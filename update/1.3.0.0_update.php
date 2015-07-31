<?php 
$output .= "<h2>Version 1.3.0.0, 1.3.0.1, etc...</h2>";
$output .= "<ul>";


// -----------------------------------------------------------
// Alter Table
// Version 1.3.0.0
//   Add/change table rows for expanded functions.
//   The "prefsPayToPrint" is a function requested by the AHA
//   for their national competition software.
// -----------------------------------------------------------

// -----------------------------------------------------------
// Create Tables
// -----------------------------------------------------------

if (!NHC) {
	// -----------------------------------------------------------
	// Create Table: staff
	//   Table to house information about staff.
	// -----------------------------------------------------------
		
		$updateSQL = "CREATE TABLE IF NOT EXISTS `$staff_db_table` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `uid` int(11) DEFAULT NULL COMMENT 'user''s id from user table',
		  `staff_judge` tinyint(2) DEFAULT '0' COMMENT '0=no; 1=yes',
		  `staff_judge_bos` tinyint(2) DEFAULT '0' COMMENT '0=no; 1=yes',
		  `staff_steward` tinyint(2) DEFAULT '0' COMMENT '0=no; 1=yes',
		  `staff_organizer` tinyint(2) DEFAULT '0' COMMENT '0=no; 1=yes',
		  `staff_staff` tinyint(2) DEFAULT '0' COMMENT '0=no; 1=yes',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing); 
		//echo $updateSQL."<br>";
		$output .= "<li>Staff table created.</li>";
	
	// -----------------------------------------------------------
	// Create Table: mods
	//   Table to house information about custom module files.
	// -----------------------------------------------------------
	
	
	
		$updateSQL = "CREATE TABLE IF NOT EXISTS `$mods_db_table` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `mod_name` varchar(255) DEFAULT NULL COMMENT 'Name of the custom module',
			  `mod_type` tinyint(2) DEFAULT NULL COMMENT 'Type of module: 0=informational 1=report 2=export 3=other',
			  `mod_extend_function` tinyint(2) DEFAULT NULL COMMENT 'If the custom module extends a core function. 0=all 1=home 2=rules 3=volunteer 4=sponsors 5=contact 6=register 7=pay 8=list 9=admin',
			  `mod_extend_function_admin` varchar(255) DEFAULT NULL COMMENT 'If the custom module extends an admin function (9 in mod_extend_function). Keys off of the go= variable.',
			  `mod_filename` varchar(255) DEFAULT NULL COMMENT 'File name of the custom module',
			  `mod_description` text COMMENT 'Short description of the custom module',
			  `mod_permission` tinyint(1) DEFAULT NULL COMMENT 'Who has permission to view the module. 0=uber-admin 1=admin 2=all',
			  `mod_rank` int(3) DEFAULT NULL COMMENT 'Rank order of the mod on the admin mods list',
			  `mod_display_rank` tinyint(1) DEFAULT NULL COMMENT '0=normal 1=above default content',
			  `mod_enable` tinyint(1) DEFAULT NULL COMMENT '0=no 1=yes',
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing); 
		//echo $updateSQL."<br>";
	
	$output .=  "<li>Custom Modules table created.</li>";
	
} // end if (!NHC)

// -----------------------------------------------------------
// Alter Tables: Preferences
// -----------------------------------------------------------

