<?php
include (DB.'payments.db.php');
if ($totalRows_payments > 0) {
?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : true,
			"sPaginationType" : "full_numbers",
			"bLengthChange" : true,
			"iDisplayLength" : <?php echo $limit; ?>,
			"sDom": 'fprtp',
			"bStateSave" : false,
			"aaSorting": [[0,'asc']],
			"aoColumns": [
				null,
				null,
				null,
				null,
				null,
				null,
				null,
				{ "asSorting": [  ] }
				]
			} );
		} );
	</script>
<p class="lead"><?php echo $_SESSION['contestName'].": PayPal Payments"; ?></p>
<table class="table table-responsive table-striped table-bordered" id="sortable">
<thead>
	<tr>
		<th nowrap>Payer <span class="hidden-xs hidden-sm">Name</span></th>
		<th class="hidden-xs">Item</th>
		<th>Am<span class="hidden-xs">ount</span></th>
		<th>St<span class="hidden-xs">atus</span></th>
		<th nowrap><span class="hidden-xs">Transaction</span> ID</th>
		<th class="hidden-xs"><span class="hidden-sm">For</span> Entries...</th>
		<th>Date</th>
		<th>Act<span class="hidden-xs">ions</span></th>
	</tr>
</thead>
 <tbody>
 <?php do { ?>
 <tr>
  <td><?php echo ucwords($row_payments['last_name']).", ".ucwords($row_payments['first_name']); ?></td>
  <td class="hidden-xs"><?php echo ucwords($row_payments['item_name']); ?></td>
  <td><?php echo $row_payments['payment_gross']." ".$row_payments['currency_code']; ?></td>
  <td><?php echo $row_payments['payment_status']; ?></td>
  <td><?php echo $row_payments['txn_id']; ?></td>
  <td class="hidden-xs"><?php echo str_replace("-",", ",$row_payments['payment_entries']); ?></td>
  <td><?php echo getTimeZoneDateTime($_SESSION['prefsTimeZone'], $row_payments['payment_time'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "short", "date-time-no-gmt"); ?></td>
  <td nowrap="nowrap">
  <a class="hide-loader" href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=<?php echo $payments_db_table; ?>&amp;action=delete&amp;id=<?php echo $row_payments['id']; ?>" data-toggle="tooltip" data-placement="top" title="Delete <?php echo $row_payments['item_name']; ?>" data-confirm="Are you sure you want to delete <?php echo $row_payments['item_name']; ?>? This cannot be undone."><span class="fa fa-lg fa-trash-o"></span></a>
  </td>
 </tr>
<?php } while($row_payments = mysqli_fetch_assoc($payments)) ?>
 </tbody>
</table>
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
<?php } else { ?>
<input type="hidden" name="relocate" value="<?php echo relocate($base_url."index.php?section=admin&go=payments","default",$msg,$id); ?>">
<?php } ?>
</form>
<?php } else { ?>
<p>No payments have been recorded in the database.</p>
<?php } ?>