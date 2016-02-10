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

if ($section != "admin") {

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

	if (($_SESSION['brewerDiscount'] == "Y") && ($_SESSION['contestEntryFeePasswordNum'] != "")) $discount = TRUE; else $discount = FALSE;

		// Conditional display of panel colors based upon open/closed dates
		if ($registration_open == 0) $reg_panel_display = "panel-default";
		elseif (($registration_open == 1) || ($judge_window_open == 1)) $reg_panel_display = "panel-success";
		elseif ($registration_open == 2) $reg_panel_display = "panel-danger";
		else $reg_panel_display = "panel-default";
		
		if ($entry_window_open == 0) $entry_panel_display = "panel-default";
		elseif ($entry_window_open == 1) $entry_panel_display = "panel-success";
		elseif ($entry_window_open == 2) $entry_panel_display = "panel-danger";
		else $entry_panel_display = "panel-default";
		
		if ($dropoff_window_open == 0) $dropoff_panel_display = "panel-default";
		elseif ($dropoff_window_open == 1) $dropoff_panel_display = "panel-success";
		elseif ($dropoff_window_open == 2) $dropoff_panel_display = "panel-danger";
		else $dropoff_panel_display = "panel-default";
		
		if ($shipping_window_open == 0) $shipping_panel_display = "panel-default";
		elseif ($shipping_window_open == 1) $shipping_panel_display = "panel-success";
		elseif ($shipping_window_open == 2) $shipping_panel_display = "panel-danger";
		else $shipping_panel_display = "panel-default";
		

	if (!$logged_in) {	
		// Online Registration Dates
		$header1_100 .= "<div class=\"panel ".$reg_panel_display."\">";
		$header1_100 .= "<div class=\"panel-heading\">";
		$header1_100 .= "<h4 class=\"panel-title\">Account Registration is";
		if (($registration_open == 1) || ($judge_window_open == 1)) $header1_100 .= " Open";
		else $header1_100 .= " Closed";
		$header1_100 .= "</h4>";
		$header1_100 .= "</div>";
		$page_info100 .= "<div class=\"panel-body\">";
		$page_info100 .= "Account registrations";
		if (($registration_open == 2) && ($judge_window_open == 1)) $page_info100 .= sprintf(" for <strong class=\"text-success\">judges and stewards only</strong> accepted %s through %s.", $judge_open_sidebar, $judge_closed_sidebar); 
		else $page_info100 .= sprintf(" accepted %s through %s.", $reg_open_sidebar, $reg_closed_sidebar);
		$page_info100 .= "</div>";
		$page_info100 .= "</div>";

	}

	// Entry Window Dates
	$header1_200 .= "<div class=\"panel ".$entry_panel_display."\">";
	$header1_200 .= "<div class=\"panel-heading\">";
	$header1_200 .= "<h4 class=\"panel-title\">Entry Registration is";
	if ($entry_window_open == 1) $header1_200 .= " Open";
	else $header1_200 .= " Closed";
	$header1_200 .= "</h4>";
	$header1_200 .= "</div>";
	$page_info200 .= "<div class=\"panel-body\">";
	$page_info200 .= sprintf("Entry registrations accepted %s through %s.", $entry_open_sidebar, $entry_closed_sidebar);
	$page_info200 .= "</div>";
	$page_info200 .= "</div>";
	
	// Customized display for users looking at their account summary
	if (($logged_in) && (($section == "list") || ($section == "pay"))) {
		$total_not_paid = total_not_paid_brewer($_SESSION['user_id']);
		
		// Online Registration Dates
		$header1_100 .= "<div class=\"panel panel-info\">";
		$header1_100 .= "<div class=\"panel-heading\">";
		$header1_100 .= "<h4 class=\"panel-title\">My Account Summary<span class=\"fa fa-info-circle text-primary pull-right\"></span></h4> ";
		$header1_100 .= "</div>";
		$page_info100 .= "<div class=\"panel-body\">";
		//$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
		//$page_info100 .= "Account summary for ".$_SESSION['brewerFirstName'].".";
		//$page_info100 .= "</div>";
		$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
		$page_info100 .= "<strong class=\"text-danger\">Confirmed Entries</strong>";
		if ($section == "list") 	$page_info100 .= "<span class=\"pull-right\"><a href=\"#entries\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"See your list of entries\">".$totalRows_log_confirmed."</a></span>";
		else $page_info100 .= "<span class=\"pull-right\"><a href=\"".build_public_url("list","default","default","default",$sef,$base_url)."#entries\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"See your list of entries\">".$totalRows_log_confirmed."</a></span>";
		$page_info100 .= "</div>";
		if (($totalRows_log - $totalRows_log_confirmed) > 0) {
			$page_info100 .= "<div class=\"bcoem-sidebar-panel bg-warning\">";
			$page_info100 .= "<strong class=\"text-danger\">Unconfirmed Entries</strong>";
			if ($section == "list") 	$page_info100 .= "<span class=\"pull-right\"><a href=\"#entries\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"You have unconfirmed entries - action is needed to confirm\">".($totalRows_log - $totalRows_log_confirmed)."</a></span>";
			else $page_info100 .= "<span class=\"pull-right\"><a href=\"".build_public_url("list","default","default","default",$sef,$base_url)."#entries\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"You have unconfirmed entries - action is needed to confirm\">".($totalRows_log - $totalRows_log_confirmed)."</a></span>";
			$page_info100 .= "</div>";
		}
		
		$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
		$page_info100 .= "<strong class=\"text-danger\">Unpaid Confirmed Entries</strong><span class=\"pull-right\">".$total_not_paid."</span>";
		$page_info100 .= "</div>";
		$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
		$page_info100 .= "<strong class=\"text-danger\">Total Entry Fees";
		if (($totalRows_log - $totalRows_log_confirmed) > 0) $page_info100 .= "*";
		$page_info100 .= "</strong>";
		if ($discount) $page_info100 .= "*";
		$page_info100 .= "<span class=\"pull-right\">".$currency_symbol.number_format($total_entry_fees,2)."</span>";
		$page_info100 .= "</div>";
		$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
		$page_info100 .= "<strong class=\"text-danger\">Entry Fees To Pay</strong>";
		if ($section != "pay") $page_info100 .= "<span class=\"pull-right\"><a data-toggle=\"tooltip\" data-placement=\"top\" title=\"Click here to pay your fees\" href=\"".build_public_url("pay","default","default","default",$sef,$base_url)."\">".$currency_symbol.number_format($total_to_pay,2)."</a></span>";
		else $page_info100 .= "<span class=\"pull-right\">".$currency_symbol.number_format($total_to_pay,2)."</span>";
		$page_info100 .= "</div>";
		if (($totalRows_log - $totalRows_log_confirmed) > 0) {
			$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
			$page_info100 .= "<small><em class=\"text-muted\">* Entry fees do not include unconfirmed entries.</em></small>";
			$page_info100 .= "</div>";
		}
		
		
		if ($row_limits['prefsUserEntryLimit'] != "") {
			
			$page_info100 .= "<div class=\"bcoem-sidebar-panel\">";
			if ($remaining_entries > 0) {
				$page_info100 .= "You have <strong class=\"text-success\">".readable_number($remaining_entries)." (".$remaining_entries.")";
				if ($remaining_entries == 1) $page_info100 .= " entry ";
				else $page_info100 .= " entries "; 
				$page_info100 .= "left before you reach the limit of ".readable_number($row_limits['prefsUserEntryLimit'])." (".$row_limits['prefsUserEntryLimit'].") ";
				if ($row_limits['prefsUserEntryLimit'] > 1) $page_info100 .= " entries ";
				else $page_info100 .= " entry "; 
				$page_info100 .= "per participant</strong> in this competition.";
			}
			if ($totalRows_log >= $row_limits['prefsUserEntryLimit'])  {
				$page_info100 .= "You have reached the limit of <strong class=\"text-danger\">".readable_number($row_limits['prefsUserEntryLimit'])." (".$row_limits['prefsUserEntryLimit'].") ";
				if ($row_limits['prefsUserEntryLimit'] > 1) $page_info100 .= "entries ";
				else $page_info100 .= "entry ";
				$page_info100 .= "per participant</strong> in this competition.";
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
	$header1_300 .= "<div class=\"panel ".$dropoff_panel_display."\">";
	$header1_300 .= "<div class=\"panel-heading\">";
	$header1_300 .= "<h4 class=\"panel-title\">Entry Drop-Off is";
	if ($dropoff_window_open == 1) $header1_300 .= " Open";
	else $header1_300 .= " Closed";
	$header1_300 .= "</h4>";
	$header1_300 .= "</div>";
	$page_info300 .= "<div class=\"panel-body\">";
	$page_info300 .= sprintf("Entry bottles accepted at <a href=\"%s#drop\">drop-off locations</a> %s through %s.",build_public_url("entry","default","default","default",$sef,$base_url),$dropoff_open_sidebar,$dropoff_closed_sidebar);
	$page_info300 .= "</p>";
	$page_info300 .= "</div>";
	$page_info300 .= "</div>";
	
	// Drop-off Dates and Location
	$header1_500 .= "<div class=\"panel ".$shipping_panel_display."\">";
	$header1_500 .= "<div class=\"panel-heading\">";
	$header1_500 .= "<h4 class=\"panel-title\">Entry Shipping is";
	if ($shipping_window_open == 1) $header1_500 .= " Open";
	else $header1_500 .= " Closed";
	$header1_500 .= "</h4>";
	$header1_500 .= "</div>";
	$page_info500 .= "<div class=\"panel-body\">";
	$page_info500 .= sprintf("Entry bottles accepted at the <a href=\"%s#shipping\">shipping location</a> %s through %s.",build_public_url("entry","default","default","default",$sef,$base_url),$shipping_open_sidebar,$shipping_closed_sidebar);
	$page_info500 .= "</p>";
	$page_info500 .= "</div>";
	$page_info500 .= "</div>";

	// Judging Location(s)
	include(DB.'judging_locations.db.php'); 
	$header1_400 .= "<div class=\"panel panel-info\">";
	$header1_400 .= "<div class=\"panel-heading\">";
	if ($totalRows_judging > 1) $header1_400 .= "<h4 class=\"panel-title\">Judging Locations/Dates</h4>";
	else $header1_400 .= "<h4 class=\"panel-title\">Judging Location/Date</h4>";
	$header1_400 .= "</div>";
	$page_info400 .= "<div class=\"panel-body\">";
	if ($totalRows_judging == 0) $page_info400 .= "<p>Competition judging dates are yet to be determined. Please check back later.</p>";
	else {
		do {
			$page_info400 .= "<p>";
			if ($row_judging['judgingLocation'] != "") $page_info400 .= "<a href=\"".$base_url."output/maps.output.php?section=driving&amp;id=".str_replace(' ', '+', $row_judging['judgingLocation'])."\" target=\"_blank\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Map to ".$row_judging['judgingLocName']." at ".$row_judging['judgingLocation']."\">".$row_judging['judgingLocName']."</a> <span class=\"fa fa-map-marker\"></span>";
			else $page_info400 .= $row_judging['judgingLocName'];
			if ($row_judging['judgingDate'] != "") $page_info400 .=  "<br />".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");
			$page_info400 .= "</p>";
		} while ($row_judging = mysql_fetch_assoc($judging));
		
	}
	$page_info400 .= "</div>";
	$page_info400 .= "</div>";

	// --------------------------------------------------------------
	// Display
	// --------------------------------------------------------------
	if ((($_SESSION['contestLogo'] != "") && (file_exists(USER_IMAGES.$_SESSION['contestLogo'])))) echo $competition_logo;
	echo $page_info;
	if ($_SESSION['prefsUseMods'] == "Y") include(INCLUDES.'mods_sidebar_top.inc.php');
	echo $header1_100;
	echo $page_info100;
	echo $header1_200;
	echo $page_info200;
	echo $header1_300;
	echo $page_info300;
	echo $header1_500;
	echo $page_info500;
	echo $header1_400;
	echo $page_info400;
	if ($_SESSION['prefsUseMods'] == "Y") include(INCLUDES.'mods_sidebar_bottom.inc.php');
}


?>