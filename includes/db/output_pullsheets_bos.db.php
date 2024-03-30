<?php
/*
$query_bos = sprintf("SELECT * FROM %s",$prefix."judging_scores");
if ($type == "4") $query_bos .= sprintf(" WHERE (scoreType='%s' OR scoreType='%s')", "2", "3");
else $query_bos .= sprintf(" WHERE scoreType='%s'", $type);
*/

if ($type == 4) $query_bos = sprintf("SELECT b.id, a.eid, a.scorePlace, a.scoreTable, a.scoreEntry, a.scorePlace, a.scoreType, a.scoreMiniBOS, c.brewerProAm, b.brewJudgingNumber, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewInfo, b.brewMead1, b.brewMead2, b.brewMead3, b.brewComments, b.brewInfoOptional, b.brewBrewerID, b.brewBoxNum, b.brewPossAllergens, b.brewStaffNotes, b.brewJuiceSource, b.brewABV, b.brewPouring, b.brewStyleType, b.brewPackaging FROM %s a, %s b, %s c WHERE a.eid = b.id AND c.uid = b.brewBrewerID AND (a.scoreType='%s' OR a.scoreType='%s')", $prefix."judging_scores", $prefix."brewing", $prefix."brewer", "2", "3");

else $query_bos = sprintf("SELECT b.id, a.eid, a.scorePlace, a.scoreTable, a.scoreEntry, a.scorePlace, a.scoreType, a.scoreMiniBOS, c.brewerProAm, b.brewJudgingNumber, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewInfo, b.brewMead1, b.brewMead2, b.brewMead3, b.brewComments, b.brewInfoOptional, b.brewBrewerID, b.brewBoxNum, b.brewPossAllergens, b.brewStaffNotes, b.brewJuiceSource, b.brewABV, b.brewPouring, b.brewStyleType, b.brewPackaging FROM %s a, %s b, %s c WHERE a.eid = b.id AND c.uid = b.brewBrewerID AND a.scoreType='%s'", $prefix."judging_scores", $prefix."brewing", $prefix."brewer", $type);

if (($action == "pro-am") && ($filter != "default")) {
	if ($filter == "1") $query_bos .= " AND scorePlace='1'";
	if ($filter == "2") $query_bos .= " AND (scorePlace='1' OR scorePlace='2')";
	if ($filter == "3") $query_bos .= " AND (scorePlace='1' OR scorePlace='2' OR scorePlace='3')";
}

else {
	if ($style_type_info[1] == "1") $query_bos .= " AND scorePlace='1'";
	if ($style_type_info[1] == "2") $query_bos .= " AND (scorePlace='1' OR scorePlace='2')";
	if ($style_type_info[1] == "3") $query_bos .= " AND (scorePlace='1' OR scorePlace='2' OR scorePlace='3')";
}

$query_bos .= " ORDER BY scoreTable ASC";
$bos = mysqli_query($connection,$query_bos) or die (mysqli_error($connection));
$row_bos = mysqli_fetch_assoc($bos);
$totalRows_bos = mysqli_num_rows($bos);
?>