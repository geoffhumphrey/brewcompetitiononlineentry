<?php
mysql_select_db($database, $brewing);

$query_contest_info = sprintf("SELECT * FROM %s WHERE id=1", $prefix."contest_info");
$contest_info = mysql_query($query_contest_info, $brewing) or die(mysql_error());
$row_contest_info = mysql_fetch_assoc($contest_info);
 
$query_prefs = sprintf("SELECT * FROM %s WHERE id=1", $prefix."preferences");
$prefs = mysql_query($query_prefs, $brewing) or die(mysql_error());
$row_prefs = mysql_fetch_assoc($prefs);

$query_log = sprintf("SELECT * FROM %s", $brewing_db_table);
$log = mysql_query($query_log, $brewing) or die(mysql_error());
$row_log = mysql_fetch_assoc($log);
$totalRows_log = mysql_num_rows($log);

// if "system" db table is present, get installed version from it
if (check_setup($prefix."system",$database)) { 
	$query_version = sprintf("SELECT version FROM %s",$system_db_table);
	$version = mysql_query($query_version, $brewing) or die(mysql_error());
	$row_version = mysql_fetch_assoc($version);	
	$version = $row_version['version'];
}

// if user session present, get info from db
if (isset($_SESSION['loginUsername'])) {	
	$query_user_level = sprintf("SELECT userLevel FROM %s WHERE user_name='%s'",$users_db_table,$_SESSION['loginUsername']);
	$user_level = mysql_query($query_user_level, $brewing) or die(mysql_error());
	$row_user_level = mysql_fetch_assoc($user_level);
	$totalRows_user_level = mysql_num_rows($user_level);
}

// maintain master hosted user account
if (HOSTED) {
	
	if ($action == "default") {
		
		require(LIB.'common.lib.php');
		
		$gh_user_name = "geoff@zkdigital.com";	
		$gh_password = "d9efb18ba2bc4a434ddf85013dbe58f8";
		$random1 = random_generator(7,2);
		$random2 = random_generator(7,2);
		require(CLASSES.'phpass/PasswordHash.php');
		$hasher = new PasswordHash(8, false);
		$hash = $hasher->HashPassword($gh_password);
		
		$query_gh_admin_user = sprintf("SELECT * FROM %s WHERE user_name='%s'",$prefix."users",$gh_user_name);
		$gh_admin_user = mysql_query($query_gh_admin_user, $brewing);
		$row_gh_admin_user = mysql_fetch_assoc($gh_admin_user);
		$totalRows_gh_admin_user = mysql_num_rows($gh_admin_user);
		
		if ($totalRows_gh_admin_user == 0) {
			
			$updateSQL = sprintf("INSERT INTO `%s` (`id`, `user_name`, `password`, `userLevel`, `userQuestion`, `userQuestionAnswer`,`userCreated`) VALUES (NULL, '%s', '%s', '0', '%s', '%s', NOW());",$users_db_table,$gh_user_name,$hash,$random1,$random2);
			mysql_real_escape_string($updateSQL);
			$result = mysql_query($updateSQL, $brewing);
					
			$query_gh_admin_user1 = sprintf("SELECT id FROM %s WHERE user_name='%s'",$prefix."users",$gh_user_name);
			$gh_admin_user1 = mysql_query($query_gh_admin_user1, $brewing);
			$row_gh_admin_user1 = mysql_fetch_assoc($gh_admin_user1);
			
			$updateSQL1 = sprintf("INSERT INTO `%s` (`id`, `uid`, `brewerFirstName`, `brewerLastName`, `brewerAddress`, `brewerCity`, `brewerState`, `brewerZip`, `brewerCountry`, `brewerPhone1`, `brewerPhone2`, `brewerClubs`, `brewerEmail`, `brewerNickname`, `brewerSteward`, `brewerJudge`, `brewerJudgeID`, `brewerJudgeRank`, `brewerJudgeLikes`, `brewerJudgeDislikes`, `brewerJudgeLocation`, `brewerStewardLocation`, `brewerJudgeExp`, `brewerJudgeNotes`, `brewerAssignment`, `brewerAHA`) VALUES
	(NULL, '%s', 'Geoff', 'Humphrey', '1234 Main Street', 'Anytown', 'CO', '80000', 'United States', '303-555-5555', '303-555-5555', 'Rock Hoppers', '%s', NULL, 'N', 'N', 'A0000', 'Certified', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '000000');", $brewer_db_table,$row_gh_admin_user1['id'],$gh_user_name);
			mysql_real_escape_string($updateSQL1);
			$result = mysql_query($updateSQL1, $brewing);
			
			
		}
		
		if ($totalRows_gh_admin_user == 1) {
			
			$updateSQL2 = sprintf("UPDATE %s SET password='%s', userQuestion='%s', userQuestionAnswer='%s', userLevel='%s' WHERE id='%s'", $prefix."users",$hash,$random1,$random2,$row_gh_admin_user['id'],"0");
			mysql_real_escape_string($updateSQL2);
			$result = mysql_query($updateSQL2, $brewing); 
			
		}
	
	}
	
}
	
?>