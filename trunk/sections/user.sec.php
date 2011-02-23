<?php if ((($_SESSION["loginUsername"] == $row_user['user_name'])) || ($row_user['userLevel'] == "1"))
{
if ($action == "username") { ?><script type="text/javascript" src="js_includes/email_check.js"></script><?php } 
if ($msg != "default") echo $msg_output; 
?>
<?php if ($action == "username") echo "<p>Your current email address is ".$row_user['user_name'].".</p>";
?>
<form action="includes/process.inc.php?section=<?php echo $section; ?>&amp;go=<?php echo $action; ?>&amp;action=edit&amp;dbTable=users&amp;id=<?php echo $row_user['id']; ?>" method="POST" name="form1" id="form1" onSubmit="return CheckRequiredFields()">
<table>
<?php if ($action == "username") { ?>
	<tr>
    	<td class="dataLabel">New Email Address:</td>
    	<td class="data"><input name="user_name" type="text" class="submit" size="40" onchange="AjaxFunction(this.value);"><div id="msg">&nbsp;</div></td>
        <td class="data" id="inf_email">&nbsp;</td>
  	</tr>
<?php } 
if ($action == "password") {
?>
  	<tr>
    	<td class="dataLabel">Current Password:</td>
    	<td class="data"><input name="passwordOld" type="password" size="25"></td>
  	</tr>
    <tr>
    	<td class="dataLabel">New Password:</td>
    	<td class="data"><input name="password" type="password" size="25"></td>
  	</tr>
<?php } ?>
  	<tr>
    	<td class="dataLabel">&nbsp;</td>
    	<td class="data"><input type="submit" class="button" value="Update"></td>
  	</tr>
</table>
<input name="user_name_old" type="hidden" value="<?php echo $row_user['user_name']; ?>">
<input name="userLevel" type="hidden" value="<?php echo $row_user['userLevel']; ?>">
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],$pg); ?>">
</form>
<?php } else echo "<div class=\"error\">You can only edit your own user name and password.</div>"; ?>
