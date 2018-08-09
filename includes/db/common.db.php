<?php
// General vars
$today = time();
$url = parse_url($_SERVER['PHP_SELF']);

mysqli_select_db($connection,$database);

$query_version1 = sprintf("SELECT * FROM %s WHERE id='1'", $prefix."system");
$version1 = mysqli_query($connection,$query_version1) or die (mysqli_error($connection));
$row_version1 = mysqli_fetch_assoc($version1);
$version = $row_version1['version'];

// Provide a variable to signify that the session has been set
$_SESSION['session_set_'.$prefix_session] = $prefix_session;

// Check to see if the session_set variable is corrupted or hijacked. If so, destroy the session and reset.
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
	if (SINGLE) $query_contest_info .= sprintf(" WHERE id='%s'", $_POST['comp_id']);
	else $query_contest_info .= " WHERE id='1'";
	$contest_info = mysqli_query($connection,$query_contest_info) or die (mysqli_error($connection));
	$row_contest_info = mysqli_fetch_assoc($contest_info);
	$contest_info_columns = array_keys($row_contest_info);

    foreach ($contest_info_columns as $contest_info_column_name) {
        if ($contest_info_column_name != "id") {
            if (isset($row_contest_info[$contest_info_column_name])) $_SESSION[$contest_info_column_name] = $row_contest_info[$contest_info_column_name];
            else $_SESSION[$contest_info_column_name] = "";
        }
    }

	$_SESSION['comp_id'] = $row_contest_info['id'];
	$_SESSION['contest_info_general'.$prefix_session] = $prefix_session;
}

