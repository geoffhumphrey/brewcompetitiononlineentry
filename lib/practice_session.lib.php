<?php
/**
 * Module:      practice_session.lib.php
 * Description: Shared helpers for the admin-triggered practice judging
 *              session feature (Manage Tables). A practice session is a
 *              single scratch judging_tables row, always created with
 *              tableNumber=999 by includes/process/process_practice_session.inc.php -
 *              that tableNumber is the sole marker used to detect/locate it
 *              (no dedicated flag column). Used by
 *              includes/process/process_practice_session.inc.php (create/delete
 *              triggers), ajax/tables_mode.ajax.php (automatic cleanup when
 *              switching out of Table Planning Mode), admin/judging_tables.admin.php
 *              (button state), and eval/dashboard.eval.php (Planning-Mode
 *              evaluation-visibility exception for the practice table).
 */

define('PRACTICE_SESSION_TABLE_NUMBER', 999);

/**
 * Returns the practice judging_tables.id if a practice session currently
 * exists, or FALSE if not.
 */
function practice_session_exists($db_conn, $prefix) {

	$db_conn->where('tableNumber', PRACTICE_SESSION_TABLE_NUMBER);
	$row_table = $db_conn->getOne($prefix."judging_tables", "id");

	if (!empty($row_table)) return $row_table['id'];
	return FALSE;

}

/**
 * Deletes the practice session (table, location, synthetic entries/styles/
 * brewer/user, judge assignments/flights, and any evaluations judges
 * submitted against it) if one exists. A no-op, reported as success, if
 * there's nothing to delete - safe to call unconditionally as a cleanup
 * step (e.g. on every switch out of Table Planning Mode).
 */
