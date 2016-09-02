<?php
// General vars
$today = strtotime("now");
$url = parse_url($_SERVER['PHP_SELF']);

mysqli_select_db($connection,$database);

$query_version1 = sprintf("SELECT * FROM %s WHERE id='1'", $prefix."system");
$version1 = mysqli_query($connection,$query_version1) or die (mysqli_error($connection));
$row_version1 = mysqli_fetch_assoc($version1);
$version = $row_version1['version'];

if (((!empty($_SESSION['session_set_'.$prefix_session])) && ($_SESSION['session_set_'.$prefix_session] != $prefix_session)) || (empty($_SESSION['session_set_'.$prefix_session]))) {

	session_unset();
	session_destroy();
	session_write_close();
	session_regenerate_id(true);
	session_name($prefix_session);
	session_start();
	$_SESSION['session_set_'.$prefix_session] = $prefix_session;

}

if (($section != "update") && (empty($_SESSION['dataCheck'.$prefix_session]))) {
	if (strstr($url['path'],"index.php")) { 
		
		// only for version 1.2.1.0; REMOVE for subsequent version
		$data_check_date = strtotime($row_version1['data_check']); 
		$_SESSION['dataCheck'.$prefix_session] = $data_check_date;
	}
}


// Get the general info for the competition from the DB and store in session variables
if (empty($_SESSION['contest_info_general'.$prefix_session])) {
	
	$query_contest_info = sprintf("SELECT * FROM %s", $prefix."contest_info");
	
	// if SSO, get information from user choice on login screen
	if (SINGLE) $query_contest_info .= sprintf(" WHERE id='%s'", $_POST['comp_id']);
	
	// if not, use 1
	else $query_contest_info .= " WHERE id='1'";
	$contest_info = mysqli_query($connection,$query_contest_info) or die (mysqli_error($connection));
	$row_contest_info = mysqli_fetch_assoc($contest_info); 
	
	$_SESSION['comp_id'] = $row_contest_info['id'];

	// Comp Name
	$_SESSION['contestName'] = $row_contest_info['contestName'];
	$_SESSION['contestLogo'] = $row_contest_info['contestLogo'];
	$_SESSION['contestID'] = $row_contest_info['contestID'];
	 
	// Comp Host Info
	$_SESSION['contestHost'] = $row_contest_info['contestHost'];
	$_SESSION['contestHostWebsite'] = $row_contest_info['contestHostWebsite'];
	$_SESSION['contestShippingName'] = $row_contest_info['contestShippingName'];
	$_SESSION['contestShippingAddress'] = $row_contest_info['contestShippingAddress'];
	$_SESSION['contestHostLocation'] = $row_contest_info['contestHostLocation'];
	
	// Awards Information
	$_SESSION['contestAwardsLocation'] = $row_contest_info['contestAwardsLocation'];
	$_SESSION['contestAwardsLocName'] = $row_contest_info['contestAwardsLocName'];
	$_SESSION['contestAwardsLocTime'] = $row_contest_info['contestAwardsLocTime'];
	
	// Entry Fees
	$_SESSION['contestEntryFee'] = $row_contest_info['contestEntryFee'];
	$_SESSION['contestEntryFee2'] = $row_contest_info['contestEntryFee2'];
	$_SESSION['contestEntryFeePasswordNum'] = $row_contest_info['contestEntryFeePasswordNum'];
	$_SESSION['contestEntryCap'] = $row_contest_info['contestEntryCap'];
	$_SESSION['contestEntryFeeDiscount'] = $row_contest_info['contestEntryFeeDiscount'];
	$_SESSION['contestEntryFeeDiscountNum'] = $row_contest_info['contestEntryFeeDiscountNum'];
		
	$_SESSION['contest_info_general'.$prefix_session] = $prefix_session;

}