if (empty($_SESSION['prefs'.$prefix_session])) {
	if (SINGLE) $query_prefs = sprintf("SELECT * FROM %s WHERE comp_id='%s'", $prefix."preferences",$_SESSION['comp_id']);
	else $query_prefs = sprintf("SELECT * FROM %s WHERE id='1'", $prefix."preferences");
	$prefs = mysqli_query($connection,$query_prefs) or die (mysqli_error($connection));
	$row_prefs = mysqli_fetch_assoc($prefs);
	$totalRows_prefs = mysqli_num_rows($prefs);
	if (isset($row_prefs['prefsDisplaySpecial'])) $_SESSION['prefsDisplaySpecial'] = $row_prefs['prefsDisplaySpecial'];
	else $_SESSION['prefsDisplaySpecial'] = "J"; // Default to use judging number for scoresheet downloads
	// For all preferences, check to see if their value is NULL, if so, provide default value
	// This prevents "breaking" of UI elements
	if (isset($row_prefs['prefsTemp'])) $_SESSION['prefsTemp'] = $row_prefs['prefsTemp'];
	else $_SESSION['prefsTemp'] = "Fahrenheit";
	if (isset($row_prefs['prefsWeight1'])) $_SESSION['prefsWeight1'] = $row_prefs['prefsWeight1'];
	else $_SESSION['prefsWeight1'] = "ounces";
	if (isset($row_prefs['prefsWeight2'])) $_SESSION['prefsWeight2'] = $row_prefs['prefsWeight2'];
	else $_SESSION['prefsWeight2'] = "pounds";
	if (isset($row_prefs['prefsLiquid1'])) $_SESSION['prefsLiquid1'] = $row_prefs['prefsLiquid1'];
	else $_SESSION['prefsLiquid1'] = "ounces";
	if (isset($row_prefs['prefsLiquid2'])) $_SESSION['prefsLiquid2'] = $row_prefs['prefsLiquid2'];
	else $_SESSION['prefsLiquid2'] = "gallons";
	if (isset($row_prefs['prefsCurrency'])) $_SESSION['prefsCurrency'] = $row_prefs['prefsCurrency'];
	else $_SESSION['prefsCurrency'] = "$";
	if (isset($row_prefs['prefsCash'])) $_SESSION['prefsCash'] = $row_prefs['prefsCash'];
	else $_SESSION['prefsCash'] = "N";
	if (isset($row_prefs['prefsCheck'])) $_SESSION['prefsCheck'] = $row_prefs['prefsCheck'];
	else $_SESSION['prefsCheck'] = "N";
	if (isset($row_prefs['prefsCheckPayee'])) $_SESSION['prefsCheckPayee'] = $row_prefs['prefsCheckPayee'];
	else {
		$_SESSION['prefsCheckPayee'] = "";
		$_SESSION['prefsCheck'] = "N";
	}
	if (isset($row_prefs['prefsPaypal'])) $_SESSION['prefsPaypal'] = $row_prefs['prefsPaypal'];
	else $_SESSION['prefsPaypal'] = "N";
	if (isset($row_prefs['prefsTransFee'])) $_SESSION['prefsTransFee'] = $row_prefs['prefsTransFee'];
	else $_SESSION['prefsTransFee'] = "N";
	if (isset($row_prefs['prefsPaypalIPN'])) $_SESSION['prefsPaypalIPN'] = $row_prefs['prefsPaypalIPN'];
	else $_SESSION['prefsPaypalIPN'] = "0";
	if (isset($row_prefs['prefsPaypalAccount'])) $_SESSION['prefsPaypalAccount'] = $row_prefs['prefsPaypalAccount'];
	else {
		$_SESSION['prefsPaypalAccount'] = "";
		$_SESSION['prefsPaypal'] = "N";
		$_SESSION['prefsTransFee'] = "N";
		$_SESSION['prefsPaypalIPN'] = "0";
	}
	if (isset($row_prefs['prefsSponsors'])) $_SESSION['prefsSponsors'] = $row_prefs['prefsSponsors'];
	else $SESSION['prefsSponsors'] = "N";
	if (isset($row_prefs['prefsSponsorLogos'])) $_SESSION['prefsSponsorLogos'] = $row_prefs['prefsSponsorLogos'];
	else $_SESSION['prefsSponsorLogos'] = "N";
	if (isset($row_prefs['prefsSponsorLogoSize'])) $_SESSION['prefsSponsorLogoSize'] = $row_prefs['prefsSponsorLogoSize'];
	else $_SESSION['prefsSponsorLogoSize'] = "250";
	if (isset($row_prefs['prefsCompLogoSize'])) $_SESSION['prefsCompLogoSize'] = $row_prefs['prefsCompLogoSize'];
	else $_SESSION['prefsCompLogoSize'] = "300";
	if (isset($row_prefs['prefsDisplayWinners'])) $_SESSION['prefsDisplayWinners'] = $row_prefs['prefsDisplayWinners'];
	else $_SESSION['prefsDisplayWinners'] = "N";
	if (isset($row_prefs['prefsWinnerDelay'])) $_SESSION['prefsWinnerDelay'] = $row_prefs['prefsWinnerDelay'];
	else $_SESSION['prefsWinnerDelay'] = 2145916800;
	if (isset($row_prefs['prefsWinnerMethod'])) $_SESSION['prefsWinnerMethod'] = $row_prefs['prefsWinnerMethod'];
	else $_SESSION['prefsWinnerMethod'] = 0;
	if (isset($_SESSION['prefsShowBestBrewer'])) $_SESSION['prefsShowBestBrewer'] = $row_prefs['prefsShowBestBrewer'];
	else $_SESSION['prefsShowBestBrewer'] = 0;
	if (isset($_SESSION['prefsBestBrewerTitle'])) $_SESSION['prefsBestBrewerTitle'] = $row_prefs['prefsBestBrewerTitle'];
	else $_SESSION['prefsBestBrewerTitle'] = "";
	if (isset($_SESSION['prefsShowBestClub'])) $_SESSION['prefsShowBestClub'] = $row_prefs['prefsShowBestClub'];
	else $_SESSION['prefsShowBestClub'] = 0;
	if (isset($_SESSION['prefsBestClubTitle'])) $_SESSION['prefsBestClubTitle'] = $row_prefs['prefsBestClubTitle'];
	else $_SESSION['prefsBestClubTitle'] = "";
	if (isset($_SESSION['prefsBestUseBOS'])) $_SESSION['prefsBestUseBOS'] = $row_prefs['prefsBestUseBOS'];
	else $_SESSION['prefsBestUseBOS'] = 0;
	if (isset($_SESSION['prefsFirstPlacePts'])) $_SESSION['prefsFirstPlacePts'] = $row_prefs['prefsFirstPlacePts'];
	else $_SESSION['prefsFirstPlacePts'] = 0;
	if (isset($_SESSION['prefsSecondPlacePts'])) $_SESSION['prefsSecondPlacePts'] = $row_prefs['prefsSecondPlacePts'];
	else $_SESSION['prefsSecondPlacePts'] = 0;
	if (isset($_SESSION['prefsThirdPlacePts'])) $_SESSION['prefsThirdPlacePts'] = $row_prefs['prefsThirdPlacePts'];
	else $_SESSION['prefsThirdPlacePts'] = 0;
	if (isset($_SESSION['prefsFourthPlacePts'])) $_SESSION['prefsFourthPlacePts'] = $row_prefs['prefsFourthPlacePts'];
	else $_SESSION['prefsFourthPlacePts'] = 0;
	if (isset($_SESSION['prefsHMPts'])) $_SESSION['prefsHMPts'] = $row_prefs['prefsHMPts'];
	else $_SESSION['prefsHMPts'] = 0;
	if (isset($_SESSION['prefsTieBreakRule1'])) $_SESSION['prefsTieBreakRule1'] = $row_prefs['prefsTieBreakRule1'];
	else $_SESSION['prefsTieBreakRule1'] = "";
	if (isset($_SESSION['prefsTieBreakRule2'])) $_SESSION['prefsTieBreakRule2'] = $row_prefs['prefsTieBreakRule2'];
	else $_SESSION['prefsTieBreakRule2'] = "";
	if (isset($_SESSION['prefsTieBreakRule3'])) $_SESSION['prefsTieBreakRule3'] = $row_prefs['prefsTieBreakRule3'];
	else $_SESSION['prefsTieBreakRule3'] = "";
	if (isset($_SESSION['prefsTieBreakRule4'])) $_SESSION['prefsTieBreakRule4'] = $row_prefs['prefsTieBreakRule4'];
	else $_SESSION['prefsTieBreakRule4'] = "";
	if (isset($_SESSION['prefsTieBreakRule5'])) $_SESSION['prefsTieBreakRule5'] = $row_prefs['prefsTieBreakRule5'];
	else $_SESSION['prefsTieBreakRule5'] = "";
	if (isset($_SESSION['prefsTieBreakRule6'])) $_SESSION['prefsTieBreakRule6'] = $row_prefs['prefsTieBreakRule6'];
	else $_SESSION['prefsTieBreakRule6'] = "";
	if (isset($row_prefs['prefsEntryForm'])) $_SESSION['prefsEntryForm'] = $row_prefs['prefsEntryForm'];
	else $_SESSION['prefsEntryForm'] = "C";
	if (isset($row_prefs['prefsRecordLimit'])) $_SESSION['prefsRecordLimit'] = $row_prefs['prefsRecordLimit'];
	else $_SESSION['prefsRecordLimit'] = 9999;
	if (isset($row_prefs['prefsRecordLimit'])) $_SESSION['prefsRecordPaging'] = $row_prefs['prefsRecordPaging'];
	else $row_prefs['prefsRecordPaging'] = 150;
	if (isset($row_prefs['prefsTheme'])) $_SESSION['prefsTheme'] = $row_prefs['prefsTheme'];
	else $_SESSION['prefsTheme'] = "default";
	if (isset($row_prefs['prefsDateFormat'])) $_SESSION['prefsDateFormat'] = $row_prefs['prefsDateFormat'];
	else $_SESSION['prefsDateFormat'] = "1";
	if (isset($row_prefs['prefsContact'])) $_SESSION['prefsContact'] = $row_prefs['prefsContact'];
	else $_SESSION['prefsContact'] = "N";
	if (isset($row_prefs['prefsTimeZone'])) $_SESSION['prefsTimeZone'] = $row_prefs['prefsTimeZone'];
	else $_SESSION['prefsTimeZone'] = "0";
	if (isset($row_prefs['prefsTimeFormat'])) $_SESSION['prefsTimeFormat'] = $row_prefs['prefsTimeFormat'];
	else $_SESSION['prefsTimeFormat'] = "0";
	if (isset($row_prefs['prefsPayToPrint'])) $_SESSION['prefsPayToPrint'] = $row_prefs['prefsPayToPrint'];
	else $_SESSION['prefsPayToPrint'] = "N";
	if (isset($row_prefs['prefsHideRecipe'])) $_SESSION['prefsHideRecipe'] = $row_prefs['prefsHideRecipe'];
	else $_SESSION['prefsHideRecipe'] = "Y";
	if (isset($row_prefs['prefsUseMods'])) $_SESSION['prefsUseMods'] = $row_prefs['prefsUseMods'];
	else $_SESSION['prefsUseMods'] = "N";
	if (isset($row_prefs['prefsSEF'])) $_SESSION['prefsSEF'] = $row_prefs['prefsSEF'];
	else $_SESSION['prefsSEF'] = "N";
	if (isset($row_prefs['prefsSpecialCharLimit'])) $_SESSION['prefsSpecialCharLimit'] = $row_prefs['prefsSpecialCharLimit'];
	else $_SESSION['prefsSpecialCharLimit'] = "150";
	if (isset($row_prefs['prefsStyleSet'])) $_SESSION['prefsStyleSet'] = $row_prefs['prefsStyleSet'];
	else $_SESSION['prefsStyleSet'] = "BJCP2015";
	if (isset($row_prefs['prefsAutoPurge'])) $_SESSION['prefsAutoPurge'] = $row_prefs['prefsAutoPurge'];
	else $_SESSION['prefsAutoPurge'] = "N";
	if (isset($row_prefs['prefsEntryLimitPaid'])) $_SESSION['prefsEntryLimitPaid'] = $row_prefs['prefsEntryLimitPaid'];
	else $_SESSION['prefsEntryLimitPaid'] = "";
	if (isset($row_prefs['prefsEmailRegConfirm'])) $_SESSION['prefsEmailRegConfirm'] = $row_prefs['prefsEmailRegConfirm'];
	else $_SESSION['prefsEmailRegConfirm'] = "0";
	if (isset($row_prefs['prefsSpecific'])) $_SESSION['prefsSpecific'] = $row_prefs['prefsSpecific'];
	else $_SESSION['prefsSpecific'] = "0";
	if (isset($row_prefs['prefsDropOff'])) $_SESSION['prefsDropOff'] = $row_prefs['prefsDropOff'];
	else $_SESSION['prefsDropOff'] = "0";
	if (isset($row_prefs['prefsShipping'])) $_SESSION['prefsShipping'] = $row_prefs['prefsShipping'];
	else $_SESSION['prefsShipping'] = "0";
	if (isset($row_prefs['prefsProEdition'])) $_SESSION['prefsProEdition'] = $row_prefs['prefsProEdition'];
	else $_SESSION['prefsProEdition'] = "0";
	if (isset($row_prefs['prefsCAPTCHA'])) $_SESSION['prefsCAPTCHA'] = $row_prefs['prefsCAPTCHA'];
	else $_SESSION['prefsCAPTCHA'] = "0";
	if (isset($row_prefs['prefsGoogleAccount'])) $_SESSION['prefsGoogleAccount'] = $row_prefs['prefsGoogleAccount'];
	else $_SESSION['prefsGoogleAccount'] = "";
	if (SINGLE) $query_judging_prefs = sprintf("SELECT * FROM %s WHERE id='%s'", $prefix."judging_preferences",$_SESSION['comp_id']);
	else $query_judging_prefs = sprintf("SELECT * FROM %s WHERE id='1'", $prefix."judging_preferences");
	$judging_prefs = mysqli_query($connection,$query_judging_prefs) or die (mysqli_error($connection));
	$row_judging_prefs = mysqli_fetch_assoc($judging_prefs);
	if (isset($row_judging_prefs['jPrefsQueued'])) $_SESSION['jPrefsQueued'] = $row_judging_prefs['jPrefsQueued'];
	else $_SESSION['jPrefsQueued'] = "Y";
	if (isset($row_judging_prefs['jPrefsFlightEntries'])) $_SESSION['jPrefsFlightEntries'] = $row_judging_prefs['jPrefsFlightEntries'];
	else $_SESSION['jPrefsFlightEntries'] = "12";
	if (isset($row_judging_prefs['jPrefsMaxBOS'])) $_SESSION['jPrefsMaxBOS'] = $row_judging_prefs['jPrefsMaxBOS'];
	else $_SESSION['jPrefsMaxBOS'] = "7";
	if (isset($row_judging_prefs['jPrefsRounds'])) $_SESSION['jPrefsRounds'] = $row_judging_prefs['jPrefsRounds'];
	else $_SESSION['jPrefsRounds'] = "3";
	if (isset($row_judging_prefs['jPrefsBottleNum'])) $_SESSION['jPrefsBottleNum'] = $row_judging_prefs['jPrefsBottleNum'];
	else $_SESSION['jPrefsBottleNum'] = "3";
	if (isset($row_judging_prefs['jPrefsCapStewards'])) $_SESSION['jPrefsCapStewards'] = $row_judging_prefs['jPrefsCapStewards'];
	else $_SESSION['jPrefsCapStewards'] = "";
	if (isset($row_judging_prefs['jPrefsCapJudges'])) $_SESSION['jPrefsCapJudges'] = $row_judging_prefs['jPrefsCapJudges'];
	else $_SESSION['jPrefsCapJudges'] = "";
	// Get counts for common, mostly static items
	$query_sponsor_count = sprintf("SELECT COUNT(*) as 'count' FROM %s", $prefix."sponsors");
	if (SINGLE) $query_sponsor_count .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
	$result_sponsor_count = mysqli_query($connection,$query_sponsor_count) or die (mysqli_error($connection));
	$row_sponsor_count = mysqli_fetch_assoc($result_sponsor_count);
	$_SESSION['sponsorCount'] = $row_sponsor_count['count'];
	$_SESSION['prefs'.$prefix_session] = "1";
	$_SESSION['prefix'] = $prefix;

	/*
	 * If using BA Styles, query DB (as of 2.1.13, BA styles are housed in the styles table)
	 * As of April 2018, BreweryDB is not issuing any further API keys
	 */

	if ($_SESSION['prefsStyleSet'] == "BA") {

		include(INCLUDES.'ba_constants.inc.php');

		$query_ba_style = sprintf("SELECT * FROM %s WHERE brewStyleVersion='BA'", $prefix."styles");
		$ba_style = mysqli_query($connection,$query_ba_style) or die (mysqli_error($connection));
		$row_ba_style = mysqli_fetch_assoc($ba_style);
		$totalRows_ba_style = mysqli_num_rows($ba_style);

		$ba_styles_arr_data = array();

		// Build various conditional arrays
		$ba_special_beer = array();
		$ba_special_mead_cider = array();
		$ba_carb = array();
		$ba_strength = array();
		$ba_sweetness = array();
		$ba_special_beer_ids = array();
		$ba_special_mead_cider_ids = array();
		$ba_carb_ids = array();
		$ba_strength_ids = array();
		$ba_sweetness_ids = array();
		$ba_beer = array();
		$ba_mead_cider = array();
		$ba_special_carb_str_sweet = array();
		$ba_special_carb_str_sweet_ids = array();
		$ba_carb_str_sweet = array();
		$ba_carb_str_sweet_ids = array();
		$ba_carb_str = array();
		$ba_carb_str_ids = array();
		$ba_carb_sweet = array();
		$ba_carb_sweet_ids = array();
		$ba_carb_special = array();
		$ba_carb_special_ids = array();
		$ba_carb_sweet_special = array();
		$ba_carb_sweet_special_ids = array();

		do {

			if (in_array($row_ba_style['brewStyleGroup'], $ba_beer_categories)) $ba_beer[] = $row_ba_style['id'];

			if (in_array($row_ba_style['brewStyleGroup'], $ba_mead_cider_categories)) $ba_mead_cider[] = $row_ba_style['id'];

			if ((in_array($row_ba_style['brewStyleGroup'], $ba_beer_categories)) && ($row_ba_style['brewStyleReqSpec'] > 0)) {
				$ba_special_beer[] = $row_ba_style['brewStyleGroup']."-".$row_ba_style['id'];
				$ba_special_beer_ids[] = $row_ba_style['id'];
			}

			if ((in_array($row_ba_style['brewStyleGroup'], $ba_mead_cider_categories)) && ($row_ba_style['brewStyleReqSpec'] > 0)) {
				$ba_special_mead_cider[] = $row_ba_style['brewStyleGroup']."-".$row_ba_style['id'];
				$ba_special_mead_cider_ids[] = $row_ba_style['id'];
			}

			if ($row_ba_style['brewStyleCarb'] > 0) {
				$ba_carb[] = $row_ba_style['brewStyleGroup']."-".$row_ba_style['id'];
				$ba_carb_ids[] = $row_ba_style['id'];
			}

			if ($row_ba_style['brewStyleStrength'] > 0) {
				$ba_strength[] = $row_ba_style['brewStyleGroup']."-".$row_ba_style['id'];
				$ba_strength_ids[] = $row_ba_style['id'];
			}

			if ($row_ba_style['brewStyleSweet'] > 0) {
				$ba_sweetness[] = $row_ba_style['brewStyleGroup']."-".$row_ba_style['id'];
				$ba_sweetness_ids[] = $row_ba_style['id'];
			}

			if (($row_ba_style['brewStyleReqSpec'] > 0) && ($row_ba_style['brewStyleCarb'] > 0) && ($row_ba_style['brewStyleStrength'] > 0) && ($row_ba_style['brewStyleSweet'] > 0)) {
				$ba_special_carb_str_sweet[] = $row_ba_style['brewStyleGroup']."-".$row_ba_style['id'];
				$ba_special_carb_str_sweet_ids[] = $row_ba_style['id'];
			}

			if (($row_ba_style['brewStyleCarb'] > 0) && ($row_ba_style['brewStyleStrength'] > 0) && ($row_ba_style['brewStyleSweet'] > 0)) {
				$ba_carb_str_sweet[] = $row_ba_style['brewStyleGroup']."-".$row_ba_style['id'];
				$ba_carb_str_sweet_ids[] = $row_ba_style['id'];
			}

			if (($row_ba_style['brewStyleCarb'] > 0) && ($row_ba_style['brewStyleStrength'] > 0)) {
				$ba_carb_str[] = $row_ba_style['brewStyleGroup']."-".$row_ba_style['id'];
				$ba_carb_str_ids[] = $row_ba_style['id'];
			}

			if (($row_ba_style['brewStyleCarb'] > 0) && ($row_ba_style['brewStyleSweet'] > 0)) {
				$ba_carb_sweet[] = $row_ba_style['brewStyleGroup']."-".$row_ba_style['id'];
				$ba_carb_sweet_ids[] = $row_ba_style['id'];
			}

			if (($row_ba_style['brewStyleReqSpec'] > 0) && ($row_ba_style['brewStyleCarb'] > 0)) {
				$ba_carb_special[] = $row_ba_style['brewStyleGroup']."-".$row_ba_style['id'];
				$ba_carb_special_ids[] = $row_ba_style['id'];
			}

			if (($row_ba_style['brewStyleReqSpec'] > 0) && ($row_ba_style['brewStyleCarb'] > 0) && ($row_ba_style['brewStyleSweet'] > 0)) {
				$ba_carb_sweet_special[] = $row_ba_style['brewStyleGroup']."-".$row_ba_style['id'];
				$ba_carb_sweet_special_ids[] = $row_ba_style['id'];
			}

		} while ($row_ba_style = mysqli_fetch_assoc($ba_style));

		$ba_special = array_merge($ba_special_beer,$ba_special_mead_cider);
		$ba_special_ids = array_merge($ba_special_beer_ids,$ba_special_mead_cider_ids);

		// Store only unique values
		$_SESSION['ba_special_beer'] = array_unique($ba_special_beer);
		$_SESSION['ba_special_beer_ids'] = array_unique($ba_special_beer_ids);
		$_SESSION['ba_special_mead_cider'] = array_unique($ba_special_mead_cider);
		$_SESSION['ba_special_mead_cider_ids'] = array_unique($ba_special_mead_cider_ids);
		$_SESSION['ba_carb'] = array_unique($ba_carb);
		$_SESSION['ba_carb_ids'] = array_unique($ba_carb_ids);
		$_SESSION['ba_strength'] = array_unique($ba_strength);
		$_SESSION['ba_strength_ids'] = array_unique($ba_strength_ids);
		$_SESSION['ba_sweetness'] = array_unique($ba_sweetness);
		$_SESSION['ba_sweetness_ids'] = array_unique($ba_sweetness_ids);
		$_SESSION['ba_beer'] = array_unique($ba_beer);
		$_SESSION['ba_mead_cider'] = array_unique($ba_mead_cider);
		$_SESSION['ba_special'] = array_unique($ba_special);
		$_SESSION['ba_special_ids'] = array_unique($ba_special_ids);
		$_SESSION['ba_special_carb_str_sweet'] = array_unique($ba_special_carb_str_sweet);
		$_SESSION['ba_special_carb_str_sweet_ids'] = array_unique($ba_special_carb_str_sweet_ids);
		$_SESSION['ba_carb_str_sweet'] = array_unique($ba_carb_str_sweet);
		$_SESSION['ba_carb_str_sweet_ids'] = array_unique($ba_carb_str_sweet_ids);
		$_SESSION['ba_carb_str'] = array_unique($ba_carb_str);
		$_SESSION['ba_carb_str_ids'] = array_unique($ba_carb_str_ids);
		$_SESSION['ba_carb_sweet'] = array_unique($ba_carb_sweet);
		$_SESSION['ba_carb_sweet_ids'] = array_unique($ba_carb_sweet_ids);
		$_SESSION['ba_carb_special'] = array_unique($ba_carb_special);
		$_SESSION['ba_carb_special_ids'] = array_unique($ba_carb_special_ids);
		$_SESSION['ba_carb_sweet_special'] = array_unique($ba_carb_sweet_special);
		$_SESSION['ba_carb_sweet_special_ids'] = array_unique($ba_carb_sweet_special_ids);

	}


}

