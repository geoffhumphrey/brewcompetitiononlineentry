<h2 onload="updateButCount(event);">
<?php
if (($action == "edit") && ($id != "default") && ($filter == "default")) echo "Edit Flights for Table #".$row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName']; 
elseif (($action == "add") && ($id != "default") && ($filter == "default")) echo "Define Flights for Table #".$row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName']; 
elseif (($action == "assign") && ($filter == "rounds"))  echo "Assign $assign_to to Rounds"; 
else echo "Define/Edit Flights"; ?></h2>
<div class="adminSubNavContainer">
 	<span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>index.php?section=admin">Back to Admin Dashboard</a>
    </span>
   	<span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>index.php?section=admin&go=judging_tables">Back to Tables List</a>
    </span>
    <?php if (($action != "default") && ($_SESSION['jPrefsQueued'] == "N")) { ?>
    <span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>images/application_form_add.png" alt="Define Another Flight"></span><a href="<?php echo $base_url; ?>index.php?section=admin&go=judging_flights">Define/Edit Flights</a>
    </span>
    <?php } ?>
    <?php if ($filter == "default") { ?>
    <span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>images/application_form_magnify.png" alt="Assign Flights to Rounds" title="Assign Flights to Rounds" /></span><a href="<?php echo $base_url; ?>index.php?section=admin&go=judging_flights&amp;action=assign&amp;filter=rounds">Assign Flights to Rounds</a>
    </span>
    <?php } ?>
</div>
<?php 
if ($filter == "default") { 
if ($totalRows_tables > 0) {
?>
<form>
<table class="dataTable">
<tbody>
	<tr>
    	<td class="dataLabel" width="1%">Choose a Table:</td>
        <td class="data">
        <select name="table_choice" id="table_choice" onchange="jumpMenu('self',this,0)">
          	<option value=""></option>
          	<?php do { 
			
			$table_choose = table_choose($section,$go,$action,$row_tables_edit['id'],$view,"default","flight_choose");
			$table_choose = explode("^",$table_choose);
			if ($table_choose[0] > 0) $table_choose_display = "edit&amp;id=".$table_choose[1]; else $table_choose_display = "add&amp;id=".$table_choose[1];
			
			?>
          	<option value="index.php?section=admin&amp;go=judging_flights&amp;filter=define&amp;action=<?php echo $table_choose_display; ?>"><?php echo "Table #".$row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName']; ?></option>
          	<?php 
			} while ($row_tables_edit = mysql_fetch_assoc($tables_edit)); ?>
        </select>
        </td>
    </tr>
</tbody>
</table>
</form>
<?php } else echo "<p>No tables have been defined. <a href='".$base_url."index.php?section=admin&amp;go=judging_tables&amp;action=add'>Tables must be defined</a> before flights can be assigned to them.</p>";
} 
if (($filter != "default") && ($filter != "rounds"))  { 
// get variables
$entry_count = get_table_info(1,"count_total",$row_tables_edit['id'],$dbTable,"default");
$flight_count = ceil($entry_count/$_SESSION['jPrefsFlightEntries']);
?>

<script type="text/javascript">
function updateButCount(e) {

	// Get event from W3C or IE event model
	var e = e || window.event; 

	// Test that appropriate features are supported
	if (!document.getElementsByTagName || !document.getElementById) return;

	// Initialise variable for keeping count
	var butCount = {
		<?php for($i=1; $i<$flight_count+1; $i++) { echo "flight".$i.":0, ";} ?>
		num:0
	};
	//var butSummary = 'Answers cleared';

	// Event may have come from load, click or reset
	// If event was from 'reset', skip counting yes/no
	if (e && 'reset' != e.type) {

	// Get a collection of the inputs inside x
	var x = document.getElementById('flightCount');
	var rButs = x.getElementsByTagName('input');
	var temp;

	// Loop over all the inputs
		for (var i=0, len=rButs.length; i<len; ++i){
		temp = rButs[i];

		// If the input is a radio button
		if ('radio' == temp.type ) {

		// Add one to the count of radio buttons
		butCount.num += 1;

		// If the button is checked
		if (temp.checked){

		// Add one to butCount.yes or butCount.no as appropriate
		butCount[temp.value] += 1;
		}
	}
}

// Build the summary string - divide butCount.num by two
// to get the number of questions
//butSummary = 'You have answered ' + (butCount.yes + butCount.no)
//+ ' of ' + (butCount.num/2) + ' questions';
}

// Write the totals for yes and no and the summary to the page
<?php for($i=1; $i<$flight_count+1; $i++) { ?>
document.getElementById('<?php echo "flight".$i; ?>').innerHTML = butCount.<?php echo "flight".$i; ?>;
<?php } ?>
// document.getElementById('summary').innerHTML = butSummary;
}
</script>
<?php 
echo "<p><span class='dataLabel'>Table Location:</span>".table_location($row_tables_edit['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default")."</p>"; ?>
<p onload="updateButCount(event);">Based upon your <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_preferences">competition organization preferences</a>, this table can be divided into <?php echo readable_number($flight_count); ?> flights. For each entry below, designate the flight in which it will be judged.</p>
<form name="flights" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?action=<?php echo $action; ?>&amp;dbTable=<?php echo $judging_flights_db_table; ?>" onreset="updateButCount(event);">
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#flightCount').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[1,'asc'],[0,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				<?php for($i=1; $i<$flight_count+1; $i++) { ?>
				{ "asSorting": [  ] },
				<?php } ?>
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				]
			} );

		} );
