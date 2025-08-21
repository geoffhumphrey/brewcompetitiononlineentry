<?php 
require_once ("../paths.php");
require_once (CONFIG.'bootstrap.php');
require_once (LIB.'process.lib.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require (CLASSES.'phpmailer/src/Exception.php');
require (CLASSES.'phpmailer/src/PHPMailer.php');
require (CLASSES.'phpmailer/src/SMTP.php');

if (!HOSTED) {
    $secretKey = base64_encode(bin2hex($password));
    $nacl = base64_encode(bin2hex($server_root));
    $smtp_password = simpleDecrypt($_SESSION['prefsEmailPassword'], $secretKey, $nacl);
}

$admin = FALSE;

if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] < 2)) {
  
    $admin = TRUE;

    function send_test_message($mail,$smtp_password) {

        require (CONFIG.'config.php');
        
        if (HOSTED) {
            include(CONFIG.'config.mail.php');
        }

        else {
            $smtp_host = $_SESSION['prefsEmailHost'];
            $smtp_username = $_SESSION['prefsEmailUsername'];
            $smtp_secure = $_SESSION['prefsEmailEncrypt'];
            $smtp_port = $_SESSION['prefsEmailPort'];
        }

        try { 

            $mail->SMTPDebug  = 1;
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

    $to_name = $_SESSION['brewerFirstName']." ".$_SESSION['brewerLastName'];
    $to_name = html_entity_decode($to_name);
    $to_name = mb_convert_encoding($to_name, "UTF-8");

    $to_email = $_SESSION['user_name'];
    $to_email = mb_convert_encoding($to_email, "UTF-8");

    $from_email = strtolower(filter_var($_SESSION['prefsEmailFrom'], FILTER_SANITIZE_EMAIL));
    $from_email = mb_convert_encoding($from_email, "UTF-8");

    $from_name = $_SESSION['contestName']." Server";
    $from_name = html_entity_decode($from_name);
    $from_name = mb_convert_encoding($from_name, "UTF-8");

    $subject = "Testing Email Settings";
    $subject = html_entity_decode($subject);
    $subject = mb_convert_encoding($subject, "UTF-8");

    $message = "";
    $message .= "<html>" . "\r\n";
    $message .= "<body>";
    
    if (HOSTED) {
        $message .= "<p>A request to send a test email to this address was made from the ".$from_name.".</p>";
        $message .= "<p>If you're reading this, emails are being generated successfully.</p>";
        $message .= "<p><small>Please do not reply to this email as it is automatically generated. The originating account is not active or monitored.</small></p>";
    }

    else {
        $message .= "<p>A request to send a test email to this address was made from the ".$from_name." using the following settings:</p>";
        $message .= "<ul>";
        $message .= "<li><strong>Originating Email Address:</strong> ".$_SESSION['prefsEmailFrom']."</li>";
        $message .= "<li><strong>Host:</strong> ".$_SESSION['prefsEmailHost']."</li>";
        $message .= "<li><strong>Username:</strong> ".$_SESSION['prefsEmailUsername']."</li>";
        $message .= "<li><strong>Encryption:</strong> ".$_SESSION['prefsEmailEncrypt']."</li>";
        $message .= "<li><strong>Port:</strong> ".$_SESSION['prefsEmailPort']."</li>";
        $message .= "</li>";
        $message .= "</ul>";
        $message .= "<p>If you're reading this, your settings are correct and emails are being generated successfully.</p>";
    }

    if ((DEBUG) || (TESTING)) $message .= "<p><small>Sent using phpMailer.</small></p>";
    $message .= "</body>" . "\r\n";
    $message .= "</html>";

}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <title><?php echo $_SESSION['contestName']; ?> - Brew Competition Online Entry &amp; Management</title>

    <!-- Load Bootstrap and jQuery -->
    <!-- Homepage URLs: http://www.getbootsrap.com and https://jquery.com -->
    <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $css_url; ?>print.min.css">

    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Load Font Awesome -->
    <!-- Homepage URL: https://fortawesome.github.io/Font-Awesome -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

  	</head>
	<body>
    <div class="container-fluid">
    <?php if ($admin) { ?>
    	<h2>Test Email</h2>
    	<p class="lead">Sending a test email to <?php echo $_SESSION['user_name']; ?>
    	<p>
    		<ul class="list-unstyled">
    			<li><strong>Originating Email Address:</strong> <?php echo $_SESSION['prefsEmailFrom']; ?></li>
    			<li><strong>Host:</strong> <?php echo $_SESSION['prefsEmailHost']; ?></li>
    			<li><strong>Username:</strong> <?php echo $_SESSION['prefsEmailUsername']; ?></li>
    			<li><strong>Encryption:</strong> <?php echo $_SESSION['prefsEmailEncrypt']; ?></li>
    			<li><strong>Port:</strong> <?php echo $_SESSION['prefsEmailPort']; ?></li>
            </ul>
        </p>
        <p>If there are any errors sending your email with the credentials you provided, they will be displayed below. Close this window and check your settings, especially your password.</p>
        <pre>

        <?php
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->addAddress($to_email, $to_name);
        $mail->setFrom($from_email, $from_name);
        $mail->Subject = $subject;
        $mail->Body = $message;
        send_test_message($mail,$smtp_password);
        ?>

        </pre>

        <?php } else { ?>
        <h2>Not Authorized</h2>
        <?php } ?>
    
    </div>
</body>
</html>