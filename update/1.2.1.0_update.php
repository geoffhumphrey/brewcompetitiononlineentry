<?php 
$output .= "<h2>Version 1.2.1.0, 1.2.1.1, 1.2.1.2, and 1.2.1.3...</h2>";
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
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
//$output .= $updateSQL."<br>";
$output .= "<li>Competition info table updated.</li>";
 
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

$output .= "<li>Brewing table updated.</li>";

// -----------------------------------------------------------
// Alter Table: users
//   Add table rows to house creation and last access data.
// -----------------------------------------------------------

$updateSQL = "ALTER TABLE  `".$prefix."users` ADD  `userCreated` TIMESTAMP NULL DEFAULT NULL COMMENT  'Timestamp of when the user was created.';";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
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
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
//$output .= $updateSQL."<br>";

$updateSQL = "UPDATE  `".$prefix."preferences` SET `prefsRecordLimit` =  '9999',  `prefsTimeZone` =  '-5.000', `prefsEntryLimit` =  NULL, `prefsDateFormat` =  '1',  `prefsTimeFormat` =  '0', `prefsGoogle` = 'N', `prefsWinnerDelay` = '24', `prefsWinnerMethod` = '0' WHERE `id` = '1';"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
//$output .= $updateSQL."<br>";

$output .= "<li>Preferences table updated.</li>";


// -----------------------------------------------------------
// Alter Table: judging locations
//   Change/add rows to accomodate new time schema.
// -----------------------------------------------------------

$updateSQL = "ALTER TABLE  `".$prefix."judging_locations` CHANGE  `judgingDate` `judgingDate` VARCHAR( 255 ) NULL DEFAULT NULL;"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
//$output .= $updateSQL."<br>";

$updateSQL = "ALTER TABLE `".$prefix."judging_locations` CHANGE  `judgingTime` `judgingTime` VARCHAR( 255 ) NULL DEFAULT NULL;"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
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
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
//$output .= $updateSQL."<br>";

$output .= "<li>Row names changed successfully in brewer table.</li>";
*/

// -----------------------------------------------------------
// Alter Table: archive user and brewer tables
//  Need to make the same changes to all archive tables as well.
// -----------------------------------------------------------
/* NOT in 1.2.1.0 update
$updateSQL = "ALTER TABLE  `".$prefix."archive` CHANGE  `archiveUserTableName`  `archiveStyleSet` VARCHAR(255) NULL DEFAULT NULL";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
//$output .= $updateSQL."<br>";

$output .= "<li>Row names changed successfully in archive table.</li>";
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
		//$output .= $updateSQL."<br>"; 
		
		$updateSQL = "ALTER TABLE `".$prefix."brewing_".$row_archive['archiveSuffix']."` ADD  `brewUpdated` TIMESTAMP NULL DEFAULT NULL COMMENT 'Timestamp of when the entry was updated.';";
		mysql_select_db($database, $brewing);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
		//$output .= $updateSQL."<br>";
		
		$updateSQL = "ALTER TABLE `".$prefix."brewing_".$row_archive['archiveSuffix']."` ADD  `brewConfirmed` TINYINT(1) NULL DEFAULT NULL COMMENT '0 = false; 1 = true';";
		mysql_select_db($database, $brewing);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
		//$output .= $updateSQL."<br>";  
		
		/*
		$updateSQL = "ALTER TABLE  `".$prefix."brewer_".$row_archive['archiveSuffix']."` CHANGE  `brewerAssignment`  `brewerAssignmentJudge` char(1) NULL DEFAULT NULL COMMENT  '1 for true; 0 for false', ADD  `brewerAssignmentStaff` tinyint(1) NULL DEFAULT NULL COMMENT  '1 for true; 0 for false', ADD  `brewerAssignmentSteward` tinyint(1) NULL DEFAULT NULL COMMENT  '1 for true; 0 for false' AFTER  `brewerAssignmentJudge`, ADD  `brewerAssignmentOrganizer` tinyint(1) NULL DEFAULT NULL COMMENT  '1 for true; 0 for false' AFTER  `brewerAssignmentStaff`, CHANGE  `brewerJudgeBOS`  `brewerJudgeBOS` char(1) NULL DEFAULT NULL COMMENT  '1 for true; 0 for false';";
		mysql_select_db($database, $brewing);
		//$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
		$output .= $updateSQL."<br>";
		
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
		
		//$output .= $updateSQL."<br>";
		/*
		$updateSQL = sprintf("UPDATE $archive_db_table SET archiveStyleSet='styles_bjcp_2008' WHERE id ='%s'",$row_archive['id']);
		mysql_select_db($database, $brewing);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
		*/
		
	} while ($row_archive = mysql_fetch_assoc($archive));
	
