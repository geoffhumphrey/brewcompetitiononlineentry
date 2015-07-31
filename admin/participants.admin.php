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

include(DB.'admin_participants.db.php');

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

if ($action != "print") { 
	if (($dbTable == "default") && ($row_participant_count['count'] > $_SESSION['prefsRecordLimit']))	{ 
			echo "<div class='info'>The DataTables recordset paging limit of ".$_SESSION['prefsRecordLimit']." has been surpassed. Filtering and sorting capabilites are only available for this set of ".$_SESSION['prefsRecordPaging']." participants.<br />To adjust this setting, <a href='index.php?section=admin&amp;go=preferences'>change your installation's DataTables Record Threshold</a> (under the &ldquo;Performance&rdquo; heading in preferences) to a number <em>greater</em> than the total number of participants (".$row_participant_count['count'].").</div>";
	}
}



// *****************************************************************************
// ---------------------- Top of Page Vars -------------------------------------
// *****************************************************************************

// Build Subtitle
$subtitle .= "<h2>";
if ($filter == "judges") { 
	$subtitle .= "Available Judges"; 
	$csv = "avail_judges"; 
	}
elseif ($filter == "stewards") { 
	$subtitle .= "Available Stewards";
	$csv = "avail_stewards"; 
	}
elseif ($action == "add") $subtitle .= "Add Participant"; 
else $subtitle .= "Participants"; 
if ($dbTable != "default") $subtitle .= ": ".get_suffix($dbTable); 
$subtitle .= "</h2>";


