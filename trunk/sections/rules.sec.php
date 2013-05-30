<?php 
/**
 * Module:      rules.sec.php 
 * Description: This module displays the public-facing competition rules
 *              specified in the contest_info database table. 
 * 
 */
$query_contest_rules = sprintf("SELECT contestRules FROM %s WHERE id='1'", $prefix."contest_info");
$contest_rules = mysql_query($query_contest_rules, $brewing) or die(mysql_error());
$row_contest_rules = mysql_fetch_assoc($contest_rules);

if ($_SESSION['prefsUseMods'] == "Y") include(INCLUDES.'mods_top.inc.php');
if (($action != "print") && ($msg != "default")) echo $msg_output; 
if (($_SESSION['contestLogo'] != "") && (file_exists($_SERVER['DOCUMENT_ROOT'].$sub_directory.'/user_images/'.$_SESSION['contestLogo']))) { // display competition's logo if name present in DB and in the correct folder on the server ?>
<img src="<?php echo $base_url; ?>user_images/<?php echo $_SESSION['contestLogo']; ?>" width="<?php echo $_SESSION['prefsCompLogoSize']; ?>" align="right" hspace="3" vspace="3" alt="Competition Logo"/>
<?php } 
if ($action != "print") { ?>
<p><span class="icon"><img src="<?php echo $base_url; ?>images/printer.png"  border="0" alt="Print" /></span><a id="modal_window_link" class="data" href="<?php echo $base_url; ?>output/print.php?section=<?php echo $section; ?>&amp;action=print" title="Print Rules">Print This Page</a></p>
<?php } 
echo $row_contest_rules['contestRules']; 

if ($_SESSION['prefsUseMods'] == "Y") include(INCLUDES.'mods_bottom.inc.php');
?>