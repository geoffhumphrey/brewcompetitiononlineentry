<?php
if (NHC) {
	// Place NHC SQL calls below
	
	
}
// end if (NHC)

else {

	if ($id == "default") $id_table = $row_tables['id'];
	else $id_table = $id;
	$query_assignments = sprintf("SELECT * FROM $judging_assignments_db_table WHERE assignTable='%s'",$id_table);
	if ($round2 != "default") $query_assignments .= sprintf(" AND assignRound='%s'", $round2);                        
	$query_assignments .= " ORDER BY assignRound,assignFlight ASC";
	$assignments = mysql_query($query_assignments, $brewing) or die(mysql_error());
	$row_assignments = mysql_fetch_assoc($assignments);
	$totalRows_assignments = mysql_num_rows($assignments);
	
}


?>