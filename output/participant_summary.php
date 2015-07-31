<?php 
session_start(); 
require('../paths.php'); 
require(CONFIG.'bootstrap.php');


if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= 1)) {

$section = "participant_summary";
include(DB.'brewer.db.php');
$total_entries_judged = get_entry_count('received');
if (NHC) $base_url = "../";
include(LIB.'output.lib.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['contestName']; ?> Summary</title>
<link href="<?php echo $base_url; ?>css/print.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>js_includes/jquery.dataTables.js"></script>
</head>
<body>
<div id="content">
<?php do {
include(DB.'output_participant_summary.db.php');
if ($totalRows_log > 0) { ?>
	<div id="content-inner">
	<div id="header">	
		<div id="header-inner">
        	<h1><?php echo $_SESSION['contestName']; ?> Summary for <?php echo $row_brewer['brewerFirstName']. " ".$row_brewer['brewerLastName']; ?></h1>
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
        <th class="dataHeading bdr1B" width="5%">Judging #</th>
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
        <td class="data bdr1B_gray"><?php echo readable_judging_number($row_log['brewCategory'],$row_log['brewJudgingNumber']); ?></td>
        <td class="data bdr1B_gray"><?php echo $row_log['brewName']; ?></td>
        <td class="data bdr1B_gray"><?php echo $row_log['brewCategorySort'].$row_log['brewSubCategory'].": ".$row_log['brewStyle'] ?></td>
        <td class="data bdr1B_gray"><?php echo score_check($row_log['id'],$judging_scores_db_table,1); ?></td>
        <td class="data bdr1B_gray"><?php echo winner_check($row_log['id'],$judging_scores_db_table,$judging_tables_db_table,$brewing_db_table,$_SESSION['prefsWinnerMethod']); ?></td>
    </tr>
    <?php } while ($row_log = mysql_fetch_assoc($log)); ?>
    </tbody>
    </table>
    <?php if ($totalRows_organizer > 0) { ?>
    <p>Regards,</p>
    <p><?php echo $row_organizer['brewerFirstName']." ".$row_organizer['brewerLastName']; ?><br />Organizer, <?php echo $_SESSION['contestName']; ?></p>
    <?php } ?>
    </div>
    <div style="page-break-after:always;"></div>
    <?php } // END entries section ?>
    <?php } while ($row_brewer = mysql_fetch_assoc($brewer)); ?>
</div>
</body>
</html>
<?php if (!$fx) { ?>
<script type="text/javascript">
function selfPrint(){
    self.focus();
    self.print();
}
setTimeout('selfPrint()',3000);
html.push(''); 
</script>
<?php } 
}
else echo "<p>Not available.</p>";
?>