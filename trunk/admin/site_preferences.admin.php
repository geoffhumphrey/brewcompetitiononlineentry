<?php 
include (DB.'styles.db.php');
mysql_select_db($database, $brewing);
$query_themes = sprintf("SELECT * FROM %s",$prefix."themes");
$themes = mysql_query($query_themes, $brewing) or die(mysql_error());
$row_themes = mysql_fetch_assoc($themes);
$totalRows_themes = mysql_num_rows($themes);
?>
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/usable_forms.js"></script>
<form method="post" action="<?php echo $base_url; ?>/includes/process.inc.php?action=<?php if ($section == "step3") echo "add"; else echo "edit"; ?>&amp;dbTable=<?php echo $preferences_db_table; ?>&amp;id=1" name="form1">
<?php if ($section != "step3") { ?>
<h2>Site Preferences</h2>
<div class="adminSubNavContainer">
  	<span class="adminSubNav">
		<span class="icon"><img src="<?php echo $base_url; ?>/images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>/index.php?section=admin">Back to Admin Dashboard</a>
	</span>
</div>
<?php } ?>
<p><input name="submit" type="submit" class="button" value="Set Preferences"></p>
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
    <option value="300" <?php if (($section == "step3") || ($row_prefs['prefsCompLogoSize'] == "300")) echo "SELECTED"; ?>>300</option>
    <option value="350" <?php if ($row_prefs['prefsCompLogoSize'] == "350") echo "SELECTED"; ?>>350</option>
    <option value="400" <?php if ($row_prefs['prefsCompLogoSize'] == "400") echo "SELECTED"; ?>>400</option>
    </select> pixels    </td>
    <td class="data">If you upload and display your competition's logo, this is the width, in pixels, that you wish to display it.</td>
  </tr>
  <tr>
    <td class="dataLabel">Winner Display:</td>
    <td nowrap="nowrap" class="data"><input type="radio" name="prefsDisplayWinners" value="Y" id="prefsDisplayWinners_0"  <?php if ($row_prefs['prefsDisplayWinners'] == "Y") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsDisplayWinners" value="N" id="prefsDisplayWinners_1" <?php if ($row_prefs['prefsDisplayWinners'] == "N") echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?>/> No</td>
  	<td class="data">Indicate if the winners of the competition for each category and Best of Show Style Type will be displayed.</td>
  </tr>
  <tr>
  	<td class="dataLabel">Winner Display Delay:</td>
    <td nowrap="nowrap" class="data"><input name="prefsWinnerDelay" type="text" value="<?php if ($section == "step3") echo "8"; else echo $row_prefs['prefsWinnerDelay']; ?>" size="3" maxlength="11" /> 
    hours</td>
    <td class="data">Hours to delay displaying winners after the <em>start</em> time of the final judging session.</td>
  </tr>
  <tr>
    <td class="dataLabel">Winner Place Distribution Method:</td>
    <td nowrap="nowrap" class="data">
    <select name="prefsWinnerMethod">
    <option value="0" <?php if (($section == "step3") || ($row_prefs['prefsWinnerMethod'] == "0")) echo "SELECTED"; ?>>By Table</option>
    <option value="1" <?php if ($row_prefs['prefsWinnerMethod'] == "1") echo "SELECTED"; ?>>By Style Category</option>
    <option value="2" <?php if ($row_prefs['prefsWinnerMethod'] == "2") echo "SELECTED"; ?>>By Style Sub-Category</option>
    </select>
    </td>
    <td class="data">How the competition will award places for winning entries.</td>
  </tr>
  <tr>
      <td class="dataLabel">Contact Form:</td>
      <td nowrap="nowrap" class="data"><input type="radio" name="prefsContact" value="Y" id="prefsContact_0"  <?php if ($row_prefs['prefsContact'] == "Y") echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsContact" value="N" id="prefsContact_1" <?php if ($row_prefs['prefsContact'] == "N") echo "CHECKED"; ?>/> No</td>
      <td class="data">Enable or disable your installation's contact form. This may be necessary if your site's server does not support PHP's <a href="http://php.net/manual/en/function.mail.php" target="_blank">mail()</a> function. Admins should test the form before disabling as the form is the more secure option.</td>
  </tr>
  <tr>
    <td class="dataLabel">Site Theme:</td>
    <td nowrap="nowrap" class="data">
    <select name="prefsTheme">
    <?php do { ?>
    <option value="<?php echo $row_themes['themeFileName']; ?>" <?php if ($row_prefs['prefsTheme'] ==  $row_themes['themeFileName']) echo " SELECTED"; ?> /><?php echo  $row_themes['themeTitle']; ?></option>
    <?php } while ($row_themes = mysql_fetch_assoc($themes)); ?>
    </select>
    </td>
  	<td class="data">&nbsp;</td>
  </tr>
  <tr>
    <td class="dataLabel">Use Custom Modules:</td>
    <td nowrap="nowrap" class="data"><input type="radio" name="prefsUseMods" value="Y" id="prefsUseMods_0"  <?php if ($row_prefs['prefsUseMods'] == "Y") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsUseMods" value="N" id="prefsUseMods_1" <?php if ($row_prefs['prefsUseMods'] == "N") echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?>/> No</td>
  	<td class="data">FOR ADVANCED USERS. Utilize the ability to add custom modules that extend BCOE&amp;M's core functionality.</td>
  </tr>
