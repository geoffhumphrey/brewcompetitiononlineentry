<?php

session_start(); 
require('../paths.php'); 
require(CONFIG.'bootstrap.php');

if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {
$type = "entries";

if ($bid != "") {
	$query_judging = "SELECT judgingLocName FROM $judging_locations_db_table WHERE id='$bid'";
	$judging = mysql_query($query_judging, $brewing) or die(mysql_error());
	$row_judging = mysql_fetch_assoc($judging);
}

if ($go == "csv") { $separator = ","; $extension = ".csv"; }
if ($go == "tab") { $separator = chr(9); $extension = ".tab"; }
$contest = str_replace(' ', '_', $_SESSION['contestName']);
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
	if (($filter == "paid") && ($bid == "default") && ($view == "not_received"))  $query_sql .= " WHERE brewPaid = '1' AND (brewReceived <> 1 OR brewReceived IS NULL)"; 
	if (($filter == "paid") && ($bid != "default"))  $query_sql .= " WHERE brewPaid = '1' AND brewReceived = '1' AND brewJudgingLocation = '$bid'"; 
	if (($filter == "nopay") && ($bid == "default") && ($view == "default")) $query_sql .= " WHERE (brewPaid <> 1 OR brewPaid IS NULL) AND brewReceived = '1'";
	if (($filter == "nopay") && ($bid == "default") && ($view == "all")) $query_sql .= " WHERE (brewPaid <> 1 OR brewPaid IS NULL)"; 
}

if (($go == "csv") && ($action == "email")) $query_sql .= " ORDER BY brewBrewerLastName,brewBrewerFirstName,id ASC";
if (($go == "csv") && ($action == "all") && ($filter == "all")) $query_sql .= " ORDER BY id ASC";

if ($filter == "winners") $query_sql = "SELECT id,tableNumber,tableName FROM $judging_tables_db_table ORDER BY tableNumber ASC";

//echo $query_sql."<br />";

$sql = mysql_query($query_sql, $brewing) or die(mysql_error());
$row_sql = mysql_fetch_assoc($sql);
$num_fields = mysql_num_fields($sql);

function filename($input) {
	if ($input == "default") return "";
	else return "_".ucfirst($input);
}

$filename = $contest."_Entries".filename($filter).filename($action).filename($view).filename($date).$loc.$extension;


if (($go == "csv") && ($action == "all") && ($filter == "all")) { 
	$headers = array(); 
	for ($i = 0; $i < $num_fields; $i++) {     
		   $headers[] = mysql_field_name($sql,$i);
		}
		$headers[] .= "Table";
		$headers[] .= "Flight";
		$headers[] .= "Round";
		$headers[] .= "Score";
		$headers[] .= "Place";
		$headers[] .= "BOS Place";
		$headers[] .= "Style Type";
		$headers[] .= "Location";
	
	$fp = fopen('php://output', 'w'); 
	
	if ($fp && $sql) {
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Pragma: no-cache');
		header('Expires: 0');
		fputcsv($fp, $headers);
		do {
			$fields1 = array_values($row_sql);
			
			$query_scores = sprintf("SELECT scoreEntry,scorePlace,scoreType FROM $judging_scores_db_table WHERE eid='%s'",$row_sql['id']);
			$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
			$row_scores = mysql_fetch_assoc($scores);
			
			$query_flight = sprintf("SELECT * FROM $judging_flights_db_table WHERE flightEntryID='%s'",$row_sql['id']);
			$flight = mysql_query($query_flight, $brewing) or die(mysql_error());
			$row_flight = mysql_fetch_assoc($flight);
			
			$query_bos = sprintf("SELECT scorePlace FROM $judging_scores_bos_db_table WHERE eid='%s'",$row_sql['id']);
			$bos = mysql_query($query_bos, $brewing) or die(mysql_error());
			$row_bos = mysql_fetch_assoc($bos);
			$totalRows_bos = mysql_num_rows($bos);
			if ($totalRows_bos > 0) $bos_place = $row_bos['scorePlace']; else $bos_place = "";
			
			$style_type = style_type($row_scores['scoreType'],2,"bcoe");
			$location = explode("^",get_table_info(1,"location",$row_flight['flightTable'],"default","default"));
			$table_info = explode("^",get_table_info(1,"basic",$row_flight['flightTable'],"default","default"));
			$table_name = sprintf("%02s",$table_info[0]).": ".$table_info[1];

			$fields2 = array($table_name,$row_flight['flightNumber'],$row_flight['flightRound'],sprintf("%02s",$row_scores['scoreEntry']),$row_scores['scorePlace'],$bos_place,$style_type,$location[2]);
			
			$fields = array_merge($fields1,$fields2);
			
			fputcsv($fp, $fields);
		}
		while ($row_sql = mysql_fetch_assoc($sql));
    die; 
	} 
}

