<?php
declare(strict_types=1);

/*
if ($bid != "") {
	$query_judging = "SELECT judgingLocName FROM $judging_locations_db_table WHERE id='$bid'";
	$judging = mysqli_query($connection,$query_judging) or die (mysqli_error($connection));
	$row_judging = mysqli_fetch_assoc($judging);

	$query_brewerID = "SELECT * FROM $brewing_db_table WHERE brewJudgingLocation='$bid'";
	$brewerID = mysqli_query($connection,$query_brewerID) or die (mysqli_error($connection));
	$row_brewerID = mysqli_fetch_assoc($brewerID);
}

if  ($section == "loc") $query_sql = "SELECT DISTINCT brewer.uid, brewer.brewerFirstName, brewer.brewerLastName, brewer.brewerAddress, brewer.brewerCity, brewer.brewerState, brewer.brewerZip, brewer.brewerCountry, brewer.brewerPhone1, brewer.brewerEmail,  brewer.brewerJudgeID, brewer.brewerJudgeRank, brewer.brewerJudgeMead, brewer.brewerJudgeCider, brewer.brewerClubs,  brewer.brewerJudgeLikes, brewer.brewerJudgeDislikes, brewer.brewerAssignment, brewer.id, brewer.brewerBreweryName, brewer.brewerBreweryTTB, brewing.brewBrewerID, brewing.brewJudgingLocation FROM $brewer_db_table, $brewing_db_table WHERE brewer.uid = brewing.brewbrewerID AND brewing.brewJudgingLocation = '$bid' ORDER BY brewer.brewerLastName ASC";

else 
*/

$db_conn->orderBy('brewerLastName', 'ASC');
$rows_sql = $db_conn->get($brewer_db_table);
$row_sql = ($rows_sql && count($rows_sql) > 0) ? $rows_sql[0] : null;

?>