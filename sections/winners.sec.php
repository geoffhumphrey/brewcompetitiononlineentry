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
	$suffix = $go;
	$judging_tables_db_table = $prefix."judging_tables_".$go;
	$judging_scores_db_table = $prefix."judging_scores_".$go;
	$brewing_db_table = $prefix."brewing_".$go;
	$brewer_db_table = $prefix."brewer_".$go;
}

else {
	$suffix = "default";
	$judging_tables_db_table = $prefix."judging_tables";
	$judging_scores_db_table = $prefix."judging_scores";
	$brewing_db_table = $prefix."brewing";
	$brewer_db_table = $prefix."brewer";
}

if ($row_scored_entries['count'] > 0) {

	do {

		$winners_table_all = "";
		
		$a = explode(",", $row_tables['tableStyles']);
		$missing_style = FALSE;

		$entry_count = 0;

		foreach ($a as $value) {

			/*
			if (HOSTED) $query_styles = sprintf("SELECT brewStyleGroup,brewStyleNum FROM %s WHERE id='%s' UNION ALL SELECT brewStyleGroup, brewStyleNum FROM %s WHERE id='%s'", "bcoem_shared_styles", $value, $prefix."styles", $value);
			else 
			*/
			$query_styles = sprintf("SELECT brewStyleGroup, brewStyleNum FROM %s WHERE id='%s'", $prefix."styles", $value);
			$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
			$row_styles = mysqli_fetch_assoc($styles);
			$totalRows_styles = mysqli_num_rows($styles);
			
			if ($totalRows_styles == 0) $missing_style = TRUE;
			
			else {
				$query_style_count = sprintf("SELECT COUNT(*) as count FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s' AND brewReceived='1'", $brewing_db_table, $row_styles['brewStyleGroup'], $row_styles['brewStyleNum']);
				$style_count = mysqli_query($connection,$query_style_count) or die (mysqli_error($connection));
				$row_style_count = mysqli_fetch_assoc($style_count);

				$entry_count += $row_style_count['count'];
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

			if (score_count($row_tables['id'],"1",$suffix))	{

				// Build page headers
				if ($psort != "default") $winners_table_header .= sprintf("<h3>%s (%s %s)</h3>",$row_tables['tableName'],$entry_count,$entries);
				else $winners_table_header .= sprintf("<h3>%s %s: %s (%s %s)</h3>",$label_table,$row_tables['tableNumber'],$row_tables['tableName'],$entry_count,$entries);

				if ($missing_style) $winners_table_header .= sprintf("<p>%s</p>",$winners_text_006);

				// Build table body
				include (DB.'scores.db.php');

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

					do {
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
							if ((isset($row_scores['brewerMHP'])) && (!empty($row_scores['brewerMHP']))) $winners_table_body_1 .= " <span data-toggle=\"tooltip\" data-placement=\"top\" title=\"Master Homebrewer Program Participant\" style=\"color: #F2D06C; background-color: #000;\" class=\"badge\">MHP</span>";
						}
						if (($_SESSION['prefsProEdition'] == 0) && (!empty($row_scores['brewCoBrewer'])) && ($row_scores['brewCoBrewer'] != " ")) $winners_table_body_1 .= "<br>".$label_cobrewer.": ".$row_scores['brewCoBrewer'];
						$winners_table_body_1 .= "</td>";

						$winners_table_body_1 .= "<td width=\"25%\">";
						$winners_table_body_1 .= $entry_name;
						$winners_table_body_1 .= "</td>";

						$winners_table_body_1 .= "<td width=\"25%\">";
						if ($_SESSION['prefsStyleSet'] == "BA") $winners_table_body_1 .= $row_scores['brewStyle'];
						else $winners_table_body_1 .= $style.": ".$row_scores['brewStyle'];

						if ((!empty($row_scores['brewInfo'])) && ($section != "results") && ($section != "past-winners")) {
							$winners_table_body_1 .= " <a href=\"#".$row_scores['id']."\" tabindex=\"0\" role=\"button\" data-toggle=\"popover\" data-trigger=\"hover\" data-placement=\"auto top\" data-container=\"body\" title=\"".$label_info."\" data-content=\"".str_replace("^", " ", $row_scores['brewInfo'])."\"><span class=\"hidden-xs hidden-sm hidden-md hidden-print fa fa-info-circle\"></span></a>";
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

					} while ($row_scores = mysqli_fetch_assoc($scores));
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
				    $('#sortable".$random1."').dataTable( {
				    	\"bPaginate\" : false,
				    	\"sDom\": 'rt',
				        \"aaSorting\": [ [0,'".$sort."'] ],
				        \"aoColumns\": [
				            { \"asSorting\": [  ] },
				            { \"asSorting\": [  ] },
				            { \"asSorting\": [  ] },
				            { \"asSorting\": [  ] },";

				if ($_SESSION['prefsProEdition'] == 0) $winners_table_all .= " { \"asSorting\": [  ] },";
				if ($tb == "scores") $winners_table_all .= " { \"asSorting\": [  ] }";

				$winners_table_all .= "
				        ]
				    });
				} );
				</script>
				";

				$winners_table_all .= "<div class=\"bcoem-winner-table\">";
				
				$winners_table_all .= $winners_table_header;
				
				if (!empty($winners_table_body_1)) {
					$winners_table_all .= "<table class=\"table table-responsive table-striped table-bordered\" id=\"sortable".$random1."\">";
					$winners_table_all .= "<thead>";
					$winners_table_all .= $winners_table_head_1;
					$winners_table_all .= "</thead>";
					$winners_table_all .= "<tbody>";
					$winners_table_all .= $winners_table_body_1;
					$winners_table_all .= "</tbody>";
					$winners_table_all .= "</table>";
				} else $winners_table_all .= sprintf("<p>%s</p>",$winners_text_007);

				$winners_table_all .= "</div>";
			
			} // end if (score_count($row_tables['id'],"1",$suffix)) 
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
	
	} while ($row_tables = mysqli_fetch_assoc($tables));

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

