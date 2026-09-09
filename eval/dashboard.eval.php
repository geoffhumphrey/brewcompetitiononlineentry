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

$count_none = "";
$count_total = "";
$count_unique = "";

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
$db_conn->orderBy ("judgingDateEnd", "DESC");
$row_session_end = $db_conn->getOne ($prefix."judging_locations", null, "judgingDateEnd");
$totalRows_session_end = $db_conn->count;

if ((time() > $row_judging_prefs['jPrefsJudgingOpen']) && (time() < $row_judging_prefs['jPrefsJudgingClosed'])) $judging_open = TRUE;
if (($totalRows_session_end > 0) && (time() < $row_session_end['judgingDateEnd'])) $judging_open = TRUE;

if ($row_judging_prefs['jPrefsQueued'] == "Y") $queued = TRUE;
if (($view == "admin") && ($_SESSION['userLevel'] <= 1)) $admin = TRUE;
if ($admin) include(DB.'admin_common.db.php');

// If viewing in admin mode, present a quick form for Admins to add an
// evaluation on behalf of a judge.
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

$db_conn->orderBy ("tableNumber","ASC");
$row_table_assignments = $db_conn->get($prefix."judging_tables");
$totalRows_table_assignments = $db_conn->count;

// Everything below - judging-end/next-session countdowns, table assignment
// listings, per-table stats - is only ever built inside the
// "$totalRows_table_assignments > 0" block further down. With no judging
// tables configured yet, that block never runs and the page below just
// renders empty with no explanation (and, previously, left warnings.eval.php
// referencing undefined countdown variables). Surface that state instead of
// silently leaving the dashboard blank.
$no_table_data_alert = "";
if ($totalRows_table_assignments == 0) {
	$no_table_data_alert .= "<div class=\"alert alert-warning\">";
	$no_table_data_alert .= sprintf("<p><i class=\"fa fa-exclamation-triangle\"></i> <strong>%s</strong> No judging tables have been configured yet, so there's nothing to evaluate here.</p>",$label_attention);
	if ($admin) $no_table_data_alert .= sprintf("<p><a href=\"%s\">Add a judging table</a> and assign entries to it to populate this dashboard.</p>",$base_url."index.php?section=admin&amp;go=judging_tables&amp;action=add");
	$no_table_data_alert .= "</div>";
}

/**
 * Batch what used to be several queries per table (get_table_info() for its
 * location, assigned_judges(), an inline judge-names query, get_evaluation_count())
 * - plus, for admins, a score_style_data()/entries/score_entry_data() query per
 * style per table per entry - into a handful of upfront queries, run once. Same
 * pattern used throughout pub/, awards.php, export.output.php, and
 * admin/participants.admin.php this session.
 */

$all_table_ids_eval = array_column($row_table_assignments, 'id');

// Mirrors get_table_info(...,"location",...)'s own query - fetched once for every
// location instead of once per table.
$judging_locations_by_id_eval = array();
$rows_all_locations_eval = $db_conn->get($prefix."judging_locations", null, "id,judgingDate,judgingDateEnd,judgingLocName,judgingLocation,judgingLocType,judgingLocNotes");
foreach ($rows_all_locations_eval as $row_location_eval) {
	$judging_locations_by_id_eval[$row_location_eval['id']] = $row_location_eval;
}

// Mirrors assigned_judges($tid,...,1)'s query exactly, batched.
$assigned_judges_by_table_eval = array();
if ((!empty($all_table_ids_eval)) && (table_exists($judging_assignments_db_table))) {
	$db_conn->where('assignTable', $all_table_ids_eval, 'in');
	$db_conn->where('assignment', 'J');
	$db_conn->groupBy('assignTable');
	$rows_assigned_judges_eval = $db_conn->get($judging_assignments_db_table, null, "assignTable, COUNT(*) as count");
	foreach ($rows_assigned_judges_eval as $row_assigned_judges_eval) {
		$assigned_judges_by_table_eval[$row_assigned_judges_eval['assignTable']] = $row_assigned_judges_eval['count'];
	}
}

// Mirrors the inline judge-names query below (originally run per table, only for
// admins) exactly, batched across every table at once.
$judge_names_by_table_eval = array();
if ((!empty($all_table_ids_eval)) && (table_exists($judging_assignments_db_table)) && (table_exists($prefix."brewer"))) {
	$placeholders_eval = implode(',', array_fill(0, count($all_table_ids_eval), '?'));
	$query_judge_names_eval = "SELECT b.assignTable, a.brewerFirstName,a.brewerLastName FROM ".$prefix."brewer"." a, ".$judging_assignments_db_table." b WHERE b.assignTable IN (".$placeholders_eval.") AND a.uid = b.bid AND b.assignment='J' ORDER BY b.assignTable, a.brewerLastName, a.brewerFirstName ASC";
	$rows_judge_names_eval = $db_conn->rawQuery($query_judge_names_eval, $all_table_ids_eval);
	foreach ($rows_judge_names_eval as $row_judge_name_eval) {
		$judge_names_by_table_eval[$row_judge_name_eval['assignTable']][] = $row_judge_name_eval;
	}
}

