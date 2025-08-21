<?php
if ((isset($_SERVER['HTTP_REFERER'])) && (((isset($_SESSION['loginUsername'])) && ((isset($_SESSION['userLevel'])) && ($_SESSION['userLevel'] == 0))))) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

	// Entry-Related
	$contestEntryOpen = "";
	$contestEntryDeadline = "";
	$contestEntryEditDeadline = "";
	$contestDropoffOpen = "";
	$contestDropoffDeadline = "";
	$contestShippingOpen = "";
	$contestShippingDeadline = "";
	$contestAwardsLocDate = "";

	if (isset($_POST['contestEntryOpen'])) $contestEntryOpen = strtotime(sterilize($_POST['contestEntryOpen']));
	if (isset($_POST['contestEntryDeadline'])) $contestEntryDeadline = strtotime(sterilize($_POST['contestEntryDeadline']));
	if (isset($_POST['contestEntryEditDeadline'])) $contestEntryEditDeadline = strtotime(sterilize($_POST['contestEntryEditDeadline']));
	if (isset($_POST['contestDropoffOpen'])) $contestDropoffOpen = strtotime(sterilize($_POST['contestDropoffOpen']));
	if (isset($_POST['contestDropoffDeadline'])) $contestDropoffDeadline = strtotime(sterilize($_POST['contestDropoffDeadline']));
	if (isset($_POST['contestShippingOpen'])) $contestShippingOpen = strtotime(sterilize($_POST['contestShippingOpen']));
	if (isset($_POST['contestShippingDeadline'])) $contestShippingDeadline = strtotime(sterilize($_POST['contestShippingDeadline']));
	if (isset($_POST['contestAwardsLocDate'])) $contestAwardsLocDate = strtotime(sterilize($_POST['contestAwardsLocDate']));
	
	// Account Registration
	$contestRegistrationOpen = "";
	$contestRegistrationDeadline = "";
	$contestJudgeOpen = "";
	$contestJudgeDeadline = "";

	if (isset($_POST['contestRegistrationOpen'])) $contestRegistrationOpen = strtotime(sterilize($_POST['contestRegistrationOpen']));
	if (isset($_POST['contestRegistrationDeadline'])) $contestRegistrationDeadline = strtotime(sterilize($_POST['contestRegistrationDeadline']));
	if (isset($_POST['contestJudgeOpen'])) $contestJudgeOpen = strtotime(sterilize($_POST['contestJudgeOpen']));
	if (isset($_POST['contestJudgeDeadline'])) $contestJudgeDeadline = strtotime(sterilize($_POST['contestJudgeDeadline']));

	$update_table = $prefix."contest_info";
	$data = array(
		'contestRegistrationOpen' => blank_to_null($contestRegistrationOpen),
		'contestRegistrationDeadline' => blank_to_null($contestRegistrationDeadline),
		'contestEntryOpen' => blank_to_null($contestEntryOpen),
		'contestEntryDeadline' => blank_to_null($contestEntryDeadline),
		'contestEntryEditDeadline' => blank_to_null($contestEntryEditDeadline),
		'contestJudgeOpen' => blank_to_null($contestJudgeOpen),
		'contestJudgeDeadline' => blank_to_null($contestJudgeDeadline),
		'contestAwardsLocDate' => blank_to_null($contestAwardsLocDate),
		'contestAwardsLocTime' => blank_to_null($contestAwardsLocDate),
		'contestShippingOpen' => blank_to_null($contestShippingOpen),
		'contestShippingDeadline' => blank_to_null($contestShippingDeadline),
		'contestDropoffOpen' => blank_to_null($contestDropoffOpen),
		'contestDropoffDeadline' => blank_to_null($contestDropoffDeadline)
	);
	$db_conn->where ('id', 1);
	$result = $db_conn->update ($update_table, $data);
	if (!$result) {
		$error_output[] = $db_conn->getLastError();
		$errors = TRUE;
	}

	// Update session vars
	foreach ($data as $key=>$value) {
		$_SESSION[$key] = $value;
	}

	// Judging Open
	
	$judging_dates = array();
	$judging_earliest_date = "";
	$judging_latest_date = "";
	
    // Check whether any judging sessions have been defined. 
    // If so, loop through and find the earliest and the latest dates.
    $query_judging_locations = sprintf("SELECT id, judgingDate, judgingDateEnd FROM %s WHERE judgingLocType <= '1';", $prefix."judging_locations");
    $judging_locations = mysqli_query($connection,$query_judging_locations) or die (mysqli_error($connection));
    $row_judging_locations = mysqli_fetch_assoc($judging_locations);
    $totalRows_judging_locations = mysqli_num_rows($judging_locations);

    if ($totalRows_judging_locations > 0) {

        do {

            if (!empty($row_judging_locations['judgingDate'])) $judging_dates[] = $row_judging_locations['judgingDate'];
            if (!empty($row_judging_locations['judgingDateEnd'])) $judging_dates[] = $row_judging_locations['judgingDateEnd'];

        } while($row_judging_locations = mysqli_fetch_assoc($judging_locations));

        $judging_earliest_date = min($judging_dates);
        $judging_latest_date = max($judging_dates);

    }

	if ((isset($_POST['jPrefsJudgingOpen'])) && (!empty($_POST['jPrefsJudgingOpen']))) $jPrefsJudgingOpen = strtotime(sterilize($_POST['jPrefsJudgingOpen']));
	elseif ((isset($_POST['jPrefsJudgingOpen'])) && (empty($_POST['jPrefsJudgingOpen'])) && (!empty($judging_earliest_date))) $jPrefsJudgingOpen = sterilize($judging_earliest_date);
	else $jPrefsJudgingOpen = "";

	if ((isset($_POST['jPrefsJudgingClosed'])) && (!empty($_POST['jPrefsJudgingClosed']))) $jPrefsJudgingClosed = strtotime(sterilize($_POST['jPrefsJudgingClosed']));
	elseif ((isset($_POST['jPrefsJudgingClosed'])) && (empty($_POST['jPrefsJudgingClosed']))) {
	    if (!empty($judging_latest_date)) $jPrefsJudgingClosed = sterilize($judging_latest_date);
	    else {
	    	if ((empty($judging_latest_date)) && (!empty($judging_earliest_date))) $jPrefsJudgingClosed = sterilize($judging_earliest_date+1209600);
	    	else $jPrefsJudgingClosed = "";
	    }
	}
	else $jPrefsJudgingClosed = "";

	$update_table = $prefix."judging_preferences";
	$data = array(
		'jPrefsJudgingOpen' => blank_to_null($jPrefsJudgingOpen),
		'jPrefsJudgingClosed' => blank_to_null($jPrefsJudgingClosed)
	);
	$db_conn->where ('id', 1);
	$result = $db_conn->update ($update_table, $data);
	if (!$result) {
		$error_output[] = $db_conn->getLastError();
		$errors = TRUE;
	}

	// Update session vars
	foreach ($data as $key=>$value) {
		$_SESSION[$key] = $value;
	}

	// Results Publish
	$prefsWinnerDelay = "";
	$prefsDisplayWinners = "N";
	
	if (isset($_POST['prefsWinnerDelay'])) {
		
		if (!empty($_POST['prefsWinnerDelay'])) {
			$prefsWinnerDelay = strtotime(sterilize($_POST['prefsWinnerDelay']));
			$prefsDisplayWinners = "Y";
		}
			
		else $prefsDisplayWinners = "N";
	}

	$update_table = $prefix."preferences";
	$data = array(
		'prefsWinnerDelay' => blank_to_null($prefsWinnerDelay),
		'prefsDisplayWinners' => blank_to_null($prefsDisplayWinners)
	);
	$db_conn->where ('id', 1);
	$result = $db_conn->update ($update_table, $data);
	if (!$result) {
		$error_output[] = $db_conn->getLastError();
		$errors = TRUE;
	}

	foreach ($data as $key=>$value) {
		$_SESSION[$key] = $value;
	}
	
	// Judging Sessions
	if (isset($_POST['id'])) {

		foreach($_POST['id'] as $id) {

			$judgingDate = "";
			$judgingDateEnd = "";
			if (isset($_POST['judgingDate'.$id])) $judgingDate = strtotime(sterilize($_POST['judgingDate'.$id]));
			if (isset($_POST['judgingDateEnd'.$id])) $judgingDateEnd = strtotime(sterilize($_POST['judgingDateEnd'.$id]));

			$update_table = $prefix."judging_locations";
			$data = array(
				'judgingDate' => blank_to_null($judgingDate),
				'judgingDateEnd' => blank_to_null($judgingDateEnd)			
			);			
			$db_conn->where ('id', $id);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

		} // end foreach($_POST['id'] as $id) {

	}
	
	if (!empty($error_output)) $_SESSION['error_output'] = $error_output;
	if ($errors) $updateGoTo = sterilize($_POST['relocate']."&msg=3");
	else $updateGoTo = sterilize($_POST['relocate']."&msg=2");
	$updateGoTo = prep_redirect_link($updateGoTo);
	$redirect_go_to = sprintf("Location: %s", $updateGoTo);

} else {
	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);
}

?>