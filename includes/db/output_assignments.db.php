<?php
$query_assignments = sprintf("SELECT * FROM $judging_assignments_db_table WHERE assignment='%s'", $filter);
if ($id != "default") $query_assignments .= " AND assignTable='$id'";
$assignments = mysqli_query($connection,$query_assignments) or die (mysqli_error($connection));
$row_assignments = mysqli_fetch_assoc($assignments);
$totalRows_assignments = mysqli_num_rows($assignments);

if ($view == "sign-in") {
	$query_brewer = "SELECT a.id,a.brewerFirstName,a.brewerLastName,a.brewerJudgeID,a.brewerJudgeWaiver,b.uid,b.staff_judge,b.staff_steward,b.staff_staff,b.staff_organizer FROM $brewer_db_table a, $staff_db_table b WHERE a.uid = b.uid";
	if ($filter == "S") $query_brewer .= " AND b.staff_steward='1'";
	else $query_brewer .= " AND b.staff_judge='1'"; 
	$query_brewer .= " ORDER BY a.brewerLastName ASC";
	$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
	$row_brewer = mysqli_fetch_assoc($brewer);
	$totalRows_brewer = mysqli_num_rows($brewer);
}
?>