<?php
declare(strict_types=1);

if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] == 0))) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

	require (INCLUDES.'scrubber.inc.php');
	require (INCLUDES.'db_tables.inc.php');
	
	$eval_db_exist = FALSE;
	if (check_setup($prefix."evaluation",$database)) $eval_db_exist = TRUE;
	
	// Instantiate HTMLPurifier
	require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
	$config_html_purifier = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config_html_purifier);

	$dbTable = "default";

	$suffix = $purifier->purify($_POST['archiveSuffix']);
	$suffix = strtr($suffix, $space_remove);
	$suffix = preg_replace("/[^a-zA-Z0-9]+/", "", $suffix);
	$suffix = sterilize($suffix);

	// Rename current tables and recreate new ones based upon user input
	$tables_array = array($brewing_db_table, $judging_assignments_db_table, $judging_flights_db_table, $judging_scores_db_table, $judging_scores_bos_db_table, $judging_tables_db_table, $staff_db_table);

	// Only rename evaluation away (emptying the live table) when it's not being kept - when
	// it is, the block below copies it into the archive instead, leaving the live table alone,
	// matching how keepSpecialBest/keepStyleTypes handle the same choice.
	if (($eval_db_exist) && (!isset($_POST['keepEvaluations']))) $tables_array[] = $prefix."evaluation";

	if ($go == "add") {

		$db_conn->where("archiveSuffix", $suffix);
		$row_suffix_check = $db_conn->getOne($archive_db_table, "COUNT(*) as 'count'");

		if ($row_suffix_check['count'] > 0) {
			$redirect = $base_url."index.php?section=admin&go=archive&msg=6";
			$redirect = prep_redirect_link($redirect);
			$redirect_go_to = sprintf("Location: %s", $redirect);
			header($redirect_go_to);
			exit();
		}
		
		/**
		 * Check if any documents are in the user_docs folder
		 * If so, create a directory with the suffix name.
		 * Move the files to that folder. 
		 * Erase all files with certain mime types in the user_docs directory.
		 */
		
		if (!is_dir_empty(USER_DOCS)) {

			// Define directories and run the move function
			$src = USER_DOCS;
			$dest = USER_DOCS.$suffix;
			rmove($src, $dest);
			
			// Run the delete function
			rdelete(USER_DOCS,"");

		}

		// Clear BJCP ID from "contest_info"
		$update_table = $prefix."contest_info";
		$data = array(
			'contestID' => NULL
		);
		$db_conn->where ('id', 1);
		$result = $db_conn->update ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		// Clear out all participants (except for current user)
		if (!isset($_POST['keepParticipants'])) {

			// Gather current User's information from the current "users" AND current "brewer" tables and store in variables
			// $_SESSION['loginUsername'] and the fetched user_name are already the canonical
			// stored value - sterilize() would HTML-encode them and break this lookup for any
			// address containing & < > " ' or non-ASCII characters.
			$db_conn->where("user_name", $_SESSION['loginUsername']);
			$row_user = $db_conn->getOne($prefix."users");

			$user_name = $row_user['user_name'];
			$user_password = $row_user['password'];
			$userLevel = $row_user['userLevel'];
			$userQuestion = $purifier->purify($row_user['userQuestion']);
			$userQuestionAnswer = $purifier->purify($row_user['userQuestionAnswer']);
			$userCreated = $row_user['userCreated'];

			$db_conn->where("uid", sterilize($row_user['id']));
			$row_name = $db_conn->getOne($prefix."brewer");

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
			$brewerJudgeMead = $row_name['brewerJudgeMead'];
			$brewerJudgeCider = $row_name['brewerJudgeCider'];
			$brewerJudgeLocation = $row_name['brewerJudgeLocation'];
			$brewerStewardLocation = $row_name['brewerStewardLocation'];
			$brewerJudgeExp = $row_name['brewerJudgeExp'];
			$brewerAHA = $row_name['brewerAHA'];
			$brewerBreweryName = $row_name['brewerBreweryName'];
			$brewerAssignment = $row_name['brewerAssignment'];

		} // end if (!isset($_POST['keepParticipants']))

		if (!isset($_POST['keepParticipants'])) {
			$tables_array[] = $users_db_table;
			$tables_array[] = $brewer_db_table;
		}

		if (!isset($_POST['keepSpecialBest'])) {
			$tables_array[] = $special_best_info_db_table;
			$tables_array[] = $special_best_data_db_table;
		}

		if (!isset($_POST['keepSponsors'])) $tables_array[] = $sponsors_db_table;

		$truncate_tables_array = array();
		if (!isset($_POST['keepDropoff'])) $truncate_tables_array[] = $drop_off_db_table;
		if (!isset($_POST['keepSponsors'])) $truncate_tables_array[] = $sponsors_db_table;
		if (!isset($_POST['keepLocations'])) $truncate_tables_array[] = $judging_locations_db_table;
		// evaluation isn't added here: when not kept, it's already emptied by the rename+recreate
		// in $tables_array above (truncating an already-empty table would be a no-op); when kept,
		// the block below leaves the live table alone entirely.

		$keep_participants = FALSE;

		if (isset($_POST['keepParticipants'])) {

			$sql = "CREATE TABLE ".$prefix."users_".$suffix." LIKE ".$users_db_table.";";
			$db_conn->rawQuery($sql);
			if ($db_conn->getLastErrno() !== 0) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$sql = "INSERT INTO ".$prefix."users_".$suffix." SELECT * FROM ".$users_db_table.";";
			$db_conn->rawQuery($sql);
			if ($db_conn->getLastErrno() !== 0) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$sql = "CREATE TABLE ".$prefix."brewer_".$suffix." LIKE ".$brewer_db_table.";";
			$db_conn->rawQuery($sql);
			if ($db_conn->getLastErrno() !== 0) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$sql = "INSERT INTO ".$prefix."brewer_".$suffix." SELECT * FROM ".$brewer_db_table.";";
			$db_conn->rawQuery($sql);
			if ($db_conn->getLastErrno() !== 0) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$keep_participants = TRUE;
		}

		if (isset($_POST['keepSpecialBest'])) {
			
			$sql = "CREATE TABLE ".$special_best_info_db_table."_".$suffix." LIKE ".$special_best_info_db_table.";";
			$db_conn->rawQuery($sql);
			if ($db_conn->getLastErrno() !== 0) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$sql = "INSERT INTO ".$special_best_info_db_table."_".$suffix." SELECT * FROM ".$special_best_info_db_table.";";
			$db_conn->rawQuery($sql);
			if ($db_conn->getLastErrno() !== 0) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$sql = "RENAME TABLE ".$special_best_data_db_table." TO ".$special_best_data_db_table."_".$suffix.";";
			$db_conn->rawQuery($sql);
			if ($db_conn->getLastErrno() !== 0) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$sql = "CREATE TABLE ".$special_best_data_db_table." LIKE ".$special_best_data_db_table."_".$suffix.";";
			$db_conn->rawQuery($sql);
			if ($db_conn->getLastErrno() !== 0) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

		}

		if (isset($_POST['keepStyleTypes'])) {

			$sql = "CREATE TABLE ".$style_types_db_table."_".$suffix." LIKE ".$style_types_db_table.";";
			$db_conn->rawQuery($sql);
			if ($db_conn->getLastErrno() !== 0) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$sql = "INSERT INTO ".$style_types_db_table."_".$suffix." SELECT * FROM ".$style_types_db_table.";";
			$db_conn->rawQuery($sql);
			if ($db_conn->getLastErrno() !== 0) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

		}
		
		if (($eval_db_exist) && (isset($_POST['keepEvaluations']))) {

			$evaluation_table = $prefix."evaluation";

			$sql = sprintf("CREATE TABLE %s LIKE %s", $evaluation_table."_".$suffix, $evaluation_table);
			$db_conn->rawQuery($sql);
			if ($db_conn->getLastErrno() !== 0) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$sql = sprintf("INSERT INTO %s SELECT * FROM %s", $evaluation_table."_".$suffix, $evaluation_table);
			$db_conn->rawQuery($sql);
			if ($db_conn->getLastErrno() !== 0) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

		}

		foreach ($tables_array as $table) {

			$sql = sprintf("RENAME TABLE %s TO %s", $table, $table."_".$suffix);
			$db_conn->rawQuery($sql);
			if ($db_conn->getLastErrno() !== 0) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$sql = sprintf("CREATE TABLE %s LIKE %s", $table, $table."_".$suffix);
			$db_conn->rawQuery($sql);
			if ($db_conn->getLastErrno() !== 0) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

		}

		foreach ($truncate_tables_array as $table) {
			$sql = sprintf("TRUNCATE %s", $table);
			$db_conn->rawQuery($sql);
			if ($db_conn->getLastErrno() !== 0) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}
		}

		if (!isset($_POST['keepStyleTypes'])) {

			$sql = "CREATE TABLE ".$style_types_db_table."_".$suffix." LIKE ".$style_types_db_table.";";
			$db_conn->rawQuery($sql);
			if ($db_conn->getLastErrno() !== 0) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$sql = "INSERT INTO ".$style_types_db_table."_".$suffix." SELECT * FROM ".$style_types_db_table.";";
			$db_conn->rawQuery($sql);
			if ($db_conn->getLastErrno() !== 0) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$update_table = $prefix."style_types";
			$db_conn->where ('id', 16, ">=");
			$result = $db_conn->delete ($update_table);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

		}

		if (!isset($_POST['keepParticipants']))  {

			$update_table = $prefix."users";
			$data = array(
				'id' => 1, 
				'user_name' => $user_name, 
				'password' => $user_password,	
				'userLevel' => $userLevel, 
				'userQuestion' => $userQuestion, 
				'userQuestionAnswer' => $userQuestionAnswer, 
				'userCreated' => date('Y-m-d H:i:s', time())
			);
			$result = $db_conn->insert ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$update_table = $prefix."brewer";
			$data = array(
				'id' => '1',
				'uid' => '1',
				'brewerFirstName' => blank_to_null($brewerFirstName),
				'brewerLastName' => blank_to_null($brewerLastName),
				'brewerAddress' => blank_to_null($brewerAddress),
				'brewerCity' => blank_to_null($brewerCity),
				'brewerState' => blank_to_null($brewerState),
				'brewerZip' => blank_to_null($brewerZip),
				'brewerCountry' => blank_to_null($brewerCountry),
				'brewerPhone1' => blank_to_null($brewerPhone1),
				'brewerPhone2' => blank_to_null($brewerPhone2),
				'brewerClubs' => blank_to_null($brewerClubs),
				'brewerEmail' => blank_to_null($brewerEmail),
				'brewerStaff' => blank_to_null($brewerStaff),
				'brewerSteward' => blank_to_null($brewerSteward),
				'brewerJudge' => blank_to_null($brewerJudge),
				'brewerJudgeID' => blank_to_null($brewerJudgeID),
				'brewerJudgeMead' => blank_to_null($brewerJudgeMead),
				'brewerJudgeCider' => blank_to_null($brewerJudgeCider),
				'brewerJudgeRank' => blank_to_null($brewerJudgeRank),
				'brewerJudgeLikes' => blank_to_null($brewerJudgeLikes),
				'brewerJudgeDislikes' => blank_to_null($brewerJudgeDislikes),
				'brewerJudgeLocation' => blank_to_null($brewerJudgeLocation),
				'brewerStewardLocation' => blank_to_null($brewerStewardLocation),
				'brewerJudgeExp' => blank_to_null($brewerJudgeExp),
				'brewerJudgeNotes' => NULL,
				'brewerAssignment' => $brewerAssignment,
				'brewerJudgeWaiver' => 'Y',
				'brewerAHA' => blank_to_null($brewerAHA),
				'brewerDiscount' => NULL,
				'brewerProAm' => '0',
				'brewerDropOff' => '999',
				'brewerBreweryName' => blank_to_null($brewerBreweryName),
				'brewerBreweryInfo' => NULL
			);
			$result = $db_conn->insert ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

		}

		// Insert a new record into the "archive" table containing the newly created archives names (allows access to archived tables)
		$styleSet = $_SESSION['prefsStyleSet'];

		$update_table = $prefix."archive";
		$data = array(
			'archiveSuffix' => $suffix,
			'archiveProEdition' => blank_to_null($_SESSION['prefsProEdition']),
			'archiveStyleSet' => blank_to_null($styleSet),
			'archiveScoresheet' => blank_to_null($_SESSION['prefsDisplaySpecial']),
			'archiveWinnerMethod' => blank_to_null($_SESSION['prefsWinnerMethod']),
			'archiveDisplayWinners' => blank_to_null($_SESSION['prefsDisplayWinners'])
		);
		$result = $db_conn->insert ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		// If participants were kept, no need to kill session and re-login - just redirect
		if ($keep_participants) {

			// First, clear judging preferences and discounts
			$update_table = $prefix."brewer";
			$data = array(
				'brewerJudge' => 'N',
				'brewerSteward' => 'N',
				'brewerJudgeLocation' => NULL,
				'brewerStewardLocation' => NULL,
				'brewerDropOff' => '999',
				'brewerDiscount' => NULL
			);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&go=archive&msg=7");
		}

		// If no participants were kept except admin users, log the user in and redirect
		else {

			// First, clear judging preferences and discounts for remaining users
				
			$update_table = $prefix."brewer";
			$data = array(
				'brewerJudge' => 'N',
				'brewerSteward' => 'N',
				'brewerJudgeLocation' => NULL,
				'brewerStewardLocation' => NULL,
				'brewerDropOff' => '999',
				'brewerDiscount' => NULL
			);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$db_conn->where("user_name", $user_name);
			$db_conn->where("password", $user_password);
			$row_login = $db_conn->getOne($users_db_table, "COUNT(*) as 'count'");

			// Authenticate the user
			if ($row_login['count'] == 1) {

				if ($prefix != "") $prefix_session = md5(rtrim($prefix,"_"));
				else $prefix_session = md5("BCOEM12345");

				$_SESSION['session_set_'.$prefix_session] = $prefix_session;
				$_SESSION['loginUsername'] = $user_name;

				$db_conn->where("id", 1);
				$row_contest_info = $db_conn->getOne($prefix."contest_info");

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

				$db_conn->where("id", 1);
				$row_prefs = $db_conn->getOne($prefix."preferences");

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
				$_SESSION['prefsSelectedStyles'] = $row_prefs['prefsSelectedStyles'];
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

				$db_conn->where("id", 1);
				$row_judging_prefs = $db_conn->getOne($prefix."judging_preferences");

				$_SESSION['jPrefsQueued'] = $row_judging_prefs['jPrefsQueued'];
				$_SESSION['jPrefsFlightEntries'] = $row_judging_prefs['jPrefsFlightEntries'];
				$_SESSION['jPrefsMaxBOS'] = $row_judging_prefs['jPrefsMaxBOS'];
				$_SESSION['jPrefsRounds'] = $row_judging_prefs['jPrefsRounds'];

				// Get counts for common, mostly static items
				$row_sponsor_count = $db_conn->getOne($prefix."sponsors", "COUNT(*) as 'count'");

				$_SESSION['sponsorCount'] = $row_sponsor_count['count'];
				$_SESSION['prefs'.$prefix_session] = "1";
				$_SESSION['prefix'] = $prefix;

				$db_conn->where("user_name", $_SESSION['loginUsername']);
				$row_user = $db_conn->getOne($prefix."users");

				$_SESSION['user_id'] = $row_user['id'];
				$_SESSION['userCreated'] = $row_user['userCreated'];
				$_SESSION['user_name'] = $row_user['user_name'];
				$_SESSION['userLevel'] = $row_user['userLevel'];

				$db_conn->where("uid", sterilize($_SESSION['user_id']));
				$row_name = $db_conn->getOne($prefix."brewer");

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

				if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

				$redirect = $base_url."index.php?section=admin&go=archive&msg=7";
				$redirect = prep_redirect_link($redirect);
				$redirect_go_to = sprintf("Location: %s", $redirect);

			}

			else {

				if (!empty($error_output)) $_SESSION['error_output'] = $error_output;
				
				// If the username/password combo is incorrect or not found, relocate to the login error page
				$redirect = $base_url."index.php?msg=1";
				$redirect = prep_redirect_link($redirect);
				$redirect_go_to = sprintf("Location: %s", $redirect);
				
				session_destroy();

			}
			
		}

	} // end if ($go == "add")


	if ($go == "edit") {

		$tables_array = array(
			$brewing_db_table, 
			$judging_assignments_db_table, 
			$judging_flights_db_table, 
			$judging_scores_db_table, 
			$judging_scores_bos_db_table, 
			$judging_tables_db_table, 
			$staff_db_table,
			$brewer_db_table,
			$special_best_data_db_table,
			$special_best_info_db_table,
			$style_types_db_table,
			$users_db_table,
			$sponsors_db_table
		);

		if ($eval_db_exist) $tables_array[] = $prefix."evaluation";
		
		// If the user changed the archive suffix name
		// Need to loop through each possible archive
		// DB table and change its name
		if ($filter != $suffix) {

			foreach ($tables_array as $table) {

				$table_old = $table."_".$filter;
				$table_new = $table."_".$suffix;

				if (check_setup($table_old,$database)) {
					
					$sql = sprintf("RENAME TABLE %s TO %s;", $table_old, $table_new);
					$db_conn->rawQuery($sql);
					if ($db_conn->getLastErrno() !== 0) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

				}

			}

			$update_table = $prefix."archive";
			$data = array('archiveSuffix' => $suffix);
			$db_conn->where ('id', $id);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

		} // end if ($filter != $suffix)

		$update_table = $prefix."archive";
		$data = array( 
			'archiveProEdition' => sterilize($_POST['archiveProEdition']),
			'archiveStyleSet' => sterilize($_POST['archiveStyleSet']),
			'archiveScoresheet' => sterilize($_POST['archiveScoresheet']),
			'archiveWinnerMethod' => sterilize($_POST['archiveWinnerMethod']),
			'archiveDisplayWinners' => sterilize($_POST['archiveDisplayWinners'])
			);
		$db_conn->where ('id', $id);
		$result = $db_conn->update ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		$redirect = $base_url."index.php?section=admin&go=archive&msg=2";
		$redirect = prep_redirect_link($redirect);
		$redirect_go_to = sprintf("Location: %s", $redirect);

	} // end if ($go == "edit")

} // end if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] == 0))
else echo "Not allowed."
?>