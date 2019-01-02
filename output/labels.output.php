<?php
require('../paths.php');
require(CONFIG.'bootstrap.php');
require(CLASSES.'fpdf/pdf_label.php');
include (DB.'output_labels.db.php');
include (LIB.'output.lib.php');
include (DB.'styles.db.php');
include (INCLUDES.'scrubber.inc.php');

$filename = "";
$number_of_labels = "";
$ba = FALSE;

if ($_SESSION['prefsStyleSet'] == "BA") $ba = TRUE;

// Define a character limit. 34 characters per line appears to be the limit for 5160, the smaller
// of the two label sizes available.
// 6 lines are available per label for a total of 204 possible characters at 9 pt Arial
$character_limit = 68;

// If the installation's preferences define a shorter character limit, it will
// be used.
if ($_SESSION['prefsSpecialCharLimit'] < $character_limit) $character_limit = $_SESSION['prefsSpecialCharLimit'];

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

		/*
		* -------------------------------------------------------------------
		* Reworked for 2.1.13 - May 7, 2018
		* Updated for 2.1.15 to remove special characters - December 19, 2018
		* -------------------------------------------------------------------
		*/

		if (($go == "entries") && (($action == "bottle-judging") || ($action == "bottle-entry"))) {

			// Begin PDF generation
			if ($psort == "3422") $pdf = new PDF_Label('3422');
			else $pdf = new PDF_Label('5160');

			$pdf->AddPage();

			if ($view == "default") {

				$filename = str_replace(" ","_",$_SESSION['contestName'])."_Bottle_Labels_Entry_Numbers";
				if ($filter != "default") 	$filename .= "_Category_".$filter;
				if ($psort == "3422") 		$filename .= "_Avery3422";
				else 						$filename .= "_Avery5160";
				$filename .= ".pdf";

				$pdf->SetFont('Courier','',8);

				// Print labels
				do {

					if ($action == "bottle-entry") $entry_no = sprintf("%06s",$row_log['id']);
					else $entry_no = sprintf("%06s",$row_log['brewJudgingNumber']);
					$text = sprintf("\n%s (%s)   %s (%s)   %s (%s)\n\n\n%s (%s)   %s (%s)   %s (%s)",
					$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory'],
					$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory'],
					$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory'],
					$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory'],
					$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory'],
					$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory']
					);
					$text = iconv('UTF-8', 'windows-1252//IGNORE', $text);
					$pdf->Add_Label($text);

				} while ($row_log = mysqli_fetch_assoc($log));

			}

			else {

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

				// Assemble the file name
				$filename = str_replace(" ","_",$_SESSION['contestName'])."_Bottle_Labels_";
				if ($action == "bottle-entry") $filename .= "Entry_Numbers";
				else $filename .= "Judging_Numbers";

				if ($filter != "default") $filename .= "_Category_".$filter;

				$filename .= "_Req_Info_Mead-Cider";

				if ($psort == "3422") $filename .= "_Avery3422";
				else $filename .= "_Avery5160";

				$filename .= ".pdf";

				// Print labels
				do {

					for($i=0; $i<$sort; $i++) {

						$text = "";
						$entry_info = "";
						$special = "";
						$special_only = "";
						$optional = "";
						$entry_str_sweet_carb = "";
						$mead_cider = "";
						$beer_strength = "";
						$beer_sweeteness = "";
						$beer_carbonation = "";

						if ($action == "bottle-entry") $entry_no = sprintf("%06s",$row_log['id']);
						else $entry_no = sprintf("%06s",$row_log['brewJudgingNumber']);
						$style = $row_log['brewCategorySort'].$row_log['brewSubCategory'];
						$style_name = truncate($row_log['brewStyle'],22);

						if ($ba) $entry_info = sprintf("%s  #%s", $style_name, $entry_no);
						else $entry_info = sprintf("\n%s: %s  #%s", $style, $style_name, $entry_no);

						if (empty($row_log['brewInfoOptional'])) {
							if ($_SESSION['prefsSpecialCharLimit'] > ($character_limit * 2)) $character_limit_adjust = ($character_limit * 2);
							else $character_limit_adjust = $_SESSION['prefsSpecialCharLimit'];
						}

						else $character_limit_adjust = $character_limit;

						if (in_array($style,$special_ingredients)) {

							$special = strip_tags($row_log['brewInfo']);
							$special = html_entity_decode($special);
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
								$character_limit_adjust = $character_limit_adjust - 12;
								if (!in_array($style,$mead)) $special = strtr($special,$special_strength);
							}

							$special = str_replace("\n"," ",truncate($special,$character_limit_adjust));
							$special = strtr($special,$html_remove);
							$special = str_replace("^", "", $special);
							$special = rtrim($special," ");
							$entry_str_sweet_carb .= $beer_carbonation.$beer_sweeteness.$beer_strength;

							if (!empty($special)) $special = sprintf("\n%s", $special);
						}

						if (empty($special)) {
							if ($_SESSION['prefsSpecialCharLimit'] > ($character_limit * 2)) $character_limit_adjust = ($character_limit * 2);
							else $character_limit_adjust = $_SESSION['prefsSpecialCharLimit'];
						}

						else $character_limit_adjust = $character_limit;

						//echo $character_limit_adjust;

						if (!empty($row_log['brewInfoOptional'])) {
							$special_optional = strip_tags($row_log['brewInfo']);
							$special_optional = html_entity_decode($special_optional);
							$optional = str_replace("\n"," ",truncate($special_optional,$character_limit_adjust,""));
							$optional = strtr($optional,$html_remove);
							$optional = sprintf("\n%s",$optional);
						}

						if (in_array($style,$mead)) {
							if (!empty($row_log['brewMead1'])) $entry_str_sweet_carb .= sprintf("*%s* ",$row_log['brewMead1']);
							if (!empty($row_log['brewMead2'])) $entry_str_sweet_carb .= sprintf("*%s* ",$row_log['brewMead2']);
							if (!empty($row_log['brewMead3'])) $entry_str_sweet_carb .= sprintf("*%s* ",$row_log['brewMead3']);
						}

						if (!empty($entry_str_sweet_carb)) {
							$entry_str_sweet_carb = str_replace("Medium Sweet", "Med Sweet", $entry_str_sweet_carb);
							$entry_str_sweet_carb = str_replace("Medium Dry", "Med Dry", $entry_str_sweet_carb);
							$entry_str_sweet_carb = str_replace("Sparkling", "Spark", $entry_str_sweet_carb);
							$entry_str_sweet_carb = str_replace("Hydromel", "Hydro", $entry_str_sweet_carb);
							$entry_str_sweet_carb = str_replace("Petillant", "Petill", $entry_str_sweet_carb);
							$entry_str_sweet_carb = sprintf("\n%s",$entry_str_sweet_carb);

						}

						if ($view == "special") {
							if ((in_array($style,$special_ingredients)) || (in_array($style,$mead))) $text = $entry_info.$special.$entry_str_sweet_carb;
							else $text = "";
						}
						else $text = $entry_info.$special.$entry_str_sweet_carb.$optional;

						$text = iconv('UTF-8', 'windows-1252//IGNORE', $text);
						if (!empty($text)) $pdf->Add_Label($text);
						//echo $text;

					}

				} while ($row_log = mysqli_fetch_assoc($log));

			} // end else

			if (ob_get_length()) ob_clean();
			//$pdf->Output();
			$pdf->Output($filename,'D');

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
						$text = sprintf("\n%s\n(%s)",$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory']);
						$text = iconv('UTF-8', 'windows-1252//IGNORE', $text);
						$pdf->Add_Label($text);
					}

					if (($entry_no != "") && ($filter == "recent") && (strtotime($row_log['brewUpdated']) > $row_contest_dates['contestRegistrationDeadline'])) {
						$text = sprintf("\n%s\n(%s)",$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory']);
						$text = iconv('UTF-8', 'windows-1252//IGNORE', $text);
						$pdf->Add_Label($text);
					}

				}

			} while ($row_log = mysqli_fetch_assoc($log));

			//$pdf->Output();
			if (ob_get_length()) ob_clean();;
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

					$entry_no = sprintf("%04s",$row_log['id']);

					if ((!empty($entry_no)) && ($filter == "default")) {
						$text = sprintf("\n%s\n(%s)",$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory']);
						$pdf->Add_Label($text);
					}

					if ((!empty($entry_no)) && ($filter == "recent") && (strtotime($row_log['brewUpdated']) > $row_contest_dates['contestRegistrationDeadline'])) {
						$text = sprintf("\n%s\n(%s)",$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory']);
						$text = iconv('UTF-8', 'windows-1252//IGNORE', $text);
						$pdf->Add_Label($text);
					}

				}

			} while ($row_log = mysqli_fetch_assoc($log));

			//$pdf->Output();
			if (ob_get_length()) ob_clean();;
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
					$text = sprintf("\n%s",$row_log['brewCategorySort'].$row_log['brewSubCategory']);
					$text = iconv('UTF-8', 'windows-1252//IGNORE', $text);
					$pdf->Add_Label($text);
				}

			} while ($row_log = mysqli_fetch_assoc($log));

			//$pdf->Output();
			if (ob_get_length()) ob_clean();;
			$pdf->Output($filename,'D');
		}

		if (($go == "participants") && ($action == "judging_nametags")) {

			$pdf = new PDF_Label('5395');
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',18);
			$filename .= str_replace(" ","_",$_SESSION['contestName'])."_Nametags_Avery5395.pdf";

			do {

				$brewerAssignment = "";
				$brewerLocation = "";

				if (($row_brewer['staff_judge'] == 1) || ($row_brewer['staff_steward'] == 1) || ($row_brewer['staff_staff'] == 1) || ($row_brewer['staff_organizer'] == 1)) {

					if ($row_brewer['staff_judge'] == 1) $brewerAssignment = "Judge";
					if ($row_brewer['staff_steward'] == 1) $brewerAssignment = "Steward";
					if ($row_brewer['staff_staff'] == 1) $brewerAssignment = "Staff";
					if ($row_brewer['staff_organizer'] == 1) $brewerAssignment = "Organizer";

					if ($row_brewer['brewerCity'] != "Anytown") $brewerLocation = $row_brewer['brewerCity'].", ".$row_brewer['brewerState'];

					$text = sprintf("\n%s\n%s\n%s",
						$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName'],
						$brewerAssignment,
						$brewerLocation
					);

					$text = iconv('UTF-8', 'windows-1252//IGNORE', $text);
					$pdf->Add_Label($text);

				}

			} while ($row_brewer = mysqli_fetch_assoc($brewer));

			if (ob_get_length()) ob_clean();;
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

			do {

				$bjcp_rank = explode(",",$row_brewer['brewerJudgeRank']);
				$rank = bjcp_rank($bjcp_rank[0],2);
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
				if (in_array("Professional Brewer", $bjcp_rank)) $pro = "Professional Brewer";
				if (in_array("Certified Cicerone", $bjcp_rank)) $cert_cicerone = "Certified Cicerone";
				if (in_array("Advanced Cicerone", $bjcp_rank)) $adv_cicerone = "Advanced Cicerone";
				if (in_array("Master Cicerone", $bjcp_rank)) $mast_cicerone = "Master Cicerone";

				$cicerone = "";
				$other = "";
				$other_ranks = "";

				if (!empty($mast_cicerone))  $cicerone[] = $mast_cicerone;
				elseif ((empty($mast_cicerone)) && (empty($cert_cicerone)) && (!empty($adv_cicerone))) $cicerone[] = $adv_cicerone;
				elseif ((empty($mast_cicerone)) && (empty($adv_cicerone)) && (!empty($cert_cicerone))) $cicerone[] = $cert_cicerone;

				if (!empty($mead)) $other[] = $mead;
				if (!empty($pro)) $other[] = $pro;

				if ((!empty($cicerone)) && (!empty($other))) $other_combined = array_merge($cicerone, $other);
				elseif ((!empty($cicerone)) && (empty($other))) $other_combined = $cicerone;
				elseif ((empty($cicerone)) && (!empty($other))) $other_combined = $other;
				else $other_combined = "";
				if (!empty($other_combined)) $other_ranks = implode(", ", $other_combined);

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


					$text = iconv('UTF-8', 'windows-1252//IGNORE', $text);
					$pdf->Add_Label($text);
				}

			} while ($row_brewer = mysqli_fetch_assoc($brewer));

			if (ob_get_length()) ob_clean();;
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

							if (!empty($brewer_country)) $last_line = $brewer_country."\nAttn: ".truncate($entries,126);
							else $last_line = "Attn: ".truncate($entries,166);

							$text = sprintf("\n%s %s\n%s\n%s, %s %s\n%s",
								$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName'],
								$entry_count,
								$row_brewer['brewerAddress'],
								$row_brewer['brewerCity'],
								$row_brewer['brewerState'],
								$row_brewer['brewerZip'],
								$last_line
							);

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

						$pdf->Add_Label($text);
					}

			} while ($row_brewer = mysqli_fetch_assoc($brewer));

			//$pdf->Output();
			if (ob_get_length()) ob_clean();;
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

			include (DB.'output_labels_awards.db.php');
			ob_end_clean();
			$pdf->Output($filename,'D');

		}

		if (($go == "judging_scores") && ($action == "awards") && ($filter == "round")) {
			if ($psort == "OL3012") $pdf = new PDF_Label('OL3012');
			elseif ($psort == "EU30095") $pdf = new PDF_Label('EU30095');
			elseif ($psort == "5293") $pdf = new PDF_Label('5293');
			else $pdf = new PDF_Label('OL5375');
			$pdf->AddPage();
			$pdf->SetFont('Courier','',8);

			$filename .= str_replace(" ","_",$_SESSION['contestName'])."_Medal_Labels_";
			$filename .= ucwords($psort);
			$filename .= ".pdf";

			include (DB.'output_labels_awards.db.php');
			ob_end_clean();
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

		/*

		$bjcp_rank = explode(",",$row_brewer['brewerJudgeRank']);
		$rank = bjcp_rank($bjcp_rank[0],2);
		//$rank = str_replace(",",", ",$row_brewer['brewerJudgeRank']);

		if (!empty($bjcp_rank[1])) $rank .= ", ".$bjcp_rank[1];
		if (!empty($bjcp_rank[2])) $rank .= ", ".$bjcp_rank[2];
		//$rank2 = truncate($rank2,$character_limit);

		$j = preg_replace('/[a-zA-Z]/','',$row_brewer['brewerJudgeID']);
		//$j = ltrim($row_brewer['brewerJudgeID'],'/[a-z][A-Z]/');

		if ($j > 0) $judge_id = " (".$row_brewer['brewerJudgeID'].")";
		else $judge_id = "";

		*/

		$bjcp_rank = explode(",",$row_brewer['brewerJudgeRank']);
		$rank = bjcp_rank($bjcp_rank[0],2);
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
		if (!empty($pro)) $other[] .= $pro;

		if ((!empty($cicerone)) && (!empty($other))) $other_combined = array_merge($cicerone, $other);
		elseif ((!empty($cicerone)) && (empty($other))) $other_combined = $cicerone;
		elseif ((empty($cicerone)) && (!empty($other))) $other_combined = $other;
		else $other_combined = "";
		if (!empty($other_combined)) $other_ranks = implode(", ", $other_combined);

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

			$text = iconv('UTF-8', 'windows-1252//IGNORE', $text);
			$pdf->Add_Label($text);

		}

		ob_end_clean();
		$pdf->Output($filename,'D');

	}

}

else echo "<p>Not available.</p>";
?>