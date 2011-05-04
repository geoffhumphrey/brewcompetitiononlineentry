<?php 
session_start(); 
require('../paths.php'); 
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'url_variables.inc.php');
require(DB.'common.db.php');
include(DB.'admin_common.db.php');
require(INCLUDES.'version.inc.php');

if ($filter == "stewards") $filter = "S"; else $filter = "J";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if ($tb == "default") { ?><meta http-equiv="refresh" content="0;URL=<?php echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."&tb=true"; ?>" /><?php } ?>
<title>Brew Competition Online Entry and Management - brewcompetition.com</title>
<link href="../css/print.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="../js_includes/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../js_includes/jquery.dataTables.js"></script>
</head>
<body <?php if ($tb == "true") echo "onload=\"javascript:window.print()\""; ?>>
<?php if ($id == "default") { ?>

    <?php do { 
		$query_assignments = sprintf("SELECT * FROM judging_assignments WHERE assignTable='%s' ORDER BY assignRound,assignFlight ASC", $row_tables['id']);
		$assignments = mysql_query($query_assignments, $brewing) or die(mysql_error());
		$row_assignments = mysql_fetch_assoc($assignments);
		$totalRows_assignments = mysql_num_rows($assignments);
	?>
<div class="table_card">
    <h1>Table <?php echo $row_tables['tableNumber']; ?></h1>
    <h2><?php echo $row_tables['tableName']; ?></h2>
    <h4><?php echo table_location($row_tables['id']); ?></h4>
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
            <td class="data"><?php echo $flight; ?></td>
		</tr>
   		<?php } while ($row_assignments = mysql_fetch_assoc($assignments)); ?>
    </tbody>
    </table>
    <?php } ?>
    <?php for($i=0; $i<8; $i++) echo "<hr />"; ?>
</div>
<div style="page-break-after:always;"></div>
<?php } while ($row_tables = mysql_fetch_assoc($tables)); ?>


<?php } else { ?>
<?php 
$query_assignments = sprintf("SELECT * FROM judging_assignments WHERE assignTable='%s' ORDER BY assignRound,assignFlight ASC", $id);
$assignments = mysql_query($query_assignments, $brewing) or die(mysql_error());
$row_assignments = mysql_fetch_assoc($assignments);
$totalRows_assignments = mysql_num_rows($assignments);
?>
<div class="table_card">
    <h1>Table <?php echo $row_tables['tableNumber']; ?></h1>
    <h2><?php echo $row_tables['tableName']; ?></h2>
    <h4><?php echo table_location($row_tables['id']); ?></h4>
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
            <td class="data"><?php echo $flight; ?></td>
		</tr>
   		<?php } while ($row_assignments = mysql_fetch_assoc($assignments)); ?>
    </tbody>
    </table>
    <?php } ?>
    <?php for($i=0; $i<8; $i++) echo "<hr />"; ?>
</div>
<?php } ?>
</body>
</html>