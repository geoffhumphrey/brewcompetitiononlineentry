<?php 
/**
 * Module:      contact.sec.php 
 * Description: This module displays the contact mechanism for user feedback.
 *              When processed, the request uses the sendmail function.
 * 
 */
include(DB.'contacts.db.php');
if ($_SESSION['prefsContact'] == "Y") {
if (($action != "print") && ($msg != "default")) echo $msg_output; 
if ($_SESSION['prefsUseMods'] == "Y") include(INCLUDES.'mods_top.inc.php');
if ($msg != "1") {
//if (!isset($_SESSION['loginUsername'])) { session_start(); }
?>
<p>Use the form below to contact individuals involved with coordinating this competition.</p>
<form name="form1" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?dbTable=<?php echo $contacts_db_table; ?>&action=email" onSubmit="return CheckRequiredFields()">
<table class="dataTable">
<tr>
	<td class="dataLabel" width="5%">Contact:</td>
	<td class="data" width="25%">
    <select name="to">
    	<?php 
		$query_contacts = sprintf("SELECT * FROM %s ORDER BY contactLastName ASC",$prefix."contacts");
		$contacts = mysql_query($query_contacts, $brewing) or die(mysql_error());
		$row_contacts = mysql_fetch_assoc($contacts);
		do { 
    	?>
    	<option value="<?php echo $row_contact['id']; ?>" <?php if(isset($COOKIE['to'])) { if ($row_contact['id'] == $_SESSION['to']) echo " SELECTED"; } ?>><?php echo $row_contact['contactFirstName']." ".$row_contact['contactLastName']." &ndash; ".$row_contact['contactPosition']; ?></option>
        <?php } while ($row_contact = mysql_fetch_assoc($contacts)); 
		mysql_free_result($contacts);
		?>
    </select>
    
    </td>
    <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
	<td class="dataLabel">Your Name (First and Last):</td>
	<td class="data"><input name="from_name" type="text" size="50" value="<?php if ($msg == "2") echo $_SESSION['from_name']; ?>"></td>
    <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
	<td class="dataLabel">Your Email Address:</td>
	<td class="data"><input name="from_email" type="text" size="50" value="<?php if ($msg == "2") echo $_SESSION['from_email']; ?>"></td>
    <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
	<td class="dataLabel">Subject:</td>
	<td class="data"><input name="subject" type="text" value="<?php if ($msg == "2") echo $_SESSION['subject']; else echo $_SESSION['contestName']; ?>" size="50"></td>
    <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
	<td class="dataLabel">Message:</td>
	<td class="data"><textarea name="message" cols="100" rows="10" class="mceNoEditor"><?php if ($msg == "2") echo $_SESSION['message']; ?></textarea></td>
    <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
	<td class="dataLabel">CAPTCHA:</td>
    <td class="data">
    <img id="captcha" src="<?php echo $base_url; ?>captcha/securimage_show.php" alt="CAPTCHA Image" style="border: 1px solid #000000;" />
	<p>
    <object type="application/x-shockwave-flash" data="<?php echo $base_url; ?>captcha/securimage_play.swf?audio_file=<?php echo $base_url; ?>captcha/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#000&amp;borderWidth=1&amp;borderColor=#000" width="19" height="19">
	<param name="movie" value="<?php echo $base_url; ?>captcha/securimage_play.swf?audio_file=<?php echo $base_url; ?>captcha/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#000&amp;borderWidth=1&amp;borderColor=#000" />
	</object>
    &nbsp;Play audio
    </p>
	<p><input type="text" name="captcha_code" size="10" maxlength="6" /><br />Enter the characters above exactly as displayed.</p>
    <p>Can't read the characters?<br /><a href="#" onclick="document.getElementById('captcha').src = '<?php echo $base_url; ?>captcha/securimage_show.php?' + Math.random(); return false">Reload the Captcha Image</a>.</p>
    </td>
    <td class="data"><span class="required">Required</span></td>
    <td class="data">&nbsp;</td>
</tr>
<tr>
  	<td>&nbsp;</td>
  	<td colspan="2" class="data"><input name="submit" type="submit" class="button" value="Send Message"></td>
</tr>
<tr>
  	<td>&nbsp;</td>
  	<td colspan="2" class="data"><input name="clear" type="button" class="button" value="Clear Values" onClick="window.location.href='<?php echo build_public_url("contact","default","default",$sef,$base_url); ?>'"></td>
</tr>
</table>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>
<?php

} 
if ($msg == "1")
 { ?>
<p>Additionally, a copy has been sent to the email address you provided.</p>
<p>Would you like to send <a href="<?php echo build_public_url("contact","default","default",$sef,$base_url); ?>">another message</a>?</p>
<?php } 
} else {
?>
<p>Use the links below to contact individuals involved with coordinating this competition:</p>
<ul>
<?php do { ?>
	<li><?php echo $row_contact['contactFirstName']." ".$row_contact['contactLastName'].", ".$row_contact['contactPosition'].' &ndash; <a href="mailto:'.$row_contact['contactEmail'].'">'.$row_contact['contactEmail'].'</a>'; ?></li>
<?php } while ($row_contact = mysql_fetch_assoc($contact)); ?>
</ul>
<?php } 
if ($_SESSION['prefsUseMods'] == "Y") include(INCLUDES.'mods_bottom.inc.php');
?>