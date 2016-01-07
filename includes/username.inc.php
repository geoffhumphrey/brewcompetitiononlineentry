<?php
if(isSet($_POST['user_name'])) {
	$user_name = strtolower($_POST['user_name']);
	include('../paths.php');
	include(CONFIG.'config.php');  
	include(INCLUDES.'url_variables.inc.php');
	include(INCLUDES.'db_tables.inc.php');
	include(LIB.'common.lib.php');
	
	mysql_select_db($database, $brewing);
	
	if (NHC) { 
		
	}
	else {
		$sql_check = mysql_query("SELECT user_name FROM ".$users_db_table." WHERE user_name='".$user_name."'");
		if (mysql_num_rows($sql_check)) echo "<span class=\"text-danger\"><span class=\"fa fa-exlamation-circle\"></span> The email address you entered is already in use. Please choose another.</span>";
		else echo "<span class=\"text-success\"><span class=\"fa fa-check\"></span> The email address you entered is not in use.</span>";
	} // end else NHC
}
?>