<?php

/*
 * Define universal vars
 */
$evalFlagship = "";
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

require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
$config_html_purifier = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config_html_purifier);

if (!empty($_POST['evalFlagship'])) $evalFlagship = $_POST['evalFlagship'];
if (!empty($_POST['evalSpecialIngredients'])) $evalSpecialIngredients = $purifier->purify($_POST['evalSpecialIngredients']);
if (!empty($_POST['evalOtherNotes'])) $evalOtherNotes = $purifier->purify($_POST['evalOtherNotes']);
if (!empty($_POST['evalAromaComments'])) $evalAromaComments = $purifier->purify($_POST['evalAromaComments']);
if (!empty($_POST['evalAppearanceComments'])) $evalAppearanceComments = $purifier->purify($_POST['evalAppearanceComments']);
if (!empty($_POST['evalFlavorComments'])) $evalFlavorComments = $purifier->purify($_POST['evalFlavorComments']); 
if (!empty($_POST['evalMouthfeelComments'])) $evalMouthfeelComments = $purifier->purify($_POST['evalMouthfeelComments']); 
if (!empty($_POST['evalOverallComments'])) $evalOverallComments = $purifier->purify($_POST['evalOverallComments']);
if (!empty($_POST['evalIntangibles'])) $evalIntangibles = $purifier->purify($_POST['evalIntangibles']);
if (!empty($_POST['eid'])) $eid = $_POST['eid'];
if (!empty($_POST['uid'])) $uid = $_POST['uid'];
if (!empty($_POST['token'])) $token = $_POST['token'];

if (isset($_POST['evalStyleAnon'])) $evalStyle = $_POST['evalStyleAnon'];
else $evalStyle = $_POST['evalStyle'];

/*
if (!empty($uid)) {

	if ($action == "add") $token = generate_token();
	if ($action == "edit") $token = $_POST['token'];

	// Query for user's info
	$query_user_info = sprintf("SELECT first_name, last_name, email FROM %s WHERE id = '%s'", $prefix."users", $uid);
    $user_info = mysqli_query($connection,$query_user_info) or die (mysqli_error($connection));
    $row_user_info = mysqli_fetch_assoc($user_info);

	// Build user email
    $url = str_replace("www.","",$_SERVER['SERVER_NAME']);
	$to_recipient_user = $row_user_info['first_name']." ".$row_user_info['last_name'];
	$to_email_user = $row_user_info['email'];
	$subject_user = sprintf("%s - %s",$_SESSION['prefs_portal_title'],$label_evaluation_complete);

	$message_user = "";
	$message_user .= "<html>" . "\r\n";
	$message_user .= "<body>" . "\r\n";
	$message_user .= sprintf("<p>%s,</p>",$row_user_info['first_name']);
	$message_user .= sprintf("<p>%s %s</p>",$evaluation_text_000,$evaluation_text_001);
	if ($section == "process-eval-full") $message_user .= sprintf("<p><a href='%s'>%s</a></p>",$base_url."print.php?section=eval-full&token=".$token,$label_view_evaluation);
	if ($section == "process-eval-checklist") $message_user .= sprintf("<p><a href='%s'>%s</a></p>",$base_url."print.php?section=eval-checklist&token=".$token,$label_view_evaluation);
	$message_user .= sprintf("<p>%s</p>",$evaluation_text_002);
	$message_user .= "</body>" . "\r\n";
	$message_user .= "</html>";

	$headers_user  = "MIME-Version: 1.0" . "\r\n";
	$headers_user .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
	$headers_user .= sprintf("%s: ".$to_recipient_user. " <".$to_email_user.">, " . "\r\n",$label_to);
	$headers_user .= sprintf("%s: %s  <noreply@".$url. ">\r\n",$label_from,$_SESSION['prefs_portal_title']);

	// Build judge Email
	$query_judge_info = sprintf("SELECT first_name, last_name FROM %s WHERE email = '%s'", $prefix."users", $_POST['evalJudgeInfo']);
    $user_info = mysqli_query($connection,$query_judge_info) or die (mysqli_error($connection));
    $row_judge_info = mysqli_fetch_assoc($user_info);

	$to_recipient_judge = $row_judge_info['first_name']." ".$row_judge_info['last_name'];
	$to_email_judge = $_POST['evalJudgeInfo'];
	$subject_judge = sprintf("%s - %s",$_SESSION['prefs_portal_title'],$label_evaluation_complete);

	$message_judge = "";
	$message_judge .= "<html>" . "\r\n";
	$message_judge .= "<body>" . "\r\n";
	$message_judge .= sprintf("<p>%s,</p>",$row_judge_info['first_name']);
	$message_judge .= sprintf("<p>%s %s</p>",$evaluation_text_003,$evaluation_text_004);
	if ($section == "process-eval-full") $message_judge .= sprintf("<p><a href='%s'>%s</a></p>",$base_url."print.php?section=eval-full&token=".$token,$label_view_evaluation);
	if ($section == "process-eval-checklist") $message_judge .= sprintf("<p><a href='%s'>%s</a></p>",$base_url."print.php?section=eval-checklist&token=".$token,$label_view_evaluation);
	$message_judge .= sprintf("<p>%s</p>",$evaluation_text_005);
	$message_judge .= sprintf("<p>%s</p>",$evaluation_text_006);
	$message_judge .= "</body>" . "\r\n";
	$message_judge .= "</html>";

	$headers_judge  = "MIME-Version: 1.0" . "\r\n";
	$headers_judge .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
	$headers_judge .= sprintf("%s: ".$to_recipient_judge. " <".$to_email_judge.">, " . "\r\n",$label_to);
	$headers_judge .= sprintf("%s: %s  <noreply@".$url. ">\r\n",$label_from,$_SESSION['prefs_portal_title']);

	echo $url;
	
	echo $headers_user."<br>";
	echo $subject_user."<br>";
	echo $message_user."<br>";

	echo $headers_judge."<br>";
	echo $subject_judge."<br>";
	echo $message_judge;

	exit;

}

*/

