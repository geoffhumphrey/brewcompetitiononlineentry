<?php 
/**
 * Module:      contact.sec.php 
 * Description: This module displays the contact mechanism for user feedback.
 *              When processed, the request uses the sendmail function.
 * 
 */

/* ---------------- PUBLIC Pages Rebuild Info ---------------------

Beginning with the 1.3.0 release, an effort was begun to separate the programming
layer from the presentation layer for all scripts with this header.

All Public pages have certain variables in common that build the page:
  $header1 = an <h2> header on the page
  $header2 = an <h3> subheader on the page
  $primary_page_info = any information related to the page
  $print_page_link = the "Print This Page" link
  $page_info = the bulk of the information on the page.
  $competition_logo = display of the competition's logo
  
  $labelX = the various labels in a table or on a form
  $messageX = various messages to display

 * ---------------- END Rebuild Info --------------------- */



include(DB.'contacts.db.php');
require_once(INCLUDES.'recaptchalib.inc.php');
if (($action != "print") && ($msg != "default")) echo $msg_output; 


if ($_SESSION['prefsContact'] == "N") {
	$page_info = "";
	$page_info .= "<p>Use the links below to contact individuals involved with coordinating this competition:</p>";
	$page_info .= "<ul>";
	do {
		$page_info .= "<li>".$row_contact['contactFirstName']." ".$row_contact['contactLastName'].", ".$row_contact['contactPosition']." &ndash; <a href='mailto:".$row_contact['contactEmail']."'>".$row_contact['contactEmail']."</a></li>";
	} while ($row_contact = mysql_fetch_assoc($contact)); 
	$page_info .= "</ul>";
	
	echo $page_info;
}

if ($_SESSION['prefsContact'] == "Y") {
	
	$query_contacts = sprintf("SELECT * FROM %s ORDER BY contactLastName ASC",$prefix."contacts");
	$contacts = mysql_query($query_contacts, $brewing) or die(mysql_error());
	$row_contacts = mysql_fetch_assoc($contacts);
	
	$option = "";
		
	do { 
	
		$option .= "<option value=".$row_contact['id'];
		if(isset($_COOKIE['to'])) {
			if ($row_contact['id'] == $_COOKIE['to']) $option .= " SELECTED"; 
			}
		$option .= ">".$row_contact['contactFirstName']." ".$row_contact['contactLastName']." &ndash; ".$row_contact['contactPosition']."</option>";

	
	} while ($row_contact = mysql_fetch_assoc($contacts)); 
   
   	mysql_free_result($contacts);

	$primary_page_info = "<p>Use the form below to contact individuals involved with coordinating this competition.</p>";
	$label1 = "Contact:";
	$label2 = "Your Name (First and Last):";
	$label3 = "Your Email Address:";
	$label4 = "Subject:";
	$label5 = "Message:";
	$label6 = "CAPTCHA:";

	$required_label = "Required";

	if ($msg == "1") {
		$message1 = "<p>Additionally, a copy has been sent to the email address you provided.</p><p>Would you like to send <a href='".build_public_url("contact","default","default",$sef,$base_url)."'>another message</a>?</p>";
		echo $message1; 
	}
	
	if ($msg != "1") {
		
	echo $primary_page_info;
?>
        <form name="form1" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?dbTable=<?php echo $contacts_db_table; ?>&action=email" onSubmit="return CheckRequiredFields()">
        <table class="dataTable">
        <tr>
            <td class="dataLabel" width="5%"><?php echo $label1; ?></td>
            <td class="data" width="25%">
            <select name="to">
                <?php echo $option; ?>
            </select>
            </td>
            <td class="data"><span class="required"><?php echo $required_label; ?></span></td>
        </tr>
        <tr>
            <td class="dataLabel"><?php echo $label2; ?></td>
            <td class="data"><input name="from_name" type="text" size="50" value="<?php if ($msg == "2") echo $_COOKIE['from_name']; ?>"></td>
            <td class="data"><span class="required"><?php echo $required_label; ?></span></td>
        </tr>
        <tr>
            <td class="dataLabel"><?php echo $label3; ?></td>
            <td class="data"><input name="from_email" type="text" size="50" value="<?php if ($msg == "2") echo $_COOKIE['from_email']; ?>"></td>
            <td class="data"><span class="required"><?php echo $required_label; ?></span></td>
        </tr>
        <tr>
            <td class="dataLabel"><?php echo $label4; ?></td>
            <td class="data"><input name="subject" type="text" value="<?php if ($msg == "2") echo $_COOKIE['subject']; else echo $_COOKIE['contestName']; ?>" size="50"></td>
            <td class="data"><span class="required"><?php echo $required_label; ?></span></td>
        </tr>
        <tr>
            <td class="dataLabel"><?php echo $label5; ?></td>
            <td class="data"><textarea name="message" cols="50" rows="10" class="mceNoEditor"><?php if ($msg == "2") echo $_COOKIE['message']; ?></textarea></td>
            <td class="data"><span class="required"><?php echo $required_label; ?></span></td>
        </tr>
        <tr>
            <td class="dataLabel"><?php echo $label6; ?></td>
            <td class="data">
            <?php echo recaptcha_get_html($publickey); ?>
            </td>
            <td class="data"><span class="required"><?php echo $required_label; ?></span></td>
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
<?php } // end if ($msg != 1);
} // end if ($_SESSION['prefsContact'] == "Y")
 
?>