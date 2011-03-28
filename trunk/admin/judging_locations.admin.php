<?php 
include(DB.'brewer.db.php');
include(DB.'judging_locations.db.php'); 
?>
<h2><?php if ($action == "add") echo "Add a Judging Location"; elseif ($action == "edit") echo "Edit a Judging Location"; elseif ($action == "update") { echo "Make Final"; if ($filter == "judges") echo " Judge";  elseif ($filter == "stewards") echo " Steward"; else echo ""; echo " Location Assignments"; } elseif ($action == "assign") { echo "Assign Participants as"; if ($filter == "judges") echo " Judges";  elseif ($filter == "stewards") echo " Stewards"; else echo "";  } else echo "Judging Locations"; ?></h2>
<?php if (($filter == "default") && ($msg == "9")) { ?>
<div class="error">Add another judging location, date, or time?</div>
<p><a href="<?php if ($section == "step5") echo "setup.php?section=step5"; else echo "index.php?section=admin&amp;go=judging"; ?>">Yes</a>&nbsp;&nbsp;&nbsp;<a href="<?php if ($section == "step5") echo "setup.php?section=step6"; else echo "index.php?section=admin"; ?>">No</a>
<?php } else { ?> 
    <?php if ($section == "admin") { ?>
	<?php if (($action == "update") || ($action == "assign")) { ?><p><?php if ($bid == "default") echo "Choose ".$filter." to assign.";  else echo "Check below which ".$filter." will be assigned to the ".$row_judging['judgingLocName']. " location."; ?></p><?php }?>
<div class="adminSubNavContainer">
   	<?php if (($action == "default") || ($action == "update") || ($action == "assign")) { ?>
    <span class="adminSubNav">
    	<span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a href="index.php?section=admin">Back to Admin</a>
   	</span>
    <span class="adminSubNav">
    	<span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a href="index.php?section=admin&amp;go=participants">Back to Participants</a>
   	</span>
	<?php } ?>
   	<?php if ((($action == "add") || ($action == "edit")) && ($section != "step3")) { ?>
    <span class="adminSubNav">
    	<span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a href="index.php?section=admin&amp;go=judging">Back to Judging Location List</a>
    </span>
	<?php } elseif (($section != "step3") && ($filter == "default")) { ?>
     <span class="adminSubNav">
    	<span class="icon"><img src="images/award_star_add.png"  /></span><a href="index.php?section=admin&amp;go=judging&amp;action=add">Add a Judging Location</a>
     </span>
	 <?php } ?>
</div>
<?php if (($action == "update") || ($action == "assign")) { ?>
<div class="adminSubNavContainer">
 	<span class="adminSubNav">
    	<span class="icon"><img src="images/user_edit.png"  /></span><a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">Assign Judges</a>
 	</span>
    <span class="adminSubNav">
    	<span class="icon"><img src="images/user_edit.png"  /></span><a href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">Assign Stewards</a>
 	</span>
	<?php if (($totalRows_stewarding2 > 1) && ($row_prefs['prefsCompOrg'] == "N")) { ?>
    <span class="adminSubNav">
    	<span class="icon"><img src="images/user_edit.png"  /></span><a href="index.php?section=admin&amp;action=update&amp;go=judging&amp;filter=judges">Assign Judges to a Location</a>
    </span>
    <span class="adminSubNav">
    	<span class="icon"><img src="images/user_edit.png"  /></span><a href="index.php?section=admin&amp;action=update&amp;go=judging&amp;filter=stewards">Assign Stewards to a Location</a>
	</span>
    <?php } ?>
</div>
	<?php } // end if ($section == "admin") {?>
	<?php } ?>
    <?php if (($action == "default") && ($section != "step5")) {  ?>
    <?php 
	mysql_select_db($database, $brewing);
	$query_judging_locs = "SELECT * FROM judging_locations";
	$judging_locs = mysql_query($query_judging_locs, $brewing) or die(mysql_error());
	$row_judging_locs = mysql_fetch_assoc($judging_locs);
	$totalRows_judging_locs = mysql_num_rows($judging_locs);
	if ($totalRows_judging_locs > 0) { ?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : true,
			"sPaginationType" : "full_numbers",
			"bLengthChange" : true,
			"iDisplayLength" : <?php echo round($row_prefs['prefsRecordPaging']); ?>,
			"sDom": 'irtip',
			"bStateSave" : false,
			"aaSorting": [[0,'asc']],
			"bProcessing" : true,
			"aoColumns": [
				null,
				null,
				null,
				null,
				{ "asSorting": [  ] },
				]
			} );
		} );
</script>
<table class="dataTable" id="sortable">
<thead>
 <tr>
  <th class="dataHeading bdr1B">Name</th>
  <th class="dataHeading bdr1B">Date</th>
  <th class="dataHeading bdr1B">Start Time</th>
  <th class="dataHeading bdr1B">Address</th>
  <th class="dataHeading bdr1B"># of Rounds</th>
  <th class="dataHeading bdr1B">Actions</th>
 </tr>
