<?php
declare(strict_types=1);
$rows_payments = $db_conn->get($prefix."payments");
$row_payments = ($rows_payments && count($rows_payments) > 0) ? $rows_payments[0] : null;
$totalRows_payments = $db_conn->count;
?>