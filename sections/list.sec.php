<?php 
/**
 * Module:      list.sec.php 
 * Description: This module displays user-related data including personal information,
 *              judging/stewarding information, and the participant's associated entries. 
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


$user_remaining_entries = 0;
if (!empty($row_limits['prefsUserEntryLimit'])) $user_remaining_entries = ($row_limits['prefsUserEntryLimit'] - $totalRows_log);
else $user_remaining_entries = 1;

function pay_to_print($prefs_pay,$entry_paid) { 
	if (($prefs_pay == "Y") && ($entry_paid == "1")) return TRUE;
	elseif (($prefs_pay == "Y") && ($entry_paid == "0")) return FALSE;
	elseif ($prefs_pay == "N") return TRUE;
}
 
if (NHC) {
	function certificate_type($score) {
		if (($score >= 25) && ($score <= 29.9)) $return = "Bronze Certificate";
		elseif (($score >= 30) && ($score <= 37.9))	$return = "Silver Certificate";
		elseif (($score >= 38) && ($score <= 50))	$return = "Gold Certificate";
		return $return;
	}	
}

$judging_date = judging_date_return();
$total_not_paid = total_not_paid_brewer($_SESSION['user_id']);

// Call the brewer's info information
include (SECTIONS.'brewer_info.sec.php');

// Call the brewer's entry information
if ($show_entires) include (SECTIONS.'brewer_entries.sec.php');  
?>