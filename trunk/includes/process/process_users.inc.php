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


if (NHC) {
// Place NHC SQL calls below
		
		
}

else {

	if (($action == "add") && ($section == "admin")) {
	// Check to see if email address is already in the system. If so, redirect.
	$username = strtolower($_POST['user_name']);
	
	if ((strstr($username,'@')) && (strstr($username,'.'))) {
	mysql_select_db($database, $brewing);
	$query_userCheck = "SELECT user_name FROM $users_db_table WHERE user_name = '$username'";
	$userCheck = mysql_query($query_userCheck, $brewing) or die(mysql_error());
	$row_userCheck = mysql_fetch_assoc($userCheck);
	$totalRows_userCheck = mysql_num_rows($userCheck);
	
	if ($totalRows_userCheck > 0) {
	
		if ($section == "admin") $msg = "10"; else $msg = "2";
		header(sprintf("Location:  %s", $base_url."index.php?section=".$section."&go=".$go."&action=".$action."&msg=".$msg));
	}
	
	else  {
		require(CLASSES.'phpass/PasswordHash.php');
		$hasher = new PasswordHash(8, false);
		$password = md5($_POST['password']);
		$hash = $hasher->HashPassword($password);
		$insertSQL = sprintf("INSERT INTO $users_db_table (user_name, userLevel, password, userQuestion, userQuestionAnswer, userCreated) VALUES (%s, %s, %s, %s, %s, %s)", 
						   GetSQLValueString($username, "text"),
						   GetSQLValueString($_POST['userLevel'], "text"),
						   GetSQLValueString($hash, "text"),
						   GetSQLValueString($_POST['userQuestion'], "text"),
						   GetSQLValueString($_POST['userQuestionAnswer'], "text"),
						   "NOW( )"					   
						   );
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($insertSQL);
		$result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
		
		if ($section != "admin") {
	
		mysql_select_db($database, $brewing);
		$query_login = "SELECT password FROM $users_db_table WHERE user_name = '$username' AND password = '$password'";
		$login = mysql_query($query_login, $brewing) or die(mysql_error());
		$row_login = mysql_fetch_assoc($login);
		$totalRows_login = mysql_num_rows($login);
	
		session_start();
			// Authenticate the user
			if ($totalRows_login == 1)
				{
				// Register the loginUsername
				$_SESSION['loginUsername'] = $username;
	
				// If the username/password combo is OK, relocate to the "protected" content index page
				header(sprintf("Location: %s", $base_url."index.php?action=add&section=brewer&go=".$go."&msg=1"));
				exit;
				}
			else
				{
				// If the username/password combo is incorrect or not found, relocate to the login error page
				header(sprintf("Location: %s", $base_url."index.php?section=login&go=".$go."&msg=1"));
				session_destroy();
				exit;
				}
		}
		
		if ($section == "admin") {
		header(sprintf("Location: %s", $base_url."index.php?section=".$section."&go=".$go."&action=".$action."&filter=info&msg=1&username=".urlencode($username)));
		}
		
	/*
		$insertGoTo = $base_url."index.php?section=login&username=".$username;
		$pattern = array('\'', '"');
		$insertGoTo = str_replace($pattern, "", $insertGoTo); 
		header(sprintf("Location: %s", stripslashes($insertGoTo)));
	*/
	  }
	 }
	 else header(sprintf("Location: %s", $base_url."index.php?section=".$section."&go=".$go."&action=".$action."&msg=3"));
	}
	
	// ---------------------------  Editing a User -------------------------------------------
	if ($action == "edit") {
	
	// Check to see if email address is already in the system. If so, redirect.
	$username = strtolower($_POST['user_name']);
	$usernameOld = strtolower($_POST['user_name_old']);
	if ((strstr($username,'@')) && (strstr($username,'.'))) {
	
	mysql_select_db($database, $brewing);
	$query_brewerCheck = "SELECT brewerEmail FROM $brewer_db_table WHERE brewerEmail = '$usernameOld'";
	$brewerCheck = mysql_query($query_brewerCheck, $brewing) or die(mysql_error());
	$row_brewerCheck = mysql_fetch_assoc($brewerCheck);
	$totalRows_brewerCheck = mysql_num_rows($brewerCheck);
	
	mysql_select_db($database, $brewing);
	$query_userCheck = "SELECT * FROM $users_db_table WHERE user_name = '$username'";
	$userCheck = mysql_query($query_userCheck, $brewing) or die(mysql_error());
	$row_userCheck = mysql_fetch_assoc($userCheck);
	$totalRows_userCheck = mysql_num_rows($userCheck);
	
		// --------------------------- If Changing a Participant's User Level ------------------------------- //
		if ($go == "make_admin") {
		$updateSQL = sprintf("UPDATE $users_db_table SET userLevel=%s WHERE user_name=%s", 
							   GetSQLValueString($_POST['userLevel'], "text"),
							   GetSQLValueString($_POST['user_name'], "text"));
							   
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL);
			$Result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			$pattern = array('\'', '"');
			$updateGoTo = str_replace($pattern, "", $updateGoTo); 
			header(sprintf("Location: %s", stripslashes($updateGoTo)));  
		}
		
		// --------------------------- If Changing a Participant's User Name ------------------------------- //
		if ($go == "username") {
		if ($totalRows_userCheck > 0) {
		  header("Location: ".$base_url."index.php?section=user&action=username&id=".$id."&msg=1");
		  }
		  else  {  
			mysql_select_db($database, $brewing);
			$updateSQL = sprintf("UPDATE $users_db_table SET user_name=%s WHERE id=%s", 
							   GetSQLValueString(strtolower($_POST['user_name']), "text"),
							   GetSQLValueString($id, "text")); 
			mysql_real_escape_string($updateSQL);
			$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
			//echo $updateSQL."<br>";
			
			$updateSQL = sprintf("UPDATE $brewer_db_table SET brewerEmail=%s WHERE uid=%s", 
							   GetSQLValueString(strtolower($_POST['user_name']), "text"),
							   GetSQLValueString($id, "text")); 
			mysql_real_escape_string($updateSQL);
			$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
			//echo $update2SQL."<br>";
			
			if ($filter == "admin") {
				$pattern = array('\'', '"');
				$updateGoTo = str_replace($pattern, "", $updateGoTo); 
				header(sprintf("Location: %s", stripslashes($updateGoTo)));	
			}
		
			else {	
			$query_login = "SELECT user_name FROM $users_db_table WHERE user_name = '$username'";
			$login = mysql_query($query_login, $brewing) or die(mysql_error());
			$row_login = mysql_fetch_assoc($login);
			$totalRows_login = mysql_num_rows($login);
			//echo $query_login;
			session_start();
				// Authenticate the user
				if ($totalRows_login == 1) {
					// Register the loginUsername
					$_SESSION['loginUsername'] = strtolower($_POST['user_name']);
					$_SESSION['user_info'.$prefix_session] = "";
					//$_SESSION['session_set_'.$prefix_session] = "";
					// If the username/password combo is OK, relocate to the "protected" content index page
					header(sprintf("Location: %s", $base_url."index.php?section=list&msg=3"));
					exit;
				}
				else {
					// If the username/password combo is incorrect or not found, relocate to the login error page
					header(sprintf("Location: %s", $base_url."index.php?section=user&action=username&msg=2"));
					exit;
				}
			
		
				$insertGoTo = $base_url."index.php?section=login&username=".$username;
				$pattern = array('\'', '"');
				$insertGoTo = str_replace($pattern, "", $insertGoTo); 
				header(sprintf("Location: %s", stripslashes($insertGoTo)));
			} //end if ($filter !="admin")
		  }
		 }
	}
	 else {
		
		header(sprintf("Location: %s", $base_url."index.php?section=user&action=username&msg=4&id=".$id));
	 }
	
	// --------------------------- If Changing a Paricipant's Password ------------------------------- //
	if ($go == "password") {
	
		// Check if old password is correct; if not redirect
		require(CLASSES.'phpass/PasswordHash.php');
		$hasher = new PasswordHash(8, false);
		
		$password_old = md5($_POST['passwordOld']);
		$password_new = md5($_POST['password']);
		
		
		
		
		mysql_select_db($database, $brewing);
		$query_userPass = sprintf("SELECT password FROM $users_db_table WHERE id = '%s'",$id);
		$userPass = mysql_query($query_userPass, $brewing) or die(mysql_error());
		$row_userPass = mysql_fetch_assoc($userPass);
		
		$check = $hasher->CheckPassword($password_old, $row_userPass['password']);
		$hash_new = $hasher->HashPassword($password_new);
	
		if (!$check) header(sprintf("Location: %s", $base_url."index.php?section=user&action=password&msg=3&id=".$id));
		
		if ($check)  {  
			$updateSQL = sprintf("UPDATE $users_db_table SET password=%s WHERE id=%s", 
						   GetSQLValueString($hash_new, "text"),
						   GetSQLValueString($id, "text")); 
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL);
			$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
			header(sprintf("Location: %s", $base_url."index.php?section=list&id=".$id."&msg=4"));
		}
	 }
		
	} // end if ($action == "edit")
	
} // end else NHC
?>