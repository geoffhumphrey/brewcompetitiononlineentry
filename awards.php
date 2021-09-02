<?php
/**
 * Module:      awards.php
 * Description: This module is the delivery vehicle for the awards presentation.
 *
 */

// ---------------------------- Load Config Scripts ------------------------------

require_once ('paths.php');
require_once (CONFIG.'bootstrap.php');
require_once (LIB.'admin.lib.php');
include (DB.'winners.db.php');
include (DB.'score_count.db.php');
include (DB.'sponsors.db.php');

$table_head1 = "";
$table_head2 = "";
$table_body1 = "";
$table_body2 = "";
$bb_count = 0;
$bb_count_clubs = "";
$bb_previouspoints = "";
$bb_previouspoints_clubs = "";

$winner_method = $_SESSION['prefsWinnerMethod'];
$style_set = $_SESSION['prefsStyleSet'];

$display_to_public = FALSE;
if ((judging_date_return() == 0) && ($entry_window_open == 2) && ($registration_open == 2) && ($judge_window_open == 2) && ($_SESSION['prefsDisplayWinners'] == "Y") && (judging_winner_display($_SESSION['prefsWinnerDelay']))) $display_to_public = TRUE;

$display_to_admin = FALSE;
if (($logged_in) && ($_SESSION['userLevel'] <= 1)) $display_to_admin = TRUE;

if ((!$display_to_public) && (!$display_to_admin)) {
	header(sprintf("Location: %s", $base_url."index.php?msg=7"));
    exit;
}

