<?php
/**
 * Get all ids from db of BA and BA2026 styles.
 * Map ids from BA to BA2026.
 *
 * Unlike the AABC2022->AABC2025 and BJCP delta conversions, BA2026 replaces
 * the old BA style set with an entirely renumbered one (see
 * includes/styles.inc.php) - old and new brewStyleGroup/brewStyleNum values
 * are NOT shared between versions. So, unlike aabc_map_2022_2025(),
 * ba_map_2026() has no safe "leave $style unchanged" fallback: a handful of
 * old BA styles (Mead/Cider/Perry, Malternative Beverages, and a few beer
 * styles with no confident 2026 analog) simply have no case in the switch
 * and return an empty string from every method. Those old styles - and any
 * brewing-table entries, judge likes/dislikes, or table style assignments
 * still using them - are deliberately left untouched on 'BA' rather than
 * guessed at.
 */

$styles_db_table = $prefix."styles";

$db_conn->where('brewStyleVersion', array('BA','BA2026'), 'in');
$db_conn->orderBy('brewStyleVersion', 'ASC');
$db_conn->orderBy('id', 'ASC');
$rows_style_ids = $db_conn->get($styles_db_table, null, "id, brewStyleGroup, brewStyleNum, brewStyleVersion");

$styles_ba = array();
$styles_ba2026 = array();
$mapped_style_ids = array();

if (!isset($output)) $output = "";

foreach ($rows_style_ids as $row_style_ids) {

	$style_num = $row_style_ids['brewStyleGroup']."-".$row_style_ids['brewStyleNum'];

	if ($row_style_ids['brewStyleVersion'] == "BA") {
		$styles_ba[$style_num] = $row_style_ids['id'];
	}

	if ($row_style_ids['brewStyleVersion'] == "BA2026") {
		$styles_ba2026[$style_num] = $row_style_ids['id'];
	}

}

foreach ($styles_ba as $key => $id_ba) {
	// Convert the BA style to BA2026 - only record a mapping when one
	// genuinely exists; unmapped old styles are left out entirely so
	// they can never be mistaken for a valid BA2026 style downstream.
	$mapped_style_to_2026 = ba_map_2026($key,1,$prefix,1);
	if (!empty($mapped_style_to_2026)) $mapped_style_ids[$id_ba] = $mapped_style_to_2026;
}

/**
 * Update judge likes and dislikes from BA to analogous BA2026 styles.
 * Old likes/dislikes with no BA2026 counterpart are dropped from the list
 * (there is no way to represent them under the new style set).
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

		if (!empty($row_judge_likes['brewerJudgeLikes'])) {
			$likes_arr = explode(",",$row_judge_likes['brewerJudgeLikes']);
			foreach ($likes_arr as $value) {
				if ((array_key_exists($value, $mapped_style_ids)) && (array_key_exists($mapped_style_ids[$value], $styles_ba2026))) {
					$likes_arr_new[] = $styles_ba2026[$mapped_style_ids[$value]];
				}
			}
		}

		if (!empty($row_judge_likes['brewerJudgeDislikes'])) {
			$dislikes_arr = explode(",",$row_judge_likes['brewerJudgeDislikes']);
			foreach ($dislikes_arr as $value) {
				if ((array_key_exists($value, $mapped_style_ids)) && (array_key_exists($mapped_style_ids[$value], $styles_ba2026))) {
					$dislikes_arr_new[] = $styles_ba2026[$mapped_style_ids[$value]];
				}
			}
		}

		if (!empty($likes_arr_new)) $likes_new = implode(",",$likes_arr_new);
		if (!empty($dislikes_arr_new)) $dislikes_new = implode(",",$dislikes_arr_new);

		if ((!empty($row_judge_likes['brewerJudgeLikes'])) || (!empty($row_judge_likes['brewerJudgeDislikes']))) {

			$update_table = $prefix."brewer";
			$data = array(
				'brewerJudgeLikes' => $likes_new,
				'brewerJudgeDislikes' => $dislikes_new
			);
			$db_conn->where ('id', $row_judge_likes['id']);
			if ($db_conn->update ($update_table, $data)) $output .= "<li>Judge likes updated to BA 2026 for ".$row_judge_likes['brewerLastName'].", ".$row_judge_likes['brewerFirstName']."</li>";
			else $output .= "<li>Judge likes NOT updated to BA 2026 for ".$row_judge_likes['brewerLastName'].", ".$row_judge_likes['brewerFirstName'].". Error: ".$db_conn->getLastError()."</li>";

		}

	}

} // end if ($totalRows_judge_likes > 0)

/**
 * Update defined BA styles for any judging table to BA2026. Old style ids
 * with no BA2026 counterpart are dropped from the table's assigned list.
 */

$db_conn->orderBy('id', 'ASC');
$rows_tables = $db_conn->get($prefix."judging_tables");
$totalRows_tables = $db_conn->count;