// Mirrors get_evaluation_count("table-unique",...)'s query exactly, batched.
$eval_count_by_table_eval = array();
if (!empty($all_table_ids_eval)) {
	$db_conn->where('evalTable', $all_table_ids_eval, 'in');
	$db_conn->groupBy('evalTable');
	$rows_eval_counts_eval = $db_conn->get($prefix."evaluation", null, "evalTable, COUNT(DISTINCT eid) as count");
	foreach ($rows_eval_counts_eval as $row_eval_count_eval) {
		$eval_count_by_table_eval[$row_eval_count_eval['evalTable']] = $row_eval_count_eval['count'];
	}
}

// Admin-only: styles/entries/score_entry_data batching. Mirrors score_style_data(),
// includes/db's per-style brewing lookup, and score_entry_data() exactly - these
// were previously run once per style per table, then once per entry per style per
// table (the dominant per-entry cost on this page).
$styles_by_id_eval = array();
$brewing_by_style_eval = array();
$score_entry_data_by_eid_eval = array();
if ($admin) {

	$all_style_ids_eval = array();
	foreach ($row_table_assignments as $row_table_prefetch_eval) {
		foreach (explode(",", $row_table_prefetch_eval['tableStyles']) as $style_id_eval) {
			if ($style_id_eval !== "") $all_style_ids_eval[] = $style_id_eval;
		}
	}
	$all_style_ids_eval = array_unique($all_style_ids_eval);

	if (!empty($all_style_ids_eval)) {
		$db_conn->where('id', $all_style_ids_eval, 'in');
		$rows_all_styles_eval = $db_conn->get($prefix."styles", null, "id,brewStyleGroup,brewStyleNum,brewStyle,brewStyleType");
		foreach ($rows_all_styles_eval as $row_style_eval) {
			$styles_by_id_eval[$row_style_eval['id']] = $row_style_eval;
		}
	}

	// Mirrors the per-style brewing query (brewCategorySort/brewSubCategory/brewReceived=1)
	// exactly, just fetched once for every received entry instead of once per style.
	$db_conn->where('brewReceived', 1);
	$rows_all_entries_eval = $db_conn->get($prefix."brewing");
	foreach ($rows_all_entries_eval as $row_entry_eval) {
		$brewing_by_style_eval[$row_entry_eval['brewCategorySort'].'|'.$row_entry_eval['brewSubCategory']][] = $row_entry_eval;
	}

	// Mirrors score_entry_data()'s query exactly - getOne() with no ORDER BY, so it's
	// only guaranteed to return *a* matching row when more than one exists, same as here.
	$all_entry_ids_eval = array_column($rows_all_entries_eval, 'id');
	if (!empty($all_entry_ids_eval)) {
		$db_conn->where('eid', $all_entry_ids_eval, 'in');
		$rows_scores_eval = $db_conn->get($prefix."judging_scores", null, "id,eid,bid,scoreEntry,scorePlace,scoreMiniBOS");
		foreach ($rows_scores_eval as $row_score_eval) {
			if (!isset($score_entry_data_by_eid_eval[$row_score_eval['eid']])) $score_entry_data_by_eid_eval[$row_score_eval['eid']] = $row_score_eval;
		}
	}

}

$row_eval_sub = $db_conn->get($prefix."evaluation");
$totalRows_eval_sub = $db_conn->count;

$eval_scores = array();
$eval_judge_evaluations = array();
$eval_judge_tables = array();
$eval_no_evaluations = array();

if ($totalRows_eval_sub > 0) {

	foreach ($row_eval_sub as $row_eval_sub) {

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

	}

}

