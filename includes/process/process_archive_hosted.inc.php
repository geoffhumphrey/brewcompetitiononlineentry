<?php
if ((isset($_SERVER['HTTP_REFERER'])) && ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] == 0))) {

	session_name($prefix_session);
	session_start();

	require(INCLUDES.'scrubber.inc.php');
	require(INCLUDES.'db_tables.inc.php');

	$dbTable = "default";

	$tables_array = array($special_best_info_db_table, $special_best_data_db_table, $brewing_db_table, $judging_assignments_db_table, $judging_flights_db_table, $judging_scores_db_table, $judging_scores_bos_db_table, $judging_tables_db_table, $staff_db_table);

	foreach ($tables_array as $table) {

		$updateSQL = "TRUNCATE ".$table.";";
		if ($table == $brwer_db_table)
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

	}

	// Delete any uploaded scoresheets in the user_docs directory
	rdelete(USER_DOCS);

	// If not retaining participant data, but keeping current admins
	if ($filter == "default") {

		$query_admin = sprintf("SELECT id,user_name FROM %s WHERE userLevel = '2'", $users_db_table);
		$admin = mysqli_query($connection,$query_admin) or die (mysqli_error($connection));
		$row_admin = mysqli_fetch_assoc($admin);
		$totalRows_admin = mysqli_num_rows($admin);

		if ($totalRows_admin > 0) {

			do {

				// Delete the user's record
				$updateSQL = sprintf("DELETE FROM %s WHERE uid='%s'", $brewer_db_table, $row_admin['id']);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

				// Delete the associated brewer record
				$updateSQL = sprintf("DELETE FROM %s WHERE id='%s'", $users_db_table, $row_admin['id']);
				mysqli_real_escape_string($connection,$updateSQL);
				$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection))

			} while($row_admin = mysqli_fetch_assoc($admin));

		} // if ($totalRows_admin > 0)

		// Clean up any strays
		// Brewer table
		$query_brewer_strays = sprintf("SELECT id,uid FROM %s", $brewer_db_table);
		$brewer_strays = mysqli_query($connection,$query_brewer_strays) or die (mysqli_error($connection));
		$row_brewer_strays = mysqli_fetch_assoc($brewer_strays);
		$totalRows_brewer_strays = mysqli_num_rows($brewer_strays);

		if ($totalRows_brewer_strays > 0) {

			do {
				$query_stray = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE id='%s'", $users_db_table, $row_brewer_strays['id']);
				$stray = mysqli_query($connection,$query_stray) or die (mysqli_error($connection));
				$row_stray = mysqli_fetch_assoc($stray);

				if ($row_stray['count'] == 0) {
					$updateSQL = sprintf("DELETE FROM %s WHERE id='%s'", $brewer_db_table, $row_brewer_strays['id']);
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				}

			} while ($row_brewer_strays = mysqli_fetch_assoc($brewer_strays));

		}

		// User table
		$query_user_strays = sprintf("SELECT id FROM %s", $users_db_table);
		$user_strays = mysqli_query($connection,$query_user_strays) or die (mysqli_error($connection));
		$row_user_strays = mysqli_fetch_assoc($user_strays);
		$totalRows_user_strays = mysqli_num_rows($user_strays);

		if ($totalRows_user_strays > 0) {

			// Users table
			do {
				$query_stray = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE uid='%s'", $brewer_db_table, $row_user_strays['id']);
				$stray = mysqli_query($connection,$query_stray) or die (mysqli_error($connection));
				$row_stray = mysqli_fetch_assoc($stray);

				if ($row_stray['count'] == 0) {
					$updateSQL = sprintf("DELETE FROM %s WHERE id='%s'", $users_db_table, $row_user_strays['id']);
					mysqli_real_escape_string($connection,$updateSQL);
					$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
				}

			} while ($row_user_strays = mysqli_fetch_assoc($user_strays));

		}

		$query_check_gh = sprintf("SELECT id FROM %s WHERE user_name='%s'", $users_db_table, $gh_user_name);
		$check_gh = mysqli_query($connection,$query_check_gh) or die (mysqli_error($connection));
		$row_check_gh = mysqli_fetch_assoc($check_gh);
		$totalRows_check_gh = mysqli_num_rows($check_gh);

		if ($totalRows_check_gh == 0) {

			$gh_user_name = "geoff@zkdigital.com";
			$gh_password = "d9efb18ba2bc4a434ddf85013dbe58f8";
			$random1 = random_generator(7,2);
			$random2 = random_generator(7,2);
			require(CLASSES.'phpass/PasswordHash.php');
			$hasher = new PasswordHash(8, false);
			$hash = $hasher->HashPassword($gh_password);

			$updateSQL = sprintf("
				INSERT INTO `%s` (`user_name`, `password`, `userLevel`, `userQuestion`, `userQuestionAnswer`,`userCreated`)
				VALUES ('%s', '%s', '0', '%s', '%s', NOW());",
				$gh_user_name,$users_db_table,$hash,$random1,$random2);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

			$query_gh_admin_user1 = sprintf("SELECT id FROM %s WHERE user_name='%s'",$users_db_table,$gh_user_name);
			$gh_admin_user1 = mysqli_query($connection,$query_gh_admin_user1) or die (mysqli_error($connection));
			$row_gh_admin_user1 = mysqli_fetch_assoc($gh_admin_user1);

			$updateSQL = sprintf("
				INSERT INTO `%s` (`uid`, `brewerFirstName`, `brewerLastName`, `brewerAddress`, `brewerCity`, `brewerState`, `brewerZip`, `brewerCountry`, `brewerPhone1`, `brewerPhone2`, `brewerClubs`, `brewerEmail`, `brewerStaff`, `brewerSteward`, `brewerJudge`, `brewerJudgeID`, `brewerJudgeRank`, `brewerJudgeLikes`, `brewerJudgeDislikes`, `brewerJudgeLocation`, `brewerStewardLocation`, `brewerJudgeExp`, `brewerJudgeNotes`, `brewerAssignment`, `brewerAHA`)
				VALUES ('%s', 'Geoff', 'Humphrey', '1234 Main Street', 'Anytown', 'CO', '80000', 'United States', '303-555-5555', '303-555-5555', 'Rock Hoppers Brew Club', '%s', 'N', 'N', 'N', 'A0000', 'Certified', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 000000);",
				$brewer_db_table,$row_gh_admin_user1['id'],$gh_user_name);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		} // end if ($totalRows_check_gh == 0)

	}

	$redirect_go_to = sprintf("Location: %s", $base_url."index.php?section=admin&go=archive&msg=7");

} else {
	$redirect_go_to = sprintf("Location: %s", $base_url."index.php?msg=98");
}
?>