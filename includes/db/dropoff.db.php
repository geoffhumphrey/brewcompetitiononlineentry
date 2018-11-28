<?php
$query_dropoff = sprintf("SELECT * FROM %s",$drop_off_db_table);
if (SINGLE) {
	$query_dropoff .= sprintf(" WHERE comp_id='%s'",$_SESSION['comp_id']);
	if (($section == "admin") && ($action == "edit")) $query_dropoff .= sprintf(" AND id='%s'",$id);
    else $query_dropoff .= " ORDER BY dropLocationName ASC";
}
else {
	if (($section == "admin") && ($action == "edit")) $query_dropoff .= sprintf(" WHERE id='%s'",$id);
    else $query_dropoff .= " ORDER BY dropLocationName ASC";
}
$dropoff = mysqli_query($connection,$query_dropoff) or die (mysqli_error($connection));
$row_dropoff = mysqli_fetch_assoc($dropoff);
$totalRows_dropoff = mysqli_num_rows($dropoff);
?>