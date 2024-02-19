<script>
$(document).ready(function () {
    disable_update_button('judging_scores');
});
</script>
<script src="<?php echo $js_url; ?>admin_ajax.min.js"></script>
<?php
if ($dbTable == "default") $pro_edition = $_SESSION['prefsProEdition'];
else $pro_edition = $row_archive_prefs['archiveProEdition'];

if ($pro_edition == 0) $edition = $label_amateur." ".$label_edition;
if ($pro_edition == 1) $edition = $label_pro." ".$label_edition;

if ($dbTable == "default") $style_display_method = 0; else $style_display_method = 2;

$eval_db_table = FALSE;

if ($dbTable == "default") {
    if ($_SESSION['prefsEval'] == 1) {
        $eval_db_table = TRUE;
        $evals = eval_exits("default","default",$dbTable);
    }
    $style_set = $_SESSION['prefsStyleSet'];
}

else {
    
    $archive_suffix = get_suffix($dbTable);
    $style_set = $row_archive_prefs['archiveStyleSet'];
    $pro_edition = $row_archive_prefs['archiveProEdition'];

    if (check_setup($prefix."evaluation_".$archive_suffix,$database)) {
        $eval_db_table = TRUE;
        $evals = eval_exits("default","default",$prefix."evaluation_".$archive_suffix);
    }

} 

if ($_SESSION['prefsWinnerMethod'] == "0") { ?>
<!-- Modal -->
<div class="modal fade" id="noDupeModal" tabindex="-1" role="dialog" aria-labelledby="noDupeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="noDupeModalLabel">Place Previously Selected</h4>
      </div>
      <div class="modal-body">
      The place you specified has already been input for the table. Please choose another place or no place (blank).
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php } ?>
<p class="lead"><?php echo $_SESSION['contestName'];
if (($action == "edit") && ($id != "default")) echo ": Edit Scores for Table ".$row_tables_edit['tableNumber']." - ".$row_tables_edit['tableName'];
elseif (($action == "add") && ($id != "default")) echo ": Add Scores for Table ".$row_tables_edit['tableNumber']." - ".$row_tables_edit['tableName'];
else echo " Scores";
if ($dbTable != "default") echo ": All Scores (Archive ".get_suffix($dbTable).")";
$totalRows_entry_count = total_paid_received($go,0);
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
    <?php if ($action != "default") { ?>
    <!-- Postion 1: View All Button -->
    <div class="btn-group" role="group" aria-label="...">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores&amp;dbTable=<?php echo $dbTable; ?>"><span class="fa fa-arrow-circle-left"></span> All Scores</a>
    </div><!-- ./button group -->
    <?php } ?>

    <?php if ($dbTable == "default") { ?>
    <!-- Postion 1: View All Button -->
    <div class="btn-group" role="group" aria-label="...">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables&amp;dbTable=<?php echo $dbTable; ?>"><span class="fa fa-arrow-circle-left"></span> All Tables</a>
    </div><!-- ./button group -->
    <?php } ?>

    <?php if ($dbTable == "default") { ?>
    <!-- Postion 1: View All Button -->
    <div class="btn-group" role="group" aria-label="...">
        <a class="btn btn-default" href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores_bos"><span class="fa fa-eye"></span> View BOS Entries and Places</a>
    </div><!-- ./button group -->
    <?php } ?>

    <?php if (($action == "default") && ($totalRows_tables > 0)) { ?>
    <!-- Position 2: Enter/Edit Dropdown Button Group -->
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="fa fa-plus-circle"></span> Add or Update Scores For...
        <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <?php do {
                    $table_count_total = table_count_total($row_tables_edit_2['id']);
                ?>
                <li class="small"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores&amp;action=<?php if ($table_count_total > 0) echo "edit&amp;id=".$row_tables_edit_2['id']; else echo "add&amp;id=".$row_tables_edit_2['id']; ?>"><?php echo "Table ".$row_tables_edit_2['tableNumber'].": ".$row_tables_edit_2['tableName']; ?></a></li>
                <?php  } while ($row_tables_edit_2 = mysqli_fetch_assoc($tables_edit_2)); ?>
        </ul>
    </div>
    <?php } ?>
    <?php if ($id == "default") { ?>
    <!-- Postion 4: Print Button Dropdown Group -->
    <div class="btn-group hidden-xs hidden-sm" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="fa fa-print"></span> Print...
        <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <?php do {
            if ($row_style_type['styleTypeBOS'] == "Y") { ?>
                <li class="small"><a data-fancybox data-type="iframe" class="modal-window-link hide-loader menuItem" href="<?php echo $base_url; ?>includes/output.inc.php?section=pullsheets&amp;go=judging_scores_bos&amp;id=<?php echo $row_style_type['id']; ?>"  title="Print the <?php echo $row_style_type['styleTypeName']; ?> BOS Pullsheet">BOS Pullsheet for <?php echo $row_style_type['styleTypeName']; ?></a></li>
        <?php }
            } while ($row_style_type = mysqli_fetch_assoc($style_type));
            ?>
        </ul>
    </div>
    <?php } ?>
    <?php } ?>
