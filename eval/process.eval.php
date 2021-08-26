<?php

/**
 * Define universal vars
 */

$evalSpecialIngredients = "";
$evalAppearanceComments = "";
$evalAromaComments = "";
$evalOtherNotes = "";
$evalFlavorComments = "";
$evalMouthfeelComments = "";
$evalOverallComments = "";
$evalIntangibles = "";
$evalDescriptors = "";
$eid = "";
$uid = "";
$token = "";
$evalMiniBOS = 0;
$evalBottle = 0;
$evalBottleNotes = "";
$evalPosition = "";
$evalMouthfeelScore = "";
$evalStyleAccuracy = "";
$evalTechMerit = "";
$evalDrinkability = "";

if ($_SESSION['prefsSEF'] == "Y") $sef = "true";
else $sef = "false";

if ($view == "admin") $insertGoTo = $base_url."index.php?section=evaluation&go=default&filter=default&view=admin";
else $insertGoTo = build_public_url("evaluation","default","default","default",$sef,$base_url);

require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
$config_html_purifier = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config_html_purifier);

$evalJudgeInfo = filter_var($_POST['evalJudgeInfo'],FILTER_SANITIZE_NUMBER_INT);
$evalScoresheet = filter_var($_POST['evalScoresheet'],FILTER_SANITIZE_NUMBER_INT);
$evalAromaScore = filter_var($_POST['evalAromaScore'],FILTER_SANITIZE_NUMBER_INT);
$evalAppearanceScore = filter_var($_POST['evalAppearanceScore'],FILTER_SANITIZE_NUMBER_INT);
$evalFlavorScore = filter_var($_POST['evalFlavorScore'],FILTER_SANITIZE_NUMBER_INT);
if (isset($_POST['evalMouthfeelScore'])) $evalMouthfeelScore = filter_var($_POST['evalMouthfeelScore'],FILTER_SANITIZE_NUMBER_INT);
$evalOverallScore = filter_var($_POST['evalOverallScore'],FILTER_SANITIZE_NUMBER_INT);
if (isset($_POST['evalStyleAccuracy'])) $evalStyleAccuracy = filter_var($_POST['evalStyleAccuracy'],FILTER_SANITIZE_NUMBER_INT);
if (isset($_POST['evalTechMerit'])) $evalTechMerit = filter_var($_POST['evalTechMerit'],FILTER_SANITIZE_NUMBER_INT);
if (isset($_POST['evalDrinkability'])) $evalDrinkability = filter_var($_POST['evalDrinkability'],FILTER_SANITIZE_NUMBER_INT);
$evalTable = filter_var($_POST['evalTable'],FILTER_SANITIZE_NUMBER_INT);
$evalFinalScore = filter_var($_POST['evalFinalScore'],FILTER_SANITIZE_NUMBER_INT);

if ($action == "edit") $id = filter_var($id,FILTER_SANITIZE_NUMBER_INT);
if (isset($_POST['eid'])) $eid = filter_var($_POST['eid'],FILTER_SANITIZE_NUMBER_INT);
if (isset($_POST['uid'])) $uid = filter_var($_POST['uid'],FILTER_SANITIZE_NUMBER_INT);
if (isset($_POST['token'])) $token = filter_var($_POST['token'],FILTER_SANITIZE_STRING);
if (isset($_POST['evalSpecialIngredients'])) $evalSpecialIngredients = $purifier->purify($_POST['evalSpecialIngredients']);
if (isset($_POST['evalOtherNotes'])) $evalOtherNotes = $purifier->purify($_POST['evalOtherNotes']);
if (isset($_POST['evalAromaComments'])) $evalAromaComments = $purifier->purify($_POST['evalAromaComments']);
if (isset($_POST['evalAppearanceComments'])) $evalAppearanceComments = $purifier->purify($_POST['evalAppearanceComments']);
if (isset($_POST['evalFlavorComments'])) $evalFlavorComments = $purifier->purify($_POST['evalFlavorComments']); 
if (isset($_POST['evalMouthfeelComments'])) $evalMouthfeelComments = $purifier->purify($_POST['evalMouthfeelComments']); 
if (isset($_POST['evalOverallComments'])) $evalOverallComments = $purifier->purify($_POST['evalOverallComments']);
if (isset($_POST['evalIntangibles'])) $evalIntangibles = $purifier->purify($_POST['evalIntangibles']);
if (isset($_POST['evalMiniBOS'])) $evalMiniBOS = filter_var($_POST['evalMiniBOS'],FILTER_SANITIZE_NUMBER_INT);
if (isset($_POST['evalBottle'])) $evalBottle = filter_var($_POST['evalBottle'],FILTER_SANITIZE_NUMBER_INT);
if (isset($_POST['evalBottleNotes'])) $evalBottleNotes = $purifier->purify($_POST['evalBottleNotes']); 
if ((isset($_POST['evalPosition_0'])) && (is_numeric($_POST['evalPosition_0']))) {
	$evalPosition = $purifier->purify($_POST['evalPosition_0']);
	if ((isset($_POST['evalPosition_1'])) && (is_numeric($_POST['evalPosition_1']))) $evalPosition .= ",".$purifier->purify($_POST['evalPosition_1']);
}

