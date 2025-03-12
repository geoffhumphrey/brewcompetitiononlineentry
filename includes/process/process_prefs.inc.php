<?php
/*
 * Module:      process_prefs_add.inc.php
 * Description: This module does all the heavy lifting for adding information to the
 *              "preferences" table.
 */

/*
if (HOSTED) $styles_db_table = "bcoem_shared_styles";
else
*/
$styles_db_table = $prefix."styles";

if ((isset($_SERVER['HTTP_REFERER'])) && (((isset($_SESSION['loginUsername'])) && ((isset($_SESSION['userLevel'])) && ($_SESSION['userLevel'] == 0))) || ($section == "setup"))) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";
	
	// Instantiate HTMLPurifier
	require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
	$config_html_purifier = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config_html_purifier);

	$query_prefs = sprintf("SELECT * FROM %s WHERE id='1'", $prefix."preferences");
	$prefs = mysqli_query($connection,$query_prefs) or die (mysqli_error($connection));
	$row_prefs = mysqli_fetch_assoc($prefs);
	$totalRows_prefs = mysqli_num_rows($prefs);

	// Sanity check for prefs
	if ($section == "setup") {
		if ($totalRows_prefs < 1) $action = "add";
	}

	$style_alert = FALSE;
	$style_set_change = FALSE;

	$prefsGoogleAccount = "";
	$prefsUSCLEx = "";
	$prefsBestBrewerTitle = "";
	$prefsBestClubTitle = "";
	$prefsUserEntryLimitDates = "";	
		
	$update_table = $prefix."preferences";

	if (!isset($_POST['prefsCompLogoSize'])) $_POST['prefsCompLogoSize'] = NULL;
	if (!isset($_POST['prefsBOSMead'])) $_POST['prefsBOSMead'] = NULL;
	if (!isset($_POST['prefsBOSCider'])) $_POST['prefsBOSCider'] = NULL;

	if (($section == "setup") && ($action == "add")) {

	}

	if (($section == "admin") && ($action == "edit")) {

		if ($go == "default") {

			// CAPTCHA uses prefsGoogleAccount column
			if ((isset($_POST['prefsGoogleAccount0'])) && (isset($_POST['prefsGoogleAccount1']))) $prefsGoogleAccount = $_POST['prefsGoogleAccount0']."|".$_POST['prefsGoogleAccount1'];
			if (HOSTED) $prefsCAPTCHA = 1;
			else $prefsCAPTCHA = sterilize($_POST['prefsCAPTCHA']);

			if (!empty($_POST['prefsWinnerDelay'])) $prefsWinnerDelay = strtotime(sterilize($_POST['prefsWinnerDelay']));
			else $prefsWinnerDelay = 2145916800;

			$data = array(

				'prefsProEdition' => sterilize($_POST['prefsProEdition']),
				'prefsDisplayWinners' => sterilize($_POST['prefsDisplayWinners']),
				'prefsWinnerDelay' => $prefsWinnerDelay,
				'prefsWinnerMethod' => sterilize($_POST['prefsWinnerMethod']),
				'prefsTheme' => sterilize($_POST['prefsTheme']),
				'prefsSEF' => sterilize($_POST['prefsSEF']),
				'prefsUseMods' => sterilize($_POST['prefsUseMods']),
				'prefsCAPTCHA' => blank_to_null($prefsCAPTCHA),
				'prefsGoogleAccount' => blank_to_null($prefsGoogleAccount),
				'prefsDropOff' => sterilize($_POST['prefsDropOff']),
				'prefsShipping' => sterilize($_POST['prefsShipping']),
				'prefsAutoPurge' => sterilize($_POST['prefsAutoPurge']),
				'prefsLanguage' => sterilize($_POST['prefsLanguage']),
				'prefsDateFormat' => sterilize($_POST['prefsDateFormat']),
				'prefsTimeZone' => sterilize($_POST['prefsTimeZone']),
				'prefsTimeFormat' => sterilize($_POST['prefsTimeFormat']),
				'prefsSponsors' => sterilize($_POST['prefsSponsors']),
				'prefsSponsorLogos' => sterilize($_POST['prefsSponsorLogos']),
			);

		}

		// Entry-related prefs
		if ($go == "entries") {

			/**
			 * Entry-related fees and discounts moved from Competition Info to 
			 * Preferences UI
			 */

			$contestEntryFee = "";
			$contestEntryFee2 = "";
			$contestEntryFeePassword = "";
			$contestEntryFeeDiscountNum = "";
			$contestEntryFeePasswordNum = "";
			$contestEntryCap = "";

			if ((empty($_POST['contestEntryFee2'])) || (empty($_POST['contestEntryFeeDiscountNum']))) $contestEntryFeeDiscount = "N";
			if ((!empty($_POST['contestEntryFee2'])) && (!empty($_POST['contestEntryFeeDiscountNum']))) $contestEntryFeeDiscount = "Y";
			if ((isset($_POST['contestEntryFee'])) && (!empty($_POST['contestEntryFee']))) $contestEntryFee = sterilize($_POST['contestEntryFee']);
			if ((isset($_POST['contestEntryFee2'])) && (!empty($_POST['contestEntryFee2']))) $contestEntryFee2 = sterilize($_POST['contestEntryFee2']);
			if ((isset($_POST['contestEntryFeeDiscountNum'])) && (!empty($_POST['contestEntryFeeDiscountNum']))) $contestEntryFeeDiscountNum = sterilize($_POST['contestEntryFeeDiscountNum']);
			if ((isset($_POST['contestEntryFeePasswordNum'])) && (!empty($_POST['contestEntryFeePasswordNum']))) $contestEntryFeePasswordNum = sterilize($_POST['contestEntryFeePasswordNum']);
			if ((isset($_POST['contestEntryCap'])) && (!empty($_POST['contestEntryCap']))) $contestEntryCap = sterilize($_POST['contestEntryCap']);

			$hash = NULL;
			if (isset($_POST['contestEntryFeePassword'])) {
				$secretKey = base64_encode(bin2hex($password));
				$nacl = base64_encode(bin2hex($server_root));
				$contestEntryFeePassword = sterilize($_POST['contestEntryFeePassword']);
				$contestEntryFeePassword = simpleEncrypt($contestEntryFeePassword, $secretKey, $nacl);
			}

			$data_entry_fees = array(
				'contestEntryFee' => blank_to_null($contestEntryFee),
				'contestEntryFee2' => blank_to_null($contestEntryFee2),
				'contestEntryFeeDiscount' => blank_to_null($contestEntryFeeDiscount),
				'contestEntryFeeDiscountNum' => blank_to_null($contestEntryFeeDiscountNum),
				'contestEntryCap' => blank_to_null($contestEntryCap),
				'contestEntryFeePassword' => blank_to_null($contestEntryFeePassword),
				'contestEntryFeePasswordNum' => blank_to_null($contestEntryFeePasswordNum)
			);

			if ($_POST['prefsStyleSet'] != $_SESSION['prefsStyleSet']) $style_set_change = TRUE;
			if (isset($_POST['prefsUSCLEx'])) $prefsUSCLEx = implode(",",$_POST['prefsUSCLEx']);
			$prefsStyleSet = sterilize($_POST['prefsStyleSet']);
			$incremental_limits = array();

			if ((!empty($_POST['user-entry-limit-number-1'])) && (!empty($_POST['user-entry-limit-expire-days-1']))) {

				$incremental_limits[1] = array(
					'limit-number' => sterilize($_POST['user-entry-limit-number-1']),
					'limit-days' => sterilize($_POST['user-entry-limit-expire-days-1'])
				);

				if (!empty($_POST['user-entry-limit-number-2'])) {
					$incremental_limits[2] = array(
						'limit-number' => sterilize($_POST['user-entry-limit-number-2']),
						'limit-days' => sterilize($_POST['user-entry-limit-expire-days-2'])
					);
				}

				if (!empty($_POST['user-entry-limit-number-3'])) {
					$incremental_limits[3] = array(
						'limit-number' => sterilize($_POST['user-entry-limit-number-3']),
						'limit-days' => sterilize($_POST['user-entry-limit-expire-days-3'])
					);
				}

				if (!empty($_POST['user-entry-limit-number-4'])) {
					$incremental_limits[4] = array(
						'limit-number' => sterilize($_POST['user-entry-limit-number-4']),
						'limit-days' => sterilize($_POST['user-entry-limit-expire-days-4'])
					);
				}

			}

			if (!empty($incremental_limits)) $prefsUserEntryLimitDates = json_encode($incremental_limits);

			$data = array(
				'prefsStyleSet' => $prefsStyleSet,
				'prefsEntryForm' => sterilize($_POST['prefsEntryForm']),
				'prefsSpecific' => sterilize($_POST['prefsSpecific']),
				'prefsSpecialCharLimit' => sterilize($_POST['prefsSpecialCharLimit']),
				'prefsEntryLimit' => blank_to_null(sterilize($_POST['prefsEntryLimit'])),
				'prefsEntryLimitPaid' => blank_to_null(sterilize($_POST['prefsEntryLimitPaid'])),
				'prefsUserEntryLimit' => blank_to_null(sterilize($_POST['prefsUserEntryLimit'])),
				'prefsUserSubCatLimit' => blank_to_null(sterilize($_POST['prefsUserSubCatLimit'])),
				'prefsUSCLExLimit' => blank_to_null(sterilize($_POST['prefsUSCLExLimit'])),
				'prefsUSCLEx' => blank_to_null($prefsUSCLEx),
				'prefsUserEntryLimitDates' => blank_to_null($prefsUserEntryLimitDates)
			);

			// Check if style type entry limits were specified
			if (!empty($_POST['style_type_entry_limits'])) {

				$style_type_limits = explode(",",sterilize($_POST['style_type_entry_limits']));

				foreach ($style_type_limits as $value) {
					
					$entry_limit = sterilize($_POST['styleTypeEntryLimit-'.$value]);
				 	$sql = sprintf("UPDATE %s SET styleTypeEntryLimit='%s' WHERE id='%s';", $prefix."style_types", blank_to_null($entry_limit), $value);
					mysqli_select_db($connection,$database);
					mysqli_real_escape_string($connection,$sql);
					$result = mysqli_query($connection,$sql);
					if (!$result) $errors = TRUE;
					
				}

			}

		}

		// Email sending prefs
		if ($go == "email") {

			$prefsEmailSMTP = sterilize($_POST['prefsEmailSMTP']);			
			$prefsContact = sterilize($_POST['prefsContact']);
			$prefsEmailRegConfirm = sterilize($_POST['prefsEmailRegConfirm']);
			$prefsEmailPassword = sterilize($row_prefs['prefsEmailPassword']);
			$prefsEmailFrom = sterilize($_POST['prefsEmailFrom']);
			$prefsEmailUsername = sterilize($_POST['prefsEmailUsername']);
			$prefsEmailHost = sterilize($_POST['prefsEmailHost']);
			$prefsEmailEncrypt = sterilize($_POST['prefsEmailEncrypt']);
			$prefsEmailPort = sterilize($_POST['prefsEmailPort']);
			if (HOSTED) $prefsEmailCC = 0;
			else $prefsEmailCC = sterilize($_POST['prefsEmailCC']);

			if ($_POST['change-email-password-choice'] == 1) {

				// Encrypt the smtp password
				$prefsEmailPassword = sterilize($_POST['prefsEmailPassword']);
				$secretKey = base64_encode(bin2hex($password));
				$nacl = base64_encode(bin2hex($server_root));
				$prefsEmailPassword = simpleEncrypt($prefsEmailPassword, $secretKey, $nacl);

			}

			if ($prefsEmailSMTP == 0) {

				$prefsEmailFrom = sterilize($row_prefs['prefsEmailFrom']);
				$prefsEmailUsername = sterilize($row_prefs['prefsEmailUsername']);
				$prefsEmailHost = sterilize($row_prefs['prefsEmailHost']);
				$prefsEmailEncrypt = sterilize($row_prefs['prefsEmailEncrypt']);
				$prefsEmailPort = sterilize($row_prefs['prefsEmailPort']);
				$prefsContact = "N"; // Disable Contact form
				$prefsEmailRegConfirm = 0; // Disable Confirmations
				$prefsEmailCC = 0; // Disable CC
				
			}

			$data = array(
				'prefsEmailSMTP' => $prefsEmailSMTP,
				'prefsEmailFrom' => blank_to_null($prefsEmailFrom),
				'prefsEmailUsername' => blank_to_null($prefsEmailUsername),
				'prefsEmailPassword' => blank_to_null($prefsEmailPassword),
				'prefsEmailHost' => blank_to_null($prefsEmailHost),
				'prefsEmailEncrypt' => blank_to_null($prefsEmailEncrypt),
				'prefsEmailPort' => blank_to_null($prefsEmailPort),
				'prefsContact' => blank_to_null($prefsContact),
				'prefsEmailRegConfirm' => $prefsEmailRegConfirm,
				'prefsEmailCC' => $prefsEmailCC,
			);

		}

		// Currency and payment prefs
		if ($go == "payment") {

			$data = array(
				'prefsCurrency' => sterilize($_POST['prefsCurrency']),
				'prefsPayToPrint' => sterilize($_POST['prefsPayToPrint']),
				'prefsCash' => sterilize($_POST['prefsCash']),
				'prefsCheck' => sterilize($_POST['prefsCheck']),
				'prefsCheckPayee' => blank_to_null(sterilize($_POST['prefsCheckPayee'])),
				'prefsPaypal' => sterilize($_POST['prefsPaypal']),
				'prefsPaypalAccount' => blank_to_null(sterilize($_POST['prefsPaypalAccount'])),
				'prefsPaypalIPN' => sterilize($_POST['prefsPaypalIPN']),
				'prefsTransFee' => sterilize($_POST['prefsTransFee'])
			);
			
		}

		// Best brewer/club prefs
		if ($go == "best") {

			if (!empty($_POST['prefsBestBrewerTitle'])) {
				$prefsBestBrewerTitle = $purifier->purify($_POST['prefsBestBrewerTitle']);
				$prefsBestBrewerTitle = sterilize($prefsBestBrewerTitle);
			}

			if (!empty($_POST['prefsBestClubTitle'])) {
				$prefsBestClubTitle = $purifier->purify($_POST['prefsBestClubTitle']);
				$prefsBestClubTitle = sterilize($prefsBestClubTitle);
			}

			$prefsBestUseBOS = sterilize($_POST['prefsBestUseBOS']);
			$prefsScoringCOA = sterilize($_POST['prefsScoringCOA']);
			if ($prefsScoringCOA == 1) $prefsBestUseBOS = 0;

			$data = array(
				'prefsShowBestBrewer' => sterilize($_POST['prefsShowBestBrewer']),
				'prefsBestBrewerTitle' => blank_to_null($prefsBestBrewerTitle),
				'prefsShowBestClub' => sterilize($_POST['prefsShowBestClub']),
				'prefsBestClubTitle' => blank_to_null($prefsBestClubTitle),
				'prefsBestUseBOS' => $prefsBestUseBOS,
				'prefsScoringCOA' => $prefsScoringCOA,
				'prefsFirstPlacePts' => sterilize($_POST['prefsFirstPlacePts']),
				'prefsSecondPlacePts' => sterilize($_POST['prefsSecondPlacePts']),
				'prefsThirdPlacePts' => sterilize($_POST['prefsThirdPlacePts']),
				'prefsFourthPlacePts' => sterilize($_POST['prefsFourthPlacePts']),
				'prefsHMPts' => sterilize($_POST['prefsHMPts']),
				'prefsTieBreakRule1' => blank_to_null(sterilize($_POST['prefsTieBreakRule1'])),
				'prefsTieBreakRule2' => blank_to_null(sterilize($_POST['prefsTieBreakRule2'])),
				'prefsTieBreakRule3' => blank_to_null(sterilize($_POST['prefsTieBreakRule3'])),
				'prefsTieBreakRule4' => blank_to_null(sterilize($_POST['prefsTieBreakRule4'])),
				'prefsTieBreakRule5' => blank_to_null(sterilize($_POST['prefsTieBreakRule5'])),
				'prefsTieBreakRule6' => blank_to_null(sterilize($_POST['prefsTieBreakRule6']))
			);
			
		}

	} // end if ($action == "edit")	

	if ($action == "add") {

		$result = $db_conn->insert ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		// Update style set of any custom styles to chosen style set
		// Safeguards against a bug introduced in 2.1.13 scripting
		// Also update sub-style idenfication scheming

		foreach ($style_sets as $key) {
            
            if ($key['style_set_name'] == $prefsStyleSet) {
            	
            	if ($prefsStyleSet == "BA") {
            		
            		// No hosted call since searching for custom styles
            		$query_style_name = sprintf("SELECT id,brewStyleNum FROM %s WHERE brewStyleOwn='custom' ORDER BY id", $styles_db_table);
					$style_name = mysqli_query($connection,$query_style_name) or die (mysqli_error($connection));
					$row_style_name = mysqli_fetch_assoc($style_name);

					/*
					if (HOSTED) $query_style_num = sprintf("SELECT brewStyleNum FROM %s WHERE brewStyleVersion='BA' UNION ALL SELECT brewStyleNum FROM %s WHERE brewStyleVersion='BA' ORDER BY brewStyleNum DESC LIMIT 1", $styles_db_table, $prefix."styles");
					else 
					*/
					$query_style_num = sprintf("SELECT brewStyleNum FROM %s WHERE brewStyleVersion='BA' ORDER BY brewStyleNum DESC LIMIT 1", $styles_db_table);
					$style_num = mysqli_query($connection,$query_style_num) or die (mysqli_error($connection));
					$row_style_num = mysqli_fetch_assoc($style_num);

					$sub_style_id = $row_style_num['brewStyleNum'] + 1;

					do {

						$sub_style = str_pad($sub_style_id,3,"0", STR_PAD_LEFT);
						
						$update_table = $prefix."styles";
						$data = array('brewStyleNum' => $sub_style);
						$db_conn->where ('id', $row_style_name['id']);
						$result = $db_conn->update ($update_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

						$sub_style_id++;

					} while ($row_style_name = mysqli_fetch_assoc($style_name));

            	}
            	
            	else {
	            	
	            	if ($key['style_set_sub_style_method'] == 0) $sub_style_id = "A";
	            	else $sub_style_id = "001";

	            	$update_table = $prefix."styles";
	            	$data = array(
	            		'brewStyleVersion' => $prefsStyleSet,
	            		'brewStyleNum' => $sub_style_id
	            	);
	            	$db_conn->where ('brewStyleOwn', 'custom');
	            	$result = $db_conn->update ($update_table, $data);
	            	if (!$result) {
	            		$error_output[] = $db_conn->getLastError();
	            		$errors = TRUE;
	            	}

	            } // end else

            } // end if ($key['style_set_name'] == $prefsStyleSet)

        } // end foreach

        // Deactivate all styles
		$update_table = $prefix."styles";
		$data = array('brewStyleActive' => 'N');
		$result = $db_conn->update ($update_table, $data);

		// Activate all styles associated with the chosen style set
		// User will deselect any unwanted in Step 7
		$update_table = $prefix."styles";
		$data = array('brewStyleActive' => 'Y');
		$db_conn->where ('brewStyleVersion', sterilize($_POST['prefsStyleSet']));
		$result = $db_conn->update ($update_table, $data);

		if ($_POST['prefsPaypalIPN'] == 1) {

			// Only install the payments db table if enabled and if not there already
			if (!check_setup($prefix."payments", $database)) {

				$sql = sprintf("CREATE TABLE IF NOT EXISTS `%s` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `uid` int(11) DEFAULT NULL,
				  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
				  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
				  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
				  `txn_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
				  `payment_gross` float(10,2) DEFAULT NULL,
				  `currency_code` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
				  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
				  `payment_entries` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
				  `payment_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",$prefix."payments");
				
				$db_conn->rawQuery($sql);
				if ($db_conn->getLastErrno() !== 0) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

		} // end if ($_POST['prefsPaypalIPN'] == 1)

		// Check to see if processed correctly.
		$query_prefs_check = sprintf("SELECT COUNT(*) as 'count' FROM %s", $prefix."preferences");
		$prefs_check = mysqli_query($connection,$query_prefs_check) or die (mysqli_error($connection));
		$row_prefs_check = mysqli_fetch_assoc($prefs_check);

		// If so, mark step as complete in system table and redirect to next step.
		if ($row_prefs_check['count'] == 1) {
			
			$update_table = $prefix."bcoem_sys";
			$data = array('setup_last_step' => 3);
			$db_conn->where ('id', 1);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$insertGoTo = $base_url."setup.php?section=step4";

		}

		else {
			$insertGoTo = $base_url."setup.php?section=step3&msg=99";
			if ($errors) $insertGoTo = $base_url."setup.php?section=step3&msg=3";
		}

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		$insertGoTo = prep_redirect_link($insertGoTo);
		$redirect_go_to = sprintf("Location: %s", $insertGoTo);

	}

	if ($action == "edit") {

		$db_conn->where ('id', $id);
		$result = $db_conn->update ($update_table, $data);
		if (!$result) {
			$error_output[] = $db_conn->getLastError();
			$errors = TRUE;
		}

		if ($go == "payment") {

			if ($_POST['prefsPaypalIPN'] == 1) {

				// Only install the payments db table if enabled and if not there already
				if (!check_setup($prefix."payments", $database)) {

					$sql = sprintf("CREATE TABLE IF NOT EXISTS `%s` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `uid` int(11) DEFAULT NULL,
					  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
					  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
					  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
					  `txn_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
					  `payment_gross` float(10,2) DEFAULT NULL,
					  `currency_code` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
					  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
					  `payment_entries` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
					  `payment_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",$prefix."payments");
					
					$db_conn->rawQuery($sql);
					if ($db_conn->getLastErrno() !== 0) {
						$error_output[] = $db_conn->getLastError();
						$errors = TRUE;
					}

				}

			} // end if ($_POST['prefsPaypalIPN'] == 1)

		}

		if ($go == "entries") {

			$update_table_entry_fees = $prefix."contest_info";
			$db_conn->where ('id', $id);
			$result = $db_conn->update ($update_table_entry_fees, $data_entry_fees);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			/**
			 * If the style set has changed from BJCP 2015 to BJCP 2021, map
			 * 2015 styles to updated 2021 styles in brewing DB.
			 * Update scripting changed BJCP2008 preference to BJCP2015 and
			 * performed a conversion from 2008 to 2015 on all entries in the DB.
			 * As a safeguard to make sure the brewing table data is updated, 
			 * perform a query for any old styles whose names have changed and/or
			 * whose category has changed.
			 */
			
			if ($prefsStyleSet == "BJCP2021") {
				
				if ($_SESSION['prefsStyleSet'] == "BJCP2015") {

					include (LIB.'convert.lib.php');
					include (INCLUDES.'convert/convert_bjcp_2021.inc.php');
					
				}

				$query_check_entry_styles = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewStyle='Clone Beer' OR brewStyle='New England IPA' OR brewStyle='Trappist Single' OR brewCategorySort='PR' OR (brewCategorySort='21' AND brewSubCategory='B7' AND brewStyle='New England IPA') OR (brewCategorySort='27' AND brewSubCategory='A1' AND brewStyle='Gose')", $prefix."brewing");
				$check_entry_styles = mysqli_query($connection,$query_check_entry_styles) or die (mysqli_error($connection));
				$row_check_entry_styles = mysqli_fetch_assoc($check_entry_styles);

				if ($row_check_entry_styles['count'] > 0) {

					include (LIB.'convert.lib.php');
					include (INCLUDES.'convert/convert_bjcp_2021.inc.php');

				}

			}

			// Finally, if the style set has changed, add all styles 
			// in the chosen style set as active.

			$prefsSelectedStyles = json_decode($row_prefs['prefsSelectedStyles'],true);

			if (($style_set_change) || (empty($prefsSelectedStyles))) {

				$update_selected_styles = array();
				$query_styles_default = sprintf("SELECT id, brewStyle, brewStyleGroup, brewStyleNum, brewStyleVersion FROM %s WHERE brewStyleVersion='%s'", $styles_db_table, $prefsStyleSet);
				$styles_default = mysqli_query($connection,$query_styles_default);
				$row_styles_default = mysqli_fetch_assoc($styles_default);

				if ($row_styles_default) {

					do {

						$update_selected_styles[$row_styles_default['id']] = array(
							'brewStyle' => $row_styles_default['brewStyle'],
							'brewStyleGroup' => $row_styles_default['brewStyleGroup'],
							'brewStyleNum' => $row_styles_default['brewStyleNum'],
							'brewStyleVersion' => $row_styles_default['brewStyleVersion']
						);

					} while($row_styles_default = mysqli_fetch_assoc($styles_default));
				
				}

				$update_selected_styles = json_encode($update_selected_styles);

				$update_table = $prefix."preferences";
				$data = array(
					'prefsSelectedStyles' => blank_to_null($update_selected_styles)
				);
				$db_conn->where ('id', 1);
				$result = $db_conn->update ($update_table, $data);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

		}

		// Empty the prefs session variable
		// Will trigger the session to reset the variables in common.db.php upon reload after redirect
		unset($_SESSION['prefs'.$prefix_session]);

		// if ($style_alert) $updateGoTo .= $_POST['relocate']."&msg=37";

		if ($section == "setup") {
			
			$update_table = $prefix."bcoem_sys";
			$data = array('setup_last_step' => 3);
			$db_conn->where ('id', 1);
			$result = $db_conn->update ($update_table, $data);
			if (!$result) {
				$error_output[] = $db_conn->getLastError();
				$errors = TRUE;
			}

			$updateGoTo = $base_url."setup.php?section=step4";

		}

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		if ($errors) $updateGoTo = $_POST['relocate']."&msg=3";
		elseif ($style_set_change) $updateGoTo = $base_url."index.php?section=admin&go=styles&msg=37";
		elseif (($go == "email") && (isset($_POST['send-test-email'])) && ($_POST['send-test-email'] == 1)) $updateGoTo = $base_url."index.php?section=admin&go=preferences&action=email&view=test-email&msg=2";
		else $updateGoTo = $base_url."index.php?section=admin&msg=2";
		$updateGoTo = prep_redirect_link($updateGoTo);
		$redirect_go_to = sprintf("Location: %s", $updateGoTo);

	}

} else {

	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);

}
?>