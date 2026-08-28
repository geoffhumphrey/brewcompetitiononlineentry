<?php

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] > 1))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

require (DB.'admin_common.db.php');
include (LIB.'admin.lib.php');
include (LIB.'output.lib.php');
include (DB.'output_pullsheets.db.php');
include (INCLUDES.'scrubber.inc.php');

$queued = FALSE;
$tables_none = FALSE;
$tables_all = FALSE;

if ($_SESSION['jPrefsQueued'] == "Y") $queued = TRUE;
if (($go == "judging_tables") && ($totalRows_tables == 0)) $tables_none = TRUE;
if ((($go == "judging_tables") || ($go == "judging_locations") || ($go == "all_entry_info")) && ($id == "default")) $tables_all = TRUE;

// Mirrors style_convert()'s type "1" and type "9" lookups - both are called once
// per displayed entry row (one query each) across every branch of this file,
// despite the style rows they need being a small, fixed table. Fetched once
// here (so every $go branch below can reuse it) then matched in PHP against
// each type's exact WHERE-clause logic per style set, keeping the first row
// per key the same way getOne()/rawQueryOne() (no ORDER BY) would.
$style_set_ps = $_SESSION['prefsStyleSet'];
include (INCLUDES.'styles.inc.php');

$style_convert_type1_by_group_ps = array();
$style_convert_type9_by_key_ps = array();

$rows_all_styles_full_ps = $db_conn->get($styles_db_table, null, "brewStyleGroup,brewStyleNum,brewStyle,brewStyleVersion,brewStyleReqSpec,brewStyleStrength,brewStyleCarb,brewStyleSweet,brewStyleOwn,brewStyleType");
foreach ($rows_all_styles_full_ps as $row_style_full_ps) {

	$grp_ps = $row_style_full_ps['brewStyleGroup'];

	if ($style_set_ps == "BJCP2025") {
		$first_char_ps = mb_substr($grp_ps, 0, 1);
		$want_version_ps = ($first_char_ps == "C") ? "BJCP2025" : "BJCP2021";
		$match_1_ps = ($row_style_full_ps['brewStyleVersion'] == $want_version_ps);
		$match_9_ps = ($match_1_ps) || ($row_style_full_ps['brewStyleOwn'] == "custom");
	}
	elseif ($style_set_ps == "AABC2025") {
		$match_1_ps = ((($row_style_full_ps['brewStyleVersion'] == "AABC2025") && ($row_style_full_ps['brewStyleType'] == "2")) || (($row_style_full_ps['brewStyleVersion'] == "AABC2022") && ($row_style_full_ps['brewStyleType'] != "2")) || ($row_style_full_ps['brewStyleOwn'] == "custom"));
		$match_9_ps = $match_1_ps;
	}
	else {
		$match_1_ps = (($row_style_full_ps['brewStyleVersion'] == $style_set_ps) || ($row_style_full_ps['brewStyleOwn'] == "custom"));
		$match_9_ps = $match_1_ps;
	}

	if (($match_1_ps) && (!isset($style_convert_type1_by_group_ps[$grp_ps]))) $style_convert_type1_by_group_ps[$grp_ps] = $row_style_full_ps;

	if ($match_9_ps) {
		$key9_ps = $grp_ps.'|'.$row_style_full_ps['brewStyleNum'];
		if (!isset($style_convert_type9_by_key_ps[$key9_ps])) $style_convert_type9_by_key_ps[$key9_ps] = $row_style_full_ps;
	}
}

// Mirrors style_convert($number,1,...)'s exact logic, from the batched map above.
$style_convert_1_ps = function($number) use ($style_convert_type1_by_group_ps, $style_sets, $style_set_ps) {
	$style_convert = "";
	$row_style = $style_convert_type1_by_group_ps[$number] ?? null;
	if ($row_style) {
		$custom = ($row_style['brewStyleOwn'] != "bcoe");
		$padded_number = $number;
		if (is_numeric($padded_number)) $padded_number = sprintf('%02d', $padded_number);
		if ($custom) $style_convert = $row_style['brewStyle']." (Custom Style)";
		else {
			foreach ($style_sets as $style_set_data) {
				if (!empty($style_set_data)) {
					if ($style_set_data['style_set_name'] === $style_set_ps) {
						$style_set_cat = $style_set_data['style_set_categories'];
						if (!empty($style_set_cat)) $style_convert = $style_set_cat[$padded_number];
					}
				}
			}
		}
	}
	return $style_convert;
};

// Mirrors style_convert($style_special,9,...)'s exact logic, from the batched map above.
$style_convert_9_ps = function($style_special) use ($style_convert_type9_by_key_ps) {
	$style_convert = "";
	$number = explode("^", $style_special);
	$row_style = $style_convert_type9_by_key_ps[$number[0].'|'.$number[1]] ?? null;
	if (($row_style) && ($number[0] == "02") && ($number[1] == "A") && ($number[2] == "BJCP2021")) $row_style['brewStyleReqSpec'] = 1;
	if ($row_style) {
		$style_name = ($row_style['brewStyle'] == "Soured Fruit Beer") ? "Wild Specialty Beer" : $row_style['brewStyle'];
		$style_convert = $row_style['brewStyleGroup']."^".$row_style['brewStyleNum']."^".$style_name."^".$row_style['brewStyleVersion']."^".$row_style['brewStyleReqSpec']."^".$row_style['brewStyleStrength']."^".$row_style['brewStyleCarb']."^".$row_style['brewStyleSweet'];
	}
	return $style_convert;
};

/**
 * Batch what used to be 2 queries per style per table (output_pullsheets_entries.db.php)
 * plus a query per table (output_pullsheets_queued.db.php / number_of_flights() / the
 * flightRound lookup) plus a query per entry per flight iteration (check_flight_number())
 * plus a query per judging assignment (judge_info() / get_table_info("basic",...)) into a
 * handful of upfront queries, run once - fetched here (rather than scoped to a single $go
 * branch) so every branch in this file can reuse the same maps. Same pattern used
 * throughout this session.
 */

// Mirrors output_pullsheets_entries.db.php's style lookup - fetched once for
// every style instead of once per style per table.
$styles_by_id_ps = array();
$rows_all_styles_ps = $db_conn->get($styles_db_table, null, "id,brewStyleGroup,brewStyleNum");
foreach ($rows_all_styles_ps as $row_style_ps) {
	$styles_by_id_ps[$row_style_ps['id']] = $row_style_ps;
}

// Mirrors output_pullsheets_entries.db.php's brewing query exactly (its non-mini_bos
// branch - mini_bos uses a genuinely different join/query, left untouched) - fetched
// once for every entry instead of once per style per table. $received matches that
// file's own logic: TRUE everywhere except the judge_inventory view in Table Planning
// Mode, so both variants are kept and the judge_inventory lookup picks the right one.
$order_col_ps = ($view == "default") ? "brewJudgingNumber" : "id";
$entries_by_style_ps = array();
$db_conn->where('brewReceived', '1');
$db_conn->orderBy($order_col_ps, 'ASC');
$rows_all_entries_ps = $db_conn->get($brewing_db_table);
foreach ($rows_all_entries_ps as $row_entry_ps) {
	$entries_by_style_ps[$row_entry_ps['brewCategorySort'].'|'.$row_entry_ps['brewSubCategory']][] = $row_entry_ps;
}
$entries_by_style_all_ps = array();
if ($_SESSION['jPrefsTablePlanning'] == 1) {
	$db_conn->orderBy($order_col_ps, 'ASC');
	$rows_all_entries_unreceived_ps = $db_conn->get($brewing_db_table);
	foreach ($rows_all_entries_unreceived_ps as $row_entry_ps) {
		$entries_by_style_all_ps[$row_entry_ps['brewCategorySort'].'|'.$row_entry_ps['brewSubCategory']][] = $row_entry_ps;
	}
}

// Mirrors get_table_info(1,"count_total",...)'s exact condition - it only
// applies brewReceived='1' outside Tables Planning Mode, which can disagree
// with the entries actually listed above in Planning Mode (a pre-existing
// quirk, not introduced here) - so this is computed separately rather than
// just reusing counts of $entries_by_style_ps.
$count_by_style_ps = array();
if ($_SESSION['jPrefsTablePlanning'] != 1) {
	foreach ($entries_by_style_ps as $count_key_ps => $rows_count_ps) {
		$count_by_style_ps[$count_key_ps] = count($rows_count_ps);
	}
}
else {
	$db_conn->groupBy('brewCategorySort');
	$db_conn->groupBy('brewSubCategory');
	$rows_unfiltered_counts_ps = $db_conn->get($brewing_db_table, null, "brewCategorySort, brewSubCategory, COUNT(*) as count");
	foreach ($rows_unfiltered_counts_ps as $row_unfiltered_count_ps) {
		$count_by_style_ps[$row_unfiltered_count_ps['brewCategorySort'].'|'.$row_unfiltered_count_ps['brewSubCategory']] = $row_unfiltered_count_ps['count'];
	}
}

// Mirrors output_pullsheets_queued.db.php's per-table count, number_of_flights()'s
// per-table max flight lookup, check_flight_number()'s per-entry lookup, and the
// inline per-table-per-flight flightRound lookup used further below - all sourced
// from one fetch of the whole judging_flights table instead of one query each.
$flight_round_count_by_table_round_ps = array();
$max_flight_by_table_ps = array();
$flight_round_by_table_flight_ps = array();
$flight_by_entry_id_ps = array();
if (table_exists($judging_flights_db_table)) {
	$rows_all_flights_ps = $db_conn->get($judging_flights_db_table);
	foreach ($rows_all_flights_ps as $row_flight_ps) {
		$fr_key_ps = $row_flight_ps['flightTable'].'|'.$row_flight_ps['flightRound'];
		$flight_round_count_by_table_round_ps[$fr_key_ps] = ($flight_round_count_by_table_round_ps[$fr_key_ps] ?? 0) + 1;
		if ((!isset($max_flight_by_table_ps[$row_flight_ps['flightTable']])) || ($row_flight_ps['flightNumber'] > $max_flight_by_table_ps[$row_flight_ps['flightTable']])) {
			$max_flight_by_table_ps[$row_flight_ps['flightTable']] = $row_flight_ps['flightNumber'];
		}
		$flight_round_by_table_flight_ps[$row_flight_ps['flightTable'].'|'.$row_flight_ps['flightNumber']] = $row_flight_ps['flightRound'];
		// check_flight_number()'s getOne() has no ORDER BY, so it's only ever
		// guaranteed to return *a* matching row when more than one exists for
		// the same entry - keeping the first one found here matches that.
		if (!isset($flight_by_entry_id_ps[$row_flight_ps['flightEntryID']])) $flight_by_entry_id_ps[$row_flight_ps['flightEntryID']] = $row_flight_ps;
	}
}

// Mirrors get_table_info(1,"count_total",...) - sums received-entry counts
// across every style at a table, from the batched maps above.
$table_entry_count_ps = function($table_styles_csv) use ($styles_by_id_ps, $count_by_style_ps) {
	$total = 0;
	foreach (explode(",", $table_styles_csv) as $style_id_lookup) {
		if ($style_id_lookup === "") continue;
		$row_style_lookup = $styles_by_id_ps[$style_id_lookup] ?? null;
		if ($row_style_lookup) $total += $count_by_style_ps[$row_style_lookup['brewStyleGroup'].'|'.$row_style_lookup['brewStyleNum']] ?? 0;
	}
	return $total;
};

// Mirrors table_location()'s exact query/output - fetched once for every
// location instead of once per table.
$judging_locations_by_id_ps = array();
$rows_all_locations_ps = $db_conn->get($prefix."judging_locations");
foreach ($rows_all_locations_ps as $row_location_ps) {
	$judging_locations_by_id_ps[$row_location_ps['id']] = $row_location_ps;
}
$table_location_ps = function($table_location_id) use ($judging_locations_by_id_ps) {
	$loc = $judging_locations_by_id_ps[$table_location_id] ?? null;
	if (!$loc) return "";
	return $loc['judgingLocName'].", ".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $loc['judgingDate'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "long", "date-time-no-gmt");
};

// Mirrors number_of_flights()'s exact query, from the batched map above.
$number_of_flights_ps = function($table_id) use ($max_flight_by_table_ps) {
	return $max_flight_by_table_ps[$table_id] ?? "";
};

// Mirrors check_flight_number()'s method==0 behavior exactly (its only use in
// this file), from the batched map above.
$check_flight_number_ps = function($entry_id, $flight) use ($flight_by_entry_id_ps) {
	$row = $flight_by_entry_id_ps[$entry_id] ?? null;
	if (($row) && ($row['flightNumber'] == $flight)) return $row['flightRound'];
	return "";
};

// Mirrors get_table_info(1,"basic",...) - fetched once for every table instead
// of once per judging assignment (used by the all_entry_info judge_inventory view).
$tables_by_id_ps = array();
$rows_all_tables_basic_ps = $db_conn->get($judging_tables_db_table, null, "id,tableNumber,tableName,tableLocation,tableStyles");
foreach ($rows_all_tables_basic_ps as $row_table_basic_ps) {
	$tables_by_id_ps[$row_table_basic_ps['id']] = $row_table_basic_ps;
}

