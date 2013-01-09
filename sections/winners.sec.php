
<?php
/**
 * Module:      winners.sec.php 
 * Description: This module displays the winners entered into the database.
 *              Displays by table.
 * 
 */

do { 
$entry_count = get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default");
if ($entry_count > 0) { 
	if ($entry_count > 1) $entries = "entries"; else $entries = "entry";
		if (score_count($row_tables['id'],"1"))	{
			if ($action == "print") { 
?>
<div id="header">	
	<div id="header-inner">
	<?php } ?>
<?php 
	if ($action == "print") { 
	?>
	</div>
</div>
<?php } ?>


 <script type="text/javascript" language="javascript">
 $(document).ready(function() {
	$('#sortable<?php echo $row_tables['id']; ?>').dataTable( {
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
<table class="dataTable" id="sortable<?php echo $row_tables['id']; ?>">
<thead>
<tr>
	<th class="dataList bdr1B" width="1%" nowrap="nowrap">Place</th>
	<th class="dataList bdr1B" width="25%">Brewer(s)</th>
	<th class="dataList bdr1B">Entry Name</th>
	<th class="dataList bdr1B" width="25%">Style</th>
	<th class="dataList bdr1B" width="25%">Club</th>
	<?php if ($filter == "scores") { ?>
	<th width="1%" class="dataHeading bdr1B">Score</th>
	<?php } ?>
</tr>
</thead>
<tbody>
<?php 
	$query_scores = sprintf("SELECT a.scorePlace, a.scoreEntry, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerClubs FROM %s a, %s b, %s c WHERE scoreTable='%s' AND a.eid = b.id AND c.uid = b.brewBrewerID", $prefix."judging_scores", $prefix."brewing", $prefix."brewer", $row_tables['id']);
	if ((($action == "print") && ($view == "winners")) || ($action == "default") || ($section == "default")) $query_scores .= " AND a.scorePlace IS NOT NULL";
	$query_scores .= " ORDER BY a.scorePlace";
	$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
	$row_scores = mysql_fetch_assoc($scores);
	$totalRows_scores = mysql_num_rows($scores);
	
			
	do { 
	$style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
?>
<tr>
	<td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php if ($action != "print") echo display_place($row_scores['scorePlace'],2); else echo display_place($row_scores['scorePlace'],1); ?></td>
	<td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php echo $row_scores['brewerFirstName']." ".$row_scores['brewerLastName']; if ($row_scores['brewCoBrewer'] != "") echo "<br>Co-Brewer: ".$row_scores['brewCoBrewer']; ?></td>
	<td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php echo $row_scores['brewName']; ?></td>
	<td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php echo $style.": ".$row_scores['brewStyle']; ?></td>
	<td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php echo $row_scores['brewerClubs']; ?></td>
	<?php if ($filter == "scores") { ?>
	<td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php echo $row_scores['scoreEntry']; ?></td>
	<?php } ?>
</tr>
<?php } while ($row_scores = mysql_fetch_assoc($scores)); ?>
</tbody>
</table>
<?php 
		} else echo "<h3>Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']." (".$entry_count." ".$entries.")</h3><p>No winners have been entered yet for this table. Please check back later.</p>";
	} // end if ($entry_count > 0);
} while ($row_tables = mysql_fetch_assoc($tables));

?>