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

	function table_limit($style_id,$planning) {

		// If in planning mode, query to see if the style's id is in a  
		// table's defined styles and whether that table is limiting entries.
		if ($planning == 1) {

			require (CONFIG.'config.php');

			// Define Vars
			$db_conn = new MysqliDb($connection);
			$table_id = "";
			$table_limit = "";
			$table_style_ids = "";
			$result = 0;

			$query_table_entry_limits = sprintf("SELECT id,tableStyles,tableEntryLimit FROM `%s` WHERE tableEntryLimit IS NOT NULL",$prefix."judging_tables");
			$table_entry_limits = mysqli_query($connection,$query_table_entry_limits) or die (mysqli_error($connection));
			$row_table_entry_limits = mysqli_fetch_assoc($table_entry_limits);

			// Loop through each table's defined styles and look for
			// the chosen style's ID as defined in the function params.
			// If found, define the necessary vars.
			do {

				$exploder = explode(",",$row_table_entry_limits['tableStyles']);
				if (in_array($style_id, $exploder)) {
					$table_id = $row_table_entry_limits['id'];
					$table_limit = $row_table_entry_limits['tableEntryLimit'];
					$table_style_ids = $row_table_entry_limits['tableStyles'];
				}

			} while($row_table_entry_limits = mysqli_fetch_assoc($table_entry_limits));

			// If found and vars are not empty, get the total entries logged
			// for all styles defined in the table, if the total is equal to
			// the limit, mark all associated styles as "at limit."
			if ((!empty($table_id)) && (!empty($table_limit)) && (!empty($table_style_ids))) {

				// Call established function to get total entry count of the 
				// table's defined styles.
				$total_table_entries = get_table_info("1","count_total",$table_id,"default","default");

				// If the total entries for that table are at or beyond limit,
				// designate each style at the table as "at limit" (true) in the styles
				// DB table.
				if ($total_table_entries >= $table_limit) {

					$exploder = explode(",",$table_style_ids);

					foreach (array_unique($exploder) as $value) {

						$update_table = $prefix."styles";
						$data = array(
							'brewStyleAtLimit' => 1
						);
						$db_conn->where ('id', $value);
						$result = $db_conn->update ($update_table, $data);
						if ($result) $return += 1;

					} // end foreach
				
				} // end if ($row_table_entry_limits['tableEntryLimit'] >= $total_table_entries)

				// If the total entries for that table is BELOW the limit,
				// designate each style at the table as "available" (false) 
				// in the styles DB table.
				if ($total_table_entries < $table_limit) {

					$exploder = explode(",",$table_style_ids);

					foreach (array_unique($exploder) as $value) {

						$update_table = $prefix."styles";
						$data = array(
							'brewStyleAtLimit' => 0
						);
						$db_conn->where ('id', $value);
						$result = $db_conn->update ($update_table, $data);
						if ($result) $return += 1;

					} // end foreach
				
				} // end if ($row_table_entry_limits['tableEntryLimit'] < $total_table_entries)

			} // end if ((!empty($table_id)) && (!empty($table_limit)) && (!empty($table_style_ids)))

			if ($return > 0) return TRUE;
			else return FALSE;
			
		} // end if ($planning == 1)

		else return FALSE;
	
	} // end function

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
			$subcat_limit = limit_subcategory(sterilize($_POST['brewStyle']),$_SESSION['prefsUserSubCatLimit'],$_SESSION['prefsUSCLExLimit'],$_SESSION['prefsUSCLEx'],sterilize($_POST['brewBrewerID']));
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

	// Do not rely on session var to determine if in tables planning mode
	$query_table_planning = sprintf("SELECT jPrefsTablePlanning FROM `%s` WHERE id='1'",$prefix."judging_preferences");
	$table_planning = mysqli_query($connection,$query_table_planning);
	$row_table_planning = mysqli_fetch_assoc($table_planning);

	if (($action == "add") || ($action == "edit")) {

		if ($_SESSION['prefsStyleSet'] == "BA") include (INCLUDES.'ba_constants.inc.php');
		include (DB.'styles_special.db.php');

		// Set up vars
		$brewComments = "";
		$brewCoBrewer = "";
		$styleBreak = $purifier->purify(sterilize($_POST['brewStyle']));
		$styleName = "";
		$brewName = $purifier->purify(sterilize($_POST['brewName']));
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
		$brewPackaging = "";
		$brewStyleType = "";
		$brewOriginalGravity = "";
		$brewFinalGravity = "";

		// Comments
		if ((isset($_POST['brewComments'])) && (!empty($_POST['brewComments']))) {
			$brewComments = $purifier->purify(sterilize($_POST['brewComments']));
		}

		// Co Brewer
		if ((isset($_POST['brewCoBrewer'])) && (!empty($_POST['brewCoBrewer']))) {

			$brewCoBrewer = $purifier->purify(sterilize($_POST['brewCoBrewer']));

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

		}
		
		// Possible Allergens
		if ((isset($_POST['brewPossAllergens'])) && (!empty($_POST['brewPossAllergens']))) {
			$brewPossAllergens = $purifier->purify(sterilize($_POST['brewPossAllergens']));
		} 

		if ($_SESSION['userLevel'] <= 1) {

			// Admin and Staff Notes
			if ((isset($_POST['brewAdminNotes'])) && (!empty($_POST['brewAdminNotes']))) {
				$brewAdminNotes = $purifier->purify(sterilize($_POST['brewAdminNotes']));
			}

			if ((isset($_POST['brewStaffNotes'])) && (!empty($_POST['brewStaffNotes']))) {
				$brewStaffNotes = $purifier->purify(sterilize($_POST['brewStaffNotes']));
			}

			if ((isset($_POST['brewBoxNum'])) && (!empty($_POST['brewBoxNum']))) {
				$brewBoxNum = $purifier->purify(sterilize($_POST['brewBoxNum']));
			}

		}

		// ABV
		if ((isset($_POST['brewABV'])) && (!empty($_POST['brewABV']))) {
			$brewABV = $purifier->purify(sterilize($_POST['brewABV']));
		}

		if ((isset($_POST['brewOriginalGravity'])) && (!empty($_POST['brewOriginalGravity']))) {
			$brewOriginalGravity = $purifier->purify(sterilize($_POST['brewOriginalGravity']));
			$brewOriginalGravity = number_format($brewOriginalGravity,3);
		}

		if ((isset($_POST['brewFinalGravity'])) && (!empty($_POST['brewFinalGravity']))) {
			$brewFinalGravity = $purifier->purify(sterilize($_POST['brewFinalGravity']));
			$brewFinalGravity = number_format($brewFinalGravity,3);
		}

		if ((!empty($brewOriginalGravity)) || (!empty($brewFinalGravity))) {
			$brewSweetnessLevel = array();
			$brewSweetnessLevel['OG'] = $brewOriginalGravity;
			$brewSweetnessLevel['FG'] = $brewFinalGravity;
			$brewSweetnessLevel = json_encode($brewSweetnessLevel);
		}

		// Sweetness Level (Specific Gravity - POST var is only for NW Cider Cup styles)
		if ((isset($_POST['brewSweetnessLevel'])) && (!empty($_POST['brewSweetnessLevel']))) {
			$brewSweetnessLevel = $purifier->purify(sterilize($_POST['brewSweetnessLevel']));
			$brewSweetnessLevel = number_format($brewSweetnessLevel,3);
		}

		// Juice Source - From multi-select
		if ((isset($_POST['brewJuiceSource'])) && (!empty($_POST['brewJuiceSource']))) {
		    $juice_src = array("juice_src" => $_POST['brewJuiceSource']);
		}

		else $juice_src = array();

		if ((isset($_POST['brewJuiceSourceOther'])) && (!empty($_POST['brewJuiceSourceOther']))) {
		    $juice_src_other = array("juice_src_other" => $_POST['brewJuiceSourceOther']);
		}

		else $juice_src_other = array();

		if ((empty($juice_src)) && (empty($juice_src_other))) {
		    $brewJuiceSource = NULL;
		}

		else {
		    $brewJuiceSource = array();
		    $brewJuiceSource = array_merge($juice_src,$juice_src_other);
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
			$brewPouringNotes = $purifier->purify(sterilize($_POST['brewPouringNotes']));
			$pouring_instructions['pouring_notes'] = $brewPouringNotes;
		}

		$brewPouring = json_encode($pouring_instructions);

		if (isset($_POST['brewPackaging'])) $brewPackaging = sterilize($_POST['brewPackaging']);

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

		// Array from constants.inc.php
		// Check to see if there are any style limits
		// If so, check if the 
		if ((is_array($style_limit_entry_count_display)) && (!empty($style_limit_entry_count_display))) {

			if ($_SESSION['sprefsStyleSet'] == "BJCP2025") {
				$first_character = mb_substr($key, 0, 1);
				if ($first_character == "C") $chosen_style_set = "BJCP2025";
				else $chosen_style_set = "BJCP2021";
			}

			else $chosen_style_set = $_SESSION['sprefsStyleSet'];

			$all_style_limits = json_decode($_SESSION['prefsStyleLimits'],true);
			
			if ((isset($all_style_limits[$style[0]])) && ($all_style_limits[$style[0]] >= $style_limit_entry_count_display[$style[0]])) {

				$update_table_styles = $prefix."styles";
				$data = array('brewStyleAtLimit' => 1);
				$db_conn->where ('brewStyleGroup', $style[0]);
				$db_conn->where ('brewStyleVersion', $chosen_style_set);
				$result = $db_conn->update ($update_table_styles, $data);
				
			}

			if ((isset($all_style_limits[$style[0]])) && ($all_style_limits[$style[0]] < $style_limit_entry_count_display[$style[0]])) {

				$update_table_styles = $prefix."styles";
				$data = array('brewStyleAtLimit' => 0);
				$db_conn->where ('brewStyleGroup', $style[0]);
				$db_conn->where ('brewStyleVersion', $chosen_style_set);
				$result = $db_conn->update ($update_table_styles, $data);

			}

		}

		// Style Name

		// Determine if the style chosen is a cider - if so, run a different query
		if ($_SESSION['prefsStyleSet'] == "BJCP2025") {
			$first_character = mb_substr($styleFix, 0, 1);
			if ($first_character == "C") $query_style_name = sprintf("SELECT id, brewStyleGroup, brewStyleNum, brewStyle, brewStyleCarb, brewStyleSweet, brewStyleStrength, brewStyleType FROM %s WHERE (brewStyleVersion='BJCP2025' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s'", $prefix."styles", $styleFix, $style[1]);
			else $query_style_name = sprintf("SELECT id, brewStyleGroup, brewStyleNum, brewStyle, brewStyleCarb, brewStyleSweet, brewStyleStrength, brewStyleType FROM %s WHERE (brewStyleVersion='BJCP2021' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s'", $prefix."styles", $styleFix, $style[1]);
		}

		else $query_style_name = sprintf("SELECT id, brewStyleGroup, brewStyleNum, brewStyle, brewStyleCarb, brewStyleSweet, brewStyleStrength, brewStyleType FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s'", $prefix."styles", $_SESSION['prefsStyleSet'], $styleFix, $style[1]);
		$style_name = mysqli_query($connection,$query_style_name) or die (mysqli_error($connection));
		$row_style_name = mysqli_fetch_assoc($style_name);
		
		$styleName = $row_style_name['brewStyle'];

		// Mark as paid if free entry fee
		if ($_SESSION['contestEntryFee'] == 0) $brewPaid = 1;

		if (!empty($_POST['brewInfo'])) {
			$brewInfo = $purifier->purify(sterilize($_POST['brewInfo']));
		}

		// Specialized/Optional info
		if ((!empty($_POST['brewInfoOptional'])) && (in_array($styleBreak,$optional_info_styles))) {
			$brewInfoOptional = $purifier->purify(sterilize($_POST['brewInfoOptional']));		
		}

		// For BJCP 2025/2021, process addtional info
		if (($_SESSION['prefsStyleSet'] == "BJCP2025") || ($_SESSION['prefsStyleSet'] == "BJCP2021")) {

			// If BJCP 2021/5 and 2A, add optional regional variation if present
			if (($index == "02-A") && (!empty($_POST['regionalVar']))) {
				$brewInfo = $purifier->purify(sterilize($_POST['regionalVar']));
			}

			// IPA strength for 21B styles
			if (strlen(strstr($index,"21-B")) > 0) {
				if ($index == "21-B") $brewInfo .= "^".sterilize($_POST['strengthIPA']);
				else $brewInfo .= sterilize($_POST['strengthIPA']);
			}

			// Pale or Dark Variant
			if (($index == "09-A") || ($index == "10-C") || ($index == "07-C"))  $brewInfo = sterilize($_POST['darkLightColor']);

			// Fruit Lambic carb/sweetness
			if ($index == "23-F") $brewInfo .= "^".sterilize($_POST['sweetnessLambic'])."^".sterilize($_POST['carbLambic']);

			// Biere de Garde color
			if ($index == "24-C") $brewInfo = sterilize($_POST['BDGColor']);

			// Saison strength/color
			if ($index == "25-B") $brewInfo = sterilize($_POST['strengthSaison'])."^".sterilize($_POST['darkLightColor']);

		}

		if ($style[0] > 34) $styleID = $styleID; else $styleID = $style[1];

		if ($row_style_name) {

			if ((isset($_POST['brewMead1'])) && ($row_style_name['brewStyleCarb'] == 1)) $brewMead1 = sterilize($_POST['brewMead1']); // Carbonation

			if ((isset($_POST['brewMead2-cider'])) && ($row_style_name['brewStyleSweet'] == 1) && ($row_style_name['brewStyleType'] == 2)) $brewMead2 = sterilize($_POST['brewMead2-cider']); // Cider Sweetness

			if ((isset($_POST['brewMead2-mead'])) && ($row_style_name['brewStyleSweet'] == 1) && ($row_style_name['brewStyleType'] == 3)) $brewMead2 = sterilize($_POST['brewMead2-mead']); // Mead Sweetness
			
			if ((isset($_POST['brewMead3'])) && ($row_style_name['brewStyleStrength'] == 1)) $brewMead3 = sterilize($_POST['brewMead3']); // Strength

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

			$brewBrewerID = sterilize($_POST['brewBrewerID']);
			$brewBrewerLastName = sterilize($_POST['brewBrewerLastName']);
			$brewBrewerFirstName = sterilize($_POST['brewBrewerFirstName']);

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
			'brewUpdated' => date('Y-m-d H:i:s', time()),
			'brewConfirmed' => blank_to_null(sterilize($_POST['brewConfirmed'])),
			'brewBoxNum' => blank_to_null($brewBoxNum),
			'brewABV' => blank_to_null($brewABV),
			'brewJuiceSource' => blank_to_null($brewJuiceSource),
			'brewSweetnessLevel' => blank_to_null($brewSweetnessLevel),
			'brewPouring' => blank_to_null($brewPouring),
			'brewStyleType' => blank_to_null($row_style_name['brewStyleType']),
			'brewPackaging' => blank_to_null(sterilize($brewPackaging))
		);

		$result = $db_conn->insert ($update_table, $data);

		// If inserted, use the table_limit function to check
		// if there's an entry limit imposed on the table.
		if ($result) {
			if ($row_table_planning['jPrefsTablePlanning'] == 1) table_limit($row_style_name['id'],1);
		}

		else {
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

			if ($_POST['brewStyle'] == "0-A") $insertGoTo = $base_url."index.php?section=admin&go=entries&action=edit&filter=".$brewBrewerID."&id=".$id."&view=0-A&msg=4";
			else {
				if ((isset($_POST['return-to-add'])) && ($_POST['return-to-add'] == 1)) $insertGoTo = $base_url."index.php?section=admin&go=entries&action=add&filter=admin&msg=1";
				else $insertGoTo = $base_url."index.php?section=admin&go=entries&msg=1";
			}

		}

		elseif (($section != "admin") && ($_POST['brewStyle'] == "0-A")) {
			$insertGoTo = $base_url."index.php?section=brew&action=edit&id=".$id."&view=0-A&msg=4";
		}

		else {
			if ((isset($_POST['return-to-add'])) && ($_POST['return-to-add'] == 1)) $insertGoTo = $base_url."index.php?section=brew&go=entries&action=add&msg=1";
			else $insertGoTo = $base_url."index.php?section=list&msg=1";
		}

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

		// Before processing the edit, determine the style of the entry
		// as stored in the DB
		$query_current_style = sprintf("SELECT brewCategorySort, brewSubCategory FROM %s WHERE id = '%s'", $prefix."brewing",$id);
		$current_style = mysqli_query($connection,$query_current_style) or die (mysqli_error($connection));
		$row_current_style = mysqli_fetch_assoc($current_style);

		// Determine if the style chosen is a cider - if so, run a different query
		if ($_SESSION['prefsStyleSet'] == "BJCP2025") {
			$first_character = mb_substr($row_current_style['brewCategorySort'], 0, 1);
			if ($first_character == "C") $query_current_style_id = sprintf("SELECT id FROM %s WHERE (brewStyleVersion='BJCP2025' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s'", $prefix."styles", $row_current_style['brewCategorySort'], $row_current_style['brewSubCategory']);
			else $query_current_style_id = sprintf("SELECT id FROM %s WHERE (brewStyleVersion='BJCP2021' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s'", $prefix."styles", $row_current_style['brewCategorySort'], $row_current_style['brewSubCategory']);
		}

		else $query_current_style_id = sprintf("SELECT id FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s'", $prefix."styles", $_SESSION['prefsStyleSet'], $row_current_style['brewCategorySort'], $row_current_style['brewSubCategory']);
		$current_style_id = mysqli_query($connection,$query_current_style_id) or die (mysqli_error($connection));
		$row_current_style_id = mysqli_fetch_assoc($current_style_id);

		if ($row_user['userLevel'] <= 1) {

			$query_brewer = sprintf("SELECT * FROM $brewer_db_table WHERE uid = '%s'", sterilize($_POST['brewBrewerID']));
			$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
			$row_brewer = mysqli_fetch_assoc($brewer);
			
			$brewBrewerID = $row_brewer['uid'];
			$brewBrewerLastName = $row_brewer['brewerLastName'];
			$brewBrewerFirstName = $row_brewer['brewerFirstName'];

		}

		else {

			$brewBrewerID = sterilize($_POST['brewBrewerID']);
			$brewBrewerLastName = sterilize($_POST['brewBrewerLastName']);
			$brewBrewerFirstName = sterilize($_POST['brewBrewerFirstName']);

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
			'brewUpdated' => date('Y-m-d H:i:s', time()),
			'brewConfirmed' => sterilize($_POST['brewConfirmed']),
			'brewBoxNum' => $brewBoxNum,
			'brewABV' => blank_to_null($brewABV),
			'brewJuiceSource' => blank_to_null($brewJuiceSource),
			'brewSweetnessLevel' => blank_to_null($brewSweetnessLevel),
			'brewPouring' => blank_to_null($brewPouring),
			'brewStyleType' => blank_to_null($row_style_name['brewStyleType']),
			'brewPackaging' => blank_to_null(sterilize($_POST['brewPackaging']))
		);
		
		$db_conn->where ('id', $id);
		$result = $db_conn->update ($update_table, $data);
		
		// When updated, use the table_limit function to check
		// if there's an entry limit imposed on the table for the
		// previous style and the current style. If so, adjust
		// each accordingly.
		if ($result) {
			if ($row_table_planning['jPrefsTablePlanning'] == 1) {
				table_limit($row_current_style_id['id'],1);
				table_limit($row_style_name['id'],1);
			}
		}

		else {
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
				$brewBoxNum = sterilize($brewBoxNum);
			}

			if (isset($_POST['brewAdminNotes'.$id])) {
				$brewAdminNotes = $purifier->purify($_POST['brewAdminNotes'.$id]);
				$brewAdminNotes = sterilize($brewAdminNotes);
			} 
			
			if (isset($_POST['brewStaffNotes'.$id])) {
				$brewStaffNotes = $purifier->purify($_POST['brewStaffNotes'.$id]);
				$brewStaffNotes = sterilize($brewStaffNotes);
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