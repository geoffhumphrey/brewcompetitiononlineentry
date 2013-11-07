<?php 
/*
 * Module:      process_judging_location.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "judging_locations" table
 */

if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {

	$judgingDate = strtotime($_POST['judgingDate']." ".$_POST['judgingTime']);

	if (NHC) {
		// Place NHC SQL calls below
		
		
	}
	// end if (NHC)
	
	else {

		if ($action == "add") {
			$insertSQL = sprintf("INSERT INTO $judging_locations_db_table (judgingDate, judgingLocation, judgingLocName, judgingRounds) VALUES (%s, %s, %s, %s)",
							   GetSQLValueString($judgingDate, "text"),
							   GetSQLValueString($_POST['judgingLocation'], "scrubbed"),
							   GetSQLValueString($_POST['judgingLocName'], "scrubbed"),
							   GetSQLValueString($_POST['judgingRounds'], "text")
							   );
		
			//echo $insertSQL;
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($insertSQL);
			$result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
			if ($section == "step5") $insertGoTo = "../setup.php?section=step5&msg=9"; else $insertGoTo = $insertGoTo;
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
							   
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL);
			//echo $judgingDate; echo "<br>".$tz; echo "<br>".$timezone_offset; echo "<br>".$_SESSION['prefsTimeZone'];
			//echo $updateSQL;
			$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
			$pattern = array('\'', '"');
			$updateGoTo = str_replace($pattern, "", $updateGoTo); 
			header(sprintf("Location: %s", stripslashes($updateGoTo)));
		}
		
	} // end else NHC
	
} else echo "<p>Not available.</p>";

?>