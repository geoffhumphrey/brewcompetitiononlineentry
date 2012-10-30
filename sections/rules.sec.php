<?php 
/**
 * Module:      rules.sec.php 
 * Description: This module displays the public-facing competition rules
 *              specified in the contest_info database table. 
 * 
 */
?>
<?php if (($action != "print") && ($msg != "default")) echo $msg_output; 
if (($row_contest_info['contestLogo'] != "") && (file_exists('user_images/'.$row_contest_info['contestLogo']))) { // display competition's logo if name present in DB and in the correct folder on the server ?>
<img src="<?php echo $base_url; ?>/user_images/<?php echo $row_contest_info['contestLogo']; ?>" width="<?php echo $row_prefs['prefsCompLogoSize']; ?>" align="right" hspace="3" vspace="3" alt="Competition Logo"/>
<?php } ?>
<?php if ($action != "print") { ?>
<p><span class="icon"><img src="<?php echo $base_url; ?>/images/printer.png"  border="0" alt="Print" /></span><a id="modal_window_link" class="data" href="<?php echo $base_url; ?>/output/print.php?section=<?php echo $section; ?>&amp;action=print" title="Print Rules">Print This Page</a></p>
<?php } ?>
<?php echo $row_contest_info['contestRules']; ?>