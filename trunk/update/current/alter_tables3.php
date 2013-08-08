<?php 
// -----------------------------------------------------------
// Alter Table
// Version 1.3.0.0
//   Add/change table rows for expanded functions.
//   The "prefsPayToPrint" is a function requested by the AHA
//   for their national competition software.
// -----------------------------------------------------------



// Update character counts for ingredient columns to keep under 65000 threshold

for ($i=1; $i <= 9; $i++) {
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewGrain".$i."` `brewGrain".$i."` VARCHAR(100) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewAddition".$i."` `brewAddition".$i."` VARCHAR(100) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewAddition".$i."Amt` `brewAddition".$i."Amt` VARCHAR(10) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewHops".$i."` `brewHops".$i."` VARCHAR(100) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewHops".$i."IBU` `brewHops".$i."IBU` VARCHAR(10) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";

	$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewHops".$i."Time` `brewHops".$i."Time` VARCHAR(10) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewHops".$i."Use` `brewHops".$i."Use` VARCHAR(10) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewHops".$i."Type` `brewHops".$i."Type` VARCHAR(10) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewHops".$i."Form` `brewHops".$i."Form` VARCHAR(10) NULL DEFAULT NULL;";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
		
}

if (!NHC) {
	
	// Add brewBoxNum column
	
	$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewBoxNum` VARCHAR(10) NULL DEFAULT NULL COMMENT 'The box where the entry is located after sorting';";
	mysql_select_db($database, $brewing);
	mysql_real_escape_string($updateSQL);
	$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
	
	// Add additional ingredient columns
		
	for ($i=10; $i <= 20; $i++) {
		
		$one_less = ($i - 1);
		
		// Grains
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewGrain".$i."` VARCHAR(100) NULL DEFAULT NULL AFTER `brewGrain".$one_less."`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewGrain".$i."Weight` VARCHAR(10) NULL DEFAULT NULL AFTER `brewGrain".$one_less."Weight`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewGrain".$i."Use` VARCHAR(25) NULL DEFAULT NULL AFTER `brewGrain".$one_less."Use`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
		
		// Additions, Adjucnts, etc.
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewAddition".$i."` VARCHAR(100) NULL DEFAULT NULL AFTER `brewAddition".$one_less."`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewAddition".$i."Amt` VARCHAR(25) NULL DEFAULT NULL AFTER `brewAddition".$one_less."Amt`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewAddition".$i."Use` VARCHAR(25) NULL DEFAULT NULL AFTER `brewAddition".$one_less."Use`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
		
		// Hops
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewHops".$i."` VARCHAR(100) NULL DEFAULT NULL AFTER `brewHops".$one_less."`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewHops".$i."Weight` VARCHAR(10) NULL DEFAULT NULL AFTER `brewHops".$one_less."Weight`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewHops".$i."Use` VARCHAR(10) NULL DEFAULT NULL AFTER `brewHops".$one_less."Use`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewHops".$i."IBU` VARCHAR(6) NULL DEFAULT NULL AFTER `brewHops".$one_less."IBU`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewHops".$i."Time` VARCHAR(10) NULL DEFAULT NULL AFTER `brewHops".$one_less."Time`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewHops".$i."Type` VARCHAR(10) NULL DEFAULT NULL AFTER `brewHops".$one_less."Type`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewHops".$i."Form` VARCHAR(10) NULL DEFAULT NULL AFTER `brewHops".$one_less."Form`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
		
	}
	
	// Add additional mash step columns
	
	for ($i=1; $i <= 5; $i++) {
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` CHANGE  `brewMashStep".$i."Name` `brewMashStep".$i."Name` VARCHAR(100) NULL DEFAULT NULL;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
	}
	
	for ($i=6; $i <= 10; $i++) {
		
		$one_less = ($i - 1);
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewMashStep".$i."Name` VARCHAR(100) NULL DEFAULT NULL AFTER `brewMashStep".$one_less."Name`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewMashStep".$i."Temp` CHAR(3) NULL DEFAULT NULL AFTER `brewMashStep".$one_less."Temp`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
		
		$updateSQL = "ALTER TABLE  `".$prefix."brewing` ADD `brewMashStep".$i."Time` CHAR(3) NULL DEFAULT NULL AFTER `brewMashStep".$one_less."Time`;";
		mysql_select_db($database, $brewing);
		mysql_real_escape_string($updateSQL);
		$result = mysql_query($updateSQL, $brewing) or die(mysql_error());
//echo $updateSQL."<br>";
		
	}

} // end if (!NHC)

$output .=  "<li>Brewing table updated.</li>";



?>