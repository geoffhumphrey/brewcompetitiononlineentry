<?php 
/*
 * Module:      process_brewing.inc.php
 * Description: This module does all the heavy lifting for adding entries to the DB
 */

// if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && (isset($_SESSION['userLevel'])))) echo "YES"; else echo "NO"; exit;


if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && (isset($_SESSION['userLevel'])))) { 
	
	include (DB.'common.db.php');
	
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
		
		if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) include (INCLUDES.'ba_constants.inc.php');
		
		include (DB.'styles_special.db.php');
		
		// Comments
		$brewComments = "";
		if (isset($_POST['brewComments'])) $brewComments .= strip_tags($_POST['brewComments']);
		
		// Co Brewer
		$brewCoBrewer = "";
		if (isset($_POST['brewCoBrewer'])) $brewCoBrewer .= strip_tags($_POST['brewCoBrewer']);
		
		// Style
		$styleBreak = $_POST['brewStyle'];
				
		$style = explode('-', $styleBreak);
		if (preg_match("/^[[:digit:]]+$/",$style[0])) $index = sprintf('%02d',$style[0])."-".$style[1];
		else $index = $style[0]."-".$style[1];
		$styleReturn = $index;
		$styleTrim = ltrim($style[0], "0"); 
		if (($style [0] < 10) && (preg_match("/^[[:digit:]]+$/",$style [0]))) $styleFix = "0".$style[0];
		else $styleFix = $style[0];
		$styleID = $style[1];
		
		// Style Name
		$styleName = "";
		
		// Get style name from broken parts if BA (currently there are 14 overall BA categories, 34 BJCP 2015, and 28 BJCP 2007)
		// Custom style overall category will always be greater than 28
		if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) {
			
			if ($style[0] > 28) {
				$query_style_name = sprintf("SELECT * FROM %s WHERE brewStyleOwn='custom' AND brewStyleGroup='%s' AND brewStyleNum='%s'",$styles_db_table,$styleFix,$style[1]);
				$style_name = mysqli_query($connection,$query_style_name) or die (mysqli_error($connection));
				$row_style_name = mysqli_fetch_assoc($style_name);
				$styleName = $row_style_name['brewStyle'];
			}
			
			else $styleName = $_SESSION['styles']['data'][$style[1]-1]['name'];
		}
		
		// Get style name from broken parts if BJCP
		else { 
			$query_style_name = sprintf("SELECT * FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s'",$styles_db_table,$_SESSION['prefsStyleSet'],$styleFix,$style[1]);
			$style_name = mysqli_query($connection,$query_style_name) or die (mysqli_error($connection));
			$row_style_name = mysqli_fetch_assoc($style_name);
			$styleName = $row_style_name['brewStyle'];
		}
		
		// Mark as paid if free entry fee
		if ($_SESSION['contestEntryFee'] == 0) $brewPaid = "1"; 
		elseif (isset($_POST['brewPaid'])) $brewPaid = $_POST['brewPaid'];
		else $brewPaid = "0";
		
		$all_special_ing_styles = array();
		
		if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) {
			if (is_array($special_beer)) $all_special_ing_styles = array_merge($all_special_ing_styles,$special_beer);
			if (is_array($carb_str_sweet_special)) $all_special_ing_styles = array_merge($all_special_ing_styles,$carb_str_sweet_special);
			if (is_array($spec_sweet_carb_only)) $all_special_ing_styles = array_merge($all_special_ing_styles,$spec_sweet_carb_only);
			if (is_array($spec_carb_only)) $all_special_ing_styles = array_merge($all_special_ing_styles,$spec_carb_only);
		}
		else { 
			$all_special_ing_styles = array_merge($all_special_ing_styles,$ba_special_ids);
		}
		
		$brewName = $_POST['brewName'];
		$brewName = strip_tags($brewName);
		$brewName = filter_var($brewName,FILTER_SANITIZE_STRING);
		$brewName = strtolower($brewName);
		$brewName = ucwords($brewName);
		
		// Special Ingredients
		$brewInfo = "";
		
		if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) {
			if (in_array($styleReturn,$all_special_ing_styles)) $brewInfo .= strip_tags($_POST['brewInfo']);
		}
		else {
			if (in_array($style[1],$all_special_ing_styles)) $brewInfo .= strip_tags($_POST['brewInfo']);
			//echo $style[1]; print_r($all_special_ing_styles); echo $brewInfo; exit;
		}
		if (is_array($custom_entry_information)) {
			if (array_key_exists($index,$custom_entry_information)) { 
				$explodies = explode("|",$custom_entry["$index"]);
				if ($explodies[2] == 1) $brewInfo .= strip_tags($_POST['brewInfo']);
			}
	 	}
		
		// Optional Ingredients
		$brewInfoOptional = "";
		
		// Process specialized info from form for certain styles
		if ($_SESSION['prefsStyleSet'] == "BJCP2015") {
			
			if (!isset($_POST['brewInfoOptional'])) {
				$brewInfoOptional = strip_tags($_POST['brewInfoOptional']);
				$brewInfoOptional = filter_var($brewInfoOptional,FILTER_SANITIZE_STRING);
			}
			
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
		
		$brewInfo = strip_tags($brewInfo);
		$brewInfo = filter_var($brewInfo,FILTER_SANITIZE_STRING);
		
		$brewMead1 = "";
		$brewMead2 = "";
		$brewMead3 = "";
		
		if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) {
			
			// check if style requires strength, carbonation, and/or sweetness
			$query_str_carb_sweet = sprintf("SELECT * FROM %s WHERE brewStyleGroup='%s' AND brewStyleNum='%s'", $styles_db_table,$index,$style[1]);
			$str_carb_sweet = mysqli_query($connection,$query_str_carb_sweet) or die (mysqli_error($connection));
			$row_str_carb_sweet = mysqli_fetch_assoc($str_carb_sweet);
			$totalRows_str_carb_sweet = mysqli_num_rows($str_carb_sweet);
			
			if ($totalRows_str_carb_sweet > 0) {
				
				if ((isset($_POST['brewMead1'])) && ($row_str_carb_sweet['brewStyleCarb'] == 1)) $brewMead1 .= $_POST['brewMead1']; // Carbonation
				if ((isset($_POST['brewMead2'])) && ($row_str_carb_sweet['brewStyleSweet'] == 1))  $brewMead2 .= $_POST['brewMead2']; // Sweetness
				if ((isset($_POST['brewMead3'])) && ($row_str_carb_sweet['brewStyleStrength'] == 1))  $brewMead3 .= $_POST['brewMead3']; // Strength
				
			}
				
			
		}
		
		else {
			
			if ((isset($_POST['brewMead1'])) && (in_array($style[1],$ba_carb_ids))) $brewMead1 .= $_POST['brewMead1']; // Carbonation
			if ((isset($_POST['brewMead2'])) && (in_array($style[1],$ba_sweetness_ids)))  $brewMead2 .= $_POST['brewMead2']; // Sweetness
			if ((isset($_POST['brewMead3'])) && (in_array($style[1],$ba_strength_ids)))  $brewMead3 .= $_POST['brewMead3']; // Strength
			
		}
		
		
		
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
		
		$brewJudgingNumber = "";
		
		$files = array_slice(scandir(USER_DOCS), 2);
		$judging_number_looper = TRUE;
			
		while($judging_number_looper) {
		
			$generated_judging_number = generate_judging_num(1,$styleTrim);
			$scoresheet_file_name_judging = $generated_judging_number.".pdf";
		
			if (!in_array($scoresheet_file_name_judging,$files))  { 
				$brewJudgingNumber = $generated_judging_number;
				$judging_number_looper = FALSE;
			}
			
			else {
				$judging_number_looper = TRUE;
			}
			
		}
			
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
			brewReceived,
			brewInfoOptional
			) VALUES (";
			
			if ($_SESSION['prefsHideRecipe'] == "N") { 
				for($i=1; $i<=5; $i++) { $insertSQL .= GetSQLValueString(filter_var($_POST['brewExtract'.$i],FILTER_SANITIZE_STRING),"text").","; }
				for($i=1; $i<=5; $i++) { $insertSQL .= GetSQLValueString(filter_var($_POST['brewExtract'.$i.'Weight'],FILTER_SANITIZE_STRING),"text").","; }
				for($i=1; $i<=5; $i++) { 
					if (isset($_POST['brewExtract'.$i.'Use'])) $brewExtractUse = $_POST['brewGrain'.$i.'Use']; else $brewExtractUse = "";
					$insertSQL .= GetSQLValueString($brewExtractUse,"text").","; 
				}
				
				for($i=1; $i<=20; $i++) { $insertSQL .= GetSQLValueString(filter_var($_POST['brewGrain'.$i],FILTER_SANITIZE_STRING),"text").","; }
				for($i=1; $i<=20; $i++) { $insertSQL .= GetSQLValueString(filter_var($_POST['brewGrain'.$i.'Weight'],FILTER_SANITIZE_STRING),"text").","; }
				for($i=1; $i<=20; $i++) {
					if (isset($_POST['brewGrain'.$i.'Use'])) $brewGrainUse = $_POST['brewGrain'.$i.'Use']; else $brewGrainUse = "";
					$insertSQL .= GetSQLValueString($brewGrainUse,"text").","; 
				}
				for($i=1; $i<=20; $i++) { $insertSQL .= GetSQLValueString(filter_var($_POST['brewAddition'.$i],FILTER_SANITIZE_STRING),"text").","; }
				for($i=1; $i<=20; $i++) {
					if (isset($_POST['brewAddition'.$i.'Amt'])) $brewAdditionAmt = $_POST['brewAddition'.$i.'Amt']; else $brewAdditionAmt = "";
					$insertSQL .= GetSQLValueString($brewAdditionAmt,"text").","; 
					}
				for($i=1; $i<=20; $i++) {
					if (isset($_POST['brewAddition'.$i.'Use'])) $brewAdditionUse = $_POST['brewAddition'.$i.'Use']; else $brewAdditionUse = "";
					$insertSQL .= GetSQLValueString($brewAdditionUse,"text").","; 
				}
				for($i=1; $i<=20; $i++) { $insertSQL .= GetSQLValueString(filter_var($_POST['brewHops'.$i],FILTER_SANITIZE_STRING),"text").","; }
				for($i=1; $i<=20; $i++) { $insertSQL .= GetSQLValueString(filter_var($_POST['brewHops'.$i.'Weight'],FILTER_SANITIZE_STRING),"text").","; }
				for($i=1; $i<=20; $i++) { $insertSQL .= GetSQLValueString($_POST['brewHops'.$i.'IBU'],"text").","; }
				for($i=1; $i<=20; $i++) {
					if (isset($_POST['brewHops'.$i.'Use'])) $brewHopsUse = $_POST['brewHops'.$i.'Use']; else $brewHopsUse = "";
					$insertSQL .= GetSQLValueString($brewHopsUse,"text").","; 
				}
				for($i=1; $i<=20; $i++) { $insertSQL .= GetSQLValueString(filter_var($_POST['brewHops'.$i.'Time'],FILTER_SANITIZE_STRING),"text").",";}
				for($i=1; $i<=20; $i++) { 
					if (isset($_POST['brewHops'.$i.'Type'])) $brewHopsType = $_POST['brewHops'.$i.'Type']; else $brewHopsType = "";
					$insertSQL .= GetSQLValueString($brewHopsType,"text").","; 
				}
				for($i=1; $i<=20; $i++) { 
					if (isset($_POST['brewHops'.$i.'Form'])) $brewHopsForm = $_POST['brewHops'.$i.'Form']; else $brewHopsForm = "";
					$insertSQL .= GetSQLValueString($brewHopsForm,"text").","; 
					}
				for($i=1; $i<=10; $i++) { $insertSQL .= GetSQLValueString(filter_var($_POST['brewMashStep'.$i.'Name'],FILTER_SANITIZE_STRING),"text").","; }
				for($i=1; $i<=10; $i++) { $insertSQL .= GetSQLValueString($_POST['brewMashStep'.$i.'Temp'],"text").","; }
				for($i=1; $i<=10; $i++) { $insertSQL .= GetSQLValueString($_POST['brewMashStep'.$i.'Time'],"text").","; }
			}
				
			$insertSQL .= GetSQLValueString(capitalize($brewName),"text").", ";
			$insertSQL .= GetSQLValueString($styleName,"text").", ";
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
				$insertSQL .= GetSQLValueString(filter_var($_POST['brewYeast'],FILTER_SANITIZE_STRING),"text").", "; 
				$insertSQL .= GetSQLValueString($_POST['brewYeastMan'],"text").", "; 
				if (isset($_POST['brewYeastForm'])) $brewYeastForm = $_POST['brewYeastForm']; else $brewYeastForm = "";
				$insertSQL .= GetSQLValueString($brewYeastForm,"text").", ";
				if (isset($_POST['brewYeastType'])) $brewYeastType = $_POST['brewYeastType']; else $brewYeastType = "";
				$insertSQL .= GetSQLValueString($brewYeastType,"text").", "; 
				$insertSQL .= GetSQLValueString($_POST['brewYeastAmount'],"text").", "; 
				if (isset($_POST['brewYeastStarter'])) $brewYeastStarter = $_POST['brewYeastStarter']; else $brewYeastStarter = "";
				$insertSQL .= GetSQLValueString($brewYeastStarter,"text").", "; 
				$insertSQL .= GetSQLValueString(filter_var($_POST['brewYeastNutrients'],FILTER_SANITIZE_STRING),"text").", "; 
				$insertSQL .= GetSQLValueString(filter_var($_POST['brewOG'],FILTER_SANITIZE_STRING),"text").", "; 
				
				$insertSQL .= GetSQLValueString(filter_var($_POST['brewFG'],FILTER_SANITIZE_STRING),"text").", "; 
				$insertSQL .= GetSQLValueString(filter_var($_POST['brewPrimary'],FILTER_SANITIZE_STRING),"text").", "; 
				$insertSQL .= GetSQLValueString(filter_var($_POST['brewPrimaryTemp'],FILTER_SANITIZE_STRING),"text").", "; 
				$insertSQL .= GetSQLValueString(filter_var($_POST['brewSecondary'],FILTER_SANITIZE_STRING),"text").", "; 
				$insertSQL .= GetSQLValueString(filter_var($_POST['brewSecondaryTemp'],FILTER_SANITIZE_STRING),"text").", ";
				
				$insertSQL .= GetSQLValueString(filter_var($_POST['brewOther'],FILTER_SANITIZE_STRING),"text").", "; 
				$insertSQL .= GetSQLValueString(filter_var($_POST['brewOtherTemp'],FILTER_SANITIZE_STRING),"text").", "; 
			}
			
			$insertSQL .= GetSQLValueString(strip_newline(filter_var($brewComments,FILTER_SANITIZE_STRING)),"text").", ";
			
			if ($_SESSION['prefsHideRecipe'] == "N") { 
				$insertSQL .= GetSQLValueString(filter_var($_POST['brewFinings'],FILTER_SANITIZE_STRING),"text").", "; 
				$insertSQL .= GetSQLValueString(filter_var($_POST['brewWaterNotes'],FILTER_SANITIZE_STRING),"text").", ";
			}
			
			$insertSQL .= GetSQLValueString($brewBrewerID,"text").", "; 
			
			if ($_SESSION['prefsHideRecipe'] == "N") { 
				if (isset($_POST['brewCarbonationMethod'])) $brewCarbonationMethod = filter_var($_POST['brewCarbonationMethod'],FILTER_SANITIZE_STRING); else $brewCarbonationMethod = "";
				$insertSQL .= GetSQLValueString($brewCarbonationMethod,"text").", "; 
				$insertSQL .= GetSQLValueString(filter_var($_POST['brewCarbonationVol'],FILTER_SANITIZE_STRING),"text").", "; 
				$insertSQL .= GetSQLValueString(filter_var($_POST['brewCarbonationNotes'],FILTER_SANITIZE_STRING),"text").", "; 
				$insertSQL .= GetSQLValueString(filter_var($_POST['brewBoilHours'],FILTER_SANITIZE_STRING),"text").", "; 
				$insertSQL .= GetSQLValueString(filter_var($_POST['brewBoilMins'],FILTER_SANITIZE_STRING),"text").", "; 
			}
			
			$insertSQL .= GetSQLValueString($brewBrewerFirstName,"text").", "; 
			$insertSQL .= GetSQLValueString($brewBrewerLastName,"text").", "; 
			$insertSQL .= GetSQLValueString(ucwords($brewCoBrewer),"text").", ";
			
			$insertSQL .= GetSQLValueString($brewJudgingNumber,"text").", ";
			$insertSQL .= "NOW( ), ";
			if ($_POST['brewStyle'] == "0-A") $insertSQL .= GetSQLValueString("0","text").", ";
			else $insertSQL .= GetSQLValueString($_POST['brewConfirmed'],"text").", ";
			$insertSQL .= GetSQLValueString($brewPaid,"text").", ";
			$insertSQL .= GetSQLValueString("0","text").", ";
			$insertSQL .= GetSQLValueString($brewInfoOptional,"text");
			$insertSQL .= ")";
		
		
		// echo $insertSQL; exit;
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
		
		// Get style name from broken parts if BA (currently there are 14 overall BA categories, 34 BJCP 2015, and 28 BJCP 2007)
		// Custom style overall category will always be greater than 28
		if ((strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) && ($style[0] > 28)) $query_style_name = sprintf("SELECT * FROM %s WHERE brewStyleOwn='custom' AND brewStyleGroup='%s' AND brewStyleNum='%s'",$styles_db_table,$styleFix,$style[1]);
		
		// Get style name from broken parts if BJCP
		else $query_style_name = sprintf("SELECT * FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s'",$styles_db_table,$_SESSION['prefsStyleSet'],$styleFix,$style[1]);
		$style_name = mysqli_query($connection,$query_style_name) or die (mysqli_error($connection));
		$row_style_name = mysqli_fetch_assoc($style_name);
		$check = $row_style_name['brewStyleOwn'];
		
		$updateSQL = "UPDATE $brewing_db_table SET ";
			if ($_SESSION['prefsHideRecipe'] == "N") { 
				for($i=1; $i<=5; $i++) { $updateSQL .= "brewExtract".$i."=".GetSQLValueString(filter_var($_POST['brewExtract'.$i],FILTER_SANITIZE_STRING),"text").","; }
				for($i=1; $i<=5; $i++) { $updateSQL .= "brewExtract".$i."Weight=".GetSQLValueString(filter_var($_POST['brewExtract'.$i.'Weight'],FILTER_SANITIZE_STRING),"text").","; }
				for($i=1; $i<=5; $i++) { $updateSQL .= "brewExtract".$i."Use=".GetSQLValueString($_POST['brewExtract'.$i.'Use'],"text").","; }
				
				for($i=1; $i<=20; $i++) { $updateSQL .= "brewGrain".$i."=".GetSQLValueString(filter_var($_POST['brewGrain'.$i],FILTER_SANITIZE_STRING),"text").","; }
				for($i=1; $i<=20; $i++) { $updateSQL .= "brewGrain".$i."Weight=".GetSQLValueString(filter_var($_POST['brewGrain'.$i.'Weight'],FILTER_SANITIZE_STRING),"text").","; }
				for($i=1; $i<=20; $i++) { $updateSQL .= "brewGrain".$i."Use=".GetSQLValueString($_POST['brewGrain'.$i.'Use'],"text").","; }
				
				for($i=1; $i<=20; $i++) { $updateSQL .= "brewAddition".$i."=".GetSQLValueString(filter_var($_POST['brewAddition'.$i],FILTER_SANITIZE_STRING),"text").","; }
				for($i=1; $i<=20; $i++) { $updateSQL .= "brewAddition".$i."Amt=".GetSQLValueString($_POST['brewAddition'.$i.'Amt'],"text").","; }
				for($i=1; $i<=20; $i++) { $updateSQL .= "brewAddition".$i."Use=".GetSQLValueString($_POST['brewAddition'.$i.'Use'],"text").","; }
				
				for($i=1; $i<=20; $i++) { $updateSQL .= "brewHops".$i."=".GetSQLValueString(filter_var($_POST['brewHops'.$i],FILTER_SANITIZE_STRING),"text").","; }
				for($i=1; $i<=20; $i++) { $updateSQL .= "brewHops".$i."Weight=".GetSQLValueString(filter_var($_POST['brewHops'.$i.'Weight'],FILTER_SANITIZE_STRING),"text").","; }
				for($i=1; $i<=20; $i++) { $updateSQL .= "brewHops".$i."IBU=".GetSQLValueString($_POST['brewHops'.$i.'IBU'],"text").","; }
				for($i=1; $i<=20; $i++) { $updateSQL .= "brewHops".$i."Use=".GetSQLValueString($_POST['brewHops'.$i.'Use'],"text").","; }
				for($i=1; $i<=20; $i++) { $updateSQL .= "brewHops".$i."Time=".GetSQLValueString(filter_var($_POST['brewHops'.$i.'Time'],FILTER_SANITIZE_STRING),"text").","; }
				for($i=1; $i<=20; $i++) { $updateSQL .= "brewHops".$i."Type=".GetSQLValueString($_POST['brewHops'.$i.'Type'],"text").","; }
				for($i=1; $i<=20; $i++) { $updateSQL .= "brewHops".$i."Form=".GetSQLValueString($_POST['brewHops'.$i.'Form'],"text").","; }
				
				for($i=1; $i<=10; $i++) { $updateSQL .= "brewMashStep".$i."Name=".GetSQLValueString(filter_var($_POST['brewMashStep'.$i.'Name'],FILTER_SANITIZE_STRING),"text").","; }
				for($i=1; $i<=10; $i++) { $updateSQL .= "brewMashStep".$i."Temp=".GetSQLValueString($_POST['brewMashStep'.$i.'Temp'],"text").","; }
				for($i=1; $i<=10; $i++) { $updateSQL .= "brewMashStep".$i."Time=".GetSQLValueString($_POST['brewMashStep'.$i.'Time'],"text").","; }
				
				$updateSQL .= "brewBottleDate=".GetSQLValueString($_POST['brewBottleDate'],"text").", ";
				$updateSQL .= "brewDate=".GetSQLValueString($_POST['brewDate'],"text").", "; 
				$updateSQL .= "brewYield=".GetSQLValueString($_POST['brewYield'],"text").", ";
				$updateSQL .= "brewWinnerCat=".GetSQLValueString(round($_POST['brewWinnerCat'],0),"text").", "; // WinnerCat being used for SRM
				$updateSQL .= "brewYeast=".GetSQLValueString(filter_var($_POST['brewYeast'],FILTER_SANITIZE_STRING),"text").", "; 
				$updateSQL .= "brewYeastMan=".GetSQLValueString($_POST['brewYeastMan'],"text").", "; 
				$updateSQL .= "brewYeastForm=".GetSQLValueString($_POST['brewYeastForm'],"text").", "; 
				$updateSQL .= "brewYeastType=".GetSQLValueString($_POST['brewYeastType'],"text").", "; 
				$updateSQL .= "brewYeastAmount=". GetSQLValueString($_POST['brewYeastAmount'],"text").", "; 
				$updateSQL .= "brewYeastStarter=". GetSQLValueString($_POST['brewYeastStarter'],"text").", "; 
				$updateSQL .= "brewYeastNutrients=". GetSQLValueString(filter_var($_POST['brewYeastNutrients'],FILTER_SANITIZE_STRING),"text").", "; 
				$updateSQL .= "brewOG=". GetSQLValueString(filter_var($_POST['brewOG'],FILTER_SANITIZE_STRING),"text").", "; 
				$updateSQL .= "brewFG=".GetSQLValueString(filter_var($_POST['brewFG'],FILTER_SANITIZE_STRING),"text").", "; 
				$updateSQL .= "brewPrimary=".GetSQLValueString(filter_var($_POST['brewPrimary'],FILTER_SANITIZE_STRING),"text").", ";
				$updateSQL .= "brewPrimaryTemp=".GetSQLValueString(filter_var($_POST['brewPrimaryTemp'],FILTER_SANITIZE_STRING),"text").", ";
				$updateSQL .= "brewSecondary=".GetSQLValueString(filter_var($_POST['brewSecondary'],FILTER_SANITIZE_STRING),"text").", ";
				$updateSQL .= "brewSecondaryTemp=".GetSQLValueString(filter_var($_POST['brewSecondaryTemp'],FILTER_SANITIZE_STRING),"text").", ";
				$updateSQL .= "brewOther=".GetSQLValueString(filter_var($_POST['brewOther'],FILTER_SANITIZE_STRING),"text").", "; 
				$updateSQL .= "brewOtherTemp=". GetSQLValueString(filter_var($_POST['brewOtherTemp'],FILTER_SANITIZE_STRING),"text").", ";
				$updateSQL .= "brewFinings=".GetSQLValueString(filter_var($_POST['brewFinings'],FILTER_SANITIZE_STRING),"text").", ";  
				$updateSQL .= "brewWaterNotes=". GetSQLValueString(filter_var($_POST['brewWaterNotes'],FILTER_SANITIZE_STRING),"text").", ";
				$updateSQL .= "brewCarbonationMethod=".GetSQLValueString(filter_var($_POST['brewCarbonationMethod'],FILTER_SANITIZE_STRING),"text").", "; 
				$updateSQL .= "brewCarbonationVol=". GetSQLValueString(filter_var($_POST['brewCarbonationVol'],FILTER_SANITIZE_STRING),"text").", ";
				$updateSQL .= "brewCarbonationNotes=". GetSQLValueString(filter_var($_POST['brewCarbonationNotes'],FILTER_SANITIZE_STRING),"text").", "; 
				$updateSQL .= "brewBoilHours=". GetSQLValueString(filter_var($_POST['brewBoilHours'],FILTER_SANITIZE_STRING),"text").", ";
				$updateSQL .= "brewBoilMins=".GetSQLValueString(filter_var($_POST['brewBoilMins'],FILTER_SANITIZE_STRING),"text").", "; 
			}
			
			$updateSQL .= "brewName=".GetSQLValueString(capitalize($brewName),"text").", ";
			$updateSQL .= "brewStyle=".GetSQLValueString($styleName,"text").", ";
			$updateSQL .= "brewCategory=".GetSQLValueString($styleTrim,"text").", "; 
			$updateSQL .= "brewCategorySort=".GetSQLValueString($styleFix,"text").", ";  
			$updateSQL .= "brewSubCategory=".GetSQLValueString($style[1],"text").", ";
			$updateSQL .= "brewInfo=".GetSQLValueString($brewInfo,"text").", "; 
			$updateSQL .= "brewMead1=".GetSQLValueString($brewMead1,"text").", "; 
			$updateSQL .= "brewMead2=".GetSQLValueString($brewMead2,"text").", "; 
			$updateSQL .= "brewMead3=".GetSQLValueString($brewMead3,"text").", ";
			$updateSQL .= "brewComments=". GetSQLValueString(strip_newline(filter_var($brewComments,FILTER_SANITIZE_STRING)),"text").", "; 
			$updateSQL .= "brewBrewerID=".GetSQLValueString($brewBrewerID,"text").", "; 
			$updateSQL .= "brewBrewerFirstName=". GetSQLValueString($brewBrewerFirstName,"text").", "; 
			$updateSQL .= "brewBrewerLastName=".GetSQLValueString($brewBrewerLastName,"text").", ";  
			$updateSQL .= "brewCoBrewer=".GetSQLValueString(ucwords($brewCoBrewer),"text").", ";	
			$updateSQL .= "brewUpdated="."NOW( ), ";
			$updateSQL .= "brewJudgingNumber=".GetSQLValueString($_POST['brewJudgingNumber'],"text").", ";
			$updateSQL .= "brewPaid=".GetSQLValueString($brewPaid,"text").", ";
			$updateSQL .= "brewConfirmed=".GetSQLValueString($_POST['brewConfirmed'],"text").", ";
			$updateSQL .= "brewInfoOptional=".GetSQLValueString($brewInfoOptional,"text");
			$updateSQL .= " WHERE id ='".$id."'";
		
		//echo $updateSQL; exit;
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		
		
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
				$updateSQL1 = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id=%s", GetSQLValueString($id, "text"));
				mysqli_real_escape_string($connection,$updateSQL1);
				//$result = mysqli_query($connection,$updateSQL1) or die (mysqli_error($connection));
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
				$updateSQL2 = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id=%s", GetSQLValueString($id, "text"));
				mysqli_real_escape_string($connection,$updateSQL2);
				//$result = mysqli_query($connection,$updateSQL2) or die (mysqli_error($connection));
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
				$updateSQL3 = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id=%s", GetSQLValueString($id, "text"));
				mysqli_real_escape_string($connection,$updateSQL3);
				$result = mysqli_query($connection,$updateSQL3) or die (mysqli_error($connection));
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
				$updateSQL4 = sprintf("UPDATE $brewing_db_table SET brewConfirmed='0' WHERE id=%s", GetSQLValueString($id, "text"));
				mysqli_real_escape_string($connection,$updateSQL4);
				$result = mysqli_query($connection,$updateSQL4) or die (mysqli_error($connection));
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
		echo $style[1]."<br>";
		echo $styleBreak."<br>";
		if (check_mead_strength($styleBreak,$_SESSION['prefsStyleSet'])) echo "YES strength<br>"; else echo "No strength<br>";
		if (check_carb($styleBreak,$_SESSION['prefsStyleSet'])) echo "YES carb<br>"; else echo "No carb<br>";
		if (check_sweetness($styleBreak,$_SESSION['prefsStyleSet'])) echo "YES sweetness<br>"; else echo "No sweeness<br>";
		if (check_special_ingredients($styleBreak,$_SESSION['prefsStyleSet']))  echo "YES special<br>"; else echo "No special<br>";
		if (!empty($brewMead1)) echo $brewMead1."<br>";
		if (!empty($brewMead2)) echo $brewMead2."<br>";
		if (!empty($brewMead3)) echo $brewMead3."<br>";
		echo $_POST['brewInfo']."<br>";
		if (isset($updateSQL)) echo $updateSQL."<br>";
		if (isset($updateSQL1)) echo $updateSQL1."<br>";
		if (isset($updateSQL2)) echo $updateSQL2."<br>";
		if (isset($updateSQL3)) echo $updateSQL3."<br>";
		if (isset($updateSQL4)) echo $updateSQL4."<br>";
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
	
} else { 
	header(sprintf("Location: %s", $base_url."index.php?msg=98"));
	exit;
}
?>