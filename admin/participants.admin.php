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
  <td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a class="data" href="index.php?section=admin">Back to Admin</a></td>
  <?php if ($action != "add") { // 1?>
  		<?php if ($dbTable != "default") { // 1.1 ?>
 	 	<td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a class="data" href="index.php?section=admin&amp;go=archive">Back to Archives</a></td>
  		<?php } // end 1.1 ?>
  		<td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/user_add.png"  /></span><a class="data" href="index.php?section=admin&amp;go=participants&amp;action=add">Add a Participant</a></td>
  		<?php if ($dbTable == "default") { // 1.2 ?>
        <td class="dataList" width="5%" nowrap="nowrap">
  		<span class="icon"><img src="images/page.png" /></span>
  		<div class="menuBar"><a class="menuButton" href="#" onclick="#" onmouseover="buttonMouseover(event, 'printMenu_entries');">View...</a>
  		</div>
  		<div id="printMenu_entries" class="menu" onmouseover="menuMouseover(event)">
  		<a class="menuItem" href="index.php?section=admin&amp;go=participants">All Participants</a>
  		<a class="menuItem" href="index.php?section=admin&amp;go=participants&amp;filter=judges">Available Judges</a>
  		<a class="menuItem" href="index.php?section=admin&amp;go=participants&amp;filter=stewards">Available Stewards</a>
        <a class="menuItem" href="index.php?section=admin&amp;go=participants&amp;filter=assignJudges">Assigned Judges</a>
        <a class="menuItem" href="index.php?section=admin&amp;go=participants&amp;filter=assignStewards">Assigned Stewards</a>
  		</div>
  		</td>
   		<?php } // end 1.2 ?>
 <td class="dataList">&nbsp;</td>
 </tr>
 <?php if (($action != "add") && ($dbTable == "default")) { // 2  ?>
 <tr>
 	<td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/user_edit.png"  /></span><a class="data" href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">Assign Judges</a></td>
 	<td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/user_edit.png"  /></span><a class="data" href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">Assign Stewards</a></td>
 	<td class="dataList" width="5%" nowrap="nowrap"><?php if ($totalRows_judging > 1) { ?><span class="icon"><img src="images/user_edit.png"  /></span><a class="data" href="index.php?section=admin&amp;action=update&amp;go=judging&amp;filter=judges">Assign Judges to a Location</a><?php } else echo "&nbsp;"; ?></td>
 	<td class="dataList" nowrap="nowrap"><?php if ($totalRows_judging > 1) { ?><span class="icon"><img src="images/user_edit.png"  /></span><a class="data" href="index.php?section=admin&amp;action=update&amp;go=judging&amp;filter=stewards">Assign Stewards to a Location</a><?php } else echo "&nbsp;"; ?></td>
 </tr>
 <tr>
 	<td class="dataList" colspan="4"><span class="icon"><img src="images/printer.png"  border="0" alt="Print" /></span><a class="data thickbox" href="print.php?section=<?php echo $section; ?>&amp;go=<?php echo $go; ?>&amp;action=print&amp;filter=<?php echo $filter; ?>&KeepThis=true&amp;TB_iframe=true&amp;height=450&amp;width=750" title="Print List of <?php if ($filter == "judges") echo "Available Judges"; elseif ($filter == "stewards") echo "Available Stewards"; elseif ($filter == "assignJudges") echo "Assigned Judges"; 
elseif ($filter == "assignStewards") echo "Assigned Stewards"; else echo "Participants"; ?>">Print This List of <?php if ($filter == "judges") echo "Available Judges"; elseif ($filter == "stewards") echo "Available Stewards"; elseif ($filter == "assignJudges") echo "Assigned Judges"; 
elseif ($filter == "assignStewards") echo "Assigned Stewards"; else echo "Participants"; ?></a></td>
 </tr>
 </table>
 <?php } // end 2
} 
}
if (($action == "default") || ($action == "print")) { 
if ($totalRows_brewer > 0) { 
	if ($action != "print") { ?>
	<link href="css/sorting.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
		"aaSorting": [[0,'asc']],
		"bStateSave" : false,
		"sPaginationType" : "full_numbers",
		"bLengthChange" : true,
		"iDisplayLength" : 25,
		"bProcessing" : true,
		"aoColumns": [
				null,
				null,
				{ "asSorting": [  ] },
				null,
				<?php if ($filter == "default") { ?>
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				null,
				<?php } 
				if ($totalRows_judging > 1) { ?>
    			{ "asSorting": [  ] },
  				<?php } if ($filter != "default") { ?>
    			<?php if ($filter == "judges") { ?>
   				null,
    			null,
    			null,
    			null,
    			<?php } ?>
				<?php } ?>
				{ "asSorting": [  ] }
			]
		} );
	} );
	</script>
   <?php } ?>
<table class="dataTable" id="sortable">
<thead>
  <tr>
    <th class="dataHeading bdr1B">Last</th>
    <th class="dataHeading bdr1B">First</th>
    <th class="dataHeading bdr1B">Info</th>
    <th class="dataHeading bdr1B">Club</th>
  <?php if ($filter == "default") { ?>
    <th class="dataHeading bdr1B">Steward?</th>
    <th class="dataHeading bdr1B">Judge?</th>
    <th class="dataHeading bdr1B">Assigned As</th>
  <?php } 
	if ($totalRows_judging > 1) { ?>
    <th class="dataHeading bdr1B">Assigned To</th>
  <?php } if ($filter != "default") { ?>
    <?php if ($filter == "judges") { ?>
    <th class="dataHeading bdr1B">ID</th>
    <th class="dataHeading bdr1B">Rank</th>
    <th class="dataHeading bdr1B">Likes</th>
    <th class="dataHeading bdr1B">Dislikes</th>
    <?php } ?>
  <?php } if ($action != "print") { ?>
    <th class="dataHeading bdr1B">Actions</th>
  <?php } ?>
  </tr>
