<?php
/*
 * Module:      process_special_best_info.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "special_best_info" table
 */

if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

	// Instantiate HTMLPurifier
	require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
	$config_html_purifier = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config_html_purifier);
	
	$sbi_name = "";
	$sbi_description = "";

	if (isset($_POST['sbi_name'])) {
		$sbi_name = $purifier->purify($_POST['sbi_name']);
		$sbi_name = sterilize($sbi_name);
	}

	if (isset($_POST['sbi_description'])) {
		$sbi_description = $purifier->purify($_POST['sbi_description']);
		$sbi_description = sterilize($sbi_description);
	}

	if ($action == "add") {

		$update_table = $prefix."special_best_info";
		$data = array(
			'sbi_name' => blank_to_null($sbi_name),
			'sbi_description' => blank_to_null($sbi_description),
			'sbi_places' => blank_to_null(sterilize($_POST['sbi_places'])),
			'sbi_rank' => blank_to_null(sterilize($_POST['sbi_rank']))
		);
		$result = $db_conn->insert ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		if ($errors) $insertGoTo = $_POST['relocate']."&msg=3";
		$insertGoTo = prep_redirect_link($insertGoTo);
		$redirect_go_to = sprintf("Location: %s", $insertGoTo);

	}

	if ($action == "edit") {

		$update_table = $prefix."special_best_info";
		$data = array(
			'sbi_name' => blank_to_null($sbi_name),
			'sbi_description' => blank_to_null($sbi_description),
			'sbi_places' => blank_to_null(sterilize($_POST['sbi_places'])),
			'sbi_rank' => blank_to_null(sterilize($_POST['sbi_rank']))
		);
		$db_conn->where ('id', $id);
		$result = $db_conn->update ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		if ($errors) $updateGoTo = $_POST['relocate']."&msg=3";
		$updateGoTo = prep_redirect_link($updateGoTo);
		$redirect_go_to = sprintf("Location: %s", $updateGoTo);

	}

} else {

	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);

}
?>