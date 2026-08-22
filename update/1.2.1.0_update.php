<?php

if (!function_exists('check_update')) {
	$redirect = "../../403.php";
	$redirect_go_to = sprintf("Location: %s", $redirect);
	header($redirect_go_to);
	exit();
}
 
// Need to escape!

$output .= "<h4>Version 1.2.1.0-1.2.1.3</h4>";
$output .= "<ul>";
// -----------------------------------------------------------
// Alter Table: contest_info
//   Add/change table rows for expanded date functions.
// -----------------------------------------------------------

$updateSQL = "
ALTER TABLE  `".$prefix."contest_info`
CHANGE  `contestRegistrationOpen`  `contestRegistrationOpen` VARCHAR(255) NULL DEFAULT NULL ,
CHANGE  `contestRegistrationDeadline`  `contestRegistrationDeadline` VARCHAR(255) NULL DEFAULT NULL ,
CHANGE  `contestEntryOpen`  `contestEntryOpen` VARCHAR(255) NULL DEFAULT NULL ,
CHANGE  `contestEntryDeadline`  `contestEntryDeadline` VARCHAR(255) NULL DEFAULT NULL ,
CHANGE  `contestAwardsLocDate`  `contestAwardsLocDate` VARCHAR(255) NULL DEFAULT NULL ,
ADD  `contestJudgeOpen` VARCHAR(255) NULL AFTER  `contestEntryDeadline` ,
ADD  `contestJudgeDeadline` VARCHAR(255) NULL AFTER  `contestJudgeOpen`,
ADD  `contestVolunteers` TEXT NULL ;
";
$result = $db_conn->rawQuery($updateSQL);
//$output .= $updateSQL."<br>";
if ($db_conn->getLastErrno() === 0) $output .= "<li>Competition info table updated.</li>";
else $output .= "<li class=\"text-danger\">Error: Competition info table NOT updated. ".$db_conn->getLastError()."</li>";
 
// -----------------------------------------------------------
// Alter Table: brewing
//   Add table rows to house creation and last access data.
// -----------------------------------------------------------

$block_ok = TRUE;

