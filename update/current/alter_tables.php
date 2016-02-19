<?php

// -----------------------------------------------------------
// Alter Tables
// Version 2.0.0.0
// -----------------------------------------------------------

// -----------------------------------------------------------
// Alter Table: Sponsors
// Adding sponsor enable flag for show/hide sponsor display
// -----------------------------------------------------------
if (!check_update("sponsorEnable", $prefix."sponsors")) {
	$updateSQL = "ALTER TABLE `".$prefix."sponsors` ADD `sponsorEnable` TINYINT(1) NULL;";
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

	$output .=  "<li>Sponsors table altered successfully.</li>";
}

// -----------------------------------------------------------
// Alter Table: Contest Info
// Adding shipping open and close dates
// Add checkin password for future QR code functionality/portal
// -----------------------------------------------------------

; 

if (!check_update("contestShippingOpen", $prefix."contest_info")) {
	$updateSQL = "ALTER TABLE  `".$prefix."contest_info` CHANGE `contestContactName` `contestShippingOpen` VARCHAR(255) NULL DEFAULT NULL;";
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
}
if (!check_update("contestShippingDeadline", $prefix."contest_info")) {
	$updateSQL = "ALTER TABLE  `".$prefix."contest_info` CHANGE `contestContactEmail` `contestShippingDeadline` VARCHAR(255) NULL DEFAULT NULL;";
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
}

if (!check_update("contestCheckInPassword", $prefix."contest_info")) {
	$updateSQL= "ALTER TABLE  `".$prefix."contest_info` ADD `contestCheckInPassword` VARCHAR(255) NULL DEFAULT NULL;";
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
}

if (!check_update("contestDropoffOpen", $prefix."contest_info")) {
	$updateSQL = "ALTER TABLE  `".$prefix."contest_info` CHANGE `contestCategories` `contestDropoffOpen` VARCHAR(255) NULL DEFAULT NULL;";
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
}

if (!check_update("contestDropoffDeadline", $prefix."contest_info")) {
	$updateSQL = "ALTER TABLE  `".$prefix."contest_info` CHANGE `contestWinnersComplete` `contestDropoffDeadline` VARCHAR(255) NULL DEFAULT NULL;";
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
}

$output .=  "<li>Competition info table altered successfully.</li>";


// -----------------------------------------------------------
// Alter Table: Brewer
// Adding judge experience
// Add judge notes to organizers
// -----------------------------------------------------------

$updateSQL = "ALTER TABLE  `".$prefix."brewer` CHANGE `brewerJudgeAssignedLocation` `brewerJudgeExp` VARCHAR(25) NULL DEFAULT NULL;";
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$updateSQL = "ALTER TABLE  `".$prefix."brewer` CHANGE `brewerStewardAssignedLocation` `brewerJudgeNotes` TEXT NULL DEFAULT NULL;";
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));


// -----------------------------------------------------------
// Alter Tables: Archived tables
// -----------------------------------------------------------

$query_archive_current = sprintf("SELECT archiveSuffix FROM %s",$archive_db_table);
$archive_current = mysqli_query($connection,$query_archive_current) or die (mysqli_error($connection));
$row_archive_current = mysqli_fetch_assoc($archive_current);
$totalRows_archive_current = mysqli_num_rows($archive_current);

if ($totalRows_archive_current > 0) {
	
	do { $a_current[] = $row_archive_current['archiveSuffix']; } while ($row_archive_current = mysqli_fetch_assoc($archive_current));
	
	foreach ($a_current as $suffix_current) {
		
		$suffix_current = "_".$suffix_current;
		
		// Update brewer table with changed values
		if (check_setup($prefix."brewer".$suffix_current,$database)) {
			
			$updateSQL = "ALTER TABLE  `".$prefix."brewer".$suffix_current."` CHANGE `brewerJudgeAssignedLocation` `brewerJudgeExp` VARCHAR(25) NULL DEFAULT NULL;";
			mysqli_select_db($connection,$database);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection)); 
			
			$updateSQL = "ALTER TABLE  `".$prefix."brewer".$suffix_current."` CHANGE `brewerStewardAssignedLocation` `brewerJudgeNotes` TEXT NULL DEFAULT NULL;";
			mysqli_select_db($connection,$database);
			mysqli_real_escape_string($connection,$updateSQL);
			$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
			
		} // end if (check_setup($prefix."brewer".$suffix_current,$database))
		
	} // end foreach ($a_current as $suffix_current)
	
} // end if ($totalRows_archive_current > 0)

$output .=  "<li>All archive brewer tables updated successfully.</li>";

// Remove countries table
$updateSQL = "DROP TABLE IF EXISTS `".$prefix."countries`";
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$output .=  "<li>Countries table removed from the database.</li>";

// Remove themes table
$updateSQL = "DROP TABLE IF EXISTS `".$prefix."themes`";
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$output .=  "<li>Themes table removed from the database.</li>";

?>