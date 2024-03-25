<?php
/*
 * Module:      process_users.inc.php
 * Description: This module does all the heavy lifting for adding/editing users the DB
 */

if (isset($_SERVER['HTTP_REFERER'])) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

	// --------------------------- If a User Registers On Their Own -------------------- //

	if (($action == "add") && ($section == "register")) include (PROCESS.'process_users_register.inc.php');

	// --------------------------- SETUP: Adding the Admin Participant ----------------- //

	if (($action == "add") && ($section == "setup")) include (PROCESS.'process_users_setup.inc.php');

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
				$redirect = $base_url."index.php?section=".$section."&go=".$go."&action=".$action."&msg=".$msg;
				$redirect = prep_redirect_link($redirect);
				$redirect_go_to = sprintf("Location: %s", $redirect);

			}

			else  {
				
				require(CLASSES.'phpass/PasswordHash.php');
				$hasher = new PasswordHash(8, false);
				$password = md5($_POST['password']);
				$hash = $hasher->HashPassword($password);
				$hasher_question = new PasswordHash(8, false);
				$hash_question = $hasher_question->HashPassword(sterilize($_POST['userQuestionAnswer']));

				$userAdminObfuscate = 1;
				if ($_POST['userLevel'] == 0) $userAdminObfuscate = 0;

				$update_table = $prefix."users";
				$data = array(
					'user_name' => $username,
					'userLevel' => sterilize($_POST['userLevel']),
					'password' => $hash,
					'userQuestion' => sterilize($_POST['userQuestion']),
					'userQuestionAnswer' => $hash_question,
					'userCreated' =>  $db_conn->now(),
					'userAdminObfuscate' => $userAdminObfuscate
				);
				$result = $db_conn->insert ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

				if ($section != "admin") {

					$query_login = "SELECT password FROM $users_db_table WHERE user_name = '$username' AND password = '$hash'";
					$login = mysqli_query($connection,$query_login) or die (mysqli_error($connection));
					$row_login = mysqli_fetch_assoc($login);
					$totalRows_login = mysqli_num_rows($login);

					if (session_status() === PHP_SESSION_NONE) {
						session_name($prefix_session);
						session_start();
					}
					
					// Authenticate the user
					if ($totalRows_login == 1)	{
						
						// Register the loginUsername
						$_SESSION['loginUsername'] = $username;

						// If the username/password combo is OK, relocate to the "protected" content index page
						$redirect = $base_url."index.php?action=add&section=brewer&go=".$go."&msg=1";
						$redirect = prep_redirect_link($redirect);
						$redirect_go_to = sprintf("Location: %s", $redirect);

					}

					else {
						
						// If the username/password combo is incorrect or not found, relocate to the login error page
						$redirect = $base_url."index.php?section=login&go=".$go."&msg=1";
						$redirect = prep_redirect_link($redirect);
						$redirect_go_to = sprintf("Location: %s", $redirect);
						session_destroy();

					}

				}

				if ($section == "admin") {
					$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=".$section."&go=".$go."&action=".$action."&filter=info&msg=1&username=".urlencode($username));
				}

			} // end else 

		} // end if (strstr($username,'@'))

		else {

			$redirect = $base_url."index.php?section=".$section."&go=".$go."&action=".$action."&view=".$view."&msg=3";
			$redirect = prep_redirect_link($redirect);
			$redirect_go_to = sprintf("Location: %s", $redirect);

		}
	
	} // end if (($action == "add") && ($section == "admin") && ($_SESSION['userLevel'] <= 1))

	// ---------------------------  Editing a User -------------------------------------------
	if (($action == "edit") && ($_POST['userEdit'] == 1) && (isset($_SESSION['loginUsername'])) && (isset($_SESSION['userLevel']))) {

		$username = "";
		$usernameOld = "";

		// Check to see if email address is already in the system. If so, redirect.
		if (isset($_POST['user_name'])) {
			$username = strtolower($_POST['user_name']);
			$username = filter_var($username,FILTER_SANITIZE_EMAIL);
		}

		if (isset($_POST['user_name_old'])) {
			$usernameOld = strtolower($_POST['user_name_old']);
			$usernameOld = filter_var($usernameOld,FILTER_SANITIZE_EMAIL);
		}

		if (strstr($username,'@')) {

			$query_brewerCheck = "SELECT brewerEmail FROM $brewer_db_table WHERE brewerEmail = '$usernameOld'";
			$brewerCheck = mysqli_query($connection,$query_brewerCheck) or die (mysqli_error($connection));
			$row_brewerCheck = mysqli_fetch_assoc($brewerCheck);
			$totalRows_brewerCheck = mysqli_num_rows($brewerCheck);

			$query_userCheck = "SELECT * FROM $users_db_table WHERE user_name = '$username'";
			$userCheck = mysqli_query($connection,$query_userCheck) or die (mysqli_error($connection));
			$row_userCheck = mysqli_fetch_assoc($userCheck);
			$totalRows_userCheck = mysqli_num_rows($userCheck);

			// ----- If Changing a Participant's User Level ----- //
			if (($go == "make_admin") && ($_SESSION['userLevel'] <= 1)) {

				$userAdminObfuscate = 1;
				if (!isset($_POST['userAdminObfuscate'])) {
					if ($_POST['userLevel'] < 2) $userAdminObfuscate = 0;
				}

				$update_table = $prefix."users";
				$data = array(
					'userLevel' => sterilize($_POST['userLevel']),
					'userCreated' => $db_conn->now(),
					'userAdminObfuscate' => $userAdminObfuscate
				);			
				$db_conn->where ('user_name', $username);
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

				if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

				if ($errors) $updateGoTo = $base_url."index.php?section=admin&go=participants&msg=3";
				$updateGoTo = prep_redirect_link($updateGoTo);
				$redirect_go_to = sprintf("Location: %s", $updateGoTo);

			} else {
	
				$redirect = $base_url."index.php?msg=98";
				$redirect = prep_redirect_link($redirect);
				$redirect_go_to = sprintf("Location: %s", $redirect);

			}

			// --------------------------- If Changing a Participant's User Name ------------------------------- //
			if ($go == "username") {
				
				// User name found. Redirect.
				if ($totalRows_userCheck > 0) {

					$redirect = $base_url."index.php?section=user&action=username&id=".$id."&msg=1";
					$redirect = prep_redirect_link($redirect);
					$redirect_go_to = sprintf("Location: %s", $redirect);

				}
				
				// User name not found. Update.
				if ($totalRows_userCheck < 1) {

					$update_table = $prefix."users";
					$data = array(
						'user_name' => $username,
						'userCreated' => $db_conn->now()
					);			
					$db_conn->where ('id', $id);
					$result = $db_conn->update ($update_table, $data);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

					$update_table = $prefix."brewer";
					$data = array('brewerEmail' => $username);			
					$db_conn->where ('uid', $id);
					$result = $db_conn->update ($update_table, $data);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

					if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

					if ($filter == "admin") {

						if ($errors) $updateGoTo = $_POST['relocate']."&msg=3";
						$updateGoTo = prep_redirect_link($updateGoTo);
						$redirect_go_to = sprintf("Location: %s", $updateGoTo);
					
					} // end if ($filter == "admin")

					if ($filter != "admin") {

						$query_login = "SELECT user_name FROM $users_db_table WHERE user_name = '$username'";
						$login = mysqli_query($connection,$query_login) or die (mysqli_error($connection));
						$row_login = mysqli_fetch_assoc($login);
						$totalRows_login = mysqli_num_rows($login);

						if (session_status() == PHP_SESSION_NONE) {
							session_name($prefix_session);
							session_start();
						}

						// Authenticate the user
						if ($totalRows_login == 1) {
							
							// Register the loginUsername
							$_SESSION['loginUsername'] = $username;
							unset($_SESSION['user_info'.$prefix_session]);
							
							// If the username/password combo is OK, relocate to the "protected" content index page
							$redirect = $base_url."index.php?section=list&msg=3";
							$redirect = prep_redirect_link($redirect);
							$redirect_go_to = sprintf("Location: %s", $redirect);

						}

						else {

							// If the username/password combo is incorrect or not found, relocate to the login error page
							$redirect = $base_url."index.php?section=user&action=username&msg=2";
							$redirect = prep_redirect_link($redirect);
							$redirect_go_to = sprintf("Location: %s", $redirect);

						}

					} // end if ($filter != "admin")

				} // end if ($totalRows_userCheck < 1)

			} // end if ($go == "username")
		
		} // end if (strstr($username,'@'))

		else {

			$redirect = $base_url."index.php?section=user&action=username&msg=4&id=".$id;
			$redirect = prep_redirect_link($redirect);
			$redirect_go_to = sprintf("Location: %s", $redirect);

		}

		// --------------------------- If a participant is changing their password ------------------------------- //
		if ($go == "password") {

			// Check if old password is correct; if not redirect
			require(CLASSES.'phpass/PasswordHash.php');
			$hasher = new PasswordHash(8, false);

			$password_old = md5(sterilize($_POST['passwordOld']));
			$password_new = md5(sterilize($_POST['password']));

			$query_userPass = sprintf("SELECT password FROM $users_db_table WHERE id = '%s'",$id);
			$userPass = mysqli_query($connection,$query_userPass) or die (mysqli_error($connection));
			$row_userPass = mysqli_fetch_assoc($userPass);

			$check = $hasher->CheckPassword($password_old, $row_userPass['password']);
			$hash_new = $hasher->HashPassword($password_new);

			if (!$check) {

				$redirect = $base_url."index.php?section=user&action=password&msg=3&id=".$id;
				$redirect = prep_redirect_link($redirect);
				$redirect_go_to = sprintf("Location: %s", $redirect);

			}

			if ($check)  {

				$update_table = $prefix."users";
				$data = array(
					'password' => $hash_new,
					'userCreated' => $db_conn->now()
				);			
				$db_conn->where ('id', $id);
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}
				
				$redirect = $base_url."index.php?section=list&id=".$id."&msg=4";
				$redirect = prep_redirect_link($redirect);
				$redirect_go_to = sprintf("Location: %s", $redirect);

			}

		} // end if ($go == "password")

		// --------------------------- If an admin is changing their password ------------------------------- //
		if ($go == "change_user_password") {

			require(CLASSES.'phpass/PasswordHash.php');
			$hasher = new PasswordHash(8, false);

			$password_new = md5(sterilize($_POST['password']));
			$hash_new = $hasher->HashPassword($password_new);

			$update_table = $prefix."users";
			$data = array(
				'password' => $hash_new,
				'userCreated' => $db_conn->now()
			);			
			$db_conn->where ('id', $id);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

			$redirect = $base_url."index.php?section=admin&go=participants&msg=33";
			if ($errors) $redirect = $base_url."index.php?section=admin&go=participants&msg=3";
			$redirect = prep_redirect_link($redirect);
			$redirect_go_to = sprintf("Location: %s", $redirect);

		} // end if ($go == "change_user_password")

	} // end if (($action == "edit") && ($_POST['userEdit'] == 1))

} else {
	
	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);

}
?>