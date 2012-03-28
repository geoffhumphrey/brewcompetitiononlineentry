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
require(INCLUDES.'staff_points.inc.php');

if ($view == "pdf") {
$filename = str_replace(" ","_",$row_contest_info['contestName']).'_BJCP_Points_Report.'.$view;
require(CLASSES.'fpdf/html_table.php');
$pdf=new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Write(5,$row_contest_info['contestName'].' BJCP Points Report');
$pdf->SetFont('Arial','',10);	

$html = '<br><br><strong>BJCP Competition ID</strong>: '.$row_contest_info['contestID'].'<br>';
$html .= '<br><strong>Total Entries</strong>: '.$total_entries.'<br>'; 

	if ($totalRows_organizer > 0) { 
	$html .= '<br><br><strong>Organizer</strong><br><br>';
	$html .= '<table border="1">';
    $html .= '<tr>';
    $html .= '<td width="300" align="center" bgcolor="#cccccc">Name</td>';
	$html .= '<td width="150" align="center" bgcolor="#cccccc">BJCP ID</td>';
    $html .= '<td width="150" align="center" bgcolor="#cccccc">Points</td>';
	$html .= '</tr>';
		$html .= '<tr>';
    	$html .= '<td width="300">'.$row_organizer['brewerLastName'].", ".$row_organizer['brewerFirstName'].'</td>';
		$html .= '<td width="150">';
			if ($row_organizer['brewerJudgeID'] != "") $html .= $row_organizer['brewerJudgeID']; else $html .= '&nbsp;';
		$html .= '</td>';
        $html .= '<td width="150">'.$organ_points.'</td>';
    	$html .= '</tr>';
    $html .= '</table>';
	}
	
	if ($totalRows_judges > 0) { 
	$html .= '<br><br><strong>Judges</strong><br><br>';
    $html .= '<table border="1">';
    $html .= '<tr>';
    $html .= '<td width="300" align="center"  bgcolor="#cccccc">Name</td>';
    $html .= '<td width="150" align="center"  bgcolor="#cccccc">BJCP ID</td>';
    $html .= '<td width="150" align="center"  bgcolor="#cccccc">Points</td>';
	$html .= '</tr>';
	do { $j[] = $row_judges['bid']; } while ($row_judges = mysql_fetch_assoc($judges));
	sort($j);
	foreach (array_unique($j) as $bid) { 
		$judge_info = explode("^",brewer_info($bid));

    	$html .= '<tr>';
    	$html .= '<td width="300">'.$judge_info['1'].', '.$judge_info['0'].'</td>';
    	$html .= '<td width="150">';
			if ($judge_info['4'] != "") $html .= $judge_info['4']; else $html .= '&nbsp;';
		$html .= '</td>';
    	$html .= '<td width="150">'.judge_points($bid,$judge_info['5']);
			if ($judge_info['5'] == "1") $html .= '*';
		$html .= '</td>';
    	$html .= '</tr>';
    	}  
    $html .= '</table>';
	$html .= '<br><em>* denotes BOS Judge</em>';
    }  
	if ($totalRows_stewards > 0) { 
	$html .= '<br><br><strong>Stewards</strong><br><br>';
	$html .= '<table border="1">';
    $html .= '<tr>';
    $html .= '<td width="300" align="center" bgcolor="#cccccc">Name</td>';
	$html .= '<td width="150" align="center" bgcolor="#cccccc">BJCP ID</td>';
    $html .= '<td width="150" align="center" bgcolor="#cccccc">Points</td>';
	$html .= '</tr>';
	do { $s[] = $row_stewards['bid']; } while ($row_stewards = mysql_fetch_assoc($stewards));
	foreach (array_unique($s) as $bid) { 
		$steward_info = explode("^",brewer_info($bid));
		$html .= '<tr>';
    	$html .= '<td width="300">'.$steward_info['1'].", ".$steward_info['0'].'</td>';
        $html .= '<td width="150">';
			if ($steward_info['4'] != "") $html .= $steward_info['4']; else $html .= '&nbsp;';
		$html .= '</td>';
		$html .= '<td width="150">'.steward_points($bid).'</td>';
    	$html .= '</tr>';
    	}  
    $html .= '</table>';
	}
	
	if ($totalRows_staff > 0) { 
	$html .= '<br><br><strong>Staff</strong>';
	$html .= '<br>Total Possible Staff Points: '.total_points($total_entries,"Staff").'. Staff Points are calculated by dividing the number of staff members into the total possible points, rounded down to the nearest half (the BJCP requirement is for half-point increments). The organizer can distribute staff points however they wish when reporting to the BJCP. The calcuations reflected below are based upon the assumption that all staff members performed equal amounts of work.<br><br>';
	$html .= '<table border="1">';
    $html .= '<tr>';
    $html .= '<td width="300" align="center" bgcolor="#cccccc">Name</td>';
	$html .= '<td width="150" align="center" bgcolor="#cccccc">BJCP ID</td>';
    $html .= '<td width="150" align="center" bgcolor="#cccccc">Points</td>';
	$html .= '</tr>';
	do { 
		$html .= '<tr>';
    	$html .= '<td width="300">'.$row_staff['brewerLastName'].", ".$row_staff['brewerFirstName'].'</td>';
		$html .= '<td width="150">';
			if ($row_staff['brewerJudgeID'] != "") $html .= $row_staff['brewerJudgeID']; else $html .= '&nbsp;';
		$html .= '</td>';
        $html .= '<td width="150">'.$staff_points.'</td>';
    	$html .= '</tr>';
    	}  
	while ($row_staff = mysql_fetch_assoc($staff));
    $html .= '</table>';
	}
	
	$pdf->WriteHTML($html);
	$pdf->Output($filename,D);
}

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

	if (($totalRows_stewards > 0) || ($totalRows_staff > 0) || ($totalRows_organizer > 0)) $output .= "\t<NonBJCP>\n";
	
	if ($totalRows_stewards > 0) { 
	do { $s[] = $row_stewards['bid']; } while ($row_stewards = mysql_fetch_assoc($stewards));
	foreach (array_unique($s) as $bid) { 
		$steward_info = explode("^",brewer_info($bid));
		$output .= "\t\t<JudgeData>\n";
    	$output .= "\t\t\t<JudgeName>".$steward_info['0']." ".$steward_info['1']."</JudgeName>\n";
		$output .= "\t\t\t<JudgeRole>Steward</JudgeRole>\n";
    	$output .= "\t\t\t<JudgePoints>0</JudgePoints>\n";
		$output .= "\t\t\t<NonJudgePoints>".steward_points($bid)."</NonJudgePoints>\n";
    	$output .= "\t\t</JudgeData>\n";
    	}  
	}
	if ($totalRows_staff > 0) { 
	do { 
		$output .= "\t\t<JudgeData>\n";
    	$output .= "\t\t\t<JudgeName>".$row_staff['brewerFirstName']." ".$row_staff['brewerLastName']."</JudgeName>\n";
		$output .= "\t\t\t<JudgeRole>Staff</JudgeRole>\n";
    	$output .= "\t\t\t<JudgePoints>0</JudgePoints>\n";
		$output .= "\t\t\t<NonJudgePoints>".$staff_points."</NonJudgePoints>\n";
    	$output .= "\t\t</JudgeData>\n";
    	}  
	while ($row_staff = mysql_fetch_assoc($staff));
	}	
	
	if ($totalRows_organizer > 0) { 
		$output .= "\t\t<JudgeData>\n";
    	$output .= "\t\t\t<JudgeName>".$row_organizer['brewerFirstName']." ".$row_organizer['brewerLastName']."</JudgeName>\n";
		$output .= "\t\t\t<JudgeRole>Organizer</JudgeRole>\n";
    	$output .= "\t\t\t<JudgePoints>0</JudgePoints>\n";
		$output .= "\t\t\t<NonJudgePoints>".$organ_points."</NonJudgePoints>\n";
    	$output .= "\t\t</JudgeData>\n";
	}
	
	if (($totalRows_stewards > 0) || ($totalRows_staff > 0) || ($totalRows_organizer > 0)) $output .= "\t</NonBJCP>\n";
	
	$output .= "\t<SubmissionDate>".date('l j F Y h:i:s A')."</SubmissionDate>\n";
	$output .= "</OrgReport>";
	
	header("Content-Type: application/force-download");
	header("Content-Disposition: attachment; filename=".$filename);
	header("Pragma: no-cache");
	header("Expires: 0");

	echo $output;
	exit();
}
if ($view == "default") { // printing from browser ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if ($tb == "default") { ?><meta http-equiv="refresh" content="0;URL=<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."&tb=true"; ?>" /><?php } ?>
<title>Brew Competition Online Entry and Management - brewcompetition.com</title>
<link href="../css/print.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="../js_includes/jquery.dataTables.js"></script>
</head>
<body <?php if ($tb == "true") echo "onload=\"javascript:window.print()\""; ?>>
<div id="content">
	<div id="content-inner">
    <div id="header">	
		<div id="header-inner">
        	<h1><?php echo $row_contest_info['contestName']; ?> BJCP Points Report</h1>
        </div>
    </div>
    <p>The points in this report are derived from the official BJCP Sanctioned Competition Requirements, available at <a href="http://www.bjcp.org/rules.php" target="_blank">http://www.bjcp.org/rules.php</a>.</p>
    <p><strong>BJCP Competition ID:</strong> <?php echo $row_contest_info['contestID']; ?></p>
    <p><strong>Total Entries:</strong> <?php echo $total_entries; ?></p>
	<?php if ($totalRows_organizer > 0) { ?>
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
    <table class="dataTable" id="sortable3">
    <thead>
    <tr>
    	<th class="dataHeading bdr1B" width="35%">Name</th>
        <th class="dataHeading bdr1B" width="10%">BJCP ID</th>
        <th class="dataHeading bdr1B">Points</th>
    </tr>
    </thead>
    <tbody>
    <tr>
    	<td class="bdr1B_gray"><?php echo $row_organizer['brewerLastName'].", ".$row_organizer['brewerFirstName']; ?></td>
        <td class="data bdr1B_gray"><?php echo $row_organizer['brewerJudgeID']; ?></td>
        <td class="data bdr1B_gray"><?php echo $organ_points; ?></td>
    </tr>
    </tbody>
    </table>
    <?php }	
	if ($totalRows_judges > 0) { ?>
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
        <th class="dataHeading bdr1B">Points</th>
    </tr>
    </thead>
    <tbody>
    <?php 
	do { $j[] = $row_judges['bid']; } while ($row_judges = mysql_fetch_assoc($judges));
	foreach (array_unique($j) as $bid) { 
	$judge_info = explode("^",brewer_info($bid));
	?>
    <tr>
    	<td class="bdr1B_gray"><?php echo $judge_info['1'].", ".$judge_info['0']; ?></td>
    	<td class="data bdr1B_gray"><?php echo $judge_info['4']; ?></td>
        <td class="data bdr1B_gray"><?php echo judge_points($bid,$judge_info['5']); if ($judge_info['5'] == "1") echo "* <em>BOS Judge</em>"; ?></td>
    </tr>
    <?php }  ?>
    </tbody>
    </table>
    <?php } ?>
	<?php if ($totalRows_stewards > 0) { ?>
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
    <?php 
	do { $s[] = $row_stewards['bid']; } while ($row_stewards = mysql_fetch_assoc($stewards));
	foreach (array_unique($s) as $bid) { 
	$steward_info = explode("^",brewer_info($bid));
	?>
    <tr>
    	<td class="bdr1B_gray"><?php echo $steward_info['1'].", ".$steward_info['0']; ?></td>
        <td class="data bdr1B_gray"><?php echo $steward_info['4']; ?></td>
        <td class="data bdr1B_gray"><?php echo steward_points($bid); ?></td>
    </tr>
    <?php }  ?>
    </tbody>
    </table>
    <?php } 
	if ($totalRows_staff > 0) { ?>
    <h2>Staff</h2>
    <p>Total Possible Staff Points: <?php echo total_points($total_entries,"Staff") ?>. Staff Points are calculated by dividing the number of staff members into the total possible points, rounded down to the nearest half (the BJCP requirement is for half-point increments). The organizer can distribute staff points however they wish when reporting to the BJCP. BCOE&amp;M's calcuations reflected below are based upon the assumption that all staff members performed equal amounts of work.</p>
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
    <?php do {  ?>
    <tr>
    	<td class="bdr1B_gray"><?php echo $row_staff['brewerLastName'].", ".$row_staff['brewerFirstName']; ?></td>
        <td class="data bdr1B_gray"><?php echo $row_staff['brewerJudgeID']; ?></td>
        <td class="data bdr1B_gray"><?php echo $staff_points; ?></td>
    </tr>
    <?php } while ($row_staff = mysql_fetch_assoc($staff)); ?>
    </tbody>
    </table>
    <?php }	?>
	</div>
</div>	
</body>
</html>	
<?php } // end if ($view == "print") ?>