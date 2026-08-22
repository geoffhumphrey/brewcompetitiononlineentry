<?php
/**
 * Module:      mods.db.php
 * Description: This module houses all custom module related queries
 *              0=none
 * 				1=home
 * 				2=rules -- deprecated --
 * 				3=volunteer -- deprecated --
 * 				4=sponsors -- deprecated --
 * 				5=contact -- deprecated --
 * 				6=register
 * 				7=pay -- deprecated --
 * 				8=list
 * 				9=admin
 */

if (($section == "admin") && ($go == "mods")) {

	if ($action == "default") $db_conn->orderBy("mod_name", "ASC");
	elseif ($action == "edit") $db_conn->where("id", $id);
	$rows_mods = $db_conn->get($mods_db_table);
	$row_mods = ($rows_mods && count($rows_mods) > 0) ? $rows_mods[0] : null;
	$totalRows_mods = $db_conn->count;

}

if ((!isset($_SESSION['mods_display'])) || ((isset($_SESSION['mods_display'])) && (empty($_SESSION['mods_display'])))) {

	$rows_mods_display = $db_conn->get($mods_db_table);
	$row_mods_display = ($rows_mods_display && count($rows_mods_display) > 0) ? $rows_mods_display[0] : null;
	$totalRows_mods_display = $db_conn->count;

	$mods_display_arr = [];

	if ($totalRows_mods_display > 0) {
		foreach ($rows_mods_display as $row_mods_display) {
			$mods_display_arr[] = [
				'id' => $row_mods_display['id'],
				'mod_extend_function' => $row_mods_display['mod_extend_function'],
				'mod_extend_function_admin' => $row_mods_display['mod_extend_function_admin'],
				'mod_permission' => $row_mods_display['mod_permission'],
				'mod_display_rank' => $row_mods_display['mod_display_rank'],
				'mod_filename' => $row_mods_display['mod_filename'],
				'mod_enable' => $row_mods_display['mod_enable'],
				'mod_type' => $row_mods_display['mod_type']
			];
		}
	}

	$_SESSION['mods_display'] = $mods_display_arr;

}

function mod_display($row_mod_display,$section,$go,$user_level,$page_location) {

	/**
	 * @param $row_mod_display is an array of mod data from a single row in the DB.
	 * @param $page_location can be 1 (before core) or 2 (after core)
	 */

	$file_not_found = 0;
	$file_ok = 0;
	$output = "";

	$display_section = match ($section) {
        "default", "rules", "volunteers", "sponsors", "contact", "pay" => 1,
        "register" => 6,
        "list" => 8,
        "admin" => 9,
        default => 0,
    };

	if (!empty($row_mod_display)) {

		// Check if file exists and if it is enabled
		if (file_exists(MODS.$row_mod_display['mod_filename'])) {

			if (($section != "admin") && (($display_section == $row_mod_display['mod_extend_function']) || ($row_mod_display['mod_extend_function'] == 0))) {
				if (($row_mod_display['mod_display_rank'] == $page_location) && ($row_mod_display['mod_permission'] >= $user_level)) {
					$file_ok = 1;
				}
			}

			if ($section == "admin") {
				//if (($row_mod_display['mod_type'] == 0) || ($row_mod_display['mod_type'] == 3)) {
					if (($row_mod_display['mod_display_rank'] == $page_location) && ($row_mod_display['mod_permission'] >= $user_level)) {
						if ($go == $row_mod_display['mod_extend_function_admin']) {
							$file_ok = 1;
						}
					}
				//}
			}

		}

		else {
			$file_not_found = 1;
			$output = $row_mod_display['mod_filename'];
		}

	}

	return [
		'file_not_found' => $file_not_found,
		'file_ok' => $file_ok,
		'output' => $output
	];

}
?>