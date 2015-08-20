<?php 
/*
 * Module:      process_judging_scores.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "judging_scores" table
 */

if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {
	
	if (NHC) {
		// Place NHC SQL calls below
		
		
	}
	// end if (NHC)
	
	else {
		if (($action == "add") || ($action == "edit")) {
			
			// First, wipe out all previously recorded scores for the table
			$deleteScores = sprintf("DELETE FROM %s WHERE scoreTable='%s'", $judging_scores_db_table, $id);
			echo $deleteScores."<br>";
			mysql_real_escape_string($deleteScores);
			$result = mysql_query($deleteScores, $brewing);
						
			foreach($_POST['score_id'] as $score_id)	{
				
				// Second, get rid of any duplicates, just in case they're in there
				$query_delete_assign = sprintf("SELECT id FROM $judging_scores_db_table WHERE eid='%s'", $_POST['eid'.$score_id]);
				$delete_assign = mysql_query($query_delete_assign, $brewing) or die(mysql_error()); 
				$row_delete_assign = mysql_fetch_assoc($delete_assign);
				$totalRows_delete_assign = mysql_num_rows($delete_assign);
				
				if ($totalRows_delete_assign > 0) {
					
					do { $delete_id[] = $row_delete_assign['id']; } while ($row_delete_assign = mysql_fetch_assoc($delete_assign));
					
					foreach($delete_id as $previous_id) {
						
						$deleteScore = sprintf("DELETE FROM %s WHERE id='%s'", $judging_scores_db_table, $previous_id);
						echo $deleteScore."<br>";
						mysql_real_escape_string($deleteScore);
						$result = mysql_query($deleteScore, $brewing);
						
					}
				}
				
				
				if (($_POST['scoreEntry'.$score_id] != "") || ($_POST['scorePlace'.$score_id] != "")) {
				$insertSQL = sprintf("INSERT INTO $judging_scores_db_table (
				eid, 
				bid, 
				scoreTable,
				scoreEntry,
				scorePlace,
				scoreType,
				scoreMiniBOS
				) VALUES (%s, %s, %s, %s, %s, %s, %s);",
								   GetSQLValueString($_POST['eid'.$score_id], "text"),
								   GetSQLValueString($_POST['bid'.$score_id], "text"),
								   GetSQLValueString($_POST['scoreTable'.$score_id], "text"),
								   GetSQLValueString($_POST['scoreEntry'.$score_id], "text"),
								   GetSQLValueString($_POST['scorePlace'.$score_id], "text"),
								   GetSQLValueString($_POST['scoreType'.$score_id], "text"),
								   GetSQLValueString($_POST['scoreMiniBOS'.$score_id], "int")
								   );
				
				//echo $insertSQL."<br>";
				mysql_select_db($database, $brewing);
				mysql_real_escape_string($insertSQL);
				$result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
				}
			}
			//exit;
			$pattern = array('\'', '"');
			$insertGoTo = str_replace($pattern, "", $insertGoTo); 
			header(sprintf("Location: %s", stripslashes($insertGoTo)));
		}
		
		
		
		
		
		
		/*
		if ($action == "edit") {
			foreach($_POST['score_id'] as $score_id)	{
				if ((($_POST['scoreEntry'.$score_id] != "") || ($_POST['scorePlace'.$score_id] != "")) && ($_POST['scorePrevious'.$score_id] == "Y")) {
					
				// First, get rid of any duplicates
				$query_delete_assign = sprintf("SELECT id FROM $judging_scores_db_table WHERE eid='%s'", $_POST['eid'.$score_id]);
				$delete_assign = mysql_query($query_delete_assign, $brewing) or die(mysql_error()); 
				$row_delete_assign = mysql_fetch_assoc($delete_assign);
				$totalRows_delete_assign = mysql_num_rows($delete_assign);
				
				if ($totalRows_delete_assign > 0) {
					
					do { $delete_id[] = $row_delete_assign['id']; } while ($row_delete_assign = mysql_fetch_assoc($delete_assign));
					
					foreach($delete_id as $previous_id) {
						
						if ($previous_id != $score_id) {
							$deleteScore = sprintf("DELETE FROM %s WHERE id='%s'", $judging_scores_db_table, $previous_id);
							echo $deleteScore."<br>";
							mysql_real_escape_string($deleteScore);
							//$Result = mysql_query($deleteScore, $brewing);
						}
						
					}
				}
					
				$updateSQL = sprintf("UPDATE $judging_scores_db_table SET
				eid=%s,
				bid=%s,
				scoreTable=%s,
				scoreEntry=%s,
				scorePlace=%s,
				scoreType=%s,
				scoreMiniBOS=%s
				WHERE id=%s;",
								   GetSQLValueString($_POST['eid'.$score_id], "text"),
								   GetSQLValueString($_POST['bid'.$score_id], "text"),
								   GetSQLValueString($_POST['scoreTable'.$score_id], "text"),
								   GetSQLValueString($_POST['scoreEntry'.$score_id], "text"),
								   GetSQLValueString($_POST['scorePlace'.$score_id], "text"),
								   GetSQLValueString($_POST['scoreType'.$score_id], "text"),
								   GetSQLValueString($_POST['scoreMiniBOS'.$score_id], "int"),
								   GetSQLValueString($score_id, "text")
								   );
				
				echo $updateSQL."<br>";
				mysql_select_db($database, $brewing);
				mysql_real_escape_string($updateSQL);
				//$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
				
			}
			exit;
			if ((($_POST['scoreEntry'.$score_id] != "") || ($_POST['scorePlace'.$score_id] != "")) && ($_POST['scorePrevious'.$score_id] == "N")) {
				
				// First, get rid of any duplicates
				$query_delete_assign = sprintf("SELECT id FROM $judging_scores_db_table WHERE eid='%s'", $_POST['eid'.$score_id]);
				$delete_assign = mysql_query($query_delete_assign, $brewing) or die(mysql_error()); 
				$row_delete_assign = mysql_fetch_assoc($delete_assign);
				$totalRows_delete_assign = mysql_num_rows($delete_assign);
				
				if ($totalRows_delete_assign > 0) {
					
					do { $delete_id[] = $row_delete_assign['id']; } while ($row_delete_assign = mysql_fetch_assoc($delete_assign));
					
					foreach($delete_id as $previous_id) {
						
						$deleteScore = sprintf("DELETE FROM %s WHERE id='%s'", $judging_scores_db_table, $previous_id);
						mysql_real_escape_string($deleteScore);
						$Result = mysql_query($deleteScore, $brewing);
						
					}
				}
				
				
				$insertSQL = sprintf("INSERT INTO $judging_scores_db_table (
				eid, 
				bid, 
				scoreTable,
				scoreEntry,
				scorePlace,
				scoreType,
				scoreMiniBOS
				) VALUES (%s, %s, %s, %s, %s, %s, %s)",
								   GetSQLValueString($_POST['eid'.$score_id], "text"),
								   GetSQLValueString($_POST['bid'.$score_id], "text"),
								   GetSQLValueString($_POST['scoreTable'.$score_id], "text"),
								   GetSQLValueString($_POST['scoreEntry'.$score_id], "text"),
								   GetSQLValueString($_POST['scorePlace'.$score_id], "text"),
								   GetSQLValueString($_POST['scoreType'.$score_id], "text"),
								   GetSQLValueString($_POST['scoreMiniBOS'.$score_id], "int")
								   );
				
				echo $insertSQL."<br>";
				mysql_select_db($database, $brewing);
				mysql_real_escape_string($insertSQL);
				$result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());		
			}
			
			if ((($_POST['scoreEntry'.$score_id] == "") && ($_POST['scorePlace'.$score_id] == "")) && ($_POST['scorePrevious'.$score_id] == "Y")) {
				$deleteScore = sprintf("DELETE FROM $judging_scores_db_table WHERE id='%s'", $score_id);
				
				//echo $deleteScore;	
				mysql_real_escape_string($deleteScore);
				$result1 = mysql_query($deleteScore, $brewing) or die(mysql_error());
			}
		
		}
		//exit;
		$pattern = array('\'', '"');
		$updateGoTo = str_replace($pattern, "", $updateGoTo); 
		header(sprintf("Location: %s", stripslashes($updateGoTo)));
		}
		*/
		
		
		
		
		
		
		
		
	} // end else NHC

} else echo "<p>Not available.</p>";

?>