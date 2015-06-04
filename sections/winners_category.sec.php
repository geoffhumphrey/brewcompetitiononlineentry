<?php
/**
 * Module:      winners_category.sec.php 
 * Description: This module displays the winners entered into the database.
 *              Displays by style category.
 * 
 */
 
$a = styles_active(0);
foreach (array_unique($a) as $style) {
	
	include(DB.'winners_category.db.php');
	
	// Display all winners 
if ($row_entry_count['count'] > 1) $entries = "entries"; else $entries = "entry";
if ($row_score_count['count'] > "0")   {
?>
<h3>Category <?php echo ltrim($style,"0").": ".style_convert($style,"1")." (".$row_entry_count['count']." ".$entries.")"; ?></h3>
 <script type="text/javascript" language="javascript">
 $(document).ready(function() {
	$('#sortable<?php echo $style; ?>').dataTable( {
		"bPaginate" : false,
		"sDom": 'rt',
		"bStateSave" : false,
		"bLengthChange" : false,
		"aaSorting": [<?php if ($action == "print") { ?>[0,'asc']<?php } ?>],
		"bProcessing" : false,
		"aoColumns": [
			{ "asSorting": [  ] },
			{ "asSorting": [  ] },
			<?php if (!NHC) { ?>
			{ "asSorting": [  ] },
			<?php } ?>
			{ "asSorting": [  ] },
			{ "asSorting": [  ] }<?php if ($filter == "scores") { ?>,
			{ "asSorting": [  ] }
			<?php } ?>
			]
		} );
	} );
</script>
<table class="dataTable" id="sortable<?php echo $style; ?>">
<thead>
<tr>
	<th class="dataList bdr1B" width="1%" nowrap="nowrap">Place</th>
	<th class="dataList bdr1B" width="25%">Brewer(s)</th>
    <?php if (!NHC) { ?>
	<th class="dataList bdr1B" width="25%">Entry Name</th>
    <?php } ?>
	<th class="dataList bdr1B" width="25%">Style</th>
	<th class="dataList bdr1B">Club</th>
	<?php if ($filter == "scores") { ?>
	<th class="dataList bdr1B" width="1%" nowrap="nowrap">Score</th>
	<?php } ?>
</tr>
</thead>
<tbody>
<?php 
	
	include(DB.'scores.db.php');
			
	do { 
	$style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
?>
<tr>
	<td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php if ($action != "print") echo display_place($row_scores['scorePlace'],2); else echo display_place($row_scores['scorePlace'],1); ?></td>
	<td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php echo $row_scores['brewerFirstName']." ".$row_scores['brewerLastName']; if ($row_scores['brewCoBrewer'] != "") echo "<br>Co-Brewer: ".$row_scores['brewCoBrewer']; ?></td>
	<?php if (!NHC) { ?>
    <td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php echo $row_scores['brewName']; ?></td>
    <?php } ?>
	<td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php echo $style.": ".$row_scores['brewStyle']; ?></td>
	<td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php echo $row_scores['brewerClubs']; ?></td>
	<?php if ($filter == "scores") { ?>
	<td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php echo $row_scores['scoreEntry']; ?></td>
	<?php } ?>
</tr>
<?php } while ($row_scores = mysql_fetch_assoc($scores)); ?>
</tbody>
</table>
<?php 	} // end if > 0
	} // end foreach
?>