<?php
/**
 * Module:      brewer_entries.sec.php
 * Description: This module displays the user's entries and related data
 *
 */

$header1_1 = "";
$page_info1 = "";
$discount_fee_message = "";
$pay_button = "";
$entry_output = "";
$entry_output_cards = "";
$print_bottle_labels = FALSE;
$multiple_bottle_ids = FALSE;
$discount = FALSE;

if (($dropoff_window_open == 1) || ($shipping_window_open == 1) || ($entry_window_open == 1)) $print_bottle_labels = TRUE;
if ((($_SESSION['prefsEntryForm'] == "5") || ($_SESSION['prefsEntryForm'] == "6")) && $print_bottle_labels) $multiple_bottle_ids = TRUE;
if ((isset($_SESSION['loginUsername'])) && ($_SESSION['brewerDiscount'] == "Y") && ($_SESSION['contestEntryFeePasswordNum'] != "")) $discount = TRUE;

$pay_button .= sprintf("<a class=\"btn btn-primary hide-loader %s\" href=\"#pay-fees\"><i class=\"fa fa-lg fa-money-bill me-2\"></i>%s</a>", $pay_button_disable, $label_pay);


// Build Header
$header1_1 .= sprintf("<a class=\"anchor-offset\" name=\"entries\"></a><h2>%s</h2>",$label_entries);
$total_not_paid = total_not_paid_brewer($_SESSION['user_id']);

$page_info1 .= "<div class=\"row g-2 mb-3\">";
$page_info1 .= "<div class=\"col-12 col-lg-6\">";
$page_info1 .= "<div class=\"card h-100 sponsor-card-bg bg-light-subtle border-secondary-subtle\">";
$page_info1 .= "<div class=\"card-body\"><small>";
$page_info1 .= "<ul class=\"list-unstyled m-0 p-0\">";
if (!empty($_SESSION['jPrefsBottleNum'])) $page_info1 .= sprintf("<li><strong>%s:</strong> %s</li>", $label_number_bottles, $_SESSION['jPrefsBottleNum']);
$page_info1 .= sprintf("<li><strong>%s:</strong> %s</li>",$label_entry_edit_deadline,$entry_edit_deadline_date);
if (((!empty($row_limits['prefsUserEntryLimit'])) || (!empty($row_limits['prefsUserEntryLimitDates']))) && (!$comp_entry_limit) && (!$comp_paid_entry_limit) && (!$disable_pay)) {
	
	$page_info1 .= sprintf("<li><strong>%s:</strong> %s</li>",$label_entries_remaining,$remaining_entries);

	if ($incremental) {

		// Default to overall entry deadline
		$enforce_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestEntryDeadline'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "date-time");

		if (time() < $limit_date_1) $enforce_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $limit_date_1, $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "date-time");
		
		if (!empty($limit_date_2)) {
			if ((time() > $limit_date_1) && (time() < $limit_date_2)) $enforce_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $limit_date_2, $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "date-time");
		}

		if (!empty($limit_date_3)) {
			if ((time() > $limit_date_2) && (time() < $limit_date_3)) $enforce_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $limit_date_3, $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "date-time");
		}

		if (!empty($limit_date_4)) {
			if ((time() > $limit_date_3) && (time() < $limit_date_4)) $enforce_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $limit_date_4, $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "date-time");
		}

		$page_info1 .= sprintf("<li><strong>%s:</strong> %s</li>",$label_entry_limit_enforced,$enforce_date);
		
	}

}
$page_info1 .= "</ul>";
$page_info1 .= "</small></div>";
$page_info1 .= "</div>";
$page_info1 .= "</div>";

$page_info1 .= "<div class=\"col-12 col-lg-6\">";
$page_info1 .= "<div class=\"card h-100 sponsor-card-bg bg-light-subtle text-dark border-secondary-subtle\">";
$page_info1 .= "<div class=\"card-body\"><small>";
$page_info1 .= "<ul class=\"list-unstyled m-0 p-0\">";
$page_info1 .= sprintf("<li><strong>%s:</strong> %s</li>",$label_confirmed_entries,$totalRows_log_confirmed);
if (($totalRows_log - $totalRows_log_confirmed) > 0) $page_info1 .= sprintf("<li class=\"text-danger-emphasis\"><strong>%s:</strong> %s<i class=\"fa fa-exclamation-circle ms-1\"></i></li>",$label_unconfirmed_entries,$totalRows_log - $totalRows_log_confirmed);
if (!$comp_paid_entry_limit) $page_info1 .= sprintf("<li><strong>%s:</strong> %s</li>",$label_unpaid_confirmed_entries,$total_not_paid);
$page_info1 .= sprintf("<li><strong>%s:</strong> %s%s</li>",$label_entry_fees_to_pay,$currency_symbol,number_format($total_to_pay,2));
if ($discount) $page_info1 .= sprintf("<li><strong>%s:</strong> %s%s</li>",ucwords($pay_text_007),$currency_symbol,number_format($_SESSION['contestEntryFeePasswordNum'],2));
$page_info1 .= "</ul>";
$page_info1 .= "</small></div>";
$page_info1 .= "</div>";
$page_info1 .= "</div>";

$page_info1 .= "</div>";

