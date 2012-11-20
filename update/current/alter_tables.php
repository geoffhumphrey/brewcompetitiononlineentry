<?php

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
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
//echo $updateSQL."<br>";
echo "<ul><li>Competition info table updated.</li></ul>";
 
// -----------------------------------------------------------
// Alter Table: brewing
//   Add table rows to house creation and last access data.
// -----------------------------------------------------------

$updateSQL = "
ALTER TABLE  `".$prefix."brewing` ADD  `brewUpdated` TIMESTAMP NULL DEFAULT NULL COMMENT  'Timestamp of when the entry was last updated';";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD  `brewConfirmed` TINYINT( 1 ) NULL DEFAULT NULL COMMENT '1=true - 2=false';";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

echo "<ul><li>Brewing table updated.</li></ul>";

// -----------------------------------------------------------
// Alter Table: users
//   Add table rows to house creation and last access data.
// -----------------------------------------------------------

$updateSQL = "ALTER TABLE  `".$prefix."users` ADD  `userCreated` TIMESTAMP NULL DEFAULT NULL COMMENT  'Timestamp of when the user was created.';";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
//echo $updateSQL."<br>";

echo "<ul><li>Date created and last access timestamp rows added to users table.</li></ul>";

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
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
//echo $updateSQL."<br>";

$updateSQL = "UPDATE  `".$prefix."preferences` SET  `prefsTimeZone` =  '-5.000', `prefsEntryLimit` =  NULL, `prefsTimeFormat` =  '0', `prefsGoogle` = 'N', `prefsWinnerDelay` = '24', `prefsWinnerMethod` = '0' WHERE `id` = '1';"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
//echo $updateSQL."<br>";

echo "<ul><li>Preferences table updated.</li></ul>";


// -----------------------------------------------------------
// Alter Table: judging locations
//   Change/add rows to accomodate new time schema.
// -----------------------------------------------------------

$updateSQL = "ALTER TABLE  `".$prefix."judging_locations` CHANGE  `judgingDate` `judgingDate` VARCHAR( 255 ) NULL DEFAULT NULL;"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
//echo $updateSQL."<br>";

$updateSQL = "ALTER TABLE `".$prefix."judging_locations` CHANGE  `judgingTime` `judgingTime` VARCHAR( 255 ) NULL DEFAULT NULL;"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
//echo $updateSQL."<br>";

echo "<ul><li>Judging Locations table updated.</li></ul>";


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
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
//echo $updateSQL."<br>";

echo "<ul><li>Row names changed successfully in brewer table.</li></ul>";
*/

// -----------------------------------------------------------
// Alter Table: archive user and brewer tables
//  Need to make the same changes to all archive tables as well.
// -----------------------------------------------------------
/* NOT in 1.2.1.0 update
$updateSQL = "ALTER TABLE  `".$prefix."archive` CHANGE  `archiveUserTableName`  `archiveStyleSet` VARCHAR(255) NULL DEFAULT NULL";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
//echo $updateSQL."<br>";

echo "<ul><li>Row names changed successfully in archive table.</li></ul>";
*/

$query_archive = "SELECT archiveSuffix FROM $archive_db_table";
$archive = mysql_query($query_archive, $brewing) or die(mysql_error());
$row_archive = mysql_fetch_assoc($archive);
$totalRows_archive = mysql_num_rows($archive);

