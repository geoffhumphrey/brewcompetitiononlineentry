<?php
session_start(); 
require('../paths.php'); 
require(INCLUDES.'url_variables.inc.php'); 
require(DB.'common.db.php');
require(INCLUDES.'functions.inc.php');

$type = "entries";

if ($bid != "") {
$query_judging = "SELECT judgingLocName FROM judging_locations WHERE id='$bid'";
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
//$query_sql = "SELECT DISTINCT id, brewBrewerFirstName, brewBrewerLastName, brewCategory, brewSubCategory, brewJudgingNumber, brewName, brewInfo, brewMead2, brewMead1, brewMead3, brewBrewerID FROM brewing";

$query_sql = "SELECT DISTINCT id, brewBrewerFirstName, brewBrewerLastName, brewCategory, brewSubCategory, brewName, brewInfo, brewMead2, brewMead1, brewMead3, brewBrewerID, brewJudgingNumber FROM brewing";

if (($filter == "paid") && ($bid == "default"))  $query_sql .= " WHERE brewPaid = '1' AND brewReceived = '1'"; 
if (($filter == "paid") && ($bid != "default"))  $query_sql .= " WHERE brewPaid = '1' AND brewReceived = '1' AND brewJudgingLocation = '$bid'"; 
if (($filter == "nopay") && ($bid == "default")) $query_sql .= " WHERE brewPaid = '0' AND brewReceived = '1'"; 
}
if (($go == "csv") && ($action == "email")) $query_sql .= " ORDER BY brewBrewerID";

	if ($filter == "winners") { 
	if ($row_prefs['prefsCompOrg'] == "N") $query_sql = "SELECT brewing.brewCategory, brewing.brewSubCategory, brewing.brewStyle,  brewing.brewBrewerLastName,  brewing.brewBrewerFirstName,  brewing.brewName, brewing.brewWinner, brewing.brewWinnerPlace, brewing.brewBOSRound, brewing.brewBOSPlace, brewer.brewerFirstName, brewer.brewerLastName FROM brewing LEFT JOIN (brewer) ON ( brewer.brewerFirstName = brewing.brewBrewerFirstName AND brewer.brewerLastName = brewing.brewBrewerLastName ) WHERE brewing.brewWinner = '1'";
	if ($row_prefs['prefsCompOrg'] == "Y") $query_sql = "SELECT id,tableNumber,tableName FROM judging_tables ORDER BY tableNumber ASC";
	}

//echo $query_sql."<br />";

$sql = mysql_query($query_sql, $brewing) or die(mysql_error());
$row_sql = mysql_fetch_assoc($sql);

include (INCLUDES.'scrubber.inc.php');

//if (($go == "csv") && ((($action == "default") || ($action == "hccp")) && ($filter != "winners"))) $a [] = array('FirstName','LastName','Category','SubCategory','JudgingNumber','BrewName','Info','MeadCiderSweetness','MeadCarb');

if (($go == "csv") && ((($action == "default") || ($action == "hccp")) && ($filter != "winners"))) $a [] = array('FirstName','LastName','Category','SubCategory','EntryNumber','BrewName','Info','MeadCiderSweetness','MeadCarb');

//if (($go == "csv") && ($action == "email") && ($filter != "winners")) $a [] = array('BrewerFirstName','BrewerLastName','Email','Category','SubCategory','JudgingNumber','BrewName','Info');

if (($go == "csv") && ($action == "email") && ($filter != "winners")) $a [] = array('BrewerFirstName','BrewerLastName','Email','Category','SubCategory','EntryNumber','BrewName','Info');

if (($go == "csv") && ($action == "default") && ($filter == "winners") && ($row_prefs['prefsCompOrg'] == "N")) $a[] = array('Category','SubCategory','Style','LastName','FirstName','BrewName','Winner','Place','BOSRound','BOSPlace');

if (($go == "csv") && ($action == "default") && ($filter == "winners") && ($row_prefs['prefsCompOrg'] == "Y")) $a[] = array('TableNumber','TableName','Category','SubCategory','Style','Place','BrewerLastName','BrewerFirstName','EntryName');

