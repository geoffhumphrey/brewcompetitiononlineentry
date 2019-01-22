<?php
/**
 * Module:      mods.admin.php
 * Description: Add, edit, and delete any custom modules that extend core functions.
 */

//require(DB.'mods.db.php');

function mod_info($info,$method) {
	if ($method == 1) {
		switch($info) {
			case "0": $output = "Informational (Basic HTML)"; break;
			case "1": $output = "Report"; break;
			case "2": $output = "Export"; break;
			case "3": $output = "PHP Code or Function"; break;
		}
	}

	if ($method == 2) {
		switch($info) {
			case "0": $output = "All"; break;
			case "1": $output = "Home Page"; break;
			case "2": $output = "Rules"; break;
			case "3": $output = "Volunteer Info"; break;
			case "4": $output = "Sponsors"; break;
			case "5": $output = "Contact"; break;
			case "6": $output = "Registration"; break;
			case "7": $output = "Payment"; break;
			case "8": $output = "User's Account"; break;
			case "9": $output = "Administration"; break;
		}
	}

	if ($method == 3) {
		switch($info) {
			case "0": $output = "Top level admins"; break;
			case "1": $output = "Admins"; break;
			case "2": $output = "All"; break;
		}
	}

	if ($method == 4) {
		switch($info) {
			case "0": $output = "N/A (Stand-alone)"; break;
			case "1": $output = "Before public core content"; break;
			case "2": $output = "After public core content"; break;
			case "3": $output = "Before public sidebar content"; break;
			case "4": $output = "After public sidebar content"; break;
		}
	}

	return $output;
}

 ?>
<p class="lead"><?php echo $_SESSION['contestName']; if ($action == "add") echo ": Add a Custom Module"; elseif ($action == "edit") echo ": Edit a Custom Module"; else echo " Custom Modules";  ?></p>

