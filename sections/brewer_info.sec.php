<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable_judge').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[1,'asc']],
			"aoColumns": [
				null,
				null,
				null
				]
			} );
		} );

	// Table Assignments
	 $(document).ready(function() {
		$('#judge_assignments').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']],
			"aoColumns": [
				null,
				null,
				null
				]
			} );
		} );

	
	 $(document).ready(function() {
		$('#sortable_steward').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[1,'asc']],
			"aoColumns": [
				null,
				null,
				null
				]
			} );
		} );
		
	$(document).ready(function() {
		$('#steward_assignments').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']],
			"aoColumns": [
				null,
				null,
				null
				]
			} );
		} );
</script>
<?php 
/**
 * Module:      brewer_info.sec.php 
 * Description: This module displays user-related data including personal information,
 *              judging/stewarding information
 * 
 */


// For Bootsrap conversion use the Horizontal Description class - http://getbootstrap.com/css/#horizontal-description


/* ---------------- USER Pages Rebuild Info ---------------------

Beginning with the 1.3.0 release, an effort was begun to separate the programming
layer from the presentation layer for all scripts with this header.

All Public pages have certain variables in common that build the page:
  
	$primary_page_info = any information related to the page
	$primary_links = top of page links
	$secondary_links = sublinks
	
	$header1_X = an <h2> header on the page
	$header2_X = an <h3> subheader on the page
	
	$page_infoX = the bulk of the information on the page.
	
	$labelX = the various labels in a table or on a form
	$table_headX = all table headers (column names)
	$table_bodyX = table body info
	$messageX = various messages to display
	
	$print_page_link = "<p><span class='icon'><img src='".$base_url."images/printer.png' border='0' alt='Print' title='Print' /></span><a id='modal_window_link' class='data' href='".$base_url."output/print.php?section=".$section."&amp;action=print' title='Print'>Print This Page</a></p>";
	
Declare all variables empty at the top of the script. Add on later...
	$primary_page_info = "";
	$header1_1 = "";
	$page_info1 = "";
	$header1_2 = "";
	$page_info2 = "";
	
	$table_head1 = "";
	$table_body1 = "";
	
	etc., etc., etc.

 * ---------------- END Rebuild Info --------------------- */


include (DB.'judging_locations.db.php');

$primary_page_info = "";
$header1_1 = "";
$page_info1 = "";
$table_head1 = "";
$table_body1 = "";
$table_head2 = "";
$table_body2 = "";
$table_head3 = "";
$table_body3 = "";

// Page specific variables
$user_edit_links = "";
$edit_user_info_link = "";
$edit_email_link = "";
$edit_password_link = "";
$name = "";
$email = "";
$phone = "";
$discount = "";
$aha_number = "";

// Build useful variables
if (($_SESSION['brewerDiscount'] == "Y") && ($_SESSION['contestEntryFeePasswordNum'] != "")) $entry_discount = TRUE; else $entry_discount = FALSE;
$brewer_assignment .= brewer_assignment($_SESSION['user_id'],"1");
$assignment_array = str_replace(", ",",",$brewer_assignment);
$assignment_array = explode(",", $assignment_array);
if ((!in_array("Judge",$assignment_array)) && ($_SESSION['brewerJudge'] == "Y") && ($totalRows_judging3 > 1)) $judge_available_not_assigned = TRUE; else $judge_available_not_assigned = FALSE;
if ((!in_array("Steward",$assignment_array)) && ($_SESSION['brewerSteward'] == "Y") && ($totalRows_judging3 > 1)) $steward_available_not_assigned = TRUE; else $steward_available_not_assigned = FALSE;
if ((in_array("Judge",$assignment_array)) && ($_SESSION['brewerJudge'] == "Y") && ($totalRows_judging3 > 1)) $assignment = "judge";
elseif ((in_array("Steward",$assignment_array)) && ($_SESSION['brewerSteward'] == "Y") && ($totalRows_judging3 > 1)) $assignment = "steward"; 
else $assignment = "";

// Build header
$header1_1 .= "<h2>Info</h2>";

