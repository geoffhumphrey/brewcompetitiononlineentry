<?php 
/**
 * Module:      login.sec.php 
 * Description: This module houses the functionality for users to log into the
 *              site using their username and password (encrypted in the db). 
 * 
 */
 

/* ---------------- PUBLIC Pages Rebuild Info ---------------------

Beginning with the 1.3.0 release, an effort was begun to separate the programming
layer from the presentation layer for all scripts with this header.

All Public pages have certain variables in common that build the page:
  
	$primary_page_info = any information related to the page
	
	$header1_X = an <h2> header on the page
	$header2_X = an <h3> subheader on the page
	
	$page_infoX = the bulk of the information on the page.
	$print_page_link = the "Print This Page" link
	$competition_logo = display of the competition's logo
	
	$labelX = the various labels in a table or on a form
	$table_headX = all table headers (column names)
	$table_bodyX = table body info
	$messageX = various messages to display
	
	$print_page_link = "<p><span class='icon'><img src='".$base_url."images/printer.png' border='0' alt='Print' title='Print' /></span><a id='modal_window_link' class='data' href='".$base_url."output/print.php?section=".$section."&amp;action=print' title='Print'>Print This Page</a></p>";
	$competition_logo = "<img src='".$base_url."user_images/".$_SESSION['contestLogo']."' width='".$_SESSION['prefsCompLogoSize']."' style='float:right; padding: 5px 0 5px 5px' alt='Competition Logo' title='Competition Logo' />";
	
Declare all variables empty at the top of the script. Add on later...
	$primary_page_info = "";
	$header1_1 = "";
	$page_info1 = "";
	$header1_2 = "";
	$page_info2 = "";
	
	$table_head1 = "";
	$table_body1 = "";
	
	etc., etc., etc.

 * ---------------- END Rebuild Info --------------------- */

$primary_links = "";
$message0 = "";
$message1 = "";
$message2 = "";
$message3 = "";

$login_form_display = FALSE;
$forget_form_display = FALSE;
$verify_form_display = FALSE;
$reset_token_form_display = FALSE;
$token_valid = "";

// Build Messages
if (isset($_SESSION['loginUsername'])) $message1 .= sprintf("<p class=\"lead\">%s</p>",$login_text_000);

if ((($action == "default") || ($action == "login") || ($action == "logout")) && (!isset($_SESSION['loginUsername']))) $login_form_display = TRUE; 
if (($action == "forgot") && ($go == "password") && (!isset($_SESSION['loginUsername']))) $forget_form_display = TRUE;
if (($action == "forgot") && ($go == "verify") && (!isset($_SESSION['loginUsername']))) { 
	
	$verify_form_display = TRUE;
	if ($username == "default") $username_check = $_POST['loginUsername'];
	else $username_check = $username;
	
	$user_check = user_check($username_check);
	$user_check = explode("^",$user_check);
	
	if (($user_check[0] == 0) && ($msg == "default")) { 
		$message2 .= sprintf("<div class='alert alert-warning'><span class=\"fa fa-lg fa-exclamation-circle\"></span> %s <a class=\"alert-link\" href=\"%s\">%s</a></div>",$login_text_001, build_public_url("login","password","forgot","default",$sef,$base_url),$login_text_002);
	}
	
}

// If there is a reset token in the URL
if (($action == "reset-password") && ($token != "default")) {
	
	// First, verify that the token is valid
	$token_valid = verify_token($token,time());
	
	// If valid, show the password reset with token form
	if ($token_valid == 0) { 
		
		if (!isset($_SESSION['loginUsername'])) {
			$reset_token_form_display = TRUE;
			$primary_links .= "<p class=\"lead\">".$login_text_026."</p>";	
		}
		
	}
	
	else $primary_links .= "<p class=\"lead\"><a href=\"".$base_url."index.php?section=login&amp;go=password&amp;action=forgot\">".$login_text_025."</a> <a href=\"#\" role=\"button\" data-toggle=\"modal\" data-target=\"#loginModal\">".ucfirst(strtolower($label_log_in))."?</a></p>";
	
	// If not valid, show an error
	if ($token_valid == 1) $message3 .= sprintf("<div class='alert alert-warning'><span class=\"fa fa-lg fa-exclamation-circle\"></span> %s</div>",$login_text_020);
	
	// If expired, show an error
	if ($token_valid == 2) $message3 .= sprintf("<div class='alert alert-warning'><span class=\"fa fa-lg fa-exclamation-circle\"></span> %s</div>",$login_text_021);
	
	
	
}

//echo $token;
//echo $token_valid;

// Build Links

if ($section != "update") {
	if (($msg != "default") && ($registration_open < "2") && (!$verify_form_display) && (!$reset_token_form_display)) $primary_links .= sprintf("<p class=\"lead\"><span class=\"fa fa-lg fa-exlamation-circle\"></span> <a href=\"%s\">%s</a></p>",build_public_url("register","default","default","default",$sef,$base_url),$login_text_003);
	if ($login_form_display) $primary_links .= sprintf("<p class=\"lead\"><span class=\"fa fa-lg fa-exlamation-circle\"></span> %s <a href=\"%s\">%s</a>.</p>",$login_text_004,$base_url."index.php?section=login&amp;go=password&amp;action=forgot",$login_text_005);
}
echo $message0;
echo $message1;
echo $message2;
echo $message3;
echo $primary_links;
?>

