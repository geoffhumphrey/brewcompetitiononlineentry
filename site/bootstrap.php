<?php
/**
 * Module:      bootstrap.php 
 * Description: This module houses specific global variables and various pre-flight checks.
 *              
 * 
 */

/*

reCaptcha Global Key for BCOE&M

Public Key: 	6LdquuQSAAAAAC3rsksvtjRmR9yPFmflBF4OWNS7
Private Key:	6LdquuQSAAAAAHkf3dDRqZckRb_RIjrkofxE8Knd 

*/

//Error reporting
error_reporting(0);	// comment out to debug
//error_reporting(E_ALL); // uncomment to debug 

// reCAPTCHA Public Key
$publickey = "6LdquuQSAAAAAC3rsksvtjRmR9yPFmflBF4OWNS7";

// Define Base URL - may not need
if (NHC) $base_url = ""; 
else $base_url = $base_url;

// Define Global Variables
$php_version = phpversion();
$nhc_landing_url = "https://www.brewingcompetition.com";

// Pre-flight Checks
function check_setup($tablename, $database) {
	
	require(CONFIG.'config.php');	
	mysql_select_db($database, $brewing);
	
	$query_log = "SELECT COUNT(*) AS count FROM information_schema.tables WHERE table_schema = '$database' AND table_name = '$tablename'";
	$log = mysql_query($query_log, $brewing) or die(mysql_error());
	$row_log = mysql_fetch_assoc($log);

    if ($row_log['count'] == 0) return FALSE;
	else return TRUE;

}

$setup_success = TRUE;

// The following line will need to change with future conversions
if ((!check_setup($prefix."mods",$database)) && (!check_setup($prefix."preferences",$database))) { 

	$setup_success = FALSE;
	$setup_relocate = "Location: ".$base_url."setup.php?section=step0";
	
}
	
if (NHC) {

	mysql_select_db($database, $brewing);
	$query_version = sprintf("SELECT version FROM %s WHERE id='1'",$prefix."system");
	$version = mysql_query($query_version, $brewing) or die(mysql_error());
	$row_version = mysql_fetch_assoc($version);
	
	//echo $row_version['version'];
	
	if ($row_version['version'] != "1.3.0.3") { 
		$setup_success = FALSE;
		$setup_relocate = "Location: ".$base_url."update.php";
	}
	
}

if ((!NHC) && (!check_setup($prefix."mods",$database)) && (check_setup($prefix."preferences",$database))) {
	
	$setup_success = FALSE;
	$setup_relocate = "Location: ".$base_url."update.php";
	
}
	
elseif (MAINT) { 

	$setup_success = FALSE;
	$setup_relocate = "Location: ".$base_url."maintenance.php";
	
}

if (!$setup_success) {
	
	header ($setup_relocate);
	exit;
	
}

// If all setup or update has taken place, run normally
if ($setup_success) {

	// Global Library, Includes and DB Calls
	require(LIB.'common.lib.php');
	require(INCLUDES.'authentication_nav.inc.php'); 
	require(INCLUDES.'url_variables.inc.php');
	require(INCLUDES.'db_tables.inc.php');
	require(DB.'common.db.php');
	require(DB.'brewer.db.php');
	require(DB.'entries.db.php');
	require(INCLUDES.'headers.inc.php');
	require(INCLUDES.'constants.inc.php');
	require(INCLUDES.'scrubber.inc.php');
	
	if ($_SESSION['prefsSEF'] == "Y") $sef = "true";
	else $sef = "false";
	
	// Data Integrity Checks
	// Perform data integrity check on users, brewer, and brewing tables at 24 hour intervals
	if ((!NHC) && ($today > ($_SESSION['dataCheck'.$prefix_session] + 86400))) data_integrity_check();
	
	// check to see if all judging numbers have been generated. If not, generate
	if ((!check_judging_numbers()) && (!NHC)) header("Location: includes/process.inc.php?action=generate_judging_numbers&go=hidden");
	
	// Automatically purge all unconfirmed entries
	purge_entries("unconfirmed", 1);
	
	// Purge entries without defined special ingredients designated to particular styles that require them
	purge_entries("special", 1);
	
	// Check if judging flights are up-to-date
	if (!check_judging_flights()) $check_judging_flights = TRUE;
	else $check_judging_flights = FALSE;
	
	// Set timezone globals
	$timezone_prefs = get_timezone($_SESSION['prefsTimeZone']);
	date_default_timezone_set($timezone_prefs);
	$tz = date_default_timezone_get();
	
	// Check for Daylight Savings Time (DST) - if true, add one hour to the offset
	$bool = date("I"); if ($bool == 1) $timezone_offset = number_format(($_SESSION['prefsTimeZone'] + 1.000),0); 
	else $timezone_offset = number_format($_SESSION['prefsTimeZone'],0);
	
	// Check for IE and redirect if not using a version beyond 7
	$ua_array = explode(' ', $_SERVER['HTTP_USER_AGENT']);
	$msie_key = array_search('MSIE', $ua_array);
	$ua = FALSE;
	if($msie_key !== false) { // you found MSIE browser
		$msie_version_key = $msie_key + 1;
		$msie_version = intval($ua_array[$msie_version_key]);
		if ($msie_version <= 7) $ua = TRUE;
	}
	
	if (NHC) {
		if (($registration_open == 1) && (isset($_SESSION['loginUsername']))) {
			// compare region prefix to the actual region that the user is registered to
			// if they do not match, destroy the session - saves confusion and cheating
			
			if ($_SESSION['userLevel'] == 2) {
			$query_check_region = sprintf("SELECT email,regionPrefix FROM nhcentrant WHERE email='%s'", $_SESSION['loginUsername']);
			$check_region = mysql_query($query_check_region, $brewing) or die(mysql_error());
			$row_check_region = mysql_fetch_assoc($check_region);
			
			if (($row_check_region['regionPrefix'] != $prefix) && ($_SESSION['loginUsername'] != "geoff@zkdigital.com") && ($_SESSION['loginUsername'] != "janis@brewersassociation.org")) session_destroy();
			}			
		}
		// ONLY for NHC application
		// Check to see if SSL is in place and redirect to non SSL instance if not on pay screens
		if ($section != "pay") {
			$https = ((!empty($_SERVER['HTTPS'])) && ($_SERVER['HTTPS'] != 'off')) ? true : false;
			if ($https)  {
				$location = "http://www.brewingcompetition.com".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
				header("Location: $location");
				exit;
			}
		}
	}
} // end if ($setup_success);
?>