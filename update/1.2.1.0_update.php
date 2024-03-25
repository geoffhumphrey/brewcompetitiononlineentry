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
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
//$output .= $updateSQL."<br>";
$output .= "<li>Competition info table updated.</li>";
 
// -----------------------------------------------------------
// Alter Table: brewing
//   Add table rows to house creation and last access data.
// -----------------------------------------------------------

$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD  `brewUpdated` TIMESTAMP NULL DEFAULT NULL COMMENT  'Timestamp of when the entry was last updated';";
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD  `brewConfirmed` TINYINT( 1 ) NULL DEFAULT NULL COMMENT '1=true - 2=false';";
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
$output .= "<li>Brewing table updated.</li>";
// -----------------------------------------------------------
// Alter Table: users
//   Add table rows to house creation and last access data.
// -----------------------------------------------------------

$updateSQL = "ALTER TABLE  `".$prefix."users` ADD  `userCreated` TIMESTAMP NULL DEFAULT NULL COMMENT  'Timestamp of when the user was created.';";
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
//$output .= $updateSQL."<br>";
$output .= "<li>Date created and last access timestamp rows added to users table.</li>";

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
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
//$output .= $updateSQL."<br>";

$updateSQL = "UPDATE  `".$prefix."preferences` SET `prefsRecordLimit` =  '9999',  `prefsTimeZone` =  '-5.000', `prefsEntryLimit` =  NULL, `prefsDateFormat` =  '1',  `prefsTimeFormat` =  '0', `prefsGoogle` = 'N', `prefsWinnerDelay` = '24', `prefsWinnerMethod` = '0' WHERE `id` = '1';"; 
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
//$output .= $updateSQL."<br>";
$output .= "<li>Preferences table updated.</li>";

// -----------------------------------------------------------
// Alter Table: judging locations
//   Change/add rows to accomodate new time schema.
// -----------------------------------------------------------

$updateSQL = "ALTER TABLE  `".$prefix."judging_locations` CHANGE  `judgingDate` `judgingDate` VARCHAR( 255 ) NULL DEFAULT NULL;"; 
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
//$output .= $updateSQL."<br>";

$updateSQL = "ALTER TABLE `".$prefix."judging_locations` CHANGE  `judgingTime` `judgingTime` VARCHAR( 255 ) NULL DEFAULT NULL;"; 
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
//$output .= $updateSQL."<br>";
$output .= "<li>Judging Locations table updated.</li>";

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
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
//$output .= $updateSQL."<br>";
$output .= "<li>Row names changed successfully in brewer table.</li>";
*/
// -----------------------------------------------------------
// Alter Table: archive user and brewer tables
//  Need to make the same changes to all archive tables as well.
// -----------------------------------------------------------
/* NOT in 1.2.1.0 update

$updateSQL = "ALTER TABLE  `".$prefix."archive` CHANGE  `archiveUserTableName`  `archiveStyleSet` VARCHAR(255) NULL DEFAULT NULL";
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
//$output .= $updateSQL."<br>";
$output .= "<li>Row names changed successfully in archive table.</li>";
*/
$query_archive = "SELECT archiveSuffix FROM $archive_db_table";
$archive = mysqli_query($connection,$query_archive) or die (mysqli_error($connection));
$row_archive = mysqli_fetch_assoc($archive);
$totalRows_archive = mysqli_num_rows($archive);
if ($totalRows_archive > 0) {
	
	do { 
		
		$updateSQL = "ALTER TABLE `".$prefix."users_".$row_archive['archiveSuffix']."` ADD  `userCreated` TIMESTAMP NULL DEFAULT NULL COMMENT 'Timestamp of when the user was created.';";
		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		//$output .= $updateSQL."<br>"; 
		
		
		$updateSQL = "ALTER TABLE `".$prefix."brewing_".$row_archive['archiveSuffix']."` ADD  `brewUpdated` TIMESTAMP NULL DEFAULT NULL COMMENT 'Timestamp of when the entry was updated.';";
		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		//$output .= $updateSQL."<br>";
		
		
		$updateSQL = "ALTER TABLE `".$prefix."brewing_".$row_archive['archiveSuffix']."` ADD  `brewConfirmed` TINYINT(1) NULL DEFAULT NULL COMMENT '0 = false; 1 = true';";
		mysqli_select_db($connection,$database);
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
		//$output .= $updateSQL."<br>";  
		
		
	} while ($row_archive = mysqli_fetch_assoc($archive));
	
$output .= "<li>All archive table schemas updated successfully.</li>";
}
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
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
//$output .= $updateSQL."<br>";