if (!NHC) {
	
	$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsUserEntryLimit`  VARCHAR(4) NULL DEFAULT NULL COMMENT 'Numeric limit of entries for each user';";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";
	
	$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsUserSubCatLimit` VARCHAR(4) NULL DEFAULT NULL COMMENT 'Numeric limit of entries for each user per subcategory';";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>"; 
	
	$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsUSCLEx` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Array of exceptions corresponding to id in styles table';";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>"; 
	
	$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsUSCLExLimit` VARCHAR(4) NULL DEFAULT NULL COMMENT 'Numeric limit of entries for each user per subcategory that has been excepted';";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>"; 
	
	$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsPayToPrint`  CHAR(1) NULL DEFAULT NULL COMMENT 'Do users need to pay before printing entry paperwork?';";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>"; 
	
	$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsHideRecipe` CHAR(1) NULL DEFAULT NULL COMMENT 'Hide the recipe (optional) sections on the add/edit entry form?';";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>"; 
	
	$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsUseMods` CHAR(1) NULL DEFAULT NULL COMMENT 'Use the custom modules function (advanced users)';";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>"; 
	
	$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD `prefsSEF` CHAR(1) NULL DEFAULT NULL COMMENT 'Use search engine friendly URLs.';";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>"; 

}

$updateSQL = "ALTER TABLE  `".$prefix."preferences` ADD  `prefsSpecialCharLimit` INT(3) NULL DEFAULT NULL COMMENT 'Character limit for special ingredients field';";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>"; 

if (NHC) $updateSQL = "UPDATE ".$prefix."preferences SET prefsPayToPrint='Y', prefsHideRecipe='Y', prefsUseMods='Y', prefsSEF='N', prefsSpecialCharLimit='50' WHERE id='1'";
else $updateSQL = "UPDATE ".$prefix."preferences SET prefsPayToPrint='N', prefsHideRecipe='N', prefsUseMods='N', prefsSEF='N', prefsSpecialCharLimit='50' WHERE id='1'"; 
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>"; 
			
$output .=  "<li>Site Preferences table updated.</li>";

// -----------------------------------------------------------
// Alter Tables: Brewer
// -----------------------------------------------------------

if (!NHC) {
	$updateSQL = "ALTER TABLE  `".$prefix."brewer` ADD `brewerDropOff` INT(4) NULL DEFAULT NULL COMMENT 'Location where brewer will drop off their entries; 0=shipping or relational to dropoff table';";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";
	
	$updateSQL = "UPDATE `".$prefix."brewer` SET `brewerDropOff` = '0';";
	mysql_select_db($database, $brewing); 
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
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
	$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";
	
	$updateSQL = "UPDATE `".$prefix."judging_scores` SET `scoreMiniBOS` = '0';";
	mysql_select_db($database, $brewing); 
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";
	
	$updateSQL = "ALTER TABLE  `".$prefix."judging_scores` CHANGE  `scoreEntry`  `scoreEntry` FLOAT NULL DEFAULT NULL COMMENT  'Numerical score assigned by judges';";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>"; 
	
	$output .=  "<li>Judging Scores table updated.</li>"; 

}

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

// -----------------------------------------------------------
// Alter Table
// Version 1.3.0.0
//   Add/change table rows for expanded functions.
//   The "prefsPayToPrint" is a function requested by the AHA
//   for their national competition software.
// -----------------------------------------------------------



// Update character counts for ingredient columns to keep under 65000 threshold

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewBoilHours` `brewBoilHours` VARCHAR(5) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewBoilMins` `brewBoilMins` VARCHAR(5) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewOG` `brewOG` VARCHAR(10) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewFG` `brewFG` VARCHAR(10) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewPrimary` `brewPrimary` VARCHAR(10) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewPrimaryTemp` `brewPrimaryTemp` VARCHAR(10) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewSecondary` `brewSecondary` VARCHAR(10) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewSecondaryTemp` `brewSecondaryTemp` VARCHAR(10) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewOther` `brewOther` VARCHAR(10) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewOtherTemp` `brewOtherTemp` VARCHAR(10) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewCarbonationVol` `brewCarbonationVol` VARCHAR(10) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewYeast` `brewYeast` VARCHAR(100) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewYeastMan` `brewYeastMan` VARCHAR(100) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewYeastForm` `brewYeastForm` VARCHAR(10) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewYeastType` `brewYeastType` VARCHAR(10) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewYeastAmount` `brewYeastAmount` VARCHAR(25) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewBrewerID` `brewBrewerID` VARCHAR(8) NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewFinings` `brewFinings` TEXT NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewWaterNotes` `brewWaterNotes` TEXT NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewComments` `brewComments` TEXT NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewCarbonationNotes` `brewCarbonationNotes` TEXT NULL DEFAULT NULL;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);

