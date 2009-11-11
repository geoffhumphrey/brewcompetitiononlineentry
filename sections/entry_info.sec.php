<div id="header">	
	<div id="header-inner"><h1><?php echo $row_contest_info['contestName']; ?> Entry Information</h1></div>
</div>
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
<h2>Judging Date</h2>
<p><?php echo dateconvert($row_contest_info['contestDate'], 2); ?></p>
<?php if ($row_contest_info['contestJudgingLocation']) { ?>
<h2>Judging Location</h2>
<p><?php echo $row_contest_info['contestJudgingLocation']; ?></p>
<?php } if ($row_contest_info['contestAwardsLocation'] != "") { ?>
<h2>Awards Location</h2>
<p><?php echo $row_contest_info['contestAwardsLocation']; ?></p>
<?php } ?>
<h2>Entry Fee</h2>
<p><?php echo $row_prefs['prefsCurrency'].$row_contest_info['contestEntryFee']; ?> per entry</p>
<?php if ($row_contest_info['contestCategories']  != "") { ?>
<h2>Payment</h2>
<p><p>After registering and inputting entries, all participants must pay their entry fee(s). Accepted payment methods include:</p>
    <ul>
    	<?php if ($row_prefs['prefsCash'] == "Y") echo "<li>Cash</li>"; ?>
        <?php if ($row_prefs['prefsCheck'] == "Y") echo "<li>Check, made out to <em>".$row_prefs['prefsCheckPayee']."</em></li>"; ?>
        <?php if ($row_prefs['prefsPaypal'] == "Y") echo "<li>PayPal (once registered, click <em>Pay Entry Fees</em> from the main navigation after inputting all entries).</li>"; ?>
    </ul>

<h2>Categories Accepted</h2>
<p><?php echo $row_contest_info['contestCategories']; ?></p>
<?php } if ($row_contest_info['contestBottles'] != "") { ?>
<h2>Bottle Acceptance Rules</h2><p><?php echo $row_contest_info['contestBottles']; ?></p>
<?php } if ($row_contest_info['contestShippingAddress'] != "") { ?>
<h2>Shipping Address</h2>
<p><?php echo $row_contest_info['contestShippingAddress']; ?></p>
<h3>Packing &amp; Shipping Hints</h3>
      <ol>
			<li>Carefully pack your entries in a sturdy box. Line the inside of your carton with a plastic trash bag. Partition and pack each bottle with adequate packaging material. Do not over pack! Write clearly: "Fragile. This Side Up." on the package. Your package should weigh less than 25 pounds. Please refrain from using &quot;messy&quot; packaging materials such a Styrofoam &quot;peanuts&quot; or pellets; please use packaging material such as bubble wrap.</li>
			<li>Every reasonable effort will be made to contact entrants whose bottles have broken to make arrangements for sending replacement bottles.</li>
		  <li>It is not against any Bureau of Alcohol, Tobacco and Firearms (ATF) regulations or federal laws to ship your entries via privately owned shipping company for analytical purposes. However, <strong>IT IS ILLEGAL TO SHIP ALCOHOLIC BEVERAGES VIA THE U.S. POSTAL SERVICE</strong>. Private shipping companies have the right to refuse your shipment if they are informed that the package contains glass and/or alcoholic beverages. Be aware that entries mailed internationally are often required by customs to have proper documentation. These entries might be opened and/or returned to the shipper by customs' officials at their discretion. It is solely the entrant's responsibility to follow all applicable laws and regulations.</li>
	  </ol>    
<?php } 
  if ($row_contest_info['contestDropOff'] != "") { ?>
<h2>Drop Off Locations</h2>
<p><?php echo $row_contest_info['contestDropOff']; ?></p>
  <?php } if ($row_contest_info['contestAwards'] != "") { ?>
<h2>Awards</h2>
<p><?php echo $row_contest_info['contestAwards']; ?></p>
<?php } ?>
