<?php 
/*
 * Module:      process_delete.inc.php
 * Description: This module does all the heavy lifting for all DB deletes: new entries,
 *              new users, organization, etc.
 */

if ($go == "judging_location") {
  // remove relational location ids from affected rows in brewer's table
	mysql_select_db($database, $brewing);
	$query_loc = "SELECT * from $brewer_db_table WHERE brewerJudgeLocation='$id'";
	$loc = mysql_query($query_loc, $brewing) or die(mysql_error());
	$row_loc = mysql_fetch_assoc($loc);
	$totalRows_loc = mysql_num_rows($loc);
  	if ($totalRows_loc > 0) {
  		do  {
  		$updateSQL = "UPDATE $brewer_db_table SET brewerJudgeLocation=NULL WHERE id='".$row_loc['id']."'; ";
		mysql_select_db($database, $brewing);
  		$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		}
  	while($row_loc = mysql_fetch_assoc($loc));
}
  
	$query_loc = "SELECT * FROM $brewer_db_table WHERE brewerJudgeLocation2='$id'";
	$loc = mysql_query($query_loc, $brewing) or die(mysql_error());
	$row_loc = mysql_fetch_assoc($loc);
	$totalRows_loc = mysql_num_rows($loc);
	if ($totalRows_loc > 0) {
  		do  {
  		$updateSQL = "UPDATE $brewer_db_table SET brewerJudgeLocation2=NULL WHERE id='".$row_loc['id']."'; ";
		mysql_select_db($database, $brewing);
  		$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		}
  	while($row_loc = mysql_fetch_assoc($loc));
	}
  
	$query_loc = "SELECT * FROM $brewer_db_table WHERE brewerStewardLocation='$id'";
	$loc = mysql_query($query_loc, $brewing) or die(mysql_error());
	$row_loc = mysql_fetch_assoc($loc);
	$totalRows_loc = mysql_num_rows($loc);
	if ($totalRows_loc > 0) {
  		do  {
  		$updateSQL = "UPDATE $brewer_db_table SET brewerStewardLocation=NULL WHERE id='".$row_loc['id']."'; ";
		mysql_select_db($database, $brewing);
  		$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		}
  	while($row_loc = mysql_fetch_assoc($loc));
	}
  
	$query_loc = "SELECT * FROM $brewer_db_table WHERE brewerStewardLocation2='$id'";
	$loc = mysql_query($query_loc, $brewing) or die(mysql_error());
	$row_loc = mysql_fetch_assoc($loc);
	$totalRows_loc = mysql_num_rows($loc);
	if ($totalRows_loc > 0) {
  		do  {
  		$updateSQL = "UPDATE $brewer_db_table SET brewerStewardLocation2=NULL WHERE id='".$row_loc['id']."'; ";
		mysql_select_db($database, $brewing);
  		$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		}
  	while($row_loc = mysql_fetch_assoc($loc));
  	}
} // end if ($go == "judging_location")


if ($go == "participants") {
  	
	if ($uid != "") {
		mysql_select_db($database, $brewing);
		$query_delete_brewer = sprintf("SELECT id FROM $users_db_table WHERE id='%s'", $uid);
		$delete_brewer = mysql_query($query_delete_brewer, $brewing) or die(mysql_error()); 
		$row_delete_brewer = mysql_fetch_assoc($delete_brewer);
		
		$deleteUser = sprintf("DELETE FROM $users_db_table WHERE id='%s'", $row_delete_brewer['id']);
		mysql_select_db($database, $brewing);
		$Result = mysql_query($deleteUser, $brewing) or die(mysql_error());
		
		$deleteBrewer = sprintf("DELETE FROM $brewer_db_table WHERE uid='%s'", $row_delete_brewer['id']);
		mysql_select_db($database, $brewing);
		$Result = mysql_query($deleteBrewer, $brewing) or die(mysql_error());
		
		$query_entries = sprintf("SELECT id from brewing WHERE brewBrewerID='%s'", $row_delete_brewer['id']);
		$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
		$row_entries = mysql_fetch_assoc($entries);
	  
		do { $a[] = $row_entries['id']; } while ($row_entries = mysql_fetch_assoc($entries));
		
			sort($a);
			
			foreach ($a as $id) { 
			$deleteEntries = sprintf("DELETE FROM $brewing_db_table WHERE id='%s'", $id);
			mysql_select_db($database, $brewing);
			$Result = mysql_query($deleteEntries, $brewing) or die(mysql_error());
			}
	} else {
		$deleteBrewer = sprintf("DELETE FROM $brewer_db_table WHERE id='%s'", $id);
		mysql_select_db($database, $brewing);
		$Result = mysql_query($deleteBrewer, $brewing) or die(mysql_error());
	}
} // end if ($go == "participants")
	
if ($go == "entries") {
	mysql_select_db($database, $brewing);
	$query_delete_entry = sprintf("SELECT id FROM $judging_scores_db_table WHERE eid='%s'", $id);
	$delete_entry = mysql_query($query_delete_entry, $brewing) or die(mysql_error()); 
	$row_delete_entry = mysql_fetch_assoc($delete_entry);
	
	$deleteScore = sprintf("DELETE FROM $judging_scores_db_table WHERE id='%s'", $row_delete_entry['id']);
	$Result = mysql_query($deleteScore, $brewing) or die(mysql_error());
} // end if ($go == "entries") 
	
