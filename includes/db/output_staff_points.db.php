<?php


// Get maximum point values based upon number of entries
$organ_points = number_format(total_points($total_entries,"Organizer"), 1);
$staff_points_total = number_format(total_points($total_entries,"Staff"), 1);
$judge_points = number_format(total_points($total_entries,"Judge"), 1);

// Divide total staff point pool by amount of staff, round down
$query_assignments = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE staff_staff='1'",$staff_db_table);
$assignments = mysql_query($query_assignments, $brewing) or die(mysql_error());
$row_assignments = mysql_fetch_assoc($assignments);
if ($row_assignments['count'] >= 2) 
$staff_points = number_format(round(($staff_points_total/$row_assignments['count']) / 0.5) * 0.5, 1);
elseif ($row_assignments['count'] == 1) $staff_points = number_format($staff_points_total,1);
else $staff_points = 0;

if (($staff_points*$row_assignments['count']) > $staff_points_total) $staff_points = number_format(floor($staff_points),1);
else $staff_points = $staff_points;

// Organizer
$query_organizer = sprintf("SELECT uid FROM %s WHERE staff_organizer='1'",$staff_db_table);
$organizer = mysql_query($query_organizer, $brewing) or die(mysql_error());
$row_organizer = mysql_fetch_assoc($organizer);
$totalRows_organizer = mysql_num_rows($organizer);

$query_org = sprintf("SELECT brewerLastName,brewerFirstName,brewerJudgeID FROM %s WHERE uid='%s'",$brewer_db_table,$row_organizer['uid']);
$org = mysql_query($query_org, $brewing) or die(mysql_error());
$row_org = mysql_fetch_assoc($org);
$totalRows_org = mysql_num_rows($org);

// Judges
$query_judges = sprintf("SELECT uid FROM %s WHERE staff_judge='1'",$staff_db_table);
$judges = mysql_query($query_judges, $brewing) or die(mysql_error());
$row_judges = mysql_fetch_assoc($judges);
$totalRows_judges = mysql_num_rows($judges);

// Best of Show Judges (those that weren't assigned to a table)
$query_bos_judges = sprintf("SELECT uid FROM %s WHERE staff_judge_bos='1'",$staff_db_table);
$bos_judges = mysql_query($query_bos_judges, $brewing) or die(mysql_error());
$row_bos_judges = mysql_fetch_assoc($bos_judges);
$totalRows_bos_judges = mysql_num_rows($bos_judges);

$bos_judge_no_assignment[] = "";

do {
	
	$query_bos_judge_assign = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE bid='%s'",$judging_assignments_db_table, $row_bos_judges['uid']);
	$bos_judge_assign = mysql_query($query_bos_judge_assign, $brewing) or die(mysql_error());
	$row_bos_judge_assign = mysql_fetch_assoc($bos_judge_assign);
	
	if ($row_bos_judge_assign['count'] == 0) $bos_judge_no_assignment[] = $row_bos_judges['uid'];

} while ($row_bos_judges = mysql_fetch_assoc($bos_judges));

// Stewards
$query_stewards = sprintf("SELECT uid FROM %s WHERE staff_steward='1'",$staff_db_table);
$stewards = mysql_query($query_stewards, $brewing) or die(mysql_error());
$row_stewards = mysql_fetch_assoc($stewards);
$totalRows_stewards = mysql_num_rows($stewards);

// Staff
$query_staff = sprintf("SELECT uid FROM %s WHERE staff_staff='1'",$staff_db_table);
$staff = mysql_query($query_staff, $brewing) or die(mysql_error());
$row_staff = mysql_fetch_assoc($staff);
$totalRows_staff = mysql_num_rows($staff);
?>