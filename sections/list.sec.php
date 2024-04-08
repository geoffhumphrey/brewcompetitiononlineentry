<?php
/**
 * Module:      list.sec.php
 * Description: This module displays user-related data including personal information,
 *              judging/stewarding information, and the participant's associated entries.
 *
 */

/*
// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && (!isset($base_url)))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}
*/
$user_remaining_entries = 0;
if (!empty($row_limits['prefsUserEntryLimit'])) $user_remaining_entries = ($row_limits['prefsUserEntryLimit'] - $totalRows_log);
else $user_remaining_entries = 1;

function pay_to_print($prefs_pay,$entry_paid) {
	if (($prefs_pay == "Y") && ($entry_paid == "1")) return TRUE;
	elseif (($prefs_pay == "Y") && ($entry_paid == "0")) return FALSE;
	elseif ($prefs_pay == "N") return TRUE;
}

if (NHC) {
	function certificate_type($score) {
		if (($score >= 25) && ($score <= 29.9)) $return = "Bronze Certificate";
		elseif (($score >= 30) && ($score <= 37.9))	$return = "Silver Certificate";
		elseif (($score >= 38) && ($score <= 50))	$return = "Gold Certificate";
		return $return;
	}
}

$judging_date = $judging_past;
$total_not_paid = total_not_paid_brewer($_SESSION['user_id']);

// Call the brewer's info information
include (SECTIONS.'brewer_info.sec.php');

// Call the brewer's entry information
if ($show_entries) include (SECTIONS.'brewer_entries.sec.php');
?>