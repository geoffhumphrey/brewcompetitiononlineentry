<?php
/**
 * Module:      process_comp_info.inc.php
 * Description: This module does all the heavy lifting for adding/editing information in the
 *              "contest_info" table.
 */

if ((isset($_SERVER['HTTP_REFERER'])) && (((isset($_SESSION['loginUsername'])) && ((isset($_SESSION['userLevel'])) && ($_SESSION['userLevel'] == 0))) || ($section == "setup"))) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";

	// Instantiate HTMLPurifier
	require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
	$config_html_purifier = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config_html_purifier);

	// Constants
	if ($go == "default") {

		$contestName = "";
		$contestHost = "";
		$contestHostWebsite = "";
		$contestHostLocation = "";
		$contestRegistrationOpen = "";
		$contestRegistrationDeadline = "";
		$contestEntryOpen = "";
		$contestEntryDeadline = "";
		$contestJudgeOpen = "";
		$contestJudgeDeadline = "";
		$contestRules = "";
		$contestAwards = "";
		$contestAwardsLocation = "";
		$contestAwardsLocName = "";
		$contestAwardsLocDate = "";
		$contestShippingOpen = "";
		$contestShippingDeadline = "";
		$contestShippingName = "";
		$contestShippingAddress = "";
		$contestDropoffOpen = "";
		$contestDropoffDeadline = "";
		$contestBottles = "";
		$contestBOSAward = "";
		$contestCircuit = "";
		$contestVolunteers = "";
		$contestLogo = "";
		$contestEntryFee = "";
		$contestEntryFee2 = "";
		$contestEntryFeePassword = "";
		$contestEntryFeeDiscountNum = "";
		$contestEntryFeePasswordNum = "";
		$contestEntryCap = "";
		$contestCheckInPassword = "";
		$contestID = "";

		if (isset($_POST['contestName'])) $contestName = $purifier->purify($_POST['contestName']);
		if (isset($_POST['contestHost'])) $contestHost = $purifier->purify($_POST['contestHost']);
		if (isset($_POST['contestHostWebsite'])) $contestHostWebsite = check_http(filter_var($_POST['contestHostWebsite'],FILTER_SANITIZE_STRING));
		if (isset($_POST['contestHostLocation'])) $contestHostLocation = $purifier->purify($_POST['contestHostLocation']);
		if (isset($_POST['contestRegistrationOpen'])) $contestRegistrationOpen = strtotime(filter_var($_POST['contestRegistrationOpen'],FILTER_SANITIZE_STRING));
		if (isset($_POST['contestRegistrationDeadline'])) $contestRegistrationDeadline = strtotime(filter_var($_POST['contestRegistrationDeadline'],FILTER_SANITIZE_STRING));
		if (isset($_POST['contestEntryOpen'])) $contestEntryOpen = strtotime(filter_var($_POST['contestEntryOpen'],FILTER_SANITIZE_STRING));
		if (isset($_POST['contestEntryDeadline'])) $contestEntryDeadline = strtotime(filter_var($_POST['contestEntryDeadline'],FILTER_SANITIZE_STRING));
		if (isset($_POST['contestJudgeOpen'])) $contestJudgeOpen = strtotime(filter_var($_POST['contestJudgeOpen'],FILTER_SANITIZE_STRING));
		if (isset($_POST['contestJudgeDeadline'])) $contestJudgeDeadline = strtotime(filter_var($_POST['contestJudgeDeadline'],FILTER_SANITIZE_STRING));
		if (isset($_POST['contestRules'])) $contestRules = $purifier->purify($_POST['contestRules']);
		if (isset($_POST['contestAwards'])) $contestAwards = $purifier->purify($_POST['contestAwards']);
		if (isset($_POST['contestAwardsLocation'])) $contestAwardsLocation = $purifier->purify($_POST['contestAwardsLocation']);
		if (isset($_POST['contestAwardsLocName'])) $contestAwardsLocName = $purifier->purify($_POST['contestAwardsLocName']);
		if (isset($_POST['contestAwardsLocDate'])) $contestAwardsLocDate = strtotime(filter_var($_POST['contestAwardsLocDate'],FILTER_SANITIZE_STRING));
		if (isset($_POST['contestShippingOpen'])) $contestShippingOpen = strtotime(filter_var($_POST['contestShippingOpen'],FILTER_SANITIZE_STRING));
		if (isset($_POST['contestShippingDeadline'])) $contestShippingDeadline = strtotime(filter_var($_POST['contestShippingDeadline'],FILTER_SANITIZE_STRING));
		if (isset($_POST['contestShippingName'])) $contestShippingName = $purifier->purify($_POST['contestShippingName']);
		if (isset($_POST['contestShippingAddress'])) $contestShippingAddress = $purifier->purify($_POST['contestShippingAddress']);
		if (isset($_POST['contestDropoffOpen'])) $contestDropoffOpen = strtotime(filter_var($_POST['contestDropoffOpen'],FILTER_SANITIZE_STRING));
		if (isset($_POST['contestDropoffDeadline'])) $contestDropoffDeadline = strtotime(filter_var($_POST['contestDropoffDeadline'],FILTER_SANITIZE_STRING));
		if (isset($_POST['contestBottles'])) $contestBottles = $purifier->purify($_POST['contestBottles']);
		if (isset($_POST['contestBOSAward'])) $contestBOSAward = $purifier->purify($_POST['contestBOSAward']);
		if (isset($_POST['contestCircuit'])) $contestCircuit = $purifier->purify($_POST['contestCircuit']);
		if (isset($_POST['contestVolunteers'])) $contestVolunteers = $purifier->purify($_POST['contestVolunteers']);
		if (isset($_POST['contestLogo'])) $contestLogo = $purifier->purify($_POST['contestLogo']);
		if (isset($_POST['contestEntryFeePassword'])) $contestEntryFeePassword = sterilize($_POST['contestEntryFeePassword']);
		if (isset($_POST['contestCheckInPassword'])) $contestCheckInPassword = sterilize($_POST['contestCheckInPassword']);
		if (isset($_POST['contestID'])) $contestID = filter_var($_POST['contestID'],FILTER_SANITIZE_NUMBER_INT);
		if ((empty($_POST['contestEntryFee2'])) || (empty($_POST['contestEntryFeeDiscountNum']))) $contestEntryFeeDiscount = "N";
		if ((!empty($_POST['contestEntryFee2'])) && (!empty($_POST['contestEntryFeeDiscountNum']))) $contestEntryFeeDiscount = "Y";

		if ((isset($_POST['contestEntryFee'])) && (!empty($_POST['contestEntryFee']))) $contestEntryFee = filter_var($_POST['contestEntryFee'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
		if ((isset($_POST['contestEntryFee2'])) && (!empty($_POST['contestEntryFee2']))) $contestEntryFee2 = filter_var($_POST['contestEntryFee2'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
		if ((isset($_POST['contestEntryFeeDiscountNum'])) && (!empty($_POST['contestEntryFeeDiscountNum']))) $contestEntryFeeDiscountNum = filter_var($_POST['contestEntryFeeDiscountNum'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
		if ((isset($_POST['contestEntryFeePasswordNum'])) && (!empty($_POST['contestEntryFeePasswordNum']))) $contestEntryFeePasswordNum = filter_var($_POST['contestEntryFeePasswordNum'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
		if ((isset($_POST['contestEntryCap'])) && (!empty($_POST['contestEntryCap']))) $contestEntryCap = filter_var($_POST['contestEntryCap'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
		
	
	} // end if ($go == "default")

	/**
	 * Adding (Setup)
	 */

	if ($action == "add") {

		$hash = NULL;

		if (isset($_POST['contestCheckInPassword'])) {
			require(CLASSES.'phpass/PasswordHash.php');
			$hasher = new PasswordHash(8, false);
			$password = md5(sterilize($_POST['contestCheckInPassword']));
			$hash = $hasher->HashPassword($password);
		}

		$update_table = $prefix."contest_info";
		$data = array(
			'id' => 1,
			'contestName' => $contestName,
			'contestHost' => $contestHost,
			'contestHostWebsite' => $contestHostWebsite,
			'contestHostLocation' => $contestHostLocation,
			'contestRegistrationOpen' => $contestRegistrationOpen,
			'contestRegistrationDeadline' => $contestRegistrationDeadline,
			'contestEntryOpen' => $contestEntryOpen,
			'contestEntryDeadline' => $contestEntryDeadline,
			'contestJudgeOpen' => $contestJudgeOpen,
			'contestJudgeDeadline' => $contestJudgeDeadline,
			'contestRules' => $contestRules,
			'contestAwards' => $contestAwards,
			'contestAwardsLocation' => $contestAwardsLocation,
			'contestAwardsLocName' => $contestAwardsLocName,
			'contestAwardsLocDate' => $contestAwardsLocDate,
			'contestAwardsLocTime' => $contestAwardsLocDate,
			'contestShippingOpen' => $contestShippingOpen,
			'contestShippingDeadline' => $contestShippingDeadline,
			'contestShippingName' => $contestShippingName,
			'contestShippingAddress' => $contestShippingAddress,
			'contestDropoffOpen' => $contestDropoffOpen,
			'contestDropoffDeadline' => $contestDropoffDeadline,
			'contestBottles' => $contestBottles,
			'contestBOSAward' => $contestBOSAward,
			'contestCircuit' => $contestCircuit,
			'contestVolunteers' => $contestVolunteers,
			'contestLogo' => $contestLogo,
			'contestEntryFee' => $contestEntryFee,
			'contestEntryFee2' => $contestEntryFee2,
			'contestEntryFeeDiscount' => $contestEntryFeeDiscount,
			'contestEntryFeeDiscountNum' => $contestEntryFeeDiscountNum,
			'contestEntryCap' => $contestEntryCap,
			'contestEntryFeePassword' => $contestEntryFeePassword,
			'contestEntryFeePasswordNum' => $contestEntryFeePasswordNum,
			'contestCheckInPassword' => $hash,
			'contestID' => $contestID
		);

		$result = $db_conn->insert ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		if (empty($_POST['contestEntryFee2'])) {

			$data = array(
				'contestEntryFee2' => NULL	
			);
			$db_conn->where ('id', 1);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

		}

		if (empty($_POST['contestEntryFeeDiscountNum'])) {

			$data = array(
				'contestEntryFeeDiscountNum' => NULL	
			);
			$db_conn->where ('id', 1);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

		}
		
		if (empty($_POST['contestEntryFeePasswordNum'])) {

			$data = array(
				'contestEntryFeePasswordNum' => NULL	
			);
			$db_conn->where ('id', 1);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

		}

		if (empty($_POST['contestEntryCap'])) {

			$data = array(
				'contestEntryCap' => NULL	
			);
			$db_conn->where ('id', 1);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

		}

		$update_table = $prefix."contacts";
		$data = array(
			'contactFirstName' => sterilize($_POST['contactFirstName']),
			'contactLastName' => sterilize($_POST['contactLastName']),
			'contactPosition' => sterilize($_POST['contactPosition']),
			'contactEmail' => sterilize($_POST['contactEmail'])
		);
		$result = $db_conn->insert ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		// Check to see if processed correctly.
		$query_comp_info_check = sprintf("SELECT COUNT(*) as 'count' FROM %s",$contest_info_db_table);
		$comp_info_check = mysqli_query($connection,$query_comp_info_check) or die (mysqli_error($connection));
		$row_comp_info_check = mysqli_fetch_assoc($comp_info_check);

		// If so, mark step as complete in system table and redirect to next step.
		if ($row_comp_info_check['count'] == 1) {

			$update_table = $prefix."bcoem_sys";
			$data = array('setup_last_step' => '4');
			$db_conn->where ('id', 1);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}
			if ($errors) $base_url."setup.php?section=step5&msg=3";
			else $insertGoTo = $base_url."setup.php?section=step5";

		}

		// If not, redirect back to step 4 and display message.
		else  {
			$insertGoTo = $base_url."setup.php?section=step4&msg=99";
			if ($errors) $insertGoTo = $base_url."setup.php?section=step4&msg=3";
		}

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		$insertGoTo = prep_redirect_link($insertGoTo);
		$redirect_go_to = sprintf("Location: %s", $insertGoTo);

	} // end if ($action == "add")

	/**
	 * Editing
	 */

	if ($action == "edit") {

		$hash = NULL;

		if ($go == "qr") {

			if (isset($_POST['contestCheckInPassword'])) {

				require(CLASSES.'phpass/PasswordHash.php');
				$hasher = new PasswordHash(8, false);
				$password = md5(sterilize($_POST['contestCheckInPassword']));
				$hash = $hasher->HashPassword($password);

				$update_table = $prefix."contest_info";
				$data = array('contestCheckInPassword' => $hash);
				$db_conn->where ('id', $id);
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

		} // end if ($go == "qr")

		else {

			/**
			 * Empty the contest_info_general session variable.
			 * Will trigger the session to reset the variables in 
			 * common.db.php upon reload after redirect.
			 */
			
			unset($_SESSION['contest_info_general'.$prefix_session]);

			$update_table = $prefix."contest_info";
			$data = array(
				'contestName' => $contestName,
				'contestHost' => $contestHost,
				'contestHostWebsite' => $contestHostWebsite,
				'contestHostLocation' => $contestHostLocation,
				'contestRegistrationOpen' => $contestRegistrationOpen,
				'contestRegistrationDeadline' => $contestRegistrationDeadline,
				'contestEntryOpen' => $contestEntryOpen,
				'contestEntryDeadline' => $contestEntryDeadline,
				'contestJudgeOpen' => $contestJudgeOpen,
				'contestJudgeDeadline' => $contestJudgeDeadline,
				'contestRules' => $contestRules,
				'contestAwards' => $contestAwards,
				'contestAwardsLocation' => $contestAwardsLocation,
				'contestAwardsLocName' => $contestAwardsLocName,
				'contestAwardsLocDate' => $contestAwardsLocDate,
				'contestAwardsLocTime' => $contestAwardsLocDate,
				'contestShippingOpen' => $contestShippingOpen,
				'contestShippingDeadline' => $contestShippingDeadline,
				'contestShippingName' => $contestShippingName,
				'contestShippingAddress' => $contestShippingAddress,
				'contestDropoffOpen' => $contestDropoffOpen,
				'contestDropoffDeadline' => $contestDropoffDeadline,
				'contestBottles' => $contestBottles,
				'contestBOSAward' => $contestBOSAward,
				'contestCircuit' => $contestCircuit,
				'contestVolunteers' => $contestVolunteers,
				'contestLogo' => $contestLogo,
				'contestEntryFee' => $contestEntryFee,
				'contestEntryFee2' => $contestEntryFee2,
				'contestEntryFeeDiscount' => $contestEntryFeeDiscount,
				'contestEntryFeeDiscountNum' => $contestEntryFeeDiscountNum,
				'contestEntryCap' => $contestEntryCap,
				'contestEntryFeePassword' => $contestEntryFeePassword,
				'contestEntryFeePasswordNum' => $contestEntryFeePasswordNum,
				'contestCheckInPassword' => $hash,
				'contestID' => $contestID
			);

			$db_conn->where ('id', 1);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			if (empty($_POST['contestEntryFee2'])) {

				$data = array(
					'contestEntryFee2' => NULL	
				);
				$db_conn->where ('id', 1);
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

			if (empty($_POST['contestEntryFeeDiscountNum'])) {

				$data = array(
					'contestEntryFeeDiscountNum' => NULL	
				);
				$db_conn->where ('id', 1);
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}
			
			if (empty($_POST['contestEntryFeePasswordNum'])) {

				$data = array(
					'contestEntryFeePasswordNum' => NULL	
				);
				$db_conn->where ('id', 1);
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

			if (empty($_POST['contestEntryCap'])) {

				$data = array(
					'contestEntryCap' => NULL	
				);
				$db_conn->where ('id', 1);
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

			if ($section == "setup") {

				$update_table = $prefix."bcoem_sys";
				$data = array('setup_last_step' => '4');
				$db_conn->where ('id', 1);
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

				if ($errors) $updateGoTo = $base_url."setup.php?section=step5&msg=3";
				else $updateGoTo = $base_url."setup.php?section=step5";

			}

		}

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		if ($errors) $updateGoTo = sterilize($_POST['relocate']."&msg=3");
		else $updateGoTo = sterilize($_POST['relocate']."&msg=2");
		$updateGoTo = prep_redirect_link($updateGoTo);
		$redirect_go_to = sprintf("Location: %s", $updateGoTo);

	} // end if ($action == "edit")

} else {
	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);
}
?>