<?php
// Get maximum point values based upon number of entries
$organ_points = number_format(total_points($total_entries,"Organizer"), 1);
$staff_points_total = number_format(total_points($total_entries,"Staff"), 1);
$judge_points = number_format(total_points($total_entries,"Judge"), 1);

// Divide total staff point pool by amount of staff, round down
$query_assignments = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE staff_staff='1'",$staff_db_table);
$assignments = mysqli_query($connection,$query_assignments) or die (mysqli_error($connection));
$row_assignments = mysqli_fetch_assoc($assignments);
if ($row_assignments['count'] >= 2) 
$staff_points = number_format(round(($staff_points_total/$row_assignments['count']) / 0.5) * 0.5, 1);
elseif ($row_assignments['count'] == 1) $staff_points = number_format($staff_points_total,1);
else $staff_points = 0;

if (($staff_points*$row_assignments['count']) > $staff_points_total) $staff_points = number_format(floor($staff_points),1);
else $staff_points = $staff_points;

// Organizer
$query_organizer = sprintf("SELECT uid FROM %s WHERE staff_organizer='1'",$staff_db_table);
$organizer = mysqli_query($connection,$query_organizer) or die (mysqli_error($connection));
$row_organizer = mysqli_fetch_assoc($organizer);
$totalRows_organizer = mysqli_num_rows($organizer);

$query_org = sprintf("SELECT brewerLastName,brewerFirstName,brewerJudgeID FROM %s WHERE uid='%s'",$brewer_db_table,$row_organizer['uid']);
$org = mysqli_query($connection,$query_org) or die (mysqli_error($connection));
$row_org = mysqli_fetch_assoc($org);
$totalRows_org = mysqli_num_rows($org);

// Judges
$query_judges = sprintf("SELECT uid FROM %s WHERE staff_judge='1'",$staff_db_table);
$judges = mysqli_query($connection,$query_judges) or die (mysqli_error($connection));;
$row_judges = mysqli_fetch_assoc($judges);
$totalRows_judges = mysqli_num_rows($judges);

// Best of Show Judges (those that weren't assigned to a table)
$query_bos_judges = sprintf("SELECT uid FROM %s WHERE staff_judge_bos='1'",$staff_db_table);
$bos_judges = mysqli_query($connection,$query_bos_judges) or die (mysqli_error($connection));
$row_bos_judges = mysqli_fetch_assoc($bos_judges);
$totalRows_bos_judges = mysqli_num_rows($bos_judges);

$bos_judge_no_assignment[] = "";

do {
	
	$query_bos_judge_assign = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE bid='%s'",$judging_assignments_db_table, $row_bos_judges['uid']);
	$bos_judge_assign = mysqli_query($connection,$query_bos_judge_assign) or die (mysqli_error($connection));
	$row_bos_judge_assign = mysqli_fetch_assoc($bos_judge_assign);
	
	if ($row_bos_judge_assign['count'] == 0) $bos_judge_no_assignment[] = $row_bos_judges['uid'];

} while ($row_bos_judges = mysqli_fetch_assoc($bos_judges));

// Stewards
$query_stewards = sprintf("SELECT uid FROM %s WHERE staff_steward='1'",$staff_db_table);
$stewards = mysqli_query($connection,$query_stewards) or die (mysqli_error($connection));
$row_stewards = mysqli_fetch_assoc($stewards);
$totalRows_stewards = mysqli_num_rows($stewards);

// Staff
$query_staff = sprintf("SELECT uid FROM %s WHERE staff_staff='1'",$staff_db_table);
$staff = mysqli_query($connection,$query_staff) or die (mysqli_error($connection));
$row_staff = mysqli_fetch_assoc($staff);
$totalRows_staff = mysqli_num_rows($staff);
?>