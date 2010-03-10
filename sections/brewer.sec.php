<?php
if ($section != "step7") {
mysql_select_db($database, $brewing);
$query_brewerID = sprintf("SELECT * FROM brewer WHERE id = '%s'", $filter); 
$brewerID = mysql_query($query_brewerID, $brewing) or die(mysql_error());
$row_brewerID = mysql_fetch_assoc($brewerID);
$totalRows_brewerID = mysql_num_rows($brewerID);
} 
if ($msg != "default") echo $msg_output; 
if (($section == "step7") || ($action == "add") || (($action == "edit") && (($_SESSION["loginUsername"] == $row_brewer['brewerEmail'])) || ($row_user['userLevel'] == "1")))  { ?>
<?php if ($section == "step7") { ?>
<form action="includes/process.inc.php?section=setup&action=add&dbTable=brewer" method="POST" name="form1" id="form1" onSubmit="return CheckRequiredFields()">
<input name="brewerSteward" type="hidden" value="N" />
<input name="brewerJudge" type="hidden" value="N" />
<input name="brewerEmail" type="hidden" value="<?php echo $go; ?>" />
<?php } else { ?>
<form action="includes/process.inc.php?section=<?php if ($go == "entrant") echo "list"; elseif ($go == "judge") echo "judge"; else echo "admin&go=".$go."&filter=".$filter; ?>&action=<?php echo $action; ?>&dbTable=brewer<?php if ($action == "edit") echo "&id=".$row_brewer['id']; ?>" method="POST" name="form1" id="form1" onSubmit="return CheckRequiredFields()">
<?php } ?>
<div class="info">The information here beyond your first name, last name, and club is strictly for record-keeping and contact purposes. A condition of entry into the competition is providing this information. Your name and club may be displayed should one of your entries place, but no other information will be made public.</div>
<table class="dataTable">
<tr>
      <td class="dataLabel" width="5%">First Name:</td>
      <td class="data" width="20%"><input type="text" id="brewerFirstName" name="brewerFirstName" value="<?php if ($action == "edit") echo $row_brewer['brewerFirstName']; ?>" size="32" maxlength="20"></td>
      <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
      <td class="dataLabel">Last Name:</td>
      <td class="data"><input type="text" name="brewerLastName" value="<?php if ($action == "edit") echo $row_brewer['brewerLastName']; ?>" size="32"></td>
      <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
      <td class="dataLabel">Street Address:</td>
      <td class="data"><input type="text" name="brewerAddress" value="<?php if ($action == "edit") echo $row_brewer['brewerAddress']; ?>" size="32"></td>
      <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
      <td class="dataLabel">City:</td>
      <td class="data"><input type="text" name="brewerCity" value="<?php if ($action == "edit") echo $row_brewer['brewerCity']; ?>" size="32"></td>
      <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
      <td class="dataLabel">State/Country:</td>
      <td class="data"><input type="text" name="brewerState" value="<?php if ($action == "edit") echo $row_brewer['brewerState']; ?>" size="32"></td>
      <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
      <td class="dataLabel">Zip/Postal Code:</td>
      <td class="data"><input type="text" name="brewerZip" value="<?php if ($action == "edit") echo $row_brewer['brewerZip']; ?>" size="32"></td>
      <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
      <td class="dataLabel">Phone 1:</td>
      <td class="data"><input type="text" name="brewerPhone1" value="<?php if ($action == "edit") echo $row_brewer['brewerPhone1']; ?>" size="32"></td>
      <td class="data"><span class="required">Required</span></td>
</tr>
<tr>
      <td class="dataLabel">Phone 2:</td>
      <td class="data"><input type="text" name="brewerPhone2" value="<?php if ($action == "edit") echo $row_brewer['brewerPhone2']; ?>" size="32"></td>
      <td class="data">&nbsp;</td>
</tr>
<tr>
      <td class="dataLabel">Club Name (if appropriate):</td>
      <td class="data"><input type="text" name="brewerClubs" value="<?php if ($action == "edit") echo $row_brewer['brewerClubs']; ?>" size="32" maxlength="200"></td>
      <td class="data">&nbsp;</td>
</tr>
<?php if (($go != "entrant") && ($section != "step7")) { ?>
<tr>
      <td class="dataLabel">Stewarding:</td>
      <td class="data">Are you willing be a steward in this competition?</td>
      <td class="data"><input type="radio" name="brewerSteward" value="Y" id="brewerSteward_0"  <?php if (($action == "add") && ($go == "judge")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerSteward'] == "Y")) echo "CHECKED"; ?> /> Yes<br /><input type="radio" name="brewerSteward" value="N" id="brewerSteward_1" <?php if (($action == "add") && ($go == "default")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerSteward'] == "N")) echo "CHECKED"; ?>/> No</td>
