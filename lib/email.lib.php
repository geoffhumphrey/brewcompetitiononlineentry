<?php
// Import PHPMailer classes into the global namespace
// These must be at the top
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require (CLASSES.'phpmailer/src/Exception.php');
require (CLASSES.'phpmailer/src/PHPMailer.php');
require (CLASSES.'phpmailer/src/SMTP.php');

/**
 * RFC 2047 MIME-encode a display name for use in a raw mail() header string
 * (e.g. "Name <email>"). PHPMailer's addAddress()/setFrom() already do this
 * internally for the SMTP path; the plain PHP mail() fallback does not, so
 * a non-ASCII name (accented characters, etc.) reaches the recipient's mail
 * server unencoded and can be rejected as a malformed header.
 *
 * Separately, RFC 5322 requires a display-name containing any "specials"
 * character (, ; : \ " ( ) < > @ [ ]) to be wrapped in a quoted-string - a
 * bare comma (e.g. a competition name like "Mile High Brew Off, Inc.") is
 * otherwise read as an address-list separator, producing a malformed header
 * strict receivers like Gmail reject outright. mb_encode_mimeheader() leaves
 * plain-ASCII input untouched, so that case is handled here explicitly; a
 * MIME encoded-word (the non-ASCII branch) is already a safe atom and never
 * needs this additional quoting.
 */
function mime_encode_header_name($name) {
    if (($name === null) || ($name === "")) return $name;
    $encoded = mb_encode_mimeheader($name, "UTF-8", "B", "\r\n");
    if (($encoded === $name) && (preg_match('/[,;:\\\\"()<>@\[\]]/', $name))) {
        $escaped = str_replace(array('\\', '"'), array('\\\\', '\\"'), $name);
        return '"'.$escaped.'"';
    }
    return $encoded;
}

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