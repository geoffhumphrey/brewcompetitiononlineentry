<?php
/**
 * Module:      contact.pub.php
 * Description: This module displays the contact mechanism for user feedback.
 *              When processed, the request uses the sendmail function.
 *
 */

// Redirect if directly accessed
if ((!isset($_SESSION['prefs'.$prefix_session])) || ((isset($_SESSION['prefs'.$prefix_session])) && (!isset($base_url)))) {
    $redirect = "../../index.php?section=contact";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

include (DB.'contacts.db.php');

if ((!HOSTED) && (!empty($_SESSION['prefsGoogleAccount']))) {
    $recaptcha_key = explode("|", $_SESSION['prefsGoogleAccount']);
    $public_captcha_key = $recaptcha_key[0];
}

if ($_SESSION['prefsContact'] == "N") {
	$page_info = "";
    if ($totalRows_contact == 0) $page_info .= sprintf("<p>%s</p>",$contact_text_004);
    else {
    	$page_info .= sprintf("<p>%s</p>",$contact_text_000);
    	$page_info .= "<ul>";
    	do {
    		$page_info .= "<li>".$row_contact['contactFirstName']." ".$row_contact['contactLastName'].", ".$row_contact['contactPosition']." &ndash; <a href='mailto:".$row_contact['contactEmail']."'>".$row_contact['contactEmail']."</a></li>";
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
            if ($_SESSION['prefsEmailCC'] == 0) $message1 = sprintf("<p>%s <a href='".build_public_url("contact","default","default","default",$sef,$base_url)."'>%s</a></p>",$contact_text_002,$contact_text_003); 
    		else $message1 = sprintf("<p><a href='".build_public_url("contact","default","default","default",$sef,$base_url)."'>%s</a></p>",$contact_text_003);
    		echo $message1;
    	}

    	if ($msg != "1") {

    	echo $primary_page_info;
?>

        <!-- <form id="bootstrap-form" class="justify-content-center hide-loader-form-submit" name="form1" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?dbTable=<?php echo $contacts_db_table; ?>&action=email" novalidate> -->
        <form id="contact-form" class="justify-content-center hide-loader-form-submit" name="contact-form" novalidate>

            <input type="hidden" name="token" value ="<?php if (isset($_SESSION['token'])) echo $_SESSION['token']; ?>">
            <?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
            <input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
            <?php } else { ?>
            <input type="hidden" name="relocate" value="<?php echo relocate($base_url,"default",$msg,$id); ?>">
            <?php } ?>

            <div class="row mb-3">
                <label for="select-contact" class="col-sm-2 col-form-label"><i class="fa fa-sm fa-star pe-1 text-primary"></i><strong><?php echo $label1; ?></strong></label>
                <div class="col-sm-10">
                    <select id="select-contact" class="form-select" name="to" placeholder="Select or search for a contact." autocomplete="off" required>
                        <option value="">Select or search for a contact.</option>
                        <?php echo $option; ?>
                    </select>
                    <div class="invalid-feedback">Please select a person.</div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="from-name" class="col-sm-2 col-form-label"><i class="fa fa-sm fa-star pe-1 text-primary"></i><strong><?php echo $label2; ?></strong></label>
                <div class="col-sm-10">
                    <input id="from-name" class="form-control no-spam" name="from_name" type="text" size="35" placeholder="Please provide your first and last name." value="<?php if (($msg == "2") && (isset($_COOKIE['from_name']))) echo $_COOKIE['from_name']; ?>" required>
                    <div class="invalid-feedback">Please provide your first and last name.</div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="from-email" class="col-sm-2 col-form-label"><i class="fa fa-sm fa-star pe-1 text-primary"></i><strong><?php echo $label3; ?></strong></label>
                <div class="col-sm-10">
                    <input id="from-email" class="form-control no-spam" name="from_email" type="email" size="35" placeholder="Please provide a valid email." value="<?php if (($msg == "2") && (isset($_COOKIE['from_email']))) echo $_COOKIE['from_email']; ?>" required>
                    <div class="invalid-feedback">Please provide a valid email.</div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="subject" class="col-sm-2 col-form-label"><i class="fa fa-sm fa-star pe-1 text-primary"></i><strong><?php echo $label4; ?></strong></label>
                <div class="col-sm-10">
                    <input id="subject" class="form-control no-spam" name="subject" type="text" placeholder="Please provide a subject." value="<?php if (($msg == "2") && (isset($_COOKIE['subject']))) echo $_COOKIE['subject']; ?>" required> 
                    <div class="invalid-feedback">Please provide a subject.</div> 
                </div>
            </div>

            <div class="row mb-3">
                <label for="message" class="col-sm-2 col-form-label"><i class="fa fa-sm fa-star pe-1 text-primary"></i><strong><?php echo $label5; ?></strong></label>
                <div class="col-sm-10">
                    <textarea id="message" class="form-control no-spam" style="height: 140px" name="message" required><?php if (($msg == "2") && (isset($_COOKIE['message']))) echo $_COOKIE['message']; ?></textarea>
                    <div class="invalid-feedback">Please provide a message.</div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="" class="col-sm-2 col-form-label"><i class="fa fa-sm fa-star pe-1 text-primary"></i><strong>CAPTCHA</strong></label>
                <div class="col-sm-10">
                    <div class="g-recaptcha mb-3" data-sitekey="<?php echo $public_captcha_key; ?>"></div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="" class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10">
                    <!-- 
                    <div class="alert alert-warning mb-3" id="form-submit-button-disabled-msg-required-1">
                        <?php echo sprintf("<i class=\"fa fa-exclamation-triangle\"></i> <strong>%s</strong> %s",$form_required_fields_00,$form_required_fields_01); ?>
                    </div>
                    -->
                    <div class="d-grid">
                        <button id="form-submit-button-1" name="submit" type="submit" class="btn btn-primary"><?php echo $label_send_message; ?></button>
                    </div>
                </div>
            </div>
            
        </form>
<script>

    // $("#form-submit-button-disabled-msg-required-1").hide();

    var my_select = new TomSelect("#select-contact",{
        create: false,
        allowEmptyOption: false,
        sortField: {
            field: "text",
            direction: "asc"
        }
    });

    var form = document.getElementById('contact-form');

    form.addEventListener('submit', function (event){

        // add was-validated to display custom colors
        form.classList.add('was-validated');

        if (form.checkValidity()) {
            // $("#form-submit-button-disabled-msg-required-1").slideUp(500);
        }

        else {
            // $("#form-submit-button-disabled-msg-required-1").slideDown(500);
            event.preventDefault()
            event.stopPropagation()
        }

    }, false);
</script>


<script src="https://www.google.com/recaptcha/api.js"></script>
<?php } // end if ($msg != 1);
    } // end if ($totalRows_contact > 0)
} // end if ($_SESSION['prefsContact'] == "Y")

?>