// Build primary page info (thank you message)
$primary_page_info .= sprintf("<p>Thank you for entering the %s, %s.",$_SESSION['contestName'],$_SESSION['brewerFirstName']); 
if (($totalRows_log > 0) && ($action != "print")) $primary_page_info .= " <a href='#list'>View your entries</a>.</p>";

if ($action != "print") { 
	// Build Edit My Info link
	$edit_user_info_link .= "<a href='".$base_url."index.php?";
	if ($_SESSION['brewerID'] != "") $edit_user_info_link .= "section=brewer&amp;action=edit&amp;id=".$_SESSION['brewerID']; 
	else $edit_user_info_link .= "action=add&amp;section=brewer&amp;go=judge";
	$edit_user_info_link .= "'>Edit My Info</a>";
	
	// Build Change My Email Address link
	$edit_email_link .= "<a href='".$base_url."index.php?section=user&amp;action=username&amp;id=".$_SESSION['brewerID']."'>Change My Email Address</a>";
	
	// Build Change My Email Address link
	$edit_password_link .= "<a href='".$base_url."index.php?section=user&amp;action=password&amp;id=".$_SESSION['brewerID']."'>Change My Password</a>";
	
	$user_edit_links .= "<div class='adminSubNavContainer'>";
	
	$user_edit_links .= "<span class='adminSubNav'>";
	$user_edit_links .= "<span class='icon'><img src='".$base_url."images/user_edit.png' /></span>";
	$user_edit_links .= $edit_user_info_link;
	$user_edit_links .= "</span>";
	
	if (!NHC) {
	$user_edit_links .= "<span class='adminSubNav'>";
	$user_edit_links .= "<span class='icon'><img src='".$base_url."images/email_edit.png' /></span>";
	$user_edit_links .= $edit_email_link;
	$user_edit_links .= "</span>";
	}
	
	$user_edit_links .= "<span class='adminSubNav'>";
	$user_edit_links .= "<span class='icon'><img src='".$base_url."images/key.png' /></span>";
	$user_edit_links .= $edit_password_link;
	$user_edit_links .= "</span>";
	
	$user_edit_links .= "</div>";
} // end if ($action != "print")


// Build User Info
$name .= $_SESSION['brewerFirstName']." ".$_SESSION['brewerLastName'];
$email .= $_SESSION['brewerEmail'];
if (!empty($_SESSION['brewerAddress'])) $address = $_SESSION['brewerAddress']; else $address = "None entered";
if (!empty($_SESSION['brewerCity'])) $city = $_SESSION['brewerCity']; else $city = "None entered";
if (!empty($_SESSION['brewerState'])) $state = $_SESSION['brewerState']; else $state = "None entered";
if (!empty($_SESSION['brewerZip'])) $zip = $_SESSION['brewerZip']; else $zip = "None entered";
if (!empty($_SESSION['brewerCountry'])) $country = $_SESSION['brewerCountry']; else $country = "None entered";
if ($_SESSION['brewerCountry'] == "United States") $us_phone = TRUE; else $us_phone = FALSE;
if (!empty($_SESSION['brewerPhone1'])) {
	if ($us_phone) $phone .= format_phone_us($_SESSION['brewerPhone1'])." (1)"; 
	else $phone .= $_SESSION['brewerPhone1']." (1)"; 
}
if (!empty($_SESSION['brewerPhone2'])) {
	if ($us_phone) $phone .= "<br>".format_phone_us($_SESSION['brewerPhone2'])." (2)";
	else $phone .= "<br>".$_SESSION['brewerPhone2']." (2)";
}
if (!empty($_SESSION['brewerClubs'])) $club = $_SESSION['brewerClubs']; else $club = "None entered";
$discount .= "Yes (".$currency_symbol.$_SESSION['contestEntryFeePasswordNum']." per entry)";
if (!empty($_SESSION['brewerAHA'])) {
	if ($_SESSION['brewerAHA'] < "999999994") $aha_number .= sprintf("%09s",$_SESSION['brewerAHA']); 
	elseif ($_SESSION['brewerAHA'] >= "999999994") $aha_number .= "Pending"; 
} else $aha_number .= "None entered";

