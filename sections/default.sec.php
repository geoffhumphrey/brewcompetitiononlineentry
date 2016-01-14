<?php
/**
 * Module:      default.sec.php
 * Description: This module houses the intallation's landing page that includes
 *              information about the competition, registration dates/info, and 
 *              winner display after all judging dates have passed.
 */
 
/* ---------------- PUBLIC Pages Rebuild Info ---------------------

Beginning with the 1.3.0 release, an effort was begun to separate the programming
layer from the presentation layer for all scripts with this header.

All Public pages have certain variables in common that build the page:
  
	$primary_page_info = any information related to the page
	
	$header1_X = an <h2> header on the page
	$header2_X = an <h3> subheader on the page
	
	$page_infoX = the bulk of the information on the page.
	$print_page_link = the "Print This Page" link
	$competition_logo = display of the competition's logo
	
	$labelX = the various labels in a table or on a form
	$messageX = various messages to display
	
	$print_page_link = "<p><span class='icon'><img src='".$base_url."images/printer.png' border='0' alt='Print' title='Print' /></span><a id='modal_window_link' class='data' href='".$base_url."output/print.php?section=".$section."&amp;action=print' title='Print'>Print This Page</a></p>";
	$competition_logo = "<img src='".$base_url."user_images/".$_SESSION['contestLogo']."' width='".$_SESSION['prefsCompLogoSize']."' style='float:right; padding: 5px 0 5px 5px' alt='Competition Logo' title='Competition Logo' />";
	
Declare all variables empty at the top of the script. Add on later...
	$primary_page_info = "";
	$header1_1 = "";
	$page_info20 = "";
	$header1_20 = "";
	$page_info20 = "";
	
	etc., etc., etc.

 * ---------------- END Rebuild Info --------------------- */

include(DB.'dropoff.db.php');
include(DB.'sponsors.db.php');
include(DB.'contacts.db.php');

$delay = $_SESSION['prefsWinnerDelay'] * 3600;
$primary_page_info = "";
$page_info = "";
$page_info20 = "";
$header1_10 = "";
$header1_20 = "";

$message1 = "<div class=\"alert alert-warning\">No drop-off locations have been specified. <a href='index.php?section=admin&amp;action=add&amp;go=dropoff'>Add a drop-off location</a>?</div>";
$message2 = "<div class=\"alert alert-warning\">No judging dates/locations have been specified. <a href='index.php?section=admin&amp;action=add&amp;go=judging'>Add a judging location</a>?</div>";





