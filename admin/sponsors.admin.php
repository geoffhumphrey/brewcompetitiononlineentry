<?php
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
<script src="<?php echo $base_url;?>js_includes/admin_ajax.min.js"></script>
<p class="lead"><?php echo $_SESSION['contestName']; if ($action == "add") echo ": Add a Sponsor"; elseif ($action == "edit") echo ": Edit a Sponsor"; else echo " Sponsors"; if ($dbTable != "default") echo " (Archive ".$archive_suffix.")"; ?></p>
<div class="bcoem-admin-element hidden-print">
<?php if (($action == "add") || ($action == "edit")) { ?>
	<div class="btn-group" role="group" aria-label="add-sponsor">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=sponsors"><span class="fa fa-eye"></span> View All Sponsors</a>
    </div><!-- ./button group -->
<?php } else { ?>
  <?php if ($dbTable == "default") { ?>
	<div class="btn-group" role="group" aria-label="add-sponsor">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=sponsors&amp;action=add"><span class="fa fa-plus-circle"></span> Add a Sponsor</a>
    </div><!-- ./button group -->
    <div class="btn-group" role="group" aria-label="upload-sponsor">
		<a class="btn btn-primary" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=upload"><span class="fa fa-upload"></span> Upload Sponsor Logo Images</a>
	</div>
  <?php } else { ?>
    <div class="btn-group" role="group" aria-label="...">
      <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=archive"><span class="fa fa-arrow-circle-left"></span> Archives</a>
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
<input type="hidden" name="token" value ="<?php if (isset($_SESSION['token'])) echo $_SESSION['token']; ?>">
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
 <?php do { ?>
 <tr>
  <td>
    <?php if ($dbTable != "default") {
    if ($row_sponsors['sponsorURL'] !="") echo "<a class=\"hide-loader\" href=\"".$row_sponsors['sponsorURL']."\" target=\"_blank\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Visit the ".$row_sponsors['sponsorName']." website\">".$row_sponsors['sponsorName']."</a>"; else echo $row_sponsors['sponsorName'];
    }
  else echo $row_sponsors['sponsorName']; 
  ?>
    
  </td>
  <td><?php echo $row_sponsors['sponsorLocation']; ?></td>
  <td><?php if (isset($row_sponsors['sponsorLevel'])) { ?>
    <?php if ($dbTable == "default") { ?>
    <div class="form-group" id="sponsor-level-ajax-<?php echo $row_sponsors['id']; ?>-sponsorLevel-form-group">
      <select class="selectpicker" name="sponsorLevel<?php echo $row_sponsors['id']; ?>" id="sponsor-level-ajax-<?php echo $row_sponsors['id']; ?>" data-width="auto" onchange="save_column('<?php echo $base_url; ?>','sponsorLevel','sponsors','<?php echo $row_sponsors['id']; ?>','default','default','default','default','sponsor-level-ajax-<?php echo $row_sponsors['id']; ?>','value')">
          <option value="1" <?php if ($row_sponsors['sponsorLevel'] == "1") echo " SELECTED"; ?>>1</option>
          <option value="2" <?php if ($row_sponsors['sponsorLevel'] == "2") echo " SELECTED"; ?>>2</option>
          <option value="3" <?php if ($row_sponsors['sponsorLevel'] == "3") echo " SELECTED"; ?>>3</option>
          <option value="4" <?php if ($row_sponsors['sponsorLevel'] == "4") echo " SELECTED"; ?>>4</option>
          <option value="5" <?php if ($row_sponsors['sponsorLevel'] == "5") echo " SELECTED"; ?>>5</option>
      </select>
      <div>
        <span id="sponsor-level-ajax-<?php echo $row_sponsors['id']; ?>-sponsorLevel-status"></span>
        <span id="sponsor-level-ajax-<?php echo $row_sponsors['id']; ?>-sponsorLevel-status-msg"></span>
      </div>
    </div>
  <?php } else echo $row_sponsors['sponsorLevel']; ?>
  <?php } ?>
  </td>
  <?php if ($dbTable == "default") { ?>
  <td>  
    <?php if (!$empty) { ?>
    <div class="form-group" id="sponsor-image-ajax-<?php echo $row_sponsors['id']; ?>-sponsorImage-form-group">
      <select class="selectpicker" name="sponsorImage<?php echo $row_sponsors['id']; ?>" id="sponsor-image-ajax-<?php echo $row_sponsors['id']; ?>" data-live-search="true" data-size="10" data-width="auto" onchange="save_column('<?php echo $base_url; ?>','sponsorImage','sponsors','<?php echo $row_sponsors['id']; ?>','default','default','default','default','sponsor-image-ajax-<?php echo $row_sponsors['id']; ?>','value')">
       <?php 
        $sponsor_images_options = "<option></option>";
          foreach ($sponsor_images as $filename) {
            $selected = "";
            if ($filename == $row_sponsors['sponsorImage']) $selected = " selected";
            $sponsor_images_options .= "<option value=\"".$filename."\"".$selected.">";
            $sponsor_images_options .= $filename;
            $sponsor_images_options .= "</option>";
          }
        echo $sponsor_images_options;
       ?>
      </select>
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
    <div class="form-group" id="sponsor-text-ajax-<?php echo $row_sponsors['id']; ?>-sponsorText-form-group">
    <textarea class="form-control" id="sponsor-text-ajax-<?php echo $row_sponsors['id']; ?>" name="sponsorText<?php echo $row_sponsors['id']; ?>" rows="2" class="mceNoEditor" onblur="save_column('<?php echo $base_url; ?>','sponsorText','sponsors','<?php echo $row_sponsors['id']; ?>','default','text-col','default','default','sponsor-text-ajax-<?php echo $row_sponsors['id']; ?>','html')"><?php if (!empty($row_sponsors['sponsorText'])) echo $row_sponsors['sponsorText']; ?></textarea>
      <div>
        <span id="sponsor-text-ajax-<?php echo $row_sponsors['id']; ?>-sponsorText-status"></span>
        <span id="sponsor-text-ajax-<?php echo $row_sponsors['id']; ?>-sponsorText-status-msg"></span>
      </div>
    </div>
    <?php } else echo $row_sponsors['sponsorText']; ?>
    </td>
  <?php if ($dbTable == "default") { ?>
  <td>
    <div class="form-group" id="sponsor-enable-ajax-<?php echo $row_sponsors['id']; ?>-sponsorEnable-form-group">
    <input id="sponsor-enable-ajax-<?php echo $row_sponsors['id']; ?>" type="checkbox" name="sponsorEnable<?php echo $row_sponsors['id']; ?>" value="1" <?php if ($row_sponsors['sponsorEnable'] == 1) echo 'CHECKED'; ?> onclick="$(this).attr('value', this.checked ? 1 : 0);save_column('<?php echo $base_url; ?>','sponsorEnable','sponsors','<?php echo $row_sponsors['id']; ?>','default','default','default','default','sponsor-enable-ajax-<?php echo $row_sponsors['id']; ?>','value')" /><input type="hidden" id="id" name="id[]" value="<?php echo $row_sponsors['id']; ?>" />
    <div>
      <span id="sponsor-enable-ajax-<?php echo $row_sponsors['id']; ?>-sponsorEnable-status"></span>
      <span id="sponsor-enable-ajax-<?php echo $row_sponsors['id']; ?>-sponsorEnable-status-msg"></span>
    </div>
    </div>
  </td>
  <td nowrap="nowrap">
  <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_sponsors['id']; ?>" data-toggle="tooltip" data-placement="top" title="Edit <?php echo $row_sponsors['sponsorName']; ?>"><span class="fa fa-lg fa-pencil"></span></a>
  <a class="hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=<?php echo $sponsors_db_table; ?>&amp;action=delete&amp;id=<?php echo $row_sponsors['id']; ?>" data-toggle="tooltip" data-placement="top" title="Delete <?php echo $row_sponsors['sponsorName']; ?> as a sponsor" data-confirm="Are you sure you want to delete <?php echo $row_sponsors['sponsorName']; ?> as a sponsor? This cannot be undone."><span class="fa fa-lg fa-trash-o"></span></a>
  <?php if ($row_sponsors['sponsorURL'] !="") echo "<a class=\"hide-loader\" href=\"".$row_sponsors['sponsorURL']."\" target=\"_blank\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Visit the ".$row_sponsors['sponsorName']." website\"><span class=\"fa fa-lg fa-link\"></span></a> "; ?>
  </td>
  <?php } ?>
 </tr>
<?php } while($row_sponsors = mysqli_fetch_assoc($sponsors)) ?>
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
<form data-toggle="validator" role="form" class="form-horizontal" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?action=<?php echo $action; ?>&amp;dbTable=<?php echo $sponsors_db_table; ?><?php if ($action == "edit") echo "&amp;id=".$id; ?>" name="form1">
<input type="hidden" name="token" value ="<?php if (isset($_SESSION['token'])) echo $_SESSION['token']; ?>">
<div class="form-group"><!-- Form Group REQUIRED Text Input -->
    <label for="sponsorName" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Name</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group has-warning">
            <!-- Input Here -->
            <input class="form-control" id="sponsorName" name="sponsorName" type="text" maxlength="255" value="<?php if ($action == "edit") echo $row_sponsors['sponsorName']; ?>" placeholder="" data-error="The sponsor's name is required" autofocus required>
            <span class="input-group-addon" id="sponsorName-addon2"><span class="fa fa-star"></span></span>
        </div>
        <div class="help-block with-errors"></div>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="sponsorLocation" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Location</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <input class="form-control" id="sponsorLocation" name="sponsorLocation" type="text" value="<?php if ($action == "edit") echo $row_sponsors['sponsorLocation']; ?>" placeholder="">
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
	<label for="sponsorLevel" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Level</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
	<!-- Input Here -->
	<select class="selectpicker" name="sponsorLevel" id="sponsorLevel" data-width="auto">
    	<option value="1" <?php if (($action == "edit") && ($row_sponsors['sponsorLevel'] == "1")) echo " SELECTED"; ?>>1</option>
        <option value="2" <?php if (($action == "edit") && ($row_sponsors['sponsorLevel'] == "2")) echo " SELECTED"; ?>>2</option>
        <option value="3" <?php if (($action == "edit") && ($row_sponsors['sponsorLevel'] == "3")) echo " SELECTED"; ?>>3</option>
        <option value="4" <?php if (($action == "edit") && ($row_sponsors['sponsorLevel'] == "4")) echo " SELECTED"; ?>>4</option>
        <option value="5" <?php if (($action == "edit") && ($row_sponsors['sponsorLevel'] == "5")) echo " SELECTED"; ?>>5</option>
    </select>
	<span id="helpBlock" class="help-block">Indicate the level of the sponsor. 1 is the highest level; 5 the lowest.</span>
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="sponsorURL" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Website</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <input class="form-control" id="sponsorURL" name="sponsorURL" type="text" value="<?php if ($action == "edit") echo $row_sponsors['sponsorURL']; ?>" placeholder="">
		<span id="helpBlock" class="help-block">Be sure to include the full website URL including the http://</span>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
    <label for="contestLogo" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Logo File Name</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
    <!-- Input Here -->
    <?php if (!$empty) { ?>
    <select class="selectpicker" name="sponsorImage" id="sponsorImage" data-live-search="true" data-size="10" data-width="auto">
       <?php 
        $sponsor_images_options = "<option></option>";
          foreach ($sponsor_images as $filename) {
            $selected = "";
            if ($filename == $row_sponsors['sponsorImage']) $selected = " selected";
            $sponsor_images_options .= "<option value=\"".$filename."\"".$selected.">";
            $sponsor_images_options .= $filename;
            $sponsor_images_options .= "</option>";
          }
        echo $sponsor_images_options;
       ?>
    </select>
    <?php } else echo "<p>No images exist in the user_images directory.</p>"; ?>
    <span id="helpBlock" class="help-block">If the directory is empty or a file is not on the list, use the &ldquo;Upload Logo Images&rdquo; button below.</span>
    <a class="btn btn-sm btn-primary" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=upload"><span class="fa fa-upload"></span> Upload Logo Images</a>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Text Input -->
    <label for="sponsorText" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Description</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <textarea class="form-control" name="sponsorText" rows="6" class="mceNoEditor"><?php if ($action == "edit") echo $row_sponsors['sponsorText']; ?></textarea>
        <span id="helpBlock" class="help-block">Any additional information about the sponsor (e.g., a description of sponsorship level, the items donated, money contributed, etc.).</span>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="sponsorEnable" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Display?</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="sponsorEnable" value="1" id="sponsorEnable_0"  <?php if (($action == "edit") && ($row_sponsors['sponsorEnable'] == 1)) echo "CHECKED"; if ($action == "add") echo "CHECKED"; ?> /> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="sponsorEnable" value="0" id="sponsorEnable_1" <?php if (($action == "edit") && ($row_sponsors['sponsorEnable'] == 0)) echo "CHECKED"; ?>/> No
            </label>
        </div>
		<span id="helpBlock" class="help-block">Do want to display this sponsor on the home page and sponsors page?</span>
    </div>
</div><!-- ./Form Group -->

<div class="bcoem-admin-element hidden-print">
	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
			<input type="submit" name="Submit" id="updateSponsor" class="btn btn-primary" value="<?php if ($action == "edit") echo "Edit"; else echo "Add"; ?> Sponsor" />
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