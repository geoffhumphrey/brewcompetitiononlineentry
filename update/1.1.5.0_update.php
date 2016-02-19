<?php 
$output .= "<h4>Version 1.1.5...</h4>";
$output .= "<ul>";

$updateSQL = "ALTER TABLE `".$prefix."sponsors` ADD `sponsorLevel` TINYINT( 1 ) NULL;"; 
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
$output .= "<li>Update to sponsors table completed.</li>";

$updateSQL = "CREATE TABLE `".$prefix."contacts` (`id` INT( 8 ) NOT NULL AUTO_INCREMENT PRIMARY KEY , `contactFirstName` VARCHAR( 255 ) NULL ,
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
?>