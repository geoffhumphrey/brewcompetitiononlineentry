<?php
require (DB.'archive.db.php');
require (DB.'styles.db.php');

$table_header1 = $label_users;
$table_header2 = $label_participants;
$table_header3 = $label_entries;
$table_header4 = $label_admin_tables;
$table_header5 = $label_admin_scores;
$table_header6 = $label_admin_bos_acr;
$table_header7 = $label_actions;
$style_set_dropdown = "";
$results_data = FALSE;

if ($action == "edit") {
    $query_results_recorded = sprintf("SELECT COUNT(*) AS 'count' FROM %s;",$prefix."judging_scores_".$row_archive['archiveSuffix']);
    $results_recorded = mysqli_query($connection,$query_results_recorded) or die (mysqli_error($connection));
    $row_results_recorded = mysqli_fetch_assoc($results_recorded);
    if ($row_results_recorded['count'] > 0) $results_data = TRUE;
}

foreach ($style_sets as $style_set) {
    
    // Reset vars
    $style_set_selected = "";
    
    // Build style set drop-down
    if ((isset($row_archive['archiveStyleSet'])) && ($style_set['style_set_name'] == $row_archive['archiveStyleSet'])) $style_set_selected = "SELECTED";
    $style_set_dropdown .= sprintf("<option value=\"%s\" %s>%s (%s)</option>",$style_set['style_set_name'],$style_set_selected,$style_set['style_set_long_name'],$style_set['style_set_short_name']);

}

