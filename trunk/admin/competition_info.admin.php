<form method="post" action="<?php echo $base_url; ?>includes/process.inc.php?action=<?php if ($section == "step4") echo "add"; else echo "edit"; ?>&amp;dbTable=<?php echo $prefix; ?>contest_info&amp;id=1" name="form1" onSubmit="return CheckRequiredFields()">
<?php if ($section != "step4") { ?>
<h2>Competition Info</h2>
<div class="adminSubNavContainer">
	<span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>index.php?section=admin">Back to Admin Dashboard</a>
    </span>
</div>
<?php } ?>
<p><input name="submit" type="submit" class="button" value="Update Competition Info"></p>
<?php if ($section == "step4") { 
$query_name = "SELECT brewerFirstName,brewerLastName,brewerEmail FROM $brewer_db_table WHERE uid='1'";
$name = mysql_query($query_name, $brewing) or die(mysql_error());
$row_name = mysql_fetch_assoc($name);
?>
<h3>Contact</h3>
<table>
  <tr>
    <td class="dataLabel">Competition Coordinator's First Name:</td>
    <td class="data"><input name="contactFirstName" type="text" class="submit" size="50" maxlength="255" value="<?php echo $_SESSION['brewerFirstName']; ?>"></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Competition Coordinator's Last Name:</td>
    <td class="data"><input name="contactLastName" type="text" class="submit" size="50" maxlength="255" value="<?php echo $_SESSION['brewerLastName']; ?>"></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Contact Email:</td>
    <td class="data"><input name="contactEmail" type="text" class="submit" size="50" maxlength="255" value="<?php echo $_SESSION['brewerEmail']; ?>"></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
  	<td colspan="3"><em>*You will be able to enter more contact names after set up via the Administration area.</em></td>
  </tr>
</table>
<input type="hidden" name="contactPosition" value="Competition Coordinator" />
<?php } ?>
<?php if (($section != "step4") && (get_contact_count() == 0)) { ?>
<div class="error">Contact information for your competition has not been set up yet. Would you like to <a href="<?php echo $base_url; ?>index.php?section=admin&go=contacts">add a contact</a>?</div>
<?php } 

$query_contest_info = sprintf("SELECT * FROM %s WHERE id=1", $prefix."contest_info");
$contest_info = mysql_query($query_contest_info, $brewing) or die(mysql_error());
$row_contest_info = mysql_fetch_assoc($contest_info);
$totalRows_contest_info = mysql_num_rows($contest_info); 

?>
<h3>General</h3>
<table>
  <tr>
    <td class="dataLabel">Competition Name:</td>
    <td class="data"><input name="contestName" type="text" class="submit" size="50" maxlength="255" value="<?php echo $_SESSION['contestName']; ?>"></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">BJCP Competition ID:</td>
    <td class="data"><input name="contestID" type="text" class="submit" size="10" maxlength="255" value="<?php echo $_SESSION['contestID']; ?>"></td>
    <td class="data">&nbsp;</td>
  </tr>
  <tr>
    <td class="dataLabel">Hosted By:</td>
    <td class="data"><input name="contestHost" type="text" class="submit" size="50" maxlength="255" value="<?php echo $_SESSION['contestHost']; ?>"></td>

    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Host Location:</td>
    <td class="data"><input name="contestHostLocation" type="text" class="submit" size="50" maxlength="255" value="<?php echo $_SESSION['contestHostLocation']; ?>"></td>
    <td class="data">&nbsp;</td>
  </tr>
  <tr>
    <td class="dataLabel">Host Website Address:</td>
    <td class="data"><input name="contestHostWebsite" type="text" class="submit" size="50" maxlength="255" value="<?php echo $_SESSION['contestHostWebsite']; ?>"></td>
    <td class="data"><em>Provide the entire website address including the http://</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Competition Logo File Name:</td>
    <td class="data"><input name="contestLogo" type="text" class="submit" size="50" maxlength="255" value="<?php echo $_SESSION['contestLogo']; ?>" />
    <br /><br /><span class="icon"><img src="<?php echo $base_url; ?>images/picture_add.png" ></span><a href="admin/upload.admin.php" title="Upload Competition Logo Image" id="modal_window_link">Upload Logo Image</a></td>
    <td class="data"><em>Provide the exact name of the file (e.g., logo.jpg).</em></td>
  </tr>
