<?php
if (NHC) {
	// Place NHC SQL calls below
	
	
}
// end if (NHC)

else {
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
	
	
}
?>