?>
<p class="lead"><?php echo $_SESSION['contestName']." ".$label_admin_archives; ?></p>
<?php if (HOSTED) { ?>
<p><?php echo $archive_text_000; ?></p>
<p><?php echo $archive_text_001; ?></p>
<h3><?php echo $archive_text_002; ?></h3>
<div class="bcoem-admin-element hidden-print">
    <div class="btn-group" id="helpArchive1" role="group" aria-label="...">
        <a class="btn btn-danger hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?action=archive" data-confirm="<?php echo $archive_text_003; ?>"><span class="fa fa-exclamation-circle"></span> <?php echo $archive_text_004; ?></a>
    </div><!-- ./button group -->
    <span id="helpBlock" class="help-block"><?php echo $archive_text_005; ?></span>
</div>
<h3><?php echo $archive_text_006; ?></h3>
<div class="bcoem-admin-element hidden-print">
    <div class="btn-group" id="helpArchive1" role="group" aria-label="...">
        <a class="btn btn-danger hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?action=archive&amp;filter=participant" data-confirm="<?php echo $archive_text_007; ?>"><span class="fa fa-exclamation-circle"></span> <?php echo $archive_text_008; ?></a>
    </div><!-- ./button group -->
    <span id="helpBlock" class="help-block"><?php echo $archive_text_009; ?></span>
</div>
<?php } else { ?>
<?php if ($action == "default") { ?>
<div class="bcoem-admin-element hidden-print">
<p><a href="<?php echo $base_url."index.php?section=admin&amp;go=archive&amp;action=add"; ?>" class="btn btn-primary">Archive Current Data</a></p>
</div>
<?php } ?>
<?php if (($action == "add") || ($action == "edit")) { ?>
<div class="bcoem-admin-element hidden-print">
    <div class="btn-group" role="group" aria-label="...">
	   <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=archive"><span class="fa fa-arrow-circle-left"></span> <?php echo $label_admin_archives; ?></a>
    </div><!-- ./button group -->
</div>
<form data-toggle="validator" role="form" id="formfield" class="form-horizontal" action="<?php echo $base_url; ?>includes/process.inc.php?action=archive&go=<?php echo $action; if ($action == "edit") echo "&filter=".$row_archive['archiveSuffix']."&id=".$id; ?>" method="post" name="form1">
<input type="hidden" name="token" value ="<?php if (isset($_SESSION['token'])) echo $_SESSION['token']; ?>">
<input type="hidden" name="action" value="add_form" />
<div class="bcoem-admin-element hidden-print">
<?php if ($action == "edit") echo "<p class=\"alert alert-warning\"><i class=\"fa fa-lg fa-exclamation-circle\"></i> ".$archive_text_017."</p>"; else echo "<p>".$archive_text_010."</p>"; ?>
</div>
<!-- Form Group REQUIRED Text Input -->
<div class="form-group">
	<label for="mod_name" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_name; ?></label>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
		<div class="input-group has-warning">
			<!-- Input Here -->
			<input class="form-control" id="archiveSuffix" name="archiveSuffix" type="text" placeholder="<?php echo date('Y'); ?> or Q2<?php echo date('Y'); ?>, etc." pattern="^[a-zA-Z0-9]+$" autofocus required value="<?php if ($action == "edit") echo $row_archive['archiveSuffix']; ?>">
			<span class="input-group-addon" id="mod_name-addon2"><span class="fa fa-star"></span></span>
		</div>
		<span class="help-block with-errors"></span>
        <span id="helpBlock" class="help-block"><?php echo $archive_text_011; ?></span>
	</div>
</div><!-- ./Form Group -->
<?php if ($action == "add") { ?>
<div class="bcoem-admin-element hidden-print">
<p><?php echo $archive_text_014; ?></p>
</div>
<div class="form-group"><!-- Form Group Checkbox STACKED -->
    <label for="retain" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_data_retain; ?></label>
    <div class="col-lg-6 col-md-4 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
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
        <div id="helpBlock" class="help-block" style="margin-top: 10px;"><?php echo $archive_text_012; ?></div>
    </div>
</div><!-- ./Form Group -->
<?php } ?>

<?php if ($action == "edit") { ?>
<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="archiveProEdition" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_admin_comp_type; ?></label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="archiveProEdition" value="0" id="archiveProEdition_0"  <?php if ($row_archive['archiveProEdition'] == "0") echo "CHECKED"; ?> /> Amateur
            </label>
            <label class="radio-inline">
                <input type="radio" name="archiveProEdition" value="1" id="archiveProEdition_1" <?php if ($row_archive['archiveProEdition'] == "1") echo "CHECKED"; ?>/> Professional
            </label>
        </div>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="archiveStyleSet" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_admin_styleset; ?></label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <!-- Input Here -->
        <select class="selectpicker" name="archiveStyleSet" id="archiveStyleSet" data-size="12" data-width="auto">
            <option value="">None Specified</option>
            <?php echo $style_set_dropdown; ?>
        </select>
    <?php if (!isset($row_archive['archiveStyleSet'])) { ?>
    <span id="helpBlock" class="help-block"><?php echo $archive_text_023; ?></span>
    <?php } ?>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group Radio INLINE -->
    <label for="archiveDisplayWinners" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_admin_winner_display; ?></label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="archiveDisplayWinners" value="Y" id="archiveDisplayWinners_0"  <?php if ($row_archive['archiveDisplayWinners'] == "Y") echo "CHECKED"; if (!$results_data) echo " DISABLED"; ?> /> <?php echo $label_admin_enable; ?>
            </label>
            <label class="radio-inline">
                <input type="radio" name="archiveDisplayWinners" value="N" id="archiveDisplayWinners_1" <?php if (($section != "step3") && (($row_archive['archiveDisplayWinners'] == "N") || (empty($row_archive['archiveDisplayWinners'])))) echo "CHECKED"; if (!$results_data) echo " DISABLED"; ?>/> <?php echo $label_admin_disable; ?>
            </label>
        </div>
        <span id="helpBlock" class="help-block"><?php if ($results_data) echo $archive_text_019; else echo $archive_text_022; ?></span>
    </div>
</div><!-- ./Form Group -->

<div class="form-group"><!-- Form Group Radio STACKED -->
    <label for="archiveWinnerMethod" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_admin_winner_dist; ?></label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <?php foreach ($results_method as $key => $value) { ?>
            <label class="radio-inline">
                <input type="radio" name="archiveWinnerMethod" value="<?php echo $key; ?>" id="archiveWinnerMethod_<?php echo $key; ?>" <?php if ($row_archive['archiveWinnerMethod'] == $key) echo "CHECKED"; if (!$results_data) echo " DISABLED"; ?>> <?php echo $value; ?>
            </label>
            <?php } ?>
            <span id="helpBlock" class="help-block"><?php if (!$results_data) echo $archive_text_022; else { echo $archive_text_020; ?> <a href="<?php echo build_public_url("past-winners",$row_archive['archiveSuffix'],"default","default",$sef,$base_url); ?>" target="_blank"><?php echo $label_view; ?> <i class="fa fa-external-link"></i></a>.<?php } ?></span>
        </div>
    </div>
</div><!-- ./Form Group -->
<?php if (file_exists(USER_DOCS.DIRECTORY_SEPARATOR.$row_archive['archiveSuffix'])) { ?>
<div class="form-group"><!-- Form Group Radio STACKED -->
    <label for="archiveScoresheet" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label"><?php echo $label_admin_scoresheet_names; ?></label>
    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
        <div class="input-group">
            <!-- Input Here -->
            <label class="radio-inline">
                <input type="radio" name="archiveScoresheet" value="J" id="archiveScoresheet_0" <?php if ($row_archive['archiveScoresheet'] == "J") echo "CHECKED"; ?>> <?php echo $label_six_char_judging; ?>
            </label>
            <label class="radio-inline">
                <input type="radio" name="archiveScoresheet" value="E" id="archiveScoresheet_1" <?php if ($row_archive['archiveScoresheet'] == "E") echo "CHECKED"; ?>> <?php echo $label_six_digit_entry; ?>
            </label>
        </div>
        <div class="help-block"><?php echo $archive_text_021; ?></div>
    </div>
</div><!-- ./Form Group -->
<?php } else { ?>
<input type="hidden" name="archiveScoresheet" value="<?php echo $row_archive['archiveScoresheet']; ?>">
<?php } ?>
<?php } ?>
<div class="bcoem-admin-element hidden-print">
	<div class="form-group">
		<div class="col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
            <?php if ($action == "edit") { ?>
            <input type="submit" name="Submit" class="btn btn-primary" value="<?php echo $label_edit." ".$label_admin_archive; ?>" />
            <?php } else { ?>
            <input type="button" name="Submit" id="submitBtn" data-toggle="modal" data-target="#confirm-submit" class="btn btn-primary" value="<?php echo $label_add." ".$label_admin_archive; ?>" />
            <?php } ?>
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
    <th width="12%"><?php echo $table_header2; ?></th>
    <th width="12%"><?php echo $label_sponsors; ?></th>
    <th width="12%"><?php echo $table_header3; ?></th>
    <th width="12%"><?php echo $table_header4; ?></th>
    <th width="12%"><?php echo $table_header5; ?></th>
    <th width="12%"><?php echo $table_header6; ?></th>
    <th width="12%" class="hidden-xs hidden-sm"><?php echo $label_admin_winner_display; ?> <a tabindex="0" type="button" role="button" data-toggle="popover" data-html="true" data-trigger="hover" data-placement="auto top" data-container="body" data-content="<?php echo $archive_text_019; if ($_SESSION['prefsProEdition'] == 0) { ?> Select the <span class='fa fa-lg fa-file-excel'></span> icon to download a CSV of winner data.<?php } ?>"><span class="fa fa-lg fa-question-circle"></span></th>
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
    <?php } else echo $row_archive['archiveSuffix']. " - ".$label_not_archived;	?>
    </td>
    <td>
    <?php
    $db = $prefix."sponsors_".$row_archive['archiveSuffix'];
    if (table_exists($db)) {
        $count = get_archive_count($db);
        if ($count > 0) { ?>
        <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=sponsors&amp;dbTable=<?php echo $db; ?>"><?php echo $row_archive['archiveSuffix']; ?></a> (<?php echo $count; ?>)
    <?php 
        } 
    } else echo $row_archive['archiveSuffix']. " - ".$label_not_archived;  ?>
    </td>
    <td>
    <?php
	$db = $prefix."brewing_".$row_archive['archiveSuffix'];
	$count = get_archive_count($db);
	if ($count > 0) { ?>
    	<a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries&amp;dbTable=<?php echo $db; ?>"><?php echo $row_archive['archiveSuffix']; ?></a> (<?php echo $count; ?>)<?php if (!isset($row_archive['archiveStyleSet'])) echo "*<br><em><small>".$archive_text_023."</small></em>"; ?>
    <?php } else echo $row_archive['archiveSuffix']. " - ".$label_not_archived; ?>
    </td>
    <td>
    <?php
	$db = $prefix."judging_tables_".$row_archive['archiveSuffix'];
	if (table_exists($db)) {
	$count = get_archive_count($db);
	   if ($count > 0) { ?>
    	<a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables&amp;dbTable=<?php echo $db; ?>"><?php echo $row_archive['archiveSuffix']; ?></a> (<?php echo $count; ?>)
    <?php 
        } else echo $row_archive['archiveSuffix']. " - ".$label_not_archived;
	}  ?>
    </td>
    <td>
    <?php
	$db = $prefix."judging_scores_".$row_archive['archiveSuffix'];
	if (table_exists($db)) {
	   if (get_archive_count($db) > 0) { ?>
    	<a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores&amp;dbTable=<?php echo $db."&amp;filter=".$row_archive['archiveSuffix']; ?>"><?php echo $row_archive['archiveSuffix']; ?></a>
    <?php 
        } else echo $row_archive['archiveSuffix']. " - ".$label_not_archived; 
	} ?>
    </td>
    <td>
    <?php
	$db = $prefix."judging_scores_bos_".$row_archive['archiveSuffix'];
	if (table_exists($db)) {
	   if (get_archive_count($db) > 0) { ?>
    	<a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores_bos&amp;dbTable=<?php echo $db."&amp;filter=".$row_archive['archiveSuffix']; ?>"><?php echo $row_archive['archiveSuffix']; ?></a>
    <?php 
        } else echo $row_archive['archiveSuffix']. " - ".$label_not_archived;
	}  ?>
    </td>
    <td class="hidden-xs hidden-sm">
        <?php 
        echo yes_no($row_archive['archiveDisplayWinners'],$base_url,1);
        if (($row_archive['archiveDisplayWinners'] == "Y") && ($_SESSION['prefsProEdition'] == 0)) {
        ?>
        &nbsp;<a target="_blank" data-toggle="tooltip" data-placement="top" title="Download a CSV of this archive's winner data." href="<?php echo $base_url; ?>output/export.output.php?section=entries&amp;go=csv&amp;filter=<?php echo $row_archive['archiveSuffix']; ?>&amp;tb=circuit&amp;sort=<?php echo $row_archive['archiveSuffix']; ?>" target="_blank"><span class="fa fa-lg fa-file-excel"></span></a>
        <?php } ?>
    </td>
    <td>
        <a href="<?php echo $base_url; ?>index.php?section=<?php echo $section; ?>&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_archive['id']; ?>"><span class="fa fa-lg fa-pencil"></span></a>&nbsp;
        <a class="hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?section=<?php echo $section; ?>&amp;go=<?php echo $go; ?>&amp;filter=<?php echo $row_archive['archiveSuffix']; ?>&amp;dbTable=<?php echo $archive_db_table; ?>&amp;action=delete&amp;id=<?php echo $row_archive['id']; ?>" data-toggle="tooltip" data-placement="top" title=" <?php echo $label_delete." ".$row_archive['archiveSuffix']; ?>" data-confirm="<?php echo $archive_text_015." ".$row_archive['archiveSuffix'].". ".$archive_text_016; ?>"><span class="fa fa-lg fa-trash-o"></span></a>
    </td>
  </tr>
  <?php } while ($row_archive = mysqli_fetch_assoc($archive)); ?>
</tbody>
</table>
<?php } else echo "<p>No archive data is present.</p>"; // end else ?>
<?php } ?>
<?php } ?>