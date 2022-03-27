<?php
/*
 * Module:      process_judging_assignments.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "judging_assignments" table
 * ----------------------------------------------------
 * Role functionality commented out
 * Search for Activate for Roles
 */

if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

	if ($action == "update") {

		if ($_SESSION['jPrefsQueued'] == "N") {

			//print_r($_POST);
			//exit;

			foreach ($_POST['random'] as $random) {
				
				$roles = array();
				$assignRoles = "";
				$roles_only_update = FALSE;

				/*
				// Activate for Roles
				if (!empty($_POST['head_judge'.$random])) $roles[] = $_POST['head_judge'.$random];
				if (!empty($_POST['lead_judge'.$random])) $roles[] = $_POST['lead_judge'.$random];
				if (!empty($_POST['minibos_judge'.$random])) $roles[] = $_POST['minibos_judge'.$random];
				if (!empty($roles)) $assignRoles = implode(", ",$roles);		

				if ((!isset($_POST['unassign'.$random])) && (($_POST['rolesPrevDefined'.$random] == 1) || ($_POST['rolesPrevDefined'.$random] == 0)) && (!empty($assignRoles))) $roles_only_update = TRUE;
				elseif ((!isset($_POST['unassign'.$random])) && ($_POST['rolesPrevDefined'.$random] == 1) && (empty($assignRoles))) $roles_only_update = TRUE;

				*/

				// Set up insert/update vars
				$bid = sterilize($_POST['bid'.$random]);
				$assignment = sterilize($_POST['assignment'.$random]);
				$assignTable = sterilize($_POST['assignTable'.$random]);
				$assignFlight = sterilize($_POST['assignFlight'.$random]);
				$assignRound = sterilize($_POST['assignRound'.$random]);
				$assignLocation = sterilize($_POST['assignLocation'.$random]);
				$assignPlanning = sterilize($_SESSION['jPrefsTablePlanning']);
				$assignRoles = sterilize($assignRoles);

				$data = array(
					'bid' => $bid,
					'assignment' => $assignment,
					'assignTable' => $assignTable,
					'assignFlight' => $assignFlight,
					'assignRound' => $assignRound,
					'assignLocation' => $assignLocation,
					'assignPlanning' => $assignPlanning,
					'assignRoles' => $assignRoles
				);

				if (isset($_POST['unassign'.$random])) $unassign = $_POST['unassign'.$random];
				else $unassign = 0

				// Check to see if participant is 1) not being "unassigned" and reassigned, and 2) being assigned.
				if (($unassign == 0) && ((isset($_POST['assignFlight'.$random])) && ($_POST['assignFlight'.$random] > 0))) {

					//Perform check to see if a record is in the DB. If not, insert a new record.
					// If so, see will update
					$query_flights = sprintf("SELECT id FROM %s WHERE (bid='%s' AND assignTable='%s' AND assignRound='%s' AND assignFlight='%s' AND assignLocation='%s')", $prefix."judging_assignments", $_POST['bid'.$random], $_POST['assignTable'.$random], $_POST['assignRound'.$random], $_POST['assignFlight'.$random], $_POST['assignLocation'.$random]);
					$flights = mysqli_query($connection,$query_flights) or die (mysqli_error($connection));
					$row_flights = mysqli_fetch_assoc($flights);
					$totalRows_flights = mysqli_num_rows($flights);

					if ($totalRows_flights == 0) {

						$update_table = $prefix."judging_assignments";
						$result = $db_conn->insert ($update_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					}

					else {

						$db_conn->where ('id', sterilize($row_flights['id']));
						$result = $db_conn->update ($update_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					}

				} // end if (($unassign == 0) && ((isset($_POST['assignFlight'.$random])) && ($_POST['assignFlight'.$random] > 0)))

				if (($unassign > 0) && ((isset($_POST['assignFlight'.$random])) && ($_POST['assignFlight'.$random] > 0))) {

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
					$data = array('assignRoles' => $assignRoles);
					$db_conn->where ('id', sterilize($_POST['id'.$random]));
					$result = $db_conn->update ($update_table, $data);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

				} // end if (($roles_only_update) && ($_POST['id'.$random] > 0))

				if (($unassign > 0) && ((isset($_POST['assignFlight'.$random])) && ($_POST['assignFlight'.$random] == 0))) {
					
					$query_flights = sprintf("SELECT id FROM $judging_assignments_db_table WHERE bid='%s' AND assignRound='%s' and assignLocation='%s'", $_POST['bid'.$random], $_POST['assignRound'.$random], $_POST['assignLocation'.$random]);
					$flights = mysqli_query($connection,$query_flights) or die (mysqli_error($connection));
					$row_flights = mysqli_fetch_assoc($flights);
					$totalRows_flights = mysqli_num_rows($flights);

					if ($totalRows_flights > 0) {

						$update_table = $prefix."judging_assignments";
						$db_conn->where ('id', sterilize($row_flights['id']));
						$result = $db_conn->delete($update_table);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					} // end if ($totalRows_flights > 0)

				} // end if (($unassign > 0) && ((isset($_POST['assignFlight'.$random])) && ($_POST['assignFlight'.$random] == 0)))

			} // end foreach

			if ((isset($_POST['head_judge_choose'])) && (!empty($_POST['head_judge_choose']))) {

				$update_table = $prefix."judging_assignments";
				$data = array('assignRoles' => 'HJ');
				$db_conn->where ('bid', sterilize($_POST['head_judge_choose']));
				$db_conn->where ('assignTable', sterilize($_POST['assignTable'.$random]));
				$result = $db_conn->update ($update_table,$data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}
		
			} // end if ((isset($_POST['head_judge_choose'])) && (!empty($_POST['head_judge_choose'])))

	  	} // end if ($_SESSION['jPrefsQueued'] == "N")

	  	if ($_SESSION['jPrefsQueued'] == "Y") {
			
			foreach ($_POST['random'] as $random) {

				$roles = array();
				$assignRoles = "";
				$roles_only_update = FALSE;

				if (!empty($_POST['head_judge'.$random])) $roles[] = $_POST['head_judge'.$random];
				if (!empty($_POST['lead_judge'.$random])) $roles[] = $_POST['lead_judge'.$random];
				if (!empty($_POST['minibos_judge'.$random])) $roles[] = $_POST['minibos_judge'.$random];
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

				$data = array(
					'bid' => $bid,
					'assignment' => $assignment,
					'assignTable' => $assignTable,
					'assignFlight' => $assignFlight,
					'assignRound' => $assignRound,
					'assignLocation' => $assignLocation,
					'assignPlanning' => $assignPlanning,
					'assignRoles' => $assignRoles
				);

				if ((!isset($_POST['unassign'.$random])) && (($_POST['rolesPrevDefined'.$random] == 1) || ($_POST['rolesPrevDefined'.$random] == 0)) && (!empty($assignRoles))) $roles_only_update = TRUE;
				elseif ((!isset($_POST['unassign'.$random])) && ($_POST['rolesPrevDefined'.$random] == 1) && (empty($assignRoles))) $roles_only_update = TRUE;

				// Check to see if participant is 1) not being "unassigned" and reassigned, and 2) being assigned.
				if (((isset($_POST['unassign'.$random])) && ($_POST['unassign'.$random] == 0)) && ((isset($_POST['assignRound'.$random])) && ($_POST['assignRound'.$random] > 0)))  {

					// Perform check to see if a record is in the DB. If not, insert a new record.
					// If so, update
					$query_flights = sprintf("SELECT COUNT(*) as 'count' FROM $judging_assignments_db_table WHERE (bid='%s' AND assignRound='%s' AND assignLocation='%s')", $_POST['bid'.$random], $_POST['assignRound'.$random], $_POST['assignLocation'.$random]);
					$flights = mysqli_query($connection,$query_flights) or die (mysqli_error($connection));
					$row_flights = mysqli_fetch_assoc($flights);

					if ($row_flights['count'] == 0) {

						$update_table = $prefix."judging_assignments";
						$result = $db_conn->insert ($update_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					}

				} // end if (((isset($_POST['unassign'.$random])) && ($_POST['unassign'.$random] == 0)) && ((isset($_POST['assignRound'.$random])) && ($_POST['assignRound'.$random] > 0)))

				// If already assigned but updating judge roles...
				if (($roles_only_update) && ($_POST['id'.$random] > 0)) {

					$update_table = $prefix."judging_assignments";
					$data = array('assignRoles' => $assignRoles);
					$db_conn->where ('id', sterilize($_POST['id'.$random]));
					$result = $db_conn->update ($update_table, $data);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

				} // end if (($roles_only_update) && ($_POST['id'.$random] > 0))

				if (((isset($_POST['unassign'.$random])) && ($_POST['unassign'.$random] > 0)) && ((isset($_POST['assignRound'.$random])) && ($_POST['assignRound'.$random] > 0))) {
		
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