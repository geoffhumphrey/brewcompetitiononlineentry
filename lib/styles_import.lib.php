<?php
/**
 * Module:      styles_import.lib.php
 * Description: Shared validation for the Admin-Uploaded Style Sets feature.
 *              Used by admin/styles_import.admin.php (new upload, phase 1)
 *              and includes/process/process_styles_import.inc.php (editing
 *              an already-imported set's metadata) so both stay in sync.
 *              Also now home to style_set_export_predicate(), shared by the
 *              style-set export feature (output/export.output.php) and the
 *              built-in-sets row-count column (admin/styles_import.admin.php)
 *              since exporting isn't limited to admin-uploaded sets.
 */

/**
 * The {prefix}styles WHERE-clause needed to fetch exactly the rows that
 * make up a given style set, for export or a row count. Returns
 * array($sql, $params) for use with $db_conn->where($sql, $params).
 *
 * BJCP2025 and AABC2025 don't ship their own beer/mead styles - only their
 * updated cider styles - and pull the rest in from BJCP2021/AABC2022 by
 * brewStyleType, matching the exact predicate the app itself uses when
 * either is the active set (includes/db/styles.db.php). This is
 * one-directional: BJCP2021 and AABC2022 are each fully self-contained
 * under their own brewStyleVersion INCLUDING their own (older, un-revised)
 * cider rows - selecting either as the active set does not pull in the
 * newer "2025" cider styles, so exporting them must not either. Every
 * other set (built-in or imported) is likewise fully self-contained.
 * brewStyleOwn != 'custom' excludes stray custom-owned rows that might
 * incidentally share a brewStyleVersion stamp (a custom row's
 * brewStyleVersion is just a snapshot of whichever set was active when it
 * was last touched, not a real pointer to any particular set).
 */
function style_set_export_predicate($style_set_name) {

    if ($style_set_name == "BJCP2025") {
        return array("((brewStyleVersion='BJCP2025' AND brewStyleType='2') OR (brewStyleVersion='BJCP2021' AND brewStyleType!='2')) AND brewStyleOwn != 'custom'", array());
    }

    if ($style_set_name == "AABC2025") {
        return array("((brewStyleVersion='AABC2025' AND brewStyleType='2') OR (brewStyleVersion='AABC2022' AND brewStyleType!='2')) AND brewStyleOwn != 'custom'", array());
    }

    return array("brewStyleVersion = ? AND brewStyleOwn != 'custom'", array($style_set_name));

}

/**
 * The actual {prefix}styles rows that make up a given style set - built-in
 * or imported - via style_set_export_predicate(), with exact-content
 * duplicate rows collapsed to one each. Used by both the style-set export
 * (output/export.output.php) and the "Styles" count column
 * (admin/styles_import.admin.php) so the two numbers always agree.
 *
 * The dedup exists because AABC2022 was found to have every one of its
 * styles stored 2-3x with fully identical content - a migration guard
 * (update/run_update.php's check_new_style() gate before including
 * update/styles_aabc_2022_update.php) apparently didn't prevent re-seeding
 * on some installs. The only fields that ever differed between such
 * duplicates were brewStyleActive/brewStyleAtLimit (competition-runtime
 * state, not part of a style's own definition, and never part of the
 * exported format) - so collapsing by every other field is always
 * correct, not a heuristic, and needs no per-set special-casing to stay
 * safe if the same kind of duplication ever turns up elsewhere.
 */
