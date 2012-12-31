<?php
if ($go != "mods") {
	foreach ($mods_display as $id) {
		$mods = mod_display($id,$section,$go,$row_user['userLevel'],0,$section);
		if ($mods != "") include($mods);
	} 
}
?>