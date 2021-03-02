<?php
/**
 * Module:      winners.sec.php
 * Description: This module displays the winners entered into the database.
 *              Displays by table.
 *
 */


/* ---------------- PUBLIC Pages Rebuild Info ---------------------

Beginning with the 1.3.0 release, an effort was begun to separate the programming
layer from the presentation layer for all scripts with this header.

All Public pages have certain variables in common that build the page:

	$primary_page_info = any information related to the page

	$header1_X = an <h2> header on the page
	$header2_X = an <h3> subheader on the page

	$page_infoX = the bulk of the information on the page.
	$print_page_link = the "Print This Page" link
	$competition_logo = display of the competition's logo

	$labelX = the various labels in a table or on a form
	$table_headX = all table headers (column names)
	$table_bodyX = table body info
	$messageX = various messages to display

	$print_page_link = "<p><span class='icon'><img src='".$base_url."images/printer.png' border='0' alt='Print' title='Print' /></span><a id='modal_window_link' href='".$base_url."output/print.php?section=".$section."&amp;action=print' title='Print'>Print This Page</a></p>";
	$competition_logo = "<img src='".$base_url."user_images/".$_SESSION['contestLogo']."' width='".$_SESSION['prefsCompLogoSize']."' style='float:right; padding: 5px 0 5px 5px' alt='Competition Logo' title='Competition Logo' />";

Declare all variables empty at the top of the script. Add on later...
	$primary_page_info = "";
	$winners_table_header = "";
	$winners_table_page_info_1 = "";
	$winners_table_head_2 = "";
	$winners_table_page_info_2 = "";

	$winners_table_head_1 = "";
	$winners_table_body_1 = "";
 
	etc., etc., etc.

 * ---------------- END Rebuild Info --------------------- */
/*
$primary_page_info = "";
$winners_table_header = "";
$winners_table_page_info_1 = "";
$winners_table_head_2 = "";
$winners_table_page_info_2 = "";

$winners_table_head_1 = "";
$winners_table_body_1 = "";
*/

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
		
		$a = explode(",", $row_tables['tableStyles']);

		$entry_count = 0;

		foreach ($a as $value) {

			$query_styles = sprintf("SELECT brewStyleGroup,brewStyleNum FROM %s WHERE id='%s'", $prefix."styles", $value);
			$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
			$row_styles = mysqli_fetch_assoc($styles);

			$query_style_count = sprintf("SELECT COUNT(*) as count FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s' AND brewReceived='1'", $brewing_db_table, $row_styles['brewStyleGroup'], $row_styles['brewStyleNum']);
			$style_count = mysqli_query($connection,$query_style_count) or die (mysqli_error($connection));
			$row_style_count = mysqli_fetch_assoc($style_count);

			$entry_count += $row_style_count['count'];

		}

		$primary_page_info = "";
		$winners_table_header = "";
		$winners_table_page_info_1 = "";
		$winners_table_head_2 = "";
		$winners_table_page_info_2 = "";
		$winners_table_head_1 = "";
		$winners_table_body_1 = "";
		
		if ($entry_count > 0) {

			if ($entry_count > 1) $entries = strtolower($label_entries); else $entries = strtolower($label_entry);

			$winners_table_head_2 .= sprintf("<div class=\"bcoem-winner-table\"><h3>%s %s: %s (%s %s)</h3><p>%s</p></div>",$label_table,$row_tables['tableNumber'],$row_tables['tableName'],$entry_count,$entries,$winners_text_000);

			if (score_count($row_tables['id'],"1",$suffix))	{

				// Build page headers
				$winners_table_header .= sprintf("<h3>%s %s: %s (%s %s)</h3>",$label_table,$row_tables['tableNumber'],$row_tables['tableName'],$entry_count,$entries);

				// Build table headers
				$winners_table_head_1 .= "<tr>";
				$winners_table_head_1 .= sprintf("<th nowrap>%s</th>",$label_place);
				$winners_table_head_1 .= sprintf("<th>%s</th>",$label_brewer);
				$winners_table_head_1 .= sprintf("<th><span class=\"hidden-xs hidden-sm hidden-md\">%s </span>%s</th>",$label_entry,$label_name);
				$winners_table_head_1 .= sprintf("<th>%s</th>",$label_style);
				if ($_SESSION['prefsProEdition'] == 0) $winners_table_head_1 .= sprintf("<th>%s</th>",$label_club);
				if ($tb == "scores") $winners_table_head_1 .= sprintf("<th nowrap>Score</th>",$label_score);
				$winners_table_head_1 .= "</tr>";

				// Build table body
				include (DB.'scores.db.php');

				do {
					if ($_SESSION['prefsStyleSet'] == "AABC") $style = ltrim($row_scores['brewCategory'],"0").".".ltrim($row_scores['brewSubCategory'],"0");
       				else $style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];

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
					if ($_SESSION['prefsProEdition'] == 1) $winners_table_body_1 .= $row_scores['brewerBreweryName'];
					else $winners_table_body_1 .= $row_scores['brewerFirstName']." ".$row_scores['brewerLastName'];
					if (($_SESSION['prefsProEdition'] == 0) && (!empty($row_scores['brewCoBrewer'])) && ($row_scores['brewCoBrewer'] != " ")) $winners_table_body_1 .= "<br>".$label_cobrewer.": ".$row_scores['brewCoBrewer'];
					$winners_table_body_1 .= "</td>";

					$winners_table_body_1 .= "<td width=\"25%\">";
					$winners_table_body_1 .= $row_scores['brewName'];
					$winners_table_body_1 .= "</td>";

					$winners_table_body_1 .= "<td width=\"25%\">";
					if ($_SESSION['prefsStyleSet'] == "BA") $winners_table_body_1 .= $row_scores['brewStyle'];
					else $winners_table_body_1 .= $style.": ".$row_scores['brewStyle'];
					if ((!empty($row_scores['brewInfo'])) && ($section != "results")) {
						$winners_table_body_1 .= " <a href=\"#".$row_scores['id']."\"  tabindex=\"0\" role=\"button\" data-toggle=\"popover\" data-trigger=\"hover\" data-placement=\"auto top\" data-container=\"body\" title=\"".$label_info."\" data-content=\"".str_replace("^", " ", $row_scores['brewInfo'])."\"><span class=\"hidden-xs hidden-sm hidden-md hidden-print fa fa-info-circle\"></span></a></td>";
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



	$random1 = "";
	$random1 .= random_generator(12,1);


	// --------------------------------------------------------------
	// Display
	// --------------------------------------------------------------
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
		<?php echo $winners_table_header; ?>
		<table class="table table-responsive table-striped table-bordered" id="sortable<?php echo $random1; ?>">
		<thead>
			<?php echo $winners_table_head_1; ?>
		</thead>
		<tbody>
			<?php echo $winners_table_body_1; ?>
		</tbody>
		</table>
	</div>
	<?php
			} else echo $winners_table_head_2;
		} // end if ($entry_count > 0);
	} while ($row_tables = mysqli_fetch_assoc($tables));
}

else echo sprintf("<p>%s</p>",$winners_text_001);
?>

<!-- Public Page Rebuild completed 08.26.15 -->

