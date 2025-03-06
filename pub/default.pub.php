<?php
/**
 * Module:      default.pub.php
 * Description: This module houses the intallation's landing page that includes
 *              information about the competition, registration dates/info, and
 *              winner display after all judging dates have passed.
 */

// Redirect if directly accessed
if ((!isset($_SESSION['prefs'.$prefix_session])) || ((isset($_SESSION['prefs'.$prefix_session])) && (!isset($base_url)))) {
    $redirect = "../../index.php";
    $redirect_go_to = sprintf("Location: %s", $redirect);
    header($redirect_go_to);
    exit();
}

$primary_page_info = "";
$page_info = "";
$page_info10 = "";
$page_info20 = "";
$page_info30 = "";
$header1_1 = "";
$header1_10 = "";
$header1_20 = "";
$header1_30 = "";

$show_at_a_glance = TRUE;

$message1 = sprintf("<div class=\"alert alert-warning\"><span class=\"fa fa-lg fa-exclamation-triangle\"> %s <a href='index.php?section=admin&amp;action=add&amp;go=dropoff'>%s</a></div>",$default_page_text_000,$default_page_text_001);
$message2 = sprintf("<div class=\"alert alert-warning\"><span class=\"fa fa-lg fa-exclamation-triangle\"> %s <a href='index.php?section=admin&amp;action=add&amp;go=judging'>%s</a></div>",$default_page_text_002,$default_page_text_003);

if (($judging_past == 0) && ($registration_open == 2) && ($entry_window_open == 2)) {

	include (PUB.'judge_closed.pub.php');
	include (DB.'winners.db.php');

	$style_types_active = styles_active(1,"default");

	$show_at_a_glance = FALSE;
	$bos_data_available = FALSE;

	$header1_10 .= "<header class=\"landing-page-section-header py-2\"><h1>".$label_results."</h1></header>";
	

	if ($row_bos_scores['count'] > 0) $bos_data_available = TRUE;

	if ($style_types_active > 0) {
		$header1_10 .= "<h2>".$default_page_text_009;
		if ($section == "past_winners") $header1_10 .= ": ".$trimmed;

		if ($bos_data_available) {
			if ($filter == "default") $header1_10 .= sprintf("<small><a class=\"hide-loader d-print-none\" href=\"%sincludes/output.inc.php?section=export-results&amp;go=judging_scores_bos&amp;action=download&amp;filter=default&amp;view=pdf\" data-bs-toggle=\"tooltip\" title=\"%s\" target=\"_blank\"><i class=\"fa fa-xs fa-fw fa-file-pdf ms-2 d-print-none\"></i></a><a class=\"hide-loader d-print-none\" href=\"%sincludes/output.inc.php?section=export-results&amp;go=judging_scores_bos&amp;action=download&amp;filter=default&amp;view=html\" data-bs-toggle=\"tooltip\" title=\"%s\" target=\"_blank\"><i class=\"fa fa-xs fa-fw fa-file-code d-print-none\"></i></a></small>",$base_url,$default_page_text_018,$base_url,$default_page_text_019);
		}

		$header1_10 .= "</h2>";

		if (!$bos_data_available) $page_info10 .= "<p>".$winners_text_005."</p>";
	}

	$header1_20 .= "<h2>".$default_page_text_010;
	if ($section == "past_winners") $header1_20 .= ": ".$trimmed;
	if ($filter == "default") $header1_20 .= sprintf("<small><a class=\"hide-loader d-print-none\" href=\"%sincludes/output.inc.php?section=export-results&amp;go=judging_scores&amp;action=default&amp;filter=default&amp;view=pdf\" data-bs-toggle=\"tooltip\" title=\"%s\" target=\"_blank\"><i class=\"fa fa-xs fa-fw fa-file-pdf ms-2 d-print-none\"></i></a><a class=\"hide-loader d-print-none\" href=\"%sincludes/output.inc.php?section=export-results&amp;go=judging_scores&amp;action=default&amp;filter=default&amp;view=html\" data-bs-toggle=\"tooltip\" title=\"%s\" target=\"_blank\"><i class=\"fa fa-xs fa-fw fa-file-code d-print-none\"></i></a>",$base_url,$default_page_text_020,$base_url,$default_page_text_021,$base_url,$default_page_text_021);
	$header1_20 .= "</h2>";

	$page_info .= sprintf("<h2>%s</h2><p>%s %s.</p>",$default_page_text_004,$default_page_text_005,getTimeZoneDateTime($_SESSION['prefsTimeZone'], $_SESSION['prefsWinnerDelay'], $_SESSION['prefsDateFormat'],  $_SESSION['prefsTimeFormat'], "long", "date-time"));

} // end if (($judging_past == 0) && ($registration_open == "2"))


else {

	/*
	if (!isset($_SESSION['loginUsername'])) {
		$page_info .= "<p class='lead'><small>".$default_page_text_011;
		if ($_SESSION['prefsPaypal'] == "Y") $page_info .= " ".$default_page_text_012;
		$page_info .= "</small></p>";
	}
	*/
	
}

// --------------------------------------------------------------
// Display
// --------------------------------------------------------------

//if ($action != "print") echo $print_page_link;

if ((isset($_SESSION['loginUsername'])) && ($_SESSION['userLevel'] <= "1") && ($section == "admin")) {
	if ($totalRows_dropoff == 0) echo $message1;
	if ($totalRows_judging == 0) echo $message2;
}

echo $primary_page_info;

if ($_SESSION['prefsProEdition'] == 1) $label_brewer = $label_organization; else $label_brewer = $label_brewer;

// Queries for current data
if ($filter == "default") {
	$winner_method = $_SESSION['prefsWinnerMethod'];
	$style_set = $_SESSION['prefsStyleSet'];
}

// Or, for archived data
else {

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

}

if (($judging_past == 0) && ($registration_open == 2) && ($entry_window_open == 2) && ($section !="past-winners")) {

	if ($_SESSION['prefsDisplayWinners'] == "Y") {

		include (DB.'score_count.db.php');

		if (judging_winner_display($_SESSION['prefsWinnerDelay'])) {

			if (((NHC) && ($prefix == "final_")) || (!NHC)) {
				echo $header1_10;
				echo $page_info10;
				include (PUB.'bos.pub.php');
			}

			if (($row_scored_entries['count'] > 0) && (($row_limits['prefsShowBestBrewer'] != 0) || ($row_limits['prefsShowBestClub'] != 0))) include (PUB.'bestbrewer.pub.php');

			echo $header1_20;
			if ($winner_method == "1") include (PUB.'winners_category.pub.php');
			elseif ($winner_method == "2") include (PUB.'winners_subcategory.pub.php');
			else include (PUB.'winners.pub.php');
		}

		else {
			if (isset($page_info)) echo $page_info;
			$show_at_a_glance = TRUE;
		}
	
	}

}

// If registration or entry window still open and the judging dates have not passed
else echo $page_info;

if ($show_at_a_glance) {
	echo "<div class=\"d-print-none\">";
	include (PUB.'at-a-glance.pub.php');
	echo "</div>";
}

?>