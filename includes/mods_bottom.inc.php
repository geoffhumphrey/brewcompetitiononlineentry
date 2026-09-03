<?php
if ((!empty($_SESSION['mods_display'])) && ($go != "mods")) {
	foreach ($_SESSION['mods_display'] as $key => $value) {
		$mods_bottom = mod_display($value,$section,$go,$user_level_mods,2);
		if (($mods_bottom['file_ok'] == 1) && ($value['mod_enable'] == 1)) {
			$mod_real_path = realpath(MODS.$value['mod_filename']);
			$mods_real_dir = realpath(MODS);
			if (($mod_real_path !== FALSE) && ($mods_real_dir !== FALSE) && (strpos($mod_real_path, $mods_real_dir.DIRECTORY_SEPARATOR) === 0)) {
				include($mod_real_path);
			}
		}
	}
} 
?>