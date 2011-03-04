<?php 
require ('../Connections/config.php');
include ('../includes/url_variables.inc.php');
include ('../includes/db_connect.inc.php');
include ('../includes/functions.inc.php'); 

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

echo "<html>\n";
echo "<body>\n";
echo "<h1>Announcing the ".$row_contest_info['contestName']."</h1>\n";
echo "<p>".$row_contest_info['contestHost']." announces the ".$row_contest_info['contestName'].".</p>\n";
echo "<h2>Entries</h2>\n";
echo "<p>To enter, please go to ".$website." and proceed through the registration process.</p>\n";
echo "<h3>Entry Deadline</h3>\n";
echo "<p>All entries must be received by our shipping location or at a drop-off location by "; $date = $row_contest_info['contestEntryDeadline']; echo dateconvert($date, 2).". Entries will not be accepted beyond this date.</p>\n";
echo "<h3>Registration Deadline</h3>\n";
echo "<p>Registration will close on "; $date = $row_contest_info['contestRegistrationDeadline']; echo dateconvert($date, 2).". Please note: registered users will <em>not</em> be able to add, view, edit, or delete entries on the registration website after "; $date = $row_contest_info['contestRegistrationDeadline']; echo dateconvert($date, 2).".</p>\n";
echo "<h2>Call for Judges and Stewards</h2>\n"; 
echo "<p>If you are willing to be a judge or steward, please go to ".$website.", register, and fill out the appropriate information.</p>\n";
echo "<h2>Competition Coordinator</h2>\n";
echo "<p>".$row_contest_info['contestContactName']." &mdash; ".$row_contest_info['contestContactEmail']."</p>\n";
if ($row_prefs['prefsSponsors'] == "Y") {
if ($totalRows_sponsors > 0) { 
echo "<h2>Sponsors</h2>\n";
echo "<ul>\n";
	do { echo "\t<li>".$row_sponsors['sponsorName']."</li>\n"; } while ($row_sponsors = mysql_fetch_assoc($sponsors));
echo "</ul>\n";
	} 
} // end if prefs dictate display sponsors 
echo "<h2>Competition Rules</h2>\n";
echo $row_contest_info['contestRules']."\n";
echo "<h3>Entry Fee</h3>\n";
echo "<p>".$row_prefs['prefsCurrency'].$row_contest_info['contestEntryFee']." per entry."; if ($row_contest_info['contestEntryFeeDiscount'] == "Y") echo $row_prefs['prefsCurrency'].number_format($row_contest_info['contestEntryFee2'], 2)." per entry after ".$row_contest_info['contestEntryFeeDiscountNum']." entries. "; if ($row_contest_info['contestEntryCap'] != "") echo $row_prefs['prefsCurrency'].number_format($row_contest_info['contestEntryCap'], 2)." for unlimited entries.</p>\n";
echo "<h3>Payment</h3>\n";
echo "<p>After registering and inputting entries, all participants must pay their entry fee(s). Accepted payment methods include:</p>\n";
echo "<ul>\n";
	if ($row_prefs['prefsCash'] == "Y") echo "\t<li>Cash</li>\n";
	if ($row_prefs['prefsCheck'] == "Y") echo "\t<li>Check, made out to <em>".$row_prefs['prefsCheckPayee']."</em></li>\n";
	if ($row_prefs['prefsPaypal'] == "Y") echo "\t<li>PayPal (once registered on the website, click <em>Pay Entry Fees</em> from the main navigation after inputting all entries).</li>\n";
echo "</ul>\n";

echo "<h3>Judging Date"; if ($totalRows_judging > 1) echo "s"; echo "</h3>";
if ($totalRows_judging == 0) echo "<p>The competition judging date is yet to be determined. Please check back later."; else { 
  do { 
  echo "<p>";
	if ($row_judging['judgingDate'] != "") echo dateconvert($row_judging['judgingDate'], 2)." at "; echo $row_judging['judgingLocName']; 
	if ($row_judging['judgingTime'] != "") echo ", ".$row_judging['judgingTime']; 
 	if ($row_judging['judgingLocation'] != "") echo "<br />".$row_judging['judgingLocation']; 
  echo "</p>\n";
	} while ($row_judging = mysql_fetch_assoc($judging));
}

