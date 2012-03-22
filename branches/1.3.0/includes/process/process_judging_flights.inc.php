<?php 
/*
 * Module:      process_drop_off.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "drop_off" table
 */

if ($action == "add") {
	foreach($_POST['id'] as $id)	{
		$flight_number = ltrim($_POST['flightNumber'.$id],"flight");
		$insertSQL = sprintf("INSERT INTO judging_flights (
		flightTable, 
		flightNumber, 
		flightEntryID
  		) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['flightTable'], "text"),
					   GetSQLValueString($flight_number, "text"),
					   GetSQLValueString($id, "text")
					   );

		//echo $insertSQL."<br>";
		mysql_select_db($database, $brewing);
  		$Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
		}
	header(sprintf("Location: %s", $insertGoTo));	
}

if ($action == "edit") {
	
	foreach($_POST['id'] as $id)	{
		$flight_number = ltrim($_POST['flightNumber'.$id],"flight");
	
		if ($id <= "999999") {
			$updateSQL = sprintf("UPDATE judging_flights SET
			flightTable=%s,
			flightNumber=%s
			WHERE id=%s",
                       GetSQLValueString($_POST['flightTable'], "text"),
					   GetSQLValueString($flight_number, "text"),
					   GetSQLValueString($id, "text")
					   );

			//echo $updateSQL."<br>";
			mysql_select_db($database, $brewing);
  			$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		}
		if ($id > "999999"){
			$insertSQL = sprintf("INSERT INTO judging_flights (
			flightTable, 
			flightNumber, 
			flightEntryID
  			) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['flightTable'], "text"),
					   GetSQLValueString($flight_number, "text"),
					   GetSQLValueString($_POST['flightEntryID'.$id], "text")
					   );

			//echo $insertSQL."<br>";
			mysql_select_db($database, $brewing);
  			$Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
		}
  	}
	header(sprintf("Location: %s", $updateGoTo));
}

if ($action == "assign") {
	foreach (array_unique($_POST['id']) as $a) {
	
	// Check to see if round has changed for the table/flight.
	if ($_POST['flightRound'.$a] != $_POST['flightRoundPrevious'.$a]) {
	
	// If so, delete all judging/steward assignments for the "old" round
		$query_assignments = sprintf("SELECT id FROM judging_assignments WHERE assignTable='%s' AND assignFlight='%s' AND assignRound='%s' ORDER BY id", $_POST['flightTable'.$a],$_POST['flightNumber'.$a],$_POST['flightRoundPrevious'.$a]);
		$assignments = mysql_query($query_assignments, $brewing) or die(mysql_error());
		$row_assignments = mysql_fetch_assoc($assignments);
		$totalRows_assignments = mysql_num_rows($assignments);
		//echo $query_assignments."<br>";
		if ($totalRows_assignments > 0) {
			do {	
		 		$deleteAssignment = sprintf("DELETE FROM judging_assignments WHERE id='%s'", $row_assignments['id']);
  				$Result = mysql_query($deleteAssignment, $brewing) or die(mysql_error());
				//echo $deleteAssignment.";<br>";
			} while ($row_assignments = mysql_fetch_assoc($assignments)); 
		}
		
		// Change the rounds for all affected table/flight assignments.	
	
		$query_flights = sprintf("SELECT id FROM judging_flights WHERE flightTable='%s' AND flightNumber='%s' ORDER BY id", $_POST['flightTable'.$a],$_POST['flightNumber'.$a]);
		$flights = mysql_query($query_flights, $brewing) or die(mysql_error());
		$row_flights = mysql_fetch_assoc($flights);
		//echo $query_flights."<br>";
		do {
		$updateSQL = sprintf("UPDATE judging_flights SET flightRound=%s WHERE id=%s", 
			GetSQLValueString($_POST['flightRound'.$a], "text"), 
			GetSQLValueString($row_flights['id'], "int")
			);
		mysql_select_db($database, $brewing);
  		$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		//echo $updateSQL.";<br>";
		} while ($row_flights = mysql_fetch_assoc($flights));
		}
 	}
	header(sprintf("Location: %s", $updateGoTo));
}
?>