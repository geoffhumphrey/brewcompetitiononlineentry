<?php
$count_beer = FALSE;
$count_mead = FALSE;
$count_mead_cider = FALSE;
$count_cider = FALSE;
$other_count = FALSE;
$cat = $key;
$cat_convert = $key;

// Counts and style metadata are pre-aggregated once by the caller
// ($style_counts_by_cat, $style_counts_logged_by_cat, $style_type_by_cat)
// instead of being queried fresh for every category here. Callers that also
// need the individual logged-entry rows for a category (id/brewCategorySort/
// brewSubCategory - e.g. to list them, not just count them) provide
// $style_entry_rows_by_cat the same way; callers that don't need them (e.g.
// admin/entries_by_style.admin.php) simply never set it, so this is an empty
// array there rather than undefined.
$row_style_count = array('count' => isset($style_counts_by_cat[$cat]) ? $style_counts_by_cat[$cat] : 0);
$row_style_count_logged = array('count' => isset($style_counts_logged_by_cat[$cat]) ? $style_counts_logged_by_cat[$cat] : 0);
$rows_style_count_logged = isset($style_entry_rows_by_cat[$cat]) ? $style_entry_rows_by_cat[$cat] : array();
$row_style_type = isset($style_type_by_cat[$cat]) ? $style_type_by_cat[$cat] : array();

if ($_SESSION['prefsStyleSet'] == "BA") {

	if (($cat < 12) || (($cat > 12) && ($cat <= $ba_category_end))) {
		$count_beer = TRUE;
		$count_mead = FALSE;
		$count_mead_cider = FALSE;
		$count_cider = FALSE;
		$other_count = FALSE;
	}

	if ($cat == 12) {
		$count_beer = FALSE;
		$count_mead = FALSE;
		$count_mead_cider = TRUE;
		$count_cider = FALSE;
		$other_count = FALSE;
	}

	if ($cat > $ba_category_end) {
		$count_beer = FALSE;
		$count_mead = FALSE;
		$count_mead_cider = FALSE;
		$count_cider = FALSE;
		$other_count = TRUE;
	}

}

else {

	if ($cat <= $beer_end) {
		$count_beer = TRUE;
		$count_mead = FALSE;
		$count_cider = FALSE;
		$other_count = FALSE;
	}

	if (in_array($cat,$mead_array)) {
		$count_beer = FALSE;
		$count_mead = TRUE;
		$count_cider = FALSE;
		$other_count = FALSE;
	}

	if (in_array($cat,$cider_array)) {
		$count_beer = FALSE;
		$count_mead = FALSE;
		$count_cider = TRUE;
		$other_count = FALSE;
	}

	if ($cat > $category_end) {
		$count_beer = FALSE;
		$count_mead = FALSE;
		$count_cider = FALSE;
		$other_count = TRUE;
	}

}

if ($count_beer) {
	$style_type = "Beer";
	$style_beer_count[] = $row_style_count['count'];
	$style_beer_count_logged[] = $row_style_count_logged['count'];
}


if ($count_mead) {
	$style_type = "Mead";
	$style_mead_count[] = $row_style_count['count'];
	$style_mead_count_logged[] = $row_style_count_logged['count'];
}

if ($count_cider)  {
	$style_type = "Cider";
	$style_cider_count[] = $row_style_count['count'];
	$style_cider_count_logged[] = $row_style_count_logged['count'];
}

if ($count_mead_cider)  {
	$style_type = "Mead/Cider";
	$style_mead_cider_count[] = $row_style_count['count'];
	$style_mead_cider_count_logged[] = $row_style_count_logged['count'];
}

if ($other_count) {
		
	if ((!empty($row_style_type['brewStyleType'])) && ($row_style_type['brewStyleType'] <= 3)) $source = "bcoe";
	else  $source = "custom";

	if (empty($row_style_type['brewStyleType'])) $style_type = "other";
	else $style_type = style_type($row_style_type['brewStyleType'],"2",$source);

	if ($style_type == "Beer") {
		$style_beer_count[] = $row_style_count['count'];
		$style_beer_count_logged[] = $row_style_count_logged['count'];
	}

	elseif ($style_type == "Mead") {
		$style_mead_count[] = $row_style_count['count'];
		$style_mead_count_logged[] = $row_style_count_logged['count'];
		$style_mead_cider_count[] = $row_style_count['count'];
		$style_mead_cider_count_logged[] = $row_style_count_logged['count'];
	}

	elseif ($style_type == "Cider") {
		$style_cider_count[] = $row_style_count['count'];
		$style_cider_count_logged[] = $row_style_count_logged['count'];
		$style_mead_cider_count[] = $row_style_count['count'];
		$style_mead_cider_count_logged[] = $row_style_count_logged['count'];
	}

	else {
		$style_other_count[] = $row_style_count['count'];
		$style_other_count_logged[] = $row_style_count_logged['count'];
	}

}

?>