if ($action != "print") {
	
	$goto_nav .= "<div class='adminSubNavContainer'>";
	// Back to Dashboard
	$goto_nav .= "<span class='adminSubNav'>";
	$goto_nav .= "<span class='icon'><img src='".$base_url."images/arrow_left.png' alt='Back' title='Back'></span><a href='".$base_url."index.php?section=admin'>Back to Admin Dashboard</a>";
	$goto_nav .= "</span>";
	
		
	if ($filter != "default") {
		$goto_nav .= "<span class='adminSubNav'>";
		$goto_nav .= "<span class='icon'><img src='".$base_url."images/arrow_left.png' alt='Back' title='Back'></span><a href='".$base_url."index.php?section=admin&amp;go=participants'>Back to Participants</a>";
		$goto_nav .= "</span>";	
	}
		
	if ($dbTable == "default") {
		
		$goto_nav .= "<span class='adminSubNav'>";
		$goto_nav .= "<span class='icon'><img src='".$base_url."images/user_add.png' alt='Add a Participant' title='Add a Participant'></span><a href='".$base_url."index.php?section=admin&amp;go=entrant&amp;action=register'>Add a Participant</a>";
		$goto_nav .= "</span>";
		
		$goto_nav .= "<span class='adminSubNav'>";
		$goto_nav .= "<span class='icon'><img src='".$base_url."images/user_add.png' alt='Add a Judge/Steward<' title='Add a Judge/Steward<'></span><a href='".$base_url."index.php?section=admin&amp;go=judge&amp;action=register'>Add a Judge/Steward</a>";
		$goto_nav .= "</span>";
		
		$goto_nav .= "<span class='adminSubNav'>";
		$goto_nav .= "<span class='icon'><img src='".$base_url."images/exclamation.png' alt='Clear All Assignments' title='Clear All Assignments'></span><a onclick=\"return confirm('Are you sure you want to clear all judge/steward/staff and organizer assignments for all participants? This will also clear any judge/steward table assignments and cannot be undone.');\"  href='".$base_url."includes/process.inc.php?dbTable=".$prefix."brewer&amp;action=update&amp;filter=clear'>Clear All Assignments</a>";
		$goto_nav .= "</span>";
	
	} // end if ($dbTable == "default")
	
	else { 
		$goto_nav .= "<span class='adminSubNav'>";
		$goto_nav .= "<span class='icon'><img src='".$base_url."images/arrow_left.png' alt='Back' title='Back'></span><a href='".$base_url."index.php?section=admin&amp;go=archive'>Back to Archives</a>";
		$goto_nav .= "</span>";
		
	} // end if ($dbTable != "default")
	
	$goto_nav .= "</div>";

	if ($dbTable == "default") {
		
		$secondary_nav .= "<div class='adminSubNavContainer'>";
		$secondary_nav .= "<span class='adminSubNav'>";
		$secondary_nav .= "<span class='icon'><img src='".$base_url."images/page_excel_go.png' alt='Export' title='Export'></span>";
		if (($filter == "judges") || ($filter == "stewards")) $secondary_nav .= "<a href='".$base_url."output/email_export.php?section=admin&amp;go=csv&amp;filter=".$csv."&amp;action=email'>Export</a>";
		else $secondary_nav .= "<a href='".$base_url."output/participants_export.php?section=admin&amp;go=csv'>Export</a>";
		$secondary_nav .= "</span>";
		$secondary_nav .= "<span class='adminSubNav'>";
		$secondary_nav .= "<span class='icon'><img src='".$base_url."images/page.png' alt='View' title='View'></span>";
		$secondary_nav .= "<div class='menuBar'><a class='menuButton' href='#' onclick='#' onmouseover='buttonMouseover(event, \"views\");'>View...</a></div>";
		$secondary_nav .= "<div id='views' class='menu' onmouseover='menuMouseover(event)'>";
		$secondary_nav .= "<a class='menuItem' href='".$base_url."index.php?section=admin&amp;go=participants'>All Participants</a>";
  		$secondary_nav .= "<a class='menuItem' href='".$base_url."index.php?section=admin&amp;go=participants&amp;filter=judges'>Available Judges</a>";
  		$secondary_nav .= "<a class='menuItem' href='".$base_url."index.php?section=admin&amp;go=participants&amp;filter=stewards'>Available Stewards</a>";
  		$secondary_nav .= "</div>";
		$secondary_nav .= "</span>";
		$secondary_nav .= "<span class='adminSubNav'>";
		$secondary_nav .= "<span class='icon'><img src='".$base_url."images/printer.png' alt='Print This List' title='Print This List'></span>";
		$secondary_nav .= "<div class='menuBar'><a class='menuButton' href='#' onclick='#' onmouseover='buttonMouseover(event, \"printMenu_participants\");'>Print This List</a></div>";
		$secondary_nav .= "<div id='printMenu_participants' class='menu' onmouseover='menuMouseover(event)'>";
		$secondary_nav .= "<a id='modal_window_link' class='menuItem' href='".$base_url."output/print.php?".$_SERVER['QUERY_STRING']."&amp;action=print&amp;view=default&amp;psort=brewer_name'>By Last Name</a>";
		if (($filter == "judges") || ($filter == "stewards")) $secondary_nav .= "<a id='modal_window_link' class='menuItem' href='".$base_url."output/print.php?".$_SERVER['QUERY_STRING']."&amp;action=print&amp;view=default&amp;psort=club'>By Club</a>";
		if ($filter == "judges")$secondary_nav .= "<a id='modal_window_link' class='menuItem' href='".$base_url."output/print.php?".$_SERVER['QUERY_STRING']."&amp;action=print&amp;view=default&amp;psort=judge_id'>By Judge ID</a>";
		$secondary_nav .= "<a id='modal_window_link' class='menuItem' href='".$base_url."output/print.php?".$_SERVER['QUERY_STRING']."&amp;action=print&amp;view=default&amp;psort=psort=judge_rank'>By Judge Rank</a>";
		$secondary_nav .= "</span>";
		$secondary_nav .= "</div>";
		$secondary_nav .= "</div>";
		
		if (($row_participant_count['count'] > $_SESSION['prefsRecordLimit']) && ($filter == "default")) { 
			$secondary_nav .= "<div class='adminSubNavContainer'>";
			$secondary_nav .= "<span class='adminSubNav'>";
			$secondary_nav .= "<span class='icon'><img src='".$base_url."images/printer.png' alt='Print All' title='Print All'></span>";
			$secondary_nav .= "<div class='menuBar'><a class='menuButton' href='#' onclick='#' onmouseover='buttonMouseover(event, \"printMenu_participants_all\");'>Print All</a></div>";
			$secondary_nav .= "<div id='printMenu_participants_all' class='menu' onmouseover='menuMouseover(event)'>";
			$secondary_nav .= "<a id='modal_window_link' class='menuItem' href='".$base_url."'output/print.php?".$_SERVER['QUERY_STRING']."&amp;action=print&amp;view=all&amp;psort=brewer_name'>By Last Name</a>";
			$secondary_nav .= "<a id='modal_window_link' class='menuItem' href='".$base_url."'output/print.php?".$_SERVER['QUERY_STRING']."&amp;action=print&amp;view=all&amp;psort=club>By Club</a>";
			$secondary_nav .= "</div>";
			$secondary_nav .= "</span>";
			$secondary_nav .= "</div>";
		} // end if (($row_participant_count['count'] > $_SESSION['prefsRecordLimit']) && ($filter == "default"))
		
	} // end if ($dbTable == "default")
	
	if ($dbTable != "default") {
		
		$secondary_nav .= "<div class='adminSubNavContainer'>";
		$secondary_nav .= "<span class='adminSubNav'>";
		$secondary_nav .= "<span class='icon'><img src='".$base_url."images/printer.png' alt='Print All' title='Print All'></span>";
		$secondary_nav .= "<div class='menuBar'><a class='menuButton' href='#' onclick='#' onmouseover='buttonMouseover(event, \"printMenu_participants_all\");'>Print All</a></div>";
		$secondary_nav .= "<div id='printMenu_participants_all' class='menu' onmouseover='menuMouseover(event)'>";
		$secondary_nav .= "<a id='modal_window_link' class='menuItem' href='".$base_url."'output/print.php?".$_SERVER['QUERY_STRING']."&amp;action=print&amp;view=all&amp;psort=brewer_name'>By Last Name</a>";
		$secondary_nav .= "<a id='modal_window_link' class='menuItem' href='".$base_url."'output/print.php?".$_SERVER['QUERY_STRING']."&amp;action=print&amp;view=all&amp;psort=club>By Club</a>";
		if ($filter == "judges") {
		$secondary_nav .= "<a id='modal_window_link' class='menuItem' href='".$base_url."'output/print.php?".$_SERVER['QUERY_STRING']."&amp;action=print&amp;view=all&amp;psort=judge_id'>By Judge ID</a>";
		$secondary_nav .= "<a id='modal_window_link' class='menuItem' href='".$base_url."'output/print.php?".$_SERVER['QUERY_STRING']."&amp;action=print&amp;view=all&amp;psort=judge_rank>By Judge Rank</a>";
		}
		$secondary_nav .= "</div>";
		$secondary_nav .= "</span>";
		$secondary_nav .= "</div>";
		
	} // end if ($dbTable != "default")
	
	
	if (($action != "add") && ($dbTable == "default")) { 
		$secondary_nav .= "<div class='adminSubNavContainer'>";
 		$secondary_nav .= "<span class='adminSubNav'>";
		$secondary_nav .= "<span class='icon'><img src='".$base_url."images/user_edit.png' alt='Assign' title='Assign'></span>";
		$secondary_nav .= "<a href='". $base_url."index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges'>Assign Judges</a>";
 		$secondary_nav .= "</span>";
		$secondary_nav .= "<span class='adminSubNav'>";
		$secondary_nav .= "<span class='icon'><img src='".$base_url."images/user_edit.png' alt='' title=''></span><a href='".$base_url."index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=bos'>Assign BOS Judges</a>";
		$secondary_nav .= "</span>";
    	$secondary_nav .= "<span class='adminSubNav'>";
		$secondary_nav .= "<span class='icon'><img src='".$base_url."images/user_edit.png' alt='Assign' title='Assign'></span>";
		$secondary_nav .= "<a href='". $base_url."index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards'>Assign Stewards</a>";
 		$secondary_nav .= "</span>";
		$secondary_nav .= "<span class='adminSubNav'>";
		$secondary_nav .= "<span class='icon'><img src='".$base_url."images/user_edit.png' alt='Assign' title='Assign'></span>";
		$secondary_nav .= "<a href='". $base_url."index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=staff'>Assign Staff</a>";
 		$secondary_nav .= "</span>";
		if ($totalRows_tables > 1) {
            $secondary_nav .= "<span class='adminSubNav'>";
			$secondary_nav .= "<span class='icon'><img src='".$base_url."images/user_edit.png' alt='Assign' title='Assign'></span>";
			$secondary_nav .= "<a href='". $base_url."index.php?section=admin&amp;action=assign&amp;go=judging_tables'>Assign Judges/Stewards to Tables</a>";
		}
		$secondary_nav .= "</div>";
	
	} // end if (($action != "add") && ($dbTable == "default")) 
	
} // end if ($action != "print")



