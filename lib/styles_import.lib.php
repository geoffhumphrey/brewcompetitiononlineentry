<?php
/**
 * Module:      styles_import.lib.php
 * Description: Shared validation for the Admin-Uploaded Style Sets feature.
 *              Used by admin/styles_import.admin.php (new upload, phase 1)
 *              and includes/process/process_styles_import.inc.php (editing
 *              an already-imported set's metadata) so both stay in sync.
 */

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

    $seen_group_num = array();
    $validated_rows = array();
    $derived_categories = array();
    $derived_overall_categories = array();
    $derived_mead = array();
    $derived_cider = array();

    foreach ($rows as $row) {

        $row_errors = array();

        $group = trim((string)($row['brewStyleGroup'] ?? ''));
        $num = trim((string)($row['brewStyleNum'] ?? ''));
        $name = trim((string)($row['brewStyle'] ?? ''));
        $type_name = trim((string)($row['style_type'] ?? ''));
        $category_name = trim((string)($row['brewStyleCategory'] ?? ''));
        $overall_category_name = trim((string)($row['brewStyleOverallCategory'] ?? ''));

        if ($group === '') $row_errors[] = "brewStyleGroup is required.";
        if ($num === '') $row_errors[] = "brewStyleNum is required.";
        if ($name === '') $row_errors[] = "brewStyle (name) is required.";

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
            'brewStyleEntry' => trim((string)($row['brewStyleEntry'] ?? '')),
            'brewStyleReqSpec' => (!empty($row['brewStyleReqSpec'])) ? 1 : 0,
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
