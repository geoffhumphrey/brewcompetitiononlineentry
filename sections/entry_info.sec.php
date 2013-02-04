<?php 
/**
 * Module:      entry_info.sec.php
 * Description: This module houses public-facing information including entry.
 *              requirements, dropoff, shipping, and judging locations, etc.
 * 
 */
 
include(DB.'dropoff.db.php');
include(DB.'contacts.db.php');
include(DB.'judging_locations.db.php');
include(DB.'styles.db.php');

if ($action != "print") { ?>
<?php if (($action != "print") && ($msg != "default")) echo $msg_output; ?>
<?php if (($row_contest_info['contestLogo'] != "") && (file_exists($_SERVER['DOCUMENT_ROOT'].$sub_directory.'/user_images/'.$row_contest_info['contestLogo']))) { // display competition's logo if name present in DB and in the correct folder on the server ?>
<img src="<?php echo $base_url; ?>/user_images/<?php echo $row_contest_info['contestLogo']; ?>" width="<?php echo $row_prefs['prefsCompLogoSize']; ?>" align="right" hspace="3" vspace="3" alt="Competition Logo"/>
<?php } ?>
<p><span class="icon"><img src="<?php echo $base_url; ?>/images/printer.png"  border="0" alt="Print" /></span><a id="modal_window_link" class="data" href="<?php echo $base_url; ?>/output/print.php?section=<?php echo $section; ?>&amp;action=print" title="Print Entry Information">Print This Page</a></p>
<?php 
if ($row_prefs['prefsUseMods'] == "Y") include(INCLUDES.'mods_top.inc.php');
$contact_count= get_contact_count();
if ($contact_count > 0) { ?><a href="#officials">Competition Official<?php if ($contact_count > 1) echo "s"; ?></a><br /><?php } ?>
<a href="#reg_window">Registration Window</a><br />
<a href="#entry_window">Entry Window</a><br />
<?php if ($row_prefs['prefsEntryLimit'] != "") { ?><a href="#entry_limit">Entry Limit</a><br /><?php } ?>
<a href="#entry">Entry Fees</a><br />
<a href="#payment">Payment</a><br />
<a href="#judging">Judging Date<?php if ($totalRows_judging > 1) echo "s"; ?></a><br />
<a href="#categories">Categories Accepted</a><br />
<?php if ($row_contest_info['contestBottles'] != "") { ?><a href="#bottle">Bottle Acceptance Rules</a><br /><?php } ?>
<?php if ($row_contest_info['contestShippingAddress'] != "") { ?><a href="#shipping">Shipping Location and Address</a><br /><?php } ?>
<?php if ($totalRows_dropoff > 0) { ?><a href="#drop">Drop Off Location<?php if ($totalRows_dropoff > 1) echo "s"; ?></a><br /><?php } ?>
<?php if ($row_contest_info['contestBOSAward'] != "") { ?><a href="#bos">Best of Show</a><br /><?php }?>
<?php if ($row_contest_info['contestAwards'] != "") { ?><a href="#awards">Awards</a><br /><?php }?>
<?php if ($row_contest_info['contestAwardsLocName'] != "") { ?><a href="#ceremony">Awards Ceremony</a><br /><?php } ?>
<?php } ?>
<?php if ($contact_count > 0) { ?>
<a name="officials"></a><h2>Competition Official<?php if ($contact_count > 1) echo "s"; ?></h2>
<?php if ($action != "print") { ?><p>You can send an email to any of the following individuals via the <a href="<?php echo build_public_url("contact","default","default",$sef,$base_url); ?>">Contact</a> section.</p><?php } ?>
<ul>
<?php do { ?>
<li><?php echo $row_contact['contactFirstName']." ".$row_contact['contactLastName']." &mdash; ".$row_contact['contactPosition']; if ($action == "print") echo " (".$row_contact['contactEmail'].")"; ?></li>
<?php } while ($row_contact = mysql_fetch_assoc($contact)); ?>
</ul>
<?php } ?>
<a name="reg_window"></a><h2>Registration Window</h2>
<p>You will be able to register your entries beginning <?php echo $reg_open." through ".$reg_closed; ?>.</p>
<a name="entry_window"></a><h2>Entry Window</h2>
<p>Entries will be accepted at our shipping and drop-off location<?php if ($totalRows_dropoff > 1) echo "s"; ?> beginning <?php echo $entry_open." through ".$entry_closed; ?>.</p>
<a name="entry"></a><h2>Entry Fees</h2>
<p><?php echo $row_prefs['prefsCurrency'].number_format($row_contest_info['contestEntryFee'],2); ?> per entry. <?php if ($row_contest_info['contestEntryFeeDiscount'] == "Y") echo $row_prefs['prefsCurrency'].number_format($row_contest_info['contestEntryFee2'], 2)." per entry after the ".addOrdinalNumberSuffix($row_contest_info['contestEntryFeeDiscountNum'])." entry. "; if ($row_contest_info['contestEntryCap'] != "") echo $row_prefs['prefsCurrency'].number_format($row_contest_info['contestEntryCap'], 2)." for unlimited entries. "; if (NHC) echo $row_prefs['prefsCurrency'].number_format($row_contest_info['contestEntryFeePasswordNum'],2)." for AHA members."; ?></p>
<?php if ($row_prefs['prefsEntryLimit'] != "") { ?>
<a name="entry_limit"></a><h2>Entry Limit</h2>
<p>There is a limit of <?php echo readable_number($row_prefs['prefsEntryLimit'])." (".$row_prefs['prefsEntryLimit'].")"; ?> entries for this competition.  Currently, <?php echo readable_number($totalRows_log)." (".$totalRows_log.")"; if ($totalRows_log == 1) echo " entry has"; else echo " entries have"; ?>  been logged.</p>
<?php } ?>
<a name="payment"></a><h2>Payment</h2>
<p>After registering and inputting entries, all participants must pay their entry fee(s).</p>
    <ul>
    	<?php 
		if ($row_prefs['prefsCash'] == "Y") echo "<li>Cash</li>";
        if ($row_prefs['prefsCheck'] == "Y") echo "<li>Check, made out to <em>".$row_prefs['prefsCheckPayee']."</em></li>";
        if ($row_prefs['prefsPaypal'] == "Y") echo "<li>PayPal</li>";
		if ($row_prefs['prefsGoogle'] == "Y") echo "<li>Google Wallet</li>"; 
		?>
    </ul>
