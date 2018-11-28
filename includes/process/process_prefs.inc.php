<?php
/*
 * Module:      process_prefs_add.inc.php
 * Description: This module does all the heavy lifting for adding information to the
 *              "preferences" table.
 */

if ((isset($_SERVER['HTTP_REFERER'])) && (((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) || ($section == "setup"))) {

	$prefsGoogleAccount = "";
	if ((isset($_POST['prefsGoogleAccount0'])) && (isset($_POST['prefsGoogleAccount1']))) $prefsGoogleAccount = $_POST['prefsGoogleAccount0']."|".$_POST['prefsGoogleAccount1'];

	if (isset($_POST['prefsUSCLEx'])) $prefsUSCLEx = implode(",",$_POST['prefsUSCLEx']);
	else  $prefsUSCLEx = "";

	$prefsStyleSet = "";
	$style_alert = FALSE;

	$prefsStyleSet = sterilize($_POST['prefsStyleSet']);

	if (!empty($_POST['prefsWinnerDelay'])) $prefsWinnerDelay = strtotime(sterilize($_POST['prefsWinnerDelay']));
	else $prefsWinnerDelay = 2145916800;

	if ($action == "add") {
		$insertSQL = sprintf("INSERT INTO $preferences_db_table (
		prefsTemp,
		prefsWeight1,
		prefsWeight2,
		prefsLiquid1,
		prefsLiquid2,

		prefsPaypal,
		prefsPaypalAccount,
		prefsCurrency,
		prefsCash,
		prefsCheck,

		prefsCheckPayee,
		prefsTransFee,
		prefsSponsors,

		prefsSponsorLogos,
		prefsDisplayWinners,
		prefsWinnerDelay,

		prefsWinnerMethod,
		prefsEntryForm,
		prefsRecordLimit,
		prefsRecordPaging,

		prefsTheme,
		prefsDateFormat,
		prefsContact,
		prefsTimeZone,
		prefsEntryLimit,

		prefsTimeFormat,
		prefsUserEntryLimit,
		prefsUserSubCatLimit,
		prefsUSCLEx,
		prefsUSCLExLimit,

		prefsPayToPrint,
		prefsHideRecipe,
		prefsUseMods,
		prefsSEF,
		prefsSpecialCharLimit,

		prefsStyleSet,
		prefsAutoPurge,
		prefsEntryLimitPaid,
		prefsEmailRegConfirm,
		prefsSpecific,

		prefsDropOff,
		prefsShipping,
		prefsPaypalIPN,
		prefsProEdition,
		prefsDisplaySpecial,

		prefsShowBestBrewer,
		prefsBestBrewerTitle,
		prefsShowBestClub,
		prefsBestClubTitle,
		prefsFirstPlacePts,
		prefsSecondPlacePts,
		prefsThirdPlacePts,

		prefsFourthPlacePts,
		prefsHMPts,
		prefsTieBreakRule1,
		prefsTieBreakRule2,
		prefsTieBreakRule3,

		prefsTieBreakRule4,
		prefsTieBreakRule5,
		prefsTieBreakRule6,
		prefsCAPTCHA,
		prefsGoogleAccount,
		prefsBestUseBOS,
		prefsLanguage,
		id

		) VALUES (
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s,
		%s, %s, %s, %s, %s)",

			GetSQLValueString(sterilize($_POST['prefsTemp']), "text"),
			GetSQLValueString(sterilize($_POST['prefsWeight1']), "text"),
			GetSQLValueString(sterilize($_POST['prefsWeight2']), "text"),
			GetSQLValueString(sterilize($_POST['prefsLiquid1']), "text"),
			GetSQLValueString(sterilize($_POST['prefsLiquid2']), "text"),

			GetSQLValueString(sterilize($_POST['prefsPaypal']), "text"),
			GetSQLValueString(sterilize($_POST['prefsPaypalAccount']), "text"),
			GetSQLValueString(sterilize($_POST['prefsCurrency']), "text"),
			GetSQLValueString(sterilize($_POST['prefsCash']), "text"),
			GetSQLValueString(sterilize($_POST['prefsCheck']), "text"),

			GetSQLValueString(sterilize($_POST['prefsCheckPayee']), "text"),
			GetSQLValueString(sterilize($_POST['prefsTransFee']), "text"),
			GetSQLValueString(sterilize($_POST['prefsSponsors']), "text"),

			GetSQLValueString(sterilize($_POST['prefsSponsorLogos']), "text"),
			GetSQLValueString(sterilize($_POST['prefsDisplayWinners']), "text"),
			GetSQLValueString($prefsWinnerDelay, "text"),

			GetSQLValueString(sterilize($_POST['prefsWinnerMethod']), "text"),
			GetSQLValueString(sterilize($_POST['prefsEntryForm']), "text"),
			GetSQLValueString(sterilize($_POST['prefsRecordLimit']), "int"),
			GetSQLValueString(sterilize($_POST['prefsRecordPaging']), "int"),

			GetSQLValueString(sterilize($_POST['prefsTheme']), "text"),
			GetSQLValueString(sterilize($_POST['prefsDateFormat']), "text"),
			GetSQLValueString(sterilize($_POST['prefsContact']), "text"),
			GetSQLValueString(sterilize($_POST['prefsTimeZone']), "text"),
			GetSQLValueString(sterilize($_POST['prefsEntryLimit']), "text"),

			GetSQLValueString(sterilize($_POST['prefsTimeFormat']), "text"),
			GetSQLValueString(sterilize($_POST['prefsUserEntryLimit']), "int"),
			GetSQLValueString(sterilize($_POST['prefsUserSubCatLimit']), "int"),
			GetSQLValueString($prefsUSCLEx, "text"),
			GetSQLValueString(sterilize($_POST['prefsUSCLExLimit']), "int"),

			GetSQLValueString(sterilize($_POST['prefsPayToPrint']), "text"),
			GetSQLValueString(sterilize($_POST['prefsHideRecipe']), "text"),
			GetSQLValueString(sterilize($_POST['prefsUseMods']), "text"),
			GetSQLValueString(sterilize($_POST['prefsSEF']), "text"),
			GetSQLValueString(sterilize($_POST['prefsSpecialCharLimit']), "int"),
			GetSQLValueString($prefsStyleSet, "text"),
			GetSQLValueString(sterilize($_POST['prefsAutoPurge']), "text"),
			GetSQLValueString(sterilize($_POST['prefsEntryLimitPaid']), "int"),
			GetSQLValueString(sterilize($_POST['prefsEmailRegConfirm']), "int"),
			GetSQLValueString(sterilize($_POST['prefsSpecific']), "int"),
			GetSQLValueString(sterilize($_POST['prefsDropOff']), "int"),
			GetSQLValueString(sterilize($_POST['prefsShipping']), "int"),
			GetSQLValueString(sterilize($_POST['prefsPaypalIPN']), "int"),
			GetSQLValueString(sterilize($_POST['prefsProEdition']), "int"),
			GetSQLValueString(sterilize($_POST['prefsDisplaySpecial']), "text"),

			GetSQLValueString(sterilize($_POST['prefsShowBestBrewer']), "int"),
			GetSQLValueString(sterilize($_POST['prefsBestBrewerTitle']), "text"),
			GetSQLValueString(sterilize($_POST['prefsShowBestClub']), "int"),
			GetSQLValueString(sterilize($_POST['prefsBestClubTitle']), "text"),
			GetSQLValueString(sterilize($_POST['prefsFirstPlacePts']), "int"),
			GetSQLValueString(sterilize($_POST['prefsSecondPlacePts']), "int"),
			GetSQLValueString(sterilize($_POST['prefsThirdPlacePts']), "int"),

			GetSQLValueString(sterilize($_POST['prefsFourthPlacePts']), "int"),
			GetSQLValueString(sterilize($_POST['prefsHMPts']), "int"),
			GetSQLValueString(sterilize($_POST['prefsTieBreakRule1']), "text"),
			GetSQLValueString(sterilize($_POST['prefsTieBreakRule2']), "text"),
			GetSQLValueString(sterilize($_POST['prefsTieBreakRule3']), "text"),

			GetSQLValueString(sterilize($_POST['prefsTieBreakRule4']), "text"),
			GetSQLValueString(sterilize($_POST['prefsTieBreakRule5']), "text"),
			GetSQLValueString(sterilize($_POST['prefsTieBreakRule6']), "text"),
			GetSQLValueString(sterilize($_POST['prefsCAPTCHA']), "text"),
			GetSQLValueString(sterilize($prefsGoogleAccount), "text"),
			GetSQLValueString($_POST['prefsBestUseBOS'], "int"),
			GetSQLValueString(sterilize($_POST['prefsLanguage']), "text"),
			GetSQLValueString($id, "int"));

			mysqli_real_escape_string($connection,$insertSQL);
			$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));

			// Update style set of any custom styles to chosen style set
			// Safeguards against a bug introduced in 2.1.13 scripting
			$updateSQL = sprintf("UPDATE `%s` SET brewStyleVersion='%s' WHERE brewStyleOwn='custom'",$prefix."styles",$prefsStyleSet);
			mysqli_select_db($connection,$database);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL);

			if ($_POST['prefsPaypalIPN'] == 1) {

				include (LIB.'update.lib.php');

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
					mysqli_select_db($connection,$database);
					mysqli_real_escape_string($connection,$sql);
					$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

				}

			}

			// Check to see if processed correctly.
			$query_prefs_check = sprintf("SELECT COUNT(*) as 'count' FROM %s",$preferences_db_table);
			$prefs_check = mysqli_query($connection,$query_prefs_check) or die (mysqli_error($connection));
			$row_prefs_check = mysqli_fetch_assoc($prefs_check);

			// If so, mark step as complete in system table and redirect to next step.
			if ($row_prefs_check['count'] == 1) {

				$sql = sprintf("UPDATE `%s` SET setup_last_step = '3' WHERE id='1';", $system_db_table);
				mysqli_select_db($connection,$database);
				mysqli_real_escape_string($connection,$sql);
				$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

				$insertGoTo = $base_url."setup.php?section=step4";

			}

			// If not, redirect back to step 3 and display message.
			else  $insertGoTo = $base_url."setup.php?section=step3&msg=99";

			$pattern = array('\'', '"');
			$insertGoTo = str_replace($pattern, "", $insertGoTo);
			$redirect_go_to = sprintf("Location: %s", stripslashes($insertGoTo));

	}

	if ($action == "edit") {

		$updateSQL = sprintf("UPDATE $preferences_db_table SET
		prefsTemp=%s,
		prefsWeight1=%s,
		prefsWeight2=%s,
		prefsLiquid1=%s,
		prefsLiquid2=%s,

		prefsPaypal=%s,
		prefsPaypalAccount=%s,
		prefsCurrency=%s,
		prefsCash=%s,
		prefsCheck=%s,

		prefsCheckPayee=%s,
		prefsTransFee=%s,
		prefsSponsors=%s,
		prefsDisplayWinners=%s,
		prefsWinnerDelay=%s,

		prefsWinnerMethod=%s,
		prefsEntryForm=%s,
		prefsRecordLimit=%s,
		prefsRecordPaging=%s,

		prefsTheme=%s,
		prefsDateFormat=%s,
		prefsContact=%s,
		prefsTimeZone=%s,
		prefsEntryLimit=%s,

		prefsTimeFormat=%s,
		prefsUserEntryLimit=%s,
		prefsUserSubCatLimit=%s,
		prefsUSCLEx=%s,
		prefsUSCLExLimit=%s,

		prefsPayToPrint=%s,
		prefsHideRecipe=%s,
		prefsUseMods=%s,
		prefsSEF=%s,
		prefsSpecialCharLimit=%s,

		prefsStyleSet=%s,
		prefsAutoPurge=%s,
		prefsEntryLimitPaid=%s,
		prefsEmailRegConfirm=%s,
		prefsSponsorLogos=%s,
		prefsSpecific=%s,

		prefsDropOff=%s,
		prefsShipping=%s,
		prefsPaypalIPN=%s,
		prefsProEdition=%s,
		prefsDisplaySpecial=%s,

		prefsShowBestBrewer=%s,
		prefsBestBrewerTitle=%s,
		prefsShowBestClub=%s,
		prefsBestClubTitle=%s,
		prefsBestUseBOS=%s,
		prefsFirstPlacePts=%s,
		prefsSecondPlacePts=%s,
		prefsThirdPlacePts=%s,

		prefsFourthPlacePts=%s,
		prefsHMPts=%s,
		prefsTieBreakRule1=%s,
		prefsTieBreakRule2=%s,
		prefsTieBreakRule3=%s,

		prefsTieBreakRule4=%s,
		prefsTieBreakRule5=%s,
		prefsTieBreakRule6=%s,
		prefsCAPTCHA=%s,
		prefsGoogleAccount=%s,
		prefsLanguage=%s

		WHERE id=%s",
			GetSQLValueString(sterilize($_POST['prefsTemp']), "text"),
			GetSQLValueString(sterilize($_POST['prefsWeight1']), "text"),
			GetSQLValueString(sterilize($_POST['prefsWeight2']), "text"),
			GetSQLValueString(sterilize($_POST['prefsLiquid1']), "text"),
			GetSQLValueString(sterilize($_POST['prefsLiquid2']), "text"),

			GetSQLValueString(sterilize($_POST['prefsPaypal']), "text"),
			GetSQLValueString(sterilize($_POST['prefsPaypalAccount']), "text"),
			GetSQLValueString(sterilize($_POST['prefsCurrency']), "text"),
			GetSQLValueString(sterilize($_POST['prefsCash']), "text"),
			GetSQLValueString(sterilize($_POST['prefsCheck']), "text"),

			GetSQLValueString(sterilize($_POST['prefsCheckPayee']), "text"),
			GetSQLValueString(sterilize($_POST['prefsTransFee']), "text"),
			GetSQLValueString(sterilize($_POST['prefsSponsors']), "text"),
			GetSQLValueString(sterilize($_POST['prefsDisplayWinners']), "text"),
			GetSQLValueString($prefsWinnerDelay, "text"),

			GetSQLValueString(sterilize($_POST['prefsWinnerMethod']), "int"),
			GetSQLValueString(sterilize($_POST['prefsEntryForm']), "text"),
			GetSQLValueString(sterilize($_POST['prefsRecordLimit']), "int"),
			GetSQLValueString(sterilize($_POST['prefsRecordPaging']), "int"),

			GetSQLValueString(sterilize($_POST['prefsTheme']), "text"),
			GetSQLValueString(sterilize($_POST['prefsDateFormat']), "text"),
			GetSQLValueString(sterilize($_POST['prefsContact']), "text"),
			GetSQLValueString(sterilize($_POST['prefsTimeZone']), "text"),
			GetSQLValueString(sterilize($_POST['prefsEntryLimit']), "text"),

			GetSQLValueString(sterilize($_POST['prefsTimeFormat']), "text"),
			GetSQLValueString(sterilize($_POST['prefsUserEntryLimit']), "int"),
			GetSQLValueString(sterilize($_POST['prefsUserSubCatLimit']), "int"),
			GetSQLValueString($prefsUSCLEx, "text"),
			GetSQLValueString(sterilize($_POST['prefsUSCLExLimit']), "int"),

			GetSQLValueString(sterilize($_POST['prefsPayToPrint']), "text"),
			GetSQLValueString(sterilize($_POST['prefsHideRecipe']), "text"),
			GetSQLValueString(sterilize($_POST['prefsUseMods']), "text"),
			GetSQLValueString(sterilize($_POST['prefsSEF']), "text"),
			GetSQLValueString(sterilize($_POST['prefsSpecialCharLimit']), "int"),

			GetSQLValueString($prefsStyleSet, "text"),
			GetSQLValueString(sterilize($_POST['prefsAutoPurge']), "int"),
			GetSQLValueString(sterilize($_POST['prefsEntryLimitPaid']), "int"),
			GetSQLValueString(sterilize($_POST['prefsEmailRegConfirm']), "int"),
			GetSQLValueString(sterilize($_POST['prefsSponsorLogos']), "text"),
			GetSQLValueString(sterilize($_POST['prefsSpecific']), "int"),

			GetSQLValueString(sterilize($_POST['prefsDropOff']), "int"),
			GetSQLValueString(sterilize($_POST['prefsShipping']), "int"),
			GetSQLValueString(sterilize($_POST['prefsPaypalIPN']), "int"),
			GetSQLValueString(sterilize($_POST['prefsProEdition']), "int"),
			GetSQLValueString(sterilize($_POST['prefsDisplaySpecial']), "text"),

			GetSQLValueString(sterilize($_POST['prefsShowBestBrewer']), "int"),
			GetSQLValueString(sterilize($_POST['prefsBestBrewerTitle']), "text"),
			GetSQLValueString(sterilize($_POST['prefsShowBestClub']), "int"),
			GetSQLValueString(sterilize($_POST['prefsBestClubTitle']), "text"),
			GetSQLValueString($_POST['prefsBestUseBOS'], "int"),
			GetSQLValueString(sterilize($_POST['prefsFirstPlacePts']), "int"),
			GetSQLValueString(sterilize($_POST['prefsSecondPlacePts']), "int"),
			GetSQLValueString(sterilize($_POST['prefsThirdPlacePts']), "int"),

			GetSQLValueString(sterilize($_POST['prefsFourthPlacePts']), "int"),
			GetSQLValueString(sterilize($_POST['prefsHMPts']), "int"),
			GetSQLValueString(sterilize($_POST['prefsTieBreakRule1']), "text"),
			GetSQLValueString(sterilize($_POST['prefsTieBreakRule2']), "text"),
			GetSQLValueString(sterilize($_POST['prefsTieBreakRule3']), "text"),

			GetSQLValueString(sterilize($_POST['prefsTieBreakRule4']), "text"),
			GetSQLValueString(sterilize($_POST['prefsTieBreakRule5']), "text"),
			GetSQLValueString(sterilize($_POST['prefsTieBreakRule6']), "text"),
			GetSQLValueString(sterilize($_POST['prefsCAPTCHA']), "text"),
			GetSQLValueString(sterilize($prefsGoogleAccount), "text"),
			GetSQLValueString(sterilize($_POST['prefsLanguage']), "text"),
			GetSQLValueString($id, "int"));

			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			//echo $updateSQL; exit;

			// Update style set of any custom styles to chosen style set
			// Safeguards against a bug introduced in 2.1.13 scripting
			$updateSQL = sprintf("UPDATE `%s` SET brewStyleVersion='%s' WHERE brewStyleOwn='custom'",$prefix."styles",$prefsStyleSet);
			mysqli_select_db($connection,$database);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL);

			if ($_POST['prefsPaypalIPN'] == 1) {

				include (LIB.'update.lib.php');

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
					mysqli_select_db($connection,$database);
					mysqli_real_escape_string($connection,$sql);
					$result = mysqli_query($connection,$sql) or die (mysqli_error($connection));

				}

			}

			// Empty the prefs session variable
			// Will trigger the session to reset the variables in common.db.php upon reload after redirect
			session_name($prefix_session);
			session_start();
			unset($_SESSION['prefs'.$prefix_session]);

			if ($style_alert) $updateGoTo .= $_POST['relocate']."&msg=37";

			$pattern = array('\'', '"');
			$updateGoTo = str_replace($pattern, "", $updateGoTo);
			$redirect_go_to = sprintf("Location: %s", stripslashes($updateGoTo));
	}

} else {
	$redirect_go_to = sprintf("Location: %s", $base_url."index.php?msg=98");
}
?>