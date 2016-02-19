<?php 
$section = "participant_summary";
include(DB.'brewer.db.php');
$total_entries_judged = get_entry_count('received');
if (NHC) $base_url = "../";
include(LIB.'output.lib.php');

do {
	include(DB.'output_participant_summary.db.php');
	if ($totalRows_log > 0) { ?>
		<div class="page-header">
            <h1><?php echo $_SESSION['contestName']; ?> Summary for <?php echo $row_brewer['brewerFirstName']. " ".$row_brewer['brewerLastName']; ?></h1>
        </div>
		<p class="lead">Thank you for entering our competition, <?php echo $row_brewer['brewerFirstName']; ?>. A summary of your entries and their associated scores and places is below.</p>
		<p class="lead"><small>In all, there were <?php echo $total_entries_judged; ?> entries.</small></p>
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
    <table class="table table-bordered table-striped" id="sortable_entries<?php echo $row_brewer['id']; ?>">
    <thead>
		<tr>
			<th width="5%" nowrap>Entry</th>
			<th width="5%" nowrap>Judging</th>
			<th width="25%">Entry Name</th>
			<th>Style</th>
			<th width="5%" nowrap>Score</th>
			<th width="20%">Place</th>
		</tr>
    </thead>
    <tbody>
		<?php do { ?>
		<tr>
			<td><?php echo sprintf("%04s",$row_log['id']); ?></td>
			<td><?php echo readable_judging_number($row_log['brewCategory'],$row_log['brewJudgingNumber']); ?></td>
			<td><?php echo $row_log['brewName']; ?></td>
			<td><?php echo $row_log['brewCategorySort'].$row_log['brewSubCategory'].": ".$row_log['brewStyle'] ?></td>
			<td><?php echo score_check($row_log['id'],$judging_scores_db_table,1); ?></td>
			<td><?php echo winner_check($row_log['id'],$judging_scores_db_table,$judging_tables_db_table,$brewing_db_table,$_SESSION['prefsWinnerMethod']); ?></td>
		</tr>
	<?php } while ($row_log = mysqli_fetch_assoc($log)); ?>
    </tbody>
    </table>
    <?php if ($totalRows_organizer > 0) { ?>
    <p>Regards,</p>
    <p><?php echo $row_organizer['brewerFirstName']." ".$row_organizer['brewerLastName']; ?><br />Organizer, <?php echo $_SESSION['contestName']; ?></p>
    <?php } ?>
    <div style="page-break-after:always;"></div>
    <?php } // END entries section ?>
    <?php } while ($row_brewer = mysqli_fetch_assoc($brewer)); ?>