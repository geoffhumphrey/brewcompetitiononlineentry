<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/usable_forms.js"></script>
<form method="post" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo $section; ?>&amp;action=edit&amp;dbTable=<?php echo $judging_preferences_db_table; ?>&amp;id=1" name="form1" onSubmit="return CheckRequiredFields()">
<?php if ($section != "step8") { ?>
<h2>Competition Organization Preferences</h2>
<div class="adminSubNavContainer">
	<span class="adminSubNavContainer">
		<span class="icon"><img src="<?php echo $base_url; ?>images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>index.php?section=admin">Back to Admin Dashboard</a>
	</span>
</div>
<?php } ?>
<table>
  <tr>
    <td class="dataLabel">Use Queued Judging:</td>
    <td nowrap="nowrap" class="data"><input type="radio" name="jPrefsQueued" value="Y" id="jPrefsQueued_0" rel="none"  <?php if ($_SESSION['jPrefsQueued'] == "Y") echo "CHECKED"; ?> /> Yes&nbsp;&nbsp;<input type="radio" name="jPrefsQueued" value="N" id="jPrefsQueued_1" rel="queued_no" <?php if ($_SESSION['jPrefsQueued'] == "N") echo "CHECKED"; ?>/> No</td>
    <td class="data">Indicate whether you would like to use the Queued Judging methodology (employed by the American Homebrewers Association for judging the National Hombrewers Competition). If &quot;Yes,&quot; there is no need for competition organizers to define flights. More information can be downloaded on the <a href="http://www.bjcp.org/docs/Queued_Judging_organizer.pdf" target="_blank">BJCP's website</a>.</td>
  </tr>
  <tr rel="queued_no">
    <td class="dataLabel">Maximum Entries per Flight:</td>
    <td nowrap="nowrap" class="data"><input name="jPrefsFlightEntries" type="text" value="<?php echo $_SESSION['jPrefsFlightEntries']; ?>" size="5" maxlength="5" /></td>
  	<td class="data">Indicate the maximum amount of entries per judging flight for all judging locations.</td>
  </tr>
  <tr>
    <td class="dataLabel">Maximum Rounds per Location:</td>
    <td nowrap="nowrap" class="data"><input name="jPrefsRounds" type="text" value="<?php echo $_SESSION['jPrefsRounds']; ?>" size="5" maxlength="5" /></td>
  	<td class="data">Indicate the maximum amount of rounds for each of the competition's locations. This <em>does not</em> include the Best of Show (BOS) round(s).</td>
  </tr>
  <tr>
    <td class="dataLabel">Maximum Places in BOS Round:</td>
    <td nowrap="nowrap" class="data"><input name="jPrefsMaxBOS" type="text" value="<?php echo $_SESSION['jPrefsMaxBOS']; ?>" size="5" maxlength="5" /></td>
    <td class="data">Indicate the maximum number of places for each of the competition's Best of Show (BOS) <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types">style types</a>. </td>
  </tr>
</table>
<p><input name="submit" type="submit" class="button" value="Set Preferences"></p>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>