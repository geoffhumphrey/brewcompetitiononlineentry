<?php
/*
 * Module:      process_judging_scores.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "judging_scores" table
 */

if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

	if (($action == "add") || ($action == "edit")) {

		// First, wipe out all previously recorded scores for the table
		$deleteSQL = sprintf("DELETE FROM %s WHERE scoreTable='%s'", $judging_scores_db_table, $id);
		mysqli_real_escape_string($connection,$deleteSQL);
		$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));

		$update_table = $prefix."judging_scores";
		$db_conn->where ('scoreTable', $id);
		$result = $db_conn->delete ($update_table);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		foreach($_POST['score_id'] as $score_id) {

			// Prep Vars
			$eid = sterilize($_POST['eid'.$score_id]);
			$bid = sterilize($_POST['bid'.$score_id]), "text"),
			$scoreTable = sterilize($_POST['scoreTable'.$score_id]);
			$scoreEntry = sterilize($_POST['scoreEntry'.$score_id]);
			$scorePlace = sterilize($_POST['scorePlace'.$score_id]);
			$scoreType = sterilize($_POST['scoreType'.$score_id]);
			if (!empty($_POST['scoreMiniBOS'.$score_id])) $scoreMiniBOS = sterilize($_POST['scoreMiniBOS'.$score_id]);
			else $scoreMiniBOS = 0;

			// Second, get rid of any duplicates, just in case they're in there
			$query_delete_assign = sprintf("SELECT id FROM $judging_scores_db_table WHERE eid='%s'", $_POST['eid'.$score_id]);
			$delete_assign = mysqli_query($connection,$query_delete_assign) or die (mysqli_error($connection));
			$row_delete_assign = mysqli_fetch_assoc($delete_assign);
			$totalRows_delete_assign = mysqli_num_rows($delete_assign);

			if ($totalRows_delete_assign > 0) {

				do {

					$update_table = $prefix."judging_scores";
					$db_conn->where ('id', $row_delete_assign['id']);
					$result = $db_conn->delete ($update_table);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

				} while ($row_delete_assign = mysqli_fetch_assoc($delete_assign));
			
			} // end if ($totalRows_delete_assign > 0)


			if ((!empty($_POST['scoreEntry'.$score_id])) || (!empty($_POST['scoreMiniBOS'.$score_id])) || (!empty($_POST['scorePlace'.$score_id]))) {

				$update_table = $prefix."judging_scores";
				$data = array(
					'eid' => blank_to_null($eid),
					'bid' => blank_to_null($bid),
					'scoreTable' => blank_to_null($scoreTable),
					'scoreEntry' => blank_to_null($scoreEntry),
					'scorePlace' => blank_to_null($scorePlace),
					'scoreType' => blank_to_null($scoreType),
					'scoreMiniBOS' => blank_to_null($scoreMiniBOS)
				);
				$result = $db_conn->insert ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			} // end if ((!empty($_POST['scoreEntry'.$score_id])) || (!empty($_POST['scoreMiniBOS'.$score_id])) || (!empty($_POST['scorePlace'.$score_id])))
		
		} // end foreach

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		if ($errors) $insertGoTo = $_POST['relocate']."&msg=3";
		$insertGoTo = prep_redirect_link($insertGoTo);
		$redirect_go_to = sprintf("Location: %s", $insertGoTo);

	} // end if (($action == "add") || ($action == "edit"))

} else {

	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);

}

?>