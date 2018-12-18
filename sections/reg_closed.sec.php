<?php
/**
 * Module:      reg_closed.sec.php
 * Description: This module houses information that will be displayed
 *              after registration has closed but before judging ends.
 *
 */

/* ---------------- PUBLIC Pages Rebuild Info ---------------------

Beginning with the 1.3.0 release, an effort was begun to separate the programming
layer from the presentation layer for all scripts with this header.

All Public pages have certain variables in common that build the page:

	$warningX = any warnings

	$primary_page_info = any information related to the page

	$header1_X = an <h2> header on the page
	$header2_X = an <h3> subheader on the page

	$page_infoX = the bulk of the information on the page.
	$help_page_link = link to the appropriate page on help.brewcompetition.com
	$print_page_link = the "Print This Page" link
	$competition_logo = display of the competition's logo

	$labelX = the various labels in a table or on a form
	$messageX = various messages to display

	$print_page_link = "<p><span class=\"icon\"><img src=\"".$base_url."images/printer.png\" border=\"0\" alt=\"Print\" title=\"Print\" /></span><a id=\"modal_window_link\" class=\"data\" href=\"".$base_url."output/print.php?section=".$section."&amp;action=print\" title=\"Print\">Print This Page</a></p>";
	$competition_logo = "<img src=\"".$base_url."user_images/".$_SESSION['contestLogo']."\" width=\"".$_SESSION['prefsCompLogoSize']."\" style=\"float:right; padding: 5px 0 5px 5px\" alt=\"Competition Logo\" title=\"Competition Logo\" />";

Declare all variables empty at the top of the script. Add on later...
	$warning1 = "";
	$primary_page_info = "";
	$header1_1 = "";
	$page_info1 = "";
	$header1_2 = "";
	$page_info2 = "";

	etc., etc., etc.

 * ---------------- END Rebuild Info --------------------- */


include (DB.'judging_locations.db.php');
include (DB.'entries.db.php');

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

$header1_3 .= sprintf("<a name='rules'></a><h2>%s</h2>",$label_rules);
$page_info3 .= $row_contest_rules['contestRules'];

$header1_2 .= sprintf("<h2>%s</h2>",$label_admin_judging_loc);
if ($totalRows_judging == 0) $page_info2 .= sprintf("<p>%s</p>",$reg_closed_text_007);
else {
	do {
		$page_info2 .= "<p>";
		if ($row_judging['judgingLocName'] != "") $page_info2 .= "<strong>".$row_judging['judgingLocName']."</strong>";

		if ($logged_in) {
			$location_link = $base_url."output/maps.output.php?section=driving&amp;id=".str_replace(' ', '+', $row_judging['judgingLocation']);
			$location_tooltip = "Map to ".$row_judging['judgingLocName'];
		}

		else {
			$location_link = "#";
			$location_tooltip = "Log in to view the ".$row_judging['judgingLocName']." location";
		}

		if ($row_judging['judgingLocation'] != "") $page_info2 .= "<br>".$row_judging['judgingLocation']." <a href=\"".$location_link."\" target=\"".$location_target."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$location_tooltip."\"><span class=\"fa fa-lg fa-map-marker\"></span></a>";

		if ($row_judging['judgingDate'] != "") $page_info2 .=  "<br />".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");

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
