<?php
/*
 * Module:      process_judging_bos.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "judging_scores_bos" table
 */

if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

	if ($action == "enter") {

		foreach($_POST['score_id'] as $score_id) {

			// Prep Vars
			$eid = blank_to_null(sterilize($_POST['eid'.$score_id]));
			$bid = blank_to_null(sterilize($_POST['bid'.$score_id])),
			$scoreEntry = blank_to_null(sterilize($_POST['scoreEntry'.$score_id]));
			$scorePlace = blank_to_null(sterilize($_POST['scorePlace'.$score_id]));
			$scoreType = blank_to_null(sterilize($_POST['scoreType'.$score_id]));

			if ((!empty($_POST['scorePlace'.$score_id])) && ($_POST['scorePrevious'.$score_id] == "Y")) {

				$update_table = $prefix."judging_scores_bos";
				$data = array(
					'eid' => $eid,
					'bid' => $bid,
					'scoreEntry' => $scoreEntry,
					'scorePlace' => $scorePlace,
					'scoreType' => $scoreType
				);
				$db_conn->where ('id', sterilize($_POST['id'.$score_id]));
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			} // end if ((!empty($_POST['scorePlace'.$score_id])) && ($_POST['scorePrevious'.$score_id] == "Y"))

			if ((!empty($_POST['scorePlace'.$score_id])) && ($_POST['scorePrevious'.$score_id] == "N")) {

				$update_table = $prefix."judging_scores_bos";
				$data = array(
					'eid' => $eid,
					'bid' => $bid,
					'scoreEntry' => $scoreEntry,
					'scorePlace' => $scorePlace,
					'scoreType' => $scoreType
				);
				$result = $db_conn->insert ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			} // end if ((!empty($_POST['scorePlace'.$score_id])) && ($_POST['scorePrevious'.$score_id] == "N"))

			if ((empty($_POST['scorePlace'.$score_id])) && ($_POST['scorePrevious'.$score_id] == "Y")) {

				$update_table = $prefix."judging_scores_bos";
				$db_conn->where ('id', sterilize($_POST['id'.$score_id]));
				$result = $db_conn->delete ($update_table);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			} // end if ((empty($_POST['scorePlace'.$score_id])) && ($_POST['scorePrevious'.$score_id] == "Y"))

		} // end foreach

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		if ($errors) $updateGoTo = $_POST['relocate']."&msg=3";
		$updateGoTo = prep_redirect_link($updateGoTo);
		$redirect_go_to = sprintf("Location: %s", $updateGoTo);

	} // end if ($action == "enter")

} else {
	
	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);

}
?>