<?php 
/**
 * Module:      participants.admin.php
 * Description: This module houses all participant (brewer) related functionality
 *              including viewing a participant list, add/edit/delete, 
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
  $output_datatables_add_link = the link to add a record
  $output_datatables_edit_link = the link to edit the record
  $output_datatables_delete_link = the link to delete the record
  $output_datatables_print_link = the link to print the record or output to print
  $output_datatables_other_link = misc use link
  $output_datatables_view_link = the link to view the record's detail
  $output_datatables_actions = compiles all of the "actions" links (edit, delete, print, view, etc.)
  
  ADD/EDIT SCREENS VARIABLE
  $output_add_edit = whether to run/display the add/edit functions - default is FALSE

 * ---------------- END Rebuild Info --------------------- */

include (DB.'admin_participants.db.php');

// Set Vars
$subtitle = "";
$primary_page_info = "";
$goto_nav = "";
$secondary_nav = "";
$secondary_page_info = "";
$form_submit_url = "";
$form_submit_button = "";
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
$output_add_edit = FALSE;
$output_hide_print = "";
$output_assignment_modals = "";
$output_user_question_modals = "";
$assignment_modal_body = "";

if ($dbTable == "default") $pro_edition = $_SESSION['prefsProEdition'];
else $pro_edition = $row_archive_prefs['archiveProEdition'];

if ($pro_edition == 0) $edition = $label_amateur." ".$label_edition;
if ($pro_edition == 1) $edition = $label_pro." ".$label_edition;

if ($action == "print") {
	$output_hide_print .= "hidden-print";
}
else {
	$output_hide_print .= "hidden-md hidden-sm hidden-xs";	
	if (($dbTable == "default") && ($row_participant_count['count'] > $_SESSION['prefsRecordLimit']))	{ 
			echo "<div class='info'>The DataTables recordset paging limit of ".$_SESSION['prefsRecordLimit']." has been surpassed. Filtering and sorting capabilites are only available for this set of ".$_SESSION['prefsRecordPaging']." participants.<br />To adjust this setting, <a href='index.php?section=admin&amp;go=preferences'>change your installation's DataTables Record Threshold</a> (under the &ldquo;Performance&rdquo; heading in preferences) to a number <em>greater</em> than the total number of participants (".$row_participant_count['count'].").</div>";
	}
}


// *****************************************************************************
// ---------------------- Top of Page Vars -------------------------------------
// *****************************************************************************

// Build Subtitle
if ($filter == "judges") { 
	$subtitle .= "Available Judges"; 
	$csv = "avail_judges"; 
	}
elseif ($filter == "stewards") { 
	$subtitle .= "Available Stewards";
	$csv = "avail_stewards"; 
	}
elseif ($filter == "with_entries") { 
	$subtitle .= "Participants with Entries";
	$csv = "with_entries"; 
	}
elseif ($action == "add") $subtitle .= "Add a Participant"; 
else $subtitle .= "Participants"; 
if ($dbTable != "default") $subtitle .= ": All Participants (Archive ".get_suffix($dbTable).")"; 


// *****************************************************************************
// ---------------------- List Participants ---------------------------
// *****************************************************************************

$output_datatables_aaSorting = "";
$output_datatables_aoColumns = "";

if ($action == "print") {
	
	$output_datatables_aaSorting .= "";
	if ($psort == "judge_rank") $output_datatables_aaSorting .= "[5,'desc']";
	if ($psort == "judge_id")  $output_datatables_aaSorting .= "[4,'asc']";
	if ($psort == "club") $output_datatables_aaSorting .= "[1,'asc']";
	if (($psort == "default") || ($psort == "brewer_name")) $output_datatables_aaSorting .= "[0,'asc']";
	
	if ($filter == "default") 	{ 
		$output_datatables_aoColumns .= "{ \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }";
	}
	
	if ($filter == "judges") 	{ 
		$output_datatables_aoColumns .= "{ \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }";
	}
	
	if ($filter == "stewards") 	{
		$output_datatables_aoColumns .= "{ \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, null, null";
	}
	

} // end if ($action == "print")

else {

	if ($filter == "default") 	{ 
		if ($pro_edition == 1) $output_datatables_aaSorting .= "[1,'asc']";
		else  $output_datatables_aaSorting .= "[0,'asc']";
		$output_datatables_aoColumns .= "null, null, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, null, null";
		if ($dbTable == "default") 	$output_datatables_aoColumns .= ",  { \"asSorting\": [  ] }";
	}
	
	if ($filter == "judges") 	{ 
		$output_datatables_aaSorting .= "[0,'asc']";
		$output_datatables_aoColumns .= "null, null,	null, null, null, null, null, null, { \"asSorting\": [  ] }";
	}
	
	if ($filter == "stewards") 	{
		$output_datatables_aaSorting .= "[0,'asc']";
		$output_datatables_aoColumns .= "null, null,	null, null, null, null, { \"asSorting\": [  ] }";
	}
	
	if ($filter == "with_entries") 	{
		$output_datatables_aaSorting .= "[0,'asc']";
		$output_datatables_aoColumns .= "null, null, { \"asSorting\": [  ] }";
	}

}

