<?php
// -----------------------------------------------------------
// Alter Tables
// Version 2.1.0.0
// -----------------------------------------------------------

/*

// -----------------------------------------------------------
// Alter Table: XXX
// XXX
// -----------------------------------------------------------

if (!check_update("sponsorEnable", $prefix."sponsors")) {
	
	$updateSQL = "ALTER TABLE `".$prefix."sponsors` ADD `sponsorEnable` TINYINT(1) NULL;";
	mysqli_select_db($connection,$database);
	mysqli_real_escape_string($connection,$updateSQL);
	$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));
	
	$output .=  "<li>XXX.</li>";
}


// -----------------------------------------------------------
// Alter Table: preferences
// Change selected preferences rows to boolean.
// -----------------------------------------------------------

// Custom function...
function convert_yes_no($input) {
	if ($input == "Y") $return = "1";
	elseif ($input == "N") $return = "0";
	else $return = "0";
	return $return;
}


// First, change the values currently in the table to 1 for Y and 0 for N

$query_preferences = sprintf("SELECT * FROM %s WHERE id=1",$prefix."preferences");
$preferences = mysqli_query($connection,$query_preferences) or die (mysqli_error($connection));
$row_preferences = mysqli_fetch_assoc($preferences);
$totalRows_preferences = mysqli_num_rows($user_preferences);

$updateSQL = sprintf("UPDATE %s SET 
`prefsCash`='%s', 
`prefsCheck`='%s', 
`prefsTransFee`='%s', 
`prefsCash`='%s', 
`prefsSponsors`='%s', 
`prefsSponsorLogos`='%s', 
`prefsDisplayWinners`='%s', 
`prefsDisplaySpecial`='%s', 
`prefsContact`='%s', 
`prefsPayToPrint`='%s', 
`prefsHideRecipe`='%s', 
`prefsUseMods`='%s', 
`prefsSEF`='%s'
WHERE `id`=1", 
$prefix."preferences",
convert_yes_no($row_preferences['prefsCash']), 
convert_yes_no($row_preferences['prefsCheck']), 
convert_yes_no($row_preferences['prefsTransFee']), 
convert_yes_no($row_preferences['prefsCash']), 
convert_yes_no($row_preferences['prefsSponsors']), 
convert_yes_no($row_preferences['prefsSponsorLogos']), 
convert_yes_no($row_preferences['prefsDisplayWinners']), 
convert_yes_no($row_preferences['prefsDisplaySpecial']),
convert_yes_no($row_preferences['prefsContact']), 
convert_yes_no($row_preferences['prefsPayToPrint']), 
convert_yes_no($row_preferences['prefsHideRecipe']), 
convert_yes_no($row_preferences['prefsUseMods']), 
convert_yes_no($row_preferences['prefsSEF'])
);
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

// Then alter the table rows to be TINYINT(1)
$updateSQL = sprintf("
ALTER TABLE `%s` 
CHANGE `prefsPaypal` `prefsPaypal` TINYINT(1) NULL DEFAULT NULL, 
CHANGE `prefsCash` `prefsCash` TINYINT(1) NULL DEFAULT NULL, 
CHANGE `prefsCheck` `prefsCheck` TINYINT(1) NULL DEFAULT NULL, 
CHANGE `prefsTransFee` `prefsTransFee` TINYINT(1) NULL DEFAULT NULL, 
CHANGE `prefsSponsors` `prefsSponsors` TINYINT(1) NULL DEFAULT NULL, 
CHANGE `prefsSponsorLogos` `prefsSponsorLogos` TINYINT(1) NULL DEFAULT NULL, 
CHANGE `prefsDisplayWinners` `prefsDisplayWinners` TINYINT(1) NULL DEFAULT NULL, 
CHANGE `prefsDisplaySpecial` `prefsDisplaySpecial` TINYINT(1) NULL DEFAULT NULL, 
CHANGE `prefsContact` `prefsContact` TINYINT(1) NULL DEFAULT NULL, 
CHANGE `prefsPayToPrint` `prefsPayToPrint` TINYINT(1) NULL DEFAULT NULL COMMENT 'Do users need to pay before printing entry paperwork?', 
CHANGE `prefsHideRecipe` `prefsHideRecipe` TINYINT(1) NULL DEFAULT NULL COMMENT 'Hide the recipe (optional) sections on the add/edit entry form?', 
CHANGE `prefsUseMods` `prefsUseMods` TINYINT(1) NULL DEFAULT NULL COMMENT 'Use the custom modules function (advanced users)', 
CHANGE `prefsSEF` `prefsSEF` TINYINT(1) NULL DEFAULT NULL COMMENT 'Use search engine friendly URLs.'", 
$prefix."preferences"
);
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$output .=  "<li>Preferences table updated to boolean values.</li>";

// -----------------------------------------------------------
// Alter Table: judging_preferences
// Change selected judging_preferences rows to boolean.
// -----------------------------------------------------------

// First, change the values currently in the table to 1 for Y and 0 for N
$query_judging_preferences = sprintf("SELECT jprefsQueued FROM %s WHERE id=1",$prefix."judging_preferences");
$judging_preferences = mysqli_query($connection,$query_judging_preferences) or die (mysqli_error($connection));
$row_judging_preferences = mysqli_fetch_assoc($judging_preferences);
$totalRows_judging_preferences = mysqli_num_rows($user_judging_preferences);

$updateSQL = sprintf("UPDATE %s SET `jprefsQueued`='%s' WHERE `id`=1", $prefix."judging_preferences", convert_yes_no($row_judging_preferences['jprefsQueued']));
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

// Then alter the table rows to be TINYINT(1)
$updateSQL = sprintf("ALTER TABLE `%s` CHANGE `jprefsQueued` `jprefsQueued` TINYINT(1) NULL DEFAULT NULL;", $prefix."preferences");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$output .=  "<li>Judging preferences table updated to boolean values.</li>";

*/

// -----------------------------------------------------------
// Alter Table: Preferences
// -----------------------------------------------------------

$updateSQL = sprintf("
ALTER TABLE `%s` 
ADD `prefsEntryLimitPaid` INT(4) NULL DEFAULT NULL,  
ADD `prefsEmailRegConfirm` TINYINT(1) NULL DEFAULT NULL;
",
$prefix."preferences");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$output .=  "<li>Preferences table updated.</li>";

// -----------------------------------------------------------
// Alter Table: Judging Preferences
// -----------------------------------------------------------

$updateSQL = sprintf("
ALTER TABLE `%s` 
ADD `jPrefsCapJudges` INT(3) NULL DEFAULT NULL,  
ADD `jPrefsCapStewards` INT(3) NULL DEFAULT NULL,  
ADD `jPrefsBottleNum` INT(3) NULL DEFAULT NULL;
",
$prefix."judging_preferences");
mysqli_select_db($connection,$database);
mysqli_real_escape_string($connection,$updateSQL);
$result = mysqli_query($connection,$updateSQL) or die (mysqli_error($connection));

$output .=  "<li>Judging preferences table updated.</li>";

?>