<?php 
/*
 * Module:      process_users.inc.php
 * Description: This module does all the heavy lifting for adding/editing users the DB
 */

// --------------------------- If a User Registers On Their Own -------------------- //

if (($action == "add") && ($section == "register")) include_once (PROCESS.'process_users_register.inc.php');

// --------------------------- SETUP: Adding the Admin Participant ----------------- //

if (($action == "add") && ($section == "setup")) 	include_once (PROCESS.'process_users_setup.inc.php');

// --------------------------- Adding a user (Admin only) -------------------------- //

if (($action == "add") && ($section == "admin")) {
// Check to see if email address is already in the system. If so, redirect.
$username = strtolower($_POST['user_name']);

if ((strstr($username,'@')) && (strstr($username,'.'))) {
mysql_select_db($database, $brewing);
$query_userCheck = "SELECT user_name FROM users WHERE user_name = '$username'";
$userCheck = mysql_query($query_userCheck, $brewing) or die(mysql_error());
$row_userCheck = mysql_fetch_assoc($userCheck);
$totalRows_userCheck = mysql_num_rows($userCheck);

if ($totalRows_userCheck > 0) {
  if ($section == "admin") $msg = "10"; else $msg = "2";
  header("Location: ../index.php?section=".$section."&go=".$go."&action=".$action."&msg=".$msg);
  }
  else 
  {
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
	
	if ($section != "admin") {

	mysql_select_db($database, $brewing);
	$query_login = "SELECT password FROM users WHERE user_name = '$username' AND password = '$password'";
	$login = mysql_query($query_login, $brewing) or die(mysql_error());
	$row_login = mysql_fetch_assoc($login);
	$totalRows_login = mysql_num_rows($login);

	session_start();
		// Authenticate the user
		if ($totalRows_login == 1)
			{
  			// Register the loginUsername
  			$_SESSION["loginUsername"] = $username;

  			// If the username/password combo is OK, relocate to the "protected" content index page
  			header("Location: ../index.php?action=add&section=brewer&go=".$go."&msg=1");
  			exit;
			}
		else
			{
  			// If the username/password combo is incorrect or not found, relocate to the login error page
  			header("Location: ../index.php?section=login&go=".$go."&msg=1");
  			session_destroy();
  			exit;
			}
	}
	
	if ($section == "admin") {
	header("Location: ../index.php?section=".$section."&go=".$go."&action=".$action."&filter=info&msg=1&username=".urlencode($username));
	}
	
/*
  $insertGoTo = "../index.php?section=login&username=".$username;
  header(sprintf("Location: %s", $insertGoTo));
*/
  }
 }
 else header("Location: ../index.php?section=".$section."&go=".$go."&action=".$action."&msg=3");
}

// ---------------------------  Editing a User -------------------------------------------
if ($action == "edit") {

// Check to see if email address is already in the system. If so, redirect.
$username = strtolower($_POST['user_name']);
$usernameOld = strtolower($_POST['user_name_old']);
if ((strstr($username,'@')) && (strstr($username,'.'))) {

mysql_select_db($database, $brewing);
$query_brewerCheck = "SELECT brewerEmail FROM brewer WHERE brewerEmail = '$usernameOld'";
$brewerCheck = mysql_query($query_brewerCheck, $brewing) or die(mysql_error());
$row_brewerCheck = mysql_fetch_assoc($brewerCheck);
$totalRows_brewerCheck = mysql_num_rows($brewerCheck);

mysql_select_db($database, $brewing);
$query_userCheck = "SELECT * FROM users WHERE user_name = '$username'";
$userCheck = mysql_query($query_userCheck, $brewing) or die(mysql_error());
$row_userCheck = mysql_fetch_assoc($userCheck);
$totalRows_userCheck = mysql_num_rows($userCheck);

	// --------------------------- If Changing a Participant's User Level ------------------------------- //
	if ($go == "make_admin") {
	$updateSQL = sprintf("UPDATE users SET userLevel=%s WHERE user_name=%s", 
						   GetSQLValueString($_POST['userLevel'], "text"),
						   GetSQLValueString($_POST['user_name'], "text"));
						   
	  mysql_select_db($database, $brewing);
	  $Result = mysql_query($updateSQL, $brewing) or die(mysql_error());
	  header(sprintf("Location: %s", $updateGoTo));  
	}
	
	// --------------------------- If Changing a Participant's User Name ------------------------------- //
	if ($go == "username") {
	if ($totalRows_userCheck > 0) {
	  header("Location: ../index.php?section=user&action=username&id=".$id."&msg=1");
	  }
	  else 
	  {  
	  $updateSQL = sprintf("UPDATE users SET user_name=%s, userLevel=%s WHERE id=%s", 
						   GetSQLValueString($_POST['user_name'], "text"),
						   GetSQLValueString($_POST['userLevel'], "text"),
						   GetSQLValueString($id, "text")); 
	
	  mysql_select_db($database, $brewing);
	  $Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
	  $update2SQL = sprintf("UPDATE brewer SET brewerEmail=%s WHERE brewerEmail=%s", 
						   GetSQLValueString($_POST['user_name'], "text"),
						   GetSQLValueString($usernameOld, "text")); 
	
	  mysql_select_db($database, $brewing);
	  $Result2 = mysql_query($update2SQL, $brewing) or die(mysql_error());
	
		mysql_select_db($database, $brewing);
		$query_login = "SELECT user_name FROM users WHERE user_name = '$username'";
		$login = mysql_query($query_login, $brewing) or die(mysql_error());
		$row_login = mysql_fetch_assoc($login);
		$totalRows_login = mysql_num_rows($login);
		
		session_destroy;
		session_start();
			// Authenticate the user
			if ($totalRows_login == 1)
				{
				// Register the loginUsername
				$_SESSION["loginUsername"] = $username;
	
				// If the username/password combo is OK, relocate to the "protected" content index page
				header("Location: ../index.php?section=list&msg=3");
				exit;
				}
			else
				{
				// If the username/password combo is incorrect or not found, relocate to the login error page
				header("Location: ../index.php?section=user&action=username&msg=2");
				session_destroy();
				exit;
				}
	/*
	  $insertGoTo = "../index.php?section=login&username=".$username;
	  header(sprintf("Location: %s", $insertGoTo));
	*/
	  }
	 }
}
 else {
	
 	header("Location: ../index.php?section=user&action=username&msg=4&id=".$id);
 }

// --------------------------- If Changing a Paricipant's Password ------------------------------- //
if ($go == "password") {

	// Check if old password is correct; if not redirect
	$passwordOld = md5($_POST['passwordOld']);
	$passwordNew = md5($_POST['password']);
	mysql_select_db($database, $brewing);
	$query_userPass = "SELECT password FROM users WHERE password = '$passwordOld'";
	$userPass = mysql_query($query_userPass, $brewing) or die(mysql_error());
	$row_userPass = mysql_fetch_assoc($userPass);
	$totalRows_userPass = mysql_num_rows($userPass);

	if ($passwordOld != $row_userPass['password']) header("Location: ../index.php?section=user&action=password&msg=3&id=".$id);
	
	else {  
  		$updateSQL = sprintf("UPDATE users SET password=%s WHERE id=%s", 
                       GetSQLValueString($passwordNew, "text"),
                       GetSQLValueString($id, "text")); 
		mysql_select_db($database, $brewing);
		$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		header("Location: ../index.php?section=list&id=".$id."&msg=4");
  	}
 }
	
} // end if ($action == "edit")
?>