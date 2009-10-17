<?php
// script courtesy of http://www.wlscripting.com/tutorial/37 (modified to accomodate TAB delimited files)
require ('../Connections/config.php');
include ('../includes/db_connect.inc.php');
include ('../includes/url_variables.inc.php'); 

if ($go == "csv") { $separator = ","; $extension = ".csv"; }
if ($go == "tab") { $separator = "\t"; $extension = ".tab"; }

$contest = str_replace(' ', '', $row_contest_info['contestName']);

function parseCSVComments($comments) {
  $comments = str_replace('"', '""', $comments); // First off, escape all " and make them ""
  if(eregi(",", $comments) or eregi("\n", $comments)) { // Check if any commas or new lines
    return '"'.$comments.'"'; // If new lines or commas and escape them
  } else {
    return $comments; // If no new lines or commas just return the value
  }
}
mysql_select_db($database, $brewing);
$query_sql = "SELECT brewBrewerFirstName, brewBrewerLastName, brewCategory, brewSubCategory, id, brewName, brewInfo, brewMead2, brewMead1 FROM brewing"; // Start our query of the database
if ($filter == "paid") $query_sql .= " WHERE brewPaid = 'Y' AND brewReceived = 'Y'"; 
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
		} // End loop
		$data .= join($separator, $row)."\n"; // Create a new row of data and append it to the last row
		$row = ''; // Clear the contents of the $row variable to start a new row
	}
	// Start our output of the CSV
	header("Content-type: application/x-msdownload");
	header("Content-Disposition: attachment; filename=".$contest."_entries".$extension);
	header("Pragma: no-cache");
	header("Expires: 0");
	if ($go == "csv") echo $headers;
	echo $data;
} else {
	// Nothing needed to be output.
	echo 'No data available.';
}
?>