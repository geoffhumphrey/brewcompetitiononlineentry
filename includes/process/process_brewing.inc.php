<?php 
/*
 * Module:      process_entry_add.inc.php
 * Description: This module does all the heavy lifting for adding entries to the DB
 */
session_start(); 

function check_special_ingredients($style) {
	switch($style) {
		case "6-D":		
		case "16-E":
		case "17-F":
		case "20-A":
		case "21-A":
		case "21-B":
		case "22-B":
		case "22-C":
		case "23-A":
		case "25-C":
		case "26-A":
		case "26-C":
		case "27-E":
		case "28-B":
		case "28-C":
		case "28-D":
		return TRUE;
		break;
		
		default:
		return FALSE;
		break;
	}
}
	  
function check_carb_sweetness($style) {
	switch($style) {
		case "24":
		case "25":
		case "26":
		case "27":
		case "28":
		return TRUE;
		break;
		
		default:
		return FALSE;
		break;
	}
}
	
	
function check_mead_strength($style) {
	switch($style) {
		case "24":
		case "25":
		case "26":
		return TRUE;
		break;
		
		default:
		return FALSE;
		break;
	}
}										
 
include(DB.'common.db.php');
mysql_select_db($database, $brewing);

$query_user = sprintf("SELECT userLevel FROM $users_db_table WHERE user_name = '%s'", $_SESSION['loginUsername']);
$user = mysql_query($query_user, $brewing) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);

