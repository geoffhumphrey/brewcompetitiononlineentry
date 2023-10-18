<?php
/*
if (HOSTED) $styles_db_table = "bcoem_shared_styles";
else
*/
$styles_db_table = $prefix."styles";

if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

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
		do { 
			$empty_array[] = $row_check_empty['flightEntryID']; 
		} while ($row_check_empty = mysqli_fetch_assoc($check_empty));
	}

	// Put all of the flightEntryIDs into an array
	do { 
		$flight_array[] = $row_check_flights['flightEntryID']; 
	} while ($row_check_flights = mysqli_fetch_assoc($check_flights));

	do {
		
		if ($totalRows_check_empty > 0) {

			if (in_array($row_check_received['id'],$empty_array)) {

				// First, get the id of the entry's style category/subcategory
				/*
				if (HOSTED) $query_style = sprintf("SELECT id FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s' UNION ALL SELECT id FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s';", $styles_db_table, $_SESSION['prefsStyleSet'], $row_check_received['brewCategorySort'], $row_check_received['brewSubCategory'], $prefix."styles", $_SESSION['prefsStyleSet'], $row_check_received['brewCategorySort'], $row_check_received['brewSubCategory']);
				else 
				*/
				$query_style = sprintf("SELECT id FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s';", $styles_db_table, $_SESSION['prefsStyleSet'], $row_check_received['brewCategorySort'], $row_check_received['brewSubCategory']);
				$style = mysqli_query($connection,$query_style) or die (mysqli_error($connection));
				$row_style = mysqli_fetch_assoc($style);

				// Then, get the id of the user defined judging table
				$query_table = sprintf("SELECT id FROM %s WHERE FIND_IN_SET('%s',tableStyles) > 0",$judging_tables_db_table,$row_style['id']);
				$table = mysqli_query($connection,$query_table) or die (mysqli_error($connection));
				$row_table = mysqli_fetch_assoc($table);
				$totalRows_table = mysqli_num_rows($table);
				//echo $query_table."<br>";

				if ($totalRows_table > 0) {
					
					// Finally, update the table information into the judging_flights DB table
					// IF there is a judging table with the entry's subcategory

					$update_table = $prefix."judging_flights";
					$data = array('flightTable' => $row_table['id']);
					$db_conn->where ('flightEntryID', $row_check_received['id']);
					$result = $db_conn->update ($update_table, $data);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

				} // end if ($totalRows_table > 0)

			} // end if (in_array($row_check_received['id'],$empty_array))

		} // end if ($totalRows_check_empty > 0)

		// Loop through the entries that have been received
		// Assign any that are not in the judging_flights table to the appropriate user defined judging table

		if (!in_array($row_check_received['id'],$flight_array)) {

			// First, get the id of the entry's style category/subcategory
			/*
			if (HOSTED) $query_style = sprintf("SELECT id FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s' UNION ALL SELECT id FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s';", $styles_db_table, $_SESSION['prefsStyleSet'], $row_check_received['brewCategorySort'], $row_check_received['brewSubCategory'], $prefix."styles", $_SESSION['prefsStyleSet'], $row_check_received['brewCategorySort'], $row_check_received['brewSubCategory']);
			else
			*/
			$query_style = sprintf("SELECT id FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s'", $styles_db_table, $_SESSION['prefsStyleSet'], $row_check_received['brewCategorySort'], $row_check_received['brewSubCategory']);
			$style = mysqli_query($connection,$query_style) or die (mysqli_error($connection));
			$row_style = mysqli_fetch_assoc($style);

			// Then, get the id of the user defined judging table
			$query_table = sprintf("SELECT id FROM %s WHERE FIND_IN_SET('%s',tableStyles) > 0",$judging_tables_db_table,$row_style['id']);
			$table = mysqli_query($connection,$query_table) or die (mysqli_error($connection));
			$row_table = mysqli_fetch_assoc($table);
			$totalRows_table = mysqli_num_rows($table);

			if ($totalRows_table > 0) {
				
				// Finally, insert the information into the judging_flights DB table
				// IF there is a judging table with the entry's subcategory
				$update_table = $prefix."judging_flights";
				$data = array(
					'flightTable' => $row_table['id'],
					'flightNumber' => 1,
					'flightEntryID' => $row_check_received['id'],
					'flightRound' => 1
				);
				$result = $db_conn->insert ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			} // end if ($totalRows_table > 0)

		} // end if (!in_array($row_check_received['id'],$flight_array))

	} while ($row_check_received = mysqli_fetch_assoc($check_received));

	if ($go == "judging_tables") {
		$updateGoTo = $base_url."index.php?section=admin&go=judging_tables&msg=4";
		if ($errors) $updateGoTo = $base_url."index.php?section=admin&go=judging_tables&msg=3";
	}

	if ($go == "admin_dashboard") {
		$updateGoTo = $base_url."index.php?section=admin&msg=4";
		if ($errors) $updateGoTo = $base_url."index.php?section=admin&msg=3";
	}

	if ($go == "hidden") {
		$updateGoTo = $base_url."index.php?section=admin&go=judging_tables";
		if ($errors) $updateGoTo = $base_url."index.php?section=admin&go=judging_tables&msg=3";
	}

	if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

	$updateGoTo = prep_redirect_link($updateGoTo);
	$redirect_go_to = sprintf("Location: %s", $updateGoTo);

} else {

	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);

}
?>