<?php if (($action == "update") || ($action == "assign")) { ?><p><?php if ($bid == "default") echo "Choose ".$filter." to assign.";  else echo "Check below which ".$filter." will be assigned to the ".$row_judging['judgingLocName']. " location."; ?></p><?php }?>
<table>
 <tr>
   <?php if (($action == "default") || ($action == "update") || ($action == "assign")) { ?>
   <td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a class="data" href="index.php?section=admin">Back to Admin</a></td>
   <td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a class="data" href="index.php?section=admin&amp;go=participants">Back to Participants</a></td>
   <?php } ?>
   <?php if ((($action == "add") || ($action == "edit")) && ($section != "step3")) { ?><td class="dataList"><span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a class="data" href="index.php?section=admin&amp;go=judging">Back to Judging Location List</a></td><?php } elseif (($section != "step3") && ($filter == "default")) { ?><td class="dataList"><span class="icon"><img src="images/award_star_add.png"  /></span><a class="data" href="index.php?section=admin&amp;go=judging&amp;action=add">Add a Judging Location</a></td><?php } ?>
	<td class="dataList"></td>
 </tr>
</table>
<?php if (($action == "update") || ($action == "assign")) { ?>
<table class="dataTable">
<tr>
 <td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/user_edit.png"  /></span><a class="data" href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges">Assign Judges</a></td>
 <td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/user_edit.png"  /></span><a class="data" href="index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards">Assign Stewards</a></td>
 <td class="dataList" width="5%" nowrap="nowrap"><?php if ($totalRows_stewarding2 > 1) { ?><span class="icon"><img src="images/user_edit.png"  /></span><a class="data" href="index.php?section=admin&amp;action=update&amp;go=judging&amp;filter=judges">Assign Judges to a Location</a><?php } else echo "&nbsp;"; ?></td>
 <td class="dataList"><?php if ($totalRows_stewarding2 > 1) { ?><span class="icon"><img src="images/user_edit.png"  /></span><a class="data" href="index.php?section=admin&amp;action=update&amp;go=judging&amp;filter=stewards">Assign Stewards to a Location</a><?php } else echo "&nbsp;"; ?></td>
 </tr>
</table> 
<?php } ?>