</table>
<h3>Entry and Registration</h3>
<script>
	$(function() {
		$( "#contestEntryOpen" ).datepicker({ dateFormat: 'yy-mm-dd', showOtherMonths: true, selectOtherMonths: true, changeMonth: true, changeYear: true });;
		$( "#contestEntryDeadline" ).datepicker({ dateFormat: 'yy-mm-dd', showOtherMonths: true, selectOtherMonths: true, changeMonth: true, changeYear: true });;
		$( "#contestRegistrationOpen" ).datepicker({ dateFormat: 'yy-mm-dd', showOtherMonths: true, selectOtherMonths: true, changeMonth: true, changeYear: true });;
		$( "#contestRegistrationDeadline" ).datepicker({ dateFormat: 'yy-mm-dd', showOtherMonths: true, selectOtherMonths: true, changeMonth: true, changeYear: true });;
		$( "#contestJudgeOpen" ).datepicker({ dateFormat: 'yy-mm-dd', showOtherMonths: true, selectOtherMonths: true, changeMonth: true, changeYear: true });;
		$( "#contestJudgeDeadline" ).datepicker({ dateFormat: 'yy-mm-dd', showOtherMonths: true, selectOtherMonths: true, changeMonth: true, changeYear: true });;
		$( "#contestAwardsLocDate" ).datepicker({ dateFormat: 'yy-mm-dd', showOtherMonths: true, selectOtherMonths: true, changeMonth: true, changeYear: true });;
		
		$('#contestEntryOpenTime').timepicker({ showPeriod: true, showLeadingZero: true });
		$('#contestEntryDeadlineTime').timepicker({ showPeriod: true, showLeadingZero: true });
		$('#contestRegistrationOpenTime').timepicker({ showPeriod: true, showLeadingZero: true });
		$('#contestRegistrationDeadlineTime').timepicker({ showPeriod: true, showLeadingZero: true });
		$('#contestJudgeOpenTime').timepicker({ showPeriod: true, showLeadingZero: true });
		$('#contestJudgeDeadlineTime').timepicker({ showPeriod: true, showLeadingZero: true });
		$('#contestAwardsLocTime').timepicker({ showPeriod: true, showLeadingZero: true });
		
	});
</script>

