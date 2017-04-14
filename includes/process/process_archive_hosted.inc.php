<?php
if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] == 0))) { 
	session_name($prefix_session);
	session_start();
	require(INCLUDES.'scrubber.inc.php');
	require(INCLUDES.'db_tables.inc.php');
	$dbTable = "default";
	
	if ($filter == "default") {
		// Gather current User's information from the current "users" AND current "brewer" tables and store in variables
		$query_user = sprintf("SELECT * FROM %s WHERE user_name = '%s'", $prefix."users", $_SESSION['loginUsername']);
		$user = mysqli_query($connection,$query_user) or die (mysqli_error($connection));
		$row_user = mysqli_fetch_assoc($user);
		$user_name = strip_tags($row_user['user_name']);
		
		$password = $row_user['password'];
		$userLevel = $row_user['userLevel'];
		$userQuestion = strip_tags($row_user['userQuestion']);
		$userQuestionAnswer = strip_tags($row_user['userQuestionAnswer']);
		$userCreated = $row_user['userCreated'];
		
		$query_name = sprintf("SELECT * FROM %s WHERE uid='%s'", $prefix."brewer", $row_user['id']);
		$name = mysqli_query($connection,$query_name) or die (mysqli_error($connection));
		$row_name = mysqli_fetch_assoc($name);
		
		$brewerFirstName = strip_tags($row_name['brewerFirstName']);
		$brewerLastName = strip_tags($row_name['brewerLastName']);
		$brewerAddress = strip_tags($row_name['brewerAddress']);
		$brewerCity = strip_tags($row_name['brewerCity']);
		$brewerState = $row_name['brewerState'];
		$brewerZip = $row_name['brewerZip'];
		$brewerCountry = $row_name['brewerCountry'];
		$brewerPhone1 = $row_name['brewerPhone1'];
		$brewerPhone2 = $row_name['brewerPhone2'];
		$brewerClubs = $row_name['brewerClubs'];
		$brewerEmail = $row_name['brewerEmail'];
		$brewerStaff = $row_name['brewerStaff'];
		$brewerSteward = $row_name['brewerSteward'];
		$brewerJudge = $row_name['brewerJudge'];
		$brewerJudgeID = $row_name['brewerJudgeID'];
		$brewerJudgeRank = $row_name['brewerJudgeRank'];
		$brewerJudgeLikes = $row_name['brewerJudgeLikes'];
		$brewerJudgeDislikes = $row_name['brewerJudgeDislikes'];
		$brewerAHA = $row_name['brewerAHA'];
	}
	
	$tables_array = array($special_best_info_db_table, $special_best_data_db_table, $brewing_db_table, $judging_assignments_db_table, $judging_flights_db_table, $judging_scores_db_table, $judging_scores_bos_db_table, $judging_tables_db_table, $staff_db_table);
	
	if ($filter == "default") {
		$tables_array[] .= $users_db_table;
		$tables_array[] .= $brewer_db_table;
	}
	
	foreach ($tables_array as $table) { 
	
		$updateSQL = "TRUNCATE ".$table.";";
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		
	}
	
	// If not retaining participant data, insert current user's info into new "users" and "brewer" table
	if ($filter == "default") {
	
		$insertSQL = "INSERT INTO $users_db_table (id, user_name, password,	userLevel, userQuestion, userQuestionAnswer, userCreated) VALUES ('1', '$user_name', '$password', '0', '$userQuestion', '$userQuestionAnswer', NOW());";
		mysqli_real_escape_string($connection,$insertSQL);
		$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));
		$insertSQL = "INSERT INTO $brewer_db_table (id, uid, brewerFirstName, brewerLastName, brewerAddress, brewerCity, brewerState, brewerZip, brewerCountry, brewerPhone1, brewerPhone2, brewerClubs, brewerEmail, brewerStaff, brewerSteward, 	brewerJudge, brewerJudgeID, brewerJudgeRank, brewerJudgeLikes, brewerJudgeDislikes, brewerJudgeLocation, brewerStewardLocation, brewerJudgeExp, brewerJudgeNotes, brewerAssignment, brewerAHA, brewerDiscount, brewerJudgeBOS, brewerDropOff) VALUES (NULL, '1', '$brewerFirstName', '$brewerLastName', '$brewerAddress', '$brewerCity',  '$brewerState', '$brewerZip', '$brewerCountry', '$brewerPhone1', '$brewerPhone2', '$brewerClubs', '$brewerEmail', '$brewerStaff', '$brewerSteward', '$brewerJudge', '$brewerJudgeID', '$brewerJudgeRank', '$brewerJudgeLikes', '$brewerJudgeDislikes', NULL, NULL, NULL, NULL, NULL, '$brewerAHA', NULL, NULL, NULL);";
		mysqli_real_escape_string($connection,$insertSQL);
		$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));
		
		// If hosted, insert GH as admin user
		if (($row_user['user_name'] != "geoff@zkdigital.com")) {
				
			$gh_user_name = "geoff@zkdigital.com";
			$gh_password = "d9efb18ba2bc4a434ddf85013dbe58f8";
			$random1 = random_generator(7,2);
			$random2 = random_generator(7,2);
			require(CLASSES.'phpass/PasswordHash.php');
			$hasher = new PasswordHash(8, false);
			$hash = $hasher->HashPassword($gh_password);
			
			$query_delete_brewer = sprintf("SELECT id FROM %s WHERE user_name='%s'", $users_db_table, $gh_user_name);
			$delete_brewer = mysqli_query($connection,$query_delete_brewer) or die (mysqli_error($connection));
			$row_delete_brewer = mysqli_fetch_assoc($delete_brewer);
			
			$delete_user = sprintf("DELETE FROM %s WHERE id='%s'", $users_db_table, $row_delete_brewer['id']);
			mysqli_real_escape_string($connection,$delete_user);
			$result = mysqli_query($connection,$delete_user) or die (mysqli_error($connection));
			
			$delete_brewer = sprintf("DELETE FROM %s WHERE uid='%s'", $brewer_db_table, $row_delete_brewer['id']);
			mysqli_real_escape_string($connection,$delete_brewer);
			$result = mysqli_query($connection,$delete_brewer) or die (mysqli_error($connection));
			
			require(CLASSES.'phpass/PasswordHash.php');
			$hasher = new PasswordHash(8, false);
			$hash = $hasher->HashPassword($gh_password);
			
			$updateSQL = sprintf("INSERT INTO `%s` (`id`, `user_name`, `password`, `userLevel`, `userQuestion`, `userQuestionAnswer`,`userCreated`) VALUES (NULL, '%s', '%s', '0', '%s', '%s', NOW());",$gh_user_name,$users_db_table,$hash,$random1,$random2);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			
			$query_gh_admin_user1 = sprintf("SELECT id FROM %s WHERE user_name='%s'",$users_db_table,$gh_user_name);
			$gh_admin_user1 = mysqli_query($connection,$query_gh_admin_user1) or die (mysqli_error($connection));
			$row_gh_admin_user1 = mysqli_fetch_assoc($gh_admin_user1);
			
			$updateSQL = sprintf("INSERT INTO `%s` (`id`, `uid`, `brewerFirstName`, `brewerLastName`, `brewerAddress`, `brewerCity`, `brewerState`, `brewerZip`, `brewerCountry`, `brewerPhone1`, `brewerPhone2`, `brewerClubs`, `brewerEmail`, `brewerStaff`, `brewerSteward`, `brewerJudge`, `brewerJudgeID`, `brewerJudgeRank`, `brewerJudgeLikes`, `brewerJudgeDislikes`, `brewerJudgeLocation`, `brewerStewardLocation`, `brewerJudgeExp`, `brewerJudgeNotes`, `brewerAssignment`, `brewerAHA`) VALUES
	(NULL, '%s', 'Geoff', 'Humphrey', '1234 Main Street', 'Anytown', 'CO', '80126', 'United States', '303-555-5555', '303-555-5555', 'Rock Hoppers', '%s', 'N', 'N', 'N', 'A0000', 'Certified', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 000000);",$brewer_db_table,$row_gh_admin_user1['id'],$gh_user_name);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				
		}
	
	}
	
	// If participants were kept, no need to kill session and re-login - just redirect
	if ($filter == "participant") {
		header(sprintf("Location: %s", $base_url."index.php?section=admin&go=archive&msg=7"));
		exit;
	}
	
	// If no participants were kept except admin users, recreate the session, log the user in and redirect 
	if ($filter == "default") {
		$query_login = "SELECT COUNT(*) as 'count' FROM $users_db_table WHERE user_name = '$user_name' AND password = '$password'";
		$login = mysqli_query($connection,$query_login) or die (mysqli_error($connection));
		$row_login = mysqli_fetch_assoc($login);
	
		// Authenticate the user
		if ($row_login['count'] == 1) {
			
			if ($prefix != "") $prefix_session = md5(rtrim($prefix,"_"));
			else $prefix_session = md5("BCOEM12345");
			
			session_unset();
			session_destroy();
			session_write_close();
			session_regenerate_id(true);
			session_name($prefix_session);
			session_start();
			
			$_SESSION['session_set_'.$prefix_session] = $prefix_session;
			$_SESSION['loginUsername'] = $user_name;
			
			$query_contest_info = sprintf("SELECT * FROM %s WHERE id=1", $prefix."contest_info");
			$contest_info = mysqli_query($connection,$query_contest_info) or die (mysqli_error($connection));
			$row_contest_info = mysqli_fetch_assoc($contest_info); 
		
			// Comp Name
			$_SESSION['contestName'] = $row_contest_info['contestName'];
			$_SESSION['contestLogo'] = $row_contest_info['contestLogo'];
			$_SESSION['contestID'] = $row_contest_info['contestID'];
			 
			// Comp Host Info
			$_SESSION['contestHost'] = $row_contest_info['contestHost'];
			$_SESSION['contestHostWebsite'] = $row_contest_info['contestHostWebsite'];
			$_SESSION['contestShippingName'] = $row_contest_info['contestShippingName'];
			$_SESSION['contestShippingAddress'] = $row_contest_info['contestShippingAddress'];
			
			// Awards Information
			$_SESSION['contestAwardsLocation'] = $row_contest_info['contestAwardsLocation'];
			$_SESSION['contestAwardsLocName'] = $row_contest_info['contestAwardsLocName'];
			$_SESSION['contestAwardsLocTime'] = $row_contest_info['contestAwardsLocTime'];
			
			// Entry Fees
			$_SESSION['contestEntryFee'] = $row_contest_info['contestEntryFee'];
			$_SESSION['contestEntryFee2'] = $row_contest_info['contestEntryFee2'];
			$_SESSION['contestEntryFeePasswordNum'] = $row_contest_info['contestEntryFeePasswordNum'];
			$_SESSION['contestEntryCap'] = $row_contest_info['contestEntryCap'];
			$_SESSION['contestEntryFeeDiscount'] = $row_contest_info['contestEntryFeeDiscount'];
			$_SESSION['contestEntryFeeDiscountNum'] = $row_contest_info['contestEntryFeeDiscountNum'];
				
			$_SESSION['contest_info_general'.$prefix_session] = $prefix_session;
	
			$query_prefs = sprintf("SELECT * FROM %s WHERE id=1", $prefix."preferences");
			$prefs = mysqli_query($connection,$query_prefs) or die (mysqli_error($connection));
			$row_prefs = mysqli_fetch_assoc($prefs);
		
			$_SESSION['prefsTemp'] = $row_prefs['prefsTemp'];
			$_SESSION['prefsWeight1'] = $row_prefs['prefsWeight1'];
			$_SESSION['prefsWeight2'] = $row_prefs['prefsWeight2'];
			$_SESSION['prefsLiquid1'] = $row_prefs['prefsLiquid1'];
			$_SESSION['prefsLiquid2'] = $row_prefs['prefsLiquid2'];
			$_SESSION['prefsPaypal'] = $row_prefs['prefsPaypal'];
			$_SESSION['prefsPaypalAccount'] = $row_prefs['prefsPaypalAccount'];
			$_SESSION['prefsCurrency'] = $row_prefs['prefsCurrency'];
			$_SESSION['prefsCash'] = $row_prefs['prefsCash'];
			$_SESSION['prefsCheck'] = $row_prefs['prefsCheck'];
			$_SESSION['prefsCheckPayee'] = $row_prefs['prefsCheckPayee'];
			$_SESSION['prefsTransFee'] = $row_prefs['prefsTransFee'];
			$_SESSION['prefsSponsors'] = $row_prefs['prefsSponsors'];
			$_SESSION['prefsSponsorLogos'] = $row_prefs['prefsSponsorLogos'];
			$_SESSION['prefsSponsorLogoSize'] = $row_prefs['prefsSponsorLogoSize'];
			$_SESSION['prefsCompLogoSize'] = $row_prefs['prefsCompLogoSize'];
			$_SESSION['prefsDisplayWinners'] = $row_prefs['prefsDisplayWinners'];
			$_SESSION['prefsWinnerDelay'] = $row_prefs['prefsWinnerDelay'];
			$_SESSION['prefsWinnerMethod'] = $row_prefs['prefsWinnerMethod'];
			$_SESSION['prefsDisplaySpecial'] = $row_prefs['prefsDisplaySpecial'];
			$_SESSION['prefsEntryForm'] = $row_prefs['prefsEntryForm'];
			$_SESSION['prefsRecordLimit'] = $row_prefs['prefsRecordLimit'];
			$_SESSION['prefsRecordPaging'] = $row_prefs['prefsRecordPaging'];
			$_SESSION['prefsTheme'] = $row_prefs['prefsTheme'];
			$_SESSION['prefsDateFormat'] = $row_prefs['prefsDateFormat'];
			$_SESSION['prefsContact'] = $row_prefs['prefsContact'];
			$_SESSION['prefsTimeZone'] = $row_prefs['prefsTimeZone'];
			$_SESSION['prefsTimeFormat'] = $row_prefs['prefsTimeFormat'];
			$_SESSION['prefsPayToPrint'] = $row_prefs['prefsPayToPrint'];
			$_SESSION['prefsHideRecipe'] = $row_prefs['prefsHideRecipe'];
			$_SESSION['prefsUseMods'] = $row_prefs['prefsUseMods'];
			$_SESSION['prefsSEF'] = $row_prefs['prefsSEF'];
			$_SESSION['prefsSpecialCharLimit'] = $row_prefs['prefsSpecialCharLimit'];
			$_SESSION['prefsStyleSet'] = $row_prefs['prefsStyleSet'];
		
			$query_judging_prefs = sprintf("SELECT * FROM %s WHERE id='1'", $prefix."judging_preferences");
			$judging_prefs = mysqli_query($connection,$query_judging_prefs) or die (mysqli_error($connection));
			$row_judging_prefs = mysqli_fetch_assoc($judging_prefs);	
			
			$_SESSION['jPrefsQueued'] = $row_judging_prefs['jPrefsQueued'];
			$_SESSION['jPrefsFlightEntries'] = $row_judging_prefs['jPrefsFlightEntries'];
			$_SESSION['jPrefsMaxBOS'] = $row_judging_prefs['jPrefsMaxBOS'];
			$_SESSION['jPrefsRounds'] = $row_judging_prefs['jPrefsRounds'];
			
			// Get counts for common, mostly static items
			$query_sponsor_count = sprintf("SELECT COUNT(*) as 'count' FROM %s", $prefix."sponsors");
			$result_sponsor_count = mysqli_query($connection,$query_sponsor_count) or die (mysqli_error($connection));
			$row_sponsor_count = mysqli_fetch_assoc($result_sponsor_count);
			
			$_SESSION['sponsorCount'] = $row_sponsor_count['count'];
			$_SESSION['prefs'.$prefix_session] = "1";
			$_SESSION['prefix'] = $prefix;
			
			$query_user = sprintf("SELECT * FROM %s WHERE user_name = '%s'", $prefix."users", $_SESSION['loginUsername']);
			$user = mysqli_query($connection,$query_user) or die (mysqli_error($connection));
			$row_user = mysqli_fetch_assoc($user);
			
			$_SESSION['user_id'] = $row_user['id'];
			$_SESSION['userCreated'] = $row_user['userCreated'];
			$_SESSION['user_name'] = $row_user['user_name'];
			$_SESSION['userLevel'] = $row_user['userLevel'];
		
			$query_name = sprintf("SELECT * FROM %s WHERE uid='%s'", $prefix."brewer", $_SESSION['user_id']);
			$name = mysqli_query($connection,$query_name) or die (mysqli_error($connection));
			$row_name = mysqli_fetch_assoc($name);
			
			$_SESSION['brewerID']  = $row_name['id'];
			$_SESSION['brewerFirstName'] = $row_name['brewerFirstName'];
			$_SESSION['brewerLastName'] = $row_name['brewerLastName'];
			$_SESSION['brewerAddress'] = $row_name['brewerAddress'];
			$_SESSION['brewerCity'] = $row_name['brewerCity'];
			$_SESSION['brewerState'] = $row_name['brewerState'];
			$_SESSION['brewerZip'] = $row_name['brewerZip'];
			$_SESSION['brewerCountry'] = $row_name['brewerCountry'];
			$_SESSION['brewerEmail'] = $row_name['brewerEmail'];
			$_SESSION['brewerPhone1'] = $row_name['brewerPhone1'];
			$_SESSION['brewerPhone2'] = $row_name['brewerPhone2'];
			$_SESSION['brewerClubs'] = $row_name['brewerClubs'];
			$_SESSION['brewerStaff'] = $row_name['brewerStaff'];
			$_SESSION['brewerSteward'] = $row_name['brewerSteward'];
			$_SESSION['brewerJudge'] = $row_name['brewerJudge'];
			$_SESSION['brewerJudgeID'] = $row_name['brewerJudgeID'];
			$_SESSION['brewerJudgeRank'] = $row_name['brewerJudgeRank'];
			$_SESSION['brewerJudgeLikes'] = $row_name['brewerJudgeLikes'];
			$_SESSION['brewerJudgeDislikes'] = $row_name['brewerJudgeDislikes'];
			$_SESSION['brewerJudgeLocation'] = $row_name['brewerJudgeLocation'];
			$_SESSION['brewerJudgeMead'] = $row_name['brewerJudgeMead'];
			$_SESSION['brewerStewardLocation'] = $row_name['brewerStewardLocation'];
			$_SESSION['brewerAssignment'] = $row_name['brewerAssignment'];
			$_SESSION['brewerDiscount'] = $row_name['brewerDiscount'];
			$_SESSION['brewerDropOff'] = $row_name['brewerDropOff'];
			$_SESSION['brewerAHA'] = $row_name['brewerAHA'];
			$_SESSION['user_info'.$prefix_session] = "1";
			
			session_write_close();
			header(sprintf("Location: %s", $base_url."index.php?section=admin&go=archive&msg=7"));
			exit;
			}
		
		else {
			// If the username/password combo is incorrect or not found, relocate to the login error page
			header(sprintf("Location: %s", $base_url."index.php?section=login&msg=1"));
			session_destroy();
			exit;
		}
	}
} else { 
	header(sprintf("Location: %s", $base_url."index.php?msg=98"));
	exit;
}
?>