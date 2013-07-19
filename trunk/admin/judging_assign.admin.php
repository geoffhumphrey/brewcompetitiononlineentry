<?php 
$queued = $_SESSION['jPrefsQueued'];
$location = $row_tables_edit['tableLocation'];

$query_table_location = sprintf("SELECT * FROM %s WHERE id='%s'",$prefix."judging_flights", $location);
$table_location = mysql_query($query_table_location, $brewing) or die(mysql_error());
$row_table_location = mysql_fetch_assoc($table_location);

$query_rounds = sprintf("SELECT flightRound FROM %s WHERE flightTable='%s' ORDER BY flightRound DESC LIMIT 1", $prefix."judging_flights", $row_tables_edit['id']);
$rounds = mysql_query($query_rounds, $brewing) or die(mysql_error());
$row_rounds = mysql_fetch_assoc($rounds);

$query_flights = sprintf("SELECT * FROM %s WHERE flightTable='%s' ORDER BY flightNumber DESC LIMIT 1", $prefix."judging_flights", $row_tables_edit['id']);
$flights = mysql_query($query_flights, $brewing) or die(mysql_error());
$row_flights = mysql_fetch_assoc($flights);
$total_flights = $row_flights['flightNumber'];

$query_assignments = sprintf("SELECT * FROM $judging_assignments_db_table WHERE assignTable='$id'", $filter);
$assignments = mysql_query($query_assignments, $brewing) or die(mysql_error());
$row_assignments = mysql_fetch_assoc($assignments);
$totalRows_assignments = mysql_num_rows($assignments);


function table_round($tid,$round) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	// get the round where the flight is assigned to
	$query_flight_round = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE flightTable='%s' AND flightRound='%s' LIMIT 1", $prefix."judging_flights", $tid, $round);
	$flight_round = mysql_query($query_flight_round, $brewing) or die(mysql_error());
	$row_flight_round = mysql_fetch_assoc($flight_round);
	if ($row_flight_round['count'] > 0) return TRUE; else return FALSE;
    }

function flight_round($tid,$flight,$round) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	// get the round where the flight is assigned to
	$query_flight_round = sprintf("SELECT flightRound FROM %s WHERE flightTable='%s' AND flightNumber='%s' LIMIT 1", $prefix."judging_flights", $tid, $flight);
	$flight_round = mysql_query($query_flight_round, $brewing) or die(mysql_error());
	$row_flight_round = mysql_fetch_assoc($flight_round);
	if ($row_flight_round['flightRound'] == $round) return TRUE; else return FALSE;
    }

function already_assigned($bid,$tid,$flight,$round) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	$query_assignments = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE (bid='%s' AND assignTable='%s' AND assignFlight='%s' AND assignRound='%s')", $prefix."judging_assignments", $bid, $tid, $flight, $round);
	$assignments = mysql_query($query_assignments, $brewing) or die(mysql_error());
	$row_assignments = mysql_fetch_assoc($assignments);
	if ($row_assignments['count'] == 1) return TRUE; 
	else return FALSE;
	}

function at_table($bid,$tid) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	$query_assignments = sprintf("SELECT assignTable FROM %s WHERE bid='%s'", $prefix."judging_assignments", $bid);
	$assignments = mysql_query($query_assignments, $brewing) or die(mysql_error());
	$row_assignments = mysql_fetch_assoc($assignments);
	$a = array();
	do {
		$a[] .= $row_assignments['assignTable']; 
	} while ($row_assignments = mysql_fetch_assoc($assignments));
	if (in_array($tid,$a)) return TRUE;
	else return FALSE;
	//return implode(",",$a);
}

function unavailable($bid,$location,$round,$tid) { 
    // returns true a person is unavailable if they are already assigned to a table/flight in the same round at the same location
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	$query_assignments = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE bid='%s' AND assignRound='%s' AND assignLocation='%s'", $prefix."judging_assignments", $bid, $round, $location);
	$assignments = mysql_query($query_assignments, $brewing) or die(mysql_error());
	$row_assignments = mysql_fetch_assoc($assignments);
	//$totalRows_assignments = mysql_num_rows($assignments);
	
	if ($row_assignments['count'] > 0) {
		/*$query_table_location = sprintf("SELECT * FROM $judging_tables_db_table WHERE id='%s'",$row_assignments['assignTable']);
		$table_location = mysql_query($query_table_location, $brewing) or die(mysql_error());
        $row_table_location = mysql_fetch_assoc($table_location);
		if ($row_table_location['tableLocation'] == $location) 
		*/
		return TRUE;
	    }
	//else $r = "0";
	else return FALSE;
    }

