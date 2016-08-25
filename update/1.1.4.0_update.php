<?php 
$output .= "<h4>Version 1.1.4.0</h4>";

if (!check_update("contestRegistrationOpen", $prefix."contests_info")) {
	
	$output .= "<ul>";
	
	$updateSQL = "ALTER TABLE `".$prefix."contest_info` ADD `contestRegistrationOpen` DATE NULL AFTER `contestHostLocation`, ADD `contestEntryOpen` DATE NULL AFTER `contestRegistrationDeadline`;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	$output .= "<li>Updates to competition info table completed.</li>";

	$updateSQL = "ALTER TABLE `".$prefix."preferences` ADD `prefsBOSMead` CHAR( 1 ) NULL DEFAULT 'N', ADD `prefsBOSCider` CHAR( 1 ) NULL DEFAULT 'N';"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	$output .= "<li>Updates to preferences info table completed.</li>";
	
	$output .= "</ul>";

}

else $output .= "<p>None</p>";
?>