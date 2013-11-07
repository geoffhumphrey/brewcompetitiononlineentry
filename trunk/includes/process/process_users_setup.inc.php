<?php 
/*
 * Module:      process_users_setup.inc.php
 * Description: This module does all the heavy lifting for adding an admin user to the DB (Setup ONLY)
 */
 
if (NHC) {
	// Place NHC SQL calls below
	
	
}
// end if (NHC)

else {

	$username = strtolower($_POST['user_name']);
	if ((strstr($username,'@')) && (strstr($username,'.'))) {
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
		
		$insertGoTo = $base_url."setup.php?section=step2&go=".$username;
		$pattern = array('\'', '"');
		$insertGoTo = str_replace($pattern, "", $insertGoTo); 
		header(sprintf("Location: %s", stripslashes($insertGoTo)));	
		
		session_start();
		$_SESSION['loginUsername'] = $username;
	}
	else {
		$GoTo = $base_url."setup.php?section=step1&msg=1";
		$pattern = array('\'', '"');
		$GoTo = str_replace($pattern, "", $GoTo); 
		header(sprintf("Location: %s", stripslashes($GoTo)));	
	}

} // end else NHC

?>