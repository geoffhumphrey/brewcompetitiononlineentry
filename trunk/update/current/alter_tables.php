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

$updateSQL = "UPDATE ".$prefix."preferences SET prefsPayToPrint='N', prefsHideRecipe='N' WHERE id='1'";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
			
echo "<ul><li>Site Preferences table updated.</li></ul>";

$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewBoxNum` VARCHAR(10) NULL DEFAULT NULL COMMENT 'The box where the entry is located after sorting';";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

echo "<ul><li>Brewing table updated.</li></ul>";

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
	}
}

echo "<ul><li>All archive entry data updated.</li></ul>";

?>