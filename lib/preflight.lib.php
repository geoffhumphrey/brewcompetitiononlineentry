<?php
include (LIB.'update.lib.php');

$update_required = FALSE;
$setup_success = FALSE;
$force_update = FALSE;
$no_updates_needed = FALSE;
$hosted_setup = FALSE;
$check_setup = FALSE;
$system_name_change = FALSE;
$recently_updated = FALSE;

if (check_setup($prefix."system",$database)) {
	
	$query_system = sprintf("SELECT * FROM `%s` WHERE id='1'",$prefix."system");
	$system = mysqli_query($connection,$query_system) or die (mysqli_error($connection));
	$row_system = mysqli_fetch_assoc($system);
	
	if ($row_system['version'] != $current_version) {
		unset($_SESSION['session_set_'.$prefix_session]);
		unset($_SESSION['currentVersion']);
	}
	
	if ((HOSTED) && ($row_system['setup_last_step'] == 8)) $hosted_setup = TRUE;
	$check_setup = TRUE;

}

if (check_setup($prefix."bcoem_sys",$database)) {
	
	$system_name_change = TRUE;
	$query_system = sprintf("SELECT * FROM `%s` WHERE id='1'",$prefix."bcoem_sys");
	$system = mysqli_query($connection,$query_system) or die (mysqli_error($connection));
	$row_system = mysqli_fetch_assoc($system);
	
	if ($row_system['version'] != $current_version) {
		unset($_SESSION['session_set_'.$prefix_session]);
		unset($_SESSION['currentVersion']);
	}

	if ((HOSTED) && ($row_system['setup_last_step'] == 8)) $hosted_setup = TRUE;
	$check_setup = TRUE;

}

if (((isset($_SESSION['update_complete'])) && ($_SESSION['update_complete'] == 1)) && (isset($_SESSION['update_summary']))) $recently_updated = TRUE;

if ((!isset($_SESSION['currentVersion'])) || ((isset($_SESSION['currentVersion'])) && ($_SESSION['currentVersion'] == 0))) {
	
	// The following line will need to change with future conversions
	if ((!check_setup($prefix."mods",$database)) && (!check_setup($prefix."preferences",$database))) {
		$setup_relocate = "Location: ".$base_url."setup.php?section=step0";
	}

	// For older versions (pre-1.3.0.0)
	if ((!check_setup($prefix."mods",$database)) && (check_setup($prefix."preferences",$database))) {
		$setup_relocate = "Location: ".$base_url."update.php";
	}

	if ($check_setup) {

		$setup_success = TRUE;

		/**
		 * Check if "prefsShipping" column is in the prefs table 
		 * since it was added in the 2.1.6.0 release. 
		 * If not, run the update.
		 */

		if (!check_update("prefsShipping", $prefix."preferences")) {
			$update_required = TRUE;
			$setup_relocate = "Location: ".$base_url."update.php";
		}

		/**
		 * Check if setup was completed successfully
		 */
		
		if ($row_system['setup'] == 0) {
			$setup_relocate = "Location: ".$base_url."setup.php?section=step".($row_system['setup_last_step']+1);

			if ($row_system['setup_last_step'] == 1) {
				$query_user = sprintf("SELECT user_name FROM %s WHERE id='1'",$prefix."users");
				$user = mysqli_query($connection,$query_user) or die (mysqli_error($connection));
				$row_user = mysqli_fetch_assoc($user);
				$setup_relocate .= "&go=".$row_user['user_name'];
			}

			$setup_relocate .= "&msg=1";
		}

		/**
		 * If the current version is the same as what is in 
		 * the DB, trigger a force update if system version 
		 * date in DB is prior to the current version date.
		 * Covers updates made in between pre-releases 
		 * and full version.
		 */

		if ($row_system['version'] == $current_version) {
			if ((strtotime($row_system['version_date'])) < ($current_version_date)) $force_update = TRUE;
			$setup_relocate = "Location: ".$base_url;
		}

		else {

			// Run update scripts if required
			if ($update_required) {
				$setup_relocate = "Location: ".$base_url."update.php";
			}

			else {
				$force_update = TRUE;
				$setup_relocate = "Location: ".$base_url;
			}

		}

	}

	if (FORCE_UPDATE) $force_update = TRUE;

	if (!$setup_success) {
		header ($setup_relocate);
		exit();
	}

	else {
		if (!$force_update) $no_updates_needed = TRUE;
	}

} // end if (!isset($_SESSION['currentVersion']))

else $setup_success = TRUE;


if ((!$system_name_change) && (check_setup($prefix."system",$database))) {
	$query_sys = sprintf("RENAME TABLE `%s` TO `%s`",$prefix."system",$prefix."bcoem_sys");
	$sys = mysqli_query($connection,$query_sys) or die (mysqli_error($connection));
	$system_name_change = TRUE;
}

/*
if (!isset($_SESSION['currentVersion'])) echo "No currentVersion session variable.<br>";
else echo "Version is ". $_SESSION['currentVersion']."<br>";

if ($check_setup) echo "Setup checked, bcoem_sys table present.<br>";
else echo "Setup checked, bcoem_sys table NOT present.<br>";

if ($system_name_change) echo "System DB table name has been changed to bcoem_sys.<br>";
else echo "System DB table name has NOT been changed to bcoem_sys.<br>";

if ($setup_success) {
	if ($row_system['version'] == $current_version) echo "Versions match.<br>";
	else echo "Versions DO NOT match.<br>";
	if ($force_update) echo "Update will run.<br>";
	else echo "Update will NOT run.<br>";
	echo $row_system['version'];
	echo "<br>";
	echo $current_version;
}
else echo "Setup has not run<br>";
*/

?>