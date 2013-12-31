<?php 
include (DB.'styles.db.php');
?>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/usable_forms.js"></script>
<form method="post" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php if ($section == "step3") echo "setup"; else echo $section; ?>&amp;action=<?php if ($section == "step3") echo "add"; else echo "edit"; ?>&amp;dbTable=<?php echo $preferences_db_table; ?>&amp;id=1" name="form1">
<?php if ($section != "step3") { ?>
<h2>Site Preferences</h2>
<div class="adminSubNavContainer">
  	<span class="adminSubNav">
		<span class="icon"><img src="<?php echo $base_url; ?>images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>index.php?section=admin">Back to Admin Dashboard</a>
	</span>
</div>
<?php } ?>
<p><input name="submit" type="submit" class="button" value="Set Preferences"></p>
<input type="hidden" name="prefsGoogle" value="N" />
<input type="hidden" name="prefsGoogleAccount" value="" />
<h3>General</h3>
<table>
  <tr>
  	<td class="dataLabel">Competition Logo Size:</td>
    <td nowrap="nowrap" class="data">
    <select name="prefsCompLogoSize">
    <option value="100" <?php if ($_SESSION['prefsCompLogoSize'] == "100") echo "SELECTED"; ?>>100</option>
    <option value="150" <?php if ($_SESSION['prefsCompLogoSize'] == "150") echo "SELECTED"; ?>>150</option>
    <option value="200" <?php if ($_SESSION['prefsCompLogoSize'] == "200") echo "SELECTED"; ?>>200</option>
    <option value="250" <?php if ($_SESSION['prefsCompLogoSize'] == "250") echo "SELECTED"; ?>>250</option>
    <option value="300" <?php if (($section == "step3") || ($_SESSION['prefsCompLogoSize'] == "300")) echo "SELECTED"; ?>>300</option>
    <option value="350" <?php if ($_SESSION['prefsCompLogoSize'] == "350") echo "SELECTED"; ?>>350</option>
    <option value="400" <?php if ($_SESSION['prefsCompLogoSize'] == "400") echo "SELECTED"; ?>>400</option>
    </select> pixels    </td>
    <td class="data">If you upload and display your competition's logo, this is the width, in pixels, that you wish to display it.</td>
  </tr>
  <tr>
    <td class="dataLabel">Winner Display:</td>
    <td nowrap="nowrap" class="data"><input type="radio" name="prefsDisplayWinners" value="Y" id="prefsDisplayWinners_0"  <?php if ($_SESSION['prefsDisplayWinners'] == "Y") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsDisplayWinners" value="N" id="prefsDisplayWinners_1" <?php if ($_SESSION['prefsDisplayWinners'] == "N") echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?>/> No</td>
  	<td class="data">Indicate if the winners of the competition for each category and Best of Show Style Type will be displayed.</td>
  </tr>
  <tr>
  	<td class="dataLabel">Winner Display Delay:</td>
    <td nowrap="nowrap" class="data"><input name="prefsWinnerDelay" type="text" value="<?php if ($section == "step3") echo "8"; else echo $_SESSION['prefsWinnerDelay']; ?>" size="3" maxlength="11" /> 
    hours</td>
    <td class="data">Hours to delay displaying winners after the <em>start</em> time of the final judging session.</td>
  </tr>
  <tr>
    <td class="dataLabel">Winner Place Distribution Method:</td>
    <td nowrap="nowrap" class="data">
    <select name="prefsWinnerMethod">
    <option value="0" <?php if (($section == "step3") || ($_SESSION['prefsWinnerMethod'] == "0")) echo "SELECTED"; ?>>By Table</option>
    <option value="1" <?php if ($_SESSION['prefsWinnerMethod'] == "1") echo "SELECTED"; ?>>By Style Category</option>
    <option value="2" <?php if ($_SESSION['prefsWinnerMethod'] == "2") echo "SELECTED"; ?>>By Style Sub-Category</option>
    </select>
    </td>
    <td class="data">How the competition will award places for winning entries.</td>
  </tr>
  <tr>
      <td class="dataLabel">Contact Form:</td>
      <td nowrap="nowrap" class="data"><input type="radio" name="prefsContact" value="Y" id="prefsContact_0"  <?php if ($_SESSION['prefsContact'] == "Y") echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsContact" value="N" id="prefsContact_1" <?php if ($_SESSION['prefsContact'] == "N") echo "CHECKED"; ?>/> No</td>
      <td class="data">Enable or disable your installation's contact form. This may be necessary if your site's server does not support PHP's <a href="http://php.net/manual/en/function.mail.php" target="_blank">mail()</a> function. Admins should test the form before disabling as the form is the more secure option.</td>
  </tr>
  <tr>
    <td class="dataLabel">Site Theme:</td>
    <td nowrap="nowrap" class="data">
    <select name="prefsTheme">
    <?php do { ?>
    <option value="<?php echo $row_themes['themeFileName']; ?>" <?php if ($_SESSION['prefsTheme'] ==  $row_themes['themeFileName']) echo " SELECTED"; ?> /><?php echo  $row_themes['themeTitle']; ?></option>
    <?php } while ($row_themes = mysql_fetch_assoc($themes)); ?>
    </select>
    </td>
  	<td class="data">&nbsp;</td>
  </tr>
  <?php if (!HOSTED) { ?>
  <tr>
    <td class="dataLabel">Use Search Engine Friendly URLs:</td>
    <td nowrap="nowrap" class="data"><input type="radio" name="prefsSEF" value="Y" id="prefsSEF_0"  <?php if ($_SESSION['prefsSEF'] == "Y") echo "CHECKED"; ?> />Yes&nbsp;&nbsp;<input type="radio" name="prefsSEF" value="N" id="prefsSEF_1" <?php if ($_SESSION['prefsSEF'] == "N") echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?>/>No</td>
    <td class="data">Generally, "Yes" is good for most installations. However, if your installation is experiencing multiple &quot;Page Not Found&quot; errors (404), switch the following to &quot;No&quot; to turn off Search Engine Friendly (SEF) URLs. <br /><em>*If you enable this and receive 404 errors, <?php if ($section == "step3") echo "AFTER SETUP HAS BEEN COMPLETED, "; ?>navigate to the login screen at <a href="<?php echo $base_url; ?>index.php?section=login" target="_blank"><?php echo $base_url; ?>index.php?section=login</a> to log back in and "turn off" this feature.</em></td>
    </tr>
 
  <tr>
    <td class="dataLabel">Use Custom Modules:</td>
    <td nowrap="nowrap" class="data"><input type="radio" name="prefsUseMods" value="Y" id="prefsUseMods_0"  <?php if ($_SESSION['prefsUseMods'] == "Y") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsUseMods" value="N" id="prefsUseMods_1" <?php if ($_SESSION['prefsUseMods'] == "N") echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?>/> No</td>
  	<td class="data"><strong>FOR ADVANCED USERS.</strong> Utilize the ability to add custom modules that extend BCOE&amp;M's core functionality.</td>
  </tr>
  <?php } else { ?>
  <input type="hidden" name="prefsSEF" value="Y" />
  <input type="hidden" name="prefsUseMods" value="N" />
  <?php } ?>
