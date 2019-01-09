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
	$account_display = "";

	etc., etc., etc.

 * ---------------- END Rebuild Info --------------------- */


include (DB.'judging_locations.db.php');

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

if ((in_array("Judge",$assignment_array)) && ($_SESSION['brewerJudge'] == "Y") && ($totalRows_judging3 > 1)) {
 	$assignment = "judge";
 	$judge_available_not_assigned = TRUE;
}

if ((in_array("Steward",$assignment_array)) && ($_SESSION['brewerSteward'] == "Y") && ($totalRows_judging3 > 1)) {
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
		else $primary_page_info .= sprintf(".</small></p><p class=\"lead hidden-print\"><small><span class=\"text-danger\"><strong>%s:</strong> %s</span> <a href=\"%s\">%s</a></small></p>",ucfirst(strtolower($label_please_note)),$pay_text_034,$link_contacts,$pay_text_001);
	}
	else $primary_page_info .= "</small></p>";
}

$user_edit_links .= "<div class=\"btn-group hidden-print\" role=\"group\" aria-label=\"EditAccountFunctions\">";
$user_edit_links .= sprintf("<a class=\"btn btn-default\" href=\"".$edit_user_info_link."\"><span class=\"fa fa-user\"></span> %s</a>",$label_edit_account);
if (!NHC) $user_edit_links .= sprintf("<a class=\"btn btn-default\" href=\"".$edit_user_email_link."\"><span class=\"fa fa-envelope\"></span> %s</a>",$label_change_email);
$user_edit_links .= sprintf("<a class=\"btn btn-default\" href=\"".$edit_user_password_link."\"><span class=\"fa fa-key\"></span> %s</a>",$label_change_password);
$user_edit_links .= "</div><!-- ./button group --> ";
$user_edit_links .= "<div class=\"btn-group hidden-print\" role=\"group\" aria-label=\"AddEntries\">";
if (($show_entries) && ($add_entry_link_show)) {
	$user_edit_links .= sprintf("<a class=\"btn btn-default\" href=\"".$add_entry_link."\"><span class=\"fa fa-plus-circle\"></span> %s</a>",$label_add_entry);
	if ((!NHC) && ($_SESSION['prefsHideRecipe'] == "N")) $user_edit_links .= sprintf("<a class=\"btn btn-default\" href=\"".$add_entry_beerxml_link."\"><span class=\"fa fa-file-code-o\"></span> %s</a>",$label_add_beerXML);
}
$user_edit_links .= "</div><!-- ./button group -->";

// Build User Info
$name .= $_SESSION['brewerFirstName']." ".$_SESSION['brewerLastName'];
$email .= $_SESSION['brewerEmail'];
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

if ($_SESSION['brewerProAm'] == 1) $pro_am = $label_yes;
else $pro_am = $label_no;

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

$account_display .= "<div class=\"row bcoem-account-info\">";
$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_security_question);
$account_display .= "<div class=\"".$display_right_cols."\">".$_SESSION['userQuestion']."</div>";
$account_display .= "</div>";

$account_display .= "<div class=\"row bcoem-account-info\">";
$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_security_answer);
$account_display .= "<div class=\"".$display_right_cols."\">".$_SESSION['userQuestionAnswer']."</div>";
$account_display .= "</div>";

if ($show_judge_steward_fields) {
	$account_display .= "<div class=\"row bcoem-account-info\">";
	$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_aha_number);
	$account_display .= sprintf("<div class=\"".$display_right_cols."\"><a class=\"hide-loader\" href=\"http://www.homebrewersassociation.org/membership/join-or-renew/\" target=\"_blank\" data-toggle=\"tooltip\" title=\"%s\" data-placement=\"right\">".$aha_number."</a></div>",$brewer_info_005);
	$account_display .= "</div>";
	if ($_SESSION['prefsProEdition'] == 0) {
		$account_display .= "<div class=\"row bcoem-account-info\">";
		$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_pro_am);
		$account_display .= sprintf("<div class=\"".$display_right_cols."\">%s</div>",$pro_am);
		$account_display .= "</div>";
	}
}

if (($_SESSION['prefsProEdition'] == 1) && (!$show_judge_steward_fields)) $account_display .= "<hr>";

$account_display .= "<div class=\"row bcoem-account-info\">";
$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_drop_off);
$account_display .= "<div class=\"".$display_right_cols."\">".dropoff_location($_SESSION['brewerDropOff']);
if ($_SESSION['brewerDropOff'] == 0) $account_display .= sprintf("<br><a id=\"modal_window_link\" class=\"hide-loader\" data-toggle=\"tooltip\" title=\"%s\" href =\"".$base_url."output/print.output.php?section=shipping-label\">%s</a>",$brewer_info_006,$brewer_info_007);
$account_display .= "</div>";
$account_display .= "</div>";

if ($show_judge_steward_fields) {

	$account_display .= "<div class=\"row bcoem-account-info\">";
	$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_club);
	$account_display .=  "<div class=\"".$display_right_cols."\">".$club."</div>";
	$account_display .= "</div>";

	if (($row_brewer['brewerJudgeNotes'] != "") && (($_SESSION['brewerJudge'] == "Y") || ($_SESSION['brewerSteward'] == "Y"))) {
		$account_display .= "<div class=\"row bcoem-account-info\">";
		$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_org_notes);
		$account_display .= "<div class=\"".$display_right_cols."\">";
		$account_display .= "<em>".$row_brewer['brewerJudgeNotes']."</em>";
		$account_display .= "</div>";
		$account_display .= "</div>";
	}

	$account_display .= "<div class=\"row bcoem-account-info\">";
	$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_staff);
	$account_display .= "<div class=\"".$display_right_cols."\">".yes_no($_SESSION['brewerStaff'],$base_url)."</div>";
	$account_display .= "</div>";

}

