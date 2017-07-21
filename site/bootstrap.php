<?php

/**
 * Module:      bootstrap.php 
 * Description: This module houses specific global variables and various pre-flight checks.
 *              
 * 
 */

/*

*/

// ---------------------------- reCAPTCHA Public Key ----------------------------

$publickey = "6LdUsBATAAAAAEJYbnqmygjGK-S6CHCoGcLALg5W";

// ---------------------------- Base URL ----------------------------

if (NHC) $base_url = ""; 
else $base_url = $base_url;

// ---------------------------- Globals ----------------------------

$php_version = phpversion();
$nhc_landing_url = "https://www.brewingcompetition.com";

// ---------------------------- Preflight Checks ----------------------------

require_once (LIB.'preflight.lib.php');

// ---------------------------- Run Scripts ----------------------------

// If all setup or update has taken place, run normally
if ($setup_success) {
	
	// ---------------------------- Define URL Variables ----------------------------
	
	require_once (INCLUDES.'url_variables.inc.php');
	
	// ---------------------------- Check if Valid Section -----------------------------
	
	$section_array = array("default","rules","entry","volunteers","contact","pay","list","admin","login","logout","check","brewer","user","setup","judge","beerxml","register","sponsors","past_winners","brew","step1","step2","step3","step4","step5","step6","step7","step8","update","confirm","delete","table_cards","participant_summary","loc","sorting","output_styles","map","driving","scores","entries","participants","emails","assignments","bos-mat","dropoff","summary","inventory","pullsheets","results","sorting","staff","styles","promo","table-cards","testing","notes","qr","shipping-label","particpant-entries","qr");
	
	// Redirect to 404 if section not the array	
	if (!in_array($section,$section_array)) { 
		header(sprintf("Location: %s",$base_url."404.php"));
		exit;
		}
		
	// ---------------------------- QR Redirect ----------------------------
	
	// Redirect to QR Code Check-In page if necessary	
	if ($section == "qr") {
		header(sprintf("Location: %s", $base_url."qr.php"));	
		exit;
	}
	
	// ---------------------------- IE Browser Check ---------------------------- 
	
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
	
	// ---------------------------- Load require_onced Scripts ----------------------------
	
	// **PREVENTING SESSION HIJACKING**
	// Prevents javascript XSS attacks aimed to steal the session ID
	ini_set('session.cookie_httponly', 1);

	// **PREVENTING SESSION FIXATION**
	// Session ID cannot be passed through URLs
	ini_set('session.use_only_cookies', 1);
	
	// Uses a secure connection (HTTPS) if possible
	ini_set('session.cookie_secure', 1);
	
	if (SINGLE) require_once(SSO.'sso.inc.php');
		
	require_once (LIB.'common.lib.php'); // OK
	require_once (INCLUDES.'db_tables.inc.php'); // OK
	require_once (LIB.'help.lib.php'); // OK
	require_once (DB.'common.db.php');
	require_once (DB.'brewer.db.php');
	require_once (DB.'entries.db.php');
	require_once (INCLUDES.'constants.inc.php');
	require_once (LANG.'language.lang.php');
	require_once (INCLUDES.'headers.inc.php');
	require_once (INCLUDES.'scrubber.inc.php');
	if ($_SESSION['prefsSEF'] == "Y") $sef = "true";
	else $sef = "false";
	
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
			
			$sql = sprintf("INSERT INTO `%s` (`id`, `prefsTemp`, `prefsWeight1`, `prefsWeight2`, `prefsLiquid1`, `prefsLiquid2`, `prefsPaypal`, `prefsPaypalAccount`, `prefsCurrency`, `prefsCash`, `prefsCheck`, `prefsCheckPayee`, `prefsTransFee`, `prefsGoogle`, `prefsGoogleAccount`, `prefsSponsors`, `prefsSponsorLogos`, `prefsSponsorLogoSize`, `prefsCompLogoSize`, `prefsDisplayWinners`, `prefsWinnerDelay`, `prefsWinnerMethod`, `prefsDisplaySpecial`, `prefsBOSMead`, `prefsBOSCider`, `prefsEntryForm`, `prefsRecordLimit`, `prefsRecordPaging`, `prefsProEdition`, `prefsTheme`, `prefsDateFormat`, `prefsContact`, `prefsTimeZone`, `prefsEntryLimit`, `prefsTimeFormat`, `prefsUserEntryLimit`, `prefsUserSubCatLimit`, `prefsUSCLEx`, `prefsUSCLExLimit`, `prefsPayToPrint`, `prefsHideRecipe`, `prefsUseMods`, `prefsSEF`, `prefsSpecialCharLimit`, `prefsStyleSet`, `prefsAutoPurge`, `prefsEntryLimitPaid`, `prefsEmailRegConfirm`, `prefsLanguage`, `prefsSpecific`, `prefsDropOff`, `prefsShipping`,`prefsPaypalIPN`) VALUES
	(1, 'Fahrenheit', 'ounces', 'pounds', 'ounces', 'gallons', 'Y', 'user.baseline@brewcompetition.com', '$', 'N', 'N', NULL, 'Y', 'N', NULL, 'Y', 'Y', '250', '300', 'N', 8, 0, NULL, 'N', 'N', 'B', 9999, 150, NULL, 'default', '1', 'Y', '-7.000', NULL, 0, NULL, NULL, NULL, NULL, 'N', 'Y', 'N', 'N', 150, 'BJCP2015', 0, NULL, NULL, 'English', 1, 1, 1, 0);",$preferences_db_table);
			mysqli_select_db($connection,$database);
			mysqli_real_escape_string($connection,$sql);
			$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
			
			$alert_flag_preferences = TRUE;
			
		}
		
		if ($row_prefs_check['id'] != "1") {
			
			$sql = sprintf("UPDATE %s SET id = '1' WHERE id = '%s'",$preferences_db_table,$row_prefs_check['id']);
			mysqli_select_db($connection,$database);
			mysqli_real_escape_string($connection,$sql);
			$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
			
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
			
			$sql = sprintf("INSERT INTO `%s` (`id`, `contestName`, `contestHost`, `contestHostWebsite`, `contestHostLocation`, `contestRegistrationOpen`, `contestRegistrationDeadline`, `contestEntryOpen`, `contestEntryDeadline`, `contestJudgeOpen`, `contestJudgeDeadline`, `contestRules`, `contestAwardsLocation`, `contestAwardsLocName`, `contestAwardsLocDate`, `contestAwardsLocTime`, `contestShippingOpen`, `contestShippingDeadline`, `contestEntryFee`, `contestEntryFee2`, `contestEntryFeeDiscount`, `contestEntryFeeDiscountNum`, `contestDropoffOpen`, `contestBottles`, `contestShippingAddress`, `contestShippingName`, `contestAwards`, `contestLogo`, `contestBOSAward`, `contestDropoffDeadline`, `contestEntryCap`, `contestEntryFeePassword`, `contestEntryFeePasswordNum`, `contestID`, `contestCircuit`, `contestVolunteers`, `contestCheckInPassword`) VALUES
	(1, 'Baseline Data Installation', 'Baseline', 'http://www.brewcompetition.com', 'Denver, CO', '1438322400', '1483253940', '1438322400', '1483253940', '1438322400', '1483253940', '<p>This competition is AHA sanctioned and open to any amateur homebrewer age 21 or older.</p>\r\n<p>All mailed entries must <strong>received</strong> at the mailing location by the entry deadline - please allow for shipping time.</p>\r\n<p>All entries will be picked up from drop-off locations the day of the entry deadline.</p>\r\n<p>All entries must be handcrafted products, containing ingredients available to the general public, and made using private equipment by hobbyist brewers (i.e., no use of commercial facilities or Brew on Premises operations, supplies, etc.).</p>\r\n<p>The competition organizers are not responsible for mis-categorized entries, mailed entries that are not received by the entry deadline, or entries that arrived damaged.</p>\r\n<p>The competition organizers reserve the right to combine styles for judging and to restructure awards as needed depending upon the quantity and quality of entries.</p>\r\n<p>Qualified judging of all entries is the primary goal of our event. Judges will evaluate and score each entry. The average of the scores will rank each entry in its category. Each flight will have at least one BJCP judge.</p>\r\n<p>Brewers are not limited to one entry in each category but may only enter each subcategory once.</p>\r\n<p>The competition committee reserves the right to combine categories based on number of entries. All possible effort will be made to combine similar styles. All brews in combined categories will be judged according to the style they were originally entered in.</p>\r\n<p>The Best of Show judging will be determined by a Best of Show panel based on a second judging of the top winners.</p>\r\n<p>Bottles will not be returned to entrants.</p>', '200 E Colfax Ave, Denver, CO 80203', 'Baseline Awards Location', NULL, '1483798980', '1438322400', '1483253940', 8, NULL, 'N', NULL, '1438322400', '<p>Each entry will consist of 12 to 22 ounce capped bottles or corked bottles that are void of all identifying information, including labels and embossing. Printed caps are allowed, but must be blacked out completely.</p>\r\n<p>12oz brown glass bottles are preferred; however, green and clear glass will be accepted. Swing top bottles will likewise be accepted as well as corked bottles.</p>\r\n<p>Bottles will not be returned to contest entrants.</p>\r\n<p>Completed entry forms and recipe sheets must be submitted with all entries, and can be printed directly from this website. Entry forms should be attached to bottle labels by the method specified on the bottle label.</p>\r\n<p>Please fill out the entry forms completely. Be meticulous about noting any special ingredients that must be specified. Failure to note such ingredients may impact the judges'' scoring of your entry.</p>\r\n<p>Brewers are not limited to one entry in each category but may only enter each subcategory once.</p>', '200 E Colfax Ave, Denver, CO 80203', 'Shipping Location', '<p>The awards ceremony will take place once judging is completed.</p>\r\n<p>Places will be awarded to 1st, 2nd, and 3rd place in each category/table.</p>\r\n<p>The 1st place entry in each category will advance to the Best of Show (BOS) round with a single, overall Best of Show beer selected.</p>\r\n<p>Additional prizes may be awarded to those winners present at the awards ceremony at the discretion of the competition organizers.</p>\r\n<p>Both score sheets and awards will be available for pick up that night after the ceremony concludes. Awards and score sheets not picked up will be mailed back to participants. Results will be posted to the competition web site after the ceremony concludes.</p>', NULL, NULL, '1483253940', NULL, NULL, NULL, '000000', NULL, '<p>Volunteer information coming soon!</p>', NULL);",$contest_info_db_table);
			mysqli_select_db($connection,$database);
			mysqli_real_escape_string($connection,$sql);
			$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
			
			$alert_flag_contest_info = TRUE;
			
		}
		
		if ($row_contest_info_check['id'] != "1") {
			
			$sql = sprintf("UPDATE %s SET id = '1' WHERE id = '%s'",$contest_info_db_table,$row_contest_info_check['id']);
			mysqli_select_db($connection,$database);
			mysqli_real_escape_string($connection,$sql);
			$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
			
		}
		
	}
	
	// If comp info OK (no alert flagged), set session variable
	if (!$alert_flag_preferences) {
		$_SESSION['compInfoSet'] = "1";
	}
	
	/*
	echo $_SESSION['characterSet']."<br>";
	echo $_SESSION['compInfoSet']."<br>";
	echo $_SESSION['preferencesSet'];
	exit;
	*/
	
	/*
	The following was reported to cause a "redirect loop failure" - commenting out in lieu of another solution
	See https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues/674
	Is not necessary as there is now a judging number check upon adding and editing entries
	Therefore, deprecated. Code will be removed in a future release after review.
	
	// Check to see if all judging numbers have been generated. If not, generate.
	if ((!check_judging_numbers()) && (!NHC)) header("Location: includes/process.inc.php?action=generate_judging_numbers&go=hidden");
	
	// Check if judging flights are up-to-date
	if (!check_judging_flights()) $check_judging_flights = TRUE;
	else $check_judging_flights = FALSE;
	$check_judging_flights = FALSE;
	
	*/
	
	// ---------------------------- Time Related Globals ---------------------------- 
	
	// Set timezone globals
	$timezone_prefs = get_timezone($_SESSION['prefsTimeZone']);
	date_default_timezone_set($timezone_prefs);
	$tz = date_default_timezone_get();
	
	// Check for Daylight Savings Time (DST) - if true, add one hour to the offset
	$bool = date("I"); 
	if ($bool == 1) $timezone_offset = number_format(($_SESSION['prefsTimeZone'] + 1.000),0); 
	else $timezone_offset = number_format($_SESSION['prefsTimeZone'],0);
	
	/*
	// Check for Firefox (printing issues persist with Firefox)
	// Deprecated as of v2.0.0
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE) $fx = TRUE;
	else $fx = FALSE;
	*/
	
	//  ---------------------------- Load Theme ---------------------------- 
	
	if (!isset($_SESSION['prefsTheme'])) $theme = $base_url."css/default.min.css";
	else $theme = $base_url."css/".$_SESSION['prefsTheme'].".min.css";
	
} // end if ($setup_success);
?>