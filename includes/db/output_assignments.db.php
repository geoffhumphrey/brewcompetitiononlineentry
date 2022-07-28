<?php

if ($filter == "staff") {

	$tbody_staff = "";
	$row_assignments = array();

	// Get IDs of non-judging sessions
	$query_non_judge = sprintf("SELECT id FROM %s WHERE judgingLocType='2'", $prefix."judging_locations");
	$non_judge = mysqli_query($connection,$query_non_judge) or die (mysqli_error($connection));
	$row_non_judge = mysqli_fetch_assoc($non_judge);
	$totalRows_non_judge = mysqli_num_rows($non_judge);

	if ($totalRows_non_judge > 0) {

		$non_judge_locs = array();
		$row_assignments = array();

		do { $non_judge_locs[] = $row_non_judge['id']; } while ($row_non_judge = mysqli_fetch_assoc($non_judge));

		$query_staff_avail = sprintf("SELECT * FROM %s WHERE brewerStaff='Y'", $prefix."brewer");
		$staff_avail = mysqli_query($connection,$query_staff_avail) or die (mysqli_error($connection));
		$row_staff_avail = mysqli_fetch_assoc($staff_avail);
		$totalRows_staff_avail = mysqli_num_rows($staff_avail);

		// Build output array
		if ($totalRows_staff_avail > 0) {

			do {

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

			} while ($row_staff_avail = mysqli_fetch_assoc($staff_avail));

		}

	}

}

else {
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
}

?>