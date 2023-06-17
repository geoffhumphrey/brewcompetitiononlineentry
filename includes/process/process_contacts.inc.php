<?php

/*
 * Module:      process_contacts.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "contacts" table
 */

use PHPMailer\PHPMailer\PHPMailer;
require(LIB.'email.lib.php');
require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
$config_html_purifier = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config_html_purifier);

$captcha_success = FALSE;

if (isset($_SERVER['HTTP_REFERER'])) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

	if ($action == "email") {

		if (($_SESSION['prefsCAPTCHA'] == 1) && (isset($_POST['g-recaptcha-response'])) && (!empty($_POST['g-recaptcha-response']))) {

			if ((!HOSTED) && (!empty($_SESSION['prefsGoogleAccount']))) {
				$recaptcha_key = explode("|", $_SESSION['prefsGoogleAccount']);
				$private_captcha_key = $recaptcha_key[1];
			}

			$verify_response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$private_captcha_key.'&response='.$_POST['g-recaptcha-response']);
			$response_data = json_decode($verify_response);

			if (($_SERVER['SERVER_NAME'] == $response_data->hostname) && ($response_data->success)) $captcha_success = TRUE;

		}

		elseif ($_SESSION['prefsCAPTCHA'] == 0) $captcha_success = TRUE;

		if (!$captcha_success) {

			setcookie("to", $_POST['to'], 0, "/"); // $id of contact record in contacts table
			setcookie("from_email", strtolower(filter_var($_POST['from_email'], FILTER_SANITIZE_EMAIL)), 0, "/");
			setcookie("from_name", sterilize(ucwords($_POST['from_name'])), 0, "/");
			setcookie("subject", sterilize(ucwords($_POST['subject'])), 0, "/");
			setcookie("message", sterilize($_POST['message']), 0, "/");
			
			$redirect = $base_url."index.php?section=contact&action=email&msg=2";
			$redirect = prep_redirect_link($redirect);
			$redirect_go_to = sprintf("Location: %s", $redirect);

		}

		else {

			$query_contact = sprintf("SELECT * FROM $contacts_db_table WHERE id='%s'", $_POST['to']);
			$contact = mysqli_query($connection,$query_contact) or die (mysqli_error($connection));
			$row_contact = mysqli_fetch_assoc($contact);

			$to_name = $row_contact['contactFirstName']." ".$row_contact['contactLastName'];
			$to_name = html_entity_decode($to_name);
			$to_name = mb_convert_encoding($to_name, "UTF-8");

			$to_email = $row_contact['contactEmail'];
			$to_email = mb_convert_encoding($to_email, "UTF-8");
			$to_email_formatted = $to_name." <".$to_email.">";

			$from_email = strtolower(filter_var($_POST['from_email'], FILTER_SANITIZE_EMAIL));
			$from_email = mb_convert_encoding($from_email, "UTF-8");

			$from_name = sterilize(ucwords($_POST['from_name']));
			$from_name = html_entity_decode($from_name);
			$from_name = mb_convert_encoding($from_name, "UTF-8");
			
			$subject = sterilize(ucwords($_POST['subject']));
			$subject = html_entity_decode($subject);
			$subject = mb_convert_encoding($subject, "UTF-8");

			$message_post = sterilize($_POST['message']);
	
			$url = str_replace("www.","",$_SERVER['SERVER_NAME']);
			
			$from_competition_email = (!isset($mail_default_from) || trim($mail_default_from) === '') ? "noreply@".$url : $mail_default_from;
			$from_competition_email = mb_convert_encoding($from_competition_email, "UTF-8");
			
			$comp_name = mb_convert_encoding($_SESSION['contestName'], "UTF-8");

			// Build the message
			$message = "<html>" . "\r\n";
			$message .= "<body>";
			$message .= "<p>". $message_post. "</p>";
			$message .= "<p><strong>Sender's Contact Info</strong><br>Name: " . $from_name . "<br>Email: ". $from_email . "<br><em><small>** Use if you try to reply and the email address contains &quot;noreply&quot; in it. Common with web-based mail services such as Gmail.</small></em></p>";
			if ((DEBUG || TESTING) && (ENABLE_MAILER)) $message .= "<p><small>Sent using phpMailer.</small></p>";
			$message .= "</body>" . "\r\n";
			$message .= "</html>";

			$headers  = "MIME-Version: 1.0"."\r\n";
			$headers .= "Content-type: text/html; charset=utf-8"."\r\n";
			$headers .= "From: ".$comp_name." Server <".$from_competition_email.">" . "\r\n"; 
			$headers .= "Reply-To: ".$from_name." <".$from_email.">"."\r\n";
			if ((!HOSTED) && ($_SESSION['prefsEmailCC'] == 0)) $headers .= "Bcc: ".$from_name." <".$from_email.">"."\r\n";

			/*
			// Debug
			echo $to_email."<br>";
			echo $to_name."<br>";
			echo $headers."<br>";
			echo "To: ".$to_name." ".$to_email."<br>";
			echo "From: ".$comp_name." ".$from_competition_email."<br>";
			echo "Reply-To: ".$from_name." ".$from_email."<br>";
			if ($_SESSION['prefsEmailCC'] == 0) echo "Bcc: ".$from_name." ".$from_email."<br>";
			echo $message;
			exit;
			*/

			if ($mail_use_smtp) {				
				$mail = new PHPMailer(true);
				$mail->CharSet = 'UTF-8';
				$mail->Encoding = 'base64';
				$mail->addAddress($to_email, $to_name);
				$mail->setFrom($from_competition_email, $comp_name);
				$mail->addReplyTo($from_email, $from_name);
				if ((!HOSTED) && ($_SESSION['prefsEmailCC'] == 0)) $mail->addBCC($from_email, $from_name);
				$mail->Subject = $subject;
				$mail->Body = $message;
				sendPHPMailerMessage($mail);
			} else {
				mail($to_email_formatted, $subject, $message, $headers);
			}

			$redirect = $base_url."index.php?section=contact&action=email&id=".$row_contact['id']."&msg=1";
			$redirect = prep_redirect_link($redirect);
			$redirect_go_to = sprintf("Location: %s", $redirect);

		}

	} // end if ($action == "email")

	elseif ((isset($_SESSION['loginUsername'])) && (isset($_SESSION['userLevel']))) {

		$contactFirstName = sterilize($_POST['contactFirstName']);
		$contactFirstName = standardize_name($purifier->purify($contactFirstName));
		$contactLastName = sterilize(ucwords($_POST['contactLastName']));
		$contactLastName = standardize_name($purifier->purify($contactLastName));
		$contactPosition = sterilize(ucwords($_POST['contactPosition']));
		$contactPosition = $purifier->purify($contactPosition);
		$contactEmail = strtolower(filter_var($_POST['contactEmail'], FILTER_SANITIZE_EMAIL));
		$contactEmail = $purifier->purify($contactEmail);

		if ($action == "add") {

			$update_table = $prefix."contacts";
			$data = array(
				'contactFirstName' => blank_to_null($contactFirstName),
				'contactLastName' => blank_to_null($contactLastName),
				'contactPosition' => blank_to_null($contactPosition),
				'contactEmail' => blank_to_null($contactEmail)
			);
			$result = $db_conn->insert ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$insertGoTo = $_POST['relocate']."&msg=3";
			}

			if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

			$insertGoTo = prep_redirect_link($insertGoTo);
			$redirect_go_to = sprintf("Location: %s", $insertGoTo);

		}

		if ($action == "edit") {

			$update_table = $prefix."contacts";
			$data = array(
				'contactFirstName' => blank_to_null($contactFirstName),
				'contactLastName' => blank_to_null($contactLastName),
				'contactPosition' => blank_to_null($contactPosition),
				'contactEmail' => blank_to_null($contactEmail)
			);			
			$db_conn->where ('id', $id);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$updateGoTo = $_POST['relocate']."&msg=3";
			}
			
			if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

			$updateGoTo = prep_redirect_link($updateGoTo);
			$redirect_go_to = sprintf("Location: %s", $updateGoTo);

		}

	}

} else {
	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);
}


?>