// Get the general info for the competition from the DB and store in cookies
if (empty($_SESSION['prefs'.$prefix_session])) {	

	if (SINGLE) $query_prefs = sprintf("SELECT * FROM %s WHERE id='%s'", $prefix."preferences",$_SESSION['comp_id']);
	else $query_prefs = sprintf("SELECT * FROM %s WHERE id='1'", $prefix."preferences");
	$prefs = mysqli_query($connection,$query_prefs) or die (mysqli_error($connection));
	$row_prefs = mysqli_fetch_assoc($prefs);
	$totalRows_prefs = mysqli_num_rows($prefs);

	$_SESSION['prefsTemp'] = $row_prefs['prefsTemp'];
	$_SESSION['prefsWeight1'] = $row_prefs['prefsWeight1'];
	$_SESSION['prefsWeight2'] = $row_prefs['prefsWeight2'];
	$_SESSION['prefsLiquid1'] = $row_prefs['prefsLiquid1'];
	$_SESSION['prefsLiquid2'] = $row_prefs['prefsLiquid2'];
	$_SESSION['prefsPaypal'] = $row_prefs['prefsPaypal'];
	$_SESSION['prefsPaypalAccount'] = $row_prefs['prefsPaypalAccount'];
	$_SESSION['prefsCurrency'] = $row_prefs['prefsCurrency'];
	$_SESSION['prefsCash'] = $row_prefs['prefsCash'];
	$_SESSION['prefsCheck'] = $row_prefs['prefsCheck'];
	$_SESSION['prefsCheckPayee'] = $row_prefs['prefsCheckPayee'];
	$_SESSION['prefsTransFee'] = $row_prefs['prefsTransFee'];
	$_SESSION['prefsSponsors'] = $row_prefs['prefsSponsors'];
	$_SESSION['prefsSponsorLogos'] = $row_prefs['prefsSponsorLogos'];
	$_SESSION['prefsSponsorLogoSize'] = $row_prefs['prefsSponsorLogoSize'];
	$_SESSION['prefsCompLogoSize'] = $row_prefs['prefsCompLogoSize'];
	$_SESSION['prefsDisplayWinners'] = $row_prefs['prefsDisplayWinners'];
	$_SESSION['prefsWinnerDelay'] = $row_prefs['prefsWinnerDelay'];
	$_SESSION['prefsWinnerMethod'] = $row_prefs['prefsWinnerMethod'];
	$_SESSION['prefsShowBestBrewer'] = $row_prefs['prefsShowBestBrewer'];
	$_SESSION['prefsBestBrewerTitle'] = $row_prefs['prefsBestBrewerTitle'];
	$_SESSION['prefsFirstPlacePts'] = $row_prefs['prefsFirstPlacePts'];
	$_SESSION['prefsSecondPlacePts'] = $row_prefs['prefsSecondPlacePts'];
	$_SESSION['prefsThirdPlacePts'] = $row_prefs['prefsThirdPlacePts'];
	$_SESSION['prefsFourthPlacePts'] = $row_prefs['prefsFourthPlacePts'];
	$_SESSION['prefsHMPts'] = $row_prefs['prefsHMPts'];
	$_SESSION['prefsTieBreakRule1'] = $row_prefs['prefsTieBreakRule1'];
	$_SESSION['prefsTieBreakRule2'] = $row_prefs['prefsTieBreakRule2'];
	$_SESSION['prefsTieBreakRule3'] = $row_prefs['prefsTieBreakRule3'];
	$_SESSION['prefsTieBreakRule4'] = $row_prefs['prefsTieBreakRule4'];
	$_SESSION['prefsTieBreakRule5'] = $row_prefs['prefsTieBreakRule5'];
	$_SESSION['prefsTieBreakRule6'] = $row_prefs['prefsTieBreakRule6'];
	$_SESSION['prefsDisplaySpecial'] = $row_prefs['prefsDisplaySpecial'];
	$_SESSION['prefsEntryForm'] = $row_prefs['prefsEntryForm'];
	$_SESSION['prefsRecordLimit'] = $row_prefs['prefsRecordLimit'];
	$_SESSION['prefsRecordPaging'] = $row_prefs['prefsRecordPaging'];
	$_SESSION['prefsTheme'] = $row_prefs['prefsTheme'];
	$_SESSION['prefsDateFormat'] = $row_prefs['prefsDateFormat'];
	$_SESSION['prefsContact'] = $row_prefs['prefsContact'];
	$_SESSION['prefsTimeZone'] = $row_prefs['prefsTimeZone'];
	$_SESSION['prefsTimeFormat'] = $row_prefs['prefsTimeFormat'];
	$_SESSION['prefsPayToPrint'] = $row_prefs['prefsPayToPrint'];
	$_SESSION['prefsHideRecipe'] = $row_prefs['prefsHideRecipe'];
	$_SESSION['prefsUseMods'] = $row_prefs['prefsUseMods'];
	$_SESSION['prefsSEF'] = $row_prefs['prefsSEF'];
	$_SESSION['prefsSpecialCharLimit'] = $row_prefs['prefsSpecialCharLimit'];
	$_SESSION['prefsStyleSet'] = $row_prefs['prefsStyleSet'];
	$_SESSION['prefsAutoPurge'] = $row_prefs['prefsAutoPurge'];
	$_SESSION['prefsEntryLimitPaid'] = $row_prefs['prefsEntryLimitPaid'];
	$_SESSION['prefsEmailRegConfirm'] = $row_prefs['prefsEmailRegConfirm'];
	$_SESSION['prefsSpecific'] = $row_prefs['prefsSpecific'];

	if (SINGLE) $query_judging_prefs = sprintf("SELECT * FROM %s WHERE id='%s'", $prefix."judging_preferences",$_SESSION['comp_id']);
	else $query_judging_prefs = sprintf("SELECT * FROM %s WHERE id='1'", $prefix."judging_preferences");
	$judging_prefs = mysqli_query($connection,$query_judging_prefs) or die (mysqli_error($connection));
	$row_judging_prefs = mysqli_fetch_assoc($judging_prefs);	
	
	$_SESSION['jPrefsQueued'] = $row_judging_prefs['jPrefsQueued'];
	$_SESSION['jPrefsFlightEntries'] = $row_judging_prefs['jPrefsFlightEntries'];
	$_SESSION['jPrefsMaxBOS'] = $row_judging_prefs['jPrefsMaxBOS'];
	$_SESSION['jPrefsRounds'] = $row_judging_prefs['jPrefsRounds'];
	$_SESSION['jPrefsBottleNum'] = $row_judging_prefs['jPrefsBottleNum'];
	$_SESSION['jPrefsCapStewards'] = $row_judging_prefs['jPrefsCapStewards'];
	$_SESSION['jPrefsCapJudges'] = $row_judging_prefs['jPrefsCapJudges'];
	
	// Get counts for common, mostly static items
	$query_sponsor_count = sprintf("SELECT COUNT(*) as 'count' FROM %s", $prefix."sponsors");
	if (SINGLE) $query_sponsor_count .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
	$result_sponsor_count = mysqli_query($connection,$query_sponsor_count) or die (mysqli_error($connection));
	$row_sponsor_count = mysqli_fetch_assoc($result_sponsor_count);
	$_SESSION['sponsorCount'] = $row_sponsor_count['count'];
	
	$_SESSION['prefs'.$prefix_session] = "1";
	$_SESSION['prefix'] = $prefix;
}