$evalStyle = $_POST['evalStyle'];

$exceptions = array(
	"evalSpecialIngredients",
	"evalOtherNotes",
	"evalAromaScore",
	"evalAromaComments",
	"evalAppearanceScore",
	"evalAppearanceComments",
	"evalFlavorScore",
	"evalFlavorComments",
	"evalMouthfeelScore",
	"evalMouthfeelComments",
	"evalIntangibles",
	"evalMiniBOS",
	"evalBottle",
	"evalBottleNotes",
	"evalPosition_0",
	"evalPosition_1",
	"evalDrinkability",
	"evalStyle",
	"eid",
	"uid",
	"token"
);

if ($section == "process-eval-structured") {

	// The data from the structured scoresheet form is stored in json format in
	// the respective Checklist columns (evalAromaChecklist, etc.).

	$evalAroma = array();
	$evalAppearance = array();
	$evalFlavor = array();
	$evalMouthfeel = array();
	$evalFlaws = array();

	foreach ($_POST as $key => $value) {
		
		if ((!empty($value)) && (is_array($value))) $value = implode(", ",$value);
		// echo $key." => ".$value."<br>";

		// Build Aroma Insert
		if ((strpos($key, "evalAroma") !== FALSE) && (!in_array($key, $exceptions))) {
			$evalAroma[$key] = $value;
		}

		// Build Appearance Insert
		if ((strpos($key, "evalAppearance") !== FALSE) && (!in_array($key, $exceptions))) {
			$evalAppearance[$key] = $value;
		}

		// Build Flavor Insert
		if ((strpos($key, "evalFlavor") !== FALSE) && (!in_array($key, $exceptions))) {
			$evalFlavor[$key] = $value;
		}

		// Build Mouthfeel Insert
		if ((strpos($key, "evalMouthfeel") !== FALSE) && (!in_array($key, $exceptions))) {
			$evalMouthfeel[$key] = $value;
		}

		// Build Flaws Insert
		if (strpos($key, "evalFlaws") !== FALSE) {
			if (!empty($value)) $evalFlaws[] = $value;
		}

	}

	$evalAromaChecklist = json_encode($evalAroma);
	$evalAppearanceChecklist = json_encode($evalAppearance);
	$evalFlavorChecklist = json_encode($evalFlavor);
	$evalMouthfeelChecklist = json_encode($evalMouthfeel);
	if (!empty($evalFlaws)) $evalFlaws = implode(", ",$evalFlaws);

	/*
	echo "<br><br>";
	echo $evalAromaChecklist."<br><br>";
	echo $evalAppearanceChecklist."<br><br>";
	echo $evalFlavorChecklist."<br><br>";
	echo $evalMouthfeelChecklist."<br><br>";
	echo $evalFlaws."<br><br>";
	*/

	if ($action == "add") {

		$insertSQL = sprintf("INSERT INTO %s 
		(
		evalJudgeInfo, 
		evalScoresheet, 
		evalStyle, 	
		evalSpecialIngredients, 
		evalOtherNotes, 

		evalAromaScore, 
		evalAromaChecklist, 
		evalAppearanceScore, 
		evalAppearanceChecklist, 
		evalFlavorScore, 

		evalFlavorChecklist, 
		evalMouthfeelScore, 
		evalMouthfeelChecklist, 
		evalOverallScore, 
		evalOverallComments, 
		
		evalStyleAccuracy, 
		evalTechMerit, 
		evalIntangibles, 
		evalFlaws, 
		evalInitialDate, 
		
		evalUpdatedDate, 
		evalToken,
		eid,
		uid,
		evalTable, 

		evalFinalScore, 
		evalMiniBOS,
		evalBottle,
		evalBottleNotes,
		evalPosition
		) 
		
		VALUES (
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s
		)",
							$prefix."evaluation",
							
							GetSQLValueString($evalJudgeInfo, "text"), 
							GetSQLValueString($evalScoresheet, "text"), 
							GetSQLValueString($evalStyle, "text"), 
							GetSQLValueString($evalSpecialIngredients, "text"), 
							GetSQLValueString($evalOtherNotes, "text"), 
							
							GetSQLValueString($evalAromaScore, "text"), 
							GetSQLValueString($evalAromaChecklist, "text"), 
							GetSQLValueString($evalAppearanceScore, "text"), 
							GetSQLValueString($evalAppearanceChecklist, "text"), 
							GetSQLValueString($evalFlavorScore, "text"), 

							GetSQLValueString($evalFlavorChecklist, "text"), 
							GetSQLValueString($evalMouthfeelScore, "text"), 
							GetSQLValueString($evalMouthfeelChecklist, "text"), 
							GetSQLValueString($evalOverallScore, "text"), 
							GetSQLValueString($evalOverallComments, "text"), 

							GetSQLValueString($evalStyleAccuracy, "text"), 
							GetSQLValueString($evalTechMerit, "text"), 
							GetSQLValueString($evalIntangibles, "text"), 
							GetSQLValueString($evalFlaws, "text"), 
							GetSQLValueString(time(), "text"), 

							GetSQLValueString(time(), "text"), 
							GetSQLValueString($token, "text"), 
							GetSQLValueString($eid, "text"), 
							GetSQLValueString($uid, "text"), 
							GetSQLValueString($evalTable, "text"), 

							GetSQLValueString($evalFinalScore, "text"), 
							GetSQLValueString($evalMiniBOS, "text"),
							GetSQLValueString($evalBottle, "text"),
							GetSQLValueString($evalBottleNotes, "text"),
							GetSQLValueString($evalPosition, "text")
		);
		
		//echo $insertSQL;
		//echo $insertGoTo;
		//exit;
		
		mysqli_real_escape_string($connection,$insertSQL);
		$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));
		
		$pattern = array('\'', '"');
		$insertGoTo = str_replace($pattern, "", $insertGoTo); 
		header(sprintf("Location: %s", stripslashes($insertGoTo)));
		
	} // if ($action == "add") 

	if ($action == "edit") {
		
		$updateSQL = sprintf("UPDATE %s SET 
		
		evalJudgeInfo=%s, 
		evalScoresheet=%s, 
		evalStyle=%s, 
		evalSpecialIngredients=%s, 
		evalOtherNotes=%s,

		evalAromaScore=%s, 
		evalAromaChecklist=%s, 
		evalAppearanceScore=%s, 
		evalAppearanceChecklist=%s,
		evalFlavorScore=%s, 

		evalFlavorChecklist=%s, 
		evalMouthfeelScore=%s,
		evalMouthfeelChecklist=%s, 
		evalOverallScore=%s,
		evalOverallComments=%s,
		
		evalStyleAccuracy=%s,
		evalTechMerit=%s, 
		evalIntangibles=%s,
		evalFlaws=%s,
		evalUpdatedDate=%s,

		eid=%s,
		uid=%s,
		evalTable=%s,
		evalFinalScore=%s,
		evalMiniBOS=%s,

		evalBottle=%s,
		evalBottleNotes=%s,
		evalPosition=%s		
		WHERE id=%s
		",
							$prefix."evaluation",
							GetSQLValueString($evalJudgeInfo, "text"), 
							GetSQLValueString($evalScoresheet, "text"),  
							GetSQLValueString($evalStyle, "text"), 
							GetSQLValueString($evalSpecialIngredients, "text"), 
							GetSQLValueString($evalOtherNotes, "text"), 

							GetSQLValueString($evalAromaScore, "text"), 
							GetSQLValueString($evalAromaChecklist, "text"), 							
							GetSQLValueString($evalAppearanceScore, "text"), 
							GetSQLValueString($evalAppearanceChecklist, "text"), 							
							GetSQLValueString($evalFlavorScore, "text"), 

							GetSQLValueString($evalFlavorChecklist, "text"), 
							GetSQLValueString($evalMouthfeelScore, "text"), 							
							GetSQLValueString($evalMouthfeelChecklist, "text"), 
							GetSQLValueString($evalOverallScore, "text"), 
							GetSQLValueString($evalOverallComments, "text"), 

							GetSQLValueString($evalStyleAccuracy, "text"), 
							GetSQLValueString($evalTechMerit, "text"), 
							GetSQLValueString($evalIntangibles, "text"),
							GetSQLValueString($evalFlaws, "text"), 							
							GetSQLValueString(time(), "text"),

							GetSQLValueString($eid, "text"),
							GetSQLValueString($uid, "text"),
							GetSQLValueString($evalTable, "text"), 
							GetSQLValueString($evalFinalScore, "text"), 							
							GetSQLValueString($evalMiniBOS, "text"),
							
							GetSQLValueString($evalBottle, "text"),
							GetSQLValueString($evalBottleNotes, "text"),
							GetSQLValueString($evalPosition, "text"),
							GetSQLValueString($id, "int")
		);
		
		//echo $updateSQL;
		//echo "<br>".$insertGoTo;
		//exit;
		
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		
		$pattern = array('\'', '"');
		$insertGoTo = str_replace($pattern, "", $insertGoTo); 
		header(sprintf("Location: %s", stripslashes($insertGoTo)));
		
	} // end if ($action == "edit")
	
	
	//exit;

}

