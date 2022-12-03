<?php

/**
 * ******************************************************************************
 * Setup the email settings for the application
 * ******************************************************************************
 * In most cases the default mail() will be OK but if you would rather use the PHPMailer class 
 * set the mail_use_smtp variable below to true and fill out the rest of the SMTP fields as required.
 * @see https://github.com/PHPMailer/PHPMailer for examples
 * 
 * See your web host's SMTP mail configuration examples for your specific environment.
 * The following is an example of a PHPMailer configuration for use with BCOE&M and on a fictional server
 * using SMTP and SSL:
 * 
 * $mail_use_smtp = TRUE;
 * $mail_default_from = "comps@example.com";
 * $smtp_debug_level = 2;
 * $smtp_host = "mail1.example.com;mail2.example.com";
 * $smtp_auth = TRUE;
 * $smtp_username = "user@example.com";
 * $smtp_password = "secret";
 * $smtp_secure = "ssl";
 * $smtp_port = 465;
 */

// Do not change the following. Enable this module in /paths.php.
if (ENABLE_MAILER) $mail_use_smtp = TRUE;
else $mail_use_smtp = FALSE;

// Set this field if you want to override the from address of 'noreply@[website]' address that gets added by default
$mail_default_from = "";

// Enable verbose debug output
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$smtp_debug_level = 2;

// Specify main (and backup, if necessary - ';' separated) SMTP servers.
// Your host will have one more of these configured for you for an outgoing mail server.
$smtp_host = "";

// Enable / Disable SMTP authentication
// To use this option, you'll likely need to have an email address established on your server to send outgoing messages from.
$smtp_auth = TRUE;

// SMTP username
// Generally the email address you are sending from.
$smtp_username = "";

// SMTP password
$smtp_password = "";

// Enable TLS encryption, `tls` and `ssl` are accepted; leave blank if not used.
$smtp_secure = "";

// TCP port to connect to - likely to be 25, 465 or 587.
$smtp_port = 465;

?>