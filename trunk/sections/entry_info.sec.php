<?php if ($action != "print") { ?>
<p><span class="icon"><img src="images/printer.png" align="absmiddle" /></span><a class="data" href="#" onClick="window.open('print.php?section=<?php echo $section; ?>&action=print','','height=600,width=800,toolbar=no,resizable=yes,scrollbars=yes'); return false;">Print This Page</a></p>
<?php } ?>
<?php if (($row_contest_info['contestLogo'] != "") && (file_exists('user_images/'.$row_contest_info['contestLogo']))) { // display competition's logo if name present in DB and in the correct folder on the server ?>
<img src="user_images/<?php echo $row_contest_info['contestLogo']; ?>" width="<?php echo $row_prefs['prefsCompLogoSize']; ?>" align="right" hspace="3" vspace="3" />
<?php } ?>
<h2>Competition Coordinator</h2>
<p><a href="mailto:<?php echo $row_contest_info['contestContactEmail']; ?>?subject=<?php echo $row_contest_info['contestName']; ?>"><?php echo $row_contest_info['contestContactName']; ?></a><?php if ($action == "print") echo " &mdash; ".$row_contest_info['contestContactEmail']; ?></p>
<h2>Entry Deadline</h2>
<p><?php echo dateconvert($row_contest_info['contestEntryDeadline'], 2); ?></p>
<h2>Judging Date<?php if ($totalRows_judging > 1) echo "s"; ?></h2>
<?php if ($totalRows_judging == 0) echo "<p>The competition judging date is yet to be determined. Please check back later."; else { 
  do { ?>
  <p>
  <?php 
	if ($row_judging['judgingDate'] != "") echo dateconvert($row_judging['judgingDate'], 2)." at "; echo $row_judging['judgingLocName']; 
	if ($row_judging['judgingTime'] != "") echo ", ".$row_judging['judgingTime']; if (($row_judging['judgingLocation'] != "") && ($action != "print"))  { ?>&nbsp;&nbsp;<span class="icon"><a class="thickbox" href="http://maps.google.com/maps/api/staticmap?center=<?php echo str_replace(' ', '+', $row_judging['judgingLocation']); ?>&zoom=13&size=600x400&markers=color:red|<?php echo str_replace(' ', '+', $row_judging['judgingLocation']); ?>&sensor=false&KeepThis=true&TB_iframe=true&height=420&width=600" title="Map to <?php echo $row_judging['judgingLocName']; ?>"><img src="images/map.png" align="absmiddle" border="0" alt="Map <?php echo $row_judging['judgingLocName']; ?>" title="Map <?php echo $row_judging['judgingLocName']; ?>" /></a></span>
	<span class="icon"><a class="thickbox" href="http://maps.google.com/maps?f=q&source=s_q&hl=en&q=<?php echo str_replace(' ', '+', $row_judging['judgingLocation']); ?>&KeepThis=true&TB_iframe=true&height=450&width=900" title="Driving Directions to <?php echo $row_judging['judgingLocName']; ?>"><img src="images/car.png" align="absmiddle" border="0" alt="Driving Directions to <?php echo $row_judging['judgingLocName']; ?>" title="Driving Direcitons to <?php echo $row_judging['judgingLocName']; ?>" /></a></span>
    <?php if ($row_judging['judgingLocation'] != "") echo "<br />".$row_judging['judgingLocation']; ?>
  <?php } ?>
</p>
<?php } while ($row_judging = mysql_fetch_assoc($judging));
} ?>
<h2>Entry Fee</h2>
<p><?php echo $row_prefs['prefsCurrency'].number_format($row_contest_info['contestEntryFee'], 2); ?> per entry. <?php if ($row_contest_info['contestEntryFeeDiscount'] == "Y") echo $row_prefs['prefsCurrency'].number_format($row_contest_info['contestEntryFee2'], 2)." per entry after ".$row_contest_info['contestEntryFeeDiscountNum']." entries. "; if ($row_contest_info['contestEntryCap'] != "") echo $row_prefs['prefsCurrency'].number_format($row_contest_info['contestEntryCap'], 2)." for unlimited entries. "; ?></p>

<h2>Payment</h2>
<p>After registering and inputting entries, all participants must pay their entry fee(s). Accepted payment methods include:</p>
    <ul>
    	<?php if ($row_prefs['prefsCash'] == "Y") echo "<li>Cash</li>"; ?>
        <?php if ($row_prefs['prefsCheck'] == "Y") echo "<li>Check, made out to <em>".$row_prefs['prefsCheckPayee']."</em></li>"; ?>
        <?php if ($row_prefs['prefsPaypal'] == "Y") echo "<li>PayPal (once registered, click <em>Pay Entry Fees</em> from the main navigation after inputting all entries).</li>"; ?>
    </ul>
<h2>Categories Accepted</h2>
<ul>
  <?php do { ?>
  <li><?php echo ltrim($row_styles['brewStyleGroup'], "0").$row_styles['brewStyleNum']." ".$row_styles['brewStyle']; if ($row_styles['brewStyleOwn'] == "custom") echo " (Special style: ".$row_contest_info['contestName'].")"; ?></li>
  <?php } while ($row_styles = mysql_fetch_assoc($styles)) ?>
