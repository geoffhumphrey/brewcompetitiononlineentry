<?php 
session_start(); 
require('../paths.php'); 
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php');
require(DB.'common.db.php');
include(DB.'admin_common.db.php');
require(INCLUDES.'version.inc.php');

if ( $go == "judging_tables" ) {
   $query_tables = "SELECT * FROM $judging_tables_db_table ORDER BY tableNumber";       
}

if ( $go == "judging_locations" ) {
   $query_tables = sprintf("SELECT a.*, b.assignRound FROM $judging_tables_db_table a, $judging_assignments_db_table b WHERE a.id = b.assignTable AND a.tableLocation = '%s' AND b.assignRound = '%s' GROUP BY b.assignTable ORDER BY tableNumber", $location, $round);       
}

$tables = mysql_query($query_tables, $brewing) or die(mysql_error());
$row_tables = mysql_fetch_assoc($tables);
$totalRows_tables = mysql_num_rows($tables);
//echo $query_tables."<br>";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Brew Competition Online Entry and Management - brewcompetition.com</title>
<link href="<?php echo $base_url; ?>/css/print.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/jquery.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/jquery.dataTables.js"></script>
</head>


<?php
if ($totalRows_tables == 0) { 
echo "<body>";
echo "<h1>No judge/steward assignments have been defined"; if ($go == "judging_locations") echo " for this location"; echo ".</h2>"; 
} 
else {
if ($round != "default") $round2 = $round; else $round2 = "default";
if ($filter == "stewards") $filter = "S"; else $filter = "J";
?>
<body onload="javascript:window.print()">
<?php if ($id == "default") { ?>

    <?php do { 
                $query_assignments = sprintf("SELECT * FROM $judging_assignments_db_table WHERE assignTable='%s'",$row_tables['id']);
                if ($round2 != "default") $query_assignments .= sprintf(" AND assignRound='%s'", $round2);                        
                $query_assignments .= " ORDER BY assignRound,assignFlight ASC";
                $assignments = mysql_query($query_assignments, $brewing) or die(mysql_error());
                $row_assignments = mysql_fetch_assoc($assignments);
                $totalRows_assignments = mysql_num_rows($assignments);
        ?>
<div class="table_card">
    <h1>Table <?php echo $row_tables['tableNumber']; ?></h1>
    <h2><?php echo $row_tables['tableName']; ?></h2>
    <h4><?php echo table_location($row_tables['id'],$row_prefs['prefsDateFormat'],$row_prefs['prefsTimeZone'],$row_prefs['prefsTimeFormat'],"default"); ?></h4>
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
                                { "asSorting": [  ] },
                                { "asSorting": [  ] }
                                ]
                        } );
                } );
        </script>
    <table class="dataTable" id="sortable<?php echo $row_tables['id']; ?>">
    <thead>
    </thead>
    <tbody>
        
        <?php do { 
                        $judge_info = explode("^",brewer_info($row_assignments['bid'])); 
                        if ($row_assignments['assignment'] == "S") $assignment = "&ndash; Steward"; else $assignment = "&ndash; Judge";
                        if ($row_assignments['assignRound'] != "") $round = "Round ".$row_assignments['assignRound']; else $round = "";
                        if ($row_assignments['assignFlight'] != "") $flight = "Flight ".$row_assignments['assignFlight']; else $flight = "";
                ?>
        <tr>
                <td width="5%" nowrap="nowrap"><?php echo $judge_info['1'].", ".$judge_info['0']; ?></td>
                <td class="data" width="5%" nowrap="nowrap"><?php echo $assignment ?></td>
            <td class="data" width="5%" nowrap="nowrap"><?php echo $round; ?></td>
            <td class="data"><?php if ($row_judging_prefs['jPrefsQueued'] == "N") echo $flight; ?></td>
                </tr>
                <?php } while ($row_assignments = mysql_fetch_assoc($assignments)); ?>
    </tbody>
    </table>
    <?php } ?>
    <table class="dataTable">
    <?php for($i=0; $i<(14-$totalRows_assignments); $i++) { ?>
    <tr>
    	<td class="bdr1B">&nbsp;</td>
    </tr>
    <?php } ?>
    </table>
</div>
<div style="page-break-after:always;"></div>
<?php } while ($row_tables = mysql_fetch_assoc($tables)); ?>


<?php } 
if ($id != "default")
{ ?>
<?php 
$query_assignments = sprintf("SELECT * FROM $judging_assignments_db_table WHERE assignTable='%s'",$id);
if ($round2 != "default") $query_assignments .= sprintf(" AND assignRound='%s'", $round2);
$query_assignments .= " ORDER BY assignRound,assignFlight ASC";
$assignments = mysql_query($query_assignments, $brewing) or die(mysql_error());
$row_assignments = mysql_fetch_assoc($assignments);
$totalRows_assignments = mysql_num_rows($assignments);
?>
<div class="table_card">
    <h1>Table <?php echo $row_tables_edit['tableNumber']; ?></h1>
    <h2><?php echo $row_tables_edit['tableName']; ?></h2>
    <h4><?php echo table_location($row_tables_edit['id'],$row_prefs['prefsDateFormat'],$row_prefs['prefsTimeZone'],$row_prefs['prefsTimeFormat'],"default"); ?></h4>
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
                                { "asSorting": [  ] },
                                { "asSorting": [  ] }
                                ]
                        } );
                } );
        </script>
    <table class="dataTable" id="sortable<?php echo $row_tables['id']; ?>">
    <thead>
    </thead>
    <tbody>
        
        <?php do { 
                        $judge_info = explode("^",brewer_info($row_assignments['bid'])); 
                        if ($row_assignments['assignment'] == "S") $assignment = "&ndash; Steward"; else $assignment = "&ndash; Judge";
                        if ($row_assignments['assignRound'] != "") $round = "Round ".$row_assignments['assignRound']; else $round = "";
                        if ($row_assignments['assignFlight'] != "") $flight = "Flight ".$row_assignments['assignFlight']; else $flight = "";
                ?>
        <tr>
                <td width="5%" nowrap="nowrap"><?php echo $judge_info['1'].", ".$judge_info['0']; ?></td>
                <td class="data" width="5%" nowrap="nowrap"><?php echo $assignment ?></td>
            <td class="data" width="5%" nowrap="nowrap"><?php echo $round; ?></td>
            <td class="data"><?php if ($row_judging_prefs['jPrefsQueued'] == "N")  echo $flight; ?></td>
                </tr>
                <?php } while ($row_assignments = mysql_fetch_assoc($assignments)); ?>
    </tbody>
    </table>
    <?php } ?>
    <table class="dataTable">
    <?php for($i=0; $i<(14-$totalRows_assignments); $i++) { ?>
    <tr>
    	<td class="bdr1B">&nbsp;</td>
    </tr>
    <?php } ?>
    </table>
</div>
<?php } 
}
?>
</body>
</html>
