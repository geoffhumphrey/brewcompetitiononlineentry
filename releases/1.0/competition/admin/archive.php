<?php 
require ('../Connections/config.php');
session_start();

$suffix = $_POST['archiveSuffix'];
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

// Second, rename current "users", "$brewers", and "brewing" tables according to information gathered from the form
$sql1 = "RENAME TABLE ".$database.".users TO ".$database.".users_".$suffix.";";
mysql_select_db($database, $brewing);
$Result1 = mysql_query($sql1, $brewing) or die(mysql_error());

$sql2 = "RENAME TABLE ".$database.".brewer  TO ".$database.".brewer_".$suffix.";";
mysql_select_db($database, $brewing);
$Result2 = mysql_query($sql2, $brewing) or die(mysql_error());

$sql3 = "RENAME TABLE ".$database.".brewing  TO ".$database.".brewing_".$suffix.";";
mysql_select_db($database, $brewing);
$Result3 = mysql_query($sql3, $brewing) or die(mysql_error());

// Third, insert a clean "users", "brewers", and "brewing" tables

$sql4 = "
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(8) NOT NULL auto_increment,
  `user_name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `password` varchar(250) collate utf8_unicode_ci NOT NULL default '',
  `userLevel` char(1) collate utf8_unicode_ci default NULL,
  `userQuestion` varchar(255) collate utf8_unicode_ci default NULL,
  `userQuestionAnswer` varchar(255) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
";

mysql_select_db($database, $brewing);
$Result4 = mysql_query($sql4, $brewing) or die(mysql_error());

$sql5 = "
CREATE TABLE IF NOT EXISTS `brewer` (
  `id` tinyint(4) NOT NULL auto_increment,
  `brewerFirstName` varchar(200) collate utf8_unicode_ci default NULL,
  `brewerLastName` varchar(200) collate utf8_unicode_ci default NULL,
  `brewerAddress` varchar(255) collate utf8_unicode_ci default NULL,
  `brewerCity` varchar(255) collate utf8_unicode_ci default NULL,
  `brewerState` varchar(255) collate utf8_unicode_ci default NULL,
  `brewerZip` varchar(255) collate utf8_unicode_ci default NULL,
  `brewerCountry` varchar(255) collate utf8_unicode_ci default NULL,
  `brewerPhone1` varchar(255) collate utf8_unicode_ci default NULL,
  `brewerPhone2` varchar(255) collate utf8_unicode_ci default NULL,
  `brewerClubs` text collate utf8_unicode_ci,
  `brewerEmail` varchar(255) collate utf8_unicode_ci default NULL,
  `brewerNickname` varchar(255) collate utf8_unicode_ci default NULL,
  `brewerSteward` char(1) collate utf8_unicode_ci default NULL,
  `brewerJudge` char(1) collate utf8_unicode_ci default NULL,
  `brewerJudgeID` varchar(255) collate utf8_unicode_ci default NULL,
  `brewerJudgeRank` varchar(255) collate utf8_unicode_ci default NULL,
  `brewerJudgeLikes` text collate utf8_unicode_ci,
  `brewerJudgeDislikes` text collate utf8_unicode_ci,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
";
mysql_select_db($database, $brewing);
$Result5 = mysql_query($sql5, $brewing) or die(mysql_error());

$sql6 = "
CREATE TABLE IF NOT EXISTS `brewing` (
  `id` tinyint(4) NOT NULL auto_increment,
  `brewName` varchar(250) collate utf8_unicode_ci default NULL,
  `brewStyle` varchar(250) collate utf8_unicode_ci default NULL,
  `brewCategory` char(2) collate utf8_unicode_ci default NULL,
  `brewCategorySort` char(2) collate utf8_unicode_ci default NULL,
  `brewSubCategory` char(1) collate utf8_unicode_ci default NULL,
  `brewBottleDate` date default NULL,
  `brewDate` date default NULL,
  `brewYield` varchar(10) collate utf8_unicode_ci default NULL,
  `brewInfo` text collate utf8_unicode_ci,
  `brewMead1` varchar(255) collate utf8_unicode_ci default NULL,
  `brewMead2` varchar(255) collate utf8_unicode_ci default NULL,
  `brewMead3` varchar(255) collate utf8_unicode_ci default NULL,
  `brewExtract1` varchar(100) collate utf8_unicode_ci default NULL,
  `brewExtract1Weight` varchar(10) collate utf8_unicode_ci default NULL,
  `brewExtract2` varchar(100) collate utf8_unicode_ci default NULL,
  `brewExtract2Weight` varchar(10) collate utf8_unicode_ci default NULL,
  `brewExtract3` varchar(100) collate utf8_unicode_ci default NULL,
  `brewExtract3Weight` varchar(4) collate utf8_unicode_ci default NULL,
  `brewExtract4` varchar(100) collate utf8_unicode_ci default NULL,
  `brewExtract4Weight` varchar(10) collate utf8_unicode_ci default NULL,
  `brewExtract5` varchar(100) collate utf8_unicode_ci default NULL,
  `brewExtract5Weight` varchar(10) collate utf8_unicode_ci default NULL,
  `brewGrain1` varchar(100) collate utf8_unicode_ci default NULL,
  `brewGrain1Weight` varchar(10) collate utf8_unicode_ci default NULL,
  `brewGrain2` varchar(100) collate utf8_unicode_ci default NULL,
  `brewGrain2Weight` varchar(10) collate utf8_unicode_ci default NULL,
  `brewGrain3` varchar(100) collate utf8_unicode_ci default NULL,
  `brewGrain3Weight` varchar(10) collate utf8_unicode_ci default NULL,
  `brewGrain4` varchar(100) collate utf8_unicode_ci default NULL,
  `brewGrain4Weight` varchar(10) collate utf8_unicode_ci default NULL,
  `brewGrain5` varchar(100) collate utf8_unicode_ci default NULL,
  `brewGrain5Weight` varchar(4) collate utf8_unicode_ci default NULL,
  `brewGrain6` varchar(100) collate utf8_unicode_ci default NULL,
  `brewGrain6Weight` varchar(10) collate utf8_unicode_ci default NULL,
  `brewGrain7` varchar(100) collate utf8_unicode_ci default NULL,
  `brewGrain7Weight` varchar(10) collate utf8_unicode_ci default NULL,
  `brewGrain8` varchar(100) collate utf8_unicode_ci default NULL,
  `brewGrain8Weight` varchar(10) collate utf8_unicode_ci default NULL,
  `brewGrain9` varchar(100) collate utf8_unicode_ci default NULL,
  `brewGrain9Weight` varchar(10) collate utf8_unicode_ci default NULL,
  `brewAddition1` varchar(100) collate utf8_unicode_ci default NULL,
  `brewAddition1Amt` varchar(20) collate utf8_unicode_ci default NULL,
  `brewAddition2` varchar(100) collate utf8_unicode_ci default NULL,
  `brewAddition2Amt` varchar(20) collate utf8_unicode_ci default NULL,
  `brewAddition3` varchar(100) collate utf8_unicode_ci default NULL,
  `brewAddition3Amt` varchar(20) collate utf8_unicode_ci default NULL,
  `brewAddition4` varchar(100) collate utf8_unicode_ci default NULL,
  `brewAddition4Amt` varchar(20) collate utf8_unicode_ci default NULL,
  `brewAddition5` varchar(100) collate utf8_unicode_ci default NULL,
  `brewAddition5Amt` varchar(20) collate utf8_unicode_ci default NULL,
  `brewAddition6` varchar(100) collate utf8_unicode_ci default NULL,
  `brewAddition6Amt` varchar(20) collate utf8_unicode_ci default NULL,
  `brewAddition7` varchar(100) collate utf8_unicode_ci default NULL,
  `brewAddition7Amt` varchar(20) collate utf8_unicode_ci default NULL,
  `brewAddition8` varchar(100) collate utf8_unicode_ci default NULL,
  `brewAddition8Amt` varchar(20) collate utf8_unicode_ci default NULL,
  `brewAddition9` varchar(100) collate utf8_unicode_ci default NULL,
  `brewAddition9Amt` varchar(20) collate utf8_unicode_ci default NULL,
  `brewHops1` varchar(100) collate utf8_unicode_ci default NULL,
  `brewHops1Weight` varchar(10) collate utf8_unicode_ci default NULL,
  `brewHops1IBU` varchar(10) collate utf8_unicode_ci default NULL,
  `brewHops1Time` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops2` varchar(100) collate utf8_unicode_ci default NULL,
  `brewHops2Weight` varchar(10) collate utf8_unicode_ci default NULL,
  `brewHops2IBU` varchar(10) collate utf8_unicode_ci default NULL,
  `brewHops2Time` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops3` varchar(100) collate utf8_unicode_ci default NULL,
  `brewHops3Weight` varchar(10) collate utf8_unicode_ci default NULL,
  `brewHops3IBU` varchar(10) collate utf8_unicode_ci default NULL,
  `brewHops3Time` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops4` varchar(100) collate utf8_unicode_ci default NULL,
  `brewHops4Weight` varchar(10) collate utf8_unicode_ci default NULL,
  `brewHops4IBU` varchar(10) collate utf8_unicode_ci default NULL,
  `brewHops4Time` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops5` varchar(100) collate utf8_unicode_ci default NULL,
  `brewHops5Weight` varchar(10) collate utf8_unicode_ci default NULL,
  `brewHops5IBU` varchar(10) collate utf8_unicode_ci default NULL,
  `brewHops5Time` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops6` varchar(100) collate utf8_unicode_ci default NULL,
  `brewHops6Weight` varchar(10) collate utf8_unicode_ci default NULL,
  `brewHops6IBU` varchar(10) collate utf8_unicode_ci default NULL,
  `brewHops6Time` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops7` varchar(100) collate utf8_unicode_ci default NULL,
  `brewHops7Weight` varchar(10) collate utf8_unicode_ci default NULL,
  `brewHops7IBU` varchar(10) collate utf8_unicode_ci default NULL,
  `brewHops7Time` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops8` varchar(100) collate utf8_unicode_ci default NULL,
  `brewHops8Weight` varchar(10) collate utf8_unicode_ci default NULL,
  `brewHops8IBU` varchar(10) collate utf8_unicode_ci default NULL,
  `brewHops8Time` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops9` varchar(100) collate utf8_unicode_ci default NULL,
  `brewHops9Weight` varchar(10) collate utf8_unicode_ci default NULL,
  `brewHops9IBU` varchar(10) collate utf8_unicode_ci default NULL,
  `brewHops9Time` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops1Use` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops2Use` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops3Use` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops4Use` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops5Use` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops6Use` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops7Use` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops8Use` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops9Use` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops1Type` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops2Type` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops3Type` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops4Type` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops5Type` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops6Type` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops7Type` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops8Type` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops9Type` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops1Form` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops2Form` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops3Form` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops4Form` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops5Form` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops6Form` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops7Form` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops8Form` varchar(25) collate utf8_unicode_ci default NULL,
  `brewHops9Form` varchar(25) collate utf8_unicode_ci default NULL,
  `brewYeast` varchar(250) collate utf8_unicode_ci default NULL,
  `brewYeastMan` varchar(250) collate utf8_unicode_ci default NULL,
  `brewYeastForm` varchar(25) collate utf8_unicode_ci default NULL,
  `brewYeastType` varchar(25) collate utf8_unicode_ci default NULL,
  `brewYeastAmount` varchar(25) collate utf8_unicode_ci default NULL,
  `brewYeastStarter` char(1) collate utf8_unicode_ci default NULL,
  `brewYeastNutrients` text collate utf8_unicode_ci,
  `brewOG` varchar(10) collate utf8_unicode_ci default NULL,
  `brewFG` varchar(10) collate utf8_unicode_ci default NULL,
  `brewPrimary` varchar(10) collate utf8_unicode_ci default NULL,
  `brewPrimaryTemp` varchar(10) collate utf8_unicode_ci default NULL,
  `brewSecondary` varchar(10) collate utf8_unicode_ci default NULL,
  `brewSecondaryTemp` varchar(10) collate utf8_unicode_ci default NULL,
  `brewOther` varchar(10) collate utf8_unicode_ci default NULL,
  `brewOtherTemp` varchar(10) collate utf8_unicode_ci default NULL,
  `brewComments` text collate utf8_unicode_ci,
  `brewMashStep1Name` varchar(250) collate utf8_unicode_ci default NULL,
  `brewMashStep1Temp` char(3) collate utf8_unicode_ci default NULL,
  `brewMashStep1Time` char(3) collate utf8_unicode_ci default NULL,
  `brewMashStep2Name` varchar(250) collate utf8_unicode_ci default NULL,
  `brewMashStep2Temp` char(3) collate utf8_unicode_ci default NULL,
  `brewMashStep2Time` char(3) collate utf8_unicode_ci default NULL,
  `brewMashStep3Name` varchar(250) collate utf8_unicode_ci default NULL,
  `brewMashStep3Temp` char(3) collate utf8_unicode_ci default NULL,
  `brewMashStep3Time` char(3) collate utf8_unicode_ci default NULL,
  `brewMashStep4Name` varchar(250) collate utf8_unicode_ci default NULL,
  `brewMashStep4Temp` char(3) collate utf8_unicode_ci default NULL,
  `brewMashStep4Time` char(3) collate utf8_unicode_ci default NULL,
  `brewMashStep5Name` varchar(250) collate utf8_unicode_ci default NULL,
  `brewMashStep5Temp` char(3) collate utf8_unicode_ci default NULL,
  `brewMashStep5Time` char(3) collate utf8_unicode_ci default NULL,
  `brewFinings` varchar(250) collate utf8_unicode_ci default NULL,
  `brewWaterNotes` varchar(250) collate utf8_unicode_ci default NULL,
  `brewBrewerID` varchar(250) collate utf8_unicode_ci default NULL,
  `brewCarbonationMethod` varchar(255) collate utf8_unicode_ci default NULL,
  `brewCarbonationVol` varchar(10) collate utf8_unicode_ci default NULL,
  `brewCarbonationNotes` text collate utf8_unicode_ci,
  `brewBoilHours` varchar(255) collate utf8_unicode_ci default NULL,
  `brewBoilMins` varchar(255) collate utf8_unicode_ci default NULL,
  `brewBrewerFirstName` varchar(255) collate utf8_unicode_ci default NULL,
  `brewBrewerLastName` varchar(255) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
";

mysql_select_db($database, $brewing);
$Result6 = mysql_query($sql6, $brewing) or die(mysql_error());

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