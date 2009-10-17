<h2>Archive Competition Data</h2>
<p>To archive the current user, participant, and entry information, please provide a suffix to append to the database table names. For example, if your competition is held yearly, you could use the year.</p>
<form action="admin/archive.php" method="post" name="form1"  onsubmit="return confirm('Are you sure you want to archive the current competition\'s participant and entry information?\nThis cannot be undone.');">
<input name="archiveSuffix" type="text" size="10" value="<?php echo date('Y'); ?>">
<input name="submit" type="submit" value="Submit">
</form>
<?php if ($totalRows_archive > 0) { ?>
<h2>Archived Data</h2>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
  	<td class="dataHeading bdr1B">&nbsp;</td>
    <td class="dataHeading bdr1B">Users</td>
    <td class="dataHeading bdr1B">Participant Info</td>
    <td class="dataHeading bdr1B" colspan="2">Entries</td>
  </tr>
  <?php do { ?>
  <tr <?php echo " style=\"background-color:$color\"";?>>
    <td class="dataList">Suffixes:</td>
    <td class="dataList"><?php echo $row_archive['archiveSuffix']; ?></td>
    <td class="dataList"><a href="index.php?section=admin&go=participants&dbTable=<?php echo $row_archive['archiveBrewerTableName']; ?>"><?php echo $row_archive['archiveSuffix']; ?></a></td>
    <td class="dataList"><a href="index.php?section=admin&go=entries&dbTable=<?php echo $row_archive['archiveBrewingTableName']; ?>"><?php echo $row_archive['archiveSuffix']; ?></a></td>
    <td class="icon" nowrap><a href="javascript:DelWithCon('includes/process.inc.php?section=admin&go=archive&dbTable=archive&filter=<?php echo $row_archive['archiveSuffix']; ?>&action=delete','id',<?php echo $row_archive['id']; ?>,'Are you sure you want to delete the archive called <?php echo $row_archive['archiveSuffix']; ?>? This cannot be undone.');"><img src="images/bin_closed.png" align="absmiddle" border="0" alt="Delete <?php echo ltrim($row_archive['archiveBrewingTableName'], "brewing_"); ?> Information?" title="Delete <?php echo $row_archive['archiveSuffix']; ?> Information?"></a></td>
  </tr>
  <?php if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
  <?php } while ($row_archive = mysql_fetch_assoc($archive)); ?>
  <tr>
 	<td colspan="5" class="bdr1T">&nbsp;</td>
 </tr>
</table>
<?php } ?>