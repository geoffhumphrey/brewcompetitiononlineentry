<?php
/*
 * Module:      process_styles_edit.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "sponsors" table
 */
if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))) {

	// Instantiate HTMLPurifier
	require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
	$config_html_purifier = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config_html_purifier);

	if ($action == "update") {

		foreach($_POST['id'] as $id) {
			$sponsor_info = $purifier->purify(sterilize($_POST['sponsorText'.$id]));
			if ($_POST['sponsorEnable'.$id] == 1) $enable = 1; else $enable = 0;
			if (isset($_POST['sponsorImage'.$id])) $image = $purifier->purify($_POST['sponsorImage'.$id]); else $image = "";
			$data = array(
				'sponsorEnable' => $enable,
				'sponsorLevel' => sterilize($_POST['sponsorLevel'.$id]),
				'sponsorImage' => $image,
				'sponsorText' => $sponsor_info
			);
			// This mass-edit table has no hotlink URL control (issue #371) - only the full
			// Add/Edit form does. Picking a file here is an unambiguous switch back to local-
			// upload mode, so clear any hotlink URL that row still has, or it would silently
			// keep winning over the file just selected (see the source-precedence in the
			// public display templates).
			if ($image != "") $data['sponsorImageURL'] = null;
			$db_conn->where('id', $id);
			$result = $db_conn->update($sponsors_db_table, $data);
		}

		$massUpdateGoTo = $base_url."index.php?section=admin&go=sponsors&msg=9";
		$pattern = array('\'', '"');
		$massUpdateGoTo = str_replace($pattern, "", $massUpdateGoTo);
		$redirect_go_to = sprintf("Location: %s", stripslashes($massUpdateGoTo));
	}

	if (($action == "add") || ($action == "edit")) {

		$sponsorURL = check_http($purifier->purify(sterilize($_POST['sponsorURL'])));
		$sponsorName = capitalize($purifier->purify(sterilize($_POST['sponsorName'])));
		$sponsorText = $purifier->purify(sterilize($_POST['sponsorText']));

		// Logo is either an uploaded file OR a hotlinked URL (issue #371), never both -
		// clear whichever field the admin didn't choose so stale data from a prior save
		// (or from switching the radio before submitting) can't linger in the other column.
		if (sterilize($_POST['sponsorLogoSource']) == "url") {
			$sponsorImage = null;
			$sponsorImageURL = check_http($purifier->purify(sterilize($_POST['sponsorImageURL'])));
		} else {
			$sponsorImage = sterilize($_POST['sponsorImage']);
			$sponsorImageURL = null;
		}

		$update_table = $prefix."sponsors";
		$data = array(
			'sponsorName' => blank_to_null($sponsorName),
			'sponsorURL' => blank_to_null($sponsorURL),
			'sponsorImage' => blank_to_null($sponsorImage),
			'sponsorImageURL' => blank_to_null($sponsorImageURL),
			'sponsorText' => blank_to_null($sponsorText),
			'sponsorLocation' => blank_to_null(sterilize($_POST['sponsorLocation'])),
			'sponsorLevel' => blank_to_null(sterilize($_POST['sponsorLevel'])),
			'sponsorEnable' => blank_to_null(sterilize($_POST['sponsorEnable']))
		);

	}

	if ($action == "add") {

		$result = $db_conn->insert ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;
		
		if (!$result) $insertGoTo = $_POST['relocate']."&msg=3";
		$insertGoTo = prep_redirect_link($insertGoTo);
		$redirect_go_to = sprintf("Location: %s", $insertGoTo);

	}

	if ($action == "edit") {

		$errors = FALSE;
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