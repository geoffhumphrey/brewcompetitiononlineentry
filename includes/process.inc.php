<?php

/*
Checked Single
2016-06-06
*/

/*
 * Module:      process.inc.php
 * Description: This module does all the heavy lifting for any DB updates; new entries,
 *              new users, organization, etc.
 */

ob_start();
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', '1');

require('../paths.php');
require(INCLUDES.'url_variables.inc.php');
include (INCLUDES.'scrubber.inc.php');
mysqli_select_db($connection,$database);
require(DB.'common.db.php');
include (LIB.'common.lib.php');
include (LANG.'language.lang.php');

if (NHC) $base_url = "../";
else $base_url = $base_url;

// Set timezone as Europe/London just in case
$timezone_raw = "0";

// Check if setup is running, if so, check whether prefs have been established
// If so, get time zone setup by admin
if (($section == "setup") && ($dbTable != $prefix."preferences")) {

	include (LIB.'update.lib.php');

	if (check_setup($prefix."preferences",$database)) {

		$query_prefs_tz = sprintf("SELECT prefsTimeZone FROM %s WHERE id='1'", $prefix."preferences");
		$prefs_tz = mysqli_query($connection,$query_prefs_tz) or die (mysqli_error($connection));
		$row_prefs_tz = mysqli_fetch_assoc($prefs_tz);
		$totalRows_prefs_tz = mysqli_num_rows($prefs_tz);

		if ($totalRows_prefs_tz > 0) {
			$timezone_raw = $row_prefs_tz['prefsTimeZone'];
		}

	}

}

// If running normally, get time zone from cookie
// Set timezone globals for the site
else  $timezone_raw = $_SESSION['prefsTimeZone'];

// Establish time zone for all date-related functions
$timezone_prefs = get_timezone($timezone_raw);
date_default_timezone_set($timezone_prefs);
$tz = date_default_timezone_get();

// Check for Daylight Savings Time (DST) - if true, add one hour to the offset
$bool = date("I");
if ($bool == 1) $timezone_offset = number_format(($timezone_raw + 1.000),0);
else $timezone_offset = number_format($timezone_raw,0);

if (isset($_SERVER['HTTP_REFERER'])) {
	$referrer = parse_url($_SERVER['HTTP_REFERER']);
	// echo $referrer['host']."<br>".$_SERVER['SERVER_NAME']; exit;
}



