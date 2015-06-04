<?php 


$output .= "<h2>Version 1.1.6...</h2>";
$output .= "<ul>";
$updateSQL = "ALTER TABLE `".$prefix."brewer` CHANGE `brewerJudgeAssignedLocation` `brewerJudgeAssignedLocation` VARCHAR( 255 ) NULL DEFAULT NULL, CHANGE `brewerStewardAssignedLocation` `brewerStewardAssignedLocation` VARCHAR( 255 ) NULL DEFAULT NULL, CHANGE `brewerJudgeLocation` `brewerJudgeLocation` VARCHAR( 255 ) NULL DEFAULT NULL, CHANGE `brewerStewardLocation` `brewerStewardLocation` VARCHAR( 255 ) NULL DEFAULT NULL, ADD `brewerAHA` INT( 11 ) NULL;"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
$output .= "<li>Updates to the brewer table completed.</li>";

$updateSQL = "ALTER TABLE `".$prefix."preferences` ADD `prefsRecordLimit` INT( 11 ) NULL DEFAULT '500' COMMENT 'User defined record limit for using DataTables vs. PHP paging';"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "ALTER TABLE `".$prefix."preferences` ADD `prefsRecordPaging` INT( 11 ) NULL DEFAULT '30' COMMENT 'User defined per page record limit'"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."preferences` SET `prefsRecordLimit` = '500', `prefsRecordPaging` = '50' WHERE `id` = '1';"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
$output .= "<li>Updates to preferences info table completed.</li>";

$updateSQL = "ALTER TABLE `".$prefix."brewing` CHANGE `brewPaid` `brewPaid` CHAR( 1 ) NULL DEFAULT 'N' ;"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "ALTER TABLE `".$prefix."brewing` ADD `brewCoBrewer` VARCHAR( 255 ) NULL ;"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$output .= "<li>Updates to brewing table completed.</li>";

$output .= "</ul>";
?>