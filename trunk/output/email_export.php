<?php
session_start(); 
require('../paths.php'); 
require(CONFIG.'bootstrap.php');
require(INCLUDES.'scrubber.inc.php');
require(LIB.'output.lib.php');

if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {
	
	include(DB.'output_email_export.db.php');
	
	echo $query_sql;
	
	$separator = ","; 
	$extension = ".csv";
	$contest = str_replace(' ', '_', $_SESSION['contestName']);
	if ($filter == "default") $type = "All_Participants";
	else $type = $filter;
	if ($section == "loc") $loc = "_".str_replace(' ', '_', $row_judging['judgingLocName']);
	else $loc = "";
	$date = date("Y-m-d");
	$filename = ltrim(filename($contest)."_Email_Addresses".filename($type)."_".$date.$loc.$extension,"_");
		
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
	
	/*
	header('Content-type: application/x-msdownload');
	header('Content-Disposition: attachment;filename="'.$filename.'"');
	header('Pragma: no-cache');
	header('Expires: 0');
	$fp = fopen('php://output', 'w');
	
	foreach ($a as $fields) {
		fputcsv($fp,$fields,$separator);
	}
	fclose($fp);
	*/
}

else echo "<p>Not available.</p>";
?>