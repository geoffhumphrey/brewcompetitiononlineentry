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
	-- Notification URL should be $base_url."pp_verify.php";

*/

require('paths.php');
require(INCLUDES.'url_variables.inc.php');
include(INCLUDES.'scrubber.inc.php');
require(LANG.'language.lang.php');

mysqli_select_db($connection,$database);

// Response from Paypal
// Read the post from PayPal system and add 'cmd'
$req = "cmd=_notify-validate";

foreach ($_POST as $key => $value) {
	$value = urlencode(stripslashes($value));
	$value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i','${1}%0D%0A${3}',$value);// IPN fix
	$req .= "&$key=$value";
}

// assign posted variables to local variables
$data['payment_status']		= $_POST['payment_status'];
$data['item_name']			= $_POST['item_name'];
$data['item_number'] 		= $_POST['item_number'];
$data['payment_status'] 	= $_POST['payment_status'];
$data['payment_amount'] 	= $_POST['mc_gross'];
$data['payment_currency']	= $_POST['mc_currency'];
$data['txn_id']				= $_POST['txn_id'];
$data['receiver_email'] 	= $_POST['receiver_email'];
$data['first_name'] 		= $_POST['first_name'];
$data['last_name'] 			= $_POST['last_name'];
$data['payer_email'] 		= $_POST['payer_email'];
$data['custom'] 			= $_POST['custom'];


$query_logo = sprintf("SELECT contestName,contestLogo FROM %s WHERE id='1'", $prefix."contest_info");
$logo = mysqli_query($connection,$query_logo) or die (mysqli_error($connection));
$row_logo = mysqli_fetch_assoc($logo);
$totalRows_logo = mysqli_num_rows($logo);

if (DEBUG) $to_email = "geoff@zkdigital.com";
else $to_email = $data['payer_email'];
$to_recipient = $data['first_name']." ".$data['last_name'];
$headers  = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
$headers .= "To: ".$to_recipient. " <".$to_email.">, " . "\r\n";
$headers .= "From: ".$row_logo['contestName']." Server <noreply@".$base_url.">\r\n";

$ch = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
// $ch = curl_init('https://www.paypal.com/cgi-bin/webscr');
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

if (!($res = curl_exec($ch))) {
  // error_log("Got " . curl_error($ch) . " when processing IPN data");
  curl_close($ch);
  exit;
}
curl_close($ch);

$query_prefs = sprintf("SELECT prefsPayPalAccount FROM %s WHERE id='1'", $prefix."preferences");
$prefs = mysqli_query($connection,$query_prefs) or die (mysqli_error($connection));
$row_prefs = mysqli_fetch_assoc($prefs);
$totalRows_prefs = mysqli_num_rows($prefs);

$query_logo = sprintf("SELECT contestLogo FROM %s WHERE id='1'", $prefix."contest_info");
$logo = mysqli_query($connection,$query_logo) or die (mysqli_error($connection));
$row_logo = mysqli_fetch_assoc($logo);
$totalRows_logo = mysqli_num_rows($logo);

if ((strcmp ($res, "VERIFIED") == 0) && ($data['receiver_email'] == $row_prefs['prefsPayPalAccount'])) {
  
  	// The IPN is verified, process it
	
	// Assign posted variables to local variables, apply urldecode to them all at this point as well, makes things simpler later
	$payment_status = $_POST['payment_status'];
	$a = explode("|",$_POST['custom']);
	
	// Add info to payments DB table
	$insertSQL = sprintf ("INSERT INTO %s (uid, first_name, last_name, item_name, txn_id, payment_gross, currency_code, payment_status, payment_entries, payment_time) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", $prefix."payments", $a[0], $data['first_name'], $data['last_name'], $data['item_name'], $data['txn_id'], $data['payment_amount'], $data['payment_currency'], $payment_status, $a[1], time());
	mysqli_real_escape_string($connection,$insertSQL);
	$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));
	
	// Begin to build the email message
	$message = "";
	$message .= "<html>";
	$message .= "<body>";
	if (isset($row_logo['contestLogo'])) $message .= "<p align='center'><img src='".$base_url."/user_images/".$row_logo['contestLogo']."' height='150'></p>";
	$message .= "<p>".$data['first_name'].",</p>";
	
	if ($payment_status == 'Completed') {
		// If payment completed, update database
		
		$b = explode("-",$a[1]);
		$queries = "";
		
		foreach ($b as $value) {
			
			$updateSQL = sprintf("UPDATE %s SET brewPaid='1', brewUpdated=NOW( ) WHERE id='%s'",$prefix."brewing",$value);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			
			// $queries .= $updateSQL."<br>";
		}
		
		
		
		$message .= sprintf("<p>%s</p>",$paypal_response_text_000);
		$message .= sprintf("<p><strong>%s</strong></p>",$paypal_response_text_001);
		$message .= "<table cellpadding=\"5\" cellspacing=\"0\">";
		$message .= sprintf("<tr valign='top'><td nowrap><strong>%s:</strong></td><td>".$to_recipient."<td></tr>",$label_name);
		$message .= sprintf("<tr valign='top'><td nowrap><strong>%s:</strong></td><td>".$data['payer_email']."<td></tr>",$label_email);
		$message .= sprintf("<tr valign='top'><td nowrap><strong>Status:</strong></td><td>".$data['payment_status']."<td></tr>",$label_status);
		$message .= sprintf("<tr valign='top'><td nowrap><strong>Amount:</strong></td><td>".$data['payment_amount']." ".$data['payment_currency']."<td></tr>",$label_amount);
		$message .= sprintf("<tr valign='top'><td nowrap><strong>Transaction ID:</strong></td><td>".$data['txn_id']."<td></tr>",$label_transaction_id);
		$message .= sprintf("<tr valign='top'><td nowrap><strong>%s %s:</strong></td><td>".str_replace("-",", ",$a[1])."<td></tr>",$label_entry_numbers,$label_paid);
		$message .= "</table>";
		
		
	}
	
	elseif ($payment_status == 'Denied' || $payment_status == 'Failed' || $payment_status == 'Refunded' || $payment_status == 'Reversed' || $payment_status == 'Voided') {
		//Do something
		
	}
	elseif ($payment_status == 'In-Progress' || $payment_status == 'Pending' || $payment_status == 'Processed') {
		//Do something
		
	}
	elseif ($payment_status == 'Canceled_Reversal')	{
		//Do something
		
	}
	
	// End the email message
	$message .= sprintf("<p>%s</p>",$paypal_response_text_002);
	$message .= sprintf("<p><small>%s</small></p>",$paypal_response_text_003);
	$message .= "</body>";
	$message .= "</html>";
	
	// Send the email message
	$subject = $data['item_name']." Details";
	mail($to_email, $subject, $message, $headers);
  
} else if (strcmp ($res, "INVALID") == 0) {
	
	// IPN invalid, log for manual investigation
	
	$message = "";
	$message .= "<html>";
	$message .= "<body>";
	$message .= "<p>Paypal response was &quot;invalid.&quot;<br>";
	$message .= "</p>";
	$message .= "</body>";
	$message .= "</html>";
	
	$subject = "PayPal Response: INVALID";
	mail($to_email, $subject, $message, $headers);
			
}
?>