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

include (DB.'dropoff.db.php');
include (DB.'sponsors.db.php');
include (DB.'contacts.db.php');

$primary_page_info = "";
$page_info = "";
$page_info10 = "";
$page_info20 = "";
$page_info30 = "";
$header1_1 = "";
$header1_10 = "";
$header1_20 = "";
$header1_30 = "";

$message1 = sprintf("<div class=\"alert alert-warning\"><span class=\"fa fa-lg fa-exclamation-triangle\"> %s <a href='index.php?section=admin&amp;action=add&amp;go=dropoff'>%s</a></div>",$default_page_text_000,$default_page_text_001);
$message2 = sprintf("<div class=\"alert alert-warning\"><span class=\"fa fa-lg fa-exclamation-triangle\"> %s <a href='index.php?section=admin&amp;action=add&amp;go=judging'>%s</a></div>",$default_page_text_002,$default_page_text_003);


if ((judging_date_return() == 0) && ($registration_open == 2) && ($entry_window_open == 2)) {
	
	include (SECTIONS.'judge_closed.sec.php'); 
	include (DB.'winners.db.php');
	
	$style_types_active = styles_active(1);
	$bos_data_available = FALSE;
	
	if ($row_bos_scores['count'] > 0) $bos_data_available = TRUE;
	
	if ($style_types_active > 0) {
		$header1_10 .= "<h2>".$default_page_text_009;
		if ($section == "past_winners") $header1_10 .= ": ".$trimmed;
		
		if ($bos_data_available) {
			$header1_10 .= sprintf(" <a href=\"%soutput/export.output.php?section=results&amp;go=judging_scores_bos&amp;action=download&amp;filter=default&amp;view=pdf\" data-toggle=\"tooltip\" title=\"%s\"><span class=\"fa fa-file-pdf-o hidden-print\"></span></a> <a href=\"%soutput/export.output.php?section=results&amp;go=judging_scores_bos&amp;action=download&amp;filter=default&amp;view=html\" data-toggle=\"tooltip\" title=\"%s\"><span class=\"fa fa-file-code-o hidden-print\"></span></a>",$base_url,$default_page_text_018,$base_url,$default_page_text_019);
		}
		
		$header1_10 .= "</h2>";
		
		if (!$bos_data_available) $page_info10 .= "<p>".$winners_text_005."</p>";
	}
	
	$header1_20 .= "<h2>".$default_page_text_010;
	if ($section == "past_winners") $header1_20 .= ": ".$trimmed;
	$header1_20 .= sprintf(" <a href=\"%soutput/export.output.php?section=results&amp;go=judging_scores&amp;action=default&amp;filter=none&amp;view=pdf\" data-toggle=\"tooltip\" title=\"%s\"><span class=\"fa fa-file-pdf-o hidden-print\"></span></a> <a href=\"%soutput/export.output.php?section=results&amp;go=judging_scores&amp;action=default&amp;filter=none&amp;view=html\" data-toggle=\"tooltip\" title=\"%s\"><span class=\"fa fa-file-code-o hidden-print\"></span></a>",$base_url,$default_page_text_020,$base_url,$default_page_text_021);
	$header1_20 .= "</h2>";

	$page_info .= sprintf("<h2>%s</h2><p>%s %s.</p>",$default_page_text_004,$default_page_text_005,getTimeZoneDateTime($_SESSION['prefsTimeZone'], ($row_check['judgingDate']+$delay), $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time"));
	
} // end if ((judging_date_return() == 0) && ($registration_open == "2"))

else {
	
	if ($logged_in) $primary_page_info .= sprintf("<p class=\"lead\">%s %s! <small><a href=\"%s\" data-toggle=\"tooltip\" title=\"%s\">%s</a></small></p>",$default_page_text_006,$_SESSION['brewerFirstName'],build_public_url("list","default","default","default",$sef,$base_url),$default_page_text_007,$default_page_text_008);
	$primary_page_info .= "<p class='lead'>";
	$primary_page_info .= sprintf("%s %s %s ",$default_page_text_022, $_SESSION['contestName'], $default_page_text_023);
	if ($_SESSION['contestHostWebsite'] != "") $primary_page_info .= sprintf("<a href='%s' target='_blank'>%s</a>",$_SESSION['contestHostWebsite'],$_SESSION['contestHost']);
	else $primary_page_info .= $_SESSION['contestHost'];
	if (!empty($_SESSION['contestHostLocation'])) $primary_page_info .= sprintf(", %s",$_SESSION['contestHostLocation']);
	$primary_page_info .= ".</p>";
	
	if (!isset($_SESSION['loginUsername'])) {
		$page_info .= "<p class='lead'><small>".$default_page_text_011;
		if ($_SESSION['prefsPaypal'] == "Y") $page_info .= " ".$default_page_text_012;
		$page_info .= "</small></p>";
	}
	
	$contact_count = get_contact_count();
	// Competition Officials
	if ($contact_count > 0) {
		if ($contact_count == 1) $header1_10 .= "<a name='officials'></a><h2>".$default_page_text_013."</h2>";
		else $header1_10 .= "<a name='officials'></a><h2>".$default_page_text_014."</h2>";
		if ($action != "print") $page_info10 .= sprintf("<p>%s <a href='%s'>%s</a>.</p>",$default_page_text_015,build_public_url("contact","default","default","default",$sef,$base_url),$label_contact);
		$page_info10 .= "<ul>";
		do {
			$page_info10 .= "<li>";
			$page_info10 .= $row_contact['contactFirstName']." ".$row_contact['contactLastName']." &mdash; ".$row_contact['contactPosition']; 
			if ($action == "print") $page_info10 .= " (".$row_contact['contactEmail'].")";
			$page_info10 .= "</li>";
		} while ($row_contact = mysqli_fetch_assoc($contact));
		$page_info10 .= "</ul>";
	}
	
	if (($_SESSION['prefsSponsors'] == "Y") && ($totalRows_sponsors > 0)) {
		$header1_30 = "<h1>".$label_sponsors."</h1>";
		 $page_info30 .= sprintf("<p>%s %s ",$_SESSION['contestHost'],$default_page_text_016);
		if ($_SESSION['prefsSponsorLogos'] == "Y") $page_info30 .= sprintf("<a href='%s'>%s</a>",build_public_url("sponsors","default","default","default",$sef,$base_url),strtolower($label_sponsors)); else $page_info20 .= "sponsors";
        $page_info30 .= sprintf(" %s %s.",$default_page_text_017,$_SESSION['contestName']); 
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

if ((judging_date_return() == 0) && ($registration_open == 2) && ($entry_window_open == 2)) { 
	
	if ($_SESSION['prefsDisplayWinners'] == "Y") {
		
		if ($_SESSION['prefsProEdition'] == 1) $label_brewer = $label_organization; else $label_brewer = $label_brewer;
		
		include (DB.'score_count.db.php');
		
		if (judging_winner_display($delay)) {
			
			if (((NHC) && ($prefix == "final_")) || (!NHC)) {
				echo $header1_10;
				echo $page_info10;
				include (SECTIONS.'bos.sec.php');  
				} 
				
			echo $header1_20;
			if ($_SESSION['prefsWinnerMethod'] == "1") include (SECTIONS.'winners_category.sec.php'); 
			elseif ($_SESSION['prefsWinnerMethod'] == "2") include (SECTIONS.'winners_subcategory.sec.php'); 
			else include (SECTIONS.'winners.sec.php'); 
		}
		
		else {
			if (isset($page_info)) echo $page_info;
		}
	} 
}

// If registration or entry window still open and the judging dates have not passed
else { 
	echo $page_info;
	
	
	if ((($registration_open == 2) && ($entry_window_open == 2)) || ($comp_entry_limit) || ($comp_paid_entry_limit)) include (SECTIONS.'reg_closed.sec.php');
	else include('reg_open.sec.php');
	
	// Display Competition Official(s)
	echo $header1_10;
	echo $page_info10;
	
	if (($_SESSION['prefsSponsors'] == "Y") && ($totalRows_sponsors > 0)) {
		echo $header1_30;
		echo $page_info30;
		include (SECTIONS.'sponsors.sec.php');
	}
}

?>