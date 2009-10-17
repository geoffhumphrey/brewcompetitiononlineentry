<div id="header">
	<div id="header-inner"><h1>Judge Information</h1></div>
</div>
<form action="includes/process.inc.php?action=edit&dbTable=brewer&go=register&id=<?php echo $row_brewer['id']; ?>" method="POST" name="form1" onSubmit="return CheckRequiredFields()">
<table class="dataTable">
      <td class="dataLabel">BJCP ID:</td>
      <td class="data"><input name="brewerJudgeID" id="brewerJudgeID" type="text" size="10" value="<?php if ($action == "edit") echo $row_brewer['brewerJudgeID']; ?>" /></td>
</tr>
<tr>
      <td class="dataLabel">BJCP Rank:</td>
      <td class="data"><select name="brewerJudgeRank">
        <option value="Experienced" <?php if (($action == "edit") && ($row_brewer['brewerJudgeRank'] == "Experienced")) echo "SELECTED"; ?>>Experienced</option>
        <option value="Recognized" <?php if (($action == "edit") && ($row_brewer['brewerJudgeRank'] == "Recognized")) echo "SELECTED"; ?>>Recognized</option>
        <option value="Certified" <?php if (($action == "edit") && ($row_brewer['brewerJudgeRank'] == "Certified")) echo "SELECTED"; ?>>Certified</option>
        <option value="National" <?php if (($action == "edit") && ($row_brewer['brewerJudgeRank'] == "National")) echo "SELECTED"; ?>>National</option>
        <option value="Master" <?php if (($action == "edit") && ($row_brewer['brewerJudgeRank'] == "Master")) echo "SELECTED"; ?>>Master</option>
        <option value="Grand Master" <?php if (($action == "edit") && ($row_brewer['brewerJudgeRank'] == "Grand Master")) echo "SELECTED"; ?>>Grand Master</option>
        <option value="Honorary Master" <?php if (($action == "edit") && ($row_brewer['brewerJudgeRank'] == "Honorary Master")) echo "SELECTED"; ?>>Honorary Master</option>
        <option value="Honorary Grand Master" <?php if (($action == "edit") && ($row_brewer['brewerJudgeRank'] == "Honorary Grand Master")) echo "SELECTED"; ?>>Honorary Grand Master</option>
      </select>
      </td>
</tr>
<tr>
      <td class="dataLabel">Preferred Categories to Judge:</td>
      <td class="data"><input name="brewerJudgeLikes" id="brewerJudgeLikes" type="text" size="50" value=""/><br />Please indicate the <strong>both</strong> BJCP category numbers and subcategory letters.</td>
</tr>
<tr>
      <td class="dataLabel">Preferred Categories NOT to Judge:</td>
      <td class="data"><input name="brewerJudgeDislikes" id="brewerJudgeDislkes" type="text" size="50" value="" /><br />Please indicate the <strong>both</strong> BJCP category numbers and subcategory letters.</td>
</tr>
<tr>
	  <td>&nbsp;</td>
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