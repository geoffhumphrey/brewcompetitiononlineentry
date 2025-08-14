<?php

ob_start();
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', '0');
require('../paths.php');
require_once (CONFIG.'bootstrap.php');

use PHPMailer\PHPMailer\PHPMailer;
require (LIB.'email.lib.php');
require (CLASSES.'is_email/is_email.php');

// Instantiate HTMLPurifier
require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
$config_html_purifier = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config_html_purifier);

$error_output = "";

if (isset($_SESSION['session_set_'.$prefix_session])) {

	if ($action == "username") {
		
		if (isset($_POST['user_name'])) {
			
			$user_name_entered = filter_var($_POST['user_name'],FILTER_SANITIZE_EMAIL);
			$user_name_entered = $purifier->purify($user_name_entered);

			$query_user_name = sprintf("SELECT user_name,userQuestion FROM %s WHERE user_name='%s'",$prefix."users",$user_name_entered);
			$user_name = mysqli_query($connection,$query_user_name) or die (mysqli_error($connection));
			$row_user_name = mysqli_fetch_assoc($user_name);

			if ($go == "forgot") {
				
				if (mysqli_num_rows($user_name)) {

					if (is_email($user_name_entered)) {
						$_SESSION['user_name_entered'] = $user_name_entered;
						$display_security_question = sprintf("<em>%s</em>",$row_user_name['userQuestion']);
						$display_security_question .= "<div class=\"form-inline\" id=\"forgot-password-security-question\">";
						$display_security_question .= "<input type=\"hidden\" name=\"email\" value=\"".$user_name_entered."\">";
						$display_security_question .= "<input type=\"text\" class=\"form-control mb-2 me-sm-2\" id=\"security-question-answer\" placeholder=\"".$label_security_answer."\">";
						$display_security_question .= "<div class=\"help-block mt-1 mb-3 small\">";
						$display_security_question .= $pwd_email_reset_text_002;
						$display_security_question .= "</div>";
						$display_security_question .= "<div class=\"d-grid mx-auto\"><button class=\"btn btn-lg btn-primary mb-2\" id=\"forgot-password-security-question-button\" onclick=\"check_security_question_answer('".$ajax_url."','security-question-answer','security-question-response-status')\" disabled>".$label_submit."</button></div>";
						$display_security_question .= "</div>";
					}

					else $display_security_question = $login_text_030;
					
					echo sprintf("<p class=\"text-success\"><i class=\"fas fa-check-circle pe-2\"></i><strong>%s</strong>. %s</p><p class=\"alert alert-primary\">%s</p>",$label_verified,$login_text_011,$display_security_question);
				}

				else echo sprintf("<p class=\"text-danger\"><i class=\"fas fa-exclamation-triangle pe-2\"></i><strong>%s</strong></p><p>%s</p>",$login_text_028,$login_text_029); 
			}

			if ($go == "change") {
				
				$user_name_found = 0;
				if (mysqli_num_rows($user_name)) $user_name_found = 1;
				if (!is_email($user_name_entered)) $user_name_found = 2;

				$change_email_array = array(
					"user_name_entered" => $user_name_entered,
					"user_name_found" => $user_name_found
				);

				echo json_encode($change_email_array);
			}
			
			if ($go == "default") {
				if (mysqli_num_rows($user_name)) echo sprintf("<p class=\"text-danger\"><i class=\"fas fa-exclamation-triangle pe-2\"></i><strong>%s</strong></p>",$alert_email_in_use);
				else echo sprintf("<p class=\"text-success\"><i class=\"fas fa-check-circle pe-2\"></i><strong>%s</strong></p>",$alert_email_not_in_use);
			}
			
		}
		
	} // end if ($action == "username")

	if ($action == "email") {
		
		$email = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
		$email = $purifier->purify($email);
		
		if (is_email($email)) echo sprintf("<p class=\"text-success\"><i class=\"fas fa-check-circle pe-2\"></i><strong>%s</strong></p>",$alert_email_valid);
		else echo sprintf("<p class=\"text-danger\"><i class=\"fas fa-exclamation-triangle pe-2\"></i><strong>%s</strong></p>",$alert_email_not_valid);
		
	} // end if ($action == "email")

	if ($action == "check_answer") {

		$email = filter_var($_SESSION['user_name_entered'],FILTER_SANITIZE_EMAIL);
		$email = $purifier->purify($email);
		
		$answer = sterilize($_POST['user_question_answer']);
		$answer = $purifier->purify($answer);

		require (CLASSES.'phpass/PasswordHash.php');
		$hasher = new PasswordHash(8, false);

		$query_user_question = sprintf("SELECT a.id, a.user_name, a.userQuestion, a.userQuestionAnswer, b.brewerFirstName, b.brewerLastName FROM %s a, %s b WHERE a.user_name='%s' AND a.id = b.uid;",$prefix."users",$prefix."brewer",$email);
		$user_question = mysqli_query($connection,$query_user_question) or die (mysqli_error($connection));
		$row_user_question = mysqli_fetch_assoc($user_question);
		$totalRows_user_question = mysqli_num_rows($user_question);

		$stored_hash = $row_user_question['userQuestionAnswer'];
		$check = 0;
		if ($totalRows_user_question > 0) $check = $hasher->CheckPassword($answer, $stored_hash);

		// If matched up, send the password reset email and let the user know.
		if ($check == 1) {

			// Generate unique token by first getting a random string
			// and onvert the binary data into hexadecimal representation
			$token = openssl_random_pseudo_bytes(16);
			$token = bin2hex($token);
			
			// Update the users table with token and the time token was generated
			$update_table = $prefix."users";
			$data = array(
				'userToken' => $token,
				'userTokenTime' => time()
			);
			$db_conn->where ('id', $row_user_question['id']);
			$result = $db_conn->update ($update_table, $data);

			// But only if token data is successfully inserted			
			if ($result) {

				// Build vars
				$first_name = $row_user_question['brewerFirstName'];
				$last_name = $row_user_question['brewerLastName'];
				$reset_url = $base_url."index.php?section=login&go=password&action=reset-password&token=".$token;

				$url = str_replace("www.","",$_SERVER['SERVER_NAME']);
				
				if (HOSTED) {
					include (CONFIG.'config.mail.php');
					if ((!isset($mail_default_from)) || (trim($mail_default_from) === '')) $from_email = $_SESSION['prefsEmailFrom'];
					else $from_email = $mail_default_from;
				}

				else {
					if ($mail_use_smtp) $from_email = $_SESSION['prefsEmailFrom'];
					else $from_email = "noreply@".$url;
				}
				
				$from_email = mb_convert_encoding($from_email, "UTF-8");

				$contestName = $_SESSION['contestName'];
				$from_name = $_SESSION['contestName']." Server";
				$from_name = html_entity_decode($from_name);
				$from_name = mb_convert_encoding($from_name, "UTF-8");

				$to_email = filter_var($row_user_question['user_name'],FILTER_SANITIZE_EMAIL);
				$to_email = mb_convert_encoding($to_email, "UTF-8");

				$to_name = $first_name." ".$last_name;
				$to_name = html_entity_decode($to_name);
				$to_name = mb_convert_encoding($to_name, "UTF-8");
				$to_email_formatted = $to_name." <".$to_email.">";
				
				$subject = sprintf("%s: %s",$contestName,"Password Reset Request");
				$subject = html_entity_decode($subject);
				$subject = mb_convert_encoding($subject, "UTF-8");

				$message = "<html>" . "\r\n";
				$message .= "<body>" . "\r\n";

				if ((!empty($_SESSION['contestLogo'])) && (file_exists(USER_IMAGES.$_SESSION['contestLogo']))) $message .= "<p><img src='".$base_url."user_images/".$_SESSION['contestLogo']."' height='150'></p>";

				$message = "<html>" . "\r\n";
				$message .= "<body>" . "\r\n";
				$message .= sprintf("<p>%s,</p>",$first_name);
				$message .= sprintf("<p>%s %s %s</p>",$pwd_email_reset_text_003,$_SESSION['contestName'],$pwd_email_reset_text_004);
				$message .= sprintf("<p>%s</p>",$pwd_email_reset_text_005);
				$message .= sprintf("<p><a href=\"%s\">%s</a></p>", $reset_url, $reset_url);
				$message .= ucwords($_SESSION['contestName'])." Server";
				$message .= "<p><small>".$paypal_response_text_003."</small></p>";
				if ((DEBUG || TESTING) && ($mail_use_smtp)) $message .= "<p><small>Sent using phpMailer.</small></p>";
				$message .= "</body>" . "\r\n";
				$message .= "</html>";

				$headers  = "MIME-Version: 1.0"."\r\n";
				$headers .= "Content-type: text/html; charset=utf-8"."\r\n";
				$headers .= "From: ".$from_name." Server <".$from_email.">"."\r\n";
				$headers .= "Reply-To: ".$from_name." <".$from_email.">"."\r\n";

				/*
				echo "<pre>".htmlspecialchars($headers)."</pre>";
				echo $message;
				exit();
				*/

				if ($mail_use_smtp) {

					$mail = new PHPMailer(true);
					$mail->CharSet = 'UTF-8';
					$mail->Encoding = 'base64';
					$mail->addAddress($to_email, $to_name);
					$mail->setFrom($from_email, $from_name);
					$mail->Subject = $subject;
					$mail->Body = $message;
					sendPHPMailerMessage($mail);

				}

				// Fallback for password recovery only - attempt to email using mail() function
				else {
					
					$fallback_mail = mail($to_email_formatted, $subject, $message, $headers);
					if (!$fallback_mail) $error_output .= "Sending email via the PHP mail() function failed. Contact the competition officials to reset your password.";

				}

				echo sprintf("<p class=\"text-success\"><i class=\"fas fa-check-circle pe-2\"></i><strong>%s</strong>. %s %s</p>",$label_verified,$header_text_034,$header_text_116);

				unset($_SESSION['user_name_entered']);

			}

			else {
				$error_output .= $db_conn->getLastError();
			}

		}

		else {
			echo sprintf("<p class=\"text-danger\"><i class=\"fa fas fa-exclamation-triangle pe-2\"></i><strong>%s</strong> %s</p>",$header_text_038, $header_text_008);
		}

		if (!empty($error_output)) echo sprintf("<p class=\"text-danger\"><i class=\"fa fas fa-exclamation-triangle pe-2\"></i><strong>Errors:</strong> %s</p>",$error_output);

	}

} // end if (isset($_SESSION['session_set_'.$prefix_session]))

?>