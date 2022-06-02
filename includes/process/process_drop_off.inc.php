<?php
/*
 * Module:      process_drop_off.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "drop_off" table
 */

$errors = FALSE;
$error_output = array();
$_SESSION['error_output'] = "";

if ((isset($_SERVER['HTTP_REFERER'])) && (((isset($_SESSION['loginUsername'])) && ((isset($_SESSION['userLevel'])) && ($_SESSION['userLevel'] <= 1))) || ($section == "setup"))) {

	// Instantiate HTMLPurifier
	require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
	$config_html_purifier = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config_html_purifier);

	$dropLocationName = sterilize($_POST['dropLocationName']);
	$dropLocationName = $purifier->purify($dropLocationName);
	$dropLocationName = capitalize($dropLocationName);
	$dropLocation = sterilize($_POST['dropLocation']);
	$dropLocation = $purifier->purify($dropLocation);
	$dropLocationPhone = sterilize($_POST['dropLocationPhone']);
	$dropLocationPhone = $purifier->purify($dropLocationPhone);
	$dropLocationWebsite = check_http(sterilize($_POST['dropLocationWebsite']));
	$dropLocationWebsite = $purifier->purify($dropLocationWebsite);
	$dropLocationWebsite = strtolower($dropLocationWebsite);
	$dropLocationNotes = sterilize($_POST['dropLocationNotes']);
	$dropLocationNotes = $purifier->purify($dropLocationNotes);

	if ($action == "add") {

		if ($go != "skip") {

			$update_table = $prefix."drop_off";
			$data = array(
				'dropLocationName' => $dropLocationName,
				'dropLocation' => $dropLocation,
				'dropLocationPhone' => $dropLocationPhone,
				'dropLocationWebsite' => $dropLocationWebsite,
				'dropLocationNotes' => $dropLocationNotes
			);
			$result = $db_conn->insert ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

		}

		if ($section == "setup") {

			$update_table = $prefix."bcoem_sys";
			$data = array('setup_last_step' => 6);
			$db_conn->where ('id', 1);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			if ($go == "skip") $insertGoTo = $base_url."setup.php?section=step7";
			else $insertGoTo = $base_url."setup.php?section=step6&msg=11";
			if ($errors) $insertGoTo = $base_url."setup.php?section=step6&msg=3";

		}

		else $insertGoTo = $base_url."index.php?section=admin&go=dropoff&msg=1";
		if ($errors) $insertGoTo = $base_url."index.php?section=admin&go=dropoff&msg=3";
		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		$insertGoTo = prep_redirect_link($insertGoTo);
		$redirect_go_to = sprintf("Location: %s", $insertGoTo);

	}

	if ($action == "edit") {

		$update_table = $prefix."drop_off";
		$data = array(
			'dropLocationName' => $dropLocationName,
			'dropLocation' => $dropLocation,
			'dropLocationPhone' => $dropLocationPhone,
			'dropLocationWebsite' => $dropLocationWebsite,
			'dropLocationNotes' => $dropLocationNotes
		);
		$db_conn->where ('id', $id);
		$result = $db_conn->update ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		$updateGoTo = $base_url."index.php?section=admin&go=dropoff&msg=2";
		if ($errors) $updateGoTo = $base_url."index.php?section=admin&go=dropoff&msg=3";
		$updateGoTo = prep_redirect_link($updateGoTo);
		$redirect_go_to = sprintf("Location: %s", $updateGoTo);

	}

} else {

	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);

}
?>