<?php

$query_payments = "SELECT * FROM $payments_db_table";
$payments = mysqli_query($connection,$query_payments) or die (mysqli_error($connection));
$row_payments = mysqli_fetch_assoc($payments);
$totalRows_payments = mysqli_num_rows($payments);

?>