// Set global pagination variables 
$display = $_SESSION['prefsRecordPaging']; 
$pg = (isset($_REQUEST['pg']) && ctype_digit($_REQUEST['pg'])) ?  $_REQUEST['pg'] : 1;
$start = $display * $pg - $display;
if ($start == 0) $start_display = "1"; else $start_display = $start;

// Session specific queries
if ((isset($_SESSION['loginUsername'])) && (empty($_SESSION['user_info'.$prefix_session])))  {
	
	$query_user = sprintf("SELECT * FROM %s WHERE user_name = '%s'", $prefix."users", $_SESSION['loginUsername']);
	$user = mysqli_query($connection,$query_user) or die (mysqli_error($connection));
	$row_user = mysqli_fetch_assoc($user);
	$totalRows_user = mysqli_num_rows($user);
	
	$_SESSION['user_id'] = $row_user['id'];
	$_SESSION['userCreated'] = $row_user['userCreated'];
	$_SESSION['user_name'] = $row_user['user_name'];
	$_SESSION['userLevel'] = $row_user['userLevel'];
	$_SESSION['userQuestion'] = $row_user['userQuestion'];
	$_SESSION['userQuestionAnswer'] = $row_user['userQuestionAnswer'];
	$_SESSION['userCreated'] = $row_user['userCreated'];

	$query_name = sprintf("SELECT * FROM %s WHERE uid='%s'", $prefix."brewer", $_SESSION['user_id']);
	$name = mysqli_query($connection,$query_name) or die (mysqli_error($connection));
	$row_name = mysqli_fetch_assoc($name);
	
	$_SESSION['brewerID']  = $row_name['id'];
	$_SESSION['brewerFirstName'] = $row_name['brewerFirstName'];
	$_SESSION['brewerLastName'] = $row_name['brewerLastName'];
	$_SESSION['brewerAddress'] = $row_name['brewerAddress'];
	$_SESSION['brewerCity'] = $row_name['brewerCity'];
	$_SESSION['brewerState'] = $row_name['brewerState'];
	$_SESSION['brewerZip'] = $row_name['brewerZip'];
	$_SESSION['brewerCountry'] = $row_name['brewerCountry'];
	$_SESSION['brewerEmail'] = $row_name['brewerEmail'];
	$_SESSION['brewerPhone1'] = $row_name['brewerPhone1'];
	$_SESSION['brewerPhone2'] = $row_name['brewerPhone2'];
	$_SESSION['brewerClubs'] = $row_name['brewerClubs'];
	$_SESSION['brewerNickname'] = $row_name['brewerNickname'];
	$_SESSION['brewerSteward'] = $row_name['brewerSteward'];
	$_SESSION['brewerJudge'] = $row_name['brewerJudge'];
	$_SESSION['brewerJudgeID'] = $row_name['brewerJudgeID'];
	$_SESSION['brewerJudgeRank'] = $row_name['brewerJudgeRank'];
	$_SESSION['brewerJudgeLikes'] = $row_name['brewerJudgeLikes'];
	$_SESSION['brewerJudgeDislikes'] = $row_name['brewerJudgeDislikes'];
	$_SESSION['brewerJudgeLocation'] = $row_name['brewerJudgeLocation'];
	$_SESSION['brewerJudgeMead'] = $row_name['brewerJudgeMead'];
	$_SESSION['brewerStewardLocation'] = $row_name['brewerStewardLocation'];
	$_SESSION['brewerAssignment'] = $row_name['brewerAssignment'];
	$_SESSION['brewerDiscount'] = $row_name['brewerDiscount'];
	$_SESSION['brewerDropOff'] = $row_name['brewerDropOff'];
	$_SESSION['brewerAHA'] = $row_name['brewerAHA'];
	
	$_SESSION['user_info'.$prefix_session] = "1";
	
	if (($go == "make_admin") || (($go == "participants") && ($action == "add"))) {
		$query_user_level = sprintf("SELECT * FROM %s WHERE user_name = '%s'", $prefix."users", $username);
		$user_level = mysqli_query($connection,$query_user_level) or die (mysqli_error($connection));
		$row_user_level = mysqli_fetch_assoc($user_level);
		$totalRows_user_level = mysqli_num_rows($user_level);
		}
	elseif (($section == "brewer") && ($action == "edit")) { 
		$query_user_level = sprintf("SELECT * FROM %s WHERE user_name = '%s'", $prefix."users",$row_name['brewerEmail']);
		$user_level = mysqli_query($connection,$query_user_level) or die (mysqli_error($connection));
		$row_user_level = mysqli_fetch_assoc($user_level);
		$totalRows_user_level = mysqli_num_rows($user_level);
		}	
	
}

