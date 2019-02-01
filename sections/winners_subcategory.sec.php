<?php
/**
 * Module:      winners_subcategory.sec.php
 * Description: This module displays the winners entered into the database.
 *              Displays by style subcategory.
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
	$header1_1 = "";
	$page_info1 = "";
	$header1_2 = "";
	$page_info2 = "";

	$table_head1 = "";
	$table_body1 = "";

	etc., etc., etc.

 * ---------------- END Rebuild Info --------------------- */

if ($row_scored_entries['count'] > 0) {

	if ($_SESSION['prefsStyleSet'] == "BJCP2008") $category_end = 28;
	else $category_end = 34;

	$a = styles_active(2);
	foreach (array_unique($a) as $style) {

		$style = explode("^",$style);

		include (DB.'winners_subcategory.db.php');

		// Display all winners
		if ($row_entry_count['count'] > 0) {
			if ($row_entry_count['count'] > 1) $entries = "entries"; else $entries = "entry";
			if ($row_score_count['count'] > 0) {


			$primary_page_info = "";
			$header1_1 = "";
			$page_info1 = "";
			$header1_2 = "";
			$page_info2 = "";

			$table_head1 = "";
			$table_body1 = "";

			// Build headers
			if ($_SESSION['prefsStyleSet'] == "BA") $header1_1 .= sprintf("<h3>%s (%s %s)</h3>",$style[2],$row_entry_count['count'],$entries);
			else $header1_1 .= sprintf("<h3>%s %s%s: %s (%s %s)</h3>",$label_category,ltrim($style[0],"0"),$style[1],$style[2],$row_entry_count['count'],$entries);

			// Build table headers
			$table_head1 .= "<tr>";
			$table_head1 .= sprintf("<th nowrap>%s</th>",$label_place);
			$table_head1 .= sprintf("<th>%s</th>",$label_brewer);
			$table_head1 .= sprintf("<th><span class=\"hidden-xs hidden-sm hidden-md\">%s </span>%s</th>",$label_entry,$label_name);
			$table_head1 .= sprintf("<th>%s</th>",$label_style);
			if ($_SESSION['prefsProEdition'] == 0) $table_head1 .= sprintf("<th>%s</th>",$label_club);
			if ($filter == "scores") $table_head1 .= sprintf("<th nowrap>Score</th>",$label_score);
			$table_head1 .= "</tr>";

			// Build table body

			include (DB.'scores.db.php');

			do {

				if ($_SESSION['prefsStyleSet'] == "BA") {

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

					$style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
					if ($row_scores['brewCategorySort'] > $category_end) $style_long = style_convert($row_scores['brewCategorySort'],1);
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
				if ($_SESSION['prefsProEdition'] == 1) $table_body1 .= $row_scores['brewerBreweryName'];
				else $table_body1 .= $row_scores['brewerFirstName']." ".$row_scores['brewerLastName'];
				if (($_SESSION['prefsProEdition'] == 0) && ($row_scores['brewCoBrewer'] != "")) $table_body1 .= "<br>".$label_cobrewer.": ".$row_scores['brewCoBrewer'];
				$table_body1 .= "</td>";

				$table_body1 .= "<td>";
				$table_body1 .= $row_scores['brewName'];
				$table_body1 .= "</td>";

				$table_body1 .= "<td width=\"25%\">";
				$table_body1 .= $style.": ".$style_long;
				if ((!empty($row_scores['brewInfo'])) && ($section != "results")) {
					$table_body1 .= " <a href=\"#".$row_scores['id']."\"  tabindex=\"0\" role=\"button\" data-toggle=\"popover\" data-trigger=\"hover\" data-placement=\"auto top\" data-container=\"body\" title=\"".$label_info."\" data-content=\"".str_replace("^", " ", $row_scores['brewInfo'])."\"><span class=\"hidden-xs hidden-sm hidden-md hidden-print fa fa-info-circle\"></span></a></td>";
				}
				$table_body1 .= "</td>";

				if ($_SESSION['prefsProEdition'] == 0) {
					$table_body1 .= "<td width=\"25%\">";
					$table_body1 .= $row_scores['brewerClubs'];
					$table_body1 .= "</td>";
				}

				if ($filter == "scores") {
					$table_body1 .= "<td width=\"1%\" nowrap>";
					if (!empty($row_scores['scoreEntry'])) $table_body1 .= $row_scores['scoreEntry'];
					else $table_body1 .= "&nbsp;";
					$table_body1 .= "</td>";
				}

				$table_body1 .= "</tr>";

			 } while ($row_scores = mysqli_fetch_assoc($scores));
	$random1 = "";
	$random1 .= random_generator(12,1);
	?>
	<?php echo $header1_1; ?></h3>
	 <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable<?php echo $random1; ?>').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [<?php if ($action == "print") { ?>[0,'asc']<?php } ?>],
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				<?php if ($_SESSION['prefsProEdition'] == 0) { ?>{ "asSorting": [  ] },<?php } ?>
				{ "asSorting": [  ] }<?php if ($filter == "scores") { ?>,
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
	<?php 	}
		}
	}
} // end if score count > 0

else echo sprintf("<p>%s</p>",$winners_text_001);
?>


<!-- Public Page Rebuild completed 08.26.15 -->

