<?php

/**
 ************************************** 
 * Admin: Entry Evaluations
 * This code will execute within the
 * entry loop for each table.
 **************************************
 */

// Mini BOS
$mini_bos_count_flag = FALSE;
$score_previous_other = FALSE;
$mini_bos_count = 0;
$eval_count = 0;
$mini_bos_alert_css = "";
$mini_bos_alert_icon = "";
$mini_bos_checked_yes = "";
$mini_bos_checked_no = "";

foreach ($eval_scores as $key => $value) {

// Display Edit button for those evaluations that have been entered
// Otherwise, display "No evaluation entered yet" message

	if ($value['eid'] == $row_entries['id']) {

		$count_evals += 1;

		$eval_judge = brewer_info($value['judge_id']);
		$eval_judge = explode("^",$eval_judge);
		$judge_name = $eval_judge[0]." ".$eval_judge[1];
		$score = $value['judge_score'];
		$eval_all_judges[] = $value['judge_id'];
		$latest_submitted[$value['eid']] = $value['date_added'];
		$latest_updated[$value['eid']] = $value['date_updated'];
		$table_judges[] = array(
			"tj_first_name" => $eval_judge[0],
			"tj_last_name" => $eval_judge[1],
			"tj_uid" => $eval_judge[7]
		);

		if (!empty($score)) $judge_score[] = $score;
		if (!empty($value['consensus_score'])) $assigned_score[] = $value['consensus_score'];
		if (!empty($value['ordinal_position'])) $ordinal_position[] = $value['ordinal_position'];
		if (!empty($value['place'])) $eval_places[] = $value['place'];

		$view_link = $base_url."includes/output.inc.php?section=evaluation&amp;go=default&amp;id=".$value['id']."&amp;tb=1";
		$print_link = $base_url."includes/output.inc.php?section=evaluation&amp;go=default&amp;id=".$value['id'];
		$edit_link = $base_url."index.php?section=evaluation&amp;go=scoresheet&amp;action=edit&amp;filter=".$tbl_id."&amp;bid=".$value['judge_id']."&amp;view=admin&amp;sort=".$value['scoresheet']."&amp;id=".$value['id'];
		$delete_link = $base_url."includes/process.inc.php?section=".$section."&amp;go=".$go."&amp;filter=".$filter."&amp;dbTable=".$prefix."evaluation&amp;action=delete&amp;id=".$value['id'];

		$actions .= "<div style=\"margin-bottom:5px;\" class=\"row\">";
		$actions .= "<div class=\"col col-lg-6 col-md-7 col-sm-12\">";
		$actions .= $label_judge.": ".$judge_name;
		// getTimeZoneDateTime($_SESSION['prefsTimeZone'], $value['date_updated'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "date-time")
		$actions .= " (".$score.")";
		$actions .= "<br><small><span class\"text-muted\">".$label_submitted.": ".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $value['date_added'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "date-time")."</span></small>";
		if ($value['date_added'] != $value['date_updated']) $actions .= "<br><small><span class\"text-muted\">".$label_updated.": ".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $value['date_updated'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "date-time")."</span></small>";
		$actions .= "</div>";
		$actions .= "<div class=\"col col-lg-6 col-md-5 col-sm-12\">";
		$actions .= "<a id=\"modal_window_link\" class=\"hide-loader\" href=\"".$view_link."\" data-toggle=\"tooltip\" title=\"View the generated scoresheet from the evaluation completed by ".$eval_judge[0]." ".$eval_judge[1].".\"><span class=\"fa-stack\"><i class=\"fa fa-square fa-stack-2x\"></i><i class=\"fa fa-stack-1x fa-file-text fa-inverse\"></i></a> ";
		$actions .= "<a id=\"modal_window_link\" class=\"hide-loader\" href=\"".$print_link."\" data-toggle=\"tooltip\" title=\"Print the generated scoresheet from the evaluation  completed by ".$eval_judge[0]." ".$eval_judge[1].".\"><i class=\"fa fa-lg fa-file-text\"></i></a> ";
		$actions .= "<a href=\"".$edit_link."\" data-toggle=\"tooltip\" data-toggle=\"tooltip\" title=\"Edit this evaluation completed by ".$eval_judge[0]." ".$eval_judge[1].".\"><i class=\"fa fa-lg fa-pencil\"></i></a> ";
		$actions .= "<a class=\"hide-loader\" href=\"".$delete_link."\" data-toggle=\"tooltip\" title=\"Delete this evaluation completed by ".$eval_judge[0]." ".$eval_judge[1].".\" data-confirm=\"Are you sure you want to delete this evaluation completed by ".$eval_judge[0]." ".$eval_judge[1]."? This cannot be undone.\"><i class=\"fa fa-lg fa-trash-o\"></i></a> ";
		$actions .= "</div>";
		$actions .= "</div>";
		
		$score_previous_other = TRUE;
		$eval_count++;
		$mini_bos_count += $value['mini_bos'];

	}

}

