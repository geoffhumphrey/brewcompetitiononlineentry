<?php
<<<<<<< HEAD
require('../paths.php');

=======

require('../paths.php');
session_name($prefix_session);
session_start();
>>>>>>> origin/master
require(CONFIG.'bootstrap.php');
require(CLASSES.'fpdf/pdf_label.php');

mysqli_select_db($connection,$database);

include(DB.'output_labels.db.php');
include(LIB.'output.lib.php');
include(DB.'styles.db.php');

$filename = "";

if (isset($_SESSION['loginUsername'])) {
<<<<<<< HEAD
	if ($psort == "3422") $number_of_labels = "24";
	if ($psort == "5160") $number_of_labels = "30";
	
	// Get Special Ingredients
	if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) {
		// Need to update to loop through DB
		do {
			// Special ingredients required
			if ($row_styles['brewStyleReqSpec'] == 1) {
				$special_ingredients[] = $row_styles['brewStyleGroup'].$row_styles['brewStyleNum'];
			}
			// Mead/cider info required
			if (($row_styles['brewStyleStrength'] == 1) || ($row_styles['brewStyleCarb'] == 1) || ($row_styles['brewStyleSweet'] == 1)) {
				$mead[] = $row_styles['brewStyleGroup'].$row_styles['brewStyleNum'];
			}
		} while ($row_styles = mysqli_fetch_assoc($styles));
	}
	
	else {
		
		include (INCLUDES.'ba_constants.inc.php');
		$special_ingredients = $ba_special_ids;
		$mead = $ba_special_mead_cider_ids;
		
	}
	
=======


	if ($psort == "3422") $number_of_labels = "24";
	if ($psort == "5160") $number_of_labels = "30";

	// Need to update to loop through DB
	do {
		// Special ingredients required
		if ($row_styles['brewStyleReqSpec'] == 1) {
			$special_ingredients[] = $row_styles['brewStyleGroup'].$row_styles['brewStyleNum'];
		}

		// Mead/cider info required
		if (($row_styles['brewStyleStrength'] == 1) || ($row_styles['brewStyleCarb'] == 1) || ($row_styles['brewStyleSweet'] == 1)) {
			$mead[] = $row_styles['brewStyleGroup'].$row_styles['brewStyleNum'];
		}

	} while ($row_styles = mysqli_fetch_assoc($styles));




