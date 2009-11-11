<?php
mysql_select_db($database, $brewing);
$query_brewerID = sprintf("SELECT * FROM brewer WHERE id = '%s'", $filter); 
$brewerID = mysql_query($query_brewerID, $brewing) or die(mysql_error());
$row_brewerID = mysql_fetch_assoc($brewerID);
$totalRows_brewerID = mysql_num_rows($brewerID);
?>
<div id="header">
	<div id="header-inner"><h1><?php if ($action == "add") echo "Step 2: Registrant Information"; else echo "Edit Registrant Information"; ?></h1></div>
</div>
<?php if ($msg != "default") echo "<div class=\"error\">Your account has been created.</div>"; 
if (($action == "add") || (($action == "edit") && (($_SESSION["loginUsername"] == $row_brewer['brewerEmail'])) || ($row_user['userLevel'] == "1")))  { ?>
<form action="includes/process.inc.php?section=<?php if ($go == "entrant") echo "list"; elseif ($go == "judge") echo "judge"; else echo "admin&go=".$go."&filter=".$filter; ?>&action=<?php echo $action; ?>&dbTable=brewer&id=<?php echo $row_brewer['id']; ?>" method="POST" name="form1" id="form1" onSubmit="return CheckRequiredFields()">
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
<?php if ($go != "entrant") { ?>
<tr>
      <td class="dataLabel">Judging:</td>
      <td class="data">Are you willing and qualified to judge in this competition?</td>
      <td class="data"><input type="radio" name="brewerJudge" value="Y" id="brewerJudge_0"  <?php if (($action == "add") && ($go == "judge")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerJudge'] == "Y")) echo "CHECKED"; ?> /> Yes<br /><input type="radio" name="brewerJudge" value="N" id="brewerJudge_1" <?php if (($action == "add") && ($go == "default")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerJudge'] == "N")) echo "CHECKED"; ?>/> No</td>
</tr>
<tr>
      <td class="dataLabel">Stewarding:</td>
      <td class="data">Are you willing be a steward in this competition?</td>
      <td class="data"><input type="radio" name="brewerSteward" value="Y" id="brewerSteward_0"  <?php if (($action == "add") && ($go == "judge")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerSteward'] == "Y")) echo "CHECKED"; ?> /> Yes<br /><input type="radio" name="brewerSteward" value="N" id="brewerSteward_1" <?php if (($action == "add") && ($go == "default")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerSteward'] == "N")) echo "CHECKED"; ?>/> No</td>
</tr>
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
      </select>
      </td>
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
      <td colspan="2" class="data"><input name="submit" type="submit" value="Submit Brewer Information" /></td>
    </tr>
</table>
<input name="brewerEmail" type="hidden" value="<?php if ($filter != "default") echo $row_brewerID['brewerEmail']; else echo $row_user['user_name']; ?>" />
<?php if ($go == "entrant") { ?>
<input name="brewerJudge" type="hidden" value="N" />
<input name="brewerSteward" type="hidden" value="N" />
<?php } ?>
</form>
<?php }
else echo "<div class=\"error\">You can only edit your own profile.</div>";
?>