<?php
/**
 * Module:      winners.sec.php
 * Description: This module displays the winners entered into the database.
 *              Displays by table.
 *
 */

/*
// Redirect if directly accessed
if ((!isset($_SESSION['prefs'.$prefix_session])) || ((isset($_SESSION['prefs'.$prefix_session])) && (!isset($base_url)))) {
    $redirect = "../../index.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}
*/

$winners_by_table = "";
$order_by = array();

if ($section == "past-winners") {
	// $go is restricted to alphanumeric characters before use in table-name identifiers
	// (matching the pattern used elsewhere in the codebase, e.g. includes/db/winners.db.php's
	// and pub/past_winners.pub.php's $filter_clean).
	$go_clean = preg_replace("/[^a-zA-Z0-9]+/", "", $go);
	$suffix = $go_clean;
	$judging_tables_db_table = $prefix."judging_tables_".$go_clean;
	$judging_scores_db_table = $prefix."judging_scores_".$go_clean;
	$brewing_db_table = $prefix."brewing_".$go_clean;
	$brewer_db_table = $prefix."brewer_".$go_clean;
}

else {
	$suffix = "default";
	$judging_tables_db_table = $prefix."judging_tables";
	$judging_scores_db_table = $prefix."judging_scores";
	$brewing_db_table = $prefix."brewing";
	$brewer_db_table = $prefix."brewer";
}
 
