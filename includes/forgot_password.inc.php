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
require(LIB.'common.lib.php'); 
require(DB.'common.db.php');
require(CLASSES.'phpass/PasswordHash.php');
$hasher = new PasswordHash(8, false);
mysqli_select_db($connection,$database);


if (($action == "email") && ($id != "default")) {
		
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
		
	$query_forgot = sprintf("SELECT * FROM %s WHERE user_name = '%s'",$users_db_table,$_POST['loginUsername']);
	$forgot = mysqli_query($connection,$query_forgot) or die (mysqli_error($connection));
	$row_forgot = mysqli_fetch_assoc($forgot);
	$totalRows_forgot = mysqli_num_rows($forgot);
			
	if ($totalRows_forgot == 0) { 
		header(sprintf("Location: %s", $base_url."index.php?section=login&action=forgot&msg=1"));
		exit; 
	}

	//if answer is correct
	if (strtolower($_POST['userQuestionAnswer']) == strtolower($row_forgot['userQuestionAnswer'])) { 
	
		/*
		echo $username."<br>";
		echo $query_forgot."<br>";
		echo $row_forgot['user_name']."<br>";
		echo $totalRows_forgot."<br>";
		
		$em = $row->username;// email is stored to a variable
		
		*/
			
		$key = random_generator(6,1);
		
		$password = md5($key);
		$hash = $hasher->HashPassword($password);
		
		$updateSQL = sprintf("UPDATE $users_db_table SET password='%s' WHERE id='%s'", $hash, $row_forgot['id']);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		
		$updateGoTo = $base_url."index.php?section=login&go=".$key."&msg=2";
		header(sprintf("Location: %s", $updateGoTo)); 
		exit;
	
	} else {
		$updateGoTo = sprintf($base_url."index.php?section=login&action=forgot&go=verify&msg=4&username=%s",$_POST['loginUsername']);
		header(sprintf("Location: %s", $updateGoTo)); 
		exit;
	}	
}
?>