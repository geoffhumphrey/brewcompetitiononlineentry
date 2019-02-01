<?php
// Set up variables

include (DB.'styles.db.php');
if ($_SESSION['prefsStyleSet'] == "BA") include (INCLUDES.'ba_constants.inc.php');

$header1_1 = "";
$header1_2 = "";
$sidebar_extension = "";
$sidebar_paid_entries = "";
$sidebar_unpaid_entries = "";
$tbody_rows = "";
$copy_paste_all_emails = array();
$copy_paste_paid_emails = array();
$copy_paste_unpaid_emails = array();

// If Archive, use archive preference
if ($dbTable == "default") $pro_edition = $_SESSION['prefsProEdition'];
else $pro_edition = $row_archive_prefs['archiveProEdition'];

if ($pro_edition == 0) $edition = $label_amateur." ".$label_edition;
if ($pro_edition == 1) $edition = $label_pro." ".$label_edition;

if ($dbTable != "default") $header1_1 .= "<p>".$edition."</p>";

if ($view == "paid") $header1_1 = "Paid ";
if ($view == "unpaid") $header1_1 = "Unpaid ";
if ($dbTable != "default") $header1_2 = " (Archive ".get_suffix($dbTable).")";

$header = $_SESSION['contestName'].": ";
if ($view == "paid") $header .= "Paid";
elseif ($view == "unpaid") $header .=  "Unpaid";
else $header .=  "All";
$header .= " Entries ".$header1_2;

if (($filter == "default") && ($bid == "default") && ($view == "default")) $entries_unconfirmed = ($totalRows_entry_count - $totalRows_log_confirmed); else $entries_unconfirmed = ($totalRows_log - $totalRows_log_confirmed);
if ($filter != "default") $sidebar_extension .= " in this Category";
if ($bid != "default") $sidebar_extension .= " for this Participant";

if (($filter == "default") && ($bid == "default")) {
  	if ($totalRows_log_paid > 0) $sidebar_paid_entries .= "<a href=\"index.php?section=".$section."&amp;go=".$go."&amp;view=paid\" title=\"View All Paid Entries\">".$totalRows_log_paid."</a>";
 	else $sidebar_paid_entries .= $totalRows_log_paid;

	if (($totalRows_entry_count - $totalRows_log_paid) > 0) $sidebar_unpaid_entries .= "<a href=\"index.php?section=".$section."&amp;go=".$go."&amp;view=unpaid\" title=\"View All Unpaid Entries\">".($totalRows_entry_count - $totalRows_log_paid)."</a>";
	else $sidebar_unpaid_entries .=  ($totalRows_entry_count - $totalRows_log_paid);
	}
else {
	$sidebar_paid_entries .= $totalRows_log_paid." (".$currency_symbol.$total_fees_paid.")";
	$sidebar_unpaid_entries .= ($totalRows_log_confirmed - $totalRows_log_paid);
}
// Build table body and associated arrays

