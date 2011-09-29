<?php 

/**
 * Module:      archive.inc.php
 * Description: This module takes the current database tables and renames them 
 *              for archiving purposes so that data is still available.
 */

require('../paths.php');
require(INCLUDES.'scrubber.inc.php');

session_start();

$suffix = strtr($_POST['archiveSuffix'], $space_remove);
mysql_select_db($database, $brewing);

$query_suffix_check = sprintf("SELECT COUNT(*) as 'count' FROM archive WHERE archiveSuffix = '%s'", $suffix);
$suffix_check = mysql_query($query_suffix_check, $brewing) or die(mysql_error());
$row_suffix_check = mysql_fetch_assoc($suffix_check);

if ($row_suffix_check['count'] > 0) { 
header("Location: ../index.php?section=admin&amp;go=archive&amp;msg=6");
} 

else {

// Gather current User's information from the current "users" AND current "brewer" tables and store in variables
mysql_select_db($database, $brewing);
$query_user = sprintf("SELECT * FROM users WHERE user_name = '%s'", $_SESSION["loginUsername"]);
$user = mysql_query($query_user, $brewing) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);

  $user_name = strtr($row_user['user_name'], $html_string);
  $password = $row_user['password'];
  $userLevel = $row_user['userLevel'];
  $userQuestion = strtr($row_user['userQuestion'], $html_string);
  $userQuestionAnswer = strtr($row_user['userQuestionAnswer'], $html_string);
  
$query_name = sprintf("SELECT * FROM brewer WHERE brewerEmail='%s'", $_SESSION["loginUsername"]);
$name = mysql_query($query_name, $brewing) or die(mysql_error());
$row_name = mysql_fetch_assoc($name);

  $brewerFirstName = strtr($row_name['brewerFirstName'],$html_string);
  $brewerLastName = strtr($row_name['brewerLastName'],$html_string);
  $brewerAddress = strtr($row_name['brewerAddress'],$html_string);
  $brewerCity = strtr($row_name['brewerCity'],$html_string);
  $brewerState = $row_name['brewerState'];
  $brewerZip = $row_name['brewerZip'];
  $brewerCountry = $row_name['brewerCountry'];
  $brewerPhone1 = $row_name['brewerPhone1'];
  $brewerPhone2 = $row_name['brewerPhone2'];
  $brewerClubs = $row_name['brewerClubs'];
  $brewerEmail = $row_name['brewerEmail'];
  $brewerNickname = $row_name['brewerNickname'];
  $brewerSteward = $row_name['brewerSteward'];
  $brewerJudge = $row_name['brewerJudge'];
  $brewerJudgeID = $row_name['brewerJudgeID'];
  $brewerJudgeRank = $row_name['brewerJudgeRank'];
  $brewerJudgeLikes = $row_name['brewerJudgeLikes'];
  $brewerJudgeDislikes = $row_name['brewerJudgeDislikes'];
  $brewerAHA = $row_name['brewerAHA'];


// Second, rename current "users", "brewer", "brewing" tables according to information gathered from the form
$rename_users = "RENAME TABLE ".$database.".users TO ".$database.".users_".$suffix.";";
//echo "<p>".$rename_users."</p>";
$result = mysql_query($rename_users, $brewing) or die(mysql_error());

$rename_brewer = "RENAME TABLE ".$database.".brewer  TO ".$database.".brewer_".$suffix.";";
//echo "<p>".$rename_brewer."</p>";
$result = mysql_query($rename_brewer, $brewing) or die(mysql_error());

$rename_brewing = "RENAME TABLE ".$database.".brewing  TO ".$database.".brewing_".$suffix.";";
//echo "<p>".$rename_brewing."</p>";
$result = mysql_query($rename_brewing, $brewing) or die(mysql_error());

$rename_sponsors = "RENAME TABLE ".$database.".sponsors  TO ".$database.".sponsors_".$suffix.";";
//echo "<p>".$rename_judging_sponsors."</p>";
$result = mysql_query($rename_sponsors , $brewing) or die(mysql_error());

$rename_judging_assignments = "RENAME TABLE ".$database.".judging_assignments  TO ".$database.".judging_assignments_".$suffix.";";
//echo "<p>".$rename_judging_assignments."</p>";
$result = mysql_query($rename_judging_assignments, $brewing) or die(mysql_error());