// Build Judge Info Display

	$judge_info = "";
	echo $a;
	$a = explode(",",$_SESSION['brewerJudgeLocation']);
	arsort($a);
	foreach ($a as $value) {
		if ($value != "0-0") {
			$b = substr($value, 2);
			
				$judging_location_info = judging_location_info($b);
				$judging_location_info = explode("^",$judging_location_info);
			
				if ($judging_location_info[0] > 0) {
					$judge_info .= "<tr>\n";
					if ($action == "print") $judge_info .= "<td class='dataList bdr1B'>"; else $judge_info .= "<td class='dataList'>";
					if ($action == "print") $judge_info .= yes_no(substr($value, 0, 1),$base_url,3); else $judge_info .= yes_no(substr($value, 0, 1),$base_url);
					$judge_info .= "</td>\n";
					if ($action == "print") $judge_info .= "<td class='dataList bdr1B'>"; else $judge_info .= "<td class='dataList'>";
					$judge_info .= $judging_location_info[1];
					$judge_info .= "</td>\n";
					if ($action == "print") $judge_info .= "<td class='dataList bdr1B'>"; else $judge_info .= "<td class='dataList'>";
					$judge_info .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $judging_location_info[2], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");
					$judge_info .= "</td>\n";
					$judge_info .= "</tr>";
				}
			}
		else $judge_info .= "";
	}

// Build Steward Info Display
$steward_info = "";
	$a = explode(",",$_SESSION['brewerStewardLocation']);
		arsort($a);
		foreach ($a as $value) {
			if ($value != "0-0") {
				$b = substr($value, 2);
				
				$judging_location_info = judging_location_info($b);
				$judging_location_info = explode("^",$judging_location_info);
				
				
					if ($judging_location_info[0] > 0) {
						$steward_info .= "<tr>\n";
						if ($action == "print") $steward_info .= "<td class='dataList bdr1B'>"; else $steward_info .= "<td class='dataList'>";
						if ($action == "print") $steward_info .= yes_no(substr($value, 0, 1),$base_url,3); else $steward_info .= yes_no(substr($value, 0, 1),$base_url);
						$steward_info .= "</td>\n";
						if ($action == "print") $steward_info .= "<td class='dataList bdr1B'>"; else $steward_info .= "<td class='dataList'>";
						$steward_info .= $judging_location_info[1];
						$steward_info .= "</td>\n";
						if ($action == "print") $steward_info .= "<td class='dataList bdr1B'>"; else $steward_info .= "<td class='dataList'>";
						$steward_info .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $judging_location_info[2], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");
						$steward_info .= "</tr>";
					}
				}
			else $steward_info .= "";
			}

if ($action == "print") $table_assign_judge = table_assignments($_SESSION['user_id'],"J",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],1);
else $table_assign_judge = table_assignments($_SESSION['user_id'],"J",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],0);

if ($action == "print") $table_assign_steward = table_assignments($_SESSION['user_id'],"S",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],1);
else $table_assign_steward = table_assignments($_SESSION['user_id'],"S",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],0);

// Build User Info table body

