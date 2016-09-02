<?php
// -----------------------------------------------------------
// Alter Tables
// Version 2.1.6.0
// -----------------------------------------------------------

/*

// -----------------------------------------------------------
// Alter Table: XXX
// XXX
// -----------------------------------------------------------

if (!check_update("sponsorEnable", $prefix."sponsors")) {
	
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `XXX` TINYINT(1) NULL;",$prefix."XXX");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$output .=  "<li>XXX.</li>";
	
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `XXX` VARCHAR(255) NULL CHARACTER SET utf8 COLLATE utf8_general_ci;",$prefix."XXX");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$output .=  "<li>XXX.</li>";
}

ALTER TABLE %s CONVERT TO CHARACTER SET utf8;
ALTER DATABASE `brewcomp_bcoetest2` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
SELECT concat('alter table ', table_name, ' CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;') FROM information_schema.t‌​ables WHERE table_schema='<your_‌​database_name>' and table_collation != 'utf8_general_ci' GROUP BY table_name;
*/


// -----------------------------------------------------------
// Alter Table: preferences
// Add ability for admins to toggle dropoff and shipping location display
// -----------------------------------------------------------

if (!check_update("prefsShipping", $prefix."preferences")) {
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `prefsShipping` TINYINT(1) NULL DEFAULT NULL;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
}

if (!check_update("prefsDropOff", $prefix."preferences")) {
	$updateSQL = sprintf("ALTER TABLE `%s` ADD `prefsDropOff` TINYINT(1) NULL DEFAULT NULL;",$prefix."preferences");
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
}

?>