if ($action == "add") {
	
	if ($row_user['userLevel'] == 1) { 
		$query_brewer = sprintf("SELECT * FROM $brewer_db_table WHERE uid = '%s'", $_POST['brewBrewerID']);
		$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
		$row_brewer = mysql_fetch_assoc($brewer);
		
		$brewBrewerID = $row_brewer['uid'];
		$brewBrewerLastName = $row_brewer['brewerLastName'];
		$brewBrewerFirstName = $row_brewer['brewerFirstName'];

	}
	else {
		$brewBrewerID = $_POST['brewBrewerID'];
		$brewBrewerLastName = $_POST['brewBrewerLastName']; 
		$brewBrewerFirstName = $_POST['brewBrewerFirstName'];
	}
	
	$styleBreak = $_POST['brewStyle'];
	$style = explode('-', $styleBreak);
	$styleTrim = ltrim($style[0], "0"); 
	if ($style [0] < 10) $styleFix = "0".$style[0]; else $styleFix = $style[0];
	
	// Get style name from broken parts
	mysql_select_db($database, $brewing);
	$query_style_name = "SELECT * FROM $styles_db_table WHERE brewStyleGroup='$styleFix' AND brewStyleNum='$style[1]'";
	$style_name = mysql_query($query_style_name, $brewing) or die(mysql_error());
	$row_style_name = mysql_fetch_assoc($style_name);
	
	$insertSQL = sprintf("
		INSERT INTO $brewing_db_table (
		brewName,
		brewStyle,
		brewCategory, 
		brewCategorySort, 
		brewSubCategory, 
		brewBottleDate, 
		brewDate, 
		brewYield, 
		brewInfo, 
		brewMead1, 
		brewMead2, 
		brewMead3, 
		brewExtract1, 
		brewExtract1Weight, 
		brewExtract2, 
		brewExtract2Weight, 
		brewExtract3, 
		brewExtract3Weight, 
		brewExtract4, 
		brewExtract4Weight, 
		brewExtract5, brewExtract5Weight, 
		brewGrain1, 
		brewGrain1Weight, 
		brewGrain2, 
		brewGrain2Weight, 
		brewGrain3, 
		brewGrain3Weight, 
		brewGrain4, 
		brewGrain4Weight, 
		brewGrain5, 
		brewGrain5Weight, 
		brewGrain6, 
		brewGrain6Weight, 
		brewGrain7, 
		brewGrain7Weight, 
		brewGrain8, 
		brewGrain8Weight, 
		brewGrain9, 
		brewGrain9Weight, 
		brewAddition1, 
		brewAddition1Amt, 
		brewAddition2, 
		brewAddition2Amt, 
		brewAddition3, 
		brewAddition3Amt, 
		brewAddition4, 
		brewAddition4Amt, 
		brewAddition5, 
		brewAddition5Amt, 
		brewAddition6, 
		brewAddition6Amt, 
		brewAddition7, 
		brewAddition7Amt, 
		brewAddition8, 
		brewAddition8Amt, 
		brewAddition9, 
		brewAddition9Amt, 
		brewHops1, 
		brewHops1Weight, 
		brewHops1IBU, 
		brewHops1Time, 
		brewHops2, 
		brewHops2Weight, 
		brewHops2IBU, 
		brewHops2Time, 
		brewHops3, 
		brewHops3Weight, 
		brewHops3IBU, 
		brewHops3Time, 
		brewHops4, 
		brewHops4Weight, 
		brewHops4IBU, 
		brewHops4Time, 
		brewHops5, 
		brewHops5Weight, 
		brewHops5IBU, 
		brewHops5Time, 
		brewHops6, 
		brewHops6Weight, 
		brewHops6IBU, 
		brewHops6Time, 
		brewHops7, 
		brewHops7Weight, 
		brewHops7IBU, 
		brewHops7Time, 
		brewHops8, 
		brewHops8Weight, 
		brewHops8IBU, 
		brewHops8Time, 
		brewHops9, 
		brewHops9Weight, 
		brewHops9IBU, 
		brewHops9Time, 
		brewHops1Use, 
		brewHops2Use, 
		brewHops3Use, 
		brewHops4Use, 
		brewHops5Use, 
		brewHops6Use, 
		brewHops7Use, 
		brewHops8Use, 
		brewHops9Use, 
		brewHops1Type, 
		brewHops2Type, 
		brewHops3Type, 
		brewHops4Type, 
		brewHops5Type, 
		brewHops6Type, 
		brewHops7Type, 
		brewHops8Type, 
		brewHops9Type, 
		brewHops1Form, 
		brewHops2Form, 
		brewHops3Form, 
		brewHops4Form, 
		brewHops5Form, 
		brewHops6Form, 
		brewHops7Form, 
		brewHops8Form, 
		brewHops9Form, 
		brewYeast, 
		brewYeastMan, 
		brewYeastForm, 
		brewYeastType, 
		brewYeastAmount, 
		brewYeastStarter, 
		brewYeastNutrients, 
		brewOG, 
		brewFG, 
		brewPrimary, 
		brewPrimaryTemp, 
		brewSecondary, 
		brewSecondaryTemp, 
		brewOther, 
		brewOtherTemp, 
		brewComments, 
		brewMashStep1Name, 
		brewMashStep1Temp, 
		brewMashStep1Time, 
		brewMashStep2Name, 
		brewMashStep2Temp, 
		brewMashStep2Time, 
		brewMashStep3Name, 
		brewMashStep3Temp, 
		brewMashStep3Time, 
		brewMashStep4Name, 
		brewMashStep4Temp, 
		brewMashStep4Time, 
		brewMashStep5Name, 
		brewMashStep5Temp, 
		brewMashStep5Time, 
		brewFinings, 
		brewWaterNotes, 
		brewBrewerID, 
		brewCarbonationMethod, 
		brewCarbonationVol, 
		brewCarbonationNotes, 
		brewBoilHours, 
		brewBoilMins, 
		brewBrewerFirstName, 
		brewBrewerLastName, 
		brewExtract1Use, 
		brewExtract2Use, 
		brewExtract3Use, 
		brewExtract4Use, 
		brewExtract5Use, 
		brewGrain1Use, 
		brewGrain2Use, 
		brewGrain3Use, 
		brewGrain4Use, 
		brewGrain5Use, 
		brewGrain6Use, 
		brewGrain7Use, 
		brewGrain8Use, 
		brewGrain9Use, 
		brewAddition1Use, 
		brewAddition2Use, 
		brewAddition3Use, 
		brewAddition4Use, 
		brewAddition5Use, 
		brewAddition6Use, 
		brewAddition7Use, 
		brewAddition8Use, 
		brewAddition9Use, 
		brewJudgingLocation, 
		brewCoBrewer,
		brewJudgingNumber,
		brewUpdated,
		brewConfirmed,
		brewPaid,
		brewReceived) VALUES 
		(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
						   GetSQLValueString(capitalize($_POST['brewName']), "text"),
						   GetSQLValueString($row_style_name['brewStyle'], "text"),
						   GetSQLValueString($styleTrim, "text"),
						   GetSQLValueString($styleFix, "text"),
						   GetSQLValueString($style[1], "text"),
						   GetSQLValueString($_POST['brewBottleDate'], "date"),
						   GetSQLValueString($_POST['brewDate'], "date"),
						   GetSQLValueString($_POST['brewYield'], "text"),
						   GetSQLValueString($_POST['brewInfo'], "scrubbed"),
						   GetSQLValueString($_POST['brewMead1'], "text"),
						   GetSQLValueString($_POST['brewMead2'], "text"),
						   GetSQLValueString($_POST['brewMead3'], "text"),
						   GetSQLValueString($_POST['brewExtract1'], "scrubbed"),
						   GetSQLValueString($_POST['brewExtract1Weight'], "text"),
						   GetSQLValueString($_POST['brewExtract2'], "scrubbed"),
						   GetSQLValueString($_POST['brewExtract2Weight'], "text"),
						   GetSQLValueString($_POST['brewExtract3'], "scrubbed"),
						   GetSQLValueString($_POST['brewExtract3Weight'], "text"),
						   GetSQLValueString($_POST['brewExtract4'], "scrubbed"),
						   GetSQLValueString($_POST['brewExtract4Weight'], "text"),
						   GetSQLValueString($_POST['brewExtract5'], "scrubbed"),
						   GetSQLValueString($_POST['brewExtract5Weight'], "text"),
						   GetSQLValueString($_POST['brewGrain1'], "scrubbed"),
						   GetSQLValueString($_POST['brewGrain1Weight'], "text"),
						   GetSQLValueString($_POST['brewGrain2'], "scrubbed"),
						   GetSQLValueString($_POST['brewGrain2Weight'], "text"),
						   GetSQLValueString($_POST['brewGrain3'], "scrubbed"),
						   GetSQLValueString($_POST['brewGrain3Weight'], "text"),
						   GetSQLValueString($_POST['brewGrain4'], "scrubbed"),
						   GetSQLValueString($_POST['brewGrain4Weight'], "text"),
						   GetSQLValueString($_POST['brewGrain5'], "scrubbed"),
						   GetSQLValueString($_POST['brewGrain5Weight'], "text"),
						   GetSQLValueString($_POST['brewGrain6'], "scrubbed"),
						   GetSQLValueString($_POST['brewGrain6Weight'], "text"),
						   GetSQLValueString($_POST['brewGrain7'], "scrubbed"),
						   GetSQLValueString($_POST['brewGrain7Weight'], "text"),
						   GetSQLValueString($_POST['brewGrain8'], "scrubbed"),
						   GetSQLValueString($_POST['brewGrain8Weight'], "text"),
						   GetSQLValueString($_POST['brewGrain9'], "scrubbed"),
						   GetSQLValueString($_POST['brewGrain9Weight'], "text"),
						   GetSQLValueString($_POST['brewAddition1'], "scrubbed"),
						   GetSQLValueString($_POST['brewAddition1Amt'], "text"),
						   GetSQLValueString($_POST['brewAddition2'], "scrubbed"),
						   GetSQLValueString($_POST['brewAddition2Amt'], "text"),
						   GetSQLValueString($_POST['brewAddition3'], "scrubbed"),
						   GetSQLValueString($_POST['brewAddition3Amt'], "text"),
						   GetSQLValueString($_POST['brewAddition4'], "scrubbed"),
						   GetSQLValueString($_POST['brewAddition4Amt'], "text"),
						   GetSQLValueString($_POST['brewAddition5'], "scrubbed"),
						   GetSQLValueString($_POST['brewAddition5Amt'], "text"),
						   GetSQLValueString($_POST['brewAddition6'], "scrubbed"),
						   GetSQLValueString($_POST['brewAddition6Amt'], "text"),
						   GetSQLValueString($_POST['brewAddition7'], "scrubbed"),
						   GetSQLValueString($_POST['brewAddition7Amt'], "text"),
						   GetSQLValueString($_POST['brewAddition8'], "scrubbed"),
						   GetSQLValueString($_POST['brewAddition8Amt'], "text"),
						   GetSQLValueString($_POST['brewAddition9'], "scrubbed"),
						   GetSQLValueString($_POST['brewAddition9Amt'], "text"),
						   GetSQLValueString($_POST['brewHops1'], "scrubbed"),
						   GetSQLValueString($_POST['brewHops1Weight'], "text"),
						   GetSQLValueString($_POST['brewHops1IBU'], "text"),
						   GetSQLValueString($_POST['brewHops1Time'], "text"),
						   GetSQLValueString($_POST['brewHops2'], "scrubbed"),
						   GetSQLValueString($_POST['brewHops2Weight'], "text"),
						   GetSQLValueString($_POST['brewHops2IBU'], "text"),
						   GetSQLValueString($_POST['brewHops2Time'], "text"),
						   GetSQLValueString($_POST['brewHops3'], "scrubbed"),
						   GetSQLValueString($_POST['brewHops3Weight'], "text"),
						   GetSQLValueString($_POST['brewHops3IBU'], "text"),
						   GetSQLValueString($_POST['brewHops3Time'], "text"),
						   GetSQLValueString($_POST['brewHops4'], "scrubbed"),
						   GetSQLValueString($_POST['brewHops4Weight'], "text"),
						   GetSQLValueString($_POST['brewHops4IBU'], "text"),
						   GetSQLValueString($_POST['brewHops4Time'], "text"),
						   GetSQLValueString($_POST['brewHops5'], "scrubbed"),
						   GetSQLValueString($_POST['brewHops5Weight'], "text"),
						   GetSQLValueString($_POST['brewHops5IBU'], "text"),
						   GetSQLValueString($_POST['brewHops5Time'], "text"),
						   GetSQLValueString($_POST['brewHops6'], "scrubbed"),
						   GetSQLValueString($_POST['brewHops6Weight'], "text"),
						   GetSQLValueString($_POST['brewHops6IBU'], "text"),
						   GetSQLValueString($_POST['brewHops6Time'], "text"),
						   GetSQLValueString($_POST['brewHops7'], "scrubbed"),
						   GetSQLValueString($_POST['brewHops7Weight'], "text"),
						   GetSQLValueString($_POST['brewHops7IBU'], "text"),
						   GetSQLValueString($_POST['brewHops7Time'], "text"),
						   GetSQLValueString($_POST['brewHops8'], "scrubbed"),
						   GetSQLValueString($_POST['brewHops8Weight'], "text"),
						   GetSQLValueString($_POST['brewHops8IBU'], "text"),
						   GetSQLValueString($_POST['brewHops8Time'], "text"),
						   GetSQLValueString($_POST['brewHops9'], "scrubbed"),
						   GetSQLValueString($_POST['brewHops9Weight'], "text"),
						   GetSQLValueString($_POST['brewHops9IBU'], "text"),
						   GetSQLValueString($_POST['brewHops9Time'], "text"),
						   GetSQLValueString($_POST['brewHops1Use'], "text"),
						   GetSQLValueString($_POST['brewHops2Use'], "text"),
						   GetSQLValueString($_POST['brewHops3Use'], "text"),
						   GetSQLValueString($_POST['brewHops4Use'], "text"),
						   GetSQLValueString($_POST['brewHops5Use'], "text"),
						   GetSQLValueString($_POST['brewHops6Use'], "text"),
						   GetSQLValueString($_POST['brewHops7Use'], "text"),
						   GetSQLValueString($_POST['brewHops8Use'], "text"),
						   GetSQLValueString($_POST['brewHops9Use'], "text"),
						   GetSQLValueString($_POST['brewHops1Type'], "text"),
						   GetSQLValueString($_POST['brewHops2Type'], "text"),
						   GetSQLValueString($_POST['brewHops3Type'], "text"),
						   GetSQLValueString($_POST['brewHops4Type'], "text"),
						   GetSQLValueString($_POST['brewHops5Type'], "text"),
						   GetSQLValueString($_POST['brewHops6Type'], "text"),
						   GetSQLValueString($_POST['brewHops7Type'], "text"),
						   GetSQLValueString($_POST['brewHops8Type'], "text"),
						   GetSQLValueString($_POST['brewHops9Type'], "text"),
						   GetSQLValueString($_POST['brewHops1Form'], "text"),
						   GetSQLValueString($_POST['brewHops2Form'], "text"),
						   GetSQLValueString($_POST['brewHops3Form'], "text"),
						   GetSQLValueString($_POST['brewHops4Form'], "text"),
						   GetSQLValueString($_POST['brewHops5Form'], "text"),
						   GetSQLValueString($_POST['brewHops6Form'], "text"),
						   GetSQLValueString($_POST['brewHops7Form'], "text"),
						   GetSQLValueString($_POST['brewHops8Form'], "text"),
						   GetSQLValueString($_POST['brewHops9Form'], "text"),
						   GetSQLValueString($_POST['brewYeast'], "scrubbed"),
						   GetSQLValueString($_POST['brewYeastMan'], "scrubbed"),
						   GetSQLValueString($_POST['brewYeastForm'], "text"),
						   GetSQLValueString($_POST['brewYeastType'], "text"),
						   GetSQLValueString($_POST['brewYeastAmount'], "text"),
						   GetSQLValueString($_POST['brewYeastStarter'], "text"),
						   GetSQLValueString($_POST['brewYeastNutrients'], "scrubbed"),
						   GetSQLValueString($_POST['brewOG'], "text"),
						   GetSQLValueString($_POST['brewFG'], "text"),
						   GetSQLValueString($_POST['brewPrimary'], "text"),
						   GetSQLValueString($_POST['brewPrimaryTemp'], "text"),
						   GetSQLValueString($_POST['brewSecondary'], "text"),
						   GetSQLValueString($_POST['brewSecondaryTemp'], "text"),
						   GetSQLValueString($_POST['brewOther'], "text"),
						   GetSQLValueString($_POST['brewOtherTemp'], "text"),
						   GetSQLValueString($_POST['brewComments'], "scrubbed"),
						   GetSQLValueString($_POST['brewMashStep1Name'], "scrubbed"),
						   GetSQLValueString($_POST['brewMashStep1Temp'], "text"),
						   GetSQLValueString($_POST['brewMashStep1Time'], "text"),
						   GetSQLValueString($_POST['brewMashStep2Name'], "scrubbed"),
						   GetSQLValueString($_POST['brewMashStep2Temp'], "text"),
						   GetSQLValueString($_POST['brewMashStep2Time'], "text"),
						   GetSQLValueString($_POST['brewMashStep3Name'], "scrubbed"),
						   GetSQLValueString($_POST['brewMashStep3Temp'], "text"),
						   GetSQLValueString($_POST['brewMashStep3Time'], "text"),
						   GetSQLValueString($_POST['brewMashStep4Name'], "scrubbed"),
						   GetSQLValueString($_POST['brewMashStep4Temp'], "text"),
						   GetSQLValueString($_POST['brewMashStep4Time'], "text"),
						   GetSQLValueString($_POST['brewMashStep5Name'], "scrubbed"),
						   GetSQLValueString($_POST['brewMashStep5Temp'], "text"),
						   GetSQLValueString($_POST['brewMashStep5Time'], "text"),
						   GetSQLValueString($_POST['brewFinings'], "scrubbed"),
						   GetSQLValueString($_POST['brewWaterNotes'], "scrubbed"),
						   GetSQLValueString($brewBrewerID, "int"),
						   GetSQLValueString($_POST['brewCarbonationMethod'], "text"),
						   GetSQLValueString($_POST['brewCarbonationVol'], "text"),
						   GetSQLValueString($_POST['brewCarbonationNotes'], "scrubbed"),
						   GetSQLValueString($_POST['brewBoilHours'], "text"),
						   GetSQLValueString($_POST['brewBoilMins'], "text"),
						   GetSQLValueString($brewBrewerFirstName, "text"),
						   GetSQLValueString($brewBrewerLastName, "text"),
						   GetSQLValueString($_POST['brewExtract1Use'], "text"), 
						   GetSQLValueString($_POST['brewExtract2Use'], "text"), 
						   GetSQLValueString($_POST['brewExtract3Use'], "text"), 
						   GetSQLValueString($_POST['brewExtract4Use'], "text"), 
						   GetSQLValueString($_POST['brewExtract5Use'], "text"),
						   GetSQLValueString($_POST['brewGrain1Use'], "text"), 
						   GetSQLValueString($_POST['brewGrain2Use'], "text"), 
						   GetSQLValueString($_POST['brewGrain3Use'], "text"), 
						   GetSQLValueString($_POST['brewGrain4Use'], "text"), 
						   GetSQLValueString($_POST['brewGrain5Use'], "text"),
						   GetSQLValueString($_POST['brewGrain6Use'], "text"), 
						   GetSQLValueString($_POST['brewGrain7Use'], "text"), 
						   GetSQLValueString($_POST['brewGrain8Use'], "text"), 
						   GetSQLValueString($_POST['brewGrain9Use'], "text"),
						   GetSQLValueString($_POST['brewAddition1Use'], "text"), 
						   GetSQLValueString($_POST['brewAddition2Use'], "text"), 
						   GetSQLValueString($_POST['brewAddition3Use'], "text"), 
						   GetSQLValueString($_POST['brewAddition4Use'], "text"), 
						   GetSQLValueString($_POST['brewAddition5Use'], "text"),
						   GetSQLValueString($_POST['brewAddition6Use'], "text"), 
						   GetSQLValueString($_POST['brewAddition7Use'], "text"), 
						   GetSQLValueString($_POST['brewAddition8Use'], "text"), 
						   GetSQLValueString($_POST['brewAddition9Use'], "text"),
						   GetSQLValueString($row_style_name['brewStyleJudgingLoc'], "int"),
						   GetSQLValueString($_POST['brewCoBrewer'], "text"),
						   GetSQLValueString(generate_judging_num($styleTrim), "text"),
						   "NOW( )",
						   GetSQLValueString($_POST['brewConfirmed'], "int"),
						   GetSQLValueString("0", "int"),
						   GetSQLValueString("0", "int")
						   );
	
	  mysql_select_db($database, $brewing);
	  $Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());


	if ($id == "default") {
		mysql_select_db($database, $brewing);
		$query_brew_id = "SELECT id FROM $brewing_db_table WHERE brewBrewerID='$brewBrewerID' ORDER BY id DESC LIMIT 1";
		$brew_id = mysql_query($query_brew_id, $brewing) or die(mysql_error());
		$row_brew_id = mysql_fetch_assoc($brew_id);
		$id = $row_brew_id['id'];
	}
	  
	// Check if entry requires special ingredients or a classic style
	  
	if (check_special_ingredients($styleBreak)) {
		  
		if ($_POST['brewInfo'] == "") {
			$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id='%s'", GetSQLValueString($id, "int"));
			mysql_select_db($database, $brewing);
			$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		}
		
		if ($section == "admin") {
			if ($_POST['brewInfo'] == "") $insertGoTo = $base_url."/index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=1-".$styleBreak;
			else $insertGoTo = $base_url."/index.php?section=admin&go=entries&msg=2";
		}
		
		else {
			if ($_POST['brewInfo'] == "") $insertGoTo = $base_url."/index.php?section=brew&action=edit&id=$id&msg=1-".$styleBreak;
			else $insertGoTo = $base_url."/index.php?section=list&msg=2";
		}
		  
	 }
	 
	 // Check if mead/cider entry has carbonation and sweetness
	 
	elseif (check_mead_strength($style[0])) {
		
		if ($_POST['brewMead3'] == "") {
			$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id='%s'", GetSQLValueString($id, "int"));
			mysql_select_db($database, $brewing);
			$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		}
		
		if ($section == "admin") {
			if ($_POST['brewMead3'] == "") $insertGoTo = $base_url."/index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=1-".$styleBreak;
			else $insertGoTo = $base_url."/index.php?section=admin&go=entries&msg=2";
		}
		
		else {
			if ($_POST['brewMead3'] == "") $insertGoTo = $base_url."/index.php?section=brew&action=edit&id=$id&msg=1-".$styleBreak;
			else $insertGoTo = $base_url."/index.php?section=list&msg=2";
		}
	}
	  
	 elseif (check_carb_sweetness($style[0])) {
		 
		if (($_POST['brewMead1'] == "") || ($_POST['brewMead2'] == "")) {
			$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id='%s'", GetSQLValueString($id, "int"));
			mysql_select_db($database, $brewing);
			$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		}
		
		if ($section == "admin") {
			if (($_POST['brewMead1'] == "") || ($_POST['brewMead2'] == "")) $insertGoTo = $base_url."/index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=1-".$styleBreak;
			else $insertGoTo = $base_url."/index.php?section=admin&go=entries&msg=2";
		}
		
		else {
			if (($_POST['brewMead1'] == "") || ($_POST['brewMead2'] == "")) $insertGoTo = $base_url."/index.php?section=brew&action=edit&id=$id&msg=1-".$styleBreak;
			else $insertGoTo = $base_url."/index.php?section=list&msg=2";
		}
		  
	 }
	
	elseif ((check_carb_sweetness($style[0])) && (check_mead_strength($style[0]))) {
		 
		if (($_POST['brewMead1'] == "") || ($_POST['brewMead2'] == "") || ($_POST['brewMead3'] == "")) {
			$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id='%s'", GetSQLValueString($id, "int"));
			mysql_select_db($database, $brewing);
			$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		}
		
		if ($section == "admin") {
			if (($_POST['brewMead1'] == "") || ($_POST['brewMead2'] == "") || ($_POST['brewMead3'] == ""))  $insertGoTo = $base_url."/index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=1-".$styleBreak;
			else $insertGoTo = $base_url."/index.php?section=admin&go=entries&msg=2";
		}
		
		else {
			if (($_POST['brewMead1'] == "") || ($_POST['brewMead2'] == "") || ($_POST['brewMead3'] == ""))  $insertGoTo = $base_url."/index.php?section=brew&action=edit&id=$id&msg=1-".$styleBreak;
			else $insertGoTo = $base_url."/index.php?section=list&msg=2";
		}
		  
	 }
	
	elseif (($style[0] > 28) && ($_POST['brewInfo'] == "")) $insertGoTo = $base_url."/index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=4";
	elseif (($style[0] > 28) && ($_POST['brewInfo'] != "")) $insertGoTo = $base_url."/index.php?section=list&msg=1";
	
	elseif ($section == "admin") $insertGoTo = $base_url."/index.php?section=admin&go=entries&msg=1";
	else $insertGoTo = $base_url."/index.php?section=list&msg=1"; 
	
	
	// Finally, relocate
	$pattern = array('\'', '"');
  	$insertGoTo = str_replace($pattern, "", $insertGoTo); 
  	header(sprintf("Location: %s", stripslashes($insertGoTo)));
} // end if ($action == "add")

