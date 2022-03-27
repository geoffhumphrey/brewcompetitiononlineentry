<?php
/*
 * Module:      process_prefs_add.inc.php
 * Description: This module does all the heavy lifting for adding information to the
 *              "preferences" table.
 */

if ((isset($_SERVER['HTTP_REFERER'])) && (((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) || ($section == "setup"))) {

	$errors = FALSE;
	$error_output = array();
	$_SESSION['error_output'] = "";
	
	// Instantiate HTMLPurifier
	require (CLASSES.'htmlpurifier/HTMLPurifier.standalone.php');
	$config_html_purifier = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config_html_purifier);

	// Sanity check for prefs
	if ($section == "setup") {
		$query_prefs = sprintf("SELECT * FROM %s WHERE id='1'", $prefix."preferences");
		$prefs = mysqli_query($connection,$query_prefs) or die (mysqli_error($connection));
		$row_prefs = mysqli_fetch_assoc($prefs);
		$totalRows_prefs = mysqli_num_rows($prefs);

		if ($totalRows_prefs < 1) $action = "add";
	}

	$style_alert = FALSE;

	$prefsGoogleAccount = "";
	$prefsUSCLEx = "";
	$prefsBestBrewerTitle = "";
	$prefsBestClubTitle = "";

	if ((isset($_POST['prefsGoogleAccount0'])) && (isset($_POST['prefsGoogleAccount1']))) $prefsGoogleAccount = $_POST['prefsGoogleAccount0']."|".$_POST['prefsGoogleAccount1'];

	if (isset($_POST['prefsUSCLEx'])) $prefsUSCLEx = implode(",",$_POST['prefsUSCLEx']);

	if (HOSTED) $prefsCAPTCHA = 1;
	else $prefsCAPTCHA = $_POST['prefsCAPTCHA'];

	if (!empty($_POST['prefsWinnerDelay'])) $prefsWinnerDelay = strtotime(sterilize($_POST['prefsWinnerDelay']));
	else $prefsWinnerDelay = 2145916800;

	if (!empty($_POST['prefsBestBrewerTitle'])) {
		$prefsBestBrewerTitle = $purifier->purify($_POST['prefsBestBrewerTitle']);
		$prefsBestBrewerTitle = sterilize($prefsBestBrewerTitle);
	}

	if (!empty($_POST['prefsBestClubTitle'])) {
		$prefsBestClubTitle = $purifier->purify($_POST['prefsBestClubTitle']);
		$prefsBestClubTitle = sterilize($prefsBestClubTitle);
	}
		
	$update_table = $prefix."preferences";

	$data = array(
		'prefsTemp' => sterilize($_POST['prefsTemp']),
		'prefsWeight1' => sterilize($_POST['prefsWeight1']),
		'prefsWeight2' => sterilize($_POST['prefsWeight2']),
		'prefsLiquid1' => sterilize($_POST['prefsLiquid1']),
		'prefsLiquid2' => sterilize($_POST['prefsLiquid2']),
		'prefsPaypal' => sterilize($_POST['prefsPaypal']),
		'prefsPaypalAccount' => sterilize($_POST['prefsPaypalAccount']),
		'prefsPaypalIPN' => sterilize($_POST['prefsPaypalIPN']),
		'prefsCurrency' => sterilize($_POST['prefsCurrency']),
		'prefsCash' => sterilize($_POST['prefsCash']),
		'prefsCheck' => sterilize($_POST['prefsCheck']),
		'prefsCheckPayee' => sterilize($_POST['prefsCheckPayee']),
		'prefsTransFee' => sterilize($_POST['prefsTransFee']),
		'prefsCAPTCHA' => $prefsCAPTCHA,
		'prefsGoogleAccount' => $prefsGoogleAccount,
		'prefsSponsors' => sterilize($_POST['prefsSponsors']),
		'prefsSponsorLogos' => sterilize($_POST['prefsSponsorLogos']),
		'prefsSponsorLogoSize' => sterilize($_POST['prefsSponsorLogoSize']),
		'prefsCompLogoSize' => sterilize($_POST['prefsCompLogoSize']),
		'prefsDisplayWinners' => sterilize($_POST['prefsDisplayWinners']),
		'prefsWinnerDelay' => $prefsWinnerDelay,
		'prefsWinnerMethod' => sterilize($_POST['prefsWinnerMethod']),
		'prefsDisplaySpecial' => sterilize($_POST['prefsDisplaySpecial']),
		'prefsBOSMead' => sterilize($_POST['prefsBOSMead']),
		'prefsBOSCider' => sterilize($_POST['prefsBOSCider']),
		'prefsShowBestBrewer' => sterilize($_POST['prefsShowBestBrewer']),
		'prefsBestBrewerTitle' => $prefsBestBrewerTitle,
		'prefsShowBestClub' => sterilize($_POST['prefsShowBestClub']),
		'prefsBestClubTitle' => $prefsBestClubTitle,
		'prefsFirstPlacePts' => sterilize($_POST['prefsFirstPlacePts']),
		'prefsSecondPlacePts' => sterilize($_POST['prefsSecondPlacePts']),
		'prefsThirdPlacePts' => sterilize($_POST['prefsThirdPlacePts']),
		'prefsFourthPlacePts' => sterilize($_POST['prefsFourthPlacePts']),
		'prefsHMPts' => sterilize($_POST['prefsHMPts']),
		'prefsTieBreakRule1' => sterilize($_POST['prefsTieBreakRule1']),
		'prefsTieBreakRule2' => sterilize($_POST['prefsTieBreakRule2']),
		'prefsTieBreakRule3' => sterilize($_POST['prefsTieBreakRule3']),
		'prefsTieBreakRule4' => sterilize($_POST['prefsTieBreakRule4']),
		'prefsTieBreakRule5' => sterilize($_POST['prefsTieBreakRule5']),
		'prefsTieBreakRule6' => sterilize($_POST['prefsTieBreakRule6']),
		'prefsEntryForm' => sterilize($_POST['prefsEntryForm']),
		'prefsRecordLimit' => sterilize($_POST['prefsRecordLimit']),
		'prefsRecordPaging' => sterilize($_POST['prefsRecordPaging']),
		'prefsProEdition' => sterilize($_POST['prefsProEdition']),
		'prefsTheme' => sterilize($_POST['prefsTheme']),
		'prefsDateFormat' => sterilize($_POST['prefsDateFormat']),
		'prefsContact' => sterilize($_POST['prefsContact']),
		'prefsTimeZone' => sterilize($_POST['prefsTimeZone']),
		'prefsEntryLimit' => sterilize($_POST['prefsEntryLimit']),
		'prefsTimeFormat' => sterilize($_POST['prefsTimeFormat']),
		'prefsUserEntryLimit' => sterilize($_POST['prefsUserEntryLimit']),
		'prefsUserSubCatLimit' => sterilize($_POST['prefsUserSubCatLimit']),
		'prefsUSCLEx' => $prefsUSCLEx,
		'prefsUSCLExLimit' => sterilize($_POST['prefsUSCLExLimit']),
		'prefsPayToPrint' => sterilize($_POST['prefsPayToPrint']),
		'prefsHideRecipe' => 'Y',
		'prefsUseMods' => sterilize($_POST['prefsUseMods']),
		'prefsSEF' => sterilize($_POST['prefsSEF']),
		'prefsSpecialCharLimit' => sterilize($_POST['prefsSpecialCharLimit']),
		'prefsStyleSet' => sterilize($_POST['prefsStyleSet']),
		'prefsAutoPurge' => sterilize($_POST['prefsAutoPurge']),
		'prefsEntryLimitPaid' => sterilize($_POST['prefsEntryLimitPaid']),
		'prefsEmailRegConfirm' => sterilize($_POST['prefsEmailRegConfirm']),
		'prefsEmailCC' => sterilize($_POST['prefsEmailCC']),
		'prefsLanguage' => sterilize($_POST['prefsLanguage']),
		'prefsSpecific' => sterilize($_POST['prefsSpecific']),
		'prefsDropOff' => sterilize($_POST['prefsDropOff']),
		'prefsShipping' => sterilize($_POST['prefsShipping']),
		'prefsBestUseBOS' => sterilize($_POST['prefsBestUseBOS']),
		'prefsEval' => sterilize($_POST['prefsEval'])
	);

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
            		
            		$query_style_name = sprintf("SELECT id,brewStyleNum FROM %s WHERE brewStyleOwn='custom' ORDER BY id", $prefix."styles");
					$style_name = mysqli_query($connection,$query_style_name) or die (mysqli_error($connection));
					$row_style_name = mysqli_fetch_assoc($style_name);

					$query_style_num = sprintf("SELECT brewStyleNum FROM %s WHERE brewStyleVersion='BA' ORDER BY brewStyleNum DESC LIMIT 1", $prefix."styles");
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
				
				$result = $db_conn->rawQuery($sql);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

		} // end if ($_POST['prefsPaypalIPN'] == 1)

		// Check to see if processed correctly.
		$query_prefs_check = sprintf("SELECT COUNT(*) as 'count' FROM %s",$preferences_db_table);
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

		if (!empty($error_output)) $_SESSION['error_output'] = $error_output;

		// If not, redirect back to step 3 and display message.
		else  $insertGoTo = $base_url."setup.php?section=step3&msg=99";
		if ($errors) $insertGoTo = $base_url."setup.php?section=step3&msg=3";
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

		/**
		 * If the style set has changed from BJCP 2015 to BJCP 2021, map
		 * 2015 styles to updated 2021 styles in brewing DB.
		 * Update scripting changed BJCP2008 preference to BJCP2015 and
		 * performed a conversion from 2008 to 2015 on all entries in the DB.
		 */
		
		if ($prefsStyleSet == "BJCP2021") {
			
			if ($_SESSION['prefsStyleSet'] == "BJCP2015") {

				include (LIB.'convert.lib.php');
				include (INCLUDES.'convert/convert_bjcp_2021.inc.php');
				
			}

		}

		// Update style set of any custom styles to chosen style set
		// Safeguards against a bug introduced in 2.1.13 scripting
		// Also update sub-style idenfication scheming

		// Update style set of any custom styles to chosen style set
		// Safeguards against a bug introduced in 2.1.13 scripting
		// Also update sub-style idenfication scheming

		foreach ($style_sets as $key) {
            
            if ($key['style_set_name'] == $prefsStyleSet) {
            	
            	if ($prefsStyleSet == "BA") {
            		
            		$query_style_name = sprintf("SELECT id,brewStyleNum FROM %s WHERE brewStyleOwn='custom' ORDER BY id", $prefix."styles");
					$style_name = mysqli_query($connection,$query_style_name) or die (mysqli_error($connection));
					$row_style_name = mysqli_fetch_assoc($style_name);

					$query_style_num = sprintf("SELECT brewStyleNum FROM %s WHERE brewStyleVersion='BA' ORDER BY brewStyleNum DESC LIMIT 1", $prefix."styles");
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
				
				$result = $db_conn->rawQuery($sql);
				if (!$result) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

		} // end if ($_POST['prefsPaypalIPN'] == 1)

		// Empty the prefs session variable
		// Will trigger the session to reset the variables in common.db.php upon reload after redirect
		session_name($prefix_session);
		session_start();
		unset($_SESSION['prefs'.$prefix_session]);

		if ($style_alert) $updateGoTo .= $_POST['relocate']."&msg=37";

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
		$updateGoTo = prep_redirect_link($updateGoTo);
		$redirect_go_to = sprintf("Location: %s", $updateGoTo);

	}

} else {

	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);

}
?>