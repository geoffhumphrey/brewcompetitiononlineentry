<?php
/*
 * Module:      process_users_setup.inc.php
 * Description: This module does all the heavy lifting for adding an admin user to the DB (Setup ONLY)
 */

if (isset($_SERVER['HTTP_REFERER'])) {

	// Instantiate HTMLPurifier
	require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
	$config_html_purifier = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config_html_purifier);

	$username = strtolower($_POST['user_name']);
	$username = filter_var($username,FILTER_SANITIZE_EMAIL);
	$userQuestionAnswer = $purifier->purify($_POST['userQuestionAnswer']);

	if (strstr($username,'@'))  {
		$password = md5($_POST['password']);
		require(CLASSES.'phpass/PasswordHash.php');
		$hasher = new PasswordHash(8, false);
		$hash = $hasher->HashPassword($password);
		$insertSQL = sprintf("INSERT INTO $users_db_table (user_name, userLevel, password, userQuestion, userQuestionAnswer, userCreated) VALUES (%s, %s, %s, %s, %s, %s)",
						   GetSQLValueString($username, "text"),
						   GetSQLValueString($_POST['userLevel'], "text"),
						   GetSQLValueString($hash, "text"),
						   GetSQLValueString($purifier->purify($_POST['userQuestion']), "text"),
						   GetSQLValueString($userQuestionAnswer, "text"),
						   "NOW( )"
						   );

		mysqli_real_escape_string($connection,$insertSQL);
		$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));

		// Check to see if processed correctly.
		$query_user_check = sprintf("SELECT COUNT(*) as 'count' FROM %s",$users_db_table);
		$user_check = mysqli_query($connection,$query_user_check) or die (mysqli_error($connection));
		$row_user_check = mysqli_fetch_assoc($user_check);

		// If so, mark step as complete in system table and redirect to next step.
		if ($row_user_check['count'] == 1) {

			$sql = sprintf("UPDATE `%s` SET setup_last_step = '1' WHERE id='1';", $system_db_table);
			mysqli_select_db($connection,$database);
			mysqli_real_escape_string($connection,$sql);
			$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

			$insertGoTo = $base_url."setup.php?section=step2&go=".$username;

			session_name($prefix_session);
			session_start();
			$_SESSION['loginUsername'] = $username;

		}

		// If not, redirect back to step 1 and display message.
		else  $insertGoTo = $base_url."setup.php?section=step1&go=".$username."&msg=99";

		$pattern = array('\'', '"');
		$insertGoTo = str_replace($pattern, "", $insertGoTo);
		header(sprintf("Location: %s", stripslashes($insertGoTo)));

	}

	else {
		$GoTo = $base_url."setup.php?section=step1&msg=1";
		$pattern = array('\'', '"');
		$GoTo = str_replace($pattern, "", $GoTo);
		header(sprintf("Location: %s", stripslashes($GoTo)));
	}
}
else {
	header(sprintf("Location: %s", $base_url."index.php?msg=98"));
	exit;
}
?>