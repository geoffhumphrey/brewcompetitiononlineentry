<?php 
/*
 * Module:      process_judging_tables.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "judging_tables" table
 */

if ($action == "add") {
	if ($_POST['tableStyles'] != "") $table_styles = implode(",",$_POST['tableStyles']); else $table_styles = $_POST['tableStyles'];

	$insertSQL = sprintf("INSERT INTO $judging_tables_db_table (
	tableName, 
	tableStyles, 
	tableNumber,
	tableLocation
  	) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString(capitalize($_POST['tableName']), "text"),
					   GetSQLValueString($table_styles, "text"),
					   GetSQLValueString($_POST['tableNumber'], "text"),
					   GetSQLValueString($_POST['tableLocation'], "text")
					   );

	mysql_select_db($database, $brewing);
  	$Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
	
	$query_table = "SELECT id,tableLocation FROM $judging_tables_db_table ORDER BY id DESC LIMIT 1";
	$table = mysql_query($query_table, $brewing) or die(mysql_error());
	$row_table = mysql_fetch_assoc($table);
	
	$query_table_rounds = sprintf("SELECT judgingRounds FROM $judging_locations_db_table WHERE id='%s'", $row_table['tableLocation']);
	$table_rounds = mysql_query($query_table_rounds, $brewing) or die(mysql_error());
	$row_table_rounds = mysql_fetch_assoc($table_rounds);
	if ($row_table_rounds['judgingRounds'] == 1) $rounds = "1"; else $rounds = "";
	
	// Add all entries affected entries to Flight1
	
	$a = explode(",",$table_styles);
	
	foreach (array_unique($a) as $value) {
	
		$query_styles = sprintf("SELECT brewStyleGroup, brewStyleNum FROM $styles_db_table WHERE id='%s'", $value);
		$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
		$row_styles = mysql_fetch_assoc($styles);
		
		$query_entries = sprintf("SELECT id FROM $brewing_db_table WHERE brewCategorySort='%s' AND brewSubCategory='%s' AND brewReceived='Y'", $row_styles['brewStyleGroup'],$row_styles['brewStyleNum']);
		$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
		$row_entries = mysql_fetch_assoc($entries);
		
		do {
			
			$insertSQL = sprintf("INSERT INTO $judging_flights_db_table (
				flightTable, 
				flightNumber, 
				flightEntryID,
				flightRound
  				) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($row_table['id'], "text"),
					   GetSQLValueString("1", "text"),
					   GetSQLValueString($row_entries['id'], "text"),
					   GetSQLValueString($rounds, "text")
					   );

			//echo $insertSQL."<br>";
			mysql_select_db($database, $brewing);
  			$Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
			
		} while ($row_entries = mysql_fetch_assoc($entries));
	}
	if ($_POST['tableStyles'] != "") $insertGoTo = $insertGoTo; else $insertGoTo = $insertGoTo = $_POST['relocate']."&msg=13";
	header(sprintf("Location: %s", $insertGoTo));
}

