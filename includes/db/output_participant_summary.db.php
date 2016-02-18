<?php
// Check for Entries
$query_log = sprintf("SELECT * FROM $brewing_db_table WHERE brewBrewerID='%s' AND brewReceived='1' AND brewPaid='1'", $row_brewer['uid']);
$log = mysqli_query($connection,$query_log) or die (mysqli_error($connection));
$row_log = mysqli_fetch_assoc($log);
$totalRows_log = mysqli_num_rows($log);
?>