$updateSQL = "INSERT INTO `".$prefix."bcoem_sys` (`id`, `version`, `version_date`, `data_check`,`setup`) VALUES (1, '1.2.1.1', '2012-09-01', NOW( ),'1');";
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
//$output .= $updateSQL."<br>";
$output .= "<li>System table created.</li>";
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
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
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
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
//$output .= $updateSQL."<br>";
$output .= "<li>Custom &ldquo;best of&rdquo; tables created.</li>";

#========================================================================================================================================================
// -----------------------------------------------------------
// Data Updates: Brewing Table
//   Convert brewPaid, brewWinner, and brewReceived to
//   boolean values.
// -----------------------------------------------------------
if ($totalRows_log > 0) {
	do {
		if ($row_log['brewPaid'] == "Y") $brewPaid = "1"; else $brewPaid = "0";
		if ($row_log['brewWinner'] == "Y") $brewWinner = "1"; else $brewWinner = "0";
		if ($row_log['brewReceived'] == "Y") $brewReceived = "1"; else $brewReceived = "0";
		
		
		
			$updateSQL = sprintf("UPDATE ".$prefix."brewing SET 
								 brewPaid='%s',
								 brewWinner='%s',
								 brewReceived='%s',
								 brewConfirmed='%s',
								 brewUpdated=%s
								 WHERE id='%s';",
								 $brewPaid,
								 $brewWinner,
								 $brewReceived,
								 "1",
								 "NOW()",
								 $row_log['id']);
			mysqli_select_db($connection,$database);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			//$output .= $updateSQL."<br>";
		
	} while ($row_log = mysqli_fetch_assoc($log));
	$output .= "<li>All entry data updated.</li>";
}

