<?php
ob_start();
require_once ('../paths.php');
require_once (CONFIG.'bootstrap.php');
ini_set('display_errors', 1); // Change to 0 for prod; change to 1 for testing.
ini_set('display_startup_errors', 1); // Change to 0 for prod; change to 1 for testing.
error_reporting(E_ALL); // Change to error_reporting(0) for prod; change to E_ALL for testing.

if ((isset($_SESSION['session_set_'.$prefix_session])) && (isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] == 0)) {

	include_once (LIB.'admin.lib.php');
	require_once (INCLUDES.'constants_post_lang.inc.php');
	require_once (LIB.'process.lib.php');

	$errors = array();
	$error_count = 0;
	$location_added = FALSE;
	$judges_to_update = FALSE;
	$styles_added_count = 0; 
	$styles_added = FALSE;
	$added_styles = array();
	$judges_list = array();
	$judges_not_assigned = array();
	$practice_table_added = FALSE;

	$testing = FALSE;

	// Get selected style types from the form
	if (isset($_POST['selected_style_types'])) $selected_style_types = $_POST['selected_style_types'];
	else $selected_style_types = array("1","2","3"); // default to beer, cider, and mead

	// Establish the new location's judging date start from the form.
	if (isset($_POST['judging_session_start'])) {
		if ($_POST['judging_session_start'] == 0) $judging_date_start = time(); // immediately
		else $judging_date_start = sterilize($_POST['judging_session_start']); // specified in the form
	}

	else $judging_date_start = time(); // default to immediately

	if (time() > $last_judging_date) $last_judging_date = time() + 604800; // 7 days from today

	$update_table = $prefix."judging_locations";
	$data = array(
		'judgingLocType' => 1, // Practice location
		'judgingDate' => $judging_date_start,
		'judgingDateEnd' => $last_judging_date,
		'judgingLocName' => $label_practice_session,
		'judgingLocation' => ucfirst(strtolower($label_practice_session)),
		'judgingRounds' => 1
	);

	$result_add_judging_date = $db_conn->insert ($update_table, $data);

	if ($result_add_judging_date) {

		$location_added = TRUE;
		
		// Get the new location's id
		$query_new_location = sprintf("SELECT id FROM %s ORDER BY id DESC LIMIT 1",$prefix."judging_locations");
		$new_location = mysqli_query($connection,$query_new_location) or die (mysqli_error($connection));
		$row_new_location = mysqli_fetch_assoc($new_location);

		$new_location = ",Y-".$row_new_location['id'];

		// Loop through all judges in the brewer table and update their availabilities
		$query_judges = sprintf("SELECT id,uid,brewerJudgeLocation,brewerFirstName,brewerLastName FROM %s WHERE brewerJudge='Y';",$prefix."brewer");
		$judges = mysqli_query($connection,$query_judges) or die (mysqli_error($connection));
		$row_judges = mysqli_fetch_assoc($judges);
		$totalRows_judges = mysqli_num_rows($judges);

		if ($totalRows_judges > 0) {

			$judges_to_update = TRUE;

			$update_table = $prefix."brewer";
			$new_availability = $row_judges['brewerJudgeLocation'].$new_location;

			do {

				$judges_list[] = array(
					'uid' => $row_judges['uid'],
					'brewerFirstName' => $row_judges['brewerFirstName'],
					'brewerLastName' => $row_judges['brewerLastName']
				);

				$data = array(
					'brewerJudgeLocation' => $new_availability
				);
				$db_conn->where ('id', $row_judges['id']);
				
				if ($testing) {
					print_r($data);
					echo "<br>";
					$result = TRUE;
				}

				else $result = $db_conn->update ($update_table, $data);

			} while($row_judges = mysqli_fetch_assoc($judges));

		}

		// Create a new, dummy participant to attach each style type's entry to
		$username = random_generator(10,1)."@practice-user.com";
		$userQuestionAnswer = random_generator(10,1);
		$entered_password = md5(random_generator(10,1));

		// Add the user's creds to the "users" table
		require (CLASSES.'phpass/PasswordHash.php');
		$hasher = new PasswordHash(8, false);
		$hash = $hasher->HashPassword($entered_password);
		$hasher_question = new PasswordHash(8, false);
		$hash_question = $hasher_question->HashPassword(sterilize($userQuestionAnswer));

		$update_table = $prefix."users";
		$data = array(
			'user_name' => $username,
			'userLevel' => 2,
			'password' => $hash,
			'userQuestion' => "Randomly Generated",
			'userQuestionAnswer' => $hash_question,
			'userCreated' =>  date('Y-m-d H:i:s', time()),
			'userAdminObfuscate' => 1
		);
		
		$result = $db_conn->insert ($update_table, $data);

		// Get the id from the "users" table to insert as the uid in the "brewer" table
		$query_new_user = sprintf("SELECT id FROM `%s` WHERE user_name = '%s'", $prefix."users", $username);
		$new_user = mysqli_query($connection,$query_new_user);
		$row_new_user = mysqli_fetch_assoc($new_user);

		$first_name = "Practice";
		$last_name = "Entrant";
		$address = "1234 Main";
		$city = "Denver";
		$state_province = "CO";
		$zip = "80000";
		$country = "United States";
		$brewerPhone1 = "(000) 867-5309";

		$update_table = $prefix."brewer";
		$data = array(
			'uid' => $row_new_user['id'],
			'brewerFirstName' => $first_name,
			'brewerLastName' => $last_name,
			'brewerAddress' => $address,
			'brewerCity' => $city,
			'brewerState' => $state_province,
			'brewerZip' => $zip,
			'brewerCountry' => $country,
			'brewerPhone1' => $brewerPhone1,
			'brewerClubs' => array_rand(array_flip($club_array), 1),
			'brewerEmail' => $username,
			'brewerStaff' => NULL,
			'brewerSteward' => NULL,
			'brewerJudge' => NULL,
			'brewerJudgeID' => NULL,
			'brewerJudgeMead' => NULL,
			'brewerJudgeCider' => NULL,
			'brewerJudgeRank' => NULL,
			'brewerJudgeLikes' => NULL,
			'brewerJudgeDislikes' => NULL,
			'brewerJudgeLocation' => NULL,
			'brewerStewardLocation' => NULL,
			'brewerJudgeExp' => NULL,
			'brewerJudgeNotes' => "Dummy participant for electronic scoresheet practice.",
			'brewerJudgeWaiver' => NULL,
			'brewerAHA' => NULL,
			'brewerMHP' => NULL,
			'brewerProAm' => NULL,
			'brewerDropOff' => 0,
			'brewerBreweryName' => NULL,
			'brewerBreweryInfo' => NULL,
			'brewerAssignment' => NULL
		);

		$result = $db_conn->insert ($update_table, $data);

		// Create a new custom style for each of the selected style types (beer, cider, and/or mead).
		// Then, add an single entry for each style type.
		
		// Get last brewStyleGroup for any custom styles (will be 50+)
		$query_last_custom = sprintf("SELECT brewStyleGroup FROM %s WHERE brewStyleGroup >= '50' AND brewStyleOwn='custom' ORDER BY brewStyleGroup DESC LIMIT 1;",$prefix."styles");
		$last_custom = mysqli_query($connection,$query_last_custom) or die (mysqli_error($connection));
		$row_last_custom = mysqli_fetch_assoc($last_custom);
		$totalRows_last_custom = mysqli_num_rows($last_custom);

		$brewStyleGroup = 50;
		if (($totalRows_last_custom > 0) && (is_numeric($row_last_custom['brewStyleGroup']))) $brewStyleGroup = $row_last_custom['brewStyleGroup'];	
		
		foreach ($selected_style_types as $value) {

			$brewStyleGroup = $brewStyleGroup + 1;

			if ($value == 1) {
				$brewStyle = $label_practice_beer;
				$brewStyleType = 1;
				$brewStyleStrength = NULL;
				$brewStyleCarb = NULL;
				$brewStyleSweet = NULL;
			}

			if ($value == 2) {
				$brewStyle = $label_practice_cider;
				$brewStyleType = 2;
				$brewStyleStrength = 1;
				$brewStyleCarb = 0;
				$brewStyleSweet = 1;
			}

			if ($value == 3) {
				$brewStyle = $label_practice_mead;
				$brewStyleType = 3;
				$brewStyleStrength = 1;
				$brewStyleCarb = 1;
				$brewStyleSweet = 1;
			}

			$brewStyle = sterilize($brewStyle);

			$update_table = $prefix."styles";

			$data = array(
				'brewStyle' => $brewStyle,
				'brewStyleOG' => NULL,
				'brewStyleOGMax' => NULL,
				'brewStyleFG' => NULL,
				'brewStyleFGMax' => NULL,
				'brewStyleABV' => NULL,
				'brewStyleABVMax' => NULL,
				'brewStyleIBU' => NULL,
				'brewStyleIBUMax' => NULL,
				'brewStyleSRM' => NULL,
				'brewStyleSRMMax' => NULL,
				'brewStyleType' => $brewStyleType,
				'brewStyleInfo' => NULL,
				'brewStyleLink' => NULL,
				'brewStyleGroup' => $brewStyleGroup,
				'brewStyleNum' => "A",
				'brewStyleActive' => "N",
				'brewStyleOwn' => "custom",
				'brewStyleVersion' => $_SESSION['prefsStyleSet'],
				'brewStyleReqSpec' => NULL,
				'brewStyleStrength' => $brewStyleStrength,
				'brewStyleCarb' => $brewStyleCarb,
				'brewStyleSweet' => $brewStyleSweet,
				'brewStyleEntry' => NULL
			);
			
			$result = $db_conn->insert ($update_table, $data);

			if ($result) {

				$styles_added_count++;
				
				// Get the id of the newly created style.
				$query_last_added = sprintf("SELECT id FROM %s WHERE brewStyleOwn='custom' ORDER BY id DESC LIMIT 1;",$prefix."styles");
				$last_added = mysqli_query($connection,$query_last_added) or die (mysqli_error($connection));
				$row_last_added = mysqli_fetch_assoc($last_added);
				
				$added_styles[] = array(
					'id' => $row_last_added['id'],
					'brewStyle' => $brewStyle,
					'brewStyleGroup' => $brewStyleGroup,
					'brewStyleNum' => "A",
					'brewStyleStrength' => $brewStyleStrength,
					'brewStyleCarb' => $brewStyleCarb,
					'brewStyleSweet' => $brewStyleSweet,
					'brewStyleType' => $brewStyleType
				);

			}

		} // end foreach

		if ($styles_added_count > 0) $styles_added = TRUE;

		$added_table_styles = array();
		$added_entry_ids = array();

		if ($styles_added) {

			// Insert into the entries DB table an entry for each style type.
			
			foreach ($added_styles as $key => $value) {

				$added_table_styles[] = $value['id'];

				if ($value['brewStyleType'] == 1) $brewAdminNotes = $label_practice_beer.".";
				elseif ($value['brewStyleType'] == 2) $brewAdminNotes = $label_practice_cider."."; 
				elseif ($value['brewStyleType'] == 3) $brewAdminNotes = $label_practice_mead.".";
				else $brewAdminNotes = $label_practice_entry.".";

				$brewAdminNotes = ucfirst(strtolower($brewAdminNotes));

				$update_table = $prefix."brewing";

				$data = array(
					'brewName' => $label_practice_entry." ".$key,
					'brewStyle' => $value['brewStyle'],
					'brewCategory' => $value['brewStyleGroup'],
					'brewCategorySort' => $value['brewStyleGroup'],
					'brewSubCategory' => $value['brewStyleNum'],
					'brewMead1' => $value['brewStyleCarb'],
					'brewMead2' => $value['brewStyleSweet'],
					'brewMead3' => $value['brewStyleStrength'],
					'brewStyleType' => $value['brewStyleType'],
					'brewConfirmed' => 1,
					'brewReceived' => 1,
					'brewPaid' => 0,
					'brewUpdated' => date('Y-m-d H:i:s', time()),
					'brewJudgingNumber' => random_judging_num_generator(),
					'brewBrewerID' => $row_new_user['id'],
					'brewBrewerFirstName' => $first_name,
					'brewBrewerLastName' => $last_name,
					'brewStaffNotes' => $brewAdminNotes,
					'brewAdminNotes' => $brewAdminNotes,
					'brewPouring' => "{\"pouring\":\"Normal\",\"pouring_rouse\":\"No\"}",
				);

				$result = $db_conn->insert ($update_table, $data);

				if ($result) {

					$query_last_entry_added = sprintf("SELECT id FROM %s ORDER BY id DESC LIMIT 1;",$prefix."brewing");
					$last_entry_added = mysqli_query($connection,$query_last_entry_added) or die (mysqli_error($connection));
					$row_last_entry_added = mysqli_fetch_assoc($last_entry_added);

					$added_entry_ids[] = $row_last_entry_added['id'];

					echo $value['brewStyle']." Complete<br>";
				}

			}

		} // end if ($styles_added)

	 	// Create a table with all the newly added styles.

	 	if ((is_array($added_table_styles)) && (!empty($added_table_styles))) $added_table_styles = implode(",",$added_table_styles);

		$update_table = $prefix."judging_tables";
		$data = array(
			'tableName' => $label_scoresheet_practice,
			'tableStyles' => $added_table_styles,
			'tableNumber' => 999,
			'tableLocation' => $row_new_location['id'],
			'tableEntryLimit' => NULL
		);

		$result = $db_conn->insert ($update_table, $data);

		if ($result) echo $label_scoresheet_practice." Table Added<br>";

		if ($result) {

			$practice_table_added = TRUE;
			
			$query_last_table_added = sprintf("SELECT id FROM %s ORDER BY id DESC LIMIT 1;",$prefix."judging_tables");
			$last_table_added = mysqli_query($connection,$query_last_table_added) or die (mysqli_error($connection));
			$row_last_table_added = mysqli_fetch_assoc($last_table_added);

			foreach ($added_entry_ids as $key => $value) {

				$update_table = $prefix."judging_flights";
				
				$data = array(
					'flightTable' => $row_last_table_added['id'],
					'flightNumber' => 1,
					'flightEntryID' => $value,
					'flightRound' => 1,
				);
				
				$result = $db_conn->insert ($update_table, $data);

			}

			// Add all assigned judges to that table.

			if ((is_array($judges_list)) && (!empty($judges_list))) {

				foreach ($judges_list as $key => $value) {
					
					$update_table = $prefix."judging_assignments";
					$data = array(
						'bid' => $value['uid'],
						'assignment' => "J",
						'assignTable' => $row_last_table_added['id'],
						'assignFlight' => 1,
						'assignRound' => 1,
						'assignLocation' => $row_new_location['id'],
						'assignPlanning' => NULL,
						'assignRoles' => NULL
					);

					$result = $db_conn->insert ($update_table, $data);

					if ($result) $value['uid']." - judge added to ".$row_last_table_added['id']."<br>";
					if (!$result) $judges_not_assigned[] = $value['brewerFirstName']." ".$value['brewerLastName'];

				}

			}

		}

	} // end if ($result_add_judging_date)

} // end if ((isset($_SESSION['session_set_'.$prefix_session])) && (isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] == 0))

else {
	
	$error = 1;
	header("Location: https://pbs.twimg.com/media/CGx6dsDVIAAV0am.png");

	/*
	if (!$location_added) $errors['location'] = "Practice session not added.";
	if (!$styles_added)  $errors['styles'] = "Practice styles not added.";
	if (!$practice_table_added)  $errors['table'] = "Practice table not added.";
	if (!empty($judges_not_assigned)) $errors['judges'] = $judges_not_assigned;
	// print_r($errors);
	*/
}

?>