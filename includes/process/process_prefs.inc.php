<?php
/*
 * Module:      process_prefs_add.inc.php
 * Description: This module does all the heavy lifting for adding information to the
 *              "preferences" table.
 */

if ((isset($_SERVER['HTTP_REFERER'])) && (((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) || ($section == "setup"))) {

	if (isset($_POST['prefsUSCLEx'])) $prefsUSCLEx = implode(",",$_POST['prefsUSCLEx']);
	else  $prefsUSCLEx = "";

	$prefsStyleSet = "";


	if (strpos($_SESSION['prefsStyleSet'],"BABDB") !== false) {

		$ba_styleSet_explodies = explode("|",$_SESSION['prefsStyleSet']);

	}

	if ((isset($_POST['prefsStyleSetAPIKey'])) && ($_POST['prefsStyleSet'] == "BABDB")) {
		 $prefsStyleSet = $_POST['prefsStyleSet']."|".$_POST['prefsStyleSetAPIKey'];
		 if (isset($ba_styleSet_explodies[2])) $prefsStyleSet .= "|".$ba_styleSet_explodies[2];
	}

	else $prefsStyleSet = $_POST['prefsStyleSet'];

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
		%s, %s, %s, %s, %s)",
							   GetSQLValueString($_POST['prefsTemp'], "text"),
							   GetSQLValueString($_POST['prefsWeight1'], "text"),
							   GetSQLValueString($_POST['prefsWeight2'], "text"),
							   GetSQLValueString($_POST['prefsLiquid1'], "text"),
							   GetSQLValueString($_POST['prefsLiquid2'], "text"),

							   GetSQLValueString($_POST['prefsPaypal'], "text"),
							   GetSQLValueString($_POST['prefsPaypalAccount'], "text"),
							   GetSQLValueString($_POST['prefsCurrency'], "text"),
							   GetSQLValueString($_POST['prefsCash'], "text"),
							   GetSQLValueString($_POST['prefsCheck'], "text"),

							   GetSQLValueString($_POST['prefsCheckPayee'], "text"),
							   GetSQLValueString($_POST['prefsTransFee'], "text"),
							   GetSQLValueString($_POST['prefsSponsors'], "text"),

							   GetSQLValueString($_POST['prefsSponsorLogos'], "text"),
							   GetSQLValueString($_POST['prefsDisplayWinners'], "text"),
							   GetSQLValueString($_POST['prefsWinnerDelay'], "text"),

							   GetSQLValueString($_POST['prefsWinnerMethod'], "text"),
							   GetSQLValueString($_POST['prefsEntryForm'], "text"),
							   GetSQLValueString($_POST['prefsRecordLimit'], "int"),
							   GetSQLValueString($_POST['prefsRecordPaging'], "int"),

							   GetSQLValueString($_POST['prefsTheme'], "text"),
							   GetSQLValueString($_POST['prefsDateFormat'], "text"),
							   GetSQLValueString($_POST['prefsContact'], "text"),
							   GetSQLValueString($_POST['prefsTimeZone'], "text"),
							   GetSQLValueString($_POST['prefsEntryLimit'], "text"),

							   GetSQLValueString($_POST['prefsTimeFormat'], "text"),
							   GetSQLValueString($_POST['prefsUserEntryLimit'], "int"),
							   GetSQLValueString($_POST['prefsUserSubCatLimit'], "int"),
							   GetSQLValueString($prefsUSCLEx, "text"),
							   GetSQLValueString($_POST['prefsUSCLExLimit'], "int"),

							   GetSQLValueString($_POST['prefsPayToPrint'], "text"),
							   GetSQLValueString($_POST['prefsHideRecipe'], "text"),
							   GetSQLValueString($_POST['prefsUseMods'], "text"),
							   GetSQLValueString($_POST['prefsSEF'], "text"),
							   GetSQLValueString($_POST['prefsSpecialCharLimit'], "int"),
							   GetSQLValueString($prefsStyleSet, "text"),
							   GetSQLValueString($_POST['prefsAutoPurge'], "text"),
							   GetSQLValueString($_POST['prefsEntryLimitPaid'], "int"),
							   GetSQLValueString($_POST['prefsEmailRegConfirm'], "int"),
							   GetSQLValueString($_POST['prefsSpecific'], "int"),
							   GetSQLValueString($_POST['prefsDropOff'], "int"),
							   GetSQLValueString($_POST['prefsShipping'], "int"),
							   GetSQLValueString($_POST['prefsPaypalIPN'], "int"),
							   GetSQLValueString($_POST['prefsProEdition'], "int"),
							   GetSQLValueString($id, "int"));

			mysqli_real_escape_string($connection,$insertSQL);
			$result = mysqli_query($connection,$insertSQL) or die (mysqli_error($connection));

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
			header(sprintf("Location: %s", stripslashes($insertGoTo)));

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
		prefsProEdition=%s

		WHERE id=%s",
							   GetSQLValueString($_POST['prefsTemp'], "text"),
							   GetSQLValueString($_POST['prefsWeight1'], "text"),
							   GetSQLValueString($_POST['prefsWeight2'], "text"),
							   GetSQLValueString($_POST['prefsLiquid1'], "text"),
							   GetSQLValueString($_POST['prefsLiquid2'], "text"),

							   GetSQLValueString($_POST['prefsPaypal'], "text"),
							   GetSQLValueString($_POST['prefsPaypalAccount'], "text"),
							   GetSQLValueString($_POST['prefsCurrency'], "text"),
							   GetSQLValueString($_POST['prefsCash'], "text"),
							   GetSQLValueString($_POST['prefsCheck'], "text"),

							   GetSQLValueString($_POST['prefsCheckPayee'], "text"),
							   GetSQLValueString($_POST['prefsTransFee'], "text"),
							   GetSQLValueString($_POST['prefsSponsors'], "text"),
							   GetSQLValueString($_POST['prefsDisplayWinners'], "text"),
							   GetSQLValueString($_POST['prefsWinnerDelay'], "text"),

							   GetSQLValueString($_POST['prefsWinnerMethod'], "int"),
							   GetSQLValueString($_POST['prefsEntryForm'], "text"),
							   GetSQLValueString($_POST['prefsRecordLimit'], "int"),
							   GetSQLValueString($_POST['prefsRecordPaging'], "int"),

							   GetSQLValueString($_POST['prefsTheme'], "text"),
							   GetSQLValueString($_POST['prefsDateFormat'], "text"),
							   GetSQLValueString($_POST['prefsContact'], "text"),
							   GetSQLValueString($_POST['prefsTimeZone'], "text"),
							   GetSQLValueString($_POST['prefsEntryLimit'], "text"),

							   GetSQLValueString($_POST['prefsTimeFormat'], "text"),
							   GetSQLValueString($_POST['prefsUserEntryLimit'], "int"),
							   GetSQLValueString($_POST['prefsUserSubCatLimit'], "int"),
							   GetSQLValueString($prefsUSCLEx, "text"),
							   GetSQLValueString($_POST['prefsUSCLExLimit'], "int"),

							   GetSQLValueString($_POST['prefsPayToPrint'], "text"),
							   GetSQLValueString($_POST['prefsHideRecipe'], "text"),
							   GetSQLValueString($_POST['prefsUseMods'], "text"),
							   GetSQLValueString($_POST['prefsSEF'], "text"),
							   GetSQLValueString($_POST['prefsSpecialCharLimit'], "int"),

							   GetSQLValueString($prefsStyleSet, "text"),
							   GetSQLValueString($_POST['prefsAutoPurge'], "int"),
							   GetSQLValueString($_POST['prefsEntryLimitPaid'], "int"),
							   GetSQLValueString($_POST['prefsEmailRegConfirm'], "int"),
							   GetSQLValueString($_POST['prefsSponsorLogos'], "text"),
							   GetSQLValueString($_POST['prefsSpecific'], "int"),

							   GetSQLValueString($_POST['prefsDropOff'], "int"),
							   GetSQLValueString($_POST['prefsShipping'], "int"),
							   GetSQLValueString($_POST['prefsPaypalIPN'], "int"),
							   GetSQLValueString($_POST['prefsProEdition'], "int"),
							   GetSQLValueString($id, "int"));

			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			//echo $updateSQL; exit;

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

			$pattern = array('\'', '"');
			$updateGoTo = str_replace($pattern, "", $updateGoTo);
			header(sprintf("Location: %s", stripslashes($updateGoTo)));
	}

} else {
	header(sprintf("Location: %s", $base_url."index.php?msg=98"));
	exit;
}
?>