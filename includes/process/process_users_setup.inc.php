<?php
/*
 * Module:      process_users_setup.inc.php
 * Description: This module does all the heavy lifting for adding an admin user to the DB (Setup ONLY)
 */

if ((isset($_SERVER['HTTP_REFERER'])) && (((isset($_SESSION['loginUsername'])) && (isset($_SESSION['userLevel']))) || ($section == "setup"))) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

	// Instantiate HTMLPurifier
	require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
	$config_html_purifier = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config_html_purifier);

	$username = strtolower($_POST['user_name']);
	$username = filter_var($username,FILTER_SANITIZE_EMAIL);
	
	$userQuestionAnswer = $purifier->purify(sterilize($_POST['userQuestionAnswer']));

	if (strstr($username,'@'))  {

		require(CLASSES.'phpass/PasswordHash.php');
		$hasher = new PasswordHash(8, false);
		$password = md5($_POST['password']);
		$hash = $hasher->HashPassword($password);
		$hasher_question = new PasswordHash(8, false);
		$hash_question = $hasher_question->HashPassword($userQuestionAnswer);

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

		// Check to see if processed correctly.
		$query_user_check = sprintf("SELECT COUNT(*) as 'count' FROM %s", $prefix."users");
		$user_check = mysqli_query($connection,$query_user_check) or die (mysqli_error($connection));
		$row_user_check = mysqli_fetch_assoc($user_check);

		// If so, mark step as complete in system table and redirect to next step.
		if ($row_user_check['count'] == 1) {

			$update_table = $prefix."bcoem_sys";
			$data = array('setup_last_step' => 1);
			$db_conn->where ('id', 1);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$insertGoTo = $base_url."setup.php?section=step2&go=".$username;
			$_SESSION['loginUsername'] = $username;

		}

		else $insertGoTo = $base_url."setup.php?section=step1&go=".$username."&msg=99";
		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		$insertGoTo = prep_redirect_link($insertGoTo);
		$redirect_go_to = sprintf("Location: %s", $insertGoTo);
		header($redirect_go_to);
		exit();

	} // end if (strstr($username,'@'))

	else {

		$redirect = $base_url."setup.php?section=step1&msg=1";
		$redirect = prep_redirect_link($redirect);
		$redirect_go_to = sprintf("Location: %s", $redirect);
		header($redirect_go_to);
		exit();

	}

}

else {

	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);
	header($redirect_go_to);
	exit();

}
?>