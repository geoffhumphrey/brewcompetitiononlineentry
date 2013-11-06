<?php 
/**
 * Module:      dropoff.php 
 * Description: Outputs report of entries by dropoff location.
 * 
 */

// ---- REQUIRED Includes if using default CSS s footer and constants ----

require('../paths.php');
require(CONFIG.'bootstrap.php');
require(DB.'dropoff.db.php');
require(LIB.'output.lib.php');
if (NHC) $base_url = "../";

if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['contestName']; ?> organized by <?php echo $_SESSION['contestHost']; ?></title>
<link href="<?php echo $base_url; ?>css/print.css" rel="stylesheet" type="text/css" />
<!-- jquery plugin - required for use with DataTables, FancyBox, DatePicker, TimePicker etc. -->
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.js"></script>
</head>
<body>
<?php if ($section == "default") {  ?>
<div id="content">
	<div id="content-inner">
	 <div id="header">	
		<div id="header-inner"><h1>Entry Totals by Drop-Off Location</h1></div>
	</div><!-- end header -->
    <!-- BEGIN content -->
    <!-- For use with the DataTables jquery plugin -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/sorting.css" type="text/css" />
    <script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript">
    // The following is for demonstration purposes only. 
    // Complete documentation and usage at http://www.datatables.net
        $(document).ready(function() {
            $('#sortable').dataTable( {
                "bPaginate" : false,
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
    <table class="dataTable" id="sortable">
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
	
	//print_r($dropoff_id);
	
	foreach ($dropoff_id as $id) { 
		
		$dropoff_location = dropoff_loc($id);
		$dropoff_location = explode("^",$dropoff_location);
		
		//echo $query_dropoffs."<br>";

		if ($dropoff_location[0] > 0) {
			
			unset($location_count);
			$location_count = location_count($id);
			$all_location_count[] = $location_count;
			
			$dropoff_location_info = dropoff_location_info($id);
			$dropoff_location_info = explode("^",$dropoff_location_info);
	?>
    	<tr>
        	<td class="data"><?php if ($dropoff_location_info[0] < 1) echo $_SESSION['contestShippingName']; else echo $dropoff_location_info[2]; ?></td>
            <td class="data"><?php if ($dropoff_location_info[0] < 1) echo $_SESSION['contestShippingAddress']; else echo $dropoff_location_info[1]; ?></td>
            <td class="data"><?php echo $location_count; ?></td> 
        </tr>
    <?php 
		}
	} // END foreach ($dropoff_id as $id)
?>
    </tbody>
    </table>
    <p>Total: <?php echo array_sum($all_location_count); ?></p>
    
	<?php 
	$dropoff_location_ship = dropoff_location("0");
	$dropoff_location_ship = explode("^",$dropoff_location_ship);

    if ($dropoff_location_ship[0] > 0) {
			
			unset($location_count);
			
			do {
				
				$dropoff_count_user = dropoff_count_user($dropoff_location[1]);
				$location_count[] = $dropoff_count_user;
				
			} while ($row_dropoffs = mysql_fetch_assoc($dropoffs));
			
	?>
    <h1>Entry Totals at the Shipping Location</h1>
    <p><?php echo $_SESSION['contestShippingName']; ?><br /><?php echo $_SESSION['contestShippingAddress']; ?></p>
    <p>Total entries at the shipping location: <?php echo array_sum($location_count); ?></p>
    <?php } 
	?>
    <!-- END content -->
    </div><!-- end content-inner -->
</div><!-- end content -->
<div id="footer">
	<div id="footer-inner">Printed <?php echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], time(), $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "long", "date-time"); ?>.</div>
</div>
<?php } 
if ($section == "check") { ?>
<div id="content">
	<div id="content-inner">
	 <div id="header">	
		<div id="header-inner"><h1>Entries By Drop-Off Location</h1></div>
	</div><!-- end header -->
    <!-- BEGIN content -->
    <?php do { 
	$random = random_generator(5,2);
	$entries_by_dropoff_loc = entries_by_dropoff_loc($row_dropoff['id']);
	$location_count = location_count($row_dropoff['id']);
	if ($location_count > 0) {
	?>
    <h3>Location: <?php echo $row_dropoff['dropLocationName']; ?></h3>
    <p><?php echo $row_dropoff['dropLocation']; ?></p>
    <p>Total Entries at this Location: <?php echo $location_count; ?></p>
    <!-- For use with the DataTables jquery plugin -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/sorting.css" type="text/css" />
    <script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript">
    // The following is for demonstration purposes only. 
    // Complete documentation and usage at http://www.datatables.net
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
    <table class="dataTable" id="sortable<?php echo $random; ?>">
    <thead>
    	<tr>
        	<th class="bdr1B" width="5%" nowrap="nowrap">Entry #</th>
            <th class="bdr1B" width="45%" nowrap="nowrap">Entry Name</th>
            <th class="bdr1B" width="45%" nowrap="nowrap">Participant Name</th>
            <th class="bdr1B" width="5%" nowrap="nowrap">Picked Up?</th>
        </tr>
    </thead>
    <tbody>
    <?php echo $entries_by_dropoff_loc; ?>
    </tbody>
    </table>
    <div id="footer">
		<div id="footer-inner">Printed <?php echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], time(), $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "long", "date-time"); ?>.</div>
	</div>
    <div style="page-break-after:always;"></div>
    <?php
		} // end if ($location_count > 0)
	} while ($row_dropoff = mysql_fetch_assoc($dropoff)) ?>   
    <!-- END content -->
    </div><!-- end content-inner -->
</div><!-- end content -->
<?php } ?>
<script type="text/javascript">
function selfPrint(){
    self.focus();
    self.print();
}
setTimeout('selfPrint()',2000);
html.push('');
</script>
</body>
</html>
<?php } 

else echo "<p>Not available.</p>"; ?>