function like_dislike($likes,$dislikes,$styles) { 
    // if a judge in the returned list listed one or more of the substyles
	// included in the table in their "likes" or "dislikes"
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	
	// get the table's associated styles from the "tables" table
	$s = explode(",",$styles);
	$r = "";	
	// check for likes
	if ($likes != "") {
	    $a = explode(",",$likes);
	        foreach ($a as $value) {
		        if (in_array($value,$s)) $b[] = 1; else $b[] = 0;
		    }
	$c = array_sum($b);
	} 
	else $c = 0;
	
	// check for dislikes
	if ($dislikes != "") {
	    $d = explode(",",$dislikes);
	        foreach ($d as $value) {
		       if (in_array($value,$s)) $e[] = 1; else $e[] = 0;
		    }
	$f = array_sum($e);
	} 
	
	else $f = 0;
	
	if (($c > 0) && ($f == 0)) $r .= '<div class="green judge-alert">Preferred Style(s)</div>'; // 1 or more likes matched, color table cell green
	elseif (($c == 0) && ($f > 0)) $r .= '<div class="red judge-alert">Non-Preferred Style(s)</div>'; // 1 or more dislikes matched, color table cell red
	else $r .="";
	
	return $r;
	}

function entry_conflict($bid,$table_styles) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	
	$b = explode(",",$table_styles);
	
	foreach ($b as $style) {
		
		$query_style = sprintf("SELECT brewStyleGroup,brewStyleNum FROM %s WHERE id='%s'", $prefix."styles", $style);
		$style = mysql_query($query_style, $brewing) or die(mysql_error());
		$row_style = mysql_fetch_assoc($style);
		
		$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewBrewerID='%s' AND brewCategorySort='%s' AND brewSubCategory='%s'", $prefix."brewing", $bid, $row_style['brewStyleGroup'],$row_style['brewStyleNum']);
		$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
		$row_entries = mysql_fetch_assoc($entries);
		
		if ($row_entries['count'] > 0) $c[] = 1; else $c[] = 0;			
	}
	$d = array_sum($c);
	if ($d > 0) return TRUE;
}


function unassign($bid,$location,$round,$tid) {
	//if (unavailable($bid,$location,$round,$tid)) {
		require(CONFIG.'config.php');
		mysql_select_db($database, $brewing);
		$query_assignments = sprintf("SELECT id FROM %s WHERE bid='%s' AND assignRound='%s' AND assignLocation='%s'", $prefix."judging_assignments", $bid, $round, $location);
		$assignments = mysql_query($query_assignments, $brewing) or die(mysql_error());
		$row_assignments = mysql_fetch_assoc($assignments);	
		$r = $row_assignments['id'];
	//}
	if ($r > 0) $r = $r;
	else $r = "0";
	//$r = $query_assignments;
	return $r;
}

