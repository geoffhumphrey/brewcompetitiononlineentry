<?php
/*
 * Module:      process_users_register.inc.php
 * Description: This module does all the heavy lifting for adding a user's info to the "users" and
 *              the "brewer" tables upon registration
 */

$captcha_success = FALSE;

if (isset($_SERVER['HTTP_REFERER'])) {

	require(PROCESS.'process_brewer_info.inc.php');

	$username = strtolower($_POST['user_name']);
	$username = filter_var($username,FILTER_SANITIZE_EMAIL);

	$userQuestionAnswer = $purifier->purify($_POST['userQuestionAnswer']);

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

	// CAPCHA check

		if (($_SESSION['prefsCAPTCHA'] == 1) && (isset($_POST['g-recaptcha-response'])) && (!empty($_POST['g-recaptcha-response']))) {

			if (!empty($_SESSION['prefsGoogleAccount'])) {
				$recaptcha_key = explode("|", $_SESSION['prefsGoogleAccount']);
				$private_captcha_key = $recaptcha_key[1];
			}

			$verify_response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$private_captcha_key.'&response='.$_POST['g-recaptcha-response']);
			$response_data = json_decode($verify_response);

			if (($_SERVER['SERVER_NAME'] == $response_data->hostname) && ($response_data->success)) $captcha_success = TRUE;

		}

		elseif ($_SESSION['prefsCAPTCHA'] == 0) $captcha_success = TRUE;

	}


	if (($view == "default") && ($filter != "admin") && (!$captcha_success)) {

		$location = $base_url."index.php?section=".$section."&go=".$go."&msg=4";
		header(sprintf("Location: %s", $location));

	}

	elseif (($view == "default") && ($username != $username2)) {

		if ($filter == "admin") $location =  $base_url."index.php?section=admin&go=entrant&action=register&msg=27";
		else $location = $base_url."index.php?section=".$section."&go=".$go."&msg=5";
		header(sprintf("Location: %s", $location));

	} else {

	// Check to see if email address is already in the system. If so, redirect.

	if (strstr($username,'@'))  {

		// Sanity check from AJAX widget
		$query_userCheck = "SELECT user_name FROM $users_db_table WHERE user_name = '$username'";
		$userCheck = mysqli_query($connection,$query_userCheck) or die (mysqli_error($connection));
		$row_userCheck = mysqli_fetch_assoc($userCheck);
		$totalRows_userCheck = mysqli_num_rows($userCheck);

		if ($totalRows_userCheck > 0) {

			if ($filter == "admin") header(sprintf("Location: %s", $base_url."index.php?section=admin&go=".$go."&action=register&msg=10"));
			else header(sprintf("Location: %s", $base_url."index.php?section=".$section."&go=".$go."&action=".$action."&msg=2"));

			} else {

			// Add the user's creds to the "users" table
			$password = md5($_POST['password']);
			require(CLASSES.'phpass/PasswordHash.php');
			$hasher = new PasswordHash(8, false);
			$hash = $hasher->HashPassword($password);

			if ($filter == "admin") {

			}

			$insertSQL = sprintf("INSERT INTO $users_db_table (user_name, userLevel, password, userQuestion, userQuestionAnswer, userCreated) VALUES (%s, %s, %s, %s, %s, %s)",
						   GetSQLValueString($username, "text"),
						   GetSQLValueString($_POST['userLevel'], "int"),
						   GetSQLValueString($hash, "text"),
						   GetSQLValueString(sterilize($_POST['userQuestion']), "text"),
						   GetSQLValueString($userQuestionAnswer, "text"),
						   "NOW( )"
						   );

			mysqli_real_escape_string($connection,$insertSQL);
			$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));
			echo $insertSQL."<br>";

			// Get the id from the "users" table to insert as the uid in the "brewer" table
			$query_user= "SELECT * FROM $users_db_table WHERE user_name = '$username'";
			$user = mysqli_query($connection,$query_user) or die (mysqli_error($connection));
			$row_user = mysqli_fetch_assoc($user);

			// Add the user's info to the "brewer" table

			$insertSQL = sprintf("INSERT INTO $brewer_db_table (
			  uid,
			  brewerFirstName,
			  brewerLastName,
			  brewerAddress,
			  brewerCity,

			  brewerState,
			  brewerZip,
			  brewerCountry,
			  brewerPhone1,
			  brewerPhone2,

			  brewerClubs,
			  brewerEmail,
			  brewerSteward,
			  brewerJudge,
			  brewerJudgeID,

			  brewerJudgeMead,
			  brewerJudgeRank,
			  brewerJudgeLocation,
			  brewerStewardLocation,
			  brewerJudgeWaiver,

			  brewerDropOff,
			  brewerStaff,
			  brewerBreweryName,
			  brewerBreweryTTB,
			  brewerProAm

			) VALUES (
			%s, %s, %s, %s, %s,
			%s, %s, %s, %s, %s,
			%s, %s, %s, %s, %s,
			%s, %s, %s, %s, %s,
			%s, %s, %s, %s, %s
			)",
						   GetSQLValueString($row_user['id'], "int"),
						   GetSQLValueString($first_name, "text"),
						   GetSQLValueString($last_name, "text"),
						   GetSQLValueString($address, "text"),
						   GetSQLValueString($city, "text"),
						   GetSQLValueString($state, "text"),
						   GetSQLValueString($purifier->purify($_POST['brewerZip']), "text"),
						   GetSQLValueString($purifier->purify($_POST['brewerCountry']), "text"),
						   GetSQLValueString($brewerPhone1, "text"),
						   GetSQLValueString($brewerPhone2, "text"),
						   GetSQLValueString($brewerClubs, "text"),
						   GetSQLValueString($username, "text"),
						   GetSQLValueString($brewerSteward, "text"),
						   GetSQLValueString($brewerJudge, "text"),
						   GetSQLValueString($brewerJudgeID, "text"),
						   GetSQLValueString($brewerJudgeMead, "text"),
						   GetSQLValueString($brewerJudgeRank, "text"),
						   GetSQLValueString($location_pref1, "text"),
						   GetSQLValueString($location_pref2, "text"),
						   GetSQLValueString($brewerJudgeWaiver, "text"),
						   GetSQLValueString($brewerDropOff, "text"),
						   GetSQLValueString($brewerStaff, "text"),
						   GetSQLValueString($brewerBreweryName, "text"),
						   GetSQLValueString($brewerBreweryTTB, "text"),
						   GetSQLValueString($brewerProAm, "text")
						   );

			mysqli_real_escape_string($connection,$insertSQL);
			$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));

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
			echo $insertSQL."<br>";
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

			// Check if UID is in staff table, if so (why is another matter, but hey), clear out assignments and associate with the newly added staff member
			$query_stray = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE uid='%s'", $prefix."staff", $row_user['id']);
 			$stray = mysqli_query($connection,$query_stray) or die (mysqli_error($connection));
 			$row_stray = mysqli_fetch_assoc($stray);

 			if ($row_stray['count'] == 0) {
 				$insertSQL = sprintf("INSERT INTO %s (uid, staff_judge, staff_judge_bos, staff_steward, staff_organizer, staff_staff) VALUES ('%s', '%s', '%s', '%s', '%s', '%s')", $prefix."staff", $row_user['id'], $staff_judge, "0", $staff_steward, "0", $staff_staff);
 				echo $insertSQL;
 				mysqli_real_escape_string($connection,$insertSQL);
				$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));
 			}

 			elseif ($row_stray['count'] == 1) {
 				$updateSQL = sprintf("UPDATE %s SET staff_judge='%s', staff_judge_bos='0', staff_steward='%s', staff_organizer='0', staff_staff='%s' WHERE uid= %s",$prefix."staff",$staff_judge,$staff_steward,$staff_staff,$row_user['id']);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
 			}

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
				$to_recipient = $first_name." ".$last_name;
				$to_email = $username;
				$subject = sprintf($_SESSION['contestName'].": %s",$register_text_037);

				$message = "<html>" . "\r\n";
				$message .= "<body>" . "\r\n";
				if (!empty($_SESSION['contestLogo'])) $message .= "<p align='center'><img src='".$base_url."user_images/".$_SESSION['contestLogo']."' height='150'></p>";
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
				$message .= "</body>" . "\r\n";
				$message .= "</html>";

				$headers  = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
				$headers .= sprintf("%s: ".$to_recipient. " <".$to_email.">, " . "\r\n",$label_to);
				if (strpos($url, 'brewcomp.com') !== false) $headers .= sprintf("%s: %s <noreply@brewcomp.com>\r\n",$label_from,$_SESSION['contestName']);
				elseif (strpos($url, 'brewcompetition.com') !== false) $headers .= sprintf("%s: %s <noreply@brewcompetition.com>\r\n",$label_from,$_SESSION['contestName']);
				else $headers .= sprintf("%s: %s  <noreply@".$url. ">\r\n",$label_from,$_SESSION['contestName']);

				$emails = $to_email;
				mail($emails, $subject, $message, $headers);

				/*
				echo $url;
				echo $headers."<br>";
				echo $subject."<br>";
				echo $message;
				exit;
				*/

			}

		if ($filter == "default") {
			// Log in the user and redirect

			session_name($prefix_session);
			session_start();
			$_SESSION['loginUsername'] = $username;

			// Redirect to Judge Info section if willing to judge
			if ($brewerJudge == "Y") {

				$query_brewer= sprintf("SELECT id FROM $brewer_db_table WHERE uid = '%s'", $row_user['id']);
				$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
				$row_brewer = mysqli_fetch_assoc($brewer);
				header(sprintf("Location: %s", $base_url."index.php?section=brewer&action=edit&go=account&psort=judge&id=".$row_brewer['id']));

			}

			else header(sprintf("Location: %s", $base_url."index.php?section=list&msg=7"));

		  } // end if ($filter == "default")

		if ($filter == "admin") {

			// Redirect to Judge Info section if willing to judge
			if ($brewerJudge == "Y") {

				$query_brewer= sprintf("SELECT id FROM $brewer_db_table WHERE uid = '%s'", $row_user['id']);
				$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
				$row_brewer = mysqli_fetch_assoc($brewer);
				if ($view == "quick") $insertGoTo = $base_url."index.php?section=admin&go=participants&msg=28";
				else $insertGoTo = $base_url."index.php?section=brewer&go=admin&action=edit&filter=".$row_brewer['id']."&psort=judge&id=".$row_brewer['id'];
				header(sprintf("Location: %s", stripslashes($insertGoTo)));

			}

			else {

				$pattern = array('\'', '"');
				$insertGoTo = $base_url."index.php?section=admin";
				$insertGoTo = str_replace($pattern, "", $insertGoTo);
				header(sprintf("Location: %s", stripslashes($insertGoTo)));

			}

		  } // end if ($filter == "admin")

		}

	  }

	} // End CAPCHA check
} else {
	header(sprintf("Location: %s", $base_url."index.php?msg=98"));
	exit;
}
?>