do {

	if ($dbTable == "default") $brewer_info_filter = "default";
	else $brewer_info_filter = get_suffix($dbTable);
	$brewer_info = brewer_info($row_log['brewBrewerID'],$brewer_info_filter);
	$brewer_info = explode("^",$brewer_info);

	$brewer_pro_am = pro_am_check($row_log['brewBrewerID']);

	$styleConvert = style_convert($row_log['brewCategorySort'], 1);
	if ($_SESSION['prefsStyleSet'] == "BA") $entry_style = "";
	else $entry_style = $row_log['brewCategorySort']."-".$row_log['brewSubCategory'];

	$entry_style_display = "";
	$entry_brewer_display = "";
	$entry_updated_display = "";
	$entry_judging_num_display = "";
	$entry_paid_display = "";
	$entry_received_display = "";
	$entry_box_num_display = "";
	$entry_actions = "";
	$entry_unconfirmed_row = "";
	$entry_allergen_row = "";
	$entry_judging_num = "";
	$entry_judging_num_hidden = "";
	$required_info = "";
	$entry_judging_num = "";
	$entry_judging_num_display = "";
	$entry_admin_notes_display = "";
	$entry_staff_notes_display = "";
	$entry_allergens_display = "";

	$entry_number = sprintf("%04s",$row_log['id']);
	$judging_number = sprintf("%06s",$row_log['brewJudgingNumber']);

	// Check whether scoresheet file exists, and, if so, provide link.
	$scoresheet_file_name_entry = sprintf("%06s",$entry_number).".pdf";
	$scoresheet_file_name_judging = $judging_number.".pdf"; // upon upload via the UI, filename is converted to lowercase

	if ($dbTable == "default") {
		$scoresheetfile_entry = USER_DOCS.$scoresheet_file_name_entry;
		$scoresheetfile_judging = USER_DOCS.$scoresheet_file_name_judging;
		$scoresheet_prefs = $_SESSION['prefsDisplaySpecial'];
	}

	else {
		$scoresheetfile_entry = USER_DOCS.DIRECTORY_SEPARATOR.get_suffix($dbTable).DIRECTORY_SEPARATOR.$scoresheet_file_name_entry;
		$scoresheetfile_judging = USER_DOCS.DIRECTORY_SEPARATOR.get_suffix($dbTable).DIRECTORY_SEPARATOR.$scoresheet_file_name_judging;
		$scoresheet_prefs = $row_archive_prefs['archiveScoresheet'];
	}

	$scoresheet = FALSE;
	$scoresheet_entry = FALSE;
	$scoresheet_judging = FALSE;

	if ((file_exists($scoresheetfile_entry)) && ($scoresheet_prefs == "E")) {
		$scoresheet = TRUE;
		$scoresheet_entry = TRUE;
	}

	elseif ((file_exists($scoresheetfile_judging)) && ($scoresheet_prefs == "J")) {
		$scoresheet = TRUE;
		$scoresheet_judging = TRUE;
	}

	$scoresheet_file_name_1 = "";
	$scoresheet_file_name_2 = "";
	if ($scoresheet_entry) $scoresheet_file_name_1 = $scoresheet_file_name_entry;
	if ($scoresheet_judging) $scoresheet_file_name_2 = $scoresheet_file_name_judging;

	if ((!empty($row_log['brewInfo'])) || (!empty($row_log['brewMead1'])) || (!empty($row_log['brewMead2'])) || (!empty($row_log['brewMead3']))) {
		$brewInfo = "";
		//$brewInfo .= "Required Info: ";
		if (!empty($row_log['brewInfo'])) $brewInfo .= str_replace("^", " | ", $row_log['brewInfo']);
		if (!empty($row_log['brewMead1'])) $brewInfo .= "&nbsp;&nbsp;".$row_log['brewMead1'];
		if (!empty($row_log['brewMead2'])) $brewInfo .= "&nbsp;&nbsp;".$row_log['brewMead2'];
		if (!empty($row_log['brewMead3'])) $brewInfo .= "&nbsp;&nbsp;".$row_log['brewMead3'];
		// $required_info .= " <a tabindex=\"0\" role=\"button\" data-toggle=\"popover\" data-placement=\"right\" data-trigger=\"hover focus\" title=\"Required Info\" data-content=\"".$brewInfo."\"><span class=\"fa fa-lg fa-comment\"></span></a>";

		$required_info .= "<p><strong>Req. Info:</strong> ".$brewInfo."</p>";
	}

	if (!empty($row_log['brewInfoOptional'])) {
		// $required_info .= " <a tabindex=\"0\" role=\"button\" data-toggle=\"popover\" data-placement=\"right\" data-trigger=\"hover focus\" title=\"Optional Info\" data-content=\"".$row_log['brewInfoOptional']."\"><span class=\"fa fa-lg fa-comment-o\"></span></a>";
		$required_info .= "<p><strong>Op. Info:</strong> ".$row_log['brewInfoOptional']."</p>";
	}

	if (!empty($row_log['brewPossAllergens'])) {
		$entry_allergens_display .= "<br><strong class=\"text-danger small\">".$label_possible_allergens.": ".$row_log['brewPossAllergens']."</strong>";
		//$required_info .= " <a tabindex=\"0\" role=\"button\" data-toggle=\"popover\" data-placement=\"right\" data-trigger=\"hover focus\" title=\"Possible Allergen(s)\" data-content=\"".$row_log['brewPossAllergens']."\"><span class=\"fa fa-lg fa-medkit\"></span></a>";
		$entry_allergen_row = "bg-warning";
	}

	if (($row_log['brewConfirmed'] == 0) || (empty($row_log['brewConfirmed']))) $entry_unconfirmed_row = "bg-danger";
	elseif (($_SESSION['prefsStyleSet'] != "BA") && ((check_special_ingredients($entry_style,$row_styles['brewStyleVersion']))) && ($row_log['brewInfo'] == "")) $entry_unconfirmed_row = "bg-warning";

	if (isset($row_log['brewJudgingNumber'])) {
		$entry_judging_num_hidden .= "<span class=\"hidden visible-print-inline\">".$judging_number."</span>";
		$entry_judging_num .= $judging_number;
	}

	if (($action != "print") && ($dbTable == "default")) $entry_judging_num_display .= "<input class=\"form-control input-sm hidden-print\" id=\"brewJudgingNumber\" name=\"brewJudgingNumber".$row_log['id']."\" type=\"text\" pattern=\".{6,}\" title=\"Judging numbers must be six characters and cannot include the ^ character. The ^ character will be converted to a dash (-) upon submit. Use leading zeroes (e.g., 000123 or 01-001, etc.)\" size=\"8\" maxlength=\"6\" value=\"".$entry_judging_num."\" /> ".$entry_judging_num_hidden;
	else $entry_judging_num_display = $entry_judging_num;

	/*
	$splitter[0] = substr($row_log['brewJudgingNumber'], 0, 2);
	$splitter[1] = substr($row_log['brewJudgingNumber'], 2);
	$add_one = $splitter[1] + 1;
	$entry_judging_num_display .= "<br>".$splitter[0].$add_one;


	$splitter = explode("-",$row_log['brewJudgingNumber']);
			$add_one = $splitter[1] + 1;
			$entry_judging_num_display .= "<br>".sprintf("%02s",$splitter[0])."-".sprintf("%04s",$add_one);
*/
	// Entry Style
	if ($_SESSION['prefsStyleSet'] == "BA") {
		if ($row_log['brewCategory'] <= 14) $entry_style_display .= $ba_category_names[$row_log['brewCategory']].": ".$row_log['brewStyle'];
		else $entry_style_display .= "Custom: ".$row_log['brewStyle'];
	}

	else {
		if ((!empty($row_log['brewCategorySort'])) && ($filter == "default") && ($bid == "default") && ($dbTable == "default"))
		$entry_style_display .= "<a href=\"".$base_url."index.php?section=admin&amp;go=entries&amp;filter=".$row_log['brewCategorySort']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"See only the ".$styleConvert." entries\" >";
		if ((!empty($row_log['brewCategorySort'])) && ($row_log['brewCategorySort'] != "00")) $entry_style_display .= $row_log['brewCategorySort'].$row_log['brewSubCategory'].": ".$row_log['brewStyle'];
		else $entry_style_display .= "<span class=\"text-danger\"><strong>Style NOT Specified</strong></span>";
		if ((!empty($row_log['brewCategorySort'])) && ($filter == "default") && ($bid == "default") && ($dbTable == "default")) $entry_style_display .= "</a>";
	}

	// Brewer Info
	if (($brewer_info[0] != "") && ($brewer_info[1] != "") && ($pro_edition == 0)) {
		if (($bid == "default") && ($dbTable == "default")) {
			$entry_brewer_display .= "<a href=\"".$base_url."index.php?section=admin&amp;go=entries&amp;bid=".$row_log['brewBrewerID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"See only ".$brewer_info[0]." ".$brewer_info[1]."&rsquo;s entries\">";
			}
		$entry_brewer_display .=  $brewer_info[1].", ".$brewer_info[0];
		if (($bid == "default") && ($dbTable == "default")) $entry_brewer_display .= "</a>";
		$entry_brewer_display .=  "<br>".$brewer_info[11].", ".$brewer_info[12];
	 }
	 elseif (($brewer_info[15] != "&nbsp;") && ($pro_edition == 1)) {
		if (($bid == "default") && ($dbTable == "default")) {
			$entry_brewer_display .= "<a href=\"".$base_url."index.php?section=admin&amp;go=entries&amp;bid=".$row_log['brewBrewerID']."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"See only ".$brewer_info[15]."&rsquo;s entries\">";
			}
		$entry_brewer_display .=  $brewer_info[15];
		if (($bid == "default") && ($dbTable == "default")) $entry_brewer_display .= "</a>";
		$entry_brewer_display .=  "<br>".$brewer_info[11].", ".$brewer_info[12];
	 }
	else $entry_brewer_display .= "&nbsp;";

	// Updated
	if ($row_log['brewUpdated'] != "") $entry_updated_display .= "<span class=\"hidden\">".strtotime($row_log['brewUpdated'])."</span>".getTimeZoneDateTime($_SESSION['prefsTimeZone'], strtotime($row_log['brewUpdated']), $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time-no-gmt");
	else $entry_updated_display .= "&nbsp;";

	// Paid
	if (($action != "print") && ($dbTable == "default")) {
		$entry_paid_display .= "<div class=\"checkbox\"><label>";
		$entry_paid_display .= "<input id=\"brewPaid\" name=\"brewPaid".$row_log['id']."\" type=\"checkbox\" value=\"1\"";
		if ($row_log['brewPaid'] == "1") $entry_paid_display .= "checked>";
		else $entry_paid_display .= ">";
		$entry_paid_display .= "<span class=\"visible-xs-inline visible-sm-inline\">Received</span>";
		$entry_paid_display .= "</label></div>";
		$entry_paid_display .= "<span class=\"hidden\">".$row_log['brewPaid']."</span>";
		if ($brewer_info[9] == "Y") $entry_paid_display .= "&nbsp;<a tabindex=\"0\" role=\"button\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"This entry has been discounted to ".$currency_symbol.number_format($_SESSION['contestEntryFeePasswordNum'], 2).".\"><span class=\"fa fa-lg fa-star\"></span></a>";
	}

	else {
		if ($row_log['brewPaid'] == "1") $entry_paid_display .= "<span class=\"fa fa-lg fa-check text-success\"></span>";
		else $entry_paid_display .= "<span class=\"fa fa-lg fa-times text-danger\"></span>";
	}

	// Received
	if (($action != "print") && ($dbTable == "default")) {
		$entry_received_display .= "<div class=\"checkbox\"><label><input id=\"brewReceived\" name=\"brewReceived".$row_log['id']."\" type=\"checkbox\" value=\"1\"";
		if ($row_log['brewReceived'] == "1") $entry_received_display .= "checked>";
		else $entry_received_display .= ">";
		$entry_received_display .= "<span class=\"visible-xs-inline visible-sm-inline\">Paid</span>";
		$entry_received_display .= "</label></div>";
		$entry_received_display .= "<span class=\"hidden\">".$row_log['brewReceived']."</span>";
	}

	else {
		if ($row_log['brewReceived'] == "1") $entry_received_display .= "<span class=\"fa fa-lg fa-check text-success\"></span>";
		else $entry_received_display .= "<span class=\"fa fa-lg fa-times text-danger\"></span>";
	}

	// Box Number
	if (($action != "print") && ($dbTable == "default")) {
		$entry_box_num_display .= "<span class=\"visible-sm-inline visible-xs-inline\">Box: </span><input class=\"form-control input-sm hidden-print\" id=\"brewBoxNum\" name=\"brewBoxNum".$row_log['id']."\" type=\"text\" size=\"5\" maxlength=\"10\" value=\"".$row_log['brewBoxNum']."\" />";
		$entry_box_num_display .= "<span class=\"hidden visible-print-inline\">".$row_log['brewBoxNum']."</span>";
	}
	else $entry_box_num_display = $row_log['brewBoxNum'];

	// Notes to Staff
	if (($action != "print") && ($dbTable == "default")) {
		//$entry_staff_notes_display .= "<input class=\"form-control input-sm hidden-print\" id=\"brewStaffNotes\" name=\"brewStaffNotes".$row_log['id']."\" type=\"text\" size=\"20\" maxlength=\"255\" placeholder=\"\" value=\"".$row_log['brewStaffNotes']."\" />";

		$entry_staff_notes_display .= "<span class=\"visible-sm-inline visible-xs-inline\">Staff Notes: </span><textarea class=\"form-control input-sm hidden-print\" id=\"brewStaffNotes\" name=\"brewStaffNotes".$row_log['id']."\" rows=\"2\" maxlength=\"255\" placeholder=\"\" />".$row_log['brewStaffNotes']."</textarea>";
		$entry_staff_notes_display.= "<span class=\"hidden visible-print-inline\">".$row_log['brewStaffNotes']."</span>";
	}
	else $entry_staff_notes_display = $row_log['brewStaffNotes'];

	// Notes to Admin
	if (($action != "print") && ($dbTable == "default")) {
		// $entry_admin_notes_display .= "<input class=\"form-control input-sm hidden-print\" id=\"brewAdminNotes\" name=\"brewAdminNotes".$row_log['id']."\" type=\"text\" size=\"10\" maxlength=\"255\" placeholder=\"\" value=\"".$row_log['brewAdminNotes']."\" />";

		$entry_admin_notes_display .= "<span class=\"visible-sm-inline visible-xs-inline\">Admin Notes: </span><textarea class=\"form-control input-sm hidden-print\" id=\"brewAdminNotes\" name=\"brewAdminNotes".$row_log['id']."\" rows=\"2\" maxlength=\"255\" placeholder=\"\" />".$row_log['brewAdminNotes']."</textarea>";
		$entry_admin_notes_display.= "<span class=\"hidden visible-print-inline\">".$row_log['brewAdminNotes']."</span>";
	}
	else $entry_admin_notes_display = $row_log['brewAdminNotes'];

	if (($action != "print") && ($dbTable == "default")) {
		$entry_actions .= "<a href=\"".$base_url."index.php?section=brew&amp;go=".$go."&amp;action=edit&amp;bid=".$brewer_info[7]."&amp;id=".$row_log['id'];
		if ($row_log['brewConfirmed'] == 0) $entry_actions .= "&amp;msg=1-".$row_log['brewCategorySort']."-".$row_log['brewSubCategory'];
		else $entry_actions .= "&amp;view=".$row_log['brewCategorySort']."-".$row_log['brewSubCategory'];
		$entry_actions .= "\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit &ldquo;".$row_log['brewName']."&rdquo;\">";
		$entry_actions .= "<span class=\"fa fa-lg fa-pencil\"></span>";
		$entry_actions .= "</a> ";
		$entry_actions .= "<a class=\"hide-loader\" href=\"".$base_url."includes/process.inc.php?section=".$section."&amp;go=".$go."&amp;filter=".$filter."&amp;dbTable=".$brewing_db_table."&amp;action=delete&amp;id=".$row_log['id']."\" data-toggle=\"tooltip\" title=\"Delete &ldquo;".$row_log['brewName']."&rdquo;\" data-confirm=\"Are you sure you want to delete the entry called &ldquo;".$row_log['brewName']."?&rdquo; This cannot be undone.\"><span class=\"fa fa-lg fa-trash-o\"></a> ";
		$entry_actions .= "<a id=\"modal_window_link\" class=\"hide-loader\" href=\"".$base_url."output/entry.output.php?id=".$row_log['id']."&amp;bid=".$brewer_info[7]."&amp;filter=admin\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Print the Entry Forms for &ldquo;".$row_log['brewName']."&rdquo;\"><span class=\"fa fa-lg fa-print hidden-xs hidden-sm\"></a> ";
		$entry_actions .= "<a class=\"hide-loader\" href=\"mailto:".$brewer_info[6]."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Email the entry&rsquo;s owner, ".$brewer_info[0]." ".$brewer_info[1].", at ".$brewer_info[6]."\"><span class=\"fa fa-lg fa-envelope\"></span></a> ";
	}

	$scoresheet_link_1 = "";
	$scoresheet_link_2 = "";

	if (($scoresheet) && ($action != "print")) {

		if ((!empty($scoresheet_file_name_1)) && ($scoresheet_entry)) {

			// The pseudo-random number and the corresponding name of the temporary file are defined each time
			// this brewer_entries.sec.php script is accessed (or refreshed), but the temporary file is created
			// only when the entrant clicks on the gavel icon to access the scoresheet.
			$random_num_str_1 = random_generator(8,2);
			$random_file_name_1 = $random_num_str_1.".pdf";
			$scoresheet_random_file_relative_1 = "user_temp/".$random_file_name_1;
			$scoresheet_random_file_1 = USER_TEMP.$random_file_name_1;
			$scoresheet_random_file_html_1 = $base_url.$scoresheet_random_file_relative_1;
			$scoresheet_link_1 .= "<a class=\"hide-loader\" href=\"".$base_url."output/scoresheets.output.php?";

			// Obfuscate the *ACTUAL* file names.
			// Prevents casual users from right clicking on scoresheet download link and changing
			// the entry or judging number pdf name passed via the URL to force downloads of files
			// they shouldn't have access to. Can I get a harumph?!

			/*
			if (function_exists('openssl_encrypt')) {
				$scoresheet_link_1 .= "scoresheetfilename=".obfuscateURL($scoresheet_file_name_1);
				$scoresheet_link_1 .= "&amp;randomfilename=".obfuscateURL($random_file_name_1)."&amp;download=true";
			}
			*/


			$scoresheet_link_1 .= "scoresheetfilename=".urlencode(obfuscateURL($scoresheet_file_name_1,$encryption_key));
			$scoresheet_link_1 .= "&amp;randomfilename=".urlencode(obfuscateURL($random_file_name_1,$encryption_key))."&amp;download=true";

			if ($dbTable != "default") $scoresheet_link_1 .= "&amp;view=".get_suffix($dbTable);
			$scoresheet_link_1 .= sprintf("\" data-toggle=\"tooltip\" title=\"%s '".$row_log['brewName']."'' (by Entry Number).\">",$brewer_entries_text_006);
			$scoresheet_link_1 .= "<span class=\"fa fa-lg fa-gavel\"></a>&nbsp;&nbsp;";
		}

		if ((!empty($scoresheet_file_name_2)) && ($scoresheet_judging)) {

			// The pseudo-random number and the corresponding name of the temporary file are defined each time
			// this brewer_entries.sec.php script is accessed (or refreshed), but the temporary file is created
			// only when the entrant clicks on the gavel icon to access the scoresheet.

			$random_num_str_2 = random_generator(8,2);
			$random_file_name_2 = $random_num_str_2.".pdf";
			$scoresheet_random_file_relative_2 = "user_temp/".$random_file_name_2;
			$scoresheet_random_file_2 = USER_TEMP.$random_file_name_2;
			$scoresheet_random_file_html_2 = $base_url.$scoresheet_random_file_relative_2;

			$scoresheet_link_2 .= "<a class=\"hide-loader\" href=\"".$base_url."output/scoresheets.output.php?";

			// Obfuscate the *ACTUAL* file names.
			// Prevents casual users from right clicking on scoresheet download link and changing
			// the entry or judging number pdf name passed via the URL to force downloads of files
			// they shouldn't have access to. Can I get a harumph?!
			$scoresheet_link_2 .= "scoresheetfilename=".urlencode(obfuscateURL($scoresheet_file_name_2,$encryption_key));
			$scoresheet_link_2 .= "&amp;randomfilename=".urlencode(obfuscateURL($random_file_name_2,$encryption_key))."&amp;download=true";
			if ($dbTable != "default") $scoresheet_link_2 .= "&amp;view=".get_suffix($dbTable);
			$scoresheet_link_2 .= sprintf("\" data-toggle=\"tooltip\" title=\"%s '".$row_log['brewName']."' (by Judging Number).\">",$brewer_entries_text_006);
			$scoresheet_link_2 .= "<span class=\"fa fa-lg fa-gavel\"></a>&nbsp;&nbsp;";
		}

		// Clean up temporary scoresheets created for other brewers, when they are at least 1 minute old (just to avoid problems when two entrants try accessing their scoresheets at practically the same time, and clean up previously created scoresheets for the same brewer, regardless of how old they are.
		$tempfiles = array_diff(scandir(USER_TEMP), array('..', '.'));

		if (is_array($tempfiles)) {
			foreach ($tempfiles as $file) {
				if ((filectime(USER_TEMP.$file) < time() - 1*60) || ((strpos($file, $scoresheet_file_name_judging) !== FALSE))) {
					unlink(USER_TEMP.$file);
				}

				if ((filectime(USER_TEMP.$file) < time() - 1*60) || ((strpos($file, $scoresheet_file_name_entry) !== FALSE))) {
					unlink(USER_TEMP.$file);
				}
			}
		}

		if ((($dbTable == "default") && ($_SESSION['prefsDisplaySpecial'] == "E")) || ($dbTable != "default")) $entry_actions .= $scoresheet_link_1;
		if ((($dbTable == "default") && ($_SESSION['prefsDisplaySpecial'] == "J")) || ($dbTable != "default")) $entry_actions .= $scoresheet_link_2;
	}

	if ((empty($entry_allergen_row)) && (!empty($entry_unconfirmed_row))) $entry_row_color = $entry_unconfirmed_row;
	elseif ((!empty($entry_allergen_row)) && (empty($entry_unconfirmed_row))) $entry_row_color = $entry_allergen_row;
	elseif ((!empty($entry_allergen_row)) && (!empty($entry_unconfirmed_row))) $entry_row_color = $entry_unconfirmed_row;
	else $entry_row_color = "";

	$tbody_rows .= "<tr class=\"".$entry_row_color."\">";
	$tbody_rows .= "<input type=\"hidden\" name=\"id[]\" value=\"".$row_log['id']."\" />";
	$tbody_rows .= "<td>".sprintf("%04s",$row_log['id'])."</td>";
	$tbody_rows .= "<td nowrap=\"nowrap\">".$entry_judging_num_display."</td>";
	$tbody_rows .= "<td class=\"hidden-xs hidden-sm hidden-md\">";
	$tbody_rows .= $row_log['brewName'];
	$tbody_rows .= "</td>";
	$tbody_rows .= "<td>";

	$tbody_rows .= "<span class=\"hidden\">".$row_log['brewCategorySort'].$row_log['brewSubCategory']."</span>";

	if ((!empty($entry_unconfirmed_row)) || (!empty($entry_allergen_row))) {

		if (!empty($entry_unconfirmed_row)) $tbody_rows .= "<a href=\"".$base_url."index.php?section=brew&amp;go=".$go."&amp;bid=".$brewer_info[7]."&amp;action=edit&amp;id=".$row_log['id']."&amp;view=".$row_log['brewCategory']."-".$row_log['brewSubCategory']."\" data-toggle=\"tooltip\" title=\"Unconfirmed Entry - Click to Edit\">";
		$tbody_rows .= "<span class=\"fa fa-lg fa-exclamation-triangle text-danger\"></span>";

		if (!empty($entry_unconfirmed_row)) $tbody_rows .= "</a>";
		$tbody_rows .= " ";
	}

	if (!empty($required_info)) $tbody_rows .= " <a role=\"button\" data-toggle=\"collapse\" data-target=\"#collapseEntryInfo".$row_log['id']."\" aria-expanded=\"false\" aria-controls=\"collapseEntryInfo".$row_log['id']."\"><span class=\"fa fa-lg fa-info-circle hidden-xs hidden-sm\"></span></a> ";

	// $tbody_rows .= " <a class=\"visible-xs-inline visible-sm-inline\" role=\"button\" data-toggle=\"collapse\" data-target=\"#collapseAdminMenu".$row_log['id']."\" aria-expanded=\"false\" aria-controls=\"collapseAdminMenu".$row_log['id']."\"><span class=\"fa fa-lg fa-cog\"></span></a> ";

	$tbody_rows .= $entry_style_display;
	$tbody_rows .= $entry_allergens_display;

	if (!empty($required_info)) $tbody_rows .= "<div class=\"visible-xs visible-sm\" style=\"margin: 5px 0 5px 0\"><button class=\"btn btn-primary btn-block btn-xs\" type=\"button\" data-toggle=\"collapse\" data-target=\"#collapseEntryInfo".$row_log['id']."\" aria-expanded=\"false\" aria-controls=\"collapseEntryInfo".$row_log['id']."\">Entry Info <span class=\"fa fa-lg fa-info-circle\"></span></button></div>";

	$tbody_rows .= "<div class=\"collapse small well\" id=\"collapseEntryInfo".$row_log['id']."\">";
    $tbody_rows .= $required_info;
    $tbody_rows .= "</div>";

    $tbody_rows .= "<section class=\"visible-sm visible-xs\">";
	$tbody_rows .= "<div style=\"margin: 5px 0 5px 0\"><button class=\"btn btn-default btn-block btn-xs\" type=\"button\" data-toggle=\"collapse\" data-target=\"#collapseAdminMenu".$row_log['id']."\" aria-expanded=\"false\" aria-controls=\"collapseAdminMenu".$row_log['id']."\">Admin Info <span class=\"fa fa-lg fa-info-circle\"></span></button></div>";

    $tbody_rows .= "<div class=\"collapse small well\" id=\"collapseAdminMenu".$row_log['id']."\">";
    $tbody_rows .= "<p><strong>".$label_brewer.": </strong>".$entry_brewer_display."</p>";
    $tbody_rows .= "<p><strong>".$label_paid.":</strong> ".yes_no($row_log['brewPaid'],$base_url)."</p>";
    $tbody_rows .= "<p><strong>".$label_received.":</strong> ".yes_no($row_log['brewReceived'],$base_url)."</p>";
    if (!empty($row_log['brewAdminNotes'])) $tbody_rows .= "<p><strong>".$label_admin." ".$label_notes.":</strong> ".$row_log['brewAdminNotes']."</p>";
    if (!empty($row_log['brewStaffNotes'])) $tbody_rows .= "<p><strong>".$label_staff." ".$label_notes.":</strong> ".$row_log['brewStaffNotes']."</p>";
    if (!empty($row_log['brewBoxNum'])) $tbody_rows .= "<p><strong>".$label_box."/".$label_location.":</strong> ".$row_log['brewBoxNum']."</p>";
    $tbody_rows .= "<p><strong>Actions:</strong> ".$entry_actions."</p>";
    $tbody_rows .= "</div>";
    $tbody_rows .= "</section>";


	// $tbody_rows .= $required_info;

	if ($brewer_pro_am == 1) $tbody_rows .= "<p><span class=\"label label-info hidden-xs hidden-sm\">NOT PRO-AM ELIGIBLE</span><span class=\"label label-info visible-xs visible-sm\">NO PRO-AM</span></p>";
	$tbody_rows .= "</td>";
	$tbody_rows .= "<td nowrap=\"nowrap\" class=\"hidden-xs hidden-sm\">".$entry_brewer_display."</td>";
	if ($pro_edition == 0) $tbody_rows .= "<td class=\"hidden-xs hidden-sm hidden-md hidden-print\">".$brewer_info[8]."</td>";
	$tbody_rows .= "<td class=\"hidden-xs hidden-sm hidden-md hidden-print\">".$entry_updated_display."</td>";
	$tbody_rows .= "<td class=\"hidden-xs hidden-sm\">".$entry_paid_display."</td>";
	$tbody_rows .= "<td class=\"hidden-xs hidden-sm\">".$entry_received_display."</td>";
	$tbody_rows .= "<td class=\"hidden-xs hidden-sm hidden-md \">".$entry_admin_notes_display."</td>";
	$tbody_rows .= "<td class=\"hidden-xs hidden-sm hidden-md \">".$entry_staff_notes_display."</td>";
	$tbody_rows .= "<td class=\"hidden-xs hidden-sm\">".$entry_box_num_display."</td>";
	if ($action != "print") $tbody_rows .= "<td class=\"hidden-xs hidden-sm\">".$entry_actions."</td>";
	$tbody_rows .= "</tr>";

	// Build all brewer email array
	if (!empty($brewer_info[6])) $copy_paste_all_emails[] = $brewer_info[6];

} while($row_log = mysqli_fetch_assoc($log));

do {
	if ($dbTable == "default") $brewer_info_filter = "default";
	else $brewer_info_filter = get_suffix($dbTable);
	$brewer_info = brewer_info($row_log_paid['brewBrewerID'],$brewer_info_filter);
	$brewer_info = explode("^",$brewer_info);
	if (!empty($brewer_info[6])) $copy_paste_paid_emails[] = $brewer_info[6];
} while ($row_log_paid = mysqli_fetch_assoc($log_paid));

// Unpaid email addresses

$query_log_unpaid = sprintf("SELECT a.brewerEmail, a.uid, b.brewBrewerID FROM %s a, %s b WHERE a.uid = b.brewBrewerID AND (b.brewPaid <> 1 OR b.brewPaid IS NULL)", $prefix."brewer", $prefix."brewing");
$log_unpaid = mysqli_query($connection,$query_log_unpaid) or die (mysqli_error($connection));
$row_log_unpaid = mysqli_fetch_assoc($log_unpaid);
$totalRows_log_unpaid = mysqli_num_rows($log_unpaid);

if ($totalRows_log_unpaid > 0) {
	do {
		$copy_paste_unpaid_emails [] = $row_log_unpaid['brewerEmail'];
	} while ($row_log_unpaid = mysqli_fetch_assoc($log_unpaid));
}

if ($action != "print") { ?>
	<?php
    if (($dbTable == "default") && ($totalRows_entry_count > $_SESSION['prefsRecordLimit']))	{
        $of = $start + $totalRows_log;
        echo "<div id=\"sortable_info\" class=\"dataTables_info\">Showing ".$start_display." to ".$of;
        if ($bid != "default") echo " of ".$totalRows_log." entries</div>";
        if ($bid == "default") echo " of ".$totalRows_entry_count." entries</div>";
        }
    ?>
	<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			<?php if (($totalRows_entry_count <= $_SESSION['prefsRecordLimit']) || ((($section == "admin") && ($go == "entries") && ($filter == "default")  && ($dbTable != "default")))) { ?>
			"bPaginate" : true,
			"sPaginationType" : "full_numbers",
			"bLengthChange" : false,
			"iDisplayLength" :  <?php echo round($_SESSION['prefsRecordPaging']); ?>,
			"sDom": 'fprtp',
			"bStateSave" : false,
			<?php } else { ?>
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			<?php } ?>
			"aaSorting": [[3,'asc']],
			"bProcessing" : true,
			"aoColumns": [
				null,
				null,
				null,
				null,
				null,
				<?php if ($pro_edition == 0) { ?>null,<?php } ?>
				null,
				null,
				null,
				null,
				null,
				null,
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
	<?php } ?>
	<?php if ($action == "print") { ?>
	<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave": false,
			"bLengthChange" : false,

			<?php if ($psort == "entry_number") { ?>"aaSorting": [[0,'asc']],<?php } ?>
			<?php if ($psort == "judging_number") { ?>"aaSorting": [[1,'asc']],<?php } ?>
			<?php if ($psort == "entry_name") { ?>"aaSorting": [[2,'asc']],<?php } ?>
			<?php if ($psort == "category") { ?>"aaSorting": [[3,'asc']],<?php } ?>
			<?php if ($psort == "brewer_name") { ?>"aaSorting": [[4,'asc']],<?php } ?>

			"aoColumns": [
				null,
				null,
				null,
				null,
				null,
				null,
				null,
				null,
				null,
				null,
				null<?php if ($pro_edition == 0) { ?>,
				null<?php } ?>
				]
			} );
		} );
	</script>
