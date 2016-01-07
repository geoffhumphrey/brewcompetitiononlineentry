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


include(DB.'judging_locations.db.php'); 
include(DB.'entries.db.php');

$primary_page_info = "";
$header1_1 = "";
$page_info1 = "";
$header1_2 = "";
$page_info2 = "";

// Header
$header1_1 .= sprintf("<h2>Thanks and Good Luck To All Who Entered the %s!</h2>",$_SESSION['contestName']);
if (NHC) $page_info1 .= sprintf("<p>There are <strong>%s</strong> registered participants, judges, and stewards.</p>",get_participant_count('default'));
else {
	$page_info1 .= sprintf("<p>There are <strong class=\"text-success\">%s</strong> registered entries and <strong class=\"text-success\">%s</strong> registered participants, judges, and stewards.</p>",get_entry_count('none'),get_participant_count('default'));
	$page_info1 .= sprintf("<p>As of %s, there are <strong class=\"text-success\">%s</strong> received and processed entries (this number will update as entries are picked up from drop-off locations and organized for judging).</p>",$current_time, get_entry_count('received'));
}



if ($totalRows_judging > 1) $header1_2 .= "<h2>Judging Locations/Dates</h2>";
else $header1_2 .= "<h2>Judging Location/Date</h2>";
if ($totalRows_judging == 0) $page_info2 .= "<p>Competition judging dates are yet to be determined. Please check back later.</p>";
else {
	do {
		$page_info2 .= "<p>";
		if ($row_judging['judgingLocName'] != "") $page_info2 .= "<strong>".$row_judging['judgingLocName']."</strong>";
		if ($row_judging['judgingLocation'] != "") $page_info2 .= "<br><a href=\"".$base_url."output/maps.output.php?section=driving&amp;id=".str_replace(' ', '+', $row_judging['judgingLocation'])."\" target=\"_blank\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Map to ".$row_judging['judgingLocName']."\">".$row_judging['judgingLocation']."</a> <span class=\"fa fa-map-marker\"></span>";
		else $page_info2 .= $row_judging['judgingLocName'];
		if ($row_judging['judgingDate'] != "") $page_info2 .=  "<br />".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");
		$page_info2 .= "</p>";
	} while ($row_judging = mysql_fetch_assoc($judging));
}

echo $header1_1;
echo $page_info1;
echo $header1_2;
echo $page_info2;

?>
