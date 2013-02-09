<?php
if(isSet($_POST['user_name'])) {
	$user_name = strtolower($_POST['user_name']);
	include('../paths.php');
	include(CONFIG.'config.php'); 
	include(INCLUDES.'url_variables.inc.php');
	include(INCLUDES.'db_tables.inc.php');
	include(INCLUDES.'functions.inc.php');
	mysql_select_db($database, $brewing);
	
	
	// Custom Code for AHA NHC
	// Check master entrant db table for email address
	if (NHC) { 
		$sql_check = mysql_query("SELECT email FROM nhcentrant WHERE Email='$user_name'");
	}
	
	else {
		$sql_check = mysql_query("SELECT user_name FROM $users_db_table WHERE user_name='$user_name'");
	}
	
		if(mysql_num_rows($sql_check)) {
			echo '<span class="icon"><img src="'.$base_url.'/images/exclamation.png" align="absmiddle"></span><span style="color:red">The email address you entered is already in use. Please choose another.</span>';
		}
		else {
			echo 'OK';
		}
}
?>