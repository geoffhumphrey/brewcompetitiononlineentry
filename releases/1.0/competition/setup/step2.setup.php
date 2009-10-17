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
<div id="header">	
	<div id="header-inner"><h1>Set Up Step 2: Enter Your Contest Information </h1></div>
</div>
<p>Here is where you enter the details about your contest. The more complete you are here, the better.</p>
<form method="post" action="includes/process.inc.php?action=add&dbTable=contest_info&id=1" name="form1" onSubmit="return CheckRequiredFields()">
<table>
  <tr>
  	<td colspan="3"><h2>Contact</h2></td>
  </tr>
  <tr>
    <td class="dataLabel">Contest Contact Name:</td>
    <td class="data"><input name="contestContactName" type="text" size="50" maxlength="255"value=""></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Contest Contact Email:</td>
    <td class="data"><input name="contestContactEmail" type="text" size="50" maxlength="255" onchange="AjaxFunction(this.value);"  value=""><div id="msg">Email Format:</div></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
  	<td colspan="3"><h2>General Info</h2></td>
  </tr>
  <tr>
    <td class="dataLabel">Contest Name:</td>
    <td class="data"><input name="contestName" type="text" size="50" maxlength="255" value=""></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Hosted By:</td>
    <td class="data"><input name="contestHost" type="text" size="50" maxlength="255" value=""></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Host Location:</td>
    <td class="data"><input name="contestHostLocation" type="text" size="50" maxlength="255" value=""></td>
    <td class="data">&nbsp;</td>
  </tr>
  <tr>
    <td class="dataLabel">Host Website Address:</td>
    <td class="data"><input name="contestHostWebsite" type="text" size="50" maxlength="255" value=""></td>
    <td class="data">Provide the entire website address including the http://</td>
  </tr>
  <tr>
    <td class="dataLabel">Entry Deadline:</td>
    <td class="data"><input name="contestEntryDeadline" type="text" size="20" onfocus="showCalendarControl(this);" value=""></td>
    <td class="data"><span class="required">Required</span>The date you will stop accepting entries</td>
  </tr>
  <tr>
    <td class="dataLabel">Registration Deadline:</td>
    <td class="data"><input name="contestRegistrationDeadline" type="text" size="20" onfocus="showCalendarControl(this);" value=""></td>
    <td class="data"><span class="required">Required</span>The date the system will automatically close registrations</td>
  </tr>
  <tr>
    <td class="dataLabel">Judging Date:</td>
    <td class="data"><input name="contestDate" type="text" size="20" onfocus="showCalendarControl(this);" value=""></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Judging Date 2:</td>
    <td class="data"><input name="contestDate2" type="text" size="20" onfocus="showCalendarControl(this);" value=""></td>
    <td class="data">&nbsp;</td>
  </tr>
  <tr>
    <td class="dataLabel">Judging Date 3:</td>
    <td class="data"><input name="contestDate3" type="text" size="20" onfocus="showCalendarControl(this);" value=""></td>
    <td class="data">&nbsp;</td>
  </tr>
  <tr>
    <td class="dataLabel">Judging Location:</td>
    <td class="data"><textarea name="contestJudgingLocation" cols="50" rows="6"></textarea></td>
    <td class="data">Provide the street address, city, and zip code</td>
  </tr>
  <tr>
    <td class="dataLabel">Awards Location:</td>
    <td class="data"><textarea name="contestAwardsLocation" cols="50" rows="6"></textarea></td>
    <td class="data">If different from Judging Location</td>
  </tr>
  <tr>
  	<td colspan="3"><h2>Rules</h2></td>
  </tr>
  <tr>
    <td class="dataLabel">Contest Rules:</td>
    <td class="data"><textarea name="contestRules" cols="50" rows="6"></textarea></td>
    <td class="data">Copy and paste if needed</td>
  </tr>
  <tr>
  	<td colspan="3"><h2>Entries</h2></td>
  </tr>
  <tr>
    <td class="dataLabel">Entry Fee:</td>
    <td class="data"><?php echo $row_prefs['prefsCurrency']; ?> <input name="contestEntryFee" type="text" size="5" maxlength="10" value=""></td>
    <td class="data"><span class="required">Required</span>Fee for a single entry (<?php echo $row_prefs['prefsCurrency']; ?>) - please enter a zero (0) for a free entry fee.</td>
  </tr>
  <tr>
    <td class="dataLabel">Categories Accepted:</td>
    <td class="data"><textarea name="contestCategories" cols="50" rows="6"></textarea></td>
    <td class="data">&nbsp;</td>
  </tr>
  <tr>
    <td class="dataLabel">Bottle Acceptance Rules:</td>
    <td class="data"><textarea name="contestBottles" cols="50" rows="6"></textarea></td>
    <td class="data">Indicate the number of bottles, size, color, etc.<br>Copy and paste if needed</td>
  </tr>
  <tr>
    <td class="dataLabel">Shipping Address:</td>
    <td class="data"><textarea name="contestShippingAddress" cols="50" rows="6"></textarea></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Drop Off Locations:</td>
    <td class="data"><textarea name="contestDropOff" cols="50" rows="6"></textarea></td>
    <td class="data">&nbsp;</td>
  </tr>
  <tr>
  	<td colspan="3"><h2>Awards</h2></td>
  </tr>
  <tr>
    <td class="dataLabel">Awards Structure:</td>
    <td class="data"><textarea name="contestAwards" cols="50" rows="6"></textarea></td>
    <td class="data">Indicate places for each category, BOS procedure, qualifying criteria, etc.</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  	<td colspan="2" class="data"><input name="submit" type="submit" value="Submit"></td>
  </tr>
</table>
</form>
