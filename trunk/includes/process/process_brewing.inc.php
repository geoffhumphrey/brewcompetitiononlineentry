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
	
	$style = str_replace("-","",$_POST['brewStyle']);
	$style = preg_replace('/[^0-9,]|,[0-9]*$/','',$style);
	
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
	
	$style = str_replace("-","",$_POST['brewStyle']);
	$style = preg_replace('/[^0-9,]|,[0-9]*$/','',$style); 
	
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


if ($row_limits['prefsUserEntryLimit'] != "") {
	
	// Check if user has reached the limit of entries in a particular sub-category. If so, redirect.
	$query_brews = sprintf("SELECT COUNT(*) as 'count' FROM $brewing_db_table WHERE brewBrewerId = '%s'", $_SESSION['user_id']);
	$brews = mysql_query($query_brews, $brewing) or die(mysql_error());
	$row_brews = mysql_fetch_assoc($brews);
	
		if ($row_brews['count'] >= $row_limits['prefsUserEntryLimit']) {
			$insertGoTo = $base_url."index.php?section=list&msg=8";
			$pattern = array('\'', '"');
  			$insertGoTo = str_replace($pattern, "", $insertGoTo); 
  			header(sprintf("Location: %s", stripslashes($insertGoTo)));
		}

}


if (check_special_ingredients($_POST['brewStyle'])) $brewInfo = strip_newline($_POST['brewInfo']);
else $brewInfo = "";

if (check_carb_sweetness($_POST['brewStyle'])) { $brewMead1 = $_POST['brewMead1']; $brewMead2 = $_POST['brewMead2']; $brewMead3 = $_POST['brewMead3']; } 

else { $brewMead1 = ""; $brewMead2 = ""; $brewMead3 = ""; } 

$brewExtract = "";
$brewExtractWeight = "";
$brewExtractUse = "";
for($i=1; $i<=5; $i++) {
	$brewExtract .= "brewExtract".$i.",";
	$brewExtractWeight .= "brewExtract".$i."Weight,";
	$brewExtractUse .= "brewExtract".$i."Use,";
	}
$brewExtract = rtrim($brewExtract,",");
$brewExtractWeight = rtrim($brewExtractWeight,",");
$brewExtractUse = rtrim($brewExtractUse,",");	

$brewGrain = "";
$brewGrainWeight = "";
$brewGrainUse = "";	
for($i=1; $i<=20; $i++) {
	$brewGrain .= "brewGrain".$i.",";
	$brewGrainWeight .= "brewGrain".$i."Weight,";
	$brewGrainUse .= "brewGrain".$i."Use,";
}
$brewGrain = rtrim($brewGrain,",");
$brewGrainWeight = rtrim($brewGrainWeight,",");
$brewGrainUse = rtrim($brewGrainUse,",");	

$brewAddition = "";
$brewAdditionAmt = "";
$brewAdditionUse = "";	
for($i=1; $i<=20; $i++) {
	$brewAddition .= "brewAddition".$i.",";
	$brewAdditionAmt .= "brewAddition".$i."Amt,";
	$brewAdditionUse .= "brewAddition".$i."Use,";
}
$brewAddition = rtrim($brewAddition,",");
$brewAdditionAmt = rtrim($brewAdditionAmt,",");
$brewAdditionUse = rtrim($brewAdditionUse,",");	

$brewHops = "";
$brewHopsWeight = "";
$brewHopsUse = "";	
$brewHopsIBU = "";
$brewHopsTime = "";
$brewHopsType = "";
$brewHopsForm = "";
for($i=1; $i<=20; $i++) {
	$brewHops .= "brewHops".$i.",";
	$brewHopsWeight .= "brewHops".$i."Weight,";
	$brewHopsUse .= "brewHops".$i."Use,";
	$brewHopsIBU .= "brewHops".$i."IBU,";
	$brewHopsTime .= "brewHops".$i."Time,";
	$brewHopsType .= "brewHops".$i."Type,";
	$brewHopsForm .= "brewHops".$i."Form,";
}
$brewHops = rtrim($brewHops,",");
$brewHopsWeight = rtrim($brewHopsWeight,",");
$brewHopsUse = rtrim($brewHopsUse,",");	
$brewHopsIBU = rtrim($brewHopsIBU,",");
$brewHopsTime = rtrim($brewHopsTime,",");
$brewHopsType = rtrim($brewHopsType,",");
$brewHopsForm = rtrim($brewHopsForm,",");

