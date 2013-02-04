<?php
session_start(); 
require('../paths.php'); 
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php');
require(DB.'common.db.php');
include(DB.'admin_common.db.php');
require(INCLUDES.'version.inc.php');
require(INCLUDES.'scrubber.inc.php');
require(CLASSES.'fpdf/pdf_label.php');
$special_ingredients = array("6D", "16E", "17F", "20A", "21A", "21B", "22B", "22C", "23A", "25C", "26A", "26B", "26C", "27E", "28B", "28C", "28D");
$mead = array("24A","24B","24C","25A","25B","26B","27A","27B","27C","27D","28A","26A","26C","27E","28B","28C","28D");

function truncate($string, $limit, $break=".", $pad="")
{
  // return with no change if string is shorter than $limit
  if(strlen($string) <= $limit) return $string;

  // is $break present between $limit and the end of the string?
  if(false !== ($breakpoint = strpos($string, $break, $limit))) {
    if($breakpoint < strlen($string) - 1) {
      $string = substr($string, 0, $breakpoint) . $pad;
    }
  }

  return $string;
}

if (($go == "entries") && ($action == "bottle-entry") && ($view != "special")) {
	$query_log = "SELECT * FROM $brewing_db_table";
	if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s'",$filter);
	$query_log .= " ORDER BY brewCategorySort,brewSubCategory,id ASC";
	$log = mysql_query($query_log, $brewing) or die(mysql_error());
	$row_log = mysql_fetch_assoc($log);
	$totalRows_log = mysql_num_rows($log);

	$filename = str_replace(" ","_",$row_contest_info['contestName'])."_Bottle_Labels";
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
	$query_log = "SELECT * FROM $brewing_db_table";
	if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s'",$filter);
	$query_log .= " ORDER BY brewCategorySort,brewSubCategory,id ASC";
	$log = mysql_query($query_log, $brewing) or die(mysql_error());
	$row_log = mysql_fetch_assoc($log);
	$totalRows_log = mysql_num_rows($log);

	$filename = str_replace(" ","_",$row_contest_info['contestName'])."_Bottle_Labels";
	if ($filter != "default") $filename .= "_Category_".$filter;
	$filename .= ".pdf";
	$pdf = new PDF_Label('5160'); 
	$pdf->AddPage();
	$pdf->SetFont('Arial','',8);

	// Print labels
	do {
		
		$entry_no = sprintf("%04s",$row_log['id']);																						  
		
		$style = $row_log['brewCategory'].$row_log['brewSubCategory'];
				
		if (in_array($style,$special_ingredients)) {
			$text = sprintf("\n%s (%s) Special: %s",
			$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory'], 
			str_replace("\n"," ",truncate($row_log['brewInfo'],120)));
			$pdf->Add_Label($text);
		}
		
	} while ($row_log = mysql_fetch_assoc($log));

	$pdf->Output($filename,'D');
}


if (($go == "entries") && ($action == "bottle-judging") && ($view == "default")) {
	$query_log = sprintf("SELECT * FROM %s",$prefix."brewing");
	if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s'",$filter);
	$query_log .= " ORDER BY brewCategorySort,brewSubCategory,brewJudgingNumber ASC";
	$log = mysql_query($query_log, $brewing) or die(mysql_error());
	$row_log = mysql_fetch_assoc($log);
	$totalRows_log = mysql_num_rows($log);

	$filename = str_replace(" ","_",$row_contest_info['contestName'])."_Bottle_Labels";
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
	if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s'",$filter);
	$query_log .= " ORDER BY brewCategorySort,brewSubCategory,brewJudgingNumber ASC";
	$log = mysql_query($query_log, $brewing) or die(mysql_error());
	$row_log = mysql_fetch_assoc($log);
	$totalRows_log = mysql_num_rows($log);

	$filename = str_replace(" ","_",$row_contest_info['contestName'])."_Round_Bottle_Labels";
	if ($filter != "default") $filename .= "_Category_".$filter;
	$filename .= ".pdf";
	$pdf = new PDF_Label('OL32'); 
	
	$pdf->AddPage();
	$pdf->SetFont('Arial','',7);

	// Print labels
	do {
		for($i=0; $i<$sort; $i++) {
			$entry_no = readable_judging_number($row_log['brewCategory'],$row_log['brewJudgingNumber']);																						  
			
			$text = sprintf("\n%s (%s)",
			$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory']
			);
			
			$pdf->Add_Label($text);
		}
	} while ($row_log = mysql_fetch_assoc($log));

	$pdf->Output($filename,'D');
	
}

