<?php

if ($action == "edit") $query_sponsors = "SELECT * FROM $sponsors_db_table WHERE id='$id'"; 
else $query_sponsors = "SELECT * FROM $sponsors_db_table ORDER BY sponsorLevel,sponsorName";
$sponsors = mysqli_query($connection,$query_sponsors) or die (mysqli_error($connection));
$row_sponsors = mysqli_fetch_assoc($sponsors);
$totalRows_sponsors = mysqli_num_rows($sponsors);

?>