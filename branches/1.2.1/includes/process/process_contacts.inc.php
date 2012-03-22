<?php 
/*
 * Module:      process_contacts.inc.php
 * Description: This module does all the heavy lifting for adding/editing info in the "contacts" table
 */

if ($action == "add") {
	$insertSQL = sprintf("INSERT INTO contacts (
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
					   GetSQLValueString($_POST['contactEmail'], "text"));
	//echo $insertSQL;				   
	mysql_select_db($database, $brewing);
  	$Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
	header(sprintf("Location: %s", $insertGoTo));
	
}

if ($action == "edit") {
	$updateSQL = sprintf("UPDATE contacts SET 
	contactFirstName=%s, 
	contactLastName=%s, 
	contactPosition=%s, 
	contactEmail=%s
	WHERE id=%s",
                       GetSQLValueString(capitalize($_POST['contactFirstName']), "text"),
                       GetSQLValueString(capitalize($_POST['contactLastName']), "text"),
                       GetSQLValueString(capitalize($_POST['contactPosition']), "text"),
					   GetSQLValueString($_POST['contactEmail'], "text"),
					   GetSQLValueString($id, "int"));
					   
	mysql_select_db($database, $brewing);
  	$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
  	header(sprintf("Location: %s", $updateGoTo));
	
}

if ($action == "email") { 

// CAPCHA check
include_once  (ROOT.'captcha/securimage.php');
$securimage = new Securimage();

	if ($securimage->check($_POST['captcha_code']) == false) {
		setcookie("to", $_POST['to'], 0, "/"); // $id of contact record in contacts table
		setcookie("from_email", $_POST['from_email'], 0, "/");
		setcookie("from_name", $_POST['from_name'], 0, "/");
		setcookie("subject", $_POST['subject'], 0, "/");
		setcookie("message", $_POST['message'], 0, "/");
		header("Location: ../index.php?section=contact&action=email&msg=2");
	}

	else {

		mysql_select_db($database, $brewing);
		$query_contact = sprintf("SELECT * FROM contacts WHERE id='%s'", $_POST['to']);
		$contact = mysql_query($query_contact, $brewing) or die(mysql_error());
		$row_contact = mysql_fetch_assoc($contact);
		
		// Gather the variables from the form
		$to_email = $row_contact['contactEmail'];
		$to_name = $row_contact['contactFirstName']." ".$row_contact['contactLastName'];
		$from_email = $_POST['from_email'];
		$from_name = $_POST['from_name'];
		$subject = $_POST['subject'];
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
		
		// Debug
		//echo $to_email."<br>";
		//echo $to_name."<br>";
		//echo $headers."<br>";
		//echo $message;
		
		mail($to_email, $subject, $message, $headers);
		header("Location: ../index.php?section=contact&action=email&msg=1&id=".$row_contact['id']);
	}
}

?>