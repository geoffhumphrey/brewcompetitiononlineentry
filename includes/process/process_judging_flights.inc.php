<?php
/*
 * Module:      process_judging_flights.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "judging_flights" table
 */
if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))) {

	 if ($action == "add") {
		foreach($_POST['id'] as $id)	{
			$flight_number = ltrim($_POST['flightNumber'.$id],"flight");
			$insertSQL = sprintf("INSERT INTO $judging_flights_db_table (
			flightTable,
			flightNumber,
			flightEntryID,
			flightRound
			) VALUES (%s, %s, %s)",
						   GetSQLValueString($_POST['flightTable'], "text"),
						   GetSQLValueString($flight_number, "text"),
						   GetSQLValueString("1", "text"),
						   GetSQLValueString($id, "text")
						   );

			//echo $insertSQL."<br>";
			mysqli_real_escape_string($connection,$insertSQL);
			$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));
		}

		$pattern = array('\'', '"');
		$insertGoTo = str_replace($pattern, "", $insertGoTo);
		$redirect_go_to = sprintf("Location: %s", stripslashes($insertGoTo));
	}

	if ($action == "edit") {

		foreach($_POST['id'] as $id)	{
			$flight_number = ltrim($_POST['flightNumber'.$id],"flight");

			if ($id <= "999999") {
				$updateSQL = sprintf("UPDATE $judging_flights_db_table SET
				flightTable=%s,
				flightNumber=%s
				WHERE id=%s",
						   GetSQLValueString($_POST['flightTable'], "text"),
						   GetSQLValueString($flight_number, "text"),
						   GetSQLValueString($id, "text")
						   );

				//echo $updateSQL."<br>";
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			}
			if ($id > "999999"){
				$insertSQL = sprintf("INSERT INTO $judging_flights_db_table (
				flightTable,
				flightNumber,
				flightEntryID
				) VALUES (%s, %s, %s)",
						   GetSQLValueString($_POST['flightTable'], "text"),
						   GetSQLValueString($flight_number, "text"),
						   GetSQLValueString($_POST['flightEntryID'.$id], "text")
						   );

				//echo $insertSQL."<br>";
				mysqli_real_escape_string($connection,$insertSQL);
				$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));
			}
		}
		$pattern = array('\'', '"');
		$updateGoTo = str_replace($pattern, "", $updateGoTo);
		$redirect_go_to = sprintf("Location: %s", stripslashes($updateGoTo));
	}

	if ($action == "assign") {
		foreach (array_unique($_POST['id']) as $a) {

		// Check to see if round has changed for the table/flight.
		if ($_POST['flightRound'.$a] != $_POST['flightRoundPrevious'.$a]) {

		// If so, delete all judging/steward assignments for the "old" round
			$query_assignments = sprintf("SELECT id FROM $judging_assignments_db_table WHERE assignTable='%s' AND assignFlight='%s' AND assignRound='%s' ORDER BY id", $_POST['flightTable'.$a],$_POST['flightNumber'.$a],$_POST['flightRoundPrevious'.$a]);
			$assignments = mysqli_query($connection,$query_assignments) or die (mysqli_error($connection));
			$row_assignments = mysqli_fetch_assoc($assignments);
			$totalRows_assignments = mysqli_num_rows($assignments);
			//echo $query_assignments."<br>";

			if ($totalRows_assignments > 0) {
				do {
					$deleteSQL = sprintf("DELETE FROM $judging_assignments_db_table WHERE id='%s'", $row_assignments['id']);
					$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
					//echo $deleteAssignment.";<br>";
				} while ($row_assignments = mysqli_fetch_assoc($assignments));
			}

			// Change the rounds for all affected table/flight assignments.

			$query_flights = sprintf("SELECT id FROM $judging_flights_db_table WHERE flightTable='%s' AND flightNumber='%s' ORDER BY id", $_POST['flightTable'.$a],$_POST['flightNumber'.$a]);
			$flights = mysqli_query($connection,$query_flights) or die (mysqli_error($connection));
			$row_flights = mysqli_fetch_assoc($flights);
			//echo $query_flights."<br>";

			do {
				$updateSQL = sprintf("UPDATE $judging_flights_db_table SET flightRound=%s WHERE id=%s",
				GetSQLValueString($_POST['flightRound'.$a], "text"),
				GetSQLValueString($row_flights['id'], "int")
				);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				//echo $updateSQL.";<br>";
				} while ($row_flights = mysqli_fetch_assoc($flights));
			}
		}
		$pattern = array('\'', '"');
		$updateGoTo = str_replace($pattern, "", $updateGoTo);
		$redirect_go_to = sprintf("Location: %s", stripslashes($updateGoTo));
	}
} else {
	$redirect_go_to = sprintf("Location: %s", $base_url."index.php?msg=98");
}

?>