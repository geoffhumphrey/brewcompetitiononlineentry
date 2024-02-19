<?php
/**
 * Module:      bos.sec.php
 * Description: This module houses public-facing display of the BEST of
 *              show results.
 *
 */

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

					$entry_name = html_entity_decode($row_bos['brewName'],ENT_QUOTES|ENT_XML1,"UTF-8");
            		$entry_name = htmlentities($entry_name,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5,"UTF-8");

					$table_body1 .= "<tr>";

					$table_body1 .= "<td width=\"1%\" nowrap>";
					$table_body1 .= display_place($row_bos['scorePlace'],2);
					$table_body1 .= "</td>";

					$table_body1 .= "<td width=\"25%\">";
					if ($_SESSION['prefsProEdition'] == 1) $table_body1 .= $row_bos['brewerBreweryName'];
					else $table_body1 .= $row_bos['brewerFirstName']." ".$row_bos['brewerLastName'];
					if (($_SESSION['prefsProEdition'] == 0) && (!empty($row_bos['brewCoBrewer'])) && ($row_bos['brewCoBrewer'] != " ")) $table_body1 .= sprintf("<br>%s: %s",$label_cobrewer,$row_bos['brewCoBrewer']);
					$table_body1 .= "</td>";

					$table_body1 .= "<td width=\"25%\">";
					$table_body1 .= $entry_name;
					$table_body1 .= "</td>";

					$table_body1 .= "<td>";
					if ($_SESSION['prefsStyleSet'] == "BA") $table_body1 .= $row_bos['brewStyle'];
					elseif ($_SESSION['prefsStyleSet'] == "AABC") $table_body1 .= ltrim($row_bos['brewCategory'],"0").".".ltrim($row_bos['brewSubCategory'],"0").": ".$row_bos['brewStyle'];
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
					//$brewer_info = explode("^",brewer_info($row_sbd['bid']));
					//$entry_info = explode("^",entry_info($row_sbd['eid']));
					//$style = $entry_info['5'].$entry_info['2'];

					$entry_name = html_entity_decode($row_sbd['brewName'],ENT_QUOTES|ENT_XML1,"UTF-8");
            		$entry_name = htmlentities($entry_name,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5,"UTF-8");

					$table_body2 .= "<tr>";

					if ($row_sbi['sbi_display_places'] == "1") {
						$table_body2 .= "<td width=\"1%\" nowrap>";
						$table_body2 .= display_place($row_sbd['sbd_place'],2);
						$table_body2 .= "</td>";
					}

					$table_body2 .= "<td width=\"25%\">";
					if ($_SESSION['prefsProEdition'] == 0) $table_body2 .= $row_sbd['brewerFirstName']." ".$row_sbd['brewerLastName'];
					else $table_body2 .= $row_sbd['brewerBreweryName'];
					if (($_SESSION['prefsProEdition'] == 0) && (!empty($row_sbd['brewCoBrewer']))) $table_body2 .=  "<br />".$label_cobrewer.": ".$row_sbd['brewCoBrewer'];
					$table_body2 .= "</td>";

					$table_body2 .= "<td width=\"25%\">";
					$table_body2 .= $entry_name;
					$table_body2 .= "</td>";

					$table_body2 .= "<td>";
					if ($_SESSION['prefsStyleSet'] == "BA") $table_body2 .= $row_sbd['brewStyle'];
					elseif ($_SESSION['prefsStyleSet'] == "AABC") $table_body2 .= ltrim($row_sbd['brewCategory'],"0").".".ltrim($row_sbd['brewSubCategory'],"0").": ".$row_sbd['brewStyle'];
					else $table_body2 .= $row_sbd['brewCategory'].$row_sbd['brewSubCategory'].": ".$row_sbd['brewStyle'];
					$table_body2 .= "</td>";

					if ($_SESSION['prefsProEdition'] == 0) {
						$table_body2 .= "<td width=\"25%\">";
						$table_body2 .= $row_sbd['brewerClubs'];
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


