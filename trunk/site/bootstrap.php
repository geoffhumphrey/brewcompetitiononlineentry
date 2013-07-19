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
//error_reporting(0);	// comment out to debug
error_reporting(E_ALL); // uncomment to debug 



// reCAPTCHA Public Key
$publickey = "6LdquuQSAAAAAC3rsksvtjRmR9yPFmflBF4OWNS7";

// Define Base URL
if (NHC) $base_url = "";
else $base_url = $base_url;

// Define Global Variables
$php_version = phpversion();
$nhc_landing_url = "https://www.brewingcompetition.com";

// Pre-flight Checks
function check_setup($tablename, $database) {
	require(CONFIG.'config.php');
	$query_log = "SELECT COUNT(*) AS count FROM information_schema.tables WHERE table_schema = '$database' AND table_name = '$tablename'";
	$log = mysql_query($query_log, $brewing) or die(mysql_error());
	$row_log = mysql_fetch_assoc($log);

    if ($row_log['count'] == 0) return FALSE;
	else return TRUE;

}

$setup_success = TRUE;

if ((!check_setup($prefix."mods",$database)) && (!check_setup($prefix."preferences",$database))) { 
	$setup_success = FALSE;
	$setup_relocate = "Location: ".$base_url."setup.php?section=step0";
	}
if ((!check_setup($prefix."mods",$database)) && (check_setup($prefix."preferences",$database))) { 
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


function version_check($version) {
	// Current version is 1.3.0.0, change version in system table if not
	// There are NO database structure or data updates for version 1.2.1.3
	// USE THIS FUNCTION ONLY IF THERE ARE *NOT* ANY DB TABLE OR DATA UPDATES
	// OTHERWISE, DEFINE/UPDATE THE VERSION VIA THE UPDATE PROCEDURE
	require(CONFIG.'config.php');
	
	if ($version != "1.3.0.0") {
		$updateSQL = sprintf("UPDATE %s SET version='%s', version_date='%s' WHERE id='%s'",$prefix."system","1.3.0.0","2013-08-31","1");
		mysql_select_db($database, $brewing);
		$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
	}
}
/*	
version_check($version);
*/

// Global Includes and DB Calls
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'authentication_nav.inc.php');  session_start(); 
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php');
require(DB.'common.db.php');


// If session variables have changed, regenerate the session
if ((isset($_SESSION['prefix'])) && ($_SESSION['prefix'] != $prefix)) {
	session_unset();
	session_destroy();
	session_write_close();
	setcookie(session_name(),'',0,'/');
	session_regenerate_id(true);
	header("Location: index.php");
	exit;
}

/*

// May not be needed - must check
if (empty($_SESSION['prefs'])) { 
	if (NHC) $location = "index.php";
	else $location = $base_url;
	header(sprintf("Location: %s",$location));
}

if ((isset($_SESSION['loginUsername'])) && (empty($_SESSION['user_info'.$prefix_session]))) { 

	if (($_SESSION['userLevel'] <= 1) && ($section == "list") && ($msg == "2")) $location = "index.php?section=list";
	elseif (($_SESSION['userLevel'] <= 1) && ($msg == "default")) $location = "index.php?section=admin";
	else $location = build_public_url("list","default","default",$sef,$base_url);
	
	header(sprintf("Location: %s",$location));
}
*/


require(DB.'brewer.db.php');
require(DB.'entries.db.php');
require(INCLUDES.'headers.inc.php');
require(INCLUDES.'constants.inc.php');

if ($_SESSION['prefsSEF'] == "Y") $sef = "true";
else $sef = "false";

// Data Integrity Checks
// Perform data integrity check on users, brewer, and brewing tables at 24 hour intervals
if ((!NHC) && ($today > ($_SESSION['dataCheck'] + 86400))) data_integrity_check();

// check to see if all judging numbers have been generated. If not, generate
if ((!check_judging_numbers()) && (!NHC)) header("Location: includes/process.inc.php?action=generate_judging_numbers&go=hidden");

// Automatically purge all unconfirmed entries
purge_entries("unconfirmed", 1);

// Purge entries without defined special ingredients designated to particular styles that require them
purge_entries("special", 1);

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
if($msie_key !== false) { // you found MSIE browser
    $msie_version_key = $msie_key + 1;
    $msie_version = intval($ua_array[$msie_version_key]);
    if ($msie_version <= 7) {
        $ua = TRUE;
    }
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