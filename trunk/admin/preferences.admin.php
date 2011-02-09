<form method="post" action="includes/process.inc.php?action=<?php if ($section == "step1") echo "add"; else echo "edit"; ?>&amp;dbTable=preferences&amp;id=1" name="form1">
<?php if ($section != "step1") { ?>
<h2>Preferences</h2>
<p><span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a class="data" href="index.php?section=admin">Back to Admin</a></p>
<?php } ?>
<h3>General</h3>
<table>
  <tr>
  	<td class="dataLabel">Competition Logo Size:</td>
    <td nowrap="nowrap" class="data">
    <select name="prefsCompLogoSize">
    <option value="100" <?php if ($row_prefs['prefsCompLogoSize'] == "100") echo "SELECTED"; ?>>100</option>
    <option value="150" <?php if ($row_prefs['prefsCompLogoSize'] == "150") echo "SELECTED"; ?>>150</option>
    <option value="200" <?php if ($row_prefs['prefsCompLogoSize'] == "200") echo "SELECTED"; ?>>200</option>
    <option value="250" <?php if ($row_prefs['prefsCompLogoSize'] == "250") echo "SELECTED"; ?>>250</option>
    <option value="300" <?php if ($row_prefs['prefsCompLogoSize'] == "300") echo "SELECTED"; ?>>300</option>
    <option value="350" <?php if ($row_prefs['prefsCompLogoSize'] == "350") echo "SELECTED"; ?>>350</option>
    <option value="400" <?php if ($row_prefs['prefsCompLogoSize'] == "400") echo "SELECTED"; ?>>400</option>
    </select> pixels    </td>
    <td class="data">If you upload and display your competition's logo, this is the width, in pixels, that you wish to display it.</td>
  </tr>
  <tr>
    <td class="dataLabel">Winner Display:</td>
    <td nowrap="nowrap" class="data"><input type="radio" name="prefsDisplayWinners" value="Y" id="prefsDisplayWinners_0"  <?php if ($row_prefs['prefsDisplayWinners'] == "Y") echo "CHECKED"; if ($section == "step1") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsDisplayWinners" value="N" id="prefsDisplayWinners_1" <?php if ($row_prefs['prefsDisplayWinners'] == "N") echo "CHECKED"; ?>/> No</td>
  	<td class="data">Indicate whether you would like to display the winners of the competition for each category and Best of Show.</td>
  </tr>
  <tr>
    <td class="dataLabel">Mead BOS:</td>
    <td nowrap="nowrap" class="data"><input type="radio" name="prefsBOSMead" value="Y" id="prefsBOSMead_0"  <?php if ($row_prefs['prefsBOSMead'] == "Y") echo "CHECKED"; if ($section == "step1") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsBOSMead" value="N" id="prefsBOSMead_1" <?php if ($row_prefs['prefsBOSMead'] == "N") echo "CHECKED"; ?>/> No</td>
  	<td class="data">Indicate whether your competition awards a separate Best of Show for meads.</td>
  </tr>
  <tr>
    <td class="dataLabel">Cider BOS:</td>
    <td nowrap="nowrap" class="data"><input type="radio" name="prefsBOSCider" value="Y" id="prefsBOSCider_0"  <?php if ($row_prefs['prefsBOSCider'] == "Y") echo "CHECKED"; if ($section == "step1") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsBOSCider" value="N" id="prefsBOSCider_1" <?php if ($row_prefs['prefsBOSCider'] == "N") echo "CHECKED"; ?>/> No</td>
  	<td class="data">Indicate whether your competition awards a separate Best of Show for ciders.</td>
  </tr>
  <tr>
    <td class="dataLabel">Require Special Ingredients<br />
      or Classic Style:</td>
    <td nowrap="nowrap" class="data"><input type="radio" name="prefsDisplaySpecial" value="Y" id="prefsDisplaySpecial_0"  <?php if ($row_prefs['prefsDisplaySpecial'] == "Y") echo "CHECKED"; if ($section == "step1") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsDisplaySpecial" value="N" id="prefsDisplaySpecial_1" <?php if ($row_prefs['prefsDisplaySpecial'] == "N") echo "CHECKED"; ?>/> No</td>
    <td class="data">Indicate whether you would like to require entrants to specify special ingredients or a classic style for all of your competition's custom styles.</td>
  </tr>
  <tr>
    <td class="dataLabel">Printed Entry Form to Use:</td>
    <td nowrap="nowrap" class="data">
    <select name="prefsEntryForm">
    <option value="B" <?php if ($row_prefs['prefsEntryForm'] == "B") echo " SELECTED"; ?> />BJCP Official</option>
    <option value="M" <?php if ($row_prefs['prefsEntryForm'] == "M") echo " SELECTED"; ?> />Simple Metric</option>
    <option value="U" <?php if ($row_prefs['prefsEntryForm'] == "U") echo " SELECTED"; ?> />Simple U.S.</option>
    </select>
    </td>
  	<td class="data">The BJCP Official form displays U.S. weights and measures.</td>
  </tr>