// Mirrors judge_info() - fetched once for every brewer/judging_assignments row
// instead of once (or twice, in non-queued judging) per judging assignment
// (used by the all_entry_info judge_inventory view).
$brewer_by_uid_ps = array();
$rows_all_brewer_ps = $db_conn->get($prefix."brewer", null, "id,uid,brewerFirstName,brewerLastName,brewerJudgeLikes,brewerJudgeDislikes,brewerJudgeMead,brewerJudgeCider,brewerJudgeRank,brewerJudgeID,brewerStewardLocation,brewerJudgeLocation,brewerJudgeExp,brewerJudgeNotes,brewerAssignment");
foreach ($rows_all_brewer_ps as $row_brewer_ps) {
	if (!isset($brewer_by_uid_ps[$row_brewer_ps['uid']])) $brewer_by_uid_ps[$row_brewer_ps['uid']] = $row_brewer_ps;
}
$judging_assignment_by_bid_ps = array();
if ($_SESSION['jPrefsQueued'] == "N") {
	$rows_all_judging_assignments_ps = $db_conn->get($prefix."judging_assignments", null, "bid,assignFlight,assignRound");
	foreach ($rows_all_judging_assignments_ps as $row_judging_assignment_ps) {
		if (!isset($judging_assignment_by_bid_ps[$row_judging_assignment_ps['bid']])) $judging_assignment_by_bid_ps[$row_judging_assignment_ps['bid']] = $row_judging_assignment_ps;
	}
}
$judge_info_ps = function($uid) use ($brewer_by_uid_ps, $judging_assignment_by_bid_ps) {
	$r = "";
	$row_brewer_info = $brewer_by_uid_ps[$uid] ?? null;
	if (!empty($row_brewer_info)) {
		$r =
		$row_brewer_info['brewerFirstName']
		."^".$row_brewer_info['brewerLastName']
		."^".$row_brewer_info['brewerJudgeLikes']
		."^".$row_brewer_info['brewerJudgeDislikes']
		."^".$row_brewer_info['brewerJudgeMead']
		."^".$row_brewer_info['brewerJudgeRank']
		."^".$row_brewer_info['brewerJudgeID']
		."^".$row_brewer_info['brewerStewardLocation']
		."^".$row_brewer_info['brewerJudgeLocation']
		."^".$row_brewer_info['brewerJudgeExp']
		."^".$row_brewer_info['brewerJudgeNotes']
		."^".$row_brewer_info['id']
		."^".$row_brewer_info['brewerJudgeCider'];
	}
	if (isset($row_brewer_info['brewerAssignment'])) $r .= "^".$row_brewer_info['brewerAssignment'];
	else $r .= "^";
	if ($_SESSION['jPrefsQueued'] == "N") {
		$row_judge_info = $judging_assignment_by_bid_ps[$uid] ?? null;
		if (!empty($row_judge_info)) $r .= "^".$row_judge_info['assignFlight']."^".$row_judge_info['assignRound'];
	}
	return $r;
};

$table_flight_thead = "";
$pullsheet_output = "";

if ($go == "judging_scores_bos") {
	$table_flight_thead .= "<tr>";
	$table_flight_thead .= "<th width=\"5%\" nowrap>".$label_pull_order."</th>";
	$table_flight_thead .= "<th width=\"5%\">#</th>";
	$table_flight_thead .= "<th width=\"5%\">".$label_table." ".$label_place."</th>";
	$table_flight_thead .= "<th width=\"20%\">".$label_style."</th>";
	$table_flight_thead .= "<th>".$label_info."</th>";
	$table_flight_thead .= "<th width=\"5%\" nowrap>".$label_box."</th>";
	$table_flight_thead .= "<th width=\"5%\" nowrap>".$label_score."</th>";
	$table_flight_thead .= "<th width=\"5%\" nowrap>".$label_bos."<br>".$label_place."</th>";
	$table_flight_thead .= "</tr>";
}

else {
	if ($go != "all_entry_info") {
		$table_flight_thead .= "<tr>";
		$table_flight_thead .= "<th width=\"5%\" nowrap>".$label_pull_order."</th>";
		$table_flight_thead .= "<th width=\"5%\">#</th>";
		$table_flight_thead .= "<th width=\"35%\">".$label_style."</th>";
		$table_flight_thead .= "<th width=\"35%\">".$label_info."</th>";
		$table_flight_thead .= "<th width=\"5%\" nowrap>".$label_box."</th>";
		if (($go != "judging_scores_bos") && ($go != "mini_bos") && ($filter != "mini_bos")) $table_flight_thead .= "<th width=\"5%\" nowrap>".$label_mini_bos."</th>";
		$table_flight_thead .= "<th width=\"5%\" nowrap>".$label_score."</th>";
		$table_flight_thead .= "<th width=\"5%\" nowrap>".$label_place."</th>";
		$table_flight_thead .= "</tr>";
	}
}

