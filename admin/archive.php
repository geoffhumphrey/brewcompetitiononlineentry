<?php 
require ('../Connections/config.php');
require ('../includes/scrubber.inc.php');
session_start();

$suffix = strtr($_POST['archiveSuffix'], $space_remove);
mysql_select_db($database, $brewing);
$query_suffixCheck = sprintf("SELECT archiveSuffix FROM archive WHERE archiveSuffix = '%s'", $_POST['archiveSuffix']);
$suffixCheck = mysql_query($query_suffixCheck, $brewing) or die(mysql_error());
$row_suffixCheck = mysql_fetch_assoc($suffixCheck);
$totalRows_suffixCheck = mysql_num_rows($suffixCheck);

if ($totalRows_suffixCheck > 0) { 
header("Location: ../index.php?section=admin&go=archive&msg=6");
} 

else {

// First, need to gather current User's information from the current "users" AND current "brewer" tables and store in variables
$query_user = sprintf("SELECT * FROM users WHERE user_name = '%s'", $_SESSION["loginUsername"]);
$user = mysql_query($query_user, $brewing) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);

  $user_name = $row_user['user_name'];
  $password = $row_user['password'];
  $userLevel = $row_user['userLevel'];
  $userQuestion = $row_user['userQuestion'];
  $userQuestionAnswer = $row_user['userQuestionAnswer'];
  
$query_name = sprintf("SELECT * FROM brewer WHERE brewerEmail='%s'", $row_user['user_name']);
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

// Second, rename current "users", "$brewers", "brewing" tables according to information gathered from the form
$sql1 = "RENAME TABLE ".$database.".users TO ".$database.".users_".$suffix.";";
mysql_select_db($database, $brewing);
$Result1 = mysql_query($sql1, $brewing) or die(mysql_error());

$sql2 = "RENAME TABLE ".$database.".brewer  TO ".$database.".brewer_".$suffix.";";
mysql_select_db($database, $brewing);
$Result2 = mysql_query($sql2, $brewing) or die(mysql_error());

$sql3 = "RENAME TABLE ".$database.".brewing  TO ".$database.".brewing_".$suffix.";";
mysql_select_db($database, $brewing);
$Result3 = mysql_query($sql3, $brewing) or die(mysql_error());

$sql3_a = "RENAME TABLE ".$database.".sponsors  TO ".$database.".sponsors_".$suffix.";";
mysql_select_db($database, $brewing);
$Result3_a = mysql_query($sql3_a, $brewing) or die(mysql_error());

// Third, insert a clean "users", "brewers", and "brewing" tables

