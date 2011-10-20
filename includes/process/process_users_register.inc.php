<?php 
/*
 * Module:      process_users_register.inc.php
 * Description: This module does all the heavy lifting for adding a user's info to the "users" and
 *              the "brewer" tables upon registration
 */

// CAPCHA check
include_once  (ROOT.'captcha/securimage.php');
$securimage = new Securimage();

if ($securimage->check($_POST['captcha_code']) == false) {
	setcookie("user_name", $_POST['user_name'], 0, "/");
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
	header("Location: ../index.php?section=".$section."&go=".$go."&msg=4");
}

else {

// Check to see if email address is already in the system. If so, redirect.
$username = $_POST['user_name'];

if ((strstr($username,'@')) && (strstr($username,'.'))) {
	
	// Sanity check from AJAX widget
	mysql_select_db($database, $brewing);
	$query_userCheck = "SELECT user_name FROM users WHERE user_name = '$username'";
	$userCheck = mysql_query($query_userCheck, $brewing) or die(mysql_error());
	$row_userCheck = mysql_fetch_assoc($userCheck);
	$totalRows_userCheck = mysql_num_rows($userCheck);

	if ($totalRows_userCheck > 0) {
		header("Location: ../index.php?section=".$section."&go=".$go."&action=".$action."&msg=2");
	  }
	else  {
	// Add the user's creds to the "users" table
		$password = md5($_POST['password']);
		$insertSQL = sprintf("INSERT INTO users (user_name, userLevel, password, userQuestion, userQuestionAnswer) VALUES (%s, %s, %s, %s, %s)", 
							   GetSQLValueString($username, "text"),
							   GetSQLValueString($_POST['userLevel'], "text"),
							   GetSQLValueString($password, "text"),
							   GetSQLValueString($_POST['userQuestion'], "text"),
							   GetSQLValueString($_POST['userQuestionAnswer'], "text"));
		mysql_select_db($database, $brewing);
		$Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());
	
	// Get the id from the "users" table to insert as the uid in the "brewer" table
		$query_user= "SELECT id FROM users WHERE user_name = '$username'";
		$user = mysql_query($query_user, $brewing) or die(mysql_error());
		$row_user = mysql_fetch_assoc($user);
	
	// Add the user's info to the "brewer" table
	  	$insertSQL = sprintf("INSERT INTO brewer (
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
				   GetSQLValueString(capitalize($_POST['brewerState']), "text"),
				   GetSQLValueString($_POST['brewerZip'], "text"),
				   GetSQLValueString($_POST['brewerCountry'], "text"),
				   GetSQLValueString($_POST['brewerPhone1'], "text"),
				   GetSQLValueString($_POST['brewerPhone2'], "text"),
				   GetSQLValueString(capitalize($_POST['brewerClubs']), "text"),
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

		mysql_select_db($database, $brewing);
		$Result1 = mysql_query($insertSQL, $brewing) or die(mysql_error());	
	
	// Log in the user and redirect
		session_start();
		$_SESSION["loginUsername"] = $username;
		
		// Redirect to Judge Info section if willing to judge
		if ($_POST['brewerJudge'] == "Y") {
			$query_brewer= sprintf("SELECT id FROM brewer WHERE uid = '%s'", $row_user['id']);
			$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
			$row_brewer = mysql_fetch_assoc($brewer);
			header("Location: ../index.php?section=brewer&action=edit&go=judge&id=".$row_brewer['id']."#judge");
		}
		else header("Location: ../index.php?section=list&msg=1");
	}
  }
  else header("Location: ../index.php?section=".$section."&go=".$go."&action=".$action."&msg=3");
} // End CAPCHA check

?>