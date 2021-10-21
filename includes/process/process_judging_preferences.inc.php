<?php
/*
 * Module:      process_judging_preferences.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "judging preferences" table
 */

use PHPMailer\PHPMailer\PHPMailer;
require(LIB.'email.lib.php');

if ((isset($_SERVER['HTTP_REFERER'])) && (((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))  || ($section == "setup"))) {

	if (($action == "edit") || ($section == "setup")) {

	// Empty the prefs session variable
	// Will trigger the session to reset the variables in common.db.php upon reload after redirect
	session_name($prefix_session);
	session_start();
	unset($_SESSION['prefs'.$prefix_session]);

	if (isset($_POST['jPrefsBottleNum'])) $jPrefsBottleNum = $_POST['jPrefsBottleNum'];
	else $jPrefsBottleNum = "2";

	if ($_POST['jPrefsQueued'] == "N") $flight_ent = $_POST['jPrefsFlightEntries']; 
	else $flight_ent = $_SESSION['jPrefsFlightEntries'];

	$updateSQL = sprintf("UPDATE $judging_preferences_db_table SET

	jPrefsQueued=%s,
	jPrefsFlightEntries=%s,
	jPrefsMaxBOS=%s,
	jPrefsRounds=%s,
	jPrefsBottleNum=%s,
	jPrefsCapStewards=%s,
	jPrefsCapJudges=%s
	WHERE id=%s",
					   GetSQLValueString(sterilize($_POST['jPrefsQueued']), "text"),
					   GetSQLValueString(sterilize($_POST['jPrefsFlightEntries']), "int"),
					   GetSQLValueString(sterilize($_POST['jPrefsMaxBOS']), "int"),
					   GetSQLValueString(sterilize($_POST['jPrefsRounds']), "int"),
					   GetSQLValueString(sterilize($jPrefsBottleNum), "int"),
					   GetSQLValueString(sterilize($_POST['jPrefsCapStewards']), "int"),
					   GetSQLValueString(sterilize($_POST['jPrefsCapJudges']), "int"),
					   GetSQLValueString($id, "int"));

	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

	if ($_SESSION['prefsEval'] == 1) {

		if (!check_update("jPrefsScoresheet", $prefix."judging_preferences")) {
			$updateSQL = sprintf("ALTER TABLE `%s` 
			ADD `jPrefsJudgingOpen` int(15) NULL DEFAULT NULL AFTER `jPrefsBottleNum`, 
			ADD `jPrefsJudgingClosed` int(15) NULL DEFAULT NULL AFTER `jPrefsJudgingOpen`, 
			ADD `jPrefsScoresheet` tinyint(2) NULL DEFAULT NULL AFTER `jPrefsJudgingClosed`, 
			ADD `jPrefsScoreDispMax` tinyint(2) NULL DEFAULT NULL COMMENT 'Maximum disparity of entry scores between judges' AFTER `jPrefsScoresheet`;
			", $prefix."judging_preferences");
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		}

		$jPrefsJudgingOpen = strtotime(filter_var($_POST['jPrefsJudgingOpen'],FILTER_SANITIZE_STRING));
		$jPrefsJudgingClosed = strtotime(filter_var($_POST['jPrefsJudgingClosed'],FILTER_SANITIZE_STRING));

		$updateSQL = sprintf("UPDATE $judging_preferences_db_table SET
			jPrefsScoresheet=%s,
			jPrefsJudgingOpen=%s,
			jPrefsJudgingClosed=%s,
			jPrefsScoreDispMax=%s
			WHERE id=%s",
						GetSQLValueString(sterilize($_POST['jPrefsScoresheet']), "text"),
						GetSQLValueString(sterilize($jPrefsJudgingOpen), "text"),
						GetSQLValueString(sterilize($jPrefsJudgingClosed), "text"),
						GetSQLValueString(sterilize($_POST['jPrefsScoreDispMax']), "int"),
						GetSQLValueString($id, "int"));

		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	}

	if ($section == "setup") {

		session_unset();
		session_destroy();
		session_write_close();
		session_regenerate_id(true);

		$updateSQL = sprintf("UPDATE %s SET setup='1', setup_last_step='8' WHERE id='1'",$prefix."bcoem_sys");
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

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
		}

		$redirect_go_to = sprintf("Location: %s", $base_url."index.php?msg=16");
	}

	else {
		$pattern = array('\'', '"');
		$updateGoTo = str_replace($pattern, "", $updateGoTo);
		$redirect_go_to = sprintf("Location: %s", stripslashes($updateGoTo));
		}
	}
} else {
	$redirect_go_to = sprintf("Location: %s", $base_url."index.php?msg=98");
}
?>