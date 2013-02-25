<?php 
/**
 * Module:      volunteers.sec.php 
 * Description: This module displays the public-facing competition volunteers
 *              specified in the contest_info database table. 
 * 
 */

if (($action != "print") && ($msg != "default")) echo $msg_output; 
if (($row_contest_info['contestLogo'] != "") && (file_exists($_SERVER['DOCUMENT_ROOT'].$sub_directory.'/user_images/'.$row_contest_info['contestLogo']))) { // display competition's logo if name present in DB and in the correct folder on the server ?>
<img src="<?php echo $base_url; ?>user_images/<?php echo $row_contest_info['contestLogo']; ?>" width="<?php echo $row_prefs['prefsCompLogoSize']; ?>" align="right" hspace="3" vspace="3" alt="Competition Logo"/>
<?php } ?>
<?php if ($action != "print") { ?>
<p><span class="icon"><img src="<?php echo $base_url; ?>images/printer.png"  border="0" alt="Print" /></span><a id="modal_window_link" class="data" href="<?php echo $base_url; ?>output/print.php?section=<?php echo $section; ?>&amp;action=print" title="Print Volunteer Info">Print This Page</a></p>
<?php } 
if ($row_prefs['prefsUseMods'] == "Y") include(INCLUDES.'mods_top.inc.php');
?>

<h2>Judging and Stewarding</h2>
<?php if (($judge_window_open > 0) && (!isset($_SESSION['loginUsername']))) { ?>
	<p>If you <em>have</em> registered, <a href="<?php echo build_public_url("login","default","default",$sef,$base_url); ?>">log in</a> and then choose <em>Edit Your Info</em> to indicate that you are willing to judge or  steward.</p>
	<p>If you <em>have not</em> registered and are willing to be a judge or steward, <a href="<?php echo build_public_url("register","judge","default",$sef,$base_url); ?>">please register</a>.</p>
<?php } elseif (($judge_window_open > 0) && (isset($_SESSION['loginUsername']))) { ?>
	<p>Since you have already registered, you can <a href="<?php echo build_public_url("list","default","default",$sef,$base_url); ?>">check your info</a> to see whether you have indicated that you are willing to judge and/or steward.</p>
<?php } else { ?>
    <p>If you are willing to judge or steward, please return to register on or after <?php echo getTimeZoneDateTime($row_prefs['prefsTimeZone'], $row_contest_info['contestJudgeOpen'], $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "long", "date-time") ?>.</p>
<?php } ?>

<?php if ($row_contest_info['contestVolunteers'] != "") { ?>
<h2>Other Volunteer Info</h2>
<?php echo $row_contest_info['contestVolunteers']; ?>
<?php } 

if ($row_prefs['prefsUseMods'] == "Y") include(INCLUDES.'mods_bottom.inc.php');

?>