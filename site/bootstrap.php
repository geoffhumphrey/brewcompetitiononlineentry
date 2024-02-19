<?php

/**
 * Module:      bootstrap.php
 * Description: This module houses specific global variables and various pre-flight checks.
 *
 *
 */

// ---------------------------- Base URL -----------------------------------------------

if (NHC) $base_url = "";
else $base_url = $base_url;

// ---------------------------- Globals ------------------------------------------------

$php_version = phpversion();

$ajax_url = $base_url."ajax/";

$js_url = $base_url."js_includes/";
if (HOSTED) $js_url = "https://brewingcompetitions.com/_bcoem_shared/js_includes/";

$images_url = $base_url."images/";
if (HOSTED) $images_url = "https://brewingcompetitions.com/_bcoem_shared/images/";

$css_url = $base_url."css/";
if (HOSTED) $css_url = "https://brewingcompetitions.com/_bcoem_shared/css/";

$js_app_url = $js_url."app.min.js";
$css_common_url = $css_url."common.min.css";

if ((DEBUG) || (TESTING)) {
   
    $css_common_url = str_replace(".min", "", $css_common_url);
    $theme = str_replace(".min", "", $theme);
    
    if (strpos($base_url, 'test.brewingcompetitions.com') !== false) {
        $js_app_url = $base_url."js_source/app.js";
    }
    
    $js_app_url .= "?t=".time();
    
}

// ---------------------------- Preflight Checks ---------------------------------------
require_once (LIB.'preflight.lib.php');

// ---------------------------- Run Scripts --------------------------------------------

