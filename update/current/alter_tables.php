<?php

// -----------------------------------------------------------
// Alter Table: preferences
//   Add/change table rows for expanded preference functions.
//   The "prefsPayToPrint" is a function requested by the AHA
//   for their national competition software.
// -----------------------------------------------------------

$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsUserEntryLimit`  INT(4) NULL DEFAULT NULL COMMENT 'Numeric limit of entries for each user';";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsUserSubCatLimit` INT(4) NULL DEFAULT NULL COMMENT 'Numeric limit of entries for each user per subcategory';";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsUSCLEx` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Array of exceptions corresponding to id in styles table';";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsUSCLExLimit` INT(4) NULL DEFAULT NULL COMMENT 'Numeric limit of entries for each user per subcategory that has been excepted';";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsPayToPrint`  CHAR(1) NULL DEFAULT NULL COMMENT 'Do users need to pay before printing entry paperwork?';";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsHideRecipe` CHAR(1) NULL DEFAULT NULL COMMENT 'Hide the recipe (optional) sections on the add/edit entry form?';";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsUseMods` CHAR(1) NULL DEFAULT NULL COMMENT 'Use the custom modules function (advanced users)';";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsSEF` CHAR(1) NULL DEFAULT NULL COMMENT 'Use search engine friendly URLs.';";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

$updateSQL = "UPDATE ".$prefix."preferences SET prefsPayToPrint='N', prefsHideRecipe='N' WHERE id='1'";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
			
echo "<ul><li>Site Preferences table updated.</li></ul>";

$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewBoxNum` VARCHAR(10) NULL DEFAULT NULL COMMENT 'The box where the entry is located after sorting';";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

echo "<ul><li>Brewing table updated.</li></ul>";

$updateSQL = "ALTER TABLE  `".$prefix."brewer` ADD `brewerDropOff` INT(4) NULL DEFAULT NULL COMMENT 'Location where brewer will drop off their entries; 0=shipping or relational to dropoff table';";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

echo "<ul><li>Brewer table updated.</li></ul>";

$updateSQL = "ALTER TABLE  `".$prefix."judging_scores` ADD `scoreMiniBOS` INT(4) NULL DEFAULT NULL COMMENT 'Did the entry go to the MiniBOS? 1=Yes, 0=No';";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

$updateSQL = "ALTER TABLE  `".$prefix."judging_scores` CHANGE  `scoreEntry`  `scoreEntry` DECIMAL( 11, 2 ) NULL DEFAULT NULL COMMENT  'Numerical score assigned by judges';";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

echo "<ul><li>Judging Scores table updated.</li></ul>"; 


$updateSQL = "ALTER TABLE  `".$prefix."styles` ADD `brewStyleReqSpec` TINYINT(1) NULL DEFAULT NULL COMMENT 'Does the style require special ingredients be input? 1=yes 0=no';";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

// Designate all BJCP styles that require special ingredients
$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 21;";
mysql_select_db($database, $brewing); 
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 59;";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 65;";
mysql_select_db($database, $brewing); 
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 74;";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 75;";
mysql_select_db($database, $brewing); 
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 76;";
mysql_select_db($database, $brewing); 
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 78;";
mysql_select_db($database, $brewing); 
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 79;";
mysql_select_db($database, $brewing); 
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 80;";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 86;";
mysql_select_db($database, $brewing); 
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 87;";
mysql_select_db($database, $brewing); 
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 89;";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 94;";
mysql_select_db($database, $brewing); 
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 95;";
mysql_select_db($database, $brewing); 
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 96;";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 97;";
mysql_select_db($database, $brewing); 
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 98;";
mysql_select_db($database, $brewing); 
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updatSQL = "";

echo "<ul><li>Styles table updated.</li></ul>";






// -----------------------------------------------------------
// Alter Tables: archived brewing tables
// -----------------------------------------------------------

$query_archive = "SELECT archiveSuffix FROM $archive_db_table";
$archive = mysql_query($query_archive, $brewing) or die(mysql_error());
$row_archive = mysql_fetch_assoc($archive);
$totalRows_archive = mysql_num_rows($archive);

if ($totalRows_archive > 0) {
	
	do { $a[] = $row_archive['archiveSuffix']; } while ($row_archive = mysql_fetch_assoc($archive));
	
	foreach ($a as $suffix) {
		$updateSQL = "ALTER TABLE `".$prefix."brewing_".$suffix."` ADD `brewBoxNum` VARCHAR(10) NULL DEFAULT NULL COMMENT 'The box where the entry is located after sorting';";
		mysql_select_db($database, $brewing);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewer_".$suffix."` ADD `brewerDropOff` INT(4) NULL DEFAULT NULL COMMENT 'Location where brewer will drop off their entries; 0=shipping or relational to dropoff table';";
		mysql_select_db($database, $brewing);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
		
		$updateSQL = "ALTER TABLE  `".$prefix."judging_scores_".$suffix."` ADD `scoreMiniBOS` INT(4) NULL DEFAULT NULL COMMENT 'Did the entry go to the MiniBOS? 1=Yes, 0=No';";
		mysql_select_db($database, $brewing);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
	}
}

echo "<ul><li>All archive entry data updated.</li></ul>";

?>