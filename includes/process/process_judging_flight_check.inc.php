<?php
if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))) {

	$query_check_received = sprintf("SELECT id,brewCategorySort,brewSubCategory FROM %s WHERE brewReceived='1'", $prefix."brewing");
	$check_received = mysqli_query($connection,$query_check_received) or die (mysqli_error($connection));
	$row_check_received = mysqli_fetch_assoc($check_received);
	$totalRows_check_received = mysqli_num_rows($check_received);

	$query_check_flights = sprintf("SELECT flightTable,flightEntryID FROM %s", $prefix."judging_flights");
	$check_flights = mysqli_query($connection,$query_check_flights) or die (mysqli_error($connection));
	$row_check_flights = mysqli_fetch_assoc($check_flights);
	$totalRows_check_flights = mysqli_num_rows($check_flights);

	$query_check_empty = sprintf("SELECT * FROM %s WHERE flightTable IS NULL", $prefix."judging_flights");
	$check_empty = mysqli_query($connection,$query_check_empty) or die (mysqli_error($connection));
	$row_check_empty = mysqli_fetch_assoc($check_empty);
	$totalRows_check_empty = mysqli_num_rows($check_empty);

	if ($totalRows_check_empty > 0) {
		do { $empty_array[] = $row_check_empty['flightEntryID']; } while ($row_check_empty = mysqli_fetch_assoc($check_empty));
	}

	// Put all of the flightEntryIDs into an array
	do { $flight_array[] = $row_check_flights['flightEntryID']; } while ($row_check_flights = mysqli_fetch_assoc($check_flights));


	do {
		//
		if ($totalRows_check_empty > 0) {

			if (in_array($row_check_received['id'],$empty_array)) {

				// First, get the id of the entry's style category/subcategory
				$query_style = sprintf("SELECT id FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s'",$styles_db_table,$_SESSION['prefsStyleSet'],$row_check_received['brewCategorySort'],$row_check_received['brewSubCategory']);
				$style = mysqli_query($connection,$query_style) or die (mysqli_error($connection));
				$row_style = mysqli_fetch_assoc($style);
				//echo $query_style."<br>";

				// Then, get the id of the user defined judging table
				$query_table = sprintf("SELECT id FROM %s WHERE FIND_IN_SET('%s',tableStyles) > 0",$judging_tables_db_table,$row_style['id']);
				$table = mysqli_query($connection,$query_table) or die (mysqli_error($connection));
				$row_table = mysqli_fetch_assoc($table);
				$totalRows_table = mysqli_num_rows($table);
				//echo $query_table."<br>";

				if ($totalRows_table > 0) {
					// Finally, update the table information into the judging_flights DB table
					// IF there is a judging table with the entry's subcategory
					$updateSQL = sprintf("UPDATE $judging_flights_db_table SET flightTable=%s WHERE flightEntryID=%s",
							   GetSQLValueString($row_table['id'], "int"),
							   GetSQLValueString($row_check_received['id'], "int")
							   );
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
					//echo $updateSQL."<br>";
				}

			}

		}

		// Loop through the entries that have been received
		// Assign any that are not in the judging_flights table to the appropriate user defined judging table

		if (!in_array($row_check_received['id'],$flight_array)) {

			// First, get the id of the entry's style category/subcategory
			$query_style = sprintf("SELECT id FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s'",$styles_db_table,$_SESSION['prefsStyleSet'],$row_check_received['brewCategorySort'],$row_check_received['brewSubCategory']);
			$style = mysqli_query($connection,$query_style) or die (mysqli_error($connection));
			$row_style = mysqli_fetch_assoc($style);
			//echo $query_style."<br>";

			// Then, get the id of the user defined judging table
			$query_table = sprintf("SELECT id FROM %s WHERE FIND_IN_SET('%s',tableStyles) > 0",$judging_tables_db_table,$row_style['id']);
			$table = mysqli_query($connection,$query_table) or die (mysqli_error($connection));
			$row_table = mysqli_fetch_assoc($table);
			$totalRows_table = mysqli_num_rows($table);
			//echo $query_table."<br>";

			if ($totalRows_table > 0) {
				// Finally, insert the information into the judging_flights DB table
				// IF there is a judging table with the entry's subcategory
				$updateSQL = sprintf("INSERT INTO $judging_flights_db_table (flightTable, flightNumber, flightEntryID, flightRound) VALUES (%s, %s, %s, %s)",
						   GetSQLValueString($row_table['id'], "int"),
						   GetSQLValueString("1", "int"),
						   GetSQLValueString($row_check_received['id'], "int"),
						   GetSQLValueString("1", "int")
						   );
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				//echo $updateSQL."<br>";
			}
		}

	} while ($row_check_received = mysqli_fetch_assoc($check_received));

	if ($go == "judging_tables") {
		$updateGoTo = $base_url."index.php?section=admin&go=judging_tables&msg=4";
		$redirect_go_to = sprintf("Location: %s", $updateGoTo);
	}
	if ($go == "admin_dashboard") {
		$updateGoTo = $base_url."index.php?section=admin&msg=4";
		$redirect_go_to = sprintf("Location: %s", $updateGoTo);
	}
	if ($go == "hidden") {
		$updateGoTo = $base_url."index.php?section=admin&go=judging_tables";
		$redirect_go_to = sprintf("Location: %s", $updateGoTo);
	}

} else {
	$redirect_go_to = sprintf("Location: %s", $base_url."index.php?msg=98");
}
?>