function style_set_export_rows($style_set_name, $prefix, $db_conn) {

    list($predicate_sql, $predicate_params) = style_set_export_predicate($style_set_name);
    $db_conn->where($predicate_sql, $predicate_params);
    $db_conn->orderBy('brewStyleGroup', 'ASC');
    $db_conn->orderBy('brewStyleNum', 'ASC');
    $rows = $db_conn->get($prefix."styles");
    if (!$rows) return array();

    $deduped = array();
    $seen_signatures = array();
    foreach ($rows as $row) {
        $signature = implode("\x1f", array(
            $row['brewStyleGroup'], $row['brewStyleNum'], $row['brewStyle'],
            $row['brewStyleCategory'], $row['brewStyleType'],
            $row['brewStyleOG'], $row['brewStyleOGMax'], $row['brewStyleFG'], $row['brewStyleFGMax'],
            $row['brewStyleABV'], $row['brewStyleABVMax'], $row['brewStyleIBU'], $row['brewStyleIBUMax'],
            $row['brewStyleSRM'], $row['brewStyleSRMMax'], $row['brewStyleInfo'], $row['brewStyleLink'],
            $row['brewStyleEntry'], $row['brewStyleReqSpec'], $row['brewStyleStrength'],
            $row['brewStyleCarb'], $row['brewStyleSweet']
        ));
        if (isset($seen_signatures[$signature])) continue;
        $seen_signatures[$signature] = true;
        $deduped[] = $row;
    }

    return $deduped;

}

/**
 * Entry Limits by Style stores and enforces one limit per
 * style_set_categories key (a single brewStyleGroup code) - fine for the
 * built-in sets, whose own group numbering already puts the broad grouping
 * an admin wants (e.g. BJCP's "21" Cider) at that level. An imported set
 * like GABF instead gives every individual style its own group number, so
 * style_set_categories has one entry per style (~110 for GABF) and the
 * broad grouping the admin actually wants ("Ale Beer Styles", etc.) only
 * exists one tier up, in style_set_overall_categories, which maps every
 * one of those group codes to just a handful of shared values.
 *
 * Given one (representative) group code, returns every group code that
 * shares its style_set_overall_categories value - i.e. every group a
 * stored/enforced limit for $group_code should actually apply to. For a
 * set with no style_set_overall_categories defined (every built-in set,
 * and any imported set that doesn't define one), or a group code with no
 * entry there, this is always just array($group_code) - unchanged,
 * single-group behavior.
 */
function style_group_limit_siblings($style_set_name, $group_code) {

    global $style_sets;

    if (is_array($style_sets)) {
        foreach ($style_sets as $set) {
            if (empty($set['style_set_name']) || ($set['style_set_name'] != $style_set_name)) continue;
            if (empty($set['style_set_overall_categories']) || (!isset($set['style_set_overall_categories'][$group_code]))) break;
            $overall_value = $set['style_set_overall_categories'][$group_code];
            $siblings = array_keys($set['style_set_overall_categories'], $overall_value, true);
            if (!empty($siblings)) return $siblings;
            break;
        }
    }

    return array($group_code);

}

/**
 * The rolled-up rows Entry Limits by Style should actually display/store
 * one limit editor row for, given a style set's full style_set_categories
 * array: one representative key per unique style_set_overall_categories
 * value (labeled with that overall category's name) when the set defines
 * overall categories, otherwise every style_set_categories key unchanged
 * (labeled with its own name), exactly as before this rollup existed.
 * Preserves style_set_categories's own key order.
 */
function style_group_limit_rollup($style_set) {

    $rollup = array();

    if (!empty($style_set['style_set_overall_categories'])) {
        $seen_values = array();
        foreach ($style_set['style_set_categories'] as $key => $value) {
            $overall_value = $style_set['style_set_overall_categories'][$key] ?? null;
            if ($overall_value === null) {
                $rollup[$key] = $value;
                continue;
            }
            if (isset($seen_values[$overall_value])) continue;
            $seen_values[$overall_value] = true;
            $rollup[$key] = $overall_value;
        }
        return $rollup;
    }

    return $style_set['style_set_categories'];

}

/**
 * Set-level (metadata-only) validation, shared by a new upload and by
 * editing an already-imported set's metadata. $exclude_set_name lets an
 * edit pass its own current name through the "already exists" check
 * unchanged (or renamed only by case) without tripping on itself.
 */
