<?php

if (NHC) {
	// Place NHC SQL calls below
	
	
}
// end if (NHC)

else {

	$query_entry_count = sprintf("SELECT COUNT(*) as 'count' FROM %s WHERE brewCategorySort='%s' AND brewSubCategory='%s' AND brewReceived='1'", $brewing_db_table,  $style[0], $style[1]);
	$entry_count = mysql_query($query_entry_count, $brewing) or die(mysql_error());
	$row_entry_count = mysql_fetch_assoc($entry_count);
	
	$query_score_count = sprintf("SELECT  COUNT(*) as 'count' FROM %s a, %s b, %s c WHERE b.brewCategorySort='%s' AND b.brewSubCategory='%s' AND a.eid = b.id AND a.scorePlace IS NOT NULL AND c.uid = b.brewBrewerID", $judging_scores_db_table, $brewing_db_table, $brewer_db_table, $style[0], $style[1]);
	$score_count = mysql_query($query_score_count, $brewing) or die(mysql_error());
	$row_score_count = mysql_fetch_assoc($score_count);
	
	//echo $row_score_count['count'];
	//echo $query_score_count;	

}

?>