<form method="post" action="includes/process.inc.php?action=<?php if ($section == "step2") echo "add"; else echo "edit"; ?>&dbTable=contest_info&id=1" name="form1">
<?php if ($section != "step2") { ?>
<h2>Competition Info</h2>
<p><a href="index.php?section=admin">&laquo; Back to Admin</a></p>
<?php } ?>
<h3>Contact</h3>
<table>
  <tr>
    <td class="dataLabel">Contact Name:</td>
    <td class="data"><input name="contestContactName" type="text" size="50" maxlength="255" value="<?php echo $row_contest_info['contestContactName']; ?>"></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Contact Email:</td>
    <td class="data"><input name="contestContactEmail" type="text" size="50" maxlength="255" value="<?php echo $row_contest_info['contestContactEmail']; ?>"></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
</table>
<h3>General Info</h3>
<table>
  <tr>
    <td class="dataLabel">Competition Name:</td>
    <td class="data"><input name="contestName" type="text" size="50" maxlength="255" value="<?php echo $row_contest_info['contestName']; ?>"></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Hosted By:</td>
    <td class="data"><input name="contestHost" type="text" size="50" maxlength="255" value="<?php echo $row_contest_info['contestHost']; ?>"></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Host Location:</td>
    <td class="data"><input name="contestHostLocation" type="text" size="50" maxlength="255" value="<?php echo $row_contest_info['contestHostLocation']; ?>"></td>
    <td class="data">&nbsp;</td>
  </tr>
  <tr>
    <td class="dataLabel">Host Website Address:</td>
    <td class="data"><input name="contestHostWebsite" type="text" size="50" maxlength="255" value="<?php echo $row_contest_info['contestHostWebsite']; ?>"></td>
    <td class="data"><em>Provide the entire website address including the http://</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Competition Logo File Name:</td>
    <td class="data"><input name="contestLogo" type="text" size="50" maxlength="255" value="<?php echo $row_contest_info['contestLogo']; ?>" />
    <br /><br /><span class="icon"><img src="images/picture_add.png" align="absmiddle"></span><a href="admin/upload.admin.php?KeepThis=true&TB_iframe=true&height=350&width=800" title="Upload Competition Logo Image" class="thickbox">Upload Logo Image</a></td>
    <td class="data"><em>Provide the exact name of the file (e.g., logo.jpg).</em></td>
  </tr>
</table>
<h3>Entry and Registration</h3>
<table>
  <tr>
    <td class="dataLabel">Entry Window Open:</td>
    <td class="data"><input name="contestEntryOpen" type="text" size="20" onfocus="showCalendarControl(this);" value="<?php echo $row_contest_info['contestEntryOpen']; ?>" /></td>
    <td class="data"><span class="required">Required</span> <em>The date entries must be received at drop-off and mail-in locations.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Entry Window Close:</td>
    <td class="data"><input name="contestEntryDeadline" type="text" size="20" onfocus="showCalendarControl(this);" value="<?php echo $row_contest_info['contestEntryDeadline']; ?>"></td>
    <td class="data"><span class="required">Required</span> 
      <em>The date entries must be received at drop-off and mail-in locations.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Registration Open:</td>
    <td class="data"><input name="contestRegistrationOpen" type="text" size="20" onfocus="showCalendarControl(this);" value="<?php echo $row_contest_info['contestRegistrationOpen']; ?>" /></td>
    <td class="data"><span class="required">Required </span><em>The date the system will automatically open registrations.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Registration Close:</td>
    <td class="data"><input name="contestRegistrationDeadline" type="text" size="20" onfocus="showCalendarControl(this);" value="<?php echo $row_contest_info['contestRegistrationDeadline']; ?>"></td>
    <td class="data"><span class="required">Required </span><em>The date the system will automatically close registrations.</em></td>
  </tr>
</table>
<h3>Awards</h3>
<table>
  <tr>
    <td class="dataLabel">Awards Date:</td>
    <td class="data"><input name="contestAwardsLocDate" type="text" size="20" onfocus="showCalendarControl(this);" value="<?php echo $row_contest_info['contestAwardsLocDate']; ?>"></td>
    <td class="data"><em>Provide even if the date of judging is the same.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Awards Start Time:</td>
    <td class="data"><input name="contestAwardsLocTime" size="30" value="<?php echo $row_contest_info['contestAwardsLocTime']; ?>"></td>
    <td class="data"><em>The approximate time the awards ceremony will begin.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Awards Location Name:</td>
    <td class="data"><input name="contestAwardsLocName" size="30" value="<?php echo $row_contest_info['contestAwardsLocName']; ?>"></td>
    <td class="data"><em>Provide the name of the awards location.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Awards Location Address:</td>
    <td class="data"><textarea name="contestAwardsLocation"  cols="40" rows="7" class="mceNoEditor"><?php echo $row_contest_info['contestAwardsLocation']; ?></textarea></td>
    <td class="data"><em>Provide the address of the award location. The more complete (e.g., street address, city, state, zip) the better.</em></td>
  </tr> 