$brewMashStepName = "";
$brewMashStepTemp = "";
$brewMashStepTime = "";
for($i=1; $i<=10; $i++) {
	$brewMashStepName .= "brewMashStep".$i."Name,";
	$brewMashStepTemp .= "brewMashStep".$i."Temp,";
	$brewMashStepTime .= "brewMashStep".$i."Time,";
}
$brewMashStepName = rtrim($brewMashStepName,",");
$brewMashStepTemp = rtrim($brewMashStepTemp,",");
$brewMashStepTime = rtrim($brewMashStepTime,",");


if ($action == "add") {
	
	if ($row_user['userLevel'] <= 1) { 
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
	
	if (NHC) $brewJudgingNumber = "";
	else $brewJudgingNumber = generate_judging_num($styleTrim);
	
	// Get style name from broken parts
	mysql_select_db($database, $brewing);
	$query_style_name = "SELECT * FROM $styles_db_table WHERE brewStyleGroup='$styleFix' AND brewStyleNum='$style[1]'";
	$style_name = mysql_query($query_style_name, $brewing) or die(mysql_error());
	$row_style_name = mysql_fetch_assoc($style_name);
	
	// Mark as paid if free entry fee
	if ($_SESSION['contestEntryFee'] == 0) $brewPaid = "1"; else $brewPaid = "0";
	
	$insertSQL = "
		INSERT INTO $brewing_db_table ($brewExtract,
		$brewExtractWeight,$brewExtractUse,
		$brewGrain,$brewGrainWeight,$brewGrainUse,
		$brewAddition,$brewAdditionAmt,$brewAdditionUse,
		$brewHops,$brewHopsWeight,$brewHopsUse,$brewHopsIBU,$brewHopsTime,$brewHopsType,$brewHopsForm,
		$brewMashStepName,$brewMashStepTemp,$brewMashStepTime,
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
		brewJudgingLocation, 
		brewCoBrewer,
		
		brewJudgingNumber,
		brewUpdated,
		brewConfirmed,
		brewPaid,
		brewReceived
		) VALUES (";
		
		for($i=1; $i<=5; $i++) { $insertSQL .= GetSQLValueString($_POST['brewExtract'.$i],"text").","; }
		for($i=1; $i<=5; $i++) { $insertSQL .= GetSQLValueString($_POST['brewExtract'.$i.'Weight'],"text").","; }
		for($i=1; $i<=5; $i++) { $insertSQL .= GetSQLValueString($_POST['brewExtract'.$i.'Use'],"text").","; }
		
		for($i=1; $i<=20; $i++) { $insertSQL .= GetSQLValueString($_POST['brewGrain'.$i],"text").","; }
		for($i=1; $i<=20; $i++) { $insertSQL .= GetSQLValueString($_POST['brewGrain'.$i.'Weight'],"text").","; }
		for($i=1; $i<=20; $i++) { $insertSQL .= GetSQLValueString($_POST['brewGrain'.$i.'Use'],"text").","; }
		
		for($i=1; $i<=20; $i++) { $insertSQL .= GetSQLValueString($_POST['brewAddition'.$i],"text").","; }
		for($i=1; $i<=20; $i++) { $insertSQL .= GetSQLValueString($_POST['brewAddition'.$i.'Amt'],"text").","; }
		for($i=1; $i<=20; $i++) { $insertSQL .= GetSQLValueString($_POST['brewAddition'.$i.'Use'],"text").","; }
		
		for($i=1; $i<=20; $i++) { $insertSQL .= GetSQLValueString($_POST['brewHops'.$i],"text").","; }
		for($i=1; $i<=20; $i++) { $insertSQL .= GetSQLValueString($_POST['brewHops'.$i.'Weight'],"text").","; }
		for($i=1; $i<=20; $i++) { $insertSQL .= GetSQLValueString($_POST['brewHops'.$i.'IBU'],"text").","; }
		for($i=1; $i<=20; $i++) { $insertSQL .= GetSQLValueString($_POST['brewHops'.$i.'Use'],"text").","; }
		for($i=1; $i<=20; $i++) { $insertSQL .= GetSQLValueString($_POST['brewHops'.$i.'Time'],"text").","; }
		for($i=1; $i<=20; $i++) { $insertSQL .= GetSQLValueString($_POST['brewHops'.$i.'Type'],"text").","; }
		for($i=1; $i<=20; $i++) { $insertSQL .= GetSQLValueString($_POST['brewHops'.$i.'Form'],"text").","; }
		
		for($i=1; $i<=10; $i++) { $insertSQL .= GetSQLValueString($_POST['brewMashStep'.$i.'Name'],"text").","; }
		for($i=1; $i<=10; $i++) { $insertSQL .= GetSQLValueString($_POST['brewMashStep'.$i.'Temp'],"text").","; }
		for($i=1; $i<=10; $i++) { $insertSQL .= GetSQLValueString($_POST['brewMashStep'.$i.'Time'],"text").","; }
		
		$insertSQL .= GetSQLValueString(capitalize($_POST['brewName']),"text").", ";
		$insertSQL .= GetSQLValueString($row_style_name['brewStyle'],"text").", ";
		$insertSQL .= GetSQLValueString($styleTrim,"text").", "; 
		$insertSQL .= GetSQLValueString($styleFix,"text").", "; 
		$insertSQL .= GetSQLValueString($style[1],"text").", ";
		
		$insertSQL .= GetSQLValueString($_POST['brewBottleDate'],"text").", "; 
		$insertSQL .= GetSQLValueString($_POST['brewDate'],"text").", "; 
		$insertSQL .= GetSQLValueString($_POST['brewYield'],"text").", "; 
		$insertSQL .= GetSQLValueString($brewInfo,"text").", "; 
		$insertSQL .= GetSQLValueString($brewMead1,"text").", "; 
		
		$insertSQL .= GetSQLValueString($brewMead2,"text").", "; 
		$insertSQL .= GetSQLValueString($brewMead3,"text").", ";
		$insertSQL .= GetSQLValueString($_POST['brewYeast'],"text").", "; 
		$insertSQL .= GetSQLValueString($_POST['brewYeastMan'],"text").", "; 
		$insertSQL .= GetSQLValueString($_POST['brewYeastForm'],"text").", "; 
		
		$insertSQL .= GetSQLValueString($_POST['brewYeastType'],"text").", "; 
		$insertSQL .= GetSQLValueString($_POST['brewYeastAmount'],"text").", "; 
		$insertSQL .= GetSQLValueString($_POST['brewYeastStarter'],"text").", "; 
		$insertSQL .= GetSQLValueString($_POST['brewYeastNutrients'],"text").", "; 
		$insertSQL .= GetSQLValueString($_POST['brewOG'],"text").", "; 
		
		$insertSQL .= GetSQLValueString($_POST['brewFG'],"text").", "; 
		$insertSQL .= GetSQLValueString($_POST['brewPrimary'],"text").", "; 
		$insertSQL .= GetSQLValueString($_POST['brewPrimaryTemp'],"text").", "; 
		$insertSQL .= GetSQLValueString($_POST['brewSecondary'],"text").", "; 
		$insertSQL .= GetSQLValueString($_POST['brewSecondaryTemp'],"text").", ";
		
		$insertSQL .= GetSQLValueString($_POST['brewOther'],"text").", "; 
		$insertSQL .= GetSQLValueString($_POST['brewOtherTemp'],"text").", "; 
		$insertSQL .= GetSQLValueString(strip_newline($_POST['brewComments']),"text").", "; 
		$insertSQL .= GetSQLValueString($_POST['brewFinings'],"text").", "; 
		$insertSQL .= GetSQLValueString($_POST['brewWaterNotes'],"text").", ";
		
		$insertSQL .= GetSQLValueString($brewBrewerID,"text").", "; 
		$insertSQL .= GetSQLValueString($_POST['brewCarbonationMethod'],"text").", "; 
		$insertSQL .= GetSQLValueString($_POST['brewCarbonationVol'],"text").", "; 
		$insertSQL .= GetSQLValueString($_POST['brewCarbonationNotes'],"text").", "; 
		$insertSQL .= GetSQLValueString($_POST['brewBoilHours'],"text").", "; 
		
		$insertSQL .= GetSQLValueString($_POST['brewBoilMins'],"text").", "; 
		$insertSQL .= GetSQLValueString($brewBrewerFirstName,"text").", "; 
		$insertSQL .= GetSQLValueString($brewBrewerLastName,"text").", "; 
		$insertSQL .= GetSQLValueString($row_style_name['brewStyleJudgingLoc'],"text").", "; 
		$insertSQL .= GetSQLValueString(ucwords($_POST['brewCoBrewer']),"text").", ";
		
		$insertSQL .= GetSQLValueString($brewJudgingNumber,"text").", ";
		$insertSQL .= "NOW( ), ";
		$insertSQL .= GetSQLValueString($_POST['brewConfirmed'],"int").", ";
		$insertSQL .= GetSQLValueString($brewPaid,"int").", ";
		$insertSQL .= GetSQLValueString("0","int");

		$insertSQL .= ")";
	
	mysql_select_db($database, $brewing);
	$Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
	
	if (($style[0] > 28) && ($_POST['brewInfo'] == "")) $insertGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=4";
	elseif (($style[0] > 28) && ($_POST['brewInfo'] != "")) $insertGoTo = $base_url."index.php?section=list&msg=1";
	
	elseif ($section == "admin") $insertGoTo = $base_url."index.php?section=admin&go=entries&msg=1";
	else $insertGoTo = $base_url."index.php?section=list&msg=1"; 
	
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
			if ($_POST['brewInfo'] == "") $insertGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=1-".$styleBreak;
			else $insertGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
		}
		
		else {
			if ($_POST['brewInfo'] == "") $insertGoTo = $base_url."index.php?section=brew&action=edit&id=$id&msg=1-".$styleBreak;
			else $insertGoTo = $base_url."index.php?section=list&msg=2";
		}
		  
	 }
	 
	 // Check if mead/cider entry has carbonation and sweetness
	 
	if (check_mead_strength($style[0])) {
		
		if ($_POST['brewMead3'] == "") {
			$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id='%s'", GetSQLValueString($id, "int"));
			mysql_select_db($database, $brewing);
			$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		}
		
		if ($section == "admin") {
			if ($_POST['brewMead3'] == "") $insertGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=1-".$styleBreak;
			else $insertGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
		}
		
		else {
			if ($_POST['brewMead3'] == "") $insertGoTo = $base_url."index.php?section=brew&action=edit&id=$id&msg=1-".$styleBreak;
			else $insertGoTo = $base_url."index.php?section=list&msg=2";
		}
	}
	  
	if (check_carb_sweetness($style[0])) {
		 
		if (($_POST['brewMead1'] == "") || ($_POST['brewMead2'] == "")) {
			$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id='%s'", GetSQLValueString($id, "int"));
			mysql_select_db($database, $brewing);
			$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		}
		
		if ($section == "admin") {
			if (($_POST['brewMead1'] == "") || ($_POST['brewMead2'] == "")) $insertGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=1-".$styleBreak;
			else $insertGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
		}
		
		else {
			if (($_POST['brewMead1'] == "") || ($_POST['brewMead2'] == "")) $insertGoTo = $base_url."index.php?section=brew&action=edit&id=$id&msg=1-".$styleBreak;
			else $insertGoTo = $base_url."index.php?section=list&msg=2";
		}
		  
	 }
	
	if ((check_carb_sweetness($style[0])) && (check_mead_strength($style[0]))) {
		 
		if (($_POST['brewMead1'] == "") || ($_POST['brewMead2'] == "") || ($_POST['brewMead3'] == "")) {
			$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id='%s'", GetSQLValueString($id, "int"));
			mysql_select_db($database, $brewing);
			$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		}
		
		if ($section == "admin") {
			if (($_POST['brewMead1'] == "") || ($_POST['brewMead2'] == "") || ($_POST['brewMead3'] == ""))  $insertGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=1-".$styleBreak;
			else $insertGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
		}
		
		else {
			if (($_POST['brewMead1'] == "") || ($_POST['brewMead2'] == "") || ($_POST['brewMead3'] == ""))  $insertGoTo = $base_url."index.php?section=brew&action=edit&id=$id&msg=1-".$styleBreak;
			else $insertGoTo = $base_url."index.php?section=list&msg=2";
		}
		  
	 }
	
	// Finally, relocate
	$pattern = array('\'', '"');
  	$insertGoTo = str_replace($pattern, "", $insertGoTo); 
  	header(sprintf("Location: %s", stripslashes($insertGoTo)));
	
} // end if ($action == "add")

