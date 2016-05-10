<?php
if(isset($_POST['user_name'])) {
	$user_name = strtolower($_POST['user_name']);
	include('../paths.php');
	include(CONFIG.'config.php');  
	include(INCLUDES.'url_variables.inc.php');
	include(INCLUDES.'db_tables.inc.php');
	include(LIB.'common.lib.php');
	
	mysqli_select_db($connection,$database);
	$query_check = "SELECT user_name FROM ".$users_db_table." WHERE user_name='".$user_name."'";
	$sql_check = mysqli_query($connection,$query_check) or die (mysqli_error($connection));
	if (mysqli_num_rows($sql_check)) echo "<span class=\"text-danger\"><span class=\"glyphicon glyphicon-exclamation-sign\"></span> The email address you entered is already in use. Please choose another.</span>";
	else echo "<span class=\"text-success\"><span class=\"glyphicon glyphicon-ok\"></span> The email address you entered is not in use.</span>";
}
?>