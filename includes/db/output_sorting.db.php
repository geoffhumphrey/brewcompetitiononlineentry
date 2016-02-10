<?php
if (NHC) {
	// Place NHC SQL calls below
	
	
}
// end if (NHC)

else {

	$query_entries = sprintf("SELECT id,brewName,brewStyle,brewCategory,brewCategorySort,brewSubCategory,brewBrewerLastName,brewBrewerFirstName,brewBrewerID,brewJudgingNumber,brewPaid,brewReceived FROM $brewing_db_table WHERE brewCategorySort='%s'", $style);
	$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
	$row_entries = mysql_fetch_assoc($entries);
	$totalRows_entries = mysql_num_rows($entries);

}

?>