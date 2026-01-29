<?php
/**
 * Module:      contact.pub.php
 * Description: This module displays the contact mechanism for user feedback.
 *              When processed, the request uses the sendmail function.
 *
 */

// Redirect if directly accessed
if ((!isset($_SESSION['prefs'.$prefix_session])) || ((isset($_SESSION['prefs'.$prefix_session])) && (!isset($base_url)))) {
    $redirect = "../../index.php#contact";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

include (DB.'contacts.db.php');

if ($_SESSION['prefsContact'] == "N") {

	$page_info = "";

    if ($totalRows_contact == 0) $page_info .= sprintf("<p>%s</p>",$contact_text_004);
    
    else {
    	
        $page_info .= sprintf("<p>%s</p>",$contact_text_000);
    	$page_info .= "<ul>";
    	
        do {

            $secretKey = base64_encode(bin2hex($password));
            $nacl = base64_encode(bin2hex($server_root));
            $link = sprintf('%06d', $row_contact['id']);
            $link = simpleEncrypt($link, $secretKey, $nacl);
            $email_redirect_link = sprintf("%sincludes/output.inc.php?section=contact&action=edit&tb=no-print&token=%s",$base_url,$link);
            $page_info .= sprintf("<li><a data-fancybox data-type=\"iframe\" class=\"modal-window-link hide-loader\" href=\"%s\">%s %s</a> &ndash; %s</li>",$email_redirect_link,$row_contact['contactFirstName'],$row_contact['contactLastName'],$row_contact['contactPosition']);
            
    	} while ($row_contact = mysqli_fetch_assoc($contact));
    	
        $page_info .= "</ul>";
    }

	echo $page_info;

}

if ($_SESSION['prefsContact'] == "Y") {

	$option = "";

    if ($totalRows_contact == 0) echo sprintf("<p>%s</p>",$contact_text_004);

    else {

    	do {

    		$option .= "<option value=\"".$row_contact['id']."\"";
            
    		if (isset($_COOKIE['to'])) {
    			// if ($row_contact['id'] == $_COOKIE['to']) $option .= " SELECTED";
    		}

    		$option .= ">".$row_contact['contactFirstName']." ".$row_contact['contactLastName']." &ndash; ".$row_contact['contactPosition']."</option>";


    	} while ($row_contact = mysqli_fetch_assoc($contact));

    	$primary_page_info = sprintf("<p>%s</p>",$contact_text_001);
    	$label1 = $label_contact;
    	$label2 = $label_name;
    	$label3 = $label_email;
    	$label4 = $label_subject;
    	$label5 = $label_message;
    	$label6 = "CAPTCHA";

    	if ($msg == "1") {
            if ($_SESSION['prefsEmailCC'] == 1) $message1 = sprintf("<p>%s <a href='".build_public_url("contact","default","default","default",$sef,$base_url)."'>%s</a></p>",$contact_text_002,$contact_text_003); 
    		else $message1 = sprintf("<p><a href='".build_public_url("contact","default","default","default",$sef,$base_url)."'>%s</a></p>",$contact_text_003);
    		echo $message1;
    	}

    	if ($msg != "1") {

    	echo $primary_page_info;

        ?>

        <div id="message-container"></div>

        <form id="contact-form" class="justify-content-center hide-loader-form-submit needs-validation" name="contact-form" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?dbTable=<?php echo $contacts_db_table; ?>&section=contact-pub&action=email" novalidate>

            <input type="text" name="website" class="pooh-bear" tabindex="-1" autocomplete="off">
            <input type="hidden" name="form_token" id="form_token" value="">
            <input type="hidden" name="form_loaded_time" id="form_loaded_time" value="">
            <input type="hidden" name="user_session_token" value ="<?php if (isset($_SESSION['user_session_token'])) echo $_SESSION['user_session_token']; ?>">
            <?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
            <input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
            <?php } else { ?>
            <input type="hidden" name="relocate" value="<?php echo $base_url."#contact"; ?>">
            <?php } ?>

            <div class="row mb-3">
                <label for="select-contact" class="col-sm-12 col-md-2 col-form-label text-teal"><i class="fa fa-sm fa-star pe-1"></i><strong><?php echo $label1; ?></strong></label>
                <div class="col-sm-12 col-md-10">
                    <select id="select-contact" class="form-select bootstrap-select" name="to" placeholder="<?php echo $contact_text_005; ?>" autocomplete="off" required>
                        <option value=""><?php echo $contact_text_005; ?></option>
                        <?php echo $option; ?>
                    </select>
                    <div class="invalid-feedback"><?php echo $contact_text_006; ?></div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="from-name" class="col-sm-12 col-md-2 col-form-label text-teal"><i class="fa fa-sm fa-star pe-1"></i><strong><?php echo $label2; ?></strong></label>
                <div class="col-sm-12 col-md-10">
                    <input id="from-name" class="form-control no-spam" name="from_name" type="text" size="35" placeholder="<?php echo $contact_text_007; ?>" value="<?php if (($msg == "2") && (isset($_COOKIE['from_name']))) echo $_COOKIE['from_name']; ?>" autocomplete="off" required>
                    <div class="invalid-feedback"><?php echo $contact_text_007; ?></div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="from-email" class="col-sm-12 col-md-2 col-form-label text-teal"><i class="fa fa-sm fa-star pe-1"></i><strong><?php echo $label3; ?></strong></label>
                <div class="col-sm-12 col-md-10">
                    <input id="from-email" class="form-control no-spam" name="from_email" type="email" size="35" placeholder="<?php echo $contact_text_008; ?>" value="<?php if (($msg == "2") && (isset($_COOKIE['from_email']))) echo $_COOKIE['from_email']; ?>" autocomplete="off" required>
                    <div class="invalid-feedback"><?php echo $contact_text_008; ?></div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="subject" class="col-sm-12 col-md-2 col-form-label text-teal"><i class="fa fa-sm fa-star pe-1"></i><strong><?php echo $label4; ?></strong></label>
                <div class="col-sm-12 col-md-10">
                    <input id="subject" class="form-control no-spam" name="subject" type="text" placeholder="<?php echo $contact_text_009; ?>" value="<?php if (($msg == "2") && (isset($_COOKIE['subject']))) echo $_COOKIE['subject']; ?>" autocomplete="off" required> 
                    <div class="invalid-feedback"><?php echo $contact_text_009; ?></div> 
                </div>
            </div>

            <div class="row mb-3">
                <label for="message" class="col-sm-12 col-md-2 col-form-label text-teal"><i class="fa fa-sm fa-star pe-1"></i><strong><?php echo $label5; ?></strong></label>
                <div class="col-sm-12 col-md-10">
                    <textarea id="message" class="form-control no-spam" style="height: 140px" name="message" required><?php if (($msg == "2") && (isset($_COOKIE['message']))) echo $_COOKIE['message']; ?></textarea>
                    <div class="invalid-feedback"><?php echo $contact_text_010; ?></div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="" class="col-sm-12 col-md-2 col-form-label text-teal"><i class="fa fa-sm fa-star pe-1"></i><strong><?php echo $login_text_007; ?></strong></label>
                <div class="col-sm-12 col-md-10">
                    <div class="<?php echo $captcha_widget_class; ?> mb-3" data-sitekey="<?php echo $public_captcha_key; ?>"></div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="form-submit-button-1" class="col-sm-2 col-form-label"></label>
                <div class="col-sm-12 col-md-10">
                    <div class="d-grid">
                        <button id="form-submit-button-1" name="submit" type="submit" class="btn btn-primary"><?php echo $label_send_message; ?></button>
                    </div>
                </div>
            </div>
            
        </form>

    <script src="<?php echo $captcha_url; ?>"></script>
    <script>
        var no_paste_message = '<?php echo $contact_text_013; ?>';
    </script>
    <script src="<?php echo $js_url; ?>contact.min.js"></script>

<?php } // end if ($msg != 1);
    
    } // end if ($totalRows_contact > 0)

} // end if ($_SESSION['prefsContact'] == "Y")

?>