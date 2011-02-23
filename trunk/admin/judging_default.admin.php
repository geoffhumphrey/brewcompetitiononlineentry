<?php 
mysql_select_db($database, $brewing);
$query_judging_locs = "SELECT * FROM judging_locations";
$judging_locs = mysql_query($query_judging_locs, $brewing) or die(mysql_error());
$row_judging_locs = mysql_fetch_assoc($judging_locs);
$totalRows_judging_locs = mysql_num_rows($judging_locs);
if ($totalRows_judging_locs > 0) { ?>
<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : true,
			"sPaginationType" : "full_numbers",
			"bLengthChange" : true,
			"iDisplayLength" : <?php echo round($row_prefs['prefsRecordPaging']); ?>,
			"sDom": 'irtip',
			"bStateSave" : false,
			"aaSorting": [[0,'asc']],
			"bProcessing" : true,
			"aoColumns": [
				null,
				null,
				null,
				null,
				{ "asSorting": [  ] },
				]
			} );
		} );
</script>
<table class="dataTable" id="sortable">
<thead>
 <tr>
  <th class="dataHeading bdr1B">Name</th>
  <th class="dataHeading bdr1B">Date</th>
  <th class="dataHeading bdr1B">Start Time</th>
  <th class="dataHeading bdr1B">Address</th>
  <th class="dataHeading bdr1B">Actions</th>
 </tr>
</thead>
<tbody>
 <?php do { ?>
 <tr>
  <td width="25%" class="dataList"><?php echo $row_judging_locs['judgingLocName']; ?></td>
  <td width="15%" class="dataList"><?php echo dateconvert($row_judging_locs['judgingDate'], 2); ?></td>
  <td width="15%" class="dataList"><?php echo $row_judging_locs['judgingTime']; ?></td>
  <td width="30%" class="dataList"><?php echo $row_judging_locs['judgingLocation']; ?></td>
  <td class="dataList">
  <span class="icon"><a href="index.php?section=admin&amp;go=<?php echo $go; ?>&amp;action=edit&amp;id=<?php echo $row_judging_locs['id']; ?>"><img src="images/pencil.png"  border="0" alt="Edit <?php echo $row_judging_locs['judgingLocName']; ?>" title="Edit <?php echo $row_judging_locs['judgingLocName']; ?>"></a></span><span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=admin&amp;go=<?php echo $go; ?>&amp;dbTable=judging&amp;action=delete','id',<?php echo $row_judging_locs['id']; ?>,'Are you sure you want to delete the <?php echo $row_judging_locs['judgingLocName']; ?> location?\nThis cannot be undone and will affect all judges and stewards who indicated this location as a preference.');"><img src="images/bin_closed.png"  border="0" alt="Delete <?php echo $row_judging_locs['judgingLocName']; ?>" title="Delete <?php echo $row_judging_locs['judgingLocName']; ?>"></a></span></td>
 </tr>
  <?php } while($row_judging_locs = mysql_fetch_assoc($judging_locs)) ?>
</tbody>
</table>
<?php } else echo "<p>No judging dates/locations have been specified.</p>"; ?>