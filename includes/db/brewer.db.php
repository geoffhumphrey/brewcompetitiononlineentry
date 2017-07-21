<?php
/**
 * Module:      brewer.db.php
 * Description: This module houses all participant (brewer) related queries
 */

include (DB.'admin_participants.db.php');

if (NHC) {
	// Custom code for AHA - possiblity of inclusion in a future version
	$query_clubs = "SELECT * FROM nhcclubs ORDER BY IDClub ASC";
	$clubs = mysqli_query($connection,$query_clubs) or die (mysqli_error($connection));
	$row_clubs = mysqli_fetch_assoc($clubs);
}

// Editing a single participant query
if (($section == "brewer") && ($action == "edit") && ($id == "default")) {
	$query_brewer = sprintf("SELECT * FROM %s WHERE uid = '%s'", $brewer_db_table, $_SESSION['user_id']);
	$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
	$row_brewer = mysqli_fetch_assoc($brewer);
	$totalRows_brewer = mysqli_num_rows($brewer);
}

elseif ($section == "notes") {
	
	// @single
	$query_brewer = "SELECT * FROM $brewer_db_table WHERE brewerJudgeNotes IS NOT NULL";
	$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
	$row_brewer = mysqli_fetch_assoc($brewer);
	$totalRows_brewer = mysqli_num_rows($brewer);
}
	
elseif (($section == "brewer") && ($action == "edit") && ($id != "default")) {
	$query_brewer = sprintf("SELECT * FROM $brewer_db_table WHERE id = '%s'",  $id);
	$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
	$row_brewer = mysqli_fetch_assoc($brewer);
	$totalRows_brewer = mysqli_num_rows($brewer);
}

elseif ($section == "pay") {
	$query_brewer = sprintf("SELECT * FROM $brewer_db_table WHERE uid = '%s'",  $_SESSION['user_id']);
	$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
	$row_brewer = mysqli_fetch_assoc($brewer);
	$totalRows_brewer = mysqli_num_rows($brewer);
}

// Viewing all participants in current comp DB query
// @single
elseif ((($section == "admin") && ($go == "participants") && ($filter == "default")  && ($dbTable == "default")) || ($section == "participant_summary") || ($section == "particpant-entries")) {
	if (SINGLE) $query_brewer = sprintf("SELECT * FROM %s WHERE FIND_IN_SET('%s',brewerCompParticipant) > 0", $brewer_db_table, $_SESSION['comp_id']);
	else $query_brewer = sprintf("SELECT * FROM %s ORDER BY brewerLastName", $brewer_db_table);
	$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
	$row_brewer = mysqli_fetch_assoc($brewer);
	$totalRows_brewer = mysqli_num_rows($brewer);
}


// @single
elseif ((($section == "admin") && ($go == "participants") && ($filter == "with_entries")  && ($dbTable == "default"))) {
	$query_brewer = sprintf("SELECT b.uid, b.brewerEmail AS 'Email', cb . *	FROM (SELECT brewBrewerLastName, brewBrewerFirstName, brewBrewerID, GROUP_CONCAT( id ORDER BY id ) AS 'Entries' FROM %s GROUP BY brewBrewerLastName, brewBrewerFirstName, brewBrewerID) cb, %s b WHERE cb.brewBrewerID = b.uid", $prefix."brewing", $prefix."brewer");
	if (SINGLE) $query_brewer .= sprintf(" AND FIND_IN_SET('%s',brewerCompParticipant) > 0", $_SESSION['comp_id']);
	$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
	$row_brewer = mysqli_fetch_assoc($brewer);
	$totalRows_brewer = mysqli_num_rows($brewer);
}

// Viewing available judges query (not assigned)
elseif (($section == "admin") && ($go == "participants") && ($filter == "judges") && ($dbTable == "default")) {
	
	// @single
	if (SINGLE) include (SSO.'available_judges.db.php');
	
	else {
		
		$query_brewer = "SELECT * FROM $brewer_db_table";
		if ($id == "default") $query_brewer .= " WHERE brewerJudge='Y'";
		if ($id != "default") $query_brewer .= sprintf(" WHERE id='%s'",$id);	
		$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
		$row_brewer = mysqli_fetch_assoc($brewer);
		$totalRows_brewer = mysqli_num_rows($brewer);
	
	}
}
	
