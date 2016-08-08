<?php
if ($bid != "") {
	$query_judging = "SELECT judgingLocName FROM $judging_locations_db_table WHERE id='$bid'";
	$judging = mysqli_query($connection,$query_judging) or die (mysqli_error($connection));
	$row_judging = mysqli_fetch_assoc($judging);
}

if ($filter == "judges") $query_sql = sprintf("SELECT a.brewerEmail, a.brewerFirstName, a.brewerLastName, a.brewerJudgeLocation, a.brewerStewardLocation, a.uid, a.brewerJudgeRank, a.brewerJudgeID, a.brewerJudgeLikes, a.brewerJudgeDislikes, b.uid FROM %s a, %s b WHERE b.staff_judge='1' AND a.uid = b.uid ORDER BY a.brewerLastName,a.brewerFirstName ASC", $prefix."brewer",$prefix."staff");
elseif ($filter == "stewards") $query_sql = sprintf("SELECT a.brewerEmail, a.brewerFirstName, a.brewerLastName, a.uid, a.brewerJudgeRank, a.brewerJudgeID, a.brewerJudgeLocation, a.brewerStewardLocation, a.brewerJudgeLikes, a.brewerJudgeDislikes, b.uid FROM %s a, %s b WHERE b.staff_steward='1' AND a.uid=b.uid ORDER BY a.brewerLastName,a.brewerFirstName ASC", $prefix."brewer",$prefix."staff");
elseif ($filter == "staff") $query_sql = sprintf("SELECT a.brewerEmail, a.brewerFirstName, a.brewerLastName, a.uid, a.brewerJudgeRank, a.brewerJudgeID, a.brewerJudgeLocation, a.brewerStewardLocation, b.uid FROM %s a, %s b WHERE b.staff_staff='1' AND a.uid=b.uid ORDER BY a.brewerLastName,a.brewerFirstName ASC", $prefix."brewer",$prefix."staff");
elseif ($filter == "avail_judges")   $query_sql = sprintf("SELECT uid, brewerFirstName, brewerLastName, brewerEmail, brewerJudge, brewerJudgeRank, brewerJudgeID, brewerSteward, brewerJudgeLocation, brewerStewardLocation, brewerJudgeLikes, brewerJudgeDislikes FROM %s WHERE brewerJudge='Y' ORDER BY brewerLastName,brewerFirstName ASC", $prefix."brewer");
elseif ($filter == "avail_stewards") $query_sql = sprintf("SELECT uid, brewerFirstName, brewerLastName, brewerEmail, brewerJudge, brewerJudgeRank, brewerJudgeID, brewerSteward, brewerJudgeLocation, brewerStewardLocation, brewerJudgeLikes, brewerJudgeDislikes FROM %s WHERE brewerSteward='Y' ORDER BY brewerLastName,brewerFirstName ASC", $prefix."brewer");
else $query_sql = sprintf("SELECT uid, brewerFirstName, brewerLastName, brewerEmail, brewerAddress, brewerCity, brewerState, brewerZip, brewerCountry, brewerPhone1, brewerClubs, brewerJudge, brewerJudgeRank, brewerJudgeID, brewerSteward, brewerJudgeLocation, brewerStewardLocation FROM %s ORDER BY brewerLastName ASC", $prefix."brewer");
$sql = mysqli_query($connection,$query_sql) or die (mysqli_error($connection));
$row_sql = mysqli_fetch_assoc($sql);

?>