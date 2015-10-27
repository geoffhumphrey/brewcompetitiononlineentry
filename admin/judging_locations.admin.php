<?php 

/**
 * Module:      judging_locations.admin.php
 * Description: This module houses all participant (brewer) related functionality
 *              involved in assigning participants a role - judge, steward, staff.
 *              Also provids judging location related functions - add, edit, delete.
 *
 */
 
/* ---------------- Rebuild Info ---------------------

Beginning with the 1.3.0 release, an effort was begun to separate the programming
layer from the presentation layer for all scripts with this header.

All Admin pages have certain variables in common that build the page:
  $subtitle = the <h2> subtitle of the page
  $primary_page_info = any information related to the page
  $goto_nav = the "Back to Admin Dashboard" and other "Back to..." navigation
  $secondary_nav = other navigation elements related to the subpage
  $secondary_page_info = detailed information that comes after the nav elements
  
  $form_submit_url = the processing url for the form
  $form_submit_button = the form submit button element
  
  DEFAULTS for all of the following are defined in constants.inc.php but can be overridden by defining on the page 
  $output_datatables_bPaginate = whether or not to paginate the DT output - default is true
  $output_datatables_sPaginationType = type of pagination links output - default is full_numbers
  $output_datatables_bLengthChange = whether or not the DT output will allow length changes - default is true
  $output_datatables_iDisplayLength = limiting of the number of items the DT displays - default is round($_SESSION['prefsRecordPaging'])
  $output_datatables_sDom = the order of DT elements - default is irftip
  $output_datatables_bStateSave = true or false to save the state of the DT after refresh - default is false
  $output_datatables_bProcessing = true or false to show a "processing" message -default is false
  $output_datatables_aaSorting = the output in the DataTables JS for sort order - always customized for each display
  $output_datatables_aoColumns = the output in the DataTables JS for columns - always customized for each display
  
  $output_datatables_head = the output for DataTables placed in the <thead> tag
  $output_datatables_body = the output for DataTables placed in the <tbody> tag
  $output_datatables_edit_link = the link to edit the record
  $output_datatables_delete_link = the link to delete the record
  $output_datatables_print_link = the link to print the record or output to print
  $output_datatables_view = the link to view the record's detail
  $output_datatables_actions = compiles all of the "actions" links (edit, delete, print, view, etc.)
  
  ADD/EDIT SCREENS VARIABLE
  $output_add_edit = whether to run/display the add/edit functions


*/

include(DB.'judging_locations.db.php');

// Set Vars
$output_datatables_head = "";
$output_datatables_body = "";
$output_add_edit = "";
$filter_readable = "";
$primary_page_info = "";
$secondary_nav = "";
$secondary_page_info = "";
$form_submit_url = "";
$form_submit_button = "";
$output_no_records = "";
$goto_nav = "";


if ($filter == "judges") 	$staff_row_field = "staff_judge";
if ($filter == "stewards") 	$staff_row_field = "staff_steward";
if ($filter == "staff") 	$staff_row_field = "staff_staff";
if ($filter == "bos") 		$staff_row_field = "staff_judge_bos";

if ($filter == "judges") 	$filter_readable .= "Judges";  
if ($filter == "stewards") 	$filter_readable .= "Stewards";
if ($filter == "staff") 	$filter_readable .= "Staff"; 
if ($filter == "bos") 		$filter_readable .= "Best of Show Judges";

// *****************************************************************************
// ---------------------- Top of Page Vars -------------------------------------
// *****************************************************************************


// Build Subtitle
$subtitle = "<h2>";
if ($action == "add") $subtitle .= "Add a Judging Location"; 
elseif ($action == "edit") $subtitle .= "Edit a Judging Location"; 
elseif ($action == "update") $subtitle .= "Make Final ".$filter_readable." Location Assignments"; 
elseif ($action == "assign") $subtitle .= "Assign Participants as ".$filter_readable;
else $subtitle .= "Judging Locations &amp; Dates";
$subtitle .= "</h2>";


// Build Page Info

