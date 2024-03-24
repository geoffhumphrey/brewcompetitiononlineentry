<?php
/**
 * Module:      brewer_entries.sec.php
 * Description: This module displays the user's entries and related data
 *
 */

$primary_page_info = "";
$primary_links = "";
$secondary_links = "";
$header1_1 = "";
$page_info1 = "";
$header1_2 = "";
$page_info2 = "";
$table_head1 = "";
$table_body1 = "";

// Page specific variables
$entry_message = "";
$remaining_message = "";
$discount_fee_message = "";
$entry_fee_message = "";
$nhc_message_1 = "";
$nhc_message_2 = "";
$add_entry_link = "";
$beer_xml_link = "";
$print_list_link = "";
$pay_fees_message = "";
$pay_button = "";

$print_bottle_labels = FALSE;
if (($dropoff_window_open == 1) || ($shipping_window_open == 1) || ($entry_window_open == 1)) $print_bottle_labels = TRUE;

$multiple_bottle_ids = FALSE;
if ((($_SESSION['prefsEntryForm'] == "5") || ($_SESSION['prefsEntryForm'] == "6")) && $print_bottle_labels) $multiple_bottle_ids = TRUE;

// Build Headers
if (($total_to_pay > 0) && (!$disable_pay)) $pay_button .= sprintf("<a class=\"btn btn-success pull-right\" href=\"%s\"><i class=\"fa fa-lg fa-money\"></i> %s</a>",$link_pay, $label_pay); 
if ($show_scores) {
	$link_results_export = $base_url."includes/output.inc.php?section=export-personal-results&amp;id=".$_SESSION['brewerID'];
	$link_results_export_mhp = $base_url."includes/output.inc.php?section=export-personal-results&amp;filter=MHP&amp;id=".$_SESSION['brewerID'];
	$pay_button .= "<div class=\"btn-group pull-right\">";
	$pay_button .= sprintf("<button type=\"button\" class=\"btn btn-success dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><i class=\"fa fa-lg fa-file-excel\" style=\"margin-right: 8px;\"></i>%s<span class=\"caret\" style=\"margin-left: 8px;\"></span></button>",$label_results_export_personal);
	$pay_button .= "<ul class=\"dropdown-menu\">";
	$pay_button .= sprintf("<li class=\"small\"><a class=\"hide-loader\" href=\"%s\" target=\"_blank\">%s</a></li>",$link_results_export,$label_general);
	$pay_button .= sprintf("<li class=\"small\"><a class=\"hide-loader\" href=\"%s\" target=\"_blank\">Master Homebrewer Program</a></li>",$link_results_export_mhp);
	$pay_button .= "</ul>";
	$pay_button .= "</div>";
}
$header1_1 .= "<div class=\"row\">";
$header1_1 .= "<div class=\"col col-xs-6 col-sm-9\">";
$header1_1 .= sprintf("<a class=\"anchor-offset\" name=\"entries\"></a><h2>%s</h2>",$label_entries);
$header1_1 .= "</div>";
$header1_1 .= "<div class=\"col col-xs-6 col-sm-3\">";
$header1_1 .= "<div style=\"margin-top: 10px;\">".$pay_button."</div>";
$header1_1 .= "</div>";
$header1_1 .= "</div>";
if ((!empty($_SESSION['jPrefsBottleNum'])) && (!$show_scores)) $header1_1 .= sprintf("<p>%s: %s</p>", $label_number_bottles, $_SESSION['jPrefsBottleNum']);

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