if ($action == "edit") {
	
	
	
	if ($row_user['userLevel'] <= 1) { 
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
	$query_style_name = "SELECT * FROM $styles_db_table WHERE brewStyleGroup='$styleFix' AND brewStyleNum='$style[1]'";
	$style_name = mysql_query($query_style_name, $brewing) or die(mysql_error());
	$row_style_name = mysql_fetch_assoc($style_name);
	$check = $row_style_name['brewStyleOwn'];
	
	$insertSQL = "UPDATE $brewing_db_table SET ";
		
		for($i=1; $i<=5; $i++) { $insertSQL .= "brewExtract".$i."=".GetSQLValueString($_POST['brewExtract'.$i],"text").","; }
		for($i=1; $i<=5; $i++) { $insertSQL .= "brewExtract".$i."Weight=".GetSQLValueString($_POST['brewExtract'.$i.'Weight'],"text").","; }
		for($i=1; $i<=5; $i++) { $insertSQL .= "brewExtract".$i."Use=".GetSQLValueString($_POST['brewExtract'.$i.'Use'],"text").","; }
		
		for($i=1; $i<=20; $i++) { $insertSQL .= "brewGrain".$i."=".GetSQLValueString($_POST['brewGrain'.$i],"text").","; }
		for($i=1; $i<=20; $i++) { $insertSQL .= "brewGrain".$i."Weight=".GetSQLValueString($_POST['brewGrain'.$i.'Weight'],"text").","; }
		for($i=1; $i<=20; $i++) { $insertSQL .= "brewGrain".$i."Use=".GetSQLValueString($_POST['brewGrain'.$i.'Use'],"text").","; }
		
		for($i=1; $i<=20; $i++) { $insertSQL .= "brewAddition".$i."=".GetSQLValueString($_POST['brewAddition'.$i],"text").","; }
		for($i=1; $i<=20; $i++) { $insertSQL .= "brewAddition".$i."Amt=".GetSQLValueString($_POST['brewAddition'.$i.'Amt'],"text").","; }
		for($i=1; $i<=20; $i++) { $insertSQL .= "brewAddition".$i."Use=".GetSQLValueString($_POST['brewAddition'.$i.'Use'],"text").","; }
		
		for($i=1; $i<=20; $i++) { $insertSQL .= "brewHops".$i."=".GetSQLValueString($_POST['brewHops'.$i],"text").","; }
		for($i=1; $i<=20; $i++) { $insertSQL .= "brewHops".$i."Weight=".GetSQLValueString($_POST['brewHops'.$i.'Weight'],"text").","; }
		for($i=1; $i<=20; $i++) { $insertSQL .= "brewHops".$i."IBU=".GetSQLValueString($_POST['brewHops'.$i.'IBU'],"text").","; }
		for($i=1; $i<=20; $i++) { $insertSQL .= "brewHops".$i."Use=".GetSQLValueString($_POST['brewHops'.$i.'Use'],"text").","; }
		for($i=1; $i<=20; $i++) { $insertSQL .= "brewHops".$i."Time=".GetSQLValueString($_POST['brewHops'.$i.'Time'],"text").","; }
		for($i=1; $i<=20; $i++) { $insertSQL .= "brewHops".$i."Type=".GetSQLValueString($_POST['brewHops'.$i.'Type'],"text").","; }
		for($i=1; $i<=20; $i++) { $insertSQL .= "brewHops".$i."Form=".GetSQLValueString($_POST['brewHops'.$i.'Form'],"text").","; }
		
		for($i=1; $i<=10; $i++) { $insertSQL .= "brewMashStep".$i."Name=".GetSQLValueString($_POST['brewMashStep'.$i.'Name'],"text").","; }
		for($i=1; $i<=10; $i++) { $insertSQL .= "brewMashStep".$i."Temp=".GetSQLValueString($_POST['brewMashStep'.$i.'Temp'],"text").","; }
		for($i=1; $i<=10; $i++) { $insertSQL .= "brewMashStep".$i."Time=".GetSQLValueString($_POST['brewMashStep'.$i.'Time'],"text").","; }
		
		$insertSQL .= "brewName=".GetSQLValueString(capitalize($_POST['brewName']),"scrubbed").", ";
		$insertSQL .= "brewStyle=".GetSQLValueString($row_style_name['brewStyle'],"text").", ";
		$insertSQL .= "brewCategory=".GetSQLValueString($styleTrim,"text").", "; 
		$insertSQL .= "brewCategorySort=".GetSQLValueString($styleFix,"text").", ";  
		$insertSQL .= "brewSubCategory=".GetSQLValueString($style[1],"text").", ";
		
		$insertSQL .= "brewBottleDate=".GetSQLValueString($_POST['brewBottleDate'],"text").", ";
		$insertSQL .= "brewDate=".GetSQLValueString($_POST['brewDate'],"text").", "; 
		$insertSQL .= "brewYield=".GetSQLValueString($_POST['brewYield'],"text").", ";
		// WinnerCat being used for SRM
		$insertSQL .= "brewWinnerCat=".GetSQLValueString($_POST['brewWinnerCat'],"text").", ";
		$insertSQL .= "brewInfo=".GetSQLValueString($brewInfo,"text").", "; 
		$insertSQL .= "brewMead1=".GetSQLValueString($brewMead1,"text").", "; 
		
		$insertSQL .= "brewMead2=".GetSQLValueString($brewMead2,"text").", "; 
		$insertSQL .= "brewMead3=".GetSQLValueString($brewMead3,"text").", ";
		$insertSQL .= "brewYeast=".GetSQLValueString($_POST['brewYeast'],"text").", "; 
		$insertSQL .= "brewYeastMan=".GetSQLValueString($_POST['brewYeastMan'],"text").", "; 
		$insertSQL .= "brewYeastForm=".GetSQLValueString($_POST['brewYeastForm'],"text").", "; 

		$insertSQL .= "brewYeastType=".GetSQLValueString($_POST['brewYeastType'],"text").", "; 
		$insertSQL .= "brewYeastAmount=". GetSQLValueString($_POST['brewYeastAmount'],"text").", "; 
		$insertSQL .= "brewYeastStarter=". GetSQLValueString($_POST['brewYeastStarter'],"text").", "; 
		$insertSQL .= "brewYeastNutrients=". GetSQLValueString($_POST['brewYeastNutrients'],"text").", "; 
		$insertSQL .= "brewOG=". GetSQLValueString($_POST['brewOG'],"text").", "; 
		
		$insertSQL .= "brewFG=".GetSQLValueString($_POST['brewFG'],"text").", "; 
		$insertSQL .= "brewPrimary=".GetSQLValueString($_POST['brewPrimary'],"text").", ";
		$insertSQL .= "brewPrimaryTemp=".GetSQLValueString($_POST['brewPrimaryTemp'],"text").", ";
		$insertSQL .= "brewSecondary=".GetSQLValueString($_POST['brewSecondary'],"text").", ";
		$insertSQL .= "brewSecondaryTemp=".GetSQLValueString($_POST['brewSecondaryTemp'],"text").", ";
		
		$insertSQL .= "brewOther=".GetSQLValueString($_POST['brewOther'],"text").", "; 
		$insertSQL .= "brewOtherTemp=". GetSQLValueString($_POST['brewOtherTemp'],"text").", ";
		$insertSQL .= "brewComments=". GetSQLValueString(strip_newline($_POST['brewComments']),"text").", "; 
		$insertSQL .= "brewFinings=".GetSQLValueString($_POST['brewFinings'],"text").", ";  
		$insertSQL .= "brewWaterNotes=". GetSQLValueString($_POST['brewWaterNotes'],"text").", ";
		
		$insertSQL .= "brewBrewerID=".GetSQLValueString($brewBrewerID,"text").", "; 
		$insertSQL .= "brewCarbonationMethod=".GetSQLValueString($_POST['brewCarbonationMethod'],"text").", "; 
		$insertSQL .= "brewCarbonationVol=". GetSQLValueString($_POST['brewCarbonationVol'],"text").", ";
		$insertSQL .= "brewCarbonationNotes=". GetSQLValueString($_POST['brewCarbonationNotes'],"text").", "; 
		$insertSQL .= "brewBoilHours=". GetSQLValueString($_POST['brewBoilHours'],"text").", ";
				
		$insertSQL .= "brewBoilMins=".GetSQLValueString($_POST['brewBoilMins'],"text").", "; 
		$insertSQL .= "brewBrewerFirstName=". GetSQLValueString($brewBrewerFirstName,"text").", "; 
		$insertSQL .= "brewBrewerLastName=".GetSQLValueString($brewBrewerLastName,"text").", "; 
		$insertSQL .= "brewJudgingLocation=".GetSQLValueString($row_style_name['brewStyleJudgingLoc'],"text").", ";  
		$insertSQL .= "brewCoBrewer=".GetSQLValueString(ucwords($_POST['brewCoBrewer']),"text").", ";	
		
		$insertSQL .= "brewUpdated="."NOW( ), ";
		$insertSQL .= "brewConfirmed=".GetSQLValueString($_POST['brewConfirmed'],"int");

		$insertSQL .= " WHERE id ='".$id."'";
	
	mysql_select_db($database, $brewing);
	$Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
	
	
	// Build updade url
	if (($style[0] > 28) && ($_POST['brewInfo'] == "")) $updateGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=4";
	elseif (($style[0] > 28) && ($_POST['brewInfo'] != "")) $updateGoTo = $base_url."index.php?section=list&msg=2";
	elseif ($section == "admin") $updateGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
	else $updateGoTo = $base_url."index.php?section=list&msg=2";
	
	if (!NHC) {  // NOT for NHC
		// Check if style has been changed. If so, regenerate judging number.
		mysql_select_db($database, $brewing);
		$query_style_changed = "SELECT brewCategory,brewSubCategory FROM $brewing_db_table WHERE id='$id'";
		$style_changed = mysql_query($query_style_changed, $brewing) or die(mysql_error());
		$row_style_changed = mysql_fetch_assoc($style_changed);
		
		$style_previous = $row_style_changed['brewCategory']."-".$row_style_changed['brewSubCategory'];
		if ($style_previous != $styleBreak) $new_judging_number = TRUE; else $new_judging_number = FALSE;
		
		if ($new_judging_number) {
			$judging_number = generate_judging_num($styleTrim);
			$updateSQL = sprintf("UPDATE $brewing_db_table SET brewJudgingNumber='%s' WHERE id=%s", $judging_number, GetSQLValueString($id, "int"));
			mysql_select_db($database, $brewing);
			$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		}
	}
	
	
	// Check if entry requires special ingredients or a classic style, if so, override the $updateGoTo variable with another and redirect
	if (check_special_ingredients($styleBreak)) {
		  
		if ($_POST['brewInfo'] == "") {
			$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id=%s", GetSQLValueString($id, "int"));
			mysql_select_db($database, $brewing);
			$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		}
		
		if ($section == "admin") {
			if ($_POST['brewInfo'] == "") $updateGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=1-".$styleBreak;
			else $updateGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
		}
		
		else {
			if ($_POST['brewInfo'] == "") $updateGoTo = $base_url."index.php?section=brew&action=edit&id=$id&msg=1-".$styleBreak;
			else $updateGoTo = $base_url."index.php?section=list&msg=2";
		}
		  
	 }
	 
	 // Check if mead/cider entry has carbonation and sweetness, if so, override the $updateGoTo variable with another and redirect
	 
	 if (check_mead_strength($style[0])) {
		
		if ($_POST['brewMead3'] == "") {
			$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id=%s", GetSQLValueString($id, "int"));
			mysql_select_db($database, $brewing);
			$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
			//echo $updateSQL."<br>";
		}
		
		if ($section == "admin") {
			if ($_POST['brewMead3'] != "")$updateGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
			else $updateGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=1-".$styleBreak;
		}
		
		else {
			if ($_POST['brewMead3'] != "") $updateGoTo = $base_url."index.php?section=list&msg=2";
			else $updateGoTo = $base_url."index.php?section=brew&action=edit&id=$id&msg=1-".$styleBreak;
		}
	}
	  
	 if (check_carb_sweetness($style[0])) {
		 
		if (($_POST['brewMead1'] == "") || ($_POST['brewMead2'] == "")) {
			$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id=%s", GetSQLValueString($id, "int"));
			mysql_select_db($database, $brewing);
			$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		}
		
		if ($section == "admin") {
			if (($_POST['brewMead1'] == "") || ($_POST['brewMead2'] == "")) $updateGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=1-".$styleBreak;
			else $updateGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
		}
		
		else {
			if (($_POST['brewMead1'] == "") || ($_POST['brewMead2'] == "")) $updateGoTo = $base_url."index.php?section=brew&action=edit&id=$id&msg=1-".$styleBreak;
			else $updateGoTo = $base_url."index.php?section=list&msg=2";
		}
		  
	 }
	
	// Check if mead/cider entry has carbonation and sweetness, if so, override the $updateGoTo variable with another and redirect
	if ((check_carb_sweetness($style[0])) && (check_mead_strength($style[0]))) {
		 
		if (($_POST['brewMead1'] == "") || ($_POST['brewMead2'] == "") || ($_POST['brewMead3'] == "")) {
			$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id=%s", GetSQLValueString($id, "int"));
			mysql_select_db($database, $brewing);
			$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		}
		
		if ($section == "admin") {
			if (($_POST['brewMead1'] == "") || ($_POST['brewMead2'] == "") || ($_POST['brewMead3'] == ""))  $updateGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=1-".$styleBreak;
			else $updateGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
		}
		
		else {
			if (($_POST['brewMead1'] == "") || ($_POST['brewMead2'] == "") || ($_POST['brewMead3'] == ""))  $updateGoTo = $base_url."index.php?section=brew&action=edit&id=$id&msg=1-".$styleBreak;
			else $updateGoTo = $base_url."index.php?section=list&msg=2";
		}
		  
	 }
	
	
	//echo $updateGoTo."<br>";
	//echo $style[0]."<br>";
	//if ($_POST['brewMead3'] == "") echo "YES - strength is empty<br>";
	//if (check_mead_strength($style[0])) echo "YES - check strength<br>";
	
	$pattern = array('\'', '"');
	$updateGoTo = str_replace($pattern, "", $updateGoTo); 
	header(sprintf("Location: %s", stripslashes($updateGoTo)));
	
	
} // end if ($action == "edit")

if ($action == "update") {
	
	foreach($_POST['id'] as $id) { 
	if ($_POST["brewPaid".$id] == "1") $brewPaid = "1"; else $brewPaid = "0";
	if ($_POST["brewReceived".$id] == "1") $brewReceived = "1"; else $brewReceived = "0";
	
	if (NHC) {
		$updateSQL = "UPDATE $brewing_db_table SET 
		brewPaid='".$brewPaid."',
		brewReceived='".$brewReceived."',
		brewBoxNum='".$_POST["brewBoxNum".$id]."',
		brewJudgingNumber='".$_POST["brewJudgingNumber".$id]."'
		WHERE id='".$id."'";
		mysql_select_db($database, $brewing);
		$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());	
		//echo $updateSQL."<br>";
		}
	
	else {
		$updateSQL = "UPDATE $brewing_db_table SET 
		brewPaid='".$brewPaid."',
		brewReceived='".$brewReceived."',
		brewBoxNum='".$_POST["brewBoxNum".$id]."'
		WHERE id='".$id."'";
		mysql_select_db($database, $brewing);
		$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());	
		//echo $updateSQL."<br>";
	}
	} 
	//echo $massUpdateGoTo;
	$massUpdateGoTo = $base_url."index.php?section=admin&go=entries&msg=9";
	$pattern = array('\'', '"');
  	$massUpdateGoTo = str_replace($pattern, "", $massUpdateGoTo); 
	header(sprintf("Location: %s", stripslashes($massUpdateGoTo))); 
} // end if ($action == "update")
?>