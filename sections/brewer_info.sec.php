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

		$('#sortable_staff').dataTable( {
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

include_once (DB.'judging_locations.db.php');

$primary_page_info = "";
$header1_1 = "";
$page_info1 = "";
$table_head1 = "";
$account_display = "";
$table_head2 = "";
$table_body2 = "";
$table_head3 = "";
$table_body3 = "";
$brewer_assignment = "";
if ($_SESSION['prefsProEdition'] == 1) $display_left_cols = "col-lg-4 col-md-4 col-sm-5 col-xs-6";
else $display_left_cols = "col-lg-3 col-md-4 col-sm-5 col-xs-6";
if ($_SESSION['prefsProEdition'] == 1) $display_right_cols = "col-lg-8 col-md-8 col-sm-7 col-xs-6";
else $display_right_cols = "col-lg-9 col-md-8 col-sm-7 col-xs-6";

// Page specific variables
$user_edit_links = "";
$name = "";
$email = "";
$phone = "";
$discount = "";
$aha_number = "";
$judgeLikes = "";
$exploder = "";
$judgeLikesDisplay = "";
$judgeLikesModals = "";
$judgeDislikesDisplay = "";
$judgeDislikesModals = "";
$table_assign_judge = "";
$table_assign_steward = "";

$show_judge_steward_fields = TRUE;

if (($_SESSION['prefsProEdition'] == 1) && (isset($row_brewer['brewerBreweryName']))) {
	$show_judge_steward_fields = FALSE;
	$label_contact = $label_contact." ";
	$label_organization = $label_organization." ";
}

else {
	$label_contact = "";
	$label_organization = "";
}

// Build useful variables
$judge_available_not_assigned = FALSE;
$steward_available_not_assigned = FALSE;
$assignment = "";

if (($_SESSION['brewerDiscount'] == "Y") && ($_SESSION['contestEntryFeePasswordNum'] != "")) $entry_discount = TRUE; else $entry_discount = FALSE;
$brewer_assignment .= brewer_assignment($_SESSION['user_id'],"1","blah",$dbTable,$filter);
$assignment_array = str_replace(", ",",",$brewer_assignment);
$assignment_array = explode(",", $assignment_array);

if ((in_array($label_judge,$assignment_array)) && ($_SESSION['brewerJudge'] == "Y") && ($totalRows_judging3 > 1)) {
 	$assignment = "judge";
 	$judge_available_not_assigned = TRUE;
}

if ((in_array($label_steward,$assignment_array)) && ($_SESSION['brewerSteward'] == "Y") && ($totalRows_judging3 > 1)) {
	$assignment = "steward";
	$steward_available_not_assigned = TRUE;
}

// Build header
$header1_1 .= sprintf("<h2>%s</h2>",$label_account_info);
// Build primary page info (thank you message)
$primary_page_info .= sprintf("<p class=\"lead\">%s %s, %s. <small class=\"text-muted\">%s %s.</small></p>",$brewer_info_000,$_SESSION['contestName'],$_SESSION['brewerFirstName'],$brewer_info_001,getTimeZoneDateTime($_SESSION['prefsTimeZone'], strtotime($_SESSION['userCreated']), $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time-no-gmt"));

if (($totalRows_log > 0) && ($show_entries)) {
	$primary_page_info .= "<p class=\"lead hidden-print\"><small>";
	$primary_page_info .= sprintf("%s",$brewer_info_002);
	if (!$disable_pay) {
		if (!$comp_paid_entry_limit) $primary_page_info .= " ".$brewer_info_011." <a href=\"".build_public_url("pay","default","default","default",$sef,$base_url)."\">".$brewer_info_003."</a>.</small></p>";
		else {
			if ($_SESSION['contestEntryFee'] > 0) $primary_page_info .= sprintf(".</small></p><p class=\"lead hidden-print\"><small><span class=\"text-danger\"><strong>%s:</strong> %s</span> <a href=\"%s\">%s</a>",ucfirst(strtolower($label_please_note)),$pay_text_034,$link_contacts,$pay_text_001);
		} 
	}
	$primary_page_info .= "</small></p>";
}

if ($_SESSION['prefsEval'] == 1) include (EVALS.'my_account.eval.php');

$user_edit_links .= "<div class=\"btn-group hidden-print\" role=\"group\" aria-label=\"EditAccountFunctions\">";
$user_edit_links .= sprintf("<a class=\"btn btn-default\" href=\"%s\"><span class=\"fa fa-user\"></span> %s</a>",$edit_user_info_link,$label_edit_account);
if (!NHC) $user_edit_links .= sprintf("<a class=\"btn btn-default\" href=\"".$edit_user_email_link."\"><span class=\"fa fa-envelope\"></span> %s</a>",$label_change_email);
$user_edit_links .= sprintf("<a class=\"btn btn-default\" href=\"%s\"><span class=\"fa fa-key\"></span> %s</a>",$edit_user_password_link,$label_change_password);
$user_edit_links .= "</div><!-- ./button group --> ";
$user_edit_links .= "<div class=\"btn-group hidden-print\" role=\"group\" aria-label=\"AddEntries\">";
if (($show_entries) && ($add_entry_link_show)) $user_edit_links .= sprintf("<a class=\"btn btn-default\" href=\"%s\"><span class=\"fa fa-plus-circle\"></span> %s</a>",$add_entry_link,$label_add_entry);
$user_edit_links .= "</div><!-- ./button group -->";


// Build User Info
$name .= $_SESSION['brewerFirstName']." ".$_SESSION['brewerLastName'];
$email .= $_SESSION['user_name'];
if (!empty($_SESSION['brewerAddress'])) $address = $_SESSION['brewerAddress']; else $address = $label_none_entered;
if (!empty($_SESSION['brewerCity'])) $city = $_SESSION['brewerCity']; else $city = $label_none_entered;
if (!empty($_SESSION['brewerState'])) $state = $_SESSION['brewerState']; else $state = $label_none_entered;
if (!empty($_SESSION['brewerZip'])) $zip = $_SESSION['brewerZip']; else $zip = $label_none_entered;
if (!empty($_SESSION['brewerCountry'])) $country = $_SESSION['brewerCountry']; else $country = $label_none_entered;
if ($_SESSION['brewerCountry'] == "United States") $us_phone = TRUE; else $us_phone = FALSE;
if (!empty($_SESSION['brewerPhone1'])) {
	if ($us_phone) $phone .= format_phone_us($_SESSION['brewerPhone1'])." (1)";
	else $phone .= $_SESSION['brewerPhone1']." (1)";
}
if (!empty($_SESSION['brewerPhone2'])) {
	if ($us_phone) $phone .= "<br>".format_phone_us($_SESSION['brewerPhone2'])." (2)";
	else $phone .= "<br>".$_SESSION['brewerPhone2']." (2)";
}
if (!empty($_SESSION['brewerClubs'])) $club = $_SESSION['brewerClubs']; else $club = $label_none_entered;
$discount .= $label_yes. " (".$currency_symbol.$_SESSION['contestEntryFeePasswordNum']." ".$brewer_info_004.")";
if (!empty($_SESSION['brewerAHA'])) {
	if ($_SESSION['brewerAHA'] < "999999994") $aha_number .= sprintf("%09s",$_SESSION['brewerAHA']);
	elseif ($_SESSION['brewerAHA'] >= "999999994") $aha_number .= "Pending";
} else $aha_number .= $label_none_entered;

if (!empty($_SESSION['brewerMHP'])) $mhp_number = $_SESSION['brewerMHP'];
else $mhp_number = $label_none_entered;

$pro_am = yes_no($_SESSION['brewerProAm'],$base_url,2);

// Build Judge Info Display

$judge_info = "";
$staff_info = "";
$a = explode(",",$_SESSION['brewerJudgeLocation']);
arsort($a);

foreach ($a as $value) {
	
	if ($value != "0-0") {
		
		$b = substr($value, 2);

		$judging_location_info = judging_location_info($b);
		$judging_location_info = explode("^",$judging_location_info);

		// Judging Location Availability
		if (($judging_location_info[0] > 0) && ($judging_location_info[5] < 2))  {
			
			$judge_info .= "<tr>\n";
			$judge_info .= "<td>";
			$judge_info .= yes_no(substr($value, 0, 1),$base_url,1);
			$judge_info .= "</td>\n";
			$judge_info .= "<td>";
			$judge_info .= $judging_location_info[1];
			if ($judging_location_info[5] == "1") $judge_info .= "<br><em><small>".$judging_location_info[3]."</small></em>";
			$judge_info .= "</td>\n";
			$judge_info .= "<td>";
			$judge_info .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $judging_location_info[2], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");
			if (!empty($judging_location_info[4])) $judge_info .= " - ".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $judging_location_info[4], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");
			$judge_info .= "</td>\n";
			$judge_info .= "</tr>";

		}

		// Staff Location Availability
		if (($judging_location_info[0] > 0) && ($judging_location_info[5] == 2))  {
			
			$staff_info .= "<tr>\n";
			$staff_info .= "<td>";
			$staff_info .= yes_no(substr($value, 0, 1),$base_url,1);
			$staff_info .= "</td>\n";
			$staff_info .= "<td>";
			$staff_info .= $judging_location_info[1];
			if ($judging_location_info[5] == "1") $staff_info .= "<br><em><small>".$judging_location_info[3]."</small></em>";
			$staff_info .= "</td>\n";
			$staff_info .= "<td>";
			$staff_info .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $judging_location_info[2], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");
			if (!empty($judging_location_info[4])) $staff_info .= " - ".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $judging_location_info[4], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");
			$staff_info .= "</td>\n";
			$staff_info .= "</tr>";
			
		}

	}

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
			$steward_info .= yes_no(substr($value, 0, 1),$base_url,1);
			$steward_info .= "</td>\n";
			$steward_info .= "<td>";
			$steward_info .= $judging_location_info[1];
			$steward_info .= "</td>\n";
			$steward_info .= "<td>";
			$steward_info .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $judging_location_info[2], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time");
			$steward_info .= "</tr>";
		}
	}
}

if ($_SESSION['jPrefsTablePlanning'] == 0) {
	if ($action == "print") $table_assign_judge = table_assignments($_SESSION['user_id'],"J",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],1);
	else $table_assign_judge = table_assignments($_SESSION['user_id'],"J",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],0);
	if ($action == "print") $table_assign_steward = table_assignments($_SESSION['user_id'],"S",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],1);
	else $table_assign_steward = table_assignments($_SESSION['user_id'],"S",$_SESSION['prefsTimeZone'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeFormat'],0);
}

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

// Build User Info display
$account_display .= "<div class=\"row bcoem-account-info\">";
$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_contact.$label_name);
$account_display .= "<div class=\"".$display_right_cols."\">".$name."</div>";
$account_display .= "</div>";

