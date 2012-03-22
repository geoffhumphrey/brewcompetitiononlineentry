<form method="post" action="includes/process.inc.php?action=<?php if ($section == "step4") echo "add"; else echo "edit"; ?>&amp;dbTable=contest_info&amp;id=1" name="form1" onSubmit="return CheckRequiredFields()">
<?php if ($section != "step4") { ?>
<h2>Competition Info</h2>
<div class="adminSubNavContainer">
	<span class="adminSubNav">
    	<span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a href="index.php?section=admin">Back to Admin</a>
    </span>
</div>
<?php } ?>
<?php if ($section == "step4") { 
$query_name = "SELECT brewerFirstName,brewerLastName,brewerEmail FROM brewer WHERE uid='1'";
$name = mysql_query($query_name, $brewing) or die(mysql_error());
$row_name = mysql_fetch_assoc($name);
?>
<h3>Contact</h3>
<table>
  <tr>
    <td class="dataLabel">Competition Coordinator's First Name:</td>
    <td class="data"><input name="contactFirstName" type="text" class="submit" size="50" maxlength="255" value="<?php echo $row_name['brewerFirstName']; ?>"></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Competition Coordinator's Last Name:</td>
    <td class="data"><input name="contactLastName" type="text" class="submit" size="50" maxlength="255" value="<?php echo $row_name['brewerLastName']; ?>"></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Contact Email:</td>
    <td class="data"><input name="contactEmail" type="text" class="submit" size="50" maxlength="255" value="<?php echo $row_name['brewerEmail']; ?>"></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
  	<td colspan="3"><em>*You will be able to enter more contact names after set up via the Administration area.</em></td>
  </tr>
</table>
<input type="hidden" name="contactPosition" value="Competition Coordinator" />
<?php } ?>
<?php if (($section != "step4") && (get_contact_count() == 0)) { ?>
<div class="error">Contact information for your competition has not been set up yet. Would you like to <a href="index.php?section=admin&go=contacts">add a contact</a>?</div>
<?php } ?>
<h3>General</h3>
<table>
  <tr>
    <td class="dataLabel">Competition Name:</td>
    <td class="data"><input name="contestName" type="text" class="submit" size="50" maxlength="255" value="<?php echo $row_contest_info['contestName']; ?>"></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">BJCP Competition ID:</td>
    <td class="data"><input name="contestID" type="text" class="submit" size="10" maxlength="255" value="<?php echo $row_contest_info['contestID']; ?>"></td>
    <td class="data">&nbsp;</td>
  </tr>
  <tr>
    <td class="dataLabel">Hosted By:</td>
    <td class="data"><input name="contestHost" type="text" class="submit" size="50" maxlength="255" value="<?php echo $row_contest_info['contestHost']; ?>"></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Host Location:</td>
    <td class="data"><input name="contestHostLocation" type="text" class="submit" size="50" maxlength="255" value="<?php echo $row_contest_info['contestHostLocation']; ?>"></td>
    <td class="data">&nbsp;</td>
  </tr>
  <tr>
    <td class="dataLabel">Host Website Address:</td>
    <td class="data"><input name="contestHostWebsite" type="text" class="submit" size="50" maxlength="255" value="<?php echo $row_contest_info['contestHostWebsite']; ?>"></td>
    <td class="data"><em>Provide the entire website address including the http://</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Competition Logo File Name:</td>
    <td class="data"><input name="contestLogo" type="text" class="submit" size="50" maxlength="255" value="<?php echo $row_contest_info['contestLogo']; ?>" />
    <br /><br /><span class="icon"><img src="images/picture_add.png" ></span><a href="admin/upload.admin.php?KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=800" title="Upload Competition Logo Image" class="thickbox">Upload Logo Image</a></td>
    <td class="data"><em>Provide the exact name of the file (e.g., logo.jpg).</em></td>
  </tr>
