<?php
/*
 * Module:      process_users_register.inc.php
 * Description: This module does all the heavy lifting for adding a user's info to the "users" and
 *              the "brewer" tables upon registration
 */

use PHPMailer\PHPMailer\PHPMailer;
require(LIB.'email.lib.php');

if (isset($_SERVER['HTTP_REFERER'])) {

	require(CLASSES.'phpass/PasswordHash.php');

	$errors = FALSE;
	$error_output = [];
	$_SESSION['error_output'] = "";
	$captcha_success = FALSE;
	$no_register = FALSE;

	require(PROCESS.'process_brewer_info.inc.php');

	$username = filter_var(strtolower($_POST['user_name']),FILTER_SANITIZE_EMAIL);
	// $username2 = filter_var(strtolower($_POST['user_name2']),FILTER_SANITIZE_EMAIL);
	$userQuestionAnswer = $purifier->purify(sterilize($_POST['userQuestionAnswer']));
	$hasher_question = new PasswordHash(8, false);
	$hash_question = $hasher_question->HashPassword($userQuestionAnswer);

	if ($filter != "admin") {

		if ($_SESSION['prefsCAPTCHA'] == 1) {

			$captcha_response = FALSE;

			if ((isset($_POST['g-recaptcha-response'])) && (!empty($_POST['g-recaptcha-response']))) $captcha_response = TRUE;
			if ((isset($_POST['h-captcha-response'])) && (!empty($_POST['h-captcha-response']))) $captcha_response = TRUE;

			if ($captcha_response) {

				if (HOSTED) $captcha_type = 2;

				else {

					if (!empty($_SESSION['prefsGoogleAccount'])) {
						$captcha_key = explode("|", $_SESSION['prefsGoogleAccount']);
						$private_captcha_key = $captcha_key[1];
						if (isset($captcha_key[2])) $captcha_type = $captcha_key[2];
						else $captcha_type = 1; // default to reCAPTCHA
					}

				}

				// Verify reCAPTCHA response
				if ($captcha_type == 1) {

					$recaptcha_data = [
						'secret' => $private_captcha_key,
						'response' => $_POST['g-recaptcha-response']
					];

					$verify = curl_init();
					curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
					curl_setopt($verify, CURLOPT_POST, true);
					curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($recaptcha_data));
					curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);

					$response = curl_exec($verify);
					$response_data = json_decode($response);

					if ((!empty($response_data)) && ($_SERVER['SERVER_NAME'] == $response_data->hostname) && ($response_data->success)) $captcha_success = TRUE;

				}

				// Verify hCAPTCHA response
				if ($captcha_type == 2) {

					$hCAPTCHA_data = [
						'secret' => $private_captcha_key,
						'response' => $_POST['h-captcha-response']
					];
					
					$verify = curl_init();
					curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
					curl_setopt($verify, CURLOPT_POST, true);
					curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($hCAPTCHA_data));
					curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
					
					$response = curl_exec($verify);
					$response_data = json_decode($response);
					
					if ($response_data->success) $captcha_success = TRUE;
				
				}

			}

		}

		elseif ($_SESSION['prefsCAPTCHA'] == 0) $captcha_success = TRUE;

	} // end if ($filter != "admin")

	if (($filter != "admin") && (!$captcha_success)) {

		$no_register = TRUE;
		$redirect = $base_url."index.php?section=".$section."&go=".$go."&msg=4";
		$redirect = prep_redirect_link($redirect);
		$redirect_go_to = sprintf("Location: %s", $redirect);

	} // end if (($filter != "admin") && (!$captcha_success))

	/*

	elseif (($view == "default") && ($username != $username2)) {

		if ($filter == "admin") $redirect =  $base_url."index.php?section=admin&go=entrant&action=register&msg=27";
		else $redirect = $base_url."index.php?section=".$section."&go=".$go."&msg=5";
		$redirect = prep_redirect_link($redirect);
		$redirect_go_to = sprintf("Location: %s", $redirect);
		header($redirect_go_to);
		exit();

	}

	*/

	else {

		// Failsafe. Check to see if email address is already in the system. If so, redirect.		
		if (strstr($username,'@'))  {

			// Sanity check from AJAX widget
			$db_conn->where("user_name", $username);
			$row_userCheck = $db_conn->getOne($prefix."users", "user_name");
			$totalRows_userCheck = $db_conn->count;

			if ($totalRows_userCheck > 0) {

				if ($section == "admin") $msg = "10"; 
				else $msg = "2";
				$redirect = $base_url."index.php?section=".$section."&go=".$go."&action=".$action."&msg=".$msg;
				$redirect = prep_redirect_link($redirect);
				$redirect_go_to = sprintf("Location: %s", $redirect);

			} else {

				// Add the user's creds to the "users" table
				$hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
				$hasher_question = new PasswordHash(8, false);
				$hash_question = $hasher_question->HashPassword(sterilize($userQuestionAnswer));

				// Only a genuinely authenticated admin session may assign a userLevel other than
				// the standard public-registration participant level (2); otherwise the client-supplied
				// value is untrusted and would allow self-registration as an admin/staff account.
				if ((isset($_SESSION['loginUsername'])) && (isset($_SESSION['userLevel'])) && ($_SESSION['userLevel'] <= 1)) {
					$registerUserLevel = sterilize($_POST['userLevel']);
				}
				else $registerUserLevel = "2";

				$userAdminObfuscate = 1;
				if ($registerUserLevel == 0) $userAdminObfuscate = 0;

				$update_table = $prefix."users";
				$data = [
					'user_name' => $username,
					'userLevel' => $registerUserLevel,
					'password' => $hash,
					'userQuestion' => sterilize($_POST['userQuestion']),
					'userQuestionAnswer' => $hash_question,
					'userCreated' =>  date('Y-m-d H:i:s', time()),
					'userAdminObfuscate' => $userAdminObfuscate
				];

				$result = $db_conn->insert ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

				// Get the id from the "users" table to insert as the uid in the "brewer" table
				$db_conn->where("user_name", $username);
				$row_user = $db_conn->getOne($users_db_table);

				$update_table = $prefix."brewer";
				$data = [
					'uid' => $row_user['id'],
					'brewerFirstName' => blank_to_null($first_name),
					'brewerLastName' => blank_to_null($last_name),
					'brewerAddress' => blank_to_null($address),
					'brewerCity' => blank_to_null($city),
					'brewerState' => blank_to_null($state_province),
					'brewerZip' => blank_to_null(sterilize($_POST['brewerZip'])),
					'brewerCountry' => blank_to_null(sterilize($_POST['brewerCountry'])),
					'brewerPhone1' => blank_to_null($brewerPhone1),
					'brewerClubs' => blank_to_null($brewerClubs),
					'brewerEmail' => blank_to_null($username),
					'brewerStaff' => blank_to_null($brewerStaff),
					'brewerSteward' => blank_to_null($brewerSteward),
					'brewerJudge' => blank_to_null($brewerJudge),
					'brewerJudgeID' => blank_to_null($brewerJudgeID),
					'brewerJudgeMead' => blank_to_null($brewerJudgeMead),
					'brewerJudgeCider' => blank_to_null($brewerJudgeCider),
					'brewerJudgeRank' => blank_to_null($rank),
					'brewerJudgeLikes' => blank_to_null($likes),
					'brewerJudgeDislikes' => blank_to_null($dislikes),
					'brewerJudgeLocation' => blank_to_null($location_pref1),
					'brewerStewardLocation' => blank_to_null($location_pref2),
					'brewerJudgeExp' => blank_to_null($brewerJudgeExp),
					'brewerJudgeNotes' => blank_to_null($brewerJudgeNotes),
					'brewerJudgeWaiver' => blank_to_null($brewerJudgeWaiver),
					'brewerAHA' => blank_to_null($brewerAHA),
					'brewerMHP' => blank_to_null($brewerMHP),
					'brewerProAm' => blank_to_null($brewerProAm),
					'brewerDropOff' => blank_to_null($brewerDropOff),
					'brewerBreweryName' => blank_to_null($brewerBreweryName),
					'brewerBreweryInfo' => blank_to_null($brewerBreweryInfo),
					'brewerAssignment' => blank_to_null($brewerAssignment)
				];

				$result = $db_conn->insert ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

				/*
				echo $_POST['userQuestion']."<br>";
				echo $userQuestionAnswer."<br>";
				echo $first_name."<br>";
				echo $last_name."<br>";
				echo $address."<br>";
				echo $city."<br>";
				echo $state_province."<br>";
				echo $purifier->purify($_POST['brewerZip'])."<br>";
				echo $purifier->purify($_POST['brewerCountry'])."<br>";
				echo $brewerPhone1."<br>";
				echo $brewerPhone2."<br>";
				echo $brewerClubs."<br>";
				echo $brewerAHA."<br>";
				echo $brewerStaff."<br>";
				echo $brewerSteward."<br>";
				echo $brewerJudge."<br>";
				echo $brewerDropOff."<br>";
				echo $location_pref1."<br>";
				echo $location_pref2."<br>";
				echo $brewerBreweryName."<br>";
				echo $brewerBreweryInfo."<br>";
				echo $brewerJudgeID."<br>";
				echo $brewerProAm."<br>";
				echo $brewerJudgeWaiver."<br>";
				print_r($data);
				exit;
				*/

				$staff_judge = 0;
				$staff_steward = 0;
				$staff_staff = 0;

				if ($filter == "admin") {
					if ($go == "judge") $staff_judge = 1;
					if ($go == "steward") $staff_steward = 1;
					if ($_POST['brewerStaff'] == "Y") $staff_staff = 1;
				}

				else {
					if (($go == "judge") && ($brewerJudge == "Y")) $staff_judge = 1;
					if (($go == "steward") && ($brewerSteward == "Y")) $staff_steward = 1;
				}

				/**
				 * Check if UID is in staff table, if so (why is another matter, but hey),
				 * clear out assignments and associate with the newly added staff member.
				 */

	 			$db_conn->where("uid", $row_user['id']);
	 			$row_stray = $db_conn->getOne($prefix."staff", "COUNT(*) AS 'count'");

	 			if ($row_stray['count'] == 0) {

	 				$update_table = $prefix."staff";
	 				$data = [
	 					'uid' => $row_user['id'],
	 					'staff_judge' => $staff_judge,
	 					'staff_judge_bos' => 0,
	 					'staff_steward' => $staff_steward,
	 					'staff_organizer' => 0,
	 					'staff_staff' => $staff_staff
	 				];
	 				$result = $db_conn->insert ($update_table, $data);
	 				if (!$result) {
	 					$error_output[] = $db_conn->getLastError();
	 					$errors = TRUE;
	 				}

	 			} // end if ($row_stray['count'] == 0)

	 			elseif ($row_stray['count'] == 1) {

	 				$update_table = $prefix."staff";
	 				$data = [
	 					'staff_judge' => $staff_judge,
	 					'staff_judge_bos' => 0,
	 					'staff_steward' => $staff_steward,
	 					'staff_organizer' => 0,
	 					'staff_staff' => $staff_staff
	 				];
	 				$db_conn->where ('uid', $row_user['id']);
	 				$result = $db_conn->update ($update_table, $data);
	 				if (!$result) {
	 					$error_output[] = $db_conn->getLastError();
	 					$errors = TRUE;
	 				}

	 			} // end elseif ($row_stray['count'] == 1)

				// If email registration info option is yes, email registrant their info...
				if (($_SESSION['prefsEmailRegConfirm'] == 1) && ($mail_use_smtp)) {

					$show_entrant_fields = TRUE;

					if (isset($_POST['brewerBreweryName'])) {

						$label_name = $label_contact." ".$label_name;
						$label_username = $label_contact." ".$label_username;
						$label_address = $label_organization." ".$label_address;
						$label_phone_primary = $label_contact." ".$label_phone_primary;
						$label_phone_secondary = $label_contact." ".$label_phone_secondary;
						$show_entrant_fields = FALSE;

					}

					// Build vars
					$url = str_replace("www.","",$_SERVER['SERVER_NAME']);

					$to_name = $first_name." ".$last_name;
					$to_name = html_entity_decode($to_name);
					$to_name = mb_convert_encoding($to_name, "UTF-8");

					$to_email = mb_convert_encoding($username, "UTF-8");
					$to_email_formatted = $to_name." <".$to_email.">";

					$subject = sprintf($_SESSION['contestName'].": %s",$register_text_037);
					$subject = html_entity_decode($subject);
					$subject = mb_convert_encoding($subject, "UTF-8");

					$from_email = (!isset($mail_default_from) || trim($mail_default_from) === '') ? "noreply@".$url : $mail_default_from;
					if (str_contains($url, 'brewingcompetitions.com')) $from_email = $default_from."@brewingcompetitions.com";
					$from_email = mb_convert_encoding($from_email, "UTF-8");

					$from_name = html_entity_decode($_SESSION['contestName']);
					$from_name = mb_convert_encoding($from_name, "UTF-8");		

					$message = "<html>" . "\r\n";
					$message .= "<body>" . "\r\n";
					if ((!empty($_SESSION['contestLogo'])) && (file_exists(USER_IMAGES.$_SESSION['contestLogo']))) $message .= "<p><img src='".$base_url."user_images/".$_SESSION['contestLogo']."' height='150'></p>";
					$message .= "<p>".$first_name.",</p>";
					if ($filter == "admin") $message .= sprintf("<p>%s</p>",$register_text_038);
					else $message .= sprintf("<p>%s</p>",$register_text_039);
					$message .= "<table cellpadding='5' border='0'>";
					if (isset($_POST['brewerBreweryName'])) $message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_organization,sterilize($_POST['brewerBreweryName']));
					if (!empty($brewerBreweryTTB)) 	$message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_ttb,sterilize($brewerBreweryTTB));
					$message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_name,$first_name." ".$last_name);
					$message .= sprintf("<tr><td valign='top'><strong>%s (%s):</strong></td><td valign='top'>%s</td></tr>",$label_username,$label_email,$username);
					$message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_security_question,sterilize($_POST['userQuestion']));
					$message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_security_answer,$userQuestionAnswer);
					$message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s<br>%s, %s %s</td></tr>",$label_address,$address,$city,$state_province,sterilize($_POST['brewerZip']));
					$message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_phone_primary,$brewerPhone1);
					if (!empty($brewerPhone2)) $message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_phone_secondary,$brewerPhone2);

					if ($show_entrant_fields) {

						if ($brewerJudge == "Y") $brewerJudge1 = $label_yes; else $brewerJudge1 = $label_no;
						if ($brewerSteward == "Y") $brewerSteward1 = $label_yes; else $brewerSteward1 = $label_no;
						if ($brewerStaff == "Y") $brewerStaff1 = $label_yes; else $brewerStaff1 = $label_no;
						if ($_POST['brewerProAm'] == 1) $brewerProAm1 = $label_yes; 
						elseif ($_POST['brewerProAm'] == 2) $brewerProAm1 = $label_opt_out;  
						else $brewerProAm1 = $label_no;

						if (!empty($brewerClubs)) $message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_club,$brewerClubs);
						if (!empty($brewerAHA)) $message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_aha_number,$brewerAHA);
						if (!empty($brewerMHP)) $message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_mhp_number,$brewerMHP);
						$message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_staff,$brewerStaff1);
						$message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_judge,$brewerJudge1);
						$message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_steward,$brewerSteward1);
						$message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_pro_am,$brewerProAm1);
					}

					$message .= "</table>";
					$message .= sprintf("<p>%s <a href='".$base_url."index.php?section=login'>%s</a> %s</p>",$register_text_040,$register_text_041,$register_text_042);
					$message .= sprintf("<p><small>%s</small></p>",$register_text_043);
					if ((DEBUG || TESTING) && ($mail_use_smtp)) $message .= "<p><small>Sent using phpMailer.</small></p>";
					$message .= "</body>" . "\r\n";
					$message .= "</html>";

					$headers  = "MIME-Version: 1.0"."\r\n";
					$headers .= "Content-type: text/html; charset=utf-8"."\r\n";
					$headers .= "From: ".$from_name." <".$from_email.">"."\r\n";
					$headers .= "Reply-To: ".$from_name." <".$from_email.">"."\r\n";

					$mail = new PHPMailer(true);
					$mail->CharSet = 'UTF-8';
					$mail->Encoding = 'base64';
					$mail->addAddress($to_email, $to_name);
					$mail->setFrom($from_email, $from_name);
					$mail->Subject = $subject;
					$mail->Body = $message;
					sendPHPMailerMessage($mail);

					/*
					echo $url;
					echo $headers."<br>";
					echo $subject."<br>";
					echo $message;
					exit;
					*/

				} // end if ($_SESSION['prefsEmailRegConfirm'] == 1)

				if ($filter == "default") {

					unset($_SESSION['user_info'.$prefix_session]);
					$_SESSION['loginUsername'] = $username;
					csrf_token_generate(true);
					$redirect = $base_url."index.php?section=list&msg=7";
					$redirect = prep_redirect_link($redirect);
					$redirect_go_to = sprintf("Location: %s", $redirect);			

				} // end if ($filter == "default")

				if ($filter == "admin") {

					// Redirect to Judge Info section if willing to judge
					if ($brewerJudge == "Y") {

						$db_conn->where("uid", $row_user['id']);
						$row_brewer = $db_conn->getOne($brewer_db_table, "id");

						if ($view == "quick") {
							$insertGoTo = $base_url."index.php?section=admin&go=participants&msg=28";
							if ($errors) $insertGoTo = $base_url."index.php?section=admin&go=participants&msg=3";
						}

						else {
							$insertGoTo = $base_url."index.php?section=brewer&go=admin&action=edit&filter=".$row_brewer['id']."&psort=judge&id=".$row_brewer['id'];
							if ($errors) $insertGoTo .= "&msg=3";
						}

						if ($error_output !== []) $_SESSION['error_output'] = $error_output;

						$insertGoTo = prep_redirect_link($insertGoTo);
						$redirect_go_to = sprintf("Location: %s", $insertGoTo);

					} // end if ($brewerJudge == "Y")

					else {

						if ($error_output !== []) $_SESSION['error_output'] = $error_output;

						$insertGoTo = $base_url."index.php?section=admin&go=participants&msg=1";
						if ($errors) $insertGoTo = $base_url."index.php?section=admin&go=participants&msg=3";
						$insertGoTo = prep_redirect_link($insertGoTo);
						$redirect_go_to = sprintf("Location: %s", $insertGoTo);

					} // end else

				} // end if ($filter == "admin")

			} // end if ($totalRows_userCheck > 0)

		} // if (strstr($username,'@'))

		else {

			$no_register = TRUE;
			if ($filter == "admin") $redirect =  $base_url."index.php?section=admin&go=entrant&action=register&msg=27";
			else $redirect = $base_url."index.php?section=".$section."&go=".$go."&msg=5";
			$redirect = prep_redirect_link($redirect);
			$redirect_go_to = sprintf("Location: %s", $redirect);
		
		}

	} // end else (CAPCHA check OK)

	if ($no_register) {

		setcookie("userQuestion", sterilize($_POST['userQuestion']), ['expires' => 0, 'path' => "/"]);
		setcookie("userQuestionAnswer", $userQuestionAnswer, ['expires' => 0, 'path' => "/"]);
		setcookie("brewerFirstName", $first_name, ['expires' => 0, 'path' => "/"]);
		setcookie("brewerLastName", $last_name, ['expires' => 0, 'path' => "/"]);
		setcookie("brewerAddress", $address, ['expires' => 0, 'path' => "/"]);
		setcookie("brewerCity", $city, ['expires' => 0, 'path' => "/"]);
		setcookie("brewerState", sterilize($state_province), ['expires' => 0, 'path' => "/"]);
		setcookie("brewerZip", sterilize($_POST['brewerZip']), ['expires' => 0, 'path' => "/"]);
		setcookie("brewerCountry", sterilize($_POST['brewerCountry']), ['expires' => 0, 'path' => "/"]);
		setcookie("brewerPhone1", $brewerPhone1, ['expires' => 0, 'path' => "/"]);
		setcookie("brewerPhone2", $brewerPhone2, ['expires' => 0, 'path' => "/"]);
		setcookie("brewerClubs", $brewerClubs, ['expires' => 0, 'path' => "/"]);
		setcookie("brewerAHA", $brewerAHA, ['expires' => 0, 'path' => "/"]);
		setcookie("brewerMHP", $brewerMHP, ['expires' => 0, 'path' => "/"]);
		setcookie("brewerStaff", sterilize($_POST['brewerStaff']), ['expires' => 0, 'path' => "/"]);
		setcookie("brewerSteward", $brewerSteward, ['expires' => 0, 'path' => "/"]);
		setcookie("brewerJudge", $brewerJudge, ['expires' => 0, 'path' => "/"]);
		setcookie("brewerDropOff", $brewerDropOff, ['expires' => 0, 'path' => "/"]);
		setcookie("brewerJudgeLocation", $location_pref1, ['expires' => 0, 'path' => "/"]);
		setcookie("brewerStewardLocation", $location_pref2, ['expires' => 0, 'path' => "/"]);
		setcookie("brewerBreweryName", $brewerBreweryName, ['expires' => 0, 'path' => "/"]);
		setcookie("brewerBreweryTTB", $brewerBreweryTTB, ['expires' => 0, 'path' => "/"]); // $brewerBreweryTTB var is incoprorated into $brewerBreweryInfo array.
		setcookie("brewerJudgeID", $brewerJudgeID, ['expires' => 0, 'path' => "/"]);
		setcookie("brewerProAm", $brewerProAm, ['expires' => 0, 'path' => "/"]);

	}

	header($redirect_go_to);
	exit();


}
$redirect = $base_url."index.php?msg=98";
$redirect = prep_redirect_link($redirect);
$redirect_go_to = sprintf("Location: %s", $redirect);
header($redirect_go_to);
exit();
?>
