<?php
ob_start();
include('../paths.php');
require(CONFIG.'config.php');
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php');
require(LIB.'common.lib.php'); 
require(DB.'common.db.php');
require(CLASSES.'phpass/PasswordHash.php');
$hasher = new PasswordHash(8, false);
mysql_select_db($database, $brewing);

if (($action == "email") && ($id != "default")) {
		
	$query_forgot = "SELECT * FROM $users_db_table WHERE id = '$id'";
	$forgot = mysql_query($query_forgot, $brewing) or die(mysql_error());
	$row_forgot = mysql_fetch_assoc($forgot);
	$totalRows_forgot = mysql_num_rows($forgot);
	
	$query_brewer = "SELECT brewerLastName,brewerFirstName FROM $brewer_db_table WHERE uid = '$id'";
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);

	$first_name = ucwords(strtolower($row_brewer['brewerFirstName']));
	$last_name = ucwords(strtolower($row_brewer['brewerLastName']));
	
		
	$to_recipient = $first_name." ".$last_name;
	$to_email = $row_forgot['user_name'];
	$subject = $_SESSION['contestName'].": ID Verification Request";
	$message = "<html>" . "\r\n";
	$message .= "<body>" . "\r\n";
	$message .= "<p>".$first_name.",</p>";
	$message .= "<p>A request was made to verify the account at the ".$_SESSION['contestName']." competition website using the ID Verfication email function. If you did not initiate this, please contact the competition's organizer.</p>";
	$message .= "<table cellpadding='0' border='0'><tr><td><strong>ID Verfication Question:</strong></td><td>".$row_forgot['userQuestion']."</td>";
	$message .= "<tr><td><strong>ID Verfication Answer:</strong></td><td>".$row_forgot['userQuestionAnswer']."</td></tr></table>";
	$message .= "<p><em>*The ID Verification Answer is case sensitive.</em></p>";
	$message .= "<p>Please do not reply to this email as it is automatically generated. The originating account is not active or monitored.</p>";
	$message .= "</body>" . "\r\n";
	$message .= "</html>";
	
	//$parse = parse_url($_SERVER['SERVER_NAME']);
	//print_r($parse);
	//$url = $parse['host'];
	//$url = preg_replace('#^www\.(.+\.)#i', '$1', $parse['host']);
	$url = str_replace("www.","",$_SERVER['SERVER_NAME']);
	
	$headers  = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
	$headers .= "To: ".$to_recipient. " <".$to_email.">, " . "\r\n";
	$headers .= "From: Competition Server <noreply@".$url. ">\r\n";
	
	$emails = $to_email;
	mail($emails, $subject, $message, $headers);
	
	//echo $headers."<br>";
	//echo $subject."<br>";
	//echo $message;
	header(sprintf("Location: %s", $base_url."index.php?section=login&action=forgot&go=verify&msg=5&username=".$to_email));
}

else {
	$username = $_POST['loginUsername'];
		
	$query_forgot = sprintf("SELECT * FROM %s WHERE user_name = '%s'",$users_db_table,$username);
	$forgot = mysql_query($query_forgot, $brewing) or die(mysql_error());
	$row_forgot = mysql_fetch_assoc($forgot);
	$totalRows_forgot = mysql_num_rows($forgot);
			
	if ($totalRows_forgot == 0) { 
		header(sprintf("Location: %s", $base_url."index.php?section=login&action=forgot&msg=1")); 
	}
	
	if (strtolower($_POST['userQuestionAnswer']) == strtolower($row_forgot['userQuestionAnswer'])) { //if answer is correct
	
		$key = random_generator(10,1);
	
		$password = md5($key);
		$hash = $hasher->HashPassword($password);
			
		mysql_select_db($database, $brewing);
		$updateSQL = sprintf("UPDATE $users_db_table SET password='%s' WHERE id='%s'", $hash, $row_forgot['id']);
		$Result = mysql_query($updateSQL, $brewing) or die(mysql_error());
		
	  	$updateGoTo = $base_url."index.php?section=login&go=".$key."&msg=2";
	  	header(sprintf("Location: %s", $updateGoTo)); 

	} else {
	
		header(sprintf("Location: %s", $base_url."index.php?section=login&action=forgot&go=verify&msg=4&username=".$username)); 
	
    	}
}
?>
