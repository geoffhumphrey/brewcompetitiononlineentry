<?php
setlocale(LC_ALL, "en_US.utf8");

$filename = "";
$number_of_labels = "";
$ba = FALSE;
$aabc = FALSE;

if ($_SESSION['prefsStyleSet'] == "BA") $ba = TRUE;
if ($_SESSION['prefsStyleSet'] == "AABC") $aabc = TRUE;

/*
 * -------------------------------------------------------------------
 * Define a character limit for address labels. 
 * -- 34 characters per line appears to be the limit for Avery 5160, 
 *    the smaller of the two label sizes available.
 * -- 6 lines are available per label for a total of 204 possible 
 *    characters at 8 pt Courier.
 * -------------------------------------------------------------------
 */

$character_limit = 32;
$total_possible_characters = (6 * $character_limit); // 6 lines in 5160/3422

if (isset($_SESSION['loginUsername'])) {

	ob_start();

	if ($psort == "3422") $number_of_labels = 24;
	if ($psort == "5160") $number_of_labels = 30;

	// Get Special Ingredients
	$special_ingredients = array();
	$mead = array();

	do {
			
		// Special ingredients required
		if ($row_styles['brewStyleReqSpec'] == 1) {
			if ($ba) $special_ingredients[] = $row_styles['brewStyleNum'];
			else $special_ingredients[] = $row_styles['brewStyleGroup'].$row_styles['brewStyleNum'];
		}

		// Mead/cider info required
		if (($row_styles['brewStyleStrength'] == 1) || ($row_styles['brewStyleCarb'] == 1) || ($row_styles['brewStyleSweet'] == 1)) {
			if ($ba) $mead[] = $row_styles['brewStyleNum'];
			else $mead[] = $row_styles['brewStyleGroup'].$row_styles['brewStyleNum'];
		}

	} while ($row_styles = mysqli_fetch_assoc($styles));

	if ($_SESSION['userLevel'] <= 1) {

		/**
		 * -------------------------------------------------------------------
		 * Reworked for 2.1.13
		 * Updated for 2.1.15 to remove special characters
		 * Updated for 2.1.18:
		 * - Convert UTF-8 characters to windows-1252 standard (FPDF limitation)
		 * - Limit to 32 characters per line (Courier 8 pt)
		 * - Made formatting of both types consistent
		 * -------------------------------------------------------------------
		 */

		// -----------------------------------------------
		// Judging box labels
		// -----------------------------------------------
		
		if (($go == "judging_tables")) {
			
			include(DB.'admin_common.db.php');

			if ($psort == "3422") $pdf = new PDF_Label('3422');
			else $pdf = new PDF_Label('5160');

			if ($filter == "judges") {
				
				$filename = str_replace(" ", "_", $_SESSION['contestName']) . "_Virtual_Judge_Labels";
				if ($psort == "3422") $filename .= "_Avery3422";
				else $filename .= "_Avery5160";
				$filename .= ".pdf";
				$filename = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$filename);

				$pdf->AddPage();
				$pdf->Next_Label();
				$pdf->SetFont('Arial', '', 12);

				// Get a list of virtual/distributed locations
				$virtual_locations = virtual_locations();

				if ($row_brewer) {
				
					do {
						
						$judge_info = judge_info($row_brewer['uid']);
						$judge_info = explode("^", $judge_info);
						$locations = explode(",", $judge_info[8]);
						
						// Is this judge virtual
						$isVirtual = false;
						foreach ($virtual_locations as $v_loc) {
							
							if (in_array($v_loc['check'], $locations)) {
								$isVirtual = true;
								break;
							}

						}

						reset($virtual_locations);
						
						if ($isVirtual) {

							for ($i = 1; $i <= $sort; $i++) {

								$brewer_info = brewer_info($row_brewer['uid']);
								$brewer_info = explode("^", $brewer_info);

								// Add name to the label
								$pdf->SetFont('Arial', 'B', 14);
								$judge_name = $judge_info[0] . ' ' . $judge_info[1];
								$judge_name = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$judge_name);
								$pdf->Cell(66, 7, $judge_name, 0, 2, 'C');
								
								// Add location to the label
								$pdf->SetFont('Arial', '', 10);
								$judge_loc = $brewer_info[11] . ', ' . $brewer_info[12] ;
								$judge_loc = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$judge_loc);
								$pdf->Cell(66, 6, $judge_loc, 0, 2, 'C');

								// Add table flights to the label
								$table_flights = array();
								
								foreach ($virtual_locations as $v_loc) {
									
									if (in_array($v_loc['check'], $locations)) {
										// Find which table this judge is assigned to for that location.
										$assign = judge_assignment($brewer_info[7], $v_loc['id'] );
										//$assign = explode("^", $flight);
										$table_flights[] = $assign['tableNumber'];
									}

								}

								// Display the assigned table number(s)
								if (isset($table_flights[0])) {

									if (sizeof($table_flights) > 1) $t_sring = "Tables";
									else $t_string = "Table";
									
									$pdf->SetFont('Arial', 'B', 12);
									$judge_flight = $t_string.": ". join(', ', $table_flights);
									$judge_flight = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$judge_flight);
									$pdf->Cell(66, 6, $judge_flight, 0, 2, 'C');
								
								} 

								else {

									$pdf->SetFont('Arial', 'B', 12);
									$judge_flight = "Table: ______";
									$pdf->Cell(66, 6, $judge_flight, 0, 2, 'C');

								}

								$pdf->Next_Label();

							}
							
							reset($virtual_locations);
						
						}

					} while ($row_brewer = mysqli_fetch_assoc($brewer));

				}

			} // end if ($filter == "judges")

			else {
				
				$filename = str_replace(" ", "_", $_SESSION['contestName']) . "_Box_Labels";
				if ($psort == "3422") $filename .= "_Avery3422";
				else $filename .= "_Avery5160";
				$filename .= ".pdf";
				$filename = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$filename);

				$pdf->AddPage();
				$pdf->Next_Label();
				$pdf->SetFont('Arial', '', 12);

				do {
					
					$style_arr = array(get_table_info("0", "list", $row_tables['id'], $dbTable, "default"));
					$styles = str_replace('&nbsp;', ' ', display_array_content($style_arr, 0));
					$styles = rtrim($styles,", ");

					$tableName = htmlspecialchars_decode($row_tables['tableName']);
					$tableName = truncate($tableName, 30);
					$tableName = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$tableName);							

					$loc_arr = explode("^", get_table_info($row_tables['tableLocation'], "location", $row_tables['id'], $dbTable, "default"));
					$location = $loc_arr[2];
					$location = htmlspecialchars_decode($location);
					$location = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$location);
					$location = truncate($location, 30);

					for ($i = 1; $i <= $sort; $i++) {
						
						$pdf->SetFont('Arial', '', 36);
						
						if ($loc_arr[4] == 1) {
							$pdf->SetFillColor(225, 225, 225);
							$fill = true;
						}

						else $fill = false;
						
						$pdf->Cell(18, 18, $row_tables['tableNumber'], 0, 0, "C", $fill);
						
						$pdf->SetFont('Arial', 'B', 10);		
						$pdf->Cell(48, 5, $tableName, 0, 2, 'L');

						$pdf->SetFont('Arial', '', 9);						
						$pdf->Cell(48, 5, $location, 0, 2);
						$pdf->MultiCell(48, 5, $styles, 0, 'L');
						$pdf->Next_Label();
					
					}

				} while ($row_tables = mysqli_fetch_assoc($tables_edit));
			
			} // end else

			if (ob_get_length()) ob_clean();
			//$pdf->Output();
			$pdf->Output($filename,'D');

		} // end if (($go == "judging_tables"))

		if (($go == "entries") && (($action == "bottle-judging") || ($action == "bottle-entry"))) {

			if ($view == "default") {

				// Begin PDF generation 
				if ($psort == "3422") $pdf = new PDF_Label('3422');
				else $pdf = new PDF_Label('5160');
				
				$pdf->AddPage();
				
				$filename = str_replace(" ","_",$_SESSION['contestName'])."_Bottle_Labels_Entry_Numbers";
				if ($filter != "default") 	$filename .= "_Category_".$filter;
				if ($psort == "3422") 		$filename .= "_Avery3422";
				else 						$filename .= "_Avery5160";
				$filename .= ".pdf";
				$filename = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$filename);

				$pdf->SetFont('Courier','',6);
				
				// Print labels
				do {
					
					if ($action == "bottle-entry") $entry_no = sprintf("%06s",$row_log['id']);
					else $entry_no = sprintf("%06s",strtoupper($row_log['brewJudgingNumber']));

					$category = sprintf("%02s",$row_log['brewCategorySort']);
					$subcategory = $row_log['brewSubCategory'];
					if ($aabc) $cat_output = ltrim($category,"0").".".ltrim($subcategory,"0");
					else $cat_output = $category.$subcategory;
					
					$text = sprintf("\n%s (%s)  %s (%s)  %s (%s)\n\n\n\n%s (%s)  %s (%s)  %s (%s)",
					$entry_no, $cat_output,
					$entry_no, $cat_output,
					$entry_no, $cat_output,
					$entry_no, $cat_output,
					$entry_no, $cat_output,
					$entry_no, $cat_output
					);

					$text = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$text);              
					$pdf->Add_Label($text);

				} while ($row_log = mysqli_fetch_assoc($log));

				if (ob_get_length()) ob_clean();
				//$pdf->Output();
				$pdf->Output($filename,'D');

			}

			// -----------------------------------------------
			// Custom Quicksort PDF
			// -----------------------------------------------
			elseif ($view == "quicksort") {
				
				$filename = str_replace(" ", "_", $_SESSION['contestName']) . "_QuickSort_Labels_Judging_Numbers";
				$filename .= ".pdf";
				$filename = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$filename);  
				
				$pdf = new PDF_Label('5167');

				$pdf->AddPage();
				$pdf->SetFont('Arial', '', 9);
				$lastStyle = "";

				do {
					
					if ($lastStyle != "") {
						
						if ($lastStyle == $row_log['brewCategory']) {
							
							$pdf->SetLineWidth(0.1);
							$pdf->SetDash(1, 1);
							if ($tb == "default") $pdf->Line(0, $pdf->GetY() + 5, 200, $pdf->GetY() + 5);
							if ($tb == "short") $pdf->Line(0, $pdf->GetY() + 2.5, 200, $pdf->GetY() + 2.5);
								
						} else {
							
							$pdf->SetLineWidth(1);
							if ($tb == "default") $pdf->Line(0, $pdf->GetY() + 5, 200, $pdf->GetY() + 5);
							if ($tb == "short") $pdf->Line(0, $pdf->GetY() + 2.5, 200, $pdf->GetY() + 2.5);

						}
					
					}
					
					$lastStyle = $row_log['brewCategory'];
					
					$judging_number = readable_judging_number($row_log['brewCategory'], $row_log['brewJudgingNumber']);
					$entry_number = sprintf("%06s", $row_log['id']);
					$style = $row_log['brewCategory'] . $row_log['brewSubCategory'];
					$style_name = truncate($row_log['brewStyle'], 22);
					$style_name = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$style_name);  
					$brewer_name = truncate($row_log['brewBrewerFirstName']." ".$row_log['brewBrewerLastName'],30);
					$brewer_name = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$brewer_name);  
					
					if ($tb == "default") $bottles = ['#1', '#2', '#3'];
					if ($tb == "short") $bottles = ['#1', '#2', 'BOS'];

					$pdf->SetFont('Arial', '', 9);
					foreach ($bottles as $b) {
						$text = sprintf("\n              %s  %s\n                     %s", $style, $judging_number, $b);
						$text = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$text);
						$pdf->Add_Label($text);
					}
					
					reset($bottles);

					if ($tb == "default") {

						$bottles = ['#4', '#5', 'BOS'];

						// Print Entrant info
						$pdf->SetFont('Arial', '', 9);
						$text = sprintf("\n  %s %s\n  %s", $style, $style_name, $brewer_name);
						$text = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$text);
						$pdf->Add_Label($text);

						$pdf->SetFont('Arial', '', 9);
						foreach ($bottles as $b) {
							$text = sprintf("\n              %s  %s\n                     %s", $style, $judging_number, $b);
							$text = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$text);
							$pdf->Add_Label($text);
						}

						reset($bottles);

						$pdf->SetFont('Arial', '', 13);
						if ($entry_number == $judging_number) $text = sprintf("\n  %s", $entry_number);
						else $text = sprintf("\n  %s | %s", $entry_number, $judging_number);
						$text = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$text);
						$pdf->Add_Label($text);

					}

					if ($tb == "short") {

						$pdf->SetFont('Arial', '', 9);
						if ($entry_number == $judging_number) $text = sprintf("\n  %s - %s \n  %s", $style, $entry_number, $brewer_name);
						else $text = sprintf("\n  %s - %s | %s\n  %s", $style, $entry_number, $judging_number, $brewer_name);
						$text = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$text);
						$pdf->Add_Label($text);

					}

				} while ($row_log = mysqli_fetch_assoc($log));
				
				if (ob_get_length()) ob_clean();
				//$pdf->Output();
				$pdf->Output($filename,'D');
			
			}

			else {

				$labels_by_table = FALSE;

				// Begin PDF generation 
				if ($psort == "3422") $pdf = new PDF_Label('3422');
				else $pdf = new PDF_Label('5160');
				
				$pdf->AddPage();

				$special_strength = array(
					"Strength" => "",
					"strength" => "",
					"Sweetness" => "",
					"sweetness" => "",
					"Carbonation" => "",
					"carbonation" => "",
					"Session " => "",
					"session " => "",
					"Standard " => "",
					"standard " => "",
					"Double " => "",
					"double " => "",
					"Table " => "",
					"table " => "",
					"Super " => "",
					"super " => "",
					"Low/None" => "",
					"Low" => "",
					"low" => "",
					"High" => "",
					"high" => "",
					"Medium" => "",
					"medium" => ""
					);
				
				$pdf->SetFont('Courier','',8);

				// If getting labels for a defined table
				if ($location != "default") {
					
					$entries_at_table = array();
					$table_info = explode("^",get_table_info(1,"basic",$location,"default","default"));

					// First, get all of the entry ids for the table in the judging_flights db
					$query_table_entries = sprintf("SELECT * FROM %s WHERE flightTable='%s'", $prefix."judging_flights",$location);
					$row_table_entries = mysqli_query($connection,$query_table_entries) or die (mysqli_error($connection));
					$table_entries = mysqli_fetch_assoc($row_table_entries);

					do {

						$entries_at_table[] = $table_entries['flightEntryID'];

					} while($table_entries = mysqli_fetch_assoc($row_table_entries));

					if (!empty($entries_at_table)) $labels_by_table = TRUE;
				
				}				
				
				// Assemble the file name
				$filename = str_replace(" ","_",$_SESSION['contestName'])."_Bottle_Labels_";
				if ($action == "bottle-entry") $filename .= "Entry_Numbers";
				else $filename .= "Judging_Numbers";
				if ($filter != "default") $filename .= "_Category_".$filter;
				$filename .= "_Req_Info";
				if ($psort == "3422") $filename .= "_Avery3422";
				else $filename .= "_Avery5160";
				if ($location != "default") $filename .= "_Table_".$table_info[0];
				$filename .= ".pdf";
				$filename = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$filename);
				
				// Print labels
				do {

					$character_length = 0;

					for($i=0; $i<$sort; $i++) {
						
						$text = "";
						$entry_info = "";
						$special = "";
						$special_only = "";
						$optional = "";
						$allergens = "";
						$entry_str_sweet_carb = "";
						$mead_cider = "";
						$beer_strength = "";
						$beer_sweeteness = "";
						$beer_carbonation = "";
						$allergens = "";
						
						if ($action == "bottle-entry") $entry_no = sprintf("%06s",$row_log['id']);
						else $entry_no = sprintf("%06s",strtoupper($row_log['brewJudgingNumber']));

						$subcategory = $row_log['brewSubCategory'];
						
						$style = strtoupper($row_log['brewCategorySort']).$subcategory;
						if ($aabc) $style_display = strtoupper(ltrim($row_log['brewCategorySort'],"0")).".".ltrim($subcategory,"0");
						else $style_display = $style;
						$style_name = $row_log['brewStyle'];
						
						if (strpos($style_name,"Pre-Prohibition") !== false) $style_name = str_replace("Pre-Prohibition", "Pre-Prohib.", $style_name);
						if (strpos($style_name,"Fermentation") !== false) $style_name = str_replace("Fermentation", "Ferm.", $style_name);
						if (strpos($style_name,"Premium") !== false) $style_name = str_replace("Premium", "Prem.", $style_name);
						if (strpos($style_name,"Australian") !== false) $style_name = str_replace("Australian", "Aust.", $style_name);
						if (strpos($style_name,"Spice, Herb, or Vegetable") !== false) $style_name = str_replace("Spice, Herb, or Vegetable", "Spice/Herb/Veg", $style_name);
						if (strpos($style_name,"Alternative") !== false) $style_name = str_replace("Alternative", "Alt.", $style_name);
						if (strpos($style_name,"Classic Style") !== false) $style_name = str_replace("Classic Style", "Cl. Style", $style_name);
						if (strpos($style_name,"Specialty") !== false) $style_name = str_replace("Specialty", "Spec.", $style_name);
						if (strpos($style_name,"Speciality") !== false) $style_name = str_replace("Speciality", "Spec.", $style_name);
						if (strpos($style_name,"with") !== false) $style_name = str_replace("with", "w/", $style_name);

						$style_name = truncate($style_name,21);
						$style_name = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$style_name);
						
						if ($ba) $entry_info = sprintf("%s (%s)", $entry_no, $style_name);
						else $entry_info = sprintf("%s (%s: %s)", $entry_no, $style_display, $style_name);
						$character_length += strlen($entry_info);

						if (in_array($style,$special_ingredients)) {
							
							$special = strip_tags($row_log['brewInfo']);
							$special = html_entity_decode($special);
							$special = str_replace("^", "", $special);
							$special = str_replace("\n", "", $special);
							$special = trim($special);
							$special = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$special);
							
							if (!empty($special)) {
								$character_length += strlen($special);
								$special = sprintf("\n%s", $special);
							}
							
							if (!in_array($style,$mead)) {

								$sp_str_sweet_carb = mb_strtolower($row_log['brewInfo']);

								if (strpos($sp_str_sweet_carb,"session strength") !== false) $beer_strength .= "*Session* ";
								if (strpos($sp_str_sweet_carb,"standard strength") !== false) $beer_strength .= "*Standard* ";
								if (strpos($sp_str_sweet_carb,"double strength") !== false) $beer_strength .= "*Double* ";
								if (strpos($sp_str_sweet_carb,"table strength") !== false) $beer_strength .= "*Table* ";
								if (strpos($sp_str_sweet_carb,"super strength") !== false) $beer_strength .= "*Super* ";
								if (strpos($sp_str_sweet_carb,"low/none sweetness") !== false) $beer_sweeteness .= "*Low/No Sweet* ";
								if (strpos($sp_str_sweet_carb,"medium sweetness") !== false) $beer_sweeteness .= "*Med Sweet* ";
								if (strpos($sp_str_sweet_carb,"high sweetness") !== false) $beer_sweeteness .= "*High Sweet* ";
								if (strpos($sp_str_sweet_carb,"low carbonation") !== false) $beer_carbonation .= "*Low Carb* ";
								if (strpos($sp_str_sweet_carb,"medium carbonation") !== false) $beer_carbonation .= "*Med Carb* ";
								if (strpos($sp_str_sweet_carb,"high carbonation") !== false) $beer_carbonation .= "*High Carb* ";
								
								if ((!empty($beer_strength)) || (!empty($beer_sweeteness)) || (!empty($beer_carbonation))) {
									$character_limit_adjust_special = $character_limit_adjust_special - 12;
									if (!in_array($style,$mead)) $special = strtr($special,$special_strength);
								}

								$entry_str_sweet_carb .= $beer_carbonation.$beer_sweeteness.$beer_strength;

							}
						
						}

						if ((!empty($row_log['brewPossAllergens'])) && ($character_length < $total_possible_characters)) {
							
							$character_limit_adjust = $character_limit * 2; // Allow for two lines
							$allergens = strip_tags($row_log['brewPossAllergens']);
							$allergens = sprintf("%s: %s",$label_allergens,$allergens);
							$allergens = str_replace("\n"," ",$allergens);
							$allergens = html_entity_decode($allergens);
							$allergens = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$allergens);
							
							if (!empty($allergens)) {
								$character_length += strlen($allergens);
								$allergens = sprintf("\n%s",$allergens);
							}
							
						}
						
						if (in_array($style,$mead)) {

							if (!empty($row_log['brewMead1'])) $entry_str_sweet_carb .= sprintf("*%s* ",$row_log['brewMead1']);
							if (!empty($row_log['brewMead2'])) $entry_str_sweet_carb .= sprintf("*%s* ",$row_log['brewMead2']);
							if (!empty($row_log['brewMead3'])) $entry_str_sweet_carb .= sprintf("*%s* ",$row_log['brewMead3']);

							$entry_str_sweet_carb = str_replace("Medium Sweet", "Med Sweet", $entry_str_sweet_carb);
							$entry_str_sweet_carb = str_replace("Medium Dry", "Med Dry", $entry_str_sweet_carb);
							$entry_str_sweet_carb = str_replace("Sparkling", "Spark", $entry_str_sweet_carb);
							$entry_str_sweet_carb = str_replace("Hydromel", "Hydro", $entry_str_sweet_carb);
							$entry_str_sweet_carb = str_replace("Petillant", "Petill", $entry_str_sweet_carb);

						}
						
						if (!empty($entry_str_sweet_carb)) {

							$character_length += strlen($entry_str_sweet_carb);
							$entry_str_sweet_carb = sprintf("\n%s",$entry_str_sweet_carb);

						}

						if (!empty($row_log['brewInfoOptional'])) {

							// Only show Optional if total possible characters minus one line has not been reached				
							if (($character_length < ($total_possible_characters - $character_limit))) {
								$optional = html_entity_decode($row_log['brewInfoOptional']);
								$optional = str_replace("\n"," ",truncate($optional,$character_limit,""));
								$optional = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$optional);
								$character_length += strlen($optional);
								$optional = sprintf("\n%s",$optional);
							}
							
						}

						// Limit Special and Optional lines if allergens and/or mead/cider info are present
						if ((!empty($allergens)) && (empty($entry_str_sweet_carb))) {
							if (!empty($special)) $special = truncate($special,$character_limit*4,"");
							if (!empty($optional)) $optional = truncate($optional,$character_limit,"");
						}

						elseif ((empty($allergens)) && (!empty($entry_str_sweet_carb))) { 
							if (!empty($special)) $special = truncate($special,$character_limit*4,"");
							if (!empty($optional)) $optional = truncate($optional,$character_limit,"");
						}

						elseif ((!empty($allergens)) && (!empty($entry_str_sweet_carb))) {
							if (!empty($special)) $special = truncate($special,$character_limit*3,"");
							if (!empty($optional)) $optional = truncate($optional,$character_limit,"");
						}

						if ($view == "special") {

							if (($location != "default") && ((in_array($style,$special_ingredients)) || (in_array($style,$mead))) && (in_array($row_log['id'],$entries_at_table))) $text = $entry_info.$special.$entry_str_sweet_carb.$allergens.$optional;

							elseif (($location == "default") && ((in_array($style,$special_ingredients)) || (in_array($style,$mead)))) $text = $entry_info.$special.$entry_str_sweet_carb.$allergens.$optional;
							
							else $text = "";
							
						}
						
						else $text = $entry_info.$special.$entry_str_sweet_carb.$allergens.$optional;
						$text = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$text); 
						
						if (!empty($text)) $pdf->Add_Label($text);

					}

				} while ($row_log = mysqli_fetch_assoc($log));

				if (ob_get_length()) ob_clean();
				//$pdf->Output();
				$pdf->Output($filename,'D');

			} // end else

		}

		// -----------------------------------------------
		// End Rework
		// -----------------------------------------------

		if (($go == "entries") && ($action == "bottle-judging-round") && ($view == "default")) {

			$filename = str_replace(" ","_",$_SESSION['contestName'])."_Round_Bottle_Labels_Judging_Numbers";
			//if ($filter != "default") $filename .= "_Category_".$filter;
			if ($psort == "OL32") 		$filename .= "_.50_Inch";
			if ($psort == "OL5275WR") 	$filename .= "_.75_Inch";
			if ($filter == "recent")		$filename .= "_Added_After_Reg_Close";
			$filename .= ".pdf";

			$pdf = new PDF_Label($psort);
			$pdf->AddPage();
			if ($psort == "OL32") $pdf->SetFont('Courier','',6);
			else $pdf->SetFont('Courier','',9);

			// Print labels
			do {

				for($i=0; $i<$sort; $i++) {

					$entry_no = sprintf("%06s",$row_log['brewJudgingNumber']);

					if (($entry_no != "") && ($filter == "default")) {
						if ($aabc) $text = sprintf("\n%s\n(%s)",$entry_no, ltrim($row_log['brewCategory'],"0").".".ltrim($row_log['brewSubCategory'],"0"));
						else $text = sprintf("\n%s\n(%s)",$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory']);
						$text = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$text);
						$pdf->Add_Label($text);
					}

					if (($entry_no != "") && ($filter == "recent") && (strtotime($row_log['brewUpdated']) > $row_contest_dates['contestRegistrationDeadline'])) {
						if ($aabc) $text = sprintf("\n%s\n(%s)",$entry_no, ltrim($row_log['brewCategory'],"0").".".ltrim($row_log['brewSubCategory'],"0"));
						else $text = sprintf("\n%s\n(%s)",$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory']);
						$text = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$text);
						$pdf->Add_Label($text);
					}

				}

			} while ($row_log = mysqli_fetch_assoc($log));

			//$pdf->Output();
			if (ob_get_length()) ob_clean();
			$pdf->Output($filename,'D');

		}

		if (($go == "entries") && ($action == "bottle-entry-round") && ($view == "default")) {

			$filename = str_replace(" ","_",$_SESSION['contestName'])."_Round_Bottle_Labels_Entry_Numbers";
			//if ($filter != "default") $filename .= "_Category_".$filter;
			if ($psort == "OL32") 		$filename .= "_.50_Inch";
			if ($psort == "OL5275WR") 	$filename .= "_.75_Inch";
			if ($filter == "recent")	$filename .= "_Added_After_Reg_Close";
			$filename .= ".pdf";

			$pdf = new PDF_Label($psort);
			$pdf->AddPage();
			if ($psort == "OL32") $pdf->SetFont('Courier','',6);
			else $pdf->SetFont('Courier','',9);

			// Print labels
			do {

				for($i=0; $i<$sort; $i++) {

					$entry_no = sprintf("%06s",$row_log['id']);

					if ((!empty($entry_no)) && ($filter == "default")) {
						if ($aabc) $text = sprintf("\n%s\n(%s)",$entry_no, ltrim($row_log['brewCategory'],"0").".".ltrim($row_log['brewSubCategory'],"0"));
						else $text = sprintf("\n%s\n(%s)",$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory']);
						$text = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $text))); 
						$pdf->Add_Label($text);
					}

					if ((!empty($entry_no)) && ($filter == "recent") && (strtotime($row_log['brewUpdated']) > $row_contest_dates['contestRegistrationDeadline'])) {
						if ($aabc) $text = sprintf("\n%s\n(%s)",$entry_no, ltrim($row_log['brewCategory'],"0").".".ltrim($row_log['brewSubCategory'],"0"));
						else $text = sprintf("\n%s\n(%s)",$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory']);
						$text = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $text))); 
						$pdf->Add_Label($text);
					}

				}

			} while ($row_log = mysqli_fetch_assoc($log));

			//$pdf->Output();
			if (ob_get_length()) ob_clean();
			$pdf->Output($filename,'D');

		}

		if (($go == "entries") && ($action == "bottle-category-round") && ($view == "default")) {

			$filename = str_replace(" ","_",$_SESSION['contestName'])."_Round_Bottle_Labels_Category_Only";
			if ($filter != "default") 	$filename .= "_Category_".$filter;
			if ($psort == "OL32") 		$filename .= "_.50_Inch";
			if ($psort == "OL5275WR") 	$filename .= "_.75_Inch";
			$filename .= ".pdf";

			$pdf = new PDF_Label($psort);
			$pdf->AddPage();
			if ($psort == "OL32") $pdf->SetFont('Arial','',7);
			else $pdf->SetFont('Arial','',10);

			// Print labels
			do {

				for($i=0; $i<$sort; $i++) {
					if ($aabc) $text = sprintf("\n%s",ltrim($row_log['brewCategorySort'],"0").".".ltrim($row_log['brewSubCategory'],"0"));
					else $text = sprintf("\n%s",$row_log['brewCategorySort'].$row_log['brewSubCategory']);
					$text = (iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", transliterator_transliterate('Any-Latin; Latin-ASCII', $text))); 
					$pdf->Add_Label($text);
				}

			} while ($row_log = mysqli_fetch_assoc($log));

			//$pdf->Output();
			if (ob_get_length()) ob_clean();
			$pdf->Output($filename,'D');
		}

		if (($go == "participants") && ($action == "judging_nametags")) {

			$pdf = new PDF_Label('5395');
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',18);
			$filename .= str_replace(" ","_",$_SESSION['contestName'])."_Nametags_Avery5395.pdf";
			$filename = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$filename);

			do {

				$brewerAssignment = "";
				$brewerLocation = "";

				if (($row_brewer['staff_judge'] == 1) || ($row_brewer['staff_steward'] == 1) || ($row_brewer['staff_staff'] == 1) || ($row_brewer['staff_organizer'] == 1)) {

					if ($row_brewer['staff_judge'] == 1) $brewerAssignment .= "Judge, ";
					if ($row_brewer['staff_steward'] == 1) $brewerAssignment .= "Steward, ";
					if ($row_brewer['staff_staff'] == 1) $brewerAssignment .= "Staff, ";
					if ($row_brewer['staff_organizer'] == 1) $brewerAssignment .= "Organizer";
					
					$brewerAssignment = rtrim($brewerAssignment,", ");
					$brewerAssignment = rtrim($brewerAssignment," ");
					$brewerAssignment = rtrim($brewerAssignment,",");
					
					if ($row_brewer['brewerCity'] != "Anytown") $brewerLocation = $row_brewer['brewerCity'].", ".$row_brewer['brewerState'];

					$text = sprintf("\n%s\n%s\n%s",
						$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName'],
						$brewerAssignment,
						$brewerLocation
					);

					$text = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$text); 
					$pdf->Add_Label($text);

				}

			} while ($row_brewer = mysqli_fetch_assoc($brewer));

			if (ob_get_length()) ob_clean();
			$pdf->Output($filename,'D');

		}

		if (($go == "participants") && ($action == "judging_labels") && ($id == "default")) {

			if ($psort == "3422") $pdf = new PDF_Label('3422');
			else $pdf = new PDF_Label('5160');
			$pdf->AddPage();
			$pdf->SetFont('Arial','',9);
			$filename .= str_replace(" ","_",$_SESSION['contestName'])."_All_Judge_Scoresheet_Labels";
			if ($psort == "3422") 		$filename .= "_Avery3422";
			else 						$filename .= "_Avery5160";
			$filename .= ".pdf";
			$filename = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$filename);

			$character_limit = $character_limit + 6; // Arial allows for more characters per line

			do {

				$bjcp_rank = explode(",",$row_brewer['brewerJudgeRank']);
				$rank = bjcp_rank($bjcp_rank[0],2);
				if (((strpos($rank, "Non-BJCP Judge") !== false)) && (($row_brewer['brewerJudgeMead'] == "Y") || ($row_brewer['brewerJudgeCider'] == "Y"))) $rank = "BJCP Cider or Mead Judge";
				$mead = "";
				$pro = "";
				$cert_cicerone = "";
				$adv_cicerone = "";
				$mast_cicerone = "";

				$j = preg_replace('/[a-zA-Z]/','',$row_brewer['brewerJudgeID']);
				if ($j > 0) $judge_id = " (".$row_brewer['brewerJudgeID'].")";
				else $judge_id = "";
				$rank .= strtoupper($judge_id);

				if ($row_brewer['brewerJudgeMead'] == "Y") $mead = "Certified Mead Judge";
				if ($row_brewer['brewerJudgeMCider'] == "Y") $cider = "Certified Cider Judge";
				if (in_array("Professional Brewer", $bjcp_rank)) $pro = "Professional Brewer";
				if (in_array("Certified Cicerone", $bjcp_rank)) $cert_cicerone = "Certified Cicerone";
				if (in_array("Advanced Cicerone", $bjcp_rank)) $adv_cicerone = "Advanced Cicerone";
				if (in_array("Master Cicerone", $bjcp_rank)) $mast_cicerone = "Master Cicerone";

				$cicerone = array();
				$other = array();
				$other_ranks = "";

				if (!empty($mast_cicerone))  $cicerone[] = $mast_cicerone;
				elseif ((empty($mast_cicerone)) && (empty($cert_cicerone)) && (!empty($adv_cicerone))) $cicerone[] = $adv_cicerone;
				elseif ((empty($mast_cicerone)) && (empty($adv_cicerone)) && (!empty($cert_cicerone))) $cicerone[] = $cert_cicerone;

				if (!empty($mead)) $other[] = $mead;
				if (!empty($cider)) $other[] = $cider;
				if (!empty($pro)) $other[] = $pro;

				if ((!empty($cicerone)) && (!empty($other))) $other_combined = array_merge($cicerone, $other);
				elseif ((!empty($cicerone)) && (empty($other))) $other_combined = $cicerone;
				elseif ((empty($cicerone)) && (!empty($other))) $other_combined = $other;
				else $other_combined = "";
				if (!empty($other_combined)) $other_ranks = implode(", ", $other_combined);
				$other_ranks = ltrim($other_ranks," ,");
				$other_ranks = ltrim($other_ranks," , ");
				$other_ranks = ltrim($other_ranks,", ");
				$other_ranks = ltrim($other_ranks,",");

				$first_name = $row_brewer['brewerFirstName'];
				$last_name = $row_brewer['brewerLastName'];

				for($i=0; $i<$number_of_labels; $i++) {

					if (!empty($other_ranks)) {
						$text = sprintf("\n%s %s\n%s\n%s\n%s",
						$first_name,
						$last_name,
						truncate($rank,$character_limit),
						truncate($other_ranks,$character_limit),
						strtolower($row_brewer['brewerEmail'])
						);
					}

					else {
						$text = sprintf("\n%s %s\n%s\n%s",
						$first_name,
						$last_name,
						truncate($rank,$character_limit),
						strtolower($row_brewer['brewerEmail'])
						);
					}

					$text = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$text);
					$pdf->Add_Label($text);
				}

			} while ($row_brewer = mysqli_fetch_assoc($brewer));

			if (ob_get_length()) ob_clean();
			$pdf->Output($filename,'D');

		}

		if (($go == "participants") && ($action == "address_labels")) {

			if ($psort == "3422") $pdf = new PDF_Label('3422');
			else $pdf = new PDF_Label('5160');

			$pdf->AddPage();
			$pdf->SetFont('Arial','',8);

			if ($filter == "with_entries") {
				$filename .= str_replace(" ","_",$_SESSION['contestName'])."_Participants_With_Entries_Address_Labels";
				do {
					$with_entries_array[] = $row_with_entries['brewBrewerID'];
				} while ($row_with_entries = mysqli_fetch_assoc($with_entries));
			}

			else $filename .= str_replace(" ","_",$_SESSION['contestName'])."_All_Participant_Address_Labels";

			if ($psort == "3422") 		$filename .= "_Avery3422";
			else 						$filename .= "_Avery5160";

			$filename .= ".pdf";
			$filename = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$filename);

			do {

				if ($filter == "with_entries") {

					if (in_array($row_brewer['uid'],$with_entries_array)) {

						$user_entry_count1 = user_entry_count($row_brewer['uid'],$view);
						$user_entry_count2 = explode("^",$user_entry_count1);

						if ($user_entry_count2[0] == 1) $entry_count ="(". $user_entry_count2[0]." Entry)";
						else $entry_count = "(".$user_entry_count2[0]." Entries)";

						if ($view == "entry") $entries = $user_entry_count2[1];
						else $entries = $user_entry_count2[2];

						if ($row_brewer['brewerCountry'] != "United States") $brewer_country = $row_brewer['brewerCountry']; else $brewer_country = "";

						if (!empty($brewer_country)) $last_line = $brewer_country."\n#: ".truncate($entries,126);
						else $last_line = "#: ".truncate($entries,166);

						$text = sprintf("\n%s %s\n%s\n%s, %s %s\n%s",
							$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName'],
							$entry_count,
							$row_brewer['brewerAddress'],
							$row_brewer['brewerCity'],
							$row_brewer['brewerState'],
							$row_brewer['brewerZip'],
							$last_line
						);

						$text = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$text);
						$pdf->Add_Label($text);

					}
				}

				else {

					if ($row_brewer['brewerCountry'] != "United States") $brewer_country = $row_brewer['brewerCountry']; else $brewer_country = "";

					$text = sprintf("\n%s\n%s\n%s, %s %s\n%s",
					$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName'],
					$row_brewer['brewerAddress'],
					$row_brewer['brewerCity'],
					$row_brewer['brewerState'],
					$row_brewer['brewerZip'],
					$brewer_country
					);

					$text = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$text);
					$pdf->Add_Label($text);

				}

			} while ($row_brewer = mysqli_fetch_assoc($brewer));

			//$pdf->Output();
			if (ob_get_length()) ob_clean();
			$pdf->Output($filename,'D');

		}

		if (($go == "judging_scores") && ($action == "awards") && ($filter == "default")) {

			if ($psort == "3422") $pdf = new PDF_Label('3422');
			else $pdf = new PDF_Label('5160');
			$pdf->AddPage();
			$pdf->SetFont('Courier','',8);

			$filename .= str_replace(" ","_",$_SESSION['contestName'])."_Award_Labels";
			if ($psort == "3422") $filename .= "_Avery3422";
			else $filename .= "_Avery5160";
			$filename .= ".pdf";
			$filename = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$filename);

			include (DB.'output_labels_awards.db.php');
			ob_end_clean();
			//$pdf->Output();
			$pdf->Output($filename,'D');

		}

		if (($go == "judging_scores") && ($action == "awards") && ($filter == "round")) {
			if ($psort == "OL3012") $pdf = new PDF_Label('OL3012');
			elseif ($psort == "EU30095") $pdf = new PDF_Label('EU30095');
			elseif ($psort == "5293") $pdf = new PDF_Label('5293');
			else $pdf = new PDF_Label('OL5375');
			$pdf->AddPage();
			$pdf->SetFont('Courier','',7);

			$filename .= str_replace(" ","_",$_SESSION['contestName'])."_Medal_Labels_";
			$filename .= ucwords($psort);
			$filename .= ".pdf";
			$filename = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$filename);

			include (DB.'output_labels_awards.db.php');
			ob_end_clean();
			//$pdf->Output();
			$pdf->Output($filename,'D');
		}

	}	// end if ($_SESSION['userLevel'] <= 1)

	// --------------------------------------------------------
	// The following is the only label output that non-admins
	// can access.
	// --------------------------------------------------------

	if (($go == "participants") && ($action == "judging_labels") && ($id != "default")) {

		if ($psort == "3422") $pdf = new PDF_Label('3422');
		else $pdf = new PDF_Label('5160');
		$pdf->AddPage();
		$pdf->SetFont('Arial','',8);

		$first_name = $row_brewer['brewerFirstName'];
		$last_name = $row_brewer['brewerLastName'];

		//echo $query_brewer;

		$filename .= $first_name."_".$last_name."_Judge_Scoresheet_Labels";
		if ($psort == "3422") 		$filename .= "_Avery3422";
		else 						$filename .= "_Avery5160";
		$filename .= ".pdf";
		$filename = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$filename);

		$character_limit = $character_limit + 6; // Arial allows for more characters per line

		$bjcp_rank = explode(",",$row_brewer['brewerJudgeRank']);
		$rank = bjcp_rank($bjcp_rank[0],2);
		if (((strpos($rank, "Non-BJCP Judge") !== false)) && (($row_brewer['brewerJudgeMead'] == "Y") || ($row_brewer['brewerJudgeCider'] == "Y"))) $rank = "BJCP Cider or Mead Judge";
		$mead = "";
		$pro = "";
		$cert_cicerone = "";
		$adv_cicerone = "";
		$mast_cicerone = "";

		$j = preg_replace('/[a-zA-Z]/','',$row_brewer['brewerJudgeID']);
		if ($j > 0) $judge_id = " (".$row_brewer['brewerJudgeID'].")";
		else $judge_id = "";
		$rank .= strtoupper($judge_id);

		if ($row_brewer['brewerJudgeMead'] == "Y") $mead = "Certified Mead Judge";
		if ($row_brewer['brewerCiderMead'] == "Y") $cider = "Certified Cider Judge";
		if (in_array("Professional Brewer", $bjcp_rank)) $pro = "Professional Brewer";
		if (in_array("Certified Cicerone", $bjcp_rank)) $cert_cicerone = "Certified Cicerone";
		if (in_array("Advanced Cicerone", $bjcp_rank)) $adv_cicerone = "Advanced Cicerone";
		if (in_array("Master Cicerone", $bjcp_rank)) $mast_cicerone = "Master Cicerone";

		$cicerone = array();
		$other = array();
		$other_ranks = "";

		if (!empty($mast_cicerone))  $cicerone[] .= $mast_cicerone;
		elseif ((empty($mast_cicerone)) && (empty($cert_cicerone)) && (!empty($adv_cicerone))) $cicerone[] .= $adv_cicerone;
		elseif ((empty($mast_cicerone)) && (empty($adv_cicerone)) && (!empty($cert_cicerone))) $cicerone[] .= $cert_cicerone;
		else $cicerone[] .= "";
		
		if (!empty($mead)) $other[] .= $mead;
		if (!empty($cider)) $other[] .= $cider;
		if (!empty($pro)) $other[] .= $pro;

		if ((!empty($cicerone)) && (!empty($other))) $other_combined = array_merge($cicerone, $other);
		elseif ((!empty($cicerone)) && (empty($other))) $other_combined = $cicerone;
		elseif ((empty($cicerone)) && (!empty($other))) $other_combined = $other;
		else $other_combined = "";
		if (!empty($other_combined)) $other_ranks = implode(", ", $other_combined);
		$other_ranks = ltrim($other_ranks," ,");
		$other_ranks = ltrim($other_ranks," , ");
		$other_ranks = ltrim($other_ranks,", ");
		$other_ranks = ltrim($other_ranks,",");

		for($i=0; $i<$number_of_labels; $i++) {

			if (!empty($other_ranks)) {
				$text = sprintf("\n%s %s\n%s\n%s\n%s",
				$first_name,
				$last_name,
				truncate($rank,$character_limit),
				truncate($other_ranks,$character_limit),
				strtolower($row_brewer['brewerEmail'])
				);
			}

			else {
				$text = sprintf("\n%s %s\n%s\n%s",
				$first_name,
				$last_name,
				truncate($rank,$character_limit),
				strtolower($row_brewer['brewerEmail'])
				);
			}

			$text = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$text);
			$pdf->Add_Label($text);

		}

		ob_end_clean();
		$pdf->Output($filename,'D');

	}

}

else echo "<p>Not available.</p>";

?>