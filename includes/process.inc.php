<?php
/*
 * Module:      process.inc.php
 * Description: This module does all the heavy lifting for any DB updates; new entries,
 *              new users, organization, etc.
 */

ob_start();
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', '0');

require ('../paths.php');
require (INCLUDES.'url_variables.inc.php');
require (INCLUDES.'styles.inc.php');
include (INCLUDES.'scrubber.inc.php');
include (LIB.'common.lib.php');
require (INCLUDES.'db_tables.inc.php');
include (LIB.'update.lib.php');
require (DB.'common.db.php');
include (LANG.'language.lang.php');
require (LIB.'process.lib.php');

// $default_from/$default_to are also set in includes/constants.inc.php, but that file assumes
// a full page-render context (it reads counts that includes/db/entries.db.php populates, and
// declares a function that some process/*.inc.php handlers already conditionally re-include this
// same file to get) - requiring the whole file here caused both an undefined-variable notice and
// a "cannot redeclare function" fatal error when those handlers ran afterward. Set just the two
// values this bootstrap actually needs instead.
if (!isset($default_from)) $default_from = "noreply";
if (!isset($default_to)) $default_to = "nosend";

$mail_use_smtp = FALSE;
if (HOSTED) $mail_use_smtp = TRUE;
elseif (isset($_SESSION['prefsEmailSMTP'])) { 
    if (($_SESSION['prefsEmailSMTP'] == 1) && (!empty($_SESSION['prefsEmailHost'])) && (!empty($_SESSION['prefsEmailFrom'])) && (!empty($_SESSION['prefsEmailUsername'])) && (!empty($_SESSION['prefsEmailPassword'])) && (!empty($_SESSION['prefsEmailPort']))) $mail_use_smtp = TRUE;
}

// Set timezone as Europe/London just in case
$timezone_raw = "0";

// Set up redirect var
$redirect_go_to = "";

// Track queries if debugging
if (DEBUG) include (DEBUGGING.'query_count_begin.debug.php');

