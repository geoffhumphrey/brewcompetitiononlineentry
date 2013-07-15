<?php 
/*
 * Module:      process_judging_preferences.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "judging preferences" table
 */

if ($action == "add") {
		
}

if (($action == "edit") || ($section == "step8")) {
	
	// Empty the prefs session variable
	// Will trigger the session to reset the variables in common.db.php upon reload after redirect
	session_start();
	unset($_SESSION['prefs'.$prefix_session]);
	
	if ($_POST['jPrefsQueued'] == "N") $flight_ent = $_POST['jPrefsFlightEntries']; else $flight_ent = $_SESSION['jPrefsFlightEntries'];
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
					<p>BCOEM Setup Completed for http://".$_SERVER['SERVER_NAME']."</p>
					<p><a href='https://box462.bluehost.com:2083/frontend/bluehost/filemanager/index.html?dirselect=domainrootselect&domainselect=".$_SERVER['SERVER_NAME']."'>Be sure to change setup_free_access to FALSE</a></p>
					</body>" . "\r\n";
		$message .= "</html>";
		
		$headers  = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
		$headers .= "To: BCOEM Admin <prost@brewcompetition.com>, " . "\r\n";
		$headers .= "From: BCOEM Server <noreply@brewcompetition.com>" . "\r\n";
		
		
		mail($to_email, $subject, $message, $headers);
		*/
		
		session_unset();
		session_destroy();
		session_write_close();
		session_regenerate_id(true);
		
		header(sprintf("Location: %s", $base_url."index.php?msg=16")); 
	}
	
	else {
	$pattern = array('\'', '"');
  	$updateGoTo = str_replace($pattern, "", $updateGoTo); 
  	header(sprintf("Location: %s", stripslashes($updateGoTo)));
	}
}


?>