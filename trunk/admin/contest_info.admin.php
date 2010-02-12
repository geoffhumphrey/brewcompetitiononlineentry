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
    <td class="data">Provide the entire website address including the http://</td>
  </tr>
  <tr>
    <td class="dataLabel">Competition Logo File Name:</td>
    <td class="data"><input name="contestLogo" type="text" size="50" maxlength="255" value="<?php echo $row_contest_info['contestLogo']; ?>" />
    <br /><br /><span class="icon"><img src="images/picture_add.png" align="absmiddle"></span><a href="admin/upload.admin.php?KeepThis=true&TB_iframe=true&height=350&width=800" title="Upload Competition Logo Image" class="thickbox">Upload Logo Image</a></td>
    <td class="data">Provide the <em>exact</em> name of the file (e.g., logo.jpg)</td>
  </tr>
</table>
<h3>Deadlines</h3>
<table>
  <tr>
    <td class="dataLabel">Entry Deadline:</td>
    <td class="data"><input name="contestEntryDeadline" type="text" size="20" onfocus="showCalendarControl(this);" value="<?php echo $row_contest_info['contestEntryDeadline']; ?>"></td>
    <td class="data"><span class="required">Required</span> 
      The date entries must be received at drop-off and mail-in locations</td>
  </tr>
  <tr>
    <td class="dataLabel">Registration Deadline:</td>
    <td class="data"><input name="contestRegistrationDeadline" type="text" size="20" onfocus="showCalendarControl(this);" value="<?php echo $row_contest_info['contestRegistrationDeadline']; ?>"></td>
    <td class="data"><span class="required">Required </span>The date the system will automatically close registrations</td>
  </tr>
</table>
<h3>Judging Dates and Locations</h3>
<table>
  <tr>
    <td class="dataLabel">Judging Date 1:</td>
    <td class="data"><input name="contestDate" type="text" size="20" onfocus="showCalendarControl(this);" value="<?php echo $row_contest_info['contestDate']; ?>"></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Judging Location 1 Name:</td>
    <td class="data"><input name="contestJudgingLocName" size="30"><?php echo $row_contest_info['contestJudgingLocName']; ?></textarea></td>
    <td class="data">Provide the name of the judging location</td>
  </tr>
  <tr>
    <td class="dataLabel">Judging Location 1 Address:</td>
    <td class="data"><textarea name="contestJudgingLocation" cols="70" rows="15"><?php echo $row_contest_info['contestJudgingLocation']; ?></textarea></td>
    <td class="data">Provide the street address, city, and zip code</td>
  </tr>
  <tr>
    <td class="dataLabel">Judging Date 2:</td>
    <td class="data"><input name="contestDate2" type="text" size="20" onfocus="showCalendarControl(this);" value="<?php echo $row_contest_info['contestDate2']; ?>"></td>
    <td class="data">Leave blank if none</td>
  </tr>
  <tr>
    <td class="dataLabel">Judging Location 2 Name:</td>
    <td class="data"><input name="contestJudgingLocName2" size="30"><?php echo $row_contest_info['contestJudgingLocName2']; ?></textarea></td>
    <td class="data">Provide the name of the 2nd judging location (leave blank if none)</td>
  </tr>
  <tr>
    <td class="dataLabel">Judging Location 2 Address:</td>
    <td class="data"><textarea name="contestJudgingLocation2" cols="70" rows="15"><?php echo $row_contest_info['contestJudgingLocation2']; ?></textarea></td>
    <td class="data">Provide the street address, city, and zip code</td>
  </tr>
  <tr>
    <td class="dataLabel">Judging Date 3:</td>
    <td class="data"><input name="contestDate3" type="text" size="20" onfocus="showCalendarControl(this);" value="<?php echo $row_contest_info['contestDate3']; ?>"></td>
    <td class="data">Leave blank if none</td>
  </tr>
  <tr>
    <td class="dataLabel">Judging Location 3 Name:</td>
    <td class="data"><input name="contestJudgingLocName3" size="30"><?php echo $row_contest_info['contestJudgingLocName3']; ?></textarea></td>
    <td class="data">Provide the name of the 3rd judging location (leave blank if none)</td>
  </tr>
  <tr>
    <td class="dataLabel">Judging Location 3 Address:</td>
    <td class="data"><textarea name="contestJudgingLocation3" cols="70" rows="15"><?php echo $row_contest_info['contestJudgingLocation3']; ?></textarea></td>
    <td class="data">Provide the street address, city, and zip code</td>
  </tr>
  <tr>
    <td class="dataLabel">Judging Date 4:</td>
    <td class="data"><input name="contestDate4" type="text" size="20" onfocus="showCalendarControl(this);" value="<?php echo $row_contest_info['contestDate4']; ?>"></td>
    <td class="data">Leave blank if none</td>
  </tr>
  <tr>
    <td class="dataLabel">Judging Location 4 Name:</td>
    <td class="data"><input name="contestJudgingLocName3" size="30"><?php echo $row_contest_info['contestJudgingLocName3']; ?></textarea></td>
    <td class="data">Provide the name of the 4th judging location (leave blank if none)</td>
  </tr>
  <tr>
    <td class="dataLabel">Judging Location 4 Address:</td>
    <td class="data"><textarea name="contestJudgingLocation4" cols="70" rows="15"><?php echo $row_contest_info['contestJudgingLocation4']; ?></textarea></td>
    <td class="data">Provide the street address, city, and zip code</td>
  </tr>
  <tr>
    <td class="dataLabel">Judging Date 5:</td>
    <td class="data"><input name="contestDate5" type="text" size="20" onfocus="showCalendarControl(this);" value="<?php echo $row_contest_info['contestDate5']; ?>"></td>
    <td class="data">Leave blank if none</td>
  </tr>
  <tr>
    <td class="dataLabel">Judging Location 5 Name:</td>
    <td class="data"><input name="contestJudgingLocName5" size="30"><?php echo $row_contest_info['contestJudgingLocName5']; ?></textarea></td>
    <td class="data">Provide the name of the 5th judging location (leave blank if none)</td>
  </tr>
  <tr>
    <td class="dataLabel">Judging Location 5 Address:</td>
    <td class="data"><textarea name="contestJudgingLocation5" cols="70" rows="15"><?php echo $row_contest_info['contestJudgingLocation5']; ?></textarea></td>
    <td class="data">Provide the street address, city, and zip code</td>
  </tr>
