<?php
// script courtesy of http://www.wlscripting.com/tutorial/37 (modified to accomodate TAB delimited files)
require ('../Connections/config.php');
include ('../includes/db_connect.inc.php');
include ('../includes/url_variables.inc.php'); 

$type = "entries";

if ($bid != "") {
$query_judging = "SELECT judgingLocName FROM judging WHERE id='$bid'";
$judging = mysql_query($query_judging, $brewing) or die(mysql_error());
$row_judging = mysql_fetch_assoc($judging);
}

if ($go == "csv") { $separator = ","; $extension = ".csv"; }
if ($go == "tab") { $separator = "\t"; $extension = ".tab"; }
$contest = str_replace(' ', '_', $row_contest_info['contestName']);
if ($section == "loc") $loc = "_".str_replace(' ', '_', $row_judging['judgingLocName']);
else $loc = "";
$date = date("m-d-Y");

function parseCSVComments($comments) {
  include ('../includes/scrubber.inc.php');
  $comments = strtr($comments, $html_remove);
  $comments = str_replace('"', '""', $comments); // First off, escape all " and make them ""
  $comments = preg_replace("/[\n\r]/","",$comments); 
  if(eregi(",", $comments) or eregi("\n", $comments) or eregi("\t", $comments) or eregi("\r", $comments) or eregi("\v", $comments)) { // Check if any commas or new lines
    return '"'.$comments.'"'; // If new lines or commas and escape them
  } else {
    return $comments; // If no new lines or commas just return the value
  }
}
mysql_select_db($database, $brewing);

// Note: the order of the columns is set to the specifications set by HCCP for import

if ($filter != "winners") {
$query_sql = "SELECT brewBrewerFirstName, brewBrewerLastName,";
if (($action == "default") || ($action == "hccp")) $query_sql .= " brewCategory, brewSubCategory, id, brewName, brewInfo, brewMead2, brewMead1 ";
if ($action == "default") $query_sql .= ", brewMead3"; 
if ($action == "email") $query_sql .= " brewBrewerID";
$query_sql .= " FROM brewing";
if (($filter == "paid") && ($bid == "default"))  $query_sql .= " WHERE brewPaid = 'Y' AND brewReceived = 'Y'"; 
if (($filter == "paid") && ($bid != "default"))  $query_sql .= " WHERE brewPaid = 'Y' AND brewReceived = 'Y' AND brewJudgingLocation = '$bid'"; 
if (($filter == "nopay") && ($bid == "default")) $query_sql .= " WHERE brewPaid = 'N' OR brewPaid = '' AND brewReceived = 'Y'"; 
}

if ($filter == "winners") $query_sql = "SELECT brewing.brewCategory, brewing.brewSubCategory, brewing.brewStyle,  brewing.brewBrewerLastName,  brewing.brewBrewerFirstName,  brewing.brewName, brewer.brewerClubs, brewer.brewerAddress, brewer.brewerCity, brewer.brewerState, brewer.brewerZip, brewer.brewerEmail,brewing.brewWinner, brewing.brewWinnerPlace, brewing.brewBOSRound, brewing.brewBOSPlace, brewer.brewerFirstName, brewer.brewerLastName FROM brewing LEFT JOIN (brewer) ON ( brewer.brewerFirstName = brewing.brewBrewerFirstName AND brewer.brewerLastName = brewing.brewBrewerLastName ) WHERE brewing.brewWinner = 'Y'";
//echo $query_sql;

$sql = mysql_query($query_sql, $brewing) or die(mysql_error());
$numberFields = mysql_num_fields($sql); // Find out how many fields we are fetching

if($numberFields) { // Check if we need to output anything
	for($i=0; $i<$numberFields; $i++) {
		$head[] = mysql_field_name($sql, $i); // Create the headers for each column, this is the field name in the database
	}
	$headers = join(',', $head)."\n"; // Make our first row in the CSV

	while($info = mysql_fetch_object($sql)) {
		foreach($head as $fieldName) { // Loop through the array of headers as we fetch the data
			$row[] = parseCSVComments($info->$fieldName);
			if ($action == "email") { 
			$query_brewer = "SELECT brewerEmail FROM brewer WHERE id='$row[2]'";
			$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
			$row_brewer = mysql_fetch_assoc($brewer);
			}
		} // End loop
		if ($action == "email") $row[2] = $row_brewer['brewerEmail'];
		$data .= join($separator, $row)."\n"; // Create a new row of data and append it to the last row
		$row = ''; // Clear the contents of the $row variable to start a new row
	}
	// Start our output of the CSV

	header("Content-type: application/x-msdownload");
	header("Content-Disposition: attachment; filename=".$contest."_entires_".$date.$loc.$extension);
	header("Pragma: no-cache");
	header("Expires: 0");
	if ($go == "csv") echo $headers;
	echo $data;
} else {
	// Nothing needed to be output.
	echo 'No data available.';
}
?>