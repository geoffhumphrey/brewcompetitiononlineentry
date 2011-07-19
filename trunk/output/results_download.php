<?php 
session_start(); 
require('../paths.php'); 
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'url_variables.inc.php');
require(DB.'common.db.php');
include(DB.'admin_common.db.php');
require(INCLUDES.'scrubber.inc.php');

if ($view == "pdf") {
	require(CLASSES.'fpdf/html_table.php');
	$pdf=new PDF();
	$pdf->AddPage();
}

if ($view == "html") {
	$header .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
	$header .= '<html xmlns="http://www.w3.org/1999/xhtml">';
	$header .= '<head>';
	$header .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
	$header .= '<title>Results - '.$row_contest_info['contestName'].'</title>';
	$header .= '</head>';
	$header .= '<body>';
}

if ($go == "judging_scores_bos") { 
if ($view == "pdf") {
	$pdf->SetFont('Arial','B',16);
	$pdf->Write(5,'Best of Show Results - '.$row_contest_info['contestName']);
	$pdf->SetFont('Arial','',8);	
}
$filename = str_replace(" ","_",$row_contest_info['contestName']).'_BOS_Results.'.$view;

do { $a[] = $row_style_types['id']; } while ($row_style_types = mysql_fetch_assoc($style_types));
$html == '';
if ($view == "html") $html .= '<h1>BOS - '.$row_contest_info['contestName'].'</h1>';
sort($a);
foreach (array_unique($a) as $type) {
	$query_style_type = "SELECT * FROM style_types WHERE id='$type'";
	$style_type = mysql_query($query_style_type, $brewing) or die(mysql_error());
	$row_style_type = mysql_fetch_assoc($style_type);

	if ($row_style_type['styleTypeBOS'] == "Y") { 
	$query_bos = "SELECT * FROM judging_scores_bos WHERE (scorePlace='1' OR scorePlace='2' OR scorePlace='3' OR scorePlace='4' OR scorePlace='5') AND scoreType='$type' ORDER BY scorePlace ASC";
	$bos = mysql_query($query_bos, $brewing) or die(mysql_error());
	$row_bos = mysql_fetch_assoc($bos);
	$totalRows_bos = mysql_num_rows($bos);
	
if ($totalRows_bos > 0) { 
	$html .= '<br><br><strong>'.$row_style_type['styleTypeName'].'</strong><br>';
	$html .= '<table border="1">';
	$html .= '<tr>';
	$html .= '<td width="50" align="center"  bgcolor="#cccccc"><strong>Place</strong></td>';
	$html .= '<td width="175" align="center" bgcolor="#cccccc"><strong>Brewer(s)</strong></td>';
	$html .= '<td width="275" align="center" bgcolor="#cccccc"><strong>Entry Name</strong></td>';
	$html .= '<td width="250" align="center" bgcolor="#cccccc"><strong>Club</strong></td>';
	$html .= '</tr>';
	do {
		$query_entries = sprintf("SELECT brewBrewerID,brewBrewerFirstName,brewBrewerLastName,brewName,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewCoBrewer FROM brewing WHERE id='%s'", $row_bos['eid']);
		$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
		$row_entries = mysql_fetch_assoc($entries);
		$style = $row_entries['brewCategory'].$row_entries['brewSubCategory'];
		
		$query_brewer = sprintf("SELECT id,brewerClubs FROM $brewer_db_table WHERE uid='%s'", $row_entries['brewBrewerID']);
		$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
		$row_brewer = mysql_fetch_assoc($brewer);
		
			$html .= '<tr>';
			$html .= '<td width="50">'.display_place($row_bos['scorePlace'],1).'</td>';
			$html .= '<td width="175">'.$row_entries['brewBrewerFirstName'].' '.$row_entries['brewBrewerLastName'];
			if ($row_entries['brewCoBrewer'] != "") $html .=', '.$row_entries['brewCoBrewer'];
			$html .= '</td>';
			$html .= '<td width="275">'.strtr($row_entries['brewName'],$html_remove).'</td>';
			$html .= '<td width="250">';
			if ($row_brewer['brewerClubs'] != "") $html .=strtr($row_brewer['brewerClubs'],$html_remove);
			else $html .= "&nbsp;";
			$html .= '</td>';
			$html .= '</tr>';
	} while ($row_bos = mysql_fetch_assoc($bos)); 
	mysql_free_result($bos);
	mysql_free_result($entries);
	mysql_free_result($brewer);
	$html .= '</table>';
	  } 
    }
  } 
 if ($view == "pdf") { $pdf->WriteHTML($html); }
} // end if ($go == "judging_scores_bos")


