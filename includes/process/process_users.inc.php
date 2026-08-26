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
		$username2 = strtolower($_POST['user_name2']);
		$username2 = filter_var($username2,FILTER_SANITIZE_EMAIL);

		if (strstr($username,'@')) {

			$db_conn->where("user_name", $username);
			$row_userCheck = $db_conn->getOne($users_db_table, "user_name");
			$totalRows_userCheck = $db_conn->count;

			if ($totalRows_userCheck > 0) {

				if ($section == "admin") $msg = "10"; else $msg = "2";
				$redirect = $base_url."index.php?section=".$section."&go=".$go."&action=".$action."&msg=".$msg;
				$redirect = prep_redirect_link($redirect);
				$redirect_go_to = sprintf("Location: %s", $redirect);

			}

			else  {
				
				require(CLASSES.'phpass/PasswordHash.php');
				$hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
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
					'userCreated' =>  date('Y-m-d H:i:s', time()),
					'userAdminObfuscate' => $userAdminObfuscate
				);
				$result = $db_conn->insert ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

				if ($section != "admin") {

					$db_conn->where("user_name", $username);
					$db_conn->where("password", $hash);
					$row_login = $db_conn->getOne($users_db_table, "password");
					$totalRows_login = $db_conn->count;

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
						$redirect = $base_url."index.php?section=default&go=".$go."&msg=1";
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

			$db_conn->where("brewerEmail", $usernameOld);
			$row_brewerCheck = $db_conn->getOne($brewer_db_table, "brewerEmail");
			$totalRows_brewerCheck = $db_conn->count;

			$db_conn->where("user_name", $username);
			$row_userCheck = $db_conn->getOne($users_db_table);
			$totalRows_userCheck = $db_conn->count;

			// ----- If Changing a Participant's User Level ----- //
			if (($go == "make_admin") && ($_SESSION['userLevel'] <= 1)) {

				// Top-Level Admins (userLevel 0) must never be judging-number-obfuscated,
				// regardless of the submitted checkbox state - the checkbox is only
				// meaningful for the Admin (userLevel 1) role.
				$userAdminObfuscate = 1;
				if ($_POST['userLevel'] == 0) $userAdminObfuscate = 0;
				elseif ((!isset($_POST['userAdminObfuscate'])) && ($_POST['userLevel'] == 1)) $userAdminObfuscate = 0;

				$update_table = $prefix."users";
				$data = array(
					'userLevel' => sterilize($_POST['userLevel']),
					'userCreated' => date('Y-m-d H:i:s', time()),
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
			// Ownership check: a user may only change their own username/email unless an admin is
			// performing the change (filter=admin), matching this file's own admin/self-service distinction.
			if (($go == "username") && ((($filter == "admin") && ($_SESSION['userLevel'] <= 1)) || (($filter != "admin") && ($id == $_SESSION['user_id'])))) {

				// User name found. Redirect.
				if ($totalRows_userCheck > 0) {

					$redirect = $base_url."index.php?section=user&action=username&id=".$id."&msg=1";
					$redirect = prep_redirect_link($redirect);
					$redirect_go_to = sprintf("Location: %s", $redirect);

				}
				
				// User name not found. OK to update.
				if ($totalRows_userCheck < 1) {

					$update_table = $prefix."users";
					$data = array(
						'user_name' => $username,
						'userCreated' => date('Y-m-d H:i:s', time())
					);			
					$db_conn->where ('id', $id);
					$result = $db_conn->update ($update_table, $data);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

					// Previously, changed the brewer record based upon a match of the user id and the brewer uid
					// Match using the old email address, update the new email address in the brewer table as well
					$update_table = $prefix."brewer";
					$data = array(
						'brewerEmail' => $username,
						'uid' => $id
					);
					$db_conn->where ('brewerEmail', $row_brewerCheck['brewerEmail']);
					$result = $db_conn->update ($update_table, $data);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

					if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

					if ($filter == "admin") {

						if ($errors) $updateGoTo = $_POST['relocate']."&msg=3";
						else $updateGoTo = $_POST['relocate']."&msg=2";
						$updateGoTo = prep_redirect_link($updateGoTo);
						$redirect_go_to = sprintf("Location: %s", $updateGoTo);
					
					} // end if ($filter == "admin")

					if ($filter != "admin") {

						$db_conn->where("user_name", $username);
						$row_login = $db_conn->getOne($users_db_table, "user_name");
						$totalRows_login = $db_conn->count;

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
			$password_old = (string) $_POST['passwordOld'];
			$password_new = (string) $_POST['password'];

			$db_conn->where("id", $id);
			$row_userPass = $db_conn->getOne($users_db_table, "password");

			$check = password_verify_legacy($password_old, $row_userPass['password']);
			$hash_new = password_hash($password_new, PASSWORD_BCRYPT);

			if (!$check) {

				$redirect = $base_url."index.php?section=user&action=password&msg=3&id=".$id;
				$redirect = prep_redirect_link($redirect);
				$redirect_go_to = sprintf("Location: %s", $redirect);

			}

			if ($check)  {

				$update_table = $prefix."users";
				$data = array(
					'password' => $hash_new,
					'userCreated' => date('Y-m-d H:i:s', time())
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
		if (($go == "change_user_password") && ($_SESSION['userLevel'] <= 1)) {

			$hash_new = password_hash($_POST['password'], PASSWORD_BCRYPT);

			$update_table = $prefix."users";
			$data = array(
				'password' => $hash_new,
				'userCreated' => date('Y-m-d H:i:s', time())
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