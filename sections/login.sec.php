<?php 
/**
 * Module:      login.sec.php 
 * Description: This module houses the functionality for users to log into the
 *              site using their username and password (encrypted in the db). 
 * 
 */

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

	$query_userCheck = sprintf("SELECT * FROM %s WHERE user_name = '%s'",$prefix."users",$username_check);
	$userCheck = mysqli_query($connection,$query_userCheck) or die (mysqli_error($connection));
	$row_userCheck = mysqli_fetch_assoc($userCheck);
	$totalRows_userCheck = mysqli_num_rows($userCheck);
	
	if (($totalRows_userCheck == 0) && ($msg == "default")) { 
		$message2 .= sprintf("<div class='text-warning lead'><span class=\"fa fa-lg fa-exclamation-circle\"></span> %s</div><p><a class=\"btn btn-primary\" href=\"%s\">%s</a></p>",$login_text_001, build_public_url("login","password","forgot","default",$sef,$base_url),$login_text_002);
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
<form data-toggle="validator" role="form" class="form-horizontal" action="<?php echo $base_url; ?>includes/process.inc.php?section=login&action=login" method="POST" name="form1" id="form1">
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

	$secret_question = "";
	
	if ((empty($message2)) || (empty($msg))) { 
	
		if ($row_userCheck) {
			
			if ($row_userCheck['userQuestion'] == $login_text_008) {
				$secret_question = $login_text_009; 
				if ($_SESSION['prefsContact'] == "Y") $secret_question .= sprintf(" %s",$login_text_010);
			}

			else $secret_question = $row_userCheck['userQuestion'];
		}

	?>	
	<p class="lead"><?php echo $login_text_011; ?> <small class="text-muted"><em><?php echo $secret_question; ?></em></small></p>
	<p class="lead"><small><?php echo $login_text_015; ?></small></p>
	<form class="form-horizontal" action="<?php echo $base_url; ?>includes/process.inc.php?section=forgot&action=forgot" method="POST" name="form1" id="form1">
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
<?php 
	} // end if ((empty($message2)) || (empty($msg)))
} // end if ($verify_form_display)
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
<form data-toggle="validator" role="form" class="form-horizontal" action="<?php echo $base_url; ?>includes/process.inc.php?section=reset&action=reset&token=<?php echo $token; ?>" method="POST" name="form1" id="form1">
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