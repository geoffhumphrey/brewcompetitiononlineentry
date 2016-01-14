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
$firefox_warning = "";



// Build Headers
$header1_1 .= "<a name=\"entries\"></a><h2>Entries</h2>";
 
$firefox_warning .= "<div class=\"alert alert-warning\"><strong>There is a known issue with printing from the Firefox browser.</strong> To print all pages properly from Firefox, RIGHT CLICK on any print link and choose \"Open Link in New Tab.\" Then, use Firefox&rsquo;s native printing function (Edit > Print) to print your documents. Be aware that you should use the browser&rsquo;s File > Page Setup... function to specify portrait or landscape, margins, etc.</div>";

// Show Scores?
if ((judging_date_return() == 0) && ($entry_window_open == 2) && ($registration_open == 2) && ($judge_window_open == 2) && ($_SESSION['prefsDisplayWinners'] == "Y") && (judging_winner_display($delay))) $show_scores = TRUE; else $show_scores = FALSE;

// Get Entry Fees
$total_entry_fees = total_fees($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $row_brewer['uid'], $filter);
$total_paid_entry_fees = total_fees_paid($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $row_brewer['uid'], $filter);
$total_to_pay = $total_entry_fees - $total_paid_entry_fees; 

// Build Warnings
$warnings = "";
if (($totalRows_log > 0) && ($action != "print")) {
	
	$entries_unconfirmed = entries_unconfirmed($_SESSION['user_id']);
	$entries_unconfirmed_sum = array_sum($entries_unconfirmed);
	
	if (($totalRows_log - $totalRows_log_confirmed) > 0) { 
			$warnings .= "<div class=\"alert alert-warning\">";
			$warnings .= "<strong>You have unconfirmed entries.</strong> For each highlighed entry below with a <span class=\"fa fa-exlamation-circle text-danger\"></span> icon, click the <span class=\"fa fa-pencil text-primary\"></span> icon to review and confirm all your entry data. Unconfirmed entries may be deleted from the system without warning."; 
			if ($_SESSION['prefsPayToPrint'] == "Y") $warnings .= " You CANNOT pay for your entries until all entries are confirmed."; 
			$warnings .= "</div>"; 
		}
		
	if (entries_no_special($_SESSION['user_id'])) {
		$warnings .= "<div class=\"alert alert-warning\"><strong>You have entries that require you to define special ingredients.</strong> For each highlighted entry below with a <span class=\"fa fa-exlamation-circle text-danger\"></span> icon, click the <span class=\"fa fa-pencil text-primary\"></span> icon to add your special ingredients. Entries without special ingredients in categories that require them may be deleted by the system without warning.</div>";
	}
}



// Build user's entry information

$entry_output = "";

