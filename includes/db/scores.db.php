<?php

$query_scores = sprintf("SELECT a.scorePlace, a.scoreEntry, b.brewName, b.brewCategory, b.brewCategorySort, b.brewSubCategory, b.brewStyle, b.brewCoBrewer, c.brewerLastName, c.brewerFirstName, c.brewerClubs FROM %s a, %s b, %s c WHERE scoreTable='%s' AND a.eid = b.id AND c.uid = b.brewBrewerID", $prefix."judging_scores", $prefix."brewing", $prefix."brewer", $row_tables['id']);
if ((($action == "print") && ($view == "winners")) || ($action == "default") || ($section == "default")) $query_scores .= " AND a.scorePlace IS NOT NULL";
$query_scores .= " ORDER BY a.scorePlace";
$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
$row_scores = mysql_fetch_assoc($scores);
$totalRows_scores = mysql_num_rows($scores);

?>