<?php
/**
 * Module:      winners_category.sec.php
 * Description: This module displays the winners entered into the database.
 *              Displays by style category.
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

	$a = json_decode($_SESSION['prefsSelectedStyles'],true);
	$actual_styles = array();

	foreach ($a as $key => $value) {
		$actual_styles[] = $value['brewStyleGroup'];
	}

	foreach (array_unique($actual_styles) as $style) {

		if (!empty($style)) {

			include (DB.'winners_category.db.php');

			/*
			echo $style."<br>";
			echo $query_entry_count."<br>";
			echo $row_entry_count['count']."<br>";
			echo $query_score_count."<br>";
			echo $row_score_count['count']."<br><br>";
			*/

			// Display all winners
			if ($row_entry_count['count'] > 1) $entries = strtolower($label_entries); else $entries = strtolower($label_entry);
			if ($row_score_count['count'] > 0) {

				$primary_page_info = "";
				$header1_1 = "";
				$page_info1 = "";
				$header1_2 = "";
				$page_info2 = "";

				$table_head1 = "";
				$table_body1 = "";

				if ($winner_style_set == "BA") {
					include (INCLUDES.'ba_constants.inc.php');
					$header1_1 .= sprintf("<h3>%s <small>%s %s</small></h3>",$ba_category_names[$style],$row_entry_count['count'],$entries);

				}
				
				else {
					$header1_1 .= sprintf("<h3>%s %s: %s <small>%s %s</small></h3>",$label_category,ltrim($style,"0"),style_convert($style,"1",$base_url,$go),$row_entry_count['count'],$entries);
				}
				
				// Build table headers
				$table_head1 .= "<tr>";
				$table_head1 .= sprintf("<th nowrap>%s</th>",$label_place);
				$table_head1 .= sprintf("<th>%s</th>",$label_brewer);
				$table_head1 .= sprintf("<th><span class=\"hidden-xs hidden-sm hidden-md\">%s </span>%s</th>",$label_entry,$label_name);
				$table_head1 .= sprintf("<th>%s</th>",$label_style);
				if ($_SESSION['prefsProEdition'] == 0) $table_head1 .= sprintf("<th width=\"24%%\">%s</th>",$label_club);
				if ($tb == "scores") $table_head1 .= sprintf("<th width=\"1%%\" nowrap>Score</th>",$label_score);
				$table_head1 .= "</tr>";

				// Build table body
				include (DB.'scores.db.php');

				do {
					if ($winner_style_set == "AABC") $style = ltrim($row_scores['brewCategory'],"0").".".ltrim($row_scores['brewSubCategory'],"0");
       				else $style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];

       				$entry_name = html_entity_decode($row_scores['brewName'],ENT_QUOTES|ENT_XML1,"UTF-8");
    				$entry_name = htmlentities($entry_name,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5,"UTF-8");

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
						if ((isset($row_scores['brewerMHP'])) && (!empty($row_scores['brewerMHP']))) $table_body1 .= " <span data-toggle=\"tooltip\" data-placement=\"top\" title=\"Master Homebrewer Program Participant\" style=\"color: #F2D06C; background-color: #000;\" class=\"badge\">MHP</span>";
					}
					if (($_SESSION['prefsProEdition'] == 0) && (!empty($row_scores['brewCoBrewer'])) && ($row_scores['brewCoBrewer'] != " ")) $table_body1 .= "<br>".$label_cobrewer.": ".$row_scores['brewCoBrewer'];
					$table_body1 .= "</td>";

					$table_body1 .= "<td>";
					$table_body1 .= $entry_name;
					$table_body1 .= "</td>";

					$table_body1 .= "<td width=\"25%\">";
					if ($winner_style_set == "BA") $table_body1 .= $row_scores['brewStyle'];
					else $table_body1 .= $style.": ".$row_scores['brewStyle'];

					if ((!empty($row_scores['brewInfo'])) && ($section != "results") && ($section != "past-winners")) {
						$table_body1 .= " <a href=\"#".$row_scores['id']."\"  tabindex=\"0\" role=\"button\" data-toggle=\"popover\" data-trigger=\"hover\" data-placement=\"auto top\" data-container=\"body\" title=\"".$label_info."\" data-content=\"".str_replace("^", " ", $row_scores['brewInfo'])."\"><span class=\"hidden-xs hidden-sm hidden-md hidden-print fa fa-info-circle\"></span></a>";
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

				 } while ($row_scores = mysqli_fetch_assoc($scores));

	$random1 = "";
	$random1 .= random_generator(12,1);

	// --------------------------------------------------------------
	// Display
	// --------------------------------------------------------------

	echo $header1_1;
	
	?>
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
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				<?php if ($_SESSION['prefsProEdition'] == 0) { ?>{ "asSorting": [  ] },<?php } ?>
				{ "asSorting": [  ] }<?php if ($tb == "scores") { ?>,
				{ "asSorting": [  ] }
				<?php } ?>
				]
			} );
		} );
	</script>
	<div class="bcoem-winner-table">
		<table class="table table-responsive table-striped table-bordered" id="sortable<?php echo $random1; ?>">
		<thead>
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

else echo sprintf("<p>%s</p>",$winners_text_001);
?>

<!-- Public Page Rebuild completed 08.26.15 -->