</table>
<h3>Entries</h3>
<table>
  <tr>
    <td class="dataLabel">Printed Entry Form to Use:</td>
    <td nowrap="nowrap" class="data">
    <select name="prefsEntryForm">
        <option value="B" <?php if (($section == "step3") || ($_SESSION['prefsEntryForm'] == "B")) echo " SELECTED"; ?> />BJCP Official</option>
        <option value="N" <?php if ($_SESSION['prefsEntryForm'] == "N") echo " SELECTED"; ?> /><?php if (NHC) echo "NHC - 1 Page with Barcode"; else echo "BJCP Official - With Barcode"; ?></option>
        <option value="M" <?php if ($_SESSION['prefsEntryForm'] == "M") echo " SELECTED"; ?> />Simple Metric</option>
        <option value="U" <?php if ($_SESSION['prefsEntryForm'] == "U") echo " SELECTED"; ?> />Simple U.S.</option>
    </select>
    </td>
  	<td class="data">The BJCP Official form displays U.S. weights and measures. <?php if (!NHC) { ?>The<em> BJCP Official - With Barcode</em> option displays the official BJCP recipe form with four bottle labels that feature a scannable barcode. This option is intended to be used with the Judging Number Barcode Labels and the Judging Number Round Labels <a href="http://www.brewcompetition.com/bottle-labels" target="_blank"><strong>available for download at brewcompetition.com</strong></a>. BCOE&amp;M utilizes the&nbsp;<strong><a href="http://en.wikipedia.org/wiki/Code_39" target="_blank">Code 39 specification</a>&nbsp;to generate all barcodes</strong>. Please make sure your scanner recognizes this type of barcode&nbsp;<em>before</em>&nbsp;implementing in your competition.  	    <?php } ?></td>
  </tr>
  <tr>
  	<td class="dataLabel">Character Limit for Special Ingredients:</td>
    <td nowrap="nowrap" class="data">
    <select name="prefsSpecialCharLimit" />
    <?php for ($i=25; $i <= 255; $i+=5) { ?>
    	<option value="<?php echo $i; ?>" <?php if (($section == "step3") && ($i == "50")) echo "SELECTED"; elseif ($row_limits['prefsSpecialCharLimit'] == $i) echo "SELECTED"; ?>><?php echo $i; ?></option>
    <?php } ?>
    </select>
    </td>
    <td class="data">Limit of characters allowed for the Special Ingredients section when adding an entry. 50 characters is the maximum recommended when utilizing the &ldquo;Bottle Labels with Special Ingredients&rdquo; report (Avery 5160 labels).</td>
  </tr>
  <tr>
  	<td class="dataLabel">Total Entry Limit:</td>
    <td nowrap="nowrap" class="data"><input name="prefsEntryLimit" type="text" value="<?php echo $row_limits['prefsEntryLimit']; ?>" size="5" maxlength="6" /></td>
    <td class="data">Limit of entries you will accept in the competition. Leave blank if no limit.</td>
  </tr>
  <tr>
  	<td class="dataLabel">Entry Limit Per Participant:</td>
    <td nowrap="nowrap" class="data">
    <select name="prefsUserEntryLimit" />
    	<option value="" rel="none" <?php ($row_limits['prefsUserEntryLimit'] == ""); echo "SELECTED"; ?>></option>
    <?php for ($i=1; $i <= 100; $i++) { ?>
    	<option value="<?php echo $i; ?>" <?php if ($row_limits['prefsUserEntryLimit'] == $i) echo "SELECTED"; ?>><?php echo $i; ?></option>
    <?php } ?>
    </select>
    </td>
    <td class="data">Limit of entries that each participant can enter. Leave blank if no limit.</td>
  </tr>
  <tr>
  	<td class="dataLabel">Entry Limit Per Sub-category:</td>
    <td nowrap="nowrap" class="data">
    <select name="prefsUserSubCatLimit" />
    	<option value="" rel="none" <?php ($row_limits['prefsUserSubCatLimit'] == ""); echo "SELECTED"; ?>></option>
    <?php for ($i=1; $i <= 100; $i++) { ?>
    	<option value="<?php echo $i; ?>" rel="user_entry_limit" <?php if ($row_limits['prefsUserSubCatLimit'] == $i) echo "SELECTED"; ?>><?php echo $i; ?></option>
    <?php } ?>
    </select>
    </td>
    <td class="data">Limit of entries that each participant can enter into a single sub-category. Leave blank if no limit.</td>
  </tr>
  <tr rel="user_entry_limit">
    <td class="dataLabel">Exceptions to Entry Limit<br />Per Sub-category:</td>
    <td class="data" colspan="2">
    <table class="dataTableCompact">
    <?php $endRow = 0; $columns = 3; $hloopRow1 = 0;
	do {
    	if (($endRow == 0) && ($hloopRow1++ != 0)) echo "<tr>";
		if ($row_styles['id'] != "") {
    ?>
            	<td width="1%"><input name="prefsUSCLEx[]" type="checkbox" value="<?php echo $row_styles['id']; ?>" <?php $a = explode(",", $row_limits['prefsUSCLEx']); $b = $row_styles['id']; foreach ($a as $value) { if ($value == $b) echo "CHECKED"; } ?>></td>
                <td width="1%"><?php echo ltrim($row_styles['brewStyleGroup'], "0").$row_styles['brewStyleNum'].":"; ?></td>
                <td><?php echo $row_styles['brewStyle']; ?></td>
            <?php  }
			
			$endRow++;
	if ($endRow >= $columns) {
  	?>
  </tr>
  <?php
 	$endRow = 0;
  		}
	} while ($row_styles = mysql_fetch_assoc($styles));
	if ($endRow != 0) {
	while ($endRow < $columns) {
    echo("<td>&nbsp;</td>");
    $endRow++;
	}
	echo("</tr>");
	}
	?>
        </table>
    </td>
  </tr>
  <tr rel="user_entry_limit">
  	<td class="dataLabel">Entry Limit For <em>Excepted</em><br />Sub-categories:</td>
    <td nowrap="nowrap" class="data">
    <select name="prefsUSCLExLimit" />
    	<option value="" rel="none" <?php ($row_limits['prefsUSCLExLimit'] == ""); echo "SELECTED"; ?>></option>
    <?php for ($i=1; $i <= 100; $i++) { ?>
    	<option value="<?php echo $i; ?>" <?php if ($row_limits['prefsUSCLExLimit'] == $i) echo "SELECTED"; ?>><?php echo $i; ?></option>
    <?php } ?>
    </select>
    </td>
    <td class="data">Limit of entries that each participant can enter into one of the above sub-categories that <em>have been checked</em>. Leave blank if no limit <strong>for the sub-categories that have been checked above</strong>.</td>
  </tr>
  <tr>
    <td class="dataLabel">Hide Entry Recipe Section:</td>
    <td nowrap="nowrap" class="data"><input type="radio" name="prefsHideRecipe" value="Y" id="prefsHideRecipe_0"  <?php if ($_SESSION['prefsHideRecipe'] == "Y") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsHideRecipe" value="N" id="prefsHideRecipe_1" <?php if ($_SESSION['prefsHideRecipe'] == "N") echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?>/> No</td>
  	<td class="data">Indicate if the recipe section (optional information such as malt, yeast, etc.) on the Add Entry or Edit Entry screens will be displayed. If enabled, the BeerXML Import function will not be available.</td>
  </tr>
