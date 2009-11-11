<div id="header">
	<div id="header-inner"><h1><?php if ($go == "judge") echo "Step 3:"; ?> Judge Information</h1></div>
</div>
<form action="includes/process.inc.php?action=edit&dbTable=brewer&go=<?php echo $go; ?>&id=<?php echo $row_brewer['id']; ?>" method="POST" name="form1" onSubmit="return CheckRequiredFields()">
<table class="dataTable">
      <td width="10%" class="dataLabel">BJCP ID:</td>
      <td class="data"><input name="brewerJudgeID" id="brewerJudgeID" type="text" size="10" value="<?php if ($action == "edit") echo $row_brewer['brewerJudgeID']; ?>" /><br />If you are not a BJCP recognized judge, please enter a zero (0).</td>
</tr>
<tr> 
      <td width="10%" class="dataLabel">Judge Rank:</td>
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
      <td width="10%" class="dataLabel">Preferred Categories to Judge:</td>
      <td class="data"><input name="brewerJudgeLikes" id="brewerJudgeLikes" type="text" size="50" value=""/><br />Please indicate the <strong>both</strong> BJCP category numbers and subcategory letters.</td>
</tr>
<tr>
      <td width="10%" class="dataLabel">Preferred Categories NOT to Judge:</td>
      <td class="data"><input name="brewerJudgeDislikes" id="brewerJudgeDislkes" type="text" size="50" value="" /><br />Please indicate the <strong>both</strong> BJCP category numbers and subcategory letters.</td>
</tr>
<tr>
	  <td width="10%">&nbsp;</td>
      <td class="data"><input name="submit" type="submit" value="Submit Judge Information" /></td>
</tr>
</table>
<input type="hidden" name="brewerEmail" value="<?php echo $_SESSION["loginUsername"]; ?>" />
<input type="hidden" name="brewerFirstName" value="<?php echo $row_brewer['brewerFirstName']; ?>">
<input type="hidden" name="brewerLastName" value="<?php echo $row_brewer['brewerLastName']; ?>">
<input type="hidden" name="brewerAddress" value="<?php echo $row_brewer['brewerAddress']; ?>">
<input type="hidden" name="brewerCity" value="<?php echo $row_brewer['brewerCity']; ?>">
<input type="hidden" name="brewerState" value="<?php echo $row_brewer['brewerState']; ?>">
<input type="hidden" name="brewerZip" value="<?php echo $row_brewer['brewerZip']; ?>">
<input type="hidden" name="brewerPhone1" value="<?php echo $row_brewer['brewerPhone1']; ?>">
<input type="hidden" name="brewerPhone2" value="<?php echo $row_brewer['brewerPhone1']; ?>">
<input type="hidden" name="brewerClubs" value="<?php echo $row_brewer['brewerClubs']; ?>">
<input type="hidden" name="brewerJudge"  value="<?php echo $row_brewer['brewerJudge']; ?>" />
<input type="hidden" name="brewerSteward"  value="<?php echo $row_brewer['brewerSteward']; ?>" />
</form>