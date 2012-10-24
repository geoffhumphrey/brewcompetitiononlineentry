<?php
session_start(); 
require('../paths.php'); 
require(INCLUDES.'url_variables.inc.php'); 
require(INCLUDES.'db_tables.inc.php');
require(DB.'common.db.php');
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'scrubber.inc.php');
$type = "entries";

if ($bid != "") {
	$query_judging = "SELECT judgingLocName FROM $judging_locations_db_table WHERE id='$bid'";
	$judging = mysql_query($query_judging, $brewing) or die(mysql_error());
	$row_judging = mysql_fetch_assoc($judging);
}

if ($go == "csv") { $separator = ","; $extension = ".csv"; }
if ($go == "tab") { $separator = chr(9); $extension = ".tab"; }
$contest = str_replace(' ', '_', $row_contest_info['contestName']);
if ($section == "loc") $loc = "_".str_replace(' ', '_', $row_judging['judgingLocName']);
else $loc = "";
$date = date("m-d-Y");

mysql_select_db($database, $brewing);

// Note: the order of the columns is set to the specifications set by HCCP for import

if ($filter != "winners") {
	
	if ($filter == "all") 	$query_sql = "SELECT * FROM $brewing_db_table";
	else $query_sql = "SELECT DISTINCT id, brewBrewerFirstName, brewBrewerLastName, brewCategory, brewSubCategory, brewName, brewInfo, brewMead2, brewMead1, brewMead3, brewBrewerID, brewJudgingNumber FROM $brewing_db_table";
	
	if (($filter == "paid") && ($bid == "default") && ($view == "default"))  $query_sql .= " WHERE brewPaid = '1' AND brewReceived = '1'"; 
	if (($filter == "paid") && ($bid == "default") && ($view == "all"))  $query_sql .= " WHERE brewPaid = '1'"; 
	if (($filter == "paid") && ($bid != "default"))  $query_sql .= " WHERE brewPaid = '1' AND brewReceived = '1' AND brewJudgingLocation = '$bid'"; 
	if (($filter == "nopay") && ($bid == "default") && ($view == "default")) $query_sql .= " WHERE (brewPaid <> 1 OR brewPaid IS NULL) AND brewReceived = '1'";
	if (($filter == "nopay") && ($bid == "default") && ($view == "all")) $query_sql .= " WHERE (brewPaid <> 1 OR brewPaid IS NULL)"; 
}

if (($go == "csv") && ($action == "email")) $query_sql .= " ORDER BY brewBrewerLastName,brewBrewerFirstName,id ASC";

if ($filter == "winners") $query_sql = "SELECT id,tableNumber,tableName FROM $judging_tables_db_table ORDER BY tableNumber ASC";

//echo $query_sql."<br />";

$sql = mysql_query($query_sql, $brewing) or die(mysql_error());
$row_sql = mysql_fetch_assoc($sql);
$num_fields = mysql_num_fields($sql); 

include (INCLUDES.'scrubber.inc.php');
$filename = $contest."_entries_".$filter."_".$action."_".$date.$loc.$extension;


if (($go == "csv") && ($action == "all") && ($filter == "all")) { 
	$headers = array(); 
	for ($i = 0; $i < $num_fields; $i++) {     
		   $headers[] = mysql_field_name($sql , $i); 
		} 
	$fp = fopen('php://output', 'w'); 
	
	
	if ($fp && $sql) {
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		header('Pragma: no-cache');
		header('Expires: 0');
		fputcsv($fp, $headers);
		while ($row = mysql_fetch_row($sql))  {
			fputcsv($fp, array_values($row));
		}
    die; 
	} 
}

