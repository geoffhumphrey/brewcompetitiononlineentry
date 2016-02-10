<?php 
$output .= "<h4>Version 1.1.4.0..</h4>";
$output .= "<ul>";
$updateSQL = "ALTER TABLE `".$prefix."contest_info` ADD `contestRegistrationOpen` DATE NULL AFTER `contestHostLocation`, ADD `contestEntryOpen` DATE NULL AFTER `contestRegistrationDeadline`;"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
$output .= "<li>Updates to competition info table completed.</li>";
$updateSQL = "ALTER TABLE `".$prefix."preferences` ADD `prefsBOSMead` CHAR( 1 ) NULL DEFAULT 'N', ADD `prefsBOSCider` CHAR( 1 ) NULL DEFAULT 'N';"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
$output .= "<li>Updates to preferences info table completed.</li>";
$output .= "</ul>";
?>