$output .= "<li>All archive table schemas updated successfully.</li>";
}

#========================================================================================================================================================

// -----------------------------------------------------------
// Create Table: system
//   Table to house system data.
// -----------------------------------------------------------

$updateSQL = "CREATE TABLE IF NOT EXISTS `".$prefix."system` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`version` varchar(12) DEFAULT NULL,
	`version_date` date DEFAULT NULL,
	`data_check` varchar(255) DEFAULT NULL COMMENT 'Date/time of the last data integrity check.',
	`setup` tinyint(1) DEFAULT NULL COMMENT 'Has setup run? 1=true, 0=false.',
	PRIMARY KEY (`id`)
) ENGINE=MyISAM";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
//$output .= $updateSQL."<br>";

$updateSQL = "INSERT INTO `".$prefix."system` (`id`, `version`, `version_date`, `data_check`,`setup`) VALUES (1, '1.2.1.1', '2012-09-01', NOW( ),'1');";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
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
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
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
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
//$output .= $updateSQL."<br>";

$output .= "<li>Custom &ldquo;best of&rdquo; tables created.</li>";


/*

Save for next version.

// -----------------------------------------------------------
// Create Tables: Styles Index, BJCP 2008, BA 2012
//  Add new tables to accomodate more style sets other than
//  BJCP 2008.
// -----------------------------------------------------------

$updateSQL = "CREATE TABLE IF NOT EXISTS `styles_index` (`id` int(11) NOT NULL AUTO_INCREMENT,`style_name` varchar(255) DEFAULT NULL, `style_year` int(4) DEFAULT NULL, `style_info` text,`style_organization` varchar(255) DEFAULT NULL, `style_db_name` varchar(255) DEFAULT NULL, `style_owner` varchar(255) DEFAULT NULL,`style_active` tinyint(1) DEFAULT NULL COMMENT '1 for true; 0 for false',  PRIMARY KEY (`id`)) ENGINE=MyISAM;"; 
mysql_select_db($database, $brewing);
//$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
$output .= $updateSQL."<br>";

$updateSQL = "INSERT INTO `styles_index` (`id`, `style_name`, `style_year`, `style_info`, `style_organization`, `style_db_name`, `style_owner`, `style_active`) VALUES (1, 'BJCP', 2008, 'Beer Judge Certification Program Style Guidelines for Beer, Mead and Cider 2008 Revision of the 2004 Guidelines.', 'Beer Judge Certification Program', 'styles_bjcp_2008', 'bcoe', 1), (2, 'Brewers Association', 2011, 'The Brewers Association beer style guidelines reflect, as much as possible, historical significance, nauthenticity or a high profile in the current commercial beer market.', 'Brewers Association', 'styles_ba_2011', 'bcoe', 0);";
mysql_select_db($database, $brewing);
//$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
$output .= $updateSQL."<br>";

$updateSQL = "RENAME TABLE `styles` TO `styles_bjcp_2008`;";
mysql_select_db($database, $brewing);
//$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
$output .= $updateSQL."<br>";

$updateSQL = "ALTER TABLE `styles_bjcp_2008` CHANGE `brewStyle` `style_name` varchar(255) NULL DEFAULT NULL, CHANGE `brewStyleGroup` `style_cat` varchar(255) NULL DEFAULT NULL, CHANGE `brewStyleNum` `style_subcat` varchar(255) NULL DEFAULT NULL, CHANGE `brewStyleOG` `style_og_min` float DEFAULT NULL, CHANGE `brewStyleOGMax` `style_og_max` float DEFAULT NULL, CHANGE `brewStyleFG` `style_fg_min` float DEFAULT NULL, CHANGE `brewStyleFGMax` `style_fg_max` float DEFAULT NULL, CHANGE `brewStyleABV` `style_abv_min` float DEFAULT NULL, CHANGE `brewStyleABVMax` `style_abv_max` float DEFAULT NULL, CHANGE `brewStyleIBU` `style_ibu_min` float DEFAULT NULL, CHANGE `brewStyleIBUMax` `style_ibu_max` float DEFAULT NULL, CHANGE `brewStyleSRM` `style_srm_min` float DEFAULT NULL, CHANGE `brewStyleSRMMax` `style_srm_max` float DEFAULT NULL, CHANGE `brewStyleType` `style_type` int(11) NULL DEFAULT NULL COMMENT 'relational to the style_types table', CHANGE `brewStyleInfo` `style_info` text, CHANGE `brewStyleActive` `style_active char(1) NULL DEFAULT NULL, CHANGE `brewStyleOwn` `style_owner` varchar(255) NULL DEFAULT NULL, CHANGE `brewStyleLink` `style_link` varchar(255) NULL DEFAULT NULL, ADD `style_spec_ingred` tinyint(1) DEFAULT NULL, CHANGE `brewStyleJudgingLoc` `style_location` int(11) NULL"; 
mysql_select_db($database, $brewing);
//$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
$output .= $updateSQL."<br>";

$updateSQL = "ALTER TABLE  `styles_bjcp_2008` DROP  `style_owner`";
mysql_select_db($database, $brewing);
//$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
$output .= $updateSQL."<br>";

$updateSQL = "CREATE TABLE IF NOT EXISTS `styles_custom` (`id` int(11) NOT NULL AUTO_INCREMENT, `style_name` varchar(255) DEFAULT NULL, `style_cat` varchar(255) DEFAULT NULL, `style_subcat` varchar(255) DEFAULT NULL, `style_og_min` float DEFAULT NULL, `style_og_max` float DEFAULT NULL, `style_fg_min` float DEFAULT NULL, `style_fg_max` float DEFAULT NULL, `style_abv_min` float DEFAULT NULL, `style_abv_max` float DEFAULT NULL, `style_ibu_min` float DEFAULT NULL, `style_ibu_max` float DEFAULT NULL, `style_srm_min` float DEFAULT NULL, `style_srm_max` float DEFAULT NULL, `style_type` int(11) DEFAULT NULL COMMENT 'relational to the style_types table', `style_info` text, `style_link` varchar(255) DEFAULT NULL, `style_active` tinyint(1) DEFAULT '1', `style_location` int(11) NULL, `style_spec_ingred` tinyint(1) DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM;";
mysql_select_db($database, $brewing);
//$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
$output .= $updateSQL."<br>";


$updateSQL = "CREATE TABLE IF NOT EXISTS `ba_2012` (`id` int(11) NOT NULL AUTO_INCREMENT, `style_name` varchar(255) DEFAULT NULL, `style_cat` varchar(255) DEFAULT NULL, `style_subcat` varchar(255) DEFAULT NULL, `style_og_min` float DEFAULT NULL, `style_og_max` float DEFAULT NULL, `style_fg_min` float DEFAULT NULL, `style_fg_max` float DEFAULT NULL, `style_abv_min` float DEFAULT NULL, `style_abv_max` float DEFAULT NULL, `style_ibu_min` float DEFAULT NULL, `style_ibu_max` float DEFAULT NULL, `style_srm_min` float DEFAULT NULL, `style_srm_max` float DEFAULT NULL, `style_type` int(11) DEFAULT NULL COMMENT 'relational to the style_types table', `style_info` text, `style_link` varchar(255) DEFAULT NULL, `style_active` tinyint(1) DEFAULT '1', `style_location` int(11) NULL, `style_spec_ingred` tinyint(1) DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM;";
mysql_select_db($database, $brewing);
//$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
$output .= $updateSQL."<br>";
*/

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
			mysql_select_db($database, $brewing);
			$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
			//$output .= $updateSQL."<br>";
		
		/*
		
		// ---------------------
		// Not in 1.2.1.0 Release
		// ---------------------
		
		if ($row_log['brewCategorySort'] > 28) {
			$query_style = sprintf("SELECT id FROM styles_custom WHERE style_cat='%s' and style_subcat='%s'", $row_log['brewCategorySort'],$row_log['brewSubCategory']);
			$style = mysql_query($query_style, $brewing) or die(mysql_error());
			$row_style = mysql_fetch_assoc($style);
			
			$updateSQL = sprintf("UPDATE $brewing_db_table SET 
								 brewStyle='%s',
								 brewCategory='%s',
								 brewPaid='%s',
								 brewWinner='%s',
								 brewReceived='%s'
								 WHERE id='%s'",
								 $row_style['id'],
								 "C",
								 $brewPaid,
								 $brewWinner,
								 $brewReceived,
								 $row_log['id']);
			mysql_select_db($database, $brewing);
			//$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
	$output .= $updateSQL."<br>";
			//$output .= $updateSQL."<br>";
		}
		
		else {
			$query_style = sprintf("SELECT id FROM $styles_db_table WHERE style_cat='%s' AND style_subcat='%s'", $row_log['brewCategorySort'],$row_log['brewSubCategory']);
			$style = mysql_query($query_style, $brewing) or die(mysql_error());
			$row_style = mysql_fetch_assoc($style);
			
			$updateSQL = sprintf("UPDATE $brewing_db_table SET 
								 brewStyle='%s',
								 brewCategory='%s',
								 brewPaid='%s',
								 brewWinner='%s',
								 brewReceived='%s'
								 WHERE id='%s'",
								 $row_style['id'],
								 "M",
								 $brewPaid,
								 $brewWinner,
								 $brewReceived,
								 $row_log['id']);
			mysql_select_db($database, $brewing);
			//$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
	$output .= $updateSQL."<br>";
			//$output .= $updateSQL."<br>";
		}
	*/	
	} while ($row_log = mysql_fetch_assoc($log));
	$output .= "<li>All entry data updated.</li>";
}

