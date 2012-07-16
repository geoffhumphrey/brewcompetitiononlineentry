<?php
/**
 * Module:      winners.db.php
 * Description: This script sets up variables for use in db queries for current
 *              and archived tables; performs all necessary db queries for winner
 *              display when NOT using BCOE for competition management.
 */


// General queries if using BCOE for comp organization
$query_sbi = "SELECT * FROM $special_best_info_db_table";
if ($action == "edit") $query_sbi .= " WHERE id='$id'"; else $query_sbi .= " ORDER BY sbi_rank ASC";
$sbi = mysql_query($query_sbi, $brewing) or die(mysql_error());
$row_sbi = mysql_fetch_assoc($sbi);
$totalRows_sbi = mysql_num_rows($sbi);

if ($row_prefs['prefsCompOrg'] == "Y") { 
	
	$query_tables = "SELECT * FROM $judging_tables_db_table ORDER BY tableNumber ASC";
	$tables = mysql_query($query_tables, $brewing) or die(mysql_error());
	$row_tables = mysql_fetch_assoc($tables);
	$totalRows_tables = mysql_num_rows($tables);
	
	$query_style_types = "SELECT * FROM $style_types_db_table";
	$style_types = mysql_query($query_style_types, $brewing) or die(mysql_error());
	$row_style_types = mysql_fetch_assoc($style_types);
	
	$query_scores = "SELECT COUNT(*) as 'count' FROM $judging_scores_db_table WHERE (scorePlace='1' OR scorePlace='2' OR scorePlace='3' OR scorePlace='4' OR scorePlace='5')";
	$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
	$row_scores = mysql_fetch_assoc($scores);
	
	$query_bos_scores = "SELECT COUNT(*) as 'count' FROM $judging_scores_bos_db_table WHERE (scorePlace='1' OR scorePlace='2' OR scorePlace='3' OR scorePlace='4' OR scorePlace='5')";
	$bos_scores = mysql_query($query_bos_scores, $brewing) or die(mysql_error());
	$row_bos_scores = mysql_fetch_assoc($bos_scores);

}

// Use legacy code to display winners if admin chooses not to use BCOE for competition organization
if ($row_prefs['prefsCompOrg'] == "N") { 

	$query_log_winners = "SELECT * FROM $brewing_db_table WHERE brewWinner='Y' ORDER BY brewWinnerCat, brewWinnerSubCat, brewWinnerPlace ASC";
	$log_winners = mysql_query($query_log_winners, $brewing) or die(mysql_error());
	$row_log_winners = mysql_fetch_assoc($log_winners);
	$totalRows_log_winners = mysql_num_rows($log_winners);

	$query_bos = "(SELECT * FROM $brewing_db_table WHERE brewCategory >= '1' AND brewCategory <= '23' AND brewBOSRound='Y') UNION (SELECT * FROM $brewing_db_table WHERE brewCategory >= '29' AND brewBOSRound='Y') ORDER BY brewBOSPlace ASC";
	$bos = mysql_query($query_bos, $brewing) or die(mysql_error());
	$row_bos = mysql_fetch_assoc($bos);
	$totalRows_bos = mysql_num_rows($bos);

	$query_bos_winner = "(SELECT * FROM $brewing_db_table WHERE brewCategory >= '1' AND brewCategory <= '23' AND brewBOSRound='Y' AND brewBOSPlace='1') UNION (SELECT * FROM $brewing_db_table WHERE brewCategory >= '29' AND brewBOSRound='Y' AND brewBOSPlace='1')";
	$bos_winner = mysql_query($query_bos_winner, $brewing) or die(mysql_error());
	$row_bos_winner = mysql_fetch_assoc($bos_winner);
	$totalRows_bos_winner = mysql_num_rows($bos_winner);

	$query_bos2 = "SELECT * FROM $brewing_db_table WHERE (brewCategory >= '27' AND brewCategory <= '28') AND brewBOSRound='Y' ORDER BY brewBOSPlace ASC";
	$bos2 = mysql_query($query_bos2, $brewing) or die(mysql_error());
	$row_bos2 = mysql_fetch_assoc($bos2);
	$totalRows_bos2 = mysql_num_rows($bos2);

	$query_bos_winner2 = "SELECT * FROM $brewing_db_table WHERE (brewCategory >= '27' AND brewCategory <= '28') AND brewBOSRound='Y' AND brewBOSPlace='1'";
	$bos_winner2 = mysql_query($query_bos_winner2, $brewing) or die(mysql_error());
	$row_bos_winner2 = mysql_fetch_assoc($bos_winner2);
	$totalRows_bos_winner2 = mysql_num_rows($bos_winner2);

	$query_bos3 = "SELECT * FROM $brewing_db_table WHERE (brewCategory >= '24' AND brewCategory <= '26') AND  brewBOSRound='Y' ORDER BY brewBOSPlace ASC";
	$bos3 = mysql_query($query_bos3, $brewing) or die(mysql_error());
	$row_bos3 = mysql_fetch_assoc($bos3);
	$totalRows_bos3 = mysql_num_rows($bos3);

	$query_bos_winner3 = "SELECT * FROM $brewing_db_table WHERE (brewCategory >= '24' AND brewCategory <= '26') AND brewBOSRound='Y' AND brewBOSPlace='1'";
	$bos_winner3 = mysql_query($query_bos_winner3, $brewing) or die(mysql_error());
	$row_bos_winner3 = mysql_fetch_assoc($bos_winner3);
	$totalRows_bos_winner3 = mysql_num_rows($bos_winner3);
}
?>