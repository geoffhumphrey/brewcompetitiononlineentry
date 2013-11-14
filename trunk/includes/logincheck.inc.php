<?php
ob_start();
require('../paths.php');
require(CONFIG.'config.php');
mysql_select_db($database, $brewing);
//require(CONFIG.'bootstrap.php');
//require(INCLUDES.'url_variables.inc.php'); 
//require(INCLUDES.'db_tables.inc.php');

$section = "default";
if (isset($_GET['section'])) {
  $section = (get_magic_quotes_gpc()) ? $_GET['section'] : addslashes($_GET['section']);
}

header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); 
header('Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT'); 
header('Cache-Control: no-store, no-cache, must-revalidate'); 
header('Cache-Control: post-check=0, pre-check=0', false); 
header('Pragma: no-cache'); 

require(CLASSES.'phpass/PasswordHash.php');
$hasher = new PasswordHash(8, false);

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
$location = $base_url."index.php?section=login";

if (NHC) $base_url = "../";
else $base_url = $base_url;

if (strlen($password) > 72) { 
	session_destroy();
	header(sprintf("Location: %s", $base_url."index.php?section=login&msg=1"));
	exit;
}

mysql_real_escape_string($loginUsername);
mysql_real_escape_string($password);
$password = md5($password);


if (NHC) {
	// Place NHC SQL calls below
	
	
}
// end if (NHC)

else {

	// ---------------------------------------------------------------
	// ONLY for 1.3.0.0 release
	// DELETE for future releases
	// Has to do with the hashing of passwords introduced in 1.3.0.0
	// ---------------------------------------------------------------
	
	if ($section == "update") {
		
		$query_login = sprintf("SELECT * FROM %s WHERE user_name = '%s' AND password = '%s'",$prefix."users",$loginUsername,$password);
		$login = mysql_query($query_login, $brewing) or die(mysql_error());
		$row_login = mysql_fetch_assoc($login);
		$totalRows_login = mysql_num_rows($login);
		
		$loginUsername = strtolower($loginUsername);
		
		if ($totalRows_login == 1) $check = 1;
		else $check = 0;
		
		//echo $query_login."<br>";
		
	}
	
	// ---------------------------------------------------------------
	
	if ($section != "update") {
		
		$loginUsername = strtolower($loginUsername);	
		$query_login = sprintf("SELECT * FROM %s WHERE user_name = '%s'",$prefix."users",$loginUsername);
		$login = mysql_query($query_login, $brewing) or die(mysql_error());
		$row_login = mysql_fetch_assoc($login);
		$totalRows_login = mysql_num_rows($login);
		
		$stored_hash = $row_login['password'];
		
		$check = 0;
		
		if ($totalRows_login > 0) {
			$check = $hasher->CheckPassword($password, $stored_hash);
		}
		
		/*
		echo $query_login."<br>";
		echo $password."<br>";
		echo $check."<br>";
		*/
		
	} // end if ($section != "update")

} // end else NHC



// If the username/password combo is valid, register a session, register a session cookie
// perform certain tasks and redirect
if ($check == 1) {
	
	// Start the session and regenerate the session ID
	session_start();
	session_regenerate_id(true); 
	
	if (NHC) {
	// Place NHC SQL calls below
	
	
	}
	// end if (NHC)
	
	else {
	
		// Register the loginUsername but first update the db record to make sure the the user name is stored as all lowercase.
		$updateSQL = sprintf("UPDATE %s SET user_name='%s' WHERE id='%s'",$prefix."users",$loginUsername, $row_login['id']);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
		// Convert email address in the user's accociate record in the "brewer" table
		$updateSQL = sprintf("UPDATE %s SET brewerEmail='%s' WHERE uid='%s'",$prefix."brewer",$loginUsername, $row_login['id']);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
	} // end else NHC
	
	// Regiseter the session variable
	$_SESSION['loginUsername'] = $loginUsername;
	
	// Set the relocation variables
	if ($section == "update") $location = $base_url."update.php";
	else {
		if ($row_login['userLevel'] <= 1) $location = $base_url."index.php?section=admin";
		else $location = $base_url."index.php?section=list";
	}
	
	//echo $loginUsername."<br>";
	//echo $location."<br>";
	//echo "Yes."."<br>";
	
}

// If the username/password combo is incorrect or not found, 
// destroy the session and relocate to the login error page.
else {
	$location = $base_url."index.php?section=login&msg=1";
	session_destroy();
}

// Relocate
header(sprintf("Location: %s", $location, true));
exit;
?>