function styles_import_validate_meta($meta, $style_sets, $exclude_set_name = null) {

    $errors_meta = array();

    if (empty($meta['style_set_name'])) $errors_meta[] = "Style set name is required.";
    elseif (!preg_match('/^[A-Za-z0-9_-]{1,20}$/', $meta['style_set_name'])) $errors_meta[] = "Style set name must be 1-20 characters, letters/numbers/underscore/hyphen only.";
    else {
        foreach ($style_sets as $existing_set) {
            if (empty($existing_set['style_set_name'])) continue;
            if (($exclude_set_name !== null) && (strcasecmp($existing_set['style_set_name'], $exclude_set_name) === 0)) continue;
            if (strcasecmp($existing_set['style_set_name'], $meta['style_set_name']) === 0) {
                $errors_meta[] = "A style set named \"" . $meta['style_set_name'] . "\" already exists. Choose a different name.";
                break;
            }
        }
    }

    if (empty($meta['style_set_long_name'])) $errors_meta[] = "Style set long name is required.";
    if (empty($meta['style_set_short_name'])) $errors_meta[] = "Style set short name is required.";
    if (empty($meta['style_set_beer_end']) || (!ctype_digit((string)$meta['style_set_beer_end']))) $errors_meta[] = "Beer category end number is required and must be numeric.";
    if (empty($meta['style_set_category_end']) || (!ctype_digit((string)$meta['style_set_category_end']))) $errors_meta[] = "Category end number is required and must be numeric.";

    return $errors_meta;

}

/**
 * Per-row and set-level validation. Returns the full report (meta,
 * validated rows each flagged valid/invalid+error, derived categories) -
 * this whole structure is what gets staged in session between phase 1
 * and phase 2, per the plan's two-phase flow.
 */