if ($go == "all_entry_info") {

	$show_table = FALSE;

	$table_flight = "";
	$table_flight_thead = "";
	$pullsheet_output = "";
	$round_count = array();

	$table_flight_thead .= "<tr>";
	$table_flight_thead .= "<th width=\"5%\">#</th>";
	$table_flight_thead .= "<th width=\"15%\">".$label_style."</th>";
	$table_flight_thead .= "<th width=\"15%\">".$label_required_info."</th>";
	$table_flight_thead .= "<th width=\"15%\">".$label_optional_info."</th>";
	$table_flight_thead .= "<th width=\"15%\">".$label_brewer_specifics."</th>";
	$table_flight_thead .= "<th width=\"15%\">".$label_possible_allergens."</th>";
	$table_flight_thead .= "<th>".$label_notes."</th>";
	$table_flight_thead .= "</tr>";

	if ($view == "judge_inventory") {

		/**
		 * Sort by individual judge.
		 * Loop through the judging_assignments DB table,
		 * grab the table info and associated entries
		 */

		include (DB.'output_assignments.db.php');

		$judge_inventory = array();

		if ($row_assignments) {

			foreach ($rows_assignments as $row_assignments) {

				$show_table = FALSE;
				// Pulled from the batched maps above instead of fresh
				// judge_info()/get_table_info() queries per judging assignment.
				$judge_info = $judge_info_ps($row_assignments['bid']);
				$judge_info = explode("^",$judge_info);

				$table_info_row_ps = $tables_by_id_ps[$row_assignments['assignTable']] ?? null;
				$table_info = ($table_info_row_ps) ? ($table_info_row_ps['tableNumber']."^".$table_info_row_ps['tableName']."^".$table_info_row_ps['tableLocation']."^".$table_info_row_ps['id']."^".$table_info_row_ps['tableStyles']) : "";
				$table_info = explode("^",$table_info);

				if (!isset($table_info[0])) $table_info[0] = "";
				if (!isset($table_info[1])) $table_info[1] = "";
				if (!isset($table_info[2])) $table_info[2] = "";
				if (!isset($table_info[3])) $table_info[3] = "";
				if (!isset($table_info[4])) $table_info[4] = "";
				
				$table_flight = "";
				$table_flight_datatables = "";
				$table_flight_tbody = "";
				$table_info_location = "";
				$table_info_notes = "";
				$table_info_header = "";
				$judge_inventory_output = "";
				$judge_roles = "";
				$random_sortable = random_generator(7,2);

				if ($location == "default") $show_table = TRUE;
				if ((isset($table_info[2])) && (($location != "default") && ($location == $table_info[2]))) $show_table = TRUE;

				if ($show_table) {

					if (!empty($table_info[4])) {

						$a = explode(",", $table_info[4]);

						$table_entry_count = 0;
						$judge_entry_count = 0;
						
						foreach (array_unique($a) as $value) {

							// Pulled from the batched styles/entries maps above instead
							// of a fresh output_pullsheets_entries.db.php query per style
							// per assignment - guarded to the mini_bos filter's own
							// (different, unbatched) query, which is left untouched, and
							// to Table Planning Mode's own (also different) received-flag
							// handling, which output_pullsheets_entries.db.php only applies
							// for this judge_inventory view.
							if ($filter == "mini_bos") {
								include (DB.'output_pullsheets_entries.db.php');
							}
							else {
								$row_style_ps_lookup = $styles_by_id_ps[$value] ?? null;
								$entries_map_ps = ($_SESSION['jPrefsTablePlanning'] == 1) ? $entries_by_style_all_ps : $entries_by_style_ps;
								$rows_entries = ($row_style_ps_lookup) ? ($entries_map_ps[$row_style_ps_lookup['brewStyleGroup'].'|'.$row_style_ps_lookup['brewStyleNum']] ?? array()) : array();
								$row_entries = (!empty($rows_entries)) ? $rows_entries[0] : null;
								$totalRows_entries = count($rows_entries);
							}

							if ($row_entries) {

								$style = style_number_const($row_entries['brewCategorySort'],$row_entries['brewSubCategory'],$_SESSION['style_set_display_separator'],1);
								$style_special = $row_entries['brewCategorySort']."^".$row_entries['brewSubCategory']."^".$_SESSION['prefsStyleSet'];

								foreach ($rows_entries as $row_entries) {

									if (!empty($row_entries['brewCategorySort'])) {

										$display_entry = TRUE;

										if ($_SESSION['jPrefsQueued'] == "N") {
											// Pulled from the batched flights map above instead
											// of a fresh check_flight_number() query per entry.
											$ji_flight_num = $flight_by_entry_id_ps[$row_entries['id']]['flightNumber'] ?? "";
											if ($ji_flight_num != $row_assignments['assignFlight']) $display_entry = FALSE;
											if ($ji_flight_num == $row_assignments['assignFlight']) $judge_entry_count += 1;
										}

										if ($display_entry) {

											$table_entry_count += 1;

											$table_flight_tbody .= "<tr>";

											$table_flight_tbody .= "<td nowrap>";
											if ($sort == "entry") $table_flight_tbody .= sprintf("%06s",$row_entries['id']);
											else $table_flight_tbody .= sprintf("%06s",$row_entries['brewJudgingNumber']);
											$table_flight_tbody .= "</td>";

											$table_flight_tbody .= "<td>";
											if ($_SESSION['prefsStyleSet'] == "BA") $table_flight_tbody .= $row_entries['brewStyle'];
											else $table_flight_tbody .= $style." ".$row_entries['brewStyle']."<em><br>".$style_convert_1_ps($row_entries['brewCategorySort'])."</em>";
											$table_flight_tbody .= "</td>";

											$special = $style_convert_9_ps($style_special);
											$special = explode("^",$special);

											$table_flight_tbody .= "<td>";
											if ((!empty($row_entries['brewInfo'])) && ($special[4] == "1")) $table_flight_tbody .= "<p>".str_replace("^","<br>",$row_entries['brewInfo'])."</p>";
											$table_flight_tbody .= "<p>";
											if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<strong>".$label_carbonation.": </strong> ".$row_entries['brewMead1']."<br>";
											if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<strong>".$label_sweetness.":</strong> ".$row_entries['brewMead2']."<br>";
											if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<strong>".$label_strength.":</strong> ".$row_entries['brewMead3']."<br>";
											if ((!empty($row_entries['brewPouring'])) && ((!empty($row_entries['brewStyleType'])) && ($row_entries['brewStyleType'] == 1))) {
												$pouring_arr = json_decode($row_entries['brewPouring'],true);
												$table_flight_tbody .= "<strong>".$label_pouring.":</strong> ".$pouring_arr['pouring']."<br>";
												if ((isset($pouring_arr['pouring_notes'])) && (!empty($pouring_arr['pouring_notes']))) $table_flight_tbody .= "<strong>".$label_pouring_notes.":</strong> ".$pouring_arr['pouring_notes']."<br>";
												$table_flight_tbody .= "<strong>".$label_rouse_yeast.":</strong> ".$pouring_arr['pouring_rouse']."<br>";
											}
											if (!empty($row_entries['brewABV'])) $table_flight_tbody .= "<strong>".$label_abv.":</strong> ".$row_entries['brewABV']."<br>";
											
											if (($_SESSION['prefsStyleSet'] == "NWCiderCup") && (!empty($row_entries['brewSweetnessLevel']))) $table_flight_tbody .= "<strong>".$label_final_gravity.":</strong> ".$row_entries['brewSweetnessLevel'];

											if (($_SESSION['prefsStyleSet'] != "NWCiderCup") && (!empty($row_entries['brewSweetnessLevel']))) {

												$sweetness_json = json_decode($row_entries['brewSweetnessLevel'],true);

												if (json_last_error() === JSON_ERROR_NONE) {
													if (!empty($sweetness_json['OG'])) $table_flight_tbody .= "<li><strong>".$label_original_gravity.":</strong> ".$sweetness_json['OG']."</li>";
													if (!empty($sweetness_json['FG'])) $table_flight_tbody .= "<li><strong>".$label_final_gravity.":</strong> ".$sweetness_json['FG']."</li>";
												}
												
												else {
													$table_flight_tbody .= "<strong>".$label_final_gravity.":</strong> ";
												}
									
											}
											
											$table_flight_tbody .= "</p>";
											$table_flight_tbody .= "</td>";

											$table_flight_tbody .= "<td>";
											if (!empty($row_entries['brewInfoOptional'])) $table_flight_tbody .= "<p>".$row_entries['brewInfoOptional']."</p>";
											$table_flight_tbody .= "</td>";

											$table_flight_tbody .= "<td>";
											if (!empty($row_entries['brewComments'])) $table_flight_tbody .= "<p>".$row_entries['brewComments']."</p>";
											$table_flight_tbody .= "</td>";

											$table_flight_tbody .= "<td>";
											if (!empty($row_entries['brewPossAllergens'])) $table_flight_tbody .= "<p>".$row_entries['brewPossAllergens']."</p>";
											$table_flight_tbody .= "</td>";

											$table_flight_tbody .= "<td>";
											if (!empty($row_entries['brewStaffNotes'])) $table_flight_tbody .= "<p>".$row_entries['brewStaffNotes']."</p>";
											$table_flight_tbody .= "</td>";

											$table_flight_tbody .= "</tr>";

										}

									}

								}

							}

						}

					}

					$table_info_header .= "<h2>";
					$table_info_header .= sprintf("%s %s: %s",$label_table,$table_info[0],$table_info[1]);
					if (!empty($row_assignments['assignRoles'])) {
						$table_info_header .= "<small>";
						$table_info_header .= "<em>";
						if (strpos($row_assignments['assignRoles'],"HJ") !== FALSE) $table_info_header .= "<span style=\"margin-left:1.5em;\">Head Judge</span>";
						if (strpos($row_assignments['assignRoles'],"MBOS") !== FALSE) $table_info_header .= "<span style=\"margin-left:1em;\">Mini-BOS</span>";
						$table_info_header .= "</em>";
						$table_info_header .= "</small>";
					}
					$table_info_header .= "</h2>";

					$table_info_header .= "<h3>";
					// Pulled from the batched map above instead of a fresh table_location() query -
					// table_location()'s "default" method resolves table id -> tableLocation itself,
					// so that resolution is replicated here via the same batched table map.
					$table_info_header .= $table_location_ps($tables_by_id_ps[$row_assignments['assignTable']]['tableLocation'] ?? null);
					if ($round != "default") $table_info_header .= sprintf("<br>%s %s",$label_round,$round);
					$table_info_header .= "</h3>";
					
					if (!empty($table_flight_tbody)) {

						$table_flight .= $table_info_header;
						$table_flight .= "<p class=\"lead\">";
						if ($_SESSION['jPrefsQueued'] == "N") {
							$table_flight .= sprintf("%s %s, %s %s <small style=\"margin-left:1em;\">%s %s</small>",$label_flight,$row_assignments['assignFlight'],$label_round,$row_assignments['assignRound'],$judge_entry_count,$label_entries_to_judge);
						}
						else $table_flight .= $table_entry_count." ".$label_entries;
						$table_flight .= "</p>";
						
						$table_flight_datatables .= "<script>";
						$table_flight_datatables .= "$(document).ready(function() {";
						$table_flight_datatables .= "$('#sortable".$random_sortable."').dataTable( {";
						$table_flight_datatables .= "\"bPaginate\" : false,";
						$table_flight_datatables .= "\"sDom\": 'rt',";
						$table_flight_datatables .= "\"bStateSave\" : false,";
						$table_flight_datatables .= "\"bLengthChange\" : false,";
						$table_flight_datatables .= "\"aaSorting\": [[1,'asc'],[0,'asc']],";
						$table_flight_datatables .= "\"bProcessing\" : false,";
						$table_flight_datatables .= "\"aoColumns\": [";
						$table_flight_datatables .= "null,";
						$table_flight_datatables .= "null,";
						$table_flight_datatables .= "null,";
						$table_flight_datatables .= "null,";
						$table_flight_datatables .= "null,";
						//if ($filter != "mini_bos") $table_flight_datatables .= "null,";
						$table_flight_datatables .= "null,";
						$table_flight_datatables .= "null";
						$table_flight_datatables .= "]";
						$table_flight_datatables .= "} );";
						$table_flight_datatables .= "} );";
						$table_flight_datatables .= "</script>";

						$table_flight .= $table_flight_datatables;
						$table_flight .= "<table class=\"table table-striped table-bordered\" id=\"sortable".$random_sortable."\">";
						$table_flight .= "<thead>";
						$table_flight .= $table_flight_thead;
						$table_flight .= "</thead>";
						$table_flight .= "<tbody>";
						$table_flight .= $table_flight_tbody;
						$table_flight .= "</tbody>";
						$table_flight .= "</table>";
					}

				}

				if (!empty($table_flight)) {
					$judge_inventory_output .= $table_flight;
					$judge_inventory_output .= "<div style=\"page-break-after:always;\"></div>";
				}

				// Create a sortable array of each judge's assigned entries, grouped by table.
				$judge_inventory[] = array(
					"table-num" => $table_info[0],
					"flight" => $row_assignments['assignFlight'],
					"round" => $row_assignments['assignRound'],
					"table-id" => $row_assignments['assignTable'],
					"table-name" => $table_info[1],
					"last-name" => $judge_info[1],
					"first-name" => $judge_info[0],
					"roles" => $row_assignments['assignRoles'],
					"table-styles" => $table_info[4],
					"inventory-html" => $judge_inventory_output
				);

			}

			sort($judge_inventory);

			foreach ($judge_inventory as $key => $value) {
				if (!empty($value['inventory-html'])) {
					$pullsheet_output .= sprintf("<h1>Judging Inventory for %s %s</h1>",$value['first-name'],$value['last-name']);
					$pullsheet_output .= $value['inventory-html'];
				}
			}

		} // end if ($row_assignments)

		if (empty($pullsheet_output)) {
			$pullsheet_output = "<h2>No Inventories Available</h2><p class\"lead\"><strong>No inventories available for this session.</strong> Entries must be marked as received for this report to return a list. If entries are marked as received, check that judges have been assigned to tables and/or flights.</p>";
		}

	} else {

		foreach ($rows_tables as $row_tables) {

			// Pulled from the batched maps above instead of fresh
			// get_table_info()/output_pullsheets_queued.db.php queries per table.
			$entry_count = $table_entry_count_ps($row_tables['tableStyles']);
			$row_table_round = array('count' => $flight_round_count_by_table_round_ps[$row_tables['id'].'|'.$round] ?? 0);
			$round_count[] = $row_table_round['count'];

			$table_flight = "";
			$table_flight_datatables = "";

			if (($row_table_round['count'] >= 1) || ($round == "default")) {

				$table_info_location = "";
				$table_info_notes = "";
				$table_info_header = "";

				if ($entry_count > 0) {

					$table_flight_tbody = "";

					$a = explode(",", $row_tables['tableStyles']);

					foreach (array_unique($a) as $value) {

						// Pulled from the batched styles/entries maps above instead
						// of a fresh output_pullsheets_entries.db.php query per style
						// per table - guarded to the mini_bos filter's own (different,
						// unbatched) query, which is left untouched.
						if ($filter == "mini_bos") {
							include (DB.'output_pullsheets_entries.db.php');
						}
						else {
							$row_style_ps_lookup = $styles_by_id_ps[$value] ?? null;
							$rows_entries = ($row_style_ps_lookup) ? ($entries_by_style_ps[$row_style_ps_lookup['brewStyleGroup'].'|'.$row_style_ps_lookup['brewStyleNum']] ?? array()) : array();
							$row_entries = (!empty($rows_entries)) ? $rows_entries[0] : null;
							$totalRows_entries = count($rows_entries);
						}
						$style = style_number_const($row_entries['brewCategorySort'],$row_entries['brewSubCategory'],$_SESSION['style_set_display_separator'],1);
						$style_special = $row_entries['brewCategorySort']."^".$row_entries['brewSubCategory']."^".$_SESSION['prefsStyleSet'];
 
						foreach ($rows_entries as $row_entries) {

							$show_record = FALSE;

							if ((!empty($row_entries['brewPossAllergens'])) || (!empty($row_entries['brewInfo'])) || (!empty($row_entries['brewMead1'])) || (!empty($row_entries['brewMead2'])) || (!empty($row_entries['brewMead3'])) || (!empty($row_entries['brewInfoOptional'])) || (!empty($row_entries['brewComments'])) || (!empty($row_entries['brewStaffNotes']))) $show_record = TRUE;

							if ((!empty($row_entries['brewCategorySort'])) && ($show_record)) {

								$table_flight_tbody .= "<tr>";

								$table_flight_tbody .= "<td nowrap>";
								if ($view == "entry")  $table_flight_tbody .= sprintf("%06s",$row_entries['id']);
								else $table_flight_tbody .= sprintf("%06s",$row_entries['brewJudgingNumber']);
								$table_flight_tbody .= "</td>";

								$table_flight_tbody .= "<td>";
								if ($_SESSION['prefsStyleSet'] == "BA") $table_flight_tbody .= $row_entries['brewStyle'];
								else $table_flight_tbody .= $style." ".$row_entries['brewStyle']."<em><br>".$style_convert_1_ps($row_entries['brewCategorySort'])."</em>";
								$table_flight_tbody .= "</td>";

								$special = $style_convert_9_ps($style_special);
								$special = explode("^",$special);
								$table_flight_tbody .= "<td>";
								if ((!empty($row_entries['brewInfo'])) && ((isset($special[4])) && ($special[4] == "1"))) $table_flight_tbody .= "<p>".str_replace("^","<br>",$row_entries['brewInfo'])."</p>";
								$table_flight_tbody .= "<p>";
								if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<strong>".$label_carbonation.": </strong> ".$row_entries['brewMead1']."<br>";
								if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<strong>".$label_sweetness.":</strong> ".$row_entries['brewMead2']."<br>";
								if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<strong>".$label_strength.":</strong> ".$row_entries['brewMead3'];
								$table_flight_tbody .= "</p>";
								$table_flight_tbody .= "</td>";

								$table_flight_tbody .= "<td>";
								if (!empty($row_entries['brewInfoOptional'])) $table_flight_tbody .= "<p>".$row_entries['brewInfoOptional']."</p>";
								$table_flight_tbody .= "</td>";

								$table_flight_tbody .= "<td>";
								if (!empty($row_entries['brewComments'])) $table_flight_tbody .= "<p>".$row_entries['brewComments']."</p>";
								$table_flight_tbody .= "</td>";

								$table_flight_tbody .= "<td>";
								if (!empty($row_entries['brewPossAllergens'])) $table_flight_tbody .= "<p>".$row_entries['brewPossAllergens']."</p>";
								$table_flight_tbody .= "</td>";

								$table_flight_tbody .= "<td>";
								if (!empty($row_entries['brewStaffNotes'])) $table_flight_tbody .= "<p>".$row_entries['brewStaffNotes']."</p>";
								$table_flight_tbody .= "</td>";

								$table_flight_tbody .= "</tr>";

							}

						}

					} // end foreach

					$table_info_header .= "<div class=\"page-header\">";
					$table_info_header .= "<h1 style=\"margin-bottom: 10px; padding-bottom:10px;\">";
					$table_info_header .= sprintf("%s %s: %s <small><em>%s</em></small>",$label_table,$row_tables['tableNumber'],$row_tables['tableName'],$label_additional_info);
					$table_info_header .= "</h1>";
					$table_info_header .= "</div>";

					if ((!empty($row_tables['tableLocation'])) && ($filter != "mini_bos")) {
						$table_info_location .= "<h3>";
						// Pulled from the batched map above instead of a fresh table_location() query.
						$table_info_location .= $table_location_ps($row_tables['tableLocation']);
						if ($round != "default") $table_info_location .= sprintf("<br>%s %s",$label_round,$round);
						$table_info_location .= "</h3>";
					}

					if (!empty($table_flight_tbody)) {

						$show_table = TRUE;
						$table_flight .= $table_info_header.$table_info_location;

						$table_flight_datatables .= "<script>";
						$table_flight_datatables .= "$(document).ready(function() {";
						$table_flight_datatables .= "$('#sortable".$row_tables['id']."').dataTable( {";
						$table_flight_datatables .= "\"bPaginate\" : false,";
						$table_flight_datatables .= "\"sDom\": 'rt',";
						$table_flight_datatables .= "\"bStateSave\" : false,";
						$table_flight_datatables .= "\"bLengthChange\" : false,";
						$table_flight_datatables .= "\"aaSorting\": [[1,'asc'],[0,'asc']],";
						$table_flight_datatables .= "\"bProcessing\" : false,";
						$table_flight_datatables .= "\"aoColumns\": [";
						$table_flight_datatables .= "null,";
						$table_flight_datatables .= "null,";
						$table_flight_datatables .= "null,";
						$table_flight_datatables .= "null,";
						$table_flight_datatables .= "null,";
						//if ($filter != "mini_bos") $table_flight_datatables .= "null,";
						$table_flight_datatables .= "null,";
						$table_flight_datatables .= "null";
						$table_flight_datatables .= "]";
						$table_flight_datatables .= "} );";
						$table_flight_datatables .= "} );";
						$table_flight_datatables .= "</script>";

						$table_flight .= $table_flight_datatables;
						$table_flight .= "<table class=\"table table-striped table-bordered\" id=\"sortable".$row_tables['id']."\">";
						$table_flight .= "<thead>";
						$table_flight .= $table_flight_thead;
						$table_flight .= "</thead>";
						$table_flight .= "<tbody>";
						$table_flight .= $table_flight_tbody;
						$table_flight .= "</tbody>";
						$table_flight .= "</table>";
					}

				} // end  if ($entry_count > 0)

			} // end if (($row_table_round['count'] >= 1) || ($round == "default"))
			
			if ($show_table) {
				$pullsheet_output .= $table_flight;
				$pullsheet_output .= "<div style=\"page-break-after:always;\"></div>";
			}

			else {
				if ($id != "default") {
					$pullsheet_output .= $table_info_header;
					$pullsheet_output .= "<p>No entries at this table have additional information.";
				}
			}

		}


	} // end else

} // end if ($go == "all_entry_info")

