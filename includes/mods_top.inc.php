<?php 
if (isset($_SESSION['loginUsername'])) $user_level_mods = $_SESSION['userLevel']; 
else $user_level_mods = "2";

if ($totalRows_mods > 0) {
	if ($go != "mods") {
		foreach ($mods_display as $mod_id) {
			$mods = mod_display($mod_id,$section,$go,$user_level_mods,1);
			if (!empty($mods)) include($mods);
		} 
	}
}
?>