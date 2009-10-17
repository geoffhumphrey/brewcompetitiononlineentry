<div id="header">	
	<div id="header-inner"><h1><?php echo $row_contest_info['contestName']; ?> Entry Information</h1></div>
</div>
<table>
  <tr>
  	<td colspan="3"><h2>Contact</h2></td>
  </tr>
  <tr>
    <td class="dataLabel">Contest Coordinator:</td>
    <td class="data"><a href="mailto:<?php echo $row_contest_info['contestContactEmail']; ?>?subject=<?php echo $row_contest_info['contestName']; ?>"><?php echo $row_contest_info['contestContactName']; ?></a></td>
  </tr>
  <tr>
  	<td colspan="3"><h2>General Info</h2></td>
  </tr>
  <tr>
    <td class="dataLabel">Entry Deadline:</td>
    <td class="data"><?php echo dateconvert($row_contest_info['contestEntryDeadline'], 2); ?></td>
  </tr>
  <tr>
    <td class="dataLabel">Judging Date:</td>
    <td class="data"><?php echo dateconvert($row_contest_info['contestDate'], 2); ?></td>
  </tr>
  <?php if ($row_contest_info['contestJudgingLocation']) { ?>
  <tr>
    <td class="dataLabel">Judging Location:</td>
    <td class="data"><?php echo $row_contest_info['contestJudgingLocation']; ?></td>
  </tr>
  <?php } if ($row_contest_info['contestAwardsLocation'] != "") { ?>
  <tr>
    <td class="dataLabel">Awards Location:</td>
    <td class="data"><?php echo $row_contest_info['contestAwardsLocation']; ?></td>
  </tr>
  <?php } ?>
  <tr>
  	<td colspan="3"><h2>Entries</h2></td>
  </tr>
  <tr>
    <td class="dataLabel">Entry Fee:</td>
    <td class="data"><?php echo $row_prefs['prefsCurrency'].$row_contest_info['contestEntryFee']; ?> per entry</td>
  </tr>
  <?php if ($row_contest_info['contestCategories']  != "") { ?>
  <tr>
    <td class="dataLabel">Categories Accepted:</td>
    <td class="data"><?php echo $row_contest_info['contestCategories']; ?></td>
  </tr>
  <?php } if ($row_contest_info['contestBottles'] != "") { ?>
  <tr>
    <td class="dataLabel">Bottle Acceptance Rules:</td>
    <td class="data"><?php echo $row_contest_info['contestBottles']; ?></td>
  </tr>
  <?php } if ($row_contest_info['contestShippingAddress'] != "") { ?>
  <tr>
    <td class="dataLabel">Shipping Address:</td>
    <td class="data"><?php echo $row_contest_info['contestShippingAddress']; ?>
    <p><strong>Packing &amp; Shipping Hints</strong></p>
	  <ol>
			<li>Carefully pack your entries in a sturdy box. Line the inside of your carton with a plastic trash bag. Partition and pack each bottle with adequate packaging material. Do not over pack! Write clearly: "Fragile. This Side Up." on the package. Your package should weigh less than 25 pounds. Please refrain from using &quot;messy&quot; packaging materials such a Styrofoam &quot;peanuts&quot; or pellets; please use packaging material such as bubble wrap.</li>
			<li>Every reasonable effort will be made to contact entrants whose bottles have broken to make arrangements for sending replacement bottles.</li>
		  <li>It is not against any Bureau of Alcohol, Tobacco and Firearms (ATF) regulations or federal laws to ship your entries via privately owned shipping company for analytical purposes. However, <strong>IT IS ILLEGAL TO SHIP ALCOHOLIC BEVERAGES VIA THE U.S. POSTAL SERVICE</strong>. Private shipping companies have the right to refuse your shipment if they are informed that the package contains glass and/or alcoholic beverages. Be aware that entries mailed internationally are often required by customs to have proper documentation. These entries might be opened and/or returned to the shipper by customs' officials at their discretion. It is solely the entrant's responsibility to follow all applicable laws and regulations.</li>
	  </ol>
    </td>
  </tr>
  <?php } 
  if ($row_contest_info['contestDropOff'] != "") { ?>
  <tr>
    <td class="dataLabel">Drop Off Locations:</td>
    <td class="data"><?php echo $row_contest_info['contestDropOff']; ?></td>
    <td class="data">&nbsp;</td>
  </tr>
  <?php } if ($row_contest_info['contestAwards'] != "") { ?>
  <tr>
  	<td colspan="3"><h2>Awards</h2></td>
  </tr>
  <tr>
    <td class="dataLabel">Awards Structure:</td>
    <td class="data"><?php echo $row_contest_info['contestAwards']; ?></td>
  </tr>
  <?php } ?>
</table>