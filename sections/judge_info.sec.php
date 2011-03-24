<tr>
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
        <option value="Apprentice" <?php if (($action == "edit") && ($row_brewer['brewerJudgeRank'] == "Apprentice")) echo "SELECTED"; ?>>BJCP - Apprentice</option>
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
      <td width="10%" class="dataLabel">Preferred:</td>
      <td class="data" colspan="2">
      	<table class="dataTableCompact">
        	<tr>
            	<td colspan="3">Check all styles that you <em>prefer</em> to judge.</td>
            </tr>
        	<?php do { 	?>
            <tr>
            	<td width="1%"><input name="brewerJudgeLikes[]" type="checkbox" value="<?php echo $row_styles['id']; ?>" <?php $a = explode(",", $row_brewer['brewerJudgeLikes']); $b = $row_styles['id']; foreach ($a as $value) { if ($value == $b) echo "CHECKED"; } ?>></td>
                <td width="1%"><?php echo ltrim($row_styles['brewStyleGroup'], "0").$row_styles['brewStyleNum'].":"; ?></td>
                <td><?php echo $row_styles['brewStyle']; ?></td>
            </tr>
            <?php } while ($row_styles = mysql_fetch_assoc($styles)); ?>
        </table>
      </td>
</tr>
<tr>
      <td width="10%" class="dataLabel">Not Preferred:</td> 
      <td class="data" colspan="2">
      	<table class="dataTableCompact">
            <tr>
            	<td colspan="3">Check all styles that you <em>do not wish</em> to judge.</td>
            </tr>
        	<?php do { ?>
            <tr>
            	<td width="1%"><input name="brewerJudgeDislikes[]" type="checkbox" value="<?php echo $row_styles2['id']; ?>" <?php $a = explode(",", $row_brewer['brewerJudgeDislikes']); $b = $row_styles2['id']; foreach ($a as $value) { if ($value == $b) echo "CHECKED"; } ?>></td>
                <td width="1%"><?php echo ltrim($row_styles2['brewStyleGroup'], "0").$row_styles2['brewStyleNum'].":"; ?></td>
                <td><?php echo $row_styles2['brewStyle']; ?></td>
            </tr>
            <?php } while ($row_styles2 = mysql_fetch_assoc($styles2)); ?>
        </table>
      </td>
</tr>