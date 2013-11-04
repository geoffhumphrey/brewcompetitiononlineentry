<?php
/**
 * Module:      bootstrap.php 
 * Description: This module houses specific global variables and various pre-flight checks.
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

// Define Base URL
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
	
	if ($row_version['version'] != "1.3.0.2") { 
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

//echo $setup_success;
//exit;

// If all setup or update has taken place, run normally
if ($setup_success) {


function version_check($version) {
	// Current version is 1.3.0.2, change version in system table if not
	// There are NO database structure or data updates for version 1.3.0.2
	// USE THIS FUNCTION ONLY IF THERE ARE *NOT* ANY DB TABLE OR DATA UPDATES
	// OTHERWISE, DEFINE/UPDATE THE VERSION VIA THE UPDATE PROCEDURE
	require(CONFIG.'config.php');
	
	if ($version != "1.3.0.2") {
		$updateSQL = sprintf("UPDATE %s SET version='%s', version_date='%s' WHERE id='%s'",$prefix."system","1.3.0.2","2013-09-13","1");
		mysql_select_db($database, $brewing);
		$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
	}
}
	
version_check($version);


// Global Includes and DB Calls
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'authentication_nav.inc.php');  session_start(); 
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php');
require(DB.'common.db.php');
require(INCLUDES.'scrubber.inc.php');


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
	elseif (($_SESSION['userLevel'] <= 1) && ($msg == "default