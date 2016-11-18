<?php
if (SINGLE) $query_contest_info = sprintf("SELECT * FROM %s WHERE id='%s'", $prefix."contest_info", $_SESSION['comp_id']);
else $query_contest_info = sprintf("SELECT * FROM %s WHERE id=1", $prefix."contest_info");
$contest_info = mysqli_query($connection,$query_contest_info) or die (mysqli_error($connection));
$row_contest_info = mysqli_fetch_assoc($contest_info);
?>