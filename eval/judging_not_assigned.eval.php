<?php

foreach ($eval_scores as $key => $value) {

	$disable_add_edit_otf = FALSE;

	if ($_SESSION['user_id'] == $value['judge_id']) {

		if (!in_array($value['table'], $table_assignments_user)) {

			// Get entry info
			$query_entry = sprintf("SELECT id, brewBrewerID, brewStyle, brewCategorySort, brewCategory, brewSubCategory, brewInfo, brewJudgingNumber, brewName FROM %s WHERE id='%s'", $prefix."brewing", $value['eid']);
			$entry = mysqli_query($connection,$query_entry) or die (mysqli_error($connection));
			$row_entry = mysqli_fetch_assoc($entry);
			$totalRows_entry = mysqli_num_rows($entry);

			$actions_otf = "";
			$number_otf = "";
			$style_display_otf = "";
			$actions_otf = "";
			$notes_otf = "";
				
			// Build table row
			if ($totalRows_entry > 0) {

				// Entry Number
				if ($_SESSION['prefsDisplaySpecial'] == "J") $number_otf = sprintf("%06s",$row_entry['brewJudgingNumber']);
	        	else $number_otf = sprintf("%06s",$row_entries['id']);

	        	$table_info = get_table_info(1,"basic",$value['table'],"default","default");
				$table_info = explode("^", $table_info);

	        	$table_location = get_table_info($table_info[2],"location",$value['table'],"default","default");
	        	$table_location = explode("^", $table_location);

	        	if ((!empty($table_location[1])) && (time() > $table_location[1])) $disable_add_edit_otf = TRUE;
				
				// Style Info
				$style_otf = style_number_const($row_entry['brewCategorySort'],$row_entry['brewSubCategory'],$_SESSION['style_set_display_separator'],0);
	        	$style_display_otf = $style_otf.": ".$row_entry['brewStyle'];

				// Get recorded evaluation entries
				$query_evals = sprintf("SELECT evalFinalScore FROM %s WHERE eid='%s'", $prefix."evaluation", $value['eid']);
				$evals = mysqli_query($connection,$query_evals) or die (mysqli_error($connection));
				$row_evals = mysqli_fetch_assoc($evals);
				$totalRows_evals = mysqli_num_rows($evals);

				// If other judges have evaluated the entry, compare evalFinalScores
				if ($totalRows_evals > 1) {
					$assigned_score = array();

					do {
						$assigned_score[] = $row_evals['evalFinalScore'];
					} while ($row_evals = mysqli_fetch_assoc($evals));

					if (count(array_unique($assigned_score)) === 1) $notes_otf .= "<div style=\"margin-bottom:5px;\" class=\"text-success\">".$evaluation_info_026."<br>".$label_assigned_score.": ".$assigned_score[0]."</div>";
					
					else {
						$notes_otf .= "<div style=\"margin-bottom:5px;\" class=\"text-danger\"><strong>";
						$notes_otf .= $evaluation_info_017;
						$notes_otf .= "<br>";
						$notes_otf .= $label_recorded_scores.": ";
						$notes_otf .= rtrim(display_array_content($assigned_score,2),", ");
						$notes_otf .= "</strong></div>";

						/*
						$table_name_otf = get_table_info(1,"basic",$value['table'],"default","default");
						$table_name_otf = explode("^", $table_name_otf);
						$tbl_name_disp_otf = $table_name_otf[1];
						$tbl_loc_disp_otf = $table_name_otf[2];
						$tbl_num_disp_otf = $table_name_otf[0];
						*/
						
						$assigned_score_mismatch[] = array(
							"table_id" => $value['table'],
							"table_name" => "",
							"id" => $value['id'],
							"brewJudgingNumber" => $number_otf,
							"brewCategorySort" => $row_entry['brewCategorySort'],
							"brewSubCategory" => $row_entry['brewSubCategory'],
							"brewStyle" => $row_entry['brewStyle']
						);
						
					}

				}

				// Build actions
				$view_link = $base_url."includes/output.inc.php?section=evaluation&amp;go=default&amp;id=".$value['id']."&amp;tb=1";

				if ($judging_open) {
	        		
	        		$edit_link = $base_url."index.php?section=evaluation&amp;go=scoresheet&amp;action=edit&amp;filter=".$value['table']."&amp;sort=".$value['scoresheet']."&amp;id=".$value['id'];
	        		$actions_otf .= "<div class=\"btn-group btn-group-justified\" role=\"group\">";
	        		if (!$disable_add_edit_otf) {
	        			$actions_otf .= "<a class=\"btn btn-sm btn-warning\" href=\"".$edit_link."\">".$label_edit;
		        		$actions_otf .= "</a>";
	        		}
	        		$actions_otf .= "<a class=\"btn btn-sm btn-info hide-loader\" id=\"modal_window_link\" class=\"hide-loader\" href=\"".$view_link."\">".$label_view;
	        		$actions_otf .= "</a>";
	        		$actions_otf .= "</div>";

	        	}
	        	
	        	else {
	        		$actions_otf = "<a class=\"btn btn-block btn-sm btn-info hide-loader\" id=\"modal_window_link\" class=\"hide-loader\" href=\"".$view_link."\">".$label_view;
	        		$actions_otf .= "</a>";
	        	}

	        	$actions_otf .= "<div class=\"text-center\">";
	    		$actions_otf .= "<small>".$label_your_score.": ".$value['judge_score']."</small>";
	    		if (!empty($value['consensus_score'])) $actions_otf .= "<br><small>".$label_your_assigned_score.": ".$value['consensus_score']."</small>";
	    		$actions_otf .= "</div>";
	    		if ($disable_add_edit_otf) {
	    			$actions_otf .= "<div class=\"text-center\">";
				    $actions_otf .= "<small>".$evaluation_info_028."</small>";
				    $actions_otf .= "</div>";
	    		}
	    		$notes_otf .= $evaluation_info_004." ";
			    if ($judging_open) $notes_otf .= $evaluation_info_006;
	    		
				$on_the_fly_display_tbody .= "<tr>";
				$on_the_fly_display_tbody .= "<td><a name=\"".$number_otf."\"></a>".$number_otf."</td>";
				$on_the_fly_display_tbody .= "<td class=\"hidden-xs\">".$style_display_otf."</td>";
				$on_the_fly_display_tbody .= "<td>".$notes_otf."</td>";
				$on_the_fly_display_tbody .= "<td>".$actions_otf."</td>";
				$on_the_fly_display_tbody .= "</tr>";

			}

		} // end if (!in_array($value['table'], $table_assignments_user)) {

	} // end if ($_SESSION['user_id'] == $value['judge_id']) {

} // end foreach