</thead>
<tbody>
 <?php do { ?>
 <tr>
  <td width="25%" class="dataList"><?php echo $row_judging_locs['judgingLocName']; ?></td>
  <td width="15%" class="dataList"><?php echo dateconvert($row_judging_locs['judgingDate'], 2); ?></td>
  <td width="15%" class="dataList"><?php echo $row_judging_locs['judgingTime']; ?></td>
  <td width="30%" class="dataList"><?php echo $row_judging_locs['judgingLocation']; ?></td>
  <td width="10%" class="dataList"><?php echo $row_judging_locs['judgingRounds']; ?></td>
  <td class="dataList" nowrap="nowrap">
  <span class="icon"><a href="index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_judging_locs['id']; ?>"><img src="images/pencil.png"  border="0" alt="Edit <?php echo $row_judging_locs['judgingLocName']; ?>" title="Edit <?php echo $row_judging_locs['judgingLocName']; ?>"></a></span><span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=judging&amp;action=delete','id',<?php echo $row_judging_locs['id']; ?>,'Are you sure you want to delete the <?php echo $row_judging_locs['judgingLocName']; ?> location?\nThis cannot be undone and will affect all judges and stewards who indicated this location as a preference.');"><img src="images/bin_closed.png"  border="0" alt="Delete <?php echo $row_judging_locs['judgingLocName']; ?>" title="Delete <?php echo $row_judging_locs['judgingLocName']; ?>"></a></span></td>
 </tr>
  <?php } while($row_judging_locs = mysql_fetch_assoc($judging_locs)) ?>
</tbody>
</table>
	<?php } else echo "<p>No judging dates/locations have been specified.</p>"; ?>
    <?php } // end if (($action == "default") && ($section != "step5")) ?>
    <?php if ((($action == "add") || ($action == "edit")) || ($section == "step5")) { ?>
	<form method="post" action="includes/process.inc.php?section=<?php echo $section; ?>&amp;action=<?php if ($section == "step5") echo "add"; else echo $action; ?>&amp;dbTable=judging_locations&amp;go=<?php if ($go == "default") echo "setup"; else echo $go; if ($action == "edit") echo "&amp;id=".$id; ?>" name="form1" onSubmit="return CheckRequiredFields()">
<table>
  <tr>
    <td class="dataLabel">Date:</td>
    <td class="data"><input name="judgingDate" type="text" size="20" onfocus="showCalendarControl(this);" value="<?php if ($action == "edit") echo $row_judging['judgingDate']; ?>"></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Name:</td>
    <td class="data"><input name="judgingLocName" size="30" value="<?php if ($action == "edit") echo $row_judging['judgingLocName']; ?>"></td>
    <td class="data"><span class="required">Required</span> <em>Provide the name of the judging location.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Start Time:</td>
    <td class="data"><input name="judgingTime" size="30" value="<?php if ($action == "edit") echo $row_judging['judgingTime']; ?>"></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Address:</td>
    <td class="data"><textarea name="judgingLocation" cols="40" rows="7" class="mceNoEditor"><?php if ($action == "edit") echo $row_judging['judgingLocation']; ?></textarea></td>
    <td class="data"><span class="required">Required</span> <em>Provide the street address, city, and zip code.</em></td>
  </tr>
  <tr>
    	<td class="dataLabel">Judging Rounds:</td>
    	<td class="data"><input name="judgingRounds" size="5" value="<?php if ($action == "edit") echo $row_judging['judgingRounds']; else echo "2"; ?>"></td>
        <td class="data"><span class="required">Required</span> <em>Provide the number of judging rounds anticipated for this location (<strong>not</strong> including Best of Show).</em></td>
  	</tr>
</table>
<input type="submit" class="button" value="<?php if ($action == "edit") echo "Update"; else echo "Submit";?>">
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default"); ?>">
</form>
<?php } // end else ?>


<?php if ((($action == "update") && ($filter != "default") && ($bid != "default")) || ($action == "assign")) { ?>
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
				<?php if (($totalRows_stewarding2 > 1) && ($row_prefs['prefsCompOrg'] == "N")) { ?>
				{ "asSorting": [  ] },
				<?php } ?>
				<?php if ($filter == "judges") { ?>
				null,
				null,
					<?php if ($row_prefs['prefsCompOrg'] == "N") { ?>
				null,
				null,
					<?php } ?>
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
  <?php if (($totalRows_stewarding2 > 1) && ($row_prefs['prefsCompOrg'] == "N")) { ?>
  <th class="dataHeading bdr1B">Assigned To</th>
  <?php } ?>
  <?php if ($filter == "judges") { ?>
  <th class="dataHeading bdr1B">ID</th>
  <th class="dataHeading bdr1B">Rank</th>
  	<?php if ($row_prefs['prefsCompOrg'] == "N") { ?>
  <th class="dataHeading bdr1B">Likes</th>
  <th class="dataHeading bdr1B">Dislikes</th>
  	<?php } ?>
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
  <?php if (($totalRows_stewarding2 > 1) && ($row_prefs['prefsCompOrg'] == "N")) { ?>
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
  <?php if ($row_prefs['prefsCompOrg'] == "N") { ?>
  <td width="10%" class="dataList"><?php echo str_replace(",", ", ", $row_brewer['brewerJudgeLikes']) ?></td>
  <td width="10%" class="dataList"><?php echo str_replace(",", ", ", $row_brewer['brewerJudgeDislikes']) ?></td>
  <?php } ?>
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
<?php } // end if ((($action == "update") && ($filter != "default") && ($bid != "default")) || ($action == "assign")) ?>
 
<?php if (($action == "update") && ($bid == "default")) {  ?>
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
<?php } // end if (($action == "update") && ($bid == "default")) ?>