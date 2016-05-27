<?php 
$section = "sorting";
include(DB.'styles.db.php');

do { $s[] = $row_styles['brewStyleGroup']; } while ($row_styles = mysqli_fetch_assoc($styles));
sort($s);
 
foreach (array_unique($s) as $style) { 
include(DB.'output_sorting.db.php');
if ($totalRows_entries > 0) {
?>
    <div class="page-header">
       <h1><?php echo "Category ".ltrim($style,"0").": ".style_convert($style,1); echo " (".$totalRows_entries." Entries)"; ?></h1>
	</div>
    <?php if ($go == "default") { ?>
     <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable<?php echo $style; ?>').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[5,'asc'],[4,'asc'],[0,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				<?php if ($view == "default") { ?>
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
    <table class="table table-bordered table-striped" id="sortable<?php echo $style; ?>">
    <thead>
    <tr>
    	<th width="5%" nowrap>Entry</th>
    	<?php if ($view == "default") { ?>
        <th width="5%" nowrap>Judging</th>
		<?php } ?>
        <th width="20%">Brewer</th>
        <th>Entry Name</th>
        <th width="20%">Style</th>
        <th width="5%">Sub</th>
        <th width="5%">Contact</th>
        <th width="5%">Paid?</th>
        <th width="5%">Sorted?</th>
        <th width="5%">Location/Box</th>
    </tr>
    </thead>
    <tbody>
    <?php do { 
	$info = brewer_info($row_entries['brewBrewerID']);
	$brewer_info = explode("^",$info);
	if ($brewer_info[14] == "United States") $phone = format_phone_us($brewer_info[2]); else $phone = $brewer_info[2];
	?>
    <tr>
        <td><?php echo sprintf("%04s",$row_entries['id']); ?></td>
        <?php if ($view == "default") { ?>
        <td><?php echo readable_judging_number($row_entries['brewCategory'],$row_entries['brewJudgingNumber']);  ?></td>
        <?php } ?>
        <td><?php echo $row_entries['brewBrewerLastName'].", ".$row_entries['brewBrewerFirstName']; if (isset($row_entries['brewCoBrewer'])) echo "<br>".$row_entries['brewCoBrewer']; ?></td>
        <td><?php echo $row_entries['brewName']; ?></td>
        <td><?php echo $row_entries['brewStyle']; ?></td>
        <td><?php echo $row_entries['brewSubCategory']; ?></td>
        <td><small><?php echo $brewer_info[6]; ?><br><?php echo $phone; ?></small></td>
        <td><?php if ($row_entries['brewPaid'] == "1") echo "<p class=\"box_small\" style=\"vertical-align:middle; text-align: center;\"><span class=\"fa fa-lg fa-check\"></span></p>"; else echo "<p class='box_small'></p>"; ?></td>
        <td><p class="box_small"></p></td>
        <td><p class="box"></p></td>
    </tr>

   <?php } while ($row_entries = mysqli_fetch_assoc($entries)); ?>
    </tbody>
    </table>
<div style="page-break-after:always;"></div>
	<?php } // end if ($go == "default") ?>
    <?php if ($go == "cheat") { ?>
    <h4>Entry Number / Judging Number Cheat Sheet</h4>
     <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable<?php echo $style; ?>').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
    <table class="table table-striped table-bordered" id="sortable<?php echo $style; ?>">
    <thead>
    <tr>
    	<th width="10%" nowrap>Entry</th>
        <th width="10%" nowrap>Judging</th>
        <th>Label Affixed?</th>
    </tr>
    </thead>
    <tbody>
    <?php do { 
	$info = brewer_info($row_entries['brewBrewerID']);
	$brewer_info = explode("^",$info);
	?>
    <tr>
        <td><?php echo sprintf("%04s",$row_entries['id']); ?></td>
        <td><?php echo readable_judging_number($row_entries['brewCategory'],$row_entries['brewJudgingNumber']);  ?></td>
        <td><p class="box_small">&nbsp;</p></td>
    </tr>
    <?php } while ($row_entries = mysqli_fetch_assoc($entries)); ?>
    </tbody>
    </table>
	<div style="page-break-after:always;"></div>  
    <?php } // end if ($go == "cheat") ?>
<?php 
  } // end if ($totalRows_entries > 0)
} // end foreach 
?>
