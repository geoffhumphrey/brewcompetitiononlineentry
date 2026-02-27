<?php
/**
 * Module:      entry_info.sec.php
 * Description: This module houses public-facing information including entry.
 *              requirements, dropoff, shipping, and judging locations, etc.
 *
 */

// Redirect if directly accessed
if ((!isset($_SESSION['prefs'.$prefix_session])) || ((isset($_SESSION['prefs'.$prefix_session])) && (!isset($base_url)))) {
    $redirect = "../../index.php?section=entry";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

include (DB.'dropoff.db.php');
include (DB.'judging_locations.db.php');
include (DB.'styles.db.php');
include (DB.'entry_info.db.php');

$primary_page_info = "";
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
$header1_9 = "";
$page_info9 = "";
$header1_10 = "";
$page_info10 = "";
$header1_11 = "";
$page_info11 = "";
$header1_12 = "";
$page_info12 = "";
$header1_13 = "";
$page_info13 = "";
$header1_14 = "";
$page_info14 = "";
$header1_15 = "";
$page_info15 = "";
$header1_16 = "";
$page_info16 = "";
$header1_17 = "";
$page_info17 = "";
$style_info_modals = "";

$ba_accepted_styles = array();
$anchor_links_nav = "";
$anchor_links = array();
$anchor_top = "<p class=\"d-print-none\"><a href=\"#top-page\">".$label_top." <span class=\"fa fa-arrow-circle-up\"></span></a></p>";

$entry_limits_by_style = "";
if ((!empty($_SESSION['prefsStyleLimits'])) && (strlen($_SESSION['prefsStyleLimits']) > 1)) {
	foreach ($style_sets as $style_set) {
		if ($_SESSION['prefsStyleSet'] == $style_set['style_set_name']) {
			foreach (json_decode($_SESSION['prefsStyleLimits'],true) as $key => $value) {
				foreach ($style_set['style_set_categories'] as $k => $v) {
					if ($k == $key) {
						if ($style_limit_entry_count_display[$key] >= $value) $entry_limits_by_style .= sprintf("<tr><td>%s: %s</td><td>%s</td><td><span class=\"text-muted\">%s</span><i class=\"fa fa-times-circle text-danger-emphasis ms-1 d-print-none\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"%s\"></i></td></tr>",$key,$v,$value,$style_limit_entry_count_display[$key],$entry_info_text_056);
						else $entry_limits_by_style .= sprintf("<tr><td>%s: %s</td><td>%s</td><td>".$style_limit_entry_count_display[$key]."</td></tr>",$key,$v,$value);
					} 
				}
			}
		}
    }
}

// Check if style limits by Medal Category or Table are in place.
// If so, display.
$entry_limits_by_medal_category = "";
if ((!empty($_SESSION['prefsStyleLimits'])) && (strlen($_SESSION['prefsStyleLimits']) == 1) && (is_numeric($_SESSION['prefsStyleLimits']))) {

	$query_tables = sprintf("SELECT * FROM %s WHERE tableEntryLimit IS NOT NULL ORDER BY tableName ASC",$prefix."judging_tables");
	$tables = mysqli_query($connection,$query_tables) or die (mysqli_error($connection));
	$row_tables = mysqli_fetch_assoc($tables);
	$totalRows_tables = mysqli_num_rows($tables);

	if ($totalRows_tables > 0) {
		
		do {

			$medal_cat_styles = "<ul class=\"list-inline m-0 p-0\">";

			$a = explode(",", $row_tables['tableStyles']);

			foreach ($a as $value) {
				
				$query_style = sprintf("SELECT brewStyle,brewStyleGroup,brewStyleNum FROM %s WHERE id='%s'", $styles_db_table, $value);
				$style = mysqli_query($connection,$query_style);
				$row_style = mysqli_fetch_assoc($style);

				if ($row_styles) {
					$medal_cat_styles .= "<li class=\"list-inline-item\">";
					$medal_cat_styles .= $row_style['brewStyle'];
					$medal_cat_styles .= "<span class=\"fw-light ms-1\">(".style_number_const($row_style['brewStyleGroup'],$row_style['brewStyleNum'],$_SESSION['style_set_display_separator'],0).")</span>";
					$medal_cat_styles .= "</li>";
				}

			}

			$medal_cat_styles .= "</ul>";

			$entry_limits_by_medal_category .= sprintf("<tr><td>%s</td><td>%s</td><td>%s</td></tr>",$row_tables['tableName'],$medal_cat_styles,$row_tables['tableEntryLimit']);
		
		} while ($row_tables = mysqli_fetch_assoc($tables));

	}

}

$contestRulesJSON = json_decode($row_contest_info['contestRules'],true);

// Registration Window
if (!$logged_in) {
	$anchor_links[] = $label_account_registration;
	$header1_2 .= sprintf("<a name=\"reg_window\"></a><h2>%s</h2>",$label_account_registration);
	if ($registration_open == 0) $page_info2 .= sprintf("<p>%s <strong class=\"text-success\">%s</strong> %s <strong class=\"text-success\">%s</strong>.</p><p>%s <strong class=\"text-success\">%s</strong> %s <strong class=\"text-success\">%s</strong>.</p>", $entry_info_text_000, $reg_open, $entry_info_text_001, $reg_closed, $entry_info_text_002, $judge_open, $entry_info_text_001, $judge_closed);
	elseif ($registration_open == 1) $page_info2 .= sprintf("<p>%s <strong class=\"text-success\">%s</strong>.</p><p>%s <strong class=\"text-success\">%s</strong>.</p>", $entry_info_text_004, $reg_closed, $entry_info_text_005, $judge_closed);
	elseif (($registration_open == 2) && ($judge_window_open == 1)) $page_info2 .= sprintf("%s <strong class=\"text-success\">%s</strong> %s %s.", $entry_info_text_006,$entry_info_text_007,$entry_info_text_008, $judge_closed);
	else $page_info2 .= sprintf("<p>%s</p>",$entry_info_text_009);
}
else $page_info2 .= sprintf("<p class=\"lead\">%s %s! <small><a href=\"%s\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"%s\">%s</a></small></p>",$entry_info_text_010,$_SESSION['brewerFirstName'],build_public_url("list","default","default","default",$sef,$base_url),$entry_info_text_011,$entry_info_text_012);

if ($show_entries) {
	// Entry Window
	$anchor_links[] = $label_entry_registration;
	$anchor_name = str_replace(" ", "-", $label_entry_registration);
	$header1_3 .= sprintf("<a class=\"anchor-offset\" name=\"%s\"></a><h2>%s</h2>",strtolower($anchor_name),$label_entry_registration);
	if ($entry_window_open == 0) $page_info3 .= sprintf("<p>%s <strong class=\"text-success\">%s</strong> %s <strong class=\"text-success\">%s</strong>.</p>",$entry_info_text_014,$entry_open,$entry_info_text_001,$entry_closed);
	elseif ($entry_window_open == 1) $page_info3 .= sprintf("<p>%s <strong class=\"text-success\">%s</strong>.</p>",$entry_info_text_015,$entry_closed);
	else $page_info3 .= sprintf("<p>%s</p>",$entry_info_text_016);

	if ($entry_window_open < 2) {

		// Entry Fees
		$anchor_links[] = $label_entry_fees;
		$anchor_name = str_replace(" ", "-", $label_entry_fees);
		$header1_4 .= sprintf("<a class=\"anchor-offset\" name=\"%s\"></a><h2>%s</h2>",strtolower($anchor_name),$label_entry_fees);
		$page_info4 .= sprintf("<p>%s%s (%s) %s. ",$currency_symbol,number_format($_SESSION['contestEntryFee'],2),$currency_code,$entry_info_text_003);
		if ($_SESSION['contestEntryFeeDiscount'] == "Y") $page_info4 .= sprintf("%s%s %s %s %s. ",$currency_symbol,number_format($_SESSION['contestEntryFee2'],2),$entry_info_text_013,addOrdinalNumberSuffix($_SESSION['contestEntryFeeDiscountNum']),strtolower($label_entry));
		if (!empty($_SESSION['contestEntryCap'])) $page_info4 .= sprintf("%s%s %s ",$currency_symbol,number_format($_SESSION['contestEntryCap'],2),$entry_info_text_017);
		if (NHC) $page_info4 .= sprintf("%s%s %s",$currency_symbol,number_format($_SESSION['contestEntryFeePasswordNum'],2),$entry_info_text_018);
		$page_info4 .= "</p>";

		// Entry Limits
		// Overall Entry Limit or Entry Limits by Style or Medal Category
		if ((!empty($row_limits['prefsEntryLimit'])) || (!empty($row_limits['prefsEntryLimitPaid'])) || (!empty($entry_limits_by_medal_category)) || (!empty($style_type_limits_display)) || (!empty($entry_limits_by_style))) {

			$anchor_links[] = $label_entry_limit;
			$anchor_name = str_replace(" ", "-", $label_entry_limit);
			
			$header1_5 .= sprintf("<a class=\"anchor-offset\" name=\"%s\"></a><h2>%s</h2>",strtolower($anchor_name),$label_entry_limits);

			if (($_SESSION['prefsProEdition'] == 0) && ((!empty($row_limits['prefsEntryLimit'])) || (!empty($row_limits['prefsEntryLimitPaid'])))) {

				$page_info5 .= "<dl class=\"row\">";
				if (!empty($row_limits['prefsEntryLimit'])) $page_info5 .= sprintf("<dt class=\"col-6 col-md-3 col-lg-2\">%s:</dt><dd class=\"col-6 col-md-9 col-lg-10\">%s</dd>",$label_entry_limit, $row_limits['prefsEntryLimit']);
				if (!empty($row_limits['prefsEntryLimitPaid'])) $page_info5 .= sprintf("<dt class=\"col-6 col-md-3 col-lg-2\">%s &ndash; %s:</dt><dd class=\"col-6 col-md-9 col-lg-10\">%s</dd>",$label_entry_limit, $label_paid, $row_limits['prefsEntryLimitPaid']);
				$page_info5 .= "</dl>";

			}

			if (!empty($style_type_entry_count_display)) {
				
				$page_info5 .= "<h3>".str_replace(":","",ucwords($entry_info_text_053))."</h3>";
				$page_info5 .= "<table class='table table-bordered border-dark-subtle'>";
				$page_info5 .= "<thead class='table-dark'>";
				$page_info5 .= sprintf("<tr><th width='%s'>%s</th><th width='%s'>%s</th><th>%s</th></tr>","30%",$label_style_type,"30%",$label_limit,$label_current_count);
				$page_info5 .= "</thead>";
				$page_info5 .= "<tbody>";

				foreach ($style_type_entry_count_display as $key => $value) {
					if (!empty($value[1])) {
						if ($value[0] >= $value[1]) $page_info5 .= sprintf("<tr><td>%s</td><td class=\"text-muted\">%s</td><td>%s<i class=\"fa fa-times-circle text-danger-emphasis ms-1 d-print-none\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"%s\"></i></td>", $key, $value[1], $value[0], $entry_info_text_056);
						else $page_info5 .= sprintf("<tr><td>%s</td><td>%s</td><td>%s</td>", $key, $value[1], $value[0]);
					}
				}
				
				$page_info5 .= "</tbody>";
				$page_info5 .= "</table>";

			}

			if (!empty($entry_limits_by_style)) {

				$page_info5 .= sprintf("<h3>%s</h3>",$label_entry_limit_style);
				$page_info5 .= "<table class='table table-bordered border-dark-subtle'>";
				$page_info5 .= "<thead class='table-dark'>";
				$page_info5 .= sprintf("<tr><th width='%s'>%s</th><th width='%s'>%s</th><th>%s</th></tr>","30%",$label_category,"30%",$label_limit,$label_current_count);
				$page_info5 .= "</thead>";
				$page_info5 .= "<tbody>";
				$page_info5 .= $entry_limits_by_style;
				$page_info5 .= "</tbody>";
				$page_info5 .= "</table>";
			}

			if (!empty($entry_limits_by_medal_category)) {

				$page_info5 .= sprintf("<h3>%s</h3>",$label_entry_limit_medal_category);
				$page_info5 .= "<table class='table table-bordered border-dark-subtle'>";
				$page_info5 .= "<thead class='table-dark'>";
				$page_info5 .= sprintf("<tr><th width='%s'>%s</th><th width='%s'>%s %s</th><th>%s</th>","30%",$label_medal_category,"50%",$_SESSION['style_set_short_name'],$label_admin_styles,$label_limit);
				$page_info5 .= "</thead>";
				$page_info5 .= "<tbody>";

				$page_info5 .= $entry_limits_by_medal_category;
				
				$page_info5 .= "</tbody>";
				$page_info5 .= "</table>";

			}
			
		}

		if ((!empty($row_limits['prefsUserEntryLimit'])) || (!empty($row_limits['prefsUserSubCatLimit'])) || (!empty($row_limits['prefsUSCLExLimit'])) || ($incremental)) {
			
			$anchor_links[] = $label_entry_per_entrant;
			$anchor_name = str_replace(" ", "-", $label_entry_per_entrant);
			$page_info5 .= sprintf("<a class=\"anchor-offset\" name=\"%s\"></a><h3>%s</h3>",strtolower($anchor_name),$label_entry_per_entrant);

			if ($incremental) {

				$page_info5 .= "<table class='table table-bordered border-dark-subtle'>";
				$page_info5 .= "<thead class='table-dark'>";
				$page_info5 .= sprintf("<tr><th width='%s'>%s</th><th>%s</th></tr>","30%",$label_limit,ucwords($sidebar_text_027));
				$page_info5 .= "</thead>";
				$page_info5 .= "<tbody>";
				if (time() < $limit_date_1) $page_info5 .= "<tr class='table-info border-dark-subtle'>";
				else  $page_info5 .= "<tr>";
				$page_info5 .= sprintf("<td>%s %s</td><td>%s</td>",$incremental_limits[1]['limit-number'], $label_entries, getTimeZoneDateTime($_SESSION['prefsTimeZone'], $limit_date_1, $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "$sidebar_date_format", "date-time"));
				$page_info5 .= "</tr>";
				
				if (!empty($limit_date_2)) {
					if ($current_limit == 2) $page_info5 .= "<tr class='table-info border-dark-subtle'>";
					else $page_info5 .= "<tr>";
					$page_info5 .= sprintf("<td>%s %s</td><td>%s</td>",$incremental_limits[2]['limit-number'], $label_entries, getTimeZoneDateTime($_SESSION['prefsTimeZone'], $limit_date_2, $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "$sidebar_date_format", "date-time"));
					$page_info5 .= "</tr>";
				}

				if (!empty($limit_date_3)) {
					if ($current_limit == 3) $page_info5 .= "<tr class='table-info border-dark-subtle'>";
					else $page_info5 .= "<tr>";
					$page_info5 .= sprintf("<td>%s %s</td><td>%s</td>",$incremental_limits[3]['limit-number'], $label_entries, getTimeZoneDateTime($_SESSION['prefsTimeZone'], $limit_date_3, $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "$sidebar_date_format", "date-time"));
					$page_info5 .= "</tr>";
				}

				if (!empty($limit_date_4)) {
					if ($current_limit == 4) $page_info5 .= "<tr class='table-info border-dark-subtle'>";
					else $page_info5 .= "<tr>";
					$page_info5 .= sprintf("<td>%s %s</td><td>%s</td>",$incremental_limits[4]['limit-number'], $label_entries, getTimeZoneDateTime($_SESSION['prefsTimeZone'], $limit_date_4, $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "$sidebar_date_format", "date-time"));
					$page_info5 .= "</tr>";
				}

				if (!empty($real_overall_user_entry_limit)) {	
					if ($current_limit == 0) $page_info5 .= "<tr class='table-info border-dark-subtle'>";
					else $page_info5 .= "<tr>";
					$page_info5 .= sprintf("<td>%s %s</td><td>%s</td>",$real_overall_user_entry_limit, $label_entries, $entry_closed);
					$page_info5 .= "</tr>";
				}

				$page_info5 .= "</tbody>";
				$page_info5 .= "</table>";

			}

			else {

				if (!empty($row_limits['prefsUserEntryLimit'])) {

					$page_info5 .= "<dl class=\"row\">";
					if (!empty($row_limits['prefsUserEntryLimit'])) $page_info5 .= sprintf("<dt class=\"col-7 col-md-4 col-lg-3\">%s:</dt><dd class=\"col-5 col-md-8 col-lg-9\">%s</dd>", $label_entry_limit_participant, $row_limits['prefsUserEntryLimit']);
					$page_info5 .= "</dl>";

				}

			}

			if (!empty($row_limits['prefsUserSubCatLimit'])) {

				$page_info5 .= "<dl class=\"row\">";
				if (!empty($row_limits['prefsUserSubCatLimit'])) $page_info5 .= sprintf("<dt class=\"col-7 col-md-4 col-lg-3\">%s:</dt><dd class=\"col-5 col-md-8 col-lg-9\">%s</dd>", $label_entry_limit_substyle, $row_limits['prefsUserSubCatLimit']);
				$page_info5 .= "</dl>";

			}

			if ((!empty($row_limits['prefsUSCLExLimit'])) && (!empty($row_limits['prefsUSCLEx']))) {
				
				$excepted_styles = explode(",",$row_limits['prefsUSCLEx']);
				
				if (count($excepted_styles) == 1) $sub = $entry_info_text_027; 
				else $sub = $entry_info_text_028;
				
				$page_info5 .= "<dl class=\"row\">";
				$page_info5 .= sprintf("<dt class=\"col-7 col-md-4 col-lg-3\">%s:</dt><dd class=\"col-5 col-md-8 col-lg-9\">%s</dd>", $label_entry_limit_exception, $row_limits['prefsUSCLExLimit']);
				$page_info5 .= sprintf("<dt class=\"col-12 col-md-4 col-lg-3\">%s:</dt>",$label_style_excepted);
				$page_info5 .= "<dd class=\"col-12 col-md-8 col-lg-9\">";	
				$page_info5 .= style_convert($row_limits['prefsUSCLEx'],"7",$base_url,$filter);
				$page_info5 .= "</dd>";
				$page_info5 .= "</dl>";

			}

		}

		if (($_SESSION['contestEntryFee'] > 0) && (($_SESSION['prefsCash'] == "Y") || ($_SESSION['prefsCheck'] == "Y") || ($_SESSION['prefsPaypal'] == "Y"))) {

			// Payment
			$anchor_links[] = $label_pay;
			$anchor_name = str_replace(" ", "-", $label_pay);
			$header1_6 .= sprintf("<a class=\"anchor-offset\" name=\"%s\"></a><h2>%s</h2>",strtolower($anchor_name),$label_pay);
			$page_info6 .= sprintf("<p>%s</p>",$entry_info_text_031);
			$page_info6 .= "<ul>";
			if ($_SESSION['prefsCash'] == "Y") $page_info6 .= sprintf("<li>%s</li>",$entry_info_text_032);
			if ($_SESSION['prefsCheck'] == "Y") $page_info6 .= sprintf("<li>%s <em>%s</em></li>",$entry_info_text_033,$_SESSION['prefsCheckPayee']);
			if ($_SESSION['prefsPaypal'] == "Y") $page_info6 .= sprintf("<li>%s</li>",$entry_info_text_034);
			$page_info6 .= "</ul>";

		}

	}

}

$anchor_links[] = $label_admin_judging_loc;
$anchor_name = str_replace(" ", "-", $label_admin_judging_loc);

$header1_7 .= sprintf("<a class=\"anchor-offset\" name=\"%s\"></a><h2>%s</h2>",strtolower($anchor_name),$label_admin_judging_loc);
if ($totalRows_judging == 0) $page_info7 .= sprintf("<p>%s</p>",$entry_info_text_035);
else {
	
	do {

		if ($row_judging['judgingLocType'] < 2) {

			$page_info7 .= "<p>";

			if (!empty($row_judging['judgingLocName'])) $page_info7 .= "<strong>".$row_judging['judgingLocName']."</strong>";

			if ($row_judging['judgingLocType'] == 0) {

				if ($logged_in) {
					$address = rtrim($row_judging['judgingLocation'],"&amp;KeepThis=true");
					$address = str_replace(' ', '+', $address);
					$driving = "http://maps.google.com/maps?f=q&source=s_q&hl=en&q=".$address;
					$location_link = "http://maps.google.com/maps?f=q&source=s_q&hl=en&q=".$address;
					$location_tooltip = "Map to ".$row_judging['judgingLocName'];
					$page_info7 .= "<br>".$row_judging['judgingLocation'];
				}

				else {
					$location_link = "#";
					$location_tooltip = $entry_info_text_058;
				}

				if (!empty($row_judging['judgingLocation'])) $page_info7 .= "<a href=\"".$location_link."\" target=\"_blank\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$location_tooltip."\"><span class=\"fa fa-map-marker ms-2\"></span></a>";

			}

			if (!empty($row_judging['judgingDate'])) $page_info7 .=  "<br />".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");

			if (!empty($row_judging['judgingDateEnd'])) $page_info7 .=  " ".$sidebar_text_004." ".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDateEnd'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");

			if ($row_judging['judgingLocType'] == 1) {
				$page_info7 .= "<br><em><small>".$row_judging['judgingLocation']."</small></em>";
			}

			if ((!empty($row_judging['judgingLocNotes'])) && ($logged_in)) $page_info7 .= "<br><small><em>".$row_judging['judgingLocNotes']."</em></small>";

			$page_info7 .= "</p>";

		}

		if ($row_judging['judgingLocType'] == 2) {

			$page_info16 .= "<p>";

			if (!empty($row_judging['judgingLocName'])) $page_info16 .= "<strong>".$row_judging['judgingLocName']."</strong>";

			if ($logged_in) {
				$address = rtrim($row_judging['judgingLocation'],"&amp;KeepThis=true");
				$address = str_replace(' ', '+', $address);
				$driving = "http://maps.google.com/maps?f=q&source=s_q&hl=en&q=".$address;
				$location_link = "http://maps.google.com/maps?f=q&source=s_q&hl=en&q=".$address;
				$location_tooltip = "Map to ".$row_judging['judgingLocName'];
				$page_info16 .= "<br>".$row_judging['judgingLocation'];
			}

			else {
				$location_link = "#";
				$location_tooltip = $entry_info_text_058;
			}

			if (!empty($row_judging['judgingLocation'])) $page_info16 .= "<a href=\"".$location_link."\" target=\"_blank\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$location_tooltip."\"><span class=\"fa fa-map-marker ms-2\"></span></a>";

			if (!empty($row_judging['judgingDate'])) $page_info16 .=  "<br />".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");

			if (!empty($row_judging['judgingDateEnd'])) $page_info16 .=  " ".$sidebar_text_004." ".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDateEnd'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");

			if ($row_judging['judgingLocType'] == 1) {
				$page_info16 .= "<br><em><small>".$row_judging['judgingLocation']."</small></em>";
			}

			if ((!empty($row_judging['judgingLocNotes'])) && ($logged_in)) $page_info16 .= "<br><small><em>".$row_judging['judgingLocNotes']."</em></small>";

			$page_info16 .= "</p>";

		}

	} while ($row_judging = mysqli_fetch_assoc($judging));
	
}

if (!empty($page_info16)) {

	$anchor_links[] = $label_non_judging;
	$anchor_name = str_replace(" ", "-", $label_non_judging);
	$header1_16 .= sprintf("<a class=\"anchor-offset\" name=\"%s\"></a><h2>%s</h2>",strtolower($anchor_name),$label_non_judging);

}

// Categories Accepted

if ($row_styles) {

	if ($_SESSION['prefsStyleSet'] == "BA") $page_info8 .= sprintf("<p>%s</p>",$entry_info_text_047);
	else $page_info8 .= sprintf("<p>%s</p>", $entry_info_text_057);

	$style_set = $_SESSION['style_set_short_name'];

	if ($_SESSION['prefsStyleSet'] == "NWCiderCup") {
		$label_styles_accepted = $label_categories_accepted;
		$label_judging_styles = $label_judging_categories;
	}

	if ($entry_window_open < 2) $header1_8 .= sprintf("<a class=\"anchor-offset\" name=\"%s\"></a><h2>%s %s</h2>",strtolower($anchor_name),$style_set,$label_styles_accepted);
	else $header1_8 .= sprintf("<a class=\"anchor-offset\" name=\"%s\"></a><h2>%s %s</h2>",strtolower($anchor_name),$style_set,$label_judging_styles);

	$page_info8 .= "<table class=\"table table-striped table-bordered table-responsive border-dark-subtle\">";
	$page_info8 .= "<tr>";

	$styles_endRow = 0;
	$styles_columns = 3;   // number of columns
	$styles_hloopRow1 = 0; // first row flag

		do {

			if (array_key_exists($row_styles['id'], $styles_selected)) {

				if (($styles_endRow == 0) && ($styles_hloopRow1++ != 0)) $page_info8 .= "<tr>";

				$page_info8 .= "<td width=\"33%\">";

				$style_number = style_number_const($row_styles['brewStyleGroup'],$row_styles['brewStyleNum'],$_SESSION['style_set_display_separator'],0);

				if ($row_styles['brewStyleAtLimit'] == 1) $page_info8 .= sprintf("<span class=\"text-muted\">%s %s</span><i class=\"fa fa-times-circle text-danger-emphasis ms-1 d-print-none\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"%s\"></i>",$style_number,$row_styles['brewStyle'],$entry_info_text_056);

				else {

					$page_info8 .= $style_number." ".$row_styles['brewStyle'];
					if ($row_styles['brewStyleOwn'] == "custom") $page_info8 .= " (Custom Style)";
					if ($row_styles['brewStyleReqSpec'] == 1) $page_info8 .= "<span class=\"fa fa-check-circle text-orange ms-1 d-print-none\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$entry_info_text_048."\"></span>";
					if ($row_styles['brewStyleStrength'] == 1) $page_info8 .= "<span class=\"fa fa-check-circle text-purple ms-1 d-print-none\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$entry_info_text_049."\"></span>";
					if ($row_styles['brewStyleCarb'] == 1) $page_info8 .= "<span class=\"fa fa-check-circle text-teal ms-1 d-print-none\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$entry_info_text_050."\"></span>";
					if ($row_styles['brewStyleSweet'] == 1) $page_info8 .= "<span class=\"fa fa-check-circle text-warning-emphasis ms-1 d-print-none\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$entry_info_text_051."\"></span>";

				}

				$page_info8 .= "</td>";
				$styles_endRow++;

				if ($styles_endRow >= $styles_columns) {
					$styles_endRow = 0;
				}

			}

		} while ($row_styles = mysqli_fetch_assoc($styles));


	if ($styles_endRow != 0) {
			while ($styles_endRow < $styles_columns) {
				$page_info8 .= "<td>&nbsp;</td>";
				$styles_endRow++;
			}
		$page_info8 .= "</tr>";
	}

	$page_info8 .= "</table>";

}

if ($show_entries) {

	// Show bottle acceptance, shipping location, and dropoff locations if open
	// Bottle Acceptance
	
	if (!empty($_SESSION['jPrefsBottleNum'])) $page_info9 .= sprintf("<p><strong>%s: %s</strong></p>", $label_number_bottles, $_SESSION['jPrefsBottleNum']);

	if ((isset($row_contest_info['contestBottles'])) && (($dropoff_window_open < 2) || ($shipping_window_open < 2))) {
		$header1_9 .= sprintf("<a class=\"anchor-offset\" name=\"%s\"></a><h2>%s</h2>",strtolower($anchor_name),$label_entry_acceptance_rules);

		if ((ENABLE_MARKDOWN) && (!is_html($row_contest_info['contestBottles']))) { 
			$page_info9 .= Parsedown::instance()
						   ->setBreaksEnabled(true) # enables automatic line breaks
						   ->setMarkupEscaped(true) # escapes markup (HTML)
						   ->text($row_contest_info['contestBottles']); 
		}
		
		else $page_info9 .= $row_contest_info['contestBottles'];

	}

	// Shipping Locations
	if (((isset($_SESSION['contestShippingAddress'])) && ($shipping_window_open < 2)) && ($_SESSION['prefsShipping'] == 1)) {

		if (!empty($shipping_open)) $entry_info_text_001 = "&mdash;";
		
		$header1_10 .= sprintf("<a class=\"anchor-offset\" name=\"%s\"></a><h2>%s</h2>",strtolower($anchor_name),$label_shipping_info);
		if (!empty($row_contest_dates['contestShippingDeadline'])) $page_info10 .= sprintf("<p>%s <strong class=\"text-success\">%s</strong> %s <strong class=\"text-success\">%s</strong>.</p>",$entry_info_text_036,$shipping_open,$entry_info_text_001,$shipping_closed);
		$page_info10 .= sprintf("<p>%s</p>",$entry_info_text_037);
		$page_info10 .= "<p>";
		$page_info10 .= $_SESSION['contestShippingName'];
		$page_info10 .= "<br>";
		$page_info10 .= $_SESSION['contestShippingAddress'];
		$page_info10 .= "</p>";
		$page_info10 .= sprintf("<h3>%s</h3>",$label_packing_shipping);

		if ((ENABLE_MARKDOWN) && (!is_html($contestRulesJSON['competition_packing_shipping']))) {
			$page_info10 .= Parsedown::instance()
							->setBreaksEnabled(true) # enables automatic line breaks
							->text($contestRulesJSON['competition_packing_shipping']); 
		}
		
		else $page_info10 .= $contestRulesJSON['competition_packing_shipping'];

	}

	// Drop-Off
	if ((($totalRows_dropoff > 0) && ($dropoff_window_open < 2)) && ($_SESSION['prefsDropOff'] == 1)) {

		if (!empty($dropoff_open)) $entry_info_text_001 = "&mdash;";

		if ($totalRows_dropoff == 1) $header1_11 .= sprintf("<a class=\"anchor-offset\" name=\"%s\"></a><h2>%s</h2>",strtolower($anchor_name),$label_drop_off);
		else $header1_11 .= sprintf("<a class=\"anchor-offset\" name=\"%s\"></a><h2>%s</h2>",strtolower($anchor_name),$label_drop_offs);

		if (!empty($row_contest_dates['contestDropoffDeadline'])) $page_info11 .= sprintf("<p>%s <strong class=\"text-success\">%s</strong> %s <strong class=\"text-success\">%s</strong>.</p>",$entry_info_text_043,$dropoff_open,$entry_info_text_001,$dropoff_closed);
		$page_info11 .= "<p>".$dropoff_qualifier_text_001."</p>";
		
		do {

			$page_info11 .= "<p>";
			if (!empty($row_dropoff['dropLocationWebsite'])) $page_info11 .= sprintf("<a class=\"hide-loader\" href=\"%s\" target=\"_blank\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$row_dropoff['dropLocationName']." %s\"><strong>%s</strong></a><span class=\"fa fa-sm fa-external-link ms-2\"></span>",$row_dropoff['dropLocationWebsite'],$label_website,$row_dropoff['dropLocationName']);
			else $page_info11 .= sprintf("<strong>%s</strong>",$row_dropoff['dropLocationName']);
			$page_info11 .= "<br />";
			
			if (empty($row_dropoff['dropLocationNotes'])) {

				$address = rtrim($row_dropoff['dropLocation'],"&amp;KeepThis=true");
				$address = str_replace(' ', '+', $address);
				$location_link = "http://maps.google.com/maps?f=q&source=s_q&hl=en&q=".$address;

			$page_info11 .= sprintf("%s<a class=\"hide-loader\" href=\"%s\" target=\"_blank\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"%s\"><span class=\"fa fa-map-marker ms-2\"></span></a>",$row_dropoff['dropLocation'],$location_link,$entry_info_text_044." ".$row_dropoff['dropLocationName']);
			}

			else {
				$page_info11 .= sprintf("%s<a href=\"#\" data-bs-toggle=\"modal\" data-bs-target=\"#dropoff-loc".$row_dropoff['id']."\" title=\"%s\"><span class=\"fa fa-map-marker ms-2\"></span></a>",$row_dropoff['dropLocation'],$row_dropoff['dropLocation'],$entry_info_text_044." ".$row_dropoff['dropLocationName']);
			}

			$page_info11 .= "<br />";
			$page_info11 .= $row_dropoff['dropLocationPhone'];
			$page_info11 .= "<br />";
			if (!empty($row_dropoff['dropLocationNotes'])) $page_info11 .= sprintf("*<em>%s</em>",$row_dropoff['dropLocationNotes']);

			$page_info11 .= "</p>";

			if (!empty($row_dropoff['dropLocationNotes'])) {

				$address = rtrim($row_dropoff['dropLocation'],"&amp;KeepThis=true");
				$address = str_replace(' ', '+', $address);
				$location_link = "http://maps.google.com/maps?f=q&source=s_q&hl=en&q=".$address;

				$page_info11 .= "<div class=\"modal modal-lg fade\" id=\"dropoff-loc".$row_dropoff['id']."\" tabindex=\"-1\" role=\"dialog\" aria-hidden=\"true\">";
				$page_info11 .= "<div class=\"modal-dialog\">";
				$page_info11 .= "<div class=\"modal-content\">";
				$page_info11 .= "<div class=\"modal-header\">";
				$page_info11 .= sprintf("<h4 class=\"modal-title\">%s &ndash; %s %s</h4>",$label_please_note,$row_dropoff['dropLocationName'],$label_drop_off);
				$page_info11 .= "<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>";
				$page_info11 .= "</div>";
				$page_info11 .= sprintf("<div class=\"modal-body\"><p>%s</p>",$row_dropoff['dropLocationNotes']);
				$page_info11 .= "</div>";
				$page_info11 .= "<div class=\"modal-footer\">";
				$page_info11 .= sprintf("<button type=\"button\" class=\"btn btn-danger\" data-bs-dismiss=\"modal\">%s</button>",$label_cancel);
				$page_info11 .= sprintf("<a href=\"%s\" target=\"_blank\" class=\"hide-loader btn btn-success\">%s</a>",$location_link,$label_understand);
				$page_info11 .= "</div>";
				$page_info11 .= "</div>";
				$page_info11 .= "</div>";
				$page_info11 .= "</div>";
			}

		 } while ($row_dropoff = mysqli_fetch_assoc($dropoff));
		
	}

}

// Best of Show
if (isset($row_contest_info['contestBOSAward'])) {
	
	$header1_12 .= sprintf("<a class=\"anchor-offset\" name=\"%s\"></a><h2>%s</h2>",strtolower($anchor_name),$label_bos);

	if ((ENABLE_MARKDOWN) && (!is_html($row_contest_info['contestBOSAward']))) { 
		$page_info12 .= Parsedown::instance()
					   ->setBreaksEnabled(true) # enables automatic line breaks
					   ->setMarkupEscaped(true) # escapes markup (HTML)
					   ->text($row_contest_info['contestBOSAward']); 
	}
	
	else $page_info12 .= $row_contest_info['contestBOSAward'];

}

// Awards and Awards Ceremony Location
if (isset($row_contest_info['contestAwards'])) {
	
	$header1_13 .= sprintf("<a class=\"anchor-offset\" name=\"%s\"></a><h2>%s</h2>",strtolower($anchor_name),$label_awards);
	if ((ENABLE_MARKDOWN) && (!is_html($row_contest_info['contestAwards']))) { 
		$page_info13 .= Parsedown::instance()
					   ->setBreaksEnabled(true) # enables automatic line breaks
					   ->setMarkupEscaped(true) # escapes markup (HTML)
					   ->text($row_contest_info['contestAwards']); 
	}

	else $page_info13 .= $row_contest_info['contestAwards'];

}

if (isset($_SESSION['contestAwardsLocName'])) {

	$header1_14 .= sprintf("<a class=\"anchor-offset\" name=\"%s\"></a><h2>%s</h2>",strtolower($anchor_name),$label_awards_ceremony);
	$page_info14 .= "<p>";
	$page_info14 .= sprintf("<strong>%s</strong>",$_SESSION['contestAwardsLocName']);

	if (!empty($_SESSION['contestAwardsLocation'])) {

		$address = rtrim($_SESSION['contestAwardsLocation'],"&amp;KeepThis=true");
		$address = str_replace(' ', '+', $address);
		$location_link = "http://maps.google.com/maps?f=q&source=s_q&hl=en&q=".$address;

		$page_info14 .= sprintf("<br />%s<a class=\"hide-loader\" href=\"".$location_link."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Map to ".$_SESSION['contestAwardsLocName']." \" target=\"_blank\"><span class=\"fa fa-map-marker ms-2\"></span></a>",$_SESSION['contestAwardsLocation']);

	}

	if (!empty($_SESSION['contestAwardsLocTime'])) $page_info14 .= sprintf("<br />%s",getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestAwardsLocTime'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time"));
	$page_info14 .= "</p>";
	//$page_info14 .= $anchor_top;
}

if ($show_entries) {
	
	// Circuit Qualification
	if (isset($row_contest_info['contestCircuit'])) {
		
		$anchor_links[] = $label_circuit;
		$anchor_name = str_replace(" ", "-", $label_circuit);
		$header1_15 .= sprintf("<a class=\"anchor-offset\" name=\"%s\"></a><h2>%s</h2>",strtolower($anchor_name),$label_circuit);

		if ((ENABLE_MARKDOWN) && (!is_html($row_contest_info['contestCircuit']))) {
			$page_info15 .= Parsedown::instance()
					   ->setBreaksEnabled(true) # enables automatic line breaks
					   ->setMarkupEscaped(true) # escapes markup (HTML)
					   ->text($row_contest_info['contestCircuit']);
		}
		
		else $page_info15 .= $row_contest_info['contestCircuit'];

	}
}

// Show rules if winner display is active (moved from default page)
if (($judging_past == 0) && ($registration_open == 2) && ($entry_window_open == 2)) {
	
	$header1_17 .= sprintf("<a class=\"anchor-offset\" name=\"%s\"></a><h2>%s</h2>",strtolower($anchor_name),$label_rules);

	if ((ENABLE_MARKDOWN) && (!is_html($contestRulesJSON['competition_rules']))) {
		$page_info17 .= Parsedown::instance()
						->setBreaksEnabled(true) # enables automatic line breaks
						->text($contestRulesJSON['competition_rules']); 
	}
	else $page_info17 .= $contestRulesJSON['competition_rules'];
	//$page_info17 .= $anchor_top;
}


// --------------------------------------------------------------
// Display
// --------------------------------------------------------------

// Display anchor links
echo "<p class=\"d-print-none\"><a class=\"anchor-offset\" name=\"top-page\"></a>";

/*
$anchor_link_display = "";

if (is_array($anchor_links)) {

	sort($anchor_links);

	$anchor_link_display .= "<p><ul class=\"nav nav-pills small\">";
	$anchor_link_display .= "<li role=\"presentation\" class=\"dropdown\">";
	$anchor_link_display .= "<a class=\"d-print-none btn btn-default dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">";
	$anchor_link_display .= $label_jump_to." <span class=\"fa fa-caret-down\"></span>";
	$anchor_link_display .= "</a>";
	$anchor_link_display .= "<ul class=\"dropdown-menu\">";

	foreach ($anchor_links as $link) {
		$anchor_link_name = str_replace(" ", "-", $link);
		$anchor_link_display .= "<li role=\"presentation\">";
		$anchor_link_display .= "<a href=\"#".strtolower($anchor_link_name)."\">".$link."</a>";
		$anchor_link_display .= "</li>";
	}

	$anchor_link_display .= "</ul>";
	$anchor_link_display .= "</li>";
	$anchor_link_display .= "</ul></p>";

}


// Display Registration Window
// echo $anchor_link_display;
// echo $header1_2;
// echo $page_info2;

// Display Entry Window
// echo $header1_3;
// echo $page_info3;

*/

// Display Entry Fees
echo "<div class=\"reveal-element\">";
echo $header1_4;
echo $page_info4;
echo "</div>";

// Display Entry Limits
echo "<div class=\"reveal-element\">";
echo $header1_5;
echo $page_info5;
echo "</div>";

// Display Payment Info
echo "<div class=\"reveal-element\">";
echo $header1_6;
echo $page_info6;
echo "</div>";

// Display Rules if winners displays on the home page
// echo $header1_17;
// echo $page_info17;

// Display Categories Accepted
echo "<div class=\"reveal-element\">";
echo $header1_8;
echo $page_info8;
echo "</div>";

// Display Entry Acceptance Rules
// echo $header1_9;
// echo $page_info9;

// Display Drop-Off Locations and Acceptance Dates
echo "<div class=\"reveal-element\">";
echo $header1_11;
echo $page_info11;
echo "</div>";

// Display Shipping Location and Acceptance Dates
echo "<div class=\"reveal-element\">";
echo $header1_10;
echo $page_info10;
echo "</div>";

// Display Judging Dates
echo "<div class=\"reveal-element\">";
echo $header1_7;
echo $page_info7;
echo "</div>";

// Display Non-Judging Dates
echo "<div class=\"reveal-element\">";
echo $header1_16;
echo $page_info16;
echo "</div>";

// Display Best of Show
echo "<div class=\"reveal-element\">";
echo $header1_12;
echo $page_info12;
echo "</div>";

// Display Awards and Awards Ceremony Location
echo "<div class=\"reveal-element\">";
echo $header1_13;
echo $page_info13;
echo $header1_14;
echo $page_info14;
echo "</div>";

// Display Circuit Qualification
echo "<div class=\"reveal-element\">";
echo $header1_15;
echo $page_info15;
echo "</div>";

// echo $style_info_modals;
?>