$entry_output = "";

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

		// Vars
		$print_forms_link = "";
		$edit_link = "";
		$required_info = "";
		$collapse_info = "";
		$entry_update_date = "";
		if (!empty($row_log['brewUpdated'])) $entry_update_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], strtotime($row_log['brewUpdated']), $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time-no-gmt");

		$st_disp_list = style_number_const($row_log['brewCategorySort'],$row_log['brewSubCategory'],$_SESSION['style_set_display_separator'],0);

		if ((!empty($row_log['brewInfo'])) || (!empty($row_log['brewMead1'])) || (!empty($row_log['brewMead2'])) || (!empty($row_log['brewMead3']))) {
			$brewInfo = "";
			if (!empty($row_log['brewInfo'])) $brewInfo .= str_replace("^", " | ", $row_log['brewInfo']);
			if (!empty($row_log['brewMead1'])) $brewInfo .= "<br>".$row_log['brewMead1'];
			if (!empty($row_log['brewMead2'])) $brewInfo .= "<br>".$row_log['brewMead2'];
			if (!empty($row_log['brewMead3'])) $brewInfo .= "<br>".$row_log['brewMead3'];
			if (($_SESSION['prefsStyleSet'] == "BJCP2021") && ($row_log['brewCategorySort'] == "02") && ($row_log['brewSubCategory'] == "A")) $required_info .= "<p><strong>".$label_regional_variation.":</strong> ".$brewInfo."</p>";
			else $required_info .= "<p><strong>".$label_required_info.":</strong> ".$brewInfo."</p>";
		}

		if (!empty($row_log['brewInfoOptional'])) {
			$required_info .= "<p><strong>".$label_optional_info.":</strong> ".$row_log['brewInfoOptional']."</p>";
		}

		if (!empty($row_log['brewPossAllergens'])) {
			$required_info .= "<p><strong>".$label_possible_allergens.":</strong> ".$row_log['brewPossAllergens']."</p>";
		}

		$entry_number = sprintf("%06s",$row_log['id']);
		$judging_number = sprintf("%06s",$row_log['brewJudgingNumber']);

		$entry_style = $row_log['brewCategorySort']."-".$row_log['brewSubCategory'];
		$entry_name = html_entity_decode($row_log['brewName'],ENT_QUOTES|ENT_XML1,"UTF-8");
	    $entry_name = htmlentities($entry_name,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5,"UTF-8");

		// Build Entry Table Body

		if ((check_special_ingredients($entry_style,$_SESSION['prefsStyleSet'])) && ($row_log['brewInfo'] == "") && ($action != "print")) $entry_tr_style = "warning";
		else $entry_tr_style = "";
		if ((is_array($entries_unconfirmed)) && (in_array($row_log['id'],$entries_unconfirmed))) $entry_tr_style = "warning";
		else $entry_tr_style = "";

		$entry_output .= "<tr class=\"".$entry_tr_style."\">";
		$entry_output .= "<td class=\"hidden-xs hidden-sm hidden-md\">";
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
					$scoresheet_link_eval = "<a id=\"modal_window_link\" class=\"hide-loader\" href=\"".$print_link."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$brewer_entries_text_025." &ndash; &ldquo;".$entry_name.".&rdquo;\"><i class=\"fa fa-lg fa-file-text\"></i></a>&nbsp;&nbsp;";
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
					$scoresheet_link .= sprintf("\" data-toggle=\"tooltip\" title=\"%s &ldquo;".$entry_name."&rdquo;.\">",$brewer_entries_text_006);
					$scoresheet_link .= "<span class=\"fa fa-lg fa-file-pdf-o\"></a>&nbsp;&nbsp;";
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
			$entry_output .= "<td class=\"hidden-xs hidden-sm hidden-md\">";
			$entry_output .= $judging_number;
			$entry_output .= "</td>";
		}

		// Brew Name
		$entry_output .= "<td>";
		$entry_output .= $entry_name;

		if (!empty($required_info)) $entry_output .= " <a class=\"hide-loader\" role=\"button\" data-toggle=\"collapse\" data-target=\"#collapseEntryInfo".$row_log['id']."\" aria-expanded=\"false\" aria-controls=\"collapseEntryInfo".$row_log['id']."\"><span class=\"fa fa-info-circle\"></span></a> ";

		if (!empty($required_info)) {
			$entry_output .= "<div style=\"margin-top: 8px;\" class=\"collapse small alert alert-info\" id=\"collapseEntryInfo".$row_log['id']."\">";
	    	$entry_output .= $required_info;
	    	$entry_output .= "</div>";
		}

		if (!empty($row_log['brewCoBrewer'])) $entry_output .= sprintf("<br><em class=\"small\">%s: ".$row_log['brewCoBrewer']."</em>",$label_cobrewer);

		$entry_output .= "<p class=\"well small hidden-lg\" style=\"margin-top: 10px; padding:10px;\">";

		$entry_output .= $label_entry_number.": ".$entry_number."<br>";

		if ($show_scores) {
			$entry_output .= $label_judging_number.": ".$judging_number."<br>";
		}

		if (!empty($st_disp_list)) $entry_output .= $st_disp_list.": ";
		$entry_output .= $row_log['brewStyle'];

		if (!$show_scores) {
			if ($row_log['brewConfirmed'] == 0) $entry_output .= "<br><span class=\"text-danger\">".$label_confirmed." <i class=\"fa fa-sm fa-fw fa-times\"></i></span>";
			else $entry_output .= "<br><span class=\"text-success\">".$label_confirmed." <i class=\"fa fa-fw fa-check\"></i></span>";
			if ($row_log['brewPaid'] == 0) $entry_output .= "<br><span class=\"text-danger\">".$label_paid." <i class=\"fa fa-sm fa-fw fa-times\"></i></span>";
			else $entry_output .= "<br><span class=\"text-success\">".$label_paid." <i class=\"fa fa-sm fa-fw fa-check\"></i></span>";
			if ($row_log['brewReceived'] == 0) $entry_output .= "<br><span class=\"text-danger\">".$label_received." <i class=\"fa fa-sm fa-fw fa-times\"></i></span>";
			else $entry_output .= "<br><span class=\"text-success\">".$label_received." <i class=\"fa fa-sm fa-fw fa-check\"></i></span>";
		}
		
		if (!empty($row_log['brewUpdated'])) $entry_output .= "<br>".$label_updated." ".$entry_update_date;
		$entry_output .= "</p>";
		$entry_output .= "</td>";

		// Style
		$entry_output .= "<td class=\"hidden-xs hidden-sm hidden-md\">";

		$entry_output .= "<span class=\"hidden\">".$entry_style."</span>";
		if (!empty($st_disp_list)) $entry_output .= $st_disp_list.": ";
		$entry_output .= $row_log['brewStyle'];
		
		if (empty($row_log['brewCategorySort'])) $entry_output .= sprintf("<strong class=\"text-danger\">%s</strong>",$brewer_entries_text_007);

		$entry_output .= "</td>";

		if (!$show_scores) {
			$entry_output .= "<td class=\"hidden-xs hidden-sm hidden-md\">";
			if ($row_log['brewConfirmed'] == "0")  $entry_output .= "<span class=\"fa fa-lg fa-exclamation-circle text-danger\"></span>";
			elseif ((check_special_ingredients($entry_style,$_SESSION['prefsStyleSet'])) && ($row_log['brewInfo'] == "")) $entry_output .= "<span class=\"fa fa-lg fa-exclamation-circle text-danger\"></span>";
			else $entry_output .= yes_no($row_log['brewConfirmed'],$base_url,1);
			$entry_output .= "</td>";

			$entry_output .= "<td class=\"hidden-xs hidden-sm hidden-md\">";
			$entry_output .= yes_no($row_log['brewPaid'],$base_url,1);
			$entry_output .= "</td>";

			$entry_output .= "<td class=\"hidden-xs hidden-sm hidden-md\">";
			$entry_output .= yes_no($row_log['brewReceived'],$base_url,1);
			$entry_output .= "</td>";

			$entry_output .= "<td class=\"hidden-xs hidden-sm hidden-md\">";
			if (!empty($row_log['brewUpdated'])) $entry_output .= "<span class=\"hidden\">".strtotime($row_log['brewUpdated'])."</span>".$entry_update_date; else $entry_output .= "&nbsp;";
			$entry_output .= "</td>";


			if ($multiple_bottle_ids) {
				$entry_output .= "<td>";
				$entry_output .= "<div class=\"checkbox\"><label>";
				if (((pay_to_print($_SESSION['prefsPayToPrint'],$row_log['brewPaid'])) && (!$comp_paid_entry_limit)) || (($comp_paid_entry_limit) && ($row_log['brewPaid'] == 1))) $entry_output .= "<input class=\"entry-print\" name=\"id[]\" type=\"checkbox\" value=\"".$row_log['id']."\">";
				else $entry_output .= "<span class=\"fa fa-lg fa-times text-danger\"></span>";
				$entry_output .= "</label></div>";
				$entry_output .= "</td>";
			}

		}

		// Display if Closed, Judging Dates have passed, winner display is enabled, and the winner display delay time period has passed
		if ($show_scores) {

			$medal_winner = winner_check($row_log['id'],$judging_scores_db_table,$judging_tables_db_table,$brewing_db_table,$_SESSION['prefsWinnerMethod']);
			if (NHC) $admin_adv = winner_check($row_log['id'],$judging_scores_db_table,$judging_tables_db_table,$brewing_db_table,$row_log['brewWinner']);
			$winner_place = preg_replace("/[^0-9\s.-:]/", "", $medal_winner);
	 		$score = score_check($row_log['id'],$judging_scores_db_table);

			$entry_output .= "<td>";
			$entry_output .= $score;
			$entry_output .= "</td>";

			$entry_output .= "<td class=\"hidden-xs\">";
			if (minibos_check($row_log['id'],$judging_scores_db_table)) {
				if ($action != "print") $entry_output .= "<span class =\"fa fa-lg fa-check text-success\"></span>";
				else $entry_output .= $label_yes;
			}
			else $entry_output .= "&nbsp;";
			$entry_output .= "</td>";

			$entry_output .= "<td>";
			$entry_output .= $medal_winner;
			if ((NHC) && ($prefix != "final_")) $enter_output .= $admin_adv;
			$entry_output .= "</td>";

		}

		// Build Actions Links
		// Edit
		if (($row_log['brewCategory'] < 10) && (preg_match("/^[[:digit:]]+$/",$row_log['brewCategory']))) $brewCategory = "0".$row_log['brewCategory'];
		else $brewCategory = $row_log['brewCategory'];

		if ((($entry_window_open == 1) && ($row_log['brewReceived'] == 0)) || (($entry_window_open != 1) && ($row_log['brewReceived'] == 0) && (time() < $row_contest_dates['contestDropoffDeadline']))) {

			$edit_link .= "<a href=\"".$base_url."index.php?section=brew&amp;action=edit&amp;id=".$row_log['id'];
			if ($row_log['brewConfirmed'] == 0) $edit_link .= "&amp;msg=1-".$brewCategory."-".$row_log['brewSubCategory'];
			$edit_link .= "&amp;view=".$brewCategory."-".$row_log['brewSubCategory'];
			$edit_link .= "\" data-toggle=\"tooltip\" title=\"Edit ".$entry_name."\">";
			$edit_link .= "<span class=\"fa fa-lg fa-pencil\"></a>&nbsp;&nbsp;";

		}

		else $edit_link .= "<span data-toggle=\"tooltip\" title=\"".$brewer_entries_text_020."\" data-placement=\"auto top\" data-container=\"body\" class=\"fa fa-lg fa-pencil text-muted\"></span>&nbsp;&nbsp;";

		// Print Forms
		$alt_title = "";
		$alt_title .= "Print ";
		if ((!NHC) && (($_SESSION['prefsEntryForm'] == "B") || ($_SESSION['prefsEntryForm'] == "M") || ($_SESSION['prefsEntryForm'] == "U") || ($_SESSION['prefsEntryForm'] == "N"))) $alt_title .= sprintf("%s ",$brewer_entries_text_008);
		$alt_title .= sprintf("%s ",$brewer_entries_text_009);
		$alt_title .= "for ".$entry_name;

		if (!$multiple_bottle_ids) {

			if (((pay_to_print($_SESSION['prefsPayToPrint'],$row_log['brewPaid'])) && (!$comp_paid_entry_limit)) || (($comp_paid_entry_limit) && ($row_log['brewPaid'] == 1))) {
					
					$print_forms_link .= "<a id=\"modal_window_link\" class=\"hide-loader\" href=\"".$base_url."includes/output.inc.php?section=entry-form&amp;action=print&amp;";
					$print_forms_link .= "id=".$row_log['id'];
					$print_forms_link .= "&amp;bid=".$_SESSION['user_id'];
					$print_forms_link .= "\" data-toggle=\"tooltip\" title=\"".$alt_title."\">";
					$print_forms_link .= "<span class=\"fa fa-lg fa-print\"></span></a>&nbsp;&nbsp;";

			}

			else {
				$print_forms_link .= "<span data-toggle=\"tooltip\" title=\"";
				if ($comp_paid_entry_limit) $print_forms_link .= $brewer_entries_text_019." ".$pay_text_034;
				else $print_forms_link .= $brewer_entries_text_018;
				$print_forms_link .= "\" data-placement=\"auto top\" data-container=\"body\" class=\"fa fa-lg fa-print text-muted\"></span>&nbsp;&nbsp;";
			}

		}

		// Print Recipe
		$print_recipe_link = sprintf("<a id=\"modal_window_link\" class=\"hide-loader\" href=\"".$base_url."includes/output.inc.php?section=entry-form&amp;action=print&amp;go=recipe&amp;id=".$row_log['id']."&amp;bid=".$_SESSION['brewerID']."\" title=\"%s ".$entry_name."\"><span class=\"fa fa-lg fa-book\"><span></a>&nbsp;&nbsp;",$brewer_entries_text_010);

		if ($comp_entry_limit) $warning_append = sprintf("\n%s",$brewer_entries_text_011); else $warning_append = "";

		$delete_alt_title = sprintf("%s %s",$label_delete, $entry_name);
		$delete_warning = sprintf("%s %s - %s.",$label_delete, $entry_name, strtolower($label_undone));
		
		if ((($entry_window_open == 1) && ($row_log['brewReceived'] == 0)) || (($entry_window_open != 1) && ($row_log['brewReceived'] == 0) && (time() < $row_contest_dates['contestDropoffDeadline']))) {
			$delete_link = sprintf("<a class=\"hide-loader\" data-toggle=\"tooltip\" title=\"%s\" href=\"%s\" data-confirm=\"%s.\"><span class=\"fa fa-lg fa-trash-o\"></a>",$delete_alt_title,$base_url."includes/process.inc.php?section=".$section."&amp;go=".$go."&amp;dbTable=".$brewing_db_table."&amp;action=delete&amp;id=".$row_log['id'],$delete_warning);
		}

		else {
			$delete_link = sprintf("<span class=\"fa fa-lg fa-trash-o text-muted\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"%s\" href=\"#\"></span>",$brewer_entries_text_015);
		}

		$entry_output .= "<td nowrap class=\"hidden-print\">";

		if ($scoresheet) {
			if (!empty($scoresheet_link_eval)) $entry_output .= $scoresheet_link_eval;
			if (!empty($scoresheet_link)) $entry_output .= $scoresheet_link;
		}

		// If no judging date specified or if a judging start date/time has not past
		// Suspend edit and delete as it may affect judging

		if ($judging_started) {
			$entry_output .= "<span data-toggle=\"tooltip\" title=\"".$brewer_entries_text_020."\" data-placement=\"auto top\" data-container=\"body\" class=\"fa fa-lg fa-pencil text-muted\"></span>&nbsp;&nbsp;";
			$entry_output .= sprintf("<span class=\"fa fa-lg fa-trash-o text-muted\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"%s\" href=\"#\"></span>",$brewer_entries_text_015);
		}

		// If a judging date has not passed yet
		else {

			if (!$show_scores) {
				$entry_output .= $edit_link;
				$entry_output .= $print_forms_link;
			}

			// If the entry window is open, display delete
			if ($entry_window_open == 1) {
				if ($row_log['brewPaid'] == 1) {
					if ($_SESSION['contestEntryFee'] > 0) $entry_output .= sprintf("<span class=\"fa fa-lg fa-trash-o text-muted\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"%s\" href=\"#\"></span>",$brewer_entries_text_015);
					else $entry_output .= $delete_link;
				}
				else $entry_output .= $delete_link;
			} 

			else $entry_output .= $delete_link;

		}

		if (($scoresheet_es) && ($scoresheet_pdf)) $entry_output .= sprintf("<span class=\"fa fa-question-circle text-muted hide-loader\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"%s\" href=\"#\"></span>",$brewer_entries_text_026);

		$entry_output .= "</td>";
		$entry_output .= "</tr>";

	} while ($row_log = mysqli_fetch_assoc($log));
} // end if ($totalRows_log > 0)