echo "<h3>Categories Accepted</h3>\n";
echo "<ul>\n";
do { 
  echo "<li>".ltrim($row_styles['brewStyleGroup'], "0").$row_styles['brewStyleNum']." ".$row_styles['brewStyle']; if ($row_styles['brewStyleOwn'] == "custom") echo " (Special style: ".$row_contest_info['contestName'].")</li>";
 } while ($row_styles = mysql_fetch_assoc($styles));
echo "</ul>\n";
 
if ($row_contest_info['contestBottles'] != "") 
{
echo "<h3>Bottle Acceptance Rules</h3>\n";
echo $row_contest_info['contestBottles']."\n";
} 
if ($row_contest_info['contestShippingAddress'] != "") 
{ 
echo "<h3>Shipping Address</h3>\n";
echo $row_contest_info['contestShippingAddress']."\n";
echo "<h4>Packing &amp; Shipping Hints</h4>\n";
echo "<ol>\n";
echo "\t<li>Carefully pack your entries in a sturdy box. Line the inside of your carton with a plastic trash bag. Partition and pack each bottle with adequate packaging material. Do not over pack! Write clearly: \"Fragile. This Side Up.\" on the package. Your package should weigh less than 25 pounds. Please refrain from using &quot;messy&quot; packaging materials such a Styrofoam &quot;peanuts&quot; or pellets; please use packaging material such as bubble wrap.</li>\n";
echo "\t<li>Every reasonable effort will be made to contact entrants whose bottles have broken to make arrangements for sending replacement bottles.</li>\n";
echo "\t<li>It is not against any Bureau of Alcohol, Tobacco and Firearms (ATF) regulations or federal laws to ship your entries via privately owned shipping company for analytical purposes. However, <strong>IT IS ILLEGAL TO SHIP ALCOHOLIC BEVERAGES VIA THE U.S. POSTAL SERVICE</strong>. Private shipping companies have the right to refuse your shipment if they are informed that the package contains glass and/or alcoholic beverages. Be aware that entries mailed internationally are often required by customs to have proper documentation. These entries might be opened and/or returned to the shipper by customs' officials at their discretion. It is solely the entrant's responsibility to follow all applicable laws and regulations.</li>\n";
echo "</ol>\n";
} 
if ($totalRows_dropoff > 0)  
{
echo "<h3>Drop Off Locations</h3>\n";
do {
echo "<p>".$row_dropoff['dropLocationName']."<br>".$row_dropoff['dropLocationPhone']."<br>".$row_dropoff['dropLocation']."</p>";
 } while($row_dropoff = mysql_fetch_assoc($dropoff));

} 
if ($row_contest_info['contestBOSAward'] != "") 
echo "<h2>Best of Show</h2>\n";
echo "<p>".$row_contest_info['contestBOSAward']."</p>\n";
if ($row_contest_info['contestAwards'] != "") 
{ 
echo "<h2>Awards</h2>\n";
echo "<p>".$row_contest_info['contestAwards']."</p>\n";
 } 

if ($row_contest_info['contestAwardsLocName'] != "") { 
echo "<h3>Award Ceremony</h3>\n";
echo "<p>";
	if ($row_contest_info['contestAwardsLocDate'] != "") echo dateconvert($row_contest_info['contestAwardsLocDate'], 2)." at "; echo $row_contest_info['contestAwardsLocName'];
	if ($row_contest_info['contestAwardsLocTime'] != "") echo ", ".$row_contest_info['contestAwardsLocTime'];
	if ($row_contest_info['contestAwardsLocation'] != "") echo "<br />".$row_contest_info['contestAwardsLocation'];
echo "</p>";
 } 
echo "</body>\n";
echo "</html>\n";
?>
