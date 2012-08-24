<?php
$query_dropoff = "SELECT * FROM $drop_off_db_table";
if ($action == "edit") $query_dropoff .= " WHERE id='$id'";
$dropoff = mysql_query($query_dropoff, $brewing) or die(mysql_error());
$row_dropoff = mysql_fetch_assoc($dropoff);
$totalRows_dropoff = mysql_num_rows($dropoff);
?>