// --------------------------------------------------------------
// Display
// --------------------------------------------------------------
echo $header1_1;

// Display Warnings and Entry Message
if (($totalRows_log > 0) && ($action != "print")) {
	echo $warnings;
	echo $entry_message;
}

// Display links and other information
if (($action != "print") && ($entry_window_open > 0)) {
	echo $primary_links;
	echo $page_info1;
	echo $page_info2;
}
if (($totalRows_log == 0) && ($entry_window_open >= 1)) echo sprintf("<p>%s</p>",$brewer_entries_text_014);
if (($totalRows_log > 0) && ($entry_window_open >= 1)) {

?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {

	 	$( ".entry-print" ).on("click", function() {
			if($(".entry-print:checked").length > 0) {
				$('#btn').prop('disabled', false);
			}
			else {
				$('#btn').prop('disabled', true);
			}
		});

		$('.entry-print').change(function(){
		    //uncheck "select all", if one of the listed checkbox item is unchecked
		    if(false == $(this).prop("checked")){ //if this item is unchecked
		        $("#select_all").prop('checked', false); //change "select all" checked status to false
		    }
		    //check "select all" if all checkbox items are checked
		    if ($('.checkbox:checked').length == $('.checkbox').length ){
		        $("#select_all").prop('checked', true);
		    }
		});

		$("#select_all").change(function () {
    		$("input:checkbox").prop('checked', $(this).prop("checked"));
    		if($(".entry-print:checked").length > 0) {
				$('#btn').prop('disabled', false);
			}
			else {
				$('#btn').prop('disabled', true);
			}
		});

		$('#sortable').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']],
			"aoColumns": [

				null,
				<?php if ($show_scores) { ?>
				null,
				<?php } ?>
				null,
				null,
				<?php if (!$show_scores) { ?>
					null,
				null,
				null,
				null,
				<?php if ($multiple_bottle_ids) { ?>{ "asSorting": [  ] },<?php } ?>
				<?php } ?>
				<?php if ($show_scores) { ?>
				null,
				{ "asSorting": [  ] },
				null,
				<?php } ?>
				<?php if ($action != "print") { ?>
				{ "asSorting": [  ] }
				<?php } ?>
				]
			} );
		} );
