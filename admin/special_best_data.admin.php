<?php
/**
 * Module:      special_best_data.admin.php
 * Description: Add, edit, and delete any custom "best of" categories for a comp.
 *              (e.g., for a Pro-Am, Best Entry Name, Stewards Choice, etc.)
 */

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] > 0))) {
    if (function_exists('redirect_or_exit')) redirect_or_exit("../../403.php");
    else { header("Location: ../../403.php"); exit(); }
}

?>
<p class="lead"><?php echo $_SESSION['contestName']; if ($action == "add") echo ": Add Entries to the ".h($row_sbi['sbi_name'])." Custom Style"; elseif ($action == "edit") echo ": Edit Entries in the ".h($row_sbi['sbi_name'])." Custom Style"; else echo " Custom Style Entries"; ?></p>

<div class="bcoem-admin-element hidden-print">
<!-- View Button Group Dropdown -->
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="fa fa-eye"></span> View...
        <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
			<li class="small"><a class="dropdown-item" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best">All Custom Categories</a></li>
			<?php if ($totalRows_sbd > 0) { ?>
            <li class="small"><a class="dropdown-item" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best_data">All Custom Style Entries</a></li>
			<?php } ?>
        </ul>
    </div><!-- ./button group -->

    <div class="btn-group" role="group" aria-label="add-custom-winning">
        <a class="btn btn-secondary" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best&amp;action=add"><span class="fa fa-plus-circle"></span> Add a Custom Category</a>
    </div><!-- ./button group -->

	<div class="btn-group" role="group">
        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="fa fa-plus-circle"></span> Add/Edit Entries For...
        <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
			<?php echo score_custom_winning_choose($special_best_info_db_table,$special_best_data_db_table); ?>
        </ul>
    </div><!-- ./button group -->



</div>

