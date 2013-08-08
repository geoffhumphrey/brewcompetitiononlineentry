<?php
/**
 * Module:      sponsors.sec.php 
 * Description: This module displays sponsors information housed in the
 *              sponsors database table. 
 * 
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
	$page_info1 = "";
	$header1_2 = "";
	$page_info2 = "";
	
	etc., etc., etc.

 * ---------------- END Rebuild Info --------------------- */

include(DB.'sponsors.db.php');

$print_page_link = "<p><span class='icon'><img src='".$base_url."images/printer.png' border='0' alt='Print' title='Print' /></span><a id='modal_window_link' class='data' href='".$base_url."output/print.php?section=".$section."&amp;action=print' title='Print'>Print This Page</a></p>";
$competition_logo = "<img src='".$base_url."user_images/".$_SESSION['contestLogo']."' width='".$_SESSION['prefsCompLogoSize']."' style='float:right; padding: 5px 0 5px 5px' alt='Competition Logo' title='Competition Logo' />";

$primary_page_info = "";
$page_info1 = "";
		
$sponsors_endRow = 0;
if ($action == "print") $sponsors_columns = 3; else $sponsors_columns = 4;  // number of columns
$sponsors_hloopRow1 = 0; // first row flag

$page_info1 .= "<table>";
$page_info1 .= "<tr>";
	
do {
	
	if (($sponsors_endRow == 0) && ($sponsors_hloopRow1++ != 0)) $page_info1 .= "<tr>";
	$page_info1 .= "<td class='looper_large'>";
	$page_info1 .= "<p>";
	if ($row_sponsors['sponsorURL'] != "") $page_info1 .= "<a href='".$row_sponsors['sponsorURL']."' target='_blank'>".$row_sponsors['sponsorName']."</a>";
	else $page_info1 .= $row_sponsors['sponsorName'];
	if ($row_sponsors['sponsorLocation'] != "") $page_info1 .= "<br>".$row_sponsors['sponsorLocation'];
	$page_info1 .= "</p>";
	$page_info1 .= "<p>";
	
	$page_info1 .= "<img src='"; 
	if (($row_sponsors['sponsorImage'] != "") && (file_exists($_SERVER['DOCUMENT_ROOT'].$sub_directory.'/user_images/'.$row_sponsors['sponsorImage']))) $page_info1 .= $base_url."user_images/".$row_sponsors['sponsorImage']; 
	else $page_info1 .= $base_url."images/no_image.png";
	$page_info1 .= sprintf("' width='%s' border='0' alt='".$row_sponsors['sponsorName']."Logo' title='".$row_sponsors['sponsorName']."Logo' />",$_SESSION['prefsSponsorLogoSize']);
	$page_info1 .= "</p>";
	if ($row_sponsors['sponsorText'] != "") $page_info1 .= "<p>".$row_sponsors['sponsorText']."</p>";
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


// --------------------------------------------------------------
// Display
// --------------------------------------------------------------
if (($action != "print") && ($msg != "default")) echo $msg_output;
if ((($_SESSION['contestLogo'] != "") && (file_exists($_SERVER['DOCUMENT_ROOT'].$sub_directory.'/user_images/'.$_SESSION['contestLogo']))) && ((judging_date_return() > 0) || (NHC))) echo $competition_logo;
if ($action != "print") echo $print_page_link;

echo $page_info1;
?>


