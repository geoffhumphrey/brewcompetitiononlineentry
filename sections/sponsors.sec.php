<?php
/**
 * Module:      sponsors.sec.php 
 * Description: This module displays sponsors information housed in the
 *              sponsors database table. 
 * 
 */

include(DB.'sponsors.db.php');


if ($action != "print") { ?>
<?php if (($action != "print") && ($msg != "default")) echo $msg_output; ?>
<p><span class="icon"><img src="<?php echo $base_url; ?>images/printer.png"  border="0" alt="Print" /></span><a id="modal_window_link" class="data" href="<?php echo $base_url; ?>output/print.php?section=<?php echo $section; ?>&amp;action=print" title="Print Sponsors">Print This Page</a></p>
<?php } ?>
<table>
  <tr>
    <?php
	$sponsors_endRow = 0;
	if ($action == "print") $sponsors_columns = 3; else $sponsors_columns = 4;  // number of columns
	$sponsors_hloopRow1 = 0; // first row flag
	do {
    	if (($sponsors_endRow == 0) && ($sponsors_hloopRow1++ != 0)) echo "<tr>";
    ?>
    <td class="looper_large">
    <p><?php if ($row_sponsors['sponsorURL'] != "") { ?><a href="<?php echo $row_sponsors['sponsorURL']; ?>" target="_blank"><?php } echo $row_sponsors['sponsorName']; ?><?php if ($row_sponsors['sponsorURL'] != "") { ?></a><?php } if ($row_sponsors['sponsorLocation'] != "") echo "<br>".$row_sponsors['sponsorLocation']; ?></p>
    <p><?php if ($row_sponsors['sponsorURL'] != "") { ?><a href="<?php echo $row_sponsors['sponsorURL']; ?>" target="_blank"><?php } ?><img src="<?php if (($row_sponsors['sponsorImage'] != "") && (file_exists($_SERVER['DOCUMENT_ROOT'].$sub_directory.'/user_images/'.$row_sponsors['sponsorImage']))) echo $base_url."user_images/".$row_sponsors['sponsorImage']; /* elseif ($_SESSION['contestLogo'] != "") echo $base_url."user_images/".$_SESSION['contestLogo']; */ else echo $base_url."images/no_image_large.png"; ?>" width="<?php echo $_SESSION['prefsSponsorLogoSize']; ?>" border="0" /><?php if ($row_sponsors['sponsorURL'] != "") { ?></a><?php } ?></p>
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

