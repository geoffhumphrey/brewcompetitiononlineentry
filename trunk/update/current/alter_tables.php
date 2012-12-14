<?php

// -----------------------------------------------------------
// Alter Table: preferences
//   Add/change table rows for expanded preference functions.
//   The "prefsPayToPrint" is a function requested by the AHA
//   for their national competition software. The function will
//   be commented out in the HTML. BCOEM will ship with a N value.
// -----------------------------------------------------------

$updateSQL = "
ALTER TABLE  `".$prefix."preferences`
ADD `prefsUserEntryLimit`  INT(4) NULL DEFAULT NULL COMMENT 'Numeric limit of entries for each user' ,
ADD `prefsUserSubCatLimit` INT(4) NULL DEFAULT NULL COMMENT 'Numeric limit of entries for each user per subcategory' ,
ADD `prefsPayToPrint`  CHAR(1) NULL DEFAULT NULL COMMENT 'Do users need to pay before printing entry paperwork?' ,
ADD `prefsHideRecipe` CHAR(1) NULL DEFAULT NULL COMMENT 'Hide the recipe (optional) sections on the add/edit entry form?',
ADD `prefsUseMods` CHAR(1) NULL DEFAULT NULL COMMENT 'Use the custom modules function (advanced users)';
";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
//echo $updateSQL."<br>";

$updateSQL = "UPDATE ".$prefix."preferences SET prefsPayToPrint='N', prefsHideRecipe='N' WHERE id='1'";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
//echo $updateSQL."<br>";
			
echo "<ul><li>Site Preferences table updated.</li></ul>";

?>