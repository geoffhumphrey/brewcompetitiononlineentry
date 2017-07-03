<?php
/*
Checked Single
2016-06-06
*/

require('../paths.php');
require(INCLUDES.'url_variables.inc.php');
require(LIB.'common.lib.php');
require(INCLUDES.'db_tables.inc.php');
require(DB.'common.db.php');

if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] == "0")) {

	// User initiated purging of data

	if ($action == "purge") {

		// Purge unconfirmed and/or entries that require special ingredients that do not have special ingredient data
		if ($go == "unconfirmed") {

			purge_entries("unconfirmed", 0);
			purge_entries("special", 0);
			header(sprintf("Location: %s", $base_url."index.php?section=admin&go=entries&purge=true"));

		}

		// Purge entry and associated data
		if (($go == "entries") || ($go == "purge-all")) {

			// Purge all data from brewing table
			$updateSQL = sprintf("TRUNCATE %s",$brewing_db_table);
			if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			// Purge all data from judging_scores table
			$updateSQL = sprintf("TRUNCATE %s",$judging_scores_db_table);
			if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			// Purge all data from judging_scores bos table
			$updateSQL = sprintf("TRUNCATE %s",$judging_scores_bos_db_table);
			if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			// Purge all data from special_best_data table
			$updateSQL = sprintf("TRUNCATE %s",$special_best_data_db_table);
			if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			// Clear judging preferences
			if (!SINGLE) {
				$updateSQL = sprintf("UPDATE %s SET brewerJudge='N',brewerSteward='N',brewerJudgeLikes=NULL,brewerJudgeDislikes=NULL,brewerJudgeLocation=NULL,brewerStewardLocation=NULL",$brewer_db_table);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			}

			header(sprintf("Location: %s", $base_url."index.php?section=admin&msg=26"));

		}

		// Purge participant and associated data
		if (($go == "participants") || ($go == "purge-all")) {

			// Purge all data from brewer and users tables (except admins)

			if (!SINGLE) {

				$query_admin = sprintf("SELECT id,user_name FROM %s WHERE userLevel = '2' ORDER BY id", $users_db_table);
				$admin = mysqli_query($connection,$query_admin) or die (mysqli_error($connection));
				$row_admin = mysqli_fetch_assoc($admin);
				$totalRows_admin = mysqli_num_rows($admin);

				do {

					$updateSQL = sprintf("DELETE FROM %s WHERE uid='%s'", $brewer_db_table, $row_admin['id']);
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

					$updateSQL = sprintf("DELETE FROM %s WHERE id='%s'", $users_db_table, $row_admin['id']);
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

				} while ($row_admin = mysqli_fetch_assoc($admin));

			}

			// Purge all data from judging_assignments table
			$updateSQL = sprintf("TRUNCATE %s",$judging_assignments_db_table);
			if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			// Purge all data from staff table
			$updateSQL = sprintf("TRUNCATE %s",$staff_db_table);
			if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			if (!SINGLE) {

				// Clean up any strays
				$query_strays = sprintf("SELECT id,uid FROM %s", $brewer_db_table);
				$strays = mysqli_query($connection,$query_strays) or die (mysqli_error($connection));
				$row_strays = mysqli_fetch_assoc($strays);

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


				// Clean up any strays
				$query_strays2 = sprintf("SELECT id FROM %s", $users_db_table);
				$strays2 = mysqli_query($connection,$query_strays2) or die (mysqli_error($connection));
				$row_strays2 = mysqli_fetch_assoc($strays2);

				do {
					$query_stray2 = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE uid='%s'", $brewer_db_table, $row_strays2['id']);
					$stray2 = mysqli_query($connection,$query_stray2) or die (mysqli_error($connection));
					$row_stray2 = mysqli_fetch_assoc($stray2);

					if ($row_stray2['count'] == 0) {
						$updateSQL = sprintf("DELETE FROM %s WHERE id='%s'", $user_db_table, $row_strays2['id']);
						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
					}


				} while ($row_strays2 = mysqli_fetch_assoc($strays2));

			}

			header(sprintf("Location: %s", $base_url."index.php?section=admin&msg=26"));

		}

		// Purge scores and associated data
		if (($go == "scores") || ($go == "purge-all")) {

			// Purge all data from judging_scores table
			$updateSQL = sprintf("TRUNCATE %s",$judging_scores_db_table);
			if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			// Purge all data from judging_scores bos table
			$updateSQL = sprintf("TRUNCATE %s",$judging_scores_bos_db_table);
			if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			// Purge all daa from special_best_data table
			$updateSQL = sprintf("TRUNCATE %s",$special_best_data_db_table);
			if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			header(sprintf("Location: %s", $base_url."index.php?section=admin&msg=26"));

		}

		// Purge judging tables and associated data
		if (($go == "tables") || ($go == "purge-all")) {

			// Purge all data from judging_tables table
			$updateSQL = sprintf("TRUNCATE %s",$judging_tables_db_table);
			if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			// Purge all data from judging_assignments table
			$updateSQL = sprintf("TRUNCATE %s",$judging_assignments_db_table);
			if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			// Purge all data from judging_flights table
			$updateSQL = sprintf("TRUNCATE %s",$judging_flights_db_table);
			if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			// Purge all data from judging_scores table
			$updateSQL = sprintf("TRUNCATE %s",$judging_scores_db_table);
			if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			// Purge all data from judging_scores_bos table
			$updateSQL = sprintf("TRUNCATE %s",$judging_scores_bos_db_table);
			if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			// Purge all daa from special_best_data table
			$updateSQL = sprintf("TRUNCATE %s",$special_best_data_db_table);
			if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			header(sprintf("Location: %s", $base_url."index.php?section=admin&msg=26"));

		}

		if (($go == "custom") || ($go == "purge-all")) {

			// Purge all data from special best info table
			$updateSQL = sprintf("TRUNCATE %s",$special_best_info_db_table);
			if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			// Purge all daa from special_best_data table
			$updateSQL = sprintf("TRUNCATE %s",$special_best_data_db_table);
			if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			header(sprintf("Location: %s", $base_url."index.php?section=admin&msg=26"));

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
			
			$locations = "";
			
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

			header(sprintf("Location: %s", $base_url."index.php?section=admin&msg=26"));

		}

		if (($go == "payments") || ($go == "purge-all")) {

			// Purge all data from special best info table
			$updateSQL = sprintf("TRUNCATE %s",$payments_db_table);
			if (SINGLE) $updateSQL .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			header(sprintf("Location: %s", $base_url."index.php?section=admin&msg=26"));

		}

	}

	// User initiated triggering of data integrity check

	if ($action == "cleanup") {
		
		data_integrity_check();
		header(sprintf("Location: %s", $base_url."index.php?section=admin&purge=cleanup"));
		
	}

} else echo "<p>Not available.</p>";

?>