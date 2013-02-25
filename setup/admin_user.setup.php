<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/email_check.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/username_check.js" ></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/usable_forms.js"></script>
<script type="text/javascript">
pic1 = new Image(16, 16); 
pic1.src = "<?php echo $base_url; ?>images/loader.gif";

$(document).ready(function(){

$("#user_name").change(function() { 

var usr = $("#user_name").val();

if(usr.length >= 3)
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
		$(this).html('<span class="icon"><img src="<?php echo $base_url; ?>images/tick.png" align="absmiddle"></span><span style="color:green;">Email address not in use.</span>');
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
	$("#status").html('<font color="red">The username should have at least <strong>3</strong> characters.</font>');
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
<?php if (($action != "print") && ($msg != "default")) echo $msg_output; ?>
<p class="info">This will be the Administrator's account with full access to <em>all</em> of the installation's features and functions. The owner of this account will be able to add, edit, and delete any entry and participant, grant administration privileges to other users, define custom styles, define tables and flights, add scores, print reports, etc. This user will also be able to add, edit, and delete their own entries into the competition.</p>
<form action="<?php echo $base_url; ?>includes/process.inc.php?section=setup&amp;action=add&amp;dbTable=<?php echo $users_db_table; ?>" method="POST" name="form1" id="form1" onSubmit="return CheckRequiredFields()">
<input name="userLevel" type="hidden" value="0" />
<table>
	<tr>
    	<td class="dataLabel">Email Address:</td>
    	<td class="data"><input name="user_name" id="user_name" type="text" class="submit" size="40" onkeyup="twitter.updateUrl(this.value)" onchange="AjaxFunction(this.value);" value="<?php if ($msg == "4") echo $_COOKIE['user_name']; ?>"><div id="msg_email">Email Format:</div><div id="status"></div></td>
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
</table>
<p><input type="submit" class="button" value="Register"></p>
</form>