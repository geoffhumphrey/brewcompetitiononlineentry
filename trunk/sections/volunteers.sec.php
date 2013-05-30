<?php 
/**
 * Module:      volunteers.sec.php 
 * Description: This module displays the public-facing competition volunteers
 *              specified in the contest_info database table. 
 * 
 */

$query_contest_info = sprintf("SELECT contestVolunteers FROM %s WHERE id=1", $prefix."contest_info");
$contest_info = mysql_query($query_contest_info, $brewing) or die(mysql_error());
$row_contest_info = mysql_fetch_assoc($contest_info);

if (($action != "print") && ($msg != "default")) echo $msg_output; 
if (($_SESSION['contestLogo'] != "") && (file_exists($_SERVER['DOCUMENT_ROOT'].$sub_directory.'/user_images/'.$_SESSION['contestLogo']))) { // display competition's logo if name present in DB and in the correct folder on the server ?>
<img src="<?php echo $base_url; ?>user_images/<?php echo $_SESSION['contestLogo']; ?>" width="<?php echo $_SESSION['prefsCompLogoSize']; ?>" align="right" hspace="3" vspace="3" alt="Competition Logo"/>
<?php } ?>
<?php if ($action != "print") { ?>
<p><span class="icon"><img src="<?php echo $base_url; ?>images/printer.png"  border="0" alt="Print" /></span><a id="modal_window_link" class="data" href="<?php echo $base_url; ?>output/print.php?section=<?php echo $section; ?>&amp;action=print" title="Print Volunteer Info">Print This Page</a></p>
<?php } 
if ($_SESSION['prefsUseMods'] == "Y") include(INCLUDES.'mods_top.inc.php');
?>
<!--
<h2>Judging and Stewarding</h2>
<?php if (($judge_window_open > 0) && (!isset($_SESSION['loginUsername']))) { ?>
	<p>If you <em>have</em> registered, <a href="<?php echo build_public_url("login","default","default",$sef,$base_url); ?>">log in</a> and then choose <em>Edit Your Info</em> to indicate that you are willing to judge or  steward.</p>
	<p>If you <em>have not</em> registered and are willing to be a judge or steward, <a href="<?php echo build_public_url("register","judge","default",$sef,$base_url); ?>">please register</a>.</p>
<?php } elseif (($judge_window_open > 0) && (isset($_SESSION['loginUsername']))) { ?>
	<p>Since you have already registered, you can <a href="<?php echo build_public_url("list","default","default",$sef,$base_url); ?>">check your info</a> to see whether you have indicated that you are willing to judge and/or steward.</p>
<?php } else { ?>
    <p>If you are willing to judge or steward, please return to register on or after <?php echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestJudgeOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time") ?>.</p>
<?php } ?>
-->
<?php if ($_SESSION['contestVolunteers'] != "") { ?>
<h2>Other Volunteer Info</h2>
<?php echo $_SESSION['contestVolunteers']; ?>
<?php } 

if ($_SESSION['prefsUseMods'] == "Y") include(INCLUDES.'mods_bottom.inc.php');

?>