function assign_to_table($tid,$bid,$filter,$total_flights,$round,$location,$table_styles,$queued) {
// Function almalgamates the above functions to output the correct form elements
// $bid = id of row in the brewer's table
// $tid = id of row in the judging_tables table
// $filter = judges or stewards from encoded URL
// $flight = flight number (query above)
// $round = the round number from the for loop
// $location = id of table's location from the judging_locations table

// Define variables
$unassign = unassign($bid,$location,$round,$tid);
$unavailable = unavailable($bid,$table_location,$round,$tid);
$random = random_generator(8,2);
if (entry_conflict($bid,$table_styles)) $disabled = 'disabled'; else $disabled = '';
if ($filter == "stewards") $role = 'S'; else $role = 'J';

// Build the form elements
$r .= '<input type="hidden" name="random[]" value="'.$random.'" />';
$r .= '<input type="hidden" name="bid'.$random.'" value="'.$bid.'" />';
$r .= '<input type="hidden" name="assignRound'.$random.'" value="'.$round.'" />';
$r .= '<input type="hidden" name="assignment'.$random.'" value="'.$role.'" />';
$r .= '<input type="hidden" name="assignLocation'.$random.'" value="'.$location.'" />';

//if ($disabled == "disabled") $r .= '<div class="disabled">Disabled - Participant has an Entry in this Round</div>'; 
if ($queued == "Y") { 
	if (already_assigned($bid,$tid,"1",$round)) { $selected = 'checked'; $default = ''; } else { $selected = ''; $default = 'checked'; } 
	//if ($selected == 'checked') echo '<div class="purple judge-alert">Assigned to this Table</div>';
}

if ($unassign > 0) {
	// Check to see if the participant is already assigned to this round. 
	// If so (function returns a value greater than 0), display the following:
	$r .= '<input type="checkbox" name="unassign'.$random.'" value="'.$unassign.'"/>';
	$r .= '<span class="data">Unassign and...</span><br>'; 
	}
	else {
		$r .= '<input type="hidden" name="unassign'.$random.'" value="'.$unassign.'"/>';	
	}

if ($queued == "Y") { // For queued judging only
	//if (already_assigned($bid,$tid,"1",$round)) { $selected = 'checked'; $default = ''; } else { $selected = ''; $default = 'checked'; }
	$r .= 'Assign to This Round and Table?<br>';
	$r .= '<input type="radio" name="assignRound'.$random.'" value="'.$round.'" '.$selected.' '.$disabled.' /><span class="data">Yes</span><br><input type="radio" name="assignRound'.$random.'" value="0" '.$default.' /><span class="data">No</span>';
	}
	else $r .= '<input type="hidden" name="assignTable'.$random.'" value="'.$tid.'" />';

if ($queued == "N") { // Non-queued judging
	// Build the flights DropDown
	$r .= '<select name="assignFlight'.$random.'" '.$disabled.'>';
	$r .= '<option value="0" />Do Not Assign to This Round</option>';
		for($f=1; $f<$total_flights+1; $f++) {
			if (flight_round($tid,$f,$round)) { 
				if (already_assigned($bid,$tid,$f,$round)) { $output = 'Assigned'; $selected = 'selected'; $style = ' style="color: #990000;"'; } else { $output = 'Assign'; $selected = ''; $style=''; }
				$r .= '<option value="'.$f.'" '.$selected.$style.' />'.$output.' to Flight '.$f.'</option>';
			}
		} // end for loop
	$r .= '</select>';
	}
return $r;
}
/*
function judge_alert($round,$bid,$tid,$location,$likes,$dislikes,$table_styles,$id) {
	if (table_round($tid,$round)) {
		$unavailable = unavailable($bid,$location,$round,$tid);
		$entry_conflict = entry_conflict($bid,$table_styles);
		$already_assigned = already_assigned($bid,$tid,$flight,$round);
		if ($unavailable == TRUE)$r = '<div class="orange judge-alert">Already Assigned to This Round as a Judge or Steward</div>'; 
		elseif ($entry_conflict == TRUE) $r = '<div class="blue judge-alert">Disabled - Participant has an Entry at this Table</div>';
		else $r = like_dislike($likes,$dislikes,$table_styles);
	}
	else $r = '';
	return $r;
}
*/

function judge_alert($round,$bid,$tid,$location,$likes,$dislikes,$table_styles,$id) {
	if (table_round($tid,$round)) {
		$unavailable = unavailable($bid,$location,$round,$tid);
		$entry_conflict = entry_conflict($bid,$table_styles);
		$at_table = at_table($bid,$tid,$flight,$round);
		//if (strpos($at_table,$tid) !== false) $already = TRUE;
		if ($unavailable) $r = '<div class="orange judge-alert">Already Assigned to This Round as a Judge or Steward</div>';
		//if ($already) $r = '<div class="purple judge-alert">Already Assigned to this Table</div>';
		if ($entry_conflict) $r = '<div class="blue judge-alert">Disabled - Participant has an Entry at this Table</div>';
		if ((!$unavailable) && (!$entry_conflict)) $r = like_dislike($likes,$dislikes,$table_styles);
	}
	else $r = '';
	return $r;
}
?>
<div class="adminSubNavContainer">
		<span class="adminSubNav">
        	<span class="icon"><img src="<?php echo $base_url; ?>images/monitor.png"  /></span><a id="modal_window_link" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=<?php echo $filter; ?>&amp;view=name&amp;tb=view" title="View Assignments by Name">View All <?php if ($filter == "stewards") echo "Steward"; else echo "Judge"; ?> Assignments By Last Name</a>
        </span>
        <span class="adminSubNav">
        	<span class="icon"><img src="<?php echo $base_url; ?>images/monitor.png"  /></span><a id="modal_window_link" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=<?php echo $filter; ?>&amp;view=table&amp;tb=view" title="View Assignments by Table">View All <?php if ($filter == "stewards") echo "Steward"; else echo "Judge"; ?> Assignments By Table</a>
        </span>
        <span class="adminSubNav">
        	<span class="icon data"><img src="<?php echo $base_url; ?>images/monitor.png"  /></span><a id="modal_window_link" href="<?php echo $base_url; ?>output/assignments.php?section=admin&amp;go=judging_assignments&amp;filter=<?php echo $filter; ?>&amp;view=name&amp;tb=view&amp;id=<?php echo $id; ?>" title="View Assignments for this Table">View <?php if ($filter == "stewards") echo "Steward"; else echo "Judge"; ?> Assignments for this Table</a>
        </span>