if (($section == "admin") && (($action == "update") || ($action == "assign"))) {
	$primary_page_info .= "<p>";
	if (($bid == "default") && ($filter != "bos")) $primary_page_info .= "Choose ".$filter." to assign.";  
	elseif ($bid != "default") $primary_page_info .= "Check below which ".$filter." will be assigned to the ".$row_judging['judgingLocName']. " location."; 
	elseif ($filter == "bos") $primary_page_info .= "Choose the judges that will judge in the Best of Show round(s) and be awarded 0.5 BJCP experience points.";
	$primary_page_info .= "</p>";
}
    
if ($section != "step5") {
// Build Go To Nav
	$goto_nav .= "<div class='adminSubNavContainer'>";
	  // Back to Dashboard
	$goto_nav .= "<span class='adminSubNav'>";
	$goto_nav .= "<span class='icon'><img src='".$base_url."images/arrow_left.png' alt='Back' title='Back'></span><a href='".$base_url."index.php?section=admin'>Back to Admin Dashboard</a>";
	$goto_nav .= "</span>";
	
	if ($action == "assign") {
	  	// Back to Main Functional Area
		$goto_nav .= "<span class='adminSubNav'>";
		$goto_nav .= "<span class='icon'><img src='".$base_url."images/arrow_left.png' alt='Back' title='Back'></span><a href='".$base_url."index.php?section=admin&amp;go=participants'>Back to Participants</a>";
		$goto_nav .= "</span>";
	}
	
	if (($section == "admin") && ((($action == "add") || ($action == "edit")) && ($section != "step3"))) {
		$goto_nav .= "<span class='adminSubNav'>";
		$goto_nav .= "<span class='icon'><img src='".$base_url."images/arrow_left.png' alt='Back' title='Back'></span><a href='".$base_url."index.php?section=admin&amp;go=judging'>Back to Judging Location List</a>";
		$goto_nav .= "</span>";
	}
	
	$goto_nav .= "</div>";

if (($section == "admin") && (($section != "step5") && ($filter == "default"))) {
	if ($action != "add") {
		$goto_nav .= "<div class='adminSubNavContainer'>";
		$goto_nav .= "<span class='adminSubNav'>";
		$goto_nav .= "<span class='icon'><img src='".$base_url."images/page_add.png' alt='Back' title='Back'></span><a href='".$base_url."index.php?section=admin&amp;go=judging&amp;action=add'>Add a Judging Location</a>";
		$goto_nav .= "</span>";
		$goto_nav .= "</div>";
	}
}
}

// Build Secondary Nav

if (($filter == "default") && ($msg == "9"))  {
	$secondary_nav .= "<div class='error'>Add another judging location, date, or time?</div>";
	$secondary_nav .= "<div class='adminSubNavContainer'>";
	$secondary_nav .= "<span class='adminSubNav'>";
	$secondary_nav .= "<span class='icon'><img src='".$base_url."images/tick.png' alt='Yes' title='Yes'></span>";
	$secondary_nav .= "<a href='".$base_url;
	if ($section == "step5") $secondary_nav .= "setup.php?section=step5"; 
	else $secondary_nav .= "index.php?section=admin&amp;go=judging"; 
	$secondary_nav .= "'>Yes</a>";
	$secondary_nav .= "</span>";
	$secondary_nav .= "<span class='adminSubNav'>";
	$secondary_nav .= "<span class='icon'><img src='".$base_url."images/cross.png' alt='Yes' title='Yes'></span>";
	$secondary_nav .= "<a href='".$base_url;
	if ($section == "step5") $secondary_nav .= "setup.php?section=step6"; else $secondary_nav .= "index.php?section=admin";
    $secondary_nav .= "'>No</a>";
	$secondary_nav .= "</span>";
	$secondary_nav .= "</div>";
}

