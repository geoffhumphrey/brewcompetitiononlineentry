<?php

/**
 * Module:      heartbeat.ajax.php
 * Description: Refreshes $_SESSION['last_action'] (already done for any request by
 *              paths.php, included below) so genuine user activity that never otherwise
 *              generates a request - client-side-only interactions like filtering a
 *              DataTables list - can still keep the session alive. Returns the effective
 *              timeout and a fresh expiry timestamp so the client-side countdown
 *              (js_source/autologout.js) can resync instead of firing its warning
 *              modals against a session that was actually just extended.
 *              @see https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues/870
 */

ob_start();
require('../paths.php');
require(CONFIG.'bootstrap.php');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

$status = 0;

$session_active = FALSE;
if ((isset($_SESSION['session_set_'.$prefix_session])) && (isset($_SESSION['loginUsername']))) $session_active = TRUE;

// CSRF: require a same-origin Referer for this session-authenticated action.
$referrer_ok = (isset($_SERVER['HTTP_REFERER'])) && (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) === $_SERVER['SERVER_NAME']);

$session_expire_after_minutes = 0;
$session_end_seconds = 0;

if (($session_active) && ($referrer_ok)) {

	// paths.php (required above) already refreshed $_SESSION['last_action'] and applied the
	// prefsSessionTimeout override - but bootstrap.php's own include of includes/db/common.db.php
	// (which can itself refresh $_SESSION['prefsSessionTimeout'] after an admin just changed it)
	// runs *after* paths.php's check, so re-apply the override here with whatever's freshest.
	if ((isset($_SESSION['prefsSessionTimeout'])) && (!empty($_SESSION['prefsSessionTimeout']))) $session_expire_after = $_SESSION['prefsSessionTimeout'];
	$session_expire_after_minutes = $session_expire_after;
	$session_end_seconds = time() + ($session_expire_after_minutes * 60);
	$status = 1;

}

if (!$session_active) $status = 9; // Session expired

$return_json = array(
	"status" => "$status",
	"session_expire_after_minutes" => $session_expire_after_minutes,
	"session_end_seconds" => $session_end_seconds
);

echo json_encode($return_json);

mysqli_close($connection);

?>
