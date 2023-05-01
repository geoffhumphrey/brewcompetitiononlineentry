<?php

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

if ($winner_style_set == "BA") $query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategory='%s' AND brewSubCategory='%s' AND brewReceived='1'", $brewing_db_table,  $style[0], $style[1]);
else $query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s'", $brewing_db_table,  $style[0], $style[1]);
$entry_count = mysqli_query($connection,$query_entry_count) or die (mysqli_error($connection));
$row_entry_count = mysqli_fetch_assoc($entry_count);

if ($winner_style_set == "BA") $query_score_count = sprintf("SELECT  COUNT(*) as 'count' FROM %s a, %s b, %s c WHERE b.brewCategory='%s' AND b.brewSubCategory='%s' AND a.scorePlace IS NOT NULL AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style[0], $style[1]);
else $query_score_count = sprintf("SELECT  COUNT(*) as 'count' FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND b.brewSubCategory='%s' AND a.scorePlace IS NOT NULL AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style[0], $style[1]);
$score_count = mysqli_query($connection,$query_score_count) or die (mysqli_error($connection));;
$row_score_count = mysqli_fetch_assoc($score_count);
?>