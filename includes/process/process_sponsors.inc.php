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
			$sponsor_info = $purifier->purify($_POST['sponsorText'.$id]);
			$sponsor_info = filter_var($sponsor_info,FILTER_SANITIZE_STRING);
			if ($_POST['sponsorEnable'.$id] == 1) $enable = 1; else $enable = 0;
			if (isset($_POST['sponsorImage'.$id])) $image = $purifier->purify($_POST['sponsorImage'.$id]); else $image = "";
			$updateSQL = sprintf("UPDATE %s SET sponsorEnable='%s', sponsorLevel='%s', sponsorImage='%s', sponsorText='%s' WHERE id='%s'",$sponsors_db_table,$enable,$_POST['sponsorLevel'.$id],$image,$sponsor_info,$id);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		}

		$massUpdateGoTo = $base_url."index.php?section=admin&go=sponsors&msg=9";
		$pattern = array('\'', '"');
		$massUpdateGoTo = str_replace($pattern, "", $massUpdateGoTo);
		$redirect_go_to = sprintf("Location: %s", stripslashes($massUpdateGoTo));
	}

	if (($action == "add") || ($action == "edit")) {

		$sponsorURL = check_http($purifier->purify($_POST['sponsorURL']));
		$sponsorURL = sterilize($sponsorURL);
		$sponsorName = capitalize($purifier->purify($_POST['sponsorName']));
		$sponsorName = sterilize($sponsorName);
		$sponsorText = $purifier->purify($_POST['sponsorText']);
		$sponsorText = sterilize($sponsorText);

	}

	if ($action == "add") {

		$update_table = $prefix."sponsors";
		$data = array(
			'sponsorName' => $sponsorName,
			'sponsorURL' => $sponsorURL,
			'sponsorImage' => sterilize($_POST['sponsorImage']),
			'sponsorText' => $sponsorText,
			'sponsorLocation' => sterilize($_POST['sponsorLocation']),
			'sponsorLevel' => sterilize($_POST['sponsorLevel']),
			'sponsorEnable' => sterilize($_POST['sponsorEnable'])
		);
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

		$update_table = $prefix."sponsors";
		$data = array(
			'sponsorName' => $sponsorName,
			'sponsorURL' => $sponsorURL,
			'sponsorImage' => sterilize($_POST['sponsorImage']),
			'sponsorText' => $sponsorText,
			'sponsorLocation' => sterilize($_POST['sponsorLocation']),
			'sponsorLevel' => sterilize($_POST['sponsorLevel']),
			'sponsorEnable' => sterilize($_POST['sponsorEnable'])
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