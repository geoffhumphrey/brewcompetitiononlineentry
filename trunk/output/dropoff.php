<?php 
/**
 * Module:      report_tempate.php 
 * Description: Template for custom reports.
 * 
 */

// ---- REQUIRED Includes if using default CSS s footer and constants ----
require('../paths.php');
//error_reporting(E_ALL ^ E_NOTICE); // comment out after debugging
error_reporting(0); // uncomment when live
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'authentication_nav.inc.php'); session_start(); 
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php'); 
require(INCLUDES.'constants.inc.php'); // if used, must also use functions.inc.php
require(DB.'common.db.php');
require(DB.'dropoff.db.php');
/*
	$query_brewer = "SELECT * FROM $brewer_db_table ORDER BY brewerLastName ASC";
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
	do {
	$random = rand(0,4);
	$updateSQL = sprintf("UPDATE $brewer_db_table SET 
						 brewerDropOff='%s'
						 WHERE id='%s'", 
						 $random,
						 $row_brewer['id']);
	mysql_select_db($database, $brewing);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
	
	}  
	while ($row_brewer = mysql_fetch_assoc($brewer));
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $row_contest_info['contestName']; ?> organized by <?php echo $row_contest_info['contestHost']; ?></title>
<link href="<?php echo $base_url; ?>/css/print.css" rel="stylesheet" type="text/css" />
<!-- jquery plugin - required for use with DataTables, FancyBox, DatePicker, TimePicker etc. -->
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/jquery.js"></script>
</head>
<body onload="javascript:window.print()">
<?php if ($section == "default") { ?>
<div id="content">
	<div id="content-inner">
	 <div id="header">	
		<div id="header-inner"><h1>Entry Totals by Drop-Off Location</h1></div>
	</div><!-- end header -->
    <!-- BEGIN content -->
    <!-- For use with the DataTables jquery plugin -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>/css/sorting.css" type="text/css" />
    <script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/jquery.dataTables.js"></script>
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
	foreach ($dropoff_id as $id) { 
		$query_dropoffs = sprintf("SELECT uid FROM %s WHERE brewerDropOff='%s'",$brewer_db_table,$id);
		$dropoffs = mysql_query($query_dropoffs, $brewing) or die(mysql_error());
		$row_dropoffs = mysql_fetch_assoc($dropoffs);
		$totalRows_dropoffs = mysql_num_rows($dropoffs);
		
		//echo $query_dropoffs."<br>";

		if ($totalRows_dropoffs > 0) {
			unset($location_count);
			do {
				$query_dropoff_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s'",$brewing_db_table,$row_dropoffs['uid']);
				$dropoff_count = mysql_query($query_dropoff_count, $brewing) or die(mysql_error());
				$row_dropoff_count = mysql_fetch_assoc($dropoff_count);
				$location_count[] = $row_dropoff_count['count'];
				$all_location_running_count[] = $row_dropoff_count['count'];
			} while ($row_dropoffs = mysql_fetch_assoc($dropoffs));
			
			$query_location_info = sprintf("SELECT id,dropLocation,dropLocationName FROM %s WHERE id='%s'",$drop_off_db_table,$id);
			$location_info = mysql_query($query_location_info, $brewing) or die(mysql_error());
			$row_location_info = mysql_fetch_assoc($location_info);
	?>
    	<tr>
        	<td class="data"><?php if ($row_location_info['id'] < 1) echo $row_contest_info['contestShippingName']; else echo $row_location_info['dropLocationName']; ?></td>
            <td class="data"><?php if ($row_location_info['id'] < 1) echo $row_contest_info['contestShippingAddress']; else echo $row_location_info['dropLocation']; ?></td>
            <td class="data"><?php echo array_sum($location_count); ?></td> 
        </tr>
    <?php 
		mysql_free_result($dropoff_count);
		mysql_free_result($dropoffs);
		}
	} // END foreach ($dropoff_id as $id)
	
	?>
    </tbody>
    </table>
    <p>Total: <?php echo array_sum($all_location_running_count); ?></p>
    <?php 
	$query_dropoffs = sprintf("SELECT uid FROM %s WHERE brewerDropOff='0'",$brewer_db_table);
	$dropoffs = mysql_query($query_dropoffs, $brewing) or die(mysql_error());
	$row_dropoffs = mysql_fetch_assoc($dropoffs);
	$totalRows_dropoffs = mysql_num_rows($dropoffs);

    if ($totalRows_dropoffs > 0) {
			unset($location_count);
			do {
			$query_dropoff_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s'",$brewing_db_table,$row_dropoffs['uid']);
			$dropoff_count = mysql_query($query_dropoff_count, $brewing) or die(mysql_error());
			$row_dropoff_count = mysql_fetch_assoc($dropoff_count);
			//echo $query_dropoff_count."<br>";
			$location_count[] = $row_dropoff_count['count'];
			$all_location_running_count[] = $row_dropoff_count['count'];
			} while ($row_dropoffs = mysql_fetch_assoc($dropoffs));
	?>
    <h1>Entry Totals at the Shipping Location</h1>
    <p><?php echo $row_contest_info['contestShippingName']; ?><br /><?php echo $row_contest_info['contestShippingAddress']; ?></p>
    <p>Total entries at the shipping location: <?php echo array_sum($location_count); ?></p>
    <?php } 
	mysql_free_result($dropoff_count);
	mysql_free_result($dropoffs);
	?>
    <!-- END content -->
    </div><!-- end content-inner -->
</div><!-- end content -->
<div id="footer">
	<div id="footer-inner">Printed <?php echo getTimeZoneDateTime($row_prefs['prefsTimeZone'], time(), $row_prefs['prefsDateFormat'], $row_prefs['prefsTimeFormat'], "long", "date-time"); ?>.</div>
</div>
<?php } ?>
<?php if ($section == "check") { ?>
<div id="content">
	<div id="content-inner">
	 <div id="header">	
		<div id="header-inner"><h1>Entries By Drop-Off Location</h1></div>
	</div><!-- end header -->
    <!-- BEGIN content -->
    <?php do { 
	$random = random_generator(5,2);
	$query_dropoffs = sprintf("SELECT uid FROM %s WHERE brewerDropOff='%s'",$brewer_db_table,$row_dropoff['id']);
	$dropoffs = mysql_query($query_dropoffs, $brewing) or die(mysql_error());
	$row_dropoffs = mysql_fetch_assoc($dropoffs);
	$totalRows_dropoffs = mysql_num_rows($dropoffs);
	
	if ($totalRows_dropoffs > 0) {
		unset($location_count);
		$build_rows = "";
			do {
				$query_dropoff_count = sprintf("SELECT * FROM %s WHERE brewBrewerID='%s'",$brewing_db_table,$row_dropoffs['uid']);
				$dropoff_count = mysql_query($query_dropoff_count, $brewing) or die(mysql_error());
				$row_dropoff_count = mysql_fetch_assoc($dropoff_count);
				$totalRows_dropoff_count = mysql_num_rows($dropoff_count);
				//echo $query_dropoff_count."<br>";
				$location_count[] = $totalRows_dropoff_count;
				
				if ($totalRows_dropoff_count > 0) {
					//unset($build_rows);
					do {
						$build_rows .= "
							<tr>
								<td class=\"data bdr1B_gray\">".$row_dropoff_count['id']."</td>
								<td class=\"data bdr1B_gray\">".$row_dropoff_count['brewName']."</td>
								<td class=\"data bdr1B_gray\">".$row_dropoff_count['brewBrewerLastName'].", ".$row_dropoff_count['brewBrewerFirstName']."</td>
								<td class=\"data bdr1B_gray\"><p class=\"box_small\"></p></td>  
							</tr>
					";
				 	} while ($row_dropoff_count = mysql_fetch_assoc($dropoff_count)); 
				}
			} while ($row_dropoffs = mysql_fetch_assoc($dropoffs));
	?>
    <h3>Location: <?php echo $row_dropoff['dropLocationName']; ?></h3>
    <p><?php echo $row_dropoff['dropLocation']; ?></p>
    <p>Total Entries at this Location: <?php echo array_sum($location_count); ?></p>
    <!-- For use with the DataTables jquery plugin -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>/css/sorting.css" type="text/css" />
    <script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/jquery.dataTables.js"></script>
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
    <?php echo $build_rows; ?>
    </tbody>
    </table>
    <div id="footer">
		<div id="footer-inner">Printed <?php echo getTimeZoneDateTime($row_prefs['prefsTimeZone'], time(), $row_prefs['prefsDateFormat'], $row_prefs['prefsTimeFormat'], "long", "date-time"); ?>.</div>
	</div>
    <div style="page-break-after:always;"></div>
    <?php } // end if ($totalRows_dropoffs > 0) ?>
    <?php } while ($row_dropoff = mysql_fetch_assoc($dropoff)) ?>   
    <!-- END content -->
    </div><!-- end content-inner -->
</div><!-- end content -->
<?php } ?>
</body>
</html>