<?php

// -----------------------------------------------------------
// Alter Table
// Version 1.3.0.0
//   Add/change table rows for expanded preference functions.
//   The "prefsPayToPrint" is a function requested by the AHA
//   for their national competition software.
// -----------------------------------------------------------

// -----------------------------------------------------------
// Alter Tables: Preferences
// -----------------------------------------------------------

$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsUserEntryLimit`  VARCHAR(4) NULL DEFAULT NULL COMMENT 'Numeric limit of entries for each user';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsUserSubCatLimit` VARCHAR(4) NULL DEFAULT NULL COMMENT 'Numeric limit of entries for each user per subcategory';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsUSCLEx` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Array of exceptions corresponding to id in styles table';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsUSCLExLimit` VARCHAR(4) NULL DEFAULT NULL COMMENT 'Numeric limit of entries for each user per subcategory that has been excepted';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsPayToPrint`  CHAR(1) NULL DEFAULT NULL COMMENT 'Do users need to pay before printing entry paperwork?';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsHideRecipe` CHAR(1) NULL DEFAULT NULL COMMENT 'Hide the recipe (optional) sections on the add/edit entry form?';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsUseMods` CHAR(1) NULL DEFAULT NULL COMMENT 'Use the custom modules function (advanced users)';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsSEF` CHAR(1) NULL DEFAULT NULL COMMENT 'Use search engine friendly URLs.';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD  `prefsSpecialCharLimit` INT(3) NULL DEFAULT NULL COMMENT 'Character limit for special ingredients field';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

$updateSQL = "UPDATE ".$prefix."preferences SET prefsPayToPrint='N', prefsHideRecipe='N', prefsUseMods='N', prefsSEF='N', prefsSpecialCharLimit='50' WHERE id='1'";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
			
$output .=  "<li>Site Preferences table updated.</li>";

// -----------------------------------------------------------
// Alter Tables: Brewing/Entries
// -----------------------------------------------------------

$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewBoxNum` VARCHAR(10) NULL DEFAULT NULL COMMENT 'The box where the entry is located after sorting';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

for ($i=10; $i <= 20; $i++) {
	
	$one_less = ($i - 1);
	
	// Grains
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewGrain".$i."` VARCHAR(100) NULL DEFAULT NULL AFTER `brewGrain".$one_less."`;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewGrain".$i."Weight` VARCHAR(10) NULL DEFAULT NULL AFTER `brewGrain".$one_less."Weight`;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewGrain".$i."Use` VARCHAR(25) NULL DEFAULT NULL AFTER `brewGrain".$one_less."Use`;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
	// Additions, Adjucnts, etc.
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewAddition".$i."` VARCHAR(100) NULL DEFAULT NULL AFTER `brewAddition".$one_less."`;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewAddition".$i."Amt` VARCHAR(25) NULL DEFAULT NULL AFTER `brewAddition".$one_less."Amt`;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewAddition".$i."Use` VARCHAR(25) NULL DEFAULT NULL AFTER `brewAddition".$one_less."Use`;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
	// Hops
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewHops".$i."` VARCHAR(100) NULL DEFAULT NULL AFTER `brewHops".$one_less."`;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewHops".$i."Weight` VARCHAR(10) NULL DEFAULT NULL AFTER `brewHops".$one_less."Weight`;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewHops".$i."Use` VARCHAR(25) NULL DEFAULT NULL AFTER `brewHops".$one_less."Use`;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewHops".$i."IBU` VARCHAR(6) NULL DEFAULT NULL AFTER `brewHops".$one_less."IBU`;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewHops".$i."Time` VARCHAR(25) NULL DEFAULT NULL AFTER `brewHops".$one_less."Time`;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewHops".$i."Type` VARCHAR(25) NULL DEFAULT NULL AFTER `brewHops".$one_less."Type`;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewHops".$i."Form` VARCHAR(25) NULL DEFAULT NULL AFTER `brewHops".$one_less."Form`;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
}