if ($totalRows_tables > 0) {

	foreach ($rows_tables as $row_tables) {

		$table_styles_arr_new = array();

		if (!empty($row_tables['tableStyles'])) {

			$table_styles_arr = explode(",",$row_tables['tableStyles']);

			foreach ($table_styles_arr as $value) {
				if ((array_key_exists($value, $mapped_style_ids)) && (array_key_exists($mapped_style_ids[$value], $styles_ba2026))) {
					$table_styles_arr_new[] = $styles_ba2026[$mapped_style_ids[$value]];
				}
			}

			$table_styles_new = implode(",",$table_styles_arr_new);

			$update_table = $prefix."judging_tables";
			$data = array('tableStyles' => $table_styles_new);
			$db_conn->where ('id', $row_tables['id']);
			if ($db_conn->update ($update_table, $data)) $output .= "<li>Table styles updated to BA 2026 for ".$row_tables['tableName']."</li>";
			else $output .= "<li>Table styles NOT updated to BA 2026 for ".$row_tables['tableName'].". Error: ".$db_conn->getLastError()."</li>";

		}

	}

} // end if ($totalRows_tables > 0)

/**
 * Update any BA2026 styles in the styles table as active if their BA
 * counterpart was active as well. Styles with no BA counterpart, or whose
 * BA counterpart has no BA2026 mapping, are left at their seeded default.
 */

$db_conn->where('brewStyleVersion', 'BA');
$db_conn->where('brewStyleActive', 'Y');
$rows_styles_active = $db_conn->get($styles_db_table);
$totalRows_styles_active = $db_conn->count;

if ($totalRows_styles_active > 0) {

	// First, "deselect" all styles in the DB for BA2026
	$update_table = $prefix."styles";
	$data = array('brewStyleActive' => 'N');
	$db_conn->where ('brewStyleVersion', 'BA2026');
	$db_conn->update ($update_table, $data);

	if (HOSTED) {
		$update_table = $styles_db_table;
		$data = array('brewStyleActive' => 'N');
		$db_conn->where ('brewStyleVersion', 'BA2026');
		$result = $db_conn->update ($update_table, $data);
	}

	foreach ($rows_styles_active as $row_styles_active) {

		$style = $row_styles_active['brewStyleGroup']."-".$row_styles_active['brewStyleNum'];

		if ((array_key_exists($style, $mapped_style_ids)) && (array_key_exists($mapped_style_ids[$style], $styles_ba2026))) {

			$id = $styles_ba2026[$mapped_style_ids[$style]];

			$update_table = $prefix."styles";
			$data = array('brewStyleActive' => 'Y');
			$db_conn->where ('id', $id);
			$result = $db_conn->update ($update_table, $data);

			if (HOSTED) {
				$update_table = $styles_db_table;
				$data = array('brewStyleActive' => 'Y');
				$db_conn->where ('id', $id);
				$result = $db_conn->update ($update_table, $data);
			}

		}

	}

} // end if ($totalRows_styles_active > 0)

/**
 * Update any entries in the brewing table to analogous BA2026 styles.
 * Entries whose current style has no BA2026 mapping (Mead/Cider/Perry,
 * Malternative Beverages, or a handful of dropped beer styles) are left
 * untouched on their existing BA style codes.
 */

$db_conn->where('brewCategorySort', array('12','14'), 'not in');
$db_conn->orderBy('brewCategorySort', 'ASC');
$db_conn->orderBy('brewSubCategory', 'ASC');
$rows_brews = $db_conn->get($prefix."brewing", null, "id,brewName,brewCategory,brewCategorySort,brewSubCategory,brewStyle");
$totalRows_brews = $db_conn->count;

$current_active = array();

if ($totalRows_brews > 0) {

	foreach ($rows_brews as $row_brews) {

		$style = $row_brews['brewCategorySort']."-".$row_brews['brewSubCategory'];
		$sql = ba_map_2026($style,0,$prefix,$row_brews['id']);
		if (!empty($sql)) {
			$current_active[] = ba_map_2026($style,2,$prefix,$row_brews['id']);
			$result = $db_conn->rawQuery($sql);
		}

	}

} // end if ($totalRows_brews > 0)

// Activate all styles that have been converted.
// Failsafe just in case comp converts during entry window.

if (!empty($current_active)) {

	$update_table = $prefix."styles";

	foreach($current_active as $value) {

		$style_parts = explode("-", $value);
		$data = array('brewStyleActive' => 'Y');
		$db_conn->where ('brewStyleGroup', $style_parts[0]);
		$db_conn->where ('brewStyleNum', $style_parts[1]);
		$db_conn->where ('brewStyleVersion', 'BA2026');
		$db_conn->update ($update_table, $data);

	}

}

$output .= "<ul>";

// Update all custom styles
$update_table = $prefix."styles";
$data = array('brewStyleVersion' => 'BA2026');
$db_conn->where ('brewStyleOwn', NULL, 'IS');
$db_conn->orWhere ('brewStyleOwn', 'custom');
if ($db_conn->update ($update_table, $data)) $output .= "<li>Custom styles updated to BA 2026.</li>";
else $output .= "<li>Custom styles NOT updated to BA 2026. Error: ".$db_conn->getLastError()."</li>";

$update_table = $prefix."preferences";
$data = array('prefsStyleSet' => 'BA2026');
$db_conn->where ('id', 1);
if ($db_conn->update ($update_table, $data)) $output .= "<li>Preferences set to BA 2026.</li>";
else $output .= "<li>Preferences NOT set to BA 2026. Error: ".$db_conn->getLastError()."</li>";

$output .= "</ul>";

unset($_SESSION['prefs'.$prefix_session]);
?>
