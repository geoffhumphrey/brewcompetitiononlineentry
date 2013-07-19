<?php

session_start(); 
require('../paths.php'); 
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php');
require(DB.'common.db.php');
require(DB.'admin_common.db.php');

require(INCLUDES.'version.inc.php');
require(INCLUDES.'scrubber.inc.php');
require(CLASSES.'fpdf/pdf_label.php');

if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {

// Special ingredients required
$special_ingredients = array("6D","16E","17F","20A","21A","21B","22B","22C","23A","25C","26A","26B","26C","27E","28B","28C","28D");

// Mead/cider info required
$mead = array("24A","24B","24C","25A","25B","25C","26A","26B","26C","27A","27B","27C","27D","27E","28A","26A","26C","27E","28B","28C","28D");


function truncate($string, $your_desired_width) {
  $parts = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
  $parts_count = count($parts);

  $length = 0;
  $last_part = 0;
  for (; $last_part < $parts_count; ++$last_part) {
    $length += strlen($parts[$last_part]);
    if ($length > $your_desired_width) { break; }
  }

  return implode(array_slice($parts, 0, $last_part));
}


if (($go == "entries") && ($action == "bottle-entry") && ($view != "special")) {
	$query_log = "SELECT * FROM $brewing_db_table";
	if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s'",$filter);
	$query_log .= " ORDER BY brewCategorySort,brewSubCategory,id ASC";
	$log = mysql_query($query_log, $brewing) or die(mysql_error());
	$row_log = mysql_fetch_assoc($log);
	$totalRows_log = mysql_num_rows($log);

	$filename = str_replace(" ","_",$_SESSION['contestName'])."_Bottle_Labels";
	if ($filter != "default") $filename .= "_Category_".$filter;
	$filename .= ".pdf";
	$pdf = new PDF_Label('5160'); 
	$pdf->AddPage();
	$pdf->SetFont('Arial','',8);

	// Print labels
	do {
		
		$entry_no = sprintf("%04s",$row_log['id']);																						  
		
		$text = sprintf("\n%s (%s)   %s (%s)   %s (%s)\n\n\n%s (%s)   %s (%s)   %s (%s)",
		$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory'], 
		$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory'],
		$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory'],
		$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory'],
		$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory'],
		$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory']
		);
		
		$pdf->Add_Label($text);
		
	} while ($row_log = mysql_fetch_assoc($log));

	$pdf->Output($filename,'D');
}

if (($go == "entries") && ($action == "bottle-entry") && ($view == "special")) {
	
	$section = "brew";
	require(DB.'styles.db.php');
	
	// Custom styles where special ingredients are required
	if ($totalRows_styles2 > 0) {
		do { 
			$style_special = ltrim($row_styles2['brewStyleGroup'],"0");
			$special_ingredients[] .= $style_special.$row_styles2['brewStyleNum']; 
		}  while ($row_styles2 = mysql_fetch_assoc($styles2));
	}
	
	// print_r($special_ingredients); exit;
	
	$query_log = sprintf("SELECT * FROM %s",$prefix."brewing");
	if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s' AND brewReceived='1'",$filter);
	$query_log .= " ORDER BY brewCategorySort,brewSubCategory,brewJudgingNumber ASC";
	$log = mysql_query($query_log, $brewing) or die(mysql_error());
	$row_log = mysql_fetch_assoc($log);
	$totalRows_log = mysql_num_rows($log);

	$filename = str_replace(" ","_",$_SESSION['contestName'])."_Bottle_Labels";
	if ($filter != "default") $filename .= "_Category_".$filter;
	$filename .= "_Special_Mead-Cider";
	$filename .= ".pdf";
	$pdf = new PDF_Label('5160'); 
	
	$pdf->AddPage();
	$pdf->SetFont('Arial','',10);

	// Print labels
	do {
		
		for($i=0; $i<$sort; $i++) {
		$entry_no = sprintf("%04s",$row_log['id']);
		
		$style = $row_log['brewCategory'].$row_log['brewSubCategory'];
		if ($style == "21A") $style_name = "S.H.V.";
		else $style_name = truncate($row_log['brewStyle'],22);
		
		$special = str_replace("\n"," ",truncate($row_log['brewInfo'],50));
		$special = strtr($special,$html_remove);
				
		if (in_array($style,$special_ingredients)) {
			$text = sprintf("\n%s  %s  #%s\nSpecial: %s", $row_log['brewCategory'].$row_log['brewSubCategory'], $style_name, $entry_no, $special);
			if (in_array($style,$mead)) {
				$text .= "\n";
				if ($row_log['brewMead1'] != "") $text .= sprintf("%s",$row_log['brewMead1']);
				if ($row_log['brewMead2'] != "") $text .= sprintf(" / %s",$row_log['brewMead2']);
				if ($row_log['brewMead3'] != "") $text .= sprintf(" / %s",$row_log['brewMead3']); 
				
			}
			$pdf->Add_Label($text);
		}
		
		}
		
	} while ($row_log = mysql_fetch_assoc($log));
    //$pdf->Output();
	$pdf->Output($filename,'D');
}


if (($go == "entries") && ($action == "bottle-judging") && ($view == "default")) {
	$query_log = sprintf("SELECT * FROM %s",$prefix."brewing");
	if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s'",$filter);
	$query_log .= " ORDER BY brewCategorySort,brewSubCategory,brewJudgingNumber ASC";
	$log = mysql_query($query_log, $brewing) or die(mysql_error());
	$row_log = mysql_fetch_assoc($log);
	$totalRows_log = mysql_num_rows($log);

	$filename = str_replace(" ","_",$_SESSION['contestName'])."_Bottle_Labels";
	if ($filter != "default") $filename .= "_Category_".$filter;
	$filename .= ".pdf";
	$pdf = new PDF_Label('5160'); 
	
	$pdf->AddPage();
	$pdf->SetFont('Arial','',8);

	// Print labels
	do {
		
		$entry_no = readable_judging_number($row_log['brewCategory'],$row_log['brewJudgingNumber']);																						  
		$text = sprintf("\n%s (%s)   %s (%s)   %s (%s)\n\n\n%s (%s)   %s (%s)   %s (%s)",
		$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory'], 
		$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory'],
		$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory'],
		$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory'],
		$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory'],
		$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory']
		);
		
		$pdf->Add_Label($text);
		
	} while ($row_log = mysql_fetch_assoc($log));

	$pdf->Output($filename,'D');
	
}

if (($go == "entries") && ($action == "bottle-judging-round") && ($view == "default")) {
	$query_log = sprintf("SELECT * FROM %s",$prefix."brewing");
	//if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s'",$filter);
	$query_log .= " ORDER BY brewCategorySort,brewSubCategory,brewJudgingNumber ASC";
	$log = mysql_query($query_log, $brewing) or die(mysql_error());
	$row_log = mysql_fetch_assoc($log);
	$totalRows_log = mysql_num_rows($log);

	$filename = str_replace(" ","_",$_SESSION['contestName'])."_Round_Bottle_Labels";
	//if ($filter != "default") $filename .= "_Category_".$filter;
	$filename .= ".pdf";
	
	$pdf = new PDF_Label($psort); 
	
	$pdf->AddPage();
	if ($psort == "OL32") $pdf->SetFont('Arial','',6);
	else $pdf->SetFont('Arial','',10);

	// Print labels
	do {
		for($i=0; $i<$sort; $i++) {
			if ((NHC) || ($_SESSION['prefsEntryForm'] == "N")) $entry_no = $row_log['brewJudgingNumber'];
			else $entry_no = readable_judging_number($row_log['brewCategory'],$row_log['brewJudgingNumber']);																						  
			if (($entry_no != "") && ($filter == "default")) {
			 	$text = sprintf("\n%s\n(%s)",$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory']);
				$pdf->Add_Label($text);
			}
			
			if (($entry_no != "") && ($filter == "recent") && (strtotime($row_log['brewUpdated']) > $_SESSION['contestRegistrationDeadline'])) {
				$text = sprintf("\n%s\n(%s)",$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory']);				
				$pdf->Add_Label($text);
			}
			
		}
	} while ($row_log = mysql_fetch_assoc($log));
	//$pdf->Output();
	$pdf->Output($filename,'D');
	
}

if (($go == "entries") && ($action == "bottle-entry-round") && ($view == "default")) {
	$query_log = sprintf("SELECT * FROM %s",$prefix."brewing");
	//if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s'",$filter);
	$query_log .= " ORDER BY brewCategorySort,brewSubCategory,id ASC";
	$log = mysql_query($query_log, $brewing) or die(mysql_error());
	$row_log = mysql_fetch_assoc($log);
	$totalRows_log = mysql_num_rows($log);

	$filename = str_replace(" ","_",$_SESSION['contestName'])."_Round_Bottle_Labels";
	//if ($filter != "default") $filename .= "_Category_".$filter;
	$filename .= ".pdf";

	$pdf = new PDF_Label($psort); 
	
	$pdf->AddPage();
	if ($psort == "OL32") $pdf->SetFont('Arial','',7);
	else $pdf->SetFont('Arial','',10);

	// Print labels
	do {
		for($i=0; $i<$sort; $i++) {
			$entry_no = sprintf("%04s",$row_log['id']);																						  
			
			if (($entry_no != "") && ($filter == "default")) {
			 	$text = sprintf("\n%s\n(%s)",$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory']);
				$pdf->Add_Label($text);
			}
			
			if (($entry_no != "") && ($filter == "recent") && (strtotime($row_log['brewUpdated']) > $_SESSION['contestRegistrationDeadline'])) {
				$text = sprintf("\n%s\n(%s)",$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory']);				
				$pdf->Add_Label($text);
			}
			
		}
	} while ($row_log = mysql_fetch_assoc($log));
	//$pdf->Output();
	$pdf->Output($filename,'D');
	
}

if (($go == "entries") && ($action == "bottle-category-round") && ($view == "default")) {
	
	$query_log = sprintf("SELECT brewCategorySort,brewSubCategory FROM %s ORDER BY brewCategorySort,brewSubCategory ASC",$prefix."brewing");
	if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s'",$filter);
	$log = mysql_query($query_log, $brewing) or die(mysql_error());
	$row_log = mysql_fetch_assoc($log);
	$totalRows_log = mysql_num_rows($log);
	
	//echo $query_log;
	$filename = str_replace(" ","_",$_SESSION['contestName'])."_Round_Bottle_Labels_Category_Only";
	if ($filter != "default") $filename .= "_Category_".$filter;
	$filename .= ".pdf";
	
	$pdf = new PDF_Label('OL32'); 
	
	$pdf->AddPage();
	$pdf->SetFont('Arial','',7);

	// Print labels
	do {
		for($i=0; $i<$sort; $i++) {
			$text = sprintf("\n%s",$row_log['brewCategorySort'].$row_log['brewSubCategory']);
			$pdf->Add_Label($text);
		}
	} while ($row_log = mysql_fetch_assoc($log));
	
	//$pdf->Output();
	$pdf->Output($filename,'D');
	
}

if (($go == "entries") && ($action == "bottle-entry-round") && ($view == "OL5275WR")) {
	$query_log = sprintf("SELECT * FROM %s",$prefix."brewing");
	if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s'",$filter);
	$query_log .= " ORDER BY brewCategorySort,brewSubCategory,id ASC";
	$log = mysql_query($query_log, $brewing) or die(mysql_error());
	$row_log = mysql_fetch_assoc($log);
	$totalRows_log = mysql_num_rows($log);

	$filename = str_replace(" ","_",$_SESSION['contestName'])."_Round_Bottle_Labels";
	if ($filter != "default") $filename .= "_Category_".$filter;
	$filename .= ".pdf";
	$pdf = new PDF_Label($view); 
	
	$pdf->AddPage();
	$pdf->SetFont('Arial','',7);

	// Print labels
	do {
		for($i=0; $i<$sort; $i++) {
			$entry_no = sprintf("%04s",$row_log['id']);																						  
			
			$text = sprintf("\n%s (%s)",
			$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory']
			);
			
			$pdf->Add_Label($text);
		}
	} while ($row_log = mysql_fetch_assoc($log));

	$pdf->Output($filename,'D');
	
}

if (($go == "entries") && ($action == "bottle-judging") && ($view == "special")) {
	
	$section = "brew";
	require(DB.'styles.db.php');
	
	// Custom styles where special ingredients are required
	if ($totalRows_styles2 > 0) {
		do { 
			$style_special = ltrim($row_styles2['brewStyleGroup'],"0");
			$special_ingredients[] .= $style_special.$row_styles2['brewStyleNum']; 
		}  while ($row_styles2 = mysql_fetch_assoc($styles2));
	}
	
	// print_r($special_ingredients); exit;
	
	$query_log = sprintf("SELECT * FROM %s",$prefix."brewing");
	if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s' AND brewReceived='1'",$filter);
	$query_log .= " ORDER BY brewCategorySort,brewSubCategory,brewJudgingNumber ASC";
	$log = mysql_query($query_log, $brewing) or die(mysql_error());
	$row_log = mysql_fetch_assoc($log);
	$totalRows_log = mysql_num_rows($log);

	$filename = str_replace(" ","_",$_SESSION['contestName'])."_Bottle_Labels";
	if ($filter != "default") $filename .= "_Category_".$filter;
	$filename .= "_Special_Mead-Cider";
	$filename .= ".pdf";
	$pdf = new PDF_Label('5160'); 
	
	$pdf->AddPage();
	$pdf->SetFont('Arial','',10);

	// Print labels
	do {
		
		for($i=0; $i<$sort; $i++) {
		if ((NHC) || ($_SESSION['prefsEntryForm'] == "N")) $entry_no = $row_log['brewJudgingNumber'];
		else $entry_no = readable_judging_number($row_log['brewCategory'],$row_log['brewJudgingNumber']);
		
		
		$style = $row_log['brewCategory'].$row_log['brewSubCategory'];
		if ($style == "21A") $style_name = "S.H.V.";
		else $style_name = truncate($row_log['brewStyle'],22);
		
		$special = str_replace("\n"," ",truncate($row_log['brewInfo'],50));
		$special = strtr($special,$html_remove);
				
		if (in_array($style,$special_ingredients)) {
			$text = sprintf("\n%s  %s  #%s\nSpecial: %s", $row_log['brewCategory'].$row_log['brewSubCategory'], $style_name, $entry_no, $special);
			if (in_array($style,$mead)) {
				$text .= "\n";
				if ($row_log['brewMead1'] != "") $text .= sprintf("%s",$row_log['brewMead1']);
				if ($row_log['brewMead2'] != "") $text .= sprintf(" / %s",$row_log['brewMead2']);
				if ($row_log['brewMead3'] != "") $text .= sprintf(" / %s",$row_log['brewMead3']); 
				
			}
			$pdf->Add_Label($text);
		}
		
		}
		
	} while ($row_log = mysql_fetch_assoc($log));
    //$pdf->Output();
	$pdf->Output($filename,'D');
	
}


if (($go == "participants") && ($action == "judging_nametags")) {
	$pdf = new PDF_Label('5395'); 
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',20);
	
	$query_brewer = "SELECT a.id,a.brewerFirstName,a.brewerLastName,a.brewerCity,a.brewerState,b.uid,b.staff_judge,b.staff_steward,b.staff_staff,b.staff_organizer FROM $brewer_db_table a, $staff_db_table b WHERE a.uid = b.uid ORDER BY a.brewerLastName ASC";
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
	
	$filename .= str_replace(" ","_",$_SESSION['contestName'])."_Nametags.pdf";
	
	do {
		
		if ($row_brewer['staff_judge'] == 1) $brewerAssignment = "Judge";
		if ($row_brewer['staff_steward'] == 1) $brewerAssignment = "Steward";
		if ($row_brewer['staff_staff'] == 1) $brewerAssignment = "Staff";
		if ($row_brewer['staff_organizer'] == 1) $brewerAssignment = "Organizer";
		if (strlen($row_brewer['brewerState']) == 2) $brewerState = strtoupper($row_brewer['brewerState']);
		else $brewerState = ucwords(strtolower($row_brewer['brewerState']));
		
		$text = sprintf("\n%s\n%s, %s\n%s",
		ucfirst(strtolower(strtr($row_brewer['brewerFirstName'],$html_remove)))." ".ucfirst(strtolower(strtr($row_brewer['brewerLastName'],$html_remove))),
		ucwords(strtolower($row_brewer['brewerCity'])),
		$brewerState,
		$brewerAssignment
		);
		
		$pdf->Add_Label($text);
	} while ($row_brewer = mysql_fetch_assoc($brewer));
	
	$pdf->Output($filename,'D');
}


if (($go == "participants") && ($action == "judging_labels") && ($id != "default")) {
	$pdf = new PDF_Label('5160'); 
	$pdf->AddPage();
	$pdf->SetFont('Arial','',9);
	
	$query_brewer = sprintf("SELECT id,brewerFirstName,brewerLastName,brewerJudgeID,brewerEmail,brewerJudgeRank,uid FROM $brewer_db_table WHERE id = %s",$id);
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
	//echo $query_brewer;
	$filename .= $row_brewer['brewerFirstName']."_".$row_brewer['brewerLastName']."_Judge_Scoresheet_Labels.pdf";
	
	//$rank = str_replace(",",", ",$row_brewer['brewerJudgeRank']);
	$bjcp_rank = explode(",",$row_brewer['brewerJudgeRank']);
	$rank = bjcp_rank($bjcp_rank[0],2);
	if (!empty($bjcp_rank[1])) $rank2 = $bjcp_rank[1].", ".$bjcp_rank[2];
	
	
	$j = preg_replace('/[a-zA-Z]/','',$row_brewer['brewerJudgeID']);
	//$j = ltrim($row_brewer['brewerJudgeID'],'/[a-z][A-Z]/');
	if ($j > 0) $judge_id = "- ".$row_brewer['brewerJudgeID'];
	else $judge_id = "";
	for($i=0; $i<30; $i++) {
		
		$text = sprintf("\n%s\n%s %s\n%s\n%s",
		ucfirst(strtolower(strtr($row_brewer['brewerFirstName'],$html_remove)))." ".ucfirst(strtolower(strtr($row_brewer['brewerLastName'],$html_remove))), 
		
		$rank,
		strtoupper($judge_id),
		$rank2,
		strtolower($row_brewer['brewerEmail'])
		);
		
		$pdf->Add_Label($text);
	}
	
	$pdf->Output($filename,'D');
}

if (($go == "participants") && ($action == "judging_labels") && ($id == "default")) {
	$pdf = new PDF_Label('5160'); 
	$pdf->AddPage();
	$pdf->SetFont('Arial','',9);
	
	$query_brewer = "SELECT a.id,a.brewerFirstName,a.brewerLastName,a.brewerJudgeID,a.brewerEmail,a.brewerJudgeRank,b.uid,b.staff_judge FROM $brewer_db_table a, $staff_db_table b WHERE a.uid = b.uid AND b.staff_judge='1' ORDER BY a.brewerLastName ASC";
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
	
	$filename .= str_replace(" ","_",$_SESSION['contestName'])."_All_Judge_Scoresheet_Labels.pdf";
	
	do {
		$rank = bjcp_rank($row_brewer['brewerJudgeRank'],2);
		$j = preg_replace('/[a-zA-Z]/','',$row_brewer['brewerJudgeID']);
		//$j = ltrim($row_brewer['brewerJudgeID'],'/[a-z][A-Z]/');
		if ($j > 0) $judge_id = "- ".$row_brewer['brewerJudgeID'];
		else $judge_id = "";
		for($i=0; $i<30; $i++) {
			
			$text = sprintf("\n%s\n%s %s\n%s",
			ucfirst(strtolower(strtr($row_brewer['brewerFirstName'],$html_remove)))." ".ucfirst(strtolower(strtr($row_brewer['brewerLastName'],$html_remove))), 
			$rank,
			strtoupper($judge_id),
			strtolower($row_brewer['brewerEmail'])
			);
			
			$pdf->Add_Label($text);
		}
	} while ($row_brewer = mysql_fetch_assoc($brewer));
	
	$pdf->Output($filename,'D');
}

if (($go == "participants") && ($action == "address_labels")) {
	$pdf = new PDF_Label('5160'); 
	$pdf->AddPage();
	$pdf->SetFont('Arial','',8);
	
	$query_brewer = sprintf("SELECT * FROM %s ORDER BY brewerLastName ASC",$brewer_db_table);
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	
	if ($filter == "with_entries") { 
		$filename .= str_replace(" ","_",$_SESSION['contestName'])."_Participants_With_Entries_Address_Labels.pdf";
		
		$query_with_entries = sprintf("SELECT brewBrewerID FROM %s",$brewing_db_table);
		$with_entries = mysql_query($query_with_entries, $brewing) or die(mysql_error());
		$row_with_entries = mysql_fetch_assoc($with_entries);
		
		do { $with_entries_array[] = $row_with_entries['brewBrewerID']; } while ($row_with_entries = mysql_fetch_assoc($with_entries));
		
	}
	else $filename .= str_replace(" ","_",$_SESSION['contestName'])."_All_Participant_Address_Labels.pdf";	
	
	do {
		
		if (strlen($row_brewer['brewerState']) <= 3) $brewerState = strtoupper($row_brewer['brewerState']);
		else $brewerState = ucwords(strtolower($row_brewer['brewerState']));
	
			if ($filter == "with_entries") { 
				if (in_array($row_brewer['uid'],$with_entries_array)) { 
				
				$query_with_entries_count = sprintf("SELECT COUNT(*) AS 'count' FROM %s WHERE brewBrewerID='%s'",$brewing_db_table,$row_brewer['uid']);
				$with_entries_count = mysql_query($query_with_entries_count, $brewing) or die(mysql_error());
				$row_with_entries_count = mysql_fetch_assoc($with_entries_count);
				if ($row_with_entries_count['count'] == 1) $entry_count ="(". $row_with_entries_count['count']." Entry)";
				else $entry_count = "(".$row_with_entries_count['count']." Entries)";
				
				if ($row_brewer['brewerCountry'] != "United States") $brewer_country = $row_brewer['brewerCountry']; else $brewer_country = "";
				$text = sprintf("\n%s %s\n%s\n%s, %s %s\n%s",
				ucwords(strtolower(strtr($row_brewer['brewerFirstName'],$html_remove)))." ".ucwords(strtolower(strtr($row_brewer['brewerLastName'],$html_remove))), 
				$entry_count,
				ucwords(strtolower(strtr($row_brewer['brewerAddress'],$html_remove))),
				ucwords(strtolower(strtr($row_brewer['brewerCity'],$html_remove))),
				$brewerState,
				$row_brewer['brewerZip'],
				$brewer_country
				);
				$pdf->Add_Label($text);
				}
			}
			else {
				if ($row_brewer['brewerCountry'] != "United States") $brewer_country = $row_brewer['brewerCountry']; else $brewer_country = "";
				$text = sprintf("\n%s\n%s\n%s, %s %s\n%s",
				ucwords(strtolower(strtr($row_brewer['brewerFirstName'],$html_remove)))." ".ucwords(strtolower(strtr($row_brewer['brewerLastName'],$html_remove))), 
				ucwords(strtolower(strtr($row_brewer['brewerAddress'],$html_remove))),
				ucwords(strtolower(strtr($row_brewer['brewerCity'],$html_remove))),
				$brewerState,
				$row_brewer['brewerZip'],
				$brewer_country
				);
				$pdf->Add_Label($text);
			}
	} while ($row_brewer = mysql_fetch_assoc($brewer));
	
	$pdf->Output($filename,'D');
	
}

if (($go == "judging_scores") && ($action == "awards")) {
	$pdf = new PDF_Label('5160'); 
	$pdf->AddPage();
	$pdf->SetFont('Arial','',9);
	
	$filename .= str_replace(" ","_",$_SESSION['contestName'])."_Award_Labels.pdf";
	
	$query_tables = "SELECT * FROM $judging_tables_db_table ORDER BY tableNumber";
	$tables = mysql_query($query_tables, $brewing) or die(mysql_error());
	$row_tables = mysql_fetch_assoc($tables);
	$totalRows_tables = mysql_num_rows($tables);
	
	$query_bos = "SELECT * FROM $judging_scores_bos_db_table ORDER BY scoreType,scorePlace ASC";
	$bos = mysql_query($query_bos, $brewing) or die(mysql_error());
	$row_bos = mysql_fetch_assoc($bos);
	$totalRows_bos = mysql_num_rows($bos);
	
	do {
				
		$query_entries = sprintf("SELECT id,brewBrewerFirstName,brewBrewerLastName,brewName,brewStyle,brewCategory,brewSubCategory FROM $brewing_db_table WHERE id='%s'", $row_bos['eid']);
		$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
		$row_entries = mysql_fetch_assoc($entries);
		if ($row_bos['scorePlace'] != "") { 
			$text = sprintf("\n%s\n%s\n%s\n%s",
			display_place($row_bos['scorePlace'],1)." - BEST IN SHOW",
			style_type($row_bos['scoreType'],"3","default"),
			strtr($row_entries['brewBrewerFirstName'],$html_remove)." ".strtr($row_entries['brewBrewerLastName'],$html_remove), 
			strtr($row_entries['brewName'],$html_remove)." - ".$row_entries['brewStyle']
			);
			$pdf->Add_Label($text);
		}
		
	} while ($row_bos = mysql_fetch_assoc($bos));
	
	if ($_SESSION['prefsWinnerMethod'] == "1") { // Output by Category
		$query_styles = "SELECT brewStyleGroup FROM $styles_db_table WHERE brewStyleActive='Y' ORDER BY brewStyleGroup ASC";
		$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
		$row_styles = mysql_fetch_assoc($styles);
		$totalRows_styles = mysql_num_rows($styles);
		do { $style[] = $row_styles['brewStyleGroup']; } while ($row_styles = mysql_fetch_assoc($styles));
		

		foreach (array_unique($style) as $style) {
			$query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategorySort='%s' AND brewReceived='1'", $brewing_db_table,  $style);
			$entry_count = mysql_query($query_entry_count, $brewing) or die(mysql_error());
			$row_entry_count = mysql_fetch_assoc($entry_count);
		
			$query_score_count = sprintf("SELECT  COUNT(*) as 'count' FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND a.eid = b.id AND c.uid = b.brewBrewerID AND (a.scorePlace IS NOT NULL OR a.scorePlace='')", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style);
			$score_count = mysql_query($query_score_count, $brewing) or die(mysql_error());
			$row_score_count = mysql_fetch_assoc($score_count);
			
			
			if (($row_entry_count['count'] > 0) && ($row_score_count['count'] > 0)) {
				
				$query_scores = sprintf("SELECT a.scorePlace, a.scoreEntry, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerClubs FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND a.eid = b.id AND c.uid = b.brewBrewerID AND (a.scorePlace IS NOT NULL OR a.scorePlace='') ORDER BY a.scorePlace", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style);
				$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
				$row_scores = mysql_fetch_assoc($scores);
				$totalRows_scores = mysql_num_rows($scores);
				
				do { 
				$style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
						
					$text = sprintf("\n%s%s\n%s\n%s",
					display_place($row_scores['scorePlace'],1)." - ",
					style_convert($row_scores['brewCategorySort'],1),
					strtr($row_scores['brewerFirstName'],$html_remove)." ".strtr($row_scores['brewerLastName'],$html_remove), 
					strtr($row_scores['brewName'],$html_remove)
					);
					$pdf->Add_Label($text);
				} while ($row_scores = mysql_fetch_assoc($scores)); 
			}
		}
	}
	
	elseif ($_SESSION['prefsWinnerMethod'] == "2") { // Output by sub-category
	
		$query_styles = "SELECT brewStyleGroup,brewStyleNum,brewStyle FROM $styles_db_table WHERE brewStyleActive='Y' ORDER BY brewStyleGroup,brewStyleNum ASC";
		$styles = mysql_query($query_styles, $brewing) or die(mysql_error());
		$row_styles = mysql_fetch_assoc($styles);
		$totalRows_styles = mysql_num_rows($styles);
		do { $style[] = $row_styles['brewStyleGroup']."-".$row_styles['brewStyleNum']."-".$row_styles['brewStyle']; } while ($row_styles = mysql_fetch_assoc($styles));

		foreach (array_unique($style) as $style) {
			$style = explode("-",$style);
			$query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s' AND brewReceived='1'", $brewing_db_table,  $style[0], $style[1]);
			$entry_count = mysql_query($query_entry_count, $brewing) or die(mysql_error());
			$row_entry_count = mysql_fetch_assoc($entry_count);
			
			$query_score_count = sprintf("SELECT  COUNT(*) as 'count' FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND b.brewSubCategory='%s' AND a.eid = b.id AND a.scorePlace IS NOT NULL AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style[0], $style[1]);
			$score_count = mysql_query($query_score_count, $brewing) or die(mysql_error());
			$row_score_count = mysql_fetch_assoc($score_count);
			
			
			if (($row_entry_count['count'] > 0) && ($row_score_count['count'] > 0)) {
				
				$query_scores = sprintf("SELECT a.scorePlace, b.brewName, b.brewCategory, b.brewSubCategory, b.brewStyle, c.brewerLastName, c.brewerFirstName, c.brewerClubs FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND b.brewSubCategory='%s' AND a.eid = b.id  AND c.uid = b.brewBrewerID  AND (a.scorePlace IS NOT NULL OR a.scorePlace='') ORDER BY a.scorePlace", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style[0],$style[1]);
				$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
				$row_scores = mysql_fetch_assoc($scores);
				$totalRows_scores = mysql_num_rows($scores);
						
				do { 
					$text = sprintf("\n%s%s\n%s\n%s",
					display_place($row_scores['scorePlace'],1)." - ",
					$row_scores['brewCategory'].$row_scores['brewSubCategory'].": ".$row_scores['brewStyle'],
					strtr($row_scores['brewerFirstName'],$html_remove)." ".strtr($row_scores['brewerLastName'],$html_remove), 
					strtr($row_scores['brewName'],$html_remove)
					);
					$pdf->Add_Label($text);
				} while ($row_scores = mysql_fetch_assoc($scores)); 
			}
		}
	} // end elseif ($_SESSION['prefsWinnerMethod'] == "2")
	
	else { // Output by Table.
		do {
		
		$query_scores = sprintf("SELECT * FROM %s WHERE scoreTable='%s'", $judging_scores_db_table, $row_tables['id']);
		$query_scores .= " AND (scorePlace='1' OR scorePlace='2' OR scorePlace='3' OR scorePlace='4' OR scorePlace='5') ORDER BY scorePlace ASC";
		$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
		$row_scores = mysql_fetch_assoc($scores);
		$totalRows_scores = mysql_num_rows($scores);
		
			do {
				$query_entries = sprintf("SELECT id,brewBrewerFirstName,brewBrewerLastName,brewName,brewStyle,brewCategory,brewSubCategory FROM $brewing_db_table WHERE id='%s'", $row_scores['eid']);
				$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
				$row_entries = mysql_fetch_assoc($entries);
				
				$text = sprintf("\n%s%s\n%s\n%s",
				display_place($row_scores['scorePlace'],1)." - ",
				$row_tables['tableName'],
				strtr($row_entries['brewBrewerFirstName'],$html_remove)." ".strtr($row_entries['brewBrewerLastName'],$html_remove), 
				strtr($row_entries['brewName'],$html_remove)
				);
				$pdf->Add_Label($text);
				
			} while ($row_scores = mysql_fetch_assoc($scores));
			
		} while ($row_tables = mysql_fetch_assoc($tables));
	
	}
		
	$pdf->Output($filename,'D');
}




}

else echo "<p>Not available.</p>";
?>