if ($go == "judging_tables") {
	mysql_select_db($database, $brewing);
	
	$query_delete_assign = sprintf("SELECT id FROM $judging_scores_db_table WHERE scoreTable='%s'", $id);
	$delete_assign = mysql_query($query_delete_assign, $brewing) or die(mysql_error()); 
	$row_delete_assign = mysql_fetch_assoc($delete_assign);
	$totalRows_delete_assign = mysql_num_rows($delete_assign);
	
	if ($totalRows_delete_assign > 0) {
		do { $z[] = $row_delete_assign['id']; } while (mysql_fetch_assoc($delete_assign));
	
		foreach ($z as $aid) {
		$deleteAssign = sprintf("DELETE FROM $judging_assignments_db_table WHERE id='%s'", $aid);
		$Result = mysql_query($deleteAssign, $brewing) or die(mysql_error());
		}
	
		$query_delete_scores = sprintf("SELECT id,eid FROM $judging_scores_db_table WHERE scoreTable='%s'", $id);
		$delete_scores = mysql_query($query_delete_scores, $brewing) or die(mysql_error()); 
		$row_delete_scores = mysql_fetch_assoc($delete_scores);
		
		do { $a[] = $row_delete_scores['id']; $c[] = $row_delete_scores['eid']; } while ($row_delete_scores = mysql_fetch_assoc($delete_scores));
		
		foreach ($a as $sid) {
			$deleteScore = sprintf("DELETE FROM $judging_scores_db_table WHERE id='%s'", $sid);
			$Result = mysql_query($deleteScore, $brewing) or die(mysql_error());
			}
	}
	$query_delete_flights = sprintf("SELECT id,flightTable FROM $judging_flights_db_table WHERE flightTable='%s'", $id);
	$delete_flights = mysql_query($query_delete_flights, $brewing) or die(mysql_error()); 
	$row_delete_flights = mysql_fetch_assoc($delete_flights);
	$totalRows_delete_flights = mysql_num_rows($delete_flights);
	
	if ($totalRows_delete_flights > 0) {
		do { $b[] = $row_delete_flights['id']; } while ($row_delete_flights = mysql_fetch_assoc($delete_flights));
		
		foreach ($b as $fid) {
			$deleteFlight = sprintf("DELETE FROM $judging_flights_db_table WHERE id='%s'", $fid);
			$Result = mysql_query($deleteFlight, $brewing) or die(mysql_error());
			}
		if ($c != "") {
		foreach ($c as $eid) {
			
			$query_delete_bos = sprintf("SELECT id,eid FROM $judging_scores_bos_db_table WHERE eid='%s'", $eid);
			$delete_bos = mysql_query($query_delete_bos, $brewing) or die(mysql_error()); 
			$row_delete_bos = mysql_fetch_assoc($delete_bos);
			if ($eid == $row_delete_bos['eid']) {
				$deleteBOS = sprintf("DELETE FROM $judging_scores_bos_db_table WHERE id='%s'", $row_delete_bos['id']);
				$Result = mysql_query($deleteScore, $brewing) or die(mysql_error());
				}
			  }
			}
	 	}
  } // end if ($go == "judging_tables") 
  
  $deleteSQL = sprintf("DELETE FROM $dbTable WHERE id='%s'", $id);
  $Result1 = mysql_query($deleteSQL, $brewing) or die(mysql_error());
  
if ($dbTable == "archive") { 
  $dropTable = "DROP TABLE users_$filter";
  $Result = mysql_query($dropTable, $brewing) or die(mysql_error());
  
  $dropTable = "DROP TABLE brewing_$filter";
  $Result = mysql_query($dropTable, $brewing) or die(mysql_error());
  
  $dropTable = "DROP TABLE brewer_$filter";
  $Result = mysql_query($dropTable, $brewing) or die(mysql_error());
  
  $dropTable = "DROP TABLE sponsors_$filter";
  $Result = mysql_query($dropTable, $brewing) or die(mysql_error());
  
  $dropTable = "DROP TABLE judging_assignments_$filter";
  $Result = mysql_query($dropTable, $brewing) or die(mysql_error());
  
  $dropTable = "DROP TABLE judging_flights_$filter";
  $Result = mysql_query($dropTable, $brewing) or die(mysql_error());
  
  $dropTable = "DROP TABLE judging_scores_$filter";
  $Result = mysql_query($dropTable, $brewing) or die(mysql_error());
  
  $dropTable = "DROP TABLE judging_scores_bos_$filter";
  $Result = mysql_query($dropTable, $brewing) or die(mysql_error());
  
  $dropTable = "DROP TABLE judging_tables_$filter";
  $Result = mysql_query($dropTable, $brewing) or die(mysql_error());
  
  $dropTable = "DROP TABLE style_types_$filter";
  $Result = mysql_query($dropTable, $brewing) or die(mysql_error());
  
  header(sprintf("Location: %s", $deleteGoTo));
}
  
if ($dbTable != "archive") { 
  header(sprintf("Location: %s", $deleteGoTo));
}

?>