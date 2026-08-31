<?php
/**
 * Module:      winners_subcategory.sec.php
 * Description: This module displays the winners entered into the database.
 *              Displays by style subcategory.
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

if ($row_scored_entries['count'] > 0) {

	$category_end = $_SESSION['style_set_category_end'];

	/**
	 * Batch what used to be 2-3 queries per active subcategory (includes/db/winners_subcategory.db.php's
	 * entry/score counts, plus includes/db/scores.db.php's fetch) into 2 queries total, run once
	 * up front - same pattern as pub/winners.pub.php and pub/winners_category.pub.php. Previously
	 * this was O(active subcategories) round trips per page load, and subcategories typically
	 * outnumber categories several times over.
	 *
	 * Driver: for the live/default competition, read the admin-curated prefsSelectedStyles list
	 * (the source of truth for which styles are enabled - see admin/styles.admin.php) instead of
	 * a live styles_active() query. Archived competitions have no per-archive equivalent of
	 * prefsSelectedStyles (only archiveStyleSet, which style version, not which styles are on),
	 * so they still need styles_active() against the archive's own style tables, keyed by $filter
	 * (the actual archive suffix - not $go, which is a separate request parameter). Both branches
	 * produce the same "group^num^style" string shape styles_active(2,...) always returned, so
	 * the rest of this file (the explode("^",...) driven loop below) is unchanged.
	 */

	// Resolve archive/filter context once - identical every iteration below, mirrors
	// includes/db/winners_subcategory.db.php's own resolution exactly.
	if ($filter == "default") {
		$winner_style_set = $_SESSION['prefsStyleSet'];
		$prefs_selected_styles = json_decode($_SESSION['prefsSelectedStyles'], true);
		$a = array();
		if (is_array($prefs_selected_styles)) {
			foreach ($prefs_selected_styles as $selected_style) {
				$a[] = $selected_style['brewStyleGroup'].'^'.$selected_style['brewStyleNum'].'^'.$selected_style['brewStyle'];
			}
		}
	}
	else {
		$winner_style_set = $row_disp_archive_winners['archiveStyleSet'];
		$filter_clean = preg_replace("/[^a-zA-Z0-9]+/", "", $filter);
		$special_best_info_db_table = $prefix."special_best_info_".$filter_clean;
		$judging_tables_db_table = $prefix."judging_tables_".$filter_clean;
		$style_types_db_table = $prefix."style_types_".$filter_clean;
		$judging_scores_db_table = $prefix."judging_scores_".$filter_clean;
		$judging_scores_bos_db_table = $prefix."judging_scores_bos_".$filter_clean;
		$a = styles_active(2, $filter);
	}

	$category_column = (style_set_no_numbering($winner_style_set)) ? "brewCategory" : "brewCategorySort";

	// Entry counts, keyed by "category|subcategory" - matches winners_subcategory.db.php's
	// composite WHERE (category_column + brewSubCategory) used for both style sets (BA
	// additionally filters brewReceived=1 there, preserved below).
	$counts_by_subcategory = array();
	if (table_exists($brewing_db_table)) {
		if (style_set_no_numbering($winner_style_set)) $db_conn->where('brewReceived', '1');
		$db_conn->groupBy($category_column);
		$db_conn->groupBy('brewSubCategory');
		$rows_subcat_counts = $db_conn->get($brewing_db_table, null, "$category_column, brewSubCategory, COUNT(*) as count");
		foreach ($rows_subcat_counts as $row_subcat_count) {
			$counts_by_subcategory[$row_subcat_count[$category_column].'|'.$row_subcat_count['brewSubCategory']] = $row_subcat_count['count'];
		}
	}

	// Mirrors includes/db/scores.db.php's winner_method==2 query exactly - including its
	// BA quirk of filtering only brewSubCategory, not category+subcategory together - just
	// without the per-subcategory WHERE filter, then split back apart below. BA groups by
	// brewSubCategory alone to match that quirk; non-BA groups by the composite key.
	// BA2026's brewStyleNum was made globally unique to match old BA's own flat numbering
	// scheme (see the BA2026 "num-uniqueness" work), so the bare-brewSubCategory key below
	// is safe for both.
	$scores_by_subcategory = array();
	if ((table_exists($judging_scores_db_table)) && (table_exists($brewing_db_table)) && (table_exists($brewer_db_table))) {
		$query_scores_all_subcat = "SELECT * FROM ".$judging_scores_db_table." a, ".$brewing_db_table." b, ".$brewer_db_table." c WHERE a.eid = b.id AND c.uid = b.brewBrewerID";
		if ((($action == "print") && ($view == "winners")) || ($action == "default") || ($section == "default")) $query_scores_all_subcat .= " AND a.scorePlace > 0";
		$query_scores_all_subcat .= " ORDER BY b.brewSubCategory";
		if ($action == "awards-pres") $query_scores_all_subcat .= ", a.scorePlace DESC";
		else $query_scores_all_subcat .= ", a.scorePlace ASC";
		$rows_scores_all_subcat = $db_conn->rawQuery($query_scores_all_subcat);
		foreach ($rows_scores_all_subcat as $row_score_subcat) {
			if (style_set_no_numbering($winner_style_set)) $group_key = $row_score_subcat['brewSubCategory'];
			else $group_key = $row_score_subcat['brewCategorySort'].'|'.$row_score_subcat['brewSubCategory'];
			$scores_by_subcategory[$group_key][] = $row_score_subcat;
		}
	}

	foreach (array_unique($a) as $style) {

		$style = explode("^",$style);
		$value['brewStyleGroup'] = $style[0];
		$value['brewStyleNum'] = $style[1];

		$row_entry_count = array('count' => $counts_by_subcategory[$value['brewStyleGroup'].'|'.$value['brewStyleNum']] ?? 0);

		if (style_set_no_numbering($winner_style_set)) $score_key = $value['brewStyleNum'];
		else $score_key = $value['brewStyleGroup'].'|'.$value['brewStyleNum'];
		$table_scores = $scores_by_subcategory[$score_key] ?? array();
		$row_score_count = array('count' => count($table_scores));

		// Display all winners
		if ($row_entry_count['count'] > 0) {

			if ($row_entry_count['count'] > 1) $entries = strtolower($label_entries); 
			else $entries = strtolower($label_entry);

			if ($row_score_count['count'] > 0) {

			$primary_page_info = "";
			$header1_1 = "";
			$page_info1 = "";
			$header1_2 = "";
			$page_info2 = "";

			$table_head1 = "";
			$table_body1 = "";

			// Build headers
			if (style_set_no_numbering($winner_style_set)) $header1_1 .= sprintf("<h3>%s <span class=\"fs-4 fw-normal text-body-secondary\">(%s %s)</span></h3>",$style[2],$row_entry_count['count'],$entries);
			else $header1_1 .= sprintf("<h3>%s %s%s: %s <span class=\"fs-4 fw-normal text-body-secondary\">(%s %s)</span></h3>",$label_category,ltrim($style[0],"0"),$style[1],$style[2],$row_entry_count['count'],$entries);

			// Build table headers
			$table_head1 .= "<tr>";
			$table_head1 .= sprintf("<th nowrap>%s</th>",$label_place);
			$table_head1 .= sprintf("<th>%s</th>",$label_brewer);
			$table_head1 .= sprintf("<th><span class=\"hidden-xs hidden-sm hidden-md\">%s </span>%s</th>",$label_entry,$label_name);
			$table_head1 .= sprintf("<th>%s</th>",$label_style);
			if ($_SESSION['prefsProEdition'] == 0) $table_head1 .= sprintf("<th>%s</th>",$label_club);
			if ($tb == "scores") $table_head1 .= sprintf("<th nowrap>Score</th>",$label_score);
			$table_head1 .= "</tr>";

			// Build table body - pulled from the batched fetch above instead of
			// a fresh include(DB.'scores.db.php') query per subcategory.
			$rows_scores = $table_scores;
			$totalRows_scores = count($rows_scores);

			foreach ($rows_scores as $row_scores) {

				if ((isset($row_scores['brewCategory'])) && (!empty($row_scores['brewCategory']))) {

				$entry_name = html_entity_decode($row_scores['brewName'],ENT_QUOTES|ENT_XML1,"UTF-8");
    			$entry_name = htmlentities($entry_name,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5,"UTF-8");

				if (style_set_no_numbering($winner_style_set)) {

					if (is_numeric($row_scores['brewSubCategory'])) {
						$style = $_SESSION['styles']['data'][$row_scores['brewSubCategory'] - 1]['category']['name'];
						if ($style == "Hybrid/mixed Beer") $style = "Hybrid/Mixed Beer";
						elseif ($style == "European-germanic Lager") $style = "European-Germanic Lager";
						else $style = ucwords($style);
					}

					else $style = "Custom Style";
					$style_long = $row_scores['brewStyle'];

				}

				else {

					if ($winner_style_set == "AABC") $style = ltrim($row_scores['brewCategory'],"0").".".ltrim($row_scores['brewSubCategory'],"0");
       				else $style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
					if ($row_scores['brewCategorySort'] > $category_end) $style_long = style_convert($row_scores['brewCategorySort'],1,$base_url,$go);
					else $style_long = $row_scores['brewStyle'];

				}

				$table_body1 .= "<tr>";

				if ($action == "print") {
					$table_body1 .= "<td width=\"1%\" nowrap>";
					$table_body1 .= display_place($row_scores['scorePlace'],1);
					$table_body1 .= "</td>";
				}

				else {
					$table_body1 .= "<td width=\"1%\" nowrap>";
					$table_body1 .= display_place($row_scores['scorePlace'],2);
					$table_body1 .= "</td>";
				}
				
				$table_body1 .= "<td width=\"25%\">";
				
				if ($_SESSION['prefsProEdition'] == 1) {
				    if (empty($row_scores['brewerBreweryName'])) $table_body1 .= $row_scores['brewerFirstName']." ".$row_scores['brewerLastName'];
				    else $table_body1 .= $row_scores['brewerBreweryName'];
				}
				
				else {
					$table_body1 .= $row_scores['brewerFirstName']." ".$row_scores['brewerLastName'];
					if (($_SESSION['prefsMHPDisplay'] == 1) && (isset($row_scores['brewerMHP'])) && (!empty($row_scores['brewerMHP']))) $table_body1 .= " <span data-toggle=\"tooltip\" data-placement=\"top\" title=\"Master Homebrewer Program Participant\" style=\"color: #F2D06C; background-color: #000;\" class=\"badge\">MHP</span>";
				}
				
				if (($_SESSION['prefsProEdition'] == 0) && (!empty($row_scores['brewCoBrewer'])) && ($row_scores['brewCoBrewer'] != " ")) $table_body1 .= "<br>".$label_cobrewer.": ".$row_scores['brewCoBrewer'];
				
				$table_body1 .= "</td>";

				$table_body1 .= "<td>";
				$table_body1 .= $entry_name;
				$table_body1 .= "</td>";

				$table_body1 .= "<td width=\"25%\">";
				$table_body1 .= $style.": ".$style_long;

				if ((!empty($row_scores['brewInfo'])) && ($section != "results") && ($section != "past-winners")) {
					$table_body1 .= "<button class=\"m-0 btn btn-sm btn-link\" style=\"--bs-btn-padding-y: .1rem; --bs-btn-padding-x: .1rem; \" tabindex=\"0\" data-bs-toggle=\"popover\" data-bs-trigger=\"hover focus\" data-bs-placement=\"top\" data-bs-container=\"body\" data-bs-title=\"".$label_info."\" data-bs-content=\"".str_replace("^", " ", $row_scores['brewInfo'])."\"><i class=\"hidden-xs hidden-sm hidden-md d-print-none fa fa-fw fa-info-circle\"></i></button>";
				}
				
				$table_body1 .= "</td>";

				if ($_SESSION['prefsProEdition'] == 0) {
					$table_body1 .= "<td width=\"25%\">";
					$table_body1 .= $row_scores['brewerClubs'];
					$table_body1 .= "</td>";
				}

				if ($tb == "scores") {
					$table_body1 .= "<td width=\"1%\" nowrap>";
					if (!empty($row_scores['scoreEntry'])) {
						if (strpos($row_scores['scoreEntry'], '.') !== false) $table_body1 .= rtrim(number_format($row_scores['scoreEntry'],2),"0"); 
						else $table_body1 .= $row_scores['scoreEntry'];
					}
					else $table_body1 .= "&nbsp;";
					$table_body1 .= "</td>";
				}

				$table_body1 .= "</tr>";
			}

			 }
	$random1 = "";
	$random1 .= random_generator(12,1);

	if (!empty($table_body1)) {
	?>
	<?php echo $header1_1; ?></h3>
	 <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable<?php echo $random1; ?>').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				null,
				null,
				null,
				<?php if ($_SESSION['prefsProEdition'] == 0) { ?>null,<?php } ?>
				null<?php if ($tb == "scores") { ?>,
				null
				<?php } ?>
				]
			} );
		} );
	</script>
	<div class="table-responsive-md">
		<table class="table table-bordered table-striped border-dark-subtle" id="sortable<?php echo $random1; ?>">
		<thead class="table-dark">
			<?php echo $table_head1; ?>
		</thead>
		<tbody>
			<?php echo $table_body1; ?>
		</tbody>
		</table>
	</div>
	<?php 		}
			}
		}
	}
} // end if score count > 0

else echo sprintf("<p>%s</p>",$winners_text_001);
?>