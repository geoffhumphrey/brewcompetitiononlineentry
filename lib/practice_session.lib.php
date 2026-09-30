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
 *              assign_judge_to_practice_session() late-joins a single judge
 *              designated after the session's own creation-time snapshot -
 *              called from includes/process/process_users_register.inc.php
 *              (registration), and process_brewer.inc.php (self-edit and
 *              admin-edit, both "add" and "edit" actions).
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
 * Returns TRUE if this uid currently has a judging_assignments row on the
 * practice session's table, FALSE otherwise (including when no practice
 * session exists at all). A practice session's own judging_locations row is
 * always created open immediately (see process_practice_session.inc.php),
 * so a judge assigned to it should be able to reach the Judging Dashboard
 * right away - independent of jPrefsJudgingOpen, the real competition's own
 * (possibly still-future) judging start date/time.
 */
function judge_has_practice_assignment($db_conn, $prefix, $uid) {

	$practice_table_id = practice_session_exists($db_conn, $prefix);
	if (!$practice_table_id) return FALSE;

	$db_conn->where('bid', $uid);
	$db_conn->where('assignTable', $practice_table_id);
	$db_conn->where('assignment', 'J');
	$row = $db_conn->getOne($prefix."judging_assignments", "id");

	return !empty($row);

}

/**
 * Adds one judge (by brewer.uid) to the current practice session, if one
 * exists - a no-op, reported as success, if there's no practice session
 * right now. Mirrors the batch assignment includes/process/
 * process_practice_session.inc.php does at creation time (availability
 * marker + judging_assignments row), but for a single judge who was
 * designated *after* that snapshot - registering as a judge, self-editing
 * their account to say Yes, or having an admin do so on their behalf. Safe
 * to call unconditionally whenever a brewer row's brewerJudge is saved as
 * "Y" - idempotent, so re-saving Y on someone already in the practice
 * session changes nothing.
 */
function assign_judge_to_practice_session($db_conn, $prefix, $uid) {

	$errors = FALSE;
	$error_output = array();

	$db_conn->where('tableNumber', PRACTICE_SESSION_TABLE_NUMBER);
	$row_table = $db_conn->getOne($prefix."judging_tables", "id,tableLocation");

	// No practice session right now - nothing to do.
	if (empty($row_table)) return array('success' => TRUE, 'errors' => array());

	$practice_table_id = $row_table['id'];
	$practice_location_id = $row_table['tableLocation'];

	// Re-read the judge's own current availability fresh, rather than trusting
	// a value the caller might have on hand from before its own save - matches
	// process_practice_session.inc.php's own per-judge (not shared/reused)
	// availability handling.
	$db_conn->where('uid', $uid);
	$row_brewer = $db_conn->getOne($prefix."brewer", "id,brewerJudgeLocation");

	if (empty($row_brewer)) {
		return array('success' => FALSE, 'errors' => array("No brewer record found for uid ".$uid."."));
	}

	$location_marker = "Y-".$practice_location_id;
	$existing_locations = (!empty($row_brewer['brewerJudgeLocation'])) ? explode(",", $row_brewer['brewerJudgeLocation']) : array();

	if (!in_array($location_marker, $existing_locations)) {

		// Drop any stale "N-" marker for this same location before appending -
		// same precedent as delete_practice_session()'s location cleanup.
		$existing_locations = array_filter($existing_locations, function($v) use ($practice_location_id) {
			return $v !== "N-".$practice_location_id;
		});
		$existing_locations[] = $location_marker;
		$new_availability = ltrim(implode(",", array_filter($existing_locations, function($v) { return $v !== ""; })), ",");

		$db_conn->where('uid', $uid);
		$result = $db_conn->update($prefix."brewer", array('brewerJudgeLocation' => $new_availability));
		if (!$result) { $error_output[] = $db_conn->getLastError(); $errors = TRUE; }

	}

	// Idempotency: don't double-assign if this judge is somehow already on the
	// practice table (e.g. brewerJudge saved as "Y" twice in a row).
	$db_conn->where('bid', $uid);
	$db_conn->where('assignTable', $practice_table_id);
	$db_conn->where('assignment', 'J');
	$row_existing_assign = $db_conn->getOne($prefix."judging_assignments", "id");

	if (empty($row_existing_assign)) {

		$data = array(
			'bid' => $uid,
			'assignment' => "J",
			'assignTable' => $practice_table_id,
			'assignFlight' => 1,
			'assignRound' => 1,
			'assignLocation' => $practice_location_id,
			'assignPlanning' => NULL,
			'assignRoles' => NULL
		);

		$result = $db_conn->insert($prefix."judging_assignments", $data);
		if (!$result) { $error_output[] = $db_conn->getLastError(); $errors = TRUE; }

	}

	return array('success' => !$errors, 'errors' => $error_output);

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
