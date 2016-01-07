<?php 
/**
 * Module:      report_tempate.php 
 * Description: Template for custom reports.
 * 
 */

include(DB.'output_post_judge_inventory.db.php');

if (NHC) $base_url = "../";
?>

<script type="text/javascript" language="javascript">
// The following is for demonstration purposes only. 
// Complete documentation and usage at http://www.datatables.net
	$(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[3,'asc']],
			"aoColumns": [
				<?php if ($section == "scores") { ?>null,<?php } ?>
				{ "asSorting": [  ] },
					{ "asSorting": [  ] },
					{ "asSorting": [  ] },
					{ "asSorting": [  ] },
					{ "asSorting": [  ] }
				]
		} );
	} );
</script>
    <div class="page-header">	
		<h1><?php echo $_SESSION['contestName']; ?> Post-Judging Entry Inventory</h1>
	</div><!-- end header -->
    <!-- BEGIN content -->
    <!-- DataTables Table Format -->
    <table class="table table-striped table-bordered" id="sortable">
    <thead>
    	<tr>
        	<th width="5%" nowrap>Entry</th>
            <th width="5%" nowrap>Judging</th>
            <th>Entry Name</th>
            <th width="25%">Category</th>
            <th width="40%">Special Ingredients / Classic Style</th>
            <?php if ($section == "scores") { ?> 
            <th width="5%" nowrap>Score</th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
    <?php do { 
	
		// Query scores table for each entry. If no score and not placing, or if score is not entered at all, put on the list
		
		$query_post_inventory_entry = sprintf("SELECT id,scoreEntry,scorePlace FROM %s WHERE eid='%s'",$prefix."judging_scores",$row_post_inventory['id']);
		$post_inventory_entry = mysql_query($query_post_inventory_entry, $brewing) or die(mysql_error());
		$row_post_inventory_entry = mysql_fetch_assoc($post_inventory_entry);
		$totalRows_post_inventory_entry = mysql_num_rows($post_inventory_entry);
		
		if ((($totalRows_post_inventory_entry > 0) && ($row_post_inventory_entry['scorePlace'] == "")) || ($totalRows_post_inventory_entry == 0)) {
		
	?>
    	<tr>
        	<td><?php echo sprintf("%04s",$row_post_inventory['id']); ?></td> 
            <td><?php echo readable_judging_number($row_post_inventory['brewCategory'],$row_post_inventory['brewJudgingNumber']); ?></td>
            <td><?php echo $row_post_inventory['brewName']; ?></td> 
            <td><?php echo $row_post_inventory['brewCategorySort'].$row_post_inventory['brewSubCategory'].": ".$row_post_inventory['brewStyle']; ?></td>
            <td><?php echo $row_post_inventory['brewInfo']; ?></td> 
            <?php if ($section == "scores") { ?> 
            <td><?php echo $row_post_inventory['scoreEntry']; ?></td>
            <?php } ?>
        </tr>
    <?php 
		}
	} while ($row_post_inventory = mysql_fetch_assoc($post_inventory)); ?>
    </tbody>
    </table>