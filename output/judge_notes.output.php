<?php
include(DB.'brewer.db.php');
include(LIB.'output.lib.php');
?>
<div class="page-header">
	<h1><?php echo $_SESSION['contestName']; ?> Judge/Steward Notes to Organizers</h1>
</div>
<?php if ($totalRows_brewer > 0) { ?>
<p class="lead">The following are the notes to organizers entered by judges.</p>
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
	<th>Judge Name</th>
	<th>Note</th>
</tr>
</thead>
<tbody>
<?php do { ?>
<tr>
	<td><?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?></td>
	<td><?php echo $row_brewer['brewerJudgeNotes']; ?></td>
</tr>
<?php } while ($row_brewer = mysql_fetch_assoc($brewer)); ?>
</tbody>
</table>
<?php } 
else { ?>
<p class="lead">No participants provided notes to organizers.</p>
<?php } ?>