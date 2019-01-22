<?php
/**
 * Module:      special_best.admin.php
 * Description: Add, edit, and delete any custom "best of" categories for a comp.
 *              (e.g., for a Pro-Am, Best Entry Name, Stewards Choice, etc.)
 */

?>
<p class="lead"><?php echo $_SESSION['contestName']; if ($action == "add") echo ": Add a Custom Category"; elseif ($action == "edit") echo ": Edit a Custom Category"; else echo " Custom Categories"; ?></p>
<?php if ($action == "default") { ?>
    <p>Custom categories are useful if your competition features unique &ldquo;best of show&rdquo; categories, such as Pro-Am opportunites, Stewards&rsquo; Choice, Best Name, etc.</p>
<?php } ?>
<div class="bcoem-admin-element hidden-print">

    <!-- View Button Group Dropdown -->
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="fa fa-eye"></span> View...
        <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
			<?php if (($action == "add") || ($action == "edit")) { ?>
            <li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best">All Custom Categories</a></li>
			<?php } if ($totalRows_sbd > 0) { ?>
            <li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best_data">All Custom Category Entries</a><li>
			<?php } else { ?>
			<li class="small"><a href="#"><span class="text-muted disabled">All Custom Category Entries</span></a><li>
			<?php } ?>
        </ul>
    </div><!-- ./button group -->

	<?php if ($action == "default") { ?>
	<div class="btn-group" role="group" aria-label="add-custom-winning">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best&amp;action=add"><span class="fa fa-plus-circle"></span> Add a Custom Category</a>
    </div><!-- ./button group -->
	<?php } ?>