if ($section == "process-eval-full") {
	
	if ((!empty($_POST['evalDescriptors'])) && (is_array($_POST['evalDescriptors']))) $evalDescriptors = implode(", ",$_POST['evalDescriptors']);
	
	if ($action == "add") {
		
		$insertSQL = sprintf("INSERT INTO %s
		(
		evalJudgeInfo, 
		evalScoresheet,  
		evalStyle, 
		evalSpecialIngredients, 
		evalOtherNotes,

		evalAromaScore,				
		evalAromaComments, 
		evalAppearanceScore, 
		evalAppearanceComments,				
		evalFlavorScore,

		evalFlavorComments, 
		evalMouthfeelScore,
		evalMouthfeelComments,
		evalOverallScore,
		evalOverallComments,

		evalStyleAccuracy,
		evalTechMerit, 
		evalIntangibles,
		evalDescriptors,
		evalInitialDate, 

		evalUpdatedDate,
		evalToken,
		eid,
		uid,
		evalTable,

		evalFinalScore,
		evalMiniBOS,
		evalBottle,
		evalBottleNotes,
		evalPosition
		) 
		
		VALUES (
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s
		)",
							$prefix."evaluation",
							
							GetSQLValueString($evalJudgeInfo, "text"), 
							GetSQLValueString($evalScoresheet, "text"),
							GetSQLValueString($evalStyle, "text"),
							GetSQLValueString($evalSpecialIngredients, "text"), 
							GetSQLValueString($evalOtherNotes, "text"), 
							
							GetSQLValueString($evalAromaScore, "text"),
							GetSQLValueString($evalAromaComments, "text"), 
							GetSQLValueString($evalAppearanceScore, "text"),
							GetSQLValueString($evalAppearanceComments, "text"),
							GetSQLValueString($evalFlavorScore, "text"),
							
							GetSQLValueString($evalFlavorComments, "text"), 
							GetSQLValueString($evalMouthfeelScore, "text"),
							GetSQLValueString($evalMouthfeelComments, "text"), 
							GetSQLValueString($evalOverallScore, "text"), 
							GetSQLValueString($evalOverallComments, "text"),
							
							GetSQLValueString($evalStyleAccuracy, "text"), 
							GetSQLValueString($evalTechMerit, "text"), 
							GetSQLValueString($evalIntangibles, "text"), 
							GetSQLValueString($evalDescriptors, "text"),
							GetSQLValueString(time(), "text"), 
							
							GetSQLValueString(time(), "text"),
							GetSQLValueString($token, "text"),
							GetSQLValueString($eid, "text"),
							GetSQLValueString($uid, "text"),
							GetSQLValueString($evalTable, "text"), 
							
							GetSQLValueString($evalFinalScore, "text"),
							GetSQLValueString($evalMiniBOS, "text"),
							GetSQLValueString($evalBottle, "text"),
							GetSQLValueString($evalBottleNotes, "text"),
							GetSQLValueString($evalPosition, "text")
		);
		
		//echo $insertSQL;
		//exit;
		
		mysqli_real_escape_string($connection,$insertSQL);
		$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));
		
		$pattern = array('\'', '"');
		$insertGoTo = str_replace($pattern, "", $insertGoTo); 
		header(sprintf("Location: %s", stripslashes($insertGoTo)));
		
	} // end if ($action == "add")
	
	
	if ($action == "edit") {
		
		$updateSQL = sprintf("UPDATE %s SET 
		
		evalJudgeInfo=%s, 
		evalScoresheet=%s, 
		evalStyle=%s, 
		
		evalSpecialIngredients=%s, 
		evalOtherNotes=%s, 
		evalAromaScore=%s,
		evalAromaComments=%s, 
		evalAppearanceScore=%s,
		
		evalAppearanceComments=%s,
		evalFlavorScore=%s,
		evalFlavorComments=%s, 
		evalMouthfeelScore=%s,
		evalMouthfeelComments=%s,
		
		evalOverallScore=%s,
		evalOverallComments=%s,
		evalStyleAccuracy=%s,
		evalTechMerit=%s, 
		evalIntangibles=%s,
		
		evalDescriptors=%s,
		evalUpdatedDate=%s,
		eid=%s,
		uid=%s,
		evalTable=%s,
		
		evalFinalScore=%s,
		evalMiniBOS=%s,
		evalBottle=%s,
		evalBottleNotes=%s,
		evalPosition=%s
		WHERE id=%s
		",
							$prefix."evaluation",
							GetSQLValueString($evalJudgeInfo, "text"), 
							GetSQLValueString($evalScoresheet, "text"), 
							GetSQLValueString($evalStyle, "text"),
							
							GetSQLValueString($evalSpecialIngredients, "text"), 
							GetSQLValueString($evalOtherNotes, "text"), 
							GetSQLValueString($evalAromaScore, "text"),									
							GetSQLValueString($evalAromaComments, "text"), 
							GetSQLValueString($evalAppearanceScore, "text"), 
							
							GetSQLValueString($evalAppearanceComments, "text"),
							GetSQLValueString($evalFlavorScore, "text"),
							GetSQLValueString($evalFlavorComments, "text"), 
							GetSQLValueString($evalMouthfeelScore, "text"),
							GetSQLValueString($evalMouthfeelComments, "text"),  
							
							GetSQLValueString($evalOverallScore, "text"), 
							GetSQLValueString($evalOverallComments, "text"),
							GetSQLValueString($evalStyleAccuracy, "text"), 
							GetSQLValueString($evalTechMerit, "text"), 
							GetSQLValueString($evalIntangibles, "text"), 
							
							GetSQLValueString($evalDescriptors, "text"),
							GetSQLValueString(time(), "text"),
							GetSQLValueString($eid, "text"),
							GetSQLValueString($uid, "text"),
							GetSQLValueString($evalTable, "text"), 
							
							GetSQLValueString($evalFinalScore, "text"),
							GetSQLValueString($evalMiniBOS, "text"),
							GetSQLValueString($evalBottle, "text"),
							GetSQLValueString($evalBottleNotes, "text"),
							GetSQLValueString($evalPosition, "text"),
							GetSQLValueString($id, "int")
		);
		
		//echo $updateSQL;
		//exit;
		
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		
		$pattern = array('\'', '"');
		$insertGoTo = str_replace($pattern, "", $insertGoTo); 
		header(sprintf("Location: %s", stripslashes($insertGoTo)));
		
	} // end if ($action == "edit")
	
}

