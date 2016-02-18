<?php 
/*
 * Module:      process_users_register.inc.php
 * Description: This module does all the heavy lifting for adding a user's info to the "users" and
 *              the "brewer" tables upon registration
 */
 
// Custom Code for AHA NHC

if (NHC) {
	
	include (DB.'common.db.php');
	function open_or_closed($now,$date1,$date2) {
		if ($now < $date1) $output = "0";
		elseif (($now >= $date1) && ($now <= $date2)) $output = "1";
		else $output = "2";
		return $output;
	}
	
	$registration_open = open_or_closed(strtotime("now"),$row_contest_dates['contestRegistrationOpen'],$row_contest_dates['contestRegistrationDeadline']);
	
	if ($registration_open == 1) {
		$email = $_POST['user_name'];
	
		$query_user_exists = "SELECT * FROM nhcentrant WHERE email = '$email'";
		$user_exists = mysqli_query($connection,$query_user_exists) or die (mysqli_error($connection));
		$row_user_exists = mysqli_fetch_assoc($user_exists);
		$totalRows_user_exists = mysqli_num_rows($user_exists);
		
		// Email in the nhcentrants table. They have already been warned about its existance. Redirect.
		if ($totalRows_user_exists > 0) {
			//echo $totalRows_user_exists."<br>";
			header(sprintf("Location: %s", $nhc_landing_url."/index.php?msg=5"));
			exit();
		}
		
		
		$aha = $_POST['brewerAHA']; 
		if ($aha != "") {
			$query_aha_exists = "SELECT COUNT(*) AS count FROM nhcentrant WHERE AHANumber = '$aha'";
			$aha_exists = mysqli_query($connection,$query_aha_exists) or die (mysqli_error($connection));
			$row_aha_exists = mysqli_fetch_assoc($aha_exists);
			
			if ($row_aha_exists['count'] > 0) $aha_exists = TRUE; else $aha_exists = FALSE;
		}
		
		if ($aha == "") $aha_exists = FALSE;
		//echo $aha_exists;
		//exit();
		// If the AHA number is in the DB, redirect.
		if ($aha_exists) {
			setcookie("user_name", $_POST['user_name'], 0, "/");
			setcookie("user_name2", $_POST['user_name2'], 0, "/");
			setcookie("password", $_POST['password'], 0, "/");
			setcookie("userQuestion", $_POST['userQuestion'], 0, "/");
			setcookie("userQuestionAnswer", $_POST['userQuestionAnswer'], 0, "/");
			setcookie("brewerFirstName", $_POST['brewerFirstName'], 0, "/");
			setcookie("brewerLastName", $_POST['brewerLastName'], 0, "/");
			setcookie("brewerAddress", $_POST['brewerAddress'], 0, "/");
			setcookie("brewerCity", $_POST['brewerCity'], 0, "/");
			setcookie("brewerState", $_POST['brewerState'], 0, "/");
			setcookie("brewerZip", $_POST['brewerZip'], 0, "/");
			setcookie("brewerCountry", $_POST['brewerCountry'], 0, "/");
			setcookie("brewerPhone1", $_POST['brewerPhone1'], 0, "/");
			setcookie("brewerPhone2", $_POST['brewerPhone2'], 0, "/");
			setcookie("brewerClubs", $_POST['brewerClubs'], 0, "/");
			setcookie("brewerAHA", $_POST['brewerAHA'], 0, "/");
			setcookie("brewerSteward", $_POST['brewerSteward'], 0, "/");
			setcookie("brewerJudge", $_POST['brewerJudge'], 0, "/");
			//echo "AHA exists!";
			header(sprintf("Location: %s", $base_url."index.php?section=".$section."&go=".$go."&msg=6"));
			exit();
			
		}
		/*
		// If AHA is blank or doesn't exist, perform other checks and redirect if needed.
		if (!$aha_exists) {  }
		*/
		
	}
	
	// ...and proceed normally with registration at the Region level.
	}
	
	// CAPCHA check
	if ($filter != "admin") {
	require_once(INCLUDES.'recaptchalib.inc.php');
	$privatekey = "6LdquuQSAAAAAHkf3dDRqZckRb_RIjrkofxE8Knd";
	$resp = recaptcha_check_answer ($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
	}
	
	if (($view == "default") && ($filter != "admin") && (!$resp->is_valid)) {
	setcookie("user_name", $_POST['user_name'], 0, "/");
	setcookie("user_name2", $_POST['user_name2'], 0, "/");
	setcookie("password", $_POST['password'], 0, "/");
	setcookie("userQuestion", $_POST['userQuestion'], 0, "/");
	setcookie("userQuestionAnswer", $_POST['userQuestionAnswer'], 0, "/");
	setcookie("brewerFirstName", $_POST['brewerFirstName'], 0, "/");
	setcookie("brewerLastName", $_POST['brewerLastName'], 0, "/");
	setcookie("brewerAddress", $_POST['brewerAddress'], 0, "/");
	setcookie("brewerCity", $_POST['brewerCity'], 0, "/");
	setcookie("brewerState", $_POST['brewerState'], 0, "/");
	setcookie("brewerZip", $_POST['brewerZip'], 0, "/");
	setcookie("brewerCountry", $_POST['brewerCountry'], 0, "/");
	setcookie("brewerPhone1", $_POST['brewerPhone1'], 0, "/");
	setcookie("brewerPhone2", $_POST['brewerPhone2'], 0, "/");
	setcookie("brewerClubs", $_POST['brewerClubs'], 0, "/");
	setcookie("brewerAHA", $_POST['brewerAHA'], 0, "/");
	setcookie("brewerSteward", $_POST['brewerSteward'], 0, "/");
	setcookie("brewerJudge", $_POST['brewerJudge'], 0, "/");
	$location = $base_url."index.php?section=".$section."&go=".$go."&msg=4";
	header(sprintf("Location: %s", $location));
	}
	
	elseif (($view == "default") && ($_POST['user_name'] != $_POST['user_name2'])) {
	setcookie("user_name", $_POST['user_name'], 0, "/");
	setcookie("user_name2", $_POST['user_name2'], 0, "/");
	setcookie("password", $_POST['password'], 0, "/");
	setcookie("userQuestion", $_POST['userQuestion'], 0, "/");
	setcookie("userQuestionAnswer", $_POST['userQuestionAnswer'], 0, "/");
	setcookie("brewerFirstName", $_POST['brewerFirstName'], 0, "/");
	setcookie("brewerLastName", $_POST['brewerLastName'], 0, "/");
	setcookie("brewerAddress", $_POST['brewerAddress'], 0, "/");
	setcookie("brewerCity", $_POST['brewerCity'], 0, "/");
	setcookie("brewerState", $_POST['brewerState'], 0, "/");
	setcookie("brewerZip", $_POST['brewerZip'], 0, "/");
	setcookie("brewerCountry", $_POST['brewerCountry'], 0, "/");
	setcookie("brewerPhone1", $_POST['brewerPhone1'], 0, "/");
	setcookie("brewerPhone2", $_POST['brewerPhone2'], 0, "/");
	setcookie("brewerClubs", $_POST['brewerClubs'], 0, "/");
	setcookie("brewerAHA", $_POST['brewerAHA'], 0, "/");
	setcookie("brewerSteward", $_POST['brewerSteward'], 0, "/");
	setcookie("brewerJudge", $_POST['brewerJudge'], 0, "/");
	if ($filter == "admin") $location =  $base_url."index.php?section=admin&go=entrant&action=register&msg=27";
	else $location = $base_url."index.php?section=".$section."&go=".$go."&msg=5";
	header(sprintf("Location: %s", $location));

} // end if NHC

else {

// Check to see if email address is already in the system. If so, redirect.
$username = strtolower($_POST['user_name']);

if ((strstr($username,'@')) && (strstr($username,'.'))) {
	
	// Sanity check from AJAX widget
	$query_userCheck = "SELECT user_name FROM $users_db_table WHERE user_name = '$username'";
	$userCheck = mysqli_query($connection,$query_userCheck) or die (mysqli_error($connection));
	$row_userCheck = mysqli_fetch_assoc($userCheck);
	$totalRows_userCheck = mysqli_num_rows($userCheck);

	if ($totalRows_userCheck > 0) {
		
		setcookie("userQuestion", $_POST['userQuestion'], 0, "/");
		setcookie("userQuestionAnswer", $_POST['userQuestionAnswer'], 0, "/");
		setcookie("brewerFirstName", $_POST['brewerFirstName'], 0, "/");
		setcookie("brewerLastName", $_POST['brewerLastName'], 0, "/");
		setcookie("brewerAddress", $_POST['brewerAddress'], 0, "/");
		setcookie("brewerCity", $_POST['brewerCity'], 0, "/");
		setcookie("brewerState", $_POST['brewerState'], 0, "/");
		setcookie("brewerZip", $_POST['brewerZip'], 0, "/");
		setcookie("brewerCountry", $_POST['brewerCountry'], 0, "/");
		setcookie("brewerPhone1", $_POST['brewerPhone1'], 0, "/");
		setcookie("brewerPhone2", $_POST['brewerPhone2'], 0, "/");
		setcookie("brewerClubs", $_POST['brewerClubs'], 0, "/");
		setcookie("brewerAHA", $_POST['brewerAHA'], 0, "/");
		setcookie("brewerSteward", $_POST['brewerSteward'], 0, "/");
		setcookie("brewerJudge", $_POST['brewerJudge'], 0, "/");
		
		if ($filter == "admin") header(sprintf("Location: %s", $base_url."index.php?section=admin&go=".$go."&action=register&msg=10"));
		else header(sprintf("Location: %s", $base_url."index.php?section=".$section."&go=".$go."&action=".$action."&msg=2"));
	  }
	else  {
		
	// Add the user's creds to the "users" table
		$password = md5($_POST['password']);
		require(CLASSES.'phpass/PasswordHash.php');
		$hasher = new PasswordHash(8, false);
		$hash = $hasher->HashPassword($password);
		$insertSQL = sprintf("INSERT INTO $users_db_table (user_name, userLevel, password, userQuestion, userQuestionAnswer, userCreated) VALUES (%s, %s, %s, %s, %s, %s)", 
                       GetSQLValueString($username, "text"),
					   GetSQLValueString($_POST['userLevel'], "text"),
                       GetSQLValueString($hash, "text"),
					   GetSQLValueString($_POST['userQuestion'], "text"),
					   GetSQLValueString($_POST['userQuestionAnswer'], "text"),
					   "NOW( )"					   
					   );
		
		mysqli_real_escape_string($connection,$insertSQL);
		$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));

		//echo $insertSQL."<br />";
	// Get the id from the "users" table to insert as the uid in the "brewer" table
		$query_user= "SELECT id FROM $users_db_table WHERE user_name = '$username'";
		$user = mysqli_query($connection,$query_user) or die (mysqli_error($connection));
		$row_user = mysqli_fetch_assoc($user);
		
   if ($_POST['brewerJudge'] == "Y") {
		if (($_POST['brewerJudgeLocation'] != "") && (is_array($_POST['brewerJudgeLocation']))) $location_pref1 = implode(",",$_POST['brewerJudgeLocation']);
        elseif (($_POST['brewerJudgeLocation'] != "") && (!is_array($_POST['brewerJudgeLocation']))) $location_pref1 = $_POST['brewerJudgeLocation'];
        
	}
	else $location_pref1 = "";
	
	if ($_POST['brewerSteward'] == "Y") {
        if (($_POST['brewerStewardLocation'] != "") && (is_array($_POST['brewerStewardLocation']))) $location_pref2 = implode(",",$_POST['brewerStewardLocation']);
        elseif (($_POST['brewerJudgeLocation'] != "") && (!is_array($_POST['brewerStewardLocation']))) $location_pref2 = $_POST['brewerStewardLocation'];
	}
    else $location_pref2 = "";
	
		// Add the user's info to the "brewer" table
	  	// Numbers 999999994 through 999999999 are reserved for NHC applications.
		if (($_POST['brewerAHA'] < "999999994") || ($_POST['brewerAHA'] == "")) {
			
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
			  brewerAHA
			) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
						   GetSQLValueString($row_user['id'], "int"),
						   GetSQLValueString(capitalize($_POST['brewerFirstName']), "text"),
						   GetSQLValueString(capitalize($_POST['brewerLastName']), "text"),
						   GetSQLValueString(capitalize($_POST['brewerAddress']), "text"),
						   GetSQLValueString(capitalize($_POST['brewerCity']), "text"),
						   GetSQLValueString($_POST['brewerState'], "text"),
						   GetSQLValueString($_POST['brewerZip'], "text"),
						   GetSQLValueString($_POST['brewerCountry'], "text"),
						   GetSQLValueString($_POST['brewerPhone1'], "text"),
						   GetSQLValueString($_POST['brewerPhone2'], "text"),
						   GetSQLValueString($_POST['brewerClubs'], "text"),
						   GetSQLValueString($username, "text"),
						   GetSQLValueString($_POST['brewerSteward'], "text"),
						   GetSQLValueString($_POST['brewerJudge'], "text"),
						   GetSQLValueString($_POST['brewerJudgeID'], "text"),
						   GetSQLValueString($_POST['brewerJudgeMead'], "text"),
						   GetSQLValueString($_POST['brewerJudgeRank'], "text"),
						   GetSQLValueString($location_pref1, "text"),
						   GetSQLValueString($location_pref2, "text"),
						   GetSQLValueString($_POST['brewerAHA'], "int")
						   );
		}
		
		else {
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
			  brewerStewardLocation
			) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
						   GetSQLValueString($row_user['id'], "int"),
						   GetSQLValueString(capitalize($_POST['brewerFirstName']), "text"),
						   GetSQLValueString(capitalize($_POST['brewerLastName']), "text"),
						   GetSQLValueString(capitalize($_POST['brewerAddress']), "text"),
						   GetSQLValueString(capitalize($_POST['brewerCity']), "text"),
						   GetSQLValueString($_POST['brewerState'], "text"),
						   GetSQLValueString($_POST['brewerZip'], "text"),
						   GetSQLValueString($_POST['brewerCountry'], "text"),
						   GetSQLValueString($_POST['brewerPhone1'], "text"),
						   GetSQLValueString($_POST['brewerPhone2'], "text"),
						   GetSQLValueString($_POST['brewerClubs'], "text"),
						   GetSQLValueString($username, "text"),
						   GetSQLValueString($_POST['brewerSteward'], "text"),
						   GetSQLValueString($_POST['brewerJudge'], "text"),
						   GetSQLValueString($_POST['brewerJudgeID'], "text"),
						   GetSQLValueString($_POST['brewerJudgeMead'], "text"),
						   GetSQLValueString($_POST['brewerJudgeRank'], "text"),
						   GetSQLValueString($location_pref1, "text"),
						   GetSQLValueString($location_pref2, "text")
						   );
		}
		
		if(NHC) {
			$updateSQL =  sprintf("INSERT INTO nhcentrant (
			uid, 
			firstName, 
			lastName, 
			email,
			AHAnumber,
			regionPrefix
			) 
			VALUES 
			(%s, %s, %s, %s, %s, %s)",
							   GetSQLValueString($row_user['id'], "int"),
							   GetSQLValueString(capitalize($_POST['brewerFirstName']), "text"),
							   GetSQLValueString(capitalize($_POST['brewerLastName']), "text"),
							   GetSQLValueString($username, "text"),
							   GetSQLValueString($_POST['brewerAHA'], "text"),
							   GetSQLValueString($prefix, "text"));
			
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		}
		
		
		mysqli_real_escape_string($connection,$insertSQL);
		$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));

		
		
		// Stop Gap for random staff assignments
		
		$updateSQL = sprintf("UPDATE %s  SET  staff_judge='0', staff_judge_bos='0', staff_steward='0', staff_organizer='0', staff_staff='0' WHERE uid=%s",$prefix."staff",$row_user['id']);
		mysqli_real_escape_string($connection,$updateSQL);
		$result1 = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		
	
	if ($filter == "default") {
	    // Log in the user and redirect
		session_start();
		$_SESSION['loginUsername'] = $username;
		
		// Redirect to Judge Info section if willing to judge
		if ($_POST['brewerJudge'] == "Y") {
			$query_brewer= sprintf("SELECT id FROM $brewer_db_table WHERE uid = '%s'", $row_user['id']);
			$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
			$row_brewer = mysqli_fetch_assoc($brewer);
			header(sprintf("Location: %s", $base_url."index.php?section=brewer&action=edit&go=judge&id=".$row_brewer['id']."#judge"));
		}
		else header(sprintf("Location: %s", $base_url."index.php?section=list&msg=1"));
	  } // end if ($filter == "default")
	
	if ($filter == "admin") {
		
		// Redirect to Judge Info section if willing to judge
		if ($_POST['brewerJudge'] == "Y") {
			$query_brewer= sprintf("SELECT id FROM $brewer_db_table WHERE uid = '%s'", $row_user['id']);
			$brewer = mysqli_query($connection,$query_brewer) or die (mysqli_error($connection));
			$row_brewer = mysqli_fetch_assoc($brewer);
			if ($view == "quick") $insertGoTo = $base_url."index.php?section=admin&go=participants&msg=28";
			else $insertGoTo = $base_url."index.php?section=brewer&go=admin&filter=".$row_brewer['id']."&action=edit&go=judge&id=".$row_brewer['id']."#judge";
			header(sprintf("Location: %s", stripslashes($insertGoTo)));
		}
		else { 
			$pattern = array('\'', '"');
			$insertGoTo = $base_url."index.php?section=admin";
			$insertGoTo = str_replace($pattern, "", $insertGoTo); 
			header(sprintf("Location: %s", stripslashes($insertGoTo)));
		}
		//echo $insertGoTo;
	  } // end if ($filter == "admin")
	}
  }
  //if ($filter == "admin") header(sprintf("Location:  %s", $base_url."index.php?section=".$section."&go=".$go."&action=".$action."&msg=3"));
 // else header(sprintf("Location: %s", $base_url."index.php?section=".$section."&go=".$go."&action=".$action."&msg=3"));
} // End CAPCHA check

?>