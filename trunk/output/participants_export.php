<?php

session_start(); 
require('../paths.php'); 
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php');
require(DB.'common.db.php');
require(DB.'admin_common.db.php');
require(INCLUDES.'headers.inc.php');
require(INCLUDES.'constants.inc.php');
require(INCLUDES.'scrubber.inc.php');
if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {
if ($bid != "") {
$query_judging = "SELECT judgingLocName FROM $judging_locations_db_table WHERE id='$bid'";
$judging = mysql_query($query_judging, $brewing) or die(mysql_error());
$row_judging = mysql_fetch_assoc($judging);

$query_brewerID = "SELECT * FROM $brewing_db_table WHERE brewJudgingLocation='$bid'";
$brewerID = mysql_query($query_brewerID, $brewing) or die(mysql_error());
$row_brewerID = mysql_fetch_assoc($brewerID);

}

if ($go == "csv") { $separator = ","; $extension = ".csv"; }
if ($go == "tab") { $separator = "\t"; $extension = ".tab"; }
$contest = str_replace(' ', '_', $_SESSION['contestName']);
if ($section == "loc") $loc = "_".str_replace(' ', '_', $row_judging['judgingLocName']);
else $loc = "";
$date = date("m-d-Y");

function  parseCSVComments($comments) {
  $comments = str_replace('"', '""', $comments); // First off escape all " and make them ""
  if(eregi(",", $comments) or eregi("\n", $comments)) { // Check if I have any commas or new lines
    return '"'.$comments.'"'; // If I have new lines or commas escape them
  } else {
    return $comments; // If no new lines or commas just return the value
  }
}

mysql_select_db($database, $brewing);
if     ($section == "loc") 
$query_sql = "
SELECT DISTINCT
brewer.brewerFirstName, 
brewer.brewerLastName, 
brewer.brewerAddress, 
brewer.brewerCity, 
brewer.brewerState, 
brewer.brewerZip, 
brewer.brewerCountry, 
brewer.brewerPhone1, 
brewer.brewerNickname, 
brewer.brewerEmail, 
brewer.brewerJudgeID, 
brewer.brewerJudgeRank, 
brewer.brewerClubs, 
brewer.brewerJudgeLikes, 
brewer.brewerJudgeDislikes,
brewer.brewerAssignment,
brewer.id,
brewing.brewBrewerID,
brewing.brewJudgingLocation
FROM $brewer_db_table, $brewing_db_table
WHERE brewer.uid = brewing.brewbrewerID
AND brewing.brewJudgingLocation = '$bid'
ORDER BY brewer.brewerLastName ASC
";

else $query_sql = "
SELECT 
brewerFirstName, 
brewerLastName, 
brewerAddress, 
brewerCity, 
brewerState, 
brewerZip,
brewerCountry, 
brewerPhone1, 
brewerNickname, 
brewerEmail, 
brewerJudgeID, 
brewerJudgeRank, 
brewerClubs,
brewerAssignment,
brewerJudgeLikes, 
brewerJudgeDislikes 
FROM $brewer_db_table
ORDER BY brewerLastName ASC
"; // Start our query of the database

$sql = mysql_query($query_sql, $brewing) or die(mysql_error());
$row_sql = mysql_fetch_assoc($sql);
$a[] = array('First Name','Last Name','Address','City','State','Zip','Country','Phone','Assignment','Email','Judge ID','Judge Rank','Clubs','Likes','Dislikes');

//echo $query_sql;

do { 
$brewerFirstName = strtr($row_sql['brewerFirstName'],$html_remove);
$brewerLastName = strtr($row_sql['brewerLastName'],$html_remove);
$brewerAddress = strtr($row_sql['brewerAddress'],$html_remove);
$brewerCity = strtr($row_sql['brewerCity'],$html_remove);
if ($go == "tab") $assignment = $row_sql['brewerNickname']; else $assignment = $row_sql['brewerAssignment'];
$a[] = array($brewerFirstName,$brewerLastName,$brewerAddress,$brewerCity,$row_sql['brewerState'],$row_sql['brewerZip'],$row_sql['brewerCountry'],$row_sql['brewerPhone1'],$assignment,$row_sql['brewerEmail'],$row_sql['brewerJudgeID'],$row_sql['brewerJudgeRank'],$row_sql['brewerClubs'],style_convert($row_sql['brewerJudgeLikes'],'6'),style_convert($row_sql['brewerJudgeDislikes'],'6')); 
} while ($row_sql = mysql_fetch_assoc($sql));

$filename = $contest."_participants_".$date.$loc.$extension;
header('Content-type: application/x-msdownload');
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Pragma: no-cache');
header('Expires: 0');
$fp = fopen('php://output', 'w');
foreach ($a as $fields) {
    fputcsv($fp, $fields,$separator);
}
fclose($fp);
}
?>