$rename_judging_flights = "RENAME TABLE ".$database.".judging_flights  TO ".$database.".judging_flights_".$suffix.";";
//echo "<p>".$rename_judging_flights."</p>";
$result = mysql_query($rename_judging_flights, $brewing) or die(mysql_error());

$rename_judging_scores = "RENAME TABLE ".$database.".judging_scores  TO ".$database.".judging_scores_".$suffix.";";
//echo "<p>".$rename_judging_scores."</p>";
$result = mysql_query($rename_judging_scores, $brewing) or die(mysql_error());

$rename_judging_scores_bos = "RENAME TABLE ".$database.".judging_scores_bos  TO ".$database.".judging_scores_bos_".$suffix.";";
//echo "<p>".$rename_judging_scores_bos."</p>";
$result = mysql_query($rename_judging_scores_bos, $brewing) or die(mysql_error());

$rename_judging_tables = "RENAME TABLE ".$database.".judging_tables  TO ".$database.".judging_tables_".$suffix.";";
//echo "<p>".$rename_judging_tables."</p>";
$result = mysql_query($rename_judging_tables, $brewing) or die(mysql_error());

$rename_style_types = "RENAME TABLE ".$database.".style_types  TO ".$database.".style_types_".$suffix.";";
//echo "<p>".$rename_style_types."</p>";
$result = mysql_query($rename_style_types, $brewing) or die(mysql_error());

// Third, insert a clean "users", "brewer", "brewing", and "sponsors" tables

$create_users = "
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `password` varchar(250) NOT NULL DEFAULT '',
  `userLevel` char(1) DEFAULT NULL,
  `userQuestion` varchar(255) DEFAULT NULL,
  `userQuestionAnswer` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
//echo "<p>".$create_users."</p>";
$result = mysql_query($create_users, $brewing) or die(mysql_error());

$create_brewer = "
CREATE TABLE IF NOT EXISTS `brewer` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `uid` int(8) DEFAULT NULL,
  `brewerFirstName` varchar(200) DEFAULT NULL,
  `brewerLastName` varchar(200) DEFAULT NULL,
  `brewerAddress` varchar(255) DEFAULT NULL,
  `brewerCity` varchar(255) DEFAULT NULL,
  `brewerState` varchar(255) DEFAULT NULL,
  `brewerZip` varchar(255) DEFAULT NULL,
  `brewerCountry` varchar(255) DEFAULT NULL,
  `brewerPhone1` varchar(255) DEFAULT NULL,
  `brewerPhone2` varchar(255) DEFAULT NULL,
  `brewerClubs` text,
  `brewerEmail` varchar(255) DEFAULT NULL,
  `brewerNickname` varchar(255) DEFAULT NULL,
  `brewerSteward` char(1) DEFAULT NULL,
  `brewerJudge` char(1) DEFAULT NULL,
  `brewerJudgeID` varchar(255) DEFAULT NULL,
  `brewerJudgeMead` char(1) DEFAULT NULL,
  `brewerJudgeMeadID` varchar(255) DEFAULT NULL,
  `brewerJudgeMeadRank` varchar(255) DEFAULT NULL,
  `brewerJudgeRank` varchar(255) DEFAULT NULL,
  `brewerJudgeLikes` text CHARACTER SET utf8 COLLATE utf8_bin,
  `brewerJudgeDislikes` text CHARACTER SET utf8 COLLATE utf8_bin,
  `brewerJudgeLocation` text CHARACTER SET utf8 COLLATE utf8_bin,
  `brewerStewardLocation` text CHARACTER SET utf8 COLLATE utf8_bin,
  `brewerJudgeAssignedLocation` text CHARACTER SET utf8 COLLATE utf8_bin,
  `brewerStewardAssignedLocation` text CHARACTER SET utf8 COLLATE utf8_bin,
  `brewerAssignment` char(1) DEFAULT NULL,
  `brewerAssignmentStaff` char(1) DEFAULT NULL,
  `brewerAHA` int(11) DEFAULT NULL,
  `brewerDiscount` char(1) DEFAULT NULL COMMENT 'Y or N if this participant receives a discount',
  `brewerJudgeBOS` char(1) DEFAULT NULL COMMENT 'Y if judged in BOS round',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
