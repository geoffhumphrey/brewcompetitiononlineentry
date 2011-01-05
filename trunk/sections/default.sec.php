<?php 
//if  ($judgingDateReturn == "false") { 
	if (($row_contest_info['contestLogo'] != "") && (file_exists('user_images/'.$row_contest_info['contestLogo']))) { // display competition's logo if name present in DB and in the correct folder on the server ?>
	<img src="user_images/<?php echo $row_contest_info['contestLogo']; ?>" width="<?php echo $row_prefs['prefsCompLogoSize']; ?>" align="right" hspace="3" vspace="3" alt="Competition Logo" />
<?php 
	} 
//} ?>
<?php if ($action != "print") { ?><p><span class="icon"><img src="images/printer.png"  border="0" alt="Print" /></span><a class="data thickbox" href="print.php?section=<?php echo $section; ?>&amp;action=print&amp;KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=750" title="Print General Information">Print This Page</a></p><?php } ?>
<?php if ($msg != "default") echo $msg_output; ?>
<?php if (($version >= "1.1.3") && ($section == "admin")) { 
		if ((isset($_SESSION['loginUsername'])) && ($row_user['userLevel'] == "1") && ($totalRows_dropoff == 0)) echo "<div class=\"error\">No drop-off locations have been specified. <a href=\"index.php?section=admin&amp;action=add&amp;go=dropoff\">Add a drop-off location</a>?</div>";
		if ((isset($_SESSION['loginUsername'])) && ($row_user['userLevel'] == "1") && ($totalRows_judging == 0)) echo "<div class=\"error\">No judging dates/locations have been specified. <a href=\"index.php?section=admin&amp;action=add&amp;go=judging\">Add a judging location</a>?</div>";
	} 
?>
<p>Thank you for your interest in the <?php echo $row_contest_info['contestName']; ?> organized by <?php if ($row_contest_info['contestHostWebsite'] != "") { ?><a href="<?php echo $row_contest_info['contestHostWebsite']; ?>" target="_blank"><?php } echo $row_contest_info['contestHost']; if ($row_contest_info['contestHostWebsite'] != "") { ?></a><?php } if ($row_contest_info['contestHostLocation'] != "") echo ", ".$row_contest_info['contestHostLocation']; ?>.  Be sure to read the <a href="index.php?section=rules">full competition rules</a>.</p>
<?php 
//if ($totalRows_archive > 0) include ('past_winners.sec.php'); 
//if (greaterDate($today,$reg_deadline)) include ('reg_closed.sec.php'); 
//if (!greaterDate($today,$reg_deadline))include ('judge_closed.sec.php');
if ($judgingDateReturn == "true") { 
	include ('judge_closed.sec.php'); 
	echo "<p>Judging has already taken place.</p>"; 
	if ($row_prefs['prefsDisplayWinners'] == "Y") { 
		include ('sections/bos.sec.php');
		include ('sections/winners.sec.php');  
	} 
}
else 
{ 
?>
<?php if (!isset($_SESSION['loginUsername'])){ ?>
<p>You only need to register your information once and can return to this site to enter more brews or edit the brews you've entered.
	<?php if ($row_prefs['prefsPaypal'] == "Y") { ?>
	You can even pay your entry fees online if you wish.
	<?php } ?></p>
<?php } 
if (!greaterDate($today,$reg_deadline)) include('reg_open.sec.php'); // end registration end check
} 
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
    <p><?php if ($row_sponsors['sponsorURL'] != "") { ?><a href="<?php echo $row_sponsors['sponsorURL']; ?>" target="_blank"><?php } ?><img src="<?php if ($row_sponsors['sponsorImage'] !="") echo "user_images/".$row_sponsors['sponsorImage']; elseif ($row_contest_info['contestLogo'] != "") echo "user_images/".$row_contest_info['contestLogo']; else echo "images/no_image.png"; ?>" width="100" border="0" alt="<?php echo $row_sponsors['sponsorName']; ?> Logo" /><?php if ($row_sponsors['sponsorURL'] != "") { ?></a><?php } ?></p>
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
