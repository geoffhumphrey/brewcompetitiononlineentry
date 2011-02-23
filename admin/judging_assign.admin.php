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
	<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : true,
			"sPaginationType" : "full_numbers",
			"bLengthChange" : true,
			"iDisplayLength" : <?php echo round($row_prefs['prefsRecordPaging']); ?>,
			"sDom": 'irtip',
			"bStateSave" : false,
			"aaSorting": [[1,'asc']],
			"bProcessing" : true,
			"aoColumns": [
				{ "asSorting": [  ] },
				null,
				<?php if ($filter == "default") { ?>
				null,
				<?php } else { ?>
				{ "asSorting": [  ] },
				<?php } ?>
				<?php if ($totalRows_stewarding2 > 1) { ?>
				{ "asSorting": [  ] },
				<?php } ?>
				<?php if ($filter == "judges") { ?>
				null,
				null,
				null,
				null,
				<?php } ?>
				<?php if ($bid != "default") { ?>
				null,
				<?php } ?>
				]
			} );
		} );
	</script>
<form name="form1" method="post" action="includes/process.inc.php?action=update&amp;dbTable=brewer&amp;filter=<?php echo $filter; if ($bid != "default") echo "&amp;bid=".$bid; ?>">
<p><input type="submit" class="button" name="Submit" value="<?php if ($action == "update") echo "Assign to ".$row_judging['judgingLocName']; elseif ($action == "assign") { echo "Assign as "; if ($filter == "judges") echo "Judges"; else echo "Stewards"; } else echo "Submit"; ?>" /></p>
<table class="dataTable" id="sortable">
<thead>
 <tr>
  <th class="dataHeading bdr1B"><input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"/></th>
  <th class="dataHeading bdr1B">Name</th>
  <th class="dataHeading bdr1B">Assigned As</th>
  <?php if ($totalRows_stewarding2 > 1) { ?>
  <th class="dataHeading bdr1B">Assigned To</th>
  <?php } ?>
  <?php if ($filter == "judges") { ?>
  <th class="dataHeading bdr1B">ID</th>
  <th class="dataHeading bdr1B">Rank</th>
  <th class="dataHeading bdr1B">Likes</th>
  <th class="dataHeading bdr1B">Dislikes</th>
  <?php } ?>
  <?php if ($bid != "default") { ?>
  <th class="dataHeading bdr1B">Location Preferences</th>
  <?php } ?>
  </thead>
  <tbody>
  <?php 
 	do { 
		/* if ($filter == "judges") $query_judging_loc = sprintf("SELECT * FROM judging_locations WHERE id='%s'", $row_brewer['brewerJudgeAssignedLocation']);
		if ($filter == "stewards") $query_judging_loc = sprintf("SELECT * FROM judging_locations WHERE id='%s'", $row_brewer['brewerStewardAssignedLocation']);
		$judging_loc = mysql_query($query_judging_loc, $brewing) or die(mysql_error());
		$row_judging_loc = mysql_fetch_assoc($judging_loc);
		$totalRows_judging_loc = mysql_num_rows($judging_loc);
		*/
 ?>
 <tr>
  <input type="hidden" name="id[]" value="<?php echo $row_brewer['id']; ?>" />
  <?php if ($bid == "default") { ?>
  <td width="1%" class="dataList"><input name="brewerAssignment<?php echo $row_brewer['id']; ?>" type="checkbox" value="<?php if ($filter == "judges") echo "J"; if ($filter == "stewards") echo "S"; ?>" <?php if (($filter == "judges") && ($row_brewer['brewerAssignment'] == "J")) echo "CHECKED"; if (($filter == "stewards") && ($row_brewer['brewerAssignment'] == "S")) echo "CHECKED"; ?>></td>
  <?php } else { ?>
  <td width="1%" class="dataList"><input name="<?php if ($filter == "judges") echo "brewerJudgeAssignedLocation".$row_brewer['id']; if ($filter == "stewards") echo "brewerStewardAssignedLocation".$row_brewer['id']; ?>" type="checkbox" value="<?php echo $bid; ?>" <?php if (($filter == "judges") && strstr($row_brewer['brewerJudgeAssignedLocation'], $bid)) echo "CHECKED"; if (($filter == "stewards") && strstr($row_brewer['brewerStewardAssignedLocation'], $bid)) echo "CHECKED"; ?>></td>
  <?php } ?>
  <td width="10%" class="dataList"><?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?></td>
  <td width="5%" class="dataList"><?php if ($row_brewer['brewerAssignment'] == "J") echo "Judge"; elseif ($row_brewer['brewerAssignment'] == "S") echo "Steward"; else echo "Not Set";?></td>
  <?php if ($totalRows_stewarding2 > 1) { ?>
  <td width="15%" class="dataList">
  <?php if ((($row_brewer['brewerAssignment'] == "J") && (($row_brewer['brewerJudgeAssignedLocation'] != "") || ($row_brewer['brewerJudgeAssignedLocation'] != "0"))) || (($row_brewer['brewerAssignment'] == "S") && (($row_brewer['brewerStewardAssignedLocation'] != "") || ($row_brewer['brewerStewardAssignedLocation'] != "")))) { ?>
		<table class="dataTableCompact">
		<?php 
		if ($row_brewer['brewerAssignment'] == "J") $a = explode(",",$row_brewer['brewerJudgeAssignedLocation']);
		if ($row_brewer['brewerAssignment'] == "S") $a = explode(",",$row_brewer['brewerStewardAssignedLocation']);
		sort($a);
		foreach ($a as $value) {
			if (($value != "") || ($value != 0)) {
				$query_judging_loc3 = sprintf("SELECT judgingLocName,judgingDate,judgingLocation FROM judging_locations WHERE id='%s'", $value);
				$judging_loc3 = mysql_query($query_judging_loc3, $brewing) or die(mysql_error());
				$row_judging_loc3 = mysql_fetch_assoc($judging_loc3);
				echo "<tr>\n<td>".$value.":</td>\n<td>".$row_judging_loc3['judgingLocName']." ("; 
				echo dateconvert($row_judging_loc3['judgingDate'], 3).")</td>\n";
				echo "</td>\n</tr>";
				}
			}
		?>
    	</table>
		<?php } else echo "Not Set"; ?>
  
  </td>
  <?php } if ($filter == "judges") { ?>
  <td width="5%" class="dataList"><?php echo $row_brewer['brewerJudgeID']; ?></td>
  <td width="5%" class="dataList"><?php echo $row_brewer['brewerJudgeRank']; ?></td>
  <td width="10%" class="dataList"><?php echo str_replace(",", ", ", $row_brewer['brewerJudgeLikes']) ?></td>
  <td width="10%" class="dataList"><?php echo str_replace(",", ", ", $row_brewer['brewerJudgeDislikes']) ?></td>
  	<?php } if ($bid != "default") { ?>
  <td class="dataList">
  	<table class="dataTableCompact">
		<?php 
		if ($row_brewer['brewerAssignment'] == "J") $a = explode(",",$row_brewer['brewerJudgeLocation']);
		if ($row_brewer['brewerAssignment'] == "S") $a = explode(",",$row_brewer['brewerStewardLocation']);
		arsort($a);
		foreach ($a as $value) {
			if ($value != "") {
				$b = substr($value, 2);
				$query_judging_loc3 = sprintf("SELECT judgingLocName,judgingDate,judgingLocation FROM judging_locations WHERE id='%s'", $b);
				$judging_loc3 = mysql_query($query_judging_loc3, $brewing) or die(mysql_error());
				$row_judging_loc3 = mysql_fetch_assoc($judging_loc3);
				if (substr($value, 0, 1) == "Y") { 
					echo "<tr>\n<td>".substr($value, 0, 1).":</td>\n<td>".$row_judging_loc3['judgingLocName']." ("; 
					echo dateconvert($row_judging_loc3['judgingDate'], 3).")</td>\n";
					echo "</td>\n</tr>";
				}
				}
			}
		?>
    	</table>
  </td>
  </tr>
  <?php } ?>
  <?php } while ($row_brewer = mysql_fetch_assoc($brewer)); ?>
</tbody>
</table>
<p><input type="submit" class="button" name="Submit" value="<?php if ($action == "update") echo "Assign to ".$row_judging['judgingLocName']; elseif ($action == "assign") { echo "Assign as "; if ($filter == "judges") echo "Judges"; else echo "Stewards"; } else echo "Submit"; ?>" /></p>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default"); ?>">
</form>
<?php } else { if ($action == "update") echo "<div class='error'>No participants have been assigned as a ".rtrim($filter, "s").".</div>"; else echo "<div class='error'>No participants have indicated that they would like to be a ".rtrim($filter, "s").".</div>"; } ?>