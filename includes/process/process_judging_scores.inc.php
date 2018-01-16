<?php
/*
 * Module:      process_judging_scores.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "judging_scores" table
 */

if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))) {

		if (($action == "add") || ($action == "edit")) {

			// First, wipe out all previously recorded scores for the table
			$deleteSQL = sprintf("DELETE FROM %s WHERE scoreTable='%s'", $judging_scores_db_table, $id);
			mysqli_real_escape_string($connection,$deleteSQL);
			$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));

			foreach($_POST['score_id'] as $score_id)	{

				// Second, get rid of any duplicates, just in case they're in there
				$query_delete_assign = sprintf("SELECT id FROM $judging_scores_db_table WHERE eid='%s'", $_POST['eid'.$score_id]);
				$delete_assign = mysqli_query($connection,$query_delete_assign) or die (mysqli_error($connection));
				$row_delete_assign = mysqli_fetch_assoc($delete_assign);
				$totalRows_delete_assign = mysqli_num_rows($delete_assign);

				if ($totalRows_delete_assign > 0) {

					do { $delete_id[] = $row_delete_assign['id']; } while ($row_delete_assign = mysqli_fetch_assoc($delete_assign));

					foreach($delete_id as $previous_id) {

						$deleteSQL = sprintf("DELETE FROM %s WHERE id='%s'", $judging_scores_db_table, $previous_id);
						mysqli_real_escape_string($connection,$deleteSQL);
						$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));

					}
				}


				if ((!empty($_POST['scoreEntry'.$score_id])) || (!empty($_POST['scoreMiniBOS'.$score_id])) || (!empty($_POST['scorePlace'.$score_id]))) {

					if (!empty($_POST['scoreMiniBOS'.$score_id])) $score_mini_bos = $_POST['scoreMiniBOS'.$score_id];
					else $score_mini_bos = 0;

					$insertSQL = sprintf("INSERT INTO $judging_scores_db_table (
					eid,
					bid,
					scoreTable,
					scoreEntry,
					scorePlace,
					scoreType,
					scoreMiniBOS
					) VALUES (%s, %s, %s, %s, %s, %s, %s);",
									   GetSQLValueString(sterilize($_POST['eid'.$score_id]), "text"),
									   GetSQLValueString(sterilize($_POST['bid'.$score_id]), "text"),
									   GetSQLValueString(sterilize($_POST['scoreTable'.$score_id]), "text"),
									   GetSQLValueString(sterilize($_POST['scoreEntry'.$score_id]), "text"),
									   GetSQLValueString(sterilize($_POST['scorePlace'.$score_id]), "text"),
									   GetSQLValueString(sterilize($_POST['scoreType'.$score_id]), "text"),
									   GetSQLValueString(sterilize($score_mini_bos), "int")
									   );

					mysqli_real_escape_string($connection,$insertSQL);
					$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));
				}
			}

			$pattern = array('\'', '"');
			$insertGoTo = str_replace($pattern, "", $insertGoTo);
			$redirect_go_to = sprintf("Location: %s", stripslashes($insertGoTo));
		}

} else {
	$redirect_go_to = sprintf("Location: %s", $base_url."index.php?msg=98");
}

?>