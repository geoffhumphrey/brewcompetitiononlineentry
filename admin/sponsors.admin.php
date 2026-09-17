<?php

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] > 0))) {
    if (function_exists('redirect_or_exit')) redirect_or_exit("../../403.php");
    else { header("Location: ../../403.php"); exit(); }
}

include (DB.'sponsors.db.php');

if ($dbTable == "default") {
  $directory = (USER_IMAGES);
  $empty = is_dir_empty($directory);
  $sponsor_images = directory_contents_dropdown($directory,"none","2");
}

else {
  $archive_suffix = get_suffix($dbTable);
}
?>
<script>
$(document).ready(function () {
    disable_update_button('sponsors');
});
</script>
<script src="<?php echo $js_url; ?>admin_ajax.min.js"></script>
<p class="lead"><?php echo $_SESSION['contestName']; if ($action == "add") echo ": Add a Sponsor"; elseif ($action == "edit") echo ": Edit a Sponsor"; else echo " Sponsors"; if ($dbTable != "default") echo " (Archive ".h($archive_suffix).")"; ?></p>
<div class="bcoem-admin-element hidden-print">
<?php if (($action == "add") || ($action == "edit")) { ?>
	<div class="btn-group" role="group" aria-label="add-sponsor">
        <a class="btn btn-secondary" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=sponsors"><span class="fa fa-eye"></span> View All Sponsors</a>
    </div><!-- ./button group -->
<?php } else { ?>
  <?php if ($dbTable == "default") { ?>
	<div class="btn-group" role="group" aria-label="add-sponsor">
        <a class="btn btn-secondary" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=sponsors&amp;action=add"><span class="fa fa-plus-circle"></span> Add a Sponsor</a>
    </div><!-- ./button group -->
    <div class="btn-group" role="group" aria-label="upload-sponsor">
		<a class="btn btn-primary" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=upload"><span class="fa fa-upload"></span> Upload Sponsor Logo Images</a>
	</div>
  <?php } else { ?>
    <div class="btn-group" role="group" aria-label="...">
      <a class="btn btn-secondary" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=archive"><span class="fa fa-arrow-circle-left"></span> Archives</a>
    </div><!-- ./button group -->
  <?php } ?>
<?php } ?>
</div>

<?php if ($totalRows_sponsors > 0) { ?>
<?php if ($action == "default") { ?>
<!--
<div class="bcoem-admin-element hidden-print">
<p><span class="fa fa-lg fa-check text-success"></span> = The logo's image file is present on the server and the name of the file entered matches the file's name on the server.
<p><span class="fa fa-lg fa-times text-danger"></span> =  No logo.
</div>
-->
<?php } ?>
<?php if ($action == "default") { ?>
<form name="form1" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?action=update&amp;dbTable=<?php echo $sponsors_db_table; ?>">
<input type="hidden" name="user_session_token" value ="<?php if (isset($_SESSION['user_session_token'])) echo htmlspecialchars($_SESSION['user_session_token'], ENT_QUOTES, 'UTF-8'); ?>">
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : true,
			"sPaginationType" : "full_numbers",
			"bLengthChange" : true,
			"iDisplayLength" : <?php echo $limit; ?>,
			"sDom": 'rtp',
			"bStateSave" : false,
			"aaSorting": [[0,'asc']],
			"aoColumns": [
				null,
				null,
				null,
        <?php if ($dbTable == "default") { ?>
				{ "asSorting": [  ] },
      <?php } ?>
				{ "asSorting": [  ] },
        <?php if ($dbTable == "default") { ?>
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
        <?php } ?>
				]
			} );
		} );
	</script>
