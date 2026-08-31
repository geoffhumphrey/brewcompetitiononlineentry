<?php
/**
 * Module:      awards.php
 * Description: This module is the delivery vehicle for the awards presentation.
 *
 */

// ---------------------------- Load Config Scripts ------------------------------

require_once ('paths.php');
require_once (CONFIG.'bootstrap.php');
require_once (LIB.'admin.lib.php');
include (DB.'winners.db.php');
include (DB.'score_count.db.php');
include (DB.'sponsors.db.php');

$table_head1 = "";
$table_head2 = "";
$table_body1 = "";
$table_body2 = "";
$bb_count = 0;
$bb_count_clubs = "";
$bb_previouspoints = "";
$bb_previouspoints_clubs = "";

$winner_method = $_SESSION['prefsWinnerMethod'];
$style_set = $_SESSION['prefsStyleSet'];

$display_to_public = FALSE;
if (($judging_past == 0) && ($entry_window_open == 2) && ($registration_open == 2) && ($judge_window_open == 2) && ($_SESSION['prefsDisplayWinners'] == "Y") && (judging_winner_display($_SESSION['prefsWinnerDelay']))) $display_to_public = TRUE;

$display_to_admin = FALSE;
if (($logged_in) && ($_SESSION['userLevel'] <= 1)) $display_to_admin = TRUE;

if ((!$display_to_public) && (!$display_to_admin)) {
	header(sprintf("Location: %s", $base_url."index.php?msg=7"));
    exit;
}

