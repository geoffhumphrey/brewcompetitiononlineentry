<?php

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] > 1))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

if ($go == "org_notes") include (DB.'brewer.db.php');
if (($go == "allergens") || ($go == "admin") || ($go == "bottles")) include (DB.'entries.db.php');
include (LIB.'admin.lib.php');
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
foreach ($rows_brewer as $row_brewer) {
	if (!empty($row_brewer['brewerJudgeNotes'])) {
?>
<tr>
	<td><?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?></td>
	<td><?php echo $row_brewer['brewerJudgeNotes']; ?></td>
</tr>
<?php }
} ?>
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
<?php foreach ($rows_log_paid as $row_log_paid) {

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
} ?>
</tbody>
</table>
<?php } else { ?>
<p class="lead"><?php echo $output_text_029; ?></p>
<?php }
}
?>

<?php if ($go == "bottles") { ?>
<div class="page-header">
	<h1><?php echo sprintf("%s %s",$_SESSION['contestName'],$label_bottles_cans_mismatch); ?></h1>
</div>
<?php if ($totalRows_log > 0) { ?>
<p class="lead"><?php echo $output_text_035; ?></p>
<script type="text/javascript" language="javascript">
 $(document).ready(function() {
	$('#bottlescans').dataTable( {
		"bPaginate" : false,
		"sDom": 'rt',
		"bStateSave" : false,
		"bLengthChange" : false,
		"aaSorting": [[2,'asc'],[0,'asc']],
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
<table class="table table-bordered table-striped" id="bottlescans">
<thead>
<tr>
	<th width="10%" nowrap="nowrap"><?php echo $label_entry_number; ?></th>
	<th width="10%" nowrap="nowrap"><?php echo $label_judging_number; ?></th>
	<th width="25%"><?php echo $label_style; ?></th>
	<th width="25%"><?php echo $label_table; ?></th>
	<th width="15%"><?php echo $label_specified; ?></th>
	<th width="15%"><?php echo $label_received; ?></th>
</tr>
</thead>
<tbody>
<?php foreach ($rows_log as $row_log) {

	$entry_number = sprintf("%06s",$row_log['id']);
	$judging_number = sprintf("%06s",$row_log['brewJudgingNumber']);

	$brew_style = "";
	if (!str_contains($_SESSION['prefsStyleSet'], "BA")) {
		if (!empty($row_log['brewCategorySort'])) $brew_style .= $row_log['brewCategorySort'];
		if (!empty($row_log['brewSubCategory'])) $brew_style .= $row_log['brewSubCategory'];
		if ((!empty($row_log['brewCategorySort'])) || (!empty($row_log['brewSubCategory']))) $brew_style .= ": ";
	}
	$brew_style .= $row_log['brewStyle'];

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
	else $table_info_all = "<i class='fa fa-lg fa-fw fa-ban'></i>";

?>
<tr>
	<td><?php echo $entry_number; ?></td>
	<td><?php echo $judging_number; ?></td>
	<td><?php echo $brew_style; ?></td>
	<td nowrap="nowrap"><?php echo $table_info_all; ?></td>
	<td><?php echo h($row_log['brewBottlesPromised']); ?></td>
	<td><?php if (($row_log['brewBottlesCounted'] !== null) && ($row_log['brewBottlesCounted'] !== "")) echo h($row_log['brewBottlesCounted']); ?></td>
</tr>
<?php
} ?>
</tbody>
</table>
<?php } else { ?>
<p class="lead"><?php echo $output_text_036; ?></p>
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
<?php foreach ($rows_log as $row_log) {

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
}
?>
</tbody>
</table>
<?php } else { ?>
<p class="lead"><?php echo $output_text_029; ?></p>
<?php }
} 
?>