if ($action == "edit") {
	
	if ($row_user['userLevel'] == 1) { 
		$name = $_POST['brewBrewerID'];
		
		$query_brewer = sprintf("SELECT * FROM $brewer_db_table WHERE uid = '%s'", $name);
		$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
		$row_brewer = mysql_fetch_assoc($brewer);
		
		$brewBrewerID = $row_brewer['uid'];
		$brewBrewerLastName = $row_brewer['brewerLastName'];
		$brewBrewerFirstName = $row_brewer['brewerFirstName'];
	
	}
	else {
		$brewBrewerID = $_POST['brewBrewerID'];
		$brewBrewerLastName = $_POST['brewBrewerLastName']; 
		$brewBrewerFirstName = $_POST['brewBrewerFirstName'];
	}
	
	$styleBreak = $_POST['brewStyle'];
	$style = explode('-', $styleBreak);
	$styleTrim = ltrim($style[0], "0"); 
	if ($style [0] < 10) $styleFix = "0".$style[0]; else $styleFix = $style[0];
	
	// Get style name from broken parts
	mysql_select_db($database, $brewing);
	$query_style_name = "SELECT * FROM $styles_db_table WHERE brewStyleGroup='$styleFix' AND brewStyleNum='$style[1]'";
	$style_name = mysql_query($query_style_name, $brewing) or die(mysql_error());
	$row_style_name = mysql_fetch_assoc($style_name);
	$check = $row_style_name['brewStyleOwn'];
	
	$updateSQL = sprintf("UPDATE $brewing_db_table SET brewName=%s, brewStyle=%s, brewCategory=%s, brewCategorySort=%s, brewSubCategory=%s, brewBottleDate=%s, brewDate=%s, brewYield=%s, brewInfo=%s, brewMead1=%s, brewMead2=%s, brewMead3=%s, brewExtract1=%s, brewExtract1Weight=%s, brewExtract2=%s, brewExtract2Weight=%s, brewExtract3=%s, brewExtract3Weight=%s, brewExtract4=%s, brewExtract4Weight=%s, brewExtract5=%s, brewExtract5Weight=%s, brewGrain1=%s, brewGrain1Weight=%s, brewGrain2=%s, brewGrain2Weight=%s, brewGrain3=%s, brewGrain3Weight=%s, brewGrain4=%s, brewGrain4Weight=%s, brewGrain5=%s, brewGrain5Weight=%s, brewGrain6=%s, brewGrain6Weight=%s, brewGrain7=%s, brewGrain7Weight=%s, brewGrain8=%s, brewGrain8Weight=%s, brewGrain9=%s, brewGrain9Weight=%s, brewAddition1=%s, brewAddition1Amt=%s, brewAddition2=%s, brewAddition2Amt=%s, brewAddition3=%s, brewAddition3Amt=%s, brewAddition4=%s, brewAddition4Amt=%s, brewAddition5=%s, brewAddition5Amt=%s, brewAddition6=%s, brewAddition6Amt=%s, brewAddition7=%s, brewAddition7Amt=%s, brewAddition8=%s, brewAddition8Amt=%s, brewAddition9=%s, brewAddition9Amt=%s, brewHops1=%s, brewHops1Weight=%s, brewHops1IBU=%s, brewHops1Time=%s, brewHops2=%s, brewHops2Weight=%s, brewHops2IBU=%s, brewHops2Time=%s, brewHops3=%s, brewHops3Weight=%s, brewHops3IBU=%s, brewHops3Time=%s, brewHops4=%s, brewHops4Weight=%s, brewHops4IBU=%s, brewHops4Time=%s, brewHops5=%s, brewHops5Weight=%s, brewHops5IBU=%s, brewHops5Time=%s, brewHops6=%s, brewHops6Weight=%s, brewHops6IBU=%s, brewHops6Time=%s, brewHops7=%s, brewHops7Weight=%s, brewHops7IBU=%s, brewHops7Time=%s, brewHops8=%s, brewHops8Weight=%s, brewHops8IBU=%s, brewHops8Time=%s, brewHops9=%s, brewHops9Weight=%s, brewHops9IBU=%s, brewHops9Time=%s, brewHops1Use=%s, brewHops2Use=%s, brewHops3Use=%s, brewHops4Use=%s, brewHops5Use=%s, brewHops6Use=%s, brewHops7Use=%s, brewHops8Use=%s, brewHops9Use=%s, brewHops1Type=%s, brewHops2Type=%s, brewHops3Type=%s, brewHops4Type=%s, brewHops5Type=%s, brewHops6Type=%s, brewHops7Type=%s, brewHops8Type=%s, brewHops9Type=%s, brewHops1Form=%s, brewHops2Form=%s, brewHops3Form=%s, brewHops4Form=%s, brewHops5Form=%s, brewHops6Form=%s, brewHops7Form=%s, brewHops8Form=%s, brewHops9Form=%s, brewYeast=%s, brewYeastMan=%s, brewYeastForm=%s, brewYeastType=%s, brewYeastAmount=%s, brewYeastStarter=%s, brewYeastNutrients=%s, brewOG=%s, brewFG=%s, brewPrimary=%s, brewPrimaryTemp=%s, brewSecondary=%s, brewSecondaryTemp=%s, brewOther=%s, brewOtherTemp=%s, brewComments=%s, brewMashStep1Name=%s, brewMashStep1Temp=%s, brewMashStep1Time=%s, brewMashStep2Name=%s, brewMashStep2Temp=%s, brewMashStep2Time=%s, brewMashStep3Name=%s, brewMashStep3Temp=%s, brewMashStep3Time=%s, brewMashStep4Name=%s, brewMashStep4Temp=%s, brewMashStep4Time=%s, brewMashStep5Name=%s, brewMashStep5Temp=%s, brewMashStep5Time=%s, brewFinings=%s, brewWaterNotes=%s, brewBrewerID=%s, brewCarbonationMethod=%s, brewCarbonationVol=%s, brewCarbonationNotes=%s, brewBoilHours=%s, brewBoilMins=%s, brewBrewerFirstName=%s, brewBrewerLastName=%s, brewExtract1Use=%s, brewExtract2Use=%s, brewExtract3Use=%s, brewExtract4Use=%s, brewExtract5Use=%s, brewGrain1Use=%s, brewGrain2Use=%s, brewGrain3Use=%s, brewGrain4Use=%s, brewGrain5Use=%s, brewGrain6Use=%s, brewGrain7Use=%s, brewGrain8Use=%s, brewGrain9Use=%s, brewAddition1Use=%s, brewAddition2Use=%s, brewAddition3Use=%s, brewAddition4Use=%s, brewAddition5Use=%s, brewAddition6Use=%s, brewAddition7Use=%s, brewAddition8Use=%s, brewAddition9Use=%s, brewJudgingLocation=%s, brewCoBrewer=%s, brewUpdated=%s, brewConfirmed=%s WHERE id=%s",
						   GetSQLValueString(capitalize($_POST['brewName']), "text"),
						   GetSQLValueString($row_style_name['brewStyle'], "text"),
						   GetSQLValueString($styleTrim, "text"),
						   GetSQLValueString($styleFix, "text"),
						   GetSQLValueString($style[1], "text"),
						   GetSQLValueString($_POST['brewBottleDate'], "date"),
						   GetSQLValueString($_POST['brewDate'], "date"),
						   GetSQLValueString($_POST['brewYield'], "text"),
						   GetSQLValueString($_POST['brewInfo'], "text"),
						   GetSQLValueString($_POST['brewMead1'], "text"),
						   GetSQLValueString($_POST['brewMead2'], "text"),
						   GetSQLValueString($_POST['brewMead3'], "text"),
						   GetSQLValueString($_POST['brewExtract1'], "scrubbed"),
						   GetSQLValueString($_POST['brewExtract1Weight'], "text"),
						   GetSQLValueString($_POST['brewExtract2'], "scrubbed"),
						   GetSQLValueString($_POST['brewExtract2Weight'], "text"),
						   GetSQLValueString($_POST['brewExtract3'], "scrubbed"),
						   GetSQLValueString($_POST['brewExtract3Weight'], "text"),
						   GetSQLValueString($_POST['brewExtract4'], "scrubbed"),
						   GetSQLValueString($_POST['brewExtract4Weight'], "text"),
						   GetSQLValueString($_POST['brewExtract5'], "scrubbed"),
						   GetSQLValueString($_POST['brewExtract5Weight'], "text"),
						   GetSQLValueString($_POST['brewGrain1'], "scrubbed"),
						   GetSQLValueString($_POST['brewGrain1Weight'], "text"),
						   GetSQLValueString($_POST['brewGrain2'], "scrubbed"),
						   GetSQLValueString($_POST['brewGrain2Weight'], "text"),
						   GetSQLValueString($_POST['brewGrain3'], "scrubbed"),
						   GetSQLValueString($_POST['brewGrain3Weight'], "text"),
						   GetSQLValueString($_POST['brewGrain4'], "scrubbed"),
						   GetSQLValueString($_POST['brewGrain4Weight'], "text"),
						   GetSQLValueString($_POST['brewGrain5'], "scrubbed"),
						   GetSQLValueString($_POST['brewGrain5Weight'], "text"),
						   GetSQLValueString($_POST['brewGrain6'], "scrubbed"),
						   GetSQLValueString($_POST['brewGrain6Weight'], "text"),
						   GetSQLValueString($_POST['brewGrain7'], "scrubbed"),
						   GetSQLValueString($_POST['brewGrain7Weight'], "text"),
						   GetSQLValueString($_POST['brewGrain8'], "scrubbed"),
						   GetSQLValueString($_POST['brewGrain8Weight'], "text"),
						   GetSQLValueString($_POST['brewGrain9'], "scrubbed"),
						   GetSQLValueString($_POST['brewGrain9Weight'], "text"),
						   GetSQLValueString($_POST['brewAddition1'], "scrubbed"),
						   GetSQLValueString($_POST['brewAddition1Amt'], "text"),
						   GetSQLValueString($_POST['brewAddition2'], "scrubbed"),
						   GetSQLValueString($_POST['brewAddition2Amt'], "text"),
						   GetSQLValueString($_POST['brewAddition3'], "scrubbed"),
						   GetSQLValueString($_POST['brewAddition3Amt'], "text"),
						   GetSQLValueString($_POST['brewAddition4'], "scrubbed"),
						   GetSQLValueString($_POST['brewAddition4Amt'], "text"),
						   GetSQLValueString($_POST['brewAddition5'], "scrubbed"),
						   GetSQLValueString($_POST['brewAddition5Amt'], "text"),
						   GetSQLValueString($_POST['brewAddition6'], "scrubbed"),
						   GetSQLValueString($_POST['brewAddition6Amt'], "text"),
						   GetSQLValueString($_POST['brewAddition7'], "scrubbed"),
						   GetSQLValueString($_POST['brewAddition7Amt'], "text"),
						   GetSQLValueString($_POST['brewAddition8'], "scrubbed"),
						   GetSQLValueString($_POST['brewAddition8Amt'], "text"),
						   GetSQLValueString($_POST['brewAddition9'], "scrubbed"),
						   GetSQLValueString($_POST['brewAddition9Amt'], "text"),
						   GetSQLValueString($_POST['brewHops1'], "scrubbed"),
						   GetSQLValueString($_POST['brewHops1Weight'], "text"),
						   GetSQLValueString($_POST['brewHops1IBU'], "text"),
						   GetSQLValueString($_POST['brewHops1Time'], "text"),
						   GetSQLValueString($_POST['brewHops2'], "scrubbed"),
						   GetSQLValueString($_POST['brewHops2Weight'], "text"),
						   GetSQLValueString($_POST['brewHops2IBU'], "text"),
						   GetSQLValueString($_POST['brewHops2Time'], "text"),
						   GetSQLValueString($_POST['brewHops3'], "scrubbed"),
						   GetSQLValueString($_POST['brewHops3Weight'], "text"),
						   GetSQLValueString($_POST['brewHops3IBU'], "text"),
						   GetSQLValueString($_POST['brewHops3Time'], "text"),
						   GetSQLValueString($_POST['brewHops4'], "scrubbed"),
						   GetSQLValueString($_POST['brewHops4Weight'], "text"),
						   GetSQLValueString($_POST['brewHops4IBU'], "text"),
						   GetSQLValueString($_POST['brewHops4Time'], "text"),
						   GetSQLValueString($_POST['brewHops5'], "scrubbed"),
						   GetSQLValueString($_POST['brewHops5Weight'], "text"),
						   GetSQLValueString($_POST['brewHops5IBU'], "text"),
						   GetSQLValueString($_POST['brewHops5Time'], "text"),
						   GetSQLValueString($_POST['brewHops6'], "scrubbed"),
						   GetSQLValueString($_POST['brewHops6Weight'], "text"),
						   GetSQLValueString($_POST['brewHops6IBU'], "text"),
						   GetSQLValueString($_POST['brewHops6Time'], "text"),
						   GetSQLValueString($_POST['brewHops7'], "scrubbed"),
						   GetSQLValueString($_POST['brewHops7Weight'], "text"),
						   GetSQLValueString($_POST['brewHops7IBU'], "text"),
						   GetSQLValueString($_POST['brewHops7Time'], "text"),
						   GetSQLValueString($_POST['brewHops8'], "scrubbed"),
						   GetSQLValueString($_POST['brewHops8Weight'], "text"),
						   GetSQLValueString($_POST['brewHops8IBU'], "text"),
						   GetSQLValueString($_POST['brewHops8Time'], "text"),
						   GetSQLValueString($_POST['brewHops9'], "scrubbed"),
						   GetSQLValueString($_POST['brewHops9Weight'], "text"),
						   GetSQLValueString($_POST['brewHops9IBU'], "text"),
						   GetSQLValueString($_POST['brewHops9Time'], "text"),
						   GetSQLValueString($_POST['brewHops1Use'], "text"),
						   GetSQLValueString($_POST['brewHops2Use'], "text"),
						   GetSQLValueString($_POST['brewHops3Use'], "text"),
						   GetSQLValueString($_POST['brewHops4Use'], "text"),
						   GetSQLValueString($_POST['brewHops5Use'], "text"),
						   GetSQLValueString($_POST['brewHops6Use'], "text"),
						   GetSQLValueString($_POST['brewHops7Use'], "text"),
						   GetSQLValueString($_POST['brewHops8Use'], "text"),
						   GetSQLValueString($_POST['brewHops9Use'], "text"),
						   GetSQLValueString($_POST['brewHops1Type'], "text"),
						   GetSQLValueString($_POST['brewHops2Type'], "text"),
						   GetSQLValueString($_POST['brewHops3Type'], "text"),
						   GetSQLValueString($_POST['brewHops4Type'], "text"),
						   GetSQLValueString($_POST['brewHops5Type'], "text"),
						   GetSQLValueString($_POST['brewHops6Type'], "text"),
						   GetSQLValueString($_POST['brewHops7Type'], "text"),
						   GetSQLValueString($_POST['brewHops8Type'], "text"),
						   GetSQLValueString($_POST['brewHops9Type'], "text"),
						   GetSQLValueString($_POST['brewHops1Form'], "text"),
						   GetSQLValueString($_POST['brewHops2Form'], "text"),
						   GetSQLValueString($_POST['brewHops3Form'], "text"),
						   GetSQLValueString($_POST['brewHops4Form'], "text"),
						   GetSQLValueString($_POST['brewHops5Form'], "text"),
						   GetSQLValueString($_POST['brewHops6Form'], "text"),
						   GetSQLValueString($_POST['brewHops7Form'], "text"),
						   GetSQLValueString($_POST['brewHops8Form'], "text"),
						   GetSQLValueString($_POST['brewHops9Form'], "text"),
						   GetSQLValueString($_POST['brewYeast'], "scrubbed"),
						   GetSQLValueString($_POST['brewYeastMan'], "scrubbed"),
						   GetSQLValueString($_POST['brewYeastForm'], "text"),
						   GetSQLValueString($_POST['brewYeastType'], "text"),
						   GetSQLValueString($_POST['brewYeastAmount'], "scrubbed"),
						   GetSQLValueString($_POST['brewYeastStarter'], "text"),
						   GetSQLValueString($_POST['brewYeastNutrients'], "scrubbed"),
						   GetSQLValueString($_POST['brewOG'], "text"),
						   GetSQLValueString($_POST['brewFG'], "text"),
						   GetSQLValueString($_POST['brewPrimary'], "text"),
						   GetSQLValueString($_POST['brewPrimaryTemp'], "text"),
						   GetSQLValueString($_POST['brewSecondary'], "text"),
						   GetSQLValueString($_POST['brewSecondaryTemp'], "text"),
						   GetSQLValueString($_POST['brewOther'], "text"),
						   GetSQLValueString($_POST['brewOtherTemp'], "text"),
						   GetSQLValueString($_POST['brewComments'], "text"),
						   GetSQLValueString($_POST['brewMashStep1Name'], "scrubbed"),
						   GetSQLValueString($_POST['brewMashStep1Temp'], "text"),
						   GetSQLValueString($_POST['brewMashStep1Time'], "text"),
						   GetSQLValueString($_POST['brewMashStep2Name'], "scrubbed"),
						   GetSQLValueString($_POST['brewMashStep2Temp'], "text"),
						   GetSQLValueString($_POST['brewMashStep2Time'], "text"),
						   GetSQLValueString($_POST['brewMashStep3Name'], "scrubbed"),
						   GetSQLValueString($_POST['brewMashStep3Temp'], "text"),
						   GetSQLValueString($_POST['brewMashStep3Time'], "text"),
						   GetSQLValueString($_POST['brewMashStep4Name'], "scrubbed"),
						   GetSQLValueString($_POST['brewMashStep4Temp'], "text"),
						   GetSQLValueString($_POST['brewMashStep4Time'], "text"),
						   GetSQLValueString($_POST['brewMashStep5Name'], "scrubbed"),
						   GetSQLValueString($_POST['brewMashStep5Temp'], "text"),
						   GetSQLValueString($_POST['brewMashStep5Time'], "text"),
						   GetSQLValueString($_POST['brewFinings'], "scrubbed"),
						   GetSQLValueString($_POST['brewWaterNotes'], "scrubbed"),
						   GetSQLValueString($brewBrewerID, "text"),
						   GetSQLValueString($_POST['brewCarbonationMethod'], "text"),
						   GetSQLValueString($_POST['brewCarbonationVol'], "text"),
						   GetSQLValueString($_POST['brewCarbonationNotes'], "scrubbed"),
						   GetSQLValueString($_POST['brewBoilHours'], "text"),
						   GetSQLValueString($_POST['brewBoilMins'], "text"),
						   GetSQLValueString($brewBrewerFirstName, "text"),
						   GetSQLValueString($brewBrewerLastName, "text"),
						   GetSQLValueString($_POST['brewExtract1Use'], "text"), 
						   GetSQLValueString($_POST['brewExtract2Use'], "text"), 
						   GetSQLValueString($_POST['brewExtract3Use'], "text"), 
						   GetSQLValueString($_POST['brewExtract4Use'], "text"), 
						   GetSQLValueString($_POST['brewExtract5Use'], "text"),
						   GetSQLValueString($_POST['brewGrain1Use'], "text"), 
						   GetSQLValueString($_POST['brewGrain2Use'], "text"), 
						   GetSQLValueString($_POST['brewGrain3Use'], "text"), 
						   GetSQLValueString($_POST['brewGrain4Use'], "text"), 
						   GetSQLValueString($_POST['brewGrain5Use'], "text"),
						   GetSQLValueString($_POST['brewGrain6Use'], "text"), 
						   GetSQLValueString($_POST['brewGrain7Use'], "text"), 
						   GetSQLValueString($_POST['brewGrain8Use'], "text"), 
						   GetSQLValueString($_POST['brewGrain9Use'], "text"),
						   GetSQLValueString($_POST['brewAddition1Use'], "text"), 
						   GetSQLValueString($_POST['brewAddition2Use'], "text"), 
						   GetSQLValueString($_POST['brewAddition3Use'], "text"), 
						   GetSQLValueString($_POST['brewAddition4Use'], "text"), 
						   GetSQLValueString($_POST['brewAddition5Use'], "text"),
						   GetSQLValueString($_POST['brewAddition6Use'], "text"), 
						   GetSQLValueString($_POST['brewAddition7Use'], "text"), 
						   GetSQLValueString($_POST['brewAddition8Use'], "text"), 
						   GetSQLValueString($_POST['brewAddition9Use'], "text"),
						   GetSQLValueString($row_style_name['brewStyleJudgingLoc'], "int"),
						   GetSQLValueString($_POST['brewCoBrewer'], "text"),
						   "NOW( )",
						   GetSQLValueString($_POST['brewConfirmed'], "int"),
						   GetSQLValueString($id, "int"));
	  
	mysql_select_db($database, $brewing);
	$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
	  
	// Check if entry requires special ingredients or a classic style
	  
	if (check_special_ingredients($styleBreak)) {
		  
		if ($_POST['brewInfo'] == "") {
			$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id='%s'", GetSQLValueString($id, "int"));
			mysql_select_db($database, $brewing);
			$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		}
		
		if ($section == "admin") {
			if ($_POST['brewInfo'] == "") $updateGoTo = $base_url."/index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=1-".$styleBreak;
			else $updateGoTo = $base_url."/index.php?section=admin&go=entries&msg=2";
		}
		
		else {
			if ($_POST['brewInfo'] == "") $updateGoTo = $base_url."/index.php?section=brew&action=edit&id=$id&msg=1-".$styleBreak;
			else $updateGoTo = $base_url."/index.php?section=list&msg=2";
		}
		  
	 }
	 
	 // Check if mead/cider entry has carbonation and sweetness
	 
	 elseif (check_mead_strength($style[0])) {
		
		if ($_POST['brewMead3'] == "") {
			$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id='%s'", GetSQLValueString($id, "int"));
			mysql_select_db($database, $brewing);
			$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		}
		
		if ($section == "admin") {
			if ($_POST['brewMead3'] == "") $updateGoTo = $base_url."/index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=1-".$styleBreak;
			else $updateGoTo = $base_url."/index.php?section=admin&go=entries&msg=2";
		}
		
		else {
			if ($_POST['brewMead3'] == "") $updateGoTo = $base_url."/index.php?section=brew&action=edit&id=$id&msg=1-".$styleBreak;
			else $updateGoTo = $base_url."/index.php?section=list&msg=2";
		}
	}
	  
	 elseif (check_carb_sweetness($style[0])) {
		 
		if (($_POST['brewMead1'] == "") || ($_POST['brewMead2'] == "")) {
			$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id='%s'", GetSQLValueString($id, "int"));
			mysql_select_db($database, $brewing);
			$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		}
		
		if ($section == "admin") {
			if (($_POST['brewMead1'] == "") || ($_POST['brewMead2'] == "")) $updateGoTo = $base_url."/index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=1-".$styleBreak;
			else $updateGoTo = $base_url."/index.php?section=admin&go=entries&msg=2";
		}
		
		else {
			if (($_POST['brewMead1'] == "") || ($_POST['brewMead2'] == "")) $updateGoTo = $base_url."/index.php?section=brew&action=edit&id=$id&msg=1-".$styleBreak;
			else $updateGoTo = $base_url."/index.php?section=list&msg=2";
		}
		  
	 }
	
	elseif ((check_carb_sweetness($style[0])) && (check_mead_strength($style[0]))) {
		 
		if (($_POST['brewMead1'] == "") || ($_POST['brewMead2'] == "") || ($_POST['brewMead3'] == "")) {
			$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id='%s'", GetSQLValueString($id, "int"));
			mysql_select_db($database, $brewing);
			$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		}
		
		if ($section == "admin") {
			if (($_POST['brewMead1'] == "") || ($_POST['brewMead2'] == "") || ($_POST['brewMead3'] == ""))  $updateGoTo = $base_url."/index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=1-".$styleBreak;
			else $updateGoTo = $base_url."/index.php?section=admin&go=entries&msg=2";
		}
		
		else {
			if (($_POST['brewMead1'] == "") || ($_POST['brewMead2'] == "") || ($_POST['brewMead3'] == ""))  $updateGoTo = $base_url."/index.php?section=brew&action=edit&id=$id&msg=1-".$styleBreak;
			else $updateGoTo = $base_url."/index.php?section=list&msg=2";
		}
		  
	 }
	
	elseif (($style[0] > 28) && ($_POST['brewInfo'] == "")) $updateGoTo = $base_url."/index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=4";
	elseif (($style[0] > 28) && ($_POST['brewInfo'] != "")) $updateGoTo = $base_url."/index.php?section=list&msg=2";
	
	elseif ($section == "admin") $updateGoTo = $base_url."/index.php?section=admin&go=entries&msg=2";
	else $updateGoTo = $base_url."/index.php?section=list&msg=2";

	$pattern = array('\'', '"');
	$updateGoTo = str_replace($pattern, "", $updateGoTo); 
	header(sprintf("Location: %s", stripslashes($updateGoTo)));

} // end if ($action == "edit")

if ($action == "update") {
	
	
	foreach($_POST['id'] as $id) { 
	if ($_POST["brewPaid".$id] == "1") $brewPaid = "1"; else $brewPaid = "0";
	if ($_POST["brewReceived".$id] == "1") $brewReceived = "1"; else $brewReceived = "0";
		$updateSQL = "UPDATE $brewing_db_table SET 
		brewPaid='".$brewPaid."',
		brewReceived='".$brewReceived."'
		WHERE id='".$id.";'"; 
		mysql_select_db($database, $brewing);
		$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());	
		//echo $updateSQL."<br>";
	} 
	//echo $massUpdateGoTo;
	$massUpdateGoTo = $base_url."/index.php?section=admin&go=entries&msg=9";
	$pattern = array('\'', '"');
  	$massUpdateGoTo = str_replace($pattern, "", $massUpdateGoTo); 
	header(sprintf("Location: %s", stripslashes($massUpdateGoTo))); 
} // end if ($action == "update")
?>