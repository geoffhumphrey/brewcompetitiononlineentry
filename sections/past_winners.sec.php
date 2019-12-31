<?php 
/**
 * Module:      past_winners.sec.php 
 * Description: This module displays winners from archived database tables. 
 * 
 */
if ($section == "past_winners") { 
	
	if ($totalRows_archive > 0) { 
		echo "<p><span class=\"fa fa-star\"></span> ".$past_winners_text_000;
		do { 
			if (ltrim($dbTable, "brewing_") != $row_archive['archiveSuffix']) echo "<a href='index.php?section=past_winners&amp;dbTable=".$brewing_db_table."'>"; 
			else echo "<strong>"; echo $row_archive['archiveSuffix']; 
			if (ltrim($dbTable, "brewing_") != $row_archive['archiveSuffix']) echo "</a>"; 
			else echo "</strong>"; 
			echo "&nbsp;&nbsp;&nbsp;"; 
		} while ($row_archive = mysqli_fetch_assoc($archive));
		echo "</p>";
	}

	include (SECTIONS.'bos.sec.php');
	include (SECTIONS.'winners.sec.php'); 
}
?>
