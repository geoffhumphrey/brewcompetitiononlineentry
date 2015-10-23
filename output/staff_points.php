<?php

/**
 * Module:      staff_points.php
 * Description: This module calculates the BJCP points for staff, judges, and stewards
 *	            using the guidelines provided by the BJCP at http://www.bjcp.org/rules.php.
 * Revision History: 
 * - fixed point output errors for judges and BOS judges
 * - programming now accounts for multiple roles (e.g., judge/staff, steward/staff, bos judge/staff, etc.)
 * - XML output is fully compliant with the BJCP Database Interface Specifications 
 *   -- http://www.bjcp.org/it/docs/BJCP%20Database%20XML%20Interface%20Spec%202.1.pdf
 */

session_start(); 
require('../paths.php');
require(CONFIG.'bootstrap.php');

if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {

include(DB.'judging_locations.db.php');
include(DB.'styles.db.php');
include(DB.'admin_common.db.php');
include(LIB.'output.lib.php');

// Get total amount of paid and received entries
$total_entries = total_paid_received("judging_scores","default");
//$total_entries = 750;

// Figure out whether BOS Judge Points are awarded or not
// "BOS points may only be awarded if a competition has at least 30 entries in at least five beer and/or three mead/cider categories."
$beer_styles[] = 0;
$mead_styles[] = 0;
$cider_styles[] = 0;

do {

	if ($row_styles2['brewStyleType'] = "Cider") { $beer_syles[] = 0; $mead_styles[] = 0; $cider_styles[] = 1; }
	elseif ($row_styles2['brewStyleType'] = "Mead") { $beer_syles[] = 0; $mead_styles[] = 1; $cider_styles[] = 0; }
	else { $beer_syles[] = 1; $mead_styles[] = 0; $cider_styles[] = 0; }
	
} while ($row_styles2 = mysql_fetch_assoc($styles2));

$beer_styles_total = array_sum($beer_styles);
$mead_styles_total = array_sum($mead_styles);
$cider_styles_total = array_sum($cider_styles);
$mead_cider_total = $mead_styles_total+$cider_styles_total;

if (($total_entries >= 30) && (($beer_styles_total >= 5) || ($mead_cider_total >= 3))) $bos_judge_points = 0.5;
else $bos_judge_points = 0.0;

include(DB.'output_staff_points.db.php');

if ($view == "pdf") {

	$filename = str_replace(" ","_",$_SESSION['contestName']).'_BJCP_Points_Report.'.$view;
	require(CLASSES.'fpdf/html_table.php');
	$pdf=new PDF();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',16);
	$pdf->Write(5,strtr($_SESSION['contestName'],$html_remove).' BJCP Points Report');
	$pdf->SetFont('Arial','',10);	
	
	$html = '<br><br><strong>BJCP Competition ID</strong>: '.$_SESSION['contestID'].'<br>';
	$html .= '<br><strong>Total Entries</strong>: '.$total_entries.'<br>';
	$html .= '<br><strong>Total Days</strong>: '.total_days().'<br>';
	$html .= '<br><strong>Total Sessions</strong>: '.total_sessions().'<br>';
	$html .= '<br><strong>Total Flights</strong>: '.total_flights().' (includes Best of Show)<br>';
	
		if ($totalRows_organizer > 0) { 
		$html .= '<br><br><strong>Organizer</strong><br>';
		$html .= '<table border="1">';
		$html .= '<tr>';
		$html .= '<td width="300" align="center" bgcolor="#cccccc">Name</td>';
		$html .= '<td width="150" align="center" bgcolor="#cccccc">BJCP ID</td>';
		$html .= '<td width="150" align="center" bgcolor="#cccccc">Points</td>';
		$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<td width="300">'.$row_org['brewerLastName'].", ".$row_org['brewerFirstName'].'</td>';
			$html .= '<td width="150">';
				if ($row_org['brewerJudgeID'] != "") $html .=  strtoupper(strtr($row_org['brewerJudgeID'],$bjcp_num_replace)); else $html .= '&nbsp;';
			$html .= '</td>';
			$html .= '<td width="150">'.$organ_points.'</td>';
			$html .= '</tr>';
		$html .= '</table>';
		}
		
		if ($totalRows_judges > 0) { 
		$html .= '<br><br><strong>Judges</strong><br>';
		$html .= '<table border="1">';
		$html .= '<tr>';
		$html .= '<td width="300" align="center"  bgcolor="#cccccc">Name</td>';
		$html .= '<td width="150" align="center"  bgcolor="#cccccc">BJCP ID</td>';
		$html .= '<td width="150" align="center"  bgcolor="#cccccc">Points</td>';
		$html .= '</tr>';
		do { $j[] = $row_judges['uid']; } while ($row_judges = mysql_fetch_assoc($judges));
		sort($j);
		foreach (array_unique($j) as $uid) { 
			$judge_info = explode("^",brewer_info($uid));
			$judge_points = judge_points($uid,$judge_info['5']);
			$bos_judge = bos_points($uid);
			if ($judge_points > 0) {
				$judge_name = strtr(ucwords(strtolower($judge_info['1'])),$html_remove).", ".strtr(ucwords(strtolower($judge_info['0'])),$html_remove);
				$html .= '<tr>';
				$html .= '<td width="300">'.$judge_name;
				if ($bos_judge) $html .= " (BOS Judge)";
				$html .= '</td>';
				$html .= '<td width="150">';
					if (validate_bjcp_id($judge_info['4'])) $html .= strtoupper(strtr($judge_info['4'],$bjcp_num_replace)); else $html .= '&nbsp;';
				$html .= '</td>';
				if ($bos_judge) $html .= '<td width="150">'.(judge_points($uid,$judge_info['5'])+$bos_judge_points).'</td>';
				else $html .= '<td width="150">'.judge_points($uid,$judge_info['5']).'</td>';
				$html .= '</tr>';
				}
			}
		foreach (array_unique($bos_judge_no_assignment) as $uid) { 
			$judge_info = explode("^",brewer_info($uid));
			if (!empty($uid)) {
				$judge_name = strtr(ucwords(strtolower($judge_info['1'])),$html_remove).", ".strtr(ucwords(strtolower($judge_info['0'])),$html_remove);
				$html .= '<tr>';
				$html .= '<td width="300">'.$judge_name;
				$html .= " (BOS Judge)";
				$html .= '</td>';
				$html .= '<td width="150">';
					if (validate_bjcp_id($judge_info['4'])) $html .= strtoupper(strtr($judge_info['4'],$bjcp_num_replace)); else $html .= '&nbsp;';
				$html .= '</td>';
				$html .= '<td width="150">1.0</td>';
				$html .= '</tr>';
			}
		}
		$html .= '</table>';
		} 
		
		if ($totalRows_stewards > 0) { 
		$html .= '<br><br><strong>Stewards</strong><br>';
		$html .= '<table border="1">';
		$html .= '<tr>';
		$html .= '<td width="300" align="center" bgcolor="#cccccc">Name</td>';
		$html .= '<td width="150" align="center" bgcolor="#cccccc">BJCP ID</td>';
		$html .= '<td width="150" align="center" bgcolor="#cccccc">Points</td>';
		$html .= '</tr>';
		do { $s[] = $row_stewards['uid']; } while ($row_stewards = mysql_fetch_assoc($stewards));
		foreach (array_unique($s) as $uid) { 
			$steward_info = explode("^",brewer_info($uid));
			$steward_points = steward_points($uid);
			if ($steward_points > 0) {
				$steward_name = ucwords(strtolower($steward_info['1'])).", ".ucwords(strtolower($steward_info['0']));
				$html .= '<tr>';
				$html .= '<td width="300">'.$steward_name.'</td>';
				$html .= '<td width="150">';
					if (validate_bjcp_id($steward_info['4'])) $html .= strtoupper(strtr($steward_info['4'],$bjcp_num_replace)); else $html .= '&nbsp;';
				$html .= '</td>';
				$html .= '<td width="150">'.steward_points($uid).'</td>';
				$html .= '</tr>';
				}
			}
		$html .= '</table>';
		}
		
		if ($totalRows_staff > 0) { 
		$html .= '<br><br><strong>Staff</strong><br>';
		$html .= '<table border="1">';
		$html .= '<tr>';
		$html .= '<td width="300" align="center" bgcolor="#cccccc">Name</td>';
		$html .= '<td width="150" align="center" bgcolor="#cccccc">BJCP ID</td>';
		$html .= '<td width="150" align="center" bgcolor="#cccccc">Points</td>';
		$html .= '</tr>';
		do { $st[] = $row_staff['uid']; } while ($row_staff = mysql_fetch_assoc($staff));
		foreach (array_unique($st) as $uid) { 
			if (array_sum($st_running_total) < $staff_points_total) {
				$staff_info = explode("^",brewer_info($uid));
				$staff_name = ucwords(strtolower($staff_info['1'])).", ".ucwords(strtolower($staff_info['0']));
				$html .= '<tr>';
				$html .= '<td width="300">'.$staff_name.'</td>';
				$html .= '<td width="150">';
					if (validate_bjcp_id($staff_info['4'])) $html .= strtoupper(strtr($staff_info['4'],$bjcp_num_replace)); else $html .= '&nbsp;';
				$html .= '</td>';
				$html .= '<td width="150">';
				if ((array_sum($st_running_total) <= $staff_points_total) && ($staff_points < $organ_points)) $html .= $staff_points;
				else $html .= $organ_points;
				$html .= '</td>';
				$html .= '</tr>';
			}
			}  
		$html .= '</table>';
		}
		$html = iconv('UTF-8', 'windows-1252', $html);	
		$pdf->WriteHTML($html);
		$pdf->Output($filename,'D');
		
}

// ------------------------------------------------------------------------------------------------------
# BJCP XML Output
// ------------------------------------------------------------------------------------------------------

// BJCP XML Specification: http://www.bjcp.org/it/docs/BJCP%20Database%20XML%20Interface%20Spec%202.1.pdf


if ($view == "xml") {
	$total_days = total_days();
	do { $j[] = $row_judges['uid']; } while ($row_judges = mysql_fetch_assoc($judges));	
	do { $s[] = $row_stewards['uid']; } while ($row_stewards = mysql_fetch_assoc($stewards));	
	do { $st[] = $row_staff['uid']; } while ($row_staff = mysql_fetch_assoc($staff));
	do { $o[] = $row_organizer['uid']; } while ($row_organizer = mysql_fetch_assoc($organizer));
	$filename = str_replace(" ","_",$_SESSION['contestName'])."_BJCP_Points_Report.xml";
	$output = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n"; 
	$output .= "<OrgReport>\n";	
	$output .= "\t<CompData>\n";
	$output .= "\t\t<CompID>".$_SESSION['contestID']."</CompID>\n";
	$output .= "\t\t<CompName>".$_SESSION['contestName']."</CompName>\n";
	$output .= "\t\t<CompDate>".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'], $_SESSION['prefsTimeFormat'], "system", "date-no-gmt")."</CompDate>\n";
	$output .= "\t\t<CompEntries>".$total_entries."</CompEntries>\n";
	$output .= "\t\t<CompDays>".$total_days."</CompDays>\n";
	$output .= "\t\t<CompSessions>".total_sessions()."</CompSessions>\n";
	$output .= "\t\t<CompFlights>".total_flights()."</CompFlights>\n";
	$output .= "\t</CompData>\n";
	$output .= "\t<BJCPpoints>\n";
	//$st_running_total[] = 0;
	
		// Judges with a properly formatted BJCP IDs in the system
		foreach (array_unique($j) as $uid) { 
		$judge_info = explode("^",brewer_info($uid));
		$judge_points = judge_points($uid,$judge_info['5']);
		if ($judge_points > 0) {
			$bos_judge = bos_points($uid);
			if ($bos_judge) $assignment = "Judge+BOS";
			else $assignment = "Judge";
				if (($judge_info['0'] != "") && ($judge_info['1'] != "") && (validate_bjcp_id($judge_info['4']))) { 
						$judge_name = strtr(ucwords(strtolower($judge_info['0'])),$html_remove)." ".strtr(ucwords(strtolower($judge_info['1'])),$html_remove);
						$output .= "\t\t<JudgeData>\n";
						$output .= "\t\t\t<JudgeName>".$judge_name."</JudgeName>\n";
						$output .= "\t\t\t<JudgeID>".strtoupper(strtr($judge_info['4'],$bjcp_num_replace))."</JudgeID>\n";
						$output .= "\t\t\t<JudgeRole>".$assignment."</JudgeRole>\n";
						if ($bos_judge) $output .= "\t\t\t<JudgePts>".number_format((judge_points($uid,$judge_info['5'])+$bos_judge_points),1)."</JudgePts>\n";
						else $output .= "\t\t\t<JudgePts>".judge_points($uid,$judge_info['5'])."</JudgePts>\n";
						$output .= "\t\t\t<NonJudgePts>0.0</NonJudgePts>\n";
						$output .= "\t\t</JudgeData>\n";
				}
			}
		}
		
		// Loner BOS Judges (no assignment to any table)
		foreach (array_unique($bos_judge_no_assignment) as $uid) { 
			$judge_info = explode("^",brewer_info($uid));
			if (($judge_info['0'] != "") && ($judge_info['1'] != "") && (validate_bjcp_id($judge_info['4']))) { 
				if (!empty($uid)) {
					$judge_name = strtr(ucwords(strtolower($judge_info['0'])),$html_remove)." ".strtr(ucwords(strtolower($judge_info['1'])),$html_remove);
					$output .= "\t\t<JudgeData>\n";
					$output .= "\t\t\t<JudgeName>".$judge_name."</JudgeName>\n";
					$output .= "\t\t\t<JudgeID>".strtoupper(strtr($judge_info['4'],$bjcp_num_replace))."</JudgeID>\n";
					$output .= "\t\t\t<JudgeRole>BOS Judge</JudgeRole>\n";
					$output .= "\t\t\t<JudgePts>1.0</JudgePts>\n";
					$output .= "\t\t\t<NonJudgePts>0.0</NonJudgePts>\n";
					$output .= "\t\t</JudgeData>\n";
				}
			}
		}
		
		// Stewards with a properly formatted BJCP IDs in the system
		foreach (array_unique($s) as $uid) { 
		$steward_info = explode("^",brewer_info($uid));
		$steward_points = steward_points($uid);
		if ($steward_points > 0) {
			$steward_name = ucwords(strtolower($steward_info['0']))." ".ucwords(strtolower($steward_info['1']));
			if (($steward_info['0'] != "") && ($steward_info['1'] != "") && (validate_bjcp_id($steward_info['4']))) {
				$output .= "\t\t<JudgeData>\n";
				$output .= "\t\t\t<JudgeName>".$steward_name."</JudgeName>\n";
				$output .= "\t\t\t<JudgeID>".strtoupper(strtr($steward_info['4'],$bjcp_num_replace))."</JudgeID>\n";
				$output .= "\t\t\t<JudgeRole>Steward</JudgeRole>\n";
				$output .= "\t\t\t<JudgePts>0.0</JudgePts>\n";
				$output .= "\t\t\t<NonJudgePts>".$steward_points."</NonJudgePts>\n";
				$output .= "\t\t</JudgeData>\n";
				}
			}
		}
		
		//Staff Members with a properly formatted BJCP IDs in the system
		foreach (array_unique($st) as $uid) { 
		if (array_sum($st_running_total) <= $staff_points_total) {
			$staff_info = explode("^",brewer_info($uid));
				if (($staff_info['0'] != "") && ($staff_info['1'] != "") && (validate_bjcp_id($staff_info['4']))) {
					$staff_name = ucwords(strtolower($staff_info['0']))." ".ucwords(strtolower($staff_info['1']));
					$st_running_total[] .= $staff_points;
					$output .= "\t\t<JudgeData>\n";
					$output .= "\t\t\t<JudgeName>".$staff_name."</JudgeName>\n";
					$output .= "\t\t\t<JudgeID>".strtoupper(strtr($staff_info['4'],$bjcp_num_replace))."</JudgeID>\n";
					$output .= "\t\t\t<JudgeRole>Staff</JudgeRole>\n";
					$output .= "\t\t\t<JudgePts>0.0</JudgePts>\n";
					$output .= "\t\t\t<NonJudgePts>".$staff_points."</NonJudgePts>\n";
					$output .= "\t\t</JudgeData>\n";
				}
			}
		}
	
		// Organizer with a properly formatted BJCP ID in the system
		foreach (array_unique($o) as $uid) {
		$organizer_info = explode("^",brewer_info($uid));
			if (($organizer_info['0'] != "") && ($organizer_info['1'] != "") && (validate_bjcp_id($organizer_info['4']))) { 
				$output .= "\t\t<JudgeData>\n";
				$output .= "\t\t\t<JudgeName>".$organizer_info['0']." ".$organizer_info['1']."</JudgeName>\n";
				$output .= "\t\t\t<JudgeID>".strtoupper(strtr($organizer_info['4'],$bjcp_num_replace))."</JudgeID>\n";
				$output .= "\t\t\t<JudgeRole>Organizer</JudgeRole>\n";
				$output .= "\t\t\t<JudgePts>0.0</JudgePts>\n";
				$output .= "\t\t\t<NonJudgePts>".$organ_points."</NonJudgePts>\n";
				$output .= "\t\t</JudgeData>\n";
			}
		}
		
		$output .= "\t</BJCPpoints>\n";
	
		$output .= "\t<NonBJCP>\n";
		
		// Judges without a properly formatted BJCP IDs in the system
		foreach (array_unique($j) as $uid) { 
		$judge_info = explode("^",brewer_info($uid));
		$judge_points = judge_points($uid,$judge_info['5']);
		if ($judge_points > 0) {
			$bos_judge = bos_points($uid);
			if ($bos_judge) $assignment = "Judge+BOS";
			else $assignment = "Judge";
				if (($judge_info['0'] != "") && ($judge_info['1'] != "") && (!validate_bjcp_id($judge_info['4']))) { 
					$judge_name = ucwords(strtolower($judge_info['0']))." ".ucwords(strtolower($judge_info['1']));
						$output .= "\t\t<JudgeData>\n";
						$output .= "\t\t\t<JudgeName>".$judge_name."</JudgeName>\n";
						$output .= "\t\t\t<JudgeRole>".$assignment."</JudgeRole>\n";
						if ($bos_judge) $output .= "\t\t\t<JudgePts>".number_format((judge_points($uid,$judge_info['5'])+$bos_judge_points),1)."</JudgePts>\n";
						else $output .= "\t\t\t<JudgePts>".judge_points($uid,$judge_info['5'])."</JudgePts>\n";
						$output .= "\t\t\t<NonJudgePts>0.0</NonJudgePts>\n";
						$output .= "\t\t</JudgeData>\n";
				}
			}
		}
		
		// Loner BOS Judges (no assignment to any table)
		foreach (array_unique($bos_judge_no_assignment) as $uid) { 
			$judge_info = explode("^",brewer_info($uid));
			if (($judge_info['0'] != "") && ($judge_info['1'] != "") && (!validate_bjcp_id($judge_info['4']))) { 
				if (!empty($uid)) {
					$judge_name = strtr(ucwords(strtolower($judge_info['0'])),$html_remove)." ".strtr(ucwords(strtolower($judge_info['1'])),$html_remove);
					$output .= "\t\t<JudgeData>\n";
					$output .= "\t\t\t<JudgeName>".$judge_name."</JudgeName>\n";
					$output .= "\t\t\t<JudgeID>".strtoupper(strtr($judge_info['4'],$bjcp_num_replace))."</JudgeID>\n";
					$output .= "\t\t\t<JudgeRole>BOS Judge</JudgeRole>\n";
					$output .= "\t\t\t<JudgePts>1.0</JudgePts>\n";
					$output .= "\t\t\t<NonJudgePts>0.0</NonJudgePts>\n";
					$output .= "\t\t</JudgeData>\n";
				}
			}
		}
		
		// Stewards without a properly formatted BJCP IDs in the system
		foreach (array_unique($s) as $uid) { 
		$steward_points = steward_points($uid);
		if ($steward_points > 0) {
			$steward_info = explode("^",brewer_info($uid));
				if (($steward_info['0'] != "") && ($steward_info['1'] != "") && (!validate_bjcp_id($steward_info['4']))) {
						$steward_name = ucwords(strtolower($steward_info['0']))." ".ucwords(strtolower($steward_info['1']));
						$output .= "\t\t<JudgeData>\n";
						$output .= "\t\t\t<JudgeName>".$steward_name."</JudgeName>\n";
						$output .= "\t\t\t<JudgeRole>Steward</JudgeRole>\n";
						$output .= "\t\t\t<JudgePts>0.0</JudgePts>\n";
						$output .= "\t\t\t<NonJudgePts>".$steward_points."</NonJudgePts>\n";
						$output .= "\t\t</JudgeData>\n";
				}
			}
		}
	
		
		// Staff Members without a properly formatted BJCP IDs in the system
		foreach (array_unique($st) as $uid) { 
		if (array_sum($st_running_total) < $staff_points_total) {
			$staff_info = explode("^",brewer_info($uid));
				if (($staff_info['0'] != "") && ($staff_info['1'] != "") && (!validate_bjcp_id($staff_info['4']))) {
					$st_running_total[] = $staff_points;
					$staff_name = ucwords(strtolower($staff_info['0']))." ".ucwords(strtolower($staff_info['1']));
					$output .= "\t\t<JudgeData>\n";
					$output .= "\t\t\t<JudgeName>".$staff_name."</JudgeName>\n";
					$output .= "\t\t\t<JudgeRole>Staff</JudgeRole>\n";
					$output .= "\t\t\t<JudgePts>0.0</JudgePts>\n";
					if ($staff_points < $organ_points) $output .= "\t\t\t<NonJudgePts>".$staff_points."</NonJudgePts>\n";
					else $output .= "\t\t\t<NonJudgePts>".$organ_points."</NonJudgePts>\n";
					$output .= "\t\t</JudgeData>\n";
				}
			}
		}
		
		// Organizer without a properly formatted BJCP ID in the system
		foreach (array_unique($o) as $uid) {
		$organizer_info = explode("^",brewer_info($uid));
			if (($organizer_info['0'] != "") && ($organizer_info['1'] != "") && (!validate_bjcp_id($organizer_info['4']))) { 
				$output .= "\t\t<JudgeData>\n";
				$output .= "\t\t\t<JudgeName>".$organizer_info['0']." ".$organizer_info['1']."</JudgeName>\n";
				$output .= "\t\t\t<JudgeRole>Organizer</JudgeRole>\n";
				$output .= "\t\t\t<JudgePts>0.0</JudgePts>\n";
				$output .= "\t\t\t<NonJudgePts>".$organ_points."</NonJudgePts>\n";
				$output .= "\t\t</JudgeData>\n";
			}
		}
		$output .= "\t</NonBJCP>\n";
		
		// BOS Reporting
		$output .= "\t<Comments>\n";
		$output .= "Submitter Email: ".$_SESSION['user_name']."\n";
		
		do { $a[] = $row_style_types['id']; } while ($row_style_types = mysql_fetch_assoc($style_types));
		sort($a);
		/*
		foreach ($a as $type) {
			
			include(DB.'output_results_download_bos.db.php');
			
			if ($totalRows_bos > 0) {
				$output .= "BOS Winner: ".."\n";
				$output .= "BOS Style: ".."\n";
				$output .= "BOS City: ".."\n";
				$output .= "BOS State: ".."\n";
			}
		}
		*/
		$output .= "\t</Comments>\n";
		
		$output .= "\t<SubmissionDate>".date('l j F Y h:i:s A')."</SubmissionDate>\n";
		$output .= "</OrgReport>";
		
		header("Content-Type: application/force-download");
		header("Content-Disposition: attachment; filename=".$filename."");
		header("Pragma: no-cache");
		header("Expires: 0");
	
		echo $output;
		exit();
}

if ($view == "default") { // printing from browser 
	if (NHC) $base_url = "../";
	
	$output_organizer = "";
	$output_judges = "";
	$output_stewards = "";
	$output_staff = "";
	
	if ($totalRows_organizer > 0) { 
		$org_name = ucwords(strtolower($row_org['brewerLastName'])).", ".ucwords(strtolower($row_org['brewerFirstName']));
		$output_organizer .= "<tr>";
		$output_organizer .= "<td class='bdr1B_gray'>".$org_name."</td>";
		$output_organizer .= "<td class='data bdr1B_gray'>";
		if (validate_bjcp_id($row_org['brewerJudgeID'])) $output_organizer .= strtoupper(strtr($row_org['brewerJudgeID'],$bjcp_num_replace));
		$output_organizer .= "</td>";
		$output_organizer .= "<td class='data bdr1B_gray'>".$organ_points."</td>";
		$output_organizer .= "</tr>";
	}
	
	if ($totalRows_judges > 0) {
		do { $j[] = $row_judges['uid']; } while ($row_judges = mysql_fetch_assoc($judges));
		foreach (array_unique($j) as $uid) { 
			$judge_info = explode("^",brewer_info($uid));
			$judge_points = judge_points($uid,$judge_info['5']);
			if ($judge_points > 0) {
				if (!empty($judge_info['1'])) {
					$judge_name = ucwords(strtolower($judge_info['1'])).", ".ucwords(strtolower($judge_info['0']));
					$bos_judge = bos_points($uid);
					$output_judges .= "<tr>";
					$output_judges .= "<td class='bdr1B_gray'>".$judge_name."</td>";
					$output_judges .= "<td class='data bdr1B_gray'>";
					if (validate_bjcp_id($judge_info['4'])) $output_judges .= strtoupper(strtr($judge_info['4'],$bjcp_num_replace));
					$output_judges .= "</td>";
					$output_judges .= "<td class='data bdr1B_gray'>";
					if ($bos_judge) $output_judges .= number_format((judge_points($uid,$judge_info['5'])+$bos_judge_points),1); 
					else $output_judges .=  judge_points($uid,$judge_info['5']);
					$output_judges .= "</td>";
					$output_judges .= "<td class='bdr1B_gray'>";
					if ($bos_judge) $output_judges .= "&#10003;";
					else $output_judges .= "&nbsp;";
					$output_judges .= "</td>";
					$output_judges .= "</tr>";
				}
			}
		}
		
		foreach (array_unique($bos_judge_no_assignment) as $uid) { 
			$judge_info = explode("^",brewer_info($uid));
			if ((!empty($uid)) && (!empty($judge_info['1']))) {
				$judge_name = ucwords(strtolower($judge_info['1'])).", ".ucwords(strtolower($judge_info['0']));
				
				$output_judges .= "<tr>";
				$output_judges .= "<td class='bdr1B_gray'>".$judge_name."</td>";
				$output_judges .= "<td class='data bdr1B_gray'>";
				if (validate_bjcp_id($judge_info['4'])) $output_judges .= strtoupper(strtr($judge_info['4'],$bjcp_num_replace));
				$output_judges .= "</td>";
				$output_judges .= "<td class='data bdr1B_gray'>";
				$output_judges .= "1.0"; 
				$output_judges .= "</td>";
				$output_judges .= "<td class='bdr1B_gray'>";
				$output_judges .= "&#10003;";
				$output_judges .= "</td>";
				$output_judges .= "</tr>";
			}
		}
	}
	
	
	if ($totalRows_stewards > 0) { 
	
		do { $s[] = $row_stewards['uid']; } while ($row_stewards = mysql_fetch_assoc($stewards));
		
		foreach (array_unique($s) as $uid) { 
			$steward_points = steward_points($uid);
			if ($steward_points > 0) {
				$steward_info = explode("^",brewer_info($uid));
				if (!empty($steward_info['1'])) {
					$steward_name = ucwords(strtolower($steward_info['1'])).", ".ucwords(strtolower($steward_info['0']));
					$output_stewards .= "<tr>";
					$output_stewards .= "<td class='bdr1B_gray'>".$steward_name."</td>";
					$output_stewards .= "<td class='data bdr1B_gray'>";
					if (validate_bjcp_id($steward_info['4'])) $output_stewards .= strtoupper(strtr($steward_info['4'],$bjcp_num_replace));
					else $output_staff .= "&nbsp;";
					$output_stewards .= "</td>";
					$output_stewards .= "<td class='data bdr1B_gray'>".steward_points($uid)."</td>";
					$output_stewards .= "</tr>";
				}
			}
	
		}
		
	}
	
	
	if ($totalRows_staff > 0) { 
		do { $st[] = $row_staff['uid']; } while ($row_staff = mysql_fetch_assoc($staff));
		$st_running_total[] = "";
		foreach (array_unique($st) as $uid) { 
		
			if (array_sum($st_running_total) < $staff_points_total) {
				$staff_info = explode("^",brewer_info($uid));
				$st_running_total[] = $staff_points;
				
				if (!empty($staff_info['1'])) {
					$staff_name = ucwords(strtolower($staff_info['1'])).", ".ucwords(strtolower($staff_info['0']));
				
					$output_staff .= "<tr>";
					$output_staff .= "<td class='bdr1B_gray'>".$staff_name."</td>";
					$output_staff .= "<td class='data bdr1B_gray'>";
					if (validate_bjcp_id($staff_info['4'])) $output_staff .= strtoupper(strtr($staff_info['4'],$bjcp_num_replace));
					
					$output_staff .= "</td>";
					$output_staff .= "<td class='data bdr1B_gray'>";
					if ((array_sum($st_running_total) <= $staff_points_total) && ($staff_points < $organ_points)) $output_staff .= $staff_points;
					else $output_staff .= $organ_points;
					$output_staff .= "</td>";
					$output_staff .= "</tr>";
				}
			}
		}
	} // end if ($totalRows_staff > 0)

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Brew Competition Online Entry and Management - brewcompetition.com</title>
<link href="<?php echo $base_url; ?>css/print.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.dataTables.js"></script>
</head>
<body>
<div id="content">
	<div id="content-inner">
    <?php 
	//echo total_days()."<br>";
	//echo total_sessions()."<br>";
	//echo $staff_points_total."<br>";
	//echo $staff_points;
	//print_r($bos_judge_no_assignment);
	?>
    <div id="header">	
		<div id="header-inner">
        	<h1><?php echo $_SESSION['contestName']; ?> BJCP Points Report</h1>
        </div>
    </div>
    <p>The points in this report are derived from the official BJCP Sanctioned Competition Requirements, available at <a href="http://www.bjcp.org/rules.php" target="_blank">http://www.bjcp.org/rules.php</a>.</p>
    <p><strong>BJCP Competition ID:</strong> <?php echo $_SESSION['contestID']; ?></p>
    <p>
    <strong>Total Entries:</strong> <?php echo $total_entries; ?><br />
    <strong>Total Days:</strong> <?php echo total_days(); ?><br />
    <strong>Total Sessions:</strong> <?php echo total_sessions(); ?><br />
    <strong>Total Flights:</strong> <?php echo total_flights(); ?> (includes Best of Show)<br />
    </p>
    <?php if (!empty($output_organizer)) { ?>
    <h2>Organizer</h2>
    <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable0').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
    <table class="dataTable" id="sortable0">
    <thead>
    <tr>
    	<th class="dataHeading bdr1B" width="35%">Name</th>
        <th class="dataHeading bdr1B" width="10%">BJCP ID</th>
        <th class="dataHeading bdr1B">Points</th>
    </tr>
    </thead>
    <tbody>
    <?php echo $output_organizer; ?>
    </tbody>
    </table>
    <?php }	?>
    <?php if (!empty($output_judges)) { ?>
    <h2>Judges</h2>
    <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable1').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
    <table class="dataTable" id="sortable1">
    <thead>
    <tr>
    	<th class="dataHeading bdr1B" width="35%">Name</th>
        <th class="dataHeading bdr1B" width="10%">BJCP ID</th>
        <th class="dataHeading bdr1B" width="10%">Points</th>
        <th class="dataHeading bdr1B">BOS?</th>
    </tr>
    </thead>
    <tbody>
    <?php echo $output_judges; ?>
    </tbody>
    </table>
    <?php } ?>
	<?php if (!empty($output_stewards)) { ?>
    <h2>Stewards</h2>
    <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable2').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
    <table class="dataTable" id="sortable2">
    <thead>
    <tr>
    	<th class="dataHeading bdr1B" width="35%">Name</th>
        <th class="dataHeading bdr1B" width="10%">BJCP ID</th>
        <th class="dataHeading bdr1B">Points</th>
    </tr>
    </thead>
    <tbody>
    <?php echo $output_stewards; ?>
    </tbody>
    </table>
    <?php } ?>
    <?php if (!empty($output_staff)) { ?>
    <h2>Staff</h2>
    <p>Total Staff Points Available: <?php echo $staff_points_total; ?></p>
    <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable99').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']],
			"bProcessing" : false,
			"aoColumns": [
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
    <table class="dataTable" id="sortable99">
    <thead>
    <tr>
    	<th class="dataHeading bdr1B" width="35%">Name</th>
        <th class="dataHeading bdr1B" width="10%">BJCP ID</th>
        <th class="dataHeading bdr1B">Points</th>
    </tr>
    </thead>
    <tbody>
    <?php echo $output_staff; ?>
    </tbody>
    </table>
    <?php }	?>
	</div>
</div>
</body>
</html>	
<?php if (!$fx) { ?>
<script type="text/javascript">
function selfPrint(){
    self.focus();
    self.print();
}
setTimeout('selfPrint()',3000);
html.push(''); 
</script>
<?php } ?>
<?php } // end if ($view == "print") 
}
else echo "<p>Not available.</p>";
?>