</table>
<h3>Entry and Registration</h3>
<table>
  <tr>
    <td class="dataLabel">Entry Window Open:</td>
    <td class="data"><input name="contestEntryOpen" type="text" class="submit" size="20" onfocus="showCalendarControl(this);" value="<?php echo $row_contest_info['contestEntryOpen']; ?>" /></td>
    <td class="data"><span class="required">Required</span> <em>The date entries must be received at drop-off and mail-in locations.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Entry Window Close:</td>
    <td class="data"><input name="contestEntryDeadline" type="text" class="submit" size="20" onfocus="showCalendarControl(this);" value="<?php echo $row_contest_info['contestEntryDeadline']; ?>"></td>
    <td class="data"><span class="required">Required</span> 
      <em>The date entries must be received at drop-off and mail-in locations.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Registration Open:</td>
    <td class="data"><input name="contestRegistrationOpen" type="text" class="submit" size="20" onfocus="showCalendarControl(this);" value="<?php echo $row_contest_info['contestRegistrationOpen']; ?>" /></td>
    <td class="data"><span class="required">Required </span><em>The date the system will automatically open registrations.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Registration Close:</td>
    <td class="data"><input name="contestRegistrationDeadline" type="text" class="submit" size="20" onfocus="showCalendarControl(this);" value="<?php echo $row_contest_info['contestRegistrationDeadline']; ?>"></td>
    <td class="data"><span class="required">Required </span><em>The date the system will automatically close registrations. For example, if you want to accept registrations through January 1, 2012, the registration deadline should be January 2, 2012 (closes at midnight).</em></td>
  </tr>
</table>
<h3>Rules</h3>
<table>
  <tr>
    <td class="dataLabel">Competition Rules:</td>
    <td class="data">
    	<textarea name="contestRules" cols="70" rows="15">
		<?php if ($section != "step4") echo $row_contest_info['contestRules']; else { ?>
        <p>This competition is AHA sanctioned and open to any amateur homebrewer age 21 or older.</p>
		<p>All mailed entries must <strong>received </strong>at the mailing location by the entry deadline - please allow for shipping time.</p>
		<p>All entries will be picked up from drop-off locations the day of the entry deadline.</p>
		<p>All entries must be handcrafted products, containing ingredients available to the general public, and made using private equipment by hobbyist brewers (i.e., no use of commercial facilities or Brew on Premises operations, supplies, etc.).</p>
		<p>The competition organizers are not responsible for mis-categorized entries, mailed entries that are not received by the entry deadline, or entries that arrived damaged.</p>
		<p>The competition organizers reserve the right to combine styles for judging and to restructure awards as needed depending upon the quantity and quality of entries.</p>
		<p>Qualified judging of all entries is the primary goal of our event. Judges will evaluate and score each entry. The average of the scores will rank each entry in its category. Each flight will have at least one BJCP judge.</p>
		<p>Brewers are not limited to one entry in each category but may only enter each subcategory once. For example, participants may enter a Belgian Pale (16B) and Belgian Saison (16C), but may not enter two Saisons, even if they are different brews.</p>
		<p>Categories with a small number of entries may be combined at the discretion of the competition organizers. The Competition Coordinator or other qualified person will review elements of beer categories and styles with each panel prior to judging.</p>
		<p>The Best of Show judging will be determined by a Blue Ribbon Panel based on a second judging of the top winners.</p>
		<p>Bottles will not be returned to entrants.</p>
        <?php } ?>
        </textarea>
    </td>
    <td class="data"><em>Edit the provided general rules text as needed.</em></td>
  </tr>
