<?php
/*

https://developer.paypal.com/docs/classic/ipn/integration-guide/IPNSetup/
http://www.evoluted.net/thinktank/web-development/paypal-php-integration

To implement:
1. Users will need to enable Auto Return for Website Payments in PayPal to $base_url."index.php?section=pay&msg=10";
	-- Must have a business account
	-- Login > Profile > My Selling Tools > Website Preferences

2. Users will need to enable Instant Payment Notification (IPN)
	-- Must have a business account
	-- Login > Profile > My Selling Tools > Instant payment notifications
	-- Notification URL should be $base_url."ppv.php";

*/

require ('paths.php');
require (CONFIG.'bootstrap.php');
require (INCLUDES.'url_variables.inc.php');
include (INCLUDES.'scrubber.inc.php');
require (LANG.'language.lang.php');

$query_prefs = sprintf("SELECT prefsPayPalAccount FROM %s WHERE id='1'", $prefix."preferences");
$prefs = mysqli_query($connection,$query_prefs) or die (mysqli_error($connection));
$row_prefs = mysqli_fetch_assoc($prefs);
$totalRows_prefs = mysqli_num_rows($prefs);

$query_logo = sprintf("SELECT contestName,contestLogo FROM %s WHERE id='1'", $prefix."contest_info");
$logo = mysqli_query($connection,$query_logo) or die (mysqli_error($connection));
$row_logo = mysqli_fetch_assoc($logo);
$totalRows_logo = mysqli_num_rows($logo);

$current_date = getTimeZoneDateTime($_SESSION['prefsTimeZone'], time(), $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "system", "date");
$current_date_time_display = getTimeZoneDateTime($_SESSION['prefsTimeZone'], time(), $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "long", "date-time");
$current_time = getTimeZoneDateTime($_SESSION['prefsTimeZone'], time(), $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "system", "time");

$custom_parts = explode("|",$_POST['custom']);

// Assign posted variables to local variables
$data['payment_status'] = filter_var($_POST['payment_status'],FILTER_SANITIZE_STRING);
$data['item_name'] = filter_var($_POST['item_name'],FILTER_SANITIZE_STRING);
$data['item_number'] = filter_var($_POST['item_number'],FILTER_SANITIZE_STRING);
$data['payment_status'] = filter_var($_POST['payment_status'],FILTER_SANITIZE_STRING);
$data['payment_amount'] = filter_var($_POST['mc_gross'],FILTER_SANITIZE_STRING);
$data['payment_currency'] = filter_var($_POST['mc_currency'],FILTER_SANITIZE_STRING);
$data['txn_id'] = filter_var($_POST['txn_id'],FILTER_SANITIZE_STRING);
$data['receiver_email'] = filter_var($_POST['receiver_email'],FILTER_SANITIZE_EMAIL);
$data['first_name'] = filter_var($_POST['first_name'],FILTER_SANITIZE_STRING);
$data['last_name'] = filter_var($_POST['last_name'],FILTER_SANITIZE_STRING);
$data['payer_email'] = filter_var($_POST['payer_email'],FILTER_SANITIZE_EMAIL);
$data['custom'] = filter_var($_POST['custom'],FILTER_SANITIZE_STRING);

$query_user_info = sprintf("SELECT brewerFirstName,brewerLastName,brewerEmail FROM %s WHERE uid='%s'", $prefix."brewer",$custom_parts[0]);
$user_info = mysqli_query($connection,$query_user_info) or die (mysqli_error($connection));
$row_user_info = mysqli_fetch_assoc($user_info);
$totalRows_user_info = mysqli_num_rows($user_info);

// Set this to true to use the sandbox endpoint during testing:
if ((DEBUG) || (TESTING)) {
	$enable_sandbox = TRUE;
	$send_confirmation_email = TRUE;
}

else {
	$enable_sandbox = FALSE;
	$send_confirmation_email = FALSE;
}

