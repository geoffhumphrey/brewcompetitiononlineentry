<?php if (($action == "default") || ($action == "login") || ($action == "logout")) {  
	if ($msg != "default") echo $msg_output;  
	if (!isset($_SESSION['loginUsername'])) { 
?>
<form action="includes/logincheck.inc.php" method="POST" name="form1" id="form1">
<table class="dataTable">
	<tr>
    	<td class="dataLabel" width="5%">Email Address:</td>
    	<td class="data"><input name="loginUsername" type="text" class="submit" size="40" <?php if ($username != "default") echo "value=\"".$username."\""; ?>></td>
  	</tr>
  	<tr>
    	<td class="dataLabel">Password:</td>
    	<td class="data"><input name="loginPassword" type="password" class="submit" size="25"></td>
  	</tr>
  	<tr>
    	<td class="dataLabel">&nbsp;</td>
    	<td class="data"><input type="submit" class="button" value="Login"></td>
  	</tr>
</table>
</form>
<?php if (lesserDate($today,$reg_deadline)) {
 if ($msg != "default") { ?><p><span class="icon"><img src="images/exclamation.png"   alt="Exclamation" /></span><span class="data">Have you <a href="index.php?section=register">registered your account</a> yet?</span></p>
<?php } } ?>
<p><span class="icon"><img src="images/exclamation.png"   alt="Exclamation" /></span><span class="data">Did you forget your password? If so, <a href="index.php?section=login&amp;action=forgot">click here to reset it</a>.</span></p>
<?php
} 
if (isset($_SESSION['loginUsername'])) echo "<div class=\"error\">You are already logged in.</div>";
 } 
if ($action == "forgot") { 
	if ($msg != "default") echo $msg_output; 
	if ($go == "default") {  ?>
<p>To reset your password, enter your email address below.</p>
<form action="index.php?section=login&amp;action=forgot&amp;go=verify" method="POST" name="form1" id="form1">
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
if ($msg == "default") $username = $_POST['loginUsername'];
mysql_select_db($database, $brewing);
$query_userCheck = "SELECT * FROM users WHERE user_name = '$username'";
$userCheck = mysql_query($query_userCheck, $brewing) or die(mysql_error());
$row_userCheck = mysql_fetch_assoc($userCheck);
$totalRows_userCheck = mysql_num_rows($userCheck);
if (($totalRows_userCheck == 0) && ($msg == "default")) echo "<div class=\"error\">There is no email address in the system that matches the one you entered.</div><p><a href=\"index.php?section=login&amp;action=forgot\">Try again?</a>";
else { if ($msg != "default") echo $msg_output; } ?>
<form action="includes/forgot_password.inc.php" method="POST" name="form1" id="form1">
<table class="dataTable">
	<tr>
    	<td class="dataLabel" width="5%">ID Verification Question:</td>
        <td class="data"><?php echo $row_userCheck['userQuestion']; ?></td>
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
<?php } 
	} 
?>