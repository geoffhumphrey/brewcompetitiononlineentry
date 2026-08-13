<?php

use PHPMailer\PHPMailer\PHPMailer;
require(LIB.'email.lib.php');

if ((isset($_SERVER['HTTP_REFERER'])) && (isset($_SESSION['loginUsername'])) && (isset($_SESSION['userLevel'])) && ($_SESSION['userLevel'] <= 1)) {

	$url = str_replace("www.","",$_SERVER['SERVER_NAME']);

	$from_name = $_SESSION['contestName']." Competition Server";
	$from_name = html_entity_decode($from_name);
	$from_name = mb_convert_encoding($from_name, "UTF-8");
	
	$from_email = (!isset($mail_default_from) || trim($mail_default_from) === '') ? "noreply@".$url : $mail_default_from;
	$from_email = mb_convert_encoding($from_email, "UTF-8");
	
	$headers = "";
	$message = "";

	if ($filter == "test-email") {

		$db_conn->where('id', $id);
		$row_forgot = $db_conn->getOne($users_db_table);
		$totalRows_forgot = $db_conn->count;

		$db_conn->where('uid', $id);
		$row_brewer = $db_conn->getOne($brewer_db_table, "brewerLastName,brewerFirstName");
		$totalRows_brewer = $db_conn->count;

		$first_name = ucwords(strtolower($row_brewer['brewerFirstName']));
		$last_name = ucwords(strtolower($row_brewer['brewerLastName']));

		$to_name = $first_name." ".$last_name;
		$to_name = html_entity_decode($to_name);
		$to_name = mb_convert_encoding($to_name, "UTF-8");
		
		$to_email = strtolower($row_forgot['user_name']);
		$to_email = mb_convert_encoding($to_email, "UTF-8");
		$to_email_formatted = $to_name." <".$to_email.">";
		
		$subject = $_SESSION['contestName']." - System Generated Email Test";
		$subject = html_entity_decode($subject);
		$subject = mb_convert_encoding($subject, "UTF-8");

		$message = "<html>" . "\r\n";
		$message .= "<body>" . "\r\n";
		$message .= "<p>".$first_name.",</p>";
		$message .= "<p>A request was made to verify ability to send system-generated emails from the  ".$_SESSION['contestName']." competition website. If you are reading this, the test was <strong>successful</strong>!</p>";
		$message .= "<p>Thank you,</p><p>The  ".$_SESSION['contestName']." Competition Server</p>";
		$message .= "<p><small>".$paypal_response_text_003."</small></p>";
		if ((DEBUG || TESTING) && ($mail_use_smtp)) $message .= "<p><small>Sent using phpMailer.</small></p>";
		$message .= "</body>" . "\r\n";
		$message .= "</html>";

		$headers  = "MIME-Version: 1.0"."\r\n";
		$headers .= "Content-type: text/html; charset=utf-8"."\r\n";
		$headers .= "From: ".$from_name." <".$from_email.">"."\r\n";
		$headers .= "Reply-To: ".$from_name." <".$from_email.">"."\r\n";

		if ($mail_use_smtp) {
			$mail = new PHPMailer(true);
			$mail->CharSet = 'UTF-8';
			$mail->Encoding = 'base64';
			$mail->addAddress($to_email, $to_name);
			$mail->setFrom($from_email, $from_name);
			$mail->Subject = $subject;
			$mail->Body = $message;
			sendPHPMailerMessage($mail);
			$redirect = $base_url."index.php?section=admin&go=preferences&msg=32";
		}

		else {
			$redirect = $base_url."index.php?section=admin&go=preferences&msg=3";
		}

		$redirect = prep_redirect_link($redirect);
		$redirect_go_to = sprintf("Location: %s", $redirect);

	}

	if ($filter == "table-assignments") {

		if ($mail_use_smtp) {

			$query_brewer = "SELECT a.id,a.uid,a.brewerFirstName,a.brewerLastName,a.brewerJudgeID,a.brewerJudgeWaiver,a.brewerEmail,b.uid,b.staff_judge,b.staff_steward FROM $brewer_db_table a, $staff_db_table b WHERE a.uid = b.uid AND (b.staff_steward='1' OR b.staff_judge='1') ORDER BY brewerLastName ASC";
			$rows_brewer = $db_conn->rawQuery($query_brewer);
			$totalRows_brewer = $db_conn->count;

			$query_organizer = "SELECT a.brewerFirstName,a.brewerLastName FROM $brewer_db_table a, $staff_db_table b WHERE a.uid = b.uid AND staff_organizer='1'";
			$row_organizer = $db_conn->rawQueryOne($query_organizer);
			$totalRows_organizer = $db_conn->count;

			foreach ($rows_brewer as $row_brewer) {

				$message = "";
				$table_assignments = "";

				if ($row_brewer['staff_judge'] == 1) $table_assignments = table_assignments($row_brewer['uid'],"J",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],0);
				if ($row_brewer['staff_steward'] == 1) $table_assignments = table_assignments($row_brewer['uid'],"S",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],0);

				if (!empty($table_assignments)) {

					$first_name = ucwords(strtolower($row_brewer['brewerFirstName']));
					$last_name = ucwords(strtolower($row_brewer['brewerLastName']));

					$to_name = $first_name." ".$last_name;
					$to_name = mb_convert_encoding($to_name, "UTF-8");
					
					$to_email = strtolower($row_brewer['brewerEmail']);
					$to_email = mb_convert_encoding($to_email, "UTF-8");
					$to_email_formatted .= $to_name." <".$to_email.">";
					
					if ($row_brewer['staff_judge'] == 1) $subject = $_SESSION['contestName']." - Your Judging Assignments";
					if ($row_brewer['staff_steward'] == 1) $subject = $_SESSION['contestName']." - Your Stewarding Assignments";
					$subject = mb_convert_encoding($subject, "UTF-8");

					$message = "<html>" . "\r\n";
					$message .= "<body>" . "\r\n";
					if (isset($_SESSION['contestLogo'])) $message .= "<p align='center'><img src='".$base_url."user_images/".$_SESSION['contestLogo']."' height='150'></p>";
					$message .= "<p>".$first_name.",</p>";
					$message .= "<p>Thank you for volunteering to be a ";
					if ($row_brewer['staff_judge'] == 1) $message .= "judge ";
					if ($row_brewer['staff_steward'] == 1) $message .= "steward ";
					$message .= "in the ".$_SESSION['contestName'].". Your assignment(s) are detailed below.</p>";

					$message .= "<table cellpadding='5' border='0' cellspacing='0'>";
					$message .= "<thead>";
					$message .= "<tr>";
					$message .= "<th align='left'>Location</th>";
					$message .= "<th align='left'>Date/Time</th>";
					$message .= "<th align='left'>Table</th>";
					$message .= "</tr>";
					$message .= "</thead>";
					$message .= "<tbody>";
					$message .= $table_assignments;
					$message .= "</tbody>";
					$message .= "</table>";
					$message .= sprintf("<p>If you wish to change your availabilty and/or withdraw your role, <a href=\"%s\">please contact</a> the appropriate competition official.</p>",$base_url."#contact");
					$message .= "<p>Cheers,</p>";
					$message .= "<p>".$row_organizer['brewerFirstName']." ".$row_organizer['brewerLastName'].", Organizer";
					$message .= sprintf("<br><a href=\"%s\">%s</a></p>",$base_url,$_SESSION['contestName']);
					$message .= "<p><small>".$paypal_response_text_003."</small></p>";
					if ((DEBUG || TESTING) && ($mail_use_smtp)) $message .= "<p><small>Sent using phpMailer.</small></p>";
					$message .= "</body>" . "\r\n";
					$message .= "</html>";

					$headers  = "MIME-Version: 1.0"."\r\n";
					$headers .= "Content-type: text/html; charset=utf-8"."\r\n";
					$headers .= "From: ".$from_name." <".$from_email.">"."\r\n";
					$headers .= "Reply-To: ".$from_name." <".$from_email.">"."\r\n";

					$mail = new PHPMailer(true);
					$mail->CharSet = 'UTF-8';
					$mail->Encoding = 'base64';
					$mail->addAddress($to_email, $to_name);
					$mail->setFrom($from_email, $from_name);
					$mail->Subject = $subject;
					$mail->Body = $message;
					sendPHPMailerMessage($mail);
				
				}

			}

		}

	}

}
else {

	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);

}
?>