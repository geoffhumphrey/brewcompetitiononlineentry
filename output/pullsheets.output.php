<?php 
require(DB.'admin_common.db.php');
include(LIB.'output.lib.php');
include(DB.'output_pullsheets.db.php');
include(INCLUDES.'scrubber.inc.php');


if (($go == "judging_tables") && ($totalRows_tables == 0)) { 
//echo "<p>".$query_tables;
echo "<h1>No tables have been defined"; if ($go == "judging_locations") echo " for this location"; echo ".</h1>"; 
} 

else {
// Use the following if not using queued judging - delinieates by flight and round
if ($_SESSION['jPrefsQueued'] == "N") {
if ((($go == "judging_tables") || ($go == "judging_locations")) && ($id == "default"))
do { 

$flights = number_of_flights($row_tables['id']);
if ($flights > 0) $flights = $flights; else $flights = "0";
?>
		<div class="page-header">
        	<h1><?php echo "Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']; ?></h1>
        </div>
	<?php if ($row_tables['tableLocation'] != "") { ?>
    <p class="lead"><?php echo table_location($row_tables['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default"); if ($round != "default") echo "<br>Round ".$round; ?></h2>
    <p class="lead"><small><?php echo "Entries: ". get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default")."<br>Flights: ".$flights; ?></small></p>
    <p>** Please Note:</p>
    <ul>
        <li>If there are no entries showing below, flights at this table have not been assigned to rounds.</li>
        <li>If entries are missing, all entries have not been assigned to a flight or round<?php if ($round != "default") echo " OR they have been assigned to a different round"; ?>.</li>
    </ul>
    <?php } ?>
    <?php 
	for($i=1; $i<$flights+1; $i++) { 
	$random =  random_generator(5,2)
	?>
    <h4><?php echo "Table ".$row_tables['tableNumber'].", Flight ".$i; ?></h4>
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
    <table class="table table-striped table-bordered" id="sortable<?php echo $random; ?>">
    <thead>
    <tr>
    	<th nowrap>Pull Order</th>
        <th>#</th>
        <th>Style/Sub-Style</th>
        <th>Loc/Box</th>
        <th>Round</th>
        <th>Score</th>
        <th>Place</th>
    </tr>
    </thead>
    <tbody>
    <?php 
	$a = explode(",", $row_tables['tableStyles']);
	//print_r($a);
	foreach (array_unique($a) as $value) {
		include(DB.'output_pullsheets_entries.db.php');		
		$style = $row_entries['brewCategorySort'].$row_entries['brewSubCategory'];
		$style_special = $row_entries['brewCategorySort']."^".$row_entries['brewSubCategory']."^".$_SESSION['prefsStyleSet'];
		do {
			$flight_round = check_flight_number($row_entries['id'],$i);
			if (check_flight_round($flight_round,$round)) {
	?>
    <tr>
    	<td><p class="box">&nbsp;</p></td>
        <td nowrap>
		<?php 
		if ($view == "entry") echo sprintf("%04s",$row_entries['id']); 
		elseif (((NHC) && ($view != "entry")) || ($_SESSION['prefsEntryForm'] == "N")) echo sprintf("%06s",$row_entries['brewJudgingNumber']);
		else echo readable_judging_number($row_entries['brewCategory'],$row_entries['brewJudgingNumber']); 
		?>
        </td>
        <td>
        <?php 
		$special = style_convert($style_special,"9");
		$special = explode("^",$special);
		$special = $special[4];
		$strength = $special[5];
		$carb = $special[6];
		$sweet = $special[7];
		echo $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1)."</em>"; 
		if (($row_entries['brewInfo'] != "") && ($special == "1")) echo "<p><strong>Required Info: </strong>".str_replace("^","; ",$row_entries['brewInfo'])."</p>"; 
		if ($row_entries['brewComments'] != "") echo "<p><strong>Specifics: </strong>".$row_entries['brewComments']."</p>"; 
		if (style_convert($style,"5")) echo "<p>"; 
		if (!empty($row_entries['brewMead1'])) echo "<strong>Carbonation:</strong> ".$row_entries['brewMead1']."<br>"; 
		if (!empty($row_entries['brewMead2'])) echo "<strong>Sweetness:</strong> ".$row_entries['brewMead2']."<br>"; 
		if (!empty($row_entries['brewMead3'])) echo "<strong>Strength:</strong> ".$row_entries['brewMead3'];
		echo "</p>";  
		?>
        </td>
        <td nowrap><?php echo $row_entries['brewBoxNum']; ?></td>
        <td><?php echo $flight_round; ?></td>
        <td><p class="box">&nbsp;</p></td>
        <td><p class="box">&nbsp;</p></td>
    </tr>
    <?php 
				} 
		} while ($row_entries = mysqli_fetch_assoc($entries));
	} // end foreach ?>
    </tbody>
    </table>
    <?php if ($flights > 0) { ?><div style="page-break-after:always;"></div><?php } ?>
    <?php } ?>
<?php if ($flights == 0) { ?><div style="page-break-after:always;"></div><?php } ?>
<?php 	} while ($row_tables = mysqli_fetch_assoc($tables)); 


if ((($go == "judging_tables") || ($go == "judging_locations")) &&  ($id != "default")) { 
	
$flights = number_of_flights($row_tables['id']);
if ($flights > 0) $flights = $flights; else $flights = "0";
?>
		<div class="page-header">
        	<h1><?php echo "Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']; ?></h1>
        </div>
    <?php if ($row_tables['tableLocation'] != "") { ?>
            <p class="lead"><?php echo table_location($row_tables['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default"); if ($round != "default") echo "<br>Round ".$round; ?></h2>
            <p class="lead"><small><?php echo "Entries: ". get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default")."<br>Flights: ".$flights; ?></small></p>
            <?php } ?>
            <p>** Please Note:</p>
            <ul>
            	<li>If there are no entries showing below, flights at this table have not been assigned to rounds.</li>
               	<li>If entries are missing, all entries have not been assigned to a flight or round.</li>
            </ul>
    <?php 
	for($i=1; $i<$flights+1; $i++) { 
	$random = random_generator(5,1);
	?>
    <h4><?php echo "Table ".$row_tables['tableNumber'].", Flight ".$i; ?></h4>
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
    <table class="table table-striped table-bordered" id="sortable<?php echo $random; ?>">
    <thead>
    <tr>
    	<th nowrap>Pull Order</th>
        <th>#</th>
        <th>Style/Sub-Style</th>
        <th>Loc/Box</th>
        <th>Round</th>
        <th>Score</th>
        <th>Place</th>
    </tr>
    </thead>
    <tbody>
    <?php 
	$a = explode(",", $row_tables['tableStyles']); 
	
	foreach (array_unique($a) as $value) {
		include(DB.'output_pullsheets_entries.db.php');
		$style = $row_entries['brewCategorySort'].$row_entries['brewSubCategory'];
		$style_special = $row_entries['brewCategorySort']."^".$row_entries['brewSubCategory']."^".$_SESSION['prefsStyleSet'];
		do {
			$flight_round = check_flight_number($row_entries['id'],$i);
			if ($flight_round != "") {
	?>
    <tr>
    	<td><p class="box">&nbsp;</p></td>
        <td nowrap>
        <?php 
		if ($view == "entry") echo sprintf("%04s",$row_entries['id']); 
		elseif (((NHC) && ($view != "entry")) || ($_SESSION['prefsEntryForm'] == "N")) echo sprintf("%06s",$row_entries['brewJudgingNumber']);
		else echo readable_judging_number($row_entries['brewCategory'],$row_entries['brewJudgingNumber']); 
		?>
        </td>
        <td>
		<?php 
		$special = style_convert($style_special,"9");
		$special = explode("^",$special);
		$special = $special[4];
		$strength = $special[5];
		$carb = $special[6];
		$sweet = $special[7];
		echo $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1)."</em>"; 
		if (($row_entries['brewInfo'] != "") && ($special == "1")) echo "<p><strong>Required Info: </strong>".str_replace("^","; ",$row_entries['brewInfo'])."</p>"; 
		if ($row_entries['brewComments'] != "") echo "<p><strong>Specifics: </strong>".$row_entries['brewComments']."</p>"; 
		if (style_convert($style,"5")) echo "<p>"; 
		if (!empty($row_entries['brewMead1'])) echo "<strong>Carbonation:</strong> ".$row_entries['brewMead1']."<br>"; 
		if (!empty($row_entries['brewMead2'])) echo "<strong>Sweetness:</strong> ".$row_entries['brewMead2']."<br>"; 
		if (!empty($row_entries['brewMead3'])) echo "<strong>Strength:</strong> ".$row_entries['brewMead3'];
		echo "</p>"; 
		?>
        </td>
        <td nowrap><?php echo $row_entries['brewBoxNum']; ?></td>
        <td><?php echo $flight_round; ?></td>
        <td><p class="box">&nbsp;</p></td>
        <td><p class="box">&nbsp;</p></td>
    </tr>
    <?php 
				}	
		} while ($row_entries = mysqli_fetch_assoc($entries));
	} // end foreach ?>
    </tbody>
    </table>

    <?php } ?>
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
	
    <div class="page-header">
        <h1>Table <?php echo $row_tables['tableNumber'].": ".$row_tables['tableName']; ?></h1>
    </div>
    <?php if ($row_tables['tableLocation'] != "") { ?>
    <h3><?php echo table_location($row_tables['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default"); if ($round != "default") echo "<br>Round ".$round; ?></h3>
    <p class="lead"><?php echo "Entries: ". $entry_count; ?></p>
    <p>** Note: if there are no entries below, this table has not been assigned to a round.</p>
    <?php } ?>
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
    <table class="table table-striped table-bordered" id="sortable<?php echo $row_tables['id']; ?>">
    <thead>
    <tr>
    	<th nowrap>Pull Order</th>
        <th>#</th>
        <th>Style/Sub-Style</th>
        <th>Loc/Box</th>
        <th>Score</th>
        <th>Place</th>
    </tr>
    </thead>
    <tbody>
    <?php 
	$a = explode(",", $row_tables['tableStyles']); 
	foreach (array_unique($a) as $value) {
		include(DB.'output_pullsheets_entries.db.php');
		$style = $row_entries['brewCategorySort'].$row_entries['brewSubCategory'];
		$style_special = $row_entries['brewCategorySort']."^".$row_entries['brewSubCategory']."^".$_SESSION['prefsStyleSet'];
		do {
	?>
    <tr>
    	<td><p class="box">&nbsp;</p></td>
        <td nowrap>
        <?php 
		if ($view == "entry") echo sprintf("%04s",$row_entries['id']); 
		elseif (((NHC) && ($view != "entry")) || ($_SESSION['prefsEntryForm'] == "N")) echo sprintf("%06s",$row_entries['brewJudgingNumber']);
		else echo readable_judging_number($row_entries['brewCategory'],$row_entries['brewJudgingNumber']); 
		?>
        </td>
        <td>
		<?php 
		$special = style_convert($style_special,"9");
		$special = explode("^",$special);
		$special = $special[4];
		$strength = $special[5];
		$carb = $special[6];
		$sweet = $special[7];
		echo $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1)."</em>"; 
		if (($row_entries['brewInfo'] != "") && ($special == "1")) echo "<p><strong>Required Info: </strong>".str_replace("^","; ",$row_entries['brewInfo'])."</p>";
		if ($row_entries['brewComments'] != "") echo "<p><strong>Specifics: </strong>".$row_entries['brewComments']."</p>"; 
		if (style_convert($style,"5")) echo "<p>"; 
		if (!empty($row_entries['brewMead1'])) echo "<strong>Carbonation:</strong> ".$row_entries['brewMead1']."<br>"; 
		if (!empty($row_entries['brewMead2'])) echo "<strong>Sweetness:</strong> ".$row_entries['brewMead2']."<br>"; 
		if (!empty($row_entries['brewMead3'])) echo "<strong>Strength:</strong> ".$row_entries['brewMead3'];
		echo "</p>";  
		?>
        </td>
        <td nowrap><?php echo $row_entries['brewBoxNum']; ?></td>
        <td><p class="box">&nbsp;</p></td>
        <td><p class="box">&nbsp;</p></td>
    </tr>
    <?php } while ($row_entries = mysqli_fetch_assoc($entries));
	
	} // end foreach ?>
    </tbody>
    </table>
    <?php } ?>
<div style="page-break-after:always;"></div>
<?php 	} 
} while ($row_tables = mysqli_fetch_assoc($tables)); 
if (($round != "default") && (array_sum($round_count) == 0)) echo "<h2>No tables have been assigned to this round at this location</h2>";
if ((($go == "judging_tables") || ($go == "judging_locations")) && ($id != "default")) { 
$entry_count = get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default");
?>
    <div class="page-header">
        <h1>Table <?php echo $row_tables['tableNumber'].": ".$row_tables['tableName']; ?></h1>
    </div>
    <?php if ($row_tables['tableLocation'] != "") { ?>
    <h3><?php echo table_location($row_tables['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default"); if ($round != "default") echo "<br>Round ".$round; ?></h3>
    <p class="lead"><?php echo "Entries: ". $entry_count; ?></p>
    <?php } ?>
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
    <table class="table table-striped table-bordered" id="sortable<?php echo $row_tables['id']; ?>">
    <thead>
    <tr>
    	<th nowrap>Pull Order</th>
        <th>#</th>
        <th>Style/Sub-Style</th>
        <th>Loc/Box</th>
        <th>Score</th>
        <th>Place</th>
    </tr>
    </thead>
    <tbody>
    <?php 
	$a = explode(",", $row_tables['tableStyles']); 
	
	foreach (array_unique($a) as $value) {
		include(DB.'output_pullsheets_entries.db.php');
		$style = $row_entries['brewCategorySort'].$row_entries['brewSubCategory'];
		$style_special = $row_entries['brewCategorySort']."^".$row_entries['brewSubCategory']."^".$_SESSION['prefsStyleSet'];
		do {
	?>
    <tr>
    	<td><p class="box">&nbsp;</p></td>
        <td nowrap>
        <?php 
		if ($view == "entry") echo sprintf("%04s",$row_entries['id']); 
		elseif (((NHC) && ($view != "entry")) || ($_SESSION['prefsEntryForm'] == "N")) echo sprintf("%06s",$row_entries['brewJudgingNumber']);
		else echo readable_judging_number($row_entries['brewCategory'],$row_entries['brewJudgingNumber']); 
		?>
        </td>
        <td>
		<?php 
		$special = style_convert($style_special,"9");
		$special = explode("^",$special);
		$special = $special[4];
		$strength = $special[5];
		$carb = $special[6];
		$sweet = $special[7];
		echo $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1)."</em>"; 
		if (($row_entries['brewInfo'] != "") && ($special == "1")) echo "<p><strong>Required Info: </strong>".str_replace("^","; ",$row_entries['brewInfo'])."</p>"; 
		if ($row_entries['brewComments'] != "") echo "<p><strong>Specifics: </strong>".$row_entries['brewComments']."</p>"; 
		if (style_convert($style,"5")) echo "<p>"; 
		if (!empty($row_entries['brewMead1'])) echo "<strong>Carbonation:</strong> ".$row_entries['brewMead1']."<br>"; 
		if (!empty($row_entries['brewMead2'])) echo "<strong>Sweetness:</strong> ".$row_entries['brewMead2']."<br>"; 
		if (!empty($row_entries['brewMead3'])) echo "<strong>Strength:</strong> ".$row_entries['brewMead3'];
		echo "</p>";   
		?>
        </td>
        <td nowrap><?php echo $row_entries['brewBoxNum']; ?></td>
        <td><p class="box">&nbsp;</p></td>
        <td><p class="box">&nbsp;</p></td>
    </tr>
    <?php } while ($row_entries = mysqli_fetch_assoc($entries));
	
	
	} // end foreach ?>
    </tbody>
    </table>
    <?php } ?>
<?php 	
	} // end if (($go == "judging_tables" && ($id != "default"))
} // end if ($_SESSION['jPrefsQueued'] == "Y") 
?>

<?php 
// Printing BOS Pull Sheets by Style Type
if ($go == "judging_scores_bos") { ?>
<?php
if ($id == "default") {
	do { $a[] = $row_style_types['id']; } while ($row_style_types = mysqli_fetch_assoc($style_types));
	sort($a);
}
if ($id != "default") $a[] = $id;
foreach ($a as $type) {
	
	$style_type_info = style_type_info($type);
	//echo $style_type_info;
	$style_type_info = explode("^",$style_type_info);

if ($style_type_info[0] == "Y") { 

	include(DB.'output_pullsheets_bos.db.php');
	//echo $query_bos."<br>";

?>
		<div class="page-header">
        	<h1>BOS Entries - <?php echo $style_type_info[2]; ?></h1>
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
<table class="table table-striped table-bordered" id="sortable<?php echo $type; ?>">
<thead>
    <tr>
    	<th nowrap>Pull Order</th>
        <th>#</th>
        <th>Style/Sub-Style</th>
        <th>Loc/Box</th>
        <th>Score</th>
        <th>Place</th>
    </tr>
    </thead>
<tbody>
	<?php do {
	include(DB.'output_pullsheets_bos_entries.db.php');
	
	$style = $row_entries_1['brewCategorySort'].$row_entries_1['brewSubCategory'];
	//echo $query_entries_1."<br>";
	//echo $style."<br><br>";
	
	?>
    <tr>
    	<td><p class="box">&nbsp;</p></td>
        <td nowrap>
		<?php 
		if ($view == "entry") echo sprintf("%04s",$row_entries_1['id']); 
		elseif (((NHC) && ($view != "entry")) || ($_SESSION['prefsEntryForm'] == "N")) echo sprintf("%06s",$row_entries_1['brewJudgingNumber']);
		else echo readable_judging_number($row_entries_1['brewCategory'],$row_entries_1['brewJudgingNumber']); 
		?>
        </td>
        <td>
		<?php 
		$style_special = $row_entries_1['brewCategorySort']."^".$row_entries_1['brewSubCategory']."^".$_SESSION['prefsStyleSet'];
		$special = style_convert($style_special,"9");
		//echo $special."<br>";
		$special = explode("^",$special);
		$special = $special[4];
		$strength = $special[5];
		$carb = $special[6];
		$sweet = $special[7];
		echo $style." ".$row_entries_1['brewStyle']."<em><br>".style_convert($row_entries_1['brewCategorySort'],1)."</em>"; 
		if (($row_entries_1['brewInfo'] != "") && ($special == "1")) echo "<p><strong>Required Info: </strong>".$row_entries_1['brewInfo']."</p>";
		if ($row_entries_1['brewComments'] != "") echo "<p><strong>Specifics: </strong>".$row_entries_1['brewComments']."</p>";  
		if (style_convert($style,"5")) echo "<p>"; 
		if (($row_entries_1['brewMead1'] != '') && ($strengh == "1")) echo $row_entries_1['brewMead1']."&nbsp;&nbsp;"; 
		if (($row_entries_1['brewMead2'] != '') && ($carb == "1")) echo $row_entries_1['brewMead2']."&nbsp;&nbsp;"; 
		if (($row_entries_1['brewMead3'] != '') && ($sweet == "1")) echo $row_entries_1['brewMead3']."</p>"; 
		?>
        </td>
        <td nowrap><?php echo $row_entries['brewBoxNum']; ?></td>
        <td><p class="box">&nbsp;</p></td>
        <td><p class="box">&nbsp;</p></td>
    </tr>
    <?php } while ($row_bos = mysqli_fetch_assoc($bos)); ?>
</tbody>
</table>
<?php } else echo "<p class=\"lead\">No entries are eligible.</p>"; 
} 
?>
<?php } ?>
<div style="page-break-after:always;"></div>
<?php } // end if (($go == "judging_scores_bos") && ($id != "default")) 
}
?>