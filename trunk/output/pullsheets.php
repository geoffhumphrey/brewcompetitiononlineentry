<?php 
require('output.bootstrap.php');
include(INCLUDES.'functions.inc.php');
include(INCLUDES.'url_variables.inc.php');
include(INCLUDES.'db_connect.inc.php');
include(INCLUDES.'version.inc.php');
include(INCLUDES.'headers.inc.php');

$today = date('Y-m-d');
$deadline = $row_contest_info['contestRegistrationDeadline'];

$query_tables = "SELECT * FROM judging_tables ORDER BY tableNumber";
$tables = mysql_query($query_tables, $brewing) or die(mysql_error());
$row_tables = mysql_fetch_assoc($tables);
$totalRows_tables = mysql_num_rows($tables);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if ($tb == "default") { ?><meta http-equiv="refresh" content="0;URL=<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."&tb=true"; ?>" /><?php } ?>
<title>Brew Competition Online Entry and Management - brewcompetition.com</title>
<link href="../css/print.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../js_includes/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../js_includes/jquery.dataTables.js"></script>
</head>
<body <?php if ($tb == "true") echo "onload=\"javascript:window.print()\""; ?>>
<?php 

// Use the following if not using queued judging - delinieates by flight and round
 if ($row_judging_prefs['jPrefsQueued'] == "N") {

function number_of_flights($table_id) { 
    require(CONFIG.'config.php');
    mysql_select_db($database, $brewing);
	
	$query_flights = sprintf("SELECT flightNumber FROM judging_flights WHERE flightTable='%s' ORDER BY flightNumber DESC LIMIT 1", $table_id);
    $flights = mysql_query($query_flights, $brewing) or die(mysql_error());
    $row_flights = mysql_fetch_assoc($flights);
	
	$r = $row_flights['flightNumber'];
	return $r;	
}

function check_flight_number($entry_id,$flight) {
	require(CONFIG.'config.php');
    mysql_select_db($database, $brewing);
	
	$query_flights = sprintf("SELECT flightNumber,flightRound FROM judging_flights WHERE flightEntryID='%s'", $entry_id);
    $flights = mysql_query($query_flights, $brewing) or die(mysql_error());
    $row_flights = mysql_fetch_assoc($flights);
	
	if ($row_flights['flightNumber'] == $flight) $r = $row_flights['flightRound'];
	else $r = "";
	return $r;
	
}


?>
<?php if ($id == "default")
do { 
$flights = number_of_flights($row_tables['id']);
if ($flights > 0) $flights = $flights; else $flights = "0";
?>
<div id="content">
	<div id="content-inner">
    <div id="header">	
		<div id="header-inner">
        	<h1><?php echo "Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']; ?></h1>
            <?php 
			if ($row_tables['tableLocation'] != "") { 
			$query_location = sprintf("SELECT judgingLocName,judgingDate,judgingTime FROM judging_locations WHERE id='%s'", $row_tables['tableLocation']);
			$location = mysql_query($query_location, $brewing) or die(mysql_error());
			$row_location = mysql_fetch_assoc($location);
			?>
            <h2><?php echo $row_location['judgingLocName']." - ".dateconvert($row_location['judgingDate'], 2)."  ".$row_location['judgingTime']; ?></h2>
            <p><?php echo "Entries: ". get_table_info(1,"count_total",$row_tables['id'])."<br>Flights: ".$flights; ?></p>
            <?php } ?>
        </div>
	</div>
    <?php 
	for($i=1; $i<$flights+1; $i++) { ?>
    <h3><?php echo "Table ".$row_tables['tableNumber'].", Flight ".$i; ?></h3>
    <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable<?php echo $row_tables['id']."-".$i; ?>').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[3,'asc'],[1,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
    <table class="dataTable" width="100%" id="sortable<?php echo $row_tables['id']."-".$i; ?>">
    <thead>
    <tr>
    	<th class="dataHeading bdr1B" width="1%">Pull Order</th>
        <th class="dataHeading bdr1B" width="1%">Entry No.</th>
        <th class="dataHeading bdr1B">Style/Sub-Style</th>
        <th class="dataHeading bdr1B" width="1%">Round</th>
        <th class="dataHeading bdr1B" width="1%">Score</th>
        <th class="dataHeading bdr1B" width="1%">Place</th>
    </tr>
    </thead>
    <tbody>
    <?php 
	$a = explode(",", $row_tables['tableStyles']); 
	
	foreach (array_unique($a) as $value) {
		$query_styles = sprintf("SELECT brewStyle FROM styles WHERE id='%s'", $value);
		$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
		$row_styles = mysql_fetch_assoc($styles);
		
		$query_entries = sprintf("SELECT id,brewStyle,brewCategory,brewCategorySort,brewSubCategory,brewInfo FROM brewing WHERE brewStyle='%s' ORDER BY id", $row_styles['brewStyle']);
		$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
		$row_entries = mysql_fetch_assoc($entries);
		$style = $row_entries['brewCategory'].$row_entries['brewSubCategory'];
		do {
			$flight_round = check_flight_number($row_entries['id'],$i);
			if ($flight_round != "") {
	?>
    <tr>
    	<td class="bdr1B_gray"><p class="box">&nbsp;</p></td>
        <td class="data bdr1B_gray"><?php echo $row_entries['id']; ?></td>
        <td class="data bdr1B_gray"><?php echo "<em>".style_convert($row_entries['brewCategorySort'],1)."</em><br>".$style." ".$row_entries['brewStyle']; if (style_convert($style,"3")) echo "<p style='margin-top: 5px;'><strong>Special Ingredients/Classic Style:</strong><br>".$row_entries['brewInfo']."</p>"; ?></td>
        <td class="data bdr1B_gray"><?php echo $flight_round; ?></td>
        <td class="data bdr1B_gray"><p class="box">&nbsp;</p></td>
        <td class="data bdr1B_gray"><p class="box">&nbsp;</p></td>
    </tr>
    <?php 
				}	
		} while ($row_entries = mysql_fetch_assoc($entries));
		mysql_free_result($styles);
		mysql_free_result($entries);
	} // end foreach ?>
    </tbody>
    </table>
    <div style="page-break-after:always;"></div>
    <?php } ?>
    </div>
</div>
<div style="page-break-after:always;"></div>
<?php 	} 
	while ($row_tables = mysql_fetch_assoc($tables)); 
	else { 
	
$flights = number_of_flights($row_tables_edit['id']);
if ($flights > 0) $flights = $flights; else $flights = "0";
?>
<div id="content">
	<div id="content-inner">
    <div id="header">	
		<div id="header-inner">
        	<h1><?php echo "Table ".$row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName']; ?></h1>
            <?php if ($row_tables_edit['tableLocation'] != "") { 
			$query_location = sprintf("SELECT judgingLocName,judgingDate,judgingTime FROM judging_locations WHERE id='%s'", $row_tables_edit['tableLocation']);
			$location = mysql_query($query_location, $brewing) or die(mysql_error());
			$row_location = mysql_fetch_assoc($location);
			?>
            <h2><?php echo $row_location['judgingLocName']." - ".dateconvert($row_location['judgingDate'], 2)."  ".$row_location['judgingTime']; ?></h2>
            <p><?php echo "Entries: ". get_table_info(1,"count_total",$row_tables_edit['id'])."<br>Flights: ".$flights; ?></p>
            <?php } ?>
        </div>
	</div>
     <?php 
	for($i=1; $i<$flights+1; $i++) { ?>
    <h3><?php echo "Table ".$row_tables_edit['tableNumber'].", Flight ".$i; ?></h3>
    <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable<?php echo $row_tables_edit['id']."-".$i; ?>').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[3,'asc'],[1,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
    <table class="dataTable" width="100%" id="sortable<?php echo $row_tables['id']."-".$i; ?>">
    <thead>
    <tr>
    	<th class="dataHeading bdr1B" width="1%">Pull Order</th>
        <th class="dataHeading bdr1B" width="1%">Entry No.</th>
        <th class="dataHeading bdr1B">Style/Sub-Style</th>
        <th class="dataHeading bdr1B" width="1%">Round</th>
        <th class="dataHeading bdr1B" width="1%">Score</th>
        <th class="dataHeading bdr1B" width="1%">Place</th>
    </tr>
    </thead>
    <tbody>
    <?php 
	$a = explode(",", $row_tables_edit['tableStyles']); 
	
	foreach (array_unique($a) as $value) {
		$query_styles = sprintf("SELECT brewStyle FROM styles WHERE id='%s'", $value);
		$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
		$row_styles = mysql_fetch_assoc($styles);
		
		$query_entries = sprintf("SELECT id,brewStyle,brewCategory,brewCategorySort,brewSubCategory,brewInfo FROM brewing WHERE brewStyle='%s' ORDER BY id", $row_styles['brewStyle']);
		$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
		$row_entries = mysql_fetch_assoc($entries);
		$style = $row_entries['brewCategory'].$row_entries['brewSubCategory'];
		do {
			$flight_round = check_flight_number($row_entries['id'],$i);
			if ($flight_round != "") {
	?>
    <tr>
    	<td class="bdr1B_gray"><p class="box">&nbsp;</p></td>
        <td class="data bdr1B_gray"><?php echo $row_entries['id']; ?></td>
        <td class="data bdr1B_gray"><?php echo "<em>".style_convert($row_entries['brewCategorySort'],1)."</em><br>".$style." ".$row_entries['brewStyle']; if (style_convert($style,"3")) echo "<p style='margin-top: 5px;'><strong>Special Ingredients/Classic Style:</strong><br>".$row_entries['brewInfo']."</p>"; ?></td>
        <td class="data bdr1B_gray"><?php echo $flight_round; ?></td>
        <td class="data bdr1B_gray"><p class="box">&nbsp;</p></td>
        <td class="data bdr1B_gray"><p class="box">&nbsp;</p></td>
    </tr>
    <?php 
				}	
		} while ($row_entries = mysql_fetch_assoc($entries));
		mysql_free_result($styles);
		mysql_free_result($entries);
	} // end foreach ?>
    </tbody>
    </table>
    <div style="page-break-after:always;"></div>
    <?php } ?>
    </div>
</div>
<?php 	
	} 
} // end if ($row_judging_prefs['jPrefsQueued'] == "N") 
?>



