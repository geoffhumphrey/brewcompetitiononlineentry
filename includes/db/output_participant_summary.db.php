<?php
// Check for Entries
$query_log = sprintf("SELECT * FROM $brewing_db_table WHERE brewBrewerID='%s' AND brewReceived='1' AND brewPaid='1'", $row_brewer['uid']);
$log = mysql_query($query_log, $brewing) or die(mysql_error());
$row_log = mysql_fetch_assoc($log);
$totalRows_log = mysql_num_rows($log);
?>