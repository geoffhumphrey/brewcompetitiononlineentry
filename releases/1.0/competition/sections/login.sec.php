<div id="header">	
	<div id="header-inner"><h1><?php if ($action == "add") echo "Log In"; if ($action == "forgot") echo "Reset Password"; ?></h1></div>
</div>
<?php if ($action == "add") {  
if ($msg == "1") { ?>
<div class="error">Sorry, there was a problem with your last login attempt.</div>
<p>Please make sure your email address and password are correct. Have you <a href="index.php?section=register">registered your account</a> yet?</p>
<?php }  
if ($msg == "2") { ?>
<div class="error">Your password has been randomly generated and reset to <?php echo $go; ?>.</div><p>You can now log in using your current username and the new password above.</p>
<?php } 
if ($msg == "3") { ?>
<div class="error">You have been logged out.</div>
<?php }	?>
<form action="includes/logincheck.inc.php" method="POST" name="form1" id="form1">
<table class="dataTable">
	<tr>
    	<td class="dataLabel">Email Address:</td>
    	<td class="data"><input name="loginUsername" type="text" class="submit" size="40" <?php if ($username != "default") echo "value=\"".$username."\""; ?>></td>
  	</tr>
  	<tr>
    	<td class="dataLabel">Password:</td>
    	<td class="data"><input name="loginPassword" type="password" class="submit" size="25"></td>
  	</tr>
  	<tr>
    	<td class="dataLabel">&nbsp;</td>
    	<td class="data"><input type="submit" value="Login"></td>
  	</tr>
</table>
</form>
<p><img src="images/exclamation.png"  align="absmiddle"  /><span class="data">Did you forget your password? If so, <a href="index.php?section=login&amp;action=forgot">click here to reset it</a>.</span></p>
<?php } 
if ($action == "forgot") { 
if ($msg == "1") { ?>
<div class="error">Sorry, the email address you entered is not in our database.</div>
<p>Please make sure your email address is correct to reset your password. Have you <a href="index.php?section=register">registered your account</a> yet?</p>
<?php }  
if ($msg == "3") { ?>
<div class="error">Sorry, there was a problem with resetting your password.</div>
<p>Please contact <?php echo $row_contest_info['contestContactName']." at "; $str = $row_contest_info['contestContactEmail']; $orig = array('@', '.'); $replace = array('[AT]', '[DOT]'); $emailDisplay = str_replace($orig, $replace, $str); echo $emailDisplay; ?> to recover your password.
<?php } if ($go == "default") {  ?>
<p>To reset your password, enter your email address below.</p>
<form action="index.php?section=login&action=forgot&go=verify" method="POST" name="form1" id="form1">
<table class="dataTable">
	<tr>
    	<td class="dataLabel">Email Address:</td>
    	<td class="data"><input name="loginUsername" type="text" class="submit" size="40" <?php if ($username != "default") echo "value=\"".$username."\""; ?>></td>
  	</tr>
    	<td class="dataLabel">&nbsp;</td>
    	<td class="data"><input type="submit" value="Submit"></td>
  	</tr>
</table>
</form>
<?php  }
if ($go == "verify") {
if ($msg == "default") $username = $_POST['loginUsername'];
mysql_select_db($database, $brewing);
$query_userCheck = "SELECT * FROM users WHERE user_name = '$username'";
$userCheck = mysql_query($query_userCheck, $brewing) or die(mysql_error());
$row_userCheck = mysql_fetch_assoc($userCheck);
$totalRows_userCheck = mysql_num_rows($userCheck);
if (($totalRows_userCheck == 0) && ($msg == "default")) echo "<div class=\"error\">There is no email address in the system that matches the one you entered.</div><p><a href=\"index.php?section=login&action=forgot\">Try again?</a>";
else { 
if ($msg == "2") { ?>
<div class="error">The security question answer you provided did not match the one in our database. Please try again.</div>
<p>If you feel this is and error, or cannot remember your security question, please contact <?php echo $row_contest_info['contestContactName']." at "; $str = $row_contest_info['contestContactEmail']; $orig = array('@', '.'); $replace = array(' [AT] ', ' [DOT] '); $emailDisplay = str_replace($orig, $replace, $str); echo $emailDisplay; ?> to recover your password.
<?php } ?>
<form action="includes/forgot_password.inc.php" method="POST" name="form1" id="form1">
<table class="dataTable">
	<tr>
    	<td class="dataLabel">ID Verification Question:</td>
        <td class="data"><?php echo $row_userCheck['userQuestion']; ?></td>
    </tr>
    <tr>
    	<td class="dataLabel">Answer:</td>
        <td class="data"><input name="userQuestionAnswer" type="text" size="40"  /></td>
    </tr>
    <tr>
    	<td class="dataLabel">&nbsp;</td>
    	<td class="data"><input type="submit" value="Reset Password"></td>
  	</tr>
</table>
<input name="loginUsername" type="hidden" class="submit" size="40" value="<?php echo $username; ?>">
</form>
<?php } 
	} 
} ?>