<?php include(DB.'dropoff.db.php'); ?>
<h2><?php if ($action == "add") echo "Add a Drop-Off Location"; elseif ($action == "edit") echo "Edit a Drop-Off Location"; else echo "Drop-Off Locations"; ?></h2>
<?php  if ($msg == "11"){ ?>
<div class="error">Add another drop-off location?</div>
<p><a href="<?php if ($section == "step6") echo "setup.php?section=step6"; else echo "index.php?section=admin&amp;go=dropoff"; ?>">Yes</a>&nbsp;&nbsp;&nbsp;<a href="<?php if ($section == "step6") echo "setup.php?section=step7"; else echo "index.php?section=admin"; ?>">No</a>
<?php } else { ?>
<?php if (($action == "update") || ($action == "assign")) { ?><p><?php if ($bid == "default") echo "Choose ".$filter." to assign.";  else echo "Check below which ".$filter." will be assigned to the ".$row_dropoff['dropLocationName']. " location."; ?></p><?php }?>
<div class="adminSubNavContainer">
   <?php if ((($action == "default") || ($action == "update") || ($action == "assign")) && ($section != "step6")) { ?>
   <span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>/images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>/index.php?section=admin">Back to Admin Dashboard</a>
   </span>
   <?php } ?>
   <?php if ((($action == "add") || ($action == "edit")) && ($section != "step6")) { ?>
  	<span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>/images/arrow_left.png" alt="Back"></span><a href="<?php echo $base_url; ?>/index.php?section=admin&amp;go=dropoff">Back to Drop-Off Location List</a>
    </span>
	<?php } elseif (($section != "step6") && ($filter == "default")) { ?>
   	<span class="adminSubNav">
    	<span class="icon"><img src="<?php echo $base_url; ?>/images/award_star_add.png"  /></span><a href="<?php echo $base_url; ?>/index.php?section=admin&amp;go=dropoff&amp;action=add">Add a Drop-Off Location</a>
   	</span>
	<?php } ?>
</div>
<?php if ((($action == "add") || ($action == "edit")) || ($section == "step6")) { ?>
<form method="post" action="<?php echo $base_url; ?>/includes/process.inc.php?section=<?php echo $section; ?>&amp;action=<?php if ($section == "step6") echo "add"; else echo $action; ?>&amp;dbTable=<?php echo $drop_off_db_table; ?>&amp;go=<?php if ($go == "default") echo "setup"; else echo $go; if ($action == "edit") echo "&amp;id=".$id; ?>" name="form1" onSubmit="return CheckRequiredFields()">
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
    <td class="data"><input name="dropLocation" type="text" class="mceNoEditor" value="<?php if ($action == "edit") echo $row_dropoff['dropLocation']; ?>" size="40" /></td>
    <td class="data"><span class="required">Required</span> <em>Provide the street address, city, and zip code</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Website:</td>
    <td class="data"><input name="dropLocationWebsite" size="50" value="<?php if ($action == "edit") echo $row_dropoff['dropLocationWebsite']; ?>"></td>
    <td class="data"><em>Be sure to include the full website URL including the http://</em></td>
  </tr>
  <tr>
    <td class="dataLabel">Notes:</td>
    <td class="data"><input name="dropLocationNotes" size="50" value="<?php if ($action == "edit") echo $row_dropoff['dropLocationNotes']; ?>" /></td>
    <td class="data"><em>Catch-all for items such as when entries will be picked up at the location, etc.</em></td>
  </tr>
  <tr>
    <td class="dataLabel">&nbsp;</td>
    <td class="data"><input type="submit" class="button" value="Submit"></td>
    <td class="data">&nbsp;</td>
  </tr>
</table>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>
<?php } 
 } 
if (($action == "default") && ($section != "step6")) { 
if ($totalRows_dropoff > 0) { ?>
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
				{ "asSorting": [  ] },
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				]
			} );
		} );
</script>
<table class="dataTable" id="sortable">
<thead>
 <tr>
  <th class="dataHeading bdr1B">Name</th>
  <th class="dataHeading bdr1B">Phone Number</th>
  <th class="dataHeading bdr1B">Address</th>
  <th class="dataHeading bdr1B">Website</th>
  <th class="dataHeading bdr1B">Notes</th>
  <th class="dataHeading bdr1B">Actions</th>
 </tr>
</thead>
<tbody>
 <?php do { ?>
 <tr>
  <td width="20%" class="dataList"><?php echo $row_dropoff['dropLocationName']; ?></td>
  <td width="10%" class="dataList"><?php echo $row_dropoff['dropLocationPhone']; ?></td>
  <td width="25%" class="dataList"><?php echo $row_dropoff['dropLocation']; ?></td>
  <td width="10%" class="dataList"><?php if ($row_dropoff['dropLocationWebsite'] !="") echo "<a href='".$row_dropoff['dropLocationWebsite']."' target='_blank'>View</a>"; ?></td>
  <td width="25%" class="dataList"><?php echo $row_dropoff['dropLocationNotes']; ?></td>
  <td class="dataList">
  <span class="icon"><a href="<?php echo $base_url; ?>/index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_dropoff['id']; ?>"><img src="<?php echo $base_url; ?>/images/pencil.png"  border="0" alt="Edit <?php echo $row_dropoff['dropLocationName']; ?>" title="Edit <?php echo $row_dropoff['dropLocationName']; ?>"></a></span><span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=<?php echo $drop_off_db_table; ?>&amp;action=delete','id',<?php echo $row_dropoff['id']; ?>,'Are you sure you want to delete the <?php echo $row_dropoff['dropLocationName']; ?> location?\nThis cannot be undone.');"><img src="<?php echo $base_url; ?>/images/bin_closed.png"  border="0" alt="Delete <?php echo $row_dropoff['dropLocationName']; ?>" title="Delete <?php echo $row_dropoff['dropLocationName']; ?>"></a></span></td>
 </tr>
  <?php } while($row_dropoff = mysql_fetch_assoc($dropoff)) ?>
</tbody>
</table>
<?php } else echo "No drop-off locations have been specified."; 
} ?>