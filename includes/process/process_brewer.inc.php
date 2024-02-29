<?php
/**
 * Module:      process_brewer_add.inc.php
 * Description: This module does all the heavy lifting for adding participant information to the
 *              "brewer" table.
 */

use PHPMailer\PHPMailer\PHPMailer;
require(LIB.'email.lib.php');

if ((isset($_SERVER['HTTP_REFERER'])) && (((isset($_SESSION['loginUsername'])) && (isset($_SESSION['userLevel']))) || ($section == "setup"))) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

	if ((isset($_SESSION['userLevel'])) && ($_SESSION['userLevel'] == 2)) {

		// Check whether user is "authorized" to edit the entry in DB
		$query_brewer_id = sprintf("SELECT id FROM $brewer_db_table WHERE uid = '%s'", $_SESSION['user_id']);
		$brewer_id = mysqli_query($connection,$query_brewer_id) or die (mysqli_error($connection));
		$row_brewer_id = mysqli_fetch_assoc($brewer_id);

		if ($id != $row_brewer_id['id']) {
			$redirect = $base_url."index.php?section=list&msg=12";
			$redirect = prep_redirect_link($redirect);
			$redirect_go_to = sprintf("Location: %s", $redirect);
		}

	}

	if (($action == "add") || ($action == "edit")) require_once (PROCESS.'process_brewer_info.inc.php');
	require_once (DB.'brewer.db.php');
	require_once (DB.'judging_locations.db.php');

	// Empty the user_info session variable
	// Will trigger the session to reset the variables in common.db.php upon reload after redirect
	unset($_SESSION['user_info'.$prefix_session]);

	$userQuestion_change = FALSE;

	if ($action == "update") {

		if ($filter == "clear") {

			$sql = sprintf("TRUNCATE %s",$prefix."staff",$uid);
			$db_conn->rawQuery($sql);
			if ($db_conn->getLastErrno() !== 0) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$sql = sprintf("TRUNCATE %s",$prefix."judging_assignments",$uid);
			$db_conn->rawQuery($sql);
			if ($db_conn->getLastErrno() !== 0) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

		}

		foreach($_POST['uid'] as $uid) {

			$query_staff = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE uid='%s'",$prefix."staff",$uid);
			$staff = mysqli_query($connection,$query_staff) or die (mysqli_error($connection));
			$row_staff = mysqli_fetch_assoc($staff);

			//echo $row_staff['count'];

			if ($filter == "judges") {

				if ((isset($_POST['staff_judge'.$uid])) && ($_POST['staff_judge'.$uid] == "1")) {
					
					if ($row_staff['count'] == 0) {
						
						$update_table = $prefix."staff";
						$data = array('uid' => $uid,'staff_judge' => '1');
						$result = $db_conn->insert ($update_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					}
					
					else {

						$update_table = $prefix."staff";
						$data = array('staff_judge' => '1');			
						$db_conn->where ('uid', $uid);
						$result = $db_conn->update ($update_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					}
					
				}

				if (!isset($_POST['staff_judge'.$uid])) {

					if ($row_staff['count'] > 0) {

						$update_table = $prefix."staff";
						$data = array('staff_judge' => '0');			
						$db_conn->where ('uid', $uid);
						$result = $db_conn->update ($update_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					}

					// Check to see if the participant is assigned to be a judge or steward in the judging_assignments table
					$query_assign = sprintf("SELECT id FROM $judging_assignments_db_table WHERE bid='%s' AND assignment='J'",$uid);
					$assign = mysqli_query($connection,$query_assign) or die (mysqli_error($connection));
					$row_assign = mysqli_fetch_assoc($assign);
					$totalRows_assign = mysqli_num_rows($assign);

					// If so, delete all instances
					if ($totalRows_assign > 0) {
						
						do {

							$update_table = $prefix."judging_assignments";
							$db_conn->where ('id', $row_assign['id']);
							$result = $db_conn->delete($update_table);
							if (!$result) {
								$error_output[] = $db_conn->getLastError();
								$errors = TRUE;
							}

						} while ($row_assign = mysqli_fetch_assoc($assign));
					
					}

				}

			} // end if ($filter == "judges")

			if ($filter == "stewards") {

				if ((isset($_POST['staff_steward'.$uid])) && ($_POST['staff_steward'.$uid] == "1")) {

					if ($row_staff['count'] == 0) {
						$update_table = $prefix."staff";
						$data = array('uid' => $uid,'staff_steward' => '1');
						$result = $db_conn->insert ($update_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}
					}

					else {

						$update_table = $prefix."staff";
						$data = array('staff_steward' => '1');			
						$db_conn->where ('uid', $uid);
						$result = $db_conn->update ($update_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					}

				}

				if (!isset($_POST['staff_steward'.$uid])) {

					if ($row_staff['count'] > 0) {

						$update_table = $prefix."staff";
						$data = array('staff_steward' => '0');			
						$db_conn->where ('uid', $uid);
						$result = $db_conn->update ($update_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					}

					// Check to see if the participant is assigned to be a steward in the judging_assignments table
					$query_assign = sprintf("SELECT id FROM $judging_assignments_db_table WHERE bid='%s' AND assignment='S'",$uid);
					$assign = mysqli_query($connection,$query_assign) or die (mysqli_error($connection));
					$row_assign = mysqli_fetch_assoc($assign);
					$totalRows_assign = mysqli_num_rows($assign);

					// If so, delete all instances
					if ($totalRows_assign > 0) {

						do {

							$update_table = $prefix."judging_assignments";
							$db_conn->where ('id', $row_assign['id']);
							$result = $db_conn->delete($update_table);
							if (!$result) {
								$error_output[] = $db_conn->getLastError();
								$errors = TRUE;
							}

						} while ($row_assign = mysqli_fetch_assoc($assign));

					}

				}

			} // if ($filter == "stewards")

			if ($filter == "staff") {

				if ((isset($_POST['staff_staff'.$uid])) && ($_POST['staff_staff'.$uid] == "1")) {

					if ($row_staff['count'] == 0) {
						$update_table = $prefix."staff";
						$data = array('uid' => $uid,'staff_staff' => '1');
						$result = $db_conn->insert ($update_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					}

					else {

						$update_table = $prefix."staff";
						$data = array('staff_staff' => '1');			
						$db_conn->where ('uid', $uid);
						$result = $db_conn->update ($update_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					}

				}

				if (!isset($_POST['staff_staff'.$uid])) {

					if ($row_staff['count'] > 0) {

						$update_table = $prefix."staff";
						$data = array('staff_staff' => '0');			
						$db_conn->where ('uid', $uid);
						$result = $db_conn->update ($update_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					}

				}

			} // end if ($filter == "staff")

			if ($filter == "bos") {

				if ((isset($_POST['staff_judge_bos'.$uid])) && ($_POST['staff_judge_bos'.$uid] == "1")) {

					if ($row_staff['count'] == 0) {
						
						$update_table = $prefix."staff";
						$data = array('uid' => $uid,'staff_judge_bos' => '1');
						$result = $db_conn->insert ($update_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					}
					
					else {

						$update_table = $prefix."staff";
						$data = array('staff_judge_bos' => '1');			
						$db_conn->where ('uid', $uid);
						$result = $db_conn->update ($update_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					}

				}

				if (!isset($_POST['staff_judge_bos'.$uid])) {

					if ($row_staff['count'] > 0) {

						$update_table = $prefix."staff";
						$data = array('staff_judge_bos' => '0');			
						$db_conn->where ('uid', $uid);
						$result = $db_conn->update ($update_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					}

				}

			} // end if ($filter == "bos")

		} // end foreach($_POST['uid'] as $uid)

		if (($filter == "staff") && ($_POST['Organizer'] != "")) {

			$uid = sterilize($_POST['Organizer']);

			$query_org = sprintf("SELECT * FROM %s WHERE uid='%s'", $prefix."staff", $uid);
			$org = mysqli_query($connection,$query_org) or die (mysqli_error($connection));
			$row_org = mysqli_fetch_assoc($org);
			$totalRows_org = mysqli_num_rows($org);
			//echo $_POST['Organizer']."<br>";
			//echo $row_org['uid']."<br>";

			if ($row_org) {

				// Clear any Organizer assignments
				$update_table = $prefix."staff";
				$data = array('staff_organizer' => 0);
				$result = $db_conn->update ($update_table, $data);

				// If the posted UID is not in the DB, add and make the Org

				if ($totalRows_org == 0) {

					$update_table = $prefix."staff";
					$data = array(
						'staff_organizer' => 1,
						'staff_staff' => 0,
						'staff_judge' => 0,
						'staff_judge_bos' => 0,
						'uid' => $uid
					);
					$result = $db_conn->insert ($update_table, $data);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

				} // end if ($totalRows_org == 0)

				else {

					// If the posted UID is the same as the retrieved UID,
					// make the Org and clear out all other assignments.

					if ($uid == $row_org['uid']) {

						$update_table = $prefix."staff";
						$data = array(
							'staff_organizer' => 1,
							'staff_staff' => 0,
							'staff_judge' => 0,
							'staff_judge_bos' => 0,
						);
						$db_conn->where ('uid', $uid);
						$result = $db_conn->update ($update_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					}

				} // end else

			} // end if ($row_org) 

			// If the DB table is empty, add record making the Org
			else {

				$update_table = $prefix."staff";
				$data = array(
					'staff_organizer' => '1',
					'staff_staff' => '0',
					'staff_judge' => '0',
					'staff_judge_bos' => '0',
					'uid' => $uid
				);
				$result = $db_conn->insert ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

		} // end if (($filter == "staff") && ($_POST['Organizer'] != ""))

		$update_table = $prefix."brewer";
		$data = array('brewerJudgeWaiver' => 'Y');
		$db_conn->where ('brewerJudge', 'Y');
		$db_conn->orWhere ('brewerSteward', 'Y');
		$result = $db_conn->update ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		if ($filter == "clear") $redirect = $base_url."index.php?section=admin&go=participants&msg=9";
		else $redirect = $base_url."index.php?section=admin&action=assign&go=judging&filter=".$filter."&msg=9";
		$redirect = prep_redirect_link($redirect);
		$redirect_go_to = sprintf("Location: %s", $redirect);

	} // end if ($action == "update")

	// --------------------------------------- Adding a Participant ----------------------------------------

	if ($action == "add") {

		$query_user = sprintf("SELECT id FROM $users_db_table WHERE id = '%s'", $_POST['uid']);
		$user = mysqli_query($connection,$query_user) or die (mysqli_error($connection));
		$row_user = mysqli_fetch_assoc($user);
		$totalRows_user = mysqli_num_rows($user);

		$brewerEmail = filter_var($_POST['brewerEmail'],FILTER_SANITIZE_EMAIL);
		$uid = sterilize($_POST['uid']);

		if ($totalRows_user == 0) {

			$update_table = $prefix."users";
			$data = array('user_name' => $brewerEmail);
			$db_conn->where ('id', $uid);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) $error_output[] = $db_conn->getLastError();

		}

		else {

			$update_table = $prefix."brewer";
			$data = array(
				'uid' => $uid,
				'brewerFirstName' => blank_to_null($first_name),
				'brewerLastName' => blank_to_null($last_name),
				'brewerAddress' => blank_to_null($address),
				'brewerCity' => blank_to_null($city),
				'brewerState' => blank_to_null($state),
				'brewerZip' => blank_to_null(sterilize($_POST['brewerZip'])),
				'brewerCountry' => blank_to_null($_POST['brewerCountry']),
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
				'brewerJudgeRank' => blank_to_null($rank),
				'brewerJudgeLikes' => blank_to_null($likes),
				'brewerJudgeDislikes' => blank_to_null($dislikes),
				'brewerJudgeLocation' => blank_to_null($location_pref1),
				'brewerStewardLocation' => blank_to_null($location_pref2),
				'brewerJudgeExp' => blank_to_null($brewerJudgeExp),
				'brewerJudgeNotes' => blank_to_null($brewerJudgeNotes),
				'brewerJudgeWaiver' => blank_to_null($brewerJudgeWaiver),
				'brewerAHA' => blank_to_null($brewerAHA),
				'brewerMHP' => blank_to_null($brewerMHP),
				'brewerProAm' => blank_to_null($brewerProAm),
				'brewerDropOff' => blank_to_null($brewerDropOff),
				'brewerBreweryName' => blank_to_null($brewerBreweryName),
				'brewerBreweryTTB' => blank_to_null($brewerBreweryTTB),
				'brewerAssignment' => blank_to_null($brewerAssignment)
			);

			$result = $db_conn->insert ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			if ($section == "setup") {

				// Check to see if processed correctly.
				$query_brewer_check = sprintf("SELECT COUNT(*) as 'count' FROM %s",$brewer_db_table);
				$brewer_check = mysqli_query($connection,$query_brewer_check) or die (mysqli_error($connection));
				$row_brewer_check = mysqli_fetch_assoc($brewer_check);

				// If so, mark step as complete in system table and redirect to next step.
				if ($row_brewer_check['count'] == 1) {

					$update_table = $prefix."bcoem_sys";
					$data = array('setup_last_step' => '2');			
					$db_conn->where ('id', 1);
					$result = $db_conn->update ($update_table, $data);
					if (!$result) $error_output[] = $db_conn->getLastError();

					$insertGoTo = $base_url."setup.php?section=step3";

				}

				// If not, redirect back to step 2 and display message.
				else  $insertGoTo = $base_url."setup.php?section=step2&msg=99";

			}

			elseif (($brewerJudge == "Y") || ($brewerSteward == "Y")) $insertGoTo = $base_url."index.php?section=judge&go=judge";
			elseif ($section == "admin") $insertGoTo = $base_url."index.php?section=admin&go=participants&msg=1&username=".$username;
			elseif (($go == "judge") && ($filter == "default")) $insertGoTo = $base_url."index.php?section=list&go=".$go."&filter=default&msg=7";
			elseif (($go == "judge") && ($filter != "default")) $insertGoTo = $base_url."index.php?section=admin&go=participants&msg=1";
			else $insertGoTo = $insertGoTo;

			if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

			if ($errors) $insertGoTo = $_POST['relocate']."&msg=3";
			$insertGoTo = prep_redirect_link($insertGoTo);
			$redirect_go_to = sprintf("Location: %s", $insertGoTo);

		} // end else

	} // end if ($action == "add")

	// --------------------------------------- Editing a Participant ----------------------------------------
	if ($action == "edit") {

		$brewerEmail = filter_var($_POST['brewerEmail'],FILTER_SANITIZE_EMAIL);
		$uid = sterilize($_POST['uid']);

		// Check for and clear assignments in staff DB table and judge assignments table if entrant
		// indicates they do not want to judge, steward, or staff

		if ($brewerJudge == "N") {

			$query_staff_assign = sprintf("SELECT id,uid,staff_judge FROM %s WHERE uid='%s'",$prefix."staff",$_POST['uid']);
			$staff_assign = mysqli_query($connection,$query_staff_assign) or die (mysqli_error($connection));
			$row_staff_assign = mysqli_fetch_assoc($staff_assign);
			$totalRows_staff_assign = mysqli_num_rows($staff_assign);

			if ($totalRows_staff_assign > 0) {

				do {

					if ($row_staff_assign['staff_judge'] == 1) {

						$update_table = $prefix."staff";
						$db_conn->where ('id', $row_staff_assign['id']);
						$result = $db_conn->delete($update_table);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					}

				} while ($row_staff_assign = mysqli_fetch_assoc($staff_assign));

			}

			$query_table_assign = sprintf("SELECT id,bid,assignment FROM %s WHERE bid='%s'",$prefix."judging_assignments",$_POST['uid']);
			$table_assign = mysqli_query($connection,$query_table_assign) or die (mysqli_error($connection));
			$row_table_assign = mysqli_fetch_assoc($table_assign);
			$totalRows_table_assign = mysqli_num_rows($table_assign);

			if ($totalRows_table_assign > 0) {

				do {

					if ($row_table_assign['assignment'] == "J") {

						$update_table = $prefix."judging_assignments";
						$db_conn->where ('id', $row_table_assign['id']);
						$result = $db_conn->delete($update_table);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					}

				} while ($row_table_assign = mysqli_fetch_assoc($table_assign));

			}

		}

		if ($brewerSteward == "N") {

			$query_staff_assign = sprintf("SELECT id,uid,staff_steward FROM %s WHERE uid='%s'",$prefix."staff",$_POST['uid']);
			$staff_assign = mysqli_query($connection,$query_staff_assign) or die (mysqli_error($connection));
			$row_staff_assign = mysqli_fetch_assoc($staff_assign);
			$totalRows_staff_assign = mysqli_num_rows($staff_assign);

			if ($totalRows_staff_assign > 0) {

				do {

					if ($row_staff_assign['staff_steward'] == 1) {

						$update_table = $prefix."staff";
						$db_conn->where ('id', $row_staff_assign['id']);
						$result = $db_conn->delete($update_table);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					}

				} while ($row_staff_assign = mysqli_fetch_assoc($staff_assign));

			}

			$query_table_assign = sprintf("SELECT id,bid,assignment FROM %s WHERE bid='%s'",$prefix."judging_assignments",$_POST['uid']);
			$table_assign = mysqli_query($connection,$query_table_assign) or die (mysqli_error($connection));
			$row_table_assign = mysqli_fetch_assoc($table_assign);
			$totalRows_table_assign = mysqli_num_rows($table_assign);

			if ($totalRows_table_assign > 0) {

				do {

					if ($row_table_assign['assignment'] == "S") {

						$update_table = $prefix."judging_assignments";
						$db_conn->where ('id', $row_table_assign['id']);
						$result = $db_conn->delete($update_table);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

					}

				} while ($row_table_assign = mysqli_fetch_assoc($table_assign));

			}

		}

		$update_table = $prefix."brewer";
		$data = array(
			'uid' => $uid,
			'brewerFirstName' => blank_to_null($first_name),
			'brewerLastName' => blank_to_null($last_name),
			'brewerAddress' => blank_to_null($address),
			'brewerCity' => blank_to_null($city),
			'brewerState' => blank_to_null($state),
			'brewerZip' => sterilize($_POST['brewerZip']),
			'brewerCountry' => blank_to_null($_POST['brewerCountry']),
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
			'brewerJudgeRank' => blank_to_null($rank),
			'brewerJudgeLikes' => blank_to_null($likes),
			'brewerJudgeDislikes' => blank_to_null($dislikes),
			'brewerJudgeLocation' => blank_to_null($location_pref1),
			'brewerStewardLocation' => blank_to_null($location_pref2),
			'brewerJudgeExp' => blank_to_null($brewerJudgeExp),
			'brewerJudgeNotes' => blank_to_null($brewerJudgeNotes),
			'brewerJudgeWaiver' => blank_to_null($brewerJudgeWaiver),
			'brewerAHA' => blank_to_null($brewerAHA),
			'brewerMHP' => blank_to_null($brewerMHP),
			'brewerProAm' => blank_to_null($brewerProAm),
			'brewerDropOff' => blank_to_null($brewerDropOff),
			'brewerBreweryName' => blank_to_null($brewerBreweryName),
			'brewerBreweryTTB' => blank_to_null($brewerBreweryTTB),
			'brewerAssignment' => blank_to_null($brewerAssignment)
		);
		$db_conn->where ('id', $id);
		$result = $db_conn->update ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		if ((isset($_POST['userQuestion'])) && ($_POST['changeSecurity'] == "Y")) {

			$update_table = $prefix."users";
			$data = array('userQuestion' => $purifier->purify($_POST['userQuestion']));
			$db_conn->where ('id', $id);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

		}

		if ((isset($_POST['userQuestionAnswer'])) && ($_POST['changeSecurity'] == "Y")) {
			
			$userQuestionAnswer = $purifier->purify(sterilize($_POST['userQuestionAnswer']));

			/**
			 * Hash the user's security question response.
			 * Addresses Issue #1208 on GitHub
			 * @see https://github.com/geoffhumphrey/brewcompetitiononlineentry/issues/1208
			 */
			
			require(CLASSES.'phpass/PasswordHash.php');
			$hasher_question = new PasswordHash(8, false);
			$hash_question = $hasher_question->HashPassword($userQuestionAnswer);

			$query_security_resp = sprintf("SELECT userQuestionAnswer FROM `%s` WHERE id='%s'",$prefix."users",$id);
			$security_resp = mysqli_query($connection,$query_security_resp);
			$row_security_resp = mysqli_fetch_assoc($security_resp);
			$totalRows_security_resp = mysqli_num_rows($security_resp);

			$stored_hash = $row_security_resp['userQuestionAnswer'];
			$check = 1;
			
			if ($totalRows_security_resp > 0) $check = $hasher_question->CheckPassword($userQuestionAnswer, $stored_hash);

			if ($check == 0) {
				// New  Store unhashed response for email confirmation
				$userQuestion_change = TRUE;
				$userQuestionAnswer_email = $userQuestionAnswer;
			}

			$update_table = $prefix."users";
			$data = array('userQuestionAnswer' => $hash_question);
			$db_conn->where ('id', $id);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

		}

		$update_table = $prefix."users";
		$data = array('userCreated' => $db_conn->now());
		$db_conn->where ('id', $id);
		$result = $db_conn->update ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		$update_table = $prefix."brewer";
		$data = array('brewerJudgeWaiver' => 'Y');
		$db_conn->where ('brewerJudge', 'Y');
		$db_conn->orWhere ('brewerSteward', 'Y');
		$result = $db_conn->update ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		if ($go == "register") $updateGoTo = $base_url."index.php?section=brew&msg=2";
		elseif (($go == "judge") && ($filter == "default")) $updateGoTo = $base_url."index.php?section=list&go=".$go."&filter=default&msg=7";
		elseif (($go == "judge") && ($filter != "default")) $updateGoTo = $base_url."index.php?section=admin&go=participants&msg=2";
		elseif ($go == "default") $updateGoTo = $base_url."index.php?section=list&go=".$go."&filter=default&msg=2";
		else $updateGoTo = $updateGoTo;

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		if ($errors) $insertGoTo = $_POST['relocate']."&msg=3";
		$updateGoTo = prep_redirect_link($updateGoTo);
		$redirect_go_to = sprintf("Location: %s", $updateGoTo);

		if ($userQuestion_change) {

			// Build vars
			$url = str_replace("www.","",$_SERVER['SERVER_NAME']);
			
			$from_email = (!isset($mail_default_from) || trim($mail_default_from) === '') ? "noreply@".$url : $mail_default_from;
			if (strpos($url, 'brewingcompetitions.com') !== false) $from_email = $default_from."@brewingcompetitions.com";
			$from_email = mb_convert_encoding($from_email, "UTF-8");

			$contestName = $_SESSION['contestName'];
			$from_name = html_entity_decode($from_name);
			$from_name = mb_convert_encoding($contestName, "UTF-8");

			$to_email = filter_var($_POST['brewerEmail'],FILTER_SANITIZE_EMAIL);
			$to_email = mb_convert_encoding($to_email, "UTF-8");
			$to_email_formatted = $to_name." <".$to_email.">";

			$to_name = $first_name." ".$last_name;
			$to_name = html_entity_decode($to_name);
			$to_name = mb_convert_encoding($to_name, "UTF-8");
			
			$subject = sprintf($_SESSION['contestName'].": %s",$register_text_051);
			$subject = html_entity_decode($subject);
			$subject = mb_convert_encoding($subject, "UTF-8");

			$message = "<html>" . "\r\n";
			$message .= "<body>" . "\r\n";

			if ((!empty($_SESSION['contestLogo'])) && (file_exists(USER_IMAGES.$_SESSION['contestLogo']))) $message .= "<p><img src='".$base_url."user_images/".$_SESSION['contestLogo']."' height='150'></p>";

			$message .= sprintf("<p>%s</p>",$register_text_047);

			$message .= "<table cellpadding='5' border='0'>";
			$message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_security_question,sterilize($_POST['userQuestion']));
			$message .= sprintf("<tr><td valign='top'><strong>%s:</strong></td><td valign='top'>%s</td></tr>",$label_security_answer,$userQuestionAnswer_email);
			$message .= "</table>";
			$message .= sprintf("<p>%s <a href='".$base_url."index.php?section=login'>%s</a>.</p>",$register_text_040,$register_text_041);

			$message .= sprintf("<p><strong>%s</strong></p>",$register_text_048);
			$message .= sprintf("<p>%s</p>",$register_text_049);
			$message .= sprintf("<p><small><em>%s</em></small></p>",$register_text_043);
			if ((DEBUG || TESTING) && ($mail_use_smtp)) $message .= "<p><small>Sent using phpMailer.</small></p>";

			$message .= "</body>" . "\r\n";
			$message .= "</html>";

			$headers  = "MIME-Version: 1.0"."\r\n";
			$headers .= "Content-type: text/html; charset=utf-8"."\r\n";
			$headers .= "From: ".$from_name." Server <".$from_email.">"."\r\n";
			$headers .= "Reply-To: ".$from_name." <".$from_email.">"."\r\n";

			/*
			echo "<pre>".htmlspecialchars($headers)."</pre>";
			echo $message;
			exit();
			*/

			if ($mail_use_smtp) {
				$mail = new PHPMailer(true);
				$mail->CharSet = 'UTF-8';
				$mail->Encoding = 'base64';
				$mail->addAddress($to_email, $to_name);
				$mail->setFrom($from_email, $from_name);
				$mail->Subject = $subject;
				$mail->Body = $message;
				sendPHPMailerMessage($mail);
			} else {
				mail($to_email_formatted, $subject, $message, $headers);
			}

		}

	}

} else {

	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);
	
}
?>
