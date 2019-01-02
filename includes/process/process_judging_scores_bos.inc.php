<?php
/*
 * Module:      process_judging_bos.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "judging_scores_bos" table
 */

if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))) {

	if ($action == "enter") {

		foreach($_POST['score_id'] as $score_id)	{

			if ((!empty($_POST['scorePlace'.$score_id])) && ($_POST['scorePrevious'.$score_id] == "Y")) {
				$sql = sprintf("UPDATE $judging_scores_bos_db_table SET
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

				mysqli_real_escape_string($connection,$sql);
				$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

			}

			if ((!empty($_POST['scorePlace'.$score_id])) && ($_POST['scorePrevious'.$score_id] == "N")) {
				$sql = sprintf("INSERT INTO $judging_scores_bos_db_table (
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

				mysqli_real_escape_string($connection,$sql);
				$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

			}

			if ((empty($_POST['scorePlace'.$score_id])) && ($_POST['scorePrevious'.$score_id] == "Y")) {

				$sql = sprintf("DELETE FROM %s WHERE id='%s'", $judging_scores_bos_db_table, $_POST['id'.$score_id]);
				mysqli_real_escape_string($connection,$sql);
				$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

			}

			//echo $sql."<br>";

		}

		//exit;

		$pattern = array('\'', '"');
		$updateGoTo = str_replace($pattern, "", $updateGoTo);
		$redirect_go_to = sprintf("Location: %s", stripslashes($updateGoTo));

	} // end if ($action == "enter")

} else {
	$redirect_go_to = sprintf("Location: %s", $base_url."index.php?msg=98");
}
?>