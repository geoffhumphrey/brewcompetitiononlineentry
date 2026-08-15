<?php

if ((isset($style)) && (is_numeric($style))) $style_pad = sprintf("%02d", $style);
else $style_pad = $style;

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

if ($winner_style_set == "BA") $db_conn->where("brewCategory", $style);
else $db_conn->where("brewCategorySort", $style_pad);
$db_conn->where("brewReceived", "1");

$row_entry_count = array('count' => 0);
$row_score_count = array('count' => 0);

// $brewing_db_table/$judging_scores_db_table/$brewer_db_table may point at an archived
// competition whose tables no longer exist - guard before querying either.
if (table_exists($brewing_db_table)) {
	$row_entry_count = $db_conn->getOne($brewing_db_table, "COUNT(*) as 'count'");
}

if ((table_exists($judging_scores_db_table)) && (table_exists($brewing_db_table)) && (table_exists($brewer_db_table))) {

	if ($winner_style_set == "BA") {
		$sql_score_count = sprintf("SELECT COUNT(*) as 'count' FROM %s a, %s b, %s c WHERE b.brewCategory=? AND a.eid = b.id AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table);
		$score_count_params = array($style);
	}
	else {
		$sql_score_count = sprintf("SELECT COUNT(*) as 'count' FROM %s a, %s b, %s c WHERE b.brewCategorySort=? AND a.eid = b.id AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table);
		$score_count_params = array($style_pad);
	}
	if (($action == "print") && ($view == "winners")) $sql_score_count .= " AND a.scorePlace IS NOT NULL";
	if (($action == "default") && ($view == "default")) $sql_score_count .= " AND a.scorePlace IS NOT NULL";
	$row_score_count = $db_conn->rawQueryOne($sql_score_count, $score_count_params);

}

?>