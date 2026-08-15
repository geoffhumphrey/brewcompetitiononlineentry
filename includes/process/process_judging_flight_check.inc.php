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

	$db_conn->where('brewReceived', '1');
	$rows_check_received = $db_conn->get($prefix."brewing", null, "id,brewCategorySort,brewSubCategory");
	$totalRows_check_received = $db_conn->count;

	$rows_check_flights = $db_conn->get($prefix."judging_flights", null, "flightTable,flightEntryID");
	$totalRows_check_flights = $db_conn->count;

	$db_conn->where('flightTable', NULL, 'IS');
	$rows_check_empty = $db_conn->get($prefix."judging_flights");
	$totalRows_check_empty = $db_conn->count;

	if ($totalRows_check_empty > 0) {
		foreach ($rows_check_empty as $row_check_empty) {
			$empty_array[] = $row_check_empty['flightEntryID'];
		}
	}

	// Put all of the flightEntryIDs into an array
	foreach ($rows_check_flights as $row_check_flights) {
		$flight_array[] = $row_check_flights['flightEntryID'];
	}

	foreach ($rows_check_received as $row_check_received) {

		if ($totalRows_check_empty > 0) {

			if (in_array($row_check_received['id'],$empty_array)) {

				// First, get the id of the entry's style category/subcategory
				if ($_SESSION['prefsStyleSet'] == "BJCP2025") {
				    $first_character = mb_substr($row_check_received['brewCategorySort'], 0, 1);
				    if ($first_character == "C") $chosen_style_set = "BJCP2025";
				    else $chosen_style_set = "BJCP2021";
				}

				else $chosen_style_set = $_SESSION['prefsStyleSet'];

				$query_style = "SELECT id FROM ".$styles_db_table." WHERE (brewStyleVersion=? OR brewStyleOwn='custom') AND brewStyleGroup=? AND brewStyleNum=?";
				$row_style = $db_conn->rawQueryOne($query_style, array($chosen_style_set, $row_check_received['brewCategorySort'], $row_check_received['brewSubCategory']));

				// Then, get the id of the user defined judging table
				$query_table = "SELECT id FROM ".$judging_tables_db_table." WHERE FIND_IN_SET(?,tableStyles) > 0";
				$row_table = $db_conn->rawQueryOne($query_table, array($row_style['id']));
				$totalRows_table = $db_conn->count;
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
			if ($_SESSION['prefsStyleSet'] == "BJCP2025") {
			    $first_character = mb_substr($row_check_received['brewCategorySort'], 0, 1);
			    if ($first_character == "C") $chosen_style_set = "BJCP2025";
			    else $chosen_style_set = "BJCP2021";
			}

			else $chosen_style_set = $_SESSION['prefsStyleSet'];
			
			$query_style = "SELECT id FROM ".$styles_db_table." WHERE (brewStyleVersion=? OR brewStyleOwn='custom') AND brewStyleGroup=? AND brewStyleNum=?";
			$row_style = $db_conn->rawQueryOne($query_style, array($chosen_style_set, $row_check_received['brewCategorySort'], $row_check_received['brewSubCategory']));

			// Then, get the id of the user defined judging table
			$query_table = "SELECT id FROM ".$judging_tables_db_table." WHERE FIND_IN_SET(?,tableStyles) > 0";
			$row_table = $db_conn->rawQueryOne($query_table, array($row_style['id']));
			$totalRows_table = $db_conn->count;

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

	}

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