<?php 

/*
 * Module:      process_contacts.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "contacts" table
 */
$captcha_success = FALSE;

if (isset($_SERVER['HTTP_REFERER'])) { 
	
	if ($action == "email") { 
		
		if ((isset($_POST['g-recaptcha-response'])) && (!empty($_POST['g-recaptcha-response']))) {
			
			$privatekey = "6LdUsBATAAAAAMPhk5yRSmY5BMXlBgcTjiLjiyPb";
			
			$verify_response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$privatekey.'&response='.$_POST['g-recaptcha-response']);
			$response_data = json_decode($verify_response);
			
			if (($_SERVER['SERVER_NAME'] = $response_data->hostname) && ($response_data->success)) $captcha_success = TRUE;
			
		}
		
		if (!$captcha_success) {
			
			setcookie("to", $_POST['to'], 0, "/"); // $id of contact record in contacts table
			setcookie("from_email", strtolower(filter_var($_POST['from_email'], FILTER_SANITIZE_EMAIL)), 0, "/");
			setcookie("from_name", capitalize($_POST['from_name']), 0, "/");
			setcookie("subject", capitalize($_POST['subject']), 0, "/");
			setcookie("message", $_POST['message'], 0, "/");
			header(sprintf("Location: %s", $base_url."index.php?section=contact&action=email&msg=2"));
			
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
			$from_name = capitalize($_POST['from_name']);
			$subject = capitalize($_POST['subject']);
			$message_post = $_POST['message'];
			
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
			header(sprintf("Location: %s", $base_url."index.php?section=contact&action=email&id=".$row_contact['id']."&msg=1"));
			
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
							   GetSQLValueString(capitalize($_POST['contactFirstName']), "text"),
							   GetSQLValueString(capitalize($_POST['contactLastName']), "text"),
							   GetSQLValueString(capitalize($_POST['contactPosition']), "text"),
							   GetSQLValueString(strtolower(filter_var($_POST['contactEmail'], FILTER_SANITIZE_EMAIL)), "text"));
			//echo $insertSQL;				   
			mysqli_real_escape_string($connection,$insertSQL);
			$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));
			$pattern = array('\'', '"');
			$insertGoTo = str_replace($pattern, "", $insertGoTo); 
			header(sprintf("Location: %s", stripslashes($insertGoTo)));
			
		}
		
		if ($action == "edit") {
			$updateSQL = sprintf("UPDATE $contacts_db_table SET 
			contactFirstName=%s, 
			contactLastName=%s, 
			contactPosition=%s, 
			contactEmail=%s
			WHERE id=%s",
							   GetSQLValueString(capitalize($_POST['contactFirstName']), "text"),
							   GetSQLValueString(capitalize($_POST['contactLastName']), "text"),
							   GetSQLValueString(capitalize($_POST['contactPosition']), "text"),
							   GetSQLValueString(strtolower(filter_var($_POST['contactEmail'], FILTER_SANITIZE_EMAIL)), "text"),
							   GetSQLValueString($id, "int"));
							   
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			$pattern = array('\'', '"');
			$updateGoTo = str_replace($pattern, "", $updateGoTo); 
			header(sprintf("Location: %s", stripslashes($updateGoTo)));
			
		}
		
	
	} 
} else { 
	header(sprintf("Location: %s", $base_url."index.php?msg=98"));
	exit;
}


?>