if ($entry_discount) {

	$account_display .= "<div class=\"row bcoem-account-info\">";
	$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_discount);
	$account_display .= "<div class=\"".$display_right_cols."\">".$discount."</div>";
	$account_display .= "</div>";

}

if ($show_judge_steward_fields) {

	$account_display .= "<div class=\"row bcoem-account-info\">";
	$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_judge);
	$account_display .= "<div class=\"".$display_right_cols."\">";
	if ((!empty($_SESSION['brewerJudge'])) && ($action != "print")) $account_display .= yes_no($_SESSION['brewerJudge'],$base_url);
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

		$account_display .= "<div class=\"row bcoem-account-info hidden-print\">";
		$account_display .= "<div class=\"".$display_left_cols."\"><strong>&nbsp;</strong></div>";
		$account_display .= sprintf("<div class=\"".$display_right_cols."\">%s <a href=\"".$base_url."output/labels.output.php?section=admin&amp;go=participants&amp;action=judging_labels&amp;id=".$_SESSION['brewerID']."&amp;psort=5160\" data-toggle=\"tooltip\" title=\"Avery 5160\">%s</a> %s <a href=\"".$base_url."output/labels.output.php?section=admin&amp;go=participants&amp;action=judging_labels&amp;id=".$_SESSION['brewerID']."&amp;psort=3422\" data-toggle=\"tooltip\" title=\"Avery 3422\">A4</a></div>",$brewer_info_012, $label_letter, $brewer_info_011);
		$account_display .= "</div>";

		$bjcp_rank = explode(",",$row_brewer['brewerJudgeRank']);
		$display_rank = bjcp_rank($bjcp_rank[0],2);
		if (!empty($bjcp_rank[1])) $display_rank .= designations($row_brewer['brewerJudgeRank'],$bjcp_rank[0]);


		$account_display .= "<div class=\"row bcoem-account-info\">";
		$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_designations);
		$account_display .= "<div class=\"".$display_right_cols."\">";
		if ($_SESSION['brewerJudgeRank'] != "") $account_display .= str_replace("<br />",", ",$display_rank); else $account_display .= "N/A";
		$account_display .= "</div>";
		$account_display .= "</div>";
		$account_display .= "<div class=\"row bcoem-account-info\">";
		$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_judge_comps);
		$account_display .= "<div class=\"".$display_right_cols."\">";
		$account_display .= $row_brewer['brewerJudgeExp'];
		$account_display .= "</div>";
		$account_display .= "</div>";
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
		$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>Preferred</strong></div>",$label_judge_preferred);
		$account_display .= "<div class=\"".$display_right_cols."\">";
		$account_display .= $judgeLikesDisplay;
		$account_display .= "</div>";
		$account_display .= "</div>";
		$account_display .= "<div class=\"row bcoem-account-info\">";
		$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>Non-Preferred</strong></div>",$label_judge_non_preferred);
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
						$account_display .= sprintf("<th>%s/%s</th>",$label_yes,$label_no);
						$account_display .= sprintf("<th>%s</th>",$label_location);
						$account_display .= sprintf("<th>%s</th>",$label_date);
						$account_display .= "</tr>";
						$account_display .= "</thead>";
						$account_display .= "<tbody>";
						$account_display .= $judge_info;
						$account_display .= "</tbody>";
						$account_display .= "</table>";

				}

				else $account_display .= "";
			}
			if ((!empty($table_assign_judge)) && (!empty($assignment))) $account_display .= sprintf("<p><strong class=\"text-success\">%s %s.</strong></p><p>%s</p>",$brewer_info_008,$assignment,$brewer_info_009);
			elseif ((in_array("Steward",$assignment_array)) && (!empty($assignment))) $account_display .= sprintf("%s %s.",$assignment,$brewer_info_010);
			$account_display .= "</div>";
			$account_display .= "</div>";

			if ((!$judge_available_not_assigned) && (!empty($table_assign_judge))) {
				$account_display .= "<div class=\"row bcoem-account-info\">";
				$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>%s</strong></div>",$label_assignment);
				$account_display .= "<div class=\"".$display_right_cols."\">";
					$account_display .= "<table id=\"judge_assignments\" class=\"table table-condensed table-striped table-bordered table-responsive\">";
					$account_display .= "<thead>";
					$account_display .= "<tr>";
					$account_display .= sprintf("<th>%s</th>",$label_location);
					$account_display .= sprintf("<th>%s</th>",$label_date);
					$account_display .= sprintf("<th>%s</th>",$label_table);
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


	$account_display .= "<div class=\"row bcoem-account-info\">";
	$account_display .= sprintf("<div class=\"".$display_left_cols."\"><strong>Steward</strong></div>",$label_steward);
	$account_display .= "<div class=\"".$display_right_cols."\">";
	if ((!empty($_SESSION['brewerSteward'])) && ($action != "print")) $account_display .= yes_no($_SESSION['brewerSteward'],$base_url);
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
						$account_display .= sprintf("<th>%s/%s</th>",$label_yes,$label_no);;
						$account_display .= sprintf("<th>%s</th>",$label_location);
						$account_display .= sprintf("<th>%s</th>",$label_date);
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
					$account_display .= sprintf("<th>%s</th>",$label_location);
					$account_display .= sprintf("<th>%s</th>",$label_date);
					$account_display .= sprintf("<th>%s</th>",$label_table);
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
		if (!empty($row_brewer['brewerJudgeWaiver'])) $account_display .= yes_no($row_brewer['brewerJudgeWaiver'],$base_url);
		else $account_display .= $label_none_entered;
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


