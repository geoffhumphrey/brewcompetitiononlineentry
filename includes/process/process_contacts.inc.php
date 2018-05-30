<?php

/*
 * Module:      process_contacts.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "contacts" table
 */
$captcha_success = FALSE;

if (isset($_SERVER['HTTP_REFERER'])) {

	if ($action == "email") {

		if (($_SESSION['prefsCAPTCHA'] == 1) && (isset($_POST['g-recaptcha-response'])) && (!empty($_POST['g-recaptcha-response']))) {

			if (!empty($_SESSION['prefsGoogleAccount'])) {
				$recaptcha_key = explode("|", $_SESSION['prefsGoogleAccount']);
				$private_captcha_key = $recaptcha_key[1];
			}

			$verify_response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$private_captcha_key.'&response='.$_POST['g-recaptcha-response']);
			$response_data = json_decode($verify_response);

			// echo $response_data->hostname."<br>"; echo $_SERVER['SERVER_NAME']; exit;

			if (($_SERVER['SERVER_NAME'] == $response_data->hostname) && ($response_data->success)) $captcha_success = TRUE;

		}

		elseif ($_SESSION['prefsCAPTCHA'] == 0) $captcha_success = TRUE;

		if (!$captcha_success) {

			setcookie("to", $_POST['to'], 0, "/"); // $id of contact record in contacts table
			setcookie("from_email", strtolower(filter_var($_POST['from_email'], FILTER_SANITIZE_EMAIL)), 0, "/");
			setcookie("from_name", sterilize(ucwords($_POST['from_name'])), 0, "/");
			setcookie("subject", sterilize(ucwords($_POST['subject'])), 0, "/");
			setcookie("message", sterilize($_POST['message']), 0, "/");
			$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=contact&action=email&msg=2");

		}

		else {

			$query_contact = sprintf("SELECT * FROM $contacts_db_table WHERE id='%s'", $_POST['to']);
			$contact = mysqli_query($connection,$query_contact) or die (mysqli_error($connection));
			$row_contact = mysqli_fetch_assoc($contact);
			//echo $query_contact;

			// Gather the variables from the form
			$to_email = $row_contact['contactEmail'];
			$to_name = $row_contact['contactFirstName']." ".$row_contact['contactLastName'];
			$from_email = strtolower(filter_var($_POST['from_email'], FILTER_SANITIZE_EMAIL));
			$from_name = sterilize(ucwords($_POST['from_name']));
			$subject = sterilize(ucwords($_POST['subject']));
			$message_post = sterilize($_POST['message']);

			// Build the message
			$message = "<html>" . "\r\n";
			$message .= "<body>";
			$message .= "<p>". $message_post. "</p>";
			$message .= "<p><strong>Sender's Contact Info</strong><br>Name: " . $from_name . "<br>Email: ". $from_email . "<br><em><small>** Use if you try to reply and the email address contains &quot;noreply&quot; in it. Common with web-based mail services such as Gmail.</small></em></p>";
			$message .= "</body>" . "\r\n";
			$message .= "</html>";

			$headers  = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
			$headers .= "To: ".$to_name." <".$to_email.">" . "\r\n";
			$headers .= "From: ".$_SESSION['contestName']." Server <noreply@".$_SERVER['SERVER_NAME'].">" . "\r\n"; // needed to change due to more stringent rules and mail send incompatibility with Gmail.
			$headers .= "Reply-To: ".$from_name." <".$from_email.">" . "\r\n";
			$headers .= "CC: ".$from_name." <".$from_email.">" . "\r\n";

			/*
			// Debug
			echo $to_email."<br>";
			echo $to_name."<br>";
			echo $headers."<br>";
			echo "To: ".$to_name." ".$to_email."<br>";
			echo "From: ".$_SESSION['contestName']." Server noreply@".$_SERVER['SERVER_NAME']."<br>";
			echo "Reply-To: ".$from_name." ".$from_email."<br>";
			echo "CC: ".$from_name." ".$from_email."<br>";
			echo $message;
			*/

			mail($to_email, $subject, $message, $headers);
			$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=contact&action=email&id=".$row_contact['id']."&msg=1");

		}

	} // end if ($action == "email")

	elseif ((isset($_SESSION['loginUsername'])) && (isset($_SESSION['userLevel']))) {

		if ($action == "add") {
			$insertSQL = sprintf("INSERT INTO $contacts_db_table (
			contactFirstName,
			contactLastName,
			contactPosition,
			contactEmail
			)
			VALUES
			(%s, %s, %s, %s)",
							   GetSQLValueString(sterilize(ucwords($_POST['contactFirstName'])), "text"),
							   GetSQLValueString(sterilize(ucwords($_POST['contactLastName'])), "text"),
							   GetSQLValueString(sterilize(ucwords($_POST['contactPosition'])), "text"),
							   GetSQLValueString(strtolower(filter_var($_POST['contactEmail'], FILTER_SANITIZE_EMAIL)), "text"));
			//echo $insertSQL;
			mysqli_real_escape_string($connection,$insertSQL);
			$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));
			$pattern = array('\'', '"');
			$insertGoTo = str_replace($pattern, "", $insertGoTo);
			$redirect_go_to = sprintf("Location: %s", stripslashes($insertGoTo));

		}

		if ($action == "edit") {
			$updateSQL = sprintf("UPDATE $contacts_db_table SET
			contactFirstName=%s,
			contactLastName=%s,
			contactPosition=%s,
			contactEmail=%s
			WHERE id=%s",
							   GetSQLValueString(sterilize(ucwords($_POST['contactFirstName'])), "text"),
							   GetSQLValueString(sterilize(ucwords($_POST['contactLastName'])), "text"),
							   GetSQLValueString(sterilize(ucwords($_POST['contactPosition'])), "text"),
							   GetSQLValueString(strtolower(filter_var($_POST['contactEmail'], FILTER_SANITIZE_EMAIL)), "text"),
							   GetSQLValueString($id, "int"));

			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			$pattern = array('\'', '"');
			$updateGoTo = str_replace($pattern, "", $updateGoTo);
			$redirect_go_to = sprintf("Location: %s", stripslashes($updateGoTo));

		}


	}
} else {
	$redirect_go_to = sprintf("Location: %s", $base_url."index.php?msg=98");
}


?>