</table>
<h3>Entries</h3>
<table class="dataTable">
  <tr>
    <td class="dataLabel">Entry Fee:</td>
    <td class="data"><?php echo $row_prefs['prefsCurrency']; ?> <input name="contestEntryFee" type="text" class="submit" size="5" maxlength="10" value="<?php echo $row_contest_info['contestEntryFee']; ?>"></td>
    <td class="data style1"> <span class="required">Required </span>Fee for a single entry (<?php echo $row_prefs['prefsCurrency']; ?>) - please enter a zero (0) for a free entry fee.</td>
  </tr>
  <tr>
    <td class="dataLabel bdr1B_dashed">Entry Fee Cap:</td>
    <td class="data bdr1B_dashed"><?php echo $row_prefs['prefsCurrency']; ?> <input name="contestEntryCap" type="text" class="submit" size="5" maxlength="10" value="<?php echo $row_contest_info['contestEntryCap']; ?>"></td>
    <td class="data bdr1B_dashed"><em>Useful for competitions with "unlimited" entries for a single fee (e.g., <?php echo $row_prefs['prefsCurrency']; ?>X for the first X number of entries, <?php echo $row_prefs['prefsCurrency']; ?>X for unlimited entries, etc.). Enter the maximum amount for each entrant. Leave blank if no cap.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Discount Multiple Entries:</td>
    <td nowrap="nowrap" class="data">
    <input type="radio" name="contestEntryFeeDiscount" value="Y" id="contestEntryFeeDiscount_0"  <?php if ($row_contest_info['contestEntryFeeDiscount'] == "Y") echo "CHECKED"; ?> /> 
    Yes&nbsp;&nbsp;
    <input type="radio" name="contestEntryFeeDiscount" value="N" id="contestEntryFeeDiscount_1" <?php if ($row_contest_info['contestEntryFeeDiscount'] == "N") echo "CHECKED"; if ($section == "step4") echo "CHECKED"; ?>/> 
    No    </td>
    <td class="data"><em>Designate Yes or No if your competition offers a discounted entry fee after a certain number is reached.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Minimum Entries for Discount:</td>
    <td class="data"><input name="contestEntryFeeDiscountNum" type="text" class="submit" size="5" maxlength="10" value="<?php echo $row_contest_info['contestEntryFeeDiscountNum']; ?>"></td>
    <td class="data"><em>The entry threshold participants must exceed to take advantage of the per entry fee discount (designated below). If no, discounted fee exists, leave blank.</em></td>
  </tr>
  <tr>
    <td class="dataLabel bdr1B_dashed">Discounted Entry Fee:</td>
    <td class="data bdr1B_dashed"><?php echo $row_prefs['prefsCurrency']; ?> <input name="contestEntryFee2" type="text" class="submit" size="5" maxlength="10" value="<?php echo $row_contest_info['contestEntryFee2']; ?>"></td>
    <td class="data bdr1B_dashed"><em>Fee for a single, </em>discounted<em> entry (<?php echo $row_prefs['prefsCurrency']; ?>).</em></td>
  </tr>
   <tr>
    <td class="dataLabel">Member Discount Password:</td>
    <td class="data"><input name="contestEntryFeePassword" type="text" class="submit" size="10" maxlength="30" value="<?php echo $row_contest_info['contestEntryFeePassword']; ?>"></td>
    <td class="data"><em>Designate a password for participants to enter to receive discounted entry fees. Useful if your competition provides a discount for members of the sponsoring club(s).</em></td>
  </tr>
  <tr>
    <td class="dataLabel bdr1B_dashed">Member Discount Fee:</td>
    <td class="data bdr1B_dashed"><?php echo $row_prefs['prefsCurrency']; ?> <input name="contestEntryFeePasswordNum" type="text" class="submit" size="5" maxlength="10" value="<?php echo $row_contest_info['contestEntryFeePasswordNum']; ?>"></td>
    <td class="data bdr1B_dashed"><em>Fee for a single, </em>discounted<em> member entry (<?php echo $row_prefs['prefsCurrency']; ?>). If you wish the member discount to be free, enter a zero (0). Leave blank for no discount.</em></td>
  </tr>
</table>
<table class="dataTable">
  <tr>
    <td class="dataLabel bdr1B_dashed">Bottle Acceptance Rules:</td>
    <td class="data bdr1B_dashed">
    	<textarea name="contestBottles" cols="70" rows="15"><?php if ($section != "step4") echo $row_contest_info['contestBottles']; else { ?>
        <p>Each entry will consist of 12 to 22 ounce capped bottles or corked bottles that are void of all identifying information, including labels and embossing. Printed caps are allowed, but must be blacked out completely.</p>
		<p>12oz brown glass bottles are preferred; however, green and clear glass will be accepted. Swing top bottles will likewise be accepted as well as corked bottles.</p>
		<p>Bottles will not be returned to contest entrants.</p>
		<p>Completed entry forms and recipe sheets must be submitted with all entries, and can be printed directly from this website. Entry forms should be attached to bottles with a rubber band only; glue and/or tape are unacceptable.</p>
		<p>Please fill out the entry forms completely. Be meticulous about noting any special ingredients that must be specified per the 2008 BJCP Style Guidelines. Failure to note such ingredients may impact the judges' scoring of your entry.</p>
		<p>Brewers are not limited to one entry in each category but may only enter each subcategory once.</p>
		<?php } ?>
        </textarea>
    </td>
    <td class="data bdr1B_dashed"><em>Indicate the number of bottles, size, color, etc. Edit default text as needed.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Name of Shipping Location:</td>
    <td class="data"><input name="contestShippingName" type="text" class="submit" value="<?php echo $row_contest_info['contestShippingName']; ?>" size="30" /></td>
    <td class="data">&nbsp;</td>
  </tr>
  <tr>
    <td class="dataLabel">Shipping Address:</td>
    <td class="data"><input name="contestShippingAddress" type="text" class="submit mceNoEditor" value="<?php echo $row_contest_info['contestShippingAddress']; ?>" size="50" /></td>
    <td class="data">&nbsp;</td>
  </tr>