<table>
  <tr>
    <td class="dataLabel">Entry Window Open Date:</td>
    <td class="data">
    <input id="contestEntryOpen" name="contestEntryOpen" type="text" class="submit" size="20" value="<?php if ($section != "step4") echo
	getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestEntryOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date"); ?>" />
   </td>
    <td class="dataLabel">Time: </td>
    <td class="data"><input id="contestEntryOpenTime" name="contestEntryOpenTime" type="text" class="submit" size="10" value="<?php if ($section != "step4") echo
	getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestEntryOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "time"); ?>" /></td>
    <td class="data"><span class="required">Required</span> <em>The date/time drop-off and mail-in locations will begin receiving entries.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Entry Window Close Date:</td>
    <td class="data"><input id="contestEntryDeadline" name="contestEntryDeadline" type="text" class="submit" size="20" value="<?php if ($section != "step4") echo
	getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestEntryDeadline'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date"); ?>"></td>
    <td class="dataLabel">Time:</td>
    <td class="data"><input id="contestEntryDeadlineTime" name="contestEntryDeadlineTime" type="text" class="submit" size="10" value="<?php if ($section != "step4") echo
	getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestEntryDeadline'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "time"); ?>" /></td>
    <td class="data"><span class="required">Required</span> <em>The final date/time drop-off and mail-in locations will receive entries.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">General Registration Open Date:</td>
    <td class="data"><input id="contestRegistrationOpen" name="contestRegistrationOpen" type="text" class="submit" size="20" value="<?php if ($section != "step4") echo
	getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestRegistrationOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date"); ?>" /></td>
    <td class="dataLabel"> Time:</td>
    <td class="data"><input id="contestRegistrationOpenTime" name="contestRegistrationOpenTime" type="text" class="submit" size="10" value="<?php if ($section != "step4") echo
	getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestRegistrationOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "time"); ?>" /></td>
    <td class="data"><span class="required">Required </span><em>The date/time the system will automatically open registrations.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">General Registration Close Date:</td>
    <td class="data"><input id="contestRegistrationDeadline" name="contestRegistrationDeadline" type="text" class="submit" size="20"  value="<?php if ($section != "step4") echo
	getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestRegistrationDeadline'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date"); ?>"></td>
    <td class="dataLabel">Time:</td>
    <td class="data"><input id="contestRegistrationDeadlineTime" name="contestRegistrationDeadlineTime" type="text" class="submit" size="10" value="<?php if ($section != "step4") echo
	getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestRegistrationDeadline'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "time"); ?>" /></td>
    <td class="data"><span class="required">Required </span><em>The date/time the system will automatically close registrations.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Judge/Steward Registration Open Date:</td>
    <td class="data"><input id="contestJudgeOpen" name="contestJudgeOpen" type="text" class="submit" size="20" value="<?php if ($section != "step4") echo
	getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestJudgeOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date"); ?>" /></td>
    <td class="dataLabel">Time:</td>
    <td class="data"><input id="contestJudgeOpenTime" name="contestJudgeOpenTime" type="text" class="submit" size="10" value="<?php if ($section != "step4") echo
	getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestJudgeOpen'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "time"); ?>" /></td>
    <td class="data"><span class="required">Required </span><em>The date/time the system will automatically open judge/steward registrations.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Judge/Steward Registration Close Date:</td>
    <td class="data"><input id="contestJudgeDeadline" name="contestJudgeDeadline" type="text" class="submit" size="20"  value="<?php if ($section != "step4") echo
	getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestJudgeDeadline'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date"); ?>"></td>
    <td class="dataLabel">Time:</td>
    <td class="data"><input id="contestJudgeDeadlineTime" name="contestJudgeDeadlineTime" type="text" class="submit" size="10" value="<?php if ($section != "step4") echo
	getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestJudgeDeadline'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "time"); ?>" /></td>
    <td class="data"><span class="required">Required </span><em>The date/time the system will automatically close judge/steward registrations.</em></td>
  </tr>
</table>
<h3>Rules</h3>
<table>
  <tr>
    <td class="dataLabel">Competition Rules:</td>
    <td class="data">
    	<textarea name="contestRules" cols="90" rows="15">
		<?php if ($section != "step4") echo $_SESSION['contestRules']; else { ?>
        <p>This competition is AHA sanctioned and open to any amateur homebrewer age 21 or older.</p>
		<p>All mailed entries must <strong>received</strong> at the mailing location by the entry deadline - please allow for shipping time.</p>
		<p>All entries will be picked up from drop-off locations the day of the entry deadline.</p>
		<p>All entries must be handcrafted products, containing ingredients available to the general public, and made using private equipment by hobbyist brewers (i.e., no use of commercial facilities or Brew on Premises operations, supplies, etc.).</p>
		<p>The competition organizers are not responsible for mis-categorized entries, mailed entries that are not received by the entry deadline, or entries that arrived damaged.</p>
		<p>The competition organizers reserve the right to combine styles for judging and to restructure awards as needed depending upon the quantity and quality of entries.</p>
		<p>Qualified judging of all entries is the primary goal of our event. Judges will evaluate and score each entry. The average of the scores will rank each entry in its category. Each flight will have at least one BJCP judge.</p>
		<p>Brewers are not limited to one entry in each category but may only enter each subcategory once. For example, participants may enter a Belgian Pale (16B) and Belgian Saison (16C), but may not enter two Saisons, even if they are different brews.</p>
		<p>The competition committee reserves the right to combine categories based on number of entries. All possible effort will be made to combine similar styles. All brews in combined categories will be judged according to the style they were originally entered in.</p>
		<p>The Best of Show judging will be determined by a Best of Show panel based on a second judging of the top winners.</p>
		<p>Bottles will not be returned to entrants.</p>
       <?php } ?>
        </textarea>
    </td>
    <td class="data"><em>Edit the provided general rules text as needed.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">&nbsp;</td>
    <td class="data"><a href="javascript:toggleEditor('contestRules');">Disable Rich Text</a></td>
    <td class="data">&nbsp;</td>
  </tr>
