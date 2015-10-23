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
$header1_1 .= "<a name='list'></a><h2>Entries</h2>";
 
$firefox_warning .= "<div class='warning'>There is a known issue with printing from the Firefox browser. To print all pages properly from Firefox, RIGHT CLICK on any print link and choose \"Open Link in New Tab.\" Then, use Firefox's native printing function (Edit > Print) to print your documents. Be aware that you should use the browser's File > Page Setup... function to specify portrait or landscape, margins, etc.</div>";

// Show Scores?
if ((judging_date_return() == 0) && ($entry_window_open == 2) && ($registration_open == 2) && ($judge_window_open == 2) && ($_SESSION['prefsDisplayWinners'] == "Y") && (judging_winner_display($delay))) $show_scores = TRUE; else $show_scores = FALSE;

// Get Entry Fees

$total_entry_fees = total_fees($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $row_brewer['uid'], $filter);
$total_paid_entry_fees = total_fees_paid($_SESSION['contestEntryFee'], $_SESSION['contestEntryFee2'], $_SESSION['contestEntryFeeDiscount'], $_SESSION['contestEntryFeeDiscountNum'], $_SESSION['contestEntryCap'], $_SESSION['contestEntryFeePasswordNum'], $row_brewer['uid'], $filter);
$total_to_pay = $total_entry_fees - $total_paid_entry_fees; 

// Build Warnings
$warnings = "";
if (($totalRows_log > 0) && ($action != "print")) {
	
	if (entries_unconfirmed($_SESSION['user_id']) > 0) { 
			$warnings .= "<div class='error'>";
			$warnings .= "You have unconfirmed entries. For each highlighed entry below with a <span class='icon'><img src='".$base_url."images/exclamation.png'></span> icon, click \"Edit\" to review and confirm all your entry data. Unconfirmed entries will be deleted automatically after 24 hours."; 
			if ($_SESSION['prefsPayToPrint'] == "Y") $warnings .= " You CANNOT pay for your entries until all entries are confirmed."; 
			$warnings .= "</div>"; 
		}
		
	if (entries_no_special($_SESSION['user_id'])) $warnings .= "<div class='warning'>You have entries that require you to define special ingredients. For each highlighted entry below with a <span class='icon'><img src='".$base_url."images/exclamation.png'></span> icon, click \"Edit\" to add your special ingredients. Entries without special ingredients in categories that require them will be deleted automatically after 24 hours.</div>";
	
}

