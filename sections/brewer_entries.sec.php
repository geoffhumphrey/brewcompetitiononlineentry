<?php
/**
 * Module:      brewer_entries.sec.php
 * Description: This module displays the user's entries and related data
 * Info:		As of version 1.3.0, most of the presentation layer has been separated from the programming layer
 *
 *
 */

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


$primary_page_info = "";
$primary_links = "";
$secondary_links = "";
$header1_1 = "";
$page_info1 = "";
$header1_2 = "";
$page_info2 = "";
$table_head1 = "";
$table_body1 = "";

// Page specific variables
$entry_message = "";
$remaining_message = "";
$discount_fee_message = "";
$entry_fee_message = "";
$nhc_message_1 = "";
$nhc_message_2 = "";
$add_entry_link = "";
$beer_xml_link = "";
$print_list_link = "";
$pay_fees_message = "";


$multiple_bottle_ids = FALSE;
if (($_SESSION['prefsEntryForm'] == "5") || ($_SESSION['prefsEntryForm'] == "6")) $multiple_bottle_ids = TRUE;

// Build Headers
$header1_1 .= sprintf("<a class=\"anchor-offset\"name=\"entries\"></a><h2>%s</h2>",$label_entries);

// Build Warnings
$warnings = "";
if (($totalRows_log > 0) && ($action != "print")) {

	$entries_unconfirmed = entries_unconfirmed($_SESSION['user_id']);
	$entries_unconfirmed_sum = array_sum($entries_unconfirmed);

	if (($totalRows_log - $totalRows_log_confirmed) > 0) {
		$warnings .= "<div class=\"alert alert-warning\">";
		$warnings .= sprintf("<span class=\"fa fa-lg fa-exclamation-triangle\"></span> <strong>%s</strong> %s",$brewer_entries_text_001,$brewer_entries_text_002);
		if ($_SESSION['prefsPayToPrint'] == "Y") $warnings .= sprintf(" %s",$brewer_entries_text_003);
		$warnings .= "</div>";
	}

	if (entries_no_special($_SESSION['user_id'])) {
		$warnings .= sprintf("<div class=\"alert alert-warning\"><span class=\"fa fa-lg fa-exclamation-triangle\"></span> <strong>%s</strong> %s</div>",$brewer_entries_text_004,$brewer_entries_text_005);
	}

	if (($_SESSION['prefsPayToPrint'] == "Y") && (judging_date_return() > 0) && (!$disable_pay) && (!$comp_paid_entry_limit)) {
		$warnings .= sprintf("<div class=\"alert alert-warning\"><span class=\"fa fa-lg fa-exclamation-triangle\"></span> <strong>%s!</strong> %s</div>",$label_please_note, $alert_text_085);
	}
}

// Build user's entry information

$entry_output = "";

