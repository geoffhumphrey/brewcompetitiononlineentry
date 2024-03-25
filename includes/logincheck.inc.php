<?php

// Redirect if directly accessed without authenticated session
if ((session_status() == PHP_SESSION_NONE) || ((isset($_SESSION['loginUsername'])) && (!function_exists('sterilize')))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

ob_start();
require (CLASSES.'phpass/PasswordHash.php');

$section = "default";
if (isset($_GET['section'])) $section = sterilize($_GET['section']);

header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); 
header('Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT'); 
header('Cache-Control: no-store, no-cache, must-revalidate'); 
header('Cache-Control: post-check=0, pre-check=0', false); 
header('Pragma: no-cache'); 

$hasher = new PasswordHash(8, false);
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

mysqli_real_escape_string($connection,$loginUsername);
mysqli_real_escape_string($connection,$password);
$password = md5($password);

/**
 * ONLY for 1.3.0.0 release; evaluate for deletion in future releases
 * Has to do with the hashing of passwords introduced in 1.3.0.0
 */

if ($section == "update") {
	
	$loginUsername = strtolower($loginUsername);	
	
	$query_login = sprintf("SELECT * FROM %s WHERE user_name = '%s'",$prefix."users",$loginUsername);
	$login = mysqli_query($connection,$query_login) or die (mysqli_error($connection));
	$row_login = mysqli_fetch_assoc($login);
	$totalRows_login = mysqli_num_rows($login);
	
	$stored_hash = $row_login['password'];
	
	$check = 0;
	
	if ($totalRows_login > 0) {
		$check = $hasher->CheckPassword($password, $stored_hash);
		$check = 1;
	}
	
	else $check = 0;
	
}

if ($section != "update") {
	
	$loginUsername = strtolower($loginUsername);	
	
	$query_login = sprintf("SELECT * FROM %s WHERE user_name = '%s'", $prefix."users",$loginUsername);
	$login = mysqli_query($connection,$query_login) or die (mysqli_error($connection));
	$row_login = mysqli_fetch_assoc($login);
	$totalRows_login = mysqli_num_rows($login);
	
	$stored_hash = $row_login['password'];
	$check = 0;
	
	if ($totalRows_login > 0) $check = $hasher->CheckPassword($password, $stored_hash);

}

/**
 * If the username/password combo is valid, register a session, 
 * register a session cookie perform certain tasks and redirect
 */

if ($check == 1) {
	
	// Register the loginUsername but first update the db record to make sure the the user name is stored as all lowercase.
	$updateSQL = sprintf("UPDATE %s SET user_name='%s' WHERE id='%s'",$prefix."users",$loginUsername, $row_login['id']);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

	// Convert email address in the user's accociated record in the "brewer" table
	$updateSQL = sprintf("UPDATE %s SET brewerEmail='%s' WHERE uid='%s'",$prefix."brewer",$loginUsername, $row_login['id']);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	// Register the session variable
	$_SESSION['loginUsername'] = $loginUsername;
	
	// Set the relocation variables
	if ($section == "update") $location = $base_url."update.php";
	else {
		if ($row_login['userLevel'] <= 1) $location = $base_url."index.php?section=admin";
		else $location = $base_url."index.php?section=list";
	}
	
}

/**
 * If the username/password combo is incorrect or not found, 
 * destroy the session and relocate to the login error page.
 */

else {
	$location = $base_url."index.php?section=login&msg=1";
	session_destroy();
	// Works with standard fail2ban apache-auth module to prevent Brute Force login attempts
	trigger_error('user authentication failure', E_USER_WARNING);
}

// Relocate
header(sprintf("Location: %s", $location, true));
exit;
?>