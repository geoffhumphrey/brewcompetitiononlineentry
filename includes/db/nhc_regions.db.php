<?php
if (NHC) {
	// Place NHC SQL calls below
	
	
}
// end if (NHC)

else {
	
	$query_regions = sprintf("SELECT * FROM %s",$prefix."Region");
	$query_regions .= " ORDER BY id ASC";
	$regions = mysql_query($query_regions, $brewing) or die(mysql_error());
	$row_regions = mysql_fetch_assoc($regions);
	$totalRows_regions = mysql_num_rows($regions);

}
?>