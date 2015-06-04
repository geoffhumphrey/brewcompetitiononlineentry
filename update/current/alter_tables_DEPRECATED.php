<?php

// -----------------------------------------------------------
// Alter Table
// Version 1.3.1.0
// -----------------------------------------------------------

// -----------------------------------------------------------
// Alter Tables: Styles
// -----------------------------------------------------------


$updateSQL1 = "ALTER TABLE  `".$prefix."styles` CHANGE `brewStyleJudgingLoc` `brewStyleVersion` INT(8) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL2);
$result1 = mysql_query($updateSQL1, $brewing); 

$updateSQL2 = "ALTER TABLE  `".$prefix."styles` ADD `brewStyleEntity` VARCHAR(20) NULL DEFAULT NULL COMMENT 'Style Entity (e.g., BJCP, BA)';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL2);
$result2 = mysql_query($updateSQL2, $brewing); 




/*
$updateSQL3 = "ALTER TABLE  `".$prefix."preferences` ADD `prefsUSCLEx` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Array of exceptions corresponding to id in styles table';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL3);
$result3 = mysql_query($updateSQL3, $brewing); 

$updateSQL4 = "ALTER TABLE  `".$prefix."preferences` ADD `prefsUSCLExLimit` VARCHAR(4) NULL DEFAULT NULL COMMENT 'Numeric limit of entries for each user per subcategory that has been excepted';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL4);
$result4 = mysql_query($updateSQL4, $brewing); 

$updateSQL5 = "ALTER TABLE  `".$prefix."preferences` ADD `prefsPayToPrint`  CHAR(1) NULL DEFAULT NULL COMMENT 'Do users need to pay before printing entry paperwork?';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL5);
$result5 = mysql_query($updateSQL5, $brewing); 

$updateSQL6 = "ALTER TABLE  `".$prefix."preferences` ADD `prefsHideRecipe` CHAR(1) NULL DEFAULT NULL COMMENT 'Hide the recipe (optional) sections on the add/edit entry form?';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL6);
$result6 = mysql_query($updateSQL6, $brewing); 

$updateSQL7 = "ALTER TABLE  `".$prefix."preferences` ADD `prefsUseMods` CHAR(1) NULL DEFAULT NULL COMMENT 'Use the custom modules function (advanced users)';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL7);
$result7 = mysql_query($updateSQL7, $brewing); 

$updateSQL8 = "ALTER TABLE  `".$prefix."preferences` ADD `prefsSEF` CHAR(1) NULL DEFAULT NULL COMMENT 'Use search engine friendly URLs.';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL8);
$result8 = mysql_query($updateSQL8, $brewing); 

$updateSQL9 = "ALTER TABLE  `".$prefix."preferences` ADD  `prefsSpecialCharLimit` INT(3) NULL DEFAULT NULL COMMENT 'Character limit for special ingredients field';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL9);
$result9 = mysql_query($updateSQL9, $brewing); 

$updateSQL0 = "UPDATE ".$prefix."preferences SET prefsPayToPrint='N', prefsHideRecipe='N', prefsUseMods='N', prefsSEF='N', prefsSpecialCharLimit='50' WHERE id='1'";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL0);
$result0 = mysql_query($updateSQL0, $brewing); 
			
*/


$output .=  "<li>Styles table altered successfully.</li>";

?>