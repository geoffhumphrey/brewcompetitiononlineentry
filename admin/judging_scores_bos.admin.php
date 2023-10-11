<?php
if ($dbTable == "default") {
    $pro_edition = $_SESSION['prefsProEdition'];
    $suffix = "default";
}
else {
    $pro_edition = $row_archive_prefs['archiveProEdition'];
    $suffix = get_suffix($dbTable);
}

if ($pro_edition == 0) $edition = $label_amateur." ".$label_edition;
if ($pro_edition == 1) $edition = $label_pro." ".$label_edition;
if ($dbTable == "default") $style_display_method = 0; else $style_display_method = 3;
?>
<script>
$(document).ready(function () {
    disable_update_button('judging_scores_bos');
});
</script>
<script src="<?php echo $js_url; ?>admin_ajax.min.js"></script>
<!-- Modal -->
<div class="modal fade" id="noDupeModal" tabindex="-1" role="dialog" aria-labelledby="noDupeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="noDupeModalLabel">Place Previously Selected</h4>
      </div>
      <div class="modal-body">
      The place you specified has already been input. Please choose another place or no place (blank).
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<p class="lead"><?php echo $_SESSION['contestName'];
if ($action == "enter") echo ": Add or Update BOS Places for ".$row_style_type['styleTypeName']; else echo ": Best of Show (BOS) Entries and Places";
if ($dbTable != "default") echo " (Archive ".$suffix.")";
?></p>
<?php if ($dbTable != "default") { ?>
<p><?php echo $edition; ?></p>
<?php } ?>
<div class="bcoem-admin-element hidden-print">
	<?php if  ($dbTable != "default") { ?>
    <!-- Postion 1: View All Button -->
    <div class="btn-group" role="group" aria-label="...">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=archive"><span class="fa fa-arrow-circle-left"></span> Archives</a>
    </div><!-- ./button group -->
    <?php } ?>
    <?php if ($dbTable == "default") { ?>
		
        <?php if ($action == "enter") { ?>
        <!-- Postion 1: View All Button -->
        <div class="btn-group" role="group" aria-label="...">
            <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores_bos"><span class="fa fa-arrow-circle-left"></span> All BOS Entries and Places</a>
        </div><!-- ./button group -->
        <?php } ?>

    	<!-- Postion 1: View All Button -->
        <div class="btn-group" role="group" aria-label="...">
            <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores"><span class="fa fa-arrow-circle-left"></span> All Scores</a>
        </div><!-- ./button group -->

        <!-- Postion 1: View All Button -->
        <div class="btn-group" role="group" aria-label="...">
            <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables"><span class="fa fa-arrow-circle-left"></span> All Tables</a>
        </div><!-- ./button group -->

        <?php if (($action == "default") && ($totalRows_style_type > 0)) { ?>
        <!-- Position 2: Enter/Edit Dropdown Button Group -->
        <div class="btn-group" role="group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="fa fa-plus-circle"></span> Add or Update...
                <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">

                <?php do {
                    if ($row_style_types_2['styleTypeBOS'] == "Y") { ?>
                    <li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores_bos&amp;action=enter&amp;filter=<?php echo $row_style_types_2['id'] ?>">BOS Places for <?php echo $row_style_types_2['styleTypeName']; ?></a>
                <?php
                    }
                } while ($row_style_types_2 = mysqli_fetch_assoc($style_types_2));
                ?>
                </ul>
            </div>
        <?php } ?>
    	<!-- Postion 4: Print Button Dropdown Group -->
        <div class="btn-group hidden-xs hidden-sm" role="group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="fa fa-print"></span> Print...
            <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <?php do {
                if ($row_style_type['styleTypeBOS'] == "Y") { ?>
                    <li class="small"><a id="modal_window_link" class="hide-loader menuItem" href="<?php echo $base_url; ?>includes/output.inc.php?section=pullsheets&amp;go=judging_scores_bos&amp;id=<?php echo $row_style_type['id']; ?>"  title="Print the <?php echo $row_style_type['styleTypeName']; ?> BOS Pullsheet">BOS Pullsheet for <?php echo $row_style_type['styleTypeName']; ?></a></li>
            <?php }
                } while ($row_style_type = mysqli_fetch_assoc($style_type));
                ?>
                <li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>includes/output.inc.php?section=bos-mat" title="Print BOS Cup Mats">BOS Cup Mats (Judging Numbers)</a></li>
                <li class="small"><a id="modal_window_link" class="hide-loader" href="<?php echo $base_url; ?>includes/output.inc.php?section=bos-mat&amp;filter=entry" title="Print BOS Cup Mats">BOS Cup Mats (Entry Numbers)</a></li>
            </ul>
    	</div>
    <?php } ?>
