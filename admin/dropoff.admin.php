<?php include (DB.'dropoff.db.php');
$dropoff_loc_url_yes = "";
$dropoff_loc_url_no = "";
if (($section != "step6") && ($_SESSION['brewerCountry'] != "United States")) $us_phone = TRUE; else $us_phone = FALSE;
if ($section != "step6") {
?>
<p class="lead"><?php echo $_SESSION['contestName']; if ($action == "add") echo ": Add a Drop-Off Location"; elseif ($action == "edit") echo ": Edit a Drop-Off Location"; else echo " Drop-Off Locations"; ?></p>
<?php  } if ($msg == "11") {
	if ($section == "step6") $dropoff_loc_url_yes .= "setup.php?section=step6";
	else $dropoff_loc_url_yes .= "index.php?section=admin&amp;go=judging";
	if ($section == "step6") $dropoff_loc_url_no .= "setup.php?section=step7";
	else $dropoff_loc_url_no .= "index.php?section=admin";
?>
<p class="lead">Add another drop-off location?</p>
<div class="btn-group" role="group" aria-label="judge-loc-yes">
    <a class="btn btn-success" href="<?php echo $base_url.$dropoff_loc_url_yes; ?>"><span class="fa fa-check"></span> Yes</a>
</div><!-- ./button group -->
<div class="btn-group" role="group" aria-label="judge-loc-no">
    <a class="btn btn-danger" href="<?php echo $base_url.$dropoff_loc_url_no; ?>"><span class="fa fa-times"></span> No</a>
</div><!-- ./button group -->
<?php } else { ?>
<?php if (($action == "update") || ($action == "assign")) { ?>
<p><?php if ($bid == "default") echo "Choose ".$filter." to assign.";  else echo "Check below which ".$filter." will be assigned to the ".$row_dropoff['dropLocationName']. " location."; ?></p>
<?php }?>
<?php if (($totalRows_dropoff == 0) && ($section == "step6")) { ?>
<a class="btn btn-primary" type="button" role="button" data-toggle="popover" data-trigger="hover" data-placement="auto right" data-container="body"  data-content="Skip this step if your competition does not have any drop-off locations." href="<?php echo $base_url; ?>includes/process.inc.php?section=setup&amp;action=add&amp;dbTable=<?php echo $drop_off_db_table; ?>&amp;go=skip">Skip This Step&nbsp;&nbsp;<span class="fa fa-lg fa-arrow-circle-right"></span></a>
<?php } ?>
<div class="bcoem-admin-element hidden-print">
<?php if ((($action == "add") || ($action == "edit")) && ($section != "step6")) { ?>
<div class="btn-group" role="group" aria-label="...">
	<a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=dropoff"><span class="fa fa-arrow-circle-left"></span> All Drop-Off Locations</a>
</div><!-- ./button group -->
<?php } elseif (($section != "step6") && ($filter == "default")) { ?>
<div class="btn-group" role="group" aria-label="...">
	<a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=dropoff&amp;action=add"><span class="fa fa-plus-circle"></span> Add a Drop-Off Location</a>
</div><!-- ./button group -->
<?php } ?>
</div>

<?php if ((($action == "add") || ($action == "edit")) || ($section == "step6")) { ?>
<form data-toggle="validator" role="form" class="form-horizontal" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?section=<?php if ($section == "step6") echo "setup"; else echo $section; ?>&amp;action=<?php if ($section == "step6") echo "add"; else echo $action; ?>&amp;dbTable=<?php echo $drop_off_db_table; ?>&amp;go=<?php if ($go == "default") echo "setup"; else echo $go; if ($action == "edit") echo "&amp;id=".$id; ?>" name="form1">

<div class="bcoem-admin-element hidden-print">
<div class="form-group"><!-- Form Group REQUIRED Text Input -->
	<label for="dropLocationName" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Name</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<div class="input-group has-warning">
			<!-- Input Here -->
			<input class="form-control" id="dropLocationName" name="dropLocationName" type="text" value="<?php if ($action == "edit") echo $row_dropoff['dropLocationName']; ?>" data-error="The dropoff location's name is required" placeholder="" autofocus required>
			<span class="input-group-addon" id="dropLocationName-addon2"><span class="fa fa-star"></span></span>
		</div>
        <span class="help-block with-errors"></span>
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group REQUIRED Text Input -->
	<label for="dropLocationPhone" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Phone</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<div class="input-group has-warning">
			<!-- Input Here -->
			<input class="form-control" id="dropLocationPhone" name="dropLocationPhone" type="tel" value="<?php if ($action == "edit") echo $row_dropoff['dropLocationPhone']; ?>" data-error="The dropoff location's phone number is required" placeholder="" required>
			<span class="input-group-addon" id="dropLocationPhone-addon2"><span class="fa fa-star"></span></span>
		</div>
        <span class="help-block with-errors"></span>
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group REQUIRED Text Input -->
	<label for="dropLocation" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Address</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<div class="input-group has-warning">
			<!-- Input Here -->
			<input class="form-control" id="dropLocation" name="dropLocation" type="text" value="<?php if ($action == "edit") echo $row_dropoff['dropLocation']; ?>" data-error="The dropoff location's address is required" placeholder="" required>
			<span class="input-group-addon" id="dropLocation-addon2"><span class="fa fa-star"></span></span>
		</div>
		<span class="help-block with-errors">Provide the street address, city, and zip code.</span>
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
	<label for="dropLocationWebsite" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Website</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<input class="form-control" id="dropLocationWebsite" name="dropLocationWebsite" type="url" value="<?php if ($action == "edit") echo $row_dropoff['dropLocationWebsite']; ?>" data-error="Make sure the web address is valid and includes http:// or https://" placeholder="http://www.yoursite.com" pattern="^(http(s?)\:\/\/)*[0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*(:(0-9)*)*(\/?)([a-zA-Z0-9\-\.\?\,\'\/\\\+&amp;%\$#_]*)?$">
		<span class="help-block with-errors">Be sure to include the full website URL including the http:// or https://</span>
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
	<label for="dropLocationNotes" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Notes</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<input class="form-control" id="dropLocationWNotes" name="dropLocationNotes" type="text" value="<?php if ($action == "edit") echo $row_dropoff['dropLocationNotes']; ?>" placeholder="" >
		<span id="helpBlock" class="help-block">Catch-all for items such as when entries will be picked up at the location, etc.</span>
	</div>
</div><!-- ./Form Group -->

</div>
<div class="bcoem-admin-element hidden-print">
	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
			<input type="submit" name="Submit" id="updateConatact" class="btn btn-primary" value="<?php if (($action == "add") || ($section == "step6")) echo "Add"; else echo "Edit"; ?> Drop-Off Location" />
		</div>
	</div>
</div>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin","default",$msg,$id); ?>">
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
			"iDisplayLength" : <?php echo round($_SESSION['prefsRecordPaging']); ?>,
			"sDom": 'rtp',
			"bStateSave" : false,
			"aaSorting": [[0,'asc']],
			"bProcessing" : true,
			"aoColumns": [
				null,
				null,
				null,
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				]
			} );
		} );
