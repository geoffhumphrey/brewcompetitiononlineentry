<?php
$query_assignments = sprintf("SELECT * FROM $judging_assignments_db_table WHERE assignment='%s'", $filter);
if ($id != "default") $query_assignments .= " AND assignTable='$id'";
$assignments = mysql_query($query_assignments, $brewing) or die(mysql_error());
$row_assignments = mysql_fetch_assoc($assignments);
$totalRows_assignments = mysql_num_rows($assignments);

if ($view == "sign-in") {
	
	$query_brewer = "SELECT a.id,a.brewerFirstName,a.brewerLastName,a.brewerJudgeID,b.uid,b.staff_judge,b.staff_steward,b.staff_staff,b.staff_organizer FROM $brewer_db_table a, $staff_db_table b WHERE a.uid = b.uid";
	if ($filter == "S") $query_brewer .= " AND b.staff_steward='1'";
	else $query_brewer .= " AND b.staff_judge='1'"; 
	$query_brewer .= " ORDER BY a.brewerLastName ASC";
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);

}

?>