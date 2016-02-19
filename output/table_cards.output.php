<?php 
$section = "table_cards";
include(DB.'admin_common.db.php');

if ($totalRows_tables == 0) { 
echo "<h2>No judge/steward assignments have been defined"; if ($go == "judging_locations") echo " for this location"; echo ".</h2>";
echo "<p class=\"lead\">If you would like to print blank table cards, close this window and choose &ldquo;Print Table Cards: All Tables&rdquo; from the <em>Reporting</em> menu.</p>";
}
 
else {
if ($round != "default") $round2 = $round; else $round2 = "default";
if ($filter == "stewards") $filter = "S"; else $filter = "J";

if ($id == "default") { ?>

    <?php do { 
          include(DB.'output_table_cards.db.php');
        ?>
<div class="table_card">
    <h1>Table <?php echo $row_tables['tableNumber']; ?></h1>
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
                        "aaSorting": [[1,'asc'],[0,'asc']],
                        "bProcessing" : false,
                        "aoColumns": [
                                { "asSorting": [  ] },
                                { "asSorting": [  ] },
                                { "asSorting": [  ] }
                                ]
                        } );
                } );
        </script>
    <table class="table table-bordered table-striped" id="sortable<?php echo $row_tables['id']; ?>">
    <thead>
    </thead>
    <tbody>
        
        <?php do { 
                        $judge_info = explode("^",brewer_info($row_assignments['bid'])); 
                        if ($row_assignments['assignment'] == "S") $assignment = "Steward"; else $assignment = "Judge";
                        if ($row_assignments['assignRound'] != "") $round = "Round ".$row_assignments['assignRound']; else $round = "";
                        if ($row_assignments['assignFlight'] != "") $flight = "Flight ".$row_assignments['assignFlight']; else $flight = "";
						$rank = str_replace(",",", ",$judge_info['3']);
                ?>
        <tr>
                <td width="75%"><?php echo $judge_info['1'].", ".$judge_info['0']; if (!empty($rank)) echo " (".$rank.")"; ?></td>
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
    <table class="table table-bordered table-striped">
    <?php for($i=0; $i<(8-$totalRows_assignments); $i++) { ?>
    <tr>
    	<td>&nbsp;</td>
    </tr>
    <?php } ?>
    </table>
</div>
<div style="page-break-after:always;"></div>
<?php } while ($row_tables = mysqli_fetch_assoc($tables)); 
} 
if ($id != "default") { 
	include(DB.'output_table_cards.db.php');
?>
<div class="table_card">
    <h1>Table <?php echo $row_tables_edit['tableNumber']; ?></h1>
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
                        "aaSorting": [[1,'asc'],[0,'asc']],
                        "bProcessing" : false,
                        "aoColumns": [
                                { "asSorting": [  ] },
                                { "asSorting": [  ] },
                                { "asSorting": [  ] },
                                { "asSorting": [  ] }
                                ]
                        } );
                } );
        </script>
    <table class="table table-bordered table-striped" id="sortable<?php echo $row_tables['id']; ?>">
    <thead>
    </thead>
    <tbody>
        
        <?php do { 
                        $judge_info = explode("^",brewer_info($row_assignments['bid'])); 
                        if ($row_assignments['assignment'] == "S") $assignment = "Steward"; else $assignment = "Judge";
                        if ($row_assignments['assignRound'] != "") $round = "Round ".$row_assignments['assignRound']; else $round = "";
                        if ($row_assignments['assignFlight'] != "") $flight = "Flight ".$row_assignments['assignFlight']; else $flight = "";
						$rank = str_replace(",",", ",$judge_info['3']);
                ?>
        <tr>
                <td width="5%" nowrap="nowrap"><?php echo $judge_info['1'].", ".$judge_info['0']; if (!empty($rank)) echo " (".$rank.")"; ?></td>
                <td width="5%" nowrap="nowrap"><?php echo $assignment ?></td>
            	<td width="5%" nowrap="nowrap"><?php echo $round; ?></td>
            	<td><?php if ($_SESSION['jPrefsQueued'] == "N")  echo $flight; ?></td>
        </tr>
                <?php } while ($row_assignments = mysqli_fetch_assoc($assignments)); ?>
    </tbody>
    </table>
    <?php } ?>
    <?php if ($totalRows_assignments < 8) { ?>
    <table class="table table-bordered table-striped">
    <?php for($i=0; $i<(12-$totalRows_assignments); $i++) { ?>
    <tr>
    	<td>&nbsp;</td>
    </tr>
    <?php } ?>
    </table>
    <?php } ?>
</div>
<?php } 
}
?>