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
		$user_exists = mysql_query($query_user_exists, $brewing) or die(mysql_error());
		$row_user_exists = mysql_fetch_assoc($user_exists);
		$totalRows_user_exists = mysql_num_rows($user_exists);
		
		// Email in the nhcentrants table. They have already been warned about its existance. Redirect.
		if ($totalRows_user_exists > 0) {
			//echo $totalRows_user_exists."<br>";
			header(sprintf("Location: %s", $nhc_landing_url."/index.php?msg=5"));
			exit();
		}
		
		
		$aha = $_POST['brewerAHA']; 
		if ($aha != "") {
			$query_aha_exists = "SELECT COUNT(*) AS count FROM nhcentrant WHERE AHANumber = '$aha'";
			$aha_exists = mysql_query($query_aha_exists, $brewing) or die(mysql_error());
			$row_aha_exists = mysql_fetch_assoc($aha_exists);
			
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
		mysql_free_result($user_exists);
	}
	
	// ...and proceed normally with registration at the Region level.
}

// CAPCHA check
if ($filter != "admin") {
require_once(INCLUDES.'recaptchalib.inc.php');
$privatekey = "6LdquuQSAAAAAHkf3dDRqZckRb_RIjrkofxE8Knd";
$resp = recaptcha_check_answer ($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
}

if (($filter != "admin") && (!$resp->is_valid)) {
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

elseif ($_POST['user_name'] != $_POST['user_name2']) {
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
	if ($filter == "admin") $location =  $base_url."index.php?section=admin&go=entrant&action=register&msg=16";
	else $location = $base_url."index.php?section=".$section."&go=".$go."&msg=5";
	header(sprintf("Location: %s", $location));
}

else {

// Check to see if email address is already in the system. If so, redirect.
$username = strtolower($_POST['user_name']);

if ((strstr($username,'@')) && (strstr($username,'.'))) {
	
	// Sanity check from AJAX widget
	mysql_select_db($database, $brewing);
	$query_userCheck = "SELECT user_name FROM $users_db_table WHERE user_name = '$username'";
	$userCheck = mysql_query($query_userCheck, $brewing) or die(mysql_error());
	$row_userCheck = mysql_fetch_assoc($userCheck);
	$totalRows_userCheck = mysql_num_rows($userCheck);

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
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($insertSQL);
		$result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
		//echo $insertSQL."<br />";
	// Get the id from the "users" table to insert as the uid in the "brewer" table
		$query_user= "SELECT id FROM $users_db_table WHERE user_name = '$username'";
		$user = mysql_query($query_user, $brewing) or die(mysql_error());
		$row_user = mysql_fetch_assoc($user);
		
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
			mysql_real_escape_string($updateSQL);
			$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
		}
		
		
		//echo $insertSQL;
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($insertSQL);
		$result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());	
	
	if ($filter == "default") {
	    // Log in the user and redirect
		session_start();
		$_SESSION['loginUsername'] = $username;
		
		// Redirect to Judge Info section if willing to judge
		if ($_POST['brewerJudge'] == "Y") {
			$query_brewer= sprintf("SELECT id FROM $brewer_db_table WHERE uid = '%s'", $row_user['id']);
			$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
			$row_brewer = mysql_fetch_assoc($brewer);
			header(sprintf("Location: %s", $base_url."index.php?section=brewer&action=edit&go=judge&id=".$row_brewer['id']."#judge"));
		}
		else header(sprintf("Location: %s", $base_url."index.php?section=list&msg=1"));
	  } // end if ($filter == "default")
	
	if ($filter == "admin") {
		
		// Redirect to Judge Info section if willing to judge
		if ($_POST['brewerJudge'] == "Y") {
			$query_brewer= sprintf("SELECT id FROM $brewer_db_table WHERE uid = '%s'", $row_user['id']);
			$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
			$row_brewer = mysql_fetch_assoc($brewer);
			$insertGoTo = $base_url."index.php?section=brewer&go=admin&filter=".$row_brewer['id']."&action=edit&go=judge&id=".$row_brewer['id']."#judge";
			header(sprintf("Location: %s", stripslashes($insertGoTo)));
		}
		else { 
			$pattern = array('\'', '"');
			//$insertGoTo = $base_url."index.php?section=admin";
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