<?php if (($action == "default") || ($action == "list")) { ?>
<p>Custom categories are useful if your competition features unique &ldquo;best of show&rdquo; categories, such as Pro-Am opportunities, Stewards&rsquo; Choice, Best Name, etc.</p>
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
    <?php foreach ($rows_sbd as $row_sbd) {
	$info = explode("^", entry_info($row_sbd['eid']));
	$brewer_info = explode("^", brewer_info($row_sbd['bid']));
	$special_best_info = explode("^",special_best_info($row_sbd['sid']));

	?>
     <tr>
      <td><?php echo h($special_best_info[1]); ?></td>
      <td><?php echo $row_sbd['sbd_place']; ?></td>
      <td><?php echo sprintf("%06s",$row_sbd['eid']); ?></td>
      <td><?php echo readable_judging_number($info[3],$info[6]); ?></td>
      <td><?php echo h($info[0]); ?></td>
      <td><?php echo h($brewer_info[0])." ".h($brewer_info[1]); ?></td>
      <td nowrap="nowrap">
	  <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_sbd['sid']; ?>" data-toggle="tooltip" data-placement="top" title="Edit the <?php echo h($special_best_info[1]); ?> Custom Style entries"><span class="fa fa-lg fa-pencil"></span></a>
	  <a class="hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=<?php echo $special_best_data_db_table; ?>&amp;action=delete&amp;id=<?php echo $row_sbd['id']; ?>" data-toggle="tooltip" data-placement="top" title="Delete &ldquo;<?php echo h($info[0]); ?>&rdquo; as a winner for the <?php echo h($special_best_info[1]); ?> Custom Style"  data-confirm="Are you sure you want to delete <?php echo h($info[0]); ?>? This cannot be undone."><span class="fa fa-lg fa-trash-o"></span></a>
      </td>
     </tr>
    <?php
	} ?>
     </tbody>
    </table>
    <?php }
	else echo "<p>There are no entries found in any custom category.</p>";
}
if (($action == "add") || ($action == "edit")) { ?>
<form class="form-horizontal needs-validation" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?action=<?php echo $action; ?>&amp;dbTable=<?php echo $special_best_data_db_table; ?>&id=<?php echo $id; ?>" name="form1" novalidate>
<input type="hidden" name="user_session_token" value ="<?php if (isset($_SESSION['user_session_token'])) echo htmlspecialchars($_SESSION['user_session_token'], ENT_QUOTES, 'UTF-8'); ?>">
<?php
if ($action == "add") {
	if (empty($row_sbi['sbi_places'])) $sbi_places = 1;
	else $sbi_places = $row_sbi['sbi_places'];
	for ($i=1; $i <= $sbi_places; $i++) {
?>
	<input type="hidden" name="id[]" value="<?php echo $i; ?>" />
    <input type="hidden" name="sid<?php echo $i; ?>" value="<?php echo $id; ?>">
	<div class="row mb-3"><!-- Form Group REQUIRED Text Input -->
		<label for="sbd_judging_no<?php echo $i; ?>" class="col-xs-12 col-sm-4 col-lg-2 col-form-label"><i class="fa fa-star me-1"></i>Winning Entry <?php echo $i; ?>'s Judging Number</label>
		<div class="col-xs-12 col-sm-8 col-lg-3">
			<input class="form-control judging-place-pair" id="sbd_judging_no<?php echo $i; ?>" name="sbd_judging_no<?php echo $i; ?>" type="text" size="10" maxlength="255" value="" placeholder="" <?php if ($i == 1) echo "autofocus"; ?>>
			<div class="help-block invalid-feedback text-danger">Enter both a judging number and a place, or leave both blank to skip this place.</div>
		</div>
	</div><!-- ./Form Group -->


	<div class="row mb-3"><!-- Form Group REQUIRED Text Input -->
		<label for="sbd_place<?php echo $i; ?>" class="col-xs-12 col-sm-4 col-lg-2 col-form-label"><i class="fa fa-star me-1"></i>Place</label>
		<div class="col-xs-12 col-sm-8 col-lg-3">
			<input class="form-control judging-place-pair" id="sbd_place<?php echo $i; ?>" name="sbd_place<?php echo $i; ?>" type="text" value="">
			<div class="help-block invalid-feedback text-danger">Enter both a judging number and a place, or leave both blank to skip this place.</div>
		</div>
	</div><!-- ./Form Group -->

  <?php }
	} // end if ($action == "add")
	if ($action == "edit") {
		foreach ($rows_sbd as $row_sbd) {
		if ($row_sbd) $info = explode("^", entry_info($row_sbd['eid']));
	?>


  <input type="hidden" name="id[]" value="<?php if ($row_sbd) echo $row_sbd['id']; ?>" />
  <input type="hidden" name="bid<?php if ($row_sbd) echo $row_sbd['id']; ?>" value="<?php echo $row_sbd['bid']; ?>" />
  <input type="hidden" name="eid<?php if ($row_sbd) echo $row_sbd['id']; ?>" value="<?php echo $row_sbd['eid']; ?>" />
  <input type="hidden" name="sid<?php if ($row_sbd) echo $row_sbd['id']; ?>" value="<?php echo $id; ?>">
  <input type="hidden" name="entry_exists<?php echo $row_sbd['id']; ?>" value="Y" />
  <div class="row mb-3"><!-- Form Group REQUIRED Text Input -->
		<label for="sbd_judging_no<?php echo $row_sbd['id']; ?>" class="col-xs-12 col-sm-4 col-lg-2 col-form-label"><i class="fa fa-star me-1"></i>Winning Entry's Judging Number</label>
		<div class="col-xs-12 col-sm-8 col-lg-3">
			<input class="form-control judging-place-pair" id="sbd_judging_no<?php if ($row_sbd) echo $row_sbd['id']; ?>" name="sbd_judging_no<?php if ($row_sbd) echo $row_sbd['id']; ?>" type="text" size="10" maxlength="255" value="<?php if ($row_sbd)  echo readable_judging_number($info[3],$info[6]); ?>" placeholder="">
			<div class="help-block invalid-feedback text-danger">Enter both a judging number and a place, or leave both blank to skip this place.</div>
		</div>
	</div><!-- ./Form Group -->
	<div class="row mb-3"><!-- Form Group REQUIRED Text Input -->
		<label for="sbd_place<?php  echo $row_sbd['id']; ?>" class="col-xs-12 col-sm-4 col-lg-2 col-form-label"><i class="fa fa-star me-1"></i>Place</label>
		<div class="col-xs-12 col-sm-8 col-lg-3">
			<input class="form-control judging-place-pair" id="sbd_place<?php if ($row_sbd)  echo $row_sbd['id']; ?>" name="sbd_place<?php  echo $row_sbd['id']; ?>" type="text" value="<?php if ($row_sbd) echo $row_sbd['sbd_place']; ?>">
			<div class="help-block invalid-feedback text-danger">Enter both a judging number and a place, or leave both blank to skip this place.</div>
		</div>
	</div><!-- ./Form Group -->

	<div class="row mb-3"><!-- Form Group NOT REQUIRED  -->
		<label for="<?php echo $info[0]; ?>" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">Entry Name</label>
		<div class="col-xs-12 col-sm-8 col-lg-3">
			<input type="text" readonly class="form-control-plaintext" value="<?php if ($row_sbd) echo $info[0]; ?>">
		</div>
	</div><!-- ./Form Group -->
	<?php if ($row_sbd) $info2 = explode("^", brewer_info($row_sbd['bid'])); ?>
	<div class="row mb-3"><!-- Form Group NOT REQUIRED  -->
		<label for="<?php if ($row_sbd) echo $info2[0].$info2[1]; ?>" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">Brewer</label>
		<div class="col-xs-12 col-sm-8 col-lg-3">
			<input type="text" readonly class="form-control-plaintext" value="<?php if ($row_sbd) echo $info2[0]." ".$info2[1]; ?>">
		</div>
	</div><!-- ./Form Group -->
  	<?php }

	if ($totalRows_sbd < $row_sbi['sbi_places']) {

	for ($i=1; $i <= ($row_sbi['sbi_places'] - $totalRows_sbd); $i++) {
	$random = random_generator(6,2);
	?>
    <input type="hidden" name="id[]" value="<?php echo $random; ?>" />
    <input type="hidden" name="entry_exists<?php echo $random; ?>" value="N" />
    <input type="hidden" name="sid<?php echo $random; ?>" value="<?php echo $id; ?>">

	<div class="row mb-3"><!-- Form Group REQUIRED Text Input -->
		<label for="sbd_judging_no<?php echo $random; ?>" class="col-xs-12 col-sm-4 col-lg-2 col-form-label"><i class="fa fa-star me-1"></i>Winning Entry's Judging Number</label>
		<div class="col-xs-12 col-sm-8 col-lg-3">
			<input class="form-control judging-place-pair" id="sbd_judging_no<?php echo $random; ?>" name="sbd_judging_no<?php echo $random; ?>" type="text" size="10" maxlength="255" value="" placeholder="">
			<div class="help-block invalid-feedback text-danger">Enter both a judging number and a place, or leave both blank to skip this place.</div>
		</div>
	</div><!-- ./Form Group -->
	<div class="row mb-3"><!-- Form Group REQUIRED Text Input -->
		<label for="sbd_place<?php echo $random; ?>" class="col-xs-12 col-sm-4 col-lg-2 col-form-label"><i class="fa fa-star me-1"></i>Place</label>
		<div class="col-xs-12 col-sm-8 col-lg-3">
			<input class="form-control judging-place-pair" id="sbd_place<?php echo $random; ?>" name="sbd_place<?php echo $random; ?>" type="text" value="">
			<div class="help-block invalid-feedback text-danger">Enter both a judging number and a place, or leave both blank to skip this place.</div>
		</div>
	</div><!-- ./Form Group -->
  <?php }
	}
	?>
  <?php } ?>

<div class="bcoem-admin-element hidden-print">
	<div class="row mb-3">
		<div class="col-xs-12 col-sm-8 col-lg-3 offset-sm-4 offset-lg-2">
			<input type="submit" name="Submit" id="updateSBD" class="btn btn-primary" value="<?php if ($action == "edit") echo "Edit"; else echo "Add"; ?> Entries">
		</div>
	</div>
</div>
</form>
<script type="text/javascript" language="javascript">
	$(document).ready(function () {
		"use strict";
		// Each judging-number/place pair may be left entirely blank (skips that
		// place, per process_special_best_data.inc.php) - but if either field in
		// a pair has a value, both become required, so a half-filled pair can't
		// be submitted silently.
		function pairSuffix(el) {
			var id = el.id || "";
			if (id.indexOf("sbd_judging_no") === 0) return id.substring("sbd_judging_no".length);
			if (id.indexOf("sbd_place") === 0) return id.substring("sbd_place".length);
			return null;
		}
		function syncPair(suffix) {
			var judging = document.getElementById("sbd_judging_no" + suffix);
			var place = document.getElementById("sbd_place" + suffix);
			if (!judging || !place) return;
			var started = (judging.value.trim() !== "") || (place.value.trim() !== "");
			judging.required = started;
			place.required = started;
		}
		var suffixes = [];
		$(".judging-place-pair").each(function () {
			var suffix = pairSuffix(this);
			if ((suffix !== null) && (suffixes.indexOf(suffix) === -1)) suffixes.push(suffix);
		});
		suffixes.forEach(syncPair);
		$(".judging-place-pair").on("input change", function () {
			var suffix = pairSuffix(this);
			if (suffix !== null) syncPair(suffix);
		});
	});
</script>
<?php } ?>
