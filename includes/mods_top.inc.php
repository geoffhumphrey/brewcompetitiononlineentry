<?php 
include(DB.'mods.db.php');
if ($go != "mods") {
	foreach ($mods_display as $id) {
		$mods = mod_display($id,$section,$go,$row_user['userLevel'],1,$section);
		if ($mods != "") include($mods);
	} 
}
?>