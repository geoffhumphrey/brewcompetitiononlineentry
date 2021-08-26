<?php

if ((isset($style)) && (is_numeric($style))) $style_pad = sprintf("%02d", $style);
else $style_pad = $style;

if ($filter == "default") {
	$winner_style_set = $_SESSION['prefsStyleSet'];
}

else {
	$winner_style_set = $row_disp_archive_winners['archiveStyleSet'];
	$special_best_info_db_table = $prefix."special_best_info_".$filter;
	$judging_tables_db_table = $prefix."judging_tables_".$filter;
	$style_types_db_table = $prefix."style_types_".$filter;
	$judging_scores_db_table = $prefix."judging_scores_".$filter;
	$judging_scores_bos_db_table = $prefix."judging_scores_bos_".$filter;
}

if ($winner_style_set == "BA") $query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategory='%s' AND brewReceived='1'", $brewing_db_table,  $style);
else $query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategorySort='%s' AND brewReceived='1'", $brewing_db_table,  $style_pad);
$entry_count = mysqli_query($connection,$query_entry_count) or die (mysqli_error($connection));
$row_entry_count = mysqli_fetch_assoc($entry_count);

if ($winner_style_set == "BA")  $query_score_count = sprintf("SELECT  COUNT(*) as 'count' FROM %s a, %s b, %s c WHERE b.brewCategory='%s' AND a.eid = b.id AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style);
else $query_score_count = sprintf("SELECT COUNT(*) as 'count' FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND a.eid = b.id AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style_pad);
if (($action == "print") && ($view == "winners")) $query_score_count .= " AND a.scorePlace IS NOT NULL";
if (($action == "default") && ($view == "default")) $query_score_count .= " AND a.scorePlace IS NOT NULL";
$score_count = mysqli_query($connection,$query_score_count) or die (mysqli_error($connection));
$row_score_count = mysqli_fetch_assoc($score_count);

?>