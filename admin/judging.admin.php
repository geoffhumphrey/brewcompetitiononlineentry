<h2><?php if ($action == "add") echo "Add a Judging Location"; elseif ($action == "edit") echo "Edit a Judging Location"; elseif ($action == "update") { echo "Make Final"; if ($filter == "judges") echo " Judge";  elseif ($filter == "stewards") echo " Steward"; else echo ""; echo " Location Assignments"; } elseif ($action == "assign") { echo "Assign Participants as"; if ($filter == "judges") echo " Judges";  elseif ($filter == "stewards") echo " Stewards"; else echo "";  } else echo "Judging Locations"; ?></h2>
<?php if (($action == "add") || ($section == "step3")) { ?><div class="info">If your competition judging will be held on the same day and location, but separated into two or more designated times (i.e., an AM session and a PM session), define each separately.</div>
<?php } if ($msg == "9"){ ?>
<div class="info">Add another judging location, date, or time?</div>
<p><a href="<?php if ($section == "step3") echo "setup.php?section=step3"; else echo "index.php?section=admin&go=judging"; ?>">Yes</a>&nbsp;&nbsp;&nbsp;<a href="<?php if ($section == "step3") echo "setup.php?section=step4"; else echo "index.php?section=admin"; ?>">No</a>
<?php } else { ?>
<table class="dataTable">
 <tr>
   <?php if (($action == "default") || ($action == "update") || ($action == "assign")) { ?><td class="dataList" width="5%" nowrap="nowrap"><a href="index.php?section=admin">&laquo; Back to Admin</a></td><?php } ?>
   <?php if ((($action == "add") || ($action == "edit")) && ($section != "step3")) { ?><td class="dataList"><a href="index.php?section=admin&go=judging">&laquo;  Back to Judging Location List</a></td><?php } elseif (($section != "step3") && ($filter == "default")) { ?><td class="dataList"><span class="icon"><img src="images/award_star_add.png" align="absmiddle" /></span><a class="data" href="index.php?section=admin&go=judging&action=add">Add a Judging Location</a></td><?php } ?>
   <?php if (($action == "update") || ($action == "assign")) { ?><td class="dataList"><?php if ($bid == "default") echo "Choose ".$filter." to assign.";  else echo "Check below which ".$filter." will be assigned to the ".$row_judging['judgingLocName']. " location."; ?></td><?php }?>
 </tr>
</table>
<?php if (($action == "update") || ($action == "assign")) { ?>
<table class="dataTable">
<tr>
 <td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/user_edit.png" align="absmiddle" /></span><a class="data" href="index.php?section=admin&action=assign&go=judging&filter=judges">Assign Judges</a></td>
 <td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/user_edit.png" align="absmiddle" /></span><a class="data" href="index.php?section=admin&action=assign&go=judging&filter=stewards">Assign Stewards</a></td>
 <td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/user_edit.png" align="absmiddle" /></span><a class="data" href="index.php?section=admin&action=update&go=judging&filter=judges">Assign Judges to a Location</a></td>
 <td class="dataList"><span class="icon"><img src="images/user_edit.png" align="absmiddle" /></span><a class="data" href="index.php?section=admin&action=update&go=judging&filter=stewards">Assign Stewards to a Location</a></td>
 </tr>
</table> 
<?php } 
if ((($action == "add") || ($action == "edit")) || ($section == "step3")) { ?>
<form method="post" action="includes/process.inc.php?section=<?php echo $section; ?>&action=<?php if ($section == "step3") echo "add"; else echo $action; ?>&dbTable=judging&go=<?php if ($go == "default") echo "setup"; else echo $go; if ($action == "edit") echo "&id=".$id; ?>" name="form1" onSubmit="return CheckRequiredFields()">
<table>
  <tr>
    <td class="dataLabel">Date:</td>
    <td class="data"><input name="judgingDate" type="text" size="20" onfocus="showCalendarControl(this);" value="<?php if ($action == "edit") echo $row_judging['judgingDate']; ?>"></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Name:</td>
    <td class="data"><input name="judgingLocName" size="30" value="<?php if ($action == "edit") echo $row_judging['judgingLocName']; ?>"></td>
    <td class="data"><span class="required">Required</span> <em>Provide the name of the judging location</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Start Time:</td>
    <td class="data"><input name="judgingTime" size="30" value="<?php if ($action == "edit") echo $row_judging['judgingTime']; ?>"></td>
    <td class="data"><span class="required">Required</span></td>
  </tr>
  <tr>
    <td class="dataLabel">Address:</td>
    <td class="data"><textarea name="judgingLocation" cols="40" rows="7" class="mceNoEditor"><?php if ($action == "edit") echo $row_judging['judgingLocation']; ?></textarea></td>
    <td class="data"><span class="required">Required</span> <em>Provide the street address, city, and zip code</em></td>
  </tr>
  <tr>
    	<td class="dataLabel">&nbsp;</td>
    	<td class="data"><input type="submit" class="button" value="Submit"></td>
        <td class="data">&nbsp;</td>
  	</tr>
</table>
</form>
<?php } ?>
<?php if (($action == "default") && ($section != "step3")) { ?>
<table class="dataTable">
 <tr>
  <td class="dataHeading bdr1B">Name</td>
  <td class="dataHeading bdr1B">Date</td>
  <td class="dataHeading bdr1B">Start Time</td>
  <td class="dataHeading bdr1B">Address</td>
  <td class="dataHeading bdr1B">Actions</td>
 </tr>
 <?php do { ?>
 <tr <?php echo " style=\"background-color:$color\"";?>>
  <td width="25%" class="dataList"><?php echo $row_judging['judgingLocName']; ?></td>
  <td width="15%" class="dataList"><?php echo dateconvert($row_judging['judgingDate'], 2); ?></td>
  <td width="15%" class="dataList"><?php echo $row_judging['judgingTime']; ?></td>
  <td width="30%" class="dataList"><?php echo $row_judging['judgingLocation']; ?></td>
  <td class="dataList">
  <span class="icon"><a href="index.php?section=admin&go=<?php echo $go; ?>&action=edit&id=<?php echo $row_judging['id']; ?>"><img src="images/pencil.png" align="absmiddle" border="0" alt="Edit <?php echo $row_judging['judgingLocName']; ?>" title="Edit <?php echo $row_judging['judgingLocName']; ?>"></a></span><span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=admin&go=<?php echo $go; ?>&dbTable=judging&action=delete','id',<?php echo $row_judging['id']; ?>,'Are you sure you want to delete the <?php echo $row_judging['judgingLocName']; ?> location?\nThis cannot be undone and will affect all judges and stewards who indicated this location as a preference.');"><img src="images/bin_closed.png" align="absmiddle" border="0" alt="Delete <?php echo $row_judging['judgingLocName']; ?>" title="Delete <?php echo $row_judging['judgingLocName']; ?>"></a></span></td>
 </tr>
  <?php if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
  <?php } while($row_judging = mysql_fetch_assoc($judging)) ?>
 <tr>
 	<td colspan="5" class="bdr1T">&nbsp;</td>
 </tr>
</table>
<?php } ?>
<?php } ?>

