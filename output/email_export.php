<?php
session_start(); 
require('../paths.php'); 
require(CONFIG.'bootstrap.php');
require(INCLUDES.'scrubber.inc.php');
require(LIB.'output.lib.php');

if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {
	
	include(DB.'output_email_export.db.php');
	
	//echo $query_sql;
	
	$separator = ","; 
	$extension = ".csv";
	$contest = str_replace(' ', '_', $_SESSION['contestName']);
	$contest = ltrim(filename($contest),"_");
	if ($filter == "default") $type = "All_Participants";
	else $type = $filter;
	if ($section == "loc") {
		 $loc = str_replace(' ', '_', $row_judging['judgingLocName']);
		 $loc = filename($loc);
	}
	else $loc = "";
	$date = date("Y-m-d");
	
	// Appropriately name the CSV file for each type of download
	if ($filter == "judges") 				$filename = $contest."_Assigned_Judge_Email_Addresses_".$date.$loc.$extension;
	elseif ($filter == "stewards") 			$filename = $contest."_Assigned_Steward_Email_Addresses_".$date.$loc.$extension;
	elseif ($filter == "staff") 			$filename = $contest."_Assigned_Staff_Email_Addresses_".$date.$loc.$extension; 
	elseif ($filter == "avail_judges") 		$filename = $contest."_Available_Judge_Email_Addresses_".$date.$loc.$extension;
	elseif ($filter == "avail_stewards") 	$filename = $contest."_Available_Steward_Email_Addresses_".$date.$loc.$extension;
	else  									$filename = $contest."_All_Participant_Email_Addresses_".$date.$loc.$extension;
	
	// Set the header row of the CSV for each type of download		
	if (($filter == "judges") || ($filter == "avail_judges")) 			$a [] = array('First Name','Last Name','Email','Rank','BJCP ID','Availability','Likes','Dislikes','Entries In...');
	elseif (($filter == "stewards") || ($filter == "avail_stewards")) 	$a [] = array('First Name','Last Name','Email','Availability','Entries In...');
	elseif ($filter == "staff") 										$a [] = array('First Name','Last Name','Email','Entries In...');
	else 																$a [] = array('First Name','Last Name','Email','Address','City','State/Province','Zip','Country','Phone','Club','Entries In...');
	
	do {
		
		$brewerFirstName = strtr($row_sql['brewerFirstName'],$html_remove);
		$brewerLastName = strtr($row_sql['brewerLastName'],$html_remove);
		if ($filter == "default") {
			$brewerAddress = strtr($row_sql['brewerAddress'],$html_remove);
			$brewerCity = strtr($row_sql['brewerCity'],$html_remove);
			if ($row_sql['brewerCountry'] == "United States") $phone = format_phone_us($row_sql['brewerPhone1']); else $phone = $row_sql['brewerPhone1'];
		}
		
		$judge_avail = judge_steward_availability($row_sql['brewerJudgeLocation'],2);
		$steward_avail = judge_steward_availability($row_sql['brewerStewardLocation'],2);
		
	
		if (($filter == "judges") || ($filter == "avail_judges")) 			$a [] = array($brewerFirstName,$brewerLastName,$row_sql['brewerEmail'],str_replace(",",", ",$row_sql['brewerJudgeRank']),strtoupper(strtr($row_sql['brewerJudgeID'],$bjcp_num_replace)),$judge_avail,style_convert($row_sql['brewerJudgeLikes'],'6'),style_convert($row_sql['brewerJudgeDislikes'],'6'),judge_entries($row_sql['uid'],0));
		elseif (($filter == "stewards") || ($filter == "avail_stewards")) 	$a [] = array($brewerFirstName,$brewerLastName,$row_sql['brewerEmail'],$steward_avail,judge_entries($row_sql['uid'],0));
		elseif ($filter == "staff") 										$a [] = array($brewerFirstName,$brewerLastName,$row_sql['brewerEmail'],judge_entries($row_sql['uid'],0));
		else 																$a [] = array($brewerFirstName,$brewerLastName,$row_sql['brewerEmail'],$brewerAddress,$brewerCity,$row_sql['brewerState'],$row_sql['brewerZip'],$row_sql['brewerCountry'],$phone,$row_sql['brewerClubs'],judge_entries($row_sql['uid'],0));
		
	} while ($row_sql = mysql_fetch_assoc($sql));  
	
	
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