<a name="judging"></a><h2>Judging Date<?php if ($totalRows_judging > 1) echo "s"; ?></h2>
<?php if ($totalRows_judging == 0) echo "<p>The competition judging date is yet to be determined. Please check back later."; else { ?>
		<?php do { ?>
			<p>
			<?php echo "<strong>".$row_judging['judgingLocName']."</strong>"; ?>
    		<?php if ($row_judging['judgingLocation'] != "") echo "<br />".$row_judging['judgingLocation']; ?>
            <?php if (($row_judging['judgingLocation'] != "") && ($action != "print"))  { ?>
            <span class="icon"><a id="modal_window_link" href="<?php echo $base_url; ?>/output/maps.php?section=map&amp;id=<?php echo str_replace(' ', '+', $row_judging['judgingLocation']); ?>" title="Map to <?php echo $row_judging['judgingLocName']; ?>"><img src="<?php echo $base_url; ?>/images/map.png"  border="0" alt="Map <?php echo $row_judging['judgingLocName']; ?>" title="Map <?php echo $row_judging['judgingLocName']; ?>" /></a></span>
            <span class="icon"><a href="<?php echo $base_url; ?>/output/maps.php?section=driving&amp;id=<?php echo str_replace(' ', '+', $row_judging['judgingLocation']); ?>" title="Driving Directions to <?php echo $row_judging['judgingLocName']; ?>" target="_blank"><img src="<?php echo $base_url; ?>/images/car.png"  border="0" alt="Driving Directions to <?php echo $row_judging['judgingLocName']; ?>" title="Driving Direcitons to <?php echo $row_judging['judgingLocName']; ?>" /></a></span>
			<?php } ?>
            <?php if ($row_judging['judgingDate'] != "") echo "<br />".getTimeZoneDateTime($row_prefs['prefsTimeZone'], $row_judging['judgingDate'], $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "long", "date-time")."<br />"; ?>
			</p>
			<?php } while ($row_judging = mysql_fetch_assoc($judging)); ?>
<?php } ?>
<a name="categories"></a><h2>Categories Accepted</h2>
<p>Each entry must be placed into one of the following accepted categories:</p>
<table class="dataTableCompact" style="border-collapse:collapse;">
  <tr>
