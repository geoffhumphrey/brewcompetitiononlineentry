<?php

session_start(); 
require('../paths.php'); 
require(CONFIG.'bootstrap.php');
require(LIB.'output.lib.php');

if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {

	$type = "entries";
	
	if ($go == "csv") { $separator = ","; $extension = ".csv"; }
	if ($go == "tab") { $separator = chr(9); $extension = ".tab"; }
	$contest = str_replace(' ', '_', $_SESSION['contestName']);
	if ($section == "loc") $loc = "_".str_replace(' ', '_', $row_judging['judgingLocName']);
	else $loc = "";
	$date = date("m-d-Y");
	$filename = ltrim(filename($contest)."_Entries".filename($filter).filename($action).filename($view).filename($date).$loc.$extension,"_");
	
	include(DB.'output_entries_export.db.php');
	
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
				include(DB.'output_entries_export_extend.db.php');
				$fields1 = array_values($row_sql);
				$fields2 = array($table_name,$row_flight['flightNumber'],$row_flight['flightRound'],sprintf("%02s",$row_scores['scoreEntry']),$row_scores['scorePlace'],$bos_place,$style_type,$location[2]);
				$fields = array_merge($fields1,$fields2);
				
				fputcsv($fp, $fields);
			}
			while ($row_sql = mysql_fetch_assoc($sql));
		die; 
		} 
	}
	
	
	else {
		//first name, last name, email, category, subcategory, entry #, judging #, brewinfo, brewmead1, brewmead2, brewmead3, address, city, state, zip
		if (($go == "csv") && ($action == "hccp") && ($filter != "winners")) $a[] = array('First Name','Last Name','Email','Category','Sub Category','Entry Number','Judging Number','Brew Name','Special Ingredients','Sweetness','Carb','Strength');
		if (($go == "csv") && (($action == "default") || ($action == "email")) && ($filter != "winners")) $a[] = array('First Name','Last Name','Email','Category','Sub Category','Entry Number','Judging Number','Brew Name','Special Ingredients','Sweetness','Carb','Strength','Address','City','State/Province','Zip/Postal Code','Country');
		if (($go == "csv") && ($action == "default") && ($filter == "winners")) $a[] = array('Table Number','Table Name','Category','Sub-Category','Style','Place','Last Name','First Name','Email','Address','City','State/Province','Zip/Postal Code','Country','Phone','Entry Name','Club','Co Brewer');
		
		do {
			$brewerFirstName = strtr($row_sql['brewBrewerFirstName'],$html_remove);
			$brewerLastName = strtr($row_sql['brewBrewerLastName'],$html_remove);
			$brewName = strtr($row_sql['brewName'],$html_remove);
			$brewInfo = strtr($row_sql['brewInfo'],$html_remove);
			$entryNo = sprintf("%04s",$row_sql['id']);
			$judgingNo = readable_judging_number($row_sql['brewCategory'],$row_sql['brewJudgingNumber']);
			$brewer_info = explode("^", brewer_info($row_sql['brewBrewerID']));
			
			// Winner Downloads
			if (($action == "default") && ($filter == "winners") && ($_SESSION['prefsWinnerMethod'] == 0)) {
				include(DB.'output_entries_export_winner.db.php');
			} // end if (($action == "default") && ($filter == "winners"))
			
			// No participant email addresses
			if (($action == "hccp") && ($filter != "winners")) 
			$a[] = array($brewerFirstName,$brewerLastName,$row_sql['brewCategory'],$row_sql['brewSubCategory'],$entryNo,$brewName,$brewInfo,$row_sql['brewMead2'],$row_sql['brewMead1'],$row_sql['brewMead3']);
			
			// With email addresses of participants.
			if ((($action == "default") || ($action == "email")) && ($go == "csv") && ($filter != "winners")) {
				$a[] = array($brewerFirstName,$brewerLastName,$brewer_info[6],$row_sql['brewCategory'],$row_sql['brewSubCategory'],$entryNo,$judgingNo,$brewName,$brewInfo,$row_sql['brewMead1'],$row_sql['brewMead2'],$row_sql['brewMead3'],$brewer_info[10],$brewer_info[11],$brewer_info[12],$brewer_info[13],$brewer_info[14]);
			}
			
		} while ($row_sql = mysql_fetch_assoc($sql));
		
		if (($action == "default") && ($filter == "winners") && ($_SESSION['prefsWinnerMethod'] > 0)) {
			include(DB.'output_entries_export_winner.db.php');
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