//echo "<p>".$create_brewer."</p>";
$result = mysql_query($create_brewer, $brewing) or die(mysql_error());

$create_brewing = "
CREATE TABLE IF NOT EXISTS `brewing` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `brewName` varchar(250) DEFAULT NULL,
  `brewStyle` varchar(250) DEFAULT NULL,
  `brewCategory` char(2) DEFAULT NULL,
  `brewCategorySort` char(2) DEFAULT NULL,
  `brewSubCategory` char(1) DEFAULT NULL,
  `brewBottleDate` date DEFAULT NULL,
  `brewDate` date DEFAULT NULL,
  `brewYield` varchar(10) DEFAULT NULL,
  `brewInfo` text,
  `brewMead1` varchar(255) DEFAULT NULL,
  `brewMead2` varchar(255) DEFAULT NULL,
  `brewMead3` varchar(255) DEFAULT NULL,
  `brewExtract1` varchar(100) DEFAULT NULL,
  `brewExtract1Weight` varchar(10) DEFAULT NULL,
  `brewExtract2` varchar(100) DEFAULT NULL,
  `brewExtract2Weight` varchar(10) DEFAULT NULL,
  `brewExtract3` varchar(100) DEFAULT NULL,
  `brewExtract3Weight` varchar(4) DEFAULT NULL,
  `brewExtract4` varchar(100) DEFAULT NULL,
  `brewExtract4Weight` varchar(10) DEFAULT NULL,
  `brewExtract5` varchar(100) DEFAULT NULL,
  `brewExtract5Weight` varchar(10) DEFAULT NULL,
  `brewGrain1` varchar(100) DEFAULT NULL,
  `brewGrain1Weight` varchar(10) DEFAULT NULL,
  `brewGrain2` varchar(100) DEFAULT NULL,
  `brewGrain2Weight` varchar(10) DEFAULT NULL,
  `brewGrain3` varchar(100) DEFAULT NULL,
  `brewGrain3Weight` varchar(10) DEFAULT NULL,
  `brewGrain4` varchar(100) DEFAULT NULL,
  `brewGrain4Weight` varchar(10) DEFAULT NULL,
  `brewGrain5` varchar(100) DEFAULT NULL,
  `brewGrain5Weight` varchar(4) DEFAULT NULL,
  `brewGrain6` varchar(100) DEFAULT NULL,
  `brewGrain6Weight` varchar(10) DEFAULT NULL,
  `brewGrain7` varchar(100) DEFAULT NULL,
  `brewGrain7Weight` varchar(10) DEFAULT NULL,
  `brewGrain8` varchar(100) DEFAULT NULL,
  `brewGrain8Weight` varchar(10) DEFAULT NULL,
  `brewGrain9` varchar(100) DEFAULT NULL,
  `brewGrain9Weight` varchar(10) DEFAULT NULL,
  `brewAddition1` varchar(100) DEFAULT NULL,
  `brewAddition1Amt` varchar(20) DEFAULT NULL,
  `brewAddition2` varchar(100) DEFAULT NULL,
  `brewAddition2Amt` varchar(20) DEFAULT NULL,
  `brewAddition3` varchar(100) DEFAULT NULL,
  `brewAddition3Amt` varchar(20) DEFAULT NULL,
  `brewAddition4` varchar(100) DEFAULT NULL,
  `brewAddition4Amt` varchar(20) DEFAULT NULL,
  `brewAddition5` varchar(100) DEFAULT NULL,
  `brewAddition5Amt` varchar(20) DEFAULT NULL,
  `brewAddition6` varchar(100) DEFAULT NULL,
  `brewAddition6Amt` varchar(20) DEFAULT NULL,
  `brewAddition7` varchar(100) DEFAULT NULL,
  `brewAddition7Amt` varchar(20) DEFAULT NULL,
  `brewAddition8` varchar(100) DEFAULT NULL,
  `brewAddition8Amt` varchar(20) DEFAULT NULL,
  `brewAddition9` varchar(100) DEFAULT NULL,
  `brewAddition9Amt` varchar(20) DEFAULT NULL,
  `brewHops1` varchar(100) DEFAULT NULL,
  `brewHops1Weight` varchar(10) DEFAULT NULL,
  `brewHops1IBU` varchar(10) DEFAULT NULL,
  `brewHops1Time` varchar(25) DEFAULT NULL,
  `brewHops2` varchar(100) DEFAULT NULL,
  `brewHops2Weight` varchar(10) DEFAULT NULL,
  `brewHops2IBU` varchar(10) DEFAULT NULL,
  `brewHops2Time` varchar(25) DEFAULT NULL,
  `brewHops3` varchar(100) DEFAULT NULL,
  `brewHops3Weight` varchar(10) DEFAULT NULL,
  `brewHops3IBU` varchar(10) DEFAULT NULL,
  `brewHops3Time` varchar(25) DEFAULT NULL,
  `brewHops4` varchar(100) DEFAULT NULL,
  `brewHops4Weight` varchar(10) DEFAULT NULL,
  `brewHops4IBU` varchar(10) DEFAULT NULL,
  `brewHops4Time` varchar(25) DEFAULT NULL,
  `brewHops5` varchar(100) DEFAULT NULL,
  `brewHops5Weight` varchar(10) DEFAULT NULL,
  `brewHops5IBU` varchar(10) DEFAULT NULL,
  `brewHops5Time` varchar(25) DEFAULT NULL,
  `brewHops6` varchar(100) DEFAULT NULL,
  `brewHops6Weight` varchar(10) DEFAULT NULL,
  `brewHops6IBU` varchar(10) DEFAULT NULL,
  `brewHops6Time` varchar(25) DEFAULT NULL,
  `brewHops7` varchar(100) DEFAULT NULL,
  `brewHops7Weight` varchar(10) DEFAULT NULL,
  `brewHops7IBU` varchar(10) DEFAULT NULL,
  `brewHops7Time` varchar(25) DEFAULT NULL,
  `brewHops8` varchar(100) DEFAULT NULL,
  `brewHops8Weight` varchar(10) DEFAULT NULL,
  `brewHops8IBU` varchar(10) DEFAULT NULL,
  `brewHops8Time` varchar(25) DEFAULT NULL,
  `brewHops9` varchar(100) DEFAULT NULL,
  `brewHops9Weight` varchar(10) DEFAULT NULL,
  `brewHops9IBU` varchar(10) DEFAULT NULL,
  `brewHops9Time` varchar(25) DEFAULT NULL,
  `brewHops1Use` varchar(25) DEFAULT NULL,
  `brewHops2Use` varchar(25) DEFAULT NULL,
  `brewHops3Use` varchar(25) DEFAULT NULL,
  `brewHops4Use` varchar(25) DEFAULT NULL,
  `brewHops5Use` varchar(25) DEFAULT NULL,
  `brewHops6Use` varchar(25) DEFAULT NULL,
  `brewHops7Use` varchar(25) DEFAULT NULL,
  `brewHops8Use` varchar(25) DEFAULT NULL,
  `brewHops9Use` varchar(25) DEFAULT NULL,
  `brewHops1Type` varchar(25) DEFAULT NULL,
  `brewHops2Type` varchar(25) DEFAULT NULL,
  `brewHops3Type` varchar(25) DEFAULT NULL,
  `brewHops4Type` varchar(25) DEFAULT NULL,
  `brewHops5Type` varchar(25) DEFAULT NULL,
  `brewHops6Type` varchar(25) DEFAULT NULL,
  `brewHops7Type` varchar(25) DEFAULT NULL,
  `brewHops8Type` varchar(25) DEFAULT NULL,
  `brewHops9Type` varchar(25) DEFAULT NULL,
  `brewHops1Form` varchar(25) DEFAULT NULL,
  `brewHops2Form` varchar(25) DEFAULT NULL,
  `brewHops3Form` varchar(25) DEFAULT NULL,
  `brewHops4Form` varchar(25) DEFAULT NULL,
  `brewHops5Form` varchar(25) DEFAULT NULL,
  `brewHops6Form` varchar(25) DEFAULT NULL,
  `brewHops7Form` varchar(25) DEFAULT NULL,
  `brewHops8Form` varchar(25) DEFAULT NULL,
  `brewHops9Form` varchar(25) DEFAULT NULL,
  `brewYeast` varchar(250) DEFAULT NULL,
  `brewYeastMan` varchar(250) DEFAULT NULL,
  `brewYeastForm` varchar(25) DEFAULT NULL,
  `brewYeastType` varchar(25) DEFAULT NULL,
  `brewYeastAmount` varchar(25) DEFAULT NULL,
  `brewYeastStarter` char(1) DEFAULT NULL,
  `brewYeastNutrients` text,
  `brewOG` float DEFAULT NULL,
  `brewFG` float DEFAULT NULL,
  `brewPrimary` varchar(10) DEFAULT NULL,
  `brewPrimaryTemp` varchar(10) DEFAULT NULL,
  `brewSecondary` varchar(10) DEFAULT NULL,
  `brewSecondaryTemp` varchar(10) DEFAULT NULL,
  `brewOther` varchar(10) DEFAULT NULL,
  `brewOtherTemp` varchar(10) DEFAULT NULL,
  `brewComments` text,
  `brewMashStep1Name` varchar(250) DEFAULT NULL,
  `brewMashStep1Temp` char(3) DEFAULT NULL,
  `brewMashStep1Time` char(3) DEFAULT NULL,
  `brewMashStep2Name` varchar(250) DEFAULT NULL,
  `brewMashStep2Temp` char(3) DEFAULT NULL,
  `brewMashStep2Time` char(3) DEFAULT NULL,
  `brewMashStep3Name` varchar(250) DEFAULT NULL,
  `brewMashStep3Temp` char(3) DEFAULT NULL,
  `brewMashStep3Time` char(3) DEFAULT NULL,
  `brewMashStep4Name` varchar(250) DEFAULT NULL,
  `brewMashStep4Temp` char(3) DEFAULT NULL,
  `brewMashStep4Time` char(3) DEFAULT NULL,
  `brewMashStep5Name` varchar(250) DEFAULT NULL,
  `brewMashStep5Temp` char(3) DEFAULT NULL,
  `brewMashStep5Time` char(3) DEFAULT NULL,
  `brewFinings` varchar(250) DEFAULT NULL,
  `brewWaterNotes` varchar(250) DEFAULT NULL,
  `brewBrewerID` varchar(250) DEFAULT NULL,
  `brewCarbonationMethod` varchar(255) DEFAULT NULL,
  `brewCarbonationVol` varchar(10) DEFAULT NULL,
  `brewCarbonationNotes` text,
  `brewBoilHours` varchar(255) DEFAULT NULL,
  `brewBoilMins` varchar(255) DEFAULT NULL,
  `brewBrewerFirstName` varchar(255) DEFAULT NULL,
  `brewBrewerLastName` varchar(255) DEFAULT NULL,
  `brewExtract1Use` varchar(255) DEFAULT NULL,
  `brewExtract2Use` varchar(255) DEFAULT NULL,
  `brewExtract3Use` varchar(255) DEFAULT NULL,
  `brewExtract4Use` varchar(255) DEFAULT NULL,
  `brewExtract5Use` varchar(255) DEFAULT NULL,
  `brewGrain1Use` varchar(255) DEFAULT NULL,
  `brewGrain2Use` varchar(255) DEFAULT NULL,
  `brewGrain3Use` varchar(255) DEFAULT NULL,
  `brewGrain4Use` varchar(255) DEFAULT NULL,
  `brewGrain5Use` varchar(255) DEFAULT NULL,
  `brewGrain6Use` varchar(255) DEFAULT NULL,
  `brewGrain7Use` varchar(255) DEFAULT NULL,
  `brewGrain8Use` varchar(255) DEFAULT NULL,
  `brewGrain9Use` varchar(255) DEFAULT NULL,
  `brewAddition1Use` varchar(255) DEFAULT NULL,
  `brewAddition2Use` varchar(255) DEFAULT NULL,
  `brewAddition3Use` varchar(255) DEFAULT NULL,
  `brewAddition4Use` varchar(255) DEFAULT NULL,
  `brewAddition5Use` varchar(255) DEFAULT NULL,
  `brewAddition6Use` varchar(255) DEFAULT NULL,
  `brewAddition7Use` varchar(255) DEFAULT NULL,
  `brewAddition8Use` varchar(255) DEFAULT NULL,
  `brewAddition9Use` varchar(255) DEFAULT NULL,
  `brewPaid` char(1) DEFAULT 'N',
  `brewWinner` char(1) DEFAULT NULL,
  `brewWinnerCat` varchar(3) DEFAULT NULL,
  `brewWinnerSubCat` varchar(3) DEFAULT NULL,
  `brewWinnerPlace` varchar(3) DEFAULT NULL,
  `brewBOSRound` char(1) DEFAULT NULL,
  `brewBOSPlace` varchar(3) DEFAULT NULL,
  `brewReceived` char(1) DEFAULT NULL,
  `brewJudgingLocation` int(8) DEFAULT NULL,
  `brewCoBrewer` varchar(255) DEFAULT NULL,
  `brewJudgingNumber` FLOAT( 8 ) NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