function styles_import_validate($meta, $rows, $style_sets, $prefix, $db_conn) {

    $errors_meta = styles_import_validate_meta($meta, $style_sets);

    // Resolve style_type names to ids once, case-insensitively - covers
    // admin-defined custom types, not just the three built-ins.
    $rows_style_types = $db_conn->get($prefix."style_types");
    $style_type_lookup = array();
    if ($rows_style_types) {
        foreach ($rows_style_types as $row_style_type) {
            $style_type_lookup[strtolower(trim($row_style_type['styleTypeName']))] = $row_style_type['id'];
        }
    }

    // Normalize purely-numeric group/sub-style numbers to a consistent
    // zero-padded width, so the string-based ORDER BY sorts used throughout
    // the app (including the Add Entry style dropdown) come out in numeric
    // order (001, 002, ... 010 ...) instead of lexical order (1, 10, 100,
    // 101, 2, 20, ...). This matters even for a source file that WAS
    // correctly zero-padded, because spreadsheet software (Excel, Sheets)
    // silently strips leading zeros from a numeric-looking CSV column on
    // open/re-save. Group width comes from the admin-provided "Last
    // Category #" field; Num width (Numeric sub-style method only) is
    // derived from the widest numeric Num actually present in this upload,
    // since there's no equivalent explicit bound for it.
    $group_pad_width = (!empty($meta['style_set_category_end'])) ? strlen((string)$meta['style_set_category_end']) : 0;

    $num_pad_width = 0;
    if (!empty($meta['style_set_sub_style_method'])) { // "1" = Numeric
        foreach ($rows as $row) {
            $num_candidate = trim((string)($row['brewStyleNum'] ?? ''));
            if ((ctype_digit($num_candidate)) && (strlen($num_candidate) > $num_pad_width)) $num_pad_width = strlen($num_candidate);
        }
    }

    $seen_group_num = array();
    $validated_rows = array();
    $derived_categories = array();
    $derived_overall_categories = array();
    $derived_mead = array();
    $derived_cider = array();

    foreach ($rows as $row) {

        $row_errors = array();

        // is_scalar() guards against a malformed source file where one of
        // these fields is accidentally an array/object (e.g. a JSON
        // authoring slip like "brewStyleGroup": ["1"]) - without it,
        // (string) on an array silently produces the literal text "Array"
        // instead of a PHP warning, which would then pass every empty-
        // string check below as if it were a real, valid value.
        $group = ((isset($row['brewStyleGroup'])) && (is_scalar($row['brewStyleGroup']))) ? trim((string)$row['brewStyleGroup']) : '';
        $num = ((isset($row['brewStyleNum'])) && (is_scalar($row['brewStyleNum']))) ? trim((string)$row['brewStyleNum']) : '';
        if ((ctype_digit($group)) && ($group_pad_width > 0)) $group = str_pad($group, $group_pad_width, '0', STR_PAD_LEFT);
        if ((ctype_digit($num)) && ($num_pad_width > 0)) $num = str_pad($num, $num_pad_width, '0', STR_PAD_LEFT);
        $name = ((isset($row['brewStyle'])) && (is_scalar($row['brewStyle']))) ? trim((string)$row['brewStyle']) : '';
        $type_name = ((isset($row['style_type'])) && (is_scalar($row['style_type']))) ? trim((string)$row['style_type']) : '';
        $category_name = ((isset($row['brewStyleCategory'])) && (is_scalar($row['brewStyleCategory']))) ? trim((string)$row['brewStyleCategory']) : '';
        $overall_category_name = ((isset($row['brewStyleOverallCategory'])) && (is_scalar($row['brewStyleOverallCategory']))) ? trim((string)$row['brewStyleOverallCategory']) : '';
        $entry_text = ((isset($row['brewStyleEntry'])) && (is_scalar($row['brewStyleEntry']))) ? trim((string)$row['brewStyleEntry']) : '';
        $req_spec = (!empty($row['brewStyleReqSpec'])) ? 1 : 0;

        if ($group === '') $row_errors[] = "brewStyleGroup is required.";
        if ($num === '') $row_errors[] = "brewStyleNum is required.";
        if ($name === '') $row_errors[] = "brewStyle (name) is required.";
        if ($category_name === '') $row_errors[] = "brewStyleCategory is required.";
        if (($req_spec == 1) && ($entry_text === '')) $row_errors[] = "brewStyleEntry is required when brewStyleReqSpec (Required Info) is set to 1.";

        $resolved_type_id = null;
        if ($type_name === '') $row_errors[] = "style_type is required.";
        elseif (!isset($style_type_lookup[strtolower($type_name)])) $row_errors[] = "style_type \"" . $type_name . "\" does not match any defined style type - add it first via Manage Style Types.";
        else $resolved_type_id = $style_type_lookup[strtolower($type_name)];

        if (($group !== '') && ($num !== '')) {
            $key = $group . "|" . $num;
            if (isset($seen_group_num[$key])) $row_errors[] = "Duplicate group/num (" . $group . "/" . $num . ") within this upload.";
            else $seen_group_num[$key] = TRUE;
        }

        $numeric_fields = array(
            'brewStyleOG' => array(0.990, 1.150), 'brewStyleOGMax' => array(0.990, 1.150),
            'brewStyleFG' => array(0.980, 1.060), 'brewStyleFGMax' => array(0.980, 1.060),
            'brewStyleABV' => array(0, 20), 'brewStyleABVMax' => array(0, 20),
            'brewStyleIBU' => array(0, 120), 'brewStyleIBUMax' => array(0, 120),
            'brewStyleSRM' => array(0, 500), 'brewStyleSRMMax' => array(0, 500)
        );

        foreach ($numeric_fields as $field => $range) {
            $val = trim((string)($row[$field] ?? ''));
            if ($val === '') continue;
            if (!is_numeric($val)) { $row_errors[] = $field." must be numeric."; continue; }
            if (($val < $range[0]) || ($val > $range[1])) $row_errors[] = $field." (".$val.") is outside the expected range (".$range[0]."-".$range[1].").";
        }

        if ((isset($row['brewStyleOGMax'])) && (trim((string)$row['brewStyleOGMax']) !== '') && (isset($row['brewStyleOG'])) && (trim((string)$row['brewStyleOG']) !== '') && (is_numeric($row['brewStyleOGMax'])) && (is_numeric($row['brewStyleOG'])) && ($row['brewStyleOGMax'] < $row['brewStyleOG'])) $row_errors[] = "brewStyleOGMax must be >= brewStyleOG.";
        if ((isset($row['brewStyleFGMax'])) && (trim((string)$row['brewStyleFGMax']) !== '') && (isset($row['brewStyleFG'])) && (trim((string)$row['brewStyleFG']) !== '') && (is_numeric($row['brewStyleFGMax'])) && (is_numeric($row['brewStyleFG'])) && ($row['brewStyleFGMax'] < $row['brewStyleFG'])) $row_errors[] = "brewStyleFGMax must be >= brewStyleFG.";
        if ((isset($row['brewStyleABVMax'])) && (trim((string)$row['brewStyleABVMax']) !== '') && (isset($row['brewStyleABV'])) && (trim((string)$row['brewStyleABV']) !== '') && (is_numeric($row['brewStyleABVMax'])) && (is_numeric($row['brewStyleABV'])) && ($row['brewStyleABVMax'] < $row['brewStyleABV'])) $row_errors[] = "brewStyleABVMax must be >= brewStyleABV.";
        if ((isset($row['brewStyleIBUMax'])) && (trim((string)$row['brewStyleIBUMax']) !== '') && (isset($row['brewStyleIBU'])) && (trim((string)$row['brewStyleIBU']) !== '') && (is_numeric($row['brewStyleIBUMax'])) && (is_numeric($row['brewStyleIBU'])) && ($row['brewStyleIBUMax'] < $row['brewStyleIBU'])) $row_errors[] = "brewStyleIBUMax must be >= brewStyleIBU.";
        if ((isset($row['brewStyleSRMMax'])) && (trim((string)$row['brewStyleSRMMax']) !== '') && (isset($row['brewStyleSRM'])) && (trim((string)$row['brewStyleSRM']) !== '') && (is_numeric($row['brewStyleSRMMax'])) && (is_numeric($row['brewStyleSRM'])) && ($row['brewStyleSRMMax'] < $row['brewStyleSRM'])) $row_errors[] = "brewStyleSRMMax must be >= brewStyleSRM.";

        if (($group !== '') && ($category_name !== '') && (!isset($derived_categories[$group]))) $derived_categories[$group] = $category_name;
        if (($group !== '') && ($overall_category_name !== '') && (!isset($derived_overall_categories[$group]))) $derived_overall_categories[$group] = $overall_category_name;

        $validated_rows[] = array(
            'valid' => empty($row_errors),
            'errors' => $row_errors,
            'brewStyleGroup' => $group,
            'brewStyleNum' => $num,
            'brewStyle' => $name,
            'brewStyleCategory' => $category_name,
            'brewStyleOverallCategory' => $overall_category_name,
            'style_type_name' => $type_name,
            'brewStyleType' => $resolved_type_id,
            'brewStyleOG' => trim((string)($row['brewStyleOG'] ?? '')),
            'brewStyleOGMax' => trim((string)($row['brewStyleOGMax'] ?? '')),
            'brewStyleFG' => trim((string)($row['brewStyleFG'] ?? '')),
            'brewStyleFGMax' => trim((string)($row['brewStyleFGMax'] ?? '')),
            'brewStyleABV' => trim((string)($row['brewStyleABV'] ?? '')),
            'brewStyleABVMax' => trim((string)($row['brewStyleABVMax'] ?? '')),
            'brewStyleIBU' => trim((string)($row['brewStyleIBU'] ?? '')),
            'brewStyleIBUMax' => trim((string)($row['brewStyleIBUMax'] ?? '')),
            'brewStyleSRM' => trim((string)($row['brewStyleSRM'] ?? '')),
            'brewStyleSRMMax' => trim((string)($row['brewStyleSRMMax'] ?? '')),
            'brewStyleInfo' => trim((string)($row['brewStyleInfo'] ?? '')),
            'brewStyleLink' => trim((string)($row['brewStyleLink'] ?? '')),
            'brewStyleEntry' => $entry_text,
            'brewStyleReqSpec' => $req_spec,
            'brewStyleStrength' => (!empty($row['brewStyleStrength'])) ? 1 : 0,
            'brewStyleCarb' => (!empty($row['brewStyleCarb'])) ? 1 : 0,
            'brewStyleSweet' => (!empty($row['brewStyleSweet'])) ? 1 : 0
        );

    }

    return array(
        'meta' => $meta,
        'meta_errors' => $errors_meta,
        'rows' => $validated_rows,
        'derived_categories' => $derived_categories,
        'derived_overall_categories' => $derived_overall_categories
    );

}
?>
