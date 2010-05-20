<?php 
mysql_select_db($database, $brewing);
$query_judging_locs = "SELECT * FROM judging";
$judging_locs = mysql_query($query_judging_locs, $brewing) or die(mysql_error());
$row_judging_locs = mysql_fetch_assoc($judging_locs);
$totalRows_judging_locs = mysql_num_rows($judging_locs);
if ($totalRows_judging_locs > 0) { ?>
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
  <td width="25%" class="dataList"><?php echo $row_judging_locs['judgingLocName']; ?></td>
  <td width="15%" class="dataList"><?php echo dateconvert($row_judging_locs['judgingDate'], 2); ?></td>
  <td width="15%" class="dataList"><?php echo $row_judging_locs['judgingTime']; ?></td>
  <td width="30%" class="dataList"><?php echo $row_judging_locs['judgingLocation']; ?></td>
  <td class="dataList">
  <span class="icon"><a href="index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_judging_locs['id']; ?>"><img src="images/pencil.png"  border="0" alt="Edit <?php echo $row_judging_locs['judgingLocName']; ?>" title="Edit <?php echo $row_judging_locs['judgingLocName']; ?>"></a></span><span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=judging&amp;action=delete','id',<?php echo $row_judging_locs['id']; ?>,'Are you sure you want to delete the <?php echo $row_judging_locs['judgingLocName']; ?> location?\nThis cannot be undone and will affect all judges and stewards who indicated this location as a preference.');"><img src="images/bin_closed.png"  border="0" alt="Delete <?php echo $row_judging_locs['judgingLocName']; ?>" title="Delete <?php echo $row_judging_locs['judgingLocName']; ?>"></a></span></td>
 </tr>
  <?php if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
  <?php } while($row_judging_locs = mysql_fetch_assoc($judging_locs)) ?>
 <tr>
 	<td colspan="5" class="bdr1T">&nbsp;</td>
 </tr>
</table>
<?php } else echo "<p>No judging dates/locations have been specified.</p>"; ?>