if ((judging_date_return() == 0) && ($registration_open == "2")) {
	
	include ('judge_closed.sec.php'); 
	include (DB.'winners.db.php');
	
	$style_types_active = styles_active(1);
	
	if ($style_types_active > 0) {
		$header1_10 .= "<h2>Best of Show Winners";
		if ($section == "past_winners") $header1_10 .= ": ".$trimmed; 
		$header1_10 .= "</h2>";
	}
	
	$header1_20 .= "<h2>Winning Entries";
	if ($section == "past_winners") $header1_20 .= ": ".$trimmed;
	$header1_20 .= "</h2>";

	$page_info .= sprintf("<h2>Winning Entries</h2><p>Winners will be posted on or after %s.</p>",getTimeZoneDateTime($_SESSION['prefsTimeZone'], ($row_check['judgingDate']+$delay), $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time"));
	
} // end if ((judging_date_return() == 0) && ($registration_open == "2"))

else {
	
	if ($logged_in) $primary_page_info .= sprintf("<p class='lead'>Welcome %s! <small>View your account information <a href='%s' data-toggle='tooltip' title='See your account details and list of entries'>here</a>.</small></p>",$_SESSION['brewerFirstName'],build_public_url("list","default","default","default",$sef,$base_url));
	$primary_page_info .= "<p class='lead'>";
	$primary_page_info .= sprintf("Thank you for your interest in the %s organized by ",$_SESSION['contestName']);
	if ($_SESSION['contestHostWebsite'] != "") $primary_page_info .= sprintf("<a href='%s' target='_blank'>%s</a>",$_SESSION['contestHostWebsite'],$_SESSION['contestHost']);
	else $primary_page_info .= $_SESSION['contestHost'];
	if (!empty($_SESSION['contestHostLocation'])) $primary_page_info .= sprintf(", %s",$_SESSION['contestHostLocation']);
	$primary_page_info .= ".</p>";
	
	if (!isset($_SESSION['loginUsername'])) {
		$page_info .= "<p class='lead'><small>You only need to register your information once and can return to this site to enter more brews or edit the brews you've entered.";
		if ($_SESSION['prefsPaypal'] == "Y") $page_info .= " You can even pay your entry fees online if you wish.";
		$page_info .= "</small></p>";
	}
	
	$contact_count = get_contact_count();
	// Competition Officials
	if ($contact_count > 0) {
		if ($contact_count == 1) $header1_10 .= "<a name='officials'></a><h2>Competition Official</h2>";
		else $header1_10 .= "<a name='officials'></a><h2>Competition Officials</h2>";
		if ($action != "print") $page_info10 .= sprintf("<p>You can send an email to any of the following individuals via the <a href='%s'>Contact</a> section.</p>",build_public_url("contact","default","default","default",$sef,$base_url));
		$page_info10 .= "<ul>";
		do {
			$page_info10 .= "<li>";
			$page_info10 .= $row_contact['contactFirstName']." ".$row_contact['contactLastName']." &mdash; ".$row_contact['contactPosition']; 
			if ($action == "print") $page_info10 .= " (".$row_contact['contactEmail'].")";
			$page_info10 .= "</li>";
		} while ($row_contact = mysql_fetch_assoc($contact));
		$page_info10 .= "</ul>";
	}
	
	if (($_SESSION['prefsSponsors'] == "Y") && ($totalRows_sponsors > 0)) {
		$header1_30 = "<h1>Sponsors</h1>";
		 $page_info30 .= sprintf("<p>%s is proud to have the following ",$_SESSION['contestHost']);
		if ($_SESSION['prefsSponsorLogos'] == "Y") $page_info30 .= sprintf("<a href='%s'>sponsors</a>",build_public_url("sponsors","default","default","default",$sef,$base_url)); else $page_info20 .= "sponsors";
        $page_info30 .= sprintf(" for the %s.",$_SESSION['contestName']); 
	}
}



// --------------------------------------------------------------
// Display
// --------------------------------------------------------------

//if ($action != "print") echo $print_page_link;

if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= "1") && ($section == "admin")) { 
	if ($totalRows_dropoff == 0) echo $message1;
	if ($totalRows_judging == 0) echo $message2;
} 

echo $primary_page_info;
//echo $totalRowsSponsors;

if ((judging_date_return() == 0) && ($registration_open == "2")) { 
	
	if ($_SESSION['prefsDisplayWinners'] == "Y") {
		
		if (judging_winner_display($delay)) {
			
			if (((NHC) && ($prefix == "final_")) || (!NHC)) { 
				echo $header1_1;
				include (SECTIONS.'bos.sec.php');  
				} 
				
			echo $header1_20;
			if ($_SESSION['prefsWinnerMethod'] == "1") include (SECTIONS.'winners_category.sec.php'); 
			elseif ($_SESSION['prefsWinnerMethod'] == "2") include (SECTIONS.'winners_subcategory.sec.php'); 
			else include (SECTIONS.'winners.sec.php'); 
		}
		
		else {
			echo $page_info;
		}
	} 
}

// If registration or entry window still open and the judging dates have not passed
else { 
	echo $page_info;
	
	
	if (($registration_open == "2") || (($total_entries > $row_limits['prefsEntryLimit']))) include(SECTIONS.'reg_closed.sec.php');
	else include('reg_open.sec.php');
	
	// Display Competition Official(s)
	echo $header1_10;
	echo $page_info10;
	
	if (($_SESSION['prefsSponsors'] == "Y") && ($totalRows_sponsors > 0)) {
		echo $header1_30;
		echo $page_info30;
		include(SECTIONS.'sponsors.sec.php');
	}
}

?>