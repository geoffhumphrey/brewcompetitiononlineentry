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
	
	$query_mods = sprintf("SELECT * FROM `%s`",$mods_db_table);
	if ($action == "default") $query_mods .= " ORDER BY mod_name ASC";
	elseif ($action == "edit") $query_mods .= sprintf(" WHERE id='%s'",$id);
	$mods = mysqli_query($connection,$query_mods) or die (mysqli_error($connection));
	$row_mods = mysqli_fetch_assoc($mods);
	$totalRows_mods = mysqli_num_rows($mods);

}

if ((!isset($_SESSION['mods_display'])) || ((isset($_SESSION['mods_display'])) && (empty($_SESSION['mods_display'])))) {

	$query_mods_display = sprintf("SELECT * FROM `%s`",$mods_db_table);
	$mods_display = mysqli_query($connection,$query_mods_display) or die (mysqli_error($connection));
	$row_mods_display = mysqli_fetch_assoc($mods_display);
	$totalRows_mods_display = mysqli_num_rows($mods_display);

	$mods_display_arr = array();

	if ($totalRows_mods_display > 0) do { 
		$mods_display_arr[] = array(
			'id' => $row_mods_display['id'],
			'mod_extend_function' => $row_mods_display['mod_extend_function'],
			'mod_extend_function_admin' => $row_mods_display['mod_extend_function_admin'],
			'mod_permission' => $row_mods_display['mod_permission'],
			'mod_display_rank' => $row_mods_display['mod_display_rank'],
			'mod_filename' => $row_mods_display['mod_filename'],
			'mod_enable' => $row_mods_display['mod_enable'],
			'mod_type' => $row_mods_display['mod_type']
		);
	} while ($row_mods_display = mysqli_fetch_assoc($mods_display));

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
	
	switch ($section) {
		
		case "default": 	
		case "rules": 		
		case "volunteers": 	
		case "sponsors": 		
		case "contact": 
		case "pay": $display_section = 1; 
		break;

		case "register": $display_section = 6; 
		break;

		case "list": $display_section = 8; 
		break;

		case "admin": $display_section = 9; 
		break;

		default: $display_section = 0; 
		break;

	}

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

	return array(
		'file_not_found' => $file_not_found,
		'file_ok' => $file_ok,
		'output' => $output
	);

}
?>