//echo "<p>".$create_brewing."</p>";
$result = mysql_query($create_brewing, $brewing) or die(mysql_error());

$create_sponsors = "
CREATE TABLE IF NOT EXISTS `sponsors` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `sponsorName` varchar(255) DEFAULT NULL,
  `sponsorURL` varchar(255) DEFAULT NULL,
  `sponsorImage` varchar(255) DEFAULT NULL,
  `sponsorText` text,
  `sponsorLocation` text,
  `sponsorLevel` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
//echo "<p>".$create_sponsors."</p>";
$result = mysql_query($create_sponsors, $brewing) or die(mysql_error());

$create_judging_assignments = "
CREATE TABLE IF NOT EXISTS `judging_assignments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bid` int(11) DEFAULT NULL COMMENT 'id from brewer',
  `assignment` char(1) DEFAULT NULL,
  `assignTable` int(11) DEFAULT NULL COMMENT 'id from judging_tables',
  `assignFlight` int(11) DEFAULT NULL,
  `assignRound` int(11) DEFAULT NULL,
  `assignLocation` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
//echo "<p>".$create_judging_assignments."</p>";
$result = mysql_query($create_judging_assignments, $brewing) or die(mysql_error());

$create_judging_flights = "
CREATE TABLE IF NOT EXISTS `judging_flights` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `flightTable` int(8) DEFAULT NULL COMMENT 'id of Table from tables',
  `flightNumber` int(8) DEFAULT NULL,
  `flightEntryID` int(11) DEFAULT NULL COMMENT 'id of entry from the brewing table',
  `flightRound` int(8) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
