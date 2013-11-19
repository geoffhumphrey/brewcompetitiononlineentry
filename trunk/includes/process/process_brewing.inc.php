<?php 
/*
 * Module:      process_brewing.inc.php
 * Description: This module does all the heavy lifting for adding entries to the DB
 */
 
if ((isset($_SESSION['loginUsername'])) && (isset($_SESSION['userLevel']))) { 

	if (NHC) {
	// Place NHC SQL calls below
	
	
	}
	// end if (NHC)

	else {

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
		
		if (($action == "add") || ($action == "edit")) {
			$brewName = strtr($_POST['brewName'],$html_remove);
			$brewName = strtr($brewName,$html_string);
			$brewInfo = $_POST['brewInfo'];
			$brewInfo = strtr($_POST['brewInfo'],$html_remove);
			
			if (check_carb_sweetness($_POST['brewStyle'])) { 
				$brewMead1 = $_POST['brewMead1']; 
				$brewMead2 = $_POST['brewMead2']; 
				$brewMead3 = $_POST['brewMead3']; 
			} 
			
			else { 
				$brewMead1 = "";
				$brewMead2 = "";
				$brewMead3 = ""; 
			} 
		
		
			// The following are only enabled when preferences dictate that the recipe fields be shown.
			if ($_SESSION['prefsHideRecipe'] == "N") {
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
			}
		}
		
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
			
			$insertSQL = "INSERT INTO $brewing_db_table (";
			if ($_SESSION['prefsHideRecipe'] == "N") { 
				$insertSQL .= "
				$brewExtract,
				$brewExtractWeight,
				$brewExtractUse,
				$brewGrain,
				$brewGrainWeight,
				$brewGrainUse,
				$brewAddition,
				$brewAdditionAmt,
				$brewAdditionUse,
				$brewHops,
				$brewHopsWeight,
				$brewHopsUse,
				$brewHopsIBU,
				$brewHopsTime,
				$brewHopsType,
				$brewHopsForm,
				$brewMashStepName,
				$brewMashStepTemp,
				$brewMashStepTime,
				";
			}
				$insertSQL .= "
				brewName,
				brewStyle,
				brewCategory, 
				brewCategorySort, 
				brewSubCategory,
				";
				
			if ($_SESSION['prefsHideRecipe'] == "N") {
				$insertSQL .= "
				brewBottleDate, 
				brewDate, 
				brewYield,
				brewWinnerCat,
				";
			}
				$insertSQL .= "
				brewInfo, 
				brewMead1, 
				
				brewMead2, 
				brewMead3,
				";
			if ($_SESSION['prefsHideRecipe'] == "N") {	
				$insertSQL .= "
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
				";
			}
				$insertSQL .= "brewComments,"; 
				
			if ($_SESSION['prefsHideRecipe'] == "N") { 
				$insertSQL .= "
				brewFinings, 
				brewWaterNotes,
				";
			}
				$insertSQL .= "brewBrewerID,";
				
			if ($_SESSION['prefsHideRecipe'] == "N") { 
				$insertSQL .= "
				brewCarbonationMethod, 
				brewCarbonationVol, 
				brewCarbonationNotes, 
				brewBoilHours, 
				
				brewBoilMins,
				";
			}
				
				$insertSQL .= "
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
				
				if ($_SESSION['prefsHideRecipe'] == "N") { 
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
				}
					
				$insertSQL .= GetSQLValueString(capitalize($brewName),"text").", ";
				$insertSQL .= GetSQLValueString($row_style_name['brewStyle'],"text").", ";
				$insertSQL .= GetSQLValueString($styleTrim,"text").", "; 
				$insertSQL .= GetSQLValueString($styleFix,"text").", "; 
				$insertSQL .= GetSQLValueString($style[1],"text").", ";
				
				if ($_SESSION['prefsHideRecipe'] == "N") { 
					$insertSQL .= GetSQLValueString($_POST['brewBottleDate'],"text").", ";  
					$insertSQL .= GetSQLValueString($_POST['brewDate'],"text").", "; 
					$insertSQL .= GetSQLValueString($_POST['brewYield'],"text").", ";
					$insertSQL .= GetSQLValueString(round($_POST['brewWinnerCat'],0),"text").", "; // WinnerCat being used for SRM
				}
				
				$insertSQL .= GetSQLValueString($brewInfo,"text").", "; 
				$insertSQL .= GetSQLValueString($brewMead1,"text").", "; 
				$insertSQL .= GetSQLValueString($brewMead2,"text").", "; 
				$insertSQL .= GetSQLValueString($brewMead3,"text").", ";
				
				if ($_SESSION['prefsHideRecipe'] == "N") { 
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
				}
				
				$insertSQL .= GetSQLValueString(strip_newline($_POST['brewComments']),"text").", ";
				
				if ($_SESSION['prefsHideRecipe'] == "N") { 
					$insertSQL .= GetSQLValueString($_POST['brewFinings'],"text").", "; 
					$insertSQL .= GetSQLValueString($_POST['brewWaterNotes'],"text").", ";
				}
				
				$insertSQL .= GetSQLValueString($brewBrewerID,"text").", "; 
				
				if ($_SESSION['prefsHideRecipe'] == "N") { 
					$insertSQL .= GetSQLValueString($_POST['brewCarbonationMethod'],"text").", "; 
					$insertSQL .= GetSQLValueString($_POST['brewCarbonationVol'],"text").", "; 
					$insertSQL .= GetSQLValueString($_POST['brewCarbonationNotes'],"text").", "; 
					$insertSQL .= GetSQLValueString($_POST['brewBoilHours'],"text").", "; 
					$insertSQL .= GetSQLValueString($_POST['brewBoilMins'],"text").", "; 
				}
				
				$insertSQL .= GetSQLValueString($brewBrewerFirstName,"text").", "; 
				$insertSQL .= GetSQLValueString($brewBrewerLastName,"text").", "; 
				$insertSQL .= GetSQLValueString($row_style_name['brewStyleJudgingLoc'],"text").", "; 
				$insertSQL .= GetSQLValueString(ucwords($_POST['brewCoBrewer']),"text").", ";
				
				$insertSQL .= GetSQLValueString($brewJudgingNumber,"text").", ";
				$insertSQL .= "NOW( ), ";
				$insertSQL .= GetSQLValueString($_POST['brewConfirmed'],"text").", ";
				$insertSQL .= GetSQLValueString($brewPaid,"text").", ";
				$insertSQL .= GetSQLValueString("0","text");
		
				$insertSQL .= ")";
			
			mysql_real_escape_string($insertSQL);
			mysql_select_db($database, $brewing);
			$result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
			
			if ($section == "admin") $insertGoTo = $base_url."index.php?section=admin&go=entries&msg=1";
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
					$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id=%s", GetSQLValueString($id, "int"));
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
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
					$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id=%s", GetSQLValueString($id, "int"));
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
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
					$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id=%s", GetSQLValueString($id, "int"));
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
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
					$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id=%s", GetSQLValueString($id, "int"));
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
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
			if ($style[0] < 10) $styleFix = "0".$style[0]; else $styleFix = $style[0];
			
			// Get style name from broken parts
			$query_style_name = "SELECT * FROM $styles_db_table WHERE brewStyleGroup='$styleFix' AND brewStyleNum='$style[1]'";
			$style_name = mysql_query($query_style_name, $brewing) or die(mysql_error());
			$row_style_name = mysql_fetch_assoc($style_name);
			$check = $row_style_name['brewStyleOwn'];
			
			$updateSQL = "UPDATE $brewing_db_table SET ";
				if ($_SESSION['prefsHideRecipe'] == "N") { 
					for($i=1; $i<=5; $i++) { $updateSQL .= "brewExtract".$i."=".GetSQLValueString($_POST['brewExtract'.$i],"text").","; }
					for($i=1; $i<=5; $i++) { $updateSQL .= "brewExtract".$i."Weight=".GetSQLValueString($_POST['brewExtract'.$i.'Weight'],"text").","; }
					for($i=1; $i<=5; $i++) { $updateSQL .= "brewExtract".$i."Use=".GetSQLValueString($_POST['brewExtract'.$i.'Use'],"text").","; }
					
					for($i=1; $i<=20; $i++) { $updateSQL .= "brewGrain".$i."=".GetSQLValueString($_POST['brewGrain'.$i],"text").","; }
					for($i=1; $i<=20; $i++) { $updateSQL .= "brewGrain".$i."Weight=".GetSQLValueString($_POST['brewGrain'.$i.'Weight'],"text").","; }
					for($i=1; $i<=20; $i++) { $updateSQL .= "brewGrain".$i."Use=".GetSQLValueString($_POST['brewGrain'.$i.'Use'],"text").","; }
					
					for($i=1; $i<=20; $i++) { $updateSQL .= "brewAddition".$i."=".GetSQLValueString($_POST['brewAddition'.$i],"text").","; }
					for($i=1; $i<=20; $i++) { $updateSQL .= "brewAddition".$i."Amt=".GetSQLValueString($_POST['brewAddition'.$i.'Amt'],"text").","; }
					for($i=1; $i<=20; $i++) { $updateSQL .= "brewAddition".$i."Use=".GetSQLValueString($_POST['brewAddition'.$i.'Use'],"text").","; }
					
					for($i=1; $i<=20; $i++) { $updateSQL .= "brewHops".$i."=".GetSQLValueString($_POST['brewHops'.$i],"text").","; }
					for($i=1; $i<=20; $i++) { $updateSQL .= "brewHops".$i."Weight=".GetSQLValueString($_POST['brewHops'.$i.'Weight'],"text").","; }
					for($i=1; $i<=20; $i++) { $updateSQL .= "brewHops".$i."IBU=".GetSQLValueString($_POST['brewHops'.$i.'IBU'],"text").","; }
					for($i=1; $i<=20; $i++) { $updateSQL .= "brewHops".$i."Use=".GetSQLValueString($_POST['brewHops'.$i.'Use'],"text").","; }
					for($i=1; $i<=20; $i++) { $updateSQL .= "brewHops".$i."Time=".GetSQLValueString($_POST['brewHops'.$i.'Time'],"text").","; }
					for($i=1; $i<=20; $i++) { $updateSQL .= "brewHops".$i."Type=".GetSQLValueString($_POST['brewHops'.$i.'Type'],"text").","; }
					for($i=1; $i<=20; $i++) { $updateSQL .= "brewHops".$i."Form=".GetSQLValueString($_POST['brewHops'.$i.'Form'],"text").","; }
					
					for($i=1; $i<=10; $i++) { $updateSQL .= "brewMashStep".$i."Name=".GetSQLValueString($_POST['brewMashStep'.$i.'Name'],"text").","; }
					for($i=1; $i<=10; $i++) { $updateSQL .= "brewMashStep".$i."Temp=".GetSQLValueString($_POST['brewMashStep'.$i.'Temp'],"text").","; }
					for($i=1; $i<=10; $i++) { $updateSQL .= "brewMashStep".$i."Time=".GetSQLValueString($_POST['brewMashStep'.$i.'Time'],"text").","; }
					
					$updateSQL .= "brewBottleDate=".GetSQLValueString($_POST['brewBottleDate'],"text").", ";
					$updateSQL .= "brewDate=".GetSQLValueString($_POST['brewDate'],"text").", "; 
					$updateSQL .= "brewYield=".GetSQLValueString($_POST['brewYield'],"text").", ";
					$updateSQL .= "brewWinnerCat=".GetSQLValueString(round($_POST['brewWinnerCat'],0),"text").", "; // WinnerCat being used for SRM
					$updateSQL .= "brewYeast=".GetSQLValueString($_POST['brewYeast'],"text").", "; 
					$updateSQL .= "brewYeastMan=".GetSQLValueString($_POST['brewYeastMan'],"text").", "; 
					$updateSQL .= "brewYeastForm=".GetSQLValueString($_POST['brewYeastForm'],"text").", "; 
					$updateSQL .= "brewYeastType=".GetSQLValueString($_POST['brewYeastType'],"text").", "; 
					$updateSQL .= "brewYeastAmount=". GetSQLValueString($_POST['brewYeastAmount'],"text").", "; 
					$updateSQL .= "brewYeastStarter=". GetSQLValueString($_POST['brewYeastStarter'],"text").", "; 
					$updateSQL .= "brewYeastNutrients=". GetSQLValueString($_POST['brewYeastNutrients'],"text").", "; 
					$updateSQL .= "brewOG=". GetSQLValueString($_POST['brewOG'],"text").", "; 
					$updateSQL .= "brewFG=".GetSQLValueString($_POST['brewFG'],"text").", "; 
					$updateSQL .= "brewPrimary=".GetSQLValueString($_POST['brewPrimary'],"text").", ";
					$updateSQL .= "brewPrimaryTemp=".GetSQLValueString($_POST['brewPrimaryTemp'],"text").", ";
					$updateSQL .= "brewSecondary=".GetSQLValueString($_POST['brewSecondary'],"text").", ";
					$updateSQL .= "brewSecondaryTemp=".GetSQLValueString($_POST['brewSecondaryTemp'],"text").", ";
					$updateSQL .= "brewOther=".GetSQLValueString($_POST['brewOther'],"text").", "; 
					$updateSQL .= "brewOtherTemp=". GetSQLValueString($_POST['brewOtherTemp'],"text").", ";
					$updateSQL .= "brewFinings=".GetSQLValueString($_POST['brewFinings'],"text").", ";  
					$updateSQL .= "brewWaterNotes=". GetSQLValueString($_POST['brewWaterNotes'],"text").", ";
					$updateSQL .= "brewCarbonationMethod=".GetSQLValueString($_POST['brewCarbonationMethod'],"text").", "; 
					$updateSQL .= "brewCarbonationVol=". GetSQLValueString($_POST['brewCarbonationVol'],"text").", ";
					$updateSQL .= "brewCarbonationNotes=". GetSQLValueString($_POST['brewCarbonationNotes'],"text").", "; 
					$updateSQL .= "brewBoilHours=". GetSQLValueString($_POST['brewBoilHours'],"text").", ";
					$updateSQL .= "brewBoilMins=".GetSQLValueString($_POST['brewBoilMins'],"text").", "; 
				}
				
				$updateSQL .= "brewName=".GetSQLValueString(capitalize($brewName),"text").", ";
				$updateSQL .= "brewStyle=".GetSQLValueString($row_style_name['brewStyle'],"text").", ";
				$updateSQL .= "brewCategory=".GetSQLValueString($styleTrim,"text").", "; 
				$updateSQL .= "brewCategorySort=".GetSQLValueString($styleFix,"text").", ";  
				$updateSQL .= "brewSubCategory=".GetSQLValueString($style[1],"text").", ";
				$updateSQL .= "brewInfo=".GetSQLValueString($brewInfo,"text").", "; 
				$updateSQL .= "brewMead1=".GetSQLValueString($brewMead1,"text").", "; 
				$updateSQL .= "brewMead2=".GetSQLValueString($brewMead2,"text").", "; 
				$updateSQL .= "brewMead3=".GetSQLValueString($brewMead3,"text").", ";
				$updateSQL .= "brewComments=". GetSQLValueString(strip_newline($_POST['brewComments']),"text").", "; 
				$updateSQL .= "brewBrewerID=".GetSQLValueString($brewBrewerID,"text").", "; 
				$updateSQL .= "brewBrewerFirstName=". GetSQLValueString($brewBrewerFirstName,"text").", "; 
				$updateSQL .= "brewBrewerLastName=".GetSQLValueString($brewBrewerLastName,"text").", "; 
				$updateSQL .= "brewJudgingLocation=".GetSQLValueString($row_style_name['brewStyleJudgingLoc'],"text").", ";  
				$updateSQL .= "brewCoBrewer=".GetSQLValueString(ucwords($_POST['brewCoBrewer']),"text").", ";	
				$updateSQL .= "brewUpdated="."NOW( ), ";
				$updateSQL .= "brewJudgingNumber=".GetSQLValueString($_POST['brewJudgingNumber'],"text").", ";
				$updateSQL .= "brewConfirmed=".GetSQLValueString($_POST['brewConfirmed'],"text");
				$updateSQL .= " WHERE id ='".$id."'";
			
			mysql_real_escape_string($updateSQL);
			mysql_select_db($database, $brewing);
			$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
			//echo $updateSQL."<br>";
			
			
			// Build updade url
			if ((check_special_ingredients($styleBreak)) && ($_POST['brewInfo'] == "")) $updateGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=4";
			elseif ($section == "admin") $updateGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
			else $updateGoTo = $base_url."index.php?section=list&msg=2";
			
			
			// Check if entry requires special ingredients or a classic style, if so, override the $updateGoTo variable with another and redirect
			if (check_special_ingredients($styleBreak)) {
				  
				if ($_POST['brewInfo'] == "") {
					$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id=%s", GetSQLValueString($id, "text"));
					mysql_real_escape_string($updateSQL);
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
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
					$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id=%s", GetSQLValueString($id, "text"));
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
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
					$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id=%s", GetSQLValueString($id, "text"));
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
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
					$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id=%s", GetSQLValueString($id, "text"));
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
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
			
			if ((NHC) || ($_SESSION['prefsEntryForm'] == "N")) {
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
				mysql_real_escape_string($updateSQL);
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
			
			if ($action == "paid") {
				
				$updateSQL = "UPDATE $brewing_db_table SET brewPaid='1'";
				mysql_select_db($database, $brewing);
				mysql_real_escape_string($updateSQL);
				$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
				
				$massUpdateGoTo = $base_url."index.php?section=admin&go=entries&msg=20";
				$pattern = array('\'', '"');
				$massUpdateGoTo = str_replace($pattern, "", $massUpdateGoTo); 
				header(sprintf("Location: %s", stripslashes($massUpdateGoTo))); 
				
			}
			
			if ($action == "received") {
				
				$updateSQL = "UPDATE $brewing_db_table SET brewReceived='1'";
				mysql_select_db($database, $brewing);
				mysql_real_escape_string($updateSQL);
				$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
				
				$massUpdateGoTo = $base_url."index.php?section=admin&go=entries&msg=21";
				$pattern = array('\'', '"');
				$massUpdateGoTo = str_replace($pattern, "", $massUpdateGoTo); 
				header(sprintf("Location: %s", stripslashes($massUpdateGoTo))); 
				
			}
			
			if ($action == "confirmed") {
				
				$updateSQL = "UPDATE $brewing_db_table SET brewConfirmed='1'";
				mysql_select_db($database, $brewing);
				mysql_real_escape_string($updateSQL);
				$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
				
				$massUpdateGoTo = $base_url."index.php?section=admin&go=entries&msg=22";
				$pattern = array('\'', '"');
				$massUpdateGoTo = str_replace($pattern, "", $massUpdateGoTo); 
				header(sprintf("Location: %s", stripslashes($massUpdateGoTo))); 
				
			}

	} // end else NHC
	
} else echo "<p>Not available.</p>";
?>