<?php require(DB.'archive.db.php'); 

$table_header1 = "Users";
$table_header2 = "Participants";
$table_header3 = "Entries";
$table_header4 = "Tables";
$table_header5 = "Scores";
$table_header6 = "BOS";
$table_header7 = "Actions";

?>
<h2>Archived Competition Data</h2>
<div class="adminSubNavContainer">
	<span class="adminSubNav">
		<span class="icon"><img src="<?php echo $base_url; ?>images/arrow_left.png" alt="Back"></span><?php if ($action == "default") { ?><a href="<?php echo $base_url; ?>index.php?section=admin">Back to Admin Dashboard</a><?php } else { ?><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables">Back to Tables List</a><?php } ?>
	</span>
</div>
<?php if (HOSTED) { ?>
<p>Due to server storage limitations, archiving of hosted BCOE&amp;M accounts is not available. To utilize the software for a new competition or simply to clear the database of data, use the links below.</p> 
<form action="<?php echo $base_url; ?>includes/archive.inc.php" method="post" name="form1"  onsubmit="return confirm('Are you sure you want to clear the current competition\'s data?\nThis CANNOT be undone.');">
<h3>Option 1</h3>
<p>The following option clears all participant, entry, judging, and scoring data. Provides a clean slate.</p>
<p><input name="submit" type="submit" class="button" value="Clear All Participant, Entry, Judging, and Scoring Data"></p>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>
<form action="<?php echo $base_url; ?>includes/archive.inc.php?filter=participant" method="post" name="form1"  onsubmit="return confirm('Are you sure you want to clear the current competition\'s entry and scoring data ONLY?\nThis CANNOT be undone.');">
<h3>Option 2</h3>
<p>The following option clears all entry, judging, and scoring data, but retains the participant data. Useful if you want don't want to have participants create new account profiles.</p>
<p><input name="submit" type="submit" class="button" value="Clear Entry, Judging, and Scoring Data Only"></p>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>
<?php } else { ?>
<form action="<?php echo $base_url; ?>includes/archive.inc.php" method="post" name="form1"  onsubmit="return confirm('Are you sure you want to archive the current competition\'s data?\nThis CANNOT be undone.');">
<p>To archive the current entry, table, scoring, and result data, provide a name of the archive.</p>
<p><input name="archiveSuffix" type="text" size="15" value="<?php echo date('Y'); ?>"> * alpha numeric characters only - all others will be omitted.</p>
<p>If you wish to keep the current participant data (useful if you want don't want to have participants create new account profiles), indicate so below.</p>
<p><input name="keepParticipants" type="checkbox" value="Y" />Keep participant information?</p>
<p><input name="submit" type="submit" class="button" value="Archive Now"></p>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default",$msg,$id); ?>">
</form>
<?php if ($totalRows_archive > 0) { ?>

<script type="text/javascript" language="javascript">
	 $(document).ready(function() {
		$('#sortable').dataTable( {
			"bPaginate" : true,
			"sPaginationType" : "full_numbers",
			"bLengthChange" : false,
			"iDisplayLength" :  <?php echo round($_SESSION['prefsRecordPaging']); ?>,
			"sDom": 'ifrtip',
			"bStateSave" : false,
			"aaSorting": [[0,'asc']],
			"bProcessing" : true,
			"aoColumns": [
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


<h3>Archives</h3>
<table class="dataTable" id="sortable">
<thead>
    <th width="10%" class="dataHeading bdr1B"><?php echo $table_header1; ?></th>
    <th width="10%" class="dataHeading bdr1B"><?php echo $table_header2; ?></th>
    <th width="10%" class="dataHeading bdr1B"><?php echo $table_header3; ?></th>
    <th width="10%" class="dataHeading bdr1B"><?php echo $table_header4; ?></th>
    <th width="10%" class="dataHeading bdr1B"><?php echo $table_header5; ?></th>
    <th width="10%" class="dataHeading bdr1B"><?php echo $table_header6; ?></th>
    <th class="dataHeading bdr1B"><?php echo $table_header7; ?></th>
</thead>
<tbody>
  <?php do { ?>
  <tr>
    <td nowrap="nowrap"><span class="icon"><img src="<?php echo $base_url; ?>images/tick.png"  border="0"></span><?php echo $row_archive['archiveSuffix']; ?></td>
    <td class="data" nowrap="nowrap">
    <?php 
	$db = $prefix."brewer_".$row_archive['archiveSuffix'];
	$count = get_archive_count($db);
	if ($count > 0) { ?>
    	<a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants&amp;dbTable=<?php echo $db;  ?>"><?php echo $row_archive['archiveSuffix']; ?></a> (<?php echo $count; ?>)
    <?php } else echo "Not Archived"; ?>
    </td>
    <td class="data" nowrap="nowrap">
    <?php 
	$db = $prefix."brewing_".$row_archive['archiveSuffix'];
	$count = get_archive_count($db);
	if ($count > 0) { ?>
    	<a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries&amp;dbTable=<?php echo $db; ?>"><?php echo $row_archive['archiveSuffix']; ?></a> (<?php echo $count; ?>)
    <?php } ?>
    </td>
    <td class="data" nowrap="nowrap">
    <?php 
	$db = $prefix."judging_tables_".$row_archive['archiveSuffix'];
	if (table_exists($db)) {
	$count = get_archive_count($db);
	if ($count > 0) { ?>
    	<a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables&amp;dbTable=<?php echo $db; ?>"><?php echo $row_archive['archiveSuffix']; ?></a> (<?php echo $count; ?>)
    <?php } 
	}?>
    </td>
    <td class="data" nowrap="nowrap">
    <?php 
	$db = $prefix."judging_scores_".$row_archive['archiveSuffix'];
	if (table_exists($db)) {
	if (get_archive_count($db) > 0) { ?>
    	<a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores&amp;dbTable=<?php echo $db."&amp;filter=".$row_archive['archiveSuffix']; ?>"><?php echo $row_archive['archiveSuffix']; ?></a>
    <?php } 
	}
	?>
    </td>
    <td class="data" nowrap="nowrap">
    <?php 
	$db = $prefix."judging_scores_bos_".$row_archive['archiveSuffix'];
	if (table_exists($db)) {
	if (get_archive_count($db) > 0) { ?>
    	<a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_scores_bos&amp;dbTable=<?php echo $db."&amp;filter=".$row_archive['archiveSuffix']; ?>"><?php echo $row_archive['archiveSuffix']; ?></a>
    </td>
    <?php } 
	}
	?>
    <td class="dataList"><span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=<?php echo $section; ?>&amp;go=<?php echo $go; ?>&amp;filter=<?php echo $row_archive['archiveSuffix']; ?>&amp;dbTable=<?php echo $archive_db_table; ?>&amp;action=delete','id',<?php echo $row_archive['id']; ?>,'Are you sure you want to delete the archive called <?php echo $row_archive['archiveSuffix']; ?>? This cannot be undone.');"><img src="<?php echo $base_url; ?>images/bin_closed.png"  border="0" alt="Delete <?php echo $row_archive['archiveSuffix']; ?> Archive?" title="Delete <?php echo $row_archive['archiveSuffix']; ?> Archive?"></a></span></td>
  </tr>
  <?php } while ($row_archive = mysql_fetch_assoc($archive)); ?>
</tbody>
</table>
<?php } // end else ?>
<?php } ?>