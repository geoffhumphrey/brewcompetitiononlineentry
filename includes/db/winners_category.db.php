<?php

if (NHC) {
	// Place NHC SQL calls below
	
	
}
// end if (NHC)

else {

	$query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategorySort='%s' AND brewReceived='1'", $brewing_db_table,  $style);
	$entry_count = mysql_query($query_entry_count, $brewing) or die(mysql_error());
	$row_entry_count = mysql_fetch_assoc($entry_count);
	
	$query_score_count = sprintf("SELECT  COUNT(*) as 'count' FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND a.eid = b.id AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style);
	if (($action == "print") && ($view == "winners")) $query_score_count .= " AND a.scorePlace IS NOT NULL";
	if (($action == "default") && ($view == "default")) $query_score_count .= " AND a.scorePlace IS NOT NULL";
	$score_count = mysql_query($query_score_count, $brewing) or die(mysql_error());
	$row_score_count = mysql_fetch_assoc($score_count);

	//echo $row_score_count['count']."<br>";
	//echo $query_score_count;

}

?>