<?php

// -----------------------------------------------------------
// Alter Tables
// Version 2.0.0.0
// -----------------------------------------------------------

// -----------------------------------------------------------
// Alter Table: Sponsors
// Adding sponsor enable flag for show/hide sponsor display
// -----------------------------------------------------------

$updateSQL0 = "ALTER TABLE `".$prefix."sponsors` ADD `sponsorEnable` TINYINT(1) NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL0);
$result0 = mysql_query($updateSQL0, $brewing); 

$output .=  "<li>Sponsors table altered successfully.</li>";

// -----------------------------------------------------------
// Alter Table: Contest Info
// Adding shipping open and close dates
// Add checkin password for future QR code functionality/portal
// -----------------------------------------------------------

$updateSQL1 = "ALTER TABLE  `".$prefix."contest_info` CHANGE `contestContactName` `contestShippingOpen` VARCHAR(255) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL1);
$result1 = mysql_query($updateSQL1, $brewing); 

$updateSQL2 = "ALTER TABLE  `".$prefix."contest_info` CHANGE `contestContactEmail` `contestShippingDeadline` VARCHAR(255) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL2);
$result2 = mysql_query($updateSQL2, $brewing); 

$updateSQL3 = "ALTER TABLE  `".$prefix."contest_info` ADD `contestCheckInPassword` VARCHAR(255) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL3);
$result3 = mysql_query($updateSQL3, $brewing); 

$output .=  "<li>Competition info table altered successfully.</li>";


// -----------------------------------------------------------
// Alter Table: Brewer
// Adding judge experience
// Add judge notes to organizers
// -----------------------------------------------------------

$updateSQL4 = "ALTER TABLE  `".$prefix."brewer` CHANGE `brewerJudgeAssignedLocation` `brewerJudgeExp` VARCHAR(25) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL4);
$result4 = mysql_query($updateSQL4, $brewing); 

$updateSQL5 = "ALTER TABLE  `".$prefix."brewer` CHANGE `brewerStewardAssignedLocation` `brewerJudgeNotes` TEXT NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL5);
$result5 = mysql_query($updateSQL5, $brewing); 


// -----------------------------------------------------------
// Alter Tables: Archived tables
// -----------------------------------------------------------

$query_archive_current = sprintf("SELECT archiveSuffix FROM %s",$archive_db_table);
$archive_current = mysql_query($query_archive_current, $brewing);
$row_archive_current = mysql_fetch_assoc($archive_current);
$totalRows_archive_current = mysql_num_rows($archive_current);

if ($totalRows_archive_current > 0) {
	
	do { $a_current[] = $row_archive_current['archiveSuffix']; } while ($row_archive_current = mysql_fetch_assoc($archive_current));
	
	foreach ($a_current as $suffix_current) {
		
		$suffix_current = "_".$suffix_current;
		
		// Update brewer table with changed values
		if (check_setup($prefix."brewer".$suffix_current,$database)) {
			
			$updateSQL4 = "ALTER TABLE  `".$prefix."brewer".$suffix_current."` CHANGE `brewerJudgeAssignedLocation` `brewerJudgeExp` VARCHAR(25) NULL DEFAULT NULL;";
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL4);
			$result4 = mysql_query($updateSQL4, $brewing); 
			
			$updateSQL5 = "ALTER TABLE  `".$prefix."brewer".$suffix_current."` CHANGE `brewerStewardAssignedLocation` `brewerJudgeNotes` TEXT NULL DEFAULT NULL;";
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL5);
			$result5 = mysql_query($updateSQL5, $brewing);
			
		} // end if (check_setup($prefix."brewer".$suffix_current,$database))
		
	} // end foreach ($a_current as $suffix_current)
	
} // end if ($totalRows_archive_current > 0)

$output .=  "<li>All archive brewer tables updated successfully.</li>";

// Remove countries table
$updateSQL6 = "DROP TABLE IF EXISTS `".$prefix."countries`";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL6);
$result6 = mysql_query($updateSQL6, $brewing);

$output .=  "<li>Countries table removed from the database.</li>";

// Remove themes table
$updateSQL6 = "DROP TABLE IF EXISTS `".$prefix."themes`";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL6);
$result6 = mysql_query($updateSQL6, $brewing);

$output .=  "<li>Themes table removed from the database.</li>";

?>