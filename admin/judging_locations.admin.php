<?php

/**
 * Module:      judging_locations.admin.php
 * Description: This module houses all participant (brewer) related functionality
 *              involved in assigning participants a role - judge, steward, staff.
 *              Also provids judging location related functions - add, edit, delete.
 *
 */

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && (strpos($section, "step") === FALSE) && ($_SESSION['userLevel'] > 0))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

// Set Vars
$output_datatables_head = "";
$output_datatables_body = "";
$output_assignment_modals = "";
$output_add_edit = "";
$filter_readable = "";
$primary_page_info = "";
$secondary_nav = "";
$secondary_page_info = "";
$form_submit_url = "";
$form_submit_button = "";
$output_no_records = "";
$goto_nav = "";
$judge_loc_url_yes = "";
$judge_loc_url_no = "";
$assignment_modal_body = "";

if ($filter == "bos") {
	$filter_readable .= "Best of Show Judges";
	$staff_row_field = "staff_judge_bos";
}
else {
	$filter_readable = ucwords($filter);
	if ($filter == "judges") 	$staff_row_field = "staff_judge";
	if ($filter == "stewards") 	$staff_row_field = "staff_steward";
	if ($filter == "staff") 	$staff_row_field = "staff_staff";
}

// *****************************************************************************
// ---------------------- Top of Page Vars -------------------------------------
// *****************************************************************************


// Build Subtitle
$subtitle = "";
if ($section != "step5") {
	if ($action == "add") $subtitle .= ": Add a Judging Session";
	elseif ($action == "edit") $subtitle .= ": Edit a Judging Session";
	elseif ($action == "update") $subtitle .= ": Make Final ".$filter_readable." Assignments";
	elseif ($action == "assign") $subtitle .= ": Assign Participants as ".$filter_readable;
	else $subtitle .= ": Judging Sessions";
}

// Build Secondary Page Info

if ($filter == "judges") {
	$secondary_page_info .= "<p>According to <a class='hide-loader' href='http://www.bjcp.org/rules.php' target='_blank'>BJCP rules</a>, &quot;Judges earn points at a rate of 0.5 judging points per session, but the following limitations apply:</p>";
	$secondary_page_info .= "<ul>";
  	$secondary_page_info .= "<li>Judges earn a minimum of 1.0 point per competition.</li>";
  	$secondary_page_info .= "<li>Judges earn a maximum of 1.5 points per day.</li>";
	$secondary_page_info .= "</ul>";
	$secondary_page_info .= "<p>&quot;The total number of judging points a judge may earn in a competition is limited by the organizer points. Participants <em>may not</em> earn both Judge and Steward points in a single competition.&quot;</p>";
}

if ($filter == "stewards") {
	$secondary_page_info .= "<p>According to <a class='hide-loader' href='http://www.bjcp.org/rules.php' target='_blank'>BJCP rules</a>, &quot;Stewards receive 0.5 non-judging points per day with a maximum of 1.0 points per competition. Participants <em>may not</em> earn both Judge and Steward points in a single competition. Steward points are awarded separately from Staff points and do not come from the Staff point pool. A program participant may earn both Steward and Staff points.&quot;</p>";
}

if ($filter == "staff") {

	$secondary_page_info .= "<h5>Staff</h5>";
	$secondary_page_info .= "<p>According to <a class='hide-loader' href='http://www.bjcp.org/rules.php' target='_blank'>BJCP rules</a>, staff members are &quot;...program participants who, under the direction of the Organizer, perform an active role in support of the competition other than as a Judge, Steward, or BOS Judge.&quot;";
	$secondary_page_info .= "<p>If a staff member is not on this list, <a href='".$base_url."index.php?section=admin&go=participants&action=add'>add them to the database</a>.</p>";

	$secondary_page_info .= "<h5>Organizer</h5>";
	$secondary_page_info .= "<p>According to <a class='hide-loader' href='http://www.bjcp.org/rules.php' target='_blank'>BJCP rules</a>, the Organizer is &quot;...the single program participant who completes and signs the application to register or sanction a competition and who in all ways assumes responsibility for the direction of that competition.&quot;</p>";
	$secondary_page_info .= "<p>If the organizer is not on this list, <a href='".$base_url."index.php?section=admin&go=participants&action=add'>add them to the database</a>.</p>";

}

if ($filter == "bos") {
	$secondary_page_info .= "<p>According to <a class='hide-loader' href='http://www.bjcp.org/rules.php' target='_blank'>BJCP rules</a>, &quot;Best-of-Show (BOS) Judges&nbsp;are eligible to receive a 0.5 judging point bonus if they judge in any BOS panel in a competition. The BOS bonus is in addition to any other judging and non-judging points earned in the competition, and may only be awarded to a single judge once per competition. BOS points may only be awarded if a competition has at least 30 entries in at least five beer and/or three mead/cider categories.</p>";
	$secondary_page_info .= "<p>&quot;The number of judges eligible to receive the BOS bonus is correlated to the number of entries in each BOS panel as follows:</p>";
	$secondary_page_info .= "<ul>";
	$secondary_page_info .= "<li>5-14 BOS entries, including beer = 3 BOS Judges</li>";
	$secondary_page_info .= "<li>3-14 BOS meads and/or ciders (only) = 3 BOS Judges</li>";
	$secondary_page_info .= "<li>15 or more BOS entries of any type or combination = 5 BOS Judges&quot;</li>";
	$secondary_page_info .= "</ul>";
}