</div>
<div class="info">Make sure you have <a href="<?php echo $base_url; ?>index.php?section=admin&go=judging_flights&action=assign&filter=rounds">assigned all tables <?php if ($_SESSION['jPrefsQueued'] == "N") echo "and flights"; ?> to rounds</a> <em>before</em> assigning <?php echo $filter; ?> to a table.
<?php if ($totalRows_judging > 1) { ?>
<br />
If no judges are listed below, no judge indicated that they are available for this table's location. To make judges available, you will need to edit their preferences via the <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants">participants list</a>.<?php } ?>
</div>
<h3>Assign <?php if ($filter == "stewards") echo "Stewards"; else echo "Judges"; ?> to Another Table</h3>
<table>
 <tr>
   <td class="dataLabel">Assign <?php if ($filter == "stewards") echo "Stewards"; else echo "Judges"; ?> To:</td>
   <td class="data">
   <select name="assign_table" id="assign_table" onchange="jumpMenu('self',this,0)">
	<option value="">Choose Below:</option>
    <?php do { ?>
	<option value="index.php?section=admin&amp;action=assign&amp;go=judging_tables&amp;filter=<?php echo $filter; ?>&amp;id=<?php echo $row_tables['id']; ?>"><?php echo "Table #".$row_tables['tableNumber'].": ".$row_tables['tableName']; ?></option>
    <?php } while ($row_tables = mysql_fetch_assoc($tables)); ?>
   </select>
  </td>
</tr>
</table>
<h3>Assign <?php if ($filter == "stewards") echo "Stewards"; else echo "Judges"; ?> to Table #<?php echo $row_tables_edit['tableNumber'].": ".$row_tables_edit['tableName']; $entry_count = get_table_info(1,"count_total",$id,$dbTable,"default"); echo " (".$entry_count." entries)"; ?></h3>
<table class="dataTableCompact">
	<tr>
    	<td class="dataLabel">Location:</td>
        <td class="data"><?php echo table_location($row_tables_edit['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default"); ?></td>
     </tr>
     <!-- 
     <tr>
        <td class="dataLabel">Available Judges<br />At This Location:</td>
        <td class="data"><?php // echo available_at_location($row_tables_edit['tableLocation'],$filter,"default"); ?></td>
    </tr>
    -->
<?php if ($row_rounds['flightRound'] != "") { ?>
<?php if ($_SESSION['jPrefsQueued'] == "N") { ?>
	<tr>
    	<td class="dataLabel">Number of Flights:</td>
		<td class="data"><?php echo $row_flights['flightNumber']; ?></td>
    </tr>
    <tr>
    	<td colspan="2">
            <ul>
            <?php 
                for($c=1; $c<$row_flights['flightNumber']+1; $c++) {
                $query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM $judging_flights_db_table WHERE flightTable='%s' AND flightNumber='%s'", $row_tables_edit['id'], $c);
                $entry_count = mysql_query($query_entry_count, $brewing) or die(mysql_error());
                $row_entry_count = mysql_fetch_assoc($entry_count);
                echo "<li>Flight $c: ".$row_entry_count['count']." entries.</li>";
                }
            ?>
            </ul>
<?php } ?>
</table>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : true,
			"sPaginationType" : "full_numbers",
			"bLengthChange" : true,
			"iDisplayLength" : <?php echo round($_SESSION['prefsRecordPaging']); ?>,
			"sDom": 'irtip',
			"bStateSave" : false,
			<?php if ($filter == "judges") { ?>
			"aaSorting": [[1,'desc']],
			<?php } ?>
			<?php if ($filter == "stewards") { ?>
			"aaSorting": [[0,'asc']],
			<?php } ?>
			"bProcessing" : false,
			<?php if ($filter == "judges") { ?>
			"aoColumns": [
				null,
				null,
				null<?php for($i=1; $i<$row_flights['flightRound']+1; $i++) {  
			    if  (table_round($row_tables_edit['id'],$i)) { 
				?>, null<?php } } ?>
				]
			} );
			<?php } ?>
			<?php if ($filter == "stewards") { ?>
			"aoColumns": [
				null<?php for($i=1; $i<$row_flights['flightRound']+1; $i++) {  
			    if  (table_round($row_tables_edit['id'],$i)) { 
				?>, null<?php } } ?>
				]
			} );
	 		<?php } ?>
		} );
