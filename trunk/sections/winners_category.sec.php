<?php
/**
 * Module:      winners_category.sec.php 
 * Description: This module displays the winners entered into the database.
 *              Displays by style category.
 * 
 */
 

?>
<?php 
// If using BCOE for comp organization, display winners by table
if ($row_prefs['prefsCompOrg'] == "Y") { 

	$query_styles = "SELECT brewStyleGroup FROM $styles_db_table WHERE brewStyleActive='Y' ORDER BY brewStyleGroup ASC";
	$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
	$row_styles = mysql_fetch_assoc($styles);
	$totalRows_styles = mysql_num_rows($styles);
	do { $style[] = $row_styles['brewStyleGroup']; } while ($row_styles = mysql_fetch_assoc($styles));

	foreach (array_unique($style) as $style) {
		$query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategorySort='%s' AND brewReceived='1'", $brewing_db_table,  $style);
		$entry_count = mysql_query($query_entry_count, $brewing) or die(mysql_error());
		$row_entry_count = mysql_fetch_assoc($entry_count);
		
		$query_score_count = sprintf("SELECT  COUNT(*) as 'count' FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND a.eid = b.id AND a.scorePlace IS NOT NULL AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style);
		$score_count = mysql_query($query_score_count, $brewing) or die(mysql_error());
		$row_score_count = mysql_fetch_assoc($score_count);
		
		
		//echo $row_score_count['count'];
		//echo $query_score_count;
		// Display all winners 
	if ($row_entry_count['count'] > 1) $entries = "entries"; else $entries = "entry";
?>
	<h3>Category <?php echo ltrim($style,"0").": ".style_convert($style,"1")." (".$row_entry_count['count']." ".$entries.")"; ?></h3>
    <?php if ($row_score_count['count'] > 0) { ?>
     <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable<?php echo $style; ?>').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']<?php if ($filter == "scores") { ?>,[5,'desc']<?php } ?>],
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
    <table class="dataTable" id="sortable<?php echo $style; ?>">
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
		$query_scores = sprintf("SELECT a.scorePlace, a.scoreEntry, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerClubs FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND a.eid = b.id AND a.scorePlace IS NOT NULL AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style);
		
		if (($action == "print") && ($view == "winners")) $query_scores .= " AND a.scorePlace IS NOT NULL";
		elseif (($action == "default") && ($view == "default")) $query_scores .= " AND a.scorePlace IS NOT NULL";
		$query_scores .= " ORDER BY a.scorePlace";
		
		$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
		$row_scores = mysql_fetch_assoc($scores);
		$totalRows_scores = mysql_num_rows($scores);
				
		do { 
		$style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
	?>
    <tr>
        <td class="data"><?php if ($action != "print") echo display_place($row_scores['scorePlace'],2); else echo display_place($row_scores['scorePlace'],1); ?></td>
        <td class="data"><?php echo $row_scores['brewerFirstName']." ".$row_scores['brewerLastName']; if ($row_scores['brewCoBrewer'] != "") echo "<br>Co-Brewer: ".$row_scores['brewCoBrewer']; ?></td>
        <td class="data"><?php echo $row_scores['brewName']; ?></td>
        <td class="data"><?php echo $style.": ".$row_scores['brewStyle']; ?></td>
        <td class="data"><?php echo $row_scores['brewerClubs']; ?></td>
        <?php if ($filter == "scores") { ?>
        <td class="data"><?php echo $row_scores['scoreEntry']; ?></td>
        <?php } ?>
    </tr>
    <?php } while ($row_scores = mysql_fetch_assoc($scores)); ?>
    </tbody>
    </table>
    <?php 	} else echo "<p>No winners have been reported for this category.</p>";
		} 
	} 
?>