if ((isset($_SESSION['loginUsername'])) && (empty($_SESSION['user_info'.$prefix_session])))  {

	$query_user = sprintf("SELECT * FROM %s WHERE user_name = '%s'", $prefix."users", $_SESSION['loginUsername']);
	$user = mysqli_query($connection,$query_user) or die (mysqli_error($connection));
	$row_user = mysqli_fetch_assoc($user);
	$totalRows_user = mysqli_num_rows($user);
	$user_columns = array_keys($row_user);

    foreach ($user_columns as $user_column_name) {
        if (($user_column_name != "password") && ($user_column_name != "id")) {
            if (isset($row_user[$user_column_name])) $_SESSION[$user_column_name] = $row_user[$user_column_name];
            else $_SESSION[$user_column_name] = "";
        }
    }

    $_SESSION['user_id'] = $row_user['id'];

	$query_name = sprintf("SELECT * FROM %s WHERE uid='%s'", $prefix."brewer", $_SESSION['user_id']);
	$name = mysqli_query($connection,$query_name) or die (mysqli_error($connection));
	$row_name = mysqli_fetch_assoc($name);
	$name_columns = array_keys($row_name);

    foreach ($name_columns as $name_column_name) {
        if ($name_column_name != "id") {
            if (isset($row_name[$name_column_name])) $_SESSION[$name_column_name] = $row_name[$name_column_name];
            else $_SESSION[$name_column_name] = "";
        }
    }

    $_SESSION['brewerID'] = $row_name['id'];
	$_SESSION['user_info'.$prefix_session] = $prefix_session;

}

