<?php 
session_start(); 
require('../paths.php'); 
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'url_variables.inc.php');
require(DB.'common.db.php');
require(INCLUDES.'version.inc.php');
//require(INCLUDES.'headers.inc.php');
include(DB.'dropoff.db.php');
include(DB.'sponsors.db.php');
include(DB.'contacts.db.php');
include(DB.'styles.db.php'); 
include(DB.'judging_locations.db.php'); 

if ($row_contest_info['contestHostWebsite'] != "") $website = $row_contest_info['contestHostWebsite']; 
	else $website = $_SERVER['SERVER_NAME'];

if ($action == "word") {
header("Content-Type: application/msword;"); 
header("Content-Disposition: attachment; filename=".str_replace(" ", "_", $row_contest_info['contestName'])."_Promo.doc");
}

if ($action == "html") { 
header("Content-Type: text/plain;"); 
header("Content-Disposition: attachment; filename=".str_replace(" ", "_", $row_contest_info['contestName'])."_Promo.html");
}
$output = "";
$output .= "<html>\n";
$output .= "<body>\n";
$output .= "<h1>".$row_contest_info['contestName']."</h1>\n";
$output .= "<p>".$row_contest_info['contestHost']." announces the ".$row_contest_info['contestName'].".</p>\n";
$output .= "<h2>Entries</h2>\n";
$output .= "<p>To enter, please go to ".$website." and proceed through the registration process.</p>\n";
$output .= "<h3>Entry Deadline</h3>\n";
$output .= "<p>All entries must be received by our shipping location or at a drop-off location by "; $date = $row_contest_info['contestEntryDeadline']; $output .= date_convert($date, 2, $row_prefs['prefsDateFormat']).". Entries will not be accepted beyond this date.</p>\n";
$output .= "<h3>Registration Deadline</h3>\n";
$output .= "<p>Registration will close on "; $date = $row_contest_info['contestRegistrationDeadline']; $output .= date_convert($date, 2, $row_prefs['prefsDateFormat']).". Please note: registered users will <em>not</em> be able to add, view, edit, or delete entries on the registration website after "; $date = $row_contest_info['contestRegistrationDeadline']; $output .= date_convert($date, 2, $row_prefs['prefsDateFormat']).".</p>\n";
$output .= "<h2>Call for Judges and Stewards</h2>\n"; 
$output .= "<p>If you are willing to be a judge or steward, please go to ".$website.", register, and fill out the appropriate information.</p>\n";
$output .= "<h2>Competition Officials</h2>\n";

$output .= "<ul>\n";
do { 
$output .= "\t<li>".$row_contact['contactFirstName']." ".$row_contact['contactLastName']." &mdash; ".$row_contact['contactPosition']." (".$row_contact['contactEmail'].")</li>\n";
} while ($row_contact = mysql_fetch_assoc($contact));
$output .= "</ul>\n";
if ($row_prefs['prefsSponsors'] == "Y") {
if ($totalRows_sponsors > 0) { 
$output .= "<h2>Sponsors</h2>\n";
$output .= "<ul>\n";
	do { $output .= "\t<li>".$row_sponsors['sponsorName']."</li>\n"; } while ($row_sponsors = mysql_fetch_assoc($sponsors));
$output .= "</ul>\n";
	} 
} // end if prefs dictate display sponsors 
$output .= "<h2>Competition Rules</h2>\n";
$output .= $row_contest_info['contestRules']."\n";
$output .= "<h3>Entry Fee</h3>\n";
$output .= "<p>".$row_prefs['prefsCurrency'].$row_contest_info['contestEntryFee']." per entry."; if ($row_contest_info['contestEntryFeeDiscount'] == "Y") $output .= $row_prefs['prefsCurrency'].number_format($row_contest_info['contestEntryFee2'], 2)." per entry after ".$row_contest_info['contestEntryFeeDiscountNum']." entries. "; if ($row_contest_info['contestEntryCap'] != "") $output .= $row_prefs['prefsCurrency'].number_format($row_contest_info['contestEntryCap'], 2)." for unlimited entries.</p>\n";
$output .= "<h3>Payment</h3>\n";
$output .= "<p>After registering and inputting entries, all participants must pay their entry fee(s). Accepted payment methods include:</p>\n";
$output .= "<ul>\n";
	if ($row_prefs['prefsCash'] == "Y") $output .= "\t<li>Cash</li>\n";
	if ($row_prefs['prefsCheck'] == "Y") $output .= "\t<li>Check, made out to <em>".$row_prefs['prefsCheckPayee']."</em></li>\n";
	if ($row_prefs['prefsPaypal'] == "Y") $output .= "\t<li>PayPal (once registered on the website, click <em>Pay Entry Fees</em> from the main navigation after inputting all entries).</li>\n";
