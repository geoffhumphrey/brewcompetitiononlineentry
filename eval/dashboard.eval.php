<?php

/**
 * -------- User Judging/Evaluation Dashboard --------
 * 
 * Dashboard for judges to add/edit evaluations and scores for entries they've judged.
 * Hooks:
 *    - Judge info
 *    - Table assignments
 *    - Flight assignments (if non-queued judging)
 *
 * TO DO:
 *    - Add check to see if all scores have been imported. If so, don't show or disable the import button.
 *    - Dynamically check at interval to see if entry currently evaluating has score entered by another judge.
 * 
 */

include(LIB.'output.lib.php');

$judging_open = FALSE;
$queued = FALSE;
$admin = FALSE;
$head_judge = FALSE;
$assignment_display = "";
$table_assignment_entries = "";
$dt_js = "";
$assign_score_mismatch = "";
$jscore_disparity = "";
$assigned_score_mismatch = array();
$judge_score_disparity = array();
$table_places_alert = array();
$places_alert = "";
$dup_judge_evals_alert = "";
$duplicate_judge_evals_alert = array();
$entries_evaluated = array();
$mini_bos_mismatch = array();
$mini_bos_mismatch_alert = "";
$total_evals_alert = "";
$single_eval = "";
$single_evaluation = array();
$table_assignments_user = array();
$on_the_fly_display = "";
$on_the_fly_display_tbody = "";
$roles = "";
$latest_submitted = array();
$date_submitted = array();
$latest_updated = array();
$date_updated = array();
$diff = 600; // Differential of seconds (10 minutes)
$admin_add_eval = "";

function find_next($arr,$needle,$diff) {
	$last = 0;
	foreach ($arr as $key => $value) {
		if ($value > ($needle-$diff))  {
			return $value;
		}
	}
	return $last;
}

function count_past($arr,$needle,$diff) {
	$count = 0;
	foreach ($arr as $key => $value) {
		if ($value < ($needle-$diff))  {
			$count += 1;
		}
	}
	return $count;
}

function count_future($arr,$needle,$diff) {
	$count = 0;
	foreach ($arr as $key => $value) {
		if ($value > ($needle-$diff)) {
			$count += 1;
		}
	}
	return $count;
}

// Get last judging session end date/time (if any)
$query_session_end = sprintf("SELECT judgingDateEnd FROM %s",$prefix."judging_locations");
if (SINGLE) $query_session_end .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
$query_session_end .= " ORDER BY judgingDateEnd DESC LIMIT 1";
$session_end = mysqli_query($connection,$query_session_end) or die (mysqli_error($connection));
$row_session_end = mysqli_fetch_assoc($session_end);
$totalRows_session_end = mysqli_num_rows($session_end);

if ((time() > $row_judging_prefs['jPrefsJudgingOpen']) && (time() < $row_judging_prefs['jPrefsJudgingClosed'])) $judging_open = TRUE;
if (($totalRows_session_end > 0) && (time() < $row_session_end['judgingDateEnd'])) $judging_open = TRUE;

if ($row_judging_prefs['jPrefsQueued'] == "Y") $queued = TRUE;
if (($view == "admin") && ($_SESSION['userLevel'] <= 1)) $admin = TRUE;
if ($admin) include(DB.'admin_common.db.php');

// If viewing in admin mode, present a quick form for Admins to add an
// evaluation on behalf of a judge.
$admin_add_eval .= "<button style=\"margin-top:0px\" class=\"btn btn-primary\" type=\"button\" data-toggle=\"collapse\" data-target=\"#collapse-add-eval\" aria-expanded=\"false\" aria-controls=\"collapse-add-eval\">Add an Evaluation on Behalf of Judge</button>";
$admin_add_eval .= "<section style=\"margin-top:15px\" id=\"collapse-add-eval\" class=\"collapse bcoem-admin-element\">";
$admin_add_eval .= "<h3>Add an Evaluation</h3>";
$admin_add_eval .= "<p>To add an evaluation on behalf of a judge, choose the judge and input the entry number.</p>";
$admin_add_eval .= "<div class=\"row\">";
$admin_add_eval .= "<div class=\"col col-md-5 col-sm-7 col-xs-12\">";
$admin_add_eval .= "<form class=\"hide-loader-form-submit form-horizontal \" name=\"form1\" data-toggle=\"validator\" role=\"form\" action=\"".$base_url."index.php?section=evaluation&amp;go=scoresheet&amp;action=add\" method=\"post\">";
$admin_add_eval .= "<div class=\"form-group\">";
$admin_add_eval .= sprintf("<label for=\"entry_number\" class=\"col-sm-4 control-label\">%s</label>",$label_judge);
$admin_add_eval .= "<div class=\"col-sm-8\">";
$admin_add_eval .= participant_choose($brewer_db_table,$_SESSION['prefsProEdition'],"1","1");
$admin_add_eval .= "</div>";
$admin_add_eval .= "</div>";
$admin_add_eval .= "<div class=\"form-group\">";
$admin_add_eval .= sprintf("<label for=\"entry_number\" class=\"col-sm-4 control-label\">%s</label>",$label_number);
$admin_add_eval .= "<div class=\"col-sm-8\">";
$admin_add_eval .= "<input id=\"entry-number-input\" name=\"entry_number\" type=\"text\" pattern=\".{6,6}\" maxlength=\"6\" class=\"form-control small\" style=\"width:100%;\" data-error=\"".$evaluation_info_015."\" required>";
$admin_add_eval .= "</div>";
$admin_add_eval .= "</div>"; // form group
$admin_add_eval .= "<div class=\"help-block with-errors\"></div>";
$admin_add_eval .= "<div class=\"col-sm-offset-4 col-sm-8\">";
$admin_add_eval .= sprintf("<button onclick=\"localStorage.clear();\" class=\"btn btn-success\" style=\"margin-top:5px;\" type=\"submit\">%s</button>",$label_add);
$admin_add_eval .= "</div>";
$admin_add_eval .= "</form>";
$admin_add_eval .= "</div>"; // ./col
$admin_add_eval .= "</div>"; // ./row
$admin_add_eval .= "</section>";


$header = sprintf("<p class=\"lead\">%s <small>%s</small></p>",$evaluation_info_000,$evaluation_info_008);
if ($queued) $header .= sprintf("<div class=\"alert alert-info\"><p><strong>%s</strong>: %s</p><p>%s</p></div>",ucfirst(strtolower($label_please_note)),$evaluation_info_001,$evaluation_info_002); 
	
$query_table_assignments = sprintf("SELECT * FROM %s ORDER BY tableNumber ASC",$prefix."judging_tables");
$table_assignments = mysqli_query($connection,$query_table_assignments) or die (mysqli_error($connection));
$row_table_assignments = mysqli_fetch_assoc($table_assignments);
$totalRows_table_assignments = mysqli_num_rows($table_assignments);

$query_eval_sub = sprintf("SELECT * FROM %s", $prefix."evaluation");
$eval_sub = mysqli_query($connection,$query_eval_sub) or die (mysqli_error($connection));
$row_eval_sub = mysqli_fetch_assoc($eval_sub);
$totalRows_eval_sub = mysqli_num_rows($eval_sub);

$eval_scores = array();
$eval_judge_evaluations = array();
$eval_judge_tables = array();
$eval_no_evaluations = array();