</script>
<table class="dataTable" id="flightCount" onclick="updateButCount(event);">
<thead>
	<tr>
    	<th class="dataHeading bdr1B">#</th>
        <th class="dataHeading bdr1B" width="30%">Category</th>
        <?php for($i=1; $i<$flight_count+1; $i++) { ?>
    	<th class="dataHeading bdr1B" width="1%" nowrap="nowrap">Flight <?php echo $i; ?></th>
		<?php } ?>
        <th class="dataHeading bdr1B"width="10%">Round</th>
        <th class="dataHeading bdr1B">Special Ingredients/Classic Style</th>
    </tr>
</thead>
<tbody>
	<?php 
	$a = explode(",", $row_tables_edit['tableStyles']); 

	foreach (array_unique($a) as $value) {
		
		$style_name = style_convert($value,"8");
		
		include(DB.'judging_flights.db.php');
		
		do {
			
			if ($action == "edit") {
				$flight_number = flight_entry_info($row_entries['id']);
				$flight_number = explode("^",$flight_number);
				$random = random_generator(7,2);
			}

	?>
	<tr>
		<td width="1%" nowrap="nowrap"><?php echo readable_judging_number($row_entries['brewCategory'],$row_entries['brewJudgingNumber']);  ?>
        <input type="hidden" name="id[]" value="<?php if ($action == "add") echo $row_entries['id']; if (($action == "edit") && ($flight_number[0] != "")) echo $flight_number[0]; else echo $random ?>" />
        <input type="hidden" name="flightTable" value="<?php echo $row_tables_edit['id']; ?>" />
        <input type="hidden" name="flightEntryID<?php if ($action == "add") echo $row_entries['id']; if (($action == "edit") && ($flight_number[0] != "")) echo $flight_number[0]; else echo $random; ?>" value="<?php echo $row_entries['id']; ?>" />
        </td>
        <td><?php echo $row_entries['brewCategorySort'].$row_entries['brewSubCategory']." ".style_convert($row_entries['brewCategorySort'],1).": ".$row_entries['brewStyle']; ?></td>
        <?php for($i=1; $i<$flight_count+1; $i++) { ?>
    	<td class="data"><input type="radio" name="flightNumber<?php if ($action == "add") echo $row_entries['id']; if (($action == "edit") && ($flight_number[0] != "")) echo $flight_number[0]; else echo $random; ?>" value="flight<?php echo $i; ?>" <?php if (($action == "add") && ($i == 1)) echo "checked"; if (($action == "edit") && ($flight_number[1] == $i)) echo "checked"; ?>></td>
		<?php } ?>
        <td class="data"><?php if ($action == "edit") echo $flight_number[3]; ?></td>
        <td><?php echo $row_entries['brewInfo']; ?></td>
	</tr>
    <?php if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
    <?php } 
	while ($row_entries = mysql_fetch_assoc($entries));
	} // end foreach ?>
    </tbody>
    <tfoot>
    <tr>
        <td class="bdr1T"><span style="display:none">9999999999</span></td>
        <td class="bdr1T" style="text-align:right;">Total Entries per Flight (of <?php echo $entry_count; ?>):</td>
        <?php for($i=1; $i<$flight_count+1; $i++) { ?>
    	<td class="bdr1T" id="flight<?php echo $i; ?>"></td>
        <?php } ?>
        <td class="bdr1T">&nbsp;</td>
        <td class="bdr1T">&nbsp;</td>
    </tr>
	</tfoot>
