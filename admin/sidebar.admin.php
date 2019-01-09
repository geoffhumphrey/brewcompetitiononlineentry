<?php
/**
 * Module:      default.sec.php
 * Description: This module houses the intallation's landing page that includes
 *              information about the competition, registration dates/info, and
 *              winner display after all judging dates have passed.
 */

/* ---------------- PUBLIC Pages Rebuild Info ---------------------

Beginning with the 1.3.0 release, an effort was begun to separate the programming
layer from the presentation layer for all scripts with this header.

All Public pages have certain variables in common that build the page:

	$primary_page_info = any information related to the page

	$header1_X = an <h2> header on the page
	$header2_X = an <h3> subheader on the page

	$page_infoX = the bulk of the information on the page.
	$print_page_link = the "Print This Page" link
	$competition_logo = display of the competition's logo

	$labelX = the various labels in a table or on a form
	$messageX = various messages to display

	$print_page_link = "<p><span class='icon'><img src='".$base_url."images/printer.png' border='0' alt='Print' title='Print' /></span><a id='modal_window_link' class='data' href='".$base_url."output/print.php?section=".$section."&amp;action=print' title='Print'>Print This Page</a></p>";
	$competition_logo = "<img src='".$base_url."user_images/".$_SESSION['contestLogo']."' width='".$_SESSION['prefsCompLogoSize']."' style='float:right; padding: 5px 0 5px 5px' alt='Competition Logo' title='Competition Logo' />";

Declare all variables empty at the top of the script. Add on later...
	$primary_page_info = "";
	$header1_100 = "";
	$page_info100 = "";
	$header1_200 = "";
	$page_info200 = "";

	etc., etc., etc.

 * ---------------- END Rebuild Info --------------------- */

$competition_logo = "<img src=\"".$base_url."user_images/".$_SESSION['contestLogo']."\" class=\"bcoem-comp-logo img-responsive hidden-print\" alt=\"Competition Logo\" title=\"Competition Logo\" />";
$page_info = "";
$header1_100 = "";
$page_info100 = "";
$header1_200 = "";
$page_info200 = "";
$header1_300 = "";
$page_info300 = "";
$header1_400 = "";
$page_info400 = "";
$header1_5 = "";
$page_info5 = "";
$header1_6 = "";
$page_info6 = "";
$header1_7 = "";
$page_info7 = "";
$header1_8 = "";
$page_info8 = "";


