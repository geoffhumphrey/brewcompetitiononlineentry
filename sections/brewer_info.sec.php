<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable_judge').( {
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
		$('#judge_assignments').( {
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
		$('#sortable_steward').( {
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
		$('#steward_assignments').( {
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
$brewer_assignment = "";

// Page specific variables
$user_edit_links = "";
$name = "";
$email = "";
$phone = "";
$discount = "";
$aha_number = "";

// Build useful variables
if (($_SESSION['brewerDiscount'] == "Y") && ($_SESSION['contestEntryFeePasswordNum'] != "")) $entry_discount = TRUE; else $entry_discount = FALSE;
$brewer_assignment .= brewer_assignment($_SESSION['user_id'],"1","blah",$dbTable,$filter);
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
$primary_page_info .= sprintf("<p class=\"lead\">Thank you for entering the %s, %s. <small>Your account details are listed below.</small></p>",$_SESSION['contestName'],$_SESSION['brewerFirstName']); 
if ($totalRows_log > 0) { 
	$primary_page_info .= "<p class=\"lead hidden-print\"><small>Take a moment to <a href=\"#entries\">review your entries</a>"; 
	if (!$disable_pay) $primary_page_info .= " or <a href=\"".build_public_url("pay","default","default","default",$sef,$base_url)."\">pay your entry fees</a>";
	$primary_page_info .= ".</small></p>";	
}

$user_edit_links .= "<div class=\"btn-group hidden-print\" role=\"group\" aria-label=\"EditAccountFunctions\">";
$user_edit_links .= "<a class=\"btn btn-default\" href=\"".$edit_user_info_link."\"><span class=\"fa fa-user\"></span> Edit Account</a>";
if (!NHC) $user_edit_links .= "<a class=\"btn btn-default\" href=\"".$edit_user_email_link."\"><span class=\"fa fa-envelope\"></span> Change Email</a>";
$user_edit_links .= "<a class=\"btn btn-default\" href=\"".$edit_user_password_link."\"><span class=\"fa fa-key\"></span> Change Password</a>";
$user_edit_links .= "</div><!-- ./button group --> ";
$user_edit_links .= "<div class=\"btn-group hidden-print\" role=\"group\" aria-label=\"AddEntries\">";
if ($add_entry_link_show) { 
	$user_edit_links .= "<a class=\"btn btn-default\" href=\"".$add_entry_link."\"><span class=\"fa fa-plus-circle\"></span> Add an Entry</a>";
	if ((!NHC) && ($_SESSION['prefsHideRecipe'] == "N")) $user_edit_links .= "<a class=\"btn btn-default\" href=\"".$add_entry_beerxml_link."\"><span class=\"fa fa-file-code-o\"></span> Add an Entry Using BeerXML</a>";
}
$user_edit_links .= "</div><!-- ./button group -->";
	
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
	$a = explode(",",$_SESSION['brewerJudgeLocation']);
	arsort($a);
	foreach ($a as $value) {
		if ($value != "0-0") {
			$b = substr($value, 2);
			
				$judging_location_info = judging_location_info($b);
				$judging_location_info = explode("^",$judging_location_info);
			
				if ($judging_location_info[0] > 0) {
					$judge_info .= "<tr>\n";
					$judge_info .= "<td>";
					$judge_info .= yes_no(substr($value, 0, 1),$base_url);
					$judge_info .= "</td>\n";
					$judge_info .= "<td>";
					$judge_info .= $judging_location_info[1];
					$judge_info .= "</td>\n";
					$judge_info .= "<td>";
					$judge_info .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $judging_location_info[2], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");
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
						$steward_info .= "<td>";
						$steward_info .= yes_no(substr($value, 0, 1),$base_url);
						$steward_info .= "</td>\n";
						$steward_info .= "<td>";
						$steward_info .= $judging_location_info[1];
						$steward_info .= "</td>\n";
						$steward_info .= "<td>";
						$steward_info .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $judging_location_info[2], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");
						$steward_info .= "</tr>";
					}
				}
			else $steward_info .= "";
			}

if ($action == "print") $table_assign_judge = table_assignments($_SESSION['user_id'],"J",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],1);
else $table_assign_judge = table_assignments($_SESSION['user_id'],"J",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],0);

if ($action == "print") $table_assign_steward = table_assignments($_SESSION['user_id'],"S",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],1);
else $table_assign_steward = table_assignments($_SESSION['user_id'],"S",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],0);

	if ($_SESSION['brewerJudgeLikes'] != "") {
		$judgeLikes = style_convert($_SESSION['brewerJudgeLikes'],4,$base_url);
		$exploder = explode("|",$judgeLikes);
		$judgeLikesDisplay = $exploder[0];
		$judgeLikesModals = $exploder[1];
		
	} 
	else $judgeLikesDisplay = "N/A";
	
	if ($_SESSION['brewerJudgeDislikes'] != "") {
		$judgeDislikes = style_convert($_SESSION['brewerJudgeDislikes'],4,$base_url);
		$exploder = explode("|",$judgeDislikes);
		$judgeDislikesDisplay = $exploder[0];
		$judgeDislikesModals = $exploder[1];
		
	} 
	else $judgeDislikesDisplay = "N/A";

// Build User Info table body

$table_body1 .= "<div class=\"row bcoem-account-info\">";
$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>Name</strong></div>";
$table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">".$name."</div>";
$table_body1 .= "</div>";

$table_body1 .= "<div class=\"row bcoem-account-info\">";
$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>Email</strong></div>";
$table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">".$email."</div>";
$table_body1 .= "</div>";
$table_body1 .= "<div class=\"row bcoem-account-info\">";
$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>Address</strong></div>";
$table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">".$address."</div>";
$table_body1 .= "</div>";
$table_body1 .= "<div class=\"row bcoem-account-info\">";
$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>City</strong></div>";
$table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">".$city."</div>";
$table_body1 .= "</div>";
$table_body1 .= "<div class=\"row bcoem-account-info\">";
$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>State/Province</strong></div>";
$table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">".$state."</div>";
$table_body1 .= "</div>";
$table_body1 .= "<div class=\"row bcoem-account-info\">";
$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>Zip/Postal Code</strong></div>";
$table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">".$zip."</div>";
$table_body1 .= "</div>";
$table_body1 .= "<div class=\"row bcoem-account-info\">";
$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>Country</strong></div>";
$table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">".$country."</div>";
$table_body1 .= "</div>";
$table_body1 .= "<div class=\"row bcoem-account-info\">";
$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>Phone</strong></div>";
$table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">".$phone."</div>";
$table_body1 .= "</div>";
$table_body1 .= "<div class=\"row bcoem-account-info\">";
$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>Security Question</strong></div>";
$table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">".$_SESSION['userQuestion']."</div>";
$table_body1 .= "</div>";
$table_body1 .= "<div class=\"row bcoem-account-info\">";
$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>Security Question Answer</strong></div>";
$table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">".$_SESSION['userQuestionAnswer']."</div>";
$table_body1 .= "</div>";
$table_body1 .= "<div class=\"row bcoem-account-info\">";
$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>AHA Number</strong></div>";
$table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\"><a href=\"http://www.homebrewersassociation.org/membership/join-or-renew/\" target=\"_blank\" data-toggle=\"tooltip\" title=\"An American Homebrewers Association (AHA) membership is required if one of your entries is selected for a Great American Beer Festival Pro-Am.\" data-placement=\"right\">".$aha_number."</a></div>";
$table_body1 .= "</div>";
$table_body1 .= "<div class=\"row bcoem-account-info\">";
$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>Drop Off Location</strong></div>";
$table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">".dropoff_location($_SESSION['brewerDropOff']);
if ($_SESSION['brewerDropOff'] == 0) $table_body1 .= "<br><a id=\"modal_window_link\" data-toggle=\"tooltip\" title=\"Print shipping labels to attach to your box(es) of bottles\" href =\"".$base_url."output/print.output.php?section=shipping-label\">Print Shipping Labels</a>"; 
$table_body1 .= "</div>";
$table_body1 .= "</div>";
$table_body1 .= "<div class=\"row bcoem-account-info\">";
$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>Club</strong></div>";
$table_body1 .=  "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">".$club."</div>";
$table_body1 .= "</div>";

if (($row_brewer['brewerJudgeNotes'] != "") && (($_SESSION['brewerJudge'] == "Y") || ($_SESSION['brewerSteward'] == "Y"))) {  
$table_body1 .= "<div class=\"row bcoem-account-info\">";
$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>Notes to Organizers</strong></div>";
$table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">";
$table_body1 .= "<em>".$row_brewer['brewerJudgeNotes']."</em>";		
$table_body1 .= "</div>";
$table_body1 .= "</div>";
}

if ($entry_discount) {

	$table_body1 .= "<div class=\"row bcoem-account-info\">";
	$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>Fee Discount</strong></div>";
	$table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">".$discount."</div>";
	$table_body1 .= "</div>";
	
}


$table_body1 .= "<div class=\"row bcoem-account-info\">";
$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>Judge?</strong></div>";
$table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">";
if ((!empty($_SESSION['brewerJudge'])) && ($action != "print")) $table_body1 .= yes_no($_SESSION['brewerJudge'],$base_url); 
else $table_body1 .= "None entered";
$table_body1 .= "</div>";
$table_body1 .= "</div>";
if (($_SESSION['brewerJudge'] == "Y") || ($_SESSION['brewerSteward'] == "Y")) {
	$table_body1 .= "<div class=\"row bcoem-account-info\">";
	$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>Signed Waiver?</strong></div>";
	$table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">";
	if (!empty($row_brewer['brewerJudgeWaiver'])) $table_body1 .= yes_no($row_brewer['brewerJudgeWaiver'],$base_url); 
	else $table_body1 .= "None entered";
	$table_body1 .= "</div>";
	$table_body1 .= "</div>";
}

if (!empty($assignment)) {
	
	$table_body1 .= "<div class=\"row bcoem-account-info\">";
    $table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>Assigned As</strong></div>";
    $table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">".$brewer_assignment."</div>";
	$table_body1 .= "</div>";
	
}


if (in_array("Judge",$assignment_array) && ($_SESSION['brewerJudge'] == "Y")) { 
	$table_body1 .= "<div class=\"row bcoem-account-info hidden-print\">";
    $table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>&nbsp;</strong></div>";
    $table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">Print your judging scoresheet labels: <a href=\"".$base_url."output/labels.output.php?section=admin&amp;go=participants&amp;action=judging_labels&amp;id=".$_SESSION['brewerID']."&amp;psort=5160\" data-toggle=\"tooltip\" title=\"Avery 5160\">Letter</a> or <a href=\"".$base_url."output/labels.output.php?section=admin&amp;go=participants&amp;action=judging_labels&amp;id=".$_SESSION['brewerID']."&amp;psort=3422\" data-toggle=\"tooltip\" title=\"Avery 3422\">A4</a></div>";
	$table_body1 .= "</div>";
} // end if (in_array("Judge",$assignment_array)) && ($action != "print"))


if ($_SESSION['brewerJudge'] == "Y") {
	
	$bjcp_rank = explode(",",$row_brewer['brewerJudgeRank']);
	$display_rank = bjcp_rank($bjcp_rank[0],2);
	if (!empty($bjcp_rank[1])) $display_rank .= designations($row_brewer['brewerJudgeRank'],$bjcp_rank[0]);
	
	$table_body1 .= "<div class=\"row bcoem-account-info\">";
	$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>BJCP ID</strong></div>";
    $table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">";
	if ($_SESSION['brewerJudgeID'] > "0") $table_body1 .= $_SESSION['brewerJudgeID']; else $table_body1 .= "N/A";
	$table_body1 .= "</div>";
	$table_body1 .= "</div>";
	$table_body1 .= "<div class=\"row bcoem-account-info\">";
	$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>Designations</strong></div>";
	$table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">";
	if ($_SESSION['brewerJudgeRank'] != "") $table_body1 .= str_replace("<br />",", ",$display_rank); else $table_body1 .= "N/A";
	$table_body1 .= "</div>";
	$table_body1 .= "</div>";
	$table_body1 .= "<div class=\"row bcoem-account-info\">";
	$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>Competitions Judged</strong></div>";
    $table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">";
    $table_body1 .= $row_brewer['brewerJudgeExp'];		
    $table_body1 .= "</div>";
	$table_body1 .= "</div>";
	$table_body1 .= "<div class=\"row bcoem-account-info\">";
	$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>Mead Judge?</strong></div>";
  	$table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">";
	if ($action == "print") $table_body1 .= yes_no($_SESSION['brewerJudgeMead'],$base_url,3); else $table_body1 .= yes_no($_SESSION['brewerJudgeMead'],$base_url); 
	$table_body1 .= "</div>";
	$table_body1 .= "</div>";
	$table_body1 .= "<div class=\"row bcoem-account-info\">";
	$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>Preferred</strong></div>";
    $table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">";
	$table_body1 .= $judgeLikesDisplay;
    $table_body1 .= "</div>";
	$table_body1 .= "</div>";
	$table_body1 .= "<div class=\"row bcoem-account-info\">";
	$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>Non-Preferred</strong></div>";
    $table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">";
    $table_body1 .= $judgeDislikesDisplay;
	$table_body1 .= "</div>";
	$table_body1 .= "</div>";

	if (!empty($judge_info)) {
		$table_body1 .= "<div class=\"row bcoem-account-info\">";
		$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>Availability</strong></div>";
		$table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">";
		if (($assignment == "judge") || (empty($assignment))) {
			
			if (empty($table_assign_judge)) {
										
					$table_body1 .= "<table id=\"sortable_judge\" class=\"table table-condensed table-striped table-bordered table-responsive\">";
    				$table_body1 .= "<thead>";
    				$table_body1 .= "<tr>";
        			$table_body1 .= "<th width=\"10%\">Yes/No</th>";
        			$table_body1 .= "<th>Location</th>";
        			$table_body1 .= "<th>Date/Time</th>";
    				$table_body1 .= "</tr>";
    				$table_body1 .= "</thead>";
    				$table_body1 .= "<tbody>";
    				$table_body1 .= $judge_info;	
    				$table_body1 .= "</tbody>";
    				$table_body1 .= "</table>";
					
			}
			
			else $table_body1 .= "";
		}
		if ((!empty($table_assign_judge)) && (!empty($assignment))) $table_body1 .= sprintf("<p><strong class=\"text-success\">You have already been assigned as a %s to a table</strong>.</p><p>If you wish to change your availabilty and/or withdraw your role, <a href=\"%s\">contact</a> the competition organizer or judge coordinator.</p>",$assignment,build_public_url("contact","default","default","default",$sef,$base_url));
		elseif ((in_array("Steward",$assignment_array)) && (!empty($assignment))) $table_body1 .= sprintf("You have already been assigned as a %s.",$assignment);
		$table_body1 .= "</div>";
		$table_body1 .= "</div>";
		
		if ((!$judge_available_not_assigned) && (!empty($table_assign_judge))) { 
			$table_body1 .= "<div class=\"row bcoem-account-info\">";
			$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>Table Assignment(s):</strong></div>";
			$table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">";
				$table_body1 .= "<table id=\"judge_assignments\" class=\"table table-condensed table-striped table-bordered table-responsive\">";
				$table_body1 .= "<thead>";
				$table_body1 .= "<tr>";
				$table_body1 .= "<th width=\"33%\">Location</th>";
				$table_body1 .= "<th width=\"34%\">Date/Time</th>";
				$table_body1 .= "<th>Table</th>";
				$table_body1 .= "</tr>";
				$table_body1 .= "</thead>";
				$table_body1 .= "<tbody>";
				$table_body1 .= $table_assign_judge;
				$table_body1 .= "</tbody>";
				$table_body1 .= "</table>";
			$table_body1 .= "</div>";
			$table_body1 .= "</div>";
		} // end if ((!$judge_available_not_assigned) && (!empty($table_assign_judge))) 
	
	} // end if (!empty($judge_info))
	
}  // end if ($_SESSION['brewerJudge'] == "Y") 



$table_body1 .= "<div class=\"row bcoem-account-info\">";
$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>Steward?</strong></div>";
$table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">";
if ((!empty($_SESSION['brewerSteward'])) && ($action != "print")) $table_body1 .= yes_no($_SESSION['brewerSteward'],$base_url);
else $table_body1 .= "None entered";
$table_body1 .= "</div>";
$table_body1 .= "</div>";

if ($_SESSION['brewerSteward'] == "Y") {
	if (!empty($steward_info)) {
		$table_body1 .= "<div class=\"row bcoem-account-info\">";
		$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>Availability</strong></div>";
		$table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">";
		if (($assignment == "steward") || (empty($assignment))) {
			
			if (empty($table_assign_steward)) {
										
					$table_body1 .= "<table id=\"sortable_steward\" class=\"table table-condensed table-striped table-bordered table-responsive\">";
    				$table_body1 .= "<thead>";
    				$table_body1 .= "<tr>";
        			$table_body1 .= "<th width=\"10%\">Yes/No</th>";
        			$table_body1 .= "<th>Location</th>";
        			$table_body1 .= "<th>Date/Time</th>";
    				$table_body1 .= "</tr>";
    				$table_body1 .= "</thead>";
    				$table_body1 .= "<tbody>";
    				$table_body1 .= $steward_info;	
    				$table_body1 .= "</tbody>";
    				$table_body1 .= "</table>";
					
			}
			
			else $table_body1 .= "";
		}
		if ((!empty($table_assign_steward)) && (!empty($assignment))) $table_body1 .= sprintf("<p><strong class=\"text-success\">You have already been assigned as a %s to a table</strong>.</p><p>If you wish to change your availabilty and/or withdraw your role, <a href=\"%s\">contact</a> the competition organizer or judge coordinator.</p>",$assignment,build_public_url("contact","default","default","default",$sef,$base_url));
		elseif ((in_array("Judge",$assignment_array)) && (!empty($assignment))) $table_body1 .= sprintf("You have already been assigned as a %s.",$assignment);
		$table_body1 .= "</div>";
		$table_body1 .= "</div>";
		
		if ((!$steward_available_not_assigned) && (!empty($table_assign_steward))) { 
			$table_body1 .= "<div class=\"row bcoem-account-info\">";
			$table_body1 .= "<div class=\"col-lg-3 col-md-3 col-sm-4 col-xs-4\"><strong>Assignment(s)</strong></div>";
			$table_body1 .= "<div class=\"col-lg-9 col-md-9 col-sm-8 col-xs-8\">";
				$table_body1 .= "<table id=\"steward_assignments\" class=\"table table-striped table-bordered table-responsive\">";
				$table_body1 .= "<thead>";
				$table_body1 .= "<tr>";
				$table_body1 .= "<th width=\"33%\">Location</th>";
				$table_body1 .= "<th width=\"34%\">Date/Time</th>";
				$table_body1 .= "<th >Table</th>";
				$table_body1 .= "</tr>";
				$table_body1 .= "</thead>";
				$table_body1 .= "<tbody>";
				$table_body1 .= $table_assign_steward;
				$table_body1 .= "</tbody>";
				$table_body1 .= "</table>";
			$table_body1 .= "</div>";
			$table_body1 .= "</div>";
		} // end if ((!$judge_available_not_assigned) && (!empty($table_assign_judge)))
	}
}



// --------------------------------------------------------------
// Display
// --------------------------------------------------------------

// Display primary page info and subhead
$judgeLikesModals = explode("^",$judgeLikesModals);
$judgeDislikesModals = explode("^",$judgeDislikesModals);

foreach ($judgeLikesModals as $judgeLikesModalDisplay) {
	echo $judgeLikesModalDisplay;
}


foreach ($judgeDislikesModals as $judgeDislikesModalDisplay) {
	echo $judgeDislikesModalDisplay;
}

echo $primary_page_info;
// Display User Edit Links
echo "<div class=\"bcoem-account-info\">";
echo $user_edit_links; 
echo "</div>";
echo $header1_1;

?>
<!-- Display User Info -->
<!-- <table class="table table-responsive bcoem-user-info-table"> -->
<?php echo $table_body1; ?>
<!-- </table> -->


<!-- Page Rebuild completed 08.27.15 --> 


