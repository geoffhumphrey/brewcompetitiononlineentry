<table class="dataTable">
 <tr>
   <?php if (($action == "default") || ($action == "update") || ($action == "assign")) { ?>
   <td class="dataList" width="5%" nowrap="nowrap"><a href="index.php?section=admin">&laquo; Back to Admin</a></td>
   <td class="dataList" width="5%" nowrap="nowrap"><a href="index.php?section=admin&go=participants">&laquo; Back to Participants</a></td>
   <?php } ?>
   <?php if ((($action == "add") || ($action == "edit")) && ($section != "step3")) { ?><td class="dataList"><a href="index.php?section=admin&go=judging">&laquo;  Back to Judging Location List</a></td><?php } elseif (($section != "step3") && ($filter == "default")) { ?><td class="dataList"><span class="icon"><img src="images/award_star_add.png" align="absmiddle" /></span><a class="data" href="index.php?section=admin&go=judging&action=add">Add a Judging Location</a></td><?php } ?>
   <?php if (($action == "update") || ($action == "assign")) { ?><td class="dataList"><?php if ($bid == "default") echo "Choose ".$filter." to assign.";  else echo "Check below which ".$filter." will be assigned to the ".$row_judging['judgingLocName']. " location."; ?></td><?php }?>
 </tr>
</table>
<?php if (($action == "update") || ($action == "assign")) { ?>
<table class="dataTable">
<tr>
 <td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/user_edit.png" align="absmiddle" /></span><a class="data" href="index.php?section=admin&action=assign&go=judging&filter=judges">Assign Judges</a></td>
 <td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/user_edit.png" align="absmiddle" /></span><a class="data" href="index.php?section=admin&action=assign&go=judging&filter=stewards">Assign Stewards</a></td>
 <td class="dataList" width="5%" nowrap="nowrap"><?php if ($totalRows_stewarding2 > 1) { ?><span class="icon"><img src="images/user_edit.png" align="absmiddle" /></span><a class="data" href="index.php?section=admin&action=update&go=judging&filter=judges">Assign Judges to a Location</a><?php } else echo "&nbsp;"; ?></td>
 <td class="dataList"><?php if ($totalRows_stewarding2 > 1) { ?><span class="icon"><img src="images/user_edit.png" align="absmiddle" /></span><a class="data" href="index.php?section=admin&action=update&go=judging&filter=stewards">Assign Stewards to a Location</a><?php } else echo "&nbsp;"; ?></td>
 </tr>
</table> 
<?php } ?>