<?php
if (NHC) {
	// Place NHC SQL calls below
	
	
}
// end if (NHC)

else {
	
	$query_archive = "SELECT id,archiveSuffix FROM $archive_db_table";
	$archive = mysql_query($query_archive, $brewing) or die(mysql_error());
	$row_archive = mysql_fetch_assoc($archive);
	$totalRows_archive = mysql_num_rows($archive);

}
?>