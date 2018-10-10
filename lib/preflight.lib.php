<?php
include (LIB.'update.lib.php');

$update_required = FALSE;
$setup_success = TRUE;
$force_update = FALSE;
if (FORCE_UPDATE) $force_update = TRUE;

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

elseif ((MAINT) && ($section != "maintenance")) {

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


	if ($row_system['version'] == $current_version) {
		// If the current version is the same as what is in the DB, trigger a force update
		// if system version date in DB is prior to the current version date
		// covers updates made in between pre-releases and full version
		if ((strtotime($row_system['version_date'])) < ($current_version_date)) $force_update = TRUE;
	}

	if ($row_system['version'] != $current_version) {

		// Run update scripts if required
		if ($update_required) {
			$setup_success = FALSE;
			$setup_relocate = "Location: ".$base_url."update.php";
		}

		else {
			$force_update = TRUE;
			$setup_success = TRUE;
			$setup_relocate = "Location: ".$base_url;
		}

	}

	if ((EVALUATION) && (!check_setup($prefix."evaluation",$database)))  {
		require_once (EVALS.'install_eval_db.inc.php');
	}

}

if (!$setup_success) {

	header ($setup_relocate);
	exit;

}

?>