</table>
<h3>Performance</h3>
<table>
  <tr>
  	<td class="dataLabel">Number of Records to Display Per Page:</td>
    <td nowrap="nowrap" class="data">
    <input type="hidden" name="prefsRecordLimit" id="prefsRecordLimit" value="9999" />
    <input name="prefsRecordPaging" type="text" value="<?php if ($section == "step3") echo "150"; else echo $_SESSION['prefsRecordPaging']; ?>" size="5" maxlength="11" /></td>
    <td class="data">The number of records  displayed per page when viewing lists (e.g., when viewing the entries or participants list). Generally, the default value will work for most installations.</td>
  </tr>
</table>
<h3>Localization</h3>
<table>
  <tr>
    <td class="dataLabel">Date Format:</td>
    <td class="data">
    <select name="prefsDateFormat">
    <option value="1" <?php if ($_SESSION['prefsDateFormat'] == "1") echo "SELECTED"; if ($section == "step3") echo "SELECTED"; ?>>Month Day Year (MM/DD/YYYY)</option>
    <option value="2" <?php if ($_SESSION['prefsDateFormat'] == "2") echo "SELECTED"; ?>>Day Month Year (DD/MM/YYYY)</option>
    <option value="3" <?php if ($_SESSION['prefsDateFormat'] == "3") echo "SELECTED"; ?>>Year Month Day (YYYY/MM/DD)</option>
    </select>
    </td>
  </tr>
  <tr>
    <td class="dataLabel">Time Format:</td>
    <td class="data">
    <select name="prefsTimeFormat">
    <option value="0" <?php if ($_SESSION['prefsTimeFormat'] == "0") echo "SELECTED"; if ($section == "step3") echo "SELECTED"; ?>>12 Hour</option>
    <option value="1" <?php if ($_SESSION['prefsTimeFormat'] == "1") echo "SELECTED"; ?>>24 Hour</option>
    </select>
    </td>
  </tr>
  <tr>
  	<td class="dataLabel">Time Zone:</td>
    <td class="data">
    <select name="prefsTimeZone">
      <option value="-12.000" <?php if ($_SESSION['prefsTimeZone'] == "-12.000") echo "SELECTED"; ?>>(GMT -12:00) International Date Line West, Eniwetok, Kwajalein</option>
      <option value="-11.000" <?php if ($_SESSION['prefsTimeZone'] == "-11.000") echo "SELECTED"; ?>>(GMT -11:00) Midway Island, Samoa</option>
      <option value="-10.000" <?php if ($_SESSION['prefsTimeZone'] == "-10.000") echo "SELECTED"; ?>>(GMT -10:00) Hawaii</option>
      <option value="-9.000" <?php if ($_SESSION['prefsTimeZone'] == "-9.000") echo "SELECTED"; ?>>(GMT -9:00) Alaska</option>
      <option value="-8.000" <?php if ($_SESSION['prefsTimeZone'] == "-8.000") echo "SELECTED"; ?>>(GMT -8:00) Pacific Time (US &amp; Canada), Tiajuana</option>
      <option value="-7.000" <?php if ($_SESSION['prefsTimeZone'] == "-7.000") echo "SELECTED"; ?>>(GMT -7:00) Mountain Time (US &amp; Canada)</option>
      <option value="-7.001" <?php if ($_SESSION['prefsTimeZone'] == "-7.001") echo "SELECTED"; ?>>(GMT -7:00) Arizona (No Daylight Savings)</option>
      <option value="-6.000" <?php if ($_SESSION['prefsTimeZone'] == "-6.000") echo "SELECTED"; ?>>(GMT -6:00) Central Time (US &amp; Canada), Central America, Mexico City</option>
      <option value="-6.001" <?php if ($_SESSION['prefsTimeZone'] == "-6.001") echo "SELECTED"; ?>>(GMT -6:00) Sonora, Mexico (No Daylight Savings)</option>
      <option value="-6.002" <?php if ($_SESSION['prefsTimeZone'] == "-6.002") echo "SELECTED"; ?>>(GMT -6:00) Canada Central Time (No Daylight Savings)</option>
      <option value="-5.000" <?php if ($_SESSION['prefsTimeZone'] == "-5.000") echo "SELECTED"; ?>>(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
      <option value="-4.000" <?php if ($_SESSION['prefsTimeZone'] == "-4.000") echo "SELECTED"; ?>>(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
      <option value="-4.001" <?php if ($_SESSION['prefsTimeZone'] == "-4.001") echo "SELECTED"; ?>>(GMT -4:00) Paraguay</option>
      <option value="-3.500" <?php if ($_SESSION['prefsTimeZone'] == "-3.500") echo "SELECTED"; ?>>(GMT -3:30) Newfoundland</option>
      <option value="-3.000" <?php if ($_SESSION['prefsTimeZone'] == "-3.000") echo "SELECTED"; ?>>(GMT -3:00) Buenos Aires, Georgetown, Greenland</option>
      <option value="-3.001" <?php if ($_SESSION['prefsTimeZone'] == "-3.001") echo "SELECTED"; ?>>(GMT -3:00) Brazil (Brasilia)</option>
      <option value="-2.000" <?php if ($_SESSION['prefsTimeZone'] == "-2.000") echo "SELECTED"; ?>>(GMT -2:00) Mid-Atlantic</option>
      <option value="-1.000" <?php if ($_SESSION['prefsTimeZone'] == "-1.000") echo "SELECTED"; ?>>(GMT -1:00 hour) Azores, Cape Verde Islands</option>
      <option value="0.000" <?php if ($_SESSION['prefsTimeZone'] == "0.000") echo "SELECTED"; ?>>(GMT) Western Europe Time, London, Lisbon, Casablanca, Monrovia</option>
      <option value="1.000" <?php if ($_SESSION['prefsTimeZone'] == "1.000") echo "SELECTED"; ?>>(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
      <option value="2.000" <?php if ($_SESSION['prefsTimeZone'] == "2.000") echo "SELECTED"; ?>>(GMT +2:00) Kaliningrad, South Africa</option>
      <option value="3.000" <?php if ($_SESSION['prefsTimeZone'] == "3.000") echo "SELECTED"; ?>>(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg, Nairobi</option>
      <option value="3.500" <?php if ($_SESSION['prefsTimeZone'] == "3.500") echo "SELECTED"; ?>>(GMT +3:30) Tehran</option>
      <option value="4.000" <?php if ($_SESSION['prefsTimeZone'] == "4.000") echo "SELECTED"; ?>>(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
      <option value="4.500" <?php if ($_SESSION['prefsTimeZone'] == "4.500") echo "SELECTED"; ?>>(GMT +4:30) Kabul</option>
      <option value="5.000" <?php if ($_SESSION['prefsTimeZone'] == "5.000") echo "SELECTED"; ?>>(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
      <option value="5.000" <?php if ($_SESSION['prefsTimeZone'] == "5.500") echo "SELECTED"; ?>>(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
      <option value="5.750" <?php if ($_SESSION['prefsTimeZone'] == "5.750") echo "SELECTED"; ?>>(GMT +5:45) Kathmandu</option>
      <option value="6.000" <?php if ($_SESSION['prefsTimeZone'] == "6.000") echo "SELECTED"; ?>>(GMT +6:00) Almaty, Dhaka, Colombo, Krasnoyarsk</option>
      <option value="7.000" <?php if ($_SESSION['prefsTimeZone'] == "7.000") echo "SELECTED"; ?>>(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
      <option value="8.000" <?php if ($_SESSION['prefsTimeZone'] == "8.000") echo "SELECTED"; ?>>(GMT +8:00) Beijing, Singapore, Hong Kong</option>
      <option value="8.001" <?php if ($_SESSION['prefsTimeZone'] == "8.001") echo "SELECTED"; ?>>(GMT +8:00) Queensland, Perth, the Northern Territory, Western Australia</option>
      <option value="9.000" <?php if ($_SESSION['prefsTimeZone'] == "9.000") echo "SELECTED"; ?>>(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
      <option value="9.500" <?php if ($_SESSION['prefsTimeZone'] == "9.500") echo "SELECTED"; ?>>(GMT +9:30) Adelaide, Darwin</option>
      <option value="10.000" <?php if ($_SESSION['prefsTimeZone'] == "10.000") echo "SELECTED"; ?>>(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
      <option value="10.001" <?php if ($_SESSION['prefsTimeZone'] == "10.001") echo "SELECTED"; ?>>(GMT +10:00) Brisbane</option>
      <option value="11.000" <?php if ($_SESSION['prefsTimeZone'] == "11.000") echo "SELECTED"; ?>>(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
      <option value="12.000" <?php if ($_SESSION['prefsTimeZone'] == "12.000") echo "SELECTED"; ?>>(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
    </select>
    </td>
    </tr>
</table>

<h3>Measurements</h3>
<table>
  <tr>
  	<td class="dataLabel">Temperature:</td>
    <td class="data">
    <select name="prefsTemp">
    <option value="Fahrenheit" <?php if ($_SESSION['prefsTemp'] == "Fahrenheit") echo "SELECTED"; ?>>Fahrenheit</option>
    <option value="Celsius" <?php if ($_SESSION['prefsTemp'] == "Celsius") echo "SELECTED"; ?>>Celsius</option>
    </select>
    </td>
    </tr>
    <tr>
    <td class="dataLabel">Weight (Small):</td>
    <td class="data">
    <select name="prefsWeight1">
    <option value="ounces" <?php if ($_SESSION['prefsWeight1'] == "ounces") echo "SELECTED"; ?>>ounces</option>
    <option value="grams" <?php if ($_SESSION['prefsWeight1'] == "grams") echo "SELECTED"; ?>>grams</option>
    </select>    </td>
    </tr>
  <tr>
    <td class="dataLabel">Weight (Large):</td>
    <td class="data">
    <select name="prefsWeight2">
    <option value="pounds" <?php if ($_SESSION['prefsWeight2'] == "ounces") echo "SELECTED"; ?>>pounds</option>
    <option value="kilograms" <?php if ($_SESSION['prefsWeight2'] == "kilograms") echo "SELECTED"; ?>>kilograms</option>
    </select>    </td>
    </tr>
  <tr>
    <td class="dataLabel">Liquid (Small):</td>
    <td class="data">
    <select name="prefsLiquid1">
    <option value="ounces" <?php if ($_SESSION['prefsLiquid1'] == "ounces") echo "SELECTED"; ?>>ounces</option>
    <option value="millilitres" <?php if ($_SESSION['prefsLiquid1'] == "millilitres") echo "SELECTED"; ?>>milliliters</option>
    </select>    </tr>
  <tr>
    <td class="dataLabel">Liquid (Large):</td>
    <td class="data">
    <select name="prefsLiquid2">
    <option value="gallons" <?php if ($_SESSION['prefsLiquid1'] == "gallons") echo "SELECTED"; ?>>gallons</option>
    <option value="litres" <?php if ($_SESSION['prefsLiquid1'] == "litres") echo "SELECTED"; ?>>liters</option>
    </select>    </td>
    </tr>
  
</table>
<h3>Currency and Payment</h3>
<table>
  	<td class="dataLabel">Currency:</td>
    <td class="data">
        <select name="prefsCurrency">
        <?php 
		
		$currency = currency_info($_SESSION['prefsCurrency'],2);
		$currency_dropdown = "";
		
		foreach($currency as $curr) {
			$curr = explode("^",$curr);
			$currency_dropdown .= '<option value="'.$curr[0].'"';
			if ($_SESSION['prefsCurrency'] == $curr[0]) $currency_dropdown .= ' SELECTED';
			$currency_dropdown .= '>';
			$currency_dropdown .= $curr[1]."</option>";
		}
		
		echo $currency_dropdown;
		?>
        </select>
    </td>
    <td class="data">Please note. The currencies available in the drop-down <em><strong>above</strong> the dashed line</em> are those that are currently accepted by PayPal.</td>
    <tr>
    <td class="dataLabel">Pay for Entries to Print Paperwork:</td>
    <td nowrap="nowrap" class="data"><input type="radio" name="prefsPayToPrint" value="Y" id="prefsPayToPrint_0"  <?php if ($_SESSION['prefsPayToPrint'] == "Y") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsPayToPrint" value="N" id="prefsPayToPrint_1" <?php if ($_SESSION['prefsPayToPrint'] == "N") echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?>/> No</td>
  	<td class="data">Indicate if the entry must be marked as paid to be able to print associated paperwork.<br /><em>The default of &ldquo;No&rdquo; is appropriate for most installations; otherwise issues may arise that the BCOE&amp;M programming cannot control (e.g., if the user doesn't click the &ldquo;return to...&rdquo; link in PayPal).</em></td>
  	</tr>
    <tr>
      <td class="dataLabel">Cash for Payment:</td>
      <td class="data"><input type="radio" name="prefsCash" value="Y" id="prefsCash_0"  <?php if ($_SESSION['prefsCash'] == "Y") echo "CHECKED";  if ($section == "step3") echo "CHECKED"; ?> /> 
      Yes&nbsp;&nbsp;
        <input type="radio" name="prefsCash" value="N" id="prefsCash_1" <?php if ($_SESSION['prefsCash'] == "N") echo "CHECKED"; ?>/> 
        No</td>
      <td class="data">Do you want to accept entry payments in cash?</td>
    </tr>
    <tr>
      <td class="dataLabel">Checks for Payment:</td>
      <td class="data"><input type="radio" name="prefsCheck" value="Y" id="prefsCheck_0"  <?php if ($_SESSION['prefsCheck'] == "Y") echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?> /> 
        Yes&nbsp;&nbsp;
        <input type="radio" name="prefsCheck" value="N" id="prefsCheck_1" <?php if ($_SESSION['prefsCheck'] == "N") echo "CHECKED"; ?>/> 
        No</td>
      <td class="data">Do you want to accept checks for entry payments?</td>
    </tr>
  <tr>
    <td class="dataLabel">Checks Payee:</td>
    <td class="data"><input name="prefsCheckPayee" type="text" size="35" maxlength="255" value="<?php echo $_SESSION['prefsCheckPayee']; ?>"></td>
    <td class="data">Indicate who the entry checks should be made out to.</td>
  </tr>
  <tr>
  	<td class="dataLabel">PayPal for Payment:</td>
   	<td class="data"><input type="radio" name="prefsPaypal" value="Y" id="prefsPaypal_0"  <?php if ($_SESSION['prefsPaypal'] == "Y") echo "CHECKED";  if ($section == "step3") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsPaypal" value="N" id="prefsPaypal_1" <?php if ($_SESSION['prefsPaypal'] == "N") echo "CHECKED"; ?>/> No</td>
  	<td class="data">Do you want to accept credit card payments via PayPal?</td>
  </tr>
  <tr>
    <td class="dataLabel">PayPal Account Email:</td>
    <td class="data"><input name="prefsPaypalAccount" type="text" size="35" maxlength="255" value="<?php echo $_SESSION['prefsPaypalAccount']; ?>"></td>
    <td class="data">Indicate the email address associated with your PayPal account.<br />Please note that you need to have a verified bank account with PayPal to accept credit cards for payment. More information is contained in the &quot;Merchant Services&quot; area of your PayPal account.</td>
  </tr>
  <!--
   <tr>
  	<td class="dataLabel">Google Wallet for Payment:</td>
   	<td class="data"><input type="radio" name="prefsGoogle" value="Y" id="prefsGoogle_0"  <?php //if ($_SESSION['prefsGoogle'] == "Y") echo "CHECKED";  if ($section == "step3") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsGoogle" value="N" id="prefsGoogle_1" <?php //if ($_SESSION['prefsGoogle'] == "N") echo "CHECKED"; ?>/> No</td>
  	<td class="data">Do you want to accept credit card payments via Google Wallet?</td>
  </tr> 
  <tr>
    <td class="dataLabel">Google Merchant ID:</td>
    <td class="data"><input name="prefsGoogleAccount" type="text" size="35" value="<?php //echo $_SESSION['prefsGoogleAccount']; ?>"></td>
    <td class="data">Indicate your Google Merchant ID.<br />Please note that a <a href="https://checkout.google.com/sell/" target="_blank">Google Wallet/Checkout</a> account is required to accept payments through Google. To function properly, your account must be <a href="https://support.google.com/checkout/sell/bin/answer.py?hl=en&amp;ctx=cartwizard_acceptunsigned&amp;answer=113366" target="_blank">set up to accept unsigned carts</a>.</td>
  </tr>
  -->
  <tr>
  	<td class="dataLabel">Entrant Pays Checkout Fees:</td>
   	<td class="data"><input type="radio" name="prefsTransFee" value="Y" id="prefsTransFee_0"  <?php if ($_SESSION['prefsTransFee'] == "Y") echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsTransFee" value="N" id="prefsTransFee_1" <?php if ($_SESSION['prefsTransFee'] == "N") echo "CHECKED"; ?>/> No</td>
  	<td class="data">Do you want participants paying via PayPal also pay the transaction fees?<br />PayPal charges 2.9% + $0.30 USD per transaction. Checking "Yes" indicates that the transaction fees will be added to the participant's total.</td>
  </tr>
</table>
<h3>Sponsors</h3>
<table>
  <tr>
  	<td class="dataLabel">Sponsor Display:</td>
   	<td nowrap="nowrap" class="data"><input type="radio" name="prefsSponsors" value="Y" id="prefs0"  <?php if ($_SESSION['prefsSponsors'] == "Y") echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsSponsors" value="N" id="prefs1" <?php if ($_SESSION['prefsSponsors'] == "N") echo "CHECKED"; ?>/> No</td>
  	<td class="data">Do you want to display the competition sponsors?</td>
  </tr>
  <tr>
  	<td class="dataLabel">Sponsor Logos:</td>
   	<td nowrap="nowrap" class="data"><input type="radio" name="prefsSponsorLogos" value="Y" id="prefsSponsorLogos_0"  <?php if ($_SESSION['prefsSponsorLogos'] == "Y") echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsSponsorLogos" value="N" id="prefsSponsorLogos_1" <?php if ($_SESSION['prefsSponsorLogos'] == "N") echo "CHECKED"; ?>/> No</td>
  	<td class="data">Do you want to display the competition sponsors' individual logos (you will need to collect and upload all logos to your installation via the <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=sponsors">sponsor administration page</a>)?</td>
  </tr>
  <tr>
  	<td class="dataLabel">Sponsor Logo Size:</td>
    <td nowrap="nowrap" class="data">
    <select name="prefsSponsorLogoSize">
    <option value="100" <?php if ($_SESSION['prefsSponsorLogoSize'] == "100") echo "SELECTED"; ?>>100</option>
    <option value="150" <?php if ($_SESSION['prefsSponsorLogoSize'] == "150") echo "SELECTED"; ?>>150</option>
    <option value="200" <?php if ($_SESSION['prefsSponsorLogoSize'] == "200") echo "SELECTED"; ?>>200</option>
    <option value="250" <?php if ($_SESSION['prefsSponsorLogoSize'] == "250") echo "SELECTED"; ?>>250</option>
    </select> pixels
    </td>
    <td class="data">This is the default size, in pixels, that will each sponsor's logo will display on the <em>sponsors</em> page. Generally, the default setting of 150 is sufficient.</td>
  </tr>
</table>
<p><input name="submit" type="submit" class="button" value="Set Preferences"></p>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>