else {

if (($go == "csv") && ($action == "hccp") && ($filter != "winners")) $a [] = array('FirstName','LastName','Category','SubCategory','EntryNumber','BrewName','Info','MeadCiderSweetness','MeadCarb','MeadStrength');


if (($go == "csv") && (($action == "default") || ($action == "email")) && ($filter != "winners")) $a [] = array('First Name','Last Name','Email','Category','Sub-Category','Entry Number','Judging Number','Entry Name','Info');

if (($go == "csv") && ($action == "default") && ($filter == "winners")) $a[] = array('Table Number','Table Name','Category','Sub-Category','Style','Place','Last Name','First Name','Email','Entry Name','Club','Co Brewer');

do {
$brewerFirstName = strtr($row_sql['brewBrewerFirstName'],$html_remove);
$brewerLastName = strtr($row_sql['brewBrewerLastName'],$html_remove);
$brewName = strtr($row_sql['brewName'],$html_remove);
$brewInfo = strtr($row_sql['brewInfo'],$html_remove);
$entryNo = sprintf("%04s",$row_sql['id']);
	// Winner download
	
	if (($action == "default") && ($filter == "winners")) {
		$query_scores = sprintf("SELECT eid,scorePlace FROM $judging_scores_db_table WHERE scoreTable='%s' AND scorePlace IS NOT NULL ORDER BY scorePlace ASC", $row_sql['id']);
		$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
		$row_scores = mysql_fetch_assoc($scores);
		$totalRows_scores = mysql_num_rows($scores);
		
		//echo $query_scores."<br>";
		if ($totalRows_scores > 0) {
			do { 
				mysql_select_db($database, $brewing);
				$query_entries = sprintf("SELECT id,brewBrewerID,brewCoBrewer,brewName,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewBrewerFirstName,brewBrewerLastName,brewJudgingNumber FROM $brewing_db_table WHERE id='%s'", $row_scores['eid']);
				$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
				$row_entries = mysql_fetch_assoc($entries);
				
				$query_brewer = sprintf("SELECT id,brewerFirstName,brewerLastName,brewerClubs,brewerEmail FROM $brewer_db_table WHERE uid='%s'", $row_entries['brewBrewerID']);
				$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
				$row_brewer = mysql_fetch_assoc($brewer);
				
				$a[] = array($row_sql['tableNumber'],$row_sql['tableName'],$row_entries['brewCategory'],$row_entries['brewSubCategory'],$row_entries['brewStyle'],$row_scores['scorePlace'],strtr($row_brewer['brewerLastName'],$html_remove),strtr($row_brewer['brewerFirstName'],$html_remove),$row_brewer['brewerEmail'],strtr($row_entries['brewName'],$html_remove),$row_brewer['brewerClubs'],$row_entries['brewCoBrewer']);
				
			} while ($row_scores = mysql_fetch_assoc($scores)); 
		}
	}	
	
	// No participant email addresses
	
	if (($action == "hccp") && ($filter != "winners")) 
	$a[] = array($brewerFirstName,$brewerLastName,$row_sql['brewCategory'],$row_sql['brewSubCategory'],$entryNo,$brewName,$brewInfo,$row_sql['brewMead2'],$row_sql['brewMead1'],$row_sql['brewMead3']);
	
	// With email addresses of participants.
	
	if ((($action == "default") || ($action == "email")) && ($go == "csv") && ($filter != "winners")) {
		
		$query_brewer = sprintf("SELECT id,brewerEmail FROM $brewer_db_table WHERE uid='%s'", $row_sql['brewBrewerID']);
		$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
		$row_brewer = mysql_fetch_assoc($brewer);
		
		$a[] = array($brewerFirstName,$brewerLastName,$row_brewer['brewerEmail'],$row_sql['brewCategory'],$row_sql['brewSubCategory'],$entryNo,readable_judging_number($row_sql['brewCategory'],$row_sql['brewJudgingNumber']),$brewName,$brewInfo);
	}

} while ($row_sql = mysql_fetch_assoc($sql));

header('Content-type: application/x-msdownload');
header('Content-Disposition: attachment;filename='.$filename);
header('Pragma: no-cache');
header('Expires: 0');
$fp = fopen('php://output', 'w');

foreach ($a as $fields) {
    fputcsv($fp,$fields,$separator);
}
fclose($fp);

}
?>