<?php
ob_start();
require('../paths.php');
require(CONFIG.'bootstrap.php');

ini_set('display_errors', 0); // Change to 0 for prod; change to 1 for testing.
ini_set('display_startup_errors', 0); // Change to 0 for prod; change to 1 for testing.
error_reporting(0); // Change to error_reporting(0) for prod; change to E_ALL for testing.

$return_json = array();
$status = 0;
$sql = "";
$display_date = "";
$dom_ct_participants = 0;
$dom_ct_participants_entries = 0;
$dom_ct_judges_avail = 0;
$dom_ct_judges_assigned = 0;
$dom_ct_stewards_avail = 0;
$dom_ct_stewards_assigned = 0;
$dom_ct_staff_avail = 0;
$dom_ct_staff_assigned = 0;
$dom_ct_entries = 0;
$dom_ct_entries_unconfirmed = 0; 
$dom_ct_entries_paid = 0;
$dom_ct_entries_paid_received = 0;
$dom_total_fees = 0;
$dom_total_fees_paid = 0;
$dom_ct_eval = 0;

if ((isset($_SESSION['session_set_'.$prefix_session])) && (isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] == 0)) {

	include(INCLUDES.'data_cleanup.inc.php');

	if (!empty($date_threshold)) {
		$display_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], $date_threshold, $_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'], "short", "date");
	}
	
} 

else {
	$status = 9; // Session expired, not enabled, etc.
}  // END if session is set

if (($go == "judge-assignments") || ($go == "steward-assignments")) {
	$return_json = array(
		"status" => "$status"
	);
}

else {
	$return_json = array(
		"status" => "$status",
		"query" => "$sql",
		"date" => "$display_date",
		"dom_ct_participants" => "$dom_ct_participants",
		"dom_ct_participants_entries" => "$dom_ct_participants_entries",
		"dom_ct_judges_avail" => "$dom_ct_judges_avail",
		"dom_ct_judges_assigned" => "$dom_ct_judges_assigned",
		"dom_ct_stewards_avail" => "$dom_ct_stewards_avail",
		"dom_ct_stewards_assigned" => "$dom_ct_stewards_assigned",
		"dom_ct_staff_avail" => "$dom_ct_staff_avail",
		"dom_ct_staff_assigned" => "$dom_ct_staff_assigned",
		"dom_ct_entries" => "$dom_ct_entries",
		"dom_ct_entries_unconfirmed" => "$dom_ct_entries_unconfirmed",
		"dom_ct_entries_paid" => "$dom_ct_entries_paid",
		"dom_ct_entries_paid_received" => "$dom_ct_entries_paid_received",
		"dom_total_fees" => "$dom_total_fees",
		"dom_total_fees_paid" => "$dom_total_fees_paid",
		"dom_ct_eval" => "$dom_ct_eval"
	);
}

echo json_encode($return_json);
mysqli_close($connection);

?>