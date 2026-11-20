<?php
/*
 * Module:      process_judging_assignments.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "judging_assignments" table
 */

if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

	if ($action == "update") {

		if ($_SESSION['jPrefsQueued'] == "N") {

			//print_r($_POST);
			//exit;

			// Team Lead is only meant to designate one judge per Team/Flight at
			// this table+round, unlike Head Judge below (table-wide) - the
			// checkbox is already mutually exclusive client-side per
			// Team/Flight (admin/judging_assign.admin.php), but that's
			// bypassable, so track every row this save sets "TL" on here, to
			// enforce it server-side too, after the loop below.
			$new_team_leads_n = array();

			foreach ($_POST['random'] as $random) {

				$roles = array();
				$assignRoles = "";
				$roles_only_update = FALSE;

				// Mini-BOS Judge and Team Lead are settable per round here (Head Judge
				// stays table-wide via head_judge_choose, handled separately below).
				// Neither makes sense for a judge not actually assigned to a flight
				// this round, so both are only honored if this round has a real
				// flight (the server-side half of that rule; both checkboxes are
				// also disabled/reset client-side against the same Flight select).
				// The Flight select itself is omitted from the POST entirely when
				// it's disabled (judge already assigned here or elsewhere this
				// round, pending the "Unassign from their current assignment..."
				// checkbox - see lib/admin.lib.php's assign_to_table()), so a
				// submitted value is trusted when present; otherwise fall back to
				// flightPrevDefined, which reflects the flight this round already
				// had before this save (and is a plain hidden field, so it posts
				// either way) - without this fallback, just toggling a role
				// checkbox on an already-assigned judge (without touching the
				// now-disabled Flight select) would silently drop the role.
				if (isset($_POST['assignFlight'.$random])) $has_flight_this_round = ($_POST['assignFlight'.$random] > 0);
				else $has_flight_this_round = ((isset($_POST['flightPrevDefined'.$random])) && ($_POST['flightPrevDefined'.$random] == 1));
				if ((!empty($_POST['minibos_judge'.$random])) && ($has_flight_this_round)) $roles[] = $_POST['minibos_judge'.$random];
				if ((!empty($_POST['team_lead'.$random])) && ($has_flight_this_round)) $roles[] = $_POST['team_lead'.$random];
				if (!empty($roles)) $assignRoles = implode(", ",$roles);

				if ((!isset($_POST['unassign'.$random])) && (($_POST['rolesPrevDefined'.$random] == 1) || ($_POST['rolesPrevDefined'.$random] == 0)) && (!empty($assignRoles))) $roles_only_update = TRUE;
				elseif ((!isset($_POST['unassign'.$random])) && ($_POST['rolesPrevDefined'.$random] == 1) && (empty($assignRoles))) $roles_only_update = TRUE;

				// Set up insert/update vars
				$bid = sterilize($_POST['bid'.$random]);
				$assignment = sterilize($_POST['assignment'.$random]);
				$assignTable = sterilize($_POST['assignTable'.$random]);
				$assignFlight = isset($_POST['assignFlight'.$random]) ? sterilize($_POST['assignFlight'.$random]) : null;
				$assignRound = sterilize($_POST['assignRound'.$random]);
				$assignLocation = sterilize($_POST['assignLocation'.$random]);
				$assignPlanning = sterilize($_SESSION['jPrefsTablePlanning']);
				$assignRoles = sterilize($assignRoles);
				// Team is derived from Flight, not a separate input - two judges
				// assigned to the same flight at this table/round are, by
				// definition, judging the same entries together, which is exactly
				// what "Team" represents. Judges only (admin/judging_assign.admin.php
				// only renders the Flight dropdown when $filter=="judges"); absent
				// for stewards, and absent when "Do Not Assign" (flight 0) is chosen.
				$assignTeam = ((!empty($assignFlight)) && ($assignFlight > 0)) ? $assignFlight : null;

				// Keyed by table+round+team so a later row for the same
				// team/flight this save can override an earlier one (last
				// submitted wins, same resolution Head Judge's
				// $new_head_judge_bid gets below) - the actual cross-bid
				// clearing happens once, after every row this save touches
				// has been written, below.
				if ((strpos($assignRoles, "TL") !== FALSE) && (!empty($assignTeam))) {
					$new_team_leads_n[$assignTable."|".$assignRound."|".$assignTeam] = array('bid' => $bid, 'assignTable' => $assignTable, 'assignRound' => $assignRound, 'assignTeam' => $assignTeam);
				}

				$data = array(
					'bid' => blank_to_null($bid),
					'assignment' => blank_to_null($assignment),
					'assignTable' => blank_to_null($assignTable),
					'assignFlight' => blank_to_null($assignFlight),
					'assignRound' => blank_to_null($assignRound),
					'assignLocation' => blank_to_null($assignLocation),
					'assignPlanning' => blank_to_null($assignPlanning),
					'assignRoles' => blank_to_null($assignRoles),
					'assignTeam' => blank_to_null($assignTeam)
				);

				if (isset($_POST['unassign'.$random])) $unassign = $_POST['unassign'.$random];
				else $unassign = 0;

				// A judge already assigned to a DIFFERENT table in this same
				// round/location (the purple "Assigned to another table" row
				// admin/judging_assign.admin.php shows) could still be assigned
				// here too via the Flight dropdown with nothing stopping it.
				// "Unassign from their current assignment at Table N and..." -
				// the same pattern as the $unassign checkbox above, just
				// targeting that OTHER table's row - frees them from it first.
				// Re-checked here regardless of what the client submits, so a
				// conflict can't be bypassed by leaving the checkbox unchecked.
				if (!empty($_POST['unassignOther'.$random])) {
					$db_conn->where('id', sterilize($_POST['unassignOther'.$random]));
					$db_conn->where('bid', sterilize($_POST['bid'.$random]));
					$result = $db_conn->delete($prefix."judging_assignments");
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}
				}

				$double_booked = FALSE;
				if (($unassign == 0) && ((isset($_POST['assignFlight'.$random])) && ($_POST['assignFlight'.$random] > 0))) {
					$db_conn->where('bid', sterilize($_POST['bid'.$random]));
					$db_conn->where('assignRound', sterilize($_POST['assignRound'.$random]));
					$db_conn->where('assignLocation', sterilize($_POST['assignLocation'.$random]));
					$db_conn->where('assignTable', sterilize($_POST['assignTable'.$random]), '!=');
					$row_double_booked = $db_conn->getOne($prefix."judging_assignments", "COUNT(*) as 'count'");
					if ($row_double_booked['count'] > 0) {
						$double_booked = TRUE;
						$error_output[] = "Judge (bid ".sterilize($_POST['bid'.$random]).") was not assigned to Table ".sterilize($_POST['assignTable'.$random])." for Round ".sterilize($_POST['assignRound'.$random])." - they are still assigned to a different table this round. Check \"Unassign from their current assignment\" to move them instead.";
						$errors = TRUE;
					}
				}

				// Check to see if participant is 1) not being "unassigned" and reassigned, 2) being assigned, and 3) not already assigned elsewhere this round.
				if (($unassign == 0) && (!$double_booked) && ((isset($_POST['assignFlight'.$random])) && ($_POST['assignFlight'.$random] > 0))) {

					//Perform check to see if a record is in the DB. If not, insert a new record.
					// If so, see will update
					$db_conn->where("bid", sterilize($_POST['bid'.$random]));
					$db_conn->where("assignTable", sterilize($_POST['assignTable'.$random]));
					$db_conn->where("assignRound", sterilize($_POST['assignRound'.$random]));
					$db_conn->where("assignFlight", sterilize($_POST['assignFlight'.$random]));
					$db_conn->where("assignLocation", sterilize($_POST['assignLocation'.$random]));
					$row_flights = $db_conn->getOne($prefix."judging_assignments", "id");
					$totalRows_flights = $db_conn->count;

					if ($totalRows_flights == 0) {

						$update_table = $prefix."judging_assignments";
						$result = $db_conn->insert ($update_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					}

					else {

						$update_table = $prefix."judging_assignments";
						$db_conn->where ('id', sterilize($row_flights['id']));
						$result = $db_conn->update ($update_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					}

				} // end if (($unassign == 0) && ((isset($_POST['assignFlight'.$random])) && ($_POST['assignFlight'.$random] > 0)))

				if (($unassign > 0) && ((isset($_POST['assignFlight'.$random])) && ($_POST['assignFlight'.$random] > 0))) {

					$update_table = $prefix."judging_assignments";
					$db_conn->where ('id', sterilize($_POST['unassign'.$random]));
					$result = $db_conn->update ($update_table, $data);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

				} // end if (($unassign > 0) && ((isset($_POST['assignFlight'.$random])) && ($_POST['assignFlight'.$random] > 0)))

				// If already assigned but updating judge roles...
				if (($roles_only_update) && ($_POST['id'.$random] > 0)) {

					$update_table = $prefix."judging_assignments";
					$data = array('assignRoles' => blank_to_null($assignRoles));
					$db_conn->where ('id', sterilize($_POST['id'.$random]));
					$result = $db_conn->update ($update_table, $data);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

				} // end if (($roles_only_update) && ($_POST['id'.$random] > 0))

				if (($unassign > 0) && ((isset($_POST['assignFlight'.$random])) && ($_POST['assignFlight'.$random] == 0))) {

					/**
					 * GitHub issue #1751/#1754: this used to re-derive the row to delete via
					 * its own bid+assignRound+assignLocation lookup rather than just using
					 * $unassign (already the correct, assignTable-scoped id - lib/admin.lib.php's
					 * unassign() computed it at render time and posted it as this exact field).
					 * assignLocation is only a snapshot of the table's location taken when the
					 * assignment was made, not a live reference, so that re-lookup could silently
					 * miss the intended row (if the table's location had since changed) or match a
					 * different table's row entirely (if another assignment happened to share the
					 * same bid+round+location) - either way deleting the wrong thing or nothing at
					 * all. Delete by the id already in hand instead, matching the equivalent Queued
					 * Judging branch below (~line 263), which never had this indirection.
					 */
					$update_table = $prefix."judging_assignments";
					$db_conn->where ('id', sterilize($unassign));
					$result = $db_conn->delete($update_table);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

				} // end if (($unassign > 0) && ((isset($_POST['assignFlight'.$random])) && ($_POST['assignFlight'.$random] == 0)))

			} // end foreach

			if ((isset($_POST['head_judge_choose'])) && (!empty($_POST['head_judge_choose']))) {

				// $random is left over from the foreach above and only holds
				// whatever value its last iteration happened to have - not a
				// reliable reference to this table. $id is what this whole
				// request is scoped to (the queued-mode branch below already
				// uses it the same way for the identical purpose).
				$update_table = $prefix."judging_assignments";
				$hj_bid = sterilize($_POST['head_judge_choose']);
				$hj_table = sterilize($id);

				// Merge "HJ" into each matching row's existing assignRoles instead
				// of overwriting the column outright - a blanket overwrite would
				// silently wipe out that judge's Mini-BOS Judge/Team Lead flags on
				// every round of this table.
				$db_conn->where ('bid', $hj_bid);
				$db_conn->where ('assignTable', $hj_table);
				$rows_hj_roles = $db_conn->get($update_table, null, "id, assignRoles");
				foreach ($rows_hj_roles as $row_hj_roles) {
					$existing_roles = $row_hj_roles['assignRoles'];
					if ((empty($existing_roles)) || (strpos($existing_roles, "HJ") === FALSE)) {
						$merged_roles = (!empty($existing_roles)) ? $existing_roles.", HJ" : "HJ";
						$db_conn->where ('id', $row_hj_roles['id']);
						$result = $db_conn->update ($update_table, array('assignRoles' => $merged_roles));
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}
					}
				}

				// The dropdown is single-valued, so selecting a new Head Judge
				// should replace the old one - clear "HJ" from whoever else
				// previously held it at this table, without touching their other
				// role tokens. Safe via str_replace(): no other token ("MBOS",
				// "TL") contains "HJ" as a substring.
				$db_conn->where ('assignTable', $hj_table);
				$db_conn->where ('bid', $hj_bid, '!=');
				$rows_other_roles = $db_conn->get($update_table, null, "id, assignRoles");
				foreach ($rows_other_roles as $row_other_roles) {
					if ((!empty($row_other_roles['assignRoles'])) && (strpos($row_other_roles['assignRoles'], "HJ") !== FALSE)) {
						$cleaned_roles = trim(str_replace("HJ", "", $row_other_roles['assignRoles']), ", ");
						$db_conn->where ('id', $row_other_roles['id']);
						$result = $db_conn->update ($update_table, array('assignRoles' => blank_to_null($cleaned_roles)));
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}
					}
				}

			} // end if ((isset($_POST['head_judge_choose'])) && (!empty($_POST['head_judge_choose'])))

			// Only one Team Lead per Team/Flight at this table+round - clear
			// "TL" from every OTHER judge sharing the same table+round+team
			// whenever this save just set it for someone. Safe via
			// str_replace(): no other token ("HJ", "MBOS") contains "TL" as
			// a substring.
			foreach ($new_team_leads_n as $new_team_lead_n) {
				$db_conn->where('assignTable', $new_team_lead_n['assignTable']);
				$db_conn->where('assignRound', $new_team_lead_n['assignRound']);
				$db_conn->where('assignTeam', $new_team_lead_n['assignTeam']);
				$db_conn->where('bid', $new_team_lead_n['bid'], '!=');
				$rows_other_tl_n = $db_conn->get($prefix."judging_assignments", null, "id, assignRoles");
				foreach ($rows_other_tl_n as $row_other_tl_n) {
					if ((!empty($row_other_tl_n['assignRoles'])) && (strpos($row_other_tl_n['assignRoles'], "TL") !== FALSE)) {
						$cleaned_tl_n = trim(str_replace("TL", "", $row_other_tl_n['assignRoles']), ", ");
						$db_conn->where('id', $row_other_tl_n['id']);
						$result = $db_conn->update($prefix."judging_assignments", array('assignRoles' => blank_to_null($cleaned_tl_n)));
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}
					}
				}
			}

	  	} // end if ($_SESSION['jPrefsQueued'] == "N")

	  	if ($_SESSION['jPrefsQueued'] == "Y") {

			// The checkbox is mutually exclusive client-side
			// (admin/judging_assign.admin.php), but that's bypassable - track
			// whichever bid this save actually checked, if any, so it can be
			// enforced server-side too, below.
			$new_head_judge_bid = "";

			// Same purpose as $new_head_judge_bid above, for Team Lead -
			// tracked per table+round+team (not table-wide) so Team 1 and
			// Team 2 can each keep their own Team Lead; see the dedup pass
			// after this foreach.
			$new_team_leads_y = array();

			foreach ($_POST['random'] as $random) {

				$roles = array();
				$assignRoles = "";
				$roles_only_update = FALSE;

				if (!empty($_POST['head_judge'.$random])) {
					$roles[] = $_POST['head_judge'.$random];
					$new_head_judge_bid = sterilize($_POST['bid'.$random]);
				}
				if (!empty($_POST['minibos_judge'.$random])) $roles[] = $_POST['minibos_judge'.$random];
				// Team Lead requires a Team - only honor it if this row's Team
				// dropdown was also submitted with a value.
				if ((!empty($_POST['team_lead'.$random])) && (!empty($_POST['assignTeam'.$random]))) $roles[] = $_POST['team_lead'.$random];
				if (!empty($roles)) $assignRoles = implode(", ",$roles);

				// Set up insert/update vars
				$bid = sterilize($_POST['bid'.$random]);
				$assignment = sterilize($_POST['assignment'.$random]);
				$assignTable = sterilize($id);
				$assignFlight = 1;
				$assignRound = sterilize($_POST['assignRound'.$random]);
				$assignLocation = sterilize($_POST['assignLocation'.$random]);
				$assignPlanning = sterilize($_SESSION['jPrefsTablePlanning']);
				$assignRoles = sterilize($assignRoles);
				// Judges only (admin/judging_assign.admin.php only renders the Team
				// dropdown when $filter=="judges"); absent for stewards.
				$assignTeam = isset($_POST['assignTeam'.$random]) ? sterilize($_POST['assignTeam'.$random]) : null;

				// Keyed by table+round+team so a later row for the same team
				// this save can override an earlier one (last submitted
				// wins) - the actual cross-bid clearing happens once, after
				// every row this save touches has been written, below.
				if ((strpos($assignRoles, "TL") !== FALSE) && (!empty($assignTeam))) {
					$new_team_leads_y[$assignTable."|".$assignRound."|".$assignTeam] = array('bid' => $bid, 'assignTable' => $assignTable, 'assignRound' => $assignRound, 'assignTeam' => $assignTeam);
				}

				$data = array(
					'bid' => blank_to_null($bid),
					'assignment' => blank_to_null($assignment),
					'assignTable' => blank_to_null($assignTable),
					'assignFlight' => blank_to_null($assignFlight),
					'assignRound' => blank_to_null($assignRound),
					'assignLocation' => blank_to_null($assignLocation),
					'assignPlanning' => blank_to_null($assignPlanning),
					'assignRoles' => blank_to_null($assignRoles),
					'assignTeam' => blank_to_null($assignTeam)
				);

				if ((!isset($_POST['unassign'.$random])) && (($_POST['rolesPrevDefined'.$random] == 1) || ($_POST['rolesPrevDefined'.$random] == 0)) && (!empty($assignRoles))) $roles_only_update = TRUE;
				elseif ((!isset($_POST['unassign'.$random])) && ($_POST['rolesPrevDefined'.$random] == 1) && (empty($assignRoles))) $roles_only_update = TRUE;

				// Same shape as $roles_only_update above, for the Team dropdown - a judge
				// already assigned to this table/round whose round isn't changing this
				// save never hits the insert branch below, so this is the only path that
				// would persist a Team change for them.
				$team_only_update = FALSE;
				if ((isset($_POST['teamPrevDefined'.$random])) && (!isset($_POST['unassign'.$random])) && (($_POST['teamPrevDefined'.$random] == 1) || ($_POST['teamPrevDefined'.$random] == 0)) && (!empty($assignTeam))) $team_only_update = TRUE;
				elseif ((isset($_POST['teamPrevDefined'.$random])) && (!isset($_POST['unassign'.$random])) && ($_POST['teamPrevDefined'.$random] == 1) && (empty($assignTeam))) $team_only_update = TRUE;

				// Check to see if participant is 1) not being "unassigned" and reassigned, and 2) being assigned.
				if (((isset($_POST['unassign'.$random])) && ($_POST['unassign'.$random] == 0)) && ((isset($_POST['assignRound'.$random])) && ($_POST['assignRound'.$random] > 0)))  {

					// Perform check to see if a record is in the DB. If not, insert a new record.
					// If so, update
					/**
					 * GitHub issue #1751: this checked bid+assignRound+assignLocation only, with
					 * no assignTable filter - so a judge already assigned to a DIFFERENT table that
					 * happens to share the same round+location (common; multiple tables commonly
					 * run at the same session) made this count come back >0, and the new assignment
					 * for the table actually being edited was silently skipped - no error, no
					 * insert, "Info Edited Successfully" shown regardless. Scope to this table
					 * instead of matching by location: assignTable+assignRound+bid already uniquely
					 * identifies "this judge's assignment at this table for this round" on its own,
					 * and unlike assignLocation (a snapshot taken when the assignment was made, not
					 * a live reference - see the assignLocation staleness bug fixed for issue #1754)
					 * it can never go stale. Matches the equivalent non-Queued check above (~line 71).
					 */
					$db_conn->where("bid", sterilize($_POST['bid'.$random]));
					$db_conn->where("assignTable", $assignTable);
					$db_conn->where("assignRound", sterilize($_POST['assignRound'.$random]));
					$row_flights = $db_conn->getOne($judging_assignments_db_table, "COUNT(*) as 'count'");

					if ($row_flights['count'] == 0) {

						$update_table = $prefix."judging_assignments";
						$result = $db_conn->insert ($update_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					}

				} // end if (((isset($_POST['unassign'.$random])) && ($_POST['unassign'.$random] == 0)) && ((isset($_POST['assignRound'.$random])) && ($_POST['assignRound'.$random] > 0)))

				// If already assigned but updating judge roles and/or team...
				if ((($roles_only_update) || ($team_only_update)) && ($_POST['id'.$random] > 0)) {

					$update_table = $prefix."judging_assignments";
					$data = array('assignRoles' => blank_to_null($assignRoles), 'assignTeam' => blank_to_null($assignTeam));
					$db_conn->where ('id', sterilize($_POST['id'.$random]));
					$result = $db_conn->update ($update_table, $data);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

				} // end if ((($roles_only_update) || ($team_only_update)) && ($_POST['id'.$random] > 0))

				if (((isset($_POST['unassign'.$random])) && ($_POST['unassign'.$random] > 0)) && ((isset($_POST['assignRound'.$random])) && ($_POST['assignRound'.$random] > 0))) {
		
					$update_table = $prefix."judging_assignments";
					$db_conn->where ('id', sterilize($_POST['unassign'.$random]));
					$result = $db_conn->update ($update_table, $data);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

				} // end if (((isset($_POST['unassign'.$random])) && ($_POST['unassign'.$random] > 0)) && ((isset($_POST['assignRound'.$random])) && ($_POST['assignRound'.$random] > 0))) 

				if (((isset($_POST['unassign'.$random])) && ($_POST['unassign'.$random] > 0)) && ((isset($_POST['assignRound'.$random])) && ($_POST['assignRound'.$random] == 0))) {

					$update_table = $prefix."judging_assignments";
					$db_conn->where ('id', sterilize($_POST['unassign'.$random]));
					$result = $db_conn->delete($update_table);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

				} // end if (((isset($_POST['unassign'.$random])) && ($_POST['unassign'.$random] > 0)) && ((isset($_POST['assignRound'.$random])) && ($_POST['assignRound'.$random] == 0)))

			} // end foreach

			// Selecting a new Head Judge should replace the old one, the same
			// way the non-queued head_judge_choose save already does above -
			// clear "HJ" from whoever else previously held it at this table,
			// without touching their other role tokens. Safe via str_replace():
			// no other token ("MBOS", "TL") contains "HJ" as a substring.
			if (!empty($new_head_judge_bid)) {
				$db_conn->where('assignTable', sterilize($id));
				$db_conn->where('bid', $new_head_judge_bid, '!=');
				$rows_other_roles_y = $db_conn->get($prefix."judging_assignments", null, "id, assignRoles");
				foreach ($rows_other_roles_y as $row_other_roles_y) {
					if ((!empty($row_other_roles_y['assignRoles'])) && (strpos($row_other_roles_y['assignRoles'], "HJ") !== FALSE)) {
						$cleaned_roles_y = trim(str_replace("HJ", "", $row_other_roles_y['assignRoles']), ", ");
						$db_conn->where('id', $row_other_roles_y['id']);
						$result = $db_conn->update($prefix."judging_assignments", array('assignRoles' => blank_to_null($cleaned_roles_y)));
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}
					}
				}
			}

			// Only one Team Lead per Team at this table+round - clear "TL"
			// from every OTHER judge sharing the same table+round+team
			// whenever this save just set it for someone. Safe via
			// str_replace(): no other token ("HJ", "MBOS") contains "TL" as
			// a substring.
			foreach ($new_team_leads_y as $new_team_lead_y) {
				$db_conn->where('assignTable', $new_team_lead_y['assignTable']);
				$db_conn->where('assignRound', $new_team_lead_y['assignRound']);
				$db_conn->where('assignTeam', $new_team_lead_y['assignTeam']);
				$db_conn->where('bid', $new_team_lead_y['bid'], '!=');
				$rows_other_tl_y = $db_conn->get($prefix."judging_assignments", null, "id, assignRoles");
				foreach ($rows_other_tl_y as $row_other_tl_y) {
					if ((!empty($row_other_tl_y['assignRoles'])) && (strpos($row_other_tl_y['assignRoles'], "TL") !== FALSE)) {
						$cleaned_tl_y = trim(str_replace("TL", "", $row_other_tl_y['assignRoles']), ", ");
						$db_conn->where('id', $row_other_tl_y['id']);
						$result = $db_conn->update($prefix."judging_assignments", array('assignRoles' => blank_to_null($cleaned_tl_y)));
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}
					}
				}
			}

		}  // end if ($_SESSION['jPrefsQueued'] == "Y")

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		$redirect = $base_url."index.php?section=admin&go=judging_tables&msg=2";
		if ($errors) $redirect = $base_url."index.php?section=admin&go=judging_tables&msg=3";
		$redirect = prep_redirect_link($redirect);
		$redirect_go_to = sprintf("Location: %s", $redirect);
	
	}

} else {
	
	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);

}

?>