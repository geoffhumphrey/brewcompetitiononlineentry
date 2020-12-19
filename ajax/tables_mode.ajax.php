<?php
ob_start();
ini_set('display_errors', 1); // Change to 0 for prod.
ini_set('display_startup_errors', 1); // Change to 0 for prod.
error_reporting(E_ALL); // Change to error_reporting(0) for prod.
require('../paths.php');
require(CONFIG.'bootstrap.php');

$return_json = array();
$status = 0;
$process = FALSE;
$sql = "";
$input = "";
$post = 0;
$error_type = 0;
$error_count = 0;

/*
 * Convert all records in the judging_assignments 
 * and judging_flights tables to 1 (planning).
 */

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

				$query_styles = sprintf("SELECT brewStyleGroup, brewStyleNum FROM %s WHERE id='%s'", $prefix."styles", $value);
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

							$sql = sprintf("INSERT INTO %s (
							flightTable,
							flightNumber,
							flightEntryID,
							flightRound
							) VALUES (%s, %s, %s, %s)",
								$prefix."judging_flights",
								GetSQLValueString($row_table['id'], "text"),
								GetSQLValueString("1", "text"),
								GetSQLValueString($row_entries['id'], "text"),
								GetSQLValueString($row_fl_round['flightRound'], "text")
							);

							mysqli_real_escape_string($connection,$sql);
							$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
							if (!$result) $error_count += 1;

						}

					} while($row_entries = mysqli_fetch_assoc($entries));

					$updated_table_styles[] = $value;
				}

			} // end foreach (array_unique($a) as $value)

			if (empty($updated_table_styles)) {

				$sql = sprintf("DELETE FROM %s WHERE id='%s'",$prefix."judging_tables", $row_table['id']);
				mysqli_real_escape_string($connection,$sql);
				$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
				if (!$result) $error_count += 1;

				$sql = sprintf("DELETE FROM %s WHERE assignTable='%s'",$prefix."judging_assignments", $row_table['id']);
				mysqli_real_escape_string($connection,$sql);
				$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
				if (!$result) $error_count += 1;

				$sql = sprintf("DELETE FROM %s WHERE flightTable='%s'",$prefix."judging_flights", $row_table['id']);
				mysqli_real_escape_string($connection,$sql);
				$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
				if (!$result) $error_count += 1;
				
			}

			// If at least one style, update the table's styles
			else {

				$new_table_styles = implode(",", $updated_table_styles);
				$sql = sprintf("UPDATE %s SET tableStyles=%s WHERE id=%s",
							$prefix."judging_tables",
							GetSQLValueString($new_table_styles, "text"),
							GetSQLValueString($row_table['id'], "text")
						);
				mysqli_real_escape_string($connection,$sql);
				$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
				if (!$result) $error_count += 1;

			}

		} while($row_table = mysqli_fetch_assoc($table));

		$sql = sprintf("UPDATE `%s` SET flightPlanning='1'", $prefix."judging_flights");
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
		if (!$result) $error_count += 1;

		$sql = sprintf("UPDATE `%s` SET assignPlanning='1'", $prefix."judging_assignments");
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
		if (!$result) $error_count += 1;

	} // end if ($row_flight_entries['count'] > 0)

	// Set the tablePlanning session variable to true (1)
	$_SESSION['tablePlanning'] = 1;

	// If error count is zero, change $status from fail (0) to success (1)
	if ($error_count == 0) $status = 1;
	else $error_type = 3; // SQL error

} // end if ($section == "enable-planning")

/*
 * Check all records in the judging_flights DB 
 * table to verify if each relational record
 * has been marked as received in the brewing 
 * DB table. If so, retain in judging_flights. 
 * If not, delete.
 * Also convert all records in the
 * judging_assignments table to 0 (production).
 */

if ($section == "enable-competition") {

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
				$sql = sprintf("DELETE FROM `%s` WHERE flightEntryID='%s'",$prefix."judging_flights",$value);
				mysqli_real_escape_string($connection,$sql);
				$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
				if (!$result) $error_count += 1;
				//echo $sql."<br>";
			}

		}

		// Update all records left to production (0)
		$sql = sprintf("UPDATE `%s` SET flightPlanning='0'", $prefix."judging_flights");
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

		// Loop through the tables and their styles made in planning mode
		$query_table = sprintf("SELECT id,tableStyles,tableLocation FROM %s", $prefix."judging_tables");
		$table = mysqli_query($connection,$query_table) or die (mysqli_error($connection));
		$row_table = mysqli_fetch_assoc($table);

		// --------- Update Table Styles ------

		do {

			$a = explode(",",$row_table['tableStyles']);
			$updated_table_styles = array();

			foreach (array_unique($a) as $value) {

				$query_styles = sprintf("SELECT brewStyleGroup, brewStyleNum FROM %s WHERE id='%s'", $prefix."styles", $value);
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

				$sql = sprintf("DELETE FROM %s WHERE id='%s'",$prefix."judging_tables", $row_table['id']);
				mysqli_real_escape_string($connection,$sql);
				$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

				$sql = sprintf("DELETE FROM %s WHERE assignTable='%s'",$prefix."judging_assignments", $row_table['id']);
				mysqli_real_escape_string($connection,$sql);
				$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

				$sql = sprintf("DELETE FROM %s WHERE flightTable='%s'",$prefix."judging_flights", $row_table['id']);
				mysqli_real_escape_string($connection,$sql);
				$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
				
			}

			// If at least one style, update the table's styles
			else {

				$new_table_styles = implode(",", $updated_table_styles);
				$sql = sprintf("UPDATE %s SET tableStyles=%s WHERE id=%s",
							$prefix."judging_tables",
							GetSQLValueString($new_table_styles, "text"),
							GetSQLValueString($row_table['id'], "text")
						);
				mysqli_real_escape_string($connection,$sql);
				$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
				// echo $sql."<br>";

			}
			
		} while($row_table = mysqli_fetch_assoc($table));	

		$sql = sprintf("UPDATE `%s` SET assignPlanning='0'", $prefix."judging_assignments");
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

	} // end if (!empty($flight_entries_arr))

	// If the $flight_entries_arr is empty, simply truncate the three associated 
	// db tables: judging_assignments, judging_flights, and judging_tables
	else {
		
		$sql = sprintf("TRUNCATE `%s`", $prefix."judging_assignments");
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

		$sql = sprintf("TRUNCATE `%s`", $prefix."judging_flights");
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

		$sql = sprintf("TRUNCATE `%s`", $prefix."judging_tables");
		mysqli_real_escape_string($connection,$sql);
		$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

	}
	
	// Set the tablePlanning session variable to false (0)
	$_SESSION['tablePlanning'] = 0;

	// If error count is zero, change $status from fail (0) to success (1)
	if ($error_count == 0) $status = 1;
	else $error_type = 3; // SQL error

}

$return_json = array(
	"status" => "$status",
	"error_count" => "$error_count",
	"error_type" => "$error_type"
);

// Return the json
echo json_encode($return_json);
?>