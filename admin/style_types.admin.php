<p class="lead"><?php echo $_SESSION['contestName']; if ($action == "add") echo ": Add a Style Type"; elseif ($action == "edit") echo ": Edit the ".$row_style_type['styleTypeName']." Style Type";  else echo " Style Types";  ?></p>

<div class="bcoem-admin-element hidden-print">
	
	<!-- Postion 1: View All Button -->
	<div class="btn-group" role="group" aria-label="all-styles">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles"><span class="fa fa-arrow-circle-left"></span> All Styles</a>
    </div><!-- ./button group -->
	
	<?php if (($action == "add") || ($action == "edit")) { ?>
	<!-- Postion 1: View All Button -->
	<div class="btn-group" role="group" aria-label="all-styles">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types"><span class="fa fa-arrow-circle-left"></span> All Style Types</a>
    </div><!-- ./button group -->
	<?php } ?>
	<?php if ($action == "default") { ?>
	<!-- Position 2: Add Dropdown Button Group -->
	<div class="btn-group" role="group">
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<span class="fa fa-plus-circle"></span> Add...  
		<span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles&amp;action=add">A Custom Style Style</a></li>
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types&amp;action=add">Add a Style Type</a><li>
		</ul>
	</div>
	<?php } ?>
</div>
<?php if ($action == "default") { ?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : false,
			"sDom": 'rt',
			"bStateSave" : false,
			"bLengthChange" : false,
			"aaSorting": [[0,'asc']],
			"bProcessing" : false,
			"aoColumns": [
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
        <th nowrap="nowrap">BOS for Style</th>
        <th nowrap="nowrap">BOS Method</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
	<?php do { ?>
	<tr>
    	<td><?php echo $row_style_type['styleTypeName']; if ($row_style_type['styleTypeOwn']  == "custom") echo " (Custom Style Type)"; ?></td>
        <td class="data"><?php if ($row_style_type['styleTypeBOS'] == "Y") echo "<span class=\"fa fa-lg fa-check text-success\"></span>"; else echo "<span class=\"fa fa-lg fa-times text-danger\"></span>"; ?></td>
        <td class="data"><?php echo bos_method($row_style_type['styleTypeBOSMethod']); ?></td>
        <td class="data">
  		<a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_style_type['id']; ?>" data-toggle="tooltip" data-placement="top" title="Edit <?php echo $row_style_type['styleTypeName']; ?>"><span class="fa fa-lg fa-pencil"></a> 
		<?php if ($row_style_type['styleTypeOwn'] != "bcoe") { ?>
		<a href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=<?php echo $style_types_db_table; ?>&amp;action=delete&amp;id=<?php echo $row_style_type['id']; ?>" data-toggle="tooltip" data-placement="top" title="Delete <?php echo $row_style_type['styleTypeName']; ?>" data-confirm="Are you sure you want to delete <?php echo $row_style_type['styleTypeName']; ?>? This cannot be undone."><span class="fa fa-lg fa-trash-o"></a>
  		<?php } else { ?>
 		<span class="fa fa-lg fa-trash-o text-muted"></span>
  		<?php } ?>
        </td>
    </tr>
    <?php } while ($row_style_type = mysqli_fetch_assoc($style_type)); ?>
</tbody>
</table>
<?php } // END if ($action == "default")?>
<?php if (($action == "add") || ($action == "edit")) { ?>
<form data-toggle="validator" role="form" class="form-horizontal" name="scores" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?action=<?php echo $action; ?>&amp;dbTable=<?php echo $style_types_db_table; if ($action == "edit") echo "&id=".$id; ?>">

<div class="form-group"><!-- Form Group REQUIRED Text Input -->
	<label for="styleTypeName" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Name</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<div class="input-group has-warning">
			<!-- Input Here -->
			<input class="form-control" id="styleTypeName" name="styleTypeName" type="text" value="<?php if ($action == "edit") echo $row_style_type['styleTypeName']; ?>" placeholder="" autofocus <?php if (($action == "edit") && ($id <= 3)) echo "disabled"; ?> required>
			<span class="input-group-addon" id="styleTypeName-addon2"><span class="fa fa-star"></span></span>
		</div>
		<div class="help-block with-errors"></div>
	</div>
</div><!-- ./Form Group -->
 <?php if (($action == "edit") && ($id <= 3)) { ?>
 <input type="hidden" name="styleTypeName" value="<?php echo $row_style_type['styleTypeName']; ?>">
 <?php } ?>
<div class="form-group"><!-- Form Group Radio INLINE -->
	<label for="brewStyleReqSpec" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">BOS for Style?</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<div class="input-group">
			<!-- Input Here -->
			<label class="radio-inline">
				<input type="radio" name="styleTypeBOS" value="Y" id="styleTypeBOS_0" <?php if (($action == "edit") && ($row_style_type['styleTypeBOS'] == "Y")) echo "checked"; if ($action == "add") echo "checked"; ?> /> Yes
			</label>
			<label class="radio-inline">
				<input type="radio" name="styleTypeBOS" value="N" id="styleTypeBOS_1" <?php if (($action == "edit") && ($row_style_type['styleTypeBOS'] == "N")) echo "checked"; ?> /> No
			</label>
		</div>
	</div>
</div><!-- ./Form Group -->
<div class="form-group"><!-- Form Group Radio STACKED -->
	<label for="styleTypeBOSMethod" class="col-lg-2 col-md-3 col-sm-4 col-xs-12  control-label">BOS Method</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<div class="input-group">
			<!-- Input Here -->
			<div class="radio">
				<label>
					<input type="radio" name="styleTypeBOSMethod" value="1" id="styleTypeBOSMethod_0" <?php if (($action == "edit") && ($row_style_type['styleTypeBOSMethod'] == "1")) echo "checked"; if ($action == "add") echo "checked"; ?> />1st place only     	
				</label>
			</div>
			<div class="radio">
				<label>
					<input type="radio" name="styleTypeBOSMethod" value="2" id="styleTypeBOSMethod_1" <?php if (($action == "edit") && ($row_style_type['styleTypeBOSMethod'] == "2")) echo "checked"; ?> />1st and 2nd places only
				</label>
			</div>
			<div class="radio">
				<label>
					<input type="radio" name="styleTypeBOSMethod" value="3" id="styleTypeBOSMethod_2" <?php if (($action == "edit") && ($row_style_type['styleTypeBOSMethod'] == "3")) echo "checked"; ?> />1st, 2nd, and 3rd places
				</label>
			</div>
		</div>
	</div>
</div><!-- ./Form Group -->
<div class="bcoem-admin-element hidden-print">
	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
			<input type="submit" name="Submit" id="updateStyle" class="btn btn-primary" value="<?php if ($action == "add") echo "Add"; else echo "Edit"; ?> Style Type" />
		</div>
	</div>
</div>
<input type="hidden" name="styleTypeOwn" value="<?php if ($action == "add") echo "custom"; else echo $row_style_type['styleTypeOwn']; ?>">
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin&go=style_types","default",$msg,$id); ?>">
<?php } ?>
</form>
<?php } // END if (($action == "add") || ($action == "edit")) ?>
