<?php 
/*
 * Module:      process_users_setup.inc.php
 * Description: This module does all the heavy lifting for adding an admin user to the DB (Setup ONLY)
 */

$username = strtolower($_POST['user_name']);
if ((strstr($username,'@')) && (strstr($username,'.'))) {
	$password = md5($_POST['password']);
	$insertSQL = sprintf("INSERT INTO users (user_name, userLevel, password, userQuestion, userQuestionAnswer, userCreated) VALUES (%s, %s, %s, %s, %s, %s)", 
                       GetSQLValueString($username, "text"),
					   GetSQLValueString($_POST['userLevel'], "text"),
                       GetSQLValueString($password, "text"),
					   GetSQLValueString($_POST['userQuestion'], "text"),
					   GetSQLValueString($_POST['userQuestionAnswer'], "text"),
					   "NOW( )"					   
					   );
	
	mysql_select_db($database, $brewing);
	$Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
	
	$insertGoTo = "../setup.php?section=step2&go=".$username;
	header(sprintf("Location: %s", $insertGoTo));	
	
	session_start();
	$_SESSION["loginUsername"] = $username;
}
else header("Location: ../setup.php?section=step1&msg=1");

?>