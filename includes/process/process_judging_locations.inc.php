<?php 
/*
 * Module:      process_judging_location.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "judging_locations" table
 */
if (((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) || ($section == "setup")) {
	$judgingDate = strtotime($_POST['judgingDate']);
	
	if ($action == "add") {
		$insertSQL = sprintf("INSERT INTO $judging_locations_db_table (judgingDate, judgingLocation, judgingLocName, judgingRounds) VALUES (%s, %s, %s, %s)",
						   GetSQLValueString($judgingDate, "text"),
						   GetSQLValueString($_POST['judgingLocation'], "scrubbed"),
						   GetSQLValueString($_POST['judgingLocName'], "scrubbed"),
						   GetSQLValueString($_POST['judgingRounds'], "text")
						   );
	
		//echo $insertSQL;
		mysqli_real_escape_string($connection,$insertSQL);
		$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));
		if ($section == "setup") $insertGoTo = "../setup.php?section=step5&msg=9"; else $insertGoTo = $insertGoTo;
		
		$pattern = array('\'', '"');
		$insertGoTo = str_replace($pattern, "", $insertGoTo); 
		header(sprintf("Location: %s", stripslashes($insertGoTo)));				   
		
	}
	
	if ($action == "edit") {
		$updateSQL = sprintf("UPDATE $judging_locations_db_table SET judgingDate=%s, judgingLocation=%s, judgingLocName=%s, judgingRounds=%s WHERE id=%s",
						   GetSQLValueString($judgingDate, "text"),
						   GetSQLValueString($_POST['judgingLocation'], "scrubbed"),
						   GetSQLValueString($_POST['judgingLocName'], "scrubbed"),
						   GetSQLValueString($_POST['judgingRounds'], "text"),
						   GetSQLValueString($id, "int"));   
						   
		//echo $judgingDate; echo "<br>".$tz; echo "<br>".$timezone_offset; echo "<br>".$_SESSION['prefsTimeZone'];
		//echo $updateSQL;
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		$pattern = array('\'', '"');
		$updateGoTo = str_replace($pattern, "", $updateGoTo); 
		header(sprintf("Location: %s", stripslashes($updateGoTo)));
	}
		
	
} else echo "<p>Not available.</p>";
?>