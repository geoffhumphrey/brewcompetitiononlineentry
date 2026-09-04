<?php
ob_start();

$section = "default";
if (isset($_GET['section'])) $section = sterilize($_GET['section']);

header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

// Credential fields must never be transformed - sterilize() HTML-encodes
// special characters for output, which would make the value compared here
// diverge from what was actually hashed/stored at registration.
$loginUsername = normalize_email_username($_POST['loginUsername']);
$entered_password = (string) $_POST['loginPassword'];
$location = $base_url."index.php?section=login";

if (strlen($entered_password) > 72) {
	session_destroy();
	header(sprintf("Location: %s", $base_url."index.php?msg=11"));
	exit;
}

$db_conn->where('user_name', $loginUsername);
$row_login = $db_conn->getOne($prefix."users");
$totalRows_login = $db_conn->count;

$stored_hash = $row_login['password'];
$check = 0;
$account_locked = FALSE;
$failed_count = 0;

if ($totalRows_login > 0) {

	$failed_count = (int) $row_login['userFailedLogins'];
	$failed_time = (int) $row_login['userFailedLoginTime'];

	if (($failed_count >= LOGIN_LOCKOUT_THRESHOLD) && ((time() - $failed_time) < LOGIN_LOCKOUT_WINDOW_SECONDS)) {
		$account_locked = TRUE;
	}

	else {

		// Lockout window (if any) has expired since the last failed attempt - start counting fresh.
		if ($failed_count >= LOGIN_LOCKOUT_THRESHOLD) $failed_count = 0;

		$check = password_verify_legacy($entered_password, $stored_hash);
		if (($check == 1) && (password_needs_legacy_upgrade($stored_hash))) upgrade_legacy_password_hash($db_conn, $prefix."users", "id", $row_login['id'], $entered_password);

	}

}

/**
 * Account is locked out from too many recent failed attempts - reject immediately without
 * touching the failed-attempt counter/timestamp, so continued attempts against a locked
 * account can't indefinitely extend the lockout window. Independent of, and in addition to,
 * the fail2ban hook below.
 */

if ($account_locked) {
	session_destroy();
	// Works with standard fail2ban apache-auth module to prevent Brute Force login attempts
	trigger_error('user authentication failure', E_USER_WARNING);
	header(sprintf("Location: %s", $base_url."index.php?msg=24"));
	exit();
}

/**
 * If the username/password combo is valid, register a session, 
 * register a session cookie perform certain tasks and redirect
 */

if ($check == 1) {

	// Regenerate the session ID on successful authentication to prevent session fixation.
	session_regenerate_id(true);

	// Register the loginUsername but first update the db record to make sure the the user name is stored as all lowercase.
	// Also reset the failed-login counter now that a valid login has succeeded.
	$db_conn->where('id', $row_login['id']);
	$db_conn->update($prefix."users", array('user_name' => $loginUsername, 'userFailedLogins' => 0, 'userFailedLoginTime' => NULL));

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

	if ($totalRows_login > 0) {
		$db_conn->where('id', $row_login['id']);
		$db_conn->update($prefix."users", array('userFailedLogins' => $failed_count + 1, 'userFailedLoginTime' => time()));
	}

	session_destroy();
	// Works with standard fail2ban apache-auth module to prevent Brute Force login attempts
	trigger_error('user authentication failure', E_USER_WARNING);
}

// Relocate
header(sprintf("Location: %s", $location, true));
exit();
?>