<?php
/**
 * Module:      winners_subcategory.sec.php 
 * Description: This module displays the winners entered into the database.
 *              Displays by style subcategory.
 * 
 */
 
$a = styles_active(2);

foreach (array_unique($a) as $style) {
	
	$style = explode("^",$style);
	
	include(DB.'winners_subcategory.db.php');
	
	// Display all winners 
	if ($row_entry_count['count'] > 0) {
		if ($row_entry_count['count'] > 1) $entries = "entries"; else $entries = "entry";
		if ($row_score_count['count'] > 0) { 
?>
<h3>Category <?php echo ltrim($style[0],"0").$style[1].": ".$style[2]." (".$row_entry_count['count']." ".$entries.")"; ?></h3>
 <script type="text/javascript" language="javascript">
 $(document).ready(function() {
	$('#sortable<?php echo $style[0].$style[1]; ?>').dataTable( {
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
			{ "asSorting": [  ] },
			{ "asSorting": [  ] }<?php if ($filter == "scores") { ?>,
			{ "asSorting": [  ] }
			<?php } ?>
			]
		} );
	} );
</script>
<table class="dataTable" id="sortable<?php echo $style[0].$style[1]; ?>">
<thead>
<tr>
	<th class="dataList bdr1B" width="1%" nowrap="nowrap">Place</th>
	<th class="dataList bdr1B" width="25%">Brewer(s)</th>
	<th class="dataList bdr1B" width="25%">Entry Name</th>
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
	if ($row_scores['brewCategorySort'] > 28) $style_long = style_convert($row_scores['brewCategorySort'],1);
	else $style_long = $row_scores['brewStyle'];
?>
<tr>
	<td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php if ($action != "print") echo display_place($row_scores['scorePlace'],2); else echo display_place($row_scores['scorePlace'],1); ?></td>
	<td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php echo $row_scores['brewerFirstName']." ".$row_scores['brewerLastName']; if ($row_scores['brewCoBrewer'] != "") echo "<br>Co-Brewer: ".$row_scores['brewCoBrewer']; ?></td>
	<td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php echo $row_scores['brewName']; ?></td>
	<td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php echo $style.": ".$style_long; ?></td>
	<td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php echo $row_scores['brewerClubs']; ?></td>
	<?php if ($filter == "scores") { ?>
	<td class="data"><?php echo $row_scores['scoreEntry']; ?></td>
	<?php } ?>
</tr>
<?php } while ($row_scores = mysql_fetch_assoc($scores)); ?>
</tbody>
</table>
<?php 	} 
	} 
} 
?>