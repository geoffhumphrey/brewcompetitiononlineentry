<?php 
/**
 * Module:      bos.sec.php 
 * Description: This module houses public-facing display of the BEST of
 *              show results.
 * 
 */
require(DB.'winners.db.php');
?> 

<?php 
	// Display BOS winners for each applicable style type
	do { $a[] = $row_style_types['id']; } while ($row_style_types = mysql_fetch_assoc($style_types));
	sort($a);
	foreach ($a as $type) {
		$query_style_type = sprintf("SELECT * FROM %s WHERE id='%s'", $prefix."style_types", $type);
		$style_type = mysql_query($query_style_type, $brewing) or die(mysql_error());
		$row_style_type = mysql_fetch_assoc($style_type);
		
		if ($row_style_type['styleTypeBOS'] == "Y") { 
			$query_bos = sprintf("SELECT a.scorePlace, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerClubs FROM %s a, %s b, %s c WHERE a.eid = b.id AND a.scorePlace IS NOT NULL AND c.uid = b.brewBrewerID AND scoreType='%s' ORDER BY a.scorePlace", $prefix."judging_scores_bos", $prefix."brewing", $prefix."brewer", $type);
			$bos = mysql_query($query_bos, $brewing) or die(mysql_error());
			//echo $query_bos;
			$row_bos = mysql_fetch_assoc($bos);
			$totalRows_bos = mysql_num_rows($bos);
			
			if ($totalRows_bos > 0) { 
			
			$random = random_generator(6,2);
			
if ($action == "print") { 
	?>
	<div id="header">	
		<div id="header-inner">
    	<?php } ?>        
		<h3>BOS - <?php echo $row_style_type['styleTypeName']; ?></h3>
        <?php 
		if ($action == "print") { 
		?>
        </div>
	</div>
    	<?php } ?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable<?php echo $random; ?>').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [],
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
<table class="dataTable" id="sortable<?php echo $random; ?>">
<thead>
	<tr>
    	<th class="dataList bdr1B" width="1%" nowrap="nowrap">Place</th>
        <th class="dataList bdr1B" width="20%">Brewer(s)</th>
        <th class="dataList bdr1B" width="20%">Entry Name</th>
        <th class="dataList bdr1B" width="20%">Style</th>
        <th class="dataList bdr1B" width="39%">Club</th>
    </tr>
</thead>
<tbody>
	<?php do { 	?>
	<tr>
        <td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php if ($action != "print") echo display_place($row_bos['scorePlace'],2); else echo display_place($row_bos['scorePlace'],1); ?></td>
        <td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php echo $row_bos['brewerFirstName']." ".$row_bos['brewerLastName']; if ($row_bos['brewCoBrewer'] != "") echo "<br>Co-Brewer: ".$row_bos['brewCoBrewer']; ?></td>
        <td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php echo $row_bos['brewName']; ?></td>
        <td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php echo $row_bos['brewCategory'].$row_bos['brewSubCategory'].": ".$row_bos['brewStyle']; ?></td>
        <td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php echo $row_bos['brewerClubs']; ?></td>
    </tr>
    <?php } while ($row_bos = mysql_fetch_assoc($bos)); ?>
</tbody>
</table>
<?php 	} 
	else echo "<h3>BOS - ".$row_style_type['styleTypeName']."</h3><p style='margin: 0 0 40px 0'>No entries are eligible.</p>";
    } 
  }
  
// Special/Custom "Best of" Display
if ($totalRows_sbi > 0) { 
echo "<h2>Overall Winners</h2>";
do { 
$query_sbd = sprintf("SELECT * FROM %s WHERE sid='%s' ORDER BY sbd_place ASC",$prefix."special_best_data",$row_sbi['id']);
$sbd = mysql_query($query_sbd, $brewing) or die(mysql_error());
$row_sbd = mysql_fetch_assoc($sbd);
$totalRows_sbd = mysql_num_rows($sbd);


if ($totalRows_sbd > 0) {
$random = random_generator(6,2);		
?>        
<h3><?php echo $row_sbi['sbi_name']; ?></h3>
<?php if ($row_sbi['sbi_description'] != "") echo "<p>".$row_sbi['sbi_description']."</p>"; ?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable<?php echo $random; ?>').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [],
			"bProcessing" : false,
			"aoColumns": [
				<?php if ($row_sbi['sbi_display_places'] == "1") { ?>		  
				{ "asSorting": [  ] },
				<?php } ?>
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
<table class="dataTable" id="sortable<?php echo $random; ?>">
<thead>
	<tr>
    	<?php if ($row_sbi['sbi_display_places'] == "1") { ?>
        <th class="dataList bdr1B" width="1%" nowrap="nowrap">Place</th>
        <?php } ?>
        <th class="dataList bdr1B" width="25%">Brewer(s)</th>
        <th class="dataList bdr1B" width="25%">Entry Name</th>
        <th class="dataList bdr1B" width="25%">Style</th>
        <th class="dataList bdr1B" width="24%">Club</th>
    </tr>
</thead>
<tbody>
	<?php do { 
	$brewer_info = explode("^",brewer_info($row_sbd['bid']));
	$entry_info = explode("^",entry_info($row_sbd['eid']));
	$style = $entry_info['5'].$entry_info['2'];
	?>
	<tr>
        <?php if ($row_sbi['sbi_display_places'] == "1") { ?><td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php if ($action != "print") echo display_place($row_sbd['sbd_place'],3); else echo display_place($row_sbd['sbd_place'],0); ?></td><?php } ?>
        <td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php echo $brewer_info['0']." ".$brewer_info['1']; if ($row_entries['brewCoBrewer'] != "") echo "<br />Co-Brewer: ".$entry_info['4']; ?></td>
        <td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php echo $entry_info['0']; ?></td>
        <td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php echo $style.": ".$entry_info['3']; ?></td>
        <td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php echo $brewer_info['8']; ?></td>    
    </tr>
    <?php } while ($row_sbd = mysql_fetch_assoc($sbd)); ?>
</tbody>
</table>
<?php }
	} while ($row_sbi = mysql_fetch_assoc($sbi));
} 
?>