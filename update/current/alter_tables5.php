<?php 
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

?>