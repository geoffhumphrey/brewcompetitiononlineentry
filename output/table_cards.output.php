<?php
$section = "table_cards";
include (DB.'admin_common.db.php');

$table_card_output = FALSE;

if ($totalRows_tables == 0) {
    echo "<h2>";
    echo sprintf("%s",$output_text_005);
    if ($go == "judging_locations") echo sprintf(" %s",$output_text_006);
    echo ".</h2>";
    echo sprintf("<p class=\"lead\">%s</p>",$output_text_007);
}

else $table_card_output = TRUE;

if (($table_card_output) && ($psort == "sorting-placards")) {

    include (DB.'styles.db.php');

    $table_card_output_print = "";
    $style_beer_count = array();
    $style_beer_count_logged = array();
    $style_mead_count = array();
    $style_mead_count_logged = array();
    $style_cider_count = array();
    $style_cider_count_logged = array();
    $style_mead_cider_count = array();
    $style_mead_cider_count_logged = array();

    foreach ($style_sets as $style_set_data) {
        
        if ($style_set_data['style_set_name'] === $_SESSION['prefsStyleSet']) {
            
            $style_set_cat = $style_set_data['style_set_categories'];
            
            foreach ($style_set_cat as $key => $value) {
                
                include (DB.'entries_by_style.db.php');
                
                if ($row_style_count_logged['count'] > 0) {

                    if (is_numeric($key)) $cat_number = sprintf('%02d', $key);
                    else $cat_number = $key;

                    if ($row_style_count_logged['count'] == 1) $display_entries = $label_entry;
                    else $display_entries = $label_entries;

                    $table_card_output_print .= "<div class=\"table_card\">";
                    $table_card_output_print .= "<h1>".$cat_number." - ".$value."</h1>";
                    $table_card_output_print .= "<h1><small>".$row_style_count_logged['count']." ".$display_entries."</small></h1>";
                    $table_card_output_print .= "</div>";
                    $table_card_output_print .= "<div style=\"page-break-after:always;\"></div>";

                } 
                
            }
            
        }

    }

    if (!empty($table_card_output_print)) echo $table_card_output_print;

}

