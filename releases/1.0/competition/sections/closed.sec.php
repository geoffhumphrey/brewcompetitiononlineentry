<h2>Thanks To All Who Entered!</h2>
<p>There were <?php echo $totalRows_log; ?> entries and <?php echo $totalRows_brewers; ?> registered brewers in the <?php echo $row_contest_info['contestName']; ?>.</p>
<p>Here is a complete list of all the entries we received.  Join us again next time!</p>
<?php if ($totalRows_log > 0) { ?>
<table>
 <tr>
  <td width="25%" class="dataHeading bdr1B">Entry Name</td>
  <td class="dataHeading bdr1B">Category</td>
  <td class="dataHeading bdr1B">Brewer</td>
  <td class="dataHeading bdr1B">Club</td>
 </tr>
 <?php do { 
    include ('includes/style_convert.inc.php');
	mysql_select_db($database, $brewing);
	if ($row_log['brewCategory'] < 10) $fix = "0"; else $fix = "";
	$query_style = sprintf("SELECT * FROM styles WHERE brewStyleGroup = '%s' AND brewStyleNum = '%s'", $fix.$row_log['brewCategory'], $row_log['brewSubCategory']);
	$style = mysql_query($query_style, $brewing) or die(mysql_error());
	$row_style = mysql_fetch_assoc($style);
	
	$query_club = sprintf("SELECT brewerClubs FROM brewer WHERE id = '%s'", $row_log['brewBrewerID']);
	$club = mysql_query($query_club, $brewing) or die(mysql_error());
	$row_club = mysql_fetch_assoc($club);
	?>
 <tr <?php echo " style=\"background-color:$color\"";?>>
  <td width="25%" class="dataList"><?php echo $row_log['brewName']; ?></td>
  <td class="dataList"><?php echo $styleConvert.": ".$row_style['brewStyle']." (".$row_log['brewCategory'].$row_log['brewSubCategory'].")"; ?></td>
  <td class="dataList"><?php echo $row_log['brewBrewerFirstName']." ".$row_log['brewBrewerLastName']; ?></td>
  <td class="dataList"><?php echo $row_club['brewerClubs']; ?></td>
 </tr>
  <?php if ($color == $color1) { $color = $color2; } else { $color = $color1; } ?>
  <?php } while ($row_log = mysql_fetch_assoc($log)); ?>
  <tr>
  	<td class="bdr1T" colspan="4">&nbsp;</td>
  </tr>
</table>
<?php } else echo "<div class=\"error\">There are no entires.</div>"; ?>