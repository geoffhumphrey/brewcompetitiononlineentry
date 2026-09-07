<?php
// The payments table is created unconditionally at install/update time (GitHub issue #1523),
// but this guard is kept for installs that upgraded before that migration ran.
if (table_exists($prefix."payments")) {
	$rows_payments = $db_conn->get($prefix."payments");
	$row_payments = ($rows_payments && count($rows_payments) > 0) ? $rows_payments[0] : null;
	$totalRows_payments = $db_conn->count;
}
else {
	$rows_payments = array();
	$row_payments = null;
	$totalRows_payments = 0;
}
?>