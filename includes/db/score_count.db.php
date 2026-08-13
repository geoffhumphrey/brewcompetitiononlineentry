<?php

if ($section == "past-winners") {
	$go_clean = preg_replace("/[^a-zA-Z0-9]+/", "", $go);
	$score_count_table = $prefix."judging_scores_".$go_clean;
}
else $score_count_table = $prefix."judging_scores";
$db_conn->where("scorePlace IS NOT NULL");
$row_scored_entries = $db_conn->getOne($score_count_table, "COUNT(*) as 'count'");

?>