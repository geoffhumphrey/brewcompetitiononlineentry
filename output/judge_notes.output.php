<?php
include (DB.'brewer.db.php');
include (LIB.'output.lib.php');
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
<?php do { ?>
<tr>
	<td><?php echo $row_brewer['brewerLastName'].", ".$row_brewer['brewerFirstName']; ?></td>
	<td><?php echo $row_brewer['brewerJudgeNotes']; ?></td>
</tr>
<?php } while ($row_brewer = mysqli_fetch_assoc($brewer)); ?>
</tbody>
</table>
<?php } 
else { ?>
<p class="lead"><?php echo $output_text_013; ?></p>
<?php } ?>