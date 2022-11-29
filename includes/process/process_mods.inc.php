blank_to_null(<?php
/*
 * Module:      process_mods.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "mods" table
 */

if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

	// Instantiate HTMLPurifier
	require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
	$config_html_purifier = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config_html_purifier);

	$update_table = $prefix."mods";

	if ($action == "update") {
		
		foreach($_POST['id'] as $id) {

			if ((isset($_POST['mod_enable'.$id])) && ($_POST['mod_enable'.$id] == 1)) $enable = 1; 
			else $enable = 0;

			
			$data = array('mod_enable' => $enable);
			$db_conn->where ('id', $id);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

		}

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		$redirect = $base_url."index.php?section=admin&go=mods&msg=9";
		if ($errors) $redirect = $base_url."index.php?section=admin&go=mods&msg=3";
		$redirect = prep_redirect_link($redirect);
		$redirect_go_to = sprintf("Location: %s", $redirect);

	} // end if ($action == "update")

	if (($action == "add") || ($action == "edit")) {

		if ((!isset($_POST['mod_extend_function_admin'])) && ((isset($_POST['mod_extend_function'])) && ($_POST['mod_extend_function'] == 9))) $mod_extend_function_admin = "default";
		elseif (isset($_POST['mod_extend_function_admin'])) $mod_extend_function_admin = $_POST['mod_extend_function_admin'];
		else $mod_extend_function_admin = "";

		$mod_name = blank_to_null($purifier->purify($_POST['mod_name']));
		$mod_name = blank_to_null(sterilize($mod_name));
		$mod_type = blank_to_null(sterilize($_POST['mod_type']));
		$mod_extend_function = blank_to_null(sterilize($_POST['mod_extend_function']));
		$mod_extend_function_admin = blank_to_null(sterilize($mod_extend_function_admin));
		$mod_filename = blank_to_null(sterilize($_POST['mod_filename']));
		$mod_description = blank_to_null($purifier->purify($_POST['mod_description']));
		$mod_description = blank_to_null(sterilize($mod_description));
		$mod_description = blank_to_null(trim($mod_description));
		$mod_permission = blank_to_null(sterilize($_POST['mod_permission']));
		$mod_rank = blank_to_null(sterilize($_POST['mod_rank']));
		$mod_display_rank = blank_to_null(sterilize($_POST['mod_display_rank']));
		$mod_enable = blank_to_null(sterilize($_POST['mod_enable']));

		$data = array(
			'mod_name' => $mod_name,
			'mod_type' => $mod_type,
			'mod_extend_function' => $mod_extend_function,
			'mod_extend_function_admin' => $mod_extend_function_admin,
			'mod_filename' => $mod_filename,
			'mod_description' => $mod_description,
			'mod_permission' => $mod_permission,
			'mod_rank' => $mod_rank,
			'mod_display_rank' => $mod_display_rank,
			'mod_enable' => $mod_enable
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

	}

	if ($action == "edit") {

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