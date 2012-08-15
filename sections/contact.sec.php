<?php 
/**
 * Module:      contact.sec.php 
 * Description: This module displays the contact mechanism for user feedback.
 *              When processed, the request uses the sendmail function.
 * 
 */
include(DB.'contacts.db.php');
if ($row_prefs['prefsContact'] == "Y") {
if (($action != "print") && ($msg != "default")) echo $msg_output; 

if ($msg != "1") {
//if (!isset($_SESSION["loginUsername"])) { session_start(); }
?>
<p>Use the form below to contact individuals involved with coordinating this competition.</p>
<form name="form1" method="post" action="includes/process.inc.php?dbTable=<?php echo $contacts_db_table; ?>&action=email" onSubmit="return CheckRequiredFields()">
<table class="dataTable">
<tr style="background-color: <?php echo $color2; ?>">
	<td class="dataLabel bdr1T" width="5%">Contact:</td>
	<td class="data bdr1T" width="25%">
    <select name="to">
    	<?php 
		mysql_select_db($database, $brewing);
		$query_contacts = sprintf("SELECT * FROM %s ORDER BY contactLastName, contactPosition",$prefix."contacts");
		$contacts = mysql_query($query_contacts, $brewing) or die(mysql_error());
		mysql_free_result($contacts);
		do { 
    	?>
    	<option value="<?php echo $row_contact['id']; ?>" <?php if(isset($COOKIE['to'])) { if ($row_contact['id'] == $_COOKIE['to']) echo " SELECTED"; } ?>><?php echo $row_contact['contactFirstName']." ".$row_contact['contactLastName']." &ndash; ".$row_contact['contactPosition']; ?></option>
        <?php } while ($row_contact = mysql_fetch_assoc($contacts)) ; ?>
    </select>
    </td>
    <td class="data bdr1T"><span class="required">Required</span></td>
</tr>
<tr style="background-color: <?php echo $color1; ?>">
	<td class="dataLabel">Your Name (First and Last):</td>
	<td class="data"><input name="from_name" type="text" size="50" value="<?php if ($msg == "2") echo $_COOKIE['from_name']; ?>"></td>
    <td class="data"><span class="required">Required</span></td>
</tr>
<tr style="background-color: <?php echo $color2; ?>">
	<td class="dataLabel">Your Email Address:</td>
	<td class="data"><input name="from_email" type="text" size="50" value="<?php if ($msg == "2") echo $_COOKIE['from_email']; ?>"></td>
    <td class="data"><span class="required">Required</span></td>
</tr>
<tr style="background-color: <?php echo $color1; ?>">
	<td class="dataLabel">Subject:</td>
	<td class="data"><input name="subject" type="text" value="<?php if ($msg == "2") echo $_COOKIE['subject']; else echo $row_contest_info['contestName']; ?>" size="50"></td>
    <td class="data"><span class="required">Required</span></td>
</tr>
<tr style="background-color: <?php echo $color2; ?>">
	<td class="dataLabel">Message:</td>
	<td class="data"><textarea name="message" cols="70" rows="20" class="mceNoEditor"><?php if ($msg == "2") echo $_COOKIE['message']; ?></textarea></td>
    <td class="data"><span class="required">Required</span></td>
</tr>
<tr style="background-color: <?php echo $color1; ?>">
	<td class="dataLabel bdr1B">CAPTCHA:</td>
    <td class="data bdr1B">
    <img id="captcha" src="captcha/securimage_show.php" alt="CAPTCHA Image" style="border: 1px solid #000000;" /><br />
	<object type="application/x-shockwave-flash" data="captcha/securimage_play.swf?audio=captcha/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#777&amp;borderWidth=1&amp;borderColor=#000" height="19" width="19">
	    <param name="movie" value="captcha/securimage_play.swf?audio=captcha/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#777&amp;borderWidth=1&amp;borderColor=#000" />
  	</object>&nbsp;Play audio
	<p><input type="text" name="captcha_code" size="10" maxlength="6" /> Enter the characters above exactly as displayed.</p>
    <p>Can't read the characters? <a href="#" onclick="document.getElementById('captcha').src = 'captcha/securimage_show.php?' + Math.random(); return false">Reload the Captcha Image</a>.</p>
    </td>
    <td class="data bdr1B"><span class="required">Required</span></td>
</tr>
<tr>
  	<td>&nbsp;</td>
  	<td colspan="2" class="data"><input name="submit" type="submit" class="button" value="Send Message"></td>
</tr>
<tr>
  	<td>&nbsp;</td>
  	<td colspan="2" class="data"><input name="clear" type="button" class="button" value="Clear Values" onClick="window.location.href='index.php?section=contact'"></td>
</tr>
</table>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],$pg,$msg,$id); ?>">
</form>
<?php } 
if ($msg == "1")
 { ?>
<p>Additionally, a copy has been sent to the email address you provided.</p>
<p>Would you like to send <a href="index.php?section=contact">another message</a>?</p>
<?php } 
} else {
?>
<p>Use the links below to contact individuals involved with coordinating this competition:</p>
<ul>
<?php do { ?>
	<li><?php echo $row_contact['contactFirstName']." ".$row_contact['contactLastName'].", ".$row_contact['contactPosition'].' &ndash; <a href="mailto:'.$row_contact['contactEmail'].'">'.$row_contact['contactEmail'].'</a>'; ?></li>
<?php } while ($row_contact = mysql_fetch_assoc($contact)); ?>
</ul>
<?php } ?>