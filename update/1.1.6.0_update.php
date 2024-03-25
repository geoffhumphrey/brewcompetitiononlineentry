<?php

if (!function_exists('check_update')) {
	$redirect = "../../403.php";
	$redirect_go_to = sprintf("Location: %s", $redirect);
	header($redirect_go_to);
	exit();
}
 
$output .= "<h4>Version 1.1.6.0</h4>";

if (!check_update("prefsRecordLimit", $prefix."preferences")) {
	
	$output .= "<ul>";

	$updateSQL = "ALTER TABLE `".$prefix."brewer` CHANGE `brewerJudgeAssignedLocation` `brewerJudgeAssignedLocation` VARCHAR( 255 ) NULL DEFAULT NULL, CHANGE `brewerStewardAssignedLocation` `brewerStewardAssignedLocation` VARCHAR( 255 ) NULL DEFAULT NULL, CHANGE `brewerJudgeLocation` `brewerJudgeLocation` VARCHAR( 255 ) NULL DEFAULT NULL, CHANGE `brewerStewardLocation` `brewerStewardLocation` VARCHAR( 255 ) NULL DEFAULT NULL, ADD `brewerAHA` INT( 11 ) NULL;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	$output .= "<li>Updates to the brewer table completed.</li>";
	
	$updateSQL = "ALTER TABLE `".$prefix."preferences` ADD `prefsRecordLimit` INT( 11 ) NULL DEFAULT '500' COMMENT 'User defined record limit for using DataTables vs. PHP paging';"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "ALTER TABLE `".$prefix."preferences` ADD `prefsRecordPaging` INT( 11 ) NULL DEFAULT '30' COMMENT 'User defined per page record limit'"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	
	
	$updateSQL = "UPDATE `".$prefix."preferences` SET `prefsRecordLimit` = '500', `prefsRecordPaging` = '50' WHERE `id` = '1';"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	$output .= "<li>Updates to preferences info table completed.</li>";
	
	$updateSQL = "ALTER TABLE `".$prefix."brewing` CHANGE `brewPaid` `brewPaid` CHAR( 1 ) NULL DEFAULT 'N' ;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "ALTER TABLE `".$prefix."brewing` ADD `brewCoBrewer` VARCHAR( 255 ) NULL ;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$output .= "<li>Updates to brewing table completed.</li>";
	$output .= "</ul>";
	
}

else $output .= "<p>None</p>";
?>