if (($mini_bos_count > 0) && ($eval_count > $mini_bos_count)) $mini_bos_count_flag = TRUE;

if ($mini_bos_count_flag) {
	$mini_bos_alert_css = "text-danger";
	$mini_bos_alert_icon = " <i class=\"fa fa-exclamation-triangle\"></i>";
	$mini_bos_mismatch[] = array(
	"table_id" => $tbl_id,
	"table_name" => $tbl_num_disp." - ".$tbl_name_disp,
	"id" => $row_entries['id'],
	"brewJudgingNumber" => $number,
	"brewCategorySort" => $row_entries['brewCategorySort'],
	"brewSubCategory" => $row_entries['brewSubCategory'],
	"brewStyle" => $row_entries['brewStyle']
	);
}

if ($mini_bos_count == 0) $mini_bos_checked_no = "CHECKED";
if ($mini_bos_count > 0) {
	if ($eval_count == $mini_bos_count) $mini_bos_checked_yes = "CHECKED";	
}

if (!empty($eval_places)) {

	if  (count(array_unique($eval_places)) === 1) {
		$eval_place = $eval_places[0];
		$table_places[] = array($number => $eval_places[0]);
	}

	if  (count(array_unique($eval_places)) > 1) {
		foreach ($eval_places as $value) {
			 $table_places[] = array($number => $value);
		}
		
	}

}

if (!empty($ordinal_position)) {
	$op_arr = array();
	foreach ($ordinal_position as $value) {
		$op = explode(",",$value);
		$op_arr[] = $op[0];
	}
	$ord_position = max($op_arr);
}