if ($section == "process-eval-full") {
	
	if ((!empty($_POST['evalDescriptors'])) && (is_array($_POST['evalDescriptors']))) $evalDescriptors = implode(", ",$_POST['evalDescriptors']);
	
	if ($action == "add") {
		
		$insertSQL = sprintf("INSERT INTO evaluation 
		(
		evalJudgeInfo, 
		evalScoresheet, 
		evalBreweryName, 
		evalBreweryLocation,
		evalBeerName,
		
		evalService, 
		evalServiceCond, 
		evalLocation, 
		evalFlagship, 
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

		uid
		) 
		
		VALUES (
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s
		)",
							GetSQLValueString($_POST['evalJudgeInfo'], "text"), 
							GetSQLValueString($_POST['evalScoresheet'], "text"), 
							GetSQLValueString(ucwords($purifier->purify($_POST['evalBreweryName'])), "text"), 
							GetSQLValueString(ucwords($purifier->purify($_POST['evalBreweryLocation'])), "text"),
							GetSQLValueString(ucwords($purifier->purify($_POST['evalBeerName'])), "text"), 
							
							GetSQLValueString($_POST['evalService'], "text"), 
							GetSQLValueString($_POST['evalServiceCond'], "text"), 
							GetSQLValueString($_POST['evalLocation'], "text"), 
							GetSQLValueString($evalFlagship, "text"), 
							GetSQLValueString($evalStyle, "text"),
							
							GetSQLValueString($evalSpecialIngredients, "text"), 
							GetSQLValueString($evalOtherNotes, "text"), 
							GetSQLValueString($_POST['evalAromaScore'], "text"),									
							GetSQLValueString($evalAromaComments, "text"), 
							GetSQLValueString($_POST['evalAppearanceScore'], "text"),
							
							GetSQLValueString($_POST['evalAppearanceComments'], "text"),
							GetSQLValueString($_POST['evalFlavorScore'], "text"),
							GetSQLValueString($evalFlavorComments, "text"), 
							GetSQLValueString($_POST['evalMouthfeelScore'], "text"),
							GetSQLValueString($evalMouthfeelComments, "text"), 
							
							GetSQLValueString($_POST['evalOverallScore'], "text"), 
							GetSQLValueString($evalOverallComments, "text"),
							GetSQLValueString($_POST['evalStyleAccuracy'], "text"), 
							GetSQLValueString($_POST['evalTechMerit'], "text"), 
							GetSQLValueString($evalIntangibles, "text"), 
							
							GetSQLValueString($evalDescriptors, "text"),
							GetSQLValueString(time(), "text"), 
							GetSQLValueString(time(), "text"),
							GetSQLValueString($token, "text"),
							GetSQLValueString($eid, "text"),
							GetSQLValueString($uid, "text")
		);
		
		//echo $insertSQL;
		//exit;
		
		mysqli_real_escape_string($connection,$insertSQL);
		$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));

		// Send emails to entrant and judge if anonymous entry type
		if ($result) {
			if (!empty($uid)) mail($to_email_user, $subject_user, $message_user, $headers_user);
			if (!empty($_POST['evalJudgeInfo'])) mail($to_email_judge, $subject_judge, $message_judge, $headers_judge);
		}
			
		$insertGoTo = $base_url."list/".strtolower($_SESSION['last_name'] )."/dashboard/".$_SESSION['user_id']."/5";
		
		$pattern = array('\'', '"');
		$insertGoTo = str_replace($pattern, "", $insertGoTo); 
		header(sprintf("Location: %s", stripslashes($insertGoTo)));
		
	} // end if ($action == "add")
	
	
	if ($action == "edit") {
		
		$updateSQL = sprintf("UPDATE evaluation SET 
		
		evalJudgeInfo=%s, 
		evalScoresheet=%s, 
		evalBreweryName=%s, 
		evalBreweryLocation=%s,
		evalBeerName=%s,
		
		evalService=%s, 
		evalServiceCond=%s, 
		evalLocation=%s, 
		evalFlagship=%s, 
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
		uid=%s 
		WHERE id=%s
		",
							GetSQLValueString($_POST['evalJudgeInfo'], "text"), 
							GetSQLValueString($_POST['evalScoresheet'], "text"), 
							GetSQLValueString(ucwords($purifier->purify($_POST['evalBreweryName'])), "text"), 
							GetSQLValueString(ucwords($purifier->purify($_POST['evalBreweryLocation'])), "text"),
							GetSQLValueString(ucwords($purifier->purify($_POST['evalBeerName'])), "text"), 
							
							GetSQLValueString($_POST['evalService'], "text"), 
							GetSQLValueString($_POST['evalServiceCond'], "text"), 
							GetSQLValueString($_POST['evalLocation'], "text"), 
							GetSQLValueString($evalFlagship, "text"), 
							GetSQLValueString($evalStyle, "text"),
							
							GetSQLValueString($evalSpecialIngredients, "text"), 
							GetSQLValueString($evalOtherNotes, "text"), 
							GetSQLValueString($_POST['evalAromaScore'], "text"),									
							GetSQLValueString($evalAromaComments, "text"), 
							GetSQLValueString($_POST['evalAppearanceScore'], "text"), 
							
							GetSQLValueString($_POST['evalAppearanceComments'], "text"),
							GetSQLValueString($_POST['evalFlavorScore'], "text"),
							GetSQLValueString($evalFlavorComments, "text"), 
							GetSQLValueString($_POST['evalMouthfeelScore'], "text"),
							GetSQLValueString($evalMouthfeelComments, "text"),  
							
							GetSQLValueString($_POST['evalOverallScore'], "text"), 
							GetSQLValueString($evalOverallComments, "text"),
							GetSQLValueString($_POST['evalStyleAccuracy'], "text"), 
							GetSQLValueString($_POST['evalTechMerit'], "text"), 
							GetSQLValueString($evalIntangibles, "text"), 
							
							GetSQLValueString($evalDescriptors, "text"),
							GetSQLValueString(time(), "text"),
							GetSQLValueString($eid, "text"),
							GetSQLValueString($uid, "text"),
							GetSQLValueString($id, "int")
		);
		
		//echo $updateSQL;
		//exit;
		
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			
		if ($msg == "admin") $insertGoTo = $base_url."eval-admin/evaluations/default/default/6";
		else $insertGoTo = $base_url."list/".strtolower($_SESSION['last_name'] )."/dashboard/".$_SESSION['user_id'] ."/6";
		
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
	echo $_POST['evalBeerName']."<br>";
	echo $evalAromaChecklist."<br>";
	echo $evalAromaChecklistDesc."<br>";
	echo $evalAppearanceChecklist."<br>";
	echo $evalAppearanceChecklistDesc."<br>";
	echo $evalFlavorChecklist."<br>";
	echo $evalFlavorChecklistDesc."<br>";
	echo $evalMouthfeelChecklist."<br>";
	echo $evalMouthfeelChecklistDesc."<br>";
	*/
	
	
	if ($action == "add") {

		$insertSQL = sprintf("INSERT INTO evaluation 
		(
		evalJudgeInfo, 
		evalScoresheet, 
		evalBreweryName, 
		evalBreweryLocation,
		evalBeerName,
		
		evalService, 
		evalServiceCond, 
		evalLocation, 
		evalFlagship, 
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
		uid
		) 
		
		VALUES (
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s
		)",
							GetSQLValueString($_POST['evalJudgeInfo'], "text"), 
							GetSQLValueString($_POST['evalScoresheet'], "text"), 
							GetSQLValueString(ucwords($purifier->purify($_POST['evalBreweryName'])), "text"), 
							GetSQLValueString(ucwords($purifier->purify($_POST['evalBreweryLocation'])), "text"),
							GetSQLValueString(ucwords($purifier->purify($_POST['evalBeerName'])), "text"), 
							
							GetSQLValueString($_POST['evalService'], "text"), 
							GetSQLValueString($_POST['evalServiceCond'], "text"), 
							GetSQLValueString($_POST['evalLocation'], "text"), 
							GetSQLValueString($evalFlagship, "text"), 
							GetSQLValueString($evalStyle, "text"), 
							
							GetSQLValueString($evalSpecialIngredients, "text"), 
							GetSQLValueString($evalOtherNotes, "text"), 
							GetSQLValueString($_POST['evalAromaScore'], "text"), 
							GetSQLValueString($evalAromaChecklist, "text"), 
							GetSQLValueString($evalAromaChecklistDesc, "text"), 
							
							GetSQLValueString($_POST['evalAromaComments'], "text"), 
							GetSQLValueString($_POST['evalAppearanceScore'], "text"), 
							GetSQLValueString($evalAppearanceChecklist, "text"), 
							GetSQLValueString($evalAppearanceChecklistDesc, "text"), 
							GetSQLValueString($evalAppearanceComments, "text"), 
							
							GetSQLValueString($_POST['evalFlavorScore'], "text"), 
							GetSQLValueString($evalFlavorChecklist, "text"), 
							GetSQLValueString($evalFlavorChecklistDesc, "text"), 
							GetSQLValueString($evalFlavorComments, "text"), 
							GetSQLValueString($_POST['evalMouthfeelScore'], "text"), 
							
							GetSQLValueString($evalMouthfeelChecklist, "text"), 
							GetSQLValueString($evalMouthfeelChecklistDesc, "text"), 
							GetSQLValueString($evalMouthfeelComments, "text"), 
							GetSQLValueString($_POST['evalOverallScore'], "text"), 
							GetSQLValueString($evalOverallComments, "text"), 
							
							GetSQLValueString($_POST['evalStyleAccuracy'], "text"), 
							GetSQLValueString($_POST['evalTechMerit'], "text"), 
							GetSQLValueString($evalIntangibles, "text"),
							GetSQLValueString($_POST['evalDrinkability'], "text"),
							GetSQLValueString($evalFlaws, "text"),
							 
							GetSQLValueString(time(), "text"), 
							GetSQLValueString(time(), "text"),
							GetSQLValueString($token, "text"),
							GetSQLValueString($eid, "text"),
							GetSQLValueString($uid, "text")
		);
		
		//echo $insertSQL;
		//exit;
		
		mysqli_real_escape_string($connection,$insertSQL);
		$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));


		// Send email to entrant if anonymous entry
		if (($result) && (!empty($uid))) {
			mail($to_email, $subject, $message, $headers);
		}

			
		$insertGoTo = $base_url."list/".strtolower($_SESSION['last_name'] )."/dashboard/".$_SESSION['user_id']."/5";
		
		$pattern = array('\'', '"');
		$insertGoTo = str_replace($pattern, "", $insertGoTo); 
		header(sprintf("Location: %s", stripslashes($insertGoTo)));
		
	} // if ($action == "add") 
	
	
	if ($action == "edit") {
		
		$updateSQL = sprintf("UPDATE evaluation SET 
		
		evalJudgeInfo=%s, 
		evalScoresheet=%s, 
		evalBreweryName=%s, 
		evalBreweryLocation=%s,
		evalBeerName=%s,
		
		evalService=%s, 
		evalServiceCond=%s, 
		evalLocation=%s, 
		evalFlagship=%s, 
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
		uid=%s		
		WHERE id=%s
		",
							GetSQLValueString($_POST['evalJudgeInfo'], "text"), 
							GetSQLValueString($_POST['evalScoresheet'], "text"), 
							GetSQLValueString(ucwords($purifier->purify($_POST['evalBreweryName'])), "text"), 
							GetSQLValueString(ucwords($purifier->purify($_POST['evalBreweryLocation'])), "text"),
							GetSQLValueString(ucwords($purifier->purify($_POST['evalBeerName'])), "text"), 
							
							GetSQLValueString($_POST['evalService'], "text"), 
							GetSQLValueString($_POST['evalServiceCond'], "text"), 
							GetSQLValueString($_POST['evalLocation'], "text"), 
							GetSQLValueString($evalFlagship, "text"), 
							GetSQLValueString($evalStyle, "text"), 
							
							GetSQLValueString($evalSpecialIngredients, "text"), 
							GetSQLValueString($evalOtherNotes, "text"), 
							GetSQLValueString($_POST['evalAromaScore'], "text"), 
							GetSQLValueString($evalAromaChecklist, "text"), 
							GetSQLValueString($evalAromaChecklistDesc, "text"), 
							
							GetSQLValueString($_POST['evalAromaComments'], "text"), 
							GetSQLValueString($_POST['evalAppearanceScore'], "text"), 
							GetSQLValueString($evalAppearanceChecklist, "text"), 
							GetSQLValueString($evalAppearanceChecklistDesc, "text"), 
							GetSQLValueString($evalAppearanceComments, "text"), 
							
							GetSQLValueString($_POST['evalFlavorScore'], "text"), 
							GetSQLValueString($evalFlavorChecklist, "text"), 
							GetSQLValueString($evalFlavorChecklistDesc, "text"), 
							GetSQLValueString($evalFlavorComments, "text"), 
							GetSQLValueString($_POST['evalMouthfeelScore'], "text"), 
							
							GetSQLValueString($evalMouthfeelChecklist, "text"), 
							GetSQLValueString($evalMouthfeelChecklistDesc, "text"), 
							GetSQLValueString($evalMouthfeelComments, "text"), 
							GetSQLValueString($_POST['evalOverallScore'], "text"), 
							GetSQLValueString($evalOverallComments, "text"), 
							
							GetSQLValueString($_POST['evalStyleAccuracy'], "text"), 
							GetSQLValueString($_POST['evalTechMerit'], "text"), 
							GetSQLValueString($evalIntangibles, "text"),
							GetSQLValueString($_POST['evalDrinkability'], "text"),
							GetSQLValueString($evalFlaws, "text"),
							
							GetSQLValueString(time(), "text"),
							GetSQLValueString($eid, "text"),
							GetSQLValueString($uid, "text"),
							GetSQLValueString($id, "int")
		);
		
		//echo $updateSQL;
		//exit;
		
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			
		if ($msg == "admin") $insertGoTo = $base_url."eval-admin/evaluations/default/default/6";
		else $insertGoTo = $base_url."list/".strtolower($_SESSION['last_name'] )."/dashboard/".$_SESSION['user_id'] ."/6";
		
		$pattern = array('\'', '"');
		$insertGoTo = str_replace($pattern, "", $insertGoTo); 
		header(sprintf("Location: %s", stripslashes($insertGoTo)));
		
		
	} // end if ($action == "edit")


} // end if (($section == "process-eval-full") || ($section == "process-eval-checklist")) 

?>