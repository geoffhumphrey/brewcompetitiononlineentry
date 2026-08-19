<?php

/*
 * Module:      process_users_setup.inc.php
 * Description: This module does all the heavy lifting for adding an admin user to the DB (Setup ONLY)
 */
if ((isset($_SERVER['HTTP_REFERER'])) && (((isset($_SESSION['loginUsername'])) && (isset($_SESSION['userLevel']))) || ($setup_free_access))) {

	$errors = FALSE;
	$error_output = [];
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
		$hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
		$hasher_question = new PasswordHash(8, false);
		$hash_question = $hasher_question->HashPassword($userQuestionAnswer);

		$userAdminObfuscate = 1;
		if ($_POST['userLevel'] == 0) $userAdminObfuscate = 0;

		$update_table = $prefix."users";
		$data = [
			'user_name' => $username,
			'userLevel' => sterilize($_POST['userLevel']),
			'password' => $hash,
			'userQuestion' => sterilize($_POST['userQuestion']),
			'userQuestionAnswer' => $hash_question,
			'userCreated' =>  date('Y-m-d H:i:s', time()),
			'userAdminObfuscate' => $userAdminObfuscate
		];

		$result = $db_conn->insert ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		// Check to see if processed correctly.
		$row_user_check = $db_conn->getOne($prefix."users", "COUNT(*) as 'count'");

		// If so, mark step as complete in system table and redirect to next step.
		if ($row_user_check['count'] == 1) {

			$update_table = $prefix."bcoem_sys";
			$data = ['setup_last_step' => 1];
			$db_conn->where ('id', 1);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$insertGoTo = $base_url."setup.php?section=step2&go=".$username;
			$_SESSION['loginUsername'] = $username;
			csrf_token_generate(true);

		}

		else $insertGoTo = $base_url."setup.php?section=step1&go=".$username."&msg=99";
		if ($error_output !== []) $_SESSION['error_output'] = $error_output;

		$insertGoTo = prep_redirect_link($insertGoTo);
		$redirect_go_to = sprintf("Location: %s", $insertGoTo);
		header($redirect_go_to);
		exit();

	}
    $redirect = $base_url."setup.php?section=step1&msg=1";
    $redirect = prep_redirect_link($redirect);
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();

}
$redirect = $base_url."index.php?msg=98";
$redirect = prep_redirect_link($redirect);
$redirect_go_to = sprintf("Location: %s", $redirect);
header($redirect_go_to);
exit();
