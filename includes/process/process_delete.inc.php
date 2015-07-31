<?php 
/*
 * Module:      process_delete.inc.php
 * Description: This module does all the heavy lifting for all DB deletes: new entries,
 *              new users, organization, etc.
 */
 
require('../paths.php');
require(INCLUDES.'url_variables.inc.php');

if ((isset($_SESSION['loginUsername'])) && (isset($_SESSION['userLevel']))) { 

	if (NHC) {
		// Place NHC SQL calls below
		
		
	}
	// end if (NHC)
	
	else {
	
		if ($go == "special_best") {
			mysql_select_db($database, $brewing);
			
			$query_delete_assign = sprintf("SELECT id FROM $special_best_data_db_table WHERE sid='%s'", $id);
			$delete_assign = mysql_query($query_delete_assign, $brewing) or die(mysql_error()); 
			$row_delete_assign = mysql_fetch_assoc($delete_assign);
			$totalRows_delete_assign = mysql_num_rows($delete_assign);
			if ($totalRows_delete_assign > 0) {
				do { $z[] = $row_delete_assign['id']; } while ($row_delete_assign = mysql_fetch_assoc($delete_assign));
			
				foreach ($z as $aid) {
				$deleteAssign = sprintf("DELETE FROM $special_best_data_db_table WHERE id='%s'", $aid);
				//echo $deleteAssign."<br>";
				mysql_real_escape_string($deleteAssign);
				$Result = mysql_query($deleteAssign, $brewing) or die(mysql_error());
				}
			}
			
		}
		
		if ($go == "judging") {
		  // remove relational location ids from affected rows in brewer's table
			mysql_select_db($database, $brewing);
			$query_loc = "SELECT id,brewerJudgeLocation,brewerStewardLocation from $brewer_db_table";
			$loc = mysql_query($query_loc, $brewing) or die(mysql_error());
			$row_loc = mysql_fetch_assoc($loc);
			$totalRows_loc = mysql_num_rows($loc);
			
			do  {
				
				if ($row_loc['brewerJudgeLocation'] != "") {
				$a = explode(",",$row_loc['brewerJudgeLocation']);
					if ((in_array("Y-".$id,$a)) || (in_array("N-".$id,$a))) {
						foreach ($a as $b) {
							if ($b == "Y-".$id) $c[] = ""; 
							elseif ($b == "N-".$id) $c[] = "";
							else $c[] = $b.",";
						}
						$d = rtrim(implode("",$c),",");
						$updateSQL = "UPDATE $brewer_db_table SET brewerJudgeLocation='".$d."' WHERE id='".$row_loc['id']."'; ";
						mysql_select_db($database, $brewing);
						$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
						
						//echo $updateSQL."<br>";
						unset($c, $d);
					}
				unset($a);
				}
				
				if ($row_loc['brewerStewardLocation'] != "") {
				$e = explode(",",$row_loc['brewerStewardLocation']);
					if ((in_array("Y-".$id,$e)) || (in_array("N-".$id,$e))) {
						foreach ($e as $f) {
							
							if ($f == "Y-".$id) $g[] = ""; 
							elseif ($f == "N-".$id) $g[] = "";
							else $g[] = $f.",";
							
						}
						$h = rtrim(implode("",$g),",");
						$updateSQL = "UPDATE $brewer_db_table SET brewerStewardLocation='".$h."' WHERE id='".$row_loc['id']."'; ";
						mysql_select_db($database, $brewing);
						mysql_real_escape_string($updateSQL);
						$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
						
						//echo $updateSQL."<br>";
						unset($g, $h);
					}
				unset($e);
				}
			} while ($row_loc = mysql_fetch_assoc($loc));
			
		} // end if ($go == "judging")
		
		
		if ($go == "participants") {
			
			if ($uid != "") {
				mysql_select_db($database, $brewing);
				/*
				$query_delete_brewer = sprintf("SELECT id FROM $users_db_table WHERE id='%s'", $id);
				$delete_brewer = mysql_query($query_delete_brewer, $brewing) or die(mysql_error()); 
				$row_delete_brewer = mysql_fetch_assoc($delete_brewer);
				*/
				$deleteUser = sprintf("DELETE FROM $users_db_table WHERE id='%s'", $id);
				mysql_select_db($database, $brewing);
				mysql_real_escape_string($deleteUser);
				//echo $deleteUser."<br>";
				$Result = mysql_query($deleteUser, $brewing) or die(mysql_error());
				
				$deleteBrewer = sprintf("DELETE FROM $brewer_db_table WHERE uid='%s'", $id);
				mysql_select_db($database, $brewing);
				mysql_real_escape_string($deleteBrewer);
				//echo $deleteBrewer."<br>";
				$Result = mysql_query($deleteBrewer, $brewing) or die(mysql_error());
				
				if (NHC) {
					$deleteNHCEntrant = sprintf("DELETE FROM nhcentrant WHERE uid='%s' AND regionPrefix='%s'", $id,$prefix);
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($deleteNHCEntrant);
					$Result = mysql_query($deleteNHCEntrant, $brewing) or die(mysql_error());
				}
				
				$query_entries = sprintf("SELECT id from $brewing_db_table WHERE brewBrewerID='%s'",$id);
				$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
				$row_entries = mysql_fetch_assoc($entries);
			  
				do { $a[] = $row_entries['id']; } while ($row_entries = mysql_fetch_assoc($entries));
				
					sort($a);
					
					foreach ($a as $brew_id) { 
						$deleteEntries = sprintf("DELETE FROM $brewing_db_table WHERE id='%s'", $brew_id);
						mysql_select_db($database, $brewing);
						mysql_real_escape_string($deleteEntries);
						//echo $deleteEntries."<br>";
						$Result = mysql_query($deleteEntries, $brewing) or die(mysql_error()); 
						
						$deleteScore = sprintf("DELETE FROM $judging_scores_db_table WHERE eid='%s'", $brew_id);
						mysql_real_escape_string($deleteScore);
						$Result = mysql_query($deleteScore, $brewing) or die(mysql_error());
						
						$deleteScoreBOS = sprintf("DELETE FROM $judging_scores_bos_db_table WHERE eid='%s'", $brew_id);
						mysql_real_escape_string($deleteScoreBOS);
						$Result = mysql_query($deleteScoreBOS, $brewing) or die(mysql_error());
					}
				
				
				// Clear any Judging Assignments
				$query_judge_assign = sprintf("SELECT id from $judging_assignments_db_table WHERE bid='%s'",$id);
				$judge_assign = mysql_query($query_judge_assign, $brewing) or die(mysql_error());
				$row_judge_assign = mysql_fetch_assoc($judge_assign);
			  
				do { $b[] = $row_judge_assign['id']; } while ($row_judge_assign = mysql_fetch_assoc($judge_assign));
				
					sort($b);
					
					foreach ($b as $judge_id) { 
						$deleteEntries = sprintf("DELETE FROM $judging_assignments_db_table WHERE id='%s'", $judge_id);
						mysql_select_db($database, $brewing);
						mysql_real_escape_string($deleteEntries);
						//echo $deleteEntries."<br>";
						$Result = mysql_query($deleteEntries, $brewing) or die(mysql_error()); 
					}
				
				// Clear any Staff Assignments
				$query_staff_assign = sprintf("SELECT id from %s WHERE uid='%s'",$prefix."staff",$id);
				//echo $query_staff_assign;
				$staff_assign = mysql_query($query_staff_assign, $brewing) or die(mysql_error());
				$row_staff_assign = mysql_fetch_assoc($staff_assign);
			  
				do { $c[] = $row_staff_assign['id']; } while ($row_staff_assign = mysql_fetch_assoc($staff_assign));
				
					sort($c);
					
					foreach ($c as $staff_id) { 
						$deleteEntries = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."staff", $staff_id);
						mysql_select_db($database, $brewing);
						mysql_real_escape_string($deleteEntries);
						//echo $deleteEntries."<br>";
						$Result = mysql_query($deleteEntries, $brewing) or die(mysql_error()); 
					}
					
			} else {
				
				$deleteUser = sprintf("DELETE FROM $users_db_table WHERE id='%s'", $id);
				mysql_select_db($database, $brewing);
				mysql_real_escape_string($deleteUser);
				//echo $deleteUser."<br>";
				$Result = mysql_query($deleteUser, $brewing) or die(mysql_error());
				
				$deleteBrewer = sprintf("DELETE FROM $brewer_db_table WHERE uid='%s'", $id);
				mysql_select_db($database, $brewing);
				mysql_real_escape_string($deleteBrewer);
				//echo $deleteBrewer."<br>";
				$Result = mysql_query($deleteBrewer, $brewing) or die(mysql_error());
			}
			//exit;
		} // end if ($go == "participants")
			
		if ($go == "entries") {
			mysql_select_db($database, $brewing);
			$query_delete_entry = sprintf("SELECT id FROM $judging_scores_db_table WHERE eid='%s'", $id);
			$delete_entry = mysql_query($query_delete_entry, $brewing) or die(mysql_error()); 
			$row_delete_entry = mysql_fetch_assoc($delete_entry);
			
			$deleteScore = sprintf("DELETE FROM $judging_scores_db_table WHERE id='%s'", $row_delete_entry['id']);
			mysql_real_escape_string($deleteScore);
			$Result = mysql_query($deleteScore, $brewing) or die(mysql_error());
		} // end if ($go == "entries") 
			
		if ($go == "judging_tables") {
			mysql_select_db($database, $brewing);
			
			$query_delete_assign = sprintf("SELECT id FROM $judging_scores_db_table WHERE scoreTable='%s'", $id);
			$delete_assign = mysql_query($query_delete_assign, $brewing) or die(mysql_error()); 
			$row_delete_assign = mysql_fetch_assoc($delete_assign);
			$totalRows_delete_assign = mysql_num_rows($delete_assign);
			
			if ($totalRows_delete_assign > 0) {
				do { $z[] = $row_delete_assign['id']; } while ($row_delete_assign = mysql_fetch_assoc($delete_assign));
			
				foreach ($z as $aid) {
				$deleteAssign = sprintf("DELETE FROM $judging_assignments_db_table WHERE id='%s'", $aid);
				mysql_real_escape_string($deleteAssign);
				$Result = mysql_query($deleteAssign, $brewing) or die(mysql_error());
				}
			
				$query_delete_scores = sprintf("SELECT id,eid FROM $judging_scores_db_table WHERE scoreTable='%s'", $id);
				$delete_scores = mysql_query($query_delete_scores, $brewing) or die(mysql_error()); 
				$row_delete_scores = mysql_fetch_assoc($delete_scores);
				
				do { $a[] = $row_delete_scores['id']; $c[] = $row_delete_scores['eid']; } while ($row_delete_scores = mysql_fetch_assoc($delete_scores));
				
				foreach ($a as $sid) {
					$deleteScore = sprintf("DELETE FROM $judging_scores_db_table WHERE id='%s'", $sid);
					mysql_real_escape_string($deleteScore);
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
					mysql_real_escape_string($deleteFlight);
					$Result = mysql_query($deleteFlight, $brewing) or die(mysql_error());
					}
				if ($c != "") {
				foreach ($c as $eid) {
					
					$query_delete_bos = sprintf("SELECT id,eid FROM $judging_scores_bos_db_table WHERE eid='%s'", $eid);
					$delete_bos = mysql_query($query_delete_bos, $brewing) or die(mysql_error()); 
					$row_delete_bos = mysql_fetch_assoc($delete_bos);
					if ($eid == $row_delete_bos['eid']) {
						$deleteBOS = sprintf("DELETE FROM $judging_scores_bos_db_table WHERE id='%s'", $row_delete_bos['id']);
						mysql_real_escape_string($deleteScore);
					$Result = mysql_query($deleteScore, $brewing) or die(mysql_error());
						}
					  }
					}
				}
		  } // end if ($go == "judging_tables") 
		  
		  $deleteSQL = sprintf("DELETE FROM $dbTable WHERE id='%s'", $id);
		  mysql_real_escape_string($deleteSQL);
		  $result1 = mysql_query($deleteSQL, $brewing) or die(mysql_error());
		  
		if ($dbTable == "archive") { 
		  $dropTable = "DROP TABLE users_$filter";
		  mysql_real_escape_string($dropTable);
		  $Result = mysql_query($dropTable, $brewing) or die(mysql_error());
		  
		  $dropTable = "DROP TABLE brewing_$filter";
		  mysql_real_escape_string($dropTable);
		  $Result = mysql_query($dropTable, $brewing) or die(mysql_error());
		  
		  $dropTable = "DROP TABLE brewer_$filter";
		  mysql_real_escape_string($dropTable);
		  $Result = mysql_query($dropTable, $brewing) or die(mysql_error());
		  
		  $dropTable = "DROP TABLE sponsors_$filter";
		  mysql_real_escape_string($dropTable);
		  $Result = mysql_query($dropTable, $brewing) or die(mysql_error());
		  
		  $dropTable = "DROP TABLE judging_assignments_$filter";
		  mysql_real_escape_string($dropTable);
		  $Result = mysql_query($dropTable, $brewing) or die(mysql_error());
		  
		  $dropTable = "DROP TABLE judging_flights_$filter";
		  mysql_real_escape_string($dropTable);
		  $Result = mysql_query($dropTable, $brewing) or die(mysql_error());
		  
		  $dropTable = "DROP TABLE judging_scores_$filter";
		  mysql_real_escape_string($dropTable);
		  $Result = mysql_query($dropTable, $brewing) or die(mysql_error());
		  
		  $dropTable = "DROP TABLE judging_scores_bos_$filter";
		  mysql_real_escape_string($dropTable);
		  $Result = mysql_query($dropTable, $brewing) or die(mysql_error());
		  
		  $dropTable = "DROP TABLE judging_tables_$filter";
		  mysql_real_escape_string($dropTable);
		  $Result = mysql_query($dropTable, $brewing) or die(mysql_error());
		  
		  $dropTable = "DROP TABLE style_types_$filter";
		  mysql_real_escape_string($dropTable);
		  $Result = mysql_query($dropTable, $brewing) or die(mysql_error());
		  
		  header(sprintf("Location: %s", $deleteGoTo));
		}
		  
		if ($dbTable != "archive") { 
		  header(sprintf("Location: %s", $deleteGoTo));
		}
	
	} // end else NHC
	
} else echo "<p>Not available.</p>";
?>