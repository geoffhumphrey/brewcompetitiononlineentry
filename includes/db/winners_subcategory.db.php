<?php
declare(strict_types=1);

if ($filter == "default") {
	$winner_style_set = $_SESSION['prefsStyleSet'];
}

else {
	$winner_style_set = $row_disp_archive_winners['archiveStyleSet'];
	$filter_clean = preg_replace("/[^a-zA-Z0-9]+/", "", $filter);
	$special_best_info_db_table = $prefix."special_best_info_".$filter_clean;
	$judging_tables_db_table = $prefix."judging_tables_".$filter_clean;
	$style_types_db_table = $prefix."style_types_".$filter_clean;
	$judging_scores_db_table = $prefix."judging_scores_".$filter_clean;
	$judging_scores_bos_db_table = $prefix."judging_scores_bos_".$filter_clean;
}

if ($winner_style_set == "BA") {
	$category_column = "brewCategory";
	$db_conn->where($category_column, $value['brewStyleGroup']);
	$db_conn->where("brewSubCategory", $value['brewStyleNum']);
	$db_conn->where("brewReceived", "1");
}
else {
	$category_column = "brewCategorySort";
	$db_conn->where($category_column, $value['brewStyleGroup']);
	$db_conn->where("brewSubCategory", $value['brewStyleNum']);
}

$row_entry_count = array('count' => 0);
$row_score_count = array('count' => 0);

// $brewing_db_table/$judging_scores_db_table/$brewer_db_table may point at an archived
// competition whose tables no longer exist - guard before querying either.
if (table_exists($brewing_db_table)) {
	$row_entry_count = $db_conn->getOne($brewing_db_table, "COUNT(*) as 'count'");
}

if ((table_exists($judging_scores_db_table)) && (table_exists($brewing_db_table)) && (table_exists($brewer_db_table))) {
	$sql_score_count = sprintf("SELECT COUNT(*) as 'count' FROM %s a, %s b, %s c WHERE b.%s=? AND b.brewSubCategory=? AND a.scorePlace IS NOT NULL AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $category_column);
	$row_score_count = $db_conn->rawQueryOne($sql_score_count, array($value['brewStyleGroup'], $value['brewStyleNum']));
}
?>