// Admin-only: pre-group $eval_scores by entry id - eliminates
// judging_admin.eval.php's O(entries x evaluations) linear scan of the full
// $eval_scores array for every single entry - and batch brewer_info() lookups for
// every judge who has scored anything, instead of one query per matching
// evaluation per entry (previously the single worst offender on this page: could
// be entries x judges-per-entry separate brewer queries).
$eval_scores_by_eid = array();
$brewer_info_string_by_judge_id = array();
if ($admin) {

	foreach ($eval_scores as $row_eval_score_grouped) {
		$eval_scores_by_eid[$row_eval_score_grouped['eid']][] = $row_eval_score_grouped;
	}

	$judge_ids_needed_eval = array();
	foreach ($eval_scores as $row_eval_score_judge) {
		$judge_ids_needed_eval[] = $row_eval_score_judge['judge_id'];
	}
	$judge_ids_needed_eval = array_unique($judge_ids_needed_eval);

	$brewer_rows_by_judge_id_eval = array();
	if ((!empty($judge_ids_needed_eval)) && (table_exists($prefix."brewer"))) {
		$db_conn->where('uid', $judge_ids_needed_eval, 'in');
		$rows_brewer_by_uid_eval = $db_conn->get($prefix."brewer");
		$found_judge_ids_eval = array();
		foreach ($rows_brewer_by_uid_eval as $row_brewer_info_eval) {
			$brewer_rows_by_judge_id_eval[$row_brewer_info_eval['uid']] = $row_brewer_info_eval;
			$found_judge_ids_eval[] = $row_brewer_info_eval['uid'];
		}
		// brewer_info() falls back to matching on `id` when a `uid` lookup misses -
		// mirror that for whichever judge ids weren't found above.
		$judge_ids_fallback_eval = array_diff($judge_ids_needed_eval, $found_judge_ids_eval);
		if (!empty($judge_ids_fallback_eval)) {
			$db_conn->where('id', $judge_ids_fallback_eval, 'in');
			$rows_brewer_by_id_eval = $db_conn->get($prefix."brewer");
			foreach ($rows_brewer_by_id_eval as $row_brewer_info_eval) {
				$brewer_rows_by_judge_id_eval[$row_brewer_info_eval['id']] = $row_brewer_info_eval;
			}
		}
	}

	// Mirrors brewer_info()'s exact string-building logic, batched, so
	// judging_admin.eval.php's explode("^", ...) parsing of the result is unaffected.
	foreach ($judge_ids_needed_eval as $judge_id_lookup_eval) {
		$row_brewer_info_eval = $brewer_rows_by_judge_id_eval[$judge_id_lookup_eval] ?? null;
		$ttb_eval = array();
		if (($_SESSION['prefsProEdition'] == 1) && (!empty($row_brewer_info_eval['brewerBreweryInfo']))) $ttb_eval = json_decode($row_brewer_info_eval['brewerBreweryInfo'],true);
		$r_eval = "";
		$r_eval .= $row_brewer_info_eval['brewerFirstName']."^";
		$r_eval .= $row_brewer_info_eval['brewerLastName']."^";
		$r_eval .= $row_brewer_info_eval['brewerPhone1']."^";
		if (isset($row_brewer_info_eval['brewerJudgeRank'])) {
			if (($row_brewer_info_eval['brewerJudgeMead'] == "Y") && ($row_brewer_info_eval['brewerJudgeRank'] == "Non-BJCP")) $r_eval .= "Non-BJCP Beer^";
			else $r_eval .= $row_brewer_info_eval['brewerJudgeRank']."^";
		}
		else $r_eval .= "Non-BJCP^";
		if (isset($row_brewer_info_eval['brewerJudgeID'])) $r_eval .= $row_brewer_info_eval['brewerJudgeID']."^"; else $r_eval .= "&nbsp;^";
		$r_eval .= $row_brewer_info_eval['brewerMHP']."^";
		$r_eval .= $row_brewer_info_eval['brewerEmail']."^";
		$r_eval .= $row_brewer_info_eval['uid']."^";
		if (isset($row_brewer_info_eval['brewerClubs'])) $r_eval .= $row_brewer_info_eval['brewerClubs']."^"; else $r_eval .= "&nbsp;^";
		if (isset($row_brewer_info_eval['brewerDiscount'])) $r_eval .= $row_brewer_info_eval['brewerDiscount']."^"; else $r_eval .= "&nbsp;^";
		$r_eval .= $row_brewer_info_eval['brewerAddress']."^";
		$r_eval .= $row_brewer_info_eval['brewerCity']."^";
		$r_eval .= $row_brewer_info_eval['brewerState']."^";
		$r_eval .= $row_brewer_info_eval['brewerZip']."^";
		$r_eval .= $row_brewer_info_eval['brewerCountry']."^";
		if ($_SESSION['prefsProEdition'] == 1) $r_eval .= $row_brewer_info_eval['brewerBreweryName']."^"; else $r_eval .= "&nbsp;^";
		if ($row_brewer_info_eval['brewerJudgeMead'] == "Y") $r_eval .= "Certified Mead Judge"; else $r_eval .= "&nbsp;^";
		if (($_SESSION['prefsProEdition'] == 1) && (isset($ttb_eval['TTB'])) && (!empty($ttb_eval['TTB']))) $r_eval .= $ttb_eval['TTB']."^"; else $r_eval .= "&nbsp;^";
		if (($_SESSION['prefsProEdition'] == 1) && (isset($ttb_eval['Production'])) && (!empty($ttb_eval['Production']))) $r_eval .= $ttb_eval['Production']."^"; else $r_eval .= "&nbsp;^";
		$brewer_info_string_by_judge_id[$judge_id_lookup_eval] = $r_eval;
	}

}

$total_scored_entries_count = 0;
$total_entries_count = 0;
$status_sidebar_table_info = "";
$status_sidebar_js = "";
$status_sidebar_js_icons = "";
$status_sidebar_js_timing = 0;

