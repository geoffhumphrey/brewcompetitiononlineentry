<?php
/*
 * Module:      process_judging_assignments.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "judging_assignments" table
 * ----------------------------------------------------
 * Role functionality commented out
 * Search for Activate for Roles
 */

if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))) {

	if ($action == "update") {

		if ($_SESSION['jPrefsQueued'] == "N") {

		foreach ($_POST['random'] as $random) {

			$roles = array();
			$assignRoles = "";
			$roles_only_update = FALSE;

			// Activate for Roles

			if (!empty($_POST['head_judge'.$random])) {
				$roles[] = $_POST['head_judge'.$random];
			}

			if (!empty($_POST['lead_judge'.$random])) {
				$roles[] = $_POST['lead_judge'.$random];
			}

			if (!empty($_POST['minibos_judge'.$random])) {
				$roles[] = $_POST['minibos_judge'.$random];
			}

			if (!empty($roles)) {
				$assignRoles = implode(", ",$roles);
			}

			if ((!isset($_POST['unassign'.$random])) && (($_POST['rolesPrevDefined'.$random] == 1) || ($_POST['rolesPrevDefined'.$random] == 0)) && (!empty($assignRoles))) $roles_only_update = TRUE;
			elseif ((!isset($_POST['unassign'.$random])) && ($_POST['rolesPrevDefined'.$random] == 1) && (empty($assignRoles))) $roles_only_update = TRUE;

			if (isset($_POST['unassign'.$random])) $unassign = $_POST['unassign'.$random];
			else $unassign = 0;

			// Check to see if participant is 1) not being "unassigned" and reassigned, and 2) being assigned.
			if (($unassign == 0) && ((isset($_POST['assignFlight'.$random])) && ($_POST['assignFlight'.$random] > 0))) {

				//Perform check to see if a record is in the DB. If not, insert a new record.
				// If so, see will update
				$query_flights = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE (bid='%s' AND assignTable='%s' AND assignRound='%s' AND assignFlight='%s' AND assignLocation='%s')", $prefix."judging_assignments", $_POST['bid'.$random], $_POST['assignTable'.$random], $_POST['assignRound'.$random], $_POST['assignFlight'.$random], $_POST['assignLocation'.$random]);
				$flights = mysqli_query($connection,$query_flights) or die (mysqli_error($connection));
				$row_flights = mysqli_fetch_assoc($flights);
				//echo $query_flights."<br>";

				if ($row_flights['count'] == 0) {
				$insertSQL = sprintf("INSERT INTO $judging_assignments_db_table (bid, assignment, assignTable, assignFlight, assignRound, assignLocation, assignRoles) VALUES (%s, %s, %s, %s, %s, %s, %s)",
					GetSQLValueString(sterilize($_POST['bid'.$random]), "text"),
					GetSQLValueString(sterilize($_POST['assignment'.$random]), "text"),
					GetSQLValueString(sterilize($_POST['assignTable'.$random]), "text"),
					GetSQLValueString(sterilize($_POST['assignFlight'.$random]), "text"),
					GetSQLValueString(sterilize($_POST['assignRound'.$random]), "text"),
					GetSQLValueString(sterilize($_POST['assignLocation'.$random]), "text"),
					GetSQLValueString(sterilize($assignRoles), "text"));

				mysqli_real_escape_string($connection,$insertSQL);
				$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));
				//echo $insertSQL."<br>";

				}
			}

			if (($unassign > 0) && ((isset($_POST['assignFlight'.$random])) && ($_POST['assignFlight'.$random] > 0))) {

				$updateSQL = sprintf("UPDATE $judging_assignments_db_table SET bid=%s, assignment=%s, assignTable=%s, assignFlight=%s, assignRound=%s, assignLocation=%s, assignRoles=%s WHERE id=%s",
					GetSQLValueString(sterilize($_POST['bid'.$random]), "text"),
					GetSQLValueString(sterilize($_POST['assignment'.$random]), "text"),
					GetSQLValueString(sterilize($_POST['assignTable'.$random]), "text"),
					GetSQLValueString(sterilize($_POST['assignFlight'.$random]), "text"),
					GetSQLValueString(sterilize($_POST['assignRound'.$random]), "text"),
					GetSQLValueString(sterilize($_POST['assignLocation'.$random]), "text"),
					GetSQLValueString(sterilize($assignRoles), "text"),
					GetSQLValueString(sterilize($_POST['unassign'.$random]), "text")
					);

				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				//echo $updateSQL."<br>";

			}

			// Activate for Roles
			// If already assigned but updating judge roles...
			if (($roles_only_update) && ($_POST['id'.$random] > 0)) {

				$updateSQL = sprintf("UPDATE $judging_assignments_db_table SET assignRoles=%s WHERE id=%s",
					GetSQLValueString(sterilize($assignRoles), "text"),
					GetSQLValueString(sterilize($_POST['id'.$random]), "text")
					);

				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				//echo $updateSQL."<br>";

			}

			if (($unassign > 0) && ((isset($_POST['assignFlight'.$random])) && ($_POST['assignFlight'.$random] == 0))) {
				$query_flights = sprintf("SELECT id FROM $judging_assignments_db_table WHERE bid='%s' AND assignRound='%s' and assignLocation='%s'", $_POST['bid'.$random], $_POST['assignRound'.$random], $_POST['assignLocation'.$random]);
				$flights = mysqli_query($connection,$query_flights) or die (mysqli_error($connection));
				$row_flights = mysqli_fetch_assoc($flights);
				$totalRows_flights = mysqli_num_rows($flights);
				//echo $query_flights."<br>";

					if ($totalRows_flights > 0) {
						$deleteSQL = sprintf("DELETE FROM $judging_assignments_db_table WHERE id='%s'", $row_flights['id']);
						mysqli_real_escape_string($connection,$deleteSQL);
						$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
						//echo $deleteSQL."<br>";
					}
				}
			} // end foreach
	  } // end if ($_SESSION['jPrefsQueued'] == "N")

	  if ($_SESSION['jPrefsQueued'] == "Y") {
		 // print_r($_POST['random']);
			foreach ($_POST['random'] as $random) {

				$roles = array();
				$assignRoles = "";
				$roles_only_update = FALSE;


				// Activate for Roles
				if (!empty($_POST['head_judge'.$random])) {
					$roles[] = $_POST['head_judge'.$random];
				}

				if (!empty($_POST['lead_judge'.$random])) {
					$roles[] = $_POST['lead_judge'.$random];
				}

				if (!empty($_POST['minibos_judge'.$random])) {
					$roles[] = $_POST['minibos_judge'.$random];
				}

				if (!empty($roles)) {
					$assignRoles = implode(", ",$roles);
				}

				if ((!isset($_POST['unassign'.$random])) && (($_POST['rolesPrevDefined'.$random] == 1) || ($_POST['rolesPrevDefined'.$random] == 0)) && (!empty($assignRoles))) $roles_only_update = TRUE;
				elseif ((!isset($_POST['unassign'.$random])) && ($_POST['rolesPrevDefined'.$random] == 1) && (empty($assignRoles))) $roles_only_update = TRUE;

				// Check to see if participant is 1) not being "unassigned" and reassigned, and 2) being assigned.
				if (((isset($_POST['unassign'.$random])) && ($_POST['unassign'.$random] == 0)) && ((isset($_POST['assignRound'.$random])) && ($_POST['assignRound'.$random] > 0)))  {

					//Perform check to see if a record is in the DB. If not, insert a new record.
					// If so, will update
					$query_flights = sprintf("SELECT COUNT(*) as 'count' FROM $judging_assignments_db_table WHERE (bid='%s' AND assignRound='%s' AND assignLocation='%s')", $_POST['bid'.$random], $_POST['assignRound'.$random], $_POST['assignLocation'.$random]);
					$flights = mysqli_query($connection,$query_flights) or die (mysqli_error($connection));
					$row_flights = mysqli_fetch_assoc($flights);
					//echo $query_flights."<br>";

					if ($row_flights['count'] == 0) {
						$insertSQL = sprintf("INSERT INTO $judging_assignments_db_table (bid, assignment, assignTable, assignFlight, assignRound, assignLocation, assignRoles) VALUES (%s, %s, %s, %s, %s, %s, %s)",
						GetSQLValueString(sterilize($_POST['bid'.$random]), "text"),
						GetSQLValueString(sterilize($_POST['assignment'.$random]), "text"),
						GetSQLValueString(sterilize($id), "text"),
						GetSQLValueString("1", "text"),
						GetSQLValueString(sterilize($_POST['assignRound'.$random]), "text"),
						GetSQLValueString(sterilize($_POST['assignLocation'.$random]), "text"),
						GetSQLValueString(sterilize($assignRoles), "text"));

						mysqli_real_escape_string($connection,$insertSQL);
						$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));
						//echo $insertSQL."<br>";

					}
				}

				// Activate for Roles
				// If already assigned but updating judge roles...
				if (($roles_only_update) && ($_POST['id'.$random] > 0)) {

					$updateSQL = sprintf("UPDATE $judging_assignments_db_table SET assignRoles=%s WHERE id=%s",
						GetSQLValueString($assignRoles, "text"),
						GetSQLValueString($_POST['id'.$random], "text")
						);

					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
					//echo $updateSQL."<br>";

				}

				if (((isset($_POST['unassign'.$random])) && ($_POST['unassign'.$random] > 0)) && ((isset($_POST['assignRound'.$random])) && ($_POST['assignRound'.$random] > 0))) {
					$updateSQL = sprintf("UPDATE $judging_assignments_db_table SET bid=%s, assignment=%s, assignTable=%s, assignFlight=%s, assignRound=%s, assignLocation=%s, assignRoles=%s WHERE id=%s",
						GetSQLValueString(sterilize($_POST['bid'.$random]), "text"),
						GetSQLValueString(sterilize($_POST['assignment'.$random]), "text"),
						GetSQLValueString(sterilize($id), "text"),
						GetSQLValueString("1", "text"),
						GetSQLValueString(sterilize($_POST['assignRound'.$random]), "text"),
						GetSQLValueString(sterilize($_POST['assignLocation'.$random]), "text"),
						GetSQLValueString(sterilize($assignRoles), "text"),
						GetSQLValueString(sterilize($_POST['unassign'.$random]), "text"));
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
					//echo $updateSQL."<br>";

				}

				if (((isset($_POST['unassign'.$random])) && ($_POST['unassign'.$random] > 0)) && ((isset($_POST['assignRound'.$random])) && ($_POST['assignRound'.$random] == 0))) {
					$deleteSQL = sprintf("DELETE FROM $judging_assignments_db_table WHERE id='%s'", $_POST['unassign'.$random]);
					mysqli_real_escape_string($connection,$deleteSQL);
					$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
					//echo $deleteSQL."<br>";
				}
			} // end foreach
	 }  // end if ($_SESSION['jPrefsQueued'] == "Y")

	$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&go=judging_tables&msg=2");

	}

} else {
	$redirect_go_to = sprintf("Location: %s", $base_url."index.php?msg=98");
}

?>