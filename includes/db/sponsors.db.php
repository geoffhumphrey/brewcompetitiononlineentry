<?php

if ($action == "edit") $query_sponsors = "SELECT * FROM $sponsors_db_table WHERE id='$id'"; 
else {
	if ($dbTable == "default") $sponsors_db_table = $prefix."sponsors";
	if ($dbTable != "default") $sponsors_db_table = $dbTable;
	$query_sponsors = sprintf("SELECT * FROM %s", $sponsors_db_table);	
}
$sponsors = mysqli_query($connection,$query_sponsors) or die (mysqli_error($connection));
$row_sponsors = mysqli_fetch_assoc($sponsors);
$totalRows_sponsors = mysqli_num_rows($sponsors);

?>