// *****************************************************************************
// ---------------------- List Participants ---------------------------
// *****************************************************************************

$output_datatables_aaSorting = "";
$output_datatables_aoColumns = "";

if ($action == "print") {
	
	$output_datatables_aaSorting .= "";
	if ($psort == "judge_rank") $output_datatables_aaSorting .= "[6,'desc']";
	if ($psort == "judge_id")  $output_datatables_aaSorting .= "[5,'asc']";
	if ($psort == "club") $output_datatables_aaSorting .= "[3,'asc']";
	if ($psort == "default") $output_datatables_aaSorting .= "[0,'asc']";
	
	
	
	if ($filter == "default") 	{ 
		$output_datatables_aoColumns .= "null, null, { \"asSorting\": [  ] }, null, null, null, null, null";
	}
	
	if ($filter == "judges") 	{ 
		$output_datatables_aoColumns .= "null, null, { \"asSorting\": [  ] }, null, null, null, null, null, { \"asSorting\": [  ] }";
	}
	
	if ($filter == "stewards") 	{
		$output_datatables_aoColumns .= "null, null, { \"asSorting\": [  ] }, null, null, null, null";
	}
	

} // end if ($action == "print")

else {

	if ($filter == "default") 	{ 
		$output_datatables_aaSorting .= "[0,'asc']";
		$output_datatables_aoColumns .= "null, null, { \"asSorting\": [  ] }, null, { \"asSorting\": [  ] }, { \"asSorting\": [  ] }, null, null";
		if ($dbTable == "default") 	
		$output_datatables_aoColumns .= ",  { \"asSorting\": [  ] }";
	}
	
	if ($filter == "judges") 	{ 
		$output_datatables_aaSorting .= "[0,'asc']";
		$output_datatables_aoColumns .= "null, null, { \"asSorting\": [  ] }, null,	null, null, null, null, null, { \"asSorting\": [  ] }";
	}
	
	if ($filter == "stewards") 	{
		$output_datatables_aaSorting .= "[0,'asc']";
		$output_datatables_aoColumns .= "null, null, { \"asSorting\": [  ] }, null,	null, null, null, { \"asSorting\": [  ] }";
	}

}


