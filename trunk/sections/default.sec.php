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
<img src="<?php echo $base_url; ?>/user_images/<?php echo $row_contest_info['contestLogo']; ?>" width="<?php echo $row_prefs['prefsCompLogoSize']; ?>" align="right" hspace="3" vspace="3" alt="Competition Logo" />
<?php } 
}
?>
<?php if ($action != "print") { ?>
<p><span class="icon"><img src="<?php echo $base_url; ?>/images/printer.png"  border="0" alt="Print" /></span><a id="modal_window_link" href="<?php echo $base_url; ?>/output/print.php?section=<?php echo $section; ?>&amp;action=print" title="Print General Information">Print This Page</a></p>
<?php } ?>
<?php if (($action != "print") && ($msg != "default")) echo $msg_output; ?>
<?php if ((isset($_SESSION['loginUsername'])) && ($row_user['userLevel'] == "1") && ($section == "admin")) { 
		if ($totalRows_dropoff == 0) echo "<div class=\"error\">No drop-off locations have been specified. <a href=\"index.php?section=admin&amp;action=add&amp;go=dropoff\">Add a drop-off location</a>?</div>";
		if ($totalRows_judging == 0) echo "<div class=\"error\">No judging dates/locations have been specified. <a href=\"index.php?section=admin&amp;action=add&amp;go=judging\">Add a judging location</a>?</div>";
	} 
if (judging_date_return() > 0) { ?>
<p>Thank you for your interest in the <?php echo $row_contest_info['contestName']; ?> organized by <?php if ($row_contest_info['contestHostWebsite'] != "") { ?><a href="<?php echo $row_contest_info['contestHostWebsite']; ?>" target="_blank"><?php } echo $row_contest_info['contestHost']; if ($row_contest_info['contestHostWebsite'] != "") { ?></a><?php } if ($row_contest_info['contestHostLocation'] != "") echo ", ".$row_contest_info['contestHostLocation']; ?>.  Be sure to read the <a href="<?php echo build_public_url("rules","default","default",$sef,$base_url); ?>">competition rules</a>.</p>
<?php }
if (judging_date_return() == 0) { 
	include ('judge_closed.sec.php'); 
	if ($row_prefs['prefsDisplayWinners'] == "Y") {  
		function judging_winner_display($delay) {
			include(CONFIG.'config.php');
			mysql_select_db($database, $brewing);
			$query_check = sprintf("SELECT judgingDate FROM %s ORDER BY judgingDate DESC LIMIT 1", $prefix."judging_locations");
			$check = mysql_query($query_check, $brewing) or die(mysql_error());
			$row_check = mysql_fetch_assoc($check);
			$today = strtotime("now");
			$r = $row_check['judgingDate'] + $delay;
			if ($r > $today) return FALSE; else return TRUE;
		}
		$delay = $row_prefs['prefsWinnerDelay'] * 3600;
		if (judging_winner_display($delay)) {
		//include (INCLUDES.'db_tables.inc.php');
		//include (DB.'winners.db.php'); ?>
        <h2>Best of Show Winners<?php if ($section == "past_winners") echo ": ".$trimmed; if ($row_bos_scores['count'] > 0) { if (($section == "default") && ($action != "print")) { ?><span class="icon">&nbsp;<a href="<?php echo $base_url; ?>/output/results_download.php?section=admin&amp;go=judging_scores_bos&amp;action=download&amp;filter=default&amp;view=pdf"><img src="<?php echo $base_url; ?>/images/page_white_acrobat.png" border="0" title="Download a PDF of the Best of Show Winner List"/></a></span><span class="icon"><a href="<?php echo $base_url; ?>/output/results_download.php?section=admin&amp;go=judging_scores_bos&amp;action=download&amp;filter=default&amp;view=html"><img src="<?php echo $base_url; ?>/images/html.png" border="0" title="Download the Best of Show Winner List in HTML format"/></a></span><?php } } ?></h2>
        <?php include (SECTIONS.'bos.sec.php'); ?>
		<h2>Winning Entries<?php if ($section == "past_winners") echo ": ".$trimmed; if ($row_scores['count'] > 0) { if (($section == "default") && ($action != "print")){ ?><span class="icon">&nbsp;<a href="<?php echo $base_url; ?>/output/results_download.php?section=admin&amp;go=judging_scores&amp;action=default&amp;filter=none&amp;view=pdf"><img src="<?php echo $base_url; ?>/images/page_white_acrobat.png" border="0" title="Download a PDF of the Winners List"/></a></span><span class="icon"><a href="<?php echo $base_url; ?>/output/results_download.php?section=admin&amp;go=judging_scores&amp;action=download&amp;filter=default&amp;view=html"><img src="<?php echo $base_url; ?>/images/html.png" border="0" title="Download the Winners List in HTML format"/></a></span><?php } } ?></h2>
        <?php 
		if ($row_prefs['prefsWinnerMethod'] == "1") include (SECTIONS.'winners_category.sec.php'); 
		elseif ($row_prefs['prefsWinnerMethod'] == "2") include (SECTIONS.'winners_subcategory.sec.php'); 
		else include (SECTIONS.'winners.sec.php'); 
		}
		
		else {
		$query_check = "SELECT judgingDate FROM $judging_locations_db_table ORDER BY judgingDate DESC LIMIT 1";
		$check = mysql_query($query_check, $brewing) or die(mysql_error());
		$row_check = mysql_fetch_assoc($check);
		echo "<h2>Winning Entries</h2><p>Winners will be posted on or after ".getTimeZoneDateTime($row_prefs['prefsTimeZone'], ($row_check['judgingDate']+$delay), $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "long", "date-time").".</p>";
		}
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
if (($registration_open == "2") || (open_limit($totalRows_log,$row_prefs['prefsEntryLimit'],$registration_open))) include('reg_closed.sec.php');
else include('reg_open.sec.php');
// echo "Today: ".strtotime("now")."<br>"; 
// echo "Deadline: ".strtotime($row_contest_info['contestRegistrationDeadline'])."<br>";
// end registration end check
} 

if ($row_contest_info['contestCircuit'] != "") { ?>
<h2>Circuit Qualification</h2>
<?php 
echo $row_contest_info['contestCircuit'];
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
    <p><?php if ($row_sponsors['sponsorURL'] != "") { ?><a href="<?php echo $row_sponsors['sponsorURL']; ?>" target="_blank"><?php } ?><img src="<?php if (($row_sponsors['sponsorImage'] !="") && (file_exists('user_images/'.$row_sponsors['sponsorImage']))) echo $base_url."/user_images/".$row_sponsors['sponsorImage']; else echo $base_url."/images/no_image.png"; ?>" width="100" border="0" alt="<?php echo $row_sponsors['sponsorName']; ?> Logo" /><?php if ($row_sponsors['sponsorURL'] != "") { ?></a><?php } ?></p>
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
