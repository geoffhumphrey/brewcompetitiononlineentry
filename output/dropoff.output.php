<?php 
/**
 * Module:      dropoff.php 
 * Description: Outputs report of entries by dropoff location.
 * 
 */

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] > 1))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

include (LIB.'output.lib.php');
include (DB.'dropoff.db.php'); 

if ($go == "default") {  ?>
	<div class="page-header">
        <h1>By Location</h1>
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
        	<th><?php echo $label_name; ?></th>
            <th><?php echo $label_address; ?></th>
            <th><?php echo $label_count; ?></th>
        </tr>
    </thead>
    <tbody>
    <?php 

    $all_location_count = 0;

    if (!empty($_SESSION['contestShippingAddress'])) { 

        $location_count = location_count("0");
        $all_location_count += $location_count;

    ?>
        <tr>
            <td><?php echo $_SESSION['contestShippingName']. " (Shipping Location)"; ?></td>
            <td><?php echo $_SESSION['contestShippingAddress']; ?></td>
            <td><?php echo $location_count; ?></td> 
        </tr>


    <?php }
	do { $dropoff_id[] = $row_dropoff['id']; } while ($row_dropoff = mysqli_fetch_assoc($dropoff));
	
	foreach ($dropoff_id as $id) { 
		
		$dropoff_location = dropoff_loc($id);
		$dropoff_location = explode("^",$dropoff_location);

		if ($dropoff_location[0] < 999) {
			
			unset($location_count);
			$location_count = location_count($id);
			$all_location_count += $location_count;
			
			$dropoff_location_info = dropoff_location_info($id);
			$dropoff_location_info = explode("^",$dropoff_location_info);
	?>
    	<tr>
        	<td><?php echo $dropoff_location_info[2]; ?></td>
            <td><?php echo $dropoff_location_info[1]; ?></td>
            <td><?php echo $location_count; ?></td> 
        </tr>
    <?php 
		}
	} // END foreach ($dropoff_id as $id)
	?>
    </tbody>
    <tfoot>
    	<tr>	
        	<th colspan="2"><span class="pull-right"><?php echo $label_total; ?></span></th>
            <th><?php echo $all_location_count; ?>*</th>
        </tr>
    </tfoot>
    </table>
    <p class="pull-right"><small>*<?php echo sprintf("%s",$output_text_032); ?></small></p>
<?php } // end if ($go == "default") ?>

<?php if ($go == "check") { ?>

    <div class="page-header">
        <h1><?php echo sprintf("%s &ndash; %s, %s", $label_drop_off, $label_shipping_location, $label_drop_offs); ?></h1>
    </div>

    <!-- Shipping  -->
    <?php

    if (!empty($_SESSION['contestShippingAddress'])) {

        $random = random_generator(5,2);
        $entries_by_dropoff_loc = entries_by_dropoff_loc("0");
        $location_count = location_count("0");
    ?>
    <h2><?php if (!empty($_SESSION['contestShippingName'])) echo sprintf("%s (%s)",$_SESSION['contestShippingName'], $label_shipping_location); else echo $label_shipping_location; ?><br><small><?php echo $_SESSION['contestShippingAddress']; ?></small></h2>
    <p class="lead"><?php echo sprintf("%s: %s",$output_text_012,$location_count); ?>*</p>
    <p><small>*<?php echo sprintf("%s",$output_text_032); ?></small></p>
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
    <table class="table table-bordered table-striped" id="sortable<?php echo $random; ?>">
    <thead>
        <tr>
            <th width="5%" nowrap="nowrap"><?php echo $label_entry; ?></th>
            <th width="45%" nowrap="nowrap"><?php echo $label_name; ?></th>
            <th width="45%" nowrap="nowrap"><?php echo $label_entrant; ?></th>
            <th width="5%" nowrap="nowrap"><?php echo $label_received; ?></th>
        </tr>
    </thead>
    <tbody>
    <?php echo $entries_by_dropoff_loc; ?>
    </tbody>
    </table>
    <div style="page-break-after:always;"></div>
    <?php }
    do { 
	$random = random_generator(5,2);
	$entries_by_dropoff_loc = entries_by_dropoff_loc($row_dropoff['id']);
	$location_count = location_count($row_dropoff['id']);
	if ($location_count > 0) {
	?>
    <h2><?php echo $row_dropoff['dropLocationName']; ?><br><small><?php echo $row_dropoff['dropLocation']; ?></small></h2>
    <p class="lead"><?php echo sprintf("%s: %s",$output_text_012,$location_count); ?>*</p>
    <p><small>*<?php echo sprintf("%s",$output_text_032); ?></small></p>
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
    <table class="table table-bordered table-striped" id="sortable<?php echo $random; ?>">
    <thead>
    	<tr>
        	<th width="5%" nowrap="nowrap"><?php echo $label_entry; ?></th>
            <th width="45%" nowrap="nowrap"><?php echo $label_name; ?></th>
            <th width="45%" nowrap="nowrap"><?php echo $label_entrant; ?></th>
            <th width="5%" nowrap="nowrap"><?php echo $label_received; ?></th>
        </tr>
    </thead>
    <tbody>
    <?php echo $entries_by_dropoff_loc; ?>
    </tbody>
    </table>
    <div style="page-break-after:always;"></div>
    <?php
		} // end if ($location_count > 0)
	} while ($row_dropoff = mysqli_fetch_assoc($dropoff)) ?>   
    <!-- END content -->
<?php } ?>

