<?php
/*
 * Module:      process_judging_tables.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "judging_tables" table
 *              Adds/moves/deletes corresponding entries to the judging_flights table
 */


if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))) {

	include (INCLUDES.'process/process_judging_flight_check.inc.php');

	// Instantiate HTMLPurifier
	require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
	$config_html_purifier = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config_html_purifier);

	if ($_POST['tableStyles'] != "") $table_styles = implode(",",$_POST['tableStyles']); else $table_styles = $_POST['tableStyles'];

	if ($action == "add") {

		$insertSQL = sprintf("INSERT INTO $judging_tables_db_table (
		tableName,
		tableStyles,
		tableNumber,
		tableLocation
		) VALUES (%s, %s, %s, %s)",
						   GetSQLValueString($purifier->purify($_POST['tableName']), "text"),
						   GetSQLValueString(sterilize($table_styles), "text"),
						   GetSQLValueString(sterilize($_POST['tableNumber']), "text"),
						   GetSQLValueString(sterilize($_POST['tableLocation']), "text")
						   );

		mysqli_real_escape_string($connection,$insertSQL);
		$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));

		$query_table = "SELECT id,tableLocation FROM $judging_tables_db_table ORDER BY id DESC LIMIT 1";
		$table = mysqli_query($connection,$query_table) or die (mysqli_error($connection));
		$row_table = mysqli_fetch_assoc($table);

		$query_table_rounds = sprintf("SELECT judgingRounds FROM $judging_locations_db_table WHERE id='%s'", $row_table['tableLocation']);
		$table_rounds = mysqli_query($connection,$query_table_rounds) or die (mysqli_error($connection));
		$row_table_rounds = mysqli_fetch_assoc($table_rounds);
		if ($row_table_rounds['judgingRounds'] == 1) $rounds = "1"; else $rounds = "";

		$a = explode(",",$table_styles);

		foreach (array_unique($a) as $value) {


			if ($_SESSION['prefsStyleSet'] != "BA") {
				$query_styles = sprintf("SELECT brewStyleGroup, brewStyleNum FROM %s WHERE id='%s'", $styles_db_table, $value);
				$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
				$row_styles = mysqli_fetch_assoc($styles);
			}

			if ($_SESSION['prefsStyleSet'] == "BA") $query_entries = sprintf("SELECT id FROM %s WHERE brewSubCategory='%s' AND brewReceived='1'", $brewing_db_table, $value);
			else $query_entries = sprintf("SELECT id FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s' AND brewReceived='1'", $brewing_db_table, $row_styles['brewStyleGroup'],$row_styles['brewStyleNum']);
			$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
			$row_entries = mysqli_fetch_assoc($entries);

			do {
				// Update any scores that have been entered already for the entry with the new table number
				$updateSQL = sprintf("UPDATE $judging_scores_db_table SET scoreTable=%s WHERE eid=%s",
							   GetSQLValueString($row_table['id'], "text"),
							   GetSQLValueString($row_entries['id'], "text"));

				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

				// Check if entry is already in the judging_flights table
				$query_empty_count = sprintf("SELECT * FROM $judging_flights_db_table WHERE flightEntryID='%s'",$row_entries['id']);
				$empty_count = mysqli_query($connection,$query_empty_count) or die (mysqli_error($connection));
				$row_empty_count = mysqli_fetch_assoc($empty_count);
				$totalRows_empty_count = mysqli_num_rows($empty_count);

				// if so, update the record with the new judging_table id
				if ($totalRows_empty_count > 0) {

					$updateSQL = sprintf("UPDATE $judging_flights_db_table SET flightTable=%s WHERE flightEntryID=%s",
								   GetSQLValueString($row_table['id'], "text"),
								   GetSQLValueString($row_entries['id'], "text"));

					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				}

				// if not, add a new record to the judging_flights table
				else {

					$insertSQL = sprintf("INSERT INTO $judging_flights_db_table (
						flightTable,
						flightNumber,
						flightEntryID,
						flightRound
						) VALUES (%s, %s, %s, %s)",
							   GetSQLValueString($row_table['id'], "text"),
							   GetSQLValueString("1", "text"),
							   GetSQLValueString($row_entries['id'], "text"),
							   GetSQLValueString($rounds, "text")
							   );

					mysqli_real_escape_string($connection,$insertSQL);
					$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));

				}

			} while ($row_entries = mysqli_fetch_assoc($entries));

		}

		if ($_POST['tableStyles'] != "") $insertGoTo = $insertGoTo; else $insertGoTo = $insertGoTo = $_POST['relocate']."&msg=13";
		$pattern = array('\'', '"');
		$insertGoTo = str_replace($pattern, "", $insertGoTo);
		$redirect_go_to = sprintf("Location: %s", stripslashes($insertGoTo));

	}

	if ($action == "edit") {


		// Check to see if table styles are different.
		$query_table = sprintf("SELECT id,tableStyles FROM %s WHERE id='%s'", $judging_tables_db_table, $id);
		$table = mysqli_query($connection,$query_table) or die (mysqli_error($connection));
		$row_table = mysqli_fetch_assoc($table);

		// If so...
		if ($table_styles != $row_table['tableStyles']) {

			// Delete all associated scores
			$deleteSQL = sprintf("DELETE FROM %s WHERE scoreTable='%s'", $prefix."judging_scores", $id);
			mysqli_real_escape_string($connection,$deleteSQL);
			$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));

			// Delete all entries in flights table that were previously assigned
			// Fool-proof way to avoid breaking system when adding new tables
			$deleteSQL = sprintf("DELETE FROM $judging_flights_db_table WHERE flightTable='%s'", $id);
			mysqli_real_escape_string($connection,$deleteSQL);
			$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));

			// Add back in
			$query_table = sprintf("SELECT id,tableLocation FROM %s WHERE id=%s", $judging_tables_db_table, $id);
			$table = mysqli_query($connection,$query_table) or die (mysqli_error($connection));
			$row_table = mysqli_fetch_assoc($table);

			$query_table_rounds = sprintf("SELECT judgingRounds FROM %s WHERE id='%s'", $judging_locations_db_table, $row_table['tableLocation']);
			$table_rounds = mysqli_query($connection,$query_table_rounds) or die (mysqli_error($connection));
			$row_table_rounds = mysqli_fetch_assoc($table_rounds);
			if ($row_table_rounds['judgingRounds'] == 1) $rounds = "1"; else $rounds = "";

			$a = explode(",",$table_styles);

			foreach (array_unique($a) as $value) {

				if ($_SESSION['prefsStyleSet'] != "BA") {
					$query_styles = sprintf("SELECT brewStyleGroup, brewStyleNum FROM %s WHERE id='%s'", $styles_db_table, $value);
					$styles = mysqli_query($connection,$query_styles) or die (mysqli_error($connection));
					$row_styles = mysqli_fetch_assoc($styles);
				}

				$query_entries = sprintf("SELECT id FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s' AND brewReceived='1'", $brewing_db_table, $row_styles['brewStyleGroup'],$row_styles['brewStyleNum']);
				$entries = mysqli_query($connection,$query_entries) or die (mysqli_error($connection));
				$row_entries = mysqli_fetch_assoc($entries);

				do {
					// Update any scores that have been entered already for the entry with the new table number
					$updateSQL = sprintf("UPDATE $judging_scores_db_table SET scoreTable=%s WHERE eid=%s",
								   GetSQLValueString($row_table['id'], "text"),
								   GetSQLValueString($row_entries['id'], "text"));

					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

					// Check if entry is already in the judging_flights table
					$query_empty_count = sprintf("SELECT id FROM %s WHERE flightEntryID='%s'", $judging_flights_db_table, $row_entries['id']);
					$empty_count = mysqli_query($connection,$query_empty_count) or die (mysqli_error($connection));
					$row_empty_count = mysqli_fetch_assoc($empty_count);
					$totalRows_empty_count = mysqli_num_rows($empty_count);

					// if so, update the record with the new judging_table id
					if ($totalRows_empty_count > 0) {

						$updateSQL = sprintf("UPDATE $judging_flights_db_table SET flightTable=%s WHERE flightEntryID=%s",
									   GetSQLValueString($id, "text"),
									   GetSQLValueString($row_entries['id'], "text"));

						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
					}

					// if not, add a new record to the judging_flights table
					else {

						$insertSQL = sprintf("INSERT INTO $judging_flights_db_table (
							flightTable,
							flightNumber,
							flightEntryID,
							flightRound
							) VALUES (%s, %s, %s, %s)",
								   GetSQLValueString($id, "text"),
								   GetSQLValueString("1", "text"),
								   GetSQLValueString($row_entries['id'], "text"),
								   GetSQLValueString($rounds, "text")
								   );

						mysqli_real_escape_string($connection,$insertSQL);
						$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));

					}

				} while ($row_entries = mysqli_fetch_assoc($entries));

			}

		} // End if ($table_styles != $row_table['tableStyles'])

		// Update table in judging_tables table
		$updateSQL = sprintf("UPDATE $judging_tables_db_table SET
		tableName=%s,
		tableStyles=%s,
		tableNumber=%s,
		tableLocation=%s
		WHERE id=%s",

							GetSQLValueString($purifier->purify($_POST['tableName']), "text"),
							GetSQLValueString(sterilize($table_styles), "text"),
							GetSQLValueString(sterilize($_POST['tableNumber']), "text"),
							GetSQLValueString(sterilize($_POST['tableLocation']), "text"),
							GetSQLValueString($id, "text"));

		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

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

				//echo $query_entry."<br>";

				if ($_SESSION['prefsStyleSet'] != "BA") {

					$query_style = sprintf("SELECT id FROM $styles_db_table WHERE (brewStyleVersion='%s' OR brewStyleOwn='custom') AND brewStyleGroup='%s' AND brewStyleNum='%s'", $styles_db_table,$_SESSION['prefsStyleSet'],$row_entry['brewCategorySort'],$row_entry['brewSubCategory']);
					$style = mysqli_query($connection,$query_style) or die (mysqli_error($connection));
					$row_style = mysqli_fetch_assoc($style);

					$style_id = $row_style['id'];

				}

				else $style_id = $row_entry['brewSubCategory'];

				//echo $query_style."<br>";

				$query_table_styles = "SELECT id,tableStyles FROM $judging_tables_db_table";
				$table_styles = mysqli_query($connection,$query_table_styles) or die (mysqli_error($connection));
				$row_table_styles = mysqli_fetch_assoc($table_styles);

				//echo $row_style['id']."<br>";
				//echo $query_table_styles."<br>";

				do {
					$style_array = explode(",",$row_table_styles['tableStyles']);

					if (in_array($style_id,$style_array)) {

						$updateSQL = sprintf("UPDATE $judging_flights_db_table SET flightTable=%s WHERE flightEntryID=%s",
							   GetSQLValueString($row_table_styles['id'], "text"),
							   GetSQLValueString($id, "text"));

						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

					}

				} while ($row_table_styles = mysqli_fetch_assoc($table_styles));

			}

		}

		// Remove all flight rows if unassigning ALL present table styles
		if (empty($_POST['tableStyles'])) {

			do { $a[] = $row_flight_count['id']; } while ($row_flight_count = mysqli_fetch_assoc($flight_count));

			foreach ($a as $id) {

				$deleteSQL = sprintf("DELETE FROM $judging_flights_db_table WHERE id='%s'", $id);
				mysqli_real_escape_string($connection,$deleteSQL);
				$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));

				}

		} // end if (($totalRows_flight_count > 0) && ($table_styles == ""))

		$updateGoTo = $base_url."index.php?section=admin&go=judging_tables&msg=2";
		$pattern = array('\'', '"');
		$updateGoTo = str_replace($pattern, "", $updateGoTo);
		$redirect_go_to = sprintf("Location: %s", stripslashes($updateGoTo));

		}

} else {
	$redirect_go_to = sprintf("Location: %s", $base_url."index.php?msg=98");
}
?>