else {

if (($go == "csv") && ($action == "hccp") && ($filter != "winners")) $a[] = array('FirstName','LastName','Category','SubCategory','EntryNumber','BrewName','Info','MeadCiderSweetness','MeadCarb','MeadStrength');
if (($go == "csv") && (($action == "default") || ($action == "email")) && ($filter != "winners")) $a [] = array('First Name','Last Name','Email','Category','Sub-Category','Entry Number','Judging Number','Entry Name','Info');
if (($go == "csv") && ($action == "default") && ($filter == "winners")) $a[] = array('Table Number','Table Name','Category','Sub-Category','Style','Place','Last Name','First Name','Email','Address','City','State/Province','Zip/Postal Code','Country','Phone','Entry Name','Club','Co Brewer');

do {
$brewerFirstName = strtr($row_sql['brewBrewerFirstName'],$html_remove);
$brewerLastName = strtr($row_sql['brewBrewerLastName'],$html_remove);
$brewName = strtr($row_sql['brewName'],$html_remove);
$brewInfo = strtr($row_sql['brewInfo'],$html_remove);
$entryNo = sprintf("%04s",$row_sql['id']);
	
	
// Winner Downloads
	
	if (($action == "default") && ($filter == "winners") && ($_SESSION['prefsWinnerMethod'] == 0)) {
		
		// BY TABLE		
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
				
				$query_brewer = sprintf("SELECT id,brewerFirstName,brewerLastName,brewerClubs,brewerEmail,brewerAddress,brewerCity,brewerState,brewerZip,brewerCountry,brewerPhone1 FROM $brewer_db_table WHERE uid='%s'", $row_entries['brewBrewerID']);
				$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
				$row_brewer = mysql_fetch_assoc($brewer);
				
				$a[] = array($row_sql['tableNumber'],$row_sql['tableName'],$row_entries['brewCategory'],$row_entries['brewSubCategory'],$row_entries['brewStyle'],$row_scores['scorePlace'],strtr($row_brewer['brewerLastName'],$html_remove),strtr($row_brewer['brewerFirstName'],$html_remove),$row_brewer['brewerEmail'],$row_brewer['brewerAddress'],$row_brewer['brewerCity'],$row_brewer['brewerState'],$row_brewer['brewerZip'],$row_brewer['brewerCountry'],$row_brewer['brewerPhone1'],strtr($row_entries['brewName'],$html_remove),$row_brewer['brewerClubs'],$row_entries['brewCoBrewer']);
				
			} while ($row_scores = mysql_fetch_assoc($scores)); 
		}
		
		
		
	} // end if (($action == "default") && ($filter == "winners"))
	
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

