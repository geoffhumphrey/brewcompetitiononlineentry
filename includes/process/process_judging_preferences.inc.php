<?php
/*
 * Module:      process_judging_preferences.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "judging preferences" table
 */

use PHPMailer\PHPMailer\PHPMailer;
require(LIB.'email.lib.php');

if ((isset($_SERVER['HTTP_REFERER'])) && (((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))  || ($section == "setup"))) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

	if (isset($_POST['jPrefsBottleNum'])) $jPrefsBottleNum = sterilize($_POST['jPrefsBottleNum']);
	else $jPrefsBottleNum = "2";

	$jPrefsQueued = sterilize($_POST['jPrefsQueued']);
	$jPrefsFlightEntries = sterilize($_POST['jPrefsFlightEntries']);
	$jPrefsMaxBOS = sterilize($_POST['jPrefsMaxBOS']);
	$jPrefsRounds = sterilize($_POST['jPrefsRounds']);
	$jPrefsCapStewards = sterilize($_POST['jPrefsCapStewards']);
	$jPrefsCapJudges = sterilize($_POST['jPrefsCapJudges']);

	if (($action == "edit") || ($section == "setup")) {

		// Empty the prefs session variable
		// Will trigger the session to reset the variables in common.db.php upon reload after redirect
		session_name($prefix_session);
		session_start();
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

		if ($_SESSION['prefsEval'] == 1) {

			if (!check_update("jPrefsScoresheet", $prefix."judging_preferences")) {

				$sql = sprintf("ALTER TABLE `%s` 
				ADD `jPrefsJudgingOpen` int(15) NULL DEFAULT NULL AFTER `jPrefsBottleNum`, 
				ADD `jPrefsJudgingClosed` int(15) NULL DEFAULT NULL AFTER `jPrefsJudgingOpen`, 
				ADD `jPrefsScoresheet` tinyint(2) NULL DEFAULT NULL AFTER `jPrefsJudgingClosed`, 
				ADD `jPrefsScoreDispMax` tinyint(2) NULL DEFAULT NULL COMMENT 'Maximum disparity of entry scores between judges' AFTER `jPrefsScoresheet`;
				", $prefix."judging_preferences");
				
				$db_conn->rawQuery($sql);
				if ($db_conn->getLastErrno() !== 0) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

			$jPrefsScoresheet = sterilize($_POST['jPrefsScoresheet']);
			$jPrefsJudgingOpen = strtotime(sterilize($_POST['jPrefsJudgingOpen']));
			$jPrefsJudgingClosed = strtotime(sterilize($_POST['jPrefsJudgingClosed']));
			$jPrefsScoreDispMax = sterilize($_POST['jPrefsScoreDispMax']);
			
			$update_table = $prefix."judging_preferences";
			$data = array(
				'jPrefsScoresheet' => $jPrefsScoresheet,
				'jPrefsJudgingOpen' => $jPrefsJudgingOpen,
				'jPrefsJudgingClosed' => $jPrefsJudgingClosed,
				'jPrefsScoreDispMax' => $jPrefsScoreDispMax
			);
			$db_conn->where ('id', $id);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}
		
		} // end if ($_SESSION['prefsEval'] == 1)

		if ($section == "setup") {

			session_unset();
			session_destroy();
			session_write_close();
			session_regenerate_id(true);

			$update_table = $prefix."bcoem_sys";
			$data = array('setup_last_step' => 8);
			$db_conn->where ('id', 1);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			if (HOSTED) {

				if (strpos($_SERVER['SERVER_NAME'],"brewcomp.com") !== false) $server = "brewcomp.com";
				elseif (strpos($_SERVER['SERVER_NAME'],"brewcompetition.com") !== false) $server = "brewcompetition.com";
				else $server = "brewcompetition.com";

				// For hosted version: email prost@brewcompetition.com to alert when setup has been completed.
				$to_email = "prost@brewcompetition.com";
				$subject = "BCOEM Setup Completed for ".$_SERVER['SERVER_NAME'];
				$message = "<html>" . "\r\n";
				$message .= "<body>
							<p>BCOEM Setup Completed for http://".$_SERVER['SERVER_NAME']."</p>
							<p>Be sure to change setup_free_access to FALSE</p>
							</body>" . "\r\n";
				$message .= "</html>";

				$headers  = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type: text/html; charset=utf-8" . "\r\n";
				$headers .= "To: BCOEM Admin <prost@brewcompetition.com>, " . "\r\n";
				$headers .= "From: BCOEM Server <noreply@".$server.">" . "\r\n";

				if ($mail_use_smtp) {
					
					$mail = new PHPMailer(true);
					$mail->CharSet = 'UTF-8';
					$mail->Encoding = 'base64';
					$mail->addAddress($to_email, "BCOEM Admin");
					$mail->setFrom("noreply@".$server, "BCOEM Server");
					$mail->Subject = $subject;
					$mail->Body = $message;
					sendPHPMailerMessage($mail);

				} else {
					mail($to_email, $subject, $message, $headers);
				}
			
			} // end if (HOSTED)

			$redirect = $base_url."index.php?msg=16";
			if ($errors) $redirect = $base_url."index.php?msg=3";
			$redirect = prep_redirect_link($redirect);
			$redirect_go_to = sprintf("Location: %s", $redirect);
		
		} // end if ($section == "setup")

		else {
			if ($errors) $updateGoTo = $_POST['relocate']."&msg=3";
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