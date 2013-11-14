<?php 
session_start(); 
require('../paths.php'); 
require(CONFIG.'bootstrap.php');
require(DB.'admin_common.db.php');
if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {
include(LIB.'output.lib.php');
include(DB.'output_pullsheets.db.php');
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
// Use the following if not using queued judging - delinieates by flight and round
if ($_SESSION['jPrefsQueued'] == "N") {
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
			if ($row_tables['tableLocation'] != "") { ?>
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
		include(DB.'output_pullsheets_entries.db.php');		
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
            <?php if ($row_tables['tableLocation'] != "") { ?>
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
		include(DB.'output_pullsheets_entries.db.php');
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
include(DB.'output_pullsheets_queued.db.php');
$round_count[] = $row_table_round['count'];

if (($row_table_round['count'] >= 1) || ($round == "default")) {
?>
<div id="content">
	<div id="content-inner">
    <div id="header">	
		<div id="header-inner">
        	<h1>Table <?php echo $row_tables['tableNumber'].": ".$row_tables['tableName']; ?></h1>
            <?php if ($row_tables['tableLocation'] != "") { ?>
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
		include(DB.'output_pullsheets_entries.db.php');
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
            <?php if ($row_tables['tableLocation'] != "") { ?>
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
		include(DB.'output_pullsheets_entries.db.php');
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
if ($go == "judging_scores_bos") { ?>
<div id="content">
	<div id="content-inner">
<?php
if ($id == "default") {
	do { $a[] = $row_style_types['id']; } while ($row_style_types = mysql_fetch_assoc($style_types));
	sort($a);
}
if ($id != "default") $a[] = $id;
foreach ($a as $type) {
	
	$style_type_info = style_type_info($type);
	$style_type_info = explode("^",$style_type_info);

if ($style_type_info[0] == "Y") { 

	include(DB.'output_pullsheets_bos.db.php');

?>
    <div id="header">	
		<div id="header-inner">
        	<h1>BOS Entries - <?php echo $style_type_info[2]; ?></h1>
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
	include(DB.'output_pullsheets_bos_entries.db.php');
	$style = $row_entries_1['brewCategorySort'].$row_entries_1['brewSubCategory'];
	
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
<?php } // end if (($go == "judging_scores_bos") && ($id != "default")) 
}
?>
</body>
</html>
<script type="text/javascript">
function selfPrint(){
    self.focus();
    self.print();
}
setTimeout('selfPrint()',2000);
html.push('');
</script>
<?php } 
else echo "<p>Not available.</p>";
?>