</ul>
<?php if ($row_contest_info['contestBottles'] != "") { ?>
<h2>Bottle Acceptance Rules</h2><p><?php echo $row_contest_info['contestBottles']; ?></p>
<?php } if ($row_contest_info['contestShippingAddress'] != "") { ?>
<h2>Shipping Location and Address</h2>
<p><?php echo $row_contest_info['contestShippingName']."<br \>".$row_contest_info['contestShippingAddress']; ?></p>
<h3>Packing &amp; Shipping Hints</h3>
      <ol>
		<li>Carefully pack your entries in a sturdy box. Line the inside of your carton with a plastic trash bag. Partition and pack each bottle with adequate packaging material. Do not over pack! Write clearly: "Fragile. This Side Up." on the package. Your package should weigh less than 25 pounds. Please refrain from using &quot;messy&quot; packaging materials such a Styrofoam &quot;peanuts&quot; or pellets; please use packaging material such as bubble wrap.</li>
		<li>Every reasonable effort will be made to contact entrants whose bottles have broken to make arrangements for sending replacement bottles.</li>
		<li>It is not against any Bureau of Alcohol, Tobacco and Firearms (ATF) regulations or federal laws to ship your entries via privately owned shipping company for analytical purposes. However, <strong>IT IS ILLEGAL TO SHIP ALCOHOLIC BEVERAGES VIA THE U.S. POSTAL SERVICE</strong>. Private shipping companies have the right to refuse your shipment if they are informed that the package contains glass and/or alcoholic beverages. Be aware that entries mailed internationally are often required by customs to have proper documentation. These entries might be opened and/or returned to the shipper by customs' officials at their discretion. It is solely the entrant's responsibility to follow all applicable laws and regulations.</li>
	  </ol>    
<?php } 
if ($totalRows_dropoff > 0) { ?>
<h2>Drop Off Locations</h2>
<?php do { ?>
<p><?php if ($row_dropoff['dropLocationWebsite'] != "") echo "<a href='".$row_dropoff['dropLocationWebsite']."' target='_blank'>"; echo $row_dropoff['dropLocationName']; if ($row_dropoff['dropLocationWebsite'] != "") echo "</a>"; ?><br />
<?php echo $row_dropoff['dropLocation']; ?>
<?php if ($action != "print") { ?>&nbsp;&nbsp;<span class="icon"><a class="thickbox" href="http://maps.google.com/maps/api/staticmap?center=<?php echo str_replace(' ', '+', $row_dropoff['dropLocation']); ?>&zoom=13&size=600x400&markers=color:red|<?php echo str_replace(' ', '+', $row_dropoff['dropLocation']); ?>&sensor=false&KeepThis=true&TB_iframe=true&height=420&width=600" title="Map to <?php echo $row_dropoff['dropLocationName']; ?>"><img src="images/map.png" align="absmiddle" border="0" alt="Map <?php echo $row_dropoff['dropLocationName']; ?>" title="Map <?php echo $row_dropoff['dropLocationName']; ?>" /></a></span>
<span class="icon"><a class="thickbox" href="http://maps.google.com/maps?f=q&source=s_q&hl=en&q=<?php echo str_replace(' ', '+', $row_dropoff['dropLocation']); ?>&KeepThis=true&TB_iframe=true&height=450&width=900" title="Driving Directions to <?php echo $row_dropoff['dropLocationName']; ?>"><img src="images/car.png" align="absmiddle" border="0" alt="Driving Directions to <?php echo $row_dropoff['dropLocationName']; ?>" title="Driving Direcitons to <?php echo $row_dropoff['dropLocationName']; ?>" /></a></span>
<?php } ?>
<br />
<?php echo $row_dropoff['dropLocationPhone']; ?>
</p>
<?php } while ($row_dropoff = mysql_fetch_assoc($dropoff)); 
} 
if ($row_contest_info['contestAwards'] != "") { ?>
<h2>Awards</h2>
<p><?php echo $row_contest_info['contestAwards']; ?></p>
<?php } ?>
<?php if ($row_contest_info['contestAwardsLocName'] != "") { ?>
<h2>Awards Ceremony</h2>
<p>
	<?php 
	if ($row_contest_info['contestAwardsLocDate'] != "") echo dateconvert($row_contest_info['contestAwardsLocDate'], 2)." at "; echo $row_contest_info['contestAwardsLocName'];
	if ($row_contest_info['contestAwardsLocTime'] != "") echo ", ".$row_contest_info['contestAwardsLocTime'];
	if (($row_contest_info['contestAwardsLocation'] != "") && ($action != "print")) { ?>&nbsp;&nbsp;<span class="icon"><a class="thickbox" href="http://maps.google.com/maps/api/staticmap?center=<?php echo str_replace(' ', '+', $row_contest_info['contestAwardsLocation']); ?>&zoom=13&size=600x400&markers=color:red|<?php echo str_replace(' ', '+', $row_contest_info['contestAwardsLocation']); ?>&sensor=false&KeepThis=true&TB_iframe=true&height=420&width=600" title="Map to <?php echo $row_contest_info['contestAwardsLocName']; ?>"><img src="images/map.png" align="absmiddle" border="0" alt="Map <?php echo $row_contest_info['contestAwardsLocName']; ?>" title="Map <?php echo $row_contest_info['contestAwardsLocName']; ?>" /></a></span>
	<span class="icon"><a class="thickbox" href="http://maps.google.com/maps?f=q&source=s_q&hl=en&q=<?php echo str_replace(' ', '+', $row_contest_info['contestAwardsLocation']); ?>&KeepThis=true&TB_iframe=true&height=450&width=900" title="Driving Directions to <?php echo $row_contest_info['contestAwardsLocName']; ?>"><img src="images/car.png" align="absmiddle" border="0" alt="Driving Directions to <?php echo $row_contest_info['contestAwardsLocName']; ?>" title="Driving Direcitons to <?php echo $row_contest_info['contestAwardsLocName']; ?>" /></a></span><?php } ?>
    <?php if ($row_contest_info['contestAwardsLocation'] != "") echo "<br />".$row_contest_info['contestAwardsLocation']; ?>
</p>
<?php } ?>