$updateSQL = "ALTER TABLE  `".$prefix."brewing` 
CHANGE  `brewPaid`  `brewPaid` TINYINT( 1 ) NULL DEFAULT NULL COMMENT '1=true; 0=false';";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
//$output .= $updateSQL."<br>";

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewReceived`  `brewReceived` TINYINT( 1 ) NULL DEFAULT NULL COMMENT '1=true; 0=false';";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());  

//$output .= $updateSQL."<br>";

$output .= "<li>Conversion of paid and received rows to new schema in brewing table completed.</li>";

#========================================================================================================================================================

// -----------------------------------------------------------
// Data Updates: Other tables
// -----------------------------------------------------------

// Update Judging Locations to use new date/time schema

$query_judging_locations = "SELECT * FROM $judging_locations_db_table";
$judging_locations = mysql_query($query_judging_locations, $brewing) or die(mysql_error());
$row_judging_locations = mysql_fetch_assoc($judging_locations);

do { 
	// Convert current time/date to UNIX
	$string = strtotime($row_judging_locations['judgingDate'].$row_judging_locations['judgingTime']);
	$updateSQL = sprintf("UPDATE $judging_locations_db_table SET 
						 judgingDate='%s'
						 WHERE id='%s'", 
						 $string,
						 $row_judging_locations['id']);
	mysql_select_db($database, $brewing);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
	//$output .= $updateSQL."<br>";
}  
while ($row_judging_locations = mysql_fetch_assoc($judging_locations));

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
	
	mysql_select_db($database, $brewing);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
	//$output .= $updateSQL."<br>";

$output .= "<li>Updates to prefereces table completed.</li>";


// Add the date of the update to all current users
// *************************** 1.2.1.0 ONLY ******************************

$query_user = sprintf("SELECT id,userCreated FROM %s", $users_db_table);
$user = mysql_query($query_user, $brewing) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);
$totalRows_user = mysql_num_rows($user);

do {
	$updateSQL = sprintf("UPDATE %s SET userCreated=NOW( ) WHERE id='%s'",$users_db_table,$row_user['id']); 
	mysql_select_db($database, $brewing);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
} while ($row_user = mysql_fetch_assoc($user));

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
$archive = mysql_query($query_archive, $brewing) or die(mysql_error());
$row_archive = mysql_fetch_assoc($archive);
$totalRows_archive = mysql_num_rows($archive);

if ($totalRows_archive > 0) {
	
	do { $a[] = $row_archive['archiveSuffix']; } while ($row_archive = mysql_fetch_assoc($archive));
	
	foreach ($a as $suffix) {
			
			
		$query_log = sprintf("SELECT brewPaid,brewWinner,brewReceived,brewUpdated,brewConfirmed,id FROM %s",$prefix."brewing_".$suffix);
		$log = mysql_query($query_log, $brewing) or die(mysql_error());
		$row_log = mysql_fetch_assoc($log);
		$totalRows_log = mysql_num_rows($log);
		
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
			mysql_select_db($database, $brewing);
			$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
			
		} while ($row_log = mysql_fetch_assoc($log));
		
		
		$query_user = sprintf("SELECT * FROM %s", $prefix."users_".$suffix);
		$user = mysql_query($query_user, $brewing) or die(mysql_error());
		$row_user = mysql_fetch_assoc($user);
		$totalRows_user = mysql_num_rows($user);
		
		do {
			$updateSQL = sprintf("UPDATE %s SET userCreated=NOW( ) WHERE id='%s'",$users_db_table,$row_user['id']); 
			mysql_select_db($database, $brewing);
			$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			
			if ($row_user['userQuestion'] == "") {
				
				$updateSQL = sprintf("UPDATE %s SET userQuestion='%s', userQuestionAnswer='%s' WHERE id='%s'",$prefix."users_".$suffix,"What is your favorite all-time beer to drink?","Pabst",$row_user['id']); 
				mysql_select_db($database, $brewing);
				$result = mysql_query($updateSQL, $brewing) or die(mysql_error());	
			
			}
			
		} while ($row_user = mysql_fetch_assoc($user));
		
		$output .= "<li>All archive entry data updated.</li>";
		
		/*
		// ------------------------ 
		// Not in 1.2.1.0 - Applies only a future release with multiple style sets
		// ------------------------
		
		do {
			
			$query_style = sprintf("SELECT id FROM styles_bjcp_2008 WHERE style_cat='%s' AND style_subcat='%s'", $row_log['brewCategorySort'],$row_log['brewSubCategory']);
			$style = mysql_query($query_style, $brewing) or die(mysql_error());
			$row_style = mysql_fetch_assoc($style);
			
			$updateSQL = sprintf("UPDATE brewing_$suffix SET brewStyle='%s',brewCategory='%s' WHERE id='%s'",$row_style['id'],"M",$row_log['id']);
			mysql_select_db($database, $brewing);
			$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			//$output .= $updateSQL."<br>";
			
		} while ($row_log = mysql_fetch_assoc($log));
		
		$output .= "<li>Updates to brewing_$suffix table completed.</li>";
		
		*/
		
		
		/* 
		
		// ------------------------ 
		// Not in 1.2.1.0 - Applies only a future release with multiple staff additions
		// ------------------------
		
		
		$query_brewer = "SELECT * FROM ".$prefix."brewer_".$suffix;
		$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
		$row_brewer = mysql_fetch_assoc($brewer);

		do { 

			if ($row_brewer['brewerAssignmentJudge'] == "J") { 
				$brewerAssignmentJudge = "1";
				$brewerAssignmentStaff = "0";
				$brewerAssignmentSteward = "0"; 
				$brewerAssignmentOrganizer = "0";
				}
				
			if ($row_brewer['brewerAssignmentJudge'] == "S") { 
				$brewerAssignmentJudge = "0";
				$brewerAssignmentStaff = "0";
				$brewerAssignmentSteward = "1"; 
				$brewerAssignmentOrganizer = "0";
				}
				
			if ($row_brewer['brewerAssignmentJudge'] == "X") { 
				$brewerAssignmentJudge = "0";
				$brewerAssignmentStaff = "1";
				$brewerAssignmentSteward = "0"; 
				$brewerAssignmentOrganizer = "0";
				}
			
			if ($row_brewer['brewerAssignmentJudge'] == "O") { 
				$brewerAssignmentJudge = "0";
				$brewerAssignmentStaff = "0";
				$brewerAssignmentSteward = "0"; 
				$brewerAssignmentOrganizer = "1";
				}
			
			if ($row_brewer['brewerAssignmentJudge'] == "") { 
				$brewerAssignmentJudge = "0";
				$brewerAssignmentStaff = "0";
				$brewerAssignmentSteward = "0"; 
				$brewerAssignmentOrganizer = "0";
				}
			if ($row_brewer['brewerJudge'] == "Y") $brewerJudge = "1"; else $brewerJudge = "0";
			if ($row_brewer['brewerSteward'] == "Y") $brewerSteward = "1"; else $brewerSteward = "0";
			if ($row_brewer['brewerJudgeBOS'] == "Y") $brewerJudgeBOS = "1"; else $brewerJudgeBOS = "0";
			if ($row_brewer['brewerDiscount'] == "Y") $brewerDiscount = "1"; else $brewerDiscount = "0";
			
			$updateSQL = sprintf("UPDATE ".$prefix."brewer_".$suffix." SET 
								 brewerJudge='%s',
								 brewerSteward='%s',
								 brewerAssignmentJudge='%s', 
								 brewerAssignmentStaff='%s', 
								 brewerAssignmentSteward='%s', 
								 brewerAssignmentOrganizer='%s', 
								 brewerJudgeBOS='%s', 
								 brewerDiscount='%s'
								 WHERE id='%s'", 
								 $brewerJudge,
								 $brewerSteward,
								 $brewerAssignmentJudge, 
								 $brewerAssignmentStaff,
								 $brewerAssignmentSteward, 
								 $brewerAssignmentOrganizer, 
								 $brewerJudgeBOS, 
								 $brewerDiscount, 
								 $row_brewer['id']);
			
			mysql_select_db($database, $brewing);
			$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			//$output .= $updateSQL."<br>";

			//$output .= "<li>Record for ".$row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']." updated.</li>";

			} 
			while ($row_brewer = mysql_fetch_assoc($brewer));

			// Change rows to tinyint type
			$updateSQL = "ALTER TABLE  `".$prefix."brewer_".$suffix."` CHANGE  `brewerAssignmentJudge`  `brewerAssignmentJudge` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false', CHANGE  `brewerJudgeBOS`  `brewerJudgeBOS` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false', CHANGE  `brewerDiscount`  `brewerDiscount` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false', CHANGE  `brewerJudge`  `brewerJudge` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false', CHANGE  `brewerSteward`  `brewerSteward` TINYINT(1) NULL DEFAULT NULL COMMENT '1 for true; 0 for false'";
			//$output .= $updateSQL."<br>";
			mysql_select_db($database, $brewing);
			$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			//$output .= $updateSQL."<br>";
			$output .= "<li>Updates to ".$prefix."brewer_".$suffix." table completed.</li>";
		   */
		  
		  
	} // end foreach
} // end if ($totalRows_archive > 0)

#========================================================================================================================================================
$output .= "</ul>";
?>