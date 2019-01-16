<?php

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

if ($go == "all_entry_info") {

	$table_flight = "";
	$table_flight_tbody = "";

	$table_flight_thead .= "<tr>";
	$table_flight_thead .= "<th width=\"5%\">#</th>";
	$table_flight_thead .= "<th width=\"10%\" nowrap>".$label_style."</th>";
	$table_flight_thead .= "<th width=\"20%\" nowrap>".$label_required_info."</th>";
	$table_flight_thead .= "<th width=\"20%\" nowrap>".$label_optional_info."</th>";
	$table_flight_thead .= "<th nowrap>".$label_brewer_specifics."</th>";
	$table_flight_thead .= "<th width=\"15%\" nowrap>".$label_possible_allergens."</th>";
	$table_flight_thead .= "<th width=\"15%\" nowrap>".$label_notes."</th>";
	$table_flight_thead .= "</tr>";

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
			$table_info_header .= sprintf("%s %s: %s<br><small><em>%s</em></small>",$label_table,$row_tables['tableNumber'],$row_tables['tableName'],$label_additional_info);
			$table_info_header .= "</h1>";
			$table_info_header .= "</div>";

			if ((!empty($row_tables['tableLocation'])) && ($filter != "mini_bos")) {
				$table_info_location .= "<h2>";
				$table_info_location .= table_location($row_tables['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default");
				if ($round != "default") $table_info_location .= sprintf("<br>%s %s",$label_round,$round);
				$table_info_location .= "</h2>";
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

				$table_flight_tbody = "";

				$a = explode(",", $row_tables['tableStyles']);

				foreach (array_unique($a) as $value) {

					include (DB.'output_pullsheets_entries.db.php');
					$style = $row_entries['brewCategorySort'].$row_entries['brewSubCategory'];
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
							if ($_SESSION['prefsStyleSet'] != "BA") $table_flight_tbody .= $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1)."</em>";
							else $table_flight_tbody .= $row_entries['brewStyle'];
							$table_flight_tbody .= "</td>";

							$special = style_convert($style_special,"9");
							$special = explode("^",$special);
							$table_flight_tbody .= "<td>";
							if ((!empty($row_entries['brewInfo'])) && ($special[4] == "1")) $table_flight_tbody .= "<p>".str_replace("^"," | ",$row_entries['brewInfo'])."</p>";
							$table_flight_tbody .= "<p>";
							if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<strong>Carbonation:</strong> ".$row_entries['brewMead1']."<br>";
							if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<strong>Sweetness:</strong> ".$row_entries['brewMead2']."<br>";
							if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<strong>Strength:</strong> ".$row_entries['brewMead3'];
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

				$table_flight .= $table_flight_tbody;
				$table_flight .= "</tbody>";
				$table_flight .= "</table>";

			} // end  if ($entry_count > 0)

		} // end if (($row_table_round['count'] >= 1) || ($round == "default"))


		if (empty($table_flight_tbody))  $pullsheet_output .= "No entries available.";
		else $pullsheet_output .= $table_flight;

		$pullsheet_output .= "<div style=\"page-break-after:always;\"></div>";

	} while ($row_tables = mysqli_fetch_assoc($tables));

}

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

			$style = $row_entries_mini['brewCategorySort'].$row_entries_mini['brewSubCategory'];
			$style_special = $row_entries_mini['brewCategorySort']."^".$row_entries_mini['brewSubCategory']."^".$_SESSION['prefsStyleSet'];


			$special = style_convert($style_special,"9");
			$special = explode("^",$special);

			$table_flight_tbody .= "<tr>";
			$table_flight_tbody .= "<td>";
			$table_flight_tbody .= "<p class=\"box\">&nbsp;</p>";
			$table_flight_tbody .= "</td>";

			$table_flight_tbody .= "<td>";
			if ($view == "entry")  $table_flight_tbody .= sprintf("%06s",$row_entries_mini['id']);
			else $table_flight_tbody .= sprintf("%06s",$row_entries_mini['brewJudgingNumber']);
			$table_flight_tbody .= "</td>";

			$table_flight_tbody .= "<td>";
			if ($_SESSION['prefsStyleSet'] != "BA") $table_flight_tbody .= $style." ".$row_entries_mini['brewStyle']."<em><br>".style_convert($row_entries_mini['brewCategorySort'],1)."</em>";
			else $table_flight_tbody .= $row_entries_mini['brewStyle'];
			$table_flight_tbody .= "</td>";

			$table_flight_tbody .= "<td>";
			if (($row_entries_mini['brewInfo'] != "") && ($special[4] == "1")) $table_flight_tbody .= "<p><strong>".$label_required_info.":</strong> ".str_replace("^"," | ",$row_entries_mini['brewInfo'])."</p>";
			if ($row_entries_mini['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>".$label_optional_info.":</strong> ".$row_entries_mini['brewInfoOptional']."</p>";
			if ($row_entries_mini['brewComments'] != "") $table_flight_tbody .= "<p><strong>".$label_brewer_specifics.":</strong> ".$row_entries_mini['brewComments']."</p>";
			$table_flight_tbody .= "<p>";
			if (!empty($row_entries_mini['brewMead1'])) $table_flight_tbody .= "<strong>".$label_carbonation.":</strong> ".$row_entries_mini['brewMead1']."<br>";
			if (!empty($row_entries_mini['brewMead2'])) $table_flight_tbody .= "<strong>".$label_sweetness.":</strong> ".$row_entries_mini['brewMead2']."<br>";
			if (!empty($row_entries_mini['brewMead3'])) $table_flight_tbody .= "<strong>".$label_strength.":</strong> ".$row_entries_mini['brewMead3'];
			$table_flight_tbody .= "</p>";
			if (!empty($row_entries_mini['brewPossAllergens'])) $table_flight_tbody .= "<p><strong>".$label_possible_allergens.":</strong> ".$row_entries_mini['brewPossAllergens']."</p>";
			if (!empty($row_entries_mini['brewStaffNotes'])) $table_flight_tbody .= "<p><strong>".$label_notes.":</strong> ".$row_entries_mini['brewStaffNotes']."</p>";
			$table_flight_tbody .= "</td>";

			$table_flight_tbody .= "<td>";
			$table_flight_tbody .= $row_entries_mini['brewBoxNum'];
			$table_flight_tbody .= "</td>";

			$table_flight_tbody .= "<td>";
			$table_flight_tbody .= "<p class=\"box\">&nbsp;</p>";
			$table_flight_tbody .= "</td>";

			$table_flight_tbody .= "<td>";
			$table_flight_tbody .= "<p class=\"box\">&nbsp;</p>";
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

						if ($view == "entry")  $table_flight_tbody .= sprintf("%06s",$row_entries_1['id']);
						else $table_flight_tbody .= sprintf("%06s",$row_entries_1['brewJudgingNumber']);
						$table_flight_tbody .= "</td>";

						$table_flight_tbody .= "<td>";
						if ($_SESSION['prefsStyleSet'] != "BA") $table_flight_tbody .= $style." ".$row_entries_1['brewStyle']."<em><br>".style_convert($row_entries_1['brewCategorySort'],1)."</em>";
						else $table_flight_tbody .= $row_entries_1['brewStyle'];
						$table_flight_tbody .= "</td>";

						$table_flight_tbody .= "<td>";
						$special = style_convert($style_special,"9");
						$special = explode("^",$special);

						if (($row_entries_1['brewInfo'] != "") && ($special[4] == "1")) $table_flight_tbody .= "<p><strong>".$label_required_info.": </strong>".str_replace("^"," | ",$row_entries_1['brewInfo'])."</p>";
						if ($row_entries_1['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>".$label_optional_info.": </strong>".$row_entries_1['brewInfoOptional']."</p>";
						if ($row_entries_1['brewComments'] != "") $table_flight_tbody .= "<p><strong>".$label_brewer_specifics.": </strong>".$row_entries_1['brewComments']."</p>";
						$table_flight_tbody .= "<p>";
						if (!empty($row_entries_1['brewMead1'])) $table_flight_tbody .= "<strong>".$label_carbonation.":</strong> ".$row_entries_1['brewMead1']."<br>";
						if (!empty($row_entries_1['brewMead2'])) $table_flight_tbody .= "<strong>".$label_sweetness.":</strong> ".$row_entries_1['brewMead2']."<br>";
						if (!empty($row_entries_1['brewMead3'])) $table_flight_tbody .= "<strong>".$label_strength.":</strong> ".$row_entries_1['brewMead3'];
						$table_flight_tbody .= "</p>";

						if (!empty($row_entries['brewPossAllergens'])) $table_flight_tbody .= "<p><strong>".$label_possible_allergens.":</strong> ".$row_entries['brewPossAllergens']."</p>";

						if (!empty($row_entries['brewStaffNotes'])) $table_flight_tbody .= "<p><strong>".$label_notes.":</strong> ".$row_entries['brewStaffNotes']."</p>";

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
							$style = $row_entries['brewCategorySort'].$row_entries['brewSubCategory'];
							$style_special = $row_entries['brewCategorySort']."^".$row_entries['brewSubCategory']."^".$_SESSION['prefsStyleSet'];

							do {

								if (!empty($row_entries['brewCategorySort'])) {

									$table_flight_tbody .= "<tr>";
									$table_flight_tbody .= "<td>";
									$table_flight_tbody .= "<p class=\"box\">&nbsp;</p>";
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									if ($view == "entry")  $table_flight_tbody .= sprintf("%06s",$row_entries['id']);
									else $table_flight_tbody .= sprintf("%06s",$row_entries['brewJudgingNumber']);
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									if ($_SESSION['prefsStyleSet'] != "BA") $table_flight_tbody .= $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1)."</em>";
									else $table_flight_tbody .= $row_entries['brewStyle'];
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";

									$special = style_convert($style_special,"9");
									$special = explode("^",$special);

									if (($row_entries['brewInfo'] != "") && ($special[4] == "1")) $table_flight_tbody .= "<p><strong>Required Info: </strong>".str_replace("^"," | ",$row_entries['brewInfo'])."</p>";
									if ($row_entries['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>Optional Info: </strong>".$row_entries['brewInfoOptional']."</p>";
									if ($row_entries['brewComments'] != "") $table_flight_tbody .= "<p><strong>Specifics: </strong>".$row_entries['brewComments']."</p>";

									$table_flight_tbody .= "<p>";
									if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<strong>Carbonation:</strong> ".$row_entries['brewMead1']."<br>";
									if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<strong>Sweetness:</strong> ".$row_entries['brewMead2']."<br>";
									if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<strong>Strength:</strong> ".$row_entries['brewMead3'];
									$table_flight_tbody .= "</p>";
									if (!empty($row_entries['brewPossAllergens'])) $table_flight_tbody .= "<p><strong>".$label_possible_allergens.":</strong> ".$row_entries['brewPossAllergens']."</p>";

									if (!empty($row_entries['brewStaffNotes'])) $table_flight_tbody .= "<p><strong>".$label_notes.":</strong> ".$row_entries['brewStaffNotes']."</p>";

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
						$style = $row_entries['brewCategorySort'].$row_entries['brewSubCategory'];
						$style_special = $row_entries['brewCategorySort']."^".$row_entries['brewSubCategory']."^".$_SESSION['prefsStyleSet'];

						do {


							if (!empty($row_entries['brewCategorySort'])) {

								$table_flight_tbody .= "<tr>";
								$table_flight_tbody .= "<td>";
								$table_flight_tbody .= "<p class=\"box\">&nbsp;</p>";
								$table_flight_tbody .= "</td>";
								$table_flight_tbody .= "<td>";
								if ($view == "entry")  $table_flight_tbody .= sprintf("%06s",$row_entries['id']);
								else $table_flight_tbody .= sprintf("%06s",$row_entries['brewJudgingNumber']);
								$table_flight_tbody .= "</td>";
								$table_flight_tbody .= "<td>";
								if ($_SESSION['prefsStyleSet'] != "BA") $table_flight_tbody .= $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1)."</em>";
								else $table_flight_tbody .= $row_entries['brewStyle'];
								$table_flight_tbody .= "</td>";
								$table_flight_tbody .= "<td>";

								$special = style_convert($style_special,"9");
								$special = explode("^",$special);

								if (($row_entries['brewInfo'] != "") && ($special[4] == "1")) $table_flight_tbody .= "<p><strong>Required Info: </strong>".str_replace("^"," | ",$row_entries['brewInfo'])."</p>";
								if ($row_entries['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>Optional Info: </strong>".$row_entries['brewInfoOptional']."</p>";
								if ($row_entries['brewComments'] != "") $table_flight_tbody .= "<p><strong>Specifics: </strong>".$row_entries['brewComments']."</p>";

								$table_flight_tbody .= "<p>";
								if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<strong>Carbonation:</strong> ".$row_entries['brewMead1']."<br>";
								if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<strong>Sweetness:</strong> ".$row_entries['brewMead2']."<br>";
								if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<strong>Strength:</strong> ".$row_entries['brewMead3'];
								$table_flight_tbody .= "</p>";

								if (!empty($row_entries['brewPossAllergens'])) $table_flight_tbody .= "<p><strong>".$label_possible_allergens.":</strong> ".$row_entries['brewPossAllergens']."</p>";

								if (!empty($row_entries['brewStaffNotes'])) $table_flight_tbody .= "<p><strong>".$label_notes.":</strong> ".$row_entries['brewStaffNotes']."</p>";


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
									if ($view == "entry")  $table_flight_tbody .= sprintf("%06s",$row_entries['id']);
									else $table_flight_tbody .= sprintf("%06s",$row_entries['brewJudgingNumber']);
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									if ($_SESSION['prefsStyleSet'] != "BA") $table_flight_tbody .= $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1)."</em>";
									else $table_flight_tbody .= $row_entries['brewStyle'];
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";

									$special = style_convert($style_special,"9");
									$special = explode("^",$special);

										if (($row_entries['brewInfo'] != "") && ($special[4] == "1")) $table_flight_tbody .= "<p><strong>Required Info: </strong>".str_replace("^"," | ",$row_entries['brewInfo'])."</p>";
										if ($row_entries['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>Optional Info: </strong>".$row_entries['brewInfoOptional']."</p>";
										if ($row_entries['brewComments'] != "") $table_flight_tbody .= "<p><strong>Specifics: </strong>".$row_entries['brewComments']."</p>";

										$table_flight_tbody .= "<p>";
										if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<strong>Carbonation:</strong> ".$row_entries['brewMead1']."<br>";
										if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<strong>Sweetness:</strong> ".$row_entries['brewMead2']."<br>";
										if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<strong>Strength:</strong> ".$row_entries['brewMead3'];
										$table_flight_tbody .= "</p>";

										if (!empty($row_entries['brewPossAllergens'])) $table_flight_tbody .= "<p><strong>".$label_possible_allergens.":</strong> ".$row_entries['brewPossAllergens']."</p>";

										if (!empty($row_entries['brewStaffNotes'])) $table_flight_tbody .= "<p><strong>".$label_notes.":</strong> ".$row_entries['brewStaffNotes']."</p>";

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
									if ($view == "entry")  $table_flight_tbody .= sprintf("%06s",$row_entries['id']);
									else $table_flight_tbody .= sprintf("%06s",$row_entries['brewJudgingNumber']);
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";
									if ($_SESSION['prefsStyleSet'] != "BA") $table_flight_tbody .= $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1)."</em>";
									else $table_flight_tbody .= $row_entries['brewStyle'];
									$table_flight_tbody .= "</td>";
									$table_flight_tbody .= "<td>";

									$special = style_convert($style_special,"9");
									$special = explode("^",$special);

										if (($row_entries['brewInfo'] != "") && ($special[4] == "1")) $table_flight_tbody .= "<p><strong>Required Info: </strong>".str_replace("^"," | ",$row_entries['brewInfo'])."</p>";
										if ($row_entries['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>Optional Info: </strong>".$row_entries['brewInfoOptional']."</p>";
										if ($row_entries['brewComments'] != "") $table_flight_tbody .= "<p><strong>Specifics: </strong>".$row_entries['brewComments']."</p>";

										$table_flight_tbody .= "<p>";
										if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<strong>Carbonation:</strong> ".$row_entries['brewMead1']."<br>";
										if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<strong>Sweetness:</strong> ".$row_entries['brewMead2']."<br>";
										if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<strong>Strength:</strong> ".$row_entries['brewMead3'];
										$table_flight_tbody .= "</p>";

										if (!empty($row_entries['brewPossAllergens'])) $table_flight_tbody .= "<p><strong>".$label_possible_allergens.":</strong> ".$row_entries['brewPossAllergens']."</p>";

										if (!empty($row_entries['brewStaffNotes'])) $table_flight_tbody .= "<p><strong>".$label_notes.":</strong> ".$row_entries['brewStaffNotes']."</p>";

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
				$table_info_location .= "<p>".$label_please_note."</p>";
				$table_info_location .= "<ul>";
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
							if ($view == "entry")  $table_flight_tbody .= sprintf("%06s",$row_entries['id']);
							else $table_flight_tbody .= sprintf("%06s",$row_entries['brewJudgingNumber']);
							$table_flight_tbody .= "</td>";
							$table_flight_tbody .= "<td>";
							if ($_SESSION['prefsStyleSet'] != "BA") $table_flight_tbody .= $style." ".$row_entries['brewStyle']."<em><br>".style_convert($row_entries['brewCategorySort'],1)."</em>";
							else $table_flight_tbody .= $row_entries['brewStyle'];
							$table_flight_tbody .= "</td>";
							$table_flight_tbody .= "<td>";

							$special = style_convert($style_special,"9");
							$special = explode("^",$special);

								if (($row_entries['brewInfo'] != "") && ($special[4] == "1")) $table_flight_tbody .= "<p><strong>Required Info: </strong>".str_replace("^"," | ",$row_entries['brewInfo'])."</p>";
								if ($row_entries['brewInfoOptional'] != "") $table_flight_tbody .= "<p><strong>Optional Info: </strong>".$row_entries['brewInfoOptional']."</p>";
								if ($row_entries['brewComments'] != "") $table_flight_tbody .= "<p><strong>Specifics: </strong>".$row_entries['brewComments']."</p>";

								$table_flight_tbody .= "<p>";
								if (!empty($row_entries['brewMead1'])) $table_flight_tbody .= "<strong>Carbonation:</strong> ".$row_entries['brewMead1']."<br>";
								if (!empty($row_entries['brewMead2'])) $table_flight_tbody .= "<strong>Sweetness:</strong> ".$row_entries['brewMead2']."<br>";
								if (!empty($row_entries['brewMead3'])) $table_flight_tbody .= "<strong>Strength:</strong> ".$row_entries['brewMead3'];
								$table_flight_tbody .= "</p>";

								if (!empty($row_entries['brewPossAllergens'])) $table_flight_tbody .= "<p><strong>".$label_possible_allergens.":</strong> ".$row_entries['brewPossAllergens']."</p>";

								if (!empty($row_entries['brewStaffNotes'])) $table_flight_tbody .= "<p><strong>".$label_notes.":</strong> ".$row_entries['brewStaffNotes']."</p>";

							$table_flight_tbody .= "</td>";
							$table_flight_tbody .= "<td>";
							$table_flight_tbody .= $row_entries['brewBoxNum'];;
							$table_flight_tbody .= "</td>";
							if ($filter != "mini_bos") {
								$table_flight_tbody .= "<td>";
								$table_flight_tbody .= "<p class=\"box_small\">";
								if (($filter == "mini_bos") && ($row_entries['scoreMiniBOS'] == 1)) $table_flight_tbody .= "<span class=\"fa fa-check\"></span>";
								else $table_flight_tbody .= "&nbsp;";
								$table_flight_tbody .= "</td>";
							}
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

}

echo $pullsheet_output;
?>