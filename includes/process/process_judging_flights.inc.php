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

			$update_table = $prefix."judging_flights";
			$data = array(
				'flightTable' => blank_to_null($flightTable),
				'flightNumber' => blank_to_null($flightNumber),
				'flightEntryID' => blank_to_null($flightEntryID),
				'flightRound' => blank_to_null($flightRound),
			);
			$result = $db_conn->insert ($update_table, $data);
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

				$update_table = $prefix."judging_flights";
				$data = array(
					'flightTable' => blank_to_null($flightTable),
					'flightNumber' => blank_to_null($flightNumber),
					'flightEntryID' => blank_to_null($flightEntryID)
				);
				$result = $db_conn->insert ($update_table, $data);
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
				$query_assignments = sprintf("SELECT id FROM $judging_assignments_db_table WHERE assignTable='%s' AND assignFlight='%s' AND assignRound='%s' ORDER BY id", $_POST['flightTable'.$a],$_POST['flightNumber'.$a],$_POST['flightRoundPrevious'.$a]);
				$assignments = mysqli_query($connection,$query_assignments) or die (mysqli_error($connection));
				$row_assignments = mysqli_fetch_assoc($assignments);
				$totalRows_assignments = mysqli_num_rows($assignments);

				if ($totalRows_assignments > 0) {
					
					do {

						$update_table = $prefix."judging_assignments";
						$db_conn->where ('id', $row_assignments['id']);
						$result = $db_conn->delete($update_table);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					} while ($row_assignments = mysqli_fetch_assoc($assignments));

				} // end if ($totalRows_assignments > 0)

				// Change the rounds for all affected table/flight assignments.
				$query_flights = sprintf("SELECT id FROM $judging_flights_db_table WHERE flightTable='%s' AND flightNumber='%s' ORDER BY id", $_POST['flightTable'.$a],$_POST['flightNumber'.$a]);
				$flights = mysqli_query($connection,$query_flights) or die (mysqli_error($connection));
				$row_flights = mysqli_fetch_assoc($flights);

				do {

					// Update with single WHERE
					$update_table = $prefix."judging_flights";
					$data = array('flightRound' => sterilize($_POST['flightRound'.$a]));
					$db_conn->where ('id', $row_flights['id']);
					$result = $db_conn->update ($update_table, $data);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}
				
				} while ($row_flights = mysqli_fetch_assoc($flights));
			
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