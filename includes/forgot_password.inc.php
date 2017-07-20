<?php
/*
Checked Single
2016-06-06
*/

ob_start();
include('../paths.php');
require(CONFIG.'config.php');
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php');
require(LANG.'language.lang.php');
require(LIB.'common.lib.php'); 
require(DB.'common.db.php');
require(CLASSES.'phpass/PasswordHash.php');
$hasher = new PasswordHash(8, false);
mysqli_select_db($connection,$database);


if (($action == "email") && ($id != "default")) {
		
	$query_forgot = sprintf("SELECT * FROM $users_db_table WHERE id = '%s'",$id);
	$forgot = mysqli_query($connection,$query_forgot) or die (mysqli_error($connection));
	$row_forgot = mysqli_fetch_assoc($forgot);
	$totalRows_forgot = mysqli_num_rows($forgot);
	
	$query_brewer = sprintf("SELECT brewerLastName,brewerFirstName FROM $brewer_db_table WHERE uid = '%s'",$id);
	$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
	$row_brewer = mysqli_fetch_assoc($brewer);
	$totalRows_brewer = mysqli_num_rows($brewer);

	$first_name = ucwords(strtolower($row_brewer['brewerFirstName']));
	$last_name = ucwords(strtolower($row_brewer['brewerLastName']));
	
	$to_recipient = $first_name." ".$last_name;
	$to_email = $row_forgot['user_name'];
	$subject = sprintf("%s: %s",$_SESSION['contestName'],$label_id_verification_request);
	
	$message = "<html>" . "\r\n";
	$message .= "<body>" . "\r\n";
	$message .= sprintf("<p>%s,</p>",$first_name);
	$message .= sprintf("<p>%s %s %s</p>",$pwd_email_reset_text_000,$_SESSION['contestName'],$pwd_email_reset_text_001);
	$message .= "<table cellpadding='0' border='0'>";
	$message .= sprintf("<tr><td><strong>%s:</strong></td><td>%s</td>",$label_id_verification_question, $row_forgot['userQuestion']);
	$message .= sprintf("<tr><td><strong>%s:</strong></td><td>%s</td>",$label_id_verification_answer, $row_forgot['userQuestionAnswer']);
	$message .= "</table>";
	$message .= sprintf("<p><em>*%s</em></p>",$pwd_email_reset_text_002);
	$message .= sprintf("<p>%s %s</p>",ucwords($_SESSION['contestName']),$label_server);
	$message .= sprintf("<p><small>%s</small></p>", $paypal_response_text_003);
	$message .= "</body>" . "\r\n";
	$message .= "</html>";
	
	$url = str_replace("www.","",$_SERVER['SERVER_NAME']);
	
	$headers  = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
	$headers .= "To: ".$to_recipient. " <".$to_email.">, " . "\r\n";
	$headers .= "From: ".ucwords($_SESSION['contestName'])." Server <noreply@".$url. ">\r\n";
	
	$emails = $to_email;
	mail($emails, $subject, $message, $headers);
	
	/*
	echo $headers."<br>";
	echo $subject."<br>";
	echo $message;
	exit;
	*/
	
	header(sprintf("Location: %s", $base_url."index.php?section=login&action=forgot&go=verify&msg=5&username=".$to_email));
}