if ($count_evals > 0) {

	$eval_place_actions .= "<div class=\"row\">";
	$eval_place_actions .= "<div class=\"col col-lg-6 col-md-7 col-sm-12\">";
	$eval_place_actions .= $label_place_awarded;
	$eval_place_actions .= "<span class=\"hidden-xs hidden-sm hidden-md\"> (".ucwords(str_replace(".", "", $evaluation_info_006)).")</span>";
	$eval_place_actions .= "</div>";
	$eval_place_actions .= "<div class=\"col col-lg-6 col-md-5 col-sm-12\">";
	$eval_place_actions .= "<div style=\"margin-bottom:5px;\" id=\"eval-place-ajax-".$row_entries['id']."-evalPlace-form-group\">";
	$eval_place_actions .= "<select class=\"selectpicker eval-place-choose-".$tbl_id."\" data-dropdown-align-right=\"auto\" id=\"eval-place-ajax-".$row_entries['id']."\" name=\"evalPlace".$row_entries['id']."\" title=\"".$label_place_awarded."\" data-width=\"100%\" onchange=\"update_place_display('".$number."','eval-place-ajax-".$row_entries['id']."','".$tbl_id."');select_place_multi('".$base_url."','evalPlace','evaluation','".$row_entries['id']."','eval-place-choose-".$tbl_id."','','','','eval-place-ajax-".$row_entries['id']."');\">";

	$eval_place_actions .= "<option value=\"\"";
	if (($eval_place == "") || ($eval_place == "0")) $eval_place_actions .= " SELECTED";
	$eval_place_actions .= ">".$label_none."</option>";
	$eval_place_actions .= "<option value=\"1\"";
	if ($eval_place == "1") $eval_place_actions .= " SELECTED";
	$eval_place_actions .= ">1st</option>";
	$eval_place_actions .= "<option value=\"2\"";
	if ($eval_place == "2") $eval_place_actions .= " SELECTED";
	$eval_place_actions .= ">2nd</option>";
	$eval_place_actions .= "<option value=\"3\"";
	if ($eval_place == "3") $eval_place_actions .= " SELECTED";
	$eval_place_actions .= ">3rd</option>";
	$eval_place_actions .= "<option value=\"4\"";
	if ($eval_place == "4") $eval_place_actions .= " SELECTED";
	$eval_place_actions .= ">4th</option>";
	$eval_place_actions .= "<option value=\"5\"";
	if ($eval_place == "5") $eval_place_actions .= " SELECTED";
	$eval_place_actions .= ">".$label_honorable_mention."</option>";
	$eval_place_actions .= "</select>";
	$eval_place_actions .= "<span id=\"eval-place-ajax-".$row_entries['id']."-evalPlace-status\"></span>";
	$eval_place_actions .= "<span id=\"eval-place-ajax-".$row_entries['id']."-evalPlace-status-msg\"></span>";
	$eval_place_actions .= "</div>";
	$eval_place_actions .= "</div>";
	$eval_place_actions .= "</div>";

	// Mini-BOS
	$eval_place_actions .= "<div class=\"row\">";
	$eval_place_actions .= "<div class=\"col col-lg-6 col-md-7 col-sm-12 ".$mini_bos_alert_css."\">";
	$eval_place_actions .= $label_mini_bos;
	$eval_place_actions .= "</div>";
	$eval_place_actions .= "<div style=\"margin-bottom:5px;\" class=\"col col-lg-6 col-md-5 col-sm-12\">";
	$eval_place_actions .= "<div class=\"input-group\">";
	$eval_place_actions .= "<label class=\"radio-inline ".$mini_bos_alert_css."\">";
	$eval_place_actions .= "<input type=\"radio\" name=\"evalMiniBOS".$row_entries['id']."\" value=\"1\" onclick=\"save_column('".$base_url."','evalMiniBOS','evaluation','".$row_entries['id']."','1','default','default','default','eval-mbos-ajax-".$row_entries['id']."','value')\" ".$mini_bos_checked_yes.">Yes";
	$eval_place_actions .= "</label>";
	$eval_place_actions .= "<label class=\"radio-inline ".$mini_bos_alert_css."\">";
	$eval_place_actions .= "<input type=\"radio\" name=\"evalMiniBOS".$row_entries['id']."\" value=\"0\" onclick=\"save_column('".$base_url."','evalMiniBOS','evaluation','".$row_entries['id']."','0','default','default','default','eval-mbos-ajax-".$row_entries['id']."','value')\" ".$mini_bos_checked_no.">No";
	$eval_place_actions .= "</label>";
	$eval_place_actions .= "</div>";
	$eval_place_actions .= "<br><span id=\"eval-mbos-ajax-".$row_entries['id']."-evalMiniBOS-status\"></span> ";
	$eval_place_actions .= "<span id=\"eval-mbos-ajax-".$row_entries['id']."-evalMiniBOS-status-msg\"></span> ";
   	$eval_place_actions .= "</div>";
	if ($mini_bos_count_flag) {
		$eval_place_actions .= "<div id=\"eval-mbos-ajax-".$row_entries['id']."-evalMiniBOS-hide\" style=\"margin-bottom:5px;\" class=\"col col-sm-12\">";
		$eval_place_actions .= sprintf("<span class=\"small %s\">%s %s</span> <a class=\"small\" href=\"#top\"><i class=\"fa fa-sm fa-arrow-circle-up\"></i> Top</a>",$mini_bos_alert_css,$mini_bos_alert_icon,$evaluation_info_104);
		$eval_place_actions .= "</div>";
	}
	$eval_place_actions .= "</div>";
	$eval_place_actions .= "<hr style=\"margin: 10px 0 10px 0; border: 0; border-top: 1px solid #ddd;\">";
}

