<?php
declare(strict_types=1);
// $timezone_raw is set by process.inc.php before including this file; default
// to UTC if this file is ever included standalone (mirrors process.inc.php).
if (!isset($timezone_raw)) $timezone_raw = 0;
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

	if (isset($_POST['contestEntryOpen'])) $contestEntryOpen = to_utc_epoch(sterilize($_POST['contestEntryOpen']), $timezone_raw);
	if (isset($_POST['contestEntryDeadline'])) $contestEntryDeadline = to_utc_epoch(sterilize($_POST['contestEntryDeadline']), $timezone_raw);
	if (isset($_POST['contestEntryEditDeadline'])) $contestEntryEditDeadline = to_utc_epoch(sterilize($_POST['contestEntryEditDeadline']), $timezone_raw);
	if (isset($_POST['contestDropoffOpen'])) $contestDropoffOpen = to_utc_epoch(sterilize($_POST['contestDropoffOpen']), $timezone_raw);
	if (isset($_POST['contestDropoffDeadline'])) $contestDropoffDeadline = to_utc_epoch(sterilize($_POST['contestDropoffDeadline']), $timezone_raw);
	if (isset($_POST['contestShippingOpen'])) $contestShippingOpen = to_utc_epoch(sterilize($_POST['contestShippingOpen']), $timezone_raw);
	if (isset($_POST['contestShippingDeadline'])) $contestShippingDeadline = to_utc_epoch(sterilize($_POST['contestShippingDeadline']), $timezone_raw);
	if (isset($_POST['contestAwardsLocDate'])) $contestAwardsLocDate = to_utc_epoch(sterilize($_POST['contestAwardsLocDate']), $timezone_raw);
	
	// Account Registration
	$contestRegistrationOpen = "";
	$contestRegistrationDeadline = "";
	$contestJudgeOpen = "";
	$contestJudgeDeadline = "";

	if (isset($_POST['contestRegistrationOpen'])) $contestRegistrationOpen = to_utc_epoch(sterilize($_POST['contestRegistrationOpen']), $timezone_raw);
	if (isset($_POST['contestRegistrationDeadline'])) $contestRegistrationDeadline = to_utc_epoch(sterilize($_POST['contestRegistrationDeadline']), $timezone_raw);
	if (isset($_POST['contestJudgeOpen'])) $contestJudgeOpen = to_utc_epoch(sterilize($_POST['contestJudgeOpen']), $timezone_raw);
	if (isset($_POST['contestJudgeDeadline'])) $contestJudgeDeadline = to_utc_epoch(sterilize($_POST['contestJudgeDeadline']), $timezone_raw);

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
    $db_conn->where('judgingLocType', '1', '<=');
    $rows_judging_locations = $db_conn->get($prefix."judging_locations", null, "id, judgingDate, judgingDateEnd");
    $totalRows_judging_locations = $db_conn->count;

    if ($totalRows_judging_locations > 0) {

        foreach ($rows_judging_locations as $row_judging_locations) {

            if (!empty($row_judging_locations['judgingDate'])) $judging_dates[] = $row_judging_locations['judgingDate'];
            if (!empty($row_judging_locations['judgingDateEnd'])) $judging_dates[] = $row_judging_locations['judgingDateEnd'];

        }

        $judging_earliest_date = min($judging_dates);
        $judging_latest_date = max($judging_dates);

    }

	if ((isset($_POST['jPrefsJudgingOpen'])) && (!empty($_POST['jPrefsJudgingOpen']))) $jPrefsJudgingOpen = to_utc_epoch(sterilize($_POST['jPrefsJudgingOpen']), $timezone_raw);
	elseif ((isset($_POST['jPrefsJudgingOpen'])) && (empty($_POST['jPrefsJudgingOpen'])) && (!empty($judging_earliest_date))) $jPrefsJudgingOpen = sterilize($judging_earliest_date);
	else $jPrefsJudgingOpen = "";

	if ((isset($_POST['jPrefsJudgingClosed'])) && (!empty($_POST['jPrefsJudgingClosed']))) $jPrefsJudgingClosed = to_utc_epoch(sterilize($_POST['jPrefsJudgingClosed']), $timezone_raw);
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
			$prefsWinnerDelay = to_utc_epoch(sterilize($_POST['prefsWinnerDelay']), $timezone_raw);
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
			if (isset($_POST['judgingDate'.$id])) $judgingDate = to_utc_epoch(sterilize($_POST['judgingDate'.$id]), $timezone_raw);
			if (isset($_POST['judgingDateEnd'.$id])) $judgingDateEnd = to_utc_epoch(sterilize($_POST['judgingDateEnd'.$id]), $timezone_raw);

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
