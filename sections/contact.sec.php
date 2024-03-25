<?php
/**
 * Module:      contact.sec.php
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

    if ($totalRows_contact == 0) {
        echo sprintf("<p>%s</p>",$contact_text_004);
    }

    else {

    	do {

    		$option .= "<option value=".$row_contact['id'];
    		if(isset($_COOKIE['to'])) {
    			if ($row_contact['id'] == $_COOKIE['to']) $option .= " SELECTED";
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
<script>
$(document).ready(function() {
    $(".no-spam").bind("cut copy paste", function(e) {
      e.preventDefault();
    });

    $(".no-spam").bind("contextmenu", function(e) {
      e.preventDefault();
    });
});
</script>
        <form id="submit-form" data-toggle="validator" role="form" class="form-horizontal hide-loader-form-submit" name="form1" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?dbTable=<?php echo $contacts_db_table; ?>&action=email">
        <input type="hidden" name="token" value ="<?php if (isset($_SESSION['token'])) echo $_SESSION['token']; ?>">
        	<div class="form-group">
            	<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label1; ?></label>
                <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
                <!-- Input Here -->
                <select class="selectpicker" name="to" data-live-search="true" data-size="10" data-width="fit">
                    <?php echo $option; ?>
                </select>
                </div>
            </div><!-- Form Group -->
            <div class="form-group">
            	<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label2; ?></label>
                <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
                	<div class="input-group has-warning">
                    	<span class="input-group-addon" id="from_name-addon1"><span class="fa fa-user"></span></span>
                		<!-- Input Here -->
                		<input id="from-name" class="form-control no-spam" name="from_name" type="text" size="35" value="<?php if (($msg == "2") && (isset($_COOKIE['from_name']))) echo $_COOKIE['from_name']; ?>" autofocus required>
                        <span class="input-group-addon" id="from_name-addon2" data-tooltip="true" title="<?php echo $form_required_fields_02; ?>"><span class="fa fa-star"></span></span>
                    </div>
                    <div class="help-block with-errors"></div>
                </div>
            </div><!-- Form Group -->
            <div class="form-group">
            	<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label3; ?></label>
                <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
                	<div class="input-group has-warning">
                    	<span class="input-group-addon" id="from_email-addon3">@</span>
                		<!-- Input Here -->
                		<input id="from-email" class="form-control no-spam" name="from_email" type="email" size="35" value="<?php if (($msg == "2") && (isset($_COOKIE['from_email']))) echo $_COOKIE['from_email']; ?>" required>
                        <span class="input-group-addon" id="from_email-addon2" data-tooltip="true" title="<?php echo $form_required_fields_02; ?>"><span class="fa fa-star"></span></span>
                    </div>
                    <div class="help-block with-errors"></div>
                </div>
            </div><!-- Form Group -->
            <div class="form-group">
            	<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label4; ?></label>
                <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
                	<div class="input-group has-warning">
                    	<span class="input-group-addon" id="subject-addon5"><span class="fa fa-info-circle"></span></span>
                		<!-- Input Here -->
                		<input id="subject" class="form-control no-spam" name="subject" type="text" value="<?php if (($msg == "2") && (isset($_COOKIE['subject']))) echo $_COOKIE['subject']; ?>" required>
                        <span class="input-group-addon" id="subject-addon2" data-tooltip="true" title="<?php echo $form_required_fields_02; ?>"><span class="fa fa-star"></span></span>
                    </div>
                    <div class="help-block with-errors"></div>
                </div>
            </div><!-- Form Group -->
        	<div class="form-group">
            	<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label5; ?></label>
                <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
                	<div class="input-group has-warning">
                    	<span class="input-group-addon" id="message-addon7"><span class="fa fa-pencil"></span></span>
                		<!-- Input Here -->
                		<textarea id="message" class="form-control no-spam" name="message" rows="6" required><?php if (($msg == "2") && (isset($_COOKIE['message']))) echo $_COOKIE['message']; ?></textarea>
                        <span class="input-group-addon" id="message-addon2" data-tooltip="true" title="<?php echo $form_required_fields_02; ?>"><span class="fa fa-star"></span></span>
                    </div>
                    <div class="help-block with-errors"></div>
                </div>
            </div><!-- Form Group -->
            <?php if ($_SESSION['prefsCAPTCHA'] == "1") { ?>
            <div class="form-group">
            	<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label6; ?></label>
                <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
                	<div class="input-group">
                        <!-- Input Here -->
                            <div class="g-recaptcha" data-sitekey="<?php echo $public_captcha_key; ?>"></div>
                    </div>
                </div>
            </div><!-- Form Group -->
            <?php } ?>
            <div class="form-group">
                <div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4 col-xs-12">
                	<!-- Input Here -->
                  	<button id="form-submit-button" name="submit" type="submit" class="btn btn-primary" ><?php echo $label_send_message; ?> <span class="fa fa-send"></span> </button>
                </div>
            </div><!-- Form Group -->
            <div class="alert alert-warning" style="margin-top: 10px;" id="form-submit-button-disabled-msg-required">
                <?php echo sprintf("<p><i class=\"fa fa-exclamation-triangle\"></i> <strong>%s</strong> %s</p>",$form_required_fields_00,$form_required_fields_01); ?>
            </div>
        <?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
        <input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
        <?php } else { ?>
        <input type="hidden" name="relocate" value="<?php echo relocate($base_url,"default",$msg,$id); ?>">
        <?php } ?>
        </form>
<script src="https://www.google.com/recaptcha/api.js"></script>
<?php } // end if ($msg != 1);
    } // end if ($totalRows_contact > 0)
} // end if ($_SESSION['prefsContact'] == "Y")

?>