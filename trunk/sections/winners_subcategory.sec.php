<?php
/**
 * Module:      winners_subcategory.sec.php 
 * Description: This module displays the winners entered into the database.
 *              Displays by style subcategory.
 * 
 */
 
$query_styles = "SELECT brewStyleGroup,brewStyleNum,brewStyle FROM $styles_db_table WHERE brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum ASC";
$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
$row_styles = mysql_fetch_assoc($styles);
$totalRows_styles = mysql_num_rows($styles);
do { $a[] = $row_styles['brewStyleGroup']."-".$row_styles['brewStyleNum']."-".$row_styles['brewStyle']; } while ($row_styles = mysql_fetch_assoc($styles));

foreach (array_unique($a) as $style) {
	$style = explode("-",$style);
	$query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s' AND brewReceived='1'", $brewing_db_table,  $style[0], $style[1]);
	$entry_count = mysql_query($query_entry_count, $brewing) or die(mysql_error());
	$row_entry_count = mysql_fetch_assoc($entry_count);
	
	$query_score_count = sprintf("SELECT  COUNT(*) as 'count' FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND b.brewSubCategory='%s' AND a.eid = b.id AND a.scorePlace IS NOT NULL AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style[0], $style[1]);
	$score_count = mysql_query($query_score_count, $brewing) or die(mysql_error());
	$row_score_count = mysql_fetch_assoc($score_count);
	
	//echo $row_score_count['count'];
	//echo $query_score_count;
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
	$query_scores = sprintf("SELECT a.scorePlace, a.scoreEntry, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerClubs FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND b.brewSubCategory='%s' AND a.eid = b.id  AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style[0],$style[1]);
	if ((($action == "print") && ($view == "winners")) || ($action == "default") || ($section == "default")) $query_scores .= " AND a.scorePlace IS NOT NULL"; 
	$query_scores .= " ORDER BY a.scorePlace";
	//echo $query_scores;
	$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
	$row_scores = mysql_fetch_assoc($scores);
	$totalRows_scores = mysql_num_rows($scores);
			
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