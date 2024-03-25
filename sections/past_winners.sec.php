<?php 
/**
 * Module:      past_winners.sec.php 
 * Description: This module displays winners from archived database tables. 
 * 
 *	TO DO: 
	 - General winner display 
		 - store display method in DB and retrieve as part of query and in loop
		 - don't display the archived data unless the display method is defined
		 - provide interface for admin to define the display method (legacy)
		 - automatically capture current display method when archiving
		 - display current archive name in <h2>
	 - BOS display
	 - Best Brewer and Best Club display
 *
 */

// Redirect if directly accessed
if ((!isset($_SESSION['prefs'.$prefix_session])) || ((isset($_SESSION['prefs'.$prefix_session])) && (!isset($base_url)))) {
 $redirect = "../../index.php";
 $redirect_go_to = sprintf("Location: %s", $redirect);
 header($redirect_go_to);
 exit();
}

include (DB.'archive.db.php');

if ($section == "past_winners") { 
	 
	if ($totalRows_archive > 0) { 
		echo "<p><span class=\"fa fa-star\"></span> ".$past_winners_text_000." ";
		do { 

			if ($go != $row_archive['archiveSuffix']) echo "<a href='index.php?section=past_winners&amp;go=".$row_archive['archiveSuffix']."'>"; 
			else echo "<strong>"; 
			echo $row_archive['archiveSuffix']; 
			if ($go != $row_archive['archiveSuffix']) echo "</a>"; 
			else echo "</strong>"; 
			echo "&nbsp;&nbsp;&nbsp;"; 
		} while ($row_archive = mysqli_fetch_assoc($archive));
		echo "</p>";
	}

	if ($go != "default") {
		//include (SECTIONS.'bos.sec.php');

		$special_best_info_db_table = $prefix."special_best_info_".$go;
		$special_best_data_db_table = $prefix."special_best_data_".$go;
		$judging_tables_db_table = $prefix."judging_tables_".$go;
		$judging_assignments_db_table = $prefix."judging_assignments_".$go;
		$judging_flights_db_table = $prefix."judging_flights_".$go;
		$style_types_db_table = $prefix."style_types_".$go;
		$judging_scores_db_table = $prefix."judging_scores_".$go;
		$judging_scores_bos_db_table = $prefix."judging_scores_bos_".$go;
		$brewing_db_table = $prefix."brewing_".$go;
		$brewer_db_table = $prefix."brewer_".$go;
		$staff_db_table = $prefix."staff_".$go;

		$query_scored_entries = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE scorePlace IS NOT NULL", $prefix."judging_scores_".$go);
		$scored_entries = mysqli_query($connection,$query_scored_entries) or die (mysqli_error($connection));
		$row_scored_entries = mysqli_fetch_assoc($scored_entries);

		$dbTable = $go;
		$winner_display_method = 0; // TODO: get from query
		$_SESSION['prefsWinnerMethod'] = $winner_display_method; // TODO: change in appropriate query scripts (scores.db.php)

		include (DB.'winners.db.php');
		if ($winner_display_method == 0) include (SECTIONS.'winners.sec.php');
		if ($winner_display_method == 1) include (SECTIONS.'winners_category.sec.php');
		if ($winner_display_method == 2) include (SECTIONS.'winners_subcategory.sec.php');
		
	}
}
?>
