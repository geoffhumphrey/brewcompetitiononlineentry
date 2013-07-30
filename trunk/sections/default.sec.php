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
  $header1_X = an <h2> header on the page
  $header2_X = an <h3> subheader on the page
  $primary_page_info = any information related to the page
  $print_page_link = the "Print This Page" link
  $page_infoX = the bulk of the information on the page.
  $competition_logo = display of the competition's logo
  
  $labelX = the various labels in a table or on a form
  $messageX = various messages to display

 * ---------------- END Rebuild Info --------------------- */

include(DB.'dropoff.db.php');
include(DB.'sponsors.db.php');
if (($action != "print") && ($msg != "default")) echo $msg_output;

$delay = $_SESSION['prefsWinnerDelay'] * 3600;

$print_page_link = "<p><span class='icon'><img src='".$base_url."images/printer.png' border='0' alt='Print' title='Print' /></span><a id='modal_window_link' class='data' href='".$base_url."output/print.php?section=".$section."&amp;action=print' title='Print Rules'>Print This Page</a></p>";
$competition_logo = "<img src='".$base_url."user_images/".$_SESSION['contestLogo']."' width='".$_SESSION['prefsCompLogoSize']."' style='float:right; padding: 5px 0 5px 5px' alt='Competition Logo' title='Competition Logo' />";
$page_info = "";
$page_info1 = "";
$header1_1 = "";
$header1_2 = "";

$message1 = "<div class=\"error\">No drop-off locations have been specified. <a href=\"index.php?section=admin&amp;action=add&amp;go=dropoff\">Add a drop-off location</a>?</div>";
$message2 = "<div class=\"error\">No judging dates/locations have been specified. <a href=\"index.php?section=admin&amp;action=add&amp;go=judging\">Add a judging location</a>?</div>";

$primary_page_info = "";
$primary_page_info .= "<p>Thank you for your interest in the ".$_SESSION['contestName']." organized by ";
if ($_SESSION['contestHostWebsite'] != "") $primary_page_info .= "<a href='".$_SESSION['contestHostWebsite']."' target='_blank'>".$_SESSION['contestHost']."</a>";
else $primary_page_info .= $_SESSION['contestHost'];
if ($_SESSION['contestHostLocation'] != "") $primary_page_info .= ", ".$_SESSION['contestHostLocation'];
$primary_page_info .= ".  Be sure to read the <a href='".build_public_url("rules","default","default",$sef,$base_url)."'>competition rules</a>.</p>";