</table>
<h3>Rules</h3>
<table>
  <tr>
    <td class="dataLabel">Competition Rules:</td>
    <td class="data"><textarea name="contestRules" cols="70" rows="25"><?php echo $row_contest_info['contestRules']; ?></textarea></td>
    <td class="data"><em>Copy and paste if needed.</em></td>
  </tr>
</table>
<h3>Entries</h3>
<table>
  <tr>
    <td class="dataLabel">Entry Fee:</td>
    <td class="data"><?php echo $row_prefs['prefsCurrency']; ?> <input name="contestEntryFee" type="text" size="5" maxlength="10" value="<?php echo $row_contest_info['contestEntryFee']; ?>"></td>
    <td class="data style1"> <span class="required">Required </span>Fee for a single entry (<?php echo $row_prefs['prefsCurrency']; ?>) - please enter a zero (0) for a free entry fee.</td>
  </tr>
  <tr>
    <td class="dataLabel">Entry Fee Cap:</td>
    <td class="data"><?php echo $row_prefs['prefsCurrency']; ?> <input name="contestEntryCap" type="text" size="5" maxlength="10" value="<?php echo $row_contest_info['contestEntryCap']; ?>"></td>
    <td class="data"><em>Useful for competitions with "unlimited" entries for a single fee (e.g., $5 for the first entry, $10 for unlimited, etc.). Enter the maximum amount for each entrant. Leave blank if no cap.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Discount Multiple Entries:</td>
    <td nowrap="nowrap" class="data">
    <input type="radio" name="contestEntryFeeDiscount" value="Y" id="contestEntryFeeDiscount_0"  <?php if ($row_contest_info['contestEntryFeeDiscount'] == "Y") echo "CHECKED"; ?> /> 
    Yes&nbsp;&nbsp;
    <input type="radio" name="contestEntryFeeDiscount" value="N" id="contestEntryFeeDiscount_1" <?php if ($row_contest_info['contestEntryFeeDiscount'] == "N") echo "CHECKED"; ?>/> 
    No    </td>
    <td class="data"><em>Designate Yes or No if your competition offers a discounted entry fee after a certain number is reached.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Minimum Entries for Discount:</td>
    <td class="data"><input name="contestEntryFeeDiscountNum" type="text" size="5" maxlength="10" value="<?php echo $row_contest_info['contestEntryFeeDiscountNum']; ?>"></td>
    <td class="data"><em>The entry threshold participants must exceed to take advantage of the per entry fee discount (designated below). If no, discounted fee exists, leave blank.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Discounted Entry Fee:</td>
    <td class="data"><?php echo $row_prefs['prefsCurrency']; ?> <input name="contestEntryFee2" type="text" size="5" maxlength="10" value="<?php echo $row_contest_info['contestEntryFee2']; ?>"></td>
    <td class="data"><em>Fee for a single, </em>discounted<em> entry (<?php echo $row_prefs['prefsCurrency']; ?>).</em></td>
  </tr>
</table>
<table>
  <tr>
    <td class="dataLabel">Bottle Acceptance Rules:</td>
    <td class="data"><textarea name="contestBottles" cols="70" rows="25"><?php  echo $row_contest_info['contestBottles']; ?></textarea></td>
    <td class="data"><em>Indicate the number of bottles, size, color, etc.<br>
    Copy and paste if needed.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Name of Shipping Location:</td>
    <td class="data"><input name="contestShippingName" type="text" value="<?php echo $row_contest_info['contestShippingName']; ?>" size="30" /></td>
    <td class="data">&nbsp;</td>
  </tr>
  <tr>
    <td class="dataLabel">Shipping Address:</td>
    <td class="data"><textarea name="contestShippingAddress"  cols="40" rows="7" class="mceNoEditor"><?php echo $row_contest_info['contestShippingAddress']; ?></textarea></td>
    <td class="data">&nbsp;</td>
  </tr>
</table>
<h3>Awards</h3>
<table>
  <tr>
    <td class="dataLabel">Awards Structure:</td>
    <td class="data"><textarea name="contestAwards" cols="70" rows="25"><?php echo $row_contest_info['contestAwards']; ?></textarea></td>
    <td class="data"><em>Indicate places for each category, BOS procedure, qualifying criteria, etc.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Best of Show Award:</td>
    <td class="data"><textarea name="contestBOSAward" cols="70" rows="25"><?php echo $row_contest_info['contestBOSAward']; ?></textarea></td>
    <td class="data"><em>Indicate whether the Best of Show winner will receive a special award (e.g., a pro-am brew with a sponsoring brewery, etc.).</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Complete Winners List:</td>
    <td class="data"><textarea name="contestWinnersComplete" cols="70" rows="25"><?php echo $row_contest_info['contestWinnersComplete']; ?></textarea></td>
    <td class="data"><em>Provide a complete winners list detailing the winners of each table, round, etc. This can be exported from HCCP in HTML format and pasted here.</em></td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  	<td colspan="2" class="data"><input name="submit" type="submit" class="button" value="Submit"></td>
  </tr>
</table>
</form>
