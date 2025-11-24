<?php
/*
 * Module:      process_judging_preferences.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "judging preferences" table
 */

use PHPMailer\PHPMailer\PHPMailer;
require(LIB.'email.lib.php');

if ((isset($_SERVER['HTTP_REFERER'])) && (((isset($_SESSION['loginUsername'])) && ((isset($_SESSION['userLevel'])) && ($_SESSION['userLevel'] <= 1))) || ($section == "setup"))) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

	if (isset($_POST['jPrefsBottleNum'])) $jPrefsBottleNum = sterilize($_POST['jPrefsBottleNum']);
	else $jPrefsBottleNum = 2;

	$jPrefsBottleNum = blank_to_null($jPrefsBottleNum);
	$jPrefsQueued = blank_to_null(sterilize($_POST['jPrefsQueued']));
	$jPrefsFlightEntries = blank_to_null(sterilize($_POST['jPrefsFlightEntries']));
	$jPrefsMaxBOS = blank_to_null(sterilize($_POST['jPrefsMaxBOS']));
	$jPrefsRounds = blank_to_null(sterilize($_POST['jPrefsRounds']));

	// If do not want or need stewards or judges, allow for 0
	// The sanitize functions scrub zeroes
	if ($_POST['jPrefsCapStewards'] == 0) $jPrefsCapStewards = 0;
	else $jPrefsCapStewards = blank_to_null(sterilize($_POST['jPrefsCapStewards']));
	if ($_POST['jPrefsCapJudges'] == 0) $jPrefsCapJudges = 0;
	else $jPrefsCapJudges = blank_to_null(sterilize($_POST['jPrefsCapJudges']));

	if (($action == "edit") || ($section == "setup")) {

		// Empty the prefs session variable
		// Will trigger the session to reset the variables in common.db.php upon reload after redirect
		unset($_SESSION['prefs'.$prefix_session]);

		$update_table = $prefix."judging_preferences";
		$data = array(
			'jPrefsQueued' => $jPrefsQueued,
			'jPrefsFlightEntries' => $jPrefsFlightEntries,
			'jPrefsMaxBOS' => $jPrefsMaxBOS,
			'jPrefsRounds' => $jPrefsRounds,
			'jPrefsBottleNum' => $jPrefsBottleNum,
			'jPrefsCapStewards' => $jPrefsCapStewards,
			'jPrefsCapJudges' => $jPrefsCapJudges
		);
		$db_conn->where ('id', $id);
		$result = $db_conn->update ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		$update_table = $prefix."preferences";
		$data = array(
			'prefsEval' => sterilize($_POST['prefsEval']),
			'prefsDisplaySpecial' => sterilize($_POST['prefsDisplaySpecial']),
		);
		$db_conn->where ('id', 1);
		$result = $db_conn->update ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		if ($_POST['prefsEval'] == 1) {

			if (!check_update("jPrefsMinWords", $prefix."judging_preferences")) {

				$sql = sprintf("ALTER TABLE `%s` 
				ADD `jPrefsMinWords` int(3) NULL DEFAULT NULL AFTER `jPrefsBottleNum`;
				", $prefix."judging_preferences");
				
				$db_conn->rawQuery($sql);
				if ($db_conn->getLastErrno() !== 0) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}		

			if (!check_update("jPrefsJudgingOpen", $prefix."judging_preferences")) {

				$sql = sprintf("ALTER TABLE `%s` ADD `jPrefsJudgingOpen` int(15) NULL DEFAULT NULL AFTER `jPrefsMinWords`;", $prefix."judging_preferences");
				
				$db_conn->rawQuery($sql);
				if ($db_conn->getLastErrno() !== 0) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

			if (!check_update("jPrefsJudgingClosed", $prefix."judging_preferences")) {

				$sql = sprintf("ALTER TABLE `%s` ADD `jPrefsJudgingClosed` int(15) NULL DEFAULT NULL AFTER `jPrefsJudgingOpen`;", $prefix."judging_preferences");
				
				$db_conn->rawQuery($sql);
				if ($db_conn->getLastErrno() !== 0) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

			if (!check_update("jPrefsScoresheet", $prefix."judging_preferences")) {

				$sql = sprintf("ALTER TABLE `%s` ADD `jPrefsScoresheet` tinyint(2) NULL DEFAULT NULL AFTER `jPrefsJudgingClosed`;", $prefix."judging_preferences");
				
				$db_conn->rawQuery($sql);
				if ($db_conn->getLastErrno() !== 0) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

			if (!check_update("jPrefsScoreDispMax", $prefix."judging_preferences")) {

				$sql = sprintf("ALTER TABLE `%s` ADD `jPrefsScoreDispMax` tinyint(2) NULL DEFAULT NULL COMMENT 'Maximum disparity of entry scores between judges' AFTER `jPrefsScoresheet`;
				", $prefix."judging_preferences");
				
				$db_conn->rawQuery($sql);
				if ($db_conn->getLastErrno() !== 0) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

			$judging_dates = array();
			$judging_earliest_date = "";
			$judging_latest_date = "";
			$jPrefsJudgingOpen = "";
			$jPrefsJudgingClosed = "";
			
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
		        if ((max($judging_dates) > $judging_earliest_date)) $judging_latest_date = max($judging_dates);

		    }

		    // If an opening date is posted
		    if ((isset($_POST['jPrefsJudgingOpen'])) && (!empty($_POST['jPrefsJudgingOpen']))) {

		    	// If no defined earliest judging session date, use the posted date
		    	if (empty($judging_earliest_date)) $jPrefsJudgingOpen = strtotime(sterilize($_POST['jPrefsJudgingOpen']));
		    	
		    	// Otherwise...
		    	else {

		    		// If the earliest judging session date defined is after the posted date, use the posted date
		    		if ($judging_earliest_date > $_POST['jPrefsJudgingOpen']) $jPrefsJudgingOpen = strtotime(sterilize($_POST['jPrefsJudgingOpen']));

		    		// Otherwise, use the earliest defined judging session date
		    		else $jPrefsJudgingOpen = $judging_earliest_date;

		    	}	    	

		    }

		    // If no opening date is posted
		    else {

		    	// If no defined earliest judging session date, default to today at midnight
		    	if (empty($judging_earliest_date)) {
		    		$date = new DateTime('today 00:00:00', new DateTimeZone($timezone_prefs));
			    	$jPrefsJudgingOpen = $date->getTimestamp();
		    	}

		    	// Otherwise, use the earliest defined judging session date
		    	else $jPrefsJudgingOpen = $judging_earliest_date;

		    }

		    // If a closing date is posted
		    if ((isset($_POST['jPrefsJudgingClosed'])) && (!empty($_POST['jPrefsJudgingClosed']))) {

		    	// If no defined earlies judging session date, use the posted date
		    	if (empty($judging_latest_date)) $jPrefsJudgingClosed = strtotime(sterilize($_POST['jPrefsJudgingClosed']));
		    	
		    	// Otherwise...
		    	else {

		    		// If the latest judging session date defined is prior to the posted date, use the posted date
		    		if ($judging_latest_date < $_POST['jPrefsJudgingClosed']) $jPrefsJudgingClosed = strtotime(sterilize($_POST['jPrefsJudgingClosed']));

		    		// Otherwise, use the latest defined judging session date
		    		else $jPrefsJudgingClosed = $judging_latest_date;

		    	}

		    }

		    // If no closing date is posted
		    else {

		    	// No defined latest judging session date
		    	if (empty($judging_latest_date)) {
		    		
		    		// If open dated posted, add 1 day to it
		    		if ((isset($_POST['jPrefsJudgingOpen'])) && (!empty($_POST['jPrefsJudgingOpen']))) $jPrefsJudgingClosed = strtotime(sterilize($_POST['jPrefsJudgingOpen'])) + 86400;

		    		// If not, and the earliest judging date is defined, add one day to it
		    		elseif (!empty($judging_earliest_date)) $jPrefsJudgingClosed = $judging_earliest_date + 86400;
		    		
		    		// If no dates are defined, fall back to tomorrow at midnight
		    		else {
		    			$date = new DateTime('tomorrow 00:00:00', new DateTimeZone($timezone_prefs));
		    			$jPrefsJudgingClosed = $date->getTimestamp();
		    		}
		    		
		    	}

		    	else $jPrefsJudgingClosed = $judging_latest_date;
		    	
		    }

			$jPrefsScoresheet = blank_to_null(sterilize($_POST['jPrefsScoresheet']));
			$jPrefsMinWords = blank_to_null(sterilize($_POST['jPrefsMinWords']));			
			$jPrefsScoreDispMax = blank_to_null(sterilize($_POST['jPrefsScoreDispMax']));
			
			$update_table = $prefix."judging_preferences";
			$data = array(
				'jPrefsScoresheet' => $jPrefsScoresheet,
				'jPrefsMinWords' => $jPrefsMinWords,
				'jPrefsJudgingOpen' => blank_to_null($jPrefsJudgingOpen),
				'jPrefsJudgingClosed' => blank_to_null($jPrefsJudgingClosed),
				'jPrefsScoreDispMax' => $jPrefsScoreDispMax
			);
			$db_conn->where ('id', $id);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}
		
		} // end if ($_SESSION['prefsEval'] == 1)

		if ($action == "edit") {

			/**
			 * If Max BOS changed to a lower number, remove any places defined 
			 * in the BOS scores db table that are greater than the new maximum, 
			 * except HM, which is 5.
			 */

			if ((isset($_SESSION['jPrefsMaxBOS'])) && ($_SESSION['jPrefsMaxBOS'] < $jPrefsMaxBOS)) {

				$update_table = $prefix."judging_scores_bos";
				$db_conn->where ("scorePlace", $jPrefsMaxBOS, ">");
				$db_conn->where ("scorePlace != 5");
				$result = $db_conn->delete ($update_table);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

		}

		if ($section == "setup") {

			session_unset();
			session_destroy();
			session_write_close();
			session_regenerate_id(true);

			$update_table = $prefix."bcoem_sys";
			$data = array(
				'setup_last_step' => 8,
				'setup' => 1
			);
			$db_conn->where ('id', 1);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			if ((HOSTED) && ($mail_use_smtp)) {

				$to_email = $default_to."@brewingcompetitions.com";
				$to_email = mb_convert_encoding($to_email, "UTF-8");
				$to_email_formatted = "BCOEM Admin <".$to_email.">";

				$subject = "BCOEM Setup Completed for ".$_SERVER['SERVER_NAME'];
				$subject = html_entity_decode($subject);
				$message = "<html>" . "\r\n";
				$message .= "<body>
							<p>BCOEM Setup Completed for http://".$_SERVER['SERVER_NAME']."</p>
							<p>Be sure to change setup_free_access to FALSE</p>
							</body>" . "\r\n";
				$message .= "</html>";

				$headers  = "MIME-Version: 1.0"."\r\n";
				$headers .= "Content-type: text/html; charset=utf-8"."\r\n";
				$headers .= "From: BCOEM Server <".$_SESSION['prefsEmailFrom'].">"."\r\n";
					
				$mail = new PHPMailer(true);
				$mail->CharSet = 'UTF-8';
				$mail->Encoding = 'base64';
				$mail->addAddress($to_email, "BCOEM Admin");
				$mail->setFrom($_SESSION['prefsEmailFrom'], "BCOEM Server");
				$mail->Subject = $subject;
				$mail->Body = $message;
				sendPHPMailerMessage($mail);				
			
			} // end if (HOSTED)

			$redirect = $base_url."index.php?msg=16";
			if ($errors) $redirect = $base_url."index.php?msg=3";
			$redirect = prep_redirect_link($redirect);
			$redirect_go_to = sprintf("Location: %s", $redirect);
		
		} // end if ($section == "setup")

		else {
			if ($errors) $updateGoTo = $_POST['relocate']."&msg=3";
			else $updateGoTo = $base_url."index.php?section=admin&msg=2";
			$updateGoTo = prep_redirect_link($updateGoTo);
			$redirect_go_to = sprintf("Location: %s", $updateGoTo);
		}

	} // end if (($action == "edit") || ($section == "setup"))

	if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

} else {

	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);

}
?>