if ($totalRows_table_assignments > 0) {

	$table_assignment_start = array();

	foreach ($row_table_assignments as $row_table_assignments) {

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
		
		// Pulled from the batched locations map above instead of a fresh
		// get_table_info(...,"location",...) query per table.
		$row_location_lookup_eval = $judging_locations_by_id_eval[$tbl_loc_disp] ?? null;
		$table_location = "";
		if ($row_location_lookup_eval) {
			$table_location =
			$row_location_lookup_eval['judgingDate']."^".
			$row_location_lookup_eval['judgingDateEnd']."^".
			$row_location_lookup_eval['judgingLocName']."^".
			$row_location_lookup_eval['judgingLocation']."^".
			$row_location_lookup_eval['judgingLocType']."^".
			$row_location_lookup_eval['judgingLocNotes'];
		}
		$table_location = explode("^", $table_location);

		if (!empty($table_location[0])) $location_start_date = $table_location[0];
		else $location_start_date = time();

		$table_assignment_start[] = $location_start_date;

		// Per-table/session defense-in-depth signal: is THIS table's own judging session
		// currently active, independent of the global jPrefsJudgingOpen/jPrefsJudgingClosed
		// window? Protects against a misconfigured or stale global window when this specific
		// session's own dates say judging should be happening right now (see issue #1607).
		$table_session_open = TRUE;
		if ((!empty($table_location[0])) && (time() < $table_location[0])) $table_session_open = FALSE;
		if ((!empty($table_location[1])) && (time() > $table_location[1])) $table_session_open = FALSE;

		/**
		 * Open up for non-admins 10 minutes before the stated session start time.
		 * Useful when judging is in-person and judges wish to review their assigned
		 * entries prior to "officially" starting.
		 * Uses $diff var.
		 */

		if (($admin) || ((!$admin) && (time() > ($table_location[0] - $diff)))) { 

			if ((!empty($table_location[1]) && (time() > $table_location[1]))) $disable_add_edit = TRUE;

			$random = random_generator(7,2);
			// Pulled from the batched counts map above instead of a fresh
			// assigned_judges() query per table.
			$assigned_judges = $assigned_judges_by_table_eval[$tbl_id] ?? 0;
			
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

			if (((isset($_SESSION['jPrefsTablePlanning'])) && ($_SESSION['jPrefsTablePlanning'] == 0)) || (!isset($_SESSION['jPrefsTablePlanning']))) {
				
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

					$db_conn->where ("id", $tbl_id);
					$row_tables = $db_conn->getOne($prefix."judging_tables", null, "tableStyles");
					$totalRows_tables = $db_conn->count;

					$a = explode(",", $row_tables['tableStyles']);

				}
				
				sort($a);

				foreach (array_unique($a) as $value) {

					// Pulled from the batched styles map above instead of a fresh
					// score_style_data() query per style per table (admins only -
					// the non-admin judging dashboard's style list isn't batched,
					// so it keeps calling the function directly).
					if ($admin) {
						$row_style_lookup_eval = $styles_by_id_eval[$value] ?? null;
						$score_style_data = "";
						if ($row_style_lookup_eval) {
							$score_style_data = $row_style_lookup_eval['brewStyleGroup']."^".$row_style_lookup_eval['brewStyleNum']."^".$row_style_lookup_eval['brewStyle']."^".$row_style_lookup_eval['brewStyleType'];
						}
					}
					else {
						$score_style_data = score_style_data($value);
					}

					if (!empty($score_style_data)) {

						$score_style_data = explode("^",$score_style_data);

						// Pulled from the batched brewing-by-style map above instead of a
						// fresh per-style query (admins only, same reasoning as above).
						if ($admin) {
							$row_entries = $brewing_by_style_eval[$score_style_data[0].'|'.$score_style_data[1]] ?? array();
							$totalRows_entries = count($row_entries);
						}
						else {
							$db_conn->where ("brewCategorySort", $score_style_data[0]);
							$db_conn->where ("brewSubCategory", $score_style_data[1]);
							$db_conn->where ("brewReceived", 1);
							$row_entries = $db_conn->get ($prefix."brewing");
							$totalRows_entries = $db_conn->count;
						}

				        if ($totalRows_entries > 0) {

				        	foreach ($row_entries as $row_entries) {

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
								// Pulled from the batched judging_scores map above instead of a
								// fresh score_entry_data() query per entry (admins only, same
								// reasoning as above) - the single worst per-row offender on
								// this page before batching.
								if ($admin) {
									$row_score_lookup_eval = $score_entry_data_by_eid_eval[$row_entries['id']] ?? null;
									$score_entry_data = "";
									if ($row_score_lookup_eval) {
										$score_entry_data =
										$row_score_lookup_eval['id']."^".
										$row_score_lookup_eval['eid']."^".
										$row_score_lookup_eval['bid']."^".
										$row_score_lookup_eval['scoreEntry']."^".
										$row_score_lookup_eval['scorePlace']."^".
										$row_score_lookup_eval['scoreMiniBOS'];
									}
								}
								else {
									$score_entry_data = score_entry_data($row_entries['id']);
								}
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
									$pouring_display .= "<li><strong>".$label_pouring.":</strong> ".h(translate_pouring_value($pouring_arr['pouring']))."</li>";
									if ((isset($pouring_arr['pouring_notes'])) && (!empty($pouring_arr['pouring_notes']))) $pouring_display .= "<li><strong>".$label_pouring_notes.":</strong> ".$pouring_arr['pouring_notes']."</li>";
									if ((isset($pouring_arr['pouring_rouse'])) && (!empty($pouring_arr['pouring_rouse']))) $pouring_display .= "<li><strong>".$label_rouse_yeast.":</strong> ".h(translate_pouring_rouse_value($pouring_arr['pouring_rouse']))."</li>";
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
					            if (($judging_open) || ($table_session_open) || ($admin) || ($scored_by_user)) {
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

					        } // end foreach

					    } // end if ($totalRows_entries > 0)

					} // end if (!empty($score_style_data)  

				} // end foreach

				if (empty($table_assignment_data)) $table_assignment_data .= "<tr><td colspan=\"4\">".$evaluation_info_016."</td></tr>";
				
				$table_assignment_post .= "</tbody>";
				$table_assignment_post .= "</table>";

				$table_assignment_post .= "<p><small><a href=\"#top\"><i class=\"fa fa-sm fa-arrow-circle-up\"></i> Top</a></small></p>";
			}

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
				 * the associated ajax calls.
				 * -------------------------------------------
				 */

				// Pulled from the batched counts map above instead of a fresh
				// get_evaluation_count("table-unique",...) query per table.
				$table_scored_entries_count = $eval_count_by_table_eval[$tbl_id] ?? 0;
				
				$tbl_name_disp = truncate($tbl_name_disp,"25","...");
				$status_sidebar_timing = $status_sidebar_js_timing += 2000;
				$status_sidebar_js .= sprintf("
					setTimeout(function() {
						fetchRecordCount(ajax_url,'total-evaluations-table-%s','1','evaluation','eid','table','evalTable','%s');
						$('.refresh-link-table-%s').removeClass('hidden');
			        	$('.refresh-link-table-%s').fadeIn('fast');
						$('.icon-sync-table-%s').removeClass('hidden');
			        	$('.icon-sync-table-%s').fadeIn('fast');
			        	setInterval(function() { 
			                $('.icon-sync-table-%s').fadeOut('fast');
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

				if ($table_entries_count == $table_scored_entries_count) {
					$table_assignment_stats .= "<div class=\"alert alert-success\">";
					if ((isset($_SESSION['jPrefsTablePlanning'])) && ($_SESSION['jPrefsTablePlanning'] == 1)) {
						$table_assignment_stats .= "<i class=\"fa fa-lg fa-info-circle\"></i> <strong>Tables Planning Mode enabled.</strong> Tables Competition Mode must be enabled view or entry evaluations at this table.";
					}
					else $table_assignment_stats .= sprintf("<i class=\"fa fa-lg fa-check-circle\"></i> <strong>%s</strong>",$evaluation_info_037);
					$table_assignment_stats .= "</div>";
				}
				
				$table_assignment_stats .= "<div class=\"row small bcoem-account-info\">";
				$table_assignment_stats .= "<div class=\"col col-lg-8 col-md-10 col-sm-12 col-xs-12\">";

				$assigned_judge_names_display = "";

				if ($assigned_judges > 0) {

					// Pulled from the batched judge-names map above instead of a fresh
					// query per table.
					$row_assigned_judge_names_list = $judge_names_by_table_eval[$tbl_id] ?? array();
					foreach ($row_assigned_judge_names_list as $row_assigned_judge_names) {
						$assigned_judge_names_display .= $row_assigned_judge_names['brewerFirstName']." ".$row_assigned_judge_names['brewerLastName'].", ";
					}

					$assigned_judge_names_display = rtrim($assigned_judge_names_display, ", ");

				}

				$table_assignment_stats .= "<section class=\"row\">";
				$table_assignment_stats .= "<div class=\"col col-lg-4 col-md-5 col-sm-5 col-xs-6\">";
				$table_assignment_stats .= "<strong>".$evaluation_info_025."</strong>";
				$table_assignment_stats .= "</div>";
				$table_assignment_stats .= "<div class=\"col col-lg-8 col-md-7 col-sm-7 col-xs-6\">";
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
					$table_assignment_stats .= "<div class=\"col col-lg-4 col-md-5 col-sm-5 col-xs-6\">";
					$table_assignment_stats .= "<strong>".$evaluation_info_043."</strong>";
					$table_assignment_stats .= "</div>";
					$table_assignment_stats .= "<div class=\"col col-lg-8 col-md-7 col-sm-7 col-xs-6\">";
					$table_assignment_stats .= $judge_names;
					$table_assignment_stats .= "</div>";
					$table_assignment_stats .= "</section>";
				}

				$table_assignment_stats .= "<section class=\"row\">";
				$table_assignment_stats .= "<div class=\"col col-lg-4 col-md-5 col-sm-5 col-xs-6\">";
				$table_assignment_stats .= "<strong>".$evaluation_info_039."</strong>";
				$table_assignment_stats .= "</div>";
				$table_assignment_stats .= "<div class=\"col col-lg-8 col-md-7 col-sm-7 col-xs-6\">";
				$table_assignment_stats .= $table_entries_count;
				$table_assignment_stats .= "</div>";
				$table_assignment_stats .= "</section>";

				$table_assignment_stats .= "<section class=\"row\">";
				$table_assignment_stats .= "<div class=\"col col-lg-4 col-md-5 col-sm-5 col-xs-6\">";
				$table_assignment_stats .= "<strong>".$evaluation_info_040."</strong>";
				$table_assignment_stats .= "</div>";
				$table_assignment_stats .= "<div class=\"col col-lg-8 col-md-7 col-sm-7 col-xs-6\">";
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

	} 

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
			if ($count_none == 1) $top_alert .= sprintf(" <button type=\"button\" style=\"margin-bottom: 15px;\" class=\"btn btn-default btn-xs\" data-toggle=\"collapse\" data-target=\"#no-eval\">%s %s <i class=\"fa fa-chevron-down small\"></i></button>",$count_none,$label_entry_without_eval);
			else $top_alert .= sprintf(" <button type=\"button\" style=\"margin-bottom: 15px;\" class=\"btn btn-default btn-xs\" data-toggle=\"collapse\" data-target=\"#no-eval\">%s %s <i class=\"fa fa-chevron-down small\"></i></button>",$count_none,$label_entries_without_eval);
			$top_alert .= "<section style=\"margin-bottom: 15px;\" class=\"collapse small\" id=\"no-eval\">";
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
<script src="<?php if (TESTING) echo $base_url."js_source/admin_ajax.js?t=".time(); else echo $js_url."admin_ajax.min.js"; ?>"></script>
<?php
} // end if ($totalRows_table_assignments > 0)

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

$show_alerts = TRUE;
if ((empty($total_evals_alert)) && (empty($places_alert)) && (empty($judge_score_disparity)) && (empty($assign_score_mismatch)) && (empty($dup_judge_evals_alert)) && (empty($single_evaluation)) && (empty($mini_bos_mismatch_alert))) $show_alerts = FALSE;

// Counts Sidebar

$sidebar_buttons = "";
$sidebar_buttons .= "<button class=\"btn btn-dark btn-sm btn-block\" type=\"button\" data-toggle=\"collapse\" data-target=\"#collapse-add-eval\" aria-expanded=\"false\" aria-controls=\"collapse-add-eval\">Add an Evaluation on Behalf of Judge</button>";

if ($show_alerts) $sidebar_buttons .= "<a class=\"btn btn-dark btn-sm btn-block\" role=\"button\" data-toggle=\"collapse\" href=\"#all-alerts\" aria-expanded=\"false\" aria-controls=\"latest-submitted\"><i style=\"padding-right: 5px;\" class=\"fa fa-chevron-down\"></i>Expand/Collapse Alerts</a>";

if ((!empty($latest_submitted_accordion)) || (!empty($latest_updated_accordion))) {
	if (!empty($latest_submitted_accordion)) $sidebar_buttons .= "<a class=\"btn btn-dark btn-sm btn-block\" role=\"button\" data-toggle=\"collapse\" href=\"#latest-submitted\" aria-expanded=\"false\" aria-controls=\"latest-submitted\"><i style=\"padding-right: 5px;\" class=\"fa fa-clock-o\"></i>Expand/Collapse 20 Latest Submitted</a>";
	if (!empty($latest_updated_accordion)) $sidebar_buttons .= "<a class=\"btn btn-dark btn-sm btn-block\" role=\"button\" data-toggle=\"collapse\" href=\"#latest-updated\" aria-expanded=\"false\" aria-controls=\"latest-updated\"><i style=\"padding-right: 5px;\" class=\"fa fa-clock-o\"></i>Expand/Collapse 20 Latest Updated</a>";
}

$buttons_small_viewport = "";
$buttons_small_viewport .= "<div class=\"bcoem-admin-element hidden-sm hidden-md hidden-lg\">";
$buttons_small_viewport .= $sidebar_buttons;
$buttons_small_viewport .= "<a href=\"#status-sidebar\" class=\"btn btn-dark btn-sm btn-block\" role=\"button\">View Status</a>";
$buttons_small_viewport .= "</div>";

$status_sidebar = "";
$status_sidebar .= "<div class=\"bcoem-admin-element hidden-xs\">";
$status_sidebar .= $sidebar_buttons;
$status_sidebar .= "</div>";
$status_sidebar .= "<a name=\"status-sidebar\"></a>";
$status_sidebar .= "<div class=\"panel panel-info\">";
$status_sidebar .= "<div class=\"panel-heading\">";

$status_sidebar .= "<h4 style=\"margin: 0px; padding-bottom: 5px;\">Status<span class=\"fa fa-2x fa-bar-chart text-info pull-right\"></span></h4>";

$status_sidebar .= "<p style=\"margin: 0px;\" class=\"small text-muted\"><span class=\"small\">Updated <span id=\"eval-count-new-timestamp\">".getTimeZoneDateTime($_SESSION['prefsTimeZone'], time(), $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "short", "date-time")."</span></span></p>";

if (!HOSTED) $status_sidebar .= "<p style=\"margin: 0px;\" class=\"small text-muted updates-indicators\"><span class=\"small\" id=\"count-two-minute-info\">".$brew_text_061."</span></p>";

$status_sidebar .= "<p style=\"margin: 0px;\" class=\"small text-muted updates-indicators\">";
$status_sidebar .= "<span class=\"small\"><a href=\"#\" onClick=\"window.location.reload()\">Refresh this page</a> to review updated evaluations and/or consensus scores.</span></span>";
$status_sidebar .= "</p>";

/*
$status_sidebar .= "<p style=\"margin: 0px;\" class=\"small text-muted updates-indicators\">";
$status_sidebar .= "<span class=\"small\" id=\"resume-updates\"><a href=\"#\" class=\"hide-loader\" onclick=\"resumeUpdates()\">Resume Updates</a></span>";
$status_sidebar .= "<span class=\"small\" id=\"stop-updates\"><a href=\"#\" class=\"hide-loader\" onclick=\"stopUpdates()\">Pause Updates</a> <a href=\"#\" class=\"hide-loader pull-right\" onclick=\"resumeUpdates()\">Update Now</a></span>";
$status_sidebar .= "</p>";
*/

$status_sidebar .= "<div class=\"updates-indicators small\" style=\"margin-top: 5px;\">";
if (!HOSTED) {
	$status_sidebar .= "<span class=\"small\" id=\"resume-updates\">";
	$status_sidebar .= "<button class=\"btn btn-primary btn-xs\" onclick=\"resumeUpdates()\"><i class=\"fa fa-xs fa-play\" style=\"padding-right:5px;\"></i> Resume Updates</button>";
	$status_sidebar .= "</span>";
}
$status_sidebar .= "<span class=\"small\" id=\"stop-updates\">";
$status_sidebar .= "<button href=\"#\" class=\"btn btn-primary btn-xs\" onclick=\"resumeUpdates()\"><i class=\"fa fa-xs fa-exchange\" style=\"padding-right:5px;\"></i> Update Status Now</button>";
if (!HOSTED) $status_sidebar .= "<button class=\"btn btn-primary btn-xs pull-right\" onclick=\"stopUpdates()\"><i class=\"fa fa-xs fa-pause\" style=\"padding-right:5px;\"></i> Pause Updates</button>";
$status_sidebar .= "</span>";
$status_sidebar .= "</div>";

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

$status_sidebar .= "<section style=\"margin: 15px 0 8px 0; border-bottom: 1px solid #dedede;\" class=\"bcoem-sidebar-panel\">";
$status_sidebar .= "<small><strong class=\"text-info\">Evaluations</strong><span class=\"pull-right\">Count / Total</span></small>";
$status_sidebar .= "</section>";
$status_sidebar .= "<div class=\"small\">";
$status_sidebar .= $status_sidebar_table_info;
$status_sidebar .= "</div>";
$status_sidebar .= "</div>"; // end panel-body
$status_sidebar .= "</div>"; // end panel panel-info

$left_side = "";

if ($show_alerts) {
	$left_side .= "<div id=\"all-alerts\" class=\"collapse in\">";
	if (!empty($total_evals_alert)) $left_side .= $total_evals_alert;
	if (!empty($places_alert)) $left_side .= $places_alert;
	if (!empty($judge_score_disparity)) $left_side .= $jscore_disparity;
	if (!empty($assign_score_mismatch)) $left_side .= $assign_score_mismatch;
	if (!empty($dup_judge_evals_alert)) $left_side .= $dup_judge_evals_alert;
	if (!empty($single_evaluation)) $left_side .= $single_eval;
	if (!empty($mini_bos_mismatch_alert)) $left_side .= $mini_bos_mismatch_alert;
	$left_side .= "</div>";
}

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
<a name="top"></a>
<div class="row">
	<div class="col-xs-12 col-sm-6 col-md-9">
		<?php 
		include (EVALS.'import_scores.eval.php');
		echo $buttons_small_viewport;
		echo $left_side;
		echo $no_table_data_alert;
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
    var interval_timeout = null;

    var count_update_text = "Counts are updated every five minutes.";
    var count_paused_text = "<?php echo $brew_text_062; ?>";
    var count_paused_manually_text = "<?php echo $brew_text_064; ?>";
    var count_timeout_text = "<?php echo $brew_text_065; ?>";

    var base_url = "<?php echo $base_url; ?>";
	var ajax_url = "<?php echo $ajax_url; ?>";
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
    function updateAllCounters(ajax_url) {
	
        // Initial counter call
        fetchRecordCount(ajax_url,'total-evaluations','0','evaluation');
        $('#icon-sync-total-evaluations').removeClass('hidden');
    	$('#icon-sync-total-evaluations').fadeIn('fast');
    	setInterval(function() { 
            $('#icon-sync-total-evaluations').fadeOut('fast');  
        }, 10000);

        setTimeout(function() {
            
            fetchRecordCount(ajax_url,'total-evaluations-unique','1','evaluation','eid','default');
	        $('#icon-sync-total-evaluations-unique').removeClass('hidden');
	    	$('#icon-sync-total-evaluations-unique').fadeIn('fast');
	    	setInterval(function() { 
	            $('#icon-sync-total-evaluations-unique').fadeOut('fast');  
	        }, 10000);

        }, 1000);

    }

    // Function to update all counters
    // JS dynamically generated in PHP loop
    function updateAllTableCounters(ajax_url) {

        <?php echo $status_sidebar_js; ?>

    }

	function stopUpdates() {
		clearInterval(interval_onload);
        clearInterval(interval_onfocus);
        clearInterval(interval_timeout);
    	$("#stop-updates").hide();
    	$("#resume-updates").show();
    	$("#count-two-minute-info").text(count_paused_manually_text);
    	$(".refresh-link").fadeOut('fast');
    	$(".refresh-link").addClass('hidden');
    	$(".fa-sync").addClass('hidden');
    }

    function resumeUpdates() {
    	clearInterval(interval_onload);
        clearInterval(interval_onfocus);
        clearInterval(interval_timeout);
        updateAllCounters(ajax_url);
        updateDateTime(ajax_url);
        interval_timeout = setTimeout(function() {
        	updateAllTableCounters(ajax_url);
        }, 5000);
        
        <?php if (!HOSTED) { ?>
    	interval_onfocus = setInterval(function() { 
            updateAllCounters(ajax_url);
            updateDateTime(ajax_url);  
            setTimeout(function() {
            	updateAllTableCounters(ajax_url);
            }, 5000);
        }, 300000);
        $("#resume-updates").hide();
    	<?php } ?>
    	
    	$("#stop-updates").show();
    	$("#count-two-minute-info").text(count_update_text);
    }

    function updateDateTime(ajax_url) {
    	fetchRecordCount(ajax_url,'eval-count-new-timestamp','0','updated-display');
    }

    <?php if (!HOSTED) { ?>
    
    $(document).ready(function() {

        window.onload = function () {
        	clearInterval(interval_onload);
            clearInterval(interval_onfocus);
        	if ((judging_started == 1) && (results_published == 0)) {
        		$(".refresh-link").addClass('hidden');
	            interval_onload = setInterval(function() {
	            	updateDateTime(ajax_url); 
	                updateAllCounters(ajax_url);
	                setTimeout(function() {
	                	updateAllTableCounters(ajax_url);
	                }, 5000);
	            }, 300000);
	            interval_timeout = setTimeout(function() {
                    stopUpdates();
                    $("#count-two-minute-info").text(count_timeout_text);
                }, 1200000);
	            $("#count-two-minute-info").text(count_update_text);
	        }
        }

        window.onfocus = function () {
            clearInterval(interval_onload);
            clearInterval(interval_onfocus);
            if ((judging_started == 1) && (results_published == 0)) {
	            updateDateTime(ajax_url);
	            updateAllCounters(ajax_url);  
	            setTimeout(function() {
                	updateAllTableCounters(ajax_url);
                }, 5000);
	            interval_onfocus = setInterval(function() { 
	                updateDateTime(ajax_url);  
	                updateAllCounters(ajax_url);
	                setTimeout(function() {
	                	updateAllTableCounters(ajax_url);
	                }, 5000);
	            }, 300000);
	            interval_timeout = setTimeout(function() {
                    stopUpdates();
                    $("#count-two-minute-info").text(count_timeout_text);
                }, 1200000);
	            $("#count-two-minute-info").text(count_update_text);
	            $("#stop-updates").show();
	    		$("#resume-updates").hide();
	    	}
        }

        window.onblur = function () {
            clearInterval(interval_onload);
            clearInterval(interval_onfocus);
            clearInterval(interval_timeout);
            if ((judging_started == 1) && (results_published == 0)) $("#count-two-minute-info").text(count_paused_text);
        }

    });

    <?php } ?>

</script>