<?php 
/**
 * Module:      login.sec.php 
 * Description: This module houses the functionality for users to log into the
 *              site using their username and password (encrypted in the db). 
 * 
 */
 

if (($action == "default") || ($action == "login") || ($action == "logout")) {  
if (($action != "print") && ($msg != "default")) echo $msg_output; 
	if (!isset($_SESSION['loginUsername'])) { 
	
?>
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
<?php if ($section != "update") {
if (($msg != "default") && ($registration_open < "2")) { ?><p><span class="icon"><img src="<?php echo $base_url; ?>images/exclamation.png" alt="Exclamation" /></span><span class="data">Have you <a href="<?php echo build_public_url("register","default","default",$sef,$base_url); ?>">registered your account</a> yet?</span></p><?php } ?>
<p><span class="icon"><img src="<?php echo $base_url; ?>images/exclamation.png"   alt="Exclamation" /></span><span class="data">Did you forget your password? If so, <a href="<?php echo build_public_url("login","password","forgot",$sef,$base_url); ?>">click here to reset it</a>.</span></p>
<?php }
} 
if (isset($_SESSION['loginUsername'])) echo "<div class=\"error\">You are already logged in.</div>";
 } 
if ($action == "forgot") { 
	//if (($action != "print") && ($msg != "default")) echo $msg_output; 
	if ($go == "password") {  ?>
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
<?php  }
if ($go == "verify") {
	
	$username = $_POST['loginUsername'];
	$user_check = user_check($username);
	$user_check = explode("^",$user_check);
	
	if (($action != "print") && ($msg != "default")) echo $msg_output;
	
	if (($user_check[0] == 0) && ($msg == "default")) { 
		echo "<div class=\"error\">There is no email address in the system that matches the one you entered.</div><p><a href='".build_public_url("login","password","forgot",$sef,$base_url)."'>Try again?</a>";
		} 
	else { ?>
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
    <?php if ($_SESSION['prefsContact'] == "Y") { ?><p>Can't remember the answer to your ID verification question? <a href="<?php echo $base_url; ?>includes/forgot_password.inc.php?action=email&amp;id=<?php echo $user_check[2]; ?>">Get it via email</a>.</p><?php } ?>
	</form>
	<?php }
	}
}

?>