<?php 
/**
 * Module:      register.sec.php 
 * Description: This module houses the functionality for new users to set
 *              up their account. 
 * 
 */
 
include(DB.'judging_locations.db.php');
include(DB.'stewarding.db.php'); 
include(DB.'styles.db.php'); 
include(DB.'brewer.db.php');
?>
<script type="text/javascript" src="js_includes/email_check.js"></script>
<script type="text/javascript" src="js_includes/username_check.js" ></script>
<script type="text/javascript">
pic1 = new Image(16, 16); 
pic1.src = "images/loader.gif";

$(document).ready(function(){

$("#user_name").change(function() { 

var usr = $("#user_name").val();

if(usr.length >= 3)
{
$("#status").html('<span class="icon"><img src="images/loader.gif" align="absmiddle"><span>Checking availability...');

    $.ajax({  
    type: "POST",  
    url: "includes/username.inc.php",  
    data: "user_name="+ usr,  
    success: function(msg){  
   
   $("#status").ajaxComplete(function(event, request, settings){ 

	if(msg == 'OK')
	{ 
        $("#user_name").removeClass('object_error'); // if necessary
		$("#user_name").addClass("object_ok");
		$(this).html('<span class="icon"><img src="images/tick.png" align="absmiddle"></span><span style="color:green;">Email address not in use.</span>');
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

//-->
</script>
<?php if (($action != "print") && ($msg != "default")) echo $msg_output; ?>
<p>Our competition entry system is completely electronic.
	<ul>
		<li>If you have already registered, <a href="index.php?section=login"> log in here</a>. </li>
    	<li>To enter your brews or indicate that you are willing to judge or steward, you will need to create an account on our system using the fields below.</li>
    	<li>Your email address will be your user name and will be used as a means of information dissemination by the competition staff. Please make sure it is correct. </li>
    	<li>Once you have registered, you can proceed through the entry process. </li>
  	    <li>Each brew you enter will automatically be assigned a number by the system.</li>
	</ul>
</p>
<?php if ($go == "default") { ?>
<form name="judgeChoice" id="judgeChoice">
<table>
	<tr>
    	<td class="dataLabel" width="5%">Are you registering as a judge or steward?</td>
    	<td class="data" width="5%">
       	  <select name="judge_steward" id="judge_steward" onchange="jumpMenu('self',this,0)">
    		<option value=""></option>
    		<option value="index.php?section=register&amp;go=judge">Yes</option>
    		<option value="index.php?section=register&amp;go=entrant">No</option>
		  </select>
  		</td>
        <td class="data" id="inf_email"><span class="required">Required</span></td>
  	</tr>
</table>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],$pg); ?>">
</form>
<?php } else { 
$query_countries = "SELECT * FROM countries ORDER BY id ASC";
$countries = mysql_query($query_countries, $brewing) or die(mysql_error());
$row_countries = mysql_fetch_assoc($countries);
?>
<div class="info">The information here beyond your first name, last name, and club is strictly for record-keeping and contact purposes. A condition of entry into the competition is providing this information. Your name and club may be displayed should one of your entries place, but no other information will be made public.</div>
  
<form action="includes/process.inc.php?action=add&amp;dbTable=users&amp;section=register&amp;go=<?php echo $go; ?>" method="POST" name="form1" id="form1" onSubmit="return CheckRequiredFields()">
<table>
	<tr>
    	<td class="dataLabel">Email Address:</td>
    	<td class="data"><input name="user_name" id="user_name" type="text" class="submit" size="40" onkeyup="twitter.updateUrl(this.value)" onchange="AjaxFunction(this.value);" value="<?php if ($msg == "4") echo $_COOKIE['user_name']; ?>"><div id="msg_email">Email Format:</div><div id="status"></div></td>
        <td class="data" id="inf_email"><span class="required">Required</span></td>
  	</tr>
  	<tr>
    	<td class="dataLabel">Password:</td>
    	<td class="data"><input name="password" id="password" type="password" class="submit" size="25"  value="<?php if ($msg == "4") echo $_COOKIE['password']; ?>"></td>
        <td class="data"><span class="required">Required</span></td>
  	</tr>
<?php if ($section != "admin") { ?>
    <tr> 
    	<td class="dataLabel">Security Question:</td>
    	<td class="data" nowrap="nowrap">
        <input type="radio" name="userQuestion" value="What is your favorite all-time beer to drink?" id="userQuestion_0" <?php if (($msg == "4") && ($_COOKIE['userQuestion'] == "What is your favorite all-time beer to drink?")) echo "CHECKED"; if ($msg == "default") echo "CHECKED"; ?> />What is your favorite all-time beer to drink?<br />
    	    <input type="radio" name="userQuestion" value="What was the name of your first pet?" id="userQuestion_1" <?php if (($msg == "4") && ($_COOKIE['userQuestion'] == "What was the name of your first pet?")) echo "CHECKED"; ?>  />What was the name of your first pet?<br />
			<input type="radio" name="userQuestion" value="What was the name of the street you grew up on?" id="userQuestion_2" <?php if (($msg == "4") && ($_COOKIE['userQuestion'] == "What was the name of the street you grew up on?")) echo "CHECKED"; ?>  />What was the name of the street you grew up on?<br />
    	    <input type="radio" name="userQuestion" value="What was your high school mascot?" id="userQuestion_3" <?php if (($msg == "4") && ($_COOKIE['userQuestion'] == "What was your high school mascot?")) echo "CHECKED"; ?>  />What was your high school mascot?<br />
        </td>
        <td class="data"><span class="required">Required</span></td>
      	<td>Choose one. This question will be used to verify your identity should you forget your password.</td>
  	</tr>
    <tr>
    	<td class="dataLabel">Security Question Answer:</td>
    	<td class="data"><input name="userQuestionAnswer" type="text" class="submit" size="30"  value="<?php if ($msg == "4") echo $_COOKIE['userQuestionAnswer']; ?>"></td>
        <td class="data"><span class="required">Required</span></td>
        <td class="data">&nbsp;</td>
  	</tr>
<?php } ?>
	<!--
  	<tr>
    	<td class="dataLabel">&nbsp;</td>
    	<td class="data"><input type="submit" class="button" value="Register"></td>
        <td class="data">&nbsp;</td>
  	</tr>
    -->
<tr>
      <td class="dataLabel" width="5%">First Name:</td>
      <td class="data" width="20%"><input type="text" id="brewerFirstName" name="brewerFirstName" value="<?php if ($msg == "4") echo $_COOKIE['brewerFirstName']; ?>" size="32" maxlength="20"></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Required</span></td>
      <td rowspan="2" class="data">Please enter only <em>one</em> person's name.<br />
      You will be able to identify a co-brewer when adding your entries.</td>
</tr>
<tr>
      <td class="dataLabel">Last Name:</td>
      <td class="data"><input type="text" name="brewerLastName" value="<?php if ($msg == "4") echo $_COOKIE['brewerLastName']; ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Required</span></td>
    </tr>
<tr>
      <td class="dataLabel">Street Address:</td>
      <td class="data"><input type="text" name="brewerAddress" value="<?php if ($msg == "4") echo $_COOKIE['brewerAddress']; ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Required</span></td>
      <td class="data">&nbsp;</td>
</tr>
<tr>
      <td class="dataLabel">City:</td>
      <td class="data"><input type="text" name="brewerCity" value="<?php if ($msg == "4") echo $_COOKIE['brewerCity']; ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Required</span></td>
      <td class="data">&nbsp;</td>
</tr>
<tr>
      <td class="dataLabel">State or Province:</td>
      <td class="data"><input type="text" name="brewerState" value="<?php if ($msg == "4") echo $_COOKIE['brewerState']; ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Required</span></td>
      <td class="data">&nbsp;</td>
</tr>
<tr>
      <td class="dataLabel">Zip or Postal Code:</td>
      <td class="data"><input type="text" name="brewerZip" value="<?php if ($msg == "4") echo $_COOKIE['brewerZip']; ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Required</span></td>
      <td class="data">&nbsp;</td>
</tr>
<tr>
  	<td class="dataLabel">Country:</td>
  	<td class="data">
    <select name="brewerCountry">
    	<?php do { ?>
        <option value="<?php echo $row_countries['name']; ?>" <?php if (($msg == "4") && ($_COOKIE['brewerCountry'] == $row_countries['name'])) echo "selected"; ?>><?php echo $row_countries['name']; ?></option>
        <?php } while ($row_countries = mysql_fetch_assoc($countries)); ?>
    </select>
    </td>
  	<td nowrap="nowrap" class="data"><span class="required">Required</span></td>
  	<td class="data">&nbsp;</td>
</tr>
<tr>
      <td class="dataLabel">Phone 1:</td>
      <td class="data"><input type="text" name="brewerPhone1" value="<?php if ($msg == "4") echo $_COOKIE['brewerPhone1']; ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data"><span class="required">Required</span></td>
      <td class="data">&nbsp;</td>
</tr>
<tr>
      <td class="dataLabel">Phone 2:</td>
      <td class="data"><input type="text" name="brewerPhone2" value="<?php if ($msg == "4") echo $_COOKIE['brewerPhone2']; ?>" size="32"></td>
      <td width="5%" nowrap="nowrap" class="data">&nbsp;</td>
      <td class="data">&nbsp;</td>
</tr>
<tr>
      <td class="dataLabel">Club Name:</td>
      <td class="data"><input type="text" name="brewerClubs" value="<?php if ($msg == "4") echo $_COOKIE['brewerClubs']; ?>" size="32" maxlength="200"></td>
      <td width="5%" nowrap="nowrap" class="data">&nbsp;</td>
      <td class="data">&nbsp;</td>
</tr>
<tr>
  <td class="dataLabel">AHA Member Number:</td>
  <td class="data"><input type="text" name="brewerAHA" value="<?php if ($msg == "4") echo $_COOKIE['brewerAHA']; ?>" size="11" maxlength="11" /></td>
  <td class="data">&nbsp;</td>
  <td class="data">To be considered for a GABF Pro-Am brewing opportunity you must be an AHA member.</td>
</tr>
<?php if ($go != "entrant") { ?>
<tr>
      <td class="dataLabel">Stewarding:</td>
      <td class="data">Are you willing be a steward in this competition?</td>
      <td width="5%" nowrap="nowrap" class="data"><input type="radio" name="brewerSteward" value="Y" id="brewerSteward_0" <?php if ($msg != "4") echo "CHECKED"; if (($msg == "4") && ($_COOKIE['brewerSteward'] == "Y")) echo "CHECKED"; ?>  />Yes<br /><input type="radio" name="brewerSteward" value="N" id="brewerSteward_1" <?php if (($msg == "4") && ($_COOKIE['brewerSteward'] == "N")) echo "CHECKED"; ?> /> No</td>
      <td class="data">&nbsp;</td>
</tr>
<tr>
      <td class="dataLabel">Judging:</td>
      <td class="data">Are you willing and qualified to judge in this competition?</td>
      <td width="5%" nowrap="nowrap" class="data"><input type="radio" name="brewerJudge" value="Y" id="brewerJudge_0"  <?php if ($msg != "4") echo "CHECKED"; if (($msg == "4") && ($_COOKIE['brewerJudge'] == "Y")) echo "CHECKED"; ?> /> Yes<br /><input type="radio" name="brewerJudge" value="N" id="brewerJudge_1" <?php if (($msg == "4") && ($_COOKIE['brewerJudge'] == "N")) echo "CHECKED"; ?> /> No</td>
      <td class="data">&nbsp;</td>
</tr>
</tr>
<?php } ?>
<tr>
	<td class="dataLabel">CAPTCHA:</td>
    <td class="data">
    <img id="captcha" src="captcha/securimage_show.php" alt="CAPTCHA Image" style="border: 1px solid #000000;" />
	<p><object type="application/x-shockwave-flash" data="captcha/securimage_play.swf?audio=captcha/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#777&amp;borderWidth=1&amp;borderColor=#000" height="19" width="19">
	    <param name="movie" value="captcha/securimage_play.swf?audio=captcha/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#777&amp;borderWidth=1&amp;borderColor=#000" />
  	</object>&nbsp;Play audio</p>
	<p><input type="text" name="captcha_code" size="10" maxlength="6" /><br />Enter the characters above exactly as displayed.</p>
    <p>Can't read the characters?<br /><a href="#" onclick="document.getElementById('captcha').src = 'captcha/securimage_show.php?' + Math.random(); return false">Reload the Captcha Image</a>.</p>
    </td>
    <td class="data"><span class="required">Required</span></td>
    <td class="data">&nbsp;</td>
</tr>
</table>
<p><input name="submit" type="submit" class="button" value="Register" /></p>
<script type="text/javascript">
  	$( function () {
  		twitter.screenNameKeyUp();
  		$('#user_screen_name').focus();
	});
</script>
<input type="hidden" name="userLevel" value="2" />
<?php if ($go == "entrant") { ?>
<input type="hidden" name="brewerJudge" value="N" />
<input type="hidden" name="brewerSteward" value="N" />
<?php } ?>
</form>
<?php } ?>