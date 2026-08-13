<?php
declare(strict_types=1);
// Get maximum point values based upon number of entries
$organ_max_points = number_format(total_points($total_entries_received,"Organizer"), 1);
$staff_max_points = number_format(total_points($total_entries_received,"Staff"), 1);
$judge_max_points = number_format(total_points($total_entries_received,"Judge"), 1);

// Divide total staff point pool by amount of staff, round down
$db_conn->where('staff_staff', '1');
$row_assignments = $db_conn->getOne($prefix."staff", "COUNT(*) as 'count'");

$staff_points = 0;
if ($row_assignments['count'] == 1) $staff_points = number_format($staff_max_points,1);
if ($row_assignments['count'] >= 2) $staff_points = number_format(round(($staff_max_points/$row_assignments['count']) / 0.5) * 0.5, 1);

// Organizer
$db_conn->where('staff_organizer', '1');
$rows_organizer = $db_conn->get($prefix."staff");
$row_organizer = ($rows_organizer && count($rows_organizer) > 0) ? $rows_organizer[0] : null;
$totalRows_organizer = $db_conn->count;

if ($totalRows_organizer > 0) {
	$db_conn->where('uid', $row_organizer['uid']);
	$rows_org = $db_conn->get($prefix."brewer", null, "uid,brewerLastName,brewerFirstName,brewerJudgeID");
	$row_org = ($rows_org && count($rows_org) > 0) ? $rows_org[0] : null;
	$totalRows_org = $db_conn->count;
}

// Judges
$query_judges = "SELECT a.uid, b.uid, b.brewerLastName, b.brewerFirstName, b.brewerJudgeID FROM ".$prefix."staff"." a, ".$prefix."brewer"." b WHERE a.staff_judge='1' AND a.uid = b.uid ORDER BY b.brewerLastName ASC";
$rows_judges = $db_conn->rawQuery($query_judges);
$row_judges = ($rows_judges && count($rows_judges) > 0) ? $rows_judges[0] : null;
$totalRows_judges = $db_conn->count;

// Best of Show Judges (those that are assigned in another role)
$query_bos_judges = "SELECT a.uid, b.uid, b.brewerLastName, b.brewerFirstName, b.brewerJudgeID FROM ".$prefix."staff"." a, ".$prefix."brewer"." b WHERE a.staff_judge_bos='1' AND a.uid = b.uid AND (a.staff_judge='1' OR a.staff_steward='1' OR a.staff_staff='1') ORDER BY b.brewerLastName ASC";
$rows_bos_judges = $db_conn->rawQuery($query_bos_judges);
$row_bos_judges = ($rows_bos_judges && count($rows_bos_judges) > 0) ? $rows_bos_judges[0] : null;
$totalRows_bos_judges = $db_conn->count;

// Best of Show Judges (those that aren't assigned in another role)
$query_bos_judges_no_assignment = "SELECT a.uid, b.uid, b.brewerLastName, b.brewerFirstName, b.brewerJudgeID FROM ".$prefix."staff"." a, ".$prefix."brewer"." b WHERE a.staff_judge_bos='1' AND a.uid = b.uid AND a.staff_judge='0' AND a.staff_steward='0' AND a.staff_staff='0' ORDER BY b.brewerLastName ASC";
$rows_bos_judges_no_assignment = $db_conn->rawQuery($query_bos_judges_no_assignment);
$row_bos_judges_no_assignment = ($rows_bos_judges_no_assignment && count($rows_bos_judges_no_assignment) > 0) ? $rows_bos_judges_no_assignment[0] : null;
$totalRows_bos_judges_no_assignment = $db_conn->count;

$bos_judge_no_assignment = array();

if ($totalRows_bos_judges > 0) {

	foreach ($rows_bos_judges as $row_bos_judges) {

		$db_conn->where('bid', $row_bos_judges['uid']);
		$row_bos_judge_assign = $db_conn->getOne($judging_assignments_db_table, "COUNT(*) AS 'count'");

		if ($row_bos_judge_assign['count'] == 0) $bos_judge_no_assignment[] = $row_bos_judges['uid'];

	}

}

if ($totalRows_bos_judges_no_assignment > 0) {

	foreach ($rows_bos_judges_no_assignment as $row_bos_judges_no_assignment) {

		$bos_judge_no_assignment[] = $row_bos_judges_no_assignment['uid'];

	}

}


// Stewards
$query_stewards = "SELECT a.uid, b.uid, b.brewerLastName, b.brewerFirstName, b.brewerJudgeID FROM ".$prefix."staff"." a, ".$prefix."brewer"." b WHERE a.staff_steward='1' AND a.uid = b.uid ORDER BY b.brewerLastName ASC";
$rows_stewards = $db_conn->rawQuery($query_stewards);
$row_stewards = ($rows_stewards && count($rows_stewards) > 0) ? $rows_stewards[0] : null;
$totalRows_stewards = $db_conn->count;

// Staff
$query_staff = "SELECT a.uid, b.uid, b.brewerLastName, b.brewerFirstName, b.brewerJudgeID FROM ".$prefix."staff"." a, ".$prefix."brewer"." b WHERE a.staff_staff='1' AND a.uid = b.uid ORDER BY b.brewerLastName ASC";
$rows_staff = $db_conn->rawQuery($query_staff);
$row_staff = ($rows_staff && count($rows_staff) > 0) ? $rows_staff[0] : null;
$totalRows_staff = $db_conn->count;
?>