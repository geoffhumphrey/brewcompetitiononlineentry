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
	<?php if ($totalRows_stewarding2 > 1) { ?>
    <span class="adminSubNav">
    	<span class="icon"><img src="images/user_edit.png"  /></span><a href="index.php?section=admin&amp;action=update&amp;go=judging&amp;filter=judges">Assign Judges to a Location</a>
    </span>
    <?php } ?>
 	<?php if ($totalRows_stewarding2 > 1) { ?>
    <span class="adminSubNav">
    	<span class="icon"><img src="images/user_edit.png"  /></span><a href="index.php?section=admin&amp;action=update&amp;go=judging&amp;filter=stewards">Assign Stewards to a Location</a>
	</span>
    <?php }  ?>
</div>
<?php } ?>