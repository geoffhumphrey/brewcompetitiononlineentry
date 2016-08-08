<?php
$query_sbd = sprintf("SELECT * FROM %s WHERE sid='%s' ORDER BY sbd_place ASC",$prefix."special_best_data",$row_sbi['id']);
$sbd = mysqli_query($connection,$query_sbd) or die (mysqli_error($connection));
$row_sbd = mysqli_fetch_assoc($sbd);
$totalRows_sbd = mysqli_num_rows($sbd);
?>