<h2><?php 
if ($filter == "judges") echo "Available Judges"; 
elseif ($filter == "stewards") echo "Available Stewards";
elseif ($filter == "assignJudges") echo "Assigned Judges"; 
elseif ($filter == "assignStewards") echo "Assigned Stewards"; 
elseif ($action == "add") echo "Add Participant"; 
else echo "Participants"; 
if ($dbTable != "default") echo ": ".$dbTable; ?></h2>
<?php if ($action != "print") { ?>
<table class="dataTable">
<tr>
  <td class="dataList" width="5%" nowrap="nowrap"><a href="index.php?section=admin">&laquo; Back to Admin</a></td>
  <?php if ($action != "add") { // 1?>
  		<?php if ($dbTable != "default") { // 1.1 ?>
 	 	<td class="dataList" width="5%" nowrap="nowrap"><a href="index.php?section=admin&go=archive">&laquo; Back to Archives</a></td>
  		<?php } // end 1.1 ?>
  		<td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/user_add.png" align="absmiddle" /></span><a class="data" href="index.php?section=admin&go=participants&action=add">Add Participant</a></td>
  		<?php if ($dbTable == "default") { // 1.2 ?>
        <td class="dataList" width="5%" nowrap="nowrap">
        	<span class="icon">View:</span>
                    <select name="view_participants" id="view_participants" onchange="jumpMenu('self',this,0)">
                    <option value="index.php?section=admin&go=participants"<?php if ($filter == "default") echo " SELECTED"; ?>>All Participants</option>
                    <option value="index.php?section=admin&go=participants&filter=judges"<?php if ($filter == "judges") echo " SELECTED"; ?>>Available Judges</option>
                    <option value="index.php?section=admin&go=participants&filter=stewards"<?php if ($filter == "stewards") echo " SELECTED"; ?>>Available Stewards</option>
                    <option value="index.php?section=admin&go=participants&filter=assignJudges"<?php if ($filter == "assignJudges") echo " SELECTED"; ?>>Assigned Judges</option>
                    <option value="index.php?section=admin&go=participants&filter=assignStewards"<?php if ($filter == "assignStewards") echo " SELECTED"; ?>>Assigned Stewards</option>
                    </select>   
   		<?php } // end 1.2 ?>
 <td class="dataList">&nbsp;</td>
 </tr>
 <?php if (($action != "add") && ($dbTable == "default")) { // 2  ?>
 <tr>
 	<td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/user_edit.png" align="absmiddle" /></span><a class="data" href="index.php?section=admin&action=assign&go=judging&filter=judges">Assign Judges</a></td>
 	<td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/user_edit.png" align="absmiddle" /></span><a class="data" href="index.php?section=admin&action=assign&go=judging&filter=stewards">Assign Stewards</a></td>
 	<td class="dataList" width="5%" nowrap="nowrap"><?php if ($totalRows_judging > 1) { ?><span class="icon"><img src="images/user_edit.png" align="absmiddle" /></span><a class="data" href="index.php?section=admin&action=update&go=judging&filter=judges">Assign Judges to a Location</a><?php } else echo "&nbsp;"; ?></td>
 	<td class="dataList" nowrap="nowrap"><?php if ($totalRows_judging > 1) { ?><span class="icon"><img src="images/user_edit.png" align="absmiddle" /></span><a class="data" href="index.php?section=admin&action=update&go=judging&filter=stewards">Assign Stewards to a Location</a><?php } else echo "&nbsp;"; ?></td>
 </tr>
 <tr>
 	<td class="dataList" colspan="4"><span class="icon"><img src="images/printer.png" align="absmiddle" /></span><a class="data thickbox" href="print.php?section=<?php echo $section; ?>&go=<?php echo $go; ?>&action=print&filter=<?php echo $filter; ?>&KeepThis=true&TB_iframe=true&height=450&width=750" title="Print List of <?php if ($filter == "judges") echo "Available Judges"; elseif ($filter == "stewards") echo "Available Stewards"; elseif ($filter == "assignJudges") echo "Assigned Judges"; 
elseif ($filter == "assignStewards") echo "Assigned Stewards"; else echo "Participants"; ?>">Print List of <?php if ($filter == "judges") echo "Available Judges"; elseif ($filter == "stewards") echo "Available Stewards"; elseif ($filter == "assignJudges") echo "Assigned Judges"; 
elseif ($filter == "assignStewards") echo "Assigned Stewards"; else echo "Participants"; ?></a></td>
 </tr>
 <tr>
 	<td class="dataList" colspan="4">Total: <?php echo $totalRows_brewer; ?></td>
 </tr>
 </table>
 <?php } // end 2
} 
}
if (($action == "default") || ($action == "print")) { 
if ($totalRows_brewer > 0) { ?>
<table class="dataTable">
  <tr>
    <td class="dataHeading bdr1B">Last</td>
    <td class="dataHeading bdr1B">First</td>
    <td class="dataHeading bdr1B">Info</td>
    <td class="dataHeading bdr1B">Club</td>
  <?php if ($filter == "default") { ?>
    <td class="dataHeading bdr1B">Steward?</td>
    <td class="dataHeading bdr1B">Judge?</td>
    <td class="dataHeading bdr1B">Assigned As</td>
  <?php } 
	if ($totalRows_judging > 1) { ?>
    <td class="dataHeading bdr1B">Assigned To</td>
  <?php } if ($filter != "default") { ?>
    <?php if ($filter == "judges") { ?>
    <td class="dataHeading bdr1B">ID</td>
    <td class="dataHeading bdr1B">Rank</td>
    <td class="dataHeading bdr1B">Likes</td>
    <td class="dataHeading bdr1B">Dislikes</td>
    <?php } ?>
  <?php } if ($action != "print") { ?>
    <td class="dataHeading bdr1B">Actions</td>
  <?php } ?>
  </tr>
<?php do { 
    if ($row_brewer['brewerAssignment'] == "J") $query_judging2 = sprintf("SELECT * FROM judging WHERE id='%s'", $row_brewer['brewerJudgeAssignedLocation']);
	if ($row_brewer['brewerAssignment'] == "S") $query_judging2 = sprintf("SELECT * FROM judging WHERE id='%s'", $row_brewer['brewerStewardAssignedLocation']);
	$judging2 = mysql_query($query_judging2, $brewing) or die(mysql_error());
	$row_judging2 = mysql_fetch_assoc($judging2);
	
	$query_user1 = sprintf("SELECT id FROM users WHERE user_name = '%s'", $row_brewer['brewerEmail']);
	$user1 = mysql_query($query_user1, $brewing) or die(mysql_error());
	$row_user1 = mysql_fetch_assoc($user1);
?>
  <tr <?php echo " style=\"background-color:$color\"";?>>
    <td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="8%"><?php echo $row_brewer['brewerLastName']; ?></td>
    <td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="8%"><?php echo $row_brewer['brewerFirstName']; ?></td>
    <td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="12%"><?php echo $row_brewer['brewerAddress']; ?><br><?php echo $row_brewer['brewerCity'].", ".$row_brewer['brewerState']." ".$row_brewer['brewerZip']; ?><br /><a href="mailto:<?php echo $row_brewer['brewerEmail']; ?>?Subject=<?php if ($filter == "judges") echo "Judging at ".$row_contest_info['contestName']; elseif ($filter == "stewards") echo "Stewarding at ".$row_contest_info['contestName']; else echo $row_contest_info['contestName'];  ?>"><?php echo $row_brewer['brewerEmail']; ?></a><br /><?php if ($row_brewer['brewerPhone1'] != "") echo $row_brewer['brewerPhone1']." (H)<br>";  if ($row_brewer['brewerPhone2'] != "") echo $row_brewer['brewerPhone2']." (W)"; ?></td>
    <td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="12%"><?php echo $row_brewer['brewerClubs']; ?></td>
  	<?php if ($filter == "default") { ?>
    	<td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="5%"><?php if ($row_brewer['brewerSteward'] == "Y") echo "<img src='images/tick.png'>"; else echo "<img src='images/cross.png'>"?></td>
    	<td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="5%"><?php if ($row_brewer['brewerJudge'] == "Y") echo "<img src='images/tick.png'>"; else echo "<img src='images/cross.png'>" ?></td>
    	<td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="5%"><?php if ($row_brewer['brewerAssignment'] == "J") echo "Judge"; elseif ($row_brewer['brewerAssignment'] == "S") echo "Steward"; else echo "Not Set";?></td>
  	<?php } if ($totalRows_judging > 1) { ?>
    	<td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="15%"><?php if ((($row_brewer['brewerAssignment'] == "J") && ($row_brewer['brewerJudgeAssignedLocation'] != "")) || (($row_brewer['brewerAssignment'] == "S") && ($row_brewer['brewerStewardAssignedLocation'] != ""))) { echo $row_judging2['judgingLocName']."<br>("; echo dateconvert($row_judging2['judgingDate'], 3).")"; } else echo "Not Set"; ?></td>
	<?php } if ($filter != "default") { ?>
    	<?php if ($filter == "judges") { ?>
    	<td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="8%"><?php echo $row_brewer['brewerJudgeID']; ?></td>
    	<td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="8%"><?php echo $row_brewer['brewerJudgeRank']; ?></td>
    	<td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="8%"><?php echo str_replace(",", ", ", $row_brewer['brewerJudgeLikes']); ?></td>
    	<td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="8%"><?php echo str_replace(",", ", ", $row_brewer['brewerJudgeDislikes']); ?></td>
	  	<?php } ?>
  <?php } if ($action != "print") { ?>
    <td class="dataList" nowrap="nowrap">
    <span class="icon"><a href="index.php?section=brew&go=entries&filter=<?php echo $row_user1['id']; ?>&action=add"><img src="images/book_add.png" align="absmiddle" border="0" alt="Add an entry for <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?>" title="Add an entry for <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?>"></a></span> 
    <span class="icon"><a href="index.php?section=brewer&go=<?php echo $go; ?>&filter=<?php echo $row_brewer['id']; ?>&action=edit&id=<?php echo $row_brewer['id']; ?>"><img src="images/pencil.png" align="absmiddle" border="0" alt="Edit <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?>" title="Edit <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?>"></a></span>
    <span class="icon"><a href="index.php?section=admin&go=make_admin&username=<?php echo $row_brewer['brewerEmail'];?>"><img src="images/lock_edit.png" align="absmiddle" border="0" alt="Change <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?>'s User Level" title="Change <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?>'s User Level"></a></span>
    <span class="icon"><?php if ($row_brewer['brewerEmail'] == $_SESSION['loginUsername']) echo "&nbsp;"; else { ?><a href="javascript:DelWithCon('includes/process.inc.php?section=<?php echo $section; ?>&go=<?php echo $go; ?>&dbTable=brewer&action=delete&username=<?php echo $row_brewer['brewerEmail'];?>','id',<?php echo $row_brewer['id']; ?>,'Are you sure you want to delete the participant <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?>?');"><img src="images/bin_closed.png" align="absmiddle" border="0" alt="Delete <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?>" title="Delete <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?>"></a><?php } ?></span>
    </td> 
  <?php } ?> 
  </tr>
  <?php if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
<?php } while ($row_brewer = mysql_fetch_assoc($brewer)); ?>
<?php if ($action != "print") { ?>
  <tr>
  	<td class="bdr1T" colspan="13">&nbsp;</td>
  </tr>
<?php } ?>
</table>
<?php } if ($totalRows_brewer == 0) { ?>

<?php 
if ($filter == "default") echo "<div class='error'>There are no participants yet.</div>"; 
if (($filter == "judges") || ($filter == "assignJudges")) echo "<div class='error'>There are no judges available yet.</div>"; 
if (($filter == "stewards") || ($filter == "assignStewards"))  echo "<div class='error'>There are no stewards available yet.</div>"; 
}
} // end if ($action == "default")