// Build Warnings
$warnings = "";
if (($totalRows_log > 0) && ($action != "print")) {

	$entries_unconfirmed = entries_unconfirmed($_SESSION['user_id']);
	$entries_unconfirmed_sum = array_sum($entries_unconfirmed);

	if (($totalRows_log - $totalRows_log_confirmed) > 0) {
		$warnings .= "<div class=\"alert alert-warning\">";
		$warnings .= sprintf("<span class=\"fa fa-lg fa-exclamation-triangle\"></span> <strong>%s</strong> %s",$brewer_entries_text_001,$brewer_entries_text_002);
		if ($_SESSION['prefsPayToPrint'] == "Y") $warnings .= sprintf(" %s",$brewer_entries_text_003);
		$warnings .= "</div>";
	}

	if (entries_no_special($_SESSION['user_id'])) {
		$warnings .= sprintf("<div class=\"alert alert-warning\"><span class=\"fa fa-lg fa-exclamation-triangle\"></span> <strong>%s</strong> %s</div>",$brewer_entries_text_004,$brewer_entries_text_005);
	}

	if (($_SESSION['prefsPayToPrint'] == "Y") && ($judging_past > 0) && (!$disable_pay) && (!$comp_paid_entry_limit)) {
		$warnings .= sprintf("<div class=\"alert alert-warning\"><span class=\"fa fa-lg fa-exclamation-triangle\"></span> <strong>%s!</strong> %s</div>",$label_please_note, $alert_text_085);
	}

}

// Build user's entry information
if ($_SESSION['prefsEval'] == 1) {

	$evals = array();
	// Check which evaluations exist
	$query_eval_exists = sprintf("SELECT DISTINCT eid FROM %s",$prefix."evaluation");
	$eval_exists = mysqli_query($connection,$query_eval_exists) or die (mysqli_error($connection));
	$row_eval_exists = mysqli_fetch_assoc($eval_exists);
	$totalRows_eval_exists = mysqli_num_rows($eval_exists);

	if ($totalRows_eval_exists > 0) {
		do {
			$evals[] = $row_eval_exists['eid'];
		} while ($row_eval_exists = mysqli_fetch_assoc($eval_exists));
	}
		 
}

