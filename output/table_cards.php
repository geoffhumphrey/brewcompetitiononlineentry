<?php 
session_start(); 
require('../paths.php'); 
require(CONFIG.'bootstrap.php');
if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {
if (NHC) $base_url = "../";
$section = "table_cards";
include(DB.'admin_common.db.php');
//echo $query_tables."<br>";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Brew Competition Online Entry and Management - brewcompetition.com</title>
<link href="<?php echo $base_url; ?>css/print.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.dataTables.js"></script>
</head>


<?php
if ($totalRows_tables == 0) { 
echo "<body>";
echo "<h1>No judge/steward assignments have been defined"; if ($go == "judging_locations") echo " for this location"; echo ".</h2>";
echo "<p>If you would like to print blank table cards, close this window and choose &ldquo;Print Table Cards: All Tables&rdquo; from the <em>Printing and Reporting</em> menu, under the <em>Before Judging</em> heading.</p>";
} 
else {
if ($round != "default") $round2 = $round; else $round2 = "default";
if ($filter == "stewards") $filter = "S"; else $filter = "J";
?>
<body>
<?php if ($id == "default") { ?>

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
                        if ($row_assignments['assignment'] == "S") $assignment = "Steward"; else $assignment = "Judge";
                        if ($row_assignments['assignRound'] != "") $round = "Round ".$row_assignments['assignRound']; else $round = "";
                        if ($row_assignments['assignFlight'] != "") $flight = "Flight ".$row_assignments['assignFlight']; else $flight = "";
                ?>
        <tr>
                <td width="5%" nowrap="nowrap"><?php echo $judge_info['1'].", ".$judge_info['0']; ?></td>
                <td class="data" width="5%" nowrap="nowrap"><?php echo $assignment ?></td>
            <td class="data" width="5%" nowrap="nowrap"><?php echo $round; ?></td>
            <td class="data"><?php if ($_SESSION['jPrefsQueued'] == "N") echo $flight; ?></td>
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
<?php } while ($row_tables = mysql_fetch_assoc($tables)); 
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
                        if ($row_assignments['assignment'] == "S") $assignment = "Steward"; else $assignment = "Judge";
                        if ($row_assignments['assignRound'] != "") $round = "Round ".$row_assignments['assignRound']; else $round = "";
                        if ($row_assignments['assignFlight'] != "") $flight = "Flight ".$row_assignments['assignFlight']; else $flight = "";
                ?>
        <tr>
                <td width="5%" nowrap="nowrap"><?php echo $judge_info['1'].", ".$judge_info['0']; ?></td>
                <td class="data" width="5%" nowrap="nowrap"><?php echo $assignment ?></td>
            <td class="data" width="5%" nowrap="nowrap"><?php echo $round; ?></td>
            <td class="data"><?php if ($_SESSION['jPrefsQueued'] == "N")  echo $flight; ?></td>
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
<script type="text/javascript">
function selfPrint(){
    self.focus();
    self.print();
}
setTimeout('selfPrint()',2000);
html.push(''); 
</script>
<?php } 
else echo "<p>Not available.</p>";
?>