if (($go == "entries") && ($action == "bottle-entry-round") && ($view == "default")) {
	$query_log = sprintf("SELECT * FROM %s",$prefix."brewing");
	if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s'",$filter);
	$query_log .= " ORDER BY brewCategorySort,brewSubCategory,id ASC";
	$log = mysql_query($query_log, $brewing) or die(mysql_error());
	$row_log = mysql_fetch_assoc($log);
	$totalRows_log = mysql_num_rows($log);

	$filename = str_replace(" ","_",$row_contest_info['contestName'])."_Round_Bottle_Labels";
	if ($filter != "default") $filename .= "_Category_".$filter;
	$filename .= ".pdf";
	$pdf = new PDF_Label('OL32'); 
	
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

if (($go == "entries") && ($action == "nhc") && ($view == "default")) {
	
	$query_log = sprintf("SELECT id FROM %s ORDER BY brewName ASC",$prefix."brewing");
	$log = mysql_query($query_log, $brewing) or die(mysql_error());
	$row_log = mysql_fetch_assoc($log);
	$totalRows_log = mysql_num_rows($log);

	$filename = str_replace(" ","_",$row_contest_info['contestName'])."_Round_Bottle_Labels";
	if ($filter != "default") $filename .= "_Category_".$filter;
	$filename .= ".pdf";
	$pdf = new PDF_Label('OL32'); 
	
	$pdf->AddPage();
	$pdf->SetFont('Arial','',7);

	// Print labels
	for($i=1; $i<=999; $i++) {
		for($z=0; $z<6; $z++) {
			//$barcode_link = $base_url."/includes/barcode/html/image.php?filetype=PNG&dpi=300&scale=2&rotation=0&font_family=Arial.ttf&font_size=8&text=".$i."&thickness=30&checksum=&code=BCGi25";
			$barcode_link = $base_url."/images/barcodes/0111.png";
			$image = $pdf->Image($barcode_link);
			$text = sprintf("\n%s\n%s",$i,$image);
			$pdf->Add_Label($text);
		}
	}

	$pdf->Output($filename,'D');
	
}

if (($go == "entries") && ($action == "bottle-entry-round") && ($view == "OL5275WR")) {
	$query_log = sprintf("SELECT * FROM %s",$prefix."brewing");
	if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s'",$filter);
	$query_log .= " ORDER BY brewCategorySort,brewSubCategory,id ASC";
	$log = mysql_query($query_log, $brewing) or die(mysql_error());
	$row_log = mysql_fetch_assoc($log);
	$totalRows_log = mysql_num_rows($log);

	$filename = str_replace(" ","_",$row_contest_info['contestName'])."_Round_Bottle_Labels";
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
	$query_log = sprintf("SELECT * FROM %s",$prefix."brewing");
	if ($filter != "default") $query_log .= sprintf(" WHERE brewCategorySort='%s'",$filter);
	$query_log .= " ORDER BY brewCategorySort,brewSubCategory,brewJudgingNumber ASC";
	$log = mysql_query($query_log, $brewing) or die(mysql_error());
	$row_log = mysql_fetch_assoc($log);
	$totalRows_log = mysql_num_rows($log);

	$filename = str_replace(" ","_",$row_contest_info['contestName'])."_Bottle_Labels";
	if ($filter != "default") $filename .= "_Category_".$filter;
	$filename .= "_Special_Mead-Cider";
	$filename .= ".pdf";
	$pdf = new PDF_Label('5160'); 
	
	$pdf->AddPage();
	$pdf->SetFont('Arial','',7);

	// Print labels
	do {
		
		if (!NHC) $entry_no = readable_judging_number($row_log['brewCategory'],$row_log['brewJudgingNumber']);
		else $entry_no = $row_log['brewJudgingNumber'];
		
		$style = $row_log['brewCategory'].$row_log['brewSubCategory'];
		
		$special = str_replace("\n"," ",truncate($row_log['brewInfo'],50));
		$special = strtr($special,$html_remove);
				
		if (in_array($style,$special_ingredients)) {
			$text = sprintf("\n%s (%s) %s\nS/C: %s", $entry_no, $row_log['brewCategory'].$row_log['brewSubCategory'], $row_log['brewStyle'], $special);
			if (in_array($style,$mead)) {
				$text .= sprintf("\nM/C: %s %s %s",$row_log['brewMead1'],$row_log['brewMead2'],$row_log['brewMead3']);
			}
			$pdf->Add_Label($text);
		}
		
	} while ($row_log = mysql_fetch_assoc($log));

	$pdf->Output($filename,'D');
	
}


if (($go == "participants") && ($action == "judging_nametags")) {
	$pdf = new PDF_Label('5395'); 
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',20);
	
	$query_brewer = "SELECT id,brewerFirstName,brewerLastName,brewerCity,brewerState,brewerAssignment FROM $brewer_db_table WHERE brewerAssignment='J' OR brewerAssignment='S' OR brewerAssignment='X' OR brewerAssignment='O' ORDER BY brewerAssignment,brewerLastName ASC";
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
	
	$filename .= str_replace(" ","_",$row_contest_info['contestName'])."_Nametags.pdf";
	
	do {
		
		if ($row_brewer['brewerAssignment'] == "J") $brewerAssignment = "Judge";
		if ($row_brewer['brewerAssignment'] == "S") $brewerAssignment = "Steward";
		if ($row_brewer['brewerAssignment'] == "X") $brewerAssignment = "Staff";
		if ($row_brewer['brewerAssignment'] == "O") $brewerAssignment = "Organizer";
		
		$text = sprintf("\n%s\n%s, %s\n%s",
		strtr($row_brewer['brewerFirstName'],$html_remove)." ".strtr($row_brewer['brewerLastName'],$html_remove),
		$row_brewer['brewerCity'],
		$row_brewer['brewerState'],
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
	
	$query_brewer = "SELECT id,brewerFirstName,brewerLastName,brewerJudgeRank,brewerJudgeID,brewerEmail FROM $brewer_db_table WHERE id = '$id'";
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
	
	$filename .= $row_brewer['brewerFirstName']."_".$row_brewer['brewerLastName']."_Judge_Scoresheet_Labels.pdf";
	
	$rank = bjcp_rank($row_brewer['brewerJudgeRank'],2);
	$j = preg_replace('/[a-zA-Z]/','',$row_brewer['brewerJudgeID']);
	//$j = ltrim($row_brewer['brewerJudgeID'],'/[a-z][A-Z]/');
	if ($j > 0) $judge_id = "- ".$row_brewer['brewerJudgeID'];
	else $judge_id = "";
	for($i=0; $i<30; $i++) {
		
		$text = sprintf("\n%s\n%s %s\n%s",
		strtr($row_brewer['brewerFirstName'],$html_remove)." ".strtr($row_brewer['brewerLastName'],$html_remove), 
		$rank,
		$judge_id,
		$row_brewer['brewerEmail']
		);
		
		$pdf->Add_Label($text);
	}
	
	$pdf->Output($filename,'D');
}

if (($go == "participants") && ($action == "judging_labels") && ($id == "default")) {
	$pdf = new PDF_Label('5160'); 
	$pdf->AddPage();
	$pdf->SetFont('Arial','',9);
	
	$query_brewer = "SELECT id,brewerFirstName,brewerLastName,brewerJudgeRank,brewerJudgeID,brewerEmail FROM $brewer_db_table WHERE brewerAssignment='J' ORDER BY brewerLastName ASC";
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
	
	$filename .= str_replace(" ","_",$row_contest_info['contestName'])."_All_Judge_Scoresheet_Labels.pdf";
	
	do {
		$rank = bjcp_rank($row_brewer['brewerJudgeRank'],2);
		$j = preg_replace('/[a-zA-Z]/','',$row_brewer['brewerJudgeID']);
		//$j = ltrim($row_brewer['brewerJudgeID'],'/[a-z][A-Z]/');
		if ($j > 0) $judge_id = "- ".$row_brewer['brewerJudgeID'];
		else $judge_id = "";
		for($i=0; $i<30; $i++) {
			
			$text = sprintf("\n%s\n%s %s\n%s",
			strtr($row_brewer['brewerFirstName'],$html_remove)." ".strtr($row_brewer['brewerLastName'],$html_remove),  
			$rank,
			$judge_id,
			$row_brewer['brewerEmail']
			);
			
			$pdf->Add_Label($text);
		}
	} while ($row_brewer = mysql_fetch_assoc($brewer));
	
	$pdf->Output($filename,'D');
}

if (($go == "participants") && ($action == "address_labels")) {
	$pdf = new PDF_Label('5160'); 
	$pdf->AddPage();
	$pdf->SetFont('Arial','',9);
	
	$query_brewer = sprintf("SELECT * FROM %s ORDER BY brewerLastName ASC",$brewer_db_table);
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$filename .= str_replace(" ","_",$row_contest_info['contestName'])."_All_Participant_Address_Labels.pdf";

	do {
			if ($row_brewer['brewerCountry'] != "United States") $brewer_country = $row_brewer['brewerCountry']; else $brewer_country = "";
			$text = sprintf("\n%s\n%s\n%s, %s %s\n%s",
			strtr($row_brewer['brewerFirstName'],$html_remove)." ".strtr($row_brewer['brewerLastName'],$html_remove), 
			strtr($row_brewer['brewerAddress'],$html_remove),
			strtr($row_brewer['brewerCity'],$html_remove),
			$row_brewer['brewerState'],
			$row_brewer['brewerZip'],
			$brewer_country
			);
			$pdf->Add_Label($text);
	} while ($row_brewer = mysql_fetch_assoc($brewer));
	
	$pdf->Output($filename,'D');
}


if (($go == "judging_scores") && ($action == "awards")) {
	$pdf = new PDF_Label('5160'); 
	$pdf->AddPage();
	$pdf->SetFont('Arial','',9);
	
	$filename .= str_replace(" ","_",$row_contest_info['contestName'])."_Award_Labels.pdf";
	
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
	
	if ($row_prefs['prefsWinnerMethod'] == "1") { // Output by Category
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
	
	elseif ($row_prefs['prefsWinnerMethod'] == "2") { // Output by sub-category
	
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
	} // end elseif ($row_prefs['prefsWinnerMethod'] == "2")
	
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

?>
