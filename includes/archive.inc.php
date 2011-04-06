<?php 

/**
 * Module: archive.inc.php
 * Description: This module takes the current database tables and renames them 
 *              for archiving purposes so that data is still available.
 */

define('ROOT',$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR);
define('INCLUDES',ROOT.'includes'.DIRECTORY_SEPARATOR);
define('CONFIG',ROOT.'Connections'.DIRECTORY_SEPARATOR);
require(CONFIG.'config.php'); 
require(INCLUDES.'scrubber.inc.php');

session_start();

$suffix = strtr($_POST['archiveSuffix'], $space_remove);
mysql_select_db($database, $brewing);

$query_suffixCheck = sprintf("SELECT archiveSuffix FROM archive WHERE archiveSuffix = '%s'", $suffix);
$suffixCheck = mysql_query($query_suffixCheck, $brewing) or die(mysql_error());
$row_suffixCheck = mysql_fetch_assoc($suffixCheck);
$totalRows_suffixCheck = mysql_num_rows($suffixCheck);

if ($totalRows_suffixCheck > 0) { 
header("Location: ../index.php?section=admin&amp;go=archive&amp;msg=6");
} 

else {

// First, need to gather current User's information from the current "users" AND current "brewer" tables and store in variables
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

  $brewerFirstName = $row_name['brewerFirstName'];
  $brewerLastName = $row_name['brewerLastName'];
  $brewerAddress = $row_name['brewerAddress'];
  $brewerCity = $row_name['brewerCity'];
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


// Second, rename current "users", "brewer", "brewing" tables according to information gathered from the form
$sql1 = "RENAME TABLE ".$database.".users TO ".$database.".users_".$suffix.";";
$Result1 = mysql_query($sql1, $brewing) or die(mysql_error());

$sql2 = "RENAME TABLE ".$database.".brewer  TO ".$database.".brewer_".$suffix.";";
$Result2 = mysql_query($sql2, $brewing) or die(mysql_error());

$sql3 = "RENAME TABLE ".$database.".brewing  TO ".$database.".brewing_".$suffix.";";
$Result3 = mysql_query($sql3, $brewing) or die(mysql_error());

$sql3_a = "RENAME TABLE ".$database.".sponsors  TO ".$database.".sponsors_".$suffix.";";
$Result3_a = mysql_query($sql3_a, $brewing) or die(mysql_error());

// Third, insert a clean "users", "brewer", "brewing", and "sponsors" tables

$sql4 = "
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `password` varchar(250) NOT NULL DEFAULT '',
  `userLevel` char(1) DEFAULT NULL,
  `userQuestion` varchar(255) DEFAULT NULL,
  `userQuestionAnswer` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
";

$Result4 = mysql_query($sql4, $brewing) or die(mysql_error());

$sql5 = "
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
  `brewerJudgeRank` varchar(255) DEFAULT NULL,
  `brewerJudgeLikes` text,
  `brewerJudgeDislikes` text,
  `brewerJudgeLocation` int(8) DEFAULT NULL,
  `brewerJudgeLocation2` int(8) DEFAULT NULL,
  `brewerStewardLocation` int(8) DEFAULT NULL,
  `brewerStewardLocation2` int(8) DEFAULT NULL,
  `brewerJudgeAssignedLocation` int(8) DEFAULT NULL,
  `brewerStewardAssignedLocation` int(8) DEFAULT NULL,
  `brewerAssignment` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
";

$Result5 = mysql_query($sql5, $brewing) or die(mysql_error());

$sql6 = "
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
  `brewOG` varchar(10) DEFAULT NULL,
  `brewFG` varchar(10) DEFAULT NULL,
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
  `brewPaid` char(1) DEFAULT NULL,
  `brewWinner` char(1) DEFAULT NULL,
  `brewWinnerCat` varchar(3) DEFAULT NULL,
  `brewWinnerSubCat` varchar(3) DEFAULT NULL,
  `brewWinnerPlace` varchar(3) DEFAULT NULL,
  `brewBOSRound` char(1) DEFAULT NULL,
  `brewBOSPlace` varchar(3) DEFAULT NULL,
  `brewReceived` char(1) DEFAULT NULL,
  `brewJudgingLocation` int(8) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
";

$Result6 = mysql_query($sql6, $brewing) or die(mysql_error());

$sql6_a = "
CREATE TABLE IF NOT EXISTS `sponsors` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `sponsorName` varchar(255) DEFAULT NULL,
  `sponsorURL` varchar(255) DEFAULT NULL,
  `sponsorImage` varchar(255) DEFAULT NULL,
  `sponsorText` text,
  `sponsorLocation` text,
  `sponsorLevel` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
";

$Result6_a = mysql_query($sql6_a, $brewing) or die(mysql_error());


// Fourth, insert current user's info into new "users" and "brewer" table

$sql7 = "INSERT INTO users (
id, 
user_name, 
password, 
userLevel, 
userQuestion, 
userQuestionAnswer
) VALUES ('1','$user_name', '$password', '1', '$userQuestion', '$userQuestionAnswer');";

$Result7 = mysql_query($sql7, $brewing) or die(mysql_error());

$sql8 = "INSERT INTO brewer (
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
brewerJudgeLocation2, 			
brewerStewardLocation, 			
brewerStewardLocation2,		
brewerJudgeAssignedLocation,		
brewerStewardAssignedLocation,		
brewerAssignment 
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
NULL,
NULL
);";

$Result8 = mysql_query($sql8, $brewing) or die(mysql_error());

// Sixth, insert a new record into the "archive" table containing the newly created archives names (allows access to archived tables)
$sql9 = sprintf("INSERT INTO archive (id, archiveUserTableName, archiveBrewerTableName, archiveBrewingTableName, archiveSuffix) VALUES (%s, %s, %s, %s, %s);", "''", "'users_".$suffix."'", "'brewer_".$suffix."'", "'brewing_".$suffix."'", "'".$suffix."'");

$Result9 = mysql_query($sql9, $brewing) or die(mysql_error());

// Last, log the user in and redirect

session_destroy();
mysql_select_db($database, $brewing);
$query_login = "SELECT password FROM users WHERE user_name = '$user_name' AND password = '$password'";
$login = mysql_query($query_login, $brewing) or die(mysql_error());
$row_login = mysql_fetch_assoc($login);
$totalRows_login = mysql_num_rows($login);

	session_start();
		// Authenticate the user
		if ($totalRows_login == 1)
			{
  			// Register the loginUsername
  			$_SESSION["loginUsername"] = $user_name;

  			// If the username/password combo is OK, relocate to the "protected" content index page
  			header("Location: ../index.php?section=admin&amp;go=archive&amp;msg=7");
  			exit;
			}
		else
			{
  			// If the username/password combo is incorrect or not found, relocate to the login error page
  			header("Location: ../index.php?section=admin&amp;go=archive&amp;msg=6");
  			session_destroy();
  			exit;
			}


/*
echo "<p>".$sql1."</p>";
echo "<p>".$sql2."</p>";
echo "<p>".$sql3."</p>";
echo "<p>".$sql3_a."</p>";
echo "<p>".$sql4."</p>";
echo "<p>".$sql5."</p>";
echo "<p>".$sql6."</p>";
echo "<p>".$sql6_a."</p>";
echo "<p>".$sql7."</p>";
echo "<p>".$sql8."</p>";
echo "<p>".$sql9."</p>";
*/

}
?>