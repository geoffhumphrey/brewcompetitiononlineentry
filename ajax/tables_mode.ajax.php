<?php
ob_start();

require('../paths.php');
require(CONFIG.'bootstrap.php');

ini_set('display_errors', 0); // Change to 0 for prod; change to 1 for testing.
ini_set('display_startup_errors', 0); // Change to 0 for prod; change to 1 for testing.
error_reporting(0); // Change to error_reporting(0) for prod; change to E_ALL for testing.

$return_json = array();
$status = 0;
$process = FALSE;
$sql = "";
$input = "";
$post = 0;
$error_type = 0;
$error_count = 0;
$unassign_flag = 0;

/*
if (HOSTED) $styles_db_table = "bcoem_shared_styles";
else
*/
$styles_db_table = $prefix."styles";

/**
 * Convert all records in the judging_assignments 
 * and judging_flights tables to 1 (planning).
 */

$session_active = FALSE;
if ((isset($_SESSION['session_set_'.$prefix_session])) && (isset($_SESSION['loginUsername']))) $session_active = TRUE;

if (($session_active) && ($_SESSION['userLevel'] <= 2)) {

	if ($section == "enable-planning") {

		// Check if assignPlanning row is in the judging_assignments table
		// If not, add it
		if (!check_update("assignPlanning", $prefix."judging_assignments")) {
			$sql = sprintf("ALTER TABLE `%s` ADD `assignPlanning` TINYINT(1) NULL;",$prefix."judging_assignments");
			mysqli_select_db($connection,$database);
			mysqli_real_escape_string($connection,$sql);
			$result = mysqli_query($connection,$sql);
			if (!$result) $error_count += 1;
		}

		// Check if flightPlanning row is in the judging_flights table
		// If not, add it
		if (!check_update("flightPlanning", $prefix."judging_flights")) {
			$sql = sprintf("ALTER TABLE `%s` ADD `flightPlanning` TINYINT(1) NULL;",$prefix."judging_flights");
			mysqli_select_db($connection,$database);
			mysqli_real_escape_string($connection,$sql);
			$result = mysqli_query($connection,$sql);
			if (!$result) $error_count += 1;
		}

		// Check if jPrefsTablePlanning row is in the judging_preferences table
		// If not, add it
		if (!check_update("jPrefsTablePlanning", $prefix."judging_preferences")) {
			$sql = sprintf("ALTER TABLE `%s` ADD `jPrefsTablePlanning` TINYINT(1) NULL;",$prefix."judging_preferences");
			mysqli_select_db($connection,$database);
			mysqli_real_escape_string($connection,$sql);
			$result = mysqli_query($connection,$sql);
			if (!$result) $error_count += 1;
		}
		
		$query_flight_entries = sprintf("SELECT COUNT(*) as 'count' FROM %s", $prefix."judging_flights");
		$flight_entries = mysqli_query($connection,$query_flight_entries) or die (mysqli_error($connection));
		$row_flight_entries = mysqli_fetch_assoc($flight_entries);

		if ($row_flight_entries['count'] > 0) {

			// Loop through the tables and their styles
			$query_table = sprintf("SELECT id,tableStyles,tableLocation FROM %s", $prefix."judging_tables");
			$table = mysqli_query($connection,$query_table) or die (mysqli_error($connection));
			$row_table = mysqli_fetch_assoc($table);

			do {

				$a = explode(",",$row_table['tableStyles']);
				$updated_table_styles = array();

				// Query the entries table for all ids for each sub-style
				foreach (array_unique($a) as $value) {

					/* 
					if (HOSTED) $query_styles = sprintf("SELECT brewStyleGroup, brewStyleNum FROM %s WHERE id='%s' UNION ALL SELECT brewStyleGroup, brewStyleNum FROM %s WHERE id='%s'", $styles_db_table, $value, $prefix."styles", $value); 
					else 
					*/
					$query_styles = sprintf("SELECT brewStyleGroup, brewStyleNum FROM %s WHERE id='%s'", $styles_db_table, $value);
					$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
					$row_styles = mysqli_fetch_assoc($styles);
					
					$query_entries = sprintf("SELECT id,brewReceived FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s'", $prefix."brewing", $row_styles['brewStyleGroup'], $row_styles['brewStyleNum']);
					$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
					$row_entries = mysqli_fetch_assoc($entries);
					$totalRows_entries = mysqli_num_rows($entries);

					// Get assigned round for flight 1
					$query_fl_round = sprintf("SELECT flightRound FROM %s WHERE flightTable='%s' AND flightNumber='1' LIMIT 1", $prefix."judging_flights", $row_table['id']);
					$fl_round = mysqli_query($connection,$query_fl_round) or die (mysqli_error($connection));
					$row_fl_round = mysqli_fetch_assoc($fl_round);

					if ($totalRows_entries > 0) {

						// Loop through and add all non-received entries into the judging_flights table
						do {

							if ($row_entries['brewReceived'] == 0) {

								$update_table = $prefix."judging_flights";
								$data = array(
									'flightTable' => $row_table['id'],
									'flightNumber' => 1,
									'flightEntryID' => $row_entries['id'],
									'flightRound' => $row_fl_round['flightRound']
								);
								$result = $db_conn->insert ($update_table, $data);
								if (!$result) $error_count += 1;

							}

						} while($row_entries = mysqli_fetch_assoc($entries));

						$updated_table_styles[] = $value;
					}

				} // end foreach (array_unique($a) as $value)

				if (empty($updated_table_styles)) {

					$update_table = $prefix."judging_tables";
					$db_conn->where ('id', $row_table['id']);
					$result = $db_conn->delete ($update_table);
					if (!$result) $error_count += 1;

					$update_table = $prefix."judging_assignments";
					$db_conn->where ('assignTable', $row_table['id']);
					$result = $db_conn->delete ($update_table);
					if (!$result) $error_count += 1;

					$update_table = $prefix."judging_flights";
					$db_conn->where ('flightTable', $row_table['id']);
					$result = $db_conn->delete ($update_table);
					if (!$result) $error_count += 1;
					
				}

				// If at least one style, update the table's styles
				else {

					$new_table_styles = implode(",", $updated_table_styles);
					
					$update_table = $prefix."judging_tables";
					$data = array('tableStyles' => $new_table_styles);
					$db_conn->where ('id', $row_table['id']);
					$result = $db_conn->update ($update_table, $data);
					if (!$result) $error_count += 1;

				}

			} while($row_table = mysqli_fetch_assoc($table));
			
			$update_table = $prefix."judging_flights";
			$data = array('flightPlanning' => 1);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) $error_count += 1;
			
			$update_table = $prefix."judging_assignments";
			$data = array('assignPlanning' => 1);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) $error_count += 1;

		} // end if ($row_flight_entries['count'] > 0)
		
		$update_table = $prefix."judging_preferences";
		$data = array('jPrefsTablePlanning' => 1);
		$result = $db_conn->update ($update_table, $data);
		if (!$result) $error_count += 1;

		unset($_SESSION['prefs'.$prefix_session]);

		// If error count is zero, change $status from fail (0) to success (1)
		if ($error_count == 0) $status = 1;
		else $error_type = 3; // SQL error

	} // end if ($section == "enable-planning")

	/**
	 * Check all records in the judging_flights DB 
	 * table to verify if each relational record
	 * has been marked as received in the brewing 
	 * DB table. If so, retain in judging_flights. 
	 * If not, delete.
	 * 
	 * Also convert all records in the
	 * judging_assignments table to 0 (production).
	 * Finally, check for entry conflicts for any
	 * assigned judge. Unassign if they have an entry
	 * at the table they were assigned to in planning
	 * mode.
	 */

	if ($section == "enable-competition") {

		$_SESSION['judge_unassign_flag'] = 0;

		require(LIB."admin.lib.php");

		$received_entries_arr = array();
		$flight_entries_arr = array();

		// Get ids of all entries marked as received in the brewing table
		$query_received_entries = sprintf("SELECT id FROM %s WHERE brewReceived='1'", $prefix."brewing");
		$received_entries = mysqli_query($connection,$query_received_entries) or die (mysqli_error($connection));
		$row_received_entries = mysqli_fetch_assoc($received_entries);
		$totalRows_received_entries = mysqli_num_rows($received_entries);

		// Convert to array
		if ($totalRows_received_entries > 0) {
			do {
				$received_entries_arr[] = $row_received_entries['id'];
			} while ($row_received_entries = mysqli_fetch_assoc($received_entries));
		}

		// Get all entry ids in the judging_flights table
		$query_flight_entries = sprintf("SELECT flightEntryID FROM %s", $prefix."judging_flights");
		$flight_entries = mysqli_query($connection,$query_flight_entries) or die (mysqli_error($connection));
		$row_flight_entries = mysqli_fetch_assoc($flight_entries);
		$totalRows_flight_entries = mysqli_num_rows($flight_entries);

		// Convert to array
		if ($totalRows_flight_entries > 0) {
			do {
				if (!empty($row_flight_entries['flightEntryID'])) $flight_entries_arr[] = $row_flight_entries['flightEntryID'];
			} while ($row_flight_entries = mysqli_fetch_assoc($flight_entries));
		}

		// print_r($received_entries_arr);
		// echo "<br><br>";
		// print_r($flight_entries_arr);

		// Loop through and compare, deleting any record from the judging_flights that
		// isn't in the received entry list.

		if (!empty($flight_entries_arr)) {
			
			// Loop through flight_entries array
			foreach ($flight_entries_arr as $value) {
				
				// Delete if no relational value
				if (!in_array($value, $received_entries_arr)) {
					
					$update_table = $prefix."judging_flights";
					$db_conn->where ('flightEntryID', $value);
					$result = $db_conn->delete ($update_table);
					if (!$result) $error_count += 1;

				}

			}

			// Update all records left to production (0)
			$update_table = $prefix."judging_flights";
			$data = array('flightPlanning' => 0);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) $error_count += 1;

			// Loop through the tables and their styles made in planning mode
			$query_table = sprintf("SELECT id,tableStyles,tableLocation FROM %s", $prefix."judging_tables");
			$table = mysqli_query($connection,$query_table) or die (mysqli_error($connection));
			$row_table = mysqli_fetch_assoc($table);

			// --------- Update Table Styles ------

			do {

				$a = explode(",",$row_table['tableStyles']);
				$updated_table_styles = array();

				foreach (array_unique($a) as $value) {

					/*
					if (HOSTED) $query_styles = sprintf("SELECT brewStyleGroup, brewStyleNum FROM %s WHERE id='%s' UNION ALL SELECT brewStyleGroup, brewStyleNum FROM %s WHERE id='%s'", $styles_db_table, $value, $prefix."styles", $value); 
					else 
					*/
					$query_styles = sprintf("SELECT brewStyleGroup, brewStyleNum FROM %s WHERE id='%s'", $styles_db_table, $value);
					$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
					$row_styles = mysqli_fetch_assoc($styles);
					
					$query_entries = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s' AND brewReceived='1'", $prefix."brewing", $row_styles['brewStyleGroup'], $row_styles['brewStyleNum']);
					$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
					$row_entries = mysqli_fetch_assoc($entries);

					if ($row_entries['count'] > 0) {
						$updated_table_styles[] = $value;
					}

				} // end foreach (array_unique($a) as $value)

				// If no styles, delete the table and any associated flights or assignments
				if (empty($updated_table_styles)) {

					$update_table = $prefix."judging_tables";
					$db_conn->where ('id', $row_table['id']);
					$result = $db_conn->delete ($update_table);
					if (!$result) $error_count += 1;

					$update_table = $prefix."judging_assignments";
					$db_conn->where ('assignTable', $row_table['id']);
					$result = $db_conn->delete ($update_table);
					if (!$result) $error_count += 1;

					$update_table = $prefix."judging_flights";
					$db_conn->where ('flightTable', $row_table['id']);
					$result = $db_conn->delete ($update_table);
					if (!$result) $error_count += 1;
					
				}

				// If at least one style, update the table's styles
				else {

					$new_table_styles = implode(",", $updated_table_styles);
					
					$update_table = $prefix."judging_tables";
					$data = array('tableStyles' => $new_table_styles);
					$db_conn->where ('id', $row_table['id']);
					$result = $db_conn->update ($update_table, $data);
					if (!$result) $error_count += 1;

					// Check if assigned judges or stewards have any
					// entries at this table.

					// Query judging assignments for this table.
					$query_table_assignments = sprintf("SELECT id,bid FROM %s WHERE assignTable='%s'",$prefix."judging_assignments",$row_table['id']);
					$table_assignments = mysqli_query($connection,$query_table_assignments) or die (mysqli_error($connection));
					$row_table_assignments = mysqli_fetch_assoc($table_assignments);

					do {

						if ($row_table_assignments) $entry_conflict = entry_conflict($row_table_assignments['bid'],$new_table_styles);
						else $entry_conflict = entry_conflict("999999999",$new_table_styles);
						
						if ($entry_conflict) {

							$update_table = $prefix."judging_assignments";
							$db_conn->where ('id', $row_table_assignments['id']);
							$result = $db_conn->delete ($update_table);
							if (!$result) $error_count += 1;

							$unassign_flag += 1;

						}

					} while($row_table_assignments = mysqli_fetch_assoc($table_assignments));

				} // end else
				
			} while($row_table = mysqli_fetch_assoc($table));

			$update_table = $prefix."judging_assignments";
			$data = array('assignPlanning' => 0);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) $error_count += 1;

		} // end if (!empty($flight_entries_arr))

		// If the $flight_entries_arr is empty, simply truncate the three associated 
		// db tables: judging_assignments, judging_flights, and judging_tables
		else {
			
			$sql = sprintf("TRUNCATE `%s`", $prefix."judging_assignments");
			$db_conn->rawQuery($sql);
			if ($db_conn->getLastErrno() !== 0) $error_count += 1;

			$sql = sprintf("TRUNCATE `%s`", $prefix."judging_flights");
			$db_conn->rawQuery($sql);
			if ($db_conn->getLastErrno() !== 0) $error_count += 1;

			$sql = sprintf("TRUNCATE `%s`", $prefix."judging_tables");
			$db_conn->rawQuery($sql);
			if ($db_conn->getLastErrno() !== 0) $error_count += 1;

		}

		$update_table = $prefix."judging_preferences";
		$data = array('jPrefsTablePlanning' => 0);
		$result = $db_conn->update ($update_table, $data);
		if (!$result) $error_count += 1;

		unset($_SESSION['prefs'.$prefix_session]);

		// If error count is zero, change $status from fail (0) to success (1)
		if ($error_count == 0) $status = 1;
		else $error_type = 3; // SQL error

		if ($unassign_flag > 0) $_SESSION['judge_unassign_flag'] = 1;

	}

	$return_json = array(
		"status" => "$status",
		"error_count" => "$error_count",
		"error_type" => "$error_type"
	);

	// Return the json
	echo json_encode($return_json);

}

mysqli_close($connection);

?>