<?php
// Get maximum point values based upon number of entries
$organ_max_points = number_format(total_points($total_entries_received,"Organizer"), 1);
$staff_max_points = number_format(total_points($total_entries_received,"Staff"), 1);
$judge_max_points = number_format(total_points($total_entries_received,"Judge"), 1);

// Divide total staff point pool by amount of staff, round down
$query_assignments = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE staff_staff='1'", $prefix."staff");
$assignments = mysqli_query($connection,$query_assignments) or die (mysqli_error($connection));
$row_assignments = mysqli_fetch_assoc($assignments);

$staff_points = 0;
if ($row_assignments['count'] == 1) $staff_points = number_format($staff_max_points,1);
if ($row_assignments['count'] >= 2) $staff_points = number_format(round(($staff_max_points/$row_assignments['count']) / 0.5) * 0.5, 1);

// Organizer
$query_organizer = sprintf("SELECT * FROM %s WHERE staff_organizer='1'",$prefix."staff");
$organizer = mysqli_query($connection,$query_organizer) or die (mysqli_error($connection));
$row_organizer = mysqli_fetch_assoc($organizer);
$totalRows_organizer = mysqli_num_rows($organizer);

if ($totalRows_organizer > 0) {
	$query_org = sprintf("SELECT uid,brewerLastName,brewerFirstName,brewerJudgeID FROM %s WHERE uid='%s'",$prefix."brewer",$row_organizer['uid']);
	$org = mysqli_query($connection,$query_org) or die (mysqli_error($connection));
	$row_org = mysqli_fetch_assoc($org);
	$totalRows_org = mysqli_num_rows($org);
}

// Judges
$query_judges = sprintf("SELECT a.uid, b.uid, b.brewerLastName, b.brewerFirstName, b.brewerJudgeID FROM %s a, %s b WHERE a.staff_judge='1' AND a.uid = b.uid ORDER BY b.brewerLastName ASC",$prefix."staff",$prefix."brewer");
$judges = mysqli_query($connection,$query_judges) or die (mysqli_error($connection));;
$row_judges = mysqli_fetch_assoc($judges);
$totalRows_judges = mysqli_num_rows($judges);

// Best of Show Judges (those that are assigned in another role)
$query_bos_judges = sprintf("SELECT a.uid, b.uid, b.brewerLastName, b.brewerFirstName, b.brewerJudgeID FROM %s a, %s b WHERE a.staff_judge_bos='1' AND a.uid = b.uid AND (a.staff_judge='1' OR a.staff_steward='1' OR a.staff_staff='1') ORDER BY b.brewerLastName ASC",$prefix."staff",$prefix."brewer");
$bos_judges = mysqli_query($connection,$query_bos_judges) or die (mysqli_error($connection));
$row_bos_judges = mysqli_fetch_assoc($bos_judges);
$totalRows_bos_judges = mysqli_num_rows($bos_judges);

// Best of Show Judges (those that aren't assigned in another role)
$query_bos_judges_no_assignment = sprintf("SELECT a.uid, b.uid, b.brewerLastName, b.brewerFirstName, b.brewerJudgeID FROM %s a, %s b WHERE a.staff_judge_bos='1' AND a.uid = b.uid AND a.staff_judge='0' AND a.staff_steward='0' AND a.staff_staff='0' ORDER BY b.brewerLastName ASC",$prefix."staff",$prefix."brewer");
$bos_judges_no_assignment = mysqli_query($connection,$query_bos_judges_no_assignment) or die (mysqli_error($connection));
$row_bos_judges_no_assignment = mysqli_fetch_assoc($bos_judges_no_assignment);
$totalRows_bos_judges_no_assignment = mysqli_num_rows($bos_judges_no_assignment);

$bos_judge_no_assignment = array();

if ($totalRows_bos_judges > 0) {

	do {

		$query_bos_judge_assign = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE bid='%s'",$judging_assignments_db_table, $row_bos_judges['uid']);
		$bos_judge_assign = mysqli_query($connection,$query_bos_judge_assign) or die (mysqli_error($connection));
		$row_bos_judge_assign = mysqli_fetch_assoc($bos_judge_assign);

		if ($row_bos_judge_assign['count'] == 0) $bos_judge_no_assignment[] = $row_bos_judges['uid'];

	} while ($row_bos_judges = mysqli_fetch_assoc($bos_judges));

}

if ($totalRows_bos_judges_no_assignment > 0) {

	do {

		$bos_judge_no_assignment[] = $row_bos_judges_no_assignment['uid'];

	} while ($row_bos_judges_no_assignment = mysqli_fetch_assoc($bos_judges_no_assignment));

}


// Stewards
$query_stewards = sprintf("SELECT a.uid, b.uid, b.brewerLastName, b.brewerFirstName, b.brewerJudgeID FROM %s a, %s b WHERE a.staff_steward='1' AND a.uid = b.uid ORDER BY b.brewerLastName ASC",$prefix."staff",$prefix."brewer");
$stewards = mysqli_query($connection,$query_stewards) or die (mysqli_error($connection));
$row_stewards = mysqli_fetch_assoc($stewards);
$totalRows_stewards = mysqli_num_rows($stewards);

// Staff
$query_staff = sprintf("SELECT a.uid, b.uid, b.brewerLastName, b.brewerFirstName, b.brewerJudgeID FROM %s a, %s b WHERE a.staff_staff='1' AND a.uid = b.uid ORDER BY b.brewerLastName ASC",$prefix."staff",$prefix."brewer");
$staff = mysqli_query($connection,$query_staff) or die (mysqli_error($connection));
$row_staff = mysqli_fetch_assoc($staff);
$totalRows_staff = mysqli_num_rows($staff);
?>