</tr>
<?php if ($totalRows_judging > 1) { ?>
<tr> 
      <td width="10%" class="dataLabel">1st Preference:</td>
      <td colspan="2" class="data">
      <select name="brewerStewardLocation">
      <option value="99999999" <?php if (($action == "edit") && ($row_brewer['brewerStewardLocation'] == "99999999")) echo "SELECTED"; ?>>None (Any Location/Time)</option>
      <?php do { ?>
      <option value="<?php echo $row_stewarding['id']; ?>" <?php if (($action == "edit") && ($row_brewer['brewerStewardLocation'] == $row_stewarding['id'])) echo "SELECTED"; ?>><?php echo $row_stewarding['judgingLocName']." ("; echo dateconvert($row_stewarding['judgingDate'], 3).")"; ?></option>
      <?php } while ($row_stewarding = mysql_fetch_assoc($stewarding)); ?>
      </select>
      </td>
</tr>
<tr> 
      <td width="10%" class="dataLabel">2nd Preference:</td>
      <td colspan="2" class="data">
      <select name="brewerStewardLocation2">
      <option value="99999999" <?php if (($action == "edit") && ($row_brewer['brewerStewardLocation2'] == "99999999")) echo "SELECTED"; ?>>None (Any Location/Time)</option>
      <option value="99999998" <?php if (($action == "edit") && ($row_brewer['brewerStewardLocation2'] == "99999998")) echo "SELECTED"; ?>>No 2nd Preference</option>
      <?php do { ?>
      <option value="<?php echo $row_stewarding2['id']; ?>" <?php if (($action == "edit") && ($row_brewer['brewerStewardLocation2'] == $row_stewarding2['id'])) echo "SELECTED"; ?>><?php echo $row_stewarding2['judgingLocName']." ("; echo dateconvert($row_stewarding2['judgingDate'], 3).")"; ?></option>
      <?php } while ($row_stewarding2 = mysql_fetch_assoc($stewarding2)); ?>
      </select>
      </td>
</tr>
<?php } ?> 
<tr>
      <td class="dataLabel">Judging:</td>
      <td class="data">Are you willing and qualified to judge in this competition?</td>
      <td class="data"><input type="radio" name="brewerJudge" value="Y" id="brewerJudge_0"  <?php if (($action == "add") && ($go == "judge")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerJudge'] == "Y")) echo "CHECKED"; ?> /> Yes<br /><input type="radio" name="brewerJudge" value="N" id="brewerJudge_1" <?php if (($action == "add") && ($go == "default")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerJudge'] == "N")) echo "CHECKED"; ?>/> No</td>