if (isset($_SESSION['loginUsername'])) {

	if (($go == "make_admin") || (($go == "participants") && ($action == "add"))) {
		$query_user_level = sprintf("SELECT * FROM %s WHERE user_name = '%s'", $prefix."users", $username);
		$user_level = mysqli_query($connection,$query_user_level) or die (mysqli_error($connection));
		$row_user_level = mysqli_fetch_assoc($user_level);
		$totalRows_user_level = mysqli_num_rows($user_level);
	}

	elseif (($section == "brewer") && ($action == "edit")) {
		$query_user_level = sprintf("SELECT * FROM %s WHERE user_name = '%s'", $prefix."users",$_SESSION['loginUsername']);
		$user_level = mysqli_query($connection,$query_user_level) or die (mysqli_error($connection));
		$row_user_level = mysqli_fetch_assoc($user_level);
		$totalRows_user_level = mysqli_num_rows($user_level);
	}

}

// Set language preferences in session variables
if (empty($_SESSION['prefsLang'.$prefix_session])) {

	// Language - in current version only English is available. Future versions will feature translations.
	$_SESSION['prefsLanguage'] = $row_prefs['prefsLanguage'];

	// Check if variation used (demarked with a dash)
	$_SESSION['prefsLang'.$prefix_session] = $prefix_session;

}

