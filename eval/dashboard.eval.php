<?php
/**
 * -------- User Judging/Evaluation Dashboard --------
 * 
 * Dashboard for judges to add/edit evaluations and scores
 * for entries they've judged.
 * Hooks:
 *    - Judge info
 *    - Table assignments
 *    - Flight assignments (if non-queued judging)
 *
 * TO DO:
 *    - Add check to see if all scores have been imported. If so, don't show or disable the import button.
 *    - Dynamically check at interval to see if entry currently evaluating has score entered by another judge.
 *
 *    -----------------------------------------------------------------------------------------
 * 
 * DONE:
 *    - Add an alert to Admin Dashboard to import scores if all scores haven't been recorded yet.  *** DONE ***
 *    - Add ability to view each of the evaluations recorded on the judging dashboard after close of judging window.  *** DONE ***
 *    - Add delete evaluation function for Admins. ***DONE***
 *    - Mead-specific scoresheet items for full form and printed scoresheets (https://bjcp.org/docs/SCP_MeadScoreSheet.pdf) ***DONE***
 *    - Cider-specific scoresheet items for full form and printed scoresheets (https://bjcp.org/docs/SCP_CiderScoreSheet.pdf) ***DONE***
 *    - Add buttons to view and print scoresheets for users (my account) when results are published. *** DONE ***
 *    - Add buttons to view and print scoresheets for admins (admin > entries) when available. *** DONE ***
 *    - Add abiltiy for admins to add an evaluation on behalf of a judge. *** DONE ***
 *    - Add a check on the Judging Dashboard to hide scores of any entries that are the judge's *** NOT NEEDED ***
 *    - Admin/organizer to open and close judging rounds. *** DONE ***
 *    - Add check to judging dashboard and my account for display. *** DONE ***
 *    - Make sure to create session variable in common.db.php from jPrefsJudgingOpen and jPrefsJudgingClose vars *** DONE *** 
 *    - Admin/organizer choose scoresheet type (full, checklist, or structured). *** DONE *** 
 *    - Make sure to create session variable in common.db.php from jPrefsScoresheet var. *** DONE ***
 *    - Add alert to Admin Dashboard if EVALUATION enabled and new jPrefs vars aren't defined in DB. *** DONE ***
 *    - Add Mangage Evaluations to Admin Essentials Menu > Scoring *** DONE *** 
 *    - Complete import of judges' scores function *** DONE *** 
 *    - Add retreival of entries with entered consensus scores and build json to display *** DONE *** 
 *    - Add alert of entered consensus scores that differ *** DONE *** 
 *    - Add Assigned Score field to scoresheet forms. *** DONE ***
 *    - Add Bottle Inspection items (Appropriate size, cap, fill level, label removal, etc. checkbox and comment box) *** DONE ***
 *    - Add check to make sure assigned scores match. If not, alert admin. *** DONE ***
 *    - Check if user is assigned to the flight that a entry is part of.  *** DONE ***
 *    - Check if user has submitted an evaluation for an entry; if so, show edit button if judging round open. *** DONE ***
 *    - Scoresheet type override for mead and cider *** DONE ***
 *    - Add Structured Version Scoresheet options
 *      - Cider http://dev.bjcp.org/download/bjcp-scoresheet-cstr.pdf/  *** DONE ***
 *      - Mead  http://dev.bjcp.org/download/bjcp-scoresheet-mstr.pdf/  *** DONE ***
 *      - Beer  http://dev.bjcp.org/download/bjcp-scoresheet-bstr.pdf/  *** DONE ***
 *    - Add elapsed time display.  *** DONE ***
 */

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && (!isset($base_url)))) {
	$redirect = "../../403.php";
	$redirect_go_to = sprintf("Location: %s", $redirect);
	header($redirect_go_to);
	exit();
}

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
if ($admin) {
	$admin_add_eval = "<h3>Add an Evaluation</h3>";
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
	$admin_add_eval .= sprintf("<button class=\"btn btn-success\" style=\"margin-top:5px;\" type=\"submit\">%s</button>",$label_add);
	$admin_add_eval .= "</div>";
	$admin_add_eval .= "</form>";
	$admin_add_eval .= "</div>"; // ./col
	$admin_add_eval .= "</div>"; // ./row
}

// If the judging window is not open, display the closed message
if ((!$judging_open) && (!$admin)) $header = sprintf("<p class=\"lead\">%s <small>%s</small></p>",$evaluation_info_022,$evaluation_info_023);

