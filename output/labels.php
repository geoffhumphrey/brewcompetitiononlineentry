<?php
session_start(); 
require('../paths.php'); 
require(CONFIG.'bootstrap.php');
require(CLASSES.'fpdf/pdf_label.php');
mysql_select_db($database, $brewing);
include(DB.'output_labels.db.php');
include(LIB.'output.lib.php');

if (isset($_SESSION['loginUsername'])) {
	// Special ingredients required
	$special_ingredients = array("6D","16E","17F","20A","21A","21B","22B","22C","23A","25C","26A","26B","26C","27E","28B","28C","28D");
	
	// Mead/cider info required
	$mead = array("24A","24B","24C","25A","25B","25C","26A","26B","26C","27A","27B","27C","27D","27E","28A","26A","26C","27E","28B","28C","28D");

	if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {
		
		if (($go == "entries") && ($action == "bottle-entry") && ($view != "special")) {
		
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
				
				$text = iconv('UTF-8', 'windows-1252', $text);
				$pdf->Add_Label($text);
				
			} while ($row_log = mysql_fetch_assoc($log));
		
			ob_end_clean();
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
					$text = iconv('UTF-8', 'windows-1252', $text);
					$pdf->Add_Label($text);
				}
				
				}
				
			} while ($row_log = mysql_fetch_assoc($log));
			//$pdf->Output();
			ob_end_clean();
			$pdf->Output($filename,'D');
		}
		
		
		if (($go == "entries") && ($action == "bottle-judging") && ($view == "default")) {
		
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
				$text = iconv('UTF-8', 'windows-1252', $text);
				$pdf->Add_Label($text);
				
				//echo $text;
				
			} while ($row_log = mysql_fetch_assoc($log));

			//$pdf->Output();
			ob_end_clean();
			$pdf->Output($filename,'D');
			
		}
		
		if (($go == "entries") && ($action == "bottle-judging-round") && ($view == "default")) {
		
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
						$text = iconv('UTF-8', 'windows-1252', $text);
						$pdf->Add_Label($text);
					}
					
					if (($entry_no != "") && ($filter == "recent") && (strtotime($row_log['brewUpdated']) > $row_contest_dates['contestRegistrationDeadline'])) {
						$text = sprintf("\n%s\n(%s)",$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory']);	
						$text = iconv('UTF-8', 'windows-1252', $text);
						$pdf->Add_Label($text);
					}
					
				}
			} while ($row_log = mysql_fetch_assoc($log));
			//$pdf->Output();
			ob_end_clean();
			$pdf->Output($filename,'D');
			
		}
		
		if (($go == "entries") && ($action == "bottle-entry-round") && ($view == "default")) {
		
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
					
					if (($entry_no != "") && ($filter == "recent") && (strtotime($row_log['brewUpdated']) > $row_contest_dates['contestRegistrationDeadline'])) {
						$text = sprintf("\n%s\n(%s)",$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory']);				
						$text = iconv('UTF-8', 'windows-1252', $text);
						$pdf->Add_Label($text);
					}
					
				}
			} while ($row_log = mysql_fetch_assoc($log));
			//$pdf->Output();
			ob_end_clean();
			$pdf->Output($filename,'D');
			
		}
		
		if (($go == "entries") && ($action == "bottle-category-round") && ($view == "default")) {
	
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
					$text = iconv('UTF-8', 'windows-1252', $text);
					$pdf->Add_Label($text);
				}
			} while ($row_log = mysql_fetch_assoc($log));
			
			//$pdf->Output();
			ob_end_clean();
			$pdf->Output($filename,'D');
			
		}
		
		if (($go == "entries") && ($action == "bottle-entry-round") && ($view == "OL5275WR")) {
		
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
					
					$text = iconv('UTF-8', 'windows-1252', $text);
					$pdf->Add_Label($text);
				}
			} while ($row_log = mysql_fetch_assoc($log));
		
			ob_end_clean();
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
					$text = iconv('UTF-8', 'windows-1252', $text);
					$pdf->Add_Label($text);
				}
				
				}
				
			} while ($row_log = mysql_fetch_assoc($log));
			//$pdf->Output();
			ob_end_clean();
			$pdf->Output($filename,'D');
			
		}
		
		if (($go == "participants") && ($action == "judging_nametags")) {
			$pdf = new PDF_Label('5395'); 
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',20);
			
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
				
				$text = iconv('UTF-8', 'windows-1252', $text);
				$pdf->Add_Label($text);
			} while ($row_brewer = mysql_fetch_assoc($brewer));
			
			ob_end_clean();
			$pdf->Output($filename,'D');
		}
	
	
		if (($go == "participants") && ($action == "judging_labels") && ($id == "default")) {
			$pdf = new PDF_Label('5160'); 
			$pdf->AddPage();
			$pdf->SetFont('Arial','',9);
			
			$filename .= str_replace(" ","_",$_SESSION['contestName'])."_All_Judge_Scoresheet_Labels.pdf";
			
			do {
				
				$bjcp_rank = explode(",",$row_brewer['brewerJudgeRank']);
	
				/*
				$bjcp_rank1 = $bjcp_rank[0].",";
				$other_ranks = str_replace($bjcp_rank1,"",$row_brewer['brewerJudgeRank']);
				$other_ranks = str_replace(",",", ",$other_ranks);
				*/
				
				$rank = bjcp_rank($bjcp_rank[0],2);
				//if (!empty($other_ranks)) $rank .= ", ".$other_ranks;
				
				if (!empty($bjcp_rank[1])) $rank .= ", ".$bjcp_rank[1];
				if (!empty($bjcp_rank[2])) $rank .= ", ".$bjcp_rank[2];
			
				
				$j = preg_replace('/[a-zA-Z]/','',$row_brewer['brewerJudgeID']);
				
				$first_name = strtr($row_brewer['brewerFirstName'],$html_remove);
				$first_name = ucfirst(strtolower($first_name));
				$last_name = strtr($row_brewer['brewerLastName'],$html_remove);
				$last_name = ucfirst(strtolower($last_name));
					
				//$j = ltrim($row_brewer['brewerJudgeID'],'/[a-z][A-Z]/');
				if ($j > 0) $judge_id = "- ".$row_brewer['brewerJudgeID'];
				else $judge_id = "";
				for($i=0; $i<30; $i++) {
					
					$text = sprintf("\n%s %s\n%s %s\n%s",
					$first_name,
					$last_name,
					truncate($rank,50),
					strtoupper($judge_id),
					strtolower($row_brewer['brewerEmail'])
					);
					
					$text = iconv('UTF-8', 'windows-1252', $text);
					$pdf->Add_Label($text);
				}
			} while ($row_brewer = mysql_fetch_assoc($brewer));
			
			$pdf->Output($filename,'D');
		}
		
		if (($go == "participants") && ($action == "address_labels")) {
		$pdf = new PDF_Label('5160'); 
		$pdf->AddPage();
		$pdf->SetFont('Arial','',8);
		
		if ($filter == "with_entries") { 
			$filename .= str_replace(" ","_",$_SESSION['contestName'])."_Participants_With_Entries_Address_Labels.pdf";
			
			do { $with_entries_array[] = $row_with_entries['brewBrewerID']; } while ($row_with_entries = mysql_fetch_assoc($with_entries));
			
		}
		else $filename .= str_replace(" ","_",$_SESSION['contestName'])."_All_Participant_Address_Labels.pdf";	
		
		do {
			
			if (strlen($row_brewer['brewerState']) <= 3) $brewerState = strtoupper($row_brewer['brewerState']);
			else $brewerState = ucwords(strtolower($row_brewer['brewerState']));
		
				if ($filter == "with_entries") { 
					if (in_array($row_brewer['uid'],$with_entries_array)) { 
					
					$user_entry_count = user_entry_count($row_brewer['uid']);
					
					if ($user_entry_count == 1) $entry_count ="(". $user_entry_count." Entry)";
					else $entry_count = "(".$user_entry_count." Entries)";
					
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
		
		//$pdf->Output();
		$pdf->Output($filename,'D');
	}
	

	
		if (($go == "judging_scores") && ($action == "awards")) {
			$pdf = new PDF_Label('5160'); 
			$pdf->AddPage();
			$pdf->SetFont('Arial','',9);
			
			$filename .= str_replace(" ","_",$_SESSION['contestName'])."_Award_Labels.pdf";
			
			include(DB.'output_labels_awards.db.php');
				
			ob_end_clean();
			$pdf->Output($filename,'D');
		}
		
		
	}	// end if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))
	
// --------------------------------------------------------
// The following is the only label output that non-admins
// can access.
// --------------------------------------------------------

	if (($go == "participants") && ($action == "judging_labels") && ($id != "default")) {
		$pdf = new PDF_Label('5160'); 
		$pdf->AddPage();
		$pdf->SetFont('Arial','',8);

		$first_name = strtr($row_brewer['brewerFirstName'],$html_remove);
		$first_name = ucfirst(strtolower($first_name));
		$last_name = strtr($row_brewer['brewerLastName'],$html_remove);
		$last_name = ucfirst(strtolower($last_name));
		
		//echo $query_brewer;
		$filename .= $first_name."_".$last_name."_Judge_Scoresheet_Labels.pdf";
		
		//$rank = str_replace(",",", ",$row_brewer['brewerJudgeRank']);
		$bjcp_rank = explode(",",$row_brewer['brewerJudgeRank']);
		$rank = bjcp_rank($bjcp_rank[0],2);
		if (!empty($bjcp_rank[1])) $rank .= ", ".$bjcp_rank[1];
		if (!empty($bjcp_rank[2])) $rank .= ", ".$bjcp_rank[2];
		//$rank2 = truncate($rank2,50);
			
		$j = preg_replace('/[a-zA-Z]/','',$row_brewer['brewerJudgeID']);
		//$j = ltrim($row_brewer['brewerJudgeID'],'/[a-z][A-Z]/');
		if ($j > 0) $judge_id = " (".$row_brewer['brewerJudgeID'].")";
		else $judge_id = "";
		for($i=0; $i<30; $i++) {
			
			$text = sprintf("\n%s %s\n%s %s\n%s",
			$first_name, 
			$last_name,
			truncate($rank,50),
			strtoupper($judge_id),
			strtolower($row_brewer['brewerEmail'])
			);
			$text = iconv('UTF-8', 'windows-1252', $text);
			$pdf->Add_Label($text);
		}
		
		$pdf->Output($filename,'D');
	}

}

else echo "<p>Not available.</p>";
?>