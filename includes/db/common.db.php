<?php
declare(strict_types=1);
/**
 *  2026.05.05:  Conversion to MysqliDB class for prepared statements
 */

// General vars
$today = time();
$url = parse_url($_SERVER['PHP_SELF']);

$version = "";

if (check_setup($prefix."system",$database)) {

	$db_conn->where ("id", 1);
	$row_version1 = $db_conn->getOne ($prefix."system");
	$version = $row_version1['version'];

}

if (check_setup($prefix."bcoem_sys",$database)) {

	$db_conn->where ("id", 1);
	$row_version1 = $db_conn->getOne ($prefix."bcoem_sys");
	$version = $row_version1['version'];

}

if (empty($version)) {
	if (session_status() !== PHP_SESSION_NONE) {
		session_unset();
		session_destroy();
		session_write_close();
	}
	$redirect = $base_url."setup.php?section=step0";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);
	header($redirect_go_to);
}

// Check to see if the session_set variable is corrupted or hijacked. If so, destroy the session and reset.
if (((!empty($_SESSION['session_set_'.$prefix_session])) && ($_SESSION['session_set_'.$prefix_session] != $prefix_session)) || ((!isset($_SESSION['session_set_'.$prefix_session])) || (empty($_SESSION['session_set_'.$prefix_session])))) {
	if (session_status() !== PHP_SESSION_NONE) {
		session_unset();
		session_destroy();
		session_write_close();
	}
	session_name($prefix_session);
	session_start();
	session_regenerate_id(true);
}

// Provide a variable to signify that the session has been set
$_SESSION['session_set_'.$prefix_session] = $prefix_session;

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

		$db_conn->where ("id", 1);
		$row_contest_info = $db_conn->getOne ($prefix."contest_info");

		if ($row_contest_info) {

			foreach ($row_contest_info as $key => $value) {
				if ($key != "id") $_SESSION[$key] = $value;
			}

			$_SESSION['comp_id'] = $row_contest_info['id'];

		}

		$_SESSION['contest_info_general'.$prefix_session] = $prefix_session;
	}

}

