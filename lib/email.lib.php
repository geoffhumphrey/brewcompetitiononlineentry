<?php

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require(ROOT.'vendor/autoload.php');

function sendPHPMailerMessage($mail) {

    require(CONFIG.'config.php');

    try {
        //Server settings
        $mail->SMTPDebug  = $smtp_debug_level;
        $mail->isSMTP();
        $mail->Host       = $smtp_host;
        $mail->SMTPAuth   = $smtp_auth; 
        $mail->Username   = $smtp_username; 
        $mail->Password   = $smtp_password; 
        $mail->SMTPSecure = $smtp_secure; 
        $mail->Port       = $smtp_port;

        $mail->isHTML(true);

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

?>