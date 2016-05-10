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

// Build Messages
if (isset($_SESSION['loginUsername'])) $message1 .= "<p class=\"lead\">You are already logged in.</p>";

if ((($action == "default") || ($action == "login") || ($action == "logout")) && (!isset($_SESSION['loginUsername']))) $login_form_display = TRUE; else $login_form_display = FALSE;
if (($action == "forgot") && ($go == "password") && (!isset($_SESSION['loginUsername']))) $forget_form_display = TRUE; else $forget_form_display = FALSE;
if (($action == "forgot") && ($go == "verify") && (!isset($_SESSION['loginUsername']))) { 
	$verify_form_display = TRUE;
	if ($username == "default") $username_check = $_POST['loginUsername'];
	else $username_check = $username;
	
	$user_check = user_check($username_check);
	$user_check = explode("^",$user_check);
	
	if (($user_check[0] == 0) && ($msg == "default")) { 
		$message2 .= sprintf("<div class='alert alert-danger'><span class=\"fa fa-exclamation-circle\"></span> There is no email address in the system that matches the one you entered. <a class='alert-link' href='%s'>Try again?</a></div>",build_public_url("login","password","forgot","default",$sef,$base_url));
	}
	
}
else $verify_form_display = FALSE;

// Build Links

if ($section != "update") {
	if (($msg != "default") && ($registration_open < "2") && (!$verify_form_display)) $primary_links .= sprintf("<p class='lead'><span class='fa fa-exlamation-circle'></span> Have you <a href='%s'>registered your account</a> yet?</p>",build_public_url("register","default","default","default",$sef,$base_url));
	if ($login_form_display) $primary_links .= sprintf("<p class='lead'><span class='fa fa-exlamation-circle'></span> Did you forget your password? If so, <a href='%s'>click here to reset it</a>.</p>",$base_url."index.php?section=login&amp;go=password&amp;action=forgot");
}


echo $message0;
echo $message1;
echo $message2;
echo $primary_links;
?>

<?php if ($login_form_display) { ?>
<form class="form-horizontal" action="<?php echo $base_url; ?>includes/logincheck.inc.php?section=<?php echo $section; ?>" method="POST" name="form1" id="form1">
	<div class="form-group">
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Email Address</label>
		<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="login-addon1"><span class="fa fa-envelope"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="loginUsername" type="email" value="<?php if ($username != "default") echo $username; ?>" autofocus required>
				<span class="input-group-addon" id="login-addon2"><span class="fa fa-star"></span></span>
			</div>
		</div>
	</div><!-- Form Group -->
	<div class="form-group">
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Password</label>
		<div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<span class="input-group-addon" id="login-addon3"><span class="fa fa-key"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="loginPassword" type="password" required>
				<span class="input-group-addon" id="login-addon4"><span class="fa fa-star"></span></span>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
			<!-- Input Here -->
			<button name="submit" type="submit" class="btn btn-primary" >Log In <span class="fa fa-sign-in" aria-hidden="true"></span> </button>
		</div>
	</div><!-- Form Group -->
</form>
<?php } ?>

<?php if ($forget_form_display) { ?>
<p class="lead">To reset your password, enter the email address you used when you registered.</p>
<form class="form-horizontal" action="<?php echo build_public_url("login","verify","forgot","default",$sef,$base_url); ?>" method="POST" name="form1" id="form1">
	<div class="form-group">
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Email Address</label>
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
			<button name="submit" type="submit" class="btn btn-primary" >Verify <span class="fa fa-check-circle" aria-hidden="true"></span> </button>
		</div>
	</div><!-- Form Group -->
</form>
<?php } ?>
<?php if ($verify_form_display) {
	if ((empty($message2)) || (empty($msg))) { 
	
	if ($user_check[1] == "Randomly generated.") {
		$secret_question = "<strong>Unavailable.</strong> Your account was created by an administrator and your &quot;secret answer&quot; was randomly generated. Please contact a website administrator to recover or change your password."; 
		if ($_SESSION['prefsContact'] == "Y") $secret_question .= " Or, use the email option below.";
	}
	else $secret_question = $user_check[1];
	
	?>	
	<p class="lead">Your ID verification question is... <small class="text-muted"><em><?php echo $secret_question; ?></em></small></p>
	<?php if ($_SESSION['prefsContact'] == "Y") { ?>
	<?php if ($msg =="5") { ?>
	<p class='lead'><small>If you didn't receive the email, <a href="<?php echo $base_url; ?>includes/forgot_password.inc.php?action=email&amp;id=<?php echo $user_check[2]; ?>" data-confirm="An email will be sent to you with your verification question and answer. Be sure to check your SPAM folder.">click here to resend it to <?php echo $username_check; ?></a>.</small></p>
	<?php } else { ?>
	<p class='lead'><small>Can't remember the answer to your ID verification question? <a href="<?php echo $base_url; ?>includes/forgot_password.inc.php?action=email&amp;id=<?php echo $user_check[2]; ?>" data-confirm="An email will be sent to you with your verification question and answer. Be sure to check your SPAM folder.">Get it emailed to <?php echo $username_check; ?></a>.</small></p>
	<?php } ?>
	<?php } ?>
<form class="form-horizontal" action="<?php echo $base_url; ?>includes/forgot_password.inc.php" method="POST" name="form1" id="form1">
	<div class="form-group">
		<label for="" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Answer</label>
		<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
			<div class="input-group">
				<span class="input-group-addon" id="id-verify-addon1"><span class="fa fa-bullhorn"></span></span>
				<!-- Input Here -->
				<input class="form-control" name="userQuestionAnswer" type="text" autofocus>
			</div>
		</div>
	</div><!-- Form Group -->
	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
			<!-- Input Here -->
			<button name="submit" type="submit" class="btn btn-primary" >Reset Password <span class="fa fa-key" aria-hidden="true"></span></button>
		</div>
	</div><!-- Form Group -->
<input type="hidden" name="loginUsername" value="<?php echo $username_check; ?>">
</form>
    <?php }
	}
?>