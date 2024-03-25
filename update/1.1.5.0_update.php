<?php

if (!function_exists('check_update')) {
	$redirect = "../../403.php";
	$redirect_go_to = sprintf("Location: %s", $redirect);
	header($redirect_go_to);
	exit();
}

$output .= "<h4>Version 1.1.5.0</h4>";

if (!check_update("sponsorLevel", $prefix."sponsors")) {
	
	$output .= "<ul>";
	$updateSQL = "ALTER TABLE `".$prefix."sponsors` ADD `sponsorLevel` TINYINT( 1 ) NULL;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	$output .= "<li>Update to sponsors table completed.</li>";
	
	$updateSQL = "CREATE TABLE IF NOT EXISTS `".$prefix."contacts` (`id` INT( 8 ) NOT NULL AUTO_INCREMENT PRIMARY KEY , `contactFirstName` VARCHAR( 255 ) NULL ,
	`contactLastName` VARCHAR( 255 ) NULL , `contactPosition` VARCHAR( 255 ) NULL , `contactEmail` VARCHAR( 255 ) NULL) ENGINE = MYISAM ;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	$output .= "<li>Contacts table added.</li>";
	
	$updateSQL = "ALTER TABLE `".$prefix."drop_off` ADD `dropLocationNotes` VARCHAR( 255 ) NULL;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	$output .= "<li>Updates to the drop off table completed.</li>";

	$updateSQL = "ALTER TABLE `".$prefix."preferences` ADD `prefsEntryForm` CHAR( 1 ) NULL ;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$updateSQL = "UPDATE `".$prefix."preferences` SET `prefsEntryForm` = 'B' WHERE `id` =1 ;"; 
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$output .= "<li>Updates to preferences table completed.</li>";
	$output .= "</ul>";
	
	// Update user levels of top admins to 0
	$query_user_level = sprintf("SELECT id,userLevel FROM %s WHERE userLevel='1'",$users_db_table);
	$user_level = mysqli_query($connection,$query_user_level) or die (mysqli_error($connection));
	$row_user_level = mysqli_fetch_assoc($user_level);
	
	do {
		$updateSQL = sprintf("UPDATE `%s` SET userLevel='0' WHERE id='%s';", $prefix."users",$row_user_level['id']); 
		mysqli_real_escape_string($connection,$updateSQL);
		$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	} while ($row_user_level = mysqli_fetch_assoc($user_level)); 

}

else $output .= "<p>None</p>";
?>