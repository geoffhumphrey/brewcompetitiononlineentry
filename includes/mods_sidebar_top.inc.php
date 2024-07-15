<?php 
if ($totalRows_mods > 0) {
	if ($go != "mods") {
		foreach ($mods_display as $mod_id) {
			$mods = mod_display($mod_id,$section,$go,$user_level_mods,3);
			if (!empty($mods)) include($mods);
		} 
	}
}
?>