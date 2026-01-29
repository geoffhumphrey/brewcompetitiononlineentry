<?php 

ob_start();
require('../paths.php');
require(CONFIG.'bootstrap.php');
ini_set('display_errors', 0); // Change to 0 for prod; change to 1 for testing.
ini_set('display_startup_errors', 0); // Change to 0 for prod; change to 1 for testing.
error_reporting(0); // Change to error_reporting(0) for prod; change to E_ALL for testing.

$status = 0;
$message = "";
$errors = "";

if (isset($_SESSION['session_set_'.$prefix_session])) {

	// $_POST['user_name'] = "buy@zkdigital.com";

	if ((isset($_POST['user_name'])) && (strlen($_POST['user_name']) >= 3)) {

		$user_name = strtolower(sterilize($_POST['user_name']));
		$user_name = filter_var($user_name, FILTER_VALIDATE_EMAIL);

		$db_conn->where("user_name",$user_name);
		$user = $db_conn->getOne ($prefix."users");

		$status = 1;
		
		if ($db_conn->count > 0) {
			$message = sprintf("<span class=\"text-danger\"><i class=\"fa fa-exclamation-triangle\"></i> %s</span>", $alert_email_in_use);
			echo $message;
		}
		
		else {
			if (!empty($user_name)) {
				$message = sprintf("<span class=\"text-success\"><i class=\"fa fa-check-circle\"></i> %s</span>", $alert_email_not_in_use);
				echo $message;
			}

			else {
				$status = 0;
				$message = "No username provided.";
				$errors = "POST variable empty.";
			}
		}

	}

	else {
		$status = 0;
		$message = "No username provided.";
		$errors = "No POST variable provided.";
	}

} 

else {
	$status = 9; // Session expired, not enabled, etc.
	$message = "No data.";
	$errors = "Session variable empty or incorrect.";
}

if ($action == "json") {

	$return_json = array(
		"status" => "$status",
		"message" => "$message",
		"errors" => "$errors"
	);

	echo json_encode($return_json);

}

mysqli_close($connection);

?>