if ($count_evals == 0) {
	$eval_no_evaluations[] = $number;
}

if ($count_evals == 1) {
	$single_evaluation[] = array(
		"table_id" => $tbl_id,
		"table_name" => $tbl_num_disp." - ".$tbl_name_disp,
		"id" => $row_entries['id'],
		"brewJudgingNumber" => $number,
		"brewCategorySort" => $row_entries['brewCategorySort'],
		"brewSubCategory" => $row_entries['brewSubCategory'],
		"brewStyle" => $row_entries['brewStyle']
	);
}

if ($count_evals > 0) {

	$date_submitted[] = array(
		"table_id" => $tbl_id,
		"table_name" => $tbl_num_disp." - ".$tbl_name_disp,
		"id" => $row_entries['id'],
		"date_submitted" => $latest_submitted[$row_entries['id']],
		"date_updated" => $latest_updated[$row_entries['id']],
		"brewJudgingNumber" => $number,
		"brewCategorySort" => $row_entries['brewCategorySort'],
		"brewSubCategory" => $row_entries['brewSubCategory'],
		"brewStyle" => $row_entries['brewStyle']
	);

	if (!empty($score_entry_data[3])) {
		$notes .= "<div style=\"margin-bottom:5px;\" class=\"text-success\"><strong>";
		$notes .= $label_accepted_score.": ".$score_entry_data[3];
		if (!empty($score_entry_data[4])) $notes .= " - ".display_place($score_entry_data[4],2); 
		$notes .= "</strong></div>";
	}

	// Check if judges' scores are within the jPrefsScoreDispMax range
	if (!empty($judge_score)) {

		$max_score = max($judge_score);
		$min_score = min($judge_score);

		if (($max_score - $min_score) > $_SESSION['jPrefsScoreDispMax']) {

			$notes .= "<div style=\"margin-bottom:5px;\" class=\"text-danger\"><strong>".$evaluation_info_036."</strong></div>";

			$judge_score_disparity[] = array(
				"table_id" => $tbl_id,
				"table_name" => $tbl_num_disp." - ".$tbl_name_disp,
				"id" => $row_entries['id'],
				"brewJudgingNumber" => $number,
				"brewCategorySort" => $row_entries['brewCategorySort'],
				"brewSubCategory" => $row_entries['brewSubCategory'],
				"brewStyle" => $row_entries['brewStyle']
			);

		}

	}

	// Check if consensus scores match
	if (!empty($assigned_score)) {
		if ((count($assigned_score)) > 1) {
			$table_scored_entries_count += 1;
			if (count(array_unique($assigned_score)) === 1) $notes .= "<div style=\"margin-bottom:5px;\" class=\"text-success\">".$evaluation_info_026."<br>".$label_assigned_score.": ".$assigned_score[0]."</div>";
			else {
				$notes .= "<div style=\"margin-bottom:5px;\" class=\"text-danger\"><strong>".$evaluation_info_017."<br>";
				$notes .= $label_recorded_scores.": ";
				$notes .= rtrim(display_array_content($assigned_score,2),", ");
				$notes .= "</strong></div>";

				$assigned_score_mismatch[] = array(
					"table_id" => $tbl_id,
					"table_name" => $tbl_num_disp." - ".$tbl_name_disp,
					"id" => $row_entries['id'],
					"brewJudgingNumber" => $number,
					"brewCategorySort" => $row_entries['brewCategorySort'],
					"brewSubCategory" => $row_entries['brewSubCategory'],
					"brewStyle" => $row_entries['brewStyle']
				);
			}
		}
	}

	$notes .= "<div style=\"margin-bottom:5px;\">".$label_evals_submitted.": ".$count_evals."</div>";
	if (!empty($ord_position)) $notes .= "<div style=\"margin-bottom:5px;\">".$label_ordinal_position.": ".$ord_position."</div>";
}

else {
	$notes .= "<div style=\"margin-bottom:5px;\">".$evaluation_info_016."</div>";
}

?>
