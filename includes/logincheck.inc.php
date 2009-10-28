<?php
require '../Connections/config.php';
//require 'authentication.inc.php';
require 'login_check.inc.php';


/* Debug

echo $hostname."<br>";
echo $username."<br>";
echo $database."<br>";
*/
// Clean the data collected in the <form>
function sterilize ($sterilize=NULL) 
{
	if ($sterilize==NULL) {return NULL;}
	$check = array (1 => "'", 2 => '"', 3 => '<', 4 => '>');
	foreach ($check as $value) {
	$sterilize=str_replace($value, '', $sterilize);
		}
		$sterilize=strip_tags ($sterilize);
		$sterilize=stripcslashes ($sterilize);
		$sterilize=stripslashes ($sterilize);
		$sterilize=addslashes ($sterilize);
	return $sterilize;
	}


$loginUsername = $_POST['loginUsername'];
$loginPassword = $_POST['loginPassword'];

mysql_select_db($database, $brewing);
$password = md5($loginPassword);
$query_login = "SELECT password FROM users WHERE user_name = '$loginUsername' AND password = '$password'";
$login = mysql_query($query_login, $brewing) or die(mysql_error());
$row_login = mysql_fetch_assoc($login);
$totalRows_login = mysql_num_rows($login);

session_start();
// Authenticate the user
	if ($totalRows_login == 1)
	{
  		// Register the loginUsername
  		$_SESSION["loginUsername"] = $loginUsername;

  		// If the username/password combo is OK, relocate to the "protected" content index page
  		header("Location: ../index.php?section=list");
  		exit;
	}
else
	{
  		// If the username/password combo is incorrect or not found, relocate to the login error page
  		header("Location: ../index.php?section=login&msg=1");
  		session_destroy();
  		exit;
	}
?>
