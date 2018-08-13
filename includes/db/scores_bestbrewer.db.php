<?php
$query_bb_prefs = sprintf("SELECT prefsBestBrewerTitle, prefsBestClubTitle, prefsFirstPlacePts, prefsSecondPlacePts, prefsThirdPlacePts, prefsFourthPlacePts, prefsHMPts, prefsTieBreakRule1, prefsTieBreakRule2, prefsTieBreakRule3, prefsTieBreakRule4, prefsTieBreakRule5, prefsTieBreakRule6, prefsBestUseBOS FROM %s WHERE id='1'", $prefix."preferences");
$bb_prefs = mysqli_query($connection,$query_bb_prefs) or die (mysqli_error($connection));
$row_bb_prefs = mysqli_fetch_assoc($bb_prefs);

$query_scores = sprintf("SELECT a.scorePlace, a.scoreEntry, b.brewCoBrewer, c.uid, c.brewerLastName, c.brewerFirstName, c.brewerBreweryName, c.brewerClubs FROM %s a, %s b, %s c WHERE a.eid = b.id AND c.uid = b.brewBrewerID AND a.scorePlace IS NOT NULL ", $judging_scores_db_table, $brewing_db_table, $brewer_db_table);
$bb_scores = mysqli_query($connection, $query_scores) or die(mysqli_error($connection));
$bb_row_scores = mysqli_fetch_assoc($bb_scores);
$bb_totalRows_scores = mysqli_num_rows($bb_scores);

if ($row_bb_prefs['prefsBestUseBOS'] == 1) {
    $query_bos_scores = sprintf("SELECT a.scorePlace, a.scoreEntry, c.brewerClubs, c.uid FROM %s a, %s b, %s c WHERE a.eid = b.id AND c.uid = a.bid AND a.scorePlace IS NOT NULL", $judging_scores_bos_db_table, $brewing_db_table, $brewer_db_table);
    $bb_bos_scores = mysqli_query($connection, $query_bos_scores) or die(mysqli_error($connection));
    $bb_row_bos_scores = mysqli_fetch_assoc($bb_bos_scores);
    $bb_totalRows_bos_scores = mysqli_num_rows($bb_bos_scores);
}



$bb_points_prefs = array($row_bb_prefs['prefsFirstPlacePts'],$row_bb_prefs['prefsSecondPlacePts'],$row_bb_prefs['prefsThirdPlacePts'],$row_bb_prefs['prefsFourthPlacePts'],$row_bb_prefs['prefsHMPts']);

$bb_tiebreaker_prefs = array($row_bb_prefs['prefsTieBreakRule1'],$row_bb_prefs['prefsTieBreakRule2'],$row_bb_prefs['prefsTieBreakRule3'],$row_bb_prefs['prefsTieBreakRule4'],$row_bb_prefs['prefsTieBreakRule5'],$row_bb_prefs['prefsTieBreakRule6']);
?>