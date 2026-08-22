<?php

$params_scores = [];

if (($action == "default") && ($type == 4)) $query_scores = "SELECT b.id, a.scorePlace, a.scoreTable, b.brewJudgingNumber, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewInfo, b.brewMead1, b.brewMead2, b.brewMead3, b.brewComments, b.brewInfoOptional, b.brewBrewerID, b.brewPossAllergens FROM ".$prefix."judging_scores"." a, ".$prefix."brewing"." b, ".$prefix."brewer"." c WHERE a.eid = b.id AND c.uid = b.brewBrewerID AND (a.scoreType='2' OR a.scoreType='3')";

else { $query_scores = "SELECT b.id, a.scorePlace, a.scoreTable, b.brewJudgingNumber, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewInfo, b.brewMead1, b.brewMead2, b.brewMead3, b.brewComments, b.brewInfoOptional, b.brewBrewerID, b.brewPossAllergens FROM ".$prefix."judging_scores"." a, ".$prefix."brewing"." b, ".$prefix."brewer"." c WHERE a.eid = b.id AND c.uid = b.brewBrewerID AND a.scoreType=?"; $params_scores[] = $type; }

if ($action == "mini-bos") {

	$query_scores = "SELECT b.id, a.scorePlace, a.scoreTable, b.brewJudgingNumber, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewInfo, b.brewMead1, b.brewMead2, b.brewMead3, b.brewComments, b.brewInfoOptional, b.brewBrewerID, b.brewPossAllergens FROM ".$prefix."judging_scores"." a, ".$prefix."brewing"." b, ".$prefix."brewer"." c WHERE a.eid = b.id AND c.uid = b.brewBrewerID AND a.scoreTable=? AND a.scoreMiniBOS='1'";
	$params_scores = [$type];

}

else {

	if ($type == 4) { $query_scores = "SELECT b.id, a.scorePlace, a.scoreTable, b.brewJudgingNumber, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewInfo, b.brewMead1, b.brewMead2, b.brewMead3, b.brewComments, b.brewInfoOptional, b.brewBrewerID, b.brewPossAllergens FROM ".$prefix."judging_scores"." a, ".$prefix."brewing"." b, ".$prefix."brewer"." c WHERE a.eid = b.id AND c.uid = b.brewBrewerID AND (a.scoreType='2' OR a.scoreType='3')"; $params_scores = []; }

	else { $query_scores = "SELECT b.id, a.scorePlace, a.scoreTable, b.brewJudgingNumber, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewInfo, b.brewMead1, b.brewMead2, b.brewMead3, b.brewComments, b.brewInfoOptional, b.brewBrewerID, b.brewPossAllergens FROM ".$prefix."judging_scores"." a, ".$prefix."brewing"." b, ".$prefix."brewer"." c WHERE a.eid = b.id AND c.uid = b.brewBrewerID AND a.scoreType=?"; $params_scores = [$type]; }

	if ($action == "pro-am") {
		if ($sort == "1") $query_scores .= "  AND scorePlace='1'";
		if ($sort == "2") $query_scores .= "  AND (scorePlace='1' OR scorePlace='2')";
		if ($sort == "3") $query_scores .= "  AND (scorePlace='1' OR scorePlace='2' OR scorePlace='3')";
	}

	else {
		if ($style_type_info[1] == "1") $query_scores .= "  AND scorePlace='1'";
		if ($style_type_info[1] == "2") $query_scores .= "  AND (scorePlace='1' OR scorePlace='2')";
		if ($style_type_info[1] == "3") $query_scores .= "  AND (scorePlace='1' OR scorePlace='2' OR scorePlace='3')";
	}

}

if (SINGLE) { $query_scores .= " AND b.comp_id=?"; $params_scores[] = $_SESSION['comp_id']; }

$query_scores .= " ORDER BY b.brewCategorySort ASC, b.brewSubCategory ASC";
$rows_scores = ($params_scores !== []) ? $db_conn->rawQuery($query_scores, $params_scores) : $db_conn->rawQuery($query_scores);
$row_scores = ($rows_scores && count($rows_scores) > 0) ? $rows_scores[0] : null;
$totalRows_scores = $db_conn->count;
?>