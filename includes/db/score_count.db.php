<?php

if ($section == "past-winners") $query_scored_entries = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE scorePlace IS NOT NULL", $prefix."judging_scores_".$go);
else $query_scored_entries = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE scorePlace IS NOT NULL", $prefix."judging_scores");
$scored_entries = mysqli_query($connection,$query_scored_entries) or die (mysqli_error($connection));
$row_scored_entries = mysqli_fetch_assoc($scored_entries);

?>