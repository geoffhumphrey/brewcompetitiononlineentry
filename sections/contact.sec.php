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

	$warningX = any warnings
  
	$primary_page_info = any information related to the page
	
	$header1_X = an <h2> header on the page
	$header2_X = an <h3> subheader on the page
	
	$page_infoX = the bulk of the information on the page.
	$help_page_link = link to the appropriate page on help.brewcompetition.com
	$print_page_link = the "Print This Page" link
	$competition_logo = display of the competition's logo
	
	$labelX = the various labels in a table or on a form
	$messageX = various messages to display
	
	$print_page_link = "<p><span class='icon'><img src='".$base_url."images/printer.png' border='0' alt='Print' title='Print' /></span><a id='modal_window_link' class='data' href='".$base_url."output/print.php?section=".$section."&amp;action=print' title='Print'>Print This Page</a></p>";
	$competition_logo = "<img src='".$base_url."user_images/".$_SESSION['contestLogo']."' width='".$_SESSION['prefsCompLogoSize']."' style='float:right; padding: 5px 0 5px 5px' alt='Competition Logo' title='Competition Logo' />";
	
Declare all variables empty at the top of the script. Add on later...
	$warning1 = "";
	$primary_page_info = "";
	$header1_1 = "";
	$page_info1 = "";
	$header1_2 = "";
	$page_info2 = "";
	
	etc., etc., etc.

 * ---------------- END Rebuild Info --------------------- */

include(DB.'contacts.db.php');
require_once(INCLUDES.'recaptchalib.inc.php');

if ($_SESSION['prefsContact'] == "N") {
	$page_info = "";
	$page_info .= "<p>Use the links below to contact individuals involved with coordinating this competition:</p>";
	$page_info .= "<ul>";
	do {
		$page_info .= "<li>".$row_contact['contactFirstName']." ".$row_contact['contactLastName'].", ".$row_contact['contactPosition']." &ndash; <a href='mailto:".$row_contact['contactEmail']."'>".$row_contact['contactEmail']."</a></li>";
	} while ($row_contact = mysqli_fetch_assoc($contact)); 
	$page_info .= "</ul>";
	
	echo $page_info;
}

if ($_SESSION['prefsContact'] == "Y") {
	
	$option = "";
		
	do { 
	
		$option .= "<option value=".$row_contact['id'];
		if(isset($_COOKIE['to'])) {
			if ($row_contact['id'] == $_COOKIE['to']) $option .= " SELECTED"; 
			}
		$option .= ">".$row_contact['contactFirstName']." ".$row_contact['contactLastName']." &ndash; ".$row_contact['contactPosition']."</option>";

	
	} while ($row_contact = mysqli_fetch_assoc($contact)); 

	$primary_page_info = "<p>Use the form below to contact a competition official. All fields with a star are <span class=\"text-warning\">required</span>.</p>";
	$label1 = "Contact";
	$label2 = "Your Name";
	$label3 = "Your Email";
	$label4 = "Subject";
	$label5 = "Message";
	$label6 = "Are You Human?";

	$required_label = "Required";

	if ($msg == "1") {
		$message1 = "<p>Additionally, a copy has been sent to the email address you provided.</p><p>Would you like to send <a href='".build_public_url("contact","default","default","default",$sef,$base_url)."'>another message</a>?</p>";
		echo $message1; 
	}
	
	if ($msg != "1") {
		
	echo $primary_page_info;
?>
        <form data-toggle="validator" role="form" class="form-horizontal" name="form1" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?dbTable=<?php echo $contacts_db_table; ?>&action=email" onSubmit="return CheckRequiredFields()">
        	<div class="form-group">
            	<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label1; ?></label>
                <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
                <!-- Input Here -->
                <select class="selectpicker" name="to" data-live-search="true" data-size="10" data-width="auto">
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
                		<input class="form-control" name="from_name" type="text" size="35" value="<?php if ($msg == "2") echo $_COOKIE['from_name']; ?>" autofocus required>
                        <span class="input-group-addon" id="from_name-addon2"><span class="fa fa-star"></span></span>
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
                		<input class="form-control" name="from_email" type="email" size="35" value="<?php if ($msg == "2") echo $_COOKIE['from_email']; ?>" required>
                        <span class="input-group-addon" id="from_email-addon2"><span class="fa fa-star"></span></span>
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
                		<input class="form-control" name="subject" type="text" value="<?php if ($msg == "2") echo $_COOKIE['subject']; ?>" required>
                        <span class="input-group-addon" id="subject-addon2"><span class="fa fa-star"></span></span>
                    </div>
                    <div class="help-block with-errors"></div>
                </div>
            </div><!-- Form Group -->
            
        	<div class="form-group">
            	<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label5; ?></label>
                <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
                	<div class="input-group has-warning">
                    	<span class="input-group-addon" id="contact-addon7"><span class="fa fa-pencil"></span></span>
                		<!-- Input Here -->
                		<textarea class="form-control" name="message" rows="6" required><?php if ($msg == "2") echo $_COOKIE['message']; ?></textarea>
                        <span class="input-group-addon" id="XXX-addon2"><span class="fa fa-star"></span></span>
                    </div>
                    <div class="help-block with-errors"></div>
                </div>
            </div><!-- Form Group -->
            
            <div class="form-group">
            	<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label6; ?></label>
                <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
                	<div class="input-group">
                        <!-- Input Here -->
                        <?php echo recaptcha_get_html($publickey, null, true); ?>
                    </div>
                </div>
            </div><!-- Form Group -->
            
            <div class="form-group">
                <div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4 col-xs-12">
                	<!-- Input Here -->
                  	<button name="submit" type="submit" class="btn btn-primary" >Send Message <span class="fa fa-send"></span> </button>
                </div>
            </div><!-- Form Group -->
            
        <?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
        <input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
        <?php } else { ?>
        <input type="hidden" name="relocate" value="<?php echo relocate($base_url,"default",$msg,$id); ?>">
        <?php } ?>
        </form>    
<?php } // end if ($msg != 1);
} // end if ($_SESSION['prefsContact'] == "Y")
 
?>