if (($table_card_output) && ($psort == "sorting-tables")) { 

    $table_card_output_print = "";
    
    if ($view == "master-list") {
        $table_card_output_print .= "<h2>Tables and Associated Styles</h2>";
        $table_card_output_print .= "<h3>Master List</h3>";
        $table_card_output_print .= "<p><strong><sup>&#10029;</sup> Entry Count</strong> is <strong>prior</strong> to check-in in the system and may not reflect the true count of received entries.<br><strong><sup>&#10033;</sup> Session</strong> is subject to change.</p>";
        $table_card_output_print .= "<table class='table table-bordered'>";
        $table_card_output_print .= "<thead>";
        $table_card_output_print .= "<tr>";
        $table_card_output_print .= "<th width=\"30%\">Table</th>";
        $table_card_output_print .= "<th width=\"25%\">Styles</th>";
        $table_card_output_print .= "<th nowrap>Entry Count<sup>&#10029;</sup></th>";
        $table_card_output_print .= "<th width=\"30%\">Session<sup>&#10033;</sup></th>";
        $table_card_output_print .= "</tr>";
        $table_card_output_print .= "</thead>";
        $table_card_output_print .= "<tbody>";
    }

    do {


        $a = array(get_table_info("1","list",$row_tables['id'],$dbTable,"default"));
        $styles = display_array_content($a,1);
        $received = get_table_info("1","count_total",$row_tables['id'],$dbTable,"default");

        if ($view == "master-list") {
            
            $table_card_output_print .= "<tr>";
            $table_card_output_print .= "<td>".sprintf("%s: %s", $row_tables['tableNumber'],$row_tables['tableName'])."</td>";
            $table_card_output_print .= "<td style=\"overflow-wrap: break-word;\">".rtrim($styles, ",&nbsp;")."</td>";
            $table_card_output_print .= "<td>".$received."</td>";
            $table_card_output_print .= "<td>".table_location($row_tables['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default")."</td>";
            $table_card_output_print .= "</tr>";

        }
        
        else {
            $table_card_output_print .= "<div class=\"table_card\">";
            $table_card_output_print .= "<h1>".sprintf("%s %s",$label_table, $row_tables['tableNumber'])."</h1>";
            $table_card_output_print .= "<h1><small>".$row_tables['tableName']."</small></h1>";
            $table_card_output_print .= "<h2><small>Styles: ".rtrim($styles, ",&nbsp;")."</small></h2>";
            $table_card_output_print .= "</div>";
            $table_card_output_print .= "<div style=\"page-break-after:always;\"></div>";
        }

    } while ($row_tables = mysqli_fetch_assoc($tables));
    
    if ($view == "master-list") {
        $table_card_output_print .= "</tbody>";
        $table_card_output_print .= "</table>";
    }

    echo $table_card_output_print;
    

} // end if (($table_card_output) && ($go == "sorting")) ?>

<?php
if (($table_card_output) && ($psort == "default")) {
if ($round != "default") $round2 = $round; else $round2 = "default";
if ($filter == "stewards") $filter = "S"; else $filter = "J";
$role_replace1 = array("HJ","LJ","MBOS",", ");
$role_replace2 = array("<span class=\"fa fa-gavel\"></span> Head Judge","<span class=\"fa fa-star\"></span> Lead Judge","<span class=\"fa fa-trophy\"></span> Mini-BOS Judge","&nbsp;&nbsp;&nbsp;");

if ($id == "default") { ?>

<?php do {
    include (DB.'output_table_cards.db.php');
?>
<div class="table_card">
    <h1><?php echo sprintf("%s %s",$label_table, $row_tables['tableNumber']); ?></h1>
    <h2><?php echo $row_tables['tableName']; ?></h2>
    <h4><?php echo table_location($row_tables['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default"); ?></h4>
    <?php if ($totalRows_assignments > 0) { ?>
    <script type="text/javascript" language="javascript">
         $(document).ready(function() {
                $('#sortable<?php echo $row_tables['id']; ?>').dataTable( {
                        "bPaginate" : false,
                        "sDom": 'rt',
                        "bStateSave" : false,
                        "bLengthChange" : false,
                        "aaSorting": [[0,'asc']],
                        "bProcessing" : false,
                        "aoColumns": [
                                { "asSorting": [  ] },
                                { "asSorting": [  ] },
                                { "asSorting": [  ] }<?php if ($_SESSION['jPrefsQueued'] == "N") { ?>,
                                { "asSorting": [  ] }
                                <?php } ?>
                                ]
                        } );
                } );
        </script>
    <table class="table table-bordered table-striped dataTable" id="sortable<?php echo $row_tables['id']; ?>">
    <thead>
    </thead>
    <tbody>

        <?php do {
                        $judge_info = explode("^",brewer_info($row_assignments['bid']));
                        if ($row_assignments['assignment'] == "S") $assignment = "Steward"; else $assignment = "Judge";
                        if ($row_assignments['assignRound'] != "") $round = "Round ".$row_assignments['assignRound']; else $round = "";
                        if ($row_assignments['assignFlight'] != "") $flight = "Flight ".$row_assignments['assignFlight']; else $flight = "";
                        $rank = str_replace(",",", ",$judge_info['3']);
                        $rank = str_replace("Novice", "Non-BJCP", $rank);
                        if ($judge_info[16] != "&nbsp;") $rank .= ", ".$judge_info[16];
                        $role = str_replace($role_replace1,$role_replace2,$row_assignments['assignRoles']);
                ?>
        <tr>
                <td><?php echo "<strong>".$judge_info['1'].", ".$judge_info['0']."</strong>"; if ((!empty($rank)) && ($assignment == "Judge")) echo " (".rtrim($rank,", ").") "; if (!empty($role)) echo "<br><em>".$role."</em>"; ?></td>
                <td width="5%" nowrap="nowrap"><?php echo $assignment ?></td>
                <td width="5%" nowrap="nowrap"><?php echo $round; ?></td>
                <?php if ($_SESSION['jPrefsQueued'] == "N") { ?>
                <td width="5%" nowrap="nowrap"><?php echo $flight; ?></td>
                <?php } ?>
        </tr>
                <?php } while ($row_assignments = mysqli_fetch_assoc($assignments)); ?>
    </tbody>
    </table>
    <?php } ?>

</div>
<div style="page-break-after:always;"></div>
<?php } while ($row_tables = mysqli_fetch_assoc($tables));
}
if ($id != "default") {
    include (DB.'output_table_cards.db.php');
?>
<div class="table_card">
    <h1><?php echo sprintf("%s %s",$label_table, $row_tables_edit['tableNumber']); ?></h1>
    <h2><?php echo $row_tables_edit['tableName']; ?></h2>
    <h4><?php echo table_location($row_tables_edit['id'],$_SESSION['prefsDateFormat'],$_SESSION['prefsTimeZone'],$_SESSION['prefsTimeFormat'],"default"); ?></h4>
    <?php if ($totalRows_assignments > 0) { ?>
    <script type="text/javascript" language="javascript">
         $(document).ready(function() {
                $('#sortable<?php echo $row_tables['id']; ?>').dataTable( {
                        "bPaginate" : false,
                        "sDom": 'rt',
                        "bStateSave" : false,
                        "bLengthChange" : false,
                        "aaSorting": [[0,'asc']],
                        "bProcessing" : false,
                        "aoColumns": [
                                { "asSorting": [  ] },
                                { "asSorting": [  ] },
                                { "asSorting": [  ] }<?php if ($_SESSION['jPrefsQueued'] == "N") { ?>,
                                { "asSorting": [  ] }
                                <?php } ?>
                                ]
                        } );
                } );
        </script>
    <table class="table table-bordered table-striped dataTable" id="sortable<?php echo $row_tables['id']; ?>">
    <thead>
    </thead>
    <tbody>

        <?php do {
                        $judge_info = explode("^",brewer_info($row_assignments['bid']));
                        if ($row_assignments['assignment'] == "S") $assignment = "Steward"; else $assignment = "Judge";
                        if ($row_assignments['assignRound'] != "") $round = "Round ".$row_assignments['assignRound']; else $round = "";
                        if ($row_assignments['assignFlight'] != "") $flight = "Flight ".$row_assignments['assignFlight']; else $flight = "";
                        $rank = str_replace(",",", ",$judge_info['3']);

                        $role = str_replace($role_replace1,$role_replace2,$row_assignments['assignRoles']);
                ?>
        <tr>
                <td><?php echo $judge_info['1'].", ".$judge_info['0']; if (!empty($rank)) echo " (".$rank.") "; echo $role; ?></td>
                <td width="5%" nowrap="nowrap"><?php echo $assignment ?></td>
                <td width="5%" nowrap="nowrap"><?php echo $round; ?></td>
                <?php if ($_SESSION['jPrefsQueued'] == "N") { ?>
                <td width="5%" nowrap="nowrap"><?php echo $flight; ?></td>
                <?php } ?>
        </tr>
                <?php } while ($row_assignments = mysqli_fetch_assoc($assignments)); ?>
    </tbody>
    </table>
    <?php } // end if ($totalRows_assignments > 0) ?>
</div>
<?php } // end if ($id != "default")
} // end if ($table_card_output)
?>