<?php
// Assign Judges by Location
if ((($action == "update") && ($filter != "default") && ($bid != "default"))  || ($action == "assign")) { ?>
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
  <?php if ($filter == "judges") { ?>
  <td class="dataHeading bdr1B">ID</td>
  <td class="dataHeading bdr1B">Rank</td>
  <td class="dataHeading bdr1B">Likes</td>
  <td class="dataHeading bdr1B">Dislikes</td>
  <?php } if ($bid != "default") { ?>
  <td class="dataHeading bdr1B">1st Location Pref</td>
  <td class="dataHeading bdr1B">2nd Location Pref</td>
 </tr>
 <?php } do { 
    if ($bid != "default") { 
 	$query_judging2 = sprintf("SELECT * FROM judging WHERE id='%s'", $row_brewer['brewerJudgeLocation']);
	$judging2 = mysql_query($query_judging2, $brewing) or die(mysql_error());
	$row_judging2 = mysql_fetch_assoc($judging2);
	
	$query_judging3 = sprintf("SELECT * FROM judging WHERE id='%s'", $row_brewer['brewerJudgeLocation2']);
	$judging3 = mysql_query($query_judging3, $brewing) or die(mysql_error());
	$row_judging3 = mysql_fetch_assoc($judging3);
	}
 ?>
 <tr <?php echo " style=\"background-color:$color\"";?>>
  <input type="hidden" name="id[]" value="<?php echo $row_brewer['id']; ?>" />
  <?php if ($bid == "default") { ?>
  <td width="1%" class="dataList"><input name="brewerAssignment<?php echo $row_brewer['id']; ?>" type="checkbox" value="<?php if ($filter == "judges") echo "J"; if ($filter == "stewards") echo "S"; ?>" <?php if (($filter == "judges") && ($row_brewer['brewerAssignment'] == "J")) echo "CHECKED"; if (($filter == "stewards") && ($row_brewer['brewerAssignment'] == "S")) echo "CHECKED"; ?>></td>
  <?php } else { ?>
  <td width="1%" class="dataList"><input name="<?php if ($filter == "judges") echo "brewerJudgeAssignedLocation".$row_brewer['id']; if ($filter == "stewards") echo "brewerStewardAssignedLocation".$row_brewer['id']; ?>" type="checkbox" value="<?php echo $bid; ?>" <?php if (($filter == "judges") && ($row_brewer['brewerJudgeAssignedLocation'] == $bid)) echo "CHECKED"; if (($filter == "stewards") && ($row_brewer['brewerStewardAssignedLocation'] == $bid)) echo "CHECKED"; ?>></td>
  <?php } ?>
  <td width="10%" class="dataList"><?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?></td>
  <?php if ($filter == "judges") { ?>
  <td width="5%" class="dataList"><?php echo $row_brewer['brewerJudgeID']; ?></td>
  <td width="5%" class="dataList"><?php echo $row_brewer['brewerJudgeRank']; ?></td>
  <td width="10%" class="dataList"><?php echo $row_brewer['brewerJudgeLikes']; ?></td>
  <td width="10%" class="dataList"><?php echo $row_brewer['brewerJudgeDislikes']; ?></td>
  <?php } if ($bid != "default") { ?>
  <td width="15%" class="dataList"><?php if ($row_brewer['brewerJudgeLocation'] < "99999998") { echo $row_judging2['judgingLocName']." ("; echo dateconvert($row_judging2['judgingDate'], 3).")"; } else echo "No Preference"; ?></td>
  <td width="15%" class="dataList"><?php if ($row_brewer['brewerJudgeLocation2'] < "99999998") { echo $row_judging3['judgingLocName']." ("; echo dateconvert($row_judging3['judgingDate'], 3).")"; } else echo "No Preference"; ?></td>
  </tr>
  <?php } if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
  <?php } while ($row_brewer = mysql_fetch_assoc($brewer)); ?>
 <tr>
 	<td colspan="9" class="bdr1T"><input type="submit" class="button" name="Submit" value="<?php if ($action == "update") echo "Assign to ".$row_judging['judgingLocName']; elseif ($action == "assign") { echo "Assign as "; if ($filter == "judges") echo "Judges"; else echo "Stewards"; } else echo "Submit"; ?>" /></td>
 </tr>
</table>
</form>
<?php } ?>

<?php if (($action == "update") && ($bid == "default")) { ?>
<table>
 <tr>
   <td class="dataLabel">Assign <?php if ($filter == "judges") echo "Judges"; if ($filter == "stewards") echo "Stewards"; ?> To:</td>
   <td class="data">
   <select name="judge_loc" id="judge_loc" onchange="jumpMenu('self',this,0)">
	<option value=""></option>
    <?php do { ?>
	<option value="index.php?section=admin&action=update&go=judging&filter=<?php echo $filter; ?>&bid=<?php echo $row_judging['id']; ?>"><?php  echo $row_judging['judgingLocName']." ("; echo dateconvert($row_judging['judgingDate'], 3).")"; ?></option>
    <?php } while ($row_judging = mysql_fetch_assoc($judging)); ?>
  </select>
  </td>
</tr>
</table>
<?php } ?>