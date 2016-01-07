<?php 

/**
 * Module:      participants.admin.php
 * Description: This module houses all participant (brewer) related functionality
 *              including viewing a participant list, add/edit/delete, 
 *              Also provids judging location related functions - add, edit, delete.
 *
 */

/* ---------------- Rebuild Info ---------------------

Beginning with the 1.3.0 release, an effort was begun to separate the programming
layer from the presentation layer for all scripts with this header.

All Admin pages have certain variables in common that build the page:
     
  $output_datatables_head = the output for DataTables placed in the <thead> tag
  $output_datatables_body = the output for DataTables placed in the <tbody> tag
  $output_datatables_add_link = the link to add a record
  $output_datatables_edit_link = the link to edit the record
  $output_datatables_delete_link = the link to delete the record
  $output_datatables_print_link = the link to print the record or output to print
  $output_datatables_other_link = misc use link
  $output_datatables_view_link = the link to view the record's detail
  $output_datatables_actions = compiles all of the "actions" links (edit, delete, print, view, etc.)
  
  $output_datatables_head = "";
  $output_datatables_body = "";
  $output_datatables_add_link = "";
  $output_datatables_edit_link = "";
  $output_datatables_delete_link = "";
  $output_datatables_print_link = "";
  $output_datatables_other_link = "";
  $output_datatables_view_link = "";
  $output_datatables_actions = "";
  
  ADD/EDIT SCREENS VARIABLE
  $output_add_edit = whether to run/display the add/edit functions - default is FALSE

 * ---------------- END Rebuild Info --------------------- */
 
$queued = $_SESSION['jPrefsQueued'];
$location = $row_tables_edit['tableLocation'];

include(DB.'admin_judging_assign.db.php');
 
 // --------------- Custom Functions --------------------- //
 
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
	// returns true a person is unavailable (if they are already assigned to a table/flight in the same round at the same location)
	require(CONFIG.'config.php');
	mysql_select_db($database, $brewing);
	$query_assignments = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE bid='%s' AND assignRound='%s' AND assignLocation='%s'", $prefix."judging_assignments", $bid, $round, $location);
	$assignments = mysql_query($query_assignments, $brewing) or die(mysql_error());
	$row_assignments = mysql_fetch_assoc($assignments);
	//$totalRows_assignments = mysql_num_rows($assignments);
	
	if ($row_assignments['count'] > 0) return TRUE;
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
	
	if (($c > 0) && ($f == 0)) $r .= 'bg-success text-success|<span class="fa fa-thumbs-o-up"></span> <strong>Preferred Style(s).</strong> One or more styles are on the participant&rsquo;s &ldquo;likes&rdquo; list.'; // 1 or more likes matched, color table cell green
	elseif (($c == 0) && ($f > 0)) $r .= 'bg-danger text-danger|<span class="fa fa-thumbs-o-down"></span> <strong>Non-Preferred Style(s).</strong> One or more styles are on the participant&rsquo;s &ldquo;dislikes&rdquo; list.'; // 1 or more dislikes matched, color table cell red
	else $r .="default|<span class=\"fa fa-star-o\"></span> <strong>Available.</strong> Paricipant is available for this round.";
	
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
$unavailable = unavailable($bid,$location,$round,$tid);
$random = random_generator(8,2);
$r = "";
if (entry_conflict($bid,$table_styles)) $disabled = "disabled"; else $disabled = "";
if ($filter == "stewards") $role = "S"; else $role = "J";

// Build the form elements
$r .= '<input type="hidden" name="random[]" value="'.$random.'" />';
$r .= '<input type="hidden" name="bid'.$random.'" value="'.$bid.'" />';
$r .= '<input type="hidden" name="assignRound'.$random.'" value="'.$round.'" />';
$r .= '<input type="hidden" name="assignment'.$random.'" value="'.$role.'" />';
$r .= '<input type="hidden" name="assignLocation'.$random.'" value="'.$location.'" />';

