<?php 
// -----------------------------------------------------------
// Alter Table
// Version 1.3.0.0
//   Add/change table rows for expanded functions.
//   The "prefsPayToPrint" is a function requested by the AHA
//   for their national competition software.
// -----------------------------------------------------------

// -----------------------------------------------------------
// Alter Tables: Brewing/Entries
// -----------------------------------------------------------

// Update character counts for ingredient columns to keep under 65000 threshold

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewMead1` `brewMead1` VARCHAR(25) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewMead2` `brewMead2` VARCHAR(25) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewMead3` `brewMead3` VARCHAR(25) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewYeast` `brewYeast` VARCHAR(100) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewYeastMan` `brewYeastMan` VARCHAR(100) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewYeastForm` `brewYeastForm` VARCHAR(10) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewYeastType` `brewYeastType` VARCHAR(10) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewYeastNutrients` `brewYeastNutrients` text;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewFinings` `brewFinings` text;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewWaterNotes` `brewWaterNotes` text;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewCarbonationMethod` `brewCarbonationMethod` CHAR(1) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";



?>