<?php

$db_conn->orderBy('brewerLastName', 'ASC');
$rows_sql = $db_conn->get($brewer_db_table);
$row_sql = ($rows_sql && count($rows_sql) > 0) ? $rows_sql[0] : null;

?>