if (($section == "admin") && (($action == "update") || ($action == "assign"))) {
	$secondary_nav .= "<div class='adminSubNavContainer'>";
	if ($filter != "judges") {
		$secondary_nav .= "<span class='adminSubNav'>";
		$secondary_nav .= "<span class='icon'><img src='".$base_url."images/user_edit.png' alt='' title=''></span><a href='".$base_url."index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges'>Assign/Unassign Participants as Judges</a>";
		$secondary_nav .= "</span>";
		$secondary_nav .= "<span class='adminSubNav'>";
		$secondary_nav .= "<span class='icon'><img src='".$base_url."images/user_edit.png' alt='' title=''></span><a href='".$base_url."index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=bos'>Assign/Unassign Participants as BOS Judges</a>";
		$secondary_nav .= "</span>";
		$secondary_nav .= "</div>";
	}
	$secondary_nav .= "<div class='adminSubNavContainer'>";
	if ($filter != "stewards") {
		$secondary_nav .= "<span class='adminSubNav'>";
		$secondary_nav .= "<span class='icon'><img src='".$base_url."images/user_edit.png' alt='' title=''></span><a href='".$base_url."index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards'>Assign/Unassign Participants as Stewards</a>";
		$secondary_nav .= "</span>";
	}
	if ($filter != "staff") {
		$secondary_nav .= "<span class='adminSubNav'>";
		$secondary_nav .= "<span class='icon'><img src='".$base_url."images/user_edit.png' alt='' title=''></span><a href='".$base_url."index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=staff'>Assign/Unassign Participants as Staff</a>";
		$secondary_nav .= "</span>";
	}
	$secondary_nav .= "</div>";
}

// Build Secondary Page Info

if ($filter == "judges") {
	$secondary_page_info .= "<p>According to <a href='http://www.bjcp.org/rules.php' target='_blank'>BJCP rules</a>, &quot;Judges earn points at a rate of 0.5 judging points per session, but the following limitations apply:</p>";
	$secondary_page_info .= "<ul>";
  	$secondary_page_info .= "<li>Judges earn a minimum of 1.0 point per competition.</li>";
  	$secondary_page_info .= "<li>Judges earn a maximum of 1.5 points per day.</li>";
	$secondary_page_info .= "</ul>";
	$secondary_page_info .= "<p>&quot;The total number of judging points a judge may earn in a competition is limited by the organizer points. Participants <em>may not</em> earn both Judge and Steward points in a single competition.&quot;</p>";
}

if ($filter == "stewards") {
	$secondary_page_info .= "<p>According to <a href='http://www.bjcp.org/rules.php' target='_blank'>BJCP rules</a>, &quot;Stewards receive 0.5 non-judging points per day with a maximum of 1.0 points per competition. Participants <em>may not</em> earn both Judge and Steward points in a single competition. Steward points are awarded separately from Staff points and do not come from the Staff point pool. A program participant may earn both Steward and Staff points.&quot;</p>";
}

if ($filter == "staff") {
	
	$secondary_page_info .= "<h3>Staff</h3>";
	$secondary_page_info .= "<p>According to <a href='http://www.bjcp.org/rules.php' target='_blank'>BJCP rules</a>, staff members are &quot;...program participants who, under the direction of the Organizer, perform an active role in support of the competition other than as a Judge, Steward, or BOS Judge.&quot;";
	$secondary_page_info .= "<p>If a staff member is not on this list, <a href='".$base_url."index.php?section=admin&go=participants&action=add'>add them to the database</a>.</p>";
	
	$secondary_page_info .= "<h3>Organizer</h3>";
	$secondary_page_info .= "<p>According to <a href='http://www.bjcp.org/rules.php' target='_blank'>BJCP rules</a>, the Organizer is &quot;...the single program participant who completes and signs the application to register or sanction a competition and who in all ways assumes responsibility for the direction of that competition.&quot;</p>";
	$secondary_page_info .= "<p>If the organizer is not on this list, <a href='".$base_url."index.php?section=admin&go=participants&action=add'>add them to the database</a>.</p>";

}

if ($filter == "bos") {
	$secondary_page_info .= "<p>According to <a href='http://www.bjcp.org/rules.php' target='_blank'>BJCP rules</a>, &quot;Best-of-Show (BOS) Judges&nbsp;are eligible to receive a 0.5 judging point bonus if they judge in any BOS panel in a competition. The BOS bonus is in addition to any other judging and non-judging points earned in the competition, and may only be awarded to a single judge once per competition. BOS points may only be awarded if a competition has at least 30 entries in at least five beer and/or three mead/cider categories.</p>";
	$secondary_page_info .= "<p>&quot;The number of judges eligible to receive the BOS bonus is correlated to the number of entries in each BOS panel as follows:</p>";
	$secondary_page_info .= "<ul>";
	$secondary_page_info .= "<li>5-14 BOS entries, including beer = 3 BOS Judges</li>";
	$secondary_page_info .= "<li>3-14 BOS meads and/or ciders (only) = 3 BOS Judges</li>";
	$secondary_page_info .= "<li>15 or more BOS entries of any type or combination = 5 BOS Judges&quot;</li>";
	$secondary_page_info .= "</ul>";
}


