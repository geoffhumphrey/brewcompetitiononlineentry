<?php if ($go == "invalid") { 

$email = $_GET['brewerEmail'];

}
?>

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
	<div id="header-inner"><h1>Set Up Step 4: Enter the Admin User's Info</h1></div>
</div>
<p>The information here is strictly for record-keeping purposes. It will not be made public.</p>
<form action="includes/process.inc.php?section=setup&action=add&dbTable=brewer" method="POST" name="form1" id="form1" onSubmit="return CheckRequiredFields()">
<input name="brewerSteward" type="hidden" value="N" />
<input name="brewerEmail" type="hidden" value="<?php echo $go; ?>" />
<table class="dataTable">
<tr>
	<td width="5%" nowrap class="dataLabel">First Name:</td>
      <td width="5%" nowrap class="data"><input type="text" id="brewerFirstName" name="brewerFirstName" value="" size="32" maxlength="20"></td>
      <td class="data"><span class="required">Required</span></td>
    </tr>
<tr>
      <td width="5%" nowrap class="dataLabel">Last Name:</td>
      <td width="5%" nowrap class="data"><input type="text" name="brewerLastName" value="" size="32"></td>
      <td class="data"><span class="required">Required</span></td>
</tr>

<tr>
      <td width="5%" nowrap class="dataLabel">Street Address:</td>
      <td width="5%" nowrap class="data"><input type="text" name="brewerAddress" value="" size="32"></td>
      <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
      <td width="5%" nowrap class="dataLabel">City:</td>
      <td width="5%" nowrap class="data"><input type="text" name="brewerCity" value="" size="32"></td>
      <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
      <td width="5%" nowrap class="dataLabel">State/Country:</td>
      <td width="5%" nowrap class="data"><input type="text" name="brewerState" value="" size="32"></td>
      <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
      <td width="5%" nowrap class="dataLabel">Zip/Postal Code:</td>
      <td width="5%" nowrap class="data"><input type="text" name="brewerZip" value="" size="32"></td>
      <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
      <td width="5%" nowrap class="dataLabel">Phone 1:</td>
      <td width="5%" nowrap class="data"><input type="text" name="brewerPhone1" value="" size="32"></td>
      <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
      <td width="5%" nowrap class="dataLabel">Phone 2:</td>
      <td colspan="2" nowrap class="data"><input type="text" name="brewerPhone2" value="" size="32"></td>
    </tr>
<tr>
      <td width="5%" nowrap class="dataLabel">Club Name:</td>
      <td colspan="2" nowrap class="data"><input type="text" name="brewerClubs" value="<?php echo $row_contest_info['contestHost']; ?>" size="60" maxlength="200"></td>
</tr>
<tr>
      <td width="5%" nowrap class="dataLabel">Judging:</td>
      <td colspan="2" nowrap class="data">Is this user willing and qualified to judge in this competition?<span class="data">
      <input type="radio" name="brewerJudge" value="Y" id="brewerJudge_0" /> Yes&nbsp;&nbsp;<input type="radio" name="brewerJudge" value="N" id="brewerJudge_1" <?php if ($judge == "N") echo "CHECKED"; if ($go == "default") echo "CHECKED"; ?>/> No</span></td>
</tr>
      <tr><td width="5%" nowrap class="dataLabel">BJCP ID:</td>
      <td colspan="2" nowrap class="data"><input name="brewerJudgeID" id="brewerJudgeID" type="text" size="10" value="" /></td>
    </tr>
<tr>
      <td width="5%" nowrap class="dataLabel">BJCP Rank:</td>
      <td colspan="2" nowrap class="data"><select name="brewerJudgeRank">
        <option value ""></option>
        <option value="Experienced">Experienced</option>
        <option value="Recognized">Recognized</option>
        <option value="Certified">Certified</option>
        <option value="National">National</option>
        <option value="Master">Master</option>
        <option value="Grand Master">Grand Master</option>
        <option value="Honorary Master">Honorary Master</option>
        <option value="Honorary Grand Master">Honorary Grand Master</option>
      </select>
      </td>
</tr>
<tr>
      <td width="5%" nowrap class="dataLabel">Preferred Categories<br>
      to Judge:</td>
      <td colspan="2" nowrap class="data"><input name="brewerJudgeLikes" id="brewerJudgeLikes" type="text" size="50" value=""/><br />Please indicate the <strong>both</strong> BJCP category numbers and subcategory letters.</td>
</tr>
<tr>
      <td width="5%" nowrap class="dataLabel">Preferred Categories<br>
      NOT to Judge:</td>
      <td colspan="2" nowrap class="data"><input name="brewerJudgeDislikes" id="brewerJudgeDislikes" type="text" size="50" value="" /><br />Please indicate the <strong>both</strong> BJCP category numbers and subcategory letters.</td>
</tr>
<tr>
	  <td width="5%" nowrap>&nbsp;</td>
      <td colspan="2" nowrap class="data"><input name="submit" type="submit" value="Submit" /></td>
</tr>
</table>
</form>