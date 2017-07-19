<?php
/**
 * Module:      user.sec.php 
 * Description: This module houses the functionality for users to add/update enter their
 *              user name and password information. 
 * 
 */

// Verify if current user is authorized to make changes to the user account

$edit_enable = FALSE;
$lead_msg = $header_text_113;
if ($_SESSION['userLevel'] == 0) $lead_msg .= " <small>".$header_text_114."</small>";

if ((($filter == "admin") && ($_SESSION['userLevel'] == 0)) || (($filter == "default") && ($id == $_SESSION['user_id']))) {
	$edit_user_enable = 1;
	$edit_enable = TRUE;
}

else $edit_user_enable = 0;

if (((($_SESSION['loginUsername'] == $_SESSION['user_name'])) || ($_SESSION['userLevel'] <= "1")) && ($edit_enable)) {

// Build Variables
$user_help_link = "";
$user_help_link_text = "";

if ($action == "password") {
	$user_help_link .= "change_password.html";
	$user_help_link_text .= "Change Password Help";
	if ($filter == "admin") $lead_msg = "You are changing ".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']."&rsquo;s Password.";
	else $lead_msg = $user_text_004;
}

if ($action == "username") {
	$user_help_link .= "change_email_address.html";
	$user_help_link_text .= "Change Email Address Help";
	if ($filter == "admin") $lead_msg = "You are changing ".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']."&rsquo;s Email Address (User Name).";
	else $lead_msg = sprintf("%s: <small class=\"text-muted\">%s</small>.", $user_text_005, $_SESSION['user_name']);
}

?>
<?php if ($action == "username") { ?>
<script type="text/javascript">
function checkAvailability()
{
	jQuery.ajax({
		url: "<?php echo $base_url; ?>includes/ajax_functions.inc.php?action=username",
		data:'user_name='+$("#user_name").val(),
		type: "POST",
		success:function(data){
			$("#status").html(data);
		},
		error:function (){}
	});
}

function AjaxFunction(email)
{
	var httpxml;
		try
		{
		// Firefox, Opera 8.0+, Safari
		httpxml=new XMLHttpRequest();
		}
	catch (e)
		{
		// Internet Explorer
		try
		{
		httpxml=new ActiveXObject("Msxml2.XMLHTTP");
		}
	catch (e)
		{
		try
		{
		httpxml=new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch (e)
		{
		//alert("Your browser does not support AJAX!");
	return false;
	}
	}
}
function stateck()
{
if(httpxml.readyState==4)
{
document.getElementById("msg_email").innerHTML=httpxml.responseText;
}
}
var url="<?php echo $base_url; ?>includes/ajax_functions.inc.php?action=email";
url=url+"&email="+email;
url=url+"&sid="+Math.random();
httpxml.onreadystatechange=stateck;
httpxml.open("GET",url,true);
httpxml.send(null);
}
//-->
</script>
<?php } // end if ($action == "username") ?>
<p class="lead"><?php echo $lead_msg; ?></p>

<form data-toggle="validator" role="form" class="form-horizontal"  action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo $section; ?>&amp;go=<?php echo $action; ?>&amp;action=edit&amp;dbTable=<?php echo $users_db_table; ?>&amp;filter=<?php echo $filter; ?>&amp;id=<?php if ($filter == "admin") echo $row_brewer['uid']; else echo $_SESSION['user_id']; ?>" method="POST" name="form1" id="form1" onSubmit="return CheckRequiredFields()">
<input name="user_name_old" type="hidden" value="<?php if ($filter == "admin") echo $row_brewer['brewerEmail']; else echo $_SESSION['user_name']; ?>">
<input type="hidden" name="userEdit" value="<?php echo $edit_user_enable; ?>">
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=list","default",$msg,$id); ?>">
<?php } ?>
<?php if ($action == "username") { ?>
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="user_name" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_new." ".$label_email; ?></label>
        <div class="col-lg-10 col-md-6 col-sm-9 col-xs-12">
            <div class="input-group has-warning">
                <!-- Input Here -->
                <span class="input-group-addon" id="user_name-addon1"><span class="fa fa-envelope"></span></span>
                <input class="form-control" id="user_name" name="user_name" type="email" onBlur="checkAvailability()" onkeyup="twitter.updateUrl(this.value)" onchange="AjaxFunction(this.value);" placeholder="" data-error="<?php echo $user_text_000; ?>" required>
                <span class="input-group-addon" id="user_name-addon2"><span class="fa fa-star"></span></span>
            </div>
            <div class="help-block with-errors"></div>
            <div id="msg_email"></div>
			<div id="status"></div>
        </div>
    </div><!-- ./Form Group -->

	<div class="form-group"><!-- Form Group Checkbox INLINE -->
        <label for="sure" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_sure; ?></label>
        <div class="col-lg-10 col-md-6 col-sm-9 col-xs-12">
            <div class="input-group">
                <!-- Input Here -->
                <label class="checkbox-inline">
                    <input type="checkbox" name="sure" value="Y" id="sure_0" data-error="<?php echo $user_text_003; ?>" required> <?php echo $label_yes; ?>
                </label>
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div><!-- ./Form Group -->

	<div class="form-group">
		<div class="col-sm-offset-2 col-lg-10 col-md-6 col-sm-9 col-xs-12">
			<!-- Input Here -->
			<button name="submit" type="submit" class="btn btn-primary" ><?php echo $label_change_email; ?></button>
		</div>
	</div><!-- Form Group -->
<?php } ?>
<?php if ($action == "password") { ?>
<script type="text/javascript">
        $(document).ready(function () {
            "use strict";
            var options = {};
            options.ui = {
                container: "#pwd-container",
				showErrors: true,
				useVerdictCssClass: true,
                showVerdictsInsideProgressBar: true,
                viewports: {
                    progress: ".pwd-strength-viewport-progress"
                },
				progressBarExtraCssClasses: "progress-bar-striped active",
				progressBarEmptyPercentage: 2,
				progressBarMinPercentage: 6
            };
            options.common = {
                zxcvbn: true,
				minChar: 8,
				onKeyUp: function (evt, data) {
					$("#length-help-text").text("<?php echo $label_length; ?>: " + $(evt.target).val().length + " - <?php echo $label_score; ?>: " + data.score.toFixed(2));
				},
            };
            $('#newPassword').pwstrength(options);
        });
</script>
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="passwordOld" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_old." ".$label_password; ?></label>
        <div class="col-lg-10 col-md-6 col-sm-9 col-xs-12">
            <div class="input-group has-warning">
                <!-- Input Here -->
                <span class="input-group-addon" id="passwordOld-addon1"><span class="fa fa-key"></span></span>
                <input class="form-control" name="passwordOld" type="password" placeholder="" id="passwordOld" data-error="<?php echo $user_text_001; ?>" required>
                <span class="input-group-addon" id="passwordOld-addon2"><span class="fa fa-star"></span></span>
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div><!-- ./Form Group -->

	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
        <label for="password" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_new." ".$label_password; ?></label>
        <div class="col-lg-10 col-md-6 col-sm-9 col-xs-12">
            <div class="input-group has-warning">
                <!-- Input Here -->
                <span class="input-group-addon" id="password-addon1"><span class="fa fa-key"></span></span>
                <input class="form-control" name="password" type="password" placeholder="" id="newPassword" data-error="<?php echo $user_text_002; ?>" required>
                <span class="input-group-addon" id="password-addon2"><span class="fa fa-star"></span></span>
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div><!-- ./Form Group -->
    
    <div class="form-group" id="pwd-container">
		<label class="col-lg-2 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $label_password_strength; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
			<div class="pwd-strength-viewport-progress"></div>
			<div id="length-help-text" class="small"></div>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-lg-10 col-md-6 col-sm-9 col-xs-12">
			<!-- Input Here -->
			<button name="submit" type="submit" class="btn btn-primary" ><?php echo $label_change_password; ?></button>
		</div>
	</div><!-- Form Group -->


<?php } ?>
</form>
<?php } // end if ((($_SESSION['loginUsername'] == $_SESSION['user_name'])) || ($_SESSION['userLevel'] <= "1"))
else echo "<div class='lead'>".$lead_msg."</div>"; ?>