if ($action == "add")  { 
	if ($filter == "default") { ?>
<script type="text/javascript" src="js_includes/email_check.js"></script>
<form action="includes/process.inc.php?action=add&dbTable=users&section=<?php echo $section; ?>&go=<?php echo $go; ?>" method="POST" name="form1" id="form1" onSubmit="return CheckRequiredFields()">
<table>
	<tr>
    	<td class="dataLabel">Email Address:</td>
    	<td class="data"><input name="user_name" id="user_name" type="text" class="submit" size="40" onchange="AjaxFunction(this.value);"><div id="msg">Email Format:</div></td>
        <td class="data" id="inf_email"><span class="required">Required</span></td>
  	</tr>
  	<tr>
    	<td class="dataLabel">Password:</td>
    	<td class="data"><input name="password" id="password" type="password" class="submit" size="25"></td>
        <td class="data"><span class="required">Required</span></td>
  	</tr>

  	<tr>
    	<td class="dataLabel">&nbsp;</td>
    	<td class="data"><input type="submit" class="button" value="Register"></td> 
        <td class="data">&nbsp;</td>
  	</tr>
</table>
<input type="hidden" name="userLevel" value="2" />
</form>
<?php } 
if ($filter == "info") { 
if (($action == "add") || (($action == "edit") && (($_SESSION["loginUsername"] == $row_brewer['brewerEmail'])) || ($row_user['userLevel'] == "1")))  { ?>
<form action="includes/process.inc.php?section=<?php echo "admin&go=".$go."&filter=".$filter; ?>&action=<?php echo $action; ?>&dbTable=brewer" method="POST" name="form1" id="form1" onSubmit="return CheckRequiredFields()">
<table class="dataTable">
<tr>
      <td class="dataLabel" width="5%">Email:</td>
      <td class="data" width="20%"><?php echo $username; ?></td>
      <td class="data">&nbsp;</td>
</tr>
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
<tr>
	  <td>&nbsp;</td>
      <td colspan="2" class="data"><input name="submit" type="submit" class="button" value="Submit Brewer Information" /></td>
    </tr>
</table>
<input name="brewerEmail" type="hidden" value="<?php echo $username; ?>" />
<input name="uid" type="hidden" value="<?php echo $row_user_level['id']; ?>" />
<input name="brewerJudge" type="hidden" value="N" />
<input name="brewerSteward" type="hidden" value="N" />
</form>
<?php 
	  }  
   }
} 
?>