// Viewing available stewards query (not assigned)
elseif (($section == "admin") && ($go == "participants") && ($filter == "stewards") && ($dbTable == "default")) {
	
	
	// @single
	if (SINGLE) include (SSO.'available_stewards.db.php');
	
	else {
		
		$query_brewer = "SELECT * FROM $brewer_db_table";
		if ($id == "default") $query_brewer .= " WHERE brewerSteward='Y'";
		if ($id != "default") $query_brewer .= sprintf(" WHERE id='%s'",$id);	
		$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
		$row_brewer = mysqli_fetch_assoc($brewer);
		$totalRows_brewer = mysqli_num_rows($brewer);
	
	}
	
}

// Viewing all participants from archive query
// @single - needed?
elseif (($section == "admin") && ($go == "participants") && ($filter == "default")  && ($dbTable != "default")) {
	$query_brewer = "SELECT * FROM $dbTable ORDER BY brewerLastName";
	$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
	$row_brewer = mysqli_fetch_assoc($brewer);
	$totalRows_brewer = mysqli_num_rows($brewer);
}

// Updating assigned judges query
elseif (($section == "admin") && ($go == "judging") && ($filter == "judges")  && ($dbTable == "default") && ($action == "update")) {
	
	
	// @single
	if (SINGLE) include (SSO.'assigned_judges.db.php');
	
	else {
	
		$query_brewer = "SELECT * FROM $brewer_db_table WHERE brewerAssignment='J'";
		$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
		$row_brewer = mysqli_fetch_assoc($brewer);
		$totalRows_brewer = mysqli_num_rows($brewer);
	
	}
	
}

