<?php
// The payments table is only created when an Admin enables the PayPal IPN
// preference (see includes/process/process_prefs.inc.php) - installs that
// never have are expected to not have this table yet.
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