</script>
<table class="table table-responsive table-striped table-bordered" id="sortable">
<thead>
 <tr>
  <th>Name</th>
  <th>Phone</th>
  <th>Address</th>
  <th>Notes</th>
  <th>Actions</th>
 </tr>
</thead>
<tbody>
 <?php do {
 if ($us_phone) $phone = format_phone_us($row_dropoff['dropLocationPhone']);
 else $phone = $row_dropoff['dropLocationPhone'];
 ?>
 <tr>
	<td><?php echo $row_dropoff['dropLocationName']; ?></td>
	<td nowrap><?php echo $phone; ?></td>
	<td><?php echo $row_dropoff['dropLocation']; ?></td>
	<td><?php echo $row_dropoff['dropLocationNotes']; ?></td>
	<td>
		<a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_dropoff['id']; ?>" data-toggle="tooltip" data-placement="top" title="Edit <?php echo $row_dropoff['dropLocationName']; ?>"><span class="fa fa-lg fa-pencil"></span></a>
		<a class="hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=<?php echo $drop_off_db_table; ?>&amp;action=delete&amp;id=<?php echo $row_dropoff['id']; ?>" data-toggle="tooltip" data-placement="top" title="Delete <?php echo $row_dropoff['dropLocationName']; ?>" data-confirm="Are you sure you want to delete the <?php echo $row_dropoff['dropLocationName']; ?> location? This cannot be undone."><span class="fa fa-lg fa-trash-o"></span></a>
		<?php if ($row_dropoff['dropLocationWebsite'] !="") echo "<a class=\"hide-loader\" href=\"".$row_dropoff['dropLocationWebsite']."\" target=\"_blank\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Visit the ".$row_dropoff['dropLocationName']." website\"><span class=\"fa fa-lg fa-link\"></span></a> "; ?>
	</tr>
  <?php } while($row_dropoff = mysqli_fetch_assoc($dropoff)) ?>
</tbody>
</table>
<?php } else echo "<p>".$alert_text_005."</p>";
} ?>