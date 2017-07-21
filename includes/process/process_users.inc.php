<?php 
/*
 * Module:      process_users.inc.php
 * Description: This module does all the heavy lifting for adding/editing users the DB
 */

if (isset($_SERVER['HTTP_REFERER'])) {
	
	// --------------------------- If a User Registers On Their Own -------------------- //
	
	if (($action == "add") && ($section == "register")) include (PROCESS.'process_users_register.inc.php');
	
	// --------------------------- SETUP: Adding the Admin Participant ----------------- //
	
	if (($action == "add") && ($section == "setup")) 	include (PROCESS.'process_users_setup.inc.php');
	
	// --------------------------- Adding a user (Admin only) -------------------------- //
	
	if (($action == "add") && ($section == "admin") && ($_SESSION['userLevel'] <= 1)) {
		
		// Check to see if email address is already in the system. If so, redirect.
		$username = strtolower($_POST['user_name']);
		$username = filter_var($username,FILTER_SANITIZE_EMAIL);
		
		if (strstr($username,'@')) {
		
			$query_userCheck = "SELECT user_name FROM $users_db_table WHERE user_name = '$username'";
			$userCheck = mysqli_query($connection,$query_userCheck) or die (mysqli_error($connection));
			$row_userCheck = mysqli_fetch_assoc($userCheck);
			$totalRows_userCheck = mysqli_num_rows($userCheck);
			
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
								   GetSQLValueString(strip_tags($_POST['userQuestionAnswer']), "text"),
								   "NOW( )"					   
								   );
				
				mysqli_real_escape_string($connection,$insertSQL);
				$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));
				
				if ($section != "admin") {
			
					$query_login = "SELECT password FROM $users_db_table WHERE user_name = '$username' AND password = '$password'";
					$login = mysqli_query($connection,$query_login) or die (mysqli_error($connection));
					$row_login = mysqli_fetch_assoc($login);
					$totalRows_login = mysqli_num_rows($login);
					
					session_name($prefix_session);
					session_start();
					// Authenticate the user
					if ($totalRows_login == 1)	{
						// Register the loginUsername
						$_SESSION['loginUsername'] = $username;
			
						// If the username/password combo is OK, relocate to the "protected" content index page
						header(sprintf("Location: %s", $base_url."index.php?action=add&section=brewer&go=".$go."&msg=1"));
						exit;
					}
					else {
						// If the username/password combo is incorrect or not found, relocate to the login error page
						header(sprintf("Location: %s", $base_url."index.php?section=login&go=".$go."&msg=1"));
						session_destroy();
						exit;
					}
				}
				
				if ($section == "admin") {
					header(sprintf("Location: %s", $base_url."index.php?section=".$section."&go=".$go."&action=".$action."&filter=info&msg=1&username=".urlencode($username)));
				}
			}
		}
		else header(sprintf("Location: %s", $base_url."index.php?section=".$section."&go=".$go."&action=".$action."&view=".$view."&msg=3"));
	}
		
	// ---------------------------  Editing a User -------------------------------------------
	if (($action == "edit") && ($_POST['userEdit'] == 1)) {
		
		// Check to see if email address is already in the system. If so, redirect.
		if (isset($_POST['user_name'])) {
			$username = strtolower($_POST['user_name']);
			$username = filter_var($username,FILTER_SANITIZE_EMAIL);
		}
		else $username = "";
		
		if (isset($_POST['user_name_old'])) { 
			$usernameOld = strtolower($_POST['user_name_old']);
			$usernameOld = filter_var($usernameOld,FILTER_SANITIZE_EMAIL);
		}
		else $usernameOld = "";
		
		if (strstr($username,'@'))  {
		
		$query_brewerCheck = "SELECT brewerEmail FROM $brewer_db_table WHERE brewerEmail = '$usernameOld'";
		$brewerCheck = mysqli_query($connection,$query_brewerCheck) or die (mysqli_error($connection));
		$row_brewerCheck = mysqli_fetch_assoc($brewerCheck);
		$totalRows_brewerCheck = mysqli_num_rows($brewerCheck);
		
		$query_userCheck = "SELECT * FROM $users_db_table WHERE user_name = '$username'";
		$userCheck = mysqli_query($connection,$query_userCheck) or die (mysqli_error($connection));
		$row_userCheck = mysqli_fetch_assoc($userCheck);
		$totalRows_userCheck = mysqli_num_rows($userCheck);
		
			// --------------------------- If Changing a Participant's User Level ------------------------------- //
			if ($go == "make_admin") {
				
				$updateGoTo = $base_url."index.php?section=admin&go=participants&msg=2";
				$updateSQL = sprintf("UPDATE $users_db_table SET userLevel=%s,userCreated=%s WHERE user_name=%s", 
								   GetSQLValueString($_POST['userLevel'], "text"),
								   "NOW( )",
								   GetSQLValueString($username, "text")
								   );
								   
				
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				
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
				
				$updateSQL = sprintf("UPDATE $users_db_table SET user_name=%s,userCreated=%s WHERE id=%s", 
								   GetSQLValueString($username, "text"),
								   "NOW( )",
								   GetSQLValueString($id, "text")
								   ); 
				
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				
				$updateSQL = sprintf("UPDATE $brewer_db_table SET brewerEmail=%s WHERE uid=%s", 
								   GetSQLValueString($username, "text"),
								   GetSQLValueString($id, "text")); 
				
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				
				if ($filter == "admin") {
					$pattern = array('\'', '"');
					$updateGoTo = str_replace($pattern, "", $updateGoTo); 
					header(sprintf("Location: %s", stripslashes($updateGoTo)));	
				}
			
				else {	
				$query_login = "SELECT user_name FROM $users_db_table WHERE user_name = '$username'";
				$login = mysqli_query($connection,$query_login) or die (mysqli_error($connection));
				$row_login = mysqli_fetch_assoc($login);
				$totalRows_login = mysqli_num_rows($login);
				//echo $query_login;
				
				session_name($prefix_session);
				session_start();
					// Authenticate the user
					if ($totalRows_login == 1) {
						// Register the loginUsername
						$_SESSION['loginUsername'] = $username;
						unset($_SESSION['user_info'.$prefix_session]);
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
		
		// --------------------------- If a participant is changing their password ------------------------------- //
		if ($go == "password") {
		
			// Check if old password is correct; if not redirect
			require(CLASSES.'phpass/PasswordHash.php');
			$hasher = new PasswordHash(8, false);
			
			$password_old = md5($_POST['passwordOld']);
			$password_new = md5($_POST['password']);
			
			$query_userPass = sprintf("SELECT password FROM $users_db_table WHERE id = '%s'",$id);
			$userPass = mysqli_query($connection,$query_userPass) or die (mysqli_error($connection));
			$row_userPass = mysqli_fetch_assoc($userPass);
			
			$check = $hasher->CheckPassword($password_old, $row_userPass['password']);
			$hash_new = $hasher->HashPassword($password_new);
		
			if (!$check) header(sprintf("Location: %s", $base_url."index.php?section=user&action=password&msg=3&id=".$id));
			
			if ($check)  {  
				$updateSQL = sprintf("UPDATE $users_db_table SET password=%s,userCreated=%s WHERE id=%s", 
								GetSQLValueString($hash_new, "text"),
								"NOW( )",
								GetSQLValueString($id, "text")
								); 
				
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				header(sprintf("Location: %s", $base_url."index.php?section=list&id=".$id."&msg=4"));
			}
		 }
		 
		 // --------------------------- If an admin is changing their password ------------------------------- //
		if ($go == "change_user_password") {
		
			require(CLASSES.'phpass/PasswordHash.php');
			$hasher = new PasswordHash(8, false);
		
			$password_new = md5($_POST['password']);
			$hash_new = $hasher->HashPassword($password_new);
		
			$updateSQL = sprintf("UPDATE $users_db_table SET password=%s,userCreated=%s WHERE id=%s", 
								GetSQLValueString($hash_new, "text"),
								"NOW( )",
								GetSQLValueString($id, "text")
								); 
				
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			header(sprintf("Location: %s", $base_url."index.php?section=admin&go=participants&msg=33"));
		 }
			
	} // end if ($action == "edit")

} else { 
	header(sprintf("Location: %s", $base_url."index.php?msg=98"));
	exit;
}

?>