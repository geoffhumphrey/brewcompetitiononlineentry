<?php
session_start(); 
require('../paths.php'); 
require(INCLUDES.'url_variables.inc.php'); 
require(INCLUDES.'db_tables.inc.php');
require(DB.'common.db.php');
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'scrubber.inc.php');
require(INCLUDES.'constants.inc.php');
if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {
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

$contest = str_replace(' ', '_', $_SESSION['contestName']);

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
if (($filter == "judges") && ($section == "admin")) $query_sql = "SELECT a.brewerEmail, a.brewerFirstName, a.brewerLastName, a.brewerJudgeLocation, a.brewerStewardLocation, a.uid, a.brewerJudgeRank, a.brewerJudgeID, a.brewerJudgeLikes, a.brewerJudgeDislikes, b.uid FROM $brewer_db_table a, $staff_db_table b WHERE b.staff_judge='1' AND a.uid=b.uid";
if (($filter == "stewards") && ($section == "admin")) $query_sql = "SELECT a.brewerEmail, a.brewerFirstName, a.brewerLastName, a.uid, a.brewerJudgeRank, a.brewerJudgeID, a.brewerJudgeLocation, a.brewerStewardLocation, a.brewerJudgeLikes, a.brewerJudgeDislikes, b.uid FROM $brewer_db_table a, $staff_db_table b WHERE b.staff_steward='1' AND a.uid=b.uid";
if (($filter == "staff") && ($section == "admin")) $query_sql = "SELECT a.brewerEmail, a.brewerFirstName, a.brewerLastName, a.uid, a.brewerJudgeRank, a.brewerJudgeID, a.brewerJudgeLocation, a.brewerStewardLocation, b.uid FROM $brewer_db_table a, $staff_db_table b WHERE b.staff_staff='1' AND a.uid=b.uid";

//if (($filter == "judges") && ($section == "loc"))   $query_sql .= " WHERE brewerAssignment='J' AND brewerJudgeAssignedLocation='$bid'";
//if (($filter == "stewards") && ($section == "loc")) $query_sql .= " WHERE brewerAssignment='S' AND brewerStewardAssignedLocation='$bid'";

//if ($filter == "avail_judges") $query_sql = "SELECT uid, brewerFirstName, brewerLastName, brewerEmail, brewerJudge, brewerJudgeRank, brewerJudgeID, brewerSteward, brewerJudgeLocation, brewerStewardLocation, brewerJudgeLikes, brewerJudgeDislikes FROM $brewer_db_table ORDER BY brewerLastName ASC";
//if ($filter == "avail_stewards") $query_sql = "SELECT uid, brewerFirstName, brewerLastName, brewerEmail, brewerJudge, brewerJudgeRank, brewerJudgeID, brewerSteward, brewerJudgeLocation, brewerStewardLocation, brewerJudgeLikes, brewerJudgeDislikes FROM $brewer_db_table ORDER BY brewerLastName ASC";
if ($filter == "avail_judges")   $query_sql = "SELECT uid, brewerFirstName, brewerLastName, brewerEmail, brewerJudge, brewerJudgeRank, brewerJudgeID, brewerSteward, brewerJudgeLocation, brewerStewardLocation FROM $brewer_db_table WHERE brewerJudge='Y' ORDER BY brewerLastName ASC";
elseif ($filter == "avail_stewards") $query_sql = "SELECT uid, brewerFirstName, brewerLastName, brewerEmail, brewerJudge, brewerJudgeRank, brewerJudgeID, brewerSteward, brewerJudgeLocation, brewerStewardLocation FROM $brewer_db_table WHERE brewerSteward='Y' ORDER BY brewerLastName ASC";
else $query_sql = "SELECT uid, brewerFirstName, brewerLastName, brewerEmail, brewerJudge, brewerJudgeRank, brewerJudgeID, brewerSteward, brewerJudgeLocation, brewerStewardLocation FROM $brewer_db_table ORDER BY brewerLastName ASC";
$sql = mysql_query($query_sql, $brewing) or die(mysql_error());
$row_sql = mysql_fetch_assoc($sql);
//echo $query_sql."<br>";


//$judge_avail = $row_sql['brewerJudgeLocation'];
//$steward_avail = $row_sql['brewerStewardLocation'];

if (($filter == "judges") || ($filter == "avail_judges")) $a [] = array('First Name','Last Name','Email','Rank','BJCP ID','Availability','Likes','Dislikes','Entries In...');
elseif ($filter == "staff") array('First Name','Last Name','Email','Entries In...');
else $a [] = array('First Name','Last Name','Email','Judge?','Judge Availability','Steward?','Steward Availability','Entries In...');

do {
$brewerFirstName = strtr($row_sql['brewerFirstName'],$html_remove);
$brewerLastName = strtr($row_sql['brewerLastName'],$html_remove);

$judge_avail = judge_steward_availability($row_sql['brewerJudgeLocation'],2);
$steward_avail = judge_steward_availability($row_sql['brewerStewardLocation'],2);

	if (($filter == "judges") || ($filter == "avail_judges")) {
		$a [] = array($brewerFirstName,$brewerLastName,$row_sql['brewerEmail'],str_replace(",",", ",$row_sql['brewerJudgeRank']),strtoupper(strtr($row_sql['brewerJudgeID'],$bjcp_num_replace)),$judge_avail,style_convert($row_sql['brewerJudgeLikes'],'6'),style_convert($row_sql['brewerJudgeDislikes'],'6'),judge_entries($row_sql['uid'],0));
		}
	elseif ($filter == "staff") $a [] = array($brewerFirstName,$brewerLastName,$row_sql['brewerEmail'],judge_entries($row_sql['uid'],0));
	else $a [] = array($brewerFirstName,$brewerLastName,$row_sql['brewerEmail'],$row_sql['brewerJudge'],$judge_avail,$row_sql['brewerSteward'],$steward_avail,judge_entries($row_sql['uid'],0));
} while ($row_sql = mysql_fetch_assoc($sql)); 

//print_r($a);

$filename = $contest."_email_addresses_".$filter."_".$date.$loc.$extension;
header('Content-type: application/x-msdownload');
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Pragma: no-cache');
header('Expires: 0');
$fp = fopen('php://output', 'w');

foreach ($a as $fields) {
    fputcsv($fp,$fields,$separator);
}
fclose($fp);

}

else echo "<p>Not available.</p>";
?>