<div id="header">	
	<div id="header-inner"><h1><?php echo $row_contest_info['contestName']; ?> Sponsors</h1></div>
</div>
<?php if ($action != "print") { ?>
<p><span class="icon"><img src="images/printer.png" align="absmiddle" /></span><a class="data" href="#" onClick="window.open('print.php?section=<?php echo $section; ?>&action=print','','height=600,width=800,toolbar=no,resizable=yes,scrollbars=yes'); return false;">Print This Page</a></p>
<?php } ?>

<table>
  <tr>
    <?php
	$sponsors_endRow = 0;
	$sponsors_columns = 4; // number of columns
	$sponsors_hloopRow1 = 0; // first row flag
	do {
    	if (($sponsors_endRow == 0) && ($sponsors_hloopRow1++ != 0)) echo "<tr>";
    ?>
    <td class="looper_large">
    <p><?php if ($row_sponsors['sponsorURL'] != "") { ?><a href="<?php echo $row_sponsors['sponsorURL']; ?>" target="_blank"><?php } echo $row_sponsors['sponsorName']; ?><?php if ($row_sponsors['sponsorURL'] != "") { ?></a><?php } if ($row_sponsors['sponsorLocation'] != "") echo "<br>".$row_sponsors['sponsorLocation']; ?></p>
    <p><?php if ($row_sponsors['sponsorURL'] != "") { ?><a href="<?php echo $row_sponsors['sponsorURL']; ?>" target="_blank"><?php } ?><img src="<?php if (($row_sponsors['sponsorImage'] !="") && (file_exists('user_images/'.$row_sponsors['sponsorImage']))) echo "user_images/".$row_sponsors['sponsorImage']; /* elseif ($row_contest_info['contestLogo'] != "") echo "user_images/".$row_contest_info['contestLogo']; else echo "images/no_image_large.png"; */  ?>" width="<?php echo $row_prefs['prefsSponsorLogoSize']; ?>" border="0" /><?php if ($row_sponsors['sponsorURL'] != "") { ?></a><?php } ?></p>
    <?php if ($row_sponsors['sponsorText'] != "") echo "<p>".$row_sponsors['sponsorText']."</p>"; ?>
    </td>
    <?php  $sponsors_endRow++;
	if ($sponsors_endRow >= $sponsors_columns) {
  	?>
  </tr>
  <?php
 	$sponsors_endRow = 0;
  		}
	} while ($row_sponsors = mysql_fetch_assoc($sponsors));
	if ($sponsors_endRow != 0) {
	while ($sponsors_endRow < $sponsors_columns) {
    echo("<td>&nbsp;</td>");
    $sponsors_endRow++;
	}
echo("</tr>");
}
?>
</table>
