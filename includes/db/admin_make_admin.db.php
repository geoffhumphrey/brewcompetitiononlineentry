<?php
$db_conn->where('id', $row_brewer['uid']);
$row_username = $db_conn->getOne($users_db_table);
?>