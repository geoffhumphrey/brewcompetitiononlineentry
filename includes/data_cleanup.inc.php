<?php
/*
Checked Single
2016-06-06
*/

if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] == 0)) {

	// User initiated purging of data

	if ($action == "purge") {

		// Purge unconfirmed and/or entries that require special ingredients that do not have special ingredient data
		if ($go == "unconfirmed") {

			purge_entries("unconfirmed", 0);
			purge_entries("special", 0);
			$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&go=entries&purge=true");

		}

		// Purge entry and associated data
		if (($go == "entries") || ($go == "purge-all")) {

			// Purge all data from brewing table
			if ((isset($_POST['dateThreshold'])) && (!empty($_POST['dateThreshold']))) {

				$date_threshold = sterilize($_POST['dateThreshold']);

				$query_purge_entries = sprintf("SELECT id,brewJudgingNumber FROM %s", $brewing_db_table);
				$query_purge_entries .= sprintf(" WHERE brewUpdated < '%s' OR brewUpdated IS NULL", $date_threshold);
				if (SINGLE) $query_purge_entries .= sprintf(" AND comp_id='%s'",$_SESSION['comp_id']);
				$purge_entries = mysqli_query($connection,$query_purge_entries) or die (mysqli_error($connection));
				$row_purge_entries = mysqli_fetch_assoc($purge_entries);
				$totalRows_purge_entries = mysqli_num_rows($purge_entries);

				if ($totalRows_purge_entries > 0) {

					do {

						$updateSQL = sprintf("DELETE FROM %s WHERE id='%s'",$brewing_db_table,$row_purge_entries['id']);
						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

						$purge_array = array($judging_scores_db_table,$judging_scores_bos_db_table,$special_best_data_db_table);

						foreach ($purge_array as $db_table) {
							$updateSQL = sprintf("DELETE FROM %s WHERE eid='%s'",$db_table,$row_purge_entries['id']);
							mysqli_real_escape_string($connection,$updateSQL);
							$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
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

			}

			// Purge all entries
			else {

				$purge_array = array($brewing_db_table,$judging_scores_db_table,$judging_scores_bos_db_table,$special_best_data_db_table);

				foreach ($purge_array as $db_table) {
					$updateSQL = sprintf("TRUNCATE %s",$db_table);
					if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				}

				// Clear judging preferences
				if (!SINGLE) {
					$updateSQL = sprintf("UPDATE %s SET brewerJudge='N',brewerSteward='N',brewerJudgeLikes=NULL,brewerJudgeDislikes=NULL,brewerJudgeLocation=NULL,brewerStewardLocation=NULL",$brewer_db_table);
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				}

			}

			$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&msg=26");

		}

		// Purge participant and associated data
		if (($go == "participants") || ($go == "purge-all")) {

			// Purge all data from brewer and users tables (except admins)

			if (!SINGLE) {

				$query_admin = sprintf("SELECT id,user_name FROM %s WHERE userLevel = '2'", $users_db_table);

				if ((isset($_POST['dateThreshold'])) && (!empty($_POST['dateThreshold']))) {
					$date_threshold = sterilize($_POST['dateThreshold']);
					$query_admin .= sprintf(" AND userCreated < '%s' OR userCreated IS NULL", $date_threshold);
				}

				$admin = mysqli_query($connection,$query_admin) or die (mysqli_error($connection));
				$row_admin = mysqli_fetch_assoc($admin);
				$totalRows_admin = mysqli_num_rows($admin);

				if ($totalRows_admin > 0) {

					do {

						// Delete the user's record
						$updateSQL = sprintf("DELETE FROM %s WHERE uid='%s'", $brewer_db_table, $row_admin['id']);
						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

						// Delete the associated brewer record
						$updateSQL = sprintf("DELETE FROM %s WHERE id='%s'", $users_db_table, $row_admin['id']);
						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

						// Delete all the user's entries
						$updateSQL = sprintf("DELETE FROM %s WHERE brewBrewerID='%s'", $brewing_db_table, $row_admin['id']);
						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

						// Purge all data from judging_assignments table
						$updateSQL = sprintf("DELETE FROM %s WHERE bid='%s'",$judging_assignments_db_table, $row_admin['id']);
						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

						// Purge all data from staff table
						$updateSQL = sprintf("DELETE FROM %s WHERE uid='%s'",$staff_db_table, $row_admin['id']);
						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

					} while ($row_admin = mysqli_fetch_assoc($admin));

				}

			}

			if (!SINGLE) {

				// Clean up any strays
				$query_strays2 = sprintf("SELECT id FROM %s", $users_db_table);

				if ((isset($_POST['dateThreshold'])) && (!empty($_POST['dateThreshold']))) {
					$date_threshold = sterilize($_POST['dateThreshold']);
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
							}

						} while ($row_strays = mysqli_fetch_assoc($strays));

					}

				}

			}

			$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&msg=26");

		}

		// Purge scores and associated data
		if (($go == "scores") || ($go == "purge-all")) {

			$purge_array = array($judging_scores_db_table,$judging_scores_bos_db_table,$special_best_data_db_table);

			foreach ($purge_array as $db_table) {
				$updateSQL = sprintf("TRUNCATE %s",$db_table);
				if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			}

			$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&msg=26");

		}

		// Purge judging tables and associated data
		if (($go == "tables") || ($go == "purge-all")) {

			$purge_array = array($judging_tables_db_table,$judging_assignments_db_table,$judging_flights_db_table,$judging_scores_db_table,$special_best_data_db_table);

			foreach ($purge_array as $db_table) {
				$updateSQL = sprintf("TRUNCATE %s",$db_table);
				if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			}

			$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&msg=26");

		}

		if (($go == "custom") || ($go == "purge-all")) {

			$purge_array = array($special_best_info_db_table,$special_best_data_db_table);

			foreach ($purge_array as $db_table) {
				$updateSQL = sprintf("TRUNCATE %s",$db_table);
				if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			}

			$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&msg=26");

		}

		if (($go == "availability") || ($go == "purge-all")) {

			$updateSQL = sprintf("UPDATE %s SET brewerJudge='N'",$brewer_db_table);
			if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			$updateSQL = sprintf("UPDATE %s SET brewerSteward='N'",$brewer_db_table);
			if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			$updateSQL = sprintf("UPDATE %s SET brewerStaff='N'",$brewer_db_table);
			if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			$updateSQL = sprintf("UPDATE %s SET brewerAssignment=NULL",$brewer_db_table);
			if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			if (SINGLE) {

				$updateSQL = sprintf("UPDATE %s SET staff_judge='0', staff_steward='0', staff_judge_bos='0', staff_staff='0', staff_organizer='0' WHERE comp_id='%s'",$staff_db_table,$_SESSION['comp_id']);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			}

			else {

				$updateSQL = sprintf("TRUNCATE %s ",$staff_db_table);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

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

					$updateSQL = sprintf("UPDATE %s SET brewerStewardLocation='%s'",$brewer_db_table, $locations_all);
					if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

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

					} while ($row_availability = mysqli_fetch_assoc($availability));

				}

			}

			else {

				$updateSQL = sprintf("TRUNCATE %s",$judging_assignments_db_table);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			}

			$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&msg=26");

		}

		if (($go == "payments") || (($go == "purge-all") && (table_exists($prefix."payments")))) {

			// Purge back from posted date
			if ((isset($_POST['dateThreshold'])) && (!empty($_POST['dateThreshold']))) {

				$date_threshold = sterilize($_POST['dateThreshold']);
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

			$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&msg=26");

		}

	}

	// User initiated triggering of data integrity check

	if ($action == "cleanup") {
		data_integrity_check();
		$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&purge=cleanup");
	}

} else echo "<p>Not available.</p>";

?>