<?php
/**
 * Module:      special_best_data.admin.php
 * Description: Add, edit, and delete any custom "best of" categories for a comp.
 *              (e.g., for a Pro-Am, Best Entry Name, Stewards Choice, etc.)
 */

//if (($action == "add") || ($action == "edit")) $query_sbd = "SELECT * FROM $special_best_data_db_table WHERE id='$id'";


?>
<p class="lead"><?php echo $_SESSION['contestName']; if ($action == "add") echo ": Add Entries to the ".$row_sbi['sbi_name']." Custom Style"; elseif ($action == "edit") echo ": Edit Entries in the ".$row_sbi['sbi_name']." Custom Style"; else echo " Custom Style Entries"; ?></p>

<div class="bcoem-admin-element hidden-print">
<!-- View Button Group Dropdown -->
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="fa fa-eye"></span> View...
        <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
			<li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best">All Custom Categories</a></li>
			<?php if ($totalRows_sbd > 0) { ?>
            <li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best_data">All Custom Style Entries</a><li>
			<?php } ?>
        </ul>
    </div><!-- ./button group -->

	<div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="fa fa-plus-circle"></span> Add/Edit Entries For...
        <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
			<?php echo score_custom_winning_choose($special_best_info_db_table,$special_best_data_db_table); ?>
        </ul>
    </div><!-- ./button group -->



</div>

