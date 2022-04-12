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
	
	$userQuestionAnswer = $purifier->purify($_POST['userQuestionAnswer']);
	$userQuestionAnswer = filter_var($userQuestionAnswer,FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_HIGH|FILTER_FLAG_STRIP_LOW);

	if (strstr($username,'@'))  {

		require(CLASSES.'phpass/PasswordHash.php');
		$hasher = new PasswordHash(8, false);
		$password = md5($_POST['password']);
		$hash = $hasher->HashPassword($password);
		$hasher_question = new PasswordHash(8, false);
		$hash_question = $hasher_question->HashPassword($userQuestionAnswer);

		$update_table = $prefix."users";
		$data = array(
			'user_name' => $username,
			'userLevel' => sterilize($_POST['userLevel']),
			'password' => $hash,
			'userQuestion' => sterilize($_POST['userQuestion']),
			'userQuestionAnswer' => $hash_question,
			'userCreated' =>  $db_conn->now()
		);
		$result = $db_conn->insert ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		// Check to see if processed correctly.
		$query_user_check = sprintf("SELECT COUNT(*) as 'count' FROM %s",$users_db_table);
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
			session_name($prefix_session);
			session_start();
			$_SESSION['loginUsername'] = $username;

		}

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		// If not, redirect back to step 1 and display message.
		else  $insertGoTo = $base_url."setup.php?section=step1&go=".$username."&msg=99";

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