$sql4 = "
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(8) NOT NULL auto_increment,
  `user_name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `password` varchar(250) collate utf8_unicode_ci NOT NULL default '',
  `userLevel` char(1),
  `userQuestion` varchar(255),
  `userQuestionAnswer` varchar(255),
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
";

mysql_select_db($database, $brewing);
$Result4 = mysql_query($sql4, $brewing) or die(mysql_error());

$sql5 = "
CREATE TABLE IF NOT EXISTS `brewer` (
  `id` tinyint(4) NOT NULL auto_increment,
  `brewerFirstName` varchar(200),
  `brewerLastName` varchar(200),
  `brewerAddress` varchar(255),
  `brewerCity` varchar(255),
  `brewerState` varchar(255),
  `brewerZip` varchar(255),
  `brewerCountry` varchar(255),
  `brewerPhone1` varchar(255),
  `brewerPhone2` varchar(255),
  `brewerClubs` text,
  `brewerEmail` varchar(255),
  `brewerNickname` varchar(255),
  `brewerSteward` char(1),
  `brewerJudge` char(1),
  `brewerJudgeID` varchar(255),
  `brewerJudgeRank` varchar(255),
  `brewerJudgeLikes` text,
  `brewerJudgeDislikes` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
";
mysql_select_db($database, $brewing);
$Result5 = mysql_query($sql5, $brewing) or die(mysql_error());

$sql6 = "
CREATE TABLE IF NOT EXISTS `brewing` (
  `id` tinyint(4) NOT NULL auto_increment,
  `brewName` varchar(250),
  `brewStyle` varchar(250),
  `brewCategory` char(2),
  `brewCategorySort` char(2),
  `brewSubCategory` char(1),
  `brewBottleDate` date default NULL,
  `brewDate` date default NULL,
  `brewYield` varchar(10),
  `brewInfo` text,
  `brewMead1` varchar(255),
  `brewMead2` varchar(255),
  `brewMead3` varchar(255),
  `brewExtract1` varchar(100),
  `brewExtract1Weight` varchar(10),
  `brewExtract2` varchar(100),
  `brewExtract2Weight` varchar(10),
  `brewExtract3` varchar(100),
  `brewExtract3Weight` varchar(4),
  `brewExtract4` varchar(100),
  `brewExtract4Weight` varchar(10),
  `brewExtract5` varchar(100),
  `brewExtract5Weight` varchar(10),
  `brewGrain1` varchar(100),
  `brewGrain1Weight` varchar(10),
  `brewGrain2` varchar(100),
  `brewGrain2Weight` varchar(10),
  `brewGrain3` varchar(100),
  `brewGrain3Weight` varchar(10),
  `brewGrain4` varchar(100),
  `brewGrain4Weight` varchar(10),
  `brewGrain5` varchar(100),
  `brewGrain5Weight` varchar(4),
  `brewGrain6` varchar(100),
  `brewGrain6Weight` varchar(10),
  `brewGrain7` varchar(100),
  `brewGrain7Weight` varchar(10),
  `brewGrain8` varchar(100),
  `brewGrain8Weight` varchar(10),
  `brewGrain9` varchar(100),
  `brewGrain9Weight` varchar(10),
  `brewAddition1` varchar(100),
  `brewAddition1Amt` varchar(20),
  `brewAddition2` varchar(100),
  `brewAddition2Amt` varchar(20),
  `brewAddition3` varchar(100),
  `brewAddition3Amt` varchar(20),
  `brewAddition4` varchar(100),
  `brewAddition4Amt` varchar(20),
  `brewAddition5` varchar(100),
  `brewAddition5Amt` varchar(20),
  `brewAddition6` varchar(100),
  `brewAddition6Amt` varchar(20),
  `brewAddition7` varchar(100),
  `brewAddition7Amt` varchar(20),
  `brewAddition8` varchar(100),
  `brewAddition8Amt` varchar(20),
  `brewAddition9` varchar(100),
  `brewAddition9Amt` varchar(20),
  `brewHops1` varchar(100),
  `brewHops1Weight` varchar(10),
  `brewHops1IBU` varchar(10),
  `brewHops1Time` varchar(25),
  `brewHops2` varchar(100),
  `brewHops2Weight` varchar(10),
  `brewHops2IBU` varchar(10),
  `brewHops2Time` varchar(25),
  `brewHops3` varchar(100),
  `brewHops3Weight` varchar(10),
  `brewHops3IBU` varchar(10),
  `brewHops3Time` varchar(25),
  `brewHops4` varchar(100),
  `brewHops4Weight` varchar(10),
  `brewHops4IBU` varchar(10),
  `brewHops4Time` varchar(25),
  `brewHops5` varchar(100),
  `brewHops5Weight` varchar(10),
  `brewHops5IBU` varchar(10),
  `brewHops5Time` varchar(25),
  `brewHops6` varchar(100),
  `brewHops6Weight` varchar(10),
  `brewHops6IBU` varchar(10),
  `brewHops6Time` varchar(25),
  `brewHops7` varchar(100),
  `brewHops7Weight` varchar(10),
  `brewHops7IBU` varchar(10),
  `brewHops7Time` varchar(25),
  `brewHops8` varchar(100),
  `brewHops8Weight` varchar(10),
  `brewHops8IBU` varchar(10),
  `brewHops8Time` varchar(25),
  `brewHops9` varchar(100),
  `brewHops9Weight` varchar(10),
  `brewHops9IBU` varchar(10),
  `brewHops9Time` varchar(25),
  `brewHops1Use` varchar(25),
  `brewHops2Use` varchar(25),
  `brewHops3Use` varchar(25),
  `brewHops4Use` varchar(25),
  `brewHops5Use` varchar(25),
  `brewHops6Use` varchar(25),
  `brewHops7Use` varchar(25),
  `brewHops8Use` varchar(25),
  `brewHops9Use` varchar(25),
  `brewHops1Type` varchar(25),
  `brewHops2Type` varchar(25),
  `brewHops3Type` varchar(25),
  `brewHops4Type` varchar(25),
  `brewHops5Type` varchar(25),
  `brewHops6Type` varchar(25),
  `brewHops7Type` varchar(25),
  `brewHops8Type` varchar(25),
  `brewHops9Type` varchar(25),
  `brewHops1Form` varchar(25),
  `brewHops2Form` varchar(25),
  `brewHops3Form` varchar(25),
  `brewHops4Form` varchar(25),
  `brewHops5Form` varchar(25),
  `brewHops6Form` varchar(25),
  `brewHops7Form` varchar(25),
  `brewHops8Form` varchar(25),
  `brewHops9Form` varchar(25),
  `brewYeast` varchar(250),
  `brewYeastMan` varchar(250),
  `brewYeastForm` varchar(25),
  `brewYeastType` varchar(25),
  `brewYeastAmount` varchar(25),
  `brewYeastStarter` char(1),
  `brewYeastNutrients` text,
  `brewOG` varchar(10),
  `brewFG` varchar(10),
  `brewPrimary` varchar(10),
  `brewPrimaryTemp` varchar(10),
  `brewSecondary` varchar(10),
  `brewSecondaryTemp` varchar(10),
  `brewOther` varchar(10),
  `brewOtherTemp` varchar(10),
  `brewComments` text,
  `brewMashStep1Name` varchar(250),
  `brewMashStep1Temp` char(3),
  `brewMashStep1Time` char(3),
  `brewMashStep2Name` varchar(250),
  `brewMashStep2Temp` char(3),
  `brewMashStep2Time` char(3),
  `brewMashStep3Name` varchar(250),
  `brewMashStep3Temp` char(3),
  `brewMashStep3Time` char(3),
  `brewMashStep4Name` varchar(250),
  `brewMashStep4Temp` char(3),
  `brewMashStep4Time` char(3),
  `brewMashStep5Name` varchar(250),
  `brewMashStep5Temp` char(3),
  `brewMashStep5Time` char(3),
  `brewFinings` varchar(250),
  `brewWaterNotes` varchar(250),
  `brewBrewerID` varchar(250),
  `brewCarbonationMethod` varchar(255),
  `brewCarbonationVol` varchar(10),
  `brewCarbonationNotes` text,
  `brewBoilHours` varchar(255),
  `brewBoilMins` varchar(255),
  `brewBrewerFirstName` varchar(255),
  `brewBrewerLastName` varchar(255),
  `brewExtract1Use` VARCHAR(255) NULL, 
  `brewExtract2Use` VARCHAR(255) NULL, 
  `brewExtract3Use` VARCHAR(255) NULL, 
  `brewExtract4Use` VARCHAR(255) NULL, 
  `brewExtract5Use` VARCHAR(255) NULL,
  `brewGrain1Use` VARCHAR(255) NULL, 
  `brewGrain2Use` VARCHAR(255) NULL, 
  `brewGrain3Use` VARCHAR(255) NULL, 
  `brewGrain4Use` VARCHAR(255) NULL, 
  `brewGrain5Use` VARCHAR(255) NULL,
  `brewGrain6Use` VARCHAR(255) NULL, 
  `brewGrain7Use` VARCHAR(255) NULL, 
  `brewGrain8Use` VARCHAR(255) NULL, 
  `brewGrain9Use` VARCHAR(255) NULL,
  `brewAddition1Use` VARCHAR(255) NULL, 
  `brewAddition2Use` VARCHAR(255) NULL, 
  `brewAddition3Use` VARCHAR(255) NULL, 
  `brewAddition4Use` VARCHAR(255) NULL, 
  `brewAddition5Use` VARCHAR(255) NULL,
  `brewAddition6Use` VARCHAR(255) NULL, 
  `brewAddition7Use` VARCHAR(255) NULL, 
  `brewAddition8Use` VARCHAR(255) NULL, 
  `brewAddition9Use` VARCHAR(255) NULL,
  `brewPaid` CHAR( 1 ) NULL ,
  `brewWinner` CHAR( 1 ) NULL ,
  `brewWinnerCat` VARCHAR( 3 ) NULL ,
  `brewWinnerSubCat` VARCHAR( 3 ) NULL ,
  `brewWinnerPlace` VARCHAR( 3 ) NULL ,
  `brewBOSRound` CHAR( 1 ) NULL ,
  `brewBOSPlace` VARCHAR( 3 ) NULL ,
  `brewReceived` CHAR( 1 ) NULL ,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
";

mysql_select_db($database, $brewing);
$Result6 = mysql_query($sql6, $brewing) or die(mysql_error());

$sql6_a = "
CREATE TABLE IF NOT EXISTS `sponsors` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `sponsorName` varchar(255) DEFAULT NULL,
  `sponsorURL` varchar(255) DEFAULT NULL,
  `sponsorImage` varchar(255) DEFAULT NULL,
  `sponsorText` text,
  `sponsorLocation` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
";
mysql_select_db($database, $brewing);
$Result6_a = mysql_query($sql6_a, $brewing) or die(mysql_error());

// Fourth, insert current user's info into new "users" and "brewers" table

$sql7 = "INSERT INTO users (id, user_name, password, userLevel, userQuestion, userQuestionAnswer) VALUES (NULL,'$user_name', '$password', '1', '$userQuestion', '$userQuestionAnswer');";
mysql_select_db($database, $brewing);
$Result7 = mysql_query($sql7, $brewing) or die(mysql_error());

$sql8 = "INSERT INTO brewer (id, brewerFirstName, brewerLastName, brewerAddress, brewerCity, brewerState, brewerZip, brewerCountry, brewerPhone1, brewerPhone2, brewerClubs, brewerEmail, brewerNickname, brewerSteward, brewerJudge, brewerJudgeID, brewerJudgeRank, brewerJudgeLikes, brewerJudgeDislikes) VALUES
(NULL, '$brewerFirstName', '$brewerLastName', '$brewerAddress', '$brewerCity', '$brewerState', '$brewerZip', '$brewerCountry', '$brewerPhone1', '$brewerPhone2', '$brewerClubs', '$brewerEmail', '$brewerNickname', '$brewerSteward', '$brewerJudge', '$brewerJudgeID', '$brewerJudgeRank', '$brewerJudgeLikes', '$brewerJudgeDislikes');";
mysql_select_db($database, $brewing);
$Result8 = mysql_query($sql8, $brewing) or die(mysql_error());

// Sixth, insert a new record into the "archive" table containing the newly created archives names (allows access to archived tables)
$sql9 = sprintf("INSERT INTO archive (id, archiveUserTableName, archiveBrewerTableName, archiveBrewingTableName, archiveSuffix) VALUES (%s, %s, %s, %s, %s);", "''", "'users_".$suffix."'", "'brewer_".$suffix."'", "'brewing_".$suffix."'", "'".$suffix."'");
mysql_select_db($database, $brewing);
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
  			header("Location: ../index.php?section=admin&go=archive&msg=7");
  			exit;
			}
		else
			{
  			// If the username/password combo is incorrect or not found, relocate to the login error page
  			header("Location: ../index.php?section=admin&go=archive&msg=6");
  			session_destroy();
  			exit;
			}



/* DEBUG
echo $sql1."<br>";
echo $sql2."<br>";
echo $sql3."<br>";
echo $sql4."<br>";
echo $sql5."<br>";
echo $sql6."<br>";
echo $sql7."<br>";
echo $sql8."<br>";
echo $sql9."<br>";
*/

}
?>