<?php

/**
 * ******************************************************************************
 * Setup the email settings for the application
 * ******************************************************************************
 * In most cases the default mail() will be OK but if you would rather use the 
 * PHPMailer class set the ENABLE_MAILER variable to TRUE in /paths.php and fill 
 * out the rest of the SMTP variables below as required.
 * @see: https://github.com/PHPMailer/PHPMailer for examples
 * 
 * See your web host's SMTP mail configuration examples for your specific environment.
 *
 * The following is an example of a PHPMailer configuration for use with BCOE&M 
 * and on a fictional server using SMTP and SSL:
 * 
 * $mail_default_from = "comps@example.com";
 * $smtp_debug_level = 0;
 * $smtp_host = "mail1.example.com;mail2.example.com";
 * $smtp_auth = TRUE;
 * $smtp_username = "comps@example.com";
 * $smtp_password = "superdupersecretpassword";
 * $smtp_secure = "ssl";
 * $smtp_port = 465;
 */

if ((session_status() == PHP_SESSION_NONE) || (!function_exists('sterilize'))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

// Do not change the following. 
// Enable this module in /paths.php by setting the ENABLE MAILER variable to TRUE.
if (ENABLE_MAILER) $mail_use_smtp = TRUE;
else $mail_use_smtp = FALSE;

// This is generally the same originating SMTP email address.
// If blank, a default address (e.g., 'noreply@[website.domain]') will be used. 
// However, without this variable set, the script is not guaranteed to work as expected.
$mail_default_from = "";

// Enable verbose debug output.
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$smtp_debug_level = 0;

// Specify main (and backup, if necessary - ';' separated) SMTP servers.
// Your host will have one more of these configured for you for an outgoing mail server.
$smtp_host = "";

// Enable / Disable SMTP authentication.
// To use this option, you'll need to have an email address established on your server to send outgoing messages from.
$smtp_auth = TRUE;

// SMTP username.
// Generally, the email address you are sending from.
$smtp_username = "";

// SMTP password
$smtp_password = "";

// Enable TLS encryption, `tls` and `ssl` are accepted; leave blank if not used.
$smtp_secure = "";

// TCP port to connect to - likely to be 25, 465, or 587.
$smtp_port = 465;

?>