do {
	
	$entry_style = $row_log['brewCategorySort']."-".$row_log['brewSubCategory'];
	
	include(DB.'styles.db.php');
	
	// Build Entry Table Body
	
	if ((check_special_ingredients($entry_style,$_SESSION['prefsStyleSet'])) && ($row_log['brewInfo'] == "") && ($action != "print")) $entry_tr_style = "warning";
	else $entry_tr_style = "";
	if (in_array($row_log['id'],$entries_unconfirmed)) $entry_tr_style = "warning";
	else $entry_tr_style = "";
	
	$entry_output .= "<tr class=\"".$entry_tr_style."\">";
	$entry_output .= "<td>";
	$entry_output .= sprintf("%04s",$row_log['id']);
	$entry_output .= "</td>";
	
	$entry_output .= "<td>";
	$entry_output .= $row_log['brewName']; 
	if ($row_log['brewCoBrewer'] != "") $entry_output .= "<br><em>Co-Brewer: ".$row_log['brewCoBrewer']."</em>";
	$entry_output .= "</td>";
	
	$entry_output .= "<td>";
	if ($row_styles['brewStyleActive'] == "Y") $entry_output .= $row_log['brewCategorySort'].$row_log['brewSubCategory'].": ".$row_styles['brewStyle']; 
	elseif (empty($row_log['brewCategorySort'])) $entry_output .= "<strong class=\"text-danger\">Style NOT Entered</strong>";
	else $entry_output .= $entry_style;
	//$entry_output .= "<span class=\"required\">Style entered NOT accepted.</span>";
	$entry_output .= "</td>";
	
	
	
	$entry_output .= "<td class=\"hidden-xs hidden-sm\">";
	if ($row_log['brewConfirmed'] == "0")  $entry_output .= "<span class=\"fa fa-exlamation-circle text-danger\"></span> No";
	elseif ((check_special_ingredients($entry_style,$_SESSION['prefsStyleSet'])) && ($row_log['brewInfo'] == "")) $entry_output .= "<span class=\"fa fa-exlamation-circle\"></span>";
	else $entry_output .= yes_no($row_log['brewConfirmed'],$base_url,1);
	$entry_output .= "</td>";
	
	
	$entry_output .= "<td class=\"hidden-xs hidden-sm\">";
	$entry_output .= yes_no($row_log['brewPaid'],$base_url,1);
	$entry_output .= "</td>";
	
	$entry_output .= "<td class=\"hidden-xs hidden-sm\">";
	if ($row_log['brewUpdated'] != "") $entry_output .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], strtotime($row_log['brewUpdated']), $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time-no-gmt"); else $entry_output .= "&nbsp;";
	$entry_output .= "</td>";
	
	
	// Display if Closed, Judging Dates have passed, winner display is enabled, and the winner display delay time period has passed
	if ($show_scores) {
		
		$medal_winner = winner_check($row_log['id'],$judging_scores_db_table,$judging_tables_db_table,$brewing_db_table,$_SESSION['prefsWinnerMethod']);
		if (NHC) $admin_adv = winner_check($row_log['id'],$judging_scores_db_table,$judging_tables_db_table,$brewing_db_table,$row_log['brewWinner']);
		$winner_place = preg_replace("/[^0-9\s.-:]/", "", $medal_winner);
 		$score = score_check($row_log['id'],$judging_scores_db_table);
	
		$entry_output .= "<td>";
		$entry_output .= $score;
		$entry_output .= "</td>";
		
		$entry_output .= "<td>";
		if (minibos_check($row_log['id'],$judging_scores_db_table)) { 
			if ($action != "print") $entry_output .= "<span class =\"fa fa-check text-success\"></span>"; 
			else $entry_output .= "Yes"; 
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
	
	$edit_link = "";
	$edit_link .= "<a href=\"".$base_url."index.php?section=brew&amp;action=edit&amp;id=".$row_log['id']; 
	if ($row_log['brewConfirmed'] == 0) $edit_link .= "&amp;msg=1-".$brewCategory."-".$row_log['brewSubCategory']; 
	
	$edit_link .= "&amp;view=".$brewCategory."-".$row_log['brewSubCategory'];
	$edit_link .= "\" data-toggle=\"tooltip\" title=\"Edit ".$row_log['brewName']."\">";
	$edit_link .= "<span class=\"fa fa-pencil\"></a>&nbsp;&nbsp;";
	
	
	// Print Forms
	$alt_title = "";
	$alt_title .= "Print ";
	if ((!NHC) && (($_SESSION['prefsEntryForm'] == "B") || ($_SESSION['prefsEntryForm'] == "M") || ($_SESSION['prefsEntryForm'] == "U") || ($_SESSION['prefsEntryForm'] == "N"))) $alt_title .= "Entry Form and ";
	$alt_title .= "Bottle Labels ";
	$alt_title .= "for ".$row_log['brewName'];
	$print_forms_link = "";	
	$print_forms_link .= "<a id=\"modal_window_link\" href=\"".$base_url."output/entry.php?";
	$print_forms_link .= "id=".$row_log['id'];
	$print_forms_link .= "&amp;bid=".$_SESSION['user_id'];
	$print_forms_link .= "\" data-toggle=\"tooltip\" title=\"".$alt_title."\">";
	$print_forms_link .= "<span class=\"fa fa-print\"></a>&nbsp;&nbsp;";
	
	// Print Recipe
	$print_recipe_link = "<a id=\"modal_window_link\" href=\"".$base_url."output/entry.php?go=recipe&amp;id=".$row_log['id']."&amp;bid=".$_SESSION['brewerID']."\" title=\"Print Recipe Form for ".$row_log['brewName']."\"><span class=\"fa fa-book\"><span></a>&nbsp;&nbsp;";
	
	if (($total_entries > $row_limits['prefsEntryLimit'])) $warning_append = "\nAlso, you will not be able to add another entry since the entry limit for the competition has been reached. Click Cancel in this box and then edit the entry instead if you wish to keep it."; else $warning_append = "";
	
	if ($entry_window_open == 1) {
	$delete_alt_title = "Delete ".$row_log['brewName'];
	$delete_warning = "Delete ".$row_log['brewName']."? This cannont be undone.";
	$delete_link = "<a data-toggle=\"tooltip\" title=\"".$delete_alt_title."\" href=\"".$base_url."includes/process.inc.php?section=".$section."&amp;go=".$go."&amp;dbTable=".$brewing_db_table."&amp;action=delete&amp;id=".$row_log['id']."\" data-confirm=\"Are you sure you want to delete the entry called ".$row_log['brewName']."? This cannot be undone.\"><span class=\"fa fa-trash-o\"></a>";
	//$delete_link = build_action_link("bin_closed",$base_url,$section,$go,"delete",$filter,$row_log['id'],$brewing_db_table,"Delete ".$row_log['brewName']."? This cannot be undone. ".$warning_append,1,"Delete");
	}

	if ((judging_date_return() > 0) && ($action != "print")) {
		
		$entry_output .= "<td nowrap class=\"hidden-print\">";
		if (($registration_open == 1) || ($entry_window_open == 1)) $entry_output .= $edit_link;
		if (pay_to_print($_SESSION['prefsPayToPrint'],$row_log['brewPaid'])) $entry_output .= $print_forms_link;
		
		if ((NHC) && ($prefix == "final_")) $entry_output .= $print_recipe_link;
		if ($row_log['brewPaid'] != 1) $entry_output .= $delete_link;
		$entry_output .= "</td>";
		
	}
	
	// Display the edit link for NHC final round after judging has taken place
	// Necessary to gather recipe data for first place winners in the final round
	if ((judging_date_return() == 0) && ($action != "print")) {
		
		$entry_output .= "<td nowrap class=\"hidden-print\">";
		if ((($registration_open == 2) && ($entry_window_open == 1)) && ((NHC) && ($prefix == "final_"))) $entry_output .= $edit_link;
		$entry_output .= "</td>";
	}
	
	$entry_output .= "</tr>";	
	
} while ($row_log = mysql_fetch_assoc($log));

// --------------------------------------------------------------
// Display
// --------------------------------------------------------------

echo $header1_1;
if (($fx) && ($action != "print")) echo $firefox_warning;

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
if (($totalRows_log == 0) && ($entry_window_open >= 1)) echo "<p>You have not added any entries to the system.</p>";
if (($totalRows_log > 0) && ($entry_window_open >= 1)) { 
?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']],
			"aoColumns": [
				null,
				null,
				null,
				null,
				null,
				null,
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
<table class="table table-responsive table-striped table-bordered dataTable" id="sortable">
<thead>
 <tr>
  	<th>#</th>
  	<th>Name</th>
  	<th>Style</th>
  	<th class="hidden-xs hidden-sm">Confirmed</th> 
  	<th class="hidden-xs hidden-sm">Paid</th> 
    <th class="hidden-xs hidden-sm">Updated</th>
  	<?php if ($show_scores) { ?>
  	<th>Score</th>
    <th class="hidden-xs hidden-sm" nowrap>Mini-BOS</th>
  	<th>Winner?</th>
  	<?php } ?>
    <th class="hidden-print">Actions</th>
 </tr>
</thead>
<tbody>
<?php echo $entry_output; ?>
</tbody>
</table>
<?php }
if ($entry_window_open == 0) echo sprintf("<p>You will be able to add entries on or after %s.</p>",$entry_open); 
?>

<!-- Page Rebuild completed 08.27.15 --> 