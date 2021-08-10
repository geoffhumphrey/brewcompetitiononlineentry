<?php
// Check for Entries
$query_log = sprintf("SELECT * FROM %s WHERE brewBrewerID='%s' AND brewReceived='1' ORDER BY brewJudgingNumber ASC", $prefix."brewing", $row_brewer['uid']);
$log = mysqli_query($connection,$query_log) or die (mysqli_error($connection));
$row_log = mysqli_fetch_assoc($log);
$totalRows_log = mysqli_num_rows($log);

$query_organizer = sprintf("SELECT a.brewerFirstName,a.brewerLastName FROM %s a, %s b WHERE a.uid = b.uid AND staff_organizer='1'", $prefix."brewer", $prefix."staff");
$organizer = mysqli_query($connection,$query_organizer) or die (mysqli_error($connection));
$row_organizer = mysqli_fetch_assoc($organizer);
$totalRows_organizer = mysqli_num_rows($organizer);
?>