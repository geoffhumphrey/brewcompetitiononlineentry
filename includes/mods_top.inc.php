<?php 
if (isset($_SESSION['loginUsername'])) $user_level_mods = $_SESSION['userLevel']; 
else $user_level_mods = "2";

$mod_top_alert = "";
$missing_enabled_files = "";
$missing_disabled_files = "";

if ((!empty($_SESSION['mods_display'])) && ($go != "mods")) {

	foreach ($_SESSION['mods_display'] as $key => $value) {

		if (!file_exists(MODS.$value['mod_filename'])) {
			if ($value['mod_enable'] == 1) $missing_enabled_files .= "<li>".$value['mod_filename']."</li>";
			if ($value['mod_enable'] == 0) $missing_disabled_files .= "<li>".$value['mod_filename']."</li>";
		}
		
		$mods_top = mod_display($value,$section,$go,$user_level_mods,1);

		if (($mods_top['file_ok'] == 1) && ($value['mod_enable'] == 1)) {
			include(MODS.$value['mod_filename']);
		}

	}

	if (!empty($missing_enabled_files)) {

		$mod_top_alert .= "<div class='alert alert-danger alert-dismissible' role='alert'>";
		$mod_top_alert .= "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
		$mod_top_alert .= "<i class='fa fa-lg fa-exclamation-triangle'></i> ";
		$mod_top_alert .= "<strong>The following <u>enabled</u> custom module files were not found in the mods directory.</strong> These cannot be included or rendered:";
		$mod_top_alert .= "<ul>";
		$mod_top_alert .= $missing_enabled_files;
		$mod_top_alert .= "</ul>";
		$mod_top_alert .= "</div>";

	}

	if (!empty($missing_disabled_files)) {

		$mod_top_alert .= "<div class='alert alert-warning alert-dismissible' role='alert'>";
		$mod_top_alert .= "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
		$mod_top_alert .= "<i class='fa fa-lg fa-exclamation-circle'></i> ";
		$mod_top_alert .= "<strong>The following <u>disabled</u> custom module files were not found in the mods directory.</strong> These cannot be included or rendered if enabled:";
		$mod_top_alert .= "<ul>";
		$mod_top_alert .= $missing_disabled_files;
		$mod_top_alert .= "</ul>";
		$mod_top_alert .= "</div>";

	}

}

if (($section == "admin") && ($go == "default")) {
	echo $mod_top_alert;
}
?>