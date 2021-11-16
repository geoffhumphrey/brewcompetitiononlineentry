<?php
$query_assignments = sprintf("SELECT * FROM %s WHERE assignment='%s'", $prefix."judging_assignments", $filter);
if ($id != "default") $query_assignments .= sprintf(" AND assignTable='%s'",$id);
if ($location != "default") $query_assignments .= sprintf(" AND assignLocation='%s'",$location);
if (SINGLE) $query_assignments .= sprintf(" AND comp_id='%s'",$_SESSION['comp_id']);
if ($view == "judge_inventory") $query_assignments .= " ORDER BY bid,assignTable ASC";
$assignments = mysqli_query($connection,$query_assignments) or die (mysqli_error($connection));
$row_assignments = mysqli_fetch_assoc($assignments);
$totalRows_assignments = mysqli_num_rows($assignments);

if ($view == "sign-in") {
	$query_sessions = sprintf("SELECT * from %s ORDER BY judgingLocName ASC",$prefix."judging_locations");
	$sessions = mysqli_query($connection,$query_sessions) or die (mysqli_error($connection));
	$row_sessions = mysqli_fetch_assoc($sessions);
	$totalRows_sessions = mysqli_num_rows($sessions);

	$judging_sessions = array();
	if ($totalRows_sessions > 0) {
		do {
			$judging_sessions[] = array(
				"loc-name" => $row_sessions['judgingLocName'],
				"loc-id" => $row_sessions['id']
			);
		} while($row_sessions = mysqli_fetch_assoc($sessions));
	}
}
?>