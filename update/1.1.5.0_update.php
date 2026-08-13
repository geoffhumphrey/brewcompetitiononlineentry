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
	$result = $db_conn->rawQuery($updateSQL);
	$output .= "<li>Update to sponsors table completed.</li>";
	
	$updateSQL = "CREATE TABLE IF NOT EXISTS `".$prefix."contacts` (`id` INT( 8 ) NOT NULL AUTO_INCREMENT PRIMARY KEY , `contactFirstName` VARCHAR( 255 ) NULL ,
	`contactLastName` VARCHAR( 255 ) NULL , `contactPosition` VARCHAR( 255 ) NULL , `contactEmail` VARCHAR( 255 ) NULL) ENGINE = MYISAM ;"; 
	$result = $db_conn->rawQuery($updateSQL);
	$output .= "<li>Contacts table added.</li>";
	
	$updateSQL = "ALTER TABLE `".$prefix."drop_off` ADD `dropLocationNotes` VARCHAR( 255 ) NULL;"; 
	$result = $db_conn->rawQuery($updateSQL);
	$output .= "<li>Updates to the drop off table completed.</li>";

	$updateSQL = "ALTER TABLE `".$prefix."preferences` ADD `prefsEntryForm` CHAR( 1 ) NULL ;"; 
	$result = $db_conn->rawQuery($updateSQL);
	
	$updateSQL = "UPDATE `".$prefix."preferences` SET `prefsEntryForm` = 'B' WHERE `id` =1 ;"; 
	$result = $db_conn->rawQuery($updateSQL);
	
	$output .= "<li>Updates to preferences table completed.</li>";
	$output .= "</ul>";
	
	// Update user levels of top admins to 0
	$db_conn->where('userLevel', '1');
	$rows_user_level = $db_conn->get($users_db_table, null, "id,userLevel");

	foreach ($rows_user_level as $row_user_level) {
		$db_conn->where('id', $row_user_level['id']);
		$result = $db_conn->update($prefix."users", array('userLevel' => '0'));
	}

}

else $output .= "<p>None</p>";
?>