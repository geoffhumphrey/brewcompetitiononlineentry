<?php 

// Redirect if directly accessed without authenticated session
if ((!isset($_SESSION['loginUsername'])) || ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] > 1))) {
    $redirect = "../../403.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

$section = "participant_entry_list";
include (DB.'brewer.db.php');
include (LIB.'admin.lib.php');
include (LIB.'output.lib.php');

$table_body = "";

do {
	
	include (DB.'output_participant_summary.db.php');
	
	$entry_numbers = array();
	$judging_numbers = array();
	unset($entry_numbers);
	unset($judging_numbers);
	
	if ($totalRows_log > 0) {
		
		$judging_numbers_output = "";
		$entry_numbers_output = "";
		
		do {		
		
			$entry_numbers[] = sprintf("%06s",$row_log['id']);
			$judging_numbers[] = sprintf("%06s",$row_log['brewJudgingNumber']);
			
		} while ($row_log = mysqli_fetch_assoc($log));
		
		$entry_numbers_output = implode(", ",$entry_numbers);
		$judging_numbers_output = implode(", ",$judging_numbers);
		
		$table_body .= "<tr>";
		$table_body .= "<td width=\"1%\" nowrap>".$row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']."</td>";
		$table_body .= "<td>".$entry_numbers_output."</td>";
		$table_body .= "<td>".$judging_numbers_output."</td>";
		$table_body .= "</tr>";
		
 	} // END entries section

} while ($row_brewer = mysqli_fetch_assoc($brewer));

?>    
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
			{ "asSorting": [  ] }
			]
		} );
	} );
</script>
<h1>Participant Entries</h1>
<p class="lead">The following lists each participant's entries and associated judging number as assigned in the system. <small>For instance, this list could be useful for distributing scoresheets sorted by number after an awards ceremony.</small></p>
<table class="table table-bordered table-striped" id="sortable_entries<?php echo $row_brewer['id']; ?>">
<thead>
<tr>
	<th><?php echo $label_name; ?></th>
	<th><?php echo $label_entry_number; ?></th>
	<th><?php echo $label_judging_number; ?></th>
</tr>
</thead>
<tbody>
<?php echo $table_body; ?>
</tbody>
</table>
