<div id="header">	
	<div id="header-inner"><h1>Set Up Step 1: Define Preferences</h1></div>
</div>
<p>Before you can start collecting your entries for your contest, your installation needs to be set up. This is the first of four steps that will gather the necessary information to get your contest entry site up and running.</p>
<form method="post" action="includes/process.inc.php?action=add&dbTable=preferences&id=1" name="form1" onSubmit="return CheckRequiredFields()">
<table>
  <tr>
  	<td colspan="3"><h2>Measurements</h2></td>
  </tr>
  <tr>
  	<td class="dataLabel">Temperature</td>
    <td class="data">
    <select name="prefsTemp">
    <option value="Fahrenheit">Fahrenheit</option>
    <option value="Celsius">Celsius</option>
    </select>
    </td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
  <tr>
    <td class="dataLabel">Weight (Small):</td>
    <td class="data">
    <select name="prefsWeight1">
    <option value="ounces">ounces</option>
    <option value="grams">grams</option>
    </select>
    </td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Weight (Large):</td>
    <td class="data">
    <select name="prefsWeight2">
    <option value="pounds">pounds</option>
    <option value="kilograms">kilograms</option>
    </select>
    </td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Liquid (Small):</td>
    <td class="data">
    <select name="prefsLiquid1">
    <option value="ounces">ounces</option>
    <option value="millilitres">milliliters</option>
    </select>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Liquid (Large):</td>
    <td class="data">
    <select name="prefsLiquid2">
    <option value="gallons">gallons</option>
    <option value="litres">liters</option>
    </select>
    </td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
  	<td colspan="3"><h2>Currency and Payment</h2></td>
  </tr>
  	<td class="dataLabel">Currency</td>
    <td class="data">
    <select name="prefsCurrency">
    <option value="$"  <?php if ($row_prefs['prefsCurrency'] == "$") echo "SELECTED"; ?>>$ - Dollar (USD)</option>
    <option value="&amp;pound;" <?php if ($row_prefs['prefsCurrency'] == "&pound;") echo "SELECTED"; ?>>&pound; - Pound Sterling (GBP)</option>
    <option value="&amp;euro;" <?php if ($row_prefs['prefsCurrency'] == "&euro;") echo "SELECTED"; ?>>&euro; - Euro (EUR)</option>
    <option value="&amp;yen;" <?php if ($row_prefs['prefsCurrency'] == "&yen;") echo "SELECTED"; ?>>&yen; - Yen (JPY)</option>
    </select>    </td>
    <td class="data">&nbsp;</td>
    <tr>
      <td class="dataLabel">Cash for Payment:</td>
      <td class="data"><input type="radio" name="prefsCash" value="Y" id="prefsCash_0"  <?php if ($row_prefs['prefsCash'] == "Y") echo "CHECKED"; ?> />Yes&nbsp;&nbsp;
        <input type="radio" name="prefsCash" value="N" id="prefsCash_1" <?php if ($row_prefs['prefsCash'] == "N") echo "CHECKED"; ?>/>No</td>
      <td class="data">Do you want to accept entry payments in cash?</td>
    </tr>
    <tr>
      <td class="dataLabel">Checks for Payment:</td>
      <td class="data"><input type="radio" name="prefsCheck" value="Y" id="prefsCheck_0"  <?php if ($row_prefs['prefsCheck'] == "Y") echo "CHECKED"; ?> />Yes&nbsp;&nbsp;
        <input type="radio" name="prefsCheck" value="N" id="prefsCheck_1" <?php if ($row_prefs['prefsCheck'] == "N") echo "CHECKED"; ?>/>No</td>
      <td class="data">Do you want to accept checks for entry payments?</td>
    </tr>
  <tr>
    <td class="dataLabel">Checks Payee:</td>
    <td class="data"><input name="prefsCheckPayee" type="text" size="35" maxlength="255" value="<?php echo $row_prefs['prefsCheckPayee']; ?>"></td>
    <td class="data">Indicate who the entry checks should be made out to.</td>
  </tr>
    <tr>
  	<td class="dataLabel">PayPal for Payment:</td>
   	<td class="data"><input type="radio" name="prefsPaypal" value="Y" id="prefsPaypal_0"  <?php if ($row_prefs['prefsPaypal'] == "Y") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsPaypal" value="N" id="prefsPaypal_1" <?php if ($row_prefs['prefsPaypal'] == "N") echo "CHECKED"; ?>/> No</td>
  	<td class="data">Do you want to accept credit card payments via PayPal?</td>
  </tr>
  <tr>
    <td class="dataLabel">PayPal Account Email:</td>
    <td class="data"><input name="prefsPaypalAccount" type="text" size="35" maxlength="255" value="<?php echo $row_prefs['prefsPaypalAccount']; ?>"></td>
    <td class="data">Indicate the email address associated with your PayPal account.<br />Please note that you need to have a verified bank account with PayPal to accept credit cards for payment. More information is contained in the &quot;Merchant Services&quot; area of your PayPal account.</td>
  </tr>
  <tr>
  	<td class="dataLabel">Entrant Pays PayPal Fees:</td>
   	<td class="data"><input type="radio" name="prefsTransFee" value="Y" id="prefsTransFee_0"  <?php if ($row_prefs['prefsTransFee'] == "Y") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsTransFee" value="N" id="prefsTransFee_1" <?php if ($row_prefs['prefsTransFee'] == "N") echo "CHECKED"; ?>/> No</td>
  	<td class="data">Do you want participants paying via PayPal to also pay the transaction fees?<br />PayPal charges 2.9% + $0.30 USD per transaction. Checking "Yes" indicates that the transaction fees will be added to the participant's total.</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  	<td colspan="2" class="data"><input name="submit" type="submit" value="Submit"></td>
  </tr>
</table>
</form>