<div class="bcoem-admin-element hidden-print">
<?php if (($action == "add") || ($action == "edit")) { ?>
<div class="btn-group" role="group" aria-label="...">
	<a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=mods"><span class="fa fa-arrow-circle-left"></span> All Custom Modules</a>
</div><!-- ./button group -->
<?php } else { ?>
<div class="btn-group" role="group" aria-label="...">
	<a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=mods&amp;action=add"><span class="fa fa-plus-circle"></span> Add a Custom Module</a>
</div><!-- ./button group -->
<?php } ?>
</div>
<?php if ($action == "default") { ?>
<form name="form1" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?action=update&amp;dbTable=<?php echo $mods_db_table; ?>">
	<p>Custom modules are useful for competitions that wish to extend BCOE&amp;M's core functions. Provided in the program package are two sample HTML files to get you started, located in the &ldquo;Mods&rdquo; sub-folder. All mod files MUST have a .php extension (e.g., name_of_file.php - some servers running PHP are not configured to &quot;include&quot; files with other exensions).</p>
  	<p>For the program to use/display any custom module, its information MUST be added into the database. The corresponding file should be uploaded to the &ldquo;mods&rdquo; sub-folder via secure FTP.</p>
	<p><em><strong>Errors in coding may result in warnings and/or &quot;broken&quot; pages. Use caution!</strong></em></p>

	<?php if ($totalRows_mods > 0) { ?>
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
                    { "asSorting": [  ] },
                    null,
                    null,
					null,
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
      <th>Description</th>
      <th>Type</th>
      <th>Extends Core Function</th>
      <th>Extends Admin Function</th>
      <th>File Name</th>
      <th>Permission</th>
      <th>Display</th>
      <th>Enabled</th>
      <th>Actions</th>
     </tr>
     </thead>
     <tbody>
     <?php do { ?>
     <tr>
      <td><?php echo $row_mods['mod_name']; ?></td>
      <td><?php echo $row_mods['mod_description']; ?></td>
      <td><?php echo mod_info($row_mods['mod_type'],1); ?></td>
      <td><?php echo mod_info($row_mods['mod_extend_function'],2); ?></td>
      <td><?php echo ucfirst(str_replace("_"," ",$row_mods['mod_extend_function_admin'])); ?></td>
      <td><?php echo $row_mods['mod_filename']; ?></td>
      <td><?php echo mod_info($row_mods['mod_permission'],3); ?></td>
      <td><?php echo mod_info($row_mods['mod_display_rank'],4); ?></td>
      <td><input id="mod_enable" type="checkbox" name="mod_enable<?php echo $row_mods['id']; ?>" value="1" <?php if ($row_mods['mod_enable'] == 1) echo 'checked="checked"'; ?> /><input type="hidden" id="id" name="id[]" value="<?php echo $row_mods['id']; ?>" /></td>
      <td nowrap="nowrap">
      <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_mods['id']; ?>" data-toggle="tooltip" data-placement="top" title="Edit <?php echo $row_mods['mod_name']; ?>"><span class="fa fa-pencil"></span></a>
      <a class="hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=<?php echo $mods_db_table; ?>&amp;action=delete&amp;id=<?php echo $row_mods['id']; ?>" data-toggle="tooltip" data-placement="top" title="Delete <?php echo $row_mods['mod_name']; ?>" data-confirm="Are you sure you want to delete <?php echo $row_mods['mod_name']; ?>? This cannot be undone. All associated data will be deleted as well."><span class="fa fa-trash-o"></span></a>
      </td>
     </tr>
    <?php } while($row_mods = mysqli_fetch_assoc($mods)) ?>
     </tbody>
    </table>
<div class="bcoem-admin-element hidden-print">
	<input type="submit" name="Submit" id="updateCustomMods" class="btn btn-primary" aria-describedby="helpBlock" value="Update Custom Modules" />
    <span id="helpBlock" class="help-block">Click "Update Custom Modules" <em>before</em> paging through records.</span>
</div>
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin&go=mods","default",$msg,$id); ?>">
<?php } ?>
</form>
    <?php } else echo "<p>No custom modules were found in the database.</p>";
}
if (($action == "add") || ($action == "edit")) { ?>

<form class="form-horizontal" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?action=<?php echo $action; ?>&amp;dbTable=<?php echo $mods_db_table; ?><?php if ($action == "edit") echo "&amp;id=".$id; ?>" name="form1">


<div class="form-group"><!-- Form Group REQUIRED Text Input -->
	<label for="mod_name" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Name</label>
	<div class="col-lg-3 col-md-4 col-sm-8 col-xs-12">
		<div class="input-group has-warning">
			<!-- Input Here -->
			<input class="form-control" id="mod_name" name="mod_name" type="text" value="<?php if ($action == "edit") echo $row_mods['mod_name']; ?>" placeholder="" autofocus>
			<span class="input-group-addon" id="mod_name-addon2"><span class="fa fa-star"></span></span>
		</div>
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group REQUIRED Text Input -->
	<label for="mod_filename" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">File Name</label>
	<div class="col-lg-3 col-md-4 col-sm-8 col-xs-12">
		<div class="input-group has-warning">
			<!-- Input Here -->
			<input class="form-control" id="mod_filename" name="mod_filename" type="text" value="<?php if ($action == "edit") echo $row_mods['mod_filename']; ?>" placeholder="your_file_name.php">
			<span class="input-group-addon" id="mod_filename-addon2"><span class="fa fa-star"></span></span>
		</div>
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT-REQUIRED Text Area -->
    <label for="mod_description" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Description</label>
    <div class="col-lg-4 col-md-4 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <textarea id="mod_description" class="form-control" name="mod_description" rows="8">
		<?php if ($action == "edit") echo $row_mods['mod_description']; ?>
        </textarea>
     </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
	<label for="mod_type" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Type</label>
	<div class="col-lg-3 col-md-3 col-sm-8 col-xs-12">
	<!-- Input Here -->
	<select class="selectpicker" name="mod_type" id="mod_type" data-width="auto">
		<option value="0" <?php if (($action == "edit") && ($row_mods['mod_type'] == "0")) echo " SELECTED"; ?>>Informational (Basic HTML)</option>
        <option value="1" <?php if (($action == "edit") && ($row_mods['mod_type'] == "1")) echo " SELECTED"; ?>>Report</option>
        <option value="2" <?php if (($action == "edit") && ($row_mods['mod_type'] == "2")) echo " SELECTED"; ?>>Export</option>
        <option value="3" <?php if (($action == "edit") && ($row_mods['mod_type'] == "3")) echo " SELECTED"; ?>>PHP Code or Function</option>
	</select>
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
	<label for="mod_permission" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Permission</label>
	<div class="col-lg-3 col-md-3 col-sm-8 col-xs-12">
	<!-- Input Here -->
	<select class="selectpicker" name="mod_permission" id="mod_permission" data-width="auto">
		<option value="0" <?php if (($action == "edit") && ($row_mods['mod_permission'] == "0")) echo " SELECTED"; ?>>Top Level Admins</option>
    	<option value="1" <?php if (($action == "edit") && ($row_mods['mod_permission'] == "1")) echo " SELECTED"; ?>>Admins</option>
        <option value="2" <?php if (($action == "edit") && ($row_mods['mod_permission'] == "2")) echo " SELECTED"; ?>>All Users</option>
	</select>
	<span id="helpBlock" class="help-block">Who has permission to view or access?</span>
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
	<label for="mod_extend_function" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Extends Core Function</label>
	<div class="col-lg-3 col-md-3 col-sm-8 col-xs-12">
	<!-- Input Here -->
	<select class="selectpicker" name="mod_extend_function" id="mod_extend_function" data-width="auto">
		<option rel="none" value="0" <?php if (($action == "edit") && ($row_mods['mod_extend_function'] == "0")) echo " SELECTED"; ?>>All</option>
		<option rel="none" value="1" <?php if (($action == "edit") && ($row_mods['mod_extend_function'] == "1")) echo " SELECTED"; ?>>Home Page</option>
		<option rel="none" value="2" <?php if (($action == "edit") && ($row_mods['mod_extend_function'] == "2")) echo " SELECTED"; ?>>Rules</option>
		<option rel="none" value="3" <?php if (($action == "edit") && ($row_mods['mod_extend_function'] == "3")) echo " SELECTED"; ?>>Volunteer Info</option>
		<option rel="none" value="4" <?php if (($action == "edit") && ($row_mods['mod_extend_function'] == "4")) echo " SELECTED"; ?>>Sponsors</option>
		<option rel="none" value="5" <?php if (($action == "edit") && ($row_mods['mod_extend_function'] == "5")) echo " SELECTED"; ?>>Contact</option>
		<option rel="none" value="6" <?php if (($action == "edit") && ($row_mods['mod_extend_function'] == "6")) echo " SELECTED"; ?>>Registration</option>
		<option rel="none" value="7" <?php if (($action == "edit") && ($row_mods['mod_extend_function'] == "7")) echo " SELECTED"; ?>>Payment</option>
		<option rel="none" value="8" <?php if (($action == "edit") && ($row_mods['mod_extend_function'] == "8")) echo " SELECTED"; ?>>User's Account</option>
        <option rel="none" value="10" <?php if (($action == "edit") && ($row_mods['mod_extend_function'] == "10")) echo " SELECTED"; ?>>Info</option>
        <option rel="none" value="11" <?php if (($action == "edit") && ($row_mods['mod_extend_function'] == "11")) echo " SELECTED"; ?>>Results</option>
		<option rel="admin" value="9" <?php if (($action == "edit") && ($row_mods['mod_extend_function'] == "9")) echo " SELECTED"; ?>>Administration</option>
	</select>
	<span id="helpBlock" class="help-block">Where should the module be placed?</span>
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
	<label for="mod_extend_function_admin" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Extends Admin Function</label>
	<div class="col-lg-3 col-md-3 col-sm-8 col-xs-12">
	<!-- Input Here -->
	<select class="selectpicker" name="mod_extend_function_admin" id="mod_extend_function_admin" data-size="10" data-width="auto">
		<option value=""></option>
		<option value="default" <?php if (($action == "edit") && ($row_mods['mod_extend_function_admin'] == "default")) echo " SELECTED"; ?>>Administration Dashboard</option>
        <option value="archives" <?php if (($action == "edit") && ($row_mods['mod_extend_function_admin'] == "archives")) echo " SELECTED"; ?>>Archives</option>
    	<option value="entries" <?php if (($action == "edit") && ($row_mods['mod_extend_function_admin'] == "entries")) echo " SELECTED"; ?>>Entry Administration</option>
        <option value="judging_scores" <?php if (($action == "edit") && ($row_mods['mod_extend_function_admin'] == "judging_scores")) echo " SELECTED"; ?>>Scoring</option>
        <option value="judging_scores_bos" <?php if (($action == "edit") && ($row_mods['mod_extend_function_admin'] == "judging_scores_bos")) echo " SELECTED"; ?>>Scoring - Best of Show</option>
        <option value="special_best" <?php if (($action == "edit") && ($row_mods['mod_extend_function_admin'] == "special_best")) echo " SELECTED"; ?>>Scoring - Special Best of Show Categories</option>
        <option value="styles" <?php if (($action == "edit") && ($row_mods['mod_extend_function_admin'] == "styles")) echo " SELECTED"; ?>>Styles</option>
        <option value="style_types" <?php if (($action == "edit") && ($row_mods['mod_extend_function_admin'] == "style_types")) echo " SELECTED"; ?>>Style Types</option>
    	<option value="judging_tables" <?php if (($action == "edit") && ($row_mods['mod_extend_function_admin'] == "judging_tables")) echo " SELECTED"; ?>>Table Administration</option>
    	<option value="participants" <?php if (($action == "edit") && ($row_mods['mod_extend_function_admin'] == "participants")) echo " SELECTED"; ?>>Users (Participants)</option>
	</select>
	<span id="helpBlock" class="help-block">What Admin function will the module extend?</span>
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
	<label for="mod_rank" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Rank</label>
	<div class="col-lg-3 col-md-3 col-sm-8 col-xs-12">
	<!-- Input Here -->
	<select class="selectpicker" name="mod_rank" id="mod_rank" data-size="10" data-width="auto">
		<?php for ($i=1; $i <= 25; $i++) { ?>
    	<option value="<?php echo $i; ?>" <?php if (($action == "edit") && ($row_mods['mod_rank'] == $i)) echo " SELECTED"; ?>><?php echo $i; ?></option>
		<?php } ?>
	</select>
	<span id="helpBlock" class="help-block">Determines custom module&rsquo;s rank in the display order. The lower the number, the higher priority.</span>
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group NOT REQUIRED Select -->
	<label for="mod_display_rank" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Display Order</label>
	<div class="col-lg-3 col-md-3 col-sm-8 col-xs-12">
	<!-- Input Here -->
	<select class="selectpicker" name="mod_display_rank" id="mod_display_rank" data-width="auto">
		<option value="0" <?php if (($action == "edit") && ($row_mods['mod_display_rank'] == "0")) echo " SELECTED"; ?>>N/A (Stand Alone)</option>
    	<option value="1" <?php if (($action == "edit") && ($row_mods['mod_display_rank'] == "1")) echo " SELECTED"; ?>>Before Public Core Content</option>
        <option value="2" <?php if (($action == "edit") && ($row_mods['mod_display_rank'] == "2")) echo " SELECTED"; ?>>After Public Core Content</option>
        <option value="3" <?php if (($action == "edit") && ($row_mods['mod_display_rank'] == "3")) echo " SELECTED"; ?>>Before Public Sidebar Content</option>
        <option value="4" <?php if (($action == "edit") && ($row_mods['mod_display_rank'] == "4")) echo " SELECTED"; ?>>After Public Sidebar Content</option>
	</select>
	<span id="helpBlock" class="help-block">If informational, where will the module's contents be displayed?</span>
	</div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="mod_enable" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Enable?</label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="mod_enable" value="1" id="mod_enable_0"  <?php if ($row_mods['mod_enable'] == 1) echo "CHECKED"; ?> /> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="mod_enable" value="0" id="mod_enable_1" <?php if ($row_mods['mod_enable'] == 0) echo "CHECKED"; ?>/> No
            </label>
        </div>
    </div>
</div><!-- ./Form Group -->

<div class="bcoem-admin-element hidden-print">
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-9">
			<input type="submit" name="Submit" id="setCustomModule" class="btn btn-primary" value="<?php if ($action == "edit") echo "Edit"; else echo "Add"; ?> Custom Module" />
		</div>
	</div>
</div>
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin&go=mods","default",$msg,$id); ?>">
<?php } ?>
</form>
<?php } ?>