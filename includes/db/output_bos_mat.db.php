<?php

$query_scores = sprintf("SELECT b.id, a.scorePlace, b.brewJudgingNumber, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewInfo, b.brewMead1, b.brewMead2, b.brewMead3, b.brewComments, b.brewInfoOptional FROM %s a, %s b, %s c WHERE a.eid = b.id AND c.uid = b.brewBrewerID AND a.scoreType='%s'", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $type);
if ($style_type_info[1] == "1") $query_scores .= "  AND scorePlace='1'";
if ($style_type_info[1] == "2") $query_scores .= "  AND (scorePlace='1' OR scorePlace='2')";
if ($style_type_info[1] == "3") $query_scores .= "  AND (scorePlace='1' OR scorePlace='2' OR scorePlace='3')";
if (SINGLE) $query_scores .= sprintf(" AND b.comp_id='%s'",$_SESSION['comp_id']);
$query_scores .= " ORDER BY b.brewCategorySort ASC, b.brewSubCategory ASC";
$scores = mysqli_query($connection,$query_scores) or die (mysqli_error($connection));
$row_scores = mysqli_fetch_assoc($scores);
$totalRows_scores = mysqli_num_rows($scores);

?>