$output .= "</ul>\n";

$output .= "<h3>Competition Date"; if ($totalRows_judging > 1) $output .= "s"; $output .= "</h3>";
if ($totalRows_judging == 0) $output .= "<p>The competition judging date is yet to be determined. Please check back later."; else { 
  do { 
  $output .= "<p>";
	if ($row_judging['judgingDate'] != "") $output .= date_convert($row_judging['judgingDate'], 2, $row_prefs['prefsDateFormat'])." at "; $output .= $row_judging['judgingLocName']; 
	if ($row_judging['judgingTime'] != "") $output .= ", ".$row_judging['judgingTime']; 
 	if ($row_judging['judgingLocation'] != "") $output .= "<br />".$row_judging['judgingLocation']; 
  $output .= "</p>\n";
	} while ($row_judging = mysql_fetch_assoc($judging));
}

$output .= "<h3>Categories Accepted</h3>\n";
$output .= "<ul>\n";
do { 
  $output .= "<li>".ltrim($row_styles['brewStyleGroup'], "0").$row_styles['brewStyleNum']." ".$row_styles['brewStyle']; if ($row_styles['brewStyleOwn'] == "custom") $output .= " (Special style: ".$row_contest_info['contestName'].")</li>";
 } while ($row_styles = mysql_fetch_assoc($styles));
$output .= "</ul>\n";
 
if ($row_contest_info['contestBottles'] != "") 
{
$output .= "<h3>Bottle Acceptance Rules</h3>\n";
$output .= $row_contest_info['contestBottles']."\n";
} 
if ($row_contest_info['contestShippingAddress'] != "") 
{ 
$output .= "<h3>Shipping Address</h3>\n";
$output .= $row_contest_info['contestShippingAddress']."\n";
$output .= "<h4>Packing &amp; Shipping Hints</h4>\n";
$output .= "<ol>\n";
$output .= "\t<li>Carefully pack your entries in a sturdy box. Line the inside of your carton with a plastic trash bag. Partition and pack each bottle with adequate packaging material. Do not over pack! Write clearly: \"Fragile. This Side Up.\" on the package. Your package should weigh less than 25 pounds. Please refrain from using &quot;messy&quot; packaging materials such a Styrofoam &quot;peanuts&quot; or pellets; please use packaging material such as bubble wrap.</li>\n";
$output .= "\t<li>Every reasonable effort will be made to contact entrants whose bottles have broken to make arrangements for sending replacement bottles.</li>\n";
$output .= "\t<li>It is not against any Bureau of Alcohol, Tobacco and Firearms (ATF) regulations or federal laws to ship your entries via privately owned shipping company for analytical purposes. However, <strong>IT IS ILLEGAL TO SHIP ALCOHOLIC BEVERAGES VIA THE U.S. POSTAL SERVICE</strong>. Private shipping companies have the right to refuse your shipment if they are informed that the package contains glass and/or alcoholic beverages. Be aware that entries mailed internationally are often required by customs to have proper documentation. These entries might be opened and/or returned to the shipper by customs' officials at their discretion. It is solely the entrant's responsibility to follow all applicable laws and regulations.</li>\n";
$output .= "</ol>\n";
} 
if ($totalRows_dropoff > 0)  
{
$output .= "<h3>Drop Off Locations</h3>\n";
do {
$output .= "<p>".$row_dropoff['dropLocationName']."<br>".$row_dropoff['dropLocationPhone']."<br>".$row_dropoff['dropLocation']."</p>";
 } while($row_dropoff = mysql_fetch_assoc($dropoff));

} 
if ($row_contest_info['contestBOSAward'] != "") 
$output .= "<h2>Best of Show</h2>\n";
$output .= "<p>".$row_contest_info['contestBOSAward']."</p>\n";
if ($row_contest_info['contestAwards'] != "") 
{ 
$output .= "<h2>Awards</h2>\n";
$output .= "<p>".$row_contest_info['contestAwards']."</p>\n";
 } 

if ($row_contest_info['contestAwardsLocName'] != "") { 
$output .= "<h3>Award Ceremony</h3>\n";
$output .= "<p>";
	if ($row_contest_info['contestAwardsLocDate'] != "") $output .= date_convert($row_contest_info['contestAwardsLocDate'], 2, $row_prefs['prefsDateFormat'])." at "; $output .= $row_contest_info['contestAwardsLocName'];
	if ($row_contest_info['contestAwardsLocTime'] != "") $output .= ", ".$row_contest_info['contestAwardsLocTime'];
	if ($row_contest_info['contestAwardsLocation'] != "") $output .= "<br />".$row_contest_info['contestAwardsLocation'];
$output .= "</p>";
 } 
$output .= "</body>\n";
$output .= "</html>\n";
echo $output;
?>
