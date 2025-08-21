<?php 
/**
 * Module:      login.pub.php 
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
	
	// If not valid, show an error
	if ($token_valid == 1) $message3 .= sprintf("<p class=\"lead\">%s</p>",$login_text_020);
	
	// If expired, show an error
	if ($token_valid == 2) $message3 .= sprintf("<p class=\"lead\">%s</p>",$login_text_021);



	if ($token_valid > 0) $message3 .= sprintf("<div class=\"mt-2\"><button type=\"button\" class=\"btn btn-lg btn-primary\" data-bs-toggle=\"modal\" data-bs-target=\"#forgot-modal\">%s</button></div>",$label_reset_password);
	
}

// Build Links

if ($section != "update") {
	if (($msg != "default") && ($registration_open < "2") && (!$verify_form_display) && (!$reset_token_form_display)) $primary_links .= sprintf("<p class=\"lead\"><span class=\"fa fa-exclamation-circle text-danger\"></span> <a href=\"%s\">%s</a></p>",build_public_url("register","default","default","default",$sef,$base_url),$login_text_003);
	if ($login_form_display) $primary_links .= sprintf("<p class=\"lead\"><span class=\"fa fa-exclamation-circle text-danger\"></span> %s <a href=\"%s\">%s</a>.</p>",$login_text_004,$base_url."index.php?section=login&amp;go=password&amp;action=forgot",$login_text_005);
}
echo $message0;
echo $message1;
echo $message2;
echo $message3;
echo $primary_links;
?>

<?php if ($reset_token_form_display) { ?>
	
	
	<form id="submit-form" role="form" class="form-horizontal hide-loader-form-submit needs-validation" action="<?php echo $base_url; ?>includes/process.inc.php?section=reset&action=reset&token=<?php echo $token; ?>" method="POST" name="form1" novalidate>
		<div class="mb-3 row">
			<label for="loginUsername" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><strong><i class="fa fa-star me-2"></i><?php echo $label_email; ?></strong></label>
			<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
				<input class="form-control" id="loginUsername" name="loginUsername" type="email" value="<?php if ($username != "default") echo $username; ?>" required>
				<div class="help-block mt-1 invalid-feedback text-danger"><?php echo $login_text_018; ?></div>
			</div>
		</div>
		<div class="mb-3 row">
			<label for="password" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><strong><i class="fa fa-star me-2"></i><?php echo $label_new_password; ?></strong></label>
			<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
				<input class="form-control password-field" name="newPassword1" id="password-entry" type="password" required>
				<div class="help-block mt-1 invalid-feedback text-danger"><?php echo $login_text_019; ?></div>
			</div>
		</div>
		<div class="mb-3 row" id="pwd-container">
			<label class="col-xs-12 col-sm-3 col-lg-2 col-form-label"><strong><?php echo $label_password_strength; ?></strong></label>
			<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
				<div class="pwd-strength-viewport-progress mt-2"></div>
				<div id="length-help-text" class="small"></div>
			</div>
		</div>
		<div class="mb-3 row">
			<label for="password-confirm" class="col-xs-12 col-sm-3 col-lg-2 col-form-label text-teal"><strong><i class="fa fa-star me-2"></i><?php echo $label_confirm_password; ?></strong></label>
			<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
				<input class="form-control password-field" name="newPassword2" id="password-confirm" type="password" required>
				<div class="help-block mt-1 invalid-feedback text-danger"><?php echo $login_text_024; ?></div>
				<div id="password-error" class="help-block mt-1 text-danger"><?php echo $login_text_023; ?></div>
			</div>
		</div>
		<div class="mb-3 row">
			<label for="button" class="col-xs-12 col-sm-3 col-lg-2 col-form-label"></label>
			<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
				<div class="d-grid gap-2 mx-auto mb-4">
					<button id="submit-button" name="submit" type="submit" class="btn btn-primary" disabled><?php echo $label_reset_password; ?><i class="fa fa-key ms-2"></i></button>
				</div>
			</div>
		</div>
	</form>

<script>
$(document).ready(function() {
    // Check for autofill on page load and after a short delay
    setTimeout(checkFieldsMatch, 100);
});
</script>
<?php } ?>