// Judging Locations & Dates List
if ($section != "step5") {
	if (($action == "default") && ($totalRows_judging_locs > 0)) {
		
		$output_datatables_aaSorting = "[2,'asc']";
		$output_datatables_aoColumns = "null, null, null, null, null, null, { \"asSorting\": [  ] }";
		
		$output_datatables_head .= "<tr>";
		$output_datatables_head .= "<th>Name</th>";
		$output_datatables_head .= "<th class=\"hidden-xs hidden-sm\">Type</th>";
		$output_datatables_head .= "<th>Start Date/Time</th>";
		$output_datatables_head .= "<th>End Date/Time</th>";
		$output_datatables_head .= "<th>Address or Entry Distribution Info</th>";
		$output_datatables_head .= "<th class=\"hidden-xs hidden-sm\"># of Rounds</th>";
		$output_datatables_head .= "<th>Actions</th>";
		$output_datatables_head .= "</tr>";

		do {

			$output_datatables_edit_link = build_action_link("fa-pencil",$base_url,"admin","judging","edit",$filter,$row_judging_locs['id'],$dbTable,"default",0,"Edit ".$row_judging_locs['judgingLocName']);

			$output_datatables_delete_link = build_action_link("fa-trash-o",$base_url,"admin","judging","delete",$filter,$row_judging_locs['id'],$judging_locations_db_table,"Are you sure you want to delete ".$row_judging_locs['judgingLocName']."? All judge/steward account location preferences for this location will be removed. This cannot be undone.",0,"Delete ".$row_judging_locs['judgingLocName']);

			$output_datatables_actions = $output_datatables_edit_link." ".$output_datatables_delete_link;

			$judgingLocType = "";
			if ($row_judging_locs['judgingLocType'] == "0") $judgingLocType = "Traditional";
			if ($row_judging_locs['judgingLocType'] == "1") $judgingLocType = "Distributed"; 

			$output_datatables_body .= "<tr>";
			$output_datatables_body .= "<td>".$row_judging_locs['judgingLocName']."</td>";
			$output_datatables_body .= "<td class=\"hidden-xs hidden-sm\">".$judgingLocType."</td>";
			$output_datatables_body .= "<td><span class=\"hidden\">".$row_judging_locs['judgingDate']."</span>".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging_locs['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time")."</td>";
			if (!empty($row_judging_locs['judgingDateEnd'])) $output_datatables_body .= "<td>".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging_locs['judgingDateEnd'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time")."</td>";
			else $output_datatables_body .= "<td>N/A</td>";
			$output_datatables_body .= "</td>";
			$output_datatables_body .= "<td>".$row_judging_locs['judgingLocation']."</td>";
			$output_datatables_body .= "<td class=\"hidden-xs hidden-sm\">".$row_judging_locs['judgingRounds']."</td>";
			$output_datatables_body .= "<td>".$output_datatables_actions."</td>";
			$output_datatables_body .= "</tr>";

		} while($row_judging_locs = mysqli_fetch_assoc($judging_locs));

	} // end if (($totalRows_judging_locs > 0) && ($action == "default"))
} // end if ($section != "step5")


// *****************************************************************************
// ---------------------- List Judging Locations ---------------------------
// *****************************************************************************
if ($section != "step5") {

	if ((($action == "update") && ($filter != "default") && ($bid != "default")) || ($action == "assign")) {

		$form_submit_url .= build_form_action($base_url,$section,"default","update",$filter,"default",$brewer_db_table,FALSE);
		$form_organizer_select = "";
		$form_submit_button_help = "";

		if ($filter == "staff") {

			do {
				$form_organizer_select .= "<option value=\"".$row_brewers['uid']."\"";
				if (($totalRows_organizer > 0) && (($row_brewers['uid'] == $row_organizer['uid']))) $form_organizer_select .= " SELECTED";
				$form_organizer_select .= ">".$row_brewers['brewerLastName'].", ".$row_brewers['brewerFirstName'];
				if (($totalRows_organizer > 0) && (($row_brewers['uid'] == $row_organizer['uid']))) $form_organizer_select .= " (Selected Competition Organizer)";
				$form_organizer_select .= "</option>";
			} while ($row_brewers = mysqli_fetch_assoc($brewers));

		}

		if ($action == "update") $form_submit_button .= "Assign to ".$row_judging['judgingLocName'];
		elseif ($action == "assign") $form_submit_button .= "Assign as ".brewer_assignment($filter,"3",$id,"default",$filter);
		elseif ($action == "add") $form_submit_button .= "Add Judging Session";
		elseif ($action == "edit") $form_submit_button .= "Edit Judging Session";
		else $form_submit_button .= "Submit";

		$form_submit_button_help .= "Select ";
		if ($action == "update") $form_submit_button_help .= "Assign to ".$row_judging['judgingLocName'];
		elseif ($action == "assign") $form_submit_button_help .= "Assign as ".brewer_assignment($filter,"3",$id,"default",$filter);
		else $form_submit_button_help .= "Submit";
		$form_submit_button_help .= " <em>before</em> paging through records.";

		if ($filter == "judges") 		$output_datatables_aaSorting = "[4,'desc']";
		elseif ($filter == "bos") 		$output_datatables_aaSorting = "[5,'desc']";
		else 							$output_datatables_aaSorting = "[1,'asc']";

		if ($filter == "judges") 		$output_datatables_aoColumns = "{ \"asSorting\": [  ] }, null, null, null, null, null, null";
		elseif ($filter == "stewards") 	$output_datatables_aoColumns = "{ \"asSorting\": [  ] }, null, null, null, null";
		elseif ($filter == "staff")		$output_datatables_aoColumns = "{ \"asSorting\": [  ] }, null, null, null";
		elseif ($filter == "bos") 		$output_datatables_aoColumns = "{ \"asSorting\": [  ] }, null, null, null, null, null";


		$output_datatables_head .= "<tr>";
		$output_datatables_head .= "<th width=\"1%\" nowrap><div class=\"checkbox\"><label><input type=\"checkbox\" name=\"all\" id=\"checkAll\" title=\"Check/Uncheck All\"></label></div></th>";
		$output_datatables_head .= "<th>Name</th>";
		$output_datatables_head .= "<th class=\"hidden-xs hidden-sm\">Assigned As</th>";
		if ($filter == "bos") $output_datatables_head .= "<th>Placing Entries</th>";
		if (($filter == "judges") || ($filter == "bos")) {
			$output_datatables_head .= "<th class=\"hidden-xs hidden-sm\">ID</th>";
			$output_datatables_head .= "<th>Rank</th>";
		}
		if (($filter == "judges") || ($filter == "stewards")) {
			$output_datatables_head .= "<th class=\"hidden-xs hidden-sm\">Preferences</th>";
			$output_datatables_head .= "<th class=\"hidden-xs hidden-sm\" width=\"30%\">Has Entries In...</th>";
		}
		if (($filter == "staff")) {
			$output_datatables_head .= "<th class=\"hidden-xs hidden-sm\">Preferences</th>";
		}
		$output_datatables_head .= "</tr>";

		if ($totalRows_brewer > 0) {

			$copy_paste_emails = "";

			do {

				$brewer_assignment = brewer_assignment($row_brewer['uid'],"1","default",$dbTable,$filter,"default");

				if (!empty($brewer_assignment)) {

					// Build assignment modal for participants
					unset($assignment_modal_body);
					if ((strpos($brewer_assignment,"Judge") !== false) || (strpos($brewer_assignment,"Steward") !== false) ) {

						if (strpos($brewer_assignment,"Judge") !== false) {
							if (!empty($table_assign_judge)) $assignment_modal_body = "<p>".$row_brewer['brewerFirstName']." is assigned as a <strong>judge</strong> to table(s): ".$table_assign_judge."<p>";
							else $assignment_modal_body = "<p>".$row_brewer['brewerFirstName']." has been added to the <strong>judge</strong> pool, but has not been assigned to a table yet.<p>";
						}
						if (strpos($brewer_assignment,"Steward") !== false) {
							if (!empty($table_assign_steward))  $assignment_modal_body .= "<p>".$row_brewer['brewerFirstName']." is assigned as a <strong>steward</strong> to table(s): ".$table_assign_steward."<p>";
							else $assignment_modal_body = "<p>".$row_brewer['brewerFirstName']." has been added to the <strong>steward</strong> pool, but has not been assigned to a table yet.<p>";
						}
						if (!empty($judge_entries)) $assignment_modal_body .= "<p>Has entries in: ".$judge_entries."</p>";
						$output_assignment_modals .= "<div class=\"modal fade\" id=\"assignment-modal-".$row_brewer['uid']."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"assignment-modal-label-".$row_brewer['uid']."\">\n";
						$output_assignment_modals .= "\t<div class=\"modal-dialog modal-lg\" role=\"document\">\n";
						$output_assignment_modals .= "\t\t<div class=\"modal-content\">\n";
						$output_assignment_modals .= "\t\t\t<div class=\"modal-header bcoem-admin-modal\">\n";
						$output_assignment_modals .= "\t\t\t\t<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>\n";
						$output_assignment_modals .= "\t\t\t\t<h4 class=\"modal-title\" id=\"assignment-modal-label-".$row_brewer['uid']."\">Assignment(s) for ".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']."</h4>\n";
						$output_assignment_modals .= "\t\t\t</div><!-- ./modal-header -->\n";
						$output_assignment_modals .= "\t\t\t<div class=\"modal-body\">\n";
						$output_assignment_modals .= "\t\t\t\t".$assignment_modal_body."\n";
						$output_assignment_modals .= "\t\t\t</div><!-- ./modal-body -->\n";
						$output_assignment_modals .= "\t\t\t<div class=\"modal-footer\">\n";
						$output_assignment_modals .= "\t\t\t\t<button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">Close</button>\n";
						$output_assignment_modals .= "\t\t\t</div><!-- ./modal-footer -->\n";
						$output_assignment_modals .= "\t\t</div><!-- ./modal-content -->\n";
						$output_assignment_modals .= "\t</div><!-- ./modal-dialog -->\n";
						$output_assignment_modals .= "</div><!-- ./modal -->\n";
					}
				}

				$assignment_checked = str_replace(", ",",",$brewer_assignment);

				if ((!empty($assignment_checked)) && ($filter == "judges") && (strpos($brewer_assignment,'Judge') !== false)) { $checked = "CHECKED"; $copy_paste_emails .= $row_brewer['brewerEmail'].", "; }
				elseif ((!empty($assignment_checked)) && ($filter == "stewards") && (strpos($brewer_assignment,'Steward') !== false)) { $checked = "CHECKED"; $copy_paste_emails .= $row_brewer['brewerEmail'].", "; }
				elseif ((!empty($assignment_checked)) && ($filter == "staff") && (strpos($brewer_assignment,'Staff') !== false)) { $checked = "CHECKED"; $copy_paste_emails .= $row_brewer['brewerEmail'].", "; }
				elseif ((!empty($assignment_checked)) && ($filter == "bos") && (strpos($brewer_assignment,'BOS') !== false)) { $checked = "CHECKED"; $copy_paste_emails .= $row_brewer['brewerEmail'].", "; }
				else $checked = "";

				if ($filter == "bos") {
						$bos_judge_eligible = bos_judge_eligible($row_brewer['uid']);
						if (!empty($bos_judge_eligible)) {
							$places_earned = explode("|",$bos_judge_eligible);
							$judge_places = "";
							foreach ($places_earned as $places) {
								$places_earned = explode("-",$places);
								$judge_places .= display_place($places_earned[0],1).": Table ".$places_earned[1].", ";
							}
							$judge_places = rtrim($judge_places,", ");
						}
				}

				if (($filter == "judges") || ($filter == "stewards") || ($filter == "staff")) {
				    
				    // Get Judging Sessions
				    $query_judging_loc3 = sprintf("SELECT id, judgingLocName, judgingLocType FROM %s", $prefix."judging_locations");
				    if ($filter == "staff") $query_judging_loc3 .= " WHERE judgingLocType='2'";
					$judging_loc3 = mysqli_query($connection,$query_judging_loc3) or die (mysqli_error($connection));
					$row_judging_loc3 = mysqli_fetch_assoc($judging_loc3);
	
	                $j_sess_arr = array();

	                if ($row_judging_loc3) {
	                	do {
	                	    $j_sess_arr[$row_judging_loc3['id']] = $row_judging_loc3['judgingLocName'];
	                	    
	                	} while ($row_judging_loc3 = mysqli_fetch_assoc($judging_loc3));
	                }
	                
					if (($filter == "judges") || ($filter == "staff")) $exploder = $row_brewer['brewerJudgeLocation'];
					if ($filter == "stewards") $exploder = $row_brewer['brewerStewardLocation'];
					$a = explode(",",$exploder);
					$output = "";
					
					if (!empty($exploder)) {

						sort($a);
						$c = array();

						$none_selected = 0;
						
						foreach ($a as $value) {
							
							if (!empty($value)) {
								
								$b = explode("-",$value);
								
								if (($b[0] == "Y") && (isset($j_sess_arr[$b[1]]))) {
								    $c[] = $j_sess_arr[$b[1]]."<br>";
									$none_selected += 1;
								}
								
							}
							
						}
					
						if (!empty($c)) {

							sort($c);
							foreach ($c as $value) {
								if (!empty($value)) $output .= $value;
							}
							
							$output = rtrim($output,"<br>");
						}

					
					}
						
					if ($none_selected == 0) {
						if ($filter != "staff") $output .= "<span class=\"fa fa-sm fa-ban text-danger\"></span> <a href=\"".$base_url."index.php?section=brewer&amp;go=admin&amp;action=edit&amp;filter=".$row_brewer['uid']."&amp;id=".$row_brewer['uid']."\" data-toggle=\"tooltip\" title=\"Enter ".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']."&rsquo;s location preferences\">None specified</a>.";
					}
					
					$output_location = $output;

				}

				if (($filter == "bos") && (!empty($bos_judge_eligible))) $output_datatables_body .= "<tr class=\"bg-danger text-danger\">";
				elseif (($filter == "bos") && (empty($bos_judge_eligible))) {
					if (strpos($brewer_assignment,'BOS') !== false) $output_datatables_body .= "<tr class=\"bg-info text-info\">";
					else $output_datatables_body .= "<tr class=\"bg-success text-success\">";
				}
				else $output_datatables_body .= "<tr>";
				$output_datatables_body .= "<td>";
				$output_datatables_body .= "<input type=\"hidden\" name=\"uid[]\" value=\"".$row_brewer['uid']."\" />";
				$output_datatables_body .= "<div class=\"checkbox\"><label><input name=\"".$staff_row_field.$row_brewer['uid']."\" type=\"checkbox\" value=\"1\" ".$checked;
				if ((isset($totalRows_organizer)) && ($totalRows_organizer > 0) && (($filter == "staff") && ($row_organizer['uid'] == $row_brewer['uid']))) $output_datatables_body .= " DISABLED";
				if (($filter == "stewards") && (strpos($brewer_assignment,'Judge') !== false)) $output_datatables_body .= " DISABLED";
				if (($filter == "judges") && (strpos($brewer_assignment,'Steward') !== false)) $output_datatables_body .= " DISABLED";
				$output_datatables_body .= " /></label></div>";
				$output_datatables_body .= "</td>";
				$output_datatables_body .= "<td>";
				$output_datatables_body .= $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName'];
				if (($filter == "staff") && ($row_brewer['brewerStaff'] == "Y")) $output_datatables_body .= " <a href=\"".$base_url."index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=staff&amp;view=yes\" tabindex=\"0\" role=\"button\" data-toggle=\"popover\" data-placement=\"right\" data-trigger=\"hover focus\" data-content=\"".$row_brewer['brewerFirstName']." has expressed interest in being a staff member. Click to see only interested users.\"><span class=\"fa fa-star text-danger\"></span></a>";
				$output_datatables_body .= "</td>";
				$output_datatables_body .= "<td class=\"hidden-xs hidden-sm\">".ucwords($brewer_assignment)."</td>";

				if ($filter == "bos") {
					$output_datatables_body .= "<td>";
					if (!empty($bos_judge_eligible)) $output_datatables_body .= $judge_places;
					else $output_datatables_body .= "&nbsp;";
					$output_datatables_body .= "</td>";
				}

				if (($filter == "judges") || ($filter == "bos")) {

					$bjcp_rank = explode(",",$row_brewer['brewerJudgeRank']);
					$display_rank = bjcp_rank($bjcp_rank[0],1);

					if (((strpos($display_rank, "Level 0:") !== false)) && (($row_brewer['brewerJudgeMead'] == "Y") || ($row_brewer['brewerJudgeCider'] == "Y"))) $display_rank = "Level 3: Certified Cider or Mead Judge";

					if ((isset($row_brewer['brewerJudgeMead'])) && ($row_brewer['brewerJudgeMead'] == "Y")) {
						$display_rank .= "<br /><em>Certified Mead Judge</em>";
					}
					
					if ((isset($row_brewer['brewerJudgeCider'])) && ($row_brewer['brewerJudgeCider'] == "Y")) {
						$display_rank .= "<br /><em>Certified Cider Judge</em>";
					}

					$output_datatables_body .= "<td class=\"hidden-xs hidden-sm\">".strtoupper($row_brewer['brewerJudgeID'])."</td>";
					$output_datatables_body .= "<td>".$display_rank;
					
					if (!empty($bjcp_rank[1])) {
						$output_datatables_body .= "<em>".designations($row_brewer['brewerJudgeRank'],$bjcp_rank[0])."</em>";
					}
					
					$output_datatables_body .= "</td>";
				
				}

				if (($filter == "judges") || ($filter == "stewards") || ($filter == "staff")) {
					$output_datatables_body .= "<td class=\"hidden-xs hidden-sm\">".$output_location."</td>";
				}

				if (($filter == "judges") || ($filter == "stewards")) {
					$output_datatables_body .= "<td class=\"hidden-xs hidden-sm\">".judge_entries($row_brewer['uid'],1)."</td>";
				}

				$output_datatables_body .= "</tr>";

			} while ($row_brewer = mysqli_fetch_assoc($brewer));

		} // end if ($totalRows_brewer > 0)

	} // end if ((($action == "update") && ($filter != "default") && ($bid != "default")) || ($action == "assign"))
}
// *****************************************************************************
// ---------------------- Add/Edit Judging Locations ---------------------------
// *****************************************************************************

if ((($action == "add") || ($action == "edit")) || ($section == "step5")) {
	$output_add_edit = TRUE;

	if ($section == "step5") $action = "add"; else $action = $action;
	if ($go == "default") $go = "setup"; else $go = $go;
	
	if ($section == "step5") $form_submit_url .= build_form_action($base_url,$section,$go,$action,$filter,"1",$judging_locations_db_table,TRUE);
	else {
		if ($action == "add") $form_submit_url .= build_form_action($base_url,$section,$go,$action,$filter,"1",$judging_locations_db_table,TRUE);
		if ($action == "edit") $form_submit_url .= build_form_action($base_url,$section,$go,$action,$filter,$row_judging['id'],$judging_locations_db_table,TRUE);
	}

	if ($action == "add") $form_submit_button .= "Add Judging Session";
	elseif ($action == "edit") $form_submit_button .= "Edit Judging Session";
	else $form_submit_button .= "Submit";

	$judging_date = "";
	$judging_end_date = "";
	$judging_time = "";
	if ($action == "edit") {
		$judging_date .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system");
		if (!empty($row_judging['judgingDateEnd'])) $judging_end_date .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDateEnd'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date-time-system");
	}

} // end if ((($action == "add") || ($action == "edit")) || ($section == "step5"))



// ----------------------------------------- Presentation ------------------------------------------


// Display HTML/JS elements and compiled PHP elements
?>
<?php if (!empty($form_submit_url)) echo $form_submit_url; ?>

<input type="hidden" name="token" value ="<?php if (isset($_SESSION['token'])) echo $_SESSION['token']; ?>">
<?php if ($section != "step5") { ?><p class="lead"><?php echo $_SESSION['contestName'].$subtitle; ?></p><?php } ?>
<?php if (($filter == "default") && ($msg == "9"))  {
	if ($section == "step5") $judge_loc_url_yes .= "setup.php?section=step5";
	else $judge_loc_url_yes .= "index.php?section=admin&amp;go=judging";
	if ($section == "step5") $judge_loc_url_no .= "setup.php?section=step6";
	else $judge_loc_url_no .= "index.php?section=admin";
?>

<!-- Setup Adding Judging Locations -->
<p class="lead">Add another judging location, date, or time?</p>
<div class="btn-group" role="group" aria-label="judge-loc-yes">
    <a class="btn btn-success" href="<?php echo $base_url.$judge_loc_url_yes; ?>"><span class="fa fa-check"></span> Yes</a>
</div><!-- ./button group -->
<div class="btn-group" role="group" aria-label="judge-loc-no">
    <a class="btn btn-danger" href="<?php echo $base_url.$judge_loc_url_no; ?>"><span class="fa fa-times"></span> No</a>
</div><!-- ./button group -->
<?php } // end if (($filter == "default") && ($msg == "9")) ?>

<?php if ($section != "step5") { ?>
<div class="bcoem-admin-element hidden-print">
	<!-- Page Navigation Elements -->
	
	<?php if (($action == "add") || ($action == "edit")) { ?>
	<!-- Postion 1: View All Button -->
	<div class="btn-group bcoem-admin-element" role="group" aria-label="...">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging"><span class="fa fa-arrow-circle-left"></span> All Judging Sessions</a>
    </div><!-- ./button group -->
	<?php } ?>
	
	<?php if (($action == "default") || ($action == "edit")) { ?>
	<div class="btn-group bcoem-admin-element" role="group" aria-label="...">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging&amp;action=add"><span class="fa fa-plus-circle"></span> Add a Judging Session</a>
    </div><!-- ./button group -->
    <div class="bcoem-admin-element hidden-print" style="margin-top: 15px;">
	    <p>BCOE&amp;M figures judge points according to the <a class="hide-loader" href="https://www.bjcp.org/rules.php" target="_blank">BJCP's definition of a session</a> as "...an uninterrupted time period when at least one panel of judges sits to judge one or more flights of entries." </p>
	    <p>Thus, to correctly calculate BJCP experience points for program participants, it is suggested that each session you set up here follow that guideline (e.g., <em>Session 1: XXX Location - Early Morning</em>, <em>Session 2: XXX Location - Afternoon</em>, <em>Session 3: Distributed - Week of XXX</em>, etc.).</p>
	    <p>A single judging session may consist of one or more flights and one or more rounds.</p>
	    <h3>Distributed Judging</h3>
	    <p>For distributed judging scenarios (e.g., when judge teams will not be in the same physical location, when judge teams are not judging concurrently, or when judge teams are not judging in a prescribed location, etc.), Administrators are required to define the start date/time and end date/time of the session. In this scenario, judging typically takes place over a period of days or weeks in a variety of locations. If a judging session is designated as <em>distributed</em>, it is required that Admins provide information on how judges will receive the entries they will be evaluating.</p>
	</div>
	<?php } ?>

	<?php if ($filter != "default") { ?>
    <!-- All Participants Button -->
	<div class="btn-group" role="group" aria-label="...">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants"><span class="fa fa-arrow-circle-left"></span> All Participants</a>
    </div><!-- ./button group -->

    <div class="btn-group" role="group" aria-label="...">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables"><span class="fa fa-arrow-circle-left"></span> All Tables</a>
    </div><!-- ./button group -->

	<!-- View Participants Dropdown -->
    <div class="btn-group" role="group">
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<span class="fa fa-eye"></span> View...
		<span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
        	<?php if ($action == "assign") { ?>
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants">All Participants</a></li>
            <li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants&amp;filter=judges">Available Judges</a><li>
            <li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants&amp;filter=stewards">Available Stewards</a><li>
            <?php } ?>
            <?php if (($section == "admin") && ((($action == "add") || ($action == "edit")))) { ?>
            <li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants&amp;filter=stewards">Judging Session List</a><li>
            <?php } ?>
		</ul>
	</div><!-- ./button group -->
    <?php } ?>

	<?php if (($section == "admin") && (($action == "update") || ($action == "assign"))) { ?>
	<!-- Assign/Unassign Judges/Stewards Dropdown -->
	<div class="btn-group" role="group">
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<span class="fa fa-check-circle"></span> Assign/Unassign...
		<span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
        	<?php if ($filter != "judges") { ?>
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">Judges</a></li>
            <?php } ?>
            <?php if ($filter != "bos") { ?>
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=bos">BOS Judges</a><li>
			<?php } ?>
            <?php if ($filter != "stewards") { ?>
            <li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">Stewards</a><li>
            <?php } ?>
            <?php if ($filter != "staff") { ?>
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=staff">Staff</a><li>
            <?php } ?>
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging_tables">Judges/Stewards to Tables</a><li>
		</ul>
	</div><!-- ./button group -->

	<?php if ($filter == "bos") { ?>
	<div class="btn-group" role="group" aria-label="...">
    	<?php if ($view == "ranked") { ?>
    	<a class="btn btn-primary" href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=bos"><span class="fa fa-filter"></span> Filter: All Judges</a>
    	<?php } else { ?>
        <a class="btn btn-primary" href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=bos&amp;view=ranked"><span class="fa fa-filter"></span> Filter: Ranked Judges Only</a>
    	<?php } ?>
    </div><!-- ./button group -->
	<?php } ?>

	<?php if ($filter == "staff") { ?>
    <div class="btn-group" role="group" aria-label="...">
        <div class="btn-group" role="group">
            <select class="selectpicker" name="Organizer" data-live-search="true" data-size="10" data-width="auto">
            <option value="" selected disabled>Designate the Competition Organizer</option>
            <?php echo $form_organizer_select; ?>
            </select>
        </div>
    </div><!-- ./button group -->
    <?php } ?>

    <?php if ($filter == "judges") { ?>
    <div class="btn-group hidden-xs" role="group" aria-label="...">
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#judgeEmailModal">
              Assigned Judge Email Addresses
            </button>
    </div><!-- ./button group -->
    <!-- Modal -->
    <div class="modal fade" id="judgeEmailModal" tabindex="-1" role="dialog" aria-labelledby="judgeEmailModalLabel">
      	<div class="modal-dialog" role="document">
        	<div class="modal-content">
          		<div class="modal-header bcoem-admin-modal">
            		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		<h4 class="modal-title" id="judgeEmailModalLabel">Assigned Judge Email Addresses</h4>
          		</div>
                <div class="modal-body">
                	<p>Copy and paste the list below into your favorite email program to contact all assigned judges.</p>
                    <textarea class="form-control" rows="8"><?php echo rtrim($copy_paste_emails,", "); ?></textarea>
                </div>
                <div class="modal-footer">
            	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        	</div>
      	</div>
    </div><!-- ./modal -->
    <?php } ?>

    <?php if ($filter == "stewards") { ?>
    <div class="btn-group hidden-xs" role="group" aria-label="...">
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#judgeEmailModal">
              Assigned Steward Email Addresses
            </button>
    </div><!-- ./button group -->
    <!-- Modal -->
    <div class="modal fade" id="judgeEmailModal" tabindex="-1" role="dialog" aria-labelledby="judgeEmailModalLabel">
      	<div class="modal-dialog" role="document">
        	<div class="modal-content">
          		<div class="modal-header bcoem-admin-modal">
            		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		<h4 class="modal-title" id="judgeEmailModalLabel">Assigned Steward Email Addresses</h4>
          		</div>
                <div class="modal-body">
                	<p>Copy and paste the list below into your favorite email program to contact all assigned stewards.</p>
                    <textarea class="form-control" rows="8"><?php echo rtrim($copy_paste_emails,", "); ?></textarea>
                </div>
                <div class="modal-footer">
            	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        	</div>
      	</div>
    </div><!-- ./modal -->
    <?php } ?>

    <?php if ($filter == "staff") { ?>
    <div class="btn-group hidden-xs" role="group" aria-label="...">
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#staffEmailModal">
              Assigned Staff Email Addresses
            </button>
    </div><!-- ./button group -->
    <!-- Modal -->
    <div class="modal fade" id="staffEmailModal" tabindex="-1" role="dialog" aria-labelledby="staffEmailModalLabel">
      	<div class="modal-dialog" role="document">
        	<div class="modal-content">
          		<div class="modal-header bcoem-admin-modal">
            		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		<h4 class="modal-title" id="staffEmailModalLabel">Assigned Staff Email Addresses</h4>
          		</div>
                <div class="modal-body">
                	<p>Copy and paste the list below into your favorite email program to contact all assigned staff.</p>
                    <textarea class="form-control" rows="8"><?php echo rtrim($copy_paste_emails,", "); ?></textarea>
                </div>
                <div class="modal-footer">
            	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        	</div>
      	</div>
    </div><!-- ./modal -->
    <?php } ?>

	<?php if ($filter != "default") { ?>
	<div class="btn-group hidden-xs pull-right" role="group" aria-label="...">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#bjcpStatusModal">
              BJCP Rules
            </button>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="bjcpStatusModal" tabindex="-1" role="dialog" aria-labelledby="bjcpStatusModalLabel">
      	<div class="modal-dialog" role="document">
        	<div class="modal-content">
          		<div class="modal-header bcoem-admin-modal">
            		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		<h4 class="modal-title" id="bjcpStatusModalLabel">BJCP Rules</h4>
          		</div>
                <div class="modal-body">
                    <?php echo $secondary_page_info; ?>
                </div>
                <div class="modal-footer">
            	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        	</div>
      	</div>
    </div><!-- ./modal -->
	<?php } ?>
	<?php } // end if (($section == "admin") && (($action == "update") || ($action == "assign"))) ?>
</div><!-- ./bcoem-admin-element hidden-print -->
<?php } // end if ($section != "step5") ?>

<?php if (!empty($output_datatables_body)) {
echo $output_assignment_modals;
?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : <?php echo $output_datatables_bPaginate; ?>,
			"sPaginationType" : "<?php echo $output_datatables_sPaginationType; ?>",
			"bLengthChange" : <?php echo $output_datatables_bLengthChange; ?>,
			"iDisplayLength" : <?php echo round($_SESSION['prefsRecordPaging']); ?>,
			"sDom": 'fprtp',
			"bStateSave" : <?php echo $output_datatables_bStateSave; ?>,
			"aaSorting": [<?php echo $output_datatables_aaSorting; ?>],
			"bProcessing" : <?php echo $output_datatables_bProcessing; ?>,
			"aoColumns": [ <?php echo $output_datatables_aoColumns; ?> ]
			} );
		} );
	$(window).load(function(){
		$("#checkAll").change(function () {
			$("input:checkbox").prop('checked', $(this).prop("checked"));
		});
	});
