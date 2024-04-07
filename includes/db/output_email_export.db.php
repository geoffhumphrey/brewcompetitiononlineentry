<?php

//@single
if ($bid != "") {
	$query_judging = sprintf("SELECT judgingLocName FROM %s WHERE id='%s'",$prefix."judging_locations", $bid);
	$judging = mysqli_query($connection,$query_judging) or die (mysqli_error($connection));
	$row_judging = mysqli_fetch_assoc($judging);
}

if ($filter == "judges") {
	$query_sql = sprintf("SELECT a.brewerEmail, a.brewerFirstName, a.brewerLastName, a.brewerJudgeLocation, a.brewerStewardLocation, a.uid, a.brewerJudgeRank, a.brewerJudgeID, a.brewerJudgeLikes, a.brewerJudgeDislikes, a.brewerJudgeMead, a.brewerJudgeCider, b.uid FROM %s a, %s b WHERE b.staff_judge='1' AND a.uid = b.uid", $prefix."brewer", $prefix."staff");
	if (SINGLE) $query_sql .= sprintf(" AND b.comp_id='%s'",$_SESSION['comp_id']);
	$query_sql .= " ORDER BY a.brewerLastName,a.brewerFirstName ASC";
}

elseif ($filter == "stewards") {
	$query_sql = sprintf("SELECT a.brewerEmail, a.brewerFirstName, a.brewerLastName, a.uid, a.brewerJudgeRank, a.brewerJudgeID, a.brewerJudgeLocation, a.brewerStewardLocation, a.brewerJudgeLikes, a.brewerJudgeDislikes, b.uid FROM %s a, %s b WHERE b.staff_steward='1' AND a.uid=b.uid", $prefix."brewer", $prefix."staff");
	if (SINGLE) $query_sql .= sprintf(" AND b.comp_id='%s'",$_SESSION['comp_id']);
	$query_sql .= " ORDER BY a.brewerLastName,a.brewerFirstName ASC";
}

elseif ($filter == "staff") {
	$query_sql = sprintf("SELECT a.brewerEmail, a.brewerFirstName, a.brewerLastName, a.uid, a.brewerJudgeRank, a.brewerJudgeID, a.brewerJudgeLocation, a.brewerStewardLocation, a.brewerStaff, b.uid, b.staff_staff FROM %s a, %s b WHERE a.brewerStaff='Y' AND a.uid=b.uid", $prefix."brewer", $prefix."staff");
	if (SINGLE) $query_sql .= sprintf(" AND b.comp_id='%s'",$_SESSION['comp_id']);
	$query_sql .= " ORDER BY a.brewerLastName,a.brewerFirstName ASC";
}

//@single
elseif ($filter == "avail_judges")  {
	$query_sql = sprintf("SELECT uid, brewerFirstName, brewerLastName, brewerEmail, brewerJudge, brewerJudgeRank, brewerJudgeID, brewerSteward, brewerJudgeLocation, brewerStewardLocation, brewerJudgeLikes, brewerJudgeDislikes, brewerJudgeMead, brewerJudgeCider FROM %s WHERE brewerJudge='Y'", $prefix."brewer");
	$query_sql .= " ORDER BY brewerLastName,brewerFirstName ASC";
}

//@single
elseif ($filter == "avail_stewards") {
	$query_sql = sprintf("SELECT uid, brewerFirstName, brewerLastName, brewerEmail, brewerJudge, brewerJudgeRank, brewerJudgeID, brewerSteward, brewerJudgeLocation, brewerStewardLocation, brewerJudgeLikes, brewerJudgeDislikes FROM %s WHERE brewerSteward='Y'", $prefix."brewer");
	$query_sql .= " ORDER BY brewerLastName,brewerFirstName ASC";
}

//@single
else {
	$query_sql = sprintf("SELECT uid, brewerFirstName, brewerLastName, brewerEmail, brewerAddress, brewerCity, brewerState, brewerZip, brewerCountry, brewerPhone1, brewerClubs, brewerJudge, brewerJudgeRank, brewerJudgeID, brewerJudgeMead, brewerJudgeCider, brewerSteward, brewerJudgeLocation, brewerStewardLocation, brewerBreweryName, brewerBreweryInfo FROM %s", $prefix."brewer");
	$query_sql .= " ORDER BY brewerLastName ASC";
}

$sql = mysqli_query($connection,$query_sql) or die (mysqli_error($connection));
$row_sql = mysqli_fetch_assoc($sql);
$num_fields = mysqli_num_fields($sql);
$totalRows_sql = mysqli_num_rows($sql);
?>