</tr>
<?php if ($totalRows_judging > 1) { ?>
<tr> 
      <td width="10%" class="dataLabel">1st Preference:</td>
      <td colspan="2" class="data">
      <select name="brewerJudgeLocation">
      <option value="99999999" <?php if (($action == "edit") && ($row_brewer['brewerJudgeLocation'] == "99999999")) echo "SELECTED"; ?>>None (Any Location/Time)</option>
      <?php do { ?>
      <option value="<?php echo $row_judging3['id']; ?>" <?php if (($action == "edit") && ($row_brewer['brewerJudgeLocation'] == $row_judging3['id'])) echo "SELECTED"; ?>><?php echo $row_judging3['judgingLocName']." ("; echo dateconvert($row_judging3['judgingDate'], 3).")"; ?></option>
      <?php } while ($row_judging3 = mysql_fetch_assoc($judging3)); ?>
      </select>
      </td>
</tr>
<tr> 
      <td width="10%" class="dataLabel">2nd Preference:</td>
      <td colspan="2" class="data">
      <select name="brewerJudgeLocation2">
      <option value="99999999" <?php if (($action == "edit") && ($row_brewer['brewerJudgeLocation2'] == "99999999")) echo "SELECTED"; ?>>None (Any Location/Time)</option>
      <option value="99999998" <?php if (($action == "edit") && ($row_brewer['brewerJudgeLocation2'] == "99999998")) echo "SELECTED"; ?>>No 2nd Preference</option>
      <?php do { ?>
      <option value="<?php echo $row_judging2['id']; ?>" <?php if (($action == "edit") && ($row_brewer['brewerJudgeLocation2'] == $row_judging2['id'])) echo "SELECTED"; ?>><?php echo $row_judging2['judgingLocName']." ("; echo dateconvert($row_judging2['judgingDate'], 3).")"; ?></option>
      <?php } while ($row_judging2 = mysql_fetch_assoc($judging2)); ?>
      </select>
      </td>
</tr>
<?php } ?>
<?php } ?>
<?php if ($action == "edit") { ?>
      <tr><td class="dataLabel">BJCP ID:</td>
      <td colspan="2" class="data"><input name="brewerJudgeID" id="brewerJudgeID" type="text" size="10" value="<?php echo $row_brewer['brewerJudgeID']; ?>" /><br />If you are <strong>not</strong> a BJCP recognized judge, but still wish to judge in our competition, please enter a zero (0).</td>
    </tr>
<tr>
      <td class="dataLabel">Judge Rank:</td>
      <td colspan="2" class="data"><select name="brewerJudgeRank">
        <option value=""></option>
        <option value="None" <?php if (($action == "edit") && ($row_brewer['brewerJudgeRank'] == "None")) echo "SELECTED"; ?>>None</option>
        <option value="Experienced" <?php if (($action == "edit") && ($row_brewer['brewerJudgeRank'] == "Experienced")) echo "SELECTED"; ?>>Experienced</option>
        <option value="Professional Brewer" <?php if (($action == "edit") && ($row_brewer['brewerJudgeRank'] == "Professional Brewer")) echo "SELECTED"; ?>>Professional Brewer</option>
        <option value="Recognized" <?php if (($action == "edit") && ($row_brewer['brewerJudgeRank'] == "Recognized")) echo "SELECTED"; ?>>BJCP - Recognized</option>
        <option value="Certified" <?php if (($action == "edit") && ($row_brewer['brewerJudgeRank'] == "Certified")) echo "SELECTED"; ?>>BJCP - Certified</option>
        <option value="National" <?php if (($action == "edit") && ($row_brewer['brewerJudgeRank'] == "National")) echo "SELECTED"; ?>>BJCP - National</option>
        <option value="Master" <?php if (($action == "edit") && ($row_brewer['brewerJudgeRank'] == "Master")) echo "SELECTED"; ?>>BJCP - Master</option>
        <option value="Grand Master" <?php if (($action == "edit") && ($row_brewer['brewerJudgeRank'] == "Grand Master")) echo "SELECTED"; ?>>BJCP - Grand Master</option>
        <option value="Honorary Master" <?php if (($action == "edit") && ($row_brewer['brewerJudgeRank'] == "Honorary Master")) echo "SELECTED"; ?>>BJCP - Honorary Master</option>
        <option value="Honorary Grand Master" <?php if (($action == "edit") && ($row_brewer['brewerJudgeRank'] == "Honorary Grand Master")) echo "SELECTED"; ?>>BJCP - Honorary Grand Master</option>
      </select>      </td>
    </tr>
<tr>
      <td class="dataLabel">Preferred Categories to Judge:</td>
      <td colspan="2" class="data"><input name="brewerJudgeLikes" id="brewerJudgeLikes" type="text" size="50" value="<?php echo $row_brewer['brewerJudgeLikes']; ?>"/><br />Please indicate the <strong>both</strong> BJCP category numbers and subcategory letters.</td>
    </tr>
<tr>
      <td class="dataLabel">Preferred Categories NOT to Judge:</td>
      <td colspan="2" class="data"><input name="brewerJudgeDislikes" id="brewerJudgeDislikes" type="text" size="50" value="<?php echo $row_brewer['brewerJudgeDislikes']; ?>" /><br />Please indicate the <strong>both</strong> BJCP category numbers and subcategory letters.</td>
    </tr>
<?php } ?>
<tr>
	  <td>&nbsp;</td>
      <td colspan="2" class="data"><input name="submit" type="submit" class="button" value="Submit Brewer Information" /></td>
    </tr>
</table>
<?php if ($section != "step7") { ?>
	<input name="brewerEmail" type="hidden" value="<?php if ($filter != "default") echo $row_brewerID['brewerEmail']; else echo $row_user['user_name']; ?>" />
	<?php if ($go == "entrant") { ?>
	<input name="brewerJudge" type="hidden" value="N" />
	<input name="brewerSteward" type="hidden" value="N" />
	<?php } ?>
<?php } ?>
</form>
<?php }
else echo "<div class=\"error\">You can only edit your own profile.</div>";
?>