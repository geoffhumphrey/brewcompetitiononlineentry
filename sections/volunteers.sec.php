<?php 
/**
 * Module:      volunteers.sec.php 
 * Description: This module displays the public-facing competition volunteers
 *              specified in the contest_info database table. 
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


 
$primary_page_info = "";
$header1_1 = "";
$page_info1 = "";
$header1_2 = "";
$page_info2 = "";

$print_page_link = "<p><span class='icon'><img src='".$base_url."images/printer.png' border='0' alt='Print' title='Print' /></span><a id='modal_window_link' class='data' href='".$base_url."output/print.php?section=".$section."&amp;action=print' title='Print'>Print This Page</a></p>";
$competition_logo = "<img src='".$base_url."user_images/".$_SESSION['contestLogo']."' width='".$_SESSION['prefsCompLogoSize']."' style='float:right; padding: 5px 0 5px 5px' alt='Competition Logo' title='Competition Logo' />";

$header1_1 .= "<h2>Judging and Stewarding</h2>";
if (($judge_window_open > 0) && (!isset($_SESSION['loginUsername']))) { 
	$page_info1 .= sprintf("<p>If you <em>have</em> registered, <a href='%s'>log in</a> and then choose <em>Edit My Info</em> to indicate that you are willing to judge or  steward.</p>",build_public_url("login","default","default",$sef,$base_url));
	$page_info1 .= sprintf("<p>If you <em>have not</em> registered and are willing to be a judge or steward, <a href='%s'>please register</a>.</p>",build_public_url("register","judge","default",$sef,$base_url));
}

elseif (($judge_window_open > 0) && (isset($_SESSION['loginUsername']))) {
	$page_info1 .= sprintf("<p>Since you have already registered, you can <a href='%s'>check your info</a> to see whether you have indicated that you are willing to judge and/or steward.</p>",build_public_url("list","default","default",$sef,$base_url));
}

else {
	$page_info1 .= sprintf("<p>If you are willing to judge or steward, please return to register on or after %s.</p>",getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_contest_dates['contestJudgeOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time"));
}

if (!empty($row_contest_info['contestVolunteers'])) {
	$header1_2 .= "<h2>Other Volunteer Info</h2>";
	$page_info2 .= $row_contest_info['contestVolunteers'];
}

// --------------------------------------------------------------
// Display
// --------------------------------------------------------------
if (($action != "print") && ($msg != "default")) echo $msg_output;
if ((($_SESSION['contestLogo'] != "") && (file_exists($_SERVER['DOCUMENT_ROOT'].$sub_directory.'/user_images/'.$_SESSION['contestLogo']))) && ((judging_date_return() > 0) || (NHC))) echo $competition_logo;
if ($action != "print") echo $print_page_link;

echo $header1_1;
echo $page_info1;
echo $header1_2;
echo $page_info2;

?>

