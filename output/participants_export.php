<?php
// script courtesy of http://www.wlscripting.com/tutorial/37 (modified to accomodate TAB delimited files)
session_start(); 
require('../paths.php'); 
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'url_variables.inc.php'); 
require(DB.'common.db.php');

if ($bid != "") {
$query_judging = "SELECT judgingLocName FROM judging_locations WHERE id='$bid'";
$judging = mysql_query($query_judging, $brewing) or die(mysql_error());
$row_judging = mysql_fetch_assoc($judging);

$query_brewerID = "SELECT * FROM brewing WHERE brewJudgingLocation='$bid'";
$brewerID = mysql_query($query_brewerID, $brewing) or die(mysql_error());
$row_brewerID = mysql_fetch_assoc($brewerID);

}

if ($go == "csv") { $separator = ","; $extension = ".csv"; }
if ($go == "tab") { $separator = "\t"; $extension = ".tab"; }
$contest = str_replace(' ', '_', $row_contest_info['contestName']);
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
brewer.id,
brewing.brewBrewerID,
brewing.brewJudgingLocation
FROM brewer, brewing
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
brewerJudgeLikes, 
brewerJudgeDislikes 
FROM brewer 
ORDER BY brewerLastName ASC
"; // Start our query of the database

//echo $query_sql;

$sql = mysql_query($query_sql, $brewing) or die(mysql_error());
$numberFields = mysql_num_fields($sql); // Find out how many fields we are fetching

if($numberFields) { // Check if we need to output anything
	for($i=0; $i<$numberFields; $i++) {
		$head[] = mysql_field_name($sql, $i); // Create the headers for each column, this is the field name in the database
	}
	$headers = join($separator, $head)."\n"; // Make our first row in the CSV

	while($info = mysql_fetch_object($sql)) {
		foreach($head as $fieldName) { // Loop through the array of headers as we fetch the data
			$row[] =  parseCSVComments($info->$fieldName);
		} // End loop
		if ($row[10] == "0") $row[10] = null;
		$data .= join($separator, $row)."\n"; // Create a new row of data and append it to the last row
		$row = ''; // Clear the contents of the $row variable to start a new row
	}
	
	// Start our output
	header("Content-type: application/x-msdownload");
	header("Content-Disposition: attachment;  filename=".$contest."_participants_".$date.$loc.$extension);
	header("Pragma: no-cache");
	header("Expires: 0");
	if ($go == "csv") echo $headers;
	echo $data;
} else {
	// Nothing needed to be output. Put an error message here or something.
	echo 'No data available for this file.';
}

?>