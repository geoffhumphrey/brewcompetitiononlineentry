<?php
/**
 * Module:      reg_closed.sec.php
 * Description: This module houses information that will be displayed
 *              after registration has closed but before judging ends.
 *
 */

// Redirect if directly accessed
if ((!isset($_SESSION['prefs'.$prefix_session])) || ((isset($_SESSION['prefs'.$prefix_session])) && (!isset($base_url)))) {
    $redirect = "../../index.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

include_once (DB.'judging_locations.db.php');
include_once (DB.'entries.db.php');

$primary_page_info = "";
$header1_1 = "";
$header1_3 = "";
$page_info1 = "";
$header1_2 = "";
$page_info2 = "";
$page_info3 = "";


// Header
$header1_1 .= sprintf("<h2>%s %s!</h2>",$reg_closed_text_000,$_SESSION['contestName']);
if (NHC) $page_info1 .= sprintf("<p>%s <strong>%s</strong> %s</p>",$reg_closed_text_001,get_participant_count('default'),$reg_closed_text_002);
elseif ($_SESSION['prefsProEdition'] == 0) {
	$page_info1 .= sprintf("<p>%s <strong class=\"text-success\">%s</strong> %s <strong class=\"text-success\">%s</strong> %s</p>",$reg_closed_text_001,get_entry_count('none'),$reg_closed_text_003,get_participant_count('default'),$reg_closed_text_004);
	$page_info1 .= sprintf("<p>%s %s, %s <strong class=\"text-success\">%s</strong> %s</p>",$reg_closed_text_005, $current_time, strtolower($reg_closed_text_001), get_entry_count('received'), $reg_closed_text_006);
}
else {
	$page_info1 .= sprintf("<p>%s <strong class=\"text-success\">%s</strong> %s</p>",$reg_closed_text_001,get_participant_count('default'),$reg_closed_text_004);
}

if ((isset($row_contest_rules['contestRules'])) && (!empty($row_contest_rules['contestRules']))) {
	$header1_3 .= sprintf("<a name='rules'></a><h2>%s</h2>",$label_rules);
	$contestRulesJSON = json_decode($row_contest_rules['contestRules'],true);
	if ((ENABLE_MARKDOWN) && (!is_html($contestRulesJSON['competition_rules']))) {
		$page_info3 .= Parsedown::instance()
						->setBreaksEnabled(true) # enables automatic line breaks
						->text($contestRulesJSON['competition_rules']); 
	}
	else $page_info3 .= $contestRulesJSON['competition_rules'];

}


$header1_2 .= sprintf("<h2>%s</h2>",$label_admin_judging_loc);
if ($totalRows_judging == 0) $page_info2 .= sprintf("<p>%s</p>",$reg_closed_text_007);

else {

	do {
		$page_info2 .= "<p>";

		if ($row_judging['judgingLocName'] != "") $page_info2 .= "<strong>".$row_judging['judgingLocName']."</strong>";

		if ($row_judging['judgingLocType'] == "0") {

			if ($logged_in) {
				$address = rtrim($row_judging['judgingLocation'],"&amp;KeepThis=true");
				$address = str_replace(' ', '+', $address);
				$location_link = "http://maps.google.com/maps?f=q&source=s_q&hl=en&q=".$address;
				$location_tooltip = "Map to ".$row_judging['judgingLocName'];
				$page_info2 .= "<br>".$row_judging['judgingLocation'];
			}

			else {
				$location_link = "#";
				$location_tooltip = "Log in to view the ".$row_judging['judgingLocName']." location";
			}

			if ($row_judging['judgingLocation'] != "") $page_info2 .= " <a href=\"".$location_link."\" target=\"".$location_target."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$location_tooltip."\"><span class=\"fa fa-lg fa-map-marker\"></span></a>";

		}

		if ($row_judging['judgingDate'] != "") $page_info2 .=  "<br />".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");

		if ($row_judging['judgingDateEnd'] != "") $page_info2 .=  " ".$sidebar_text_004." ".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDateEnd'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");

		$page_info2 .= "</p>";

	} while ($row_judging = mysqli_fetch_assoc($judging));

}

echo $header1_1;
echo $page_info1;
echo $header1_3;
echo $page_info3;
echo $header1_2;
echo $page_info2;
?>
