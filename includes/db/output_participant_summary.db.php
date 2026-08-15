<?php
// Check for Entries
$db_conn->where('brewBrewerID', $row_brewer['uid']);
$db_conn->where('brewReceived', '1');
$db_conn->orderBy('brewJudgingNumber', 'ASC');
$rows_log = $db_conn->get($prefix."brewing");
$row_log = ($rows_log && count($rows_log) > 0) ? $rows_log[0] : null;
$totalRows_log = $db_conn->count;

$query_organizer = "SELECT a.brewerFirstName,a.brewerLastName FROM ".$prefix."brewer"." a, ".$prefix."staff"." b WHERE a.uid = b.uid AND staff_organizer='1'";
$rows_organizer = $db_conn->rawQuery($query_organizer);
$row_organizer = ($rows_organizer && count($rows_organizer) > 0) ? $rows_organizer[0] : null;
$totalRows_organizer = $db_conn->count;
?>