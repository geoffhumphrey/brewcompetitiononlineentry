<?php 
/**
 * Module:      past_winners.sec.php 
 * Description: This module displays winners from archived database tables. 
 * 
 */

if ($section == "past_winners") {
if ($action != "print") { 
?>
<p><span class="icon"><img src="<?php echo $base_url; ?>images/printer.png"  border="0" alt="Print" /></span><a id="modal_window_link" href="<?php echo $base_url; ?>output/print.php?section=<?php echo $section; ?>&amp;dbTable=<?php echo $dbTable; ?>&amp;action=print">Print This Page</a></p>
<?php }  
if ($totalRows_archive > 0) { ?>
	<p><span class="icon"><img src="<?php echo $base_url; ?>images/award_star_gold_2.png"  /></span><span class="data">View past winners: <?php 
	do { 
		if (ltrim($dbTable, "brewing_") != $row_archive['archiveSuffix']) echo "<a href='index.php?section=past_winners&amp;dbTable=".$brewing_db_table."'>"; 
		else echo "<strong>"; echo $row_archive['archiveSuffix']; 
		if (ltrim($dbTable, "brewing_") != $row_archive['archiveSuffix']) echo "</a>"; 
		else echo "</strong>"; 
		echo "&nbsp;&nbsp;&nbsp;"; 
	} while ($row_archive = mysql_fetch_assoc($archive)); ?></span><p>
<?php }
	include (SECTIONS.'bos.sec.php');
	include (SECTIONS.'winners.sec.php'); 
}
?>
