<?php
// Import PHPMailer classes into the global namespace
// These must be at the top
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require (CLASSES.'phpmailer/src/Exception.php');
require (CLASSES.'phpmailer/src/PHPMailer.php');
require (CLASSES.'phpmailer/src/SMTP.php');

function sendPHPMailerMessage($mail) {

    require (CONFIG.'config.php');

    if (HOSTED) {
        include(CONFIG.'config.mail.php');
    }

    else {
        if (!function_exists('simpleDecrypt')) include (LIB.'common.lib.php');
        $secretKey = base64_encode(bin2hex($password));
        $nacl = base64_encode(bin2hex($server_root));
        $smtp_password = simpleDecrypt($_SESSION['prefsEmailPassword'], $secretKey, $nacl);
        $smtp_host = $_SESSION['prefsEmailHost'];
        $smtp_username = $_SESSION['prefsEmailUsername'];
        $smtp_secure = $_SESSION['prefsEmailEncrypt'];
        $smtp_port = $_SESSION['prefsEmailPort'];
    }

    try {
        $mail->SMTPDebug  = 0;
        $mail->isSMTP();
        $mail->Host       = $smtp_host;
        $mail->SMTPAuth   = TRUE; 
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