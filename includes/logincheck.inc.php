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

	// Pre-2.1.10.0 data won't have these columns yet - default to "no failed attempts
	// recorded" rather than warning, since a login here is likely on its way to
	// update.php, which is what adds them.
	$failed_count = (int) ($row_login['userFailedLogins'] ?? 0);
	$failed_time = (int) ($row_login['userFailedLoginTime'] ?? 0);

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
	try {
		$db_conn->where('id', $row_login['id']);
		$db_conn->update($prefix."users", array('user_name' => $loginUsername, 'userFailedLogins' => 0, 'userFailedLoginTime' => NULL));
	}
	catch (mysqli_sql_exception $e) {
		// Pre-2.1.10.0 data: those two columns don't exist yet. Don't let a login that's
		// likely on its way to update.php (which adds them) crash here - retry with just
		// the field that's always been present.
		$db_conn->where('id', $row_login['id']);
		$db_conn->update($prefix."users", array('user_name' => $loginUsername));
	}

	// Convert email address in the user's accociated record in the "brewer" table
	$db_conn->where('uid', $row_login['id']);
	$db_conn->update($prefix."brewer", array('brewerEmail' => $loginUsername));
	
	// Register the session variable
	$_SESSION['loginUsername'] = $loginUsername;

	/**
	 * Hosted-platform maintenance check (brewingcompetitions.com/brewcomp.com only). Previously
	 * ran unconditionally on every index.php/update.php page load, for any visitor, on any
	 * hosted install - now scoped to run at most once per session, only when a Top-Level Admin
	 * (userLevel 0) actually logs in. Only a Top-Level Admin can delete another Top-Level Admin,
	 * so a login by one is the one moment worth re-verifying the maintainer account wasn't
	 * removed/demoted.
	 */
	if ((HOSTED) && ($row_login['userLevel'] == 0) && (empty($_SESSION['hosted_admin_checked']))) {
		require_once (LIB.'hosted.lib.php');
		$_SESSION['hosted_admin_checked'] = TRUE;
		$_SESSION['hosted_admin_email'] = $hosted_admin_email;
	}

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
		try {
			$db_conn->where('id', $row_login['id']);
			$db_conn->update($prefix."users", array('userFailedLogins' => $failed_count + 1, 'userFailedLoginTime' => time()));
		}
		catch (mysqli_sql_exception $e) {
			// Pre-2.1.10.0 data: nothing to record yet, but don't let it crash the login flow.
		}
	}

	session_destroy();
	// Works with standard fail2ban apache-auth module to prevent Brute Force login attempts
	trigger_error('user authentication failure', E_USER_WARNING);
}

// Relocate
header(sprintf("Location: %s", $location, true));
exit();
?>