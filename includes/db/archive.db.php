<?php
$query_archive = "SELECT * FROM $archive_db_table";
$archive = mysqli_query($connection,$query_archive) or die (mysqli_error($connection));
$row_archive = mysqli_fetch_assoc($archive);
$totalRows_archive = mysqli_num_rows($archive);
?>