</table>
<h3>Judge / Steward / Staff Information</h3>
<table>
  <tr>
    <td class="dataLabel">Volunteer Info:</td>
    <td class="data">
    	<textarea name="contestVolunteers" cols="90" rows="15">
		<?php if ($section != "step4") echo $_SESSION['contestVolunteers']; else { ?>
        <p>Volunteer information coming soon!</p>
        <?php } ?>
        </textarea>
    </td>
    <td class="data"><em>Provide info. regarding hotels, meals, speakers, etc.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">&nbsp;</td>
    <td class="data"><a href="javascript:toggleEditor('contestVolunteers');">Disable Rich Text</a></td>
    <td class="data">&nbsp;</td>
  </tr>
</table>
<h3>Entries</h3>
<table class="dataTable">
  <tr>
    <td class="dataLabel">Entry Fee:</td>
    <td class="data"><?php echo $_SESSION['prefsCurrency']; ?> <input name="contestEntryFee" type="text" class="submit" size="5" maxlength="10" value="<?php echo $_SESSION['contestEntryFee']; ?>"></td>
    <td class="data style1"> <span class="required">Required </span>Fee for a single entry (<?php echo $_SESSION['prefsCurrency']; ?>) - please enter a zero (0) for a free entry fee.</td>
  </tr>
  <tr>
    <td class="dataLabel">Entry Fee Cap:</td>
    <td class="data"><?php echo $_SESSION['prefsCurrency']; ?> <input name="contestEntryCap" type="text" class="submit" size="5" maxlength="10" value="<?php echo $_SESSION['contestEntryCap']; ?>"></td>
    <td class="data"><em>Useful for competitions with "unlimited" entries for a single fee (e.g., <?php echo $_SESSION['prefsCurrency']; ?>X for the first X number of entries, <?php echo $_SESSION['prefsCurrency']; ?>X for unlimited entries, etc.). Enter the maximum amount for each entrant. Leave blank if no cap.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Discount Multiple Entries:</td>
    <td nowrap="nowrap" class="data">
    <input type="radio" name="contestEntryFeeDiscount" value="Y" id="contestEntryFeeDiscount_0"  <?php if ($_SESSION['contestEntryFeeDiscount'] == "Y") echo "CHECKED"; ?> /> 
    Yes&nbsp;&nbsp;
    <input type="radio" name="contestEntryFeeDiscount" value="N" id="contestEntryFeeDiscount_1" <?php if ($_SESSION['contestEntryFeeDiscount'] == "N") echo "CHECKED"; if ($section == "step4") echo "CHECKED"; ?>/> 
    No    </td>
    <td class="data"><em>Designate Yes or No if your competition offers a discounted entry fee after a certain number is reached.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Minimum Entries for Discount:</td>
    <td class="data"><input name="contestEntryFeeDiscountNum" type="text" class="submit" size="5" maxlength="10" value="<?php echo $_SESSION['contestEntryFeeDiscountNum']; ?>"></td>
    <td class="data"><em>The entry threshold participants must exceed to take advantage of the per entry fee discount (designated below). If no, discounted fee exists, leave blank.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Discounted Entry Fee:</td>
    <td class="data"><?php echo $_SESSION['prefsCurrency']; ?> <input name="contestEntryFee2" type="text" class="submit" size="5" maxlength="10" value="<?php echo $_SESSION['contestEntryFee2']; ?>"></td>
    <td class="data"><em>Fee for a single, </em>discounted<em> entry (<?php echo $_SESSION['prefsCurrency']; ?>).</em></td>
  </tr>
   <tr>
    <td class="dataLabel">Member Discount Password:</td>
    <td class="data"><input name="contestEntryFeePassword" type="text" class="submit" size="10" maxlength="30" value="<?php echo $_SESSION['contestEntryFeePassword']; ?>"></td>
    <td class="data"><em>Designate a password for participants to enter to receive discounted entry fees. Useful if your competition provides a discount for members of the sponsoring club(s).</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Member Discount Fee:</td>
    <td class="data"><?php echo $_SESSION['prefsCurrency']; ?> <input name="contestEntryFeePasswordNum" type="text" class="submit" size="5" maxlength="10" value="<?php echo $_SESSION['contestEntryFeePasswordNum']; ?>"></td>
    <td class="data"><em>Fee for a single, </em>discounted<em> member entry (<?php echo $_SESSION['prefsCurrency']; ?>). If you wish the member discount to be free, enter a zero (0). Leave blank for no discount.</em></td>
  </tr>