</div>
<?php
if (($action == "default") && ($totalRows_style_type > 0)) {
    do { $a[] = $row_style_types['id']; } while ($row_style_types = mysqli_fetch_assoc($style_types));
    foreach ($a as $type) {
    	$style_type_info = style_type_info($type,$suffix);
    	$style_type_info = explode("^",$style_type_info);
        if ($style_type_info[0] == "Y") {
            include (DB.'admin_judging_scores_bos.db.php');
?>
<a name="<?php echo $type; ?>"></a><h3>BOS Entries and Places for <?php echo $style_type_info[2]; ?></h3>
<?php if ($totalRows_bos > 0) { ?>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
    $('#sortable<?php echo $type; ?>').dataTable( {
    	"bPaginate" : false,
    	"sPaginationType" : "full_numbers",
    	"bLengthChange" : true,
        "iDisplayLength" :  <?php echo round($_SESSION['prefsRecordPaging']); ?>,
    	"sDom": 'rt',
    	"bStateSave" : false,
    	"aaSorting": [[2,'asc'],[6,'asc'],[7,'asc']],
    	"bProcessing" : true,
    	"aoColumns": [
    		null,
    		null,
    		null,
    		null,
    		null,
    		null,
    		null,
            null,
    		null
    	]
	});
});
</script>
<table class="table table-striped table-bordered table-responsive" id="sortable<?php echo $type; ?>">
<thead>
	<tr>
    	<th nowrap>Entry</th>
        <th nowrap>Judging</th>
        <th nowrap>Table</th>
        <th class="hidden-xs hidden-sm">Table Name</th>
        <th>Style</th>
        <?php if ($dbTable == "default") { ?>
    	<th class="hidden-xs hidden-sm">Table Score</th>
        <th class="hidden-xs hidden-sm">Table Place</th>
        <?php } ?>
        <?php if ($dbTable != "default") { ?>
        <th><?php if ($pro_edition == 1) echo $label_organization; else echo $label_brewer; ?></th>
        <th>Entry Name</th>
        <?php } ?>
        <th>BOS Score</th>
        <th>BOS Place</th>
    </tr>
</thead>
<tbody>
	<?php
    do {
    	$bos_entry_info = bos_entry_info($row_bos['eid'], $row_bos['scoreTable'],$filter);
    	$bos_entry_info = explode("^",$bos_entry_info);
        $style = style_number_const($bos_entry_info[1],$bos_entry_info[3],$_SESSION['style_set_display_separator'],$style_display_method);
        $judging_number = sprintf("%06s",$bos_entry_info[6]);
        if (empty($style)) $style_name = $bos_entry_info[0];
        else $style_name = $style.": ".$bos_entry_info[0];
	?>
	<tr>
    	<td nowrap><?php echo sprintf("%06s",$row_bos['eid']); ?></td>
        <td><?php echo $judging_number; ?></td>
        <td><?php echo $bos_entry_info[9]; ?></td>
        <td class="hidden-xs hidden-sm"><?php echo $bos_entry_info[8]; ?></td>
        <td><?php echo $style_name; ?></td>
        <?php if ($dbTable == "default") { ?>
        <td class="hidden-xs hidden-sm"><?php echo $row_bos['scoreEntry']; ?></td>
        <td class="hidden-xs hidden-sm"><?php echo $row_bos['scorePlace']; ?></td>
        <?php } ?>
        <?php if ($dbTable != "default") { ?>
        <td><?php if ($pro_edition == 1) echo $bos_entry_info[16]; else echo $bos_entry_info[5].", ".$bos_entry_info[4]; ?></td>
        <td><?php echo $bos_entry_info[12]; ?></td>
        <?php } ?>
        <td><?php echo $bos_entry_info[11]; ?></td>
        <td><?php if ($bos_entry_info[10] == "5") echo "HM"; else echo $bos_entry_info[10]; ?></td>
    </tr>
    <?php } while ($row_bos = mysqli_fetch_assoc($bos)); ?>
</tbody>
</table>
<?php       } else echo "<p style='margin: 0 0 40px 0'>No entries are eligible.</p>";
        } // end if ($style_type_info[0] == "Y")
    } // end foreach
} // end if ($action == "default")
?>
<?php 
if ($action == "enter") {
include (DB.'admin_judging_scores_bos.db.php');
?>
<?php if ($totalRows_enter_bos > 0) { ?>
<form name="scores" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?action=<?php echo $action; ?>&amp;dbTable=<?php echo $judging_scores_bos_db_table; ?>">
<input type="hidden" name="token" value ="<?php if (isset($_SESSION['token'])) echo $_SESSION['token']; ?>">
<script type="text/javascript" language="javascript">
$(document).ready(function(){
    $('#sortable').dataTable({
    	"bPaginate" : false,
    	"sDom": 'rt',
    	"bStateSave" : false,
    	"bLengthChange" : false,
        <?php if ($dbTable == "default") { ?>
    	"aaSorting": [[2,'asc']],
        <?php } else { ?>
        "aaSorting": [[8,'asc']],
        <?php } ?>
    	"bProcessing" : false,
    	"aoColumns": [
    		null,
    		null,
    		null,
    		{ "asSorting": [  ] },
    		{ "asSorting": [  ] }
    	]
    });
});
</script>
<table class="table table-striped table-bordered table-responsive" id="sortable">
<thead>
	<tr>
    	<th nowrap>Entry</th>
        <th nowrap>Judging</th>
        <th>Style</th>
    	<th>Score</th>
        <th>Place</th>
    </tr>
</thead>
<tbody>
	<?php
	do {
		$bos_entry_info = bos_entry_info($row_enter_bos['eid'], "default","default");
		$bos_entry_info = explode("^",$bos_entry_info);
		$judging_number = sprintf("%06s",$bos_entry_info[6]);
        $style = style_number_const($bos_entry_info[1],$bos_entry_info[3],$_SESSION['style_set_display_separator'],$style_display_method);
        $judging_number = sprintf("%06s",$bos_entry_info[6]);
        if (!empty($style)) $style_name = $bos_entry_info[0];
        else $style_name = $style.": ".$bos_entry_info[0];
	?>
	<tr>
		<?php $eid = $row_enter_bos['eid']; ?>
        <input type="hidden" name="score_id[]" value="<?php echo $eid; ?>" />
        <input type="hidden" name="scorePrevious<?php echo $eid; ?>" value="<?php if (is_numeric($bos_entry_info[14])) echo "Y"; else echo $bos_entry_info[14]; ?>" />
        <input type="hidden" name="eid<?php echo $eid; ?>" value="<?php echo $eid; ?>" />
        <input type="hidden" name="bid<?php echo $eid; ?>" value="<?php echo $bos_entry_info[15]; ?>" />
        <input type="hidden" name="scoreType<?php echo $eid; ?>" value="<?php echo $filter; ?>" />
        <?php if (is_numeric($bos_entry_info[14])) { ?>
        <input type="hidden" name="id<?php echo $eid; ?>" value="<?php echo $bos_entry_info[14]; ?>" />
        <?php } ?>
        <td><?php echo sprintf("%06s",$row_enter_bos['eid']); ?></td>
        <td><?php echo $judging_number ?></td>
        <td><?php echo $style_name; ?></td>
    	<td>
            <div class="form-group" id="score-entry-bos-ajax-<?php echo $eid; ?>-scoreEntry-form-group">
            <input class="form-control" id="score-entry-bos-ajax-<?php echo $eid; ?>" type="number" pattern="\d{2}" maxlength="2" name="scoreEntry<?php echo $eid; ?>" size="6" maxlength="6" value="<?php echo $bos_entry_info[11]; ?>" onblur="save_column('<?php echo $base_url; ?>','scoreEntry','judging_scores_bos','<?php echo $row_enter_bos['eid']; ?>','<?php echo $bos_entry_info[15]; ?>','1','<?php echo $filter; ?>','default','score-entry-bos-ajax-<?php echo $eid; ?>','value')"  />
            </div>
            <span id="score-entry-bos-ajax-<?php echo $eid; ?>-scoreEntry-status"></span>
            <span id="score-entry-bos-ajax-<?php echo $eid; ?>-scoreEntry-status-msg"></span>
        </td>
        <td>
            <div class="form-group" id="score-place-bos-ajax-<?php echo $eid; ?>-scorePlace-form-group">
            <?php if ($_SESSION['prefsWinnerMethod'] == "0") { ?>
            <select class="form-control" id="score-place-bos-ajax-<?php echo $eid; ?>" name="scorePlace<?php echo $eid; ?>" onchange="select_place('<?php echo $base_url; ?>','scorePlace','judging_scores_bos','<?php echo $row_enter_bos['eid']; ?>','<?php echo $bos_entry_info[15]; ?>','1','<?php echo $filter; ?>','default','score-place-bos-ajax-<?php echo $eid; ?>')">
            <?php } else { ?>
            <select class="form-control" id="score-place-bos-ajax-<?php echo $eid; ?>" name="scorePlace<?php echo $eid; ?>" onchange="save_column('<?php echo $base_url; ?>','scorePlace','judging_scores_bos','<?php echo $row_enter_bos['eid']; ?>','<?php echo $bos_entry_info[15]; ?>','1','<?php echo $filter; ?>','default','score-place-bos-ajax-<?php echo $eid; ?>','value')">    
            <?php } ?>
                <option value=""></option>
                <?php for($i=1; $i<$_SESSION['jPrefsMaxBOS']+1; $i++) { ?>
                <option value="<?php echo $i; ?>" <?php if ($bos_entry_info[10] == $i) echo "selected"; ?>><?php echo text_number($i); ?></option>
                <?php } ?>
                <option value="5" <?php if ($bos_entry_info[10] == "5") echo "selected"; ?>><?php echo "Hon. Men."; ?></option>
            </select>
            </div>
            <span id="score-place-bos-ajax-<?php echo $eid; ?>-scorePlace-status"></span>
            <span id="score-place-bos-ajax-<?php echo $eid; ?>-scorePlace-status-msg"></span>
        </td>
	</tr>
<?php } while ($row_enter_bos = mysqli_fetch_assoc($enter_bos)); ?>
</tbody>
</table>
<div class="bcoem-admin-element hidden-print">
	<input type="submit" name="Submit" id="judging_scores_bos-submit" class="btn btn-primary" value="<?php if ($action == "enter") echo "Update BOS Places"; else echo "Add BOS Places"; ?>" />
    <span id="judging_scores_bos-update-button-enabled" class="help-block">Select "<?php if ($action == "edit") echo "Update BOS Places"; else echo "Add BOS Places"; ?>" <em>before</em> paging through records.</span>
    <span id="judging_scores_bos-update-button-disabled" class="help-block">The "<?php if ($action == "edit") echo "Update BOS Places"; else echo "Add BOS Places"; ?>" button has been disabled since data is being saved successfully as it is being entered.</span>
</div>
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin&go=judging_tables","default",$msg,$id); ?>">
<?php } ?>
</form>
<?php }
else echo "<p>There are no qualifying entries available.</p>";
}
?>