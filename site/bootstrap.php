<?php

/**
 * Module:      bootstrap.php 
 * Description: This module houses specific global variables and various pre-flight checks.
 *              
 * 
 */

/*

reCaptcha Global Keys for BCOE&M

--- Version 1 ---
Public Key: 	6LdquuQSAAAAAC3rsksvtjRmR9yPFmflBF4OWNS7
Private Key:	6LdquuQSAAAAAHkf3dDRqZckRb_RIjrkofxE8Knd 

Future Version
--- No CAPTCHA (Version 2) ---
Public Key:		6LdUsBATAAAAAEJYbnqmygjGK-S6CHCoGcLALg5W
Private Key:	6LdUsBATAAAAAMPhk5yRSmY5BMXlBgcTjiLjiyPb

http://www.codediesel.com/security/integrating-googles-new-nocaptcha-recaptcha-in-php/

*/

// ---------------------------- reCAPTCHA Public Key ----------------------------

$publickey = "6LdquuQSAAAAAC3rsksvtjRmR9yPFmflBF4OWNS7";

// ---------------------------- Base URL ----------------------------

if (NHC) $base_url = ""; 
else $base_url = $base_url;

// ---------------------------- Globals ----------------------------

$php_version = phpversion();
$nhc_landing_url = "https://www.brewingcompetition.com";

// ---------------------------- Preflight Checks ----------------------------

require(LIB.'preflight.lib.php');

// ---------------------------- Run Scripts ----------------------------

// If all setup or update has taken place, run normally
if ($setup_success) {
	
	// ---------------------------- Define URL Variables ----------------------------
	
	require(INCLUDES.'url_variables.inc.php');
	
	// ---------------------------- Check if Valid Section -----------------------------
	
	$section_array = array("default","rules","entry","volunteers","contact","pay","list","admin","login","logout","check","brewer","user","setup","judge","beerxml","register","sponsors","past_winners","brew","step1","step2","step3","step4","step5","step6","step7","step8","update","confirm","delete","table_cards","participant_summary","loc","sorting","output_styles","map","driving","scores","entries","participants","emails","assignments","bos-mat","dropoff","summary","inventory","pullsheets","results","sorting","staff","styles","promo","table-cards","testing","notes","qr","shipping-label");
	
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
	
	// ---------------------------- Load Required Scripts ----------------------------
	
	if (SINGLE) require(SSO.'sso.inc.php');
	
	else {

		require(INCLUDES.'authentication_nav.inc.php'); 
		require(LIB.'common.lib.php');
		require(INCLUDES.'db_tables.inc.php');
		require(LIB.'help.lib.php');
		require(DB.'common.db.php');
		require(DB.'brewer.db.php');
		require(DB.'entries.db.php');
		require(INCLUDES.'constants.inc.php');
		require(LANG.'language.lang.php');
		require(INCLUDES.'headers.inc.php');
		require(INCLUDES.'scrubber.inc.php');
	
	}
		
	if ($_SESSION['prefsSEF'] == "Y") $sef = "true";
	else $sef = "false";
	
	// ---------------------------- Data Integrity Checks ---------------------------- 
	
	// Perform data integrity check on users, brewer, and brewing tables at 24 hour intervals
	if ((isset($_SESSION['dataCheck'.$prefix_session])) && ((isset($_SESSION['prefsAutoPurge'])) && ($_SESSION['prefsAutoPurge'] == 1))) {
		if ((!NHC) && ($today > ($_SESSION['dataCheck'.$prefix_session] + 86400))) data_integrity_check();
	}
	
	// check to see if all judging numbers have been generated. If not, generate
	if ((!check_judging_numbers()) && (!NHC)) header("Location: includes/process.inc.php?action=generate_judging_numbers&go=hidden");
	
	// Check if judging flights are up-to-date
	//if (!check_judging_flights()) $check_judging_flights = TRUE;
	//else $check_judging_flights = FALSE;
	
	$check_judging_flights = FALSE;
	
	//  ---------------------------- Time Related Globals ---------------------------- 
	
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
	$theme = $base_url."css/".$_SESSION['prefsTheme'].".min.css";
	
} // end if ($setup_success);
?>