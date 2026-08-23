<?php
/*
 * Module:      process_reorder_flights.inc.php
 * Description: Saves the manual pull order (flightEntryOrder) for all entries within a single flight.
 *              CSRF (user_session_token) and referer are validated upstream in process.inc.php.
 */

$errors = FALSE;

if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))) {

	$flight_table = (int) sterilize($_POST['flightTable']);
	$flight_number = (int) sterilize($_POST['flightNumber']);

	// Ordered entry ids as posted by flight_reorder.min.js ("12,3,7")
	$ordered_ids = [];
	$order_raw = isset($_POST['entry_order']) ? explode(",",(string) $_POST['entry_order']) : [];
	foreach ($order_raw as $v) {
		$v = (int) trim($v);
		if ($v > 0) $ordered_ids[] = $v;
	}

	// Every entry currently assigned to this flight
	$db_conn->where('flightTable', $flight_table);
	$db_conn->where('flightNumber', $flight_number);
	$rows_flight_entries = $db_conn->get($judging_flights_db_table, null, "flightEntryID");
	$existing_ids = [];
	foreach ($rows_flight_entries as $row_flight_entries) $existing_ids[] = (int) $row_flight_entries['flightEntryID'];

	// The posted order must contain every entry of this flight exactly once -
	// a partial order or an id from another table/flight is rejected outright.
	$sent_ids = $ordered_ids;
	sort($sent_ids);
	$existing_ids_sorted = $existing_ids;
	sort($existing_ids_sorted);

	if ((count($ordered_ids) > 0) && ($sent_ids === $existing_ids_sorted)) {

		foreach ($ordered_ids as $position => $entry_id) {

			$data = [
				'flightEntryOrder' => $position + 1,
			];
			$db_conn->where('flightTable', $flight_table);
			$db_conn->where('flightNumber', $flight_number);
			$db_conn->where('flightEntryID', $entry_id);
			$result = $db_conn->update ($judging_flights_db_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$_SESSION['error_output'] = implode(" ",$error_output);
				$errors = TRUE;
				break;
			}

		}

	} else $errors = TRUE;

	// Back to the reorder view for this flight
	$updateGoTo = $base_url."index.php?section=admin&go=judging_flights&filter=define&action=edit&id=".$flight_table."&flight=".$flight_number."&msg=".(($errors) ? "3" : "2");
	$updateGoTo = prep_redirect_link($updateGoTo);
	$redirect_go_to = sprintf("Location: %s", $updateGoTo);

} else {

	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);

}

?>