if (($display_to_admin) || ($display_to_public)) {

	if ($view == "default") $view = "white";

	$reveal_theme = array(
		"white" => "white.min.css",
		"black" => "black.min.css",
		"blue" => "moon.min.css",
		"beige" => "beige.min.css"
	);

	$places = array(
		"5" => "1st",
		"4" => "2nd",
		"3" => "3rd",
		"2" => "4th",
		"1" => "HM"
	);

	// Judges and Stewards
	$query_assignments = sprintf("SELECT DISTINCT c.uid, c.brewerLastName, c.brewerFirstName, c.brewerJudgeRank, c.brewerClubs, a.assignment, b.staff_judge, b.staff_steward, b.staff_judge_bos, b.staff_staff, b.staff_organizer FROM %s a RIGHT JOIN (%s b CROSS JOIN %s c ON b.uid=c.uid) ON c.uid=a.bid WHERE b.staff_judge='1' OR b.staff_steward='1' OR b.staff_judge_bos='1' OR b.staff_staff='1' OR b.staff_organizer='1' ORDER BY c.brewerLastName, c.brewerFirstName ASC;", $prefix."judging_assignments", $prefix."staff", $prefix."brewer");
	$assignments = mysqli_query($connection,$query_assignments) or die (mysqli_error($connection));
	$row_assignments = mysqli_fetch_assoc($assignments);
	$totalRows_assignments = mysqli_num_rows($assignments);
	$judge_list = "";
	$judge_bos = "";
	$steward_list = "";
	$staff_list = "";
	$staff_organizer = "";

	if ($totalRows_assignments > 0) {
		do {

			if ($row_assignments['staff_judge'] == 1) {
				$judge_list .= $row_assignments['brewerFirstName']." ".$row_assignments['brewerLastName'];
				$judge_list .= ", ";
			}

			if ($row_assignments['staff_judge_bos'] == 1) {
				$judge_bos .= $row_assignments['brewerFirstName']." ".$row_assignments['brewerLastName'];
				$judge_bos .= ", ";
			}

			if ($row_assignments['staff_steward'] == 1) {
				$steward_list .= $row_assignments['brewerFirstName']." ".$row_assignments['brewerLastName'];
				$steward_list .= ", ";
			}

			if ($row_assignments['staff_staff'] == 1)  {
				$staff_list .= $row_assignments['brewerFirstName']." ".$row_assignments['brewerLastName'];
				$staff_list .= ", ";
			}

			if ($row_assignments['staff_organizer'] == 1)  {
				$staff_organizer .= $row_assignments['brewerFirstName']." ".$row_assignments['brewerLastName'];
				$staff_organizer .= ", ";
			}

		} while ($row_assignments = mysqli_fetch_assoc($assignments));
	}

	$slides = "";
	$slides_bos = "";
	$slides_best_brewer = "";
	$slides_best_club = "";

	if ($row_scored_entries['count'] > 0) {

		// Build slides by Table
		if ($_SESSION['prefsWinnerMethod'] == "0") {

			if ($totalRows_tables > 0) {
				
				do {

					include (DB.'scores.db.php');

					$entry_count = get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default");
					if ($entry_count > 1) $entries = strtolower($label_entries); else $entries = strtolower($label_entry);

					$assigned_judge_names_display = "";

					$assigned_judges = assigned_judges($row_tables['id'],"default",$prefix."judging_assignments",1);

					if ($assigned_judges > 0) {

						$query_assigned_judge_names = sprintf("SELECT a.brewerFirstName,a.brewerLastName, b.assignRoles FROM %s a, %s b WHERE b.assignTable='%s' AND a.uid = b.bid ORDER BY a.brewerLastName, a.brewerFirstName ASC",$prefix."brewer",$prefix."judging_assignments",$row_tables['id']);
						$assigned_judge_names = mysqli_query($connection,$query_assigned_judge_names);
						$row_assigned_judge_names = mysqli_fetch_assoc($assigned_judge_names);
						
						do {
							$assigned_judge_names_display .= $row_assigned_judge_names['brewerFirstName']." ".$row_assigned_judge_names['brewerLastName'];
							if ((isset($row_assigned_judge_names['assignRoles'])) && (strpos($row_assigned_judge_names['assignRoles'], "HJ") !== false)) $assigned_judge_names_display .= " <span style=\"font-size: .75em;\">(".$label_head_judge.")</span>";
							$assigned_judge_names_display .= ", ";
						} while ($row_assigned_judge_names = mysqli_fetch_assoc($assigned_judge_names));

						$assigned_judge_names_display = rtrim($assigned_judge_names_display, ", ");
					
					}
					
					// Build Slide
					$slides .= "<section>";

					$slides .= "<h1 class=\"r-fit-text tight\">";
					$slides .= sprintf("%s %s: %s",$label_table,$row_tables['tableNumber'],$row_tables['tableName']);
					$slides .= "</h1>";

					$slides .= "<p class=\"entry-count\">";
					$slides .= sprintf("%s %s",$entry_count,$entries);
					$slides .= "</p>";
					if (!empty($assigned_judge_names_display)) $slides .= sprintf("<p class=\"small entry-count\">%s: %s</p>",$label_judges,$assigned_judge_names_display);

					// Perform check to see if any placing entries
					// If so, loop through and display as normal
					if ($totalRows_scores > 0) {

						do {

							$place_heirarchy = place_heirarchy($row_scores['scorePlace']);
							$display_place = display_place($row_scores['scorePlace'],1);

							$entry_name = html_entity_decode($row_scores['brewName'], ENT_QUOTES | ENT_XML1, 'UTF-8');
							$entry_name = htmlentities($entry_name,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5,"UTF-8");

							// Category/Style Display
							if ($_SESSION['prefsStyleSet'] == "AABC") $style = ltrim($row_scores['brewCategory'],"0").".".ltrim($row_scores['brewSubCategory'],"0");
	       					else $style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
	       					if ($_SESSION['prefsStyleSet'] == "BA") $style_display = $row_scores['brewStyle'];
							else $style_display = $style.": ".$row_scores['brewStyle'];

	       					// Name Display
							if ($_SESSION['prefsProEdition'] == 1) $brewer_name = $row_scores['brewerBreweryName'];
							else $brewer_name = $row_scores['brewerFirstName']." ".$row_scores['brewerLastName'];

							$brewer_club = "";
							if ((!empty($row_scores['brewerClubs'])) && ($row_scores['brewerClubs'] != "Other")) $brewer_club = $row_scores['brewerClubs'];

							// Build Slide Content
							$slides .= "<div id=\"medal-grid\">";
							$slides .= "<div class=\"fragment justify-right col-right\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."\"><i class=\"fa fa-trophy icon pos-".$place_heirarchy."-medal-color\"></i>".$display_place."</div>";
							$slides .= "<div class=\"fragment justify-left\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-name\">";
							$slides .= $brewer_name;
							if (!empty($row_scores['brewCoBrewer'])) $slides .= "<span style=\"padding-top: .9em;\" class=\"small\">&nbsp;&amp;&nbsp;<em>".truncate_string($row_scores['brewCoBrewer'],20," ")."</em></span>";
							$slides .= "</div>";
							if ($_SESSION['prefsProEdition'] == 0) $slides .= "<div class=\"fragment justify-left small\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-club\">".truncate_string($brewer_club,25," ")."</div>";
							$slides .= "<div class=\"fragment justify-left small entry-name bottom-row\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-style\">".truncate_string($entry_name,65," ")." (".$style_display.")</div>";
							$slides .= "</div>";

						} while ($row_scores = mysqli_fetch_assoc($scores));

					}

					// If not, display "no winning entries" message
					else {
						$slides .= "<p>".$winners_text_000."</p>";
					}
							
					$slides .= "</section>\n";

				} while ($row_tables = mysqli_fetch_assoc($tables));

			} // end if ($totalRows_tables > 0)

		} // end if ($_SESSION['prefsWinnerMethod'] == "0")

		// Build slides by Category
		if ($_SESSION['prefsWinnerMethod'] == "1") {
			
			$a = styles_active(0,"default");

			foreach (array_unique($a) as $style) {

				if (!empty($style)) {

					include (DB.'winners_category.db.php');

					if ($row_entry_count['count'] > 1) $entries_display = strtolower($label_entries);
					else $entries_display = strtolower($label_entry);
					
					if ($row_score_count['count'] > 0) {

						include (DB.'scores.db.php');

						$slides .= "<section>";

						if ($_SESSION['prefsStyleSet'] == "BA") {

							include (INCLUDES.'ba_constants.inc.php');
							$slides .= "<h1 class=\"r-fit-text tight\">";
							$slides .= $ba_category_names[$style];
							$slides .= "</h1>";

						} // end if ($_SESSION['prefsStyleSet'] == "BA")

						else {

							//style_convert($number,$type,$base_url="",$archive="")

							$slides .= "<h1 class=\"r-fit-text tight\">";
							$slides .= sprintf("%s %s: %s",$label_category,ltrim($style,"0"),style_convert($style,"1"));
							$slides .= "</h1>";

						}

						$slides .= "<p class=\"entry-count\">";
						$slides .= sprintf("%s %s",$row_entry_count['count'],$entries_display);
						$slides .= "</p>";

						if ($totalRows_scores > 0) {

							do {

								$place_heirarchy = place_heirarchy($row_scores['scorePlace']);
								$display_place = display_place($row_scores['scorePlace'],1);

								$entry_name = html_entity_decode($row_scores['brewName'], ENT_QUOTES | ENT_XML1, 'UTF-8');
								$entry_name = htmlentities($entry_name,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5,"UTF-8");

								// Category/Style Display
								if ($_SESSION['prefsStyleSet'] == "AABC") $style = ltrim($row_scores['brewCategory'],"0").".".ltrim($row_scores['brewSubCategory'],"0");
		       					else $style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
		       					if ($_SESSION['prefsStyleSet'] == "BA") $style_display = $row_scores['brewStyle'];
								else $style_display = $style.": ".$row_scores['brewStyle'];

		       					// Name Display
								if ($_SESSION['prefsProEdition'] == 1) $brewer_name = $row_scores['brewerBreweryName'];
								else $brewer_name = $row_scores['brewerFirstName']." ".$row_scores['brewerLastName'];

								$brewer_club = "";
								if ((!empty($row_scores['brewerClubs'])) && ($row_scores['brewerClubs'] != "Other")) $brewer_club = $row_scores['brewerClubs'];

								// Build Slide Content
								$slides .= "<div id=\"medal-grid\">";
								$slides .= "<div class=\"fragment justify-right col-right\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."\"><i class=\"fa fa-trophy icon pos-".$place_heirarchy."-medal-color\"></i>".$display_place."</div>";
								$slides .= "<div class=\"fragment justify-left\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-name\">";
								$slides .= $brewer_name;
								if (!empty($row_scores['brewCoBrewer'])) $slides .= "<span style=\"padding-top: .9em;\" class=\"small\">&nbsp;&amp;&nbsp;<em>".truncate_string($row_scores['brewCoBrewer'],20," ")."</em></span>";
								$slides .= "</div>";
								
								if ($_SESSION['prefsProEdition'] == 0) $slides .= "<div class=\"fragment justify-left small\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-club\">".truncate_string($brewer_club,25," ")."</div>";
								$slides .= "<div class=\"fragment justify-left small entry-name bottom-row\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-style\">".truncate_string($entry_name,65," ")." (".$style_display.")</div>";
								$slides .= "</div>";

							} while ($row_scores = mysqli_fetch_assoc($scores));

						}

						// If not, display "no winning entries" message
						else {
							$slides .= "<p>".$winners_text_000."</p>";
						}

						$slides .= "</section>\n";

					} // end if ($row_score_count['count'] > 0)

				} // end if (!empty($style))

			}

		} // end if ($_SESSION['prefsWinnerMethod'] == "1")

		// Build slides by Sub-Category
		if ($_SESSION['prefsWinnerMethod'] == "2") {

			if ($_SESSION['prefsStyleSet'] == "BJCP2008") $category_end = 28;
			else $category_end = 34;

			$a = styles_active(2,"default");

			foreach (array_unique($a) as $style) {

				$style = explode("^",$style);

				include (DB.'winners_subcategory.db.php');

				// Display all winners
				if ($row_entry_count['count'] > 0) {

					if ($row_entry_count['count'] > 1) $entries_display = "entries"; 
					else $entries_display = "entry";
					
					if ($row_score_count['count'] > 0) {

						include (DB.'scores.db.php');

						$slides .= "<section>";

						if ($_SESSION['prefsStyleSet'] == "BA") {

							$slides .= "<h1 class=\"r-fit-text tight\">";
							$slides .= $style[2];
							$slides .= "</h1>";

						} // end if ($_SESSION['prefsStyleSet'] == "BA")

						else {

							$slides .= "<h1 class=\"r-fit-text tight\">";
							$slides .= sprintf("%s %s%s: %s",$label_category,ltrim($style[0],"0"),$style[1],$style[2]);
							$slides .= "</h1>";

						}

						$slides .= "<p class=\"entry-count\">";
						$slides .= sprintf("%s %s",$row_entry_count['count'],$entries_display);
						$slides .= "</p>";

						if ($totalRows_scores > 0) {

							do {

								$place_heirarchy = place_heirarchy($row_scores['scorePlace']);
								$display_place = display_place($row_scores['scorePlace'],1);

								$entry_name = html_entity_decode($row_scores['brewName'], ENT_QUOTES | ENT_XML1, 'UTF-8');
								$entry_name = htmlentities($entry_name,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5,"UTF-8");

								// Category/Style Display
								if ($_SESSION['prefsStyleSet'] == "AABC") $style = ltrim($row_scores['brewCategory'],"0").".".ltrim($row_scores['brewSubCategory'],"0");
		       					else $style = $row_scores['brewCategory'].$row_scores['brewSubCategory'];
		       					if ($_SESSION['prefsStyleSet'] == "BA") $style_display = $row_scores['brewStyle'];
								else $style_display = $style.": ".$row_scores['brewStyle'];

		       					// Name Display
								if ($_SESSION['prefsProEdition'] == 1) $brewer_name = $row_scores['brewerBreweryName'];
								else $brewer_name = $row_scores['brewerFirstName']." ".$row_scores['brewerLastName'];

								$brewer_club = "";
								if ((!empty($row_scores['brewerClubs'])) && ($row_scores['brewerClubs'] != "Other")) $brewer_club = $row_scores['brewerClubs'];

								// Build Slide Content
								$slides .= "<div id=\"medal-grid\">";
								$slides .= "<div class=\"fragment justify-right col-right\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."\"><i class=\"fa fa-trophy icon pos-".$place_heirarchy."-medal-color\"></i>".$display_place."</div>";
								$slides .= "<div class=\"fragment justify-left\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-name\">";
								$slides .= $brewer_name;
								if (!empty($row_scores['brewCoBrewer'])) $slides .= "<span style=\"padding-top: .9em;\" class=\"small\">&nbsp;&amp;&nbsp;<em>".truncate_string($row_scores['brewCoBrewer'],20," ")."</em></span>";
								$slides .= "</div>";

								if ($_SESSION['prefsProEdition'] == 0) $slides .= "<div class=\"fragment justify-left small\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-club\">".truncate_string($brewer_club,25," ")."</div>";
								$slides .= "<div class=\"fragment justify-left small entry-name bottom-row\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-style\">".truncate_string($entry_name,65," ")." (".$style_display.")</div>";
								$slides .= "</div>";

							} while ($row_scores = mysqli_fetch_assoc($scores));

						}

						// If not, display "no winning entries" message
						else {
							$slides .= "<p>".$winners_text_000."</p>";
						}

						$slides .= "</section>\n";

					} // end if ($row_score_count['count'] > 0)

				} // end if ($row_entry_count['count'] > 0)

			} // end foreach (array_unique($a) as $style)

		} // end if ($_SESSION['prefsWinnerMethod'] == "2")

	} // end if ($row_scored_entries['count'] > 0)

	/**
	 * Best of Show *
	 * Need to display combined Mead/Cider
	 */

	$display_bos_style_type = FALSE;

	do { 
		$st[] = $row_style_types['id']; 
	} while ($row_style_types = mysqli_fetch_assoc($style_types));

	sort($st);

	// print_r($st);

	foreach ($st as $type) {

		include (DB.'output_results_download_bos.db.php');

		if ($totalRows_bos > 0) {

			$display_bos_style_type = TRUE;

			$slides_bos .= "<section>";

			$slides_bos .= "<h1 class=\"r-fit-text tight\">";
			$slides_bos .= $label_bos;
			$slides_bos .= "</h1>";
			$slides_bos .= "<h3 class=\"entry-count\">";
			$slides_bos .= $row_style_type_1['styleTypeName'];
			$slides_bos .= "</h3>";
			if (!empty($judge_bos)) $slides_bos .= sprintf("<p class=\"small entry-count\">%s: %s</p>",$label_judges,rtrim($judge_bos,", "));

			do {

				$place_heirarchy = place_heirarchy($row_bos['scorePlace']);
				$display_place = display_place($row_bos['scorePlace'],1);

				$entry_name = html_entity_decode($row_bos['brewName'], ENT_QUOTES | ENT_XML1, 'UTF-8');
				$entry_name = htmlentities($entry_name,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5,"UTF-8");

				// Category/Style Display
				if ($_SESSION['prefsStyleSet'] == "AABC") $style = ltrim($row_bos['brewCategory'],"0").".".ltrim($row_bos['brewSubCategory'],"0");
					else $style = $row_bos['brewCategory'].$row_bos['brewSubCategory'];
					if ($_SESSION['prefsStyleSet'] == "BA") $style_display = $row_bos['brewStyle'];
				else $style_display = $style.": ".$row_bos['brewStyle'];

					// Name Display
				if ($_SESSION['prefsProEdition'] == 1) $brewer_name = $row_bos['brewerBreweryName'];
				else $brewer_name = $row_bos['brewerFirstName']." ".$row_bos['brewerLastName'];

				$brewer_club = "";
				if ((!empty($row_bos['brewerClubs'])) && ($row_bos['brewerClubs'] != "Other")) $brewer_club = $row_bos['brewerClubs'];

				// Build Slide Content
				$slides_bos .= "<div id=\"medal-grid\">";
				$slides_bos .= "<div class=\"fragment justify-right col-right\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."\"><i class=\"fa fa-trophy icon pos-".$place_heirarchy."-medal-color\"></i>".$display_place."</div>";
				$slides_bos .= "<div class=\"fragment justify-left\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-name\">";
				$slides_bos .= $brewer_name;
				if (!empty($row_bos['brewCoBrewer'])) $slides_bos .= "<span style=\"padding-top: .9em;\" class=\"small\">&nbsp;&amp;&nbsp;<em>".truncate_string($row_bos['brewCoBrewer'],20," ")."</em></span>";
				$slides_bos .= "</div>";

				if ($_SESSION['prefsProEdition'] == 0) $slides_bos .= "<div class=\"fragment justify-left small\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-club\">".truncate_string($brewer_club,25," ")."</div>";
				$slides_bos .= "<div class=\"fragment justify-left small entry-name bottom-row\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-style\">".truncate_string($entry_name,65," ")." (".$style_display.")</div>";
				$slides_bos .= "</div>"; 
			
			} while ($row_bos = mysqli_fetch_assoc($bos));

			$slides_bos .= "</section>\n";

		}

	} // end foreach ($a as $type)

	/**
	 * Special/Custom "Best of"
	 */

	if ($totalRows_sbi > 0) {

		do {

			include (DB.'output_results_download_sbd.db.php');
				
				if ($totalRows_sbd > 0) {

					$slides_bos .= "<section>";

					$slides_bos .= "<h1 class=\"r-fit-text tight\">";
					$slides_bos .= $row_sbi['sbi_name'];
					$slides_bos .= "</h1>";

					$place_heirarchy_count = 0;

					do {

						$place_heirarchy_count += 1;
						$display_place = "";

						if (($row_sbi['sbi_display_places'] == "1") && (!empty($row_sbd['sbd_place']))) {
							$place_heirarchy = place_heirarchy($row_sbd['sbd_place']);
							$display_place = display_place($row_sbd['sbd_place'],1);
						}
						
						else {
							$place_heirarchy = place_heirarchy($place_heirarchy_count);
						}

						$entry_name = html_entity_decode($row_sbd['brewName'],ENT_QUOTES|ENT_XML1,"UTF-8");
						$entry_name = htmlentities($entry_name,ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5,"UTF-8");
						
						// Category/Style Display
						if ($_SESSION['prefsStyleSet'] == "AABC") $style = ltrim($row_sbd['brewerCategory'],"0").".".ltrim($row_sbd['brewSubCategory'],"0");
							else $style = $row_sbd['brewCategory'].$row_sbd['brewSubCategory'];
						if ($_SESSION['prefsStyleSet'] == "BA") $style_display = $row_sbd['brewStyle'];
						else $style_display = $row_sbd['brewCategory'].$row_sbd['brewSubCategory'].": ".$row_sbd['brewStyle'];

							// Name Display
						if ($_SESSION['prefsProEdition'] == 1) $brewer_name = $row_sbd['brewerBreweryName'];
						else $brewer_name = $row_sbd['brewerFirstName']." ".$row_sbd['brewerLastName'];

						$brewer_club = "";
						if ((!empty($row_sbd['brewerClubs'])) && ($row_sbd['brewerClubs'] != "Other")) $brewer_club = $row_sbd['brewerClubs'];

						// Build Slide Content
						$slides_bos .= "<div id=\"medal-grid\">";
						$slides_bos .= "<div class=\"fragment justify-right col-right\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."\"><i class=\"fa fa-trophy icon pos-".$place_heirarchy."-medal-color\"></i>".$display_place."</div>";
						$slides_bos .= "<div class=\"fragment justify-left\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-name\">".$brewer_name."</div>";
						if ($_SESSION['prefsProEdition'] == 0) $slides_bos .= "<div class=\"fragment justify-left small\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-club\">".truncate_string($brewer_club,25," ")."</div>";
						$slides_bos .= "<div class=\"fragment justify-left small entry-name bottom-row\" data-fragment-index=\"".$place_heirarchy."\" id=\"pos-".$place_heirarchy."-style\">".truncate_string($entry_name,65," ")." (".$style_display.")</div>";
						$slides_bos .= "</div>";
					
					} while ($row_sbd = mysqli_fetch_assoc($sbd));

					$slides_bos .= "</section>\n";

				}

		} while ($row_sbi = mysqli_fetch_assoc($sbi));

	}

	/**
	 * Best Brewer / Best Club *
	 */

	if (($row_limits['prefsShowBestBrewer'] != 0) || ($row_limits['prefsShowBestClub'] != 0)) {
		
		$bestbrewer = array();
		$bestbrewer_clubs = array();

		include(DB.'scores_bestbrewer.db.php');

		// Loop through brewing table for preliminary round scores
		do {

			$place = floor($bb_row_scores['scorePlace']);
			$club_name = normalizeClubs($bb_row_scores['brewerClubs']);

			if (array_key_exists($bb_row_scores['uid'], $bestbrewer)) {
				if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer[$bb_row_scores['uid']]['Places'][$place-1] += 1;
				$bestbrewer[$bb_row_scores['uid']]['Scores'][] = $bb_row_scores['scoreEntry'];

				// -- Compile separate vars for clubs --
				if (!empty($bb_row_scores['brewerClubs'])) {

					if (array_key_exists($club_name, $bestbrewer_clubs)) {
						if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places'][$place-1] += 1;
						$bestbrewer_clubs[$club_name]['Scores'][] = $bb_row_scores['scoreEntry'];
					}

					else {
						$bestbrewer_clubs[$club_name]['Places'] = array(0,0,0,0,0);
						if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places'][$place-1] = 1;
						$bestbrewer_clubs[$club_name]['Scores'] = array();
						$bestbrewer_clubs[$club_name]['Scores'][] = $bb_row_scores['scoreEntry'];
						$bestbrewer_clubs[$club_name]['Clubs'] = $bb_row_scores['brewerClubs'];
					}

				}// -- end clubs --

			}

			else {
				
				if ($_SESSION['prefsProEdition'] == 1) $bestbrewer[$bb_row_scores['uid']]['Name'] = $bb_row_scores['brewerBreweryName'];
				if ($_SESSION['prefsProEdition'] == 0) {
					$bestbrewer[$bb_row_scores['uid']]['Name'] = $bb_row_scores['brewerFirstName']." ".$bb_row_scores['brewerLastName'];
				}

				if ($_SESSION['prefsProEdition'] == 0) $bestbrewer[$bb_row_scores['uid']]['Clubs'] = $bb_row_scores['brewerClubs'];
				$bestbrewer[$bb_row_scores['uid']]['Places'] = array(0,0,0,0,0);
				$bestbrewer[$bb_row_scores['uid']]['Scores'] = array();
				$bestbrewer[$bb_row_scores['uid']]['TypeBOS'] = array();

				if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer[$bb_row_scores['uid']]['Places'][$place-1] = 1;
				$bestbrewer[$bb_row_scores['uid']]['Scores'][0] = $bb_row_scores['scoreEntry'];

				// -- Compile separate vars for clubs --
				if (!empty($bb_row_scores['brewerClubs'])) {

					if (array_key_exists($club_name, $bestbrewer_clubs)) {
						if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places'][$place-1] += 1;
						$bestbrewer_clubs[$club_name]['Scores'][] = $bb_row_scores['scoreEntry'];
					}

					else {
						$bestbrewer_clubs[$club_name]['Places'] = array(0,0,0,0,0);
						if (($place == $bb_row_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places'][$place-1] = 1;
						$bestbrewer_clubs[$club_name]['Scores'] = array();
						$bestbrewer_clubs[$club_name]['Scores'][] = $bb_row_scores['scoreEntry'];
						$bestbrewer_clubs[$club_name]['Clubs'] = $bb_row_scores['brewerClubs'];
					}

				} // -- end clubs --

			}

		} while ($bb_row_scores = mysqli_fetch_assoc($bb_scores));

		// BOS - do calcs only if pref is true
		if ($row_bb_prefs['prefsBestUseBOS'] == 1) {

			do {

				$club_name = normalizeClubs($bb_row_bos_scores['brewerClubs']);

				if (array_key_exists($bb_row_bos_scores['uid'], $bestbrewer)) {
					
					$place = floor($bb_row_bos_scores['scorePlace']);
					if (($place == $bb_row_bos_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer[$bb_row_bos_scores['uid']]['Places'][$place-1] += 1;
					$bestbrewer[$bb_row_bos_scores['uid']]['Scores'][] = $bb_row_bos_scores['scoreEntry'];
					$bestbrewer[$bb_row_bos_scores['uid']]['TypeBOS'][] += 1;

					// -- Compile separate vars for clubs --
					if (!empty($bb_row_bos_scores['brewerClubs'])) {

						if (array_key_exists($club_name, $bestbrewer_clubs)) {
							if (($place == $bb_row_bos_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places'][$place-1] += 1;
							$bestbrewer_clubs[$club_name]['Scores'][] = $bb_row_bos_scores['scoreEntry'];
						}

						else {
							$bestbrewer_clubs[$club_name]['Places'] = array(0,0,0,0,0);
							if (($place == $bb_row_bos_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places'][$place-1] = 1;
							$bestbrewer_clubs[$club_name]['Scores'] = array();
							$bestbrewer_clubs[$club_name]['Scores'][] = $bb_row_bos_scores['scoreEntry'];
							$bestbrewer_clubs[$club_name]['Clubs'] = $bb_row_bos_scores['brewerClubs'];
						}

					}
					// -- end clubs --
				}

				else {
					$bestbrewer[$bb_row_bos_scores['uid']]['Places'] = array(0,0,0,0,0);
					$bestbrewer[$bb_row_bos_scores['uid']]['TypeBOS'] = array();
					$bestbrewer[$bb_row_bos_scores['uid']]['Scores'] = array();

					$place = floor($bb_row_bos_scores['scorePlace']);
					if (($place == $bb_row_bos_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer[$bb_row_bos_scores['uid']]['Places'][$place-1] = 1;
					$bestbrewer[$bb_row_bos_scores['uid']]['Scores'][0] = $bb_row_bos_scores['scoreEntry'];
					$bestbrewer[$bb_row_bos_scores['uid']]['TypeBOS'][0] = 1;

					// -- Compile separate vars for clubs --
					if (!empty($bb_row_bos_scores['brewerClubs'])) {

						if (array_key_exists($club_name, $bestbrewer_clubs)) {
							if (($place == $bb_row_bos_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places'][$place-1] += 1;
							$bestbrewer_clubs[$club_name]['Scores'][] = $bb_row_bos_scores['scoreEntry'];
						}

						else {
							$bestbrewer_clubs[$club_name]['Places'] = array(0,0,0,0,0);
							if (($place == $bb_row_bos_scores['scorePlace']) && ($place >= 1) && ($place <= 5)) $bestbrewer_clubs[$club_name]['Places'][$place-1] = 1;
							$bestbrewer_clubs[$club_name]['Scores'] = array();
							$bestbrewer_clubs[$club_name]['Scores'][] = $bb_row_bos_scores['scoreEntry'];
							$bestbrewer_clubs[$club_name]['Clubs'] = $bb_row_bos_scores['brewerClubs'];
						}

					}
					// -- end clubs --

				}

			} while ($bb_row_bos_scores = mysqli_fetch_assoc($bb_bos_scores));

		}

		if ($row_limits['prefsShowBestBrewer'] != 0) {

			foreach (array_keys($bestbrewer) as $key) {
				$points = best_brewer_points($key,$bestbrewer[$key]['Places'],$bestbrewer[$key]['Scores'],$bb_points_prefs,$bb_tiebreaker_prefs);
				$bestbrewer[$key]['Points'] = $points;
				$bb_sorter[$key] = $points;
			}

			arsort($bb_sorter);

			$show_4th = FALSE;
			$show_HM = FALSE;
			
			if ($row_limits['prefsShowBestBrewer'] == -1) $bb_max_position = count(array_keys($bb_sorter));
			else $bb_max_position = $row_limits['prefsShowBestBrewer'];

			foreach (array_keys($bb_sorter) as $key) {
				$bb_count += 1;
				$points = $bestbrewer[$key]['Points'];
				if ($points != $bb_previouspoints) {
					$bb_position = $bb_count;
					$bb_previouspoints = $points;
				}
				if ($bb_position <= $bb_max_position) {
					if ($bestbrewer[$key]['Places'][3] > 0) $show_4th = TRUE;
					if ($bestbrewer[$key]['Places'][4] > 0) $show_HM = TRUE;
				}
				else break;
			}

			$bb_count = 0;
			$bb_position = 0;
			$bb_previouspoints = 0;

			foreach (array_keys($bb_sorter) as $key) {
				
				$bb_count += 1;
				$points = $bestbrewer[$key]['Points'];
				
				if ($points != $bb_previouspoints) {
					$bb_position = $bb_count;
					$bb_previouspoints = $points;
					$bb_display_position = display_place($bb_position,3);
					$place_heirarchy = place_heirarchy($bb_count);
				}

				else $bb_display_position = "";
				
				if ($bb_position <= $bb_max_position) {
					$table_body1 .= "<tr class=\"fragment\" data-fragment-index=\"".$place_heirarchy."\" >";
					$table_body1 .= "<td class=\"no-bottom-border\" width=\"1%\" nowrap><a name=\"".$points."\"></a>".$bb_display_position."</td>";
					$table_body1 .= "<td class=\"no-bottom-border\" width=\"25%\">".$bestbrewer[$key]['Name'];
					if (array_sum($bestbrewer[$key]['TypeBOS']) > 0) $table_body1 .= sprintf("<small><em><br>** %s %s **</em></small>",$label_bos,$label_participant);
					$table_body1 .= "</td>";
					$table_body1 .= "<td class=\"no-bottom-border\" width=\"5%\" nowrap>".$bestbrewer[$key]['Places'][0]."</td>";
					$table_body1 .= "<td class=\"no-bottom-border\" width=\"5%\" nowrap>".$bestbrewer[$key]['Places'][1]."</td>";
					$table_body1 .= "<td class=\"no-bottom-border\" width=\"5%\" nowrap>".$bestbrewer[$key]['Places'][2]."</td>";
					if ($show_4th) $table_body1 .= "<td class=\"no-bottom-border\" width=\"5%\" nowrap>".$bestbrewer[$key]['Places'][3]."</td>";
					if ($show_HM) $table_body1 .= "<td class=\"no-bottom-border\" width=\"5%\" nowrap>".$bestbrewer[$key]['Places'][4]."</td>";
					$table_body1 .= "<td align=\"right\" class=\"no-bottom-border\" width=\"5%\" nowrap>";
					$table_body1 .= number_format($points,6);
					$table_body1 .= "</td>";
					if ($_SESSION['prefsProEdition'] == 0) $table_body1 .= "<td class=\"no-bottom-border\">".truncate_string($bestbrewer[$key]['Clubs'],20," ")."</td>";
					$table_body1 .= "</tr>";
				}

				else break;
			}

			// Build best brewer table headers
			$table_head1 .= "<tr>";
			$table_head1 .= sprintf("<th nowrap>%s</th>",$label_place);
			$table_head1 .= sprintf("<th>%s</th>",$label_brewer);
			$table_head1 .= "<th>".addOrdinalNumberSuffix(1)."</th>";
			$table_head1 .= "<th>".addOrdinalNumberSuffix(2)."</th>";
			$table_head1 .= "<th>".addOrdinalNumberSuffix(3)."</th>";
			if ($show_4th) $table_head1 .= "<th>".addOrdinalNumberSuffix(4)."</th>";
			if ($show_HM) $table_head1 .= sprintf("<th>%s</th>",$best_brewer_text_001);
			$table_head1 .= sprintf("<th nowrap>%s</th>",$label_score);
			if ($_SESSION['prefsProEdition'] == 0) $table_head1 .= sprintf("<th>%s</th>",$label_club);

			// Display
			$slides_bos .= "<section>";
			
			$slides_bos .= "<h1 class=\"r-fit-text tight\">".$row_bb_prefs['prefsBestBrewerTitle']."</h1>";

			$slides_bos .= "<p class=\"entry-count\">";
			$slides_bos .= sprintf(" %s %s <span class=\"small entry-count\">[<a data-fancybox data-src=\"#scoring-method\" href=\"javascript:;\">%s</a>]</span>", get_participant_count('received-entrant'), ucwords($best_brewer_text_000), $best_brewer_text_003);
			$slides_bos .= "</p>";

			$slides_bos .= "<table style=\"width: 100%; font-size: .55em;\">";
			$slides_bos .= "<thead>";
			$slides_bos .= $table_head1;
			$slides_bos .= "</thead>";
			$slides_bos .= "<tbody>";
			$slides_bos .= $table_body1;
			$slides_bos .= "</tbody>";
			$slides_bos .= "</table>";
			
			$slides_bos .= "</section>\n";

		} // end if ($row_limits['prefsShowBestBrewer'] != 0)

		if (($_SESSION['prefsProEdition'] == 0) && ($row_limits['prefsShowBestClub'] != 0)) {

			// Compile the Best Club points
			foreach (array_keys($bestbrewer_clubs) as $key) {
				$points_clubs = best_brewer_points($key,$bestbrewer_clubs[$key]['Places'],$bestbrewer_clubs[$key]['Scores'],$bb_points_prefs,$bb_tiebreaker_prefs);
				$bestbrewer_clubs[$key]['Points'] = $points_clubs;
				$bb_sorter_clubs[$key] = $points_clubs;
			}

			arsort($bb_sorter_clubs);

			$show_4th_clubs = FALSE;
			$show_HM_clubs = FALSE;
			$bb_count_clubs = 0;
			$bb_position_clubs = 0;
			$bb_previouspoints_clubs = 0;
			if ($row_limits['prefsShowBestClub'] == -1) $bb_max_position_clubs = count(array_keys($bb_sorter_clubs));
			else $bb_max_position_clubs = $row_limits['prefsShowBestClub'];

			foreach (array_keys($bb_sorter_clubs) as $key) {
				$bb_count_clubs += 1;
				$points_clubs = $bestbrewer_clubs[$key]['Points'];
				if ($points_clubs != $bb_previouspoints_clubs) {
					$bb_position_clubs = $bb_count_clubs;
					$bb_previouspoints_clubs = $points_clubs;
				}
				if ($bb_position_clubs <= $bb_max_position_clubs) {
					if ($bestbrewer_clubs[$key]['Places'][3] > 0) $show_4th_clubs = TRUE;
					if ($bestbrewer_clubs[$key]['Places'][4] > 0) $show_HM_clubs = TRUE;
				}

				else break;
			}

			// Build clubs table body
			$bb_count_clubs = 0;
			$bb_position_clubs = 0;
			$bb_previouspoints_clubs = 0;

			foreach (array_keys($bb_sorter_clubs) as $key) {

				$points_clubs = $bestbrewer_clubs[$key]['Points'];
				$output .= $bestbrewer_clubs[$key]['Clubs']." - ".$points_clubs. " - Places... ";
				$output .= "1: ".$bestbrewer_clubs[$key]['Places'][0]." ";
				$output .= "2: ".$bestbrewer_clubs[$key]['Places'][1]." ";
				$output .= "3: ".$bestbrewer_clubs[$key]['Places'][2]." ";
				if ($show_4th_clubs) $output .= "4: ".$bestbrewer_clubs[$key]['Places'][3]." ";
				if ($show_HM_clubs) $output .= "HM: ".$bestbrewer_clubs[$key]['Places'][4]." ";
				$output .= "<br>";

				$bb_count_clubs += 1;
				$points_clubs = $bestbrewer_clubs[$key]['Points'];

				if ($points_clubs != $bb_previouspoints_clubs) {
					$bb_position_clubs = $bb_count_clubs;
					$bb_previouspoints_clubs = $points_clubs;
					$bb_display_position_clubs = display_place($bb_position_clubs,3);
					$place_heirarchy = place_heirarchy($bb_count_clubs);
				}

				else $bb_display_position_clubs = "";

				if ($bb_position_clubs <= $bb_max_position_clubs) {

					// Build club points table body
					$table_body2 .= "<tr class=\"fragment\" data-fragment-index=\"".$place_heirarchy."\" >";
					$table_body2 .= "<td class=\"no-bottom-border\" width=\"1%\" nowrap><a name=\"club-".$points_clubs."\"></a>".$bb_display_position_clubs."</td>";
					$table_body2 .= "<td class=\"no-bottom-border\">".$bestbrewer_clubs[$key]['Clubs']."</td>";
					$table_body2 .= "<td class=\"no-bottom-border\" width=\"10%\" nowrap>".$bestbrewer_clubs[$key]['Places'][0]."</td>";
					$table_body2 .= "<td class=\"no-bottom-border\" width=\"10%\" nowrap>".$bestbrewer_clubs[$key]['Places'][1]."</td>";
					$table_body2 .= "<td class=\"no-bottom-border\" width=\"10%\" nowrap>".$bestbrewer_clubs[$key]['Places'][2]."</td>";
					if ($show_4th_clubs) $table_body2 .= "<td class=\"no-bottom-border\" width=\"10%\" nowrap>".$bestbrewer_clubs[$key]['Places'][3]."</td>";
					if ($show_HM_clubs) $table_body2 .= "<td class=\"no-bottom-border\" width=\"10%\" nowrap>".$bestbrewer_clubs[$key]['Places'][4]."</td>";
					$table_body2 .= "<td align=\"right\" class=\"no-bottom-border\" width=\"1%\" nowrap>";
					$table_body2 .= number_format($points_clubs,6);
					$table_body2 .= "</td>";
					$table_body2 .= "</tr>";

				}

				else break;
			}

			// Clubs table headers
			$table_head2 .= "<tr>";
			$table_head2 .= sprintf("<th nowrap>%s</th>",$label_place);
			$table_head2 .= sprintf("<th>%s</th>",$label_club);
			$table_head2 .= "<th>".addOrdinalNumberSuffix(1)."</th>";
			$table_head2 .= "<th>".addOrdinalNumberSuffix(2)."</th>";
			$table_head2 .= "<th>".addOrdinalNumberSuffix(3)."</th>";
			if ($show_4th_clubs) $table_head2 .= "<th>".addOrdinalNumberSuffix(4)."</th>";
			if ($show_HM_clubs) $table_head2 .= sprintf("<th>%s</th>",$best_brewer_text_001);
			$table_head2 .= sprintf("<th nowrap>%s</th>",$label_score);
			$table_head2 .= "</tr>";

			// Display
			$slides_bos .= "<section>";
			
			$slides_bos .= "<h1 class=\"r-fit-text tight\">".$row_bb_prefs['prefsBestClubTitle']."</h1>";

			$slides_bos .= "<p class=\"entry-count\">";
			$slides_bos .= sprintf(" %s %s <span class=\"small\">[<a data-fancybox data-src=\"#scoring-method\" href=\"javascript:;\">%s</a>]</span>", get_participant_count('received-club'), ucwords($best_brewer_text_014), $best_brewer_text_003);
			$slides_bos .= "</p>";

			$slides_bos .= "<table style=\"width: 100%; font-size: .55em;\">";
			$slides_bos .= "<thead>";
			$slides_bos .= $table_head2;
			$slides_bos .= "</thead>";
			$slides_bos .= "<tbody>";
			$slides_bos .= $table_body2;
			$slides_bos .= "</tbody>";
			$slides_bos .= "</table>";

			$slides_bos .= "</section>\n";

		} // end if (($_SESSION['prefsProEdition'] == 0) && ($row_limits['prefsShowBestClub'] != 0))

		$slides_bos .= "<div style=\"display: none; height: 75%; width: 75%;\" class=\"fancy\" id=\"scoring-method\">";
		$slides_bos .= "<h2 class=\"fancy-h2\">".$best_brewer_text_003."</h2>";
		$slides_bos .= "<p class=\"bold-text\">".$best_brewer_text_004."</p>";
		$slides_bos .= "<ul class=\"fancy-list\">";
		$slides_bos .= "<li>".addOrdinalNumberSuffix(1)." ".$label_place.": ".$row_bb_prefs['prefsFirstPlacePts']."</li>";
		$slides_bos .= "<li>".addOrdinalNumberSuffix(2)." ".$label_place.": ".$row_bb_prefs['prefsSecondPlacePts']."</li>";
		$slides_bos .= "<li>".addOrdinalNumberSuffix(3)." ".$label_place.": ".$row_bb_prefs['prefsThirdPlacePts']."</li>";
		if ($row_bb_prefs['prefsFourthPlacePts'] > 0) $slides_bos .= "<li>".addOrdinalNumberSuffix(4)." ".$label_place.": ".$row_bb_prefs['prefsFourthPlacePts']."</li>";
		if ($row_bb_prefs['prefsHMPts'] > 0) $slides_bos .= "<li>".$best_brewer_text_001.": ".$row_bb_prefs['prefsHMPts']."</li>";
		$slides_bos .= "</ul>";
		if (!empty($row_bb_prefs['prefsTieBreakRule1'])) {
			$slides_bos .= "<p class=\"bold-text\">".$best_brewer_text_005."</p>";
			$slides_bos .= "<ol class=\"fancy-list\">";
			$slides_bos .= "<li>".tiebreak_rule($row_bb_prefs['prefsTieBreakRule1'])."</li>";
			if (!empty($row_bb_prefs['prefsTieBreakRule2'])) $slides_bos .= "<li>".tiebreak_rule($row_bb_prefs['prefsTieBreakRule2'])."</li>";
			if (!empty($row_bb_prefs['prefsTieBreakRule3'])) $slides_bos .= "<li>".tiebreak_rule($row_bb_prefs['prefsTieBreakRule3'])."</li>";
			if (!empty($row_bb_prefs['prefsTieBreakRule4'])) $slides_bos .= "<li>".tiebreak_rule($row_bb_prefs['prefsTieBreakRule4'])."</li>";
			if (!empty($row_bb_prefs['prefsTieBreakRule5'])) $slides_bos .= "<li>".tiebreak_rule($row_bb_prefs['prefsTieBreakRule5'])."</li>";
			if (!empty($row_bb_prefs['prefsTieBreakRule6'])) $slides_bos .= "<li>".tiebreak_rule($row_bb_prefs['prefsTieBreakRule6'])."</li>";
			$slides_bos .= "</ol>";
		}
		$slides_bos .= "</div>";

	} // end if (($row_limits['prefsShowBestBrewer'] != 0) || ($row_limits['prefsShowBestClub'] != 0))
?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<title><?php echo $_SESSION['contestName']. " - " . $label_awards; ?></title>
		<!-- Load reveal.js styles / https://revealjs.com -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.1.0/reset.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.1.0/reveal.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.1.0/theme/<?php echo $reveal_theme[$view]; ?>" id="theme">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.1.0/theme/fonts/league-gothic/league-gothic.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.1.0/theme/fonts/source-sans-pro/source-sans-pro.min.css">
		<!-- Load Font Awesome / https://fortawesome.github.io/Font-Awesome -->
    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightslider/1.1.6/css/lightslider.min.css">
    	<!-- Load Fancybox / http://www.fancyapps.com -->
	    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />
    	<link rel="stylesheet" href="<?php echo $base_url; ?>css/awards.css">
    	<style>
    		.cs-hidden {
		        height: 1px;
		        opacity: 0;
		        filter: alpha(opacity=0);
		        overflow: hidden;
		    }

		    .sponsor-text {
		    	font-size: .4em;
		    }

		    .fancy {
		    	font-family: 'Source Sans Pro';
		    	font-size: 18px;
		    }

		    .fancy p {
		    	margin: 10px 0 5px 0;
		    	padding: 10px 0 5px 0;
		    }

		    .fancy-h2 {
		    	font-size: 3em;
		    	margin: 0 0 10px 0;
		    	padding: 0 0 10px 0;
		    	font-weight: bolder;
		    }

		    .fancy-list {
		    	margin-left: 35px;
		    }

		    .fancy-list li {
		    	margin-bottom: 5px;
		    	padding-bottom: 5px;
		    }

		    .fancy .bold-text {
		    	font-weight: bold;
		    }
    	</style>
	</head>
	<body>
		<noscript><?php echo $alert_text_087; ?></noscript>
		<div class="reveal">
			<div class="slides">
				
				<!-- Title Slide -->
				<section>
					<h1 style="margin:0;padding:0" class="r-fit-text"><?php echo $_SESSION['contestName']; ?></h1>
					<h2 style="margin:0;padding:0"><?php echo $label_awards; ?></h2>
					<?php if ((!empty($_SESSION['contestLogo'])) && (file_exists(USER_IMAGES.$_SESSION['contestLogo']))) { ?>
						<div class="logo-image">
							<img src="<?php echo $base_url."user_images/".$_SESSION['contestLogo']; ?>">
						</div>
					<?php } ?>
				</section>
				
				<?php if ($_SESSION['prefsSponsorLogos'] == "Y") { ?>
				<!-- Sponsor Carousel Slide -->	
				<section>
					<h1 style="margin:0;padding:0" class="r-fit-text"><?php echo $label_sponsors; ?></h1>
					    <ul id="sponsor-slider">
					   	<?php do { 
					   	if ($row_sponsors['sponsorEnable'] == "1") {
					   	if ((!empty($row_sponsors['sponsorImage'])) && (file_exists(USER_IMAGES.$row_sponsors['sponsorImage']))) { ?>
					   		<li data-thumb="<?php echo $base_url."user_images/".$row_sponsors['sponsorImage']; ?>"><img src="<?php echo $base_url."user_images/".$row_sponsors['sponsorImage']; ?>" height="200" alt="<?php echo $row_sponsors['sponsorName']; ?>"></li>
					   	<?php 
					   			} 
					   		} 
					   	} while ($row_sponsors = mysqli_fetch_assoc($sponsors)); ?>
					    </ul>
				</section>
				<?php } ?>
				
				<?php if (!empty($judge_list)) { ?>
				<!-- Judge List Slide -->
				<section>
					<h1 style="margin:0;padding:0"><?php echo $label_judges; ?></h1>
					<p><small><?php echo rtrim($judge_list, ", "); ?></small></p>
					<?php if (!empty($judge_bos)) { ?>
					<h3 style="margin:0;padding:0"><?php echo $label_judges." - ".$label_bos; ?></h3>
					<p><small><?php echo rtrim($judge_bos, ", "); ?></small></p>
					<?php } ?>
				</section>
				<?php } ?>
				
				<?php if (!empty($steward_list)) { ?>
				<!-- Steward List Slide -->
				<section>
					<h1 style="margin:0;padding:0"><?php echo $label_stewards; ?></h1>
					<p><small><?php echo rtrim($steward_list, ", "); ?></small></p>
				</section>
				<?php } ?>
				
				<?php if ((!empty($staff_list)) || (!empty($staff_organizer))) { ?>
				<!-- Staff List Slide -->
				<section>
					<h1 style="margin:0;padding:0"><?php echo $label_staff; ?></h1>
					<?php if (!empty($staff_list)) { ?>
					<p><small><?php echo rtrim($staff_list, ", "); ?></small></p>
					<?php } ?>
					<?php if (!empty($staff_organizer)) { ?>
					<h2 style="margin:0;padding:0"><?php echo $label_organizer; ?></h2>
					<p><small><?php echo rtrim($staff_organizer, ", "); ?></small></p>
					<?php } ?>
				</section>
				<?php } ?>

				<!-- Statistic Slide -->
				<?php 
				$entries_count = get_entry_count('paid-received');
				$entrant_count = get_participant_count('received-entrant');
				$judges_count = get_participant_count('judge-assigned');
				$steward_count = get_participant_count('steward-assigned');
				$staff_count = get_participant_count('staff-assigned');
				$placing_entry_count = get_entry_count('placing-entries');
				?>
				<section>
					<h1 style="margin:0;padding:0"><?php echo $label_by_the_numbers; ?></h1>
					<p>
						<?php if ($entries_count > 0) { ?>
						<span style="margin-right: 15px;" class="fragment" data-fragment-index="1"><i class="fa fa-beer"></i> <?php echo $entries_count." ".$label_entries; ?></span>
						<?php } ?>
						<?php if ($entrant_count > 0) { ?>
						<span class="fragment" data-fragment-index="1"><i class="fa fa-user"></i> <?php echo $entrant_count." ".$label_entrants; ?></span>
						<?php } ?>
					</p>
					<p>
						<?php if ($judges_count > 0) { ?>
						<span style="margin-right: 15px;" class="fragment" data-fragment-index="2"><i class="fa fa-gavel"></i> <?php echo $judges_count." ".$label_judges; ?></span>
						<?php } ?>
						<?php if ($steward_count > 0) { ?>
						<span style="margin-right: 15px;" class="fragment" data-fragment-index="2"><i class="fa fa-pencil"></i> <?php echo $steward_count." ".$label_stewards; ?></span>
						<?php } ?>
						<?php if ($staff_count > 0) { ?>
						<span class="fragment" data-fragment-index="2"><i class="fa fa-user-circle"></i> <?php echo $staff_count." ".$label_staff; ?></span>
						<?php } ?>
					</p>
					<?php if ($placing_entry_count > 0) { ?>
					<p>
						<span style="margin-right: 15px;" class="fragment" data-fragment-index="3"><i class="fa fa-trophy"></i> <?php echo $placing_entry_count." ".$label_placing_entries;  ?></span>
					</p>
					<?php } ?>
					<?php if ((!empty($_SESSION['contestLogo'])) && (file_exists(USER_IMAGES.$_SESSION['contestLogo']))) { ?>
						<div class="logo-image">
							<img style="max-height: 225px;" src="<?php echo $base_url."user_images/".$_SESSION['contestLogo']; ?>">
						</div>
					<?php } ?>
				</section>
				
				<!-- Table/Category/Sub-Cat Medal Slide Sections -->
				<?php 
				if (!empty($slides)) echo $slides; 
				if (!empty($slides_bos)) echo $slides_bos;
				?>
				<!-- End Slide -->
				<section>
					<h2 style="margin:0;padding:0" class="r-fit-text"><?php echo $label_thank_you; ?></h2>
					<h3 style="margin:0;padding:0"><?php echo $label_congrats_winners; ?></h3>
					<?php if ((!empty($_SESSION['contestLogo'])) && (file_exists(USER_IMAGES.$_SESSION['contestLogo']))) { ?>
						<div class="logo-image">
							<img src="<?php echo $base_url."user_images/".$_SESSION['contestLogo']; ?>">
						</div>
					<?php } ?>
				</section>
			</div>	
			<div class="footer"><?php echo $_SESSION['contestName']." - ".$label_awards." - ".$current_date_display; ?></div>
		</div>
		<!-- Load reveal.js and associated plug-ins / https://revealjs.com -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.1.0/reveal.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.1.0/plugin/notes/notes.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/4.1.0/plugin/notes/plugin.min.js"></script>
		<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/lightslider/1.1.6/js/lightslider.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
		<script>
			Reveal.initialize({
				hash: true,
				plugins: [ RevealNotes ]
			});
			$(document).ready(function() {
				$("#sponsor-slider").lightSlider({
					gallery: true,
					auto: true,
					item: 4,
			        autoWidth: false,
			        loop: true,
			        keyPress: false,
			        thumbItem: 25,
			        easing: 'cubic-bezier(0.25, 0, 0.25, 1)',
			    });
			  });
		</script>
	</body>
</html>
<?php } // end if (($logged_in) && ($_SESSION['userLevel'] <= 1)) ?>