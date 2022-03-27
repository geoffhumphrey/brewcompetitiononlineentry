<?php
$error_output = array();
$_SESSION['error_output'] = "";

if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] == 0))) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

	require(INCLUDES.'scrubber.inc.php');
	require(INCLUDES.'db_tables.inc.php');

	$dbTable = "default";

	$tables_array = array($special_best_info_db_table, $special_best_data_db_table, $brewing_db_table, $judging_assignments_db_table, $judging_flights_db_table, $judging_scores_db_table, $judging_scores_bos_db_table, $judging_tables_db_table, $staff_db_table);

	if (check_setup($prefix."evaluation",$database)) {
		$tables_array[] = $prefix."evaluation";
	}

	foreach ($tables_array as $table) {

		$sql = "TRUNCATE ".$table.";";
		$result = $db_conn->rawQuery($sql);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

	}

	// Delete any uploaded scoresheets in the user_docs directory
	rdelete(USER_DOCS,"");

	// Reset judge, steward, and staff interest and availability
	$update_table = $prefix."brewer";
	$data = array(
		'brewerJudge' => 'N',
		'brewerSteward' => 'N',
		'brewerJudgeLocation' => NULL,
		'brewerStewardLocation' => NULL
	);
	$result = $db_conn->update ($update_table, $data);
	if (!$result) {
		$error_output[] = $db_conn->getLastError();
		$errors = TRUE;
	}

	// If not retaining participant data, keep current admins
	if ($filter == "default") {

		$query_admin = sprintf("SELECT id FROM %s WHERE userLevel < '2'", $prefix."users");
		$admin = mysqli_query($connection,$query_admin) or die (mysqli_error($connection));
		$row_admin = mysqli_fetch_assoc($admin);
		$totalRows_admin = mysqli_num_rows($admin);

		$admin_ids = array();

		if ($totalRows_admin > 0) {

			do {
				$admin_ids[] = $row_admin['id'];
			} while($row_admin = mysqli_fetch_assoc($admin));

		}

		$query_non_admin = sprintf("SELECT id FROM %s WHERE userLevel='2'", $prefix."users");
		$non_admin = mysqli_query($connection,$query_non_admin) or die (mysqli_error($connection));
		$row_non_admin = mysqli_fetch_assoc($non_admin);
		$totalRows_non_admin = mysqli_num_rows($non_admin);

		if ($totalRows_non_admin > 0) {

			do {

				if (!in_array($row_non_admin['id'],$admin_ids)) {

					// Delete the user's record
					$update_table = $prefix."users";
					$db_conn->where ('id', $row_non_admin['id']);
					$result = $db_conn->delete($update_table);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

					// Delete the associated brewer's record
					$update_table = $prefix."brewer";
					$db_conn->where ('uid', $row_non_admin['id']);
					$result = $db_conn->delete($update_table);
					if (!$result) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

				} // end if (!in_array($row_non_admin['id'],$admin_ids))

			} while ($row_non_admin = mysqli_fetch_assoc($non_admin));

		} // end if ($totalRows_non_admin > 0)

	} // end if ($filter == "default")

	// Check for GH
	$gh_user_name = "geoff@zkdigital.com";
	$query_check_gh = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE user_name='%s'", $prefix."users", $gh_user_name);
	$check_gh = mysqli_query($connection,$query_check_gh) or die (mysqli_error($connection));
	$row_check_gh = mysqli_fetch_assoc($check_gh);

	if ($row_check_gh['count'] == 0) {

		$gh_password = "d9efb18ba2bc4a434ddf85013dbe58f8";
		$random1 = random_generator(7,2);
		$random2 = random_generator(7,2);
		require(CLASSES.'phpass/PasswordHash.php');
		$hasher = new PasswordHash(8, false);
		$hash = $hasher->HashPassword($gh_password);

		$update_table = $prefix."users";
		$data = array(
			'user_name' => $gh_user_name,
			'password' => $hash,
			'userLevel' => '0',
			'userQuestion' => $random1,
			'userQuestionAnswer' => $random2,
			'userCreated' => $db_conn->now()
		);
		$result = $db_conn->insert ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		$query_gh_admin_user1 = sprintf("SELECT id FROM %s WHERE user_name='%s'", $prefix."users", $gh_user_name);
		$gh_admin_user1 = mysqli_query($connection,$query_gh_admin_user1) or die (mysqli_error($connection));
		$row_gh_admin_user1 = mysqli_fetch_assoc($gh_admin_user1);

		$update_table = $prefix."brewer";
		$data = array('id' => '1',
			'uid' => $row_gh_admin_user1['id'],
			'brewerFirstName' => 'Geoff',
			'brewerLastName' => 'Humphrey',
			'brewerAddress' => '1234 Main Street',
			'brewerCity' => 'Denver',
			'brewerState' => 'CO',
			'brewerZip' => '80001',
			'brewerCountry' => 'United States',
			'brewerPhone1' => '303-555-5555',
			'brewerPhone2' => '303-555-5555',
			'brewerClubs' => 'Rock Hoppers Brew Club',
			'brewerEmail' => $gh_user_name,
			'brewerStaff' => 'N',
			'brewerSteward' => 'N',
			'brewerJudge' => 'N',
			'brewerJudgeWaiver' => 'Y',
			'brewerProAm' => '0',
			'brewerDropOff' => '0'
		);		
		$db_conn->where ('id', $id);
		$result = $db_conn->update ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

	} // end if ($totalRows_check_gh == 0)

	if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

	$redirect = $base_url."index.php?section=admin&go=archive&msg=7";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);

} else {

	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);

}
?>