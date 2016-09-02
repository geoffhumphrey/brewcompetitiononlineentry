<?php

$query_scores = sprintf("SELECT a.scorePlace, a.scoreEntry, b.brewCoBrewer, c.uid, c.brewerLastName, c.brewerFirstName, c.brewerClubs FROM %s a, %s b, %s c WHERE a.eid = b.id AND c.uid = b.brewBrewerID AND a.scorePlace IS NOT NULL ", $judging_scores_db_table, $brewing_db_table, $brewer_db_table);

//if ((($action == "print") && ($view == "winners")) || ($action == "default") || ($section == "default")) $query_scores .= " AND a.scorePlace IS NOT NULL";

//$query_scores .= " ORDER BY a.scorePlace ASC";

$bb_scores = mysqli_query($connection, $query_scores) or die(mysqli_error($connection));
$bb_row_scores = mysqli_fetch_assoc($bb_scores);
$bb_totalRows_scores = mysqli_num_rows($bb_scores);

?>