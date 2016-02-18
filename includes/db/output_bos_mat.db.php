<?php

// Beer Styles
$query_scores = sprintf("SELECT b.id, a.scorePlace, b.brewJudgingNumber, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewInfo, b.brewMead1, b.brewMead2, b.brewMead3, b.brewComments FROM %s a, %s b, %s c WHERE a.eid = b.id AND c.uid = b.brewBrewerID AND a.scoreType='%s'", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $type);
if ($style_type_info[1] == "1") $query_scores .= "  AND scorePlace='1'";
if ($style_type_info[1] == "2") $query_scores .= "  AND (scorePlace='1' OR scorePlace='2')";
if ($style_type_info[1] == "3") $query_scores .= "  AND (scorePlace='1' OR scorePlace='2' OR scorePlace='3')";
$query_scores .= " ORDER BY b.brewCategorySort ASC, b.brewSubCategory ASC";
$scores = mysqli_query($connection,$query_scores) or die (mysqli_error($connection));
$row_scores = mysqli_fetch_assoc($scores);
$totalRows_scores = mysqli_num_rows($scores);

/*
// Mead Styles
$query_scores_mead = sprintf("SELECT a.scorePlace, b.brewJudgingNumber, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewInfo, b.brewMead1, b.brewMead2, b.brewMead3 FROM %s a, %s b, %s c WHERE a.eid = b.id AND c.uid = b.brewBrewerID AND scorePlace='1' AND (brewCategory = 24 OR brewCategory = 25 OR brewCategory = 26)", $judging_scores_db_table, $brewing_db_table, $brewer_db_table);
$query_scores_mead .= " ORDER BY b.brewCategorySort ASC";
$scores_mead = mysql_query($query_scores_mead, $brewing) or die(mysql_error());
$row_scores_mead = mysql_fetch_assoc($scores_mead);
$totalRows_scores_mead = mysql_num_rows($scores_mead);

// Cider Styles
$query_scores_cider = sprintf("SELECT a.scorePlace, b.brewJudgingNumber, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewInfo, b.brewMead1, b.brewMead2, b.brewMead3 FROM %s a, %s b, %s c WHERE a.eid = b.id AND c.uid = b.brewBrewerID AND scorePlace='1' AND (brewCategory = 27 OR brewCategory = 28)", $judging_scores_db_table, $brewing_db_table, $brewer_db_table);
$query_scores_cider .= " ORDER BY b.brewCategorySort ASC";
$scores_cider = mysql_query($query_scores_cider, $brewing) or die(mysql_error());
$row_scores_cider = mysql_fetch_assoc($scores_cider);
$totalRows_scores_cider = mysql_num_rows($scores_cider);
*/

?>