<?php
if ($section == "past_winners")$dbTable = $dbTable; else $dbTable = "brewing";
	if ($section == "past_winners") {
	$user_table = "users_".ltrim($dbTable, "brewing_");
	$brewer_table = "brewer_".ltrim($dbTable, "brewing_");
	}
	else {
	$user_table = "users";
	$brewer_table = "brewer";
	}

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
?>