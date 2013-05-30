<?php
if ($totalRows_mods > 0) {
	if ($go != "mods") {
		foreach ($mods_display as $mbid) {
			$mods = mod_display($mbid,$section,$go,$_SESSION['userLevel'],0,$section);
			if ($mods != "") include($mods);
		} 
	}
}
?>