<?php
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
$entered_password = sterilize($_POST['loginPassword']);
$location = $base_url."index.php?section=login";

if (strlen($entered_password) > 72) { 
	session_destroy();
	header(sprintf("Location: %s", $base_url."index.php?msg=11"));
	exit;
}

$entered_password = md5($entered_password);
$loginUsername = strtolower($loginUsername);

/**
 * ONLY for 1.3.0.0 release; evaluate for deletion in future releases
 * Has to do with the hashing of passwords introduced in 1.3.0.0
 */

if ($section == "update") {

	$db_conn->where('user_name', $loginUsername);
	$row_login = $db_conn->getOne($prefix."users");
	$totalRows_login = $db_conn->count;

	$stored_hash = $row_login['password'];

	$check = 0;

	if ($totalRows_login > 0) $check = $hasher->CheckPassword($entered_password, $stored_hash);

	else $check = 0;

}

else {

	$db_conn->where('user_name', $loginUsername);
	$row_login = $db_conn->getOne($prefix."users");
	$totalRows_login = $db_conn->count;

	$stored_hash = $row_login['password'];
	$check = 0;

	if ($totalRows_login > 0) $check = $hasher->CheckPassword($entered_password, $stored_hash);

}

/**
 * If the username/password combo is valid, register a session, 
 * register a session cookie perform certain tasks and redirect
 */

if ($check == 1) {

	// Regenerate the session ID on successful authentication to prevent session fixation.
	session_regenerate_id(true);

	// Register the loginUsername but first update the db record to make sure the the user name is stored as all lowercase.
	$db_conn->where('id', $row_login['id']);
	$db_conn->update($prefix."users", array('user_name' => $loginUsername));

	// Convert email address in the user's accociated record in the "brewer" table
	$db_conn->where('uid', $row_login['id']);
	$db_conn->update($prefix."brewer", array('brewerEmail' => $loginUsername));
	
	// Register the session variable
	$_SESSION['loginUsername'] = $loginUsername;

	// Rotate CSRF token on successful login
	csrf_token_generate(true);
	
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
	$location = $base_url."index.php?msg=11";
	session_destroy();
	// Works with standard fail2ban apache-auth module to prevent Brute Force login attempts
	trigger_error('user authentication failure', E_USER_WARNING);
}

// Relocate
header(sprintf("Location: %s", $location, true));
exit();
?>