if ($totalRows_eval_sub > 0) {

	do {

		$judge_score = $row_eval_sub['evalAromaScore'] + $row_eval_sub['evalAppearanceScore'] + $row_eval_sub['evalFlavorScore'] + $row_eval_sub['evalMouthfeelScore'] + $row_eval_sub['evalOverallScore'];

		if (!$admin) {
			
			$eval_judge_evaluations[] = array(
				"entry_id" => $row_eval_sub['eid']
			);

			$eval_judge_tables[] = array(
				"judge_id" => $row_eval_sub['evalJudgeInfo'],
				"table_id" => $row_eval_sub['evalTable']
			);

		}

		$eval_scores[] = array(
			"id" => $row_eval_sub['id'],
			"eid" => $row_eval_sub['eid'],
			"judge_id" => $row_eval_sub['evalJudgeInfo'],
			"judge_score" => $judge_score,
			"consensus_score" => $row_eval_sub['evalFinalScore'],
			"table" => $row_eval_sub['evalTable'],
			"place" => $row_eval_sub['evalPlace'],
			"ordinal_position" => $row_eval_sub['evalPosition'],
			"date_added" => $row_eval_sub['evalInitialDate'],
			"date_updated" => $row_eval_sub['evalUpdatedDate'],
			"scoresheet" => $row_eval_sub['evalScoresheet'],
			"mini_bos" => $row_eval_sub['evalMiniBOS']
		);

	} while($row_eval_sub = mysqli_fetch_assoc($eval_sub));
	
}

$total_scored_entries_count = 0;
$total_entries_count = 0;
$status_sidebar_table_info = "";
$status_sidebar_js = "";
$status_sidebar_js_icons = "";
$status_sidebar_js_timing = 0;

