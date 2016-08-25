<?php 
/**
 * Module:      entry_info.sec.php
 * Description: This module houses public-facing information including entry.
 *              requirements, dropoff, shipping, and judging locations, etc.
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

 
include(DB.'dropoff.db.php');
include(DB.'judging_locations.db.php');
include(DB.'styles.db.php');
include(DB.'entry_info.db.php');

$primary_page_info = "";
$header1_1 = "";
$page_info1 = "";
$header1_2 = "";
$page_info2 = "";
$header1_3 = "";
$page_info3 = "";
$header1_4 = "";
$page_info4 = "";
$header1_5 = "";
$page_info5 = "";
$header1_6 = "";
$page_info6 = "";
$header1_7 = "";
$page_info7 = "";
$header1_8 = "";
$page_info8 = "";
$header1_9 = "";
$page_info9 = "";
$header1_10 = "";
$page_info10 = "";
$header1_11 = "";
$page_info11 = "";
$header1_12 = "";
$page_info12 = "";
$header1_13 = "";
$page_info13 = "";
$header1_14 = "";
$page_info14 = "";
$header1_15 = "";
$page_info15 = "";
$header1_16 = "";
$page_info16 = "";


// Registration Window
if (!$logged_in) {
	$header1_2 .= sprintf("<a name=\"reg_window\"></a><h2>%s</h2>",$label_account_registration);
	if ($registration_open == 0) $page_info2 .= sprintf("<p>%s <strong class=\"text-success\">%s</strong> %s <strong class=\"text-success\">%s</strong>.</p><p>%s <strong class=\"text-success\">%s</strong> %s <strong class=\"text-success\">%s</strong>.</p>", $entry_info_text_000, $reg_open, $entry_info_text_001, $reg_closed, $entry_info_text_002, $judge_open, $entry_info_text_001, $judge_closed);
	elseif ($registration_open == 1) $page_info2 .= sprintf("<p>%s <strong class=\"text-success\">%s</strong>.</p><p>%s <strong class=\"text-success\">%s</strong>.</p>", $entry_info_text_004, $reg_closed, $entry_info_text_005, $judge_closed);
	elseif (($registration_open == 2) && ($judge_window_open == 1)) $page_info2 .= sprintf("%s <strong class=\"text-success\">%s</strong> %s %s.", $entry_info_text_006,$entry_info_text_007,$entry_info_text_008, $judge_closed); 
	else $page_info2 .= sprintf("<p>%s</p>",$entry_info_text_009);
}
else $page_info2 .= sprintf("<p class=\"lead\">%s %s! <small><a href=\"%s\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"%s\">%s</a></small></p>",$entry_info_text_010,$_SESSION['brewerFirstName'],build_public_url("list","default","default","default",$sef,$base_url),$entry_info_text_011,$entry_info_text_012);

// Entry Window
$header1_3 .= sprintf("<a name=\"entry_window\"></a><h2>%s</h2>",$label_entry_registration);
if ($entry_window_open == 0) $page_info3 .= sprintf("<p>%s <strong class=\"text-success\">%s</strong> %s <strong class=\"text-success\">%s</strong>.</p>",$entry_info_text_014,$entry_info_text_001,$entry_open,$entry_info_text_001,$entry_closed);
elseif ($entry_window_open == 1) $page_info3 .= sprintf("<p>%s <strong class=\"text-success\">%s</strong>.</p>",$entry_info_text_015,$entry_closed);
else $page_info3 .= sprintf("<p>%s</p>",$entry_info_text_016);


if ($entry_window_open < 2) {
	
	// Entry Fees
	$header1_4 .= sprintf("<a name=\"entry\"></a><h2>%s</h2>",$label_entry_fees);
	$page_info4 .= sprintf("<p>%s%s (%s) %s. ",$currency_symbol,number_format($_SESSION['contestEntryFee'],2),$currency_code,$entry_info_text_003);
	if ($_SESSION['contestEntryFeeDiscount'] == "Y") $page_info4 .= sprintf("%s%s %s %s %s. ",$currency_symbol,number_format($_SESSION['contestEntryFee2'],2),$entry_info_text_013,addOrdinalNumberSuffix($_SESSION['contestEntryFeeDiscountNum']),strtolower($label_entry));
	if ($_SESSION['contestEntryCap'] != "") $page_info4 .= sprintf("%s%s %s ",$currency_symbol,number_format($_SESSION['contestEntryCap'],2),$entry_info_text_017);
	if (NHC) $page_info4 .= sprintf("%s%s %s",$currency_symbol,number_format($_SESSION['contestEntryFeePasswordNum'],2),$entry_info_text_018);
	$page_info4 .= "</p>";
	
	// Entry Limit
	if ($row_limits['prefsEntryLimit'] != "") {
		$header1_5 .= sprintf("<a name=\"entry_limit\"></a><h2>%s</h2>",$label_entry_limit);
		$page_info5 .= sprintf("<p>%s %s %s</p>",$entry_info_text_019,$row_limits['prefsEntryLimit'],$entry_info_text_020);
	}
	
	if ((!empty($row_limits['prefsUserEntryLimit'])) || (!empty($row_limits['prefsUserSubCatLimit'])) || (!empty($row_limits['prefsUSCLExLimit']))) {
		$header1_16 .= sprintf("<h2>%s</h2>",$label_entry_per_entrant);
		
		if (!empty($row_limits['prefsUserEntryLimit'])) {
			if ($row_limits['prefsUserEntryLimit'] == 1) $page_info16 .= sprintf("<p>%s %s %s.</p>",$entry_info_text_021,$row_limits['prefsUserEntryLimit'],$entry_info_text_022);
			else $page_info16 .= sprintf("<p>%s %s %s.</p>",$entry_info_text_021,$row_limits['prefsUserEntryLimit'],$entry_info_text_023);
		}
		
		if (!empty($row_limits['prefsUserSubCatLimit'])) { 
			$page_info16 .= "<p>";
			if ($row_limits['prefsUserSubCatLimit'] == 1) $page_info16 .= sprintf("%s %s %s ",$entry_info_text_021,$row_limits['prefsUserSubCatLimit'],$entry_info_text_024);
			else $page_info16 .= sprintf("%s %s %s ",$entry_info_text_021,$row_limits['prefsUserSubCatLimit'],$entry_info_text_025);
			if (!empty($row_limits['prefsUSCLExLimit'])) $page_info16 .= sprintf(" &ndash; %s",$entry_info_text_026);
			$page_info16 .= ".";
			$page_info16 .= "</p>";
	
		}
		
		if (!empty($row_limits['prefsUSCLExLimit'])) { 
		$excepted_styles = explode(",",$row_limits['prefsUSCLEx']);
		if (count($excepted_styles) == 1) $sub = $entry_info_text_027; else $sub = $entry_info_text_028;
			if ($row_limits['prefsUSCLExLimit'] == 1) $page_info16 .= sprintf("<p>%s to %s %s %s: </p>",$entry_info_text_021,$row_limits['prefsUSCLExLimit'],$entry_info_text_029,$sub);
			else $page_info16 .= sprintf("<p>%s %s %s %s: </p>",$entry_info_text_021,$row_limits['prefsUSCLExLimit'],$entry_info_text_030,$sub);
			$page_info16 .= "<div class=\"row\">";
			$page_info16 .= "<div class=\"col col-lg-6 col-md-8 col-sm-10 col-xs-12\">";
			$page_info16 .= style_convert($row_limits['prefsUSCLEx'],"7");
			$page_info16 .= "</div>";
			$page_info16 .= "</div>";
	
		}
		
	}
	
	// Payment
	$header1_6 .= sprintf("<a name=\"payment\"></a><h2>%s</h2>",$label_pay);
	$page_info6 .= sprintf("<p>%s</p>",$entry_info_text_031);
	$page_info6 .= "<ul>";
	if ($_SESSION['prefsCash'] == "Y") $page_info6 .= sprintf("<li>%s</li>",$entry_info_text_032);
	if ($_SESSION['prefsCheck'] == "Y") $page_info6 .= sprintf("<li>%s <em>%s</em></li>",$entry_info_text_033,$_SESSION['prefsCheckPayee']);
	if ($_SESSION['prefsPaypal'] == "Y") $page_info6 .= sprintf("<li>%s</li>",$entry_info_text_034);
	//if ($_SESSION['prefsGoogle'] == "Y") $page_info6 .= "<li>Google Wallet</li>"; 
	$page_info6 .= "</ul>";

}

$header1_7 .= sprintf("<h2>%s</h2>",$label_admin_judging_loc);
if ($totalRows_judging == 0) $page_info7 .= sprintf("<p>%s</p>",$entry_info_text_035);
else {
	do {
		$page_info7 .= "<p>";
		if ($row_judging['judgingLocName'] != "") $page_info7 .= "<strong>".$row_judging['judgingLocName']."</strong>";
		if ($row_judging['judgingLocation'] != "") $page_info7 .= "<br><a href=\"".$base_url."output/maps.output.php?section=driving&amp;id=".str_replace(' ', '+', $row_judging['judgingLocation'])."\" target=\"_blank\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Map to ".$row_judging['judgingLocName']."\">".$row_judging['judgingLocation']."</a> <span class=\"fa fa-lg fa-map-marker\"></span>";
		else $page_info7 .= $row_judging['judgingLocName'];
		if ($row_judging['judgingDate'] != "") $page_info7 .=  "<br />".getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");
		$page_info7 .= "</p>";
	} while ($row_judging = mysqli_fetch_assoc($judging));
}


// Categories Accepted
$header1_8 .= "";
$page_info8 .= "";

if ($entry_window_open < 2) $header1_8 .= sprintf("<a name=\"categories\"></a><h2>%s %s</h2>",str_replace("2"," 2",$row_styles['brewStyleVersion']),$label_categories_accepted);
else $header1_8 .= sprintf("<a name=\"categories\"></a><h2>%s %s</h2>",str_replace("2"," 2",$row_styles['brewStyleVersion']),$label_judging_categories);
$page_info8 .= "<table class=\"table table-striped table-bordered table-responsive\">";
$page_info8 .= "<tr>"; 

$styles_endRow = 0;
$styles_columns = 3;   // number of columns
$styles_hloopRow1 = 0; // first row flag

do {
	if (($styles_endRow == 0) && ($styles_hloopRow1++ != 0)) $page_info8 .= "<tr>";
	
	$page_info8 .= "<td width=\"33%\">";
	$page_info8 .= ltrim($row_styles['brewStyleGroup'], "0").$row_styles['brewStyleNum']." ".$row_styles['brewStyle']; 
	if ($row_styles['brewStyleOwn'] == "custom") $page_info8 .= " (Custom Style)";
	$page_info8 .= "</td>";
	
	$styles_endRow++;
	if ($styles_endRow >= $styles_columns) { $styles_endRow = 0; }
		
} while ($row_styles = mysqli_fetch_assoc($styles));

if ($styles_endRow != 0) {
		while ($styles_endRow < $styles_columns) {
			$page_info8 .= "<td>&nbsp;</td>";
			$styles_endRow++;
		}
	$page_info8 .= "</tr>"; 
}


$page_info8 .= "</table>";

// Show bottle acceptance, shipping location, and dropoff locations if open

// Bottle Acceptance
if ((isset($row_contest_info['contestBottles'])) && (($dropoff_window_open < 2) || ($shipping_window_open < 2))) {
	$header1_9 .= sprintf("<a name=\"bottle\"></a><h2>%s</h2>",$label_entry_acceptance_rules);
	$page_info9 .= $row_contest_info['contestBottles'];
}


// Shipping Locations
if ((isset($_SESSION['contestShippingAddress'])) && ($shipping_window_open < 2)) {
	$header1_10 .= sprintf("<a name=\"shipping\"></a><h2>%s</h2>",$label_shipping_info);
	$page_info10 .= sprintf("<p>%s <strong class=\"text-success\">%s</strong> through <strong class=\"text-success\">%s</strong>.</p>",$entry_info_text_036,$entry_info_text_001,$shipping_open,$shipping_closed);
	$page_info10 .= sprintf("<p>%s</p>",$entry_info_text_037);
	$page_info10 .= "<p>";
	$page_info10 .= $_SESSION['contestShippingName'];
	$page_info10 .= "<br>";
	$page_info10 .= $_SESSION['contestShippingAddress'];
	$page_info10 .= "</p>";
    $page_info10 .= sprintf("<h3>%s</h3>",$label_packing_shipping);
    $page_info10 .= sprintf("<p><strong>%s</strong></p>",$entry_info_text_038);
	$page_info10 .= sprintf("<p>%s</p>",$entry_info_text_039);
    $page_info10 .= sprintf("<p>%s</p>",$entry_info_text_040);
    $page_info10 .= sprintf("<p>%s</p>",$entry_info_text_041);
    $page_info10 .= sprintf("<p>%s</p>",$entry_info_text_042);
}

// Drop Off
if (($totalRows_dropoff > 0) && ($dropoff_window_open < 2)) {
	if ($totalRows_dropoff == 1) $header1_11 .= "<a name=\"drop\"></a><h2>Drop Off Location</h2>";
	else $header1_11 .= "<a name=\"drop\"></a><h2>Drop Off Locations</h2>";
	$page_info11 .= sprintf("<p>Entry bottles accepted at our drop-off locations from <strong class=\"text-success\">%s</strong> through <strong class=\"text-success\">%s</strong>.</p>",$dropoff_open,$dropoff_closed);
	
	do {
		
		$page_info11 .= "<p>";
		if ($row_dropoff['dropLocationWebsite'] != "") $page_info11 .= sprintf("<a href=\"%s\" target=\"_blank\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Go to the ".$row_dropoff['dropLocationName']." website\"><strong>%s</strong></a> <span class=\"fa fa-lg fa-external-link\"></span>",$row_dropoff['dropLocationWebsite'],$row_dropoff['dropLocationName']);
		else $page_info11 .= sprintf("<strong>%s</strong>",$row_dropoff['dropLocationName']);
		$page_info11 .= "<br />";
		$page_info11 .= "<a href=\"".$base_url."output/maps.output.php?section=driving&amp;id=".str_replace(' ', '+', $row_dropoff['dropLocation'])."\" target=\"_blank\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Map to ".$row_dropoff['dropLocationName']."\">".$row_dropoff['dropLocation']."</a> <span class=\"fa fa-lg fa-map-marker\"></span>";
		$page_info11 .= "<br />";
		$page_info11 .= $row_dropoff['dropLocationPhone'];
		$page_info11 .= "<br />";
		if ($row_dropoff['dropLocationNotes'] != "") $page_info11 .= sprintf("*<em>%s</em>",$row_dropoff['dropLocationNotes']);
		$page_info11 .= "</p>";
	 } while ($row_dropoff = mysqli_fetch_assoc($dropoff));
}

// Best of Show
if (isset($row_contest_info['contestBOSAward'])) {
	$header1_12 .= sprintf("<a name=\"bos\"></a><h2>%s</h2>",$label_bos);
	$page_info12 .= $row_contest_info['contestBOSAward'];;
}

// Awards and Awards Ceremony Location
if (isset($row_contest_info['contestAwards'])) {
	$header1_13 .= sprintf("<a name=\"awards\"></a><h2>%s</h2>",$label_awards);
	$page_info13 .= $row_contest_info['contestAwards'];;
}

if (isset($_SESSION['contestAwardsLocName'])) {
	$header1_14 .= sprintf("<a name=\"ceremony\"></a><h2>%s</h2>",$label_awards_ceremony);
	$page_info14 .= "<p>";
	$page_info14 .= sprintf("<strong>%s</strong>",$_SESSION['contestAwardsLocName']);
	if ($_SESSION['contestAwardsLocation'] != "") $page_info14 .= sprintf("<br /><a href=\"".$base_url."output/maps.output.php?section=driving&amp;id=".str_replace(' ', '+', $_SESSION['contestAwardsLocation'])."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Map to ".$_SESSION['contestAwardsLocName']." \" target=\"_blank\">%s</a> <span class=\"fa fa-lg fa-map-marker\"></span>",$_SESSION['contestAwardsLocation']);
	if ($_SESSION['contestAwardsLocTime'] != "") $page_info14 .= sprintf("<br />%s",getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestAwardsLocTime'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time"));
	$page_info14 .= "</p>";
	
}

// Circuit Qualification
if (isset($row_contest_info['contestCircuit'])) {
	$header1_15 .= sprintf("<a name=\"circuit\"></a><h2>%s</h2>",$label_circuit);
	$page_info15 .= $row_contest_info['contestCircuit'];
}


// --------------------------------------------------------------
// Display
// --------------------------------------------------------------

// Display Registration Window
echo $header1_2;
echo $page_info2;

// Display Entry Window
echo $header1_3;
echo $page_info3;

// Display Entry Fees
echo $header1_4;
echo $page_info4;

// Display Categories Accepted
echo $header1_8;
echo $page_info8;

// Display Entry Limits
echo $header1_5;
echo $page_info5;

// Display Per Entrant Limit
echo $header1_16;
echo $page_info16;

// Display Payment Info
echo $header1_6;
echo $page_info6;

// Display Entry Acceptance Rules
echo $header1_9;
echo $page_info9;

// Display Drop Off Locations and Acceptance Dates
echo $header1_11;
echo $page_info11;

// Display Shipping Location and Acceptance Dates
echo $header1_10;
echo $page_info10;

// Display Judging Dates
echo $header1_7;
echo $page_info7;

// Display Best of Show
echo $header1_12;
echo $page_info12;

// Display Awards and Awards Ceremony Location
echo $header1_13;
echo $page_info13;
echo $header1_14;
echo $page_info14;

// Display Circuit Qualification
echo $header1_15;
echo $page_info15;

?>