// Check if setup is running, if so, check whether prefs have been established
// If so, get time zone setup by admin
if ($section == "setup") {

	if (check_setup($prefix."preferences",$database)) {

		if ($dbTable == $prefix."preferences") {
			$action = "edit";
		}

		else {
			$db_conn->where('id', '1');
			$row_prefs_tz = $db_conn->getOne($prefix."preferences", "prefsTimeZone");
			$totalRows_prefs_tz = $db_conn->count;

			if ($totalRows_prefs_tz > 0) {
				$timezone_raw = $row_prefs_tz['prefsTimeZone'];
			}
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

$process_allowed = FALSE;
if (isset($_SERVER['HTTP_REFERER'])) {
	$referrer = parse_url($_SERVER['HTTP_REFERER']);
	if ((($referrer['host'] == $_SERVER['SERVER_NAME']) && (isset($_SESSION['prefs'.$prefix_session]))) || ($setup_free_access)) $process_allowed = TRUE;
}

if ((isset($_SESSION['prefsSEF'])) && ($_SESSION['prefsSEF'] == "Y")) $sef = TRUE;

/**
 * Check for CSRF token.
 * If tokens match, continue with process.
 * If not, redirect to 403 (forbidden) error page.
 */

$request_method = strtoupper($_SERVER['REQUEST_METHOD']);
$bypass_token = array("login","logout","forgot","reset","paypal");

if (($request_method === "POST") && (!in_array($action,$bypass_token))) {

	$token_hash = FALSE;
	$posted = filter_input(INPUT_POST, 'user_session_token', FILTER_UNSAFE_RAW);
	$posted = is_string($posted) ? trim($posted) : '';
	$session = $_SESSION['user_session_token'] ?? '';

	// Validate shape first (example: 64 hex chars for 32 bytes)
	$valid_shape = (bool) preg_match('/^[a-f0-9]{64}$/i', $posted);

	if (($valid_shape) && ($session !== '') && (hash_equals($session, $posted))) {
		$token_hash = TRUE;
	}

	if (($posted === '') || (!$token_hash) || (!$process_allowed)) {
	    session_unset();
	    session_destroy();
	    session_write_close();
	    $redirect = $base_url."index.php?section=403";
	    $redirect = prep_redirect_link($redirect);
	    $redirect_go_to = sprintf("Location: %s", $redirect);
	    header($redirect_go_to);
	    exit();
	}

}

if (((isset($_SERVER['HTTP_REFERER'])) && ($referrer['host'] == $_SERVER['SERVER_NAME'])) && ((isset($_SESSION['prefs'.$prefix_session])) || ($setup_free_access))) {

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
	$system_db_table = $prefix."bcoem_sys";
	$themes_db_table = $prefix."themes";
	$users_db_table = $prefix."users";

	// --------------------------- // -------------------------------- //

	$insertGoTo = "";
	$updateGoTo = "";
	$massUpdateGoTo = "";
	$errorGoTo = "";
	$deleteGoTo = "";
	
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
	elseif 	(strstr($_SERVER['HTTP_REFERER'], $base_url."pay")) 		$deleteGoTo = $base_url."index.php?section=pay&msg=5";
	else $deleteGoTo = clean_up_url($_SERVER['HTTP_REFERER'])."&msg=5";

	// --------------------------- Various Actions ------------------------------- //

	// Log in, log out, forgot password
	if ($action == "login") include (INCLUDES.'logincheck.inc.php');
	elseif ($action == "logout") include (INCLUDES.'logout.inc.php');
	elseif (($action == "forgot") || ($action == "reset")) include (PROCESS.'process_forgot_password.inc.php');

	// Delete
	elseif ($action == "delete") include (PROCESS.'process_delete.inc.php');

	// Create a practice judging session
	//elseif ($action == "practice_session") include (PROCESS.'process_judging_practice_session.inc.php');
	
	// Barcode check in
	elseif ($action == "barcode_check_in") include (PROCESS.'process_barcode_check_in.inc.php');

	// Updating judging flights
	elseif ($action == "update_judging_flights") include (PROCESS.'process_judging_flight_check.inc.php');
	
	// Delete scoresheets in user_docs folder
	elseif ($action == "delete_scoresheets") {

		if ((!isset($_SESSION['userLevel'])) || ($_SESSION['userLevel'] > 1)) {
			header("Location: ".$base_url."403.php");
			exit();
		}

		rdelete(USER_DOCS,"");
		if ($filter == "admin-dashboard") $redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&msg=31");
		else $redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&go=upload_scoresheets&action=".$filter."&msg=31");

	}

	// Clear session vars
	elseif ($action == "clear_session") {

		unset($_SESSION['session_set_'.$prefix_session]);
		unset($_SESSION['prefs'.$prefix_session]);
		unset($_SESSION['user_info'.$prefix_session]);
		unset($_SESSION['contest_info_general'.$prefix_session]);

		if ($section == "update") $redirect_go_to = sprintf("Location: %s", $base_url."update.php");
		else $redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin");

	}

	// Data clean up
	elseif (($action == "purge") || ($action == "cleanup")) include (INCLUDES.'data_cleanup.inc.php');

	// Regenerate judging numbers
	elseif ($action == "generate_judging_numbers") {

		if ((!isset($_SESSION['userLevel'])) || ($_SESSION['userLevel'] > 1)) {
			header("Location: ".$base_url."403.php");
			exit();
		}

		generate_judging_numbers($prefix."brewing",$sort);

		if ($go == "hidden") $redirect_go_to =  sprintf("Location: %s", $base_url."index.php");
		elseif ($go == "entries") $redirect_go_to =  sprintf("Location: %s", $base_url."index.php?section=admin&go=entries&msg=14");
		else $redirect_go_to =  sprintf("Location: %s", $base_url."index.php?section=admin&msg=14");

	}

	// Check for any entry fee discounts
	elseif ($action == "check_discount") {

		$db_conn->where('id', '1');
		if (SINGLE) $db_conn->where('comp_id', $_SESSION['comp_id']);
		$row_contest_info1 = $db_conn->getOne($prefix."contest_info", "contestEntryFeePassword");

		$secretKey = base64_encode(bin2hex($password));
		$nacl = base64_encode(bin2hex($server_root));
		$contestEntryFeePassword = simpleDecrypt($row_contest_info1['contestEntryFeePassword'], $secretKey, $nacl);

		if (sterilize($_POST['brewerDiscount']) == $contestEntryFeePassword) {
			$db_conn->where('uid', $id);
			$result = $db_conn->update($brewer_db_table, array('brewerDiscount' => 'Y'));
			$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=list&bid=".$id."&msg=15");
		}

		else $redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=list&bid=".$id."&msg=16");
	}

	// Convert entries to selected BJCP version
	elseif ($action == "convert_bjcp") {

		if ((!isset($_SESSION['userLevel'])) || ($_SESSION['userLevel'] > 1)) {
			header("Location: ".$base_url."403.php");
			exit();
		}

		include (LIB.'convert.lib.php');

		if ($_SESSION['prefsStyleSet'] == "BJCP2008") {

			include (INCLUDES.'convert/convert_bjcp_2015.inc.php');

			$db_conn->where('id', '1');
			$result = $db_conn->update($prefix."preferences", array('prefsStyleSet' => 'BJCP2015'));

		}

		if ($_SESSION['prefsStyleSet'] == "BJCP2015") {

			include (INCLUDES.'convert/convert_bjcp_2021.inc.php');

			$db_conn->where('id', '1');
			$result = $db_conn->update($prefix."preferences", array('prefsStyleSet' => 'BJCP2021'));

		}

		if ($_SESSION['prefsStyleSet'] == "BJCP2021") {

			include (INCLUDES.'convert/convert_bjcp_2025.inc.php');

			$db_conn->where('id', '1');
			$result = $db_conn->update($prefix."preferences", array('prefsStyleSet' => 'BJCP2025'));

		}
		
		if (session_status() === PHP_SESSION_NONE) {
			session_name($prefix_session);
			session_start();
		}
		
		unset($_SESSION['prefs'.$prefix_session]);

		$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&go=entries&msg=25");

	}

	// Archive data
	elseif ($action == "archive") {

		if (HOSTED) include (PROCESS.'process_archive_hosted.inc.php');
		else include (PROCESS.'process_archive.inc.php');

	}

	/**
	 * Publish results - resets pertinent dates to the current timestamp -
	 * entry, acct registration, judge/steward registration, and judging deadlines.
	 * Marks all relevant dates in the past to trigger the winner display.
	 */ 
	elseif ($action == "publish") {

		$db_conn->where('id', '1');
		$result = $db_conn->update($prefix."preferences", array('prefsDisplayWinners' => 'Y', 'prefsWinnerDelay' => time()));

		if ($_SESSION['contestRegistrationDeadline'] > time()) {
			$db_conn->where('id', '1');
			$result = $db_conn->update($prefix."contest_info", array('contestRegistrationDeadline' => time()));
		}

		if ($_SESSION['contestEntryDeadline'] > time()) {
			$db_conn->where('id', '1');
			$result = $db_conn->update($prefix."contest_info", array('contestEntryDeadline' => time()));
		}

		if ($_SESSION['contestJudgeDeadline'] > time()) {
			$db_conn->where('id', '1');
			$result = $db_conn->update($prefix."contest_info", array('contestJudgeDeadline' => time()));
		}

		if ($_SESSION['jPrefsJudgingClosed'] > time()) {
			$db_conn->where('id', '1');
			$result = $db_conn->update($prefix."judging_preferences", array('jPrefsJudgingClosed' => time()));
		}

		$rows_judging_locations = $db_conn->get($prefix."judging_locations", null, "id,judgingDate");
		$row_judging_locations = ($rows_judging_locations && count($rows_judging_locations) > 0) ? $rows_judging_locations[0] : null;
		$totalRows_judging_locations = $db_conn->count;

		if ($totalRows_judging_locations > 0) {

			foreach ($rows_judging_locations as $row_judging_locations) {

				if ($row_judging_locations['judgingDate'] > time()) {

					$db_conn->where('id', $row_judging_locations['id']);
					$result = $db_conn->update($prefix."judging_locations", array('judgingDate' => time()));

				}

			}

		}

		$db_conn->where('judgingLocType', '1');
		$result = $db_conn->update($prefix."judging_locations", array('judgingDateEnd' => time()));

		if (session_status() === PHP_SESSION_NONE) {
			session_name($prefix_session);
			session_start();
		}
		unset($_SESSION['prefs'.$prefix_session]);

		$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&msg=36");

	}

	// Email functions
	elseif (($action == "email") && ($dbTable == "default")) include (PROCESS.'process_email.inc.php');
	
	// Paypal IPN
	elseif (($action == "paypal") && ($dbTable == "default")) include (PROCESS.'process_paypal.inc.php');
	
	// Updates to associated entry, acct registration, judge/steward registration, and judging dates
	elseif (($action == "dates") && ($dbTable == "default")) include (PROCESS.'process_dates.inc.php');
	
	// Update to various DB Tables as called out in process URL
	else {

		if ($dbTable == $prefix."brewing") include (PROCESS.'process_brewing.inc.php');
		if ($dbTable == $prefix."users") include (PROCESS.'process_users.inc.php');
		if ($dbTable == $prefix."brewer") include (PROCESS.'process_brewer.inc.php');
		if ($dbTable == $prefix."contest_info") include (PROCESS.'process_comp_info.inc.php');
		if ($dbTable == $prefix."preferences") include (PROCESS.'process_prefs.inc.php');
		if ($dbTable == $prefix."sponsors") include (PROCESS.'process_sponsors.inc.php');
		if ($dbTable == $prefix."judging_locations") include (PROCESS.'process_judging_locations.inc.php');
		if ($dbTable == $prefix."drop_off") include (PROCESS.'process_drop_off.inc.php');
		if (($dbTable == $prefix."styles") || ($dbTable == "bcoem_shared_styles")) include (PROCESS.'process_styles.inc.php');
		if ($dbTable == $prefix."contacts") include (PROCESS.'process_contacts.inc.php');
		if ($dbTable == $prefix."judging_preferences") include (PROCESS.'process_judging_preferences.inc.php');
		if ($dbTable == $prefix."judging_tables") include (PROCESS.'process_judging_tables.inc.php');
		if ($dbTable == $prefix."judging_flights") include (PROCESS.'process_judging_flights.inc.php');
		if ($dbTable == $prefix."judging_assignments") include (PROCESS.'process_judging_assignments.inc.php');
		if ($dbTable == $prefix."judging_scores") include (PROCESS.'process_judging_scores.inc.php');
		if ($dbTable == $prefix."judging_scores_bos") include (PROCESS.'process_judging_scores_bos.inc.php');
		if ($dbTable == $prefix."style_types") include (PROCESS.'process_style_types.inc.php');
		if ($dbTable == $prefix."special_best_info") include (PROCESS.'process_special_best_info.inc.php');
		if ($dbTable == $prefix."special_best_data") include (PROCESS.'process_special_best_data.inc.php');
		if ($dbTable == $prefix."mods") include (PROCESS.'process_mods.inc.php');
		if ($dbTable == $prefix."evaluation") include (EVALS.'process.eval.php');

	}

	if (DEBUG) include (DEBUGGING.'query_count_end.debug.php');
	session_write_close();

	// Failsafe to convert &amp; to & and so on for use in header redirect.
	$redirect_go_to = html_entity_decode($redirect_go_to);
	header($redirect_go_to);

}

else {
	header(sprintf("Location: %s", $base_url."index.php?msg=98"));
}

exit();
?>