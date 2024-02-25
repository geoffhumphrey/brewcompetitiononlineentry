<?php
/*
 * Module:      process_style_types.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "style_types" table
 */
if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

	// Instantiate HTMLPurifier
	require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
	$config_html_purifier = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config_html_purifier);

	$styleTypeName = "";

	if (isset($_POST['styleTypeName'])) {
		$styleTypeName = $purifier->purify($_POST['styleTypeName']);
		$styleTypeName = capitalize($styleTypeName);
		$styleTypeName = sterilize($styleTypeName,);
	}
		
	if ($action == "add") {

		// Determine the greatest id value that is in the style_types table
		$query_id_last_num = sprintf("SELECT id FROM %s ORDER BY id DESC LIMIT 1",$prefix."style_types");
		$id_last_num = mysqli_query($connection,$query_id_last_num) or die (mysqli_error($connection));
		$row_id_last_num = mysqli_fetch_assoc($id_last_num);

		// ids 1-15 are reserved for system use
		if ($row_id_last_num['id'] < 16) $id = 16;
		else $id = $row_id_last_num['id'] + 1;

		$update_table = $prefix."style_types";
		$data = array(
			'id' => $id,
			'styleTypeName' => blank_to_null($styleTypeName),
			'styleTypeOwn' => blank_to_null(sterilize($_POST['styleTypeOwn'])),
			'styleTypeBOS' => blank_to_null(sterilize($_POST['styleTypeBOS'])),
			'styleTypeBOSMethod' => blank_to_null(sterilize($_POST['styleTypeBOSMethod'])),
			'styleTypeEntryLimit' => blank_to_null(sterilize($_POST['styleTypeEntryLimit']))
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

		if ($go == "combine") {

			$update_table = $prefix."style_types";

			// Activate Mead/Cider style type
			$data = array('styleTypeBOS' => 'Y');
			$db_conn->where ('styleTypeName', 'Mead/Cider');
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			// Deactivate Cider
			$data = array(
				'styleTypeBOS' => 'N',
				'styleTypeEntryLimit' => NULL
			);
			$db_conn->where ('id', 2);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			// Deactivate Mead
			$data = array(
				'styleTypeBOS' => 'N',
				'styleTypeEntryLimit' => NULL
			);
			$db_conn->where ('id', 3);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			// Delete any BOS score entries with Mead or Cider ids; failsafe
			$update_table = $prefix."judging_scores_bos";
			$db_conn->where ('scoreType', 2);
			$db_conn->orWhere ('scoreType', 3);
			$result = $db_conn->delete ($update_table);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$updateGoTo = $base_url."index.php?section=admin&go=style_types&msg=2";

		}

		elseif ($go == "separate") {

			$update_table = $prefix."style_types";

			// Deactivate Mead/Cider style type
			$data = array(
				'styleTypeBOS' => 'N',
				'styleTypeEntryLimit' => NULL
			);
			$db_conn->where ('styleTypeName', 'Mead/Cider');
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			// Activate Cider
			$data = array('styleTypeBOS' => 'Y');
			$db_conn->where ('id', 2);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			// Activate Mead
			$data = array('styleTypeBOS' => 'Y');
			$db_conn->where ('id', 3);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			// Delete any BOS score entries with Mead/Cider id; failsafe
			$query_mead_cider_present = sprintf("SELECT id FROM %s WHERE styleTypeName = 'Mead/Cider'",$prefix."style_types");
			$mead_cider_present = mysqli_query($connection,$query_mead_cider_present) or die (mysqli_error($connection));
			$row_mead_cider_present = mysqli_fetch_assoc($mead_cider_present);

			$update_table = $prefix."judging_scores_bos";
			$db_conn->where ('scoreType', $row_mead_cider_present['id']);
			$result = $db_conn->delete ($update_table);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$updateGoTo = $base_url."index.php?section=admin&go=style_types&msg=2";

		}

		else {

			$update_table = $prefix."style_types";
			$data = array(
				'styleTypeName' => blank_to_null($styleTypeName),
				'styleTypeOwn' => blank_to_null(sterilize($_POST['styleTypeOwn'])),
				'styleTypeBOS' => blank_to_null(sterilize($_POST['styleTypeBOS'])),
				'styleTypeBOSMethod' => blank_to_null(sterilize($_POST['styleTypeBOSMethod'])),
				'styleTypeEntryLimit' => blank_to_null(sterilize($_POST['styleTypeEntryLimit']))
			);
			$db_conn->where ('id', $id);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

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