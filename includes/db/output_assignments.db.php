<?php
$query_assignments = sprintf("SELECT * FROM %s WHERE assignment='%s'", $judging_assignments_db_table, $filter);
if ($id != "default") $query_assignments .= sprintf(" AND assignTable='%s'",$id);
if (SINGLE) $query_assignments .= sprintf(" AND comp_id='%s'",$_SESSION['comp_id']);
$assignments = mysqli_query($connection,$query_assignments) or die (mysqli_error($connection));
$row_assignments = mysqli_fetch_assoc($assignments);
$totalRows_assignments = mysqli_num_rows($assignments);

if ($view == "sign-in") {
	$query_brewer = sprintf("SELECT a.id,a.brewerFirstName,a.brewerLastName,a.brewerJudgeID,a.brewerJudgeWaiver,b.uid,b.staff_judge,b.staff_steward,b.staff_staff,b.staff_organizer FROM %s a, $staff_db_table b WHERE a.uid = b.uid",$brewer_db_table);
	if (SINGLE) $query_brewer .= sprintf(" AND comp_id='%s'",$_SESSION['comp_id']);
	if ($filter == "S") $query_brewer .= " AND b.staff_steward='1'";
	else $query_brewer .= " AND b.staff_judge='1'"; 
	$query_brewer .= " ORDER BY a.brewerLastName ASC";
	$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
	$row_brewer = mysqli_fetch_assoc($brewer);
	$totalRows_brewer = mysqli_num_rows($brewer);

	$query_sessions .= sprintf("SELECT * from %s ORDER BY judgingLocName ASC",$prefix."judging_locations");
	$sessions = mysqli_query($connection,$query_sessions) or die (mysqli_error($connection));
	$row_sessions = mysqli_fetch_assoc($sessions);
	$totalRows_sessions = mysqli_num_rows($sessions);

	$judging_sessions = array();

	if ($totalRows_sessions > 0) {
		do {
			$judging_sessions[] = $row_sessions['judgingLocName'];
		} while($row_sessions = mysqli_fetch_assoc($sessions));
	}
}
?>