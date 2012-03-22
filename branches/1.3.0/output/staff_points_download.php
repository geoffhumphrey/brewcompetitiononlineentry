<?php
/**
 * Module:      staff_points.php
 * Description: This module calculates the BJCP points for staff, judges, and stewards
 *	            using the guidelines provided by the BJCP at http://www.bjcp.org/rules.php.
 */
session_start(); 
require('../paths.php'); 
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'url_variables.inc.php');
require(DB.'common.db.php');
require(DB.'admin_common.db.php');
require(DB.'judging_locations.db.php');
require(INCLUDES.'staff_points.inc.php');

if ($view == "xml") {
$filename = str_replace(" ","_",$row_contest_info['contestName'])."_BJCP_Points_Report.".$view;
$output = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n"; 
$output .= "<OrgReport>\n";	
$output .= "\t<CompData>\n";
$output .= "\t\t<CompID>".$row_contest_info['contestID']."<CompID>\n";
$output .= "\t\t<CompName>".$row_contest_info['contestName']."<CompName>\n";
$output .= "\t\t<CompDate>".$row_judging['judgingDate']."<CompDate>\n";
$output .= "\t\t<CompEntries>".$total_entries."<CompEntries>\n";
$output .= "\t\t<CompDays>".$totalRows_judging."<CompDays>\n";
$output .= "\t\t<CompSessions><CompSessions>\n";
$output .= "\t\t<CompFlights><CompFlights>\n";
$output .= "\t</CompData>\n";

	if ($totalRows_judges > 0) { 
	do { $j[] = $row_judges['bid']; } while ($row_judges = mysql_fetch_assoc($judges));
	$output .= "\t<BJCPpoints>\n";
	foreach (array_unique($j) as $bid) { 
		$judge_info = explode("^",brewer_info($bid));
		if ($judge_info['5'] == "Y") $assignment = "Judge+BOS";
		else $assignment = "Judge";
    	$output .= "\t\t<JudgeData>\n";
    	$output .= "\t\t\t<JudgeName>".$judge_info['0']." ".$judge_info['1']."</JudgeName>\n";
    	$output .= "\t\t\t<JudgeID>";
			if ($judge_info['4'] != "") $output .= $judge_info['4']; else $output .= "";
		$output .= "</JudgeID>\n";
		$output .= "\t\t\t<JudgeRole>".$assignment."</JudgeRole>\n";
    	$output .= "\t\t\t<JudgePoints>".judge_points($bid,$judge_info['5'])."</JudgePoints>\n";
		$output .= "\t\t\t<NonJudgePoints>0</NonJudgePoints>\n";
    	$output .= "\t\t</JudgeData>\n";
    	}  
	$output .= "\t</BJCPpoints>\n";
    }  
	if ($totalRows_stewards > 0) { 
	do { $s[] = $row_stewards['bid']; } while ($row_stewards = mysql_fetch_assoc($stewards));
	$output .= "\t<NonBJCP>\n";
	foreach (array_unique($s) as $bid) { 
		$steward_info = explode("^",brewer_info($bid));
		$output .= "\t\t<JudgeData>\n";
    	$output .= "\t\t\t<JudgeName>".$steward_info['0']." ".$steward_info['1']."</JudgeName>\n";
    	$output .= "\t\t\t<JudgeID>";
			if ($steward_info['4'] != "") $output .= $steward_info['4']; else $output .= "";
		$output .= "</JudgeID>\n";
		$output .= "\t\t\t<JudgeRole>Steward</JudgeRole>\n";
    	$output .= "\t\t\t<JudgePoints>".steward_points($bid)."</JudgePoints>\n";
		$output .= "\t\t\t<NonJudgePoints>0</NonJudgePoints>\n";
    	$output .= "\t\t</JudgeData>\n";
    	}  
	$output .= "\t<NonBJCP>\n";
	}
	if ($totalRows_staff > 0) { 
	$output .= "\t<NonBJCP>\n";
	do { 
		$output .= "\t\t<JudgeData>\n";
    	$output .= "\t\t\t<JudgeName>".$row_staff['brewerLastName'].", ".$row_staff['brewerFirstName']."</JudgeName>\n";
    	$output .= "\t\t\t<JudgeID>";
			if ($row_staff['brewerJudgeID'] != "") $output .= $row_staff['brewerJudgeID']; else $output .= "";
		$output .= "</JudgeID>\n";
		$output .= "\t\t\t<JudgeRole>Steward</JudgeRole>\n";
    	$output .= "\t\t\t<JudgePoints>".$staff_points."</JudgePoints>\n";
		$output .= "\t\t\t<NonJudgePoints>0</NonJudgePoints>\n";
    	$output .= "\t\t</JudgeData>\n";
    	}  
	while ($row_staff = mysql_fetch_assoc($staff));
	$output .= "\t</NonBJCP>\n";
	}	
	$output .= "\t<SubmissionDate>".date('l j F Y h:i:s A')."</SubmissionDate>\n";
	$output .= "</OrgReport>";
	
	header("Content-Type: application/force-download");
	header("Content-Disposition: attachment; filename=".$filename);
	header("Pragma: no-cache");
	header("Expires: 0");

	echo $output;
	exit();
}
?>