<div id="header">

	<div id="header-inner"><h1>Change Your <?php if ($action == "username") echo "Email"; if ($action == "password") echo "Password"; ?></h1></div>

</div>

<?php if ((($_SESSION["loginUsername"] == $row_user['user_name'])) || ($row_user['userLevel'] == "1"))

{

if ($action == "username") { ?>

<script type="text/javascript">

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

document.getElementById("msg").innerHTML=httpxml.responseText;



}

}

var url="includes/email.inc.php";

url=url+"?email="+email;

url=url+"&sid="+Math.random();

httpxml.onreadystatechange=stateck;

httpxml.open("GET",url,true);

httpxml.send(null);

}

</script>

<?php } 

if ($msg == "1") echo "<div class=\"error\">The email address provided is already in use, please provide another email address.</div>";

if ($msg == "2") echo "<div class=\"error\">There was a problem with the last request, please try again.</div>";

if ($msg == "3") echo "<div class=\"error\">Your current password was incorrect. Please try again.</div>";

if ($msg == "4") echo "<div class=\"error\">Please provide an email address.</div>";

?>

<?php if ($action == "username") echo "<p>Your current email address is ".$row_user['user_name'].".</p>";

?>

<form action="includes/process.inc.php?section=<?php echo $section; ?>&go=<?php echo $action; ?>&action=edit&dbTable=users&id=<?php echo $row_user['id']; ?>" method="POST" name="form1" id="form1" onSubmit="return CheckRequiredFields()">

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

</form>

<?php } else echo "<div class=\"error\">You can only edit your own user name and password.</div>"; ?>

