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
if (($action != "print") && ($msg != "default")) $message0 .= $msg_output;
if (isset($_SESSION['loginUsername'])) $message1 .= "<div class='error'>You are already logged in.</div>";

if ((($action == "default") || ($action == "login") || ($action == "logout")) && (!isset($_SESSION['loginUsername']))) $login_form_display = TRUE; else $login_form_display = FALSE;
if (($action == "forgot") && ($go == "password") && (!isset($_SESSION['loginUsername']))) $forget_form_display = TRUE; else $foget_form_display = FALSE;
if (($action == "forgot") && ($go == "verify") && (!isset($_SESSION['loginUsername']))) { 
	$verify_form_display = TRUE;
	if (empty($username)) $username_check = $_POST['loginUsername'];
	else $username_check = $username;
	$user_check = user_check($username_check);
	$user_check = explode("^",$user_check);
	
	if (($user_check[0] == 0) && ($msg == "default")) { 
		$message2 .= sprintf("<div class='error'>There is no email address in the system that matches the one you entered.</div><p><a href='%s'>Try again?</a>",build_public_url("login","password","forgot",$sef,$base_url));
	}
	
}
else $verify_form_display = FALSE;

// Build Links

if ($section != "update") {
	if (($msg != "default") && ($registration_open < "2") && (!$verify_form_display)) $primary_links .= sprintf("<p><span class='icon'><img src='%simages/exclamation.png' alt='Exclamation' /></span><span class='data'>Have you <a href='%s'>registered your account</a> yet?</span></p>",$base_url,build_public_url("register","default","default",$sef,$base_url));
	if ($login_form_display) $primary_links .= sprintf("<p><span class='icon'><img src='%simages/exclamation.png' alt='Exclamation' /></span><span class='data'>Did you forget your password? If so, <a href='%s'>click here to reset it</a>.</span></p>",$base_url,build_public_url("login","password","forgot",$sef,$base_url));
}


echo $message0;
echo $message1;
echo $message2;
echo $primary_links;
?>

<?php if ($login_form_display) { ?>
<form action="<?php echo $base_url; ?>includes/logincheck.inc.php?section=<?php echo $section; ?>" method="POST" name="form1" id="form1">
<table class="dataTable">
	<tr>
    	<td class="dataLabel" width="5%">Email Address:</td>
    	<td class="data"><input name="loginUsername" type="text" class="submit" size="40" <?php if ($username != "default") echo "value=\"".$username."\""; ?>></td>
  	</tr>
  	<tr>
    	<td class="dataLabel">Password:</td>
    	<td class="data"><input name="loginPassword" type="password" class="submit" size="25"></td>
  	</tr>
</table>
<p><input type="submit" class="button" value="Login"></p>
</form>
<?php } ?>

<?php if ($forget_form_display) { ?>
<p>To reset your password, enter the email address you used when you registered.</p>
<form action="<?php echo build_public_url("login","verify","forgot",$sef,$base_url); ?>" method="POST" name="form1" id="form1">
<table class="dataTable">
	<tr>
    	<td class="dataLabel" width="5%">Email Address:</td>
    	<td class="data"><input name="loginUsername" type="text" class="submit" size="40" <?php if ($username != "default") echo "value=\"".$username."\""; ?>></td>
  	</tr>
    	<td class="dataLabel">&nbsp;</td>
    	<td class="data"><input type="submit" class="button" value="Submit"></td>
  	</tr>
</table>
</form>
<?php } ?>
<?php if ($verify_form_display) {
	if ((empty($message2)) || (empty($msg))) { ?>
	<form action="<?php echo $base_url; ?>includes/forgot_password.inc.php" method="POST" name="form1" id="form1">
	<table class="dataTable">
	<tr>
    	<td class="dataLabel" width="5%">ID Verification Question:</td>
        <td class="data"><?php echo $user_check[1]; ?></td>
    </tr>
    <tr>
    	<td class="dataLabel">Answer:</td>
        <td class="data"><input name="userQuestionAnswer" type="text" size="40"  /></td>
    </tr>
    <tr>
    	<td class="dataLabel">&nbsp;</td>
    	<td class="data"><input type="submit" class="button" value="Reset Password"></td>
  	</tr>
	</table>
	<input name="loginUsername" type="hidden" class="submit" size="40" value="<?php echo $username; ?>">
	</form>
    <?php if ($_SESSION['prefsContact'] == "Y") { ?><p>Can't remember the answer to your ID verification question? <a href="<?php echo $base_url; ?>includes/forgot_password.inc.php?action=email&amp;id=<?php echo $user_check[2]; ?>" onClick="return confirm('An email will be sent to you with your verification question and answer. Be sure to check your SPAM folder.');">Get it via email</a>.</p><?php } ?>
	<?php }
	}
?>