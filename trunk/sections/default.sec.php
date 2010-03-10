<?php if ($action != "print") { ?><p><span class="icon"><img src="images/printer.png" align="absmiddle" /></span><a class="data" href="#" onClick="window.open('print.php?section=<?php echo $section; ?>&action=print','','height=600,width=800,toolbar=no,resizable=yes,scrollbars=yes'); return false;">Print This Page</a></p><?php } 
if ($msg != "default") echo $msg_output;
if ($version >= "1.1.3") { 
		if ((isset($_SESSION['loginUsername'])) && ($row_user['userLevel'] == "1") && ($totalRows_dropoff == 0)) echo "<div class=\"error\">No drop-off locations have been specified. <a href=\"index.php?section=admin&action=add&go=dropoff\">Add a drop-off location</a>?</div>";
		if ((isset($_SESSION['loginUsername'])) && ($row_user['userLevel'] == "1") && ($totalRows_judging == 0)) echo "<div class=\"error\">No judging dates/locations have been specified. <a href=\"index.php?section=admin&action=add&go=judging\">Add a judging location</a>?</div>";
	}
if ($totalRows_archive > 0) include ('past_winners.sec.php'); 
if (greaterDate($today,$deadline)) { 
?>
	<?php if (($row_contest_info['contestLogo'] != "") && (file_exists('user_images/'.$row_contest_info['contestLogo']))) { // display competition's logo if name present in DB and in the correct folder on the server ?>
	<img src="user_images/<?php echo $row_contest_info['contestLogo']; ?>" width="<?php echo $row_prefs['prefsCompLogoSize']; ?>" align="right" hspace="3" vspace="3" />
	<?php } ?>
<h2>Thanks To All Who Participated in the <?php echo $row_contest_info['contestName']; ?>!</h2>
<p>There were <?php echo $totalRows_entries; ?> entries and <?php echo $totalRows_brewers; ?> registered brewers, judges, and stewards.</p>
	<?php 
	if ($judgingDateReturn == "true")
	 {
	 ?>
	<p>Judging has already taken place.</p>
	<?php include ('closed.sec.php'); }
} 
else 
{ 
?>
<?php if (($row_contest_info['contestLogo'] != "") && (file_exists('user_images/'.$row_contest_info['contestLogo']))) { // display competition's logo if name present in DB and in the correct folder on the server ?>
<img src="user_images/<?php echo $row_contest_info['contestLogo']; ?>" width="<?php echo $row_prefs['prefsCompLogoSize']; ?>" align="right" hspace="3" vspace="3" />
<?php } ?>
<p>Thank you for your interest in the <?php echo $row_contest_info['contestName']; ?> organized by <?php if ($row_contest_info['contestHostWebsite'] != "") { ?><a href="<?php echo $row_contest_info['contestHostWebsite']; ?>" target="_blank"><?php } echo $row_contest_info['contestHost']; if ($row_contest_info['contestHostWebsite'] != "") { ?>
</a><?php } if ($row_contest_info['contestHostLocation'] != "") echo ", ".$row_contest_info['contestHostLocation']; ?>.  Be sure to read the <a href="index.php?section=rules">full competition rules</a>.</p>
<?php if (!isset($_SESSION['loginUsername'])){ ?>
<h2>Registration</h2>
<p>You only need to register your information once and can return to this site to enter more brews or edit the brews you've entered.
  <?php if ($row_prefs['prefsPaypal'] == "Y") { ?>
  You can even pay your entry fees online if you wish.
  <?php } ?>
</p>
<p>If you have already registered, please <a href="index.php?section=login">log in</a> to add, view, edit, or delete your entries as well as indicate that you are willing to judge or  steward.</p>
<h3>Enter Your Brews</h3>
<p>To enter your brews, please proceed through the <a href="index.php?section=register">registration process</a>.</p>
<h3>Judging and Stewarding</h3>
<p>If you <em>have not</em> registered and are willing to be a judge, <a href="index.php?section=register&go=judge">please register</a>.</p>
<p>If you <em>have</em> registered, <a href="index.php?section=login">log in</a> and then choose <em>Edit Your Info</em> to indicate that you are willing to judge or  steward.</p>
<?php } ?>
<h2>Competition Date<?php if ($totalRows_judging > 1) echo "s"; ?></h2>
<?php if ($totalRows_judging == 0) echo "<p>The competition judging date is yet to be determined. Please check back later."; else { ?>
<p>Judging for the <?php echo $row_contest_info['contestName']; ?> will take place:</p>
<ul>
<?php do { ?>
<li>
<?php 
	if ($row_judging['judgingDate'] != "") echo dateconvert($row_judging['judgingDate'], 2)." at "; echo $row_judging['judgingLocName']; 
	if ($row_judging['judgingTime'] != "") echo ", ".$row_judging['judgingTime']; if (($row_judging['judgingLocation'] != "") && ($action != "print"))  { ?>&nbsp;&nbsp;<span class="icon"><a class="thickbox" href="http://maps.google.com/maps/api/staticmap?center=<?php echo str_replace(' ', '+', $row_judging['judgingLocation']); ?>&zoom=13&size=600x400&markers=color:red|<?php echo str_replace(' ', '+', $row_judging['judgingLocation']); ?>&sensor=false&KeepThis=true&TB_iframe=true&height=420&width=600" title="Map to <?php echo $row_judging['judgingLocName']; ?>"><img src="images/map.png" align="absmiddle" border="0" alt="Map <?php echo $row_judging['judgingLocName']; ?>" title="Map <?php echo $row_judging['judgingLocName']; ?>" /></a></span>
	<span class="icon"><a class="thickbox" href="http://maps.google.com/maps?f=q&source=s_q&hl=en&q=<?php echo str_replace(' ', '+', $row_judging['judgingLocation']); ?>&KeepThis=true&TB_iframe=true&height=450&width=900" title="Driving Directions to <?php echo $row_judging['judgingLocName']; ?>"><img src="images/car.png" align="absmiddle" border="0" alt="Driving Directions to <?php echo $row_judging['judgingLocName']; ?>" title="Driving Direcitons to <?php echo $row_judging['judgingLocName']; ?>" /></a></span><?php } ?>
    <?php if ($row_judging['judgingLocation'] != "") echo "<br />".$row_judging['judgingLocation']; ?>
</li>
<?php } while ($row_judging = mysql_fetch_assoc($judging)); ?>
</ul>
<?php } ?>
<h2>Registration Deadline</h2>
<p>Registration will close on <?php $date = $row_contest_info['contestRegistrationDeadline']; echo dateconvert($date, 2); ?>. Please note: registered users will <em>not</em> be able to add, view, edit or delete entries after <?php $date = $row_contest_info['contestRegistrationDeadline']; echo dateconvert($date, 2); ?>.</p>
<h2>Entry Deadline</h2>
<p>All entries must be received by our shipping location <?php if ($totalRows_dropoff > 0) echo "or at a drop-off location"; ?> by <?php $date = $row_contest_info['contestEntryDeadline']; echo dateconvert($date, 2); ?>. Entries will not be accepted beyond this date.</p> 
<?php } 
if ($row_prefs['prefsSponsors'] == "Y") {
if ($totalRows_sponsors > 0) {
?>
<h2>Sponsors</h2>
<p><?php echo $row_contest_info['contestHost']; ?> is proud to have the following <?php if ($row_prefs['prefsSponsorLogos'] == "Y") echo "<a href=\"index.php?section=sponsors\">sponsors</a>"; else echo "sponsors"; ?> for the <?php echo $row_contest_info['contestName']; ?>:
<?php if ($row_prefs['prefsSponsorLogos'] == "Y") { ?>
<table>
  <tr>
    <?php
	$sponsors_endRow = 0;
	$sponsors_columns = 6; // number of columns
	$sponsors_hloopRow1 = 0; // first row flag
	do {
    	if (($sponsors_endRow == 0) && ($sponsors_hloopRow1++ != 0)) echo "<tr>";
    ?>
    <td class="looper">
    <p><?php if ($row_sponsors['sponsorURL'] != "") { ?><a href="<?php echo $row_sponsors['sponsorURL']; ?>" target="_blank"><?php } echo $row_sponsors['sponsorName']; ?><?php if ($row_sponsors['sponsorURL'] != "") { ?></a><?php } if ($row_sponsors['sponsorLocation'] != "") echo "<br>".$row_sponsors['sponsorLocation']; ?></p>
    <p><?php if ($row_sponsors['sponsorURL'] != "") { ?><a href="<?php echo $row_sponsors['sponsorURL']; ?>" target="_blank"><?php } ?><img src="<?php if ($row_sponsors['sponsorImage'] !="") echo "user_images/".$row_sponsors['sponsorImage']; elseif ($row_contest_info['contestLogo'] != "") echo "user_images/".$row_contest_info['contestLogo']; else echo "images/no_image.png"; ?>" width="100" border="0" /><?php if ($row_sponsors['sponsorURL'] != "") { ?></a><?php } ?></p>
    </td>
    <?php  $sponsors_endRow++;
	if ($sponsors_endRow >= $sponsors_columns) {
  	?>
  </tr>
  <?php
 	$sponsors_endRow = 0;
  		}
	} while ($row_sponsors = mysql_fetch_assoc($sponsors));
	if ($sponsors_endRow != 0) {
	while ($sponsors_endRow < $sponsors_columns) {
    echo("<td>&nbsp;</td>");
    $sponsors_endRow++;
	}
echo("</tr>");
}
?>
</table>
<?php  } // end if use sponsor logos 
else { ?>
<ul id="sponsor">
	<?php do { ?>
    <li><?php if ($row_sponsors['sponsorURL'] != "") { ?><a href="<?php echo $row_sponsors['sponsorURL']; ?>" target="_blank"><?php } echo $row_sponsors['sponsorName']; ?><?php if ($row_sponsors['sponsorURL'] != "") { ?></a><?php } ?></li>
    <?php } while ($row_sponsors = mysql_fetch_assoc($sponsors)); ?>
</ul>
<?php 
		} // end if no logos 
	} // end if (totalRows_sponsors > 0)
} // end if prefs dictate display sponsors 
?>
