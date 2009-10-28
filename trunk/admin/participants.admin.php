<h2><?php if ($filter == "judges") echo "Available Judges"; elseif ($filter == "stewards") echo "Available Stewards"; else echo "Participants"; if ($dbTable != "default") echo ": ".$dbTable; ?></h2>
<?php if ($action != "print") { ?>
<table class="dataTable">
<tr>
  <td class="dataList" width="5%" nowrap="nowrap"><a href="index.php?section=admin">&laquo; Back to Admin</a></td>
  <?php if ($dbTable != "default") { ?>
  <td class="dataList" width="5%" nowrap="nowrap"><a href="index.php?section=admin&go=archive">&laquo; Back to Archives</a></td>
  <?php } ?>
  <?php if ($dbTable == "default") { ?>
  <?php if ($filter != "default") echo "<td class=\"dataList\" width=\"5%\" nowrap=\"nowrap\"><a href=\"index.php?section=admin&go=participants\">View All Participants</a></td>"; ?>
  <?php if (($filter == "default") || ($filter == "judges")) { ?><td class="dataList" width="5%" nowrap="nowrap"><a href="index.php?section=admin&go=participants&filter=stewards">View Stewards</a></td><?php } if (($filter == "default") || ($filter == "stewards")) { ?><td class="dataList" width="5%" nowrap="nowrap"><a href="index.php?section=admin&go=participants&filter=judges">View Judges</a></td>
  <?php }  
  }
  ?>
 <td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/printer.png" align="absmiddle" /></span><a class="data" href="#" onClick="window.open('print.php?section=<?php echo $section; ?>&go=<?php echo $go; ?>&action=print&filter=<?php echo $filter; ?>','','height=600,width=800,toolbar=no,resizable=yes,scrollbars=yes'); return false;">Print List of <?php if ($filter == "judges") echo "Available Judges"; elseif ($filter == "stewards") echo "Available Stewards"; else echo "Participants"; ?></a></td>
 <td class="dataList">Total: <?php echo $totalRows_brewer; ?> </td>
</tr>
</table>
<?php } if ($totalRows_brewer > 0) { ?>
<table class="dataTable">
  <tr>
    <td class="dataHeading bdr1B">Last</td>
    <td class="dataHeading bdr1B">First</td>
    <td class="dataHeading bdr1B">Info</td>
    <td class="dataHeading bdr1B">Club</td>
  <?php if ($filter == "default") { ?>
    <td class="dataHeading bdr1B">S?</td>
    <td class="dataHeading bdr1B">J?</td>
  <?php } if ($filter != "default") { ?>
    <td class="dataHeading bdr1B">ID</td>
    <td class="dataHeading bdr1B">Rank</td>
    <td class="dataHeading bdr1B">Yes</td>
    <td class="dataHeading bdr1B">No</td>
  <?php } ?>
  <?php if ($action != "print") { ?>
    <td class="dataHeading bdr1B">Actions</td>
  <?php } ?>
  </tr>
<?php do { ?>
  <tr <?php echo " style=\"background-color:$color\"";?>>
    <td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="10%"><?php echo $row_brewer['brewerLastName']; ?></td>
    <td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="10%"><?php echo $row_brewer['brewerFirstName']; ?></td>
    <td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="15%"><?php echo $row_brewer['brewerAddress']; ?><br><?php echo $row_brewer['brewerCity'].", ".$row_brewer['brewerState']." ".$row_brewer['brewerZip']; ?><br /><a href="mailto:<?php echo $row_brewer['brewerEmail']; ?>?Subject=<?php if ($filter == "judges") echo "Judging at ".$row_contest_info['contestName']; elseif ($filter == "stewards") echo "Stewarding at ".$row_contest_info['contestName']; else echo $row_contest_info['contestName'];  ?>"><?php echo $row_brewer['brewerEmail']; ?></a><br /><?php if ($row_brewer['brewerPhone1'] != "") echo $row_brewer['brewerPhone1']." (H)<br>";  if ($row_brewer['brewerPhone2'] != "") echo $row_brewer['brewerPhone2']." (W)"; ?></td>
    <td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="15%"><?php echo $row_brewer['brewerClubs']; ?></td>
  <?php if ($filter == "default") { ?>
    <td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="5%"><?php echo $row_brewer['brewerSteward'] ?></td>
    <td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="5%"><?php echo $row_brewer['brewerJudge']; ?></td>
  <?php } if ($filter != "default") { ?>
    <td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="5%"><?php echo $row_brewer['brewerJudgeID']; ?></td>
    <td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="5%"><?php echo $row_brewer['brewerJudgeRank']; ?></td>
    <td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="10%"><?php echo $row_brewer['brewerJudgeLikes']; ?></td>
    <td class="dataList<?php if ($action == "print") echo " bdr1B"; ?>" width="10%"><?php echo $row_brewer['brewerJudgeDislikes']; ?></td>
  <?php } ?>
  <?php if ($action != "print") { ?>
    <td class="dataList" nowrap="nowrap"><span class="icon"><a href="index.php?section=brewer&go=<?php echo $go; ?>&filter=<?php echo $row_brewer['id']; ?>&action=edit&id=<?php echo $row_brewer['id']; ?>"><img src="images/pencil.png" align="absmiddle" border="0" alt="Edit <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?>" title="Edit <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?>"></a></span><span class="icon"><a href="index.php?section=admin&go=make_admin&username=<?php echo $row_brewer['brewerEmail'];?>"><img src="images/lock_edit.png" align="absmiddle" border="0" alt="Change <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?>'s User Level" title="Change <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?>'s User Level"></a></span><span class="icon"><?php if ($row_brewer['brewerEmail'] == $_SESSION['loginUsername']) echo "&nbsp;"; else { ?><a href="javascript:DelWithCon('includes/process.inc.php?section=<?php echo $section; ?>&go=<?php echo $go; ?>&dbTable=brewer&action=delete&username=<?php echo $row_brewer['brewerEmail'];?>','id',<?php echo $row_brewer['id']; ?>,'Are you sure you want to delete the participant <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?>?');"><img src="images/bin_closed.png" align="absmiddle" border="0" alt="Delete <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?>" title="Delete <?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?>"></a><?php } ?></span>
    </td> 
  <?php } ?> 
  </tr>
  <?php if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
<?php } while ($row_brewer = mysql_fetch_assoc($brewer)); ?>
<?php if ($action != "print") { ?>
  <tr>
  	<td class="bdr1T" colspan="12">&nbsp;</td>
  </tr>
<?php } ?>
</table>
<?php } if ($totalRows_brewer == 0) { ?>
<div class="error">
<?php 
if ($filter == "default") echo "There are no participants yet."; 
if ($filter == "judges") echo "There are no judges available yet."; 
if ($filter == "stewards") echo "There are no stewards available yet."; 
}
?>
</div>