if (($action == "default") && ($filter == "winners") && ($_SESSION['prefsWinnerMethod'] > 0)) {
	// BY CATEGORY
	if ($_SESSION['prefsWinnerMethod'] == 1) {
		
		$query_styles = "SELECT brewStyleGroup FROM $styles_db_table WHERE brewStyleActive='Y' ORDER BY brewStyleGroup ASC";
		$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
		$row_styles = mysql_fetch_assoc($styles);
		$totalRows_styles = mysql_num_rows($styles);
		do { $z[] = $row_styles['brewStyleGroup']; } while ($row_styles = mysql_fetch_assoc($styles));
		
		foreach (array_unique($z) as $style) {
			$query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategorySort='%s' AND brewReceived='1'", $brewing_db_table,  $style);
			$entry_count = mysql_query($query_entry_count, $brewing) or die(mysql_error());
			$row_entry_count = mysql_fetch_assoc($entry_count);
			
			$query_score_count = sprintf("SELECT  COUNT(*) as 'count' FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND a.eid = b.id AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style);
			if (($action == "print") && ($view == "winners")) $query_score_count .= " AND a.scorePlace IS NOT NULL";
			if (($action == "default") && ($view == "default")) $query_score_count .= " AND a.scorePlace IS NOT NULL";
			$score_count = mysql_query($query_score_count, $brewing) or die(mysql_error());
			$row_score_count = mysql_fetch_assoc($score_count);
			
			if ($row_score_count['count'] > "0")   {
				
				$query_scores = sprintf("SELECT a.scoreTable, a.scorePlace, a.scoreEntry, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerEmail, c.brewerClubs, c.brewerAddress, c.brewerState, c.brewerCity, c.brewerZip, c.brewerPhone1, c.brewerCountry FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND a.eid = b.id AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style);
				$query_scores .= " AND a.scorePlace IS NOT NULL";
				$query_scores .= " ORDER BY b.brewCategory,a.scorePlace ASC";
				//echo $query_scores."<br>";
				$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
				$row_scores = mysql_fetch_assoc($scores);
				$totalRows_scores = mysql_num_rows($scores);
						
				do { 
					$query_table_name = sprintf("SELECT tableName,tableNumber from %s WHERE id = '%s'",$judging_tables_db_table,$row_scores['scoreTable']);
					$table_name = mysql_query($query_table_name, $brewing) or die(mysql_error());
					$row_table_name = mysql_fetch_assoc($table_name);
				
					$a[] = array($row_table_name['tableNumber'],strtr($row_table_name['tableName'],$html_remove),$row_scores['brewCategory'],$row_scores['brewSubCategory'],$row_scores['brewStyle'],$row_scores['scorePlace'],strtr($row_scores['brewerLastName'],$html_remove),strtr($row_scores['brewerFirstName'],$html_remove),$row_scores['brewerEmail'],$row_scores['brewerAddress'],$row_scores['brewerCity'],$row_scores['brewerState'],$row_scores['brewerZip'],$row_scores['brewerCountry'],$row_scores['brewerPhone1'],strtr($row_scores['brewName'],$html_remove),$row_scores['brewerClubs'],$row_scores['brewCoBrewer']);
				} while ($row_scores = mysql_fetch_assoc($scores));
			}
		}	
	} // end if ($_SESSION['prefsWinnerMethod'] == 1)
	
	
	// BY SUB-CATEGORY
	
	if ($_SESSION['prefsWinnerMethod'] == 2) {
		
		$query_styles = "SELECT brewStyleGroup,brewStyleNum,brewStyle FROM $styles_db_table WHERE brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum ASC";
		$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
		$row_styles = mysql_fetch_assoc($styles);
		$totalRows_styles = mysql_num_rows($styles);
		do { $b[] = $row_styles['brewStyleGroup']."-".$row_styles['brewStyleNum']; } while ($row_styles = mysql_fetch_assoc($styles));
		
		foreach (array_unique($b) as $style) {
			$style = explode("-",$style);
			
			$query_score_count = sprintf("SELECT  COUNT(*) as 'count' FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND b.brewSubCategory='%s' AND a.eid = b.id AND a.scorePlace IS NOT NULL AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style[0], $style[1]);
			$score_count = mysql_query($query_score_count, $brewing) or die(mysql_error());
			$row_score_count = mysql_fetch_assoc($score_count);
			
			//echo $row_score_count['count'];
			//echo $query_score_count;
			if ($row_score_count['count'] > 0)   {
			
				$query_scores = sprintf("SELECT a.scoreTable, a.scorePlace, a.scoreEntry, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerClubs, c.brewerAddress, c.brewerState, c.brewerCity, c.brewerZip, c.brewerPhone1, c.brewerCountry FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND b.brewSubCategory='%s' AND a.eid = b.id  AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style[0],$style[1]);
				$query_scores .= " AND a.scorePlace IS NOT NULL";
				$query_scores .= " ORDER BY b.brewCategory,b.brewSubCategory,a.scorePlace";
				//echo $query_scores;
				$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
				$row_scores = mysql_fetch_assoc($scores);
				$totalRows_scores = mysql_num_rows($scores);
						
				do { 
				$style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
				if ($row_scores['brewCategorySort'] > 28) $style_long = style_convert($row_scores['brewCategorySort'],1);
				else $style_long = $row_scores['brewStyle'];
				
					$query_table_name = sprintf("SELECT tableName,tableNumber from %s WHERE id = '%s'",$judging_tables_db_table,$row_scores['scoreTable']);
					$table_name = mysql_query($query_table_name, $brewing) or die(mysql_error());
					$row_table_name = mysql_fetch_assoc($table_name);
				
					$a[] = array($row_table_name['tableNumber'],strtr($row_table_name['tableName'],$html_remove),$row_scores['brewCategory'],$row_scores['brewSubCategory'],$style_long,$row_scores['scorePlace'],strtr($row_scores['brewerLastName'],$html_remove),strtr($row_scores['brewerFirstName'],$html_remove),$row_scores['brewerEmail'],$row_scores['brewerAddress'],$row_scores['brewerCity'],$row_brewer['brewerState'],$row_scores['brewerZip'],$row_scores['brewerCountry'],$row_scores['brewerPhone1'],strtr($row_scores['brewName'],$html_remove),$row_scores['brewerClubs'],$row_scores['brewCoBrewer']);
				} while ($row_scores = mysql_fetch_assoc($scores));
			}
		}
	} // end if ($_SESSION['prefsWinnerMethod'] == 2)
}

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

}

else echo "<p>Not available.</p>";

?>