// If logged in, show the following
if ($logged_in) {

	// Conditional display of panel colors based upon open/closed dates
	if ($registration_open == 0) $reg_panel_display = "panel-default";
	elseif (($registration_open == 1) || ($judge_window_open == 1)) $reg_panel_display = "panel-success";
	elseif ($registration_open == 2) $reg_panel_display = "panel-danger";
	else $reg_panel_display = "panel-default";

	if ($entry_window_open == 0) $entry_panel_display = "panel-default";
	elseif ($entry_window_open == 1) $entry_panel_display = "panel-success";
	elseif ($entry_window_open == 2) $entry_panel_display = "panel-danger";
	else $entry_panel_display = "panel-default";

	// Competition Status Panel
	$header1_100 .= "<div class=\"panel panel-info\">";
	$header1_100 .= "<div class=\"panel-heading\">";
	$header1_100 .= "<h4 class=\"panel-title\">Competition Status<span class=\"fa fa-2x fa-bar-chart text-info pull-right\"></span></h4>";
	$header1_100 .= "<span class=\"small text-muted\">As of ".getTimeZoneDateTime($_SESSION['prefsTimeZone'], time(), $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "date-time")."</span>";
	$header1_100 .= "</div>";
	$page_info100 .= "<div class=\"panel-body\">";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Confirmed Entries</strong>";
	$page_info100 .= "<span class=\"pull-right\"><a href=\"".$base_url."index.php?section=admin&amp;go=entries\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View all entries\">".$totalRows_log_confirmed."</a>";
	if (!empty($row_limits['prefsEntryLimit'])) {
		$page_info100 .= " / ";
		if ($_SESSION['userLevel'] == 0) $page_info100 .= "<a href=\"".$base_url."index.php?section=admin&amp;go=preferences\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Change the limit of total entries\">".$row_limits['prefsEntryLimit']."</a>";
		else $page_info100 .= $row_limits['prefsEntryLimit'];

	}
	$page_info100 .= "</span>";
	$page_info100 .= "</div>";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Unconfirmed Entries</strong>";
	$page_info100 .= "<span class=\"pull-right\">".$entries_unconfirmed."</span>";
	$page_info100 .= "</div>";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Paid Entries</strong>";
	$page_info100 .= "<span class=\"pull-right\">".get_entry_count("paid");
	if (!empty($row_limits['prefsEntryLimitPaid'])) {
		$page_info100 .= " / ";
		if ($_SESSION['userLevel'] == 0) $page_info100 .= "<a href=\"".$base_url."index.php?section=admin&amp;go=preferences\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Change the limit of paid entries\">".$row_limits['prefsEntryLimitPaid']."</a>";
		else $page_info100 .= $row_limits['prefsEntryLimitPaid'];
	}
	$page_info100 .= "</span>";
	$page_info100 .= "</div>";


	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Paid/Rec'd Entries</strong>";
	$page_info100 .= "<span class=\"pull-right\">".get_entry_count("paid-received")."</span>";
	$page_info100 .= "</div>";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Entry Counts</strong>";
	$page_info100 .= "<span class=\"pull-right\"><a href=\"".$base_url."index.php?section=admin&amp;go=count_by_style\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View the entry counts broken down by style\">Style</a>&nbsp;&nbsp;<a href=\"".$base_url."index.php?section=admin&amp;go=count_by_substyle\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View the entry counts broken down by sub-style\">Sub-Style</a></span>";
	$page_info100 .= "</div>";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Total Fees</strong>";
	$page_info100 .= "<span class=\"pull-right\">".$currency_symbol.number_format($total_fees,2)."</span>";
	$page_info100 .= "</div>";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Total Fees Paid</strong>";
	$page_info100 .= "<span class=\"pull-right\"><a href=\"".$base_url."index.php?section=admin&amp;go=entries&amp;view=paid\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View all paid entries\">".$currency_symbol.number_format($total_fees_paid,2)."</a></span>";
	$page_info100 .= "</div>";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Participants</strong>";
	$page_info100 .= "<span class=\"pull-right\"><a href=\"".$base_url."index.php?section=admin&amp;go=participants\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View all participants\">".get_participant_count('default')."</a></span>";
	$page_info100 .= "</div>";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Participants with Entries</strong>";
	$page_info100 .= "<span class=\"pull-right\">".$row_with_entries['count']."</span>";
	$page_info100 .= "</div>";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Available Judges</strong>";
	$page_info100 .= "<span class=\"pull-right\"><a href=\"".$base_url."index.php?section=admin&amp;go=participants&amp;filter=judges\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View available judges\">".get_participant_count('judge')."</a>";
	if (!empty($row_judge_limits['jprefsCapJudges'])) {
		$page_info100 .= " / ";
		if ($_SESSION['userLevel'] == 0) $page_info100 .= "<a href=\"".$base_url."index.php?section=admin&amp;go=judging_preferences\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Change the limit of judges\">".$row_judge_limits['jprefsCapJudges']."</a>";
		else $page_info100 .= $row_judge_limits['jprefsCapJudges'];
	}
	$page_info100 .= "</span>";
	$page_info100 .= "</div>";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Available Stewards</strong>";
	$page_info100 .= "<span class=\"pull-right\"><a href=\"".$base_url."index.php?section=admin&amp;go=participants&amp;filter=stewards\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View available stewards\">".get_participant_count('steward')."</a>";
	if (!empty($row_judge_limits['jprefsCapStewards'])) {
		$page_info100 .= " / ";
		if ($_SESSION['userLevel'] == 0) $page_info100 .= "<a href=\"".$base_url."index.php?section=admin&amp;go=judging_preferences\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Change the limit of stewards\">".$row_judge_limits['jprefsCapStewards']."</a>";
		else $page_info100 .= $row_judge_limits['jprefsCapStewards'];
	}
	$page_info100 .= "</span>";
	$page_info100 .= "</div>";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Entry Registration</strong>";
	if ($entry_window_open == 1) $page_info100 .= "<span class=\"pull-right text-success\"><span class=\"fa fa-lg fa-check\"></span> Open</span>";
	else $page_info100 .= "<span class=\"pull-right text-danger\"><span class=\"fa fa-lg fa-times\"></span> Closed</span>";
	$page_info100 .= "</div>";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Drop-Off Window</strong>";
	if ($dropoff_window_open == 1) $page_info100 .= "<span class=\"pull-right text-success\"><span class=\"fa fa-lg fa-check\"></span> Open</span>";
	else $page_info100 .= "<span class=\"pull-right text-danger\"><span class=\"fa fa-lg fa-times\"></span> Closed</span>";
	$page_info100 .= "</div>";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Shipping Window</strong>";
	if ($shipping_window_open == 1) $page_info100 .= "<span class=\"pull-right text-success\"><span class=\"fa fa-lg fa-check\"></span> Open</span>";
	else $page_info100 .= "<span class=\"pull-right text-danger\"><span class=\"fa fa-lg fa-times\"></span> Closed</span>";
	$page_info100 .= "</div>";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Registration</strong>";
	if ($registration_open == 1) $page_info100 .= "<span class=\"pull-right text-success\"><span class=\"fa fa-lg fa-check\"></span> Open</span>";
	else $page_info100 .= "<span class=\"pull-right text-danger\"><span class=\"fa fa-lg fa-times\"></span> Closed</span>";
	$page_info100 .= "</div>";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Judge Registration</strong>";
	if ($judge_window_open == 1) $page_info100 .= "<span class=\"pull-right text-success\"><span class=\"fa fa-lg fa-check\"></span> Open</span>";
	else $page_info100 .= "<span class=\"pull-right text-danger\"><span class=\"fa fa-lg fa-times\"></span> Closed</span>";
	$page_info100 .= "</div>";

	$page_info100 .= "</div>";
	$page_info100 .= "</div>";
}

// --------------------------------------------------------------
// Display
// --------------------------------------------------------------
//if ((($_SESSION['contestLogo'] != "") && (file_exists($_SERVER['DOCUMENT_ROOT'].$sub_directory.'/user_images/'.$_SESSION['contestLogo'])))) echo $competition_logo;
//echo $page_info;

echo $header1_100;
echo $page_info100;




?>