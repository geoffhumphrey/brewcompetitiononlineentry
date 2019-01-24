<?php
$section = "table_cards";
include (DB.'admin_common.db.php');

if ($totalRows_tables == 0) {
	echo "<h2>";
	echo sprintf("%s",$output_text_005);
	if ($go == "judging_locations") echo sprintf(" %s",$output_text_006);
	echo ".</h2>";
	echo sprintf("<p class=\"lead\">%s</p>",$output_text_007);
}

else {
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
    <?php } ?>
</div>
<?php }
}
?>