</script>
<form name="form1" method="post" action="<?php echo $base_url; ?>includes/output.inc.php?section=entry-form-multi" target="_blank" class="hide-loader-form-submit">
<table class="table table-responsive table-striped table-bordered dataTable" id="sortable">
<thead>
 <tr>
  	<th width="5%" class="hidden-xs hidden-sm hidden-md"><?php if ($show_scores) echo $label_entry ?>#</th>
    <?php if ($show_scores) { ?>
    <th class="hidden-xs hidden-sm hidden-md"><?php echo $label_judging; ?>#</th>
    <?php } ?>
  	<th>Name</th>
  	<th class="hidden-xs hidden-sm hidden-md" width="15%"><?php echo $label_style; ?></th>
    <?php if (!$show_scores) { ?>
  	<th width="5%" class="hidden-xs hidden-sm hidden-md"><?php echo $label_confirmed; ?></th>
  	<th width="5%" class="hidden-xs hidden-sm hidden-md"><?php echo $label_paid; ?></th>
    <th width="5%" class="hidden-xs hidden-sm hidden-md" nowrap><?php echo $label_received; ?><a class="hide-loader" tabindex="0" role="button" title="<?php echo $label_received." ".$label_entries." ".$label_info; ?>" data-placement="auto top" data-toggle="popover" data-trigger="hover focus" data-content="<?php echo $brewer_entries_text_017; ?>" data-container="body"><span style="padding-left:5px;" class="fa fa-question-circle"></span></a></th>
    <th width="10%" class="hidden-xs hidden-sm hidden-md"><?php echo $label_updated; ?></th>
    <?php } ?>
  	<?php if ($show_scores) { ?>
  	<th><?php echo $label_score; ?></th>
    <th width="5%" class="hidden-xs" nowrap><?php echo $label_mini_bos; ?></th>
  	<th width="5%"><?php echo $label_winner; ?></th>
  	<?php } ?>
  	<?php if ((!$show_scores) && ($multiple_bottle_ids)) { ?>
    <th width="7%" class="hidden-print" nowrap><input type="checkbox" id="select_all"><a class="hide-loader" style="cursor: pointer;" data-toggle="popover" data-container="body" data-trigger="hover focus" data-placement="auto" title="<?php echo $brewer_entries_text_024; ?>" data-content="<?php echo $brewer_entries_text_021; ?>"><span style="padding-left:5px;" class="fa fa-question-circle hide-loader hidden-xs hidden-sm"></span></a></th>
	<?php } ?>
    <th class="hidden-print"><?php echo $label_actions; ?></th>
 </tr>
</thead>
<tbody>
<?php echo $entry_output; ?>
</tbody>
</table>
<?php if ((!$show_scores) && ($multiple_bottle_ids)) { ?>
<div style="margin-top: 20px;">
<input type="submit" id="btn" class="btn btn-primary pull-right hidden-print" value="<?php echo $brewer_entries_text_024; ?>" disabled data-toggle="popover" data-container="body" data-trigger="hover focus" data-placement="auto right" title="<?php echo $brewer_entries_text_022; ?>" data-content="<?php echo $brewer_entries_text_023; ?>">
</div>
<?php } ?>
</form>
<?php }
if ($entry_window_open == 0) echo sprintf("<p>%s %s.</p>",$brewer_entries_text_013,$entry_open);
?>

<!-- Page Rebuild completed 08.27.15 -->