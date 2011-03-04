<?php if ($section == "past_winners") { ?>
<?php if ($action != "print") { ?>
<p><span class="icon"><img src="images/printer.png"  border="0" alt="Print" /></span><a href="#" onClick="window.open('output/print.php?section=<?php echo $section; ?>&amp;dbTable=<?php echo $dbTable; ?>&amp;action=print','','height=600,width=800,toolbar=no,resizable=yes,scrollbars=yes'); return false;">Print This Page</a></p>
<?php }  
}
if ($totalRows_archive > 0) { ?>
<p><span class="icon"><img src="images/award_star_gold_2.png"  /></span><span class="data">View past winners: <?php do { if (ltrim($dbTable, "brewing_") != $row_archive['archiveSuffix']) echo "<a href='index.php?section=past_winners&amp;dbTable=brewing_".$row_archive['archiveSuffix']."'>"; else echo "<strong>"; echo $row_archive['archiveSuffix']; if (ltrim($dbTable, "brewing_") != $row_archive['archiveSuffix']) echo "</a>"; else echo "</strong>"; echo "&nbsp;&nbsp;&nbsp;"; } while ($row_archive = mysql_fetch_assoc($archive)); ?></span><p>
<?php } 
if ($section == "past_winners")
include (SECTIONS.'bos.sec.php');
include (SECTIONS.'winners.sec.php'); 
?>
