<?php 
/*
 * Module:      process_judging_bos.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "judging_scores_bos" table
 */

if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))) {
	
	if ($action == "enter") {
		foreach($_POST['score_id'] as $score_id)	{
			
			if ((!empty($_POST['scorePlace'.$score_id])) && ($_POST['scorePrevious'.$score_id] == "Y")) {
				$updateSQL = sprintf("UPDATE $judging_scores_bos_db_table SET
				eid=%s,
				bid=%s,
				scoreEntry=%s,
				scorePlace=%s,
				scoreType=%s
				WHERE id=%s",
								   GetSQLValueString(sterilize($_POST['eid'.$score_id]), "text"),
								   GetSQLValueString(sterilize($_POST['bid'.$score_id]), "text"),
								   GetSQLValueString(sterilize($_POST['scoreEntry'.$score_id]), "text"),
								   GetSQLValueString(sterilize($_POST['scorePlace'.$score_id]), "text"),
								   GetSQLValueString(sterilize($_POST['scoreType'.$score_id]), "text"),
								   GetSQLValueString(sterilize($_POST['id'.$score_id]), "text")
								   );
			
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				
			}
			
			if ((!empty($_POST['scorePlace'.$score_id])) && ($_POST['scorePrevious'.$score_id] == "N")) {
				$insertSQL = sprintf("INSERT INTO $judging_scores_bos_db_table (
				eid, 
				bid, 
				scoreEntry,
				scorePlace,
				scoreType
				) VALUES (%s, %s, %s, %s, %s)",
								   GetSQLValueString(sterilize($_POST['eid'.$score_id]), "text"),
								   GetSQLValueString(sterilize($_POST['bid'.$score_id]), "text"),
								   GetSQLValueString(sterilize($_POST['scoreEntry'.$score_id]), "text"),
								   GetSQLValueString(sterilize($_POST['scorePlace'.$score_id]), "text"),
								   GetSQLValueString(sterilize($_POST['scoreType'.$score_id]), "text")
								   );
			
				mysqli_real_escape_string($connection,$insertSQL);
				$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));	
				
			}
			
			if ((empty($_POST['scorePlace'.$score_id])) && ($_POST['scorePrevious'.$score_id] == "Y")) {
				
				$deleteSQL = sprintf("DELETE FROM $judging_scores_bos_db_table WHERE id='%s'", $_POST['id'.$score_id]);
				mysqli_real_escape_string($connection,$deleteSQL);
				$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
				
			}
			
			
			
		}
		$pattern = array('\'', '"');
		$updateGoTo = str_replace($pattern, "", $updateGoTo); 
		header(sprintf("Location: %s", stripslashes($updateGoTo)));
		
	} // end if ($action == "enter")
		
} else { 
	header(sprintf("Location: %s", $base_url."index.php?msg=98"));
	exit;
}
?>