<?php } ?>
<?php if ($action == "print") { ?>
<h1><?php echo $header; ?></h1>
<?php } else { ?>
<p class="lead"><?php echo $header; ?></p>
<?php } ?>
<form name="form1" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?action=update&amp;dbTable=<?php echo $brewing_db_table; ?>&amp;filter=<?php echo $filter; ?>">
<?php if ($action != "print") { ?>
<div class="bcoem-admin-element hidden-print row">
	<div class="col-md-12">
		<?php if ($dbTable != "default") { ?>
		<div class="btn-group" role="group" aria-label="...">
			<a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=archive"><span class="fa fa-arrow-circle-left"></span> Archives</a>
		</div><!-- ./button group -->
		<?php } ?>
		<?php if ($dbTable == "default") { ?>
		<?php if (($filter != "default") || ($bid != "default") || ($view != "default")) { ?>
		<div class="btn-group" role="group" aria-label="allEntriesNav">
			<a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries"><span class="fa fa-arrow-circle-left"></span> <?php if ($filter != "default") echo "All Styles"; if ($bid != "default") echo "All Entries"; if ($view != "default") echo "All Entries"; ?></a>
		</div><!-- ./button group -->
		<?php } // end if (($filter != "default") || ($bid != "default") || ($view != "default")) ?>
		<?php if ($totalRows_log > 0) { ?>
		<!-- View Entries Dropdown -->
		<div class="btn-group" role="group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<span class="fa fa-eye"></span> View...
			<span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<?php if ($view != "default") { ?>
				<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries">All Entries</a></li>
				<?php } ?>
				<?php if ($view != "paid") {  ?>
				<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries&amp;view=paid">Paid Entries</a><li>
				<?php } ?>
				<?php if (($view != "unpaid") && ($totalRows_log_paid < $row_total_count['count'])) { ?>
				<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries&amp;view=unpaid">Unpaid Entries</a><li>
				<?php } ?>
			</ul>
		</div><!-- ./button group -->
		<?php } ?>
		<div class="btn-group" role="group" aria-label="chooseParticipants">
			<?php echo participant_choose($brewer_db_table,$pro_edition); ?>
		</div><!-- ./button group -->
		<?php if ($totalRows_log > 0) { ?>
		<div class="btn-group hidden-xs hidden-sm" role="group" aria-label="printCurrent">
			<div class="btn-group" role="group">
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<span class="fa fa-print"></span> Print Current View...
				<span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
					<li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=admin&amp;go=entries&amp;action=print&amp;psort=entry_number">By Entry Number</a></li>
					<li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=admin&amp;go=entries&amp;action=print&amp;psort=judging_number">By Judging Number</a></li>
					<li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=admin&amp;go=entries&amp;action=print&amp;psort=category">By Style</a></li>
					<li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=admin&amp;go=entries&amp;action=print&amp;psort=brewer_name"><?php if ($pro_edition == 0) echo "By Brewer Last Name"; else echo "By Organization Name"; ?></a></li>
					<li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=admin&amp;go=entries&amp;action=print&amp;psort=entry_name">By Entry Name</a></li>
				</ul>
			</div>
			<?php if (($totalRows_entry_count > $limit) && ($filter == "default")) { ?>
				<div class="btn-group" role="group">
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<span class="fa fa-print"></span> Print All...
				<span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
					<li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=admin&amp;go=entries&amp;action=print&amp;view=all&amp;psort=entry_number">By Entry Number</a></li>
					<li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=admin&amp;go=entries&amp;action=print&amp;view=all&amp;psort=judging_number">By Judging Number</a></li>
					<li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=admin&amp;go=entries&amp;action=print&amp;view=all&amp;psort=category">By Style</a></li>
					<li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=admin&amp;go=entries&amp;action=print&amp;view=all&amp;psort=brewer_name">By Brewer Last Name</a></li>
					<li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>output/print.output.php?section=admin&amp;go=entries&amp;action=print&amp;view=all&amp;psort=entry_name">By Entry Name</a></li>
				</ul>
			</div>
			<?php } ?>
		</div><!-- ./button group -->
		<?php } ?>
		<?php if (($filter == "default") && ($bid == "default") && ($view == "default") && ($totalRows_log > 0)) { ?>
		<div class="btn-group" role="group" aria-label="markEntriesAs">
			<div class="btn-group" role="group">
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<span class="fa fa-check-circle"></span> Admin Actions
				<span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
					<li class="small"><a class="hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?action=paid&amp;dbTable=<?php echo $brewing_db_table; ?>" data-confirm="Are you sure? This will mark ALL entries as paid and could be a large pain to undo.">Mark All as Paid</a></li>
					<li class="small"><a class="hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?action=unpaid&amp;dbTable=<?php echo $brewing_db_table; ?>" data-confirm="Are you sure? This will mark ALL entries as unpaid and could be a large pain to undo.">Un-Mark All as Paid</a></li>
					<li class="small"><a class="hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?action=received&amp;dbTable=<?php echo $brewing_db_table; ?>" data-confirm="Are you sure? This will mark ALL entries as received and could be a large pain to undo.">Mark All as Received</a></li>
					<li class="small"><a class="hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?action=not-received&amp;dbTable=<?php echo $brewing_db_table; ?>" data-confirm="Are you sure? This will mark ALL entries as NOT received and could be a large pain to undo.">Un-Mark All as Received</a></li>
					<li class="small"><a class="hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?action=confirmed&amp;dbTable=<?php echo $brewing_db_table; ?>" data-confirm="Are you sure? This will mark ALL entries as confirmed and could be a large pain to undo.">Confirm All Entries</a></li>
					<li class="small"><a class="hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?action=purge&amp;go=unconfirmed" data-confirm="Are you sure? This will delete ALL unconfirmed entries and/or entries without special ingredients/classic style info that require them from the database - even those that are less than 24 hours old. This cannot be undone.">Purge All Unconfirmed Entries</a></li>
					<li class="small"><a class="hide-loader" data-confirm="Are you sure you want to regenerate judging numbers for all entries? This will over-write all judging numbers, including those that have been assigned via the barcode or QR Code scanning function. The process may take a while depending upon the number of entires in your database." href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=generate_judging_numbers&amp;sort=default">Regenerate Judging Numbers (Random)</a></li>
					<li class="small"><a class="hide-loader" data-confirm="Are you sure you want to regenerate judging numbers for all entries? This will over-write all judging numbers, including those that have been assigned via the barcode or QR Code scanning function. The process may take a while depending upon the number of entires in your database. PLEASE NOTE that judging numbers will be in the following format: XX-123 (where XX is the category number or name)." href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=generate_judging_numbers&amp;sort=legacy">Regenerate Judging Numbers (With Style Number Prefix)</a></li>
					<li class="small"><a class="hide-loader" data-confirm="Are you sure you want to regenerate judging numbers for all entries? This will over-write all judging numbers, including those that have been assigned via the barcode or QR Code scanning function. The process may take a while depending upon the number of entires in your database." href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=generate_judging_numbers&amp;sort=identical">Regenerate Judging Numbers (Same as Entry Numbers)</a></li>
				</ul>
				</ul>
			</div>
		</div><!-- ./button group -->
		<?php } ?>
		<div class="btn-group pull-right hidden-xs hidden-sm" role="group" aria-label="entryStatus">
	        <div class="btn-group" role="group">
	            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#entryStatusModal">
	              <?php if ($view == "paid") echo "Paid"; elseif ($view == "unpaid") echo "Unpaid"; else echo "All" ?> Entry Status
	            </button>
	        </div>
	    </div>
	</div>
</div>
<div class="bcoem-admin-element hidden-print row hidden-xs">
	<?php $all_email_display = implode(", ",array_unique($copy_paste_all_emails));
	if (!empty($all_email_display))	{ ?>
	<div class="col-md-12">
	<!-- All Participant Email Addresses Modal -->
	   <div class="btn-group hidden-xs hidden-sm" role="group" aria-label="...">
			<button type="button" class="btn btn-info" data-toggle="modal" data-target="#allEmailModal">
				  All Participants with Entries Email Addresses
				</button>
		</div><!-- ./button group -->
		<!-- Modal -->
		<div class="modal fade" id="allEmailModal" tabindex="-1" role="dialog" aria-labelledby="allEmailModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header bcoem-admin-modal">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="allEmailModalLabel">All Participants with Entries Email Addresses</h4>
					</div>
					<div class="modal-body">
						<p>Copy and paste the list below into your favorite email program to contact all particpants with entries.</p>
						<textarea class="form-control" rows="8"><?php echo ltrim($all_email_display," ")
						?></textarea>
					</div>
					<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				</div>
				</div>
			</div>
		</div><!-- ./modal -->
		<?php }
		$paid_email_display = implode(", ",array_unique($copy_paste_paid_emails));
		if (!empty($paid_email_display)) { ?>
		<!-- All Participants with Paid Entries Email Addresses Modal -->
		<div class="btn-group hidden-xs hidden-sm" role="group" aria-label="...">
			<button type="button" class="btn btn-info" data-toggle="modal" data-target="#paidEmailModal">
				  All Participants with Paid Entries Email Addresses
				</button>
		</div><!-- ./button group -->
		<!-- Modal -->
		<div class="modal fade" id="paidEmailModal" tabindex="-1" role="dialog" aria-labelledby="paidEmailModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header bcoem-admin-modal">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="paidEmailModalLabel">All Participants with Paid Entries Email Addresses</h4>
					</div>
					<div class="modal-body">
						<p>Copy and paste the list below into your favorite email program to contact particpants with <strong>PAID</strong> entries.</p>
						<textarea class="form-control" rows="8"><?php echo ltrim($paid_email_display); ?></textarea>
					</div>
					<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				</div>
				</div>
			</div>
		</div><!-- ./modal -->
		<?php }
		$unpaid_email_display = implode(", ",array_unique($copy_paste_unpaid_emails));
		if (!empty($unpaid_email_display)) { ?>
		<div class="btn-group hidden-xs hidden-sm" role="group" aria-label="...">
			<button type="button" class="btn btn-info" data-toggle="modal" data-target="#unpaidEmailModal">
				  All Participants with Unpaid Entries Email Addresses
				</button>
		</div><!-- ./button group -->
		<!-- All Participants with Unpaid Entries Email Addresses Modal -->
		<div class="modal fade" id="unpaidEmailModal" tabindex="-1" role="dialog" aria-labelledby="unpaidEmailModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header bcoem-admin-modal">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="unpaidEmailModalLabel">All Participants with Unpaid Entries Email Addresses</h4>
					</div>
					<div class="modal-body">
						<p>Copy and paste the list below into your favorite email program to contact particpants with <strong>UNPAID</strong> entries.</p>
						<textarea class="form-control" rows="8"><?php echo ltrim($unpaid_email_display); ?></textarea>
					</div>
					<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				</div>
				</div>
			</div>
		</div><!-- ./modal -->
		<?php } ?>
		<!-- Entry status modal -->
    <!-- Modal -->
    <div class="modal fade" id="entryStatusModal" tabindex="-1" role="dialog" aria-labelledby="entryStatusModalLabel">
      	<div class="modal-dialog" role="document">
        	<div class="modal-content">
          		<div class="modal-header bcoem-admin-modal">
            		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		<h4 class="modal-title" id="entryStatusModalLabel"><?php if ($view == "paid") echo "Paid"; elseif ($view == "unpaid") echo "Unpaid"; else echo "All" ?> Entry Status</h4>
          		</div>
                <div class="modal-body">
                    <div class="bcoem-sidebar-panel">
                        <strong class="text-info">Confirmed Entries<?php echo $sidebar_extension; ?></strong><span class="pull-right"><?php echo $totalRows_log_confirmed; if ((!empty($row_limits['prefsEntryLimit'])) && ($view == "default")) echo " of ".$row_limits['prefsEntryLimit']; ?></span>
                    </div>
                    <div class="bcoem-sidebar-panel">
                        <strong class="text-info">Unconfirmed Entries<?php echo $sidebar_extension; ?></strong><span class="pull-right"><?php echo $entries_unconfirmed; ?></span>
                    </div>
                    <?php if (($filter == "default") && ($bid == "default") && ($view == "default")) { ?>
                    <div class="bcoem-sidebar-panel">
                        <strong class="text-info">Paid Confirmed Entries<?php echo $sidebar_extension; ?></strong><span class="pull-right"><?php echo $sidebar_paid_entries; ?></span>
                    </div>
                    <div class="bcoem-sidebar-panel">
                        <strong class="text-info">Unpaid Confirmed Entries<?php echo $sidebar_extension; ?></strong><span class="pull-right"><?php echo $sidebar_unpaid_entries; ?></span>
                    </div>
                    <div class="bcoem-sidebar-panel">
                        <strong class="text-info">Received Entries</strong><span class="pull-right"><?php echo $total_nopay_received; ?></span>
                    </div>
                    <div class="bcoem-sidebar-panel">
                        <strong class="text-info">Total Fees<?php echo $sidebar_extension; ?></strong><span class="pull-right"><?php echo $currency_symbol.number_format($total_fees,2); ?></span>
                    </div>
                    <?php } ?>
                    <?php if (($view == "paid") || ($view == "default")) { ?>
                    <div class="bcoem-sidebar-panel">
                        <strong class="text-info">Total Fees Paid<?php echo $sidebar_extension; ?></strong><span class="pull-right"><?php echo $currency_symbol.number_format($total_fees_paid,2); ?></span>
                    </div>
                    <?php } ?>
                    <?php if (($view == "unpaid") || ($view == "default")) { ?>
                    <div class="bcoem-sidebar-panel">
                        <strong class="text-info">Total Fees Unpaid <?php echo $sidebar_extension; ?></strong><span class="pull-right"><?php echo $currency_symbol.number_format($total_fees_unpaid,2); ?></span>
                    </div>
                    <?php } ?>
                </div>
                <div class="modal-footer">
            	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        	</div>
      	</div>
    </div><!-- ./modal -->
	</div>
</div>
    <?php }// end if ($dbTable == "default") ?>

<?php } // end if ($action != "print") ?>