if ($filter == "with_entries") {
	
	$output_datatables_head .= "<tr>";
	$output_datatables_head .= "<th>Name</th>";
	$output_datatables_head .= "<th>Entries</th>";
	if ($action != "print") $output_datatables_head .= "<th>Actions</th>";
	$output_datatables_head .= "</tr>";
	
	}
	
else {
	$output_datatables_head .= "<tr>";
	if ($pro_edition == 1) $output_datatables_head .= "<th>Contact Name</th>";
	else $output_datatables_head .= "<th>Name</th>";
	if ($action == "print") $output_datatables_head .= "<th>Info</th>";
	$output_datatables_head .= "<th class=\"".$output_hide_print."\">";
	if (($totalRows_judging > 0) && (($filter == "judges") || ($filter == "stewards"))) $output_datatables_head .= "Location(s) Available"; 
	else { 
		if ($pro_edition == 1) $output_datatables_head .= $label_organization;
		else $output_datatables_head .= $label_club;
	}
	$output_datatables_head .= "</th>";
	if ($filter == "default") { 
		$output_datatables_head .= "<th class=\"".$output_hide_print."\">Steward?</th>";
		$output_datatables_head .= "<th class=\"".$output_hide_print."\">Judge?</th>";
	}
	$output_datatables_head .= "<th>Assigned As</th>";
	if ($filter != "default") { 
		if ($filter == "judges") {
			$output_datatables_head .= "<th class=\"".$output_hide_print."\">ID</th>";
			$output_datatables_head .= "<th>Rank</th>";
			
		}
		$output_datatables_head .= "<th>Assigned to Table(s)</th>";
		$output_datatables_head .= "<th class=\"".$output_hide_print."\">Has Entries In...</th>";
		
	}
	$output_datatables_head .= "<th class=\"".$output_hide_print."\">Updated</th>";
	if (($action != "print") && ($dbTable == "default")) $output_datatables_head .= "<th>Actions</th>";
	$output_datatables_head .= "</tr>";
	
	$email_subject = "";
	if ($filter == "judges") $email_subject .= "Judging at ".$_SESSION['contestName']; 
	elseif ($filter == "stewards") $email_subject .= "Stewarding at ".$_SESSION['contestName']; 
	else $email_subject .= $_SESSION['contestName'];  
}

