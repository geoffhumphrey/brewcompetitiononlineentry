<?php
/*
 * Module:      process_comp_info.inc.php
 * Description: This module does all the heavy lifting for adding/editing information in the
 *              "contest_info" table.
 */
if ((isset($_SERVER['HTTP_REFERER'])) && (((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] == 0)) || ($section == "setup"))) {

	// Instantiate HTMLPurifier
	require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
	$config_html_purifier = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config_html_purifier);

	// Constants
	if ($go == "default") {
		$contestRegistrationOpen = strtotime(filter_var($_POST['contestRegistrationOpen'],FILTER_SANITIZE_STRING));
		$contestRegistrationDeadline = strtotime(filter_var($_POST['contestRegistrationDeadline'],FILTER_SANITIZE_STRING));
		$contestEntryOpen = strtotime(filter_var($_POST['contestEntryOpen'],FILTER_SANITIZE_STRING));
		$contestEntryDeadline = strtotime(filter_var($_POST['contestEntryDeadline'],FILTER_SANITIZE_STRING));
		$contestJudgeOpen = strtotime(filter_var($_POST['contestJudgeOpen'],FILTER_SANITIZE_STRING));
		$contestJudgeDeadline = strtotime(filter_var($_POST['contestJudgeDeadline'],FILTER_SANITIZE_STRING));
		$contestAwardsLocDate = strtotime(filter_var($_POST['contestAwardsLocDate'],FILTER_SANITIZE_STRING));
		$contestShippingOpen = strtotime(filter_var($_POST['contestShippingOpen'],FILTER_SANITIZE_STRING));
		$contestShippingDeadline = strtotime(filter_var($_POST['contestShippingDeadline'],FILTER_SANITIZE_STRING));
		$contestDropoffOpen = strtotime(filter_var($_POST['contestDropoffOpen'],FILTER_SANITIZE_STRING));
		$contestDropoffDeadline = strtotime(filter_var($_POST['contestDropoffDeadline'],FILTER_SANITIZE_STRING));
		$contestHostWebsite = check_http(filter_var($_POST['contestHostWebsite'],FILTER_SANITIZE_STRING));
	}

	/** DEBUG
	    echo $contestRegistrationOpen."<br>"; echo $contestRegistrationDeadline."<br>"; echo $contestEntryOpen ."<br>"; echo $contestEntryDeadline."<br>"; echo $judgingDate."<br>";
	    echo "<br>".$tz; echo "<br>".$timezone_offset; echo "<br>".$_SESSION['prefsTimeZone'];
	 **/

	if (NHC) {
		// Place NHC SQL calls below


	}
	// end if (NHC)

	else {

	$contestBottles = "";
	$contestShippingName = "";
	$contestShippingAddress = "";
	$contestAwards = "";
	$contestRules = "";
	$contestHostLocation = "";
	$contestHost = "";
	$contestName = "";
	$contestAwardsLocName = "";
	$contestAwardsLocation = "";
	$contestBOSAward = "";
	$contestEntryFeePassword = "";
	$contestCircuit = "";
	$contestVolunteers = "";
	$contestCheckInPassword = "";
	$contestLogo = "";

	if (isset($_POST['contestBottles'])) $contestBottles = $purifier->purify($_POST['contestBottles']);
	if (isset($_POST['contestShippingName'])) $contestShippingName = $purifier->purify($_POST['contestShippingName']);
	if (isset($_POST['contestShippingAddress'])) $contestShippingAddress = $purifier->purify($_POST['contestShippingAddress']);
	if (isset($_POST['contestAwards'])) $contestAwards = $purifier->purify($_POST['contestAwards']);
	if (isset($_POST['contestRules'])) $contestRules = $purifier->purify($_POST['contestRules']);
	if (isset($_POST['contestHostLocation'])) $contestHostLocation = $purifier->purify($_POST['contestHostLocation']);
	if (isset($_POST['contestHost'])) $contestHost = $purifier->purify($_POST['contestHost']);
	if (isset($_POST['contestName'])) $contestName = $purifier->purify($_POST['contestName']);
	if (isset($_POST['contestAwardsLocName'])) $contestAwardsLocName = $purifier->purify($_POST['contestAwardsLocName']);
	if (isset($_POST['contestAwardsLocation'])) $contestAwardsLocation = $purifier->purify($_POST['contestAwardsLocation']);
	if (isset($_POST['contestBOSAward'])) $contestBOSAward = $purifier->purify($_POST['contestBOSAward']);
	if (isset($_POST['contestEntryFeePassword'])) $contestEntryFeePassword = sterilize($_POST['contestEntryFeePassword']);
	if (isset($_POST['contestCircuit'])) $contestCircuit = $purifier->purify($_POST['contestCircuit']);
	if (isset($_POST['contestVolunteers'])) $contestVolunteers = $purifier->purify($_POST['contestVolunteers']);
	if (isset($_POST['contestCheckInPassword'])) $contestCheckInPassword = sterilize($_POST['contestCheckInPassword']);
	if (isset($_POST['contestLogo'])) $contestLogo = $purifier->purify($_POST['contestLogo']);
	if ((empty($_POST['contestEntryFee2'])) || (empty($_POST['contestEntryFeeDiscountNum']))) $contestEntryFeeDiscount = "N";
	if ((!empty($_POST['contestEntryFee2'])) && (!empty($_POST['contestEntryFeeDiscountNum']))) $contestEntryFeeDiscount = "Y";

	// --------------------------------------- Adding (SETUP ONLY) ----------------------------------------

	if ($action == "add") {

		if (isset($_POST['contestCheckInPassword'])) {
			require(CLASSES.'phpass/PasswordHash.php');
			$hasher = new PasswordHash(8, false);
			$password = md5(sterilize($_POST['contestCheckInPassword']));
			$hash = $hasher->HashPassword($password);
		}

		$insertSQL = sprintf("INSERT INTO $contest_info_db_table (
		contestName,
		contestID,
		contestHost,
		contestHostWebsite,
		contestHostLocation,

		contestRegistrationOpen,
		contestRegistrationDeadline,
		contestEntryOpen,
		contestEntryDeadline,
		contestJudgeOpen,

		contestJudgeDeadline,
		contestRules,
		contestAwardsLocation,

		contestEntryFee,
		contestBottles,
		contestShippingAddress,
		contestShippingName,
		contestAwards,
		contestDropoffOpen,
		contestDropoffDeadline,
		contestEntryCap,
		contestAwardsLocName,
		contestAwardsLocTime,

		contestEntryFee2,
		contestEntryFeeDiscount,
		contestEntryFeeDiscountNum,
		contestLogo,
		contestBOSAward,

		contestEntryFeePassword,
		contestEntryFeePasswordNum,
		contestCircuit,
		contestVolunteers,
		contestShippingOpen,
		contestShippingDeadline,
		contestCheckInPassword,
		id
		)
		VALUES
		(
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s)",
							   GetSQLValueString($contestName, "text"),
							   GetSQLValueString(filter_var($_POST['contestID'],FILTER_SANITIZE_NUMBER_INT), "text"),
							   GetSQLValueString($contestHost, "text"),
							   GetSQLValueString($contestHostWebsite, "text"),
							   GetSQLValueString($contestHostLocation, "text"),
							   GetSQLValueString($contestRegistrationOpen, "text"),
							   GetSQLValueString($contestRegistrationDeadline, "text"),
							   GetSQLValueString($contestEntryOpen, "text"),
							   GetSQLValueString($contestEntryDeadline, "text"),
							   GetSQLValueString($contestJudgeOpen, "text"),
							   GetSQLValueString($contestJudgeDeadline, "text"),
							   GetSQLValueString($contestRules, "text"),
							   GetSQLValueString($contestAwardsLocation, "text"),
							   GetSQLValueString(filter_var($_POST['contestEntryFee'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION), "text"),
							   GetSQLValueString($contestBottles, "text"),
							   GetSQLValueString($contestShippingAddress, "text"),
							   GetSQLValueString($contestShippingName, "text"),
							   GetSQLValueString($contestAwards, "text"),
							   GetSQLValueString($contestDropoffOpen, "text"),
							   GetSQLValueString($contestDropoffDeadline, "text"),
							   GetSQLValueString(filter_var($_POST['contestEntryCap'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION), "text"),
							   GetSQLValueString($contestAwardsLocName, "text"),
							   GetSQLValueString($contestAwardsLocDate, "text"),
							   GetSQLValueString(filter_var($_POST['contestEntryFee2'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION), "text"),
							   GetSQLValueString($contestEntryFeeDiscount, "text"),
							   GetSQLValueString(filter_var($_POST['contestEntryFeeDiscountNum'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION), "text"),
							   GetSQLValueString($contestLogo, "text"),
							   GetSQLValueString($contestBOSAward, "text"),
							   GetSQLValueString($contestEntryFeePassword, "text"),
							   GetSQLValueString(filter_var($_POST['contestEntryFeePasswordNum'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION), "text"),
							   GetSQLValueString($contestCircuit, "text"),
							   GetSQLValueString($contestVolunteers, "text"),
							   GetSQLValueString($contestShippingOpen, "text"),
							   GetSQLValueString($contestShippingDeadline, "text"),
							   GetSQLValueString($hash, "text"),
							   GetSQLValueString($id, "int"));

		  	mysqli_real_escape_string($connection,$insertSQL);
			$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));
		  	//echo $insertSQL."<br>";

		  	$insertSQL = sprintf("INSERT INTO $contacts_db_table (
			contactFirstName,
			contactLastName,
			contactPosition,
			contactEmail
			)
			VALUES
			(%s, %s, %s, %s)",
							   GetSQLValueString(sterilize($_POST['contactFirstName']), "text"),
							   GetSQLValueString(sterilize($_POST['contactLastName']), "text"),
							   GetSQLValueString(sterilize($_POST['contactPosition']), "text"),
							   GetSQLValueString(sterilize($_POST['contactEmail']), "text"));

			mysqli_real_escape_string($connection,$insertSQL);
			$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));

			// Check to see if processed correctly.
			$query_comp_info_check = sprintf("SELECT COUNT(*) as 'count' FROM %s",$contest_info_db_table);
			$comp_info_check = mysqli_query($connection,$query_comp_info_check) or die (mysqli_error($connection));
			$row_comp_info_check = mysqli_fetch_assoc($comp_info_check);

			// If so, mark step as complete in system table and redirect to next step.
			if ($row_comp_info_check['count'] == 1) {

				$sql = sprintf("UPDATE `%s` SET setup_last_step = '4' WHERE id='1';", $system_db_table);
				mysqli_select_db($connection,$database);
				mysqli_real_escape_string($connection,$sql);
				$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));
				$insertGoTo = $base_url."setup.php?section=step5";

			}

			// If not, redirect back to step 4 and display message.
			else  $insertGoTo = $base_url."setup.php?section=step4&msg=99";

			$pattern = array('\'', '"');
			$insertGoTo = str_replace($pattern, "", $insertGoTo);
			$redirect_go_to = sprintf("Location: %s", stripslashes($insertGoTo));
	}

	// --------------------------------------- Editing  ----------------------------------------
	if ($action == "edit") {

		if ($go == "qr") {

			if (isset($_POST['contestCheckInPassword'])) {

				require(CLASSES.'phpass/PasswordHash.php');
				$hasher = new PasswordHash(8, false);
				$password = md5(sterilize($_POST['contestCheckInPassword']));
				$hash = $hasher->HashPassword($password);

			}

			else $hash = "";

			$updateSQL = sprintf("UPDATE $contest_info_db_table SET contestCheckInPassword=%s WHERE id=%s", GetSQLValueString($hash, "text"), GetSQLValueString($id, "int"));

		}

		else {
			// Empty the contest_info_general session variable
			// Will trigger the session to reset the variables in common.db.php upon reload after redirect
			session_name($prefix_session);
			session_start();
			unset($_SESSION['contest_info_general'.$prefix_session]);

			$updateSQL = sprintf("UPDATE $contest_info_db_table SET
			contestName=%s,
			contestID=%s,
			contestHost=%s,
			contestHostWebsite=%s,
			contestHostLocation=%s,
			contestRegistrationOpen=%s,
			contestRegistrationDeadline=%s,
			contestEntryOpen=%s,
			contestEntryDeadline=%s,
			contestJudgeOpen=%s,
			contestJudgeDeadline=%s,
			contestRules=%s,
			contestAwardsLocation=%s,

			contestEntryFee=%s,
			contestBottles=%s,
			contestShippingAddress=%s,
			contestShippingName=%s,

			contestAwards=%s,
			contestDropoffOpen=%s,
			contestDropoffDeadline=%s,
			contestEntryCap=%s,
			contestAwardsLocName=%s,

			contestAwardsLocTime=%s,
			contestEntryFee2=%s,
			contestEntryFeeDiscount=%s,
			contestEntryFeeDiscountNum=%s,
			contestLogo=%s,
			contestBOSAward=%s,
			contestEntryFeePassword=%s,
			contestEntryFeePasswordNum=%s,
			contestCircuit=%s,
			contestVolunteers=%s,
			contestShippingOpen=%s,
			contestShippingDeadline=%s
			WHERE id=%s",



			GetSQLValueString($contestName, "text"),
			GetSQLValueString(filter_var($_POST['contestID'],FILTER_SANITIZE_NUMBER_INT), "text"),
			GetSQLValueString($contestHost, "text"),
			GetSQLValueString($contestHostWebsite, "text"),
			GetSQLValueString($contestHostLocation, "text"),
			GetSQLValueString($contestRegistrationOpen, "text"),
			GetSQLValueString($contestRegistrationDeadline, "text"),
			GetSQLValueString($contestEntryOpen, "text"),
			GetSQLValueString($contestEntryDeadline, "text"),
			GetSQLValueString($contestJudgeOpen, "text"),
			GetSQLValueString($contestJudgeDeadline, "text"),
			GetSQLValueString($contestRules, "text"),
			GetSQLValueString($contestAwardsLocation, "text"),
			GetSQLValueString(filter_var($_POST['contestEntryFee'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION), "text"),
			GetSQLValueString($contestBottles, "text"),
			GetSQLValueString($contestShippingAddress, "text"),
			GetSQLValueString($contestShippingName, "text"),
			GetSQLValueString($contestAwards, "text"),
			GetSQLValueString($contestDropoffOpen, "text"),
			GetSQLValueString($contestDropoffDeadline, "text"),
			GetSQLValueString(filter_var($_POST['contestEntryCap'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION), "text"),
			GetSQLValueString($contestAwardsLocName, "text"),
			GetSQLValueString($contestAwardsLocDate, "text"),
			GetSQLValueString(filter_var($_POST['contestEntryFee2'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION), "text"),
			GetSQLValueString($contestEntryFeeDiscount, "text"),
			GetSQLValueString(filter_var($_POST['contestEntryFeeDiscountNum'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION), "text"),
			GetSQLValueString($contestLogo, "text"),
			GetSQLValueString($contestBOSAward, "text"),
			GetSQLValueString($contestEntryFeePassword, "text"),
			GetSQLValueString(filter_var($_POST['contestEntryFeePasswordNum'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION), "text"),
			GetSQLValueString($contestCircuit, "text"),
			GetSQLValueString($contestVolunteers, "text"),
			GetSQLValueString($contestShippingOpen, "text"),
			GetSQLValueString($contestShippingDeadline, "text"),
			GetSQLValueString($id, "int"));
			//echo $updateSQL;

		}

		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

		if ($section == "setup") {

			$sql = sprintf("UPDATE `%s` SET setup_last_step = '4' WHERE id='1';", $system_db_table);
			mysqli_select_db($connection,$database);
			mysqli_real_escape_string($connection,$sql);
			$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

			$updateGoTo = $base_url."setup.php?section=step5";
		}

		$pattern = array('\'', '"');
		$updateGoTo = str_replace($pattern, "", $updateGoTo);
		$redirect_go_to = sprintf("Location: %s", stripslashes($updateGoTo));

		}

	} // end else
} else {
	$redirect_go_to = sprintf("Location: %s", $base_url."index.php?msg=98");
}
?>