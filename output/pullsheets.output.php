<?php

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] > 1))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

require (DB.'admin_common.db.php');
include (LIB.'output.lib.php');
include (DB.'output_pullsheets.db.php');
include (INCLUDES.'scrubber.inc.php');

$queued = FALSE;
$tables_none = FALSE;
$tables_all = FALSE;

if ($_SESSION['jPrefsQueued'] == "Y") $queued = TRUE;
if (($go == "judging_tables") && ($totalRows_tables == 0)) $tables_none = TRUE;
if ((($go == "judging_tables") || ($go == "judging_locations") || ($go == "all_entry_info")) && ($id == "default")) $tables_all = TRUE;

$table_flight_thead = "";
$pullsheet_output = "";

if ($go == "judging_scores_bos") {
	$table_flight_thead .= "<tr>";
	$table_flight_thead .= "<th width=\"5%\" nowrap>".$label_pull_order."</th>";
	$table_flight_thead .= "<th width=\"5%\">#</th>";
	$table_flight_thead .= "<th width=\"5%\">".$label_table." ".$label_place."</th>";
	$table_flight_thead .= "<th width=\"20%\">".$label_style."</th>";
	$table_flight_thead .= "<th>".$label_info."</th>";
	$table_flight_thead .= "<th width=\"5%\" nowrap>".$label_box."</th>";
	$table_flight_thead .= "<th width=\"5%\" nowrap>".$label_score."</th>";
	$table_flight_thead .= "<th width=\"5%\" nowrap>".$label_bos."<br>".$label_place."</th>";
	$table_flight_thead .= "</tr>";
}

else {
	if ($go != "all_entry_info") {
		$table_flight_thead .= "<tr>";
		$table_flight_thead .= "<th width=\"5%\" nowrap>".$label_pull_order."</th>";
		$table_flight_thead .= "<th width=\"5%\">#</th>";
		$table_flight_thead .= "<th width=\"35%\">".$label_style."</th>";
		$table_flight_thead .= "<th width=\"35%\">".$label_info."</th>";
		$table_flight_thead .= "<th width=\"5%\" nowrap>".$label_box."</th>";
		if (($go != "judging_scores_bos") && ($go != "mini_bos") && ($filter != "mini_bos")) $table_flight_thead .= "<th width=\"5%\" nowrap>".$label_mini_bos."</th>";
		$table_flight_thead .= "<th width=\"5%\" nowrap>".$label_score."</th>";
		$table_flight_thead .= "<th width=\"5%\" nowrap>".$label_place."</th>";
		$table_flight_thead .= "</tr>";
	}
}

