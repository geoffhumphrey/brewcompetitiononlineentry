<?php 
/**
 * Module:      volunteers.sec.php 
 * Description: This module displays the public-facing competition volunteers
 *              specified in the contest_info database table. 
 * 
 */
?>
<?php if (($action != "print") && ($msg != "default")) echo $msg_output; 
if (($row_contest_info['contestLogo'] != "") && (file_exists('user_images/'.$row_contest_info['contestLogo']))) { // display competition's logo if name present in DB and in the correct folder on the server ?>
<img src="user_images/<?php echo $row_contest_info['contestLogo']; ?>" width="<?php echo $row_prefs['prefsCompLogoSize']; ?>" align="right" hspace="3" vspace="3" alt="Competition Logo"/>
<?php } ?>
<?php if ($action != "print") { ?>
<p><span class="icon"><img src="images/printer.png"  border="0" alt="Print" /></span><a id="modal_window_link" class="data" href="output/print.php?section=<?php echo $section; ?>&amp;action=print" title="Print Volunteer Info">Print This Page</a></p>
<?php } ?>

<h2>Judging and Stewarding</h2>
<?php if (($judge_window_open > 0) && (!isset($_SESSION['loginUsername']))) { ?>
	<p>If you <em>have</em> registered, <a href="index.php?section=login">log in</a> and then choose <em>Edit Your Info</em> to indicate that you are willing to judge or  steward.</p>
	<p>If you <em>have not</em> registered and are willing to be a judge or steward, <a href="index.php?section=register&amp;go=judge">please register</a>.</p>
<?php } elseif (($judge_window_open > 0) && (isset($_SESSION['loginUsername']))) { ?>
	<p>Since you have already registered, you can <a href="index.php?section=list">check your info</a> to see whether you have indicated that you are willing to judge and/or steward.</p>
<?php } else { ?>
    <p>If you are willing to judge or steward, please return to register on or after <?php echo getTimeZoneDateTime($row_prefs['prefsTimeZone'], $row_contest_info['contestJudgeOpen'], $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "long", "date-time") ?>.</p>
<?php } ?>

<?php if ($row_contest_info['contestVolunteers'] != "") { ?>
<h2>Other Volunteer Info</h2>
<?php echo $row_contest_info['contestVolunteers']; ?>
<?php } ?>