if ($queued == "Y") { 
	if (already_assigned($bid,$tid,"1",$round)) { $selected = "checked"; $default = ""; } else { $selected = ""; $default = "checked"; } 
}

if ($unassign > 0) {
	// Check to see if the participant is already assigned to this round. 
	// If so (function returns a value greater than 0), display the following:
	$r .= '<div class="form-inline">';
	$r .= '<div class="checkbox">';
	$r .= '<label for="unassign'.$random.'">';
	$r .= '<input type="checkbox" id="unassign'.$random.'" name="unassign'.$random.'" value="'.$unassign.'"/>';
	$r .= ' Unassign and...</label>';
	$r .= '</div>';
	$r .= '</div>'; 
	}
	else {
		$r .= '<input type="hidden" name="unassign'.$random.'" value="'.$unassign.'"/>';	
	}

if ($queued == "Y") { // For queued judging only
	//if (already_assigned($bid,$tid,"1",$round)) { $selected = 'checked'; $default = ''; } else { $selected = ''; $default = 'checked'; }
	$r .= '<div class="form-inline">';
	$r .= '<div class="form-group">';
	$r .= '<div class="input-group">';
    $r .= '<label class="radio-inline">';
    $r .= '<input type="radio" name="assignRound'.$random.'" value="'.$round.'" '.$selected.' '.$disabled.' /> Assign to this Table';
    $r .= '</label>';
    $r .= '<label class="radio-inline">';
    $r .= '<input type="radio" name="assignRound'.$random.'" value="0" '.$default.' /> Keep as Assigned';
    $r .= '</label>';
    $r .= '</div>';
	$r .= '</div>';
	}
	else $r .= '<input type="hidden" name="assignTable'.$random.'" value="'.$tid.'" />';

