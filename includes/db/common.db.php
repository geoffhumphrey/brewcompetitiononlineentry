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
if (((!empty($_SESSION['session_set_'.$prefix_session])) && ($_SESSION['session_set_'.$prefix_session] != $prefix_session)) || ((!isset($_SESSION['session_set_'.$prefix_session])) || (empty($_SESSION['session_set_'.$prefix_session])))) {

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
if ((!isset($_SESSION['contest_info_general'.$prefix_session])) || (empty($_SESSION['contest_info_general'.$prefix_session]))) {

	if (strpos($section, "step") === FALSE) {
		$query_contest_info = sprintf("SELECT * FROM %s", $prefix."contest_info");
		if (SINGLE) $query_contest_info .= sprintf(" WHERE id='%s'", $_POST['comp_id']);
		else $query_contest_info .= " WHERE id='1'";
		$contest_info = mysqli_query($connection,$query_contest_info) or die (mysqli_error($connection));
		$row_contest_info = mysqli_fetch_assoc($contest_info);

	    foreach ($row_contest_info as $key => $value) {
			if ($key != "id") $_SESSION[$key] = $value;
		}

		$_SESSION['comp_id'] = $row_contest_info['id'];
		$_SESSION['contest_info_general'.$prefix_session] = $prefix_session;
	}
}

if ((!isset($_SESSION['prefs'.$prefix_session])) || (empty($_SESSION['prefs'.$prefix_session]))) {

	if (strpos($section, "step") === FALSE) {

		if (SINGLE) $query_prefs = sprintf("SELECT * FROM %s WHERE comp_id='%s'", $prefix."preferences",$_SESSION['comp_id']);
		else $query_prefs = sprintf("SELECT * FROM %s WHERE id='1'", $prefix."preferences");
		$prefs = mysqli_query($connection,$query_prefs) or die (mysqli_error($connection));
		$row_prefs = mysqli_fetch_assoc($prefs);
		$totalRows_prefs = mysqli_num_rows($prefs);

		foreach ($row_prefs as $key => $value) {
			if ($key != "id") $_SESSION[$key] = $value;
		}

		if (SINGLE) $query_judging_prefs = sprintf("SELECT * FROM %s WHERE id='%s'", $prefix."judging_preferences",$_SESSION['comp_id']);
		else $query_judging_prefs = sprintf("SELECT * FROM %s WHERE id='1'", $prefix."judging_preferences");
		$judging_prefs = mysqli_query($connection,$query_judging_prefs) or die (mysqli_error($connection));
		$row_judging_prefs = mysqli_fetch_assoc($judging_prefs);

		foreach ($row_judging_prefs as $key => $value) {
			if ($key != "id") $_SESSION[$key] = $value;
		}

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

}

if ((isset($_SESSION['loginUsername'])) && ((!isset($_SESSION['user_info'.$prefix_session])) || (empty($_SESSION['user_info'.$prefix_session]))))  {

	if (strpos($section, "step") === FALSE) {
		$query_user = sprintf("SELECT * FROM %s WHERE user_name = '%s'", $prefix."users", $_SESSION['loginUsername']);
		$user = mysqli_query($connection,$query_user) or die (mysqli_error($connection));
		$row_user = mysqli_fetch_assoc($user);
		$totalRows_user = mysqli_num_rows($user);

	    foreach ($row_user as $key => $value) {
			if ($key != "id") $_SESSION[$key] = $value;
		}

	    $_SESSION['user_id'] = $row_user['id'];

		$query_name = sprintf("SELECT * FROM %s WHERE uid='%s'", $prefix."brewer", $_SESSION['user_id']);
		$name = mysqli_query($connection,$query_name) or die (mysqli_error($connection));
		$row_name = mysqli_fetch_assoc($name);
		$name_columns = array_keys($row_name);

	    foreach ($row_name as $key => $value) {
			if ($key != "id") $_SESSION[$key] = $value;
		}

	    $_SESSION['brewerID'] = $row_name['id'];
		$_SESSION['user_info'.$prefix_session] = $prefix_session;
	}

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
if ((!isset($_SESSION['prefsLang'.$prefix_session]))|| (empty($_SESSION['prefsLang'.$prefix_session]))) {

	// Language - in current version only English is available. Future versions will feature translations.
	if ($section != "update") $_SESSION['prefsLanguage'] = $row_prefs['prefsLanguage'];
	elseif (!isset($row_prefs['prefsLanguage'])) {
		$_SESSION['prefsLanguage'] = "en-US";
	}
	else $_SESSION['prefsLanguage'] = "en-US";

	// Check if variation used (demarked with a dash)
	$_SESSION['prefsLang'.$prefix_session] = $prefix_session;

}

if ((!isset($_SESSION['prefsLanguageFolder'.$prefix_session]))|| (empty($_SESSION['prefsLanguageFolder'.$prefix_session]))) {

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

$prefs_barcode_labels = array("N","C","2","0","3","4");

?>