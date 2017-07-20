<?php 
/*
 * Module:      process_users_register.inc.php
 * Description: This module does all the heavy lifting for adding a user's info to the "users" and
 *              the "brewer" tables upon registration
 */
 
if (isset($_SERVER['HTTP_REFERER'])) {
	
	$captcha_success = FALSE;
	
	// Gather, convert, and/or sanitize info from the form
	if (isset($_POST['brewerJudgeID'])) {
		$brewerJudgeID = $_POST['brewerJudgeID'];
		$brewerJudgeID = strip_tags($brewerJudgeID);
		$brewerJudgeID = strtoupper($brewerJudgeID);
	}
	else $brewerJudgeID = "";
	
	if (isset($_POST['brewerJudgeMead'])) $brewerJudgeMead = $_POST['brewerJudgeMead'];
	else $brewerJudgeMead = "";
	
	if (isset($_POST['brewerJudgeRank'])) $brewerJudgeRank = $_POST['brewerJudgeRank'];
	else $brewerJudgeRank = "";
	
	if (isset($_POST['brewerAHA'])) {
		$brewerAHA = filter_var($_POST['brewerAHA'],FILTER_SANITIZE_NUMBER_INT);		
	}
	else $brewerAHA = "";
	
	if (isset($_POST['brewerClubs'])) {
		$brewerClubs = strip_tags($_POST['brewerClubs']);
	}
	else $brewerClubs = "";
	
	$brewerPhone1 = strip_tags($_POST['brewerPhone1']);
	
	if (isset($_POST['brewerPhone2'])) { 
		$brewerPhone2 = strip_tags($_POST['brewerPhone2']);
	}
	else $brewerPhone2 = "";
	
	if (isset($_POST['brewerJudgeWaiver'])) $brewerJudgeWaiver = $_POST['brewerJudgeWaiver'];
	else $brewerJudgeWaiver = "";
	
	if (isset($_POST['brewerDropOff'])) $brewerDropOff = $_POST['brewerDropOff'];
	else $brewerDropOff = "0";
	
	if (isset($_POST['brewerBreweryName'])) {
		$brewerBreweryName = $_POST['brewerBreweryName'];
		$brewerBreweryName = strip_tags($brewerBreweryName);
		$brewerBreweryName = strtolower($brewerBreweryName);
		$brewerBreweryName = ucwords($brewerBreweryName);
	}
	else $brewerBreweryName = "";
	
	if (isset($_POST['brewerBreweryTTB'])) {
		$brewerBreweryTTB = strip_tags($_POST['brewerBreweryTTB']);
	}
	else $brewerBreweryTTB = "";
	
	if (isset($_POST['brewerJudge'])) $brewerJudge = $_POST['brewerJudge'];
	else $brewerJudge = "";
	
	if (isset($_POST['brewerSteward'])) $brewerSteward = $_POST['brewerSteward'];
	else $brewerSteward = "";
	
	if (isset($_POST['brewerStaff'])) $brewerStaff = $_POST['brewerStaff'];
	else $brewerStaff = "";
	
	if (isset($_POST['brewerJudgeExp'])) $brewerJudgeExp = $_POST['brewerJudgeExp'];
	else $brewerJudgeExp = "";
	
	if (isset($_POST['brewerJudgeNotes'])) {
		$brewerJudgeNotes = strip_tags($_POST['brewerJudgeNotes']);
	}
	else $brewerJudgeNotes = "";
	
	$userQuestionAnswer = strip_tags($_POST['userQuestionAnswer']);
	
	$first_name = strip_tags($_POST['brewerFirstName']);
	$first_name = strtolower($first_name);
	$first_name = ucwords($first_name);
	
	$last_name = strip_tags($_POST['brewerLastName']);
	$last_name = strtolower($last_name);
	$last_name = ucwords($last_name);
	
	$address = strip_tags($_POST['brewerAddress']);
	$address = strtolower($address);
	$address = ucwords($address);
	
	$city = strip_tags($_POST['brewerCity']);
	$city = strtolower($city);
	$city = ucwords($city);
	
	$username = strtolower($_POST['user_name']);
	$username = filter_var($username,FILTER_SANITIZE_EMAIL);
	
	$username2 = strtolower($_POST['user_name2']);
	$username2 = filter_var($username2,FILTER_SANITIZE_EMAIL);
	
	if (($_SESSION['prefsProEdition'] == 1) && ($go == "entrant")) {
		$brewerJudge = "N";
		$brewerSteward = "N";
	}
	else {
		$brewerJudge = $_POST['brewerJudge'];
		$brewerSteward = $_POST['brewerSteward'];
	}
	
	if ($brewerJudge == "Y") {
		if (($_POST['brewerJudgeLocation'] != "") && (is_array($_POST['brewerJudgeLocation']))) $location_pref1 = implode(",",$_POST['brewerJudgeLocation']);
		elseif (($_POST['brewerJudgeLocation'] != "") && (!is_array($_POST['brewerJudgeLocation']))) $location_pref1 = $_POST['brewerJudgeLocation'];
	}
	else $location_pref1 = "";
	
	if ($brewerSteward == "Y") {
		if (($_POST['brewerStewardLocation'] != "") && (is_array($_POST['brewerStewardLocation']))) $location_pref2 = implode(",",$_POST['brewerStewardLocation']);
		elseif (($_POST['brewerStewardLocation'] != "") && (!is_array($_POST['brewerStewardLocation']))) $location_pref2 = $_POST['brewerStewardLocation'];
	}
	else $location_pref2 = "";
	
	// CAPCHA check
	if ($filter != "admin") {
		
		if ((isset($_POST['g-recaptcha-response'])) && (!empty($_POST['g-recaptcha-response']))) {
			
			$privatekey = "6LdUsBATAAAAAMPhk5yRSmY5BMXlBgcTjiLjiyPb";
			
			$verify_response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$privatekey.'&response='.$_POST['g-recaptcha-response']);
			$response_data = json_decode($verify_response);
			
			if (($_SERVER['SERVER_NAME'] = $response_data->hostname) && ($response_data->success)) $captcha_success = TRUE;
			
		}
		
	}
	
	if (($view == "default") && ($filter != "admin") && (!$captcha_success)) {
		setcookie("user_name", $username, 0, "/");
		setcookie("user_name2", $username2, 0, "/");
		setcookie("password", $_POST['password'], 0, "/");
		setcookie("userQuestion", $_POST['userQuestion'], 0, "/");
		setcookie("userQuestionAnswer", $userQuestionAnswer, 0, "/");
		setcookie("brewerFirstName", $first_name, 0, "/");
		setcookie("brewerLastName", $last_name, 0, "/");
		setcookie("brewerAddress", $address, 0, "/");
		setcookie("brewerCity", $city, 0, "/");
		setcookie("brewerState", strip_tags($_POST['brewerState']), 0, "/");
		setcookie("brewerZip", strip_tags($_POST['brewerZip']), 0, "/");
		setcookie("brewerCountry", $_POST['brewerCountry'], 0, "/");
		setcookie("brewerPhone1", $brewerPhone1, 0, "/");
		setcookie("brewerPhone2", $brewerPhone2, 0, "/");
		setcookie("brewerClubs", $brewerClubs, 0, "/");
		setcookie("brewerAHA", $brewerAHA, 0, "/");
		setcookie("brewerStaff", $_POST['brewerStaff'], 0, "/");
		setcookie("brewerSteward", $brewerSteward, 0, "/");
		setcookie("brewerJudge", $brewerJudge, 0, "/");
		setcookie("brewerDropOff", $brewerDropOff, 0, "/");
		setcookie("brewerJudgeLocation", $location_pref1, 0, "/");
		setcookie("brewerStewardLocation", $location_pref2, 0, "/");
		setcookie("brewerBreweryName", $brewerBreweryName, 0, "/");
		setcookie("brewerBreweryTTB", $brewerBreweryTTB, 0, "/");
		
		$location = $base_url."index.php?section=".$section."&go=".$go."&msg=4";
		header(sprintf("Location: %s", $location));
		
	}
	
	elseif (($view == "default") && ($username != $username2)) {
		
		setcookie("user_name", $username, 0, "/");
		setcookie("user_name2", $username2, 0, "/");
		setcookie("password", $_POST['password'], 0, "/");
		setcookie("userQuestion", $_POST['userQuestion'], 0, "/");
		setcookie("userQuestionAnswer", $userQuestionAnswer, 0, "/");
		setcookie("brewerFirstName", $first_name, 0, "/");
		setcookie("brewerLastName", $last_name, 0, "/");
		setcookie("brewerAddress", $address, 0, "/");
		setcookie("brewerCity", $city, 0, "/");
		setcookie("brewerState", strip_tags($_POST['brewerState']), 0, "/");
		setcookie("brewerZip", strip_tags($_POST['brewerZip']), 0, "/");
		setcookie("brewerCountry", $_POST['brewerCountry'], 0, "/");
		setcookie("brewerPhone1", $brewerPhone1, 0, "/");
		setcookie("brewerPhone2", $brewerPhone2, 0, "/");
		setcookie("brewerClubs", $brewerClubs, 0, "/");
		setcookie("brewerAHA", $brewerAHA, 0, "/");
		setcookie("brewerStaff", $_POST['brewerStaff'], 0, "/");
		setcookie("brewerSteward", $brewerSteward, 0, "/");
		setcookie("brewerJudge", $brewerJudge, 0, "/");
		setcookie("brewerDropOff", $brewerDropOff, 0, "/");
		setcookie("brewerJudgeLocation", $location_pref1, 0, "/");
		setcookie("brewerStewardLocation", $location_pref2, 0, "/");
		setcookie("brewerBreweryName", $brewerBreweryName, 0, "/");
		setcookie("brewerBreweryTTB", $brewerBreweryTTB, 0, "/");
		
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
			
			setcookie("userQuestion", $_POST['userQuestion'], 0, "/");
			setcookie("userQuestionAnswer", $userQuestionAnswer, 0, "/");
			setcookie("brewerFirstName", $first_name, 0, "/");
			setcookie("brewerLastName", $last_name, 0, "/");
			setcookie("brewerAddress", $address, 0, "/");
			setcookie("brewerCity", $city, 0, "/");
			setcookie("brewerState", strip_tags($_POST['brewerState']), 0, "/");
			setcookie("brewerZip", strip_tags($_POST['brewerZip']), 0, "/");
			setcookie("brewerCountry", $_POST['brewerCountry'], 0, "/");
			setcookie("brewerPhone1", $brewerPhone1, 0, "/");
			setcookie("brewerPhone2", $brewerPhone2, 0, "/");
			setcookie("brewerClubs", $brewerClubs, 0, "/");
			setcookie("brewerAHA", $brewerAHA, 0, "/");
			setcookie("brewerStaff", $_POST['brewerStaff'], 0, "/");
			setcookie("brewerSteward", $brewerSteward, 0, "/");
			setcookie("brewerJudge", $brewerJudge, 0, "/");
			setcookie("brewerDropOff", $brewerDropOff, 0, "/");
			setcookie("brewerJudgeLocation", $location_pref1, 0, "/");
			setcookie("brewerStewardLocation", $location_pref2, 0, "/");
			setcookie("brewerBreweryName", $brewerBreweryName, 0, "/");
			setcookie("brewerBreweryTTB", $brewerBreweryTTB, 0, "/");

			
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
						   GetSQLValueString($_POST['userQuestion'], "text"),
						   GetSQLValueString($userQuestionAnswer, "text"),
						   "NOW( )"					   
						   );
			
			mysqli_real_escape_string($connection,$insertSQL);
			$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));
	
			// Get the id from the "users" table to insert as the uid in the "brewer" table
			$query_user= "SELECT * FROM $users_db_table WHERE user_name = '$username'";
			$user = mysqli_query($connection,$query_user) or die (mysqli_error($connection));
			$row_user = mysqli_fetch_assoc($user);
		
			// Add the user's info to the "brewer" table
			// Numbers 999999994 through 999999999 are reserved for NHC applications.
			if (($brewerAHA < "999999994") || ($brewerAHA == "")) {
				
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
				  brewerAHA,
				  
				  brewerJudgeWaiver,
				  brewerDropOff,
				  brewerStaff,
				  brewerBreweryName,
				  brewerBreweryTTB
				  
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
							   GetSQLValueString(strip_tags($_POST['brewerState']), "text"),
							   GetSQLValueString(strip_tags($_POST['brewerZip']), "text"),
							   GetSQLValueString($_POST['brewerCountry'], "text"),
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
							   GetSQLValueString($brewerAHA, "int"),
							   GetSQLValueString($brewerJudgeWaiver, "text"),
							   GetSQLValueString($brewerDropOff, "text"),
							   GetSQLValueString($_POST['brewerStaff'], "text"),
							   GetSQLValueString($brewerBreweryName, "text"),
							   GetSQLValueString($brewerBreweryTTB, "text")
							   );
			
			} else {
				
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
				  brewerBreweryTTB
				  
				) VALUES (
				%s, %s, %s, %s, %s, 
				%s, %s, %s, %s, %s, 
				%s, %s, %s, %s, %s, 
				%s, %s, %s, %s, %s, 
				%s, %s, %s, %s
				)",
							   GetSQLValueString($row_user['id'], "int"),
							   GetSQLValueString($first_name, "text"),
							   GetSQLValueString($last_name, "text"),
							   GetSQLValueString($address, "text"),
							   GetSQLValueString($city, "text"),
							   GetSQLValueString(strip_tags($_POST['brewerState']), "text"),
							   GetSQLValueString(strip_tags($_POST['brewerZip']), "text"),
							   GetSQLValueString($_POST['brewerCountry'], "text"),
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
							   GetSQLValueString($_POST['brewerStaff'], "text"),
							   GetSQLValueString($brewerBreweryName, "text"),
							   GetSQLValueString($brewerBreweryTTB, "text")
							   );
			}
			
			mysqli_real_escape_string($connection,$insertSQL);
			$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));
	
			// Stop Gap for random staff assignments
			$updateSQL = sprintf("UPDATE %s  SET  staff_judge='0', staff_judge_bos='0', staff_steward='0', staff_organizer='0', staff_staff='0' WHERE uid=%s",$prefix."staff",$row_user['id']);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			
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
				if (isset($_POST['brewerBreweryName'])) $message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_organization,$_POST['brewerBreweryName']);
				if (isset($_POST['brewerBreweryTTB'])) 	$message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_ttb,$_POST['brewerBreweryTTB']);
				$message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_name,$first_name." ".$last_name);
				$message .= sprintf("<tr><td valign='top'><strong>%s (%s):</strong></td><td valign='top'>%s</td></tr>",$label_username,$label_email,$username);
				$message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_security_question,$_POST['userQuestion']);
				$message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_security_answer,$userQuestionAnswer);
				$message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s<br>%s, %s %s</td></tr>",$label_address,$address,$city,strip_tags($_POST['brewerState']),strip_tags($_POST['brewerZip']));
				$message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_phone_primary,$brewerPhone1);
				if (isset($brewerPhone2)) $message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_phone_secondary,$brewerPhone2);
				
				if ($show_entrant_fields) {
					
					if ($brewerJudge == "Y") $brewerJudge = $label_yes; else $brewerJudge = $label_no;
					if ($brewerSteward == "Y") $brewerSteward = $label_yes; else $brewerSteward = $label_no;
					if ($_POST['brewerStaff'] == "Y") $brewerStaff = $label_yes; else $brewerStaff = $label_no;
					
					if (isset($brewerClubs)) $message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_club,$brewerClubs);
					if (isset($brewerAHA)) $message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_aha_number,$brewerAHA);
					if (isset($_POST['brewerStaff']))$message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_staff,$brewerStaff);
					if (isset($_POST['brewerJudge'])) $message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_judge,$brewerJudge);
					if (isset($_POST['brewerSteward'])) $message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_steward,$brewerSteward);
					
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
			if (($brewerJudge == "Y") && ($go == "judge")) {
				
				$query_brewer= sprintf("SELECT id FROM $brewer_db_table WHERE uid = '%s'", $row_user['id']);
				$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
				$row_brewer = mysqli_fetch_assoc($brewer);
				header(sprintf("Location: %s", $base_url."index.php?section=brewer&action=edit&go=judge&psort=judge&id=".$row_brewer['id']));
				
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
				else $insertGoTo = $base_url."index.php?section=participants=edit&go=admin&filter=".$row_brewer['id']."&psort=judge&id=".$row_brewer['id'];
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