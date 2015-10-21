<?php

// -----------------------------------------------------------
// Alter Tables
// Version 1.3.1.0 and 1.3.2.0
// -----------------------------------------------------------

// -----------------------------------------------------------
// Alter Table: Preferences
// Adding style set preferences
// -----------------------------------------------------------

$updateSQL0 = "ALTER TABLE `".$prefix."preferences` ADD `prefsStyleSet` VARCHAR( 20 ) NULL";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL0);
$result0 = mysql_query($updateSQL0, $brewing); 

$updateSQL10 = "ALTER TABLE `".$prefix."preferences` ADD `prefsAutoPurge` TINYINT( 1 ) NULL";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL10);
$result10 = mysql_query($updateSQL10, $brewing); 

//if (check_db_table_column("preferences","prefsStyleSet")) 
$output .=  "<li>Preferences table altered successfully.</li>";
//else $output .= "<li>Preferences table NOT altered successfully.";

// -----------------------------------------------------------
// Alter Table: Styles
// Adding columns to allow for more than one styleset
// (BJCP2008, BJCP2015, BA for future releases) and to 
// shift carbonation, sweetnes, and strength to DB side
// -----------------------------------------------------------


$updateSQL1 = "ALTER TABLE  `".$prefix."styles` CHANGE `brewStyleJudgingLoc` `brewStyleVersion` VARCHAR(20) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL2);
$result1 = mysql_query($updateSQL1, $brewing); 

$updateSQL3 = "ALTER TABLE  `".$prefix."styles` ADD `brewStyleStrength` INT(1) NULL COMMENT 'Requires strength? 0=No, 1=Yes';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL3);
$result3 = mysql_query($updateSQL3, $brewing); 

$updateSQL4 = "ALTER TABLE  `".$prefix."styles` ADD `brewStyleCarb` INT(1) NULL COMMENT 'Requires carbonation? 0=No, 1=Yes';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL4);
$result4 = mysql_query($updateSQL4, $brewing); 

$updateSQL5 = "ALTER TABLE  `".$prefix."styles` ADD `brewStyleSweet` INT(1) NULL COMMENT 'Requires sweetness? 0=No, 1=Yes';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL5);
$result5 = mysql_query($updateSQL5, $brewing); 

$updateSQL6 = "ALTER TABLE  `".$prefix."styles` ADD `brewStyleCategory` VARCHAR(255) NULL DEFAULT NULL AFTER `brewStyle`;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL6);
$result6 = mysql_query($updateSQL6, $brewing);

$updateSQL7 = "ALTER TABLE  `".$prefix."styles` ADD `brewStyleTags` VARCHAR(255) NULL DEFAULT NULL";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL7);
$result7 = mysql_query($updateSQL7, $brewing);

//if (check_db_table_column("preferences","prefsStyleSet")) 
$output .=  "<li>Styles table altered successfully.</li>";
//else $output .=  "<li>Styles table NOT altered successfully.</li>";

// -----------------------------------------------------------
// Alter Table: Brewing
// Need to alter two columns to allow for 2015 BJCP style sub-
// category identification schema (numeric to alpha numeric)
// -----------------------------------------------------------

$updateSQL8 = "ALTER TABLE `".$prefix."brewing` CHANGE `brewCategory` `brewCategory` VARCHAR(4) NULL DEFAULT NULL";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL8);
$result8 = mysql_query($updateSQL8, $brewing); 

$updateSQL9 = "ALTER TABLE `".$prefix."brewing` CHANGE `brewCategorySort` `brewCategorySort` VARCHAR(4) NULL DEFAULT NULL";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL9);
$result9 = mysql_query($updateSQL9, $brewing); 

$output .=  "<li>Entries table altered successfully.</li>";

?>