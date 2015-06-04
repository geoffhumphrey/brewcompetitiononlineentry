<?php
/**
 * Module:      mods.db.php
 * Description: This module houses all custom module related queries
 
  0=none 1=home 2=rules 3=volunteer 4=sponsors 5=contact 6=register 7=pay 8=list 9=admin
 */
if (NHC) {
	// Place NHC SQL calls below
	
	
}
// end if (NHC)
else {
		
	$query_mods = "SELECT * FROM $mods_db_table";
	if ($section == "default") 		$query_mods .= " WHERE (mod_extend_function='1' OR mod_extend_function='0') AND mod_enable='1' ORDER BY mod_rank ASC";
	elseif ($section == "rules") 	$query_mods .= " WHERE (mod_extend_function='2' OR mod_extend_function='0') AND mod_enable='1' ORDER BY mod_rank ASC";
	elseif ($section == "volunteer")$query_mods .= " WHERE (mod_extend_function='3' OR mod_extend_function='0') AND mod_enable='1' ORDER BY mod_rank ASC";
	elseif ($section == "sponsors") $query_mods .= " WHERE (mod_extend_function='4' OR mod_extend_function='0') AND mod_enable='1' ORDER BY mod_rank ASC";
	elseif ($section == "contact") 	$query_mods .= " WHERE (mod_extend_function='5' OR mod_extend_function='0') AND mod_enable='1' ORDER BY mod_rank ASC";
	elseif ($section == "register") $query_mods .= " WHERE (mod_extend_function='6' OR mod_extend_function='0') AND mod_enable='1' ORDER BY mod_rank ASC";
	elseif ($section == "pay") 		$query_mods .= " WHERE (mod_extend_function='7' OR mod_extend_function='0') AND mod_enable='1' ORDER BY mod_rank ASC";
	elseif ($section == "list") 	$query_mods .= " WHERE (mod_extend_function='8' OR mod_extend_function='0') AND mod_enable='1' ORDER BY mod_rank ASC";
	elseif (($section == "admin") && ($action == "default") && ($go != "mods")) $query_mods .= " WHERE (mod_extend_function='9' OR mod_extend_function='0') AND mod_enable='1' ORDER BY mod_rank ASC";
	elseif (($section == "admin") && ($action == "default") && ($go == "mods")) $query_mods .= " ORDER BY mod_name ASC";
	elseif (($section == "admin") && ($action == "edit") && ($go == "mods")) 	$query_mods .= sprintf(" WHERE id='%s'",$id);
	
	$mods = mysql_query($query_mods, $brewing) or die(mysql_error());
	$row_mods = mysql_fetch_assoc($mods);
	$totalRows_mods = mysql_num_rows($mods);
	
	//echo "<p>".$query_mods."</p>";
	
	if ($go != "mods") {
		do { $mods_display[] = $row_mods['id']; } while ($row_mods = mysql_fetch_assoc($mods));
	}
	
	//print_r($mods_display);
	
	function mod_display($id,$section,$go,$user_level,$page_location) {
		
		require(CONFIG.'config.php');	
		mysql_select_db($database, $brewing);
		
		$query_mod_display = sprintf("SELECT * FROM %s WHERE mod_enable='1' AND id='%s'",$prefix."mods",$id);
		$mod_display = mysql_query($query_mod_display, $brewing) or die(mysql_error());
		$row_mod_display = mysql_fetch_assoc($mod_display);
		
		$output = "";
		
		switch ($section) {
			case "default": 	$display_section = 1; break;
			case "rules": 		$display_section = 2; break;
			case "volunteers": 	$display_section = 3; break;
			case "sponsors": 	$display_section = 4; break;	
			case "contact": 	$display_section = 5; break;
			case "register": 	$display_section = 6; break;
			case "pay": 		$display_section = 7; break;
			case "list": 		$display_section = 8; break;
			case "admin": 		$display_section = 9; break;
		}
		
		if (($section != "admin") && (($display_section == $row_mod_display['mod_extend_function']) || ($row_mod_display['mod_extend_function'] == 0))) {
			if (($row_mod_display['mod_display_rank'] == $page_location) && ($row_mod_display['mod_permission'] >= $user_level)) $output .= (MODS.$row_mod_display['mod_filename']);
		}
		
		if ($section == "admin") {
			if (($row_mod_display['mod_type'] == 0) || ($row_mod_display['mod_type'] == 3)) {
				if (($row_mod_display['mod_display_rank'] == $page_location) && ($row_mod_display['mod_permission'] >= $user_level)) {
					if (($go == $row_mod_display['mod_extend_function_admin']) || ($row_mod_display['mod_extend_function'] == 0)) $output .= (MODS.$row_mod_display['mod_filename']);
				}
			}
		}
		
		return $output;
	}
}
?>