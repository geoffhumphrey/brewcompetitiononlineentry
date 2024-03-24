<?php

if ($winner_method != 0) {
	if ((isset($style)) && (is_numeric($style))) $style_pad = sprintf("%02d", $style);
	else $style_pad = $style;
}

// Display by Table
if ($winner_method == 0) $query_scores = sprintf("SELECT * FROM %s a, %s b, %s c WHERE a.scoreTable='%s' AND a.eid = b.id AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $row_tables['id']);

// Display by Category
if ($winner_method == 1) {

    if ($style_set == "BA") $query_scores = sprintf("SELECT * FROM %s a, %s b, %s c WHERE b.brewCategory='%s' AND a.eid = b.id AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style);

    else $query_scores = sprintf("SELECT * FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND a.eid = b.id AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style_pad);

}

// Display by Subcategory
if ($winner_method == 2) {

    if ($style_set == "BA") $query_scores = sprintf("SELECT * FROM %s a, %s b, %s c WHERE b.brewSubCategory='%s' AND a.eid = b.id  AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style[1]);

    else $query_scores = sprintf("SELECT * FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND b.brewSubCategory='%s' AND a.eid = b.id  AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style[0], $style[1]);

}

if ((($action == "print") && ($view == "winners")) || ($action == "default") || ($section == "default")) $query_scores .= " AND a.scorePlace > 0";
if ($action == "awards-pres") $query_scores .= " ORDER BY a.scorePlace DESC";
else $query_scores .= " ORDER BY a.scorePlace ASC";
$scores = mysqli_query($connection,$query_scores) or die (mysqli_error($connection));
$row_scores = mysqli_fetch_assoc($scores);
$totalRows_scores = mysqli_num_rows($scores);
?>