if (($display_to_admin) || ($display_to_public)) {

	if ($view == "default") $view = "white";

	$reveal_theme = array(
		"white" => "white.min.css",
		"black" => "black.min.css",
		"blue" => "moon.min.css",
	);

	$places = array(
		"5" => "1st",
		"4" => "2nd",
		"3" => "3rd",
		"2" => "4th",
		"1" => "HM"
	);

	// Judges and Stewards
	$sql = sprintf("SELECT DISTINCT c.uid, c.brewerLastName, c.brewerFirstName, c.brewerJudgeRank, c.brewerClubs, a.assignment, b.staff_judge, b.staff_steward, b.staff_judge_bos, b.staff_staff, b.staff_organizer FROM %s a RIGHT JOIN (%s b CROSS JOIN %s c ON b.uid=c.uid) ON c.uid=a.bid WHERE b.staff_judge='1' OR b.staff_steward='1' OR b.staff_judge_bos='1' OR b.staff_staff='1' OR b.staff_organizer='1' ORDER BY c.brewerLastName, c.brewerFirstName ASC;", $prefix."judging_assignments", $prefix."staff", $prefix."brewer");
	$row_assignments = $db_conn->rawQuery($sql);
	$totalRows_assignments = $db_conn->count;

	$judge_list = "";
	$judge_bos = "";
	$steward_list = "";
	$staff_list = "";
	$staff_organizer = "";

	if ($totalRows_assignments > 0) {
		
		foreach ($row_assignments as $row_assignments) {

			if ($row_assignments['staff_judge'] == 1) {
				$judge_list .= $row_assignments['brewerFirstName']." ".$row_assignments['brewerLastName'];
				$judge_list .= ", ";
			}

			if ($row_assignments['staff_judge_bos'] == 1) {
				$judge_bos .= $row_assignments['brewerFirstName']." ".$row_assignments['brewerLastName'];
				$judge_bos .= ", ";
			}

			if ($row_assignments['staff_steward'] == 1) {
				$steward_list .= $row_assignments['brewerFirstName']." ".$row_assignments['brewerLastName'];
				$steward_list .= ", ";
			}

			if ($row_assignments['staff_staff'] == 1)  {
				$staff_list .= $row_assignments['brewerFirstName']." ".$row_assignments['brewerLastName'];
				$staff_list .= ", ";
			}

			if ($row_assignments['staff_organizer'] == 1)  {
				$staff_organizer .= $row_assignments['brewerFirstName']." ".$row_assignments['brewerLastName'];
				$staff_organizer .= ", ";
			}

		}
	
	}

	$slides = "";
	$slides_bos = "";
	$slides_best_brewer = "";
	$slides_best_club = "";

	if ($row_scored_entries['count'] > 0) {

		// Build slides by Table
		if ($_SESSION['prefsWinnerMethod'] == "0") {

			$order_by = array();

			if ($totalRows_tables > 0) {

				/**
				 * Batch what used to be 4+ queries per table (scores.db.php's fetch,
				 * get_table_info()'s per-style count loop, assigned_judges()'s count,
				 * and an inline judge-names query) into 4 queries total, run once up
				 * front - same pattern as pub/winners.pub.php's per-table batching,
				 * extended to cover this file's extra per-table lookups.
				 */

				$all_style_ids = array();
				foreach ($rows_tables as $row_tables_prefetch) {
					foreach (explode(",", $row_tables_prefetch['tableStyles']) as $style_id) {
						if ($style_id !== "") $all_style_ids[] = $style_id;
					}
				}
				$all_style_ids = array_unique($all_style_ids);

				$styles_by_id = array();
				if (!empty($all_style_ids)) {
					$db_conn->where('id', $all_style_ids, 'in');
					$rows_all_styles = $db_conn->get($prefix."styles", null, "id,brewStyleGroup,brewStyleNum");
					foreach ($rows_all_styles as $row_style) {
						$styles_by_id[$row_style['id']] = $row_style;
					}
				}

				// Mirrors get_table_info(1,"count_total",...,"default")'s own query exactly,
				// including the jPrefsTablePlanning exception, just grouped instead of
				// run once per style per table.
				$counts_by_style = array();
				if (table_exists($brewing_db_table)) {
					if ($_SESSION['jPrefsTablePlanning'] != 1) $db_conn->where('brewReceived', '1');
					$db_conn->groupBy('brewCategorySort');
					$db_conn->groupBy('brewSubCategory');
					$rows_style_counts = $db_conn->get($brewing_db_table, null, "brewCategorySort, brewSubCategory, COUNT(*) as count");
					foreach ($rows_style_counts as $row_style_count) {
						$counts_by_style[$row_style_count['brewCategorySort'].'|'.$row_style_count['brewSubCategory']] = $row_style_count['count'];
					}
				}

				// Mirrors includes/db/scores.db.php's winner_method==0 query exactly, just
				// widened from "WHERE a.scoreTable=?" (one table) to an IN-list (all tables).
				$table_ids = array_column($rows_tables, 'id');
				$scores_by_table = array();
				if (!empty($table_ids)) {
					$placeholders = implode(',', array_fill(0, count($table_ids), '?'));
					$query_scores_all = "SELECT * FROM ".$judging_scores_db_table." a, ".$brewing_db_table." b, ".$brewer_db_table." c WHERE a.scoreTable IN (".$placeholders.") AND a.eid = b.id AND c.uid = b.brewBrewerID";
					if ((($action == "print") && ($view == "winners")) || ($action == "default") || ($section == "default")) $query_scores_all .= " AND a.scorePlace > 0";
					$query_scores_all .= " ORDER BY a.scoreTable";
					if ($action == "awards-pres") $query_scores_all .= ", a.scorePlace DESC";
					else $query_scores_all .= ", a.scorePlace ASC";
					$rows_scores_all = $db_conn->rawQuery($query_scores_all, $table_ids);
					foreach ($rows_scores_all as $row_score_all) {
						$scores_by_table[$row_score_all['scoreTable']][] = $row_score_all;
					}
				}

				// Mirrors the original inline judge-names query exactly, just widened to
				// all tables at once and grouped by table below.
				$judge_names_by_table = array();
				if (!empty($table_ids)) {
					$placeholders_j = implode(',', array_fill(0, count($table_ids), '?'));
					$query_judge_names_all = "SELECT a.brewerFirstName,a.brewerLastName, b.assignRoles, b.assignTable FROM ".$prefix."brewer"." a, ".$prefix."judging_assignments"." b WHERE b.assignTable IN (".$placeholders_j.") AND assignment = 'J' AND a.uid = b.bid ORDER BY b.assignTable, a.brewerLastName, a.brewerFirstName ASC";
					$rows_judge_names_all = $db_conn->rawQuery($query_judge_names_all, $table_ids);
					foreach ($rows_judge_names_all as $row_judge_name) {
						$judge_names_by_table[$row_judge_name['assignTable']][] = $row_judge_name;
					}
				}

				foreach ($rows_tables as $row_tables) {

					$slides_tables = "";

					// Pulled from the batched fetch above instead of a fresh
					// include(DB.'scores.db.php') query per table.
					$rows_scores = $scores_by_table[$row_tables['id']] ?? array();
					$totalRows_scores = count($rows_scores);

					// entry_count: sum of counts_by_style for each style on this table -
					// mirrors get_table_info(1,"count_total",...) exactly, batched.
					$entry_count = 0;
					foreach (explode(",", $row_tables['tableStyles']) as $table_style_id) {
						if (!isset($styles_by_id[$table_style_id])) continue;
						$row_styles_lookup = $styles_by_id[$table_style_id];
						$entry_count += $counts_by_style[$row_styles_lookup['brewStyleGroup'].'|'.$row_styles_lookup['brewStyleNum']] ?? 0;
					}
					if ($entry_count > 1) $entries = strtolower($label_entries); else $entries = strtolower($label_entry);

					$assigned_judge_names_display = "";
					$table_judge_names = $judge_names_by_table[$row_tables['id']] ?? array();

					if (!empty($table_judge_names)) {

						foreach ($table_judge_names as $row_assigned_judge_names) {
							$assigned_judge_names_display .= $row_assigned_judge_names['brewerFirstName']." ".$row_assigned_judge_names['brewerLastName'];
							if ((isset($row_assigned_judge_names['assignRoles'])) && (strpos($row_assigned_judge_names['assignRoles'], "HJ") !== false)) $assigned_judge_names_display .= " <span style=\"font-size: .75em;\">(".$label_head_judge.")</span>";
							$assigned_judge_names_display .= ", ";
						}

						$assigned_judge_names_display = rtrim($assigned_judge_names_display, ", ");

					}

					// Build Slide
					$slides_tables .= "<section>";

					if (($go == "table-numbers") || ($go == "default")) $slides_tables .= sprintf("<h1 class=\"r-fit-text tight\">%s %s: %s</h1>",$label_table,$row_tables['tableNumber'],$row_tables['tableName']);
					
					else {
						if (strlen($row_tables['tableName']) > 18) $slides_tables .= sprintf("<h1 class=\"r-fit-text tight\">%s</h1>",$row_tables['tableName']);
						else $slides_tables .= sprintf("<h1 class=\"tight\">%s</h1>",$row_tables['tableName']);
					}


					$slides_tables .= "<p class=\"entry-count\">";
					$slides_tables .= sprintf("%s %s",$entry_count,$entries);
					$slides_tables .= "</p>";
					if (!empty($assigned_judge_names_display)) $slides_tables .= sprintf("<p class=\"small entry-count\">%s: %s</p>",$label_judges,$assigned_judge_names_display);

					// Perform check to see if any placing entries
					// If so, loop through and display as normal
					if ($totalRows_scores > 0) {

						foreach ($rows_scores as $row_scores) {

							$place_heirarchy = place_heirarchy($row_scores['scorePlace']);
							$display_place = display_place($row_scores['scorePlace'],1);

							$entry_name = html_entity_decode($row_scores['brewName'], ENT_QUOTES | ENT_XML1, 'UTF-8');
							$entry_name = htmlentities($entry_name,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5,"UTF-8");

							// Category/Style Display
							if ($_SESSION['prefsStyleSet'] == "AABC") $style = ltrim($row_scores['brewCategory'],"0").".".ltrim($row_scores['brewSubCategory'],"0");
	       					else $style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
	       					if ($_SESSION['style_set_no_numbering']) $style_display = $row_scores['brewStyle'];
							else $style_display = $style.": ".$row_scores['brewStyle'];

	       					// Name Display
							if ($_SESSION['prefsProEdition'] == 1) {
								if (empty($row_scores['brewerBreweryName'])) $brewer_name = $row_scores['brewerFirstName']." ".$row_scores['brewerLastName'];
								else $brewer_name = $row_scores['brewerBreweryName'];
							}
							else $brewer_name = $row_scores['brewerFirstName']." ".$row_scores['brewerLastName'];

							$brewer_club = "";
							if ((!empty($row_scores['brewerClubs'])) && ($row_scores['brewerClubs'] != "Other")) $brewer_club = $row_scores['brewerClubs'];

							// Build Slide Content
							$slides_tables .= "<div id=\"medal-grid\">";
							$slides_tables .= "<div class=\"fragment justify-right col-right\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."\"><i class=\"fa fa-trophy icon pos-".$place_heirarchy."-medal-color\"></i>".$display_place."</div>";
							$slides_tables .= "<div class=\"fragment justify-left\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-name\">";
							$slides_tables .= $brewer_name;
							if (!empty($row_scores['brewCoBrewer'])) $slides_tables .= "<span style=\"padding-top: .9em;\" class=\"small\">&nbsp;&amp;&nbsp;<em>".truncate_string($row_scores['brewCoBrewer'],20," ")."</em></span>";
							$slides_tables .= "</div>";
							if ($_SESSION['prefsProEdition'] == 0) $slides_tables .= "<div class=\"fragment justify-left small\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-club\">".truncate_string($brewer_club,25," ")."</div>";
							$slides_tables .= "<div class=\"fragment justify-left small entry-name bottom-row\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-style\">".truncate_string($entry_name,65," ")." (".$style_display.")</div>";
							$slides_tables .= "</div>";

						}

					}

					// If not, display "no winning entries" message
					else {
						$slides_tables .= "<p>".$winners_text_007."</p>";
					}
							
					$slides_tables .= "</section>\n";

					if (($go == "table-numbers") || ($go == "table-name-only") || ($go == "default")) {
						$order_by[] = array(
							'id' => $row_tables['tableNumber'],
							'table_name' => $row_tables['tableName'],
							'data' => $slides_tables
						);
					}

					if (($go == "table-entry-count-asc") || ($go == "table-entry-count-desc")) {
						$order_by[] = array(
							'id' => $entry_count,
							'table_name' => $row_tables['tableName'],
							'data' => $slides_tables
						);
					}

				}

			} // end if ($totalRows_tables > 0)

			$table_number = array_column($order_by, 'id');
			$table_name = array_column($order_by, 'table_name');

			if ($go == "table-entry-count-desc") array_multisort($table_number, SORT_DESC, $table_name, SORT_ASC, $order_by);
			else array_multisort($table_number, SORT_ASC, $table_name, SORT_ASC, $order_by);

			foreach ($order_by as $key => $value) {
				//echo $value['data'];
				$slides .= $value['data'];
			}

		} // end if ($_SESSION['prefsWinnerMethod'] == "0")

		// Build slides by Category
		if ($_SESSION['prefsWinnerMethod'] == "1") {

			/**
			 * Batch what used to be 2-3 queries per active category (includes/db/winners_category.db.php's
			 * entry/score counts, plus includes/db/scores.db.php's fetch) into 2 queries total, run once
			 * up front - same pattern as pub/winners_category.pub.php.
			 *
			 * Driver: read the admin-curated prefsSelectedStyles list (the source of truth for which
			 * styles are enabled - see admin/styles.admin.php) instead of a live styles_active() query.
			 * awards.php has no archive/$filter support (it's a live-only presentation), so unlike the
			 * pub/ files there's no archived-competition case to branch on here.
			 */

			$prefs_selected_styles = json_decode($_SESSION['prefsSelectedStyles'], true);
			$a = array();
			if (is_array($prefs_selected_styles)) {
				foreach ($prefs_selected_styles as $selected_style) {
					$a[] = $selected_style['brewStyleGroup'];
				}
			}

			$category_column = (style_set_no_numbering($style_set)) ? "brewCategory" : "brewCategorySort";

			$counts_by_category = array();
			if (table_exists($brewing_db_table)) {
				$db_conn->where('brewReceived', '1');
				$db_conn->groupBy($category_column);
				$rows_category_counts = $db_conn->get($brewing_db_table, null, "$category_column, COUNT(*) as count");
				foreach ($rows_category_counts as $row_cat_count) {
					$counts_by_category[$row_cat_count[$category_column]] = $row_cat_count['count'];
				}
			}

			// Mirrors includes/db/scores.db.php's winner_method==1 query exactly, just without
			// the per-category WHERE filter, then split back apart by category below.
			$scores_by_category = array();
			if ((table_exists($judging_scores_db_table)) && (table_exists($brewing_db_table)) && (table_exists($brewer_db_table))) {
				$query_scores_all_cat = "SELECT * FROM ".$judging_scores_db_table." a, ".$brewing_db_table." b, ".$brewer_db_table." c WHERE a.eid = b.id AND c.uid = b.brewBrewerID";
				if ((($action == "print") && ($view == "winners")) || ($action == "default") || ($section == "default")) $query_scores_all_cat .= " AND a.scorePlace > 0";
				$query_scores_all_cat .= " ORDER BY b.".$category_column;
				if ($action == "awards-pres") $query_scores_all_cat .= ", a.scorePlace DESC";
				else $query_scores_all_cat .= ", a.scorePlace ASC";
				$rows_scores_all_cat = $db_conn->rawQuery($query_scores_all_cat);
				foreach ($rows_scores_all_cat as $row_score_cat) {
					$scores_by_category[$row_score_cat[$category_column]][] = $row_score_cat;
				}
			}

			foreach (array_unique($a) as $style) {

				if (!empty($style)) {

					if ((isset($style)) && (is_numeric($style))) $style_pad_awards = sprintf("%02d", $style);
					else $style_pad_awards = $style;

					$lookup_key_awards = (style_set_no_numbering($style_set)) ? $style : $style_pad_awards;
					$row_entry_count = array('count' => $counts_by_category[$lookup_key_awards] ?? 0);
					$table_scores_awards = $scores_by_category[$lookup_key_awards] ?? array();
					// Deliberately counts only placed (scorePlace > 0) entries, unlike the old
					// includes/db/winners_category.db.php's row_score_count (which counted all
					// judged entries regardless of placement in this context). That mismatch let
					// categories with judged-but-unplaced entries render an empty announcement
					// slide (a header + entry count, no winners). Confirmed with the maintainer:
					// skip those categories entirely instead.
					$row_score_count = array('count' => count($table_scores_awards));

					if ($row_entry_count['count'] > 1) $entries_display = strtolower($label_entries);
					else $entries_display = strtolower($label_entry);

					if ($row_score_count['count'] > 0) {

						// Pulled from the batched fetch above instead of a fresh
						// include(DB.'scores.db.php') query per category.
						$rows_scores = $table_scores_awards;
						$totalRows_scores = count($rows_scores);

						$slides .= "<section>";

						if ($_SESSION['style_set_no_numbering']) {

							include (INCLUDES.'ba_constants.inc.php');
							$slides .= "<h1 class=\"r-fit-text tight\">";
							$slides .= $ba_category_names[$style];
							$slides .= "</h1>";

						} // end if ($_SESSION['style_set_no_numbering'])

						else {

							$slides .= "<h1 class=\"r-fit-text tight\">";
							$slides .= sprintf("%s %s: %s",$label_category,ltrim($style,"0"),style_convert($style,"1",$base_url));
							$slides .= "</h1>";

						}

						$slides .= "<p class=\"entry-count\">";
						$slides .= sprintf("%s %s",$row_entry_count['count'],$entries_display);
						$slides .= "</p>";

						if ($totalRows_scores > 0) {

							foreach ($rows_scores as $row_scores) {

								$place_heirarchy = place_heirarchy($row_scores['scorePlace']);
								$display_place = display_place($row_scores['scorePlace'],1);

								$entry_name = html_entity_decode($row_scores['brewName'], ENT_QUOTES | ENT_XML1, 'UTF-8');
								$entry_name = htmlentities($entry_name,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5,"UTF-8");

								// Category/Style Display
								if ($_SESSION['prefsStyleSet'] == "AABC") $style = ltrim($row_scores['brewCategory'],"0").".".ltrim($row_scores['brewSubCategory'],"0");
		       					else $style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
		       					if ($_SESSION['style_set_no_numbering']) $style_display = $row_scores['brewStyle'];
								else $style_display = $style.": ".$row_scores['brewStyle'];

		       					// Name Display
								if ($_SESSION['prefsProEdition'] == 1) $brewer_name = $row_scores['brewerBreweryName'];
								else $brewer_name = $row_scores['brewerFirstName']." ".$row_scores['brewerLastName'];

								$brewer_club = "";
								if ((!empty($row_scores['brewerClubs'])) && ($row_scores['brewerClubs'] != "Other")) $brewer_club = $row_scores['brewerClubs'];

								// Build Slide Content
								$slides .= "<div id=\"medal-grid\">";
								$slides .= "<div class=\"fragment justify-right col-right\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."\"><i class=\"fa fa-trophy icon pos-".$place_heirarchy."-medal-color\"></i>".$display_place."</div>";
								$slides .= "<div class=\"fragment justify-left\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-name\">";
								$slides .= $brewer_name;
								if (!empty($row_scores['brewCoBrewer'])) $slides .= "<span style=\"padding-top: .9em;\" class=\"small\">&nbsp;&amp;&nbsp;<em>".truncate_string($row_scores['brewCoBrewer'],20," ")."</em></span>";
								$slides .= "</div>";
								
								if ($_SESSION['prefsProEdition'] == 0) $slides .= "<div class=\"fragment justify-left small\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-club\">".truncate_string($brewer_club,25," ")."</div>";
								$slides .= "<div class=\"fragment justify-left small entry-name bottom-row\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-style\">".truncate_string($entry_name,65," ")." (".$style_display.")</div>";
								$slides .= "</div>";

							}

						}

						// If not, display "no winning entries" message
						else {
							$slides .= "<p>".$winners_text_007."</p>";
						}

						$slides .= "</section>\n";

					} // end if ($row_score_count['count'] > 0)

				} // end if (!empty($style))

			}

		} // end if ($_SESSION['prefsWinnerMethod'] == "1")

		// Build slides by Sub-Category
		if ($_SESSION['prefsWinnerMethod'] == "2") {

			$category_end = $_SESSION['style_set_category_end'];

			/**
			 * Driver: read the admin-curated prefsSelectedStyles list (the source of truth for which
			 * styles are enabled - see admin/styles.admin.php) instead of a live styles_active() query.
			 * awards.php has no archive/$filter support (it's a live-only presentation). Produces the
			 * same "group^num^style" string shape styles_active(2,...) always returned, so the rest of
			 * this block (the explode("^",...) driven loop below) is unchanged.
			 */

			$prefs_selected_styles = json_decode($_SESSION['prefsSelectedStyles'], true);
			$a = array();
			if (is_array($prefs_selected_styles)) {
				foreach ($prefs_selected_styles as $selected_style) {
					$a[] = $selected_style['brewStyleGroup'].'^'.$selected_style['brewStyleNum'].'^'.$selected_style['brewStyle'];
				}
			}

			/**
			 * Batch what used to be 2-3 queries per active subcategory (includes/db/winners_subcategory.db.php's
			 * entry/score counts, plus includes/db/scores.db.php's fetch) into 2 queries total, run once
			 * up front - same pattern as pub/winners_subcategory.pub.php. This also fixes a pre-existing
			 * bug: the old code below included winners_subcategory.db.php without ever setting
			 * $value['brewStyleGroup']/$value['brewStyleNum'], which that file's WHERE clause depends on -
			 * $value held whatever unrelated value it last had (observed live as a stray int), so every
			 * lookup silently matched nothing and no subcategory winners were ever displayed.
			 */

			$category_column_sub = (style_set_no_numbering($style_set)) ? "brewCategory" : "brewCategorySort";

			// Entry counts, keyed by "category|subcategory" - matches winners_subcategory.db.php's
			// composite WHERE (category_column + brewSubCategory) used for both style sets (BA
			// additionally filters brewReceived=1 there, preserved below).
			$counts_by_subcategory = array();
			if (table_exists($brewing_db_table)) {
				if (style_set_no_numbering($style_set)) $db_conn->where('brewReceived', '1');
				$db_conn->groupBy($category_column_sub);
				$db_conn->groupBy('brewSubCategory');
				$rows_subcat_counts = $db_conn->get($brewing_db_table, null, "$category_column_sub, brewSubCategory, COUNT(*) as count");
				foreach ($rows_subcat_counts as $row_subcat_count) {
					$counts_by_subcategory[$row_subcat_count[$category_column_sub].'|'.$row_subcat_count['brewSubCategory']] = $row_subcat_count['count'];
				}
			}

			// Mirrors includes/db/scores.db.php's winner_method==2 query exactly - including its
			// BA quirk of filtering only brewSubCategory, not category+subcategory together - just
			// without the per-subcategory WHERE filter, then split back apart below. BA2026's
			// brewStyleNum was made globally unique to match old BA's own flat numbering scheme
			// (see the BA2026 "num-uniqueness" work), so grouping by the bare brewSubCategory key
			// below is safe for both.
			$scores_by_subcategory = array();
			if ((table_exists($judging_scores_db_table)) && (table_exists($brewing_db_table)) && (table_exists($brewer_db_table))) {
				$query_scores_all_subcat = "SELECT * FROM ".$judging_scores_db_table." a, ".$brewing_db_table." b, ".$brewer_db_table." c WHERE a.eid = b.id AND c.uid = b.brewBrewerID";
				if ((($action == "print") && ($view == "winners")) || ($action == "default") || ($section == "default")) $query_scores_all_subcat .= " AND a.scorePlace > 0";
				$query_scores_all_subcat .= " ORDER BY b.brewSubCategory";
				if ($action == "awards-pres") $query_scores_all_subcat .= ", a.scorePlace DESC";
				else $query_scores_all_subcat .= ", a.scorePlace ASC";
				$rows_scores_all_subcat = $db_conn->rawQuery($query_scores_all_subcat);
				foreach ($rows_scores_all_subcat as $row_score_subcat) {
					if (style_set_no_numbering($style_set)) $group_key_sub = $row_score_subcat['brewSubCategory'];
					else $group_key_sub = $row_score_subcat['brewCategorySort'].'|'.$row_score_subcat['brewSubCategory'];
					$scores_by_subcategory[$group_key_sub][] = $row_score_subcat;
				}
			}

			foreach (array_unique($a) as $style) {

				$style = explode("^",$style);
				$value['brewStyleGroup'] = $style[0];
				$value['brewStyleNum'] = $style[1];

				$row_entry_count = array('count' => $counts_by_subcategory[$value['brewStyleGroup'].'|'.$value['brewStyleNum']] ?? 0);

				if (style_set_no_numbering($style_set)) $score_key_sub = $value['brewStyleNum'];
				else $score_key_sub = $value['brewStyleGroup'].'|'.$value['brewStyleNum'];
				$table_scores_sub_awards = $scores_by_subcategory[$score_key_sub] ?? array();
				$row_score_count = array('count' => count($table_scores_sub_awards));

				// Display all winners
				if ($row_entry_count['count'] > 0) {

					if ($row_entry_count['count'] > 1) $entries_display = "entries";
					else $entries_display = "entry";

					if ($row_score_count['count'] > 0) {

						// Pulled from the batched fetch above instead of a fresh
						// include(DB.'scores.db.php') query per subcategory.
						$rows_scores = $table_scores_sub_awards;
						$totalRows_scores = count($rows_scores);

						$slides .= "<section>";

						if ($_SESSION['style_set_no_numbering']) {

							$slides .= "<h1 class=\"r-fit-text tight\">";
							$slides .= $style[2];
							$slides .= "</h1>";

						} // end if ($_SESSION['style_set_no_numbering'])

						else {

							$slides .= "<h1 class=\"r-fit-text tight\">";
							$slides .= sprintf("%s %s%s: %s",$label_category,ltrim($style[0],"0"),$style[1],$style[2]);
							$slides .= "</h1>";

						}

						$slides .= "<p class=\"entry-count\">";
						$slides .= sprintf("%s %s",$row_entry_count['count'],$entries_display);
						$slides .= "</p>";

						if ($totalRows_scores > 0) {

							foreach ($rows_scores as $row_scores) {

								$place_heirarchy = place_heirarchy($row_scores['scorePlace']);
								$display_place = display_place($row_scores['scorePlace'],1);

								$entry_name = html_entity_decode($row_scores['brewName'], ENT_QUOTES | ENT_XML1, 'UTF-8');
								$entry_name = htmlentities($entry_name,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5,"UTF-8");

								// Category/Style Display
								if ($_SESSION['prefsStyleSet'] == "AABC") $style = ltrim($row_scores['brewCategory'],"0").".".ltrim($row_scores['brewSubCategory'],"0");
		       					else $style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
		       					if ($_SESSION['style_set_no_numbering']) $style_display = $row_scores['brewStyle'];
								else $style_display = $style.": ".$row_scores['brewStyle'];

		       					// Name Display
								if ($_SESSION['prefsProEdition'] == 1) $brewer_name = $row_scores['brewerBreweryName'];
								else $brewer_name = $row_scores['brewerFirstName']." ".$row_scores['brewerLastName'];

								$brewer_club = "";
								if ((!empty($row_scores['brewerClubs'])) && ($row_scores['brewerClubs'] != "Other")) $brewer_club = $row_scores['brewerClubs'];

								// Build Slide Content
								$slides .= "<div id=\"medal-grid\">";
								$slides .= "<div class=\"fragment justify-right col-right\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."\"><i class=\"fa fa-trophy icon pos-".$place_heirarchy."-medal-color\"></i>".$display_place."</div>";
								$slides .= "<div class=\"fragment justify-left\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-name\">";
								$slides .= $brewer_name;
								if (!empty($row_scores['brewCoBrewer'])) $slides .= "<span style=\"padding-top: .9em;\" class=\"small\">&nbsp;&amp;&nbsp;<em>".truncate_string($row_scores['brewCoBrewer'],20," ")."</em></span>";
								$slides .= "</div>";

								if ($_SESSION['prefsProEdition'] == 0) $slides .= "<div class=\"fragment justify-left small\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-club\">".truncate_string($brewer_club,25," ")."</div>";
								$slides .= "<div class=\"fragment justify-left small entry-name bottom-row\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-style\">".truncate_string($entry_name,65," ")." (".$style_display.")</div>";
								$slides .= "</div>";

							}

						}

						// If not, display "no winning entries" message
						else {
							$slides .= "<p>".$winners_text_007."</p>";
						}

						$slides .= "</section>\n";

					} // end if ($row_score_count['count'] > 0)

				} // end if ($row_entry_count['count'] > 0)

			} // end foreach (array_unique($a) as $style)

		} // end if ($_SESSION['prefsWinnerMethod'] == "2")

	} // end if ($row_scored_entries['count'] > 0)

	/**
	 * Best of Show
	 * Need to display combined Mead/Cider
	 */

	$display_bos_style_type = FALSE;

	foreach ($rows_style_types as $row_style_types) {
		$st[] = $row_style_types['id'];
	}

	sort($st);

	// print_r($st);

	foreach ($st as $type) {

		include (DB.'output_results_download_bos.db.php');

		if ($totalRows_bos > 0) {

			$display_bos_style_type = TRUE;

			$slides_bos .= "<section>";

			$slides_bos .= "<h1 class=\"r-fit-text tight\">";
			$slides_bos .= $label_bos;
			$slides_bos .= "</h1>";
			$slides_bos .= "<h3 class=\"entry-count\">";
			$slides_bos .= $row_style_type_1['styleTypeName'];
			$slides_bos .= "</h3>";
			if (!empty($judge_bos)) $slides_bos .= sprintf("<p class=\"small entry-count\">%s: %s</p>",$label_judges,rtrim($judge_bos,", "));

			foreach ($rows_bos as $row_bos) {

				$place_heirarchy = place_heirarchy($row_bos['scorePlace']);
				$display_place = display_place($row_bos['scorePlace'],1);

				$entry_name = html_entity_decode($row_bos['brewName'], ENT_QUOTES | ENT_XML1, 'UTF-8');
				$entry_name = htmlentities($entry_name,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5,"UTF-8");

				// Category/Style Display
				if ($_SESSION['prefsStyleSet'] == "AABC") $style = ltrim(h($row_bos['brewCategory']),"0").".".ltrim(h($row_bos['brewSubCategory']),"0");
					else $style = h($row_bos['brewCategory']).h($row_bos['brewSubCategory']);
					if ($_SESSION['style_set_no_numbering']) $style_display = h($row_bos['brewStyle']);
				else $style_display = $style.": ".h($row_bos['brewStyle']);

					// Name Display
				if ($_SESSION['prefsProEdition'] == 1) $brewer_name = $row_bos['brewerBreweryName'];
				else $brewer_name = $row_bos['brewerFirstName']." ".$row_bos['brewerLastName'];

				$brewer_club = "";
				if ((!empty($row_bos['brewerClubs'])) && ($row_bos['brewerClubs'] != "Other")) $brewer_club = h($row_bos['brewerClubs']);

				// Build Slide Content
				$slides_bos .= "<div id=\"medal-grid\">";
				$slides_bos .= "<div class=\"fragment justify-right col-right\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."\"><i class=\"fa fa-trophy icon pos-".$place_heirarchy."-medal-color\"></i>".$display_place."</div>";
				$slides_bos .= "<div class=\"fragment justify-left\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-name\">";
				$slides_bos .= $brewer_name;
				if (!empty($row_bos['brewCoBrewer'])) $slides_bos .= "<span style=\"padding-top: .9em;\" class=\"small\">&nbsp;&amp;&nbsp;<em>".truncate_string(h($row_bos['brewCoBrewer']),20," ")."</em></span>";
				$slides_bos .= "</div>";

				if ($_SESSION['prefsProEdition'] == 0) $slides_bos .= "<div class=\"fragment justify-left small\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-club\">".truncate_string($brewer_club,25," ")."</div>";
				$slides_bos .= "<div class=\"fragment justify-left small entry-name bottom-row\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-style\">".truncate_string($entry_name,65," ")." (".$style_display.")</div>";
				$slides_bos .= "</div>";

			}

			$slides_bos .= "</section>\n";

		}

	} // end foreach ($a as $type)

	/**
	 * Special/Custom "Best of"
	 */

	if ($totalRows_sbi > 0) {

		foreach ($rows_sbi as $row_sbi) {

			include (DB.'output_results_download_sbd.db.php');
				
				if ($totalRows_sbd > 0) {

					$slides_bos .= "<section>";

					$slides_bos .= "<h1 class=\"r-fit-text tight\">";
					$slides_bos .= $row_sbi['sbi_name'];
					$slides_bos .= "</h1>";

					$place_heirarchy_count = 0;

					foreach ($rows_sbd as $row_sbd) {

						$place_heirarchy_count += 1;
						$display_place = "";

						if (($row_sbi['sbi_display_places'] == "1") && (!empty($row_sbd['sbd_place']))) {
							$place_heirarchy = place_heirarchy($row_sbd['sbd_place']);
							$display_place = display_place($row_sbd['sbd_place'],1);
						}
						
						else {
							$place_heirarchy = place_heirarchy($place_heirarchy_count);
						}

						$entry_name = html_entity_decode($row_sbd['brewName'],ENT_QUOTES|ENT_XML1,"UTF-8");
						$entry_name = htmlentities($entry_name,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5,"UTF-8");
						
						// Category/Style Display
						if ($_SESSION['prefsStyleSet'] == "AABC") $style = ltrim(h($row_sbd['brewerCategory']),"0").".".ltrim(h($row_sbd['brewSubCategory']),"0");
							else $style = h($row_sbd['brewCategory']).h($row_sbd['brewSubCategory']);
						if ($_SESSION['style_set_no_numbering']) $style_display = h($row_sbd['brewStyle']);
						else $style_display = h($row_sbd['brewCategory']).h($row_sbd['brewSubCategory']).": ".h($row_sbd['brewStyle']);

							// Name Display
						if ($_SESSION['prefsProEdition'] == 1) $brewer_name = $row_sbd['brewerBreweryName'];
						else $brewer_name = $row_sbd['brewerFirstName']." ".$row_sbd['brewerLastName'];

						$brewer_club = "";
						if ((!empty($row_sbd['brewerClubs'])) && ($row_sbd['brewerClubs'] != "Other")) $brewer_club = h($row_sbd['brewerClubs']);

						// Build Slide Content
						$slides_bos .= "<div id=\"medal-grid\">";
						$slides_bos .= "<div class=\"fragment justify-right col-right\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."\"><i class=\"fa fa-trophy icon pos-".$place_heirarchy."-medal-color\"></i>".$display_place."</div>";
						$slides_bos .= "<div class=\"fragment justify-left\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-name\">".$brewer_name."</div>";
						if ($_SESSION['prefsProEdition'] == 0) $slides_bos .= "<div class=\"fragment justify-left small\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-club\">".truncate_string($brewer_club,25," ")."</div>";
						$slides_bos .= "<div class=\"fragment justify-left small entry-name bottom-row\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-style\">".truncate_string($entry_name,65," ")." (".$style_display.")</div>";
						$slides_bos .= "</div>";

					}

					$slides_bos .= "</section>\n";

				}

		}

	}

	/**
	 * Best Brewer / Best Club *
	 */


	if (($row_limits['prefsShowBestBrewer'] != 0) || ($row_limits['prefsShowBestClub'] != 0)) {
		
		$bestbrewer = array();
		$bestbrewer_clubs = array();

		include(DB.'scores_bestbrewer.db.php');

		if ($bb_totalRows_scores > 0) {

			// Loop through brewing table for preliminary round scores
			foreach ($rows_bb_scores as $bb_row_scores) {

				$place = floor($bb_row_scores['scorePlace']);
				$club_name = normalizeClubs($bb_row_scores['brewerClubs']);

				if (array_key_exists($bb_row_scores['uid'], $bestbrewer)) {

					if ($row_bb_prefs['prefsScoringCOA'] == 1) {

						// Get table number and place at table
						if ($row_bb_prefs['prefsWinnerMethod'] == 0) {

							if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer[$bb_row_scores['uid']]['Places-data'][$bb_row_scores['scoreTable']] = $place;

						}

						else {

							if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer[$bb_row_scores['uid']]['Places-data'][$bb_row_scores['brewCategorySort']] = $place;

						}


					}


					if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer[$bb_row_scores['uid']]['Places'][$place-1] += 1;

					$bestbrewer[$bb_row_scores['uid']]['Scores'][] = $bb_row_scores['scoreEntry'];
					
					// Compile separate vars for clubs
					if (!empty($bb_row_scores['brewerClubs'])) {

						if (array_key_exists($club_name, $bestbrewer_clubs)) {

							if ($row_bb_prefs['prefsScoringCOA'] == 1) {

								// Get table number and place at table
								if ($row_bb_prefs['prefsWinnerMethod'] == 0) {

									if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places-data'][$bb_row_scores['scoreTable']] = $place;

								}

								else {

									if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places-data'][$bb_row_scores['brewCategorySort']] = $place;

								}

							}

							if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places'][$place-1] += 1;
							$bestbrewer_clubs[$club_name]['Scores'][] = $bb_row_scores['scoreEntry'];
						
						}

						else {
							
							if ($row_bb_prefs['prefsScoringCOA'] == 1) {

								$bestbrewer_clubs[$club_name]['Places-data'] = array();

								// Get table number and place at table
								if ($row_bb_prefs['prefsWinnerMethod'] == 0) {

									if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places-data'][$bb_row_scores['scoreTable']] = $place;

								}

								elseif ($row_bb_prefs['prefsWinnerMethod'] == 1) {

									if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places-data'][$bb_row_scores['brewCategorySort']] = $place;

								}

								elseif ($row_bb_prefs['prefsWinnerMethod'] == 2) {

									if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) {
										$substyle = $bb_row_scores['brewCategorySort']."-".$bb_row_scores['brewSubCategory'];
										$bestbrewer_clubs[$club_name]['Places-data'][$substyle] = $place;
									}
									

								}

							}

							$bestbrewer_clubs[$club_name]['Places'] = array(0,0,0,0,0);
							if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places'][$place-1] = 1;

							$bestbrewer_clubs[$club_name]['Scores'] = array();
							$bestbrewer_clubs[$club_name]['Scores'][] = $bb_row_scores['scoreEntry'];
							$bestbrewer_clubs[$club_name]['Clubs'] = $bb_row_scores['brewerClubs'];
						
						}

					} // end clubs
					
				}

				else {
					
					if ($_SESSION['prefsProEdition'] == 1) $bestbrewer[$bb_row_scores['uid']]['Name'] = $bb_row_scores['brewerBreweryName'];
					
					if ($_SESSION['prefsProEdition'] == 0) {
						$bestbrewer[$bb_row_scores['uid']]['Name'] = $bb_row_scores['brewerFirstName']." ".$bb_row_scores['brewerLastName'];
					}

					if ($_SESSION['prefsProEdition'] == 0) $bestbrewer[$bb_row_scores['uid']]['Clubs'] = $bb_row_scores['brewerClubs'];

					$bestbrewer[$bb_row_scores['uid']]['Places'] = array(0,0,0,0,0);
					$bestbrewer[$bb_row_scores['uid']]['Scores'] = array();
					$bestbrewer[$bb_row_scores['uid']]['TypeBOS'] = array();

					if ($row_bb_prefs['prefsScoringCOA'] == 1) {

						$bestbrewer[$bb_row_scores['uid']]['Places-data'] = array();

						// Get table number and place at table
						if ($row_bb_prefs['prefsWinnerMethod'] == 0) {

							if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer[$bb_row_scores['uid']]['Places-data'][$bb_row_scores['scoreTable']] = $place;

						}

						else {

							if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer[$bb_row_scores['uid']]['Places-data'][$bb_row_scores['brewCategorySort']] = $place;

						}

					}

					if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer[$bb_row_scores['uid']]['Places'][$place-1] = 1;

					$bestbrewer[$bb_row_scores['uid']]['Scores'][0] = $bb_row_scores['scoreEntry'];
					
					// Compile separate vars for clubs
					if (!empty($bb_row_scores['brewerClubs'])) {

						if (array_key_exists($club_name, $bestbrewer_clubs)) {

							if ($row_bb_prefs['prefsScoringCOA'] == 1) {

								// Get table number and place at table
								if ($row_bb_prefs['prefsWinnerMethod'] == 0) {

									if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places-data'][$bb_row_scores['scoreTable']] = $place;

								}

								else {

									if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places-data'][$bb_row_scores['brewCategorySort']] = $place;

								}

							}

							if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places'][$place-1] += 1;
							$bestbrewer_clubs[$club_name]['Scores'][] = $bb_row_scores['scoreEntry'];
						
						}

						else {
							
							if ($row_bb_prefs['prefsScoringCOA'] == 1) {

								$bestbrewer_clubs[$club_name]['Places-data'] = array();

								// Get table number and place at table
								if ($row_bb_prefs['prefsWinnerMethod'] == 0) {

									if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places-data'][$bb_row_scores['scoreTable']] = $place;

								}

								else {

									if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places-data'][$bb_row_scores['brewCategorySort']] = $place;

								}

							}

							$bestbrewer_clubs[$club_name]['Places'] = array(0,0,0,0,0);
							if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places'][$place-1] = 1;

							$bestbrewer_clubs[$club_name]['Scores'] = array();
							$bestbrewer_clubs[$club_name]['Scores'][] = $bb_row_scores['scoreEntry'];
							$bestbrewer_clubs[$club_name]['Clubs'] = $bb_row_scores['brewerClubs'];
						
						}

					} // end clubs
					
				}

			}

		}

		// BOS - do calcs only if pref is true
		if ($row_bb_prefs['prefsBestUseBOS'] == 1) {

			if ($rows_bb_bos_scores) {

				foreach ($rows_bb_bos_scores as $bb_row_bos_scores) {

					$club_name = normalizeClubs($bb_row_bos_scores['brewerClubs']);

					if (array_key_exists($bb_row_bos_scores['uid'], $bestbrewer)) {
						
						$place = floor($bb_row_bos_scores['scorePlace']);
						if (($place == $bb_row_bos_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer[$bb_row_bos_scores['uid']]['Places'][$place-1] += 1;
						$bestbrewer[$bb_row_bos_scores['uid']]['Scores'][] = $bb_row_bos_scores['scoreEntry'];
						$bestbrewer[$bb_row_bos_scores['uid']]['TypeBOS'][] = 1;

						// -- Compile separate vars for clubs --
						if (!empty($bb_row_bos_scores['brewerClubs'])) {

							if (array_key_exists($club_name, $bestbrewer_clubs)) {
								if (($place == $bb_row_bos_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places'][$place-1] += 1;
								$bestbrewer_clubs[$club_name]['Scores'][] = $bb_row_bos_scores['scoreEntry'];
							}

							else {
								$bestbrewer_clubs[$club_name]['Places'] = array(0,0,0,0,0);
								if (($place == $bb_row_bos_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places'][$place-1] = 1;
								$bestbrewer_clubs[$club_name]['Scores'] = array();
								$bestbrewer_clubs[$club_name]['Scores'][] = $bb_row_bos_scores['scoreEntry'];
								$bestbrewer_clubs[$club_name]['Clubs'] = $bb_row_bos_scores['brewerClubs'];
							}

						}
						// -- end clubs --
					}

					else {
						$bestbrewer[$bb_row_bos_scores['uid']]['Places'] = array(0,0,0,0,0);
						$bestbrewer[$bb_row_bos_scores['uid']]['TypeBOS'] = array();
						$bestbrewer[$bb_row_bos_scores['uid']]['Scores'] = array();

						$place = floor($bb_row_bos_scores['scorePlace']);
						if (($place == $bb_row_bos_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer[$bb_row_bos_scores['uid']]['Places'][$place-1] = 1;
						$bestbrewer[$bb_row_bos_scores['uid']]['Scores'][0] = $bb_row_bos_scores['scoreEntry'];
						$bestbrewer[$bb_row_bos_scores['uid']]['TypeBOS'][0] = 1;

						// -- Compile separate vars for clubs --
						if (!empty($bb_row_bos_scores['brewerClubs'])) {

							if (array_key_exists($club_name, $bestbrewer_clubs)) {
								if (($place == $bb_row_bos_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places'][$place-1] += 1;
								$bestbrewer_clubs[$club_name]['Scores'][] = $bb_row_bos_scores['scoreEntry'];
							}

							else {
								$bestbrewer_clubs[$club_name]['Places'] = array(0,0,0,0,0);
								if (($place == $bb_row_bos_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places'][$place-1] = 1;
								$bestbrewer_clubs[$club_name]['Scores'] = array();
								$bestbrewer_clubs[$club_name]['Scores'][] = $bb_row_bos_scores['scoreEntry'];
								$bestbrewer_clubs[$club_name]['Clubs'] = $bb_row_bos_scores['brewerClubs'];
							}

						}
						// -- end clubs --

					}

				}

			}

		}

		if (($row_limits['prefsShowBestBrewer'] != 0) && (!empty($bestbrewer))) {

			$bb_sorter = array();

			foreach (array_keys($bestbrewer) as $key) {
				
				if ($row_bb_prefs['prefsScoringCOA'] == 1) $points = best_brewer_points($key,$bestbrewer[$key]['Places-data'],$bestbrewer[$key]['Scores'],$bb_points_prefs,$bb_tiebreaker_prefs,1);
				else $points = best_brewer_points($key,$bestbrewer[$key]['Places'],$bestbrewer[$key]['Scores'],$bb_points_prefs,$bb_tiebreaker_prefs,0);
				$bestbrewer[$key]['Points'] = $points;
				$bb_sorter[$key] = $points;
			
			}

			arsort($bb_sorter);

			$show_4th = FALSE;
			$show_HM = FALSE;
			
			if ($row_limits['prefsShowBestBrewer'] == -1) $bb_max_position = count(array_keys($bb_sorter));
			else $bb_max_position = $row_limits['prefsShowBestBrewer'];

			foreach (array_keys($bb_sorter) as $key) {
				$bb_count += 1;
				$points = $bestbrewer[$key]['Points'];
				if ($points != $bb_previouspoints) {
					$bb_position = $bb_count;
					$bb_previouspoints = $points;
				}
				if ($bb_position <= $bb_max_position) {
					if ($bestbrewer[$key]['Places'][3] > 0) $show_4th = TRUE;
					if ($bestbrewer[$key]['Places'][4] > 0) $show_HM = TRUE;
				}
				else break;
			}

			$bb_count = 0;
			$bb_position = 0;
			$bb_previouspoints = 0;

			foreach (array_keys($bb_sorter) as $key) {
				
				$bb_count += 1;
				$points = $bestbrewer[$key]['Points'];
				
				if ($points != $bb_previouspoints) {
					$bb_position = $bb_count;
					$bb_previouspoints = $points;
					$bb_display_position = display_place($bb_position,4);
					$place_heirarchy = place_heirarchy($bb_count);
				}

				else $bb_display_position = "";
				
				if ($bb_position <= $bb_max_position) {
					$table_body1 .= "<tr class=\"fragment\" data-fragment-index=\"".$place_heirarchy."\" >";
					$table_body1 .= "<td class=\"no-bottom-border\" width=\"1%\" nowrap><a name=\"".$points."\"></a>".$bb_display_position."</td>";
					$table_body1 .= "<td class=\"no-bottom-border\" width=\"25%\">".$bestbrewer[$key]['Name']."</td>";
					$table_body1 .= "<td class=\"no-bottom-border\" width=\"5%\" nowrap>".$bestbrewer[$key]['Places'][0]."</td>";
					$table_body1 .= "<td class=\"no-bottom-border\" width=\"5%\" nowrap>".$bestbrewer[$key]['Places'][1]."</td>";
					$table_body1 .= "<td class=\"no-bottom-border\" width=\"5%\" nowrap>".$bestbrewer[$key]['Places'][2]."</td>";
					if ($show_4th) $table_body1 .= "<td class=\"no-bottom-border\" width=\"5%\" nowrap>".$bestbrewer[$key]['Places'][3]."</td>";
					if ($show_HM) $table_body1 .= "<td class=\"no-bottom-border\" width=\"5%\" nowrap>".$bestbrewer[$key]['Places'][4]."</td>";
					$table_body1 .= "<td align=\"right\" class=\"no-bottom-border\" width=\"5%\" nowrap>";
					$table_body1 .= number_format($points,7);
					$table_body1 .= "</td>";
					if ($_SESSION['prefsProEdition'] == 0) $table_body1 .= "<td class=\"no-bottom-border\">".truncate_string($bestbrewer[$key]['Clubs'],20," ")."</td>";
					$table_body1 .= "</tr>";
				}

				else break;
			}

			// Build best brewer table headers
			$table_head1 .= "<tr>";
			$table_head1 .= sprintf("<th nowrap>%s</th>",$label_place);
			$table_head1 .= sprintf("<th>%s</th>",$label_brewer);
			$table_head1 .= "<th>".addOrdinalNumberSuffix(1)."</th>";
			$table_head1 .= "<th>".addOrdinalNumberSuffix(2)."</th>";
			$table_head1 .= "<th>".addOrdinalNumberSuffix(3)."</th>";
			if ($show_4th) $table_head1 .= "<th>".addOrdinalNumberSuffix(4)."</th>";
			if ($show_HM) $table_head1 .= sprintf("<th>%s</th>",$best_brewer_text_001);
			$table_head1 .= sprintf("<th nowrap>%s</th>",$label_score);
			if ($_SESSION['prefsProEdition'] == 0) $table_head1 .= sprintf("<th>%s</th>",$label_club);

			// Display
			$slides_bos .= "<section>";
			
			$slides_bos .= "<h1 class=\"r-fit-text tight\">".$row_bb_prefs['prefsBestBrewerTitle']."</h1>";

			$slides_bos .= "<p class=\"entry-count\">";
			$slides_bos .= sprintf(" %s %s <span class=\"small entry-count\">[<a data-fancybox data-src=\"#scoring-method\" href=\"javascript:;\">%s</a>]</span>", get_participant_count('received-entrant'), ucwords($best_brewer_text_000), $best_brewer_text_003);
			$slides_bos .= "</p>";

			$slides_bos .= "<table style=\"width: 100%; font-size: .55em;\">";
			$slides_bos .= "<thead>";
			$slides_bos .= $table_head1;
			$slides_bos .= "</thead>";
			$slides_bos .= "<tbody>";
			$slides_bos .= $table_body1;
			$slides_bos .= "</tbody>";
			$slides_bos .= "</table>";
			
			$slides_bos .= "</section>\n";

		} // end if ($row_limits['prefsShowBestBrewer'] != 0)

		if (($_SESSION['prefsProEdition'] == 0) && ($row_limits['prefsShowBestClub'] != 0) && (!empty($bestbrewer_clubs))) {

			$bb_sorter_clubs = array();

			// Compile the Best Club points
			foreach (array_keys($bestbrewer_clubs) as $key) {
				if ($row_bb_prefs['prefsScoringCOA'] == 1) $points_clubs = best_brewer_points($key,$bestbrewer_clubs[$key]['Places-data'],$bestbrewer_clubs[$key]['Scores'],$bb_points_prefs,$bb_tiebreaker_prefs,1);
				else $points_clubs = best_brewer_points($key,$bestbrewer_clubs[$key]['Places'],$bestbrewer_clubs[$key]['Scores'],$bb_points_prefs,$bb_tiebreaker_prefs,0);

				$bestbrewer_clubs[$key]['Points'] = $points_clubs;
				$bb_sorter_clubs[$key] = $points_clubs;
			}

			arsort($bb_sorter_clubs);

			$show_4th_clubs = FALSE;
			$show_HM_clubs = FALSE;
			$bb_count_clubs = 0;
			$bb_position_clubs = 0;
			$bb_previouspoints_clubs = 0;
			if ($row_limits['prefsShowBestClub'] == -1) $bb_max_position_clubs = count(array_keys($bb_sorter_clubs));
			else $bb_max_position_clubs = $row_limits['prefsShowBestClub'];

			foreach (array_keys($bb_sorter_clubs) as $key) {
				$bb_count_clubs += 1;
				$points_clubs = $bestbrewer_clubs[$key]['Points'];
				if ($points_clubs != $bb_previouspoints_clubs) {
					$bb_position_clubs = $bb_count_clubs;
					$bb_previouspoints_clubs = $points_clubs;
				}
				if ($bb_position_clubs <= $bb_max_position_clubs) {
					if ($bestbrewer_clubs[$key]['Places'][3] > 0) $show_4th_clubs = TRUE;
					if ($bestbrewer_clubs[$key]['Places'][4] > 0) $show_HM_clubs = TRUE;
				}

				else break;
			}

			// Build clubs table body
			$bb_count_clubs = 0;
			$bb_position_clubs = 0;
			$bb_previouspoints_clubs = 0;

			foreach (array_keys($bb_sorter_clubs) as $key) {

				$points_clubs = $bestbrewer_clubs[$key]['Points'];
				$output .= $bestbrewer_clubs[$key]['Clubs']." - ".$points_clubs. " - Places... ";
				$output .= "1: ".$bestbrewer_clubs[$key]['Places'][0]." ";
				$output .= "2: ".$bestbrewer_clubs[$key]['Places'][1]." ";
				$output .= "3: ".$bestbrewer_clubs[$key]['Places'][2]." ";
				if ($show_4th_clubs) $output .= "4: ".$bestbrewer_clubs[$key]['Places'][3]." ";
				if ($show_HM_clubs) $output .= "HM: ".$bestbrewer_clubs[$key]['Places'][4]." ";
				$output .= "<br>";

				$bb_count_clubs += 1;
				$points_clubs = $bestbrewer_clubs[$key]['Points'];

				if ($points_clubs != $bb_previouspoints_clubs) {
					$bb_position_clubs = $bb_count_clubs;
					$bb_previouspoints_clubs = $points_clubs;
					$bb_display_position_clubs = display_place($bb_position_clubs,4);
					$place_heirarchy = place_heirarchy($bb_count_clubs);
				}

				else $bb_display_position_clubs = "";

				if ($bb_position_clubs <= $bb_max_position_clubs) {

					// Build club points table body
					$table_body2 .= "<tr class=\"fragment\" data-fragment-index=\"".$place_heirarchy."\" >";
					$table_body2 .= "<td class=\"no-bottom-border\" width=\"1%\" nowrap><a name=\"club-".$points_clubs."\"></a>".$bb_display_position_clubs."</td>";
					$table_body2 .= "<td class=\"no-bottom-border\">".$bestbrewer_clubs[$key]['Clubs']."</td>";
					$table_body2 .= "<td class=\"no-bottom-border\" width=\"10%\" nowrap>".$bestbrewer_clubs[$key]['Places'][0]."</td>";
					$table_body2 .= "<td class=\"no-bottom-border\" width=\"10%\" nowrap>".$bestbrewer_clubs[$key]['Places'][1]."</td>";
					$table_body2 .= "<td class=\"no-bottom-border\" width=\"10%\" nowrap>".$bestbrewer_clubs[$key]['Places'][2]."</td>";
					if ($show_4th_clubs) $table_body2 .= "<td class=\"no-bottom-border\" width=\"10%\" nowrap>".$bestbrewer_clubs[$key]['Places'][3]."</td>";
					if ($show_HM_clubs) $table_body2 .= "<td class=\"no-bottom-border\" width=\"10%\" nowrap>".$bestbrewer_clubs[$key]['Places'][4]."</td>";
					$table_body2 .= "<td align=\"right\" class=\"no-bottom-border\" width=\"1%\" nowrap>";
					$table_body2 .= number_format($points_clubs,7);
					$table_body2 .= "</td>";
					$table_body2 .= "</tr>";

				}

				else break;
			}

			// Clubs table headers
			$table_head2 .= "<tr>";
			$table_head2 .= sprintf("<th nowrap>%s</th>",$label_place);
			$table_head2 .= sprintf("<th>%s</th>",$label_club);
			$table_head2 .= "<th>".addOrdinalNumberSuffix(1)."</th>";
			$table_head2 .= "<th>".addOrdinalNumberSuffix(2)."</th>";
			$table_head2 .= "<th>".addOrdinalNumberSuffix(3)."</th>";
			if ($show_4th_clubs) $table_head2 .= "<th>".addOrdinalNumberSuffix(4)."</th>";
			if ($show_HM_clubs) $table_head2 .= sprintf("<th>%s</th>",$best_brewer_text_001);
			$table_head2 .= sprintf("<th nowrap>%s</th>",$label_score);
			$table_head2 .= "</tr>";

			// Display
			$slides_bos .= "<section>";
			
			$slides_bos .= "<h1 class=\"r-fit-text tight\">".$row_bb_prefs['prefsBestClubTitle']."</h1>";

			$slides_bos .= "<p class=\"entry-count\">";
			$slides_bos .= sprintf(" %s %s <span class=\"small\">[<a data-fancybox data-src=\"#scoring-method\" href=\"javascript:;\">%s</a>]</span>", get_participant_count('received-club'), ucwords($best_brewer_text_014), $best_brewer_text_003);
			$slides_bos .= "</p>";

			$slides_bos .= "<table style=\"width: 100%; font-size: .55em;\">";
			$slides_bos .= "<thead>";
			$slides_bos .= $table_head2;
			$slides_bos .= "</thead>";
			$slides_bos .= "<tbody>";
			$slides_bos .= $table_body2;
			$slides_bos .= "</tbody>";
			$slides_bos .= "</table>";

			$slides_bos .= "</section>\n";

		} // end if (($_SESSION['prefsProEdition'] == 0) && ($row_limits['prefsShowBestClub'] != 0))

		$slides_bos .= "<div style=\"display: none; height: 75%; width: 75%;\" class=\"fancy\" id=\"scoring-method\">";
		$slides_bos .= "<h2 class=\"fancy-h2\">".$best_brewer_text_003."</h2>";
		
		
		if ($row_bb_prefs['prefsScoringCOA'] == 1) {

			$slides_bos .= "<p class=\"bold-text\">".$best_brewer_text_015."</p>";
			if ($_SESSION['prefsWinnerMethod'] == 0) $slides_bos .= "<p><img src='https://brewingcompetitions.com/00_images/CoA_Scoring_Tables.png' class='img-responsive'></p>";
			else $slides_bos .= "<p><img src='https://brewingcompetitions.com/00_images/CoA_Scoring_Styles.png' class='img-responsive'></p>";
		
		}

		else {

			$slides_bos .= "<p class=\"bold-text\">".$best_brewer_text_004."</p>";

			$slides_bos .= "<ul class=\"fancy-list\">";
			$slides_bos .= "<li>".addOrdinalNumberSuffix(1)." ".$label_place.": ".$row_bb_prefs['prefsFirstPlacePts']."</li>";
			$slides_bos .= "<li>".addOrdinalNumberSuffix(2)." ".$label_place.": ".$row_bb_prefs['prefsSecondPlacePts']."</li>";
			$slides_bos .= "<li>".addOrdinalNumberSuffix(3)." ".$label_place.": ".$row_bb_prefs['prefsThirdPlacePts']."</li>";
			if ($row_bb_prefs['prefsFourthPlacePts'] > 0) $slides_bos .= "<li>".addOrdinalNumberSuffix(4)." ".$label_place.": ".$row_bb_prefs['prefsFourthPlacePts']."</li>";
			if ($row_bb_prefs['prefsHMPts'] > 0) $slides_bos .= "<li>".$best_brewer_text_001.": ".$row_bb_prefs['prefsHMPts']."</li>";
			$slides_bos .= "</ul>";

		}
		
		if (!empty($row_bb_prefs['prefsTieBreakRule1'])) {
			$slides_bos .= "<p class=\"bold-text\">".$best_brewer_text_005."</p>";
			$slides_bos .= "<ol class=\"fancy-list\">";
			$slides_bos .= "<li>".tiebreak_rule($row_bb_prefs['prefsTieBreakRule1'])."</li>";
			if (!empty($row_bb_prefs['prefsTieBreakRule2'])) $slides_bos .= "<li>".tiebreak_rule($row_bb_prefs['prefsTieBreakRule2'])."</li>";
			if (!empty($row_bb_prefs['prefsTieBreakRule3'])) $slides_bos .= "<li>".tiebreak_rule($row_bb_prefs['prefsTieBreakRule3'])."</li>";
			if (!empty($row_bb_prefs['prefsTieBreakRule4'])) $slides_bos .= "<li>".tiebreak_rule($row_bb_prefs['prefsTieBreakRule4'])."</li>";
			if (!empty($row_bb_prefs['prefsTieBreakRule5'])) $slides_bos .= "<li>".tiebreak_rule($row_bb_prefs['prefsTieBreakRule5'])."</li>";
			if (!empty($row_bb_prefs['prefsTieBreakRule6'])) $slides_bos .= "<li>".tiebreak_rule($row_bb_prefs['prefsTieBreakRule6'])."</li>";
			$slides_bos .= "</ol>";
		}
		$slides_bos .= "</div>";

	} // end if (($row_limits['prefsShowBestBrewer'] != 0) || ($row_limits['prefsShowBestClub'] != 0))

?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<title><?php echo $_SESSION['contestName']. " - " . $label_awards; ?></title>
		<!-- Load reveal.js styles / https://revealjs.com -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.1.0/reset.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.1.0/reveal.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.1.0/theme/<?php echo $reveal_theme[$view]; ?>" id="theme">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.1.0/theme/fonts/league-gothic/league-gothic.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.1.0/theme/fonts/source-sans-pro/source-sans-pro.min.css">
		<!-- Load Font Awesome / https://fortawesome.github.io/Font-Awesome -->
    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightslider/1.1.6/css/lightslider.min.css">
    	<!-- Load Fancybox / http://www.fancyapps.com -->
	    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />
    	<link rel="stylesheet" href="<?php echo $css_url; ?>awards.css">
    	<style>
    		.cs-hidden {
		        height: 1px;
		        opacity: 0;
		        filter: alpha(opacity=0);
		        overflow: hidden;
		    }

		    .sponsor-text {
		    	font-size: .4em;
		    }

		    .fancy {
		    	font-family: 'Source Sans Pro';
		    	font-size: 18px;
		    }

		    .fancy p {
		    	margin: 10px 0 5px 0;
		    	padding: 10px 0 5px 0;
		    }

		    .fancy-h2 {
		    	font-size: 3em;
		    	margin: 0 0 10px 0;
		    	padding: 0 0 10px 0;
		    	font-weight: bolder;
		    }

		    .fancy-list {
		    	margin-left: 35px;
		    }

		    .fancy-list li {
		    	margin-bottom: 5px;
		    	padding-bottom: 5px;
		    }

		    .fancy .bold-text {
		    	font-weight: bold;
		    }
    	</style>
	</head>
	<body>
		<noscript><?php echo $alert_text_087; ?></noscript>
		<div class="reveal">
			<div class="slides">
				
				<!-- Title Slide -->
				<section>
					<h1 style="margin:0;padding:0" class="r-fit-text"><?php echo $_SESSION['contestName']; ?></h1>
					<h1 style="margin:0;padding:0" class="tight"><?php echo $label_awards; ?></h1>
					<?php if ((!empty($_SESSION['contestLogo'])) && (file_exists(USER_IMAGES.$_SESSION['contestLogo']))) { ?>
						<div class="logo-image">
							<img src="<?php echo $base_url."user_images/".$_SESSION['contestLogo']; ?>">
						</div>
					<?php } ?>
				</section>
				
				<?php if ($_SESSION['prefsSponsorLogos'] == "Y") { ?>
				<!-- Sponsor Carousel Slide -->	
				<section>
					<h1 style="margin:0;padding:0" class="r-fit-text"><?php echo $_SESSION['contestName']; ?></h1>
					<h1 style="margin:0;padding:0" class="tight"><?php echo $label_sponsors; ?></h1>
					    <ul id="sponsor-slider">
					   	<?php foreach ($rows_sponsors as $row_sponsors) {
					   	if ($row_sponsors['sponsorEnable'] == "1") {
					   	if ((!empty($row_sponsors['sponsorImage'])) && (file_exists(USER_IMAGES.$row_sponsors['sponsorImage']))) { ?>
					   		<li data-thumb="<?php echo $base_url."user_images/".$row_sponsors['sponsorImage']; ?>"><img src="<?php echo $base_url."user_images/".$row_sponsors['sponsorImage']; ?>" height="200" alt="<?php echo $row_sponsors['sponsorName']; ?>"></li>
					   	<?php
					   			}
					   		}
					   	} ?>
					    </ul>
				</section>
				<?php } ?>
				
				<?php if (!empty($judge_list)) { ?>
				<!-- Judge List Slide -->
				<section>
					<h1 style="margin:0;padding:0" class="tight"><?php echo $label_judges; ?></h1>
					<p><small><?php echo rtrim($judge_list, ", "); ?></small></p>
					<?php if (!empty($judge_bos)) { ?>
					<h3 style="margin:0;padding:0" class="tight"><?php echo $label_judges." - ".$label_bos; ?></h3>
					<p><small><?php echo rtrim($judge_bos, ", "); ?></small></p>
					<?php } ?>
				</section>
				<?php } ?>
				
				<?php if (!empty($steward_list)) { ?>
				<!-- Steward List Slide -->
				<section>
					<h1 style="margin:0;padding:0" class="tight"><?php echo $label_stewards; ?></h1>
					<p><small><?php echo rtrim($steward_list, ", "); ?></small></p>
				</section>
				<?php } ?>
				
				<?php if ((!empty($staff_list)) || (!empty($staff_organizer))) { ?>
				<!-- Staff List Slide -->
				<section>
					<h1 style="margin:0;padding:0" class="tight"><?php echo $label_staff; ?></h1>
					<?php if (!empty($staff_list)) { ?>
					<p><small><?php echo rtrim($staff_list, ", "); ?></small></p>
					<?php } ?>
					<?php if (!empty($staff_organizer)) { ?>
					<h2 style="margin:0;padding:0" class="tight"><?php echo $label_organizer; ?></h2>
					<p><small><?php echo rtrim($staff_organizer, ", "); ?></small></p>
					<?php } ?>
				</section>
				<?php } ?>

				<!-- Statistic Slide -->
				<?php 
				$entries_count = get_entry_count('paid-received');
				$entrant_count = get_participant_count('received-entrant');
				$judges_count = get_participant_count('judge-assigned');
				$steward_count = get_participant_count('steward-assigned');
				$staff_count = get_participant_count('staff-assigned');
				$placing_entry_count = get_entry_count('placing-entries');
				?>
				<section>
					<h1 style="margin:0;padding:0" class="tight"><?php echo $label_by_the_numbers; ?></h1>
					<p>
						<?php if ($entries_count > 0) { ?>
						<span style="margin-right: 15px;" class="fragment" data-fragment-index="1"><i class="fa fa-beer"></i> <?php echo $entries_count." ".$label_entries; ?></span>
						<?php } ?>
						<?php if ($entrant_count > 0) { ?>
						<span class="fragment" data-fragment-index="1"><i class="fa fa-user"></i> <?php echo $entrant_count." ".$label_entrants; ?></span>
						<?php } ?>
					</p>
					<p>
						<?php if ($judges_count > 0) { ?>
						<span style="margin-right: 15px;" class="fragment" data-fragment-index="2"><i class="fa fa-gavel"></i> <?php echo $judges_count." ".$label_judges; ?></span>
						<?php } ?>
						<?php if ($steward_count > 0) { ?>
						<span style="margin-right: 15px;" class="fragment" data-fragment-index="2"><i class="fa fa-pencil"></i> <?php echo $steward_count." ".$label_stewards; ?></span>
						<?php } ?>
						<?php if ($staff_count > 0) { ?>
						<span class="fragment" data-fragment-index="2"><i class="fa fa-user-circle"></i> <?php echo $staff_count." ".$label_staff; ?></span>
						<?php } ?>
					</p>
					<?php if ($placing_entry_count > 0) { ?>
					<p>
						<span style="margin-right: 15px;" class="fragment" data-fragment-index="3"><i class="fa fa-trophy"></i> <?php echo $placing_entry_count." ".$label_placing_entries;  ?></span>
					</p>
					<?php } ?>
					<?php if ((!empty($_SESSION['contestLogo'])) && (file_exists(USER_IMAGES.$_SESSION['contestLogo']))) { ?>
						<div class="logo-image">
							<img style="max-height: 225px;" src="<?php echo $base_url."user_images/".$_SESSION['contestLogo']; ?>">
						</div>
					<?php } ?>
				</section>
				
				<!-- Table/Category/Sub-Cat Medal Slide Sections -->
				<?php 
				if (!empty($slides)) echo $slides; 
				if (!empty($slides_bos)) echo $slides_bos;
				?>
				<!-- End Slide -->
				<section>
					<h1 style="margin:0;padding:0" class="r-fit-text"><?php echo $label_thank_you; ?></h1>
					<h3 style="margin:0;padding:0"><?php echo $label_congrats_winners; ?></h3>
					<?php if ((!empty($_SESSION['contestLogo'])) && (file_exists(USER_IMAGES.$_SESSION['contestLogo']))) { ?>
						<div class="logo-image">
							<img height="200" src="<?php echo $base_url."user_images/".$_SESSION['contestLogo']; ?>">
						</div>
					<?php } ?>
				</section>
			</div>	
			<div class="footer" style="text-align:left; padding-left: 20px; font-size: .35em;"><?php echo $_SESSION['contestName']." - ".$label_awards." - ".$current_date_display; ?></div>
		</div>
		<!-- Load reveal.js and associated plug-ins / https://revealjs.com -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.1.0/reveal.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.1.0/plugin/notes/notes.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.1.0/plugin/notes/plugin.min.js"></script>
		<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/lightslider/1.1.6/js/lightslider.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
		<script>
			Reveal.initialize({
				hash: true,
				plugins: [ RevealNotes ]
			});
			$(document).ready(function() {
				$("#sponsor-slider").lightSlider({
					gallery: true,
					auto: true,
					item: 4,
			        autoWidth: false,
			        loop: true,
			        keyPress: false,
			        thumbItem: 25,
			        easing: 'cubic-bezier(0.25, 0, 0.25, 1)',
			    });
			  });
		</script>
	</body>
</html>
<?php } // end if (($logged_in) && ($_SESSION['userLevel'] <= 1)) ?>
