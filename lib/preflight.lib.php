<?php
include (LIB.'update.lib.php');

$update_required = FALSE;
$setup_success = TRUE;

// The following line will need to change with future conversions
if ((!check_setup($prefix."mods",$database)) && (!check_setup($prefix."preferences",$database))) {
/*  */
	$setup_success = FALSE;
	$setup_relocate = "Location: ".$base_url."setup.php?section=step0";

}

// For older versions (pre-1.3.0.0)
if ((!check_setup($prefix."mods",$database)) && (check_setup($prefix."preferences",$database))) {

	$setup_success = FALSE;
	$setup_relocate = "Location: ".$base_url."update.php";

}

elseif (MAINT) {

	$setup_success = FALSE;
	$setup_relocate = "Location: ".$base_url."maintenance.php";

}

if (check_setup($prefix."system",$database)) {

	mysqli_select_db($connection,$database);
	$query_system = sprintf("SELECT * FROM %s WHERE id='1'",$prefix."system");
	$system = mysqli_query($connection,$query_system) or die (mysqli_error($connection));
	$row_system = mysqli_fetch_assoc($system);

	// For current version, check if "prefsShipping" column is in the prefs table since it was added in the 2.1.6.0 release
	// If not, run the update

	if (!check_update("prefsShipping", $prefix."preferences")) {

		$update_required = TRUE;
		$setup_success = FALSE;
		$setup_relocate = "Location: ".$base_url."update.php";

	}

	// Check if setup was completed successfully
	if ($row_system['setup'] == 0) {

		$setup_success = FALSE;

		$setup_relocate = "Location: ".$base_url."setup.php?section=step".($row_system['setup_last_step']+1);

		if ($row_system['setup_last_step'] == 1) {
			$query_user = sprintf("SELECT user_name FROM %s WHERE id='1'",$prefix."users");
			$user = mysqli_query($connection,$query_user) or die (mysqli_error($connection));
			$row_user = mysqli_fetch_assoc($user);
			$setup_relocate .= "&go=".$row_user['user_name'];
		}

		$setup_relocate .= "&msg=1";
	}

	// If not, flag as such and define relation var


	// Perform various checks and update various DB
	include (UPDATE.'off_schedule_update.php');

	if ($row_system['version'] != $current_version) {

		// Run update scripts if required
		if ($update_required) {
			$setup_success = FALSE;
			$setup_relocate = "Location: ".$base_url."update.php";
		}

		// Change version number in DB only if there is no need to run the update scripts

		else {

			$updateSQL = sprintf("UPDATE %s SET version='%s', version_date='%s', setup='1' WHERE id='1'",$prefix."system",$current_version,"2017-07-22");
			mysqli_select_db($connection,$database);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			// echo $updateSQL."<br>";

			$setup_success = TRUE;
			$setup_relocate = "Location: ".$base_url;

		}

	}

}

if (!$setup_success) {

	header ($setup_relocate);
	exit;

}

?>