if ($totalRows_table_assignments > 0) {

	$table_assignment_start = array();

	do {

		$table_places = array();
		$table_places_display = "";
		$disable_add_edit = FALSE;
		$table_entries_count = 0;
		$table_scored_entries_count = 0;
		$flight_entries_count = 0;
		$user_flight_entries_count = 0;
		$flight_scored_entries_count = 0;
		$user_flight_scored_entries_count = 0;
		$table_assignment_stats = "";
		$table_judges = array();
		
		$tbl_id = $row_table_assignments['id'];
		$tbl_name_disp = $row_table_assignments['tableName'];
		$tbl_loc_disp = $row_table_assignments['tableLocation'];
		$tbl_num_disp = $row_table_assignments['tableNumber'];
		
		$table_location = get_table_info($tbl_loc_disp,"location",$tbl_id,"default","default");
		$table_location = explode("^", $table_location);

		if (!empty($table_location[0])) $location_start_date = $table_location[0];
		else $location_start_date = time();

		$table_assignment_start[] = $location_start_date;

		/**
		 * Open up for non-admins 10 minutes before the stated session start time.
		 * Useful when judging is in-person and judges wish to review their assigned
		 * entries prior to "officially" starting.
		 * Uses $diff var.
		 */

		if (($admin) || ((!$admin) && (time() > ($table_location[0] - $diff)))) { 

			if ((!empty($table_location[1]) && (time() > $table_location[1]))) $disable_add_edit = TRUE;

			$random = random_generator(7,2);
			$assigned_judges = assigned_judges($tbl_id,$dbTable,$judging_assignments_db_table,1);
			
			$table_start_time = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $location_start_date, $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");

			if (isset($table_location[1])) {

				if (empty($table_location[1])) $table_assignment_entries .= sprintf("<a name=\"table".$tbl_id."\"></a><h3 style=\"margin-top: 30px;\">%s %s - %s <br><small>%s &#8226; %s</small></h3>",$label_table,$tbl_num_disp,$tbl_name_disp,$table_location[2],$table_start_time);
				
				else {
					$table_end_time = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $table_location[1], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");
					
					if (time() < $table_location[1]) $table_assignment_entries .= sprintf("<a name=\"table".$tbl_id."\"></a><h3 style=\"margin-top: 30px;\">%s %s - %s<br><small>%s &#8226; %s %s %s</small></h3>",$label_table,$tbl_num_disp,$tbl_name_disp,$table_location[2],$table_start_time,$entry_info_text_001,$table_end_time);

					else $table_assignment_entries .= sprintf("<a name=\"table".$tbl_id."\"></a><h3 style=\"margin-top: 30px;\">%s %s - %s<br><small>%s &#8226; %s %s <span class=\"text-warning\">%s</span> - %s</small></h3>",$label_table,$tbl_num_disp,$tbl_name_disp,$table_location[2],$table_start_time,$entry_info_text_001,$table_end_time,strtolower($evaluation_info_028));
				}

			}

			$table_assignment_pre = "";
			$table_assignment_data = "";
			$table_assignment_post = "";
			
			$table_assignment_pre .= "<table id=\"table-".$random."\" class=\"table table-condensed table-striped table-bordered table-responsive\">";
			$table_assignment_pre .= "<thead>";
			$table_assignment_pre .= "<tr>";
			$table_assignment_pre .= "<th width=\"5%\" nowrap>".$label_number."</th>";
			$table_assignment_pre .= "<th width=\"20%\" class=\"hidden-xs\">".$label_style."</th>";
			$table_assignment_pre .= "<th width=\"20%\">".$label_info."</th>";
			$table_assignment_pre .= "<th width=\"25%\">".$label_notes."</th>";
			$table_assignment_pre .= "<th>".$label_actions."</th>";
			$table_assignment_pre .= "</tr>";
			$table_assignment_pre .= "</thead>";
			$table_assignment_pre .= "<tbody>";

			$dt_js .= "
			$('#table-".$random."').dataTable({
				\"bPaginate\" : false,
				\"sDom\": 'rt',
				\"bStateSave\" : false,
				\"bLengthChange\" : false,
				\"aaSorting\": [[1,'asc'],[0,'asc']],
				\"bProcessing\" : false,
				\"aoColumns\": [
					null,
					null,
					null,
					null,
					null
					]
				});
			";
			
			if ($admin) {
				$a = explode(",", $row_table_assignments['tableStyles']);
			}

			else {
				$query_tables = sprintf("SELECT tableStyles FROM %s WHERE id='%s'",$prefix."judging_tables",$tbl_id);
				$tables = mysqli_query($connection,$query_tables) or die (mysqli_error($connection));
				$row_tables = mysqli_fetch_assoc($tables);
				$totalRows_tables = mysqli_num_rows($tables);
				$a = explode(",", $row_tables['tableStyles']);
			}
			
			sort($a);

			foreach (array_unique($a) as $value) {

				$score_style_data = score_style_data($value);

				if (!empty($score_style_data)) {

					$score_style_data = explode("^",$score_style_data);
			        
					$query_entries = sprintf("SELECT * FROM %s WHERE (brewCategorySort='%s' AND brewSubCategory='%s') AND brewReceived='1'", $prefix."brewing", $score_style_data[0], $score_style_data[1]);
					//$query_entries .= " ORDER BY brewJudgingNumber, brewCategorySort, brewSubCategory ASC;";
					$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
					$row_entries = mysqli_fetch_assoc($entries);
					$totalRows_entries = mysqli_num_rows($entries);

			        if ($totalRows_entries > 0) {

			        	do {

			        		if ($_SESSION['prefsDisplaySpecial'] == "J") $number = sprintf("%06s",$row_entries['brewJudgingNumber']);
				    		else $number = sprintf("%06s",$row_entries['id']);

				    		// Store total entry count in array for use later
							$table_entries_count += 1;

			        		$notes = "";
			        		$score = "";
			        		$scored_by_user = FALSE;
			        		$add_disabled = FALSE;
			        		$score_previous = FALSE;
			        		$score_previous_other = FALSE;
			        		$actions = "";
			        		$eval_place_actions = "";
			        		$count_evals = 0;
			        		$assigned_score = array();
			        		$judge_score = array();
							$eval_places = array();
							$eval_place = "";
							$score_entry_data = score_entry_data($row_entries['id']);
							$score_entry_data = explode("^",$score_entry_data);
							$eval_all_judges = array();
							$ordinal_position = array();
							$ord_position = "";
							
							// Classic
							if ($row_judging_prefs['jPrefsScoresheet'] == 1) {
								$output_form = "full-scoresheet";
								$scoresheet_form = "full_scoresheet.eval.php";
							}

							// Beer Checklist
							if ($row_judging_prefs['jPrefsScoresheet'] == 2) {

								if ($score_style_data[3] == 1) {
									$output_form = "checklist-scoresheet";
									$scoresheet_form = "checklist_scoresheet.eval.php";
								}

								else  {
									$output_form = "full-scoresheet";
									$scoresheet_form = "full_scoresheet.eval.php";
								}

							}

							// Structured (Includes NW Cider Cup)
							if (($row_judging_prefs['jPrefsScoresheet'] == 3) || ($row_judging_prefs['jPrefsScoresheet'] == 4)) {

								if ($score_style_data[3] <= 3) {
									$output_form = "structured-scoresheet";
									$scoresheet_form = "structured_scoresheet.eval.php";
								}

								else {
									$output_form = "full-scoresheet";
									$scoresheet_form = "full_scoresheet.eval.php";
								}
								
							}
							
			        		$style = style_number_const($row_entries['brewCategorySort'],$row_entries['brewSubCategory'],$_SESSION['style_set_display_separator'],1);
							$style_display = $style." ".$row_entries['brewStyle'];

							$info_display = "";
							$allergen_display = "";
							$abv_display = "";
							$pouring_display = "";
							$pouring_arr = "";
							$juice_src_display = "";
							$carb_display = "";
							$sweetness_display = "";
							$sweetness_level_display = "";
							$strength_display = "";
							$additional_info = 0;
							
							if (!empty($row_entries['brewInfo'])) {
								$additional_info++;
								if ((($_SESSION['prefsStyleSet'] == "BJCP2021") || ($_SESSION['prefsStyleSet'] == "BJCP2025")) && ($row_entries['brewCategorySort'] == "02") && ($row_entries['brewSubCategory'] == "A")) $info_display .= "<strong>".$label_regional_variation; 
								else $info_display .= "<strong>".$label_required_info;
								$info_display .= ":</strong> ".$row_entries['brewInfo'];
							}

							if (!empty($row_entries['brewMead1'])) {
								$additional_info++;
								$carb_display .= "<strong>".$label_carbonation.":</strong> ".$row_entries['brewMead1'];
							}

							if (!empty($row_entries['brewMead2'])) {
								$additional_info++;
								$sweetness_display .= "<strong>".$label_sweetness.":</strong> ".$row_entries['brewMead2'];
							}

							if (!empty($row_entries['brewSweetnessLevel'])) {

								$additional_info++;
								$sweetness_json = json_decode($row_entries['brewSweetnessLevel'],true);
								
								if (json_last_error() === JSON_ERROR_NONE) {

									if (!empty($sweetness_json['OG'])) $sweetness_level_display .= "<li><strong>".$label_original_gravity.":</strong> ".$sweetness_json['OG']."</li>";
									if (!empty($sweetness_json['FG'])) $sweetness_level_display .= "<li><strong>".$label_final_gravity.":</strong> ".$sweetness_json['FG']."</li>";

								}
								
								else {
									$sweetness_level_display .= "<strong>".$label_final_gravity.":</strong> ".$row_entries['brewSweetnessLevel'];
								}

							}

							if (!empty($row_entries['brewMead3'])) {
								$additional_info++;
								$strength_display .= "<strong>".$label_strength.":</strong> ".$row_entries['brewMead3'];
							}

							if (!empty($row_entries['brewPossAllergens'])) {
								$additional_info++;
								$allergen_display .= "<strong>".$label_possible_allergens.":</strong> ".$row_entries['brewPossAllergens'];
							}
							
							if (!empty($row_entries['brewABV'])) {
								$additional_info++;
								$abv_display .= "<strong>".$label_abv.":</strong> ".number_format($row_entries['brewABV'],1);
							}

							if (!empty($row_entries['brewPouring'])) {
								$pouring_arr = json_decode($row_entries['brewPouring'],true);
								$pouring_display .= "<li><strong>".$label_pouring.":</strong> ".$pouring_arr['pouring']."</li>";
								if ((isset($pouring_arr['pouring_notes'])) && (!empty($pouring_arr['pouring_notes']))) $pouring_display .= "<li><strong>".$label_pouring_notes.":</strong> ".$pouring_arr['pouring_notes']."</li>";
								$pouring_display .= "<li><strong>".$label_rouse_yeast.":</strong> ".$pouring_arr['pouring_rouse']."</li>";
								unset($pouring_arr);
							}

							if (($admin) && ($_SESSION['prefsStyleSet'] == "NWCiderCup") && (!empty($row_entries['brewJuiceSource']))) {

								$additional_info++;

								$juice_src_arr = json_decode($row_entries['brewJuiceSource'],true);
								$juice_src_disp = "";

								if (is_array($juice_src_arr['juice_src'])) {
									$juice_src_disp .= implode(", ",$juice_src_arr['juice_src']);
									$juice_src_disp .= ", ";
								}

								if ((isset($juice_src_arr['juice_src_other'])) && (is_array($juice_src_arr['juice_src_other']))) {
									$juice_src_disp .= implode(", ",$juice_src_arr['juice_src_other']);
									$juice_src_disp .= ", ";
								}

								$juice_src_disp = rtrim($juice_src_disp,",");
								$juice_src_disp = rtrim($juice_src_disp,", ");

								$juice_src_display .= "<strong>".$label_juice_source.":</strong> ".$juice_src_disp;
							
							}
							
			        		// Admin: Entry Evaluations
			        		if ($admin) {
			        			$add_link = $base_url."index.php?section=admin&amp;go=evaluation&amp;action=add&amp;filter=".$tbl_id."&amp;id=".$row_entries['id'];
			        			include (EVALS.'judging_admin.eval.php');
			        		}

			        		// Judging Dashboard
			        		else {
			        			$add_link = $base_url."index.php?section=evaluation&amp;go=scoresheet&amp;action=add&amp;filter=".$tbl_id."&amp;id=".$row_entries['id'];
			        			include (EVALS.'judging_dashboard.eval.php');
			        		}
				            
				            // Build table data
				            if (($judging_open) || ($admin) || ((!$judging_open) && ($scored_by_user))) {
					            if ($add_disabled) $table_assignment_data .= "<tr class=\"text-muted\">";
					            elseif ((!$queued) && (!$add_disabled) && (!$admin)) $table_assignment_data .= "<tr class=\"text-primary\">";
					            else $table_assignment_data .= "<tr>";
					        	$table_assignment_data .= "<td><a class=\"anchor\" name=\"".$number."\"></a>".$number."</td>";
					        	$table_assignment_data .= "<td class=\"hidden-xs\">";
					        	$table_assignment_data .= $style_display;
					        	$table_assignment_data .= "</td>";
					        	
					        	$table_assignment_data .= "<td>";
					        	if ($additional_info > 0) {
					        		$table_assignment_data .= "<small><ul class=\"list-unstyled\">";
					        		if (!empty($info_display)) $table_assignment_data .= "<li>".str_replace("^",", ",$info_display)."</li>";
					        		if (!empty($carb_display)) $table_assignment_data .= "<li>".$carb_display."</li>";
					        		if (!empty($sweetness_display)) $table_assignment_data .= "<li>".$sweetness_display."</li>";
					        		if (!empty($sweetness_level_display)) $table_assignment_data .= "<li>".$sweetness_level_display."</li>";
					        		if (!empty($allergen_display)) $table_assignment_data .= "<li>".$allergen_display."</li>";
					        		if (!empty($abv_display)) $table_assignment_data .= "<li>".$abv_display."%</li>";
					        		if (!empty($juice_src_display)) $table_assignment_data .= "<li>".$juice_src_display."</li>";
					        		if (!empty($strength_display)) $table_assignment_data .= "<li>".$strength_display."</li>";
					        		if (!empty($pouring_display)) $table_assignment_data .= $pouring_display;
					        		$table_assignment_data .= "</ul></small>";
					        	}
					        	$table_assignment_data .= "</td>";

					        	$table_assignment_data .= "<td>".$notes."</td>";
					        	$table_assignment_data .= "<td>".$eval_place_actions.$actions."</td>";
					            $table_assignment_data .= "</tr>";
					        }

					        // Check to see if any judges have more than one evaluation for this
					        // entry. If so, add to duplicate judges alert array.
					        if (!empty($eval_all_judges)) {
					        	$all_judges_count = array_count_values($eval_all_judges);
					        	foreach ($all_judges_count as $key => $value) {
					        		if ($value > 1) {
					        			$duplicate_judge_evals_alert[] = array(
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

				        } while ($row_entries = mysqli_fetch_assoc($entries));

				    } // end if ($totalRows_entries > 0)

				} // end if (!empty($score_style_data)  

			} // end foreach

			if (empty($table_assignment_data)) $table_assignment_data .= "<tr><td colspan=\"4\">".$evaluation_info_016."</td></tr>";
			
			$table_assignment_post .= "</tbody>";
			$table_assignment_post .= "</table>";

			$table_assignment_post .= "<p><small><a href=\"#top\"><i class=\"fa fa-sm fa-arrow-circle-up\"></i> Top</a></small></p>";

			// If places have been awarded at the table, but there are duplicates, list them for admins
			if (($admin) && (!empty($table_places))) {

				$places_table_flag_arr = array();
				$table_places_display_ul = "";
				
				foreach ($table_places as $key => $value) {
					foreach ($value as $k => $v) {
						$places_table_flag_arr[] = $v;
						$table_places_display_ul .= "<li id=\"place-display-".$k."\">".$k." - <span id=\"place-display-num-".$k."\">".display_place($v,1)."</span></li>";	
					}	
				}

				if (($_SESSION['prefsWinnerMethod'] == "0") && (count(array_unique($places_table_flag_arr)) < count($places_table_flag_arr))) {
					
					$table_places_display .= "<div class=\"alert alert-danger\">";
					$table_places_display .=sprintf("<p><strong><i class=\"fa fa-exclamation-circle\"></i> %s</strong></p><p>%s:</p>",$label_attention,ucfirst(strtolower($label_places_awarded_duplicate)));
					$table_places_display .= "<ul id=\"places-awarded-table-".$tbl_id."\">";
					$table_places_display .= $table_places_display_ul;
					$table_places_display .= "</ul>";		
					$table_places_display .= "</div>";
					$table_places_alert[] = array(
						"table_id" => $tbl_id,
						"table_name" => $tbl_num_disp." - ".$tbl_name_disp,
					);

				}

			}

			if ($admin) {

				/**
				 * -------------------------------------------
				 * Build Table Counts Sidebar Data
				 * For each table, get count data and build
				 * the associated javascript ajax calls.
				 * -------------------------------------------
				 */

				$table_scored_entries_count = get_evaluation_count("table-unique",$tbl_id);
				
				$tbl_name_disp = truncate($tbl_name_disp,"25","...");
				$status_sidebar_timing = $status_sidebar_js_timing += 2000;
				$status_sidebar_js .= sprintf("
					setTimeout(function() {
						fetchRecordCount(base_url,'total-evaluations-table-%s','1','evaluation','eid','table','evalTable','%s');
						$('.refresh-link-table-%s').removeClass('hidden');
			        	$('.refresh-link-table-%s').fadeIn();
						$('.icon-sync-table-%s').removeClass('hidden');
			        	$('.icon-sync-table-%s').fadeIn();
			        	setInterval(function() { 
			                $('.icon-sync-table-%s').fadeOut();
			            }, 10000);
					}, %s);\n
					",$tbl_id,$tbl_id,$tbl_id,$tbl_id,$tbl_id,$tbl_id,$tbl_id,$status_sidebar_timing);

				$status_sidebar_table_info .= "<section class=\"bcoem-sidebar-panel\">";
				$status_sidebar_table_info .= sprintf("<strong class=\"text-info\"><a href=\"#table%s\">%s</a> - %s</strong> <i class=\"fa fa-xs fa-sync fa-spin icon-sync-table-%s hidden\"></i>",$tbl_id,$tbl_num_disp,$tbl_name_disp,$tbl_id);
				$status_sidebar_table_info .= sprintf("<span style=\"margin-left: 15px;\" class=\"pull-right\"><span class=\"total-evaluations-table-%s\">%s</span> / %s</span>",$tbl_id,$table_scored_entries_count,$table_entries_count);
				$status_sidebar_table_info .= "</section>";

				/**
				 * -------------------------------------------
				 * Build Table Assignment Statistics
				 * For each table, get count data and other
				 * statistics (judges, number of entries, 
				 * scored entries) to display below the table
				 * name and location.
				 * -------------------------------------------
				 */

				// s
				if ($table_entries_count == $table_scored_entries_count) {
					$table_assignment_stats .= "<div class=\"alert alert-success\">";
					$table_assignment_stats .= sprintf("<i class=\"fa fa-lg fa-check-circle\"></i> <strong>%s</strong>",$evaluation_info_037);
					$table_assignment_stats .= "</div>";
				}
				
				$table_assignment_stats .= "<div class=\"row small bcoem-account-info\">";
				$table_assignment_stats .= "<div class=\"col col-lg-8 col-md-10 col-sm-12 col-xs-12\">";

				$assigned_judge_names_display = "";

				if ($assigned_judges > 0) {

					$query_assigned_judge_names = sprintf("SELECT a.brewerFirstName,a.brewerLastName, b.assignment FROM %s a, %s b WHERE b.assignTable='%s' AND a.uid = b.bid AND b.assignment='J' ORDER BY a.brewerLastName, a.brewerFirstName ASC",$prefix."brewer",$prefix."judging_assignments",$tbl_id);
					$assigned_judge_names = mysqli_query($connection,$query_assigned_judge_names);
					$row_assigned_judge_names = mysqli_fetch_assoc($assigned_judge_names);
					
					do {
						$assigned_judge_names_display .= $row_assigned_judge_names['brewerFirstName']." ".$row_assigned_judge_names['brewerLastName'].", ";
					} while ($row_assigned_judge_names = mysqli_fetch_assoc($assigned_judge_names));

					$assigned_judge_names_display = rtrim($assigned_judge_names_display, ", ");
				
				}

				$table_assignment_stats .= "<section class=\"row\">";
				$table_assignment_stats .= "<div class=\"col col-lg-3 col-md-5 col-sm-5 col-xs-6\">";
				$table_assignment_stats .= "<strong>".$evaluation_info_025."</strong>";
				$table_assignment_stats .= "</div>";
				$table_assignment_stats .= "<div class=\"col col-lg-9 col-md-7 col-sm-7 col-xs-6\">";
				$table_assignment_stats .= $assigned_judges;
				if (!empty($assigned_judge_names_display)) $table_assignment_stats .= " &ndash; ".$assigned_judge_names_display;
				$table_assignment_stats .= "</div>";
				$table_assignment_stats .= "</section>";

				if ($table_scored_entries_count > 0) {

					$columns = array_column($table_judges, "tj_last_name");
					array_multisort($columns, SORT_ASC, $table_judges);
					$table_judges = array_unique($table_judges, SORT_REGULAR);
					
					$judge_names = "";
					foreach ($table_judges as $key => $value) {
						$judge_names .= $value['tj_first_name']." ".$value['tj_last_name'].", ";
					}
					$judge_names = rtrim($judge_names, ", ");

					$table_assignment_stats .= "<section class=\"row\">";
					$table_assignment_stats .= "<div class=\"col col-lg-3 col-md-5 col-sm-5 col-xs-6\">";
					$table_assignment_stats .= "<strong>".$evaluation_info_043."</strong>";
					$table_assignment_stats .= "</div>";
					$table_assignment_stats .= "<div class=\"col col-lg-9 col-md-7 col-sm-7 col-xs-6\">";
					$table_assignment_stats .= $judge_names;
					$table_assignment_stats .= "</div>";
					$table_assignment_stats .= "</section>";
				}

				$table_assignment_stats .= "<section class=\"row\">";
				$table_assignment_stats .= "<div class=\"col col-lg-3 col-md-5 col-sm-5 col-xs-6\">";
				$table_assignment_stats .= "<strong>".$evaluation_info_039."</strong>";
				$table_assignment_stats .= "</div>";
				$table_assignment_stats .= "<div class=\"col col-lg-9 col-md-7 col-sm-7 col-xs-6\">";
				$table_assignment_stats .= $table_entries_count;
				$table_assignment_stats .= "</div>";
				$table_assignment_stats .= "</section>";

				$table_assignment_stats .= "<section class=\"row\">";
				$table_assignment_stats .= "<div class=\"col col-lg-3 col-md-5 col-sm-5 col-xs-6\">";
				$table_assignment_stats .= "<strong>".$evaluation_info_040."</strong>";
				$table_assignment_stats .= "</div>";
				$table_assignment_stats .= "<div class=\"col col-lg-9 col-md-7 col-sm-7 col-xs-6\">";
				$table_assignment_stats .= sprintf("<span class=\"total-evaluations-table-%s\">%s</span> <i class=\"fa fa-xs fa-sync fa-spin icon-sync-table-%s hidden\"></i>",$tbl_id,$table_scored_entries_count,$tbl_id);
				$table_assignment_stats .= sprintf(" <span style=\"margin-left: 10px;\" class=\"refresh-link refresh-link-table-%s small hidden\"><a href=\"#\" onClick=\"window.location.reload()\">Refresh</a> to review updates.</span>",$tbl_id);
				$table_assignment_stats .= "</div>";
				$table_assignment_stats .= "</section>";

				$table_assignment_stats .= "</div>";
				$table_assignment_stats .= "</div>";

				$table_assignment_stats .= "<p><small><a href=\"#top\"><i class=\"fa fa-xs fa-arrow-circle-up\"></i> Top</a></small></p>";

				$total_entries_count += $table_entries_count;
				$total_scored_entries_count += $table_scored_entries_count;

			}

			$table_assignment_entries .= $table_places_display.$table_assignment_stats.$table_assignment_pre.$table_assignment_data.$table_assignment_post;
			
		} // end if (time() > $table_location[0])

	} while ($row_table_assignments = mysqli_fetch_assoc($table_assignments));

	asort($table_assignment_start);

	$next_date = find_next($table_assignment_start,time(),0);
	$next_judging_date_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], ($next_date - $diff) , "999",  $_SESSION['prefsTimeFormat'], "short", "date-no-gmt");
	$current_or_past_sessions = count_past($table_assignment_start,time(),0);
	$future_sessions = count_future($table_assignment_start,time(),0);

	/**
	 * -------------------------------------------
	 * Build Alerts
	 * These alerts will be at the top of the page
	 * -------------------------------------------
	 */
	
	// Judge Score Disparity Alert
	if (!empty($judge_score_disparity)) {
		$jscore_disparity .= "<div class=\"alert alert-warning alert-dismissible\">";
		$jscore_disparity .= sprintf("<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button><p><strong><i class=\"fa fa-exclamation-circle\"></i> %s %s</strong></p><p> %s</p>",$label_attention,$evaluation_info_036,$evaluation_info_018);
		$jscore_disparity .= "<ul>";
		asort($assigned_score_mismatch);
		foreach ($judge_score_disparity as $key => $value) {
			$jscore_disparity .= "<li>";
			$jscore_disparity .= "<a href=\"#".$value['brewJudgingNumber']."\">".$value['brewJudgingNumber']."</a>";
			$jscore_disparity .= " - ".style_number_const($value['brewCategorySort'],$value['brewSubCategory'],$_SESSION['style_set_display_separator'],0)." ".$value['brewStyle'];
			if (empty($value['table_name'])) $jscore_disparity .= " (".$label_unassigned_eval.")";
			else $jscore_disparity .= " (".$label_table." ".$value['table_name'].")";
			$jscore_disparity .= "</li>";
		}
		$jscore_disparity .= "</ul>";
		$jscore_disparity .= "</div>";
	}

	// Assigned Score Mismatch Alert
	if (!empty($assigned_score_mismatch)) {
		$assign_score_mismatch .= "<div class=\"alert alert-warning alert-dismissible\">";
		$assign_score_mismatch .= sprintf("<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button><p><strong><i class=\"fa fa-exclamation-circle\"></i> %s %s</strong></p><p> %s</p>",$label_attention,$evaluation_info_017,$evaluation_info_018);
		$assign_score_mismatch .= "<ul>";
		asort($assigned_score_mismatch);
		foreach ($assigned_score_mismatch as $key => $value) {
			$assign_score_mismatch .= "<li>";
			$assign_score_mismatch .= "<a href=\"#".$value['brewJudgingNumber']."\">".$value['brewJudgingNumber']."</a>";
			$assign_score_mismatch .= " - ".style_number_const($value['brewCategorySort'],$value['brewSubCategory'],$_SESSION['style_set_display_separator'],0)." ".$value['brewStyle'];
			if (empty($value['table_name'])) $assign_score_mismatch .= " (".$label_unassigned_eval.")";
			else $assign_score_mismatch .= " (".$label_table." ".$value['table_name'].")";
			$assign_score_mismatch .= "</li>";
		}
		$assign_score_mismatch .= "</ul>";
		$assign_score_mismatch .= "</div>";
	}

	// Build assigned score mismatch alert
	if (!empty($duplicate_judge_evals_alert)) {
		$dup_judge_evals_alert .= "<div class=\"alert alert-warning alert-dismissible\">";
		$dup_judge_evals_alert .= sprintf("<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button><p><strong><i class=\"fa fa-exclamation-circle\"></i> %s %s</strong> %s</p><p> %s</p>",$label_attention,$evaluation_info_032,$evaluation_info_033,$evaluation_info_018);
		$dup_judge_evals_alert .= "<ul>";
		asort($duplicate_judge_evals_alert);
		foreach ($duplicate_judge_evals_alert as $key => $value) {
			$dup_judge_evals_alert .= "<li>";
			$dup_judge_evals_alert .= "<a href=\"#".$value['brewJudgingNumber']."\">".$value['brewJudgingNumber']."</a>";
			$dup_judge_evals_alert .= " - ".style_number_const($value['brewCategorySort'],$value['brewSubCategory'],$_SESSION['style_set_display_separator'],0)." ".$value['brewStyle'];
			if (empty($value['table_name'])) $dup_judge_evals_alert .= " (".$label_unassigned_eval.")";
			else $dup_judge_evals_alert .= " (".$label_table." ".$value['table_name'].")";
			$dup_judge_evals_alert .= "</li>";
		}
		$dup_judge_evals_alert .= "</ul>";
		$dup_judge_evals_alert .= "</div>";
	}

	// Build single evaluation list alert
	if (!empty($single_evaluation)) {	
		$single_eval .= "<div class=\"alert alert-warning alert-dismissible\">";
		$single_eval .= sprintf("<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button><p><strong><i class=\"fa fa-exclamation-circle\"></i> %s</strong></p><p>%s</p>",$label_attention,$evaluation_info_019);
		$single_eval .= "<ul>";
		asort($single_evaluation);
		foreach ($single_evaluation as $key => $value) {
			$single_eval .= "<li>";
			$single_eval .= "<a href=\"#".$value['brewJudgingNumber']."\">".$value['brewJudgingNumber']."</a>";
			$single_eval .= " - ".style_number_const($value['brewCategorySort'],$value['brewSubCategory'],$_SESSION['style_set_display_separator'],0)." ".$value['brewStyle'];
			$single_eval .= " (".$label_table." ".$value['table_name'].")";
			$single_eval .= "</li>";
		}
		$single_eval .= "</ul>";
		$single_eval .= "</div>";
	}

	// Build duplicate places at table alert
	if (!empty($table_places_alert)) {
		$places_alert .= "<div class=\"alert alert-danger alert-dismissible\">";
		$places_alert .= sprintf("<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button><p><strong><i class=\"fa fa-exclamation-circle\"></i> %s</strong></p><p>%s</p>",$label_attention,$evaluation_info_029);
		$places_alert .= "<ul>";
		asort($table_places_alert);
		foreach ($table_places_alert as $key => $value) {
			$places_alert .= "<li>";
			$places_alert .= "<a href=\"#table".$value['table_id']."\">".$label_table." ".$value['table_name']."</a>";
			$places_alert .= "</li>";
		}
		$places_alert .= "</ul>";
		$places_alert .= "</div>";
	}

	// Build mini-bos mismatch alert
	if (!empty($mini_bos_mismatch)) {
		$mini_bos_mismatch_alert .= "<div class=\"alert alert-info alert-dismissible\">";
		$mini_bos_mismatch_alert .= sprintf("<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button><p><strong><i class=\"fa fa-info-circle\"></i> %s</strong></p><p>%s</p>",$label_please_note,$evaluation_info_105);
		$mini_bos_mismatch_alert .= "<ul>";
		asort($mini_bos_mismatch);
		foreach ($mini_bos_mismatch as $key => $value) {
			$mini_bos_mismatch_alert .= "<li>";
			$mini_bos_mismatch_alert .= "<a href=\"#".$value['brewJudgingNumber']."\">".$value['brewJudgingNumber']."</a>";
			$mini_bos_mismatch_alert .= " - ".style_number_const($value['brewCategorySort'],$value['brewSubCategory'],$_SESSION['style_set_display_separator'],0)." ".$value['brewStyle'];
			$mini_bos_mismatch_alert .= " (".$label_table." ".$value['table_name'].")";
			$mini_bos_mismatch_alert .= "</li>";
		}
		$mini_bos_mismatch_alert .= "</ul>";
		$mini_bos_mismatch_alert .= "</div>";
	}

	// Build display datatable if judge has evaluated entries 
	// at any judging table besides their assigned ones (on-the-fly)
	// if (!$admin) include (EVALS.'judging_not_assigned.eval.php');

	$top_alert = "";

	$two_to_end_prefs = ($row_judging_prefs['jPrefsJudgingClosed'] - 172800);
	if ((!empty($row_session_end['judgingDateEnd'])) && (is_numeric($row_session_end['judgingDateEnd'])) && ($totalRows_session_end > 0)) $two_to_end_sess = ($row_session_end['judgingDateEnd'] - 172800);	
	else $two_to_end_sess = $two_to_end_prefs;

	if ($two_to_end_sess > $two_to_end_prefs) {
		$two_days = $two_to_end_sess;
		$judging_end = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_session_end['judgingDateEnd'], "999",  $_SESSION['prefsTimeFormat'], "short", "date-no-gmt");
	}
	else {
		$two_days = $two_to_end_prefs;
		$judging_end = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging_prefs['jPrefsJudgingClosed'], "999",  $_SESSION['prefsTimeFormat'], "short", "date-no-gmt");
	}

	$count_none = count($eval_no_evaluations);
	$count_total = get_evaluation_count('total');
	$count_unique = get_evaluation_count('unique');

	if (($admin) && ($totalRows_eval_sub > 0)) {

		//$top_alert .= sprintf("<i style=\"padding-right: 5px;\" class=\"fa fa-comments-o\"></i><strong>%s</strong> %s %s %s, %s.", $totalRows_eval_sub, $evaluation_info_031, strtolower($reg_closed_text_005), $current_time, $current_date_display);
		
		if (($judging_open && (time() > $two_days)) && ($count_none > 0)) {
			if ($count_none == 1) $top_alert .= sprintf(" <button type=\"button\" class=\"btn btn-default btn-xs\" data-toggle=\"collapse\" data-target=\"#no-eval\">%s %s <i class=\"fa fa-chevron-down small\"></i></button>",$count_none,$label_entry_without_eval);
			else $top_alert .= sprintf(" <button type=\"button\" class=\"btn btn-default btn-xs\" data-toggle=\"collapse\" data-target=\"#no-eval\">%s %s <i class=\"fa fa-chevron-down small\"></i></button>",$count_none,$label_entries_without_eval);
			$top_alert .= "<section style=\"margin-top: 15px;\" class=\"collapse small\" id=\"no-eval\">";
			$top_alert .= sprintf("<p>%s:</p>",$evaluation_info_049);
			$top_alert .= "<ul class=\"list-inline\">";
			asort($eval_no_evaluations);
			foreach ($eval_no_evaluations as $value) {
				$top_alert .= "<li><a href=\"#".$value."\">".$value."</a></li>";
			}
			$top_alert .= "</ul>";
			$top_alert .= "</section>";
		}

		/*
		else {
			$top_alert .= sprintf("<br><i style=\"padding-right: 5px;\" class=\"fa fa-check-circle\"></i><strong>%s</strong>: <span class=\"total-evaluations-unique\">%s</span>",$label_entries_with_eval,$count_unique);
			// $top_alert .= sprintf("<br><i style=\"padding-right: 5px;\" class=\"fa fa-times-circle\"></i><strong>%s</strong>: %s",$label_entries_without_eval,$count_none);
		}
		*/
	}

	if ($judging_open) {

		$top_alert .= sprintf("<p><i style=\"padding-right: 5px;\" class=\"fa fa-clock-o\"></i><strong>%s:</strong> <span id=\"judging-ends\"></span></p>", $label_judging_close);
		if ($next_date-$diff > time()) $top_alert .= "<p><i style=\"padding-right: 5px;\" class=\"fa fa-clock\"></i><strong>Next Session Open:</strong> <span id=\"next-session-open\"></span></p>";

	}

	if (!empty($top_alert)) {

		$total_evals_alert .= "<div class=\"alert alert-teal alert-dismissible\">";
		$total_evals_alert .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
		$total_evals_alert .= $top_alert;
		$total_evals_alert .= "</div>";

	}


?>

<script type="text/javascript" language="javascript">
	
	function update_place_display(number,element_id,table_id) {
		
		var value = $("#"+element_id).val();
		
		if ((value == 0) || (value == "")) {
			$("#place-display-"+number).hide();
		}

		if (value > 0) {
			$("#place-display-"+number).show();
			if (value == 1) disp_val = "1st";
			if (value == 2) disp_val = "2nd";
			if (value == 3) disp_val = "3rd";
			if (value == 4) disp_val = "4th";
			if (value == 5) disp_val = "HM";
			$("#place-display-num-"+number).html(disp_val);
		}

	}

	$(document).ready(function() {
		$("#next-session-refresh-button").hide();
		$('#judge_assignments').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[1,'asc']],
			"aoColumns": [
				null,
				null,
				null
				]
			});
			<?php echo $dt_js; ?>
		$('.dropdown').each(function (key, dropdown) {
	        var $dropdown = $(dropdown);
	        $dropdown.find('.dropdown-menu a').on('click', function () {
	            $dropdown.find('button').text($(this).text()).append(' <span class="caret"></span>');
	        });
	    });
	});

