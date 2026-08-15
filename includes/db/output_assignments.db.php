<?php

if ($filter == "staff") {

	$tbody_staff = "";
	$row_assignments = array();

	// Get IDs of non-judging sessions
	$db_conn->where('judgingLocType', '2');
	$rows_non_judge = $db_conn->get($prefix."judging_locations", null, "id");
	$totalRows_non_judge = $db_conn->count;

	if ($totalRows_non_judge > 0) {

		$non_judge_locs = array();
		$row_assignments = array();

		foreach ($rows_non_judge as $row_non_judge) { $non_judge_locs[] = $row_non_judge['id']; }

		$db_conn->where('brewerStaff', 'Y');
		$rows_staff_avail = $db_conn->get($prefix."brewer");
		$totalRows_staff_avail = $db_conn->count;

		// Build output array
		if ($totalRows_staff_avail > 0) {

			foreach ($rows_staff_avail as $row_staff_avail) {

				$explodies = explode(",",$row_staff_avail['brewerJudgeLocation']);

				foreach ($non_judge_locs as $value) {
					$affirm = "Y-".$value;
					$location_info = table_location($value,$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"known-id");
					if (in_array($affirm,$explodies)) {
						$tbody_staff .= "<tr>";
						$tbody_staff .= "<td>".$row_staff_avail['brewerLastName'].", ".$row_staff_avail['brewerFirstName']."<br><small>".$row_staff_avail['brewerEmail']."</small></td>";
						$tbody_staff .= "<td>".$location_info."</td>";
						$tbody_staff .= "</tr>";
					}
				}

			}

		}

	}

}

else {
	$query_assignments = "SELECT * FROM ".$prefix."judging_assignments"." WHERE assignment=?";
	$params_assignments = array($filter);
	if ($id != "default") { $query_assignments .= " AND assignTable=?"; $params_assignments[] = $id; }
	if ($location != "default") { $query_assignments .= " AND assignLocation=?"; $params_assignments[] = $location; }
	if (SINGLE) { $query_assignments .= " AND comp_id=?"; $params_assignments[] = $_SESSION['comp_id']; }
	if ($view == "judge_inventory") $query_assignments .= " ORDER BY bid,assignTable ASC";
	$rows_assignments = $db_conn->rawQuery($query_assignments, $params_assignments);
	$row_assignments = ($rows_assignments && count($rows_assignments) > 0) ? $rows_assignments[0] : null;
	$totalRows_assignments = $db_conn->count;

	if ($view == "sign-in") {
		$db_conn->orderBy('judgingLocName', 'ASC');
		$rows_sessions = $db_conn->get($prefix."judging_locations");
		$totalRows_sessions = $db_conn->count;

		$judging_sessions = array();
		if ($totalRows_sessions > 0) {
			foreach ($rows_sessions as $row_sessions) {
				$judging_sessions[] = array(
					"loc-name" => $row_sessions['judgingLocName'],
					"loc-id" => $row_sessions['id']
				);
			}
		}
	}
}

?>