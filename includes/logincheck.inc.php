<?php
require('../paths.php');
require(CONFIG.'config.php');
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php');

if (NHC) $base_url = "../";
else $base_url = $base_url;

//require 'authentication.inc.php';
//require 'login_check.inc.php';
/* Debug

echo $hostname."<br>";
echo $username."<br>";
echo $database."<br>";
*/
// Clean the data collected in the <form>
function sterilize ($sterilize=NULL) 
{
	if ($sterilize==NULL) { return NULL; }
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

$loginUsername = sterilize($_POST['loginUsername']);
$loginPassword = sterilize($_POST['loginPassword']);

mysql_real_escape_string($loginUsername);
mysql_real_escape_string($loginPassword);

mysql_select_db($database, $brewing);
$password = md5($loginPassword);
$query_login = "SELECT * FROM $users_db_table WHERE user_name = '$loginUsername' AND password = '$password'";
$login = mysql_query($query_login, $brewing) or die(mysql_error());
$row_login = mysql_fetch_assoc($login);
$totalRows_login = mysql_num_rows($login);

$loginUsername = strtolower($loginUsername);

session_start();
session_regenerate_id(true); 
// Authenticate the user
	if ($totalRows_login == 1) {
  		// Register the loginUsername but first update the db record to make sure the the user name is stored as all lowercase.
		$updateSQL = sprintf("UPDATE $users_db_table SET user_name='%s' WHERE id='%s'",$loginUsername, $row_login['id']);
		
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		//echo $updateSQL."<br>";
		$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		
		// Convert email address in the user's accociate record in the "brewer" table
		$updateSQL = sprintf("UPDATE $brewer_db_table SET brewerEmail='%s' WHERE uid='%s'",$loginUsername, $row_login['id']);
		//echo $updateSQL."<br>";
		mysql_real_escape_string($updateSQL);
		$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
		
  		$_SESSION['loginUsername'] = $loginUsername;
		//echo $_SESSION['loginUsername'];
  		// If the username/password combo is OK, relocate to the "protected" content index page
		if (($section != "update") && ($row_login['userLevel'] == "2")) header(sprintf("Location: %s", $base_url."index.php?section=list"));
		elseif (($section != "update") && ($row_login['userLevel'] <= "1")) header(sprintf("Location: %s", $base_url."index.php?section=admin"));
		else header("Location: ../update.php");
  		exit;
	}
else {
  		// If the username/password combo is incorrect or not found, relocate to the login error page
  		header(sprintf("Location: %s", $base_url."index.php?section=login&msg=1"));
  		session_destroy();
  		exit;
	}
?>