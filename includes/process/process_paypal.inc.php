<?php

// PayPal settings
// The payee address must come from the competition's own configured setting, never from the
// submitted form, otherwise a tampered request could redirect payment to an attacker's account.
$paypal_email = $_SESSION['prefsPaypalAccount'];
$return_url = $_POST['return'];
$cancel_url = $_POST['cancel_return'];

if ((TESTING) || (DEBUG)) $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
else $paypal_url = "https://www.paypal.com/cgi-bin/webscr";

$item_name = sterilize($_POST['item_name']);
$item_amount = sterilize($_POST['amount']);

// ---------------------------- Check if paypal request or response  ---------------------------

// Request payment

$query_string = "";

// Append PayPal account to querystring
$query_string .= "?business=".urlencode($paypal_email)."&";

// Loop for posted values and append to querystring
foreach($_POST as $key => $value){
	if (($key != "cancel_return") && ($key != "return") && ($key != "business")) {
		$value = urlencode(stripslashes($value));
		$query_string .= "$key=$value&";
	}
}

// Append paypal return addresses
$query_string .= "return=".urlencode(stripslashes($return_url));
$query_string .= "&cancel_return=".urlencode(stripslashes($cancel_url));

// Redirect to PayPal IPN
$redirect_go_to = sprintf('location:%s%s',$paypal_url,$query_string);

?>