$output_datatables_head .= "<tr>";
$output_datatables_head .= "<th width='5%' class='dataHeading bdr1B'>Last</th>";
$output_datatables_head .= "<th width='5%' class='dataHeading bdr1B'>First</th>";
$output_datatables_head .= "<th width='15%' class='dataHeading bdr1B'>Info</th>";
$output_datatables_head .= "<th width='22%' class='dataHeading bdr1B'>";
if (($totalRows_judging > 0) && (($filter == "judges") || ($filter == "stewards"))) $output_datatables_head .= "Location(s) Available"; 
else $output_datatables_head .= "Club";
$output_datatables_head .= "</th>";
if ($filter == "default") { 
	$output_datatables_head .= "<th width='8%' class='dataHeading bdr1B'>Steward?</th>";
	$output_datatables_head .= "<th width='8%' class='dataHeading bdr1B'>Judge?</th>";
}
$output_datatables_head .= "<th width='15%' class='dataHeading bdr1B'>Assigned As</th>";
if ($filter != "default") { 
	if ($filter == "judges") {
		$output_datatables_head .= "<th width='8%' class='dataHeading bdr1B'>ID</th>";
		$output_datatables_head .= "<th width='10%' class='dataHeading bdr1B'>Rank</th>";
	}
	
	$output_datatables_head .= "<th width='15%' class='dataHeading bdr1B'>Has Entries In...</th>";
}
$output_datatables_head .= "<th width='10%' class='dataHeading bdr1B'>Date Created</th>";
if (($action != "print") && ($dbTable == "default")) $output_datatables_head .= "<th class='dataHeading bdr1B'>Actions</th>";
$output_datatables_head .= "</tr>";