for ($i=6; $i <= 10; $i++) {
	
	$one_less = ($i - 1);
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewMashStep".$i."Name` VARCHAR(100) NULL DEFAULT NULL AFTER `brewMashStep".$one_less."Name`;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewMashStep".$i."Temp` VARCHAR(10) NULL DEFAULT NULL AFTER `brewMashStep".$one_less."Temp`;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewMashStep".$i."Time` VARCHAR(10) NULL DEFAULT NULL AFTER `brewMashStep".$one_less."Time`;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
	
}

$output .=  "<li>Brewing table updated.</li>";

// -----------------------------------------------------------
// Alter Tables: Brewer
// -----------------------------------------------------------

$updateSQL = "ALTER TABLE  `".$prefix."brewer` ADD `brewerDropOff` INT(4) NULL DEFAULT NULL COMMENT 'Location where brewer will drop off their entries; 0=shipping or relational to dropoff table';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."brewer` SET `brewerDropOff` = '0'";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$output .=  "<li>Brewer table updated.</li>";

// -----------------------------------------------------------
// Alter Tables: Judging Scores
// -----------------------------------------------------------

$updateSQL = "ALTER TABLE  `".$prefix."judging_scores` ADD `scoreMiniBOS` TINYINT(1) NULL DEFAULT NULL COMMENT 'Did the entry go to the MiniBOS? 1=Yes, 0=No';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."judging_scores` SET `scoreMiniBOS` = '0'";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "ALTER TABLE  `".$prefix."judging_scores` CHANGE  `scoreEntry`  `scoreEntry` DECIMAL(11,2) NULL DEFAULT NULL COMMENT  'Numerical score assigned by judges';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 

$output .=  "<li>Judging Scores table updated.</li>"; 

// -----------------------------------------------------------
// Alter Tables: Styles
// -----------------------------------------------------------

$updateSQL = "ALTER TABLE  `".$prefix."styles` ADD `brewStyleReqSpec` TINYINT(1) NULL DEFAULT NULL COMMENT 'Does the style require special ingredients be input? 1=yes 0=no';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '0'";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

// Designate all BJCP styles that require special ingredients
$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 21;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 59;";
mysql_select_db($database, $brewing);

mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 65;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 74;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 75;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 76;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 78;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 79;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 80;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 86;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 87;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 89;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 94;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 95;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 96;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 97;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 98;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());

$output .=  "<li>Styles table updated.</li>";


// -----------------------------------------------------------
// Alter Tables: archived brewing tables
// -----------------------------------------------------------

$query_archive_current = "SELECT archiveSuffix FROM $archive_db_table";
$archive_current = mysql_query($query_archive_current, $brewing) or die(mysql_error());
$row_archive_current = mysql_fetch_assoc($archive_current);
$totalRows_archive_current = mysql_num_rows($archive_current);

if ($totalRows_archive_current > 0) {
	
	do { $a_current[] = $row_archive_current['archiveSuffix']; } while ($row_archive_current = mysql_fetch_assoc($archive_current));
	
	foreach ($a_current as $suffix_current) {
		if (strpos($suffix_current,'_') !== false) $suffix_current = $suffix_current;
		//if (substr($suffix_current,0,1) == '_') !== false) $suffix_current = "_".$suffix_current;
		else $suffix_current = "_".$suffix_current;
		
		$updateSQL = "ALTER TABLE  `".$prefix."judging_scores".$suffix_current."` ADD `scoreMiniBOS` INT(4) NULL DEFAULT NULL COMMENT 'Did the entry go to the MiniBOS? 1=Yes, 0=No';";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
		
		$updateSQL = "ALTER TABLE  `".$prefix."judging_scores".$suffix_current."` CHANGE  `scoreEntry`  `scoreEntry` DECIMAL( 11, 2 ) NULL DEFAULT NULL COMMENT  'Numerical score assigned by judges';";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
		
		$updateSQL = "ALTER TABLE `".$prefix."brewing".$suffix_current."` ADD `brewBoxNum` VARCHAR(10) NULL DEFAULT NULL COMMENT 'The box where the entry is located after sorting';";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewer".$suffix_current."` ADD `brewerDropOff` INT(4) NULL DEFAULT NULL COMMENT 'Location where brewer will drop off their entries; 0=shipping or relational to dropoff table';";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error()); 
		
		
		for ($i=10; $i <= 20; $i++) {
	
			$one_less = ($i - 1);
			
			// Grains
			
			$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewGrain".$i."` VARCHAR(100) NULL DEFAULT NULL AFTER `brewGrain".$one_less."`;";
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			
			$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewGrain".$i."Weight` VARCHAR(10) NULL DEFAULT NULL AFTER `brewGrain".$one_less."Weight`;";
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			
			$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewGrain".$i."Use` VARCHAR(25) NULL DEFAULT NULL AFTER `brewGrain".$one_less."Use`;";
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			
			// Additions, Adjucnts, etc.
			
			$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewAddition".$i."` VARCHAR(100) NULL DEFAULT NULL AFTER `brewAddition".$one_less."`;";
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			
			$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewAddition".$i."Amt` VARCHAR(25) NULL DEFAULT NULL AFTER `brewAddition".$one_less."Amt`;";
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			
			$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewAddition".$i."Use` VARCHAR(25) NULL DEFAULT NULL AFTER `brewAddition".$one_less."Use`;";
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			
			// Hops
			
			$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewHops".$i."` VARCHAR(100) NULL DEFAULT NULL AFTER `brewHops".$one_less."`;";
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			
			$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewHops".$i."Weight` VARCHAR(10) NULL DEFAULT NULL AFTER `brewHops".$one_less."Weight`;";
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			
			$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewHops".$i."Use` VARCHAR(25) NULL DEFAULT NULL AFTER `brewHops".$one_less."Use`;";
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			
			$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewHops".$i."IBU` VARCHAR(6) NULL DEFAULT NULL AFTER `brewHops".$one_less."IBU`;";
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			
			$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewHops".$i."Time` VARCHAR(25) NULL DEFAULT NULL AFTER `brewHops".$one_less."Time`;";
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			
			$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewHops".$i."Type` VARCHAR(25) NULL DEFAULT NULL AFTER `brewHops".$one_less."Type`;";
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			
			$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewHops".$i."Form` VARCHAR(25) NULL DEFAULT NULL AFTER `brewHops".$one_less."Form`;";
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			
		}
		
		for ($i=6; $i <= 10; $i++) {
			
			$one_less = ($i - 1);
			
			$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewMashStep".$i."Name` VARCHAR(100) NULL DEFAULT NULL AFTER `brewMashStep".$one_less."Name`;";
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			
			$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewMashStep".$i."Temp` VARCHAR(10) NULL DEFAULT NULL AFTER `brewMashStep".$one_less."Temp`;";
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			
			$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewMashStep".$i."Time` VARCHAR(10) NULL DEFAULT NULL AFTER `brewMashStep".$one_less."Time`;";
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
			
		}
		
	}
	
}

$output .=  "<li>All archive entry data updated.</li>";

?>