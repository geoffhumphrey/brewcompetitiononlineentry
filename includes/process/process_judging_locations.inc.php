<?php
/*
 * Module:      process_judging_location.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "judging_locations" table
 */

if ((isset($_SERVER['HTTP_REFERER'])) && (((isset($_SESSION['loginUsername'])) && ((isset($_SESSION['userLevel'])) && ($_SESSION['userLevel'] == 0))) || ($section == "setup"))) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

	// Instantiate HTMLPurifier
	require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
	$config_html_purifier = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config_html_purifier);

	$judgingDate = strtotime(sterilize($_POST['judgingDate']));
	$judgingLocName = sterilize($_POST['judgingLocName']);
	$judgingLocName = $purifier->purify($judgingLocName);
	$judgingLocation = sterilize($_POST['judgingLocation']);
	$judgingLocation = $purifier->purify($judgingLocation);
	$judgingLocType = sterilize($_POST['judgingLocType']);
	$judgingRounds = "";
	$judgingDateEnd = "";
	$judgingLocNotes = "";
	
	if (isset($_POST['judgingLocNotes'])) {
		$judgingLocNotes = sterilize($_POST['judgingLocNotes']);
		$judgingLocNotes = $purifier->purify($judgingLocNotes);
	}
	
	if (isset($_POST['judgingRounds'])) $judgingRounds = sterilize($_POST['judgingRounds']);
	if (!empty($_POST['judgingDateEnd'])) $judgingDateEnd = strtotime(sterilize($_POST['judgingDateEnd']));
	if (empty($judgingLocType)) $judgingLocType = 0;

	$update_table = $prefix."judging_locations";
	$data = array(
		'judgingLocType' => $judgingLocType,
		'judgingDate' => blank_to_null($judgingDate),
		'judgingDateEnd' => blank_to_null($judgingDateEnd),
		'judgingLocation' => blank_to_null($judgingLocation),
		'judgingLocName' => blank_to_null($judgingLocName),
		'judgingRounds' => blank_to_null($judgingRounds),
		'judgingLocNotes' => blank_to_null($judgingLocNotes)
	);

	if ($action == "add") {
		
		$result = $db_conn->insert ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		if ($section == "setup") {

			$update_table = $prefix."bcoem_sys";
			$data = array('setup_last_step' => 5);
			$db_conn->where ('id', 1);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$insertGoTo = $base_url."setup.php?section=step5&msg=9";
			if ($errors) $insertGoTo = $base_url."setup.php?section=step5&msg=3";
			$insertGoTo = prep_redirect_link($insertGoTo);
			$redirect_go_to = sprintf("Location: %s", $insertGoTo);

		}

		else {
			if ($errors) $insertGoTo = $_POST['relocate']."&msg=3";
			$insertGoTo = prep_redirect_link($insertGoTo);
			$redirect_go_to = sprintf("Location: %s", $insertGoTo);
		}

	} // end if ($action == "add")

	if ($action == "edit") {

		$db_conn->where ('id', $id);
		$result = $db_conn->update ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		if ($errors) $updateGoTo = $_POST['relocate']."&msg=3";
		$updateGoTo = prep_redirect_link($updateGoTo);
		$redirect_go_to = sprintf("Location: %s", $updateGoTo);
	
	} // end if ($action == "edit")

	if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

} else {

	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);

}
?>