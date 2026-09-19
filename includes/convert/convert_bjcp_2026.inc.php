<?php
/**
 * Get all mead-only ids from db of BJCP2021 (live), BJCP2015 (orphaned, if
 * any still present), and BJCP2026 (new) mead styles. Map old ids to their
 * BJCP2026 equivalent.
 * 2026 update was mead only - see bjcp_map_2021_2026() in convert.lib.php
 * for the full old-code -> new-code mapping and the reasoning behind each
 * non-1:1 case (M2E rename, M3A/M3B reorganization, M4B/M4C fallback).
 *
 * Unlike convert_bjcp_2025.inc.php (which this mirrors structurally), this
 * script deliberately filters to brewStyleType='3' (mead) throughout, since
 * this update never needs to touch beer/cider rows at all - and builds one
 * $mapped_style_ids array keyed by *every* old mead row's own id (both
 * BJCP2021-tagged and any orphaned BJCP2015-tagged rows), each valued by
 * the new BJCP2026 code string, so both sources are migrated identically.
 */

$styles_db_table = $prefix."styles";

$db_conn->where('brewStyleVersion', array('BJCP2021','BJCP2015','BJCP2026'), 'in');
$db_conn->where('brewStyleType', '3');
$db_conn->orderBy('brewStyleVersion', 'ASC');
$db_conn->orderBy('id', 'ASC');
$rows_style_ids = $db_conn->get($styles_db_table, null, "id, brewStyleGroup, brewStyleNum, brewStyleVersion");

$styles_old = array(); // every old (2021 + 2015) mead row's id, keyed by "GroupNum" code
$styles_2026 = array(); // every new BJCP2026 mead row's id, keyed by "GroupNum" code
$mapped_style_ids = array(); // old row id -> new BJCP2026 "GroupNum" code

if (!isset($output)) $output = "";

foreach ($rows_style_ids as $row_style_ids) {

	$style_num = $row_style_ids['brewStyleGroup'].$row_style_ids['brewStyleNum'];

	if (($row_style_ids['brewStyleVersion'] == "BJCP2021") || ($row_style_ids['brewStyleVersion'] == "BJCP2015")) {
		// Both old sources use the exact same 13 group/num codes (BJCP2015's
		// mead content/codes were carried forward unchanged into BJCP2021 -
		// see bjcp_map_2021_2026()'s doc comment) - later id wins if both
		// happen to define the same code, which is harmless since both rows
		// would map to the identical new BJCP2026 target anyway.
		$styles_old[$style_num] = $row_style_ids['id'];
	}

	if ($row_style_ids['brewStyleVersion'] == "BJCP2026") {
		$styles_2026[$style_num] = $row_style_ids['id'];
	}

}

foreach ($rows_style_ids as $row_style_ids) {

	if (($row_style_ids['brewStyleVersion'] != "BJCP2021") && ($row_style_ids['brewStyleVersion'] != "BJCP2015")) continue;

	$style_num = $row_style_ids['brewStyleGroup'].$row_style_ids['brewStyleNum'];
	$mapped_style_ids[$row_style_ids['id']] = bjcp_map_2021_2026($style_num, 1, $prefix, 1);

}

/**
 * Update judge likes and dislikes from old mead codes to their BJCP2026 equivalents
 */

$db_conn->where("(brewerJudgeLikes IS NOT NULL OR brewerJudgeDislikes IS NOT NULL) OR (brewerJudgeLikes !='' OR brewerJudgeDislikes !='')");
$db_conn->orderBy('id', 'ASC');
$rows_judge_likes = $db_conn->get($prefix."brewer");
$totalRows_judge_likes = $db_conn->count;

