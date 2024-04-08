<?php

/*
// Redirect if directly accessed without authenticated session
if ((session_status() == PHP_SESSION_NONE) || ((isset($_SESSION['loginUsername'])) && (!function_exists('sterilize')))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}
*/

if ($totalRows_mods > 0) {
	if ($go != "mods") {
		foreach ($mods_display as $mid) {
			$mods1 = mod_display($mid,$section,$go,$user_level_mods,2);
			if (!empty($mods1)) include($mods1);
		} 
	}
}
?>