<?php
if(isSet($_POST['user_name'])) {
	$user_name = strtolower($_POST['user_name']);
	include('../paths.php');
	include(CONFIG.'config.php'); 
	include(INCLUDES.'url_variables.inc.php');
	include(INCLUDES.'db_tables.inc.php');
	mysql_select_db($database, $brewing);
	$sql_check = mysql_query("SELECT user_name FROM $users_db_table WHERE user_name='$user_name'");
	
		if(mysql_num_rows($sql_check)) {
			echo '<span class="icon"><img src="images/exclamation.png" align="absmiddle"></span><span style="color:red">The email address you entered is already in use. Please choose another.</span>';
		}
		else {
			echo 'OK';
		}
}
?>