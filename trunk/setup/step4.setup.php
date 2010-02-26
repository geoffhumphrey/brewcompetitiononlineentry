<script type="text/javascript" src="js_includes/email_check.js"></script>
<div id="header">	
	<div id="header-inner"><h1>Set Up Step 4: Create an Admin User Account</h1></div>
</div>
<?php if ($msg == "1") { ?>
<div class="error">Please provide an email address.</div>
<?php } ?>
<p>This account will be the Administrator's account with full access to the entry portal site. This account will be able to add, edit and delete any entries and participants, grant administration privileges to other users, and have the ability to export the necessary files to use with other brew contest-related applications such as HCCP and Excel.</p>
<p>This user will also be able to add, edit, and delete their own entries into the competition.</p>
<form action="includes/process.inc.php?section=setup&action=add&dbTable=users" method="POST" name="form1" id="form1" onSubmit="return CheckRequiredFields()">
<input name="userLevel" type="hidden" value="1" />
<table>
	<tr>
    	<td class="dataLabel">Email Address:</td>
    	<td class="data"><input name="user_name" id="user_name" type="text" class="submit" size="40" onchange="AjaxFunction(this.value);"><div id="msg">Email Format:</div></td>
        <td class="data" id="inf_email"><span class="required">Required</span></td>
  	</tr>
  	<tr>
    	<td class="dataLabel">Password:</td>
    	<td class="data"><input name="password" id="password" type="password" class="submit" size="25"></td>
        <td class="data"><span class="required">Required</span></td>
  	</tr>
    <tr>
    	<td class="dataLabel">Security Question:</td>
    	<td class="data">
    	    <input type="radio" name="userQuestion" value="What is your favorite all-time beer to drink?" id="userQuestion_0" checked="checked" />What is your favorite all-time beer to drink?<br />
    	    <input type="radio" name="userQuestion" value="What is your mother's maiden name?" id="userQuestion_1" />What is your mother's maiden name?<br />
			<input type="radio" name="userQuestion" value="What was the name of the street you grew up on?" id="userQuestion_2" />What was the name of the street you grew up on?<br />
    	    <input type="radio" name="userQuestion" value="What was your high school's mascot?" id="userQuestion_3" />What was your high school&#8217;s mascot?<br />
        </td>
        <td class="data"><em>Choose one. This question will be used to verify your identity should you forget your password.</em></td>
  	</tr>
    <tr>
    	<td class="dataLabel">Security Question Answer:</td>
    	<td class="data"><input name="userQuestionAnswer" type="text" class="submit" size="30"></td>
        <td class="data"><span class="required">Required</span></td>
  	</tr>
  	<tr>
    	<td class="dataLabel">&nbsp;</td>
    	<td class="data"><input type="submit" class="button" value="Register"></td>
        <td class="data">&nbsp;</td>
  	</tr>
</table>
</form>