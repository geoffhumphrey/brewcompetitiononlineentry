<?php
/**
 * Module:      default.sec.php
 * Description: This module houses the intallation's landing page that includes
 *              information about the competition, registration dates/info, and
 *              winner display after all judging dates have passed.
 */

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
	$page_info100 .= "<span class=\"pull-right\"><a href=\"".$base_url."index.php?section=admin&amp;go=entries\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View all entries\"><span id=\"admin-dashboard-entries-count\">".$totalRows_log_confirmed."</span></a>";
	if (!empty($row_limits['prefsEntryLimit'])) {
		$page_info100 .= " / ";
		if ($_SESSION['userLevel'] == 0) $page_info100 .= "<a href=\"".$base_url."index.php?section=admin&amp;go=preferences\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Change the limit of total entries\">".$row_limits['prefsEntryLimit']."</a>";
		else $page_info100 .= $row_limits['prefsEntryLimit'];

	}
	$page_info100 .= "</span>";
	$page_info100 .= "</div>";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Unconfirmed Entries</strong>";
	$page_info100 .= "<span class=\"pull-right\" id=\"admin-dashboard-entries-unconfirmed-count\">".$entries_unconfirmed."</span>";
	$page_info100 .= "</div>";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Paid Entries</strong>";
	$page_info100 .= "<span class=\"pull-right\"><span id=\"admin-dashboard-entries-paid-count\">".get_entry_count("paid")."</span>";
	if (!empty($row_limits['prefsEntryLimitPaid'])) {
		$page_info100 .= " / ";
		if ($_SESSION['userLevel'] == 0) $page_info100 .= "<a href=\"".$base_url."index.php?section=admin&amp;go=preferences\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Change the limit of paid entries\">".$row_limits['prefsEntryLimitPaid']."</a>";
		else $page_info100 .= $row_limits['prefsEntryLimitPaid'];
	}
	$page_info100 .= "</span>";
	$page_info100 .= "</div>";


	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Paid/Rec'd Entries</strong>";
	$page_info100 .= "<span class=\"pull-right\" id=\"admin-dashboard-entries-paid-received-count\">".get_entry_count("paid-received")."</span>";
	$page_info100 .= "</div>";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Entry Counts</strong>";
	$page_info100 .= "<span class=\"pull-right\"><a href=\"".$base_url."index.php?section=admin&amp;go=count_by_style\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View the entry counts broken down by style\"><small>Style</small></a> / <a href=\"".$base_url."index.php?section=admin&amp;go=count_by_substyle\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View the entry counts broken down by sub-style\"><small>Sub-Style</small></a></span>";
	$page_info100 .= "</div>";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Total Fees</strong>";
	$page_info100 .= "<span class=\"pull-right\">".$currency_symbol."<span id=\"admin-dashboard-total-fees\">".number_format($total_fees,2)."</span></span>";
	$page_info100 .= "</div>";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Total Fees Paid</strong>";
	$page_info100 .= "<span class=\"pull-right\"><a href=\"".$base_url."index.php?section=admin&amp;go=entries&amp;view=paid\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View all paid entries\">".$currency_symbol."<span id=\"admin-dashboard-total-fees-paid\">".number_format($total_fees_paid,2)."</span></a></span>";
	$page_info100 .= "</div>";

	if ($_SESSION['prefsEval'] == 1) {

		$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
		$page_info100 .= "<strong class=\"text-info\">Evaluations</strong>";
		$page_info100 .= "<span class=\"pull-right\"><a href=\"".$base_url."index.php?section=evaluation&amp;go=default&amp;filter=default&amp;view=admin\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Total evaluations\"><span id=\"admin-dashboard-evaluation-count-total\">".get_evaluation_count('total')."</span></a> / <a href=\"".$base_url."index.php?section=evaluation&amp;go=default&amp;filter=default&amp;view=admin\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Entries with evaluations\"><span id=\"admin-dashboard-evaluation-count\">".get_evaluation_count('unique')."</span></a></span>";
		$page_info100 .= "</div>";

	}

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Participants</strong>";
	$page_info100 .= "<span class=\"pull-right\"><a href=\"".$base_url."index.php?section=admin&amp;go=participants\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View all participants\"><span id=\"admin-dashboard-participant-count\">".get_participant_count('default')."</span></a></span>";
	$page_info100 .= "</div>";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Participants with Entries</strong>";
	$page_info100 .= "<span class=\"pull-right\" id=\"admin-dashboard-participant-entries\">".$row_with_entries['count']."</span>";
	$page_info100 .= "</div>";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Available Judges</strong>";
	$page_info100 .= "<span class=\"pull-right\"><a href=\"".$base_url."index.php?section=admin&amp;go=participants&amp;filter=judges\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View available judges\"><span id=\"admin-dashboard-avail-judges-count\">".get_participant_count('judge')."</span></a>";
	if (!empty($row_judge_limits['jprefsCapJudges'])) {
		$page_info100 .= " / ";
		if ($_SESSION['userLevel'] == 0) $page_info100 .= "<a href=\"".$base_url."index.php?section=admin&amp;go=judging_preferences\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Change the limit of judges\">".$row_judge_limits['jprefsCapJudges']."</a>";
		else $page_info100 .= $row_judge_limits['jprefsCapJudges'];
	}
	$page_info100 .= "</span>";
	$page_info100 .= "</div>";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Assigned Judges</strong>";
	$page_info100 .= "<span class=\"pull-right\"><a href=\"".$base_url."index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View assigned judges\"><span id=\"admin-dashboard-assigned-judges-count\">".get_participant_count('judge-assigned')."</span></a>";
	$page_info100 .= "</span>";
	$page_info100 .= "</div>";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Available Stewards</strong>";
	$page_info100 .= "<span class=\"pull-right\"><a href=\"".$base_url."index.php?section=admin&amp;go=participants&amp;filter=stewards\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View available stewards\"><span id=\"admin-dashboard-avail-stewards-count\">".get_participant_count('steward')."</span></a>";
	if (!empty($row_judge_limits['jprefsCapStewards'])) {
		$page_info100 .= " / ";
		if ($_SESSION['userLevel'] == 0) $page_info100 .= "<a href=\"".$base_url."index.php?section=admin&amp;go=judging_preferences\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Change the limit of stewards\">".$row_judge_limits['jprefsCapStewards']."</a>";
		else $page_info100 .= $row_judge_limits['jprefsCapStewards'];
	}
	$page_info100 .= "</span>";
	$page_info100 .= "</div>";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Assigned Stewards</strong>";
	$page_info100 .= "<span class=\"pull-right\"><a href=\"".$base_url."index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View assigned stewards\"><span id=\"admin-dashboard-assigned-stewards-count\">".get_participant_count('steward-assigned')."</span></a>";
	$page_info100 .= "</span>";
	$page_info100 .= "</div>";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Available Staff</strong>";
	$page_info100 .= "<span class=\"pull-right\"><a href=\"".$base_url."index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=staff&amp;view=yes\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View available staff\"><span id=\"admin-dashboard-avail-staff-count\">".get_participant_count('staff')."</span></a>";
	$page_info100 .= "</span>";
	$page_info100 .= "</div>";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
	$page_info100 .= "<strong class=\"text-info\">Assigned Staff</strong>";
	$page_info100 .= "<span class=\"pull-right\"><a href=\"".$base_url."index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=staff\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View assigned staff\"><span id=\"admin-dashboard-assigned-staff-count\">".get_participant_count('staff-assigned')."</span></a>";
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

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\" style=\"margin-top: 10px;\"><a data-toggle=\"tooltip\" data-placement=\"top\" title=\"Like the software? Buy the author a beer via PayPal!\" class=\"btn btn-small btn-default btn-block\" href=\"https://www.brewingcompetitions.com/donation\" target=\"_blank\">Donate <span class=\"fa fa-lg fa-cc-paypal\"></span></a></div>";

	$page_info100 .= "<div class=\"bcoem-sidebar-panel\" style=\"margin-top: 10px; margin-bottom: 0px;\">";
	$page_info100 .= "<em><span class=\"text-muted\" style=\"font-size:.75em;\">".$server_environ_sidebar."</span></em>";
	$page_info100 .= "</div>";

	$page_info100 .= "</div>";
	$page_info100 .= "</div>";
}

// --------------------------------------------------------------
// Display
// --------------------------------------------------------------

echo $header1_100;
echo $page_info100;
?>