if ((judging_date_return() == 0) && ($registration_open == "2")) {
	
	include ('judge_closed.sec.php'); 
	include (DB.'winners.db.php');
	
	$query_style_types_active = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE styleTypeBOS='Y'", $prefix."style_types");
	$style_types_active = mysql_query($query_style_types_active, $brewing) or die(mysql_error());
	$row_style_types_active = mysql_fetch_assoc($style_types_active);
	
	
	if ($row_style_types_active['count'] > 0) {
		$header1_1 .= "<h2>Best of Show Winners";
		if ($section == "past_winners") $header1_1 .= ": ".$trimmed; 
		if (($row_bos_scores['count'] > 0) && ($section == "default") && ($action != "print")) { 
			$header1_1 .= "<span class='icon'>&nbsp;<a href='".$base_url."output/results_download.php?section=admin&amp;go=judging_scores_bos&amp;action=download&amp;filter=default&amp;view=pdf'><img src='".$base_url."images/page_white_acrobat.png' border='0' title='Download a PDF of the Best of Show Winner List'/></a></span><span class='icon'><a href='".$base_url."output/results_download.php?section=admin&amp;go=judging_scores_bos&amp;action=download&amp;filter=default&amp;view=html'><img src='".$base_url."images/html.png' border='0' title='Download the Best of Show Winner List in HTML format'/></a></span>"; 
			} 
		$header1_1 .= "</h2>";
	}
	
	$header1_2 .= "<h2>Winning Entries";
	if ($section == "past_winners") $header1_2 .= ": ".$trimmed;
	if (($row_scores['count'] > 0) && ($section == "default") && ($action != "print")) { 
		$header1_2 .= "<span class='icon'>&nbsp;<a href='".$base_url."output/results_download.php?section=admin&amp;go=judging_scores&amp;action=default&amp;filter=none&amp;view=pdf'><img src='".$base_url."images/page_white_acrobat.png' border='0' title='Download a PDF of the Best of Show Winner List'/></a></span><span class='icon'><a href='".$base_url."output/results_download.php?section=admin&amp;go=judging_scores&amp;action=download&amp;filter=default&amp;view=html'><img src='".$base_url."images/html.png' border='0' title='Download the Best of Show Winner List in HTML format'/></a></span>"; 
		}
	$header1_2 .= "</h2>";

	$page_info .= "<h2>Winning Entries</h2><p>Winners will be posted on or after ".getTimeZoneDateTime($_SESSION['prefsTimeZone'], ($row_check['judgingDate']+$delay), $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time").".</p>";
	
} // end if ((judging_date_return() == 0) && ($registration_open == "2"))

else {
	
	if (!isset($_SESSION['loginUsername'])) {
		$page_info .= "<p>You only need to register your information once and can return to this site to enter more brews or edit the brews you've entered.";
		if ($_SESSION['prefsPaypal'] == "Y") $page_info .= " You can even pay your entry fees online if you wish.";
		$page_info .= "</p>";
	}
	
	if (($_SESSION['prefsSponsors'] == "Y") && ($totalRows_sponsors > 0)) {
		
		$header1_1 .= "<h2>Sponsors</h2>";
		$page_info1 .= "<p>".$_SESSION['contestHost']." is proud to have the following ";
		if ($_SESSION['prefsSponsorLogos'] == "Y") $page_info1 .= "<a href='".build_public_url("sponsors","default","default",$sef,$base_url)."'>sponsors</a>"; else $page_info1 .= "sponsors";
        $page_info1 .= " for the ".$_SESSION['contestName'].".";
		
		// Build Sponsor Display
		if ($_SESSION['prefsSponsorLogos'] == "Y") {
		
			$sponsors_endRow = 0;
			$sponsors_columns = 6; // number of columns
			$sponsors_hloopRow1 = 0; // first row flag
			
			$page_info1 .= "<table>";
			$page_info1 .= "<tr>";
				
			do {
				
				if (($sponsors_endRow == 0) && ($sponsors_hloopRow1++ != 0)) $page_info1 .= "<tr>";
				$page_info1 .= "<td class='looper'>";
				$page_info1 .= "<p>";
				if ($row_sponsors['sponsorURL'] != "") $page_info1 .= "<a href='".$row_sponsors['sponsorURL']."' target='_blank'>".$row_sponsors['sponsorName']."</a>";
				else $page_info1 .= $row_sponsors['sponsorName'];
				if ($row_sponsors['sponsorLocation'] != "") $page_info1 .= "<br>".$row_sponsors['sponsorLocation'];
				$page_info1 .= "</p>";
				$page_info1 .= "<p>";
				
				$page_info1 .= "<img src='"; 
				if (($row_sponsors['sponsorImage'] != "") && (file_exists($_SERVER['DOCUMENT_ROOT'].$sub_directory.'/user_images/'.$row_sponsors['sponsorImage']))) $page_info1 .= $base_url."user_images/".$row_sponsors['sponsorImage']; 
				else $page_info1 .= $base_url."images/no_image.png";
                $page_info1 .= "' width='100' border='0' alt='".$row_sponsors['sponsorName']."Logo' title='".$row_sponsors['sponsorName']."Logo' />";
				$page_info1 .= "</p>";
				$page_info1 .= "</td>";
				$sponsors_endRow++;
				
				if ($sponsors_endRow >= $sponsors_columns) {
					$page_info1 .= "</tr>";
					$sponsors_endRow = 0;
  				}
				
			} while ($row_sponsors = mysql_fetch_assoc($sponsors));

			if ($sponsors_endRow != 0) {
				while ($sponsors_endRow < $sponsors_columns) {
					$page_info1 .= "<td>&nbsp;</td>";
					$sponsors_endRow++;
				}
			}
	
			$page_info1 .= "</tr>";
			$page_info1 .= "</table>";
		
		}
		
		else {
		
			$page_info1 .= "<ul id='sponsor'>";
			do {
				$page_info1 .= "<li>";
				if ($row_sponsors['sponsorURL'] != "") $page_info1 .= "<a href='".$row_sponsors['sponsorURL']."' target='_blank'>".$row_sponsors['sponsorName']."</a>";
				else $page_info1 .= $row_sponsors['sponsorName'];
				$page_info1 .= "</li>";
			} while ($row_sponsors = mysql_fetch_assoc($sponsors)); 
			$page_info1 .= "</ul>";
		
		}
	
	}
	
}

// Display
if ((($_SESSION['contestLogo'] != "") && (file_exists($_SERVER['DOCUMENT_ROOT'].$sub_directory.'/user_images/'.$_SESSION['contestLogo']))) && ((judging_date_return() > 0) || (NHC))) echo $competition_logo;
if ($action != "print") echo $print_page_link;


if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= "1") && ($section == "admin")) { 
	if ($totalRows_dropoff == 0) echo $message1;
	if ($totalRows_judging == 0) echo $message2;
} 

echo $primary_page_info;
echo $totalRowsSponsors;

if ((judging_date_return() == 0) && ($registration_open == "2")) { 
	
	if ($_SESSION['prefsDisplayWinners'] == "Y") {
		
		if (judging_winner_display($delay)) {
			
			if (((NHC) && ($prefix == "final_")) || (!NHC)) { 
				echo $header1_1;
				include (SECTIONS.'bos.sec.php');  
				} 
				
			echo $header1_2;
			if ($_SESSION['prefsWinnerMethod'] == "1") include (SECTIONS.'winners_category.sec.php'); 
			elseif ($_SESSION['prefsWinnerMethod'] == "2") include (SECTIONS.'winners_subcategory.sec.php'); 
			else include (SECTIONS.'winners.sec.php'); 
		}
		
		else {
			$query_check = "SELECT judgingDate FROM $judging_locations_db_table ORDER BY judgingDate DESC LIMIT 1";
			$check = mysql_query($query_check, $brewing) or die(mysql_error());
			$row_check = mysql_fetch_assoc($check);
			echo $page_info;
		}
	} 
}

// If registration or entry window still open and the judging dates have not passed
else { 
	echo $page_info;
	if (($registration_open == "2") || (open_limit($totalRows_entry_count,$row_limits['prefsEntryLimit'],$registration_open))) include('reg_closed.sec.php');
	else include('reg_open.sec.php');
}

if ($_SESSION['prefsSponsors'] == "Y") {
	echo $header1_1;
	echo $page_info1;
}

?>
