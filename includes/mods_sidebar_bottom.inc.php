<?php
if ($totalRows_mods > 0) {
	if ($go != "mods") {
		foreach ($mods_display as $mid) {
			$mods1 = mod_display($mid,$section,$go,$_SESSION['userLevel'],4);
			if (!empty($mods1)) include($mods1);
		} 
	}
}
?>