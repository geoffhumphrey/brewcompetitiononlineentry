<?php 
/*
 * Module:      process.inc.php
 * Description: This module does all the heavy lifting for any DB updates; new entries,
 *              new users, organization, etc.
 */
 
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', '1');

require('../paths.php');
require(INCLUDES.'url_variables.inc.php');


if ($section != "setup")  { 
	require(DB.'common.db.php');
	require(INCLUDES.'date_time.inc.php');
	
	// Set timezone globals for the site
	$timezone_prefs = get_timezone($row_prefs['prefsTimeZone']);
	date_default_timezone_set($timezone_prefs);
	$tz = date_default_timezone_get();
	
	// Check for Daylight Savings Time (DST) - if true, add one hour to the offset
	$bool = date("I"); if ($bool == 1) $timezone_offset = number_format(($row_prefs['prefsTimeZone'] + 1.000),0); 
	else $timezone_offset = number_format($row_prefs['prefsTimeZone'],0);
	
	
}

require(INCLUDES.'scrubber.inc.php');

function check_http($input) {
	if ($input != "") {
			if (strstr($input,"http://")) return $input;
			if (strstr($input,"https://")) return $input;
			if ((!strstr($input, "http://")) || (!strstr($input, "https://"))) return "http://".$input;		   
		}
}

$archive_db_table = $prefix."archive";
$brewer_db_table = $prefix."brewer";
$brewing_db_table = $prefix."brewing";
$contacts_db_table = $prefix."contacts";
$contest_info_db_table = $prefix."contest_info";
$countries_db_table = $prefix."countries";
$drop_off_db_table = $prefix."drop_off";
$judging_assignments_db_table = $prefix."judging_assignments";
$judging_flights_db_table = $prefix."judging_flights";
$judging_locations_db_table = $prefix."judging_locations";
$judging_preferences_db_table = $prefix."judging_preferences";
$judging_scores_db_table = $prefix."judging_scores";
$judging_scores_bos_db_table = $prefix."judging_scores_bos";
$judging_tables_db_table = $prefix."judging_tables";
$preferences_db_table = $prefix."preferences";
$special_best_data_db_table = $prefix."special_best_data";
$special_best_info_db_table = $prefix."special_best_info";
$sponsors_db_table = $prefix."sponsors";
$styles_db_table = $prefix."styles";
$style_types_db_table = $prefix."style_types";
$themes_db_table = $prefix."themes";
$users_db_table = $prefix."users";

if (($section == "setup") && (($dbTable == $contest_info_db_table) || ($dbTable == $drop_off_db_table) || ($dbTable == $judging_locations_db_table) || ($dbTable == $styles_db_table) || ($dbTable == $judging_preferences_db_table) || ($dbTable == $brewer_db_table) || ($dbTable == $preferences_db_table))) {
	require(DB.'common.db.php');
	require(INCLUDES.'date_time.inc.php');
	
	// Set timezone globals for the site
	$timezone_prefs = get_timezone($row_prefs['prefsTimeZone']);
	date_default_timezone_set($timezone_prefs);
	$tz = date_default_timezone_get();
	
	// Check for Daylight Savings Time (DST) - if true, add one hour to the offset
	$bool = date("I"); if ($bool == 1) $timezone_offset = number_format(($row_prefs['prefsTimeZone'] + 1.000),0); 
	else $timezone_offset = number_format($row_prefs['prefsTimeZone'],0);
	
}

// --------------------------- Various Functions -------------------------------- //

function generate_judging_num($style_cat_num) {
	// Generate the Judging Number each entry 
	require(CONFIG.'config.php');
	require(INCLUDES.'url_variables.inc.php');
	mysql_select_db($database, $brewing);
	$query_brewing_styles = sprintf("SELECT brewJudgingNumber FROM %s WHERE brewCategory='%s' ORDER BY brewJudgingNumber DESC LIMIT 1", $prefix."brewing", $style_cat_num);
	$brewing_styles = mysql_query($query_brewing_styles, $brewing) or die(mysql_error());
	$row_brewing_styles = mysql_fetch_assoc($brewing_styles);
	$totalRows_brewing_styles = mysql_num_rows($brewing_styles);
	if (($totalRows_brewing_styles == 0) || ($row_brewing_styles['brewJudgingNumber'] == "")) $return = $style_cat_num."001";
	else $return = $row_brewing_styles['brewJudgingNumber'] + 1;
	return $return;
}

function ucwordspecific($str,$delimiter) {
	$delimiter_space = $delimiter." ";
	$output = str_replace($delimiter_space,$delimiter,ucwords(str_replace($delimiter,$delimiter_space,$str)));
	return $output;
}