<?php if ($row_judging_prefs['jPrefsQueued'] == "Y") { ?>
<?php if ($id == "default") do { 
$entry_count = get_table_info(1,"count_total",$row_tables['id']);
?>
<div id="content">
	<div id="content-inner">
    <div id="header">	
		<div id="header-inner">
        	<h1>Table <?php echo $row_tables['tableNumber'].": ".$row_tables['tableName']; ?></h1>
            <?php if ($row_tables['tableLocation'] != "") { 
			$query_location = sprintf("SELECT judgingLocName,judgingDate,judgingTime FROM judging_locations WHERE id='%s'", $row_tables['tableLocation']);
			$location = mysql_query($query_location, $brewing) or die(mysql_error());
			$row_location = mysql_fetch_assoc($location);
			?>
            <h2><?php echo $row_location['judgingLocName']." - ".dateconvert($row_location['judgingDate'], 2)."  ".$row_location['judgingTime']; ?></h2>
            <p><?php echo "Entries: ". $entry_count; ?></p>
            <?php } ?>
        </div>
	</div>
    <?php if ($entry_count > 0) { ?>
     <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable<?php echo $row_tables['id']; ?>').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[1,'asc']],
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
    <table class="dataTable" width="100%" id="sortable<?php echo $row_tables['id']; ?>">
    <thead>
    <tr>
    	<th class="dataHeading bdr1B" width="1%">Pull Order</th>
        <th class="dataHeading bdr1B" width="1%">Entry No.</th>
        <th class="dataHeading bdr1B">Style/Sub-Style</th>
        <th class="dataHeading bdr1B" width="1%">Score</th>
        <th class="dataHeading bdr1B" width="1%">Place</th>
    </tr>
    </thead>
    <tbody>
    <?php 
	$a = explode(",", $row_tables['tableStyles']); 
	foreach (array_unique($a) as $value) {
		$query_styles = sprintf("SELECT brewStyle FROM styles WHERE id='%s'", $value);
		$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
		$row_styles = mysql_fetch_assoc($styles);
		
		$query_entries = sprintf("SELECT id,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewInfo FROM brewing WHERE brewStyle='%s' AND brewPaid='Y' AND brewReceived='Y' ORDER BY id", $row_styles['brewStyle']);
		$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
		$row_entries = mysql_fetch_assoc($entries);
		$style = $row_entries['brewCategory'].$row_entries['brewSubCategory'];
		do {
	?>
    <tr>
    	<td class="bdr1B_gray"><p class="box">&nbsp;</p></td>
        <td class="data bdr1B_gray"><?php echo $row_entries['id']; ?></td>
        <td class="data bdr1B_gray"><?php echo style_convert($row_entries['brewCategorySort'],1)."<br>".$style." ".$row_entries['brewStyle']; if (style_convert($style,"3")) echo "<br><strong>Special Ingredients/Classic Style:</strong><span class='data'>".$row_entries['brewInfo']."</span>"; ?></td>
        <td class="data bdr1B_gray"><p class="box">&nbsp;</p></td>
        <td class="data bdr1B_gray"><p class="box">&nbsp;</p></td>
    </tr>
    <?php } while ($row_entries = mysql_fetch_assoc($entries));
	mysql_free_result($styles);
	mysql_free_result($entries);
	} // end foreach ?>
    </tbody>
    </table>
    <?php } ?>
    </div>
</div>
<div style="page-break-after:always;"></div>
<?php 	} 
	while ($row_tables = mysql_fetch_assoc($tables)); 
	else { 
	$entry_count = get_table_info(1,"count_total",$row_tables_edit['id']);
?>
<div id="content">
	<div id="content-inner">
    <div id="header">	
		<div id="header-inner">
        	<h1>Table <?php echo $row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName']; ?></h1>
            <?php if ($row_tables_edit['tableLocation'] != "") { 
			$query_location = sprintf("SELECT judgingLocName,judgingDate,judgingTime FROM judging_locations WHERE id='%s'", $row_tables_edit['tableLocation']);
			$location = mysql_query($query_location, $brewing) or die(mysql_error());
			$row_location = mysql_fetch_assoc($location);
			?>
            <h2><?php echo $row_location['judgingLocName']." - ".dateconvert($row_location['judgingDate'], 2)."  ".$row_location['judgingTime']; ?></h2>
            <p><?php echo "Entries: ". $entry_count; ?></p>
            <?php } ?>
        </div>
	</div>
    <?php if ($entry_count > 0) { ?>
    <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable<?php echo $row_tables_edit['id']; ?>').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[1,'asc']],
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
    <table class="dataTable" width="100%" id="sortable<?php echo $row_tables_edit['id']; ?>">
    <thead>
    <tr>
    	<th class="dataHeading bdr1B" width="1%">Pull Order</th>
        <th class="dataHeading bdr1B" width="1%">Entry No.</th>
        <th class="dataHeading bdr1B">Style/Sub-Style</th>
        <th class="dataHeading bdr1B" width="1%">Score</th>
        <th class="dataHeading bdr1B" width="1%">Place</th>
    </tr>
    </thead>
    <tbody>
    <?php 
	$a = explode(",", $row_tables_edit['tableStyles']); 
	
	foreach (array_unique($a) as $value) {
		$query_styles = sprintf("SELECT brewStyle FROM styles WHERE id='%s'", $value);
		$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
		$row_styles = mysql_fetch_assoc($styles);
		
		$query_entries = sprintf("SELECT id,brewStyle,brewCategory,brewCategorySort,brewSubCategory,brewInfo FROM brewing WHERE brewStyle='%s' ORDER BY id", $row_styles['brewStyle']);
		$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
		$row_entries = mysql_fetch_assoc($entries);
		$style = $row_entries['brewCategory'].$row_entries['brewSubCategory'];
		do {
	?>
    <tr>
    	<td class="bdr1B_gray"><p class="box">&nbsp;</p></td>
        <td class="data bdr1B_gray"><?php echo $row_entries['id']; ?></td>
        <td class="data bdr1B_gray"><?php echo "<em>".style_convert($row_entries['brewCategorySort'],1)."</em><br>".$style." ".$row_entries['brewStyle']; if (style_convert($style,"3")) echo "<p style='margin-top: 5px;'><strong>Special Ingredients/Classic Style:</strong><br>".$row_entries['brewInfo']."</p>"; ?></td>
        <td class="data bdr1B_gray"><p class="box">&nbsp;</p></td>
        <td class="data bdr1B_gray"><p class="box">&nbsp;</p></td>
    </tr>
    <?php } while ($row_entries = mysql_fetch_assoc($entries));
	mysql_free_result($styles);
	mysql_free_result($entries);
	} // end foreach ?>
    </tbody>
    </table>
    <?php } ?>
    </div>
</div>
<?php 	
	} 
} // end if ($row_judging_prefs['jPrefsQueued'] == "Y") 
?>
</body>
</html>