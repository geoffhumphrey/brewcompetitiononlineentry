<?php 
require('output.bootstrap.php');
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'url_variables.inc.php');
require(DB.'common.db.php');
include(DB.'admin_common.db.php');
require(INCLUDES.'version.inc.php');
require(INCLUDES.'headers.inc.php');

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
<?php if (($go == "judging_tables") && ($id == "default"))
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
            <h2><?php echo $row_location['judgingLocName']." - ".date_convert($row_location['judgingDate'], 2, $row_prefs['prefsDateFormat'])."  ".$row_location['judgingTime']; ?></h2>
            <p><?php echo "Entries: ". get_table_info(1,"count_total",$row_tables['id'],$dbTable)."<br>Flights: ".$flights; ?></p>
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
			"aaSorting": [[2,'asc'],[1,'asc']],
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
		$style = $row_entries['brewCategorySort'].$row_entries['brewSubCategory'];
		do {
			$flight_round = check_flight_number($row_entries['id'],$i);
			if ($flight_round != "") {
	?>
    <tr>
    	<td class="bdr1B_gray"><p class="box">&nbsp;</p></td>
        <td class="data bdr1B_gray"><?php echo $row_entries['id']; ?></td>
        <td class="data bdr1B_gray"><?php echo $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1)."</em>"; if (style_convert($style,"3")) echo "<p style='margin-top: 5px;'><strong>Special Ingredients/Classic Style:</strong><br>".$row_entries['brewInfo']."</p>"; ?></td>
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

if (($go == "judging_tables") && ($id != "default")) { 
	
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
            <h2><?php echo $row_location['judgingLocName']." - ".date_convert($row_location['judgingDate'], 2, $row_prefs['prefsDateFormat'])."  ".$row_location['judgingTime']; ?></h2>
            <p><?php echo "Entries: ". get_table_info(1,"count_total",$row_tables_edit['id'],$dbTable)."<br>Flights: ".$flights; ?></p>
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
			"aaSorting": [[3,'asc'],[2,'asc'],[1,'asc']],
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
		$style = $row_entries['brewCategorySort'].$row_entries['brewSubCategory'];
		do {
			$flight_round = check_flight_number($row_entries['id'],$i);
			if ($flight_round != "") {
	?>
    <tr>
    	<td class="bdr1B_gray"><p class="box">&nbsp;</p></td>
        <td class="data bdr1B_gray"><?php echo $row_entries['id']; ?></td>
        <td class="data bdr1B_gray"><?php echo $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1)."</em>"; if (style_convert($style,"3")) echo "<p style='margin-top: 5px;'><strong>Special Ingredients/Classic Style:</strong><br>".$row_entries['brewInfo']."</p>"; ?></td>
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
	} // end if (($go == "judging_tables") && ($id != "default")) 
} // end if ($row_judging_prefs['jPrefsQueued'] == "N") 
?>



<?php if ($row_judging_prefs['jPrefsQueued'] == "Y") { ?>
<?php if (($go == "judging_tables") && ($id == "default"))  
do { 
$entry_count = get_table_info(1,"count_total",$row_tables['id'],$dbTable);
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
            <h2><?php echo $row_location['judgingLocName']." - ".date_convert($row_location['judgingDate'], 2, $row_prefs['prefsDateFormat'])."  ".$row_location['judgingTime']; ?></h2>
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
			"aaSorting": [[2,'asc'],[1,'asc']],
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
		$style = $row_entries['brewCategorySort'].$row_entries['brewSubCategory'];
		do {
	?>
    <tr>
    	<td class="bdr1B_gray"><p class="box">&nbsp;</p></td>
        <td class="data bdr1B_gray"><?php echo $row_entries['id']; ?></td>
        <td class="data bdr1B_gray"><?php echo $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1)."</em>"; if (style_convert($style,"3")) echo "<br><strong>Special Ingredients/Classic Style:</strong><span class='data'>".$row_entries['brewInfo']."</span>"; ?></td>
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
if (($go == "judging_tables") && ($id != "default")) { 
$entry_count = get_table_info(1,"count_total",$row_tables_edit['id'],$dbTable);
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
            <h2><?php echo $row_location['judgingLocName']." - ".date_convert($row_location['judgingDate'], 2, $row_prefs['prefsDateFormat'])."  ".$row_location['judgingTime']; ?></h2>
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
			"aaSorting": [[2,'asc'],[1,'asc']],
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
		$style = $row_entries['brewCategorySort'].$row_entries['brewSubCategory'];
		do {
	?>
    <tr>
    	<td class="bdr1B_gray"><p class="box">&nbsp;</p></td>
        <td class="data bdr1B_gray"><?php echo $row_entries['id']; ?></td>
        <td class="data bdr1B_gray"><?php echo $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1)."</em>"; if (style_convert($style,"3")) echo "<p style='margin-top: 5px;'><strong>Special Ingredients/Classic Style:</strong><br>".$row_entries['brewInfo']."</p>"; ?></td>
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
	} // end if (($go == "judging_tables" && ($id != "default"))
} // end if ($row_judging_prefs['jPrefsQueued'] == "Y") 
?>

<?php 
// Printing BOS Pull Sheets by Style Type
if (($go == "judging_scores_bos") && ($id == "default")) { ?>
<div id="content">
	<div id="content-inner">
<?php 
do { $a[] = $row_style_types['id']; } while ($row_style_types = mysql_fetch_assoc($style_types));
sort($a);
foreach ($a as $type) {
	$query_style_type = "SELECT * FROM style_types WHERE id='$type'";
	$style_type = mysql_query($query_style_type, $brewing) or die(mysql_error());
	$row_style_type = mysql_fetch_assoc($style_type);

if ($row_style_type['styleTypeBOS'] == "Y") { 

	$query_bos = "SELECT * FROM judging_scores";
	if ($row_style_type['styleTypeBOSMethod'] == "1") $query_bos .= " WHERE scoreType='$type' AND scorePlace='1'";
	if ($row_style_type['styleTypeBOSMethod'] == "2") $query_bos .= " WHERE scoreType='$type' AND (scorePlace='1' OR scorePlace='2')";
	if ($row_style_type['styleTypeBOSMethod'] == "3") $query_bos .= " WHERE (scoreType='$type' AND scorePlace='1') OR (scoreType='$type' AND scorePlace='2') OR (scoreType='$type' AND scorePlace='3')";
	$query_bos .= " ORDER BY scoreTable ASC";

	$bos = mysql_query($query_bos, $brewing) or die(mysql_error());
	$row_bos = mysql_fetch_assoc($bos);
	$totalRows_bos = mysql_num_rows($bos);

?>
    <div id="header">	
		<div id="header-inner">
        	<h1>BOS Entries - <?php echo $row_style_type['styleTypeName']; ?></h1>
        </div>
	</div>
<?php if ($totalRows_bos > 0) { ?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable<?php echo $type; ?>').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[2,'asc'],[1,'asc']],
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
<table class="dataTable" id="sortable<?php echo $type; ?>">
<thead>
    <tr>
    	<th class="dataHeading bdr1B" width="1%">Pull Order</th>
        <th class="dataHeading bdr1B" width="1%">Entry No.</th>
        <th class="dataHeading bdr1B" width="1%">From Table #</th>
        <th class="dataHeading bdr1B">Style/Sub-Style</th>
        <th class="dataHeading bdr1B" width="1%">Score</th>
        <th class="dataHeading bdr1B" width="1%">Place</th>
    </tr>
    </thead>
<tbody>
	<?php do {
	$query_entries_1 = sprintf("SELECT brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewInfo FROM brewing WHERE id='%s'", $row_bos['eid']);
	$entries_1 = mysql_query($query_entries_1, $brewing) or die(mysql_error());
	$row_entries_1 = mysql_fetch_assoc($entries_1);
	$style = $row_entries_1['brewCategorySort'].$row_entries_1['brewSubCategory'];
	
	$query_tables_1 = sprintf("SELECT id,tableName,tableNumber FROM judging_tables WHERE id='%s'", $row_bos['scoreTable']);
	$tables_1 = mysql_query($query_tables_1, $brewing) or die(mysql_error());
	$row_tables_1 = mysql_fetch_assoc($tables_1);
	$totalRows_tables = mysql_num_rows($tables_1);
	?>
    <tr>
    	<td class="bdr1B_gray"><p class="box">&nbsp;</p></td>
        <td class="data bdr1B_gray"><?php echo $row_bos['eid']; ?></td>
        <td class="data bdr1B_gray"><?php echo $row_tables_1['tableNumber']; ?></td>
        <td class="data bdr1B_gray"><?php echo "<em>".$row_entries_1['brewCategorySort']."</em>".$style." ".$row_entries_1['brewStyle']; if (style_convert($style,"3")) echo "<p style='margin-top: 5px;'><strong>Special Ingredients/Classic Style:</strong><br>".$row_entries_1['brewInfo']."</p>"; ?></td>
        <td class="data bdr1B_gray"><p class="box">&nbsp;</p></td>
        <td class="data bdr1B_gray"><p class="box">&nbsp;</p></td>
    </tr>
    <?php } while ($row_bos = mysql_fetch_assoc($bos)); 
	mysql_free_result($bos);
	mysql_free_result($style_type);
	mysql_free_result($tables_1);
	mysql_free_result($entries_1);
	?>
</tbody>
</table>
<div style="page-break-after:always;"></div>
<?php } else echo "<p style='margin: 0 0 40px 0'>No entries are eligible.</p>"; 
} 
?>
<?php } ?>
	</div>
</div>
<?php } // end if (($go == "judging_scores_bos") && ($id != "default")) ?>

<?php 
// Printing BOS Pull Sheets by Style Type
if (($go == "judging_scores_bos") && ($id != "default")) { ?>
<div id="content">
	<div id="content-inner">
<?php 
	$query_style_type = "SELECT * FROM style_types WHERE id='$id'";
	$style_type = mysql_query($query_style_type, $brewing) or die(mysql_error());
	$row_style_type = mysql_fetch_assoc($style_type);
	
if ($row_style_type['styleTypeBOS'] == "Y") { 

	$query_bos = "SELECT * FROM judging_scores";
	if ($row_style_type['styleTypeBOSMethod'] == "1") $query_bos .= " WHERE scoreType='$id' AND scorePlace='1'";
	if ($row_style_type['styleTypeBOSMethod'] == "2") $query_bos .= " WHERE scoreType='$id' AND (scorePlace='1' OR scorePlace='2')";
	if ($row_style_type['styleTypeBOSMethod'] == "3") $query_bos .= " WHERE (scoreType='$id' AND scorePlace='1') OR (scoreType='$id' AND scorePlace='2') OR (scoreType='$id' AND scorePlace='3')";
	$query_bos .= " ORDER BY scoreTable ASC";
	$bos = mysql_query($query_bos, $brewing) or die(mysql_error());
	$row_bos = mysql_fetch_assoc($bos);
	$totalRows_bos = mysql_num_rows($bos);

?>
    <div id="header">	
		<div id="header-inner">
        	<h1>BOS Entries - <?php echo $row_style_type['styleTypeName']; ?></h1>
        </div>
	</div>
<?php if ($totalRows_bos > 0) { ?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
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
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
<table class="dataTable" id="sortable">
<thead>
    <tr>
    	<th class="dataHeading bdr1B" width="1%">Pull Order</th>
        <th class="dataHeading bdr1B" width="1%">Entry No.</th>
        <th class="dataHeading bdr1B" width="1%">From Table #</th>
        <th class="dataHeading bdr1B">Style/Sub-Style</th>
        <th class="dataHeading bdr1B" width="1%">Score</th>
        <th class="dataHeading bdr1B" width="1%">Place</th>
    </tr>
    </thead>
<tbody>
	<?php do {
	$query_entries_1 = sprintf("SELECT brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewInfo FROM brewing WHERE id='%s'", $row_bos['eid']);
	$entries_1 = mysql_query($query_entries_1, $brewing) or die(mysql_error());
	$row_entries_1 = mysql_fetch_assoc($entries_1);
	$style = $row_entries_1['brewCategorySort'].$row_entries_1['brewSubCategory'];
	
	$query_tables_1 = sprintf("SELECT id,tableName,tableNumber FROM judging_tables WHERE id='%s'", $row_bos['scoreTable']);
	$tables_1 = mysql_query($query_tables_1, $brewing) or die(mysql_error());
	$row_tables_1 = mysql_fetch_assoc($tables_1);
	$totalRows_tables = mysql_num_rows($tables_1);
	?>
    <tr>
    	<td class="bdr1B_gray"><p class="box">&nbsp;</p></td>
        <td class="data bdr1B_gray"><?php echo $row_bos['eid']; ?></td>
        <td class="data bdr1B_gray"><?php echo $row_tables_1['tableNumber']; ?></td>
        <td class="data bdr1B_gray"><?php echo "<em>".style_convert($row_entries_1['brewCategorySort'],1)."</em>".$style." ".$row_entries_1['brewStyle']; if (style_convert($style,"3")) echo "<p style='margin-top: 5px;'><strong>Special Ingredients/Classic Style:</strong><br>".$row_entries_1['brewInfo']."</p>"; ?></td>
        <td class="data bdr1B_gray"><p class="box">&nbsp;</p></td>
        <td class="data bdr1B_gray"><p class="box">&nbsp;</p></td>
    </tr>
    <?php } while ($row_bos = mysql_fetch_assoc($bos)); 
	mysql_free_result($bos);
	mysql_free_result($style_type);
	mysql_free_result($tables_1);
	mysql_free_result($entries_1);
	?>
</tbody>
</table>
<div style="page-break-after:always;"></div>
<?php } else echo "<p style='margin: 0 0 40px 0'>No entries are eligible.</p>"; ?>
<?php } ?>
	</div>
</div>
<?php } // end if (($go == "judging_scores_bos") && ($id != "default")) ?>

</body>
</html>