if ($totalRows_archive > 0) {
	do { 
		$updateSQL = "ALTER TABLE `".$prefix."users_".$row_archive['archiveSuffix']."` ADD  `userCreated` TIMESTAMP NULL DEFAULT NULL COMMENT 'Timestamp of when the user was created.';";
		mysql_select_db($database, $brewing);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
		//echo $updateSQL."<br>"; 
		
		$updateSQL = "ALTER TABLE `".$prefix."brewing_".$row_archive['archiveSuffix']."` ADD  `brewUpdated` TIMESTAMP NULL DEFAULT NULL COMMENT 'Timestamp of when the entry was updated.';";
		mysql_select_db($database, $brewing);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
		//echo $updateSQL."<br>";
		
		$updateSQL = "ALTER TABLE `".$prefix."brewing_".$row_archive['archiveSuffix']."` ADD  `brewConfirmed` TINYINT(1) NULL DEFAULT NULL COMMENT '0 = false; 1 = true';";
		mysql_select_db($database, $brewing);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
		//echo $updateSQL."<br>";  
		
		/*
		$updateSQL = "ALTER TABLE  `".$prefix."brewer_".$row_archive['archiveSuffix']."` CHANGE  `brewerAssignment`  `brewerAssignmentJudge` char(1) NULL DEFAULT NULL COMMENT  '1 for true; 0 for false', ADD  `brewerAssignmentStaff` tinyint(1) NULL DEFAULT NULL COMMENT  '1 for true; 0 for false', ADD  `brewerAssignmentSteward` tinyint(1) NULL DEFAULT NULL COMMENT  '1 for true; 0 for false' AFTER  `brewerAssignmentJudge`, ADD  `brewerAssignmentOrganizer` tinyint(1) NULL DEFAULT NULL COMMENT  '1 for true; 0 for false' AFTER  `brewerAssignmentStaff`, CHANGE  `brewerJudgeBOS`  `brewerJudgeBOS` char(1) NULL DEFAULT NULL COMMENT  '1 for true; 0 for false';";
		mysql_select_db($database, $brewing);
		//$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
		echo $updateSQL."<br>";
		
		/*
		// Loop through table and change the data to be usable
		$query_archive = "SELECT id,brewerAssignmentJudge,brewerJudgeBOS FROM brewer_".$row_archive['archiveSuffix'];
		$archive = mysql_query($query_archive, $brewing) or die(mysql_error());
		$row_archive = mysql_fetch_assoc($archive);
		
		do {
			if ($row_archive['brewerAssignmentJudge'] == "O") $updateSQL = sprintf("UPDATE brewer_".$row_archive['archiveSuffix']." SET brewerAssgigmentJudge='0', brewerAssignmentStaff='0', brewerAssignmentSteward='0', brewerAssignmentOrganizer='1' WHERE id='%s';", $row_archive['id']);
			if ($row_archive['brewerAssignmentJudge'] == "J") $updateSQL = sprintf("UPDATE brewer_".$row_archive['archiveSuffix']." SET brewerAssgigmentJudge='1', brewerAssignmentStaff='0', brewerAssignmentSteward='0', brewerAssignmentOrganizer='0' WHERE id='%s';", $row_archive['id']);
			if ($row_archive['brewerAssignmentJudge'] == "S") $updateSQL = sprintf("UPDATE brewer_".$row_archive['archiveSuffix']." SET brewerAssgigmentJudge='0', brewerAssignmentStaff='0', brewerAssignmentSteward='1', brewerAssignmentOrganizer='0' WHERE id='%s';", $row_archive['id']);
			if ($row_archive['brewerAssignmentJudge'] == "X") $updateSQL = sprintf("UPDATE brewer_".$row_archive['archiveSuffix']." SET brewerAssgigmentJudge='0', brewerAssignmentStaff='1', brewerAssignmentSteward='0', brewerAssignmentOrganizer='0' WHERE id='%s';", $row_archive['id']);
			$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			
			if ($row_archive['brewerJudgeBOS'] == "Y") $updateSQL = sprintf("UPDATE brewer_".$row_archive['archiveSuffix']." SET brewerJudgeBOS='1' WHERE id='%s';", $row_archive['id']);
			else $updateSQL = sprintf("UPDATE brewer_".$row_archive['archiveSuffix']." SET brewerJudgeBOS='0' WHERE id='%s';", $row_archive['id']);
			$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			
		} while ($row_archive = mysql_fetch_assoc($archive));
		
		//echo $updateSQL."<br>";
		/*
		$updateSQL = sprintf("UPDATE $archive_db_table SET archiveStyleSet='styles_bjcp_2008' WHERE id ='%s'",$row_archive['id']);
		mysql_select_db($database, $brewing);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
		*/
		
	} while ($row_archive = mysql_fetch_assoc($archive));
	
echo "<ul><li>All archive table schemas updated successfully.</li></ul>";
}
?>