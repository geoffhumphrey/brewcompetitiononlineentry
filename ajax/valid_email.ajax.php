<?php 
ob_start();
require('../paths.php');
require(INCLUDES.'url_variables.inc.php');
if (session_status() === PHP_SESSION_NONE) session_start();

if ($section == "setup") {
	$alert_email_valid = "Email format is valid!";
	$alert_email_not_valid = "Email format is not valid.";
}

else {
	require(CONFIG.'bootstrap.php');
}

ini_set('display_errors', 0); // Change to 0 for prod; change to 1 for testing.
ini_set('display_startup_errors', 0); // Change to 0 for prod; change to 1 for testing.
error_reporting(0); // Change to error_reporting(0) for prod; change to E_ALL for testing.

if (isset($_SESSION['session_set_'.$prefix_session])) {

	require(CLASSES.'is_email/is_email.php');
	$email = sterilize($_GET['email']);
	$email = filter_var($email, FILTER_VALIDATE_EMAIL);
	if ((is_email($email)) && (!empty($email))) echo sprintf("<span class=\"text-success\"><i class=\"fa fa-check-circle\"></i> %s</span>", $alert_email_valid);
	else echo sprintf("<span class=\"text-danger\"><i class=\"fa fa-exclamation-triangle\"></i> %s</span>", $alert_email_not_valid);

} 

else $status = 9; // Session expired, not enabled, etc.

if ($action == "json") {

	$return_json = array(
		"status" => "$status"
	);

	echo json_encode($return_json);

}

mysqli_close($connection);

?>