</table>
<h3>Awards</h3>
<table>
  <tr>
    <td class="dataLabel">Awards Date:</td>
    <td class="data"><input name="contestAwardsLocDate" type="text" class="submit" size="20" onfocus="showCalendarControl(this);" value="<?php echo $row_contest_info['contestAwardsLocDate']; ?>"></td>
    <td class="data"><em>Provide even if the date of judging is the same.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Awards Start Time:</td>
    <td class="data"><input name="contestAwardsLocTime" type="text" class="submit" size="30" value="<?php echo $row_contest_info['contestAwardsLocTime']; ?>"></td>
    <td class="data"><em>The approximate time the awards ceremony will begin.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Awards Location Name:</td>
    <td class="data"><input name="contestAwardsLocName" type="text" class="submit" size="30" value="<?php echo $row_contest_info['contestAwardsLocName']; ?>"></td>
    <td class="data"><em>Provide the name of the awards location.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Awards Location Address:</td>
    <td class="data"><input name="contestAwardsLocation" type="text" class="submit mceNoEditor" value="<?php echo $row_contest_info['contestAwardsLocation']; ?>" size="50" /></td>
    <td class="data"><em>Provide the address of the award location. The more complete (e.g., street address, city, state, zip) the better.</em></td>
  </tr> 
</table>
<table>
  <tr>
    <td class="dataLabel">Awards Structure:</td>
    <td class="data">
    <textarea name="contestAwards" class="submit" cols="70" rows="15">
		<?php if ($section != "step4") echo $row_contest_info['contestAwards']; else { ?>
        <p>The awards ceremony will take place once judging is completed.</p>
		<p>Places will be awarded to 1st, 2nd, and 3rd place in each category/table.</p>
		<p>The 1st place entry in each category will advance to the Best of Show (BOS) round with a single, overall Best of Show beer selected.</p>
		<p>Additional prizes may be awarded to those winners present at the awards ceremony at the discretion of the competition organizers.</p>
        <?php } ?>
    </textarea>
    </td>
    <td class="data"><em>Indicate places for each category, BOS procedure, qualifying criteria, etc. Edit default text as needed.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Best of Show Award:</td>
    <td class="data"><textarea name="contestBOSAward" class="submit" cols="70" rows="15"><?php echo $row_contest_info['contestBOSAward']; ?></textarea></td>
    <td class="data"><em>Indicate whether the Best of Show winner will receive a special award (e.g., a pro-am brew with a sponsoring brewery, etc.).</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Circuit Qualifying Events:</td>
    <td class="data"><textarea name="contestCircuit" class="submit" cols="70" rows="15"><?php echo $row_contest_info['contestCircuit']; ?></textarea></td>
    <td class="data"><em>Indicate whether your competition is a qualifier for any national or regional competitions.</em></td>
  </tr>
</table>
<?php if ($section != "step4") { ?>
<h3>Winners List</h3>
<table class="dataTable">
  <tr>
    <td class="dataLabel">Complete Winners List:</td>
    <td class="data"><textarea name="contestWinnersComplete" class="submit" cols="70" rows="15"><?php echo $row_contest_info['contestWinnersComplete']; ?></textarea><br /><a href="javascript:toggleEditor('contestWinnersComplete');"></a></td>
    <td class="data"><em>Provide a complete winners list detailing the winners of each table, round, etc. This can be exported from HCCP in <strong>HTML format</strong> and pasted here.</em><p class="required">To paste raw HTML code, use this link: <a href="javascript:toggleEditor('contestWinnersComplete');">Add/Remove Editor</a>.</p>
      <p>If you paste using the editor to the left, most HTML tags will be stripped out and the original formatting will be lost.</p></td>
  </tr>
</table>
<?php } ?>
<p><input name="submit" type="submit" class="button" value="Submit"></p>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default"); ?>">
</form>