// Updating assigned stewards query
elseif (($section == "admin") && ($go == "judging") && ($filter == "stewards")  && ($dbTable == "default") && ($action == "update")) {
	
	// @single
	if (SINGLE) include (SSO.'assigned_stewards.db.php');
	
	else {
	
		$query_brewer = "SELECT * FROM $brewer_db_table WHERE brewerAssignment='S'";
		if (($row_participant_count['count'] > $_SESSION['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
		$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
		$row_brewer = mysqli_fetch_assoc($brewer);
		$totalRows_brewer = mysqli_num_rows($brewer);
	
	}
}

// Assign Judge query
elseif (($section == "admin") && ($go == "judging") && ($filter == "judges")  && ($dbTable == "default") && ($action == "assign")) { 

	if (SINGLE) include (SSO.'available_judges.db.php');
	
	else {
	
		$query_brewer = "SELECT * FROM $brewer_db_table WHERE brewerJudge='Y'";
		$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
		$row_brewer = mysqli_fetch_assoc($brewer);
		$totalRows_brewer = mysqli_num_rows($brewer);
	
	}
}

// Assign Steward query
elseif (($section == "admin") && ($go == "judging") && ($filter == "stewards")  && ($dbTable == "default") && ($action == "assign")) {
	
	if (SINGLE) include (SSO.'available_stewards.db.php');
	
	else {
		
		$query_brewer = "SELECT * FROM $brewer_db_table WHERE brewerSteward='Y'";
		$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
		$row_brewer = mysqli_fetch_assoc($brewer);
		$totalRows_brewer = mysqli_num_rows($brewer);
	
	}
}
	
// Assign staff query
elseif (($section == "admin") && ($go == "judging") && ($filter == "staff")  && ($dbTable == "default") && ($action == "assign")) {
	$query_brewer = "SELECT * FROM $brewer_db_table";
	if (SINGLE) $query_brewer .= sprintf(" WHERE FIND_IN_SET('%s',brewerCompParticipant) > 0", $_SESSION['comp_id']);
	if ((!SINGLE) && ($view == "yes")) $query_brewer .= " WHERE brewerStaff='Y'";
	if ((SINGLE) && ($view == "yes")) $query_brewer .= " AND brewerStaff='Y'";
	$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
	$row_brewer = mysqli_fetch_assoc($brewer);
	$totalRows_brewer = mysqli_num_rows($brewer);
}

// Assign BOS judges query
elseif (($section == "admin") && ($go == "judging") && ($filter == "bos")  && ($dbTable == "default") && ($action == "assign")) {
	$query_brewer = "SELECT * FROM $brewer_db_table WHERE (brewerJudgeRank LIKE '%Certified%' OR brewerJudgeRank LIKE'%National%' OR brewerJudgeRank LIKE '%Master%')";
	if (SINGLE) $query_brewer .= sprintf(" AND brewerJudge='%s' AND FIND_IN_SET('%s',brewerCompParticipant) > 0", "Y-".$_SESSION['comp_id'], $_SESSION['comp_id']);
	else $query_brewer .= " AND brewerJudge='Y'";
	$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
	$row_brewer = mysqli_fetch_assoc($brewer);
	$totalRows_brewer = mysqli_num_rows($brewer);
}

// Assigned judges at table query	
elseif (($section == "admin") && ($go == "judging_tables") && ($filter == "judges") && ($dbTable == "default")) { 
	$query_brewer = "SELECT a.brewerFirstName, a.brewerLastName, a.uid, a.brewerJudgeRank, a.brewerJudgeID, b.uid FROM $brewer_db_table a, $staff_db_table b WHERE b.staff_judge='1' AND a.uid=b.uid";
	//$query_brewer = "SELECT * FROM $staff_db_table WHERE staff_judge='1'";
	if (SINGLE) $query_brewer .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
	$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
	$row_brewer = mysqli_fetch_assoc($brewer);
	$totalRows_brewer = mysqli_num_rows($brewer);
}

// Assigned staff query
elseif (($section == "admin") && ($go == "judging_tables") && ($filter == "staff")  && ($dbTable == "default")) {
	//$query_brewer = "SELECT * FROM $staff_db_table WHERE staff_steward='1'";
	$query_brewer = "SELECT a.brewerFirstName, a.brewerLastName, a.uid, a.brewerJudgeRank, a.brewerJudgeID, b.uid FROM $brewer_db_table a, $staff_db_table b WHERE b.staff_staff='1' AND a.uid=b.uid";
	if (SINGLE) $query_brewer .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
	$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
	$row_brewer = mysqli_fetch_assoc($brewer);
	$totalRows_brewer = mysqli_num_rows($brewer);
}
	
// Assigned stewards at table query
elseif (($section == "admin") && ($go == "judging_tables") && ($filter == "stewards")  && ($dbTable == "default")) {
	//$query_brewer = "SELECT * FROM $brewer_db_table WHERE brewerAssignment='S' ORDER BY brewerLastName";
	$query_brewer = "SELECT a.brewerFirstName, a.brewerLastName, a.uid, a.brewerJudgeRank, a.brewerJudgeID, b.uid FROM $brewer_db_table a, $staff_db_table b WHERE b.staff_steward='1' AND a.uid=b.uid";
	if (SINGLE) $query_brewer .= sprintf(" AND comp_id='%s'", $_SESSION['comp_id']);
	$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
	$row_brewer = mysqli_fetch_assoc($brewer);
	$totalRows_brewer = mysqli_num_rows($brewer);
}

// Make a participant an admin or change password query
elseif ((($section == "admin") && ($go == "make_admin")) || (($section == "admin") && ($go == "change_user_password")) || (($section == "user") && ($filter == "admin") && ($action == "username"))){
	$query_brewer = sprintf("SELECT * FROM $brewer_db_table WHERE uid='%s'",$id);
	$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
	$row_brewer = mysqli_fetch_assoc($brewer);
	$totalRows_brewer = mysqli_num_rows($brewer);
}

elseif (($section == "user") && ($filter == "default")) {
	
	$query_brewer = sprintf("SELECT id,uid FROM $brewer_db_table WHERE uid = '%s'", $_SESSION['user_id']);
	$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
	$row_brewer = mysqli_fetch_assoc($brewer);
	
}
	
elseif (($section == "list") || ($section == "judge") || ($section == "steward")) {
	$query_brewer = sprintf("SELECT * FROM $brewer_db_table WHERE uid = '%s'", $_SESSION['user_id']);
	$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
	$row_brewer = mysqli_fetch_assoc($brewer);
	$totalRows_brewer = mysqli_num_rows($brewer);
}

if ($section != "step2") {
	mysqli_select_db($connection,$database);
	$query_brewerID = sprintf("SELECT id,brewerEmail FROM $brewer_db_table WHERE id = '%s'", $id); 
	$brewerID = mysqli_query($connection,$query_brewerID) or die (mysqli_error($connection));
	$row_brewerID = mysqli_fetch_assoc($brewerID);
	$totalRows_brewerID = mysqli_num_rows($brewerID);
} 

if ($section == "step2")  {
	mysqli_select_db($connection,$database);
	$query_brewerID = sprintf("SELECT id,user_name FROM $users_db_table WHERE user_name = '%s'", $go); 
	$brewerID = mysqli_query($connection,$query_brewerID) or die (mysqli_error($connection));
	$row_brewerID = mysqli_fetch_assoc($brewerID);
	$totalRows_brewerID = mysqli_num_rows($brewerID);
}
?>