<?php
if (($action == "edit") && ($id != "default")) $db_conn->where('id', $id);
$rows_archive = $db_conn->get($archive_db_table);
$row_archive = ($rows_archive && count($rows_archive) > 0) ? $rows_archive[0] : null;
$totalRows_archive = $db_conn->count;
?>