</table>
<table class="dataTable">
  <tr>
    <td class="dataLabel">Bottle Acceptance Rules:</td>
    <td class="data">
    	<textarea name="contestBottles" cols="90" rows="15"><?php if ($section != "step4") echo $_SESSION['contestBottles']; else { ?>
        <p>Each entry will consist of 12 to 22 ounce capped bottles or corked bottles that are void of all identifying information, including labels and embossing. Printed caps are allowed, but must be blacked out completely.</p>
		<p>12oz brown glass bottles are preferred; however, green and clear glass will be accepted. Swing top bottles will likewise be accepted as well as corked bottles.</p>
		<p>Bottles will not be returned to contest entrants.</p>
		<p>Completed entry forms and recipe sheets must be submitted with all entries, and can be printed directly from this website. Entry forms should be attached to bottles with a rubber band only; glue and/or tape are unacceptable.</p>
		<p>Please fill out the entry forms completely. Be meticulous about noting any special ingredients that must be specified per the 2008 BJCP Style Guidelines. Failure to note such ingredients may impact the judges' scoring of your entry.</p>
		<p>Brewers are not limited to one entry in each category but may only enter each subcategory once.</p>
		<?php } ?>
        </textarea>
    </td>
    <td class="data"><em>Indicate the number of bottles, size, color, etc. Edit default text as needed.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">&nbsp;</td>
    <td class="data"><a href="javascript:toggleEditor('contestBottles');">Disable Rich Text</a></td>
    <td class="data">&nbsp;</td>
  </tr>
  <tr>
    <td class="dataLabel">Name of Shipping Location:</td>
    <td class="data"><input name="contestShippingName" type="text" class="submit" value="<?php echo $_SESSION['contestShippingName']; ?>" size="30" /></td>
    <td class="data">&nbsp;</td>
  </tr>
  <tr>
    <td class="dataLabel">Shipping Address:</td>
    <td class="data"><input name="contestShippingAddress" type="text" class="submit mceNoEditor" value="<?php echo $_SESSION['contestShippingAddress']; ?>" size="50" /></td>
    <td class="data">&nbsp;</td>
  </tr>
</table>
<h3>Awards</h3>
<br /><b>Warning</b>:  date() expects parameter 2 to be long, string given in <b>/home5/brewcomp/public_html/bcoetest/includes/date_time.inc.php</b> on line <b>98</b><br />
<table>
  <tr>
    <td class="dataLabel">Awards Date:</td>
    <td class="data"><input id="contestAwardsLocDate" name="contestAwardsLocDate" type="text" class="submit" size="20" value="<?php if ($section != "step4") echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestAwardsLocTime'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "date"); ?>"></td>
    <td class="data"><em>Provide even if the date of judging is the same.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Awards Start Time:</td>
    <td class="data"><input id="contestAwardsLocTime" name="contestAwardsLocTime" type="text" class="submit" size="10" value="<?php if ($section != "step4") echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['contestAwardsLocTime'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "system", "time"); ?>"></td>
    <td class="data"><em>The approximate time the awards ceremony will begin.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Awards Location Name:</td>
    <td class="data"><input name="contestAwardsLocName" type="text" class="submit" size="30" value="<?php echo $_SESSION['contestAwardsLocName']; ?>"></td>
    <td class="data"><em>Provide the name of the awards location.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Awards Location Address:</td>
    <td class="data"><input name="contestAwardsLocation" type="text" class="submit mceNoEditor" value="<?php echo $_SESSION['contestAwardsLocation']; ?>" size="50" /></td>
    <td class="data"><em>Provide the address of the award location. The more complete (e.g., street address, city, state, zip) the better.</em></td>
  </tr> 
