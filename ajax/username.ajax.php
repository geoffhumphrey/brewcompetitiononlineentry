<?php 
ob_start();
require('../paths.php');
require(CONFIG.'bootstrap.php');

ini_set('display_errors', 0); // Change to 0 for prod; change to 1 for testing.
ini_set('display_startup_errors', 0); // Change to 0 for prod; change to 1 for testing.
error_reporting(0); // Change to error_reporting(0) for prod; change to E_ALL for testing.

if (isset($_SESSION['session_set_'.$prefix_session])) {

	if ((isset($_POST['user_name'])) && (strlen($_POST['user_name']) >= 3)) {

		$user_name = strtolower(sterilize($_POST['user_name']));
		$user_name = filter_var($user_name, FILTER_VALIDATE_EMAIL);

		$db_conn->where("user_name",$user_name);
		$user = $db_conn->getOne ($prefix."users");
		
		if ($db_conn->count > 0) echo sprintf("<span class=\"text-danger\"><span class=\"fa fa-exclamation-triangle\"></span> %s</span>",$alert_email_in_use);
		
		else {
			if (!empty($user_name)) echo sprintf("<span class=\"text-success\"><span class=\"fa fa-check-circle\"></span> %s</span>",$alert_email_not_in_use);
		}

	}

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