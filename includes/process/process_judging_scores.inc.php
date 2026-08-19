<?php
/*
 * Module:      process_judging_scores.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "judging_scores" table
 */

if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))) {

	$errors = FALSE;
	$error_output = [];
	$_SESSION['error_output'] = "";

	if (($action == "add") || ($action == "edit")) {

		// First, wipe out all previously recorded scores for the table
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
			$bid = sterilize($_POST['bid'.$score_id]);
			$scoreTable = sterilize($_POST['scoreTable'.$score_id]);
			$scoreEntry = sterilize($_POST['scoreEntry'.$score_id]);
			$scorePlace = sterilize($_POST['scorePlace'.$score_id]);
			$scoreType = sterilize($_POST['scoreType'.$score_id]);
			if (!empty($_POST['scoreMiniBOS'.$score_id])) $scoreMiniBOS = sterilize($_POST['scoreMiniBOS'.$score_id]);
			else $scoreMiniBOS = 0;

			// Second, get rid of any duplicates, just in case they're in there
			$db_conn->where("eid", sterilize($_POST['eid'.$score_id]));
			$rows_delete_assign = $db_conn->get($judging_scores_db_table, null, "id");
			$totalRows_delete_assign = $db_conn->count;

			if ($totalRows_delete_assign > 0) {

				foreach ($rows_delete_assign as $row_delete_assign) {

					$update_table = $prefix."judging_scores";
					$db_conn->where ('id', $row_delete_assign['id']);
					$result = $db_conn->delete ($update_table);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

				}

			} // end if ($totalRows_delete_assign > 0)


			if ((!empty($_POST['scoreEntry'.$score_id])) || (!empty($_POST['scoreMiniBOS'.$score_id])) || (!empty($_POST['scorePlace'.$score_id]))) {

				$update_table = $prefix."judging_scores";
				$data = [
					'eid' => blank_to_null($eid),
					'bid' => blank_to_null($bid),
					'scoreTable' => blank_to_null($scoreTable),
					'scoreEntry' => blank_to_null($scoreEntry),
					'scorePlace' => blank_to_null($scorePlace),
					'scoreType' => blank_to_null($scoreType),
					'scoreMiniBOS' => blank_to_null($scoreMiniBOS)
				];
				$result = $db_conn->insert ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			} // end if ((!empty($_POST['scoreEntry'.$score_id])) || (!empty($_POST['scoreMiniBOS'.$score_id])) || (!empty($_POST['scorePlace'.$score_id])))

		} // end foreach

		if ($error_output !== []) $_SESSION['error_output'] = $error_output;

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