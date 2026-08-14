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
$filter_clean = preg_replace("/[^a-zA-Z0-9]+/", "", $filter);
$db_conn->where("archiveSuffix", $filter);
$row_archive_prefs = $db_conn->getOne($prefix."archive");
$totalRows_archive_prefs = $db_conn->count;

$winner_method = $row_archive_prefs['archiveWinnerMethod'];
$style_set = $row_archive_prefs['archiveStyleSet'];
$judging_scores_db_table = $prefix."judging_scores_".$filter_clean;
$brewing_db_table = $prefix."brewing_".$filter_clean;
$brewer_db_table = $prefix."brewer_".$filter_clean;

// $filter is a separate request parameter from $go (the archive suffix already validated
// upstream), so it isn't guaranteed to point at tables that actually exist.
$archive_tables_exist = (table_exists($judging_scores_db_table)) && (table_exists($brewing_db_table)) && (table_exists($brewer_db_table));

	if ((!empty($archive_alert_display)) && ($archive_tables_exist)) {

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

?>