if ($action == "forgot") {
	
	$username = $_POST['loginUsername'];
		
	$query_forgot = sprintf("SELECT * FROM %s WHERE user_name = '%s'",$users_db_table,$_POST['loginUsername']);
	$forgot = mysqli_query($connection,$query_forgot) or die (mysqli_error($connection));
	$row_forgot = mysqli_fetch_assoc($forgot);
	$totalRows_forgot = mysqli_num_rows($forgot);
			
	if ($totalRows_forgot == 0) { 
		header(sprintf("Location: %s", $base_url."index.php?section=login&action=forgot&msg=1"));
		exit; 
	}

	//if answer is correct
	if (strtolower(sterilize($_POST['userQuestionAnswer'])) == strtolower(sterilize($row_forgot['userQuestionAnswer']))) { 
		
		$query_brewer = sprintf("SELECT brewerLastName,brewerFirstName FROM %s WHERE uid = '%s'", $brewer_db_table, $row_forgot['id']);
		$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
		$row_brewer = mysqli_fetch_assoc($brewer);
		$totalRows_brewer = mysqli_num_rows($brewer);

		$first_name = ucwords(strtolower($row_brewer['brewerFirstName']));
		$last_name = ucwords(strtolower($row_brewer['brewerLastName']));
		
		// Generate unique token by first getting a random string
		$token = openssl_random_pseudo_bytes(16);

		//Convert the binary data into hexadecimal representation
		$token = bin2hex($token);
		
		// Update the users table with token and the time token was generated
		$updateSQL = sprintf("UPDATE %s SET userToken='%s', userTokenTime='%s' WHERE id='%s'", $users_db_table, $token, time(), $row_forgot['id']);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		
		$reset_url = $base_url."index.php?section=login&go=password&action=reset-password&token=".$token;
		
		$to_recipient = $first_name." ".$last_name;
		$to_email = $row_forgot['user_name'];
		$subject = sprintf("%s: %s",$_SESSION['contestName'],$label_password_reset);
		$message = "<html>" . "\r\n";
		$message .= "<body>" . "\r\n";
		$message .= sprintf("<p>%s,</p>",$first_name);
		$message .= sprintf("<p>%s %s %s</p>",$pwd_email_reset_text_003,$_SESSION['contestName'],$pwd_email_reset_text_004);
		$message .= sprintf("<p>%s</p>",$pwd_email_reset_text_005);
		$message .= sprintf("<p><a href=\"%s\">%s</a></p>", $reset_url, $reset_url);
		$message .= ucwords($_SESSION['contestName'])." ".$label_server;
		$message .= "<p><small>".$paypal_response_text_003."</small></p>";
		$message .= "</body>" . "\r\n";
		$message .= "</html>";

		$url = str_replace("www.","",$_SERVER['SERVER_NAME']);

		$headers  = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
		$headers .= "To: ".$to_recipient. " <".$to_email.">, " . "\r\n";
		$headers .= "From: ".ucwords($_SESSION['contestName'])." Server <noreply@".$url. ">\r\n";

		$emails = $to_email;
		mail($emails, $subject, $message, $headers);
		
		/*
		// Debug
		echo $username."<br>";
		echo $query_forgot."<br>";
		echo $row_forgot['user_name']."<br>";
		echo $totalRows_forgot."<br>";
		echo $updateSQL."<br>";
		echo $emails."<br>".;
		echo $headers."<br>";
		echo $subject."<br>";
		echo $message;
		exit;
		*/
		
		$updateGoTo = $base_url."index.php?msg=6";
		header(sprintf("Location: %s", $updateGoTo)); 
		exit;
	
	} else {
		
		$updateGoTo = sprintf($base_url."index.php?section=login&action=forgot&go=verify&msg=4&username=%s", sterilize($_POST['loginUsername']));
		header(sprintf("Location: %s", $updateGoTo)); 
		exit;
		
	}
	
}

if ($action == "reset") {
	
	// First, check if email entered corresponds to the token provided
	$query_reset = sprintf("SELECT * FROM %s WHERE user_name = '%s' and userToken='%s'", $users_db_table, sterilize($_POST['loginUsername']), $token);
	$reset = mysqli_query($connection,$query_reset) or die (mysqli_error($connection));
	$row_reset = mysqli_fetch_assoc($reset);
	$totalRows_reset = mysqli_num_rows($reset);
	
	// If no match, redirect
	if ($totalRows_reset == 0) {
		
		$updateGoTo = sprintf($base_url."index.php?section=login&go=password&action=reset-password&msg=7&token=%s",$token);
		header(sprintf("Location: %s", $updateGoTo)); 
		exit;
		
	}
	
	if ($totalRows_reset == 1) {
		
		// Check and see if both entered passwords match
		// If so, hash the password
		if ((sterilize($_POST['newPassword1']) == sterilize($_POST['newPassword2']))) {
			
			// Update the database
			$password = md5(sterilize($_POST['newPassword1']));
			$hasher = new PasswordHash(8, false);
			$hash = $hasher->HashPassword($password);
			
			// Update the DB with the new password and clear the token
			$updateSQL = sprintf("UPDATE $users_db_table SET password='%s', userToken=NULL, userTokenTime=NULL WHERE id='%s'", $hash, $row_reset['id']);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		
			// Login the user with new creds
			$_SESSION['loginUsername'] =  sterilize($_POST['loginUsername']);
			
			// Redirect to appropriate area
			$updateGoTo = $base_url."index.php?section=login&msg=8";
			header(sprintf("Location: %s", $updateGoTo)); 
			exit;
			
		}
		
		// If not, redirect
		else {
			
			$updateGoTo = sprintf($base_url."index.php?section=login&go=password&action=reset-password&msg=6&token=%s",$token);
			header(sprintf("Location: %s", $updateGoTo)); 
			exit;
			
		}	
		
	}	
	
}
?>