<?php
	$styles_endRow = 0;
	$styles_columns = 3;   // number of columns
	$styles_hloopRow1 = 0; // first row flag
	do {
    	if (($styles_endRow == 0) && ($styles_hloopRow1++ != 0)) echo "<tr>";
    ?>
  	<td>
  	<?php echo ltrim($row_styles['brewStyleGroup'], "0").$row_styles['brewStyleNum']." ".$row_styles['brewStyle']; if ($row_styles['brewStyleOwn'] == "custom") echo " (Custom Style)"; ?>
  	</td>
    <?php  
		$styles_endRow++;
		if ($styles_endRow >= $styles_columns) { $styles_endRow = 0; }
	} while ($row_styles = mysql_fetch_assoc($styles));
	if ($styles_endRow != 0) {
	while ($styles_endRow < $styles_columns) {
    echo("<td>&nbsp;</td>");
    $styles_endRow++;
	}
	echo("</tr>"); 
	}
	?>
</table>
<?php if ($row_contest_info['contestBottles'] != "") { ?>
<a name="bottle"></a><h2>Bottle Acceptance Rules</h2>
<?php echo $row_contest_info['contestBottles']; ?>
<?php } if ($row_contest_info['contestShippingAddress'] != "") { ?>
<a name="shipping"></a><h2>Shipping Location and Address</h2>
<p><?php echo $row_contest_info['contestShippingName']."<br>".$row_contest_info['contestShippingAddress']; ?></p>
<h3>Packing and Shipping</h3>
<p>Carefully pack your entries in a sturdy box. Line the inside of your carton with a plastic trash bag. Partition and pack each bottle with adequate packaging material. Do not over pack! Write clearly: "Fragile. This Side Up." on the package. Your package should weigh less than 25 pounds. Please refrain from using &quot;messy&quot; packaging materials such a Styrofoam &quot;peanuts&quot; or pellets; please use packaging material such as bubble wrap.</p>
<p>Place entry forms and payment a sealed zip-top bag. Additionally, enclose your <em>each</em> of your bottle labels in a small zip-top bag before attaching to their respective bottles. This way it makes it possible for the organizer to identify specifically  which beer has broken if there is damage during shipment.</p>
<p>Every reasonable effort will be made to contact entrants whose bottles have broken to make arrangements for sending replacement bottles.</p>
<p>It is not against any Bureau of Alcohol, Tobacco and Firearms (ATF) regulations or federal laws to ship your entries via privately owned shipping company for analytical purposes. However, <strong>IT IS ILLEGAL TO SHIP ALCOHOLIC BEVERAGES VIA THE U.S. POSTAL SERVICE</strong>. Private shipping companies have the right to refuse your shipment if they are informed that the package contains glass and/or alcoholic beverages. Be aware that entries mailed internationally are often required by customs to have proper documentation. These entries might be opened and/or returned to the shipper by customs' officials at their discretion. It is solely the entrant's responsibility to follow all applicable laws and regulations.</p>
<?php } 
if ($totalRows_dropoff > 0) { ?>
<a name="drop"></a><h2>Drop Off Location<?php if ($totalRows_dropoff > 1) echo "s"; ?></h2>
<?php do { ?>
<p><?php if ($row_dropoff['dropLocationWebsite'] != "") echo "<a href='".$row_dropoff['dropLocationWebsite']."' target='_blank'>"; echo "<strong>".$row_dropoff['dropLocationName']."</strong>"; if ($row_dropoff['dropLocationWebsite'] != "") echo "</a>"; ?><br />
<?php echo $row_dropoff['dropLocation']; ?>
<?php if ($action != "print") { ?>&nbsp;&nbsp;<span class="icon"><a id="modal_window_link" href="<?php echo $base_url; ?>/output/maps.php?section=map&amp;id=<?php echo str_replace(' ', '+', $row_dropoff['dropLocation']); ?>" title="Map to <?php echo $row_dropoff['dropLocationName']; ?>"><img src="<?php echo $base_url; ?>/images/map.png"  border="0" alt="Map <?php echo $row_dropoff['dropLocationName']; ?>" title="Map <?php echo $row_dropoff['dropLocationName']; ?>" /></a></span><span class="icon"><a href="<?php echo $base_url; ?>/output/maps.php?section=driving&amp;id=<?php echo str_replace(' ', '+', $row_dropoff['dropLocation']); ?>" title="Driving Directions to <?php echo $row_dropoff['dropLocationName']; ?>" target="_blank"><img src="<?php echo $base_url; ?>/images/car.png"  border="0" alt="Driving Directions to <?php echo $row_dropoff['dropLocationName']; ?>" title="Driving Direcitons to <?php echo $row_dropoff['dropLocationName']; ?>" /></a></span>
<?php } ?>
<br />
<?php echo $row_dropoff['dropLocationPhone']; ?>
<br />
<?php if ($row_dropoff['dropLocationNotes'] != "") echo "*<em>".$row_dropoff['dropLocationNotes']."</em>"; ?>
</p>
<?php } while ($row_dropoff = mysql_fetch_assoc($dropoff)); ?>
<?php } ?>
<?php if ($row_contest_info['contestBOSAward'] != "") { ?>
<a name="bos"></a><h2>Best of Show</h2>
<?php echo $row_contest_info['contestBOSAward']; ?>
<?php }
if ($row_contest_info['contestAwards'] != "") { ?>
<a name="awards"></a><h2>Awards</h2>
<?php echo $row_contest_info['contestAwards']; ?>
<?php } ?>
<?php if ($row_contest_info['contestAwardsLocName'] != "") { ?>
<a name="ceremony"></a><h2>Award Ceremony</h2>
<p>
	<?php 
	 echo "<strong>".$row_contest_info['contestAwardsLocName']."</strong>";
     if ($row_contest_info['contestAwardsLocation'] != "") echo "<br />".$row_contest_info['contestAwardsLocation']; 
	 if (($row_contest_info['contestAwardsLocation'] != "") && ($action != "print")) { ?>&nbsp;&nbsp;<span class="icon"><a id="modal_window_link" href="<?php echo $base_url; ?>/output/maps.php?section=map&amp;id=<?php echo str_replace(' ', '+', $row_contest_info['contestAwardsLocation']); ?>" title="Map to <?php echo $row_contest_info['contestAwardsLocName']; ?>"><img src="<?php echo $base_url; ?>/images/map.png"  border="0" alt="Map <?php echo $row_contest_info['contestAwardsLocName']; ?>" title="Map <?php echo $row_contest_info['contestAwardsLocName']; ?>" /></a></span>
	<span class="icon"><a href="<?php echo $base_url; ?>/output/maps.php?section=driving&amp;id=<?php echo str_replace(' ', '+', $row_contest_info['contestAwardsLocation']); ?>" title="Driving Directions to <?php echo $row_contest_info['contestAwardsLocName']; ?>" target="_blank"><img src="<?php echo $base_url; ?>/images/car.png"  border="0" alt="Driving Directions to <?php echo $row_contest_info['contestAwardsLocName']; ?>" title="Driving Direcitons to <?php echo $row_contest_info['contestAwardsLocName']; ?>" /></a></span>
	<?php } 
	if ($row_contest_info['contestAwardsLocTime'] != "") echo "<br />". 
	getTimeZoneDateTime($row_prefs['prefsTimeZone'], $row_contest_info['contestAwardsLocTime'], $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "long", "date-time");
	?>
</p>
<?php } 
if ($row_prefs['prefsUseMods'] == "Y") include(INCLUDES.'mods_bottom.inc.php');
?>
