<?php
$url = str_replace("www.","",$_SERVER['SERVER_NAME']);

$from_name = $_SESSION['contestName']." Competition Server";
$from_email = "noreply@".$url;

$headers = "";
$message = "";

if ($filter == "test-email") {
	
	$query_forgot = "SELECT * FROM $users_db_table WHERE id = '$id'";
	$forgot = mysqli_query($connection,$query_forgot) or die (mysqli_error($connection));
	$row_forgot = mysqli_fetch_assoc($forgot);
	$totalRows_forgot = mysqli_num_rows($forgot);
	
	$query_brewer = "SELECT brewerLastName,brewerFirstName FROM $brewer_db_table WHERE uid = '$id'";
	$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
	$row_brewer = mysqli_fetch_assoc($brewer);
	$totalRows_brewer = mysqli_num_rows($brewer);

	$first_name = ucwords(strtolower($row_brewer['brewerFirstName']));
	$last_name = ucwords(strtolower($row_brewer['brewerLastName']));
	
	$to_recipient = $first_name." ".$last_name;
	$to_email = strtolower($row_forgot['user_name']);
	$subject = $_SESSION['contestName']." - System Generated Email Test";
	
	$message = "<html>" . "\r\n";
	$message .= "<body>" . "\r\n";
	$message .= "<p>".$first_name.",</p>";
	$message .= "<p>A request was made to verify ability to send system-generated emails from the  ".$_SESSION['contestName']." competition website. If you are reading this, the test was <strong>successful</strong>!</p>";
	$message .= "<p>Thank you,</p><p>The  ".$_SESSION['contestName']." Competition Server</p>";
	$message .= "<p><small>Please do not reply to this email as it is automatically generated. The originating account is not active or monitored.</small></p>";
	$message .= "</body>" . "\r\n";
	$message .= "</html>";
	
	$headers  = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
	$headers .= "To: ".$to_recipient. " <".$to_email.">, " . "\r\n";
	$headers .= "From: ".$from_name." <".$from_email.">" . "\r\n";
	
	mail($to_email, $subject, $message, $headers);
	
	header(sprintf("Location: %s", $base_url."index.php?section=admin&go=preferences&msg=32"));
	
}

?>