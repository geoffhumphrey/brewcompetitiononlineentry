<?php
session_start(); 
require('../paths.php'); 
require(INCLUDES.'url_variables.inc.php'); 
require(INCLUDES.'db_tables.inc.php');
require(DB.'common.db.php');
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'scrubber.inc.php');

if ($bid != "") {
$query_judging = "SELECT judgingLocName FROM $judging_locations_db_table WHERE id='$bid'";
$judging = mysql_query($query_judging, $brewing) or die(mysql_error());
$row_judging = mysql_fetch_assoc($judging);
}

$separator = ","; 
$extension = ".csv";
if ($filter == "judges") $type = "judge";
elseif ($filter == "stewards") $type = "steward";
else $type = "all_participant";
if ($section == "loc") $loc = "_".str_replace(' ', '_', $row_judging['judgingLocName']);
else $loc = "";
$date = date("Y-m-d");

$contest = str_replace(' ', '_', $row_contest_info['contestName']);

function parseCSVComments($comments) {
  $comments = str_replace('"', '""', $comments); // First off, escape all " and make them ""
  $comments = preg_replace("/[\n\r]/","",$comments); 
  if(eregi(",", $comments) or eregi("\n", $comments) or eregi("\t", $comments) or eregi("\r", $comments) or eregi("\v", $comments)) { // Check if any commas or new lines
    return '"'.$comments.'"'; // If new lines or commas and escape them
  } else {
    return $comments; // If no new lines or commas just return the value
  }
}

mysql_select_db($database, $brewing);
$query_sql = "SELECT brewerFirstName, brewerLastName, brewerEmail"; 
if ($filter == "judges") $query_sql .= ", brewerJudgeLikes, brewerJudgeDislikes";
$query_sql .= " FROM $brewer_db_table";
if (($filter == "judges") && ($section == "admin"))   $query_sql .= " WHERE brewerAssignment='J'";
if (($filter == "judges") && ($section == "loc"))   $query_sql .= " WHERE brewerAssignment='J' AND brewerJudgeAssignedLocation='$bid'";
if (($filter == "stewards") && ($section == "admin")) $query_sql .= " WHERE brewerAssignment='S'";
if (($filter == "stewards") && ($section == "loc")) $query_sql .= " WHERE brewerAssignment='S' AND brewerStewardAssignedLocation='$bid'";
$query_sql .= " ORDER BY brewerLastName ASC";
$sql = mysql_query($query_sql, $brewing) or die(mysql_error());
$row_sql = mysql_fetch_assoc($sql);

if ($filter == "judges") $a [] = array('First Name','Last Name','Email','Likes','Dislikes');
else $a [] = array('First Name','Last Name','Email');

do {
$brewerFirstName = strtr($row_sql['brewerFirstName'],$html_remove);
$brewerLastName = strtr($row_sql['brewerLastName'],$html_remove);
	if ($filter == "judges") $a [] = array($brewerFirstName,$brewerLastName,$row_sql['brewerEmail'],style_convert($row_sql['brewerJudgeLikes'],'6'),style_convert($row_sql['brewerJudgeDislikes'],'6'));
	else $a [] = array($brewerFirstName,$brewerLastName,$row_sql['brewerEmail']);
} while ($row_sql = mysql_fetch_assoc($sql)); 


$filename = $contest."_email_addresses_".$filter."_".$date.$loc.$extension;
header('Content-type: application/x-msdownload');
header('Content-Disposition: attachment;filename='.$filename);
header('Pragma: no-cache');
header('Expires: 0');
$fp = fopen('php://output', 'w');

foreach ($a as $fields) {
    fputcsv($fp,$fields,$separator);
}
fclose($fp);
?>