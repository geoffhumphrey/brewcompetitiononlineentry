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
require(INCLUDES.'db_tables.inc.php');
require(DB.'common.db.php');
require(DB.'admin_common.db.php');
require(DB.'judging_locations.db.php');
mysql_select_db($database, $brewing);

// Get total amount of paid and received entries
$total_entries = total_paid_received("judging_scores","default");

function round_down_to_hundred($number) {
    if (strlen($number)<3) { $number = $number;	} 
	else { $number = substr($number, 0, strlen($number)-2) . "00";	}
    return $number;
}

// Get possible organizer points

function total_points($total_entries,$method) {
	switch ($method) {
		case "Organizer":
			if (($total_entries >= 1) && ($total_entries <= 49)) $points = 2;
			elseif (($total_entries >= 50) && ($total_entries <= 99)) $points = 2.5;
			elseif (($total_entries >= 100) && ($total_entries <= 149)) $points = 3;
			elseif (($total_entries >= 150) && ($total_entries <= 199)) $points = 3.5;
			elseif (($total_entries >= 200) && ($total_entries <= 299)) $points = 4;
			elseif (($total_entries >= 300) && ($total_entries <= 399)) $points = 4.5;
			elseif (($total_entries >= 400) && ($total_entries <= 499)) $points = 5;
			elseif ($total_entries >= 500) $points = 6;
			else $points = 0;
		break;
		
		case "Staff":
			if (($total_entries >= 1) && ($total_entries <= 49)) $points = 1;
			elseif (($total_entries >= 50) && ($total_entries <= 99)) $points = 2;
			elseif (($total_entries >= 100) && ($total_entries <= 149)) $points = 3;
			elseif (($total_entries >= 150) && ($total_entries <= 199)) $points = 4;
			
			else {
				$total = round_down_to_hundred($total_entries)/100;
				//$points = $total;
				if ($total >= 2) {
					for($i=1; $i<$total+1; $i++) {
						$points = $i+3;
					}
				}
			}
		break;
		
		case "Judge":
			if (($total_entries >= 1) && ($total_entries <= 49)) $points = 1.5;
			elseif (($total_entries >= 50) && ($total_entries <= 99)) $points = 2;
			elseif (($total_entries >= 100) && ($total_entries <= 149)) $points = 2.5;
			elseif (($total_entries >= 150) && ($total_entries <= 199)) $points = 3;
			elseif (($total_entries >= 200) && ($total_entries <= 299)) $points = 3.5;
			elseif (($total_entries >= 300) && ($total_entries <= 399)) $points = 4;
			elseif (($total_entries >= 400) && ($total_entries <= 499)) $points = 4.55;
			elseif ($total_entries >= 500) $points = 5.5;
			else $points = 0;
		break;
	}
	return $points;
}

// calculate a Judge's points
function judge_points($bid,$bos) { 
	session_start(); 
require('../paths.php'); 
	require(DB.'judging_locations.db.php');
	
	// *minimum* of 1.0 points per competition	
	// *maximum* of 1.5 points per day 
	
	do { $a[] = $row_judging['id']; } while ($row_judging = mysql_fetch_assoc($judging));
	foreach (array_unique($a) as $location) {
		$query_assignments = sprintf("SELECT COUNT(*) as 'count' FROM $judging_assignments_db_table WHERE bid='%s' AND assignLocation='%s' AND assignment='J'", $bid, $location);
		$assignments = mysql_query($query_assignments, $brewing) or die(mysql_error());
		$row_assignments = mysql_fetch_assoc($assignments);
		
		if ($row_assignments['count'] >= 3) $b[] = 1.5;
		else $b[] = 1.0;
	}
	
	$points = array_sum($b);
	if (($bos == "Y") && ($bos_elegible == "Y")) $points = $points + 0.5; else $points = $points;
	return $points;
	
}
	
// calculate a Steward's points
function steward_points($bid) {
	session_start(); 
require('../paths.php'); 
	require(DB.'judging_locations.db.php');
	
	// *minimum* of 0.5 points per day	
	// *maximum* of 1.0 points per competition
	
	do { $a[] = $row_judging['id']; } while ($row_judging = mysql_fetch_assoc($judging));
	foreach (array_unique($a) as $location) {
		$query_assignments = sprintf("SELECT COUNT(*) as 'count' FROM $judging_assignments_db_table WHERE bid='%s' AND assignLocation='%s' AND assignment='S'", $bid, $location);
		$assignments = mysql_query($query_assignments, $brewing) or die(mysql_error());
		$row_assignments = mysql_fetch_assoc($assignments);
		
		if ($row_assignments['count'] >= 2) $b[] = 1.0;
		else $b[] = 0.5;
	}
	
	$points = array_sum($b);
	if ($points >= 1.0) $points = 1.0; else $points = $points;
	return $points;
}

// Get maximum point values based upon number of entries
$organ_points = number_format(total_points($total_entries,"Organizer"), 1);
$staff_points = total_points($total_entries,"Staff");
$judge_points = number_format(total_points($total_entries,"Judge"), 1);

// Divide total staff point pool by amount of staff, round down
$query_assignments = "SELECT COUNT(*) as 'count' FROM $brewer_db_table WHERE brewerAssignment='X'";
$assignments = mysql_query($query_assignments, $brewing) or die(mysql_error());
$row_assignments = mysql_fetch_assoc($assignments);
$staff_points = number_format(floor(floor(($staff_points/$row_assignments['count'])) * 10 + 5) * .1, 1);

// Judge points
$query_judges = "SELECT bid FROM $judging_assignments_db_table WHERE assignment='J'";
$judges = mysql_query($query_judges, $brewing) or die(mysql_error());
$row_judges = mysql_fetch_assoc($judges);
$totalRows_judges = mysql_num_rows($judges);

// Steward points
$query_stewards = "SELECT bid FROM $judging_assignments_db_table WHERE assignment='S'";
$stewards = mysql_query($query_stewards, $brewing) or die(mysql_error());
$row_stewards = mysql_fetch_assoc($stewards);
$totalRows_stewards = mysql_num_rows($stewards);

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