<?php

if (SINGLE) {
	
	$query_judging = sprintf("SELECT * FROM %s WHERE comp_id='%s'",$judging_locations_db_table,$_SESSION['comp_id']);
	if (($go == "styles") && ($bid != "default")) $query_judging .= sprintf(" AND id='%s'",$bid);
	elseif (($go == "judging") && ($action == "update") && ($bid != "default")) $query_judging .= sprintf(" AND id='%s'",$bid);
	elseif (($go == "judging") && (($action == "add") || ($action == "edit")))  $query_judging .= sprintf(" AND id='%s'",$id);
	else $query_judging .= " ORDER BY judgingDate,judgingLocName ASC";
	
}

else {
	
	$query_judging = sprintf("SELECT * FROM %s",$judging_locations_db_table);
	if (($go == "styles") && ($bid != "default")) $query_judging .= sprintf(" WHERE id='%s'",$bid);
	elseif (($go == "judging") && ($action == "update") && ($bid != "default")) $query_judging .= sprintf(" WHERE id='%s'",$bid);
	elseif (($go == "judging") && (($action == "add") || ($action == "edit")))  $query_judging .= sprintf(" WHERE id='%s'",$id);
	else $query_judging .= " ORDER BY judgingDate,judgingLocName ASC";
	
}

$judging = mysqli_query($connection,$query_judging) or die (mysqli_error($connection));
$row_judging = mysqli_fetch_assoc($judging);
$totalRows_judging = mysqli_num_rows($judging); 

// Separate connections for selected queries that are housed on the same page.
// ********************* Should be replaced with function *********************
$query_judging1 = sprintf("SELECT * FROM %s",$judging_locations_db_table);
if (SINGLE) $query_judging1 .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
$query_judging1 .= " ORDER BY judgingDate,judgingLocName ASC";
$judging1 = mysqli_query($connection,$query_judging1) or die (mysqli_error($connection));
$row_judging1 = mysqli_fetch_assoc($judging1);
$totalRows_judging1 = mysqli_num_rows($judging1);

$query_judging2 = sprintf("SELECT * FROM %s",$judging_locations_db_table);
if (SINGLE) $query_judging2 .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
if (($section == "brewer") || ($section == "admin") || ($section == "register")) $query_judging2 .= " ORDER BY judgingDate,judgingLocName ASC";
$judging2 = mysqli_query($connection,$query_judging2) or die (mysqli_error($connection));
$row_judging2 = mysqli_fetch_assoc($judging2);
$totalRows_judging2 = mysqli_num_rows($judging2);

$query_judging3 = sprintf("SELECT * FROM %s",$judging_locations_db_table);
if (SINGLE) $query_judging3 .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
if ((($section == "brewer") && ($action == "edit")) || ($section == "admin") || ($section == "register")) $query_judging3 .= " ORDER BY judgingDate,judgingLocName ASC";
$judging3 = mysqli_query($connection,$query_judging3) or die (mysqli_error($connection));
$row_judging3 = mysqli_fetch_assoc($judging3);
$totalRows_judging3 = mysqli_num_rows($judging3);

// Make DB Connections
//if ($section != "step5") include (DB.'judging_locations.db.php');

if ((($action == "default") || ($action == "assign")) && ($section != "step5")) {
	
	// Get Judging Locations Info
	$query_judging_locs = sprintf("SELECT * FROM %s",$judging_locations_db_table);
	if (SINGLE) $query_judging_locs .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
	$query_judging_locs .= " ORDER by judgingDate ASC";
	$judging_locs = mysqli_query($connection,$query_judging_locs) or die (mysqli_error($connection));
	$row_judging_locs = mysqli_fetch_assoc($judging_locs);
	$totalRows_judging_locs = mysqli_num_rows($judging_locs);

}

if ($filter == "staff") {
	
	$query_organizer = sprintf("SELECT uid FROM %s WHERE staff_organizer='1'",$prefix."staff");
	if (SINGLE) $query_organizer .= sprintf(" AND comp_id='%s'",$_SESSION['comp_id']);
	$organizer = mysqli_query($connection,$query_organizer) or die (mysqli_error($connection));
	$row_organizer = mysqli_fetch_assoc($organizer);
	
	// @single
	$query_brewers = "SELECT * FROM $brewer_db_table ORDER BY brewerLastName";
	$brewers = mysqli_query($connection,$query_brewers) or die (mysqli_error($connection));
	$row_brewers = mysqli_fetch_assoc($brewers);

}

?>