//echo "<p>".$create_judging_flights."</p>";
$result = mysql_query($create_judging_flights, $brewing) or die(mysql_error());

$create_judging_scores = "
CREATE TABLE IF NOT EXISTS `judging_scores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eid` int(11) DEFAULT NULL COMMENT 'entry id from brewing table',
  `bid` int(11) DEFAULT NULL COMMENT 'brewer id from brewer table',
  `scoreTable` int(11) DEFAULT NULL COMMENT 'id of table from judging_tables table',
  `scoreEntry` int(11) DEFAULT NULL COMMENT 'numerical score assigned by judges',
  `scorePlace` int(11) DEFAULT NULL COMMENT 'place of entry as assigned by judges',
  `scoreType` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
//echo "<p>".$create_judging_scores."</p>";
$result = mysql_query($create_judging_scores, $brewing) or die(mysql_error());

$create_judging_scores_bos = "
CREATE TABLE IF NOT EXISTS `judging_scores_bos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eid` int(11) DEFAULT NULL COMMENT 'entry id from brewing table',
  `bid` int(11) DEFAULT NULL COMMENT 'brewer id from brewer table',
  `scoreEntry` varchar(3) DEFAULT NULL COMMENT 'numerical score assigned by judges',
  `scorePlace` varchar(3) DEFAULT NULL COMMENT 'place of entry as assigned by judges',
  `scoreType` char(1) DEFAULT NULL COMMENT 'id of row from the style_type table',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