if (!empty($on_the_fly_display_tbody)) {
	$dt_js .= "
	$('#table-on-the-fly').dataTable({
		\"bPaginate\" : false,
		\"sDom\": 'rt',
		\"bStateSave\" : false,
		\"bLengthChange\" : false,
		\"aaSorting\": [[1,'asc'],[0,'asc']],
		\"aoColumns\": [
			null,
			null,
			null,
			null
			]
		});
	";

	$on_the_fly_display .= "<h3 style=\"margin-top: 30px;\">".$label_unassigned_eval."</h3>";
	$on_the_fly_display .= "<p>".$evaluation_info_027."</p>";
	$on_the_fly_display .= "<table id=\"table-on-the-fly\" class=\"table table-condensed table-striped table-bordered table-responsive\">";
	$on_the_fly_display .= "<thead>";
	$on_the_fly_display .= "<tr>";
	$on_the_fly_display .= "<th width=\"10%\">".$label_number."</th>";
	$on_the_fly_display .= "<th class=\"hidden-xs\">".$label_style."</th>";
	$on_the_fly_display .= "<th>".$label_notes."</th>";
	$on_the_fly_display .= "<th width=\"30%\">".$label_actions."</th>";
	$on_the_fly_display .= "</tr>";
	$on_the_fly_display .= "</thead>";
	$on_the_fly_display .= "<tbody>";
	$on_the_fly_display .= $on_the_fly_display_tbody;
	$on_the_fly_display .= "</tbody>";
	$on_the_fly_display .= "</table>";
}
?>