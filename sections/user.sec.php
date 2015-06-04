<?php
/**
 * Module:      user.sec.php 
 * Description: This module houses the functionality for users to add/update enter their
 *              user name and password information. 
 * 
 */
 
if ((($_SESSION['loginUsername'] == $_SESSION['user_name'])) || ($_SESSION['userLevel'] <= "1")) {
if ($action == "username") { ?>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/username_check.js" ></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/usable_forms.js"></script>
<script type="text/javascript">
pic1 = new Image(16, 16); 
pic1.src = "<?php echo $base_url; ?>images/loader.gif";
$(document).ready(function(){
$("#user_name").change(function() { 
var usr = $("#user_name").val();
if(usr.length >= 6)
{
$("#status").html('<span class="icon"><img src="<?php echo $base_url; ?>images/loader.gif" align="absmiddle"><span>Checking availability...');
    $.ajax({  
    type: "POST",  
    url: "<?php echo $base_url; ?>includes/username.inc.php",  
    data: "user_name="+ usr,  
    success: function(msg){  
   
   $("#status").ajaxComplete(function(event, request, settings){ 
	if(msg == 'OK')
	{ 
        $("#user_name").removeClass('object_error'); // if necessary
		$("#user_name").addClass("object_ok");
		$(this).html('<span style="color:green;">Email address not in use.</span>');
	}  
	else  
	{  
		$("#user_name").removeClass('object_ok'); // if necessary
		$("#user_name").addClass("object_error");
		$(this).html(msg);
	}  
   
   });
 } 
   
  }); 
}
else
	{
	$("#status").html('<font color="red">The username should have at least <strong>6</strong> characters.</font>');
	$("#user_name").removeClass('object_ok'); // if necessary
	$("#user_name").addClass("object_error");
	}
});
});
function AjaxFunction(email)
{
	var httpxml;
		try
		{
		// Firefox, Opera 8.0+, Safari
		httpxml=new XMLHttpRequest();
		}
	catch (e)
		{
		// Internet Explorer
		try
		{
		httpxml=new ActiveXObject("Msxml2.XMLHTTP");
		}
	catch (e)
		{
		try
		{
		httpxml=new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch (e)
		{
		//alert("Your browser does not support AJAX!");
	return false;
	}
	}
}
function stateck()
{
if(httpxml.readyState==4)
{
document.getElementById("msg_email").innerHTML=httpxml.responseText;
}
}
var url="<?php echo $base_url; ?>includes/email.inc.php";
url=url+"?email="+email;
url=url+"&sid="+Math.random();
httpxml.onreadystatechange=stateck;
httpxml.open("GET",url,true);
httpxml.send(null);
}
//-->
</script>
<?php } 
if (($action != "print") && ($msg != "default")) echo $msg_output; 
if ($action == "username") { ?>
<p><span class="icon"><img src="<?php echo $base_url; ?>images/help.png"  /></span><a id="modal_window_link" href="http://help.brewcompetition.com/files/change_email_address.html" title="BCOE&amp;M Help: Change Email Address">Change Email Address Help</a></p>
<?php } if ($action == "password") { ?>
<p><span class="icon"><img src="<?php echo $base_url; ?>images/help.png"  /></span><a id="modal_window_link" href="http://help.brewcompetition.com/files/change_password.html" title="BCOE&amp;M Help: Change Password">Change Password Help</a></p>
<?php } 
//if (($action == "username") && ($filter != "admin")) echo "<p>Your current email address is ".$_SESSION['user_name'].".</p>";
if (($action == "username") && ($filter == "admin")) {
	echo "<p>You are changing ".$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName']."&rsquo;s Email Address (User Name).</p>";
	
}
?>
<form action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo $section; ?>&amp;go=<?php echo $action; ?>&amp;action=edit&amp;dbTable=<?php echo $users_db_table; ?>&amp;filter=<?php echo $filter; ?>&amp;id=<?php if ($filter == "admin") echo $row_brewer['uid']; else echo $_SESSION['user_id']; ?>" method="POST" name="form1" id="form1" onSubmit="return CheckRequiredFields()">
<table>
<?php if ($action == "username") { ?>
	<tr>
    	<td class="dataLabel">Current Email Address:</td>
    	<td class="data"><?php if ($filter == "admin") echo $row_brewer['brewerEmail']; else echo $_SESSION['user_name']; ?></td>
        <td class="data">&nbsp;</td>
  	</tr>
	<tr>
    	<td class="dataLabel">New Email Address:</td>
    	<td class="data"><input name="user_name" id="user_name"  type="text" class="submit" size="40"  onkeyup="twitter.updateUrl(this.value)" onchange="AjaxFunction(this.value);" ><div id="msg_email">Email Format:</div><div id="status"></div></td>
        <td class="data"><div id="inf_email">&nbsp;</div></td>
  	</tr>
    <tr>
    	<td class="dataLabel">Are You Sure?</td>
    	<td class="data"><input type="checkbox" id="sure" name="sure" value="Y" />Yes</td>
        <td class="data">&nbsp;</td>
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
</table>
<p><input type="submit" class="button" value="Update"></p>
<input name="user_name_old" type="hidden" value="<?php if ($filter == "admin") echo $row_brewer['brewerEmail']; else echo $_SESSION['user_name']; ?>">
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>
<?php } else echo "<div class=\"error\">You can only edit your own user name and password.</div>"; ?>