$account_display .= "<div class=\"row bcoem-account-info\">";
$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_contact.$label_email);
$account_display .= "<div class=\"".$display_right_cols."\">".$email."</div>";
$account_display .= "</div>";

$account_display .= "<div class=\"row bcoem-account-info\">";
$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_contact.$label_phone);
$account_display .= "<div class=\"".$display_right_cols."\">".$phone."</div>";
$account_display .= "</div>";

if (($_SESSION['prefsProEdition'] == 1) && (!$show_judge_steward_fields)) {

	$account_display .= "<hr>";

	$account_display .= "<div class=\"row bcoem-account-info\">";
	$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_organization.$label_name);
	$account_display .= "<div class=\"".$display_right_cols."\">".$_SESSION['brewerBreweryName']."</div>";
	$account_display .= "</div>";

	$account_display .= "<div class=\"row bcoem-account-info\">";
	$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_organization.$label_ttb);
	$account_display .= "<div class=\"".$display_right_cols."\">".$_SESSION['brewerBreweryTTB']."</div>";
	$account_display .= "</div>";
}

$account_display .= "<div class=\"row bcoem-account-info\">";
$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_organization.$label_address);
$account_display .= "<div class=\"".$display_right_cols."\">".$address."</div>";
$account_display .= "</div>";

