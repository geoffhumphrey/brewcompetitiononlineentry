<?php
/**
 * Module:      process_brewing.inc.php
 * Description: This module does all the heavy lifting for adding entries to the DB
 */

/*
foreach ($_POST as $key => $value) {
	echo $key.": ".$value."<br>";
}
exit();
*/

$errors = FALSE;
$error_output = array();
$_SESSION['error_output'] = "";

if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && (isset($_SESSION['userLevel'])))) {

	include (DB.'entries.db.php');
	include (INCLUDES.'constants.inc.php');

	$query_user = sprintf("SELECT id,userLevel FROM $users_db_table WHERE user_name = '%s'", $_SESSION['loginUsername']);
	$user = mysqli_query($connection,$query_user) or die (mysqli_error($connection));
	$row_user = mysqli_fetch_assoc($user);

	$process_allowed_entries = FALSE;

	/**
	 * Perform various checks
	 */

	// If non-admin, can only submit under their own brewer ID
	if (($row_user['userLevel'] > 1) && ($row_user['id'] == $_POST['brewBrewerID'])) $process_allowed_entries = TRUE;

	// Cannot submit if entry limits have been reached or exceeded
	if (!$comp_entry_limit) $process_allowed_entries = TRUE;
	if (!$comp_paid_entry_limit) $process_allowed_entries = TRUE;

	// Allow processing if admin
	if ($row_user['userLevel'] <= 1) $process_allowed_entries = TRUE;	

	if (($row_limits['prefsUserEntryLimit'] != "") && ($row_user['userLevel'] == 2) && ($action == "add")) {

		// Check if user has reached the limit of entries in a particular sub-category. If so, redirect.
		$query_brews = sprintf("SELECT COUNT(*) as 'count' FROM $brewing_db_table WHERE brewBrewerId = '%s'", $_SESSION['user_id']);
		$brews = mysqli_query($connection,$query_brews) or die (mysqli_error($connection));
		$row_brews = mysqli_fetch_assoc($brews);

			if ($row_brews['count'] >= $row_limits['prefsUserEntryLimit']) {
				$insertGoTo = $base_url."index.php?section=list&msg=8";
				$insertGoTo = prep_redirect_link($insertGoTo);
				$redirect_go_to = sprintf("Location: %s", $insertGoTo);
				header($redirect_go_to);
				exit();
			}

	}

	$subcat_limit = FALSE;

	if ((isset($_SESSION['prefsUserSubCatLimit'])) && (!empty($_SESSION['prefsUserSubCatLimit']))) {
		if (($action == "add") || (($action == "edit") && ($entry_window_open == 1) && ($_POST['brewStyle'] != $_POST['brewEditStyle']))) {
			$subcat_limit = limit_subcategory(filter_var($_POST['brewStyle'],FILTER_SANITIZE_FULL_SPECIAL_CHARS),$_SESSION['prefsUserSubCatLimit'],$_SESSION['prefsUSCLExLimit'],$_SESSION['prefsUSCLEx'],filter_var($_POST['brewBrewerID'],FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		}
		
	}

	if (($subcat_limit) && ($row_user['userLevel'] == 2)) {
		$insertGoTo = $base_url."index.php?section=list&msg=9";
		$insertGoTo = prep_redirect_link($insertGoTo);
		$redirect_go_to = sprintf("Location: %s", $insertGoTo);
		header($redirect_go_to);
		exit();
	}

	if (!$process_allowed_entries) {
		session_unset();
		session_destroy();
		session_write_close();
		$redirect = $base_url."403.php";
		$redirect = prep_redirect_link($redirect);
		$redirect_go_to = sprintf("Location: %s", $redirect);
		header($redirect_go_to);
		exit();
	}

	// Instantiate HTMLPurifier
	require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
	$config_html_purifier = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config_html_purifier);

	include (CLASSES.'capitalize_name/parser.php');
	$name_parser = new FullNameParser();

	if (($action == "add") || ($action == "edit")) {

		if ($_SESSION['prefsStyleSet'] == "BA") include (INCLUDES.'ba_constants.inc.php');
		include (DB.'styles_special.db.php');

		// Set up vars
		$brewComments = "";
		$brewCoBrewer = "";
		$styleBreak = filter_var($_POST['brewStyle'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$styleName = "";
		$brewName = $purifier->purify($_POST['brewName']);
		$brewName = filter_var($brewName,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$brewName = capitalize($brewName);
		$brewInfo = "";
		$brewInfoOptional = "";
		$index = ""; // Defined with Style
		$brewMead1 = ""; // Carbonation
		$brewMead2 = ""; // Sweetness
		$brewMead3 = ""; // Strength
		$brewJudgingNumber = "";
		$brewPossAllergens = "";
		$brewAdminNotes = "";
		$brewStaffNotes = "";
		$brewBoxNum = "";
		$brewPaid = 0;
		$brewReceived = 0;
		$brewABV = "";
		$brewSweetnessLevel = "";
		$brewJuiceSource = "";
		$brewPouring = "";

		// Comments
		if ((isset($_POST['brewComments'])) && (!empty($_POST['brewComments']))) {
			$brewComments = $purifier->purify($_POST['brewComments']);
			$brewComments = filter_var($brewComments,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}

		// Co Brewer
		if ((isset($_POST['brewCoBrewer'])) && (!empty($_POST['brewCoBrewer']))) {

			$brewCoBrewer = $purifier->purify($_POST['brewCoBrewer']);
			
			if ((isset($_SESSION['prefsLanguageFolder'])) && (in_array($_SESSION['prefsLanguageFolder'], $name_check_langs))) {
		    	
		    	$parsed_name = $name_parser->parse_name($brewCoBrewer);

		    	$first_name = "";
			    if (!empty($parsed_name['salutation'])) $first_name .= $parsed_name['salutation']." ";
			    $first_name .= $parsed_name['fname'];
			    if (!empty($parsed_name['initials'])) $first_name .= " ".$parsed_name['initials'];
			    
			    $last_name = "";
			    if ((isset($_SESSION['prefsLanguageFolder'])) && (in_array($_SESSION['prefsLanguageFolder'], $last_name_exception_langs))) $last_name .= standardize_name($parsed_name['lname']);
			    else $last_name .= $parsed_name['lname']; 
			    if (!empty($parsed_name['suffix'])) $last_name .= " ".$parsed_name['suffix']; 

			    $brewCoBrewer = $first_name." ".$last_name;

			}

			$brewCoBrewer = filter_var($brewCoBrewer,FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		}
		
		// Possible Allergens
		if ((isset($_POST['brewPossAllergens'])) && (!empty($_POST['brewPossAllergens']))) {
			$brewPossAllergens = $purifier->purify($_POST['brewPossAllergens']);
			$brewPossAllergens = filter_var($brewPossAllergens,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		} 

		if ($_SESSION['userLevel'] <= 1) {

			// Admin and Staff Notes
			if ((isset($_POST['brewAdminNotes'])) && (!empty($_POST['brewAdminNotes']))) {
				$brewAdminNotes = $purifier->purify($_POST['brewAdminNotes']);
				$brewAdminNotes = filter_var($brewAdminNotes,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			}

			if ((isset($_POST['brewStaffNotes'])) && (!empty($_POST['brewStaffNotes']))) {
				$brewStaffNotes = $purifier->purify($_POST['brewStaffNotes']);
				$brewStaffNotes = filter_var($brewStaffNotes,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			}

			if ((isset($_POST['brewBoxNum'])) && (!empty($_POST['brewBoxNum']))) {
				$brewBoxNum = $purifier->purify($_POST['brewBoxNum']);
				$brewBoxNum = filter_var($brewBoxNum,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			}

		}

		// ABV
		if ((isset($_POST['brewABV'])) && (!empty($_POST['brewABV']))) {
			$brewABV = $purifier->purify($_POST['brewABV']);
			$brewABV = filter_var($brewABV, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
		}

		// Sweetness Level (Specific Gravity)
		if ((isset($_POST['brewSweetnessLevel'])) && (!empty($_POST['brewSweetnessLevel']))) {
			$brewSweetnessLevel = $purifier->purify($_POST['brewSweetnessLevel']);
			$brewSweetnessLevel = filter_var($brewSweetnessLevel, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
			$brewSweetnessLevel = number_format($brewSweetnessLevel,3);
		}

		// Juice Sorce - From multi-select
		if ((isset($_POST['brewJuiceSource'])) && (!empty($_POST['brewJuiceSource']))) {
		    $juice_src = array("juice_src" => $_POST['brewJuiceSource']);
		}

		else $juice_src = array();

		if ((isset($_POST['brewJuiceSourceOther'])) && (!empty($_POST['brewJuiceSourceOther']))) {

		    $juice_src_other_arr = str_replace(", ",",",$_POST['brewJuiceSourceOther']);
		    $juice_src_other_arr = str_replace(",",",",$_POST['brewJuiceSourceOther']);
		    $juice_src_other_arr = str_replace("; ",",",$_POST['brewJuiceSourceOther']);
		    $juice_src_other_arr = str_replace(";",",",$_POST['brewJuiceSourceOther']);
		    $juice_src_other_arr = explode(",",$juice_src_other_arr);
		    
		    $juice_src_other = array();

		    foreach ($juice_src_other_arr as $value) {  
		        $value = $purifier->purify($value);
		        $value = sterilize($value);
		        $juice_src_other[] = strtoupper($value);
		    }

		    if (!empty($juice_src_other)) $juice_src_other_arr = array("juice_src_other" => $juice_src_other);

		}

		else $juice_src_other_arr = array();

		if ((empty($juice_src)) && (empty($juice_src_other_arr))) {
		    $brewJuiceSource = NULL;
		}

		else {
		    $brewJuiceSource = array();
		    $brewJuiceSource = array_merge($juice_src,$juice_src_other_arr);
		    $brewJuiceSource = json_encode($brewJuiceSource);
		}

		$pouring_instructions = array();

		if ((isset($_POST['brewPouringInst'])) && (!empty($_POST['brewPouringInst']))) {
			$pouring_instructions['pouring'] = sterilize($_POST['brewPouringInst']);		
		}

		if ((isset($_POST['brewPouringRouse'])) && (!empty($_POST['brewPouringRouse']))) {
			$pouring_instructions['pouring_rouse'] = sterilize($_POST['brewPouringRouse']);		
		}

		if ((isset($_POST['brewPouringNotes'])) && (!empty($_POST['brewPouringNotes']))) {
			$brewPouringNotes = $purifier->purify($_POST['brewPouringNotes']);
			$brewPouringNotes = sterilize($brewPouringNotes);
			$pouring_instructions['pouring_notes'] = $brewPouringNotes;
		}

		$brewPouring = json_encode($pouring_instructions);

		// Record Paid and Received
		if ($action == "add") {

			if ($_SESSION['userLevel'] <= 1) {
				if (isset($_POST['brewPaid'])) $brewPaid = $_POST['brewPaid'];
				if (isset($_POST['brewReceived'])) $brewReceived = $_POST['brewReceived'];
			}

			else {
				$brewPaid = 0;
				$brewReceived = 0;
			}

		}

		if ($action == "edit") {

			if (($_SESSION['userLevel'] <= 1) && (isset($_POST['brewPaid']))) $brewPaid = $_POST['brewPaid'];

			if (($_SESSION['userLevel'] <= 1) && (isset($_POST['brewReceived']))) $brewReceived = $_POST['brewReceived'];

			if (($_SESSION['userLevel'] > 1) || ((!isset($_POST['brewPaid'])) || (!isset($_POST['brewReceived'])))) {

				$query_entry_status = sprintf("SELECT brewPaid,brewReceived FROM %s WHERE id='%s'", $prefix."brewing",$id);
				$entry_status = mysqli_query($connection,$query_entry_status) or die (mysqli_error($connection));
				$row_entry_status = mysqli_fetch_assoc($entry_status);

				$brewPaid = $row_entry_status['brewPaid'];
				$brewReceived = $row_entry_status['brewReceived'];

			}

		}

		// Style
		$style = explode('-', $styleBreak);
		if (preg_match("/^[[:digit:]]+$/",$style[0])) $index = sprintf('%02d',$style[0])."-".$style[1];
		else $index = $style[0]."-".$style[1];
		$styleReturn = $index;
		$styleTrim = ltrim($style[0], "0");
		if (($style [0] < 10) && (preg_match("/^[[:digit:]]+$/",$style [0]))) $styleFix = "0".$style[0];
		else $styleFix = $style[0];
		$styleID = $style[1];

		// Style Name
		/*
		if (HOSTED) $query_style_name = sprintf("SELECT brewStyle, brewStyleCarb, brewStyleSweet, brewStyleStrength, brewStyleType FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s' UNION ALL SELECT brewStyle, brewStyleCarb, brewStyleSweet, brewStyleStrength, brewStyleType FROM %s WHERE brewStyleVersion='%s' AND brewStyleGroup='%s' AND brewStyleNum='%s'", $prefix."styles", $_SESSION['prefsStyleSet'], $styleFix, $style[1], "bcoem_shared_styles", $_SESSION['prefsStyleSet'], $styleFix, $style[1]);
		else 
		*/
		$query_style_name = sprintf("SELECT brewStyle, brewStyleCarb, brewStyleSweet, brewStyleStrength, brewStyleType FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s'", $prefix."styles", $_SESSION['prefsStyleSet'], $styleFix, $style[1]);
		$style_name = mysqli_query($connection,$query_style_name) or die (mysqli_error($connection));
		$row_style_name = mysqli_fetch_assoc($style_name);
		
		$styleName = $row_style_name['brewStyle'];

		// Mark as paid if free entry fee
		if ($_SESSION['contestEntryFee'] == 0) $brewPaid = 1;

		if (!empty($_POST['brewInfo'])) {
			$brewInfo = $purifier->purify($_POST['brewInfo']);
			$brewInfo = filter_var($brewInfo,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}

		// Specialized/Optional info
		if ((!empty($_POST['brewInfoOptional'])) && (in_array($styleBreak,$optional_info_styles))) {
			$brewInfoOptional = $purifier->purify($_POST['brewInfoOptional']);
			$brewInfoOptional = filter_var($brewInfoOptional,FILTER_SANITIZE_FULL_SPECIAL_CHARS);			
		}

		// For BJCP 2015/2021, process addtional info
		if (($_SESSION['prefsStyleSet'] == "BJCP2015") || ($_SESSION['prefsStyleSet'] == "BJCP2021")) {

			// If BJCP 2021 and 2A, add optional regional variation if present
			if (($index == "02-A") && ($_SESSION['prefsStyleSet'] == "BJCP2021") && (!empty($_POST['regionalVar']))) {
				$brewInfo = $purifier->purify($_POST['regionalVar']);
				$brewInfo = filter_var($brewInfo,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			}

			// IPA strength for 21B styles
			if (strlen(strstr($index,"21-B")) > 0) {
				if ($index == "21-B") $brewInfo .= "^".filter_var($_POST['strengthIPA'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				else $brewInfo .= filter_var($_POST['strengthIPA'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			}

			// Pale or Dark Variant
			if (($index == "09-A") || ($index == "10-C") || ($index == "07-C"))  $brewInfo = filter_var($_POST['darkLightColor'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);

			// Fruit Lambic carb/sweetness
			if ($index == "23-F") $brewInfo .= "^".filter_var($_POST['sweetnessLambic'],FILTER_SANITIZE_FULL_SPECIAL_CHARS,FILTER_FLAG_ENCODE_HIGH|FILTER_FLAG_ENCODE_LOW)."^".filter_var($_POST['carbLambic'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);

			// Biere de Garde color
			if ($index == "24-C") $brewInfo = filter_var($_POST['BDGColor'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);

			// Saison strength/color
			if ($index == "25-B") $brewInfo = filter_var($_POST['strengthSaison'],FILTER_SANITIZE_FULL_SPECIAL_CHARS,FILTER_FLAG_ENCODE_HIGH|FILTER_FLAG_ENCODE_LOW)."^".filter_var($_POST['darkLightColor'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		}

		if ($style[0] > 34) $styleID = $styleID; else $styleID = $style[1];

		if ($row_style_name) {

			if ((isset($_POST['brewMead1'])) && ($row_style_name['brewStyleCarb'] == 1)) $brewMead1 = filter_var($_POST['brewMead1'],FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Carbonation

			if ((isset($_POST['brewMead2-cider'])) && ($row_style_name['brewStyleSweet'] == 1) && ($row_style_name['brewStyleType'] == 2)) $brewMead2 = filter_var($_POST['brewMead2-cider'],FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Cider Sweetness

			if ((isset($_POST['brewMead2-mead'])) && ($row_style_name['brewStyleSweet'] == 1) && ($row_style_name['brewStyleType'] == 3)) $brewMead2 = filter_var($_POST['brewMead2-mead'],FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Mead Sweetness
			
			if ((isset($_POST['brewMead3'])) && ($row_style_name['brewStyleStrength'] == 1)) $brewMead3 = filter_var($_POST['brewMead3'],FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Strength

		}

		/*
		// DEBUG
		echo "ID: ".$styleID."<br>";
		echo "Carb: ".$brewMead1."<br>";
		echo "Sweet: ".$brewMead2."<br>";
		echo "Strength: ".$brewMead3;
		exit();
		*/

	}

	if ($action == "add") {

		if ($row_user['userLevel'] <= 1) {

			$query_brewer = sprintf("SELECT * FROM `%s` WHERE uid = '%s'", $brewer_db_table, $_POST['brewBrewerID']);
			$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
			$row_brewer = mysqli_fetch_assoc($brewer);

			$brewBrewerID = $row_brewer['uid'];
			$brewBrewerLastName = $row_brewer['brewerLastName'];
			$brewBrewerFirstName = $row_brewer['brewerFirstName'];

		}

		else {

			$brewBrewerID = filter_var($_POST['brewBrewerID'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$brewBrewerLastName = filter_var($_POST['brewBrewerLastName'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$brewBrewerFirstName = filter_var($_POST['brewBrewerFirstName'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		}


		$files = array_slice(scandir(USER_DOCS), 2);
		$judging_number_looper = TRUE;

		while($judging_number_looper) {

			$generated_judging_number = generate_judging_num(1,$styleTrim);
			$scoresheet_file_name_judging = strtolower($generated_judging_number).".pdf";

			if (!in_array($scoresheet_file_name_judging,$files))  {
				$brewJudgingNumber = strtolower($generated_judging_number);
				$judging_number_looper = FALSE;
			}

			else {
				$judging_number_looper = TRUE;
			}

		}

		$update_table = $prefix."brewing";
		$data = array(
			'brewName' => blank_to_null($brewName),
			'brewStyle' => blank_to_null($styleName),
			'brewCategory' => blank_to_null($styleTrim),
			'brewCategorySort' => blank_to_null($styleFix),
			'brewSubCategory' => blank_to_null($style[1]),
			'brewInfo' => blank_to_null($brewInfo),
			'brewMead1' => blank_to_null($brewMead1),
			'brewMead2' => blank_to_null($brewMead2),
			'brewMead3' => blank_to_null($brewMead3),
			'brewComments' => blank_to_null($brewComments),
			'brewBrewerID' => blank_to_null($brewBrewerID),
			'brewBrewerFirstName' => blank_to_null($brewBrewerFirstName),
			'brewBrewerLastName' => blank_to_null($brewBrewerLastName),
			'brewPaid' => blank_to_null($brewPaid),
			'brewInfoOptional' => blank_to_null($brewInfoOptional),
			'brewAdminNotes' => blank_to_null($brewAdminNotes),
			'brewStaffNotes' => blank_to_null($brewStaffNotes),
			'brewPossAllergens' => blank_to_null($brewPossAllergens),
			'brewReceived' => blank_to_null($brewReceived),
			'brewCoBrewer' => blank_to_null($brewCoBrewer),
			'brewJudgingNumber' => blank_to_null($brewJudgingNumber),
			'brewUpdated' => $db_conn->now(),
			'brewConfirmed' => blank_to_null(filter_var($_POST['brewConfirmed'],FILTER_SANITIZE_FULL_SPECIAL_CHARS)),
			'brewBoxNum' => blank_to_null($brewBoxNum),
			'brewABV' => blank_to_null($brewABV),
			'brewJuiceSource' => blank_to_null($brewJuiceSource),
			'brewSweetnessLevel' => blank_to_null($brewSweetnessLevel),
			'brewPouring' => blank_to_null($brewPouring)
		);
		$result = $db_conn->insert ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		if ($id == "default") {

			$query_brew_id = sprintf("SELECT id FROM `%s` WHERE brewBrewerID='%s' ORDER BY id DESC LIMIT 1",$brewing_db_table,$brewBrewerID);
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

				$update_table = $prefix."brewing";
				$data = array('brewConfirmed' => '0');
				$db_conn->where ('id', $id);
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

			if ($section == "admin") {
				if (empty($brewInfo)) $insertGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&view=$styleReturn&msg=1-".$styleReturn;
				else $insertGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
			}

			else {
				if (empty($brewInfo)) $insertGoTo = $base_url."index.php?section=brew&action=edit&id=$id&view=$styleReturn&msg=1-".$styleReturn;
				else $insertGoTo = $base_url."index.php?section=list&msg=2";
			}

		 }

		// $brewMead1 - Check if entry style requires carbonation
		if (check_carb($styleBreak,$_SESSION['prefsStyleSet'])) {

			if (empty($brewMead1)) {

				$update_table = $prefix."brewing";
				$data = array('brewConfirmed' => '0');
				$db_conn->where ('id', $id);
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

			if ($section == "admin") {
				if ((empty($brewMead1)) || (empty($brewMead2))) $insertGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&view=$styleReturn&msg=1-".$styleReturn;
				else $insertGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
			}

			else {
				if (empty($brewMead1)) $insertGoTo = $base_url."index.php?section=brew&action=edit&id=$id&view=$styleReturn&msg=1-".$styleReturn;
				else $insertGoTo = $base_url."index.php?section=list&msg=2";
			}

		 }

		 // $brewMead2 - Check if entry style requires sweetness
		 if (check_sweetness($styleBreak,$_SESSION['prefsStyleSet'])) {

			if (empty($brewMead2)) {

				$update_table = $prefix."brewing";
				$data = array('brewConfirmed' => '0');
				$db_conn->where ('id', $id);
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

			if ($section == "admin") {
				if (empty($brewMead2)) $insertGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&view=$styleReturn&msg=1-".$styleReturn;
				else $insertGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
			}

			else {
				if (empty($brewMead2)) $insertGoTo = $base_url."index.php?section=brew&action=edit&id=$id&view=$styleReturn&msg=1-".$styleReturn;
				else $insertGoTo = $base_url."index.php?section=list&msg=2";
			}

		 }

		// $brewMead3 - Check if entry style requires strength
		if (check_mead_strength($styleBreak,$_SESSION['prefsStyleSet'])) {

			if (empty($brewMead3)) {

				$update_table = $prefix."brewing";
				$data = array('brewConfirmed' => '0');
				$db_conn->where ('id', $id);
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

			if ($section == "admin") {
				if (empty($brewMead3)) $insertGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&view=$styleReturn&msg=1-".$styleReturn;
				else $insertGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
			}

			else {
				if (empty($brewMead3)) $insertGoTo = $base_url."index.php?section=brew&action=edit&id=$id&view=$styleReturn&msg=1-".$styleReturn;
				else $insertGoTo = $base_url."index.php?section=list&msg=2";
			}
		}

		if ((check_carb($styleBreak,$_SESSION['prefsStyleSet'])) && (check_sweetness($styleBreak,$_SESSION['prefsStyleSet'])) && (check_mead_strength($styleBreak,$_SESSION['prefsStyleSet']))) {

			if ((empty($brewMead1)) || (empty($brewMead2)) || (empty($brewMead3))) {
				
				$update_table = $prefix."brewing";
				$data = array('brewConfirmed' => '0');
				$db_conn->where ('id', $id);
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

			if ($section == "admin") {
				if ((empty($brewMead1)) || (empty($brewMead2)) || (empty($brewMead3)))  $insertGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&view=$styleReturn&msg=1-".$styleReturn;
				else $insertGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
			}

			else {
				if ((empty($brewMead1)) || (empty($brewMead2)) || (empty($brewMead3))) $insertGoTo = $base_url."index.php?section=brew&action=edit&id=$id&view=$styleReturn&msg=1-".$styleReturn;
				else $insertGoTo = $base_url."index.php?section=list&msg=2";
			}

		 }

		if ($errors) {
			if ($section == "admin") $insertGoTo = $base_url."index.php?section=admin&msg=3";
			else $insertGoTo = $base_url."index.php?section=list&msg=3";
		}
		$insertGoTo = prep_redirect_link($insertGoTo);
		$redirect_go_to = sprintf("Location: %s", $insertGoTo);

		/*
		// DEBUG
		echo $styleTrim."<br>";
		echo $brewJudgingNumber."<br>";
		echo $_POST['brewStyle']."<br>";
		echo $insertSQL."<br>";
		echo $insertGoTo;
		exit;
		*/

	} // end if ($action == "add")

	if ($action == "edit") {

		if ($row_user['userLevel'] <= 1) {

			$query_brewer = sprintf("SELECT * FROM $brewer_db_table WHERE uid = '%s'", filter_var($_POST['brewBrewerID'],FILTER_SANITIZE_FULL_SPECIAL_CHARS));
			$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
			$row_brewer = mysqli_fetch_assoc($brewer);
			
			$brewBrewerID = $row_brewer['uid'];
			$brewBrewerLastName = $row_brewer['brewerLastName'];
			$brewBrewerFirstName = $row_brewer['brewerFirstName'];

		}

		else {

			$brewBrewerID = filter_var($_POST['brewBrewerID'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$brewBrewerLastName = filter_var($_POST['brewBrewerLastName'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$brewBrewerFirstName = filter_var($_POST['brewBrewerFirstName'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		}

		$brewJudgingNumber = strtolower($_POST['brewJudgingNumber']);

		$update_table = $prefix."brewing";
		$data = array(
			'brewName' => $brewName,
			'brewStyle' => $styleName,
			'brewCategory' => $styleTrim,
			'brewCategorySort' => $styleFix,
			'brewSubCategory' => $style[1],
			'brewInfo' => $brewInfo,
			'brewMead1' => $brewMead1,
			'brewMead2' => $brewMead2,
			'brewMead3' => $brewMead3,
			'brewComments' => $brewComments,
			'brewBrewerID' => $brewBrewerID,
			'brewBrewerFirstName' => $brewBrewerFirstName,
			'brewBrewerLastName' => $brewBrewerLastName,
			'brewPaid' => $brewPaid,
			'brewInfoOptional' => $brewInfoOptional,
			'brewAdminNotes' => $brewAdminNotes,
			'brewStaffNotes' => $brewStaffNotes,
			'brewPossAllergens' => $brewPossAllergens,
			'brewReceived' => $brewReceived,
			'brewCoBrewer' => $brewCoBrewer,
			'brewJudgingNumber' => $brewJudgingNumber,
			'brewUpdated' => $db_conn->now(),
			'brewConfirmed' => filter_var($_POST['brewConfirmed'],FILTER_SANITIZE_FULL_SPECIAL_CHARS),
			'brewBoxNum' => $brewBoxNum,
			'brewABV' => blank_to_null($brewABV),
			'brewJuiceSource' => blank_to_null($brewJuiceSource),
			'brewSweetnessLevel' => blank_to_null($brewSweetnessLevel),
			'brewPouring' => blank_to_null($brewPouring)
		);
		$db_conn->where ('id', $id);
		$result = $db_conn->update ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		// Build updade url
		if ((check_special_ingredients($styleBreak,$_SESSION['prefsStyleSet'])) && ($_POST['brewInfo'] == "")) $updateGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&msg=4";
		elseif ($section == "admin") $updateGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
		else $updateGoTo = $base_url."index.php?section=list&msg=2";

		if ($_POST['brewStyle'] == "0-A") {

			$update_table = $prefix."brewing";
			$data = array('brewConfirmed' => '0');
			$db_conn->where ('id', $id);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$updateGoTo = $base_url."index.php?section=brew&action=edit&id=".$id."&filter=".$filter."&view=0-A&msg=4";

		}

		// Check if entry requires special ingredients or a classic style, if so, override the $updateGoTo variable with another and redirect
		if (check_special_ingredients($styleBreak,$_SESSION['prefsStyleSet'])) {

			if (empty($brewInfo)) {
				
				$update_table = $prefix."brewing";
				$data = array('brewConfirmed' => '0');
				$db_conn->where ('id', $id);
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

			if ($section == "admin") {
				if (empty($brewInfo)) $updateGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&view=$styleReturn&msg=1-".$styleReturn;
				else $updateGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
			}

			else {
				if (empty($brewInfo)) $updateGoTo = $base_url."index.php?section=brew&action=edit&id=$id&view=$styleReturn&msg=1-".$styleReturn;
				else $updateGoTo = $base_url."index.php?section=list&msg=2";
			}

		 }

		 // Check if mead/cider entry has carbonation and sweetness, if so, override the $updateGoTo variable with another and redirect
		 if (check_carb($styleBreak,$_SESSION['prefsStyleSet'])) {

			if (empty($brewMead1)) {
				
				$update_table = $prefix."brewing";
				$data = array('brewConfirmed' => '0');
				$db_conn->where ('id', $id);
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

			if ($section == "admin") {
				if (empty($brewMead1)) $updateGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&view=$styleReturn&msg=1-".$styleReturn;
				else $updateGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
			}

			else {
				if (empty($brewMead1)) $updateGoTo = $base_url."index.php?section=brew&action=edit&id=$id&view=$styleReturn&msg=1-".$styleReturn;
				else $updateGoTo = $base_url."index.php?section=list&msg=2";
			}

		 }

		 if (check_sweetness($styleBreak,$_SESSION['prefsStyleSet'])) {

			if (empty($brewMead2)) {
				
				$update_table = $prefix."brewing";
				$data = array('brewConfirmed' => '0');
				$db_conn->where ('id', $id);
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

			if ($section == "admin") {
				if (empty($brewMead2)) $updateGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&view=$styleReturn&msg=1-".$styleReturn;
				else $updateGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
			}

			else {
				if (empty($brewMead2)) $updateGoTo = $base_url."index.php?section=brew&action=edit&id=$id&view=$styleReturn&msg=1-".$styleReturn;
				else $updateGoTo = $base_url."index.php?section=list&msg=2";
			}

		 }

		 if (check_mead_strength($styleBreak,$_SESSION['prefsStyleSet'])) {

			if (empty($brewMead3))  {
				
				$update_table = $prefix."brewing";
				$data = array('brewConfirmed' => '0');
				$db_conn->where ('id', $id);
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

			if ($section == "admin") {
				if (empty($brewMead3)) $updateGoTo = $base_url."index.php?section=brew&go=entries&filter=$filter&action=edit&id=$id&view=$styleReturn&msg=1-".$styleReturn;
				else $updateGoTo = $base_url."index.php?section=admin&go=entries&msg=2";
			}

			else {
				if (empty($brewMead3)) $updateGoTo = $base_url."index.php?section=brew&action=edit&id=$id&view=$styleReturn&msg=1-".$styleReturn;
				else $updateGoTo = $base_url."index.php?section=list&msg=2";
			}
		}

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		if ($errors) {
			if ($section == "admin") $updateGoTo = $base_url."index.php?section=admin&msg=3";
			else $updateGoTo = $base_url."index.php?section=list&msg=3";
		}
		$updateGoTo = prep_redirect_link($updateGoTo);
		$redirect_go_to = sprintf("Location: %s", $updateGoTo);

		/*
		// DEBUG
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
		echo $brewInfo."<br>";
		if (isset($updateSQL)) echo $updateSQL."<br>";
		if (isset($updateSQL1)) echo "Special: ".$updateSQL1."<br>";
		if (isset($updateSQL2)) echo "Carb: ".$updateSQL2."<br>";
		if (isset($updateSQL3)) echo "Sweetness: ".$updateSQL3."<br>";
		if (isset($updateSQL4)) echo "Strength: ".$updateSQL4."<br>";
		exit();
		*/

	} // end if ($action == "edit")

	if ($action == "update") {

		foreach($_POST['id'] as $id) {

			$brewBoxNum = "";
			$brewAdminNotes = "";
			$brewStaffNotes = "";
			$brewPaid = 0;
			$brewReceived = 0;
			$brewJudgingNumber = str_replace("^","-",$_POST['brewJudgingNumber'.$id]);

			if (isset($_POST['brewBoxNum'.$id])) {
				$brewBoxNum = $purifier->purify($_POST['brewBoxNum'.$id]);
				$brewBoxNum = filter_var($brewBoxNum,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			}

			if (isset($_POST['brewAdminNotes'.$id])) {
				$brewAdminNotes = $purifier->purify($_POST['brewAdminNotes'.$id]);
				$brewAdminNotes = filter_var($brewAdminNotes,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			} 
			
			if (isset($_POST['brewStaffNotes'.$id])) {
				$brewStaffNotes = $purifier->purify($_POST['brewStaffNotes'.$id]);
				$brewStaffNotes = filter_var($brewStaffNotes,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			}

			if ((isset($_POST['brewPaid'.$id])) && ($_POST['brewPaid'.$id] == 1)) $brewPaid = 1;
			if ((isset($_POST['brewReceived'.$id])) && ($_POST['brewReceived'.$id] == 1)) $brewReceived = 1;

			$update_table = $prefix."brewing";
			$data = array(
				'brewPaid' => $brewPaid,
				'brewReceived' => $brewReceived,
				'brewBoxNum' => $brewBoxNum,
				'brewJudgingNumber' => strtolower($brewJudgingNumber),
				'brewAdminNotes' => $brewAdminNotes,
				'brewStaffNotes' => $brewStaffNotes
			);			
			$db_conn->where ('id', $id);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

		}

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		if ($errors) $redirect = $base_url."index.php?section=admin&go=entries&msg=3";
		else $redirect = $base_url."index.php?section=admin&go=entries&msg=9";
		$redirect = prep_redirect_link($redirect);
		$redirect_go_to = sprintf("Location: %s", $redirect);

	} // end if ($action == "update")

	if ($action == "paid") {

		$update_table = $prefix."brewing";
		$data = array('brewPaid' => '1');
		$result = $db_conn->update ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		if ($errors) $redirect = $base_url."index.php?section=admin&go=entries&msg=3";
		else $redirect = $base_url."index.php?section=admin&go=entries&msg=20";
		$redirect = prep_redirect_link($redirect);
		$redirect_go_to = sprintf("Location: %s", $redirect);

	}

	if ($action == "unpaid") {

		$update_table = $prefix."brewing";
		$data = array('brewPaid' => '0');
		$result = $db_conn->update ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		if ($errors) $redirect = $base_url."index.php?section=admin&go=entries&msg=3";
		else $redirect = $base_url."index.php?section=admin&go=entries&msg=34";
		$redirect = prep_redirect_link($redirect);
		$redirect_go_to = sprintf("Location: %s", $redirect);

	}

	if ($action == "received") {

		$update_table = $prefix."brewing";
		$data = array('brewReceived' => '1');
		$result = $db_conn->update ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		if ($errors) $redirect = $base_url."index.php?section=admin&go=entries&msg=3";
		else $redirect = $base_url."index.php?section=admin&go=entries&msg=21";
		$redirect = prep_redirect_link($redirect);
		$redirect_go_to = sprintf("Location: %s", $redirect);

	}

	if ($action == "not-received") {

		$update_table = $prefix."brewing";
		$data = array('brewReceived' => '0');
		$result = $db_conn->update ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		if ($errors) $redirect = $base_url."index.php?section=admin&go=entries&msg=3";
		else $redirect = $base_url."index.php?section=admin&go=entries&msg=35";
		$redirect = prep_redirect_link($redirect);
		$redirect_go_to = sprintf("Location: %s", $redirect);

	}

	if ($action == "confirmed") {

		$update_table = $prefix."brewing";
		$data = array('brewConfirmed' => '1');
		$result = $db_conn->update ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		if ($errors) $redirect = $base_url."index.php?section=admin&go=entries&msg=3";
		else $redirect = $base_url."index.php?section=admin&go=entries&msg=22";
		$redirect = prep_redirect_link($redirect);
		$redirect_go_to = sprintf("Location: %s", $redirect);

	}

} else {

	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);

}
?>