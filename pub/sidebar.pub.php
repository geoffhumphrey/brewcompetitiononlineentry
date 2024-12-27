<?php
/**
 * Module:      default.sec.php
 * Description: This module houses the intallation's landing page that includes
 *              information about the competition, registration dates/info, and
 *              winner display after all judging dates have passed.
 */

/*
// Redirect if directly accessed
if ((!isset($_SESSION['prefs'.$prefix_session])) || ((isset($_SESSION['prefs'.$prefix_session])) && (!isset($base_url)))) {
    $redirect = "../../index.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}
*/

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
	$header1_600 = "";
	$page_info600 = "";
	$header1_700 = "";
	$page_info700 = "";

	$non_judging_display = "";

	if ((isset($_SESSION['loginUsername'])) && ($_SESSION['brewerDiscount'] == "Y") && ($_SESSION['contestEntryFeePasswordNum'] != "")) $discount = TRUE;
	else $discount = FALSE;

		// Conditional display of panel colors based upon open/closed dates
		if (($registration_open == 0) && ($judge_window_open == 0)) $reg_panel_display = "panel-danger";
		elseif (($registration_open == 1) && ($judge_window_open != 1)) $reg_panel_display = "panel-success";
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
		
		if (($registration_open == 2) && ($judge_window_open == 1) && ($judge_limit) && ($steward_limit)) $page_info100 .= sprintf("<p>%s</p>",$sidebar_text_003);
		else $page_info100 .= sprintf("<p>%s %s %s %s.</p>", $sidebar_text_005, $reg_open_sidebar, $sidebar_text_004, $reg_closed_sidebar);
		
		if ((!$judge_limit) && (!$steward_limit)) $page_info100 .= sprintf("<p>%s %s %s %s.</p>", $sidebar_text_000, $judge_open_sidebar, $sidebar_text_004, $judge_closed_sidebar);
		elseif (($judge_limit) && (!$steward_limit)) $page_info100 .= sprintf("<p><a href=\"%s\">%s</a> %s %s %s.</p>", build_public_url("register","steward","default","default",$sef,$base_url), $sidebar_text_001, $sidebar_text_004, $judge_open_sidebar, $judge_closed_sidebar);
		elseif ((!$judge_limit) && ($steward_limit)) $page_info100 .= sprintf("<p><a href=\"%s\">%s</a> %s %s %s.</p>", build_public_url("register","judge","default","default",$sef,$base_url), $sidebar_text_002, $sidebar_text_004, $judge_open_sidebar, $judge_closed_sidebar);
		
		$page_info100 .= "</div>";
		$page_info100 .= "</div>";

	}

	if ($show_entries) {

		// Entry Window Dates
		$header1_200 .= "<div class=\"hidden-print panel ".$entry_panel_display."\">";
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
			if ($row_limits['prefsEntryLimit'] > 0) $page_info200 .= sprintf(" %s <strong>%s</strong> %s",$entry_info_text_019,$row_limits['prefsEntryLimit'],$entry_info_text_020);
			$page_info200 .= "</p>";

			$page_info200 .= "<p>";
			$page_info200 .= sprintf("<strong class=\"text-success\">%s %s</strong> %s %s, %s.", $total_paid, strtolower($label_paid_entries), $sidebar_text_026, $current_time, $current_date_display);
			if ($row_limits['prefsEntryLimitPaid'] > 0) $page_info200 .= sprintf(" %s <strong>%s</strong> <em>%s</em> %s",$entry_info_text_019,$row_limits['prefsEntryLimitPaid'],strtolower($label_paid),$entry_info_text_020);
			$page_info200 .= "</p>";

		}

		

		if ((!$comp_entry_limit) && (!$comp_paid_entry_limit)) $page_info200 .= sprintf("%s %s %s %s.", $sidebar_text_009, $entry_open_sidebar, $sidebar_text_004, $entry_closed_sidebar);
		
		if (($comp_entry_limit) || ($comp_paid_entry_limit)) {
			$page_info200 .= "<span class=\"text-danger\">";
			if ($comp_entry_limit) $page_info200 .= $sidebar_text_011;
			else $page_info200 .= $sidebar_text_010;
			$page_info200 .= "</span>";
		}
		
		$page_info200 .= "</div>";
		$page_info200 .= "</div>";

		// Customized display for users looking at their account summary
		if (($logged_in) && (($section == "list") || ($section == "pay"))) {
			$total_not_paid = total_not_paid_brewer($_SESSION['user_id']);

			// Online Registration Dates
			$header1_100 .= "<div class=\"hidden-print panel panel-info\">";
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

				if (!$disable_pay) {
					$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
					$page_info100 .= sprintf("<strong class=\"text-danger\">%s</strong>",$label_entry_fees_to_pay);
					if ($section != "pay") $page_info100 .= sprintf("<span class=\"pull-right\"><a data-toggle=\"tooltip\" data-placement=\"top\" title=\"%s\" href=\"%s\">%s%s</a></span>",$sidebar_text_013,build_public_url("pay","default","default","default",$sef,$base_url),$currency_symbol,number_format($total_to_pay,2));
					else $page_info100 .= sprintf("<span class=\"pull-right\">%s%s</span>",$currency_symbol,number_format($total_to_pay,2));
					$page_info100 .= "</div>";
				}
				if (($totalRows_log - $totalRows_log_confirmed) > 0) {
					$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
					$page_info100 .= sprintf("<small><em class=\"text-muted\">* %s</em></small>",$sidebar_text_014);
					$page_info100 .= "</div>";
				}

			}

			if (!empty($row_limits['prefsUserEntryLimit']) && (!$comp_entry_limit) && (!$comp_paid_entry_limit) && (!$disable_pay)) {

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
		if (($_SESSION['prefsDropOff'] == 1) && (!empty($dropoff_closed_sidebar))) {

			if (!empty($dropoff_open_sidebar)) $sidebar_text_004 = "&mdash;";

			$header1_300 .= sprintf("<div class=\"hidden-print panel %s\">",$dropoff_panel_display);
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
		if (($_SESSION['prefsShipping'] == 1) && (!empty($shipping_closed_sidebar)))  {

			if (!empty($shipping_open_sidebar)) $sidebar_text_004 = "&mdash;";

			$header1_500 .= "<div class=\"hidden-print panel ".$shipping_panel_display."\">";
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

	if ($show_presentation) {
		if ((get_archive_count($prefix."judging_scores") > 0) || (get_archive_count($prefix."judging_scores_bos") > 0)) $header1_600 .= "<div class=\"bcoem-admin-element hidden-print\"><a class=\"btn btn-primary btn-block btn-sm\" href=\"".$base_url."awards.php\" target=\"_blank\">".$label_launch_pres." <span class=\"fa fa-award\"></span></a></div>";
	}

	// Judging Location(s)
	$header1_400 .= "<div class=\"hidden-print panel panel-info\">";
	$header1_400 .= "<div class=\"panel-heading\">";
	$header1_400 .= sprintf("<h4 class=\"panel-title\">%s</h4>",$label_judging_loc);
	$header1_400 .= "</div>";
	$page_info400 .= "<div class=\"panel-body\">";
	if ($totalRows_judging == 0) $page_info400 .= sprintf("<p>%s</p>",$sidebar_text_024);
	
	else {
		
		do {

			if ($row_judging['judgingLocType'] == 2) {

				$non_judging_display .= "<p>";

				if ($row_judging['judgingLocName'] != "") $non_judging_display .= "<strong>".$row_judging['judgingLocName']."</strong>";

				if ($logged_in) {
					$address = rtrim($row_judging['judgingLocation'],"&amp;KeepThis=true");
					$address = str_replace(' ', '+', $address);
					$location_link = "http://maps.google.com/maps?f=q&source=s_q&hl=en&q=".$address;
					$location_tooltip = "Map to ".$row_judging['judgingLocName'];
				}
				else {
					$location_link = "#";
					$location_tooltip = "Log in to view the ".$row_judging['judgingLocName']." location.";
				}
				if ($row_judging['judgingLocation'] != "") $non_judging_display .= " <a class=\"hide-loader\" href=\"".$location_link."\" target=\"".$location_target."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$location_tooltip."\"> <span class=\"fa fa-lg fa-map-marker\"></span></a>";

				if ($row_judging['judgingDate'] != "") $non_judging_display .=  "<br />".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");

				$non_judging_display .= "</p>";
			}

			else {

				$page_info400 .= "<p>";
				if ($row_judging['judgingLocName'] != "") $page_info400 .= "<strong>".$row_judging['judgingLocName']."</strong>";
				if ($row_judging['judgingLocType'] == 0) {
					if ($logged_in) {
						$address = rtrim($row_judging['judgingLocation'],"&amp;KeepThis=true");
						$address = str_replace(' ', '+', $address);
						$location_link = "http://maps.google.com/maps?f=q&source=s_q&hl=en&q=".$address;
						$location_tooltip = "Map to ".$row_judging['judgingLocName'];
					}
					else {
						$location_link = "#";
						$location_tooltip = "Log in to view the ".$row_judging['judgingLocName']." location.";
					}
					if ($row_judging['judgingLocation'] != "") $page_info400 .= " <a class=\"hide-loader\" href=\"".$location_link."\" target=\"".$location_target."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$location_tooltip."\"> <span class=\"fa fa-lg fa-map-marker\"></span></a>";
				}
				
				if ($row_judging['judgingDate'] != "") $page_info400 .=  "<br />".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");
				if ($row_judging['judgingDateEnd'] != "") $page_info400 .=  " ".$sidebar_text_004." ".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDateEnd'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");
				
				if ($row_judging['judgingLocType'] == 1) {
					if (!empty($row_judging['judgingLocation'])) $page_info400 .= "<br /><small>".$row_judging['judgingLocation']."</small>";
				}

				$page_info400 .= ".</p>";
			
			}
			
		} while ($row_judging = mysqli_fetch_assoc($judging));
	
	} // end else
	
	$page_info400 .= "</div>";
	$page_info400 .= "</div>";

	// Non-Judging Location(s)
	if (!empty($non_judging_display)) {
		$header1_700 .= "<div class=\"hidden-print panel panel-info\">";
		$header1_700 .= "<div class=\"panel-heading\">";
		$header1_700 .= sprintf("<h4 class=\"panel-title\">%s</h4>",$label_non_judging);
		$header1_700 .= "</div>";
		$page_info700 .= "<div class=\"panel-body\">";
		$page_info700 .= $non_judging_display;
		$page_info700 .= "</div>";
		$page_info700 .= "</div>";
	}

	$archive_sidebar_content = "";
	$archive_sidebar = FALSE;
	$archive_sidebar_count = 0;

	if ((isset($_SESSION['contestWinnerLink'])) && (!empty($_SESSION['contestWinnerLink']))) $archive_sidebar = TRUE;

	if (!HOSTED) {

		if ($totalRows_archive > 0) {

			do {

				if (($row_archive['archiveDisplayWinners'] == "Y") && ($row_archive['archiveStyleSet'] != "")) {
					$table_archive = $prefix."judging_scores_".$row_archive['archiveSuffix'];
					if (table_exists($table_archive)) {
				   		if (get_archive_count($table_archive) > 0) {
				   			$archive_link = build_public_url("past-winners",$row_archive['archiveSuffix'],"default","default",$sef,$base_url);
				   			$archive_sidebar_count++;
							if ($go == $row_archive['archiveSuffix']) $archive_sidebar_content .= "<li><i class=\"fa fa-fw fa-trophy text-gold\"></i> <strong>".$row_archive['archiveSuffix']."</strong></li>";
							else $archive_sidebar_content .= "<li><i class=\"fa fa-fw fa-trophy text-silver\"></i> <a href=\"".$archive_link."\">".$row_archive['archiveSuffix']."</a></li>";
						}
					}
				}	

			} while($row_archive = mysqli_fetch_assoc($archive));

		}

		if ($archive_sidebar_count > 0) $archive_sidebar = TRUE;
		
	}

	if ($archive_sidebar) {

		if ((isset($_SESSION['contestWinnerLink'])) && (!empty($_SESSION['contestWinnerLink']))) {
			if ($archive_sidebar_count == 0) $archive_sidebar_content .= sprintf("<li><a class=\"hide-loader\" href=\"%s\" target=\"_blank\">%s <i class=\"fa fa-fw fa-external-link-alt\"></i></a></li>",$_SESSION['contestWinnerLink'],$label_view);
			else $archive_sidebar_content .= sprintf("<li><i class=\"fa fa-fw fa-external-link-alt text-silver\"></i> <a class=\"hide-loader\" href=\"%s\" target=\"_blank\">%s</a></li>",$_SESSION['contestWinnerLink'],$label_more_info);
		}
		
		$header1_600 .= "<div class=\"hidden-print panel panel-info\">";
		$header1_600 .= "<div class=\"panel-heading\">";
		$header1_600 .= sprintf("<h4 class=\"panel-title\">%s</h4>",$label_past_winners);
		$header1_600 .= "</div>";
		$page_info600 .= "<div class=\"panel-body\">";
		$page_info600 .= "<ul class=\"list-unstyled\">";
		$page_info600 .= $archive_sidebar_content;
		$page_info600 .= "</ul>";
		$page_info600 .= "</div>";
		$page_info600 .= "</div>";
	}

	// --------------------------------------------------------------
	// Display
	// --------------------------------------------------------------
	if ((isset($_SESSION['contestLogo'])) && (!empty($_SESSION['contestLogo'])) && (file_exists(USER_IMAGES.$_SESSION['contestLogo']))) echo $competition_logo;

	echo $page_info;

	if ($_SESSION['prefsUseMods'] == "Y") include (INCLUDES.'mods_sidebar_top.inc.php');

	echo $header1_600;
	echo $page_info600;

	echo $header1_400;
	echo $page_info400;

	echo $header1_700;
	echo $page_info700;

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