$account_display .= "<div class=\"row bcoem-account-info\">";
$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_organization.$label_city);
$account_display .= "<div class=\"".$display_right_cols."\">".$city."</div>";
$account_display .= "</div>";

$account_display .= "<div class=\"row bcoem-account-info\">";
$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_organization.$label_state_province);
$account_display .= "<div class=\"".$display_right_cols."\">".$state."</div>";
$account_display .= "</div>";

$account_display .= "<div class=\"row bcoem-account-info\">";
$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_organization.$label_zip);
$account_display .= "<div class=\"".$display_right_cols."\">".$zip."</div>";
$account_display .= "</div>";

$account_display .= "<div class=\"row bcoem-account-info\">";
$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_organization.$label_country);
$account_display .= "<div class=\"".$display_right_cols."\">".$country."</div>";
$account_display .= "</div>";

if (($_SESSION['prefsProEdition'] == 1) && (!$show_judge_steward_fields)) $account_display .= "<hr>";

if ($_SESSION['prefsProEdition'] == 0) {

	$account_display .= "<div class=\"row bcoem-account-info\">";
	$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong> <span style=\"color: #F2D06C; background-color: #000;\" class=\"badge\">MHP</span></div>",$label_mhp_number);
	$account_display .= sprintf("<div class=\"".$display_right_cols."\"><a class=\"hide-loader\" href=\"https://www.masterhomebrewerprogram.com\" target=\"_blank\" data-toggle=\"tooltip\" title=\"%s\" data-placement=\"right\">".$mhp_number."</a></div>",$brewer_text_053);
	$account_display .= "</div>";

	if ($show_judge_steward_fields) {
		$account_display .= "<div class=\"row bcoem-account-info\">";
		$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_aha_number);
		$account_display .= sprintf("<div class=\"".$display_right_cols."\"><a class=\"hide-loader\" href=\"http://www.homebrewersassociation.org/membership/join-or-renew/\" target=\"_blank\" data-toggle=\"tooltip\" title=\"%s\" data-placement=\"right\">".$aha_number."</a></div>",$brewer_info_005);
		$account_display .= "</div>";
		if (($_SESSION['prefsProEdition'] == 0) && ($_SESSION['brewerCountry'] == "United States")) {
			$account_display .= "<div class=\"row bcoem-account-info\">";
			$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_pro_am);
			$account_display .= sprintf("<div class=\"".$display_right_cols."\">%s</div>",$pro_am);
			$account_display .= "</div>";
		}
	} 

	$account_display .= "<div class=\"row bcoem-account-info\">";
	$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_drop_off);
	$account_display .= "<div class=\"".$display_right_cols."\">".dropoff_location($_SESSION['brewerDropOff']);
	if ($_SESSION['brewerDropOff'] == 0) $account_display .= sprintf("<br><a data-fancybox data-type=\"iframe\" class=\"modal-window-link hide-loader\" data-toggle=\"tooltip\" title=\"%s\" href =\"".$base_url."includes/output.inc.php?section=shipping-label\">%s</a>",$brewer_info_006,$brewer_info_007);
	$account_display .= "</div>";
	$account_display .= "</div>";

	$account_display .= "<div class=\"row bcoem-account-info\">";
	$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_club);
	$account_display .=  "<div class=\"".$display_right_cols."\">".$club."</div>";
	$account_display .= "</div>";

	if ($entry_discount) {

		$account_display .= "<div class=\"row bcoem-account-info\">";
		$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_discount);
		$account_display .= "<div class=\"".$display_right_cols."\">".$discount."</div>";
		$account_display .= "</div>";

	}

}

