<?php

session_start(); 
require('../paths.php'); 
require(CONFIG.'bootstrap.php');

if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {
	
	include(DB.'output_participants_export.db.php');
	
	if ($go == "csv") { $separator = ","; $extension = ".csv"; }
	if ($go == "tab") { $separator = "\t"; $extension = ".tab"; }
	$contest = str_replace(' ', '_', $_SESSION['contestName']);
	if ($section == "loc") $loc = "_".str_replace(' ', '_', $row_judging['judgingLocName']);
	else $loc = "";
	$date = date("m-d-Y");
	
	$a[] = array('First Name','Last Name','Address','City','State','Zip','Country','Phone','Assignment','Email','Judge ID','Judge Rank','Clubs','Likes','Dislikes');
	
	//echo $query_sql;
	
	do { 
		$brewerFirstName = strtr($row_sql['brewerFirstName'],$html_remove);
		$brewerLastName = strtr($row_sql['brewerLastName'],$html_remove);
		$brewerAddress = strtr($row_sql['brewerAddress'],$html_remove);
		$brewerCity = strtr($row_sql['brewerCity'],$html_remove);
		if ($go == "tab") $assignment = $row_sql['brewerNickname']; else $assignment = $row_sql['brewerAssignment'];
		$a[] = array($brewerFirstName,$brewerLastName,$brewerAddress,$brewerCity,$row_sql['brewerState'],$row_sql['brewerZip'],$row_sql['brewerCountry'],$row_sql['brewerPhone1'],$assignment,$row_sql['brewerEmail'],$row_sql['brewerJudgeID'],str_replace(",",", ",$row_sql['brewerJudgeRank']),$row_sql['brewerClubs'],style_convert($row_sql['brewerJudgeLikes'],'6'),style_convert($row_sql['brewerJudgeDislikes'],'6')); 
	} while ($row_sql = mysql_fetch_assoc($sql));
	
	$filename = $contest."_participants_".$date.$loc.$extension;
	header('Content-type: application/x-msdownload');
	header('Content-Disposition: attachment;filename="'.$filename.'"');
	header('Pragma: no-cache');
	header('Expires: 0');
	$fp = fopen('php://output', 'w');
	foreach ($a as $fields) {
		fputcsv($fp, $fields,$separator);
	}
	fclose($fp);
} else echo "<p>Not Available</p>";
?>