function capitalize($string1) {
	require(INCLUDES.'scrubber.inc.php');
	$capitalize = strtr($string1,$html_remove);
	$capitalize = ucwords($capitalize);
	$capitalize = ucwordspecific($capitalize,"-");
	$capitalize = ucwordspecific($capitalize,".");
	$capitalize = ucwordspecific($capitalize,"(");
	$capitalize = ucwordspecific($capitalize,")");
	$capitalize = strtr($capitalize,$html_string);
	return $capitalize;
}

if ($action != "purge") {
	function relocate($referer,$page,$msg,$id) { 
		include(CONFIG."config.php");
	
		// Break URL into an array
		$parts = parse_url($referer);
		$referer = $parts['query'];	
		
		// Remove $msg=X from query string
		$pattern = array("/[0-9]/", "/&msg=/");
		$referer = preg_replace($pattern, "", $referer);
	
		// Remove $id=X from query string
		$pattern = array("/[0-9]/", "/&id=/");
		$referer = preg_replace($pattern, "", $referer);
		
		// Remove $pg=X from query string and add back in
		if ($page != "default") { 
			$pattern = array("/[0-9]/", "/&pg=/");
			$referer = str_replace($pattern,"",$referer);
			$referer .= "&pg=".$page; 
		}
		
		$pattern = array('\'', '"');
		$referer = str_replace($pattern,"",$referer);
		$referer = stripslashes($referer);	
		
		// Reconstruct the URL
		$reconstruct = $base_url."/index.php?".$referer;
		return $reconstruct;
	}

	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
	{
	  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;
	  require ('scrubber.inc.php');
	  switch ($theType) {
	  
		case "text":
		  $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
		  break;    
		case "long":
		case "int":
		  $theValue = ($theValue != "") ? intval($theValue) : "NULL";
		  break;
		case "double":
		  $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
		  break;
		case "date":
		  $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
		  break;
		case "defined":
		  $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
		  break;
		case "scrubbed":
		  $theValue = ($theValue != "") ? "'" . strtr($theValue, $html_string) . "'" : "NULL";
	  }
	  return $theValue;
	}
}
// Script Specific Variables

if (strpos($_POST['relocate'],"?") === false) { 
	$insertGoTo = $_POST['relocate']."?msg=1"; 
	$updateGoTo = $_POST['relocate']."?msg=2";
	$massUpdateGoTo = $_POST['relocate']."?msg=9";
}

else { 
	$insertGoTo = $_POST['relocate']."&msg=1";
	$updateGoTo = $_POST['relocate']."&msg=2";
	$massUpdateGoTo = $_POST['relocate']."&msg=9";
}

$deleteGoTo = $_SERVER['HTTP_REFERER']."&msg=5";

//echo $insertGoTo;
//echo $updateGoTo;
//echo $deleteGoTo;
//exit;
//session_start();

// --------------------------- Entries -------------------------------- //

if ($dbTable == $prefix."brewing")				include_once (PROCESS.'process_brewing.inc.php');

// --------------------------- Users ------------------------------- //

if ($dbTable == $prefix."users") 				include_once (PROCESS.'process_users.inc.php');

// --------------------------- Participant or Admin's Info ------------------------------- //

if ($dbTable == $prefix."brewer") 				include_once (PROCESS.'process_brewer.inc.php'); 
	
// --------------------------- General Contest Info ------------------------------- // 

if ($dbTable == $prefix."contest_info") 		include_once (PROCESS.'process_comp_info.inc.php');

// --------------------------- Preferences ------------------------------- //

if ($dbTable == $prefix."preferences") 			include_once (PROCESS.'process_prefs.inc.php');

// --------------------------- Sponsors ------------------------------- //

if ($dbTable == $prefix."sponsors") 			include_once (PROCESS.'process_sponsors.inc.php');

// --------------------------- Judging Locations ------------------------------- //

if ($dbTable == $prefix."judging_locations") 	include_once (PROCESS.'process_judging_locations.inc.php');

// --------------------------- Drop-off Locations ------------------------------- //

if ($dbTable == $prefix."drop_off") 			include_once (PROCESS.'process_drop_off.inc.php');

// --------------------------- Styles --------------------------- //

if ($dbTable == $prefix."styles") 				include_once (PROCESS.'process_styles.inc.php');

// --------------------------- If Adding a Contact (Non-setup) --------------------------- //

if ($dbTable == $prefix."contacts")				include_once (PROCESS.'process_contacts.inc.php');

// --------------------------- If Editing Judging Preferences ------------------------------- //

if ($dbTable == $prefix."judging_preferences") 	include_once (PROCESS.'process_judging_preferences.inc.php');

// --------------------------- Tables and Associated Styles ------------------------------- //

if ($dbTable == $prefix."judging_tables")		include_once (PROCESS.'process_judging_tables.inc.php'); 

