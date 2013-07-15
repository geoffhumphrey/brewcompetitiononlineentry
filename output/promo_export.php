<?php 

session_start();
require('../paths.php'); 
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php');
require(DB.'common.db.php');
require(INCLUDES.'version.inc.php');
include(DB.'dropoff.db.php');
include(DB.'sponsors.db.php');
include(DB.'contacts.db.php');
include(DB.'styles.db.php'); 
include(DB.'judging_locations.db.php'); 

if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {
	
	if ($_SESSION['contestHostWebsite'] != "") $website = $_SESSION['contestHostWebsite']; 
		else $website = $_SERVER['SERVER_NAME'];
	
	if ($action == "word") {
		header("Content-Type: application/msword;"); 
		header("Content-Disposition: attachment; filename=".str_replace(" ", "_", $_SESSION['contestName'])."_Promo.doc");
	}
	
	if ($action == "html") { 
		header("Content-Type: text/plain;"); 
		header("Content-Disposition: attachment; filename=".str_replace(" ", "_", $_SESSION['contestName'])."_Promo.html");
	}
	
	if ($action == "bbcode") { 
		header("Content-Type: text/plain;"); 
		header("Content-Disposition: attachment; filename=".str_replace(" ", "_", $_SESSION['contestName'])."_Promo.txt");
	}
	
	$output = "";
	if ($action != "bbcode") { 
	$output .= "<html>\n";
	$output .= "<body>\n";
	}
	
	$output .= "<h1>".$_SESSION['contestName']."</h1>\n";
	$output .= "<p>".$_SESSION['contestHost']." announces the ".$_SESSION['contestName'].".</p>\n";
	$output .= "<h2>Entries</h2>\n";
	$output .= "<p>To enter, please go to ".$website." and proceed through the registration process.</p>\n";
	$output .= "<h3>Entry Deadline</h3>\n";
	$output .= "<p>All entries must be received by our shipping location or at a drop-off location by "; $output .=  getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestEntryDeadline'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time").". Entries will not be accepted beyond this date.</p>\n";
	$output .= "<h3>Registration Deadline</h3>\n";
	$output .= "<p>Registration will close on "; $output .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestRegistrationDeadline'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time").". Please note: registered users will <em>not</em> be able to add, view, edit, or delete entries on the registration website after  this date.</p>\n";
	$output .= "<h2>Call for Judges and Stewards</h2>\n"; 
	$output .= "<p>If you are willing to be a judge or steward, please go to ".$website.", register, and fill out the appropriate information.</p>\n";
	$output .= "<h2>Competition Officials</h2>\n";
	
	$output .= "<ul>\n";
	do { 
	$output .= "\t<li>".$row_contact['contactFirstName']." ".$row_contact['contactLastName']." &mdash; ".$row_contact['contactPosition']." (".$row_contact['contactEmail'].")</li>\n";
	} while ($row_contact = mysql_fetch_assoc($contact));
	$output .= "</ul>\n";
	if ($_SESSION['prefsSponsors'] == "Y") {
	if ($totalRows_sponsors > 0) { 
	$output .= "<h2>Sponsors</h2>\n";
	$output .= "<ul>\n";
		do { $output .= "\t<li>".$row_sponsors['sponsorName']."</li>\n"; } while ($row_sponsors = mysql_fetch_assoc($sponsors));
	$output .= "</ul>\n";
		} 
	} // end if prefs dictate display sponsors 
	
	$output .= "<h2>Competition Rules</h2>\n";
	$output .= $_SESSION['contestRules']."\n";
	$output .= "<h3>Entry Fee</h3>\n";
	$output .= "<p>".$_SESSION['prefsCurrency'].$_SESSION['contestEntryFee']." per entry."; if ($_SESSION['contestEntryFeeDiscount'] == "Y") $output .= $_SESSION['prefsCurrency'].number_format($_SESSION['contestEntryFee2'], 2)." per entry after ".$_SESSION['contestEntryFeeDiscountNum']." entries. "; if ($_SESSION['contestEntryCap'] != "") $output .= $_SESSION['prefsCurrency'].number_format($_SESSION['contestEntryCap'], 2)." for unlimited entries."; 
	$output .= "</p>\n";
	$output .= "<h3>Payment</h3>\n";
	$output .= "<p>After registering and inputting entries, all participants must pay their entry fee(s). Accepted payment methods include:</p>\n";
	$output .= "<ul>\n";
		if ($_SESSION['prefsCash'] == "Y") $output .= "\t<li>Cash</li>\n";
		if ($_SESSION['prefsCheck'] == "Y") $output .= "\t<li>Check, made out to <em>".$_SESSION['prefsCheckPayee']."</em></li>\n";
		if ($_SESSION['prefsPaypal'] == "Y") $output .= "\t<li>PayPal (once registered on the website, click <em>Pay Entry Fees</em> from the main navigation after inputting all entries).</li>\n";
	$output .= "</ul>\n";
	
	$output .= "<h3>Competition Date"; if ($totalRows_judging > 1) $output .= "s"; $output .= "</h3>\n";
	if ($totalRows_judging == 0) $output .= "<p>The competition judging date is yet to be determined. Please check back later.</p>\n"; else { 
	  do { 
	  $output .= "<p>";
	  $output .= "<strong>".$row_judging['judgingLocName']."</strong><br />"; 
		if ($row_judging['judgingDate'] != "") $output .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_judging['judgingDate'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time"); 
		if ($row_judging['judgingLocation'] != "") $output .= "<br />".$row_judging['judgingLocation']; 
	  $output .= "</p>\n";
		} while ($row_judging = mysql_fetch_assoc($judging));
	}
	
	$output .= "<h3>Categories Accepted</h3>\n";
	$output .= "<ul>\n";
	do { 
	  $output .= "\t<li>".ltrim($row_styles['brewStyleGroup'], "0").$row_styles['brewStyleNum']." ".$row_styles['brewStyle']; if ($row_styles['brewStyleOwn'] == "custom") $output .= " (Special style: ".$_SESSION['contestName'].")"; $output .= "</li>\n";
	} while ($row_styles = mysql_fetch_assoc($styles));
	$output .= "</ul>\n";
	 
	if ($_SESSION['contestBottles'] != "") {
		$output .= "<h3>Bottle Acceptance Rules</h3>\n";
		$output .= $_SESSION['contestBottles']."\n";
	}
	
	if ($_SESSION['contestShippingAddress'] != "") { 
		$output .= "<h3>Shipping Address</h3>\n";
		$output .= "<p>".$_SESSION['contestShippingAddress']."</p>\n";
		$output .= "<h4>Packing &amp; Shipping Hints</h4>\n";
		$output .= "<ol>\n";
		$output .= "\t<li>Carefully pack your entries in a sturdy box. Line the inside of your carton with a plastic trash bag. Partition and pack each bottle with adequate packaging material. Do not over pack! Write clearly: \"Fragile. This Side Up.\" on the package. Your package should weigh less than 25 pounds. Please refrain from using &quot;messy&quot; packaging materials such a Styrofoam &quot;peanuts&quot; or pellets; please use packaging material such as bubble wrap.</li>\n";
		$output .= "\t<li>Every reasonable effort will be made to contact entrants whose bottles have broken to make arrangements for sending replacement bottles.</li>\n";
		$output .= "\t<li>It is not against any Bureau of Alcohol, Tobacco and Firearms (ATF) regulations or federal laws to ship your entries via privately owned shipping company for analytical purposes. However, <strong>IT IS ILLEGAL TO SHIP ALCOHOLIC BEVERAGES VIA THE U.S. POSTAL SERVICE</strong>. Private shipping companies have the right to refuse your shipment if they are informed that the package contains glass and/or alcoholic beverages. Be aware that entries mailed internationally are often required by customs to have proper documentation. These entries might be opened and/or returned to the shipper by customs' officials at their discretion. It is solely the entrant's responsibility to follow all applicable laws and regulations.</li>\n";
		$output .= "</ol>\n";
	} 
	
	if ($totalRows_dropoff > 0)  {
		$output .= "<h3>Drop Off Locations</h3>\n";
		do {
			$output .= "<p><strong>".$row_dropoff['dropLocationName']."</strong><br />".$row_dropoff['dropLocation']."<br />".$row_dropoff['dropLocationPhone']."</p>\n";
		} while($row_dropoff = mysql_fetch_assoc($dropoff));
	}
	
	if ($_SESSION['contestBOSAward'] != "") 
		$output .= "<h2>Best of Show</h2>\n";
		$output .= "<p>".$_SESSION['contestBOSAward']."</p>\n";
		
	if ($_SESSION['contestAwards'] != "") { 
		$output .= "<h2>Awards</h2>\n";
		$output .= "<p>".$_SESSION['contestAwards']."</p>\n";
	 } 
	
	if ($_SESSION['contestAwardsLocName'] != "") { 
		$output .= "<h3>Award Ceremony</h3>\n";
		$output .= "<p>";
			if ($_SESSION['contestAwardsLocDate'] != "") 
			$output .= "<strong>".$_SESSION['contestAwardsLocName']."</strong><br />";
			$output .= getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestAwardsLocTime'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time");
			if ($_SESSION['contestAwardsLocation'] != "") $output .= "<br />".$_SESSION['contestAwardsLocation'];
		$output .= "</p>\n"; 
	 } 
	 
	if ($action != "bbcode") {
		$output .= "</body>\n";
		$output .= "</html>\n";
	}
	
	if ($action == "bbcode") {
		$html   = array("<p>","</p>","<strong>","</strong>","<ul>","</ul>","<ol>","</ol>","<li>","</li>","<h1>","</h1>","<h2>","</h2>","<h3>","</h3>","<h4>","</h4>","<body>","</body>","<html>","</html>","<br />","<em>","</em>","&amp;","&mdash;");
		$bbcode = array("[left]","[/left]\r\n\r","[b]","[/b]","[list type=disc]\r","[/list]\r\n\r","[list type=upper-roman]\r","[/list]\r\n\r","[li]","[/li]\r","[size=xx-large]","[/size]\r\n\r","[size=x-large]","[/size]\r\n\r","[size=large]","[/size]\r\n\r","[b]","[/b]","","","","","\n","[i]","[/i]","&","-");
		$output = str_replace($html,$bbcode,$output);
	}
	echo $output;
	
} else echo "<p>Not Available</p>";
?>