if ($row_scored_entries['count'] > 0) {

	/**
	 * Batch what used to be per-table, per-style queries in the loop below into
	 * three queries total, run once up front:
	 *   1. Every style referenced by any table's tableStyles list, in one lookup.
	 *   2. Received-entry counts for every category/subcategory combo, grouped.
	 *   3. All scores for all tables, joined once and grouped by table in PHP.
	 * Previously this was ~2 queries per style per table plus ~2 per table
	 * (score_count() + scores.db.php), i.e. O(tables x styles) round trips -
	 * on a real dataset (38 tables) that was 200+ queries for this one section.
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

	$counts_by_style = array();
	if (table_exists($brewing_db_table)) {
		$db_conn->where('brewReceived', '1');
		$db_conn->groupBy('brewCategorySort');
		$db_conn->groupBy('brewSubCategory');
		$rows_counts = $db_conn->get($brewing_db_table, null, "brewCategorySort, brewSubCategory, COUNT(*) as count");
		foreach ($rows_counts as $row_count) {
			$counts_by_style[$row_count['brewCategorySort'].'|'.$row_count['brewSubCategory']] = $row_count['count'];
		}
	}

	// Mirrors includes/db/scores.db.php's winner_method==0 query exactly, just
	// widened from "WHERE a.scoreTable=?" (one table) to an IN-list (all tables),
	// then split back apart by scoreTable below.
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

	foreach ($rows_tables as $row_tables) {

		$winners_table_all = "";

		$a = explode(",", $row_tables['tableStyles']);
		$missing_style = FALSE;

		$entry_count = 0;

		foreach ($a as $value) {

			if (!isset($styles_by_id[$value])) $missing_style = TRUE;

			else {
				$row_styles = $styles_by_id[$value];
				$style_key = $row_styles['brewStyleGroup'].'|'.$row_styles['brewStyleNum'];
				$entry_count += $counts_by_style[$style_key] ?? 0;
			}

		}

		$primary_page_info = "";
		$winners_table_header = "";
		$winners_table_page_info_1 = "";
		$winners_table_head_2 = "";
		$winners_table_page_info_2 = "";
		$winners_table_head_1 = "";
		$winners_table_body_1 = "";
		
		if ($entry_count > 0) {

			if ($entry_count > 1) $entries = strtolower($label_entries); 
			else $entries = strtolower($label_entry);

			if ($psort != "default") $winners_table_head_2 .= sprintf("<div class=\"bcoem-winner-table\"><h3>%s (%s %s)</h3><p>%s</p></div>",$row_tables['tableName'],$entry_count,$entries,$winners_text_000);
			else $winners_table_head_2 .= sprintf("<div class=\"bcoem-winner-table\"><h3>%s %s: %s (%s %s)</h3><p>%s</p></div>",$label_table,$row_tables['tableNumber'],$row_tables['tableName'],$entry_count,$entries,$winners_text_000);

			if (!empty($scores_by_table[$row_tables['id']])) {

				// Build page headers
				$winners_table_header .= sprintf("<h3>%s <span class=\"fs-4 fw-normal text-body-secondary\">(%s %s)</span></h3>",$row_tables['tableName'],$entry_count,$entries);

				if ($missing_style) $winners_table_header .= sprintf("<p>%s</p>",$winners_text_006);

				// Build table body - pulled from the batched fetch above instead of
				// a fresh include(DB.'scores.db.php') query per table.
				$rows_scores = $scores_by_table[$row_tables['id']];
				$totalRows_scores = count($rows_scores);

				if ($totalRows_scores > 0) {

					// Build table headers
					$winners_table_head_1 .= "<tr>";
					$winners_table_head_1 .= sprintf("<th nowrap>%s</th>",$label_place);
					$winners_table_head_1 .= sprintf("<th>%s</th>",$label_brewer);
					$winners_table_head_1 .= sprintf("<th><span class=\"hidden-xs hidden-sm hidden-md\">%s </span>%s</th>",$label_entry,$label_name);
					$winners_table_head_1 .= sprintf("<th>%s</th>",$label_style);
					if ($_SESSION['prefsProEdition'] == 0) $winners_table_head_1 .= sprintf("<th>%s</th>",$label_club);
					if ($tb == "scores") $winners_table_head_1 .= sprintf("<th nowrap>Score</th>",$label_score);
					$winners_table_head_1 .= "</tr>";

					foreach ($rows_scores as $row_scores) {
						if ($_SESSION['prefsStyleSet'] == "AABC") $style = ltrim($row_scores['brewCategory'],"0").".".ltrim($row_scores['brewSubCategory'],"0");
	       				else $style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];

	       				$entry_name = html_entity_decode($row_scores['brewName'],ENT_QUOTES|ENT_XML1,"UTF-8");
	   					$entry_name = htmlentities($entry_name,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5,"UTF-8");

						$winners_table_body_1 .= "<tr>";

						if ($action == "print") {
							$winners_table_body_1 .= "<td width=\"1%\" nowrap>";
							$winners_table_body_1 .= display_place($row_scores['scorePlace'],1);
							$winners_table_body_1 .= "</td>";
						}

						else {
							$winners_table_body_1 .= "<td width=\"1%\" nowrap>";
							$winners_table_body_1 .= display_place($row_scores['scorePlace'],2);
							$winners_table_body_1 .= "</td>";
						}

						$winners_table_body_1 .= "<td>";
						if ($_SESSION['prefsProEdition'] == 1) {
						    if (empty($row_scores['brewerBreweryName'])) $winners_table_body_1 .= $row_scores['brewerFirstName']." ".$row_scores['brewerLastName'];
						    else $winners_table_body_1 .= $row_scores['brewerBreweryName'];
						}
						else {
							$winners_table_body_1 .= $row_scores['brewerFirstName']." ".$row_scores['brewerLastName'];
							if (($_SESSION['prefsMHPDisplay'] == 1) && (isset($row_scores['brewerMHP'])) && (!empty($row_scores['brewerMHP']))) $winners_table_body_1 .= " <span data-bs-toggle=\"tooltip\" data-bs-placement=\"top\" title=\"Master Homebrewer Program Participant\" style=\"color: #F2D06C; background-color: #000;\" class=\"badge\">MHP</span>";
						}
						if (($_SESSION['prefsProEdition'] == 0) && (!empty($row_scores['brewCoBrewer'])) && ($row_scores['brewCoBrewer'] != " ")) $winners_table_body_1 .= "<br>".$label_cobrewer.": ".$row_scores['brewCoBrewer'];
						$winners_table_body_1 .= "</td>";

						$winners_table_body_1 .= "<td width=\"25%\">";
						$winners_table_body_1 .= $entry_name;
						$winners_table_body_1 .= "</td>";

						$winners_table_body_1 .= "<td width=\"25%\">";
						if ($_SESSION['style_set_no_numbering']) $winners_table_body_1 .= $row_scores['brewStyle'];
						else $winners_table_body_1 .= $style.": ".$row_scores['brewStyle'];

						if ((!empty($row_scores['brewInfo'])) && ($section != "results") && ($section != "past-winners")) {
							$winners_table_body_1 .= "<button class=\"m-0 btn btn-sm btn-link\" style=\"--bs-btn-padding-y: .1rem; --bs-btn-padding-x: .1rem; \" tabindex=\"0\" data-bs-toggle=\"popover\" data-bs-trigger=\"hover focus\" data-bs-placement=\"top\" data-bs-container=\"body\" data-bs-title=\"".$label_info."\" data-bs-content=\"".str_replace("^", " ", $row_scores['brewInfo'])."\"><i class=\"hidden-xs hidden-sm hidden-md d-print-none fa fa-fw fa-info-circle\"></i></button>";
						}

						$winners_table_body_1 .= "</td>";

						if ($_SESSION['prefsProEdition'] == 0) {
							$winners_table_body_1 .= "<td width=\"25%\">";
							$winners_table_body_1 .= $row_scores['brewerClubs'];
							$winners_table_body_1 .= "</td>";
						}

						if ($tb == "scores") {
							$winners_table_body_1 .= "<td width=\"1%\" nowrap>";
							if (!empty($row_scores['scoreEntry'])) {
								if (strpos($row_scores['scoreEntry'], '.') !== false) $winners_table_body_1 .= rtrim(number_format($row_scores['scoreEntry'],2),"0"); 
								else $winners_table_body_1 .= $row_scores['scoreEntry'];
							}
							else $winners_table_body_1 .= "&nbsp;";
							$winners_table_body_1 .= "</td>";
						}

						$winners_table_body_1 .= "</tr>";

					}
				}

				$random1 = "";
				$random1 .= random_generator(12,1);

				// --------------------------------------------------------------
				// Display
				// --------------------------------------------------------------


				if ($sort == "default") $sort = "asc";
				else $sort = $sort;
				$winners_table_all .= "
				<script type=\"text/javascript\" language=\"javascript\">
				$(document).ready(function() {
				    $('#medal-category-".$random1."').dataTable( {
				    	\"bPaginate\" : false,
				    	\"sDom\": 'rt',
				        \"aaSorting\": [ [0,'".$sort."'] ],
				        \"aoColumns\": [
				            null,
				            null,
				            null,
				            null,";

				if ($_SESSION['prefsProEdition'] == 0) $winners_table_all .= " { \"asSorting\": [  ] },";
				if ($tb == "scores") $winners_table_all .= " { \"asSorting\": [  ] }";

				$winners_table_all .= "
				        ]
				    });
				} );
				</script>
				";

				$winners_table_all .= "<div class=\"bcoem-winner-table reveal-element\">";
				
				$winners_table_all .= $winners_table_header;
				
				if (!empty($winners_table_body_1)) {
					$winners_table_all .= "<div class=\"table-responsive-md\">";
					$winners_table_all .= "<table class=\"table table-bordered table-striped border-dark-subtle\" id=\"medal-category-".$random1."\">";
					$winners_table_all .= "<thead class=\"table-dark\">";
					$winners_table_all .= $winners_table_head_1;
					$winners_table_all .= "</thead>";
					$winners_table_all .= "<tbody>";
					$winners_table_all .= $winners_table_body_1;
					$winners_table_all .= "</tbody>";
					$winners_table_all .= "</table>";
					$winners_table_all .= "</div>";
				} else $winners_table_all .= sprintf("<p>%s</p>",$winners_text_007);

				$winners_table_all .= "</div>";
			
			} // end if (!empty($scores_by_table[$row_tables['id']]))
			else $winners_table_all .= $winners_table_head_2;
		
		} // end if ($entry_count > 0);

		if (($psort == "table-numbers") || ($psort == "default")) {
			$order_by[] = array(
				'id' => $row_tables['tableNumber'],
				'table_name' => $row_tables['tableName'],
				'data' => $winners_table_all
			);
		}

		if (($psort == "table-entry-count-asc") || ($psort == "table-entry-count-desc")) {
			$order_by[] = array(
				'id' => $entry_count,
				'table_name' => $row_tables['tableName'],
				'data' => $winners_table_all
			);
		}

	}

	$table_number = array_column($order_by, 'id');
	$table_name = array_column($order_by, 'table_name');

	if ($psort == "table-entry-count-desc") array_multisort($table_number, SORT_DESC, $table_name, SORT_ASC, $order_by);
	else array_multisort($table_number, SORT_ASC, $table_name, SORT_ASC, $order_by);

	foreach ($order_by as $key => $value) {
		$winners_by_table .= $value['data'];
	}

	if (!empty($winners_by_table)) echo $winners_by_table;

}

else echo sprintf("<p>%s</p>",$winners_text_001);
?>

<!-- Public Page Rebuild completed 08.26.15 -->

