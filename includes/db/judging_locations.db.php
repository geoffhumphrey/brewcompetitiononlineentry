<?php
if (NHC) {
	// Place NHC SQL calls below
	
	
}
// end if (NHC)

else {
		
	$query_judging = "SELECT * FROM $judging_locations_db_table";
	if (($go == "styles") && ($bid != "default")) $query_judging .= " WHERE id='$bid'";
	elseif (($go == "judging") && ($action == "update") && ($bid != "default")) $query_judging .= " WHERE id='$bid'";
	elseif (($go == "judging") && (($action == "add") || ($action == "edit")))  $query_judging .= " WHERE id='$id'";
	else $query_judging .= " ORDER BY judgingDate,judgingLocName ASC";
	$judging = mysql_query($query_judging, $brewing) or die(mysql_error());
	$row_judging = mysql_fetch_assoc($judging);
	$totalRows_judging = mysql_num_rows($judging); 
	
	// Separate connections for selected queries that are housed on the same page.
	// ********************* Should be replaced with function *********************
	$query_judging1 = "SELECT * FROM $judging_locations_db_table ORDER BY judgingDate,judgingLocName ASC";
	$judging1 = mysql_query($query_judging1, $brewing) or die(mysql_error());
	$row_judging1 = mysql_fetch_assoc($judging1);
	$totalRows_judging1 = mysql_num_rows($judging1);
	
	$query_judging2 = "SELECT * FROM $judging_locations_db_table";
	if (($section == "brewer") || ($section == "admin") || ($section == "register")) $query_judging2 .= " ORDER BY judgingDate,judgingLocName ASC";
	$judging2 = mysql_query($query_judging2, $brewing) or die(mysql_error());
	$row_judging2 = mysql_fetch_assoc($judging2);
	$totalRows_judging2 = mysql_num_rows($judging2);
	
	$query_judging3 = "SELECT * FROM $judging_locations_db_table";
	if ((($section == "brewer") && ($action == "edit")) || ($section == "admin") || ($section == "register")) $query_judging3 .= " ORDER BY judgingDate,judgingLocName ASC";
	$judging3 = mysql_query($query_judging3, $brewing) or die(mysql_error());
	$row_judging3 = mysql_fetch_assoc($judging3);
	$totalRows_judging3 = mysql_num_rows($judging3);

	// Make DB Connections
	//if ($section != "step5") include(DB.'judging_locations.db.php');
	
	if ((($action == "default") || ($action == "assign")) && ($section != "step5")) {
		
		// Get Judging Locations Info
		$query_judging_locs = "SELECT * FROM $judging_locations_db_table ORDER by judgingDate ASC";
		$judging_locs = mysql_query($query_judging_locs, $brewing) or die(mysql_error());
		$row_judging_locs = mysql_fetch_assoc($judging_locs);
		$totalRows_judging_locs = mysql_num_rows($judging_locs);
	
	}
	
	if ($filter == "staff") {
		
		$query_organizer = sprintf("SELECT uid FROM %s WHERE staff_organizer='1'",$prefix."staff");
		$organizer = mysql_query($query_organizer, $brewing) or die(mysql_error());
		$row_organizer = mysql_fetch_assoc($organizer);
		
		$query_brewers = "SELECT * FROM $brewer_db_table ORDER BY brewerLastName";
		$brewers = mysql_query($query_brewers, $brewing) or die(mysql_error());
		$row_brewers = mysql_fetch_assoc($brewers);
	
	}

}
?>