</script>
<table class="table table-responsive table-bordered <?php if ($filter != "bos") echo "table-striped"; ?>" id="sortable">
<thead>
<?php echo $output_datatables_head; ?>
</thead>
<tbody>
<?php echo $output_datatables_body; ?>
</tbody>
</table>
<?php if (!empty($form_submit_url)) { ?>
<div class="bcoem-admin-element hidden-print">
	<input type="submit" name="Submit" id="helpUpdateJudgeAssign" class="btn btn-primary" aria-describedby="helpBlock" value="<?php echo $form_submit_button; ?>" />
    <span id="helpBlock" class="help-block"><?php echo $form_submit_button_help; ?></span>
</div>
</form>
<?php } ?>

<?php } // end if (($action == "default") && (!empty($output_datatables_body)))
elseif (($section != "step5") && ($action == "assign") && ($filter != "default") && (empty($output_datatables_body))) {
$output_none = "<p>No participants have been assigned to the ";
if ($filter == "stewards") $output_none .= "steward pool.</p><p><a href=\"".$base_url."index.php?section=admin&amp;go=judging&amp;action=assign&amp;filter=stewards\" class=\"btn btn-primary\">Assign Stewards</a></p>";
else $output_none .= "judge pool.</p><p><a href=\"".$base_url."index.php?section=admin&amp;go=judging&amp;action=assign&amp;filter=judges\" class=\"btn btn-primary\">Assign Judges</a></p>";
echo $output_none;
}

