<?php

/**
 * File: account_checks.ajax.php
 * Description: Check if user name is already in DB
 *              Check if email address supplied is valid
 */

ob_start();
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', '1');

require('../paths.php');
//require_once (INCLUDES.'temporary_top_level_scripts.inc.php');
require_once (CONFIG.'bootstrap.php');
//require_once (INCLUDES.'temporary_scripts.inc.php');

// Instantiate HTMLPurifier
require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
$config_html_purifier = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config_html_purifier);

if ($action == "username") {
	
	if (isset($_POST['user_name'])) {
		
		$user_name_entered = filter_var($_POST['user_name'],FILTER_SANITIZE_EMAIL);
		$user_name_entered = $purifier->purify($user_name_entered);

		$query_user_name = sprintf("SELECT user_name,user_question FROM %s WHERE user_name='%s'",$prefix."users",$user_name_entered);
		$user_name = mysqli_query($connection,$query_user_name) or die (mysqli_error($connection));
		$row_user_name = mysqli_fetch_assoc($user_name);

		if ($go == "forgot") {
			
			if (mysqli_num_rows($user_name)) {

				if (is_email($user_name_entered)) {
					$display_security_question = sprintf("<em>%s</em>",$security_question[$row_user_name['user_question']]);
					$display_security_question .= "<div class=\"form-inline\" id=\"forgot-password-security-question\">";
					$display_security_question .= "<input type=\"text\" class=\"form-control mb-2 me-sm-2\" id=\"security-question-answer\" placeholder=\"".$label_security_answer."\">";
					$display_security_question .= "<button class=\"btn btn-primary mb-2\" id=\"forgot-password-security-question-button\" onclick=\"check_security_question_answer(base_url,'security-question-answer','security-question-response-status')\" disabled>".$label_submit."</button>";
					$display_security_question .= "</div>";
				}

				else $display_security_question = "Email provided is not valid. Please check it and try again.";
				
				echo sprintf("<p class=\"text-success\"><i class=\"fas fa-check-circle pe-2\"></i><strong>%s</strong>. %s</p><p class=\"alert alert-dark\">%s</p>",$label_verified,$login_text_011,$display_security_question);
			}

			else echo sprintf("<p class=\"text-danger\"><i class=\"fas fa-exclamation-triangle pe-2\"></i><strong>%s</strong></p><p>%s</p>",$login_text_020,$login_text_021); 
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

	$email = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
	$email = $purifier->purify($email);
	
	$answer = sterilize($_POST['user_question_answer']);
	$answer = $purifier->purify($answer);

	require (CLASSES.'phpass/PasswordHash.php');
	$hasher = new PasswordHash(8, false);

	$query_user_question = sprintf("SELECT userQuestionAnswer FROM %s WHERE user_name='%s'",$prefix."users",$email);
	$user_question = mysqli_query($connection,$query_user_question) or die (mysqli_error($connection));
	$totalRows_user_question = mysqli_num_rows($user_question);

	$stored_hash = $row_login['userQuestionAnswer'];
	$check = 0;
	if ($totalRows_user_question > 0) $check = $hasher->CheckPassword($answer, $stored_hash);

	if ($check == 1) {
		echo sprintf("<p class=\"text-success\"><i class=\"fas fa-check-circle pe-2\"></i><strong>%s</strong>. %s %s</p>",$label_verified,$header_text_034,$header_text_116);
	}

	else {
		echo sprintf("<p class=\"text-danger\"><i class=\"fa fas fa-exclamation-triangle pe-2\"></i><strong>%s</strong> %s",$header_text_038, $header_text_008);
	}

}

?>