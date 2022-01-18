<?php
ob_start();
ini_set('display_errors', 1); // Change to 0 for prod.
ini_set('display_startup_errors', 1); // Change to 0 for prod.
error_reporting(E_ALL); // Change to error_reporting(0) for prod.
require('../paths.php');
require(CONFIG.'bootstrap.php');
require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
$config_html_purifier = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config_html_purifier);

/**
 * Email types available:
 * 0 - Judges and Stewards - Free Form 
 * 1 - Judges and Stewards - Verify Availability
 * 2 - Judges and Stewards - Assigned as a Judge/Steward
 * 3 - Judges and Stewards - Table Assignments
 * 4 - Judges and Stewards - BJCP Points Submitted
 * 5 - Entrants - Free Form 
 * 6 - Entrants - Verify Entries and Drop-off Location; Delete Unused
 * 7 - Entrants - Unpaid Entries
 * 8 - Entrants - Entries Paid but Not Received
 * 9 - Entrants - Entries Received but Not Paid
 */  

$return_json = array();
$status = 0;
$error_type = 0;
$sql = "";
$email_type = "";
$email_from = "";
$email_from_name = "";
$email_subject = "";
$email_body = "";
$email_bcc_all = 0; // 1 = send a single email BCCing all recipients; 0 = send individual emails.
$recipients = array(); // Build array of recipients for display in the DOM after sucessful send
$total_recipients = 0;

if isset($_POST['email_type']) $email_type = $purifier->purify($_POST['email_type']);
if isset($_POST['email_from']) $email_from = $purifier->purify($_POST['email_from']);
if isset($_POST['email_from_name']) $email_from_name = $purifier->purify($_POST['email_from_name']);
if isset($_POST['email_subject']) $email_subject = $purifier->purify($_POST['email_subject']);
if isset($_POST['email_body']) $email_body = $purifier->purify($_POST['email_body']);
if isset($_POST['email_bcc_all']) $email_bcc_all = sterilize($_POST['email_bcc_all']);
if isset($_POST['total_recipients']) $email_bcc_all = sterilize($_POST['total_recipients']);

$send_email = FALSE;
if ((!empty($email_type)) && (!empty($email_from)) && (!empty($email_from_name)) && (!empty($email_subject)) && (!empty($email_body))) $send_email = TRUE;

// Get number of recipients
// If over 100, use BCC
if ($total_recipients > 100) $email_bcc_all = 0;


// Judge and Steward Emails
// Free Form
if ($email_type == 0) {

}

// Verify Availability
if ($email_type == 1) {
	$sql = sprintf("SELECT a.id, a.brewerFirstName, a.brewerLastName, a.brewerEmail, b.user_name, a.brewerJudge, a.brewerSteward, a.brewerJudgeRank, a.brewerJudgeID, a.brewerJudgeLocation, a.brewerStewardLocation, a.brewerJudgeMead, a.brewerJudgeCider, a.brewerJudgeNotes, a.brewerJudgeLikes, a.brewerJudgeDislikes FROM %s a, %s b WHERE a.uid = b.id AND (a.brewerJudge = 'Y' OR a.brewerSteward = 'Y') ORDER BY a.id ASC", $prefix."brewer", $prefix."user");
}

// Assigned as a Judge or Steward
if ($email_type == 2) {
	$sql = sprintf("SELECT a.id, a.brewerFirstName, a.brewerLastName, a.brewerEmail, a.brewerJudgeRank, a.brewerJudgeID, a.brewerJudgeLocation, a.brewerStewardLocation, a.brewerJudgeMead, a.brewerJudgeCider, a.brewerJudgeNotes, a.brewerJudgeLikes, a.brewerJudgeDislikes FROM %s a, %s b WHERE (b.staff_judge = 1 OR b.staff_steward = 1) AND a.uid = b.uid ORDER BY a.id ASC", $prefix."brewer", $prefix."staff");

}

// Table Assignments
if ($email_type == 3) {
	$sql = sprintf("SELECT a.id, a.brewerFirstName, a.brewerLastName, a.brewerEmail, a.brewerJudgeRank, a.brewerJudgeID, b.assignment, b.assignTable, b.assignFlight, b.assignRound, b.assignLocation, b.assignRoles FROM %s a, %s b WHERE a.uid = b.bid ORDER BY a.id ASC", $prefix."brewer", $prefix."staff");

}

if ($email_type == 4) {

}

// General Entrant Emails
if ($email_type == 5) {

}

// Send the email(s)
// If BCC All, send out in batches of 100


$return_json = array(
	"status" => "$status",
	"query" => "$sql",
	"email_type" => "$email_type",
	"email_subject" => "$email_body",
	"email_body" => "$email_body",
	"input" => "$input",
	"error_type" => "$error_type",
	"recipients" => "$recipients"
);

// Return the json
echo json_encode($return_json);
mysqli_close($connection);
?>