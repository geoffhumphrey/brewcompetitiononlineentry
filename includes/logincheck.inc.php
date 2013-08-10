<?php

require('../paths.php');
//require(CONFIG.'bootstrap.php');
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php');

mysql_select_db($database, $brewing);

// Clean the data collected in the <form>
function sterilize ($sterilize=NULL) {
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
$password = sterilize($_POST['loginPassword']);

if (NHC) $base_url = "../";
else $base_url = $base_url;

if (strlen($password) > 72) { 
	header(sprintf("Location: %s", $base_url."index.php?section=login&msg=1"));
	session_destroy();
	exit;
}

mysql_real_escape_string($loginUsername);
mysql_real_escape_string($password);
$password = md5($password);


// ---------------------------------------------------------------
// ONLY for 1.3.0.0 release
// DELETE for future releases
// ---------------------------------------------------------------
if ($section == "update") {
	
	$query_login = "SELECT * FROM $users_db_table WHERE user_name = '$loginUsername' AND password = '$password'";
	$login = mysql_query($query_login, $brewing) or die(mysql_error());
	$row_login = mysql_fetch_assoc($login);
	$totalRows_login = mysql_num_rows($login);
	
	$loginUsername = strtolower($loginUsername);
	
	if ($totalRows_login == 1) $check = TRUE;
	else $check = FALSE;
	
	//echo $query_login."<br>";
	
}

// ---------------------------------------------------------------
// END DELETE
// ---------------------------------------------------------------


else {
	
	require(CLASSES.'phpass/PasswordHash.php');
	$hasher = new PasswordHash(8, false);
	
	$query_login = "SELECT * FROM $users_db_table WHERE user_name = '$loginUsername'";
	$login = mysql_query($query_login, $brewing) or die(mysql_error());
	$row_login = mysql_fetch_assoc($login);
	$totalRows_login = mysql_num_rows($login);
	
	$loginUsername = strtolower($loginUsername);
	
	// Retrieve the hash from the DB
	$stored_hash = $row_login['password'];
	
	// Check that the password is correct, returns a boolean
	$check = $hasher->CheckPassword($password, $stored_hash);
	
}


// Authenticate the user
if ($check) {
	
	session_start();
	session_regenerate_id(true); 

	// Register the loginUsername but first update the db record to make sure the the user name is stored as all lowercase.
	$updateSQL = sprintf("UPDATE $users_db_table SET user_name='%s' WHERE id='%s'",$loginUsername, $row_login['id']);

	mysql_real_escape_string($updateSQL);
	$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
	// Convert email address in the user's accociate record in the "brewer" table
	$updateSQL = sprintf("UPDATE $brewer_db_table SET brewerEmail='%s' WHERE uid='%s'",$loginUsername, $row_login['id']);
	mysql_real_escape_string($updateSQL);
	$Result1 = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
	$_SESSION['loginUsername'] = $loginUsername;

	if (($section != "update") && ($row_login['userLevel'] == "2")) header(sprintf("Location: %s", $base_url."index.php?section=list"));
	elseif (($section != "update") && ($row_login['userLevel'] <= "1")) header(sprintf("Location: %s", $base_url."index.php?section=admin"));
	else header(sprintf("Location: %s", $base_url."update.php"));
	exit;
}

else {
	// If the username/password combo is incorrect or not found, relocate to the login error page
	header(sprintf("Location: %s", $base_url."index.php?section=login&msg=1"));
	session_destroy();
	exit;
}
?>