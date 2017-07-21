<?php 
$section = "sorting";
include (DB.'styles.db.php');

do { $a[] = $row_styles['brewStyleGroup']; } while ($row_styles = mysqli_fetch_assoc($styles));

if (strpos($styleSet,"BABDB") !== false) {
	include (INCLUDES.'ba_constants.inc.php');
	$s = array_merge($a,$ba_beer_categories);
}
else $s = $a;

sort($s,SORT_NUMERIC);

// print_r($s);
 
foreach (array_unique($s) as $style) { 
include (DB.'output_sorting.db.php');
	
// echo $query_entries."<br>";
	
if ($totalRows_entries > 0) {
	if ($totalRows_entries == 1) $total_entries = $totalRows_entries." Entry"; else $total_entries = $totalRows_entries." Entries";
	
	if ((strpos($styleSet,"BABDB") !== false) && ($style < 28))  $title = sprintf("%s<br><small><em class=\"text-muted\">%s</em></small>", $ba_category_names[$style], $total_entries);
	elseif ((strpos($styleSet,"BABDB") !== false) && ($style > 28))  $title = sprintf("%s<br><small><em class=\"text-muted\">%s</em></small>", style_convert($style,1), $total_entries);
	else $title = sprintf("%s %s: %s<br><small><em class=\"text-muted\">%s</em></small>", $label_category, ltrim($style,"0"), style_convert($style,1), $total_entries);
	
	
?>
    <div class="page-header">
       <h2><?php echo $title; ?></h2>
	</div>
    <?php if ($go == "default") { ?>
     <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable<?php echo $style; ?>').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			<?php if ($view == "default") { ?>
			"aaSorting": [[4,'asc'],[1,'asc']],
			<?php } ?>
			<?php if ($view == "entry") { ?>
			"aaSorting": [[4,'asc'],[0,'asc']],
			<?php } ?>
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
    	<th width="5%" nowrap><?php echo $label_entry; ?></th>
    	<?php if ($view == "default") { ?>
        <th width="5%" nowrap><?php echo $label_judging; ?></th>
		<?php } ?>
        <th width="20%"><?php echo $label_brewer; ?></th>
        <th><?php echo $label_name; ?></th>
        <th width="20%"><?php echo $label_style; ?></th>
        <th width="5%"><?php echo $label_subcategory; ?></th>
        <th width="5%"><?php echo $label_contact; ?></th>
        <th width="5%"><?php echo $label_paid; ?></th>
        <th width="5%"><?php echo $label_sorted; ?></th>
        <th width="5%"><?php echo $label_box; ?></th>
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
        <td><span class="hidden"><?php echo $row_entries['brewCategorySort'].$row_entries['brewSubCategory']; ?></span><?php echo $row_entries['brewStyle']; ?></td>
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
    <h4><?php echo $output_text_021; ?></h4>
     <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable<?php echo $style; ?>').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc'],[1,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				<?php if (strpos($styleSet,"BABDB") === false) { ?>{ "asSorting": [  ] },<?php } ?>
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
		<?php if (strpos($styleSet,"BABDB") === false) { ?><th width="5%" nowrap><?php echo $label_subcategory; ?></th><?php } ?>
    	<th width="20%" nowrap><?php echo $label_entry; ?></th>
        <th width="20%" nowrap><?php echo $label_judging; ?></th>
        <th><?php echo $label_affixed; ?></th>
    </tr>
    </thead>
    <tbody>
    <?php do { 
	$info = brewer_info($row_entries['brewBrewerID']);
	$brewer_info = explode("^",$info);
	?>
    <tr>
		<?php if (strpos($styleSet,"BABDB") === false) { ?><td><span class="hidden"><?php echo $row_entries['brewCategorySort'].$row_entries['brewSubCategory']; ?></span><?php echo $row_entries['brewSubCategory']; ?></td><?php } ?>
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