// If the judging window is open, query db and display
else {
	$header = sprintf("<p class=\"lead\">%s <small>%s</small></p>",$evaluation_info_000,$evaluation_info_008);
	if ($queued) $header .= sprintf("<div class=\"alert alert-info\"><p><strong>%s</strong>: %s</p><p>%s</p></div>",ucfirst(strtolower($label_please_note)),$evaluation_info_001,$evaluation_info_002); 
}
	
if ($admin) $query_table_assignments = sprintf("SELECT * FROM %s ORDER BY tableNumber ASC",$prefix."judging_tables");
else $query_table_assignments = sprintf("SELECT * FROM %s a, %s b WHERE a.bid='%s' AND a.assignment='%s' AND a.assignTable = b.id ORDER BY b.tableNumber",$prefix."judging_assignments",$prefix."judging_tables",$_SESSION['user_id'],"J");
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

	/*
	foreach ($eval_scores as $key => $value) {
		$entries_evaluated[] = $value['eid'];
	}

	$total_entries_eval = (count(array_unique($entries_evaluated)));
	*/
	
}

/*
print_r($eval_scores);
echo "<br>";
print_r($eval_judge_evaluations);
echo "<br>";
print_r($eval_judge_tables);
echo "<br>";
exit;
*/

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

		if ($admin) {
			$tbl_id = $row_table_assignments['id'];
			$tbl_name_disp = $row_table_assignments['tableName'];
			$tbl_loc_disp = $row_table_assignments['tableLocation'];
			$tbl_num_disp = $row_table_assignments['tableNumber'];
		}

		else {
			$tbl_id = $row_table_assignments['assignTable'];
			$table_name = get_table_info(1,"basic",$tbl_id,"default","default");
			$table_name = explode("^", $table_name);
			$tbl_name_disp = $table_name[1];
			$tbl_loc_disp = $table_name[2];
			$tbl_num_disp = $table_name[0];
			$table_assignments_user[] = $tbl_id;
		}
		
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
			$table_assignment_pre .= "<th width=\"10%\">".$label_number."</th>";
			$table_assignment_pre .= "<th width=\"20%\" class=\"hidden-xs\">".$label_style."</th>";
			$table_assignment_pre .= "<th width=\"35%\">".$label_notes."</th>";
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
				\"aoColumns\": [
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
			        
					$query_entries = sprintf("SELECT id, brewBrewerID, brewStyle, brewCategorySort, brewCategory, brewSubCategory, brewInfo, brewJudgingNumber, brewName, brewPossAllergens, brewABV, brewJuiceSource, brewSweetnessLevel, brewMead1, brewMead2, brewMead3 FROM %s WHERE (brewCategorySort='%s' AND brewSubCategory='%s') AND brewReceived='1'", $prefix."brewing", $score_style_data[0], $score_style_data[1]);
					if ($_SESSION['prefsDisplaySpecial'] == "J") $query_entries .= " ORDER BY brewJudgingNumber ASC;";
					else $query_entries .= " ORDER BY id ASC;";
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
							
			        		$style = style_number_const($row_entries['brewCategorySort'],$row_entries['brewSubCategory'],$_SESSION['style_set_display_separator'],0);
							$style_display = $style.": ".$score_style_data[2];

							$info_display = "";
							$allergen_display = "";
							$abv_display = "";
							$juice_src_display = "";
							$carb_display = "";
							$sweetness_display = "";
							$sweetness_level_display = "";
							$strength_display = "";
							$additional_info = 0;
							
							if (!empty($row_entries['brewInfo'])) {
								$additional_info++;
								if (($_SESSION['prefsStyleSet'] == "BJCP2021") && ($row_entries['brewCategorySort'] == "02") && ($row_entries['brewSubCategory'] == "A")) $info_display .= "<strong>".$label_regional_variation; 
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
								$sweetness_level_display .= "<strong>".$label_final_gravity.":</strong> ".$row_entries['brewSweetnessLevel'];
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
								$abv_display .= "<strong>".$label_abv.":</strong> ".$row_entries['brewABV'];
							}

							if (($_SESSION['prefsStyleSet'] == "NWCiderCup") && (!empty($row_entries['brewJuiceSource']))) {

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

							$add_link = $base_url."index.php?section=evaluation&amp;go=scoresheet&amp;action=add&amp;filter=".$tbl_id."&amp;id=".$row_entries['id'];
							
			        		// Admin: Entry Evaluations
			        		if ($admin) include (EVALS.'judging_admin.eval.php');

			        		// Judging Dashboard
			        		else include (EVALS.'judging_dashboard.eval.php');
				            
				            // Build table data
				            if (($judging_open) || ($admin) || ((!$judging_open) && ($scored_by_user))) {
					            if ($add_disabled) $table_assignment_data .= "<tr class=\"text-muted\">";
					            elseif ((!$queued) && (!$add_disabled) && (!$admin)) $table_assignment_data .= "<tr class=\"text-primary\">";
					            else $table_assignment_data .= "<tr>";
					        	$table_assignment_data .= "<td><a class=\"anchor\" name=\"".$number."\"></a>".$number."</td>";
					        	$table_assignment_data .= "<td class=\"hidden-xs\">";
					        	$table_assignment_data .= $style_display;
					        	
					        	if ($additional_info > 0) {
					        		$table_assignment_data .= "<ul class=\"list-unstyled small\">";
					        		if (!empty($info_display)) $table_assignment_data .= "<li><em>".str_replace("^",", ",$info_display)."</em></li>";
					        		if (!empty($carb_display)) $table_assignment_data .= "<li><em>".$carb_display."</em></li>";
					        		if (!empty($sweetness_display)) $table_assignment_data .= "<li><em>".$sweetness_display."</em></li>";
					        		if (!empty($sweetness_level_display)) $table_assignment_data .= "<li><em>".$sweetness_level_display."</em></li>";
					        		if (!empty($allergen_display)) $table_assignment_data .= "<li><em>".$allergen_display."</em></li>";
					        		if (!empty($abv_display)) $table_assignment_data .= "<li><em>".$abv_display."</em></li>";
					        		if (!empty($juice_src_display)) $table_assignment_data .= "<li><em>".$juice_src_display."</em></li>";
					        		if (!empty($strength_display)) $table_assignment_data .= "<li><em>".$strength_display."</em></li>";
					        		$table_assignment_data .= "</ul>";
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

			$table_assignment_post .= "<p><a href=\"#top\"><i class=\"fa fa-sm fa-arrow-circle-up\"></i> Top</a></p>";

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
				$table_assignment_stats .= $table_scored_entries_count;
				$table_assignment_stats .= "</div>";
				$table_assignment_stats .= "</section>";

				$table_assignment_stats .= "</div>";
				$table_assignment_stats .= "</div>";

			}

			if (!$admin) {

				if ((strpos($row_table_assignments['assignRoles'], "HJ") !== false) && ($table_entries_count == $table_scored_entries_count)) {
					$table_assignment_stats .= "<div class=\"alert alert-success\">";
					$table_assignment_stats .= sprintf("<i class=\"fa fa-lg fa-check-circle\"></i> <strong>%s</strong> %s",$evaluation_info_037,$evaluation_info_038);
					$table_assignment_stats .= "</div>";
				}
				
				$table_assignment_stats .= "<div class=\"row small bcoem-account-info\">";
			
				if ($judging_open) {
					
					$table_assignment_stats .= "<div class=\"col col-xs-8\">";

					$table_assignment_stats .= "<section class=\"row\">";
					$table_assignment_stats .= "<div class=\"col col-md-5 col-sm-6 col-xs-8\">";
					$table_assignment_stats .= "<strong>".$evaluation_info_025."</strong>";
					$table_assignment_stats .= "</div>";
					$table_assignment_stats .= "<div class=\"col col-md-7 col-sm-6 col-xs-4\">";
					$table_assignment_stats .= $assigned_judges;
					$table_assignment_stats .= "</div>";
					$table_assignment_stats .= "</section>";

					$table_assignment_stats .= "<section class=\"row\">";
					$table_assignment_stats .= "<div class=\"col col-md-5 col-sm-6 col-xs-8\">";
					$table_assignment_stats .= "<strong>".$evaluation_info_039."</strong>";
					$table_assignment_stats .= "</div>";
					$table_assignment_stats .= "<div class=\"col col-md-7 col-sm-6 col-xs-4\">";
					$table_assignment_stats .= $table_entries_count;
					$table_assignment_stats .= "</div>";
					$table_assignment_stats .= "</section>";

					$table_assignment_stats .= "<section class=\"row\">";
					$table_assignment_stats .= "<div class=\"col col-md-5 col-sm-6 col-xs-8\">";
					$table_assignment_stats .= "<strong>".$evaluation_info_040."</strong>";
					$table_assignment_stats .= "</div>";
					$table_assignment_stats .= "<div class=\"col col-md-7 col-sm-6 col-xs-4\">";
					$table_assignment_stats .= $table_scored_entries_count;
					$table_assignment_stats .= "<small> / </small>".$table_entries_count;
					$table_assignment_stats .= "</div>";
					$table_assignment_stats .= "</section>";

					if ($queued) {
						$table_assignment_stats .= "<section class=\"row\">";
						$table_assignment_stats .= "<div class=\"col col-md-5 col-sm-6 col-xs-8\">";
						$table_assignment_stats .= "<strong>".$evaluation_info_042."</strong>";
						$table_assignment_stats .= "</div>";
						$table_assignment_stats .= "<div class=\"col col-md-7 col-sm-6 col-xs-4\">";
						$table_assignment_stats .= $user_flight_scored_entries_count;
						$table_assignment_stats .= "</div>";
						$table_assignment_stats .= "</section>";
					}
					
					if (!$queued) {
						$table_assignment_stats .= "<section class=\"row\">";
						$table_assignment_stats .= "<div class=\"col col-md-5 col-sm-6 col-xs-8\">";
						$table_assignment_stats .= "<strong>".$evaluation_info_041."</strong>";
						$table_assignment_stats .= "</div>";
						$table_assignment_stats .= "<div class=\"col col-md-7 col-sm-6 col-xs-4\">";
						$table_assignment_stats .= $flight_scored_entries_count;
						$table_assignment_stats .= "<small> / </small>".$user_flight_entries_count;
						$table_assignment_stats .= "</div>";
						$table_assignment_stats .= "</section>";

						$table_assignment_stats .= "<section class=\"row\">";
						$table_assignment_stats .= "<div class=\"col col-md-5 col-sm-6 col-xs-8\">";
						$table_assignment_stats .= "<strong>".$evaluation_info_042."</strong>";
						$table_assignment_stats .= "</div>";
						$table_assignment_stats .= "<div class=\"col col-md-7 col-sm-6 col-xs-4\">";
						$table_assignment_stats .= $user_flight_scored_entries_count;
						$table_assignment_stats .= "<small> / </small>".$user_flight_entries_count;
						$table_assignment_stats .= "</div>";
						$table_assignment_stats .= "</section>";

					}
					

					$table_assignment_stats .= "</div>";

				}
			
				if ($judging_open) $table_assignment_stats .= "<div class=\"col col-xs-4\">";
				else $table_assignment_stats .= "<div class=\"col col-xs-12\">";
				if (strpos($row_table_assignments['assignRoles'], "HJ") !== false) {
					$table_assignment_stats .= "<div class=\"text-right\"><span class=\"text-primary\"><i class=\"fa fa-gavel\"></i> ".$label_head_judge."</span></div>";
				}
				if (strpos($row_table_assignments['assignRoles'], "MBOS") !== false) {
					$table_assignment_stats .= "<div class=\"text-right\"><span class=\"text-success\"><i class=\"fa fa-trophy\"></i> ".$label_mini_bos_judge."</span></div>";
				}
				$table_assignment_stats .= "</div>";
				$table_assignment_stats .= "</div>";

				if ($judging_open) {
					$table_assignment_stats .= "<section class=\"row small\">";
					$table_assignment_stats .= "<div class=\"col col-xs-12\">";
					$table_assignment_stats .= sprintf("<em>%s</em>",$evaluation_info_007);
					$table_assignment_stats .= "</div>";
					$table_assignment_stats .= "</section>"; // end row
				}

			}

			$table_assignment_entries .= $table_places_display.$table_assignment_stats.$table_assignment_pre.$table_assignment_data.$table_assignment_post;
			
		} // end if (time() > $table_location[0])

	} while ($row_table_assignments = mysqli_fetch_assoc($table_assignments));

	asort($table_assignment_start);
	//print_r($table_assignment_start);

	$next_date = find_next($table_assignment_start,time(),0);
	$next_judging_date_open = getTimeZoneDateTime($_SESSION['prefsTimeZone'], ($next_date - $diff) , "999",  $_SESSION['prefsTimeFormat'], "short", "date-no-gmt");
	$current_or_past_sessions = count_past($table_assignment_start,time(),0);
	$future_sessions = count_future($table_assignment_start,time(),0);

	// Display a summary of table(s) the judge is assigned.
	// Include the "on the fly" judging form so the judge can
	// add an evalation for any entry they are not assigned to.
	if (!$admin) {

		if ($totalRows_table_assignments > 0) $table_assign_judge = table_assignments($_SESSION['user_id'],"J",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],0,$label_table);
		
		$assignment_display .= "<h2>".$label_table_assignments."</h2>";

		if ($next_date-$diff > time()) {
			$assignment_display .= "<div class=\"bcoem-admin-element\">".$evaluation_info_095."<strong> <span id=\"next-session-open\"></span></strong><br><span class=\"small text-muted\">".$evaluation_info_096."</span></div>";
			$assignment_display .= "<div class=\"bcoem-admin-element text-success\" id=\"next-session-refresh-button\"><strong>".$evaluation_info_097."</strong> ".$evaluation_info_098." <button type=\"button\" class=\"btn btn-success btn-sm\" onClick=\"window.location.reload()\">".$label_refresh."</button></div>";
		}

		$assignment_display .= "<div class=\"bcoem-admin-element\">";
		$assignment_display .= $evaluation_info_024;
		$assignment_display .= sprintf("<br><span class=\"small text-muted\">%s %s &#8226; %s %s</span>",$evaluation_info_099,$current_or_past_sessions,$evaluation_info_100,$future_sessions);
		$assignment_display .= "</div>";
		
		$assignment_display .= "<table id=\"judge_assignments\" class=\"table table-condensed table-striped table-bordered table-responsive\">";
		$assignment_display .= "<thead>";
		$assignment_display .= "<tr>";
		$assignment_display .= sprintf("<th>%s</th>",$label_session);
		$assignment_display .= sprintf("<th width=\"30%%\">%s</th>",$label_date);
		$assignment_display .= sprintf("<th width=\"30%%\">%s</th>",$label_table);
		$assignment_display .= "</tr>";
		$assignment_display .= "</thead>";
		$assignment_display .= "<tbody>";
		if (empty($table_assign_judge)) $assignment_display .= sprintf("<tr><td>%s</td><td>%s</td><td>%s</td></tr>",$label_na,$label_na,$label_na);
		else $assignment_display .= $table_assign_judge;
		$assignment_display .= "</tbody>";

		// On the fly form
		if ($judging_open) {
			$assignment_display .= "<tfoot>";
			$assignment_display .= "<tr>";
			$assignment_display .= sprintf("<td colspan=\"2\">%s<br><small><em>* %s</em></small></td>",$evaluation_info_011,$evaluation_info_012);
			$assignment_display .= "<td>";
			$assignment_display .= sprintf("<a class=\"btn btn-block btn-sm btn-default\" role=\"button\" href=\"#add-single-form\" data-toggle=\"collapse\" aria-expanded=\"false\" aria-controls=\"add-single-form\">%s</a>",$label_add);
			$assignment_display .= "<div class=\"collapse\" id=\"add-single-form\" style=\"margin-top:5px;\">";
			$assignment_display .= "<form class=\"hide-loader-form-submit\"  name=\"form1\" data-toggle=\"validator\" role=\"form\" action=\"".$base_url."index.php?section=evaluation&amp;go=scoresheet&amp;action=add\" method=\"post\">";
			$assignment_display .= "<div class=\"form-group small\" style=\"margin-top:5px;\">";
			$assignment_display .= sprintf("<label for=\"entry_number\">%s</label>",$label_entry_number);
			$assignment_display .= "<input id=\"entry-number-input\" name=\"entry_number\" type=\"text\" pattern=\".{6,6}\" maxlength=\"6\" class=\"form-control small\" style=\"width:100%;\" data-error=\"".$evaluation_info_015."\" required>";
			$assignment_display .= "<div class=\"help-block small with-errors\"></div>";
			$assignment_display .= "</div>";
			$assignment_display .= sprintf("<button class=\"btn btn-xs btn-success btn-block\" style=\"margin-top:5px;\" type=\"submit\">%s</button>",$label_go);
			$assignment_display .= "</form>";
			$assignment_display .= "</div>";
			$assignment_display .= "</td>";
			$assignment_display .= "</tr>";
			$assignment_display .= "</tfoot>";
		}
		
		$assignment_display .= "</table>";

	}

	// Build judge score disparity alert

	//print_r($judge_score_disparity);
	
	if (!empty($judge_score_disparity)) {
		$jscore_disparity .= "<div class=\"alert alert-warning\">";
		$jscore_disparity .= sprintf("<p><strong><i class=\"fa fa-exclamation-circle\"></i> %s %s</strong></p><p> %s</p>",$label_attention,$evaluation_info_036,$evaluation_info_018);
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

	// Build assigned score mismatch alert
	if (!empty($assigned_score_mismatch)) {
		$assign_score_mismatch .= "<div class=\"alert alert-warning\">";
		$assign_score_mismatch .= sprintf("<p><strong><i class=\"fa fa-exclamation-circle\"></i> %s %s</strong></p><p> %s</p>",$label_attention,$evaluation_info_017,$evaluation_info_018);
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

	if (!empty($eval_judge_evaluations)) {

	}

	// Build assigned score mismatch alert
	if (!empty($duplicate_judge_evals_alert)) {
		$dup_judge_evals_alert .= "<div class=\"alert alert-warning\">";
		$dup_judge_evals_alert .= sprintf("<p><strong><i class=\"fa fa-exclamation-circle\"></i> %s %s</strong> %s</p><p> %s</p>",$label_attention,$evaluation_info_032,$evaluation_info_033,$evaluation_info_018);
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
		$single_eval .= "<div class=\"alert alert-warning\">";
		$single_eval .= sprintf("<p><strong><i class=\"fa fa-exclamation-circle\"></i> %s</strong></p><p>%s</p>",$label_attention,$evaluation_info_019);
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
		$places_alert .= "<div class=\"alert alert-danger\">";
		$places_alert .= sprintf("<p><strong><i class=\"fa fa-exclamation-circle\"></i> %s</strong></p><p>%s</p>",$label_attention,$evaluation_info_029);
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
		$mini_bos_mismatch_alert .= "<div class=\"alert alert-info\">";
		$mini_bos_mismatch_alert .= sprintf("<p><strong><i class=\"fa fa-info-circle\"></i> %s</strong></p><p>%s</p>",$label_please_note,$evaluation_info_105);
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
	if (!$admin) include (EVALS.'judging_not_assigned.eval.php');

		$total_evals_alert .= "<div class=\"alert alert-teal\">";

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
		$count_unique = get_evaluation_count('unique');

		if (($admin) && ($totalRows_eval_sub > 0)) {

			$total_evals_alert .= sprintf("<i style=\"padding-right: 5px;\" class=\"fa fa-comments-o\"></i><strong>%s</strong> %s %s %s, %s.", $totalRows_eval_sub, $evaluation_info_031, strtolower($reg_closed_text_005), $current_time, $current_date_display);
			
			if (($judging_open && (time() > $two_days)) && ($count_none > 0)) {
				if ($count_none == 1) $total_evals_alert .= sprintf(" <button type=\"button\" class=\"btn btn-default btn-xs\" data-toggle=\"collapse\" data-target=\"#no-eval\">%s %s <i class=\"fa fa-chevron-down small\"></i></button>",$count_none,$label_entry_without_eval);
				else $total_evals_alert .= sprintf(" <button type=\"button\" class=\"btn btn-default btn-xs\" data-toggle=\"collapse\" data-target=\"#no-eval\">%s %s <i class=\"fa fa-chevron-down small\"></i></button>",$count_none,$label_entries_without_eval);
				$total_evals_alert .= "<section style=\"margin-top: 15px;\" class=\"collapse small\" id=\"no-eval\">";
				$total_evals_alert .= sprintf("<p>%s:</p>",$evaluation_info_049);
				$total_evals_alert .= "<ul class=\"list-inline\">";
				asort($eval_no_evaluations);
				foreach ($eval_no_evaluations as $value) {
					$total_evals_alert .= "<li><a href=\"#".$value."\">".$value."</a></li>";
				}
				$total_evals_alert .= "</ul>";
				$total_evals_alert .= "</section>";
			}

			else {
				$total_evals_alert .= sprintf("<br><i style=\"padding-right: 5px;\" class=\"fa fa-check-circle\"></i><strong>%s</strong>: %s",$label_entries_with_eval,$count_unique);
				$total_evals_alert .= sprintf("<br><i style=\"padding-right: 5px;\" class=\"fa fa-times-circle\"></i><strong>%s</strong>: %s",$label_entries_without_eval,$count_none);
			}
		}

		if ($judging_open) $total_evals_alert .= sprintf("<p><i style=\"padding-right: 5px;\" class=\"fa fa-clock-o\"></i><strong>%s:</strong> <span id=\"judging-ends\"></span></p>", $label_judging_close);

		$total_evals_alert .= "</div>";

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

if ($admin) {
	$columns = array_column($date_submitted, "date_submitted");
	array_multisort($columns, SORT_DESC, $date_submitted);
	$date_submitted = array_unique($date_submitted, SORT_REGULAR);
	$show_submitted = 0;
	$latest_submitted_accordion = "";

	foreach ($date_submitted as $key => $value) {
		$show_submitted += 1;
		if ($show_submitted <=20) {
			$submitted_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $value['date_submitted'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");
			$latest_submitted_accordion .= sprintf("<li><a href=\"#%s\">%s</a> - %s%s: %s (%s)</li>",$value['brewJudgingNumber'],$value['brewJudgingNumber'],$value['brewCategorySort'],$value['brewSubCategory'],$value['brewStyle'],$submitted_date);
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
			$latest_updated_accordion .= sprintf("<li><a href=\"#%s\">%s</a> - %s%s: %s (%s)</li>",$value['brewJudgingNumber'],$value['brewJudgingNumber'],$value['brewCategorySort'],$value['brewSubCategory'],$value['brewStyle'],$updated_date);
		}
	}
}

if (!$admin) {
	echo $header;
	if (($judging_open) && (empty($table_assign_judge))) echo sprintf("<p>%s</p>",$evaluation_info_009);
}

if (!empty($total_evals_alert)) {
	if ($admin) echo $total_evals_alert;
	if ((!$admin) && ($judging_open)) echo $total_evals_alert;
}

if (!empty($places_alert)) echo $places_alert;
if (!empty($judge_score_disparity)) echo $jscore_disparity;
if (!empty($assign_score_mismatch)) echo $assign_score_mismatch;
if (!empty($dup_judge_evals_alert)) echo $dup_judge_evals_alert;
if (!empty($single_evaluation)) echo $single_eval;
if (!empty($mini_bos_mismatch_alert)) echo $mini_bos_mismatch_alert;

if ((!empty($latest_submitted_accordion)) || (!empty($latest_updated_accordion))) {
	echo "<div class=\"bcoem-admin-element\">";

	if (!empty($latest_submitted_accordion)) {
		echo "<a style=\"margin:0 10px 15px 0;\" class=\"btn btn-default\" role=\"button\" data-toggle=\"collapse\" href=\"#latest-submitted\" aria-expanded=\"false\" aria-controls=\"latest-submitted\"><i style=\"padding-right: 5px;\" class=\"fa fa-clock-o\"></i>20 Latest Submitted</a>";
	}

	if (!empty($latest_updated_accordion)) {
		echo "<a style=\"margin:0 10px 15px 0;\" class=\"btn btn-default\" role=\"button\" data-toggle=\"collapse\" href=\"#latest-updated\" aria-expanded=\"false\" aria-controls=\"latest-updated\"><i style=\"padding-right: 5px;\" class=\"fa fa-clock-o\"></i>20 Latest Updated</a>";
	}

}

if (!empty($latest_submitted_accordion)) {
	echo "<div id=\"latest-submitted\" class=\"collapse alert alert-teal\">";
	echo "<p><i style=\"padding-right: 5px;\" class=\"fa fa-clock-o\"></i>The <strong>20 most recently submitted</strong> evaluations:</p>";
	echo "<ul>";
	echo $latest_submitted_accordion;
	echo "</ul>";
	echo "</div>";
}

if (!empty($latest_updated_accordion)) {
	echo "<div id=\"latest-updated\" class=\"collapse alert alert-teal\">";
	echo "<p><i style=\"padding-right: 5px;\" class=\"fa fa-clock-o\"></i>The <strong>20 most recently updated</strong> evaluations:</p>";
	echo "<ul>";
	echo $latest_updated_accordion;
	echo "</ul>";
	echo "</div>";
}

if (!$admin) echo $assignment_display;
if (!empty($on_the_fly_display)) echo $on_the_fly_display;
if (($admin) || ((!empty($table_assign_judge)) && (!$admin))) {
	if ($admin) {
		include (EVALS.'import_scores.eval.php');
		echo $admin_add_eval;
	}
	echo $table_assignment_entries;
} 
?>
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
