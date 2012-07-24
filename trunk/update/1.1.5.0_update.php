<?php 
echo "<h2>Performing Updates for Version 1.1.5...</h2>";

$updateSQL = "ALTER TABLE `sponsors` ADD `sponsorLevel` TINYINT( 1 ) NULL;"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
echo "<ul><li>Update to sponsors table completed.</li></ul>";

$updateSQL = "CREATE TABLE `contacts` (`id` INT( 8 ) NOT NULL AUTO_INCREMENT PRIMARY KEY , `contactFirstName` VARCHAR( 255 ) NULL ,
`contactLastName` VARCHAR( 255 ) NULL , `contactPosition` VARCHAR( 255 ) NULL , `contactEmail` VARCHAR( 255 ) NULL) ENGINE = MYISAM ;"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
echo "<ul><li>Contacts table added.</li></ul>";

$updateSQL = "ALTER TABLE `drop_off` ADD `dropLocationNotes` VARCHAR( 255 ) NULL;"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
echo "<ul><li>Updates to the drop off table completed.</li></ul>";

$updateSQL = "ALTER TABLE `preferences` ADD `prefsEntryForm` CHAR( 1 ) NULL ;"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `preferences` SET `prefsEntryForm` = 'B' WHERE `id` =1 ;"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
echo "<ul><li>Updates to preferences table completed.</li></ul>";



?>