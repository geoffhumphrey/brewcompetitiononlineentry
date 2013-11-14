<?php 
/**
 * Module:      archive.inc.php
 * Description: This module takes the current database tables and renames them 
 *              for archiving purposes so that data is still available.
 */
ob_start();
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', '1');
require('../paths.php');
session_start();

//echo $_SESSION['userLevel'];

if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] == 0)) { 
	
	require(INCLUDES.'scrubber.inc.php');
	require(LIB.'common.lib.php');
	$dbTable = "default";
	require(INCLUDES.'db_tables.inc.php');
	if (NHC) $base_url = "../";
	else $base_url = $base_url;
	
	$suffix = strtr($_POST['archiveSuffix'], $space_remove);
	$suffix = preg_replace("/[^a-zA-Z0-9]+/", "", $suffix);
	mysql_select_db($database, $brewing);
	
	if (NHC) {
	// Place NHC SQL calls below
	
	
	}
	
	else {
	
		$query_suffix_check = sprintf("SELECT COUNT(*) as 'count' FROM $archive_db_table WHERE archiveSuffix = '%s';", $suffix);
		$suffix_check = mysql_query($query_suffix_check, $brewing) or die(mysql_error());
		$row_suffix_check = mysql_fetch_assoc($suffix_check);
		
	} // end if (NHC)
	
	
	if ($row_suffix_check['count'] > 0) { 
		header(sprintf("Location: %s", $base_url."index.php?section=admin&amp;go=archive&amp;msg=6"));
	}
	
	else {
		
		if (NHC) {
			// Place NHC SQL calls below
			
			
		}
		// end if (NHC)
		
		else {
		
			// Gather current User's information from the current "users" AND current "brewer" tables and store in variables
			mysql_select_db($database, $brewing);
			$query_user = sprintf("SELECT * FROM %s WHERE user_name = '%s'", $prefix."users", $_SESSION['loginUsername']);
			$user = mysql_query($query_user, $brewing) or die(mysql_error());
			$row_user = mysql_fetch_assoc($user);
			
				  $user_name = strtr($row_user['user_name'], $html_string);
				  $password = $row_user['password'];
				  $userLevel = $row_user['userLevel'];
				  $userQuestion = strtr($row_user['userQuestion'], $html_string);
				  $userQuestionAnswer = strtr($row_user['userQuestionAnswer'], $html_string);
				  $userCreated = $row_user['userCreated'];
			  
			$query_name = sprintf("SELECT * FROM %s WHERE uid='%s'", $prefix."brewer", $row_user['id']);
			$name = mysql_query($query_name, $brewing) or die(mysql_error());
			$row_name = mysql_fetch_assoc($name);
			
				  $brewerFirstName = strtr($row_name['brewerFirstName'],$html_string);
				  $brewerLastName = strtr($row_name['brewerLastName'],$html_string);
				  $brewerAddress = strtr($row_name['brewerAddress'],$html_string);
				  $brewerCity = strtr($row_name['brewerCity'],$html_string);
				  $brewerState = $row_name['brewerState'];
				  $brewerZip = $row_name['brewerZip'];
				  $brewerCountry = $row_name['brewerCountry'];
				  $brewerPhone1 = $row_name['brewerPhone1'];
				  $brewerPhone2 = $row_name['brewerPhone2'];
				  $brewerClubs = $row_name['brewerClubs'];
				  $brewerEmail = $row_name['brewerEmail'];
				  $brewerNickname = $row_name['brewerNickname'];
				  $brewerSteward = $row_name['brewerSteward'];
				  $brewerJudge = $row_name['brewerJudge'];
				  $brewerJudgeID = $row_name['brewerJudgeID'];
				  $brewerJudgeRank = $row_name['brewerJudgeRank'];
				  $brewerJudgeLikes = $row_name['brewerJudgeLikes'];
				  $brewerJudgeDislikes = $row_name['brewerJudgeDislikes'];
				  $brewerAHA = $row_name['brewerAHA'];
			
			// Second, rename current tables and recreate new ones.
			// For hosted accounts, limit the table creation to the users, brewer, brewing, judging_tables, judging_assignments, judging_scores, judging_scores_bos, and style_types tables
			if (HOSTED) $tables_array = array($users_db_table, $brewer_db_table, $brewing_db_table, $judging_assignments_db_table, $judging_scores_db_table, $judging_tables_db_table, $judging_scores_bos_db_table, $style_types_db_table);
			else $tables_array = array($users_db_table, $brewer_db_table, $brewing_db_table, $sponsors_db_table, $judging_assignments_db_table, $judging_flights_db_table, $judging_scores_db_table, $judging_tables_db_table, $style_types_db_table, $special_best_data_db_table, $special_best_info_db_table, $judging_scores_bos_db_table);
			
			foreach ($tables_array as $table) { 
				$updateSQL = "RENAME TABLE ".$table." TO ".$table."_".$suffix.";";
				mysql_real_escape_string($updateSQL);
				//echo "<p>".$updateSQL."</p>";
				$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
				
				$updateSQL = "CREATE TABLE ".$table." LIKE ".$table."_".$suffix.";";
				mysql_real_escape_string($updateSQL);
				//echo "<p>".$updateSQL."</p>";
				$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			}
			
			$insertSQL = "
			INSERT INTO $style_types_db_table (id, styleTypeName, styleTypeOwn, styleTypeBOS, styleTypeBOSMethod) VALUES
				(1, 'Beer', 'bcoe', 'Y', 1),
				(2, 'Cider', 'bcoe', 'Y', 3),
				(3, 'Mead', 'bcoe', 'Y', 3)
			";
			mysql_real_escape_string($insertSQL);
			$result = mysql_query($insertSQL, $brewing) or die(mysql_error());
			
			// Insert current user's info into new "users" and "brewer" table
			$insertSQL = "INSERT INTO $users_db_table (
				id, 
				user_name, 
				password, 
				userLevel, 
				userQuestion, 
				userQuestionAnswer,
				userCreated
			) 
			VALUES 
			(
				'1',
				'$user_name', 
				'$password', 
				'0', 
				'$userQuestion', 
				'$userQuestionAnswer', 
				NOW());";
			//echo "<p>".$insertSQL."</p>";
			mysql_real_escape_string($insertSQL);
			$result = mysql_query($insertSQL, $brewing) or die(mysql_error());
			
			$insertSQL = "
			INSERT INTO $brewer_db_table (
				id,
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
				brewerNickname,
				brewerSteward,
				brewerJudge,
				brewerJudgeID,
				brewerJudgeRank,
				brewerJudgeLikes,
				brewerJudgeDislikes,
				brewerJudgeLocation,
				brewerStewardLocation,
				brewerJudgeAssignedLocation,
				brewerStewardAssignedLocation,
				brewerAssignment,
				brewerAHA,
				brewerDiscount,
				brewerJudgeBOS,
				brewerDropOff
			) 
			VALUES (
				NULL, 
				'1', 
				'$brewerFirstName', 
				'$brewerLastName', 
				'$brewerAddress', 
				'$brewerCity', 
				'$brewerState', 
				'$brewerZip', 
				'$brewerCountry', 
				'$brewerPhone1', 
				'$brewerPhone2', 
				'$brewerClubs', 
				'$brewerEmail', 
				'$brewerNickname', 
				'$brewerSteward', 
				'$brewerJudge', 
				'$brewerJudgeID', 
				'$brewerJudgeRank', 
				'$brewerJudgeLikes', 
				'$brewerJudgeDislikes',
				NULL,
				NULL,
				NULL,
				NULL,
				NULL,
				'$brewerAHA',
				NULL,
				NULL,
				NULL
			);";
			//echo "<p>".$insertSQL."</p>";
			mysql_real_escape_string($insertSQL);
			$result = mysql_query($insertSQL, $brewing) or die(mysql_error());
			
			
			// If hosted, insert GH as admin user
			if (HOSTED) {
				
				$gh_user_name = "geoff@zkdigital.com";
				$gh_password = "d9efb18ba2bc4a434ddf85013dbe58f8";
				
				require(CLASSES.'phpass/PasswordHash.php');
				$hasher = new PasswordHash(8, false);
				$hash = $hasher->HashPassword($gh_password);
				
				$updateSQL = sprintf("INSERT INTO `%s` (`id`, `user_name`, `password`, `userLevel`, `userQuestion`, `userQuestionAnswer`,`userCreated`) VALUES
		(NULL, 'geoff@zkdigital.com', '%s', '0', 'What was your high school''s mascot?', 'spartan', NOW());", $users_db_table,$hash);
				mysql_real_escape_string($updateSQL);
				$result = mysql_query($updateSQL, $brewing);
				
				$query_gh_admin_user1 = sprintf("SELECT id FROM %s WHERE user_name='%s'",$users_db_table,$gh_user_name);
				$gh_admin_user1 = mysql_query($query_gh_admin_user1, $brewing);
				$row_gh_admin_user1 = mysql_fetch_assoc($gh_admin_user1);
				
				$updateSQL = sprintf("INSERT INTO `%s` (`id`, `uid`, `brewerFirstName`, `brewerLastName`, `brewerAddress`, `brewerCity`, `brewerState`, `brewerZip`, `brewerCountry`, `brewerPhone1`, `brewerPhone2`, `brewerClubs`, `brewerEmail`, `brewerNickname`, `brewerSteward`, `brewerJudge`, `brewerJudgeID`, `brewerJudgeRank`, `brewerJudgeLikes`, `brewerJudgeDislikes`, `brewerJudgeLocation`, `brewerStewardLocation`, `brewerJudgeAssignedLocation`, `brewerStewardAssignedLocation`, `brewerAssignment`, `brewerAHA`) VALUES
		(NULL, '%s', 'Geoff', 'Humphrey', '1234 Main Street', 'Anytown', 'CO', '80126', 'United States', '303-555-5555', '303-555-5555', 'Rock Hoppers', 'geoff@zkdigital.com', NULL, 'N', 'N', 'A0000', 'Certified', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 000000);",$brewer_db_table,$row_gh_admin_user1['id']);
				mysql_real_escape_string($updateSQL);
				$result = mysql_query($updateSQL, $brewing);
				
			}
			
			// Insert a new record into the "archive" table containing the newly created archives names (allows access to archived tables)
			$insertSQL = sprintf("INSERT INTO $archive_db_table (archiveSuffix) VALUES (%s);", "'".$suffix."'");
			//echo "<p>".$insertSQL."</p>";
			mysql_real_escape_string($insertSQL);
			$result = mysql_query($insertSQL, $brewing) or die(mysql_error());
			
			// Last, log the user in and redirect 
			$query_login = "SELECT COUNT(*) as 'count' FROM $users_db_table WHERE user_name = '$user_name' AND password = '$password'";
			$login = mysql_query($query_login, $brewing) or die(mysql_error());
			$row_login = mysql_fetch_assoc($login);
			
				// Authenticate the user
				if ($row_login['count'] == 1) {
					
					// -------------------------------------------------------------------------------
					// MUST CHANGE ALL TO MATCH common.db.php
					// -------------------------------------------------------------------------------
					
					if ($prefix != "") $prefix_session = md5(rtrim($prefix,"_"));
					else $prefix_session = md5("BCOEM12345");
					
					session_unset();
					session_destroy();
					session_write_close();
					session_regenerate_id(true);
					session_start();
					$_SESSION['session_set_'.$prefix_session] = $prefix_session;
							
					$_SESSION['loginUsername'] = $user_name;
					
					$query_contest_info = sprintf("SELECT * FROM %s WHERE id=1", $prefix."contest_info");
					$contest_info = mysql_query($query_contest_info, $brewing) or die(mysql_error());
					$row_contest_info = mysql_fetch_assoc($contest_info); 
				
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
					//$_SESSION['contestEntryFeePassword'] = $row_contest_info['contestEntryFeePassword'];
					$_SESSION['contestEntryFeePasswordNum'] = $row_contest_info['contestEntryFeePasswordNum'];
					$_SESSION['contestEntryCap'] = $row_contest_info['contestEntryCap'];
					$_SESSION['contestEntryFeeDiscount'] = $row_contest_info['contestEntryFeeDiscount'];
					$_SESSION['contestEntryFeeDiscountNum'] = $row_contest_info['contestEntryFeeDiscountNum'];
						
					$_SESSION['contest_info_general'.$prefix_session] = $prefix_session;
			
					$query_prefs = sprintf("SELECT * FROM %s WHERE id=1", $prefix."preferences");
					$prefs = mysql_query($query_prefs, $brewing) or die(mysql_error());
					$row_prefs = mysql_fetch_assoc($prefs);
					$totalRows_prefs = mysql_num_rows($prefs);
				
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
					// $_SESSION['prefsGoogle'] = $row_prefs['prefsGoogle'];
					// $_SESSION['prefsGoogleAccount'] = $row_prefs['prefsGoogleAccount'];
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
					$_SESSION['prefsCompOrg'] = $row_prefs['prefsCompOrg'];
					$_SESSION['prefsTheme'] = $row_prefs['prefsTheme'];
					$_SESSION['prefsDateFormat'] = $row_prefs['prefsDateFormat'];
					$_SESSION['prefsContact'] = $row_prefs['prefsContact'];
					$_SESSION['prefsTimeZone'] = $row_prefs['prefsTimeZone'];
					$_SESSION['prefsTimeFormat'] = $row_prefs['prefsTimeFormat'];
					//$row_limits['prefsEntryLimit'] = $row_prefs['prefsEntryLimit'];
					//$_SESSION['prefsUserEntryLimit'] = $row_prefs['prefsUserEntryLimit'];
					//$_SESSION['prefsUserSubCatLimit'] = $row_prefs['prefsUserSubCatLimit'];
					$_SESSION['prefsPayToPrint'] = $row_prefs['prefsPayToPrint'];
					$_SESSION['prefsHideRecipe'] = $row_prefs['prefsHideRecipe'];
					$_SESSION['prefsUseMods'] = $row_prefs['prefsUseMods'];
					//$_SESSION['prefsUSCLEx'] = $row_prefs['prefsUSCLEx'];
					//$_SESSION['prefsUSCLExLimit'] = $row_prefs['prefsUSCLExLimit'];
					$_SESSION['prefsSEF'] = $row_prefs['prefsSEF'];
					$_SESSION['prefsSpecialCharLimit'] = $row_prefs['prefsSpecialCharLimit'];
					
				
					$query_judging_prefs = sprintf("SELECT * FROM %s WHERE id='1'", $prefix."judging_preferences");
					$judging_prefs = mysql_query($query_judging_prefs, $brewing) or die(mysql_error());
					$row_judging_prefs = mysql_fetch_assoc($judging_prefs);	
					
					$_SESSION['jPrefsQueued'] = $row_judging_prefs['jPrefsQueued'];
					$_SESSION['jPrefsFlightEntries'] = $row_judging_prefs['jPrefsFlightEntries'];
					$_SESSION['jPrefsMaxBOS'] = $row_judging_prefs['jPrefsMaxBOS'];
					$_SESSION['jPrefsRounds'] = $row_judging_prefs['jPrefsRounds'];
					
					// Get counts for common, mostly static items
					$query_sponsor_count = sprintf("SELECT COUNT(*) as 'count' FROM %s", $prefix."sponsors");
					$result_sponsor_count = mysql_query($query_sponsor_count, $brewing) or die(mysql_error());
					$row_sponsor_count = mysql_fetch_assoc($result_sponsor_count);
					$_SESSION['sponsorCount'] = $row_sponsor_count['count'];
					
					$_SESSION['prefs'.$prefix_session] = "1";
					$_SESSION['prefix'] = $prefix;
					
					$query_user = sprintf("SELECT * FROM %s WHERE user_name = '%s'", $prefix."users", $_SESSION['loginUsername']);
					$user = mysql_query($query_user, $brewing) or die(mysql_error());
					$row_user = mysql_fetch_assoc($user);
					$totalRows_user = mysql_num_rows($user);
					
					$_SESSION['user_id'] = $row_user['id'];
					$_SESSION['userCreated'] = $row_user['userCreated'];
					$_SESSION['user_name'] = $row_user['user_name'];
					$_SESSION['userLevel'] = $row_user['userLevel'];
				
					$query_name = sprintf("SELECT * FROM %s WHERE uid='%s'", $prefix."brewer", $_SESSION['user_id']);
					$name = mysql_query($query_name, $brewing) or die(mysql_error());
					$row_name = mysql_fetch_assoc($name);
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
					$_SESSION['brewerNickname'] = $row_name['brewerNickname'];
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
		
		} // end else NHC
		
	} // end else if ($row_suffix_check['count'] > 0)
	
} // end if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] == 0))
	
else echo "<p>Not available.</p>";
?>
