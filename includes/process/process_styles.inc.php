blank_to_null(<?php
/*
 * Module:      process_styles.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "styles" table
 */

if ((isset($_SERVER['HTTP_REFERER'])) && (((isset($_SESSION['loginUsername'])) && ((isset($_SESSION['userLevel'])) && ($_SESSION['userLevel'] <= 1))) || ($section == "setup"))) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

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

		$update_table = $prefix."styles";
		$data = array('brewStyleActive' => 'N');
		$result = $db_conn->update ($update_table, $data);

		foreach($_POST['id'] as $id) {

			if (isset($_POST['brewStyleActive'.$id])) $brewStyleActive = "Y";
			else $brewStyleActive = "N";

			if ($filter == "default") {

				$update_table = $prefix."styles";
				$data = array('brewStyleActive' => $brewStyleActive);
				$db_conn->where ('id', $id);
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			} // end if ($filter == "default")

			if (($filter == "judging") && ($bid == $_POST["brewStyleJudgingLoc".$id])) {

				$update_table = $prefix."styles";
				$data = array('brewStyleJudgingLoc' => sterilize($_POST["brewStyleJudgingLoc".$id]));
				$db_conn->where ('id', $id);
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

				// Also need to find all records in the "brewing" table (entries) that are null or have either old judging location associated with the style and update them with the new judging location.
				$query_style_name = "SELECT *FROM $styles_db_table WHERE id='".$id."'";
				$style_name = mysqli_query($connection,$query_style_name) or die (mysqli_error($connection));
				$row_style_name = mysqli_fetch_assoc($style_name);

				$query_loc = sprintf("SELECT * FROM $brewing_db_table WHERE brewCategorySort='%s' AND brewSubCategory='%s'", $row_style_name['brewStyleGroup'], $row_style_name['brewStyleNum']);
				$loc = mysqli_query($connection,$query_loc) or die (mysqli_error($connection));
				$row_loc = mysqli_fetch_assoc($loc);
				$totalRows_loc = mysqli_num_rows($loc);

				if ($totalRows_loc > 0) {
					
					do {

						if ($row_loc['brewJudgingLocation'] != $_POST["brewStyleJudgingLoc".$id]) {

							$update_table = $prefix."brewing";
							$data = array('brewJudgingLocation' => sterilize($_POST["brewStyleJudgingLoc".$id]));
							$db_conn->where ('id', $row_loc['id']);
							$result = $db_conn->update ($update_table, $data);
							if (!$result) {
								$error_output[] = $db_conn->getLastError();
								$errors = TRUE;
							}

						}

					} while($row_loc = mysqli_fetch_assoc($loc));

				} // end if ($totalRows_loc > 0)

			} // end if (($filter == "judging") && ($bid == $_POST["brewStyleJudgingLoc".$id]))

		} // end foreach($_POST['id'] as $id)

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

			if ($errors) $massUpdateGoTo = $_POST['relocate']."&msg=3";
			$massUpdateGoTo = prep_redirect_link($updateGoTo);
			$redirect_go_to = sprintf("Location: %s", $massUpdateGoTo);

		}

	} // end if ($action == "update");

	if ($action == "add") {

		$order_by = "id";
		$category_end = $_SESSION['style_set_category_end'];

		$query_style_name = sprintf("SELECT id,brewStyleGroup,brewStyleNum FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup >= %s ORDER BY brewStyleGroup DESC LIMIT 1", $styles_db_table, $_SESSION['prefsStyleSet'], $category_end);
		$style_name = mysqli_query($connection,$query_style_name) or die (mysqli_error($connection));
		$row_style_name = mysqli_fetch_assoc($style_name);

		if ((is_numeric($row_style_name['brewStyleGroup'])) && ($row_style_name['brewStyleGroup'] > $category_end)) $style_add_one = $row_style_name['brewStyleGroup'] + 1;
		else $style_add_one = $category_end + 1;
		
		if ($_SESSION['style_set_sub_style_method'] == 1) {
			if ($_SESSION['prefsStyleSet'] == "BA") $style_num_add_one = $row_style_name['brewStyleNum'] + 1;
			else $style_num_add_one = 1;
			$style_num_add_one = str_pad($style_num_add_one,3,"0", STR_PAD_LEFT);
		}
		else $style_num_add_one = "A";

		$update_table = $prefix."styles";
		$data = array(
			'brewStyleNum' => $style_num_add_one,
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
			'brewStyleGroup' => blank_to_null(sterilize($style_add_one)),
			'brewStyleActive' => blank_to_null(sterilize($_POST['brewStyleActive'])),
			'brewStyleOwn' => blank_to_null(sterilize($_POST['brewStyleOwn'])),
			'brewStyleVersion' => $_SESSION['prefsStyleSet'],
			'brewStyleReqSpec' => blank_to_null(sterilize($_POST['brewStyleReqSpec'])),
			'brewStyleStrength' => blank_to_null(sterilize($_POST['brewStyleStrength'])),
			'brewStyleCarb' => blank_to_null(sterilize($_POST['brewStyleCarb'])),
			'brewStyleSweet' => blank_to_null(sterilize($_POST['brewStyleSweet'])),
			'brewStyleEntry' => blank_to_null($brewStyleEntry)
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

	} // end if ($action == "add");

	if ($action == "edit") {

		$update_table = $prefix."styles";
		$data = array(
			'brewStyleNum' => sterilize($_POST['brewStyleNum']),
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
			'brewStyleActive' => blank_to_null(sterilize($_POST['brewStyleActive'])),
			'brewStyleOwn' => blank_to_null(sterilize($_POST['brewStyleOwn'])),
			'brewStyleVersion' => $_SESSION['prefsStyleSet'],
			'brewStyleReqSpec' => blank_to_null(sterilize($_POST['brewStyleReqSpec'])),
			'brewStyleStrength' => blank_to_null(sterilize($_POST['brewStyleStrength'])),
			'brewStyleCarb' => blank_to_null(sterilize($_POST['brewStyleCarb'])),
			'brewStyleSweet' => blank_to_null(sterilize($_POST['brewStyleSweet'])),
			'brewStyleEntry' => blank_to_null($brewStyleEntry)
		);
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

} else {
	
	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);
	
}
?>