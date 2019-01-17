<?php
if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] == 0))) {
	session_name($prefix_session);
	session_start();

	require(INCLUDES.'scrubber.inc.php');
	require(INCLUDES.'db_tables.inc.php');

	// Instantiate HTMLPurifier
	require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
	$config_html_purifier = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config_html_purifier);



	$dbTable = "default";

	$suffix = strtr($_POST['archiveSuffix'], $space_remove);
	$suffix = preg_replace("/[^a-zA-Z0-9]+/", "", $suffix);
	$suffix = sterilize($suffix);

	$query_suffix_check = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE archiveSuffix = '%s';", $archive_db_table, $suffix);
	$suffix_check = mysqli_query($connection,$query_suffix_check) or die (mysqli_error($connection));
	$row_suffix_check = mysqli_fetch_assoc($suffix_check);

	if ($row_suffix_check['count'] > 0) {
			$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&go=archive&msg=6");
	}

	else {

		//Check if any documents are in the user_docs folder

		if ((isset($_POST['keepScoresheets'])) && (!is_dir_empty(USER_DOCS))) {

			// Define directories and run the move function
			$src = USER_DOCS;
			$dest = USER_DOCS.$suffix;
			rmove($src, $dest);

		}

		if (!isset($_POST['keepScoresheets'])) {
			// Erase all files in the user_docs directory
			rdelete(USER_DOCS);
		}

		if (!isset($_POST['keepParticipants'])) {

			// Gather current User's information from the current "users" AND current "brewer" tables and store in variables
			$query_user = sprintf("SELECT * FROM %s WHERE user_name = '%s'", $prefix."users", $_SESSION['loginUsername']);
			$user = mysqli_query($connection,$query_user) or die (mysqli_error($connection));
			$row_user = mysqli_fetch_assoc($user);

			$user_name = sterilize($row_user['user_name']);
			$password = $row_user['password'];
			$userLevel = $row_user['userLevel'];
			$userQuestion = $purifier->purify($row_user['userQuestion']);
			$userQuestionAnswer = $purifier->purify($row_user['userQuestionAnswer']);
			$userCreated = $row_user['userCreated'];

			$query_name = sprintf("SELECT * FROM %s WHERE uid='%s'", $prefix."brewer", $row_user['id']);
			$name = mysqli_query($connection,$query_name) or die (mysqli_error($connection));
			$row_name = mysqli_fetch_assoc($name);

			$brewerFirstName = $purifier->purify($row_name['brewerFirstName']);
			$brewerLastName = $purifier->purify($row_name['brewerLastName']);
			$brewerAddress = $purifier->purify($row_name['brewerAddress']);
			$brewerCity = $purifier->purify($row_name['brewerCity']);
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
			$brewerBreweryName = $row_name['brewerBreweryName'];

		} // end if (!isset($_POST['keepParticipants']))

		// Rename current tables and recreate new ones based upon user input
		$tables_array = array($brewing_db_table, $judging_assignments_db_table, $judging_flights_db_table, $judging_scores_db_table, $judging_scores_bos_db_table, $judging_tables_db_table, $staff_db_table);

		if (!isset($_POST['keepParticipants'])) {
			$tables_array[] = $users_db_table;
			$tables_array[] = $brewer_db_table;
		}

		if (!isset($_POST['keepSpecialBest'])) {
			$tables_array[] = $special_best_info_db_table;
			$tables_array[] = $special_best_data_db_table;
		}

		if (!isset($_POST['keepStyleTypes'])) $tables_array[] = $style_types_db_table;

		$truncate_tables_array = array();
		if (!isset($_POST['keepDropoff'])) $truncate_tables_array[] = $drop_off_db_table;
		if (!isset($_POST['keepSponsors'])) $truncate_tables_array[] = $sponsors_db_table;
		if (!isset($_POST['keepLocations'])) $truncate_tables_array[] = $judging_locations_db_table;

		$keep_participants = FALSE;

		if (isset($_POST['keepParticipants'])) {
			$updateSQL = "CREATE TABLE ".$prefix."users_".$suffix." LIKE ".$users_db_table.";";
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			$updateSQL = "INSERT INTO ".$prefix."users_".$suffix." SELECT * FROM ".$users_db_table.";";
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			$updateSQL = "CREATE TABLE ".$prefix."brewer_".$suffix." LIKE ".$brewer_db_table.";";
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			$updateSQL = "INSERT INTO ".$prefix."brewer_".$suffix." SELECT * FROM ".$brewer_db_table.";";
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			$keep_participants = TRUE;
		}

		if (isset($_POST['keepSpecialBest'])) {
			$updateSQL = "CREATE TABLE ".$special_best_info_db_table."_".$suffix." LIKE ".$special_best_info_db_table.";";
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			$updateSQL = "INSERT INTO ".$special_best_info_db_table."_".$suffix." SELECT * FROM ".$special_best_info_db_table.";";
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			$updateSQL = "RENAME TABLE ".$special_best_data_db_table." TO ".$special_best_data_db_table."_".$suffix.";";
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			$updateSQL = "CREATE TABLE ".$special_best_data_db_table." LIKE ".$special_best_data_db_table."_".$suffix.";";
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		}

		if (isset($_POST['keepStyleTypes'])) {
			$updateSQL = "CREATE TABLE ".$style_types_db_table."_".$suffix." LIKE ".$style_types_db_table.";";
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			$updateSQL = "INSERT INTO ".$style_types_db_table."_".$suffix." SELECT * FROM ".$style_types_db_table.";";
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		}

		foreach ($tables_array as $table) {

			$updateSQL = "RENAME TABLE ".$table." TO ".$table."_".$suffix.";";
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			$updateSQL = "CREATE TABLE ".$table." LIKE ".$table."_".$suffix.";";
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		}

		foreach ($truncate_tables_array as $table) {

			$updateSQL = "TRUNCATE ".$table.";";
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		}

		if (!isset($_POST['keepStyleTypes'])) {

			$insertSQL = "INSERT INTO $style_types_db_table (id, styleTypeName, styleTypeOwn, styleTypeBOS, styleTypeBOSMethod) VALUES (1, 'Beer', 'bcoe', 'Y', 1), (2, 'Cider', 'bcoe', 'Y', 3), (3, 'Mead', 'bcoe', 'Y', 3)";
			mysqli_real_escape_string($connection,$insertSQL);
			$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));

		}

		if (!isset($_POST['keepParticipants']))  {

			// Insert current user's info into new "users" and "brewer" table
			$insertSQL = "INSERT INTO $users_db_table (id, user_name, password,	userLevel, userQuestion, userQuestionAnswer, userCreated) VALUES ('1', '$user_name', '$password', '0', '$userQuestion', '$userQuestionAnswer', NOW());";
			mysqli_real_escape_string($connection,$insertSQL);
			$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));

			$insertSQL = "INSERT INTO $brewer_db_table (id, uid, brewerFirstName, brewerLastName, brewerAddress, brewerCity, brewerState, brewerZip, brewerCountry, brewerPhone1, brewerPhone2, brewerClubs, brewerEmail, brewerStaff, brewerSteward, brewerJudge, brewerJudgeID, brewerJudgeRank, brewerJudgeLikes, brewerJudgeDislikes, brewerJudgeLocation, brewerStewardLocation, brewerJudgeExp, brewerJudgeNotes, brewerAssignment, brewerAHA, brewerDiscount, brewerDropOff, brewerBreweryName) VALUES (NULL, '1', '$brewerFirstName', '$brewerLastName', '$brewerAddress', '$brewerCity',  '$brewerState', '$brewerZip', '$brewerCountry', '$brewerPhone1', '$brewerPhone2', '$brewerClubs', '$brewerEmail', '$brewerStaff', '$brewerSteward', '$brewerJudge', '$brewerJudgeID', '$brewerJudgeRank', '$brewerJudgeLikes', '$brewerJudgeDislikes', NULL, NULL, NULL, NULL, NULL, '$brewerAHA', NULL, NULL,'$brewerBreweryName');";
			mysqli_real_escape_string($connection,$insertSQL);
			$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));

		}

		// Insert a new record into the "archive" table containing the newly created archives names (allows access to archived tables)

		$styleSet = $_SESSION['prefsStyleSet'];

		$insertSQL = sprintf("INSERT INTO %s (archiveSuffix,archiveProEdition,archiveStyleSet) VALUES ('%s','%s','%s');",$archive_db_table,$suffix,$_SESSION['prefsProEdition'],$styleSet);
		mysqli_real_escape_string($connection,$insertSQL);
		$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));

		// If participants were kept, no need to kill session and re-login - just redirect
		if ($keep_participants) {

			// First, clear judging preferences
			if (!SINGLE) {
				$updateSQL = sprintf("UPDATE %s SET brewerJudge='N',brewerSteward='N',brewerJudgeLikes=NULL,brewerJudgeDislikes=NULL,brewerJudgeLocation=NULL,brewerStewardLocation=NULL",$brewer_db_table);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			}

			$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&go=archive&msg=7");
		}

		// If no participants were kept except admin users, log the user in and redirect
		else {

			// First, clear judging preferences for remaining users
			if (!SINGLE) {
				$updateSQL = sprintf("UPDATE %s SET brewerJudge='N',brewerSteward='N',brewerJudgeLikes=NULL,brewerJudgeDislikes=NULL,brewerJudgeLocation=NULL,brewerStewardLocation=NULL",$brewer_db_table);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			}

			$query_login = "SELECT COUNT(*) as 'count' FROM $users_db_table WHERE user_name = '$user_name' AND password = '$password'";
			$login = mysqli_query($connection,$query_login) or die (mysqli_error($connection));
			$row_login = mysqli_fetch_assoc($login);

			// Authenticate the user
			if ($row_login['count'] == 1) {

				if ($prefix != "") $prefix_session = md5(rtrim($prefix,"_"));
				else $prefix_session = md5("BCOEM12345");

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

				$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&go=archive&msg=7");
			}

			else {
				// If the username/password combo is incorrect or not found, relocate to the login error page
				$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=login&msg=1");
				session_destroy();
			}
		}

	} // end else

} // end if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] == 0))
else echo "Not allowed."
?>