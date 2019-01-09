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
	$warningX = any warnings

	$primary_page_info = any information related to the page

	$header1_X = an <h2> header on the page
	$header2_X = an <h3> subheader on the page

	$page_infoX = the bulk of the information on the page.
	$help_page_link = link to the appropriate page on help.brewcompetition.com
	$print_page_link = the "Print This Page" link
	$competition_logo = display of the competition's logo

	$labelX = the various labels in a table or on a form
	$messageX = various messages to display

	$print_page_link = "<p><span class='icon'><img src='".$base_url."images/printer.png' border='0' alt='Print' title='Print' /></span><a id='modal_window_link' class='data' href='".$base_url."output/print.php?section=".$section."&amp;action=print' title='Print'>Print This Page</a></p>";
	$competition_logo = "<img src='".$base_url."user_images/".$_SESSION['contestLogo']."' width='".$_SESSION['prefsCompLogoSize']."' style='float:right; padding: 5px 0 5px 5px' alt='Competition Logo' title='Competition Logo' />";

Declare all variables empty at the top of the script. Add on later...
	$warning1 = "";
	$primary_page_info = "";
	$header1_1 = "";
	$page_info1 = "";
	$header1_2 = "";
	$page_info2 = "";

	etc., etc., etc.
 * ---------------- END Rebuild Info --------------------- */

include (DB.'sponsors.db.php');
$page_info1 = "";

$sponsors_endRow = 0;
$sponsors_columns = 4;  // number of columns
$sponsors_hloopRow1 = 0; // first row flag

// Define Bootstrap Row
$page_info1 .= "<section class=\"row\">";

do {
	if ($row_sponsors['sponsorEnable'] == "1") {

		if (($sponsors_endRow == 0) && ($sponsors_hloopRow1++ != 0)) $page_info1 .= "<section class=\"row\">";

		// Layout Column DIV
		$page_info1 .= "<section class=\"col-lg-3 col-md-6 col-sm-9 col-xs-12 bcoem-sponsor-container\">";

		// Sponsor Name
		$page_info1 .= "<section class=\"bcoem-sponsor-name\">";
		$page_info1 .= "<h5>";
		if ($row_sponsors['sponsorURL'] != "") $page_info1 .= sprintf("<a class=\"hide-loader\" href=\"%s\" data-toggle=\"tooltip\" title=\"%s %s\" target=\"_blank\">%s</a>",$row_sponsors['sponsorURL'],$row_sponsors['sponsorName'],strtolower($label_website),$row_sponsors['sponsorName']);
		else $page_info1 .= $row_sponsors['sponsorName'];
		$page_info1 .= "</h5>";
		$page_info1 .= "</section>";

		// Sponsor Location
		if ($row_sponsors['sponsorLocation'] != "") $page_info1 .= sprintf("<section class=\"bcoem-sponsor-location\">%s</section>",$row_sponsors['sponsorLocation']);
		else $page_info1 .= "<section class=\"bcoem-sponsor-location\">&nbsp;</section>"; // provides uniformity in display

		if ($_SESSION['prefsSponsorLogos'] == "Y") {
		// Sponsor Image
			$page_info1 .= "<img class=\"responsive-image img-thumbnail\" src=\"";
			if (($row_sponsors['sponsorImage'] != "") && (file_exists($_SERVER['DOCUMENT_ROOT'].$sub_directory."/user_images/".$row_sponsors['sponsorImage']))) $page_info1 .= $base_url."user_images/".$row_sponsors['sponsorImage'];
			else $page_info1 .= $base_url."images/no_image.png";
			$page_info1 .= sprintf("\" border=\"0\" alt=\"".$row_sponsors['sponsorName']."\" title=\"".$row_sponsors['sponsorName']."\" />");
		}

		// Sponsor Info
		if ($row_sponsors['sponsorText'] != "") $page_info1 .= "<section class=\"bcoem-sponsor-text small\">".$row_sponsors['sponsorText']."</section>";

		// END Layout Column section
		$page_info1 .= "</section>";

		$sponsors_endRow++;

		if ($sponsors_endRow >= $sponsors_columns) {
			$page_info1 .= "</section>";
			$sponsors_endRow = 0;
		}
	}

} while ($row_sponsors = mysqli_fetch_assoc($sponsors));

// Insert Empty Column if No Content Available
if ($sponsors_endRow != 0) {
	while ($sponsors_endRow < $sponsors_columns) {
		$page_info1 .= "<section class=\"col-lg-3 col-md-3 col-sm-3\">&nbsp;</section>";
		$sponsors_endRow++;
	}
}

// End Bootstrap Row
$page_info1 .= "</section>";

// --------------------------------------------------------------
// Display
// --------------------------------------------------------------

echo $page_info1;
?>