</div>
<?php if ($action == "default") { ?>
<?php if ($_SESSION['prefsEval'] == 1) {
    if ($dbTable == "default") {
        echo "<div style=\"margin: 0 0 15px 0;\" class=\"btn-group hidden-print\" role=\"group\"><a class=\"btn btn-block btn-default\" href=\"".$base_url."index.php?section=evaluation&amp;go=default&amp;filter=default&amp;view=admin\"><span class=\"fa fa-chevron-circle-left\"></span> ".$label_admin.": ".$label_evaluations."</a></div>";
        include (EVALS.'import_scores.eval.php');
    }
}
?>
<?php if ($dbTable == "default") { ?>
<p id="score-entered-status-default">Scores have been entered for <?php echo $totalRows_scores; ?> of <?php echo $totalRows_entry_count; ?> entries marked as paid and received.</p>
<?php } // end if ($dbTable == "default")
} // end if ($action == "default") ?>
<?php if (($action == "default") && ($id == "default")) { ?>
<?php if ($totalRows_scores > 0) { ?>
<script type="text/javascript" language="javascript">
     $(document).ready(function() {
        $('#sortable').dataTable( {
            "bPaginate" : true,
            "sPaginationType" : "full_numbers",
            "bLengthChange" : true,
            "iDisplayLength" :  <?php echo round($_SESSION['prefsRecordPaging']); ?>,
            "sDom": 'frtp',
            "bStateSave" : false,
            <?php if ($filter == "category") { ?>
            "aaSorting": [[4,'asc'],[6,'asc'],[5,'desc']],
            <?php } elseif ($dbTable != "default") { ?>
            "aaSorting": [[2,'asc'],[8,'asc']],
            <?php } else { ?>
            "aaSorting": [[2,'asc'],[6,'asc'],[5,'desc']],
            <?php } ?>
            "bProcessing" : true,
            "aoColumns": [
                null,
                null,
                null,
                null,
                null,
                null,
                <?php if ($dbTable != "default") { ?>
                null,
                null,
                <?php } ?>
                null,
                { "asSorting": [  ] },
                { "asSorting": [  ] }
                ]
            } );
        } );
    </script>
<table class="table table-responsive table-bordered table-striped" id="sortable">
<thead>
    <tr>
        <th nowrap>Entry</th>
        <th nowrap>Judging</th>
        <th nowrap>Table</th>
        <th class="hidden-xs hidden-sm">Table Name</th>
        <th class="hidden-xs hidden-sm">Style</th>
        <?php if ($dbTable != "default") { ?>
        <th><?php if ($pro_edition == 1) echo $label_organization; else echo $label_brewer; ?></th>
        <th>Entry Name</th>
        <?php } ?>
        <th><?php echo $label_assigned_score; ?></th>
        <th>Place</th>
        <th>Mini-BOS?</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
<?php

    do {

    $table_score_data = table_score_data($row_scores['eid'],$row_scores['scoreTable'],$filter);
    $table_score_data = explode("^",$table_score_data);

    $entry_number = sprintf("%06s",$table_score_data[0]);
    $judging_number = sprintf("%06s",$table_score_data[6]);

    if ($row_scores['scorePlace'] == "5") $score_place = "HM";
    elseif ($row_scores['scorePlace'] == "6") $score_place =  "Admin Adv.";
    elseif ($row_scores['scorePlace'] == "") $score_place = "<span style=\"display:none\">N/A</span>";
    else $score_place =  $row_scores['scorePlace'];

    if ($row_scores['scoreMiniBOS'] == "1") $mini_bos = "<span class=\"fa fa-lg fa-check text-success\"></span>";
    else $mini_bos = "&nbsp;";

    
    $style_display_number = style_number_const($table_score_data[8],$table_score_data[15],$_SESSION['style_set_display_separator'],$style_display_method);
    /*
    if ($dbTable == "default") $entry_category = $style_display_number.": ".style_convert($table_score_data[8],1,$base_url,$filter).": ".$table_score_data[13];
    else 
    */
    if (empty($style_display_number)) $entry_category = $table_score_data[13];
    else $entry_category = $style_display_number.": ".$table_score_data[13];

    $scoresheet = FALSE;
    $scoresheet_eval = FALSE;
    $scoresheet_entry = FALSE;
    $scoresheet_judging = FALSE;
    $entry_actions = "";

    if ($eval_db_table) {

        if (in_array($row_scores['eid'], $evals)) {
            
            $view_link = $base_url."includes/output.inc.php?section=evaluation&amp;go=default&amp;view=all&amp;id=".$row_scores['eid']."&amp;tb=1";
            if ($dbTable != "default") $view_link .= "&amp;dbTable=".$prefix."evaluation_".$archive_suffix;
            $print_link = $base_url."includes/output.inc.php?section=evaluation&amp;go=default&amp;view=all&amp;id=".$row_scores['eid'];
            if ($dbTable != "default") $print_link .= "&amp;dbTable=".$prefix."evaluation_".$archive_suffix;

            $entry_actions .= "<a data-fancybox data-type=\"iframe\" class=\"modal-window-link hide-loader\" href=\"".$view_link."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"View the Judge Evaluations in the system for entry ".$entry_number."\"><span class=\"fa-stack\"><i class=\"fa fa-square fa-stack-2x\"></i><i class=\"fa fa-stack-1x fa-file-text fa-inverse\"></i></span></a> ";
            $entry_actions .= "<a data-fancybox data-type=\"iframe\" class=\"modal-window-link hide-loader\" href=\"".$print_link."\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Print the Judge Evaluations in the system for entry ".$entry_number."\"><i class=\"fa fa-lg fa-file-text\"></i></a> ";
        }

    }
    
    // Check whether scoresheet file exists, and, if so, provide link.
    $scoresheet_file_name_entry = sprintf("%06s",$entry_number).".pdf";
    $scoresheet_file_name_judging = strtolower($judging_number).".pdf"; // upon upload via the UI, filename is converted to lowercase

    if ($dbTable == "default") {
        $scoresheetfile_entry = USER_DOCS.$scoresheet_file_name_entry;
        $scoresheetfile_judging = USER_DOCS.$scoresheet_file_name_judging;
        $scoresheet_prefs = $_SESSION['prefsDisplaySpecial'];
    }

    else {
        $scoresheetfile_entry = USER_DOCS.DIRECTORY_SEPARATOR.get_suffix($dbTable).DIRECTORY_SEPARATOR.$scoresheet_file_name_entry;
        $scoresheetfile_judging = USER_DOCS.DIRECTORY_SEPARATOR.get_suffix($dbTable).DIRECTORY_SEPARATOR.$scoresheet_file_name_judging;
        $scoresheet_prefs = $row_archive_prefs['archiveScoresheet'];
    }

    if ((file_exists($scoresheetfile_entry)) && ($scoresheet_prefs == "E")) {
        $scoresheet = TRUE;
        $scoresheet_entry = TRUE;
    }

    elseif ((file_exists($scoresheetfile_judging)) && ($scoresheet_prefs == "J")) {
        $scoresheet = TRUE;
        $scoresheet_judging = TRUE;
    }

    $scoresheet_file_name_1 = "";
    $scoresheet_file_name_2 = "";
    $scoresheet_link_1 = "";
    $scoresheet_link_2 = "";

    if ($scoresheet_entry) $scoresheet_file_name_1 = $scoresheet_file_name_entry;
    if ($scoresheet_judging) $scoresheet_file_name_2 = $scoresheet_file_name_judging;

    if (($scoresheet) && ($action != "print")) {

        if ((!empty($scoresheet_file_name_1)) && ($scoresheet_entry)) {

            /**
             * The pseudo-random number and the corresponding name of the 
             * temporary file are defined each time. The temporary file is created
             * only when the user selects the icon to access the scoresheet.
             */

            $random_num_str_1 = random_generator(8,2);
            $random_file_name_1 = $random_num_str_1.".pdf";
            $scoresheet_random_file_relative_1 = "user_temp/".$random_file_name_1;
            $scoresheet_random_file_1 = USER_TEMP.$random_file_name_1;
            $scoresheet_random_file_html_1 = $base_url.$scoresheet_random_file_relative_1;
            $scoresheet_link_1 .= "<a target=\"_blank\" class=\"hide-loader\" href=\"".$base_url."includes/output.inc.php?section=scoresheet";

            $scoresheet_link_1 .= "&amp;scoresheetfilename=".urlencode(obfuscateURL($scoresheet_file_name_1,$_SESSION['encryption_key']));
            $scoresheet_link_1 .= "&amp;randomfilename=".urlencode(obfuscateURL($random_file_name_1,$_SESSION['encryption_key']))."&amp;download=true";

            if ($dbTable != "default") $scoresheet_link_1 .= "&amp;view=".get_suffix($dbTable);
            $scoresheet_link_1 .= sprintf("\" data-toggle=\"tooltip\" title=\"%s '".$table_score_data[3]."'' (by Entry Number).\" data-download=\"true\">",$brewer_entries_text_006);
            $scoresheet_link_1 .= "<span class=\"fa fa-lg fa-file-pdf-o\"></a>&nbsp;&nbsp;";
        }

        if ((!empty($scoresheet_file_name_2)) && ($scoresheet_judging)) {

            /**
             * The pseudo-random number and the corresponding name of the 
             * temporary file are defined each time. The temporary file is created
             * only when the user selects the icon to access the scoresheet.
             */
            
            $random_num_str_2 = random_generator(8,2);
            $random_file_name_2 = $random_num_str_2.".pdf";
            $scoresheet_random_file_relative_2 = "user_temp/".$random_file_name_2;
            $scoresheet_random_file_2 = USER_TEMP.$random_file_name_2;
            $scoresheet_random_file_html_2 = $base_url.$scoresheet_random_file_relative_2;

            $scoresheet_link_2 .= "<a target=\"_blank\" class=\"hide-loader\" href=\"".$base_url."includes/output.inc.php?section=scoresheet";

            $scoresheet_link_2 .= "&amp;scoresheetfilename=".urlencode(obfuscateURL($scoresheet_file_name_2,$_SESSION['encryption_key']));
            $scoresheet_link_2 .= "&amp;randomfilename=".urlencode(obfuscateURL($random_file_name_2,$_SESSION['encryption_key']))."&amp;download=true";
            if ($dbTable != "default") $scoresheet_link_2 .= "&amp;view=".get_suffix($dbTable);
            $scoresheet_link_2 .= sprintf("\" data-toggle=\"tooltip\" title=\"%s '".$table_score_data[3]."' (by Judging Number).\" data-download=\"true\">",$brewer_entries_text_006);
            $scoresheet_link_2 .= "<span class=\"fa fa-lg fa-file-pdf-o\"></a>&nbsp;&nbsp;";
        }

        /*

        // Clean up temporary scoresheets created for other brewers, when they are at least 1 minute old (just to avoid problems when two entrants try accessing their scoresheets at practically the same time, and clean up previously created scoresheets for the same brewer, regardless of how old they are.
        $tempfiles = array_diff(scandir(USER_TEMP), array('..', '.'));

        if (is_array($tempfiles)) {
            foreach ($tempfiles as $file) {
                if ((filectime(USER_TEMP.$file) < time() - 1*60) || ((strpos($file, $scoresheet_file_name_judging) !== FALSE))) {
                    unlink(USER_TEMP.$file);
                }

                if ((filectime(USER_TEMP.$file) < time() - 1*60) || ((strpos($file, $scoresheet_file_name_entry) !== FALSE))) {
                    unlink(USER_TEMP.$file);
                }
            }
        }

        */

        if ((($dbTable == "default") && ($_SESSION['prefsDisplaySpecial'] == "E")) || ($dbTable != "default")) $entry_actions .= $scoresheet_link_1;
        if ((($dbTable == "default") && ($_SESSION['prefsDisplaySpecial'] == "J")) || ($dbTable != "default")) $entry_actions .= $scoresheet_link_2;
    }
?>
    <tr>
        <td><?php echo $entry_number; ?></td>
        <td><?php echo $judging_number;  ?></td>
        <td><?php echo $table_score_data[11]; ?></td>
        <td class="hidden-xs hidden-sm"><?php echo $table_score_data[10]; ?></td>
        <td class="hidden-xs hidden-sm"><?php echo $entry_category; ?></td>

        <?php if ($dbTable != "default") { ?>
        <td><?php if ($pro_edition == 1) echo $table_score_data[14]; else echo $table_score_data[5].", ".$table_score_data[4]; ?></td>
        <td><?php echo $table_score_data[3]; ?></td>
        <?php } ?>
        <td><?php if (fmod($row_scores['scoreEntry'], 1) !== 0.00) echo number_format($row_scores['scoreEntry'],2); else echo $row_scores['scoreEntry']; ?></td>
        <td><?php echo $score_place; ?></td>
        <td><?php echo $mini_bos; ?></td>
        <td>
            <?php if ($dbTable == "default") { ?>
            <a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $table_score_data[9]; ?>" data-toggle="tooltip" data-placement="top" title="Edit the <?php echo $table_score_data[10]; ?> scores"><span class="fa fa-lg fa-pencil"></span></a>&nbsp;<a class="hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?action=delete&amp;go=<?php echo $go; ?>&amp;id=<?php echo $row_scores['id']; ?>" data-toggle="tooltip" data-placement="top" title="Delete this score for entry #<?php echo $row_scores['eid']; ?>" data-confirm="Are you sure? This will delete the score and/or place for this entry."><span class="fa fa-lg fa-trash-o"></span></a>
            <?php echo "&nbsp;";
            }
            echo $entry_actions; ?>
        </td>
    </tr>
    <?php
        //}
    } while ($row_scores = mysqli_fetch_assoc($scores)); ?>
</tbody>
</table>
<?php } // end if ($totalRows_scores > 0)
else echo "<p id=\"no-scores-entered\">No scores have been entered. If tables have been defined, use the &ldquo;Add or Update Scores for...&rdquo; menu above to add scores.</p>"; ?>
<?php } // end if (($action == "default") && ($id == "default")) ?>


