<?php
/**
 * Module:      winners.db.php
 * Description: This script sets up variables for use in db queries for current
 *              and archived tables; performs all necessary db queries for winner
 *              display when NOT using BCOE for competition management.
 */


// Designate query variables depending upon db tables (current or archived)
if ($section == "past_winners") {
	$dbTable = $dbTable;
	$trimmed = ltrim($dbTable, "brewing_");
	$user_table = "users_".$trimmed;
	$brewer_table = "brewer_".$trimmed;
	$style_types = "style_types_".$trimmed;
	$judging_tables = "judging_tables_".$trimmed;
	$judging_scores = "judging_scores_".$trimmed;
	$judging_scores_bos = "judging_scores_bos_".$trimmed;
	$judging_locations = "judging_locations_".$trimmed;
	$judging_flights = "judging_flights_".$trimmed;
	$judging_assignments = "judging_tables_".$trimmed;
	}
else {
	$dbTable = "brewing";
	$user_table = "users";
	$brewer_table = "brewer";
	$style_types = "style_types";
	$judging_tables = "judging_tables";
	$judging_scores = "judging_scores";
	$judging_scores_bos = "judging_scores_bos";
	$judging_locations = "judging_locations";
	$judging_flights = "judging_flights";
	$judging_assignments = "judging_tables";
}

// General queries if using BCOE for comp organization
if ($row_prefs['prefsCompOrg'] == "Y") { 
	
	$query_tables = "SELECT * FROM $judging_tables ORDER BY tableNumber ASC";
	$tables = mysql_query($query_tables, $brewing) or die(mysql_error());
	$row_tables = mysql_fetch_assoc($tables);
	$totalRows_tables = mysql_num_rows($tables);
	
	$query_style_types = "SELECT * FROM $style_types";
	$style_types = mysql_query($query_style_types, $brewing) or die(mysql_error());
	$row_style_types = mysql_fetch_assoc($style_types);

}

// Use legacy code to display winners if admin chooses not to use BCOE for competition organization
if ($row_prefs['prefsCompOrg'] == "N") { 

	$query_log_winners = "SELECT * FROM $dbTable WHERE brewWinner='Y' ORDER BY brewWinnerCat, brewWinnerSubCat, brewWinnerPlace ASC";
	$log_winners = mysql_query($query_log_winners, $brewing) or die(mysql_error());
	$row_log_winners = mysql_fetch_assoc($log_winners);
	$totalRows_log_winners = mysql_num_rows($log_winners);

	$query_bos = "(SELECT * FROM $dbTable WHERE brewCategory >= '1' AND brewCategory <= '23' AND brewBOSRound='Y') UNION (SELECT * FROM $dbTable WHERE brewCategory >= '29' AND brewBOSRound='Y') ORDER BY brewBOSPlace ASC";
	$bos = mysql_query($query_bos, $brewing) or die(mysql_error());
	$row_bos = mysql_fetch_assoc($bos);
	$totalRows_bos = mysql_num_rows($bos);

	$query_bos_winner = "(SELECT * FROM $dbTable WHERE brewCategory >= '1' AND brewCategory <= '23' AND brewBOSRound='Y' AND brewBOSPlace='1') UNION (SELECT * FROM $dbTable WHERE brewCategory >= '29' AND brewBOSRound='Y' AND brewBOSPlace='1')";
	$bos_winner = mysql_query($query_bos_winner, $brewing) or die(mysql_error());
	$row_bos_winner = mysql_fetch_assoc($bos_winner);
	$totalRows_bos_winner = mysql_num_rows($bos_winner);

	$query_bos2 = "SELECT * FROM $dbTable WHERE (brewCategory >= '27' AND brewCategory <= '28') AND brewBOSRound='Y' ORDER BY brewBOSPlace ASC";
	$bos2 = mysql_query($query_bos2, $brewing) or die(mysql_error());
	$row_bos2 = mysql_fetch_assoc($bos2);
	$totalRows_bos2 = mysql_num_rows($bos2);

	$query_bos_winner2 = "SELECT * FROM $dbTable WHERE (brewCategory >= '27' AND brewCategory <= '28') AND brewBOSRound='Y' AND brewBOSPlace='1'";
	$bos_winner2 = mysql_query($query_bos_winner2, $brewing) or die(mysql_error());
	$row_bos_winner2 = mysql_fetch_assoc($bos_winner2);
	$totalRows_bos_winner2 = mysql_num_rows($bos_winner2);

	$query_bos3 = "SELECT * FROM $dbTable WHERE (brewCategory >= '24' AND brewCategory <= '26') AND  brewBOSRound='Y' ORDER BY brewBOSPlace ASC";
	$bos3 = mysql_query($query_bos3, $brewing) or die(mysql_error());
	$row_bos3 = mysql_fetch_assoc($bos3);
	$totalRows_bos3 = mysql_num_rows($bos3);

	$query_bos_winner3 = "SELECT * FROM $dbTable WHERE (brewCategory >= '24' AND brewCategory <= '26') AND brewBOSRound='Y' AND brewBOSPlace='1'";
	$bos_winner3 = mysql_query($query_bos_winner3, $brewing) or die(mysql_error());
	$row_bos_winner3 = mysql_fetch_assoc($bos_winner3);
	$totalRows_bos_winner3 = mysql_num_rows($bos_winner3);
}
?>