for ($i=1; $i <= 5; $i++) {
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewExtract".$i."Use` `brewExtract".$i."Use` VARCHAR(10) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
	//echo $updateSQL."<br>";
	
}

for ($i=1; $i <= 9; $i++) {
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewGrain".$i."` `brewGrain".$i."` VARCHAR(100) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
	//echo $updateSQL."<br>";
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewGrain".$i."Use` `brewGrain".$i."Use` VARCHAR(10) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
	//echo $updateSQL."<br>";

	$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewGrain".$i."Weight` `brewGrain".$i."Weight` VARCHAR(10) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
	//echo $updateSQL."<br>";
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewAddition".$i."` `brewAddition".$i."` VARCHAR(100) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
	//echo $updateSQL."<br>";
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewAddition".$i."Amt` `brewAddition".$i."Amt` VARCHAR(10) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
	//echo $updateSQL."<br>";
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewAddition".$i."Use` `brewAddition".$i."Use` VARCHAR(10) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
	//echo $updateSQL."<br>";
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewHops".$i."` `brewHops".$i."` VARCHAR(100) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
	//echo $updateSQL."<br>";
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewHops".$i."IBU` `brewHops".$i."IBU` VARCHAR(10) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
	//echo $updateSQL."<br>";

	$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewHops".$i."Time` `brewHops".$i."Time` VARCHAR(10) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
	//echo $updateSQL."<br>";
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewHops".$i."Use` `brewHops".$i."Use` VARCHAR(10) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
	//echo $updateSQL."<br>";
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewHops".$i."Type` `brewHops".$i."Type` VARCHAR(10) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
	//echo $updateSQL."<br>";
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewHops".$i."Form` `brewHops".$i."Form` VARCHAR(10) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
	//echo $updateSQL."<br>";
		
}

if (!NHC) {
	
	// Add brewBoxNum column
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewBoxNum` VARCHAR(10) NULL DEFAULT NULL COMMENT 'The box where the entry is located after sorting';";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing);
	//echo $updateSQL."<br>";
	
	// Add additional ingredient columns
		
	for ($i=10; $i <= 20; $i++) {
		
		$one_less = ($i - 1);
		
		// Grains
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewGrain".$i."` VARCHAR(100) NULL DEFAULT NULL AFTER `brewGrain".$one_less."`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing);
		//echo $updateSQL."<br>";
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewGrain".$i."Weight` VARCHAR(10) NULL DEFAULT NULL AFTER `brewGrain".$one_less."Weight`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing);
		//echo $updateSQL."<br>";
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewGrain".$i."Use` VARCHAR(25) NULL DEFAULT NULL AFTER `brewGrain".$one_less."Use`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing);
		//echo $updateSQL."<br>";
		
		// Additions, Adjucnts, etc.
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewAddition".$i."` VARCHAR(100) NULL DEFAULT NULL AFTER `brewAddition".$one_less."`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing);
		//echo $updateSQL."<br>";
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewAddition".$i."Amt` VARCHAR(25) NULL DEFAULT NULL AFTER `brewAddition".$one_less."Amt`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing);
		//echo $updateSQL."<br>";
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewAddition".$i."Use` VARCHAR(25) NULL DEFAULT NULL AFTER `brewAddition".$one_less."Use`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing);
		//echo $updateSQL."<br>";
		
		// Hops
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewHops".$i."` VARCHAR(100) NULL DEFAULT NULL AFTER `brewHops".$one_less."`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing);
		//echo $updateSQL."<br>";
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewHops".$i."Weight` VARCHAR(10) NULL DEFAULT NULL AFTER `brewHops".$one_less."Weight`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing);
		//echo $updateSQL."<br>";
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewHops".$i."Use` VARCHAR(10) NULL DEFAULT NULL AFTER `brewHops".$one_less."Use`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing);
		//echo $updateSQL."<br>";
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewHops".$i."IBU` VARCHAR(6) NULL DEFAULT NULL AFTER `brewHops".$one_less."IBU`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing);
		//echo $updateSQL."<br>";
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewHops".$i."Time` VARCHAR(10) NULL DEFAULT NULL AFTER `brewHops".$one_less."Time`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing);
		//echo $updateSQL."<br>";
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewHops".$i."Type` VARCHAR(10) NULL DEFAULT NULL AFTER `brewHops".$one_less."Type`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing);
		//echo $updateSQL."<br>";
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewHops".$i."Form` VARCHAR(10) NULL DEFAULT NULL AFTER `brewHops".$one_less."Form`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing);
		//echo $updateSQL."<br>";
		
	}
	
	// Add additional mash step columns
	
	for ($i=1; $i <= 5; $i++) {
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewMashStep".$i."Name` `brewMashStep".$i."Name` VARCHAR(100) NULL DEFAULT NULL;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing);
		//echo $updateSQL."<br>";
	}
	
	for ($i=6; $i <= 10; $i++) {
		
		$one_less = ($i - 1);
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewMashStep".$i."Name` VARCHAR(100) NULL DEFAULT NULL AFTER `brewMashStep".$one_less."Name`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing);
		//echo $updateSQL."<br>";
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewMashStep".$i."Temp` CHAR(3) NULL DEFAULT NULL AFTER `brewMashStep".$one_less."Temp`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing);
		//echo $updateSQL."<br>";
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewMashStep".$i."Time` CHAR(3) NULL DEFAULT NULL AFTER `brewMashStep".$one_less."Time`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing);
		//echo $updateSQL."<br>";
		
	}

} // end if (!NHC)


// -----------------------------------------------------------
// Data Updates: Brewing Table
//   Convert brewPaid, brewWinner, and brewReceived to
//   boolean values.
// -----------------------------------------------------------



if ($totalRows_log > 0) {

	do {
		if ($row_log['brewPaid'] == "Y") $brewPaid = "1"; else $brewPaid = "0";
		if ($row_log['brewWinner'] == "Y") $brewWinner = "1"; else $brewWinner = "0";
		if ($row_log['brewReceived'] == "Y") $brewReceived = "1"; else $brewReceived = "0";
		
		
		$updateSQL = sprintf("UPDATE ".$prefix."brewing SET 
								 brewPaid='%s',
								 brewWinner='%s',
								 brewReceived='%s',
								 brewConfirmed='%s',
								 brewUpdated=%s
								 WHERE id='%s';",
								 $brewPaid,
								 $brewWinner,
								 $brewReceived,
								 "1",
								 "NOW()",
								 $row_log['id']);
			mysql_select_db($database, $brewing);
			$result = mysql_query($updateSQL, $brewing); 	
	} while ($row_log = mysql_fetch_assoc($log));
	$output .= "<li>All entry data updated.</li>";
}

$updateSQL = "ALTER TABLE  `".$prefix."brewing` 
CHANGE  `brewPaid`  `brewPaid` TINYINT( 1 ) NULL DEFAULT NULL COMMENT '1=true; 0=false';";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing); 
//$output .= $updateSQL."<br>";

$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewReceived`  `brewReceived` TINYINT( 1 ) NULL DEFAULT NULL COMMENT '1=true; 0=false';";
mysql_select_db($database, $brewing);
$result = mysql_query($updateSQL, $brewing);  

