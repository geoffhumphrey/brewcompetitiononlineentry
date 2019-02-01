<?php
/**
 * Module:      bos.sec.php
 * Description: This module houses public-facing display of the BEST of
 *              show results.
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

$display_bos_style_type = FALSE;

require(DB.'winners.db.php');

	// Display BOS winners for each applicable style type
	do { $a[] = $row_style_types['id']; } while ($row_style_types = mysqli_fetch_assoc($style_types));

		sort($a);

		foreach ($a as $type) {

			include (DB.'output_results_download_bos.db.php');

			if ($totalRows_bos > 0) {

				$display_bos_style_type = TRUE;

				$header1_1 = "";
				$table_head1 = "";
				$table_body1 = "";

				// Build headers
				$header1_1 .= sprintf("<h3>%s &ndash; %s</h3>",$label_bos,$row_style_type_1['styleTypeName']);

				// Build table headers
				$table_head1 .= "<tr>";
				$table_head1 .= sprintf("<th nowrap>%s</th>",$label_place);
				$table_head1 .= sprintf("<th>%s</th>",$label_brewer);
				$table_head1 .= sprintf("<th>%s</th>",$label_entry_name);
				$table_head1 .= sprintf("<th>%s</th>",$label_style);
				if ($_SESSION['prefsProEdition'] == 0) $table_head1 .= sprintf("<th>%s</th>",$label_club);
				$table_head1 .= "</tr>";

				do {

					$table_body1 .= "<tr>";

					$table_body1 .= "<td width=\"1%\" nowrap>";
					$table_body1 .= display_place($row_bos['scorePlace'],2);
					$table_body1 .= "</td>";

					$table_body1 .= "<td width=\"25%\">";
					if ($_SESSION['prefsProEdition'] == 1) $table_body1 .= $row_bos['brewerBreweryName'];
					else $table_body1 .= $row_bos['brewerFirstName']." ".$row_bos['brewerLastName'];
					if (($_SESSION['prefsProEdition'] == 0) && ($row_bos['brewCoBrewer'] != "")) $table_body1 .= sprintf("<br>%s: %s",$label_cobrewer,$row_bos['brewCoBrewer']);
					$table_body1 .= "</td>";

					$table_body1 .= "<td width=\"25%\">";
					$table_body1 .= $row_bos['brewName'];
					$table_body1 .= "</td>";

					$table_body1 .= "<td>";
					if ($_SESSION['prefsStyleSet'] == "BA") $table_body1 .= $row_bos['brewStyle'];
					else $table_body1 .= $row_bos['brewCategory'].$row_bos['brewSubCategory'].": ".$row_bos['brewStyle'];
					$table_body1 .= "</td>";

					if ($_SESSION['prefsProEdition'] == 0) {
						$table_body1 .= "<td width=\"20%\">";
						$table_body1 .= $row_bos['brewerClubs'];
						$table_body1 .= "</td>";
					}

					$table_body1 .= "</tr>";

				} while ($row_bos = mysqli_fetch_assoc($bos));

				$random = "";
				$random = random_generator(7,2);



// --------------------------------------------------------------
// Display
// --------------------------------------------------------------

?>
<script type="text/javascript" language="javascript">
 $(document).ready(function() {
	$('#sortable<?php echo $random; ?>').dataTable( {
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
			{ "asSorting": [  ] }
			]
		} );
	} );
</script>
<div class="bcoem-winner-table">
<?php echo $header1_1; ?>
<table class="table table-responsive table-striped table-bordered dataTable" id="sortable<?php echo $random; ?>">
<thead>
	<?php echo $table_head1; ?>
</thead>
<tbody>
	<?php echo $table_body1; ?>
</tbody>
</table>
</div>
<?php
	}
}
// Special/Custom "Best of" Display
if ($totalRows_sbi > 0) {
	do {
		include (DB.'output_results_download_sbd.db.php');
			if ($totalRows_sbd > 0) {

				$display_bos_style_type = TRUE;

				$header2_1 = "";
				$table_head2 = "";
				$table_body2 = "";

				// Build page headers
				$header2_1 .= sprintf("<h3>%s</h3>", $row_sbi['sbi_name']);
				if ($row_sbi['sbi_description'] != "") $header2_1 .= "<p>".$row_sbi['sbi_description']."</p>";

				// Build table headers


				$table_head2 .= "<tr>";
				if ($row_sbi['sbi_display_places'] == "1") $table_head2 .= sprintf("<th width=\"5%%\" nowrap>%s</th>",$label_place);
				$table_head2 .= sprintf("<th>%s</th>",$label_brewer);
				$table_head2 .= sprintf("<th>%s</th>",$label_entry_name);
				$table_head2 .= sprintf("<th>%s</th>",$label_style);
				if ($_SESSION['prefsProEdition'] == 0) $table_head2 .= sprintf("<th>%s</th>",$label_club);
				$table_head2 .= "</tr>";

				// Build table body
				do {
					$brewer_info = explode("^",brewer_info($row_sbd['bid']));
					$entry_info = explode("^",entry_info($row_sbd['eid']));
					$style = $entry_info['5'].$entry_info['2'];

					$table_body2 .= "<tr>";

					if ($row_sbi['sbi_display_places'] == "1") {
						$table_body2 .= "<td width=\"1%\" nowrap>";
						$table_body2 .= display_place($row_sbd['sbd_place'],2);
						$table_body2 .= "</td>";
					}

					$table_body2 .= "<td width=\"25%\">";
					if ($_SESSION['prefsProEdition'] == 0) $table_body2 .= $brewer_info['0']." ".$brewer_info['1'];
					else $table_body2 .= $brewer_info[15];
					if (($_SESSION['prefsProEdition'] == 0) && (!empty($entry_info['4']))) $table_body2 .=  "<br />".$label_cobrewer.": ".$entry_info['4'];
					$table_body2 .= "</td>";

					$table_body2 .= "<td width=\"25%\">";
					$table_body2 .= $entry_info['0'];
					$table_body2 .= "</td>";

					$table_body2 .= "<td>";
					$table_body2 .= $style.": ".$entry_info['3'];
					$table_body2 .= "</td>";

					if ($_SESSION['prefsProEdition'] == 0) {
						$table_body2 .= "<td width=\"25%\">";
						$table_body2 .= $brewer_info['8'];
						$table_body2 .= "</td>";
					}

					$table_body2 .= "</tr>";


				} while ($row_sbd = mysqli_fetch_assoc($sbd));

				$random1 = "";
				$random1 .= random_generator(7,2);


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
			"aaSorting": [],
			"bProcessing" : false,
			"aoColumns": [
				<?php if ($row_sbi['sbi_display_places'] == "1") { ?>{ "asSorting": [  ] },<?php } ?>
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				<?php if ($_SESSION['prefsProEdition'] == 0) { ?>{ "asSorting": [  ] },<?php } ?>
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
<div class="bcoem-winner-table">
<?php echo $header2_1; ?>
<table class="table table-responsive table-bordered table-striped dataTable" id="sortable<?php echo $random1; ?>">
<thead>
	<?php echo $table_head2; ?>
</thead>
<tbody>
	<?php echo $table_body2; ?>
</tbody>
</table>
</div>
<?php }
	} while ($row_sbi = mysqli_fetch_assoc($sbi));
}
?>
<!-- Public Page Rebuild completed 08.26.15 -->


