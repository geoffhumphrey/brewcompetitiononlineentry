<?php
if (NHC) {
	// Place NHC SQL calls below
	
	
}
// end if (NHC)

else {

	if ($bid != "") {
		$query_judging = "SELECT judgingLocName FROM $judging_locations_db_table WHERE id='$bid'";
		$judging = mysql_query($query_judging, $brewing) or die(mysql_error());
		$row_judging = mysql_fetch_assoc($judging);
		
		$query_brewerID = "SELECT * FROM $brewing_db_table WHERE brewJudgingLocation='$bid'";
		$brewerID = mysql_query($query_brewerID, $brewing) or die(mysql_error());
		$row_brewerID = mysql_fetch_assoc($brewerID);
	}

	mysql_select_db($database, $brewing);
	if  ($section == "loc") $query_sql = "SELECT DISTINCT brewer.uid, brewer.brewerFirstName, brewer.brewerLastName, brewer.brewerAddress, brewer.brewerCity, brewer.brewerState, brewer.brewerZip, brewer.brewerCountry, brewer.brewerPhone1, brewer.brewerNickname, brewer.brewerEmail,  brewer.brewerJudgeID, brewer.brewerJudgeRank, brewer.brewerClubs,  brewer.brewerJudgeLikes, brewer.brewerJudgeDislikes, brewer.brewerAssignment, brewer.id, brewing.brewBrewerID, brewing.brewJudgingLocation FROM $brewer_db_table, $brewing_db_table WHERE brewer.uid = brewing.brewbrewerID	AND brewing.brewJudgingLocation = '$bid' ORDER BY brewer.brewerLastName ASC";
	
	else $query_sql = "SELECT uid, brewerFirstName, brewerLastName, brewerAddress, brewerCity, brewerState, brewerZip, brewerCountry, brewerPhone1, brewerNickname, brewerEmail, brewerJudgeID, brewerJudgeRank, brewerClubs, brewerAssignment, brewerJudgeLikes, brewerJudgeDislikes FROM $brewer_db_table ORDER BY brewerLastName ASC";
	$sql = mysql_query($query_sql, $brewing) or die(mysql_error());
	$row_sql = mysql_fetch_assoc($sql);
	

}

?>