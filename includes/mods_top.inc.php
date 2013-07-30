<?php 
if ($totalRows_mods > 0) {
	if ($go != "mods") {
		foreach ($mods_display as $id) {
			$mods = mod_display($id,$section,$go,$_SESSION['userLevel'],1);
			if (!empty($mods)) include($mods);
		} 
	}
}
?>