do {
	
	$output_datatables_add_link = "";
	$output_datatables_edit_link = "";
	$output_datatables_delete_link = "";
	$output_datatables_print_link = "";
	$output_datatables_other_link = "";
	$output_datatables_view_link = "";
	$output_datatables_email_link = "";
	$output_datatables_actions = "";
	
	$user_info = user_info($row_brewer['uid']);
	$user_info = explode("^",$user_info);
	
	if ($_SESSION['brewerCountry'] == "United States") $us_phone = TRUE; else $us_phone = FALSE;
		
	if ($dbTable != "default") $archive = get_suffix($dbTable);
	else $archive = "default"; 
	
	
	
	unset($brewer_assignment);
	$brewer_assignment = brewer_assignment($row_brewer['uid'],"1",$id,$dbTable,$filter,$archive);
	
	// Build Action Links
	// build_action_link($icon,$base_url,$section,$go,$action,$filter,$id,$dbTable,$alt_title) {
	
	if (!$archive_display) {
		$output_datatables_add_link = build_action_link("fa-beer",$base_url,"brew","entries","add",$row_brewer['uid'],"default","default","default",0,"Add an entry for ".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']);
		$output_datatables_edit_link = build_action_link("fa-pencil",$base_url,"brewer","admin","edit",$row_brewer['uid'],$row_brewer['id'],$dbTable,"default",0,"Edit ".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']."&rsquo;s user account information");
		if ($row_brewer['brewerEmail'] != $_SESSION['loginUsername']) $output_datatables_delete_link = build_action_link("fa-trash-o",$base_url,"admin","participants","delete",$row_brewer['uid'],$row_brewer['uid'],$brewer_db_table,"Are you sure you want to delete the participant account for ".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']."? ALL entries for this participant WILL BE DELETED as well. This cannot be undone.",0,"Delete ".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']."&rsquo;s account.");
		else $output_datatables_delete_link = "<span class=\"fa fa-lg fa-trash-o text-muted\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Silly, you cannot delete yourself, ".$_SESSION['brewerFirstName']."!\"></span>";
		if ($row_brewer['brewerEmail'] != $_SESSION['loginUsername']) $output_datatables_other_link = build_action_link("fa-lock",$base_url,"admin","make_admin","default","default",$row_brewer['uid'],"default","default",0,"Change ".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']."&rsquo;s User Level");
		else $output_datatables_other_link = "<span class=\"fa fa-lg fa-lock text-muted\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"You cannot change your own user level, ".$_SESSION['brewerFirstName'].".\"></span>";
		if (strpos($brewer_assignment,'Judge') !== false)  {
			$output_datatables_view_link = "<a href=\"".$base_url."output/labels.output.php?section=admin&amp;go=participants&amp;action=judging_labels&amp;id=".$row_brewer['id']."&amp;psort=5160\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Download Judge Scoresheet Labels for ".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']." - Letter (Avery 5160)\"><span class=\"fa fa-lg fa-file\"></span></a> <a href=\"".$base_url."output/labels.output.php?section=admin&amp;go=participants&amp;action=judging_labels&amp;id=".$row_brewer['id']."&amp;psort=3422\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Download Judge Scoresheet Labels for ".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']." - A4 (Avery 3422)\"><span class=\"fa fa-lg fa-file-text\"></span></a>";
		}
		else $output_datatables_view_link = "";
		$output_datatables_other_link2 = build_action_link("fa-user",$base_url,"user","default","username","admin",$row_brewer['uid'],"default","default",0,"Change ".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']."&rsquo;s email address");
		$output_datatables_email_link .= "<a href=\"mailto:".$row_brewer['brewerEmail']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Email ".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']." at ".$row_brewer['brewerEmail']."\"><span class=\"fa fa-lg fa-envelope\"></span></a>";
		
		if ($us_phone) {
			$output_datatables_phone_link = "<a href=\"#\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']."&rsquo;s phone number: ".format_phone_us($row_brewer['brewerPhone1'])."\"><span class=\"fa fa-lg fa-phone\"></span></a>";
		}
		
		else {
			$output_datatables_phone_link = "<a href=\"#\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']."&rsquo;s phone number: ".$row_brewer['brewerPhone1']."\"><span class=\"fa fa-lg fa-phone\"></span></a>";	
		}
		
		$output_datatables_user_question_link = "<a href=\"#\" data-tooltip=\"true\" data-toggle=\"modal\" data-target=\"#user-question-modal-".$row_brewer['uid']."\" data-placement=\"top\" title=\"Click to see ".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']."&rsquo;s secret question and answer\"><span class=\"fa fa-lg fa-question-circle\"></span></a>";
		
		$output_datatables_change_pwd = build_action_link("fa-key",$base_url,"admin","change_user_password","edit","default",$row_brewer['uid'],"default","default",0,"Change ".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']."&rsquo;s password");
		
		
		$output_datatables_actions = $output_datatables_add_link." ".$output_datatables_edit_link." ".$output_datatables_delete_link." ".$output_datatables_other_link." ".$output_datatables_email_link." ".$output_datatables_phone_link." ".$output_datatables_other_link2." ".$output_datatables_user_question_link." ".$output_datatables_change_pwd." ".$output_datatables_view_link;
	
	}
	
	if ($filter == "with_entries") {
		unset($entries);
		
		$output_datatables_body .= "<tr>";
		$output_datatables_body .= "<td><a name='".$row_brewer['uid']."'></a>".$row_brewer['brewBrewerLastName'].", ".$row_brewer['brewBrewerFirstName'];
		
		$explodies = explode(",",$row_brewer['Entries']);
		
		foreach ($explodies as $entry_number) {
			$entries[] = "<a href=\"".$base_url."index.php?section=admin&amp;go=entries&amp;bid=".$row_brewer['uid']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View ".$row_brewer['brewBrewerFirstName']." ".$row_brewer['brewBrewerLastName']."&rsquo;s entries.\">".$entry_number."</a>"; 
		}
		
		$brewer_entries = implode(",",$entries);
		
		$output_datatables_body .= "<td>".str_replace(",",", ",$brewer_entries)."</td>";
		if ($action != "print") $output_datatables_body .= "<td>".$output_datatables_actions."</td>";
		$output_datatables_body .= "</tr>";		
		
	}
	
	else {		
		
		$table_assign_judge = table_assignments($user_info[0],"J",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],1);
		$table_assign_judge = rtrim($table_assign_judge,",&nbsp;");
		$table_assign_steward = table_assignments($user_info[0],"S",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],1);
		$table_assign_steward = rtrim($table_assign_steward,",&nbsp;");
		
		$judge_entries = judge_entries($row_brewer['uid'],1);
		
		if ($filter == "judges") $locations = $row_brewer['brewerJudgeLocation'];
		if ($filter == "stewards") $locations = $row_brewer['brewerStewardLocation'];
		
		if ((!empty($brewer_assignment) && (!$archive_display))) {
			// Build assignment modal for participants
			unset($assignment_modal_body);
			if ((strpos($brewer_assignment,"Judge") !== false) || (strpos($brewer_assignment,"Steward") !== false) ) {
							
				if (strpos($brewer_assignment,"Judge") !== false) {
					if (!empty($table_assign_judge)) $assignment_modal_body = "<p>".$row_brewer['brewerFirstName']." is assigned as a <strong>judge</strong> to table(s): ".$table_assign_judge."<p>";
					else $assignment_modal_body = "<p>".$row_brewer['brewerFirstName']." has been added to the <strong>judge</strong> pool, but has not been assigned to a table yet.<p>";
				}
				if (strpos($brewer_assignment,"Steward") !== false) {
					if (!empty($table_assign_steward))  $assignment_modal_body = "<p>".$row_brewer['brewerFirstName']." is assigned as a <strong>steward</strong> to table(s): ".$table_assign_steward."<p>";
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
		
		
		if (!$archive_display) {
			// Build secret user question modals
			unset($user_question_modal_body);
			
			$user_question_modal_body = "";
			
			$query_question = sprintf("SELECT userQuestion,userQuestionAnswer FROM %s WHERE id = '%s'", $users_db_table, $row_brewer['uid']); 
			$question = mysqli_query($connection,$query_question) or die (mysqli_error($connection));
			$row_question = mysqli_fetch_assoc($question);
			
			$user_question_modal_body .= "<p>";
			$user_question_modal_body .= "<strong>Question:</strong><br>";
			if (isset($row_question['userQuestion'])) $user_question_modal_body .= "<em>".$row_question['userQuestion']."</em>";
			else $user_question_modal_body .= "<em>No question is present.</em>";
			$user_question_modal_body .= "</p>";
			
			$user_question_modal_body .= "<p>";
			$user_question_modal_body .= "<strong>Answer:</strong><br>";
			if (isset($row_question['userQuestionAnswer'])) $user_question_modal_body .= "<em>".$row_question['userQuestionAnswer']."</em>";
			else $user_question_modal_body .= "<em>No answer is present.</em>";
			$user_question_modal_body .= "</p>";
			
			$output_user_question_modals .= "<div class=\"modal fade\" id=\"user-question-modal-".$row_brewer['uid']."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"user-question-modal-label-".$row_brewer['uid']."\">\n";
			$output_user_question_modals .= "\t<div class=\"modal-dialog modal-lg\" role=\"document\">\n";
			$output_user_question_modals .= "\t\t<div class=\"modal-content\">\n";
			$output_user_question_modals .= "\t\t\t<div class=\"modal-header bcoem-admin-modal\">\n";
			$output_user_question_modals .= "\t\t\t\t<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>\n";
			$output_user_question_modals .= "\t\t\t\t<h4 class=\"modal-title\" id=\"user-question-modal-label-".$row_brewer['uid']."\">Secret Question and Answer for ".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']."</h4>\n";
			$output_user_question_modals .= "\t\t\t</div><!-- ./modal-header -->\n";
			$output_user_question_modals .= "\t\t\t<div class=\"modal-body\">\n";
			$output_user_question_modals .= "\t\t\t\t".$user_question_modal_body."\n";
			$output_user_question_modals .= "\t\t\t</div><!-- ./modal-body -->\n";
			$output_user_question_modals .= "\t\t\t<div class=\"modal-footer\">\n";
			$output_user_question_modals .= "\t\t\t\t<button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">Close</button>\n";
			$output_user_question_modals .= "\t\t\t</div><!-- ./modal-footer -->\n";
			$output_user_question_modals .= "\t\t</div><!-- ./modal-content -->\n";
			$output_user_question_modals .= "\t</div><!-- ./modal-dialog -->\n";
			$output_user_question_modals .= "</div><!-- ./modal -->\n";
		}
		
		$output_datatables_body .= "<tr>";
		
		$output_datatables_body .= "<td><a name='".$row_brewer['uid']."'></a>".$row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName'];
		if (($dbTable == "default") && ($user_info[1] == 0)) $output_datatables_body .= " <span class=\"fa fa-lg fa-lock text-danger\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']." is a Top-Level Admin User\"></span>";
		elseif (($dbTable == "default") && ($user_info[1] == 1)) $output_datatables_body .= " <span class=\"fa fa-lg fa-lock text-warning\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']." is an Admin Level User\"></span>";
		else $output_datatables_body .= "";
		$output_datatables_body .= "</td>";
		
		if ($action == "print") {
			$output_datatables_body .= "<td nowrap>";
			$output_datatables_body .= "<small>".$row_brewer['brewerEmail']."</small><br>";
			if ($row_brewer['brewerPhone1'] != "") {
				if ($us_phone) $output_datatables_body .= format_phone_us($row_brewer['brewerPhone1'])." (1)<br>"; 
				else $output_datatables_body .= $row_brewer['brewerPhone1']." (1)<br>"; 
			}
			if ($row_brewer['brewerPhone2'] != "") {
				if ($us_phone) $output_datatables_body .= format_phone_us($row_brewer['brewerPhone2'])." (2)<br>"; 
				else $output_datatables_body .= $row_brewer['brewerPhone2']." (2)<br>"; 
			}
			$output_datatables_body .= "</td>";
		}
		
		$output_datatables_body .= "<td class=\"".$output_hide_print."\">";
		if (($totalRows_judging > 0) && (($filter == "judges") || ($filter == "stewards"))) {
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
			$output_datatables_body .= $output; 
		}	
		else { 
			if ($pro_edition == 1) $output_datatables_body .= $row_brewer['brewerBreweryName'];
			else $output_datatables_body .= $row_brewer['brewerClubs'];
		}
		$output_datatables_body .= "</td>";
		
		if ($filter == "default") {
			$output_datatables_body .= "<td class=\"".$output_hide_print."\">";
			if ($row_brewer['brewerSteward'] == "Y") { 
					if ($action == "print") $output_datatables_body .= "Y"; 
					else $output_datatables_body .= "<span class=\"fa fa-lg fa-check text-success\"></span>"; 
				} 
				if ($row_brewer['brewerSteward'] == "N") {  
					if ($action == "print") $output_datatables_body .= "N"; 
					else $output_datatables_body .= "<span class=\"fa fa-lg fa-times text-danger\"></span>";
				}
			$output_datatables_body .= "</td>";
			$output_datatables_body .= "<td class=\"".$output_hide_print."\">";
			if ($row_brewer['brewerJudge'] == "Y") { 
					if ($action == "print") $output_datatables_body .= "Y"; 
					else $output_datatables_body .= "<span class=\"fa fa-lg fa-check text-success\"></span>"; 
				} 
				if ($row_brewer['brewerJudge'] == "N") {  
					if ($action == "print") $output_datatables_body .= "N"; 
					else $output_datatables_body .= "<span class=\"fa fa-lg fa-times text-danger\"></span>"; 
				}
			$output_datatables_body .= "</td>";
			
		}
		
		$output_datatables_body .= "<td>".ucwords($brewer_assignment)."</td>";
		
		if ($filter != "default") { 
			if ($filter == "judges") {
				$bjcp_rank = explode(",",$row_brewer['brewerJudgeRank']);
				$display_rank = bjcp_rank($bjcp_rank[0],1);
				
				$output_datatables_body .= "<td class=\"".$output_hide_print."\">".$row_brewer['brewerJudgeID']."</td>";
				$output_datatables_body .= "<td>".$display_rank; 
				if ($row_brewer['brewerJudgeMead'] == "Y") $output_datatables_body .= "<br />Certified Mead Judge";
				if (!empty($bjcp_rank[1])) {
					$output_datatables_body .= designations($row_brewer['brewerJudgeRank'],$bjcp_rank[0]);
				}
				$output_datatables_body .= "</td>";
				
			}
			if ($filter == "judges") $output_datatables_body .= "<td>".rtrim($table_assign_judge,",&nbsp;")."</td>";
			if ($filter == "stewards") $output_datatables_body .= "<td>".rtrim($table_assign_steward,",&nbsp;")."</td>";
			$output_datatables_body .= "<td class=\"".$output_hide_print."\">".judge_entries($row_brewer['uid'],1)."</td>";
			
		}
		
		
		$output_datatables_body .= "<td class=\"".$output_hide_print."\">".date_created($row_brewer['uid'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],$_SESSION['prefsTimeZone'],$dbTable)."</td>";
		
		if (($action != "print") && ($dbTable == "default")) { 
		
			$output_datatables_body .= "<td>".$output_datatables_actions."</td>";
			
		}
		
	$output_datatables_body .= "</tr>";

	}
	
} while ($row_brewer = mysqli_fetch_assoc($brewer));

if ($dbTable != "default") $subtitle .= "<p>".$edition."</p>";

// ----------------------------------------- Presentation ------------------------------------------

// Display Top Of Page Elements (Subtitle, Primary Page Info, Nav, and Secondary Page Info)
echo $output_assignment_modals;
echo $output_user_question_modals;
?>

<?php if ($action == "print") { ?>
<h1><?php echo $_SESSION['contestName']." ".$subtitle; ?></h1>
<?php } else { ?>
<p class="lead"><?php echo $_SESSION['contestName']." ".$subtitle; ?></p>
<?php } ?>
<?php if ($action !="print") { ?>
<div class="bcoem-admin-element hidden-print">
	<div class="row">
    	<div class="col-lg-10 col-md-8 col-sm-12 col-xs-12">
	<?php if ($dbTable != "default") { ?>
    <!-- Archives Button -->
	<div class="btn-group" role="group" aria-label="...">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=archive"><span class="fa fa-arrow-circle-left"></span> Archives</a>
    </div><!-- ./button group -->
	<?php } ?>
    <?php if ($dbTable == "default") { ?>
    <?php if ($filter != "default") { ?>
    <!-- All Participants Button -->
	<div class="btn-group" role="group" aria-label="...">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants"><span class="fa fa-arrow-circle-left"></span> All Participants</a>
    </div><!-- ./button group -->
	<?php } ?>
    
    <!-- View Participants Dropdown -->
    <div class="btn-group" role="group">
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<span class="fa fa-eye"></span> View...   
		<span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
        	<?php if ($filter != "default") { ?>
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants">All Participants</a></li>
            <?php } ?>
            <?php if (($filter != "judges") || (($filter == "judges") && ($id != "default"))) { ?>
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants&amp;filter=judges">Available Judges</a><li>
            <?php } ?>
            <?php if (($filter != "stewards") || (($filter == "stewards") && ($id != "default"))) { ?>
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants&amp;filter=stewards">Available Stewards</a><li>
            <?php } ?>
            <?php if (($filter != "with_entries") || (($filter == "stewards") && ($id != "default"))) { ?>
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants&amp;filter=with_entries">Participants with Entries</a><li>
            <?php } ?>
		</ul>
	</div><!-- ./button group -->
    <!-- Add Participants Dropdown -->
    <div class="btn-group" role="group">
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<span class="fa fa-plus-circle"></span> Register...  
		<span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entrant&amp;action=register">A Participant</a></li>
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judge&amp;action=register">A Judge or Steward (Standard)</a><li>
            <li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judge&amp;action=register&amp;view=quick">A Judge or Steward (Quick)</a><li>
		</ul>
	</div>
    <?php if (($action != "add") && ($dbTable == "default")) { ?>
    <!-- Assign/Unassign Judges/Stewards Dropdown -->
	<div class="btn-group" role="group">
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<span class="fa fa-check-circle"></span> Assign/Unassign...  
		<span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">Judges</a></li>
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=bos">BOS Judges</a><li>
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">Stewards</a><li>
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=staff">Staff</a><li>
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;action=assign&amp;go=judging_tables">Judges/Stewards to Tables</a><li>
		</ul>
	</div><!-- ./button group -->
    <?php } ?>
    <!-- Print This List Dropdown -->
	<div class="btn-group hidden-xs hidden-sm hidden-md" role="group">
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<span class="fa fa-print"></span> Print Current View...   
		<span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
        	<?php if ($filter == "default") { ?>
			<li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=admin&amp;go=participants&amp;action=print&amp;view=default&amp;psort=brewer_name">By Last Name</a></li>
			<li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=admin&amp;go=participants&amp;action=print&amp;view=default&amp;psort=club">By Club</a><li>
            <?php } ?>
            <?php if ($filter == "with_entries") { ?>
            <li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=admin&amp;go=participants&amp;action=print&amp;view=default&amp;filter=with_entries">By Entrant Last Name</a><li>
            <?php } ?>
            <?php if ($filter == "judges") { ?>
			<li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=admin&amp;go=participants&amp;filter=judges&amp;action=print&amp;view=default&amp;psort=judge_id">By Judge ID</a><li>
            <li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=admin&amp;go=participants&amp;filter=judges&amp;action=print&amp;view=default&amp;psort=judge_rank">By Judge Rank</a><li>
            <?php } ?>
		</ul>
	</div><!-- ./button group -->
    <?php if ($dbTable != "default") { ?>
    <!-- Print Participants Dropdown -->
    <div class="btn-group" role="group">
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<span class="fa fa-print"></span> Print All...   
		<span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=admin&amp;go=participants&amp;action=print&amp;view=default&amp;dbTable=<?php echo $dbTable; ?>&amp;psort=brewer_name">By Last Name</a></li>
			<li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=admin&amp;go=participants&amp;action=print&amp;view=default&amp;dbTable=<?php echo $dbTable; ?>&amp;psort=club">By Club</a><li>
            <?php if ($filter == "judges") { ?>
			<li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=admin&amp;go=participants&amp;filter=judges&amp;action=print&amp;view=default&amp;dbTable=<?php echo $dbTable; ?>&amp;psort=judge_id">By Judge ID</a><li>
            <li class="small"><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.output.php?section=admin&amp;go=participant&amp;filter=judgess&amp;action=print&amp;view=default&amp;dbTable=<?php echo $dbTable; ?>&amp;psort=judge_rank">By Judge Rank</a><li>
            <?php } ?>
		</ul>
	</div><!-- ./button group -->
    <?php } ?>
    </div><!-- ./ col -->
    <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
        <!-- Participant status modal -->
        <div class="btn-group pull-right hidden-xs" role="group" aria-label="...">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#participantStatusModal">
                 Participant Status
                </button>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="participantStatusModal" tabindex="-1" role="dialog" aria-labelledby="participantStatusModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header bcoem-admin-modal">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="participantStatusModalLabel">Participant Status</h4>
                    </div>
                    <div class="modal-body">
                        <div class="bcoem-sidebar-panel">
                            <strong class="text-info">Participants</strong><span class="pull-right"><?php echo get_participant_count('default'); ?></span>
                        </div>
                        <div class="bcoem-sidebar-panel">
                            <strong class="text-info">Participants with Entries</strong><span class="pull-right"><?php echo $row_with_entries['count']; ?></span>
                        </div>
                        <div class="bcoem-sidebar-panel">
                            <strong class="text-info">Available Judges</strong><span class="pull-right"><?php echo get_participant_count('judge'); ?></span>
                        </div>
                        <div class="bcoem-sidebar-panel">
                            <strong class="text-info">Available Stewards</strong><span class="pull-right"><?php echo get_participant_count('steward'); ?></span>
                        </div>
                    </div>
                    <div class="modal-footer">
            			<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            		</div>
                </div>
            </div>
        </div><!-- ./modal -->
        <?php } ?>
	</div><!-- ./col -->
</div><!-- ./row -->
</div><!-- ./bcoem-admin-element -->
<?php } ?>

<?php
if (($action == "default") || ($action == "print")) { 
if ($totalRows_brewer > 0) { ?>
	<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : <?php echo $output_datatables_bPaginate; ?>,
			"sPaginationType" : "<?php echo $output_datatables_sPaginationType; ?>",
			"bLengthChange" : <?php echo $output_datatables_bLengthChange; ?>,
			"iDisplayLength" : <?php if ($action == "print") echo "99999"; else echo round($_SESSION['prefsRecordPaging']); ?>,
			"sDom": '<?php echo $output_datatables_sDom; ?>',
			"bStateSave" : <?php echo $output_datatables_bStateSave; ?>,
			"aaSorting": [<?php echo $output_datatables_aaSorting; ?>],
			"bProcessing" : <?php echo $output_datatables_bProcessing; ?>,
			"aoColumns": [ <?php echo $output_datatables_aoColumns; ?> ]
			} );
		} );
</script>
<table class="table table-responsive table-bordered table-striped dataTable" id="sortable">
<thead>
<?php echo $output_datatables_head; ?>
</thead>
<tbody>
<?php  echo $output_datatables_body; ?>
</tbody>
</table>
<?php } if ($totalRows_brewer == 0) { ?>

<?php 
if ($filter == "default") 	echo "<div class='error'>There are no participants yet.</div>"; 
if ($filter == "judges") 	echo "<div class='error'>There are no judges available yet.</div>"; 
if ($filter == "stewards")  echo "<div class='error'>There are no stewards available yet.</div>"; 
}
} // end if ($action == "default")

