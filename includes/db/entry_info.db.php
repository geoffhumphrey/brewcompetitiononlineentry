<?php
if (SINGLE) $db_conn->where('id', $_SESSION['comp_id']);
else $db_conn->where('id', 1);
$row_contest_info = $db_conn->getOne($prefix."contest_info");
?>