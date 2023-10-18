<?php
/**
 ************************************** 
 * Judging Dashboard (for Judges Only)
 * Code is executed in the the entry
 * loop for each table.
 **************************************
 */

$user_submitted_eval = user_submitted_eval($_SESSION['user_id'],$row_entries['id']);
if ((!empty($score_entry_data[3])) || (!empty($score_entry_data[4]))) $score_previous = TRUE;
elseif (is_array($user_submitted_eval)) $score_previous = TRUE;

if (TESTING) {

	/*
	if (HOSTED) $styles_db_table = "bcoem_shared_styles";
	else
	*/
	$styles_db_table = $prefix."styles";

	/*
	if (HOSTED) $query_style = sprintf("SELECT brewStyleType FROM %s WHERE brewStyleVersion='%s'AND brewStyleGroup='%s' AND brewStyleNum='%s' UNION ALL SELECT brewStyleType FROM %s WHERE brewStyleVersion='%s'AND brewStyleGroup='%s' AND brewStyleNum='%s'",$prefix."styles",$_SESSION['prefsStyleSet'],$row_entries['brewCategorySort'],$row_entries['brewSubCategory'],$styles_db_table,$_SESSION['prefsStyleSet'],$row_entries['brewCategorySort'],$row_entries['brewSubCategory']);
	else 
	*/
	$query_style = sprintf("SELECT brewStyleType FROM %s WHERE brewStyleVersion='%s'AND brewStyleGroup='%s' AND brewStyleNum='%s'",$prefix."styles",$_SESSION['prefsStyleSet'],$row_entries['brewCategorySort'],$row_entries['brewSubCategory']);
	$style = mysqli_query($connection,$query_style) or die (mysqli_error($connection));
	$row_style = mysqli_fetch_assoc($style);
	
	$add_link_full = $base_url."index.php?section=evaluation&amp;go=scoresheet&amp;action=add&amp;filter=".$tbl_id."&amp;sort=1&amp;id=".$row_entries['id'];
	$add_link_checklist = $base_url."index.php?section=evaluation&amp;go=scoresheet&amp;action=add&amp;filter=".$tbl_id."&amp;sort=2&amp;id=".$row_entries['id'];
	$add_link_structured = $base_url."index.php?section=evaluation&amp;go=scoresheet&amp;action=add&amp;filter=".$tbl_id."&amp;sort=3&amp;id=".$row_entries['id'];
}

	
$eval_place = "";
$eval_places = array();

foreach ($eval_scores as $key => $value) {
	if ($value['eid'] == $row_entries['id']) {
		$count_evals += 1;
		$entries_evaluated[] = $value['eid'];
		$assigned_score[] = $value['consensus_score'];
		if (!empty($value['place'])) $eval_places[] = $value['place'];

		$score = $value['judge_score'];
		if (!empty($score)) $judge_score[] = $score;
		
	}
}

if (!empty($judge_score)) {

	$max_score = max($judge_score);
	$min_score = min($judge_score);

	if (($max_score - $min_score) > $_SESSION['jPrefsScoreDispMax']) {
		$notes .= "<div style=\"margin-bottom:5px;\" class=\"text-danger\"><strong>".$evaluation_info_036."</strong></div>";
	}

}

