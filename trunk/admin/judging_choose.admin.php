<table>
 <tr>
   <td class="dataLabel">Assign <?php if ($filter == "judges") echo "Judges"; if ($filter == "stewards") echo "Stewards"; ?> To:</td>
   <td class="data">
   <select name="judge_loc" id="judge_loc" onchange="jumpMenu('self',this,0)">
	<option value=""></option>
    <?php do { ?>
	<option value="index.php?section=admin&amp;action=update&amp;go=judging&amp;filter=<?php echo $filter; ?>&amp;bid=<?php echo $row_judging['id']; ?>"><?php  echo $row_judging['judgingLocName']." ("; echo dateconvert($row_judging['judgingDate'], 3).")"; ?></option>
    <?php } while ($row_judging = mysql_fetch_assoc($judging)); ?>
   </select>
  </td>
</tr>
</table>