// Judging Locations & Dates List
if ($section != "step5") {
	if (($totalRows_judging_locs > 0) && ($action == "default")) {
		$output_datatables_aaSorting = "[1,'asc']";
		$output_datatables_aoColumns = "null, null, null, null,	null, { \"asSorting\": [  ] }";
		$output_datatables_head .= "<tr>";
		$output_datatables_head .= "<th width='25%' class='dataHeading bdr1B'>Name</th>";
		$output_datatables_head .= "<th width='15%' class='dataHeading bdr1B'>Date</th>";
		$output_datatables_head .= "<th width='15%' class='dataHeading bdr1B'>Start Time</th>";
		$output_datatables_head .= "<th width='30%' class='dataHeading bdr1B'>Address</th>";
		$output_datatables_head .= "<th width='10%' class='dataHeading bdr1B'># of Rounds</th>";
		$output_datatables_head .= "<th class='dataHeading bdr1B'>Actions</th>";
		$output_datatables_head .= "</tr>";
		$output_datatables_head .= "";
		
		do {
			
			$output_datatables_edit_link = build_action_link("pencil",$base_url,"admin","judging","edit",$filter,$row_judging_locs['id'],$dbTable,"Edit ".$row_judging_locs['judgingLocName']);
			
			$output_datatables_delete_link = build_action_link("bin_closed",$base_url,"admin","judging","delete",$filter,$row_judging_locs['id'],$judging_locations_db_table,"Are you sure you want to delete ".$row_judging_locs['judgingLocName']."? This cannot be undone");
			
			$output_datatables_actions = $output_datatables_edit_link.$output_datatables_delete_link;
			
			$output_datatables_body .= "<tr>";
			$output_datatables_body .= "<td class='dataList'>".$row_judging_locs['judgingLocName']."</td>";
			$output_datatables_body .= "<td class='dataList'>".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging_locs['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date")."</td>";
			$output_datatables_body .= "<td class='dataList'>".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging_locs['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "time-gmt")."</td>";
			$output_datatables_body .= "<td class='dataList'>".$row_judging_locs['judgingLocation']."</td>";
			$output_datatables_body .= "<td class='dataList'>".$row_judging_locs['judgingRounds']."</td>";
			$output_datatables_body .= "<td class='dataList' nowrap='nowrap'>".$output_datatables_actions."</td>";
			$output_datatables_body .= "</tr>";
			
		} while($row_judging_locs = mysql_fetch_assoc($judging_locs));
		
	} // end if (($totalRows_judging_locs > 0) && ($action == "default"))
} // end if ($section != "step5")


// *****************************************************************************
// ---------------------- List Judging Locations ---------------------------
// *****************************************************************************
if ($section != "step5") {
	if ((($action == "update") && ($filter != "default") && ($bid != "default")) || ($action == "assign")) { 
	
		$form_submit_url .= build_form_action($base_url,$section,"default","update",$filter,"default",$brewer_db_table,FALSE);
		
		// Needed to place the organizer designation dropdown after <form> element
		if ($filter == "staff") {
			$form_submit_url .= "<p><strong>Designate the Competition Organizer:</strong><span class='data'>";
			$form_submit_url .= "<select name='Organizer'>";
			$form_submit_url .= "<option value=''>Choose Below:</option>";
			do {
				$form_submit_url .= "<option value='".$row_brewers['uid']."'";
				if (($row_brewers['uid'] == $row_organizer['uid'])) $form_submit_url .= " SELECTED";
				$form_submit_url .= ">".$row_brewers['brewerLastName'].", ".$row_brewers['brewerFirstName']."</option>";
			} while ($row_brewers = mysql_fetch_assoc($brewers));
			$form_submit_url .= "</select></span></p>";
		}
		$form_submit_button .= "<p><input type='submit' class='button' name='Submit' value='";
		if ($action == "update") $form_submit_button .= "Assign to ".$row_judging['judgingLocName']; 
		elseif ($action == "assign") $form_submit_button .= "Assign as ".brewer_assignment($filter,"3",$id,"default"); 
		else $form_submit_button .= "Submit";
		$form_submit_button .= "' />";
		$form_submit_button .= "&nbsp;";
		$form_submit_button .= "<span class='required'>Click";
		if ($action == "update") $form_submit_button .= "Assign to ".$row_judging['judgingLocName']; 
		elseif ($action == "assign") $form_submit_button .= "Assign as ".brewer_assignment($filter,"3",$id,"default"); 
		else $form_submit_button .= "Submit";
		$form_submit_button .= " <em>before</em> paging through records.</span></p>";
	
		if ($filter == "judges") 		$output_datatables_aaSorting = "[4,'desc']";
		elseif ($filter == "bos") 		$output_datatables_aaSorting = "[5,'desc']";
		else 							$output_datatables_aaSorting = "[1,'asc']";
		
		if ($filter == "judges") 		$output_datatables_aoColumns = "{ \"asSorting\": [  ] }, null, null, null, null, null, null";
		elseif ($filter == "stewards") 	$output_datatables_aoColumns = "{ \"asSorting\": [  ] }, null, null, null, null";
		elseif ($filter == "staff")		$output_datatables_aoColumns = "{ \"asSorting\": [  ] }, null, null";
		elseif ($filter == "bos") 		$output_datatables_aoColumns = "{ \"asSorting\": [  ] }, null, null, null, null, null";
		
		
		$output_datatables_head .= "<tr>";
		$output_datatables_head .= "<th width='1%' class='dataHeading bdr1B'><input type='checkbox' onclick='toggleChecked(this.checked)' title='Check/Uncheck All'></th>";
		$output_datatables_head .= "<th width='20%' class='dataHeading bdr1B'>Name</th>";
		$output_datatables_head .= "<th class='dataHeading bdr1B'>Assigned As</th>";
		if ($filter == "bos") $output_datatables_head .= "<th width='25%' class='dataHeading bdr1B'>Placing Entries</th>";
		if (($filter == "judges") || ($filter == "bos")) {
			$output_datatables_head .= "<th width='10%' class='dataHeading bdr1B'>ID</th>";
			$output_datatables_head .= "<th width='15%' class='dataHeading bdr1B'>Rank</th>";
		}
		if (($filter == "judges") || ($filter == "stewards")) {
			$output_datatables_head .= "<th width='25%' class='dataHeading bdr1B'>Location Preferences</th>";
			$output_datatables_head .= "<th width='15%' class='dataHeading bdr1B'>Has Entries In...</th>";
		}
		$output_datatables_head .= "</tr>";
		
		do {
			
			$brewer_assignment = brewer_assignment($row_brewer['uid'],"1","default",$dbTable);
			$assignment_checked = str_replace(", ",",",$brewer_assignment);
			//$assignment_checked = explode(",",$assignment_checked);
			if ((!empty($assignment_checked)) && ($filter == "judges") && (strpos($brewer_assignment,'Judge') !== false)) $checked = "CHECKED";
			elseif ((!empty($assignment_checked)) && ($filter == "stewards") && (strpos($brewer_assignment,'Steward') !== false)) $checked = "CHECKED";
			elseif ((!empty($assignment_checked)) && ($filter == "staff") && (strpos($brewer_assignment,'Staff') !== false)) $checked = "CHECKED";
			elseif ((!empty($assignment_checked)) && ($filter == "bos") && (strpos($brewer_assignment,'BOS Judge') !== false)) $checked = "CHECKED";
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
			if (($filter == "judges") || ($filter == "stewards")) {
				if ($filter == "judges") $exploder = $row_brewer['brewerJudgeLocation'];
				if ($filter == "stewards") $exploder = $row_brewer['brewerStewardLocation'];
				$a = explode(",",$exploder);
				$output = "";
				if ($exploder != "") { 
					sort($a);
					foreach ($a as $value) {
						if ($value != "") {
							$b = substr($value, 2);
							$output .= judging_location_avail($b,$value);
							}
						}
					}
				$output = rtrim($output,"<br>");
			}
			if (empty($output)) $output_location = "<span class='icon'><img src='".$base_url."images/exclamation.png'></span>None specified.";
			else $output_location = $output;
			
			$output_datatables_body .= "<tr>";
			$output_datatables_body .= "<td class='dataList'>";
			$output_datatables_body .= "<input type='hidden' name='uid[]' value='".$row_brewer['uid']."' />";
			$output_datatables_body .= "<input name='".$staff_row_field.$row_brewer['uid']."' type='checkbox' class='checkbox' value='1' ".$checked; 
			if (($filter == "staff") && ($row_organizer['uid'] == $row_brewer['uid'])) $output_datatables_body .= " DISABLED";
			if (($filter == "stewards") && (strpos($brewer_assignment,'Judge') !== false)) $output_datatables_body .= " DISABLED";
			if (($filter == "judges") && (strpos($brewer_assignment,'Steward') !== false)) $output_datatables_body .= " DISABLED";
			$output_datatables_body .= " />";
			$output_datatables_body .= "</td>";
			$output_datatables_body .= "<td class='dataList'>".$row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']."</td>";
			$output_datatables_body .= "<td class='dataList'>".$brewer_assignment."</td>";
			
			if ($filter == "bos") {
				$output_datatables_body .= "<td class='dataList'>";
				if (!empty($bos_judge_eligible)) $output_datatables_body .= $judge_places; 
				else $output_datatables_body .= "&nbsp;";
				$output_datatables_body .= "</td>";
			}
			
			if (($filter == "judges") || ($filter == "bos")) {
				
				$bjcp_rank = explode(",",$row_brewer['brewerJudgeRank']);
				$display_rank = bjcp_rank($bjcp_rank[0],1);
			
				$output_datatables_body .= "<td class='dataList'>".strtoupper($row_brewer['brewerJudgeID'])."</td>";
				$output_datatables_body .= "<td class='dataList'>".$display_rank;
				if ($row_brewer['brewerJudgeMead'] == "Y") $output_datatables_body .= "<br /><em>Certified Mead Judge</em>";
				if (!empty($bjcp_rank[1])) {
					$output_datatables_body .= "<em>".designations($row_brewer['brewerJudgeRank'],$bjcp_rank[0])."</em>";
				}
				$output_datatables_body .= "</td>";		
			}
			
			if (($filter == "judges") || ($filter == "stewards")) { 			
				$output_datatables_body .= "<td class='dataList'>".$output_location."</td>";
				$output_datatables_body .= "<td class='dataList'>".judge_entries($row_brewer['uid'],1)."</td>";
			}
			
			$output_datatables_body .= "</tr>";
			
		} while ($row_brewer = mysql_fetch_assoc($brewer));
	
	} // end if (($totalRows_brewer > 0) && ((($action == "update") && ($filter != "default") && ($bid != "default")) || ($action == "assign")))
}
// *****************************************************************************
// ---------------------- Add/Edit Judging Locations ---------------------------
// *****************************************************************************

if ((($action == "add") || ($action == "edit")) || ($section == "step5")) {
	$output_add_edit = TRUE;

	if ($section == "step5") $action = "add"; else $action = $action;
	if ($go == "default") $go = "setup"; else $go = $go;
	if ($section == "step5") $form_submit_url .= build_form_action($base_url,$section,$go,$action,$filter,"1",$judging_locations_db_table,TRUE);
	else $form_submit_url .= build_form_action($base_url,$section,$go,$action,$filter,$row_judging['id'],$judging_locations_db_table,TRUE);
	$form_submit_button .= "<input type='submit' class='button' value='";
	if ($action == "edit") $form_submit_button .= "Update"; 
	else $form_submit_button .= "Submit";
	$form_submit_button .= "'>";
	
	$judging_date = "";
	$judging_time = "";
	if ($action == "edit") $judging_date .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date");
	if ($action == "edit") $judging_time .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "time");
		
} // end if ((($action == "add") || ($action == "edit")) || ($section == "step5")) 



// ----------------------------------------- Presentation ------------------------------------------


// Display Top Of Page Elements (Subtitle, Primary Page Info, Nav, and Secondary Page Info)
echo $subtitle;
echo $primary_page_info;
if ($section != "step5") echo $goto_nav;
echo $secondary_nav;
echo $secondary_page_info;

// Display HTML/JS elements and compiled PHP elements
if (!empty($output_no_records)) echo $output_no_records; 
if (!empty($output_datatables_body)) { 
	if (!empty($form_submit_url)) echo $form_submit_url.$form_submit_button; 
?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : <?php echo $output_datatables_bPaginate; ?>,
			"sPaginationType" : "<?php echo $output_datatables_sPaginationType; ?>",
			"bLengthChange" : <?php echo $output_datatables_bLengthChange; ?>,
			"iDisplayLength" : <?php echo round($_SESSION['prefsRecordPaging']); ?>,
			"sDom": '<?php echo $output_datatables_sDom; ?>',
			"bStateSave" : <?php echo $output_datatables_bStateSave; ?>,
			"aaSorting": [<?php echo $output_datatables_aaSorting; ?>],
			"bProcessing" : <?php echo $output_datatables_bProcessing; ?>,
			"aoColumns": [ <?php echo $output_datatables_aoColumns; ?> ]
			} );
		} );