// If all setup or update has taken place, run normally
if ($setup_success) {

	$errors = FALSE;
	$error_output = array();
	if (!isset($_SESSION['error_output'])) $_SESSION['error_output'] = "";

	// ---------------------------- Define URL Variables -------------------------------
	require_once (INCLUDES.'url_variables.inc.php');

	// ---------------------------- Check if Valid Section -----------------------------
	$section_array = array(
		"default", "rules", "entry", "volunteers", "contact", "pay", "list", "admin", "login", "logout", "check", "brewer", "user", "setup", "judge", "register", "sponsors", "past_winners", "brew", "step1", "step2", "step3", "step4", "step5", "step6", "step7", "step8", "update", "confirm", "delete", "table_cards", "participant_summary", "loc", "sorting", "output_styles", "map", "driving", "scores", "entries", "participants", "emails", "assignments", "bos-mat", "dropoff", "summary", "inventory", "pullsheets", "results", "sorting", "staff", "styles", "promo", "table-cards", "testing", "notes", "qr", "shipping-label", "particpant-entries", "evaluation", "competition", "past-winners"
	);

	// ---------------------------- QR Redirect --------------------------------------

	// Redirect to QR Code Check-In page if necessary
	if ($section == "qr") {
		header(sprintf("Location: %s", $base_url."qr.php"));
		exit;
	}

	// ---------------------------- IE Browser Check ---------------------------------

	// Check for IE and redirect if not using a version beyond 9

	$ua = FALSE;

	if (isset($_SERVER['HTTP_USER_AGENT'])) {

		$ua_array = explode(' ', $_SERVER['HTTP_USER_AGENT']);
		$msie_key = array_search('MSIE', $ua_array);

		if($msie_key !== false) {
			$msie_version_key = $msie_key + 1;
			$msie_version = intval($ua_array[$msie_version_key]);
			if ($msie_version <= 9) $ua = TRUE;
		}

	}

	// ---------------------------- Load Required Scripts ----------------------------

	if (SINGLE) require_once(SSO.'sso.inc.php');
	require_once (LIB.'common.lib.php');
	require_once (INCLUDES.'db_tables.inc.php');
	if ($force_update) include (UPDATE.'off_schedule_update.php');
	require_once (LIB.'help.lib.php');
	require_once (INCLUDES.'styles.inc.php'); // Establishing session vars depends upon arrays here
	require_once (DB.'common.db.php');
	require_once (DB.'brewer.db.php');
	require_once (DB.'entries.db.php');
	require_once (INCLUDES.'constants.inc.php');
	require_once (LANG.'language.lang.php');
	require_once (INCLUDES.'headers.inc.php');
	require_once (INCLUDES.'scrubber.inc.php');
	if ($_SESSION['prefsSEF'] == "Y") $sef = TRUE;
	else $sef = FALSE;

	if ($no_updates_needed) $_SESSION['currentVersion'] = 1;
	else $_SESSION['currentVersion'] = 0;

	if ((isset($_SESSION['prefsEval'])) && ($_SESSION['prefsEval'] == 1)) {
		
		if (!check_setup($prefix."evaluation",$database)) require_once (EVALS.'install_eval_db.eval.php');
		
		if (($section == "evaluation") && ($go == "scoresheet")) {
			
			/**
			 * For usability when accessing an electronic scoresheet, 
			 * make sure session timeout is at least 30 minutes to 
			 * give slowpoke judges sufficient time for evaluations 
			 * (typically should be no more than 15 minutes per entry).
			 */

			if ($session_expire_after < 30) {
				$session_expire_after = 30;
				$auto_logout_extension = TRUE;
			}
			
			else $session_expire_after = $session_expire_after;

		}

	}

	// ---------------------------- Data Integrity Checks ----------------------------

	// Perform data integrity check on users, brewer, and brewing tables at 24 hour intervals. Set alert flag.
	if ((isset($_SESSION['dataCheck'.$prefix_session])) && (isset($_SESSION['prefsAutoPurge']))) {
		if (($_SESSION['prefsAutoPurge'] == 1) && (!NHC) && ($today > ($_SESSION['dataCheck'.$prefix_session] + 86400))) data_integrity_check();
	}

	// Check to see if DB is utf8mb4 character set and utf8mb4_unicode_ci collation
	// If not, convert DB and all tables

	$alert_flag_character = FALSE;

	if (!isset($_SESSION['characterSet'])) {

		$query_character_check = "SHOW VARIABLES LIKE 'character_set_database'";
		$character_check = mysqli_query($connection,$query_character_check) or die (mysqli_error($connection));
		$row_character_check = mysqli_fetch_assoc($character_check);

		// If not usf8mb4, convert DB and all tables
		if ($row_character_check['Value'] != "utf8mb4") {

			$sql = sprintf("ALTER DATABASE `%s` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;",$database);
			mysqli_select_db($connection,$database);
			mysqli_real_escape_string($connection,$sql);
			$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

			foreach ($db_table_array as $table) {

				$sql = sprintf("ALTER TABLE `%s` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;",$table);
				mysqli_select_db($connection,$database);
				mysqli_real_escape_string($connection,$sql);
				$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

			}

			$alert_flag_character = TRUE;

		}
	}

	// Set session variable
	if (!$alert_flag_character) {
		$_SESSION['characterSet'] = "utf8mb4";
	}

	$alert_flag_preferences = FALSE;
	// Check if preferences DB table is empty or does not have a row with id of 1. If so, add row with id of 1 with default preferences. Set alert flag.
	// Avoids breaking of UI

	if (!isset($_SESSION['preferencesSet'])) {

		$query_prefs_check = sprintf("SELECT id FROM %s ORDER BY id ASC LIMIT 1",$preferences_db_table);
		$prefs_check = mysqli_query($connection,$query_prefs_check) or die (mysqli_error($connection));
		$row_prefs_check = mysqli_fetch_assoc($prefs_check);
		$totalRows_prefs_check = mysqli_num_rows($prefs_check);

		if ($totalRows_prefs_check == 0) {

			$update_table = $prefix."preferences";
			$data = array(
				'id' => '1',
				'prefsTemp' => 'Fahrenheit',
				'prefsWeight1' => 'ounces',
				'prefsWeight2' => 'pounds',
				'prefsLiquid1' => 'ounces',
				'prefsLiquid2' => 'gallons',
				'prefsPaypal' => 'N',
				'prefsPaypalAccount' => NULL,
				'prefsPaypalIPN' => '0',
				'prefsCurrency' => '$',
				'prefsCash' => 'N',
				'prefsCheck' => 'N',
				'prefsCheckPayee' => NULL,
				'prefsTransFee' => 'Y',
				'prefsGoogleAccount' => '|',
				'prefsSponsors' => 'N',
				'prefsSponsorLogos' => 'N',
				'prefsSelectedStyles' => NULL,
				'prefsCompLogoSize' => '300',
				'prefsDisplayWinners' => 'Y',
				'prefsWinnerDelay' => '1616974200',
				'prefsWinnerMethod' => '0',
				'prefsDisplaySpecial' => 'J',
				'prefsBOSMead' => 'N',
				'prefsBOSCider' => 'N',
				'prefsEntryForm' => '5',
				'prefsRecordLimit' => '9999',
				'prefsRecordPaging' => '150',
				'prefsProEdition' => '0',
				'prefsTheme' => 'bruxellensis',
				'prefsDateFormat' => '1',
				'prefsContact' => 'Y',
				'prefsTimeZone' => '-7.001',
				'prefsEntryLimit' => NULL,
				'prefsTimeFormat' => '0',
				'prefsUserEntryLimit' => NULL,
				'prefsUserSubCatLimit' => NULL,
				'prefsUSCLEx' => NULL,
				'prefsUSCLExLimit' => NULL,
				'prefsPayToPrint' => 'N',
				'prefsHideRecipe' => 'Y',
				'prefsUseMods' => 'N',
				'prefsSEF' => 'N',
				'prefsSpecialCharLimit' => '200',
				'prefsStyleSet' => 'BJCP2021',
				'prefsAutoPurge' => '0',
				'prefsEntryLimitPaid' => NULL,
				'prefsEmailRegConfirm' => '0',
				'prefsEmailCC' => '1',
				'prefsShipping' => '1',
				'prefsDropOff' => '1',
				'prefsLanguage' => 'en-US',
				'prefsSpecific' => '1',
				'prefsShowBestBrewer' => '0',
				'prefsBestBrewerTitle' => NULL,
				'prefsFirstPlacePts' => '0',
				'prefsSecondPlacePts' => '0',
				'prefsThirdPlacePts' => '0',
				'prefsFourthPlacePts' => '0',
				'prefsHMPts' => '0',
				'prefsTieBreakRule1' => NULL,
				'prefsTieBreakRule2' => NULL,
				'prefsTieBreakRule3' => NULL,
				'prefsTieBreakRule4' => NULL,
				'prefsTieBreakRule5' => NULL,
				'prefsTieBreakRule6' => NULL,
				'prefsShowBestClub' => '0',
				'prefsBestClubTitle' => NULL,
				'prefsCAPTCHA' => '0',
				'prefsBestUseBOS' => '0',
				'prefsEval' => '1'
			);
			$result = $db_conn->insert ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$alert_flag_preferences = TRUE;

		}

		if ($row_prefs_check['id'] != "1") {

			$update_table = $prefix."preferences";
			$data = array('id' => 1);
			$db_conn->where ('id', $row_prefs_check['id']);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

		}

	}

	// If preferences OK (no alert flagged), set session variable
	if (!$alert_flag_preferences) {
		$_SESSION['preferencesSet'] = "1";
	}

	$alert_flag_contest_info = FALSE;
	
	// Check if contest_info DB table is empty or does not have a row with id of 1. If so, add row with id of 1 with dummy content. Set alert flag.
	if (!isset($_SESSION['compInfoSet'])) {

		$query_contest_info_check = sprintf("SELECT id FROM %s ORDER BY id ASC LIMIT 1",$contest_info_db_table);
		$contest_info_check = mysqli_query($connection,$query_contest_info_check) or die (mysqli_error($connection));
		$row_contest_info_check = mysqli_fetch_assoc($contest_info_check);
		$totalRows_contest_info_check = mysqli_num_rows($contest_info_check);

		if ($totalRows_contest_info_check == 0) {

			$update_table = $prefix."contest_info";
			$data = array(
				'id' => '1',
				'contestName' => 'Baseline Data Installation',
				'contestHost' => 'Baseline',
				'contestHostWebsite' => 'http://www.brewingcompetitions.com',
				'contestHostLocation' => 'Denver, CO',
				'contestRegistrationOpen' => '1438322400',
				'contestRegistrationDeadline' => '1483253940',
				'contestEntryOpen' => '1438322400',
				'contestEntryDeadline' => '1483253940',
				'contestJudgeOpen' => '1438322400',
				'contestJudgeDeadline' => '1483253940',
				'contestRules' => '<p>This competition is AHA sanctioned and open to any amateur homebrewer age 21 or older.</p><p>All mailed entries must <strong>received</strong> at the mailing location by the entry deadline - please allow for shipping time.</p><p>All entries will be picked up from drop-off locations the day of the entry deadline.</p><p>All entries must be handcrafted products, containing ingredients available to the general public, and made using private equipment by hobbyist brewers (i.e., no use of commercial facilities or Brew on Premises operations, supplies, etc.).</p><p>The competition organizers are not responsible for mis-categorized entries, mailed entries that are not received by the entry deadline, or entries that arrived damaged.</p><p>The competition organizers reserve the right to combine styles for judging and to restructure awards as needed depending upon the quantity and quality of entries.</p><p>Qualified judging of all entries is the primary goal of our event. Judges will evaluate and score each entry. The average of the scores will rank each entry in its category. Each flight will have at least one BJCP judge.</p><p>Brewers are not limited to one entry in each category but may only enter each subcategory once.</p><p>The competition committee reserves the right to combine categories based on number of entries. All possible effort will be made to combine similar styles. All brews in combined categories will be judged according to the style they were originally entered in.</p><p>The Best of Show judging will be determined by a Best of Show panel based on a second judging of the top winners.</p><p>Bottles will not be returned to entrants.</p>',
				'contestAwardsLocation' => '200 E Colfax Ave, Denver, CO 80203',
				'contestAwardsLocName' => 'Baseline Awards Location',
				'contestAwardsLocDate' => NULL,
				'contestAwardsLocTime' => '1483798980',
				'contestShippingOpen' => '1438322400',
				'contestShippingDeadline' => '1483253940',
				'contestEntryFee' => '8.00',
				'contestEntryFee2' => NULL,
				'contestEntryFeeDiscount' => 'N',
				'contestEntryFeeDiscountNum' => NULL,
				'contestDropoffOpen' => '1438322400',
				'contestBottles' => '<p>Each entry will consist of capped or corked bottles that are void of all identifying information, including labels and embossing. Printed caps are allowed, but must be blacked out completely.</p><p>12oz brown glass bottles are preferred; however, green and clear glass will be accepted. Swing top bottles will likewise be accepted as well as corked bottles.</p><p>Bottles will not be returned to contest entrants.</p><p>Completed entry forms and recipe sheets must be submitted with all entries, and can be printed directly from this website. Entry forms should be attached to bottle labels by the method specified on the bottle label.</p><p>Please fill out the entry forms completely. Be meticulous about noting any special ingredients that must be specified. Failure to note such ingredients may impact the judges\' scoring of your entry.</p><p>Brewers are not limited to one entry in each category but may only enter each subcategory once.</p>',
				'contestShippingAddress' => '200 E Colfax Ave, Denver, CO 80203',
				'contestShippingName' => 'Shipping Location',
				'contestAwards' => '<p>The awards ceremony will take place once judging is completed.</p><p>Places will be awarded to 1st, 2nd, and 3rd place in each category/table.</p><p>The 1st place entry in each category will advance to the Best of Show (BOS) round with a single, overall Best of Show beer selected.</p><p>Additional prizes may be awarded to those winners present at the awards ceremony at the discretion of the competition organizers.</p><p>Both score sheets and awards will be available for pick up that night after the ceremony concludes. Awards and score sheets not picked up will be mailed back to participants. Results will be posted to the competition web site after the ceremony concludes.</p>',
				'contestLogo' => NULL,
				'contestBOSAward' => NULL,
				'contestDropoffDeadline' => '1483253940',
				'contestEntryCap' => NULL,
				'contestEntryFeePassword' => NULL,
				'contestEntryFeePasswordNum' => NULL,
				'contestID' => '000000',
				'contestCircuit' => NULL,
				'contestVolunteers' => '<p>Volunteer information coming soon!</p>',
				'contestCheckInPassword' => NULL
			);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$alert_flag_contest_info = TRUE;

		}

		if ($row_contest_info_check['id'] != "1") {

			$update_table = $prefix."contest_info";
			$data = array('id' => 1);
			$db_conn->where ('id', $row_contest_info_check['id']);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

		}

	}

	// If comp info OK (no alert flagged), set session variable
	if (!$alert_flag_preferences) {
		$_SESSION['compInfoSet'] = "1";
	}

	// ---------------------------- Time Related Globals ----------------------------

	// Set timezone globals
	$timezone_prefs = get_timezone($_SESSION['prefsTimeZone']);
	date_default_timezone_set($timezone_prefs);
	$tz = date_default_timezone_get();

	// Check for Daylight Savings Time (DST) - if true, add one hour to the offset
	$bool = date("I");
	if ($bool == 1) $timezone_offset = number_format(($_SESSION['prefsTimeZone'] + 1.000),0);
	else $timezone_offset = number_format($_SESSION['prefsTimeZone'],0);

	//  ---------------------------- Load Theme ----------------------------

	if (!isset($_SESSION['prefsTheme'])) $theme = $css_url."default.min.css";
	else $theme = $css_url.$_SESSION['prefsTheme'].".min.css";

} // end if ($setup_success);
?>