session_write_close();

// Some limits and dates may need to be changed by admin and propagated instantly to all users
// These will be called on every page load instead of being stored in a session variable
$query_limits = sprintf("SELECT prefsEntryLimit,prefsUserEntryLimit,prefsSpecialCharLimit,prefsUserSubCatLimit,prefsUSCLEx,prefsUSCLExLimit,prefsEntryLimitPaid FROM %s WHERE id='1'", $prefix."preferences");
$limits = mysqli_query($connection,$query_limits) or die (mysqli_error($connection));
$row_limits = mysqli_fetch_assoc($limits);

$query_judge_limits = sprintf("SELECT jprefsCapJudges,jprefsCapStewards FROM %s WHERE id='1'", $prefix."judging_preferences");
$judge_limits = mysqli_query($connection,$query_judge_limits) or die (mysqli_error($connection));
$row_judge_limits = mysqli_fetch_assoc($judge_limits);

$query_contest_dates = sprintf("SELECT contestCheckInPassword,contestRegistrationOpen,contestRegistrationDeadline,contestJudgeOpen,contestJudgeDeadline,contestEntryOpen,contestEntryDeadline,contestShippingOpen,contestShippingDeadline,contestDropoffOpen,contestDropoffDeadline FROM %s WHERE id=1", $prefix."contest_info");
$contest_dates = mysqli_query($connection,$query_contest_dates) or die (mysqli_error($connection));
$row_contest_dates = mysqli_fetch_assoc($contest_dates);

// Only used for initial setup of installation
if ($section == "step4") {
	$query_name = "SELECT brewerFirstName,brewerLastName,brewerEmail FROM $brewer_db_table WHERE uid='1'";
	$name = mysqli_query($connection,$query_name) or die (mysqli_error($connection));
	$row_name = mysqli_fetch_assoc($name);
}

