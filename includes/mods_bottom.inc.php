<?php
if ((!empty($_SESSION['mods_display'])) && ($go != "mods")) {
	foreach ($_SESSION['mods_display'] as $key => $value) {
		$mods_bottom = mod_display($value,$section,$go,$user_level_mods,2);
		if (($mods_bottom['file_ok'] == 1) && ($value['mod_enable'] == 1)) include (MODS.$value['mod_filename']);
	}
} 
?>