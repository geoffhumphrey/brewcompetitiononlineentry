<?php
include('../output/output.bootstrap.php')
// Based upon a script from www.plus2net.com 
require(CONFIG.'config.php');
require(INCLUDES.'functions.inc.php'); 
require(DB.'common.db.php');
$username = $_POST['loginUsername'];

mysql_select_db($database, $brewing);
$query_forgot = "SELECT * FROM users WHERE user_name = '$username'";
$forgot = mysql_query($query_forgot, $brewing) or die(mysql_error());
$row_forgot = mysql_fetch_assoc($forgot);
$totalRows_forgot = mysql_num_rows($forgot);

if ($totalRows_forgot == 0) { header("Location: ../index.php?section=login&action=forgot&msg=1"); }
if ($_POST['userQuestionAnswer'] == $row_forgot['userQuestionAnswer']) { //if answer is correct

/*
echo $username."<br>";
echo $query_forgot."<br>";
echo $row_forgot['user_name']."<br>";
echo $totalRows_forgot."<br>";

$em = $row->username;// email is stored to a variable

// Send the email with key
*/

$key = random_generator(10,1);

$password = md5($key);
$updateSQL = sprintf("UPDATE users SET password='%s' WHERE user_name='%s'", $password, $username);
					   
  mysql_select_db($database, $brewing);
  $Result = mysql_query($updateSQL, $brewing) or die(mysql_error());
  
  $updateGoTo = "../index.php?section=login&go=".$key."&msg=2";
  header(sprintf("Location: %s", $updateGoTo)); 

} else {
header("Location: ../index.php?section=login&action=forgot&go=verify&msg=4&username=".$username); 
}

/*
$headers4	 = $row_contest_info['contestContactEmail'];
$headers	.= "Reply-to: $headers4\n";
$headers 	.= "From: $headers4\n"; 
$headers 	.= "Errors-to: $headers4\n"; 
$headers 	= "Content-Type: text/html; charset=iso-8859-1\n".$headers;// for html mail
 
if (mail("$em","Password Reset","This is in response to your request for a password reset at from the ".$row_contest_info['contestName']." entry site. \n \nYour user name: $row->userid \n Your new password: ".$key."\n\n
\n\n Thank You \n \n The ".$row_contest_info['contestName']." Staff","$headers")) 
{
header("Location: ../index.php?section=login&action=forgot&msg=2");
}
else
{ 
header("Location: ../index.php?section=login&action=forgot&msg=3");
}
*/

//}
?>