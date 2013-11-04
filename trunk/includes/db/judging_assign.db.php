<?php
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

$query_assignments = sprintf("SELECT * FROM $judging_assignments_db_table WHERE assignTable='%s'", $row_tables_edit['id']);
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
	$r .= '<option value="0" />Do Not Assign</option>';
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
		if ($unavailable) $r = '<div class="orange judge-alert">Already Assigned to This Round</div>';
		//if ($already) $r = '<div class="purple judge-alert">Already Assigned to this Table</div>';
		if ($entry_conflict) $r = '<div class="blue judge-alert">Disabled - Participant has an Entry at this Table</div>';
		if ((!$unavailable) && (!$entry_conflict)) $r = like_dislike($likes,$dislikes,$table_styles);
	}
	else $r = '';
	return $r;
}

function judge_info($uid) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	$query_brewer_info = sprintf("SELECT brewerFirstName,brewerLastName,brewerJudgeLikes,brewerJudgeDislikes,brewerJudgeMead,brewerJudgeRank,brewerJudgeID,brewerStewardLocation,brewerJudgeLocation FROM %s WHERE uid='%s'", $prefix."brewer", $uid);
	$brewer_info = mysql_query($query_brewer_info, $brewing) or die(mysql_error());
	$row_brewer_info = mysql_fetch_assoc($brewer_info);
	$r = $row_brewer_info['brewerFirstName']."^".$row_brewer_info['brewerLastName']."^".$row_brewer_info['brewerJudgeLikes']."^".$row_brewer_info['brewerJudgeDislikes']."^".$row_brewer_info['brewerJudgeMead']."^".$row_brewer_info['brewerJudgeRank']."^".$row_brewer_info['brewerJudgeID']."^".$row_brewer_info['brewerStewardLocation']."^".$row_brewer_info['brewerJudgeLocation'];
	return $r;
}

function flight_entry_count($table_id,$flight) {
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
    $query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE flightTable='%s' AND flightNumber='%s'", $prefix."judging_flights", $table_id, $flight);
    $entry_count = mysql_query($query_entry_count, $brewing) or die(mysql_error());
    $row_entry_count = mysql_fetch_assoc($entry_count);
	return $row_entry_count['count'];
}
?>