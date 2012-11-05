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
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/username_check.js" ></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/usable_forms.js"></script>
<script type="text/javascript">
pic1 = new Image(16, 16); 
pic1.src = "<?php echo $base_url; ?>/images/loader.gif";

$(document).ready(function(){

$("#user_name").change(function() { 

var usr = $("#user_name").val();

if(usr.length >= 3)
{
$("#status").html('<span class="icon"><img src="<?php echo $base_url; ?>/images/loader.gif" align="absmiddle"><span>Checking availability...');

    $.ajax({  
    type: "POST",  
    url: "<?php echo $base_url; ?>/includes/username.inc.php",  
    data: "user_name="+ usr,  
    success: function(msg){  
   
   $("#status").ajaxComplete(function(event, request, settings){ 

	if(msg == 'OK')
	{ 
        $("#user_name").removeClass('object_error'); // if necessary
		$("#user_name").addClass("object_ok");
		$(this).html('<span class="icon"><img src="<?php echo $base_url; ?>/images/tick.png" align="absmiddle"></span><span style="color:green;">Email address not in use.</span>');
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
var url="<?php echo $base_url; ?>/includes/email.inc.php";
url=url+"?email="+email;
url=url+"&sid="+Math.random();
httpxml.onreadystatechange=stateck;
httpxml.open("GET",url,true);
httpxml.send(null);
}


//-->
</script>
<?php if (($action != "print") && ($msg != "default") && ($section != "admin")) echo $msg_output; ?>
<?php if (($registration_open < "2") && (!open_limit($totalRows_log,$row_prefs['prefsEntryLimit'],$registration_open))) { ?>
<p>Our competition entry system is completely electronic.
	<ul>
		<li>If you have already registered, <a href="<?php echo build_public_url("login","default","default",$sef,$base_url); ?>"> log in here</a>. </li>
    	<li>To enter your brews or indicate that you are willing to judge or steward, you will need to create an account on our system using the fields below.</li>
    	<li>Your email address will be your user name and will be used as a means of information dissemination by the competition staff. Please make sure it is correct. </li>
    	<li>Once you have registered, you can proceed through the entry process. </li>
  	    <li>Each brew you enter will automatically be assigned a number by the system.</li>
	</ul>
</p>
<?php } if ($go == "default") { ?>
<form name="judgeChoice" id="judgeChoice">
<table>
	<tr>
    	<td class="dataLabel" width="5%">Are you registering as a judge or steward?</td>
    	<td class="data" width="5%">
       	  <select name="judge_steward" id="judge_steward" onchange="jumpMenu('self',this,0)">
    		<option value=""></option>
    		<option value="<?php echo build_public_url("register","judge","default",$sef,$base_url); ?>">Yes</option>
    		<option value="<?php echo build_public_url("register","entrant","default",$sef,$base_url); ?>">No</option>
		  </select>
  		</td>
        <td class="data" id="inf_email"><span class="required">Required</span></td>
  	</tr>
</table>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>
<?php } else { 
$query_countries = "SELECT * FROM $countries_db_table ORDER BY id ASC";
$countries = mysql_query($query_countries, $brewing) or die(mysql_error());
$row_countries = mysql_fetch_assoc($countries);

if ($section != "admin") { 
?>
<div class="info">The information here beyond your first name, last name, and club is strictly for record-keeping and contact purposes. A condition of entry into the competition is providing this information. Your name and club may be displayed should one of your entries place, but no other information will be made public.</div>
<?php } 
if ($section == "admin") { 
echo "<h2>Add";
if ($go == "judge") echo " a Judge/Steward</h2>"; else echo " a Participant</h2>";
} 
?> 
<form action="<?php echo $base_url; ?>/includes/process.inc.php?action=add&amp;dbTable=<?php echo $users_db_table; ?>&amp;section=register&amp;go=<?php echo $go; if ($section == "admin") echo "&amp;filter=admin"; ?>" method="POST" name="form1" id="form1" onSubmit="return CheckRequiredFields()">
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
      <td width="5%" nowrap="nowrap" class="data"><input type="radio" name="brewerSteward" value="Y" id="brewerSteward_0" <?php if ($msg != "4") echo "CHECKED"; if (($msg == "4") && ($_COOKIE['brewerSteward'] == "Y")) echo "CHECKED"; ?> rel="steward_no" />Yes<br /><input type="radio" name="brewerSteward" value="N" id="brewerSteward_1" <?php if (($msg == "4") && ($_COOKIE['brewerSteward'] == "N")) echo "CHECKED"; ?> rel="none" /> No</td>
      <td class="data">&nbsp;</td>
</tr>
	<?php if ($totalRows_judging > 1) { ?>
    <tr rel="steward_no" >
    <td class="dataLabel">Stewarding<br />Location Availabilty:</td>
    <td colspan="3" class="data">
    <?php do { ?>
        <table class="dataTableCompact">
            <tr>
                <td width="1%" nowrap="nowrap">
                    <select name="brewerStewardLocation[]" id="brewerStewardLocation">
                        <option value="<?php echo "Y-".$row_stewarding['id']; ?>" <?php $a = explode(",", $row_brewer['brewerStewardLocation']); $b = "Y-".$row_stewarding['id']; foreach ($a as $value) { if ($value == $b) { echo "SELECTED"; } } ?>>Yes</option>
                        <option value="<?php echo "N-".$row_stewarding['id']; ?>" <?php $a = explode(",", $row_brewer['brewerStewardLocation']); $b = "N-".$row_stewarding['id']; foreach ($a as $value) { if ($value == $b) { echo "SELECTED"; } } ?>>No</option>
                    </select>
                </td>
                <td class="data"><?php echo $row_stewarding['judgingLocName']." ("; echo getTimeZoneDateTime($row_prefs['prefsTimeZone'], $row_stewarding['judgingDate'], $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "long", "date-time").")"; ?></td>
            </tr>
        </table>
    <?php }  while ($row_stewarding = mysql_fetch_assoc($stewarding));  ?>
    </td>
    </tr>
    <?php } ?>
<tr>
      <td class="dataLabel">Judging:</td>
      <td class="data">Are you willing and qualified to judge in this competition?</td>
      <td width="5%" nowrap="nowrap" class="data"><input type="radio" name="brewerJudge" value="Y" id="brewerJudge_0"  <?php if ($msg != "4") echo "CHECKED"; if (($msg == "4") && ($_COOKIE['brewerJudge'] == "Y")) echo "CHECKED"; ?> rel="judge_no" /> Yes<br /><input type="radio" name="brewerJudge" value="N" id="brewerJudge_1" <?php if (($msg == "4") && ($_COOKIE['brewerJudge'] == "N")) echo "CHECKED"; ?> rel="none" /> No</td>
      <td class="data">&nbsp;</td>
</tr>

	<?php if ($totalRows_judging > 1) { ?>
    <tr rel="judge_no">
    <td class="dataLabel">Judging<br />Location Availablity:</td>
    <td class="data" colspan="3">
    <?php do { ?>
        <table class="dataTableCompact">
            <tr>
                <td width="1%" nowrap="nowrap">
                <select name="brewerJudgeLocation[]" id="brewerJudgeLocation">
                    <option value="<?php echo "Y-".$row_judging3['id']; ?>"   <?php $a = explode(",", $row_brewer['brewerJudgeLocation']); $b = "Y-".$row_judging3['id']; foreach ($a as $value) { if ($value == $b) { echo "SELECTED"; } } ?>>Yes</option>
                    <option value="<?php echo "N-".$row_judging3['id']; ?>"   <?php $a = explode(",", $row_brewer['brewerJudgeLocation']); $b = "N-".$row_judging3['id']; foreach ($a as $value) { if ($value == $b) { echo "SELECTED"; } } ?>>No</option>
                </select>
                </td>
                <td class="data"><?php echo $row_judging3['judgingLocName']." ("; echo getTimeZoneDateTime($row_prefs['prefsTimeZone'], $row_judging3['judgingDate'], $row_prefs['prefsDateFormat'],  $row_prefs['prefsTimeFormat'], "long", "date-time").")"; ?></td>
            </tr>
        </table>
    <?php }  while ($row_judging3 = mysql_fetch_assoc($judging3)); ?>
    </td>
    </tr>
    <?php } else { ?>
        <input name="brewerJudgeLocation" type="hidden" value="<?php echo "Y-".$row_judging3['id']; ?>" />
        <input name="brewerStewardLocation" type="hidden" value="<?php echo "Y-".$row_judging3['id']; ?>" />
    <?php } ?>

<?php } 
if ($section != "admin") { 
?>
<tr>
	<td class="dataLabel">CAPTCHA:</td>
    <td class="data">
    <img id="captcha" src="<?php echo $base_url; ?>/captcha/securimage_show.php" alt="CAPTCHA Image" style="border: 1px solid #000000;" />
	<p>
    <object type="application/x-shockwave-flash" data="<?php echo $base_url; ?>/captcha/securimage_play.swf?audio_file=<?php echo $base_url; ?>/captcha/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#000&amp;borderWidth=1&amp;borderColor=#000" width="19" height="19">
	<param name="movie" value="<?php echo $base_url; ?>/captcha/securimage_play.swf?audio_file=<?php echo $base_url; ?>/captcha/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#000&amp;borderWidth=1&amp;borderColor=#000" />
	</object>
    &nbsp;Play audio
    </p>
	<p><input type="text" name="captcha_code" size="10" maxlength="6" /><br />Enter the characters above exactly as displayed.</p>
    <p>Can't read the characters?<br /><a href="#" onclick="document.getElementById('captcha').src = '<?php echo $base_url; ?>/captcha/securimage_show.php?' + Math.random(); return false">Reload the Captcha Image</a>.</p>
    </td>
    <td class="data"><span class="required">Required</span></td>
    <td class="data">&nbsp;</td>
</tr>
<?php } ?>
</table>
<p><input name="submit" type="submit" class="button" value="Register" /></p>
<script type="text/javascript">
  	$( function () {
  		twitter.screenNameKeyUp();
  		$('#user_screen_name').focus();
	});
</script>
<input type="hidden" name="userLevel" value="2" />
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php if ($go == "entrant") { ?>
<input type="hidden" name="brewerJudge" value="N" />
<input type="hidden" name="brewerSteward" value="N" />
<?php } ?>
</form>
<?php } ?>