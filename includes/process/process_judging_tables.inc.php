<?php
/*
 * Module:      process_judging_tables.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "judging_tables" table
 *              Adds/moves/deletes corresponding entries to the judging_flights table
 */

/*
if (HOSTED) $styles_db_table = "bcoem_shared_styles";
else
*/
$styles_db_table = $prefix."styles";

if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

	if ($_SESSION['jPrefsTablePlanning'] == 1) $flightPlanning = 1; 
	else $flightPlanning = 0;

	include (INCLUDES.'process/process_judging_flight_check.inc.php');

	// Instantiate HTMLPurifier
	require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
	$config_html_purifier = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config_html_purifier);

	if ($_POST['tableStyles'] != "") $tableStyles = implode(",",$_POST['tableStyles']); 
	else $tableStyles = $_POST['tableStyles'];
	$tableStyles = sterilize($tableStyles);

	$tableName = "";
	if (isset($_POST['tableName'])) {
		$tableName = $purifier->purify($_POST['tableName']);
		$tableName = sterilize($tableName);
	}

	$tableNumber = sterilize($_POST['tableNumber']);
	$tableLocation = sterilize($_POST['tableLocation']);
	$tableEntryLimit = sterilize($_POST['tableEntryLimit']);

	if ($action == "add") {

		$update_table = $prefix."judging_tables";
		$data = array(
			'tableName' => blank_to_null($tableName),
			'tableStyles' => blank_to_null($tableStyles),
			'tableNumber' => blank_to_null($tableNumber),
			'tableLocation' => blank_to_null($tableLocation),
			'tableEntryLimit' => blank_to_null($tableEntryLimit)
		);

		$result = $db_conn->insert ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		$query_table = "SELECT id,tableLocation,tableEntryLimit FROM $judging_tables_db_table ORDER BY id DESC LIMIT 1";
		$table = mysqli_query($connection,$query_table) or die (mysqli_error($connection));
		$row_table = mysqli_fetch_assoc($table);

		$query_table_rounds = sprintf("SELECT judgingRounds FROM $judging_locations_db_table WHERE id='%s'", $row_table['tableLocation']);
		$table_rounds = mysqli_query($connection,$query_table_rounds) or die (mysqli_error($connection));
		$row_table_rounds = mysqli_fetch_assoc($table_rounds);
		if ($row_table_rounds['judgingRounds'] == 1) $rounds = "1"; else $rounds = "";

		$a = explode(",",$tableStyles);

		if ((!empty($tableEntryLimit)) && (!empty($tableStyles))) {

			// Call established function to get total entry count of the 
			// table's defined styles.
			$total_table_entries = get_table_info(1,"count_total",$id,"current","default",$base_url);

			// If the total entries for that table are at or beyond limit,
			// designate each style at the table as "at limit" (true) in the styles
			// DB table.
			if ($total_table_entries >= $tableEntryLimit) {

				foreach (array_unique($a) as $value) {

					$update_table = $prefix."styles";
					$data = array(
						'brewStyleAtLimit' => 1
					);
					$db_conn->where ('id', $value);
					$result = $db_conn->update ($update_table, $data);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

				} // end foreach
			
			} // end if ($row_table_entry_limits['tableEntryLimit'] >= $total_table_entries)

			// If the total entries for that table is BELOW the limit,
			// designate each style at the table as "available" (false) 
			// in the styles DB table.
			if ($total_table_entries < $tableEntryLimit) {

				foreach (array_unique($a) as $value) {

					$update_table = $prefix."styles";
					$data = array(
						'brewStyleAtLimit' => 0
					);
					$db_conn->where ('id', $value);
					$result = $db_conn->update ($update_table, $data);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

				} // end foreach
			
			} // end if ($row_table_entry_limits['tableEntryLimit'] < $total_table_entries)

		} // end if (!empty($tableEntryLimit))

		foreach (array_unique($a) as $value) {

			if ($_SESSION['prefsStyleSet'] != "BA") {
				$query_styles = sprintf("SELECT brewStyleGroup, brewStyleNum FROM %s WHERE id='%s';", $styles_db_table, $value);
				$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
				$row_styles = mysqli_fetch_assoc($styles);
			}

			if ($_SESSION['jPrefsTablePlanning'] == 1) {

				if ($_SESSION['prefsStyleSet'] == "BA") $query_entries = sprintf("SELECT id FROM %s WHERE brewSubCategory='%s'", $brewing_db_table, $value);
				else $query_entries = sprintf("SELECT id FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s'", $brewing_db_table, $row_styles['brewStyleGroup'],$row_styles['brewStyleNum']);

			}

			else {

				if ($_SESSION['prefsStyleSet'] == "BA") $query_entries = sprintf("SELECT id FROM %s WHERE brewSubCategory='%s' AND brewReceived='1'", $brewing_db_table, $value);
				else $query_entries = sprintf("SELECT id FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s' AND brewReceived='1'", $brewing_db_table, $row_styles['brewStyleGroup'],$row_styles['brewStyleNum']);

			}

			$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
			$row_entries = mysqli_fetch_assoc($entries);

			do {

				$update_table = $prefix."judging_scores";
				$data = array('scoreTable' => $row_table['id']);
				$db_conn->where ('eid', $row_entries['id']);
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

				// Check if entry is already in the judging_flights table
				$query_empty_count = sprintf("SELECT * FROM $judging_flights_db_table WHERE flightEntryID='%s'",$row_entries['id']);
				$empty_count = mysqli_query($connection,$query_empty_count) or die (mysqli_error($connection));
				$row_empty_count = mysqli_fetch_assoc($empty_count);
				$totalRows_empty_count = mysqli_num_rows($empty_count);

				// if so, update the record with the new judging_table id
				if ($totalRows_empty_count > 0) {

					$update_table = $prefix."judging_flights";
					$data = array('flightTable' => $row_table['id']);
					$db_conn->where ('flightEntryID', $row_entries['id']);
					$result = $db_conn->update ($update_table, $data);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

				}

				// if not, add a new record to the judging_flights table
				else {

					$update_table = $prefix."judging_flights";
					$data = array(
						'flightTable' => $row_table['id'],
						'flightNumber' => 1,
						'flightEntryID' => $row_entries['id'],
						'flightRound' => $rounds
					);
					$result = $db_conn->insert ($update_table, $data);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

				} // end else

			} while ($row_entries = mysqli_fetch_assoc($entries));

			// Finally change the flightPlanning status for all records
			$update_table = $prefix."judging_flights";
			$data = array('flightPlanning' => blank_to_null($flightPlanning));
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

		}

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		if (empty($_POST['tableStyles'])) $insertGoTo = $insertGoTo;
		elseif (($_POST['return-to-add-table'] == 1) && (!empty($_POST['tableStyles']))) $insertGoTo = $base_url."index.php?section=admin&go=judging_tables&action=add&msg=1";
		elseif (($_POST['return-to-add-table'] == 0) && (!empty($_POST['tableStyles']))) $insertGoTo = $base_url."index.php?section=admin&go=judging_tables&msg=1";
		else $insertGoTo = $_POST['relocate']."&msg=13";
		if ($errors) $insertGoTo = $_POST['relocate']."&msg=3";

		$insertGoTo = prep_redirect_link($insertGoTo);
		$redirect_go_to = sprintf("Location: %s", $insertGoTo);

	} // end if ($action == "add")

	if ($action == "edit") {

		// Check to see if table styles are different.
		$query_table = sprintf("SELECT id,tableStyles FROM %s WHERE id='%s'", $judging_tables_db_table, $id);
		$table = mysqli_query($connection,$query_table) or die (mysqli_error($connection));
		$row_table = mysqli_fetch_assoc($table);

		$a = explode(",",$tableStyles);

		// If so...
		if ($tableStyles != $row_table['tableStyles']) {

			// Delete all associated scores
			$update_table = $prefix."judging_scores";
			$db_conn->where ('scoreTable', $id);
			$result = $db_conn->delete ($update_table);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			// Delete all entries in flights table that were previously assigned
			// Fool-proof way to avoid breaking system when adding new tables

			$update_table = $prefix."judging_flights";
			$db_conn->where ('flightTable', $id);
			$result = $db_conn->delete ($update_table);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			// Add back in
			$query_table = sprintf("SELECT id,tableLocation FROM %s WHERE id=%s", $judging_tables_db_table, $id);
			$table = mysqli_query($connection,$query_table) or die (mysqli_error($connection));
			$row_table = mysqli_fetch_assoc($table);

			$query_table_rounds = sprintf("SELECT judgingRounds FROM %s WHERE id='%s'", $judging_locations_db_table, $row_table['tableLocation']);
			$table_rounds = mysqli_query($connection,$query_table_rounds) or die (mysqli_error($connection));
			$row_table_rounds = mysqli_fetch_assoc($table_rounds);
			if ($row_table_rounds['judgingRounds'] == 1) $rounds = "1"; else $rounds = "";

			foreach (array_unique($a) as $value) {

				if ($_SESSION['prefsStyleSet'] != "BA") {
					
					$query_styles = sprintf("SELECT brewStyleGroup, brewStyleNum FROM %s WHERE id='%s'", $styles_db_table, $value);
					$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
					$row_styles = mysqli_fetch_assoc($styles);
				
				}

				if ($_SESSION['jPrefsTablePlanning'] == 1) $query_entries = sprintf("SELECT id FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s'", $brewing_db_table, $row_styles['brewStyleGroup'],$row_styles['brewStyleNum']);
				else $query_entries = sprintf("SELECT id FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s' AND brewReceived='1'", $brewing_db_table, $row_styles['brewStyleGroup'],$row_styles['brewStyleNum']);
				$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
				$row_entries = mysqli_fetch_assoc($entries);

				do {

					$update_table = $prefix."judging_scores";
					$data = array('scoreTable' => $row_table['id']);
					$db_conn->where ('eid', $row_entries['id']);
					$result = $db_conn->update ($update_table, $data);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

					// Check if entry is already in the judging_flights table
					$query_empty_count = sprintf("SELECT id FROM %s WHERE flightEntryID='%s'", $judging_flights_db_table, $row_entries['id']);
					$empty_count = mysqli_query($connection,$query_empty_count) or die (mysqli_error($connection));
					$row_empty_count = mysqli_fetch_assoc($empty_count);
					$totalRows_empty_count = mysqli_num_rows($empty_count);

					// if so, update the record with the new judging_table id
					if ($totalRows_empty_count > 0) {

						$update_table = $prefix."judging_flights";
						$data = array('flightTable' => $id);
						$db_conn->where ('flightEntryID', $row_entries['id']);
						$result = $db_conn->update ($update_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					}

					// if not, add a new record to the judging_flights table
					else {

						$update_table = $prefix."judging_flights";
						$data = array(
							'flightTable' => $id,
							'flightNumber' => 1,
							'flightEntryID' => $row_entries['id'],
							'flightRound' => blank_to_null($rounds)
						);
						$result = $db_conn->insert ($update_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					}

				} while ($row_entries = mysqli_fetch_assoc($entries));

				// Finally change the flightPlanning status for all records
				$update_table = $prefix."judging_flights";
				$data = array('flightPlanning' => blank_to_null($flightPlanning));
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

		} // End if ($tableStyles != $row_table['tableStyles'])

		$update_table = $prefix."judging_tables";
		$data = array(
			'tableName' => blank_to_null($tableName),
			'tableStyles' => blank_to_null($tableStyles),
			'tableNumber' => blank_to_null($tableNumber),
			'tableLocation' => blank_to_null($tableLocation),
			'tableEntryLimit' => blank_to_null($tableEntryLimit)
		);
		$db_conn->where ('id', $id);
		$result = $db_conn->update ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		if ((!empty($tableEntryLimit)) && (!empty($tableStyles))) {

			// Call established function to get total entry count of the 
			// table's defined styles.
			$total_table_entries = get_table_info(1,"count_total",$id,"current","default",$base_url);

			// If the total entries for that table are at or beyond limit,
			// designate each style at the table as "at limit" (true) in the styles
			// DB table.
			if ($total_table_entries >= $tableEntryLimit) {

				foreach (array_unique($a) as $value) {

					$update_table = $prefix."styles";
					$data = array(
						'brewStyleAtLimit' => 1
					);
					$db_conn->where ('id', $value);
					$result = $db_conn->update ($update_table, $data);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

				} // end foreach
			
			} // end if ($row_table_entry_limits['tableEntryLimit'] >= $total_table_entries)

			// If the total entries for that table is BELOW the limit,
			// designate each style at the table as "available" (false) 
			// in the styles DB table.
			if ($total_table_entries < $tableEntryLimit) {

				foreach (array_unique($a) as $value) {

					$update_table = $prefix."styles";
					$data = array(
						'brewStyleAtLimit' => 0
					);
					$db_conn->where ('id', $value);
					$result = $db_conn->update ($update_table, $data);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

				} // end foreach
			
			} // end if ($row_table_entry_limits['tableEntryLimit'] < $total_table_entries)

		} // end if (!empty($tableEntryLimit))

		// Check rows for "blank" flightTables in the judging_flights table
		$query_empty_count = "SELECT flightEntryID FROM $judging_flights_db_table WHERE flightTable='' OR flightTable IS NULL";
		$empty_count = mysqli_query($connection,$query_empty_count) or die (mysqli_error($connection));
		$row_empty_count = mysqli_fetch_assoc($empty_count);
		$totalRows_empty_count = mysqli_num_rows($empty_count);

		// If so, match up the flightEntryID with the id in the brewing table,
		// Determine its style, and assign the row to the proper table

		if ($totalRows_empty_count > 0) {

			do { $z[] = $row_empty_count['flightEntryID']; } while ($row_empty_count = mysqli_fetch_assoc($empty_count));

			foreach ($z as $id) {

				$query_entry = sprintf("SELECT brewCategorySort,brewSubCategory FROM $brewing_db_table WHERE id='%s'", $id);
				$entry = mysqli_query($connection,$query_entry) or die (mysqli_error($connection));
				$row_entry = mysqli_fetch_assoc($entry);

				if ($_SESSION['prefsStyleSet'] != "BA") {

					if ($_SESSION['prefsStyleSet'] == "BJCP2025") {
					    $first_character = mb_substr($row_entry['brewCategorySort'], 0, 1);
					    if ($first_character == "C") $chosen_style_set = "BJCP2025";
					    else $chosen_style_set = "BJCP2021";
					}

					else $chosen_style_set = $_SESSION['prefsStyleSet'];

					$query_style = sprintf("SELECT id FROM %s WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s';", $styles_db_table, $chosen_style_set, $row_entry['brewCategorySort'], $row_entry['brewSubCategory']);
					$style = mysqli_query($connection,$query_style) or die (mysqli_error($connection));
					$row_style = mysqli_fetch_assoc($style);

					$style_id = $row_style['id'];

				}

				else $style_id = $row_entry['brewSubCategory'];

				$query_table_styles = "SELECT id,tableStyles FROM $judging_tables_db_table";
				$tableStyles = mysqli_query($connection,$query_table_styles) or die (mysqli_error($connection));
				$row_table_styles = mysqli_fetch_assoc($tableStyles);

				do {
					$style_array = explode(",",$row_table_styles['tableStyles']);

					if (in_array($style_id,$style_array)) {

						$update_table = $prefix."judging_flights";
						$data = array('flightTable' => $row_table_styles['id']);
						$db_conn->where ('flightEntryID', $id);
						$result = $db_conn->update ($update_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					}

				} while ($row_table_styles = mysqli_fetch_assoc($tableStyles));

			}

		} // end if ($totalRows_empty_count > 0)

		// Remove all flight rows if unassigning ALL present table styles
		if (empty($_POST['tableStyles'])) {

			do { $a[] = $row_flight_count['id']; } while ($row_flight_count = mysqli_fetch_assoc($flight_count));

			foreach ($a as $id) {

				$update_table = $prefix."judging_flights";
				$db_conn->where ('id', $id);
				$result = $db_conn->delete ($update_table);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			} // end foreach

		} // end if (empty($_POST['tableStyles']))

		$update_table = $prefix."judging_flights";
		$data = array('flightPlanning' => blank_to_null($flightPlanning));
		$result = $db_conn->update ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		$updateGoTo = $base_url."index.php?section=admin&go=judging_tables&msg=2";
		if ($errors) $updateGoTo = $base_url."index.php?section=admin&go=judging_tables&msg=3";
		$updateGoTo = prep_redirect_link($updateGoTo);
		$redirect_go_to = sprintf("Location: %s", $updateGoTo);

	} // end if ($action == "edit")

} else {

	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);

}
?>