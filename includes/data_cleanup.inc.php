<?php
$count_results = 0;
$date_threshold = "";

if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] == 0)) {

	if (is_numeric($view)) {
		if ((isset($_POST['dateThreshold'])) && (!empty($_POST['dateThreshold']))) $date_threshold = sterilize($_POST['dateThreshold']); 
		if ((!isset($_POST['dateThreshold']) && ($view != "default"))) $date_threshold = sterilize($view);
	}
		
	$sql = "action=".$action."&go=".$go;
	if (!empty($date_threshold)) $sql .= "&view=".$view;

	if ($action == "purge") {

		// Purge unpaid entries
		if ($go == "unpaid") {
			
			$unpaid = purge_entries("unpaid", 0);

			$redirect = $base_url."index.php?section=admin&go=entries&purge=true";
			$redirect = prep_redirect_link($redirect);
			$redirect_go_to = sprintf("Location: %s", $redirect);

			if ($unpaid) $status = 1;

		}

		// Purge unconfirmed and/or entries that require special ingredients that do not have special ingredient data
		if ($go == "unconfirmed") {

			$uncon = purge_entries("unconfirmed", 0);
			$spec = purge_entries("special", 0);

			$redirect = $base_url."index.php?section=admin&go=entries&purge=true";
			$redirect = prep_redirect_link($redirect);
			$redirect_go_to = sprintf("Location: %s", $redirect);

			if (($uncon) && ($spec)) $status = 1;

		} // END if ($go == "unconfirmed")
		
		if (($go == "scoresheets") || ($go == "purge-all")) {

			require(LIB.'process.lib.php');
			$delete_scoresheets = rdelete(USER_DOCS,"");
			if ($delete_scoresheets) $status = 1;

		} // END if ($go == "scoresheets")

		// Purge entry and associated data
		if (($go == "entries") || ($go == "purge-all")) {

			$count_results = 0;
			$count_results_actual = 0;

			// Purge all data from brewing table
			if (!empty($date_threshold)) {

				$query_purge_entries = "SELECT id,brewJudgingNumber FROM ".$prefix."brewing WHERE brewUpdated < ? OR brewUpdated IS NULL";
				$rows_purge_entries = $db_conn->rawQuery($query_purge_entries, array($date_threshold));
				$totalRows_purge_entries = $db_conn->count;

				// echo $totalRows_purge_entries;

				if ($totalRows_purge_entries == 0) {
					$status = 2; // No entries found
				}

				else {

					$purge_array = array($judging_scores_db_table,$judging_scores_bos_db_table,$special_best_data_db_table);
					if (table_exists($prefix."evaluation")) $purge_array[] = $prefix."evaluation";

					foreach ($rows_purge_entries as $row_purge_entries) {

						$update_table = $prefix."brewing";
						$db_conn->where ('id', $row_purge_entries['id']);
						$result = $db_conn->delete ($update_table);
						$count_results_actual += 1;
						if ($result) $count_results += 1;

						foreach ($purge_array as $db_table) {

							$db_conn->where ('eid', $row_purge_entries['id']);
							$result = $db_conn->delete ($db_table);
							$count_results_actual += 1;
							if ($result) $count_results += 1;

						}

						// Delete any associated scoresheet
						$scoresheet_file_name_entry = sprintf("%06s",$row_purge_entries['id']).".pdf";
						$scoresheet_file_name_judging = sprintf("%06s",$row_purge_entries['brewJudgingNumber']).".pdf";
						$scoresheetfile_entry = USER_DOCS.strtolower($scoresheet_file_name_entry);
						$scoresheetfile_judging = USER_DOCS.strtolower($scoresheet_file_name_judging);

						if ((file_exists($scoresheetfile_entry)) && ($_SESSION['prefsDisplaySpecial'] == "E")) {
							unlink($scoresheetfile_entry);
						}

						elseif ((file_exists($scoresheetfile_judging)) && ($_SESSION['prefsDisplaySpecial'] == "J")) {
							unlink($scoresheetfile_judging);
						}

					}

				} // end else

				if ($count_results == $count_results_actual) $status = 1;
			
			} // end if (!empty($date_threshold))

			// Purge all entries
			else {

				$purge_array = array($brewing_db_table,$judging_scores_db_table,$judging_scores_bos_db_table,$special_best_data_db_table);
				if (table_exists($prefix."evaluation")) $purge_array[] = $prefix."evaluation";
				if (table_exists($prefix."payments")) $purge_array[] = $prefix."payments";

				foreach ($purge_array as $db_table) {

					if (SINGLE) {	
							
						$db_conn->where ('comp_id', $_SESSION['comp_id']);
						$result = $db_conn->delete ($db_table);
						$count_results_actual += 1;
						if ($result) $count_results += 1;
					
					}

					else {

						$sql_purge = sprintf("TRUNCATE %s",$db_table);
						$db_conn->rawQuery($sql_purge);
						$count_results_actual += 1;
						if ($db_conn->getLastErrno() === 0) $count_results += 1;
					
					}

				}

				// Clear judging preferences
				$update_table = $prefix."brewer";
				$data = array(
					'brewerJudge' => 'N',
					'brewerSteward' => 'N',
					'brewerJudgeLocation' => NULL,
					'brewerStewardLocation' => NULL
				);
				if (SINGLE)	$db_conn->where ('comp_id', $_SESSION['comp_id']);
				$result = $db_conn->update ($update_table, $data);
				$count_results_actual += 1;
				if ($result) $count_results += 1;

			}

			$redirect = $base_url."index.php?section=admin&msg=26";
			$redirect = prep_redirect_link($redirect);
			$redirect_go_to = sprintf("Location: %s", $redirect);
			
			if ($count_results == $count_results_actual) $status = 1;

			if (!empty($date_threshold)) $date_threshold = strtotime($date_threshold);

		} // END if (($go == "entries") || ($go == "purge-all"))

		// Purge participant and associated data
		if (($go == "participants") || ($go == "purge-all")) {

			$count_results = 0;
			$count_results_actual = 0;

			// Purge all data from brewer and users tables (except admins)
			$db_conn->where('userLevel', '2', '<');
			$rows_admin = $db_conn->get($prefix."users", null, "id");
			$totalRows_admin = $db_conn->count;

			$admin_ids = array();

			if ($totalRows_admin > 0) {

				foreach ($rows_admin as $row_admin) {
					$admin_ids[] = $row_admin['id'];
				}

			}

			$query_non_admin = "SELECT id FROM ".$prefix."users WHERE userLevel='2'";
			if (!empty($date_threshold)) {
				$query_non_admin .= " AND userCreated < ? OR userCreated IS NULL";
				$rows_non_admin = $db_conn->rawQuery($query_non_admin, array($date_threshold));
			}
			else $rows_non_admin = $db_conn->rawQuery($query_non_admin);
			$totalRows_non_admin = $db_conn->count;

			if ($totalRows_non_admin == 0) $status = 2; // No entries found

			if ($totalRows_non_admin > 0) {

				$dom_ct_participants += $totalRows_non_admin;

				foreach ($rows_non_admin as $row_non_admin) {

					if (!in_array($row_non_admin['id'],$admin_ids)) {

						$update_table = $prefix."users";
						$db_conn->where ('id', $row_non_admin['id']);
						$result = $db_conn->delete ($update_table);
						$count_results_actual += 1;
						if ($result) $count_results += 1;

						$update_table = $prefix."brewer";
						$db_conn->where ('uid', $row_non_admin['id']);
						$result = $db_conn->delete ($update_table);
						$count_results_actual += 1;
						if ($result) $count_results += 1;

						$update_table = $prefix."brewing";
						$db_conn->where ('brewBrewerID', $row_non_admin['id']);
						$result = $db_conn->delete ($update_table);
						$count_results_actual += 1;
						if ($result) $count_results += 1;

						$update_table = $prefix."staff";
						$db_conn->where ('uid', $row_non_admin['id']);
						$result = $db_conn->delete ($update_table);
						$count_results_actual += 1;
						if ($result) $count_results += 1;

						$update_table = $prefix."judging_assignments";
						$db_conn->where ('bid', $row_non_admin['id']);
						$result = $db_conn->delete ($update_table);
						$count_results_actual += 1;
						if ($result) $count_results += 1;

					}

				}

				// Search for any strays in the users table that is NOT in the brewer's table
				$rows_stray_brewers = $db_conn->get($prefix."brewer", null, "id,uid");
				$totalRows_stray_brewers = $db_conn->count;

				foreach ($rows_stray_brewers as $row_stray_brewers) {

					$db_conn->where('id', $row_stray_brewers['uid']);
					$row_stray_users = $db_conn->getOne($prefix."users", "id");
					$totalRows_stray_users = $db_conn->count;

					if ($totalRows_stray_users == 0) {

						$update_table = $prefix."users";
						$db_conn->where ('id', $row_stray_users['id']);
						$result = $db_conn->delete ($update_table);
						$count_results_actual += 1;
						if ($result) $count_results += 1;

						$update_table = $prefix."brewer";
						$db_conn->where ('uid', $row_stray_users['id']);
						$result = $db_conn->delete ($update_table);
						$count_results_actual += 1;
						if ($result) $count_results += 1;

					}

				}

			} // end if ($totalRows_non_admin > 0)

			if ($count_results == $count_results_actual) $status = 1;

			if (!empty($date_threshold)) $date_threshold = strtotime($date_threshold);

			$redirect = $base_url."index.php?section=admin&msg=26";
			$redirect = prep_redirect_link($redirect);
			$redirect_go_to = sprintf("Location: %s", $redirect);

		} // END if (($go == "participants") || ($go == "purge-all"))

		// Purge scores and associated data
		if (($go == "scores") || ($go == "purge-all")) {

			$count_results = 0;
			$count_results_actual = 0;

			$purge_array = array($judging_scores_db_table,$judging_scores_bos_db_table,$special_best_data_db_table);

			foreach ($purge_array as $db_table) {

				$sql_purge = sprintf("TRUNCATE %s",$db_table);
				if (SINGLE) {
					$sql_purge .= " WHERE comp_id=?";
					$db_conn->rawQuery($sql_purge, array($_SESSION['comp_id']));
				}
				else $db_conn->rawQuery($sql_purge);
				$count_results_actual += 1;
				if ($db_conn->getLastErrno() === 0) $count_results += 1;
			
			}

			if ($count_results == $count_results_actual) $status = 1;
			
			$redirect = $base_url."index.php?section=admin&msg=26";
			$redirect = prep_redirect_link($redirect);
			$redirect_go_to = sprintf("Location: %s", $redirect);

		} // END if (($go == "scores") || ($go == "purge-all"))

		// Purge judging tables and associated data
		if (($go == "tables") || ($go == "purge-all")) {

			$count_results = 0;
			$count_results_actual = 0;

			$purge_array = array($judging_tables_db_table,$judging_assignments_db_table,$judging_flights_db_table,$judging_scores_db_table,$special_best_data_db_table);

			foreach ($purge_array as $db_table) {

				$sql_purge = sprintf("TRUNCATE %s",$db_table);
				if (SINGLE) {
					$sql_purge .= " WHERE comp_id=?";
					$db_conn->rawQuery($sql_purge, array($_SESSION['comp_id']));
				}
				else $db_conn->rawQuery($sql_purge);
				$count_results_actual += 1;
				if ($db_conn->getLastErrno() === 0) $count_results += 1;

			}

			if ($count_results == $count_results_actual) $status = 1;

			$redirect = $base_url."index.php?section=admin&msg=26";
			$redirect = prep_redirect_link($redirect);
			$redirect_go_to = sprintf("Location: %s", $redirect);

		} // END if (($go == "tables") || ($go == "purge-all"))

		if ($go == "judge-assignments") {

			$update_table = $prefix."judging_assignments";

			if ($view != "default") {

				$view = explode("-",$view);
				$db_conn->where ('assignTable', $view[2]);
				$db_conn->where ('assignment', 'J');
				$result = $db_conn->delete ($update_table);
				if ($result) $status = 1;

			}

			else {

				$db_conn->where ('assignment', 'J');
				$result = $db_conn->delete ($update_table);
				if ($result) $status = 1;

			}

			$redirect = $base_url."index.php?section=admin&msg=26";
			$redirect = prep_redirect_link($redirect);
			$redirect_go_to = sprintf("Location: %s", $redirect);

		} // END if ($go == "judging-assignments")

		if ($go == "steward-assignments") {

			$update_table = $prefix."judging_assignments";

			if ($view != "default") {

				$view = explode("-",$view);
				$db_conn->where ('assignTable', $view[2]);
				$db_conn->where ('assignment', 'S');
				$result = $db_conn->delete ($update_table);
				if ($result) $status = 1;

			}
			
			else {

				$db_conn->where ('assignment', 'S');
				$result = $db_conn->delete ($update_table);
				if ($result) $status = 1;

			}

			$redirect = $base_url."index.php?section=admin&msg=26";
			$redirect = prep_redirect_link($redirect);
			$redirect_go_to = sprintf("Location: %s", $redirect);

		} // END if ($go == "judging-assignments")

		if (($go == "custom") || ($go == "purge-all")) {

			$count_results = 0;
			$count_results_actual = 0;

			$purge_array = array($special_best_info_db_table,$special_best_data_db_table);

			foreach ($purge_array as $db_table) {

				$sql_purge = sprintf("TRUNCATE %s",$db_table);
				if (SINGLE) {
					$sql_purge .= " WHERE comp_id=?";
					$db_conn->rawQuery($sql_purge, array($_SESSION['comp_id']));
				}
				else $db_conn->rawQuery($sql_purge);
				$count_results_actual += 1;
				if ($db_conn->getLastErrno() === 0) $count_results += 1;

			}

			if ($count_results == $count_results_actual) $status = 1;

			$redirect = $base_url."index.php?section=admin&msg=26";
			$redirect = prep_redirect_link($redirect);
			$redirect_go_to = sprintf("Location: %s", $redirect);

		} // END if (($go == "custom") || ($go == "purge-all"))

		if (($go == "availability") || ($go == "purge-all")) {

			$count_results = 0;
			$count_results_actual = 0;

			$update_table = $prefix."brewer";
			$data = array(
				'brewerJudge' => 'N',
				'brewerSteward' => 'N',
				'brewerStaff' => 'N',
				'brewerAssignment' => NULL
			);
			if (SINGLE) $db_conn->where ('comp_id', $_SESSION['comp_id']);
			$result = $db_conn->update ($update_table, $data);
			$count_results_actual += 1;
			if ($result) $count_results += 1;

			if (SINGLE) {

				$update_table = $prefix."staff";
				$data = array(
					'staff_judge' => 0,
					'staff_steward' => 0,
					'staff_judge_bos' => 0,
					'staff_staff' => 0
				);
				$db_conn->where ('comp_id', $_SESSION['comp_id']);
				$result = $db_conn->update ($update_table, $data);
				$count_results_actual += 1;
				if ($result) $count_results += 1;

			}

			else {
				
				$sql_purge = sprintf("TRUNCATE %s",$prefix."staff");
				$db_conn->rawQuery($sql_purge);
				$count_results_actual += 1;
				if ($db_conn->getLastErrno() === 0) $count_results += 1;

			}

			if (SINGLE) $db_conn->where('comp_id', $_SESSION['comp_id']);
			$rows_judge_locations = $db_conn->get($judging_locations_db_table, null, "id");
			$totalRows_judge_locations = $db_conn->count;

			$locations = array();

			if ($totalRows_judge_locations > 0) {

				foreach ($rows_judge_locations as $row_judge_locations) {

					$locations [] = "N-".$row_judge_locations['id'];

				}

				if (is_array($locations)) {

					$locations_all = implode(",",$locations);

					$update_table = $prefix."brewer";
					$data = array(
						'brewerJudgeLocation' => $locations_all,
						'brewerStewardLocation' => $locations_all
					);
					if (SINGLE) $db_conn->where ('comp_id', $_SESSION['comp_id']);
					$result = $db_conn->update ($update_table, $data);
					$count_results_actual += 1;
					if ($result) $count_results += 1;

				}

			}

			if (SINGLE) {

				$db_conn->where('comp_id', $_SESSION['comp_id']);
				$rows_availability = $db_conn->get($judging_assignments_db_table, null, "id");
				$totalRows_availability = $db_conn->count;

				if ($totalRows_availability > 0) {

					foreach ($rows_availability as $row_availability) {

						$db_conn->where ('id', $row_availability['id']);
						$result = $db_conn->delete ($judging_assignments_db_table);
						$count_results_actual += 1;
						if ($result) $count_results += 1;

					}

				}

			}

			else {

				$sql_purge = sprintf("TRUNCATE %s",$prefix."judging_assignments");
				$db_conn->rawQuery($sql_purge);
				$count_results_actual += 1;
				if ($db_conn->getLastErrno() === 0) $count_results += 1;

			}

			if ($count_results == $count_results_actual) $status = 1;
			
			$redirect = $base_url."index.php?section=admin&msg=26";
			$redirect = prep_redirect_link($redirect);
			$redirect_go_to = sprintf("Location: %s", $redirect);

		} // END if (($go == "availability") || ($go == "purge-all"))

		if ((($go == "evaluation") || ($go == "purge-all")) && (table_exists($prefix."evaluation"))) {

			$sql_purge = sprintf("TRUNCATE %s",$prefix."evaluation");
			$db_conn->rawQuery($sql_purge);
			if ($db_conn->getLastErrno() === 0) {
				$status = 1;
			}

		}

		if ((($go == "payments") || ($go == "purge-all")) && (table_exists($prefix."payments"))) {

			$count_results = 0;
			$count_results_actual = 0;

			$update_table = $prefix."payments";

			// Purge back from posted date
			if (!empty($date_threshold)) {
				
				$date_threshold = strtotime($date_threshold);

				if (SINGLE) {
					$db_conn->where ('comp_id', $_SESSION['comp_id']);
				}
				else {
					$db_conn->where ('payment_time', $date_threshold, "<");
					$db_conn->orWhere ('payment_time', NULL, 'IS');
				}
				$result = $db_conn->delete ($update_table);
				$count_results_actual += 1;
				if ($result) $count_results += 1;

			}

			// Purge all data
			else {

				if (SINGLE) {

					$db_conn->where ('comp_id', $_SESSION['comp_id']);
					$result = $db_conn->delete ($update_table);
					$count_results_actual += 1;
					if ($result) $count_results += 1;

				}

				else {

					$sql_purge = sprintf("TRUNCATE %s",$prefix."payments");
					$db_conn->rawQuery($sql_purge);
					$count_results_actual += 1;
					if ($db_conn->getLastErrno() === 0) $count_results += 1;

				}

			}

			if ($count_results == $count_results_actual) $status = 1;
			$redirect = $base_url."index.php?section=admin&msg=26";
			$redirect = prep_redirect_link($redirect);
			$redirect_go_to = sprintf("Location: %s", $redirect);

		} // END if (($go == "payments") || (($go == "purge-all") && (table_exists($prefix."payments"))))

	} // END if ($action == "purge")

	// User initiated triggering of data integrity check
	if ($action == "cleanup") {
		
		$data_int = data_integrity_check();
		if ($data_int) $status = 1;
		$redirect = $base_url."index.php?section=admin&purge=cleanup";
		$redirect = prep_redirect_link($redirect);
		$redirect_go_to = sprintf("Location: %s", $redirect);

	} // END if ($action == "cleanup")

	if ($action == "confirmed") {

		$update_table = $prefix."brewing";
		$data = array('brewConfirmed' => 1);
		$result = $db_conn->update ($update_table, $data);
		if ($result) $status = 1;

	} // END if ($action == "confirmed")

} // end if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] == 0))

else {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}
?>