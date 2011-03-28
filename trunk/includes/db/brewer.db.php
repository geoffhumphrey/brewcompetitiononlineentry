<?php
if (($section == "brewer") && ($action == "edit")) $query_brewer = "SELECT * FROM brewer WHERE id = '$id'";
elseif (($section == "admin") && ($go == "participants") && ($filter == "default")  && ($dbTable == "default")) {
	$query_brewer = "SELECT * FROM brewer ORDER BY brewerLastName";
	if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
	}
elseif (($section == "admin") && ($go == "participants") && ($filter == "judges")   && ($dbTable == "default")) {
	$query_brewer = "SELECT * FROM brewer WHERE brewerJudge='Y' ORDER BY brewerLastName";
	if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default"))  $query_brewer .= " LIMIT $start, $display";
	}
elseif (($section == "admin") && ($go == "participants") && ($filter == "stewards") && ($dbTable == "default")) {
	$query_brewer = "SELECT * FROM brewer WHERE brewerSteward='Y' ORDER BY brewerLastName";
	if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default"))  $query_brewer .= " LIMIT $start, $display";
	}
elseif (($section == "admin") && ($go == "participants") && ($filter == "assignJudges") && ($dbTable == "default")) { 
	$query_brewer = "SELECT * FROM brewer WHERE brewerAssignment='J' ORDER BY brewerLastName";
	if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default"))  $query_brewer .= " LIMIT $start, $display";
	}
elseif (($section == "admin") && ($go == "participants") && ($filter == "assignStewards") && ($dbTable == "default")) {
	$query_brewer = "SELECT * FROM brewer WHERE brewerAssignment='S' ORDER BY brewerLastName";
	if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default"))  $query_brewer .= " LIMIT $start, $display";
	}
elseif (($section == "admin") && ($go == "participants") && ($filter == "default")  && ($dbTable != "default")) {
	$query_brewer = "SELECT * FROM $dbTable ORDER BY brewerLastName";
	//if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
	}
elseif (($section == "admin") && ($go == "judging") && ($filter == "judges")  && ($dbTable == "default") && ($action == "update")) {
	$query_brewer = "SELECT * FROM brewer WHERE brewerAssignment='J' ORDER BY brewerLastName";
	if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
	}
elseif (($section == "admin") && ($go == "judging") && ($filter == "stewards")  && ($dbTable == "default") && ($action == "update")) {
	$query_brewer = "SELECT * FROM brewer WHERE brewerAssignment='S' ORDER BY brewerLastName";
	if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
	}
elseif (($section == "admin") && ($go == "judging") && ($filter == "judges")  && ($dbTable == "default") && ($action == "assign")) { 
	$query_brewer = "SELECT * FROM brewer WHERE brewerJudge='Y' ORDER BY brewerLastName";
	if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
	}
elseif (($section == "admin") && ($go == "judging") && ($filter == "stewards")  && ($dbTable == "default") && ($action == "assign")) {
	$query_brewer = "SELECT * FROM brewer WHERE brewerSteward='Y' ORDER BY brewerLastName";
	if (($totalRows_participant_count > $row_prefs['prefsRecordLimit']) && ($view == "default")) $query_brewer .= " LIMIT $start, $display";
	}
elseif (($section == "admin") && ($go == "judging_tables") && ($filter == "judges")  && ($dbTable == "default")) { 
	$query_brewer = "SELECT * FROM brewer WHERE brewerAssignment='J' ORDER BY brewerLastName";
	}
elseif (($section == "admin") && ($go == "judging_tables") && ($filter == "stewards")  && ($dbTable == "default")) {
	$query_brewer = "SELECT * FROM brewer WHERE brewerAssignment='S' ORDER BY brewerLastName";
	}
elseif (($section == "admin") && ($go == "make_admin")) {
	$query_brewer = "SELECT * FROM brewer WHERE brewerEmail='$username'";
	}
else $query_brewer = $query_name;
$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
$row_brewer = mysql_fetch_assoc($brewer);
$totalRows_brewer = mysql_num_rows($brewer);
?>