if ($totalRows_log > 0) {
	
	do {

		// include (DB.'styles.db.php');
		$print_forms_link = "";
		$edit_link = "";
		$required_info = "";
		$collapse_info = "";
		$entry_update_date = "";
		$allergen_info = "";
		$style_disp = "";
		
		if (!empty($row_log['brewUpdated'])) $entry_update_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], strtotime($row_log['brewUpdated']), $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time-no-gmt");

		$st_disp_list = style_number_const($row_log['brewCategorySort'],$row_log['brewSubCategory'],$_SESSION['style_set_display_separator'],0);
		if (!empty($st_disp_list)) $style_disp .= $st_disp_list.": ";
		$style_disp .= $row_log['brewStyle'];

		$brewInfo = "";
		if (!empty($row_log['brewInfo'])) {
			if (($_SESSION['prefsStyleSet'] == "BJCP2021") && ($row_log['brewCategorySort'] == "02") && ($row_log['brewSubCategory'] == "A")) $brewInfo .= "<li><strong>".$label_regional_variation.":</strong> ".str_replace("^", " | ", $row_log['brewInfo'])."</li>";
			else $brewInfo .= "<li><strong>".$label_required_info.":</strong> ".str_replace("^", " | ", $row_log['brewInfo'])."</li>";
		}

		if (!empty($brewInfo)) $required_info .= $brewInfo;

		if (!empty($row_log['brewInfoOptional'])) {
			$required_info .= "<li><strong>".$label_optional_info.":</strong> ".$row_log['brewInfoOptional']."</li>";
		}

		$cider_mead_req_info = "";
		if (!empty($row_log['brewMead1'])) $cider_mead_req_info .= "<li><strong>".$label_carbonation.":</strong> ".$row_log['brewMead1']."</li>";
		if (!empty($row_log['brewMead2'])) $cider_mead_req_info .= "<li><strong>".$label_sweetness.":</strong> ".$row_log['brewMead2']."</li>";
		if (!empty($row_log['brewSweetnessLevel'])) $cider_mead_req_info .= "<li><strong>".$label_final_gravity.":</strong> ".$row_log['brewSweetnessLevel']."</li>";
		if (!empty($row_log['brewMead3'])) $cider_mead_req_info .= "<li><strong>".$label_strength.":</strong> ".$row_log['brewMead3']."</li>";
		if (!empty($cider_mead_req_info)) $required_info .= $cider_mead_req_info;
		if (!empty($row_log['brewABV'])) $required_info .= "<li><strong>".$label_abv.":</strong> ".$row_log['brewABV']."%</li>";

		if (($_SESSION['prefsStyleSet'] == "NWCiderCup") && (!empty($row_log['brewJuiceSource']))) {
		  
			$juice_src_arr = json_decode($row_log['brewJuiceSource'],true);
			$juice_src_disp = "";
			$juice_src_disp_other = "";

			if (is_array($juice_src_arr['juice_src'])) {
				$juice_src_disp .= implode(", ",$juice_src_arr['juice_src']);
				$juice_src_disp .= ", ";
			}

			if ((isset($juice_src_arr['juice_src_other'])) && (is_array($juice_src_arr['juice_src_other']))) {
				$juice_src_disp_other .= implode(", ",$juice_src_arr['juice_src_other']);
				$juice_src_disp_other .= ", ";
			}

			$juice_src_disp = rtrim($juice_src_disp,",");
			$juice_src_disp = rtrim($juice_src_disp,", ");
			$juice_src_disp_other = rtrim($juice_src_disp_other,",");
			$juice_src_disp_other = rtrim($juice_src_disp_other,", ");

			$required_info .= "<li><strong>".$label_juice_source."</strong>: ".$juice_src_disp."</li>";
			$required_info .= "<li><strong>".$label_fruit_add_source."</strong>: ".$juice_src_disp_other."</li>";
		
		}

		if ((!empty($row_log['brewPouring'])) && ((!empty($row_log['brewStyleType'])) && ($row_log['brewStyleType'] == 1))) {
			$pouring_arr = json_decode($row_log['brewPouring'],true);
			$required_info .= "<li><strong>".$label_pouring.":</strong> ".$pouring_arr['pouring']."</li>";
			if ((isset($pouring_arr['pouring_notes'])) && (!empty($pouring_arr['pouring_notes']))) $required_info .= "<li><strong>".$label_pouring_notes.":</strong> ".$pouring_arr['pouring_notes']."</li>";
			$required_info .= "<li><strong>".$label_rouse_yeast.":</strong> ".$pouring_arr['pouring_rouse']."</li>";
		}

		if (!empty($row_log['brewPossAllergens'])) {
			$allergen_info .= $label_possible_allergens.": ".$row_log['brewPossAllergens'];
		}

		if (!empty($row_log['brewPackaging'])) $required_info .= "<li><strong>".$label_packaging.":</strong> ".$packaging_display[$row_log['brewPackaging']]."</li>";

		$entry_number = sprintf("%06s",$row_log['id']);
		$judging_number = sprintf("%06s",$row_log['brewJudgingNumber']);

		$entry_style = $row_log['brewCategorySort']."-".$row_log['brewSubCategory'];
		$entry_name = html_entity_decode($row_log['brewName'],ENT_QUOTES|ENT_XML1,"UTF-8");
	    $entry_name = htmlentities($entry_name,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5,"UTF-8");

		// Build Entry Table Body and Cards
	    $entry_tr_style = "";
		if ((check_special_ingredients($entry_style,$_SESSION['prefsStyleSet'])) && ($row_log['brewInfo'] == "") && ($action != "print")) $entry_tr_style = "warning";
		if ((is_array($entries_unconfirmed)) && (in_array($row_log['id'],$entries_unconfirmed))) $entry_tr_style = "warning";

		$entry_output .= "<tr class=\"bg-".$entry_tr_style."\">";
		$entry_output .= "<td class=\"\">";
		$entry_output .= $entry_number;
		$entry_output .= "</td>";
		
		$scoresheet = FALSE;
		$scoresheet_es = FALSE;
		$scoresheet_pdf = FALSE;
		$scoresheet_link = "";
		$scoresheet_link_eval = "";

		if (($show_scores) && ($show_scoresheets)) {

			if ($_SESSION['prefsEval'] == 1) {
						
				if (in_array($row_log['id'], $evals)) {

					/*
					if (HOSTED) $query_style = sprintf("SELECT id,brewStyleType FROM %s WHERE brewStyleVersion='%s'AND brewStyleGroup='%s' AND brewStyleNum='%s' UNION ALL SELECT id,brewStyleType FROM %s WHERE brewStyleVersion='%s'AND brewStyleGroup='%s' AND brewStyleNum='%s'", "bcoem_shared_styles", $_SESSION['prefsStyleSet'], $row_log['brewCategorySort'], $row_log['brewSubCategory'], $prefix."styles", $_SESSION['prefsStyleSet'], $row_log['brewCategorySort'], $row_log['brewSubCategory']);
					else 
					*/
					$query_style = sprintf("SELECT id,brewStyleType FROM %s WHERE brewStyleVersion='%s'AND brewStyleGroup='%s' AND brewStyleNum='%s'",$prefix."styles",$_SESSION['prefsStyleSet'],$row_log['brewCategorySort'],$row_log['brewSubCategory']);
					
					$style = mysqli_query($connection,$query_style) or die (mysqli_error($connection));
					$row_style = mysqli_fetch_assoc($style);

					$scoresheet = TRUE;
					$scoresheet_es = TRUE;
					$print_link = $base_url."includes/output.inc.php?section=evaluation&amp;go=default&amp;view=all&amp;id=".$row_log['id'];
					$scoresheet_link_eval = "<a data-fancybox data-type=\"iframe\" class=\"modal-window-link hide-loader\" href=\"".$print_link."\" data-bs-toggle=\"tooltip\" data-bs-placement=\"top\" title=\"".$brewer_entries_text_025." &ndash; &ldquo;".$entry_name.".&rdquo;\"><i class=\"fa fa-lg fa-file-text me-1\"></i></a>&nbsp;&nbsp;";
				}
			
			}
			
			// Check whether scoresheet file exists, and, if so, provide link.
			$scoresheet_file_name_entry = sprintf("%06s",$entry_number).".pdf";
			$scoresheet_file_name_judging = strtolower($judging_number).".pdf";
			$scoresheetfile_entry = USER_DOCS.$scoresheet_file_name_entry;
			$scoresheetfile_judging = USER_DOCS.$scoresheet_file_name_judging;

			if ((file_exists($scoresheetfile_entry)) && ($_SESSION['prefsDisplaySpecial'] == "E")) $scoresheet_file_name = $scoresheet_file_name_entry;
			elseif ((file_exists($scoresheetfile_judging)) && ($_SESSION['prefsDisplaySpecial'] == "J")) $scoresheet_file_name = $scoresheet_file_name_judging;
			else $scoresheet_file_name = "";

			if (!empty($scoresheet_file_name)) {

				/**
				 * The pseudo-random number and the corresponding name of the 
				 * temporary file are defined each time. The temporary file is created
				 * only when the user selects the icon to access the scoresheet.
				 */

				$scoresheet = TRUE;
				$random_num_str = random_generator(8,2);
				$random_file_name = $random_num_str.".pdf";
				$scoresheet_random_file_relative = "user_temp/".$random_file_name;
				$scoresheet_random_file = USER_TEMP.$random_file_name;
				$scoresheet_random_file_html = $base_url.$scoresheet_random_file_relative;

				if (($scoresheet) && (!empty($scoresheet_file_name))) {
					$scoresheet_link .= "<a target=\"_blank\" class=\"hide-loader\" href=\"".$base_url."includes/output.inc.php?section=scoresheet";
					$scoresheet_link .= "&amp;scoresheetfilename=".urlencode(obfuscateURL($scoresheet_file_name,$_SESSION['encryption_key']));
					$scoresheet_link .= "&amp;randomfilename=".urlencode(obfuscateURL($random_file_name,$_SESSION['encryption_key']))."&amp;download=true";
					$scoresheet_link .= sprintf("\" data-bs-toggle=\"tooltip\" title=\"%s &ldquo;".$entry_name."&rdquo;.\" data-download=\"true\">",$brewer_entries_text_006);
					$scoresheet_link .= "<span class=\"fa fa-lg fa-file-pdf me-1\"></a>&nbsp;&nbsp;";
				}
			
			}

			/**
			 * Clean up temporary scoresheets created for other brewers, 
			 * when they are at least 1 minute old (just to avoid 
			 * problems when two entrants try accessing their scoresheets 
			 * at practically the same time, and clean up previously 
			 * created scoresheets for the same brewer, regardless of 
			 * how old they are.
			 */

			$tempfiles = array_diff(scandir(USER_TEMP), array('..', '.'));
			
			foreach ($tempfiles as $file) {
				
				if (!empty($scoresheet_file_name_judging)) {
					if ((filectime(USER_TEMP.$file) < time() - 1*60) || ((strpos($file, $scoresheet_file_name_judging) !== FALSE))) {
						unlink(USER_TEMP.$file);
					}
				}

				if (!empty($scoresheet_file_name_entry)) {
					if ((filectime(USER_TEMP.$file) < time() - 1*60) || ((strpos($file, $scoresheet_file_name_entry) !== FALSE))) {
						unlink(USER_TEMP.$file);
					}
				}

			}
		
		}

		if ($show_scores) {
			$entry_output .= "<td class=\"\">";
			$entry_output .= $judging_number;
			$entry_output .= "</td>";
		}

		// Brew Name
		$entry_output .= "<td>";
		$entry_output .= "<div class=\"mb-2\">";
		$entry_output .= $entry_name;

		if (!empty($required_info)) {
			$entry_output .= " <a class=\"hide-loader d-print-none\" role=\"button\" data-bs-toggle=\"collapse\" href=\"#collapseEntryInfo".$row_log['id']."\" aria-expanded=\"false\" aria-controls=\"collapseEntryInfo".$row_log['id']."\"><span class=\"fa fa-info-circle\"></span></a> ";
			$entry_output .= "<div class=\"mt-2 collapse d-print-none\" id=\"collapseEntryInfo".$row_log['id']."\">";
			$entry_output .= "<div class=\"small border-info bg-info-subtle text-info-emphasis card card-body\">";
			$entry_output .= "<ul class='list-unstyled pb-0'>";
	    	$entry_output .= $required_info;
	    	$entry_output .= "</ul>";
	    	$entry_output .= "</div>";
	    	$entry_output .= "</div>";
		}

		$entry_output .= "</div>";

		if (!empty($allergen_info)) {
			$entry_output .= "<div style=\"padding: .6em\" class=\"badge text-bg-danger\">";
			$entry_output .= $allergen_info;
	    	$entry_output .= "</div>";
		}

		if (!empty($row_log['brewCoBrewer'])) $entry_output .= sprintf("<br><em class=\"small\">%s: ".$row_log['brewCoBrewer']."</em>",$label_cobrewer);

		$entry_output .= "<div class=\" d-lg-none card card-body mt-2 d-print-none\">";
		$entry_output .= "<p class=\"small\">";
		$entry_output .= $label_entry_number.": ".$entry_number."<br>";

		if ($show_scores) {
			$entry_output .= $label_judging_number.": ".$judging_number."<br>";
			if (minibos_check($row_log['id'],$judging_scores_db_table)) $entry_output .= "<br>".$label_mini_bos." <i class=\"fa fa-sm fa-check text-success\"></i>";
		}

		else {
			if ($row_log['brewConfirmed'] == 0) $entry_output .= "<br><span class=\"text-danger\">".$label_confirmed." <i class=\"fa fa-sm fa-times\"></i></span>";
			else $entry_output .= "<br><span class=\"text-success\">".$label_confirmed." <i class=\"fa fa-fw fa-check\"></i></span>";
			if ($row_log['brewPaid'] == 0) $entry_output .= "<br><span class=\"text-danger\">".$label_paid." <i class=\"fa fa-sm fa-times\"></i></span>";
			else $entry_output .= "<br><span class=\"text-success\">".$label_paid." <i class=\"fa fa-sm fa-fw fa-check\"></i></span>";
			if ($row_log['brewReceived'] == 0) $entry_output .= "<br><span class=\"text-danger\">".$label_received." <i class=\"fa fa-sm fa-times\"></i></span>";
			else $entry_output .= "<br><span class=\"text-success\">".$label_received." <i class=\"fa fa-sm fa-fw fa-check\"></i></span>";
		}
		
		if (!empty($row_log['brewUpdated'])) $entry_output .= "<br>".$label_updated." ".$entry_update_date;
		$entry_output .= "</p>";
		$entry_output .= "</div>";
		$entry_output .= "</td>";

		// Style
		$entry_output .= "<td class=\"\">";

		$entry_output .= "<span class=\"visually-hidden\">".$entry_style."</span>";
		$entry_output .= $style_disp;
		
		if (empty($row_log['brewCategorySort'])) $entry_output .= sprintf("<strong class=\"text-danger\">%s</strong>",$brewer_entries_text_007);

		$entry_output .= "</td>";

		if (!$show_scores) {
			$entry_output .= "<td class=\"\" nowrap>";
			if ($row_log['brewConfirmed'] == "0")  $entry_output .= "<span class=\"fa fa-lg fa-exclamation-circle text-danger\"></span>";
			elseif ((check_special_ingredients($entry_style,$_SESSION['prefsStyleSet'])) && ($row_log['brewInfo'] == "")) $entry_output .= "<span class=\"fa fa-lg fa-exclamation-circle text-danger\"></span>";
			else $entry_output .= yes_no($row_log['brewConfirmed'],$base_url,1);
			$entry_output .= "</td>";

			$entry_output .= "<td class=\"\" nowrap>";
			$entry_output .= yes_no($row_log['brewPaid'],$base_url,1);
			$entry_output .= "</td>";

			$entry_output .= "<td class=\"\" nowrap>";
			$entry_output .= yes_no($row_log['brewReceived'],$base_url,1);
			$entry_output .= "</td>";

			$entry_output .= "<td class=\"\" nowrap>";
			if (!empty($row_log['brewUpdated'])) $entry_output .= "<span class=\"visually-hidden\">".strtotime($row_log['brewUpdated'])."</span>".$entry_update_date; else $entry_output .= "&nbsp;";
			$entry_output .= "</td>";


			$multi_print_link = "";
			if (($multiple_bottle_ids) && (!$judging_started)) {
				$entry_output .= "<td class=\"d-print-none\">";
				
				if (((pay_to_print($_SESSION['prefsPayToPrint'],$row_log['brewPaid'])) && (!$comp_paid_entry_limit)) || (($comp_paid_entry_limit) && ($row_log['brewPaid'] == 1))) {
					$multi_print_link .= "<input class=\"form-check-input entry-print\" name=\"id[]\" type=\"checkbox\" value=\"".$row_log['id']."\">";
				}

				else $multi_print_link .= "<input class=\"form-check-input\" type=\"checkbox\" value=\"\" disabled>";

				$entry_output .= "<div class=\"form-check form-check-inline\">";
				$entry_output .= $multi_print_link;
				//$entry_output .= "<label class=\"form-check-label\" ></label></div>";
				$entry_output .= "</td>";
			}

		}

		// Display if Closed, Judging Dates have passed, winner display is enabled, and the winner display delay time period has passed
		if ($show_scores) {

			$medal_winner = winner_check($row_log['id'],$judging_scores_db_table,$judging_tables_db_table,$brewing_db_table,$_SESSION['prefsWinnerMethod']);
			
			$winner_place = strpos($medal_winner, ':');
			$winner_place = substr($medal_winner, 0, $winner_place);
			if (preg_match("~[0-9]+~", $medal_winner)) {
				$winner_place = preg_replace("/[^0-9\s.-]/", "", $winner_place);
			}
			
			$score = score_check($row_log['id'],$judging_scores_db_table);
	 		$entry_mini_bos = FALSE;
	 		if (minibos_check($row_log['id'],$judging_scores_db_table)) $entry_mini_bos = TRUE;

			$entry_output .= "<td>";
			$entry_output .= $score;
			$entry_output .= "</td>";

			$entry_output .= "<td>";
			if ($entry_mini_bos) {
				if ($action != "print") $entry_output .= "<span class =\"fa fa-lg fa-check text-success\"></span>";
				else $entry_output .= $label_yes;
			}
			else $entry_output .= "&nbsp;";
			$entry_output .= "</td>";

			$entry_output .= "<td>";
			$entry_output .= $medal_winner;
			$entry_output .= "</td>";

		}

		// Build Actions Links
		// Edit
		if (($row_log['brewCategory'] < 10) && (preg_match("/^[[:digit:]]+$/",$row_log['brewCategory']))) $brewCategory = "0".$row_log['brewCategory'];
		else $brewCategory = $row_log['brewCategory'];

		if ((($entry_window_open == 1) && ($row_log['brewReceived'] == 0)) || (($entry_window_open != 1) && ($row_log['brewReceived'] == 0) && (time() < $entry_edit_deadline))) {

			$edit_link .= "<a role=\"button\" href=\"".$base_url."index.php?section=brew&amp;action=edit&amp;id=".$row_log['id'];
			if ($row_log['brewConfirmed'] == 0) $edit_link .= "&amp;msg=1-".$brewCategory."-".$row_log['brewSubCategory'];
			$edit_link .= "&amp;view=".$brewCategory."-".$row_log['brewSubCategory'];
			$edit_link .= "\" data-bs-toggle=\"tooltip\" title=\"Edit ".$entry_name."\">";
			$edit_link .= "<i class=\"fa fa-fw fa-lg fa-pencil\"></i>";
			$edit_link .= "</a>";

		}

		else $edit_link .= "<a class=\"hide-loader\" role=\"button\" data-bs-toggle=\"tooltip\" data-bs-title=\"".$brewer_entries_text_020."\" data-bs-placement=\"top\" data-bs-container=\"body\"><i class=\"fa fa-fw fa-lg fa-pencil text-muted\"></i></a>";

		// Print Forms
		$alt_title = "";
		$alt_title .= "Print ";
		if ((!NHC) && (($_SESSION['prefsEntryForm'] == "B") || ($_SESSION['prefsEntryForm'] == "M") || ($_SESSION['prefsEntryForm'] == "U") || ($_SESSION['prefsEntryForm'] == "N"))) $alt_title .= sprintf("%s ",$brewer_entries_text_008);
		$alt_title .= sprintf("%s ",$brewer_entries_text_009);
		$alt_title .= "for ".$entry_name;

		if (!$multiple_bottle_ids) {

			if (((pay_to_print($_SESSION['prefsPayToPrint'],$row_log['brewPaid'])) && (!$comp_paid_entry_limit)) || (($comp_paid_entry_limit) && ($row_log['brewPaid'] == 1))) {
					
					$print_forms_link .= "<a data-fancybox data-type=\"iframe\" class=\"modal-window-link hide-loader\" href=\"".$base_url."includes/output.inc.php?section=entry-form&amp;action=print&amp;";
					$print_forms_link .= "id=".$row_log['id'];
					$print_forms_link .= "&amp;bid=".$_SESSION['user_id'];
					$print_forms_link .= "\" data-bs-toggle=\"tooltip\" title=\"".$alt_title."\">";
					$print_forms_link .= "<span class=\"fa fa-lg fa-print\"></span></a>&nbsp;&nbsp;";

			}

			else {
				$print_forms_link .= "<a class=\"hide-loader\" role=\"button\" data-bs-toggle=\"tooltip\" data-bs-title=\"";
				if ($comp_paid_entry_limit) $print_forms_link .= $brewer_entries_text_019." ".$pay_text_034;
				else $print_forms_link .= $brewer_entries_text_018;
				$print_forms_link .= "\" data-bs-placement=\"top\" data-bs-container=\"body\"><i class=\"fa fa-lg fa-print text-muted me-1\"></i></a>";
			}

		}

		if ($comp_entry_limit) $warning_append = sprintf("\n%s",$brewer_entries_text_011); else $warning_append = "";

		$delete_alt_title = sprintf("%s %s",$label_delete, $entry_name);
		$delete_warning = sprintf("%s %s? %s.",$label_delete, $entry_name, $label_undone);
		
		if ((($entry_window_open == 1) && ($row_log['brewReceived'] == 0)) || (($entry_window_open != 1) && ($row_log['brewReceived'] == 0) && (time() < $entry_edit_deadline))) {
			$delete_link = sprintf("<a class=\"hide-loader\" role=\"button\" data-bs-toggle=\"tooltip\" title=\"%s\" data-confirm-title=\"%s\" data-confirm-cancel=\"%s\" data-confirm-proceed=\"%s\" href=\"%s\" data-confirm=\"%s.\"><i class=\"fa fa-fw fa-lg fa-trash-can\"></i></a>",$delete_alt_title,$label_please_confirm,$label_cancel,$label_delete,$base_url."includes/process.inc.php?section=".$section."&amp;go=".$go."&amp;dbTable=".$brewing_db_table."&amp;action=delete&amp;id=".$row_log['id'],$delete_warning);
		}

		else {
			$delete_link = sprintf("<a class=\"hide-loader\" role=\"button\" data-bs-toggle=\"tooltip\" data-bs-placement=\"top\" data-bs-title=\"%s\"><i class=\"fa fa-fw fa-lg fa-trash-can text-muted\"></i></a>",$brewer_entries_text_015);
		}

		$entry_output .= "<td nowrap class=\"d-print-none\">";

		if ($scoresheet) {
			if (!empty($scoresheet_link_eval)) $entry_output .= $scoresheet_link_eval;
			if (!empty($scoresheet_link)) $entry_output .= $scoresheet_link;
		}

		// If no judging date specified or if a judging start date/time has not past
		// Suspend edit and delete as it may affect judging

		if ($judging_started) {
			$edit_link = "<a class=\"hide-loader\" role=\"button\" data-bs-toggle=\"tooltip\" data-bs-title=\"".$brewer_entries_text_020."\" data-bs-placement=\"top\" data-bs-container=\"body\"><i class=\"fa fa-lg fa-pencil text-muted\"></i></a>";
			$entry_output .= "<span class=\"me-1\">".$edit_link."</span>";
			
			$delete_link = sprintf("<a class=\"hide-loader\" role=\"button\" data-bs-toggle=\"tooltip\" data-bs-placement=\"top\" title=\"%s\"><i class=\"fa fa-lg fa-trash-can text-muted\"></i></a>",$brewer_entries_text_015);
			$entry_output .= "<span class=\"me-1\">".$delete_link."</span>";
		}

		// If a judging date has not passed yet
		else {

			if (!$show_scores) {
				$entry_output .= "<span class=\"me-1\">".$edit_link."</span>";
				if (!$judging_started) $entry_output .= "<span class=\"me-1\">".$print_forms_link."</span>";
			}

			// If the entry window is open, display delete
			if ($entry_window_open == 1) {
				if ($row_log['brewPaid'] == 1) {
					if ($_SESSION['contestEntryFee'] > 0) $entry_output .= sprintf("<a class=\"hide-loader\" role=\"button\" data-bs-toggle=\"tooltip\" data-bs-placement=\"top\" title=\"%s\"><i class=\"fa fa-lg fa-trash-can text-muted me-1\"></i></a>",$brewer_entries_text_015);
					else $entry_output .= "<span class=\"me-1\">".$delete_link."</span>";
				}
				else $entry_output .= "<span class=\"me-1\">".$delete_link."</span>";
			} 

			else $entry_output .= "<span class=\"me-1\">".$delete_link."</span>";

		}

		$scoresheet_mixed = "";
		if (($scoresheet_es) && ($scoresheet_pdf)) { 
			$scoresheet_mixed .= sprintf("<a class=\"hide-loader\" role=\"button\" data-bs-toggle=\"tooltip\" data-bs-placement=\"top\" data-bs-title=\"%s\"><i class=\"fa fa-question-circle text-muted\"></i></a>",$brewer_entries_text_026);
			$entry_output .= $scoresheet_mixed;
		}


		$entry_output .= "</td>";
		$entry_output .= "</tr>";

		// Card Output
		$entry_output_cards .= "<div class=\"col\">";
		$entry_output_cards .= "<div class=\"card h-100 sponsor-card-bg bg-light-subtle border-secondary-subtle\">";
		$entry_output_cards .= "<div class=\"card-header bg-dark border-dark pt-3 pb-3\">";

		if (!empty($medal_winner)) $entry_output_cards .= "<div class=\"position-absolute top-0 start-50 translate-middle badge bg-black border border-secondary text-white rounded-pill winner-place-pill\"><span class=\"fw-normal\"> ".display_place($winner_place,2)."</span></div>";

		if (empty($row_log['brewCategorySort'])) $style_disp = sprintf("<span class=\"text-danger\">%s</span>", $brewer_entries_text_007);
		
		//if (!empty($required_info)) $entry_output_cards .= "<a class=\"hide-loader small\" role=\"button\" data-bs-toggle=\"collapse\" href=\"#collapseEntryInfo".$row_log['id']."-card\" aria-expanded=\"false\" aria-controls=\"collapseEntryInfo".$row_log['id']."\"><i class=\"fa fa-info-circle me-2\"></i></a>";
		$entry_output_cards .= "<span class=\"text-white m-0 ps-0 pe-0 pt-2 py-2 fs-5 fw-bolder\">".$entry_name."</span>";
		$entry_output_cards .= "</div>";
		$entry_output_cards .= "<div class=\"card-header bg-secondary-subtle pt-2 pb-2\">";
		$entry_output_cards .= "<span class=\"m-0 ps-0 pe-0 fs-6 fw-bold text-secondary-emphasis\">".$style_disp."</span>";
		$entry_output_cards .= "</div>";
		
		$entry_output_cards .= "<div class=\"card-body\">";
		//$entry_output_cards .= "<header class=\"sponsor-header\"><small>".$style_disp."</small></header>";

		/*
		$entry_output_cards .= "<div class=\"mt-2 collapse\" id=\"collapseEntryInfo".$row_log['id']."-card\">";
		$entry_output_cards .= "<div class=\"small border-info bg-info-subtle text-info-emphasis card card-body \">";
		$entry_output_cards .= "<small>";
		$entry_output_cards .= "<ul class=\"list-unstyled\">";
    	$entry_output_cards .= $required_info;
    	$entry_output_cards .= "</ul>";
    	$entry_output_cards .= "</small>";
    	$entry_output_cards .= "</div>";
    	$entry_output_cards .= "</div>";
    	*/

		// Entry Info
		$entry_output_cards .= "<small>";
		$entry_output_cards .= "<ul class=\"list-unstyled\">";
		$entry_output_cards .= sprintf("<li><strong>%s:</strong> %s</li>", $label_entry_number, $entry_number);

		if ($show_scores) {
			$entry_output_cards .= sprintf("<li><strong>%s:</strong> %s</li>",$label_judging_number, $judging_number);
			$entry_output_cards .= sprintf("<li><strong>%s:</strong> %s</li>",$label_score, $score);
			if (minibos_check($row_log['id'],$judging_scores_db_table)) $entry_output_cards .= sprintf("<li><strong>%s:</strong> <i class=\"fa fa-sm fa-check text-success\"></i></li>",$label_mini_bos);
		}

		$entry_output_cards .= $required_info;

		if (!$show_scores) {
			$entry_output_cards .= sprintf("<li><strong>%s:</strong> %s</li>", $label_updated, $entry_update_date);
			if ((is_array($entries_unconfirmed)) && (in_array($row_log['id'],$entries_unconfirmed))) $entry_output_cards .= sprintf("<li class=\"text-danger\"><strong>%s:</strong> %s</li>", $label_confirmed, yes_no($row_log['brewConfirmed'],$base_url,4));
			else $entry_output_cards .= sprintf("<li><strong>%s:</strong> %s</li>", $label_confirmed, yes_no($row_log['brewConfirmed'],$base_url,4));
			$entry_output_cards .= sprintf("<li><strong>%s:</strong> %s</li>", $label_paid, yes_no($row_log['brewPaid'],$base_url,4));
			$entry_output_cards .= sprintf("<li><strong>%s:</strong> %s</li>", $label_received, yes_no($row_log['brewReceived'],$base_url,4));
			if (!empty($allergen_info)) {
				$entry_output_cards .= "<div style=\"padding: .6em\" class=\"mt-2 badge text-bg-danger\">";
				$entry_output_cards .= $allergen_info;
		    	$entry_output_cards .= "</div>";
			}
		}
		
		if ($scoresheet) {
			$entry_output_cards .= sprintf("<li><strong>%s:</strong> %s%s %s</li>", $label_scoresheet, $scoresheet_link_eval, $scoresheet_link, $scoresheet_mixed);
		}

		$entry_output_cards .= "</ul>";
		$entry_output_cards .= "</small>";

		$entry_output_cards .= "</div>"; // End of card-body

		if (!$show_scores) {

			// Buttons in Footer
			$entry_output_cards .= "<div class=\"card-footer\" style=\"border:none; background-color: inherit;\">";
			$entry_output_cards .= "<div class=\"row\">";

			// Edit Button
			$entry_output_cards .= "<div class=\"col-4\">";
			$entry_output_cards .= sprintf("<div class=\"text-center\">%s</div>", $edit_link);
			$entry_output_cards .= "</div>";

			// -- Print Button
			$entry_output_cards .= "<div class=\"col-4\">";
			if (!$judging_started) {
				if ($multiple_bottle_ids) $entry_output_cards .= sprintf("<div class=\"text-center\"><i class=\"fa fa-fw fa-lg fa-print me-1\"></i>%s</div>", $multi_print_link);
				else $entry_output_cards .= sprintf("<div class=\"text-center\">%s</div>", $print_forms_link);
			}
			$entry_output_cards .= "</div>";

			// -- Delete Button		
			$entry_output_cards .= "<div class=\"col-4\">";
			$entry_output_cards .= sprintf("<div class=\"text-center\">%s</div>", $delete_link);
			$entry_output_cards .= "</div>";
								
			$entry_output_cards .= "</div>"; // end row
			$entry_output_cards .= "</div>"; // end of footer

		}

		$entry_output_cards .= "</div>"; // end card
		$entry_output_cards .= "</div>"; // end col

	} while ($row_log = mysqli_fetch_assoc($log));

} // end if ($totalRows_log > 0)