if ($totalRows_judge_likes > 0) {

    foreach ($rows_judge_likes as $row_judge_likes) {

        $likes_arr_new = array();
        $dislikes_arr_new = array();
        $likes_new = "";
        $dislikes_new = "";
        $any_mead_likes = FALSE;
        $any_mead_dislikes = FALSE;

        if (!empty($row_judge_likes['brewerJudgeLikes'])) {
            $likes_arr = explode(",",$row_judge_likes['brewerJudgeLikes']);
            foreach ($likes_arr as $value) {
                if (array_key_exists($value, $mapped_style_ids)) {
                    $new_style_num = $mapped_style_ids[$value];
                    if (array_key_exists($new_style_num, $styles_2026)) $likes_arr_new[] = $styles_2026[$new_style_num];
                    $any_mead_likes = TRUE;
                }
                else $likes_arr_new[] = $value;
            }
        }

        if (!empty($row_judge_likes['brewerJudgeDislikes'])) {
            $dislikes_arr = explode(",",$row_judge_likes['brewerJudgeDislikes']);
            foreach ($dislikes_arr as $value) {
                if (array_key_exists($value, $mapped_style_ids)) {
                    $new_style_num = $mapped_style_ids[$value];
                    if (array_key_exists($new_style_num, $styles_2026)) $dislikes_arr_new[] = $styles_2026[$new_style_num];
                    $any_mead_dislikes = TRUE;
                }
                else $dislikes_arr_new[] = $value;
            }
        }

        if (!empty($likes_arr_new)) $likes_new = implode(",",array_unique($likes_arr_new));
        if (!empty($dislikes_arr_new)) $dislikes_new = implode(",",array_unique($dislikes_arr_new));

        if (($any_mead_likes) || ($any_mead_dislikes)) {

            $update_table = $prefix."brewer";
            $data = array(
                'brewerJudgeLikes' => $likes_new,
                'brewerJudgeDislikes' => $dislikes_new
            );
            $db_conn->where ('id', $row_judge_likes['id']);
            if ($db_conn->update ($update_table, $data)) $output .= "<li>Judge likes/dislikes updated to BJCP 2026 for ".$row_judge_likes['brewerLastName'].", ".$row_judge_likes['brewerFirstName']."</li>";
            else $output .= "<li>Judge likes/dislikes NOT updated to BJCP 2026 for ".$row_judge_likes['brewerLastName'].", ".$row_judge_likes['brewerFirstName'].". Error: ".$db_conn->getLastError()."</li>";

        }

    }

} // end if ($totalRows_judge_likes > 0)

/**
 * Update defined old mead styles for any table to BJCP2026
 */

$db_conn->orderBy('id', 'ASC');
$rows_tables = $db_conn->get($prefix."judging_tables");
$totalRows_tables = $db_conn->count;

if ($totalRows_tables > 0) {

    foreach ($rows_tables as $row_tables) {

        $table_styles_arr_new = array();
        $any_mead_styles = FALSE;

        if (!empty($row_tables['tableStyles'])) {

            $table_styles_arr = explode(",",$row_tables['tableStyles']);

            foreach ($table_styles_arr as $value) {
                if (array_key_exists($value, $mapped_style_ids)) {
                    $new_style_num = $mapped_style_ids[$value];
                    if (array_key_exists($new_style_num, $styles_2026)) $table_styles_arr_new[] = $styles_2026[$new_style_num];
                    $any_mead_styles = TRUE;
                }
                else $table_styles_arr_new[] = $value;
            }

        }

        if ($any_mead_styles) {

            $table_styles_new = implode(",",array_unique($table_styles_arr_new));

            $update_table = $prefix."judging_tables";
            $data = array('tableStyles' => $table_styles_new);
            $db_conn->where ('id', $row_tables['id']);
            if ($db_conn->update ($update_table, $data)) $output .= "<li>Table styles updated to BJCP 2026 for ".$row_tables['tableName']."</li>";
            else $output .= "<li>Table styles NOT updated to BJCP 2026 for ".$row_tables['tableName'].". Error: ".$db_conn->getLastError()."</li>";

        }

    }

} // end if ($totalRows_tables > 0)

/**
 * Update any old mead styles in the styles table as active if their old
 * counterpart was active, and deactivate the old rows.
 */

$db_conn->where('brewStyleVersion', array('BJCP2021','BJCP2015'), 'in');
$db_conn->where('brewStyleType', '3');
$db_conn->where('brewStyleActive', 'Y');
$rows_styles_active = $db_conn->get($styles_db_table);
$totalRows_styles_active = $db_conn->count;