$updateSQL = "ALTER TABLE  `".$prefix."brewing` 
CHANGE  `brewPaid`  `brewPaid` TINYINT( 1 ) NULL DEFAULT NULL COMMENT '1=true; 0=false';";
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
//$output .= $updateSQL."<br>";

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewReceived`  `brewReceived` TINYINT( 1 ) NULL DEFAULT NULL COMMENT '1=true; 0=false';";
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection)); 
//$output .= $updateSQL."<br>";
$output .= "<li>Conversion of paid and received rows to new schema in brewing table completed.</li>";
#========================================================================================================================================================
// -----------------------------------------------------------
// Data Updates: Other tables
// -----------------------------------------------------------
// Update Judging Locations to use new date/time schema
$query_judging_locations = "SELECT * FROM $judging_locations_db_table";
$judging_locations = mysqli_query($connection,$query_judging_locations) or die (mysqli_error($connection));
$row_judging_locations = mysqli_fetch_assoc($judging_locations);
do { 
	// Convert current time/date to UNIX
	$string = strtotime($row_judging_locations['judgingDate'].$row_judging_locations['judgingTime']);
	
	$updateSQL = sprintf("UPDATE $judging_locations_db_table SET 
						 judgingDate='%s'
						 WHERE id='%s'", 
						 $string,
						 $row_judging_locations['id']);
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	//$output .= $updateSQL."<br>";
}  
while ($row_judging_locations = mysqli_fetch_assoc($judging_locations));
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
	
	$updateSQL = sprintf("UPDATE $contest_info_db_table SET 
						 contestRegistrationOpen='%s',
						 contestRegistrationDeadline='%s',
						 contestEntryOpen='%s',
						 contestEntryDeadline='%s',
						 contestJudgeOpen='%s',
						 contestJudgeDeadline='%s',
						 contestAwardsLocTime='%s'						 
						 WHERE id='1'", 
						 $string1,
						 $string2,
						 $string3,
						 $string4,
						 $string1,
						 $string2,
						 $string5);
	
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	//$output .= $updateSQL."<br>";
	$output .= "<li>Updates to prefereces table completed.</li>";

// Add the date of the update to all current users
// *************************** 1.2.1.0 ONLY ******************************
$query_user = sprintf("SELECT id,userCreated FROM %s", $users_db_table);
$user = mysqli_query($connection,$query_user) or die (mysqli_error($connection));
$row_user = mysqli_fetch_assoc($user);
$totalRows_user = mysqli_num_rows($user);
do {
	
	$updateSQL = sprintf("UPDATE %s SET userCreated=NOW( ) WHERE id='%s'",$users_db_table,$row_user['id']); 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

} while ($row_user = mysqli_fetch_assoc($user));
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
$archive = mysqli_query($connection,$query_archive) or die (mysqli_error($connection));
$row_archive = mysqli_fetch_assoc($archive);
$totalRows_archive = mysqli_num_rows($archive);

$a = array();

if ($totalRows_archive > 0) {
	
	do { $a[] = $row_archive['archiveSuffix']; } while ($row_archive = mysqli_fetch_assoc($archive));
	
	foreach ($a as $suffix) {
			
			
		$query_log = sprintf("SELECT brewPaid,brewWinner,brewReceived,brewUpdated,brewConfirmed,id FROM %s",$prefix."brewing_".$suffix);
		$log = mysqli_query($connection,$query_log) or die (mysqli_error($connection));
		$row_log = mysqli_fetch_assoc($log);
		$totalRows_log = mysqli_num_rows($log);
		
		//$output .= $query_log."<br>";
		
		do {
		if ($row_log['brewPaid'] == "Y") $brewPaid = "1"; else $brewPaid = "0";
		if ($row_log['brewWinner'] == "Y") $brewWinner = "1"; else $brewWinner = "0";
		if ($row_log['brewReceived'] == "Y") $brewReceived = "1"; else $brewReceived = "0";
		
		
		
		$updateSQL = sprintf("UPDATE ".$prefix."brewing_".$suffix." SET 
								 brewPaid='%s',
								 brewWinner='%s',
								 brewReceived='%s',
								 brewConfirmed='%s',
								 brewUpdated=%s
								 WHERE id='%s';",
								 $brewPaid,
								 $brewWinner,
								 $brewReceived,
								 "1",
								 "NOW( )",
								 $row_log['id']);
			mysqli_select_db($connection,$database);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			
		} while ($row_log = mysqli_fetch_assoc($log));
		
		
		$query_user = sprintf("SELECT * FROM %s", $prefix."users_".$suffix);
		$user = mysqli_query($connection,$query_user) or die (mysqli_error($connection));
		$row_user = mysqli_fetch_assoc($user);
		$totalRows_user = mysqli_num_rows($user);
		
		do {
			
			$updateSQL = sprintf("UPDATE %s SET userCreated=NOW( ) WHERE id='%s'",$users_db_table,$row_user['id']); 
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			
			if ($row_user['userQuestion'] == "") {
				
				
			$updateSQL = sprintf("UPDATE %s SET userQuestion='%s', userQuestionAnswer='%s' WHERE id='%s'",$prefix."users_".$suffix,"What is your favorite all-time beer to drink?","Pabst",$row_user['id']); 
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));	
			
			}
			
		} while ($row_user = mysqli_fetch_assoc($user));
		
		$output .= "<li>All archive entry data updated.</li>";
		
	}
	
}

$output .= "<li>All archived tables updated successfully.</li>";
$output .= "</ul>";
?>