do {

	include (DB.'styles.db.php');

	// Vars
	$print_forms_link = "";
	$required_info = "";
	$edit_link = "";

	if ((!empty($row_log['brewInfo'])) || (!empty($row_log['brewMead1'])) || (!empty($row_log['brewMead2'])) || (!empty($row_log['brewMead3']))) {
		$brewInfo = "";
		//$brewInfo .= "Required Info: ";
		if (!empty($row_log['brewInfo'])) $brewInfo .= str_replace("^", " | ", $row_log['brewInfo']);
		if (!empty($row_log['brewMead1'])) $brewInfo .= "&nbsp;&nbsp;".$row_log['brewMead1'];
		if (!empty($row_log['brewMead2'])) $brewInfo .= "&nbsp;&nbsp;".$row_log['brewMead2'];
		if (!empty($row_log['brewMead3'])) $brewInfo .= "&nbsp;&nbsp;".$row_log['brewMead3'];
		//$required_info .= " <a tabindex=\"0\" role=\"button\" data-toggle=\"popover\" data-placement=\"right\" data-trigger=\"hover focus\" title=\"Required Info\" data-content=\"".$brewInfo."\"><span class=\"fa fa-lg fa-comment\"></span></a>";
		$required_info .= "<p><strong>".$label_required_info.":</strong> ".$brewInfo."</p>";
	}

	if (!empty($row_log['brewInfoOptional'])) {

		//$required_info .= " <a tabindex=\"0\" role=\"button\" data-toggle=\"popover\" data-placement=\"right\" data-trigger=\"hover focus\" title=\"Optional Info\" data-content=\"".$row_log['brewInfoOptional']."\"><span class=\"fa fa-lg fa-comment-o\"></span></a>";

		$required_info .= "<p><strong>".$label_optional_info."</strong> ".$row_log['brewInfoOptional']."</p>";

	}

	$entry_number = sprintf("%04s",$row_log['id']);
	$judging_number = sprintf("%06s",$row_log['brewJudgingNumber']);

	$entry_style = $row_log['brewCategorySort']."-".$row_log['brewSubCategory'];

	// Build Entry Table Body

	if ((check_special_ingredients($entry_style,$_SESSION['prefsStyleSet'])) && ($row_log['brewInfo'] == "") && ($action != "print")) $entry_tr_style = "warning";
	else $entry_tr_style = "";
	if ((is_array($entries_unconfirmed)) && (in_array($row_log['id'],$entries_unconfirmed))) $entry_tr_style = "warning";
	else $entry_tr_style = "";

	$entry_output .= "<tr class=\"".$entry_tr_style."\">";
	$entry_output .= "<td class=\"hidden-xs\">";
	$entry_output .= $entry_number;
	$entry_output .= "</td>";

	$scoresheet = FALSE;

	if (($show_scores) && ($show_scoresheets)) {

		// Check whether scoresheet file exists, and, if so, provide link.
		$scoresheet_file_name_entry = sprintf("%06s",$entry_number).".pdf";
		$scoresheet_file_name_judging = $judging_number.".pdf";
		$scoresheetfile_entry = USER_DOCS.$scoresheet_file_name_entry;
		$scoresheetfile_judging = USER_DOCS.$scoresheet_file_name_judging;

		if ((file_exists($scoresheetfile_entry)) && ($_SESSION['prefsDisplaySpecial'] == "E")) $scoresheet_file_name = $scoresheet_file_name_entry;
		elseif ((file_exists($scoresheetfile_judging)) && ($_SESSION['prefsDisplaySpecial'] == "J")) $scoresheet_file_name = $scoresheet_file_name_judging;
		else $scoresheet_file_name = "";

		if (!empty($scoresheet_file_name)) {

			$scoresheet = TRUE;

			// The pseudo-random number and the corresponding name of the temporary file are defined each time
			// this brewer_entries.sec.php script is accessed (or refreshed), but the temporary file is created
			// only when the entrant clicks on the gavel icon to access the scoresheet.
			$random_num_str = random_generator(8,2);
			$random_file_name = $random_num_str.".pdf";
			$scoresheet_random_file_relative = "user_temp/".$random_file_name;
			$scoresheet_random_file = USER_TEMP.$random_file_name;
			$scoresheet_random_file_html = $base_url.$scoresheet_random_file_relative;

			if (($scoresheet) && (!empty($scoresheet_file_name))) {
				$scoresheet_link = "";
				$scoresheet_link .= "<a class=\"hide-loader\" href=\"".$base_url."output/scoresheets.output.php?";

				// Obfuscate the *ACTUAL* file names.
				// Prevents casual users from right clicking on scoresheet download link and changing
				// the entry or judging number pdf name passed via the URL to force downloads of files
				// they shouldn't have access to. Can I get a harumph?!
				$scoresheet_link .= "scoresheetfilename=".urlencode(obfuscateURL($scoresheet_file_name,$encryption_key));
				$scoresheet_link .= "&amp;randomfilename=".urlencode(obfuscateURL($random_file_name,$encryption_key))."&amp;download=true";
				$scoresheet_link .= sprintf("\" data-toggle=\"tooltip\" title=\"%s '".$row_log['brewName']."'.\">",$brewer_entries_text_006);
				$scoresheet_link .= "<span class=\"fa fa-lg fa-gavel\"></a>&nbsp;&nbsp;";
			}
		}

		// Clean up temporary scoresheets created for other brewers, when they are at least 1 minute old (just to avoid problems when two entrants try accessing their scoresheets at practically the same time, and clean up previously created scoresheets for the same brewer, regardless of how old they are.
		$tempfiles = array_diff(scandir(USER_TEMP), array('..', '.'));
		foreach ($tempfiles as $file) {
			if ((filectime(USER_TEMP.$file) < time() - 1*60) || ((strpos($file, $scoresheet_file_name_judging) !== FALSE))) {
				unlink(USER_TEMP.$file);
			}

			if ((filectime(USER_TEMP.$file) < time() - 1*60) || ((strpos($file, $scoresheet_file_name_entry) !== FALSE))) {
				unlink(USER_TEMP.$file);
			}
		}
	}

	if ($show_scores) {
		$entry_output .= "<td class=\"hidden-xs\">";
		$entry_output .= $judging_number;
		$entry_output .= "</td>";
	}

	// Brew Name
	$entry_output .= "<td>";
	$entry_output .= $row_log['brewName'];

	if (!empty($required_info)) $entry_output .= " <a role=\"button\" data-toggle=\"collapse\" data-target=\"#collapseEntryInfo".$row_log['id']."\" aria-expanded=\"false\" aria-controls=\"collapseEntryInfo".$row_log['id']."\"><span class=\"fa fa-lg fa-info-circle\"></span></a> ";

	if (!empty($required_info)) {
		$entry_output .= "<div style=\"margin-top: 8px;\" class=\"collapse small well\" id=\"collapseEntryInfo".$row_log['id']."\">";
    	$entry_output .= $required_info;
    	$entry_output .= "</div>";
	}

	//$entry_output .= "&nbsp;".$required_info;
	if ($row_log['brewCoBrewer'] != "") $entry_output .= sprintf("<br><em>%s: ".$row_log['brewCoBrewer']."</em>",$label_cobrewer);
	$entry_output .= "</td>";


	// Style
	$entry_output .= "<td>";

	if ($row_styles['brewStyleActive'] == "Y") {
		if ($_SESSION['prefsStyleSet'] == "BA") $entry_output .= $row_log['brewStyle'];
		else $entry_output .= $row_log['brewCategorySort'].$row_log['brewSubCategory'].": ".$row_log['brewStyle'];
	}
	else $entry_output .= sprintf("<strong class=\"text-danger\">%s</strong>",$brewer_entries_text_016);
	if (empty($row_log['brewCategorySort'])) $entry_output .= sprintf("<strong class=\"text-danger\">%s</strong>",$brewer_entries_text_007);

	$entry_output .= "</td>";

	if (!$show_scores) {
		$entry_output .= "<td class=\"hidden-xs hidden-md\">";
		if ($row_log['brewConfirmed'] == "0")  $entry_output .= "<span class=\"fa fa-lg fa-exclamation-circle text-danger\"></span>";
		elseif ((check_special_ingredients($entry_style,$_SESSION['prefsStyleSet'])) && ($row_log['brewInfo'] == "")) $entry_output .= "<span class=\"fa fa-lg fa-exclamation-circle text-danger\"></span>";
		else $entry_output .= yes_no($row_log['brewConfirmed'],$base_url,1);
		$entry_output .= "</td>";

		$entry_output .= "<td class=\"hidden-xs\">";
		$entry_output .= yes_no($row_log['brewPaid'],$base_url,1);
		$entry_output .= "</td>";

		$entry_output .= "<td class=\"hidden-xs\">";
		$entry_output .= yes_no($row_log['brewReceived'],$base_url,1);
		$entry_output .= "</td>";

		$entry_output .= "<td class=\"hidden-xs hidden-sm\">";
		if ($row_log['brewUpdated'] != "") $entry_output .= "<span class=\"hidden\">".strtotime($row_log['brewUpdated'])."</span>".getTimeZoneDateTime($_SESSION['prefsTimeZone'], strtotime($row_log['brewUpdated']), $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time-no-gmt"); else $entry_output .= "&nbsp;";
		$entry_output .= "</td>";


		if ($multiple_bottle_ids) {
			$entry_output .= "<td>";
			$entry_output .= "<div class=\"checkbox\"><label>";
			if (((pay_to_print($_SESSION['prefsPayToPrint'],$row_log['brewPaid'])) && (!$comp_paid_entry_limit)) || (($comp_paid_entry_limit) && ($row_log['brewPaid'] == 1))) $entry_output .= "<input class=\"entry-print\" name=\"id[]\" type=\"checkbox\" value=\"".$row_log['id']."\">";
			else $entry_output .= "<span class=\"fa fa-lg fa-times text-danger\"></span>";
			$entry_output .= "</label></div>";
			$entry_output .= "</td>";
		}

	}

	// Display if Closed, Judging Dates have passed, winner display is enabled, and the winner display delay time period has passed
	if ($show_scores) {

		$medal_winner = winner_check($row_log['id'],$judging_scores_db_table,$judging_tables_db_table,$brewing_db_table,$_SESSION['prefsWinnerMethod']);
		if (NHC) $admin_adv = winner_check($row_log['id'],$judging_scores_db_table,$judging_tables_db_table,$brewing_db_table,$row_log['brewWinner']);
		$winner_place = preg_replace("/[^0-9\s.-:]/", "", $medal_winner);
 		$score = score_check($row_log['id'],$judging_scores_db_table);

		$entry_output .= "<td>";
		$entry_output .= $score;
		$entry_output .= "</td>";

		$entry_output .= "<td class=\"hidden-xs\">";
		if (minibos_check($row_log['id'],$judging_scores_db_table)) {
			if ($action != "print") $entry_output .= "<span class =\"fa fa-lg fa-check text-success\"></span>";
			else $entry_output .= $label_yes;
		}
		else $entry_output .= "&nbsp;";
		$entry_output .= "</td>";

		$entry_output .= "<td>";
		$entry_output .= $medal_winner;
		if ((NHC) && ($prefix != "final_")) $enter_output .= $admin_adv;
		$entry_output .= "</td>";

	}

	// Build Actions Links

	// Edit
	if (($row_log['brewCategory'] < 10) && (preg_match("/^[[:digit:]]+$/",$row_log['brewCategory']))) $brewCategory = "0".$row_log['brewCategory'];
	else $brewCategory = $row_log['brewCategory'];

	if (($entry_window_open == 1) || (($entry_window_open != 1) && (time() < $row_contest_dates['contestEntryDeadline']))) {

		$edit_link .= "<a href=\"".$base_url."index.php?section=brew&amp;action=edit&amp;id=".$row_log['id'];
		if ($row_log['brewConfirmed'] == 0) $edit_link .= "&amp;msg=1-".$brewCategory."-".$row_log['brewSubCategory'];
		$edit_link .= "&amp;view=".$brewCategory."-".$row_log['brewSubCategory'];
		$edit_link .= "\" data-toggle=\"tooltip\" title=\"Edit ".$row_log['brewName']."\">";
		$edit_link .= "<span class=\"fa fa-lg fa-pencil\"></a>&nbsp;&nbsp;";

	}

	else $edit_link .= "<span data-toggle=\"tooltip\" title=\"".$brewer_entries_text_020."\" data-placement=\"auto top\" data-container=\"body\" class=\"fa fa-lg fa-pencil text-muted\"></span>&nbsp;&nbsp;";


	// Print Forms
	$alt_title = "";
	$alt_title .= "Print ";
	if ((!NHC) && (($_SESSION['prefsEntryForm'] == "B") || ($_SESSION['prefsEntryForm'] == "M") || ($_SESSION['prefsEntryForm'] == "U") || ($_SESSION['prefsEntryForm'] == "N"))) $alt_title .= sprintf("%s ",$brewer_entries_text_008);
	$alt_title .= sprintf("%s ",$brewer_entries_text_009);
	$alt_title .= "for ".$row_log['brewName'];

	if (!$multiple_bottle_ids) {

		if (((pay_to_print($_SESSION['prefsPayToPrint'],$row_log['brewPaid'])) && (!$comp_paid_entry_limit)) || (($comp_paid_entry_limit) && ($row_log['brewPaid'] == 1))) {
				$print_forms_link .= "<a id=\"modal_window_link\" class=\"hide-loader\" href=\"".$base_url."output/entry.output.php?";
				$print_forms_link .= "id=".$row_log['id'];
				$print_forms_link .= "&amp;bid=".$_SESSION['user_id'];
				$print_forms_link .= "\" data-toggle=\"tooltip\" title=\"".$alt_title."\">";
				$print_forms_link .= "<span class=\"fa fa-lg fa-print\"></span></a>&nbsp;&nbsp;";
		}

		else {
			$print_forms_link .= "<span data-toggle=\"tooltip\" title=\"";
			if ($comp_paid_entry_limit) $print_forms_link .= $brewer_entries_text_019." ".$pay_text_034;
			else $print_forms_link .= $brewer_entries_text_018;
			$print_forms_link .= "\" data-placement=\"auto top\" data-container=\"body\" class=\"fa fa-lg fa-print text-muted\"></span>&nbsp;&nbsp;";
		}

	}

	// Print Recipe
	$print_recipe_link = sprintf("<a id=\"modal_window_link\" class=\"hide-loader\" href=\"".$base_url."output/entry.output.php?go=recipe&amp;id=".$row_log['id']."&amp;bid=".$_SESSION['brewerID']."\" title=\"%s ".$row_log['brewName']."\"><span class=\"fa fa-lg fa-book\"><span></a>&nbsp;&nbsp;",$brewer_entries_text_010);

	if ($comp_entry_limit) $warning_append = sprintf("\n%s",$brewer_entries_text_011); else $warning_append = "";

	$delete_alt_title = sprintf("%s %s",$label_delete, $row_log['brewName']);
	$delete_warning = sprintf("%s %s - %s.",$label_delete,$row_log['brewName'],strtolower($label_undone));
	$delete_link = sprintf("<a class=\"hide-loader\" data-toggle=\"tooltip\" title=\"%s\" href=\"%s\" data-confirm=\"%s.\"><span class=\"fa fa-lg fa-trash-o\"></a>",$delete_alt_title,$base_url."includes/process.inc.php?section=".$section."&amp;go=".$go."&amp;dbTable=".$brewing_db_table."&amp;action=delete&amp;id=".$row_log['id'],$delete_warning);
	$entry_output .= "<td nowrap class=\"hidden-print\">";

	if ($scoresheet) {
		$entry_output .= $scoresheet_link;
	}

	if (!$show_scores) {
		$entry_output .= $edit_link;
		$entry_output .= $print_forms_link;
	}

	// If a judging date has not passed yet
	if (judging_date_return() > 0) {
		if ($row_log['brewPaid'] != 1) $entry_output .= $delete_link;
		else $entry_output .= sprintf("<span class=\"fa fa-lg fa-trash-o text-muted\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"%s\" href=\"#\"></span>",$brewer_entries_text_015);
	}

	// If no judging date specified or if a single judging date has passed
	if (judging_date_return() == 0) {

		// Display the edit link for NHC final round after judging has taken place
		// Necessary to gather recipe data for first place winners in the final round
		if ((($registration_open == 2) && ($entry_window_open == 1)) && ((NHC) && ($prefix == "final_"))) $entry_output .= $edit_link;

		// Display delete link only when entry window is open
		if ($entry_window_open == 1) {
			if ($row_log['brewPaid'] != 1) $entry_output .= $delete_link;
			else $entry_output .= sprintf("<span class=\"fa fa-lg fa-trash-o text-muted\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"%s\" href=\"#\"></span>",$brewer_entries_text_015);
		}

	}

	$entry_output .= "</td>";
	$entry_output .= "</tr>";

} while ($row_log = mysqli_fetch_assoc($log));

// --------------------------------------------------------------
// Display
// --------------------------------------------------------------

echo $header1_1;

// Display Warnings and Entry Message
if (($totalRows_log > 0) && ($action != "print")) {
	echo $warnings;
	echo $entry_message;
}

// Display links and other information
if (($action != "print") && ($entry_window_open > 0)) {
	echo $primary_links;
	echo $page_info1;
	echo $page_info2;
}
if (($totalRows_log == 0) && ($entry_window_open >= 1)) echo sprintf("<p>%s</p>",$brewer_entries_text_014);
if (($totalRows_log > 0) && ($entry_window_open >= 1)) {

?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {

	 	$( ".entry-print" ).on("click", function() {
			if($(".entry-print:checked").length > 0) {
				$('#btn').prop('disabled', false);
			}
			else {
				$('#btn').prop('disabled', true);
			}
		});

		$('.entry-print').change(function(){
		    //uncheck "select all", if one of the listed checkbox item is unchecked
		    if(false == $(this).prop("checked")){ //if this item is unchecked
		        $("#select_all").prop('checked', false); //change "select all" checked status to false
		    }
		    //check "select all" if all checkbox items are checked
		    if ($('.checkbox:checked').length == $('.checkbox').length ){
		        $("#select_all").prop('checked', true);
		    }
		});

		$("#select_all").change(function () {
    		$("input:checkbox").prop('checked', $(this).prop("checked"));
    		if($(".entry-print:checked").length > 0) {
				$('#btn').prop('disabled', false);
			}
			else {
				$('#btn').prop('disabled', true);
			}
		});

		$('#sortable').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']],
			"aoColumns": [

				null,
				<?php if ($show_scores) { ?>
				null,
				<?php } ?>
				null,
				null,
				<?php if (!$show_scores) { ?>
					null,
				null,
				null,
				null,
				<?php if ($multiple_bottle_ids) { ?>{ "asSorting": [  ] },<?php } ?>
				<?php } ?>
				<?php if ($show_scores) { ?>
				null,
				{ "asSorting": [  ] },
				null,
				<?php } ?>
				<?php if ($action != "print") { ?>
				{ "asSorting": [  ] }
				<?php } ?>
				]
			} );
		} );