// --------------------------- Flights ------------------------------- //

if ($dbTable == $prefix."judging_flights") 		include_once (PROCESS.'process_judging_flights.inc.php'); 

// --------------------------- Judging Assignments ------------------------------- //

if ($dbTable == $prefix."judging_assignments") 	include_once (PROCESS.'process_judging_assignments.inc.php');

// --------------------------- Scores ------------------------------- //

if ($dbTable == $prefix."judging_scores") 		include_once (PROCESS.'process_judging_scores.inc.php');

// --------------------------- BOS Scores ------------------------------- //

if ($dbTable == $prefix."judging_scores_bos") 	include_once (PROCESS.'process_judging_scores_bos.inc.php');

// --------------------------- Style Types ------------------------------- //

if ($dbTable == $prefix."style_types") 			include_once (PROCESS.'process_style_types.inc.php');

// --------------------------- Custom Winner Category Info ------------------------------- //

if ($dbTable == $prefix."special_best_info") 	include_once (PROCESS.'process_special_best_info.inc.php');

// --------------------------- Custom Winner Category Entries ------------------------------- //

if ($dbTable == $prefix."special_best_data") 	include_once (PROCESS.'process_special_best_data.inc.php');

// --------------------------- Various Actions ------------------------------- //

if ($action == "delete")				include_once (PROCESS.'process_delete.inc.php');

if ($action == "beerxml")				include_once (PROCESS.'process_beerxml.inc.php');

if ($action == "purge") {
	require(INCLUDES.'functions.inc.php');
	purge_entries("unconfirmed", 0);
	purge_entries("special", 0); 
	header(sprintf("Location: %s", $base_url."/index.php?section=admin&go=entries&purge=true"));
}

if ($action == "check_discount") {
	
	mysql_select_db($database, $brewing);
	$query_contest_info = "SELECT contestEntryFeePassword FROM $contest_info_db_table WHERE id=1";
	$contest_info = mysql_query($query_contest_info, $brewing) or die(mysql_error());
	$row_contest_info = mysql_fetch_assoc($contest_info);
					
	if ($_POST['brewerDiscount'] == $row_contest_info['contestEntryFeePassword']) {
		$updateSQL = sprintf("UPDATE $brewer_db_table SET brewerDiscount=%s WHERE uid=%s", 
					   GetSQLValueString("Y", "text"),
                       GetSQLValueString($id, "text"));	
		
		//echo $updateSQL;
  		mysql_select_db($database, $brewing);
  		$Result = mysql_query($updateSQL, $brewing) or die(mysql_error());
  		header(sprintf("Location: %s", $base_url."/index.php?section=pay&bid=".$id."&msg=12"));
	}
	else header(sprintf("Location: %s", $base_url."/index.php?section=pay&bid=".$id."&msg=13"));
}


if ($action == "generate_judging_numbers") {
	if ($filter == "default") {
		$query_judging_numbers = "SELECT id FROM $brewing_db_table ORDER BY id ASC";
		$judging_numbers = mysql_query($query_judging_numbers, $brewing) or die(mysql_error());
		$row_judging_numbers = mysql_fetch_assoc($judging_numbers);
		
		// Clear out all current judging numbers
		do { 
			$updateSQL = sprintf("UPDATE $brewing_db_table SET brewJudgingNumber=%s WHERE id='%s'", "NULL", $row_judging_numbers['id']);
  			mysql_select_db($database, $brewing);
  			$Result = mysql_query($updateSQL, $brewing) or die(mysql_error());
		
		} while ($row_judging_numbers = mysql_fetch_assoc($judging_numbers));
		
		$query_judging_numbers = "SELECT id,brewCategory,brewName FROM $brewing_db_table ORDER BY $sort $dir";
		$judging_numbers = mysql_query($query_judging_numbers, $brewing) or die(mysql_error());
		$row_judging_numbers = mysql_fetch_assoc($judging_numbers);
		
		// Generate and insert new judging numbers
		do { 	
			$updateSQL = sprintf("UPDATE $brewing_db_table SET brewJudgingNumber=%s WHERE id=%s", 
					   GetSQLValueString(generate_judging_num($row_judging_numbers['brewCategory']), "text"),
                       GetSQLValueString($row_judging_numbers['id'], "text"));	
  			mysql_select_db($database, $brewing);
  			$Result = mysql_query($updateSQL, $brewing) or die(mysql_error());
		
		} while ($row_judging_numbers = mysql_fetch_assoc($judging_numbers));
		
	}
if ($go == "hidden") $updateGoTo = $base_url."/index.php"; 
else $updateGoTo = $base_url."/index.php?section=admin&msg=14";
header(sprintf("Location: %s", $updateGoTo));		
}

?>