<h2 onload="updateButCount(event);">
<?php 
if ($action == "edit") echo "Edit Flights for Table #".$row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName']; 
elseif ($action == "add") echo "Define Flights for Table #".$row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName']; 
else echo "Define/Edit Flights"; ?></h2>
<?php if ($id =="default") { 
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
			$query_flights = sprintf("SELECT flightTable FROM judging_flights WHERE flightTable='%s'", $row_tables_edit['id']);
			$flights = mysql_query($query_flights, $brewing) or die(mysql_error());
			$row_flights = mysql_fetch_assoc($flights);
			$totalRows_flights = mysql_num_rows($flights);
			$entry_count = get_table_info(1,"count_total",$row_tables_edit['id']);
			?>
            <?php if ($entry_count > $row_judging_prefs['jPrefsFlightEntries']) { ?>
          	<option value="index.php?section=admin&amp;go=judging_flights&amp;&amp;action=<?php if ($totalRows_flights > 0) echo "edit&amp;id=".$row_tables_edit['id']; else echo "add&amp;id=".$row_tables_edit['id']; ?>"><?php echo "Table #:".$row_tables_edit['tableNumber']." ".$row_tables_edit['tableName']; ?></option>
          	<?php } 
			} while ($row_tables_edit = mysql_fetch_assoc($tables_edit)) 
			?>
        </select>
        </td>
    </tr>
</tbody>
</table>
</form>
<?php } else echo "<p>No tables have been defined. Tables <a href='index.php?section=admin&amp;go=judging_tables&amp;action=add'>must be defined</a> before flights can be assigned to them.</p>";
} 
if ($id !="default") { 
// get variables
$entry_count = get_table_info(1,"count_total",$row_tables_edit['id']);
$flight_count = ceil($entry_count/$row_judging_prefs['jPrefsFlightEntries']);
$round_count = $row_tables_edit['tableRound']; 
?>

<script type="text/javascript">
function updateButCount(e)
{

// Get event from W3C or IE event model
var e = e || window.event; 

// Test that appropriate features are supported
if ( !document.getElementsByTagName
|| !document.getElementById
) return;

// Initialise variable for keeping count
var butCount = {
	<?php for($i=1; $i<$flight_count+1; $i++) { 
	echo "flight".$i.":0, ";
	}
	?>
	num:0
	
	};
//var butSummary = 'Answers cleared';

// Event may have come from load, click or reset
// If event was from 'reset', skip counting yes/no
if (e && 'reset' != e.type){

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
<p onload="updateButCount(event);">Based upon your <a href="index.php?section=admin&amp;go=judging_preferences">competition organization preferences</a>, this table can be divided into <?php echo $flight_count; ?> flights. For each entry below, designate the flight in which it will be judged.</p>
<table class="dataTable" style="margin: 0 0 20px 0;">
<tbody>
<tr>
  	<td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a class="data" href="index.php?section=admin">Back to Admin</a></td> 
  	<td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a class="data" href="index.php?section=admin&go=judging_tables">Back to Tables List</a></td>
    <td class="dataList" nowrap="nowrap"><span class="icon"><img src="images/note_add.png" alt="Define Another Flight"></span><a class="data" href="index.php?section=admin&go=judging_flights">Define/Edit Another Flight</a></td>
</tr>
</tbody>
</table>
<form name="flights" method="post" action="includes/process.inc.php?action=<?php echo $action; ?>&amp;dbTable=<?php echo $go; ?>" onreset="updateButCount(event);">
<table class="dataTable" id="flightCount" onclick="updateButCount(event);">
<thead>
	<tr>
    	<th class="dataList" width="1%" nowrap="nowrap">#</th>
        <th class="dataList" width="35%">Category</th>
        <?php for($i=1; $i<$flight_count+1; $i++) { ?>
    	<th class="dataList" width="1%" nowrap="nowrap">Flight <?php echo $i; ?></th>
		<?php } ?>
        <th class="dataList">Special Ingredients/Classic Style</th>
    </tr>
</thead>
<tbody>
	<?php 
	$a = explode(",", $row_tables_edit['tableStyles']); 
	
	foreach (array_unique($a) as $value) {
		$query_styles = sprintf("SELECT brewStyle FROM styles WHERE id='%s'", $value);
		$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
		$row_styles = mysql_fetch_assoc($styles);
		
		$query_entries = sprintf("SELECT id,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewInfo FROM brewing WHERE brewStyle='%s' AND brewPaid='Y' AND brewReceived='Y' ORDER BY brewCategorySort,brewSubCategory", $row_styles['brewStyle']);
		$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
		$row_entries = mysql_fetch_assoc($entries);
		$style = $row_entries['brewCategory'].$row_entries['brewSubCategory'];
		
		
		do {	
			if ($action == "edit") {
				$query_flight_number = sprintf("SELECT id,flightNumber,flightEntryID FROM judging_flights WHERE flightEntryID='%s'", $row_entries['id']);
				$flight_number = mysql_query($query_flight_number, $brewing) or die(mysql_error());
				$row_flight_number = mysql_fetch_assoc($flight_number);	
			}
		
	?>
	<tr <?php echo "style=\"background-color:$color\""; ?>>
		<td><?php echo $row_entries['id']; ?>
        <input type="hidden" name="id[]" value="<?php if ($action == "add") echo $row_entries['id']; if ($action == "edit") echo $row_flight_number['id']?>" />
        <input type="hidden" name="flightTable" value="<?php echo $row_tables_edit['id']; ?>" />
        </td>
        <td><?php echo $style." ".style_convert($row_entries['brewCategorySort'],1).": ".$row_entries['brewStyle']; ?></td>
        <?php for($i=1; $i<$flight_count+1; $i++) { ?>
    	<td class="data"><input type="radio" name="flightNumber<?php if ($action == "add") echo $row_entries['id']; if ($action == "edit") echo $row_flight_number['id']; ?>" value="flight<?php echo $i; ?>" <?php if (($action == "add") && ($i == 1)) echo "checked"; if (($action == "edit") && ($row_flight_number['flightNumber'] == $i)) echo "checked"; ?>></td>
		<?php } ?>
        <td><?php echo $row_entries['brewInfo']; ?></td>
	</tr>
    <?php if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
    <?php } while ($row_entries = mysql_fetch_assoc($entries));
	mysql_free_result($styles);
	mysql_free_result($entries);
	} // end foreach ?>
    <tr>
        <td class="bdr1T"><input type="submit" class="button" value="<?php if ($action == "edit") echo "Update"; else echo "Submit"; ?>"></td>
        <td class="bdr1T" style="text-align:right;">Total Entries per Flight (of <?php echo $entry_count; ?>):</td>
        <?php for($i=1; $i<$flight_count+1; $i++) { ?>
    	<td class="bdr1T" id="flight<?php echo $i; ?>"></td>
        <?php } ?>
        <td class="bdr1T">&nbsp;</td>
    </tr>
</tbody>
</table>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER']); ?>">
</form>
<?php } // end if ($id !="default") ?>