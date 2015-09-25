<?php 
/**
 * Module:      reg_open.sec.php
 * Description: This module houses information regarding registering for the competition,
 *              judging dates, etc. Shown while the registration window is open.
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
	
	$print_page_link = "<p><span class='icon'><img src='".$base_url."images/printer.png' border='0' alt='Print' title='Print' /></span><a id='modal_window_link' class='data' href='".$base_url."output/print.php?section=".$section."&amp;action=print' title='Print'>Print This Page</a></p>";
	$competition_logo = "<img src='".$base_url."user_images/".$_SESSION['contestLogo']."' width='".$_SESSION['prefsCompLogoSize']."' style='float:right; padding: 5px 0 5px 5px' alt='Competition Logo' title='Competition Logo' />";
	
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
$message1 = "";
$header1_2 = ""; 
$page_info2 = "";
$header1_3 = ""; 
$page_info3 = "";
$header1_4 = ""; 
$page_info4 = "";
$header1_5 = ""; 
$page_info5 = "";
$header1_6 = ""; 
$page_info6 = "";
$header1_7 = ""; 
$page_info7 = "";
$header1_8 = ""; 
$page_info8 = "";

$header1_2 .= "<h2>Online Registration Window</h2>";
$page_info2 .= sprintf("<p>Online registration on this site will take place between <strong>%s</strong> and <strong>%s</strong>.</p>", $reg_open, $reg_closed);
$page_info2 .= sprintf("<p><em>** Please note: The Registration Window open date is for registration of your personal information only. You will not be able to add your entries into the system until the Entry Window opens on %s.</em></p>",$entry_open);
if (($registration_open == "1") && (!isset($_SESSION['loginUsername']))) $page_info2 .= " <p>If you have already registered, please <a href='".build_public_url("login","default","default",$sef,$base_url)."'>log in</a> to add, view, edit, or delete your entries as well as indicate that you are willing to judge or steward.</p>";
$page_info2 .= "<h2>Online Entry Window</h2>";
$page_info2 .= sprintf("<p>You will be able to add your entries into the system between <strong>%s</strong> and <strong>%s</strong>.</p>", $entry_open, $entry_closed);
$header1_3 .= "<h2>Judging and Stewarding</h2>"; 

if (($registration_open == "1") && (!isset($_SESSION['loginUsername']))) { 
	$page_info3 .= "<p>If you <em>have not</em> registered and are willing to be a judge or steward, <a href='".build_public_url("register","judge","default",$sef,$base_url)."'>please register</a>.</p>";
	$page_info3 .= "<p>If you <em>have</em> registered, <a href='".build_public_url("login","default","default",$sef,$base_url)."'>log in</a> and then choose <em>Edit My Info</em> to indicate that you are willing to judge or  steward.</p>";
}

elseif (($registration_open == "1") && (isset($_SESSION['loginUsername']))) { 
	$page_info3 .= "<p>Since you have already registered, you can <a href='".build_public_url("list","default","default",$sef,$base_url)."'>check your info</a> to see whether you have indicated that you are willing to judge and/or steward.</p>";
	$page_info3 .= "";
}
else $page_info3 .= sprintf("<p>If you are willing to judge or steward, please return to register on or after %s.</p>",$judge_open);

$header1_4 .= "<h2>Entry Drop-Off and Shipping</h2>"; 
$page_info4 .= sprintf("<p>Drop-off and shipping locations will begin accepting entries on <strong>%s</strong>. </p>",$entry_open);
$page_info4 .= "<p>";
$page_info4 .= "";
$page_info4 .= sprintf("<p>All entries must be received at our shipping location or at a drop-off location by <strong>%s</strong> and will not be accepted after that date/time. For details, see the <a href='%s'>Entry Information</a> page.</p></p>",$entry_closed, build_public_url("entry","default","default",$sef,$base_url));

if ($row_limits['prefsEntryLimit'] != "") {
	$header1_5 .= "<h2>Entry Limit</h2>";
	$page_info5 .= sprintf("<p>There is a limit of %s entries for this competition.</p>",readable_number($row_limits['prefsEntryLimit'])." (".$row_limits['prefsEntryLimit'].")");
	
}

if ((!empty($row_limits['prefsUserEntryLimit'])) || (!empty($row_limits['prefsUserSubCatLimit'])) || (!empty($row_limits['prefsUSCLExLimit']))) {
	$header1_8 .= "<h2>Per Entrant Limits</h2>";
	
	if (!empty($row_limits['prefsUserEntryLimit'])) {
		if ($row_limits['prefsUserEntryLimit'] == 1) $page_info8 .= sprintf("<p>You are limited to %s entry for this competition.</p>",readable_number($row_limits['prefsUserEntryLimit'])." (".$row_limits['prefsUserEntryLimit'].")");
		else $page_info8 .= sprintf("<p>You are limited to %s entries for this competition.</p>",readable_number($row_limits['prefsUserEntryLimit'])." (".$row_limits['prefsUserEntryLimit'].")");
	}
	
	if (!empty($row_limits['prefsUserSubCatLimit'])) { 
		$page_info8 .= "<p>";
		if ($row_limits['prefsUserSubCatLimit'] == 1) $page_info8 .= sprintf("You are limited to %s entry per sub-category ",readable_number($row_limits['prefsUserSubCatLimit'])." (".$row_limits['prefsUserSubCatLimit'].")");
		else $page_info8 .= sprintf("You are limited to %s entries per sub-category ",readable_number($row_limits['prefsUserSubCatLimit'])." (".$row_limits['prefsUserSubCatLimit'].")");
		if (!empty($row_limits['prefsUSCLExLimit'])) $page_info8 .= " (exceptions are detailed below)";
		$page_info8 .= ".";
		$page_info8 .= "</p>";

	}
	
	if (!empty($row_limits['prefsUSCLExLimit'])) { 
	$excepted_styles = explode(",",$row_limits['prefsUSCLEx']);
	if (count($excepted_styles) == 1) $sub = "sub-category"; else $sub = "sub-categories";
		if ($row_limits['prefsUSCLExLimit'] == 1) $page_info8 .= sprintf("<p>You are limited to %s entry for the following %s: </p>",readable_number($row_limits['prefsUSCLExLimit'])." (".$row_limits['prefsUSCLExLimit'].")",$sub);
		else $page_info8 .= sprintf("<p>You are limited to %s entries for for the following %s: </p>",readable_number($row_limits['prefsUSCLExLimit'])." (".$row_limits['prefsUSCLExLimit'].")",$sub);
		$page_info8 .= style_convert($row_limits['prefsUSCLEx'],"7");

	}
	
}

if ($entry_window_open == 1) {
	$header1_6 .= "<h2>Add Your Entries</h2>"; 
	$page_info6 .= "<p>";
	$page_info6 .= "To add your entries into the system, ";
	if (!isset($_SESSION['loginUsername'])) $page_info6 .= "please proceed through the <a href='".build_public_url("register","default","default",$sef,$base_url)."'>registration process</a>.";
	else $page_info6 .= "use the <a href='".build_public_url("brew","entry","add",$sef,$base_url)."'>add an entry form</a>.";
	$page_info6 .= "</p>";
}

if ($totalRows_judging > 1) $header1_7 .= "<h2>Judging Locations and Dates</h2>";
else $header1_7 .= "<h2>Judging Location and Date</h2>";
if ($totalRows_judging == 0) $page_info7 .= "<p>The competition judging date is yet to be determined. Please check back later.</p>";
else {
	do {
		$page_info7 .= "<p>";
		$page_info7 .= "<strong>".$row_judging['judgingLocName']."</strong>";
		if ($row_judging['judgingLocation'] != "") $page_info7 .= "<br />".$row_judging['judgingLocation']; 
		if ((!empty($row_judging['judgingLocation'])) && ($action != "print"))  {
			$page_info7 .= "&nbsp;<span class='icon'><a id='modal_window_link' href='".$base_url."output/maps.php?section=map&amp;id=".str_replace(' ', '+', $row_judging['judgingLocation'])."' title='Map to ".$row_judging['judgingLocName']."'><img src='".$base_url."images/map.png'  border='0' alt='Map ".$row_judging['judgingLocName']."' title='Map ".$row_judging['judgingLocName']."' /></a></span>";
			$page_info7 .= "<span class='icon'><a href='".$base_url."output/maps.php?section=driving&amp;id=".str_replace(' ', '+', $row_judging['judgingLocation'])."' target='_blank' title='Map to ".$row_judging['judgingLocName']."'><img src='".$base_url."images/car.png'  border='0' alt='Map ".$row_judging['judgingLocName']."' title='Driving Directions to ".$row_judging['judgingLocName']."' /></a></span>";

		}
		if ($row_judging['judgingDate'] != "") $page_info7 .=  "<br />".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");
		$page_info7 .= "</p>";
	} while ($row_judging = mysql_fetch_assoc($judging));
	
}


// --------------------------------------------------------------
// Display
// --------------------------------------------------------------

echo $header1_2;
echo $page_info2;
echo $header1_4;
echo $page_info4;
echo $header1_5;
echo $page_info5;

echo $header1_8;
echo $page_info8;
echo $header1_6;
echo $page_info6;
echo $header1_3;
echo $page_info3;
echo $header1_7;
echo $page_info7;

?>