</table>
<input type="submit" class="button" value="<?php if ($action == "edit") echo "Update"; else echo "Submit"; ?>">
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>
<?php } // end if ($filter !="default") 
?>

<?php 
if (($action == "assign") && ($filter == "rounds")) { 
	if ($totalRows_tables > 0) { 
?>
<form name="form1" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?action=<?php echo $action; ?>&amp;dbTable=<?php echo $judging_flights_db_table; ?>&amp;filter=<?php echo $filter; ?>" onsubmit="return confirm('Caution!\nALL applicable judging/stewarding assignmens WILL BE DELETED \nIF you have CHANGED a table\'s round assignment.\nDo you wish to continue?');">
<p style="margin-top: 3em"><input type="submit" class="button" value="Assign"></p>
<?php 
		do { $a[] = $row_tables_edit['id']; } while ($row_tables_edit = mysql_fetch_assoc($tables_edit));
		
		foreach (array_unique($a) as $flight_table) {
			
			include(DB.'judging_flights.db.php');
			
?>
	<h3 style="margin-top: 3em;">Table <?php echo $row_tables['tableNumber'].": ".$row_tables['tableName']; if (($totalRows_flights > 0) && ($_SESSION['jPrefsQueued'] == "N")) { ?>&nbsp;&nbsp;<span class="icon"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_flights&amp;filter=define&amp;action=edit&amp;id=<?php echo $flight_table; ?>"><img src="<?php echo $base_url; ?>images/application_form_edit.png" alt="Edit the <?php echo $row_tables['tableName']; ?> Flights" title="Edit the <?php echo $row_tables['tableName']; ?> Flights"/></a></span><?php }  if (($totalRows_flights == 0) && ($_SESSION['jPrefsQueued'] == "N")) { ?>&nbsp;&nbsp;<span class="icon"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_flights&amp;action=add&amp;id=<?php echo $flight_table; ?>" alt="Define Flights for <?php echo $row_tables['tableName']; ?>" title="Define Flights for <?php echo $row_tables['tableName']; ?>"><img src="<?php echo $base_url; ?>images/application_form_add.png"></a></span><?php } ?></h3>

	<p><strong>Location:</strong> <?php echo $row_table_location['judgingLocName']." &ndash; ".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_table_location['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time") ?> (<?php echo $row_table_location['judgingRounds']; ?> rounds <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging&amp;action=edit&amp;id=<?php echo $row_table_location['id']; ?>" title="Edit the <?php echo $row_table_location['judgingLocName']; ?> location">defined for this location</a>).</p>
	<?php 
	if ($totalRows_flights > 0) {
		if ($_SESSION['jPrefsQueued'] == "N") $flight_no_total = $row_flights['flightNumber']; else $flight_no_total = 1;
	
		for($i=1; $i<$flight_no_total+1; $i++) { 
			$flight_round_number = flight_round_number($flight_table,$i);
			$random = random_generator(7,2);
		?>
		<p>Assign <?php if ($_SESSION['jPrefsQueued'] == "N") echo "Flight $i"; else echo "Table"; ?> to:
        <span class="data">
        <input type="hidden" name="id[]" value="<?php echo $random ?>" />
        <input type="hidden" name="flightTable<?php echo $random ?>" value="<?php echo $row_tables['id']; ?>" />
        <input type="hidden" name="flightNumber<?php echo $random ?>" value="<?php echo $i; ?>" />
        <input type="hidden" name="flightRoundPrevious<?php echo $random ?>" value="<?php echo $flight_round_number; ?>" />
        <select name="flightRound<?php echo $random ?>">
        <option value="">Choose Below:</option>
        <?php for($r=1; $r<$row_table_location['judgingRounds']+1; $r++) { ?>
		<option value="<?php echo $r; ?>" <?php if ($flight_round_number == $r) echo "selected"; ?>>Round <?php echo $r; ?></option>
		<?php } ?>
		</select>
        </span>
        </p>
		<?php }
	} else echo "<p>No flights have been defined.</p>";
  } ?>
<p style="margin-top: 3em"><input type="submit" class="button" value="Assign"></p>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>
<?php } // end if ($totalRows_tables > 0) ?>
<?php } // end if ($action == "assign") ?>