<?php if ($login_form_display) { ?>
<form data-toggle="validator" role="form" class="form-horizontal" action="<?php echo $base_url; ?>includes/logincheck.inc.php?section=<?php echo $section; ?>" method="POST" name="form1" id="form1">
	<div class="form-group">
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_email; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="login-addon1"><span class="fa fa-envelope"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="loginUsername" type="email" value="<?php if ($username != "default") echo $username; ?>" autofocus required data-error="<?php echo $login_text_018; ?>">
				<span class="input-group-addon" id="login-addon2"><span class="fa fa-star"></span></span>
			</div>
			<span class="help-block with-errors"></span>
		</div>
	</div><!-- Form Group -->
	<div class="form-group">
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_password; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="login-addon3"><span class="fa fa-key"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="loginPassword" type="password" required data-error="<?php echo $login_text_019; ?>">
				<span class="input-group-addon" id="login-addon4"><span class="fa fa-star"></span></span>
			</div>
			<span class="help-block with-errors"></span>
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
			<!-- Input Here -->
			<button name="submit" type="submit" class="btn btn-primary" ><?php echo $label_log_in; ?> <span class="fa fa-sign-in" aria-hidden="true"></span> </button>
		</div>
	</div><!-- Form Group -->
</form>
<?php } ?>

<?php if ($forget_form_display) { ?>
<p class="lead"><?php echo $login_text_006; ?></p>
<form class="form-horizontal" action="<?php echo build_public_url("login","verify","forgot","default",$sef,$base_url); ?>" method="POST" name="form1" id="form1">
	<div class="form-group">
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_email; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group">
				<span class="input-group-addon" id="reset-addon1"><span class="fa fa-envelope"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="loginUsername" type="email" value="<?php if ($username != "default") echo $username; ?>" autofocus>
			</div>
		</div>
	</div><!-- Form Group -->
	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
			<!-- Input Here -->
			<button name="submit" type="submit" class="btn btn-primary" ><?php echo $login_text_007." ".$label_email; ?> <span class="fa fa-check-circle" aria-hidden="true"></span> </button>
		</div>
	</div><!-- Form Group -->
</form>
<?php } ?>
<?php if ($verify_form_display) {
	if ((empty($message2)) || (empty($msg))) { 
	
	if ($user_check[1] == $login_text_008) {
		$secret_question = $login_text_009; 
		if ($_SESSION['prefsContact'] == "Y") $secret_question .= sprintf(" %s",$login_text_010);
	}
	else $secret_question = $user_check[1];
	?>	
	<p class="lead"><?php echo $login_text_011; ?> <small class="text-muted"><em><?php echo $secret_question; ?></em></small></p>
	<p class="lead"><small><?php echo $login_text_015; ?></small></p>
<form class="form-horizontal" action="<?php echo $base_url; ?>includes/forgot_password.inc.php?action=forgot" method="POST" name="form1" id="form1">
	<div class="form-group">
		<label for="" class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_security_answer; ?></label>
		<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group">
				<span class="input-group-addon" id="id-verify-addon1"><span class="fa fa-bullhorn"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="userQuestionAnswer" type="text" autofocus>
			</div>
		</div>
	</div><!-- Form Group -->
	<div class="form-group">
		<div class="col-lg-offset-3 col-md-offset-3 col-sm-offset-4">
			<!-- Input Here -->
			<button name="submit" type="submit" class="btn btn-primary" ><?php echo $label_submit; ?></button>
		</div>
	</div><!-- Form Group -->
<input type="hidden" name="loginUsername" value="<?php echo $username_check; ?>">
</form>
    <?php }
	}
?>
<?php if ($reset_token_form_display) { ?>
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
            $('#password').pwstrength(options);
        });
</script>
<form data-toggle="validator" role="form" class="form-horizontal" action="<?php echo $base_url; ?>includes/forgot_password.inc.php?action=reset&token=<?php echo $token; ?>" method="POST" name="form1" id="form1">
	<div class="form-group">
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_email; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="login-addon1"><span class="fa fa-envelope"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="loginUsername" type="email" value="<?php if ($username != "default") echo $username; ?>" autofocus required data-error="<?php echo $login_text_018; ?>">
				<span class="input-group-addon" id="login-addon2"><span class="fa fa-star"></span></span>
			</div>
			<span class="help-block with-errors"></span>
		</div>
	</div><!-- Form Group -->
	<div class="form-group">
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_new_password; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="login-addon3"><span class="fa fa-key"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="newPassword1" id="password" type="password" data-error="<?php echo $login_text_019; ?>" required>
				<span class="input-group-addon" id="login-addon4"><span class="fa fa-star"></span></span>
			</div>
			<span class="help-block with-errors"></span>
		</div>
	</div>
	<div class="form-group" id="pwd-container">
		<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_password_strength; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
			<div class="pwd-strength-viewport-progress"></div>
			<div id="length-help-text" class="small"></div>
		</div>
	</div>
	<div class="form-group">
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_confirm_password; ?></label>
		<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="login-addon3"><span class="fa fa-key"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="newPassword2" id="password2" type="password" required data-error="<?php echo $login_text_024; ?>" data-match="#password" data-match-error="<?php echo $login_text_023; ?>" required>
				<span class="input-group-addon" id="login-addon4"><span class="fa fa-star"></span></span>
			</div>
			<span class="help-block with-errors"></span>
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
			<!-- Input Here -->
			<button name="submit" type="submit" class="btn btn-primary" ><?php echo $label_reset_password; ?> <span class="fa fa-key" aria-hidden="true"></span></button>
		</div>
	</div><!-- Form Group -->
</form>
<?php } ?>