<?php if ($totalRows_log > 0) { ?>
<table class="table table-responsive table-bordered" id="sortable">
<thead>
    <tr>
        <th nowrap>Entry</th>
        <th nowrap>Judging <?php if (($action != "print") &&  ($dbTable == "default")) { ?><a href="#" tabindex="0" role="button" data-toggle="popover" data-trigger="hover" data-placement="auto top" data-container="body" title="Judging Numbers" data-content="Judging numbers are random six-digit numbers that are automatically assigned by the system. You can override each judging number when scanning in barcodes, QR Codes, or by entering it in the field provided."><span class="hidden-xs hidden-sm hidden-md hidden-print fa fa-question-circle"></span></a><?php } ?></th>
        <th class="hidden-xs hidden-sm hidden-md">Name</th>
        <th>Style</th>
        <th class="hidden-xs hidden-sm"><?php if ($pro_edition == 1) echo "Organization"; else echo "Brewer"; ?></th>
        <?php if ($pro_edition == 0) { ?>
        <th class="hidden-xs hidden-sm hidden-md hidden-print">Club</th>
        <?php } ?>
        <th class="hidden-xs hidden-sm hidden-md hidden-print">Updated</th>
        <th class="hidden-xs hidden-sm" width="3%">P<span class="hidden-md">aid?</span></th>
        <th class="hidden-xs hidden-sm" width="3%">R<span class="hidden-md">ec'd?</span></th>
        <th class="hidden-xs hidden-sm hidden-md ">Admin Notes <?php if (($action != "print") &&  ($dbTable == "default")) { ?><a href="#" tabindex="0" role="button" data-toggle="popover" data-trigger="hover" data-placement="auto top" data-container="body" title="Admin Notes" data-content="Catch-all for any information Admins may need for individual entries such as 'partial refund needed', 'maybe mis-categorized', etc. 255 character limit."><span class="hidden-xs hidden-sm hidden-md hidden-print fa fa-question-circle"></span></a><?php } ?></th>
        <th class="hidden-xs hidden-sm hidden-md ">Staff Notes <?php if (($action != "print") &&  ($dbTable == "default")) { ?><a href="#" tabindex="0" role="button" data-toggle="popover" data-trigger="hover" data-placement="auto top" data-container="body" title="Staff Notes" data-content="Catch-all for any information staff may need to know about individual entries such as 'single 750ml bottle', 'missing MBOS bottle', etc. Notes entered here are printed on pullsheets. 255 character limit."><span class="hidden-xs hidden-sm hidden-md hidden-print fa fa-question-circle"></span></a><?php } ?></th>
        <th class="hidden-xs hidden-sm">Loc<span class="hidden-md">/Box</span></th>
        <?php if ($action != "print") { ?><th class="hidden-xs hidden-sm hidden-print">Actions</th><?php } ?>
    </tr>
</thead>
<tbody>
<?php echo $tbody_rows; ?>
</tbody>
</table>
<?php if ($action != "print") {
	if (($dbTable == "default") && ($totalRows_entry_count >= $_SESSION['prefsRecordLimit']))	{
	if (($filter == "default") && ($bid == "default")) $total_paginate = $totalRows_entry_count;
	else $total_paginate = $totalRows_log;
	}
?>
<?php if ($dbTable == "default") { ?>
<div class="bcoem-admin-element hidden-print">
	<input type="submit" name="Submit" id="helpUpdateEntries" class="btn btn-primary" aria-describedby="helpBlock" value="Update Entries" />
    <span id="helpBlock" class="help-block">Click "Update Entries" <em>before</em> paging through records.</span>
</div>
<?php } ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin&go=entries","default",$msg,$id); ?>">
</form>
<?php } ?>
<?php } else echo "<div class=\"container-fluid\"><div class=\"row\"><div class=\"col-md-12\"><p>No entries have been added to the database yet.</p></div></div></div>"; ?>