if ($go == "judging_scores") {
if ($view == "pdf") {
	$pdf->SetFont('Arial','B',16);
	$pdf->Write(5,'Results - '.$row_contest_info['contestName']);
	$pdf->SetFont('Arial','',9);	
}
$filename = str_replace(" ","_",$row_contest_info['contestName']).'_Results.'.$view;
$html = '';
if ($view == "html") $html .= '<h1>Results - '.$row_contest_info['contestName'].'</h1>';
// loop through 'judging_tables' table
do { 
	$entry_count = get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default");
	
	if ($entry_count > 0) {
	$html .= '<br><br><strong>Table '.$row_tables['tableNumber'].': '.$row_tables['tableName'].' ('.$entry_count.' entries)</strong><br>';
	$html .= '<table border="1">';
	$html .= '<tr>';
	$html .= '<td width="50" align="center"  bgcolor="#cccccc"><strong>Place</strong></td>';
	$html .= '<td width="175" align="center" bgcolor="#cccccc"><strong>Brewer(s)</strong></td>';
	$html .= '<td width="275" align="center" bgcolor="#cccccc"><strong>Entry Name</strong></td>';
	$html .= '<td width="250" align="center" bgcolor="#cccccc"><strong>Club</strong></td>';
	$html .= '</tr>';
	$query_scores = sprintf("SELECT * FROM %s WHERE scoreTable='%s'", $go, $row_tables['id']);
	$query_scores .= " AND (scorePlace='1' OR scorePlace='2' OR scorePlace='3' OR scorePlace='4' OR scorePlace='5') ORDER BY scorePlace ASC";	
	$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
	$row_scores = mysql_fetch_assoc($scores);
	
	// loop through 'scores' table
	do { 
		$query_entries = sprintf("SELECT brewBrewerID,id,brewName,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewBrewerFirstName,brewBrewerLastName FROM brewing WHERE id='%s'", $row_scores['eid']);
		$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
		$row_entries = mysql_fetch_assoc($entries);
		
		$query_brewer = sprintf("SELECT id,brewerClubs FROM $brewer_db_table WHERE uid='%s'", $row_entries['brewBrewerID']);
		$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
		$row_brewer = mysql_fetch_assoc($brewer);
		
			$html .= '<tr>';
			$html .= '<td width="50">'.display_place($row_scores['scorePlace'],1).'</td>';
			$html .= '<td width="175">'.$row_entries['brewBrewerFirstName'].' '.$row_entries['brewBrewerLastName'];
			if ($row_entries['brewCoBrewer'] != "") $html .=', '.$row_entries['brewCoBrewer'];
			$html .= '</td>';
			$html .= '<td width="275">'.strtr($row_entries['brewName'],$html_remove).'</td>';
			$html .= '<td width="250">';
			if ($row_brewer['brewerClubs'] != "") $html .=strtr($row_brewer['brewerClubs'],$html_remove);
			else $html .= "&nbsp;";
			$html .= '</td>';
			$html .= '</tr>';
			
		mysql_free_result($entries);
	} while ($row_scores = mysql_fetch_assoc($scores));
	$html .= '</table>';
	}
} while ($row_tables = mysql_fetch_assoc($tables));;
if ($view == "pdf") { $pdf->WriteHTML($html); }	
} // end if ($go == "judging_scores")


if ($view == "pdf") { 
	$pdf->Output($filename,D);
	}
if ($view == "html") { 
	$footer = '</body>';
	$footer .= '</html>';
	header("Content-Type: application/force-download");
	header("Content-Disposition: attachment; filename=".$filename);
	header("Pragma: no-cache");
	header("Expires: 0");
	echo $header.$html.$footer;
	exit();
	}
?>