//echo "<p>".$create_judging_scores_bos."</p>";
$result = mysql_query($create_judging_scores_bos, $brewing) or die(mysql_error());

$create_judging_tables = "
CREATE TABLE IF NOT EXISTS `judging_tables` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `tableName` varchar(255) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Name of table that will judge the prescribed categories',
  `tableStyles` text CHARACTER SET latin1 COMMENT 'Array of ids from styles table',
  `tableNumber` int(5) DEFAULT NULL COMMENT 'User defined for sorting',
  `tableLocation` int(5) DEFAULT NULL COMMENT 'Physical location of table (if more than one judging location) - relational to judging table',
  `tableJudges` varchar(255) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Array of ids from brewer table',
  `tableStewards` varchar(255) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Array of ids from brewer table',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
//echo "<p>".$create_judging_tables."</p>";
$result = mysql_query($create_judging_tables, $brewing) or die(mysql_error());

$create_style_types = "
CREATE TABLE IF NOT EXISTS `style_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `styleTypeName` varchar(255) DEFAULT NULL,
  `styleTypeOwn` varchar(255) DEFAULT NULL,
  `styleTypeBOS` char(1) DEFAULT NULL,
  `styleTypeBOSMethod` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8; ";
$result = mysql_query($create_style_types, $brewing) or die(mysql_error());

$insert_style_types = "
INSERT INTO style_types (id, styleTypeName, styleTypeOwn, styleTypeBOS, styleTypeBOSMethod) VALUES
(1, 'Beer', 'bcoe', 'Y', 1),
(2, 'Cider', 'bcoe', 'Y', 3),
(3, 'Mead', 'bcoe', 'Y', 3)
";
//echo "<p>".$create_style_types."</p>";
$result = mysql_query($insert_style_types, $brewing) or die(mysql_error());

// Insert current user's info into new "users" and "brewer" table
$insert_users = "INSERT INTO users (
id, 
user_name, 
password, 
userLevel, 
userQuestion, 
userQuestionAnswer
) VALUES ('1','$user_name', '$password', '1', '$userQuestion', '$userQuestionAnswer');";
//echo "<p>".$insert_users."</p>";
$result = mysql_query($insert_users, $brewing) or die(mysql_error());

$insert_brewer = "INSERT INTO brewer (
id,
uid,
brewerFirstName,
brewerLastName,
brewerAddress,
brewerCity,
brewerState,
brewerZip,
brewerCountry,
brewerPhone1,
brewerPhone2,
brewerClubs,
brewerEmail,
brewerNickname,
brewerSteward,
brewerJudge,
brewerJudgeID,
brewerJudgeRank,
brewerJudgeLikes,
brewerJudgeDislikes,
brewerJudgeLocation,
brewerStewardLocation,
brewerJudgeAssignedLocation,
brewerStewardAssignedLocation,
brewerAssignment,
brewerAHA,
brewerDiscount,
brewerJudgeBOS
) 
VALUES (
NULL, 
'1', 
'$brewerFirstName', 
'$brewerLastName', 
'$brewerAddress', 
'$brewerCity', 
'$brewerState', 
'$brewerZip', 
'$brewerCountry', 
'$brewerPhone1', 
'$brewerPhone2', 
'$brewerClubs', 
'$brewerEmail', 
'$brewerNickname', 
'$brewerSteward', 
'$brewerJudge', 
'$brewerJudgeID', 
'$brewerJudgeRank', 
'$brewerJudgeLikes', 
'$brewerJudgeDislikes',
NULL,
NULL,
NULL,
NULL,
NULL,
'$brewerAHA',
NULL,
NULL
);";
//echo "<p>".$insert_brewer."</p>";
$result = mysql_query($insert_brewer, $brewing) or die(mysql_error());

// Insert a new record into the "archive" table containing the newly created archives names (allows access to archived tables)
$insert_archive = sprintf("INSERT INTO archive (id, archiveSuffix) VALUES (%s, %s);", "''", "'".$suffix."'");
//echo "<p>".$insert_archive."</p>";
$result = mysql_query($insert_archive, $brewing) or die(mysql_error());

// Last, log the user in and redirect
session_destroy();
mysql_select_db($database, $brewing);
$query_login = "SELECT COUNT(*) as 'count' FROM users WHERE user_name = '$user_name' AND password = '$password'";
$login = mysql_query($query_login, $brewing) or die(mysql_error());
$row_login = mysql_fetch_assoc($login);

session_start();
	// Authenticate the user
	if ($row_login['count'] == 1) {
  		// Register the loginUsername
 		$_SESSION["loginUsername"] = $user_name;
		// If the username/password combo is OK, relocate to the "protected" content index page
  		header("Location: ../index.php?section=admin&go=archive&msg=7");
  		exit;
		}
	else {
  		// If the username/password combo is incorrect or not found, relocate to the login error page
  		header("Location: ../index.php?section=admin&go=archive&msg=6");
  		session_destroy();
  		exit;
		}
}
?>