</table>
<table>
  <tr>
    <td class="dataLabel">Awards Structure:</td>
    <td class="data">
    <textarea name="contestAwards" class="submit" cols="90" rows="15">
		<?php if ($section != "step4") echo $_SESSION['contestAwards']; else { ?>
        <p>The awards ceremony will take place once judging is completed.</p>
		<p>Places will be awarded to 1st, 2nd, and 3rd place in each category/table.</p>
		<p>The 1st place entry in each category will advance to the Best of Show (BOS) round with a single, overall Best of Show beer selected.</p>
		<p>Additional prizes may be awarded to those winners present at the awards ceremony at the discretion of the competition organizers.</p>
        <p>Both score sheets and awards will be available for pick up that night after the ceremony concludes.  Awards and score sheets not picked up will be mailed back to participants.  Results will be posted to the competition web site after the ceremony concludes.</p>
        <?php } ?>
    </textarea>
    </td>
    <td class="data"><em>Indicate places for each category, BOS procedure, qualifying criteria, etc. Edit default text as needed.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">&nbsp;</td>
    <td class="data"><a href="javascript:toggleEditor('contestAwards');">Disable Rich Text</a></td>
    <td class="data">&nbsp;</td>
  </tr>
  <tr>
    <td class="dataLabel">Best of Show Award:</td>
    <td class="data"><textarea name="contestBOSAward" class="submit" cols="90" rows="15"><?php echo $_SESSION['contestBOSAward']; ?></textarea></td>
    <td class="data"><em>Indicate whether the Best of Show winner will receive a special award (e.g., a pro-am brew with a sponsoring brewery, etc.).</em></td>
  </tr>
  <tr>
    <td class="dataLabel">&nbsp;</td>
    <td class="data"><a href="javascript:toggleEditor('contestBOSAward');">Disable Rich Text</a></td>
    <td class="data">&nbsp;</td>
  </tr>
  <tr>
    <td class="dataLabel">Circuit Qualifying Events:</td>
    <td class="data"><textarea name="contestCircuit" class="submit" cols="90" rows="15"><?php echo $_SESSION['contestCircuit']; ?></textarea></td>
    <td class="data"><em>Indicate whether your competition is a qualifier for any national or regional competitions.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">&nbsp;</td>
    <td class="data"><a href="javascript:toggleEditor('contestCircuit');">Disable Rich Text</a></td>
    <td class="data">&nbsp;</td>
  </tr>
</table>
<?php if ($section != "step4") { ?>
<h3>Winners List</h3>
<table class="dataTable">
  <tr>
    <td class="dataLabel">Complete Winners List:</td>
    <td class="data"><textarea name="contestWinnersComplete" class="submit" cols="90" rows="15"><?php echo $_SESSION['contestWinnersComplete']; ?></textarea></td>
    <td class="data"><em>Provide a complete winners list detailing the winners of each table, round, etc. This can be exported from HCCP in <strong>HTML format</strong> and pasted here.</em><p class="required">To paste raw HTML code, use this link: <a href="javascript:toggleEditor('contestWinnersComplete');">Add/Remove Editor</a>.</p>
      <p>If you paste using the editor to the left, most HTML tags will be stripped out and the original formatting will be lost.</p></td>
  </tr>
  <tr>
    <td class="dataLabel">&nbsp;</td>
    <td class="data"><a href="javascript:toggleEditor('contestWinnersComplete');">Disable Rich Text</a></td>
    <td class="data">&nbsp;</td>
  </tr>
</table>
<?php } ?>
<p><input name="submit" type="submit" class="button" value="Update Competition Info"></p>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>