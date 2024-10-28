<?php

if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] == 0))) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

	require(INCLUDES.'scrubber.inc.php');
	require(INCLUDES.'db_tables.inc.php');

	$dbTable = "default";

	$tables_array = array($special_best_info_db_table, $special_best_data_db_table, $brewing_db_table, $judging_assignments_db_table, $judging_flights_db_table, $judging_scores_db_table, $judging_scores_bos_db_table, $judging_tables_db_table, $staff_db_table);

	if (check_setup($prefix."evaluation",$database)) {
		$tables_array[] = $prefix."evaluation";
	}

	foreach ($tables_array as $table) {

		$sql = "TRUNCATE ".$table.";";
		$db_conn->rawQuery($sql);
		if ($db_conn->getLastErrno() !== 0) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

	}

	// Delete any uploaded scoresheets in the user_docs directory
	rdelete(USER_DOCS,"");

	// Clear BJCP ID from "contest_info"
	$update_table = $prefix."contest_info";
	$data = array(
		'contestID' => NULL
	);
	$db_conn->where ('id', 1);
	$result = $db_conn->update ($update_table, $data);
	if (!$result) {
		$error_output[] = $db_conn->getLastError();
		$errors = TRUE;
	}

	// Reset judge, steward, and staff interest and availability
	$update_table = $prefix."brewer";
	$data = array(
		'brewerJudge' => 'N',
		'brewerSteward' => 'N',
		'brewerJudgeLocation' => NULL,
		'brewerStewardLocation' => NULL,
		'brewerDropOff' => '999'
	);
	$result = $db_conn->update ($update_table, $data);
	if (!$result) {
		$error_output[] = $db_conn->getLastError();
		$errors = TRUE;
	}

	// If not retaining participant data, keep current admins
	if ($filter == "default") {

		$query_admin = sprintf("SELECT id FROM %s WHERE userLevel < '2'", $prefix."users");
		$admin = mysqli_query($connection,$query_admin) or die (mysqli_error($connection));
		$row_admin = mysqli_fetch_assoc($admin);
		$totalRows_admin = mysqli_num_rows($admin);

		$admin_ids = array();

		if ($totalRows_admin > 0) {

			do {
				$admin_ids[] = $row_admin['id'];
			} while($row_admin = mysqli_fetch_assoc($admin));

		}

		$query_non_admin = sprintf("SELECT id FROM %s WHERE userLevel='2'", $prefix."users");
		$non_admin = mysqli_query($connection,$query_non_admin) or die (mysqli_error($connection));
		$row_non_admin = mysqli_fetch_assoc($non_admin);
		$totalRows_non_admin = mysqli_num_rows($non_admin);

		if ($totalRows_non_admin > 0) {

			do {

				if (!in_array($row_non_admin['id'],$admin_ids)) {

					// Delete the user's record
					$update_table = $prefix."users";
					$db_conn->where ('id', $row_non_admin['id']);
					$result = $db_conn->delete($update_table);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

					// Delete the associated brewer's record
					$update_table = $prefix."brewer";
					$db_conn->where ('uid', $row_non_admin['id']);
					$result = $db_conn->delete($update_table);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

				} // end if (!in_array($row_non_admin['id'],$admin_ids))

			} while ($row_non_admin = mysqli_fetch_assoc($non_admin));

		} // end if ($totalRows_non_admin > 0)

	} // end if ($filter == "default")

	if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

	$redirect = $base_url."index.php?section=admin&go=archive&msg=7";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);

} else {

	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);

}
?>