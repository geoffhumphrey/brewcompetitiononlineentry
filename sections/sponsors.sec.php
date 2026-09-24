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

foreach ($rows_sponsors as $row_sponsors) {
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
			// Hotlinked logos (issue #371) get an onerror fallback to the same
			// no_image.png placeholder used when a local upload is missing - a
			// dead/unreachable external URL can only be detected client-side.
			$no_image_src = $images_url."no_image.png";
			$sponsor_logo_onerror = "";

			if (!empty($row_sponsors['sponsorImageURL'])) {
				// sponsorImageURL is sterilize()+purify()'d at save time - safe in text
				// context, but still needs h() inside an attribute like src=.
				$sponsor_logo_src = h($row_sponsors['sponsorImageURL']);
				$sponsor_logo_onerror = " onerror=\"this.onerror=null;this.src='".$no_image_src."';\"";
			}
			elseif (($row_sponsors['sponsorImage'] != "") && (file_exists(USER_IMAGES.$row_sponsors['sponsorImage']))) $sponsor_logo_src = $base_url."user_images/".$row_sponsors['sponsorImage'];
			else $sponsor_logo_src = $no_image_src;

			$page_info1 .= "<img class=\"responsive-image img-thumbnail\" src=\"".$sponsor_logo_src."\"".$sponsor_logo_onerror." border=\"0\" alt=\"".h($row_sponsors['sponsorName'])."\" title=\"".h($row_sponsors['sponsorName'])."\" />";
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

}

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
