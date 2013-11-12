<?php 
/**
 * Module:      bos.sec.php 
 * Description: This module houses public-facing display of the BEST of
 *              show results.
 * 
 */
require(DB.'winners.db.php');

	// Display BOS winners for each applicable style type
	do { $a[] = $row_style_types['id']; } while ($row_style_types = mysql_fetch_assoc($style_types));
	sort($a);
	foreach ($a as $type) {
	
		include(DB.'output_results_download_bos.db.php');
			
			if ($totalRows_bos > 0) { 
			
			$random = random_generator(6,2);
			
	if ($action == "print") echo '<div id="header"><div id="header-inner">'; 
	?>
	<h3>Best of Show &ndash; <?php echo $row_style_type['styleTypeName']; ?></h3>
    <?php if ($action == "print") echo "</div></div>";	?>
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
<?php 	
	} 
}
  
// Special/Custom "Best of" Display
if ($totalRows_sbi > 0) { 
do { 

include(DB.'output_results_download_sbd.db.php');

if ($totalRows_sbd > 0) {
$random = random_generator(6,2);		
?>        
<h3>Best of Show &ndash; <?php echo $row_sbi['sbi_name']; ?></h3>
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
        <td class="data" <?php if ($action == "print") echo 'style="bdr1B"'; ?>><?php echo $brewer_info['0']." ".$brewer_info['1']; if (!empty($entry_info['4'])) echo "<br />Co-Brewer: ".$entry_info['4']; ?></td>
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