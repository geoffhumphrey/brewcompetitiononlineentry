<form method="post" action="includes/process.inc.php?action=edit&dbTable=preferences&id=1" name="form1">
<table>
  <tr>
  	<td colspan="3"><a href="index.php?section=admin">&laquo; Back to Admin</a></td>
  </tr>
  <tr>
  	<td colspan="3"><h2>Measurements</h2></td>
  </tr>
  <tr>
  	<td class="dataLabel">Temperature</td>
    <td class="data">
    <select name="prefsTemp">
    <option value="Fahrenheit" <?php if ($row_prefs['prefsTemp'] == "Fahrenheit") echo "SELECTED"; ?>>Fahrenheit</option>
    <option value="Celsius" <?php if ($row_prefs['prefsTemp'] == "Celsius") echo "SELECTED"; ?>>Celsius</option>
    </select>
    </td>
    <td class="data">&nbsp;</td>
  </tr>
  <tr>
  <tr>
    <td class="dataLabel">Weight (Small):</td>
    <td class="data">
    <select name="prefsWeight1">
    <option value="ounces" <?php if ($row_prefs['prefsWeight1'] == "ounces") echo "SELECTED"; ?>>ounces</option>
    <option value="grams" <?php if ($row_prefs['prefsWeight1'] == "grams") echo "SELECTED"; ?>>grams</option>
    </select>
    </td>
    <td class="data">&nbsp;</td>
  </tr>
  <tr>
    <td class="dataLabel">Weight (Large):</td>
    <td class="data">
    <select name="prefsWeight2">
    <option value="pounds" <?php if ($row_prefs['prefsWeight2'] == "ounces") echo "SELECTED"; ?>>pounds</option>
    <option value="kilograms" <?php if ($row_prefs['prefsWeight2'] == "kilograms") echo "SELECTED"; ?>>kilograms</option>
    </select>
    </td>
    <td class="data">&nbsp;</td>
  </tr>
  <tr>
    <td class="dataLabel">Liquid (Small):</td>
    <td class="data">
    <select name="prefsLiquid1">
    <option value="ounces" <?php if ($row_prefs['prefsLiquid1'] == "ounces") echo "SELECTED"; ?>>ounces</option>
    <option value="millilitres" <?php if ($row_prefs['prefsLiquid1'] == "millilitres") echo "SELECTED"; ?>>millilitres</option>
    </select>
    <td class="data">&nbsp;</td>
  </tr>
  <tr>
    <td class="dataLabel">Liquid (Large):</td>
    <td class="data">
    <select name="prefsLiquid2">
    <option value="gallons" <?php if ($row_prefs['prefsLiquid1'] == "gallons") echo "SELECTED"; ?>>gallons</option>
    <option value="litres" <?php if ($row_prefs['prefsLiquid1'] == "litres") echo "SELECTED"; ?>>litres</option>
    </select>
    </td>
    <td class="data">&nbsp;</td>
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
    </select>
    </td>
    <td class="data">&nbsp;</td>
  <tr>
  	<td class="dataLabel">PayPal for Payment:</td>
   	<td class="data"><input type="radio" name="prefsPaypal" value="Y" id="prefsPaypal_0"  <?php if ($row_prefs['prefsPaypal'] == "Y") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsPaypal" value="N" id="prefsPaypal_1" <?php if ($row_prefs['prefsPaypal'] == "N") echo "CHECKED"; ?>/> No</td>
  	<td class="data">Do you want to accept credit card payments via PayPal?</td>
  </tr>
  <tr>
    <td class="dataLabel">PayPal Account Email:</td>
    <td class="data"><input name="prefsPaypalAccount" type="text" size="35" maxlength="255" value="<?php echo $row_prefs['prefsPaypalAccount']; ?>"></td>
    <td class="data">Indicate the email address associated with your PayPal account.<br />
    Please note that you need to have a verified bank account with PayPal to accept credit cards for payment. More information is contained in the &quot;Merchant Services&quot; area of your PayPal account.</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  	<td colspan="2" class="data"><input name="submit" type="submit" value="Update Preferences"></td>
  </tr>
</table>
</form>