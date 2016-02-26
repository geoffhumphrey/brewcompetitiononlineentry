<?php 
/*
 * Module:      process_judging_assignments.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "judging_assignments" table
 */
if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {
	
	
	if ($action == "update") {
		if ($_SESSION['jPrefsQueued'] == "N") {
		foreach ($_POST['random'] as $random) {
			// Check to see if participant is 1) not being "unassigned" and reassigned, and 2) being assigned.
			if (((isset($_POST['unassign'.$random])) && ($_POST['unassign'.$random] == 0)) && ((isset($_POST['assignFlight'.$random])) && ($_POST['assignFlight'.$random] > 0))) {
				
				//Perform check to see if a record is in the DB. If not, insert a new record.
				// If so, see will update
				$query_flights = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE (bid='%s' AND assignRound='%s' AND assignFlight='%s' AND assignLocation='%s')", $prefix."judging_assignments", $_POST['bid'.$random], $_POST['assignRound'.$random], $_POST['assignFlight'.$random], $_POST['assignLocation'.$random]);
				$flights = mysqli_query($connection,$query_flights) or die (mysqli_error($connection));
				$row_flights = mysqli_fetch_assoc($flights);
				
				if ($row_flights['count'] == 0) {
				$insertSQL = sprintf("INSERT INTO $judging_assignments_db_table (bid, assignment, assignTable, assignFlight, assignRound, assignLocation) VALUES (%s, %s, %s, %s, %s, %s)",
					GetSQLValueString($_POST['bid'.$random], "text"),
					GetSQLValueString($_POST['assignment'.$random], "text"),
					GetSQLValueString($_POST['assignTable'.$random], "text"),
					GetSQLValueString($_POST['assignFlight'.$random], "text"),
					GetSQLValueString($_POST['assignRound'.$random], "text"),
					GetSQLValueString($_POST['assignLocation'.$random], "text"));
				
				mysqli_real_escape_string($connection,$insertSQL);
				$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));

				}
			}
			
			
			if (((isset($_POST['unassign'.$random])) && ($_POST['unassign'.$random] > 0)) && ((isset($_POST['assignFlight'.$random])) && ($_POST['assignFlight'.$random] > 0))) {
				$updateSQL = sprintf("UPDATE $judging_assignments_db_table SET bid=%s, assignment=%s, assignTable=%s, assignFlight=%s, assignRound=%s, assignLocation=%s WHERE id=%s", 
					GetSQLValueString($_POST['bid'.$random], "text"),
					GetSQLValueString($_POST['assignment'.$random], "text"),
					GetSQLValueString($_POST['assignTable'.$random], "text"),
					GetSQLValueString($_POST['assignFlight'.$random], "text"),
					GetSQLValueString($_POST['assignRound'.$random], "text"),
					GetSQLValueString($_POST['assignLocation'.$random], "text"),
					GetSQLValueString($_POST['unassign'.$random], "text")
					);		   
				//echo $updateSQL.";<br>";
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			}
			
			if (((isset($_POST['unassign'.$random])) && ($_POST['unassign'.$random] > 0)) && ((isset($_POST['assignFlight'.$random])) && ($_POST['assignFlight'.$random] == 0))) {
				$query_flights = sprintf("SELECT id FROM $judging_assignments_db_table WHERE bid='%s' AND assignRound='%s' and assignLocation='%s'", $_POST['bid'.$random], $_POST['assignRound'.$random], $_POST['assignLocation'.$random]);
				$flights = mysqli_query($connection,$query_flights) or die (mysqli_error($connection));
				$row_flights = mysqli_fetch_assoc($flights);
				$totalRows_flights = mysqli_num_rows($flights);
				//echo $query_flights."<br>";
				
					if ($totalRows_flights > 0) {
						$deleteSQL = sprintf("DELETE FROM $judging_assignments_db_table WHERE id='%s'", $row_flights['id']);
						mysqli_real_escape_string($connection,$deleteSQL);
						$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
					}	
				}
			} // end foreach
	  } // end if ($_SESSION['jPrefsQueued'] == "N")
	  
	  if ($_SESSION['jPrefsQueued'] == "Y") {
			foreach ($_POST['random'] as $random) {
				// Check to see if participant is 1) not being "unassigned" and reassigned, and 2) being assigned.
				if (((isset($_POST['unassign'.$random])) && ($_POST['unassign'.$random] == 0)) && ((isset($_POST['assignFlight'.$random])) && ($_POST['assignFlight'.$random] > 0)))  {
					
					//Perform check to see if a record is in the DB. If not, insert a new record.
					// If so, will update
					$query_flights = sprintf("SELECT COUNT(*) as 'count' FROM $judging_assignments_db_table WHERE (bid='%s' AND assignRound='%s' AND assignLocation='%s')", $_POST['bid'.$random], $_POST['assignRound'.$random], $_POST['assignLocation'.$random]);
					$flights = mysqli_query($connection,$query_flights) or die (mysqli_error($connection));
					$row_flights = mysqli_fetch_assoc($flights);
				
					if ($row_flights['count'] == 0) {
						$insertSQL = sprintf("INSERT INTO $judging_assignments_db_table (bid, assignment, assignTable, assignFlight, assignRound, assignLocation) VALUES (%s, %s, %s, %s, %s, %s)",
						GetSQLValueString($_POST['bid'.$random], "text"),
						GetSQLValueString($_POST['assignment'.$random], "text"),
						GetSQLValueString($id, "text"),
						GetSQLValueString("1", "text"),
						GetSQLValueString($_POST['assignRound'.$random], "text"),
						GetSQLValueString($_POST['assignLocation'.$random], "text"));
						
						mysqli_real_escape_string($connection,$insertSQL);
						$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));
					}
				}
			
				if (((isset($_POST['unassign'.$random])) && ($_POST['unassign'.$random] > 0)) && ((isset($_POST['assignFlight'.$random])) && ($_POST['assignFlight'.$random] > 0))) {
					$updateSQL = sprintf("UPDATE $judging_assignments_db_table SET bid=%s, assignment=%s, assignTable=%s, assignFlight=%s, assignRound=%s, assignLocation=%s WHERE id=%s", 
						GetSQLValueString($_POST['bid'.$random], "text"),
						GetSQLValueString($_POST['assignment'.$random], "text"),
						GetSQLValueString($id, "text"),
						GetSQLValueString("1", "text"),
						GetSQLValueString($_POST['assignRound'.$random], "text"),
						GetSQLValueString($_POST['assignLocation'.$random], "text"),
						GetSQLValueString($_POST['unassign'.$random], "text")
						);		   
					//echo $updateSQL.";<br>";
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				}
			
				if (((isset($_POST['unassign'.$random])) && ($_POST['unassign'.$random] > 0)) && ((isset($_POST['assignFlight'.$random])) && ($_POST['assignFlight'.$random] == 0))) {
					$deleteSQL = sprintf("DELETE FROM $judging_assignments_db_table WHERE id='%s'", $_POST['unassign'.$random]);
					mysqli_real_escape_string($connection,$deleteSQL);
					$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
				}
			} // end foreach	  
	 }  // end if ($_SESSION['jPrefsQueued'] == "Y")
	header(sprintf("Location: %s", $base_url."index.php?section=admin&go=judging_tables&msg=2"));
	}
	
} else echo "<p>Not available.</p>";
?>