</table>
<h3>Performance</h3>
<table>
  <tr>
  	<td class="dataLabel">DataTables Record Threshold:</td>
    <td nowrap="nowrap" class="data"><input name="prefsRecordLimit" type="text" value="<?php if ($section == "step1") echo "300"; else echo $row_prefs['prefsRecordLimit']; ?>" size="5" maxlength="11" /></td>
    <td class="data">The threshold of records for BCOE to utilize <a href="http://www.datatables.net/" target="_blank">DataTables</a> for paging and sorting,  a Javascript-enabled function that does not require page refreshes to sort or page through <em>all </em>records - the higher the threshold, the greater the possiblity for performance issues because <em>all</em> records are loaded at once.  Generally, the default value will work for most installations.</td>
  </tr>
  <tr>
  	<td class="dataLabel">Number of Records to Display Per Page:</td>
    <td nowrap="nowrap" class="data"><input name="prefsRecordPaging" type="text" value="<?php if ($section == "step1") echo "30"; else echo $row_prefs['prefsRecordPaging']; ?>" size="5" maxlength="11" /></td>
    <td class="data">The number of records  displayed per page when viewing lists (e.g., when viewing the entries or participants list). Generally, the default value will work for most installations.</td>
  </tr>
</table>
<h3>Measurements</h3>
<table>
  <tr>
  	<td class="dataLabel">Temperature:</td>
    <td class="data">
    <select name="prefsTemp">
    <option value="Fahrenheit" <?php if ($row_prefs['prefsTemp'] == "Fahrenheit") echo "SELECTED"; ?>>Fahrenheit</option>
    <option value="Celsius" <?php if ($row_prefs['prefsTemp'] == "Celsius") echo "SELECTED"; ?>>Celsius</option>
    </select>    </td>
    </tr><tr>
    <td class="dataLabel">Weight (Small):</td>
    <td class="data">
    <select name="prefsWeight1">
    <option value="ounces" <?php if ($row_prefs['prefsWeight1'] == "ounces") echo "SELECTED"; ?>>ounces</option>
    <option value="grams" <?php if ($row_prefs['prefsWeight1'] == "grams") echo "SELECTED"; ?>>grams</option>
    </select>    </td>
    </tr>
  <tr>
    <td class="dataLabel">Weight (Large):</td>
    <td class="data">
    <select name="prefsWeight2">
    <option value="pounds" <?php if ($row_prefs['prefsWeight2'] == "ounces") echo "SELECTED"; ?>>pounds</option>
    <option value="kilograms" <?php if ($row_prefs['prefsWeight2'] == "kilograms") echo "SELECTED"; ?>>kilograms</option>
    </select>    </td>
    </tr>
  <tr>
    <td class="dataLabel">Liquid (Small):</td>
    <td class="data">
    <select name="prefsLiquid1">
    <option value="ounces" <?php if ($row_prefs['prefsLiquid1'] == "ounces") echo "SELECTED"; ?>>ounces</option>
    <option value="millilitres" <?php if ($row_prefs['prefsLiquid1'] == "millilitres") echo "SELECTED"; ?>>millilitres</option>
    </select>    </tr>
  <tr>
    <td class="dataLabel">Liquid (Large):</td>
    <td class="data">
    <select name="prefsLiquid2">
    <option value="gallons" <?php if ($row_prefs['prefsLiquid1'] == "gallons") echo "SELECTED"; ?>>gallons</option>
    <option value="litres" <?php if ($row_prefs['prefsLiquid1'] == "litres") echo "SELECTED"; ?>>litres</option>
    </select>    </td>
    </tr>