if ($go == "all_entry_info") {

	$show_table = FALSE;

	$table_flight = "";
	$table_flight_thead = "";
	$pullsheet_output = "";
	$round_count = array();

	$table_flight_thead .= "<tr>";
	$table_flight_thead .= "<th width=\"5%\">#</th>";
	$table_flight_thead .= "<th width=\"15%\">".$label_style."</th>";
	$table_flight_thead .= "<th width=\"15%\">".$label_required_info."</th>";
	$table_flight_thead .= "<th width=\"15%\">".$label_optional_info."</th>";
	$table_flight_thead .= "<th width=\"15%\">".$label_brewer_specifics."</th>";
	$table_flight_thead .= "<th width=\"15%\">".$label_possible_allergens."</th>";
	$table_flight_thead .= "<th>".$label_notes."</th>";
	$table_flight_thead .= "</tr>";

	if ($view == "judge_inventory") {

		/**
		 * Sort by individual judge.
		 * Loop through the judging_assignments DB table,
		 * grab the table info and associated entries
		 */

		include (DB.'output_assignments.db.php');

		$judge_inventory = array();

		if ($row_assignments) {
		
			do {

				$show_table = FALSE;
				$judge_info = judge_info($row_assignments['bid']);
				$judge_info = explode("^",$judge_info);

				$table_info = get_table_info(1,"basic",$row_assignments['assignTable'],$dbTable,"default");
				$table_info = explode("^",$table_info);

				if (!isset($table_info[0])) $table_info[0] = "";
				if (!isset($table_info[1])) $table_info[1] = "";
				if (!isset($table_info[2])) $table_info[2] = "";
				if (!isset($table_info[3])) $table_info[3] = "";
				if (!isset($table_info[4])) $table_info[4] = "";
				
				$table_flight = "";
				$table_flight_datatables = "";
				$table_flight_tbody = "";
				$table_info_location = "";
				$table_info_notes = "";
				$table_info_header = "";
				$judge_inventory_output = "";
				$judge_roles = "";
				$random_sortable = random_generator(7,2);

				if ($location == "default") $show_table = TRUE;
				if ((isset($table_info[2])) && (($location != "default") && ($location == $table_info[2]))) $show_table = TRUE;

				if ($show_table) {

					if (!empty($table_info[4])) {

						$a = explode(",", $table_info[4]);

						$table_entry_count = 0;
						$judge_entry_count = 0;
						
						foreach (array_unique($a) as $value) {
							
							include (DB.'output_pullsheets_entries.db.php');

							if ($row_entries) {

								$style = style_number_const($row_entries['brewCategorySort'],$row_entries['brewSubCategory'],$_SESSION['style_set_display_separator'],0);
								$style_special = $row_entries['brewCategorySort']."^".$row_entries['brewSubCategory']."^".$_SESSION['prefsStyleSet'];

								do {

									if (!empty($row_entries['brewCategorySort'])) {

										$display_entry = TRUE;

										if ($_SESSION['jPrefsQueued'] == "N") {
											$ji_flight_num = check_flight_number($row_entries['id'],1,1);
											if ($ji_flight_num != $row_assignments['assignFlight']) $display_entry = FALSE;
											if ($ji_flight_num == $row_assignments['assignFlight']) $judge_entry_count += 1;
										}

										if ($display_entry) {

											$table_entry_count += 1;

											$table_flight_tbody .= "<tr>";

											$table_flight_tbody .= "<td nowrap>";
											if ($sort == "entry") $table_flight_tbody .= sprintf("%06s",$row_entries['id']);
											else $table_flight_tbody .= sprintf("%06s",$row_entries['brewJudgingNumber']);
											$table_flight_tbody .= "</td>";

											$table_flight_tbody .= "<td>";
											if ($_SESSION['prefsStyleSet'] == "BA") $table_flight_tbody .= $row_entries['brewStyle'];
											else $table_flight_tbody .= $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1,$base_url)."</em>";
											$table_flight_tbody .= "</td>";

											$special = style_convert($style_special,"9",$base_url);
											$special = explode("^",$special);

											$table_flight_tbody .= "<td>";
											if ((!empty($row_entries['brewInfo'])) && ($special[4] == "1")) $table_flight_tbody .= "<p>".str_replace("^","<br>",$row_entries['brewInfo'])."</p>";
											$table_flight_tbody .= "<p>";
											if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<strong>".$label_carbonation.": </strong> ".$row_entries['brewMead1']."<br>";
											if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<strong>".$label_sweetness.":</strong> ".$row_entries['brewMead2']."<br>";
											if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<strong>".$label_strength.":</strong> ".$row_entries['brewMead3']."<br>";
											if ((!empty($row_entries['brewPouring'])) && ((!empty($row_entries['brewStyleType'])) && ($row_entries['brewStyleType'] == 1))) {
												$pouring_arr = json_decode($row_entries['brewPouring'],true);
												$table_flight_tbody .= "<strong>".$label_pouring.":</strong> ".$pouring_arr['pouring']."<br>";
												if ((isset($pouring_arr['pouring_notes'])) && (!empty($pouring_arr['pouring_notes']))) $table_flight_tbody .= "<strong>".$label_pouring_notes.":</strong> ".$pouring_arr['pouring_notes']."<br>";
												$table_flight_tbody .= "<strong>".$label_rouse_yeast.":</strong> ".$pouring_arr['pouring_rouse']."<br>";
											}
											if (!empty($row_entries['brewABV'])) $table_flight_tbody .= "<strong>".$label_abv.":</strong> ".$row_entries['brewABV']."<br>";
											if (($_SESSION['prefsStyleSet'] == "NWCiderCup") && (!empty($row_entries['brewSweetnessLevel']))) $table_flight_tbody .= "<strong>".$label_final_gravity.":</strong> ".$row_entries['brewSweetnessLevel'];
											$table_flight_tbody .= "</p>";
											$table_flight_tbody .= "</td>";

											$table_flight_tbody .= "<td>";
											if (!empty($row_entries['brewInfoOptional'])) $table_flight_tbody .= "<p>".$row_entries['brewInfoOptional']."</p>";
											$table_flight_tbody .= "</td>";

											$table_flight_tbody .= "<td>";
											if (!empty($row_entries['brewComments'])) $table_flight_tbody .= "<p>".$row_entries['brewComments']."</p>";
											$table_flight_tbody .= "</td>";

											$table_flight_tbody .= "<td>";
											if (!empty($row_entries['brewPossAllergens'])) $table_flight_tbody .= "<p>".$row_entries['brewPossAllergens']."</p>";
											$table_flight_tbody .= "</td>";

											$table_flight_tbody .= "<td>";
											if (!empty($row_entries['brewStaffNotes'])) $table_flight_tbody .= "<p>".$row_entries['brewStaffNotes']."</p>";
											$table_flight_tbody .= "</td>";

											$table_flight_tbody .= "</tr>";

										}

									}

								} while ($row_entries = mysqli_fetch_assoc($entries));

							}

						}

					}

					$table_info_header .= "<h2>";
					$table_info_header .= sprintf("%s %s: %s",$label_table,$table_info[0],$table_info[1]);
					if (!empty($row_assignments['assignRoles'])) {
						$table_info_header .= "<small>";
						$table_info_header .= "<em>";
						if (strpos($row_assignments['assignRoles'],"HJ") !== FALSE) $table_info_header .= "<span style=\"margin-left:1.5em;\">Head Judge</span>";
						if (strpos($row_assignments['assignRoles'],"MBOS") !== FALSE) $table_info_header .= "<span style=\"margin-left:1em;\">Mini-BOS</span>";
						$table_info_header .= "</em>";
						$table_info_header .= "</small>";
					}
					$table_info_header .= "</h2>";
					
					if (!empty($table_flight_tbody)) {

						$table_flight .= $table_info_header;
						$table_flight .= "<p class=\"lead\">";
						if ($_SESSION['jPrefsQueued'] == "N") {
							$table_flight .= sprintf("%s %s, %s %s <small style=\"margin-left:1em;\">%s %s</small>",$label_flight,$row_assignments['assignFlight'],$label_round,$row_assignments['assignRound'],$judge_entry_count,$label_entries_to_judge);
						}
						else $table_flight .= $table_entry_count." ".$label_entries;
						$table_flight .= "</p>";
						
						$table_flight_datatables .= "<script>";
						$table_flight_datatables .= "$(document).ready(function() {";
						$table_flight_datatables .= "$('#sortable".$random_sortable."').dataTable( {";
						$table_flight_datatables .= "\"bPaginate\" : false,";
						$table_flight_datatables .= "\"sDom\": 'rt',";
						$table_flight_datatables .= "\"bStateSave\" : false,";
						$table_flight_datatables .= "\"bLengthChange\" : false,";
						$table_flight_datatables .= "\"aaSorting\": [[1,'asc'],[0,'asc']],";
						$table_flight_datatables .= "\"bProcessing\" : false,";
						$table_flight_datatables .= "\"aoColumns\": [";
						$table_flight_datatables .= "{ \"asSorting\": [  ] },";
						$table_flight_datatables .= "{ \"asSorting\": [  ] },";
						$table_flight_datatables .= "{ \"asSorting\": [  ] },";
						$table_flight_datatables .= "{ \"asSorting\": [  ] },";
						$table_flight_datatables .= "{ \"asSorting\": [  ] },";
						//if ($filter != "mini_bos") $table_flight_datatables .= "{ \"asSorting\": [  ] },";
						$table_flight_datatables .= "{ \"asSorting\": [  ] },";
						$table_flight_datatables .= "{ \"asSorting\": [  ] }";
						$table_flight_datatables .= "]";
						$table_flight_datatables .= "} );";
						$table_flight_datatables .= "} );";
						$table_flight_datatables .= "</script>";

						$table_flight .= $table_flight_datatables;
						$table_flight .= "<table class=\"table table-striped table-bordered\" id=\"sortable".$random_sortable."\">";
						$table_flight .= "<thead>";
						$table_flight .= $table_flight_thead;
						$table_flight .= "</thead>";
						$table_flight .= "<tbody>";
						$table_flight .= $table_flight_tbody;
						$table_flight .= "</tbody>";
						$table_flight .= "</table>";
					}

				}

				if (!empty($table_flight)) {
					$judge_inventory_output .= $table_flight;
					$judge_inventory_output .= "<div style=\"page-break-after:always;\"></div>";
				}

				// Create a sortable array of each judge's assigned entries, grouped by table.
				$judge_inventory[] = array(
					"table-num" => $table_info[0],
					"flight" => $row_assignments['assignFlight'],
					"round" => $row_assignments['assignRound'],
					"table-id" => $row_assignments['assignTable'],
					"table-name" => $table_info[1],
					"last-name" => $judge_info[1],
					"first-name" => $judge_info[0],
					"roles" => $row_assignments['assignRoles'],
					"table-styles" => $table_info[4],
					"inventory-html" => $judge_inventory_output
				);

			} while ($row_assignments = mysqli_fetch_assoc($assignments));

			sort($judge_inventory);

			foreach ($judge_inventory as $key => $value) {
				if (!empty($value['inventory-html'])) {
					$pullsheet_output .= sprintf("<h1>Judging Inventory for %s %s</h1>",$value['first-name'],$value['last-name']);
					$pullsheet_output .= $value['inventory-html'];
				}
			}

		} // end if ($row_assignments)

		if (empty($pullsheet_output)) {
			$pullsheet_output = "<h2>No Inventories Available</h2><p class\"lead\"><strong>No inventories available for this session.</strong> Entries must be marked as received for this report to return a list. If entries are marked as received, check that judges have been assigned to tables and/or flights.</p>";
		}

	} else {

		do {

			$entry_count = get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default");
			include (DB.'output_pullsheets_queued.db.php');
			$round_count[] = $row_table_round['count'];

			$table_flight = "";
			$table_flight_datatables = "";

			if (($row_table_round['count'] >= 1) || ($round == "default")) {

				$table_info_location = "";
				$table_info_notes = "";
				$table_info_header = "";

				if ($entry_count > 0) {

					$table_flight_tbody = "";

					$a = explode(",", $row_tables['tableStyles']);

					foreach (array_unique($a) as $value) {

						include (DB.'output_pullsheets_entries.db.php');
						$style = style_number_const($row_entries['brewCategorySort'],$row_entries['brewSubCategory'],$_SESSION['style_set_display_separator'],0);
						$style_special = $row_entries['brewCategorySort']."^".$row_entries['brewSubCategory']."^".$_SESSION['prefsStyleSet'];
 
						do {

							$show_record = FALSE;

							if ((!empty($row_entries['brewPossAllergens'])) || (!empty($row_entries['brewInfo'])) || (!empty($row_entries['brewMead1'])) || (!empty($row_entries['brewMead2'])) || (!empty($row_entries['brewMead3'])) || (!empty($row_entries['brewInfoOptional'])) || (!empty($row_entries['brewComments'])) || (!empty($row_entries['brewStaffNotes']))) $show_record = TRUE;

							if ((!empty($row_entries['brewCategorySort'])) && ($show_record)) {

								$table_flight_tbody .= "<tr>";

								$table_flight_tbody .= "<td nowrap>";
								if ($view == "entry")  $table_flight_tbody .= sprintf("%06s",$row_entries['id']);
								else $table_flight_tbody .= sprintf("%06s",$row_entries['brewJudgingNumber']);
								$table_flight_tbody .= "</td>";

								$table_flight_tbody .= "<td>";
								if ($_SESSION['prefsStyleSet'] == "BA") $table_flight_tbody .= $row_entries['brewStyle'];
								else $table_flight_tbody .= $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1,$base_url)."</em>";
								$table_flight_tbody .= "</td>";

								$special = style_convert($style_special,"9",$base_url);
								$special = explode("^",$special);
								$table_flight_tbody .= "<td>";
								if ((!empty($row_entries['brewInfo'])) && ((isset($special[4])) && ($special[4] == "1"))) $table_flight_tbody .= "<p>".str_replace("^","<br>",$row_entries['brewInfo'])."</p>";
								$table_flight_tbody .= "<p>";
								if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<strong>".$label_carbonation.": </strong> ".$row_entries['brewMead1']."<br>";
								if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<strong>".$label_sweetness.":</strong> ".$row_entries['brewMead2']."<br>";
								if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<strong>".$label_strength.":</strong> ".$row_entries['brewMead3'];
								$table_flight_tbody .= "</p>";
								$table_flight_tbody .= "</td>";

								$table_flight_tbody .= "<td>";
								if (!empty($row_entries['brewInfoOptional'])) $table_flight_tbody .= "<p>".$row_entries['brewInfoOptional']."</p>";
								$table_flight_tbody .= "</td>";

								$table_flight_tbody .= "<td>";
								if (!empty($row_entries['brewComments'])) $table_flight_tbody .= "<p>".$row_entries['brewComments']."</p>";
								$table_flight_tbody .= "</td>";

								$table_flight_tbody .= "<td>";
								if (!empty($row_entries['brewPossAllergens'])) $table_flight_tbody .= "<p>".$row_entries['brewPossAllergens']."</p>";
								$table_flight_tbody .= "</td>";

								$table_flight_tbody .= "<td>";
								if (!empty($row_entries['brewStaffNotes'])) $table_flight_tbody .= "<p>".$row_entries['brewStaffNotes']."</p>";
								$table_flight_tbody .= "</td>";

								$table_flight_tbody .= "</tr>";

							}

						} while ($row_entries = mysqli_fetch_assoc($entries));

					} // end foreach

					$table_info_header .= "<div class=\"page-header\">";
					$table_info_header .= "<h1 style=\"margin-bottom: 10px; padding-bottom:10px;\">";
					$table_info_header .= sprintf("%s %s: %s <small><em>%s</em></small>",$label_table,$row_tables['tableNumber'],$row_tables['tableName'],$label_additional_info);
					$table_info_header .= "</h1>";
					$table_info_header .= "</div>";

					if ((!empty($row_tables['tableLocation'])) && ($filter != "mini_bos")) {
						$table_info_location .= "<h3>";
						$table_info_location .= table_location($row_tables['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default");
						if ($round != "default") $table_info_location .= sprintf("<br>%s %s",$label_round,$round);
						$table_info_location .= "</h3>";
					}

					if (!empty($table_flight_tbody)) {

						$show_table = TRUE;
						$table_flight .= $table_info_header.$table_info_location;

						$table_flight_datatables .= "<script>";
						$table_flight_datatables .= "$(document).ready(function() {";
						$table_flight_datatables .= "$('#sortable".$row_tables['id']."').dataTable( {";
						$table_flight_datatables .= "\"bPaginate\" : false,";
						$table_flight_datatables .= "\"sDom\": 'rt',";
						$table_flight_datatables .= "\"bStateSave\" : false,";
						$table_flight_datatables .= "\"bLengthChange\" : false,";
						$table_flight_datatables .= "\"aaSorting\": [[1,'asc'],[0,'asc']],";
						$table_flight_datatables .= "\"bProcessing\" : false,";
						$table_flight_datatables .= "\"aoColumns\": [";
						$table_flight_datatables .= "{ \"asSorting\": [  ] },";
						$table_flight_datatables .= "{ \"asSorting\": [  ] },";
						$table_flight_datatables .= "{ \"asSorting\": [  ] },";
						$table_flight_datatables .= "{ \"asSorting\": [  ] },";
						$table_flight_datatables .= "{ \"asSorting\": [  ] },";
						//if ($filter != "mini_bos") $table_flight_datatables .= "{ \"asSorting\": [  ] },";
						$table_flight_datatables .= "{ \"asSorting\": [  ] },";
						$table_flight_datatables .= "{ \"asSorting\": [  ] }";
						$table_flight_datatables .= "]";
						$table_flight_datatables .= "} );";
						$table_flight_datatables .= "} );";
						$table_flight_datatables .= "</script>";

						$table_flight .= $table_flight_datatables;
						$table_flight .= "<table class=\"table table-striped table-bordered\" id=\"sortable".$row_tables['id']."\">";
						$table_flight .= "<thead>";
						$table_flight .= $table_flight_thead;
						$table_flight .= "</thead>";
						$table_flight .= "<tbody>";
						$table_flight .= $table_flight_tbody;
						$table_flight .= "</tbody>";
						$table_flight .= "</table>";
					}

				} // end  if ($entry_count > 0)

			} // end if (($row_table_round['count'] >= 1) || ($round == "default"))
			
			if ($show_table) {
				$pullsheet_output .= $table_flight;
				$pullsheet_output .= "<div style=\"page-break-after:always;\"></div>";
			}

			else {
				if ($id != "default") {
					$pullsheet_output .= $table_info_header;
					$pullsheet_output .= "<p>No entries at this table have additional information.";
				}
			}

		} while ($row_tables = mysqli_fetch_assoc($tables));

		
	} // end else	

} // end if ($go == "all_entry_info")

