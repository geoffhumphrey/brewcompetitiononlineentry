<?php
// script courtesy of http://www.wlscripting.com/tutorial/37 (modified to accomodate TAB delimited files)
require('output.bootstrap.php');
include(INCLUDES.'url_variables.inc.php'); 
include(INCLUDES.'db_connect.inc.php');

if ($bid != "") {
$query_judging = "SELECT judgingLocName FROM judging_locations WHERE id='$bid'";
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
$date = date("m-d-Y");

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
$query_sql = "SELECT brewerFirstName, brewerLastName, brewerEmail FROM brewer";
if (($filter == "judges") && ($section == "all"))   $query_sql .= " WHERE brewerAssignment='J'";
if (($filter == "judges") && ($section == "loc"))   $query_sql .= " WHERE brewerAssignment='J' AND brewerJudgeAssignedLocation='$bid'";
if (($filter == "stewards") && ($section == "all")) $query_sql .= " WHERE brewerAssignment='S'";
if (($filter == "stewards") && ($section == "loc")) $query_sql .= " WHERE brewerAssignment='S' AND brewerStewardAssignedLocation='$bid'";
$sql = mysql_query($query_sql, $brewing) or die(mysql_error());
$numberFields = mysql_num_fields($sql); // Find out how many fields we are fetching
//echo $query_sql;

if($numberFields) { // Check if we need to output anything
	for($i=0; $i<$numberFields; $i++) {
		$head[] = mysql_field_name($sql, $i); // Create the headers for each column, this is the field name in the database
	}
	$headers = join($separator, $head)."\n"; // Make our first row in the CSV

	while($info = mysql_fetch_object($sql)) {
		foreach($head as $fieldName) { // Loop through the array of headers as we fetch the data
			$row[] =  parseCSVComments($info->$fieldName);
		} // End loop
		$data .= join($separator, $row)."\n"; // Create a new row of data and append it to the last row
		$row = ''; // Clear the contents of the $row variable to start a new row
	}
	// Start our output of the CSV
	header("Content-type: application/x-msdownload");
	header("Content-Disposition: attachment; filename=".$contest."_".$type."_emails_".$date.$loc.$extension);
	header("Pragma: no-cache");
	header("Expires: 0");
	echo $headers;
	echo $data;
} else {
	// Nothing needed to be output. Put an error message here or something.
	echo 'No data available.';
}

?>