</table>
<h3>Currency and Payment</h3>
<table>
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
      <td class="data"><input type="radio" name="prefsCash" value="Y" id="prefsCash_0"  <?php if ($row_prefs['prefsCash'] == "Y") echo "CHECKED";  if ($section == "step1") echo "CHECKED"; ?> /> 
      Yes&nbsp;&nbsp;
        <input type="radio" name="prefsCash" value="N" id="prefsCash_1" <?php if ($row_prefs['prefsCash'] == "N") echo "CHECKED"; ?>/> 
        No</td>
      <td class="data">Do you want to accept entry payments in cash?</td>
    </tr>
    <tr>
      <td class="dataLabel">Checks for Payment:</td>
      <td class="data"><input type="radio" name="prefsCheck" value="Y" id="prefsCheck_0"  <?php if ($row_prefs['prefsCheck'] == "Y") echo "CHECKED"; if ($section == "step1") echo "CHECKED"; ?> /> 
        Yes&nbsp;&nbsp;
        <input type="radio" name="prefsCheck" value="N" id="prefsCheck_1" <?php if ($row_prefs['prefsCheck'] == "N") echo "CHECKED"; ?>/> 
        No</td>
      <td class="data">Do you want to accept checks for entry payments?</td>
    </tr>
  <tr>
    <td class="dataLabel">Checks Payee:</td>
    <td class="data"><input name="prefsCheckPayee" type="text" size="35" maxlength="255" value="<?php echo $row_prefs['prefsCheckPayee']; ?>"></td>
    <td class="data">Indicate who the entry checks should be made out to.</td>
  </tr>
  <tr>
  	<td class="dataLabel">PayPal for Payment:</td>
   	<td class="data"><input type="radio" name="prefsPaypal" value="Y" id="prefsPaypal_0"  <?php if ($row_prefs['prefsPaypal'] == "Y") echo "CHECKED";  if ($section == "step1") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsPaypal" value="N" id="prefsPaypal_1" <?php if ($row_prefs['prefsPaypal'] == "N") echo "CHECKED"; ?>/> No</td>
  	<td class="data">Do you want to accept credit card payments via PayPal?</td>
  </tr>
  <tr>
    <td class="dataLabel">PayPal Account Email:</td>
    <td class="data"><input name="prefsPaypalAccount" type="text" size="35" maxlength="255" value="<?php echo $row_prefs['prefsPaypalAccount']; ?>"></td>
    <td class="data">Indicate the email address associated with your PayPal account.<br />Please note that you need to have a verified bank account with PayPal to accept credit cards for payment. More information is contained in the &quot;Merchant Services&quot; area of your PayPal account.</td>
  </tr>
  <tr>
  	<td class="dataLabel">Entrant Pays PayPal Fees:</td>
   	<td class="data"><input type="radio" name="prefsTransFee" value="Y" id="prefsTransFee_0"  <?php if ($row_prefs['prefsTransFee'] == "Y") echo "CHECKED"; if ($section == "step1") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsTransFee" value="N" id="prefsTransFee_1" <?php if ($row_prefs['prefsTransFee'] == "N") echo "CHECKED"; ?>/> No</td>
  	<td class="data">Do you want participants paying via PayPal to also pay the transaction fees?<br />PayPal charges 2.9% + $0.30 USD per transaction. Checking "Yes" indicates that the transaction fees will be added to the participant's total.</td>
  </tr>
</table>
<h3>Sponsors</h3>
<table>
  <tr>
  	<td class="dataLabel">Sponsor Display:</td>
   	<td nowrap="nowrap" class="data"><input type="radio" name="prefsSponsors" value="Y" id="prefsSponsors_0"  <?php if ($row_prefs['prefsSponsors'] == "Y") echo "CHECKED"; if ($section == "step1") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsSponsors" value="N" id="prefsSponsors_1" <?php if ($row_prefs['prefsSponsors'] == "N") echo "CHECKED"; ?>/> No</td>
  	<td class="data">Do you want to display the competition sponsors?</td>
  </tr>
  <tr>
  	<td class="dataLabel">Sponsor Logos:</td>
   	<td nowrap="nowrap" class="data"><input type="radio" name="prefsSponsorLogos" value="Y" id="prefsSponsorLogos_0"  <?php if ($row_prefs['prefsSponsorLogos'] == "Y") echo "CHECKED"; if ($section == "step1") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsSponsorLogos" value="N" id="prefsSponsorLogos_1" <?php if ($row_prefs['prefsSponsorLogos'] == "N") echo "CHECKED"; ?>/> No</td>
  	<td class="data">Do you want to display the competition sponsors' individual logos (you will need to collect and upload all logos to your installation of BCOE via the <a href="index.php?section=admin&amp;go=sponsors">sponsor administration page</a>)?</td>
  </tr>
  <tr>
  	<td class="dataLabel">Sponsor Logo Size:</td>
    <td nowrap="nowrap" class="data">
    <select name="prefsSponsorLogoSize">
    <option value="100" <?php if ($row_prefs['prefsSponsorLogoSize'] == "100") echo "SELECTED"; ?>>100</option>
    <option value="150" <?php if ($row_prefs['prefsSponsorLogoSize'] == "150") echo "SELECTED"; ?>>150</option>
    <option value="200" <?php if ($row_prefs['prefsSponsorLogoSize'] == "200") echo "SELECTED"; ?>>200</option>
    <option value="250" <?php if ($row_prefs['prefsSponsorLogoSize'] == "250") echo "SELECTED"; ?>>250</option>
    </select> pixels
    </td>
    <td class="data">This is the default size, in pixels, that will each sponsor's logo will display on the <em>sponsors</em> page. Generally, the default setting of 150 is sufficient.</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  	<td colspan="2" class="data"><input name="submit" type="submit" class="button" value="Set Preferences"></td>
  </tr>
</table>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER']); ?>">
</form>