if (((isset($_SERVER['HTTP_REFERER'])) && ($referrer['host'] == $_SERVER['SERVER_NAME'])) && ((isset($_SESSION['prefs'.$prefix_session])) || ($setup_free_access))) {

	require(LIB.'process.lib.php');

	$archive_db_table = $prefix."archive";
	$brewer_db_table = $prefix."brewer";
	$brewing_db_table = $prefix."brewing";
	$contacts_db_table = $prefix."contacts";
	$contest_info_db_table = $prefix."contest_info";
	$drop_off_db_table = $prefix."drop_off";
	$judging_assignments_db_table = $prefix."judging_assignments";
	$judging_flights_db_table = $prefix."judging_flights";
	$judging_locations_db_table = $prefix."judging_locations";
	$judging_preferences_db_table = $prefix."judging_preferences";
	$judging_scores_db_table = $prefix."judging_scores";
	$judging_scores_bos_db_table = $prefix."judging_scores_bos";
	$judging_tables_db_table = $prefix."judging_tables";
	$mods_db_table = $prefix."mods";
	$preferences_db_table = $prefix."preferences";
	$special_best_data_db_table = $prefix."special_best_data";
	$special_best_info_db_table = $prefix."special_best_info";
	$sponsors_db_table = $prefix."sponsors";
	$staff_db_table = $prefix."staff";
	$styles_db_table = $prefix."styles";
	$style_types_db_table = $prefix."style_types";
	$system_db_table = $prefix."system";
	$themes_db_table = $prefix."themes";
	$users_db_table = $prefix."users";

	if (($section == "setup") && (($dbTable == $contest_info_db_table) || ($dbTable == $drop_off_db_table) || ($dbTable == $judging_locations_db_table) || ($dbTable == $styles_db_table) || ($dbTable == $judging_preferences_db_table) || ($dbTable == $brewer_db_table) || ($dbTable == $preferences_db_table))) {
		require(DB.'common.db.php');
	}

	// --------------------------- // -------------------------------- //


	if (NHC) {
		$insertGoTo = "../";
		$updateGoTo = "../";
		$massUpdateGoTo = "../";
	}
	else {
		$insertGoTo = "";
		$updateGoTo = "";
		$massUpdateGoTo = "";
		$errorGoTo = "";
	}

	if (isset($_POST['relocate'])) {

		if (strpos($_POST['relocate'],"?") === false) {
			$insertGoTo .= $_POST['relocate']."?msg=1";
			$updateGoTo .= $_POST['relocate']."?msg=2";
			$errorGoTo .= $_POST['relocate']."?msg=3";
			$massUpdateGoTo .= $_POST['relocate']."?msg=9";
		}

		else {
			$insertGoTo .= $_POST['relocate']."&msg=1";
			$updateGoTo .= $_POST['relocate']."&msg=2";
			$errorGoTo .= $_POST['relocate']."&msg=3";
			$massUpdateGoTo .= $_POST['relocate']."&msg=9";
		}

	}

		if 		(strstr($_SERVER['HTTP_REFERER'], $base_url."list"))  		$deleteGoTo = $base_url."index.php?section=list&msg=5";
		elseif 	(strstr($_SERVER['HTTP_REFERER'], $base_url."rules")) 		$deleteGoTo = $base_url."index.php?section=rules&msg=5";
		elseif 	(strstr($_SERVER['HTTP_REFERER'], $base_url."volunteers")) 	$deleteGoTo = $base_url."index.php?section=volunteers&msg=5";
		elseif 	(strstr($_SERVER['HTTP_REFERER'], $base_url."sponsors")) 	$deleteGoTo = $base_url."index.php?section=sponsors&msg=5";
		elseif 	(strstr($_SERVER['HTTP_REFERER'], $base_url."pay")) 			$deleteGoTo = $base_url."index.php?section=pay&msg=5";
		else $deleteGoTo = clean_up_url($_SERVER['HTTP_REFERER'])."&msg=5";

		//echo $insertGoTo;
		//echo $updateGoTo;
		//echo $deleteGoTo;
		//exit;
		//session_start();


	// --------------------------- Various Actions ------------------------------- //

	if ($action == "delete") include (PROCESS.'process_delete.inc.php');
	elseif ($action == "beerxml") include (PROCESS.'process_beerxml.inc.php');
	elseif ($action == "update_judging_flights") include (PROCESS.'process_judging_flight_check.inc.php');

	elseif ($action == "delete-scoresheets") {

		$upload_dir = (USER_DOCS);
		$file_mimes = array('image/jpeg','image/jpg','image/gif','image/png','application/pdf'); // Allowable file mime types
		$file_exts  = array('.jpeg','.jpg','.png','.gif','.pdf'); // Allowable file extensions

		if (!is_dir_empty($upload_dir)) {

			$handle = opendir($upload_dir);

			while ($file = readdir($handle)) {

	   			if(!is_dir($file) && !is_link($file)) {

					$file_extension = explode('.', $_FILES['file']['name']);

				}

			}
		}
	}

	elseif ($action == "purge") {


	}

	elseif ($action == "generate_judging_numbers") {
		generate_judging_numbers($prefix."brewing",$sort);
		if ($go == "hidden") $updateGoTo = $base_url."index.php";
		elseif ($go == "entries") $updateGoTo = $base_url."index.php?section=admin&go=entries&msg=14";
		else $updateGoTo = $base_url."index.php?section=admin&msg=14";
		header(sprintf("Location: %s", $updateGoTo));
	}

	elseif ($action == "check_discount") {

			$query_contest_info1 = sprintf("SELECT contestEntryFeePassword FROM %s WHERE id=1",$prefix."contest_info");
			if (SINGLE) $query_contest_info1 .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
			$contest_info1 = mysqli_query($connection,$query_contest_info1) or die (mysqli_error($connection));
			$row_contest_info1 = mysqli_fetch_assoc($contest_info1);

			if ($_POST['brewerDiscount'] == $row_contest_info1['contestEntryFeePassword']) {
				$updateSQL = sprintf("UPDATE $brewer_db_table SET brewerDiscount=%s WHERE uid=%s",
							   GetSQLValueString("Y", "text"),
							   GetSQLValueString($id, "text"));

				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				header(sprintf("Location: %s", $base_url."index.php?section=pay&bid=".$id."&msg=12"));
			}

			else header(sprintf("Location: %s", $base_url."index.php?section=pay&bid=".$id."&msg=13"));
	}

	elseif ($action == "convert_bjcp") {

		bjcp_convert();
		session_name($prefix_session);
		session_start();
		unset($_SESSION['prefs'.$prefix_session]);

		$updateSQL = sprintf("UPDATE %s SET prefsStyleSet='%s' WHERE id='%s'",$prefix."preferences","BJCP2015","1");
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$updateGoTo = $base_url."index.php?section=admin&go=entries&msg=25";
		header(sprintf("Location: %s", $updateGoTo));
	}

	elseif ($action == "archive") {

		if (HOSTED) include (PROCESS.'process_archive_hosted.inc.php');
		else include (PROCESS.'process_archive.inc.php');

	}
	
	elseif ($action == "publish") {
		
		$update = sprintf("UPDATE %s SET prefsDisplayWinners='%s', prefsWinnerDelay='%s' WHERE id='%s'",$prefix."preferences","Y","0","1");
		mysqli_real_escape_string($connection,$update);
		$result = mysqli_query($connection,$update) or die (mysqli_error($connection));

		session_name($prefix_session);
		session_start();
		unset($_SESSION['prefs'.$prefix_session]);
		
		$updateGoTo = $base_url."index.php?section=admin&msg=36";
		header(sprintf("Location: %s", $updateGoTo));
		
	}

	elseif (($action == "email") && ($dbTable == "default")) {

		include (PROCESS.'process_email.inc.php');

	}

	elseif (($action == "paypal") && ($dbTable == "default")) {

		include (PROCESS.'process_paypal.inc.php');

	}

	else {

		// include (DEBUGGING.'session_vars.debug.php');

		// --------------------------- Entries -------------------------------- //

		if ($dbTable == $prefix."brewing")				include (PROCESS.'process_brewing.inc.php');

		// --------------------------- Users ------------------------------- //

		if ($dbTable == $prefix."users") 				include (PROCESS.'process_users.inc.php');

		// --------------------------- Participant or Admin's Info ------------------------------- //

		if ($dbTable == $prefix."brewer") 				include (PROCESS.'process_brewer.inc.php');

		// --------------------------- General Contest Info ------------------------------- //

		if ($dbTable == $prefix."contest_info") 		include (PROCESS.'process_comp_info.inc.php');

		// --------------------------- Preferences ------------------------------- //

		if ($dbTable == $prefix."preferences") 			include (PROCESS.'process_prefs.inc.php');

		// --------------------------- Sponsors ------------------------------- //

		if ($dbTable == $prefix."sponsors") 			include (PROCESS.'process_sponsors.inc.php');

		// --------------------------- Judging Locations ------------------------------- //

		if ($dbTable == $prefix."judging_locations") 	include (PROCESS.'process_judging_locations.inc.php');

		// --------------------------- Drop-off Locations ------------------------------- //

		if ($dbTable == $prefix."drop_off") 			include (PROCESS.'process_drop_off.inc.php');

		// --------------------------- Styles --------------------------- //

		if ($dbTable == $prefix."styles") 				include (PROCESS.'process_styles.inc.php');

		// --------------------------- If Adding a Contact (Non-setup) --------------------------- //

		if ($dbTable == $prefix."contacts")				include (PROCESS.'process_contacts.inc.php');

		// --------------------------- If Editing Judging Preferences ------------------------------- //

		if ($dbTable == $prefix."judging_preferences") 	include (PROCESS.'process_judging_preferences.inc.php');

		// --------------------------- Tables and Associated Styles ------------------------------- //

		if ($dbTable == $prefix."judging_tables")		include (PROCESS.'process_judging_tables.inc.php');

		// --------------------------- Flights ------------------------------- //

		if ($dbTable == $prefix."judging_flights") 		include (PROCESS.'process_judging_flights.inc.php');

		// --------------------------- Judging Assignments ------------------------------- //

		if ($dbTable == $prefix."judging_assignments") 	include (PROCESS.'process_judging_assignments.inc.php');

		// --------------------------- Scores ------------------------------- //

		if ($dbTable == $prefix."judging_scores") 		include (PROCESS.'process_judging_scores.inc.php');

		// --------------------------- BOS Scores ------------------------------- //

		if ($dbTable == $prefix."judging_scores_bos") 	include (PROCESS.'process_judging_scores_bos.inc.php');

		// --------------------------- Style Types ------------------------------- //

		if ($dbTable == $prefix."style_types") 			include (PROCESS.'process_style_types.inc.php');

		// --------------------------- Custom Winner Category Info ------------------------------- //

		if ($dbTable == $prefix."special_best_info") 	include (PROCESS.'process_special_best_info.inc.php');

		// --------------------------- Custom Winner Category Entries ------------------------------- //

		if ($dbTable == $prefix."special_best_data") 	include (PROCESS.'process_special_best_data.inc.php');

		// --------------------------- Custom Modules ------------------------------- //

		if ($dbTable == $prefix."mods") 				include (PROCESS.'process_mods.inc.php');

	}

}

else {
	header(sprintf("Location: %s", $base_url."index.php?msg=98"));
	exit;
}

?>