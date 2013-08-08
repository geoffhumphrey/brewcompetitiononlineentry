<?php

// -----------------------------------------------------------
// Alter Table
// Version 1.3.0.0
//   Add/change table rows for expanded functions.
//   The "prefsPayToPrint" is a function requested by the AHA
//   for their national competition software.
// -----------------------------------------------------------

// -----------------------------------------------------------
// Alter Tables: Preferences
// -----------------------------------------------------------

if (!NHC) {
	
	$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsUserEntryLimit`  VARCHAR(4) NULL DEFAULT NULL COMMENT 'Numeric limit of entries for each user';";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
	
	$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsUserSubCatLimit` VARCHAR(4) NULL DEFAULT NULL COMMENT 'Numeric limit of entries for each user per subcategory';";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>"; 
	
	$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsUSCLEx` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Array of exceptions corresponding to id in styles table';";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>"; 
	
	$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsUSCLExLimit` VARCHAR(4) NULL DEFAULT NULL COMMENT 'Numeric limit of entries for each user per subcategory that has been excepted';";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>"; 
	
	$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsPayToPrint`  CHAR(1) NULL DEFAULT NULL COMMENT 'Do users need to pay before printing entry paperwork?';";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>"; 
	
	$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsHideRecipe` CHAR(1) NULL DEFAULT NULL COMMENT 'Hide the recipe (optional) sections on the add/edit entry form?';";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>"; 
	
	$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsUseMods` CHAR(1) NULL DEFAULT NULL COMMENT 'Use the custom modules function (advanced users)';";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>"; 
	
	$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsSEF` CHAR(1) NULL DEFAULT NULL COMMENT 'Use search engine friendly URLs.';";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>"; 

}

$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD  `prefsSpecialCharLimit` INT(3) NULL DEFAULT NULL COMMENT 'Character limit for special ingredients field';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>"; 

$updateSQL = "UPDATE ".$prefix."preferences SET prefsPayToPrint='N', prefsHideRecipe='N', prefsUseMods='N', prefsSEF='N', prefsSpecialCharLimit='50' WHERE id='1'";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>"; 
			
$output .=  "<li>Site Preferences table updated.</li>";

// -----------------------------------------------------------
// Alter Tables: Brewer
// -----------------------------------------------------------

if (!NHC) {
	$updateSQL = "ALTER TABLE  `".$prefix."brewer` ADD `brewerDropOff` INT(4) NULL DEFAULT NULL COMMENT 'Location where brewer will drop off their entries; 0=shipping or relational to dropoff table';";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
	
	$updateSQL = "UPDATE `".$prefix."brewer` SET `brewerDropOff` = '0';";
	mysql_select_db($database, $brewing); 
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
	
	$output .=  "<li>Brewer table updated.</li>";
}

// -----------------------------------------------------------
// Alter Tables: Judging Scores
// -----------------------------------------------------------

if (!NHC) {

	$updateSQL = "ALTER TABLE  `".$prefix."judging_scores` ADD `scoreMiniBOS` TINYINT(1) NULL DEFAULT NULL COMMENT 'Did the entry go to the MiniBOS? 1=Yes, 0=No';";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
	
	$updateSQL = "UPDATE `".$prefix."judging_scores` SET `scoreMiniBOS` = '0';";
	mysql_select_db($database, $brewing); 
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
	
	$updateSQL = "ALTER TABLE  `".$prefix."judging_scores` CHANGE  `scoreEntry`  `scoreEntry` FLOAT NULL DEFAULT NULL COMMENT  'Numerical score assigned by judges';";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>"; 
	
	$output .=  "<li>Judging Scores table updated.</li>"; 

}
?>