if (empty($_SESSION['prefsLanguageFolder'.$prefix_session])) {

	if (strpos($_SESSION['prefsLanguage'], '-') !== FALSE) {
		$lang_folder = explode("-",$_SESSION['prefsLanguage']);
		$_SESSION['prefsLanguageFolder'] = strtolower($lang_folder[0]);
	}

	else $_SESSION['prefsLanguageFolder'] = strtolower($_SESSION['prefsLanguage']);
}

if ($section != "update") {
	// Some limits and dates may need to be changed by admin and propagated instantly to all users
	// These will be called on every page load instead of being stored in a session variable
	$query_limits = sprintf("SELECT prefsStyleSet, prefsEntryLimit, prefsUserEntryLimit, prefsSpecialCharLimit, prefsUserSubCatLimit, prefsUSCLEx, prefsUSCLExLimit, prefsEntryLimitPaid, prefsShowBestBrewer, prefsShowBestClub FROM %s WHERE id='1'", $prefix."preferences");
	$limits = mysqli_query($connection,$query_limits) or die (mysqli_error($connection));
	$row_limits = mysqli_fetch_assoc($limits);

	$query_judge_limits = sprintf("SELECT jprefsCapJudges,jprefsCapStewards FROM %s WHERE id='1'", $prefix."judging_preferences");
	$judge_limits = mysqli_query($connection,$query_judge_limits) or die (mysqli_error($connection));
	$row_judge_limits = mysqli_fetch_assoc($judge_limits);

	$query_contest_dates = sprintf("SELECT contestCheckInPassword, contestRegistrationOpen, contestRegistrationDeadline, contestJudgeOpen, contestJudgeDeadline, contestEntryOpen, contestEntryDeadline, contestShippingOpen, contestShippingDeadline, contestDropoffOpen, contestDropoffDeadline FROM %s WHERE id=1", $prefix."contest_info");
	$contest_dates = mysqli_query($connection,$query_contest_dates) or die (mysqli_error($connection));
	$row_contest_dates = mysqli_fetch_assoc($contest_dates);
}

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

