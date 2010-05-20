<h2><?php if ($action == "add") echo "Add a Drop-Off Location"; elseif ($action == "edit") echo "Edit a Drop-Off Location"; else echo "Drop-Off Locations"; ?></h2>
<?php  if ($msg == "11"){ ?>
<div class="error">Add another drop-off location?</div>
<p><a href="<?php if ($section == "step4") echo "setup.php?section=step4"; else echo "index.php?section=admin&amp;go=dropoff"; ?>">Yes</a>&nbsp;&nbsp;&nbsp;<a href="<?php if ($section == "step4") echo "setup.php?section=step5"; else echo "index.php?section=admin"; ?>">No</a>
<?php } else { ?>
<?php if (($action == "update") || ($action == "assign")) { ?><p><?php if ($bid == "default") echo "Choose ".$filter." to assign.";  else echo "Check below which ".$filter." will be assigned to the ".$row_dropoff['dropLocationName']. " location."; ?></p><?php }?>
<table class="dataTable">
 <tr>
   <?php if ((($action == "default") || ($action == "update") || ($action == "assign")) && ($section != "step4")) { ?><td class="dataList" width="5%" nowrap="nowrap"><span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a class="data" href="index.php?section=admin">Back to Admin</a></td><?php } ?>
   <?php if ((($action == "add") || ($action == "edit")) && ($section != "step4")) { ?>
   <td class="dataList"><span class="icon"><img src="images/arrow_left.png" alt="Back"></span><a class="data" href="index.php?section=admin&amp;go=dropoff">Back to Drop-Off Location List</a></td><?php } elseif (($section != "step4") && ($filter == "default")) { ?>
   <td class="dataList"><span class="icon"><img src="images/award_star_add.png"  /></span><a class="data" href="index.php?section=admin&amp;go=dropoff&amp;action=add">Add a Drop-Off Location</a></td><?php } ?>
 </tr>
</table>
<?php if ((($action == "add") || ($action == "edit")) || ($section == "step4")) { ?>
<form method="post" action="includes/process.inc.php?section=<?php echo $section; ?>&amp;action=<?php if ($section == "step4") echo "add"; else echo $action; ?>&amp;dbTable=drop_off&amp;go=<?php if ($go == "default") echo "setup"; else echo $go; if ($action == "edit") echo "&amp;id=".$id; ?>" name="form1" onSubmit="return CheckRequiredFields()">
<table>
  <tr>
    <td class="dataLabel">Name:</td>
    <td class="data"><input name="dropLocationName" size="30" value="<?php if ($action == "edit") echo $row_dropoff['dropLocationName']; ?>"></td>
    <td class="data"><span class="required">Required</span> <em>Provide the name of the drop-off location</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Phone Number:</td>
    <td class="data"><input name="dropLocationPhone" size="30" value="<?php if ($action == "edit") echo $row_dropoff['dropLocationPhone']; ?>"></td>
    <td class="data"><span class="required">Required</span> <em>Provide drop-off location phone number.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Address:</td>
    <td class="data"><textarea name="dropLocation" cols="40" rows="7" class="mceNoEditor"><?php if ($action == "edit") echo $row_dropoff['dropLocation']; ?></textarea></td>
    <td class="data"><span class="required">Required</span> <em>Provide the street address, city, and zip code</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Website:</td>
    <td class="data"><input name="dropLocationWebsite" size="50" value="<?php if ($action == "edit") echo $row_dropoff['dropLocationWebsite']; ?>"></td>
    <td class="data"><em>Be sure to include the full website URL including the http://</em></td>
  </tr>
  <tr>
    <td class="dataLabel">&nbsp;</td>
    <td class="data"><input type="submit" class="button" value="Submit"></td>
    <td class="data">&nbsp;</td>
  </tr>
</table>
</form>
<?php } 
 } 
if (($action == "default") && ($section != "step4")) { 
if ($totalRows_dropoff > 0) { ?>
<table class="dataTable">
 <tr>
  <td class="dataHeading bdr1B">Name</td>
  <td class="dataHeading bdr1B">Phone Number</td>
  <td class="dataHeading bdr1B">Address</td>
  <td class="dataHeading bdr1B">Website</td>
  <td class="dataHeading bdr1B">Actions</td>
 </tr>
 <?php do { ?>
 <tr <?php echo " style=\"background-color:$color\"";?>>
  <td width="15%" class="dataList"><?php echo $row_dropoff['dropLocationName']; ?></td>
  <td width="15%" class="dataList"><?php echo $row_dropoff['dropLocationPhone']; ?></td>
  <td width="30%" class="dataList"><?php echo $row_dropoff['dropLocation']; ?></td>
  <td width="30%" class="dataList"><?php if ($row_dropoff['dropLocationWebsite'] !="") echo "<a href='".$row_dropoff['dropLocationWebsite']."' target='_blank'>".$row_dropoff['dropLocationWebsite']."</a>"; ?></td>
  <td class="dataList">
  <span class="icon"><a href="index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_dropoff['id']; ?>"><img src="images/pencil.png"  border="0" alt="Edit <?php echo $row_dropoff['dropLocationName']; ?>" title="Edit <?php echo $row_dropoff['dropLocationName']; ?>"></a></span><span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=drop_off&amp;action=delete','id',<?php echo $row_dropoff['id']; ?>,'Are you sure you want to delete the <?php echo $row_dropoff['dropLocationName']; ?> location?\nThis cannot be undone.');"><img src="images/bin_closed.png"  border="0" alt="Delete <?php echo $row_dropoff['dropLocationName']; ?>" title="Delete <?php echo $row_dropoff['dropLocationName']; ?>"></a></span></td>
 </tr>
  <?php if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
  <?php } while($row_dropoff = mysql_fetch_assoc($dropoff)) ?>
 <tr>
 	<td colspan="5" class="bdr1T">&nbsp;</td>
 </tr>
</table>
<?php } else echo "No drop-off locations have been specified."; 
} ?>