</script>
<form name="form1" method="post" action="<?php echo $base_url; ?>output/bottle_label.output.php" target="_blank" class="hide-loader-form-submit">
<table class="table table-responsive table-striped table-bordered dataTable" id="sortable">
<thead>
 <tr>
  	<th class="hidden-xs"><?php if ($show_scores) echo $label_entry ?>#</th>
    <?php if ($show_scores) { ?>
    <th class="hidden-xs"><?php echo $label_judging; ?>#</th>
    <?php } ?>
  	<th>Name</th>
  	<th><?php echo $label_style; ?></th>
    <?php if (!$show_scores) { ?>
  	<th class="hidden-xs hidden-md"><?php echo $label_confirmed; ?></th>
  	<th class="hidden-xs"><?php echo $label_paid; ?></th>
    <th class="hidden-xs" nowrap><?php echo $label_received; ?> <a tabindex="0" role="button" title="<?php echo $label_received." ".$label_entries." ".$label_info; ?>" data-placement="auto top" data-toggle="popover" data-trigger="hover focus" data-content="<?php echo $brewer_entries_text_017; ?>" data-container="body"><span class="fa fa-question-circle"></span></a></th>
    <th class="hidden-xs hidden-sm"><?php echo $label_updated; ?></th>
    <?php } ?>
  	<?php if ($show_scores) { ?>
  	<th><?php echo $label_score; ?></th>
    <th class="hidden-xs" nowrap><?php echo $label_mini_bos; ?></th>
  	<th><?php echo $label_winner; ?></th>
  	<?php } ?>
  	<?php if ((!$show_scores) && ($multiple_bottle_ids)) { ?>
    <th class="hidden-print" nowrap>
    <input type="checkbox" id="select_all"> <a style="cursor: pointer;" data-toggle="popover" data-container="body" data-trigger="hover focus" data-placement="auto" title="<?php echo $brewer_entries_text_024; ?>" data-content="<?php echo $brewer_entries_text_021; ?>"><span class="fa fa-question-circle"></span></a>
    </th>
	<?php } ?>
    <th class="hidden-print"><?php echo $label_actions; ?></th>
 </tr>
</thead>
<tbody>
<?php echo $entry_output; ?>
</tbody>
</table>
<?php if ((!$show_scores) && ($multiple_bottle_ids)) { ?>
<input type="submit" id="btn" class="btn btn-primary pull-right" value="Print Bottle Labels" disabled data-toggle="popover" data-container="body" data-trigger="hover focus" data-placement="auto right" title="<?php echo $brewer_entries_text_022; ?>" data-content="<?php echo $brewer_entries_text_023; ?>">
<?php } ?>
</form>
<?php }
if ($entry_window_open == 0) echo sprintf("<p>%s %s.</p>",$brewer_entries_text_013,$entry_open);
?>

<!-- Page Rebuild completed 08.27.15 -->