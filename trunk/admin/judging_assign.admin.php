<?php if ($totalRows_brewer > 0) { ?>
<script language="javascript" type="text/javascript">
//Custom JavaScript Functions by Shawn Olson
//Copyright 2006-2008
//http://www.shawnolson.net
function checkUncheckAll(theElement) {
     var theForm = theElement.form, z = 0;
	 for(z=0; z<theForm.length;z++){
      if(theForm[z].type == 'checkbox' && theForm[z].name != 'checkall'){
	  theForm[z].checked = theElement.checked;
	  }
     }
    }
</script>
<form name="form1" method="post" action="includes/process.inc.php?action=update&dbTable=brewer&filter=<?php echo $filter; if ($bid != "default") echo "&bid=".$bid; ?>">
<table class="dataTable">
 <tr>
 	<td colspan="10"><input type="submit" class="button" name="Submit" value="<?php if ($action == "update") echo "Assign to ".$row_judging['judgingLocName']; elseif ($action == "assign") { echo "Assign as "; if ($filter == "judges") echo "Judges"; else echo "Stewards"; } else echo "Submit"; ?>" /></td>
 </tr>
 <tr>
  <td class="dataHeading bdr1B"><input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"/></td>
  <td class="dataHeading bdr1B">Name</td>
  <td class="dataHeading bdr1B">Assigned As</td>
  <?php if ($totalRows_stewarding2 > 1) { ?>
  <td class="dataHeading bdr1B">Assigned To</td>
  <?php } ?>
  <?php if ($filter == "judges") { ?>
  <td class="dataHeading bdr1B">ID</td>
  <td class="dataHeading bdr1B">Rank</td>
  <td class="dataHeading bdr1B">Likes</td>
  <td class="dataHeading bdr1B">Dislikes</td>
  <?php } if ($bid != "default") { ?>
  <td class="dataHeading bdr1B">Location Ranking</td>
 </tr>
 <?php } do { 
	if ($filter == "judges") $query_judging_loc = sprintf("SELECT * FROM judging WHERE id='%s'", $row_brewer['brewerJudgeAssignedLocation']);
	if ($filter == "stewards") $query_judging_loc = sprintf("SELECT * FROM judging WHERE id='%s'", $row_brewer['brewerStewardAssignedLocation']);
	$judging_loc = mysql_query($query_judging_loc, $brewing) or die(mysql_error());
	$row_judging_loc = mysql_fetch_assoc($judging_loc);
	$totalRows_judging_loc = mysql_num_rows($judging_loc);
 
 ?>
 <tr <?php echo " style=\"background-color:$color\"";?>>
  <input type="hidden" name="id[]" value="<?php echo $row_brewer['id']; ?>" />
  <?php if ($bid == "default") { ?>
  <td width="1%" class="dataList"><input name="brewerAssignment<?php echo $row_brewer['id']; ?>" type="checkbox" value="<?php if ($filter == "judges") echo "J"; if ($filter == "stewards") echo "S"; ?>" <?php if (($filter == "judges") && ($row_brewer['brewerAssignment'] == "J")) echo "CHECKED"; if (($filter == "stewards") && ($row_brewer['brewerAssignment'] == "S")) echo "CHECKED"; ?>></td>
  <?php } else { ?>
  <td width="1%" class="dataList"><input name="<?php if ($filter == "judges") echo "brewerJudgeAssignedLocation".$row_brewer['id']; if ($filter == "stewards") echo "brewerStewardAssignedLocation".$row_brewer['id']; ?>" type="checkbox" value="<?php echo $bid; ?>" <?php if (($filter == "judges") && ($row_brewer['brewerJudgeAssignedLocation'] == $bid)) echo "CHECKED"; if (($filter == "stewards") && ($row_brewer['brewerStewardAssignedLocation'] == $bid)) echo "CHECKED"; ?>></td>
  <?php } ?>
  <td width="10%" class="dataList"><?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?></td>
  <td width="5%" class="dataList"><?php if ($row_brewer['brewerAssignment'] == "J") echo "Judge"; elseif ($row_brewer['brewerAssignment'] == "S") echo "Steward"; else echo "Not Set";?></td>
  <?php if ($totalRows_stewarding2 > 1) { ?>
  <td width="15%" class="dataList">
  <?php 
  if (($filter == "judges") && ($row_brewer['brewerJudgeAssignedLocation'])) { echo $row_judging_loc['judgingLocName']."<br>("; echo dateconvert($row_judging_loc['judgingDate'], 3).")"; }
  if (($filter == "stewards") && ($row_brewer['brewerStewardAssignedLocation'])) { echo $row_judging_loc['judgingLocName']."<br>("; echo dateconvert($row_judging_loc['judgingDate'], 3).")"; }
  ?>  </td>
  <?php } if ($filter == "judges") { ?>
  <td width="5%" class="dataList"><?php echo $row_brewer['brewerJudgeID']; ?></td>
  <td width="5%" class="dataList"><?php echo $row_brewer['brewerJudgeRank']; ?></td>
  <td width="10%" class="dataList"><?php echo str_replace(",", ", ", $row_brewer['brewerJudgeLikes']) ?></td>
  <td width="10%" class="dataList"><?php echo str_replace(",", ", ", $row_brewer['brewerJudgeDislikes']) ?></td>
  <?php } if ($bid != "default") { ?>
  <td class="dataList">
  	<table class="dataTableCompact">
  	<?php 
		if ($filter == "stewards") $a = explode(",",$row_brewer['brewerStewardLocation']);
		if ($filter == "judges") $a = explode(",",$row_brewer['brewerJudgeLocation']);
		sort($a);
		foreach ($a as $value) {
			if ($value != "0-0") {
			$b = substr($value, 2);
			$query_judging_loc3 = sprintf("SELECT judgingLocName,judgingDate FROM judging WHERE id='%s'", $b);
			$judging_loc3 = mysql_query($query_judging_loc3, $brewing) or die(mysql_error());
			$row_judging_loc3 = mysql_fetch_assoc($judging_loc3);
			echo "<tr><td>".substr($value, 0, 1).":</td><td>".$row_judging_loc3['judgingLocName']." ("; echo dateconvert($row_judging_loc3['judgingDate'], 3).")";"</td></tr>";
			}
			}
	?>
    </table>
  </td>
  </tr>
  <?php } ?>
  <?php if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
  <?php } while ($row_brewer = mysql_fetch_assoc($brewer)); ?>
 <tr>
 	<td colspan="9" class="bdr1T"><input type="submit" class="button" name="Submit" value="<?php if ($action == "update") echo "Assign to ".$row_judging['judgingLocName']; elseif ($action == "assign") { echo "Assign as "; if ($filter == "judges") echo "Judges"; else echo "Stewards"; } else echo "Submit"; ?>" /></td>
 </tr>
</table>
</form>
<?php } else { if ($action == "update") echo "<div class='error'>No participants have been assigned as a ".rtrim($filter, "s").".</div>"; else echo "<div class='error'>No participants have indicated that they would like to be a ".rtrim($filter, "s").".</div>"; } ?>