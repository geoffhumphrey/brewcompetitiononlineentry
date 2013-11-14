<?php 
/*
 * Module:      process_contacts.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "contacts" table
 */

if ($action == "email") { 

	require_once(INCLUDES.'recaptchalib.inc.php');
	$privatekey = "6LdquuQSAAAAAHkf3dDRqZckRb_RIjrkofxE8Knd";
	$resp = recaptcha_check_answer ($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
	
	if (!$resp->is_valid) {
		// What happens when the CAPTCHA was entered incorrectly
		//die ("The reCAPTCHA wasn't entered correctly. Go back and try it again."."(reCAPTCHA said: " . $resp->error . ")");
		
		setcookie("to", $_POST['to'], 0, "/"); // $id of contact record in contacts table
		setcookie("from_email", strtolower($_POST['from_email']), 0, "/");
		setcookie("from_name", capitalize($_POST['from_name']), 0, "/");
		setcookie("subject", capitalize($_POST['subject']), 0, "/");
		setcookie("message", $_POST['message'], 0, "/");
		header(sprintf("Location: %s", $base_url."index.php?section=contact&action=email&msg=2"));
		
	} // end if (!$resp->is_valid)
	
	else {
		
		
		if (NHC) {
	// Place NHC SQL calls below
	
	
		}
		
		
		else {
		
			mysql_select_db($database, $brewing);
			$query_contact = sprintf("SELECT * FROM $contacts_db_table WHERE id='%s'", $_POST['to']);
			$contact = mysql_query($query_contact, $brewing) or die(mysql_error());
			$row_contact = mysql_fetch_assoc($contact);
			//echo $query_contact;
		
		} // end if (NHC)
		
		
		// Gather the variables from the form
		$to_email = $row_contact['contactEmail'];
		$to_name = $row_contact['contactFirstName']." ".$row_contact['contactLastName'];
		$from_email = strtolower($_POST['from_email']);
		$from_name = capitalize($_POST['from_name']);
		$subject = capitalize($_POST['subject']);
		$message_post = $_POST['message'];
		
		// Build the message
		$message = "<html>" . "\r\n";
		//$message .= "<head>" . $subject."</head>" . "\r\n";
		$message .= "<body>" . $message_post. "\r\n". "</body>" . "\r\n";
		$message .= "</html>";
		
		$headers  = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
		$headers .= "To: ".$to_name." <".$to_email.">, " . "\r\n";
		$headers .= "From: ".$from_name." <".$from_email.">" . "\r\n";
		$headers .= "CC: ".$from_name." <".$from_email.">" . "\r\n";
		
		/*
		// Debug
		echo $to_email."<br>";
		echo $to_name."<br>";
		echo $headers."<br>";
		echo $message;
		*/
		
		mail($to_email, $subject, $message, $headers);
		header(sprintf("Location: %s", $base_url."index.php?section=contact&action=email&id=".$row_contact['id']."&msg=1"));
		
	} // end else if (!$resp->is_valid)

} // end if ($action == "email")

elseif ((isset($_SESSION['loginUsername'])) && (isset($_SESSION['userLevel']))) {
	
	if (NHC) {
		// Place NHC SQL calls below
		
		
	}
	// end if (NHC)
	
	else {
	
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
							   GetSQLValueString(strtolower($_POST['contactEmail']), "text"));
			//echo $insertSQL;				   
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($insertSQL);
			$result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
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
							   GetSQLValueString(strtolower($_POST['contactEmail']), "text"),
							   GetSQLValueString($id, "int"));
							   
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL);
			$result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
			$pattern = array('\'', '"');
			$updateGoTo = str_replace($pattern, "", $updateGoTo); 
			header(sprintf("Location: %s", stripslashes($updateGoTo)));
			
		}
	
	} // end else NHC

} 

else echo "<p>Not available.</p>";


?>