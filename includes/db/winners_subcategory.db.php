<?php
if ($_SESSION['prefsStyleSet'] == "BA") $query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategory='%s' AND brewSubCategory='%s' AND brewReceived='1'", $brewing_db_table,  $style[0], $style[1]);

else $query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s' AND brewReceived='1'", $brewing_db_table,  $style[0], $style[1]);

$entry_count = mysqli_query($connection,$query_entry_count) or die (mysqli_error($connection));
$row_entry_count = mysqli_fetch_assoc($entry_count);

if ($_SESSION['prefsStyleSet'] == "BA") $query_score_count = sprintf("SELECT  COUNT(*) as 'count' FROM %s a, %s b, %s c WHERE b.brewCategory='%s' AND b.brewSubCategory='%s' AND a.eid = b.id AND a.scorePlace IS NOT NULL AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style[0], $style[1]);
else $query_score_count = sprintf("SELECT  COUNT(*) as 'count' FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND b.brewSubCategory='%s' AND a.eid = b.id AND a.scorePlace IS NOT NULL AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style[0], $style[1]);

$score_count = mysqli_query($connection,$query_score_count) or die (mysqli_error($connection));;
$row_score_count = mysqli_fetch_assoc($score_count);
?>