$table_body1 .= "<tr>";
$table_body1 .= "<td class='dataLabel' width='5%'>Name:</td>";
$table_body1 .= "<td class='data'>".$name."</td>";
$table_body1 .= "</tr>";
$table_body1 .= "<tr>";
$table_body1 .= "<td class='dataLabel'>Email:</td>";
$table_body1 .= "<td class='data'>".$email."</td>";
$table_body1 .= "</tr>";
$table_body1 .= "<tr>";
$table_body1 .= "<td class='dataLabel'>Address:</td>";
$table_body1 .= "<td class='data'>".$address."</td>";
$table_body1 .= "</tr>";
$table_body1 .= "<tr>";
$table_body1 .= "<td class='dataLabel'>City:</td>";
$table_body1 .= "<td class='data'>".$city."</td>";
$table_body1 .= "</tr>";
$table_body1 .= "<tr>";
$table_body1 .= "<td class='dataLabel'>State or Province:</td>";
$table_body1 .= "<td class='data'>".$state."</td>";
$table_body1 .= "</tr>";
$table_body1 .= "<tr>";
$table_body1 .= "<td class='dataLabel'>Zip or Postal Code:</td>";
$table_body1 .= "<td class='data'>".$zip."</td>";
$table_body1 .= "</tr>";
$table_body1 .= "<tr>";
$table_body1 .= "<td class='dataLabel'>Country:</td>";
$table_body1 .= "<td class='data'>".$country."</td>";
$table_body1 .= "</tr>";
$table_body1 .= "<tr>";
$table_body1 .= "<td class='dataLabel'>Phone Number(s):</td>";
$table_body1 .= "<td class='data'>".$phone."</td>";
$table_body1 .= "</tr>";
$table_body1 .= "<tr>";
$table_body1 .= "<td class='dataLabel'>AHA Number:</td>";
$table_body1 .= "<td class='data'>".$aha_number."&nbsp;&nbsp;<em>*An <a href='http://www.homebrewersassociation.org/' target='_blank'>American Homebrewers Association</a> (AHA) membership is required if one of your entries is selected for a <a href='http://www.greatamericanbeerfestival.com/the-competition/pro-am-competition/' target='_blank'>Great American Beer Festival Pro-Am</a>.</em></td>";
$table_body1 .= "</tr>";
$table_body1 .= "<tr>";
$table_body1 .= "<td class='dataLabel'>Drop Off Location:</td>";
$table_body1 .= "<td class='data'>".dropoff_location($_SESSION['brewerDropOff'])."</td>";
$table_body1 .= "</tr>";
$table_body1 .= "<tr>";
$table_body1 .= "<td class='dataLabel'>Club:</td>";
$table_body1 .= "<td class='data'>".$club."</td>";
$table_body1 .= "</tr>";

$table_body1 .= "<tr>";
$table_body1 .= "<td class='dataLabel'>Available to Judge?</td>";
$table_body1 .= "<td class='data'>";
if ((!empty($_SESSION['brewerJudge'])) && ($action != "print")) $table_body1 .= yes_no($_SESSION['brewerJudge'],$base_url); 
elseif ((!empty($_SESSION['brewerJudge'])) && ($action == "print")) $table_body1 .= yes_no($_SESSION['brewerJudge'],$base_url,3); 
else $table_body1 .= "None entered";
$table_body1 .= "</td>";
$table_body1 .= "</tr>";

if ($entry_discount) {

	$table_body1 .= "<tr>";
	$table_body1 .= "<td class='dataLabel'>Entry Fee Discount:</td>";
	$table_body1 .= "<td class='data'>".$discount."</td>";
	$table_body1 .= "</tr>";
	
}

if (!empty($assignment)) {
	
	$table_body1 .= "<tr>";
    $table_body1 .= "<td class='dataLabel'>Assigned As:</td>";
    $table_body1 .= "<td class='data'>".$brewer_assignment."</td>";
	$table_body1 .= "</tr>";
	
}


if ((in_array("Judge",$assignment_array)) && ($action != "print")) { 
	$table_body1 .= "<tr>";
    $table_body1 .= "<td class='dataLabel'>&nbsp;</td>";
    $table_body1 .= "<td class='data'><span class='icon'><img src='".$base_url."images/page_white_acrobat.png'  border='0' alt='Print your judging scoresheet labels' title='Judging scoresheet labels'></span><a href='".$base_url."output/labels.php?section=admin&amp;go=participants&amp;action=judging_labels&amp;id=".$_SESSION['brewerID']."'>Print Judging Scoresheet Labels</a><span class='data'>(Avery 5160 PDF Download)</span></td>";
	$table_body1 .= "</tr>";
} // end if (in_array("Judge",$assignment_array)) && ($action != "print"))