<table class="table table-responsive table-striped table-bordered" id="sortable">
 <thead>
 <tr>
  <th>Sponsor Name</th>
  <th>Sponsor Location</th>
  <th>Level</th>
  <?php if ($dbTable == "default") { ?>
  <th>Logo?</th>
  <?php } ?>
  <th>Description/Text</th>
  <?php if ($dbTable == "default") { ?>
  <th>Display</th>
  <th>Actions</th>
  <?php } ?>
 </tr>
 </thead>
 <tbody>
 <?php foreach ($rows_sponsors as $row_sponsors) { ?>
 <tr>
  <td>
    <?php if ($dbTable != "default") {
    if (($row_sponsors['sponsorURL'] != "") && (preg_match('#^https?://#i', $row_sponsors['sponsorURL']))) echo "<a class=\"hide-loader\" href=\"".$row_sponsors['sponsorURL']."\" target=\"_blank\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Visit the ".$row_sponsors['sponsorName']." website\">".$row_sponsors['sponsorName']."</a>"; else echo $row_sponsors['sponsorName'];
    }
  else echo $row_sponsors['sponsorName'];
  ?>

  </td>
  <!-- sponsorLocation is already HTML-entity-encoded at save time
       (process_sponsors.inc.php's sterilize()) - h() here would double-encode it. -->
  <td><?php echo $row_sponsors['sponsorLocation']; ?></td>
  <td><?php if (isset($row_sponsors['sponsorLevel'])) { ?>
    <?php if ($dbTable == "default") { ?>
    <select class="form-select" name="sponsorLevel<?php echo $row_sponsors['id']; ?>" id="sponsor-level-ajax-<?php echo $row_sponsors['id']; ?>" onchange="save_column('<?php echo $ajax_url; ?>','sponsorLevel','sponsors','<?php echo $row_sponsors['id']; ?>','default','default','default','default','sponsor-level-ajax-<?php echo $row_sponsors['id']; ?>','value')">
          <option value="1" <?php if ($row_sponsors['sponsorLevel'] == "1") echo " SELECTED"; ?>>1</option>
          <option value="2" <?php if ($row_sponsors['sponsorLevel'] == "2") echo " SELECTED"; ?>>2</option>
          <option value="3" <?php if ($row_sponsors['sponsorLevel'] == "3") echo " SELECTED"; ?>>3</option>
          <option value="4" <?php if ($row_sponsors['sponsorLevel'] == "4") echo " SELECTED"; ?>>4</option>
          <option value="5" <?php if ($row_sponsors['sponsorLevel'] == "5") echo " SELECTED"; ?>>5</option>
      </select>
    <div class="mt-3" id="sponsor-level-ajax-<?php echo $row_sponsors['id']; ?>-sponsorLevel-form-group">
      <div>
        <span id="sponsor-level-ajax-<?php echo $row_sponsors['id']; ?>-sponsorLevel-status"></span>
        <span id="sponsor-level-ajax-<?php echo $row_sponsors['id']; ?>-sponsorLevel-status-msg"></span>
      </div>
    </div>
  <?php } else echo h($row_sponsors['sponsorLevel']); ?>
  <?php } ?>
  </td>
  <?php if ($dbTable == "default") { ?>
  <td>
    <?php if (!$empty) { ?>
    <select class="form-select bootstrap-select" name="sponsorImage<?php echo $row_sponsors['id']; ?>" id="sponsor-image-ajax-<?php echo $row_sponsors['id']; ?>" onchange="save_column('<?php echo $ajax_url; ?>','sponsorImage','sponsors','<?php echo $row_sponsors['id']; ?>','default','default','default','default','sponsor-image-ajax-<?php echo $row_sponsors['id']; ?>','value')">
       <?php
        $sponsor_images_options = "<option></option>";
          foreach ($sponsor_images as $filename) {
            $selected = "";
            if ($filename == $row_sponsors['sponsorImage']) $selected = " selected";
            $sponsor_images_options .= "<option value=\"".h($filename)."\"".$selected.">";
            $sponsor_images_options .= h($filename);
            $sponsor_images_options .= "</option>";
          }
        echo $sponsor_images_options;
       ?>
      </select>
    <div class="mt-3" id="sponsor-image-ajax-<?php echo $row_sponsors['id']; ?>-sponsorImage-form-group">
      <div>
        <span id="sponsor-image-ajax-<?php echo $row_sponsors['id']; ?>-sponsorImage-status"></span>
        <span id="sponsor-image-ajax-<?php echo $row_sponsors['id']; ?>-sponsorImage-status-msg"></span>
      </div>
    </div>
    <?php } else echo "<p>No images exist in the user_images directory.</p>"; ?>
  </td>
<?php } ?>
  <td>
    <?php if ($dbTable == "default") { ?>
    <textarea class="form-control" id="sponsor-text-ajax-<?php echo $row_sponsors['id']; ?>" name="sponsorText<?php echo $row_sponsors['id']; ?>" rows="2" class="mceNoEditor" onblur="save_column('<?php echo $ajax_url; ?>','sponsorText','sponsors','<?php echo $row_sponsors['id']; ?>','default','text-col','default','default','sponsor-text-ajax-<?php echo $row_sponsors['id']; ?>','html')"><?php if (!empty($row_sponsors['sponsorText'])) echo $row_sponsors['sponsorText']; ?></textarea>
    <div class="" id="sponsor-text-ajax-<?php echo $row_sponsors['id']; ?>-sponsorText-form-group">
      <div>
        <span id="sponsor-text-ajax-<?php echo $row_sponsors['id']; ?>-sponsorText-status"></span>
        <span id="sponsor-text-ajax-<?php echo $row_sponsors['id']; ?>-sponsorText-status-msg"></span>
      </div>
    </div>
    <?php } else echo $row_sponsors['sponsorText']; ?>
    </td>
  <?php if ($dbTable == "default") { ?>
  <td>
    <input id="sponsor-enable-ajax-<?php echo $row_sponsors['id']; ?>" type="checkbox" name="sponsorEnable<?php echo $row_sponsors['id']; ?>" value="1" <?php if ($row_sponsors['sponsorEnable'] == 1) echo 'CHECKED'; ?> onclick="$(this).attr('value', this.checked ? 1 : 0);save_column('<?php echo $ajax_url; ?>','sponsorEnable','sponsors','<?php echo $row_sponsors['id']; ?>','default','default','default','default','sponsor-enable-ajax-<?php echo $row_sponsors['id']; ?>','value')" /><input type="hidden" id="id" name="id[]" value="<?php echo $row_sponsors['id']; ?>" />
    <div class="" id="sponsor-enable-ajax-<?php echo $row_sponsors['id']; ?>-sponsorEnable-form-group">
    <div>
      <span id="sponsor-enable-ajax-<?php echo $row_sponsors['id']; ?>-sponsorEnable-status"></span>
      <span id="sponsor-enable-ajax-<?php echo $row_sponsors['id']; ?>-sponsorEnable-status-msg"></span>
    </div>
    </div>
  </td>
  <td nowrap="nowrap">
  <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_sponsors['id']; ?>" data-toggle="tooltip" data-placement="top" title="Edit <?php echo $row_sponsors['sponsorName']; ?>"><span class="fa fa-lg fa-pencil"></span></a>
  <a class="hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=<?php echo $sponsors_db_table; ?>&amp;action=delete&amp;id=<?php echo $row_sponsors['id']; ?>" data-toggle="tooltip" data-placement="top" title="Delete <?php echo $row_sponsors['sponsorName']; ?> as a sponsor" data-confirm="Are you sure you want to delete <?php echo $row_sponsors['sponsorName']; ?> as a sponsor? This cannot be undone."><span class="fa fa-lg fa-trash-o"></span></a>
  <?php if (($row_sponsors['sponsorURL'] != "") && (preg_match('#^https?://#i', $row_sponsors['sponsorURL']))) echo "<a class=\"hide-loader\" href=\"".$row_sponsors['sponsorURL']."\" target=\"_blank\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Visit the ".$row_sponsors['sponsorName']." website\"><span class=\"fa fa-lg fa-link\"></span></a> "; ?>
  </td>
  <?php } ?>
 </tr>
<?php } ?>
 </tbody>
</table>
<?php if ($dbTable == "default") { ?>
<div class="bcoem-admin-element hidden-print">
  <input type="submit" name="Submit" id="sponsors-submit" class="btn btn-primary" aria-describedby="helpBlock" value="Update Sponsors" disabled />
    <span id="sponsors-update-button-enabled" class="help-block">Select Update Sponsors <em>before</em> paging through records.</span>
    <span id="sponsors-update-button-disabled" class="help-block">The Update Sponsors button has been disabled since data is being saved successfully as it is being entered.</span>
</div>
<?php } if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin&go=sponsors","default",$msg,$id); ?>">
<?php } ?>
</form>
<?php } } else {
if ($action == "default") { ?>
<p>There are no sponsors in the database.</p>
<?php } } ?>
<?php if (($action == "add") || ($action == "edit")) { ?>
<form class="form-horizontal hide-loader-form-submit needs-validation" role="form" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?action=<?php echo $action; ?>&amp;dbTable=<?php echo $sponsors_db_table; ?><?php if ($action == "edit") echo "&amp;id=".$id; ?>" name="form1" novalidate>
<input type="hidden" name="user_session_token" value ="<?php if (isset($_SESSION['user_session_token'])) echo htmlspecialchars($_SESSION['user_session_token'], ENT_QUOTES, 'UTF-8'); ?>">
<div class="row mb-3"><!-- Form Group REQUIRED Text Input -->
    <label for="sponsorName" class="col-xs-12 col-sm-4 col-lg-2 col-form-label"><i class="fa fa-star me-1"></i>Name</label>
    <div class="col-xs-12 col-sm-8 col-lg-6">
        <input class="form-control" id="sponsorName" name="sponsorName" type="text" maxlength="255" value="<?php if ($action == "edit") echo $row_sponsors['sponsorName']; ?>" placeholder="" autofocus required>
        <div class="help-block invalid-feedback text-danger">The sponsor's name is required.</div>
    </div>
</div><!-- ./Form Group -->

<div class="row mb-3"><!-- Form Group NOT REQUIRED Text Input -->
    <!-- sponsorLocation is already HTML-entity-encoded at save time
         (process_sponsors.inc.php's sterilize()) - h() here would double-encode it. -->
    <label for="sponsorLocation" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">Location</label>
    <div class="col-xs-12 col-sm-8 col-lg-6">
        <input class="form-control" id="sponsorLocation" name="sponsorLocation" type="text" value="<?php if ($action == "edit") echo $row_sponsors['sponsorLocation']; ?>" placeholder="">
    </div>
</div><!-- ./Form Group -->

<div class="row mb-3"><!-- Form Group NOT REQUIRED Select -->
	<label for="sponsorLevel" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">Level</label>
	<div class="col-xs-12 col-sm-8 col-lg-6">
	<select class="form-select bootstrap-select" name="sponsorLevel" id="sponsorLevel">
    	<option value="1" <?php if (($action == "edit") && ($row_sponsors['sponsorLevel'] == "1")) echo " SELECTED"; ?>>1</option>
        <option value="2" <?php if (($action == "edit") && ($row_sponsors['sponsorLevel'] == "2")) echo " SELECTED"; ?>>2</option>
        <option value="3" <?php if (($action == "edit") && ($row_sponsors['sponsorLevel'] == "3")) echo " SELECTED"; ?>>3</option>
        <option value="4" <?php if (($action == "edit") && ($row_sponsors['sponsorLevel'] == "4")) echo " SELECTED"; ?>>4</option>
        <option value="5" <?php if (($action == "edit") && ($row_sponsors['sponsorLevel'] == "5")) echo " SELECTED"; ?>>5</option>
    </select>
	<div class="help-block">Indicate the level of the sponsor. 1 is the highest level; 5 the lowest.</div>
	</div>
</div><!-- ./Form Group -->

<div class="row mb-3"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="sponsorURL" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">Website</label>
    <div class="col-xs-12 col-sm-8 col-lg-6">
        <input class="form-control" id="sponsorURL" name="sponsorURL" type="text" value="<?php if ($action == "edit") echo $row_sponsors['sponsorURL']; ?>" placeholder="">
		<div class="help-block">Be sure to include the full website URL including the http://</div>
    </div>
</div><!-- ./Form Group -->

<div class="row mb-3"><!-- Form Group NOT REQUIRED Select -->
    <label for="sponsorImage" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">Logo File Name</label>
    <div class="col-xs-12 col-sm-8 col-lg-6">
    <?php if (!$empty) { ?>
    <select class="form-select bootstrap-select" name="sponsorImage" id="sponsorImage">
       <?php
        $sponsor_images_options = "<option></option>";
          foreach ($sponsor_images as $filename) {
            $selected = "";
            if ($filename == $row_sponsors['sponsorImage']) $selected = " selected";
            $sponsor_images_options .= "<option value=\"".h($filename)."\"".$selected.">";
            $sponsor_images_options .= h($filename);
            $sponsor_images_options .= "</option>";
          }
        echo $sponsor_images_options;
       ?>
    </select>
    <?php } else echo "<p>No images exist in the user_images directory.</p>"; ?>
    <div class="help-block">If the directory is empty or a file is not on the list, use the &ldquo;Upload Logo Images&rdquo; button below.</div>
    <a class="btn btn-sm btn-primary" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=upload"><span class="fa fa-upload"></span> Upload Logo Images</a>
    </div>
</div><!-- ./Form Group -->

<div class="row mb-3"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="sponsorText" class="col-xs-12 col-sm-4 col-lg-2 col-form-label">Description</label>
    <div class="col-xs-12 col-sm-8 col-lg-6">
        <textarea class="form-control" name="sponsorText" rows="6" class="mceNoEditor"><?php if ($action == "edit") echo $row_sponsors['sponsorText']; ?></textarea>
        <div class="help-block">Any additional information about the sponsor (e.g., a description of sponsorship level, the items donated, money contributed, etc.).</div>
    </div>
</div><!-- ./Form Group -->

<div class="row mb-3"><!-- Form Group Radio INLINE -->
    <label class="col-xs-12 col-sm-4 col-lg-2 col-form-label">Display?</label>
    <div class="col-xs-12 col-sm-8 col-lg-6">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="sponsorEnable" value="1" id="sponsorEnable_0" <?php if (($action == "edit") && ($row_sponsors['sponsorEnable'] == 1)) echo "checked"; if ($action == "add") echo "checked"; ?>>
            <label class="form-check-label" for="sponsorEnable_0">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="sponsorEnable" value="0" id="sponsorEnable_1" <?php if (($action == "edit") && ($row_sponsors['sponsorEnable'] == 0)) echo "checked"; ?>>
            <label class="form-check-label" for="sponsorEnable_1">No</label>
        </div>
		<div class="help-block">Do want to display this sponsor on the home page and sponsors page?</div>
    </div>
</div><!-- ./Form Group -->

<div class="bcoem-admin-element hidden-print">
	<div class="row mb-3">
		<div class="col-xs-12 col-sm-8 col-lg-6 offset-sm-4 offset-lg-2">
			<input type="submit" name="Submit" id="updateSponsor" class="btn btn-primary" value="<?php if ($action == "edit") echo "Edit"; else echo "Add"; ?> Sponsor">
		</div>
	</div>
</div>
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin&go=sponsors","default",$msg,$id); ?>">
<?php } ?>
</form>
<?php } ?>