</table>
<h3>Awards</h3>
<table>
  <tr>
    <td class="dataLabel">Awards Location Name:</td>
    <td class="data"><input name="contestAwardsLocName" size="30"><?php echo $row_contest_info['contestAwardsLocName']; ?></textarea></td>
    <td class="data">Provide the name of the awards location.</td>
  </tr>
  <tr>
    <td class="dataLabel">Awards Location Address:</td>
    <td class="data"><textarea name="contestAwardsLocation" cols="70" rows="25"><?php echo $row_contest_info['contestAwardsLocation']; ?></textarea></td>
    <td class="data">If different from any judging location</td>
  </tr> 
</table>
<h3>Rules</h3>
<table>
  <tr>
    <td class="dataLabel">Compeition Rules:</td>
    <td class="data"><textarea name="contestRules" cols="70" rows="25"><?php echo $row_contest_info['contestRules']; ?></textarea></td>
    <td class="data">Copy and paste if needed</td>
  </tr>
</table>
<h3>Entries</h3>
<table>
  <tr>
    <td class="dataLabel">Entry Fee:</td>
    <td class="data"><?php echo $row_prefs['prefsCurrency']; ?> <input name="contestEntryFee" type="text" size="5" maxlength="10" value="<?php echo $row_contest_info['contestEntryFee']; ?>"></td>
    <td class="data"> <span class="required">Required </span>Fee for a single entry (<?php echo $row_prefs['prefsCurrency']; ?>) - please enter a zero (0) for a free entry fee.</td>
  </tr>
  <tr>
    <td class="dataLabel">Entry Fee Cap:</td>
    <td class="data"><?php echo $row_prefs['prefsCurrency']; ?> <input name="contestEntryCap" type="text" size="5" maxlength="10" value="<?php echo $row_contest_info['contestEntryCap']; ?>"></td>
    <td class="data">Useful for competitions with "unlimited" entries for a single fee (e.g., $5 for the first entry, $10 for unlimited, etc.). Enter the maximum amount for each entrant. Leave blank if no cap.</td>
  </tr>
  <tr>
    <td class="dataLabel">Discount Multiple Entries:</td>
    <td nowrap="nowrap" class="data">
    <input type="radio" name="contestEntryFeeDiscount" value="Y" id="contestEntryFeeDiscount_0"  <?php if ($row_contest_info['contestEntryFeeDiscount'] == "Y") echo "CHECKED"; ?> /> 
    Yes&nbsp;&nbsp;
    <input type="radio" name="contestEntryFeeDiscount" value="N" id="contestEntryFeeDiscount_1" <?php if ($row_contest_info['contestEntryFeeDiscount'] == "N") echo "CHECKED"; ?>/> 
    No    </td>
    <td class="data">Designate Yes or No if your competition offers a discounted entry fee after a certain number is reached.</td>
  </tr>
  <tr>
    <td class="dataLabel">Minimum Entries for Discount:</td>
    <td class="data"><input name="contestEntryFeeDiscountNum" type="text" size="5" maxlength="10" value="<?php echo $row_contest_info['contestEntryFeeDiscountNum']; ?>"></td>
    <td class="data">The entry threshold participants must exceed to take advantage of the per entry fee discount (designated below). If no, discounted fee exists, leave blank.</td>
  </tr>
  <tr>
    <td class="dataLabel">Discounted Entry Fee:</td>
    <td class="data"><?php echo $row_prefs['prefsCurrency']; ?> <input name="contestEntryFee2" type="text" size="5" maxlength="10" value="<?php echo $row_contest_info['contestEntryFee2']; ?>"></td>
    <td class="data">Fee for a single, <em>discounted</em> entry (<?php echo $row_prefs['prefsCurrency']; ?>).</td>
  </tr>
