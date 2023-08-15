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
	$error_output = array();
	$_SESSION['error_output'] = "";
	
	$captcha_success = FALSE;

	require(PROCESS.'process_brewer_info.inc.php');

	$username = strtolower($_POST['user_name']);
	$username = filter_var($username,FILTER_SANITIZE_EMAIL);

	$userQuestionAnswer = $purifier->purify($_POST['userQuestionAnswer']);
	$userQuestionAnswer = filter_var($userQuestionAnswer,FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_HIGH|FILTER_FLAG_STRIP_LOW);
	
	$hasher_question = new PasswordHash(8, false);
	$hash_question = $hasher_question->HashPassword($userQuestionAnswer);

	$username2 = strtolower($_POST['user_name2']);
	$username2 = filter_var($username2,FILTER_SANITIZE_EMAIL);

	setcookie("userQuestion", $_POST['userQuestion'], 0, "/");
	setcookie("userQuestionAnswer", $userQuestionAnswer, 0, "/");
	setcookie("brewerFirstName", $first_name, 0, "/");
	setcookie("brewerLastName", $last_name, 0, "/");
	setcookie("brewerAddress", $address, 0, "/");
	setcookie("brewerCity", $city, 0, "/");
	setcookie("brewerState", sterilize($_POST['brewerState']), 0, "/");
	setcookie("brewerZip", sterilize($_POST['brewerZip']), 0, "/");
	setcookie("brewerCountry", $_POST['brewerCountry'], 0, "/");
	setcookie("brewerPhone1", $brewerPhone1, 0, "/");
	setcookie("brewerPhone2", $brewerPhone2, 0, "/");
	setcookie("brewerClubs", $brewerClubs, 0, "/");
	setcookie("brewerAHA", $brewerAHA, 0, "/");
	setcookie("brewerStaff", sterilize($_POST['brewerStaff']), 0, "/");
	setcookie("brewerSteward", $brewerSteward, 0, "/");
	setcookie("brewerJudge", $brewerJudge, 0, "/");
	setcookie("brewerDropOff", $brewerDropOff, 0, "/");
	setcookie("brewerJudgeLocation", $location_pref1, 0, "/");
	setcookie("brewerStewardLocation", $location_pref2, 0, "/");
	setcookie("brewerBreweryName", $brewerBreweryName, 0, "/");
	setcookie("brewerBreweryTTB", $brewerBreweryTTB, 0, "/");
	setcookie("brewerJudgeID", $brewerJudgeID, 0, "/");
	setcookie("brewerProAm", $brewerProAm, 0, "/");

	if ($filter != "admin") {

		if (($_SESSION['prefsCAPTCHA'] == 1) && (isset($_POST['g-recaptcha-response'])) && (!empty($_POST['g-recaptcha-response']))) {

			if ((!HOSTED) && (!empty($_SESSION['prefsGoogleAccount']))) {
				$recaptcha_key = explode("|", $_SESSION['prefsGoogleAccount']);
				$private_captcha_key = $recaptcha_key[1];
			}

			$verify_response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$private_captcha_key.'&response='.$_POST['g-recaptcha-response']);
			$response_data = json_decode($verify_response);

			if (($_SERVER['SERVER_NAME'] == $response_data->hostname) && ($response_data->success)) $captcha_success = TRUE;

		}

		elseif ($_SESSION['prefsCAPTCHA'] == 0) $captcha_success = TRUE;

	} // end if ($filter != "admin")

	if (($view == "default") && ($filter != "admin") && (!$captcha_success)) {

		$redirect = $base_url."index.php?section=".$section."&go=".$go."&msg=4";
		$redirect = prep_redirect_link($redirect);
		$redirect_go_to = sprintf("Location: %s", $redirect);
		header($redirect_go_to);
		exit();

	} // end if (($view == "default") && ($filter != "admin") && (!$captcha_success))

	elseif (($view == "default") && ($username != $username2)) {

		if ($filter == "admin") $redirect =  $base_url."index.php?section=admin&go=entrant&action=register&msg=27";
		else $redirect = $base_url."index.php?section=".$section."&go=".$go."&msg=5";
		$redirect = prep_redirect_link($redirect);
		$redirect_go_to = sprintf("Location: %s", $redirect);
		header($redirect_go_to);
		exit();

	} 

	else {

		// Check to see if email address is already in the system. If so, redirect.
		if (strstr($username,'@'))  {

			// Sanity check from AJAX widget
			$query_userCheck = sprintf("SELECT user_name FROM %s WHERE user_name = '%s'",$prefix."users",$username);
			$userCheck = mysqli_query($connection,$query_userCheck) or die (mysqli_error($connection));
			$row_userCheck = mysqli_fetch_assoc($userCheck);
			$totalRows_userCheck = mysqli_num_rows($userCheck);

			if ($totalRows_userCheck > 0) {

				if ($section == "admin") $msg = "10"; else $msg = "2";
				$redirect = $base_url."index.php?section=".$section."&go=".$go."&action=".$action."&msg=".$msg;
				$redirect = prep_redirect_link($redirect);
				$redirect_go_to = sprintf("Location: %s", $redirect);

				} else {

				// Add the user's creds to the "users" table			
				$hasher = new PasswordHash(8, false);
				$password = md5($_POST['password']);
				$hash = $hasher->HashPassword($password);
				$hasher_question = new PasswordHash(8, false);
				$hash_question = $hasher_question->HashPassword(sterilize($userQuestionAnswer));

				$userAdminObfuscate = 1;
				if ($_POST['userLevel'] == 0) $userAdminObfuscate = 0;

				$update_table = $prefix."users";
				$data = array(
					'user_name' => $username,
					'userLevel' => sterilize($_POST['userLevel']),
					'password' => $hash,
					'userQuestion' => sterilize($_POST['userQuestion']),
					'userQuestionAnswer' => $hash_question,
					'userCreated' =>  $db_conn->now(),
					'userAdminObfuscate' => $userAdminObfuscate
				);
				$result = $db_conn->insert ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

				// Get the id from the "users" table to insert as the uid in the "brewer" table
				$query_user= "SELECT * FROM $users_db_table WHERE user_name = '$username'";
				$user = mysqli_query($connection,$query_user) or die (mysqli_error($connection));
				$row_user = mysqli_fetch_assoc($user);

				$update_table = $prefix."brewer";
				$data = array(
					'uid' => $row_user['id'],
					'brewerFirstName' => blank_to_null($first_name),
					'brewerLastName' => blank_to_null($last_name),
					'brewerAddress' => blank_to_null($address),
					'brewerCity' => blank_to_null($city),
					'brewerState' => blank_to_null($state),
					'brewerZip' => blank_to_null($purifier->purify($_POST['brewerZip'])),
					'brewerCountry' => blank_to_null($purifier->purify($_POST['brewerCountry'])),
					'brewerPhone1' => blank_to_null($brewerPhone1),
					'brewerPhone2' => blank_to_null($brewerPhone2),
					'brewerClubs' => blank_to_null($brewerClubs),
					'brewerEmail' => blank_to_null($username),
					'brewerStaff' => blank_to_null($brewerStaff),
					'brewerSteward' => blank_to_null($brewerSteward),
					'brewerJudge' => blank_to_null($brewerJudge),
					'brewerJudgeID' => blank_to_null($brewerJudgeID),
					'brewerJudgeMead' => blank_to_null($brewerJudgeMead),
					'brewerJudgeCider' => blank_to_null($brewerJudgeCider),
					'brewerJudgeRank' => blank_to_null($brewerJudgeRank),
					'brewerJudgeLocation' => blank_to_null($location_pref1),
					'brewerStewardLocation' => blank_to_null($location_pref2),
					'brewerJudgeWaiver' => blank_to_null($brewerJudgeWaiver),
					'brewerAHA' => blank_to_null($brewerAHA),
					'brewerProAm' => blank_to_null($brewerProAm),
					'brewerDropOff' => blank_to_null($brewerDropOff),
					'brewerBreweryName' => blank_to_null($brewerBreweryName),
					'brewerBreweryTTB' => blank_to_null($brewerBreweryTTB)
				);

				print_r($data);

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
				echo $state."<br>";
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
				echo $brewerBreweryTTB."<br>";
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

				/**
				 * Check if UID is in staff table, if so (why is another matter, but hey),
				 * clear out assignments and associate with the newly added staff member.
				 */

				$query_stray = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE uid='%s'", $prefix."staff", $row_user['id']);
	 			$stray = mysqli_query($connection,$query_stray) or die (mysqli_error($connection));
	 			$row_stray = mysqli_fetch_assoc($stray);

	 			if ($row_stray['count'] == 0) {

	 				$update_table = $prefix."staff";
	 				$data = array(
	 					'uid' => $row_user['id'],
	 					'staff_judge' => $staff_judge,
	 					'staff_judge_bos' => 0,
	 					'staff_steward' => $staff_steward,
	 					'staff_organizer' => 0,
	 					'staff_staff' => $staff_staff
	 				);
	 				$result = $db_conn->insert ($update_table, $data);
	 				if (!$result) {
	 					$error_output[] = $db_conn->getLastError();
	 					$errors = TRUE;
	 				}

	 			} // end if ($row_stray['count'] == 0)

	 			elseif ($row_stray['count'] == 1) {

	 				$update_table = $prefix."staff";
	 				$data = array(
	 					'staff_judge' => $staff_judge,
	 					'staff_judge_bos' => 0,
	 					'staff_steward' => $staff_steward,
	 					'staff_organizer' => 0,
	 					'staff_staff' => $staff_staff
	 				);
	 				$db_conn->where ('uid', $row_user['id']);
	 				$result = $db_conn->update ($update_table, $data);
	 				if (!$result) {
	 					$error_output[] = $db_conn->getLastError();
	 					$errors = TRUE;
	 				}

	 			} // end elseif ($row_stray['count'] == 1)

				// If email registration info option is yes, email registrant their info...
				if ($_SESSION['prefsEmailRegConfirm'] == 1) {

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
					$to_email_formatted .= $to_name." <".$to_email.">";
					
					$subject = sprintf($_SESSION['contestName'].": %s",$register_text_037);
					$subject = html_entity_decode($subject);
					$subject = mb_convert_encoding($subject, "UTF-8");

					$from_email = (!isset($mail_default_from) || trim($mail_default_from) === '') ? "noreply@".$url : $mail_default_from;
					if ((strpos($url, 'brewcomp.com') !== false) || (strpos($url, 'brewcompetition.com') !== false)) $from_email = "noreply@brewcompetition.com";
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
					if (isset($_POST['brewerBreweryTTB'])) 	$message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_ttb,sterilize($_POST['brewerBreweryTTB']));
					$message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_name,$first_name." ".$last_name);
					$message .= sprintf("<tr><td valign='top'><strong>%s (%s):</strong></td><td valign='top'>%s</td></tr>",$label_username,$label_email,$username);
					$message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_security_question,sterilize($_POST['userQuestion']));
					$message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_security_answer,$userQuestionAnswer);
					$message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s<br>%s, %s %s</td></tr>",$label_address,$address,$city,sterilize($_POST['brewerState']),sterilize($_POST['brewerZip']));
					$message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_phone_primary,$brewerPhone1);
					if (!empty($brewerPhone2)) $message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_phone_secondary,$brewerPhone2);

					if ($show_entrant_fields) {

						if ($brewerJudge == "Y") $brewerJudge1 = $label_yes; else $brewerJudge1 = $label_no;
						if ($brewerSteward == "Y") $brewerSteward1 = $label_yes; else $brewerSteward1 = $label_no;
						if ($brewerStaff == "Y") $brewerStaff1 = $label_yes; else $brewerStaff1 = $label_no;
						if ($_POST['brewerProAm'] == 1) $brewerProAm1 = $label_yes; else $brewerProAm1 = $label_no;

						if (!empty($brewerClubs)) $message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_club,$brewerClubs);
						if (!empty($brewerAHA)) $message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_aha_number,$brewerAHA);
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

					if ($mail_use_smtp) {
						$mail = new PHPMailer(true);
						$mail->CharSet = 'UTF-8';
						$mail->Encoding = 'base64';
						$mail->addAddress($to_email, $to_name);
						$mail->setFrom($from_email, $from_name);
						$mail->Subject = $subject;
						$mail->Body = $message;
						sendPHPMailerMessage($mail);
					} else {
						mail($to_email_formatted, $subject, $message, $headers);
					}

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

					// Redirect to Judge Info section if willing to judge
					if ($brewerJudge == "Y") {

						$query_brewer= sprintf("SELECT id FROM $brewer_db_table WHERE uid = '%s'", $row_user['id']);
						$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
						$row_brewer = mysqli_fetch_assoc($brewer);

						$redirect = $base_url."index.php?section=brewer&action=edit&go=account&psort=judge&id=".$row_brewer['id'];
						$redirect = prep_redirect_link($redirect);
						$redirect_go_to = sprintf("Location: %s", $redirect);			
						header($redirect_go_to);
						exit();

					} // end if ($brewerJudge == "Y")

					else {

						$redirect = $base_url."index.php?section=list&msg=7";
						$redirect = prep_redirect_link($redirect);
						$redirect_go_to = sprintf("Location: %s", $redirect);			
						header($redirect_go_to);
						exit();

					}

				} // end if ($filter == "default")

				if ($filter == "admin") {

					// Redirect to Judge Info section if willing to judge
					if ($brewerJudge == "Y") {

						$query_brewer= sprintf("SELECT id FROM $brewer_db_table WHERE uid = '%s'", $row_user['id']);
						$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
						$row_brewer = mysqli_fetch_assoc($brewer);
						
						if ($view == "quick") {
							$insertGoTo = $base_url."index.php?section=admin&go=participants&msg=28";
							if ($errors) $insertGoTo = $base_url."index.php?section=admin&go=participants&msg=3";
						}
						
						else {
							$insertGoTo = $base_url."index.php?section=brewer&go=admin&action=edit&filter=".$row_brewer['id']."&psort=judge&id=".$row_brewer['id'];
							if ($errors) $insertGoTo .= "&msg=3";
						}

						if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

						$insertGoTo = prep_redirect_link($insertGoTo);
						$redirect_go_to = sprintf("Location: %s", $insertGoTo);
						header($redirect_go_to);
						exit();

					} // end if ($brewerJudge == "Y")

					else {

						if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

						$insertGoTo = $base_url."index.php?section=admin&go=participants&msg=1";
						if ($errors) $insertGoTo = $base_url."index.php?section=admin&go=participants&msg=3";
						$insertGoTo = prep_redirect_link($insertGoTo);
						$redirect_go_to = sprintf("Location: %s", $insertGoTo);
						header($redirect_go_to);
						exit();

					} // end else

				} // end if ($filter == "admin")

			} // end if ($totalRows_userCheck > 0)

		} // if (strstr($username,'@'))

	} // end else (CAPCHA check OK)

} else {

	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);
	header($redirect_go_to);
	exit();
}
?>