function delete_practice_session($db_conn, $prefix) {

	$errors = FALSE;
	$error_output = array();

	$db_conn->where('tableNumber', PRACTICE_SESSION_TABLE_NUMBER);
	$row_table = $db_conn->getOne($prefix."judging_tables", "id,tableLocation");

	if (empty($row_table)) return array('success' => TRUE, 'errors' => array());

	$practice_table_id = $row_table['id'];
	$practice_location_id = $row_table['tableLocation'];

	// Capture the synthetic entry ids (and, from those entries, the synthetic
	// brewer/style identity) before anything downstream gets deleted out from
	// under them.
	$db_conn->where('flightTable', $practice_table_id);
	$rows_flights = $db_conn->get($prefix."judging_flights", null, "flightEntryID");

	$entry_ids = array();
	foreach ($rows_flights as $row_flight) {
		if (!empty($row_flight['flightEntryID'])) $entry_ids[] = $row_flight['flightEntryID'];
	}

	$synthetic_uids = array();
	$style_tuples = array();

	if (!empty($entry_ids)) {

		$db_conn->where('id', $entry_ids, 'in');
		$rows_entries = $db_conn->get($prefix."brewing", null, "id,brewBrewerID,brewCategorySort,brewSubCategory");

		foreach ($rows_entries as $row_entry) {
			if (!empty($row_entry['brewBrewerID'])) $synthetic_uids[] = $row_entry['brewBrewerID'];
			$style_tuples[] = array('group' => $row_entry['brewCategorySort'], 'num' => $row_entry['brewSubCategory']);
		}

		$synthetic_uids = array_unique($synthetic_uids);

	}

	// Any practice evaluations judges submitted while rehearsing.
	$db_conn->where('evalTable', $practice_table_id);
	$result = $db_conn->delete($prefix."evaluation");
	if (!$result) { $error_output[] = $db_conn->getLastError(); $errors = TRUE; }

	// Defense-in-depth, matches the real-table delete precedent - a practice
	// table has no real reason to have judging_scores rows, but cover it.
	$db_conn->where('scoreTable', $practice_table_id);
	$result = $db_conn->delete($prefix."judging_scores");
	if (!$result) { $error_output[] = $db_conn->getLastError(); $errors = TRUE; }

	$db_conn->where('assignTable', $practice_table_id);
	$result = $db_conn->delete($prefix."judging_assignments");
	if (!$result) { $error_output[] = $db_conn->getLastError(); $errors = TRUE; }

	$db_conn->where('flightTable', $practice_table_id);
	$result = $db_conn->delete($prefix."judging_flights");
	if (!$result) { $error_output[] = $db_conn->getLastError(); $errors = TRUE; }

	if (!empty($entry_ids)) {
		$db_conn->where('id', $entry_ids, 'in');
		$result = $db_conn->delete($prefix."brewing");
		if (!$result) { $error_output[] = $db_conn->getLastError(); $errors = TRUE; }
	}

	// Synthetic custom styles - matched by (group, num, custom) rather than a
	// captured id list, since the entries themselves (deleted above) were the
	// only record of which style rows belong to this practice batch.
	foreach ($style_tuples as $tuple) {
		$db_conn->where('brewStyleGroup', $tuple['group']);
		$db_conn->where('brewStyleNum', $tuple['num']);
		$db_conn->where('brewStyleOwn', 'custom');
		$result = $db_conn->delete($prefix."styles");
		if (!$result) { $error_output[] = $db_conn->getLastError(); $errors = TRUE; }
	}

	if (!empty($synthetic_uids)) {

		$db_conn->where('uid', $synthetic_uids, 'in');
		$result = $db_conn->delete($prefix."brewer");
		if (!$result) { $error_output[] = $db_conn->getLastError(); $errors = TRUE; }

		$db_conn->where('id', $synthetic_uids, 'in');
		$result = $db_conn->delete($prefix."users");
		if (!$result) { $error_output[] = $db_conn->getLastError(); $errors = TRUE; }

	}

	// Strip the practice location out of every judge/steward's availability CSV -
	// same string-surgery as includes/process/process_delete.inc.php's go=="judging"
	// (location delete) branch.
	if (!empty($practice_location_id)) {

		$rows_loc = $db_conn->get($prefix."brewer", null, "id,brewerJudgeLocation,brewerStewardLocation");

		foreach ($rows_loc as $row_loc) {

			if ($row_loc['brewerJudgeLocation'] != "") {

				$a = explode(",", $row_loc['brewerJudgeLocation']);

				if ((in_array("Y-".$practice_location_id, $a)) || (in_array("N-".$practice_location_id, $a))) {

					$c = array();
					foreach ($a as $b) {
						if ($b == "Y-".$practice_location_id) $c[] = "";
						elseif ($b == "N-".$practice_location_id) $c[] = "";
						else $c[] = $b.",";
					}

					$d = rtrim(implode("", $c), ",");

					$db_conn->where('id', $row_loc['id']);
					$result = $db_conn->update($prefix."brewer", array('brewerJudgeLocation' => $d));
					if (!$result) { $error_output[] = $db_conn->getLastError(); $errors = TRUE; }

				}

			}

			if ($row_loc['brewerStewardLocation'] != "") {

				$e = explode(",", $row_loc['brewerStewardLocation']);

				if ((in_array("Y-".$practice_location_id, $e)) || (in_array("N-".$practice_location_id, $e))) {

					$g = array();
					foreach ($e as $f) {
						if ($f == "Y-".$practice_location_id) $g[] = "";
						elseif ($f == "N-".$practice_location_id) $g[] = "";
						else $g[] = $f.",";
					}

					$h = rtrim(implode("", $g), ",");

					$db_conn->where('id', $row_loc['id']);
					$result = $db_conn->update($prefix."brewer", array('brewerStewardLocation' => $h));
					if (!$result) { $error_output[] = $db_conn->getLastError(); $errors = TRUE; }

				}

			}

		}

		$db_conn->where('id', $practice_location_id);
		$result = $db_conn->delete($prefix."judging_locations");
		if (!$result) { $error_output[] = $db_conn->getLastError(); $errors = TRUE; }

	}

	$db_conn->where('id', $practice_table_id);
	$result = $db_conn->delete($prefix."judging_tables");
	if (!$result) { $error_output[] = $db_conn->getLastError(); $errors = TRUE; }

	return array('success' => !$errors, 'errors' => $error_output);

}
?>
