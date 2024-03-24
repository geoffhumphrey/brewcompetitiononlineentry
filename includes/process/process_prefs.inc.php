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

	// Sanity check for prefs
	if ($section == "setup") {
		$query_prefs = sprintf("SELECT * FROM %s WHERE id='1'", $prefix."preferences");
		$prefs = mysqli_query($connection,$query_prefs) or die (mysqli_error($connection));
		$row_prefs = mysqli_fetch_assoc($prefs);
		$totalRows_prefs = mysqli_num_rows($prefs);

		if ($totalRows_prefs < 1) $action = "add";
	}

	$style_alert = FALSE;
	$style_set_change = FALSE;

	$prefsGoogleAccount = "";
	$prefsUSCLEx = "";
	$prefsBestBrewerTitle = "";
	$prefsBestClubTitle = "";

	if ((isset($_POST['prefsGoogleAccount0'])) && (isset($_POST['prefsGoogleAccount1']))) $prefsGoogleAccount = $_POST['prefsGoogleAccount0']."|".$_POST['prefsGoogleAccount1'];

	if (isset($_POST['prefsUSCLEx'])) $prefsUSCLEx = implode(",",$_POST['prefsUSCLEx']);

	if (HOSTED) $prefsCAPTCHA = 1;
	else $prefsCAPTCHA = sterilize($_POST['prefsCAPTCHA']);

	if (HOSTED) $prefsEmailCC = 1;
	else $prefsEmailCC = sterilize($_POST['prefsEmailCC']);

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

	if ($_POST['prefsStyleSet'] != $_SESSION['prefsStyleSet']) $style_set_change = TRUE;
		
	$update_table = $prefix."preferences";

	if (!isset($_POST['prefsCompLogoSize'])) $_POST['prefsCompLogoSize'] = NULL;
	if (!isset($_POST['prefsBOSMead'])) $_POST['prefsBOSMead'] = NULL;
	if (!isset($_POST['prefsBOSCider'])) $_POST['prefsBOSCider'] = NULL;

	$prefsStyleSet = sterilize($_POST['prefsStyleSet']);
	$prefsBestUseBOS = sterilize($_POST['prefsBestUseBOS']);
	$prefsScoringCOA = sterilize($_POST['prefsScoringCOA']);

	if ($prefsScoringCOA == 1) $prefsBestUseBOS = 0;

	$data = array(
		'prefsTemp' => sterilize($_POST['prefsTemp']),
		'prefsWeight1' => sterilize($_POST['prefsWeight1']),
		'prefsWeight2' => sterilize($_POST['prefsWeight2']),
		'prefsLiquid1' => sterilize($_POST['prefsLiquid1']),
		'prefsLiquid2' => sterilize($_POST['prefsLiquid2']),
		'prefsPaypal' => sterilize($_POST['prefsPaypal']),
		'prefsPaypalAccount' => blank_to_null(sterilize($_POST['prefsPaypalAccount'])),
		'prefsPaypalIPN' => sterilize($_POST['prefsPaypalIPN']),
		'prefsCurrency' => sterilize($_POST['prefsCurrency']),
		'prefsCash' => sterilize($_POST['prefsCash']),
		'prefsCheck' => sterilize($_POST['prefsCheck']),
		'prefsCheckPayee' => blank_to_null(sterilize($_POST['prefsCheckPayee'])),
		'prefsTransFee' => sterilize($_POST['prefsTransFee']),
		'prefsCAPTCHA' => blank_to_null($prefsCAPTCHA),
		'prefsGoogleAccount' => blank_to_null($prefsGoogleAccount),
		'prefsSponsors' => sterilize($_POST['prefsSponsors']),
		'prefsSponsorLogos' => sterilize($_POST['prefsSponsorLogos']),
		'prefsCompLogoSize' => blank_to_null(sterilize($_POST['prefsCompLogoSize'])),
		'prefsDisplayWinners' => sterilize($_POST['prefsDisplayWinners']),
		'prefsWinnerDelay' => $prefsWinnerDelay,
		'prefsWinnerMethod' => sterilize($_POST['prefsWinnerMethod']),
		'prefsDisplaySpecial' => sterilize($_POST['prefsDisplaySpecial']),
		'prefsBOSMead' => sterilize($_POST['prefsBOSMead']),
		'prefsBOSCider' => sterilize($_POST['prefsBOSCider']),
		'prefsShowBestBrewer' => sterilize($_POST['prefsShowBestBrewer']),
		'prefsBestBrewerTitle' => blank_to_null($prefsBestBrewerTitle),
		'prefsShowBestClub' => sterilize($_POST['prefsShowBestClub']),
		'prefsBestClubTitle' => blank_to_null($prefsBestClubTitle),
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
		'prefsTieBreakRule6' => blank_to_null(sterilize($_POST['prefsTieBreakRule6'])),
		'prefsEntryForm' => sterilize($_POST['prefsEntryForm']),
		'prefsRecordLimit' => blank_to_null(sterilize($_POST['prefsRecordLimit'])),
		'prefsRecordPaging' => blank_to_null(sterilize($_POST['prefsRecordPaging'])),
		'prefsProEdition' => sterilize($_POST['prefsProEdition']),
		'prefsTheme' => sterilize($_POST['prefsTheme']),
		'prefsDateFormat' => sterilize($_POST['prefsDateFormat']),
		'prefsContact' => sterilize($_POST['prefsContact']),
		'prefsTimeZone' => sterilize($_POST['prefsTimeZone']),
		'prefsEntryLimit' => blank_to_null(sterilize($_POST['prefsEntryLimit'])),
		'prefsTimeFormat' => sterilize($_POST['prefsTimeFormat']),
		'prefsUserEntryLimit' => blank_to_null(sterilize($_POST['prefsUserEntryLimit'])),
		'prefsUserSubCatLimit' => blank_to_null(sterilize($_POST['prefsUserSubCatLimit'])),
		'prefsUSCLEx' => blank_to_null($prefsUSCLEx),
		'prefsUSCLExLimit' => blank_to_null(sterilize($_POST['prefsUSCLExLimit'])),
		'prefsPayToPrint' => sterilize($_POST['prefsPayToPrint']),
		'prefsHideRecipe' => 'Y',
		'prefsUseMods' => sterilize($_POST['prefsUseMods']),
		'prefsSEF' => sterilize($_POST['prefsSEF']),
		'prefsSpecialCharLimit' => sterilize($_POST['prefsSpecialCharLimit']),
		'prefsStyleSet' => $prefsStyleSet,
		'prefsAutoPurge' => sterilize($_POST['prefsAutoPurge']),
		'prefsEntryLimitPaid' => blank_to_null(sterilize($_POST['prefsEntryLimitPaid'])),
		'prefsEmailRegConfirm' => sterilize($_POST['prefsEmailRegConfirm']),
		'prefsEmailCC' => $prefsEmailCC,
		'prefsLanguage' => sterilize($_POST['prefsLanguage']),
		'prefsSpecific' => sterilize($_POST['prefsSpecific']),
		'prefsDropOff' => sterilize($_POST['prefsDropOff']),
		'prefsShipping' => sterilize($_POST['prefsShipping']),
		'prefsBestUseBOS' => $prefsBestUseBOS,
		'prefsEval' => sterilize($_POST['prefsEval']),
		'prefsScoringCOA' => $prefsScoringCOA
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

		// Update style set of any custom styles to chosen style set
		// Safeguards against a bug introduced in 2.1.13 scripting
		// Also update sub-style idenfication scheming

		foreach ($style_sets as $key) {
            
            if ($key['style_set_name'] == $prefsStyleSet) {
            	
            	if ($prefsStyleSet == "BA") {
            		
            		$query_style_name = sprintf("SELECT id,brewStyleNum,brewStyle FROM %s WHERE brewStyleOwn='custom' ORDER BY id", $prefix."styles");
					$style_name = mysqli_query($connection,$query_style_name) or die (mysqli_error($connection));
					$row_style_name = mysqli_fetch_assoc($style_name);

					$query_style_num = sprintf("SELECT brewStyleNum FROM %s WHERE brewStyleVersion='BA' ORDER BY brewStyleNum DESC LIMIT 1", $prefix."styles");
					$style_num = mysqli_query($connection,$query_style_num) or die (mysqli_error($connection));
					$row_style_num = mysqli_fetch_assoc($style_num);

					$sub_style_id = $row_style_num['brewStyleNum'] + 1;

					do {

						$sub_style = str_pad($sub_style_id,3,"0", STR_PAD_LEFT);
						
						$update_table = $prefix."styles";
						$data = array(
							'brewStyleVersion' => $prefsStyleSet,
							'brewStyleNum' => $sub_style
						);
						$db_conn->where ('id', $row_style_name['id']);
						$result = $db_conn->update ($update_table, $data);
						if (!$result) {
							$error_output[] = $db_conn->getLastError();
							$errors = TRUE;
						}

						$update_table = $prefix."brewing";
						$data = array(
							'brewSubCategory' => $sub_style
						);
						$db_conn->where ('brewStyle', $row_style_name['brewStyle']);
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

	            	$update_table = $prefix."brewing";
	            	$data = array(
	            		'brewSubCategory' => $sub_style_id
	            	);
	            	$db_conn->where ('brewCategory', 50, ">=");
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
				
				$db_conn->rawQuery($sql);
				if ($db_conn->getLastErrno() !== 0) {
					$error_output[] = $db_conn->getLastError();
					$errors = TRUE;
				}

			}

		} // end if ($_POST['prefsPaypalIPN'] == 1)

		// Finally, if the style set has changed, add all styles 
		// in the chosen style set as active.
		if ($style_set_change) {

			$update_selected_styles = array();

			/*
			if (HOSTED) $query_styles_default = sprintf("SELECT id, brewStyle, brewStyleGroup, brewStyleNum, brewStyleVersion FROM `%s` WHERE brewStyleVersion='%s' UNION ALL SELECT id, brewStyle, brewStyleGroup, brewStyleNum, brewStyleVersion FROM `%s` WHERE brewStyleVersion='%s';", $styles_db_table, $prefsStyleSet, $prefix."styles", $prefsStyleSet);
			else 
			*/
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
		if ($style_set_change) $updateGoTo = $base_url."index.php?section=admin&go=styles&msg=37";
		$updateGoTo = prep_redirect_link($updateGoTo);
		$redirect_go_to = sprintf("Location: %s", $updateGoTo);

	}

} else {

	$redirect = $base_url."index.php?msg=98";
	$redirect = prep_redirect_link($redirect);
	$redirect_go_to = sprintf("Location: %s", $redirect);

}
?>