if ($action == "edit") {
	if ($_POST['tableStyles'] != "") $table_styles = implode(",",$_POST['tableStyles']); else $table_styles = "";

	$updateSQL = sprintf("UPDATE $judging_tables_db_table SET 
	tableName=%s, 
	tableStyles=%s, 
	tableNumber=%s,
	tableLocation=%s
	WHERE id=%s",
                    
	GetSQLValueString(capitalize($_POST['tableName']), "text"),
	GetSQLValueString($table_styles, "text"),
	GetSQLValueString($_POST['tableNumber'], "text"),
	GetSQLValueString($_POST['tableLocation'], "text"),
	GetSQLValueString($id, "text"));

  	mysql_select_db($database, $brewing);
  	$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
  
  	// Check to see if flights have been designated already
  	$query_flight_count = sprintf("SELECT id,flightEntryID FROM $judging_flights_db_table WHERE flightTable='%s'", $id);
	$flight_count = mysql_query($query_flight_count, $brewing) or die(mysql_error());
	$row_flight_count = mysql_fetch_assoc($flight_count);
	$totalRows_flight_count = mysql_num_rows($flight_count);
	
	//echo "<p>".$totalRows_flight_count."<p>";
  	
	// If flights are designated and the Table's styles have changed, loop through the judging_flights and update or remove the affected entries
  	if (($totalRows_flight_count > 0) && ($table_styles != "")) {
		
		$query_flight_round = sprintf("SELECT flightRound FROM $judging_flights_db_table WHERE flightTable='%s' ORDER BY flightRound DESC LIMIT 1", $id);
		$flight_round = mysql_query($query_flight_round, $brewing) or die(mysql_error());
		$row_flight_round = mysql_fetch_assoc($flight_round);
		
		$a = explode(",",$table_styles);
		
		$query_table_styles = "SELECT id,tableStyles FROM $judging_tables_db_table";
		$table_styles = mysql_query($query_table_styles, $brewing) or die(mysql_error());
		$row_table_styles = mysql_fetch_assoc($table_styles);
					
		do { $t[] = $row_table_styles['id']; } while ($row_table_styles = mysql_fetch_assoc($table_styles));
	
	// Update or remove	
		do { $f[] = $row_flight_count['id']; } while ($row_flight_count = mysql_fetch_assoc($flight_count)); 
		
		foreach ($f as $id) {
			unset($update);
			unset($b);	
			
			$query_entry_style = sprintf("SELECT flightEntryID FROM $judging_flights_db_table WHERE id='%s'", $id);
			$entry_style = mysql_query($query_entry_style, $brewing) or die(mysql_error());
			$row_entry_style = mysql_fetch_assoc($entry_style);
			//echo $query_entry_style."<br>";
			
			$query_entry = sprintf("SELECT brewCategorySort,brewSubCategory FROM $brewing_db_table WHERE id='%s'", $row_entry_style['flightEntryID']);
			$entry = mysql_query($query_entry, $brewing) or die(mysql_error());
			$row_entry = mysql_fetch_assoc($entry);
			//echo $query_entry."<br>";
			
			foreach ($t as $table_id) {
				
				$query_table_style = sprintf("SELECT id,tableStyles FROM $judging_tables_db_table WHERE id='%s'", $table_id);
				$table_style = mysql_query($query_table_style, $brewing) or die(mysql_error());
				$row_table_style = mysql_fetch_assoc($table_style);
				//echo $query_table_style."<br>";
				
				$query_style = sprintf("SELECT id FROM $styles_db_table WHERE brewStyleGroup='%s' AND brewStyleNum='%s'", $row_entry['brewCategorySort'],$row_entry['brewSubCategory']);
				$style = mysql_query($query_style, $brewing) or die(mysql_error());
				$row_style = mysql_fetch_assoc($style);
				//echo $query_style."<br>";
				
				$array = explode(",",$row_table_style['tableStyles']);
				//print_r($array);
				//echo "<br>";
				if (in_array($row_style['id'],$array)) $update[] = $row_table_style['id']; else $update[] = "N";
			}
			
			$query_flight_info = sprintf("SELECT id,flightEntryID,flightTable FROM $judging_flights_db_table WHERE id='%s'", $id);
			$flight_info = mysql_query($query_flight_info, $brewing) or die(mysql_error());
			$row_flight_info = mysql_fetch_assoc($flight_info);
			$totalRows_flight_info = mysql_num_rows($flight_info);
			
			$query_entry = sprintf("SELECT brewCategorySort,brewSubCategory FROM $brewing_db_table WHERE id='%s'", $row_flight_info['flightEntryID']);
			$entry = mysql_query($query_entry, $brewing) or die(mysql_error());
			$row_entry = mysql_fetch_assoc($entry);
			
			$entry_style = $row_entry['brewCategorySort'].$row_entry['brewSubCategory'];
			
			//echo "<p>".$query_entry."<br>";
			
			foreach ($a as $style_id) {
				
				$b[] = 0;
				
				$query_style = sprintf("SELECT brewStyleGroup,brewStyleNum FROM $styles_db_table WHERE id='%s'", $style_id);
				$style = mysql_query($query_style, $brewing) or die(mysql_error());
				$row_style = mysql_fetch_assoc($style);
			
				//echo $query_style."<br>";
				
				$table_style = $row_style['brewStyleGroup'].$row_style['brewStyleNum'];
				
				//echo "Table Style: ".$table_style."<br>";
				//echo "Entry Style: ".$entry_style."<br>";
				
				if ($table_style == $entry_style) $b[] = 1;
				if ($table_style != $entry_style) $b[] = 0;
			}
			
			$update = array_filter($update,"is_numeric");
			$update = implode("",$update);
			$delete = array_sum($b);
			
			//echo $update."<br>";
			//echo $delete."<br>";
			
			// Delete if no table found that contains the entry's style
			if (($delete == 0) && ($update == "")) {
				$delete = sprintf("DELETE FROM $judging_flights_db_table WHERE id='%s'", $row_flight_info['id']);
				//echo $delete."<br>";
  				mysql_select_db($database, $brewing);
  				$Result = mysql_query($delete, $brewing) or die(mysql_error());	
			}
			
			// If table style is assigned to another table, reassign the entry to that table
			if (($delete == 0) && ($update != "")) {
				$updateSQL = sprintf("UPDATE $judging_flights_db_table SET
					flightTable=%s, 
					flightNumber=%s, 
					flightRound=%s
					WHERE id=%s",
                       GetSQLValueString($update, "text"),
                       GetSQLValueString("1", "text"),
                       GetSQLValueString("1", "text"),
                       GetSQLValueString($id, "text"));
				//echo $updateSQL."<br>";
  				mysql_select_db($database_brewing, $brewing);
  				$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());	
			}
		}
	} //end if (($row_flight_count['count'] > 0) && ($table_styles != ""))
	
	// Remove all flight rows if unassigning all present table styles
	if (($totalRows_flight_count > 0) && ($table_styles == "")) {
		do { $a[] = $row_flight_count['id']; } while ($row_flight_count = mysql_fetch_assoc($flight_count));
		foreach ($a as $id) {
			$delete = sprintf("DELETE FROM $judging_flights_db_table WHERE id='%s'", $id);
  			mysql_select_db($database, $brewing);
  			$Result = mysql_query($delete, $brewing) or die(mysql_error());
			}
	} // end if (($totalRows_flight_count > 0) && ($table_styles == ""))
  
  header(sprintf("Location: %s", $updateGoTo));
}

?>