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
  
	$primary_page_info_vol = any information related to the page
	
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
	$primary_page_info_vol = "";
	$header_vol_1_1 = "";
	$page_info_vol_1 = "";
	$header_vol_1_2 = "";
	$page_info_vol_2 = "";
	
	etc., etc., etc.

 * ---------------- END Rebuild Info --------------------- */

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
$header_vol_1_2 .= sprintf("<h2%s</h2>",$label_staff);
$page_info_vol_2 .= sprintf("<p>%s",$volunteers_text_009, build_public_url("contact","default","default","default",$sef,$base_url), $volunteers_text_010);
}

if (!empty($row_contest_info['contestVolunteers'])) {
	$header_vol_1_3 .= sprintf("<h2>%s %s</h2>",$label_other,$label_volunteer_info);
	$page_info_vol_3 .= $row_contest_info['contestVolunteers'];
}

// --------------------------------------------------------------
// Display
// --------------------------------------------------------------
if (($action != "print") && ($msg != "default")) echo $msg_output;
//if ((($_SESSION['contestLogo'] != "") && (file_exists($_SERVER['DOCUMENT_ROOT'].$sub_directory.'/user_images/'.$_SESSION['contestLogo']))) && ((judging_date_return() > 0) || (NHC))) echo $competition_logo;
//if ($action != "print") echo $print_page_link;

echo $header_vol_1_1;
echo $page_info_vol_1;
echo $header_vol_1_2;
echo $page_info_vol_2;
echo $header_vol_1_3;
echo $page_info_vol_3;
?>