if ($section == "process-eval-checklist") {

	$evalAromaChecklistDesc = "";
	$evalAppearanceChecklistDesc = "";
	$evalFlavorChecklistDesc = "";
	$evalMouthfeelChecklistDesc = "";
	$evalOverallChecklistDesc = "";
	$evalFlaws = "";
	
	if ((!empty($_POST['evalAromaChecklistDesc'])) && (is_array($_POST['evalAromaChecklistDesc']))) $evalAromaChecklistDesc = implode(", ",$_POST['evalAromaChecklistDesc']); 
	if ((!empty($_POST['evalAppearanceChecklistDesc'])) && (is_array($_POST['evalAppearanceChecklistDesc']))) $evalAppearanceChecklistDesc = implode(", ",$_POST['evalAppearanceChecklistDesc']);
	if ((!empty($_POST['evalFlavorChecklistDesc'])) && (is_array($_POST['evalFlavorChecklistDesc']))) $evalFlavorChecklistDesc = implode(", ",$_POST['evalFlavorChecklistDesc']);
	if ((!empty($_POST['evalMouthfeelChecklistDesc'])) && (is_array($_POST['evalMouthfeelChecklistDesc']))) $evalMouthfeelChecklistDesc = implode(", ",$_POST['evalMouthfeelChecklistDesc']);
	if ((!empty($_POST['evalOverallChecklistDesc'])) && (is_array($_POST['evalOveralllChecklistDesc']))) $evalOverallChecklistDesc = implode(", ",$_POST['evalOverallChecklistDesc']);
	if ((!empty($_POST['evalFlaws'])) && (is_array($_POST['evalFlaws']))) $evalFlaws = implode(", ",$_POST['evalFlaws']);
	
	// Aroma
	$evalAromaCheck = array();
	$evalAromaCheck[] = $_POST['evalAromaMalt'];
	$evalAromaCheck[] = $_POST['evalAromaHops'];
	$evalAromaCheck[] = $_POST['evalAromaEsters'];
	$evalAromaCheck[] = $_POST['evalAromaPhenols'];
	$evalAromaCheck[] = $_POST['evalAromaAlcohol'];
	$evalAromaCheck[] = $_POST['evalAromaSweetness'];
	$evalAromaCheck[] = $_POST['evalAromaAcidity'];
	$evalAromaChecklist = implode(", ",$evalAromaCheck);
	
	// Appearance
	$evalAppearanceCheck = array();
	$evalAppearanceCheck[] = $_POST['evalAppearanceClarity'];
	$evalAppearanceCheck[] = $_POST['evalAppearanceHeadSize'];
	$evalAppearanceCheck[] = $_POST['evalAppearanceHeadRetention'];
	$evalAppearanceChecklist = implode(", ",$evalAppearanceCheck);
	
	// Flavor
	$evalFlavorCheck = array();
	$evalFlavorCheck[] = $_POST['evalFlavorMalt'];
	$evalFlavorCheck[] = $_POST['evalFlavorHops'];
	$evalFlavorCheck[] = $_POST['evalFlavorEsters'];
	$evalFlavorCheck[] = $_POST['evalFlavorPhenols'];
	$evalFlavorCheck[] = $_POST['evalFlavorSweetness'];
	$evalFlavorCheck[] = $_POST['evalFlavorBitterness'];
	$evalFlavorCheck[] = $_POST['evalFlavorAlcohol'];
	$evalFlavorCheck[] = $_POST['evalFlavorAcidity'];
	$evalFlavorCheck[] = $_POST['evalFlavorHarshness'];
	$evalFlavorChecklist = implode(", ",$evalFlavorCheck);
	
	// Mouthfeel
	$evalMouthfeelCheck = array();
	$evalMouthfeelCheck[] = $_POST['evalMouthfeelBody'];
	$evalMouthfeelCheck[] = $_POST['evalMouthfeelCarbonation'];
	$evalMouthfeelCheck[] = $_POST['evalMouthfeelWarmth'];
	$evalMouthfeelCheck[] = $_POST['evalMouthfeelCreaminess'];
	$evalMouthfeelCheck[] = $_POST['evalMouthfeelAstringency'];
	$evalMouthfeelChecklist = implode(", ",$evalMouthfeelCheck);
	
	/*
	// echo $_POST['evalBeerName']."<br>";
	echo $evalAromaChecklist."<br>";
	echo $evalAromaChecklistDesc."<br>";
	echo $evalAppearanceChecklist."<br>";
	echo $evalAppearanceChecklistDesc."<br>";
	echo $evalFlavorChecklist."<br>";
	echo $evalFlavorChecklistDesc."<br>";
	echo $evalMouthfeelChecklist."<br>";
	echo $evalMouthfeelChecklistDesc."<br>";
	echo $evalFlaws;
	exit;
	*/
	
	
	if ($action == "add") {

		$insertSQL = sprintf("INSERT INTO %s 
		(
		evalJudgeInfo, 
		evalScoresheet,  
		evalStyle, 
		
		evalSpecialIngredients, 
		evalOtherNotes, 
		evalAromaScore, 
		evalAromaChecklist, 
		evalAromaChecklistDesc, 
		
		evalAromaComments, 
		evalAppearanceScore, 
		evalAppearanceChecklist, 
		evalAppearanceChecklistDesc, 
		evalAppearanceComments,
		
		evalFlavorScore, 
		evalFlavorChecklist, 
		evalFlavorChecklistDesc, 
		evalFlavorComments, 
		evalMouthfeelScore,
		
		evalMouthfeelChecklist, 
		evalMouthfeelChecklistDesc, 
		evalMouthfeelComments,
		evalOverallScore,
		evalOverallComments,
		
		evalStyleAccuracy,
		evalTechMerit,
		evalIntangibles,
		evalDrinkability,
		evalFlaws, 
		
		evalInitialDate, 
		evalUpdatedDate,
		evalToken,
		eid,
		uid,
		
		evalTable,
		evalFinalScore,
		evalMiniBOS,
		evalBottle,
		evalBottleNotes,
		evalPosition
		) 
		
		VALUES (
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s
		)",
							$prefix."evaluation",
							GetSQLValueString($evalJudgeInfo, "text"), 
							GetSQLValueString($evalScoresheet, "text"), 
							GetSQLValueString($evalStyle, "text"), 
							
							GetSQLValueString($evalSpecialIngredients, "text"), 
							GetSQLValueString($evalOtherNotes, "text"), 
							GetSQLValueString($evalAromaScore, "text"), 
							GetSQLValueString($evalAromaChecklist, "text"), 
							GetSQLValueString($evalAromaChecklistDesc, "text"), 
							
							GetSQLValueString($evalAromaComments, "text"), 
							GetSQLValueString($evalAppearanceScore, "text"), 
							GetSQLValueString($evalAppearanceChecklist, "text"), 
							GetSQLValueString($evalAppearanceChecklistDesc, "text"), 
							GetSQLValueString($evalAppearanceComments, "text"), 
							
							GetSQLValueString($evalFlavorScore, "text"), 
							GetSQLValueString($evalFlavorChecklist, "text"), 
							GetSQLValueString($evalFlavorChecklistDesc, "text"), 
							GetSQLValueString($evalFlavorComments, "text"), 
							GetSQLValueString($evalMouthfeelScore, "text"), 
							
							GetSQLValueString($evalMouthfeelChecklist, "text"), 
							GetSQLValueString($evalMouthfeelChecklistDesc, "text"), 
							GetSQLValueString($evalMouthfeelComments, "text"), 
							GetSQLValueString($evalOverallScore, "text"), 
							GetSQLValueString($evalOverallComments, "text"), 
							
							GetSQLValueString($evalStyleAccuracy, "text"), 
							GetSQLValueString($evalTechMerit, "text"), 
							GetSQLValueString($evalIntangibles, "text"),
							GetSQLValueString($evalDrinkability, "text"),
							GetSQLValueString($evalFlaws, "text"),
							 
							GetSQLValueString(time(), "text"), 
							GetSQLValueString(time(), "text"),
							GetSQLValueString($token, "text"),
							GetSQLValueString($eid, "text"),
							GetSQLValueString($uid, "text"),
							
							GetSQLValueString($evalTable, "text"), 
							GetSQLValueString($evalFinalScore, "text"),
							GetSQLValueString($evalMiniBOS, "text"),
							GetSQLValueString($evalBottle, "text"),
							GetSQLValueString($evalBottleNotes, "text"),
							GetSQLValueString($evalPosition, "text")
		);
		
		//echo $insertSQL;
		//echo $insertGoTo;
		//exit;
		
		mysqli_real_escape_string($connection,$insertSQL);
		$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));
		
		$pattern = array('\'', '"');
		$insertGoTo = str_replace($pattern, "", $insertGoTo); 
		header(sprintf("Location: %s", stripslashes($insertGoTo)));
		
	} // if ($action == "add") 
	
	
	if ($action == "edit") {
		
		$updateSQL = sprintf("UPDATE %s SET 
		
		evalJudgeInfo=%s, 
		evalScoresheet=%s, 
		evalStyle=%s, 
		
		evalSpecialIngredients=%s, 
		evalOtherNotes=%s, 
		evalAromaScore=%s, 
		evalAromaChecklist=%s, 
		evalAromaChecklistDesc=%s, 
		
		evalAromaComments=%s, 
		evalAppearanceScore=%s, 
		evalAppearanceChecklist=%s, 
		evalAppearanceChecklistDesc=%s, 
		evalAppearanceComments=%s,
		
		evalFlavorScore=%s, 
		evalFlavorChecklist=%s, 
		evalFlavorChecklistDesc=%s, 
		evalFlavorComments=%s, 
		evalMouthfeelScore=%s,
		
		evalMouthfeelChecklist=%s, 
		evalMouthfeelChecklistDesc=%s, 
		evalMouthfeelComments=%s,
		evalOverallScore=%s,
		evalOverallComments=%s,
		
		evalStyleAccuracy=%s,
		evalTechMerit=%s, 
		evalIntangibles=%s,
		evalDrinkability=%s,
		evalFlaws=%s,
		
		evalUpdatedDate=%s,
		eid=%s,
		uid=%s,
		evalTable=%s,
		evalFinalScore=%s,
		
		evalMiniBOS=%s,
		evalBottle=%s,
		evalBottleNotes=%s,
		evalPosition=%s		
		WHERE id=%s
		",
							$prefix."evaluation",
							GetSQLValueString($evalJudgeInfo, "text"), 
							GetSQLValueString($evalScoresheet, "text"),  
							GetSQLValueString($evalStyle, "text"), 
							
							GetSQLValueString($evalSpecialIngredients, "text"), 
							GetSQLValueString($evalOtherNotes, "text"), 
							GetSQLValueString($evalAromaScore, "text"), 
							GetSQLValueString($evalAromaChecklist, "text"), 
							GetSQLValueString($evalAromaChecklistDesc, "text"), 
							
							GetSQLValueString($evalAromaComments, "text"), 
							GetSQLValueString($evalAppearanceScore, "text"), 
							GetSQLValueString($evalAppearanceChecklist, "text"), 
							GetSQLValueString($evalAppearanceChecklistDesc, "text"), 
							GetSQLValueString($evalAppearanceComments, "text"), 
							
							GetSQLValueString($evalFlavorScore, "text"), 
							GetSQLValueString($evalFlavorChecklist, "text"), 
							GetSQLValueString($evalFlavorChecklistDesc, "text"), 
							GetSQLValueString($evalFlavorComments, "text"), 
							GetSQLValueString($evalMouthfeelScore, "text"), 
							
							GetSQLValueString($evalMouthfeelChecklist, "text"), 
							GetSQLValueString($evalMouthfeelChecklistDesc, "text"), 
							GetSQLValueString($evalMouthfeelComments, "text"), 
							GetSQLValueString($evalOverallScore, "text"), 
							GetSQLValueString($evalOverallComments, "text"), 
							
							GetSQLValueString($evalStyleAccuracy, "text"), 
							GetSQLValueString($evalTechMerit, "text"), 
							GetSQLValueString($evalIntangibles, "text"),
							GetSQLValueString($evalDrinkability, "text"),
							GetSQLValueString($evalFlaws, "text"),
							
							GetSQLValueString(time(), "text"),
							GetSQLValueString($eid, "text"),
							GetSQLValueString($uid, "text"),
							GetSQLValueString($evalTable, "text"), 
							GetSQLValueString($evalFinalScore, "text"),
							
							GetSQLValueString($evalMiniBOS, "text"),
							GetSQLValueString($evalBottle, "text"),
							GetSQLValueString($evalBottleNotes, "text"),
							GetSQLValueString($evalPosition, "text"),
							GetSQLValueString($id, "int")
		);
		
		//echo $updateSQL;
		//echo "<br>".$insertGoTo;
		//exit;
		
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		
		$pattern = array('\'', '"');
		$insertGoTo = str_replace($pattern, "", $insertGoTo); 
		header(sprintf("Location: %s", stripslashes($insertGoTo)));
		
	} // end if ($action == "edit")

} // end if (($section == "process-eval-full") || ($section == "process-eval-checklist")) 
?>