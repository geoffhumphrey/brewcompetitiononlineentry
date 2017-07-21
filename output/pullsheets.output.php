<?php

require(DB.'admin_common.db.php');
include (LIB.'output.lib.php');
include (DB.'output_pullsheets.db.php');
include (INCLUDES.'scrubber.inc.php');

$queued = FALSE;
$tables_none = FALSE;
$tables_all = FALSE;

if ($_SESSION['jPrefsQueued'] == "Y") $queued = TRUE;
if (($go == "judging_tables") && ($totalRows_tables == 0)) $tables_none = TRUE;
if ((($go == "judging_tables") || ($go == "judging_locations")) && ($id == "default")) $tables_all = TRUE;

$table_flight_thead = "";
$table_flight_thead .= "<tr>";
$table_flight_thead .= "<th width=\"5%\" nowrap>".$label_pull_order."</th>";
$table_flight_thead .= "<th width=\"5%\">#</th>";
$table_flight_thead .= "<th width=\"35%\">".$label_style."</th>";
$table_flight_thead .= "<th width=\"35%\">".$label_info."</th>";
$table_flight_thead .= "<th width=\"5%\" nowrap>".$label_box."</th>";
if ($go != "judging_scores_bos") $table_flight_thead .= "<th width=\"5%\" nowrap>".$label_mini_bos."</th>";
$table_flight_thead .= "<th width=\"5%\" nowrap>".$label_score."</th>";
$table_flight_thead .= "<th width=\"5%\" nowrap>".$label_place."</th>";
$table_flight_thead .= "</tr>";

$pullsheet_output = "";