// --------------------------------------------------------------
// Display
// --------------------------------------------------------------
echo $header1_1;

// Display Warnings and Entry Message
if (($totalRows_log > 0) && ($action != "print")) {
	echo $warnings;
}

// Display links and other information
if (($action != "print") && ($entry_window_open > 0)) {
	if (($totalRows_log == 0) && ($entry_window_open >= 1)) echo sprintf("<p>%s</p>",$brewer_entries_text_014);
	if (!$show_scores) echo $page_info1; 
}
	
if (($totalRows_log > 0) && ($entry_window_open >= 1)) {

?>

<form name="form1" method="post" action="<?php echo $base_url; ?>includes/output.inc.php?section=entry-form-multi" target="_blank" class="hide-loader-form-submit">

	<div class="row g-2 d-print-none mb-3">
		<div class="col-sm-12 col-md-3">
			<div class="d-grid mb-1">
				<?php 
				if (($show_scores) && ($totalRows_log > 0)) {
					$link_results_export = $base_url."includes/output.inc.php?section=export-personal-results&amp;id=".$_SESSION['brewerID'];
					echo sprintf("<a href=\"%s\" class=\"btn btn-success\" target=\"_blank\"><i class=\"fa fa-lg fa-file-csv me-2\"></i>%s</a>",$link_results_export, $label_results_export_personal);
				} ?>

				<?php if (!$show_scores) { ?>
				<a class="btn btn-primary <?php echo $add_entry_button_disable; ?>" href="<?php echo $add_entry_link; ?>"><i class="fa fa-plus-circle me-2"></i><?php echo $label_add_entry; ?></a>
				<?php } ?>
			</div>
		</div>
		<?php if (!$show_scores) { ?>
		<div class="col-sm-12 col-md-3">
			<div class="d-grid mb-1">
				<?php echo $pay_button; ?>
			</div>
		</div>
		<div class="col-sm-12 col-md-3">
			<div class="d-grid mb-1">
			<?php if ((!$show_scores) && ($multiple_bottle_ids)) { ?>
				<button type="submit" id="btn" class="btn btn-primary" disabled data-bs-toggle="popover" data-bs-container="body" data-bs-trigger="hover focus" data-bs-placement="auto" data-bs-title="<?php echo $brewer_entries_text_024; ?>" data-bs-html="true" data-bs-content="<?php echo sprintf("%s %s",$brewer_entries_text_023,$bottle_labels_008); ?>"><i class='fa fa-print me-2'></i><?php echo $brewer_entries_text_024; ?></button>
			<?php } ?>
			</div>
		</div>
		<?php } ?>
		<div class="col-sm-12 col-md-3 ms-auto">
			<div class="d-grid mb-1">
				<div class="btn-group" role="group"><button id="toggle-entry-cards" class="btn btn-primary" type="button"><i class="fa fa-lg fa-th-large"></i></button><button id="toggle-entry-table" class="btn btn-primary" type="button"><i class="fa fa-lg fa-list"></i></button>
				</div>
			</div>
		</div>
	</div>

	
	<div id="entry-cards" class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-2 mb-4 d-print-none">
		<?php echo $entry_output_cards; ?>
	</div>
	<div id="entry-table" class="mt-2 table-responsive d-print-block">
		<table class="table table-bordered table-striped border-dark-subtle" id="sortable">
			<thead class="table-dark">
				<tr>
				  	<th width="5%"><?php if ($show_scores) echo $label_entry ?>#</th>
				    <?php if ($show_scores) { ?>
				    <th width="5%"><?php echo $label_judging; ?>#</th>
				    <?php } ?>
				  	<th width="25%">Name</th>
				  	<th width="25%"><?php echo $label_style; ?></th>
				    <?php if (!$show_scores) { ?>
				  	<th width="5%"><?php echo $label_confirmed; ?></th>
				  	<th width="5%"><?php echo $label_paid; ?></th>
				    <th width="5%" nowrap><?php echo $label_received; ?> <a role="button" class="hide-loader d-print-none" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="auto" data-bs-trigger="hover focus" data-bs-title="<?php echo $label_received." ".$label_entries." ".$label_info; ?>" data-bs-content="<?php echo $brewer_entries_text_017; ?>"><i class="fa fa-question-circle ml-1"></i></a></th>
				    <th width="10%" class=""><?php echo $label_updated; ?></th>
				    <?php } ?>
				  	<?php if ($show_scores) { ?>
				  	<th width="5%"><?php echo $label_score; ?></th>
				    <th width="5%" nowrap><?php echo $label_mini_bos; ?></th>
				  	<th width="5%"><?php echo $label_winner; ?></th>
				  	<?php } ?>
				  	<?php if ((!$show_scores) && ($multiple_bottle_ids)) { ?>
				  	<?php if (!$judging_started) { ?>
				    <th class="d-print-none" width="7%" nowrap>
				    	<input class="form-check-input d-print-none" type="checkbox" id="select_all">
				    	<a class="hide-loader d-print-none" style="cursor: pointer;" data-bs-toggle="popover" data-bs-container="body" data-bs-trigger="hover focus" data-bs-placement="auto" data-bs-title="<?php echo $brewer_entries_text_024; ?>" data-bs-content="<?php echo $brewer_entries_text_021; ?>"><i class="fa fa-question-circle hide-loader ms-1"></i></a>
				    </th>
				    <?php } ?>
					<?php } ?>
				    <th class="d-print-none"><?php echo $label_actions; ?></th>
				</tr>
			</thead>
			<tbody class="table-group-divider">
				<?php echo $entry_output; ?>
			</tbody>
		</table>
	</div>
</form>
<?php } // end if (($totalRows_log > 0) && ($entry_window_open >= 1))
if ($entry_window_open == 0) echo sprintf("<p>%s %s.</p>",$brewer_entries_text_013, $entry_open);
?>