if (($_SESSION['brewerJudge'] == "Y") || ($_SESSION['brewerSteward'] == "Y")) {

	// BJCP ID (show for either judge or steward)
	$account_display .= "<div class=\"row bcoem-account-info\">";
	$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_bjcp_id);
	$account_display .= "<div class=\"".$display_right_cols."\">";
	if ($_SESSION['brewerJudgeID'] > "0") $account_display .= $_SESSION['brewerJudgeID']; else $account_display .= "N/A";
	$account_display .= "</div>";
	$account_display .= "</div>";

	$account_display .= "<div class=\"row bcoem-account-info\">";
	$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_waiver);
	$account_display .= "<div class=\"".$display_right_cols."\">";
	if (!empty($row_brewer['brewerJudgeWaiver'])) $account_display .= yes_no($row_brewer['brewerJudgeWaiver'],$base_url,2);
	else $account_display .= $label_none_entered;
	$account_display .= "</div>";
	$account_display .= "</div>";

}

if ($show_judge_steward_fields) {

	if (($row_brewer['brewerJudgeNotes'] != "") && (($_SESSION['brewerJudge'] == "Y") || ($_SESSION['brewerSteward'] == "Y") || ($_SESSION['brewerStaff'] == "Y"))) {
		$account_display .= "<div class=\"row bcoem-account-info\">";
		$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_org_notes);
		$account_display .= "<div class=\"".$display_right_cols."\">";
		$account_display .= "<em>".$row_brewer['brewerJudgeNotes']."</em>";
		$account_display .= "</div>";
		$account_display .= "</div>";
	}

	if ($_SESSION['brewerJudge'] == "Y") $account_display .= "<hr>";
	$account_display .= "<div class=\"row bcoem-account-info\">";
	$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_judge);
	$account_display .= "<div class=\"".$display_right_cols."\">";
	if ((!empty($_SESSION['brewerJudge'])) && ($action != "print")) $account_display .= yes_no($_SESSION['brewerJudge'],$base_url,2);
	else $account_display .= "None entered";
	$account_display .= "</div>";
	$account_display .= "</div>";

	if (!empty($assignment)) {

		$account_display .= "<div class=\"row bcoem-account-info\">";
		$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_assignment);
		$account_display .= "<div class=\"".$display_right_cols."\">".ucwords($brewer_assignment)."</div>";
		$account_display .= "</div>";

	}

	if ($_SESSION['brewerJudge'] == "Y") {

		if ($_SESSION['prefsEval'] == 0) {
			$account_display .= "<div class=\"row bcoem-account-info hidden-print\">";
			$account_display .= "<div class=\"".$display_left_cols."\"><strong>&nbsp;</strong></div>";
			$account_display .= sprintf("<div class=\"".$display_right_cols."\">%s <a class=\"hide-loader\" href=\"".$base_url."includes/output.inc.php?section=labels-judge&amp;go=participants&amp;action=judging_labels&amp;id=".$_SESSION['brewerID']."&amp;psort=5160\" data-toggle=\"tooltip\" title=\"Avery 5160\">%s</a> %s <a class=\"hide-loader\" href=\"".$base_url."includes/output.inc.php?section=labels-judge&amp;go=participants&amp;action=judging_labels&amp;id=".$_SESSION['brewerID']."&amp;psort=3422\" data-toggle=\"tooltip\" title=\"Avery 3422\">A4</a></div>",$brewer_info_012, $label_letter, $brewer_info_011);
			$account_display .= "</div>";
		}

		$bjcp_rank = explode(",",$row_brewer['brewerJudgeRank']);
		$display_rank = bjcp_rank($bjcp_rank[0],2);
		if (!empty($bjcp_rank[1])) $display_rank .= designations($row_brewer['brewerJudgeRank'],$bjcp_rank[0]);
		$account_display .= "<div class=\"row bcoem-account-info\">";
		$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_bjcp_mead);
		$account_display .= "<div class=\"".$display_right_cols."\">";
		if ($action == "print") $account_display .= yes_no($_SESSION['brewerJudgeMead'],$base_url,3); else $account_display .= yes_no($_SESSION['brewerJudgeMead'],$base_url);
		$account_display .= "</div>";
		$account_display .= "</div>";
		$account_display .= "<div class=\"row bcoem-account-info\">";
		$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_bjcp_cider);
		$account_display .= "<div class=\"".$display_right_cols."\">";
		if ($action == "print") $account_display .= yes_no($_SESSION['brewerJudgeCider'],$base_url,3); else $account_display .= yes_no($_SESSION['brewerJudgeCider'],$base_url);
		$account_display .= "</div>";
		$account_display .= "</div>";
		$account_display .= "<div class=\"row bcoem-account-info\">";
		$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_designations);
		$account_display .= "<div class=\"".$display_right_cols."\">";
		if ($_SESSION['brewerJudgeRank'] != "") $account_display .= str_replace("<br />",", ",$display_rank); else $account_display .= "N/A";
		$account_display .= "</div>";
		$account_display .= "</div>";

		if ($_SESSION['prefsProEdition'] == 1) $participant_orgs_label = $label_industry_affiliations;
		else $participant_orgs_label = $label_brewing_partners;

		$account_display .= "<div class=\"row bcoem-account-info\">";
		$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$participant_orgs_label);
		$account_display .= "<div class=\"".$display_right_cols."\">";
		
		if (!empty($_SESSION['brewerAssignment'])) {

			$affiliated_orgs = json_decode($_SESSION['brewerAssignment'],true);
			$affiliations = array();

			if (!empty($affiliated_orgs['affilliated'])) {
			    foreach($affiliated_orgs['affilliated'] as $value) {
			    	if (!empty($value)) {
			    		$affiliations[] = $value;
			    	}
			    }
			}
			
			if (!empty($affiliated_orgs['affilliatedOther'])) {
			    foreach($affiliated_orgs['affilliatedOther'] as $value) {
			        if (!empty($value)) {
			    		$affiliations[] = $value;
			    	}
			    }
			}

			if (!empty($affiliations)) {
				$affiliations = implode(", ",$affiliations);
				$account_display .= $affiliations;
			}

			else {
				$account_display .= $label_none;
			}

		}
		
		else $account_display .= $label_none;
		$account_display .= "</div>";
		$account_display .= "</div>";

		$account_display .= "<div class=\"row bcoem-account-info\">";
		$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_judge_comps);
		$account_display .= "<div class=\"".$display_right_cols."\">";
		$account_display .= $row_brewer['brewerJudgeExp'];
		$account_display .= "</div>";
		$account_display .= "</div>";
		$account_display .= "<div class=\"row bcoem-account-info\">";
		$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_judge_preferred);
		$account_display .= "<div class=\"".$display_right_cols."\">";
		$account_display .= $judgeLikesDisplay;
		$account_display .= "</div>";
		$account_display .= "</div>";
		$account_display .= "<div class=\"row bcoem-account-info\">";
		$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_judge_non_preferred);
		$account_display .= "<div class=\"".$display_right_cols."\">";
		$account_display .= $judgeDislikesDisplay;
		$account_display .= "</div>";
		$account_display .= "</div>";

		if (!empty($judge_info)) {
			$account_display .= "<div class=\"row bcoem-account-info\">";
			$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_avail);
			$account_display .= "<div class=\"".$display_right_cols."\">";
			if (($assignment == "judge") || (empty($assignment))) {
				if (empty($table_assign_judge)) {
						$account_display .= "<table id=\"sortable_judge\" class=\"table table-condensed table-striped table-bordered table-responsive\">";
						$account_display .= "<thead>";
						$account_display .= "<tr>";
						$account_display .= sprintf("<th width=\"14%%\">%s/%s</th>",$label_yes,$label_no);
						$account_display .= sprintf("<th width=\"43%%\">%s</th>",$label_session);
						$account_display .= sprintf("<th width=\"43%%\">%s</th>",$label_date);
						$account_display .= "</tr>";
						$account_display .= "</thead>";
						$account_display .= "<tbody>";
						$account_display .= $judge_info;
						$account_display .= "</tbody>";
						$account_display .= "</table>";
				}
			}

			if (!empty($table_assign_judge)) $account_display .= sprintf("<p><strong class=\"text-success\">%s %s.</strong></p><p>%s</p>",$brewer_info_008,$label_judge,$brewer_info_009);
			elseif ((in_array("Steward",$assignment_array)) && (!empty($assignment))) $account_display .= sprintf("%s %s.",$brewer_info_010,$label_steward);
			$account_display .= "</div>";
			$account_display .= "</div>";

			if (!empty($table_assign_judge)) {
				$account_display .= "<div class=\"row bcoem-account-info\">";
				$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_assignment);
				$account_display .= "<div class=\"".$display_right_cols."\">";
					$account_display .= "<table id=\"judge_assignments\" class=\"table table-condensed table-striped table-bordered table-responsive\">";
					$account_display .= "<thead>";
					$account_display .= "<tr>";
					$account_display .= sprintf("<th width=\"34%%\">%s</th>",$label_session);
					$account_display .= sprintf("<th width=\"33%%\">%s</th>",$label_date);
					$account_display .= sprintf("<th width=\"33%%\">%s</th>",$label_table);
					$account_display .= "</tr>";
					$account_display .= "</thead>";
					$account_display .= "<tbody>";
					$account_display .= $table_assign_judge;
					$account_display .= "</tbody>";
					$account_display .= "</table>";
				$account_display .= "</div>";
				$account_display .= "</div>";
			} // end if ((!$judge_available_not_assigned) && (!empty($table_assign_judge)))

		} // end if (!empty($judge_info))

	}  // end if ($_SESSION['brewerJudge'] == "Y")

	if ((($_SESSION['brewerSteward'] == "Y") && (!empty($steward_info))) || (($_SESSION['brewerJudge'] == "Y") && (!empty($judge_info))))  $account_display .= "<hr>";
	$account_display .= "<div class=\"row bcoem-account-info\">";
	$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_steward);
	$account_display .= "<div class=\"".$display_right_cols."\">";
	if (!empty($_SESSION['brewerSteward'])) {
		if ($action == "print") $account_display .= yes_no($_SESSION['brewerSteward'],$base_url);
		else $account_display .= yes_no($_SESSION['brewerSteward'],$base_url,2);
	}
	else $account_display .= $label_none_entered;
	$account_display .= "</div>";
	$account_display .= "</div>";

	if ($_SESSION['brewerSteward'] == "Y") {
		if (!empty($steward_info)) {
			$account_display .= "<div class=\"row bcoem-account-info\">";
			$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_avail);
			$account_display .= "<div class=\"".$display_right_cols."\">";
			if (($assignment == "steward") || (empty($assignment))) {

				if (empty($table_assign_steward)) {

						$account_display .= "<table id=\"sortable_steward\" class=\"table table-condensed table-striped table-bordered table-responsive\">";
						$account_display .= "<thead>";
						$account_display .= "<tr>";
						$account_display .= sprintf("<th width=\"14%%\">%s/%s</th>",$label_yes,$label_no);;
						$account_display .= sprintf("<th width=\"43%%\">%s</th>",$label_session);
						$account_display .= sprintf("<th width=\"43%%\">%s</th>",$label_date);
						$account_display .= "</tr>";
						$account_display .= "</thead>";
						$account_display .= "<tbody>";
						$account_display .= $steward_info;
						$account_display .= "</tbody>";
						$account_display .= "</table>";

				}

				else $account_display .= "";
			}
			if ((!empty($table_assign_steward)) && (!empty($assignment))) $account_display .= sprintf("<p><strong class=\"text-success\">You have already been assigned as a %s to a table</strong>.</p><p>If you wish to change your availabilty and/or withdraw your role, <a href=\"%s\">contact</a> the competition organizer or judge coordinator.</p>",$assignment,build_public_url("contact","default","default","default",$sef,$base_url));
			elseif ((in_array("Judge",$assignment_array)) && (!empty($assignment))) $account_display .= sprintf("You have already been assigned as a %s.",$assignment);
			$account_display .= "</div>";
			$account_display .= "</div>";

			if ((!$steward_available_not_assigned) && (!empty($table_assign_steward))) {
				$account_display .= "<div class=\"row bcoem-account-info\">";
				$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_assignment);
				$account_display .= "<div class=\"".$display_right_cols."\">";
					$account_display .= "<table id=\"steward_assignments\" class=\"table table-striped table-bordered table-responsive\">";
					$account_display .= "<thead>";
					$account_display .= "<tr>";
					$account_display .= sprintf("<th width=\"34%%\">%s</th>",$label_session);
					$account_display .= sprintf("<th width=\"33%%\">%s</th>",$label_date);
					$account_display .= sprintf("<th width=\"33%%\">%s</th>",$label_table);
					$account_display .= "</tr>";
					$account_display .= "</thead>";
					$account_display .= "<tbody>";
					$account_display .= $table_assign_steward;
					$account_display .= "</tbody>";
					$account_display .= "</table>";
				$account_display .= "</div>";
				$account_display .= "</div>";
			} // end if ((!$judge_available_not_assigned) && (!empty($table_assign_judge)))
		}
	}

}