</script>
<script src="<?php echo $js_url; ?>admin_ajax.min.js"></script>
<?php
} // end if ($totalRows_table_assignments > 0)


// Counts Sidebar
$status_sidebar = "";
$status_sidebar .= "<div class=\"panel panel-info\">";
$status_sidebar .= "<div class=\"panel-heading\">";

$status_sidebar .= "<h4 style=\"margin: 0px; padding-bottom: 5px;\">Status<span class=\"fa fa-2x fa-bar-chart text-info pull-right\"></span></h4>";

$status_sidebar .= "<p style=\"margin: 0px;\" class=\"small text-muted\"><span class=\"small\">Updated <span class=\"total-evaluations-unique-updated\">".getTimeZoneDateTime($_SESSION['prefsTimeZone'], time(), $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "date-time")."</span></span></p>";

$status_sidebar .= "<p style=\"margin: 0px;\" class=\"small text-muted updates-indicators\"><span class=\"small\" id=\"count-two-minute-info\"></span></p>";

$status_sidebar .= "<p style=\"margin: 0px;\" class=\"small text-muted updates-indicators\">";
$status_sidebar .= "<span class=\"small\"><a href=\"#\" onClick=\"window.location.reload()\">Refresh this page</a> to review updated evaluations and/or consensus scores.</span></span>";
$status_sidebar .= "</p>";

