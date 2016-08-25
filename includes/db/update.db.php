<?php
mysqli_select_db($connection,$database);

$query_contest_info = sprintf("SELECT * FROM %s WHERE id=1", $prefix."contest_info");
$contest_info = mysqli_query($connection,$query_contest_info) or die (mysqli_error($connection));
$row_contest_info = mysqli_fetch_assoc($contest_info);
 
$query_prefs = sprintf("SELECT * FROM %s WHERE id=1", $prefix."preferences");
$prefs = mysqli_query($connection,$query_prefs) or die (mysqli_error($connection));
$row_prefs = mysqli_fetch_assoc($prefs);

$query_log = sprintf("SELECT * FROM %s", $brewing_db_table);
$log = mysqli_query($connection,$query_log) or die (mysqli_error($connection));
$row_log = mysqli_fetch_assoc($log);
$totalRows_log = mysqli_num_rows($log);

// if "system" db table is present, get installed version from it
if (check_setup($prefix."system",$database)) { 
	$query_version = sprintf("SELECT version,version_date FROM %s",$system_db_table);
	$version = mysqli_query($connection,$query_version) or die (mysqli_error($connection));
	$row_version = mysqli_fetch_assoc($version);	
	$version = $row_version['version'];
	$version_date = strtotime($row_version['version_date']); 
}

// if user session present, get info from db
if (isset($_SESSION['loginUsername'])) {	
	$query_user_level = sprintf("SELECT userLevel FROM %s WHERE user_name='%s'",$users_db_table,$_SESSION['loginUsername']);
	$user_level = mysqli_query($connection,$query_user_level) or die (mysqli_error($connection));
	$row_user_level = mysqli_fetch_assoc($user_level);
	$totalRows_user_level = mysqli_num_rows($user_level);
}

// maintain master hosted user account
if (HOSTED) {
	
	if ($action == "default") {
		
		require(INCLUDES.'url_variables.inc.php');
		
		$gh_user_name = "geoff@zkdigital.com";	
		$gh_password = "d9efb18ba2bc4a434ddf85013dbe58f8";
		$random1 = random_generator(7,2);
		$random2 = random_generator(7,2);
		require(CLASSES.'phpass/PasswordHash.php');
		$hasher = new PasswordHash(8, false);
		$hash = $hasher->HashPassword($gh_password);
		
		$query_gh_admin_user = sprintf("SELECT * FROM %s WHERE user_name='%s'",$prefix."users",$gh_user_name);
		$gh_admin_user = mysqli_query($connection,$query_gh_admin_user) or die (mysqli_error($connection));
		$row_gh_admin_user = mysqli_fetch_assoc($gh_admin_user);
		$totalRows_gh_admin_user = mysqli_num_rows($gh_admin_user);
		
		if ($totalRows_gh_admin_user == 0) {
			
			$updateSQL = sprintf("INSERT INTO `%s` (`id`, `user_name`, `password`, `userLevel`, `userQuestion`, `userQuestionAnswer`,`userCreated`) VALUES (NULL, '%s', '%s', '0', '%s', '%s', NOW());",$users_db_table,$gh_user_name,$hash,$random1,$random2);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
					
			$query_gh_admin_user1 = sprintf("SELECT id FROM %s WHERE user_name='%s'",$prefix."users",$gh_user_name);
			$gh_admin_user1 = mysqli_query($connection,$query_gh_admin_user1) or die (mysqli_error($connection));
			$row_gh_admin_user1 = mysqli_fetch_assoc($gh_admin_user1);
			
			$updateSQL = sprintf("INSERT INTO `%s` (`id`, `uid`, `brewerFirstName`, `brewerLastName`, `brewerAddress`, `brewerCity`, `brewerState`, `brewerZip`, `brewerCountry`, `brewerPhone1`, `brewerPhone2`, `brewerClubs`, `brewerEmail`, `brewerNickname`, `brewerSteward`, `brewerJudge`, `brewerJudgeID`, `brewerJudgeRank`, `brewerAHA`) VALUES
	(NULL, '%s', 'Geoff', 'Humphrey', '1234 Main Street', 'Anytown', 'CO', '80000', 'United States', '303-555-5555', '303-555-5555', 'Rock Hoppers', '%s', NULL, 'N', 'N', 'A0000', 'Certified', '000000');", $brewer_db_table,$row_gh_admin_user1['id'],$gh_user_name);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			
		}
		
		if ($totalRows_gh_admin_user == 1) {
			
			$updateSQL2 = sprintf("UPDATE %s SET password='%s', userQuestion='%s', userQuestionAnswer='%s', userLevel='%s' WHERE id='%s'", $prefix."users",$hash,$random1,$random2,$row_gh_admin_user['id'],"0");
			mysqli_real_escape_string($connection,$updateSQL2);
			$result = mysqli_query($connection,$updateSQL2) or die (mysqli_error($connection)); 
			
		}
	
	}
	
}
	
?>