</div>
<?php if (($totalRows_sbi > 0) && ($action == "default")) { ?>
    <script type="text/javascript" language="javascript">
         $(document).ready(function() {
            $('#sortable').dataTable( {
                "bPaginate" : true,
                "sPaginationType" : "full_numbers",
                "bLengthChange" : true,
                "iDisplayLength" : <?php echo $limit; ?>,
                "sDom": 'rtp',
                "bStateSave" : false,
                "aaSorting": [[4,'asc']],
                "aoColumns": [
                    null,
                    { "asSorting": [  ] },
                    null,
                    null,
					null,
					{ "asSorting": [  ] },
                    ]
                } );
            } );
        </script>
    <table class="table table-responsive table-striped table-bordered" id="sortable">
     <thead>
     <tr>
      <th>Name</th>
      <th>Description</th>
      <th>Places</th>
      <th>Places Displayed?</th>
      <th>Rank</th>
      <th>Actions</th>
     </tr>
     </thead>
     <tbody>
     <?php do {
		$sbd_count = sbd_count($row_sbi['id']);
	 ?>
     <tr>
      <td width="20%"><?php echo $row_sbi['sbi_name']; ?></td>
      <td><?php echo $row_sbi['sbi_description']; ?></td>
      <td><?php echo $row_sbi['sbi_places']; ?></td>
      <td><?php if ($row_sbi['sbi_display_places'] == 1) echo "<span class=\"fa fa-lg fa-check text-success\"></span>"; else echo "<span class=\"fa fa-lg fa-times text-danger\"></span>" ?></td>
      <td><?php echo $row_sbi['sbi_rank']; ?></td>
      <td>
      <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_sbi['id']; ?>" data-toggle="tooltip" data-placement="top" title="Edit <?php echo $row_sbi['sbi_name']; ?>"><span class="fa fa-lg fa-pencil"></span></a>
	  <a class="hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=<?php echo $special_best_info_db_table; ?>&amp;action=delete&amp;id=<?php echo $row_sbi['id']; ?>" data-toggle="tooltip" data-placement="top" title="Delete <?php echo $row_sbi['sbi_name']; ?>."  data-confirm="Are you sure you want to delete <?php echo $row_sbi['sbi_name']; ?>? This cannot be undone. All associated data will be deleted as well."><span class="fa fa-lg fa-trash-o"></span></a>
	  <?php if ($sbd_count > 0) { ?>
	  <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best_data&amp;action=edit&amp;id=<?php echo $row_sbi['id']; ?>" data-toggle="tooltip" data-placement="top" title="Edit winners for <?php echo $row_sbi['sbi_name']; ?>"><span class="fa fa-lg fa-pencil-square-o"></span></a>
	  <?php } else { ?>
	  <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best_data&amp;action=add&amp;id=<?php echo $row_sbi['id']; ?>" data-toggle="tooltip" data-placement="top" title="Enter winners for <?php echo $row_sbi['sbi_name']; ?>"><span class="fa fa-lg fa-plus-circle-sign"></span></a>
	  <?php } ?>

      </td>
     </tr>
    <?php } while($row_sbi = mysqli_fetch_assoc($sbi)) ?>
     </tbody>
    </table>
<?php } if (($totalRows_sbi == 0) && ($action == "default")) echo "<p>No custom categories were found in the database.</p>"; ?>
<?php if (($action == "add") || ($action == "edit")) { ?>
<form data-toggle="validator" role="form" class="form-horizontal" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?action=<?php echo $action; ?>&amp;dbTable=<?php echo $special_best_info_db_table; ?><?php if ($action == "edit") echo "&amp;id=".$id; ?>" name="form1">

<div class="form-group"><!-- Form Group REQUIRED Text Input -->
	<label for="sbi_name" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Name</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<div class="input-group has-warning">
			<!-- Input Here -->
			<input class="form-control" id="sbi_name" name="sbi_name" type="text" value="<?php if ($action == "edit") echo $row_sbi['sbi_name']; ?>" placeholder="Pro-Am with XXX Brewery, People's Choice, etc." data-error="The the custom category's name is required." autofocus required>
			<span class="input-group-addon" id="sbi_name-addon2"><span class="fa fa-star"></span></span>
		</div>
		<span class="help-block with-errors"></span>
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT-REQUIRED Text Area -->
	<label for="sbi_description" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Description</label>
    <div class="col-lg-6 col-md-3 col-sm-8 col-xs-12">
		<!-- Input Here -->
		<textarea class="form-control" name="sbi_description" rows="6"><?php if ($action == "edit") echo $row_sbi['sbi_description']; ?></textarea>
	 </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group REQUIRED Text Input -->
	<label for="sbi_places" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Places</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<!-- Input Here -->
		<input class="form-control" id="sbi_places" name="sbi_places" type="text" value="<?php if ($action == "edit") echo $row_sbi['sbi_places']; ?>" placeholder="">
		<span id="helpBlock" class="help-block">The number of places available for the category.</span>
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group Radio INLINE -->
	<label for="sbi_display_places" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Display Places?</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<div class="input-group">
			<!-- Input Here -->
			<label class="radio-inline">
				<input type="radio"  name="sbi_display_places" value="1" id="sbi_display_places_1" <?php if (($row_sbi['sbi_display_places'] == "1") || ($action == "add")) echo "CHECKED"; ?>> Yes
			</label>
			<label class="radio-inline">
				<input type="radio" name="sbi_display_places" value="0" id="sbi_display_places_0" <?php if ($row_sbi['sbi_display_places'] == "0") echo "CHECKED"; ?> />No
			</label>
		</div>
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
	<label for="sbi_rank" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Rank</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
	<!-- Input Here -->
	<select class="selectpicker" name="sbi_rank" id="sbi_rank" data-size="10" data-width="auto">
		<?php for($i=1; $i<=20; $i++) { ?>
		<option value="<?php echo $i; ?>" <?php if (($action == "edit") && ($row_sbi['sbi_rank'] == $i)) echo " SELECTED"; ?>><?php echo $i; ?></option>
		<?php } ?>
	</select>
	<span id="helpBlock" class="help-block">Determines this category's rank in the display order. The lower the number, the higher priority.</span>
	</div>
</div><!-- ./Form Group -->

<div class="bcoem-admin-element hidden-print">
	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
			<input type="submit" name="updateSpecialBest" id="updateSpecialBest" class="btn btn-primary" value="<?php if ($action == "edit") echo "Edit"; else echo "Add"; ?> Custom Category">
		</div>
	</div>
</div>
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin&go=special_best","default",$msg,$id); ?>">
<?php } ?>
</form>
<?php } ?>