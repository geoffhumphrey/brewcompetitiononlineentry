<?php

/**
 * Define universal vars
 */

if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && (isset($_SESSION['userLevel'])))) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

	$eid = "";
	$uid = "";
	$token = "";
	$evalSpecialIngredients = "";
	$evalAppearanceComments = "";
	$evalAromaComments = "";
	$evalOtherNotes = "";
	$evalFlavorComments = "";
	$evalMouthfeelComments = "";
	$evalOverallComments = "";
	$evalIntangibles = "";
	$evalDescriptors = "";
	$evalMiniBOS = 0;
	$evalBottle = 0;
	$evalBottleNotes = "";
	$evalPosition = "";
	$evalStyleAccuracy = "";
	$evalTechMerit = "";
	$evalDrinkability = "";
	$evalAromaScore = 0;
	$evalAppearanceScore = 0;
	$evalFlavorScore = 0;
	$evalMouthfeelScore = 0;

	if ($_SESSION['prefsSEF'] == "Y") $sef = "true";
	else $sef = "false";

	if ($view == "admin") $insertGoTo = $base_url."index.php?section=evaluation&go=default&filter=default&view=admin";
	else $insertGoTo = build_public_url("evaluation","default","default","default",$sef,$base_url);

	require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
	$config_html_purifier = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config_html_purifier);

	if (isset($_POST['evalJudgeInfo'])) $evalJudgeInfo = filter_var($_POST['evalJudgeInfo'],FILTER_SANITIZE_NUMBER_INT);
	if (isset($_POST['evalScoresheet'])) $evalScoresheet = filter_var($_POST['evalScoresheet'],FILTER_SANITIZE_NUMBER_INT);
	if (isset($_POST['evalAromaScore'])) $evalAromaScore = filter_var($_POST['evalAromaScore'],FILTER_SANITIZE_NUMBER_INT);
	if (isset($_POST['evalAppearanceScore'])) $evalAppearanceScore = filter_var($_POST['evalAppearanceScore'],FILTER_SANITIZE_NUMBER_INT);
	if (isset($_POST['evalFlavorScore'])) $evalFlavorScore = filter_var($_POST['evalFlavorScore'],FILTER_SANITIZE_NUMBER_INT);
	if (isset($_POST['evalMouthfeelScore'])) $evalMouthfeelScore = filter_var($_POST['evalMouthfeelScore'],FILTER_SANITIZE_NUMBER_INT);
	
	// All scoresheets require an overall and consensus score
	$evalOverallScore = filter_var($_POST['evalOverallScore'],FILTER_SANITIZE_NUMBER_INT);
	$evalFinalScore = filter_var($_POST['evalFinalScore'],FILTER_SANITIZE_NUMBER_INT);
	
	if (isset($_POST['evalStyleAccuracy'])) $evalStyleAccuracy = filter_var($_POST['evalStyleAccuracy'],FILTER_SANITIZE_NUMBER_INT);
	if (isset($_POST['evalTechMerit'])) $evalTechMerit = filter_var($_POST['evalTechMerit'],FILTER_SANITIZE_NUMBER_INT);
	if (isset($_POST['evalDrinkability'])) $evalDrinkability = filter_var($_POST['evalDrinkability'],FILTER_SANITIZE_STRING);
	$evalTable = filter_var($_POST['evalTable'],FILTER_SANITIZE_NUMBER_INT);
	

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

	$evalStyle = filter_var($_POST['evalStyle'],FILTER_SANITIZE_NUMBER_INT);

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

			if (!empty($value)) {
				
				if (is_numeric($value)) $value = filter_var($value,FILTER_SANITIZE_NUMBER_INT);	
				
				if (is_array($value)) {

					$new_value = array();
					
					foreach ($value as $v) {
						if (is_numeric($v)) $v = filter_var($value,FILTER_SANITIZE_NUMBER_INT);
						else  $v = $purifier->purify($v);
						$new_value[] = $v;
					}
					
					$value = implode(", ",$new_value);
					
				}

			}

			// Build Aroma Insert
			if ((strpos($key, "evalAroma") !== FALSE) && (!in_array($key, $exceptions))) {
				$key = filter_var($key,FILTER_SANITIZE_STRING);
				$evalAroma[$key] = $value;
			}

			// Build Appearance Insert
			// When processing NW, convert evalAppearanceColorChoice to evalAppearanceColor
			// If evalAppearanceColorChoice is "Other", convert evalAppearanceColorOther to evalAppearanceColor
			if ((strpos($key, "evalAppearance") !== FALSE) && (!in_array($key, $exceptions))) {
				$key = filter_var($key,FILTER_SANITIZE_STRING);
				if ($key == "evalAppearanceColorChoice") {
					$key = "evalAppearanceColor";
					if ($value == "999") $value = $_POST['evalAppearanceColorOther'];
					else $value = $_POST['evalAppearanceColorChoice'];
				}
				$evalAppearance[$key] = $value;
			}

			// Build Flavor Insert
			if ((strpos($key, "evalFlavor") !== FALSE) && (!in_array($key, $exceptions))) {
				$key = filter_var($key,FILTER_SANITIZE_STRING);
				$evalFlavor[$key] = $value;
			}

			// Build Mouthfeel Insert
			if ((strpos($key, "evalMouthfeel") !== FALSE) && (!in_array($key, $exceptions))) {
				$key = filter_var($key,FILTER_SANITIZE_STRING);
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
		if ((is_array($evalFlaws)) && (!empty($evalFlaws))) $evalFlaws = implode(", ",$evalFlaws); 
		else $evalFlaws = "";

		/*
		echo "<br><br>";
		echo $evalAromaChecklist."<br><br>";
		echo $evalAppearanceChecklist."<br><br>";
		echo $evalFlavorChecklist."<br><br>";
		echo $evalMouthfeelChecklist."<br><br>";
		echo $evalFlaws."<br><br>";
		*/

		if (($action == "add") || ($action == "edit")) {

			$update_table = $prefix."evaluation";
			$data = array(
				'eid' => $eid,
				'uid' => $uid,
				'evalJudgeInfo' => $evalJudgeInfo, 
				'evalScoresheet' => $evalScoresheet, 
				'evalStyle' => $evalStyle, 	
				'evalSpecialIngredients' => $evalSpecialIngredients, 
				'evalOtherNotes' => $evalOtherNotes, 
				'evalAromaScore' => $evalAromaScore, 
				'evalAromaChecklist' => $evalAromaChecklist, 
				'evalAppearanceScore' => $evalAppearanceScore, 
				'evalAppearanceChecklist' => $evalAppearanceChecklist, 
				'evalFlavorScore' => $evalFlavorScore, 
				'evalFlavorChecklist' => $evalFlavorChecklist, 
				'evalMouthfeelScore' => $evalMouthfeelScore, 
				'evalMouthfeelChecklist' => $evalMouthfeelChecklist, 
				'evalOverallScore' => $evalOverallScore, 
				'evalOverallComments' => $evalOverallComments, 
				'evalStyleAccuracy' => $evalStyleAccuracy, 
				'evalTechMerit' => $evalTechMerit, 
				'evalIntangibles' => $evalIntangibles, 
				'evalFlaws' => $evalFlaws, 
				'evalInitialDate' => time(), 
				'evalUpdatedDate' => time(), 
				'evalToken' => $token,
				'evalTable' => $evalTable, 
				'evalFinalScore' => $evalFinalScore, 
				'evalMiniBOS' => $evalMiniBOS,
				'evalBottle' => $evalBottle,
				'evalBottleNotes' => $evalBottleNotes,
				'evalPosition' => $evalPosition
			);

		}

		if ($action == "add") {

			$result = $db_conn->insert ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			if (!empty($error_output)) $_SESSION['error_output'] = $error_output;
			
			if ($errors) $insertGoTo = $_POST['relocate']."&msg=3";
			$insertGoTo = prep_redirect_link($insertGoTo);
			$redirect_go_to = sprintf("Location: %s", $insertGoTo);
			header($redirect_go_to);
			
		} // if ($action == "add") 

		if ($action == "edit") {
			
			$db_conn->where ('id', $id);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			if (!empty($error_output)) $_SESSION['error_output'] = $error_output;
			
			if ($errors) $insertGoTo = $_POST['relocate']."&msg=3";
			$insertGoTo = prep_redirect_link($insertGoTo);
			$redirect_go_to = sprintf("Location: %s", $insertGoTo);
			header($redirect_go_to);
			
		} // end if ($action == "edit")

	}

	if ($section == "process-eval-full") {
		
		if ((!empty($_POST['evalDescriptors'])) && (is_array($_POST['evalDescriptors']))) $evalDescriptors = implode(", ",$_POST['evalDescriptors']);

		if (($action == "add") || ($action == "edit")) {

			$update_table = $prefix."evaluation";
			$data = array(
				'eid' => $eid,
				'uid' => $uid,
				'evalJudgeInfo' => $evalJudgeInfo, 
				'evalScoresheet' => $evalScoresheet, 
				'evalStyle' => $evalStyle, 	
				'evalSpecialIngredients' => $evalSpecialIngredients, 
				'evalOtherNotes' => $evalOtherNotes, 
				'evalAromaScore' => $evalAromaScore, 
				'evalAromaChecklist' => $evalAromaChecklist, 
				'evalAromaComments' => $evalAromaComments, 
				'evalAppearanceScore' => $evalAppearanceScore, 
				'evalAppearanceChecklist' => $evalAppearanceChecklist, 
				'evalAppearanceComments' => $evalAppearanceComments, 
				'evalFlavorScore' => $evalFlavorScore, 
				'evalFlavorChecklist' => $evalFlavorChecklist, 
				'evalFlavorComments' => $evalFlavorComments, 
				'evalMouthfeelScore' => $evalMouthfeelScore, 
				'evalMouthfeelChecklist' => $evalMouthfeelChecklist, 
				'evalMouthfeelComments' => $evalMouthfeelComments, 
				'evalOverallScore' => $evalOverallScore, 
				'evalOverallComments' => $evalOverallComments, 
				'evalStyleAccuracy' => $evalStyleAccuracy, 
				'evalTechMerit' => $evalTechMerit, 
				'evalIntangibles' => $evalIntangibles, 
				'evalFlaws' => $evalFlaws, 
				'evalInitialDate' => time(), 
				'evalUpdatedDate' => time(), 
				'evalToken' => $token,
				'evalTable' => $evalTable, 
				'evalFinalScore' => $evalFinalScore, 
				'evalMiniBOS' => $evalMiniBOS,
				'evalBottle' => $evalBottle,
				'evalBottleNotes' => $evalBottleNotes,
				'evalPosition' => $evalPosition,
				'evalDescriptors' => $evalDescriptors
			);

		}
		
		if ($action == "add") {
			
			$result = $db_conn->insert ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			if (!empty($error_output)) $_SESSION['error_output'] = $error_output;
			
			if ($errors) $insertGoTo = $_POST['relocate']."&msg=3";
			$insertGoTo = prep_redirect_link($insertGoTo);
			$redirect_go_to = sprintf("Location: %s", $insertGoTo);
			header($redirect_go_to);
			
		} // end if ($action == "add")
		
		
		if ($action == "edit") {
				
			$db_conn->where ('id', $id);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			if (!empty($error_output)) $_SESSION['error_output'] = $error_output;
			
			if ($errors) $insertGoTo = $_POST['relocate']."&msg=3";
			$insertGoTo = prep_redirect_link($insertGoTo);
			$redirect_go_to = sprintf("Location: %s", $insertGoTo);
			header($redirect_go_to);
			
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

		if (($action == "add") || ($action == "edit")) {

			$update_table = $prefix."evaluation";
			$data = array(
				'eid' => $eid,
				'uid' => $uid,
				'evalJudgeInfo' => $evalJudgeInfo, 
				'evalScoresheet' => $evalScoresheet, 
				'evalStyle' => $evalStyle, 	
				'evalSpecialIngredients' => $evalSpecialIngredients, 
				'evalOtherNotes' => $evalOtherNotes, 
				'evalAromaScore' => $evalAromaScore, 
				'evalAromaChecklist' => $evalAromaChecklist, 
				'evalAromaChecklistDesc' => $evalAromaChecklistDesc, 
				'evalAromaComments' => $evalAromaComments, 
				'evalAppearanceScore' => $evalAppearanceScore, 
				'evalAppearanceChecklist' => $evalAppearanceChecklist, 
				'evalAppearanceChecklistDesc' => $evalAppearanceChecklistDesc, 
				'evalAppearanceComments' => $evalAppearanceComments, 
				'evalFlavorScore' => $evalFlavorScore, 
				'evalFlavorChecklist' => $evalFlavorChecklist, 
				'evalFlavorChecklistDesc' => $evalFlavorChecklistDesc, 
				'evalFlavorComments' => $evalFlavorComments, 
				'evalMouthfeelScore' => $evalMouthfeelScore, 
				'evalMouthfeelChecklist' => $evalMouthfeelChecklist, 
				'evalMouthfeelChecklistDesc' => $evalMouthfeelChecklistDesc, 
				'evalMouthfeelComments' => $evalMouthfeelComments, 
				'evalOverallScore' => $evalOverallScore, 
				'evalOverallComments' => $evalOverallComments, 
				'evalOverallChecklistDesc' => $evalOverallChecklistDesc, 
				'evalStyleAccuracy' => $evalStyleAccuracy, 
				'evalTechMerit' => $evalTechMerit, 
				'evalIntangibles' => $evalIntangibles, 
				'evalFlaws' => $evalFlaws, 
				'evalInitialDate' => time(), 
				'evalUpdatedDate' => time(), 
				'evalToken' => $token,
				'evalTable' => $evalTable, 
				'evalFinalScore' => $evalFinalScore, 
				'evalMiniBOS' => $evalMiniBOS,
				'evalBottle' => $evalBottle,
				'evalBottleNotes' => $evalBottleNotes,
				'evalPosition' => $evalPosition,
				'evalDrinkability' => $evalDrinkability
			);

		}
		
		if ($action == "add") {

			$result = $db_conn->insert ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			if (!empty($error_output)) $_SESSION['error_output'] = $error_output;
			
			if ($errors) $insertGoTo = $_POST['relocate']."&msg=3";
			$insertGoTo = prep_redirect_link($insertGoTo);
			$redirect_go_to = sprintf("Location: %s", $insertGoTo);
			header($redirect_go_to);
			
		} // end if ($action == "add") 
		
		if ($action == "edit") {
			
			$db_conn->where ('id', $id);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			if (!empty($error_output)) $_SESSION['error_output'] = $error_output;
			
			if ($errors) $insertGoTo = $_POST['relocate']."&msg=3";
			$insertGoTo = prep_redirect_link($insertGoTo);
			$redirect_go_to = sprintf("Location: %s", $insertGoTo);
			header($redirect_go_to);
			
		} // end if ($action == "edit")

	} // end if (($section == "process-eval-full") || ($section == "process-eval-checklist")) 

} else {

	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);
	header($redirect_go_to);
	
}
?>