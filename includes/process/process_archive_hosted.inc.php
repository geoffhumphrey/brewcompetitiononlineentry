<?php
if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] == 0))) {

	//session_name($prefix_session);
	//session_start();

	require(INCLUDES.'scrubber.inc.php');
	require(INCLUDES.'db_tables.inc.php');

	$dbTable = "default";
	$gh_user_name = "geoff@zkdigital.com";

	$tables_array = array($special_best_info_db_table, $special_best_data_db_table, $brewing_db_table, $judging_assignments_db_table, $judging_flights_db_table, $judging_scores_db_table, $judging_scores_bos_db_table, $judging_tables_db_table, $staff_db_table);

	if (check_setup($prefix."evaluation",$database)) {
		$tables_array[] = $prefix."evaluation";
	}

	foreach ($tables_array as $table) {

		$updateSQL = "TRUNCATE ".$table.";";
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		//echo $updateSQL."<br>";

	}

	// Delete any uploaded scoresheets in the user_docs directory
	rdelete(USER_DOCS,"");

	// Reset judge, steward, and staff interest and availability
	$updateSQL = sprintf("UPDATE %s SET brewerStaff = 'N', brewerSteward = 'N', brewerJudge = 'N', brewerJudgeLocation = NULL, brewerStewardLocation = NULL", $prefix."brewer");
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

	// If not retaining participant data, keep current admins
	if ($filter == "default") {

		$query_admin = sprintf("SELECT id, userLevel, user_name FROM %s WHERE userLevel = '2'", $prefix."users");
		$admin = mysqli_query($connection,$query_admin) or die (mysqli_error($connection));
		$row_admin = mysqli_fetch_assoc($admin);
		$totalRows_admin = mysqli_num_rows($admin);

		if ($totalRows_admin > 0) {

			do {

				// Delete the user's record
				$updateSQL = sprintf("DELETE FROM %s WHERE uid='%s'", $prefix."brewer", $row_admin['id']);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

				// Delete the associated brewer record
				$updateSQL = sprintf("DELETE FROM %s WHERE id='%s'", $prefix."users", $row_admin['id']);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			} while($row_admin = mysqli_fetch_assoc($admin));

		} // if ($totalRows_admin > 0)

	}

	// Check for GH
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

		$updateSQL = sprintf("
			INSERT INTO `%s` (`user_name`, `password`, `userLevel`, `userQuestion`, `userQuestionAnswer`,`userCreated`)
			VALUES ('%s', '%s', '0', '%s', '%s', NOW());",
			$users_db_table,$gh_user_name,$hash,$random1,$random2);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		$query_gh_admin_user1 = sprintf("SELECT id FROM %s WHERE user_name='%s'",$users_db_table,$gh_user_name);
		$gh_admin_user1 = mysqli_query($connection,$query_gh_admin_user1) or die (mysqli_error($connection));
		$row_gh_admin_user1 = mysqli_fetch_assoc($gh_admin_user1);

		$updateSQL = sprintf("
			INSERT INTO `%s` (`uid`, `brewerFirstName`, `brewerLastName`, `brewerAddress`, `brewerCity`, `brewerState`, `brewerZip`, `brewerCountry`, `brewerPhone1`, `brewerPhone2`, `brewerClubs`, `brewerEmail`, `brewerStaff`, `brewerSteward`, `brewerJudge`, `brewerJudgeID`, `brewerJudgeRank`, `brewerAHA`)
			VALUES ('%s', 'Geoff', 'Humphrey', '1234 Main Street', 'Anytown', 'CO', '80000', 'United States', '303-555-5555', '303-555-5555', 'Rock Hoppers Brew Club', '%s', 'N', 'N', 'N', 'A0000', 'Certified', 000000);",
			$brewer_db_table,$row_gh_admin_user1['id'],$gh_user_name);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

	} // end if ($totalRows_check_gh == 0)

	$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&go=archive&msg=7");

} else {
	$redirect_go_to = sprintf("Location: %s", $base_url."index.php?msg=98");
}
?>