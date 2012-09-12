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
		
		
		$updateSQL = sprintf("UPDATE %s SET setup='1' WHERE id='1'",$prefix."system");			   
		mysql_select_db($database, $brewing);
  		$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		
		/*
		// For hosted version: email prost@brewcompetition.com to alert when setup has been completed.
		$to_email = "prost@brewcompetition.com";
		$subject = "BCOEM Setup Completed for ".$_SERVER['SERVER_NAME'];
		$message = "<html>" . "\r\n";
		$message .= "<body>
					<p>BCOEM Setup Completed for ".$_SERVER['SERVER_NAME']."</p>
					<p>Be sure to change setup_free_access to FALSE</p>
					</body>" . "\r\n";
		$message .= "</html>";
		
		$headers  = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
		$headers .= "To: BCOEM Admin <prost@brewcompetition.com>, " . "\r\n";
		$headers .= "From: BCOEM Server <noreply@brewcompetition.com>" . "\r\n";
		
		
		mail($to_email, $subject, $message, $headers);
		*/
		header(sprintf("Location: %s", $base_url."/index.php?msg=2")); 
	}
	
	else {
	$pattern = array('\'', '"');
  	$updateGoTo = str_replace($pattern, "", $updateGoTo); 
  	header(sprintf("Location: %s", stripslashes($updateGoTo)));
	}
}


?>