// Build Messages and Links
if (($registration_open >= 1) && ($entry_window_open >=1)) {
	$entry_message .= "<div class='adminSubNavContainer'>";
	$entry_message .= "<span class='adminSubNav'>";
	$entry_message .= "<span class='icon'><img src='".$base_url."images/information.png'  border='0' alt='Entry Limit' title='Entry Limit' /></span>";
	$entry_message .= $_SESSION['brewerFirstName'].", you have ".readable_number($totalRows_log);
	if ($totalRows_log == 1) $entry_message .= " entry"; else $entry_message .= " entries"; 
	$entry_message .= ", listed below.";
	$entry_message .= "</span>";
	$entry_message .= "</div>";
	
	// Build Remaining Entries Message
	if ($row_limits['prefsUserEntryLimit'] != "") {
		$remaining_message .= "<div class='adminSubNavContainer'>";
		$remaining_message .= "<span class='adminSubNav'>";
		$remaining_message .= "<span class='icon'><img src='".$base_url."images/information.png'  border='0' alt='Entry Limit' title='Entry Limit' /></span>";
		
		if ($remaining_entries > 0) {
			$remaining_message .= "You have <strong>".readable_number($remaining_entries)." (".$remaining_entries.")</strong>";
			if ($remaining_entries == 1) $remaining_message .= " entry ";
			else $remaining_message .= " entries "; 
			$remaining_message .= "left before you reach the limit of ".readable_number($row_limits['prefsUserEntryLimit'])." (".$row_limits['prefsUserEntryLimit'].") ";
			if ($row_limits['prefsUserEntryLimit'] > 1) $remaining_message .= " entries ";
			else $remaining_message .= " entry "; 
			$remaining_message .= "per participant in this competition.";
		}
		else {
			$remaining_message .= "<strong>";
			$remaining_message .= "You have reached the limit of ".readable_number($row_limits['prefsUserEntryLimit'])." (".$row_limits['prefsUserEntryLimit'].") ";
			if ($row_limits['prefsUserEntryLimit'] > 1) $remaining_message .= "entries ";
			else $remaining_message .= "entry ";
			$remaining_message .= "per participant in this competition.";
			$remaining_message .= "</strong>";
		}
		
		$remaining_message .= "</span>";
		$remaining_message .= "</div>";
	}
	
	// Build Add Entry Link
	$add_entry_link .= "<span class='adminSubNav'>";
	$add_entry_link .= "<span class='icon'><img src='".$base_url."images/book_add.png'  border='0' alt='Add Entry' title='Add Entry' /></span>";
	$add_entry_link .= "<a href='";
	if ($_SESSION['userLevel'] <= "1") $add_entry_link .= "index.php?section=brew&amp;go=entries&amp;action=add&amp;filter=admin"; 
	else $add_entry_link .= "index.php?section=brew&amp;action=add'";
	$add_entry_link .= "'>Add an Entry</a>";
	$add_entry_link .= "</span>";
	
	// Build Beer XML Link
	$beer_xml_link .= "<span class='adminSubNav'>";
	$beer_xml_link .= "<span class='icon'><img src='".$base_url."images/page_code.png' border='0' alt='Add Entry Using BeerXML' title='Add Entry Using BeerXML' /></span>";
	$beer_xml_link .= "<a href='".build_public_url("beerxml","default","default",$sef,$base_url)."'>Import Entries Using BeerXML</a>";
	$beer_xml_link .= "</span>";

	// Build Print List of Entries Link
	$print_list_link .= "<span class='adminSubNav'>";
	$print_list_link .= "<span class='icon'><img src='".$base_url."images/printer.png' border='0' alt='Print Entry List' title='Print Entry List' /></span>";
	$print_list_link .= "<a id='modal_window_link' href='".$base_url."output/print.php?section=list&amp;action=print' title='Print Your List of Entries and Info'>Print Your List of Entries and Info</a>"; 
	$print_list_link .= "</span>";
	
	// Build Entry Fee Message
	$entry_fee_message .= "<span class='adminSubNav'>";
	$entry_fee_message .= "<span class='icon'><img src='".$base_url."images/money.png' border='0' alt='Entry Fees' title='Entry Fees' /></span>";
	$entry_fee_message .= "You currently have ".readable_number($total_not_paid)." <strong>unpaid</strong>";
	if ($total_not_paid == "1") $entry_fee_message .= " entry. "; 
	else $entry_fee_message .= " entries. ";
	$entry_fee_message .= "Your total entry fees are ".$currency_symbol.$total_entry_fees; 
	if ((NHC) && ($_SESSION['brewerDiscount'] != "Y")) $entry_fee_message .= " (as a non-AHA member)"; 
	$entry_fee_message .= ". You need to pay ".$currency_symbol.$total_to_pay.".";
	$entry_fee_message .= "</span>";
	
	// Build Discount Fee Message
	$discount_fee_message .= "<span class='adminSubNav'>";
	$discount_fee_message .= "<span class='icon'><img src='".$base_url."images/star.png' border='0' alt='Discount!' title='Discount!' /></span>";
	if (NHC) $discount_fee_message .= "As an AHA member, your entry fees are "; 
	else $discount_fee_message .= "Your fees have been discounted to "; 
	$discount_fee_message .= $currency_symbol.$_SESSION['contestEntryFeePasswordNum']." per entry.";
	$discount_fee_message .= "</span>";
	
	// Build Pay Fees Message/Link
	$pay_fees_message .= "<span class='adminSubNav'>";
	if ($totalRows_log == 0) $pay_fees_message .= "";
	elseif (($total_not_paid > 0) && ($_SESSION['contestEntryFee'] > 0)) {
		if ($totalRows_log_confirmed == $totalRows_log) { 
			$pay_fees_message .= "<span class='icon'><img src='".$base_url."images/exclamation.png' border='0' alt='Entry Fees' title='Entry Fees' /></span>";
			$pay_fees_message .= "<a href='".build_public_url("pay","default","default",$sef,$base_url)."'>Pay Your Fees</a>";
			if ($_SESSION['prefsPayToPrint'] == "Y") $pay_fees_message .= " <em>** Please note that you will not be able to print your bottle labels and entry forms until you pay for your entries.</em>";
		}
		else {
			$pay_fees_message .= "<span class='icon'><img src='".$base_url."images/exclamation.png' border='0' alt='Entry Fees' title='Entry Fees' /></span>";
			$pay_fees_message .= "<span style='color: red;'>You have unconfirmed entries.";
			if ($_SESSION['prefsPayToPrint'] == "Y") $pay_fees_message .= " <strong>You cannot pay for your entries until ALL are confirmed.</strong>";
			$pay_fees_message .= "</span> Confirm each entry by clicking its corresponding &ldquo;Edit&rdquo; link.";
		}
	}
	else {
		$pay_fees_message .= "<span class='icon'><img src='".$base_url."images/thumb_up.png' border='0' alt='Entry Fees' title='Entry Fees' /></span>";
		$pay_fees_message .= "Your fees have been paid. Thank you.";
		
	}
	$pay_fees_message .= "</span>";
	
	/* ------------------------ NHC-specific Code -----------------------------
		
	  The following code is specifically for the NHC installation of BCOE&M.
	  Displays the banner above the list of entries directing users to download
	  their post-competition package.
		
	*/
	
	if (NHC) {
	
		if ($show_scores) { 
			
			if ($totalRows_package_count > 0) {
				do { 
				if (($prefix != "final_") && ($row_package_count['scorePlace'] != "") && ($row_package_count['scorePlace'] <= 3) && ($row_package_count['scoreEntry'] >= 30)) $count_winner[] = 1;
				elseif (($prefix == "final_") &&($row_package_count['scorePlace'] != "") && ($row_package_count['scorePlace'] <= 3)) $count_winner[] = 1;
				else $count_winner[] = 0;
				} while ($row_package_count = mysql_fetch_assoc($package_count));
				$winner_count = array_sum($count_winner);
			}
			else $winner_count = 0;
			
			if ($winner_count > 0) $winner = TRUE;
			if ($row_admin_adv['count'] > 0) $admin_advance = TRUE;
			if ($totalRows_package_count > 0) $certificate = TRUE;
			
		}
	
	// Build NHC Specific Messages
		
		$nhc_message_1 .= "<div class='adminSubNavContainer'>";
		$nhc_message_1 .= "<span class='adminSubNav'>";
		$nhc_message_1 .= "<span class='icon'><img src='".$base_url."images/exclamation.png' border='0' alt='NHC Paid' title='NHC Paid' /></span>";
		if ((($registration_open == 2) && ($entry_window_open == 1)) && ((NHC) && ($prefix == "final_"))) {
		$nhc_message_1 .= "Please click the corresponding edit link below add the recipe for each of your entries.";
		}
		else {
		$nhc_message_1 .= "Your entries are not completely entered until they have been confirmed and entry fees have been paid.  Entries not paid within 24 hours of registration will be deleted from the competition database.";
		}
		$nhc_message_1 .= "</span>";
		$nhc_message_1 .= "</div>";
		
		if ($prefix != "final_") {
			$nhc_message_2 .= "<div class='closed'>";
			$nhc_message_2 .= "Your NHC Post-Competition Package is now available - it includes a letter from the American Homebrewers Association";
			if ($certificate) { 
				$nhc_message_2 .= " and the gold, silver, and/or bronze certificates your"; 
				if ($totalRows_count_winner == 1) $nhc_message_2 .= " entry "; 
				else $nhc_message_2 .= " entries ";
				$nhc_message_2 .= "earned";
			}
			$nhc_message_2 .= ". ";
			$nhc_message_2 .= "Download the <a href='".$base_url."mods/nhc_package.php?view=";
			if ($winner) $nhc_message_2 .= "winner";  
			else $nhc_message_2 .= "non-winner"; 
			if ($admin_advance) $nhc_message_2 .= "&amp;filter=admin_adv"; 
			else $nhc_message_2 .= "&amp;filter=default&amp;id=".$_SESSION['user_id']."'>letter</a> (PDF)";
			if ($certificate) $nhc_message_2 .= " and your <a href='".$base_url."mods/nhc_package_certificates.php?id=".$_SESSION['user_id']."'>certificates</a> (PDF).";
			$nhc_message_2 .= "</div>";
		}
		
		if ($prefix == "final_") {
			$nhc_message_2 .= "<div class='closed'>";
			$nhc_message_2 .= "Your NHC Post-Final Round Competition Letter is now available. ";
			$nhc_message_2 .= "Download the <a href='".$base_url."mods/nhc_package.php?view=";
			if ($winner) $nhc_message_2 .= "winner";  
			else $nhc_message_2 .= "non-winner"; 
			$nhc_message_2 .= "&amp;filter=default&amp;id=".$_SESSION['user_id']."'>letter</a> (PDF) from the American Homebrewers Association.";
			if ($winner) $nhc_message_2 .= "<br>Your customized NHC Final Round Press Release is now available that details your medal wins. Download your <a href='".$base_url."mods/nhc_press_release.php?view=winner&amp;id=".$_SESSION['user_id']."'>press release package</a> (PDF).";
			//$nhc_message_2 .= "Download the <a href='".$base_url."mods/nhc_press_release.php?view=winner&amp;id=".$_SESSION['user_id'].">press release package</a> (PDF).";
			$nhc_message_2 .= "</div>";
		}
		
	
	} // end if (NHC)

} // end if (($registration_open == 1) && (($entry_open == 1) || ($entry_open == 2))) 

