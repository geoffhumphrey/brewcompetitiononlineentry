<?php 

$output .= "<h2>Version 1.2.0.1, 1.2.0.2, and 1.2.0.3...</h2>";
$output .= "<ul>";
$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD  `brewJudgingNumber` VARCHAR( 10 ) NULL;"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$output .= "<li>Brewing table updated successfully.</li>";

$updateSQL = "ALTER TABLE  `".$prefix."brewer` ADD  `brewerJudgeMead` CHAR( 1 ) NULL AFTER  `brewerJudgeID` ;"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "ALTER TABLE  `".$prefix."brewer` ADD  `brewerAssignmentStaff` CHAR( 1 ) NULL AFTER  `brewerAssignment`;"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$output .= "<li>Brewer table updated successfully.</li>";

$updateSQL = "ALTER TABLE  `".$prefix."contest_info` ADD  `contestCircuit` TEXT NULL ;"; 
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$output .= "<li>Competition Info table updated successfully.</li>";

$output .= "</ul>";
?>