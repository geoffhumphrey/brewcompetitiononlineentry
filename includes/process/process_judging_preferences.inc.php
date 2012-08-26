<?php 
/*
 * Module:      process_judging_preferences.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "judging preferences" table
 */

if ($action == "add") {
		
}

if (($action == "edit") || ($section == "step8")) {
	if ($_POST['jPrefsQueued'] == "N") $flight_ent = $_POST['jPrefsFlightEntries']; else $flight_ent = $row_judging_prefs['jPrefsFlightEntries'];
$updateSQL = sprintf("UPDATE $judging_preferences_db_table SET
					 
jPrefsQueued=%s,
jPrefsFlightEntries=%s,
jPrefsMaxBOS=%s,
jPrefsRounds=%s
WHERE id=%s",
                       GetSQLValueString($_POST['jPrefsQueued'], "text"),
					   GetSQLValueString($flight_ent, "int"),
                       GetSQLValueString($_POST['jPrefsMaxBOS'], "int"),
					   GetSQLValueString($_POST['jPrefsRounds'], "int"),
                       GetSQLValueString($id, "int"));
					   
	mysql_select_db($database, $brewing);
  	$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
	if ($section == "step8") {
		// Lock down the config file
		//if (@chmod("/site/config.php", 0555)) $message = "success"; else $message = "chmod";
		header(sprintf("Location: %s", $base_url."/index.php?msg=$message")); 
	}
	
	else {
	$pattern = array('\'', '"');
  	$updateGoTo = str_replace($pattern, "", $updateGoTo); 
  	header(sprintf("Location: %s", stripslashes($updateGoTo)));
	}
}


?>