<?php
/*
 * Module:      process_brewer_add.inc.php
 * Description: This module does all the heavy lifting for adding participant information to the
 *              "brewer" table.
 */

if ((isset($_SERVER['HTTP_REFERER'])) && (((isset($_SESSION['loginUsername'])) && (isset($_SESSION['userLevel']))) || ($section == "setup"))) {

	require(PROCESS.'process_brewer_info.inc.php');

	if ($_SESSION['userLevel'] == 2) {

		// Check whether user is "authorized" to edit the entry in DB
		$query_brewer_id = sprintf("SELECT id FROM $brewer_db_table WHERE uid = '%s'", $_SESSION['user_id']);
		$brewer_id = mysqli_query($connection,$query_brewer_id) or die (mysqli_error($connection));
		$row_brewer_id = mysqli_fetch_assoc($brewer_id);

		if ($id != $row_brewer_id['id']) {
			$redirectGoTo = $base_url."index.php?section=list&msg=12";
			$redirect_go_to = sprintf("Location: %s", stripslashes($redirectGoTo));
		}

	}

	require(DB.'brewer.db.php');
	require(DB.'judging_locations.db.php');

	// Empty the user_info session variable
	// Will trigger the session to reset the variables in common.db.php upon reload after redirect
	session_name($prefix_session);
	session_start();
	unset($_SESSION['user_info'.$prefix_session]);

	if ($action == "update") {

		if ($filter == "clear") {

			$updateSQL = sprintf("TRUNCATE %s",$prefix."staff",$uid);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			$updateSQL = sprintf("TRUNCATE %s",$prefix."judging_assignments",$uid);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		}

		foreach($_POST['uid'] as $uid) {

		$query_staff = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE uid='%s'",$prefix."staff",$uid);
		$staff = mysqli_query($connection,$query_staff) or die (mysqli_error($connection));
		$row_staff = mysqli_fetch_assoc($staff);

		//echo $row_staff['count'];

			if ($filter == "judges") {

				if ((isset($_POST['staff_judge'.$uid])) && ($_POST['staff_judge'.$uid] == "1")) {
					if ($row_staff['count'] == 0) $updateSQL = sprintf("INSERT INTO %s (uid,staff_judge) VALUES (%s,1)",$prefix."staff",$uid);
					else $updateSQL = sprintf("UPDATE %s SET staff_judge=1 WHERE uid=%s",$prefix."staff",$uid);
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
					//echo $updateSQL."<br>";
				}

				if (!isset($_POST['staff_judge'.$uid])) {

					if ($row_staff['count'] == 0) $updateSQL == "";
					else {
						$updateSQL = sprintf("UPDATE %s SET staff_judge=0 WHERE uid=%s",$prefix."staff",$uid);
						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
					}
					//echo $updateSQL."<br>";


					// Check to see if the participant is assigned to be a judge or steward in the judging_assignments table
					$query_assign = sprintf("SELECT id FROM $judging_assignments_db_table WHERE bid='%s' AND assignment='J'",$uid);
					$assign = mysqli_query($connection,$query_assign) or die (mysqli_error($connection));
					$row_assign = mysqli_fetch_assoc($assign);
					$totalRows_assign = mysqli_num_rows($assign);

					// If so, delete all instances
					if ($totalRows_assign > 0) {
						do {
							$deleteSQL = sprintf("DELETE FROM $judging_assignments_db_table WHERE id='%s'", $row_assign['id']);
							mysqli_real_escape_string($connection,$deleteSQL);
							$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
							//echo $deleteSQL."<br>";
						} while ($row_assign = mysqli_fetch_assoc($assign));
					}

				}

			} // end if ($filter == "judges")

			if ($filter == "stewards") {

				if ((isset($_POST['staff_steward'.$uid])) && ($_POST['staff_steward'.$uid] == "1")) {
					if ($row_staff['count'] == 0) $updateSQL = sprintf("INSERT INTO %s (uid,staff_steward) VALUES (%s,1)",$prefix."staff",$uid);
					else $updateSQL = sprintf("UPDATE %s SET staff_steward=1 WHERE uid=%s",$prefix."staff",$uid);
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
					//echo $updateSQL."<br>";
				}

				if (!isset($_POST['staff_steward'.$uid])) {

					if ($row_staff['count'] == 0) $updateSQL == "";
					else {
						$updateSQL = sprintf("UPDATE %s SET staff_steward=0 WHERE uid=%s",$prefix."staff",$uid);
						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
					}
					//echo $updateSQL."<br>";

					// Check to see if the participant is assigned to be a steward in the judging_assignments table
					$query_assign = sprintf("SELECT id FROM $judging_assignments_db_table WHERE bid='%s' AND assignment='S'",$uid);
					$assign = mysqli_query($connection,$query_assign) or die (mysqli_error($connection));
					$row_assign = mysqli_fetch_assoc($assign);
					$totalRows_assign = mysqli_num_rows($assign);

					// If so, delete all instances
					if ($totalRows_assign > 0) {
						do {
							$deleteSQL = sprintf("DELETE FROM $judging_assignments_db_table WHERE id='%s'", $row_assign['id']);
							mysqli_real_escape_string($connection,$deleteSQL);
							$result = mysqli_query($connection,$deleteSQL) or die (mysqli_error($connection));
						} while ($row_assign = mysqli_fetch_assoc($assign));
					}
				}
			} // if ($filter == "stewards")

			if ($filter == "staff") {

				if ((isset($_POST['staff_staff'.$uid])) && ($_POST['staff_staff'.$uid] == "1")) {
					if ($row_staff['count'] == 0) $updateSQL = sprintf("INSERT INTO %s (uid,staff_staff) VALUES (%s,1)",$prefix."staff",$uid);
					else $updateSQL = sprintf("UPDATE %s SET staff_staff=1 WHERE uid=%s",$prefix."staff",$uid);
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
					//echo $updateSQL."<br>";
				}

				if (!isset($_POST['staff_staff'.$uid])) {
					if ($row_staff['count'] == 0) $updateSQL == "";
					else {
						$updateSQL = sprintf("UPDATE %s SET staff_staff=0 WHERE uid=%s",$prefix."staff",$uid);
						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
					}
					//echo $updateSQL."<br>";
				}

			} // end if ($filter == "staff")

			if ($filter == "bos") {

				if ((isset($_POST['staff_judge_bos'.$uid])) && ($_POST['staff_judge_bos'.$uid] == "1")) {
					if ($row_staff['count'] == 0) $updateSQL = sprintf("INSERT INTO %s (uid,staff_judge_bos) VALUES (%s,1)",$prefix."staff",$uid);
					else $updateSQL = sprintf("UPDATE %s SET staff_judge_bos=1 WHERE uid=%s",$prefix."staff",$uid);
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
					//echo $updateSQL."<br>";
				}

				if (!isset($_POST['staff_judge_bos'.$uid])) {
					if ($row_staff['count'] == 0) $updateSQL == "";
					else {
						$updateSQL = sprintf("UPDATE %s SET staff_judge_bos=0 WHERE uid=%s",$prefix."staff",$uid);
						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
					}
					//echo $updateSQL."<br>";
				}

			} // end if ($filter == "bos")

		} // end foreach($_POST['uid'] as $uid)

		if ($filter == "staff") {

			if ($_POST['Organizer'] != "") {

				$query_org = sprintf("SELECT uid FROM %s WHERE staff_organizer='1'",$prefix."staff");
				$org = mysqli_query($connection,$query_org) or die (mysqli_error($connection));
				$row_org = mysqli_fetch_assoc($org);
				//echo $_POST['Organizer']."<br>";
				//echo $row_org['uid']."<br>";

				if ($_POST['Organizer'] != $row_org['uid']) {

					$updateSQL = sprintf("UPDATE %s SET staff_organizer='0' WHERE uid='%s'", $prefix."staff",$row_org['uid']);
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
					//echo $updateSQL."<br>";

					$query_staff_org = sprintf("SELECT uid FROM %s WHERE uid='%s'",$prefix."staff",$purifier->purify($_POST['Organizer']));
					$staff_org = mysqli_query($connection,$query_staff_org) or die (mysqli_error($connection));
					$row_staff_org = mysqli_fetch_assoc($staff_org);
					$totalRows_staff_org = mysqli_num_rows($staff_org);

					if ($totalRows_staff_org > 0) $updateSQL = sprintf("UPDATE %s SET staff_organizer='1', staff_staff='0', staff_judge='0', staff_judge_bos='0' WHERE uid='%s'", $prefix."staff", $_POST['Organizer']);
					else $updateSQL = sprintf("INSERT INTO %s (uid,staff_organizer) VALUES (%s,1)",$prefix."staff",$_POST['Organizer']);
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
					//echo $updateSQL."<br>";
				}

				if ($_POST['Organizer'] == $row_org['uid']) {
					$updateSQL = sprintf("UPDATE %s SET staff_organizer='1' WHERE uid='%s'", $prefix."staff", $purifier->purify($_POST['Organizer']));
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
					//echo $updateSQL."<br>";
				}

			}

		}

		$pattern = array('\'', '"');
		if ($filter == "clear") $massUpdateGoTo = $base_url."index.php?section=admin&go=participants&msg=9";
		else $massUpdateGoTo = $base_url."index.php?section=admin&action=assign&go=judging&filter=".$filter."&msg=9";
		$massUpdateGoTo = str_replace($pattern, "", $massUpdateGoTo);
		//echo $massUpdateGoTo;
		$redirect_go_to = sprintf("Location: %s", stripslashes($massUpdateGoTo));

	} // end if ($action == "update")

	// --------------------------------------- Adding a Participant ----------------------------------------

	if ($action == "add") {

		$query_user = sprintf("SELECT id FROM $users_db_table WHERE id = '%s'", $_POST['uid']);
		$user = mysqli_query($connection,$query_user) or die (mysqli_error($connection));
		$row_user = mysqli_fetch_assoc($user);
		$totalRows_user = mysqli_num_rows($user);

		if ($totalRows_user == 0) {
			//$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=brewer&go=".$go."&msg=2");
			$updateSQL = sprintf("UPDATE $users_db_table SET user_name='%s' WHERE id='%s'",
						   GetSQLValueString(filter_var($_POST['brewerEmail'],FILTER_SANITIZE_EMAIL), "text"),
						   GetSQLValueString($_POST['uid'], "text"));
			//echo $updateSQL;
			//exit;
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		}

		else {

			// Numbers 999999994 through 999999999 are reserved for NHC applications.
			if (($brewerAHA < "999999994") || ($brewerAHA == "")) {

				$insertSQL = sprintf("INSERT INTO $brewer_db_table (
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
				  brewerSteward,
				  brewerJudge,
				  brewerJudgeID,

				  brewerJudgeMead,
				  brewerJudgeRank,
				  brewerJudgeLocation,
				  brewerStewardLocation,
				  brewerAHA,

				  brewerDropOff,
				  brewerJudgeExp,
				  brewerJudgeNotes,
				  brewerStaff,
				  brewerBreweryName,

				  brewerBreweryTTB,
				  brewerJudgeCider,
				  brewerProAm

				) VALUES (
				%s, %s, %s, %s, %s,
				%s, %s, %s, %s, %s,
				%s, %s, %s, %s, %s,
				%s, %s, %s, %s, %s,
				%s, %s, %s, %s, %s,
				%s, %s, %s)",
							   GetSQLValueString($_POST['uid'], "int"),
							   GetSQLValueString($first_name, "text"),
							   GetSQLValueString($last_name, "text"),
							   GetSQLValueString($address, "text"),
							   GetSQLValueString($city, "text"),
							   GetSQLValueString($state, "text"),
							   GetSQLValueString(sterilize($_POST['brewerZip']), "text"),
							   GetSQLValueString($_POST['brewerCountry'], "text"),
							   GetSQLValueString($brewerPhone1, "text"),
							   GetSQLValueString($brewerPhone2, "text"),
							   GetSQLValueString($brewerClubs, "text"),
							   GetSQLValueString(filter_var($_POST['brewerEmail'],FILTER_SANITIZE_EMAIL), "text"),
							   GetSQLValueString($brewerSteward, "text"),
							   GetSQLValueString($brewerJudge, "text"),
							   GetSQLValueString($brewerJudgeID, "text"),
							   GetSQLValueString($brewerJudgeMead, "text"),
							   GetSQLValueString($brewerJudgeRank, "text"),
							   GetSQLValueString($location_pref1, "text"),
							   GetSQLValueString($location_pref2, "text"),
							   GetSQLValueString($brewerAHA, "int"),
							   GetSQLValueString($brewerDropOff, "int"),
							   GetSQLValueString($brewerJudgeExp, "text"),
							   GetSQLValueString($brewerJudgeNotes, "text"),
							   GetSQLValueString($brewerStaff, "text"),
							   GetSQLValueString($brewerBreweryName, "text"),
							   GetSQLValueString($brewerBreweryTTB, "text"),
							   GetSQLValueString($brewerJudgeCider, "text"),
							   GetSQLValueString($_POST['brewerProAm'], "int")
				);

				// only if added by an admin.
				if((NHC) && ($section == "admin")) {
				$updateSQL =  sprintf("INSERT INTO nhcentrant (
				uid,
				firstName,
				lastName,
				email,
				AHAnumber,
				regionPrefix
				)
				VALUES
				(%s, %s, %s, %s, %s, %s)",
								   GetSQLValueString($_POST['uid'], "int"),
								   GetSQLValueString($first_name, "text"),
								   GetSQLValueString($last_name, "text"),
								   GetSQLValueString(filter_var($_POST['brewerEmail'],FILTER_SANITIZE_EMAIL), "text"),
								   GetSQLValueString($brewerAHA, "text"),
								   GetSQLValueString($prefix, "text"));
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

				}
			}

			else {
				$insertSQL = sprintf("INSERT INTO $brewer_db_table (
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
				  brewerSteward,
				  brewerJudge,
				  brewerJudgeID,

				  brewerJudgeMead,
				  brewerJudgeRank,
				  brewerJudgeLocation,
				  brewerStewardLocation,
				  brewerDropOff,

				  brewerJudgeExp,
				  brewerJudgeNotes,
				  brewerStaff,
				  brewerBreweryName,
				  brewerBreweryTTB,

				  brewerJudgeCider,
				  brewerProAm

				) VALUES (
				%s, %s, %s, %s, %s,
				%s, %s, %s, %s, %s,
				%s, %s, %s, %s, %s,
				%s, %s, %s, %s, %s,
				%s, %s, %s, %s, %s,
				%s, %s)",
							   GetSQLValueString($_POST['uid'], "int"),
							   GetSQLValueString($first_name, "text"),
							   GetSQLValueString($last_name, "text"),
							   GetSQLValueString($address, "text"),
							   GetSQLValueString($city, "text"),
							   GetSQLValueString($state, "text"),
							   GetSQLValueString(sterilize($_POST['brewerZip']), "text"),
							   GetSQLValueString($_POST['brewerCountry'], "text"),
							   GetSQLValueString($_POST['brewerPhone1'], "text"),
							   GetSQLValueString($brewerPhone2, "text"),
							   GetSQLValueString($brewerClubs, "text"),
							   GetSQLValueString(filter_var($_POST['brewerEmail'],FILTER_SANITIZE_EMAIL), "text"),
							   GetSQLValueString($brewerSteward, "text"),
							   GetSQLValueString($brewerJudge, "text"),
							   GetSQLValueString($brewerJudgeID, "text"),
							   GetSQLValueString($brewerJudgeMead, "text"),
							   GetSQLValueString($rank, "text"),
							   GetSQLValueString($location_pref1, "text"),
							   GetSQLValueString($location_pref2, "text"),
							   GetSQLValueString($brewerDropOff, "int"),
							   GetSQLValueString($brewerJudgeExp, "text"),
							   GetSQLValueString($brewerJudgeNotes, "text"),
							   GetSQLValueString($brewerStaff, "text"),
							   GetSQLValueString($brewerBreweryName, "text"),
							   GetSQLValueString($brewerBreweryTTB, "text"),
							   GetSQLValueString($brewerJudgeCider, "text"),
							   GetSQLValueString($_POST['brewerProAm'], "int"),
							   );
			}
			//echo $insertSQL;
			//exit;
			mysqli_real_escape_string($connection,$insertSQL);
			$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));

			if ($section == "setup") {

				// Check to see if processed correctly.
				$query_brewer_check = sprintf("SELECT COUNT(*) as 'count' FROM %s",$brewer_db_table);
				$brewer_check = mysqli_query($connection,$query_brewer_check) or die (mysqli_error($connection));
				$row_brewer_check = mysqli_fetch_assoc($brewer_check);

				// If so, mark step as complete in system table and redirect to next step.
				if ($row_brewer_check['count'] == 1) {

					$sql = sprintf("UPDATE `%s` SET setup_last_step = '2' WHERE id='1';", $system_db_table);
					mysqli_select_db($connection,$database);
					mysqli_real_escape_string($connection,$sql);
					$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

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

		$pattern = array('\'', '"');
		$insertGoTo = str_replace($pattern, "", $insertGoTo);
		$redirect_go_to = sprintf("Location: %s", stripslashes($insertGoTo));

		}

	} // end if ($action == "add")

	// --------------------------------------- Editing a Participant ----------------------------------------
	if ($action == "edit") {

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
						$updateSQL = sprintf("DELETE FROM %s WHERE id=%s",$prefix."staff",$row_staff_assign['id']);
						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
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
						$updateSQL = sprintf("DELETE FROM %s WHERE id=%s",$prefix."judging_assignments",$row_table_assign['id']);
						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
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
						$updateSQL = sprintf("DELETE FROM %s WHERE id=%s",$prefix."staff",$row_staff_assign['id']);
						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
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
						$updateSQL = sprintf("DELETE FROM %s WHERE id=%s",$prefix."judging_assignments",$row_table_assign['id']);
						mysqli_real_escape_string($connection,$updateSQL);
						$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
					}

				} while ($row_table_assign = mysqli_fetch_assoc($table_assign));

			}

		}

		if (isset($brewerJudgeWaiver)) $judgeWaiver = $brewerJudgeWaiver;
		else $judgeWaiver = "N";

		$updateSQL = sprintf("UPDATE $brewer_db_table SET
			uid=%s,
			brewerFirstName=%s,
			brewerLastName=%s,
			brewerAddress=%s,
			brewerCity=%s,
			brewerState=%s,

			brewerZip=%s,
			brewerCountry=%s,
			brewerPhone1=%s,
			brewerPhone2=%s,
			brewerClubs=%s,
			brewerEmail=%s,

			brewerSteward=%s,
			brewerJudge=%s,
			brewerJudgeID=%s,
			brewerJudgeMead=%s,
			brewerJudgeRank=%s,
			brewerJudgeLikes=%s,
			brewerJudgeDislikes=%s,
			brewerJudgeLocation=%s,
			brewerStewardLocation=%s,
			brewerDropOff=%s,
			brewerJudgeExp=%s,
			brewerJudgeNotes=%s,
			brewerJudgeWaiver=%s,
			brewerStaff=%s,
			brewerBreweryName=%s,
			brewerBreweryTTB=%s,
			brewerJudgeCider=%s,
			brewerProAm=%s
			",
							   GetSQLValueString(sterilize($_POST['uid']), "int"),
							   GetSQLValueString($first_name, "text"),
							   GetSQLValueString($last_name, "text"),
							   GetSQLValueString($address, "text"),
							   GetSQLValueString($city, "text"),
							   GetSQLValueString($state, "text"),
							   GetSQLValueString(sterilize($_POST['brewerZip']), "text"),
							   GetSQLValueString($_POST['brewerCountry'], "text"),
							   GetSQLValueString($_POST['brewerPhone1'], "text"),
							   GetSQLValueString($brewerPhone2, "text"),
							   GetSQLValueString($brewerClubs, "text"),
							   GetSQLValueString(filter_var($_POST['brewerEmail'],FILTER_SANITIZE_EMAIL), "text"),
							   GetSQLValueString($brewerSteward, "text"),
							   GetSQLValueString($brewerJudge, "text"),
							   GetSQLValueString($brewerJudgeID, "text"),
							   GetSQLValueString($brewerJudgeMead, "text"),
							   GetSQLValueString($rank, "text"),
							   GetSQLValueString($likes, "text"),
							   GetSQLValueString($dislikes, "text"),
							   GetSQLValueString($location_pref1, "text"),
							   GetSQLValueString($location_pref2, "text"),
							   GetSQLValueString($brewerDropOff, "int"),
							   GetSQLValueString($brewerJudgeExp, "text"),
							   GetSQLValueString($brewerJudgeNotes, "text"),
							   GetSQLValueString($judgeWaiver, "text"),
							   GetSQLValueString($brewerStaff, "text"),
							   GetSQLValueString($brewerBreweryName, "text"),
							   GetSQLValueString($brewerBreweryTTB, "text"),
							   GetSQLValueString($brewerJudgeCider, "text"),
							   GetSQLValueString($_POST['brewerProAm'], "int")
							   );
		// Numbers 999999994 through 999999999 are reserved for NHC applications.
		if (($brewerAHA < "999999994") || ($brewerAHA == "")) {
			$updateSQL .= sprintf(", brewerAHA=%s",GetSQLValueString($brewerAHA, "text"));
		}
		$updateSQL .= sprintf(" WHERE id=%s",GetSQLValueString($id, "int"));
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		//echo $updateSQL."<br>";
		//exit;

		if (isset($_POST['userQuestion'])) {
			$updateSQL = sprintf("UPDATE $users_db_table SET userQuestion=%s WHERE id=%s",GetSQLValueString($purifier->purify($_POST['userQuestion']),"text"),GetSQLValueString($_SESSION['user_id'],"int"));
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		}

		if (isset($_POST['userQuestionAnswer'])) {
			$userQuestionAnswer = $purifier->purify($_POST['userQuestionAnswer']);
			$userQuestionAnswer = filter_var($userQuestionAnswer,FILTER_SANITIZE_STRING);

			/*
			require(CLASSES.'phpass/PasswordHash.php');
			$hasher_question = new PasswordHash(8, false);
			$hash_question = $hasher_question->HashPassword($userQuestionAnswer);
			*/

			$updateSQL = sprintf("UPDATE $users_db_table SET userQuestionAnswer=%s WHERE id=%s",GetSQLValueString($userQuestionAnswer,"text"),GetSQLValueString($_SESSION['user_id'],"int"));
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		}

		$updateSQL = sprintf("UPDATE $users_db_table SET userCreated=%s WHERE id=%s",
						   	"NOW( )",
							GetSQLValueString($_POST['uid'], "text")
							);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		if ($go == "register") $updateGoTo = $base_url."index.php?section=brew&msg=2";
		elseif (($go == "judge") && ($filter == "default")) $updateGoTo = $base_url."index.php?section=list&go=".$go."&filter=default&msg=7";
		elseif (($go == "judge") && ($filter != "default")) $updateGoTo = $base_url."index.php?section=admin&go=participants&msg=2";
		elseif ($go == "default") $updateGoTo = $base_url."index.php?section=list&go=".$go."&filter=default&msg=2";
		else $updateGoTo = $updateGoTo;

		$pattern = array('\'', '"');
		$updateGoTo = str_replace($pattern, "", $updateGoTo);

		$redirect_go_to = sprintf("Location: %s", stripslashes($updateGoTo));

	}

} else {

	$redirect_go_to = sprintf("Location: %s", $base_url."index.php?msg=98");

}
?>
