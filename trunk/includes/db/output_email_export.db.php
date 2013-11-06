<?php
if ($bid != "") {
	$query_judging = "SELECT judgingLocName FROM $judging_locations_db_table WHERE id='$bid'";
	$judging = mysql_query($query_judging, $brewing) or die(mysql_error());
	$row_judging = mysql_fetch_assoc($judging);
}

if (($filter == "judges") && ($section == "admin")) $query_sql = "SELECT a.brewerEmail, a.brewerFirstName, a.brewerLastName, a.brewerJudgeLocation, a.brewerStewardLocation, a.uid, a.brewerJudgeRank, a.brewerJudgeID, a.brewerJudgeLikes, a.brewerJudgeDislikes, b.uid FROM $brewer_db_table a, $staff_db_table b WHERE b.staff_judge='1' AND a.uid=b.uid";
if (($filter == "stewards") && ($section == "admin")) $query_sql = "SELECT a.brewerEmail, a.brewerFirstName, a.brewerLastName, a.uid, a.brewerJudgeRank, a.brewerJudgeID, a.brewerJudgeLocation, a.brewerStewardLocation, a.brewerJudgeLikes, a.brewerJudgeDislikes, b.uid FROM $brewer_db_table a, $staff_db_table b WHERE b.staff_steward='1' AND a.uid=b.uid";
if (($filter == "staff") && ($section == "admin")) $query_sql = "SELECT a.brewerEmail, a.brewerFirstName, a.brewerLastName, a.uid, a.brewerJudgeRank, a.brewerJudgeID, a.brewerJudgeLocation, a.brewerStewardLocation, b.uid FROM $brewer_db_table a, $staff_db_table b WHERE b.staff_staff='1' AND a.uid=b.uid";
if ($filter == "avail_judges")   $query_sql = "SELECT uid, brewerFirstName, brewerLastName, brewerEmail, brewerJudge, brewerJudgeRank, brewerJudgeID, brewerSteward, brewerJudgeLocation, brewerStewardLocation, brewerJudgeLikes, brewerJudgeDislikes FROM $brewer_db_table WHERE brewerJudge='Y' ORDER BY brewerLastName ASC";
elseif ($filter == "avail_stewards") $query_sql = "SELECT uid, brewerFirstName, brewerLastName, brewerEmail, brewerJudge, brewerJudgeRank, brewerJudgeID, brewerSteward, brewerJudgeLocation, brewerStewardLocation, brewerJudgeLikes, brewerJudgeDislikes FROM $brewer_db_table WHERE brewerSteward='Y' ORDER BY brewerLastName ASC";
else $query_sql = "SELECT uid, brewerFirstName, brewerLastName, brewerEmail, brewerJudge, brewerJudgeRank, brewerJudgeID, brewerSteward, brewerJudgeLocation, brewerStewardLocation FROM $brewer_db_table ORDER BY brewerLastName ASC";
$sql = mysql_query($query_sql, $brewing) or die(mysql_error());
$row_sql = mysql_fetch_assoc($sql);

?>