$save_log_file = TRUE;

$url = str_replace("www.","",$_SERVER['SERVER_NAME']);

$paypal_email_address = $row_prefs['prefsPayPalAccount'];

$confirm_to_email_address = "PayPal IPN Confirmation <".$row_prefs['prefsPayPalAccount'].">";
$confirm_from_email_address = $row_logo['contestName']." Server <noreply@".$url.">";

$to_email = "";
$to_recipient = "";
$cc_email = "";
$cc_recipient = "";

$test_text = "";
$data_text = "";

// Instantiate the PayPal IPN Class
require(CLASSES.'paypal/paypalIPN.php');

$ipn = new PaypalIPN();

if ($enable_sandbox) {
    $ipn->useSandbox();
}

$verified = $ipn->verifyIPN();

if ($send_confirmation_email) {
	foreach ($_POST as $key => $value) {
		$data_text .= "<tr><td width=\"150\">".$key."</td><td>".$value."</td></tr>";
	}
}

if ($_POST['test_ipn'] == 1) {
    $test_text = "Test: ";
}

// Check the receiver email to see if it matches
$receiver_email_found = FALSE;

if (strtolower($data['receiver_email']) == strtolower($paypal_email_address)) {
	$receiver_email_found = TRUE;
}

$paypal_ipn_status = "VERIFICATION FAILED";

if ($verified) {

	$paypal_ipn_status = "RECEIVER EMAIL MISMATCH";

	if ($receiver_email_found) {

		$paypal_ipn_status = "Completed Successfully";

		// Process IPN
        // A list of variables are available here:
        // https://developer.paypal.com/webapps/developer/docs/classic/ipn/integration-guide/IPNandPDTVariables/

		// If payment completed, update the brewing table rows for each paid entry

		$b = explode("-",$custom_parts[1]);
		$queries = "";

		foreach ($b as $value) {

			$updateSQL = sprintf("UPDATE %s SET brewPaid='1', brewUpdated=NOW( ) WHERE id='%s'",$prefix."brewing",$value);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		}

		$to_email = 	$row_user_info['brewerEmail'];
		$to_recipient = $row_user_info['brewerFirstName']." ".$row_user_info['brewerLastName'];

		$cc_email = 	$data['payer_email'];
		$cc_recipient = $data['first_name']." ".$data['last_name'];

		$headers  = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
		$headers .= "To: ".$to_recipient. " <".$to_email .">, " . "\r\n";
		$headers .= "CC: ".$cc_recipient. " <".$cc_email.">, " . "\r\n";
		$headers .= "From: ".$row_logo['contestName']." Server <noreply@".$url.">\r\n";

		$message_top = "";
		$message_body = "";
		$message_bottom = "";

		$message_top .= "<body>";
		$message_top .= "<html>";
		if (isset($row_logo['contestLogo'])) $message_body .= "<p align='center'><img src='".$base_url."/user_images/".$row_logo['contestLogo']."' height='150'></p>";
		$message_body .= "<p>".$row_user_info['brewerFirstName'].",</p>";
		$message_body .= sprintf("<p>%s</p>",$paypal_response_text_000);
		$message_body .= sprintf("<p><strong>%s</strong></p>",$paypal_response_text_001);
		$message_body .= "<table cellpadding=\"5\" cellspacing=\"0\">";
		$message_body .= sprintf("<tr valign='top'><td nowrap><strong>%s %s:</strong></td><td>".$to_recipient."<td></tr>",$label_payer,$label_name);
		$message_body .= sprintf("<tr valign='top'><td nowrap><strong>%s %s:</strong></td><td>".$data['payer_email']."<td></tr>",$label_payer,$label_email);
		$message_body .= sprintf("<tr valign='top'><td nowrap><strong>%s:</strong></td><td>".$data['payment_status']."<td></tr>",$label_status);
		$message_body .= sprintf("<tr valign='top'><td nowrap><strong>%s:</strong></td><td>".$data['payment_amount']." ".$data['payment_currency']."<td></tr>",$label_amount);
		$message_body .= sprintf("<tr valign='top'><td nowrap><strong>%s:</strong></td><td>".$data['txn_id']."<td></tr>",$label_transaction_id);
		$message_body .= sprintf("<tr valign='top'><td nowrap><strong>%s %s:</strong></td><td>".str_replace("-",", ",$custom_parts[1])."<td></tr>",$label_entry_numbers,$label_paid);
		$message_body .= "</table>";

		$message_body .= sprintf("<p>%s</p>",$paypal_response_text_002);
		$message_body .= sprintf("<p><small>%s</small></p>",$paypal_response_text_003);
		$message_bottom .= "</body>";
		$message_bottom .= "</html>";

		$message_all = $message_top.$message_body.$message_bottom;


		// Send the email message
		$subject = $test_text." ".$data['item_name']." - ".ucwords($paypal_response_text_009);
		mail($to_email, $subject, $message_all, $headers);

    }

}

