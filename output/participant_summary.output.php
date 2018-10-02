<?php
$section = "participant_summary";
include (DB.'brewer.db.php');
include (DB.'winners.db.php');
$total_entries_judged = get_entry_count('received');
if (NHC) $base_url = "../";
// include (LIB.'common.lib.php');
include (LIB.'output.lib.php');

// Best of Show check

function bos_place($entry_id,$prefix,$connection) {
    $query_bos = sprintf("SELECT a.scorePlace FROM %s a, %s b, %s c WHERE a.eid = %s AND c.uid = b.brewBrewerID", $prefix."judging_scores_bos", $prefix."brewing", $prefix."brewer", $entry_id);
    $bos = mysqli_query($connection,$query_bos) or die (mysqli_error($connection));
    $row_bos = mysqli_fetch_assoc($bos);
    $totalRows_bos = mysqli_num_rows($bos);

    if ($totalRows_bos > 0) {
        $return = $row_bos['scorePlace'];
    }

    else $return = "";

    return $return;
}

// Get custom winning category info
do { $sbi_categories[] = $row_sbi['id']."|".$row_sbi['sbi_name']; } while ($row_sbi = mysqli_fetch_assoc($sbi));

foreach ($sbi_categories as $special_best_cat) {

	$explodies = explode ("|", $special_best_cat);

	$query_sbd = sprintf("SELECT * FROM %s WHERE sid='%s' ORDER BY sbd_place ASC",$prefix."special_best_data",$explodies[0]);
	$sbd = mysqli_query($connection,$query_sbd) or die (mysqli_error($connection));
	$row_sbd = mysqli_fetch_assoc($sbd);
	$totalRows_sbd = mysqli_num_rows($sbd);

	do { $special_best_cat_winners[] = $explodies[1]."|".$row_sbd['eid']."|".$row_sbd['sbd_place']; } while ($row_sbd = mysqli_fetch_assoc($sbd));

}

do {

	include (DB.'output_participant_summary.db.php');

	if ($totalRows_log > 0) {

        ?>
		<div class="page-header">
            <h1><?php echo sprintf("%s %s %s %s",$_SESSION['contestName'],$output_text_002,$row_brewer['brewerFirstName'],$row_brewer['brewerLastName']); ?></h1>
        </div>
		<p class="lead"><?php echo sprintf("%s, %s. %s",$output_text_000,$row_brewer['brewerFirstName'],$output_text_001); ?> </p>
		<p class="lead"><small><?php echo sprintf("%s %s.",$total_entries_judged,$output_text_003); ?></small></p>
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
			<th width="5%" nowrap><?php echo $label_entry; ?></th>
			<th width="5%" nowrap><?php echo $label_judging; ?></th>
			<th width="25%"><?php echo $label_name; ?></th>
			<th><?php echo $label_style; ?></th>
			<th width="5%" nowrap><?php echo $label_score; ?></th>
			<th width="5%" nowrap><?php echo $label_mini_bos; ?></th>
            <th width="5%" nowrap><?php echo $label_bos; ?></th>
			<th width="20%"><?php echo $label_place; ?></th>
		</tr>
    </thead>
    <tbody>
		<?php do {
            $bos_place = bos_place($row_log['id'],$prefix,$connection);
            ?>
		<tr>
			<td><?php echo sprintf("%04s",$row_log['id']); ?></td>
			<td><?php echo readable_judging_number($row_log['brewCategory'],$row_log['brewJudgingNumber']); ?></td>
			<td><?php echo $row_log['brewName']; ?></td>
			<td><?php if ($_SESSION['prefsStyleSet'] != "BA") echo $row_log['brewCategorySort'].$row_log['brewSubCategory'].": "; echo $row_log['brewStyle'] ?></td>
			<td><?php echo score_check($row_log['id'],$judging_scores_db_table,1); ?></td>
			<td><?php if (minibos_check($row_log['id'],$judging_scores_db_table)) echo "<span class =\"fa fa-lg fa-check\"></span>"; ?></td>
            <td><?php if (!empty($bos_place)) echo "<span class=\"fa fa-lg fa-trophy\"></span> ". addOrdinalNumberSuffix($bos_place); ?></td>
			<td><?php echo winner_check($row_log['id'],$judging_scores_db_table,$judging_tables_db_table,$brewing_db_table,$_SESSION['prefsWinnerMethod']); ?></td>
		</tr>
		<?php } while ($row_log = mysqli_fetch_assoc($log)); ?>
    </tbody>
    </table>
    <?php if ($totalRows_organizer > 0) { ?>
    <p><?php echo sprintf("%s,",$label_cheers); ?></p>
    <p><?php echo sprintf("%s %s",$row_organizer['brewerFirstName'],$row_organizer['brewerLastName']); ?><br /><?php echo sprintf("%s, %s",$label_organizer,$_SESSION['contestName']); ?></p>
    <?php } ?>
    <div style="page-break-after:always;"></div>
    <?php } // END entries section ?>
    <?php } while ($row_brewer = mysqli_fetch_assoc($brewer)); ?>