// If Archive DB table, get pertinent info
if ($dbTable != "default") {
	$suffix = strrchr($dbTable,"_");
	$suffix = ltrim($suffix, "_");
	$query_archive_prefs = sprintf("SELECT archiveStyleSet,archiveProEdition,archiveScoresheet FROM %s WHERE archiveSuffix='%s'", $prefix."archive", $suffix);
	$archive_prefs = mysqli_query($connection,$query_archive_prefs) or die (mysqli_error($connection));
	$row_archive_prefs = mysqli_fetch_assoc($archive_prefs);
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

if (($section == "admin") && ($go == "default")) {
	if (SINGLE) $query_prefs = sprintf("SELECT * FROM %s WHERE comp_id='%s'", $prefix."preferences",$_SESSION['comp_id']);
	else $query_prefs = sprintf("SELECT * FROM %s WHERE id='1'", $prefix."preferences");
	$prefs = mysqli_query($connection,$query_prefs) or die (mysqli_error($connection));
	$row_prefs = mysqli_fetch_assoc($prefs);
	$totalRows_prefs = mysqli_num_rows($prefs);
}


/* *********************************************
 * Unused
 * *********************************************

do {

	$i = "0";

	$ba_styles_arr_data[] = array(
		"id"=>$row_ba_styles['id'],
		"category"=>array(
			"id"=>$i++,
			"name"=>$row_ba_styles['brewStyleCategory'],
			"createDate"=>time(),
		),
		"name"=>$row_ba_styles['brewStyle'],
		"shortName"=>$row_ba_styles['brewStyle'],
		"description"=>$row_ba_styles['brewStyleInfo'],
		"ibuMin"=>$row_ba_styles['brewStyleIBU'],
		"ibuMax"=>$row_ba_styles['brewStyleIBUMax'],
		"abvMin"=>$row_ba_styles['brewStyleABV'],
		"abvMax"=>$row_ba_styles['brewStyleABVMax'],
		"srmMin"=>$row_ba_styles['brewStyleSRM'],
		"srmMax"=>$row_ba_styles['brewStyleSRMMax'],
		"ogMin"=>$row_ba_styles['brewStyleOG'],
		"ogMax"=>$row_ba_styles['brewStyleOGMax'],
		"fgMin"=>$row_ba_styles['brewStyleFG'],
		"fgMax"=>$row_ba_styles['brewStyleFGMax'],
		"createDate"=>time(),
		"categoryID"=>$row_ba_styles['brewStyleGroup'],
		"brewStyleReqSpec"=>$row_ba_styles['brewStyleReqSpec'],
		"brewStyleStrength"=>$row_ba_styles['brewStyleStrength'],
		"brewStyleCarb"=>$row_ba_styles['brewStyleCarb'],
		"brewStyleSweet"=>$row_ba_styles['brewStyleSweet'],
		"brewStyleGroup"=>$row_ba_styles['brewStyleGroup']
	);

	$ba_styles_arr = array(
		"message"=>"Request Successful",
		"data"=>$ba_styles_arr_data,
		"status"=>"success"
	);

	$_SESSION['styles'] = $ba_styles_arr;
	$_SESSION['ba_style_count'] = $totalRows_ba_style;

	foreach ($_SESSION['styles'] as $ba_styles => $stylesData) {

		if (is_array($stylesData) || is_object($stylesData)) {

			foreach ($stylesData as $key => $ba_style) {



			} // end foreach ($stylesData as $key => $ba_style)

		} // end if (is_array($stylesData) || is_object($stylesData))

	} // end foreach ($_SESSION['styles'] as $styles => $stylesData)

} while ($row_ba_style = mysqli_fetch_assoc($ba_style));

$bdb_api_key = explode("|",$_SESSION['prefsStyleSet']);
if ((isset($bdb_api_key[1])) && ($bdb_api_key[1] != "000000")) $bdb_api_key_check = TRUE;
else $bdb_api_key_check = FALSE;

if (!isset($_SESSION['styles'])) {



	// If an API key is found, connect to BreweryDB API and get data
	if ($bdb_api_key_check) {

		include (INCLUDES.'brewerydb/brewerydb.inc.php');
		include (INCLUDES.'brewerydb/exception.inc.php');

		$apikey = $bdb_api_key[1];
		$bdb = new Pintlabs_Service_Brewerydb($apikey);
		$bdb->setFormat('php');
		$params = array();

		try {
				$results = $bdb->request('styles', $params, 'GET'); // where $params is a keyed array of parameters to send with the API call.
			}

		catch (Exception $e) {
				$results = array('error' => $e->getMessage());
			}

		// store results in a session variable
		$_SESSION['styles'] = $results;

		// store related data in session variables
		$_SESSION['ba_special'] = array("3-27","3-28","3-44","4-56","5-70","11-114","11-117","11-119","11-120","11-121","11-124","11-125","11-126","11-128","11-130","11-131","11-132","11-133","11-134","11-135","11-136","11-137","12-145","12-146","12-148","12-151","12-153","12-154","12-155","12-157","14-161","14-162","12-170");
		$_SESSION['ba_special_beer'] = array("3-27","3-28","3-44","4-56","5-70","11-114","11-117","11-119","11-120","11-121","11-124","11-125","11-126","11-128","11-130","11-131","11-132","11-133","11-134","11-135","11-136","11-137","14-161","14-162","12-170");
		$_SESSION['ba_special_mead_cider'] = array("12-146","12-148","12-151","12-153","12-154","12-155","12-157");
		$_SESSION['ba_carb'] = array("12-140","12-141","12-142","12-143","12-144","12-145","12-146","12-147","12-148","12-149","12-150","12-151","12-152","12-153","12-154","12-155","12-156","12-157");
		$_SESSION['ba_strength'] = array("12-140","12-141","12-142","12-143","12-144","12-145","12-146","12-147","12-148");
		$_SESSION['ba_sweetness'] = array("12-143","12-144","12-145","12-146","12-147","12-148","12-149","12-150","12-151","12-152","12-153","12-154","12-155","12-156","12-157");
		$_SESSION['ba_special_carb_str_sweet'] = array("12-145","12-146","12-148");
		$_SESSION['ba_carb_str_sweet'] = array("12-143","12-144","12-147");
		$_SESSION['ba_carb_str'] = array("12-140","12-141","12-142");
		$_SESSION['ba_carb_sweet'] = array("12-149","12-150","12-152","12-156");
		$_SESSION['ba_carb_special'] = array();
		$_SESSION['ba_carb_sweet_special'] = array("12-151","12-153","12-154","12-155","12-157");

		$_SESSION['ba_special_ids'] = array(27,28,44,56,70,114,117,119,120,121,124,125,126,128,130,131,132,133,134,135,136,137,145,146,148,151,153,154,155,157,161,162,170);
		$_SESSION['ba_special_beer_ids'] = array(27,28,44,56,70,114,117,119,120,121,124,125,126,128,130,131,132,133,134,135,136,137,161,162,170);
		$_SESSION['ba_special_mead_cider_ids'] = array(145,146,148,151,153,154,155,157);
		$_SESSION['ba_carb_ids'] = array(140,141,142,143,144,145,146,147,148,149,150,151,152,153,154,155,156,157);
		$_SESSION['ba_strength_ids'] = array(140,141,142,143,144,145,146,147,148);
		$_SESSION['ba_sweetness_ids'] = array(143,144,145,146,147,148,149,150,151,152,153,154,155,156,157);
		$_SESSION['ba_special_carb_str_sweet_ids'] = array(145,146,148);
		$_SESSION['ba_carb_str_sweet_ids'] = array(143,144,147);
		$_SESSION['ba_carb_str_ids'] = array(140,141,142);
		$_SESSION['ba_carb_sweet_ids'] = array(149,150,152,156);
		$_SESSION['ba_carb_special_ids'] = array();
		$_SESSION['ba_carb_sweet_special_ids'] = array();
		$_SESSION['ba_mead_cider'] = array(140,141,142,143,144,145,146,147,148,149,150,151,152,153,154,155,156,157);
		$_SESSION['ba_mead'] = array(140,141,142,143,144,145,146,147,148);
		$_SESSION['ba_cider'] = array(149,150,151,152,153,154,155,156,157);

	}

}

 */


$prefs_barcode_labels = array("N","C","2","0","3","4");

?>