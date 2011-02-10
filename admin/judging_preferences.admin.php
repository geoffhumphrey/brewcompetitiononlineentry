<script type="text/javascript" src="js_includes/usable_forms.js"></script>
<form method="post" action="includes/process.inc.php?action=edit&amp;dbTable=judging_preferences&amp;id=1" name="form1">
<?php if ($section != "step1") { ?>
<h2> Competition Organization Preferences</h2>
<p><span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a class="data" href="index.php?section=admin">Back to Admin</a></p>
<?php } ?>
<table>
  <tr>
    <td class="dataLabel">Use Queued Judging:</td>
    <td nowrap="nowrap" class="data"><input type="radio" name="jPrefsQueued" value="Y" id="jPrefsQueued_0" rel="none"  <?php if ($row_judging_prefs['jPrefsQueued'] == "Y") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="jPrefsQueued" value="N" id="jPrefsQueued_1" rel="queued_no" <?php if ($row_judging_prefs['jPrefsQueued'] == "N") echo "CHECKED"; ?>/> No</td>
    <td class="data">Indicate whether you would like to use the Queued Judging methodology (employed by the American Homebrewers Association for judging the National Hombrewers Competition). If &quot;Yes,&quot; there is no need for competition organizers to define flights. More information can be downloaded on the <a href="http://www.bjcp.org/docs/Queued_Judging_organizer.pdf" target="_blank">AHA's website</a>.</td>
  </tr>
  <tr rel="queued_no">
    <td class="dataLabel">Entries per Flight:</td>
    <td nowrap="nowrap" class="data"><input name="jPrefsFlightEntries" type="text" value="<?php echo $row_judging_prefs['jPrefsFlightEntries']; ?>" size="5" maxlength="5" /></td>
  	<td class="data">Indicate the maximum amount of entries per judging flight.</td>
  </tr>
  <tr rel="queued_no">
    <td class="dataLabel">BOS:</td>
    <td nowrap="nowrap" class="data">
      <input type="radio" name="jPrefsBOSMethod" value="1" id="jPrefsBOSMethod_0" <?php if ($row_judging_prefs['jPrefsBOSMethod'] == "1") echo "checked"; ?> />1st place only<br />
      <input type="radio" name="jPrefsBOSMethod" value="2" id="jPrefsBOSMethod_1" <?php if ($row_judging_prefs['jPrefsBOSMethod'] == "2") echo "checked"; ?> />1st and 2nd places only<br />
      <input type="radio" name="jPrefsBOSMethod" value="3" id="jPrefsBOSMethod_2" <?php if ($row_judging_prefs['jPrefsBOSMethod'] == "3") echo "checked"; ?> />1st, 2nd, and 3rd places<br />
      <input type="radio" name="jPrefsBOSMethod" value="4" id="jPrefsBOSMethod_3" <?php if ($row_judging_prefs['jPrefsBOSMethod'] == "4") echo "checked"; ?> />Defined by user
    </td>
  	<td class="data">Indicate which places from each table will advance to the BOS Round or whether you will define each BOS entry manually.</td>
  </tr>
</table>
<p><input name="submit" type="submit" class="button" value="Set Preferences"></p>

<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER']); ?>">
</form>