// Do not rely on session data to populate Competition Info for editing in Admin or in Setup
if (($section == "admin") && ($go == "contest_info")) {
	$query_contest_info = sprintf("SELECT * FROM %s WHERE id=1", $prefix."contest_info");
	$contest_info = mysqli_query($connection,$query_contest_info) or die (mysqli_error($connection));
	$row_contest_info = mysqli_fetch_assoc($contest_info);
}

// Do not rely on session data to populate Site Preferences for editing in Admin or in Setup
if ((($section == "admin") && ($go == "preferences")) || ($section == "step3")) {
	$query_prefs = sprintf("SELECT * FROM %s WHERE id=1", $prefix."preferences");
	$prefs = mysqli_query($connection,$query_prefs) or die (mysqli_error($connection));
	$row_prefs = mysqli_fetch_assoc($prefs);
	$totalRows_prefs = mysqli_num_rows($prefs);
}

// Do not rely on session data to populate Competition Organization Preferences (Judging Preferences) for editing in Admin or in Setup
if (SINGLE) $query_judging_prefs = sprintf("SELECT * FROM %s WHERE id='%s'", $prefix."judging_preferences",$_SESSION['comp_id']);
else $query_judging_prefs = sprintf("SELECT * FROM %s WHERE id='1'", $prefix."judging_preferences");
$judging_prefs = mysqli_query($connection,$query_judging_prefs) or die (mysqli_error($connection));
$row_judging_prefs = mysqli_fetch_assoc($judging_prefs);

$query_judge_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewerJudge='Y'", $prefix."brewer");
if (SINGLE) $query_judge_count = sprintf(" AND comp_id='%s'",$_SESSION['comp_id']);
$judge_count = mysqli_query($connection,$query_judge_count) or die (mysqli_error($connection));
$row_judge_count = mysqli_fetch_assoc($judge_count);

$query_steward_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewerSteward='Y'", $prefix."brewer");
if (SINGLE) $query_judge_count = sprintf(" AND comp_id='%s'",$_SESSION['comp_id']);
$steward_count = mysqli_query($connection,$query_steward_count) or die (mysqli_error($connection));
$row_steward_count = mysqli_fetch_assoc($steward_count);


if ($section == "default") {
		
	$query_check = sprintf("SELECT judgingDate FROM %s",$prefix."judging_locations");
	if (SINGLE) $query_judge_count .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
	$query_check .= " ORDER BY judgingDate DESC LIMIT 1";
	$check = mysqli_query($connection,$query_check) or die (mysqli_error($connection));
	$row_check = mysqli_fetch_assoc($check);

}
	
if (SINGLE) $query_contest_rules = sprintf("SELECT contestRules FROM %s WHERE id='%s'", $prefix."contest_info",$_SESSION['comp_id']);
else $query_contest_rules = sprintf("SELECT contestRules FROM %s WHERE id='1'", $prefix."contest_info");
$contest_rules = mysqli_query($connection,$query_contest_rules) or die (mysqli_error($connection));
$row_contest_rules = mysqli_fetch_assoc($contest_rules);	

if ($section == "volunteers") {

	$query_contest_info = sprintf("SELECT contestVolunteers FROM %s", $prefix."contest_info");
	if (SINGLE) $query_contest_info .= sprintf(" WHERE id='%s'", $_SESSION['comp_id']);
	else $query_contest_info .= " WHERE id='1'";
	$contest_info = mysqli_query($connection,$query_contest_info) or die (mysqli_error($connection));
	$row_contest_info = mysqli_fetch_assoc($contest_info);

}

// If using BA Styles, use the BreweryDB API to get all styles and store the resulting array as a session variable

if ($_SESSION['prefsStyleSet'] == "BA") {
	
	if (!isset($_SESSION['styles'])) {
		
		include (INCLUDES.'brewerydb/brewerydb.inc.php');
		include (INCLUDES.'brewerydb/exception.inc.php');
		
		$apikey = "9b986a69d8803dfcaedd2bbdabbf9169";
		$bdb = new Pintlabs_Service_Brewerydb($apikey);
		$bdb->setFormat('php');
		$params = array();
		
		try {
				$results = $bdb->request('styles', $params, 'GET'); // where $params is a keyed array of parameters to send with the API call.
			} 
			
		catch (Exception $e) { 
				$results = array('error' => $e->getMessage()); 
			}
			
		$_SESSION['styles'] = $results;
	}
	
}

$prefs_barcode_labels = array("N","C","2","0","3","4");
?>