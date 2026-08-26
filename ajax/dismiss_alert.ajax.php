<?php

ob_start();
require('../paths.php');
require(CONFIG.'bootstrap.php');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

$return_json = array();
$status = 0;

$session_active = FALSE;
if ((isset($_SESSION['session_set_'.$prefix_session])) && (isset($_SESSION['loginUsername']))) $session_active = TRUE;

// CSRF: require a same-origin Referer for this session-authenticated write action.
$referrer_ok = (isset($_SERVER['HTTP_REFERER'])) && (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) === $_SERVER['SERVER_NAME']);

// Allowlist of dismissible admin-dashboard alert keys. The incoming key is
// never trusted as a free-form $_SESSION array index.
$valid_alert_keys = array(
	'no-dropoff-dates',
	'no-judging-dates',
	'no-competition-contacts',
	'results-published',
	'update-summary',
	'update-errors',
	'data-cleanup-double-encoding',
	'maintenance-mode',
	'empty-judging-prefs',
	'setup-free-access',
	'missing-enabled-mods',
	'missing-disabled-mods'
);

$alert_key = isset($_GET['key']) ? $_GET['key'] : "";

if (($session_active) && ($_SESSION['userLevel'] <= 1) && ($referrer_ok) && (in_array($alert_key, $valid_alert_keys, true))) {

	if (!isset($_SESSION['dismissed_admin_alerts'])) $_SESSION['dismissed_admin_alerts'] = array();
	$_SESSION['dismissed_admin_alerts'][$alert_key] = TRUE;

	$status = 1;

}

if (!$session_active) $status = 9; // Session expired

$return_json = array("status" => "$status");

echo json_encode($return_json);

mysqli_close($connection);

?>