</thead>
<tbody>
<?php do { 
    if ($row_brewer['brewerAssignment'] == "J") $query_judging2 = sprintf("SELECT * FROM judging WHERE id='%s'", $row_brewer['brewerJudgeAssignedLocation']);
	if ($row_brewer['brewerAssignment'] == "S") $query_judging2 = sprintf("SELECT * FROM judging WHERE id='%s'", $row_brewer['brewerStewardAssignedLocation']);
	$judging2 = mysql_query($query_judging2, $brewing) or die(mysql_error());
	$row_judging2 = mysql_fetch_assoc($judging2);
	
	$query_user1 = sprintf("SELECT id FROM users WHERE user_name = '%s'", $row_brewer['brewerEmail']);
	$user1 = mysql_query($query_user1, $brewing) or die(mysql_error());
	$row_user1 = mysql_fetch_assoc($user1);
?>
  <tr>
    <td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="8%"><?php echo $row_brewer['brewerLastName']; ?></td>
    <td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="8%"><?php echo $row_brewer['brewerFirstName']; ?></td>
    <td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="12%"><?php echo $row_brewer['brewerAddress']; ?><br><?php echo $row_brewer['brewerCity'].", ".$row_brewer['brewerState']." ".$row_brewer['brewerZip']; ?><br /><a href="mailto:<?php echo $row_brewer['brewerEmail']; ?>?Subject=<?php if ($filter == "judges") echo "Judging at ".$row_contest_info['contestName']; elseif ($filter == "stewards") echo "Stewarding at ".$row_contest_info['contestName']; else echo $row_contest_info['contestName'];  ?>"><?php echo $row_brewer['brewerEmail']; ?></a><br /><?php if ($row_brewer['brewerPhone1'] != "") echo $row_brewer['brewerPhone1']." (H)<br>";  if ($row_brewer['brewerPhone2'] != "") echo $row_brewer['brewerPhone2']." (W)"; ?></td>
    <td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="12%"><?php echo $row_brewer['brewerClubs']; ?></td>
  	<?php if ($filter == "default") { ?>
    	<td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="5%"><?php if ($row_brewer['brewerSteward'] == "Y") echo "<img src='images/tick.png'>"; else echo "<img src='images/cross.png'>"?></td>
    	<td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="5%"><?php if ($row_brewer['brewerJudge'] == "Y") echo "<img src='images/tick.png'>"; else echo "<img src='images/cross.png'>" ?></td>
    	<td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="10%"><?php if ($row_brewer['brewerAssignment'] == "J") echo "Judge"; elseif ($row_brewer['brewerAssignment'] == "S") echo "Steward"; else echo "Not Set";?></td>
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
    <span class="icon"><a href="index.php?section=brew&amp;go=entries&amp;filter=<?php echo $row_user1['id']; ?>&amp;action=add"><img src="images/book_add.png"  border="0" alt="Add an entry for <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?>" title="Add an entry for <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?>"></a></span> 
    <span class="icon"><a href="index.php?section=brewer&amp;go=<?php echo $go; ?>&amp;filter=<?php echo $row_brewer['id']; ?>&amp;action=edit&amp;id=<?php echo $row_brewer['id']; ?>"><img src="images/pencil.png"  border="0" alt="Edit <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?>" title="Edit <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?>"></a></span>
    <span class="icon"><a href="index.php?section=admin&amp;go=make_admin&amp;username=<?php echo $row_brewer['brewerEmail'];?>"><img src="images/lock_edit.png"  border="0" alt="Change <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?>'s User Level" title="Change <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?>'s User Level"></a></span>
    <span class="icon"><?php if ($row_brewer['brewerEmail'] == $_SESSION['loginUsername']) echo "&nbsp;"; else { ?><a href="javascript:DelWithCon('includes/process.inc.php?section=<?php echo $section; ?>&amp;go=<?php echo $go; ?>&amp;dbTable=brewer&amp;action=delete&amp;username=<?php echo $row_brewer['brewerEmail'];?>','id',<?php echo $row_brewer['id']; ?>,'Are you sure you want to delete the participant <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?>?');"><img src="images/bin_closed.png"  border="0" alt="Delete <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?>" title="Delete <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?>"></a><?php } ?></span>
    </td> 
  <?php } ?> 
  </tr>
<?php } while ($row_brewer = mysql_fetch_assoc($brewer)); ?>
</tbody>
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
<form action="includes/process.inc.php?action=add&amp;dbTable=users&amp;section=<?php echo $section; ?>&amp;go=<?php echo $go; ?>" method="POST" name="form1" id="form1" onSubmit="return CheckRequiredFields()">
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
<form action="includes/process.inc.php?section=<?php echo "admin&amp;go=".$go."&amp;filter=".$filter; ?>&amp;action=<?php echo $action; ?>&amp;dbTable=brewer" method="POST" name="form1" id="form1" onSubmit="return CheckRequiredFields()">
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