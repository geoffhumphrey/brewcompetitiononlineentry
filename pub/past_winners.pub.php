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


include (DB.'archive.db.php');

// Query the archive table for preferences
$query_archive_prefs = sprintf("SELECT * FROM %s WHERE archiveSuffix='%s'",$prefix."archive", $filter);
$archive_prefs = mysqli_query($connection,$query_archive_prefs) or die (mysqli_error($connection));
$row_archive_prefs = mysqli_fetch_assoc($archive_prefs);
$totalRows_archive_prefs = mysqli_num_rows($archive_prefs);

$winner_method = $row_archive_prefs['archiveWinnerMethod'];
$style_set = $row_archive_prefs['archiveStyleSet'];
$judging_scores_db_table = $prefix."judging_scores_".$filter;
$brewing_db_table = $prefix."brewing_".$filter;
$brewer_db_table = $prefix."brewer_".$filter;

	if (!empty($archive_alert_display)) {

		include (DB.'score_count.db.php');

	    $archive_alert_button = "<div class=\"d-grid mb-3 mt-3\">";
	    $archive_alert_button .= "<button class=\"btn btn-dark btn-lg d-block d-sm-block d-md-none\" type=\"button\" data-bs-toggle=\"offcanvas\" data-bs-target=\"#archive-list\" aria-controls=\"archive-list\">";
	    $archive_alert_button .= "<i class=\"fa fa-trophy me-2 text-gold\"></i>";
	    $archive_alert_button .= ucwords(rtrim($past_winners_text_000, ":"));
	    $archive_alert_button .= "</button>";
	    $archive_alert_button .= "</div>";

	    
	    $archive_alert_button .= "<button class=\"btn btn-dark btn-lg float-end ms-4 d-none d-sm-none d-md-block\" type=\"button\" data-bs-toggle=\"offcanvas\" data-bs-target=\"#archive-list\" aria-controls=\"archive-list\">";
	    $archive_alert_button .= "<i class=\"fa fa-trophy me-2 text-gold\"></i>";
	    $archive_alert_button .= ucwords(rtrim($past_winners_text_000, ":"));
	    $archive_alert_button .= "</button>";
	    

	    echo $archive_alert_button;
	    if ($_SESSION['prefsProEdition'] == 1) $entry_count_text = sprintf("<%s <strong class=\"text-success\">%s</strong> %s",$judge_closed_001,get_participant_count('default',$filter),$judge_closed_003);
	    else $entry_count_text =  sprintf("%s <strong class=\"text-success\">%s</strong> %s <strong class=\"text-success\">%s</strong> %s",$judge_closed_001,get_entry_count('received',$filter),$judge_closed_002,get_participant_count('default',$filter),$judge_closed_003);
	    echo sprintf("<p class=\"lead\">%s %s.</p><p class=\"lead\"><small>%s</small></p>",$judge_closed_000, $_SESSION['contestName'],$entry_count_text);
	    echo $archive_alert_display;
		echo "<h2>".$default_page_text_009."</h2>";
		include (PUB.'bos.pub.php');

		echo "<h2>".$default_page_text_010."</h2>";
		if ($winner_method == 0) include (PUB.'winners.pub.php');
		elseif ($winner_method == 1) include (PUB.'winners_category.pub.php');
		else include (PUB.'winners_subcategory.pub.php');
	}

	/*

	if ($go != "default") {

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
		//$winner_display_method = 0; // TODO: get from query
		//$_SESSION['prefsWinnerMethod'] = $winner_display_method; // TODO: change in appropriate query scripts (scores.db.php)

		$winner_method = $_SESSION['prefsWinnerMethod'];

		include (DB.'winners.db.php');
		if ($winner_method == 0) include (PUB.'winners.pub.php');
		if ($winner_method == 1) include (PUB.'winners_category.pub.php');
		if ($_SESSION['prefsWinnerMethod'] == 2) include (PUB.'winners_subcategory.pub.php');
		
	}

	*/

?>