if ((!empty($assigned_score)) && (count($assigned_score) > 1)) {
	if (count(array_unique($assigned_score)) === 1) $notes .= "<div style=\"margin-bottom:5px;\" class=\"text-success\">".$evaluation_info_026."<br>".$label_assigned_score.": ".$assigned_score[0]."</div>";
	else {
		$notes .= "<div style=\"margin-bottom:5px;\" class=\"text-danger\"><strong>".$evaluation_info_017."<br>";
		$notes .= $label_recorded_scores.": ";
		$notes .= rtrim(display_array_content($assigned_score,2),", ");
		$notes .= "</strong></div>";

		if ($score_previous) {
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

if (!$queued) {
	
	// Check if the user is assigned to this flight
	$user_flight_assignment = user_flight_assignment($_SESSION['user_id'],$tbl_id);
	$entry_flight_assignment = entry_flight_assignment($row_entries['id'],$tbl_id);

	if ($user_flight_assignment == $entry_flight_assignment) $user_flight = TRUE;
	else $user_flight = FALSE;

	$flight_entries_count += 1;
	
	// If not, "disable" the Add button and add on-the-fly accordion
	if ($user_flight) {
		$user_flight_entries_count += 1;
	} 
	else {
		$add_disabled = TRUE;
	}
	
}

if ($score_previous) {
	
	if (!empty($user_submitted_eval['evalFinalScore'])) $notes .= $evaluation_info_004." ";
	if (empty($score_entry_data[3])) $notes .= $evaluation_info_003." ";
	
	// If so, display the edit and view buttons.
	if (is_array($user_submitted_eval)) {
		// if ($queued) $user_flight_entries_count += 1;
		$scored_by_user = TRUE;
		$add_disabled = FALSE;
		$user_flight_scored_entries_count += 1;
		$score = $user_submitted_eval['evalAromaScore'] + $user_submitted_eval['evalAppearanceScore'] + $user_submitted_eval['evalFlavorScore'] + $user_submitted_eval['evalMouthfeelScore'] + $user_submitted_eval['evalOverallScore']; 
	}
	
}

// Mini BOS
$mini_bos_count_flag = FALSE;
$mini_bos_count = 0;
$eval_count = 0;
$mini_bos_alert_css = "";
$mini_bos_alert_icon = "";
$mini_bos_checked_yes = "";
$mini_bos_checked_no = "";

foreach ($eval_scores as $key => $value) {

	if ($value['eid'] == $row_entries['id']) {
		$score_previous_other = TRUE;
		$eval_count++;
		$mini_bos_count += $value['mini_bos'];
	}
	
}

if (($mini_bos_count > 0) && ($eval_count > $mini_bos_count)) $mini_bos_count_flag = TRUE;

if ($mini_bos_count_flag) {
	$mini_bos_alert_css = "text-danger";
	$mini_bos_alert_icon = " <i class=\"fa fa-exclamation-triangle\"></i>";
}

if ($mini_bos_count == 0) $mini_bos_checked_no = "CHECKED";
if ($mini_bos_count > 0) {
	if ($eval_count == $mini_bos_count) $mini_bos_checked_yes = "CHECKED";
}

if (($judging_open) && (strpos($row_table_assignments['assignRoles'], "HJ") !== false)) {

	if ($score_previous_other) {

		if ((!empty($eval_places)) && (count(array_unique($eval_places)) === 1)) $eval_place = $eval_places[0];

		// save_column('".$base_url."','evalPlace','evaluation','".$row_entries['id']."','','','','','eval-place-ajax-".$row_entries['id']."');
		// select_place_multi('".$base_url."','evalPlace','evaluation','".$row_entries['id']."','eval-place-choose-".$tbl_id."','','','','eval-place-ajax-".$row_entries['id']."');

		$actions .= "<div class=\"row\">";
		$actions .= "<div class=\"col col-lg-6 col-md-7 col-sm-12\">";
		$actions .= $label_place_awarded;
		$actions .= "</div>";
		$actions .= "<div class=\"col col-lg-6 col-md-5 col-sm-12\">";
		$actions .= "<div style=\"margin-bottom:5px;\" id=\"eval-place-ajax-".$row_entries['id']."-evalPlace-form-group\">";
		if ($_SESSION['prefsWinnerMethod'] == "0") {
	        $actions .= "<select class=\"selectpicker eval-place-choose-".$tbl_id."\" id=\"eval-place-ajax-".$row_entries['id']."\" name=\"evalPlace".$row_entries['id']."\" title=\"".$label_place_awarded."\" data-width=\"100%\" onchange=\"select_place_multi('".$base_url."','evalPlace','evaluation','".$row_entries['id']."','eval-place-choose-".$tbl_id."','','','','eval-place-ajax-".$row_entries['id']."')\">";
	    } else {
	    	$actions .= "<select class=\"selectpicker eval-place-choose-".$tbl_id."\" id=\"eval-place-ajax-".$row_entries['id']."\" name=\"evalPlace".$row_entries['id']."\" title=\"".$label_place_awarded."\" data-width=\"100%\" onchange=\"save_column('".$base_url."','evalPlace','evaluation','".$row_entries['id']."','eval-place-choose-".$tbl_id."','','','','eval-place-ajax-".$row_entries['id']."')\">";
	    }
        $actions .= "<option value=\"\"";
    	if (($eval_place == "") || ($eval_place == "0")) $actions .= " SELECTED";
    	$actions .= ">".$label_none."</option>";
    	$actions .= "<option value=\"1\"";
        if ($eval_place == "1") $actions .= " SELECTED";
        $actions .= ">1st</option>";
        $actions .= "<option value=\"2\"";
        if ($eval_place == "2") $actions .= " SELECTED";
        $actions .= ">2nd</option>";
        $actions .= "<option value=\"3\"";
        if ($eval_place == "3") $actions .= " SELECTED";
        $actions .= ">3rd</option>";
        $actions .= "<option value=\"4\"";
        if ($eval_place == "4") $actions .= " SELECTED";
        $actions .= ">4th</option>";
        $actions .= "<option value=\"5\"";
        if ($eval_place == "5") $actions .= " SELECTED";
        $actions .= ">".$label_honorable_mention."</option>";
        $actions .= "</select>";
        $actions .= "<span id=\"eval-place-ajax-".$row_entries['id']."-evalPlace-status\"></span>";
		$actions .= "<span id=\"eval-place-ajax-".$row_entries['id']."-evalPlace-status-msg\"></span>";
        $actions .= "</div>";
		$actions .= "</div>";
		$actions .= "</div>";


		// Mini-BOS
		$actions .= "<div class=\"row\">";
		$actions .= "<div class=\"col col-lg-6 col-md-7 col-sm-12 ".$mini_bos_alert_css."\">";
		$actions .= $label_mini_bos;
		$actions .= "</div>";
		$actions .= "<div style=\"margin-bottom:5px;\" class=\"col col-lg-6 col-md-5 col-sm-12\">";
		$actions .= "<div class=\"input-group\">";
		$actions .= "<label class=\"radio-inline ".$mini_bos_alert_css."\">";
		$actions .= "<input type=\"radio\" name=\"evalMiniBOS".$row_entries['id']."\" value=\"1\" onclick=\"save_column('".$base_url."','evalMiniBOS','evaluation','".$row_entries['id']."','1','default','default','default','eval-mbos-ajax-".$row_entries['id']."','value')\" ".$mini_bos_checked_yes.">Yes";
		$actions .= "</label>";
		$actions .= "<label class=\"radio-inline ".$mini_bos_alert_css."\">";
		$actions .= "<input type=\"radio\" name=\"evalMiniBOS".$row_entries['id']."\" value=\"0\" onclick=\"save_column('".$base_url."','evalMiniBOS','evaluation','".$row_entries['id']."','0','default','default','default','eval-mbos-ajax-".$row_entries['id']."','value')\" ".$mini_bos_checked_no.">No";
		$actions .= "</label>";
		$actions .= "</div>";
		$actions .= "<br><span id=\"eval-mbos-ajax-".$row_entries['id']."-evalMiniBOS-status\"></span> ";
		$actions .= "<span id=\"eval-mbos-ajax-".$row_entries['id']."-evalMiniBOS-status-msg\"></span> ";
       	$actions .= "</div>";
		if ($mini_bos_count_flag) {
			$actions .= "<div id=\"eval-mbos-ajax-".$row_entries['id']."-evalMiniBOS-hide\" style=\"margin-bottom:5px;\" class=\"col col-sm-12\">";
			$actions .= "<span class=\"small ".$mini_bos_alert_css."\">".$mini_bos_alert_icon." Not all judges indicated this entry advanced to the mini-BOS round. Please verify and select Yes or No above.</span>";
			$actions .= "</div>";
		}
		$actions .= "</div>";

	}

}

if (!$score_previous_other) {
	$notes .= "<div style=\"margin-bottom:5px;\">";
	$notes .= $evaluation_info_016;
	if ((strpos($row_table_assignments['assignRoles'], "HJ") !== false) && ((!$judging_open) || ((!empty($table_location[1])) && (time() > $table_location[1])))) $notes .= " ".$evaluation_info_030;
	$notes .= "</div>";
}

if (($add_disabled) && ($judging_open)) {
	
	if (!$disable_add_edit) {
		$actions .= "<a class=\"btn btn-block btn-sm btn-default\" role=\"button\" href=\"#add-collapse-".$row_entries['id']."\" data-toggle=\"collapse\" aria-expanded=\"false\" aria-controls=\"add-collapse-".$row_entries['id']."\">".$label_add."</a>";
		$actions .= "<div class=\"collapse\" id=\"add-collapse-".$row_entries['id']."\">";
		$actions .= "<small>";
		$actions .= sprintf("<strong>%s</strong> %s",ucfirst(strtolower($label_sure)),$evaluation_info_005);

		if (TESTING) {

			$actions .= "<p style=\"margin-top: 3px;\">Choose a scoresheet (demo mode only - Admins choose the official scoresheet for the competition):</p>";
			$actions .= "<div class=\"row\" style=\"margin-top: 3px;\">";
			$actions .= "<div style=\"padding-top:3px;\" class=\"col col-md-4 col-sm-12\">";
			$actions .= "<a class=\"btn btn-block btn-xs btn-danger\" href=\"".$add_link_full."\">Classic</a>";
			$actions .= "</div>";
			if ($row_style['brewStyleType'] == 1) {
				$actions .= "<div style=\"padding-top:3px;\" class=\"col col-md-4 col-sm-12\">";
				$actions .= "<a class=\"btn btn-block btn-xs btn-danger\" href=\"".$add_link_checklist."\">Checklist</a>";
				$actions .= "</div>";
			}

			if ($row_style['brewStyleType'] <= 3) {
				$actions .= "<div style=\"padding-top:3px;\" class=\"col col-md-4 col-sm-12\">";
				$actions .= "<a class=\"btn btn-block btn-xs btn-danger\" href=\"".$add_link_structured."\">Structured</a>";
				$actions .= "</div>";
			}

			$actions .= "</div>"; // end row
		}

		else {
			$actions .= "<div style=\"margin-top: 3px;\">";
			$actions .= "<a class=\"btn btn-block btn-xs btn-danger\" href=\"".$add_link."\">".$label_yes."</a>";
			$actions .= "</div>";
		}

		$actions .= "</small>";
		$actions .= "</div>";
	}
}

elseif ($scored_by_user) {

	$view_link = $base_url."includes/output.inc.php?section=evaluation&amp;go=default&amp;id=".$user_submitted_eval['id']."&amp;tb=1";

	if ($judging_open) {

		$edit_link = $base_url."index.php?section=evaluation&amp;go=scoresheet&amp;action=edit&amp;filter=".$tbl_id."&amp;sort=".$user_submitted_eval['evalScoresheet']."&amp;id=".$user_submitted_eval['id'];

		$actions .= "<div class=\"btn-group btn-group-justified\" role=\"group\">";
		
		if (!$disable_add_edit) {
    		$actions .= "<a class=\"btn btn-sm btn-warning\" href=\"".$edit_link."\">".$label_edit;
    		$actions .= "</a>";
    	}
		
		$actions .= "<a style=\"word-wrap:break-word;\" class=\"btn btn-sm btn-info hide-loader\" id=\"modal_window_link\" class=\"hide-loader\" href=\"".$view_link."\">";
		if (strpos($row_table_assignments['assignRoles'], "HJ") !== false) $actions .= $label_view_my_eval;
		else $actions .= $label_view;
		$actions .= "</a>";
		$actions .= "</div>";
		
	}

	else {
		$actions .= "<a class=\"btn btn-block btn-sm btn-info hide-loader\" id=\"modal_window_link\" class=\"hide-loader\" href=\"".$view_link."\">".$label_view;
		$actions .= "</a>";
	}

	$actions .= "<div class=\"text-center\">";
	$actions .= "<small>".$label_your_score.": ".$score."</small>";
	$actions .= "</div>";
	
	if (!empty($user_submitted_eval['evalFinalScore'])) {
		$actions .= "<div class=\"text-center\">";
		$actions .= "<small>".$label_your_assigned_score.": ".$user_submitted_eval['evalFinalScore']."</small>";
		$actions .= "</div>";
	}
	
	if ($judging_open) $notes .= " ".$evaluation_info_006;

}

else {
	
	if ($judging_open) { 
		
		if ($disable_add_edit) {
			$actions .= "<a style=\"margin-bottom:5px;\" class=\"btn btn-block btn-sm btn-default disabled\" href=\"".$add_link."\">".$label_add."</a>";
			$actions .= "<div class=\"text-center\">";
			$actions .= "<small>".$evaluation_info_028."</small>";
			$actions .= "</div>";
		}

		else {

			if (TESTING) {
						
				$actions .= "<a class=\"btn btn-block btn-sm btn-primary\" role=\"button\" href=\"#add-choose-".$row_entries['id']."\" data-toggle=\"collapse\" aria-expanded=\"false\" aria-controls=\"add-choose-".$row_entries['id']."\">".$label_add."</a>";
				$actions .= "<div class=\"collapse\" id=\"add-choose-".$row_entries['id']."\">";
				$actions .= "<p style=\"margin-top: 3px;\"><small>Choose a scoresheet (demo mode only - Admins choose the official scoresheet for the competition):</small></p>";
				$actions .= "<div class=\"row\" style=\"margin-top: 3px;\">";
				$actions .= "<div style=\"padding-top:3px;\" class=\"col col-md-4 col-sm-12\">";
				$actions .= "<a class=\"btn btn-block btn-xs btn-info\" href=\"".$add_link_full."\">Classic</a>";
				$actions .= "</div>";
				if ($row_style['brewStyleType'] == 1) {
					$actions .= "<div style=\"padding-top:3px;\" class=\"col col-md-4 col-sm-12\">";
					$actions .= "<a class=\"btn btn-block btn-xs btn-info\" href=\"".$add_link_checklist."\">Checklist</a>";
					$actions .= "</div>";
				}
				if ($row_style['brewStyleType'] <= 3) {
					$actions .= "<div style=\"padding-top:3px;\" class=\"col col-md-4 col-sm-12\">";
					$actions .= "<a class=\"btn btn-block btn-xs btn-info\" href=\"".$add_link_structured."\">Structured</a>";
					$actions .= "</div>";
				}
				$actions .= "</div>"; // end row
				$actions .= "</div>"; // end collapse
			
			}

			else $actions .= "<a style=\"margin-bottom:5px;\" class=\"btn btn-block btn-sm btn-primary\" href=\"".$add_link."\">".$label_add."</a>";

		}

		if (!$queued) $notes .= $evaluation_info_010;
		
		if (($score_previous_other) && ($scored_by_user)) {
			
			if (count(array_unique($assigned_score)) === 1) $notes .= "<div style=\"margin-bottom:5px;\" class=\"text-success\">".$evaluation_info_026."<br>".$label_assigned_score.": ".$assigned_score[0]."</div>";
			
			else {
				$notes .= "<div style=\"margin-bottom:5px;\" class=\"text-danger\"><strong>".$evaluation_info_017."<br>";
				$notes .= $label_recorded_scores.": ";
				$notes .= rtrim(display_array_content($assigned_score,2),", ");
				$notes .= "</strong></div>";
			}

		}

	}

}

if (($judging_open) && (strpos($row_table_assignments['assignRoles'], "HJ") !== false) && ($score_previous_other)) {

	foreach ($eval_scores as $key => $value) {
		if ($value['eid'] == $row_entries['id']) {
			if ($value['judge_id'] != $_SESSION['user_id']) {
				$score_previous_id = $value['id'];
				$score_previous = $value['judge_score'];
				$view_link = $base_url."includes/output.inc.php?section=evaluation&amp;go=default&amp;id=".$score_previous_id."&amp;tb=1";
				$actions .= "<div style=\"margin-top: 5px;\">";
				$actions .= "<a style=\"word-wrap:break-word;\" class=\"btn btn-block btn-sm btn-info hide-loader\" id=\"modal_window_link\" class=\"hide-loader\" href=\"".$view_link."\">";
				$actions .= $label_view_other_judge_eval;
				$actions .= " (";
				$actions .= $label_score.": ".$score_previous;
				$actions .= ")";
				$actions .= "</a>";
				$actions .= "</div>";
			}	
		}
	}

}

if (($judging_open) && (strpos($row_table_assignments['assignRoles'], "HJ") === false) && ((!empty($eval_places)) && (count(array_unique($eval_places)) === 1))) {
	$actions .= "<div class=\"text-center\"><small>".$label_place_awarded.": ".display_place($eval_places[0],1)."</small></div>";
}

if (($score_previous) || ($score_previous_other)) {
	$table_scored_entries_count += 1;
	if ((!$queued) && ($user_flight)) $flight_scored_entries_count += 1; 
} 
?>