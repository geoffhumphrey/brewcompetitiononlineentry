<?php
/**
 * Module:      brewer.db.php
 * Description: This module houses all participant (brewer) related queries
 */

// Editing a single participant query
if (($section == "brewer") && ($action == "edit")) $query_brewer = "SELECT * FROM brewer WHERE id = '$id'";

// Viweing all participants in current comp DB query
elseif (($section == "admin") && ($go == "participants") && ($filter == "default")  && ($dbTable == "default")) {
	$query_brewer = "SELECT * FROM brewer ORDER BY brewerLastName";
	if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
	}

// Viewing available judges query (not assigned)
elseif (($section == "admin") && ($go == "participants") && ($filter == "judges")   && ($dbTable == "default")) {
	$query_brewer = "SELECT * FROM brewer WHERE brewerJudge='Y' ORDER BY brewerLastName";
	if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default"))  $query_brewer .= " LIMIT $start, $display";
	}

// Viewing available stewards query (not assigned)
elseif (($section == "admin") && ($go == "participants") && ($filter == "stewards") && ($dbTable == "default")) {
	$query_brewer = "SELECT * FROM brewer WHERE brewerSteward='Y' ORDER BY brewerLastName";
	if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default"))  $query_brewer .= " LIMIT $start, $display";
	}

// Viewing assigned judges query
elseif (($section == "admin") && ($go == "participants") && ($filter == "assignJudges") && ($dbTable == "default")) { 
	$query_brewer = "SELECT * FROM brewer WHERE brewerAssignment='J' ORDER BY brewerLastName";
	if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default"))  $query_brewer .= " LIMIT $start, $display";
	}

// Viewing assigned stewards query
elseif (($section == "admin") && ($go == "participants") && ($filter == "assignStewards") && ($dbTable == "default")) {
	$query_brewer = "SELECT * FROM brewer WHERE brewerAssignment='S' ORDER BY brewerLastName";
	if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default"))  $query_brewer .= " LIMIT $start, $display";
	}

// Viewing all participants query from archive query
elseif (($section == "admin") && ($go == "participants") && ($filter == "default")  && ($dbTable != "default")) {
	$query_brewer = "SELECT * FROM $dbTable ORDER BY brewerLastName";
	//if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
	}

// Updating assigned judges query
elseif (($section == "admin") && ($go == "judging") && ($filter == "judges")  && ($dbTable == "default") && ($action == "update")) {
	$query_brewer = "SELECT * FROM brewer WHERE brewerAssignment='J' ORDER BY brewerLastName";
	if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
	}

// Updating assigned stewards query
elseif (($section == "admin") && ($go == "judging") && ($filter == "stewards")  && ($dbTable == "default") && ($action == "update")) {
	$query_brewer = "SELECT * FROM brewer WHERE brewerAssignment='S' ORDER BY brewerLastName";
	if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
	}

// Assign Judge query
elseif (($section == "admin") && ($go == "judging") && ($filter == "judges")  && ($dbTable == "default") && ($action == "assign")) { 
	$query_brewer = "SELECT * FROM brewer WHERE brewerJudge='Y' ORDER BY brewerLastName";
	if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
	}

// Assign Steward query
elseif (($section == "admin") && ($go == "judging") && ($filter == "stewards")  && ($dbTable == "default") && ($action == "assign")) {
	$query_brewer = "SELECT * FROM brewer WHERE brewerSteward='Y' ORDER BY brewerLastName";
	if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
	}
	
// Assign staff query
elseif (($section == "admin") && ($go == "judging") && ($filter == "staff")  && ($dbTable == "default") && ($action == "assign")) {
	$query_brewer = "SELECT * FROM brewer ORDER BY brewerLastName";
	if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
	}

// Assign BOS judges query
elseif (($section == "admin") && ($go == "judging") && ($filter == "bos")  && ($dbTable == "default") && ($action == "assign")) {
	$query_brewer = "SELECT * FROM brewer WHERE (brewerJudgeRank='Certified' OR brewerJudgeRank='National' OR brewerJudgeRank LIKE '%Master%') ORDER BY brewerLastName ";
	if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
	}

// Assigned judges at table query	
elseif (($section == "admin") && ($go == "judging_tables") && ($filter == "judges")  && ($dbTable == "default")) { 
	$query_brewer = "SELECT * FROM brewer WHERE brewerAssignment='J' ORDER BY brewerLastName";
	}

// Assigned stewards query
elseif (($section == "admin") && ($go == "judging_tables") && ($filter == "stewards")  && ($dbTable == "default")) {
	$query_brewer = "SELECT * FROM brewer WHERE brewerAssignment='S' ORDER BY brewerLastName";
	}
	
// Assigned staff query
elseif (($section == "admin") && ($go == "judging_tables") && ($filter == "stewards")  && ($dbTable == "default")) {
	$query_brewer = "SELECT * FROM brewer WHERE brewerAssignment='S' ORDER BY brewerLastName";
	}

// Make a participant an admin query
elseif (($section == "admin") && ($go == "make_admin")) {
	$query_brewer = "SELECT * FROM brewer WHERE brewerEmail='$username'";
	}
else $query_brewer = "SELECT * FROM brewer";
$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
$row_brewer = mysql_fetch_assoc($brewer);
$totalRows_brewer = mysql_num_rows($brewer);
?>