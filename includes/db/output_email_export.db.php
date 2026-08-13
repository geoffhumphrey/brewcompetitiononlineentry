<?php

//@single
if ($bid != "") {
	$db_conn->where('id', $bid);
	$row_judging = $db_conn->getOne($prefix."judging_locations", "judgingLocName");
}

$params_sql = array();

if ($filter == "judges") {
	$query_sql = "SELECT a.brewerEmail, a.brewerFirstName, a.brewerLastName, a.brewerJudgeLocation, a.brewerStewardLocation, a.uid, a.brewerJudgeRank, a.brewerJudgeID, a.brewerJudgeLikes, a.brewerJudgeDislikes, a.brewerJudgeMead, a.brewerJudgeCider, b.uid FROM ".$prefix."brewer"." a, ".$prefix."staff"." b WHERE b.staff_judge='1' AND a.uid = b.uid";
	if (SINGLE) { $query_sql .= " AND b.comp_id=?"; $params_sql[] = $_SESSION['comp_id']; }
	$query_sql .= " ORDER BY a.brewerLastName,a.brewerFirstName ASC";
}

elseif ($filter == "stewards") {
	$query_sql = "SELECT a.brewerEmail, a.brewerFirstName, a.brewerLastName, a.uid, a.brewerJudgeRank, a.brewerJudgeID, a.brewerJudgeLocation, a.brewerStewardLocation, a.brewerJudgeLikes, a.brewerJudgeDislikes, b.uid FROM ".$prefix."brewer"." a, ".$prefix."staff"." b WHERE b.staff_steward='1' AND a.uid=b.uid";
	if (SINGLE) { $query_sql .= " AND b.comp_id=?"; $params_sql[] = $_SESSION['comp_id']; }
	$query_sql .= " ORDER BY a.brewerLastName,a.brewerFirstName ASC";
}

elseif ($filter == "staff") {
	$query_sql = "SELECT a.brewerEmail, a.brewerFirstName, a.brewerLastName, a.uid, a.brewerJudgeRank, a.brewerJudgeID, a.brewerJudgeLocation, a.brewerStewardLocation, a.brewerStaff, b.uid, b.staff_staff FROM ".$prefix."brewer"." a, ".$prefix."staff"." b WHERE a.brewerStaff='Y' AND a.uid=b.uid";
	if (SINGLE) { $query_sql .= " AND b.comp_id=?"; $params_sql[] = $_SESSION['comp_id']; }
	$query_sql .= " ORDER BY a.brewerLastName,a.brewerFirstName ASC";
}

//@single
elseif ($filter == "avail_judges")  {
	$query_sql = "SELECT uid, brewerFirstName, brewerLastName, brewerEmail, brewerJudge, brewerJudgeRank, brewerJudgeID, brewerSteward, brewerJudgeLocation, brewerStewardLocation, brewerJudgeLikes, brewerJudgeDislikes, brewerJudgeMead, brewerJudgeCider FROM ".$prefix."brewer"." WHERE brewerJudge='Y'";
	$query_sql .= " ORDER BY brewerLastName,brewerFirstName ASC";
}

//@single
elseif ($filter == "avail_stewards") {
	$query_sql = "SELECT uid, brewerFirstName, brewerLastName, brewerEmail, brewerJudge, brewerJudgeRank, brewerJudgeID, brewerSteward, brewerJudgeLocation, brewerStewardLocation, brewerJudgeLikes, brewerJudgeDislikes FROM ".$prefix."brewer"." WHERE brewerSteward='Y'";
	$query_sql .= " ORDER BY brewerLastName,brewerFirstName ASC";
}

//@single
else {
	$query_sql = "SELECT uid, brewerFirstName, brewerLastName, brewerEmail, brewerAddress, brewerCity, brewerState, brewerZip, brewerCountry, brewerPhone1, brewerClubs, brewerJudge, brewerJudgeRank, brewerJudgeID, brewerJudgeMead, brewerJudgeCider, brewerSteward, brewerJudgeLocation, brewerStewardLocation, brewerBreweryName, brewerBreweryInfo FROM ".$prefix."brewer";
	$query_sql .= " ORDER BY brewerLastName ASC";
}

$rows_sql = (!empty($params_sql)) ? $db_conn->rawQuery($query_sql, $params_sql) : $db_conn->rawQuery($query_sql);
$row_sql = ($rows_sql && count($rows_sql) > 0) ? $rows_sql[0] : null;
$totalRows_sql = $db_conn->count;
?>