// Compile Primary Links
// Add Entry, Beer XML and Print List of Entries Links
	if ((judging_date_return() > 0) && (!$comp_entry_limit)) $primary_links .= $remaining_message;
	$primary_links .= "<div class='adminSubNavContainer'>";
	if (($remaining_entries > 0) && ($entry_window_open == 1) && (judging_date_return() > 0) && (!$comp_entry_limit)) {
		$primary_links .= $add_entry_link;
		if ((!NHC) && ($_SESSION['prefsHideRecipe'] == "N")) $primary_links .= $beer_xml_link;
		}
	$primary_links .= $print_list_link;
	$primary_links .= "</div>";

// Compile Messages
// Entry Fee and Discount Info
if (($registration_open >= 1) && ($entry_window_open >= 1)) {
	if ((!NHC) || ((NHC) && ($prefix != "final_"))) { 
		$page_info1 .= "<div class='adminSubNavContainer'>";
		$page_info1 .=  $entry_fee_message;
		if (($_SESSION['brewerDiscount'] == "Y") && ($_SESSION['contestEntryFeePasswordNum'] != "")) $page_info1 .=  $discount_fee_message;
		$page_info1 .=  "</div>";
		$page_info1 .=  "<div class='adminSubNavContainer'>";
		$page_info1 .=  $pay_fees_message;	
		$page_info1 .=  "</div>";
	} // end if ((!NHC) || ((NHC) && ($prefix != "final_"))) 
} // end if if (judging_date_return() > 0) 