if ($go == "mini_bos") {

	include (DB.'output_pullsheets_mini_bos.db.php');
	$table_flight = "";
	$table_flight_datatables = "";
	$table_flight_tbody = "";

	$table_info_header = "";

	$table_info_header .= "<div class=\"page-header\">";
	$table_info_header .= "<h1>";
	$table_info_header .= $label_mini_bos;
	$table_info_header .= "</h1>";
	$table_info_header .= "</div>";

	$pullsheet_output .= $table_info_header;

	if ($totalRows_entries_mini > 0) {

		if (!isset($type)) $type = "1234567890";
		else $type = $type;

		$table_flight_datatables .= "<script>";
		$table_flight_datatables .= "$(document).ready(function() {";
		$table_flight_datatables .= "$('#sortable".$type."').dataTable( {";
		$table_flight_datatables .= "\"bPaginate\" : false,";
		$table_flight_datatables .= "\"sDom\": 'rt',";
		$table_flight_datatables .= "\"bStateSave\" : false,";
		$table_flight_datatables .= "\"bLengthChange\" : false,";
		$table_flight_datatables .= "\"aaSorting\": [[2,'asc'],[1,'asc']],";
		$table_flight_datatables .= "\"bProcessing\" : false,";
		$table_flight_datatables .= "\"aoColumns\": [";
		$table_flight_datatables .= "null,";
		$table_flight_datatables .= "null,";
		$table_flight_datatables .= "null,";
		$table_flight_datatables .= "null,";
		$table_flight_datatables .= "null,";
		$table_flight_datatables .= "null,";
		$table_flight_datatables .= "null";
		$table_flight_datatables .= "]";
		$table_flight_datatables .= "} );";
		$table_flight_datatables .= "} );";
		$table_flight_datatables .= "</script>";

		$table_flight .= $table_flight_datatables;
		$table_flight .= "<table class=\"table table-striped table-bordered\" id=\"sortable\">";
		$table_flight .= "<thead>";
		$table_flight .= $table_flight_thead;
		$table_flight .= "</thead>";
		$table_flight .= "<tbody>";

		foreach ($rows_entries_mini as $row_entries_mini) {

			$style = style_number_const($row_entries_mini['brewCategorySort'],$row_entries_mini['brewSubCategory'],$_SESSION['style_set_display_separator'],1);
			$style_special = $row_entries_mini['brewCategorySort']."^".$row_entries_mini['brewSubCategory']."^".$_SESSION['prefsStyleSet'];
			$special = $style_convert_9_ps($style_special);
			$special = explode("^",$special);

			$table_flight_tbody .= "<tr>";
			$table_flight_tbody .= "<td>";
			$table_flight_tbody .= "<p>&nbsp;</p>";
			$table_flight_tbody .= "</td>";

			$table_flight_tbody .= "<td>";
			if ($view == "entry")  $table_flight_tbody .= sprintf("%06s",$row_entries_mini['id']);
			else $table_flight_tbody .= sprintf("%06s",$row_entries_mini['brewJudgingNumber']);
			$table_flight_tbody .= "</td>";

			$table_flight_tbody .= "<td>";
			if ($_SESSION['prefsStyleSet'] == "BA") $table_flight_tbody .= $row_entries_mini['brewStyle'];
			else $table_flight_tbody .= $style." ".$row_entries_mini['brewStyle']."<em><br>".$style_convert_1_ps($row_entries_mini['brewCategorySort'])."</em>";
			$table_flight_tbody .= "</td>";

			$table_flight_tbody .= "<td>";

			if ((($_SESSION['prefsStyleSet'] == "BJCP2021") || ($_SESSION['prefsStyleSet'] == "BJCP2025")) && ($style == "02A") && ($row_entries_mini['brewInfo'] != "")) {
				$table_flight_tbody .= "<p><strong>".$label_regional_variation.": </strong> ".str_replace("^"," | ",$row_entries_mini['brewInfo'])."</p>";
			} 

			elseif (($row_entries_mini['brewInfo'] != "") && ($special[4] == "1")) {
				$table_flight_tbody .= "<p><strong>".$label_required_info.":</strong> ".str_replace("^"," | ",$row_entries_mini['brewInfo'])."</p>";
			}

			if ($row_entries_mini['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>".$label_optional_info.":</strong> ".$row_entries_mini['brewInfoOptional']."</p>";
			if ($row_entries_mini['brewComments'] != "") $table_flight_tbody .= "<p><strong>".$label_brewer_specifics.":</strong> ".$row_entries_mini['brewComments']."</p>";

			$table_flight_tbody .= "<ul class=\"list-unstyled\">";
			
			if (!empty($row_entries_mini['brewMead1'])) $table_flight_tbody .= "<li><strong>".$label_carbonation.":</strong> ".$row_entries_mini['brewMead1']."</li>";
			if (!empty($row_entries_mini['brewMead2'])) $table_flight_tbody .= "<li><strong>".$label_sweetness.":</strong> ".$row_entries_mini['brewMead2']."</li>";
			if (!empty($row_entries_mini['brewMead3'])) $table_flight_tbody .= "<li><strong>".$label_strength.":</strong> ".$row_entries_mini['brewMead3']."</li>";

			if (!empty($row_entries_mini['brewPossAllergens'])) $table_flight_tbody .= "<li><strong>".$label_possible_allergens.":</strong> ".$row_entries_mini['brewPossAllergens']."</li>";


			if (!empty($row_entries_mini['brewABV'])) $table_flight_tbody .= "<li><strong>".$label_abv.":</strong> ".$row_entries_mini['brewABV']."</li>";	
			
			/*

			if (($_SESSION['prefsStyleSet'] == "NWCiderCup") && (!empty($row_entries_mini['brewJuiceSource']))) {
				  
				$juice_src_arr = json_decode($row_entries_mini['brewJuiceSource'],true);
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

				$table_flight_tbody .= "<li><strong>".$label_juice_source.":</strong> ".$juice_src_disp."</li>";

			}

			*/

			if (!empty($row_entries_mini['brewPackaging'])) $table_flight_tbody .= "<li><strong>".$label_packaging.":</strong> ".$packaging_display[$row_entries_mini['brewPackaging']]."</li>";

			if ((!empty($row_entries_mini['brewPouring'])) && ((!empty($row_entries_mini['brewStyleType'])) && ($row_entries_mini['brewStyleType'] == 1))) {
				$pouring_arr = json_decode($row_entries_mini['brewPouring'],true);
				$table_flight_tbody .= "<li><strong>".$label_pouring.":</strong> ".$pouring_arr['pouring']."</li>";
				if ((isset($pouring_arr['pouring_notes'])) && (!empty($pouring_arr['pouring_notes']))) $table_flight_tbody .= "<li><strong>".$label_pouring_notes.":</strong> ".$pouring_arr['pouring_notes']."</li>";
				$table_flight_tbody .= "<li><strong>".$label_rouse_yeast.":</strong> ".$pouring_arr['pouring_rouse']."</li>";
			}

			if (!empty($row_entries['brewStaffNotes'])) $table_flight_tbody .= "<li><strong>".$label_notes.":</strong> ".$row_entries['brewStaffNotes']."</li>";
			
			$table_flight_tbody .= "</ul>";

			$table_flight_tbody .= "</td>";

			$table_flight_tbody .= "<td>";
			$table_flight_tbody .= $row_entries_mini['brewBoxNum'];
			$table_flight_tbody .= "</td>";

			$table_flight_tbody .= "<td>";
			$table_flight_tbody .= "<p>&nbsp;</p>";
			$table_flight_tbody .= "</td>";

			$table_flight_tbody .= "<td>";
			$table_flight_tbody .= "<p>&nbsp;</p>";
			$table_flight_tbody .= "</td>";

			$table_flight_tbody .= "</tr>";

		}

		$table_flight .= $table_flight_tbody;
		$table_flight .= "</tbody>";
		$table_flight .= "</table>";

	} // end if ($totalRows_entries_mini > 0)

	else {
		$table_flight .= "<p>No Mini-BOS entries were found.</p>";
	}

	$pullsheet_output .= $table_flight;

} // end if ($go == "mini_bos")

if ($go == "judging_scores_bos") {

	$a = array();

	if ($id == "default") {
		foreach ($rows_style_types as $row_style_types) {
			$a[] = $row_style_types['id'];
		}
		sort($a);
	}

	else $a[] = $id;

	foreach ($a as $type) {

		$style_type_info = style_type_info($type);
		//echo $style_type_info;
		$style_type_info = explode("^",$style_type_info);

		$table_flight = "";
		$table_flight_datatables = "";
		$table_flight_tbody = "";

		if ($style_type_info[0] == "Y") {

			include (DB.'output_pullsheets_bos.db.php');

			$table_info_header = "";

			$table_info_header .= "<div class=\"page-header\">";
			$table_info_header .= "<h1>";
			$table_info_header .= sprintf("%s: %s",$label_bos,$style_type_info[2]);
			$table_info_header .= "</h1>";
			$table_info_header .= "</div>";

			$pullsheet_output .= $table_info_header;

			if ($totalRows_bos > 0) {

				$table_flight_datatables .= "<script>";
				$table_flight_datatables .= "$(document).ready(function() {";
				$table_flight_datatables .= "$('#sortable".$type."').dataTable( {";
				$table_flight_datatables .= "\"bPaginate\" : false,";
				$table_flight_datatables .= "\"sDom\": 'rt',";
				$table_flight_datatables .= "\"bStateSave\" : false,";
				$table_flight_datatables .= "\"bLengthChange\" : false,";
				$table_flight_datatables .= "\"aaSorting\": [[3,'asc'],[2,'asc'],[1,'asc']],";
				$table_flight_datatables .= "\"bProcessing\" : false,";
				$table_flight_datatables .= "\"aoColumns\": [";
				$table_flight_datatables .= "null,";
				$table_flight_datatables .= "null,";
				$table_flight_datatables .= "null,";
				$table_flight_datatables .= "null,";
				$table_flight_datatables .= "null,";
				$table_flight_datatables .= "null,";
				$table_flight_datatables .= "null,";
				$table_flight_datatables .= "null";
				$table_flight_datatables .= "]";
				$table_flight_datatables .= "} );";
				$table_flight_datatables .= "} );";
				$table_flight_datatables .= "</script>";

				$table_flight .= $table_flight_datatables;
				$table_flight .= "<table class=\"table table-striped table-bordered\" id=\"sortable".$type."\">";
				$table_flight .= "<thead>";
				$table_flight .= $table_flight_thead;
				$table_flight .= "</thead>";
				$table_flight .= "<tbody>";

				foreach ($rows_bos as $row_bos) {

					// include (DB.'output_pullsheets_bos_entries.db.php');

					$style = style_number_const($row_bos['brewCategorySort'],$row_bos['brewSubCategory'],$_SESSION['style_set_display_separator'],1);
					$style_special = $row_bos['brewCategorySort']."^".$row_bos['brewSubCategory']."^".$_SESSION['prefsStyleSet'];

					if (!empty($row_bos['brewCategorySort'])) {

						$table_flight_tbody .= "<tr>";

						$table_flight_tbody .= "<td>";
						$table_flight_tbody .= "<p>&nbsp;</p>";
						$table_flight_tbody .= "</td>";
						$table_flight_tbody .= "<td>";

						if ($view == "entry")  $table_flight_tbody .= sprintf("%06s",$row_bos['id']);
						else $table_flight_tbody .= sprintf("%06s",$row_bos['brewJudgingNumber']);
						$table_flight_tbody .= "</td>";

						$table_flight_tbody .= "<td>";
						$table_flight_tbody .= $row_bos['scorePlace'];
						$table_flight_tbody .= "</td>";

						$table_flight_tbody .= "<td>";
						if ($_SESSION['prefsStyleSet'] == "BA") $table_flight_tbody .= $row_bos['brewStyle'];
						else $table_flight_tbody .= $style." ".$row_bos['brewStyle']."<em><br>".$style_convert_1_ps($row_bos['brewCategorySort'])."</em>";
						$table_flight_tbody .= "</td>";

						$table_flight_tbody .= "<td>";
						$special = $style_convert_9_ps($style_special);
						$special = explode("^",$special);

						if ((($_SESSION['prefsStyleSet'] == "BJCP2021") || ($_SESSION['prefsStyleSet'] == "BJCP2025")) && ($style == "02A") && ($row_bos['brewInfo'] != "")) {
							$table_flight_tbody .= "<p><strong>".$label_regional_variation.": </strong> ".str_replace("^"," | ",$row_bos['brewInfo'])."</p>";
						} 

						elseif (($row_bos['brewInfo'] != "") && ($special[4] == "1")) {
							$table_flight_tbody .= "<p><strong>".$label_required_info.": </strong>".str_replace("^"," | ",$row_bos['brewInfo'])."</p>";
						}

						if ($row_bos['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>".$label_optional_info.": </strong>".$row_bos['brewInfoOptional']."</p>";
						if ($row_bos['brewComments'] != "") $table_flight_tbody .= "<p><strong>".$label_brewer_specifics.": </strong>".$row_bos['brewComments']."</p>";

						$table_flight_tbody .= "<ul class=\"list-unstyled\">";

						if ((!empty($row_bos['brewMead1'])) || (!empty($row_bos['brewMead2'])) || (!empty($row_bos['brewMead3']))) {
							if (!empty($row_bos['brewMead1'])) $table_flight_tbody .= "<li><strong>".$label_carbonation.":</strong> ".$row_bos['brewMead1']."</li>";
							if (!empty($row_bos['brewMead2'])) $table_flight_tbody .= "<li><strong>".$label_sweetness.":</strong> ".$row_bos['brewMead2']."</li>";
							if (!empty($row_bos['brewMead3'])) $table_flight_tbody .= "<li><strong>".$label_strength.":</strong> ".$row_bos['brewMead3']."</li>";
						}
						
						if (!empty($row_bos['brewPossAllergens'])) $table_flight_tbody .= "<li><strong>".$label_possible_allergens.":</strong> ".$row_bos['brewPossAllergens']."</li>";

						if (!empty($row_bos['brewABV'])) $table_flight_tbody .= "<li><strong>".$label_abv.":</strong> ".$row_bos['brewABV']."</li>";

						/*

						if (($_SESSION['prefsStyleSet'] == "NWCiderCup") && (!empty($row_bos['brewJuiceSource']))) {
							  
							$juice_src_arr = json_decode($row_bos['brewJuiceSource'],true);
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

							$table_flight_tbody .= "<li><strong>".$label_juice_source.":</strong> ".$juice_src_disp."</li>";

						}

						*/

						if (!empty($row_bos['brewPackaging'])) $table_flight_tbody .= "<li><strong>".$label_packaging.":</strong> ".$packaging_display[$row_bos['brewPackaging']]."</li>";

						if ((!empty($row_bos['brewPouring'])) && ((!empty($row_bos['brewStyleType'])) && ($row_bos['brewStyleType'] == 1))) {
							$pouring_arr = json_decode($row_bos['brewPouring'],true);
							$table_flight_tbody .= "<li><strong>".$label_pouring.":</strong> ".$pouring_arr['pouring']."</li>";
							if ((isset($pouring_arr['pouring_notes'])) && (!empty($pouring_arr['pouring_notes']))) $table_flight_tbody .= "<li><strong>".$label_pouring_notes.":</strong> ".$pouring_arr['pouring_notes']."</li>";
							$table_flight_tbody .= "<li><strong>".$label_rouse_yeast.":</strong> ".$pouring_arr['pouring_rouse']."</li>";
						}

						if (!empty($row_bos['brewStaffNotes'])) $table_flight_tbody .= "<p><strong>".$label_notes.":</strong> ".$row_bos['brewStaffNotes']."</p>";
						$table_flight_tbody .= "</ul>";

						if ($row_bos['brewerProAm'] >= 1) $table_flight_tbody .= "<p><strong>** NOT ELIGIBLE FOR PRO-AM **</p>"; 

						$table_flight_tbody .= "</td>";
						$table_flight_tbody .= "<td>";
						$table_flight_tbody .= $row_bos['brewBoxNum'];
						$table_flight_tbody .= "</td>";
						$table_flight_tbody .= "<td>";
						$table_flight_tbody .= "<p>&nbsp;</p>";
						$table_flight_tbody .= "</td>";
						$table_flight_tbody .= "<td>";
						$table_flight_tbody .= "<p>&nbsp;</p>";
						$table_flight_tbody .= "</td>";
						$table_flight_tbody .= "</tr>";

					}


				}

				$table_flight .= $table_flight_tbody;
				$table_flight .= "</tbody>";
				$table_flight .= "</table>";
				$table_flight .= "<div style=\"page-break-after:always;\"></div>";

			}

			else {
				$table_flight .= "<p>No BOS entries were found for ".$style_type_info[2].".</p>";
				$table_flight .= "<div style=\"page-break-after:always;\"></div>";
			}

			$pullsheet_output .= $table_flight;

		} // end if ($style_type_info[0] == "Y")

	}

} // end if ($go == "judging_scores_bos")

elseif (($go != "judging_scores_bos") && ($go != "mini_bos") && ($go != "all_entry_info")) {

	// If using queued judging (no flights)

	if ($queued) {

		if ($tables_all) {

			$pullsheet_output = "";
			$round_count = array();

			foreach ($rows_tables as $row_tables) {

				// Pulled from the batched maps above instead of a fresh
				// get_table_info()/output_pullsheets_queued.db.php query per table.
				$entry_count = $table_entry_count_ps($row_tables['tableStyles']);
				$row_table_round = array('count' => $flight_round_count_by_table_round_ps[$row_tables['id'].'|'.$round] ?? 0);
				$round_count[] = $row_table_round['count'];

				$table_flight = "";
				$table_flight_datatables = "";

				if (($row_table_round['count'] >= 1) || ($round == "default")) {

					$table_info_location = "";
					$table_info_notes = "";
					$table_info_header = "";

					$table_info_header .= "<div class=\"page-header\">";
					$table_info_header .= "<h1>";
					$table_info_header .= sprintf("%s %s: %s",$label_table,$row_tables['tableNumber'],$row_tables['tableName']);
					if ($filter == "mini_bos") $table_info_header .= sprintf(" - %s",$label_mini_bos);
					$table_info_header .= "</h1>";
					$table_info_header .= "</div>";

					if ((!empty($row_tables['tableLocation'])) && ($filter != "mini_bos")) {

						$table_info_location .= "<h2>";
						// Pulled from the batched locations map above instead of a
						// fresh table_location() query per table.
						$table_info_location .= $table_location_ps($row_tables['tableLocation']);
						if ($round != "default") $table_info_location .= sprintf("<br>%s %s",$label_round,$round);
						$table_info_location .= "</h2>";
						$table_info_location .= "<p class=\"lead\">";
						$table_info_location .= sprintf("%s: %s",$label_entries,$entry_count);
						$table_info_location .= "</p>";
						$table_info_location .= "<p>";
						$table_info_location .= sprintf("%s: %s",$label_please_note,$output_text_019);
						$table_info_location .= "</p>";

					}

					$pullsheet_output .= $table_info_header.$table_info_location;

					if ($entry_count > 0) {

						//$table_flight .= $row_tables['tableStyles'];

						$table_flight_datatables .= "<script>";
						$table_flight_datatables .= "$(document).ready(function() {";
						$table_flight_datatables .= "$('#sortable".$row_tables['id']."').dataTable( {";
						$table_flight_datatables .= "\"bPaginate\" : false,";
						$table_flight_datatables .= "\"sDom\": 'rt',";
						$table_flight_datatables .= "\"bStateSave\" : false,";
						$table_flight_datatables .= "\"bLengthChange\" : false,";
						$table_flight_datatables .= "\"aaSorting\": [[2,'asc'],[1,'asc']],";
						$table_flight_datatables .= "\"bProcessing\" : false,";
						$table_flight_datatables .= "\"aoColumns\": [";
						$table_flight_datatables .= "null,";
						$table_flight_datatables .= "null,";
						$table_flight_datatables .= "null,";
						$table_flight_datatables .= "null,";
						$table_flight_datatables .= "null,";
						if ($filter != "mini_bos") $table_flight_datatables .= "null,";
						$table_flight_datatables .= "null,";
						$table_flight_datatables .= "null";
						$table_flight_datatables .= "]";
						$table_flight_datatables .= "} );";
						$table_flight_datatables .= "} );";
						$table_flight_datatables .= "</script>";

						$table_flight .= $table_flight_datatables;
						$table_flight .= "<table class=\"table table-striped table-bordered\" id=\"sortable".$row_tables['id']."\">";
						$table_flight .= "<thead>";
						$table_flight .= $table_flight_thead;
						$table_flight .= "</thead>";
						$table_flight .= "<tbody>";

						$table_flight_tbody = "";

						$a = explode(",", $row_tables['tableStyles']);

						foreach (array_unique($a) as $value) {

							// Pulled from the batched styles/entries maps above instead
							// of a fresh output_pullsheets_entries.db.php query per style
							// per table - guarded to the mini_bos filter's own (different,
							// unbatched) query, which is left untouched.
							if ($filter == "mini_bos") {
								include (DB.'output_pullsheets_entries.db.php');
							}
							else {
								$row_style_ps_lookup = $styles_by_id_ps[$value] ?? null;
								$rows_entries = ($row_style_ps_lookup) ? ($entries_by_style_ps[$row_style_ps_lookup['brewStyleGroup'].'|'.$row_style_ps_lookup['brewStyleNum']] ?? array()) : array();
								$row_entries = (!empty($rows_entries)) ? $rows_entries[0] : null;
								$totalRows_entries = count($rows_entries);
							}

							$style = "";
							$style_special = "";

							if ($row_entries) {
								$style = style_number_const($row_entries['brewCategorySort'],$row_entries['brewSubCategory'],$_SESSION['style_set_display_separator'],1);
								$style_special = $row_entries['brewCategorySort']."^".$row_entries['brewSubCategory']."^".$_SESSION['prefsStyleSet'];
							}


							foreach ($rows_entries as $row_entries) {

								if (!empty($row_entries['brewCategorySort'])) {

									$table_flight_tbody .= "<tr>";
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= "<p></p>";
									$table_flight_tbody .= "</td>";

									$table_flight_tbody .= "<td>";
									if ($view == "entry")  $table_flight_tbody .= sprintf("%06s",$row_entries['id']);
									else $table_flight_tbody .= sprintf("%06s",$row_entries['brewJudgingNumber']);
									$table_flight_tbody .= "</td>";

									$table_flight_tbody .= "<td>";
									if ($_SESSION['prefsStyleSet'] == "BA") $table_flight_tbody .= $row_entries['brewStyle'];
									else $table_flight_tbody .= $style." ".$row_entries['brewStyle']."<em><br>".$style_convert_1_ps($row_entries['brewCategorySort'])."</em>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";

									$special = $style_convert_9_ps($style_special);
									$special = explode("^",$special);

									if (($row_entries['brewInfo'] != "") && ($special[4] == "1")) {
										if ((($_SESSION['prefsStyleSet'] == "BJCP2021") || ($_SESSION['prefsStyleSet'] == "BJCP2025")) && ($style == "2A")) $table_flight_tbody .= "<p><strong>".$label_regional_variation.": </strong> ".str_replace("^"," | ",$row_entries['brewInfo'])."</p>";
										else $table_flight_tbody .= "<p><strong>".$label_required_info.": </strong> ".str_replace("^"," | ",$row_entries['brewInfo'])."</p>";
									}
									if ($row_entries['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>".$label_optional_info.": </strong> ".$row_entries['brewInfoOptional']."</p>";
									if ($row_entries['brewComments'] != "") $table_flight_tbody .= "<p><strong>".$label_brewer_specifics.": </strong> ".$row_entries['brewComments']."</p>";

									$table_flight_tbody .= "<ul class=\"list-unstyled\">";
									if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<li><strong>".$label_carbonation.": </strong> ".$row_entries['brewMead1']."</li>";
									if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<li><strong>".$label_sweetness.":</strong> ".$row_entries['brewMead2']."</li>";
									if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<li><strong>".$label_strength.":</strong> ".$row_entries['brewMead3']."</li>";
									
									if (!empty($row_entries['brewPossAllergens'])) $table_flight_tbody .= "<li><strong>".$label_possible_allergens.":</strong> ".$row_entries['brewPossAllergens']."</li>";

									if (!empty($row_entries['brewABV'])) $table_flight_tbody .= "<li><strong>".$label_abv.":</strong> ".$row_entries['brewABV']."</li>";	
									/*

									if (($_SESSION['prefsStyleSet'] == "NWCiderCup") && (!empty($row_entries['brewJuiceSource']))) {
										  
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

										$table_flight_tbody .= "<li><strong>".$label_juice_source.":</strong> ".$juice_src_disp."</li>";

									}

									*/

									if (!empty($row_entries['brewPackaging'])) $table_flight_tbody .= "<li><strong>".$label_packaging.":</strong> ".$packaging_display[$row_entries['brewPackaging']]."</li>";

									if ((!empty($row_entries['brewPouring'])) && ((!empty($row_entries['brewStyleType'])) && ($row_entries['brewStyleType'] == 1))) {
										$pouring_arr = json_decode($row_entries['brewPouring'],true);
										$table_flight_tbody .= "<li><strong>".$label_pouring.":</strong> ".$pouring_arr['pouring']."</li>";
										if ((isset($pouring_arr['pouring_notes'])) && (!empty($pouring_arr['pouring_notes']))) $table_flight_tbody .= "<li><strong>".$label_pouring_notes.":</strong> ".$pouring_arr['pouring_notes']."</li>";
										$table_flight_tbody .= "<li><strong>".$label_rouse_yeast.":</strong> ".$pouring_arr['pouring_rouse']."</li>";
									}

									if (!empty($row_entries['brewStaffNotes'])) $table_flight_tbody .= "<li><strong>".$label_notes.":</strong> ".$row_entries['brewStaffNotes']."</li>";

									$table_flight_tbody .= "</ul>";

									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= $row_entries['brewBoxNum'];;
									$table_flight_tbody .= "</td>";
									if ($filter != "mini_bos") {
										$table_flight_tbody .= "<td>";
										$table_flight_tbody .= "<p class=\"box_small\">";
										$table_flight_tbody .= "</td>";
									}
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= "<p>&nbsp;</p>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= "<p>&nbsp;</p>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "</tr>";

								}

							}

						} // end foreach

						$table_flight .= $table_flight_tbody;
						$table_flight .= "</tbody>";
						$table_flight .= "</table>";

					} // end  if ($entry_count > 0)

				} // end if (($row_table_round['count'] >= 1) || ($round == "default"))

				if (empty($table_flight_tbody)) {
					if ($filter == "mini_bos") $pullsheet_output .= "No Mini-BOS entries available.";
					else $pullsheet_output .= "No entries available.";
				}
				else $pullsheet_output .= $table_flight;

				$pullsheet_output .= "<div style=\"page-break-after:always;\"></div>";

			}

		} // end if ($tables_all)

		if (!$tables_all) {

			$pullsheet_output = "";

			// Pulled from the batched maps above instead of fresh
			// get_table_info()/output_pullsheets_queued.db.php queries per table.
			$entry_count = $table_entry_count_ps($row_tables['tableStyles']);
			$row_table_round = array('count' => $flight_round_count_by_table_round_ps[$row_tables['id'].'|'.$round] ?? 0);
			$round_count[] = $row_table_round['count'];

			$table_flight = "";
			$table_flight_datatables = "";

			if (($row_table_round['count'] >= 1) || ($round == "default")) {

				$table_info_location = "";
				$table_info_notes = "";
				$table_info_header = "";

				$table_info_header .= "<div class=\"page-header\">";
				$table_info_header .= "<h1>";
				$table_info_header .= sprintf("%s %s: %s",$label_table,$row_tables['tableNumber'],$row_tables['tableName']);
				if ($filter == "mini_bos") $table_info_header .= sprintf(" - %s",$label_mini_bos);
				$table_info_header .= "</h1>";
				$table_info_header .= "</div>";

				if ((!empty($row_tables['tableLocation'])) && ($filter != "mini_bos")) {

					$table_info_location .= "<h2>";
					// Pulled from the batched map above instead of a fresh table_location() query.
					$table_info_location .= $table_location_ps($row_tables['tableLocation']);
					if ($round != "default") $table_info_location .= sprintf("<br>%s %s",$label_round,$round);
					$table_info_location .= "</h2>";
					$table_info_location .= "<p class=\"lead\">";
					$table_info_location .= sprintf("%s: %s",$label_entries,$entry_count);
					$table_info_location .= "</p>";
					$table_info_location .= "<p>";
					$table_info_location .= sprintf("%s: %s",$label_please_note,$output_text_019);
					$table_info_location .= "</p>";

				}

				$pullsheet_output .= $table_info_header.$table_info_location;

				if ($entry_count > 0) {

					$table_flight_datatables .= "<script>";
					$table_flight_datatables .= "$(document).ready(function() {";
					$table_flight_datatables .= "$('#sortable".$row_tables['id']."').dataTable( {";
					$table_flight_datatables .= "\"bPaginate\" : false,";
					$table_flight_datatables .= "\"sDom\": 'rt',";
					$table_flight_datatables .= "\"bStateSave\" : false,";
					$table_flight_datatables .= "\"bLengthChange\" : false,";
					$table_flight_datatables .= "\"aaSorting\": [[2,'asc'],[1,'asc']],";
					$table_flight_datatables .= "\"bProcessing\" : false,";
					$table_flight_datatables .= "\"aoColumns\": [";
					$table_flight_datatables .= "null,";
					$table_flight_datatables .= "null,";
					$table_flight_datatables .= "null,";
					$table_flight_datatables .= "null,";
					$table_flight_datatables .= "null,";
					if ($filter != "mini_bos") $table_flight_datatables .= "null,";
					$table_flight_datatables .= "null,";
					$table_flight_datatables .= "null";
					$table_flight_datatables .= "]";
					$table_flight_datatables .= "} );";
					$table_flight_datatables .= "} );";
					$table_flight_datatables .= "</script>";

					$table_flight .= $table_flight_datatables;
					$table_flight .= "<table class=\"table table-striped table-bordered\" id=\"sortable".$row_tables['id']."\">";
					$table_flight .= "<thead>";
					$table_flight .= $table_flight_thead;
					$table_flight .= "</thead>";
					$table_flight .= "<tbody>";

					$table_flight_tbody = "";

					$a = explode(",", $row_tables['tableStyles']);

					foreach (array_unique($a) as $value) {

						// Pulled from the batched styles/entries maps above instead
						// of a fresh output_pullsheets_entries.db.php query per style
						// per table - guarded to the mini_bos filter's own (different,
						// unbatched) query, which is left untouched.
						if ($filter == "mini_bos") {
							include (DB.'output_pullsheets_entries.db.php');
						}
						else {
							$row_style_ps_lookup = $styles_by_id_ps[$value] ?? null;
							$rows_entries = ($row_style_ps_lookup) ? ($entries_by_style_ps[$row_style_ps_lookup['brewStyleGroup'].'|'.$row_style_ps_lookup['brewStyleNum']] ?? array()) : array();
							$row_entries = (!empty($rows_entries)) ? $rows_entries[0] : null;
							$totalRows_entries = count($rows_entries);
						}

						if ($row_entries) {

							$style = style_number_const($row_entries['brewCategorySort'],$row_entries['brewSubCategory'],$_SESSION['style_set_display_separator'],1);
							$style_special = $row_entries['brewCategorySort']."^".$row_entries['brewSubCategory']."^".$_SESSION['prefsStyleSet'];

							foreach ($rows_entries as $row_entries) {

								if (!empty($row_entries['brewCategorySort'])) {

									$table_flight_tbody .= "<tr>";
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= "<p>&nbsp;</p>";
									$table_flight_tbody .= "</td>";

									$table_flight_tbody .= "<td>";
									if ($view == "entry")  $table_flight_tbody .= sprintf("%06s",$row_entries['id']);
									else $table_flight_tbody .= sprintf("%06s",$row_entries['brewJudgingNumber']);
									$table_flight_tbody .= "</td>";

									$table_flight_tbody .= "<td>";
									if ($_SESSION['prefsStyleSet'] == "BA") $table_flight_tbody .= $row_entries['brewStyle'];
									else $table_flight_tbody .= $style." ".$row_entries['brewStyle']."<em><br>".$style_convert_1_ps($row_entries['brewCategorySort'])."</em>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";

									$special = $style_convert_9_ps($style_special);
									$special = explode("^",$special);

									if ((($_SESSION['prefsStyleSet'] == "BJCP2021") || ($_SESSION['prefsStyleSet'] == "BJCP2025")) && ($style == "02A") && ($row_entries['brewInfo'] != "")) {
										$table_flight_tbody .= "<p><strong>".$label_regional_variation.": </strong> ".str_replace("^"," | ",$row_entries['brewInfo'])."</p>";
									}

									elseif (($row_entries['brewInfo'] != "") && ($special[4] == "1")) {
										$table_flight_tbody .= "<p><strong>".$label_required_info.": </strong> ".str_replace("^"," | ",$row_entries['brewInfo'])."</p>";
									}

									if ($row_entries['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>".$label_optional_info.": </strong> ".$row_entries['brewInfoOptional']."</p>";
									if ($row_entries['brewComments'] != "") $table_flight_tbody .= "<p><strong>".$label_brewer_specifics.": </strong> ".$row_entries['brewComments']."</p>";

									$table_flight_tbody .= "<ul class=\"list-unstyled\">";
									if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<li><strong>".$label_carbonation.": </strong> ".$row_entries['brewMead1']."</li>";
									if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<li><strong>".$label_sweetness.":</strong> ".$row_entries['brewMead2']."</li>";
									if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<li><strong>".$label_strength.":</strong> ".$row_entries['brewMead3']."</li>";

									if (!empty($row_entries['brewPossAllergens'])) $table_flight_tbody .= "<li><strong>".$label_possible_allergens.":</strong> ".$row_entries['brewPossAllergens']."</li>";

									if (!empty($row_entries['brewABV'])) $table_flight_tbody .= "<li><strong>".$label_abv.":</strong> ".$row_entries['brewABV']."</li>";
									/*

									if (($_SESSION['prefsStyleSet'] == "NWCiderCup") && (!empty($row_entries['brewJuiceSource']))) {

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

										$table_flight_tbody .= "<li><strong>".$label_juice_source.":</strong> ".$juice_src_disp."</li>";

									}

									*/

									if (!empty($row_entries['brewPackaging'])) $table_flight_tbody .= "<li><strong>".$label_packaging.":</strong> ".$packaging_display[$row_entries['brewPackaging']]."</li>";

									if ((!empty($row_entries['brewPouring'])) && ((!empty($row_entries['brewStyleType'])) && ($row_entries['brewStyleType'] == 1))) {
										$pouring_arr = json_decode($row_entries['brewPouring'],true);
										$table_flight_tbody .= "<li><strong>".$label_pouring.":</strong> ".$pouring_arr['pouring']."</li>";
										if ((isset($pouring_arr['pouring_notes'])) && (!empty($pouring_arr['pouring_notes']))) $table_flight_tbody .= "<li><strong>".$label_pouring_notes.":</strong> ".$pouring_arr['pouring_notes']."</li>";
										$table_flight_tbody .= "<li><strong>".$label_rouse_yeast.":</strong> ".$pouring_arr['pouring_rouse']."</li>";
									}

									if (!empty($row_entries['brewStaffNotes'])) $table_flight_tbody .= "<li><strong>".$label_notes.":</strong> ".$row_entries['brewStaffNotes']."</li>";

									$table_flight_tbody .= "</ul>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= $row_entries['brewBoxNum'];;
									$table_flight_tbody .= "</td>";
									if ($filter != "mini_bos") {
										$table_flight_tbody .= "<td>";
										$table_flight_tbody .= "<p class=\"box_small\">";
										$table_flight_tbody .= "</td>";
									}
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= "<p>&nbsp;</p>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= "<p>&nbsp;</p>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "</tr>";

								}

							}

						}

					} // end foreach

					$table_flight .= $table_flight_tbody;
					$table_flight .= "</tbody>";
					$table_flight .= "</table>";
					$table_flight .= "<div style=\"page-break-after:always;\"></div>";

				} // end  if ($entry_count > 0)

			} // end if (($row_table_round['count'] >= 1) || ($round == "default"))

			$pullsheet_output .= $table_flight;

		} // end if (!$tables_all)

	} // end if ($queued)

	// If NOT using queued judging (with flights)
	if (!$queued) {

		// Loop through all tables
		if ($tables_all) {

			$pullsheet_output = "";

			// Don't separate out flights when generating MBOS

			if ($filter == "mini_bos") {

				foreach ($rows_tables as $row_tables) {

					// Reset Vars
					$table_info_location = "";
					$table_info_notes = "";
					$table_info_header = "";

					// Pulled from the batched map above instead of a fresh
					// number_of_flights() query per table.
					$flights = $number_of_flights_ps($row_tables['id']);
					if ($flights > 0) $flights = $flights; else $flights = "0";

					$table_info_header .= "<div class=\"page-header\">";
					$table_info_header .= "<h1>";
					$table_info_header .= sprintf("%s %s: %s",$label_table,$row_tables['tableNumber'],$row_tables['tableName']);
					if ($filter == "mini_bos") $table_info_header .= sprintf(" - %s",$label_mini_bos);
					$table_info_header .= "</h1>";
					$table_info_header .= "</div>";

					/*

					if (!empty($row_tables['tableLocation'])) {

						$table_info_location .= "<h2>";
						$table_info_location .= table_location($row_tables['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default");
						if ($round != "default") $table_info_location .= sprintf("<br>%s %s",$label_round,$round);
						$table_info_location .= "</h2>";
						$table_info_location .= "<p class=\"lead\">";
						$table_info_location .= sprintf("%s: %s<br>%s: %s",$label_entries,get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default"),$label_flights,$flights);
						$table_info_location .= "</p>";
						$table_info_location .= "<p>".$label_please_note."</p>";
						$table_info_location .= "<ul>";
						$table_info_location .= "<li>".$output_text_017."</li>";
						$table_info_location .= "<li>".$output_text_018."</li>";
						$table_info_location .= "</ul>";

					}
					*/

					$pullsheet_output .= $table_info_header.$table_info_location;

					// Manual pull order per flight (maps are empty when no order has been saved)
					$flight_order_maps = [];
					for($f=1; $f<$flights+1; $f++) $flight_order_maps[$f] = flight_entry_orders((int) $row_tables['id'], $f);
					$has_manual_flight_order = count(array_filter($flight_order_maps)) > 0;

					$table_flight = "";
					$table_flight_datatables = "";

					$random = random_generator(5,2);

					$table_flight_datatables .= "<script>";
					$table_flight_datatables .= "$(document).ready(function() {";
					$table_flight_datatables .= "$('#sortable".$random."').dataTable( {";
					$table_flight_datatables .= "\"bPaginate\" : false,";
					$table_flight_datatables .= "\"sDom\": 'rt',";
					$table_flight_datatables .= "\"bStateSave\" : false,";
					$table_flight_datatables .= "\"bLengthChange\" : false,";
					$table_flight_datatables .= (($has_manual_flight_order) ? "\"aaSorting\": []," : "\"aaSorting\": [[2,'asc'],[1,'asc']],");
					$table_flight_datatables .= "\"bProcessing\" : false,";
					$table_flight_datatables .= "\"aoColumns\": [";
					$table_flight_datatables .= "null,";
					$table_flight_datatables .= "null,";
					$table_flight_datatables .= "null,";
					$table_flight_datatables .= "null,";
					$table_flight_datatables .= "null,";
					$table_flight_datatables .= "null,";
					if ($filter != "mini_bos") $table_flight_datatables .= "null,";
					$table_flight_datatables .= "null";
					$table_flight_datatables .= "]";
					$table_flight_datatables .= "} );";
					$table_flight_datatables .= "} );";
					$table_flight_datatables .= "</script>";

					$table_flight .= $table_flight_datatables;

					$table_flight .= "<table class=\"table table-striped table-bordered\" id=\"sortable".$random."\">";
					$table_flight .= "<thead>";
					$table_flight .= $table_flight_thead;
					$table_flight .= "</thead>";
					$table_flight .= "<tbody>";

					for($i=1; $i<$flights+1; $i++) {

						$flight_rows = [];

						$a = explode(",", $row_tables['tableStyles']);

						//print_r($a);

						foreach (array_unique($a) as $value) {

							include (DB.'output_pullsheets_entries.db.php');

							$table_flight_tbody = "";

							if (!empty($row_entries)) {

								$style = style_number_const($row_entries['brewCategorySort'],$row_entries['brewSubCategory'],$_SESSION['style_set_display_separator'],0);
								$style_special = $row_entries['brewCategorySort']."^".$row_entries['brewSubCategory']."^".$_SESSION['prefsStyleSet'];

								foreach ($rows_entries as $row_entries) {

									// Pulled from the batched map above instead of a fresh
							// check_flight_number() query per entry per flight.
							$flight_round = $check_flight_number_ps($row_entries['id'],$i);

									if (check_flight_round($flight_round,$round)) {

										$row_html_start = strlen($table_flight_tbody);

										$table_flight_tbody .= "<tr>";
										$table_flight_tbody .= "<td>";
										$table_flight_tbody .= "<p>&nbsp;</p>";
										$table_flight_tbody .= "</td>";

										$table_flight_tbody .= "<td>";
										if ($view == "entry")  $table_flight_tbody .= sprintf("%06s",$row_entries['id']);
										else $table_flight_tbody .= sprintf("%06s",$row_entries['brewJudgingNumber']);
										$table_flight_tbody .= "</td>";

										$table_flight_tbody .= "<td>";
										if ($_SESSION['prefsStyleSet'] == "BA") $table_flight_tbody .= $row_entries['brewStyle'];
										else $table_flight_tbody .= $style." ".$row_entries['brewStyle']."<em><br>".$style_convert_1_ps($row_entries['brewCategorySort'])."</em>";
										$table_flight_tbody .= "</td>";
										$table_flight_tbody .= "<td>";

										$special = $style_convert_9_ps($style_special);
										$special = explode("^",$special);

											if ((($_SESSION['prefsStyleSet'] == "BJCP2021") || ($_SESSION['prefsStyleSet'] == "BJCP2025")) && ($style == "02A") && ($row_entries['brewInfo'] != "")) {
												$table_flight_tbody .= "<p><strong>".$label_regional_variation.": </strong> ".str_replace("^"," | ",$row_entries['brewInfo'])."</p>";
											} 

											elseif (($row_entries['brewInfo'] != "") && ($special[4] == "1")) {
												$table_flight_tbody .= "<p><strong>".$label_required_info.": </strong> ".str_replace("^"," | ",$row_entries['brewInfo'])."</p>";
											}

											if ($row_entries['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>".$label_optional_info.": </strong> ".$row_entries['brewInfoOptional']."</p>";
											if ($row_entries['brewComments'] != "") $table_flight_tbody .= "<p><strong>".$label_brewer_specifics.": </strong> ".$row_entries['brewComments']."</p>";

											$table_flight_tbody .= "<ul class=\"list-unstyled\">";
											if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<li><strong>".$label_carbonation.": </strong> ".$row_entries['brewMead1']."</li>";
											if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<li><strong>".$label_sweetness.":</strong> ".$row_entries['brewMead2']."</li>";
											if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<li><strong>".$label_strength.":</strong> ".$row_entries['brewMead3']."</li>";
											

											if (!empty($row_entries['brewPossAllergens'])) $table_flight_tbody .= "<li><strong>".$label_possible_allergens.":</strong> ".$row_entries['brewPossAllergens']."</li>";

											if (!empty($row_entries['brewABV'])) $table_flight_tbody .= "<li><strong>".$label_abv.":</strong> ".$row_entries['brewABV']."</li>";	
											
											/*

											if (($_SESSION['prefsStyleSet'] == "NWCiderCup") && (!empty($row_entries['brewJuiceSource']))) {
												  
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

												$table_flight_tbody .= "<li><strong>".$label_juice_source.":</strong> ".$juice_src_disp."</li>";

											}

											*/

											if (!empty($row_entries['brewPackaging'])) $table_flight_tbody .= "<li><strong>".$label_packaging.":</strong> ".$packaging_display[$row_entries['brewPackaging']]."</li>";

											if ((!empty($row_entries['brewPouring'])) && ((!empty($row_entries['brewStyleType'])) && ($row_entries['brewStyleType'] == 1))) {
												$pouring_arr = json_decode($row_entries['brewPouring'],true);
												$table_flight_tbody .= "<li><strong>".$label_pouring.":</strong> ".$pouring_arr['pouring']."</li>";
												if ((isset($pouring_arr['pouring_notes'])) && (!empty($pouring_arr['pouring_notes']))) $table_flight_tbody .= "<li><strong>".$label_pouring_notes.":</strong> ".$pouring_arr['pouring_notes']."</li>";
												$table_flight_tbody .= "<li><strong>".$label_rouse_yeast.":</strong> ".$pouring_arr['pouring_rouse']."</li>";
											}

											if (!empty($row_entries['brewStaffNotes'])) $table_flight_tbody .= "<li><strong>".$label_notes.":</strong> ".$row_entries['brewStaffNotes']."</li>";

											$table_flight_tbody .= "</ul>";
											$table_flight_tbody .= "</td>";
											$table_flight_tbody .= "<td>";
											$table_flight_tbody .= $row_entries['brewBoxNum'];;
											$table_flight_tbody .= "</td>";
											if ($filter != "mini_bos") {
												$table_flight_tbody .= "<td>";
												$table_flight_tbody .= "<p class=\"box_small\">";
												$table_flight_tbody .= "</td>";
											}
											$table_flight_tbody .= "<td>";
											$table_flight_tbody .= "<p>&nbsp;</p>";
											$table_flight_tbody .= "</td>";
											$table_flight_tbody .= "<td>";
											$table_flight_tbody .= "<p>&nbsp;</p>";
											$table_flight_tbody .= "</td>";
											$table_flight_tbody .= "</tr>";

											$flight_rows[(int) $row_entries['id']] = ['html' => substr($table_flight_tbody, $row_html_start), 'key' => (($view == "default") ? $row_entries['brewJudgingNumber'] : $row_entries['id'])];

										}

								}

							}

						} // end foreach

						// Manual pull order: render this flight as one flat list across styles; otherwise keep collection (style-grouped) order
						$flight_row_ids = array_keys($flight_rows);
						if (!empty($flight_order_maps[$i])) usort($flight_row_ids, function($a,$b) use ($flight_order_maps,$i,$flight_rows) {
							$oa = $flight_order_maps[$i][(int) $a] ?? PHP_INT_MAX;
							$ob = $flight_order_maps[$i][(int) $b] ?? PHP_INT_MAX;
							if ($oa !== $ob) return $oa <=> $ob;
							return strnatcmp(sprintf("%06s",$flight_rows[$a]['key']),sprintf("%06s",$flight_rows[$b]['key']));
						});
						foreach ($flight_row_ids as $flight_row_id) $table_flight .= $flight_rows[$flight_row_id]['html'];

					} // end for($i=1; $i<$flights+1; $i++)

					$table_flight .= "</tbody>";
					$table_flight .= "</table>";

					if (empty($table_flight_tbody)){
						if ($filter == "mini_bos") $pullsheet_output .= "No Mini-BOS entries available.";
						else $pullsheet_output .= "No entries available.";
					}
					else $pullsheet_output .= $table_flight;
					//if (($flights > 0) && ($filter != "mini_bos")) $pullsheet_output .= "<div style=\"page-break-after:always;\"></div>";
					//if (($flights == 0) || ($filter == "mini_bos")) $pullsheet_output .= "<div style=\"page-break-after:always;\"></div>";
					$pullsheet_output .= "<div style=\"page-break-after:always;\"></div>";

				}

			}

			// Separate by flights if pulling for general judging

			else {

				foreach ($rows_tables as $row_tables) {

					// Reset Vars
					$table_info_location = "";
					$table_info_notes = "";
					$table_info_header = "";

					// Pulled from the batched map above instead of a fresh
					// number_of_flights() query per table.
					$flights = $number_of_flights_ps($row_tables['id']);
					if ($flights > 0) $flights = $flights; else $flights = "0";

					$table_info_header .= "<div class=\"page-header\">";
					$table_info_header .= "<h1>";
					$table_info_header .= sprintf("%s %s: %s",$label_table,$row_tables['tableNumber'],$row_tables['tableName']);
					if ($filter == "mini_bos") $table_info_header .= sprintf(" - %s",$label_mini_bos);
					$table_info_header .= "</h1>";
					$table_info_header .= "</div>";

					if (!empty($row_tables['tableLocation'])) {

						$table_info_location .= "<h2>";
						// Pulled from the batched maps above instead of fresh
						// table_location()/get_table_info() queries per table.
						$table_info_location .= $table_location_ps($row_tables['tableLocation']);
						if ($round != "default") $table_info_location .= sprintf("<br>%s %s",$label_round,$round);
						$table_info_location .= "</h2>";
						$table_info_location .= "<p class=\"lead\">";
						$table_info_location .= sprintf("%s: %s<br>%s: %s",$label_entries,$table_entry_count_ps($row_tables['tableStyles']),$label_flights,$flights);
						$table_info_location .= "</p>";
						$table_info_location .= "<div class=\"alert alert-warning hidden-print\">";
						$table_info_location .= "<p><strong>".$label_please_note."</strong></p>";
						$table_info_location .= "<ul>";
						$table_info_location .= "<li>".$output_text_017."</li>";
						$table_info_location .= "<li>".$output_text_018."</li>";
						$table_info_location .= "</ul>";
						$table_info_location .= "</div>";

					}

					$pullsheet_output .= $table_info_header.$table_info_location;

					for($i=1; $i<$flights+1; $i++) {

						$table_flight = "";
						$flight_rows = [];
						// Manual pull order for this flight (empty when none saved)
						$flight_order_map = flight_entry_orders((int) $row_tables['id'], $i);
						$has_manual_flight_order = count($flight_order_map) > 0;

						$table_flight_datatables = "";

						$random = random_generator(5,2);

						// Pulled from the batched flights map above instead of a
						// fresh query per table per flight.
						$row_round_check = array('flightRound' => $flight_round_by_table_flight_ps[$row_tables['id'].'|'.$i] ?? null);

						$table_flight .= "<h3>".sprintf("%s %s: %s - %s %s, %s %s",$label_table,$row_tables['tableNumber'],$row_tables['tableName'],$label_flight,$i,$label_round,$row_round_check['flightRound'])."</h3>";

						$table_flight_datatables .= "<script>";
						$table_flight_datatables .= "$(document).ready(function() {";
						$table_flight_datatables .= "$('#sortable".$random."').dataTable( {";
						$table_flight_datatables .= "\"bPaginate\" : false,";
						$table_flight_datatables .= "\"sDom\": 'rt',";
						$table_flight_datatables .= "\"bStateSave\" : false,";
						$table_flight_datatables .= "\"bLengthChange\" : false,";
						$table_flight_datatables .= (($has_manual_flight_order) ? "\"aaSorting\": []," : "\"aaSorting\": [[2,'asc'],[1,'asc']],");
						$table_flight_datatables .= "\"bProcessing\" : false,";
						$table_flight_datatables .= "\"aoColumns\": [";
						$table_flight_datatables .= "null,";
						$table_flight_datatables .= "null,";
						$table_flight_datatables .= "null,";
						$table_flight_datatables .= "null,";
						$table_flight_datatables .= "null,";
						$table_flight_datatables .= "null,";
						if ($filter != "mini_bos") $table_flight_datatables .= "null,";
						$table_flight_datatables .= "null";
						$table_flight_datatables .= "]";
						$table_flight_datatables .= "} );";
						$table_flight_datatables .= "} );";
						$table_flight_datatables .= "</script>";

						$table_flight .= $table_flight_datatables;

						$table_flight .= "<table class=\"table table-striped table-bordered\" id=\"sortable".$random."\">";
						$table_flight .= "<thead>";
						$table_flight .= $table_flight_thead;
						$table_flight .= "</thead>";
						$table_flight .= "<tbody>";

						$a = explode(",", $row_tables['tableStyles']);
						//print_r($a);
						foreach (array_unique($a) as $value) {

							// Pulled from the batched styles/entries maps above instead
							// of a fresh output_pullsheets_entries.db.php query per style.
							$row_style_ps_lookup = $styles_by_id_ps[$value] ?? null;
							$rows_entries = ($row_style_ps_lookup) ? ($entries_by_style_ps[$row_style_ps_lookup['brewStyleGroup'].'|'.$row_style_ps_lookup['brewStyleNum']] ?? array()) : array();
							$row_entries = (!empty($rows_entries)) ? $rows_entries[0] : null;
							$totalRows_entries = count($rows_entries);
							$style = style_number_const($row_entries['brewCategorySort'],$row_entries['brewSubCategory'],$_SESSION['style_set_display_separator'],1);
							$style_special = $row_entries['brewCategorySort']."^".$row_entries['brewSubCategory']."^".$_SESSION['prefsStyleSet'];

							foreach ($rows_entries as $row_entries) {

								$table_flight_tbody = "";

								// Pulled from the batched map above instead of a fresh
							// check_flight_number() query per entry per flight.
							$flight_round = $check_flight_number_ps($row_entries['id'],$i);

								if (check_flight_round($flight_round,$round)) {

									$table_flight_tbody .= "<tr>";
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= "<p>&nbsp;</p>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									if ($view == "entry")  $table_flight_tbody .= sprintf("%06s",$row_entries['id']);
									else $table_flight_tbody .= sprintf("%06s",$row_entries['brewJudgingNumber']);
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									if ($_SESSION['prefsStyleSet'] == "BA") $table_flight_tbody .= $row_entries['brewStyle'];
									else $table_flight_tbody .= $style." ".$row_entries['brewStyle']."<em><br>".$style_convert_1_ps($row_entries['brewCategorySort'])."</em>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";

									$special = $style_convert_9_ps($style_special);
									$special = explode("^",$special);

									if ((($_SESSION['prefsStyleSet'] == "BJCP2021") || ($_SESSION['prefsStyleSet'] == "BJCP2025")) && ($style == "02A") && ($row_entries['brewInfo'] != "")) {
										$table_flight_tbody .= "<p><strong>".$label_regional_variation.": </strong> ".str_replace("^"," | ",$row_entries['brewInfo'])."</p>";
									} 

									elseif (($row_entries['brewInfo'] != "") && ((isset($special[4])) && ($special[4] == "1"))) {
										$table_flight_tbody .= "<p><strong>".$label_required_info.": </strong> ".str_replace("^"," | ",$row_entries['brewInfo'])."</p>";
									}

									if ($row_entries['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>".$label_optional_info.": </strong> ".$row_entries['brewInfoOptional']."</p>";
									
									if ($row_entries['brewComments'] != "") $table_flight_tbody .= "<p><strong>".$label_brewer_specifics.": </strong> ".$row_entries['brewComments']."</p>";

									$table_flight_tbody .= "<ul class=\"list-unstyled\">";
									
									if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<li><strong>".$label_carbonation.": </strong> ".$row_entries['brewMead1']."</li>";

									if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<li><strong>".$label_sweetness.":</strong> ".$row_entries['brewMead2']."</li>";

									if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<li><strong>".$label_strength.":</strong> ".$row_entries['brewMead3'];
									$table_flight_tbody .= "</li>";

									if (!empty($row_entries['brewPossAllergens'])) $table_flight_tbody .= "<li><strong>".$label_possible_allergens.":</strong> ".$row_entries['brewPossAllergens']."</li>";

									if (!empty($row_entries['brewABV'])) $table_flight_tbody .= "<li><strong>".$label_abv.":</strong> ".$row_entries['brewABV']."</li>";

									/*

									if (($_SESSION['prefsStyleSet'] == "NWCiderCup") && (!empty($row_entries['brewJuiceSource']))) {
										  
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

										$table_flight_tbody .= "<li><strong>".$label_juice_source.":</strong> ".$juice_src_disp."</li>";

									}

									*/

									if (!empty($row_entries['brewPackaging'])) $table_flight_tbody .= "<li><strong>".$label_packaging.":</strong> ".$packaging_display[$row_entries['brewPackaging']]."</li>";

									if ((!empty($row_entries['brewPouring'])) && ((!empty($row_entries['brewStyleType'])) && ($row_entries['brewStyleType'] == 1))) {
										$pouring_arr = json_decode($row_entries['brewPouring'],true);
										$table_flight_tbody .= "<li><strong>".$label_pouring.":</strong> ".$pouring_arr['pouring']."</li>";
										if ((isset($pouring_arr['pouring_notes'])) && (!empty($pouring_arr['pouring_notes']))) $table_flight_tbody .= "<li><strong>".$label_pouring_notes.":</strong> ".$pouring_arr['pouring_notes']."</li>";
										$table_flight_tbody .= "<li><strong>".$label_rouse_yeast.":</strong> ".$pouring_arr['pouring_rouse']."</li>";
									}

									if (!empty($row_entries['brewStaffNotes'])) $table_flight_tbody .= "<li><strong>".$label_notes.":</strong> ".$row_entries['brewStaffNotes']."</li>";
									
									$table_flight_tbody .= "</ul>";

									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= $row_entries['brewBoxNum'];;
									$table_flight_tbody .= "</td>";
									if ($filter != "mini_bos") {
										$table_flight_tbody .= "<td>";
										$table_flight_tbody .= "<p class=\"box_small\">";
										$table_flight_tbody .= "</td>";
									}
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= "<p>&nbsp;</p>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= "<p>&nbsp;</p>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "</tr>";

								}

								$flight_rows[(int) $row_entries['id']] = ['html' => $table_flight_tbody, 'key' => (($view == "default") ? $row_entries['brewJudgingNumber'] : $row_entries['id'])];

							}

						} // end foreach

						// Manual pull order: render this flight as one flat list across styles; otherwise keep collection (style-grouped) order
						$flight_row_ids = array_keys($flight_rows);
						if (!empty($flight_order_map)) usort($flight_row_ids, function($a,$b) use ($flight_order_map,$flight_rows) {
							$oa = $flight_order_map[(int) $a] ?? PHP_INT_MAX;
							$ob = $flight_order_map[(int) $b] ?? PHP_INT_MAX;
							if ($oa !== $ob) return $oa <=> $ob;
							return strnatcmp(sprintf("%06s",$flight_rows[$a]['key']),sprintf("%06s",$flight_rows[$b]['key']));
						});
						foreach ($flight_row_ids as $flight_row_id) $table_flight .= $flight_rows[$flight_row_id]['html'];

						$table_flight .= "</tbody>";
						$table_flight .= "</table>";
						$pullsheet_output .= $table_flight;
						if (($flights > 0) && ($filter != "mini_bos")) $pullsheet_output .= "<div style=\"page-break-after:always;\"></div>";

					} // end for($i=1; $i<$flights+1; $i++)

					if (($flights == 0) || ($filter == "mini_bos")) $pullsheet_output .= "<div style=\"page-break-after:always;\"></div>";

				}
			}

		} // end if ($tables_all)

		// Or just a single table
		else {

			// Reset Vars
			$pullsheet_output = "";
			$table_info_location = "";
			$table_info_notes = "";
			$table_info_header = "";

			// Pulled from the batched map above instead of a fresh number_of_flights() query.
			$flights = $number_of_flights_ps($row_tables['id']);
			if ($flights > 0) $flights = $flights; else $flights = "0";

			$table_info_header .= "<div class=\"page-header\">";
			$table_info_header .= "<h1>";
			$table_info_header .= sprintf("%s %s: %s",$label_table,$row_tables['tableNumber'],$row_tables['tableName']);
			if ($filter == "mini_bos") $table_info_header .= sprintf(" - %s",$label_mini_bos);
			$table_info_header .= "</h1>";
			$table_info_header .= "</div>";

			if (!empty($row_tables['tableLocation'])) {

				$table_info_location .= "<h2>";
				// Pulled from the batched maps above instead of fresh
				// table_location()/get_table_info() queries per table.
				$table_info_location .= $table_location_ps($row_tables['tableLocation']);
				if ($round != "default") $table_info_location .= sprintf("<br>%s %s",$label_round,$round);
				$table_info_location .= "</h2>";
				$table_info_location .= "<p class=\"lead\">";
				$table_info_location .= sprintf("%s: %s<br>%s: %s",$label_entries,$table_entry_count_ps($row_tables['tableStyles']),$label_flights,$flights);
				$table_info_location .= "</p>";
				$table_info_location .= "<p class=\"hidden-print\">".$label_please_note."</p>";
				$table_info_location .= "<ul class=\"hidden-print\">";
				$table_info_location .= "<li>".$output_text_017."</li>";
				$table_info_location .= "<li>".$output_text_018."</li>";
				$table_info_location .= "</ul>";

			}

			$pullsheet_output .= $table_info_header;

			// If MBOS, add datatables and table, and table header
			if ($filter == "mini_bos") {
				$pullsheet_output .= "<script>";
				$pullsheet_output .= "$(document).ready(function() {";
				$pullsheet_output .= "$('#sortable".$row_tables['tableNumber']."').dataTable( {";
				$pullsheet_output .= "\"bPaginate\" : false,";
				$pullsheet_output .= "\"sDom\": 'rt',";
				$pullsheet_output .= "\"bStateSave\" : false,";
				$pullsheet_output .= "\"bLengthChange\" : false,";
				$pullsheet_output .= "\"aaSorting\": [[2,'asc'],[1,'asc']],";
				$pullsheet_output .= "\"bProcessing\" : false,";
				$pullsheet_output .= "\"aoColumns\": [";
				$pullsheet_output .= "null,";
				$pullsheet_output .= "null,";
				$pullsheet_output .= "null,";
				$pullsheet_output .= "null,";
				$pullsheet_output .= "null,";
				$pullsheet_output .= "null,";
				$pullsheet_output .= "null";
				$pullsheet_output .= "]";
				$pullsheet_output .= "} );";
				$pullsheet_output .= "} );";
				$pullsheet_output .= "</script>";

				$pullsheet_output .= "<table class=\"table table-striped table-bordered\" id=\"sortable".$row_tables['tableNumber']."\">";
				$pullsheet_output .= "<thead>";
				$pullsheet_output .= $table_flight_thead;
				$pullsheet_output .= "</thead>";
				$pullsheet_output .= "<tbody>";

			}

			else $pullsheet_output .= $table_info_location;

			// Loop through flights. Gather entry information

			for($i=1; $i<$flights+1; $i++) {

				$table_flight = "";
				$flight_rows = [];
				$table_flight_datatables = "";
				// Manual pull order for this flight (empty when none saved)
				$flight_order_map = flight_entry_orders((int) $row_tables['id'], $i);
				$has_manual_flight_order = count($flight_order_map) > 0;



				if ($filter != "mini_bos") {

					$random = random_generator(5,2);

					// Pulled from the batched flights map above instead of a
					// fresh query per table per flight.
					$row_round_check = array('flightRound' => $flight_round_by_table_flight_ps[$row_tables['id'].'|'.$i] ?? null);

					$table_flight .= "<h3>".sprintf("%s %s: %s - %s %s, %s %s",$label_table,$row_tables['tableNumber'],$row_tables['tableName'],$label_flight,$i,$label_round,$row_round_check['flightRound'])."</h3>";

					$table_flight_datatables .= "<script>";
					$table_flight_datatables .= "$(document).ready(function() {";
					$table_flight_datatables .= "$('#sortable".$random."').dataTable( {";
					$table_flight_datatables .= "\"bPaginate\" : false,";
					$table_flight_datatables .= "\"sDom\": 'rt',";
					$table_flight_datatables .= "\"bStateSave\" : false,";
					$table_flight_datatables .= "\"bLengthChange\" : false,";
					$table_flight_datatables .= (($has_manual_flight_order) ? "\"aaSorting\": []," : "\"aaSorting\": [[2,'asc'],[1,'asc']],");
					$table_flight_datatables .= "\"bProcessing\" : false,";
					$table_flight_datatables .= "\"aoColumns\": [";
					$table_flight_datatables .= "null,";
					$table_flight_datatables .= "null,";
					$table_flight_datatables .= "null,";
					$table_flight_datatables .= "null,";
					$table_flight_datatables .= "null,";
					$table_flight_datatables .= "null,";
					$table_flight_datatables .= "null,";
					$table_flight_datatables .= "null";
					$table_flight_datatables .= "]";
					$table_flight_datatables .= "} );";
					$table_flight_datatables .= "} );";
					$table_flight_datatables .= "</script>";

					$table_flight .= $table_flight_datatables;
					$table_flight .= "<table class=\"table table-striped table-bordered\" id=\"sortable".$random."\">";
					$table_flight .= "<thead>";
					$table_flight .= $table_flight_thead;
					$table_flight .= "</thead>";
					$table_flight .= "<tbody>";
				}

				$a = explode(",", $row_tables['tableStyles']);
				//print_r($a);
				
				foreach (array_unique($a) as $value) {

					// Pulled from the batched styles/entries maps above instead
					// of a fresh output_pullsheets_entries.db.php query per style
					// per table per flight - guarded to the mini_bos filter's own
					// (different, unbatched) query, which is left untouched.
					if ($filter == "mini_bos") {
						include (DB.'output_pullsheets_entries.db.php');
					}
					else {
						$row_style_ps_lookup = $styles_by_id_ps[$value] ?? null;
						$rows_entries = ($row_style_ps_lookup) ? ($entries_by_style_ps[$row_style_ps_lookup['brewStyleGroup'].'|'.$row_style_ps_lookup['brewStyleNum']] ?? array()) : array();
						$row_entries = (!empty($rows_entries)) ? $rows_entries[0] : null;
						$totalRows_entries = count($rows_entries);
					}

					$table_flight_tbody = "";

					if (!empty($row_entries)) {

						$style = style_number_const($row_entries['brewCategorySort'],$row_entries['brewSubCategory'],$_SESSION['style_set_display_separator'],1);
						$style_special = $row_entries['brewCategorySort']."^".$row_entries['brewSubCategory']."^".$_SESSION['prefsStyleSet'];

						foreach ($rows_entries as $row_entries) {

							$table_flight_tbody = "";

							// Pulled from the batched map above instead of a fresh
							// check_flight_number() query per entry per flight.
							$flight_round = $check_flight_number_ps($row_entries['id'],$i);

							if (check_flight_round($flight_round,$round)) {

								$table_flight_tbody .= "<tr>";

								// Pull Order
								$table_flight_tbody .= "<td>";
								$table_flight_tbody .= "<p>&nbsp;</p>";
								$table_flight_tbody .= "</td>";

								// Entry or Judging Number
								$table_flight_tbody .= "<td>";
								if ($view == "entry")  $table_flight_tbody .= sprintf("%06s",$row_entries['id']);
								else $table_flight_tbody .= sprintf("%06s",$row_entries['brewJudgingNumber']);
								$table_flight_tbody .= "</td>";

								// Style
								$table_flight_tbody .= "<td>";
								if ($_SESSION['prefsStyleSet'] == "BA") $table_flight_tbody .= $row_entries['brewStyle'];
								else $table_flight_tbody .= $style." ".$row_entries['brewStyle']."<em><br>".$style_convert_1_ps($row_entries['brewCategorySort'])."</em>";
								$table_flight_tbody .= "</td>";
								
								// Entry Info
								$table_flight_tbody .= "<td>";
								$special = $style_convert_9_ps($style_special);
								$special = explode("^",$special);

								if ((($_SESSION['prefsStyleSet'] == "BJCP2021") || ($_SESSION['prefsStyleSet'] == "BJCP2025")) && ($style == "02A") && ($row_entries['brewInfo'] != "")) {
									$table_flight_tbody .= "<p><strong>".$label_regional_variation.": </strong> ".str_replace("^"," | ",$row_entries['brewInfo'])."</p>";
								} 

								elseif (($row_entries['brewInfo'] != "") && ($special[4] == "1")) {
									$table_flight_tbody .= "<p><strong>".$label_required_info.": </strong> ".str_replace("^"," | ",$row_entries['brewInfo'])."</p>";
								}
								
								if ($row_entries['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>".$label_optional_info.": </strong>".$row_entries['brewInfoOptional']."</p>";
								
								if ($row_entries['brewComments'] != "") $table_flight_tbody .= "<p><strong>".$label_brewer_specifics.": </strong> ".$row_entries['brewComments']."</p>";

								$table_flight_tbody .= "<ul class=\"list-unstyled\">";

								if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<li><strong>".$label_carbonation.": </strong> ".$row_entries['brewMead1']."</li>";
								if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<li><strong>".$label_sweetness.":</strong> ".$row_entries['brewMead2']."</li>";				

								if (($_SESSION['prefsStyleSet'] == "NWCiderCup") && (!empty($row_entries['brewSweetnessLevel']))) $table_flight_tbody .= "<strong>".$label_final_gravity.":</strong> ".$row_entries['brewSweetnessLevel'];

								if (($_SESSION['prefsStyleSet'] != "NWCiderCup") && (!empty($row_entries['brewSweetnessLevel']))) {

									$sweetness_json = json_decode($row_entries['brewSweetnessLevel'],true);
									
									if (json_last_error() === JSON_ERROR_NONE) {
										if (!empty($sweetness_json['OG'])) $table_flight_tbody .= "<li><strong>".$label_original_gravity.":</strong> ".$sweetness_json['OG']."</li>";
										if (!empty($sweetness_json['FG'])) $table_flight_tbody .= "<li><strong>".$label_final_gravity.":</strong> ".$sweetness_json['FG']."</li>";
									}
									
									else {
										$table_flight_tbody .= "<strong>".$label_final_gravity.":</strong> ";
									}
								
								}

								if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<li><strong>".$label_strength.":</strong> ".$row_entries['brewMead3']."</li>";

								if (!empty($row_entries['brewPossAllergens'])) $table_flight_tbody .= "<li><strong>".$label_possible_allergens.":</strong> ".$row_entries['brewPossAllergens']."</li>";

								if (!empty($row_entries['brewABV'])) $table_flight_tbody .= "<li><strong>".$label_abv.":</strong> ".$row_entries['brewABV']."</li>";

								/*	
								
								if (($_SESSION['prefsStyleSet'] == "NWCiderCup") && (!empty($row_entries['brewJuiceSource']))) {
									  
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

									$table_flight_tbody .= "<li><strong>".$label_juice_source.":</strong> ".$juice_src_disp."</li>";

								}

								*/

								if (!empty($row_entries['brewPackaging'])) $table_flight_tbody .= "<li><strong>".$label_packaging.":</strong> ".$packaging_display[$row_entries['brewPackaging']]."</li>";

								if ((!empty($row_entries['brewPouring'])) && ((!empty($row_entries['brewStyleType'])) && ($row_entries['brewStyleType'] == 1))) {
									$pouring_arr = json_decode($row_entries['brewPouring'],true);
									$table_flight_tbody .= "<li><strong>".$label_pouring.":</strong> ".$pouring_arr['pouring']."</li>";
									if ((isset($pouring_arr['pouring_notes'])) && (!empty($pouring_arr['pouring_notes']))) $table_flight_tbody .= "<li><strong>".$label_pouring_notes.":</strong> ".$pouring_arr['pouring_notes']."</li>";
									$table_flight_tbody .= "<li><strong>".$label_rouse_yeast.":</strong> ".$pouring_arr['pouring_rouse']."</li>";
								}

								if (!empty($row_entries['brewStaffNotes'])) $table_flight_tbody .= "<li><strong>".$label_notes.":</strong> ".$row_entries['brewStaffNotes']."</li>";
										
								$table_flight_tbody .= "</ul>";
								$table_flight_tbody .= "</td>";
								
								// Box/Location
								$table_flight_tbody .= "<td>";
								$table_flight_tbody .= $row_entries['brewBoxNum'];;
								$table_flight_tbody .= "</td>";

								// Mini-BOS
								if ($filter != "mini_bos") {
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= "<p class=\"box_small\">";
									if (($filter == "mini_bos") && ($row_entries['scoreMiniBOS'] == 1)) $table_flight_tbody .= "<span class=\"fa fa-check\"></span>";
									else $table_flight_tbody .= "&nbsp;";
									$table_flight_tbody .= "</td>";
								}

								// Score
								$table_flight_tbody .= "<td>";
								$table_flight_tbody .= "<p>&nbsp;</p>";
								$table_flight_tbody .= "</td>";

								// Place
								$table_flight_tbody .= "<td>";
								$table_flight_tbody .= "<p>&nbsp;</p>";
								$table_flight_tbody .= "</td>";
								$table_flight_tbody .= "</tr>";

							}

							$flight_rows[(int) $row_entries['id']] = ['html' => $table_flight_tbody, 'key' => (($view == "default") ? $row_entries['brewJudgingNumber'] : $row_entries['id'])];

						}

					} // if (!empty($row_entries))

				} // end foreach

				// Manual pull order: render this flight as one flat list across styles; otherwise keep collection (style-grouped) order
				$flight_row_ids = array_keys($flight_rows);
				if (!empty($flight_order_map)) usort($flight_row_ids, function($a,$b) use ($flight_order_map,$flight_rows) {
					$oa = $flight_order_map[(int) $a] ?? PHP_INT_MAX;
					$ob = $flight_order_map[(int) $b] ?? PHP_INT_MAX;
					if ($oa !== $ob) return $oa <=> $ob;
					return strnatcmp(sprintf("%06s",$flight_rows[$a]['key']),sprintf("%06s",$flight_rows[$b]['key']));
				});
				foreach ($flight_row_ids as $flight_row_id) $table_flight .= $flight_rows[$flight_row_id]['html'];

				if ($filter != "mini_bos") {
					$table_flight .= "</tbody>";
					$table_flight .= "</table>";
				}

				$pullsheet_output .= $table_flight;

			} // end for($i=1; $i<$flights+1; $i++)

			if ($filter == "mini_bos") {
				$pullsheet_output .= "</tbody>";
				$pullsheet_output .= "</table>";
			}

			$pullsheet_output .= "<div style=\"page-break-after:always;\"></div>";

		} // end else

	} // end if (!$queued)

} // end elseif (($go != "judging_scores_bos") && ($go != "mini_bos") && ($go != "all_entry_info"))

echo $pullsheet_output;
?>