if ($_SESSION['brewerJudge'] == "Y") { 

	
	
	$bjcp_rank = explode(",",$row_brewer['brewerJudgeRank']);
	$display_rank = bjcp_rank($bjcp_rank[0],2);
	if (!empty($bjcp_rank[1])) $display_rank .= designations($row_brewer['brewerJudgeRank'],$bjcp_rank[0]);
	
	$table_body1 .= "<tr>";
	$table_body1 .= "<td class='dataLabel'>BJCP Judge ID:</td>";
    $table_body1 .= "<td class='data'>";
	if ($_SESSION['brewerJudgeID'] > "0") $table_body1 .= $_SESSION['brewerJudgeID']; else $table_body1 .= "N/A";
	$table_body1 .= "</td>";
	$table_body1 .= "</tr>";
	$table_body1 .= "<td class='dataLabel'>Beer Judge Ranks and Designations:</td>";
	$table_body1 .= "<td class='data'>";
	if ($_SESSION['brewerJudgeRank'] != "") $table_body1 .= str_replace("<br />",", ",$display_rank); else $table_body1 .= "N/A";
	$table_body1 .= "</td>";
	$table_body1 .= "<tr>";
	$table_body1 .= "<td width='10%' class='dataLabel'>Mead Judge Endorsement:</td>";
  	$table_body1 .= "<td colspan='2' class='data'>";
	if ($action == "print") $table_body1 .= yes_no($_SESSION['brewerJudgeMead'],$base_url,3); else $table_body1 .= yes_no($_SESSION['brewerJudgeMead'],$base_url); 
	$table_body1 .= "</td>";
	$table_body1 .= "</tr>";
	$table_body1 .= "<td class='dataLabel'>Categories Preferred:</td>";
    $table_body1 .= "<td class='data'>";
    if ($_SESSION['brewerJudgeLikes'] != "") $table_body1 .= style_convert($_SESSION['brewerJudgeLikes'],4,$base_url);
    $table_body1 .= "</td>";
	$table_body1 .= "<tr>";
	$table_body1 .= "<td class='dataLabel'>Categories Not Preferred:</td>";
    $table_body1 .= "<td class='data'>";
    if ($_SESSION['brewerJudgeDislikes'] != "") $table_body1 .= style_convert($_SESSION['brewerJudgeDislikes'],4,$base_url); else $table_body1 .= "N/A";		
    $table_body1 .= "</td>";
	$table_body1 .= "</tr>";

	if (!empty($judge_info)) {
		$table_body1 .= "<tr>";
		$table_body1 .= "<td class='dataLabel'>Judge Availability:</td>";
		$table_body1 .= "<td>";
		if (($assignment == "judge") || (empty($assignment))) {
			
			if (empty($table_assign_judge)) {
										
					$table_body1 .= "<table id='sortable_judge' class='dataTable' style='width:50%;float:left;'>";
    				$table_body1 .= "<thead>";
    				$table_body1 .= "<tr>";
        			$table_body1 .= "<th class='dataHeading bdr1B' width='15%'>Yes/No</th>";
        			$table_body1 .= "<th class='dataHeading bdr1B' width='40%'>Location</th>";
        			$table_body1 .= "<th class='dataHeading bdr1B'>Date/Time</th>";
    				$table_body1 .= "</tr>";
    				$table_body1 .= "</thead>";
    				$table_body1 .= "<tbody>";
    				$table_body1 .= $judge_info;	
    				$table_body1 .= "</tbody>";
    				$table_body1 .= "</table>";
					
			}
			
			else $table_body1 .= "No data retrieved.";
		}
		if ((!empty($table_assign_judge)) && (!empty($assignment))) $table_body1 .= sprintf("** You have already been assigned as a %s to a table. If you wish to change your availabilty and/or withdraw your role, <a href='%s'>contact</a> the competition organizer or judge coordinator.",$assignment,build_public_url("contact","default","default",$sef,$base_url));
		$table_body1 .= "</td>";
		$table_body1 .= "</tr>";
		
		if ((!$judge_available_not_assigned) && (!empty($table_assign_judge))) { 
			$table_body1 .= "<tr>";
			$table_body1 .= "<td class='dataLabel'>Table Assignment(s):</td>";
			$table_body1 .= "<td>";
				$table_body1 .= "<table id='judge_assignments' class='dataTable' style='width:75%;float:left;'>";
				$table_body1 .= "<thead>";
				$table_body1 .= "<tr>";
				$table_body1 .= "<th class='dataHeading bdr1B' width='33%'>Location</th>";
				$table_body1 .= "<th class='dataHeading bdr1B' width='34%'>Date/Time</th>";
				$table_body1 .= "<th class='dataHeading bdr1B'>Table</th>";
				$table_body1 .= "</tr>";
				$table_body1 .= "</thead>";
				$table_body1 .= "<tbody>";
				$table_body1 .= $table_assign_judge;
				$table_body1 .= "</tbody>";
				$table_body1 .= "</table>";
			$table_body1 .= "</td>";
			$table_body1 .= "</tr>";
		} // end if ((!$judge_available_not_assigned) && (!empty($table_assign_judge))) 
	
	} // end if (!empty($judge_info))
			
}  // end if ($_SESSION['brewerJudge'] == "Y") 