// -------------------------------- Add/Edit Form ---------------------------------------------

if (($output_add_edit) && ($msg != 9)) { 
	if (!empty($form_submit_url)) echo $form_submit_url; 
?>

<script type="text/javascript">
	$(document).ready(function() {

		<?php if (((!empty($row_judging['judgingLocType'])) && ($row_judging['judgingLocType'] == "0")) || ($section == "step5")) { ?>
		$('#judgingDateEndDiv').hide();
		$('#helpBlockLocation2').hide();
		$("#judgingLocationLabel").html("Session Address");
		<?php } elseif ((!empty($row_judging['judgingLocType'])) && ($row_judging['judgingLocType'] == "1")) { ?>
		$('#judgingDateEndDiv').show();
		$('#helpBlockLocation1').hide();
		$('#helpBlockLocation2').show();
		$("#judgingLocationLabel").html("Entry Distribution to Judges");
		<?php } else { ?>
		$('#judgingDateEndDiv').hide();
		$('#helpBlockLocation2').hide();
		$("#judgingLocationLabel").html("Session Address");
		<?php } ?>
		
		$('#judgingDate').datetimepicker({
			format: 'YYYY-MM-DD hh:mm A'
		});
		
		$('#judgingDateEnd').datetimepicker({
			format: 'YYYY-MM-DD hh:mm A'
		});

		$("input[name$='judgingLocType']").click(function() {
	        if ($(this).val() == "1") {
	            $("#helpBlockLocation1").hide("fast");
	            $("#judgingDateEndDiv").show("fast");
	            $("#helpBlockLocation2").show("fast");
	            $("#judgingLocationLabel").html("Entry Distribution to Judges");
	            $("#judgingDateEnd").prop("required",true);
	        }
	        else {
	            $("#judgingDateEndDiv").hide("fast");
	            $("#helpBlockLocation2").hide("fast");
	            $("#helpBlockLocation1").show("fast");
	            $("#judgingLocationLabel").html("Session Address");
	            $("#judgingDateEnd").prop("required",false);
	            $("#judgingDateEnd").val("");
	        }
	    });

	});
</script>

<div class="form-group"><!-- Form Group REQUIRED Text Input -->
	<label for="judgingLocName" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Session Name</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<div class="input-group has-warning">
			<!-- Input Here -->
			<input class="form-control" id="judgingLocName" name="judgingLocName" type="text" size="10" maxlength="255" value="<?php if ($action == "edit") echo $row_judging['judgingLocName']; ?>" placeholder="" autofocus required>
			<span class="input-group-addon" id="judgingTime2" data-tooltip="true" title="<?php echo $form_required_fields_02; ?>"><span class="fa fa-star"></span></span>
		</div>
		<span class="help-block">Provide the name of the judging location.</span>
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="judgingLocType" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Session Type</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="radio">
            <!-- Input Here -->
            <label>
                <input type="radio" name="judgingLocType" value="0" id="judgingLocType_0"  <?php if (($section == "step5") || ($action == "add")) echo "CHECKED"; if ((isset($row_judging['judgingLocType'])) && ($section != "step5") && ($row_judging['judgingLocType'] == "0")) echo "CHECKED";  ?> required /> Traditional <small>(typically a single day in a central location)</small>
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="judgingLocType" value="1" id="judgingLocType_1" <?php if ((isset($row_judging['judgingLocType'])) && ($section != "step5") && ($row_judging['judgingLocType'] == "1")) echo "CHECKED"; ?> required /> Distributed <small>(multi-day and/or multi-location)</small>
            </label>
        </div>
        <span class="help-block">Indicate whether judge teams in this session will be evaluating entries at a single, designated location, typically collectively, or over a series of days in various locations. For example, choose <em>Distributed</em> if judges will be evaluating entries virtually - synchronously or asynchronously - or if locations will be ad-hoc, such as in a judge team member's home.</span>
    	<span class="help-block">Traditional sessions only require a start time. Distributed sessions require a start AND end time.</span>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group REQUIRED Text Input -->
	<label for="judgingDate" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Session Start Date/Time</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<div class="input-group date has-warning">
			<!-- Input Here -->
			<input class="form-control" id="judgingDate" name="judgingDate" type="text" value="<?php if ($action == "edit") echo $judging_date; ?>" placeholder="<?php if (strpos($section, "step") === FALSE) echo $current_date." ".$current_time; ?>" required>
			<span class="input-group-addon" data-tooltip="true" title="<?php echo $form_required_fields_02; ?>"><span class="fa fa-star"></span></span>
		</div>
		<span class="help-block">Provide an start date and time for the session.</span>
	</div>
</div><!-- ./Form Group -->

<div id="judgingDateEndDiv"  class="form-group"><!-- Form Group REQUIRED Text Input -->
	<label for="judgingDateEnd" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Session End Date/Time</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<div class="input-group date has-warning">
			<!-- Input Here -->
			<input class="form-control" id="judgingDateEnd" name="judgingDateEnd" type="text" value="<?php if ($action == "edit") echo $judging_end_date; ?>" placeholder="<?php if (strpos($section, "step") === FALSE) echo $current_date." ".$current_time; ?>">
			<span class="input-group-addon" data-tooltip="true" title="<?php echo $form_required_fields_02; ?>"><span class="fa fa-star"></span></span>
		</div>
		<span class="help-block">For a distributed session, it is required that you provide an end date and time that will serve as a deadline for judges to submit their evaluations.</span>
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group REQUIRED Text Input -->
	<label id="judgingLocationLabel" for="judgingLocation" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Session Address</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<div class="input-group has-warning">
			<!-- Input Here -->
			<input class="form-control" id="judgingLocation" name="judgingLocation" type="text" size="10" maxlength="255" value="<?php if ($action == "edit") echo $row_judging['judgingLocation']; ?>" placeholder="" required>
			<span class="input-group-addon" data-tooltip="true" title="<?php echo $form_required_fields_02; ?>"><span class="fa fa-star"></span></span>
		</div>
        <span id="helpBlockLocation1" class="help-block">Provide the street address, city, and zip/postal code where the session will take place.</span>
        <span id="helpBlockLocation2" class="help-block">Inform judges how they will receive their entries to evaluate (e.g., a designated pick-up location with address, shipped directly, etc.). 255 character maximum.</span>
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group REQUIRED Text Input -->
	<label for="judgingRounds" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Session Rounds</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<div class="input-group has-warning">
			<!-- Input Here -->
			<input class="form-control" id="judgingRounds" name="judgingRounds" type="number" size="10" maxlength="255" value="<?php if ($action == "edit") echo $row_judging['judgingRounds']; ?>" placeholder="" required>
        	<span class="input-group-addon" data-tooltip="true" title="<?php echo $form_required_fields_02; ?>"><span class="fa fa-star"></span></span>
        </div>
        <span class="help-block">Provide the number of judging rounds anticipated for this session (see the <a class="hide-loader" href="https://www.bjcp.org/rules.php" target="_blank">BJCP's definition of a session</a> in their rules).</span>
	</div>
</div><!-- ./Form Group -->

<?php if (!empty($form_submit_button)) { ?>
<div class="bcoem-admin-element hidden-print">
	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
			<input type="submit" name="Submit" id="helpUpdateJudgeAssign" class="btn btn-primary" value="<?php echo $form_submit_button; ?>" />
		</div>
	</div>
</div>
<?php } ?>
<input type="hidden" name="relocate" value="<?php echo $base_url."index.php?section=admin&amp;go=judging"; ?>">
</form>
<?php } ?>