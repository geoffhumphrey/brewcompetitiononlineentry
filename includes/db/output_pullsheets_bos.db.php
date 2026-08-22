<?php
/*
$query_bos = sprintf("SELECT * FROM %s",$prefix."judging_scores");
if ($type == "4") $query_bos .= sprintf(" WHERE (scoreType='%s' OR scoreType='%s')", "2", "3");
else $query_bos .= sprintf(" WHERE scoreType='%s'", $type);
*/

$params_bos = [];

if ($type == 4) $query_bos = "SELECT b.id, a.eid, a.scorePlace, a.scoreTable, a.scoreEntry, a.scorePlace, a.scoreType, a.scoreMiniBOS, c.brewerProAm, b.brewJudgingNumber, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewInfo, b.brewMead1, b.brewMead2, b.brewMead3, b.brewComments, b.brewInfoOptional, b.brewBrewerID, b.brewBoxNum, b.brewPossAllergens, b.brewStaffNotes, b.brewJuiceSource, b.brewABV, b.brewPouring, b.brewStyleType, b.brewPackaging FROM ".$prefix."judging_scores"." a, ".$prefix."brewing"." b, ".$prefix."brewer"." c WHERE a.eid = b.id AND c.uid = b.brewBrewerID AND (a.scoreType='2' OR a.scoreType='3')";

else {
	$query_bos = "SELECT b.id, a.eid, a.scorePlace, a.scoreTable, a.scoreEntry, a.scorePlace, a.scoreType, a.scoreMiniBOS, c.brewerProAm, b.brewJudgingNumber, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewInfo, b.brewMead1, b.brewMead2, b.brewMead3, b.brewComments, b.brewInfoOptional, b.brewBrewerID, b.brewBoxNum, b.brewPossAllergens, b.brewStaffNotes, b.brewJuiceSource, b.brewABV, b.brewPouring, b.brewStyleType, b.brewPackaging FROM ".$prefix."judging_scores"." a, ".$prefix."brewing"." b, ".$prefix."brewer"." c WHERE a.eid = b.id AND c.uid = b.brewBrewerID AND a.scoreType=?";
	$params_bos[] = $type;
}

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
$rows_bos = ($params_bos !== []) ? $db_conn->rawQuery($query_bos, $params_bos) : $db_conn->rawQuery($query_bos);
$row_bos = ($rows_bos && count($rows_bos) > 0) ? $rows_bos[0] : null;
$totalRows_bos = $db_conn->count;
?>