$table_body1 .= "<tr>";
$table_body1 .= "<td class='dataLabel'>Available to Steward?</td>";
$table_body1 .= "<td class='data'>";
if ((!empty($_SESSION['brewerSteward'])) && ($action != "print")) $table_body1 .= yes_no($_SESSION['brewerSteward'],$base_url); 
elseif ((!empty($_SESSION['brewerSteward'])) && ($action == "print")) $table_body1 .= yes_no($_SESSION['brewerSteward'],$base_url,3); 
else $table_body1 .= "None entered";
$table_body1 .= "</td>";
$table_body1 .= "</tr>";

if ($_SESSION['brewerSteward'] == "Y") {
	if (!empty($steward_info)) {
		$table_body1 .= "<tr>";
		$table_body1 .= "<td class='dataLabel'>Steward Availability:</td>";
		$table_body1 .= "<td>";
		if (($assignment == "steward") || (empty($assignment))) {
			
			if (empty($table_assign_steward)) {
										
					$table_body1 .= "<table id='sortable_steward' class='dataTable' style='width:50%;float:left;'>";
    				$table_body1 .= "<thead>";
    				$table_body1 .= "<tr>";
        			$table_body1 .= "<th class='dataHeading bdr1B' width='15%'>Yes/No</th>";
        			$table_body1 .= "<th class='dataHeading bdr1B' width='40%'>Location</th>";
        			$table_body1 .= "<th class='dataHeading bdr1B'>Date/Time</th>";
    				$table_body1 .= "</tr>";
    				$table_body1 .= "</thead>";
    				$table_body1 .= "<tbody>";
    				$table_body1 .= $steward_info;	
    				$table_body1 .= "</tbody>";
    				$table_body1 .= "</table>";
					
			}
			
			else $table_body1 .= "No data retrieved.";
		}
		if ((!empty($table_assign_steward)) && (!empty($assignment))) $table_body1 .= sprintf("** You have already been assigned as a %s to a table. If you wish to change your availabilty and/or withdraw your role, <a href='%s'>contact</a> the competition organizer or judge coordinator.",$assignment,build_public_url("contact","default","default",$sef,$base_url));
		$table_body1 .= "</td>";
		$table_body1 .= "</tr>";
		
		if ((!$steward_available_not_assigned) && (!empty($table_assign_steward))) { 
			$table_body1 .= "<tr>";
			$table_body1 .= "<td class='dataLabel'>Table Assignment(s):</td>";
			$table_body1 .= "<td>";
				$table_body1 .= "<table id='steward_assignments' class='dataTable' style='width:75%;float:left;'>";
				$table_body1 .= "<thead>";
				$table_body1 .= "<tr>";
				$table_body1 .= "<th class='dataHeading bdr1B' width='33%'>Location</th>";
				$table_body1 .= "<th class='dataHeading bdr1B' width='34%'>Date/Time</th>";
				$table_body1 .= "<th class='dataHeading bdr1B'>Table</th>";
				$table_body1 .= "</tr>";
				$table_body1 .= "</thead>";
				$table_body1 .= "<tbody>";
				$table_body1 .= $table_assign_steward;
				$table_body1 .= "</tbody>";
				$table_body1 .= "</table>";
			$table_body1 .= "</td>";
			$table_body1 .= "</tr>";
		} // end if ((!$judge_available_not_assigned) && (!empty($table_assign_judge)))
	}
}


// --------------------------------------------------------------
// Display
// --------------------------------------------------------------

// Display primary page info and subhead
echo $primary_page_info;
echo $header1_1;

// Display User Edit Links
echo $user_edit_links; 

?>
<!-- Display User Info -->
<table class="dataTable">
<?php echo $table_body1; ?>
</table>


<!-- Page Rebuild completed 08.27.15 --> 