>>>>>>> origin/master
	if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {

		if (($go == "entries") && ($action == "bottle-entry") && ($view == "default")) {
<<<<<<< HEAD
			
=======

>>>>>>> origin/master
			$filename = str_replace(" ","_",$_SESSION['contestName'])."_Bottle_Labels_Entry_Numbers";
			if ($filter != "default") 	$filename .= "_Category_".$filter;
			if ($psort == "3422") 		$filename .= "_Avery3422";
			else 						$filename .= "_Avery5160";
			$filename .= ".pdf";
			
			if ($psort == "3422") $pdf = new PDF_Label('3422');
			else $pdf = new PDF_Label('5160');
			$pdf->AddPage();
			$pdf->SetFont('Arial','',8);
<<<<<<< HEAD
			
			// Print labels
			do {
				
				$entry_no = sprintf("%04s",$row_log['id']);
=======

			// Print labels
			do {

				$entry_no = sprintf("%04s",$row_log['id']);

>>>>>>> origin/master
				$text = sprintf("\n%s (%s)   %s (%s)   %s (%s)\n\n\n%s (%s)   %s (%s)   %s (%s)",
				$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory'],
				$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory'],
				$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory'],
				$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory'],
				$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory'],
				$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory']
				);
<<<<<<< HEAD
=======

>>>>>>> origin/master
				$text = iconv('UTF-8', 'windows-1252', $text);
				$pdf->Add_Label($text);

			} while ($row_log = mysqli_fetch_assoc($log));
<<<<<<< HEAD
			
=======

>>>>>>> origin/master
			ob_end_clean();
			$pdf->Output($filename,'D');
			
		}
<<<<<<< HEAD
		
		if (($go == "entries") && ($action == "bottle-entry") && ($view == "special")) {
			
=======


		if (($go == "entries") && ($action == "bottle-entry") && ($view == "special")) {


>>>>>>> origin/master
			$filename = str_replace(" ","_",$_SESSION['contestName'])."_Bottle_Labels_Entry_Numbers";
			if ($filter != "default") 	$filename .= "_Category_".$filter;
										$filename .= "_Req_Info_Mead-Cider";
			if ($psort == "3422") 		$filename .= "_Avery3422";
			else 						$filename .= "_Avery5160";
			$filename .= ".pdf";
<<<<<<< HEAD
			
			if ($psort == "3422") $pdf = new PDF_Label('3422');
			else $pdf = new PDF_Label('5160');
			$pdf->AddPage();
			$pdf->SetFont('Arial','',9);
			
			// Print labels
			do {
				
				$text = "";
				
				for($i=0; $i<$sort; $i++) {
					
					$entry_no = sprintf("%04s",$row_log['id']);
						
					$style_name = truncate($row_log['brewStyle'],22);
					if (strlen($style_name) == 22) $style_name .= "...";
					
					$special = str_replace("\n"," ",truncate($row_log['brewInfo'],50));
					$special = strtr($special,$html_remove);
					
					$optional = "";
					if (!empty($row_log['brewInfoOptional'])) $optional .= strtr($row_log['brewInfoOptional'],$html_remove);
					
					if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) {
						$style = $row_log['brewCategorySort'].$row_log['brewSubCategory'];
						if (in_array($style,$special_ingredients)) {
							$text = sprintf("\n%s  %s  #%s\nReq: %s", $style, $style_name, $entry_no, $special);
							if (!empty($optional)) $text .= sprintf("\nOp: %s",$optional);	
						}
					}
					
					else {
						$style = $row_log['brewSubCategory'];
						if (in_array($style,$special_ingredients)) { 
							$text = sprintf("\n%s  #%s\nReq: %s", $style_name, $entry_no, $special);
							if (!empty($optional)) $text .= sprintf("\nOp: %s",$optional);
						}
					}
					
					if (!empty($text)) {
						$text = iconv('UTF-8', 'windows-1252', $text);
						$pdf->Add_Label($text);
					}
					
=======
			if ($psort == "3422") $pdf = new PDF_Label('3422');
			else $pdf = new PDF_Label('5160');

			$pdf->AddPage();
			$pdf->SetFont('Arial','',10);

			// Print labels
			do {
				$text = '';
				for($i=0; $i<$sort; $i++) {
				$entry_no = sprintf("%04s",$row_log['id']);

				$style = $row_log['brewCategorySort'].$row_log['brewSubCategory'];
				if ($style == "21A") $style_name = "S.H.V.";
				else $style_name = truncate($row_log['brewStyle'],22);

				$special = str_replace("\n"," ",truncate($row_log['brewInfo'],50));
				$special = str_replace("^","; ",$special);
				$special = strtr($special,$html_remove);

				if (in_array($style,$special_ingredients)) {
					$text = sprintf("\n%s  %s  #%s\nReq Info: %s", $row_log['brewCategory'].$row_log['brewSubCategory'], $style_name, $entry_no, $special);
				}

				if (in_array($style,$mead)) {
						$text .= "\n";
						if (!empty($row_log['brewMead1'])) $text .= sprintf("%s",$row_log['brewMead1']);
						if (!empty($row_log['brewMead2'])) $text .= sprintf(" / %s",$row_log['brewMead2']);
						if (!empty($row_log['brewMead3'])) $text .= sprintf(" / %s",$row_log['brewMead3']);
				}

				if ( ! empty( $text ) ) {
					$text = iconv('UTF-8', 'windows-1252', $text);
					$pdf->Add_Label($text);
				}

>>>>>>> origin/master
				}

			} while ($row_log = mysqli_fetch_assoc($log));
			
			//$pdf->Output();
			ob_end_clean();
			$pdf->Output($filename,'D');
			
		}
<<<<<<< HEAD
		
=======


>>>>>>> origin/master
		if (($go == "entries") && ($action == "bottle-entry") && ($view == "all")) {

			$filename = str_replace(" ","_",$_SESSION['contestName'])."_Bottle_Labels_Entry_Numbers";
			if ($filter != "default")	$filename .= "_Category_".$filter;
										$filename .= "_Req_Info_Mead-Cider";
			if ($psort == "3422") 		$filename .= "_Avery3422";
			else 						$filename .= "_Avery5160";
			$filename .= ".pdf";
<<<<<<< HEAD
			
			if ($psort == "3422") $pdf = new PDF_Label('3422');
			else $pdf = new PDF_Label('5160');
			$pdf->AddPage();
			$pdf->SetFont('Arial','',9);
			
=======
			if ($psort == "3422") $pdf = new PDF_Label('3422');
			else $pdf = new PDF_Label('5160');

			$pdf->AddPage();
			$pdf->SetFont('Arial','',10);

>>>>>>> origin/master
			// Print labels
			do {

				for($i=0; $i<$sort; $i++) {
<<<<<<< HEAD
					
					$text = "";
							
					$entry_no = sprintf("%04s",$row_log['id']);
						
					$style_name = truncate($row_log['brewStyle'],22);
					if (strlen($style_name) == 22) $style_name .= "...";
					
					if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) {
						
						$style = $row_log['brewCategorySort'].$row_log['brewSubCategory'];
						$text = sprintf("\n%s  %s  #%s", $style, $style_name, $entry_no);
						
						if (in_array($style,$special_ingredients)) {
							
							$special = str_replace("\n"," ",truncate($row_log['brewInfo'],50));
							$special = strtr($special,$html_remove);
							
							$optional = "";
							if (!empty($row_log['brewInfoOptional'])) $optional .= strtr($row_log['brewInfoOptional'],$html_remove);
							
							$text .= sprintf("\n%s  %s  #%s\nReq: %s", $style, $style_name, $entry_no, $special);
							if (!empty($optional)) $text .= sprintf("\nOp: %s",$optional);	
							
						}
						
					}
					
					else {
						
						$style = $row_log['brewSubCategory'];
						$text = sprintf("\n%s  #%s", $style_name, $entry_no);
						
						if (in_array($style,$special_ingredients)) { 
							
							$special = str_replace("\n"," ",truncate($row_log['brewInfo'],50));
							$special = strtr($special,$html_remove);
							
							$optional = "";
							if (!empty($row_log['brewInfoOptional'])) $optional .= strtr($row_log['brewInfoOptional'],$html_remove);
							
							$text = sprintf("\n%s  #%s\nReq: %s", $style_name, $entry_no, $special);
							if (!empty($optional)) $text .= sprintf("\nOp: %s",$optional);
							
						}
						
					}
					
=======
					$entry_no = sprintf("%04s",$row_log['id']);

					$style = $row_log['brewCategorySort'].$row_log['brewSubCategory'];
					if ($style == "21A") $style_name = "S.H.V.";
					else $style_name = truncate($row_log['brewStyle'],22);

					$style = $row_log['brewCategorySort'].$row_log['brewSubCategory'];
					$style_name = truncate($row_log['brewStyle'],22);

					$text = sprintf("\n%s  %s  #%s", $row_log['brewCategory'].$row_log['brewSubCategory'], $style_name, $entry_no);


					if (in_array($style,$special_ingredients)) {
						$special = str_replace("\n"," ",truncate($row_log['brewInfo'],50));
						$special = strtr($special,$html_remove);
						$text .= sprintf("\nReq Info: %s", $special);
					}

>>>>>>> origin/master
					if (in_array($style,$mead)) {
							
							$text .= "\n";
							
							if (!empty($row_log['brewMead1'])) $text .= sprintf("%s",$row_log['brewMead1']);
							if (!empty($row_log['brewMead2'])) $text .= sprintf(" / %s",$row_log['brewMead2']);
							if (!empty($row_log['brewMead3'])) $text .= sprintf(" / %s",$row_log['brewMead3']);
<<<<<<< HEAD
							
					}
					
=======
					}

>>>>>>> origin/master
					$text = iconv('UTF-8', 'windows-1252', $text);
					$pdf->Add_Label($text);
					
				}

			} while ($row_log = mysqli_fetch_assoc($log));
			
			//$pdf->Output();
			ob_end_clean();
			$pdf->Output($filename,'D');

		}
<<<<<<< HEAD
		
		if (($go == "entries") && ($action == "bottle-judging") && ($view == "default")) {
			
=======


		if (($go == "entries") && ($action == "bottle-judging") && ($view == "default")) {

>>>>>>> origin/master
			$filename = str_replace(" ","_",$_SESSION['contestName'])."_Bottle_Labels_Judging_Numbers";
			if ($filter != "default") 	$filename .= "_Category_".$filter;
			if ($psort == "3422") 		$filename .= "_Avery3422";
			else 						$filename .= "_Avery5160";
			$filename .= ".pdf";
<<<<<<< HEAD
			
			if ($psort == "3422") $pdf = new PDF_Label('3422');
			else $pdf = new PDF_Label('5160');
			$pdf->AddPage();
			$pdf->SetFont('Arial','',8);
			
			// Print labels
			do {
=======
			if ($psort == "3422") $pdf = new PDF_Label('3422');
			else $pdf = new PDF_Label('5160');

			$pdf->AddPage();
			$pdf->SetFont('Arial','',8);

			// Print labels
			do {

>>>>>>> origin/master
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
<<<<<<< HEAD
				
=======

				//echo $text;

>>>>>>> origin/master
			} while ($row_log = mysqli_fetch_assoc($log));
			
			//$pdf->Output();
			ob_end_clean();
			$pdf->Output($filename,'D');

		}

		if (($go == "entries") && ($action == "bottle-judging-round") && ($view == "default")) {
<<<<<<< HEAD
			
=======

>>>>>>> origin/master
			$filename = str_replace(" ","_",$_SESSION['contestName'])."_Round_Bottle_Labels_Judging_Numbers";
			//if ($filter != "default") $filename .= "_Category_".$filter;
			if ($psort == "OL32") 		$filename .= "_.50_Inch";
			if ($psort == "OL5275WR") 	$filename .= "_.75_Inch";
			if ($filter == "recent")		$filename .= "_Added_After_Reg_Close";
			$filename .= ".pdf";
<<<<<<< HEAD
			
			$pdf = new PDF_Label($psort);
			$pdf->AddPage();
			if ($psort == "OL32") $pdf->SetFont('Arial','',6);
			else $pdf->SetFont('Arial','',10);
			
=======

			$pdf = new PDF_Label($psort);

			$pdf->AddPage();
			if ($psort == "OL32") $pdf->SetFont('Arial','',6);
			else $pdf->SetFont('Arial','',10);

>>>>>>> origin/master
			// Print labels
			do {
				
				for($i=0; $i<$sort; $i++) {
					
					if ((NHC) || ($_SESSION['prefsEntryForm'] == "N")) $entry_no = $row_log['brewJudgingNumber'];
					else $entry_no = readable_judging_number($row_log['brewCategory'],$row_log['brewJudgingNumber']);
<<<<<<< HEAD
					
=======
>>>>>>> origin/master
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
				
			} while ($row_log = mysqli_fetch_assoc($log));
			
			//$pdf->Output();
			ob_end_clean();
			$pdf->Output($filename,'D');

		}

		if (($go == "entries") && ($action == "bottle-entry-round") && ($view == "default")) {
<<<<<<< HEAD
			
=======

>>>>>>> origin/master
			$filename = str_replace(" ","_",$_SESSION['contestName'])."_Round_Bottle_Labels_Entry_Numbers";
			//if ($filter != "default") $filename .= "_Category_".$filter;
			if ($psort == "OL32") 		$filename .= "_.50_Inch";
			if ($psort == "OL5275WR") 	$filename .= "_.75_Inch";
			if ($filter == "recent")		$filename .= "_Added_After_Reg_Close";
			$filename .= ".pdf";
<<<<<<< HEAD
			
			$pdf = new PDF_Label($psort);
			$pdf->AddPage();
			if ($psort == "OL32") $pdf->SetFont('Arial','',7);
			else $pdf->SetFont('Arial','',10);
			
=======

			$pdf = new PDF_Label($psort);

			$pdf->AddPage();
			if ($psort == "OL32") $pdf->SetFont('Arial','',7);
			else $pdf->SetFont('Arial','',10);

>>>>>>> origin/master
			// Print labels
			do {
				
				for($i=0; $i<$sort; $i++) {
<<<<<<< HEAD
					
					$entry_no = sprintf("%04s",$row_log['id']);
					
=======
					$entry_no = sprintf("%04s",$row_log['id']);

>>>>>>> origin/master
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
				
			} while ($row_log = mysqli_fetch_assoc($log));
			
			//$pdf->Output();
			ob_end_clean();
			$pdf->Output($filename,'D');

		}

		if (($go == "entries") && ($action == "bottle-category-round") && ($view == "default")) {
<<<<<<< HEAD
			
=======

>>>>>>> origin/master
			$filename = str_replace(" ","_",$_SESSION['contestName'])."_Round_Bottle_Labels_Category_Only";
			if ($filter != "default") 	$filename .= "_Category_".$filter;
			if ($psort == "OL32") 		$filename .= "_.50_Inch";
			if ($psort == "OL5275WR") 	$filename .= "_.75_Inch";
			$filename .= ".pdf";
<<<<<<< HEAD
			
			$pdf = new PDF_Label($psort);
			$pdf->AddPage();
			if ($psort == "OL32") $pdf->SetFont('Arial','',7);
			else $pdf->SetFont('Arial','',10);
			
=======

			$pdf = new PDF_Label($psort);

			$pdf->AddPage();
			if ($psort == "OL32") $pdf->SetFont('Arial','',7);
			else $pdf->SetFont('Arial','',10);

>>>>>>> origin/master
			// Print labels
			do {
				
				for($i=0; $i<$sort; $i++) {
					$text = sprintf("\n%s",$row_log['brewCategorySort'].$row_log['brewSubCategory']);
					$text = iconv('UTF-8', 'windows-1252', $text);
					$pdf->Add_Label($text);
				}
				
			} while ($row_log = mysqli_fetch_assoc($log));

			//$pdf->Output();
			ob_end_clean();
			$pdf->Output($filename,'D');
<<<<<<< HEAD
=======

>>>>>>> origin/master
		}

		if (($go == "entries") && ($action == "bottle-judging") && ($view == "special")) {
<<<<<<< HEAD
			
=======

>>>>>>> origin/master
			$filename = str_replace(" ","_",$_SESSION['contestName'])."_Bottle_Labels_Judging_Numbers";
			if ($filter != "default") $filename .= "_Category_".$filter;
			$filename .= "_Req_Info_Mead-Cider";
			if ($psort == "3422") 		$filename .= "_Avery3422";
			else 						$filename .= "_Avery5160";
			$filename .= ".pdf";
			
			if ($psort == "3422") $pdf = new PDF_Label('3422');
			else $pdf = new PDF_Label('5160');
<<<<<<< HEAD
			$pdf->AddPage();
			$pdf->SetFont('Arial','',9);
			
=======

			$pdf->AddPage();
			$pdf->SetFont('Arial','',10);

>>>>>>> origin/master
			// Print labels
			do {

				for($i=0; $i<$sort; $i++) {
<<<<<<< HEAD
					
					$text = "";
					
					if ((NHC) || ($_SESSION['prefsEntryForm'] == "N")) $entry_no = $row_log['brewJudgingNumber'];
					else $entry_no = readable_judging_number($row_log['brewCategory'],$row_log['brewJudgingNumber']);
					
					$style_name = truncate($row_log['brewStyle'],22);
					if (strlen($style_name) == 22) $style_name .= "...";
					
					$special = str_replace("\n"," ",truncate($row_log['brewInfo'],50));
					$special = strtr($special,$html_remove);
					
					$optional = "";
					if (!empty($row_log['brewInfoOptional'])) $optional .= strtr($row_log['brewInfoOptional'],$html_remove);
					
					if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) {
						
						$style = $row_log['brewCategorySort'].$row_log['brewSubCategory'];
						
						if (in_array($style,$special_ingredients)) {
							$text = sprintf("\n%s  %s  #%s\nReq: %s", $style, $style_name, $entry_no, $special);
							if (!empty($optional)) $text .= sprintf("\nOp: %s",$optional);
						}
						
					}
					
					else {
						
						$style = $row_log['brewSubCategory'];
						
						if (in_array($style,$special_ingredients)) { 
							$text = sprintf("\n%s  #%s\nReq: %s", $style_name, $entry_no, $special);
							if (!empty($optional)) $text .= sprintf("\nOp: %s",$optional);
						}
						
=======
					$text = '';
					if ((NHC) || ($_SESSION['prefsEntryForm'] == "N")) $entry_no = $row_log['brewJudgingNumber'];
					else $entry_no = readable_judging_number($row_log['brewCategory'],$row_log['brewJudgingNumber']);

					$style = $row_log['brewCategorySort'].$row_log['brewSubCategory'];
					$style_name = truncate($row_log['brewStyle'],22);

					$special = str_replace("\n"," ",truncate($row_log['brewInfo'],50));
					$special = strtr($special,$html_remove);

					if (in_array($style,$special_ingredients)) {
						$text = sprintf("\n%s  %s  #%s\nReq Info: %s", $row_log['brewCategory'].$row_log['brewSubCategory'], $style_name, $entry_no, $special);
>>>>>>> origin/master
					}

					if (in_array($style,$mead)) {
							$text .= "\n";
							if (!empty($row_log['brewMead1'])) $text .= sprintf("%s",$row_log['brewMead1']);
							if (!empty($row_log['brewMead2'])) $text .= sprintf(" / %s",$row_log['brewMead2']);
							if (!empty($row_log['brewMead3'])) $text .= sprintf(" / %s",$row_log['brewMead3']);
<<<<<<< HEAD
					}
					
					if (!empty($text)) {
						$text = iconv('UTF-8', 'windows-1252', $text);
						$pdf->Add_Label($text);
					}
					
				} // end for $i
				
=======
					}

					if ( ! empty( $text ) ) {
						$text = iconv( 'UTF-8', 'windows-1252', $text );
						$pdf->Add_Label( $text );
					}
				}

>>>>>>> origin/master
			} while ($row_log = mysqli_fetch_assoc($log));
			
			//$pdf->Output();
			ob_end_clean();
			$pdf->Output($filename,'D');

		}

		if (($go == "entries") && ($action == "bottle-judging") && ($view == "all")) {
<<<<<<< HEAD
			
=======

>>>>>>> origin/master
			$filename = str_replace(" ","_",$_SESSION['contestName'])."_Bottle_Labels_Judging_Numbers";
			if ($filter != "default") $filename .= "_Category_".$filter;
			$filename .= "_Req_Info_Mead-Cider";
			if ($psort == "3422") 		$filename .= "_Avery3422";
			else 						$filename .= "_Avery5160";
			$filename .= ".pdf";
			
			if ($psort == "3422") $pdf = new PDF_Label('3422');
			else $pdf = new PDF_Label('5160');
<<<<<<< HEAD
			$pdf->AddPage();
			$pdf->SetFont('Arial','',9);
			
=======

			$pdf->AddPage();
			$pdf->SetFont('Arial','',10);

>>>>>>> origin/master
			// Print labels
			do {

				for($i=0; $i<$sort; $i++) {

					if ((NHC) || ($_SESSION['prefsEntryForm'] == "N")) $entry_no = $row_log['brewJudgingNumber'];
					else $entry_no = readable_judging_number($row_log['brewCategory'],$row_log['brewJudgingNumber']);
					
					$style = $row_log['brewCategorySort'].$row_log['brewSubCategory'];
					
					$style_name = truncate($row_log['brewStyle'],22);
<<<<<<< HEAD
					if (strlen($style_name) == 22) $style_name .= "...";
					
					if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) {
						
						$style = $row_log['brewCategorySort'].$row_log['brewSubCategory'];
						
						$text = sprintf("\n%s  %s  #%s", $style, $style_name, $entry_no);
						
						if (in_array($style,$special_ingredients)) {
							
							$special = str_replace("\n"," ",truncate($row_log['brewInfo'],50));
							$special = strtr($special,$html_remove);
							
							$optional = "";
							if (!empty($row_log['brewInfoOptional'])) $optional .= strtr($row_log['brewInfoOptional'],$html_remove);
							
							$text .= sprintf("\n%s  %s  #%s\nReq: %s", $style, $style_name, $entry_no, $special);
							if (!empty($optional)) $text .= sprintf("\nOp: %s",$optional);
							
						}
						
					}
					
					else {
						
						$style = $row_log['brewSubCategory'];
						
						$text = sprintf("\n%s  #%s", $style_name, $entry_no);
						
						if (in_array($style,$special_ingredients)) { 
						
							$special = str_replace("\n"," ",truncate($row_log['brewInfo'],50));
							$special = strtr($special,$html_remove);
							
							$optional = "";
							if (!empty($row_log['brewInfoOptional'])) $optional .= strtr($row_log['brewInfoOptional'],$html_remove);
						
							$text .= sprintf("\n%s  #%s\nReq: %s", $style_name, $entry_no, $special);
							if (!empty($optional)) $text .= sprintf("\nOp: %s",$optional);
						}
						
=======

					$text = sprintf("\n%s  %s  #%s", $row_log['brewCategory'].$row_log['brewSubCategory'], $style_name, $entry_no);

					if (in_array($style,$special_ingredients)) {
						$special = str_replace("\n"," ",truncate($row_log['brewInfo'],50));
						$special = strtr($special,$html_remove);
						$text .= sprintf("\nReq Info: %s", $special);
>>>>>>> origin/master
					}

					if (in_array($style,$mead)) {
							
							$text .= "\n";
							if (!empty($row_log['brewMead1'])) $text .= sprintf("%s",$row_log['brewMead1']);
							if (!empty($row_log['brewMead2'])) $text .= sprintf(" / %s",$row_log['brewMead2']);
							if (!empty($row_log['brewMead3'])) $text .= sprintf(" / %s",$row_log['brewMead3']);
<<<<<<< HEAD
							
=======
>>>>>>> origin/master
					}

					$text = iconv('UTF-8', 'windows-1252', $text);
					$pdf->Add_Label($text);
<<<<<<< HEAD
=======

>>>>>>> origin/master
				}

			} while ($row_log = mysqli_fetch_assoc($log));

			//$pdf->Output();
			ob_end_clean();
			$pdf->Output($filename,'D');

		}

		if (($go == "participants") && ($action == "judging_nametags")) {
<<<<<<< HEAD
			
			$pdf = new PDF_Label('5395');
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',20);
=======
			$pdf = new PDF_Label('5395');
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',20);

>>>>>>> origin/master
			$filename .= str_replace(" ","_",$_SESSION['contestName'])."_Nametags_Avery5395.pdf";

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
				
			} while ($row_brewer = mysqli_fetch_assoc($brewer));

			ob_end_clean();
			$pdf->Output($filename,'D');
			
		}
<<<<<<< HEAD
		
=======


>>>>>>> origin/master
		if (($go == "participants") && ($action == "judging_labels") && ($id == "default")) {
			
			if ($psort == "3422") $pdf = new PDF_Label('3422');
			else $pdf = new PDF_Label('5160');
			$pdf->AddPage();
			$pdf->SetFont('Arial','',9);
<<<<<<< HEAD
=======

>>>>>>> origin/master
			$filename .= str_replace(" ","_",$_SESSION['contestName'])."_All_Judge_Scoresheet_Labels";
			if ($psort == "3422") 		$filename .= "_Avery3422";
			else 						$filename .= "_Avery5160";
			$filename .= ".pdf";

			do {

				$bjcp_rank = explode(",",$row_brewer['brewerJudgeRank']);
				$rank = bjcp_rank($bjcp_rank[0],2);
<<<<<<< HEAD
				
=======

>>>>>>> origin/master
				/*
				$bjcp_rank1 = $bjcp_rank[0].",";
				$other_ranks = str_replace($bjcp_rank1,"",$row_brewer['brewerJudgeRank']);
				$other_ranks = str_replace(",",", ",$other_ranks);
				if (!empty($other_ranks)) $rank .= ", ".$other_ranks;
				if (!empty($bjcp_rank[1])) $rank .= ", ".$bjcp_rank[1];
				if (!empty($bjcp_rank[2])) $rank .= ", ".$bjcp_rank[2];
				*/

				$j = preg_replace('/[a-zA-Z]/','',$row_brewer['brewerJudgeID']);
<<<<<<< HEAD
				//$j = ltrim($row_brewer['brewerJudgeID'],'/[a-z][A-Z]/');
				
=======

>>>>>>> origin/master
				$first_name = strtr($row_brewer['brewerFirstName'],$html_remove);
				$first_name = ucfirst(strtolower($first_name));
				
				$last_name = strtr($row_brewer['brewerLastName'],$html_remove);
				$last_name = ucfirst(strtolower($last_name));
<<<<<<< HEAD
				
=======

				//$j = ltrim($row_brewer['brewerJudgeID'],'/[a-z][A-Z]/');
>>>>>>> origin/master
				if ($j > 0) $judge_id = "- ".$row_brewer['brewerJudgeID'];
				else $judge_id = "";
				
				for($i=0; $i<$number_of_labels; $i++) {

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

			} while ($row_brewer = mysqli_fetch_assoc($brewer));

			ob_end_clean();
			$pdf->Output($filename,'D');
			
		}

		if (($go == "participants") && ($action == "address_labels")) {
			
			if ($psort == "3422") $pdf = new PDF_Label('3422');
			else $pdf = new PDF_Label('5160');
<<<<<<< HEAD
			
			$pdf->AddPage();
			$pdf->SetFont('Arial','',8);
			
			if ($filter == "with_entries") {
				$filename .= str_replace(" ","_",$_SESSION['contestName'])."_Participants_With_Entries_Address_Labels";
				do { $with_entries_array[] = $row_with_entries['brewBrewerID']; } while ($row_with_entries = mysqli_fetch_assoc($with_entries));
=======

			$pdf->AddPage();
			$pdf->SetFont('Arial','',8);

			if ($filter == "with_entries") {
				$filename .= str_replace(" ","_",$_SESSION['contestName'])."_Participants_With_Entries_Address_Labels";

				do { $with_entries_array[] = $row_with_entries['brewBrewerID']; } while ($row_with_entries = mysqli_fetch_assoc($with_entries));

>>>>>>> origin/master
			}

			else $filename .= str_replace(" ","_",$_SESSION['contestName'])."_All_Participant_Address_Labels";

			if ($psort == "3422") 		$filename .= "_Avery3422";
			else 						$filename .= "_Avery5160";
<<<<<<< HEAD
			
			$filename .= ".pdf";
			
=======
			$filename .= ".pdf";

>>>>>>> origin/master
			do {

				if (strlen($row_brewer['brewerState']) <= 3) $brewerState = strtoupper($row_brewer['brewerState']);
				else $brewerState = ucwords(strtolower($row_brewer['brewerState']));
<<<<<<< HEAD
				
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
							
=======

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
>>>>>>> origin/master
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
					
			} while ($row_brewer = mysqli_fetch_assoc($brewer));
<<<<<<< HEAD
			
=======

>>>>>>> origin/master
			//$pdf->Output();
			ob_end_clean();
			$pdf->Output($filename,'D');
			
		}
<<<<<<< HEAD
		
=======



>>>>>>> origin/master
		if (($go == "judging_scores") && ($action == "awards") && ($filter == "default")) {
			
			if ($psort == "3422") $pdf = new PDF_Label('3422');
			else $pdf = new PDF_Label('5160');
			$pdf->AddPage();
			$pdf->SetFont('Arial','',9);

			$filename .= str_replace(" ","_",$_SESSION['contestName'])."_Award_Labels";
<<<<<<< HEAD
=======

>>>>>>> origin/master
			if ($psort == "3422") 		$filename .= "_Avery3422";
			else 						$filename .= "_Avery5160";
			$filename .= ".pdf";

			include(DB.'output_labels_awards.db.php');
<<<<<<< HEAD
=======

>>>>>>> origin/master
			ob_end_clean();
			$pdf->Output($filename,'D');
			
		}

		if (($go == "judging_scores") && ($action == "awards") && ($filter == "round")) {
			if ($psort == "EU30095") $pdf = new PDF_Label('EU30095');
			else $pdf = new PDF_Label('OL5375');
			$pdf->AddPage();
			$pdf->SetFont('Arial','',9);

			$filename .= str_replace(" ","_",$_SESSION['contestName'])."_Medal_Labels";
<<<<<<< HEAD
=======

>>>>>>> origin/master
			if ($psort == "EU30095") $filename .= "_EU30095";
			else $filename .= "_OL5375";
			$filename .= ".pdf";

			include(DB.'output_labels_awards.db.php');
<<<<<<< HEAD
			ob_end_clean();
			$pdf->Output($filename,'D');
		}
		
	}	// end if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))
	
	// --------------------------------------------------------
	// The following is the only label output that non-admins
	// can access.
	// --------------------------------------------------------
=======

			ob_end_clean();
			$pdf->Output($filename,'D');
		}


	}	// end if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1))

// --------------------------------------------------------
// The following is the only label output that non-admins
// can access.
// --------------------------------------------------------
>>>>>>> origin/master

	if (($go == "participants") && ($action == "judging_labels") && ($id != "default")) {
		
		if ($psort == "3422") $pdf = new PDF_Label('3422');
		else $pdf = new PDF_Label('5160');
		$pdf->AddPage();
		$pdf->SetFont('Arial','',8);
		
		$first_name = strtr($row_brewer['brewerFirstName'],$html_remove);
		$first_name = ucfirst(strtolower($first_name));
		$last_name = strtr($row_brewer['brewerLastName'],$html_remove);
		$last_name = ucfirst(strtolower($last_name));

		//echo $query_brewer;
		
		$filename .= $first_name."_".$last_name."_Judge_Scoresheet_Labels";
		if ($psort == "3422") 		$filename .= "_Avery3422";
		else 						$filename .= "_Avery5160";
		$filename .= ".pdf";
<<<<<<< HEAD
		
=======

		//$rank = str_replace(",",", ",$row_brewer['brewerJudgeRank']);
>>>>>>> origin/master
		$bjcp_rank = explode(",",$row_brewer['brewerJudgeRank']);
		$rank = bjcp_rank($bjcp_rank[0],2);
		//$rank = str_replace(",",", ",$row_brewer['brewerJudgeRank']);
		
		if (!empty($bjcp_rank[1])) $rank .= ", ".$bjcp_rank[1];
		if (!empty($bjcp_rank[2])) $rank .= ", ".$bjcp_rank[2];
		//$rank2 = truncate($rank2,50);
<<<<<<< HEAD
		
=======

>>>>>>> origin/master
		$j = preg_replace('/[a-zA-Z]/','',$row_brewer['brewerJudgeID']);
		//$j = ltrim($row_brewer['brewerJudgeID'],'/[a-z][A-Z]/');
		
		if ($j > 0) $judge_id = " (".$row_brewer['brewerJudgeID'].")";
		else $judge_id = "";
		
		for($i=0; $i<$number_of_labels; $i++) {

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
		
		ob_end_clean();
		$pdf->Output($filename,'D');
		
	}
	
}

else echo "<p>Not available.</p>";
?>