if (NHC) { 
	if (($entry_window_open > 0) && ($prefix != "final_")) $page_info2 .=  $nhc_message_1;
	if ($show_scores) $page_info2 .=  $nhc_message_2;
} // end if (NHC)


// Build user's entry information

$entry_output = "";

do {
	
	$entry_style = $row_log['brewCategorySort']."-".$row_log['brewSubCategory'];
	
	include(DB.'styles.db.php');
	
	// Build Entry Table Body
	
	if (($row_log['brewConfirmed'] == 0) && ($action != "print")) $entry_tr_style = " style='background-color: #ff9; border-top: 1px solid #F90; border-bottom: 1px solid #F90;'"; 
	elseif ((check_special_ingredients($entry_style,$_SESSION['prefsStyleSet'])) && ($row_log['brewInfo'] == "") && ($action != "print")) $entry_tr_style = " style='background-color: #f90; border-top: 1px solid #FF6600; border-bottom: 1px solid #FF6600;'";
	else $entry_tr_style = "";
	
	$entry_output .= "<tr".$entry_tr_style.">";
	if ($action == "print") $entry_output .= "<td class='dataList bdr1B'>";
	else $entry_output .= "<td class='dataList'>";
	if ((NHC) && ($prefix == "final_")) $entry_output .= sprintf("%06s",$row_log['id']); else $entry_output .= sprintf("%04s",$row_log['id']);
	$entry_output .= "</td>";
	
	if ($action == "print") $entry_output .= "<td class='dataList bdr1B'>";
	else $entry_output .= "<td class='dataList'>";
	$entry_output .= $row_log['brewName']; 
	if ($row_log['brewCoBrewer'] != "") $entry_output .= "<br><em>Co-Brewer: ".$row_log['brewCoBrewer']."</em>";
	$entry_output .= "</td>";
	
	if ($action == "print") $entry_output .= "<td class='dataList bdr1B'>";
	else $entry_output .= "<td class='dataList'>";
	if ($row_styles['brewStyleActive'] == "Y") $entry_output .= $row_log['brewCategorySort'].$row_log['brewSubCategory'].": ".$row_styles['brewStyle']; 
	elseif (empty($row_log['brewCategorySort'])) $entry_output .= "<span class='required'>Style NOT entered</span>";
	else $entry_output .= $entry_style;
	//$entry_output .= "<span class='required'>Style entered NOT accepted.</span>";
	$entry_output .= "</td>";
	
	
	
	if ($action == "print") $entry_output .= "<td class='dataList bdr1B'>";
	else $entry_output .= "<td class='dataList'>";
	if ($row_log['brewConfirmed'] == "0") { 
		if ($action != "print") $entry_output .= "<span class='icon'><img src='".$base_url."images/exclamation.png' border='0' alt='Unconfirmed entry!' title='Unconfirmed entry! Click Edit to review and confirm the entry data.'></span>"; else $entry_output .= "Y";
	} 
	elseif ((check_special_ingredients($entry_style,$_SESSION['prefsStyleSet'])) && ($row_log['brewInfo'] == "")) { 
		if ($action != "print") $entry_output .= "<span class='icon'><img src='".$base_url."images/exclamation.png'  border='0' alt='Unconfirmed entry!' title='Unconfirmed entry! Click Edit to review and confirm the entry data.'></span>"; else $entry_output .= "Y";
	} 
	else { 
		if ($action != "print") $entry_output .= yes_no($row_log['brewConfirmed'],$base_url);
		else $entry_output .= "Yes";
	} 
	
	$entry_output .= "</td>";
	
	
	if ($action == "print") $entry_output .= "<td class='dataList bdr1B'>";
	else $entry_output .= "<td class='dataList'>";
	if ($action != "print") $entry_output .= yes_no($row_log['brewPaid'],$base_url);
	else  $entry_output .= yes_no($row_log['brewPaid'],$base_url,3);
	$entry_output .= "</td>";
	
	
	if ($action == "print") $entry_output .= "<td class='dataList bdr1B'>";
	else $entry_output .= "<td class='dataList'>";
	if ($row_log['brewUpdated'] != "") $entry_output .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], strtotime($row_log['brewUpdated']), $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time-no-gmt"); else $entry_output .= "&nbsp;";
	$entry_output .= "</td>";
	
	
	// Display if Closed, Judging Dates have passed, winner display is enabled, and the winner display delay time period has passed
	if ($show_scores) {
		
		$medal_winner = winner_check($row_log['id'],$judging_scores_db_table,$judging_tables_db_table,$brewing_db_table,$_SESSION['prefsWinnerMethod']);
		if (NHC) $admin_adv = winner_check($row_log['id'],$judging_scores_db_table,$judging_tables_db_table,$brewing_db_table,$row_log['brewWinner']);
		$winner_place = preg_replace("/[^0-9\s.-:]/", "", $medal_winner);
 		$score = score_check($row_log['id'],$judging_scores_db_table);
	
		if ($action == "print") $entry_output .= "<td class='dataList bdr1B'>";
	else $entry_output .= "<td class='dataList'>";
		$entry_output .= $score;
		$entry_output .= "</td>";
		
		if ($action == "print") $entry_output .= "<td class='dataList bdr1B'>";
	else $entry_output .= "<td class='dataList'>";
		if (minibos_check($row_log['id'],$judging_scores_db_table)) { 
			if ($action != "print") $entry_output .= "<img src='".$base_url."images/tick.png'> Yes"; 
			else $entry_output .= "Yes"; 
			}
		else $entry_output .= "&nbsp;";
		$entry_output .= "</td>";
		
		if ($action == "print") $entry_output .= "<td class='dataList bdr1B'>";
	else $entry_output .= "<td class='dataList'>";
		$entry_output .= $medal_winner;
		if ((NHC) && ($prefix != "final_")) $enter_output .= $admin_adv;
		$entry_output .= "</td>";
		
	}
	
	
	// Build Actions Links
	
	// Edit
	if (($row_log['brewCategory'] < 10) && (preg_match("/^[[:digit:]]+$/",$row_log['brewCategory']))) $brewCategory = "0".$row_log['brewCategory'];
	else $brewCategory = $row_log['brewCategory'];
	
	$edit_link = "";
	$edit_link .= "<span class='icon'><img src='".$base_url."images/pencil.png' border='0' alt='Edit ".$row_log['brewName']."' title='Edit ".$row_log['brewName']."'></span>";
	$edit_link .= "<a href='".$base_url."index.php?section=brew&amp;action=edit&amp;id=".$row_log['id']; 
	if ($row_log['brewConfirmed'] == 0) $edit_link .= "&amp;msg=1-".$brewCategory."-".$row_log['brewSubCategory']; 
	
	$edit_link .= "&amp;view=".$brewCategory."-".$row_log['brewSubCategory'];
	$edit_link .= "' title='Edit ".$row_log['brewName']."'>Edit</a>&nbsp;&nbsp;";
	
	// Print Forms
	$alt_title = "";
	$alt_title .= "Print ";
	if ((!NHC) && (($_SESSION['prefsEntryForm'] == "B") || ($_SESSION['prefsEntryForm'] == "M") || ($_SESSION['prefsEntryForm'] == "U") || ($_SESSION['prefsEntryForm'] == "N"))) $alt_title .= "Entry Form and ";
	$alt_title .= "Bottle Labels ";
	$alt_title .= "for ".$row_log['brewName'];
	$link_text = "";
	$link_text .= "Print ";
	if ((!NHC) && (($_SESSION['prefsEntryForm'] == "B") || ($_SESSION['prefsEntryForm'] == "M") || ($_SESSION['prefsEntryForm'] == "U") || ($_SESSION['prefsEntryForm'] == "N"))) $link_text .= "Entry Form/"; 
	$link_text .= "Bottle Labels";
	$print_forms_link =  build_action_link("printer",$base_url,$_SESSION['user_id'],$go,"delete",$filter,$row_log['id'],$brewing_db_table,$alt_title,2,$link_text);
	
	// Print Recipe
	$print_recipe_link = "<span class='icon'><img src='".$base_url."images/printer.png'  border='0' alt='Print Recipe Form for ".$row_log['brewName']."' title='Print Recipe for ".$row_log['brewName']."'></span><a id='modal_window_link' href='".$base_url."output/entry.php?go=recipe&amp;id=".$row_log['id']."&amp;bid=".$_SESSION['brewerID']."' title='Print Recipe Form for ".$row_log['brewName']."'>Print Recipe</a>&nbsp;&nbsp;";
	
	if ($comp_entry_limit) $warning_append = "\nAlso, you will not be able to add another entry since the entry limit for the competition has been reached. Click Cancel in this box and then edit the entry instead if you wish to keep it."; else $warning_append = "";
	
	if ($entry_window_open == 1) $delete_link = build_action_link("bin_closed",$base_url,$section,$go,"delete",$filter,$row_log['id'],$brewing_db_table,"Delete ".$row_log['brewName']."? This cannot be undone. ".$warning_append,1,"Delete");

	if ((judging_date_return() > 0) && ($action != "print")) {
		
		$entry_output .= "<td class='dataList' nowrap='nowrap'>";
		if (($registration_open == 1) || ($entry_window_open == 1)) $entry_output .= $edit_link;
		if (pay_to_print($_SESSION['prefsPayToPrint'],$row_log['brewPaid'])) $entry_output .= $print_forms_link;
		
		if ((NHC) && ($prefix == "final_")) $entry_output .= $print_recipe_link;
		if ($row_log['brewPaid'] != 1) $entry_output .= $delete_link;
		$entry_output .= "</td>";
		
	}
	
	// Display the edit link for NHC final round after judging has taken place
	// Necessary to gather recipe data for first place winners in the final round
	if ((judging_date_return() == 0) && ($action != "print")) {
		
		$entry_output .= "<td class='dataList' nowrap='nowrap'>";
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
<table class="dataTable" id="sortable">
<thead>
 <tr>
  	<th class="dataHeading bdr1B" width="5%">Entry #</th>
  	<th class="dataHeading bdr1B" width="15%">Entry Name</th>
  	<th class="dataHeading bdr1B" width="15%">Style</th>
  	<th class="dataHeading bdr1B" width="8%">Confirmed?</th> 
  	<th class="dataHeading bdr1B" width="8%">Paid?</th> 
    <th class="dataHeading bdr1B" width="12%">Updated</th>
  	<?php if ($show_scores) { ?>
  	<th class="dataHeading bdr1B" width="10%">Score</th>
    <th class="dataHeading bdr1B" width="10%">Mini-BOS?</th>
  	<th class="dataHeading bdr1B" width="10%">Winner?</th>
  	<?php } ?>
    <?php if ($action != "print") { ?>
  	<th class="dataHeading bdr1B">Actions</th>
    <?php } ?>
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