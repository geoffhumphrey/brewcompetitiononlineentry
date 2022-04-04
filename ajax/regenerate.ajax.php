<?php
ob_start();

require('../paths.php');
require(CONFIG.'bootstrap.php');

ini_set('display_errors', 0); // Change to 0 for prod; change to 1 for testing.
ini_set('display_startup_errors', 0); // Change to 0 for prod; change to 1 for testing.
error_reporting(0); // Change to error_reporting(0) for prod; change to E_ALL for testing.

$return_json = array();
$status = 0;

if ((isset($_SESSION['session_set_'.$prefix_session])) && (isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] == 0)) {

	require(LIB.'process.lib.php');
	$new_jn = generate_judging_numbers($prefix."brewing",$action);
	if ($new_jn == 0) $status = 1;

} else {
	$status = 9; // Session expired, not enabled, etc.
}

$return_json = array(
	"status" => "$status",
	// "action" => "$action"
);

echo json_encode($return_json);
mysqli_close($connection);

?>