</script>
<table class="dataTable" id="sortable">
<thead>
<?php echo $output_datatables_head; ?>
</thead>
<tbody>
<?php echo $output_datatables_body; ?>
</tbody>
</table>
<?php if (!empty($form_submit_url)) echo $form_submit_button."</form>"; ?>

<?php } // end if (($action == "default") && (!empty($output_datatables_body)))


// -------------------------------- Add/Edit Form ---------------------------------------------

if (($output_add_edit) && ($msg != 9)) { ?>
<script>
$(function() {
	$('#judgingDate').datepicker({ dateFormat: 'yy-mm-dd', showOtherMonths: true, selectOtherMonths: true, changeMonth: true, changeYear: true });
	$('#judgingTime').timepicker({ showPeriod: true, showLeadingZero: true });
	
});
</script>
<?php if (!empty($form_submit_url)) echo $form_submit_url; ?> 
<table>
  <tr>
    <td class="dataLabel">Date:</td>
    <td class="data"><input id="judgingDate" name="judgingDate" type="text" size="20" value="<?php echo $judging_date; ?>"></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Start Time:</td>
    <td class="data"><input id="judgingTime" name="judgingTime" size="10" value="<?php echo $judging_time; ?>"></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Name:</td>
    <td class="data"><input name="judgingLocName" size="30" value="<?php if ($action == "edit") echo $row_judging['judgingLocName']; ?>"></td>
    <td class="data"><span class="required">Required</span> <em>Provide the name of the judging location.</em></td>
  </tr>
  
  <tr>
    <td class="dataLabel">Address:</td>
    <td class="data"><input name="judgingLocation" size="50" value="<?php if ($action == "edit") echo $row_judging['judgingLocation']; ?>"></td>
    <td class="data"><span class="required">Required</span> <em>Provide the street address, city, and zip code.</em></td>
  </tr>
  <tr>
    	<td class="dataLabel">Judging Rounds:</td>
    	<td class="data"><input name="judgingRounds" size="5" value="<?php if ($action == "edit") echo $row_judging['judgingRounds']; else echo "2"; ?>"></td>
        <td class="data"><span class="required">Required</span> <em>Provide the number of judging rounds anticipated for this location (<strong>not</strong> including Best of Show).</em></td>
  	</tr>
</table>
<?php if (!empty($form_submit_button)) echo $form_submit_button; ?>
<input type="hidden" name="relocate" value="<?php echo $base_url."index.php?section=admin&amp;go=judging"; ?>">
</form>
<?php } ?>


<?php if (($action == "update") && ($bid == "default")) {  ?>
<table>
 <tr>
   <td class="dataLabel">Assign <?php echo brewer_assignment($filter,"3","default","default"); ?> To:</td>
   <td class="data">
   <select name="judge_loc" id="judge_loc" onchange="jumpMenu('self',this,0)">
	<option value=""></option>
    <?php do { ?>
	<option value="index.php?section=admin&amp;action=update&amp;go=judging&amp;filter=<?php echo $filter; ?>&amp;bid=<?php echo $row_judging['id']; ?>"><?php  echo $row_judging['judgingLocName']." ("; echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time").")"; ?></option>
    <?php } while ($row_judging = mysql_fetch_assoc($judging)); ?>
   </select>
  </td>
</tr>
</table>
<?php } // end if (($action == "update") && ($bid == "default")) ?>
