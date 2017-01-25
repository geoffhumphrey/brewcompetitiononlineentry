<?php 
/*
 * Module:      process_brewing.inc.php
 * Description: This module does all the heavy lifting for adding entries to the DB
 */

if ((isset($_SESSION['loginUsername'])) && (isset($_SESSION['userLevel']))) { 
	
	include(DB.'common.db.php');
	
	$query_user = sprintf("SELECT userLevel FROM $users_db_table WHERE user_name = '%s'", $_SESSION['loginUsername']);
	$user = mysqli_query($connection,$query_user) or die (mysqli_error($connection));
	$row_user = mysqli_fetch_assoc($user);
	
	
	if ($row_limits['prefsUserEntryLimit'] != "") {
		
		// Check if user has reached the limit of entries in a particular sub-category. If so, redirect.
		$query_brews = sprintf("SELECT COUNT(*) as 'count' FROM $brewing_db_table WHERE brewBrewerId = '%s'", $_SESSION['user_id']);
		$brews = mysqli_query($connection,$query_brews) or die (mysqli_error($connection));
		$row_brews = mysqli_fetch_assoc($brews);
		
			if ($row_brews['count'] >= $row_limits['prefsUserEntryLimit']) {
				$insertGoTo = $base_url."index.php?section=list&msg=8";
				$pattern = array('\'', '"');
				$insertGoTo = str_replace($pattern, "", $insertGoTo); 
				header(sprintf("Location: %s", stripslashes($insertGoTo)));
			}
	
	}
	
	if (($action == "add") || ($action == "edit")) {
		
		include(DB.'styles_special.db.php');
		
		$styleBreak = $_POST['brewStyle'];
		$style = explode('-', $styleBreak);
		if (preg_match("/^[[:digit:]]+$/",$style[0])) $index = sprintf('%02d',$style[0])."-".$style[1];
		else $index = $style[0]."-".$style[1];
		$styleReturn = $index;
		$styleTrim = ltrim($style[0], "0"); 
		if (($style [0] < 10) && (preg_match("/^[[:digit:]]+$/",$style [0]))) $styleFix = "0".$style[0];
		else $styleFix = $style[0];
		
		// Mark as paid if free entry fee
		if ($_SESSION['contestEntryFee'] == 0) $brewPaid = "1"; 
		elseif (isset($_POST['brewPaid'])) $brewPaid = $_POST['brewPaid'];
		else $brewPaid = "0";
		
		$all_special_ing_styles = array_merge($special_beer,$carb_str_sweet_special,$spec_sweet_carb_only,$spec_carb_only);
		//$index = $_POST['brewStyle'];
		$brewName = strtr($_POST['brewName'],$html_remove);
		$brewName = strtr($brewName,$html_string);
		$brewInfo = "";
		
		// Get style name from broken parts
		$query_style_name = sprintf("SELECT * FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s'",$styles_db_table, $_SESSION['prefsStyleSet'],$styleFix,$style[1]);
		$style_name = mysqli_query($connection,$query_style_name) or die (mysqli_error($connection));
		$row_style_name = mysqli_fetch_assoc($style_name);
		
		if (in_array($styleReturn,$all_special_ing_styles)) $brewInfo .= strtr($_POST['brewInfo'],$html_remove);
		if (isset($custom_entry_information)) {
			if (array_key_exists($index,$custom_entry_information)) { 
				$explodies = explode("|",$custom_entry["$index"]);
				if ($explodies[2] == 1) $brewInfo .= strtr($_POST['brewInfo'],$html_remove);
			}
	 	}
		// Process specialized info from form for certain styles
		if ($_SESSION['prefsStyleSet'] == "BJCP2015") {
			
			// Pale or Dark Variant
			if (($index == "09-A") || ($index == "10-C") || ($index == "07-C"))  $brewInfo = $_POST['darkLightColor'];
			
			// IPA strength for 21B (standalone)
			elseif ($index == "21-B") $brewInfo .= "^".$_POST['strengthIPA'];
			
			// IPA strength for other Specialty IPA styles
			elseif ($index == "21-B1") $brewInfo = $row_style_name['brewStyle']."^".$_POST['strengthIPA'];
			elseif ($index == "21-B2") $brewInfo = $row_style_name['brewStyle']."^".$_POST['strengthIPA'];
			elseif ($index == "21-B3") $brewInfo = $row_style_name['brewStyle']."^".$_POST['strengthIPA'];
			elseif ($index == "21-B4") $brewInfo = $row_style_name['brewStyle']."^".$_POST['strengthIPA'];
			elseif ($index == "21-B5") $brewInfo = $row_style_name['brewStyle']."^".$_POST['strengthIPA'];
			elseif ($index == "21-B6") $brewInfo = $row_style_name['brewStyle']."^".$_POST['strengthIPA'];
			
			
			// Fruit Lambic carb/sweetness
			elseif ($index == "23-F") $brewInfo .= "^".$_POST['sweetnessLambic']."^".$_POST['carbLambic'];
			
			// Biere de Garde color
			elseif ($index == "24-C") $brewInfo = $_POST['BDGColor'];
			
			// Saison strength/color
			elseif ($index == "25-B") $brewInfo = $_POST['strengthSaison']."^".$_POST['darkLightColor'];
			
			else $brewInfo .= "";
		}
		
		$brewMead1 = "";
		$brewMead2 = "";
		$brewMead3 = "";
		
		if (isset($_POST['brewMead1'])) $brewMead1 .= $_POST['brewMead1']; // Carbonation
		if (isset($_POST['brewMead2'])) $brewMead2 .= $_POST['brewMead2']; // Sweetness
		if (isset($_POST['brewMead3'])) $brewMead3 .= $_POST['brewMead3']; // Strength
		
		/*
		echo "Carb: ".$brewMead1."<br>";
		echo "Sweet: ".$brewMead2."<br>";
		echo "Strength: ".$brewMead3;
		exit;
		*/
	
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
			$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
			$row_brewer = mysqli_fetch_assoc($brewer);
			
			$brewBrewerID = $row_brewer['uid'];
			$brewBrewerLastName = $row_brewer['brewerLastName'];
			$brewBrewerFirstName = $row_brewer['brewerFirstName'];
	
		}
		else {
			$brewBrewerID = $_POST['brewBrewerID'];
			$brewBrewerLastName = $_POST['brewBrewerLastName']; 
			$brewBrewerFirstName = $_POST['brewBrewerFirstName'];
		}
		
		
					
		if (NHC) $brewJudgingNumber = "";
		else $brewJudgingNumber = generate_judging_num(1,$styleTrim);
		
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
			$brewHopsIBU,
			$brewHopsUse,
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
				for($i=1; $i<=5; $i++) { 
					if (isset($_POST['brewExtract'.$i.'Use'])) $brewExtractUse = $_POST['brewGrain'.$i.'Use']; else $brewExtractUse = "";
					$insertSQL .= GetSQLValueString($brewExtractUse,"text").","; 
				}
				
				for($i=1; $i<=20; $i++) { $insertSQL .= GetSQLValueString($_POST['brewGrain'.$i],"text").","; }
				for($i=1; $i<=20; $i++) { $insertSQL .= GetSQLValueString($_POST['brewGrain'.$i.'Weight'],"text").","; }
				for($i=1; $i<=20; $i++) {
					if (isset($_POST['brewGrain'.$i.'Use'])) $brewGrainUse = $_POST['brewGrain'.$i.'Use']; else $brewGrainUse = "";
					$insertSQL .= GetSQLValueString($brewGrainUse,"text").","; 
				}
				for($i=1; $i<=20; $i++) { $insertSQL .= GetSQLValueString($_POST['brewAddition'.$i],"text").","; }
				for($i=1; $i<=20; $i++) {
					if (isset($_POST['brewAddition'.$i.'Amt'])) $brewAdditionAmt = $_POST['brewAddition'.$i.'Use']; else $brewAdditionAmt = "";
					$insertSQL .= GetSQLValueString($brewAdditionAmt,"text").","; 
					}
				for($i=1; $i<=20; $i++) {
					if (isset($_POST['brewAddition'.$i.'Use'])) $brewAdditionUse = $_POST['brewAddition'.$i.'Use']; else $brewAdditionUse = "";
					$insertSQL .= GetSQLValueString($brewAdditionUse,"text").","; 
				}
				for($i=1; $i<=20; $i++) { $insertSQL .= GetSQLValueString($_POST['brewHops'.$i],"text").","; }
				for($i=1; $i<=20; $i++) { $insertSQL .= GetSQLValueString($_POST['brewHops'.$i.'Weight'],"text").","; }
				for($i=1; $i<=20; $i++) { $insertSQL .= GetSQLValueString($_POST['brewHops'.$i.'IBU'],"text").","; }
				for($i=1; $i<=20; $i++) {
					if (isset($_POST['brewHops'.$i.'Use'])) $brewHopsUse = $_POST['brewHops'.$i.'Use']; else $brewHopsUse = "";
					$insertSQL .= GetSQLValueString($brewHopsUse,"text").","; 
				}
				for($i=1; $i<=20; $i++) { $insertSQL .= GetSQLValueString($_POST['brewHops'.$i.'Time'],"text").",";}
				for($i=1; $i<=20; $i++) { 
					if (isset($_POST['brewHops'.$i.'Type'])) $brewHopsType = $_POST['brewHops'.$i.'Type']; else $brewHopsType = "";
					$insertSQL .= GetSQLValueString($brewHopsType,"text").","; 
				}
				for($i=1; $i<=20; $i++) { 
					if (isset($_POST['brewHops'.$i.'Form'])) $brewHopsForm = $_POST['brewHops'.$i.'Form']; else $brewHopsForm = "";
					$insertSQL .= GetSQLValueString($brewHopsForm,"text").","; 
					}
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
				if (isset($_POST['brewYeastForm'])) $brewYeastForm = $_POST['brewYeastForm']; else $brewYeastForm = "";
				$insertSQL .= GetSQLValueString($brewYeastForm,"text").", ";
				if (isset($_POST['brewYeastType'])) $brewYeastType = $_POST['brewYeastType']; else $brewYeastType = "";
				$insertSQL .= GetSQLValueString($brewYeastType,"text").", "; 
				$insertSQL .= GetSQLValueString($_POST['brewYeastAmount'],"text").", "; 
				if (isset($_POST['brewYeastStarter'])) $brewYeastStarter = $_POST['brewYeastStarter']; else $brewYeastStarter = "";
				$insertSQL .= GetSQLValueString($brewYeastStarter,"text").", "; 
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
				if (isset($_POST['brewCarbonationMethod'])) $brewCarbonationMethod = $_POST['brewCarbonationMethod']; else $brewCarbonationMethod = "";
				$insertSQL .= GetSQLValueString($brewCarbonationMethod,"text").", "; 
				$insertSQL .= GetSQLValueString($_POST['brewCarbonationVol'],"text").", "; 
				$insertSQL .= GetSQLValueString($_POST['brewCarbonationNotes'],"text").", "; 
				$insertSQL .= GetSQLValueString($_POST['brewBoilHours'],"text").", "; 
				$insertSQL .= GetSQLValueString($_POST['brewBoilMins'],"text").", "; 
			}
			
			$insertSQL .= GetSQLValueString($brewBrewerFirstName,"text").", "; 
			$insertSQL .= GetSQLValueString($brewBrewerLastName,"text").", "; 
			$insertSQL .= GetSQLValueString(ucwords($_POST['brewCoBrewer']),"text").", ";
			
			$insertSQL .= GetSQLValueString($brewJudgingNumber,"text").", ";
			$insertSQL .= "NOW( ), ";
			if ($_POST['brewStyle'] == "0-A") $insertSQL .= GetSQLValueString("0","text").", ";
			else $insertSQL .= GetSQLValueString($_POST['brewConfirmed'],"text").", ";
			$insertSQL .= GetSQLValueString($brewPaid,"text").", ";
			$insertSQL .= GetSQLValueString("0","text");
	
			$insertSQL .= ")";
			
		mysqli_real_escape_string($connection,$insertSQL);
		$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));
		
		if ($id == "default") {
			
			$query_brew_id = "SELECT id FROM $brewing_db_table WHERE brewBrewerID='$brewBrewerID' ORDER BY id DESC LIMIT 1";
			$brew_id = mysqli_query($connection,$query_brew_id) or die (mysqli_error($connection));
			$row_brew_id = mysqli_fetch_assoc($brew_id);
			$id = $row_brew_id['id'];
			
		}
		
		if ($section == "admin") {
			
			if ($_POST['brewStyle'] == "0-A") $insertGoTo = $base_url."index.php?section=brew&go=entries&action=edit&filter=".$brewBrewerID."&id=".$id."&view=0-A&msg=4";
			else $insertGoTo = $base_url."index.php?section=admin&go=entries&msg=1";
			
		} 
		
		elseif (($section != "admin") && ($_POST['brewStyle'] == "0-A")) {
			
			$insertGoTo = $base_url."index.php?section=brew&action=edit&id=".$id."&view=0-A&msg=4";
			
		}
		
		else $insertGoTo = $base_url."index.php?section=list&msg=1"; 
		  
		// Check if entry requires special ingredients or a classic style
		if (check_special_ingredients($styleBreak,$_SESSION['prefsStyleSet'])) {
			
			if (empty($brewInfo)) {
				$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id=%s", GetSQLValueString($id, "int"));
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			}
			
			if ($section == "admin") {
				if (empty($brewInfo)) $insertGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=1-".$styleReturn;
				else $insertGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
			}
			
			else {
				if (empty($brewInfo)) $insertGoTo = $base_url."index.php?section=brew&action=edit&id=$id&msg=1-".$styleReturn;
				else $insertGoTo = $base_url."index.php?section=list&msg=2";
			}
			  
		 }
		 
		
		// $brewMead1 - Check if entry style requires carbonation  
		if (check_carb($styleBreak,$_SESSION['prefsStyleSet'])) {
			 
			if (empty($brewMead1)) {
				$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id=%s", GetSQLValueString($id, "int"));
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			}
			
			if ($section == "admin") {
				if ((empty($brewMead1)) || (empty($brewMead2))) $insertGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=1-".$styleReturn;
				else $insertGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
			}
			
			else {
				if (empty($brewMead1)) $insertGoTo = $base_url."index.php?section=brew&action=edit&id=$id&msg=1-".$styleReturn;
				else $insertGoTo = $base_url."index.php?section=list&msg=2";
			}
			  
		 }
		 
		 // $brewMead2 - Check if entry style requires sweetness
		 if (check_sweetness($styleBreak,$_SESSION['prefsStyleSet'])) {
			 
			if (empty($brewMead2)) {
				$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id=%s", GetSQLValueString($id, "int"));
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			}
			
			if ($section == "admin") {
				if (empty($brewMead2)) $insertGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=1-".$styleReturn;
				else $insertGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
			}
			
			else {
				if (empty($brewMead2)) $insertGoTo = $base_url."index.php?section=brew&action=edit&id=$id&msg=1-".$styleReturn;
				else $insertGoTo = $base_url."index.php?section=list&msg=2";
			}
			  
		 }
		 
		// $brewMead3 - Check if entry style requires strength
		 
		if (check_mead_strength($styleBreak,$_SESSION['prefsStyleSet'])) {
			
			if (empty($brewMead3)) {
				$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id=%s", GetSQLValueString($id, "int"));
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			}
			
			if ($section == "admin") {
				if (empty($brewMead3)) $insertGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=1-".$styleReturn;
				else $insertGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
			}
			
			else {
				if (empty($brewMead3)) $insertGoTo = $base_url."index.php?section=brew&action=edit&id=$id&msg=1-".$styleReturn;
				else $insertGoTo = $base_url."index.php?section=list&msg=2";
			}
		}
		
		if ((check_carb($styleBreak,$_SESSION['prefsStyleSet'])) && (check_sweetness($styleBreak,$_SESSION['prefsStyleSet'])) && (check_mead_strength($styleBreak,$_SESSION['prefsStyleSet']))) {
			 
			if ((empty($brewMead1)) || (empty($brewMead2)) || (empty($brewMead3))) {
				$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id=%s", GetSQLValueString($id, "int"));
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			}
			
			if ($section == "admin") {
				if ((empty($brewMead1)) || (empty($brewMead2)) || (empty($brewMead3)))  $insertGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=1-".$styleReturn;
				else $insertGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
			}
			
			else {
				if ((empty($brewMead1)) || (empty($brewMead2)) || (empty($brewMead3))) $insertGoTo = $base_url."index.php?section=brew&action=edit&id=$id&msg=1-".$styleReturn;
				else $insertGoTo = $base_url."index.php?section=list&msg=2";
			}
			  
		 }
		
		// Finally, relocate
		$pattern = array('\'', '"');
		$insertGoTo = str_replace($pattern, "", $insertGoTo); 
		
		//echo $styleTrim."<br>";
		//echo $brewJudgingNumber."<br>";
		//echo $_POST['brewStyle']."<br>";
		//echo $insertSQL."<br>";
		//echo $insertGoTo;
		//exit;
		header(sprintf("Location: %s", stripslashes($insertGoTo)));
		
	} // end if ($action == "add")
	
	if ($action == "edit") {
		
		if ($row_user['userLevel'] <= 1) { 
		
			$name = $_POST['brewBrewerID'];
			
			$query_brewer = sprintf("SELECT * FROM $brewer_db_table WHERE uid = '%s'", $name);
			$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
			$row_brewer = mysqli_fetch_assoc($brewer);
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
		if (preg_match("/^[[:digit:]]+$/",$style[0])) $styleReturn = sprintf('%02d',$style[0])."-".$style[1];
		else $styleReturn = $style[0]."-".$style[1];
		$styleTrim = ltrim($style[0], "0"); 
		
		if (($style [0] < 10) && (preg_match("/^[[:digit:]]+$/",$style [0]))) $styleFix = "0".$style[0];
		else $styleFix = $style[0];
		
		// Get style name from broken parts
		$query_style_name = sprintf("SELECT * FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s'",$styles_db_table,$_SESSION['prefsStyleSet'],$styleFix,$style[1]);
		$style_name = mysqli_query($connection,$query_style_name) or die (mysqli_error($connection));
		$row_style_name = mysqli_fetch_assoc($style_name);
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
			$updateSQL .= "brewCoBrewer=".GetSQLValueString(ucwords($_POST['brewCoBrewer']),"text").", ";	
			$updateSQL .= "brewUpdated="."NOW( ), ";
			$updateSQL .= "brewJudgingNumber=".GetSQLValueString($_POST['brewJudgingNumber'],"text").", ";
			$updateSQL .= "brewPaid=".GetSQLValueString($brewPaid,"text").", ";
			$updateSQL .= "brewConfirmed=".GetSQLValueString($_POST['brewConfirmed'],"text");
			$updateSQL .= " WHERE id ='".$id."'";
		
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		//echo $updateSQL."<br>";
		
		
		// Build updade url
		if ((check_special_ingredients($styleBreak,$_SESSION['prefsStyleSet'])) && ($_POST['brewInfo'] == "")) $updateGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=4";
		elseif ($section == "admin") $updateGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
		else $updateGoTo = $base_url."index.php?section=list&msg=2";
		
		if (($_POST['brewStyle'] == "0-A")) {
			
			$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id=%s", GetSQLValueString($id, "text"));
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			
			$updateGoTo = $base_url."index.php?section=brew&action=edit&id=".$id."&filter=".$filter."&view=0-A&msg=4";
			
		}
		
		// Check if entry requires special ingredients or a classic style, if so, override the $updateGoTo variable with another and redirect
		if (check_special_ingredients($styleBreak,$_SESSION['prefsStyleSet'])) {
			
			if (empty($brewInfo)) {
				$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id=%s", GetSQLValueString($id, "text"));
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			}
			
			if ($section == "admin") {
				if (empty($brewInfo)) $updateGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=1-".$styleReturn;
				else $updateGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
			}
			
			else {
				if (empty($brewInfo)) $updateGoTo = $base_url."index.php?section=brew&action=edit&id=$id&msg=1-".$styleReturn;
				else $updateGoTo = $base_url."index.php?section=list&msg=2";
			}
			  
		 }
		 
		 // Check if mead/cider entry has carbonation and sweetness, if so, override the $updateGoTo variable with another and redirect
		 
		 if (check_carb($styleBreak,$_SESSION['prefsStyleSet'])) {
			 
			if (empty($brewMead1)) {
				$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id=%s", GetSQLValueString($id, "text"));
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			}
			
			if ($section == "admin") {
				if (empty($brewMead1)) $updateGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=1-".$styleReturn;
				else $updateGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
			}
			
			else {
				if (empty($brewMead1)) $updateGoTo = $base_url."index.php?section=brew&action=edit&id=$id&msg=1-".$styleReturn;
				else $updateGoTo = $base_url."index.php?section=list&msg=2";
			}
			  
		 }
		 
		 if (check_sweetness($styleBreak,$_SESSION['prefsStyleSet'])) {
			 
			if (empty($brewMead2)) {
				$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id=%s", GetSQLValueString($id, "text"));
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			}
			
			if ($section == "admin") {
				if (empty($brewMead2)) $updateGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=1-".$styleReturn;
				else $updateGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
			}
			
			else {
				if (empty($brewMead2)) $updateGoTo = $base_url."index.php?section=brew&action=edit&id=$id&msg=1-".$styleReturn;
				else $updateGoTo = $base_url."index.php?section=list&msg=2";
			}
			  
		 }
		 
		 
		 if (check_mead_strength($styleBreak,$_SESSION['prefsStyleSet'])) {
			
			if (empty($brewMead3))  {
				$updateSQL = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id=%s", GetSQLValueString($id, "text"));
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				//echo $updateSQL."<br>";
			}
			
			if ($section == "admin") {
				if (empty($brewMead3)) $updateGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=1-".$styleReturn;
				else $updateGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
			}
			
			else {
				if (empty($brewMead3)) $updateGoTo = $base_url."index.php?section=brew&action=edit&id=$id&msg=1-".$styleReturn;
				else $updateGoTo = $base_url."index.php?section=list&msg=2";
			}
		}
		
		
		
		$pattern = array('\'', '"');
		$updateGoTo = str_replace($pattern, "", $updateGoTo); 
		header(sprintf("Location: %s", stripslashes($updateGoTo)));
		/*
		echo $updateGoTo."<br>";
		echo $style[0]."<br>";
		echo $styleTrim."<br>";
		if (check_mead_strength($styleBreak,$_SESSION['prefsStyleSet'])) echo "YES strength<br>"; else echo "No strength<br>";
		if (check_carb($styleBreak,$_SESSION['prefsStyleSet'])) echo "YES carb<br>"; else echo "No carb/sweeness<br>";
		if (check_sweetness($styleBreak,$_SESSION['prefsStyleSet'])) echo "YES sweetness<br>"; else echo "No carb/sweeness<br>";
		echo $brewMead1."<br>";
		echo $brewMead2."<br>";
		echo $brewMead3."<br>";
		*/
		
	} // end if ($action == "edit")
	
	if ($action == "update") {
		
		foreach($_POST['id'] as $id) { 
			if ((isset($_POST["brewPaid".$id])) && ($_POST["brewPaid".$id] == "1")) $brewPaid = "1"; 
			if (!isset($_POST["brewPaid".$id])) $brewPaid = "0";
			if ((isset($_POST["brewReceived".$id])) && ($_POST["brewReceived".$id] == "1")) $brewReceived = "1"; 
			if (!isset($_POST["brewReceived".$id])) $brewReceived = "0";
		
			$updateSQL = "UPDATE $brewing_db_table SET 
			brewPaid='".$brewPaid."',
			brewReceived='".$brewReceived."',
			brewBoxNum='".$_POST["brewBoxNum".$id]."',
			brewJudgingNumber='".$_POST["brewJudgingNumber".$id]."'
			WHERE id='".$id."'";
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));	

		} 
		//echo $massUpdateGoTo;
		$massUpdateGoTo = $base_url."index.php?section=admin&go=entries&msg=9";
		$pattern = array('\'', '"');
		$massUpdateGoTo = str_replace($pattern, "", $massUpdateGoTo); 
		header(sprintf("Location: %s", stripslashes($massUpdateGoTo))); 
		} // end if ($action == "update")
		
		if ($action == "paid") {
			
			$updateSQL = "UPDATE $brewing_db_table SET brewPaid='1'";
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			
			$massUpdateGoTo = $base_url."index.php?section=admin&go=entries&msg=20";
			$pattern = array('\'', '"');
			$massUpdateGoTo = str_replace($pattern, "", $massUpdateGoTo); 
			header(sprintf("Location: %s", stripslashes($massUpdateGoTo))); 
			
		}
		
		if ($action == "unpaid") {
			
			$updateSQL = "UPDATE $brewing_db_table SET brewPaid='0'";
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			
			$massUpdateGoTo = $base_url."index.php?section=admin&go=entries&msg=34";
			$pattern = array('\'', '"');
			$massUpdateGoTo = str_replace($pattern, "", $massUpdateGoTo); 
			header(sprintf("Location: %s", stripslashes($massUpdateGoTo))); 
			
		}
		
		if ($action == "received") {
			
			$updateSQL = "UPDATE $brewing_db_table SET brewReceived='1'";
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			
			$massUpdateGoTo = $base_url."index.php?section=admin&go=entries&msg=21";
			$pattern = array('\'', '"');
			$massUpdateGoTo = str_replace($pattern, "", $massUpdateGoTo); 
			header(sprintf("Location: %s", stripslashes($massUpdateGoTo))); 
			
		}
		
		if ($action == "not-received") {
			
			$updateSQL = "UPDATE $brewing_db_table SET brewReceived='0'";
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			
			$massUpdateGoTo = $base_url."index.php?section=admin&go=entries&msg=35";
			$pattern = array('\'', '"');
			$massUpdateGoTo = str_replace($pattern, "", $massUpdateGoTo); 
			header(sprintf("Location: %s", stripslashes($massUpdateGoTo))); 
			
		}
		
		if ($action == "confirmed") {
			
			$updateSQL = "UPDATE $brewing_db_table SET brewConfirmed='1'";
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			
			$massUpdateGoTo = $base_url."index.php?section=admin&go=entries&msg=22";
			$pattern = array('\'', '"');
			$massUpdateGoTo = str_replace($pattern, "", $massUpdateGoTo); 
			header(sprintf("Location: %s", stripslashes($massUpdateGoTo))); 
			
		}
	
} else echo "<p>Not available.</p>";
?>