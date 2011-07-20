<?php
/**
 * Module:      default.sec.php
 * Description: This module houses the intallation's landing page that includes
 *              information about the competition, registration dates/info, and 
 *              winner display after all judging dates have passed.
 */


include(DB.'dropoff.db.php');
if (($row_contest_info['contestLogo'] != "") && (file_exists('user_images/'.$row_contest_info['contestLogo']))) { 
	if (judging_date_return() > 0) { ?>
<img src="user_images/<?php echo $row_contest_info['contestLogo']; ?>" width="<?php echo $row_prefs['prefsCompLogoSize']; ?>" align="right" hspace="3" vspace="3" alt="Competition Logo" />
<?php } 
}
?>
<?php if ($action != "print") { ?>
<p><span class="icon"><img src="images/printer.png"  border="0" alt="Print" /></span><a class="data thickbox" href="output/print.php?section=<?php echo $section; ?>&amp;action=print&amp;KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=750" title="Print General Information">Print This Page</a></p>
<?php } ?>
<?php if ($msg != "default") echo $msg_output; ?>
<?php if ((isset($_SESSION['loginUsername'])) && ($row_user['userLevel'] == "1") && ($section == "admin")) { 
		if ($totalRows_dropoff == 0) echo "<div class=\"error\">No drop-off locations have been specified. <a href=\"index.php?section=admin&amp;action=add&amp;go=dropoff\">Add a drop-off location</a>?</div>";
		if ($totalRows_judging == 0) echo "<div class=\"error\">No judging dates/locations have been specified. <a href=\"index.php?section=admin&amp;action=add&amp;go=judging\">Add a judging location</a>?</div>";
	} 
if (judging_date_return() > 0) { ?>
<p>Thank you for your interest in the <?php echo $row_contest_info['contestName']; ?> organized by <?php if ($row_contest_info['contestHostWebsite'] != "") { ?><a href="<?php echo $row_contest_info['contestHostWebsite']; ?>" target="_blank"><?php } echo $row_contest_info['contestHost']; if ($row_contest_info['contestHostWebsite'] != "") { ?></a><?php } if ($row_contest_info['contestHostLocation'] != "") echo ", ".$row_contest_info['contestHostLocation']; ?>.  Be sure to read the <a href="index.php?section=rules">competition rules</a>.</p>
<?php }
if (judging_date_return() == 0) { 
	include ('judge_closed.sec.php'); 
	if ($row_prefs['prefsDisplayWinners'] == "Y") {  ?>
		<script type="text/javascript" language="javascript" src="js_includes/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="js_includes/jquery.dataTables.js"></script>
	<?php
		include (INCLUDES.'db_tables.inc.php');
		include (DB.'winners.db.php'); 
		include (SECTIONS.'bos.sec.php');
		include (SECTIONS.'winners.sec.php');  
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
if (greaterDate($today,$row_contest_info['contestRegistrationDeadline'])) include('reg_closed.sec.php');
else include('reg_open.sec.php');
// echo "Today: ".strtotime($today)."<br>";
// echo "Deadline: ".strtotime($row_contest_info['contestRegistrationDeadline'])."<br>";
// end registration end check
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
