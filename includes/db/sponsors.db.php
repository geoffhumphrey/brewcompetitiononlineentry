<?php
if (NHC) {
	// Place NHC SQL calls below
	
	
}
// end if (NHC)
else {
	
	if ($action == "edit") $query_sponsors = "SELECT * FROM $sponsors_db_table WHERE id='$id'"; 
	else $query_sponsors = "SELECT * FROM $sponsors_db_table ORDER BY sponsorLevel,sponsorName";
	$sponsors = mysql_query($query_sponsors, $brewing) or die(mysql_error());
	$row_sponsors = mysql_fetch_assoc($sponsors);
	$totalRows_sponsors = mysql_num_rows($sponsors);
}
?>