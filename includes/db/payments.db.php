<?php
$query_payments = sprintf("SELECT * FROM %s",$prefix."payments");
$payments = mysqli_query($connection,$query_payments) or die (mysqli_error($connection));
$row_payments = mysqli_fetch_assoc($payments);
$totalRows_payments = mysqli_num_rows($payments);
?>