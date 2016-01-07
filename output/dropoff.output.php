<?php 
/**
 * Module:      dropoff.php 
 * Description: Outputs report of entries by dropoff location.
 * 
 */

include (LIB.'output.lib.php');
include (DB.'dropoff.db.php'); 

if ($go == "default") {  ?>
	<div class="page-header">
        <h1>Entry Totals by Drop-Off Location</h1>
    </div>
	<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : false,
			"bLengthChange" : false,
			"iDisplayLength" :  <?php echo round($_SESSION['prefsRecordPaging']); ?>,
			"sDom": 'rt',
			"bStateSave" : false,
            "bLengthChange" : false,
            "aaSorting": [[0,'asc']],
            "aoColumns": [
                    { "asSorting": [  ] },
                    { "asSorting": [  ] },
					{ "asSorting": [  ] }
                ]
			} );
		} );
	</script>
    <table class="table table-bordered table-striped" id="sortable">
    <thead>
    	<tr>
        	<th>Location Name</th>
            <th>Location Address</th>
            <th>Count</th>
        </tr>
    </thead>
    <tbody>
    <?php 
	do { $dropoff_id[] = $row_dropoff['id']; } while ($row_dropoff = mysql_fetch_assoc($dropoff));
	
	foreach ($dropoff_id as $id) { 
		
		$dropoff_location = dropoff_loc($id);
		$dropoff_location = explode("^",$dropoff_location);

		if ($dropoff_location[0] > 0) {
			
			unset($location_count);
			$location_count = location_count($id);
			$all_location_count[] = $location_count;
			
			$dropoff_location_info = dropoff_location_info($id);
			$dropoff_location_info = explode("^",$dropoff_location_info);
	?>
    	<tr>
        	<td><?php if ($dropoff_location_info[0] < 1) echo $_SESSION['contestShippingName']; else echo $dropoff_location_info[2]; ?></td>
            <td><?php if ($dropoff_location_info[0] < 1) echo $_SESSION['contestShippingAddress']; else echo $dropoff_location_info[1]; ?></td>
            <td><?php echo $location_count; ?></td> 
        </tr>
    <?php 
		}
	} // END foreach ($dropoff_id as $id)
	?>
    </tbody>
    <tfoot>
    	<tr>	
        	<th colspan="2"><span class="pull-right">Total</span></th>
            <th><?php echo array_sum($all_location_count); ?></th>
        </tr>
    </tfoot>
    </table> 
<?php } // end if ($go == "default") ?>

<?php if ($go == "check") { ?>
	<script type="text/javascript" language="javascript">
        $(document).ready(function() {
            $('#sortable<?php echo $random; ?>').dataTable( {
                "bPaginate" : false,
                "sDom": 'rt',
                "bStateSave" : false,
                "bLengthChange" : false,
                "aaSorting": [[2,'asc']],
                "aoColumns": [
                    { "asSorting": [  ] },
                    { "asSorting": [  ] },
					{ "asSorting": [  ] },
					{ "asSorting": [  ] }
                    ]
            } );
        } );
    </script>
	<div class="page-header">
        <h1>Entries By Drop-Off Location</h1>
    </div>
    <?php do { 
	$random = random_generator(5,2);
	$entries_by_dropoff_loc = entries_by_dropoff_loc($row_dropoff['id']);
	$location_count = location_count($row_dropoff['id']);
	if ($location_count > 0) {
	?>
    <h3>Location: <?php echo $row_dropoff['dropLocationName']; ?></h3>
    <p class="lead"><?php echo $row_dropoff['dropLocation']; ?></p>
    <p class="lead"><small>Total Entries at this Location: <?php echo $location_count; ?></small></p>
    
    <table class="table table-bordered table-striped" id="sortable<?php echo $random; ?>">
    <thead>
    	<tr>
        	<th width="5%" nowrap="nowrap">Entry #</th>
            <th width="45%" nowrap="nowrap">Entry Name</th>
            <th width="45%" nowrap="nowrap">Participant Name</th>
            <th width="5%" nowrap="nowrap">Picked Up?</th>
        </tr>
    </thead>
    <tbody>
    <?php echo $entries_by_dropoff_loc; ?>
    </tbody>
    </table>
    <div style="page-break-after:always;"></div>
    <?php
		} // end if ($location_count > 0)
	} while ($row_dropoff = mysql_fetch_assoc($dropoff)) ?>   
    <!-- END content -->
<?php } ?>

