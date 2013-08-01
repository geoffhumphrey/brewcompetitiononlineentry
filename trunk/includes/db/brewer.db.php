<?php
/**
 * Module:      brewer.db.php
 * Description: This module houses all participant (brewer) related queries
 */

$query_participant_count = sprintf("SELECT COUNT(*) as 'count' FROM %s", $prefix."brewer");
$result_participant_count = mysql_query($query_participant_count, $brewing) or die(mysql_error());
$row_participant_count = mysql_fetch_assoc($result_participant_count);

// Editing a single participant query
if (($section == "brewer") && ($action == "edit") && ($id == "default")) {
	$query_brewer = sprintf("SELECT * FROM $brewer_db_table WHERE uid = '%s'",  $_SESSION['user_id']);
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
}
	
elseif (($section == "brewer") && ($action == "edit") && ($id != "default")) {
	$query_brewer = sprintf("SELECT * FROM $brewer_db_table WHERE id = '%s'",  $id);
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
}

elseif ($section == "pay") {
	$query_brewer = sprintf("SELECT * FROM $brewer_db_table WHERE uid = '%s'",  $_SESSION['user_id']);
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
}

// Viewing all participants in current comp DB query
elseif (($section == "admin") && ($go == "participants") && ($filter == "default")  && ($dbTable == "default")) {
	$query_brewer = "SELECT * FROM $brewer_db_table ORDER BY brewerLastName";
	if (($row_participant_count['count'] > $_SESSION['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
	}

// Viewing available judges query (not assigned)
elseif (($section == "admin") && ($go == "participants") && ($filter == "judges")   && ($dbTable == "default")) {
	$query_brewer = "SELECT * FROM $brewer_db_table WHERE brewerJudge='Y' ORDER BY brewerLastName";
	if (($row_participant_count['count'] > $_SESSION['prefsRecordLimit']) && ($view == "default"))  $query_brewer .= " LIMIT $start, $display";
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
	}

// Viewing available stewards query (not assigned)
elseif (($section == "admin") && ($go == "participants") && ($filter == "stewards") && ($dbTable == "default")) {
	$query_brewer = "SELECT * FROM $brewer_db_table WHERE brewerSteward='Y' ORDER BY brewerLastName";
	if (($row_participant_count['count'] > $_SESSION['prefsRecordLimit']) && ($view == "default"))  $query_brewer .= " LIMIT $start, $display";
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
	}
/*
// Viewing assigned judges query
elseif (($section == "admin") && ($go == "participants") && ($filter == "assignJudges") && ($dbTable == "default")) { 
	$query_brewer = "SELECT * FROM $brewer_db_table WHERE brewerJudge='Y' AND brewerAssignment='J' ORDER BY brewerLastName";
	if (($row_participant_count['count'] > $_SESSION['prefsRecordLimit']) && ($view == "default"))  $query_brewer .= " LIMIT $start, $display";
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
	}

// Viewing assigned stewards query

elseif (($section == "admin") && ($go == "participants") && ($filter == "assignStewards") && ($dbTable == "default")) {
	$query_brewer = "SELECT * FROM $brewer_db_table WHERE brewerSteward='Y' AND brewerAssignment='S' ORDER BY brewerLastName";
	if (($row_participant_count['count'] > $_SESSION['prefsRecordLimit']) && ($view == "default"))  $query_brewer .= " LIMIT $start, $display";
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
	}
*/
// Viewing all participants query from archive query
elseif (($section == "admin") && ($go == "participants") && ($filter == "default")  && ($dbTable != "default")) {
	$query_brewer = "SELECT * FROM $dbTable ORDER BY brewerLastName";
	//if (($row_participant_count['count'] > $_SESSION['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
	}

// Updating assigned judges query
elseif (($section == "admin") && ($go == "judging") && ($filter == "judges")  && ($dbTable == "default") && ($action == "update")) {
	$query_brewer = "SELECT * FROM $brewer_db_table WHERE brewerAssignment='J' ORDER BY brewerLastName";
	if (($row_participant_count['count'] > $_SESSION['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
	}

// Updating assigned stewards query
elseif (($section == "admin") && ($go == "judging") && ($filter == "stewards")  && ($dbTable == "default") && ($action == "update")) {
	$query_brewer = "SELECT * FROM $brewer_db_table WHERE brewerAssignment='S' ORDER BY brewerLastName";
	if (($row_participant_count['count'] > $_SESSION['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
	}

// Assign Judge query
elseif (($section == "admin") && ($go == "judging") && ($filter == "judges")  && ($dbTable == "default") && ($action == "assign")) { 
	$query_brewer = "SELECT * FROM $brewer_db_table WHERE brewerJudge='Y' ORDER BY brewerLastName";
	if (($row_participant_count['count'] > $_SESSION['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
	}

// Assign Steward query
elseif (($section == "admin") && ($go == "judging") && ($filter == "stewards")  && ($dbTable == "default") && ($action == "assign")) {
	$query_brewer = "SELECT * FROM $brewer_db_table WHERE brewerSteward='Y' ORDER BY brewerLastName";
	if (($row_participant_count['count'] > $_SESSION['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
	}
	
// Assign staff query
elseif (($section == "admin") && ($go == "judging") && ($filter == "staff")  && ($dbTable == "default") && ($action == "assign")) {
	$query_brewer = "SELECT * FROM $brewer_db_table ORDER BY brewerLastName";
	if (($row_participant_count['count'] > $_SESSION['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
	}

// Assign BOS judges query
elseif (($section == "admin") && ($go == "judging") && ($filter == "bos")  && ($dbTable == "default") && ($action == "assign")) {
	//$query_brewer = "SELECT * FROM $brewer_db_table WHERE (brewerJudgeRank='Certified' OR brewerJudgeRank='National' OR brewerJudgeRank LIKE '%Master%') ORDER BY brewerLastName ";
	//$query_brewer = "SELECT * FROM $brewer_db_table WHERE brewerAssignment='J' ORDER BY brewerLastName";
	$query_brewer = "SELECT a.brewerFirstName, a.brewerLastName, a.uid, a.brewerJudgeRank, a.brewerJudgeID, b.uid FROM $brewer_db_table a, $staff_db_table b WHERE b.staff_judge='1' AND a.uid=b.uid";
	if (($row_participant_count['count'] > $_SESSION['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
	}

// Assigned judges at table query	
elseif (($section == "admin") && ($go == "judging_tables") && ($filter == "judges")  && ($dbTable == "default")) { 
	$query_brewer = "SELECT * FROM $staff_db_table WHERE staff_judge='1'";
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
	}

// Assigned staff query
elseif (($section == "admin") && ($go == "judging_tables") && ($filter == "staff")  && ($dbTable == "default")) {
	//$query_brewer = "SELECT * FROM $staff_db_table WHERE staff_steward='1'";
	$query_brewer = "SELECT a.brewerFirstName, a.brewerLastName, a.uid, a.brewerJudgeRank, a.brewerJudgeID, b.uid FROM $brewer_db_table a, $staff_db_table b WHERE b.staff_staff='1' AND a.uid=b.uid";
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
	}
	
// Assigned staff query 
elseif (($section == "admin") && ($go == "judging_tables") && ($filter == "stewards")  && ($dbTable == "default")) {
	//$query_brewer = "SELECT * FROM $brewer_db_table WHERE brewerAssignment='S' ORDER BY brewerLastName";
	$query_brewer = "SELECT a.brewerFirstName, a.brewerLastName, a.uid, a.brewerJudgeRank, a.brewerJudgeID, b.uid FROM $brewer_db_table a, $staff_db_table b WHERE b.staff_steward='1' AND a.uid=b.uid";
	
	
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
	}

// Make a participant an admin query
elseif ((($section == "admin") && ($go == "make_admin")) || (($section == "user") && ($filter == "admin") && ($action == "username"))){
	$query_brewer = "SELECT * FROM $brewer_db_table WHERE id='$id'";
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
	}

elseif (($section == "list") || ($section == "judge") || ($section == "steward")) {
	$query_brewer = sprintf("SELECT * FROM $brewer_db_table WHERE uid = '%s'", $_SESSION['user_id']);
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
	}

//else $query_brewer = "SELECT * FROM $brewer_db_table ORDER BY brewerLastName ASC";

?>