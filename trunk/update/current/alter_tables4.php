<?php 
// -----------------------------------------------------------
// Alter Table
// Version 1.3.0.0
//   Add/change table rows for expanded functions.
//   The "prefsPayToPrint" is a function requested by the AHA
//   for their national competition software.
// -----------------------------------------------------------

// -----------------------------------------------------------
// Alter Tables: Styles
// -----------------------------------------------------------

$updateSQL = "ALTER TABLE  `".$prefix."styles` ADD `brewStyleReqSpec` TINYINT(1) NULL DEFAULT NULL COMMENT 'Does the style require special ingredients be input? 1=yes 0=no';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '0'";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";

// Designate all BJCP styles that require special ingredients
$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 21;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 59;";
mysql_select_db($database, $brewing);

mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 65;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 74;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 75;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 76;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 78;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 79;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 80;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 86;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 87;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 89;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 94;";

mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 95;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 96;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 97;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 98;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";

$output .=  "<li>Styles table updated.</li>";




?>