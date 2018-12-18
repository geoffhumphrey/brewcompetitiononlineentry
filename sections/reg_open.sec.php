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

$message1 = "";
$header1_1 = "";
$page_info1 = "";
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

if (($registration_open == 1) && ($judge_window_open == 1) && (!isset($_SESSION['loginUsername']))) {

	if (($judge_limit) && (!$steward_limit)) {
		$header1_1 .= sprintf("<h2>%s <span class='text-success'>%s</span></h2>",$reg_open_text_016,$reg_open_text_001);
		$page_info1 .= sprintf("<p><strong class=\"text-danger\">%s</strong></p>",$alert_text_072);
		$page_info1 .= sprintf("<p>%s %s.</p>",$reg_open_text_002,$reg_open_text_003);
	}

	if ((!$judge_limit) && ($steward_limit)) {
		$header1_1 .= sprintf("<h2>%s <span class='text-success'>%s</span></h2>",$reg_open_text_015,$reg_open_text_001);
		$page_info1 .= sprintf("<p><strong class=\"text-danger\">%s</strong></p>",$alert_text_076);
		$page_info1 .= sprintf("<p>%s %s.</p>",$reg_open_text_002,$reg_open_text_003);
	}

	if ((!$judge_limit) && (!$steward_limit)) {
		$header1_1 .= sprintf("<h2>%s <span class='text-success'>%s</span></h2>",$reg_open_text_000,$reg_open_text_001) ;
		$page_info1 .= sprintf("<p>%s %s.</p>",$reg_open_text_002,$reg_open_text_003);
	}

	$page_info1 .= sprintf("<p>%s <span class=\"fa fa-lg fa-user\"></span> %s</p>",$reg_open_text_004,$reg_open_text_005);
}

if (($registration_open == 1) && (!isset($_SESSION['brewerBreweryName'])) && (isset($_SESSION['loginUsername']))) $page_info1 .= sprintf("<p>%s <a href=\"%s\">%s</a> %s</p>",$reg_open_text_006,build_public_url("list","default","default","default",$sef,$base_url),$reg_open_text_007,$reg_open_text_008);

if ($registration_open != 1) $page_info1 .= sprintf("<p>%s %s.</p>",$reg_open_text_009,$judge_open);


if (($entry_window_open == 1) && ($show_entries)) {
	$header1_2 .= sprintf("<h2>%s <span class='text-success'>%s</a></h2>",$reg_open_text_010,$reg_open_text_001);

	if ($_SESSION['prefsProEdition'] == 0)  {
		$page_info2 .= "<p>";
		$page_info2 .= sprintf("<strong class=\"text-success\">%s %s</strong> %s %s, %s.", $total_entries, strtolower($label_entries), $sidebar_text_025, $current_time, $current_date_display);
		$page_info2 .= "</p>";
	}


	$page_info2 .= "<p>";
	$page_info2 .= sprintf("%s, ",$reg_open_text_011);
	if (!isset($_SESSION['loginUsername'])) $page_info2 .= sprintf("%s or %s %s",$reg_open_text_012,strtolower($label_log_in),$reg_open_text_013);
	else $page_info2 .= sprintf("<a href=\"%s\">%s</a>.",build_public_url("brew","entry","add","default",$sef,$base_url),$reg_open_text_014);
	$page_info2 .= "</p>";

}

$header1_3 .= sprintf("<a name='rules'></a><h2>%s</h2>",$label_rules);
$page_info3 .= $row_contest_rules['contestRules'];

// --------------------------------------------------------------
// Display
// --------------------------------------------------------------


echo $header1_2;
echo $page_info2;
echo $header1_1;
echo $page_info1;

echo $header1_3;
echo $page_info3;
echo $header1_4;
echo $page_info4;
echo $header1_5;
echo $page_info5;
echo $header1_6;
echo $page_info6;
echo $header1_6;
echo $page_info7;
echo $header1_8;
echo $page_info8;



?>