elseif ($enable_sandbox) {
    if ($_POST['test_ipn'] != 1) $paypal_ipn_status = "RECEIVED FROM LIVE WHILE SANDBOXED";
}

elseif ($_POST['test_ipn'] == 1) {
    $paypal_ipn_status = "RECEIVED FROM SANDBOX WHILE LIVE";
}

if ($save_log_file) {

	$insertSQL = sprintf ("INSERT INTO %s (uid, first_name, last_name, item_name, txn_id, payment_gross, currency_code, payment_status, payment_entries, payment_time) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", $prefix."payments", $custom_parts[0], $data['first_name'], $data['last_name'], $data['item_name'], $data['txn_id'], $data['payment_amount'], $data['payment_currency'], $paypal_ipn_status, $custom_parts[1], time());
	mysqli_real_escape_string($connection,$insertSQL);
	$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));

}

if ($send_confirmation_email) {

	// Send confirmation email

	$headers_confirm  = "MIME-Version: 1.0" . "\r\n";
	$headers_confirm .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
	$headers_confirm .= "To: ".$confirm_to_email_address.", " . "\r\n";
	$headers_confirm .= "From: ".$confirm_from_email_address."\r\n";

	$message_top_confirm = "";
	$message_body_confirm = "";
	$message_bottom_confirm = "";

	$message_top_confirm .= "<body>";
	$message_top_confirm .= "<html>";

	if (!empty($to_email)) {
		$message_body_confirm .= "<p>To: ".$to_recipient. "<br>" . "To Email: ".$to_email. "<br>" . "CC: ".$cc_recipient. "<br>" . "CC Email: ".$cc_email."</p>";
		$message_body_confirm .= $message_body;
		$message_body_confirm .= "<br>-----------------------------------</p>";
	}

	$message_body_confirm .= "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\">";
	$message_body_confirm .= "<tr><th>Variable</th><th>Value</th></tr>";
	$message_body_confirm .= "<tr><td width=\"150\">paypal_ipn_status</td><td>".$paypal_ipn_status."</td></tr>";
	$message_body_confirm .= "<tr><td width=\"150\">paypal_ipn_date</td><td>".$current_date_time_display."</td></tr>";
	if (!empty($data_text))	$message_body_confirm .= $data_text;
	$message_body_confirm .= "</table>";

	$message_bottom_confirm .= "</body>";
	$message_bottom_confirm .= "</html>";

	$message_all_confirm = $message_top_confirm.$message_body_confirm.$message_bottom_confirm;

	$subject_confirm = "PayPal IPN: ".$paypal_ipn_status;
	mail($confirm_to_email_address, $subject_confirm, $message_all_confirm, $headers_confirm);

}

// Reply with an empty 200 response to indicate to paypal the IPN was received correctly
header("HTTP/1.1 200 OK");
?>