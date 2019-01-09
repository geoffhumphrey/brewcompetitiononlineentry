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

include (DB.'judging_locations.db.php');
include (DB.'dropoff.db.php');

if ($section != "admin") {

	$competition_logo = "<img src=\"".$base_url."user_images/".$_SESSION['contestLogo']."\" class=\"bcoem-comp-logo img-responsive hidden-print center-block\" alt=\"Competition Logo\" title=\"Competition Logo\" />";
	$page_info = "";
	$header1_100 = "";
	$page_info100 = "";
	$header1_200 = "";
	$page_info200 = "";
	$header1_300 = "";
	$page_info300 = "";
	$header1_400 = "";
	$page_info400 = "";
	$header1_500 = "";
	$page_info500 = "";
	$header1_5 = "";
	$page_info5 = "";
	$header1_6 = "";
	$page_info6 = "";
	$header1_7 = "";
	$page_info7 = "";
	$header1_8 = "";
	$page_info8 = "";

	if ((isset($_SESSION['loginUsername'])) && ($_SESSION['brewerDiscount'] == "Y") && ($_SESSION['contestEntryFeePasswordNum'] != "")) $discount = TRUE;
	else $discount = FALSE;

		// Conditional display of panel colors based upon open/closed dates
		if (($registration_open == 0) && ($judge_window_open == 0)) $reg_panel_display = "panel-danger";
		elseif (($registration_open != 1) && ($judge_window_open == 1)) $reg_panel_display = "panel-success";
		elseif (($registration_open == 1) && ($judge_window_open == 1)) $reg_panel_display = "panel-success";
		elseif (($registration_open == 2) && ($judge_window_open == 2)) $reg_panel_display = "panel-danger";
		else $reg_panel_display = "panel-default";

		if ($entry_window_open == 0) $entry_panel_display = "panel-danger";
		elseif ($entry_window_open == 1) $entry_panel_display = "panel-success";
		elseif ($entry_window_open == 2) $entry_panel_display = "panel-danger";
		else $entry_panel_display = "panel-default";

		if (($comp_entry_limit) || ($comp_paid_entry_limit)) $entry_panel_display = "panel-danger";

		if ($dropoff_window_open == 0) $dropoff_panel_display = "panel-danger";
		elseif ($dropoff_window_open == 1) $dropoff_panel_display = "panel-success";
		elseif ($dropoff_window_open == 2) $dropoff_panel_display = "panel-danger";
		else $dropoff_panel_display = "panel-default";

		if ($shipping_window_open == 0) $shipping_panel_display = "panel-danger";
		elseif ($shipping_window_open == 1) $shipping_panel_display = "panel-success";
		elseif ($shipping_window_open == 2) $shipping_panel_display = "panel-danger";
		else $shipping_panel_display = "panel-default";


	if (!$logged_in) {

		// Account Registration Dates
		$header1_100 .= "<div class=\"panel ".$reg_panel_display."\">";
		$header1_100 .= "<div class=\"panel-heading\">";
		$header1_100 .= sprintf("<h4 class=\"panel-title\">%s",$label_account_registration);

		if ($registration_open == 1) $header1_100 .= sprintf(" %s",$label_open);
		elseif (($registration_open != 1) && ($judge_window_open == 1) && (!$judge_limit) && (!$steward_limit)) $header1_100 .= sprintf(" %s",$sidebar_text_006);
		elseif (($registration_open != 1) && ($judge_window_open == 1) && ($judge_limit) && (!$steward_limit)) $header1_100 .= sprintf(" %s",$sidebar_text_007);
		elseif (($registration_open != 1) && ($judge_window_open == 1) && (!$judge_limit) && ($steward_limit)) $header1_100 .= sprintf(" %s",$sidebar_text_008);
		else $header1_100 .= sprintf(" %s",$label_closed);

		$header1_100 .= "</h4>";
		$header1_100 .= "</div>";
		$page_info100 .= "<div class=\"panel-body\">";

		if ($nav_register_entrant_show) {
			if (($registration_open == 2) && ($judge_window_open == 1) && ($judge_limit) && ($steward_limit)) $page_info100 .= sprintf("<p>%s</p>",$sidebar_text_003);
			else $page_info100 .= sprintf("<p>%s %s through %s.</p>", $sidebar_text_005, $reg_open_sidebar, $reg_closed_sidebar);
		}

		if ($judge_window_open == 1) {
			if ((!$judge_limit) && (!$steward_limit)) $page_info100 .= sprintf("<p>%s accepted %s through %s.</p>", $sidebar_text_000, $judge_open_sidebar, $judge_closed_sidebar);
			elseif (($judge_limit) && (!$steward_limit)) $page_info100 .= sprintf("<p><a href=\"%s\">%s</a> accepted %s through %s.</p>", build_public_url("register","steward","default","default",$sef,$base_url), $sidebar_text_001, $judge_open_sidebar, $judge_closed_sidebar);
			elseif ((!$judge_limit) && ($steward_limit)) $page_info100 .= sprintf("<p><a href=\"%s\">%s</a> accepted %s through %s.</p>", build_public_url("register","judge","default","default",$sef,$base_url), $sidebar_text_002, $judge_open_sidebar, $judge_closed_sidebar);
		}

		$page_info100 .= "</div>";
		$page_info100 .= "</div>";

	}

	if ($show_entries) {

		// Entry Window Dates
		$header1_200 .= "<div class=\"panel ".$entry_panel_display."\">";
		$header1_200 .= "<div class=\"panel-heading\">";
		$header1_200 .= sprintf("<h4 class=\"panel-title\">%s",$label_entry_registration);
		if (($entry_window_open == 1) && (!$comp_entry_limit) && (!$comp_paid_entry_limit)) $header1_200 .= sprintf(" %s",$label_open);
		else $header1_200 .= " Closed";
		$header1_200 .= "</h4>";
		$header1_200 .= "</div>";
		$page_info200 .= "<div class=\"panel-body\">";

		if (($_SESSION['prefsProEdition'] == 0) && ($entry_window_open == 1)) {
			$page_info200 .= "<p>";
			$page_info200 .= sprintf("<strong class=\"text-success\">%s %s</strong> %s %s, %s.", $total_entries, strtolower($label_entries), $sidebar_text_025, $current_time, $current_date_display);
			$page_info200 .= "</p>";
		}

		if ((!$comp_entry_limit) && (!$comp_paid_entry_limit)) $page_info200 .= sprintf("%s %s through %s.", $sidebar_text_009, $entry_open_sidebar, $entry_closed_sidebar);
		if (($comp_entry_limit) || ($comp_paid_entry_limit)) {
			$page_info200 .= "<span class=\"text-danger\">";
			if ($comp_paid_entry_limit) $page_info200 .= $sidebar_text_010;
			else $page_info200 .= $sidebar_text_011;
			$page_info200 .= "</span>";
		}
		$page_info200 .= "";
		$page_info200 .= "</div>";
		$page_info200 .= "</div>";

		// Customized display for users looking at their account summary
		if (($logged_in) && (($section == "list") || ($section == "pay"))) {
			$total_not_paid = total_not_paid_brewer($_SESSION['user_id']);

			// Online Registration Dates
			$header1_100 .= "<div class=\"panel panel-info\">";
			$header1_100 .= "<div class=\"panel-heading\">";
			$header1_100 .= sprintf("<h4 class=\"panel-title\">%s<span class=\"fa fa-lg fa-info-circle text-primary pull-right\"></span></h4>",$label_account_summary);
			$header1_100 .= "</div>";
			$page_info100 .= "<div class=\"panel-body\">";
			$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
			$page_info100 .= sprintf("<strong class=\"text-danger\">%s</strong>",$label_confirmed_entries);
			if ($section == "list") 	$page_info100 .= sprintf("<span class=\"pull-right\"><a href=\"#entries\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"%s\">%s</a></span>",$sidebar_text_016,$totalRows_log_confirmed);
			else $page_info100 .= sprintf("<span class=\"pull-right\"><a href=\"%s\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"%s\">%s</a></span>",build_public_url("list","default","default","default",$sef,$base_url)."#entries", $sidebar_text_012, $totalRows_log_confirmed);
			$page_info100 .= "</div>";
			if (($totalRows_log - $totalRows_log_confirmed) > 0) {
				$page_info100 .= "<div class=\"bcoem-sidebar-panel bg-warning\">";
				$page_info100 .= sprintf("<strong class=\"text-danger\">%s</strong>",$label_unconfirmed_entries);
				if ($section == "list") 	$page_info100 .= sprintf("<span class=\"pull-right\"><a href=\"#entries\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"%s\">%s</a></span>",$sidebar_text_015,($totalRows_log - $totalRows_log_confirmed));
				else $page_info100 .= sprintf("<span class=\"pull-right\"><a href=\"%s\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"%s\">%s</a></span>",build_public_url("list","default","default","default",$sef,$base_url)."#entries",$sidebar_text_015,($totalRows_log - $totalRows_log_confirmed));
				$page_info100 .= "</div>";
			}

			if (!$comp_paid_entry_limit) {

				if ($total_to_pay > 0) {
					$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
					$page_info100 .= sprintf("<strong class=\"text-danger\">%s</strong><span class=\"pull-right\">%s</span>",$label_unpaid_confirmed_entries,$total_not_paid);
					$page_info100 .= "</div>";
				}

				$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
				$page_info100 .= sprintf("<strong class=\"text-danger\">%s",$label_total_entry_fees);
				if (($totalRows_log - $totalRows_log_confirmed) > 0) $page_info100 .= "*";
				$page_info100 .= "</strong>";
				if ($discount) $page_info100 .= "*";
				$page_info100 .= sprintf("<span class=\"pull-right\">%s%s</span>",$currency_symbol,number_format($total_entry_fees,2));
				$page_info100 .= "</div>";

				$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
				$page_info100 .= sprintf("<strong class=\"text-danger\">%s</strong>",$label_entry_fees_to_pay);
				if ($section != "pay") $page_info100 .= sprintf("<span class=\"pull-right\"><a data-toggle=\"tooltip\" data-placement=\"top\" title=\"%s\" href=\"%s\">%s%s</a></span>",$sidebar_text_013,build_public_url("pay","default","default","default",$sef,$base_url),$currency_symbol,number_format($total_to_pay,2));
				else $page_info100 .= sprintf("<span class=\"pull-right\">%s%s</span>",$currency_symbol,number_format($total_to_pay,2));
				$page_info100 .= "</div>";
				if (($totalRows_log - $totalRows_log_confirmed) > 0) {
					$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
					$page_info100 .= sprintf("<small><em class=\"text-muted\">* %s</em></small>",$sidebar_text_014);
					$page_info100 .= "</div>";
				}

			}

			if (!empty($row_limits['prefsUserEntryLimit']) && (!$comp_entry_limit) && (!$comp_paid_entry_limit)) {

				$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
				if ($remaining_entries > 0) {
					$page_info100 .= sprintf("%s <strong class=\"text-success\">%s",$sidebar_text_017,$remaining_entries);
					if ($remaining_entries == 1) $page_info100 .= sprintf(" %s ",strtolower($label_entry));
					else $page_info100 .= sprintf(" %s ",strtolower($label_entries));
					$page_info100 .= sprintf("%s %s",$sidebar_text_018,$row_limits['prefsUserEntryLimit']);
					if ($row_limits['prefsUserEntryLimit'] > 1) $page_info100 .= sprintf(" %s ",strtolower($label_entries));
					else $page_info100 .= sprintf(" %s ",strtolower($label_entry));
					$page_info100 .= sprintf("%s</strong> %s.",$sidebar_text_019,$sidebar_text_021);
				}
				if ($totalRows_log >= $row_limits['prefsUserEntryLimit'])  {
					$page_info100 .= sprintf("%s <strong class=\"text-danger\">%s",$sidebar_text_020,$row_limits['prefsUserEntryLimit']);
					if ($row_limits['prefsUserEntryLimit'] > 1) $page_info100 .= sprintf(" %s ",strtolower($label_entries));
					else $page_info100 .= sprintf(" %s ",strtolower($label_entry));
					$page_info100 .= sprintf("%s</strong> %s.",$sidebar_text_019,$sidebar_text_021);
				}
				$page_info100 .= "</div>";
			}

			if ($discount) {
				$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
				$page_info100 .= "<small><em class=\"text-muted\">";
				if (NHC) $page_info100 .= "* As an AHA member, your entry fees are ";
				else $page_info100 .= "* Fees discounted to ";
				$page_info100 .= $currency_symbol.number_format($_SESSION['contestEntryFeePasswordNum'],2)." per entry.";
				$page_info100 .= "</em></small>";
				$page_info100 .= "</div>";
			}

			$page_info100 .= "</div>";
			$page_info100 .= "</div>";

		}

		// Drop-off Dates and Location
		if ($_SESSION['prefsDropOff'] == 1) {
			$header1_300 .= sprintf("<div class=\"panel %s\">",$dropoff_panel_display);
			$header1_300 .= "<div class=\"panel-heading\">";
			$header1_300 .= sprintf("<h4 class=\"panel-title\">%s",$label_entry_drop_off);
			if ($dropoff_window_open == 1) $header1_300 .= sprintf(" %s",$label_open);
			else $header1_300 .= sprintf(" %s",$label_closed);
			$header1_300 .= "</h4>";
			$header1_300 .= "</div>";
			$page_info300 .= "<div class=\"panel-body\">";
			if ($totalRows_dropoff == 1) {
				$page_info300 .= sprintf("%s <a href=\"%s\">%s</a> %s %s %s.",$sidebar_text_022,build_public_url("entry","default","default","default",$sef,$base_url)."#".str_replace(" ", "-", strtolower($label_drop_off)),strtolower($label_drop_offs),$dropoff_open_sidebar,$sidebar_text_004, $dropoff_closed_sidebar);
			}
			else {
				$page_info300 .= sprintf("%s <a href=\"%s\">%s</a> %s %s %s.",$sidebar_text_022,build_public_url("entry","default","default","default",$sef,$base_url)."#".str_replace(" ", "-", strtolower($label_drop_offs)),strtolower($label_drop_offs),$dropoff_open_sidebar,$sidebar_text_004, $dropoff_closed_sidebar);
			}
			$page_info300 .= "</p>";
			$page_info300 .= "<p><small>".$dropoff_qualifier_text_001."</small></p>";
			$page_info300 .= "</div>";
			$page_info300 .= "</div>";

		}

		// Shipping Date and Location
		if ($_SESSION['prefsShipping'] == 1) {
			$header1_500 .= "<div class=\"panel ".$shipping_panel_display."\">";
			$header1_500 .= "<div class=\"panel-heading\">";
			$header1_500 .= sprintf("<h4 class=\"panel-title\">%s",$label_entry_shipping);
			if ($shipping_window_open == 1) $header1_500 .= sprintf(" %s",$label_open);
			else $header1_500 .= sprintf(" %s",$label_closed);
			$header1_500 .= "</h4>";
			$header1_500 .= "</div>";
			$page_info500 .= "<div class=\"panel-body\">";
			$page_info500 .= sprintf("%s <a href=\"%s\">%s</a> %s %s %s.",$sidebar_text_022, build_public_url("entry","default","default","default",$sef,$base_url)."#".str_replace(" ", "-", strtolower($label_shipping_info)), $sidebar_text_023, $shipping_open_sidebar, $sidebar_text_004, $shipping_closed_sidebar);
			$page_info500 .= "</p>";
			$page_info500 .= "</div>";
			$page_info500 .= "</div>";
		}

	}

	// Judging Location(s)
	$header1_400 .= "<div class=\"panel panel-info\">";
	$header1_400 .= "<div class=\"panel-heading\">";
	$header1_400 .= sprintf("<h4 class=\"panel-title\">%s</h4>",$label_judging_loc);
	$header1_400 .= "</div>";
	$page_info400 .= "<div class=\"panel-body\">";
	if ($totalRows_judging == 0) $page_info400 .= sprintf("<p>%s</p>",$sidebar_text_024);
	else {
		do {

			$page_info400 .= "<p>";
			if ($row_judging['judgingLocName'] != "") $page_info400 .= $row_judging['judgingLocName'];
			if ($logged_in) {
				$location_link = $base_url."output/maps.output.php?section=driving&amp;id=".str_replace(' ', '+', $row_judging['judgingLocation']);
				$location_tooltip = "Map to ".$row_judging['judgingLocName'];
			}
			else {
				$location_link = "#";
				$location_tooltip = "Log in to view the ".$row_judging['judgingLocName']." location";
			}
			if ($row_judging['judgingLocation'] != "") $page_info400 .= " <a class=\"hide-loader\" href=\"".$location_link."\" target=\"".$location_target."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$location_tooltip."\"> <span class=\"fa fa-lg fa-map-marker\"></span></a>";
			if ($row_judging['judgingDate'] != "") $page_info400 .=  "<br />".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");
			$page_info400 .= "</p>";
		} while ($row_judging = mysqli_fetch_assoc($judging));
	}
	$page_info400 .= "</div>";
	$page_info400 .= "</div>";

	// --------------------------------------------------------------
	// Display
	// --------------------------------------------------------------
	if ((($_SESSION['contestLogo'] != "") && (file_exists(USER_IMAGES.$_SESSION['contestLogo'])))) echo $competition_logo;

	echo $page_info;

	if ($_SESSION['prefsUseMods'] == "Y") include (INCLUDES.'mods_sidebar_top.inc.php');

	echo $header1_400;
	echo $page_info400;

	echo $header1_100;
	echo $page_info100;

	echo $header1_200;
	echo $page_info200;

	echo $header1_300;
	echo $page_info300;

	echo $header1_500;
	echo $page_info500;

	if ($_SESSION['prefsUseMods'] == "Y") include (INCLUDES.'mods_sidebar_bottom.inc.php');
}
?>