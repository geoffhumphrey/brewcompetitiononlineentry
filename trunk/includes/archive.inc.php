<?php 

/**
 * Module:      archive.inc.php
 * Description: This module takes the current database tables and renames them 
 *              for archiving purposes so that data is still available.
 */
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', '1');
require('../paths.php');
require(INCLUDES.'scrubber.inc.php');
require(INCLUDES.'functions.inc.php');
$dbTable = "default";
require(INCLUDES.'db_tables.inc.php');
if (NHC) $base_url = "../";
else $base_url = $base_url;

session_start();

$suffix = strtr($_POST['archiveSuffix'], $space_remove);
mysql_select_db($database, $brewing);

$query_suffix_check = sprintf("SELECT COUNT(*) as 'count' FROM
 $archive_db_table WHERE archiveSuffix = '%s';", $suffix);
// echo "<p>".$query_suffix_check; 
$suffix_check = mysql_query($query_suffix_check, $brewing) or die(mysql_error());
 $row_suffix_check = mysql_fetch_assoc($suffix_check);

if ($row_suffix_check['count'] > 0) { 
header(sprintf("Location: %s", $base_url."index.php?section=admin&amp;go=archive&amp;msg=6"));
} 

else {

// Gather current User's information from the current "users" AND current "brewer" tables and store in variables
mysql_select_db($database, $brewing);
$query_user = sprintf("SELECT * FROM %s WHERE user_name = '%s'", $prefix."users", $_SESSION["loginUsername"]);
$user = mysql_query($query_user, $brewing) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);

	  $user_name = strtr($row_user['user_name'], $html_string);
	  $password = $row_user['password'];
	  $userLevel = $row_user['userLevel'];
	  $userQuestion = strtr($row_user['userQuestion'], $html_string);
	  $userQuestionAnswer = strtr($row_user['userQuestionAnswer'], $html_string);
	  $userCreated = $row_user['userCreated'];
  
$query_name = sprintf("SELECT * FROM %s WHERE brewerEmail='%s'", $prefix."brewer", $_SESSION["loginUsername"]);
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

// Second, rename current tables and recreate new ones.
$tables_array = array($users_db_table, $brewer_db_table, $brewing_db_table, $sponsors_db_table, $judging_assignments_db_table, $judging_flights_db_table, $judging_scores_db_table, $judging_tables_db_table, $style_types_db_table, $special_best_data_db_table, $special_best_info_db_table, $judging_scores_bos_db_table);


// For hosted accounts, limit the table creation to the users, brewer, brewing, judging_tables, judging_assignments, judging_scores, judging_scores_bos, and style_types tables
//$tables_array = array($users_db_table, $brewer_db_table, $brewing_db_table, $judging_assignments_db_table, $judging_scores_db_table, $judging_tables_db_table, $judging_scores_bos_db_table, $style_types_db_table);

foreach ($tables_array as $table) { 
	$rename_table = "RENAME TABLE ".$table." TO ".$table."_".$suffix.";";
	$result = mysql_query($rename_table, $brewing) or die(mysql_error());
	
	$create_table = "CREATE TABLE ".$table." LIKE ".$table."_".$suffix.";";
	$result = mysql_query($create_table, $brewing) or die(mysql_error());
}

$insert_style_types = "
INSERT INTO $style_types_db_table (id, styleTypeName, styleTypeOwn, styleTypeBOS, styleTypeBOSMethod) VALUES
	(1, 'Beer', 'bcoe', 'Y', 1),
	(2, 'Cider', 'bcoe', 'Y', 3),
	(3, 'Mead', 'bcoe', 'Y', 3)
";

$result = mysql_query($insert_style_types, $brewing) or die(mysql_error());

// Insert current user's info into new "users" and "brewer" table
$insert_users = "INSERT INTO $users_db_table (
	id, 
	user_name, 
	password, 
	userLevel, 
	userQuestion, 
	userQuestionAnswer,
	userCreated
) 
VALUES 
(
	'1',
	'$user_name', 
	'$password', 
	'1', 
	'$userQuestion', 
	'$userQuestionAnswer', 
	NOW());";
// echo "<p>".$insert_users."</p>";
$result = mysql_query($insert_users, $brewing) or die(mysql_error());

$insert_brewer = "
INSERT INTO $brewer_db_table (
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
	brewerJudgeBOS,
	brewerDropOff
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
	NULL,
	NULL
);";
// echo "<p>".$insert_brewer."</p>";
$result = mysql_query($insert_brewer, $brewing) or die(mysql_error());

// Insert a new record into the "archive" table containing the newly created archives names (allows access to archived tables)
$insert_archive = sprintf("INSERT INTO $archive_db_table (id, archiveSuffix) VALUES (%s, %s);", "''", "'".$suffix."'");
// echo "<p>".$insert_archive."</p>";
$result = mysql_query($insert_archive, $brewing) or die(mysql_error());

// Last, log the user in and redirect 
session_destroy();
mysql_select_db($database, $brewing);
$query_login = "SELECT COUNT(*) as 'count' FROM $users_db_table WHERE user_name = '$user_name' AND password = '$password'";
$login = mysql_query($query_login, $brewing) or die(mysql_error());
$row_login = mysql_fetch_assoc($login);

session_start();
	// Authenticate the user
	if ($row_login['count'] == 1) {
  		// Register the loginUsername
 		$_SESSION["loginUsername"] = $user_name;
		// If the username/password combo is OK, relocate to the "protected" content index page
  		header(sprintf("Location: %s", $base_url."index.php?section=admin&go=archive&msg=7"));
  		exit;
		}
	else {
  		// If the username/password combo is incorrect or not found, relocate to the login error page
  		header(sprintf("Location: %s", $base_url."index.php?section=login&msg=1"));
  		session_destroy();
  		exit;
		}
}
?>