$status_sidebar .= "<p style=\"margin: 0px;\" class=\"small text-muted updates-indicators\">";
$status_sidebar .= "<span class=\"small\" id=\"resume-updates\"><a href=\"#\" class=\"hide-loader\" onclick=\"resumeUpdates()\">Resume Updates</a></span>";
$status_sidebar .= "<span class=\"small\" id=\"stop-updates\"><a href=\"#\" class=\"hide-loader\" onclick=\"stopUpdates()\">Pause Updates</a> <a href=\"#\" class=\"hide-loader pull-right\" onclick=\"resumeUpdates()\">Update Now</a></span>";
$status_sidebar .= "</p>";

$status_sidebar .= "</div>"; // end panel-heading

$status_sidebar .= "<div class=\"panel-body\">";

$status_sidebar .= "<section class=\"bcoem-sidebar-panel\">";
$status_sidebar .= "<strong class=\"text-teal\">Total Evaluations </strong> <i id=\"icon-sync-total-evaluations\" class=\"fa fa-xs fa-sync fa-spin hidden\"></i>";
$status_sidebar .= "<span id=\"total-evaluations\" class=\"pull-right\" style=\"margin-left: 15px;\">".$count_total."</span>";
$status_sidebar .= "</section>";

$status_sidebar .= "<section class=\"bcoem-sidebar-panel\">";
$status_sidebar .= "<strong class=\"text-teal\">Total Entries to Evaluate</strong>";
$status_sidebar .= "<span class=\"pull-right\" style=\"margin-left: 15px;\">".get_entry_count("paid-received")."</span>";
$status_sidebar .= "</section>";

