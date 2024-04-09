<?php
/**
 * Module:      sponsors.sec.php
 * Description: This module displays sponsors information housed in the
 *              sponsors database table.
 *
 */

/*
// Redirect if directly accessed
if ((!isset($_SESSION['prefs'.$prefix_session])) || ((isset($_SESSION['prefs'.$prefix_session])) && (!isset($base_url)))) {
    $redirect = "../../index.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}
*/

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
			if (($row_sponsors['sponsorImage'] != "") && (file_exists(USER_IMAGES.$row_sponsors['sponsorImage']))) $page_info1 .= $base_url."user_images/".$row_sponsors['sponsorImage'];
			else $page_info1 .= $images_url."no_image.png";
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