if ($queued == "N") { // Non-queued judging
	// Build the flights DropDown
	$r .= '<select class="selectpicker" name="assignFlight'.$random.'" '.$disabled.'>';
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
		$at_table = at_table($bid,$tid);
		if ($unavailable) $r = "bg-purple text-purple|<span class=\"fa fa-check\"></span> <strong>Assigned.</strong> Paricipant is assigned to another table in this round.";
		if ($entry_conflict) $r = "bg-info text-info|<span class=\"fa fa-ban\"></span> <strong>Disabled.</strong> Participant has an entry at this table.";
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

 // --------------- END Custom Functions --------------------- //

$output_datatables_head = "";
$output_datatables_body = "";
$output_datatables_add_link = "";
$output_datatables_edit_link = "";
$output_datatables_delete_link = "";
$output_datatables_print_link = "";
$output_datatables_other_link = "";
$output_datatables_view_link = "";
$output_datatables_actions = "";


// Build DataTables Header
$output_datatables_head .= "<tr>";
$output_datatables_head .= "<th>Name</th>";
if ($filter == "judges") { 
	$output_datatables_head .= "<th>BJCP Rank</th>";
	$output_datatables_head .= "<th class=\"hidden-xs hidden-sm hidden-md\">BJCP #</th>";
}
for($i=1; $i<$row_flights['flightRound']+1; $i++) {
	if  (table_round($row_tables_edit['id'],$i)) { 
		$output_datatables_head .= "<th>Round ".$i."</th>";
	}
}

$output_datatables_head .= "</tr>";

do {
	$assign_row_color = "";
	$assign_flag = "";
	
	for($i=1; $i<$row_flights['flightRound']+1; $i++) {
		
		if  (table_round($row_tables_edit['id'],$i)) {
			
			$flights_display = "";
			
			if (at_table($row_brewer['uid'],$row_tables_edit['id'])) {
				$assign_row_color = "bg-orange text-orange";
				$assign_flag = "<span class=\"fa fa-check\"></span> <strong>Assigned.</strong> Participant is assigned to this table.";
			}
			
			else {
				$judge_alert = judge_alert($i,$row_brewer['uid'],$row_tables_edit['id'],$location,$judge_info[2],$judge_info[3],$row_tables_edit['tableStyles'],$row_tables_edit['id']);
				$judge_alert = explode("|",$judge_alert);
				$assign_row_color = $judge_alert[0];
				$assign_flag = "<div>".$judge_alert[1]."</div>";
			}
			
			
			//if (at_table($row_brewer['uid'],$row_tables_edit['id'])) $output_datatables_body .= "<div class=\"alert alert-warning\"><strong>Assigned.</strong> Participant is assigned to this table.</div>"; 
			//else $output_datatables_body .= judge_alert($i,$row_brewer['uid'],$row_tables_edit['id'],$location,$judge_info[2],$judge_info[3],$row_tables_edit['tableStyles'],$row_tables_edit['id']);
			$flights_display .= $assign_flag;
			$flights_display .= assign_to_table($row_tables_edit['id'],$row_brewer['uid'],$filter,$total_flights,$i,$location,$row_tables_edit['tableStyles'],$queued);
			
		}
	}
	
	$table_location = "Y-".$row_tables_edit['tableLocation'];
	$judge_info = judge_info($row_brewer['uid']);
	$judge_info = explode("^",$judge_info);
	$bjcp_rank = explode(",",$judge_info[5]);
	$display_rank = bjcp_rank($bjcp_rank[0],1);
	
	if ($judge_info[4] == "Y") $display_rank .= "<br /><em>Certified Mead Judge</em>";
	 
	if (!empty($bjcp_rank[1])) {
		$display_rank .= "<em>".designations($judge_info[5],$bjcp_rank[0])."</em>";
	}
	
	if ($filter == "stewards") $locations = explode(",",$judge_info[7]); 
	else $locations = explode(",",$judge_info[8]);
	
	if (in_array($table_location,$locations)) {
	
		$output_datatables_body .= "<tr class=\"".$assign_row_color."\">";
		
		$output_datatables_body .= "<td nowrap>";
		$output_datatables_body .= "<a href=\"".$base_url."index.php?section=brewer&amp;go=admin&amp;action=edit&amp;filter=".$row_brewer['uid']."&amp;id=".$row_brewer['uid']."\" data-toggle=\"tooltip\" title=\"Edit ".$judge_info[0]." ".$judge_info[1]."&rsquo;s account info\">".$judge_info[1].", ".$judge_info[0]."</a>"; 
		$output_datatables_body .= "</td>";
		
		if ($filter == "judges") { 
			$output_datatables_body .= "<td>";
			$output_datatables_body .= $display_rank;
			$output_datatables_body .= "</td>";
			$output_datatables_body .= "<td class=\"hidden-xs hidden-sm hidden-md\">";
			if (($judge_info[6] != "") && ($judge_info[6] != "0")) $output_datatables_body .= strtoupper($judge_info[6]); 
			else $output_datatables_body .= "N/A";
			$output_datatables_body .= "</td>";
		}
		
		$output_datatables_body .= "<td>".$flights_display."</td>";
		$output_datatables_body .= "</tr>";
	}
} while ($row_brewer = mysql_fetch_assoc($brewer)); 
?>
<div class="bcoem-admin-element hidden-print">
	<div class="row">
	<div class="col-lg-4 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <select class="selectpicker" name="assign_table" id="assign_table" onchange="jumpMenu('self',this,0)" data-width="auto">
        <option value="" disabled selected>Assign <?php if ($filter == "stewards") echo "Stewards"; else echo "Judges"; ?> to Another Table...</option>
            <?php do { ?>
				<option value="index.php?section=admin&amp;action=assign&amp;go=judging_tables&amp;filter=<?php echo $filter; ?>&amp;id=<?php echo $row_tables['id']; ?>"><?php echo "Table ".$row_tables['tableNumber'].": ".$row_tables['tableName']; ?></option>
    		<?php } while ($row_tables = mysql_fetch_assoc($tables)); ?>
        </select>
    </div>
    </div>
</div>
<p>Make sure you have <a href="<?php echo $base_url; ?>index.php?section=admin&go=judging_flights&action=assign&filter=rounds">assigned all tables <?php if ($_SESSION['jPrefsQueued'] == "N") echo "and flights"; ?> to rounds</a> <em>before</em> assigning <?php echo $filter; ?> to a table.</p>
<?php if ($totalRows_judging > 1) { ?>
<p>If no <?php echo $filter; ?> are listed below, no <?php echo rtrim($filter,"s"); ?> indicated that they are available for this table's location. To make <?php echo $filter; ?> available, you will need to edit their preferences via the <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants">participants list</a>.</p>
<?php } ?>

<h4>Assign <span class="text-capitalize"><?php echo $filter; ?></span> to Table<?php echo $row_tables_edit['tableNumber']." &ndash; ".$row_tables_edit['tableName']; $entry_count = get_table_info(1,"count_total",$row_tables_edit['id'],$dbTable,"default"); echo " (".$entry_count." entries)"; ?> <small><?php echo table_location($row_tables_edit['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default"); ?></small></h4>
<p><strong>Number of Flights:</strong> <?php echo $row_flights['flightNumber']; ?>
<?php if ($_SESSION['jPrefsQueued'] == "N") { ?>
<ul class="list-unstyled">
	<?php for($c=1; $c<$row_flights['flightNumber']+1; $c++) { 
	$flight_entry_count = flight_entry_count($row_tables_edit['id'], $c);
	?>
	<li><?php echo "<strong>Flight ".$c.":</strong> ".$flight_entry_count." entries"; ?></li>
    <?php } ?>
</ul>
<?php } ?>
</p>
<?php if ($row_rounds['flightRound'] != "") { ?>	
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : true,
			"sPaginationType" : "full_numbers",
			"bLengthChange" : true,
			"iDisplayLength" : <?php echo round($_SESSION['prefsRecordPaging']); ?>,
			"sDom": 'rtp',
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
			"aaSorting": [[1,'asc'],[0,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				null,
				null,
				null
				<?php if ($_SESSION['jPrefsQueued'] == "N") { ?>,
				null,
				null
				<?php } ?>
				]
			} );
		} );
	</script>
<form name="form1" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?action=update&amp;dbTable=<?php echo $judging_assignments_db_table; ?>&amp;filter=<?php echo $filter; ?>&amp;limit=<?php echo $row_rounds['flightRound']; ?>&amp;view=<?php echo $_SESSION['jPrefsQueued']; ?>&amp;id=<?php echo $row_tables_edit['id']; ?>">
<table class="table table-responsive table-bordered table" id="sortable">
<thead>
 	<?php echo $output_datatables_head; ?>
</thead>
<tbody>
	<?php echo $output_datatables_body; ?>	
</tbody>
</table>
<p><input type="submit" class="btn btn-primary" name="Submit" value="Assign to Table <?php echo $row_tables_edit['tableNumber']; ?>" /></p>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$row_tables_edit['id']); if ($msg != "default") echo "&id=".$row_tables_edit['id']; ?>">
</form>
<?php
//mysql_free_result($styles);
mysql_free_result($tables);
mysql_free_result($tables_edit);
} // end if ($row_rounds['flightRound'] != "")
else { 
	if ($_SESSION['jPrefsQueued'] == "N") "<p>Flights from this table have not been assigned to rounds yet. <a href=\"".$base_url."index.php?section=admin&amp;go=judging_flights&amp;action=assign&amp;filter=rounds\">Assign flights to rounds?</a></p>"; 
	else echo "<p>This table has not been assigned to a round yet. <a href=\"".$base_url."index.php?section=admin&amp;go=judging_flights&amp;action=assign&amp;filter=rounds\">Assign to a round?</a></p>";
	}
?>