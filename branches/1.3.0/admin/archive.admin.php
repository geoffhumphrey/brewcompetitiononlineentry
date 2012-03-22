<h2>Archive Competition Data</h2>
<div class="adminSubNavContainer">
	<span class="adminSubNav">
		<span class="icon"><img src="images/arrow_left.png" alt="Back"></span><?php if ($action == "default") { ?><a href="index.php?section=admin">Back to Admin</a><?php } else { ?><a href="index.php?section=admin&amp;go=judging_tables">Back to Tables List</a><?php } ?>
	</span>
</div>
<p>To archive the current user, participant, entry, table, scoring, and result data, please provide a name of the archive. For example, if your competition is held yearly, you could use the year.</p>
<form action="includes/archive.inc.php" method="post" name="form1"  onsubmit="return confirm('Are you sure you want to archive the current competition\'s data?\nThis CANNOT be undone.');">
<p><input name="archiveSuffix" type="text" size="15" value="<?php echo date('Y'); ?>"></p>
<p><input name="submit" type="submit" class="button" value="Archive Now"></p>
<input type="hidden" name="relocate" value="<?php echo relocate($_SERVER['HTTP_REFERER'],"default"); ?>">
</form>
<?php if ($totalRows_archive > 0) { ?>
<h3>Archives</h3>
<table class="dataTable" id="sortable">
<thead>
    <th width="10%" class="dataHeading bdr1B">Users</th>
    <th width="10%" class="dataHeading bdr1B">Participants</th>
    <th width="10%" class="dataHeading bdr1B">Entries</th>
    <th width="10%" class="dataHeading bdr1B">Tables</th>
    <th width="10%" class="dataHeading bdr1B">Scores</th>
    <th width="10%" class="dataHeading bdr1B">BOS</th>
    <th class="dataHeading bdr1B">Actions</th>
</thead>
<tbody>
  <?php do { ?>
  <tr <?php echo " style=\"background-color:$color\"";?>>
    <td class="dataList" nowrap="nowrap"><span class="icon"><img src="images/tick.png"  border="0"></span><?php echo $row_archive['archiveSuffix']; ?></td>
    <td class="dataList" nowrap="nowrap"><span class="icon"><img src="images/monitor.png"  border="0"></span><a href="index.php?section=admin&amp;go=participants&amp;dbTable=brewer_<?php echo $row_archive['archiveSuffix']; ?>"><?php echo $row_archive['archiveSuffix']; ?></a></td>
    <td class="dataList" nowrap="nowrap"><span class="icon"><img src="images/monitor.png"  border="0"></span><a href="index.php?section=admin&amp;go=entries&amp;dbTable=brewing_<?php echo $row_archive['archiveSuffix']; ?>"><?php echo $row_archive['archiveSuffix']; ?></a></td>
    <td class="dataList" nowrap="nowrap"><span class="icon"><img src="images/monitor.png"  border="0"></span><a href="index.php?section=admin&amp;go=judging_tables&amp;dbTable=judging_tables_<?php echo $row_archive['archiveSuffix']; ?>"><?php echo $row_archive['archiveSuffix']; ?></a></td>
    <td class="dataList" nowrap="nowrap"><span class="icon"><img src="images/monitor.png"  border="0"></span><a href="index.php?section=admin&amp;go=judging_scores&amp;dbTable=judging_scores_<?php echo $row_archive['archiveSuffix']; ?>"><?php echo $row_archive['archiveSuffix']; ?></a></td>
    <td class="dataList" nowrap="nowrap"><span class="icon"><img src="images/monitor.png"  border="0"></span><a href="index.php?section=admin&amp;go=judging_scores_bos&amp;dbTable=judging_scores_bos_<?php echo $row_archive['archiveSuffix']; ?>"><?php echo $row_archive['archiveSuffix']; ?></a></td>
    <td class="dataList"><span class="icon"><a href="javascript:DelWithCon('includes/process.inc.php?section=<?php echo $section; ?>&amp;go=<?php echo $go; ?>&amp;filter=<?php echo $row_archive['archiveSuffix']; ?>&amp;dbTable=archive&amp;action=delete','id',<?php echo $row_archive['id']; ?>,'Are you sure you want to delete the archive called <?php echo $row_archive['archiveSuffix']; ?>? This cannot be undone.');"><img src="images/bin_closed.png"  border="0" alt="Delete <?php echo $row_archive['archiveSuffix']; ?> Archive?" title="Delete <?php echo $row_archive['archiveSuffix']; ?> Archive?"></a></span></td>
  </tr>
  <?php if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
  <?php } while ($row_archive = mysql_fetch_assoc($archive)); ?>
</tbody>
</table>
<?php } ?>