<?php if (($action == "default") || ($action == "list")) { ?>
<p>Custom categories are useful if your competition features unique &ldquo;best of show&rdquo; categories, such as Pro-Am opportunites, Stewards&rsquo; Choice, Best Name, etc.</p>
	<?php if ($totalRows_sbd > 0) { ?>
    <script type="text/javascript" language="javascript">
         $(document).ready(function() {
            $('#sortable').dataTable( {
                "bPaginate" : true,
                "sPaginationType" : "full_numbers",
                "bLengthChange" : true,
                "iDisplayLength" : <?php echo $limit; ?>,
                "sDom": 'rtp',
                "bStateSave" : false,
                "aaSorting": [[0,'asc'],[1,'asc']],
                "aoColumns": [
                    null,
                    { "asSorting": [  ] },
                    { "asSorting": [  ] },
                    { "asSorting": [  ] },
					null,
                    null,
					{ "asSorting": [  ] },
                    ]
                } );
            } );
        </script>
    <table class="table table-responsive table-bordered table-striped" id="sortable">
     <thead>
     <tr>
      <th>Custom Style</th>
      <th>Place</th>
      <th>Entry</th>
      <th>Judging</th>
      <th>Entry Name</th>
      <th>Brewer</th>
      <th>Actions</th>
     </tr>
     </thead>
     <tbody>
    <?php do {
	$info = explode("^", entry_info($row_sbd['eid']));
	$brewer_info = explode("^", brewer_info($row_sbd['bid']));
	$special_best_info = explode("^",special_best_info($row_sbd['sid']));

	?>
     <tr>
      <td><?php echo $special_best_info[1]; ?></td>
      <td><?php echo $row_sbd['sbd_place']; ?></td>
      <td><?php echo sprintf("%04s",$row_sbd['eid']); ?></td>
      <td>
	  <?php
	  if ((NHC) || ($_SESSION['prefsEntryForm'] == "N")) echo sprintf("%06s",$info[6]);
	  else echo readable_judging_number($info[3],$info[6]);
	  ?></td>
      <td><?php echo $info[0]; ?></td>
      <td><?php echo $brewer_info[0]." ".$brewer_info[1]; ?></td>
      <td nowrap="nowrap">

	  <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_sbd['sid']; ?>" data-toggle="tooltip" data-placement="top" title="Edit the <?php echo $special_best_info[1]; ?> Custom Style entries"><span class="fa fa-lg fa-pencil"></span></a>
	  <a class="hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=<?php echo $special_best_data_db_table; ?>&amp;action=delete&amp;id=<?php echo $row_sbd['id']; ?>" data-toggle="tooltip" data-placement="top" title="Delete &ldquo;<?php echo $info[0]; ?>&rdquo; as a winner for the <?php echo $special_best_info[1]; ?> Custom Style"  data-confirm="Are you sure you want to delete <?php echo $info[0]; ?>? This cannot be undone."><span class="fa fa-lg fa-trash-o"></span></a>
      </td>
     </tr>
    <?php
	} while($row_sbd = mysqli_fetch_assoc($sbd));  ?>
     </tbody>
    </table>
    <?php }
	else echo "<p>There are no entries found in any custom category.</p>";
}
if (($action == "add") || ($action == "edit")) { ?>
<form class="form-horizontal" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?action=<?php echo $action; ?>&amp;dbTable=<?php echo $special_best_data_db_table; ?>&id=<?php echo $id; ?>" name="form1">

<?php
if ($action == "add") {
	for ($i=1; $i <= $row_sbi['sbi_places']; $i++) {
?>
	<input type="hidden" name="id[]" value="<?php echo $i; ?>" />
    <input type="hidden" name="sid<?php echo $i; ?>" value="<?php echo $id; ?>">
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="sbd_judging_no<?php echo $i; ?>" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Winning Entry <?php echo $i; ?>'s Judging Number</label>
		<div class="col-lg-3 col-md-6 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<!-- Input Here -->
				<input class="form-control" id="sbd_judging_no<?php echo $i; ?>" name="sbd_judging_no<?php echo $i; ?>" type="text" size="10" maxlength="255" value="" placeholder="" <?php if ($i == 1) echo "autofocus"; ?>>
				<span class="input-group-addon" id="sbd_judging_no<?php echo $i; ?>-2"><span class="fa fa-star"></span></span>
			</div>
		</div>
	</div><!-- ./Form Group -->


	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="sbd_place<?php echo $i; ?>" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Place</label>
		<div class="col-lg-3 col-md-6 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<!-- Input Here -->
				<input class="form-control" id="sbd_place<?php echo $i; ?>" name="sbd_place<?php echo $i; ?>" type="text" value="">
				<span class="input-group-addon" id="sbd_place<?php echo $i; ?>-2"><span class="fa fa-star"></span></span>
			</div>
		</div>
	</div><!-- ./Form Group -->

  <?php }
	} // end if ($action == "add")
	if ($action == "edit") {
		do {
		$info = explode("^", entry_info($row_sbd['eid']));
		?>
  <input type="hidden" name="id[]" value="<?php echo $row_sbd['id']; ?>" />
  <input type="hidden" name="bid<?php echo $row_sbd['id']; ?>" value="<?php echo $row_sbd['bid']; ?>" />
  <input type="hidden" name="eid<?php echo $row_sbd['id']; ?>" value="<?php echo $row_sbd['eid']; ?>" />
  <input type="hidden" name="sid<?php echo $row_sbd['id']; ?>" value="<?php echo $id; ?>">
  <input type="hidden" name="entry_exists<?php echo $row_sbd['id']; ?>" value="Y" />


  <div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="sbd_judging_no<?php echo $row_sbd['id']; ?>" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Winning Entry's Judging Number</label>
		<div class="col-lg-3 col-md-6 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<!-- Input Here -->
				<input class="form-control" id="sbd_judging_no<?php echo $row_sbd['id']; ?>" name="sbd_judging_no<?php echo $row_sbd['id']; ?>" type="text" size="10" maxlength="255" value="<?php if ($info[6] > 0) {
	  if ((NHC) || ($_SESSION['prefsEntryForm'] == "N")) echo sprintf("%06s",$info[6]);
	  else echo readable_judging_number($info[3],$info[6]);
	}
	  ?>" placeholder="">
				<span class="input-group-addon" id="sbd_judging_no<?php echo $row_sbd['id']; ?>-2"><span class="fa fa-star"></span></span>
			</div>
		</div>
	</div><!-- ./Form Group -->


	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="sbd_place<?php  echo $row_sbd['id']; ?>" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Place</label>
		<div class="col-lg-3 col-md-6 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<!-- Input Here -->
				<input class="form-control" id="sbd_place<?php  echo $row_sbd['id']; ?>" name="sbd_place<?php  echo $row_sbd['id']; ?>" type="text" value="<?php echo $row_sbd['sbd_place']; ?>">
				<span class="input-group-addon" id="sbd_place<?php  echo $row_sbd['id']; ?>-2"><span class="fa fa-star"></span></span>
			</div>
		</div>
	</div><!-- ./Form Group -->

	<div class="form-group"><!-- Form Group NOT REQUIRED  -->
		<label for="<?php echo $info[0]; ?>" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Entry Name</label>
		<div class="col-lg-3 col-md-6 col-sm-8 col-xs-12">
			<p class="form-control-static"><?php echo $info[0]; ?></p>
		</div>
	</div><!-- ./Form Group -->
	<?php $info2 = explode("^", brewer_info($row_sbd['bid'])); ?>
	<div class="form-group"><!-- Form Group NOT REQUIRED  -->
		<label for="<?php echo $info2[0].$info2[1]; ?>" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Brewer</label>
		<div class="col-lg-3 col-md-6 col-sm-8 col-xs-12">
			<p class="form-control-static"><?php  echo $info2[0]." ".$info2[1]; ?></p>
		</div>
	</div><!-- ./Form Group -->
  	<?php } while($row_sbd = mysqli_fetch_assoc($sbd));

	if ($totalRows_sbd < $row_sbi['sbi_places']) {

	for ($i=1; $i <= ($row_sbi['sbi_places'] - $totalRows_sbd); $i++) {
	$random = random_generator(6,2);
	?>
    <input type="hidden" name="id[]" value="<?php echo $random; ?>" />
    <input type="hidden" name="entry_exists<?php echo $random; ?>" value="N" />
    <input type="hidden" name="sid<?php echo $random; ?>" value="<?php echo $id; ?>">

	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="sbd_judging_no<?php echo $random; ?>" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Winning Entry's Judging Number</label>
		<div class="col-lg-3 col-md-6 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<!-- Input Here -->
				<input class="form-control" id="sbd_judging_no<?php echo $random; ?>" name="sbd_judging_no<?php echo $random; ?>" type="text" size="10" maxlength="255" value="" placeholder="">
				<span class="input-group-addon" id="sbd_judging_no<?php echo $random; ?>-2"><span class="fa fa-star"></span></span>
			</div>
		</div>
	</div><!-- ./Form Group -->
	<div class="form-group"><!-- Form Group REQUIRED Text Input -->
		<label for="sbd_place<?php echo $random; ?>" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Place</label>
		<div class="col-lg-3 col-md-6 col-sm-8 col-xs-12">
			<div class="input-group has-warning">
				<!-- Input Here -->
				<input class="form-control" id="sbd_place<?php echo $random; ?>" name="sbd_place<?php echo $random; ?>" type="text" value="">
				<span class="input-group-addon" id="sbd_place<?php echo $random; ?>-2"><span class="fa fa-star"></span></span>
			</div>
		</div>
	</div><!-- ./Form Group -->
  <?php }
	}
	?>
  <?php } ?>

<div class="bcoem-admin-element hidden-print">
	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4 col-xs-offset-12">
			<input type="submit" name="Submit" id="updateSBD" class="btn btn-primary" value="<?php if ($action == "edit") echo "Edit"; else echo "Add"; ?> Entries" />
		</div>
	</div>
</div>
</form>
<?php } ?>