//$output .= $updateSQL."<br>";

$output .= "<li>Conversion of paid and received rows to new schema in brewing table completed.</li>";

$output .=  "<li>Brewing table updated.</li>";

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
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '0'";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

// Designate all BJCP styles that require special ingredients
$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 21;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 59;";
mysql_select_db($database, $brewing);

mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";
$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 65;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 74;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 75;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 76;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 78;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 79;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 80;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 86;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 87;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 89;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 94;";

mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 95;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 96;";
mysql_select_db($database, $brewing);
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 97;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

$updateSQL = "UPDATE `".$prefix."styles` SET `brewStyleReqSpec` = '1' WHERE `id` = 98;";
mysql_select_db($database, $brewing); 
mysql_real_escape_string($updateSQL);
$result = mysql_query($updateSQL, $brewing);
//echo $updateSQL."<br>";

$output .=  "<li>Styles table updated.</li>";

// -----------------------------------------------------------
// Alter Table
// Version 1.3.0.0
//   Add/change table rows for expanded functions.
//   The "prefsPayToPrint" is a function requested by the AHA
//   for their national competition software.
// -----------------------------------------------------------

// -----------------------------------------------------------
// Alter Tables: archived brewing tables
// -----------------------------------------------------------

if (!NHC) {
		
	$query_archive_current = "SELECT archiveSuffix FROM $archive_db_table";
	$archive_current = mysql_query($query_archive_current, $brewing);
	$row_archive_current = mysql_fetch_assoc($archive_current);
	$totalRows_archive_current = mysql_num_rows($archive_current);
	
	if ($totalRows_archive_current > 0) {
		
		do { $a_current[] = $row_archive_current['archiveSuffix']; } while ($row_archive_current = mysql_fetch_assoc($archive_current));
		
		foreach ($a_current as $suffix_current) {
			//if (strpos($suffix_current,'_') !== false) $suffix_current = $suffix_current;
			//if (substr($suffix_current,0,1) == '_') !== false) $suffix_current = "_".$suffix_current;
			//else
			$suffix_current = "_".$suffix_current;
			
			if (check_setup($prefix."judging_scores".$suffix_current,$database)) {
				$updateSQL = "ALTER TABLE  `".$prefix."judging_scores".$suffix_current."` ADD `scoreMiniBOS` INT(4) NULL DEFAULT NULL COMMENT 'Did the entry go to the MiniBOS? 1=Yes, 0=No';";
				mysql_select_db($database, $brewing);
				mysql_real_escape_string($updateSQL);
				$result = mysql_query($updateSQL, $brewing);
				//echo $updateSQL."<br>"; 
			
			$updateSQL = "ALTER TABLE  `".$prefix."judging_scores".$suffix_current."` CHANGE  `scoreEntry`  `scoreEntry` DECIMAL( 11, 2 ) NULL DEFAULT NULL COMMENT  'Numerical score assigned by judges';";
			mysql_select_db($database, $brewing);
			mysql_real_escape_string($updateSQL);
			$result = mysql_query($updateSQL, $brewing);
			//echo $updateSQL."<br>"; 
			}
			
			if (check_setup($prefix."brewer".$suffix_current,$database)) {
				$updateSQL = "ALTER TABLE  `".$prefix."brewer".$suffix_current."` ADD `brewerDropOff` INT(4) NULL DEFAULT NULL COMMENT 'Location where brewer will drop off their entries; 0=shipping or relational to dropoff table';";
				mysql_select_db($database, $brewing);
				mysql_real_escape_string($updateSQL);
				$result = mysql_query($updateSQL, $brewing);
				//echo $updateSQL."<br>";
			}
			
			if (check_setup($prefix."brewing".$suffix_current,$database)) {
				
				$updateSQL = "ALTER TABLE `".$prefix."brewing".$suffix_current."` ADD `brewBoxNum` VARCHAR(10) NULL DEFAULT NULL COMMENT 'The box where the entry is located after sorting';";
				mysql_select_db($database, $brewing);
				mysql_real_escape_string($updateSQL);
				$result = mysql_query($updateSQL, $brewing);
				//echo $updateSQL."<br>";  
			
				// Update character counts for ingredient columns to keep under 65000 threshold
				
				
		
				$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` CHANGE  `brewMead1` `brewMead1` VARCHAR(25) NULL DEFAULT NULL;";
				mysql_select_db($database, $brewing);
				mysql_real_escape_string($updateSQL);
				$result = mysql_query($updateSQL, $brewing);
				//echo $updateSQL."<br>"; 
				
				$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` CHANGE  `brewMead2` `brewMead2` VARCHAR(25) NULL DEFAULT NULL;";
				mysql_select_db($database, $brewing);
				mysql_real_escape_string($updateSQL);
				$result = mysql_query($updateSQL, $brewing);
				//echo $updateSQL."<br>"; 
				
				$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` CHANGE  `brewMead3` `brewMead3` VARCHAR(25) NULL DEFAULT NULL;";
				mysql_select_db($database, $brewing);
				mysql_real_escape_string($updateSQL);
				$result = mysql_query($updateSQL, $brewing);
				//echo $updateSQL."<br>"; 
				
				$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` CHANGE  `brewYeast` `brewYeast` VARCHAR(100) NULL DEFAULT NULL;";
				mysql_select_db($database, $brewing);
				mysql_real_escape_string($updateSQL);
				$result = mysql_query($updateSQL, $brewing);
				//echo $updateSQL."<br>"; 
				
				$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` CHANGE  `brewYeastMan` `brewYeastMan` VARCHAR(100) NULL DEFAULT NULL;";
				mysql_select_db($database, $brewing);
				mysql_real_escape_string($updateSQL);
				$result = mysql_query($updateSQL, $brewing);
				//echo $updateSQL."<br>"; 
				
				$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` CHANGE  `brewYeastForm` `brewYeastForm` VARCHAR(10) NULL DEFAULT NULL;";
				mysql_select_db($database, $brewing);
				mysql_real_escape_string($updateSQL);
				$result = mysql_query($updateSQL, $brewing);
				//echo $updateSQL."<br>"; 
				
				$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` CHANGE  `brewYeastType` `brewYeastType` VARCHAR(10) NULL DEFAULT NULL;";
				mysql_select_db($database, $brewing);
				mysql_real_escape_string($updateSQL);
				$result = mysql_query($updateSQL, $brewing);
				//echo $updateSQL."<br>"; 
				
				$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` CHANGE  `brewYeastNutrients` `brewYeastNutrients` text;";
				mysql_select_db($database, $brewing);
				mysql_real_escape_string($updateSQL);
				$result = mysql_query($updateSQL, $brewing);
				//echo $updateSQL."<br>"; 
				
				$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` CHANGE  `brewFinings` `brewFinings` text;";
				mysql_select_db($database, $brewing);
				mysql_real_escape_string($updateSQL);
				$result = mysql_query($updateSQL, $brewing);
				//echo $updateSQL."<br>"; 
				
				$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` CHANGE  `brewWaterNotes` `brewWaterNotes` text;";
				mysql_select_db($database, $brewing);
				mysql_real_escape_string($updateSQL);
				$result = mysql_query($updateSQL, $brewing);
				//echo $updateSQL."<br>"; 
				
				$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` CHANGE  `brewCarbonationMethod` `brewCarbonationMethod` CHAR(1) NULL DEFAULT NULL;";
				mysql_select_db($database, $brewing);
				mysql_real_escape_string($updateSQL);
				$result = mysql_query($updateSQL, $brewing);
				//echo $updateSQL."<br>";
				
				for ($i=1; $i <= 9; $i++) {
			
					$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` CHANGE  `brewGrain".$i."` `brewGrain".$i."` VARCHAR(100) NULL DEFAULT NULL;";
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result = mysql_query($updateSQL, $brewing);
					//echo $updateSQL."<br>";
					
					$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` CHANGE  `brewAddition".$i."` `brewAddition".$i."` VARCHAR(100) NULL DEFAULT NULL;";
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result = mysql_query($updateSQL, $brewing);
					//echo $updateSQL."<br>";
					
					$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` CHANGE  `brewAddition".$i."Amt` `brewAddition".$i."Amt` VARCHAR(10) NULL DEFAULT NULL;";
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result = mysql_query($updateSQL, $brewing);
					//echo $updateSQL."<br>";
					
					$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` CHANGE  `brewHops".$i."` `brewHops".$i."` VARCHAR(100) NULL DEFAULT NULL;";
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result = mysql_query($updateSQL, $brewing);
					//echo $updateSQL."<br>";
					
					$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` CHANGE  `brewHops".$i."IBU` `brewHops".$i."IBU` VARCHAR(10) NULL DEFAULT NULL;";
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result = mysql_query($updateSQL, $brewing);
					//echo $updateSQL."<br>";
				
					$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` CHANGE  `brewHops".$i."Time` `brewHops".$i."Time` VARCHAR(10) NULL DEFAULT NULL;";
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result = mysql_query($updateSQL, $brewing);
					//echo $updateSQL."<br>";
					
					$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` CHANGE  `brewHops".$i."Use` `brewHops".$i."Use` VARCHAR(10) NULL DEFAULT NULL;";
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result = mysql_query($updateSQL, $brewing);
					//echo $updateSQL."<br>";
					
					$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` CHANGE  `brewHops".$i."Type` `brewHops".$i."Type` VARCHAR(10) NULL DEFAULT NULL;";
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result = mysql_query($updateSQL, $brewing);
					//echo $updateSQL."<br>";
					
					$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` CHANGE  `brewHops".$i."Form` `brewHops".$i."Form` VARCHAR(10) NULL DEFAULT NULL;";
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result = mysql_query($updateSQL, $brewing);
					//echo $updateSQL."<br>";
						
				}
				
				for ($i=10; $i <= 20; $i++) {
			
					$one_less = ($i - 1);
					
					// Grains
					
					$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewGrain".$i."` VARCHAR(100) NULL DEFAULT NULL AFTER `brewGrain".$one_less."`;";
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result = mysql_query($updateSQL, $brewing);
					//echo $updateSQL."<br>";
					
					$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewGrain".$i."Weight` VARCHAR(10) NULL DEFAULT NULL AFTER `brewGrain".$one_less."Weight`;";
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result = mysql_query($updateSQL, $brewing);
					//echo $updateSQL."<br>";
					
					$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewGrain".$i."Use` VARCHAR(25) NULL DEFAULT NULL AFTER `brewGrain".$one_less."Use`;";
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result = mysql_query($updateSQL, $brewing);
					//echo $updateSQL."<br>";
					
					// Additions, Adjucnts, etc.
					
					$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewAddition".$i."` VARCHAR(100) NULL DEFAULT NULL AFTER `brewAddition".$one_less."`;";
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result = mysql_query($updateSQL, $brewing);
					//echo $updateSQL."<br>";
					
					$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewAddition".$i."Amt` VARCHAR(25) NULL DEFAULT NULL AFTER `brewAddition".$one_less."Amt`;";
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result = mysql_query($updateSQL, $brewing);
					//echo $updateSQL."<br>";
					
					$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewAddition".$i."Use` VARCHAR(25) NULL DEFAULT NULL AFTER `brewAddition".$one_less."Use`;";
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result = mysql_query($updateSQL, $brewing);
					//echo $updateSQL."<br>";
					
					// Hops
					
					$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewHops".$i."` VARCHAR(100) NULL DEFAULT NULL AFTER `brewHops".$one_less."`;";
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result = mysql_query($updateSQL, $brewing);
					//echo $updateSQL."<br>";
					
					$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewHops".$i."Weight` VARCHAR(10) NULL DEFAULT NULL AFTER `brewHops".$one_less."Weight`;";
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result = mysql_query($updateSQL, $brewing);
					//echo $updateSQL."<br>";
					
					$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewHops".$i."Use` VARCHAR(25) NULL DEFAULT NULL AFTER `brewHops".$one_less."Use`;";
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result = mysql_query($updateSQL, $brewing);
					//echo $updateSQL."<br>";
					
					$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewHops".$i."IBU` VARCHAR(6) NULL DEFAULT NULL AFTER `brewHops".$one_less."IBU`;";
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result = mysql_query($updateSQL, $brewing);
					//echo $updateSQL."<br>";
					
					$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewHops".$i."Time` VARCHAR(25) NULL DEFAULT NULL AFTER `brewHops".$one_less."Time`;";
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result = mysql_query($updateSQL, $brewing);
					//echo $updateSQL."<br>";
					
					$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewHops".$i."Type` VARCHAR(25) NULL DEFAULT NULL AFTER `brewHops".$one_less."Type`;";
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result = mysql_query($updateSQL, $brewing);
					//echo $updateSQL."<br>";
					
					$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewHops".$i."Form` VARCHAR(25) NULL DEFAULT NULL AFTER `brewHops".$one_less."Form`;";
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result = mysql_query($updateSQL, $brewing);
					//echo $updateSQL."<br>";
					
					
				}
				
				for ($i=1; $i <= 5; $i++) {
					$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` CHANGE  `brewMashStep".$i."Name` `brewMashStep".$i."Name` VARCHAR(100) NULL DEFAULT NULL;";
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result = mysql_query($updateSQL, $brewing);
					//echo $updateSQL."<br>";
				}
				
				for ($i=6; $i <= 10; $i++) {
					
					$one_less = ($i - 1);
					
					$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewMashStep".$i."Name` VARCHAR(100) NULL DEFAULT NULL AFTER `brewMashStep".$one_less."Name`;";
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result = mysql_query($updateSQL, $brewing);
					//echo $updateSQL."<br>";
					
					$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewMashStep".$i."Temp` VARCHAR(10) NULL DEFAULT NULL AFTER `brewMashStep".$one_less."Temp`;";
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result = mysql_query($updateSQL, $brewing);
					//echo $updateSQL."<br>";
					
					$updateSQL = "ALTER TABLE  `".$prefix."brewing".$suffix_current."` ADD `brewMashStep".$i."Time` VARCHAR(10) NULL DEFAULT NULL AFTER `brewMashStep".$one_less."Time`;";
					mysql_select_db($database, $brewing);
					mysql_real_escape_string($updateSQL);
					$result = mysql_query($updateSQL, $brewing);
					//echo $updateSQL."<br>";
					
				}
				
			} // end if brewing table exists	
			
		}
		
	}
	
	$output .=  "<li>All archive entry data updated.</li>";
} // end if (!NHC)



$output .= "</ul>";
?>