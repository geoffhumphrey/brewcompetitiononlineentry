<?php 
//session_start(); 

require('../paths.php'); 
require(CONFIG.'config.php');
include(INCLUDES.'functions.inc.php');
include(INCLUDES.'url_variables.inc.php');
require(INCLUDES.'db_tables.inc.php');
require(DB.'common.db.php');
include(DB.'admin_common.db.php');
include(INCLUDES.'version.inc.php');
include(INCLUDES.'headers.inc.php');
include(DB.'brewer.db.php');
$query_organizer = "SELECT * FROM $brewer_db_table WHERE brewerAssignment='O'";
$organizer = mysql_query($query_organizer, $brewing) or die(mysql_error());
$row_organizer = mysql_fetch_assoc($organizer);
$totalRows_organizer = mysql_num_rows($organizer);
//ini_set('display_errors', '0');
$total_entries_judged = get_entry_count('received');
//$total_judges = "";
//$total_participants = get_participant_count();
//error_reporting(E_ALL);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $row_contest_info['contestName']; ?> Summary</title>
<link href="<?php echo $base_url; ?>/css/print.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/js_includes/jquery.dataTables.js"></script>
</head>
<body onload="javascript:window.print()">
<div id="content">
<?php do { 
// Check for Entries
$query_log = sprintf("SELECT * FROM $brewing_db_table WHERE brewBrewerID='%s' AND brewReceived='1' AND brewPaid='1'", $row_brewer['uid']);
$log = mysql_query($query_log, $brewing) or die(mysql_error());
$row_log = mysql_fetch_assoc($log);
$totalRows_log = mysql_num_rows($log);

if ($totalRows_log > 0) { ?>
	<div id="content-inner">
	<div id="header">	
		<div id="header-inner">
        	<h1><?php echo $row_contest_info['contestName']; ?> Summary for <?php echo $row_brewer['brewerFirstName']. " ".$row_brewer['brewerLastName']; ?></h1>
        </div>
    </div>
    <p>Thank you for entering our competition, <?php echo $row_brewer['brewerFirstName']; ?>. A summary of your entries and their associated scores and places is below.</p>
    <p>In all, there were <?php echo $total_entries_judged; ?> entries.
    <!-- Brewer's Entries -->
    <script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable_entries<?php echo $row_brewer['id']; ?>').dataTable( {
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
				{ "asSorting": [  ] },
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
    <table class="dataTable" id="sortable_entries<?php echo $row_brewer['id']; ?>">
    <thead>
    <tr>
    	<th class="dataHeading bdr1B" width="5%">Entry #</th>
        <th class="dataHeading bdr1B" width="25%">Entry Name</th>
        <th class="dataHeading bdr1B">Style</th>
        <th class="dataHeading bdr1B" width="5%">Score</th>
        <th class="dataHeading bdr1B" width="20%">Place</th>
    </tr>
    </thead>
    <tbody>
    <?php do { ?>
    <tr>
    	<td class="bdr1B_gray"><?php echo sprintf("%04s",$row_log['id']); ?></td>
        <td class="data bdr1B_gray"><?php echo $row_log['brewName']; ?></td>
        <td class="data bdr1B_gray"><?php echo $row_log['brewCategorySort'].$row_log['brewSubCategory'].": ".$row_log['brewStyle'] ?></td>
        <td class="data bdr1B_gray"><?php echo score_check($row_log['id'],$judging_scores_db_table,1); ?></td>
        <td class="data bdr1B_gray"><?php echo winner_check($row_log['id'],$judging_scores_db_table,$judging_tables_db_table,$brewing_db_table,$row_prefs['prefsWinnerMethod']); ?></td>
    </tr>
    <?php } while ($row_log = mysql_fetch_assoc($log)); ?>
    </tbody>
    </table>
    <?php if ($totalRows_organizer > 0) { ?>
    <p>Regards,</p>
    <p><?php echo $row_organizer['brewerFirstName']." ".$row_organizer['brewerLastName']; ?><br />Organizer, <?php echo $row_contest_info['contestName']; ?></p>
    <?php } ?>
    </div>
    <div style="page-break-after:always;"></div>
    <?php } // END entries section ?>
    <?php } while ($row_brewer = mysql_fetch_assoc($brewer)); ?>
</div>
</body>
</html>