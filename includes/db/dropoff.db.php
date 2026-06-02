<?php
$query_dropoff = sprintf("SELECT * FROM %s",$drop_off_db_table);
if (($section == "admin") && ($action == "edit") && ($go == "dropoff")) $query_dropoff .= sprintf(" WHERE id='%s'",$id);
else $query_dropoff .= " ORDER BY dropLocationName ASC";
$dropoff = mysqli_query($connection,$query_dropoff) or die ("A database error occurred.");
$row_dropoff = mysqli_fetch_assoc($dropoff);
$totalRows_dropoff = mysqli_num_rows($dropoff);
?>