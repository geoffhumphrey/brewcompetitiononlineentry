<?php
/*
 * Module:      process_practice_session.inc.php
 * Description: Creates/deletes the admin-triggered practice judging session
 *              (Manage Tables). Reached via includes/process.inc.php's
 *              action dispatch ($action == "practice_session_enable" or
 *              "practice_session_delete") - not a standalone AJAX endpoint,
 *              so it relies on process.inc.php's own bootstrap, CSRF token
 *              check, and header()/exit() tail rather than doing its own.
 */

// includes/constants.inc.php normally supplies $row_system/$last_judging_date/
// $club_array via the full page bootstrap, but requiring that whole file here
// risks the "cannot redeclare function" fatal already called out in
// process.inc.php's own bootstrap comment above - so constants_post_lang.inc.php
// (which reads $row_system) needs it pre-set, same minimal-default approach
// process.inc.php itself already uses for $default_from/$default_to.
if (!isset($row_system)) $row_system = null;

include_once (LIB.'admin.lib.php');
require_once (INCLUDES.'constants_post_lang.inc.php');
require_once (LIB.'practice_session.lib.php');

$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&go=judging_tables");

// Matches Manage Tables' own access level (redirect_or_exit gate in
// admin/judging_tables.admin.php is userLevel<=1) rather than the stricter
// userLevel==0 this feature originally (never-shipped) required - any Admin
// who can reach the page and toggle Table Planning Mode (itself
// userLevel<=2) can also manage a practice session.
if ($_SESSION['userLevel'] <= 1) {

	$errors = FALSE;
	$error_output = array();
	unset($_SESSION['practice_session_errors']);
	unset($_SESSION['practice_session_success']);

	// Practice sessions may only exist during Table Planning Mode - matches the UI's
	// own gating (the buttons that reach this action are only shown/enabled then),
	// enforced again here in case this is ever hit directly.
	if ($_SESSION['jPrefsTablePlanning'] != 1) {

		$errors = TRUE;
		$error_output[] = "Practice judging sessions are only available in Tables Planning Mode.";

	}

	elseif ($action == "practice_session_delete") {

		$delete_result = delete_practice_session($db_conn, $prefix);

		if (!$delete_result['success']) {
			$errors = TRUE;
			$error_output = array_merge($error_output, $delete_result['errors']);
		}

		else $_SESSION['practice_session_success'] = "delete";

	}

	else { // practice_session_enable

		// Idempotency: never create a second practice table/location/entries.
		if (practice_session_exists($db_conn, $prefix)) {

			$errors = TRUE;
			$error_output[] = "A practice judging session already exists.";

		}

		else {

			$location_added = FALSE;
			$judges_to_update = FALSE;
			$styles_added_count = 0;
			$styles_added = FALSE;
			$added_styles = array();
			$judges_list = array();
			$judges_not_assigned = array();
			$practice_table_added = FALSE;

			// Get selected style types from the form
			if (isset($_POST['selected_style_types'])) $selected_style_types = $_POST['selected_style_types'];
			else $selected_style_types = array();

			if (empty($selected_style_types)) {

				$errors = TRUE;
				$error_output[] = "Select at least one style (Beer, Cider, or Mead).";

			}

			else {

				// Establish the new location's judging date start - always immediate for
				// a practice session (there's no form field for this today).
				$judging_date_start = time();

				// $last_judging_date normally comes from includes/constants.inc.php (the
				// latest real session's end date, extended if already past) - not available
				// here (see the require note above). A practice location doesn't need that
				// aggregate; a flat 7-day window is enough for a scratch judging date range.
				$last_judging_date = time() + 604800;

				$update_table = $prefix."judging_locations";
				$data = array(
					'judgingLocType' => 1,
					'judgingDate' => $judging_date_start,
					'judgingDateEnd' => $last_judging_date,
					'judgingLocName' => $label_practice_session,
					'judgingLocation' => ucfirst(strtolower($label_practice_session)),
					'judgingRounds' => 1
				);

				$result_add_judging_date = $db_conn->insert($update_table, $data);

				if (!$result_add_judging_date) {
					$errors = TRUE;
					$error_output[] = $db_conn->getLastError();
				}

				else {

					$location_added = TRUE;

					// Get the new location's id
					$db_conn->orderBy('id', 'DESC');
					$row_new_location = $db_conn->getOne($prefix."judging_locations", "id");

					$new_location = ",Y-".$row_new_location['id'];

					// Loop through all judges in the brewer table and update their availabilities
					$db_conn->where('brewerJudge', 'Y');
					$rows_judges = $db_conn->get($prefix."brewer", null, "id,uid,brewerJudgeLocation,brewerFirstName,brewerLastName");
					$totalRows_judges = $db_conn->count;

					if ($totalRows_judges > 0) {

						$judges_to_update = TRUE;

						$update_table = $prefix."brewer";

						foreach ($rows_judges as $row_judges) {

							$judges_list[] = array(
								'uid' => $row_judges['uid'],
								'brewerFirstName' => $row_judges['brewerFirstName'],
								'brewerLastName' => $row_judges['brewerLastName']
							);

							// Each judge's OWN existing availability, not a value computed once
							// from the first judge in the list and reused for everyone - that
							// bug would have overwritten every judge's real availability with
							// a single shared string.
							$new_availability = $row_judges['brewerJudgeLocation'].$new_location;

							$data = array(
								'brewerJudgeLocation' => $new_availability
							);
							$db_conn->where('id', $row_judges['id']);
							$result = $db_conn->update($update_table, $data);
							if (!$result) {
								$errors = TRUE;
								$error_output[] = $db_conn->getLastError();
							}

						}

					}

					// Create a new, dummy participant to attach each style type's entry to
					$username = random_generator(10,1)."@practice-user.fake";
					$userQuestionAnswer = random_generator(10,1);
					$entered_password = md5(random_generator(10,1));

					// Bare $club_array normally comes from includes/constants.inc.php (not
					// available here - see the require note above); $_SESSION['club_array']
					// is the same list, already populated by whatever full page load got the
					// admin to this form, so no need to re-derive it. Falls back to a single
					// placeholder in the unlikely case the session copy is empty.
					$club_array_for_practice = (!empty($_SESSION['club_array'])) ? $_SESSION['club_array'] : array("N/A");

					// Add the user's creds to the "users" table
					require (CLASSES.'phpass/PasswordHash.php');
					$hasher = new PasswordHash(8, false);
					$hash = $hasher->HashPassword($entered_password);
					$hasher_question = new PasswordHash(8, false);
					$hash_question = $hasher_question->HashPassword($userQuestionAnswer);

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

					$result = $db_conn->insert($update_table, $data);
					if (!$result) {
						$errors = TRUE;
						$error_output[] = $db_conn->getLastError();
					}

					// Get the id from the "users" table to insert as the uid in the "brewer" table
					$db_conn->where('user_name', $username);
					$row_new_user = $db_conn->getOne($prefix."users", "id");

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
						'brewerClubs' => array_rand(array_flip($club_array_for_practice), 1),
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

					$result = $db_conn->insert($update_table, $data);
					if (!$result) {
						$errors = TRUE;
						$error_output[] = $db_conn->getLastError();
					}

					// Create a new custom style for each of the selected style types (beer, cider, and/or mead).
					// Then, add an single entry for each style type.

					// Get last brewStyleGroup for any custom styles (will be 50+)
					$db_conn->where('brewStyleGroup', '50', '>=');
					$db_conn->where('brewStyleOwn', 'custom');
					$db_conn->orderBy('brewStyleGroup', 'DESC');
					$row_last_custom = $db_conn->getOne($prefix."styles", "brewStyleGroup");
					$totalRows_last_custom = $db_conn->count;

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

						$result = $db_conn->insert($update_table, $data);

						if ($result) {

							$styles_added_count++;

							// Get the id of the newly created style.
							$db_conn->where('brewStyleOwn', 'custom');
							$db_conn->orderBy('id', 'DESC');
							$row_last_added = $db_conn->getOne($prefix."styles", "id");

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

						else {
							$errors = TRUE;
							$error_output[] = $db_conn->getLastError();
						}

					} // end foreach

					if ($styles_added_count > 0) $styles_added = TRUE;

					$added_table_styles = array();
					$added_entry_ids = array();

					$brewMead1_arr = array("Still","Petillant","Sparkling");
					$brewMead2_arr = array("Dry","Semi-Dry","Medium","Semi-Sweet","Sweet");
					$brewMead3_arr = array("Hydromel","Standard","Sack");

					if ($styles_added) {

						// Insert into the entries DB table an entry for each style type.

						foreach ($added_styles as $key => $value) {

							$added_table_styles[] = $value['id'];

							if ($value['brewStyleType'] == 1) {
								$brewAdminNotes = $label_practice_beer.".";
								$brewMead1 = "";
								$brewMead2 = "";
								$brewMead3 = "";
							}

							elseif ($value['brewStyleType'] == 2) {
								$brewAdminNotes = $label_practice_cider.".";
								$brewMead1 = array_rand(array_flip($brewMead1_arr), 1);
								$brewMead2 = array_rand(array_flip($brewMead2_arr), 1);
								$brewMead3 = array_rand(array_flip($brewMead3_arr), 1);
							}

							elseif ($value['brewStyleType'] == 3) {
								$brewAdminNotes = $label_practice_mead.".";
								$brewMead1 = array_rand(array_flip($brewMead1_arr), 1);
								$brewMead2 = array_rand(array_flip($brewMead2_arr), 1);
								$brewMead3 = array_rand(array_flip($brewMead3_arr), 1);
							}

							else {
								$brewAdminNotes = $label_practice_entry.".";
							}

							$brewAdminNotes = ucfirst(strtolower($brewAdminNotes));

							$update_table = $prefix."brewing";

							$data = array(
								'brewName' => $label_practice_entry." ".$key,
								'brewStyle' => $value['brewStyle'],
								'brewCategory' => $value['brewStyleGroup'],
								'brewCategorySort' => $value['brewStyleGroup'],
								'brewSubCategory' => $value['brewStyleNum'],
								'brewMead1' => $brewMead1,
								'brewMead2' => $brewMead2,
								'brewMead3' => $brewMead3,
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

							$result = $db_conn->insert($update_table, $data);

							if ($result) {

								$db_conn->orderBy('id', 'DESC');
								$row_last_entry_added = $db_conn->getOne($prefix."brewing", "id");

								$added_entry_ids[] = $row_last_entry_added['id'];

							}

							else {
								$errors = TRUE;
								$error_output[] = $db_conn->getLastError();
							}

						}

					} // end if ($styles_added)

					// Create a table with all the newly added styles.

					if ((is_array($added_table_styles)) && (!empty($added_table_styles))) $added_table_styles = implode(",", $added_table_styles);

					$update_table = $prefix."judging_tables";
					$data = array(
						'tableName' => $label_scoresheet_practice,
						'tableStyles' => $added_table_styles,
						'tableNumber' => PRACTICE_SESSION_TABLE_NUMBER,
						'tableLocation' => $row_new_location['id'],
						'tableEntryLimit' => NULL
					);

					$result = $db_conn->insert($update_table, $data);

					if (!$result) {
						$errors = TRUE;
						$error_output[] = $db_conn->getLastError();
					}

					else {

						$practice_table_added = TRUE;

						$db_conn->orderBy('id', 'DESC');
						$row_last_table_added = $db_conn->getOne($prefix."judging_tables", "id");

						foreach ($added_entry_ids as $key => $value) {

							$update_table = $prefix."judging_flights";

							$data = array(
								'flightTable' => $row_last_table_added['id'],
								'flightNumber' => 1,
								'flightEntryID' => $value,
								'flightRound' => 1,
							);

							$result = $db_conn->insert($update_table, $data);
							if (!$result) {
								$errors = TRUE;
								$error_output[] = $db_conn->getLastError();
							}

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

								$result = $db_conn->insert($update_table, $data);

								if (!$result) $judges_not_assigned[] = $value['brewerFirstName']." ".$value['brewerLastName'];

							}

						}

						if (!empty($judges_not_assigned)) {
							$errors = TRUE;
							$error_output[] = "The following judges could not be assigned to the practice table: ".implode(", ", $judges_not_assigned);
						}

						if (!$errors) $_SESSION['practice_session_success'] = "create";

					}

				} // end else ($result_add_judging_date)

			} // end else (!empty($selected_style_types))

		} // end else (!practice_session_exists)

	} // end else (practice_session_enable)

	if (!empty($error_output)) $_SESSION['practice_session_errors'] = $error_output;

}
?>
