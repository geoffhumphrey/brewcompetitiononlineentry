<?php require(DB.'archive.db.php'); 

$table_header1 = "Users";
$table_header2 = "Participants";
$table_header3 = "Entries";
$table_header4 = "Tables";
$table_header5 = "Scores";
$table_header6 = "BOS";
$table_header7 = "Actions";

?>
<p class="lead"><?php echo $_SESSION['contestName']; ?> Archives</p>
<?php if (HOSTED) { ?>
<p>Due to server storage limitations, archiving of hosted BCOE&amp;M accounts is not available. To utilize the software for a new competition or simply to clear the database of data, use the links below.</p>
<p>Custom category, custom style type, drop-off location, judging location, and sponsor data <strong class="text-success">will not be purged</strong>. Admins will need to update these for future competition instances.</p>
<h3>Option 1</h3>
<div class="bcoem-admin-element hidden-print">
    <div class="btn-group" id="helpArchive1" role="group" aria-label="...">
        <a class="btn btn-danger" href="<?php echo $base_url; ?>includes/archive.inc.php" data-confirm="Are you sure you want to clear the current competition&rsquo;s data? This CANNOT be undone."><span class="fa fa-exclamation-circle"></span> Clear All Participant, Entry, Judging, and Scoring Data</a>
    </div><!-- ./button group -->
    <span id="helpBlock" class="help-block">This option clears all participant, entry, judging, and scoring data. Provides a clean slate.</span>
</div>
<h3>Option 2</h3>
<div class="bcoem-admin-element hidden-print">
    <div class="btn-group" id="helpArchive1" role="group" aria-label="...">
        <a class="btn btn-danger" href="<?php echo $base_url; ?>includes/archive.inc.php?filter=participant" data-confirm="Are you sure you want to clear the current competition&rsquo;s data? This CANNOT be undone."><span class="fa fa-exclamation-circle"></span> Clear Entry, Judging, and Scoring Data Only</a>
    </div><!-- ./button group -->
    <span id="helpBlock" class="help-block">This option clears all entry, judging, and scoring data, but retains the participant data. Useful if you want don't want to have participants create new account profiles.</span>
</div>
<?php } else { ?>
<form data-toggle="validator" role="form" id="formfield" class="form-horizontal" action="<?php echo $base_url; ?>includes/archive.inc.php" method="post" name="form1">
<input type="hidden" name="action" value="add_form" /> 
<p>To archive the current entry, table, scoring, and result data, provide a name of the archive.</p>
<!-- Form Group REQUIRED Text Input -->
<div class="form-group">
	<label for="mod_name" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Name</label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<div class="input-group has-warning">
			<!-- Input Here -->
			<input class="form-control" id="archiveSuffix" name="archiveSuffix" type="text" placeholder="2015 or Q12016, etc." pattern="^[a-zA-Z0-9]+$" autofocus required>
			<span class="input-group-addon" id="mod_name-addon2"><span class="fa fa-star"></span></span>
		</div>
		<span class="help-block with-errors"></span>
        <span id="helpBlock" class="help-block">Alpha numeric characters only - all others will be omitted.</span>
	</div>
</div><!-- ./Form Group -->
<div class="form-group"><!-- Form Group Checkbox STACKED -->
    <label for="retain" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Data to Retain</label>
    <div class="col-lg-6 col-md-4 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
			<div class="checkbox">
                <label>
                    <input type="checkbox" name="none" id="retain_7" value=""> None
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="keepSpecialBest" id="retain_5" value="Y"> Custom Categories
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="keepStyleTypes" id="retain_4" value="Y"> Custom Style Types
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="keepDropoff" id="retain_0" value="Y"> Drop-Off Locations
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="keepLocations" id="retain_1" value="Y"> Judging Locations
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="keepParticipants" id="retain_2" value="Y"> Participants
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="keepSponsors" id="retain_3" value="Y"> Sponsors
                </label>
            </div>
        </div>
        <span id="helpBlock" class="help-block">Check the information you would like to retain for use in future competition instances.</span>
    </div>
</div><!-- ./Form Group -->
<div class="bcoem-admin-element hidden-print">
	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
			<input type="button" name="Submit" id="submitBtn" data-toggle="modal" data-target="#confirm-submit" class="btn btn-primary" value="Archive Now" />
		</div>
	</div>
</div>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>

<!-- Form submit confirmation modal -->
<!-- Refer to bcoem_custom.js for configuration -->
<div class="modal fade" id="confirm-submit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Please Confirm</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to archive current data with the name <span id="archiveName"></span>? This cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <a href="#" id="submit" class="btn btn-success success">Yes</a>
            </div>
        </div>
    </div>
</div>

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


<h3>Archives</h3>
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
    <td><a href="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo $section; ?>&amp;go=<?php echo $go; ?>&amp;filter=<?php echo $row_archive['archiveSuffix']; ?>&amp;dbTable=<?php echo $archive_db_table; ?>&amp;action=delete&amp;id=<?php echo $row_archive['id']; ?>" data-toggle="tooltip" data-placement="top" title="Delete <?php echo $row_archive['archiveSuffix']; ?> Archive?" data-confirm="Are you sure you want to delete the archive called <?php echo $row_archive['archiveSuffix']; ?>? This cannot be undone."><span class="fa fa-trash-o"></span></a></td>
  </tr>
  <?php } while ($row_archive = mysql_fetch_assoc($archive)); ?>
</tbody>
</table>
<?php } // end else ?>
<?php } ?>