$status_sidebar .= "<section class=\"bcoem-sidebar-panel\">";
$status_sidebar .= "<strong class=\"text-teal\">Total Entries with Evaluations </strong> <i id=\"icon-sync-total-evaluations-unique\" class=\"fa fa-xs fa-sync fa-spin hidden\"></i>";
$status_sidebar .= "<span class=\"pull-right total-evaluations-unique\" style=\"margin-left: 15px;\">".$count_unique."</span>";
$status_sidebar .= "</section>";

$status_sidebar .= "<section style=\"margin: 15px 0 8px 0;\" class=\"bcoem-sidebar-panel\">";
$status_sidebar .= "<strong class=\"text-info\">Entries with Evaluations by Table</strong>";
$status_sidebar .= "<span class=\"pull-right\">Count / Total</span>";
$status_sidebar .= "</section>";

$status_sidebar .= $status_sidebar_table_info;

$status_sidebar .= "</div>"; // end panel-body
$status_sidebar .= "</div>"; // end panel panel-info

$columns = array_column($date_submitted, "date_submitted");
array_multisort($columns, SORT_DESC, $date_submitted);
$date_submitted = array_unique($date_submitted, SORT_REGULAR);
$show_submitted = 0;
$latest_submitted_accordion = "";

foreach ($date_submitted as $key => $value) {
	$show_submitted += 1;
	if ($show_submitted <=20) {
		$submitted_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $value['date_submitted'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");
		$latest_submitted_accordion .= sprintf("<li><a href=\"#%s\">%s</a> - %s%s: %s (%s) - Score: %s</li>",$value['brewJudgingNumber'],$value['brewJudgingNumber'],$value['brewCategorySort'],$value['brewSubCategory'],$value['brewStyle'],$submitted_date,$value['consensus_score']);
	}
}

$columns = array_column($date_submitted, "date_updated");
array_multisort($columns, SORT_DESC, $date_submitted);
$date_submitted = array_unique($date_submitted, SORT_REGULAR);
$show_updated = 0;
$latest_updated_accordion = "";
foreach ($date_submitted as $key => $value) {
	$show_updated += 1;
	if ($show_updated <=20) {
		$updated_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $value['date_updated'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "date-time");
		$latest_updated_accordion .= sprintf("<li><a href=\"#%s\">%s</a> - %s%s: %s (%s) - Score: %s</li>",$value['brewJudgingNumber'],$value['brewJudgingNumber'],$value['brewCategorySort'],$value['brewSubCategory'],$value['brewStyle'],$updated_date,$value['consensus_score']);
	}
}

if (!$admin) {
	echo $header;
	if (($judging_open) && (empty($table_assign_judge))) echo sprintf("<p>%s</p>",$evaluation_info_009);
}

$left_side = "";

if ((!empty($latest_submitted_accordion)) || (!empty($latest_updated_accordion))) {
	
	$left_side .= "<div class=\"bcoem-admin-element\">";
	$left_side .= "<a style=\"margin:0 10px 0 0;\" class=\"btn btn-default\" role=\"button\" data-toggle=\"collapse\" href=\"#all-alerts\" aria-expanded=\"false\" aria-controls=\"latest-submitted\"><i style=\"padding-right: 5px;\" class=\"fa fa-chevron-down\"></i>Expand/Collapse Alerts</a>";
	if (!empty($latest_submitted_accordion)) $left_side .= "<a style=\"margin:0 10px 0 0;\" class=\"btn btn-default\" role=\"button\" data-toggle=\"collapse\" href=\"#latest-submitted\" aria-expanded=\"false\" aria-controls=\"latest-submitted\"><i style=\"padding-right: 5px;\" class=\"fa fa-clock-o\"></i>Expand/Collapse 20 Latest Submitted</a>";
	if (!empty($latest_updated_accordion)) $left_side .= "<a style=\"margin:0 10px 0 0;\" class=\"btn btn-default\" role=\"button\" data-toggle=\"collapse\" href=\"#latest-updated\" aria-expanded=\"false\" aria-controls=\"latest-updated\"><i style=\"padding-right: 5px;\" class=\"fa fa-clock-o\"></i>Expand/Collapse 20 Latest Updated</a>";
	$left_side .= "</div>";

}

$left_side .= "<div id=\"all-alerts\" class=\"collapse in\">";
if (!empty($total_evals_alert)) $left_side .= $total_evals_alert;
if (!empty($places_alert)) $left_side .= $places_alert;
if (!empty($judge_score_disparity)) $left_side .= $jscore_disparity;
if (!empty($assign_score_mismatch)) $left_side .= $assign_score_mismatch;
if (!empty($dup_judge_evals_alert)) $left_side .= $dup_judge_evals_alert;
if (!empty($single_evaluation)) $left_side .= $single_eval;
if (!empty($mini_bos_mismatch_alert)) $left_side .= $mini_bos_mismatch_alert;
$left_side .= "</div>";

if (!empty($latest_submitted_accordion)) {
	$left_side .= "<div id=\"latest-submitted\" class=\"collapse alert alert-teal\">";
	$left_side .= "<p><i style=\"padding-right: 5px;\" class=\"fa fa-clock-o\"></i>The <strong>20 most recently submitted</strong> evaluations:</p>";
	$left_side .= "<ul>";
	$left_side .= $latest_submitted_accordion;
	$left_side .= "</ul>";
	$left_side .= "</div>";
}

if (!empty($latest_updated_accordion)) {
	$left_side .= "<div id=\"latest-updated\" class=\"collapse alert alert-teal\">";
	$left_side .= "<p><i style=\"padding-right: 5px;\" class=\"fa fa-clock-o\"></i>The <strong>20 most recently updated</strong> evaluations:</p>";
	$left_side .= "<ul>";
	$left_side .= $latest_updated_accordion;
	$left_side .= "</ul>";
	$left_side .= "</div>";
}

if (!$admin) $left_side .= $assignment_display;
if (!empty($on_the_fly_display)) $left_side .= $on_the_fly_display;

?>

<div class="row">
	<div class="col-xs-12 col-sm-6 col-md-9">
		<?php 
		echo $left_side;
		include (EVALS.'import_scores.eval.php');
		echo $admin_add_eval;
		echo $table_assignment_entries;
		?>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-3">
		<?php echo $status_sidebar; ?>
	</div>
</div>

<?php if (($action == "success") && ($view == "clear")) { ?>
<script type="text/javascript">
	localStorage.clear();
</script>
<?php } ?>

<!-- Modal -->
<div class="modal fade" id="noDupeModal" tabindex="-1" role="dialog" aria-labelledby="noDupeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="noDupeModalLabel"><?php echo $label_place_previously_selected; ?></h4>
      </div>
      <div class="modal-body">
      	<?php echo $evaluation_info_048; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo $label_close; ?></button>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Next Session Open -->
<div class="modal fade" id="next-session-open-modal" tabindex="-1" role="dialog" aria-labelledby="next-session-open-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="next-session-open-modal-label"><?php echo $label_please_note; ?></h4>
      </div>
      <div class="modal-body">
        <p><?php echo "<strong>".$evaluation_info_097."</strong> ".$evaluation_info_098; ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $label_stay_here; ?></button>
        <button type="button" class="btn btn-success" data-dismiss="modal" onclick="window.location.reload()"><?php echo $label_refresh; ?></button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

	var interval_onload = null;
    var interval_onfocus = null;
    var count_update_text = "<?php echo $brew_text_061; ?>";
    var count_paused_text = "<?php echo $brew_text_062; ?>";
    var count_paused_manually_text = "<?php echo $brew_text_064; ?>";
    var base_url = "<?php echo $base_url; ?>";
    var judging_started = "<?php if ($judging_started) echo "1"; else echo "0"; ?>";;
	var results_published = "<?php if ($show_presentation) echo "1"; else echo "0"; ?>";

	$("#resume-updates").hide();

	if (results_published == 1) {
		$(".updates-indicators").hide();
	}

	if (judging_started == 0) {
		$(".updates-indicators").hide();
	}
    
    // Function to update all counters
    function updateAllCounters(base_url) {

        // Initial counter call
        fetchRecordCount(base_url,'total-evaluations','0','evaluation');
        $('#icon-sync-total-evaluations').removeClass('hidden');
    	$('#icon-sync-total-evaluations').fadeIn();
    	setInterval(function() { 
            $('#icon-sync-total-evaluations').fadeOut();  
        }, 10000);

        setTimeout(function() {
            
            fetchRecordCount(base_url,'total-evaluations-unique','1','evaluation','eid','default');
	        $('#icon-sync-total-evaluations-unique').removeClass('hidden');
	    	$('#icon-sync-total-evaluations-unique').fadeIn();
	    	setInterval(function() { 
	            $('#icon-sync-total-evaluations-unique').fadeOut();  
	        }, 10000);

        }, 1000);


    }

    // Function to update all counters
    // JS dynamically generated in PHP loop
    function updateAllTableCounters(base_url) {

        <?php echo $status_sidebar_js; ?>

    }

	function stopUpdates() {
		clearInterval(interval_onload);
        clearInterval(interval_onfocus);
    	$("#stop-updates").hide();
    	$("#resume-updates").show();
    	$("#count-two-minute-info").text(count_paused_manually_text);
    	$(".refresh-link").fadeOut();
    	$(".refresh-link").addClass('hidden');
    	$(".fa-sync").addClass('hidden');
    }

    function resumeUpdates() {
    	clearInterval(interval_onload);
        clearInterval(interval_onfocus);
        updateAllCounters(base_url);
        setTimeout(function() {
        	updateAllTableCounters(base_url);
        }, 5000);
    	interval_onfocus = setInterval(function() { 
            updateAllCounters(base_url);
            setTimeout(function() {
            	updateAllTableCounters(base_url);
            }, 5000);
        }, 120000);
    	$("#stop-updates").show();
    	$("#resume-updates").hide();
    	$("#count-two-minute-info").text(count_update_text);
    }
    
    $(document).ready(function() {

        window.onload = function () {
        	if ((judging_started == 1) && (results_published == 0)) {
        		$(".refresh-link").addClass('hidden');
	            interval_onload = setInterval(function() { 
	                updateAllCounters(base_url);
	                setTimeout(function() {
	                	updateAllTableCounters(base_url);
	                }, 5000);
	            }, 120000);
	            $("#count-two-minute-info").text(count_update_text);
	        }
        };

        window.onfocus = function () {
            clearInterval(interval_onload);
            clearInterval(interval_onfocus);
            if ((judging_started == 1) && (results_published == 0)) {
	            updateAllCounters(base_url);
	            setTimeout(function() {
                	updateAllTableCounters(base_url);
                }, 5000);
	            interval_onfocus = setInterval(function() { 
	                updateAllCounters(base_url);
	                setTimeout(function() {
	                	updateAllTableCounters(base_url);
	                }, 5000);
	            }, 120000);
	            $("#count-two-minute-info").text(count_update_text);
	            $("#stop-updates").show();
	    		$("#resume-updates").hide();
	    	}
        };

        window.onblur = function () {
            clearInterval(interval_onload);
            clearInterval(interval_onfocus);
            if ((judging_started == 1) && (results_published == 0)) $("#count-two-minute-info").text(count_paused_text);
        };

    });

</script>