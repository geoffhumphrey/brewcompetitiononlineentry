<?php 

// Redirect if directly accessed without authenticated session
if ((session_status() == PHP_SESSION_NONE) || ((isset($_SESSION['loginUsername'])) && (!function_exists('sterilize')))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

if ($totalRows_mods > 0) {
	if ($go != "mods") {
		foreach ($mods_display as $mod_id) {
			$mods = mod_display($mod_id,$section,$go,$user_level_mods,3);
			if (!empty($mods)) include($mods);
		} 
	}
}
?>