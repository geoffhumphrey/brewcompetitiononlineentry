<?php
/*
 * Module:      process_judging_flights.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "judging_flights" table
 */

$errors = FALSE;
$error_output = array();
$_SESSION['error_output'] = "";

if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))) {

	 if ($action == "add") {
		
		foreach($_POST['id'] as $id) {

			// Set up vars
			$flightTable = sterilize($_POST['flightTable']);
			$flightNumber = sterilize($_POST['flightNumber'.$id]);
			$flightNumber = ltrim($flightNumber,"flight");
			$flightEntryID = sterilize($id);
			$flightRound = 1;

			/**
			 * GitHub issue #1751: a blank/missing id[] value here (cause not fully
			 * pinned down - a stray form row is the leading suspect) used to fall
			 * through to a real INSERT every time: the dedup check just below matches
			 * on `WHERE flightEntryID = ?`, and MysqliDb renders a null value as the
			 * literal `= NULL`, which never matches any row (not even other NULL rows)
			 * under standard SQL - so it always looked like "no existing row" and
			 * inserted a fresh flightEntryID-less row instead of ever finding/reusing
			 * one it had already created. Each of those phantom rows still counts
			 * toward flight_entry_count()'s "N entries in this flight" display, quietly
			 * inflating it with entries that don't exist. Skip rather than insert junk.
			 */
			if (empty($flightEntryID)) continue;

			$update_table = $prefix."judging_flights";
			$data = array(
				'flightTable' => blank_to_null($flightTable),
				'flightNumber' => blank_to_null($flightNumber),
				'flightEntryID' => blank_to_null($flightEntryID),
				'flightRound' => blank_to_null($flightRound),
			);

			// This table is being flighted for the first time (that's what makes this the
			// "add" action rather than "edit" - see table_choose() in admin.lib.php), but the
			// entry itself may already have a row from a previous table assignment (e.g. its
			// style/category was later moved here from a table that already had flights
			// defined). Update that row instead of blindly inserting a second one for the
			// same entry - see GitHub issue #1641.
			$db_conn->where('flightEntryID', $flightEntryID);
			$db_conn->orderBy('id', 'DESC');
			$row_existing_flight = $db_conn->getOne($prefix."judging_flights", "id");

			if ($row_existing_flight) {
				$db_conn->where('id', $row_existing_flight['id']);
				$result = $db_conn->update ($update_table, $data);
			} else {
				$result = $db_conn->insert ($update_table, $data);
			}

			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

		} // end foreach

		if ($errors) $insertGoTo = $_POST['relocate']."&msg=3";
		$insertGoTo = prep_redirect_link($insertGoTo);
		$redirect_go_to = sprintf("Location: %s", $insertGoTo);
	
	} // end if ($action == "add")

	if ($action == "edit") {

		foreach($_POST['id'] as $id) {

			$flightTable = sterilize($_POST['flightTable']);
			$flightNumber = sterilize($_POST['flightNumber'.$id]);
			$flightNumber = ltrim($flightNumber,"flight");
			

			if ($id <= "999999") {

				$update_table = $prefix."judging_flights";
				$data = array(
					'flightTable' => blank_to_null($flightTable),
					'flightNumber' => blank_to_null($flightNumber)
				);
				$db_conn->where ('id', $id);
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}
			
			if ($id > "999999") {

				$flightEntryID = sterilize($_POST['flightEntryID'.$id]);

				// See the matching guard in the "add" action above (GitHub issue #1751) -
				// a blank/missing flightEntryID here would insert the same kind of
				// entry-less phantom row via the insert() path further down.
				if (empty($flightEntryID)) continue;

				/**
				 * GitHub issue #1751: this branch never set flightRound, unlike the "add"
				 * action above - a brand-new row (no existing flightEntryID match, so it
				 * falls through to insert() below) silently defaulted to NULL. Every
				 * round-scoped lookup on the judge/steward assignment screen (table_round(),
				 * unassign(), already_assigned(), judge_alert(), etc.) does an exact
				 * WHERE ...Round = ? comparison, which never matches NULL in SQL - so a table
				 * with even one such row ends up with broken shading, "already assigned"
				 * state always showing unchecked, and both new assignments and role updates
				 * silently failing to save for that table. Inherit the round already in use
				 * for this table's flight (or the table generally) instead of leaving it unset.
				 */
				$db_conn->where('flightTable', $flightTable);
				$db_conn->where('flightNumber', $flightNumber);
				$db_conn->where('flightRound', NULL, 'IS NOT');
				$db_conn->orderBy('id', 'DESC');
				$row_existing_round = $db_conn->getOne($prefix."judging_flights", "flightRound");

				if (empty($row_existing_round)) {
					$db_conn->where('flightTable', $flightTable);
					$db_conn->where('flightRound', NULL, 'IS NOT');
					$db_conn->orderBy('id', 'DESC');
					$row_existing_round = $db_conn->getOne($prefix."judging_flights", "flightRound");
				}

				if (!empty($row_existing_round)) $flightRound = $row_existing_round['flightRound'];
				else {
					// No other flighted round to inherit from (this is the table's very
					// first flight row) - fall back to the same single-round default
					// process_judging_tables.inc.php already uses.
					$db_conn->where('id', $flightTable);
					$row_table_location = $db_conn->getOne($prefix."judging_tables", "tableLocation");
					$db_conn->where('id', $row_table_location['tableLocation']);
					$row_table_rounds = $db_conn->getOne($prefix."judging_locations", "judgingRounds");
					$flightRound = ($row_table_rounds['judgingRounds'] == 1) ? 1 : null;
				}

				$update_table = $prefix."judging_flights";
				$data = array(
					'flightTable' => blank_to_null($flightTable),
					'flightNumber' => blank_to_null($flightNumber),
					'flightEntryID' => blank_to_null($flightEntryID),
					'flightRound' => blank_to_null($flightRound)
				);

				// $id > 999999 means flight_entry_info() (admin/judging_flights.admin.php)
				// found no existing row for this entry when the page was rendered, but check
				// again here rather than trust that - it's possible for one to exist under a
				// different table by now (e.g. a concurrent edit). Same reasoning as the "add"
				// action above - see GitHub issue #1641.
				$db_conn->where('flightEntryID', $flightEntryID);
				$db_conn->orderBy('id', 'DESC');
				$row_existing_flight = $db_conn->getOne($prefix."judging_flights", "id");

				if ($row_existing_flight) {
					$db_conn->where('id', $row_existing_flight['id']);
					$result = $db_conn->update ($update_table, $data);
				} else {
					$result = $db_conn->insert ($update_table, $data);
				}

				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

		} // end foreach

		if ($errors) $updateGoTo = $_POST['relocate']."&msg=3";
		$updateGoTo = prep_redirect_link($updateGoTo);
		$redirect_go_to = sprintf("Location: %s", $updateGoTo);

	} // end if ($action == "edit")

	if ($action == "assign") {
		
		foreach (array_unique($_POST['id']) as $a) {

			// Check to see if round has changed for the table/flight.
			if ($_POST['flightRound'.$a] != $_POST['flightRoundPrevious'.$a]) {

				// If so, delete all judging/steward assignments for the "old" round
				$db_conn->where("assignTable", $_POST['flightTable'.$a]);
				$db_conn->where("assignFlight", $_POST['flightNumber'.$a]);
				$db_conn->where("assignRound", $_POST['flightRoundPrevious'.$a]);
				$db_conn->orderBy("id", "ASC");
				$rows_assignments = $db_conn->get($judging_assignments_db_table, null, "id");
				$totalRows_assignments = $db_conn->count;

				if ($totalRows_assignments > 0) {

					foreach ($rows_assignments as $row_assignments) {

						$update_table = $prefix."judging_assignments";
						$db_conn->where ('id', $row_assignments['id']);
						$result = $db_conn->delete($update_table);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					}

				} // end if ($totalRows_assignments > 0)

				// Change the rounds for all affected table/flight assignments.
				$db_conn->where("flightTable", $_POST['flightTable'.$a]);
				$db_conn->where("flightNumber", $_POST['flightNumber'.$a]);
				$db_conn->orderBy("id", "ASC");
				$rows_flights = $db_conn->get($judging_flights_db_table, null, "id");

				foreach ($rows_flights as $row_flights) {

					// Update with single WHERE
					$update_table = $prefix."judging_flights";
					$data = array('flightRound' => sterilize($_POST['flightRound'.$a]));
					$db_conn->where ('id', $row_flights['id']);
					$result = $db_conn->update ($update_table, $data);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

				}
			
			} // end if ($_POST['flightRound'.$a] != $_POST['flightRoundPrevious'.$a])

		} // end foreach

		if ($errors) $updateGoTo = $_POST['relocate']."&msg=3";
		$updateGoTo = prep_redirect_link($updateGoTo);
		$redirect_go_to = sprintf("Location: %s", $updateGoTo);

	}
	
} else {

	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);
	
}

?>