if ((($_SESSION['brewerStaff'] == "Y") && (!empty($staff_info))) || (($_SESSION['brewerSteward'] == "Y") && (!empty($steward_info))))  $account_display .= "<hr>";
$account_display .= "<div class=\"row bcoem-account-info\">";
$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_staff);
$account_display .= "<div class=\"".$display_right_cols."\">";
if (!empty($_SESSION['brewerStaff'])) {
	if ($action == "print") $account_display .= yes_no($_SESSION['brewerStaff'],$base_url);
	else $account_display .= yes_no($_SESSION['brewerStaff'],$base_url,2);
}
else $account_display .= $label_none_entered;
$account_display .= "</div>";
$account_display .= "</div>";

if ($_SESSION['brewerStaff'] == "Y") {
	
	if (!empty($staff_info)) {

		$account_display .= "<div class=\"row bcoem-account-info\">";
		$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_avail);
		$account_display .= "<div class=\"".$display_right_cols."\">";

		$account_display .= "<table id=\"sortable_staff\" class=\"table table-condensed table-striped table-bordered table-responsive\">";
		$account_display .= "<thead>";
		$account_display .= "<tr>";
		$account_display .= sprintf("<th width=\"14%%\">%s/%s</th>",$label_yes,$label_no);;
		$account_display .= sprintf("<th width=\"43%%\">%s</th>",$label_session);
		$account_display .= sprintf("<th width=\"43%%\">%s</th>",$label_date);
		$account_display .= "</tr>";
		$account_display .= "</thead>";
		$account_display .= "<tbody>";
		$account_display .= $staff_info;
		$account_display .= "</tbody>";
		$account_display .= "</table>";

		$account_display .= "</div>";
		$account_display .= "</div>";

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
<?php echo $account_display; ?>
<!-- </table> -->
<!-- Page Rebuild completed 08.27.15 -->