if ((!isset($_SESSION['prefs'.$prefix_session])) || (empty($_SESSION['prefs'.$prefix_session]))) {

	if (strpos($section, "step") === FALSE) {

		$db_conn->where ("id", 1);
		$row_prefs = $db_conn->getOne ($prefix."preferences");
		$totalRows_prefs = $db_conn->count; 

		if ($totalRows_prefs > 0) {
			foreach ($row_prefs as $key => $value) {
				if ($key != "id") $_SESSION[$key] = $value;
			}
		}

		$db_conn->where ("id", 1);
		$row_judging_prefs = $db_conn->getOne ($prefix."judging_preferences");
		$totalRows_judging_prefs = $db_conn->count; 

		if ($totalRows_judging_prefs > 0) {
			foreach ($row_judging_prefs as $key => $value) {
				if ($key != "id") $_SESSION[$key] = $value;
			}
		}
		
		// Get counts for common, mostly static items
		$row_sponsor_count = $db_conn->getOne ($prefix."sponsors", "sum(id), COUNT(*) as count");

		$_SESSION['sponsorCount'] = $row_sponsor_count['count'];
		$_SESSION['prefs'.$prefix_session] = "1";
		$_SESSION['prefix'] = $prefix;

		// Bring Style Set Information into session if preference is set
		if (isset($_SESSION['prefsStyleSet'])) {
			foreach ($style_sets as $style_set_data) {
				if ($style_set_data['style_set_name'] === $_SESSION['prefsStyleSet']) {
					$_SESSION['style_set_id'] = $style_set_data['id'];
					$_SESSION['style_set_name'] = $style_set_data['style_set_name'];
					$_SESSION['style_set_short_name'] = $style_set_data['style_set_short_name'];
					$_SESSION['style_set_long_name'] = $style_set_data['style_set_long_name'];
					$_SESSION['style_set_display_separator'] = $style_set_data['style_set_display_separator'];
					$_SESSION['style_set_system_separator'] = $style_set_data['style_set_system_separator'];
					$_SESSION['style_set_sub_style_method'] = $style_set_data['style_set_sub_style_method'];
					$_SESSION['style_set_beer_end'] = $style_set_data['style_set_beer_end'];
					$_SESSION['style_set_mead'] = $style_set_data['style_set_mead'];
					$_SESSION['style_set_cider'] = $style_set_data['style_set_cider'];
					$_SESSION['style_set_category_end'] = $style_set_data['style_set_category_end'];
				}
			}
		}

		/*
		 * If using BA Styles, query DB (as of 2.1.13, BA styles are housed in the styles table)
		 * As of April 2018, BreweryDB is not issuing any further API keys
		 */

		if ((isset($_SESSION['prefsStyleSet'])) && ($_SESSION['prefsStyleSet'] == "BA")) {

			include(INCLUDES.'ba_constants.inc.php');

			$db_conn->where ('brewStyleVersion', 'BA');
			$db_conn->returnType = 'array'; 
			$return_ba_style = $db_conn->get($prefix."styles");
			$totalRows_ba_style = $db_conn->count;


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

			foreach ($return_ba_style as $row_ba_style) {

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

			}

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

		if ($section != "setup") {

			$db_conn->where ('user_name', $_SESSION['loginUsername']);
			$row_user = $db_conn->getOne ($prefix."users");
			$totalRows_user = $db_conn->count; 

			/*
			$query_user = sprintf("SELECT * FROM %s WHERE user_name = '%s'", $prefix."users", $_SESSION['loginUsername']);
			$user = mysqli_query($connection,$query_user) or die (mysqli_error($connection));
			$row_user = mysqli_fetch_assoc($user);
			$totalRows_user = mysqli_num_rows($user);
			*/

			if ($totalRows_user > 0) {

				foreach ($row_user as $key => $value) {
					if ($key != "id") $_SESSION[$key] = $value;
				}
				
			}

		    $_SESSION['user_id'] = $row_user['id'];

		    $db_conn->where ('uid', $row_user['id']);
			$row_name = $db_conn->getOne ($prefix."brewer");
			$totalRows_name = $db_conn->count; 

			/*
			$query_name = sprintf("SELECT * FROM %s WHERE uid='%s'", $prefix."brewer", $row_user['id']);
			$brewer_name = mysqli_query($connection,$query_name) or die (mysqli_error($connection));
			$row_name = mysqli_fetch_assoc($brewer_name);
			*/
			
			if ($totalRows_name > 0) {

				$name_columns = array_keys($row_name);

			    foreach ($row_name as $key => $value) {
					if ($key != "id") $_SESSION[$key] = $value;
				}

			}

		    $_SESSION['brewerID'] = $row_name['id'];
			$_SESSION['user_info'.$prefix_session] = $prefix_session;

		}
			
	}

}

if (isset($_SESSION['loginUsername'])) {

	if (($go == "make_admin") || (($go == "participants") && ($action == "add"))) {
		$db_conn->where ('user_name', $username);
	}

	elseif (($section == "brewer") && ($action == "edit")) {
		$db_conn->where ('user_name', $_SESSION['loginUsername']);
	}

	$row_user_level = $db_conn->getOne ($prefix."users");
	$totalRows_user_level = $db_conn->count; 

}

// Set language preferences in session variables
if ((!isset($_SESSION['prefsLang'.$prefix_session]))|| (empty($_SESSION['prefsLang'.$prefix_session]))) {

	if (($section != "update") && (empty($row_prefs['prefsLanguage']))) $_SESSION['prefsLanguage'] = $row_prefs['prefsLanguage'];

	if ((!isset($_SESSION['prefsLanguage'])) || (empty($row_prefs['prefsLanguage']))) $_SESSION['prefsLanguage'] = $row_prefs['prefsLanguage'];

	// Check if variation used (demarked with a dash)
	$_SESSION['prefsLang'.$prefix_session] = $prefix_session;

}

if ((!isset($_SESSION['prefsLanguageFolder'.$prefix_session]))|| (empty($_SESSION['prefsLanguageFolder'.$prefix_session]))) {

	// Legacy installs may still have prefsLanguage stored as "English" (pre-locale-code
	// installs, e.g. via the 2.1.5.0 update) rather than "en-US" - normalize before deriving
	// the folder, or this falls through to strtolower() producing "english", which doesn't
	// match the actual "en" folder on disk.
	if (strtolower($_SESSION['prefsLanguage']) == "english") $_SESSION['prefsLanguage'] = "en-US";

	if (strpos($_SESSION['prefsLanguage'], '-') !== FALSE) {
		$lang_folder = explode("-",$_SESSION['prefsLanguage']);
		$_SESSION['prefsLanguageFolder'] = strtolower($lang_folder[0]);
	}

	else $_SESSION['prefsLanguageFolder'] = strtolower($_SESSION['prefsLanguage']);
}

// Check for Tables Planning Mode

if ((check_update("flightPlanning", $prefix."judging_flights")) && ((!isset($_SESSION['jPrefsTablePlanning'])) || (empty($_SESSION['jPrefsTablePlanning'])))) {

	// Check judging_flights for any record with a 1 (planning mode);
	// If found, set as 1, otherwise set as 0

	/*
	$query_planning = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE flightPlanning='1'", $prefix."judging_flights");
	$planning = mysqli_query($connection,$query_planning) or die (mysqli_error($connection));
	$row_planning = mysqli_fetch_assoc($planning);
	*/

	$db_conn->where ('flightPlanning', '1');
	$db_conn->get ($prefix."judging_flights");
	$totalRows_planning = $db_conn->count;

	if ($totalRows_planning > 0) $_SESSION['jPrefsTablePlanning'] = 1;
	else $_SESSION['jPrefsTablePlanning'] = 0;

}

if ((check_update("prefsShowBestBrewer", $prefix."preferences")) && ($section != "update")) {
	
	// Some limits and dates may need to be changed by admin and propagated instantly to all users
	// These will be called on every page load instead of being stored in a session variable

	$cols = array("prefsStyleSet", "prefsEntryLimit", "prefsUserEntryLimit", "prefsSpecialCharLimit", "prefsUserSubCatLimit", "prefsUSCLEx", "prefsUSCLExLimit", "prefsEntryLimitPaid", "prefsShowBestBrewer", "prefsShowBestClub", "prefsUserEntryLimitDates");
	$db_conn->where ("id", 1);
	$row_limits = $db_conn->getOne ($prefix."preferences", null, $cols);
	$totalRows_limits = $db_conn->count;

	/*
	$query_limits = sprintf("SELECT prefsStyleSet, prefsEntryLimit, prefsUserEntryLimit, prefsSpecialCharLimit, prefsUserSubCatLimit, prefsUSCLEx, prefsUSCLExLimit, prefsEntryLimitPaid, prefsShowBestBrewer, prefsShowBestClub, prefsUserEntryLimitDates FROM %s WHERE id='1'", $prefix."preferences");
	$limits = mysqli_query($connection,$query_limits) or die (mysqli_error($connection));
	$row_limits = mysqli_fetch_assoc($limits);
	*/

	$incremental = FALSE;

	$real_overall_user_entry_limit = "";
	if (!empty($row_limits['prefsUserEntryLimit'])) $real_overall_user_entry_limit = $row_limits['prefsUserEntryLimit'];

	if (!empty($row_limits['prefsUserEntryLimitDates'])) {

		$incremental = TRUE;	    
	    $incremental_limits = json_decode($row_limits['prefsUserEntryLimitDates'],true);

	    $limit_date_1 = "";
	    $limit_date_2 = "";
	    $limit_date_3 = "";
	    $limit_date_4 = "";

	    $current_limit = 0;

	    if (isset($_SESSION['contestEntryOpen'])) {

	    	if ((isset($incremental_limits[1]['limit-number'])) && (isset($incremental_limits[1]['limit-days']))) {
	    		$limit_date_1 = $_SESSION['contestEntryOpen'] + ($incremental_limits[1]['limit-days'] * 86400);
	    		if (time() <= $limit_date_1) $current_limit = 1;	
	    	}

	    	if ((isset($incremental_limits[2]['limit-number'])) && (isset($incremental_limits[2]['limit-days']))) {
	    		$limit_date_2 = $_SESSION['contestEntryOpen'] + ($incremental_limits[2]['limit-days'] * 86400);
	    		if ((time() > $limit_date_1) && (time() <= $limit_date_2)) $current_limit = 2;    	
	    	}

	    	if ((isset($incremental_limits[3]['limit-number'])) && (isset($incremental_limits[3]['limit-days']))) {
	    		$limit_date_3 = $_SESSION['contestEntryOpen'] + ($incremental_limits[3]['limit-days'] * 86400);
	    		if ((time() > $limit_date_2) && (time() <= $limit_date_3)) $current_limit = 3;	    	
	    	}

	    	if ((isset($incremental_limits[4]['limit-number'])) && (isset($incremental_limits[4]['limit-days']))) {
	    		$limit_date_4 = $_SESSION['contestEntryOpen'] + ($incremental_limits[4]['limit-days'] * 86400);
	    		if ((time() > $limit_date_3) && (time() <= $limit_date_4)) $current_limit = 4;	
	    	}

	    	if ($current_limit == 0) $row_limits['prefsUserEntryLimit'] = $real_overall_user_entry_limit;
	    	elseif ($current_limit == 1) $row_limits['prefsUserEntryLimit'] = $incremental_limits[1]['limit-number']; 
	    	elseif ($current_limit == 2) $row_limits['prefsUserEntryLimit'] = $incremental_limits[2]['limit-number'];
	    	elseif ($current_limit == 3) $row_limits['prefsUserEntryLimit'] = $incremental_limits[3]['limit-number']; 
	    	elseif ($current_limit == 4) $row_limits['prefsUserEntryLimit'] = $incremental_limits[4]['limit-number'];

	    }

	}

	$cols = array("jprefsCapJudges","jprefsCapStewards");
	$db_conn->where ("id", 1);
	$row_judge_limits = $db_conn->getOne ($prefix."judging_preferences", null, $cols);

	/*
	$query_judge_limits = sprintf("SELECT jprefsCapJudges,jprefsCapStewards FROM %s WHERE id='1'", $prefix."judging_preferences");
	$judge_limits = mysqli_query($connection,$query_judge_limits) or die (mysqli_error($connection));
	$row_judge_limits = mysqli_fetch_assoc($judge_limits);
	*/

	$cols = array("contestCheckInPassword", "contestRegistrationOpen", "contestRegistrationDeadline", "contestJudgeOpen", "contestJudgeDeadline", "contestEntryOpen", "contestEntryDeadline", "contestShippingOpen", "contestShippingDeadline", "contestDropoffOpen", "contestDropoffDeadline", "contestEntryEditDeadline", "contestAwardsLocTime");
	$db_conn->where ("id", 1);
	$row_contest_dates = $db_conn->getOne ($prefix."contest_info", null, $cols);

	/*
	$query_contest_dates = sprintf("SELECT contestCheckInPassword, contestRegistrationOpen, contestRegistrationDeadline, contestJudgeOpen, contestJudgeDeadline, contestEntryOpen, contestEntryDeadline, contestShippingOpen, contestShippingDeadline, contestDropoffOpen, contestDropoffDeadline, contestEntryEditDeadline, contestAwardsLocTime FROM %s WHERE id=1", $prefix."contest_info");
	$contest_dates = mysqli_query($connection,$query_contest_dates) or die (mysqli_error($connection));
	$row_contest_dates = mysqli_fetch_assoc($contest_dates);
	*/

}

// Only used for initial setup of installation
if ($section == "step4") {

	$cols = array("brewerFirstName","brewerLastName","brewerEmail");
	$db_conn->where ("uid", 1);
	$row_name = $db_conn->getOne ($brewer_db_table, null, $cols);

	/*
	$query_name = "SELECT brewerFirstName,brewerLastName,brewerEmail FROM $brewer_db_table WHERE uid='1'";
	$name = mysqli_query($connection,$query_name) or die (mysqli_error($connection));
	$row_name = mysqli_fetch_assoc($name);
	*/

}

// Do not rely on session data to populate Competition Info for editing in Admin or in Setup
if (($section == "admin") && ($go == "contest_info")) {

	$db_conn->where ("id", 1);
	$row_contest_info = $db_conn->getOne ($prefix."contest_info");

	/*
	$query_contest_info = sprintf("SELECT * FROM %s WHERE id=1", $prefix."contest_info");
	$contest_info = mysqli_query($connection,$query_contest_info) or die (mysqli_error($connection));
	$row_contest_info = mysqli_fetch_assoc($contest_info);
	*/
}

// Do not rely on session data to populate Site Preferences for editing in Admin or in Setup
if ((($section == "admin") && ($go == "preferences")) || ($section == "step3")) {

	$db_conn->where ("id", 1);
	$row_prefs = $db_conn->getOne ($prefix."preferences");
	$totalRows_prefs = $db_conn->count;

	/*
	$query_prefs = sprintf("SELECT * FROM %s WHERE id=1", $prefix."preferences");
	$prefs = mysqli_query($connection,$query_prefs) or die (mysqli_error($connection));
	$row_prefs = mysqli_fetch_assoc($prefs);
	$totalRows_prefs = mysqli_num_rows($prefs);
	*/

}

// If Archive DB table, get pertinent info
if ($dbTable != "default") {
	
	$suffix = strrchr($dbTable,"_");
	$suffix = ltrim($suffix, "_");
	$db_conn->where ("archiveSuffix", $suffix);
	$row_archive_prefs = $db_conn->getOne ($prefix."archive");
	$totalRows_archive_prefs = $db_conn->count;

	/*
	$query_archive_prefs = sprintf("SELECT * FROM %s WHERE archiveSuffix='%s'", $prefix."archive", $suffix);
	$archive_prefs = mysqli_query($connection,$query_archive_prefs) or die (mysqli_error($connection));
	$row_archive_prefs = mysqli_fetch_assoc($archive_prefs);
	*/

}

if ($section != "admin") {

	$db_conn->orderBy ('archiveSuffix', 'ASC');
	$row_archive = $db_conn->get ($prefix."archive");
	$totalRows_archive = $db_conn->count;

	/*
	$query_archive = sprintf("SELECT * FROM %s ORDER BY archiveSuffix ASC", $prefix."archive");
	$archive = mysqli_query($connection,$query_archive) or die (mysqli_error($connection));
	$row_archive = mysqli_fetch_assoc($archive);
	$totalRows_archive = mysqli_num_rows($archive);
	*/

}

// Do not rely on session data to populate Judging/Competition Organization Preferences

$db_conn->where ("id", 1);
$row_judging_prefs = $db_conn->getOne ($prefix."judging_preferences");
$totalRows_judging_prefs = $db_conn->count; 

/*
$query_judging_prefs = sprintf("SELECT * FROM %s WHERE id='1'", $prefix."judging_preferences");
$judging_prefs = mysqli_query($connection,$query_judging_prefs) or die (mysqli_error($connection));
$row_judging_prefs = mysqli_fetch_assoc($judging_prefs);
*/

$db_conn->where ("brewerJudge", "Y");
$row_judge_count = $db_conn->getOne ($prefix."brewer", "sum(id), COUNT(*) as count");

/*
$query_judge_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewerJudge='Y'", $prefix."brewer");
$judge_count = mysqli_query($connection,$query_judge_count) or die (mysqli_error($connection));
$row_judge_count = mysqli_fetch_assoc($judge_count);
*/

$db_conn->where ("brewerSteward", "Y");
$row_steward_count = $db_conn->getOne ($prefix."brewer", "sum(id), COUNT(*) as count");

/*
$query_steward_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewerSteward='Y'", $prefix."brewer");
$steward_count = mysqli_query($connection,$query_steward_count) or die (mysqli_error($connection));
$row_steward_count = mysqli_fetch_assoc($steward_count);
*/

if ($section == "default") {

	$sql = sprintf("SELECT judgingDate FROM %s ORDER BY judgingDate DESC LIMIT 1",$prefix."judging_locations");
	$row_check = $db_conn->rawQueryOne($sql);

	/*
	$query_check = sprintf("SELECT judgingDate FROM %s",$prefix."judging_locations");
	$query_check .= " ORDER BY judgingDate DESC LIMIT 1";
	$check = mysqli_query($connection,$query_check) or die (mysqli_error($connection));
	$row_check = mysqli_fetch_assoc($check);
	*/

}

$db_conn->where ("id", 1);
$row_contest_rules = $db_conn->getOne ($prefix."contest_info", null, "contestRules");

/*
$query_contest_rules = sprintf("SELECT contestRules FROM %s WHERE id='1'", $prefix."contest_info");
$contest_rules = mysqli_query($connection,$query_contest_rules) or die (mysqli_error($connection));
$row_contest_rules = mysqli_fetch_assoc($contest_rules);
*/

if (($section == "admin") && ($go == "default")) {

	$db_conn->where ("id", 1);
	$row_prefs = $db_conn->getOne ($prefix."preferences");
	$totalRows_prefs = $db_conn->count;

	/*
	$query_prefs = sprintf("SELECT * FROM %s WHERE id='1'", $prefix."preferences");
	$prefs = mysqli_query($connection,$query_prefs) or die (mysqli_error($connection));
	$row_prefs = mysqli_fetch_assoc($prefs);
	$totalRows_prefs = mysqli_num_rows($prefs);
	*/

}

$prefs_barcode_labels = array("N","C","2","0","3","4");


/*
if ($section == "volunteers") {
	$query_contest_info = sprintf("SELECT contestVolunteers FROM %s", $prefix."contest_info");
	$query_contest_info .= " WHERE id='1'";
	$contest_info = mysqli_query($connection,$query_contest_info) or die (mysqli_error($connection));
	$row_contest_info = mysqli_fetch_assoc($contest_info);
}
*/

?>