<?php 
/**
 * Module:      volunteers.sec.php 
 * Description: This module displays the public-facing competition volunteers
 *              specified in the contest_info database table. 
 * 
 */

/*
// Redirect if directly accessed
if ((!isset($_SESSION['prefs'.$prefix_session])) || ((isset($_SESSION['prefs'.$prefix_session])) && (!isset($base_url)))) {
    $redirect = "../../index.php?section=volunteers";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}
*/

include (DB.'judging_locations.db.php');
$primary_page_info_vol = "";
$header_vol_1_1 = "";
$page_info_vol_1 = "";
$header_vol_1_2 = "";
$page_info_vol_2 = "";
$header_vol_1_3 = "";
$page_info_vol_3 = "";

$header_vol_1_1 .= sprintf("<h2>%s %s %s</h2>",$label_judges, $volunteers_text_003, $label_stewards);
if (($judge_window_open > 0) && (!$logged_in)) { 
	$page_info_vol_1 .= sprintf("<p>%s %s %s <span class=\"fa fa-user\"></span> %s.</p>",$volunteers_text_000, strtolower($label_log_in), $volunteers_text_001, $volunteers_text_002);
	if ($registration_open < 2) $page_info_vol_1 .= sprintf("<p>%s.</p>",$volunteers_text_004);
}

elseif (($judge_window_open > 0) && ($logged_in)) {
	$page_info_vol_1 .= sprintf("<p>%s <a href=\"%s\">%s</a> %s.</p>",$volunteers_text_005, build_public_url("list","default","default","default",$sef,$base_url), $volunteers_text_006, $volunteers_text_007);
}

else {
	$page_info_vol_1 .= sprintf("<p>%s %s.</p>",$volunteers_text_008, getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestJudgeOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time"));
}

if ($registration_open < 2) {
	$staff_locations = "";
	$header_vol_1_2 .= sprintf("<h2>%s</h2>",$label_staff);
	$page_info_vol_2 .= sprintf("<p>%s",$volunteers_text_009, build_public_url("contact","default","default","default",$sef,$base_url), $volunteers_text_010);
	
	if ($row_judging1) {
		do {
			if ($row_judging1['judgingLocType'] == 2) $staff_locations .= sprintf("<li>%s &ndash; %s</li>",$row_judging1['judgingLocName'],getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging1['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time"));
		} while($row_judging1 = mysqli_fetch_assoc($judging1));
	}
		

	if (!empty($staff_locations)) {
		$page_info_vol_2 .= sprintf("<p>%s</p>",$volunteers_text_010);
		$page_info_vol_2 .= "<ul>";
		$page_info_vol_2 .= $staff_locations;
		$page_info_vol_2 .= "</ul>";
	}

}

if (!empty($row_contest_info['contestVolunteers'])) {
	$header_vol_1_3 .= sprintf("<h2>%s %s</h2>",$label_other,$label_volunteer_info);

	if ((ENABLE_MARKDOWN) && (!is_html($row_contest_info['contestVolunteers']))) { 
		$page_info_vol_3 .= Parsedown::instance()
					   ->setBreaksEnabled(true) # enables automatic line breaks
					   ->setMarkupEscaped(true) # escapes markup (HTML)
					   ->text($row_contest_info['contestVolunteers']); 
	}
	else $page_info_vol_3 .= $row_contest_info['contestVolunteers'];
}

// --------------------------------------------------------------
// Display
// --------------------------------------------------------------
// if (($action != "print") && ($msg != "default")) echo $msg_output;

echo $header_vol_1_1;
echo $page_info_vol_1;
echo $header_vol_1_2;
echo $page_info_vol_2;
echo $header_vol_1_3;
echo $page_info_vol_3;
?>

