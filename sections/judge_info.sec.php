	<tr>
      <td width="10%" class="dataLabel">BJCP Judge ID:</td>
      <td colspan="2" class="data"><input name="brewerJudgeID" id="brewerJudgeID" type="text" size="10" value="<?php if ($action == "edit") echo $row_brewer['brewerJudgeID']; ?>" /></td>
	</tr>
	<tr>
      <td width="10%" class="dataLabel">Mead Judge Endorsement:</td>
      <td width="15%" class="data">Have you taken <strong>and passed</strong> the BJCP Mead Exam?</td>
      <td class="data">
      <input type="radio" name="brewerJudgeMead" value="Y" id="brewerJudgeMead_0"  <?php if (($action == "edit") && ($row_brewer['brewerJudgeMead'] == "Y")) echo "CHECKED"; ?> /> Yes<br /><input type="radio" name="brewerJudgeMead" value="N" id="brewerJudgeMead_1" <?php if (($action == "add") && ($go == "judge")) echo "CHECKED";  if (($action == "add") && ($go == "default")) echo "CHECKED"; if (($action == "edit") && ($row_brewer['brewerJudgeMead'] == "N")) echo "CHECKED"; elseif ((($action == "edit") || ($section == "register")) && ($go == "judge")) echo "CHECKED"; ?>/> No</td>
    </tr>
    <tr>
      <td width="10%" class="dataLabel">Judge Rank:</td>
      <td class="data" colspan="2">
      	<table class="dataTableCompact">
        	<tr>
            	<td>BJCP Designations</td>
                <td>Other Designations*</td>
            </tr>
            	<td nowrap="nowrap">
                <?php 
				$judge_array = explode(",",$row_brewer['brewerJudgeRank']); 
				?>
                <input type="radio" name="brewerJudgeRank[]" value="Novice" <?php if (($action == "edit") && (in_array("Non-BJCP",$judge_array) || in_array("Novice",$judge_array))) echo "CHECKED"; else echo "CHECKED" ?>>Non-BJCP<br />
                <input type="radio" name="brewerJudgeRank[]" value="Rank Pending" <?php if (($action == "edit")  && in_array("Rank Pending",$judge_array)) echo "CHECKED"; ?>>Rank Pending<br />
                <input type="radio" name="brewerJudgeRank[]" value="Apprentice" <?php if (($action == "edit") && in_array("Apprentice",$judge_array)) echo "CHECKED"; ?>>Apprentice<br />
                <input type="radio" name="brewerJudgeRank[]" value="Provisional" <?php if (($action == "edit") && in_array("Provisional",$judge_array)) echo "CHECKED"; ?>>Provisional<br />
                <input type="radio" name="brewerJudgeRank[]" value="Recognized" <?php if (($action == "edit") && in_array("Recognized",$judge_array)) echo "CHECKED"; ?>>Recognized<br />
                <input type="radio" name="brewerJudgeRank[]" value="Certified" <?php if (($action == "edit") && in_array("Certified",$judge_array)) echo "CHECKED"; ?>>Certified<br />
                <input type="radio" name="brewerJudgeRank[]" value="National" <?php if (($action == "edit") && in_array("National",$judge_array)) echo "CHECKED"; ?>>National<br />
                <input type="radio" name="brewerJudgeRank[]" value="Master" <?php if (($action == "edit") && in_array("Master",$judge_array)) echo "CHECKED"; ?>>Master<br />
                <input type="radio" name="brewerJudgeRank[]" value="Grand Master" <?php if (($action == "edit") && in_array("Grand Master",$judge_array)) echo "CHECKED"; ?>>Grand Master<br />
                <input type="radio" name="brewerJudgeRank[]" value="Honorary Master" <?php if (($action == "edit") && in_array("Honorary Master",$judge_array)) echo "CHECKED"; ?>>Honorary Master<br />
                <input type="radio" name="brewerJudgeRank[]" value="Honorary Grand Master" <?php if (($action == "edit") && in_array("Honorary Grand Master",$judge_array)) echo "CHECKED"; ?>>Honorary Grand Master<br />
                </td>
                <td>
                <input type="checkbox" name="brewerJudgeRank[]" value="Professional Brewer" <?php if (($action == "edit") && in_array("Professional Brewer",$judge_array)) echo "CHECKED"; ?>>Professional Brewer<br />
                <input type="checkbox" name="brewerJudgeRank[]" value="Beer Sommelier" <?php if (($action == "edit") && in_array("Beer Sommelier",$judge_array)) echo "CHECKED"; ?>>Beer Sommelier<br />
                <input type="checkbox" name="brewerJudgeRank[]" value="Certified Cicerone" <?php if (($action == "edit") && in_array("Certified Cicerone",$judge_array)) echo "CHECKED"; ?>>Certified Cicerone<br />
                <input type="checkbox" name="brewerJudgeRank[]" value="Master Cicerone" <?php if (($action == "edit") && in_array("Master Cicerone",$judge_array)) echo "CHECKED"; ?>>Master Cicerone<br />
                <input type="checkbox" name="brewerJudgeRank[]" value="Judge with Sensory Training" <?php if (($action == "edit") && in_array("Judge with Sensory Training",$judge_array)) echo "CHECKED"; ?>>Judge with Sensory Training
				<br /><em>* Only the first two checked will appear on your Judge Scoresheet Labels</em></td>
            </tr>
       </table>
      </td>
	</tr>
    <tr>
    	<td colspan="3">
        <ul>
          <li>The <em>Non-BJCP</em> rank is for those who haven't taken the BJCP Beer Judge Entrance Exam, and are <em>not</em> a professional brewer.</li>
          <li>The <em>Apprentice</em> rank is for those who have taken the BJCP Legacy Beer Exam, but did not pass one or more of the sections. This rank will be phased out in 2014.</li>
          <li>The <em>Provisional</em> rank is for those have taken the BJCP Beer Judge Entrance Exam, have passed, but have not yet taken the BJCP Beer Judging Exam.</li>
      	</ul>
        </td>
    </tr>
    <?php if (!$table_assignment) { ?>
	<tr>
      <td width="10%" class="dataLabel">Preferred:</td>
      <td class="data" colspan="3">
      Check all styles that you <em>prefer</em> to judge.<p><span class="required"><strong>For preferences ONLY.</strong> Leaving a style unchecked indicates that you are OK to judge it &ndash; there's no need to check all that your available to judge.</span></p>
      	<table class="dataTableCompact">
        		<?php $endRow = 0; $columns = 3; $hloopRow1 = 0;
				do {
    			if (($endRow == 0) && ($hloopRow1++ != 0)) echo "<tr>";
				if (!empty($row_styles['brewStyleGroup'])) {
    			?>
            	<td width="1%"><input name="brewerJudgeLikes[]" type="checkbox" value="<?php echo $row_styles['id']; ?>" <?php $a = explode(",", $row_brewer['brewerJudgeLikes']); $b = $row_styles['id']; foreach ($a as $value) { if ($value == $b) echo "CHECKED"; } ?>></td>
                <td width="1%"><?php echo ltrim($row_styles['brewStyleGroup'], "0").$row_styles['brewStyleNum'].":"; ?></td>
                <td nowrap="nowrap"><?php echo $row_styles['brewStyle']; ?></td>
           		<?php  $endRow++;
				if ($endRow >= $columns) {
  				?>
  				</tr>
  				<?php $endRow = 0;
  				}
				}
				} while ($row_styles = mysqli_fetch_assoc($styles));
				if ($endRow != 0) {
				while ($endRow < $columns) {
   				echo("<td>&nbsp;</td>");
    			$endRow++;
				}
				echo("</tr>");
				}
				?>
        </table>
      </td>
	</tr>
	<tr>
      <td width="10%" class="dataLabel">Not Preferred:</td> 
      <td class="data" colspan="3">
      Check all styles that you <em>do not wish</em> to judge.<p><span class="required">There is no need to mark those styles for which you have entries; the system will not allow you to be assigned to any table  where you have entries.</span></p>
      	<table class="dataTableCompact">
        	<?php $endRow = 0; $columns = 3; $hloopRow1 = 0;
				do {
    			if (($endRow == 0) && ($hloopRow1++ != 0)) echo "<tr>";
				if (!empty($row_styles2['brewStyleGroup'])) {
    			?>
            	<td width="1%"><input name="brewerJudgeDislikes[]" type="checkbox" value="<?php echo $row_styles2['id']; ?>" <?php $a = explode(",", $row_brewer['brewerJudgeDislikes']); $b = $row_styles2['id']; foreach ($a as $value) { if ($value == $b) echo "CHECKED"; } ?>></td>
                <td width="1%"><?php echo ltrim($row_styles2['brewStyleGroup'], "0").$row_styles2['brewStyleNum'].":"; ?></td>
                <td><?php echo $row_styles2['brewStyle']; ?></td>
            <?php  $endRow++;
				if ($endRow >= $columns) {
  				?>
  				</tr>
  				<?php $endRow = 0;
  				}
				}
				} while ($row_styles2 = mysqli_fetch_assoc($styles2));
				if ($endRow != 0) {
				while ($endRow < $columns) {
   				echo("<td>&nbsp;</td>");
    			$endRow++;
				}
				echo("</tr>");
				}
				?>
        </table>
      </td>
	</tr>
    <?php } ?>