do {
	//if ((($action == "default") || ($action == "hccp")) && ($filter != "winners")) $a[] = array($row_sql['brewBrewerFirstName'],$row_sql['brewBrewerLastName'],$row_sql['brewCategory'],$row_sql['brewSubCategory'],$row_sql['brewJudgingNumber'],$row_sql['brewName'],strtr($row_sql['brewInfo'], $html_remove),$row_sql['brewMead2'],$row_sql['brewMead1']);
	
	if ((($action == "default") || ($action == "hccp")) && ($filter != "winners")) $a[] = array($row_sql['brewBrewerFirstName'],$row_sql['brewBrewerLastName'],$row_sql['brewCategory'],$row_sql['brewSubCategory'],$row_sql['brewJudgingNumber'],$row_sql['brewName'],strtr($row_sql['brewInfo'], $html_remove),$row_sql['brewMead2'],$row_sql['brewMead1']);
	
	if (($go == "csv") && ($action == "email") && ($filter != "winners")) {
		
		$query_brewer = sprintf("SELECT id,brewerEmail FROM brewer WHERE id='%s'", $row_sql['brewBrewerID']);
		$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
		$row_brewer = mysql_fetch_assoc($brewer);
		
		//$a[] = array($row_sql['brewBrewerFirstName'],$row_sql['brewBrewerLastName'],$row_brewer['brewerEmail'],$row_sql['brewCategory'],$row_sql['brewSubCategory'],$row_sql['brewJudgingNumber'],$row_sql['brewName'],strtr($row_sql['brewInfo'], $html_remove));
		
		$a[] = array($row_sql['brewBrewerFirstName'],$row_sql['brewBrewerLastName'],$row_brewer['brewerEmail'],$row_sql['brewCategory'],$row_sql['brewSubCategory'],$row_sql['brewJudgingNumber'],$row_sql['brewName'],strtr($row_sql['brewInfo'], $html_remove));
		
	}
	 
	if (($row_prefs['prefsCompOrg'] == "N") && ($action == "default") && ($filter == "winners")) $a[] = array($row_sql['brewCategory'],$row_sql['brewSubCategory'],$row_sql['brewStyle'],$row_sql['brewBrewerLastName'],$row_sql['brewBrewerFirstName'],$row_sql['brewName'],$row_sql['brewWinner'],$row_sql['brewWinnerPlace'],$row_sql['brewBOSRound'],$row_sql['brewBOSPlace']);
	
	if (($row_prefs['prefsCompOrg'] == "Y") && ($action == "default") && ($filter == "winners")) {
		$query_scores = sprintf("SELECT eid,scorePlace FROM judging_scores WHERE scoreTable='%s'", $row_sql['brewJudgingNumber']);
		$query_scores .= " AND (scorePlace='1' OR scorePlace='2' OR scorePlace='3' OR scorePlace='4' OR scorePlace='5') ORDER BY scorePlace ASC";	
		$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
		$row_scores = mysql_fetch_assoc($scores);
		$totalRows_scores = mysql_num_rows($scores);
		
		//echo $query_scores."<br>";
		if ($totalRows_scores > 0) {
		do { 
			mysql_select_db($database, $brewing);
			$query_entries = sprintf("SELECT id,brewBrewerID,brewCoBrewer,brewName,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewBrewerFirstName,brewBrewerLastName,brewJudgingNumber FROM brewing WHERE id='%s'", $row_scores['eid']);
			$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
			$row_entries = mysql_fetch_assoc($entries);
			//echo $query_entries."<br>";
			
			$query_brewer = sprintf("SELECT id,brewerClubs FROM brewer WHERE uid='%s'", $row_entries['brewBrewerID']);
			$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
			$row_brewer = mysql_fetch_assoc($brewer);
			
			$a[] = array($row_sql['tableNumber'],$row_sql['tableName'],$row_entries['brewCategory'],$row_entries['brewSubCategory'],$row_entries['brewStyle'],$row_scores['scorePlace'],$row_entries['brewBrewerLastName'],$row_entries['brewBrewerFirstName'],$row_entries['brewName']);
			
		} while ($row_scores = mysql_fetch_assoc($scores)); 
		}
	}
		
} while ($row_sql = mysql_fetch_assoc($sql));

$filename = $contest."_entries_".$filter."_".$date.$loc.$extension;
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