</table>
<h3>Entries</h3>
<table>
	<tr>
    <td class="dataLabel">Require Special Ingredients<br />
      for Custom Styles:</td>
    <td nowrap="nowrap" class="data"><input type="radio" name="prefsDisplaySpecial" value="Y" id="prefsDisplaySpecial_0"  <?php if ($row_prefs['prefsDisplaySpecial'] == "Y") echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsDisplaySpecial" value="N" id="prefsDisplaySpecial_1" <?php if ($row_prefs['prefsDisplaySpecial'] == "N") echo "CHECKED"; ?>/> No</td>
    <td class="data">Indicate whether you would like to require entrants to specify special ingredients or a classic style for all of your competition's custom styles.</td>
  </tr>
  <tr>
    <td class="dataLabel">Printed Entry Form to Use:</td>
    <td nowrap="nowrap" class="data">
    <select name="prefsEntryForm">
        <option value="B" <?php if (($section == "step3") || ($row_prefs['prefsEntryForm'] == "B")) echo " SELECTED"; ?> />BJCP Official</option>
        <option value="M" <?php if ($row_prefs['prefsEntryForm'] == "M") echo " SELECTED"; ?> />Simple Metric</option>
        <option value="U" <?php if ($row_prefs['prefsEntryForm'] == "U") echo " SELECTED"; ?> />Simple U.S.</option>
        <option value="N" <?php if ($row_prefs['prefsEntryForm'] == "N") echo " SELECTED"; ?> />NHC Style - 1 Page with Barcode</option>
    </select>
    </td>
  	<td class="data">The BJCP Official form displays U.S. weights and measures.</td>
  </tr> 
  <tr>
  	<td class="dataLabel">Total Entry Limit:</td>
    <td nowrap="nowrap" class="data"><input name="prefsEntryLimit" type="text" value="<?php echo $row_prefs['prefsEntryLimit']; ?>" size="5" maxlength="11" /></td>
    <td class="data">Limit of entries you will accept in the competition. Leave blank if no limit.</td>
  </tr>
  <tr>
  	<td class="dataLabel">Entry Limit Per Participant:</td>
    <td nowrap="nowrap" class="data">
    <select name="prefsUserEntryLimit" />
    	<option value="" rel="none" <?php ($row_prefs['prefsUserEntryLimit'] == ""); echo "SELECTED"; ?>></option>
    <?php for ($i=1; $i <= 50; $i++) { ?>
    	<option value="<?php echo $i; ?>" <?php if ($row_prefs['prefsUserEntryLimit'] == $i) echo "SELECTED"; ?>><?php echo $i; ?></option>
    <?php } ?>
    </select>
    </td>
    <td class="data">Limit of entries that each participant can enter. Leave blank if no limit.</td>
  </tr>
  <tr>
  	<td class="dataLabel">Entry Limit Per Sub-category:</td>
    <td nowrap="nowrap" class="data">
    <select name="prefsUserSubCatLimit" />
    	<option value="" rel="none" <?php ($row_prefs['prefsUserSubCatLimit'] == ""); echo "SELECTED"; ?>></option>
    <?php for ($i=1; $i <= 50; $i++) { ?>
    	<option value="<?php echo $i; ?>" rel="user_entry_limit" <?php if ($row_prefs['prefsUserSubCatLimit'] == $i) echo "SELECTED"; ?>><?php echo $i; ?></option>
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
    ?>
            	<td width="1%"><input name="prefsUSCLEx[]" type="checkbox" value="<?php echo $row_styles['id']; ?>" <?php $a = explode(",", $row_prefs['prefsUSCLEx']); $b = $row_styles['id']; foreach ($a as $value) { if ($value == $b) echo "CHECKED"; } ?>></td>
                <td width="1%"><?php echo ltrim($row_styles['brewStyleGroup'], "0").$row_styles['brewStyleNum'].":"; ?></td>
                <td><?php echo $row_styles['brewStyle']; ?></td>
            <?php  $endRow++;
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
    	<option value="" rel="none" <?php ($row_prefs['prefsUSCLExLimit'] == ""); echo "SELECTED"; ?>></option>
    <?php for ($i=1; $i <= 50; $i++) { ?>
    	<option value="<?php echo $i; ?>" <?php if ($row_prefs['prefsUSCLExLimit'] == $i) echo "SELECTED"; ?>><?php echo $i; ?></option>
    <?php } ?>
    </select>
    </td>
    <td class="data">Limit of entries that each participant can enter into one of the above sub-categories that <em>have been checked</em>. Leave blank if no limit for these sub-categories.</td>
  </tr>
  <tr>
    <td class="dataLabel">Hide Entry Recipe Section:</td>
    <td nowrap="nowrap" class="data"><input type="radio" name="prefsHideRecipe" value="Y" id="prefsHideRecipe_0"  <?php if ($row_prefs['prefsHideRecipe'] == "Y") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsHideRecipe" value="N" id="prefsHideRecipe_1" <?php if ($row_prefs['prefsHideRecipe'] == "N") echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?>/> No</td>
  	<td class="data">Indicate if the recipe section (optional information such as malt, yeast, etc.) on the Add Entry or Edit Entry screens will be displayed. If enabled, the BeerXML Import function will not be available.</td>
  </tr>
</table>
<h3>Performance</h3>
<table>
  <tr>
  	<td class="dataLabel">DataTables Record Threshold:</td>
    <td nowrap="nowrap" class="data"><input name="prefsRecordLimit" type="text" value="<?php if ($section == "step3") echo "750"; else echo $row_prefs['prefsRecordLimit']; ?>" size="5" maxlength="11" /></td>
    <td class="data">The threshold of records for the application to utilize <a href="http://www.datatables.net/" target="_blank">DataTables</a> for paging and sorting,  a Javascript-enabled function that does not require page refreshes to sort or page through <em>all </em>records - the higher the threshold, the greater the possiblity for performance issues because <em>all</em> records are loaded at once.  Generally, the default value will work for most installations.</td>
  </tr>
  <tr>
  	<td class="dataLabel">Number of Records to Display Per Page:</td>
    <td nowrap="nowrap" class="data"><input name="prefsRecordPaging" type="text" value="<?php if ($section == "step3") echo "150"; else echo $row_prefs['prefsRecordPaging']; ?>" size="5" maxlength="11" /></td>
    <td class="data">The number of records  displayed per page when viewing lists (e.g., when viewing the entries or participants list). Generally, the default value will work for most installations.</td>
  </tr>
</table>
<h3>Localization</h3>
<table>
  <tr>
    <td class="dataLabel">Date Format:</td>
    <td class="data">
    <select name="prefsDateFormat">
    <option value="1" <?php if ($row_prefs['prefsDateFormat'] == "1") echo "SELECTED"; if ($section == "step3") echo "SELECTED"; ?>>Month Day Year (MM/DD/YYYY)</option>
    <option value="2" <?php if ($row_prefs['prefsDateFormat'] == "2") echo "SELECTED"; ?>>Day Month Year (DD/MM/YYYY)</option>
    <option value="3" <?php if ($row_prefs['prefsDateFormat'] == "3") echo "SELECTED"; ?>>Year Month Day (YYYY/MM/DD)</option>
    </select>
    </td>
  </tr>
  <tr>
    <td class="dataLabel">Time Format:</td>
    <td class="data">
    <select name="prefsTimeFormat">
    <option value="0" <?php if ($row_prefs['prefsTimeFormat'] == "0") echo "SELECTED"; if ($section == "step3") echo "SELECTED"; ?>>12 Hour</option>
    <option value="1" <?php if ($row_prefs['prefsTimeFormat'] == "1") echo "SELECTED"; ?>>24 Hour</option>
    </select>
    </td>
  </tr>
  <tr>
  	<td class="dataLabel">Time Zone:</td>
    <td class="data">
    <select name="prefsTimeZone">
      <option value="-12.000" <?php if ($row_prefs['prefsTimeZone'] == "-12.000") echo "SELECTED"; ?>>(GMT -12:00) International Date Line West, Eniwetok, Kwajalein</option>
      <option value="-11.000" <?php if ($row_prefs['prefsTimeZone'] == "-11.000") echo "SELECTED"; ?>>(GMT -11:00) Midway Island, Samoa</option>
      <option value="-10.000" <?php if ($row_prefs['prefsTimeZone'] == "-10.000") echo "SELECTED"; ?>>(GMT -10:00) Hawaii</option>
      <option value="-9.000" <?php if ($row_prefs['prefsTimeZone'] == "-9.000") echo "SELECTED"; ?>>(GMT -9:00) Alaska</option>
      <option value="-8.000" <?php if ($row_prefs['prefsTimeZone'] == "-8.000") echo "SELECTED"; ?>>(GMT -8:00) Pacific Time (US &amp; Canada), Tiajuana</option>
      <option value="-7.000" <?php if ($row_prefs['prefsTimeZone'] == "-7.000") echo "SELECTED"; ?>>(GMT -7:00) Mountain Time (US &amp; Canada)</option>
      <option value="-7.001" <?php if ($row_prefs['prefsTimeZone'] == "-7.001") echo "SELECTED"; ?>>(GMT -7:00) Arizona (No Daylight Savings)</option>
      <option value="-6.000" <?php if ($row_prefs['prefsTimeZone'] == "-6.000") echo "SELECTED"; ?>>(GMT -6:00) Central Time (US &amp; Canada), Central America, Mexico City</option>
      <option value="-6.001" <?php if ($row_prefs['prefsTimeZone'] == "-6.001") echo "SELECTED"; ?>>(GMT -6:00) Sonora, Mexico (No Daylight Savings)</option>
      <option value="-6.002" <?php if ($row_prefs['prefsTimeZone'] == "-6.002") echo "SELECTED"; ?>>(GMT -6:00) Canada Central Time (No Daylight Savings)</option>
      <option value="-5.000" <?php if ($row_prefs['prefsTimeZone'] == "-5.000") echo "SELECTED"; ?>>(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
      <option value="-4.000" <?php if ($row_prefs['prefsTimeZone'] == "-4.000") echo "SELECTED"; ?>>(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
      <option value="-4.001" <?php if ($row_prefs['prefsTimeZone'] == "-4.001") echo "SELECTED"; ?>>(GMT -4:00) Paraguay</option>
      <option value="-3.500" <?php if ($row_prefs['prefsTimeZone'] == "-3.500") echo "SELECTED"; ?>>(GMT -3:30) Newfoundland</option>
      <option value="-3.000" <?php if ($row_prefs['prefsTimeZone'] == "-3.000") echo "SELECTED"; ?>>(GMT -3:00) Brazil, Buenos Aires, Georgetown, Greenland</option>
      <option value="-3.001" <?php if ($row_prefs['prefsTimeZone'] == "-3.001") echo "SELECTED"; ?>>(GMT -3:00) Brazil (No Daylight Savings)</option>
      <option value="-2.000" <?php if ($row_prefs['prefsTimeZone'] == "-2.000") echo "SELECTED"; ?>>(GMT -2:00) Mid-Atlantic</option>
      <option value="-1.000" <?php if ($row_prefs['prefsTimeZone'] == "-1.000") echo "SELECTED"; ?>>(GMT -1:00 hour) Azores, Cape Verde Islands</option>
      <option value="0.000" <?php if ($row_prefs['prefsTimeZone'] == "0.000") echo "SELECTED"; ?>>(GMT) Western Europe Time, London, Lisbon, Casablanca, Monrovia</option>
      <option value="1.000" <?php if ($row_prefs['prefsTimeZone'] == "1.000") echo "SELECTED"; ?>>(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
      <option value="2.000" <?php if ($row_prefs['prefsTimeZone'] == "2.000") echo "SELECTED"; ?>>(GMT +2:00) Kaliningrad, South Africa</option>
      <option value="3.000" <?php if ($row_prefs['prefsTimeZone'] == "3.000") echo "SELECTED"; ?>>(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg, Nairobi</option>
      <option value="3.500" <?php if ($row_prefs['prefsTimeZone'] == "3.500") echo "SELECTED"; ?>>(GMT +3:30) Tehran</option>
      <option value="4.000" <?php if ($row_prefs['prefsTimeZone'] == "4.000") echo "SELECTED"; ?>>(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
      <option value="4.500" <?php if ($row_prefs['prefsTimeZone'] == "4.500") echo "SELECTED"; ?>>(GMT +4:30) Kabul</option>
      <option value="5.000" <?php if ($row_prefs['prefsTimeZone'] == "5.000") echo "SELECTED"; ?>>(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
      <option value="5.000" <?php if ($row_prefs['prefsTimeZone'] == "5.500") echo "SELECTED"; ?>>(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
      <option value="5.750" <?php if ($row_prefs['prefsTimeZone'] == "5.750") echo "SELECTED"; ?>>(GMT +5:45) Kathmandu</option>
      <option value="6.000" <?php if ($row_prefs['prefsTimeZone'] == "6.000") echo "SELECTED"; ?>>(GMT +6:00) Almaty, Dhaka, Colombo, Krasnoyarsk</option>
      <option value="7.000" <?php if ($row_prefs['prefsTimeZone'] == "7.000") echo "SELECTED"; ?>>(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
      <option value="8.000" <?php if ($row_prefs['prefsTimeZone'] == "8.000") echo "SELECTED"; ?>>(GMT +8:00) Beijing, Singapore, Hong Kong</option>
      <option value="8.001" <?php if ($row_prefs['prefsTimeZone'] == "8.001") echo "SELECTED"; ?>>(GMT +8:00) Queensland, Perth, the Northern Territory, Western Australia</option>
      <option value="9.000" <?php if ($row_prefs['prefsTimeZone'] == "9.000") echo "SELECTED"; ?>>(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
      <option value="9.500" <?php if ($row_prefs['prefsTimeZone'] == "9.500") echo "SELECTED"; ?>>(GMT +9:30) Adelaide, Darwin</option>
      <option value="10.000" <?php if ($row_prefs['prefsTimeZone'] == "10.000") echo "SELECTED"; ?>>(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
      <option value="10.001" <?php if ($row_prefs['prefsTimeZone'] == "10.001") echo "SELECTED"; ?>>(GMT +10:00) Brisbane</option>
      <option value="11.000" <?php if ($row_prefs['prefsTimeZone'] == "11.000") echo "SELECTED"; ?>>(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
      <option value="12.000" <?php if ($row_prefs['prefsTimeZone'] == "12.000") echo "SELECTED"; ?>>(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
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
    <option value="Fahrenheit" <?php if ($row_prefs['prefsTemp'] == "Fahrenheit") echo "SELECTED"; ?>>Fahrenheit</option>
    <option value="Celsius" <?php if ($row_prefs['prefsTemp'] == "Celsius") echo "SELECTED"; ?>>Celsius</option>
    </select>
    </td>
    </tr>
    <tr>
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
  	<td class="dataLabel">Currency:</td>
    <td class="data">
    <select name="prefsCurrency">
    <option value="$"  <?php if ($row_prefs['prefsCurrency'] == "$") echo "SELECTED"; ?>>$ - Dollar</option>
    <option value="&amp;pound;" <?php if ($row_prefs['prefsCurrency'] == "&pound;") echo "SELECTED"; ?>>&pound; - Pound Sterling</option>
    <option value="&amp;euro;" <?php if ($row_prefs['prefsCurrency'] == "&euro;") echo "SELECTED"; ?>>&euro; - Euro</option>
    <option value="&amp;yen;" <?php if ($row_prefs['prefsCurrency'] == "&yen;") echo "SELECTED"; ?>>&yen; - Yen</option>
    <option value="&#8355;" <?php if ($row_prefs['prefsCurrency'] == "&#8355;") echo "SELECTED"; ?>>&#8355; - Franc</option>
    <option value="kr" <?php if ($row_prefs['prefsCurrency'] == "kr") echo "SELECTED"; ?>>kr - Krone</option>
    <option value="&#8356;" <?php if ($row_prefs['prefsCurrency'] == "&#8356;") echo "SELECTED"; ?>>&#8356; - Lira</option>
    <option value="R" <?php if ($row_prefs['prefsCurrency'] == "&yen;") echo "SELECTED"; ?>>R - Rand</option>
    <option value="&#8360;" <?php if ($row_prefs['prefsCurrency'] == "&#8360;") echo "SELECTED"; ?>>&#8360; - Rupee</option>
    </select>    </td>
    <td class="data">&nbsp;</td>
    <tr>
    <td class="dataLabel">Pay for Entries to Print Paperwork:</td>
    <td nowrap="nowrap" class="data"><input type="radio" name="prefsPayToPrint" value="Y" id="prefsPayToPrint_0"  <?php if ($row_prefs['prefsPayToPrint'] == "Y") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsPayToPrint" value="N" id="prefsPayToPrint_1" <?php if ($row_prefs['prefsPayToPrint'] == "N") echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?>/> No</td>
  	<td class="data">Indicate if the entry must be marked as paid to be able to print associated paperwork.<br /><em>The default of &ldquo;No&rdquo; is appropriate for most installations; otherwise issues may arise that the BCOE&amp;M programming cannot control (e.g., if the user doesn't click the &ldquo;return to...&rdquo; link in PayPal).</em></td>
  	</tr>
    <tr>
      <td class="dataLabel">Cash for Payment:</td>
      <td class="data"><input type="radio" name="prefsCash" value="Y" id="prefsCash_0"  <?php if ($row_prefs['prefsCash'] == "Y") echo "CHECKED";  if ($section == "step3") echo "CHECKED"; ?> /> 
      Yes&nbsp;&nbsp;
        <input type="radio" name="prefsCash" value="N" id="prefsCash_1" <?php if ($row_prefs['prefsCash'] == "N") echo "CHECKED"; ?>/> 
        No</td>
      <td class="data">Do you want to accept entry payments in cash?</td>
    </tr>
    <tr>
      <td class="dataLabel">Checks for Payment:</td>
      <td class="data"><input type="radio" name="prefsCheck" value="Y" id="prefsCheck_0"  <?php if ($row_prefs['prefsCheck'] == "Y") echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?> /> 
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
   	<td class="data"><input type="radio" name="prefsPaypal" value="Y" id="prefsPaypal_0"  <?php if ($row_prefs['prefsPaypal'] == "Y") echo "CHECKED";  if ($section == "step3") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsPaypal" value="N" id="prefsPaypal_1" <?php if ($row_prefs['prefsPaypal'] == "N") echo "CHECKED"; ?>/> No</td>
  	<td class="data">Do you want to accept credit card payments via PayPal?</td>
  </tr>
  <tr>
    <td class="dataLabel">PayPal Account Email:</td>
    <td class="data"><input name="prefsPaypalAccount" type="text" size="35" maxlength="255" value="<?php echo $row_prefs['prefsPaypalAccount']; ?>"></td>
    <td class="data">Indicate the email address associated with your PayPal account.<br />Please note that you need to have a verified bank account with PayPal to accept credit cards for payment. More information is contained in the &quot;Merchant Services&quot; area of your PayPal account.</td>
  </tr>
   <tr>
  	<td class="dataLabel">Google Wallet for Payment:</td>
   	<td class="data"><input type="radio" name="prefsGoogle" value="Y" id="prefsGoogle_0"  <?php if ($row_prefs['prefsGoogle'] == "Y") echo "CHECKED";  if ($section == "step3") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsGoogle" value="N" id="prefsGoogle_1" <?php if ($row_prefs['prefsGoogle'] == "N") echo "CHECKED"; ?>/> No</td>
  	<td class="data">Do you want to accept credit card payments via PayPal?</td>
  </tr>
  <tr>
    <td class="dataLabel">Google Merchant ID:</td>
    <td class="data"><input name="prefsGoogleAccount" type="text" size="35" value="<?php echo $row_prefs['prefsGoogleAccount']; ?>"></td>
    <td class="data">Indicate your Google Merchant ID.<br />Please note that a <a href="https://checkout.google.com/sell/" target="_blank">Google Wallet/Checkout</a> account is required to accept payments through Google. To function properly, your account must be <a href="https://support.google.com/checkout/sell/bin/answer.py?hl=en&amp;ctx=cartwizard_acceptunsigned&amp;answer=113366" target="_blank">set up to accept unsigned carts</a>.</td>
  </tr>
  <tr>
  	<td class="dataLabel">Entrant Pays Checkout Fees:</td>
   	<td class="data"><input type="radio" name="prefsTransFee" value="Y" id="prefsTransFee_0"  <?php if ($row_prefs['prefsTransFee'] == "Y") echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsTransFee" value="N" id="prefsTransFee_1" <?php if ($row_prefs['prefsTransFee'] == "N") echo "CHECKED"; ?>/> No</td>
  	<td class="data">Do you want participants paying via PayPal and/or Google to also pay the transaction fees?<br />PayPal and Google each charge 2.9% + $0.30 USD per transaction. Checking "Yes" indicates that the transaction fees will be added to the participant's total.</td>
  </tr>
</table>
<h3>Sponsors</h3>
<table>
  <tr>
  	<td class="dataLabel">Sponsor Display:</td>
   	<td nowrap="nowrap" class="data"><input type="radio" name="prefsSponsors" value="Y" id="prefs0"  <?php if ($row_prefs['prefsSponsors'] == "Y") echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsSponsors" value="N" id="prefs1" <?php if ($row_prefs['prefsSponsors'] == "N") echo "CHECKED"; ?>/> No</td>
  	<td class="data">Do you want to display the competition sponsors?</td>
  </tr>
  <tr>
  	<td class="dataLabel">Sponsor Logos:</td>
   	<td nowrap="nowrap" class="data"><input type="radio" name="prefsSponsorLogos" value="Y" id="prefsSponsorLogos_0"  <?php if ($row_prefs['prefsSponsorLogos'] == "Y") echo "CHECKED"; if ($section == "step3") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="prefsSponsorLogos" value="N" id="prefsSponsorLogos_1" <?php if ($row_prefs['prefsSponsorLogos'] == "N") echo "CHECKED"; ?>/> No</td>
  	<td class="data">Do you want to display the competition sponsors' individual logos (you will need to collect and upload all logos to your installation via the <a href="<?php echo $base_url; ?>/index.php?section=admin&amp;go=sponsors">sponsor administration page</a>)?</td>
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
</table>
<p><input name="submit" type="submit" class="button" value="Set Preferences"></p>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>