<?php
require(DB.'archive.db.php'); 

$table_header1 = $label_users;
$table_header2 = $label_participants;
$table_header3 = $label_entries;
$table_header4 = $label_admin_tables;
$table_header5 = $label_admin_scores;
$table_header6 = $label_admin_bos_acr;
$table_header7 = $label_actions;

?>
<p class="lead"><?php echo $_SESSION['contestName']." ".$label_admin_archives; ?></p>
<?php if (HOSTED) { ?>
<p><?php echo $archive_text_000; ?></p>
<p><?php echo $archive_text_001; ?></p>
<h3><?php echo $archive_text_002; ?></h3>
<div class="bcoem-admin-element hidden-print">
    <div class="btn-group" id="helpArchive1" role="group" aria-label="...">
        <a class="btn btn-danger" href="<?php echo $base_url; ?>includes/process.inc.php?action=archive" data-confirm="<?php echo $archive_text_003; ?>"><span class="fa fa-exclamation-circle"></span> <?php echo $archive_text_004; ?></a>
    </div><!-- ./button group -->
    <span id="helpBlock" class="help-block"><?php echo $archive_text_005; ?></span>
</div>
<h3><?php echo $archive_text_006; ?></h3>
<div class="bcoem-admin-element hidden-print">
    <div class="btn-group" id="helpArchive1" role="group" aria-label="...">
        <a class="btn btn-danger" href="<?php echo $base_url; ?>includes/process.inc.php?action=archive&amp;filter=participant" data-confirm="<?php echo $archive_text_007; ?>"><span class="fa fa-exclamation-circle"></span> <?php echo $archive_text_008; ?></a>
    </div><!-- ./button group -->
    <span id="helpBlock" class="help-block"><?php echo $archive_text_009; ?></span>
</div>
<?php } else { ?>
<?php if ($action == "default") { ?>
<p><a href="<?php echo $base_url."index.php?section=admin&amp;go=archive&amp;action=add"; ?>" class="btn btn-primary">Archive Current Data</a></p>
<?php } ?>
<?php if ($action == "add") { ?>
<div class="btn-group" role="group" aria-label="...">
	<a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=archive"><span class="fa fa-arrow-circle-left"></span> Archives</a>
</div><!-- ./button group -->
<form data-toggle="validator" role="form" id="formfield" class="form-horizontal" action="<?php echo $base_url; ?>includes/process.inc.php?action=archive" method="post" name="form1">
<input type="hidden" name="action" value="add_form" /> 
<p><?php echo $archive_text_010; ?></p>
<!-- Form Group REQUIRED Text Input -->
<div class="form-group">
	<label for="mod_name" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_name; ?></label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<div class="input-group has-warning">
			<!-- Input Here -->
			<input class="form-control" id="archiveSuffix" name="archiveSuffix" type="text" placeholder="2015 or Q12016" pattern="^[a-zA-Z0-9]+$" autofocus required>
			<span class="input-group-addon" id="mod_name-addon2"><span class="fa fa-star"></span></span>
		</div>
		<span class="help-block with-errors"></span>
        <span id="helpBlock" class="help-block"><?php $archive_text_011; ?></span>
	</div>
</div><!-- ./Form Group -->
<p><?php echo $archive_text_014; ?></p>
<div class="form-group"><!-- Form Group Checkbox STACKED -->
    <label for="retain" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_data_retain; ?></label>
    <div class="col-lg-6 col-md-4 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
			<div class="checkbox">
                <label>
                    <input type="checkbox" name="none" id="retain_7" value="" > <?php echo $label_none; ?>
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="keepSpecialBest" id="retain_5" value="Y"> <?php echo $label_admin_custom_cat; ?>
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="keepStyleTypes" id="retain_4" value="Y"> <?php echo $label_custom_style_types; ?>
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="keepDropoff" id="retain_0" value="Y"> <?php echo $label_drop_offs; ?>
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="keepLocations" id="retain_1" value="Y"> <?php echo $label_judging_loc; ?>
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="keepParticipants" id="retain_2" value="Y"> <?php echo $label_participants; ?>
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="keepSponsors" id="retain_3" value="Y"> <?php echo $label_sponsors; ?>
                </label>
            </div>
        </div>
        <span id="helpBlock" class="help-block"><?php echo $archive_text_012; ?></span>
    </div>
</div><!-- ./Form Group -->
<div class="bcoem-admin-element hidden-print">
	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
			<input type="button" name="Submit" id="submitBtn" data-toggle="modal" data-target="#confirm-submit" class="btn btn-primary" value="Archive Now" />
		</div>
	</div>
</div>
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin&go=archive","default",$msg,$id); ?>">
<?php } ?>
</form>

<!-- Form submit confirmation modal -->
<!-- Refer to bcoem_custom.js for configuration -->
<div class="modal fade" id="confirm-submit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo $label_please_confirm; ?></h4>
            </div>
            <div class="modal-body">
                <?php echo $archive_text_013." ".$label_undone; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo $label_cancel; ?></button>
                <a href="#" id="submit" class="btn btn-success success"><?php echo $label_yes; ?></a>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<?php if ($action == "default") { ?>
<?php if ($totalRows_archive > 0) { ?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : true,
			"sPaginationType" : "full_numbers",
			"bLengthChange" : false,
			"iDisplayLength" :  <?php echo round($_SESSION['prefsRecordPaging']); ?>,
			"sDom": 'rtp',
			"bStateSave" : false,
			"aaSorting": [[0,'asc']],
			"bProcessing" : true,
			"aoColumns": [
				null,
				null,
				null,
				null,
				null,
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
<h3><?php echo $label_admin_archives; ?></h3>
<table class="table table-responsive table-striped table-bordered" id="sortable">
<thead>
    <th><?php echo $table_header2; ?></th>
    <th><?php echo $table_header3; ?></th>
    <th><?php echo $table_header4; ?></th>
    <th><?php echo $table_header5; ?></th>
    <th><?php echo $table_header6; ?></th>
    <th><?php echo $table_header7; ?></th>
</thead>
<tbody>
  <?php do { ?>
  <tr>
    <td>
    <?php 
	$db = $prefix."brewer_".$row_archive['archiveSuffix'];
	$count = get_archive_count($db);
	if ($count > 0) { ?>
    	<a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants&amp;dbTable=<?php echo $db;  ?>"><?php echo $row_archive['archiveSuffix']; ?></a> (<?php echo $count; ?>)
    <?php } else echo $row_archive['archiveSuffix']. " - Not Archived"; 
	?>
    </td>
    <td>
    <?php 
	$db = $prefix."brewing_".$row_archive['archiveSuffix'];
	$count = get_archive_count($db);
	if ($count > 0) { ?>
    	<a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries&amp;dbTable=<?php echo $db; ?>"><?php echo $row_archive['archiveSuffix']; ?></a> (<?php echo $count; ?>)
    <?php }  else echo $row_archive['archiveSuffix']. " - Not Archived";  
	?>
    </td>
    <td>
    <?php 
	$db = $prefix."judging_tables_".$row_archive['archiveSuffix'];
	if (table_exists($db)) {
	$count = get_archive_count($db);
	if ($count > 0) { ?>
    	<a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables&amp;dbTable=<?php echo $db; ?>"><?php echo $row_archive['archiveSuffix']; ?></a> (<?php echo $count; ?>)
    <?php } 
	} else echo $row_archive['archiveSuffix']. " - Not Archived"; 
	?>
    </td>
    <td>
    <?php 
	$db = $prefix."judging_scores_".$row_archive['archiveSuffix'];
	if (table_exists($db)) {
	if (get_archive_count($db) > 0) { ?>
    	<a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores&amp;dbTable=<?php echo $db."&amp;filter=".$row_archive['archiveSuffix']; ?>"><?php echo $row_archive['archiveSuffix']; ?></a>
    <?php } 
	} else echo $row_archive['archiveSuffix']. " - Not Archived"; 
	?>
    </td>
    <td>
    <?php 
	$db = $prefix."judging_scores_bos_".$row_archive['archiveSuffix'];
	if (table_exists($db)) {
	if (get_archive_count($db) > 0) { ?>
    	<a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores_bos&amp;dbTable=<?php echo $db."&amp;filter=".$row_archive['archiveSuffix']; ?>"><?php echo $row_archive['archiveSuffix']; ?></a>
    </td>
    <?php } 
	} else echo $row_archive['archiveSuffix']. " - Not Archived"; 
	?>
    <td><a href="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo $section; ?>&amp;go=<?php echo $go; ?>&amp;filter=<?php echo $row_archive['archiveSuffix']; ?>&amp;dbTable=<?php echo $archive_db_table; ?>&amp;action=delete&amp;id=<?php echo $row_archive['id']; ?>" data-toggle="tooltip" data-placement="top" title=" <?php echo $label_delete." ".$row_archive['archiveSuffix']; ?>" data-confirm="<?php echo $archive_text_015." ".$row_archive['archiveSuffix']; ?>"><span class="fa fa-lg fa-trash-o"></span></a></td>
  </tr>
  <?php } while ($row_archive = mysqli_fetch_assoc($archive)); ?>
</tbody>
</table>
<?php } else echo "<p>No archive data is present.</p>"; // end else ?>
<?php } ?>
<?php } ?>