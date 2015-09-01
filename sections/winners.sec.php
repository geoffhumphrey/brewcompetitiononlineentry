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
	
	$print_page_link = "<p><span class='icon'><img src='".$base_url."images/printer.png' border='0' alt='Print' title='Print' /></span><a id='modal_window_link' class='data' href='".$base_url."output/print.php?section=".$section."&amp;action=print' title='Print'>Print This Page</a></p>";
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
 


do { 
$entry_count = get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default");

if ($entry_count > 0) { 
	
	if ($entry_count > 1) $entries = "entries"; else $entries = "entry";
		
		if (score_count($row_tables['id'],"1"))	{
			
			$primary_page_info = "";
			$header1_1 = "";
			$page_info1 = "";
			$header1_2 = "";
			$page_info2 = "";
		
			$table_head1 = "";
			$table_body1 = "";
			
			// Build page headers
			$header1_1 .= "<h3>Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']." (".$entry_count." ".$entries.")</h3>";
			$header1_2 .= "<h3>Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']." (".$entry_count." ".$entries.")</h3><p>No winners have been entered yet for this table. Please check back later.</p>";
			
			// Build table headers
			$table_head1 .= "<tr>";
			$table_head1 .= "<th class='dataList bdr1B' width='1%' nowrap='nowrap'>Place</th>";
			$table_head1 .= "<th class='dataList bdr1B' width='25%'>Brewer(s)</th>";
			$table_head1 .= "<th class='dataList bdr1B' width='25%'>Entry Name</th>";
			$table_head1 .= "<th class='dataList bdr1B' width='25%'>Style</th>";
			$table_head1 .= "<th class='dataList bdr1B'>Club</th>";
			if ($filter == "scores") $table_head1 .= "<th class='dataList bdr1B' width='1%' nowrap='nowrap'>Score</th>";
			$table_head1 .= "</tr>";
			
			// Build table body
			include(DB.'scores.db.php');
			
			do { 
				$style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
				
				$table_body1 .= "<tr>";
				
				if ($action == "print") { 
					$table_body1 .= "<td class='data' style='bdr1B'>";
					$table_body1 .= display_place($row_scores['scorePlace'],1);
					$table_body1 .= "</td>";
				}
				
				else {
					$table_body1 .= "<td class='data'>";
					$table_body1 .= display_place($row_scores['scorePlace'],2);
					$table_body1 .= "</td>";
				}
				
				if ($action == "print") $table_body1 .= "<td class='data' style='bdr1B'>";
				else $table_body1 .= "<td class='data'>";
				$table_body1 .= $row_scores['brewerFirstName']." ".$row_scores['brewerLastName'];
				if ($row_scores['brewCoBrewer'] != "") $table_body1 .= "<br>Co-Brewer: ".$row_scores['brewCoBrewer'];
				$table_body1 .= "</td>";
				
				if ($action == "print") $table_body1 .= "<td class='data' style='bdr1B'>";
				else $table_body1 .= "<td class='data'>";
				$table_body1 .= $row_scores['brewName'];
				$table_body1 .= "</td>";
				
				if ($action == "print") $table_body1 .= "<td class='data' style='bdr1B'>";
				else $table_body1 .= "<td class='data'>";
				$table_body1 .= $style.": ".$row_scores['brewStyle'];
				$table_body1 .= "</td>";
				
				if ($action == "print") $table_body1 .= "<td class='data' style='bdr1B'>";
				else $table_body1 .= "<td class='data'>";
				$table_body1 .= $row_scores['brewerClubs'];
				$table_body1 .= "</td>";
				
				if ($filter == "scores") { 
					if ($action == "print") $table_body1 .= "<td class='data' style='bdr1B'>";
					else $table_body1 .= "<td class='data'>";
					$table_body1 .= $row_scores['scoreEntry'];
					$table_body1 .= "</td>";
				}
				
				$table_body1 .= "</tr>";
				
			 } while ($row_scores = mysql_fetch_assoc($scores));



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
		"aaSorting": [[0,'asc']],
		"bProcessing" : false,
		"aoColumns": [
			{ "asSorting": [  ] },
			{ "asSorting": [  ] },
			{ "asSorting": [  ] },
			{ "asSorting": [  ] },
			{ "asSorting": [  ] }<?php if ($filter == "scores") { ?>,
			{ "asSorting": [  ] }
			<?php } ?>
			]
		} );
	} );
</script>
<?php echo $header1_1; ?>
<table class="dataTable" id="sortable<?php echo $random1; ?>">
<thead>
	<?php echo $table_head1; ?>
</thead>
<tbody>
	<?php echo $table_body1; ?>
</tbody>
</table>
<?php 
		} else echo $header1_2;
	} // end if ($entry_count > 0);
} while ($row_tables = mysql_fetch_assoc($tables));

?>

<!-- Public Page Rebuild completed 08.26.15 --> 