// Deselect all BJCP2026 mead styles first, then re-activate only the ones
// whose old counterpart was actually active. Old rows are deactivated but
// never deleted (see convert_bjcp_2025.inc.php precedent) - archived
// competitions that ran under BJCP2021/BJCP2015 still resolve their mead
// style names correctly.
$update_table = $prefix."styles";
$data = array('brewStyleActive' => 'N');
$db_conn->where ('brewStyleVersion', 'BJCP2026');
$db_conn->update ($update_table, $data);

if ($totalRows_styles_active > 0) {

    foreach ($rows_styles_active as $row_styles_active) {

        $style = $row_styles_active['brewStyleGroup'].$row_styles_active['brewStyleNum'];

        if (array_key_exists($row_styles_active['id'], $mapped_style_ids)) {

            $new_style_num = $mapped_style_ids[$row_styles_active['id']];

            if (array_key_exists($new_style_num, $styles_2026)) {

                $update_table = $prefix."styles";
                $data = array('brewStyleActive' => 'Y');
                $db_conn->where ('id', $styles_2026[$new_style_num]);
                $db_conn->update ($update_table, $data);

            }

        }

    }

    // Deactivate the old mead rows now that their BJCP2026 counterparts are active.
    $update_table = $prefix."styles";
    $data = array('brewStyleActive' => 'N');
    $db_conn->where ('brewStyleVersion', array('BJCP2021','BJCP2015'), 'in');
    $db_conn->where ('brewStyleType', '3');
    $db_conn->update ($update_table, $data);

}

/**
 * Update any mead entries in the brewing table to their BJCP2026 equivalents
 */

$db_conn->orderBy('brewCategorySort', 'ASC');
$db_conn->orderBy('brewSubCategory', 'ASC');
$rows_brews = $db_conn->get($prefix."brewing", null, "id,brewName,brewCategory,brewCategorySort,brewSubCategory,brewStyle");
$totalRows_brews = $db_conn->count;

if ($totalRows_brews > 0) {

	foreach ($rows_brews as $row_brews) {

		$style = $row_brews['brewCategorySort'].$row_brews['brewSubCategory'];
        $sql = bjcp_map_2021_2026($style,0,$prefix,$row_brews['id']);
        if (!empty($sql)) $result = $db_conn->rawQuery($sql);

	}

} // end if ($totalRows_brews > 0)

$output .= "<ul>";

// Update all custom mead-style-set styles to BJCP2026 - two separate calls
// (rather than an orWhere()) so the brewStyleType='3' filter applies to
// both the NULL and 'custom' brewStyleOwn cases; an orWhere() here would
// match ANY brewStyleOwn='custom' row regardless of type, wrongly
// retagging custom beer/cider styles into this mead-only version too.
$update_table = $prefix."styles";
$data = array('brewStyleVersion' => 'BJCP2026');
$custom_update_ok = TRUE;

$db_conn->where ('brewStyleOwn', NULL, 'IS');
$db_conn->where ('brewStyleType', '3');
if (!$db_conn->update ($update_table, $data)) $custom_update_ok = FALSE;

$db_conn->where ('brewStyleOwn', 'custom');
$db_conn->where ('brewStyleType', '3');
if (!$db_conn->update ($update_table, $data)) $custom_update_ok = FALSE;

if ($custom_update_ok) $output .= "<li>Custom mead styles updated to BJCP 2026.</li>";
else $output .= "<li>Custom mead styles NOT updated to BJCP 2026. Error: ".$db_conn->getLastError()."</li>";

$update_table = $prefix."preferences";
$data = array('prefsStyleSet' => 'BJCP2026');
$db_conn->where ('id', 1);
if ($db_conn->update ($update_table, $data)) $output .= "<li>Preferences set to BJCP 2026.</li>";
else $output .= "<li>Preferences NOT set to BJCP 2026. Error: ".$db_conn->getLastError()."</li>";

$output .= "</ul>";

unset($_SESSION['prefs'.$prefix_session]);
?>
