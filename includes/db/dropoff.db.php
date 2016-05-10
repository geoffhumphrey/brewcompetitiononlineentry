<?php
$query_dropoff = "SELECT * FROM $drop_off_db_table";
if (($section == "admin") && ($action == "edit")) $query_dropoff .= " WHERE id='$id'";
else $query_dropoff .= " ORDER BY dropLocationName ASC";
$dropoff = mysqli_query($connection,$query_dropoff) or die (mysqli_error($connection));
$row_dropoff = mysqli_fetch_assoc($dropoff);
$totalRows_dropoff = mysqli_num_rows($dropoff);
?>