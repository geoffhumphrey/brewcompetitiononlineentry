<?php
/*
 * Module:      process_styles.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "styles" table
 */

if ((isset($_SERVER['HTTP_REFERER'])) && (((isset($_SESSION['loginUsername'])) && ((isset($_SESSION['userLevel'])) && ($_SESSION['userLevel'] <= 1))) || ($section == "setup"))) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

	if (HOSTED) $styles_db_table = "bcoem_shared_styles";
	else $styles_db_table = $prefix."styles";

	// Instantiate HTMLPurifier
	require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
	$config_html_purifier = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config_html_purifier);

	$ba_styles_accepted = "";
	$brewStyleEntry = "";
	$brewStyleInfo = "";
	$brewStyleLink = "";
	$brewStyleStrength = "";
	
	if (isset($_POST['brewStyleEntry'])) {
		$brewStyleEntry = trim($_POST['brewStyleEntry']);
		$brewStyleEntry = $purifier->purify($brewStyleEntry);
		$brewStyleEntry = filter_var($brewStyleEntry,FILTER_SANITIZE_SPECIAL_CHARS,FILTER_FLAG_ENCODE_HIGH|FILTER_FLAG_ENCODE_LOW);
	}
	
	if (isset($_POST['brewStyleInfo'])) {
		$brewStyleInfo = trim($_POST['brewStyleInfo']);
		$brewStyleInfo = $purifier->purify($brewStyleInfo);
		$brewStyleInfo = filter_var($brewStyleInfo,FILTER_SANITIZE_SPECIAL_CHARS,FILTER_FLAG_ENCODE_HIGH|FILTER_FLAG_ENCODE_LOW);
	}
	
	if (isset($_POST['brewStyleLink'])) $brewStyleLink = sterilize($_POST['brewStyleLink']);

	if ((isset($_POST['brewStyleType'])) && ($_POST['brewStyleType'] == 2)) $brewStyleStrength = 0; 
	else {
		$brewStyleStrength = 0;
		if (isset($_POST['brewStyleStrength'])) $brewStyleStrength = sterilize($_POST['brewStyleStrength']);
	}

	if ($action == "update") {

		$update_selected_styles = array();

		foreach($_POST['id'] as $id) {

			if ($filter == "default") {
				
				if (isset($_POST['brewStyleActive'.$id])) {

					$style_id = sterilize($id);

					if (HOSTED) {
						
						$query_styles_default = sprintf("SELECT * FROM %s WHERE id='%s'", $styles_db_table, $style_id);
						$styles_default = mysqli_query($connection,$query_styles_default);
						$row_styles_default = mysqli_fetch_assoc($styles_default);

						if ($row_styles_default) {
							$update_selected_styles[$row_styles_default['id']] = array(
								'brewStyle' => $row_styles_default['brewStyle'],
								'brewStyleGroup' => $row_styles_default['brewStyleGroup'],
								'brewStyleNum' => $row_styles_default['brewStyleNum'],
								'brewStyleVersion' => $row_styles_default['brewStyleVersion']
							);
						}
						
						$query_styles_custom = sprintf("SELECT * FROM %s WHERE id='%s'", $prefix."styles", $style_id);
						$styles_custom = mysqli_query($connection,$query_styles_custom);
						$row_styles_custom = mysqli_fetch_assoc($styles_custom);

						if ($row_styles_custom) {
							$update_selected_styles[$row_styles_custom['id']] = array(
								'brewStyle' => sterilize($row_styles_custom['brewStyle']),
								'brewStyleGroup' => sterilize($row_styles_custom['brewStyleGroup']),
								'brewStyleNum' => sterilize($row_styles_custom['brewStyleNum']),
								'brewStyleVersion' => sterilize($row_styles_custom['brewStyleVersion'])
							);
						}
					
					} // end if (HOSTED)
						
					else {

						$query_styles_default = sprintf("SELECT id, brewStyle, brewStyleGroup, brewStyleNum, brewStyleVersion FROM %s WHERE id='%s'", $styles_db_table, $style_id);
						$styles_default = mysqli_query($connection,$query_styles_default);
						$row_styles_default = mysqli_fetch_assoc($styles_default);
						$totalRows_styles_default = mysqli_num_rows($styles_default);

						if ($row_styles_default) {
							$update_selected_styles[$row_styles_default['id']] = array(
								'brewStyle' => sterilize($row_styles_default['brewStyle']),
								'brewStyleGroup' => sterilize($row_styles_default['brewStyleGroup']),
								'brewStyleNum' => sterilize($row_styles_default['brewStyleNum']),
								'brewStyleVersion' => sterilize($row_styles_default['brewStyleVersion'])
							);
						}

					} // end else
				
				} // if (isset($_POST['brewStyleActive'.$id]))

			} // end if ($filter == "default")

		} // end foreach($_POST['id'] as $id)

		$update_selected_styles = json_encode($update_selected_styles);

		$update_table = $prefix."preferences";
		$data = array(
			'prefsSelectedStyles' => blank_to_null($update_selected_styles)
		);
		$db_conn->where ('id', 1);
		$result = $db_conn->update ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		// Empty the prefs session variable
		// Will trigger the session to reset the variables in common.db.php upon reload after redirect
		unset($_SESSION['prefs'.$prefix_session]);

		if ($section == "setup") {

			$update_table = $prefix."bcoem_sys";
			$data = array('setup_last_step' => 7);
			$db_conn->where ('id', 1);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

			$redirect = $base_url."setup.php?section=step8";
			if ($errors) $base_url."setup.php?section=step7&msg=3";
			$redirect = prep_redirect_link($redirect);
			$redirect_go_to = sprintf("Location: %s", $redirect);

		}

		else {

			if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

			if ($errors) $massUpdateGoTo = $base_url."index.php?section=admin&go=styles&msg=3";
			else $massUpdateGoTo = $base_url."index.php?section=admin&go=styles&msg=2";
			$massUpdateGoTo = prep_redirect_link($massUpdateGoTo);
			$redirect_go_to = sprintf("Location: %s", $massUpdateGoTo);

		}

	} // end if ($action == "update");

	if (($action == "add") || ($action == "edit")) {

		$update_table = $prefix."styles";
		$data = array(
			'brewStyle' => blank_to_null($purifier->purify($_POST['brewStyle'])),
			'brewStyleOG' => blank_to_null(sterilize($_POST['brewStyleOG'])),
			'brewStyleOGMax' => blank_to_null(sterilize($_POST['brewStyleOGMax'])),
			'brewStyleFG' => blank_to_null(sterilize($_POST['brewStyleFG'])),
			'brewStyleFGMax' => blank_to_null(sterilize($_POST['brewStyleFGMax'])),
			'brewStyleABV' => blank_to_null(sterilize($_POST['brewStyleABV'])),
			'brewStyleABVMax' => blank_to_null(sterilize($_POST['brewStyleABVMax'])),
			'brewStyleIBU' => blank_to_null(sterilize($_POST['brewStyleIBU'])),
			'brewStyleIBUMax' => blank_to_null(sterilize($_POST['brewStyleIBUMax'])),
			'brewStyleSRM' => blank_to_null(sterilize($_POST['brewStyleSRM'])),
			'brewStyleSRMMax' => blank_to_null(sterilize($_POST['brewStyleSRMMax'])),
			'brewStyleType' => blank_to_null(sterilize($_POST['brewStyleType'])),
			'brewStyleInfo' => blank_to_null($brewStyleInfo),
			'brewStyleLink' => blank_to_null($brewStyleLink),
			'brewStyleGroup' => blank_to_null(sterilize($_POST['brewStyleGroup'])),
			'brewStyleNum' => blank_to_null(sterilize($_POST['brewStyleNum'])),
			'brewStyleActive' => blank_to_null(sterilize($_POST['brewStyleActive'])),
			'brewStyleOwn' => blank_to_null(sterilize($_POST['brewStyleOwn'])),
			'brewStyleVersion' => $_SESSION['prefsStyleSet'],
			'brewStyleReqSpec' => blank_to_null(sterilize($_POST['brewStyleReqSpec'])),
			'brewStyleStrength' => blank_to_null(sterilize($_POST['brewStyleStrength'])),
			'brewStyleCarb' => blank_to_null(sterilize($_POST['brewStyleCarb'])),
			'brewStyleSweet' => blank_to_null(sterilize($_POST['brewStyleSweet'])),
			'brewStyleEntry' => blank_to_null($brewStyleEntry)
		);

	}

	if ($action == "add") {

		$result = $db_conn->insert ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		if ($errors) $insertGoTo = $_POST['relocate']."&msg=3";
		$insertGoTo = prep_redirect_link($insertGoTo);
		$redirect_go_to = sprintf("Location: %s", $insertGoTo);

	} // end if ($action == "add");

	if ($action == "edit") {

		$db_conn->where ('id', $id);
		$result = $db_conn->update ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		$query_log = sprintf("SELECT id FROM $brewing_db_table WHERE brewStyle = '%s'",$_POST['brewStyleOld']);
		$log = mysqli_query($connection,$query_log) or die (mysqli_error($connection));
		$row_log = mysqli_fetch_assoc($log);
		$totalRows_log = mysqli_num_rows($log);

		if ($totalRows_log > 0) {

			do {

				$update_table = $prefix."brewing";
				$data = array('brewStyle' => $_POST['brewStyle']);
				$db_conn->where ('id', $row_log['id']);
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			} while ($row_log = mysqli_fetch_assoc($log));

		}

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		if ($errors) $updateGoTo = $_POST['relocate']."&msg=3";
		$updateGoTo = prep_redirect_link($updateGoTo);
		$redirect_go_to = sprintf("Location: %s", $updateGoTo);

	}

	if (($action == "add") || ($action == "edit")) {

		if ($_POST['brewStyleActive'] == "Y") {

			// If adding, look up latest id
			if ($action == "add") {

				$query_last_style_added = sprintf("SELECT id FROM %s ORDER BY id DESC LIMIT 1",$prefix."styles");
				$last_style_added = mysqli_query($connection,$query_last_style_added) or die (mysqli_error($connection));
				$row_last_style_added = mysqli_fetch_assoc($last_style_added);

				$id = $row_last_style_added['id'];

			}

			// If editing, use $id
			
			$update_selected_styles = json_decode($_SESSION['prefsSelectedStyles'], true);
			$update_selected_styles[$id] = array(
				'brewStyle' => $purifier->purify($_POST['brewStyle']),
				'brewStyleGroup' => sterilize($_POST['brewStyleGroup']),
				'brewStyleNum' => sterilize($_POST['brewStyleNum']),
				'brewStyleVersion' => sterilize($_SESSION['prefsStyleSet'])
			);

			$update_selected_styles = json_encode($update_selected_styles);
			//echo $update_selected_styles; exit();

			$update_table = $prefix."preferences";
			$data = array(
				'prefsSelectedStyles' => blank_to_null($update_selected_styles)
			);
			$db_conn->where ('id', 1);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			// Empty the prefs session variable
			// Will trigger the session to reset the variables in common.db.php upon reload after redirect
			unset($_SESSION['prefs'.$prefix_session]);
		}

	}

} else {
	
	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);
	
}
?>