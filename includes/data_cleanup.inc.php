<?php
/*
Checked Single
2016-06-06
*/

$count_results = 0;
$date_threshold = "";


if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] == 0)) {

	if ((isset($_POST['dateThreshold'])) && (!empty($_POST['dateThreshold']))) $date_threshold = sterilize($_POST['dateThreshold']); 
	if ((!isset($_POST['dateThreshold']) && ($view != "default"))) $date_threshold = sterilize($view);

	$sql = "action=".$action."&go=".$go;
	if (!empty($date_threshold)) $sql .= "&view=".$view;

	if ($action == "purge") {

		// Purge unconfirmed and/or entries that require special ingredients that do not have special ingredient data
		if ($go == "unconfirmed") {

			$uncon = purge_entries("unconfirmed", 0);
			$spec = purge_entries("special", 0);
			$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&go=entries&purge=true");
			if (($uncon) && ($spec)) $status = 1;

		} // END if ($go == "unconfirmed")
		
		if (($go == "scoresheets") || ($go == "purge-all")) {

			require(LIB.'process.lib.php');
			$delete_scoresheets = rdelete(USER_DOCS,"");
			if ($delete_scoresheets) $status = 1;

		} // END if ($go == "scoresheets")

		// Purge entry and associated data
		if (($go == "entries") || ($go == "purge-all")) {

			// Purge all data from brewing table
			if (!empty($date_threshold)) {

				$query_purge_entries = sprintf("SELECT id,brewJudgingNumber FROM %s WHERE brewUpdated < '%s' OR brewUpdated IS NULL",$brewing_db_table,$date_threshold);
				if (SINGLE) $query_purge_entries .= sprintf(" AND comp_id='%s'",$_SESSION['comp_id']);
				$purge_entries = mysqli_query($connection,$query_purge_entries) or die (mysqli_error($connection));
				$row_purge_entries = mysqli_fetch_assoc($purge_entries);
				$totalRows_purge_entries = mysqli_num_rows($purge_entries);

				// echo $totalRows_purge_entries;

				if ($totalRows_purge_entries == 0) {
					$status = 2; // No entries found
				}

				else {

					do {

						$updateSQL = sprintf("DELETE FROM %s WHERE id='%s'",$brewing_db_table,$row_purge_entries['id']);
						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
						if ($result) $count_results += 1;

						$purge_array = array($judging_scores_db_table,$judging_scores_bos_db_table,$special_best_data_db_table);
						if (table_exists($prefix."evaluation")) $purge_array[] = $prefix."evaluation";

						foreach ($purge_array as $db_table) {
							$updateSQL = sprintf("DELETE FROM %s WHERE eid='%s'",$db_table,$row_purge_entries['id']);
							mysqli_real_escape_string($connection,$updateSQL);
							$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
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

					} while ($row_purge_entries = mysqli_fetch_assoc($purge_entries));

				}

				if ($count_results > 0) {
					
					$status = 1;

					$dom_ct_participants_entries = get_participant_count("received-entrant");
					$dom_ct_entries = get_entry_count("total-logged");
					$dom_ct_entries_unconfirmed = get_entry_count("unconfirmed");
					$dom_ct_entries_paid = get_entry_count("paid");
					$dom_ct_entries_paid_received = get_entry_count("paid-received");
					$total_fees = total_fees($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $bid, $filter, $_SESSION['comp_id']);
					$total_fees_paid = total_fees_paid($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $bid, $filter, $_SESSION['comp_id']);					
					$dom_total_fees = number_format($total_fees,2);
					$dom_total_fees_paid = number_format($total_fees_paid,2);

				}
			}

			// Purge all entries
			else {

				$purge_array = array($brewing_db_table,$judging_scores_db_table,$judging_scores_bos_db_table,$special_best_data_db_table);

				if (table_exists($prefix."evaluation")) $purge_array[] = $prefix."evaluation";
				if (table_exists($prefix."payments")) $purge_array[] = $prefix."payments";

				foreach ($purge_array as $db_table) {
					$updateSQL = sprintf("TRUNCATE %s",$db_table);
					if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
					if ($result) $count_results += 1;
				}

				// Clear judging preferences
				if (!SINGLE) {
					$updateSQL = sprintf("UPDATE %s SET brewerJudge='N',brewerSteward='N',brewerJudgeLikes=NULL,brewerJudgeDislikes=NULL,brewerJudgeLocation=NULL,brewerStewardLocation=NULL",$brewer_db_table);
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
					if ($result) $count_results += 1;
				}

			}

			$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&msg=26");
			
			if ($count_results > 0) {
					
					$status = 1;

					if ($go != "purge-all") {
						
						$dom_ct_participants_entries = get_participant_count("received-entrant");
						$dom_ct_entries = get_entry_count("total-logged");
						$dom_ct_entries_unconfirmed = get_entry_count("unconfirmed");
						$dom_ct_entries_paid = get_entry_count("paid");
						$dom_ct_entries_paid_received = get_entry_count("paid-received");
						$total_fees = total_fees($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $bid, $filter, $_SESSION['comp_id']);
						$total_fees_paid = total_fees_paid($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $bid, $filter, $_SESSION['comp_id']);					
						$dom_total_fees = number_format($total_fees,2);
						$dom_total_fees_paid = number_format($total_fees_paid,2);
					
					}

				}

		} // END if (($go == "entries") || ($go == "purge-all"))

		// Purge participant and associated data
		if (($go == "participants") || ($go == "purge-all")) {

			// Purge all data from brewer and users tables (except admins)

			if (!SINGLE) {

				$query_admin = sprintf("SELECT id,user_name FROM %s WHERE userLevel = '2'", $users_db_table);

				if (!empty($date_threshold)) {
					$query_admin .= sprintf(" AND userCreated < '%s' OR userCreated IS NULL", $date_threshold);
				}

				$admin = mysqli_query($connection,$query_admin) or die (mysqli_error($connection));
				$row_admin = mysqli_fetch_assoc($admin);
				$totalRows_admin = mysqli_num_rows($admin);

				if ($totalRows_admin == 0) {
					$status = 2; // No entries found
				}

				else {

					$dom_ct_participants += $totalRows_admin;

					do {

						// Delete the user's record
						$updateSQL = sprintf("DELETE FROM %s WHERE uid='%s'", $brewer_db_table, $row_admin['id']);
						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
						if ($result) $count_results += 1;

						// Delete the associated brewer record
						$updateSQL = sprintf("DELETE FROM %s WHERE id='%s'", $users_db_table, $row_admin['id']);
						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
						if ($result) $count_results += 1;

						// Delete all the user's entries
						$updateSQL = sprintf("DELETE FROM %s WHERE brewBrewerID='%s'", $brewing_db_table, $row_admin['id']);
						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
						if ($result) $count_results += 1;

						// Purge all data from judging_assignments table
						$updateSQL = sprintf("DELETE FROM %s WHERE bid='%s'",$judging_assignments_db_table, $row_admin['id']);
						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
						if ($result) $count_results += 1;

						// Purge all data from staff table
						$updateSQL = sprintf("DELETE FROM %s WHERE uid='%s'",$staff_db_table, $row_admin['id']);
						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
						if ($result) $count_results += 1;

					} while ($row_admin = mysqli_fetch_assoc($admin));

				}

				if ($count_results > 0) {
					
					$status = 1;

					if ($go != "purge-all") {
						$dom_ct_judges_avail = get_participant_count("judge");
						$dom_ct_judges_assigned = get_participant_count("judge-assigned");
						$dom_ct_stewards_avail = get_participant_count("steward");
						$dom_ct_stewards_assigned = get_participant_count("steward-assigned");
						$dom_ct_staff_avail = get_participant_count("staff");
						$dom_ct_staff_assigned = get_participant_count("staff-assigned");
						$dom_ct_participants_entries = get_participant_count("received-entrant");
						$dom_ct_entries = get_entry_count("total-logged");
						$dom_ct_entries_unconfirmed = get_entry_count("unconfirmed");
						$dom_ct_entries_paid = get_entry_count("paid");
						$dom_ct_entries_paid_received = get_entry_count("paid-received");
						$total_fees = total_fees($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $bid, $filter, $_SESSION['comp_id']);
						$total_fees_paid = total_fees_paid($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $bid, $filter, $_SESSION['comp_id']);					
						$dom_total_fees = number_format($total_fees,2);
						$dom_total_fees_paid = number_format($total_fees_paid,2);
					}
					
				}

			}

			if (!SINGLE) {

				// Clean up any strays
				$query_strays2 = sprintf("SELECT id FROM %s", $users_db_table);

				if (!empty($date_threshold)) {
					$query_strays2 .= sprintf(" WHERE userCreated < '%s' OR userCreated IS NULL", $date_threshold);
				}

				$strays2 = mysqli_query($connection,$query_strays2) or die (mysqli_error($connection));
				$row_strays2 = mysqli_fetch_assoc($strays2);
				$totalRows_strays2 = mysqli_num_rows($strays2);

				if ($totalRows_strays2 > 0) {

					// Users table
					do {
						$query_stray2 = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE uid='%s'", $brewer_db_table, $row_strays2['id']);
						$stray2 = mysqli_query($connection,$query_stray2) or die (mysqli_error($connection));
						$row_stray2 = mysqli_fetch_assoc($stray2);

						if ($row_stray2['count'] == 0) {
							$updateSQL = sprintf("DELETE FROM %s WHERE id='%s'", $users_db_table, $row_strays2['id']);
							mysqli_real_escape_string($connection,$updateSQL);
							$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
							if ($result) $count_results += 1;
						}


					} while ($row_strays2 = mysqli_fetch_assoc($strays2));

					// Brewer table
					$query_strays = sprintf("SELECT id,uid FROM %s", $brewer_db_table);
					$strays = mysqli_query($connection,$query_strays) or die (mysqli_error($connection));
					$row_strays = mysqli_fetch_assoc($strays);
					$totalRows_strays = mysqli_num_rows($strays);

					if ($totalRows_strays > 0) {

						do {
							$query_stray = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE id='%s'", $users_db_table, $row_strays['id']);
							$stray = mysqli_query($connection,$query_stray) or die (mysqli_error($connection));
							$row_stray = mysqli_fetch_assoc($stray);

							if ($row_stray['count'] == 0) {
								$updateSQL = sprintf("DELETE FROM %s WHERE id='%s'", $brewer_db_table, $row_strays['id']);
								mysqli_real_escape_string($connection,$updateSQL);
								$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
								if ($result) $count_results += 1;
							}

						} while ($row_strays = mysqli_fetch_assoc($strays));

					}

				}

			}

			if ($count_results > 0) $status = 1;
			$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&msg=26");

		} // END if (($go == "participants") || ($go == "purge-all"))

		// Purge scores and associated data
		if (($go == "scores") || ($go == "purge-all")) {

			$purge_array = array($judging_scores_db_table,$judging_scores_bos_db_table,$special_best_data_db_table);

			foreach ($purge_array as $db_table) {
				$updateSQL = sprintf("TRUNCATE %s",$db_table);
				if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				if ($result) $count_results += 1;
			}

			if ($count_results > 0) $status = 1;
			$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&msg=26");

		} // END if (($go == "scores") || ($go == "purge-all"))

		// Purge judging tables and associated data
		if (($go == "tables") || ($go == "purge-all")) {

			$purge_array = array($judging_tables_db_table,$judging_assignments_db_table,$judging_flights_db_table,$judging_scores_db_table,$special_best_data_db_table);

			foreach ($purge_array as $db_table) {
				$updateSQL = sprintf("TRUNCATE %s",$db_table);
				if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				if ($result) $count_results += 1;
			}

			if ($count_results > 0) $status = 1;
			$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&msg=26");

		} // END if (($go == "tables") || ($go == "purge-all"))

		if (($go == "custom") || ($go == "purge-all")) {

			$purge_array = array($special_best_info_db_table,$special_best_data_db_table);

			foreach ($purge_array as $db_table) {
				$updateSQL = sprintf("TRUNCATE %s",$db_table);
				if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				if ($result) $count_results += 1;
			}

			if ($count_results > 0) $status = 1;
			$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&msg=26");

		} // END if (($go == "custom") || ($go == "purge-all"))

		if (($go == "availability") || ($go == "purge-all")) {

			$updateSQL = sprintf("UPDATE %s SET brewerJudge='N'",$brewer_db_table);
			if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			if ($result) $count_results += 1;

			$updateSQL = sprintf("UPDATE %s SET brewerSteward='N'",$brewer_db_table);
			if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			if ($result) $count_results += 1;

			$updateSQL = sprintf("UPDATE %s SET brewerStaff='N'",$brewer_db_table);
			if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			if ($result) $count_results += 1;

			$updateSQL = sprintf("UPDATE %s SET brewerAssignment=NULL",$brewer_db_table);
			if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			if ($result) $count_results += 1;

			if (SINGLE) {

				$updateSQL = sprintf("UPDATE %s SET staff_judge='0', staff_steward='0', staff_judge_bos='0', staff_staff='0', staff_organizer='0' WHERE comp_id='%s'",$staff_db_table,$_SESSION['comp_id']);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				if ($result) $count_results += 1;

			}

			else {

				$updateSQL = sprintf("TRUNCATE %s ",$staff_db_table);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				if ($result) $count_results += 1;

			}

			$query_judge_locations = sprintf("SELECT id FROM %s", $judging_locations_db_table);
			if (SINGLE) $query_judge_locations .= sprintf(" WHERE comp_id='%s'", $_SESSION['comp_id']);
			$judge_locations = mysqli_query($connection,$query_judge_locations) or die (mysqli_error($connection));
			$row_judge_locations = mysqli_fetch_assoc($judge_locations);
			$totalRows_judge_locations = mysqli_num_rows($judge_locations);

			$locations = array();

			if ($totalRows_judge_locations > 0) {

				do {

					$locations [] = "N-".$row_judge_locations['id'];

				} while ($row_judge_locations = mysqli_fetch_assoc($judge_locations));

				if (is_array($locations)) {

					$locations_all = implode(",",$locations);

					$updateSQL = sprintf("UPDATE %s SET brewerJudgeLocation='%s'",$brewer_db_table, $locations_all);
					if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
					if ($result) $count_results += 1;

					$updateSQL = sprintf("UPDATE %s SET brewerStewardLocation='%s'",$brewer_db_table, $locations_all);
					if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
					if ($result) $count_results += 1;

				}

			}

			if (SINGLE) {

				$query_availability = sprintf("SELECT id FROM %s WHERE comp_id='%s'", $judging_assignments_db_table, $_SESSION['comp_id']);
				$availability = mysqli_query($connection,$query_availability) or die (mysqli_error($connection));
				$row_availability = mysqli_fetch_assoc($availability);
				$totalRows_availability = mysqli_num_rows($availability);

				if ($totalRows_availability > 0) {

					do {

						$updateSQL = sprintf("DELETE FROM %s WHERE id='%s'",$judging_assignments_db_table, $row_availability['id']);
						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
						if ($result) $count_results += 1;

					} while ($row_availability = mysqli_fetch_assoc($availability));

				}

			}

			else {

				$updateSQL = sprintf("TRUNCATE %s",$judging_assignments_db_table);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				if ($result) $count_results += 1;

			}

			if ($count_results > 0) $status = 1;
			$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&msg=26");

		} // END if (($go == "availability") || ($go == "purge-all"))

		if ((($go == "evaluation") || ($go == "purge-all")) && (table_exists($prefix."evaluation"))) {
			$updateSQL = sprintf("TRUNCATE %s",$prefix."evaluation");
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			if ($result) $status = 1;
		}

		if ((($go == "payments") || ($go == "purge-all")) && (table_exists($prefix."payments"))) {

			// Purge back from posted date
			if (!empty($date_threshold)) {
				$date_threshold = strtotime($date_threshold);
				$updateSQL = sprintf("DELETE FROM %s WHERE payment_time < '%s' OR payment_time IS NULL",$prefix."payments",$date_threshold);
				if (SINGLE) $updateSQL .= sprintf(" AND comp_id='%s'",$_SESSION['comp_id']);
			}

			// Purge all data
			else {
				$updateSQL = sprintf("TRUNCATE %s",$prefix."payments");
				if (SINGLE) $updateSQL = sprintf("DELETE FROM %s WHERE comp_id='%s'",$prefix."payments",$_SESSION['comp_id']);
			}

			// echo $updateSQL; exit;

			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			if ($result) $count_results += 1;

			if ($count_results > 0) $status = 1;
			$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&msg=26");

		} // END if (($go == "payments") || (($go == "purge-all") && (table_exists($prefix."payments"))))

	} // END if ($action == "purge")

	// User initiated triggering of data integrity check
	if ($action == "cleanup") {
		$data_int = data_integrity_check();
		if ($data_int) $status = 1;
		$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&purge=cleanup");
	} // END if ($action == "cleanup")

	if ($action == "confirmed") {
		$updateSQL = sprintf("UPDATE %s SET brewConfirmed='1'",$prefix."brewing");
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		if ($result) $status = 1;
	} // END if ($action == "confirmed")

}
?>