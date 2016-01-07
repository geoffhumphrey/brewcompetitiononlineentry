<?php
if (NHC) {
	// Place NHC SQL calls below
	
	
}
// end if (NHC)
else {
	
	if ($dbTable == "default") $dbTable = $prefix."brewing";
	else $dbTable = $dbTable;
	// This query is ONLY used in the judging_scores function when entering scores for a particular table
	$query_entries = sprintf("SELECT id,brewBrewerID,brewStyle,brewCategorySort,brewCategory,brewSubCategory,brewInfo,brewJudgingNumber,brewName FROM %s WHERE (brewCategorySort='%s' AND brewSubCategory='%s') AND brewReceived='1'", $dbTable, $score_style_data[0], $score_style_data[1]);
	$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
	$row_entries = mysql_fetch_assoc($entries);
	$totalRows_entries = mysql_num_rows($entries);
}
?>