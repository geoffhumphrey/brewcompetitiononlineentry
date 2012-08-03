<?php

// -----------------------------------------------------------
// Data Updates: Other tables
// -----------------------------------------------------------

// Update Judging Locations to use new date/time schema

$query_judging_locations = "SELECT * FROM $judging_locations_db_table";
$judging_locations = mysql_query($query_judging_locations, $brewing) or die(mysql_error());
$row_judging_locations = mysql_fetch_assoc($judging_locations);

do { 
	// Convert current time/date to UNIX
	$string = strtotime($row_judging_locations['judgingDate'].$row_judging_locations['judgingTime']);
	$updateSQL = sprintf("UPDATE $judging_locations_db_table SET 
						 judgingDate='%s'
						 WHERE id='%s'", 
						 $string,
						 $row_judging_locations['id']);
	mysql_select_db($database, $brewing);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
	//echo $updateSQL."<br>";
}  
while ($row_judging_locations = mysql_fetch_assoc($judging_locations));

echo "<ul><li>Updates to judging locations table completed.</li></ul>";




// Update Preferences to use new date/time schema

	// Convert current time/date to UNIX
	
	if ($row_contest_info['contestRegistrationOpen'] != "") $string1 = strtotime($row_contest_info['contestRegistrationOpen']."08:00 AM");
	else $string1 = strtotime(date("Y-m-d")."08:00 AM");
	
	if ($row_contest_info['contestRegistrationDeadline'] != "") $string2 = strtotime($row_contest_info['contestRegistrationDeadline']."08:00 PM");
	else $string2 = strtotime(date("Y-m-d")."08:00 PM");
	
	if ($row_contest_info['contestEntryOpen'] != "") $string3 = strtotime($row_contest_info['contestEntryOpen']."08:00 AM");
	else $string3 = strtotime(date("Y-m-d")."08:00 PM");
	
	if ($row_contest_info['contestEntryDeadline'] != "") $string4 = strtotime($row_contest_info['contestEntryDeadline']."08:00 PM");
	else $string4 = strtotime(date("Y-m-d")."08:00 PM");
	
	if ($row_contest_info['contestAwardsLocationDate'] != "") $string5 = strtotime($row_contest_info['contestAwardsLocDate'].$row_contest_info['contestAwardsLocationTime']);
	else $string5 = strtotime(date("Y-m-d")."08:00 PM");

	$updateSQL = sprintf("UPDATE $contest_info_db_table SET 
						 contestRegistrationOpen='%s',
						 contestRegistrationDeadline='%s',
						 contestEntryOpen='%s',
						 contestEntryDeadline='%s',
						 contestJudgeOpen='%s',
						 contestJudgeDeadline='%s',
						 contestAwardsLocTime='%s'						 
						 WHERE id='1'", 
						 $string1,
						 $string2,
						 $string3,
						 $string4,
						 $string1,
						 $string2,
						 $string5);
	
	mysql_select_db($database, $brewing);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
	//echo $updateSQL."<br>";

echo "<ul><li>Updates to prefereces table completed.</li></ul>";

?>