$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD  `brewUpdated` TIMESTAMP NULL DEFAULT NULL COMMENT  'Timestamp of when the entry was last updated';";
$result = $db_conn->rawQuery($updateSQL);
if ($db_conn->getLastErrno() !== 0) { $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>"; $block_ok = FALSE; }

$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD  `brewConfirmed` TINYINT( 1 ) NULL DEFAULT NULL COMMENT '1=true - 2=false';";
$result = $db_conn->rawQuery($updateSQL);
if ($db_conn->getLastErrno() !== 0) { $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>"; $block_ok = FALSE; }

if ($block_ok) $output .= "<li>Brewing table updated.</li>";
// -----------------------------------------------------------
// Alter Table: users
//   Add table rows to house creation and last access data.
// -----------------------------------------------------------

$updateSQL = "ALTER TABLE  `".$prefix."users` ADD  `userCreated` TIMESTAMP NULL DEFAULT NULL COMMENT  'Timestamp of when the user was created.';";
$result = $db_conn->rawQuery($updateSQL);
//$output .= $updateSQL."<br>";
if ($db_conn->getLastErrno() === 0) $output .= "<li>Date created and last access timestamp rows added to users table.</li>";
else $output .= "<li class=\"text-danger\">Error: Users table NOT updated. ".$db_conn->getLastError()."</li>";

// -----------------------------------------------------------
// Alter Table: preferences
//   Add table rows for localization, Google Wallet, and
//   winner display (delay, method).
// -----------------------------------------------------------

$updateSQL = "
ALTER TABLE  `".$prefix."preferences` 
ADD  `prefsTimeZone` DECIMAL(10,3)  NULL DEFAULT NULL , 
ADD  `prefsEntryLimit` INT(11) NULL DEFAULT NULL , 
ADD  `prefsTimeFormat` TINYINT(1) NULL DEFAULT NULL ,
ADD  `prefsGoogle` CHAR(1) NULL DEFAULT NULL AFTER  `prefsTransFee` ,
ADD  `prefsGoogleAccount` VARCHAR (255) NULL DEFAULT NULL COMMENT  'Google Merchant ID' AFTER  `prefsGoogle`,
ADD  `prefsWinnerDelay` INT(11) NULL DEFAULT NULL COMMENT  'Hours after last judging date beginning time to delay displaying winners' AFTER `prefsDisplayWinners`,
ADD  `prefsWinnerMethod` INT NULL DEFAULT NULL COMMENT 'Method comp uses to choose winners: 0=by table; 1=by category; 2=by sub-category' AFTER `prefsWinnerDelay` ;
";
$block_ok = TRUE;

$result = $db_conn->rawQuery($updateSQL);
if ($db_conn->getLastErrno() !== 0) { $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>"; $block_ok = FALSE; }
//$output .= $updateSQL."<br>";

$updateSQL = "UPDATE  `".$prefix."preferences` SET `prefsRecordLimit` =  '9999',  `prefsTimeZone` =  '-5.000', `prefsEntryLimit` =  NULL, `prefsDateFormat` =  '1',  `prefsTimeFormat` =  '0', `prefsGoogle` = 'N', `prefsWinnerDelay` = '24', `prefsWinnerMethod` = '0' WHERE `id` = '1';";
$result = $db_conn->rawQuery($updateSQL);
if ($db_conn->getLastErrno() !== 0) { $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>"; $block_ok = FALSE; }
//$output .= $updateSQL."<br>";

if ($block_ok) $output .= "<li>Preferences table updated.</li>";

// -----------------------------------------------------------
// Alter Table: judging locations
//   Change/add rows to accomodate new time schema.
// -----------------------------------------------------------

$block_ok = TRUE;

$updateSQL = "ALTER TABLE  `".$prefix."judging_locations` CHANGE  `judgingDate` `judgingDate` VARCHAR( 255 ) NULL DEFAULT NULL;";
$result = $db_conn->rawQuery($updateSQL);
if ($db_conn->getLastErrno() !== 0) { $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>"; $block_ok = FALSE; }
//$output .= $updateSQL."<br>";

$updateSQL = "ALTER TABLE `".$prefix."judging_locations` CHANGE  `judgingTime` `judgingTime` VARCHAR( 255 ) NULL DEFAULT NULL;";
$result = $db_conn->rawQuery($updateSQL);
if ($db_conn->getLastErrno() !== 0) { $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>"; $block_ok = FALSE; }
//$output .= $updateSQL."<br>";

if ($block_ok) $output .= "<li>Judging Locations table updated.</li>";

// -----------------------------------------------------------
// Alter Table: brewer
//   Change/add rows to accomodate new staffing schema.
// -----------------------------------------------------------
/* NOT in 1.2.1.0 update

$updateSQL = "
ALTER TABLE  `".$prefix."brewer` 
CHANGE  `brewerAssignment`  `brewerAssignmentJudge` char(1) NULL DEFAULT NULL COMMENT  '1 for true; 0 for false', 
CHANGE  `brewerAssignmentStaff`  `brewerAssignmentStaff` char(1) NULL DEFAULT NULL COMMENT  '1 for true; 0 for false', 
ADD  `brewerAssignmentSteward` char(1) NULL DEFAULT NULL COMMENT  '1 for true; 0 for false' AFTER  `brewerAssignmentJudge`, 
ADD  `brewerAssignmentOrganizer` char(1) NULL DEFAULT NULL COMMENT  '1 for true; 0 for false' AFTER  `brewerAssignmentStaff`, CHANGE  `brewerJudgeBOS`  `brewerJudgeBOS` char(1) NULL DEFAULT NULL COMMENT  '1 for true; 0 for false';
";
$result = $db_conn->rawQuery($updateSQL);
//$output .= $updateSQL."<br>";
$output .= "<li>Row names changed successfully in brewer table.</li>";
*/
// -----------------------------------------------------------
// Alter Table: archive user and brewer tables
//  Need to make the same changes to all archive tables as well.
// -----------------------------------------------------------
/* NOT in 1.2.1.0 update

$updateSQL = "ALTER TABLE  `".$prefix."archive` CHANGE  `archiveUserTableName`  `archiveStyleSet` VARCHAR(255) NULL DEFAULT NULL";
$result = $db_conn->rawQuery($updateSQL);
//$output .= $updateSQL."<br>";
$output .= "<li>Row names changed successfully in archive table.</li>";
*/
$query_archive = "SELECT archiveSuffix FROM $archive_db_table";
$rows_archive = $db_conn->rawQuery($query_archive);
$totalRows_archive = count($rows_archive);
$block_ok = TRUE;
if ($totalRows_archive > 0) {

	foreach ($rows_archive as $row_archive) {

		// Sanitize before splicing into table identifiers below - archiveSuffix is admin-entered free text.
		$row_archive['archiveSuffix'] = preg_replace("/[^a-zA-Z0-9]+/", "", $row_archive['archiveSuffix']);

		$updateSQL = "ALTER TABLE `".$prefix."users_".$row_archive['archiveSuffix']."` ADD  `userCreated` TIMESTAMP NULL DEFAULT NULL COMMENT 'Timestamp of when the user was created.';";
		$result = $db_conn->rawQuery($updateSQL);
		if ($db_conn->getLastErrno() !== 0) { $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>"; $block_ok = FALSE; }
		//$output .= $updateSQL."<br>";


		$updateSQL = "ALTER TABLE `".$prefix."brewing_".$row_archive['archiveSuffix']."` ADD  `brewUpdated` TIMESTAMP NULL DEFAULT NULL COMMENT 'Timestamp of when the entry was updated.';";
		$result = $db_conn->rawQuery($updateSQL);
		if ($db_conn->getLastErrno() !== 0) { $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>"; $block_ok = FALSE; }
		//$output .= $updateSQL."<br>";


		$updateSQL = "ALTER TABLE `".$prefix."brewing_".$row_archive['archiveSuffix']."` ADD  `brewConfirmed` TINYINT(1) NULL DEFAULT NULL COMMENT '0 = false; 1 = true';";
		$result = $db_conn->rawQuery($updateSQL);
		if ($db_conn->getLastErrno() !== 0) { $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>"; $block_ok = FALSE; }
		//$output .= $updateSQL."<br>";


	}

}

if ($block_ok) $output .= "<li>All archive table schemas updated successfully.</li>";
#========================================================================================================================================================
// -----------------------------------------------------------
// Create Table: system
//   Table to house system data.
// -----------------------------------------------------------

$updateSQL = "CREATE TABLE IF NOT EXISTS `".$prefix."bcoem_sys` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`version` varchar(12) DEFAULT NULL,
	`version_date` date DEFAULT NULL,
	`data_check` varchar(255) DEFAULT NULL COMMENT 'Date/time of the last data integrity check.',
	`setup` tinyint(1) DEFAULT NULL COMMENT 'Has setup run? 1=true, 0=false.',
	PRIMARY KEY (`id`)
) ENGINE=MyISAM";
$block_ok = TRUE;

$result = $db_conn->rawQuery($updateSQL);
if ($db_conn->getLastErrno() !== 0) { $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>"; $block_ok = FALSE; }
//$output .= $updateSQL."<br>";

$updateSQL = "INSERT INTO `".$prefix."bcoem_sys` (`id`, `version`, `version_date`, `data_check`,`setup`) VALUES (1, '1.2.1.1', '2012-09-01', NOW( ),'1');";
$result = $db_conn->rawQuery($updateSQL);
if ($db_conn->getLastErrno() !== 0) { $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>"; $block_ok = FALSE; }
//$output .= $updateSQL."<br>";

if ($block_ok) $output .= "<li>System table created.</li>";
// -----------------------------------------------------------
// Create Tables: special_best_info, special_best_data
//  Tables to house custom "best of" categories and data.
// -----------------------------------------------------------

$updateSQL = "CREATE TABLE IF NOT EXISTS `".$prefix."special_best_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sbi_name` varchar(255) DEFAULT NULL,
  `sbi_description` text,
  `sbi_places` int(11) DEFAULT NULL,
  `sbi_rank` int(11) DEFAULT NULL,
  `sbi_display_places` tinyint(1) DEFAULT NULL COMMENT '1=true; 0=false',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM ;
"; 
$block_ok = TRUE;

$result = $db_conn->rawQuery($updateSQL);
if ($db_conn->getLastErrno() !== 0) { $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>"; $block_ok = FALSE; }
//$output .= $updateSQL."<br>";

$updateSQL = "CREATE TABLE IF NOT EXISTS `".$prefix."special_best_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) DEFAULT NULL COMMENT 'relational to special_best_info table',
  `bid` int(11) DEFAULT NULL COMMENT 'relational to brewer table - bid row',
  `eid` int(11) DEFAULT NULL COMMENT 'relational to brewing table - id (entry number)',
  `sbd_place` int(11) DEFAULT NULL,
  `sbd_comments` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM ;
";
$result = $db_conn->rawQuery($updateSQL);
if ($db_conn->getLastErrno() !== 0) { $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>"; $block_ok = FALSE; }
//$output .= $updateSQL."<br>";
if ($block_ok) $output .= "<li>Custom &ldquo;best of&rdquo; tables created.</li>";

#========================================================================================================================================================
// -----------------------------------------------------------
// Data Updates: Brewing Table
//   Convert brewPaid, brewWinner, and brewReceived to
//   boolean values.
// -----------------------------------------------------------
if ($totalRows_log > 0) {
	foreach ($log as $row_log) {
		if ($row_log['brewPaid'] == "Y") $brewPaid = "1"; else $brewPaid = "0";
		if ($row_log['brewWinner'] == "Y") $brewWinner = "1"; else $brewWinner = "0";
		if ($row_log['brewReceived'] == "Y") $brewReceived = "1"; else $brewReceived = "0";

			$data = [
				'brewPaid' => $brewPaid,
				'brewWinner' => $brewWinner,
				'brewReceived' => $brewReceived,
				'brewConfirmed' => "1",
				'brewUpdated' => $db_conn->now()
			];
			$db_conn->where('id', $row_log['id']);
			$result = $db_conn->update($prefix."brewing", $data);
			//$output .= $updateSQL."<br>";

	}
	$output .= "<li>All entry data updated.</li>";
}

$block_ok = TRUE;

$updateSQL = "ALTER TABLE  `".$prefix."brewing`
CHANGE  `brewPaid`  `brewPaid` TINYINT( 1 ) NULL DEFAULT NULL COMMENT '1=true; 0=false';";
$result = $db_conn->rawQuery($updateSQL);
if ($db_conn->getLastErrno() !== 0) { $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>"; $block_ok = FALSE; }
//$output .= $updateSQL."<br>";

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewReceived`  `brewReceived` TINYINT( 1 ) NULL DEFAULT NULL COMMENT '1=true; 0=false';";
$result = $db_conn->rawQuery($updateSQL);
if ($db_conn->getLastErrno() !== 0) { $output .= "<li class=\"text-danger\">Error: ".$db_conn->getLastError()."</li>"; $block_ok = FALSE; }
//$output .= $updateSQL."<br>";

if ($block_ok) $output .= "<li>Conversion of paid and received rows to new schema in brewing table completed.</li>";
#========================================================================================================================================================
// -----------------------------------------------------------
// Data Updates: Other tables
// -----------------------------------------------------------
// Update Judging Locations to use new date/time schema
$query_judging_locations = "SELECT * FROM $judging_locations_db_table";
$rows_judging_locations = $db_conn->rawQuery($query_judging_locations);
foreach ($rows_judging_locations as $row_judging_locations) {
	// Convert current time/date to UNIX
	$string = strtotime($row_judging_locations['judgingDate'].$row_judging_locations['judgingTime']);

	$db_conn->where('id', $row_judging_locations['id']);
	$result = $db_conn->update($judging_locations_db_table, ['judgingDate' => $string]);
	//$output .= $updateSQL."<br>";
}
$output .= "<li>Updates to judging locations table completed.</li>";
// Update Preferences to use new date/time schema
	// Convert current time/date to UNIX
	
	if ($row_contest_info['contestRegistrationOpen'] != "") $string1 = strtotime($row_contest_info['contestRegistrationOpen']." 12:00 AM");
	else $string1 = strtotime(date("Y-m-d")." 12:00 AM");
	
	if ($row_contest_info['contestRegistrationDeadline'] != "") $string2 = strtotime($row_contest_info['contestRegistrationDeadline']." 12:00 AM");
	else $string2 = strtotime(date("Y-m-d")." 12:00 AM");
	
	if ($row_contest_info['contestEntryOpen'] != "") $string3 = strtotime($row_contest_info['contestEntryOpen']." 12:00 AM");
	else $string3 = strtotime(date("Y-m-d")." 12:00 AM");
	
	if ($row_contest_info['contestEntryDeadline'] != "") $string4 = strtotime($row_contest_info['contestEntryDeadline']." 12:00 AM");
	else $string4 = strtotime(date("Y-m-d")." 12:00 AM");
	
	if ($row_contest_info['contestAwardsLocDate'] != "") $string5 = strtotime($row_contest_info['contestAwardsLocDate'].$row_contest_info['contestAwardsLocTime']);
	else $string5 = strtotime(date("Y-m-d")." 12:00 AM");
	
	$data = [
		'contestRegistrationOpen' => $string1,
		'contestRegistrationDeadline' => $string2,
		'contestEntryOpen' => $string3,
		'contestEntryDeadline' => $string4,
		'contestJudgeOpen' => $string1,
		'contestJudgeDeadline' => $string2,
		'contestAwardsLocTime' => $string5
	];
	$db_conn->where('id', 1);
	$result = $db_conn->update($contest_info_db_table, $data);
	//$output .= $updateSQL."<br>";
	$output .= "<li>Updates to prefereces table completed.</li>";

// Add the date of the update to all current users
// *************************** 1.2.1.0 ONLY ******************************
$query_user = sprintf("SELECT id,userCreated FROM %s", $users_db_table);
$rows_user = $db_conn->rawQuery($query_user);
$totalRows_user = count($rows_user);
foreach ($rows_user as $row_user) {

	$db_conn->where('id', $row_user['id']);
	$result = $db_conn->update($users_db_table, ['userCreated' => $db_conn->now()]);

}
$output .= "<li>Users table updated.</li>";
#========================================================================================================================================================
// -----------------------------------------------------------
// Data Updates: Archive Tables
//   Convert the data in archived brewer tables to be compatible
//   with the new boolean schema for paid, received, and winner 
// -----------------------------------------------------------
// -----------------------------------------------------------
// FUTURE Data Updates: Archive Tables
//   Convert the data in the brewStyle row table to key off
//   of the id row of either the 'styles_XXX' table or 'styles_custom'
//   table INSTEAD of the style name.
//   Designate whether the style of the entry is from the main
//   set designated by an admin (M) or whether it is a custom style
//   (C).
// -----------------------------------------------------------
$query_archive = "SELECT archiveSuffix FROM $archive_db_table";
$rows_archive = $db_conn->rawQuery($query_archive);
$totalRows_archive = count($rows_archive);

$a = [];

if ($totalRows_archive > 0) {

	foreach ($rows_archive as $row_archive) { $a[] = $row_archive['archiveSuffix']; }

	foreach ($a as $suffix) {

		// Sanitize before splicing into table identifiers below - archiveSuffix is admin-entered free text.
		$suffix = preg_replace("/[^a-zA-Z0-9]+/", "", $suffix);

		$query_log = sprintf("SELECT brewPaid,brewWinner,brewReceived,brewUpdated,brewConfirmed,id FROM %s",$prefix."brewing_".$suffix);
		$rows_log = $db_conn->rawQuery($query_log);
		$totalRows_log = count($rows_log);

		//$output .= $query_log."<br>";

		foreach ($rows_log as $row_log) {
		if ($row_log['brewPaid'] == "Y") $brewPaid = "1"; else $brewPaid = "0";
		if ($row_log['brewWinner'] == "Y") $brewWinner = "1"; else $brewWinner = "0";
		if ($row_log['brewReceived'] == "Y") $brewReceived = "1"; else $brewReceived = "0";

		$data = [
			'brewPaid' => $brewPaid,
			'brewWinner' => $brewWinner,
			'brewReceived' => $brewReceived,
			'brewConfirmed' => "1",
			'brewUpdated' => $db_conn->now()
		];
			$db_conn->where('id', $row_log['id']);
			$result = $db_conn->update($prefix."brewing_".$suffix, $data);

		}


		$query_user = sprintf("SELECT * FROM %s", $prefix."users_".$suffix);
		$rows_user = $db_conn->rawQuery($query_user);
		$totalRows_user = count($rows_user);

		foreach ($rows_user as $row_user) {

			$db_conn->where('id', $row_user['id']);
			$result = $db_conn->update($users_db_table, ['userCreated' => $db_conn->now()]);

			if ($row_user['userQuestion'] == "") {

			$data = [
				'userQuestion' => "What is your favorite all-time beer to drink?",
				'userQuestionAnswer' => "Pabst"
			];
			$db_conn->where('id', $row_user['id']);
			$result = $db_conn->update($prefix."users_".$suffix, $data);

			}

		}

		$output .= "<li>All archive entry data updated.</li>";

	}

}

$output .= "<li>All archived tables updated successfully.</li>";
$output .= "</ul>";
?>