<?php 
session_start(); 
require('../paths.php'); 
require(CONFIG.'bootstrap.php');
require(DB.'admin_common.db.php');
if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {

function check_flight_round($flight_round,$round) {

	if ($round == "default") {
		if ($flight_round != "") return TRUE;
		else return FALSE;
	}
	
	if ($round != "default") {
		if (($flight_round != "") && ($flight_round == $round)) return TRUE;
		else return FALSE;
	}
		
}

$query_tables = "SELECT * FROM $judging_tables_db_table";
if ($go == "judging_locations") $query_tables .= sprintf(" WHERE tableLocation = '%s'", $location);
if ($id != "default") $query_tables .= sprintf(" WHERE id='%s'",$id);
else $query_tables .= " ORDER BY tableNumber";
$tables = mysql_query($query_tables, $brewing) or die(mysql_error());
$row_tables = mysql_fetch_assoc($tables);
$totalRows_tables = mysql_num_rows($tables); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Brew Competition Online Entry and Management - brewcompetition.com</title>
<link href="<?php echo $base_url; ?>css/print.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.dataTables.js"></script>
</head>
<?php 

if ($totalRows_tables == 0) { 
echo "<body>";
echo "<h1>No tables have been defined"; if ($go == "judging_locations") echo " for this location"; echo ".</h2>"; 
} 
else {
?>
<body>
<?php
//print_r($_SESSION);
// Use the following if not using queued judging - delinieates by flight and round
 if ($_SESSION['jPrefsQueued'] == "N") {

function number_of_flights($table_id) { 
    require(CONFIG.'config.php');
    mysql_select_db($database, $brewing);
	
	$query_flights = sprintf("SELECT flightNumber FROM %s WHERE flightTable='%s' ORDER BY flightNumber DESC LIMIT 1", $prefix."judging_flights", $table_id);
    $flights = mysql_query($query_flights, $brewing) or die(mysql_error());
    $row_flights = mysql_fetch_assoc($flights);
	
	$r = $row_flights['flightNumber'];
	return $r;	
}

function check_flight_number($entry_id,$flight) {
	require(CONFIG.'config.php');
    mysql_select_db($database, $brewing);
	
	$query_flights = sprintf("SELECT flightNumber,flightRound FROM %s WHERE flightEntryID='%s'", $prefix."judging_flights", $entry_id);
    $flights = mysql_query($query_flights, $brewing) or die(mysql_error());
    $row_flights = mysql_fetch_assoc($flights);
	
	if ($row_flights['flightNumber'] == $flight) $r = $row_flights['flightRound'];
	else $r = "";
	return $r;
	
}

if ((($go == "judging_tables") || ($go == "judging_locations")) && ($id == "default"))
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
			$query_location = sprintf("SELECT judgingLocName,judgingDate,judgingTime FROM %s WHERE id='%s'", $prefix."judging_locations", $row_tables['tableLocation']);
			$location = mysql_query($query_location, $brewing) or die(mysql_error());
			$row_location = mysql_fetch_assoc($location);
			?>
            <h2><?php echo table_location($row_tables['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default"); if ($round != "default") echo "<br>Round ".$round; ?></h2>
            <p><?php echo "Entries: ". get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default")."<br>Flights: ".$flights; ?></p>
            <p>** Please Note:</p>
            <ul>
            	<li>If there are no entries showing below, flights at this table have not been assigned to rounds.</li>
               	<li>If entries are missing, all entries have not been assigned to a flight or round <?php if ($round != "default") echo "OR they have been assigned to a different round"; ?>.</li>
            </ul>
            <?php } ?>
        </div>
	</div>
    <?php 
	for($i=1; $i<$flights+1; $i++) { 
	$random =  random_generator(5,2)
	?>
    <h3><?php echo "Table ".$row_tables['tableNumber'].", Flight ".$i; ?></h3>
    <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable<?php echo $random; ?>').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[2,'asc'],[1,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				null,
				null,
				null,
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
    	<th class="dataHeading bdr1B" width="1%">Pull Order</th>
        <th class="dataHeading bdr1B" width="1%">#</th>
        <th class="dataHeading bdr1B" width="95%">Style/Sub-Style</th>
        <th class="dataHeading bdr1B" width="1%">Loc /<br />Box</th>
        <th class="dataHeading bdr1B" width="1%">Round</th>
        <th class="dataHeading bdr1B" width="1%">Score</th>
        <th class="dataHeading bdr1B" width="1%">Place</th>
    </tr>
    </thead>
    <tbody>
    <?php 
	$a = explode(",", $row_tables['tableStyles']);
	
	foreach (array_unique($a) as $value) {
		$query_styles = sprintf("SELECT brewStyle FROM $styles_db_table WHERE id='%s'", $value);
		$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
		$row_styles = mysql_fetch_assoc($styles);
		
		$query_entries = sprintf("SELECT id,brewStyle,brewCategory,brewCategorySort,brewSubCategory,brewInfo,brewMead1,brewMead2,brewMead3,brewJudgingNumber,brewBoxNum FROM %s WHERE brewStyle='%s' AND brewReceived='1' ORDER BY id", $prefix."brewing", $row_styles['brewStyle']);
		$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
		$row_entries = mysql_fetch_assoc($entries);
		$style = $row_entries['brewCategorySort'].$row_entries['brewSubCategory'];
		do {
			$flight_round = check_flight_number($row_entries['id'],$i);
			if (check_flight_round($flight_round,$round)) {
	?>
    <tr>
    	<td class="bdr1B_gray"><p class="box">&nbsp;</p></td>
        <td class="data bdr1B_gray" nowrap>
		<?php 
		if ($view == "entry") echo sprintf("%04s",$row_entries['id']); 
		elseif (((NHC) && ($view != "entry")) || ($_SESSION['prefsEntryForm'] == "N")) echo $row_entries['brewJudgingNumber'];
		else echo readable_judging_number($row_entries['brewCategory'],$row_entries['brewJudgingNumber']); 
		?>
        </td>
        <td class="data bdr1B_gray"><?php echo $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1)."</em>"; if ($row_entries['brewInfo'] != "") echo "<p><strong>Special Ingredients/Classic Style: </strong>".$row_entries['brewInfo']."</p>"; if (style_convert($style,"5")) echo "<p>"; if ($row_entries['brewMead1'] != '') echo $row_entries['brewMead1']."&nbsp;&nbsp;"; if ($row_entries['brewMead2'] != '') echo $row_entries['brewMead2']."&nbsp;&nbsp;"; if ($row_entries['brewMead3'] != '') echo $row_entries['brewMead3']."</p>"; ?></td>
        <td nowrap="nowrap" class="data bdr1B_gray"><?php echo $row_entries['brewBoxNum']; ?></td>
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
    <?php if ($flights > 0) { ?><div style="page-break-after:always;"></div><?php } ?>
    <?php } ?>
    </div>
</div>
<?php if ($flights == 0) { ?><div style="page-break-after:always;"></div><?php } ?>
<?php 	} 
	while ($row_tables = mysql_fetch_assoc($tables)); 


if ((($go == "judging_tables") || ($go == "judging_locations")) &&  ($id != "default")) { 
	
$flights = number_of_flights($row_tables['id']);
if ($flights > 0) $flights = $flights; else $flights = "0";
?>
<div id="content">
	<div id="content-inner">
    <div id="header">	
		<div id="header-inner">
        	<h1><?php echo "Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']; ?></h1>
            <?php if ($row_tables['tableLocation'] != "") { 
			$query_location = sprintf("SELECT judgingLocName,judgingDate,judgingTime FROM %s WHERE id='%s'", $prefix."judging_locations", $row_tables['tableLocation']);
			$location = mysql_query($query_location, $brewing) or die(mysql_error());
			$row_location = mysql_fetch_assoc($location);
			?>
            <h2><?php echo table_location($row_tables['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default"); if ($round != "default") echo "<br>Round ".$round; ?></h2>
            <p><?php echo "Entries: ". get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default")."<br>Flights: ".$flights; ?></p>
            <?php } ?>
            <p>** Please Note:</p>
            <ul>
            	<li>If there are no entries showing below, flights at this table have not been assigned to rounds.</li>
               	<li>If entries are missing, all entries have not been assigned to a flight or round.</li>
            </ul>
        </div>
	</div>
     <?php 
	for($i=1; $i<$flights+1; $i++) { 
	$random = random_generator(5,1);
	?>
    <h3><?php echo "Table ".$row_tables['tableNumber'].", Flight ".$i; ?></h3>
    <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable<?php echo $random; ?>').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[4,'asc'],[2,'asc'],[1,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
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
    <table class="dataTable" id="sortable<?php echo $random; ?>">
    <thead>
    <tr>
    	<th class="dataHeading bdr1B" width="1%">Pull Order</th>
        <th class="dataHeading bdr1B" width="1%">#</th>
        <th class="dataHeading bdr1B" width="95%">Style/Sub-Style</th>
        <th class="dataHeading bdr1B" width="1%">Loc/Box</th>
        <th class="dataHeading bdr1B" width="1%">Round</th>
        <th class="dataHeading bdr1B" width="1%">Score</th>
        <th class="dataHeading bdr1B" width="1%">Place</th>
    </tr>
    </thead>
    <tbody>
    <?php 
	$a = explode(",", $row_tables['tableStyles']); 
	
	foreach (array_unique($a) as $value) {
		$query_styles = sprintf("SELECT brewStyle FROM $styles_db_table WHERE id='%s'", $value);
		$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
		$row_styles = mysql_fetch_assoc($styles);
		
		$query_entries = sprintf("SELECT id,brewStyle,brewCategory,brewCategorySort,brewSubCategory,brewInfo,brewMead1,brewMead2,brewMead3,brewJudgingNumber,brewBoxNum FROM %s WHERE brewStyle='%s' AND brewReceived='1' ORDER BY id", $prefix."brewing", $row_styles['brewStyle']);
		$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
		$row_entries = mysql_fetch_assoc($entries);
		$style = $row_entries['brewCategorySort'].$row_entries['brewSubCategory'];
		do {
			$flight_round = check_flight_number($row_entries['id'],$i);
			if ($flight_round != "") {
	?>
    <tr>
    	<td class="bdr1B_gray"><p class="box">&nbsp;</p></td>
        <td class="data bdr1B_gray" nowrap>
        <?php 
		if ($view == "entry") echo sprintf("%04s",$row_entries['id']); 
		elseif (((NHC) && ($view != "entry")) || ($_SESSION['prefsEntryForm'] == "N")) echo $row_entries['brewJudgingNumber'];
		else echo readable_judging_number($row_entries['brewCategory'],$row_entries['brewJudgingNumber']); 
		?>
        </td>
        <td class="data bdr1B_gray"><?php echo $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1)."</em>"; if ($row_entries['brewInfo'] != "") echo "<p><strong>Special Ingredients/Classic Style: </strong>".$row_entries['brewInfo']."</p>"; if (style_convert($style,"5")) echo "<p>"; if ($row_entries['brewMead1'] != '') echo $row_entries['brewMead1']."&nbsp;&nbsp;"; if ($row_entries['brewMead2'] != '') echo $row_entries['brewMead2']."&nbsp;&nbsp;"; if ($row_entries['brewMead3'] != '') echo $row_entries['brewMead3']."</p>"; ?></td>
        <td nowrap="nowrap" class="data bdr1B_gray"><?php echo $row_entries['brewBoxNum']; ?></td>
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

    <?php } ?>
    </div>
</div>
<div style="page-break-after:always;"></div>
<?php 	
	} // end if (($go == "judging_tables") && ($id != "default")) 
} // end if ($_SESSION['jPrefsQueued'] == "N") 
?>



<?php if ($_SESSION['jPrefsQueued'] == "Y") { ?>
<?php if ((($go == "judging_tables") || ($go == "judging_locations")) && ($id == "default"))  
do { 
$entry_count = get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default");

$query_table_round = sprintf("SELECT COUNT(*) as count from $judging_flights_db_table WHERE flightTable='%s' AND flightRound='%s'",$row_tables['id'],$round);
$table_round  = mysql_query($query_table_round, $brewing) or die(mysql_error());
$row_table_round = mysql_fetch_assoc($table_round);
$round_count[] = $row_table_round['count'];

if (($row_table_round['count'] >= 1) || ($round == "default")) {
?>
<div id="content">
	<div id="content-inner">
    <div id="header">	
		<div id="header-inner">
        	<h1>Table <?php echo $row_tables['tableNumber'].": ".$row_tables['tableName']; ?></h1>
            <?php if ($row_tables['tableLocation'] != "") { 
			$query_location = sprintf("SELECT judgingLocName,judgingDate,judgingTime FROM %s WHERE id='%s'", $prefix."judging_locations", $row_tables['tableLocation']);
			$location = mysql_query($query_location, $brewing) or die(mysql_error());
			$row_location = mysql_fetch_assoc($location);
			?>
            <h2><?php echo table_location($row_tables['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default"); if ($round != "default") echo "<br>Round ".$round; ?></h2>
            <p><?php echo "Entries: ". $entry_count; ?></p>
            <p>** Note: if there are no entries below, this table has not been assigned to a round.</p>
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
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
    <table class="dataTable" id="sortable<?php echo $row_tables['id']; ?>">
    <thead>
    <tr>
    	<th class="dataHeading bdr1B" width="1%">Pull Order</th>
        <th class="dataHeading bdr1B" width="1%">#</th>
        <th class="dataHeading bdr1B" width="95%">Style/Sub-Style</th>
        <th class="dataHeading bdr1B" width="1%">Loc/Box</th>
        <th class="dataHeading bdr1B" width="1%">Score</th>
        <th class="dataHeading bdr1B" width="1%">Place</th>
    </tr>
    </thead>
    <tbody>
    <?php 
	$a = explode(",", $row_tables['tableStyles']); 
	foreach (array_unique($a) as $value) {
		$query_styles = sprintf("SELECT brewStyle FROM $styles_db_table WHERE id='%s'", $value);
		$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
		$row_styles = mysql_fetch_assoc($styles);
		
		$query_entries = sprintf("SELECT id,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewInfo,brewMead1,brewMead2,brewMead3,brewJudgingNumber,brewBoxNum FROM %s WHERE brewStyle='%s'  AND brewReceived='1' ORDER BY id", $prefix."brewing", $row_styles['brewStyle']);
		$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
		$row_entries = mysql_fetch_assoc($entries);
		$style = $row_entries['brewCategorySort'].$row_entries['brewSubCategory'];
		do {
	?>
    <tr>
    	<td class="bdr1B_gray"><p class="box">&nbsp;</p></td>
        <td class="data bdr1B_gray" nowrap>
        <?php 
		if ($view == "entry") echo sprintf("%04s",$row_entries['id']); 
		elseif (((NHC) && ($view != "entry")) || ($_SESSION['prefsEntryForm'] == "N")) echo $row_entries['brewJudgingNumber'];
		else echo readable_judging_number($row_entries['brewCategory'],$row_entries['brewJudgingNumber']); 
		?>
        </td>
        <td class="data bdr1B_gray"><?php echo $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1)."</em>"; if ($row_entries['brewInfo'] != "") echo "<p><strong>Special Ingredients/Classic Style: </strong>".$row_entries['brewInfo']."</p>"; if (style_convert($style,"5")) echo "<p>"; if ($row_entries['brewMead1'] != '') echo $row_entries['brewMead1']."&nbsp;&nbsp;"; if ($row_entries['brewMead2'] != '') echo $row_entries['brewMead2']."&nbsp;&nbsp;"; if ($row_entries['brewMead3'] != '') echo $row_entries['brewMead3']."</p>"; ?></td>
        <td nowrap="nowrap" class="data bdr1B_gray"><?php echo $row_entries['brewBoxNum']; ?></td>
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
} while ($row_tables = mysql_fetch_assoc($tables)); 
if (($round != "default") && (array_sum($round_count) == 0)) echo "<h2>No tables have been assigned to this round at this location</h2>";
if ((($go == "judging_tables") || ($go == "judging_locations")) && ($id != "default")) { 
$entry_count = get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default");
?>
<div id="content">
	<div id="content-inner">
    <div id="header">	
		<div id="header-inner">
        	<h1>Table <?php echo $row_tables['tableNumber'].": ".$row_tables['tableName']; ?></h1>
            <?php if ($row_tables['tableLocation'] != "") { 
			$query_location = sprintf("SELECT judgingLocName,judgingDate,judgingTime FROM %s WHERE id='%s'", $prefix."judging_locations", $row_tables['tableLocation']);
			$location = mysql_query($query_location, $brewing) or die(mysql_error());
			$row_location = mysql_fetch_assoc($location);
			?>
            <h2><?php echo table_location($row_tables['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default"); if ($round != "default") echo "<br>Round ".$round; ?></h2>
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
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
    <table class="dataTable" id="sortable<?php echo $row_tables['id']; ?>">
    <thead>
    <tr>
    	<th class="dataHeading bdr1B" width="1%">Pull Order</th>
        <th class="dataHeading bdr1B" width="1%">#</th>
        <th class="dataHeading bdr1B" width="95%">Style/Sub-Style</th>
        <th class="dataHeading bdr1B" width="1%">Loc/Box</th>
        <th class="dataHeading bdr1B" width="1%">Score</th>
        <th class="dataHeading bdr1B" width="1%">Place</th>
    </tr>
    </thead>
    <tbody>
    <?php 
	$a = explode(",", $row_tables['tableStyles']); 
	
	foreach (array_unique($a) as $value) {
		$query_styles = sprintf("SELECT brewStyle FROM $styles_db_table WHERE id='%s'", $value);
		$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
		$row_styles = mysql_fetch_assoc($styles);
		
		$query_entries = sprintf("SELECT id,brewStyle,brewCategory,brewCategorySort,brewSubCategory,brewInfo,brewMead1,brewMead2,brewMead3,brewJudgingNumber,brewBoxNum FROM %s WHERE brewStyle='%s' AND brewReceived='1' ORDER BY id", $prefix."brewing", $row_styles['brewStyle']);
		$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
		$row_entries = mysql_fetch_assoc($entries);
		$style = $row_entries['brewCategorySort'].$row_entries['brewSubCategory'];
		do {
	?>
    <tr>
    	<td class="bdr1B_gray"><p class="box">&nbsp;</p></td>
        <td class="data bdr1B_gray" nowrap>
        <?php 
		if ($view == "entry") echo sprintf("%04s",$row_entries['id']); 
		elseif (((NHC) && ($view != "entry")) || ($_SESSION['prefsEntryForm'] == "N")) echo $row_entries['brewJudgingNumber'];
		else echo readable_judging_number($row_entries['brewCategory'],$row_entries['brewJudgingNumber']); 
		?>
        </td>
        <td class="data bdr1B_gray"><?php echo $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1)."</em>"; if ($row_entries['brewInfo'] != "") echo "<p><strong>Special Ingredients/Classic Style: </strong>".$row_entries['brewInfo']."</p>"; if (style_convert($style,"5")) echo "<p>"; if ($row_entries['brewMead1'] != '') echo $row_entries['brewMead1']."&nbsp;&nbsp;"; if ($row_entries['brewMead2'] != '') echo $row_entries['brewMead2']."&nbsp;&nbsp;"; if ($row_entries['brewMead3'] != '') echo $row_entries['brewMead3']."</p>"; ?></td>
        <td nowrap="nowrap" class="data bdr1B_gray"><?php echo $row_entries['brewBoxNum']; ?></td>
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
} // end if ($_SESSION['jPrefsQueued'] == "Y") 
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
	$query_style_type = "SELECT * FROM $style_types_db_table WHERE id='$type'";
	$style_type = mysql_query($query_style_type, $brewing) or die(mysql_error());
	$row_style_type = mysql_fetch_assoc($style_type);

if ($row_style_type['styleTypeBOS'] == "Y") { 

	$query_bos = sprintf("SELECT * FROM %s",$prefix."judging_scores");
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
        <th class="dataHeading bdr1B" width="1%">#</th>
        <th class="dataHeading bdr1B" width="95%">Style/Sub-Style</th>
        <th class="dataHeading bdr1B" width="1%">Loc/Box</th>
        <th class="dataHeading bdr1B" width="1%">Score</th>
        <th class="dataHeading bdr1B" width="1%">Place</th>
    </tr>
    </thead>
<tbody>
	<?php do {
	$query_entries_1 = sprintf("SELECT id,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewInfo,brewMead1,brewMead2,brewMead3,brewJudgingNumber,brewBoxNum FROM %s WHERE id='%s'", $prefix."brewing", $row_bos['eid']);
	$entries_1 = mysql_query($query_entries_1, $brewing) or die(mysql_error());
	$row_entries_1 = mysql_fetch_assoc($entries_1);
	$style = $row_entries_1['brewCategorySort'].$row_entries_1['brewSubCategory'];
	
	$query_tables_1 = sprintf("SELECT id,tableName,tableNumber FROM $judging_tables_db_table WHERE id='%s'", $row_bos['scoreTable']);
	$tables_1 = mysql_query($query_tables_1, $brewing) or die(mysql_error());
	$row_tables_1 = mysql_fetch_assoc($tables_1);
	$totalRows_tables = mysql_num_rows($tables_1);
	?>
    <tr>
    	<td class="bdr1B_gray"><p class="box">&nbsp;</p></td>
        <td class="data bdr1B_gray" nowrap>
		<?php 
		if ($view == "entry") echo sprintf("%04s",$row_entries_1['id']); 
		elseif (((NHC) && ($view != "entry")) || ($_SESSION['prefsEntryForm'] == "N")) echo $row_entries_1['brewJudgingNumber'];
		else echo readable_judging_number($row_entries_1['brewCategory'],$row_entries_1['brewJudgingNumber']); 
		?>
        </td>
        <td class="data bdr1B_gray"><?php echo $style." ".$row_entries_1['brewStyle']."<em><br>".style_convert($row_entries_1['brewCategorySort'],1)."</em>"; if ($row_entries_1['brewInfo'] != "") echo "<p><strong>Special Ingredients/Classic Style: </strong>".$row_entries_1['brewInfo']."</p>"; if (style_convert($style,"5")) echo "<p>"; if ($row_entries_1['brewMead1'] != '') echo $row_entries_1['brewMead1']."<br>"; if ($row_entries_1['brewMead2'] != '') echo $row_entries_1['brewMead2']."<br>"; if ($row_entries_1['brewMead3'] != '') echo $row_entries_1['brewMead3']."</p>"; ?></td>
        <td nowrap="nowrap" class="data bdr1B_gray"><?php echo $row_entries['brewBoxNum']; ?></td>
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
<?php } else echo "<p style='margin: 0 0 40px 0'>No entries are eligible.</p>"; 
} 
?>
<?php } ?>
	</div>
</div>
<div style="page-break-after:always;"></div>
<?php } // end if (($go == "judging_scores_bos") && ($id != "default")) ?>

<?php 
// Printing BOS Pull Sheets by Style Type
if (($go == "judging_scores_bos") && ($id != "default")) { ?>
<div id="content">
	<div id="content-inner">
<?php 
	$query_style_type = sprintf("SELECT * FROM %s WHERE id='$id'", $prefix."style_types");
	$style_type = mysql_query($query_style_type, $brewing) or die(mysql_error());
	$row_style_type = mysql_fetch_assoc($style_type);
	
if ($row_style_type['styleTypeBOS'] == "Y") { 

	$query_bos = sprintf("SELECT * FROM %s",$prefix."judging_scores");
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
<table class="dataTable" id="sortable">
<thead>
    <tr>
    	<th class="dataHeading bdr1B" width="1%">Pull Order</th>
        <th class="dataHeading bdr1B" width="1%">#</th>
        <th class="dataHeading bdr1B" width="95%">Style/Sub-Style</th>
        <th class="dataHeading bdr1B" width="1%">Loc/Box</th>
        <th class="dataHeading bdr1B" width="1%">Score</th>
        <th class="dataHeading bdr1B" width="1%">Place</th>
    </tr>
    </thead>
<tbody>
	<?php do {
	$query_entries_1 = sprintf("SELECT brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewInfo,brewMead1,brewMead2,brewMead3,brewJudgingNumber,brewBoxNum FROM %s WHERE id='%s'", $prefix."brewing", $row_bos['eid']);
	$entries_1 = mysql_query($query_entries_1, $brewing) or die(mysql_error());
	$row_entries_1 = mysql_fetch_assoc($entries_1);
	$style = $row_entries_1['brewCategorySort'].$row_entries_1['brewSubCategory'];
	
	$query_tables_1 = sprintf("SELECT id,tableName,tableNumber FROM %s WHERE id='%s'", $prefix."judging_tables", $row_bos['scoreTable']);
	$tables_1 = mysql_query($query_tables_1, $brewing) or die(mysql_error());
	$row_tables_1 = mysql_fetch_assoc($tables_1);
	$totalRows_tables = mysql_num_rows($tables_1);
	?>
    <tr>
    	<td class="bdr1B_gray"><p class="box">&nbsp;</p></td>
       	<td class="data bdr1B_gray" nowrap>
		<?php 
		if ($view == "entry") echo sprintf("%04s",$row_entries_1['id']); 
		elseif (((NHC) && ($view != "entry")) || ($_SESSION['prefsEntryForm'] == "N")) echo $row_entries_1['brewJudgingNumber'];
		else echo readable_judging_number($row_entries_1['brewCategory'],$row_entries_1['brewJudgingNumber']); 
		?>
		</td>
        <td class="data bdr1B_gray"><?php echo $style." ".$row_entries_1['brewStyle']."<em><br>".style_convert($row_entries_1['brewCategorySort'],1)."</em>"; if ($row_entries_1['brewInfo'] != "") echo "<p><strong>Special Ingredients/Classic Style: </strong>".$row_entries_1['brewInfo']."</p>"; if (style_convert($style,"5")) echo "<p>"; if ($row_entries_1['brewMead1'] != '') echo $row_entries_1['brewMead1']."<br>"; if ($row_entries_1['brewMead2'] != '') echo $row_entries_1['brewMead2']."<br>"; if ($row_entries_1['brewMead3'] != '') echo $row_entries_1['brewMead3']."</p>"; ?></td>
        <td nowrap="nowrap" class="data bdr1B_gray"><?php echo $row_entries['brewBoxNum']; ?></td>
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

<?php } else echo "<p style='margin: 0 0 40px 0'>No entries are eligible.</p>"; ?>
<?php } ?>
	</div>
</div>
<div style="page-break-after:always;"></div>
<?php } // end if (($go == "judging_scores_bos") && ($id != "default")) 
}
?>
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
else echo "<p>Not available.</p>";
?>