</table>
<table>
  <tr>
    <td class="dataLabel">Categories Accepted:</td>
    <td class="data"><textarea name="contestCategories" cols="70" rows="25"><?php echo $row_contest_info['contestCategories']; ?></textarea></td>
    <td class="data">&nbsp;</td>
  </tr>
  <tr>
    <td class="dataLabel">Bottle Acceptance Rules:</td>
    <td class="data"><textarea name="contestBottles" cols="70" rows="25"><?php echo $row_contest_info['contestBottles']; ?></textarea></td>
    <td class="data">Indicate the number of bottles, size, color, etc.<br>
    Copy and paste if needed.</td>
  </tr>
  <tr>
    <td class="dataLabel">Shipping Address:</td>
    <td class="data"><textarea name="contestShippingAddress" cols="70" rows="25"><?php echo $row_contest_info['contestShippingAddress']; ?></textarea></td>
    <td class="data">&nbsp;</td>
  </tr>
  <tr>
    <td class="dataLabel">Drop Off Locations:</td>
    <td class="data"><textarea name="contestDropOff" cols="70" rows="25"><?php echo $row_contest_info['contestDropOff']; ?></textarea></td>
    <td class="data">&nbsp;</td>
  </tr>
</table>
<h3>Awards</h3>
<table>
  <tr>
    <td class="dataLabel">Awards Structure:</td>
    <td class="data"><textarea name="contestAwards" cols="70" rows="25"><?php echo $row_contest_info['contestAwards']; ?></textarea></td>
    <td class="data">Indicate places for each category, BOS procedure, qualifying criteria, etc.</td>
  </tr>
  <tr>
    <td class="dataLabel">Best of Show Award:</td>
    <td class="data"><textarea name="contestBOSAward" cols="70" rows="25"><?php echo $row_contest_info['contestBOSAward']; ?></textarea></td>
    <td class="data">Indicate whether the Best of Show winner will receive a special award (e.g., a pro-am brew with a sponsoring brewery, etc.).</td>
  </tr>
  <tr>
    <td class="dataLabel">Complete Winners List:</td>
    <td class="data"><textarea name="contestWinnersComplete" cols="70" rows="25"><?php echo $row_contest_info['contestWinnersComplete']; ?></textarea></td>
    <td class="data">Provide a complete winners list detailing the winners of each table, round, etc. This can be exported from HCCP in HTML format and pasted here.</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  	<td colspan="2" class="data"><input name="submit" type="submit" value="Submit"></td>
  </tr>
</table>
</form>