<?php if ((($action == "add") || ($action == "edit")) && ($dbTable == "default")) {
if (NHC) echo "<div class='alert alert-danger'><span class=\"fa fa-exclamation-circle\"></span> A requirement for the NHC is to enter scores for <em>all</em> entries and the top three places for each BJCP category. For an entry to advance to the final round, it must be designated here as 1st, 2nd, or 3rd in its category and achieve a score of 30 or more.</div>";
?>
<?php if ($id != "default") { ?>
<form name="scores" method="post" action="<?php echo $base_url; ?>includes/process.inc.php?action=<?php echo $action; ?>&amp;dbTable=<?php echo $judging_scores_db_table; ?>&amp;id=<?php echo $id; ?>">
<input type="hidden" name="token" value ="<?php if (isset($_SESSION['token'])) echo $_SESSION['token']; ?>">

<script type="text/javascript" language="javascript">

/**
 * https://datatables.net/examples/plug-ins/dom_sort.html
 * Create an array with the values of all the input boxes in a column, parsed as numbers
 */

$.fn.dataTable.ext.order['dom-text-numeric'] = function (settings, col) {
    return this.api().column(col, {order:'index'}).nodes().map(function (td, i) {
        return $('input', td).val() * 1;
    });
}

/* Create an array with the values of all the select options in a column */
$.fn.dataTable.ext.order['dom-select'] = function (settings, col) {
    return this.api().column(col, {order:'index'}).nodes().map(function (td, i) {
        return $('select', td).val();
    });
}

$(document).ready(function() {
    $('#sortable').dataTable( {
        "bPaginate" : false,
        "sDom": 'rt',
        "bStateSave" : false,
        "bLengthChange" : false,
        "aaSorting": [[2,'asc'],[1,'asc']],
        "bProcessing" : false,
        "aoColumns": [
            null,
            null,
            null,
            { "asSorting": [  ] },
            { "orderDataType": "dom-text-numeric" },
            { "orderDataType": "dom-select" }
        ]
    } );
} );
</script>
<table class="table table-responsive table-striped table-bordered" id="sortable">
<thead>
    <tr>
        <th width="10%">Entry</th>
        <th width="10%">Judging</th>
        <th class="hidden-xs hidden-sm">Style</th>
        <th width="15%">Mini-BOS?</th>
        <th width="20%">Score</th>
        <th width="20%">Place</th>
    </tr>
</thead>
<tbody>
<?php
    $a = explode(",", $row_tables_edit['tableStyles']);

    foreach (array_unique($a) as $value) {

        $score_style_data = score_style_data($value);
        //echo $score_style_data."<br>";
        $score_style_data = explode("^",$score_style_data);

        include (DB.'admin_judging_scores.db.php');

        if ($row_entries) {
        
            $style = style_number_const($row_entries['brewCategorySort'],$row_entries['brewSubCategory'],$_SESSION['style_set_display_separator'],$style_display_method);

            do {

                if ($totalRows_entries > 0) {

                    $saving_random_num = random_generator(8,2);

                    if ($action == "edit") {
                        $score_entry_data = score_entry_data($row_entries['id']);
                        $score_entry_data = explode("^",$score_entry_data);
                    }

                    if (!empty($score_entry_data[3])) $score_previous = "Y";
                    elseif (!empty($score_entry_data[4])) $score_previous = "Y";
                    else $score_previous = "N";

                    $eid = $row_entries['id'];
                    $bid = $row_entries['brewBrewerID'];
                    $entry_number = sprintf("%06s",$row_entries['id']);
                    $judging_number = sprintf("%06s",$row_entries['brewJudgingNumber']);

                    if (empty($style)) $style_display = $score_style_data[2];
                    else $style_display = $style.": ".$score_style_data[2];

                    $scoreType = style_type($score_style_data[3],"1","bcoe");

    ?>
    <tr>
        <input type="hidden" name="score_id[]" value="<?php echo $eid; ?>" />
        <?php if ($action == "edit") { ?>
        <input type="hidden" name="scorePrevious<?php echo $eid; ?>" value="<?php echo $score_previous; ?>" />
        <?php } ?>
        <input type="hidden" name="eid<?php echo $eid; ?>" value="<?php echo $eid; ?>" />
        <input type="hidden" name="bid<?php echo $eid; ?>" value="<?php echo $bid; ?>" />
        <input type="hidden" name="scoreTable<?php echo $eid; ?>" value="<?php echo $id; ?>" />
        <input type="hidden" name="scoreType<?php echo $eid; ?>" value="<?php echo $scoreType; ?>" />
        <td><?php echo $entry_number; ?></td>
        <td><?php echo $judging_number; ?></td>
        <td class="hidden-xs hidden-sm"><?php echo $style_display; ?></td>
        <td>
            <div class="form-group" id="score-mini-bos-ajax-<?php echo $saving_random_num; ?>-scoreMiniBOS-form-group">
            <input type="checkbox" id="score-mini-bos-ajax-<?php echo $saving_random_num; ?>" name="scoreMiniBOS<?php echo $eid; ?>" value="1" onclick="$(this).attr('value', this.checked ? 1 : 0);save_column('<?php echo $base_url; ?>','scoreMiniBOS','judging_scores','<?php echo $eid; ?>','<?php echo $bid; ?>','<?php echo $id; ?>','<?php echo $scoreType; ?>','default','score-mini-bos-ajax-<?php echo $saving_random_num; ?>','value')" <?php if ((isset($score_entry_data[5])) && (($action == "edit") && ($score_entry_data[5] == "1"))) echo "CHECKED"; ?> />
            <span id="score-mini-bos-ajax-<?php echo $saving_random_num; ?>-scoreMiniBOS-status"></span>
            <span id="score-mini-bos-ajax-<?php echo $saving_random_num; ?>-scoreMiniBOS-status-msg"></span>
            </div>
        </td>
        <td>
            <div class="form-group" id="score-entry-ajax-<?php echo $saving_random_num; ?>-scoreEntry-form-group">
            <input class="form-control" id="score-entry-ajax-<?php echo $saving_random_num; ?>" type="number" pattern="\d{2}" maxlength="2" name="scoreEntry<?php echo $eid; ?>" size="6" maxlength="6" value="<?php if ($action == "edit") echo $score_entry_data[3]; ?>" onblur="save_column('<?php echo $base_url; ?>','scoreEntry','judging_scores','<?php echo $eid; ?>','<?php echo $bid; ?>','<?php echo $id; ?>','<?php echo $scoreType; ?>','default','score-entry-ajax-<?php echo $saving_random_num; ?>','value')" />
            </div>
            <span id="score-entry-ajax-<?php echo $saving_random_num; ?>-scoreEntry-status"></span>
            <span id="score-entry-ajax-<?php echo $saving_random_num; ?>-scoreEntry-status-msg"></span>
        </td>
        <td>
        <span class="hidden"><?php if ((isset($score_entry_data[4])) && (($action == "edit") && ($score_entry_data[4] == "1"))) echo $score_entry_data[4]; ?></span>
            <div class="form-group" id="score-place-ajax-<?php echo $saving_random_num; ?>-scorePlace-form-group">
            <?php if ($_SESSION['prefsWinnerMethod'] == "0") { ?>
            <select class="form-control nodupe" id="score-place-ajax-<?php echo $saving_random_num; ?>" name="scorePlace<?php echo $eid; ?>" onchange="select_place('<?php echo $base_url; ?>','scorePlace','judging_scores','<?php echo $eid; ?>','<?php echo $bid; ?>','<?php echo $id; ?>','<?php echo $scoreType; ?>','default','score-place-ajax-<?php echo $saving_random_num; ?>')">
            <?php } else { ?>
            <select class="form-control" id="score-place-ajax-<?php echo $saving_random_num; ?>" name="scorePlace<?php echo $eid; ?>" onchange="save_column('<?php echo $base_url; ?>','scorePlace','judging_scores','<?php echo $eid; ?>','<?php echo $bid; ?>','<?php echo $id; ?>','<?php echo $scoreType; ?>','default','score-place-ajax-<?php echo $saving_random_num; ?>','value')">    
            <?php } ?>
                <option value=""></option>
                  <option value="1" <?php if ((isset($score_entry_data[4])) && (($action == "edit") && ($score_entry_data[4] == "1"))) echo "SELECTED"; ?>>1st</option>
                  <option value="2" <?php if ((isset($score_entry_data[4])) && (($action == "edit") && ($score_entry_data[4] == "2"))) echo "SELECTED"; ?>>2nd</option>
                  <option value="3" <?php if ((isset($score_entry_data[4])) && (($action == "edit") && ($score_entry_data[4] == "3"))) echo "SELECTED"; ?>>3rd</option>
                  <?php if (!NHC) { ?>
                  <option value="4" <?php if ((isset($score_entry_data[4])) && (($action == "edit") && ($score_entry_data[4] == "4"))) echo "SELECTED"; ?>>4th</option>
                  <option value="5" <?php if ((isset($score_entry_data[4])) && (($action == "edit") && ($score_entry_data[4] == "5"))) echo "SELECTED"; ?>>Hon. Men.</option>
                  <?php } ?>
            </select>
            </div>
            <span id="score-place-ajax-<?php echo $saving_random_num; ?>-scorePlace-status"></span>
            <span id="score-place-ajax-<?php echo $saving_random_num; ?>-scorePlace-status-msg"></span>
        </td>
    </tr>
    <?php       }
            } while ($row_entries = mysqli_fetch_assoc($entries));
        } // end if ($row_entries)
    } // end foreach ?>
</tbody>
</table>
<div class="bcoem-admin-element hidden-print">
    <input type="submit" name="Submit" id="judging_scores-submit" class="btn btn-primary" aria-describedby="helpBlock" value="<?php if ($action == "edit") echo "Update Scores"; else echo "Add Scores"; ?>" disabled />
    <span id="judging_scores-update-button-enabled" class="help-block">Select "<?php if ($action == "edit") echo "Update Scores"; else echo "Add Scores"; ?>" <em>before</em> paging through records.</span>
    <span id="judging_scores-update-button-disabled" class="help-block">The "<?php if ($action == "edit") echo "Update Scores"; else echo "Add Scores"; ?>" button has been disabled since data is being saved successfully as it is being entered.</span>
</div>
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin&go=judging_tables","default",$msg,$id); ?>">
<?php } ?>
</form>

<?php } // end if ($id != "default") ?>
<?php } // end if ((($action == "add") || ($action == "edit")) && ($dbTable == "default")) ?>