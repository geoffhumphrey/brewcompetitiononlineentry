<?php
if ($go == "judging_locations") $db_conn->where('tableLocation', $location);
if ($id != "default") $db_conn->where('id', $id);
else $db_conn->orderBy('tableNumber', 'ASC');
$rows_tables = $db_conn->get($prefix."judging_tables");
$row_tables = ($rows_tables && count($rows_tables) > 0) ? $rows_tables[0] : null;
$totalRows_tables = $db_conn->count;
?>