if ($action == "add")  { 
	if ($filter == "default") { ?>
<script type="text/javascript">
function checkAvailability()
{
	jQuery.ajax({
		url: "<?php echo $base_url; ?>includes/ajax_functions.inc.php?action=username",
		data:'user_name='+$("#user_name").val(),
		type: "POST",
		success:function(data){
			$("#status").html(data);
		},
		error:function (){}
	});
}

function AjaxFunction(email)
{
	var httpxml;
		try
		{
		// Firefox, Opera 8.0+, Safari
		httpxml=new XMLHttpRequest();
		}
	catch (e)
		{
		// Internet Explorer
		try
		{
		httpxml=new ActiveXObject("Msxml2.XMLHTTP");
		}
	catch (e)
		{
		try
		{
		httpxml=new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch (e)
		{
		//alert("Your browser does not support AJAX!");
	return false;
	}
	}
}
function stateck()
{
if(httpxml.readyState==4)
{
document.getElementById("msg_email").innerHTML=httpxml.responseText;
}
}
var url="<?php echo $base_url; ?>includes/ajax_functions.inc.php?action=email";
url=url+"&email="+email;
url=url+"&sid="+Math.random();
httpxml.onreadystatechange=stateck;
httpxml.open("GET",url,true);
httpxml.send(null);
}
//-->
</script>
<form action="<?php echo $base_url; ?>includes/process.inc.php?action=add&amp;dbTable=<?php echo $users_db_table; ?>&amp;section=<?php echo $section; ?>&amp;go=<?php echo $go; ?>" method="POST" name="form1" id="form1" onSubmit="return CheckRequiredFields()">
<table>
	<tr>
    	<td class="dataLabel">Email Address:</td>
    	<td class="data"><input name="user_name" id="user_name" type="text" class="submit" size="40" onBlur="checkAvailability()" onkeyup="twitter.updateUrl(this.value)" onchange="AjaxFunction(this.value);" value="<?php if ($msg == "4") echo $_SESSION['user_name']; ?>"><div id="msg_email">Email Format:</div><div id="status"></div></td>
        <td class="data" id="inf_email"><span class="required">Required</span></td>
  	</tr>
  	<tr>
    	<td class="dataLabel">Password:</td>
    	<td class="data"><input name="password" id="password" type="password" class="submit" size="25"></td>
        <td class="data"><span class="required">Required</span></td>
  	</tr>

  	<tr>
    	<td class="dataLabel">&nbsp;</td>
    	<td class="data"><input type="submit" class="button" value="Register"></td> 
        <td class="data">&nbsp;</td>
  	</tr>
</table>
<input type="hidden" name="userLevel" value="2" />
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin&go=participants","default",$msg,$id); ?>">
<?php } ?>
</form>
</div>
<?php } 
if ($filter == "info") { 
if (($action == "add") || (($action == "edit") && (($_SESSION['loginUsername'] == $row_brewer['brewerEmail'])) || ($_SESSION['userLevel'] <= "1")))  { 
?>
<form action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo "admin&amp;go=".$go."&amp;filter=".$filter; ?>&amp;action=<?php echo $action; ?>&amp;dbTable=<?php echo $brewer_db_table; ?>" method="POST" name="form1" id="form1" onSubmit="return CheckRequiredFields()">
<table class="dataTable">
<tr>
      <td class="dataLabel" width="5%">Email:</td>
      <td class="data" width="20%"><?php echo $username; ?></td>
      <td class="data">&nbsp;</td>
</tr>
<tr>
      <td class="dataLabel" width="5%">First Name:</td>
      <td class="data" width="20%"><input type="text" id="brewerFirstName" name="brewerFirstName" value="<?php if ($action == "edit") echo $row_brewer['brewerFirstName']; ?>" size="32" maxlength="20"></td>
      <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
      <td class="dataLabel">Last Name:</td>
      <td class="data"><input type="text" name="brewerLastName" value="<?php if ($action == "edit") echo $row_brewer['brewerLastName']; ?>" size="32"></td>
      <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
      <td class="dataLabel">Street Address:</td>
      <td class="data"><input type="text" name="brewerAddress" value="<?php if ($action == "edit") echo $row_brewer['brewerAddress']; ?>" size="32"></td>
      <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
      <td class="dataLabel">City:</td>
      <td class="data"><input type="text" name="brewerCity" value="<?php if ($action == "edit") echo $row_brewer['brewerCity']; ?>" size="32"></td>
      <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
      <td class="dataLabel">State/Country:</td>
      <td class="data"><input type="text" name="brewerState" value="<?php if ($action == "edit") echo $row_brewer['brewerState']; ?>" size="32"></td>
      <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
      <td class="dataLabel">Zip/Postal Code:</td>
      <td class="data"><input type="text" name="brewerZip" value="<?php if ($action == "edit") echo $row_brewer['brewerZip']; ?>" size="32"></td>
      <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
      <td class="dataLabel">Phone 1:</td>
      <td class="data"><input type="text" name="brewerPhone1" value="<?php if ($action == "edit") echo $row_brewer['brewerPhone1']; ?>" size="32"></td>
      <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
      <td class="dataLabel">Phone 2:</td>
      <td class="data"><input type="text" name="brewerPhone2" value="<?php if ($action == "edit") echo $row_brewer['brewerPhone2']; ?>" size="32"></td>
      <td class="data">&nbsp;</td>
</tr>
<tr>
      <td class="dataLabel">Club Name (if appropriate):</td>
      <td class="data"><input type="text" name="brewerClubs" value="<?php if ($action == "edit") echo $row_brewer['brewerClubs']; ?>" size="32" maxlength="200"></td>
      <td class="data">&nbsp;</td>
</tr>
<tr>
	  <td>&nbsp;</td>
      <td colspan="2" class="data"><input name="submit" type="submit" class="button" value="Submit Brewer Information" /></td>
    </tr>
</table>
<input name="brewerEmail" type="hidden" value="<?php echo $username; ?>" />
<input name="uid" type="hidden" value="<?php echo $row_user_level['id']; ?>" />
<input name="brewerJudge" type="hidden" value="N" />
<input name="brewerSteward" type="hidden" value="N" />
</form>
<?php 
	  }  
   }
} 
?>