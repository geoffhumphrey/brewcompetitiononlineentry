<?php
if ($go == "org_notes") include (DB.'brewer.db.php');
if (($go == "allergens") || ($go == "admin")) include (DB.'entries.db.php');
include (LIB.'output.lib.php');

if ($go == "org_notes") {
?>
<div class="page-header">
	<h1><?php echo sprintf("%s %s",$_SESSION['contestName'],$label_org_notes); ?></h1>
</div>
<?php if ($totalRows_brewer > 0) { ?>
<p class="lead"><?php echo $output_text_014; ?></p>
<!-- All Notes -->
<script type="text/javascript" language="javascript">
 $(document).ready(function() {
	$('#sortable').dataTable( {
		"bPaginate" : false,
		"sDom": 'rt',
		"bStateSave" : false,
		"bLengthChange" : false,
		"aaSorting": [[0,'asc']],
		"bProcessing" : false,
		"aoColumns": [
			{ "asSorting": [  ] },
			{ "asSorting": [  ] }
			]
		} );
	} );
</script>
<table class="table table-bordered table-striped" id="sortable">
<thead>
<tr>
	<th><?php echo $label_name; ?></th>
	<th><?php echo $label_org_notes; ?></th>
</tr>
</thead>
<tbody>
<?php 
do { 
	if (!empty($row_brewer['brewerJudgeNotes'])) {
?>
<tr>
	<td><?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?></td>
	<td><?php echo $row_brewer['brewerJudgeNotes']; ?></td>
</tr>
<?php }
} while ($row_brewer = mysqli_fetch_assoc($brewer)); ?>
</tbody>
</table>
<?php } else { ?>
<p class="lead"><?php echo $output_text_013; ?></p>
<?php }
} 
?>

<?php if ($go == "allergens") { ?>
<div class="page-header">
	<h1><?php echo sprintf("%s %s",$_SESSION['contestName'],$label_possible_allergens); ?></h1>
</div>
<?php if ($totalRows_log_paid > 0) { ?>
<p class="lead"><?php echo $output_text_028; ?></p>
<!-- All Allergens -->
<script type="text/javascript" language="javascript">
 $(document).ready(function() {
	$('#allergens').dataTable( {
		"bPaginate" : false,
		"sDom": 'rt',
		"bStateSave" : false,
		"bLengthChange" : false,
		"aaSorting": [[3,'asc'],[0,'asc']],
		"bProcessing" : false,
		"aoColumns": [
			{ "asSorting": [  ] },
			{ "asSorting": [  ] },
			null,
			null,
			null
			]
		} );
	} );
</script>
<table class="table table-bordered table-striped" id="allergens">
<thead>
<tr>
	<th width="8%" nowrap="nowrap"><?php echo $label_entry_number; ?></th>
	<th width="8%" nowrap="nowrap"><?php echo $label_judging_number; ?></th>
	<th><?php echo $label_style; ?></th>
	<th><?php echo $label_table; ?></th>
	<th><?php echo $label_possible_allergens; ?></th>
</tr>
</thead>
<tbody>
<?php do {

	if (!empty($row_log_paid['brewPossAllergens'])) {

		$entry_number = sprintf("%06s",$row_log_paid['id']);
		$judging_number = sprintf("%06s",$row_log_paid['brewJudgingNumber']);
		$table_info = get_flight_info($row_log_paid['id']);
		$table_info_all = "";
		if ($table_info['response'] == "Assigned") {
			$table_info_all .= $table_info['tableNumber'].": ";
			$table_info_all .= $table_info['tableName'];
			if ($_SESSION['jPrefsQueued'] == "N") {
				$table_info_all .= "<br>".$label_round." ".$table_info['flightRound'];
				$table_info_all .= "<br>".$label_flight." ".$table_info['flightNumber'];
			}
		}

	?>
<tr>
	<td><?php echo $entry_number; ?></td>
	<td><?php echo $judging_number; ?></td>
	<td><?php echo $row_log_paid['brewStyle']; ?></td>
	<td nowrap="nowrap"><?php echo $table_info_all; ?></td>
	<td><?php echo $row_log_paid['brewPossAllergens']; ?></td>
</tr>
<?php }
} while ($row_log_paid = mysqli_fetch_assoc($log_paid)); ?>
</tbody>
</table>
<?php } else { ?>
<p class="lead"><?php echo $output_text_029; ?></p>
<?php }
} 
?>

<?php if ($go == "admin") { ?>
<div class="page-header">
	<h1><?php echo sprintf("%s %s",$_SESSION['contestName'],$label_notes); ?></h1>
</div>
<?php if ($totalRows_log > 0) { ?>
<!-- All Allergens -->
<script type="text/javascript" language="javascript">
 $(document).ready(function() {
	$('#allergens').dataTable( {
		"bPaginate" : false,
		"sDom": 'rt',
		"bStateSave" : false,
		"bLengthChange" : false,
		"aaSorting": [[3,'asc'],[0,'asc']],
		"bProcessing" : false,
		"aoColumns": [
			{ "asSorting": [  ] },
			{ "asSorting": [  ] },
			null,
			null,
			null,
			null
			]
		} );
	} );
</script>
<table class="table table-bordered table-striped" id="allergens">
<thead>
<tr>
	<th width="10%" nowrap="nowrap"><?php echo $label_entry_number; ?></th>
	<th width="10%" nowrap="nowrap"><?php echo $label_judging_number; ?></th>
	<th width="20%"><?php echo $label_style; ?></th>
	<th width="20%"><?php echo $label_table; ?></th>
	<th width="20%"><?php echo $label_admin; ?></th>
	<th width="20%"><?php echo $label_staff; ?></th>
</tr>
</thead>
<tbody>
<?php do {

	if ((!empty($row_log['brewAdminNotes'])) || (!empty($row_log['brewStaffNotes']))) {


	$entry_number = sprintf("%06s",$row_log['id']);
	$judging_number = sprintf("%06s",$row_log['brewJudgingNumber']);
	$table_info = get_flight_info($row_log['id']);
	$table_info_all = "";
	if ($table_info['response'] == "Assigned") {
		$table_info_all .= $table_info['tableNumber'].": ";
		$table_info_all .= $table_info['tableName'];
		if ($_SESSION['jPrefsQueued'] == "N") {
			$table_info_all .= "<br>".$label_round." ".$table_info['flightRound'];
			$table_info_all .= "<br>".$label_flight." ".$table_info['flightNumber'];
		}
	}

	?>
<tr>
	<td><?php echo $entry_number; ?></td>
	<td><?php echo $judging_number; ?></td>
	<td><?php echo $row_log['brewStyle']; ?></td>
	<td nowrap="nowrap"><?php echo $table_info_all; ?></td>
	<td><?php if (!empty($row_log['brewAdminNotes'])) echo $row_log['brewAdminNotes']; ?></td>
	<td><?php if (!empty($row_log['brewStaffNotes'])) echo $row_log['brewStaffNotes']; ?></td>
</tr>
<?php 
	}
} while ($row_log = mysqli_fetch_assoc($log)); 
?>
</tbody>
</table>
<?php } else { ?>
<p class="lead"><?php echo $output_text_029; ?></p>
<?php }
} 
?>