if ($go == "mini_bos") {

	include (DB.'output_pullsheets_mini_bos.db.php');
	$table_flight = "";
	$table_flight_datatables = "";
	$table_flight_tbody = "";

	$table_info_header = "";

	$table_info_header .= "<div class=\"page-header\">";
	$table_info_header .= "<h1>";
	$table_info_header .= $label_mini_bos;
	$table_info_header .= "</h1>";
	$table_info_header .= "</div>";

	$pullsheet_output .= $table_info_header;

	if ($totalRows_entries_mini > 0) {

		if (!isset($type)) $type = "1234567890";
		else $type = $type;

		$table_flight_datatables .= "<script>";
		$table_flight_datatables .= "$(document).ready(function() {";
		$table_flight_datatables .= "$('#sortable".$type."').dataTable( {";
		$table_flight_datatables .= "\"bPaginate\" : false,";
		$table_flight_datatables .= "\"sDom\": 'rt',";
		$table_flight_datatables .= "\"bStateSave\" : false,";
		$table_flight_datatables .= "\"bLengthChange\" : false,";
		$table_flight_datatables .= "\"aaSorting\": [[2,'asc'],[1,'asc']],";
		$table_flight_datatables .= "\"bProcessing\" : false,";
		$table_flight_datatables .= "\"aoColumns\": [";
		$table_flight_datatables .= "{ \"asSorting\": [  ] },";
		$table_flight_datatables .= "{ \"asSorting\": [  ] },";
		$table_flight_datatables .= "{ \"asSorting\": [  ] },";
		$table_flight_datatables .= "{ \"asSorting\": [  ] },";
		$table_flight_datatables .= "{ \"asSorting\": [  ] },";
		$table_flight_datatables .= "{ \"asSorting\": [  ] },";
		$table_flight_datatables .= "{ \"asSorting\": [  ] }";
		$table_flight_datatables .= "]";
		$table_flight_datatables .= "} );";
		$table_flight_datatables .= "} );";
		$table_flight_datatables .= "</script>";

		$table_flight .= $table_flight_datatables;
		$table_flight .= "<table class=\"table table-striped table-bordered\" id=\"sortable\">";
		$table_flight .= "<thead>";
		$table_flight .= $table_flight_thead;
		$table_flight .= "</thead>";
		$table_flight .= "<tbody>";

		do {

			$style = style_number_const($row_entries_mini['brewCategorySort'],$row_entries_mini['brewSubCategory'],$_SESSION['style_set_display_separator'],0);
			$style_special = $row_entries_mini['brewCategorySort']."^".$row_entries_mini['brewSubCategory']."^".$_SESSION['prefsStyleSet'];
			$special = style_convert($style_special,"9",$base_url);
			$special = explode("^",$special);

			$table_flight_tbody .= "<tr>";
			$table_flight_tbody .= "<td>";
			$table_flight_tbody .= "<p>&nbsp;</p>";
			$table_flight_tbody .= "</td>";

			$table_flight_tbody .= "<td>";
			if ($view == "entry")  $table_flight_tbody .= sprintf("%06s",$row_entries_mini['id']);
			else $table_flight_tbody .= sprintf("%06s",$row_entries_mini['brewJudgingNumber']);
			$table_flight_tbody .= "</td>";

			$table_flight_tbody .= "<td>";
			if ($_SESSION['prefsStyleSet'] == "BA") $table_flight_tbody .= $row_entries_mini['brewStyle'];
			else $table_flight_tbody .= $style." ".$row_entries_mini['brewStyle']."<em><br>".style_convert($row_entries_mini['brewCategorySort'],1,$base_url)."</em>"; 
			$table_flight_tbody .= "</td>";

			$table_flight_tbody .= "<td>";
			if (($row_entries_mini['brewInfo'] != "") && ($special[4] == "1")) {
				if (($_SESSION['prefsStyleSet'] == "BJCP2021") && ($style == "2A")) $table_flight_tbody .= "<p><strong>".$label_regional_variation.":</strong> ".str_replace("^"," | ",$row_entries_mini['brewInfo'])."</p>";
				else $table_flight_tbody .= "<p><strong>".$label_required_info.":</strong> ".str_replace("^"," | ",$row_entries_mini['brewInfo'])."</p>";
			}
			if ($row_entries_mini['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>".$label_optional_info.":</strong> ".$row_entries_mini['brewInfoOptional']."</p>";
			if ($row_entries_mini['brewComments'] != "") $table_flight_tbody .= "<p><strong>".$label_brewer_specifics.":</strong> ".$row_entries_mini['brewComments']."</p>";

			$table_flight_tbody .= "<ul class=\"list-unstyled\">";
			
			if (!empty($row_entries_mini['brewMead1'])) $table_flight_tbody .= "<li><strong>".$label_carbonation.":</strong> ".$row_entries_mini['brewMead1']."</li>";
			if (!empty($row_entries_mini['brewMead2'])) $table_flight_tbody .= "<li><strong>".$label_sweetness.":</strong> ".$row_entries_mini['brewMead2']."</li>";
			if (!empty($row_entries_mini['brewMead3'])) $table_flight_tbody .= "<li><strong>".$label_strength.":</strong> ".$row_entries_mini['brewMead3']."</li>";

			if (!empty($row_entries_mini['brewPossAllergens'])) $table_flight_tbody .= "<li><strong>".$label_possible_allergens.":</strong> ".$row_entries_mini['brewPossAllergens']."</li>";


			if (!empty($row_entries_mini['brewABV'])) $table_flight_tbody .= "<li><strong>".$label_abv.":</strong> ".$row_entries_mini['brewABV']."</li>";	
			
			/*

			if (($_SESSION['prefsStyleSet'] == "NWCiderCup") && (!empty($row_entries_mini['brewJuiceSource']))) {
				  
				$juice_src_arr = json_decode($row_entries_mini['brewJuiceSource'],true);
				$juice_src_disp = "";

				if (is_array($juice_src_arr['juice_src'])) {
					$juice_src_disp .= implode(", ",$juice_src_arr['juice_src']);
					$juice_src_disp .= ", ";
				}

				if ((isset($juice_src_arr['juice_src_other'])) && (is_array($juice_src_arr['juice_src_other']))) {
					$juice_src_disp .= implode(", ",$juice_src_arr['juice_src_other']);
					$juice_src_disp .= ", ";
				}

				$juice_src_disp = rtrim($juice_src_disp,",");
				$juice_src_disp = rtrim($juice_src_disp,", ");

				$table_flight_tbody .= "<li><strong>".$label_juice_source.":</strong> ".$juice_src_disp."</li>";

			}

			*/

			if (!empty($row_entries_mini['brewPackaging'])) $table_flight_tbody .= "<li><strong>".$label_packaging.":</strong> ".$packaging_display[$row_entries_mini['brewPackaging']]."</li>";

			if ((!empty($row_entries_mini['brewPouring'])) && ((!empty($row_entries_mini['brewStyleType'])) && ($row_entries_mini['brewStyleType'] == 1))) {
				$pouring_arr = json_decode($row_entries_mini['brewPouring'],true);
				$table_flight_tbody .= "<li><strong>".$label_pouring.":</strong> ".$pouring_arr['pouring']."</li>";
				if ((isset($pouring_arr['pouring_notes'])) && (!empty($pouring_arr['pouring_notes']))) $table_flight_tbody .= "<li><strong>".$label_pouring_notes.":</strong> ".$pouring_arr['pouring_notes']."</li>";
				$table_flight_tbody .= "<li><strong>".$label_rouse_yeast.":</strong> ".$pouring_arr['pouring_rouse']."</li>";
			}

			if (!empty($row_entries['brewStaffNotes'])) $table_flight_tbody .= "<li><strong>".$label_notes.":</strong> ".$row_entries['brewStaffNotes']."</li>";
			
			$table_flight_tbody .= "</ul>";

			$table_flight_tbody .= "</td>";

			$table_flight_tbody .= "<td>";
			$table_flight_tbody .= $row_entries_mini['brewBoxNum'];
			$table_flight_tbody .= "</td>";

			$table_flight_tbody .= "<td>";
			$table_flight_tbody .= "<p>&nbsp;</p>";
			$table_flight_tbody .= "</td>";

			$table_flight_tbody .= "<td>";
			$table_flight_tbody .= "<p>&nbsp;</p>";
			$table_flight_tbody .= "</td>";

			$table_flight_tbody .= "</tr>";

		} while ($row_entries_mini = mysqli_fetch_assoc($entries_mini));

		$table_flight .= $table_flight_tbody;
		$table_flight .= "</tbody>";
		$table_flight .= "</table>";

	} // end if ($totalRows_entries_mini > 0)

	else {
		$table_flight .= "<p>No Mini-BOS entries were found.</p>";
	}

	$pullsheet_output .= $table_flight;

} // end if ($go == "mini_bos")

if ($go == "judging_scores_bos") {

	$a = array();

	if ($id == "default") {
		do {
			$a[] = $row_style_types['id'];
		} while ($row_style_types = mysqli_fetch_assoc($style_types));
		sort($a);
	}

	else $a[] = $id;

	foreach ($a as $type) {

		$style_type_info = style_type_info($type);
		//echo $style_type_info;
		$style_type_info = explode("^",$style_type_info);

		$table_flight = "";
		$table_flight_datatables = "";
		$table_flight_tbody = "";

		if ($style_type_info[0] == "Y") {

			include (DB.'output_pullsheets_bos.db.php');

			$table_info_header = "";

			$table_info_header .= "<div class=\"page-header\">";
			$table_info_header .= "<h1>";
			$table_info_header .= sprintf("%s: %s",$label_bos,$style_type_info[2]);
			$table_info_header .= "</h1>";
			$table_info_header .= "</div>";

			$pullsheet_output .= $table_info_header;

			if ($totalRows_bos > 0) {

				$table_flight_datatables .= "<script>";
				$table_flight_datatables .= "$(document).ready(function() {";
				$table_flight_datatables .= "$('#sortable".$type."').dataTable( {";
				$table_flight_datatables .= "\"bPaginate\" : false,";
				$table_flight_datatables .= "\"sDom\": 'rt',";
				$table_flight_datatables .= "\"bStateSave\" : false,";
				$table_flight_datatables .= "\"bLengthChange\" : false,";
				$table_flight_datatables .= "\"aaSorting\": [[3,'asc'],[2,'asc'],[1,'asc']],";
				$table_flight_datatables .= "\"bProcessing\" : false,";
				$table_flight_datatables .= "\"aoColumns\": [";
				$table_flight_datatables .= "{ \"asSorting\": [  ] },";
				$table_flight_datatables .= "{ \"asSorting\": [  ] },";
				$table_flight_datatables .= "{ \"asSorting\": [  ] },";
				$table_flight_datatables .= "{ \"asSorting\": [  ] },";
				$table_flight_datatables .= "{ \"asSorting\": [  ] },";
				$table_flight_datatables .= "{ \"asSorting\": [  ] },";
				$table_flight_datatables .= "{ \"asSorting\": [  ] },";
				$table_flight_datatables .= "{ \"asSorting\": [  ] }";
				$table_flight_datatables .= "]";
				$table_flight_datatables .= "} );";
				$table_flight_datatables .= "} );";
				$table_flight_datatables .= "</script>";

				$table_flight .= $table_flight_datatables;
				$table_flight .= "<table class=\"table table-striped table-bordered\" id=\"sortable".$type."\">";
				$table_flight .= "<thead>";
				$table_flight .= $table_flight_thead;
				$table_flight .= "</thead>";
				$table_flight .= "<tbody>";

				do {

					// include (DB.'output_pullsheets_bos_entries.db.php');

					$style = style_number_const($row_bos['brewCategorySort'],$row_bos['brewSubCategory'],$_SESSION['style_set_display_separator'],1);
					$style_special = $row_bos['brewCategorySort']."^".$row_bos['brewSubCategory']."^".$_SESSION['prefsStyleSet'];

					if (!empty($row_bos['brewCategorySort'])) {

						$table_flight_tbody .= "<tr>";

						$table_flight_tbody .= "<td>";
						$table_flight_tbody .= "<p>&nbsp;</p>";
						$table_flight_tbody .= "</td>";
						$table_flight_tbody .= "<td>";

						if ($view == "entry")  $table_flight_tbody .= sprintf("%06s",$row_bos['id']);
						else $table_flight_tbody .= sprintf("%06s",$row_bos['brewJudgingNumber']);
						$table_flight_tbody .= "</td>";

						$table_flight_tbody .= "<td>";
						$table_flight_tbody .= $row_bos['scorePlace'];
						$table_flight_tbody .= "</td>";

						$table_flight_tbody .= "<td>";
						if ($_SESSION['prefsStyleSet'] == "BA") $table_flight_tbody .= $row_bos['brewStyle'];
						else $table_flight_tbody .= $style." ".$row_bos['brewStyle']."<em><br>".style_convert($row_bos['brewCategorySort'],1,$base_url)."</em>";
						$table_flight_tbody .= "</td>";

						$table_flight_tbody .= "<td>";
						$special = style_convert($style_special,"9",$base_url);
						$special = explode("^",$special);

						if (($row_bos['brewInfo'] != "") && ($special[4] == "1")) {
							if (($_SESSION['prefsStyleSet'] == "BJCP2021") && ($style == "02A")) $table_flight_tbody .= "<p><strong>".$label_regional_variation.": </strong>".str_replace("^"," | ",$row_bos['brewInfo'])."</p>";
							else $table_flight_tbody .= "<p><strong>".$label_required_info.": </strong>".str_replace("^"," | ",$row_bos['brewInfo'])."</p>";
						}
						if ($row_bos['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>".$label_optional_info.": </strong>".$row_bos['brewInfoOptional']."</p>";
						if ($row_bos['brewComments'] != "") $table_flight_tbody .= "<p><strong>".$label_brewer_specifics.": </strong>".$row_bos['brewComments']."</p>";

						$table_flight_tbody .= "<ul class=\"list-unstyled\">";

						if ((!empty($row_bos['brewMead1'])) || (!empty($row_bos['brewMead2'])) || (!empty($row_bos['brewMead3']))) {
							if (!empty($row_bos['brewMead1'])) $table_flight_tbody .= "<li><strong>".$label_carbonation.":</strong> ".$row_bos['brewMead1']."</li>";
							if (!empty($row_bos['brewMead2'])) $table_flight_tbody .= "<li><strong>".$label_sweetness.":</strong> ".$row_bos['brewMead2']."</li>";
							if (!empty($row_bos['brewMead3'])) $table_flight_tbody .= "<li><strong>".$label_strength.":</strong> ".$row_bos['brewMead3']."</li>";
						}
						
						if (!empty($row_bos['brewPossAllergens'])) $table_flight_tbody .= "<li><strong>".$label_possible_allergens.":</strong> ".$row_bos['brewPossAllergens']."</li>";

						if (!empty($row_bos['brewABV'])) $table_flight_tbody .= "<li><strong>".$label_abv.":</strong> ".$row_bos['brewABV']."</li>";

						/*

						if (($_SESSION['prefsStyleSet'] == "NWCiderCup") && (!empty($row_bos['brewJuiceSource']))) {
							  
							$juice_src_arr = json_decode($row_bos['brewJuiceSource'],true);
							$juice_src_disp = "";

							if (is_array($juice_src_arr['juice_src'])) {
								$juice_src_disp .= implode(", ",$juice_src_arr['juice_src']);
								$juice_src_disp .= ", ";
							}

							if ((isset($juice_src_arr['juice_src_other'])) && (is_array($juice_src_arr['juice_src_other']))) {
								$juice_src_disp .= implode(", ",$juice_src_arr['juice_src_other']);
								$juice_src_disp .= ", ";
							}

							$juice_src_disp = rtrim($juice_src_disp,",");
							$juice_src_disp = rtrim($juice_src_disp,", ");

							$table_flight_tbody .= "<li><strong>".$label_juice_source.":</strong> ".$juice_src_disp."</li>";

						}

						*/

						if (!empty($row_bos['brewPackaging'])) $table_flight_tbody .= "<li><strong>".$label_packaging.":</strong> ".$packaging_display[$row_bos['brewPackaging']]."</li>";

						if ((!empty($row_bos['brewPouring'])) && ((!empty($row_bos['brewStyleType'])) && ($row_bos['brewStyleType'] == 1))) {
							$pouring_arr = json_decode($row_bos['brewPouring'],true);
							$table_flight_tbody .= "<li><strong>".$label_pouring.":</strong> ".$pouring_arr['pouring']."</li>";
							if ((isset($pouring_arr['pouring_notes'])) && (!empty($pouring_arr['pouring_notes']))) $table_flight_tbody .= "<li><strong>".$label_pouring_notes.":</strong> ".$pouring_arr['pouring_notes']."</li>";
							$table_flight_tbody .= "<li><strong>".$label_rouse_yeast.":</strong> ".$pouring_arr['pouring_rouse']."</li>";
						}

						if (!empty($row_bos['brewStaffNotes'])) $table_flight_tbody .= "<p><strong>".$label_notes.":</strong> ".$row_bos['brewStaffNotes']."</p>";
						$table_flight_tbody .= "</ul>";

						if ($row_bos['brewerProAm'] == 1) $table_flight_tbody .= "<p><strong>** NOT ELIGIBLE FOR PRO-AM **</p>"; 

						$table_flight_tbody .= "</td>";
						$table_flight_tbody .= "<td>";
						$table_flight_tbody .= $row_bos['brewBoxNum'];
						$table_flight_tbody .= "</td>";
						$table_flight_tbody .= "<td>";
						$table_flight_tbody .= "<p>&nbsp;</p>";
						$table_flight_tbody .= "</td>";
						$table_flight_tbody .= "<td>";
						$table_flight_tbody .= "<p>&nbsp;</p>";
						$table_flight_tbody .= "</td>";
						$table_flight_tbody .= "</tr>";

					}


				} while ($row_bos = mysqli_fetch_assoc($bos));

				$table_flight .= $table_flight_tbody;
				$table_flight .= "</tbody>";
				$table_flight .= "</table>";
				$table_flight .= "<div style=\"page-break-after:always;\"></div>";

			}

			else {
				$table_flight .= "<p>No BOS entries were found for ".$style_type_info[2].".</p>";
				$table_flight .= "<div style=\"page-break-after:always;\"></div>";
			}

			$pullsheet_output .= $table_flight;

		} // end if ($style_type_info[0] == "Y")

	}

} // end if ($go == "judging_scores_bos")

elseif (($go != "judging_scores_bos") && ($go != "mini_bos") && ($go != "all_entry_info")) {

	// If using queued judging (no flights)
	
	if ($queued) {

		if ($tables_all) {

			$pullsheet_output = "";
			$round_count = array();

			do {

				$entry_count = get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default");
				include (DB.'output_pullsheets_queued.db.php');
				$round_count[] = $row_table_round['count'];

				$table_flight = "";
				$table_flight_datatables = "";

				if (($row_table_round['count'] >= 1) || ($round == "default")) {

					$table_info_location = "";
					$table_info_notes = "";
					$table_info_header = "";

					$table_info_header .= "<div class=\"page-header\">";
					$table_info_header .= "<h1>";
					$table_info_header .= sprintf("%s %s: %s",$label_table,$row_tables['tableNumber'],$row_tables['tableName']);
					if ($filter == "mini_bos") $table_info_header .= sprintf(" - %s",$label_mini_bos);
					$table_info_header .= "</h1>";
					$table_info_header .= "</div>";

					if ((!empty($row_tables['tableLocation'])) && ($filter != "mini_bos")) {

						$table_info_location .= "<h2>";
						$table_info_location .= table_location($row_tables['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default");
						if ($round != "default") $table_info_location .= sprintf("<br>%s %s",$label_round,$round);
						$table_info_location .= "</h2>";
						$table_info_location .= "<p class=\"lead\">";
						$table_info_location .= sprintf("%s: %s",$label_entries,$entry_count);
						$table_info_location .= "</p>";
						$table_info_location .= "<p>";
						$table_info_location .= sprintf("%s: %s",$label_please_note,$output_text_019);
						$table_info_location .= "</p>";

					}

					$pullsheet_output .= $table_info_header.$table_info_location;

					if ($entry_count > 0) {

						//$table_flight .= $row_tables['tableStyles'];

						$table_flight_datatables .= "<script>";
						$table_flight_datatables .= "$(document).ready(function() {";
						$table_flight_datatables .= "$('#sortable".$row_tables['id']."').dataTable( {";
						$table_flight_datatables .= "\"bPaginate\" : false,";
						$table_flight_datatables .= "\"sDom\": 'rt',";
						$table_flight_datatables .= "\"bStateSave\" : false,";
						$table_flight_datatables .= "\"bLengthChange\" : false,";
						$table_flight_datatables .= "\"aaSorting\": [[2,'asc'],[1,'asc']],";
						$table_flight_datatables .= "\"bProcessing\" : false,";
						$table_flight_datatables .= "\"aoColumns\": [";
						$table_flight_datatables .= "{ \"asSorting\": [  ] },";
						$table_flight_datatables .= "{ \"asSorting\": [  ] },";
						$table_flight_datatables .= "{ \"asSorting\": [  ] },";
						$table_flight_datatables .= "{ \"asSorting\": [  ] },";
						$table_flight_datatables .= "{ \"asSorting\": [  ] },";
						if ($filter != "mini_bos") $table_flight_datatables .= "{ \"asSorting\": [  ] },";
						$table_flight_datatables .= "{ \"asSorting\": [  ] },";
						$table_flight_datatables .= "{ \"asSorting\": [  ] }";
						$table_flight_datatables .= "]";
						$table_flight_datatables .= "} );";
						$table_flight_datatables .= "} );";
						$table_flight_datatables .= "</script>";

						$table_flight .= $table_flight_datatables;
						$table_flight .= "<table class=\"table table-striped table-bordered\" id=\"sortable".$row_tables['id']."\">";
						$table_flight .= "<thead>";
						$table_flight .= $table_flight_thead;
						$table_flight .= "</thead>";
						$table_flight .= "<tbody>";

						$table_flight_tbody = "";

						$a = explode(",", $row_tables['tableStyles']);

						foreach (array_unique($a) as $value) {

							include (DB.'output_pullsheets_entries.db.php');
							
							$style = "";
							$style_special = "";

							if ($row_entries) {
								$style = style_number_const($row_entries['brewCategorySort'],$row_entries['brewSubCategory'],$_SESSION['style_set_display_separator'],0);
								$style_special = $row_entries['brewCategorySort']."^".$row_entries['brewSubCategory']."^".$_SESSION['prefsStyleSet'];
							}
							

							do {

								if (!empty($row_entries['brewCategorySort'])) {

									$table_flight_tbody .= "<tr>";
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= "<p>&nbsp;</p>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									if ($view == "entry")  $table_flight_tbody .= sprintf("%06s",$row_entries['id']);
									else $table_flight_tbody .= sprintf("%06s",$row_entries['brewJudgingNumber']);
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									if ($_SESSION['prefsStyleSet'] == "BA") $table_flight_tbody .= $row_entries['brewStyle']; 
									else $table_flight_tbody .= $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1,$base_url)."</em>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";

									$special = style_convert($style_special,"9",$base_url);
									$special = explode("^",$special);

									if (($row_entries['brewInfo'] != "") && ($special[4] == "1")) {
										if (($_SESSION['prefsStyleSet'] == "BJCP2021") && ($style == "2A")) $table_flight_tbody .= "<p><strong>".$label_regional_variation.": </strong> ".str_replace("^"," | ",$row_entries['brewInfo'])."</p>";
										else $table_flight_tbody .= "<p><strong>".$label_required_info.": </strong> ".str_replace("^"," | ",$row_entries['brewInfo'])."</p>";
									}
									if ($row_entries['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>".$label_optional_info.": </strong> ".$row_entries['brewInfoOptional']."</p>";
									if ($row_entries['brewComments'] != "") $table_flight_tbody .= "<p><strong>".$label_brewer_specifics.": </strong> ".$row_entries['brewComments']."</p>";

									$table_flight_tbody .= "<ul class\"list-unstyled\">";
									if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<li><strong>".$label_carbonation.": </strong> ".$row_entries['brewMead1']."</li>";
									if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<li><strong>".$label_sweetness.":</strong> ".$row_entries['brewMead2']."</li>";
									if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<li><strong>".$label_strength.":</strong> ".$row_entries['brewMead3']."</li>";
									
									if (!empty($row_entries['brewPossAllergens'])) $table_flight_tbody .= "<li><strong>".$label_possible_allergens.":</strong> ".$row_entries['brewPossAllergens']."</li>";

									if (!empty($row_entries['brewABV'])) $table_flight_tbody .= "<li><strong>".$label_abv.":</strong> ".$row_entries['brewABV']."</li>";	
									/*

									if (($_SESSION['prefsStyleSet'] == "NWCiderCup") && (!empty($row_entries['brewJuiceSource']))) {
										  
										$juice_src_arr = json_decode($row_entries['brewJuiceSource'],true);
										$juice_src_disp = "";

										if (is_array($juice_src_arr['juice_src'])) {
											$juice_src_disp .= implode(", ",$juice_src_arr['juice_src']);
											$juice_src_disp .= ", ";
										}

										if ((isset($juice_src_arr['juice_src_other'])) && (is_array($juice_src_arr['juice_src_other']))) {
											$juice_src_disp .= implode(", ",$juice_src_arr['juice_src_other']);
											$juice_src_disp .= ", ";
										}

										$juice_src_disp = rtrim($juice_src_disp,",");
										$juice_src_disp = rtrim($juice_src_disp,", ");

										$table_flight_tbody .= "<li><strong>".$label_juice_source.":</strong> ".$juice_src_disp."</li>";

									}

									*/

									if (!empty($row_entries['brewPackaging'])) $table_flight_tbody .= "<li><strong>".$label_packaging.":</strong> ".$packaging_display[$row_entries['brewPackaging']]."</li>";

									if ((!empty($row_entries['brewPouring'])) && ((!empty($row_entries['brewStyleType'])) && ($row_entries['brewStyleType'] == 1))) {
										$pouring_arr = json_decode($row_entries['brewPouring'],true);
										$table_flight_tbody .= "<li><strong>".$label_pouring.":</strong> ".$pouring_arr['pouring']."</li>";
										if ((isset($pouring_arr['pouring_notes'])) && (!empty($pouring_arr['pouring_notes']))) $table_flight_tbody .= "<li><strong>".$label_pouring_notes.":</strong> ".$pouring_arr['pouring_notes']."</li>";
										$table_flight_tbody .= "<li><strong>".$label_rouse_yeast.":</strong> ".$pouring_arr['pouring_rouse']."</li>";
									}

									if (!empty($row_entries['brewStaffNotes'])) $table_flight_tbody .= "<li><strong>".$label_notes.":</strong> ".$row_entries['brewStaffNotes']."</li>";

									$table_flight_tbody .= "</ul>";

									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= $row_entries['brewBoxNum'];;
									$table_flight_tbody .= "</td>";
									if ($filter != "mini_bos") {
										$table_flight_tbody .= "<td>";
										$table_flight_tbody .= "<p class=\"box_small\">";
										$table_flight_tbody .= "</td>";
									}
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= "<p>&nbsp;</p>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= "<p>&nbsp;</p>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "</tr>";

								}

							} while ($row_entries = mysqli_fetch_assoc($entries));

						} // end foreach

						$table_flight .= $table_flight_tbody;
						$table_flight .= "</tbody>";
						$table_flight .= "</table>";

					} // end  if ($entry_count > 0)

				} // end if (($row_table_round['count'] >= 1) || ($round == "default"))

				if (empty($table_flight_tbody)) {
					if ($filter == "mini_bos") $pullsheet_output .= "No Mini-BOS entries available.";
					else $pullsheet_output .= "No entries available.";
				}
				else $pullsheet_output .= $table_flight;

				$pullsheet_output .= "<div style=\"page-break-after:always;\"></div>";

			} while ($row_tables = mysqli_fetch_assoc($tables));

		} // end if ($tables_all)

		if (!$tables_all) {

			$pullsheet_output = "";

			$entry_count = get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default");
			include (DB.'output_pullsheets_queued.db.php');
			$round_count[] = $row_table_round['count'];

			$table_flight = "";
			$table_flight_datatables = "";

			if (($row_table_round['count'] >= 1) || ($round == "default")) {

				$table_info_location = "";
				$table_info_notes = "";
				$table_info_header = "";

				$table_info_header .= "<div class=\"page-header\">";
				$table_info_header .= "<h1>";
				$table_info_header .= sprintf("%s %s: %s",$label_table,$row_tables['tableNumber'],$row_tables['tableName']);
				if ($filter == "mini_bos") $table_info_header .= sprintf(" - %s",$label_mini_bos);
				$table_info_header .= "</h1>";
				$table_info_header .= "</div>";

				if ((!empty($row_tables['tableLocation'])) && ($filter != "mini_bos")) {

					$table_info_location .= "<h2>";
					$table_info_location .= table_location($row_tables['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default");
					if ($round != "default") $table_info_location .= sprintf("<br>%s %s",$label_round,$round);
					$table_info_location .= "</h2>";
					$table_info_location .= "<p class=\"lead\">";
					$table_info_location .= sprintf("%s: %s",$label_entries,$entry_count);
					$table_info_location .= "</p>";
					$table_info_location .= "<p>";
					$table_info_location .= sprintf("%s: %s",$label_please_note,$output_text_019);
					$table_info_location .= "</p>";

				}

				$pullsheet_output .= $table_info_header.$table_info_location;

				if ($entry_count > 0) {

					$table_flight_datatables .= "<script>";
					$table_flight_datatables .= "$(document).ready(function() {";
					$table_flight_datatables .= "$('#sortable".$row_tables['id']."').dataTable( {";
					$table_flight_datatables .= "\"bPaginate\" : false,";
					$table_flight_datatables .= "\"sDom\": 'rt',";
					$table_flight_datatables .= "\"bStateSave\" : false,";
					$table_flight_datatables .= "\"bLengthChange\" : false,";
					$table_flight_datatables .= "\"aaSorting\": [[2,'asc'],[1,'asc']],";
					$table_flight_datatables .= "\"bProcessing\" : false,";
					$table_flight_datatables .= "\"aoColumns\": [";
					$table_flight_datatables .= "{ \"asSorting\": [  ] },";
					$table_flight_datatables .= "{ \"asSorting\": [  ] },";
					$table_flight_datatables .= "{ \"asSorting\": [  ] },";
					$table_flight_datatables .= "{ \"asSorting\": [  ] },";
					$table_flight_datatables .= "{ \"asSorting\": [  ] },";
					if ($filter != "mini_bos") $table_flight_datatables .= "{ \"asSorting\": [  ] },";
					$table_flight_datatables .= "{ \"asSorting\": [  ] },";
					$table_flight_datatables .= "{ \"asSorting\": [  ] }";
					$table_flight_datatables .= "]";
					$table_flight_datatables .= "} );";
					$table_flight_datatables .= "} );";
					$table_flight_datatables .= "</script>";

					$table_flight .= $table_flight_datatables;
					$table_flight .= "<table class=\"table table-striped table-bordered\" id=\"sortable".$row_tables['id']."\">";
					$table_flight .= "<thead>";
					$table_flight .= $table_flight_thead;
					$table_flight .= "</thead>";
					$table_flight .= "<tbody>";

					$table_flight_tbody = "";

					$a = explode(",", $row_tables['tableStyles']);

					foreach (array_unique($a) as $value) {

						include (DB.'output_pullsheets_entries.db.php');

						if ($row_entries) {

							$style = style_number_const($row_entries['brewCategorySort'],$row_entries['brewSubCategory'],$_SESSION['style_set_display_separator'],0);
							$style_special = $row_entries['brewCategorySort']."^".$row_entries['brewSubCategory']."^".$_SESSION['prefsStyleSet'];

							do {

								if (!empty($row_entries['brewCategorySort'])) {

									$table_flight_tbody .= "<tr>";
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= "<p>&nbsp;</p>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									if ($view == "entry")  $table_flight_tbody .= sprintf("%06s",$row_entries['id']);
									else $table_flight_tbody .= sprintf("%06s",$row_entries['brewJudgingNumber']);
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									if ($_SESSION['prefsStyleSet'] == "BA") $table_flight_tbody .= $row_entries['brewStyle'];
									else $table_flight_tbody .= $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1,$base_url)."</em>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";

									$special = style_convert($style_special,"9",$base_url);
									$special = explode("^",$special);

									if (($row_entries['brewInfo'] != "") && ($special[4] == "1")) {
										if (($_SESSION['prefsStyleSet'] == "BJCP2021") && ($style == "2A")) $table_flight_tbody .= "<p><strong>".$label_regional_variation.": </strong> ".str_replace("^"," | ",$row_entries['brewInfo'])."</p>";
										else $table_flight_tbody .= "<p><strong>".$label_required_info.": </strong> ".str_replace("^"," | ",$row_entries['brewInfo'])."</p>";
									}
									if ($row_entries['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>".$label_optional_info.": </strong> ".$row_entries['brewInfoOptional']."</p>";
									if ($row_entries['brewComments'] != "") $table_flight_tbody .= "<p><strong>".$label_brewer_specifics.": </strong> ".$row_entries['brewComments']."</p>";

									$table_flight_tbody .= "<ul class=\"list-unstyled\">";
									if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<li><strong>".$label_carbonation.": </strong> ".$row_entries['brewMead1']."</li>";
									if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<li><strong>".$label_sweetness.":</strong> ".$row_entries['brewMead2']."</li>";
									if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<li><strong>".$label_strength.":</strong> ".$row_entries['brewMead3']."</li>";
									
									if (!empty($row_entries['brewPossAllergens'])) $table_flight_tbody .= "<li><strong>".$label_possible_allergens.":</strong> ".$row_entries['brewPossAllergens']."</li>";

									if (!empty($row_entries['brewABV'])) $table_flight_tbody .= "<li><strong>".$label_abv.":</strong> ".$row_entries['brewABV']."</li>";	
									/*

									if (($_SESSION['prefsStyleSet'] == "NWCiderCup") && (!empty($row_entries['brewJuiceSource']))) {
										  
										$juice_src_arr = json_decode($row_entries['brewJuiceSource'],true);
										$juice_src_disp = "";

										if (is_array($juice_src_arr['juice_src'])) {
											$juice_src_disp .= implode(", ",$juice_src_arr['juice_src']);
											$juice_src_disp .= ", ";
										}

										if ((isset($juice_src_arr['juice_src_other'])) && (is_array($juice_src_arr['juice_src_other']))) {
											$juice_src_disp .= implode(", ",$juice_src_arr['juice_src_other']);
											$juice_src_disp .= ", ";
										}

										$juice_src_disp = rtrim($juice_src_disp,",");
										$juice_src_disp = rtrim($juice_src_disp,", ");

										$table_flight_tbody .= "<li><strong>".$label_juice_source.":</strong> ".$juice_src_disp."</li>";

									}

									*/

									if (!empty($row_entries['brewPackaging'])) $table_flight_tbody .= "<li><strong>".$label_packaging.":</strong> ".$packaging_display[$row_entries['brewPackaging']]."</li>";
									
									if ((!empty($row_entries['brewPouring'])) && ((!empty($row_entries['brewStyleType'])) && ($row_entries['brewStyleType'] == 1))) {
										$pouring_arr = json_decode($row_entries['brewPouring'],true);
										$table_flight_tbody .= "<li><strong>".$label_pouring.":</strong> ".$pouring_arr['pouring']."</li>";
										if ((isset($pouring_arr['pouring_notes'])) && (!empty($pouring_arr['pouring_notes']))) $table_flight_tbody .= "<li><strong>".$label_pouring_notes.":</strong> ".$pouring_arr['pouring_notes']."</li>";
										$table_flight_tbody .= "<li><strong>".$label_rouse_yeast.":</strong> ".$pouring_arr['pouring_rouse']."</li>";
									}

									if (!empty($row_entries['brewStaffNotes'])) $table_flight_tbody .= "<li><strong>".$label_notes.":</strong> ".$row_entries['brewStaffNotes']."</li>";

									$table_flight_tbody .= "</ul>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= $row_entries['brewBoxNum'];;
									$table_flight_tbody .= "</td>";
									if ($filter != "mini_bos") {
										$table_flight_tbody .= "<td>";
										$table_flight_tbody .= "<p class=\"box_small\">";
										$table_flight_tbody .= "</td>";
									}
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= "<p>&nbsp;</p>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= "<p>&nbsp;</p>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "</tr>";

								}

							} while ($row_entries = mysqli_fetch_assoc($entries));

						}

					} // end foreach

					$table_flight .= $table_flight_tbody;
					$table_flight .= "</tbody>";
					$table_flight .= "</table>";
					$table_flight .= "<div style=\"page-break-after:always;\"></div>";

				} // end  if ($entry_count > 0)

			} // end if (($row_table_round['count'] >= 1) || ($round == "default"))

			$pullsheet_output .= $table_flight;

		} // end if (!$tables_all)

	} // end if ($queued)

	// If NOT using queued judging (with flights)
	if (!$queued) {

		// Loop through all tables
		if ($tables_all) {

			$pullsheet_output = "";

			// Don't separate out flights when generating MBOS

			if ($filter == "mini_bos") {

				do {

					// Reset Vars
					$table_info_location = "";
					$table_info_notes = "";
					$table_info_header = "";

					$flights = number_of_flights($row_tables['id']);
					if ($flights > 0) $flights = $flights; else $flights = "0";

					$table_info_header .= "<div class=\"page-header\">";
					$table_info_header .= "<h1>";
					$table_info_header .= sprintf("%s %s: %s",$label_table,$row_tables['tableNumber'],$row_tables['tableName']);
					if ($filter == "mini_bos") $table_info_header .= sprintf(" - %s",$label_mini_bos);
					$table_info_header .= "</h1>";
					$table_info_header .= "</div>";

					/*

					if (!empty($row_tables['tableLocation'])) {

						$table_info_location .= "<h2>";
						$table_info_location .= table_location($row_tables['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default");
						if ($round != "default") $table_info_location .= sprintf("<br>%s %s",$label_round,$round);
						$table_info_location .= "</h2>";
						$table_info_location .= "<p class=\"lead\">";
						$table_info_location .= sprintf("%s: %s<br>%s: %s",$label_entries,get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default"),$label_flights,$flights);
						$table_info_location .= "</p>";
						$table_info_location .= "<p>".$label_please_note."</p>";
						$table_info_location .= "<ul>";
						$table_info_location .= "<li>".$output_text_017."</li>";
						$table_info_location .= "<li>".$output_text_018."</li>";
						$table_info_location .= "</ul>";

					}
					*/

					$pullsheet_output .= $table_info_header.$table_info_location;

					$table_flight = "";
					$table_flight_datatables = "";

					$random = random_generator(5,2);

					/*
					$query_round_check = sprintf("SELECT flightRound FROM %s WHERE flightTable='%s' AND flightNumber='%s' LIMIT 1", $prefix."judging_flights", $row_tables['id'],$i);
					$round_check = mysqli_query($connection,$query_round_check) or die (mysqli_error($connection));
					$row_round_check = mysqli_fetch_assoc($round_check);

					// $table_flight .= "<h3>".sprintf("%s %s: %s - %s %s, %s %s",$label_table,$row_tables['tableNumber'],$row_tables['tableName'],$label_flight,$i,$label_round,$row_round_check['flightRound'])."</h3>";
					*/

					$table_flight_datatables .= "<script>";
					$table_flight_datatables .= "$(document).ready(function() {";
					$table_flight_datatables .= "$('#sortable".$random."').dataTable( {";
					$table_flight_datatables .= "\"bPaginate\" : false,";
					$table_flight_datatables .= "\"sDom\": 'rt',";
					$table_flight_datatables .= "\"bStateSave\" : false,";
					$table_flight_datatables .= "\"bLengthChange\" : false,";
					$table_flight_datatables .= "\"aaSorting\": [[2,'asc'],[1,'asc']],";
					$table_flight_datatables .= "\"bProcessing\" : false,";
					$table_flight_datatables .= "\"aoColumns\": [";
					$table_flight_datatables .= "{ \"asSorting\": [  ] },";
					$table_flight_datatables .= "{ \"asSorting\": [  ] },";
					$table_flight_datatables .= "{ \"asSorting\": [  ] },";
					$table_flight_datatables .= "{ \"asSorting\": [  ] },";
					$table_flight_datatables .= "{ \"asSorting\": [  ] },";
					$table_flight_datatables .= "{ \"asSorting\": [  ] },";
					if ($filter != "mini_bos") $table_flight_datatables .= "{ \"asSorting\": [  ] },";
					$table_flight_datatables .= "{ \"asSorting\": [  ] }";
					$table_flight_datatables .= "]";
					$table_flight_datatables .= "} );";
					$table_flight_datatables .= "} );";
					$table_flight_datatables .= "</script>";

					$table_flight .= $table_flight_datatables;

					$table_flight .= "<table class=\"table table-striped table-bordered\" id=\"sortable".$random."\">";
					$table_flight .= "<thead>";
					$table_flight .= $table_flight_thead;
					$table_flight .= "</thead>";
					$table_flight .= "<tbody>";

					for($i=1; $i<$flights+1; $i++) {

						$a = explode(",", $row_tables['tableStyles']);

						//print_r($a);

						foreach (array_unique($a) as $value) {

							include (DB.'output_pullsheets_entries.db.php');

							$table_flight_tbody = "";

							if (!empty($row_entries)) {

								$style = style_number_const($row_entries['brewCategorySort'],$row_entries['brewSubCategory'],$_SESSION['style_set_display_separator'],0);
								$style_special = $row_entries['brewCategorySort']."^".$row_entries['brewSubCategory']."^".$_SESSION['prefsStyleSet'];

								do {

									$flight_round = check_flight_number($row_entries['id'],$i,0);

									if (check_flight_round($flight_round,$round)) {

										$table_flight_tbody .= "<tr>";
										$table_flight_tbody .= "<td>";
										$table_flight_tbody .= "<p>&nbsp;</p>";
										$table_flight_tbody .= "</td>";
										$table_flight_tbody .= "<td>";
										if ($view == "entry")  $table_flight_tbody .= sprintf("%06s",$row_entries['id']);
										else $table_flight_tbody .= sprintf("%06s",$row_entries['brewJudgingNumber']);
										$table_flight_tbody .= "</td>";
										$table_flight_tbody .= "<td>";
										if ($_SESSION['prefsStyleSet'] == "BA") $table_flight_tbody .= $row_entries['brewStyle'];
										else $table_flight_tbody .= $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1,$base_url)."</em>";
										$table_flight_tbody .= "</td>";
										$table_flight_tbody .= "<td>";

										$special = style_convert($style_special,"9",$base_url);
										$special = explode("^",$special);

											if (($row_entries['brewInfo'] != "") && ($special[4] == "1")) {
												if (($_SESSION['prefsStyleSet'] == "BJCP2021") && ($style == "2A")) $table_flight_tbody .= "<p><strong>".$label_regional_variation.": </strong> ".str_replace("^"," | ",$row_entries['brewInfo'])."</p>";
												else $table_flight_tbody .= "<p><strong>".$label_required_info.": </strong> ".str_replace("^"," | ",$row_entries['brewInfo'])."</p>";
											}
											if ($row_entries['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>".$label_optional_info.": </strong> ".$row_entries['brewInfoOptional']."</p>";
											if ($row_entries['brewComments'] != "") $table_flight_tbody .= "<p><strong>".$label_brewer_specifics.": </strong> ".$row_entries['brewComments']."</p>";

											$table_flight_tbody .= "<ul class=\"list-unstyled\">";
											if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<li><strong>".$label_carbonation.": </strong> ".$row_entries['brewMead1']."</li>";
											if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<li><strong>".$label_sweetness.":</strong> ".$row_entries['brewMead2']."</li>";
											if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<li><strong>".$label_strength.":</strong> ".$row_entries['brewMead3']."</li>";
											

											if (!empty($row_entries['brewPossAllergens'])) $table_flight_tbody .= "<li><strong>".$label_possible_allergens.":</strong> ".$row_entries['brewPossAllergens']."</li>";

											if (!empty($row_entries['brewABV'])) $table_flight_tbody .= "<li><strong>".$label_abv.":</strong> ".$row_entries['brewABV']."</li>";	
											
											/*

											if (($_SESSION['prefsStyleSet'] == "NWCiderCup") && (!empty($row_entries['brewJuiceSource']))) {
												  
												$juice_src_arr = json_decode($row_entries['brewJuiceSource'],true);
												$juice_src_disp = "";

												if (is_array($juice_src_arr['juice_src'])) {
													$juice_src_disp .= implode(", ",$juice_src_arr['juice_src']);
													$juice_src_disp .= ", ";
												}

												if ((isset($juice_src_arr['juice_src_other'])) && (is_array($juice_src_arr['juice_src_other']))) {
													$juice_src_disp .= implode(", ",$juice_src_arr['juice_src_other']);
													$juice_src_disp .= ", ";
												}

												$juice_src_disp = rtrim($juice_src_disp,",");
												$juice_src_disp = rtrim($juice_src_disp,", ");

												$table_flight_tbody .= "<li><strong>".$label_juice_source.":</strong> ".$juice_src_disp."</li>";

											}

											*/

											if (!empty($row_entries['brewPackaging'])) $table_flight_tbody .= "<li><strong>".$label_packaging.":</strong> ".$packaging_display[$row_entries['brewPackaging']]."</li>";

											if ((!empty($row_entries['brewPouring'])) && ((!empty($row_entries['brewStyleType'])) && ($row_entries['brewStyleType'] == 1))) {
												$pouring_arr = json_decode($row_entries['brewPouring'],true);
												$table_flight_tbody .= "<li><strong>".$label_pouring.":</strong> ".$pouring_arr['pouring']."</li>";
												if ((isset($pouring_arr['pouring_notes'])) && (!empty($pouring_arr['pouring_notes']))) $table_flight_tbody .= "<li><strong>".$label_pouring_notes.":</strong> ".$pouring_arr['pouring_notes']."</li>";
												$table_flight_tbody .= "<li><strong>".$label_rouse_yeast.":</strong> ".$pouring_arr['pouring_rouse']."</li>";
											}

											if (!empty($row_entries['brewStaffNotes'])) $table_flight_tbody .= "<li><strong>".$label_notes.":</strong> ".$row_entries['brewStaffNotes']."</li>";

											$table_flight_tbody .= "</ul>";
											$table_flight_tbody .= "</td>";
											$table_flight_tbody .= "<td>";
											$table_flight_tbody .= $row_entries['brewBoxNum'];;
											$table_flight_tbody .= "</td>";
											if ($filter != "mini_bos") {
												$table_flight_tbody .= "<td>";
												$table_flight_tbody .= "<p class=\"box_small\">";
												$table_flight_tbody .= "</td>";
											}
											$table_flight_tbody .= "<td>";
											$table_flight_tbody .= "<p>&nbsp;</p>";
											$table_flight_tbody .= "</td>";
											$table_flight_tbody .= "<td>";
											$table_flight_tbody .= "<p>&nbsp;</p>";
											$table_flight_tbody .= "</td>";
											$table_flight_tbody .= "</tr>";

										}

									$table_flight .= $table_flight_tbody;

								} while ($row_entries = mysqli_fetch_assoc($entries));

							}

						} // end foreach

					} // end for($i=1; $i<$flights+1; $i++)

					$table_flight .= "</tbody>";
					$table_flight .= "</table>";

					if (empty($table_flight_tbody)){
						if ($filter == "mini_bos") $pullsheet_output .= "No Mini-BOS entries available.";
						else $pullsheet_output .= "No entries available.";
					}
					else $pullsheet_output .= $table_flight;
					//if (($flights > 0) && ($filter != "mini_bos")) $pullsheet_output .= "<div style=\"page-break-after:always;\"></div>";
					//if (($flights == 0) || ($filter == "mini_bos")) $pullsheet_output .= "<div style=\"page-break-after:always;\"></div>";
					$pullsheet_output .= "<div style=\"page-break-after:always;\"></div>";

				} while ($row_tables = mysqli_fetch_assoc($tables));

			}

			// Separate by flights if pulling for general judging

			else {

				do {

					// Reset Vars
					$table_info_location = "";
					$table_info_notes = "";
					$table_info_header = "";

					$flights = number_of_flights($row_tables['id']);
					if ($flights > 0) $flights = $flights; else $flights = "0";

					$table_info_header .= "<div class=\"page-header\">";
					$table_info_header .= "<h1>";
					$table_info_header .= sprintf("%s %s: %s",$label_table,$row_tables['tableNumber'],$row_tables['tableName']);
					if ($filter == "mini_bos") $table_info_header .= sprintf(" - %s",$label_mini_bos);
					$table_info_header .= "</h1>";
					$table_info_header .= "</div>";

					if (!empty($row_tables['tableLocation'])) {

						$table_info_location .= "<h2>";
						$table_info_location .= table_location($row_tables['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default");
						if ($round != "default") $table_info_location .= sprintf("<br>%s %s",$label_round,$round);
						$table_info_location .= "</h2>";
						$table_info_location .= "<p class=\"lead\">";
						$table_info_location .= sprintf("%s: %s<br>%s: %s",$label_entries,get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default"),$label_flights,$flights);
						$table_info_location .= "</p>";
						$table_info_location .= "<div class=\"alert alert-warning hidden-print\">";
						$table_info_location .= "<p><strong>".$label_please_note."</strong></p>";
						$table_info_location .= "<ul>";
						$table_info_location .= "<li>".$output_text_017."</li>";
						$table_info_location .= "<li>".$output_text_018."</li>";
						$table_info_location .= "</ul>";
						$table_info_location .= "</div>";

					}

					$pullsheet_output .= $table_info_header.$table_info_location;

					for($i=1; $i<$flights+1; $i++) {

						$table_flight = "";
						$table_flight_datatables = "";

						$random = random_generator(5,2);

						$query_round_check = sprintf("SELECT flightRound FROM %s WHERE flightTable='%s' AND flightNumber='%s' LIMIT 1", $prefix."judging_flights", $row_tables['id'],$i);
						$round_check = mysqli_query($connection,$query_round_check) or die (mysqli_error($connection));
						$row_round_check = mysqli_fetch_assoc($round_check);

						$table_flight .= "<h3>".sprintf("%s %s: %s - %s %s, %s %s",$label_table,$row_tables['tableNumber'],$row_tables['tableName'],$label_flight,$i,$label_round,$row_round_check['flightRound'])."</h3>";

						$table_flight_datatables .= "<script>";
						$table_flight_datatables .= "$(document).ready(function() {";
						$table_flight_datatables .= "$('#sortable".$random."').dataTable( {";
						$table_flight_datatables .= "\"bPaginate\" : false,";
						$table_flight_datatables .= "\"sDom\": 'rt',";
						$table_flight_datatables .= "\"bStateSave\" : false,";
						$table_flight_datatables .= "\"bLengthChange\" : false,";
						$table_flight_datatables .= "\"aaSorting\": [[2,'asc'],[1,'asc']],";
						$table_flight_datatables .= "\"bProcessing\" : false,";
						$table_flight_datatables .= "\"aoColumns\": [";
						$table_flight_datatables .= "{ \"asSorting\": [  ] },";
						$table_flight_datatables .= "{ \"asSorting\": [  ] },";
						$table_flight_datatables .= "{ \"asSorting\": [  ] },";
						$table_flight_datatables .= "{ \"asSorting\": [  ] },";
						$table_flight_datatables .= "{ \"asSorting\": [  ] },";
						$table_flight_datatables .= "{ \"asSorting\": [  ] },";
						if ($filter != "mini_bos") $table_flight_datatables .= "{ \"asSorting\": [  ] },";
						$table_flight_datatables .= "{ \"asSorting\": [  ] }";
						$table_flight_datatables .= "]";
						$table_flight_datatables .= "} );";
						$table_flight_datatables .= "} );";
						$table_flight_datatables .= "</script>";

						$table_flight .= $table_flight_datatables;

						$table_flight .= "<table class=\"table table-striped table-bordered\" id=\"sortable".$random."\">";
						$table_flight .= "<thead>";
						$table_flight .= $table_flight_thead;
						$table_flight .= "</thead>";
						$table_flight .= "<tbody>";

						$a = explode(",", $row_tables['tableStyles']);
						//print_r($a);
						foreach (array_unique($a) as $value) {

							include (DB.'output_pullsheets_entries.db.php');
							$style = style_number_const($row_entries['brewCategorySort'],$row_entries['brewSubCategory'],$_SESSION['style_set_display_separator'],0);
							$style_special = $row_entries['brewCategorySort']."^".$row_entries['brewSubCategory']."^".$_SESSION['prefsStyleSet'];

							do {

								$table_flight_tbody = "";

								$flight_round = check_flight_number($row_entries['id'],$i,0);

								if (check_flight_round($flight_round,$round)) {

									$table_flight_tbody .= "<tr>";
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= "<p>&nbsp;</p>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									if ($view == "entry")  $table_flight_tbody .= sprintf("%06s",$row_entries['id']);
									else $table_flight_tbody .= sprintf("%06s",$row_entries['brewJudgingNumber']);
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									if ($_SESSION['prefsStyleSet'] == "BA") $table_flight_tbody .= $row_entries['brewStyle'];
									else $table_flight_tbody .= $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1,$base_url)."</em>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									
									$special = style_convert($style_special,"9",$base_url);
									$special = explode("^",$special);

									if (($row_entries['brewInfo'] != "") && ((isset($special[4])) && ($special[4] == "1"))) {
										if (($_SESSION['prefsStyleSet'] == "BJCP2021") && ($style == "2A")) $table_flight_tbody .= "<p><strong>".$label_regional_variation.": </strong> ".str_replace("^"," | ",$row_entries['brewInfo'])."</p>";
										else $table_flight_tbody .= "<p><strong>".$label_required_info.": </strong> ".str_replace("^"," | ",$row_entries['brewInfo'])."</p>";
									}

									if ($row_entries['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>".$label_optional_info.": </strong> ".$row_entries['brewInfoOptional']."</p>";
									
									if ($row_entries['brewComments'] != "") $table_flight_tbody .= "<p><strong>".$label_brewer_specifics.": </strong> ".$row_entries['brewComments']."</p>";

									$table_flight_tbody .= "<ul class=\"list-unstyled\">";
									
									if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<li><strong>".$label_carbonation.": </strong> ".$row_entries['brewMead1']."</li>";

									if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<li><strong>".$label_sweetness.":</strong> ".$row_entries['brewMead2']."</li>";

									if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<li><strong>".$label_strength.":</strong> ".$row_entries['brewMead3'];
									$table_flight_tbody .= "</li>";

									if (!empty($row_entries['brewPossAllergens'])) $table_flight_tbody .= "<li><strong>".$label_possible_allergens.":</strong> ".$row_entries['brewPossAllergens']."</li>";

									if (!empty($row_entries['brewABV'])) $table_flight_tbody .= "<li><strong>".$label_abv.":</strong> ".$row_entries['brewABV']."</li>";

									/*

									if (($_SESSION['prefsStyleSet'] == "NWCiderCup") && (!empty($row_entries['brewJuiceSource']))) {
										  
										$juice_src_arr = json_decode($row_entries['brewJuiceSource'],true);
										$juice_src_disp = "";

										if (is_array($juice_src_arr['juice_src'])) {
											$juice_src_disp .= implode(", ",$juice_src_arr['juice_src']);
											$juice_src_disp .= ", ";
										}

										if ((isset($juice_src_arr['juice_src_other'])) && (is_array($juice_src_arr['juice_src_other']))) {
											$juice_src_disp .= implode(", ",$juice_src_arr['juice_src_other']);
											$juice_src_disp .= ", ";
										}

										$juice_src_disp = rtrim($juice_src_disp,",");
										$juice_src_disp = rtrim($juice_src_disp,", ");

										$table_flight_tbody .= "<li><strong>".$label_juice_source.":</strong> ".$juice_src_disp."</li>";

									}

									*/

									if (!empty($row_entries['brewPackaging'])) $table_flight_tbody .= "<li><strong>".$label_packaging.":</strong> ".$packaging_display[$row_entries['brewPackaging']]."</li>";

									if ((!empty($row_entries['brewPouring'])) && ((!empty($row_entries['brewStyleType'])) && ($row_entries['brewStyleType'] == 1))) {
										$pouring_arr = json_decode($row_entries['brewPouring'],true);
										$table_flight_tbody .= "<li><strong>".$label_pouring.":</strong> ".$pouring_arr['pouring']."</li>";
										if ((isset($pouring_arr['pouring_notes'])) && (!empty($pouring_arr['pouring_notes']))) $table_flight_tbody .= "<li><strong>".$label_pouring_notes.":</strong> ".$pouring_arr['pouring_notes']."</li>";
										$table_flight_tbody .= "<li><strong>".$label_rouse_yeast.":</strong> ".$pouring_arr['pouring_rouse']."</li>";
									}

									if (!empty($row_entries['brewStaffNotes'])) $table_flight_tbody .= "<li><strong>".$label_notes.":</strong> ".$row_entries['brewStaffNotes']."</li>";
									
									$table_flight_tbody .= "</ul>";

									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= $row_entries['brewBoxNum'];;
									$table_flight_tbody .= "</td>";
									if ($filter != "mini_bos") {
										$table_flight_tbody .= "<td>";
										$table_flight_tbody .= "<p class=\"box_small\">";
										$table_flight_tbody .= "</td>";
									}
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= "<p>&nbsp;</p>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= "<p>&nbsp;</p>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "</tr>";

								}

								$table_flight .= $table_flight_tbody;

							} while ($row_entries = mysqli_fetch_assoc($entries));

						} // end foreach

						$table_flight .= "</tbody>";
						$table_flight .= "</table>";
						$pullsheet_output .= $table_flight;
						if (($flights > 0) && ($filter != "mini_bos")) $pullsheet_output .= "<div style=\"page-break-after:always;\"></div>";

					} // end for($i=1; $i<$flights+1; $i++)

					if (($flights == 0) || ($filter == "mini_bos")) $pullsheet_output .= "<div style=\"page-break-after:always;\"></div>";

				} while ($row_tables = mysqli_fetch_assoc($tables));
			}

		} // end if ($tables_all)

		// Or just a single table
		else {

			// Reset Vars
			$pullsheet_output = "";
			$table_info_location = "";
			$table_info_notes = "";
			$table_info_header = "";

			$flights = number_of_flights($row_tables['id']);
			if ($flights > 0) $flights = $flights; else $flights = "0";

			$table_info_header .= "<div class=\"page-header\">";
			$table_info_header .= "<h1>";
			$table_info_header .= sprintf("%s %s: %s",$label_table,$row_tables['tableNumber'],$row_tables['tableName']);
			if ($filter == "mini_bos") $table_info_header .= sprintf(" - %s",$label_mini_bos);
			$table_info_header .= "</h1>";
			$table_info_header .= "</div>";

			if (!empty($row_tables['tableLocation'])) {

				$table_info_location .= "<h2>";
				$table_info_location .= table_location($row_tables['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default");
				if ($round != "default") $table_info_location .= sprintf("<br>%s %s",$label_round,$round);
				$table_info_location .= "</h2>";
				$table_info_location .= "<p class=\"lead\">";
				$table_info_location .= sprintf("%s: %s<br>%s: %s",$label_entries,get_table_info(1,"count_total",$row_tables['id'],$dbTable,"default"),$label_flights,$flights);
				$table_info_location .= "</p>";
				$table_info_location .= "<p class=\"hidden-print\">".$label_please_note."</p>";
				$table_info_location .= "<ul class=\"hidden-print\">";
				$table_info_location .= "<li>".$output_text_017."</li>";
				$table_info_location .= "<li>".$output_text_018."</li>";
				$table_info_location .= "</ul>";

			}

			$pullsheet_output .= $table_info_header;

			// If MBOS, add datatables and table, and table header
			if ($filter == "mini_bos") {
				$pullsheet_output .= "<script>";
				$pullsheet_output .= "$(document).ready(function() {";
				$pullsheet_output .= "$('#sortable".$row_tables['tableNumber']."').dataTable( {";
				$pullsheet_output .= "\"bPaginate\" : false,";
				$pullsheet_output .= "\"sDom\": 'rt',";
				$pullsheet_output .= "\"bStateSave\" : false,";
				$pullsheet_output .= "\"bLengthChange\" : false,";
				$pullsheet_output .= "\"aaSorting\": [[2,'asc'],[1,'asc']],";
				$pullsheet_output .= "\"bProcessing\" : false,";
				$pullsheet_output .= "\"aoColumns\": [";
				$pullsheet_output .= "{ \"asSorting\": [  ] },";
				$pullsheet_output .= "{ \"asSorting\": [  ] },";
				$pullsheet_output .= "{ \"asSorting\": [  ] },";
				$pullsheet_output .= "{ \"asSorting\": [  ] },";
				$pullsheet_output .= "{ \"asSorting\": [  ] },";
				$pullsheet_output .= "{ \"asSorting\": [  ] },";
				$pullsheet_output .= "{ \"asSorting\": [  ] }";
				$pullsheet_output .= "]";
				$pullsheet_output .= "} );";
				$pullsheet_output .= "} );";
				$pullsheet_output .= "</script>";

				$pullsheet_output .= "<table class=\"table table-striped table-bordered\" id=\"sortable".$row_tables['tableNumber']."\">";
				$pullsheet_output .= "<thead>";
				$pullsheet_output .= $table_flight_thead;
				$pullsheet_output .= "</thead>";
				$pullsheet_output .= "<tbody>";

			}

			else $pullsheet_output .= $table_info_location;

			// Loop through flights. Gather entry information

			for($i=1; $i<$flights+1; $i++) {

				$table_flight = "";
				$table_flight_datatables = "";


				if ($filter != "mini_bos") {

					$random = random_generator(5,2);

					$query_round_check = sprintf("SELECT flightRound FROM %s WHERE flightTable='%s' AND flightNumber='%s' LIMIT 1", $prefix."judging_flights", $row_tables['id'],$i);
					$round_check = mysqli_query($connection,$query_round_check) or die (mysqli_error($connection));
					$row_round_check = mysqli_fetch_assoc($round_check);

					$table_flight .= "<h3>".sprintf("%s %s: %s - %s %s, %s %s",$label_table,$row_tables['tableNumber'],$row_tables['tableName'],$label_flight,$i,$label_round,$row_round_check['flightRound'])."</h3>";

					$table_flight_datatables .= "<script>";
					$table_flight_datatables .= "$(document).ready(function() {";
					$table_flight_datatables .= "$('#sortable".$random."').dataTable( {";
					$table_flight_datatables .= "\"bPaginate\" : false,";
					$table_flight_datatables .= "\"sDom\": 'rt',";
					$table_flight_datatables .= "\"bStateSave\" : false,";
					$table_flight_datatables .= "\"bLengthChange\" : false,";
					$table_flight_datatables .= "\"aaSorting\": [[2,'asc'],[1,'asc']],";
					$table_flight_datatables .= "\"bProcessing\" : false,";
					$table_flight_datatables .= "\"aoColumns\": [";
					$table_flight_datatables .= "{ \"asSorting\": [  ] },";
					$table_flight_datatables .= "{ \"asSorting\": [  ] },";
					$table_flight_datatables .= "{ \"asSorting\": [  ] },";
					$table_flight_datatables .= "{ \"asSorting\": [  ] },";
					$table_flight_datatables .= "{ \"asSorting\": [  ] },";
					$table_flight_datatables .= "{ \"asSorting\": [  ] },";
					$table_flight_datatables .= "{ \"asSorting\": [  ] },";
					$table_flight_datatables .= "{ \"asSorting\": [  ] }";
					$table_flight_datatables .= "]";
					$table_flight_datatables .= "} );";
					$table_flight_datatables .= "} );";
					$table_flight_datatables .= "</script>";

					$table_flight .= $table_flight_datatables;
					$table_flight .= "<table class=\"table table-striped table-bordered\" id=\"sortable".$random."\">";
					$table_flight .= "<thead>";
					$table_flight .= $table_flight_thead;
					$table_flight .= "</thead>";
					$table_flight .= "<tbody>";
				}

				$a = explode(",", $row_tables['tableStyles']);
				//print_r($a);
				
				foreach (array_unique($a) as $value) {

					include (DB.'output_pullsheets_entries.db.php');

					$table_flight_tbody = "";

					if (!empty($row_entries)) {

						$style = style_number_const($row_entries['brewCategorySort'],$row_entries['brewSubCategory'],$_SESSION['style_set_display_separator'],0);
						$style_special = $row_entries['brewCategorySort']."^".$row_entries['brewSubCategory']."^".$_SESSION['prefsStyleSet'];

						do {

							$table_flight_tbody = "";

							$flight_round = check_flight_number($row_entries['id'],$i,0);

							if (check_flight_round($flight_round,$round)) {

								$table_flight_tbody .= "<tr>";

								// Pull Order
								$table_flight_tbody .= "<td>";
								$table_flight_tbody .= "<p>&nbsp;</p>";
								$table_flight_tbody .= "</td>";

								// Entry or Judging Number
								$table_flight_tbody .= "<td>";
								if ($view == "entry")  $table_flight_tbody .= sprintf("%06s",$row_entries['id']);
								else $table_flight_tbody .= sprintf("%06s",$row_entries['brewJudgingNumber']);
								$table_flight_tbody .= "</td>";

								// Style
								$table_flight_tbody .= "<td>";
								if ($_SESSION['prefsStyleSet'] == "BA") $table_flight_tbody .= $row_entries['brewStyle'];
								else $table_flight_tbody .= $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1,$base_url)."</em>";
								$table_flight_tbody .= "</td>";
								
								// Entry Info
								$table_flight_tbody .= "<td>";
								$special = style_convert($style_special,"9",$base_url);
								$special = explode("^",$special);

								if (($row_entries['brewInfo'] != "") && ($special[4] == "1")) {
									if (($_SESSION['prefsStyleSet'] == "BJCP2021") && ($style == "2A")) $table_flight_tbody .= "<p><strong>".$label_regional_variation.": </strong> ".str_replace("^"," | ",$row_entries['brewInfo'])."</p>";
									else $table_flight_tbody .= "<p><strong>".$label_required_info.": </strong> ".str_replace("^"," | ",$row_entries['brewInfo'])."</p>";
								}
								
								if ($row_entries['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>".$label_optional_info.": </strong>".$row_entries['brewInfoOptional']."</p>";
								
								if ($row_entries['brewComments'] != "") $table_flight_tbody .= "<p><strong>".$label_brewer_specifics.": </strong> ".$row_entries['brewComments']."</p>";

								$table_flight_tbody .= "<ul class=\"list-unstyled\">";

								if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<li><strong>".$label_carbonation.": </strong> ".$row_entries['brewMead1']."</li>";
								if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<li><strong>".$label_sweetness.":</strong> ".$row_entries['brewMead2']."</li>";
								if (($_SESSION['prefsStyleSet'] == "NWCiderCup") && (!empty($row_entries['brewSweetnessLevel']))) $table_flight_tbody .= "<li><strong>".$label_final_gravity.":</strong> ".$row_entries['brewSweetnessLevel']."</li>";
								if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<li><strong>".$label_strength.":</strong> ".$row_entries['brewMead3']."</li>";

								if (!empty($row_entries['brewPossAllergens'])) $table_flight_tbody .= "<li><strong>".$label_possible_allergens.":</strong> ".$row_entries['brewPossAllergens']."</li>";

								if (!empty($row_entries['brewABV'])) $table_flight_tbody .= "<li><strong>".$label_abv.":</strong> ".$row_entries['brewABV']."</li>";

								/*	
								
								if (($_SESSION['prefsStyleSet'] == "NWCiderCup") && (!empty($row_entries['brewJuiceSource']))) {
									  
									$juice_src_arr = json_decode($row_entries['brewJuiceSource'],true);
									$juice_src_disp = "";

									if (is_array($juice_src_arr['juice_src'])) {
										$juice_src_disp .= implode(", ",$juice_src_arr['juice_src']);
										$juice_src_disp .= ", ";
									}

									if ((isset($juice_src_arr['juice_src_other'])) && (is_array($juice_src_arr['juice_src_other']))) {
										$juice_src_disp .= implode(", ",$juice_src_arr['juice_src_other']);
										$juice_src_disp .= ", ";
									}

									$juice_src_disp = rtrim($juice_src_disp,",");
									$juice_src_disp = rtrim($juice_src_disp,", ");

									$table_flight_tbody .= "<li><strong>".$label_juice_source.":</strong> ".$juice_src_disp."</li>";

								}

								*/

								if (!empty($row_entries['brewPackaging'])) $table_flight_tbody .= "<li><strong>".$label_packaging.":</strong> ".$packaging_display[$row_entries['brewPackaging']]."</li>";

								if ((!empty($row_entries['brewPouring'])) && ((!empty($row_entries['brewStyleType'])) && ($row_entries['brewStyleType'] == 1))) {
									$pouring_arr = json_decode($row_entries['brewPouring'],true);
									$table_flight_tbody .= "<li><strong>".$label_pouring.":</strong> ".$pouring_arr['pouring']."</li>";
									if ((isset($pouring_arr['pouring_notes'])) && (!empty($pouring_arr['pouring_notes']))) $table_flight_tbody .= "<li><strong>".$label_pouring_notes.":</strong> ".$pouring_arr['pouring_notes']."</li>";
									$table_flight_tbody .= "<li><strong>".$label_rouse_yeast.":</strong> ".$pouring_arr['pouring_rouse']."</li>";
								}

								if (!empty($row_entries['brewStaffNotes'])) $table_flight_tbody .= "<li><strong>".$label_notes.":</strong> ".$row_entries['brewStaffNotes']."</li>";
										
								$table_flight_tbody .= "</ul>";
								$table_flight_tbody .= "</td>";
								
								// Box/Location
								$table_flight_tbody .= "<td>";
								$table_flight_tbody .= $row_entries['brewBoxNum'];;
								$table_flight_tbody .= "</td>";

								// Mini-BOS
								if ($filter != "mini_bos") {
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= "<p class=\"box_small\">";
									if (($filter == "mini_bos") && ($row_entries['scoreMiniBOS'] == 1)) $table_flight_tbody .= "<span class=\"fa fa-check\"></span>";
									else $table_flight_tbody .= "&nbsp;";
									$table_flight_tbody .= "</td>";
								}

								// Score
								$table_flight_tbody .= "<td>";
								$table_flight_tbody .= "<p>&nbsp;</p>";
								$table_flight_tbody .= "</td>";

								// Place
								$table_flight_tbody .= "<td>";
								$table_flight_tbody .= "<p>&nbsp;</p>";
								$table_flight_tbody .= "</td>";
								$table_flight_tbody .= "</tr>";

							}

							$table_flight .= $table_flight_tbody;

						} while ($row_entries = mysqli_fetch_assoc($entries));

					} // if (!empty($row_entries))

				} // end foreach

				if ($filter != "mini_bos") {
					$table_flight .= "</tbody>";
					$table_flight .= "</table>";
				}

				$pullsheet_output .= $table_flight;

			} // end for($i=1; $i<$flights+1; $i++)

			if ($filter == "mini_bos") {
				$pullsheet_output .= "</tbody>";
				$pullsheet_output .= "</table>";
			}

			$pullsheet_output .= "<div style=\"page-break-after:always;\"></div>";

		} // end else

	} // end if (!$queued)

} // end elseif (($go != "judging_scores_bos") && ($go != "mini_bos") && ($go != "all_entry_info"))

echo $pullsheet_output;
?>