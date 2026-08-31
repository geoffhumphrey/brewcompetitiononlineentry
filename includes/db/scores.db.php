<?php

if ($winner_method != 0) {

    if ($winner_method == 2) $style = $value['brewStyleGroup'].$value['brewStyleNum'];
	if ((isset($style)) && (is_numeric($style))) $style_pad = sprintf("%02d", $style);
	else $style_pad = $style;

}

// Display by Table
if ($winner_method == 0) {
    $query_scores = "SELECT * FROM ".$judging_scores_db_table." a, ".$brewing_db_table." b, ".$brewer_db_table." c WHERE a.scoreTable=? AND a.eid = b.id AND c.uid = b.brewBrewerID";
    $params_scores = array($row_tables['id']);
}

// Display by Category
if ($winner_method == 1) {

    if (style_set_no_numbering($style_set)) {
        $query_scores = "SELECT * FROM ".$judging_scores_db_table." a, ".$brewing_db_table." b, ".$brewer_db_table." c WHERE b.brewCategory=? AND a.eid = b.id AND c.uid = b.brewBrewerID";
        $params_scores = array($style);
    }

    else {
        $query_scores = "SELECT * FROM ".$judging_scores_db_table." a, ".$brewing_db_table." b, ".$brewer_db_table." c WHERE b.brewCategorySort=? AND a.eid = b.id AND c.uid = b.brewBrewerID";
        $params_scores = array($style_pad);
    }

}

// Display by Subcategory
// BA2026's brewStyleNum was made globally unique to match old BA's own flat numbering
// scheme (see the BA2026 "num-uniqueness" work), so the bare-brewSubCategory query below
// is safe for both.
if ($winner_method == 2) {

    if (style_set_no_numbering($style_set)) {
        $query_scores = "SELECT * FROM ".$judging_scores_db_table." a, ".$brewing_db_table." b, ".$brewer_db_table." c WHERE b.brewSubCategory=? AND a.eid = b.id  AND c.uid = b.brewBrewerID";
        $params_scores = array($value['brewStyleNum']);
    }

    else {
        $query_scores = "SELECT * FROM ".$judging_scores_db_table." a, ".$brewing_db_table." b, ".$brewer_db_table." c WHERE b.brewCategorySort=? AND b.brewSubCategory=? AND a.eid = b.id  AND c.uid = b.brewBrewerID";
        $params_scores = array($value['brewStyleGroup'], $value['brewStyleNum']);
    }

}

if ((($action == "print") && ($view == "winners")) || ($action == "default") || ($section == "default")) $query_scores .= " AND a.scorePlace > 0";
if ($action == "awards-pres") $query_scores .= " ORDER BY a.scorePlace DESC";
else $query_scores .= " ORDER BY a.scorePlace ASC";
$rows_scores = $db_conn->rawQuery($query_scores, $params_scores);
$row_scores = ($rows_scores && count($rows_scores) > 0) ? $rows_scores[0] : null;
$totalRows_scores = $db_conn->count;
?>