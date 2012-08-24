<?php 
echo "<h2>Performing Updates for Version 1.1.6...</h2>";

$updateSQL = "ALTER TABLE `".$prefix."brewer` CHANGE `brewerJudgeAssignedLocation` `brewerJudgeAssignedLocation` VARCHAR( 255 ) NULL DEFAULT NULL, CHANGE `brewerStewardAssignedLocation` `brewerStewardAssignedLocation` VARCHAR( 255 ) NULL DEFAULT NULL, CHANGE `brewerJudgeLocation` `brewerJudgeLocation` VARCHAR( 255 ) NULL DEFAULT NULL, CHANGE `brewerStewardLocation` `brewerStewardLocation` VARCHAR( 255 ) NULL DEFAULT NULL, ADD `brewerAHA` INT( 11 ) NULL;"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
echo "<ul><li>Updates to the brewer table completed.</li></ul>";

$updateSQL = "ALTER TABLE `".$prefix."preferences` ADD `prefsRecordLimit` INT( 11 ) NULL DEFAULT '300' COMMENT 'User defined record limit for using DataTables vs. PHP paging';"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "ALTER TABLE `".$prefix."preferences` ADD `prefsRecordLimit` INT( 11 ) NULL DEFAULT '300' COMMENT 'User defined record limit for using DataTables vs. PHP paging';"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."preferences` SET `prefsRecordLimit` = '300', `prefsRecordPaging` = '30' WHERE `id` = 1;"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
echo "<ul><li>Updates to preferences info table completed.</li></ul>";

$updateSQL = "ALTER TABLE `".$prefix."brewing` CHANGE `brewPaid` `brewPaid` CHAR( 1 ) NULL DEFAULT 'N', ADD `brewCoBrewer` VARCHAR( 255 ) NULL ;"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
echo "<ul><li>Updates to brewing table completed.</li></ul>";

?>