</script>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable1').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				null,
				null,
				null
				]
			} );
		} );
	</script>
<form name="form1" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?action=update&amp;dbTable=<?php echo $judging_assignments_db_table; ?>&amp;filter=<?php echo $filter; ?>&amp;limit=<?php echo $row_rounds['flightRound']; ?>&amp;view=<?php echo $_SESSION['jPrefsQueued']; ?>&amp;id=<?php echo $id; ?>">
<table class="dataTable">
    <tr>
        <td width="45%">
            <table class="dataTable bdr1">
            <thead>
                <tr>
                    <th class="dataHeading bdr1B" colspan="2">Legend</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="red" style="color: #FFF;">Red</td>
                    <td class="data red" style="color: #FFF;">One or more styles are on the participant's "dislikes" list.</td> 
                </tr>
                <tr>
                    <td class="green" style="color: #FFF;">Green</td>
                    <td class="data green" style="color: #FFF;">One or more styles are on the participant's "likes" list.</td>
                </tr>
                <tr>
                  <td class="blue" style="color: #FFF;">Blue</td>
                  <td class="data blue" style="color: #FFF;">Disabled; participant has an entry at this table.</td>
                </tr>
                <tr>
                  <td class="purple" style="color: #FFF;">Purple</td>
                  <td class="data purple" style="color: #FFF;">Particpant is already assigned to this table/flight.</td>
                </tr>
                <tr>
                    <td class="orange">Orange</td>
                    <td class="data orange">Participant is already assigned to this round at another table/flight.</td> 
                </tr>
            </tbody>
            </table>
        </td>
        <td>
        	<table class="dataTable bdr1">
            <tr>
            	<th class="dataHeading bdr1B">Judges/Stewards Assigned to this Table</th>
            </tr>
            <tr>
            <td style="padding:0;">
            <table class="dataTable" id="sortable1">
                <thead>
                    <tr style="display:none">
                        <td class="dataHeading bdr1B">Name</td>
                        <td class="dataHeading bdr1B">Assignment</td>
                        <td class="dataHeading bdr1B">Rank</td>
                    </tr>
                </thead>
                <tbody>
                	<?php do { 
					$judge_info = explode("^",brewer_info($row_assignments['bid']));
					?>
                    <tr>
                        <td width="15%" nowrap="nowrap"><?php echo ucwords(strtolower($judge_info['1'])).", ".ucwords(strtolower($judge_info['0'])); ?></td>
                        <td class="data" width="10%"><?php if ($row_assignments['assignment'] == "S") echo "Steward"; else echo "Judge"; ?></td>
                        <td class="data"><?php echo str_replace(",",", ",$judge_info['3']); ?></td>
                    </tr>
                    <?php } while ($row_assignments = mysql_fetch_assoc($assignments)); ?>
                </tbody>
            </table>
            </td>
            </tr>
            </table>
        </td>
    </tr>
</table>