$email_subject = "";
if ($filter == "judges") $email_subject .= "Judging at ".$_SESSION['contestName']; 
elseif ($filter == "stewards") $email_subject .= "Stewarding at ".$_SESSION['contestName']; 
else $email_subject .= $_SESSION['contestName'];  

do {
	
	$output_datatables_add_link = "";
	$output_datatables_edit_link = "";
	$output_datatables_delete_link = "";
	$output_datatables_print_link = "";
	$output_datatables_other_link = "";
	$output_datatables_view_link = "";
	$output_datatables_actions = "";
	
	$user_info = user_info($row_brewer['uid']);
	$user_info = explode("^",$user_info);
	
	if ($filter == "judges") $locations = $row_brewer['brewerJudgeLocation'];
	if ($filter == "stewards") $locations = $row_brewer['brewerStewardLocation'];
	
	if ($_SESSION['brewerCountry'] == "United States") $us_phone = TRUE; else $us_phone = FALSE;
	unset($brewer_assignment);
	$brewer_assignment = brewer_assignment($row_brewer['uid'],"1");
	//$judge_array = str_replace(", ",",",$brewer_assignment);
	//$judge_array = explode(",",$judge_array);
	//if (in_array("Judge",$judge_array)) $brewer_judge = TRUE; else $brewer_judge = FALSE;
	if ($action == "print") $output_datatables_body .= "<tr class='bdr1B_gray'>";
	else $output_datatables_body .= "<tr>";
	$output_datatables_body .= "<td class='dataList'>".$row_brewer['brewerLastName']."</td>";
	$output_datatables_body .= "<td class='dataList'>".$row_brewer['brewerFirstName']."</td>";
	
	$output_datatables_body .= "<td class='dataList'>";
	$output_datatables_body .= "<a href='mailto:".$row_brewer['brewerEmail']."?Subject=$email_subject'>".$row_brewer['brewerEmail']."</a><br />";
	if ($row_brewer['brewerPhone1'] != "") {
		if ($us_phone) $output_datatables_body .= format_phone_us($row_brewer['brewerPhone1'])." (1)<br>"; 
		else $output_datatables_body .= $row_brewer['brewerPhone1']." (1)<br>"; 
	}
	if ($row_brewer['brewerPhone2'] != "") {
		if ($us_phone) $output_datatables_body .= format_phone_us($row_brewer['brewerPhone2'])." (2)<br>"; 
		else $output_datatables_body .= $row_brewer['brewerPhone2']." (2)<br>"; 
	}
	$output_datatables_body .= "</td>";
	
	$output_datatables_body .= "<td class='dataList'>";
	if (($totalRows_judging > 0) && (($filter == "judges") || ($filter == "stewards"))) $output_datatables_body .= judge_steward_availability($locations,1); else $output_datatables_body .= $row_brewer['brewerClubs'];
	//else $output_datatables_body .= "&nbsp;";
	$output_datatables_body .= "</td>";
	
	if ($filter == "default") {
		$output_datatables_body .= "<td class='dataList'>";
		if ($row_brewer['brewerSteward'] == "Y") { 
				if ($action == "print") $output_datatables_body .= "Y"; 
				else $output_datatables_body .= "<img src='".$base_url."images/tick.png'>"; 
			} 
			if ($row_brewer['brewerSteward'] == "N") {  
				if ($action == "print") $output_datatables_body .= "N"; 
				else $output_datatables_body .= "<img src='".$base_url."images/cross.png'>"; 
			}
		$output_datatables_body .= "</td>";
		$output_datatables_body .= "<td class='dataList'>";
		if ($row_brewer['brewerJudge'] == "Y") { 
				if ($action == "print") $output_datatables_body .= "Y"; 
				else $output_datatables_body .= "<img src='".$base_url."images/tick.png'>"; 
			} 
			if ($row_brewer['brewerJudge'] == "N") {  
				if ($action == "print") $output_datatables_body .= "N"; 
				else $output_datatables_body .= "<img src='".$base_url."images/cross.png'>"; 
			}
		$output_datatables_body .= "</td>";
		
	}
	
	$output_datatables_body .= "<td class='dataList'>".$brewer_assignment."</td>";
	
	if ($filter != "default") { 
		if ($filter == "judges") {
			$bjcp_rank = explode(",",$row_brewer['brewerJudgeRank']);
			$display_rank = bjcp_rank($bjcp_rank[0],1);
			
			$output_datatables_body .= "<td class='dataList'>".$row_brewer['brewerJudgeID']."</td>";
			$output_datatables_body .= "<td class='dataList'>".$display_rank; 
			if ($row_brewer['brewerJudgeMead'] == "Y") $output_datatables_body .= "<br />Certified Mead Judge";
			if (!empty($bjcp_rank[1])) {
				$output_datatables_body .= designations($row_brewer['brewerJudgeRank'],$bjcp_rank[0]);
			}
			$output_datatables_body .= "</td>";
		}
		$output_datatables_body .= "<td class='dataList'>".judge_entries($row_brewer['uid'],1)."</td>";
	}
	
	
	$output_datatables_body .= "<td class='dataList' nowrap='nowrap'>".date_created($row_brewer['uid'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],$_SESSION['prefsTimeZone'],$dbTable)."</td>";
	
	if (($action != "print") && ($dbTable == "default")) { 
	
	
	// build_action_link($icon,$base_url,$section,$go,$action,$filter,$id,$dbTable,$alt_title) {

		$output_datatables_add_link = build_action_link("book_add",$base_url,"brew","entries","add",$row_brewer['uid'],"default","default","Add an entry for ".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']);
		$output_datatables_edit_link = build_action_link("pencil",$base_url,"brewer","admin","edit",$row_brewer['uid'],$row_brewer['id'],$dbTable,"Edit the user record for ".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']);
		if ($row_brewer['brewerEmail'] != $_SESSION['loginUsername']) $output_datatables_delete_link = build_action_link("bin_closed",$base_url,"admin","participants","delete",$row_brewer['uid'],$row_brewer['uid'],$brewer_db_table,"Are you sure you want to delete the participant ".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']."? ALL entries for this participant WILL BE DELETED as well. This cannot be undone.");
		else $output_datatables_delete_link = "<span class='icon'><img src='".$base_url."images/bin_closed_fade.png' title='You cannot delete yourself!'></span>";
		if ($user_info[1] <= "1") $change_icon = "lock_open"; else $change_icon = "lock_edit";
		$output_datatables_other_link = build_action_link($change_icon,$base_url,"admin","make_admin","default","default",$row_brewer['uid'],"default","Change ".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']."&rsquo;s User Level");
		$output_datatables_view_link = "";
		if (strpos($brewer_assignment,'Judge') !== false)  {
			$output_datatables_view_link = build_output_link("page_white_acrobat",$base_url,"labels.php","admin","participants","judging_labels","default",$row_brewer['id'],"default","Download judging labels for ".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName'],FALSE);
		}
		if ($row_brewer['brewerEmail'] != $_SESSION['loginUsername']) $output_datatables_other_link2 = build_action_link("email_edit",$base_url,"user","default","username","admin",$row_brewer['id'],"default","Change ".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']."&rsquo;s Email Address");
		else $output_datatables_other_link2 = "<span class='icon'><img src='".$base_url."images/page_edit_fade.png' title='Use the Change Your Email Link from the My Info and Entries page to change your email.'></span>";
		$output_datatables_actions = $output_datatables_add_link.$output_datatables_edit_link.$output_datatables_other_link2.$output_datatables_other_link.$output_datatables_delete_link.$output_datatables_view_link;
		
		$output_datatables_body .= "<td class='dataList' nowrap='nowrap'>".$output_datatables_actions."</td>";
	}
	

	
	$output_datatables_body .= "</tr>";
	
} while ($row_brewer = mysql_fetch_assoc($brewer));

// ----------------------------------------- Presentation ------------------------------------------


// Display Top Of Page Elements (Subtitle, Primary Page Info, Nav, and Secondary Page Info)
echo $subtitle;
echo $primary_page_info;
echo $goto_nav;
echo $secondary_nav;
echo $secondary_page_info;

if ($action != "print") { 
	if (($dbTable == "default") && ($row_participant_count['count'] > $_SESSION['prefsRecordLimit']))	{ 
			echo "<div class='info'>The DataTables recordset paging limit of ".$_SESSION['prefsRecordLimit']." has been surpassed. Filtering and sorting capabilites are only available for this set of ".$_SESSION['prefsRecordPaging']." participants.<br />To adjust this setting, <a href='index.php?section=admin&amp;go=preferences'>change your installation's DataTables Record Threshold</a> (under the &ldquo;Performance&rdquo; heading in preferences) to a number <em>greater</em> than the total number of participants (".$row_participant_count['count'].").</div>";
	}
}




if (($action == "default") || ($action == "print")) { 
if ($row_participant_count['count'] > 0) { ?>
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
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/email_check.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/username_check.js" ></script>
<script type="text/javascript">
pic1 = new Image(16, 16); 
pic1.src = "<?php echo $base_url; ?>images/loader.gif";

$(document).ready(function(){

$("#user_name").change(function() { 

var usr = $("#user_name").val();

if(usr.length >= 6)
{
$("#status").html('<span class="icon"><img src="<?php echo $base_url; ?>images/loader.gif" align="absmiddle"><span>Checking availability...');

    $.ajax({  
    type: "POST",  
    url: "includes/username.inc.php",  
    data: "user_name="+ usr,  
    success: function(msg){  
   
   $("#status").ajaxComplete(function(event, request, settings){ 

	if(msg == 'OK')
	{ 
        $("#user_name").removeClass('object_error'); // if necessary
		$("#user_name").addClass("object_ok");
		$(this).html('<span style="color:green;">Email address not in use.</span>');
	}  
	else  
	{  
		$("#user_name").removeClass('object_ok'); // if necessary
		$("#user_name").addClass("object_error");
		$(this).html(msg);
	}  
   
   });

 } 
   
  }); 

}
else
	{
	$("#status").html('<font color="red">The username should have at least <strong>6</strong> characters.</font>');
	$("#user_name").removeClass('object_ok'); // if necessary
	$("#user_name").addClass("object_error");
	}

});

});

//-->
</script>
<form action="<?php echo $base_url; ?>includes/process.inc.php?action=add&amp;dbTable=<?php echo $users_db_table; ?>&amp;section=<?php echo $section; ?>&amp;go=<?php echo $go; ?>" method="POST" name="form1" id="form1" onSubmit="return CheckRequiredFields()">
<table>
	<tr>
    	<td class="dataLabel">Email Address:</td>
    	<td class="data"><input name="user_name" id="user_name" type="text" class="submit" size="40" onkeyup="twitter.updateUrl(this.value)" onchange="AjaxFunction(this.value);" value="<?php if ($msg == "4") echo $_SESSION['user_name']; ?>"><div id="msg_email">Email Format:</div><div id="status"></div></td>
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
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>
</div>
<?php } 
if ($filter == "info") { 
if (($action == "add") || (($action == "edit") && (($_SESSION['loginUsername'] == $row_brewer['brewerEmail'])) || ($_SESSION['userLevel'] <= "1")))  { ?>
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