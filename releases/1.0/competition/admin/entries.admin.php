<h2>Entries</h2>
<table>
<tr>
  <td class="dataList"><a href="index.php?section=admin">&laquo; Back to Admin</a></td>
  <?php if ($dbTable != "default") { ?>
  <td class="dataList"><a href="index.php?section=admin&go=archive">&laquo; Back to Archives</a></td>
  <?php } ?>
  <?php if ($dbTable == "default") { ?>
  <td class="data"><?php if ($filter != "default") echo "<a href=\"index.php?section=admin&go=entries\">View entries in all categories</a>";  else echo "&nbsp;"; ?></td>
  <?php } ?>
</tr>
<tr>
  <td class="dataHeading">Total Entries <?php if ($filter != "default") echo "In This Category"; ?>:</td>
  <td class="data"><?php echo $totalRows_log; ?></td>
</tr>
<tr>
  <td class="dataHeading">Total Entry Fees <?php if ($filter != "default") echo "for This Category"; ?>:</td>
  <td class="data">$<?php echo ($totalRows_log * $row_contest_info['contestEntryFee']); ?></td>
</tr>
</table>
<?php if ($totalRows_log > 0) { ?>
<table>
 <tr>
  <td width="25%" class="dataHeading bdr1B">Entry Name</td>
  <td class="dataHeading bdr1B">Cat./Sub. No.</td>
  <td class="dataHeading bdr1B">Category Name</td>
  <td class="dataHeading bdr1B">Subcategory Name</td>
  <td class="dataHeading bdr1B">Brewer</td>
  <td colspan="2" class="dataHeading bdr1B">Actions</td>
 </tr>
 <?php do { 
    include ('includes/style_convert.inc.php');
	mysql_select_db($database, $brewing);
	if ($row_log['brewCategory'] < 10) $fix = "0"; else $fix = "";
	$query_style = sprintf("SELECT * FROM styles WHERE brewStyleGroup = '%s' AND brewStyleNum = '%s'", $fix.$row_log['brewCategory'], $row_log['brewSubCategory']);
	$style = mysql_query($query_style, $brewing) or die(mysql_error());
	$row_style = mysql_fetch_assoc($style);
	$totalRows_style = mysql_num_rows($style);
	?>
 <tr <?php echo " style=\"background-color:$color\"";?>>
  <td width="25%" class="dataList"><?php echo $row_log['brewName']; ?></td>
  <td class="dataList"><?php echo $row_log['brewCategory'].$row_log['brewSubCategory']; ?></td>
  <td class="dataList"><?php if ($filter == "default") { ?><a href="index.php?section=admin&go=entries&filter=<?php echo $row_log['brewCategorySort']; ?>" title="See only the <?php echo $styleConvert; ?> entries"><?php } echo $styleConvert; if ($filter == "default") { ?></a><?php } ?></td>
  <td class="dataList"><?php echo $row_style['brewStyle']; ?></td>
  <td class="dataList"><?php echo $row_log['brewBrewerLastName'].", ".$row_log['brewBrewerFirstName']; ?></td>
  <td class="icon" nowrap><a href="index.php?section=brew&go=<?php echo $go; ?>&filter=<?php echo $row_log['brewBrewerID']; ?>&action=edit&id=<?php echo $row_log['id']; ?>"><img src="images/pencil.png" align="absmiddle" border="0" alt="Edit <?php echo $row_log['brewName']; ?>" title="Edit <?php echo $row_log['brewName']; ?>"></a></td>
  <td class="icon" nowrap><a href="javascript:DelWithCon('includes/process.inc.php?section=brew&go=<?php echo $go; ?>&filter=<?php echo $filter; ?>&dbTable=brewing&action=delete','id',<?php echo $row_log['id']; ?>,'Are you sure you want to delete the entry called <?php echo $row_log['brewName']; ?>? This cannot be undone.');"><img src="images/bin_closed.png" align="absmiddle" border="0" alt="Delete <?php echo $row_log['brewName']; ?>" title="Delete <?php echo $row_log['brewName']; ?>"></a></td>
 </tr>
  <?php if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
  <?php } while ($row_log = mysql_fetch_assoc($log)); ?>
 <tr>
 	<td colspan="7" class="bdr1T">&nbsp;</td>
 </tr>
</table>
<?php } else echo "<div class=\"error\">There are no entires.</div>"; ?>