<p><input type="submit" class="button" name="Submit" value="Assign to Table #<?php echo $row_tables_edit['tableNumber']; ?>" /></p>
<table class="dataTable" id="sortable">
<thead>
 	<tr>
  		<th class="dataHeading bdr1B" width="20%">Name</th>
        <?php if ($filter == "judges") { ?>
        <th class="dataHeading bdr1B" width="8%">BJCP Rank</th>
        <th class="dataHeading bdr1B" width="8%">BJCP #</th>
        <?php } 
   			for($i=1; $i<$row_flights['flightRound']+1; $i++) {  
			    if  (table_round($row_tables_edit['id'],$i)) { 
			?>
  		<th class="dataHeading bdr1B">Round <?php echo $i; ?></th>
        	<?php
			   } 
			} 
			?>
	</tr>
</thead>
<tbody>
<?php do { 
$table_location = "Y-".$row_tables_edit['tableLocation'];
$query_judge_info = sprintf("SELECT brewerFirstName,brewerLastName,brewerJudgeLikes,brewerJudgeDislikes,brewerJudgeMead,brewerJudgeRank,brewerJudgeID,brewerStewardLocation,brewerJudgeLocation FROM %s WHERE uid='%s'",$brewer_db_table,$row_brewer['uid']);
$judge_info = mysql_query($query_judge_info, $brewing) or die(mysql_error());
$row_judge_info = mysql_fetch_assoc($judge_info);

$bjcp_rank = explode(",",$row_judge_info['brewerJudgeRank']);
$display_rank = bjcp_rank($bjcp_rank[0],1);
if ($row_judge_info['brewerJudgeMead'] == "Y") $display_rank .= "<br /><em>Certified Mead Judge</em>"; 
if (!empty($bjcp_rank[1])) {
	$display_rank .= "<em>".designations($row_judge_info['brewerJudgeRank'],$bjcp_rank[0])."</em>";
}

if ($filter == "stewards") $locations = explode(",",$row_judge_info['brewerStewardLocation']); else $locations = explode(",",$row_judge_info['brewerJudgeLocation']);
if (in_array($table_location,$locations)) {
?>
	<tr> 
    	<td nowrap="nowrap"><?php echo $row_judge_info['brewerLastName'].", ".$row_judge_info['brewerFirstName']; //echo " - ".$row_brewer['id']; ?></td>
        <?php if ($filter == "judges") { ?>
        <td nowrap="nowrap"><?php echo $display_rank; ?></td>
        <td nowrap="nowrap"><?php if (($row_judge_info['brewerJudgeID'] != "") && ($row_judge_info['brewerJudgeID'] != "0")) echo strtoupper($row_judge_info['brewerJudgeID']); else echo "N/A"; ?></td>
        <?php } ?>
		<?php for($i=1; $i<$row_flights['flightRound']+1; $i++) {  
			    if  (table_round($row_tables_edit['id'],$i)) {  
		?>
        <td class="data">
        <?php 
		
		if (at_table($row_brewer['uid'],$id)) echo '<div class="purple judge-alert">Already Assigned to this Table</div>'; 
		else echo judge_alert($i,$row_brewer['uid'],$id,$location,$row_judge_info['brewerJudgeLikes'],$row_judge_info['brewerJudgeDislikes'],$row_tables_edit['tableStyles'],$id);
		?>
        <?php echo assign_to_table($id,$row_brewer['uid'],$filter,$total_flights,$i,$location,$row_tables_edit['tableStyles'],$queued); ?> 
        </td>
		<?php }
		} // end for loop ?>
    </tr> 
<?php }
} while ($row_brewer = mysql_fetch_assoc($brewer)); 
?>	
</tbody>
</table>
<p><input type="submit" class="button" name="Submit" value="Assign to Table #<?php echo $row_tables_edit['tableNumber']; ?>" /></p>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); if ($msg != "default") echo "&id=".$id; ?>">
</form>
<?php
//mysql_free_result($styles);
mysql_free_result($tables);
mysql_free_result($tables_edit);
} // end if ($row_rounds['flightRound'] != "")
else { 
	if ($_SESSION['jPrefsQueued'] == "N") "<p>Flights from this table have not been assigned to rounds yet. <a href='index.php?section=admin&amp;go=judging_flights&amp;action=assign&amp;filter=rounds'>Assign flights to rounds?</a></p>"; 
	else echo "<p>This table has not been assigned to a round yet. <a href='index.php?section=admin&amp;go=judging_flights&amp;action=assign&amp;filter=rounds'>Assign to a round?</a></p>";
	}
?>