if ($go == "judging_scores_bos") { 
	
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
				$table_flight .= "<table class=\"table table-striped table-bordered\" id=\"sortable".$type."\">";
				$table_flight .= "<thead>";
				$table_flight .= $table_flight_thead;
				$table_flight .= "</thead>";
				$table_flight .= "<tbody>";
				
				do {
					
					include (DB.'output_pullsheets_bos_entries.db.php');
					
					$style = $row_entries_1['brewCategorySort'].$row_entries_1['brewSubCategory'];
					$style_special = $row_entries_1['brewCategorySort']."^".$row_entries_1['brewSubCategory']."^".$_SESSION['prefsStyleSet'];
					
					if (!empty($row_entries_1['brewCategorySort'])) {
					
						$table_flight_tbody .= "<tr>";
						
						$table_flight_tbody .= "<td>";
						$table_flight_tbody .= "<p class=\"box\">&nbsp;</p>";
						$table_flight_tbody .= "</td>";
						$table_flight_tbody .= "<td>";
						
						if ($view == "entry")  $table_flight_tbody .= sprintf("%04s",$row_entries_1['id']);
						elseif (((NHC) && ($view != "entry")) || ($_SESSION['prefsEntryForm'] == "N"))  $table_flight_tbody .= sprintf("%06s",$row_entries_1['brewJudgingNumber']);
						else  $table_flight_tbody .= readable_judging_number($row_entries_1['brewCategory'],$row_entries_1['brewJudgingNumber']);
						$table_flight_tbody .= "</td>";
						
						$table_flight_tbody .= "<td>";
						if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) $table_flight_tbody .= $style." ".$row_entries_1['brewStyle']."<em><br>".style_convert($row_entries_1['brewCategorySort'],1)."</em>";
						else $table_flight_tbody .= $row_entries_1['brewStyle'];
						$table_flight_tbody .= "</td>";
						
						$table_flight_tbody .= "<td>";
						$special = style_convert($style_special,"9");
						$special = explode("^",$special);
						if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) {
							if (($row_entries_1['brewInfo'] != "") && ($special[4] == "1")) $table_flight_tbody .= "<p><strong>Required Info: </strong>".str_replace("^","; ",$row_entries_1['brewInfo'])."</p>";
							if ($row_entries_1['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>Optional Info: </strong>".$row_entries_1['brewInfoOptional']."</p>";
							if ($row_entries_1['brewComments'] != "") $table_flight_tbody .= "<p><strong>Specifics: </strong>".$row_entries_1['brewComments']."</p>";
							if (style_convert($style,"5")) {
								$table_flight_tbody .= "<p>";
								if (!empty($row_entries_1['brewMead1'])) $table_flight_tbody .= "<strong>Carbonation:</strong> ".$row_entries_1['brewMead1']."<br>";
								if (!empty($row_entries_1['brewMead2'])) $table_flight_tbody .= "<strong>Sweetness:</strong> ".$row_entries_1['brewMead2']."<br>";
								if (!empty($row_entries_1['brewMead3'])) $table_flight_tbody .= "<strong>Strength:</strong> ".$row_entries_1['brewMead3'];
								$table_flight_tbody .= "</p>";
							}
						}
						
						else {
							include (INCLUDES.'ba_constants.inc.php');
							$value = $row_entries_1['id'];
							if (in_array($value,$ba_special_ids)) $table_flight_tbody .= "<p><strong>Required Info: </strong>".str_replace("^","; ",$row_entries_1['brewInfo'])."</p>";
							if ($row_entries_1['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>Optional Info: </strong>".$row_entries_1['brewInfoOptional']."</p>";
							if ($row_entries_1['brewComments'] != "") $table_flight_tbody .= "<p><strong>Specifics: </strong>".$row_entries_1['brewComments']."</p>";
							if (in_array($value,$ba_mead_cider)) {
								$table_flight_tbody .= "<p>";
								if (!empty($row_entries_1['brewMead1'])) $table_flight_tbody .= "<strong>Carbonation:</strong> ".$row_entries_1['brewMead1']."<br>";
								if (!empty($row_entries_1['brewMead2'])) $table_flight_tbody .= "<strong>Sweetness:</strong> ".$row_entries_1['brewMead2']."<br>";
								if (!empty($row_entries_1['brewMead3'])) $table_flight_tbody .= "<strong>Strength:</strong> ".$row_entries_1['brewMead3'];
								$table_flight_tbody .= "</p>";
							}
						}
						$table_flight_tbody .= "</td>";
						
						$table_flight_tbody .= "<td>";
						$table_flight_tbody .= $row_entries_1['brewBoxNum'];
						$table_flight_tbody .= "</td>";
						$table_flight_tbody .= "<td>";
						$table_flight_tbody .= "<p class=\"box\">&nbsp;</p>";
						$table_flight_tbody .= "</td>";
						$table_flight_tbody .= "<td>";
						$table_flight_tbody .= "<p class=\"box\">&nbsp;</p>";
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

} 

else {
	
	// If using queued judging (no flights)
	if ($queued) {
	
		if ($tables_all) {
	
			$pullsheet_output = "";
	
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
						$table_flight_datatables .= "{ \"asSorting\": [  ] },";
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
							$style = $row_entries['brewCategorySort'].$row_entries['brewSubCategory'];
							$style_special = $row_entries['brewCategorySort']."^".$row_entries['brewSubCategory']."^".$_SESSION['prefsStyleSet'];					
							
							do {
	
								
	
								if (!empty($row_entries['brewCategorySort'])) {
	
									$table_flight_tbody .= "<tr>";
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= "<p class=\"box\">&nbsp;</p>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									if ($view == "entry")  $table_flight_tbody .= sprintf("%04s",$row_entries['id']);
									elseif (((NHC) && ($view != "entry")) || ($_SESSION['prefsEntryForm'] == "N"))  $table_flight_tbody .= sprintf("%06s",$row_entries['brewJudgingNumber']);
									else  $table_flight_tbody .= readable_judging_number($row_entries['brewCategory'],$row_entries['brewJudgingNumber']);
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) $table_flight_tbody .= $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1)."</em>";
									else $table_flight_tbody .= $row_entries['brewStyle'];
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
	
									$special = style_convert($style_special,"9");
									$special = explode("^",$special);
									if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) {
										if (($row_entries['brewInfo'] != "") && ($special[4] == "1")) $table_flight_tbody .= "<p><strong>Required Info: </strong>".str_replace("^","; ",$row_entries['brewInfo'])."</p>";
										if ($row_entries['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>Optional Info: </strong>".$row_entries['brewInfoOptional']."</p>";
										if ($row_entries['brewComments'] != "") $table_flight_tbody .= "<p><strong>Specifics: </strong>".$row_entries['brewComments']."</p>";
										if (style_convert($style,"5")) {
											$table_flight_tbody .= "<p>";
											if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<strong>Carbonation:</strong> ".$row_entries['brewMead1']."<br>";
											if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<strong>Sweetness:</strong> ".$row_entries['brewMead2']."<br>";
											if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<strong>Strength:</strong> ".$row_entries['brewMead3'];
											$table_flight_tbody .= "</p>";
										}
									}
									else {
										include (INCLUDES.'ba_constants.inc.php');
										if (in_array($value,$ba_special_ids)) $table_flight_tbody .= "<p><strong>Required Info: </strong>".str_replace("^","; ",$row_entries['brewInfo'])."</p>";
										if ($row_entries['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>Optional Info: </strong>".$row_entries['brewInfoOptional']."</p>";
										if ($row_entries['brewComments'] != "") $table_flight_tbody .= "<p><strong>Specifics: </strong>".$row_entries['brewComments']."</p>";
										if (in_array($value,$ba_mead_cider)) {
											$table_flight_tbody .= "<p>";
											if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<strong>Carbonation:</strong> ".$row_entries['brewMead1']."<br>";
											if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<strong>Sweetness:</strong> ".$row_entries['brewMead2']."<br>";
											if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<strong>Strength:</strong> ".$row_entries['brewMead3'];
											$table_flight_tbody .= "</p>";
										}
									}
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= $row_entries['brewBoxNum'];;
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= "<p class=\"box_small\">";
									if (($filter == "mini_bos") && ($row_entries['scoreMiniBOS'] == 1)) $table_flight_tbody .= "<span class=\"fa fa-check\"></span>";
									else $table_flight_tbody .= "&nbsp;";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= "<p class=\"box\">&nbsp;</p>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= "<p class=\"box\">&nbsp;</p>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "</tr>";
	
								}
								
							} while ($row_entries = mysqli_fetch_assoc($entries));
	
						} // end foreach
	
						$table_flight .= $table_flight_tbody;
						$table_flight .= "</tbody>";
						$table_flight .= "</table>";
						$table_flight .= "<div style=\"page-break-after:always;\"></div>";
	
					} // end  if ($entry_count > 0)
	
				} // end if (($row_table_round['count'] >= 1) || ($round == "default"))
	
				$pullsheet_output .= $table_flight;
	
			} while ($row_tables = mysqli_fetch_assoc($tables));
	
		}
	
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
					$table_flight_datatables .= "{ \"asSorting\": [  ] },";
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
						$style = $row_entries['brewCategorySort'].$row_entries['brewSubCategory'];
						$style_special = $row_entries['brewCategorySort']."^".$row_entries['brewSubCategory']."^".$_SESSION['prefsStyleSet'];					
						
						do {
	
							
	
							if (!empty($row_entries['brewCategorySort'])) {
	
								$table_flight_tbody .= "<tr>";
								$table_flight_tbody .= "<td>";
								$table_flight_tbody .= "<p class=\"box\">&nbsp;</p>";
								$table_flight_tbody .= "</td>";
								$table_flight_tbody .= "<td>";
								if ($view == "entry")  $table_flight_tbody .= sprintf("%04s",$row_entries['id']);
								elseif (((NHC) && ($view != "entry")) || ($_SESSION['prefsEntryForm'] == "N"))  $table_flight_tbody .= sprintf("%06s",$row_entries['brewJudgingNumber']);
								else  $table_flight_tbody .= readable_judging_number($row_entries['brewCategory'],$row_entries['brewJudgingNumber']);
								$table_flight_tbody .= "</td>";
								$table_flight_tbody .= "<td>";
								if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) $table_flight_tbody .= $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1)."</em>";
								else $table_flight_tbody .= $row_entries['brewStyle'];
								$table_flight_tbody .= "</td>";
								$table_flight_tbody .= "<td>";
	
								$special = style_convert($style_special,"9");
								$special = explode("^",$special);
								if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) {
									if (($row_entries['brewInfo'] != "") && ($special[4] == "1")) $table_flight_tbody .= "<p><strong>Required Info: </strong>".str_replace("^","; ",$row_entries['brewInfo'])."</p>";
									if ($row_entries['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>Optional Info: </strong>".$row_entries['brewInfoOptional']."</p>";
									if ($row_entries['brewComments'] != "") $table_flight_tbody .= "<p><strong>Specifics: </strong>".$row_entries['brewComments']."</p>";
									if (style_convert($style,"5")) {
										$table_flight_tbody .= "<p>";
										if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<strong>Carbonation:</strong> ".$row_entries['brewMead1']."<br>";
										if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<strong>Sweetness:</strong> ".$row_entries['brewMead2']."<br>";
										if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<strong>Strength:</strong> ".$row_entries['brewMead3'];
										$table_flight_tbody .= "</p>";
									}
								}
								else {
									include (INCLUDES.'ba_constants.inc.php');
									if (in_array($value,$ba_special_ids)) $table_flight_tbody .= "<p><strong>Required Info: </strong>".str_replace("^","; ",$row_entries['brewInfo'])."</p>";
									if ($row_entries['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>Optional Info: </strong>".$row_entries['brewInfoOptional']."</p>";
									if ($row_entries['brewComments'] != "") $table_flight_tbody .= "<p><strong>Specifics: </strong>".$row_entries['brewComments']."</p>";
									if (in_array($value,$ba_mead_cider)) {
										$table_flight_tbody .= "<p>";
										if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<strong>Carbonation:</strong> ".$row_entries['brewMead1']."<br>";
										if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<strong>Sweetness:</strong> ".$row_entries['brewMead2']."<br>";
										if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<strong>Strength:</strong> ".$row_entries['brewMead3'];
										$table_flight_tbody .= "</p>";
									}
								}
								$table_flight_tbody .= "</td>";
								$table_flight_tbody .= "<td>";
								$table_flight_tbody .= $row_entries['brewBoxNum'];;
								$table_flight_tbody .= "</td>";
								$table_flight_tbody .= "<td>";
								$table_flight_tbody .= "<p class=\"box_small\">";
								if (($filter == "mini_bos") && ($row_entries['scoreMiniBOS'] == 1)) $table_flight_tbody .= "<span class=\"fa fa-check\"></span>";
								else $table_flight_tbody .= "&nbsp;";
								$table_flight_tbody .= "</td>";
								$table_flight_tbody .= "<td>";
								$table_flight_tbody .= "<p class=\"box\">&nbsp;</p>";
								$table_flight_tbody .= "</td>";
								$table_flight_tbody .= "<td>";
								$table_flight_tbody .= "<p class=\"box\">&nbsp;</p>";
								$table_flight_tbody .= "</td>";
								$table_flight_tbody .= "</tr>";
	
							}
							
						} while ($row_entries = mysqli_fetch_assoc($entries));
	
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
					$table_info_location .= "<p>".$label_please_note."</p>";
					$table_info_location .= "<ul>";
					$table_info_location .= "<li>".$output_text_017."</li>";
					$table_info_location .= "<li>".$output_text_018."</li>";
					$table_info_location .= "</ul>";
	
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
						$style = $row_entries['brewCategorySort'].$row_entries['brewSubCategory'];
						$style_special = $row_entries['brewCategorySort']."^".$row_entries['brewSubCategory']."^".$_SESSION['prefsStyleSet'];
						
						do {
		
							$table_flight_tbody = "";
		
							$flight_round = check_flight_number($row_entries['id'],$i);
		
							if (check_flight_round($flight_round,$round)) {
		
								$table_flight_tbody .= "<tr>";
								$table_flight_tbody .= "<td>";
								$table_flight_tbody .= "<p class=\"box\">&nbsp;</p>";
								$table_flight_tbody .= "</td>";
								$table_flight_tbody .= "<td>";
								if ($view == "entry")  $table_flight_tbody .= sprintf("%04s",$row_entries['id']);
								elseif (((NHC) && ($view != "entry")) || ($_SESSION['prefsEntryForm'] == "N"))  $table_flight_tbody .= sprintf("%06s",$row_entries['brewJudgingNumber']);
								else  $table_flight_tbody .= readable_judging_number($row_entries['brewCategory'],$row_entries['brewJudgingNumber']);
								$table_flight_tbody .= "</td>";
								$table_flight_tbody .= "<td>";
								if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) $table_flight_tbody .= $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1)."</em>";
								else $table_flight_tbody .= $row_entries['brewStyle'];
								$table_flight_tbody .= "</td>";
								$table_flight_tbody .= "<td>";
		
								$special = style_convert($style_special,"9");
								$special = explode("^",$special);
								if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) {
									if (($row_entries['brewInfo'] != "") && ($special[4] == "1")) $table_flight_tbody .= "<p><strong>Required Info: </strong>".str_replace("^","; ",$row_entries['brewInfo'])."</p>";
									if ($row_entries['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>Optional Info: </strong>".$row_entries['brewInfoOptional']."</p>";
									if ($row_entries['brewComments'] != "") $table_flight_tbody .= "<p><strong>Specifics: </strong>".$row_entries['brewComments']."</p>";
									if (style_convert($style,"5")) {
										$table_flight_tbody .= "<p>";
										if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<strong>Carbonation:</strong> ".$row_entries['brewMead1']."<br>";
										if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<strong>Sweetness:</strong> ".$row_entries['brewMead2']."<br>";
										if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<strong>Strength:</strong> ".$row_entries['brewMead3'];
										$table_flight_tbody .= "</p>";
									}
								}
								else {
									include (INCLUDES.'ba_constants.inc.php');
									if (in_array($value,$ba_special_ids)) $table_flight_tbody .= "<p><strong>Required Info: </strong>".str_replace("^","; ",$row_entries['brewInfo'])."</p>";
									if ($row_entries['brewComments'] != "") $table_flight_tbody .= "<p><strong>Specifics: </strong>".$row_entries['brewComments']."</p>";
									if (in_array($value,$ba_mead_cider)) {
										$table_flight_tbody .= "<p>";
										if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<strong>Carbonation:</strong> ".$row_entries['brewMead1']."<br>";
										if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<strong>Sweetness:</strong> ".$row_entries['brewMead2']."<br>";
										if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<strong>Strength:</strong> ".$row_entries['brewMead3'];
										$table_flight_tbody .= "</p>";
									}
								}
								$table_flight_tbody .= "</td>";
								$table_flight_tbody .= "<td>";
								$table_flight_tbody .= $row_entries['brewBoxNum'];;
								$table_flight_tbody .= "</td>";
								$table_flight_tbody .= "<td>";
								$table_flight_tbody .= "<p class=\"box_small\">";
								if (($filter == "mini_bos") && ($row_entries['scoreMiniBOS'] == 1)) $table_flight_tbody .= "<span class=\"fa fa-check\"></span>";
								else $table_flight_tbody .= "&nbsp;";
								$table_flight_tbody .= "</td>";
								$table_flight_tbody .= "<td>";
								$table_flight_tbody .= "<p class=\"box\">&nbsp;</p>";
								$table_flight_tbody .= "</td>";
								$table_flight_tbody .= "<td>";
								$table_flight_tbody .= "<p class=\"box\">&nbsp;</p>";
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
				$table_info_location .= "<p>".$label_please_note."</p>";
				$table_info_location .= "<ul>";
				$table_info_location .= "<li>".$output_text_017."</li>";
				$table_info_location .= "<li>".$output_text_018."</li>";
				$table_info_location .= "</ul>";
	
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
	
				$a = explode(",", $row_tables['tableStyles']);
				//print_r($a);
				foreach (array_unique($a) as $value) {
	
					include (DB.'output_pullsheets_entries.db.php');
					$style = $row_entries['brewCategorySort'].$row_entries['brewSubCategory'];
					$style_special = $row_entries['brewCategorySort']."^".$row_entries['brewSubCategory']."^".$_SESSION['prefsStyleSet'];
					
					do {
	
						$table_flight_tbody = "";
	
						$flight_round = check_flight_number($row_entries['id'],$i);
	
						if (check_flight_round($flight_round,$round)) {
	
							$table_flight_tbody .= "<tr>";
							$table_flight_tbody .= "<td>";
							$table_flight_tbody .= "<p class=\"box\">&nbsp;</p>";
							$table_flight_tbody .= "</td>";
							$table_flight_tbody .= "<td>";
							if ($view == "entry")  $table_flight_tbody .= sprintf("%04s",$row_entries['id']);
							elseif (((NHC) && ($view != "entry")) || ($_SESSION['prefsEntryForm'] == "N"))  $table_flight_tbody .= sprintf("%06s",$row_entries['brewJudgingNumber']);
							else  $table_flight_tbody .= readable_judging_number($row_entries['brewCategory'],$row_entries['brewJudgingNumber']);
							$table_flight_tbody .= "</td>";
							$table_flight_tbody .= "<td>";
							if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) $table_flight_tbody .= $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1)."</em>";
							else $table_flight_tbody .= $row_entries['brewStyle'];
							$table_flight_tbody .= "</td>";
							$table_flight_tbody .= "<td>";
	
							$special = style_convert($style_special,"9");
							$special = explode("^",$special);
							if (strpos($_SESSION['prefsStyleSet'],"BABDB") === false) {
								if (($row_entries['brewInfo'] != "") && ($special[4] == "1")) $table_flight_tbody .= "<p><strong>Required Info: </strong>".str_replace("^","; ",$row_entries['brewInfo'])."</p>";
								if ($row_entries['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>Optional Info: </strong>".$row_entries['brewInfoOptional']."</p>";
								if ($row_entries['brewComments'] != "") $table_flight_tbody .= "<p><strong>Specifics: </strong>".$row_entries['brewComments']."</p>";
								if (style_convert($style,"5")) {
									$table_flight_tbody .= "<p>";
									if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<strong>Carbonation:</strong> ".$row_entries['brewMead1']."<br>";
									if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<strong>Sweetness:</strong> ".$row_entries['brewMead2']."<br>";
									if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<strong>Strength:</strong> ".$row_entries['brewMead3'];
									$table_flight_tbody .= "</p>";
								}
							}
							else {
								include (INCLUDES.'ba_constants.inc.php');
								if (in_array($value,$ba_special_ids)) $table_flight_tbody .= "<p><strong>Required Info: </strong>".str_replace("^","; ",$row_entries['brewInfo'])."</p>";
								if ($row_entries['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>Optional Info: </strong>".$row_entries['brewInfoOptional']."</p>";
								if ($row_entries['brewComments'] != "") $table_flight_tbody .= "<p><strong>Specifics: </strong>".$row_entries['brewComments']."</p>";
								if (in_array($value,$ba_mead_cider)) {
									$table_flight_tbody .= "<p>";
									if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<strong>Carbonation:</strong> ".$row_entries['brewMead1']."<br>";
									if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<strong>Sweetness:</strong> ".$row_entries['brewMead2']."<br>";
									if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<strong>Strength:</strong> ".$row_entries['brewMead3'];
									$table_flight_tbody .= "</p>";
								}
							}
							$table_flight_tbody .= "</td>";
							$table_flight_tbody .= "<td>";
							$table_flight_tbody .= $row_entries['brewBoxNum'];;
							$table_flight_tbody .= "</td>";
							$table_flight_tbody .= "<td>";
							$table_flight_tbody .= "<p class=\"box_small\">";
							if (($filter == "mini_bos") && ($row_entries['scoreMiniBOS'] == 1)) $table_flight_tbody .= "<span class=\"fa fa-check\"></span>";
							else $table_flight_tbody .= "&nbsp;";
							$table_flight_tbody .= "</td>";
							$table_flight_tbody .= "<td>";
							$table_flight_tbody .= "<p class=\"box\">&nbsp;</p>";
							$table_flight_tbody .= "</td>";
							$table_flight_tbody .= "<td>";
							$table_flight_tbody .= "<p class=\"box\">&nbsp;</p>";
							$table_flight_tbody .= "</td>";
							$table_flight_tbody .= "</tr>";
	
						}
	
						$table_flight .= $table_flight_tbody;
	
					} while ($row_entries = mysqli_fetch_assoc($entries));
	
				} // end foreach
	
				$table_flight .= "</tbody>";
				$table_flight .= "</table>";
				$pullsheet_output .= $table_flight;
				
			} // end for($i=1; $i<$flights+1; $i++)
	
			$pullsheet_output .= "<div style=\"page-break-after:always;\"></div>";
			
	
		} // end else
	
	} // end if (!$queued)
	
}

echo $pullsheet_output;
?>